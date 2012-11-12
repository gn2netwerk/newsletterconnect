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

require_once 'copyprotect.php';

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
     * Starts looplinker
     *
     */
    private function __construct()
    {
        $this->looplink('core');
        $this->looplink('admin');
        $this->looplink('out');
        $this->looplink('views');
    }

    /**
     * looplink($directory);
     * Automatically symlink every file in a specified folder
     * within the module folder to a file with the same path
     * in the root OXID directory.
     *
     * @param string  $dir    Folder to link
     * @param boolean $append Prepend module folder to path yes/no
     *
     * @return void
     */
    function looplink($dir = '',$append=true)
    {
        if (isAdmin()) {
            return true;
        }

        $me = getCwd();
        $moduleSlug = '/modules/'.basename(dirname(__FILE__)).'/';
        if ($append) {
            $me .= $moduleSlug;
        }
        $me .= str_replace(getCwd(), '', $dir);

        if (!file_exists($me)) {
            return false;
        }

        if ($handle = opendir($me)) {
            $files = array();

            while (false !== ($file = readdir($handle))) {
                $path = $me.'/'. $file;
                if (filetype($path) == "dir" && $file != "." && $file != "..") {
                    $this->looplink($path, false);
                } else if ($file != "." && $file != "..") {
                    if ( !in_array(
                        basename($file),
                        array('.DS_Store','.project','.git','.gitignore')
                    )
                    ) {
                        $src = $path;
                        $dest = str_replace($moduleSlug, '/', $src);

                        if (!is_link($dest)) {
                            @symlink($src, $dest);
                        }
                    }
                }
            }
            closedir($handle);
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


/**
 * GN2_NewsletterConnect_Oxoutput - Dummy Class. We're only loading OXOUTPUT as a bootstrap.
 *
 * @category GN2_NewsletterConnect
 * @package  GN2_NewsletterConnect
 * @author   Dave Holloway <dh@gn2-netwerk.de>
 * @license  GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version  Release: <package_version>
 * @link     http://www.gn2-netwerk.de/
 */
class GN2_NewsletterConnect_OxOutput extends GN2_NewsletterConnect_OxOutput_parent
{
    public function __construct() {
        if (oxConfig::getParameter('cl') == 'thankyou') {
            $this->gn2NewstterConnect_transferOrder();
        }

    }

    public function gn2NewstterConnect_transferOrder() {
        global $myConfig;
        $items = array();
        $oxOrder = $myConfig->getActiveView()->getOrder();
        $oxArticles = $oxOrder->getOrderArticles(true);

        $i = 0;
        foreach ($oxArticles as $oxArticle) {
            // ID
            $items[$i]['OrderID'] = $oxOrder->oxorder__oxordernr->value;

            // SKU - Eindeutige Artikelnummer
            $items[$i]['ItemSKU'] = $oxArticle->oxorderarticles__oxartnum->value;

            // ITEMNAME
            $items[$i]['ItemName'] = $oxArticle->oxorderarticles__oxtitle->value;

            // VARIANT
            $category = '';
            $shopArticle = oxNew('oxarticle');
            $shopArticle->load($oxArticle->oxorderarticles__oxartid->value);
            if (is_object($shopArticle)) {
                $shopCategory = $shopArticle->getCategory();
                if (is_object($shopCategory)) {
                    $oDb = oxDb::getDb();
                    $category = $oDb->getOne('select oxtitle from oxcategories where OXID = "'.$shopCategory->oxcategories__oxid->value.'" LIMIT 1');
                }
            }
            $items[$i]['ItemVariant'] = $category;

            // ITEM PRICE
            $items[$i]['ItemPrice'] = $oxArticle->oxorderarticles__oxbprice->value;

            // QUANTITY
            $items[$i]['ItemQuantity'] = $oxArticle->oxorderarticles__oxamount->value;

            $i++;
        }

        /* Get existing MailingService */
        $mailingServiceUser = GN2_NewsletterConnect::getMailingService()->getRecipientByEmail(
            $this->getUser()->oxuser__oxusername->rawValue
        );

        $bCountry = oxNew('oxcountry');
        $bCountry->load($oxOrder->oxorder__oxbillcountryid->rawValue);
        $dCountry = oxNew('oxcountry');
        $dCountry->load($oxOrder->oxorder__oxdelcountryid->rawValue);

        $billCountry = $bCountry->oxcountry__oxisoalpha2->rawValue;
        $delCountry = ($bCountry->oxcountry__oxisoalpha2->rawValue != "") ? $bCountry->oxcountry__oxisoalpha2->rawValue : $bCountry;

        if ($mailingServiceUser !== null) {

            $count = 0;
            foreach ($items as $item) {
                $count = $count + $item['ItemQuantity'];
            }

            $basketData = array(
                'orderDate' => $oxOrder->oxorder__oxorderdate->rawValue,
                'basketPrice' => number_format($oxOrder->oxorder__oxtotalbrutsum->rawValue, 2, '.', ''),
                'billCountry' => $billCountry,
                'deliveryCountry' => $delCountry,
                'productCount' => $count,
            );

            GN2_NewsletterConnect::getMailingService()->transferOrder(
                $mailingServiceUser,
                $basketData,
                $items
            );
            die('loooool');
        }

    }
}

if (defined('GN2_NEWSLETTERCONNECT_LOADED')) {
    GN2_NewsletterConnect::main();
}