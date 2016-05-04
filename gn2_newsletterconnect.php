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

require_once 'stub.php';

/**
 * GN2_NewsletterConnect - Main OXID Module Initialization Class
 *
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

    private  static $_OxVersion = null;
    private  static $_OxConfig = null;

    /**
     * getEnvironment
     * Returns an instance of the current environment
     *
     * @return GN2_NewsletterConnect_Environment
     */
    public static function getEnvironment()
    {

        $root = dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME'])));
        if (file_exists($root.'/bootstrap.php')) {
            $env = new GN2_NewsletterConnect_Environment_Oxid47();
            $env->loadBootstrap();
        } else {
            $env = new GN2_NewsletterConnect_Environment_Oxid();
            $env->loadBootstrap();
        }

        switch(self::getOXVersion()){
            case 44:
                return new GN2_NewsletterConnect_Environment_Oxid44();
            case 47:
                return new GN2_NewsletterConnect_Environment_Oxid47();
            default:
                return new GN2_NewsletterConnect_Environment_Oxid();
        }
    }

    /**
     * Main bootstrap function
     *
     * @static
     *
     * @return void
     */
    public static function main()
    {
        try {
            $env = self::getEnvironment();
            self::$config = $env->getModuleConfig();

            $newsletterConnect = new self;
        } catch (Exception $e) {
            // TODO: Live ErrorTracking
        }
    }


    /**
     * Generates the relevant child instance of GN2_NewsletterConnect_MailingService, depending on settings.php
     *
     * @static
     * @return mixed
     * @throws Exception
     */
    public static function getMailingService()
    {
        if (isset(self::$config['mailingService'])) {
            $key = self::$config['mailingService'];
            $className = 'GN2_NewsletterConnect_MailingService_'.$key;
            if (class_exists($className)) {
                $config = (isset(self::$config['service_'.$key])) ? self::$config['service_'.$key] : array();
                return new $className($config);
            }
            throw new Exception('gn2_newsletterConnect- Cannot find class:'.$className);
        }
    }



    public static function getOXVersion(){
        if (self::$_OxVersion === null){
            $oxConfig = self::getOXConfig();

            $sOXVersion = substr($oxConfig->getVersion(), 0, 3);
            self::$_OxVersion = intval(str_replace('.', '', $sOXVersion));
        }

        return self::$_OxVersion;
    }


    public static function getOXConfig(){
        if(self::$_OxConfig === null){
            if (class_exists ( "oxconfig")) {
                if (method_exists (oxconfig, "getInstance") ) {
                    self::$_OxConfig =  oxconfig::getInstance();
                }
            }

            self::$_OxConfig = oxRegistry::getConfig();
        }
        return self::$_OxConfig;
    }


    public static function getOXParameter($sParameter){
        if (self::getOXVersion() < 49) {
            return oxConfig::getParameter($sParameter);
        } else {
            return oxRegistry::getConfig()->getRequestParameter($sParameter);
        }

    }

    public static function getOXSession(){
        if (class_exists ( "oxsession")) {
            if (method_exists (oxsession, "getInstance") ) {
                return  oxSession::getInstance();
            }
        }

        return oxRegistry::getSession();
    }

}