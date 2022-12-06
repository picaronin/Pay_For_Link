<?php
/**
 * PayForLink extension for the phpBB >=3.3.0 Forum Software package.
 * @copyright (c) 2022 Picaron
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace pikaron\payforlink\migrations;

class version_3_3_0 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return !empty($this->config['payforlink_version']);
	}
	
    static public function depends_on()
    {
        return array('\phpbb\db\migration\data\v320\v320');
    }

    public function update_data()
    {
        return array(
            // Add config
            array('config.add', array('payforlink_version', '3.3.0')),
            array('config.add', array('payforlink_show', 0)),
            array('config.add', array('payforlink_cant', 15)),            

            // Add payforlink bbcode
            array('custom', array(array($this, 'install_payforlink_bbcode'))),

            // UCP module
            array('module.add', array(
                'ucp',
                false,
                'UCP_PAYFORLINK_TITLE'
            )),
            array('module.add', array(
                'ucp',
                'UCP_PAYFORLINK_TITLE',
                array(
                    'module_basename'   => '\pikaron\payforlink\ucp\ucp_payforlink_module',
                    'auth'              => 'ext_pikaron/payforlink',
                    'modes'             => array('mybuylinks', 'whobuymylinks', 'allmylinks', 'totallinks'),
                ),
            )),
        );
    }

    public function revert_data()
    {
        return array(
            // Delete config
            array('config.remove', array('payforlink_version')),
            array('config.remove', array('payforlink_show')),
            array('config.remove', array('payforlink_cant')),
            
            // Delete payforlink bbcode
            array('custom', array(array($this, 'remove_payforlink_bbcode'))),

            // UCP module
            array('module.remove', array(
                'ucp',
                false,
                'UCP_PAYFORLINK_TITLE'
            )),
            array('module.remove', array(
                'ucp',
                'UCP_PAYFORLINK_TITLE',
                array(
                    'module_basename'   => '\pikaron\payforlink\ucp\ucp_payforlink_module',
                    'auth'              => 'ext_pikaron/payforlink',					
                    'modes'             => array('mybuylinks', 'whobuymylinks', 'allmylinks', 'totallinks'),
                ),
            )),
        );
    }

    public function update_schema()
    {
        return array(
            'add_tables'    => array(
                $this->table_prefix . 'payforlink'  => array(
                    'COLUMNS'   => array(
                        'link_id'       => array('UINT', null, 'auto_increment'),
                        'user_id_buy'   => array('UINT', null, ''),
                        'link_url'      => array('MTEXT_UNI', '', 'true_sort'),
                        'link_cost'     => array('UINT:3', 0),
                        'user_id_prop'  => array('UINT', null, ''),
                    ),
                    'PRIMARY_KEY'   => 'link_id',
                ),
            ),
        );
    }

    public function revert_schema()
    {
        return  array(
            'drop_tables' => array(
                $this->table_prefix . 'payforlink',
            ),
        );
    }

    // Add payforlink bbcode
    public function install_payforlink_bbcode()
    {
        global $db, $request, $user;
        $acp_manager = new \pikaron\payforlink\includes\acp_manager($db, $request, $user, $this->phpbb_root_path, $this->php_ext);
        $acp_manager->install_bbcode();
    }

    // Delete payforlink bbcode
    public function remove_payforlink_bbcode()
    {
        global $db, $request, $user;
        $acp_manager = new \pikaron\payforlink\includes\acp_manager($db, $request, $user, $this->phpbb_root_path, $this->php_ext);
        $acp_manager->delete_bbcode();
    }
}
?>