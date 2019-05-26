<?php
/**
 * PayForLink extension for the phpBB >=3.2.0 Forum Software package.
 * @copyright (c) 2017 Picaron
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace Picaron\PayForLink\ucp;

class ucp_payforlink_info
{
    function module()
    {
        return array(
            'filename'          => 'Picaron\PayForLink\ucp\ucp_payforlink_module',
            'title'             => 'UCP_PAYFORLINK_TITLE',
            'modes'             => array(
                'mybuylinks'    => array(
                    'title'     => 'POINTS_LINKS_BUY',
                    'auth'      => 'ext_Picaron/PayForLink',
                    'cat'       => array('UCP_MAIN')
                ),
                'whobuymylinks' => array(
                    'title'     => 'POINTS_LINKS_PAY',
                    'auth'      => 'ext_Picaron/PayForLink',
                    'cat'       => array('UCP_MAIN')
                ),
                'allmylinks'    => array(
                    'title'     => 'POINTS_LINKS_MEE',
                    'auth'      => 'ext_Picaron/PayForLink',
                    'cat'       => array('UCP_MAIN')
                ),
                'totallinks'    => array(
                    'title'     => 'POINTS_LINKS_ALL',
                    'auth'      => 'ext_Picaron/PayForLink',
                    'cat'       => array('UCP_MAIN')
                ),
            ),
        );
    }
}
?>