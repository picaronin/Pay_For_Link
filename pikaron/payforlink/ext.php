<?php
/**
 * PayForLink extension for the phpBB >=3.3.0 Forum Software package.
 * @copyright (c) 2022 Picaron
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace pikaron\payforlink;

/**
* Extension class Search Back for custom enable/disable/purge actions
*/
class ext extends \phpbb\extension\base
{

    public function is_enableable()
    {
        $config = $this->container->get('config');
        $language = $this->container->get('language');
        $table_prefix = $this->container->getParameter('core.table_prefix');
        $language->add_lang('payforlink', 'pikaron/payforlink');

        // Verify if there is a previous version installed
        if (isset($config['version_payforlink']))
        {
            trigger_error($language->lang('PAYFORLINK_OLD_VERSION', $table_prefix . 'payforlink', $config['version_payforlink'], $config['version_payforlink'], $config['version_payforlink']), E_USER_WARNING);
        }

        /**
         * Check phpBB requirements
         *
         * Requires phpBB 3.3.0 or greater
         *
         * @return bool
         */
        $is_ver_phpbb = phpbb_version_compare($config['version'], '3.3.0', '>=');

        // Display a custom warning message if requirement fails.
        if (!$is_ver_phpbb)
        {
            trigger_error($language->lang('PAYFORLINK_PHPBB_ERROR'), E_USER_WARNING);
        }

        /**
         * Check PHP requirements
         *
         * Requires PHP 7.1.0 or greater
         *
         * @return bool
         */
        $is_ver_php = phpbb_version_compare(PHP_VERSION, '7.1.0', '>=');

        // Display a custom warning message if requirement fails.
        if (!$is_ver_php)
        {
            trigger_error($language->lang('PAYFORLINK_PHP_ERROR'), E_USER_WARNING);
        }

        return $is_ver_phpbb && $is_ver_php;
    }

    public function enable_step($old_state)
    {
        switch ($old_state)
        {
            case '':
                $acp_manager = new \pikaron\payforlink\includes\acp_manager
                (
                    $this->container->get('dbal.conn'),
                    $this->container->get('request'),
                    $this->container->get('user'),
                    $this->container->getParameter('core.root_path'),
                    $this->container->getParameter('core.php_ext')
                );
                // Add payforlink bbcode
                $acp_manager->install_bbcode();
                return 'payforlink';
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
                $acp_manager = new \pikaron\payforlink\includes\acp_manager
                (
                    $this->container->get('dbal.conn'),
                    $this->container->get('request'),
                    $this->container->get('user'),
                    $this->container->getParameter('core.root_path'),
                    $this->container->getParameter('core.php_ext')
                );
                // Delete payforlink bbcode
                $acp_manager->delete_bbcode();
                return 'payforlink';
            break;
            default:
                return parent::disable_step($old_state);
            break;
        }
    }
}
?>