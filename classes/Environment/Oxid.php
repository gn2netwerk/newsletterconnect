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
        // Kristian Berger: Erweiterung der Config Einstellungen um akt. Shop Id (für Multishops notwendig)
        $sShopId = oxRegistry::getConfig()->getShopId();
        $savedSettings = $config->getShopConfVar('config_' . $sShopId, null, 'module:gn2_newsletterconnect');
        $settings['service_Mailingwork'] = $savedSettings;
        return $settings;
    }
}
