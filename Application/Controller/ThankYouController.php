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

namespace GN2\NewsletterConnect\Application\Controller;

if (!class_exists('GN2_NewsletterConnect')) {
    include dirname(__FILE__) . '/../../gn2_newsletterconnect.php';
}

use \GN2_NewsletterConnect;
use OxidEsales\Eshop\Application\Model\Country;
use \OxidEsales\Eshop\Application\Model\Order;
use \OxidEsales\Eshop\Application\Model\Article;
use \OxidEsales\Eshop\Core\DatabaseProvider;

/**
 * Class ThankYouController
 * @package GN2\NewsletterConnect\Application\Controller
 */
class ThankYouController extends ThankYouController_parent
{
    /**
     * Constructor
     * Transfers current order to MailingService on thank you page
     */
    public function __construct()
    {
        try {
            global $myConfig;
            $items = array();
            $myConfig = GN2_NewsletterConnect::getOXConfig();

            $orderId = GN2_NewsletterConnect::getOXSession()->getBasket()->getOrderId();
            $oxOrder = oxNew(Order::class);
            $oxOrder->load($orderId);

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
                $shopArticle = oxNew(Article::class);
                $shopArticle->load($oxArticle->oxorderarticles__oxartid->value);
                if (is_object($shopArticle)) {
                    $shopCategory = $shopArticle->getCategory();
                    if (is_object($shopCategory)) {
                        $oDb = DatabaseProvider::getDb();
                        $category = $oDb->getOne(
                            'select oxtitle from oxcategories where OXID = "' .
                            $shopCategory->oxcategories__oxid->value . '" LIMIT 1'
                        );
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

            $bCountry = oxNew(Country::class);
            $bCountry->load($oxOrder->oxorder__oxbillcountryid->rawValue);
            $dCountry = oxNew(\OxidEsales\Eshop\Application\Model\Country::class);
            $dCountry->load($oxOrder->oxorder__oxdelcountryid->rawValue);

            $billCountry = $bCountry->oxcountry__oxisoalpha2->rawValue;

            if ($bCountry->oxcountry__oxisoalpha2->rawValue != "") {
                $delCountry = $bCountry->oxcountry__oxisoalpha2->rawValue;
            } else {
                $delCountry = $bCountry;
            }

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
        } catch (Exception $e) {
            /* ignore any gn2_newsletterconnect errors. */
        }
        parent::__construct();
    }
}