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
