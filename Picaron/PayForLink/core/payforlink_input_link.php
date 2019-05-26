<?php
/**
 * PayForLink extension for the phpBB >=3.2.0 Forum Software package.
 * @copyright (c) 2017 Picaron
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace Picaron\PayForLink\core;

class payforlink_input_link
{
    /** @var \phpbb\auth\auth */
    protected $auth;

    /** @var \phpbb\template\template */
    protected $template;

    /** @var \phpbb\user */
    protected $user;

    /** @var string */
    protected $php_ext;

    /** @var string phpBB root path */
    protected $root_path;

    /**
    * Constructor
    *
    * @param \phpbb\auth\auth                   $auth
    * @param \phpbb\template\template           $template
    * @param \phpbb\user                        $user
    * @param string                             $php_ext
    * @param string                             $root_path
    *
    */
    public function __construct(
        \phpbb\auth\auth $auth,
        \phpbb\template\template $template,
        \phpbb\user $user,
        $php_ext,
        $root_path
    )
    {
        $this->auth                         = $auth;
        $this->template                     = $template;
        $this->user                         = $user;
        $this->php_ext                      = $php_ext;
        $this->root_path                    = $root_path;
    }

    function main()
    {
        $this->template->assign_vars(array(
            'ACCESO'            => $this->auth->acl_getf_global('m_') ? true : false,
            'DATOS'             => $this->user->data['username'],
            'U_MAKE'            => "{$this->root_path}app.php/payforlink?mode=make",
            'U_FIND_USERNAME'   => "{$this->root_path}memberlist.{$this->php_ext}?mode=searchuser&form=postform&field=payforlinkpropiet&select_single=true",
        ));

        // Generate the page
        page_header($this->user->lang['ADD_LINK']);

        // Generate the page template
        $this->template->set_filenames(array(
            'body'  => 'payforlink/payforlink_input_link.html'
        ));

        page_footer();
    }
}
?>