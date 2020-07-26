<?php

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
class GN2_NewsletterConnect extends \OxidEsales\Eshop\Core\Base
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

        // TODO: this may be useless.. and wrong folder anyways.
        define('EXPORTDIR', 'gn2_newsletterconnect/');


        // TODO: maybe useless include because of class-autoloader?
        include_once dirname(__FILE__) . '/Core/Export/Export.php';
        include_once dirname(__FILE__) . '/Core/Help/Utilities.php';

        // TODO?
        if (strpos($_SERVER['SCRIPT_NAME'], 'api.php') !== false) {
            include_once dirname(__FILE__) . '/Core/Mapper/Abstract.php';
            include_once dirname(__FILE__) . '/Core/Mapper/Categories.php';
            include_once dirname(__FILE__) . '/Core/Mapper/Products.php';
            include_once dirname(__FILE__) . '/Core/Output/Abstract.php';
            include_once dirname(__FILE__) . '/Core/Output/Json.php';
            include_once dirname(__FILE__) . '/Core/Output/Csv.php';
            include_once dirname(__FILE__) . '/Core/Data/Result.php';
            include_once 'api.php';
        }

        include_once dirname(__FILE__) . '/Core/Exception/MailingService.php';
        include_once dirname(__FILE__) . '/Core/WebService/Abstract.php';
        include_once dirname(__FILE__) . '/Core/WebService/Curl.php';
        include_once dirname(__FILE__) . '/Core/Mailing/List.php';
        include_once dirname(__FILE__) . '/Core/Mailing/Recipient.php';
        include_once dirname(__FILE__) . '/Core/MailingService/Interface.php';
        include_once dirname(__FILE__) . '/Core/MailingService/MailingWork.php';
    }


    /**
     * Generates the relevant child instance of GN2_NewsletterConnect_MailingService, depending on settings.php
     * @static
     * @return mixed
     * @throws Exception
     */
    public static function getMailingService()
    {
        if (isset(self::$config['mailingService'])) {
            $key = self::$config['mailingService'];
            $className = 'GN2_NewsletterConnect_MailingService_' . $key;
            if (class_exists($className)) {
                $config = (isset(self::$config['service_' . $key])) ? self::$config['service_' . $key] : array();
                return new $className($config);
            }
            throw new Exception('gn2_newsletterConnect- Cannot find class:' . $className);
        }

        // TODO: not returning anything is mailingservice can't be detected...
    }


    /**
     * Get parameter
     * @param string $sParameter the parameter key
     * @return mixed
     *
     * TODO
     */
    public static function getOXParameter($sParameter)
    {
        $oRequest = oxNew(Request::class);

        if (class_exists(\OxidEsales\Eshop\Core\Request::class)) {

            if (method_exists($oRequest, "getRequestEscapedParameter")) {
                return $oRequest->getRequestEscapedParameter($sParameter);
            }

            if (method_exists($oRequest, "getRequestParameter")) {
                return $oRequest->getRequestParameter($sParameter);
            }
        }

        if (class_exists(\OxidEsales\EshopCommunity\Core\Registry::class)) {
            $oConfig = \OxidEsales\EshopCommunity\Core\Registry::getConfig();

            if (method_exists($oConfig, "getRequestParameter")) {
                return $oConfig->getRequestParameter($sParameter);
            }

            if (method_exists($oConfig, "getParameter")) {
                return $oConfig->getParameter($sParameter);
            }
        }

        return false;
    }


    /**
     * Get the current session
     * @return mixed
     *
     * TODO
     */
    public static function getOXSession()
    {
        if (class_exists(\OxidEsales\Eshop\Core\Session::class)) {
            return oxNew(\OxidEsales\Eshop\Core\Session::class);
        }

        if (class_exists("oxRegistry")) {
            if (method_exists(oxRegistry, "getSession")) {
                return oxRegistry::getSession();
            }
        }

        if (class_exists("oxsession")) {
            if (method_exists(oxsession, "getInstance")) {
                return oxSession::getInstance();
            }
        }

        return false;
    }


    /**
     * @param $sName
     * @return bool
     *
     * TODO
     */
    public static function getOXSessionVariable($sName)
    {
        $oSession = self::getOXSession();

        if (is_object($oSession)) {
            if (method_exists($oSession, "getVariable")) {
                $oSession->getVariable($sName);
                return true;
            }

            if (method_exists($oSession, "getVar")) {
                $oSession->getVar($sName);
                return true;
            }
        }

        return false;
    }


    /**
     * @param $sName
     * @param $sValue
     * @return bool
     *
     * TODO
     */
    public static function setOXSessionVariable($sName, $sValue)
    {
        $oSession = self::getOXSession();

        if (is_object($oSession)) {
            if (method_exists($oSession, "setVariable")) {
                $oSession->setVariable($sName, $sValue);
                return true;
            }

            if (method_exists($oSession, "setVar")) {
                $oSession->setVar($sName, $sValue);
                return true;
            }
        }

        return false;
    }


    /**
     * @param $sName
     * @return bool
     *
     * TODO
     */
    public static function deleteOXSessionVariable($sName)
    {
        $oSession = self::getOXSession();

        if (is_object($oSession)) {
            if (method_exists($oSession, "deleteVariable")) {
                $oSession->deleteVariable($sName);
                return true;
            }

            if (method_exists($oSession, "delVar")) {
                $oSession->delVar($sName);
                return true;
            }
        }

        return false;
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
     * Returns the article long description
     *
     * @return string Emptystring
     */
    public static function getArticleLongDesc($article)
    {
        return $article->getLongDesc();
    }

    /**
     * @return array|mixed
     */
    public static function getModuleConfig()
    {
        $config = GN2_NewsletterConnect::getOXConfig();
        $settings = array();
        $settings['mailingService'] = 'Mailingwork';
        // Kristian Berger: Erweiterung der Config Einstellungen um akt. Shop Id (für Multishops notwendig)
        $sShopId = $config->getShopId();
        $savedSettings = $config->getShopConfVar('config_' . $sShopId, null, 'module:gn2_newsletterconnect');
        $settings['service_Mailingwork'] = $savedSettings;
        return $settings;
    }

}