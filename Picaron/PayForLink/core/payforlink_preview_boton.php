<?php
/**
 * PayForLink extension for the phpBB >=3.2.0 Forum Software package.
 * @copyright (c) 2017 Picaron
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace Picaron\PayForLink\core;

class payforlink_preview_boton
{
    /** @var \phpbb\auth\auth */
    protected $auth;

    /** @var \phpbb\template\template */
    protected $template;

    /** @var \phpbb\user */
    protected $user;

    /** @var \phpbb\db\driver\driver_interface */
    protected $db;

    /** @var \phpbb\db\tools\tools_interface */
    protected $db_tools;

    /** @var \phpbb\config\config */
    protected $config;

    /** @var string phpBB root path */
    protected $root_path;

    /**
    * The database tables
    *
    * @var string
    */
    protected $payforlink_table;


    /**
    * Constructor
    *
    * @param \phpbb\auth\auth                   $auth
    * @param \phpbb\template\template           $template
    * @param \phpbb\user                        $user
    * @param \phpbb\db\driver\driver_interface  $db
    * @param \phpbb\db\tools\tools_interface    $db_tools
    * @param \phpbb\config\config               $config
    * @param string                             $root_path
    * @param string                             $payforlink_table
    *
    */
    public function __construct(
        \phpbb\auth\auth $auth,
        \phpbb\template\template $template,
        \phpbb\user $user,
        \phpbb\db\driver\driver_interface $db,
        \phpbb\db\tools\tools_interface $db_tools,
        \phpbb\config\config $config,
        $root_path,
        $payforlink_table
    )
    {
        $this->auth                         = $auth;
        $this->template                     = $template;
        $this->user                         = $user;
        $this->db                           = $db;
        $this->db_tools                     = $db_tools;
        $this->config                       = $config;
        $this->root_path                    = $root_path;
        $this->payforlink_table             = $payforlink_table;
    }

    function main($urlcodec)
    {
        $cat_url     = $urlcodec;
        $goback_url  = strrev($cat_url);
        $clear_url   = $this->DecodeID($goback_url);
        $datos       = explode('|||',$clear_url);
        $url         = isset($datos[0]) ? $datos[0] : '';
        $precio      = isset($datos[1]) ? $datos[1] : '';
        $id_prop     = isset($datos[2]) ? $datos[2] : '';
        $id_conec    = $this->user->data['user_id'];

        $theme_path  = generate_board_url()."/ext/Picaron/PayForLink/styles/all/theme/" . $this->user->lang_name . '/';
        $enl_desc    = "{$this->root_path}payforlink?mode=download&urlcodec=" . $cat_url;

        // Verificamos que los datos sean validos
        if (($precio < 1) or ($precio > 150) or (substr($url, 0, 4) != "http"))
        {
            $titulo = $this->user->lang['LINK_ADD_ERROR'];
            $download = '<b><font color="#990000" size="2" face="Geneva, Arial, Helvetica, sans-serif">'.$this->user->lang['LINK_ADD_ERROR'].'</font></b>';
            $text_precio = '<font color="#990000" size="1" face="Geneva, Arial, Helvetica, sans-serif">'.$this->user->lang['LINK_ADD_ERROR_NOTIFY'].'</font>';
        } else {
            // Check if donwload free system is enabled and exist column 'user_points'
            if ((!$this->config['display_payforlink_show']) and ($this->db_tools->sql_column_exists(USERS_TABLE, 'user_points')))
            {
                // Verificamos si el usuario conectado ha comprado anteriormente el enlace
                $user_buy = '';
                $sql = "SELECT link_url FROM " . $this->payforlink_table . " WHERE link_url = '$cat_url' AND user_id_buy = " . $id_conec;
                $result = $this->db->sql_query($sql);
                $user_buy = $this->db->sql_fetchfield('link_url');
                $this->db->sql_freeresult($result);

                // El usuario NO tiene comprado el enlace (continua el proceso de pago)
                if ($user_buy == '')
                {
                    // Si el usuario conectado es el propietario de la descarga, no suma ni resta puntos
                    if ($id_conec != $id_prop)
                    {
                        $sql = 'SELECT user_points
                            FROM ' . USERS_TABLE . '
                            WHERE user_id = ' . (int) $id_conec;
                        $result = $this->db->sql_query($sql);
                        $user_points = (int) $this->db->sql_fetchfield('user_points');
                        $this->db->sql_freeresult($result);

                        if ($this->user->data['user_points'] < $precio)
                        {
                            $download = '<img src="'. $theme_path . 'button_locked.png" title="'.$this->user->lang['LINK_ADD_NO_POINTS'].'" alt="'.$this->user->lang['LINK_ADD_NO_POINTS'].'"></img>';
                            $titulo = $this->user->lang['LINK_ADD_DISABLE'];
                            $text_precio = '<font color="#990000" size="2" face="Geneva, Arial, Helvetica, sans-serif">'.$this->user->lang['LINK_ADD_PRICE'].$precio.$this->user->lang['LINK_ADD_POINTS'].'</font><br>
                                            <font color="#0000CC" size="2" face="Geneva, Arial, Helvetica, sans-serif">'.$this->user->lang['LINK_ADD_CASH'].$user_points.$this->user->lang['LINK_ADD_POINTS'].'</font>';
                        } else {
                            $links = $this->Return_Substrings($url, '//', '/');
                            $download = '<a href="'.$enl_desc.'" title="'.$this->user->lang['LINK_ADD_HOSTING'].$links[0].'" style="background-image:url('.$theme_path.'button_preview.png);width:210px;height:40px;display:block;"></a>';
                            $titulo = $this->user->lang['LINK_ADD_REST_POINTS'].$precio.$this->user->lang['LINK_ADD_POINTS'];
                            $text_precio = '<font color="#006600" size="2" face="Geneva, Arial, Helvetica, sans-serif">'.$this->user->lang['LINK_ADD_PRICE'].$precio.$this->user->lang['LINK_ADD_POINTS'].'</font><br>
                                            <font color="#0000CC" size="2" face="Geneva, Arial, Helvetica, sans-serif">'.$this->user->lang['LINK_ADD_CASH'].$user_points.$this->user->lang['LINK_ADD_POINTS'].'</font>';
                        }

                    } else {
                        // El usuario conectado es el propietario de la descarga
                        $download = '<a href="'.$enl_desc.'" title="'.$this->user->lang['LINK_ADD_FREE'].'" style="background-image:url('.$theme_path.'button_free_link.png);width:210px;height:40px;display:block;"></a>';
                        $titulo = $this->user->lang['LINK_ADD_FREE_FILE'];
                        $text_precio = '<font color="#0000CC" size="2" face="Geneva, Arial, Helvetica, sans-serif">'.$this->user->lang['LINK_ADD_PRICE'].$precio.$this->user->lang['LINK_ADD_POINTS'].'<br>'.$this->user->lang['LINK_ADD_OWNER'].'</font>';
                    }

                } else {
                // El usuario SI tiene comprado el enlace (se facilita el enlace de descarga directamente)
                $download = '<a href="'.$enl_desc.'" title="'.$this->user->lang['LINK_ADD_NO_PAY'].'" style="background-image:url('.$theme_path.'button_free_link.png);width:210px;height:40px;display:block;"></a>';
                $titulo = $this->user->lang['LINK_ADD_FREE_FILE_DOWN'];
                $text_precio = '<font color="#006600" size="1" face="Geneva, Arial, Helvetica, sans-serif">'.$this->user->lang['LINK_ADD_USER_BUY'].$precio.$this->user->lang['LINK_ADD_POINTS'].')<br>'.$this->user->lang['LINK_ADD_NOT_ADD_REST'].'</font>';
                }

            } else {
            // Como esta Habilitada la opcion de descarga free o NO existe la columna 'user_points' se facilita el enlace de descarga directamente
            $download = '<a href="'.$enl_desc.'" title="'.$this->user->lang['LINK_ADD_NO_PAY'].'" style="background-image:url('.$theme_path.'button_free_link.png);width:210px;height:40px;display:block;"></a>';
            $titulo = $this->user->lang['LINK_ADD_FREE_FILE_DOWN'];
            $text_precio = '<font color="#0000CC" size="2" face="Geneva, Arial, Helvetica, sans-serif">'.$this->user->lang['LINK_ADD_FREE_DOWN'].'</font>';
            }

        }

        // Exclude Bots & Chequea que el usuario conectado tenga acceso a los puntos
        if (($this->user->data['is_bot']) or (!$this->auth->acl_get('u_use_points')))
        {
            $titulo = $this->user->lang['LINK_ADD_ERROR'];
            $download = '<b><font color="#990000" size="2" face="Geneva, Arial, Helvetica, sans-serif">'.$this->user->lang['LINK_ADD_ERROR'].'</font></b>';
            $text_precio = '<font color="#990000" size="1" face="Geneva, Arial, Helvetica, sans-serif">'.$this->user->lang['LINK_ADD_ERROR_NOTIFY'].'</font>';
        }

        $this->template->assign_vars(array(
            'LINK_UP_TITLE'     => $titulo,
            'LINK_DOWNLOAD'     => $download,
            'LINK_TEXT_PRICE'   => $text_precio,
        ));

        // Generate the page
        page_header($this->user->lang['ADD_LINK']);

        // Generate the page template
        $this->template->set_filenames(array(
            'body'  => 'payforlink/payforlink_boton.html'
        ));

        page_footer();
    }

    function DecodeID($string)
    {
        $decode = str_replace("cDFjNHIw","",$string);
        $decode = str_replace("RWwzVjR0","",$decode);
        $decoded = base64_decode(str_pad(strtr($decode, '-_', '+/'), strlen($decode) % 4, '=', STR_PAD_RIGHT));
        return $decoded;
    }

    function Return_Substrings($text, $sopener, $scloser)
    {
        $result = array();

        $noresult = substr_count($text, $sopener);
        $ncresult = substr_count($text, $scloser);

        if ($noresult < $ncresult)
        {
            $nresult = $noresult;
        } else {
            $nresult = $ncresult;
        }

        unset($noresult);
        unset($ncresult);

        for ($i=0;$i<$nresult;$i++)
            {
                $pos = strpos($text, $sopener) + strlen($sopener);
                $text = substr($text, $pos, strlen($text));
                $pos = strpos($text, $scloser);
                $result[] = substr($text, 0, $pos);
                $text = substr($text, $pos + strlen($scloser), strlen($text));
            }
        return $result;
    }
}
?>