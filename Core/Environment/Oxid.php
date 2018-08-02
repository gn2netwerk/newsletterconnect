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

/**
 * Generic Oxid class
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
        $root = dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME'])));
        include_once $root . '/bootstrap.php';
    }

    /**
     * @return array|mixed
     */
    public function getModuleConfig()
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
