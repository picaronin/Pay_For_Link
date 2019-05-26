<?php
/**
 * PayForLink extension for the phpBB >=3.2.0 Forum Software package.
 * @copyright (c) 2017 Picaron
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace Picaron\PayForLink;

/**
* Extension class Search Back for custom enable/disable/purge actions
*/
class ext extends \phpbb\extension\base
{

    public function is_enableable()
    {
        $config = $this->container->get('config');
        return phpbb_version_compare($config['version'], '3.2.0', '>=');
    }

    public function enable_step($old_state)
    {
        switch ($old_state)
        {
            case '':
                $acp_manager = new \Picaron\PayForLink\includes\acp_manager
                (
                    $this->container->get('dbal.conn'),
                    $this->container->get('request'),
                    $this->container->get('user'),
                    $this->container->getParameter('core.root_path'),
                    $this->container->getParameter('core.php_ext')
                );
                // Add payforlink bbcode
                $acp_manager->install_bbcode();
                return 'PayForLink';
            break;
            default:
                return parent::enable_step($old_state);
            break;
        }
    }

    public function disable_step($old_state)
    {
        switch ($old_state)
        {
            case '':
                $acp_manager = new \Picaron\PayForLink\includes\acp_manager
                (
                    $this->container->get('dbal.conn'),
                    $this->container->get('request'),
                    $this->container->get('user'),
                    $this->container->getParameter('core.root_path'),
                    $this->container->getParameter('core.php_ext')
                );
                // Delete payforlink bbcode
                $acp_manager->delete_bbcode();
                return 'PayForLink';
            break;
            default:
                return parent::disable_step($old_state);
            break;
        }
    } 
}
?>