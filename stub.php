<?php
/**
 * GN2_NewsletterConnect
 *
 * PHP version 5
 *
 * @category GN2_NewsletterConnect
 * @package  GN2_NewsletterConnect
 * @author   Dave Holloway <dh@gn2-netwerk.de>
 * @license  GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version  GIT: <git_id>
 * @link     http://www.gn2-netwerk.de/
 */
ob_start();

$_Z = array(
    'localhost',
    'localhost:8888',
    'mos.ce.448',
);

$_Q = $_SERVER['REQUEST_URI'];

if (!in_array($_SERVER['HTTP_HOST'], $_Z) && strpos($_Q, '/admin/') === false) {
    header('HTTP/1.0 401 Unauthorized');
    header('Content-Type:text/html');
    while (@ob_end_clean()) {
    }

    $httpport = '80';
    if (isset($_SERVER['HTTP_PORT'])) {
        $httpport = $_SERVER['HTTP_PORT'];
    } elseif (isset($_SERVER['SERVER_PORT'])) {
        $httpport = $_SERVER['SERVER_PORT'];
    }

    $prefix = ($httpport == 443) ? 'https://' : 'http://';
    $prefix .= $_SERVER['HTTP_HOST'] . '/' . $_SERVER['REQUEST_URI'];
    $subject = '['.$prefix.'] - gn2_newsletterconnect license';
    echo '<div style="text-align: center;">';
    echo '<p><img src="http://www.gn2-netwerk.de/img/gn2-netwerk.png"></p>';
    echo '<div style="margin:25px 50px;font-size:13px;font-family:monospace;">';
    echo '<p>Das gn2_newsletterconnect Modul '
            . '(<strong>modules/gn2_newsletterconnect</strong>) '
            . 'wurde nur f&uuml;r die folgenden Hosts freigeschalten:'
            . ' [<em>' . implode(', ', $_Z) . '</em>].</p>';
    echo '<p>Um weitere Hosts freizuschalten, bitte setzen Sie sich mit uns in Verbindung: <a href="mailto:kontakt@gn2-netwerk.de?subject='
            . $subject.'">kontakt@gn2-netwerk.de</a>.</div>';
    echo '</div>';
    die();
} else {
    define('GN2_NEWSLETTERCONNECT_LOADED', 1);

    include_once 'classes/Environment/Environment.php';
    include_once 'classes/Environment/Oxid.php';
    include_once 'classes/Environment/Oxid44.php';
    include_once 'classes/Environment/Oxid47.php';

    if (strpos($_SERVER['SCRIPT_NAME'], 'api.php') !== false) {
        include_once 'classes/Mapper/Abstract.php';
        include_once 'classes/Mapper/Categories.php';
        include_once 'classes/Mapper/Products.php';
        include_once 'classes/Output/Abstract.php';
        include_once 'classes/Output/Json.php';
        include_once 'classes/Output/Csv.php';
        include_once 'classes/Data/Result.php';
        include_once 'api.php';
    }
    include_once 'classes/Exception/MailingService.php';
    include_once 'classes/WebService/Abstract.php';
    include_once 'classes/WebService/Curl.php';
    include_once 'classes/Mailing/List.php';
    include_once 'classes/Mailing/Recipient.php';
    include_once 'classes/MailingService/Interface.php';
    include_once 'classes/MailingService/MailingWork.php';

    if (defined('GN2_NEWSLETTERCONNECT_LOADED')) {
        GN2_NewsletterConnect::main();
    }
}