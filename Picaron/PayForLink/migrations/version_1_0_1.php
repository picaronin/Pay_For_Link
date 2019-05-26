<?php
/**
 * PayForLink extension for the phpBB >=3.2.0 Forum Software package.
 * @copyright (c) 2017 Picaron
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace Picaron\PayForLink\migrations;

class version_1_0_1 extends \phpbb\db\migration\migration
{
    static public function depends_on()
    {
        return array(
            '\Picaron\PayForLink\migrations\version_1_0_0',
        );
    }

    public function update_data()
    {
        return array(
            array('config.update', array('version_payforlink', '1.0.1')),
        );
    }
}