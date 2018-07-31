<?php
/**
 * GN2_NewsletterConnect
 * @category GN2_NewsletterConnect
 * @package  GN2_NewsletterConnect
 * @author   gn2 netwerk <kontakt@gn2.de>
 * @license  GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version  GIT: <git_id>
 * @link     http://www.gn2-netwerk.de/
 */
ob_start();

define('GN2_NEWSLETTERCONNECT_LOADED', 1);
define('EXPORTDIR', 'gn2_newsletterconnect/');

include_once dirname(__FILE__) . 'Core/Environment/Environment.php';
include_once dirname(__FILE__) . 'Core/Environment/Oxid.php';
include_once dirname(__FILE__) . 'Core/Environment/Oxid602.php';
include_once dirname(__FILE__) . 'Core/Export/Export.php';
include_once dirname(__FILE__) . 'Core/Help/Utilities.php';

if (strpos($_SERVER['SCRIPT_NAME'], 'api.php') !== false) {
    include_once dirname(__FILE__) . 'Core/Mapper/Abstract.php';
    include_once dirname(__FILE__) . 'Core/Mapper/Categories.php';
    include_once dirname(__FILE__) . 'Core/Mapper/Products.php';
    include_once dirname(__FILE__) . 'Core/Output/Abstract.php';
    include_once dirname(__FILE__) . 'Core/Output/Json.php';
    include_once dirname(__FILE__) . 'Core/Output/Csv.php';
    include_once dirname(__FILE__) . 'Core/Data/Result.php';
    include_once 'api.php';
}

include_once dirname(__FILE__) . 'Core/Exception/MailingService.php';
include_once dirname(__FILE__) . 'Core/WebService/Abstract.php';
include_once dirname(__FILE__) . 'Core/WebService/Curl.php';
include_once dirname(__FILE__) . 'Core/Mailing/List.php';
include_once dirname(__FILE__) . 'Core/Mailing/Recipient.php';
include_once dirname(__FILE__) . 'Core/MailingService/Interface.php';
include_once dirname(__FILE__) . 'Core/MailingService/MailingWork.php';

if (defined('GN2_NEWSLETTERCONNECT_LOADED')) {
    GN2_NewsletterConnect::main();
}
