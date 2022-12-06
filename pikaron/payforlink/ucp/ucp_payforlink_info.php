<?php
/**
 * PayForLink extension for the phpBB >=3.3.0 Forum Software package.
 * @copyright (c) 2022 Picaron
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace pikaron\payforlink\ucp;

class ucp_payforlink_info
{
    function module()
    {
        return array(
            'filename'          => 'pikaron\payforlink\ucp\ucp_payforlink_module',
            'title'             => 'UCP_PAYFORLINK_TITLE',
            'modes'             => array(
                'mybuylinks'    => array(
                    'title'     => 'POINTS_LINKS_BUY',
                    'auth'      => 'ext_pikaron/payforlink',
                    'cat'       => array('UCP_MAIN')
                ),
                'whobuymylinks' => array(
                    'title'     => 'POINTS_LINKS_PAY',
                    'auth'      => 'ext_pikaron/payforlink',
                    'cat'       => array('UCP_MAIN')
                ),
                'allmylinks'    => array(
                    'title'     => 'POINTS_LINKS_MEE',
                    'auth'      => 'ext_pikaron/payforlink',
                    'cat'       => array('UCP_MAIN')
                ),
                'totallinks'    => array(
                    'title'     => 'POINTS_LINKS_ALL',
                    'auth'      => 'ext_pikaron/payforlink',
                    'cat'       => array('UCP_MAIN')
                ),
            ),
        );
    }
}
?>