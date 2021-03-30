<?php

use \OxidEsales\Eshop\Core\Registry;
use \OxidEsales\Eshop\Core\Request;

/**
 * GN2_NewsletterConnect
 * @category GN2_NewsletterConnect
 * @package  GN2_NewsletterConnect
 * @author   gn2 netwerk <kontakt@gn2.de>
 * @license  GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version  GIT: <git_id>
 * @link     http://www.gn2-netwerk.de/
 */
class GN2_NewsletterConnect
{
    /**
     * @var array Configuration Array
     */
    public static $config = array();


    /**
     * Constructor.
     */
    public function __construct()
    {

        // TODO: maybe useless include because of class-autoloader?
        include_once dirname(__FILE__) . '/Core/Export/Export.php';
        include_once dirname(__FILE__) . '/Core/Help/Utilities.php';

        // TODO?
        if (strpos($_SERVER['SCRIPT_NAME'], 'api.php') !== false) {
            include_once dirname(__FILE__) . '/Core/Mapper/MapperAbstract.php';
            include_once dirname(__FILE__) . '/Core/Mapper/Categories.php';
            include_once dirname(__FILE__) . '/Core/Mapper/Products.php';
            include_once dirname(__FILE__) . '/Core/Output/OutputAbstract.php';
            include_once dirname(__FILE__) . '/Core/Output/Json.php';
            include_once dirname(__FILE__) . '/Core/Output/Csv.php';
            include_once dirname(__FILE__) . '/Core/Data/Result.php';
            include_once 'api.php';
        }

        include_once dirname(__FILE__) . '/Core/WebService/WebServiceAbstract.php';
        include_once dirname(__FILE__) . '/Core/WebService/Curl.php';
        include_once dirname(__FILE__) . '/Core/Mailing/MailingList.php';
        include_once dirname(__FILE__) . '/Core/Mailing/Recipient.php';
        include_once dirname(__FILE__) . '/Core/MailingService/MailingServiceInterface.php';
        include_once dirname(__FILE__) . '/Core/MailingService/MailingWork.php';
    }


    /**
     * Generates the relevant child instance of \GN2\NewsletterConnect\Core\MailingService, depending on settings.php
     * @static
     * @return mixed
     * @throws Exception
     */
    public static function getMailingService()
    {
        if (isset(self::$config['mailingService'])) {
            $key = self::$config['mailingService'];

            // TODO: maybe without the first slash? this "inclusion" is sloppy anyways
            $className = '\GN2\NewsletterConnect\Core\MailingService\\' . $key;

            if (class_exists($className)) {
                $config = (isset(self::$config['service_' . $key])) ? self::$config['service_' . $key] : array();
                return new $className($config);
            }
            throw new Exception('gn2_newsletterConnect- Cannot find class:' . $className);
        }

        // TODO: not returning anything is mailingservice can't be detected...
    }

    /**
     * Returns the name of the article table
     *
     * @return string String containing the tablename
     */
    public static function getArticleTableName()
    {
        return "oxv_oxarticles_de";
    }

    /**
     * @return array|mixed
     */
    public static function getModuleConfig()
    {
        $oConfig = Registry::getConfig();
        $settings = array();
        $settings['mailingService'] = 'Mailingwork';

        // Kristian Berger: Erweiterung der Config Einstellungen um akt. Shop Id (für Multishops notwendig)
        $sShopId = $oConfig->getShopId();

        $savedSettings = $oConfig->getShopConfVar('config_' . $sShopId, null, 'module:gn2_newsletterconnect');
        $settings['service_Mailingwork'] = $savedSettings;
        return $settings;
    }

}