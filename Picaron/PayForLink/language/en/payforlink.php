<?php
/**
*
* PayForLink System
*
* @package main (English)
* PayForLink extension for the phpBB >=3.2.0 Forum Software package.
* @copyright (c) 2017 Picaron
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
    exit;
}

if (empty($lang) || !is_array($lang))
{
    $lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine

$lang = array_merge($lang, array(
    'TITLE_LINK'                => 'Add Link',
    'LINK_EXPLAIN'              => 'Enter the download link and the price to pay and click the OK button to add to your message.',
    'ADD_LINK'                  => 'Download Link',
    'LINK_ADD_ALERT_URL'        => 'You must enter a valid URL !!!',
    'LINK_ADD_ALERT_COST'       => 'The price must be a value between 1 and 150 !!!',
    'LINK_ADD_TITLE'            => 'Enter Data Link !',
    'LINK_ADD_TITLE_BOX1'       => 'Full URL of the download link:',
    'LINK_ADD_TITLE_BOX2'       => 'Price in points for each download:',
    'LINK_ADD_BOX2_EXPLAIN'     => '(For each download that is made of this link, to join 50% of the points in your account)',
    'LINK_ADD_BUTTON'           => 'OK',
    'LINK_ADD_EXPLAIN'          => 'Note: Save link as this data will be encrypted and can not recover.',
    'LINK_ADD_CORRECT'          => 'Link OK !!!',
    'LINK_ADD_TITLE_BOX3'       => 'Download Link Data:',
    'LINK_ADD_OTHER'            => 'Add another link ?',
    'LINK_ADD_TO_POST'          => 'Add the download link to the Message',
    'LINK_ADD_ERROR'            => '!! ERROR IN THE DATA FROM THE DOWNLOAD !!',
    'LINK_ADD_ERROR_NOTIFY'     => '(Report It to the originator of the thread)',
    'LINK_ADD_NO_POINTS'        => 'Not enough Points to download the file',
    'LINK_ADD_DISABLE'          => 'Link DISABLED',
    'LINK_ADD_PRICE'            => 'Price:&nbsp;',
    'LINK_ADD_POINTS'           => '&nbsp;Points',
    'LINK_ADD_CASH'             => 'Your Balance:&nbsp;',
    'LINK_ADD_OWNER'            => '(You are the Owner Link)',
    'LINK_ADD_HOSTING'          => 'Link Hosted at::&nbsp;',
    'LINK_ADD_REST_POINTS'      => 'Click to download the file. Be subtracted from your account:&nbsp;',
    'LINK_ADD_WITH_COST'        => 'Payment Download Link',
    'LINK_ADD_FREE'             => 'FREE Download',
    'LINK_ADD_FREE_FILE'        => 'Link to Free File',
    'LINK_ADD_NO_PAY'           => 'Click to download the file. No Points are deducted from your account',
    'LINK_ADD_FREE_FILE_DOWN'   => 'FREE DOWNLOAD link',
    'LINK_ADD_FREE_DOWN'        => 'Free Download',
    'LINK_ADD_USER_BUY'         => '(You have previously paid for this link&nbsp;',
    'LINK_ADD_ENABLED'          => 'Download Enabled',
    'LINK_ADD_LINK_FILE'        => 'Link to File',
    'LINK_ADD_WILL_REST'        => 'Have been subtracted&nbsp;',
    'LINK_ADD_WILL_ADD'         => 'Have been added&nbsp;',
    'LINK_ADD_P_ME_ACCOUNT'     => '&nbsp;points of your account',
    'LINK_ADD_P_HE_ACCOUNT'     => '&nbsp;points to the account of&nbsp;',
    'LINK_ADD_NOT_ADD_REST'     => '(Do not will add or subtract points from your Account)',
    'LINK_ADD_TITLE_BOX4'       => 'Owner of the download:',

    // UCP
    'POINTS_LINKS_TIT_DESCR'    => 'Link Management',
    'POINTS_LINKS_DESCRIPTION'  => 'In this section we have at our disposal the necessary options to control our download links.<br>We get different lists:<br>- List of all the links you have purchased.<br>- Relationship of your links that have been purchased by other users.<br>- General Relationship Download ALL your links.<br>- General and a list of ALL Links Download Forum.<br>From all listings will have access to both the download URL and the forum thread where the link.',
    'POINTS_LINKS_BUY'          => 'I bought many Links ?',
    'POINTS_LINKS_PAY'          => 'Who Bought my Links ?',
    'POINTS_LINKS_MEE'          => 'All My Links',
    'POINTS_LINKS_ALL'          => 'All Download Links',
    'POINTS_LIST_LINKS_BUY'     => 'List of links that you have purchased',
    'POINTS_LIST_LINKS_PAY'     => 'Relationship of Users who have purchased your links',
    'POINTS_LIST_LINKS_MEE'     => 'List of all your links',
    'POINTS_LIST_LINKS_ALL'     => 'List of all download links in the Forum',
    'POINTS_OWNER'              => 'Owner',
    'POINTS_BUYER'              => 'Buyer',
    'POINTS_LINK'               => 'Links',
    'POINTS_LINK_LOCATED'       => 'Posts where the Link',
    'POINTS_LINK_CREATOR'       => 'Creator of the message',
    'POINTS_LINK_PAY'           => 'Paid',
    'POINTS_LINK_BUY'           => 'Cattle',
    'POINTS_LINK_NO_SUBJECT'    => '(Message No Subject)',
    'POINTS_LINK_TITLE'         => 'Links of %1$s',
    'POINTS_TOT_PAY'            => 'Total spent in',
    'POINTS_TOT_BUY'            => 'Total cattle',
    'POINTS_TOT_MEE'            => 'Total Value by',
    'POINTS_ORD_CRONO'          => 'Chronological Order',
    'POINTS_ORD_GAIN'           => 'Price Cattle',
    'POINTS_ORD_SUBJECT'        => 'Message Subject',
    'POINTS_PRICE_PAY'          => 'Price Paid',
    'POINTS_NO_LINK'            => '(The Link is not found in any forum post)',
    'POINTS_POINTS'             => 'Points',
    'PAYFORLINK_VERSION'        => 'Release',
    'POINTS_PAY_TOTAL'          => 'Total',

    // ACP
    'ACP_PAYFORLINK_SETTINGS'   => 'Extension: Pay For Link',
    'DISPLAY_PAYFORLINK_SHOW'   => 'All Links are FREE',
    'DISPLAY_PAYFORLINK_CANT'   => 'Number of Links per page in Reports of UCP',
));

?>