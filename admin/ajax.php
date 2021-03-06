<?php
/**
 * Administrative AJAX functions for the Daily Quote plugin.
 *
 * @author      Lee Garner <lee@leegarner.com>
 * @copyright   Copyright (c) 2009-2016 Lee Garner <lee@leegarner.com>
 * @package     dailyquote
 * @version     v0.2.0
 * @license     http://opensource.org/licenses/gpl-2.0.php
 *              GNU Public License v2 or later
 * @filesource
 */

/** Include required glFusion common functions */
require_once '../../../lib-common.php';

// This is for administrators only
if (!SEC_hasRights('dailyquote.edit')) {
    COM_accessLog("User {$_USER['username']} tried to illegally access the dailyquote AJAX functions.");
    exit;
}

$base_url = $_CONF['site_url'];

switch ($_GET['action']) {
case 'toggleEnabled':
    $newval = (isset($_GET['newval']) && $_GET['newval'] == 1) ? 1 : 0;
    $id = COM_sanitizeId($_GET['id']);

    switch ($_GET['type']) {
    case 'quote':
        DailyQuote\Quote::toggleEnabled($newval, $id);
        break;

    case 'category':
        DailyQuote\Category::toggleEnabled($newval, $id);
        break;

     default:
        exit;
    }

    $result = array(
        'id' => $id,
        'newval' => $newval,
    );
    $result = json_encode($result);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-cache, must-revalidate');
    // A date in the past to force no caching
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    echo $result;
    break;
}

?>
