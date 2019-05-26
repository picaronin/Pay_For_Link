<?php
/**
*
* PayForLink System
*
* @package main (French)
* PayForLink extension for the phpBB >=3.2.0 Forum Software package.
* @copyright (c) 2017 Picaron
* French translation by Kiasma
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
    'TITLE_LINK'                => 'Ajouter un lien',
    'LINK_EXPLAIN'              => 'Entrez le lien de téléchargement et le prix à payer et cliquez sur le bouton OK pour ajouter à votre message.',
    'ADD_LINK'                  => 'Lien de téléchargement',
    'LINK_ADD_ALERT_URL'        => 'Vous devez entrer une URL valide !!!',
    'LINK_ADD_ALERT_COST'       => 'Le prix doit être compris entre 1 et 150 !!!',
    'LINK_ADD_TITLE'            => 'Entrez le lien de données !',
    'LINK_ADD_TITLE_BOX1'       => 'URL complète du lien de téléchargement :',
    'LINK_ADD_TITLE_BOX2'       => 'Prix ​​en Points pour chaque téléchargement :',
    'LINK_ADD_BOX2_EXPLAIN'     => '(Pour chaque téléchargement effectué avec ce lien, 50% des Points seront versés sur votre compte)',
    'LINK_ADD_BUTTON'           => 'OK',
    'LINK_ADD_EXPLAIN'          => 'Remarque: Enregistrez le lien car ces données seront cryptées et ne pourront pas être récupérées.',
    'LINK_ADD_CORRECT'          => 'Lien OK !!!',
    'LINK_ADD_TITLE_BOX3'       => 'Télécharger les données du lien :',
    'LINK_ADD_OTHER'            => 'Ajouter un autre lien ?',
    'LINK_ADD_TO_POST'          => 'Ajouter le lien de téléchargement au message',
    'LINK_ADD_ERROR'            => '!! ERREUR DANS LES DONNÉES DU TÉLÉCHARGEMENT !!',
    'LINK_ADD_ERROR_NOTIFY'     => '(Rapportez-le à l\'auteur du fil)',
    'LINK_ADD_NO_POINTS'        => 'Vous n\'avez pas assez de Points pour télécharger le fichier',
    'LINK_ADD_DISABLE'          => 'Lien DESACTIVÉ',
    'LINK_ADD_PRICE'            => 'Prix :&nbsp;',
    'LINK_ADD_POINTS'           => '&nbsp;Points',
    'LINK_ADD_CASH'             => 'Votre solde:&nbsp;',
    'LINK_ADD_OWNER'            => '(Vous êtes le propriétaire du lien)',
    'LINK_ADD_HOSTING'          => 'Lien hébergé sur:&nbsp;',
    'LINK_ADD_REST_POINTS'      => 'Cliquez pour télécharger le fichier. Seront soustraits de votre compte:&nbsp;',
    'LINK_ADD_WITH_COST'        => 'Paiement du lien de téléchargement',
    'LINK_ADD_FREE'             => 'Téléchargement Gratuit',
    'LINK_ADD_FREE_FILE'        => 'Lien vers le fichier gratuit',
    'LINK_ADD_NO_PAY'           => 'Cliquez pour télécharger le fichier. Aucun Point n\'est déduit de votre compte',
    'LINK_ADD_FREE_FILE_DOWN'   => 'Lien du téléchargement gratuit',
    'LINK_ADD_FREE_DOWN'        => 'Téléchargement gratuit',
    'LINK_ADD_USER_BUY'         => '(Vous avez déjà payé pour ce lien&nbsp;',
    'LINK_ADD_ENABLED'          => 'Téléchargement activé',
    'LINK_ADD_LINK_FILE'        => 'Lien vers le fichier',
    'LINK_ADD_WILL_REST'        => 'Ont été soustraits&nbsp;',
    'LINK_ADD_WILL_ADD'         => 'Ont été ajoutés&nbsp;',
    'LINK_ADD_P_ME_ACCOUNT'     => '&nbsp;Points de votre compte',
    'LINK_ADD_P_HE_ACCOUNT'     => '&nbsp;Points vers le compte de&nbsp;',
    'LINK_ADD_NOT_ADD_REST'     => '(Aucun Point ne sera ajouté ou soustrait sur votre compte)',
    'LINK_ADD_TITLE_BOX4'       => 'Propriétaire du téléchargement:',

    // UCP
    'POINTS_LINKS_TIT_DESCR'    => 'Gestion des liens',
    'POINTS_LINKS_DESCRIPTION'  => 'Dans cette section, vous avez à votre disposition les options nécessaires pour contrôler vos liens de téléchargement.<br>Voici les différentes listes :<br>- Liste de tous les liens que vous avez achetés.<br>- Liste des utilisateurs qui ont acheté vos liens.<br>- Liste des liens que vous avez postés.<br>- Liste générale de tous les liens à télécharger sur le Forum.<br>Toutes les listes vous donnent accès à l\'URL de téléchargement et à la discussion où se trouve le lien.',
    'POINTS_LINKS_BUY'          => 'Quels liens ai-je acheté ?',
    'POINTS_LINKS_PAY'          => 'Qui a acheté mes liens ?',
    'POINTS_LINKS_MEE'          => 'Quels liens ai-je partagé ?',
    'POINTS_LINKS_ALL'          => 'Tous les liens de téléchargement.',
    'POINTS_LIST_LINKS_BUY'     => 'Liste des liens que vous avez achetés',
    'POINTS_LIST_LINKS_PAY'     => 'Liste des utilisateurs qui ont acheté vos liens',
    'POINTS_LIST_LINKS_MEE'     => 'Liste de tous vos liens',
    'POINTS_LIST_LINKS_ALL'     => 'Listes de tous les liens de téléchargement dans le forum',
    'POINTS_OWNER'              => 'Propriétaire',
    'POINTS_BUYER'              => 'Acheteur',
    'POINTS_LINK'               => 'Liens',
    'POINTS_LINK_LOCATED'       => 'Posts où se trouve le lien',
    'POINTS_LINK_CREATOR'       => 'Auteur du message',
    'POINTS_LINK_PAY'           => 'Payé',
    'POINTS_LINK_BUY'           => 'Gagné',
    'POINTS_LINK_NO_SUBJECT'    => '(Message sans sujet)',
    'POINTS_LINK_TITLE'         => 'Liens de %1$s',
    'POINTS_TOT_PAY'            => 'Total dépensé',
    'POINTS_TOT_BUY'            => 'Total gagné',
    'POINTS_TOT_MEE'            => 'Valeur totale par',
    'POINTS_ORD_CRONO'          => 'Ordre chronologique',
    'POINTS_ORD_GAIN'           => 'Prix ​​gagné',
    'POINTS_ORD_SUBJECT'        => 'Sujet du message',
    'POINTS_PRICE_PAY'          => 'Prix payé',
    'POINTS_NO_LINK'            => '(Le lien n\'a pas été trouvé dans un message du forum)',
    'POINTS_POINTS'             => 'Points',
    'PAYFORLINK_VERSION'        => 'Lancement',
    'POINTS_PAY_TOTAL'          => 'Total',

    // ACP
    'ACP_PAYFORLINK_SETTINGS'   => 'Extension: Pay For Link',
    'DISPLAY_PAYFORLINK_SHOW'   => 'Tous les liens sont GRATUITS',
    'DISPLAY_PAYFORLINK_CANT'   => 'Nombre de liens par page dans les rapports d\'UCP',
));

?>