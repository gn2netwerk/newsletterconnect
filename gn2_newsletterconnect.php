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

    /**
     * getEnvironment
     * Returns an instance of the current environment
     *
     * @return GN2_NewsletterConnect_Environment
     */
    public static function getEnvironment()
    {
        if (file_exists(dirname(__FILE__) . '/../../bootstrap.php')) {
            $env = new GN2_NewsletterConnect_Environment_Oxid47();
            $env->loadBootstrap();
        } else {
            $env = new GN2_NewsletterConnect_Environment_Oxid();
            $env->loadBootstrap();
        }

        $oxver = substr(oxconfig::getInstance()->getVersion(), 0, 3);
        $oxver = intval(str_replace('.', '', $oxver));

        switch($oxver){
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
            include_once dirname(__FILE__).'/settings.php';
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

}