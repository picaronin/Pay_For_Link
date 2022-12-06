<?php
/**
 * PayForLink extension for the phpBB >=3.3.0 Forum Software package.
 * @copyright (c) 2022 Picaron
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace pikaron\payforlink\core;

class payforlink_make_link
{
    /** @var \phpbb\auth\auth */
    protected $auth;

    /** @var \phpbb\template\template */
    protected $template;

    /** @var \phpbb\user */
    protected $user;

    /** @var \phpbb\db\driver\driver_interface */
    protected $db;

    /** @var \phpbb\request\request */
    protected $request;

    /** @var string phpBB root path */
    protected $root_path;

    /**
    * Constructor
    *
    * @param \phpbb\auth\auth                   $auth
    * @param \phpbb\template\template           $template
    * @param \phpbb\user                        $user
    * @param \phpbb\db\driver\driver_interface  $db
    * @param \phpbb\request\request             $request
    * @param string                             $root_path
    *
    */
    public function __construct(
        \phpbb\auth\auth $auth,
        \phpbb\template\template $template,
        \phpbb\user $user,
        \phpbb\db\driver\driver_interface $db,
        \phpbb\request\request $request,
        $root_path
    )
    {
        $this->auth                         = $auth;
        $this->template                     = $template;
        $this->user                         = $user;
        $this->db                           = $db;
        $this->request                      = $request;
        $this->root_path                    = $root_path;
    }

    function main()
    {
        $link               = trim($this->request->variable('payforlinklink', ''));
        $points             = (int) $this->request->variable('payforlinkpoints', '');
        $id_prop            = (int) $this->user->data['user_id'];
        $payforlinklink     = $link;

        // Fix cost
        if (($points < 1) or ($points > 150)) { $points = 40; }

        // Verify is connect user are a moderator
        if ($this->auth->acl_getf_global('m_'))
        {
            $propietario = $this->request->variable('payforlinkpropiet', '');
            if ($propietario)
            {
                // Take ID of new user
                $sql3 = "SELECT * FROM " . USERS_TABLE . " WHERE username = '$propietario'";
                $result3 = $this->db->sql_query($sql3);
                $conec = $this->db->sql_fetchrow($result3);
                $this->db->sql_freeresult($result3);
                if ($conec['username']) { $id_prop  = (int) $conec['user_id']; }
            }
        }

        // Check URL
        if ((substr($link,0,8) != "https://") and (substr($link,0,7) != "http://")) { $link = "http://".$link; }

        // Encode link
        $addlink        = $link.'|||'.$points.'|||'.$id_prop;
        $addlinkcodec   = $this->EncodeID($addlink);
        $linkok         = '[payforlink]'. strrev($addlinkcodec) .'[/payforlink]';

        $this->template->assign_vars(array(
            'ERRORES'     => $payforlinklink == '' ? true : false,
            'T_ERROR'     => $this->user->lang['LINK_ADD_ALERT_URL'],
            'U_MAKE'      => "{$this->root_path}app.php/payforlink?mode=input",
            'LINK_OK'     => $linkok,
        ));

        // Generate the page
        page_header($this->user->lang['ADD_LINK']);

        // Generate the page template
        $this->template->set_filenames(array(
            'body'  => 'payforlink/payforlink_make_link.html'
        ));

        page_footer();
    }

    function EncodeID($string)
    {
        $encode = rtrim(strtr(base64_encode($string), '+/', '-_'), '=');
        $encode = $encode[0].rtrim(strtr(base64_encode('p1c4r0'), '+/', '-_'), '=').substr($encode,1);
        $encode = substr($encode,0,strlen($encode)-2).rtrim(strtr(base64_encode('El3V4t'), '+/', '-_'), '=').substr($encode,strlen($encode)-2,2);
        return $encode;
    }
}
?>