<?php
if (!class_exists('GN2_NewsletterConnect')) include(dirname(__FILE__).'/gn2_newsletterconnect.php');

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

/**
 * GN2_NewsletterConnect_ThankYou
 *
 * @category GN2_NewsletterConnect
 * @package  GN2_NewsletterConnect
 * @author   Dave Holloway <dh@gn2-netwerk.de>
 * @license  GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version  Release: <package_version>
 * @link     http://www.gn2-netwerk.de/
 */
class GN2_NewsletterConnect_ThankYou extends GN2_NewsletterConnect_ThankYou_parent
{
    public function __construct() {
        try {
            global $myConfig;
            $items = array();
            $myConfig = oxConfig::getInstance();

            $view = $myConfig->getActiveView();
            /* This method is not available in OXID 4.4.x */
            if (method_exists($view, 'getOrder')) {
                $oxOrder = $view->getOrder();
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
                }
            }
        } catch (Exception $e) {
            /* ignore any gn2_newsletterconnect errors. */
        }
        parent::__construct();
    }
}