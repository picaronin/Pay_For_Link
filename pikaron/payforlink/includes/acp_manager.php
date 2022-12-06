<?php
/**
 * PayForLink extension for the phpBB >=3.3.0 Forum Software package.
 * @copyright (c) 2022 Picaron
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace pikaron\payforlink\includes;

class acp_manager
{
    /** @var \phpbb\db\driver\driver_interface */
    protected $db;

    /** @var \phpbb\request\request */
    protected $request;

    /** @var \phpbb\user */
    protected $user;

    /** @var string */
    protected $phpbb_root_path;

    /** @var string */
    protected $php_ext;

    /**
    * Constructor
    *
    * @param \phpbb\db\driver\driver_interface $db
    * @param \phpbb\request\request $request
    * @param \phpbb\user $user
    * @param string $phpbb_root_path
    * @param string $php_ext
    * @access public
    */
    public function __construct
    (
        \phpbb\db\driver\driver_interface $db,
        \phpbb\request\request $request,
        \phpbb\user $user,
        $phpbb_root_path,
        $php_ext
    )
    {
        $this->db = $db;
        $this->request = $request;
        $this->user = $user;
        $this->phpbb_root_path = $phpbb_root_path;
        $this->php_ext = $php_ext;
    }

    /**
    * Installs BBCodes, used by migrations to perform add/updates
    *
    * @param array $bbcode_data Array of BBCode data to install
    * @return null
    * @access public
    */
    public function install_bbcode()
    {
        // Load the acp_bbcode class
        if (!class_exists('acp_bbcodes'))
        {
            include($this->phpbb_root_path . 'includes/acp/acp_bbcodes.' . $this->php_ext);
        }
        $bbcode_tool = new \acp_bbcodes();

        $bbcode_data = array(
            'payforlink' => array(
                'bbcode_helpline'       => '[payforlink]URL codificada del enlace de descarga[/payforlink]',
                'display_on_posting'    => 0,
                'bbcode_match'          => '[payforlink]{SIMPLETEXT}[/payforlink]',
                'bbcode_tpl'            => '<iframe src="./app.php/payforlink?mode=preview&urlcodec={SIMPLETEXT}" width="100%" height="90" frameborder=0 scrolling="no" style="margin-left: 0px; margin-right: 0px; margin-top: 0px; margin-bottom: 0px;">WARNING: [EXT] PayForLink - To work correctly, it is necessary to enable the iframes in your browser !!!.</iframe>',
            ),
        );

        foreach ($bbcode_data as $bbcode_name => $bbcode_array)
        {
            // Build the BBCodes
            $data = $bbcode_tool->build_regexp($bbcode_array['bbcode_match'], $bbcode_array['bbcode_tpl']);

            $bbcode_array += array(
                'bbcode_tag'            => $data['bbcode_tag'],
                'first_pass_match'      => $data['first_pass_match'],
                'first_pass_replace'    => $data['first_pass_replace'],
                'second_pass_match'     => $data['second_pass_match'],
                'second_pass_replace'   => $data['second_pass_replace']
            );

            $sql = 'SELECT bbcode_id
                FROM ' . BBCODES_TABLE . "
                WHERE LOWER(bbcode_tag) = '" . strtolower($bbcode_name) . "'
                OR LOWER(bbcode_tag) = '" . strtolower($bbcode_array['bbcode_tag']) . "'";
            $result = $this->db->sql_query($sql);
            $row_exists = $this->db->sql_fetchrow($result);
            $this->db->sql_freeresult($result);

            if (!$row_exists)
            {
                // Create new BBCode
                $sql = 'SELECT MAX(bbcode_id) AS max_bbcode_id
                    FROM ' . BBCODES_TABLE;
                $result = $this->db->sql_query($sql);
                $row = $this->db->sql_fetchrow($result);
                $this->db->sql_freeresult($result);

                if ($row)
                {
                    $bbcode_id = $row['max_bbcode_id'] + 1;

                    // Make sure it is greater than the core BBCode ids...
                    if ($bbcode_id <= NUM_CORE_BBCODES)
                    {
                        $bbcode_id = NUM_CORE_BBCODES + 1;
                    }
                }
                else
                {
                    $bbcode_id = NUM_CORE_BBCODES + 1;
                }

                if ($bbcode_id <= BBCODE_LIMIT)
                {
                    $bbcode_array['bbcode_id'] = (int) $bbcode_id;
                    // set display_on_posting to 1 by default, so if 0 is desired, set it in our data array
                    $bbcode_array['display_on_posting'] = (int) !array_key_exists('display_on_posting', $bbcode_array);

                    $this->db->sql_query('INSERT INTO ' . BBCODES_TABLE . ' ' . $this->db->sql_build_array('INSERT', $bbcode_array));
                }
            }
        }
    }

    public function delete_bbcode()
    {
        $sql = "DELETE FROM " . BBCODES_TABLE . " WHERE bbcode_tag = 'payforlink'";
        $this->db->sql_query($sql);
    }
}
?>