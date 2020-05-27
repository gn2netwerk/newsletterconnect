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

require_once 'stub.php';


/**
 * GN2_NewsletterConnect - Main OXID Module Initialization Class
 * @category GN2_NewsletterConnect
 * @package  GN2_NewsletterConnect
 * @author   Dave Holloway <dh@gn2-netwerk.de>
 * @license  GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version  Release: <package_version>
 * @link     http://www.gn2-netwerk.de/
 */
class GN2_NewsletterConnect
{
    /**
     * @var array Configuration Array
     */
    public static $config = array();

    /**
     * oxid version
     * @var null
     */
    private static $_OxVersion = null;

    /**
     * oxconfig
     * @var null
     */
    private static $_OxConfig = null;


    /**
     * getEnvironment
     * Returns an instance of the current environment
     * @return GN2_NewsletterConnect_Environment
     */
    public static function getEnvironment()
    {
        $version = self::getOXVersion();

        switch ($version) {
            case 60:
                $env = new GN2_NewsletterConnect_Environment_Oxid60;
                break;

            default:
                $env = new GN2_NewsletterConnect_Environment_Oxid;
                break;
        }

        $env->loadBootstrap();
        return $env;
    }


    /**
     * Main bootstrap function
     * @static
     * @return void
     */
    public static function main()
    {
        try {
            $env = self::getEnvironment();
            self::$config = $env->getModuleConfig();
            $newsletterConnect = new self;
        } catch (\Exception $e) {
            // TODO: Live ErrorTracking
        }
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
    }


    /**
     * Get the oxid version
     * @return int the oxid-version
     */
    public static function getOXVersion()
    {
        $sOXVersion = "";

        if (self::$_OxVersion === null) {
            if (class_exists(\OxidEsales\Eshop\Core\ShopVersion::class)) {
                $oVersionObject = oxNew(\OxidEsales\Eshop\Core\ShopVersion::class);
            }

            if (!$oVersionObject) {
                $oVersionObject = self::getOXConfig();
            }

            if (method_exists($oVersionObject, "getVersion")) {
                $sOXVersion = substr($oVersionObject->getVersion(), 0, 3);
            }

            self::$_OxVersion = intval(str_replace('.', '', $sOXVersion));
        }

        return self::$_OxVersion;
    }


    /**
     * Get oxid config object
     * @return oxConfig the oxid config object
     */
    public static function getOXConfig()
    {
        if (self::$_OxConfig === null) {
            if (!is_object(self::$_OxConfig)) {
                if (class_exists(\OxidEsales\Eshop\Core\Config::class)) {
                    self::$_OxConfig = oxNew(\OxidEsales\Eshop\Core\Config::class);
                }
            }

            if (!is_object(self::$_OxConfig)) {
                if (class_exists("oxRegistry")) {
                    if (method_exists("oxRegistry", "getConfig")) {
                        self::$_OxConfig = oxRegistry::getConfig();
                    }
                }
            }

            if (!is_object(self::$_OxConfig)) {
                if (class_exists("oxConfig")) {
                    if (method_exists("oxConfig", "getInstance")) {
                        self::$_OxConfig = oxConfig::getInstance();
                    }
                }
            }
        }
        return self::$_OxConfig;
    }


    /**
     * Get parameter
     * @param string $sParameter the parameter key
     * @return mixed
     */
    public static function getOXParameter($sParameter)
    {
        $oConfig = self::getOXConfig();

        if (class_exists(\OxidEsales\Eshop\Core\Request::class)) {
            $oRequest = oxNew(\OxidEsales\Eshop\Core\Request::class);
            return $oRequest->getRequestParameter($sParameter);
        }

        if (method_exists($oConfig, "getRequestParameter")) {
            return $oConfig->getRequestParameter($sParameter);
        }

        if (method_exists($oConfig, "getParameter")) {
            return $oConfig->getParameter($sParameter);
        }

        return false;
    }


    /**
     * Get the current session
     * @return mixed
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

}