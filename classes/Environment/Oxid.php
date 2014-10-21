<?php
/**
 * GN2_NewsletterConnect
 *
 * PHP version 5
 *
 * @category   GN2_NewsletterConnect
 * @package    GN2_NewsletterConnect
 * @subpackage Environment
 * @author     Heiko Adams <ha@gn2-netwerk.de>
 * @license    GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version    GIT: <git_id>
 * @link       http://www.gn2-netwerk.de/
 */
/**
 * Generic Oxid class
 *
 * @category   GN2_NewsletterConnect
 * @package    GN2_NewsletterConnect
 * @subpackage Environment
 * @author     Heiko Adams <ha@gn2-netwerk.de>
 * @license    GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version    Release: <package_version>
 * @link       http://www.gn2-netwerk.de/
 */
class GN2_NewsletterConnect_Environment_Oxid
implements GN2_NewsletterConnect_Environment
{

    /**
     * Returns the name of the article table
     *
     * @return string String containing the tablename
     */
    public function getArticleTableName()
    {
        return "oxv_oxarticles_de";
    }

    /**
     * Returns the article long description
     *
     * @return string Emptystring
     */
    public function getArticleLongDesc($article)
    {
        return $article->getLongDesc();
    }

    /**
     * Bootstraps the oxid instance
     *
     * @return void
     */
    public function loadBootstrap()
    {

        if (!function_exists('getShopBasePath')) {
            /**
             * Returns OXID base path
             *
             * @return string OXID Base Path
             */
            function getShopBasePath()
            {
                return $_SERVER['DOCUMENT_ROOT'].'/'
                . dirname(dirname(dirname($_SERVER['SCRIPT_NAME']))).'/';
            }
            include_once getShopBasePath() . 'modules/functions.php';
            include_once getShopBasePath() . 'core/oxfunctions.php';
            oxUtils::getInstance()->stripGpcMagicQuotes();
        }
    }

    public function getModuleConfig()
    {
        $config = oxConfig::getInstance();
        $settings = array();
        $settings['mailingService'] = 'Mailingwork';

        $savedSettings = $config->getShopConfVar('config', null, 'module:gn2_newsletterconnect');

        $settings['service_Mailingwork'] = array(
            'api_baseurl'    => $savedSettings['api_baseurl'],
            'api_username'   => $savedSettings['api_username'],
            'api_password'   => $savedSettings['api_password'],
            'api_signupsetup'=> $savedSettings['api_signupsetup'],
            'use_vouchers'   => $savedSettings['use_vouchers'],
            'voucher_series' => $savedSettings['voucher_series'],

        );
        return $settings;
    }
}
