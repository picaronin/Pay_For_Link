<?php
/**
 * PayForLink extension for the phpBB >=3.2.0 Forum Software package.
 * @copyright (c) 2017 Picaron
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace Picaron\PayForLink\core;

class payforlink_download_boton
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
        $payforlink_table
    )
    {
        $this->auth                         = $auth;
        $this->template                     = $template;
        $this->user                         = $user;
        $this->db                           = $db;
        $this->db_tools                     = $db_tools;
        $this->config                       = $config;
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
        $ganar       = (int) ($precio/2);
        $theme_path  = generate_board_url()."/ext/Picaron/PayForLink/styles/all/theme/" . $this->user->lang_name . '/';

        // Verificamos que los datos sean validos
        if (($precio < 1) or ($precio > 150) or (substr($url, 0, 4) != "http"))
        {
            $download = '<b><font color="#990000" size="2" face="Geneva, Arial, Helvetica, sans-serif">'.$this->user->lang['LINK_ADD_ERROR'].'</font></b>';
            $text_precio = '<font color="#990000" size="1" face="Geneva, Arial, Helvetica, sans-serif">'.$this->user->lang['LINK_ADD_ERROR_NOTIFY'].'</font>';
        } else {

            $sql = 'SELECT username
                FROM ' . USERS_TABLE . '
                WHERE user_id = ' . (int) $id_prop;
            $result = $this->db->sql_query($sql);
            $row = $this->db->sql_fetchrow($result);
            $this->db->sql_freeresult($result);

            // Check if donwload free system is enabled and exist column 'user_points'
            if ((!$this->config['display_payforlink_show']) and ($this->db_tools->sql_column_exists(USERS_TABLE, 'user_points')))
            {
                // Verificamos si el usuario ha comprado anteriormente el enlace
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

                        if ($this->user->data['user_points'] < $precio)
                        {
                            $download = '<img src="'. $theme_path . 'button_locked_no_points.png" title="'.$this->user->lang['LINK_ADD_NO_POINTS'].'" alt="'.$this->user->lang['LINK_ADD_NO_POINTS'].'"></img>';
                            $titulo = $this->user->lang['LINK_ADD_DISABLE'];
                            $text_precio = '<font color="#990000" size="2" face="Geneva, Arial, Helvetica, sans-serif">'.$this->user->lang['LINK_ADD_PRICE'].$precio.$this->user->lang['LINK_ADD_POINTS'].'</font><br>
                                            <font color="#0000CC" size="2" face="Geneva, Arial, Helvetica, sans-serif">'.$this->user->lang['LINK_ADD_CASH'].$user_points.$this->user->lang['LINK_ADD_POINTS'].'</font>';
                        } else {
                            $download = '<a href="'.$url.'" target="_blank" title="'.$this->user->lang['LINK_ADD_ENABLED'].'" style="background-image:url('.$theme_path.'button_download.png);width:210px;height:40px;display:block;"></a>';
                            $titulo = $this->user->lang['LINK_ADD_LINK_FILE'];
                            $text_precio = '<font color="#006600" size="2" face="Geneva, Arial, Helvetica, sans-serif">'.$this->user->lang['LINK_ADD_WILL_REST'].$precio.$this->user->lang['LINK_ADD_P_ME_ACCOUNT'].'</font><br>
                                            <font color="#0000CC" size="2" face="Geneva, Arial, Helvetica, sans-serif">'.$this->user->lang['LINK_ADD_WILL_ADD'].$ganar.$this->user->lang['LINK_ADD_P_HE_ACCOUNT'].$row['username'].'</font>';

                                // Resta puntos al usuario que descarga
                                $sql = 'UPDATE ' . USERS_TABLE . '
                                    SET user_points = user_points - ' . $precio . '
                                    WHERE user_id = ' . (int) $id_conec;
                                $this->db->sql_query($sql);

                                if ($row['username'] != '')
                                {
                                    // Suma puntos por el 50% del valor de la descarga al creador del enlace
                                    $sql = 'UPDATE ' . USERS_TABLE . '
                                        SET user_points = user_points + ' . $ganar . '
                                        WHERE user_id = ' . (int) $id_prop;
                                    $this->db->sql_query($sql);
                                }

                                // Guardamos la compra
                                $sql = 'INSERT INTO ' . $this->payforlink_table .' ' . $this->db->sql_build_array('INSERT', array(
                                    'user_id_buy'   => (int) $id_conec,
                                    'link_url'      => $cat_url,
                                    'link_cost'     => $precio,
                                    'user_id_prop'  => $id_prop)
                                );
                                $this->db->sql_query($sql);
                        }

                    } else {
                        // El usuario conectado es el propietario de la descarga
                        $download = '<a href="'.$url.'" target="_blank" title="'.$this->user->lang['LINK_ADD_FREE'].'" style="background-image:url('.$theme_path.'button_download.png);width:210px;height:40px;display:block;"></a>';
                        $titulo = $this->user->lang['LINK_ADD_LINK_FILE'];
                        $text_precio = '<font color="#0000CC" size="2" face="Geneva, Arial, Helvetica, sans-serif">'.$this->user->lang['LINK_ADD_OWNER'].'<br>'.$this->user->lang['LINK_ADD_NOT_ADD_REST'].'</font>';
                    }

                } else {
                    // El usuario SI tiene comprado el enlace (se facilita el enlace de descarga directamente)
                    $download = '<a href="'.$url.'" target="_blank" title="'.$this->user->lang['LINK_ADD_NO_PAY'].'" style="background-image:url('.$theme_path.'button_download.png);width:210px;height:40px;display:block;"></a>';
                    $titulo = $this->user->lang['LINK_ADD_FREE_FILE_DOWN'];
                    $text_precio = '<font color="#006600" size="1" face="Geneva, Arial, Helvetica, sans-serif">'.$this->user->lang['LINK_ADD_USER_BUY'].$precio.$this->user->lang['LINK_ADD_POINTS'].')<br>'.$this->user->lang['LINK_ADD_NOT_ADD_REST'].'</font>';
                }

            } else {
                // Como esta Habilitada la opcion de descarga free o NO existe la columna 'user_points'
                // se muestra el enlace de descarga directamente y NO se restan NI suman puntos
                $download = '<a href="'.$url.'" target="_blank" title="'.$this->user->lang['LINK_ADD_FREE'].'" style="background-image:url('.$theme_path.'button_download.png);width:210px;height:40px;display:block;"></a>';
                $titulo = $this->user->lang['LINK_ADD_LINK_FILE'];
                $text_precio = '<font color="#0000CC" size="2" face="Geneva, Arial, Helvetica, sans-serif">'.$this->user->lang['LINK_ADD_ENABLED'].'</font>';
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
}
?>