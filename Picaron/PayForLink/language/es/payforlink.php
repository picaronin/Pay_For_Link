<?php
/**
*
* PayForLink System
*
* @package main (Spanish)
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
    'TITLE_LINK'                => 'Agregar enlace de Descarga',
    'LINK_EXPLAIN'              => 'Introduzca el enlace de descarga externo, la cantidad de puntos a pagar asociada y haga clic en el botón Aceptar para incorporarlo en su mensaje.',
    'ADD_LINK'                  => 'Enlace de Descarga',
    'LINK_ADD_ALERT_URL'        => '¡¡¡ Tiene que indicar una URL válida !!!',
    'LINK_ADD_ALERT_COST'       => '¡¡¡ El Precio debe ser un valor entre 1 y 150 !!!',
    'LINK_ADD_TITLE'            => 'Introduzca los Datos del Enlace',
    'LINK_ADD_TITLE_BOX1'       => 'URL completa del Enlace de Descarga:',
    'LINK_ADD_TITLE_BOX2'       => 'Precio en puntos por cada descarga:',
    'LINK_ADD_BOX2_EXPLAIN'     => '(Por cada descarga que se realice de este enlace, se sumaran el 50% de los puntos en su cuenta)',
    'LINK_ADD_BUTTON'           => 'Aceptar',
    'LINK_ADD_EXPLAIN'          => 'Nota: Guarde los datos del enlace pues este va a ser Codificado y no podrá recuperarlo posteriormente.',
    'LINK_ADD_CORRECT'          => '¡¡¡ Enlace CORRECTO !!!',
    'LINK_ADD_TITLE_BOX3'       => 'Datos del Enlace de Descarga:',
    'LINK_ADD_OTHER'            => '¿¿ Agregar otro Enlace ??',
    'LINK_ADD_TO_POST'          => 'Agregar el enlace de descarga al Mensaje',
    'LINK_ADD_ERROR'            => '!! ERROR EN LOS DATOS DE LA DESCARGA !!',
    'LINK_ADD_ERROR_NOTIFY'     => '(Notifíquelo al creador del hilo)',
    'LINK_ADD_NO_POINTS'        => 'No tiene suficiente Puntos para poder Descargar el archivo',
    'LINK_ADD_DISABLE'          => 'Enlace DESHABILITADO',
    'LINK_ADD_PRICE'            => 'Precio:&nbsp;',
    'LINK_ADD_POINTS'           => '&nbsp;Puntos',
    'LINK_ADD_CASH'             => 'Su Saldo:&nbsp;',
    'LINK_ADD_OWNER'            => '(Usted es el Propietario del Enlace)',
    'LINK_ADD_HOSTING'          => 'Enlace hospedado en:&nbsp;',
    'LINK_ADD_REST_POINTS'      => 'Haga click para Descargar el archivo. Se restaran de su cuenta:&nbsp;',
    'LINK_ADD_WITH_COST'        => 'Enlace de DESCARGA con cargo',
    'LINK_ADD_FREE'             => 'DESCARGA Gratuita',
    'LINK_ADD_FREE_FILE'        => 'Enlace Libre al Archivo',
    'LINK_ADD_NO_PAY'           => 'Haga click para Descargar el archivo. No se restarán puntos de su cuenta',
    'LINK_ADD_FREE_FILE_DOWN'   => 'Enlace de DESCARGA LIBRE',
    'LINK_ADD_FREE_DOWN'        => 'Descarga Libre',
    'LINK_ADD_USER_BUY'         => '(Ha pagado con anterioridad por este Enlace&nbsp;',
    'LINK_ADD_ENABLED'          => 'Descarga Habilitada',
    'LINK_ADD_LINK_FILE'        => 'Enlace al Archivo',
    'LINK_ADD_WILL_REST'        => 'Se han restado&nbsp;',
    'LINK_ADD_WILL_ADD'         => 'Se han agregado&nbsp;',
    'LINK_ADD_P_ME_ACCOUNT'     => '&nbsp;puntos de su cuenta',
    'LINK_ADD_P_HE_ACCOUNT'     => '&nbsp;puntos a la cuenta de&nbsp;',
    'LINK_ADD_NOT_ADD_REST'     => '(No se sumarán ni restarán puntos de su cuenta)',
    'LINK_ADD_TITLE_BOX4'       => 'Propietario de la Descarga:',

    // UCP
    'POINTS_LINKS_TIT_DESCR'    => 'GESTION DE ENLACES',
    'POINTS_LINKS_DESCRIPTION'  => 'En esta seccion tenemos a nuestra disposición las opciones necesarias para controlar nuestros enlaces de descarga.<br>Podremos obtener diferentes listados:<br>- Relacion de todos los enlaces que has comprado.<br>- Relacion de TUS enlaces que han sido comprados por los demas usuarios.<br>- Relacion general de TODOS tus Enlaces de Descarga.<br>Asi como un listado general de TODOS los Enlaces de Descarga del Foro.<br>Desde todos los listados se tiene acceso tanto a la URL de descarga como al hilo del foro donde se encuentra el enlace.',
    'POINTS_LINKS_BUY'          => '¿Cuantos Enlaces he Comprado?',
    'POINTS_LINKS_PAY'          => '¿Quien ha Comprado mis Enlaces?',
    'POINTS_LINKS_MEE'          => 'Todos Mis Enlaces',
    'POINTS_LINKS_ALL'          => 'TODOS los Enlaces de Descarga',
    'POINTS_LIST_LINKS_BUY'     => 'Relacion de los Enlaces que TU has Comprado',
    'POINTS_LIST_LINKS_PAY'     => 'Relacion de Usuarios que han Comprado TUS Enlaces',
    'POINTS_LIST_LINKS_MEE'     => 'Relacion de Todos TUS Enlaces',
    'POINTS_LIST_LINKS_ALL'     => 'Relacion de Todos los Enlaces de Descarga en el Foro',
    'POINTS_OWNER'              => 'Propietario',
    'POINTS_BUYER'              => 'Comprador',
    'POINTS_LINK'               => 'Enlaces',
    'POINTS_LINK_LOCATED'       => 'Localizar Enlaces',
    'POINTS_LINK_CREATOR'       => 'Creador del Mensaje',
    'POINTS_LINK_PAY'           => 'Pagados',
    'POINTS_LINK_BUY'           => 'Ganados',
    'POINTS_LINK_NO_SUBJECT'    => '(Mensaje Sin Asunto)',
    'POINTS_LINK_TITLE'         => 'Enlaces de %1$s',
    'POINTS_TOT_PAY'            => 'Total gastado en',
    'POINTS_TOT_BUY'            => 'Total ganado con',
    'POINTS_TOT_MEE'            => 'Valor Total por',
    'POINTS_ORD_CRONO'          => 'Orden Cronologico',
    'POINTS_ORD_GAIN'           => 'Precio Ganado',
    'POINTS_ORD_SUBJECT'        => 'Asunto del Mensaje',
    'POINTS_PRICE_PAY'          => 'Precio Pagado',
    'POINTS_NO_LINK'            => '(El enlace no se encuentra en ningun mensaje del foro)',
    'POINTS_POINTS'             => 'Puntos',
    'PAYFORLINK_VERSION'        => 'Versión',
    'POINTS_PAY_TOTAL'          => 'Total',

    // ACP
    'ACP_PAYFORLINK_SETTINGS'   => 'Extensión: Pay For Link',
    'DISPLAY_PAYFORLINK_SHOW'   => 'Enlaces de Descarga GRATUITOS',
    'DISPLAY_PAYFORLINK_CANT'   => 'Cantidad de Enlaces por página en Informes de UCP',
));

?>