<?php
/**
 * GN2_NewsletterConnect
 * @package gn2_newsletterconnect
 * @copyright GN2 netwerk
 * @link http://www.gn2-netwerk.de/
 * @author Dave Holloway <dh[at]gn2-netwerk[dot]de>
 * @license GN2 Commercial Addon License
 */

$_Z = array(
    '192.168.1.78',
    '127.0.0.1',
    'ox458ce.gn2-dev.de',
    'localhost'
);

if (!in_array($_SERVER['HTTP_HOST'],$_Z)) {
    header('HTTP/1.0 401 Unauthorized');
    header('Content-Type:text/html');
    while (ob_end_clean()) {}

    $prefix = ($_SERVER['HTTP_PORT'] == 443) ? 'https://' : 'http://';
    $prefix .= $_SERVER['HTTP_HOST'] . '/' . $_SERVER['REQUEST_URI'];
    $subject = '['.$prefix.'] - gn2_newsletterconnect license';
    echo '<p><img src="http://www.gn2-netwerk.de/img/gn2-netwerk.png"></p>';
    echo '<div style="margin:10px 50px;font-size:13px;font-family:monospace;">';
    echo '<p>The gn2_newsletterconnect module (<strong>modules/gn2_newsletterconnect</strong>) is only authorized for use on the following hosts: [<em>' . implode(', ',$_Z) . '</em>].</p>';
    echo '<p>Please write to <a href="mailto:kontakt@gn2-netwerk.de?subject='.$subject.'">kontakt@gn2-netwerk.de</a> for license information.</div>';
    die();
} else {
    /**
     * Defined when all the dependencies are loaded.
     * @var boolean
     */
    define('GN2_NEWSLETTERCONNECT_LOADED',true);
    if (strpos($_SERVER['SCRIPT_NAME'],'api.php') !== false) {
        require_once('classes/Mapper/Abstract.php');
        require_once('classes/Mapper/Categories.php');
        require_once('classes/Mapper/Products.php');
        require_once('classes/Output/Abstract.php');
        require_once('classes/Output/Json.php');
        require_once('classes/Output/Csv.php');
        require_once('classes/Data/Result.php');
        require_once('api.php');
        $x = new gn2_newsletterconnect_Mapper_Products;
    }
}