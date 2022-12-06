<?php
/**
 * PayForLink extension for the phpBB >=3.3.0 Forum Software package.
 * @copyright (c) 2022 Picaron
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace pikaron\payforlink\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
    /** @var \phpbb\user */
    protected $user;

    /** @var \phpbb\db\driver\driver_interface */
    protected $db;

    /** @var \phpbb\db\tools\tools_interface */
    protected $db_tools;

    /** @var \phpbb\template\template */
    protected $template;

    /**
    * Constructor
    *
    * @param \phpbb\user                        $user
    * @param \phpbb\db\driver\driver_interface  $db
    * @param \phpbb\db\tools\tools_interface    $db_tools
    * @param \phpbb\template\template           $template
    *
    */
    public function __construct(
        \phpbb\user $user,
        \phpbb\db\driver\driver_interface $db,
        \phpbb\db\tools\tools_interface $db_tools,
        \phpbb\template\template $template
    )
    {
        $this->user         = $user;
        $this->db           = $db;
        $this->db_tools     = $db_tools;
        $this->template     = $template;
    }

    static public function getSubscribedEvents()
    {
        return array(
            'core.user_setup'                   => 'load_language_on_setup',
            'core.posting_modify_template_vars' => 'posting_modify_template_vars',
            'core.acp_board_config_edit_add'    => 'acp_board_config_edit_add',
            'core.update_session_after'         => 'update_session_after',
        );
    }

    // Add 200 points at users that get out of group New Register User
    public function update_session_after($event)
    {
        global $config;

        $ok_session_time = $event['session_data'];
        if ($ok_session_time['session_time'])
        {
            // Add 200 points at user that get out of group New Register User
            if ($this->user->data['user_id'] != ANONYMOUS && isset($config['new_member_post_limit']) && $this->user->data['user_new'] && $config['new_member_post_limit'] <= $this->user->data['user_posts'])
            {
                // Check if exist column 'user_points'
                if ($this->db_tools->sql_column_exists(USERS_TABLE, 'user_points'))
                {
                    // Suma 200 puntos a la cuenta del usuario
                    $sql = 'UPDATE ' . USERS_TABLE . '
                            SET user_points = user_points + 200 WHERE user_id = ' . (int) $this->user->data['user_id'];
                    $this->db->sql_query($sql);
                }
            }
        }
    }

    // Load Lenguage
    public function load_language_on_setup($event)
    {
        // load language
        $lang_set_ext = $event['lang_set_ext'];
        $lang_set_ext[] = array(
            'ext_name' => 'pikaron/payforlink',
            'lang_set' => 'payforlink',
        );
        $event['lang_set_ext'] = $lang_set_ext;
    }

    //  Display Review
    public function posting_modify_template_vars($event)
    {
        global $config;

        $this->template->assign_vars(array(
            'PAYFORLINK_VERSION' => $config['payforlink_version'],
        ));
    }

    // ACP
    public function acp_board_config_edit_add($event)
    {
		if ($event['mode'] === 'post' && array_key_exists('legend3', $event['display_vars']['vars']))
        {
            $add_config_var = array(
                'legend65' => 'ACP_PAYFORLINK_SETTINGS',
                'payforlink_show' => array('lang' => 'DISPLAY_PAYFORLINK_SHOW', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => false),
                'payforlink_cant' => array('lang' => 'DISPLAY_PAYFORLINK_CANT', 'validate' => 'int:5:100', 'type' => 'number:0:99999', 'explain' => false),
            );
			$event->update_subarray('display_vars', 'vars', phpbb_insert_config_array($event['display_vars']['vars'], $add_config_var, ['before' => 'legend3']));
        }
    }

}
?>