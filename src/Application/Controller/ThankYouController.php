<?php
/**
 * Gn2_NewsletterConnect
 * @category Gn2_NewsletterConnect
 * @package  Gn2_NewsletterConnect
 * @author   gn2 netwerk <kontakt@gn2.de>
 * @license  Gn2 Commercial Addon License http://www.gn2-netwerk.de/
 * @link     http://www.gn2-netwerk.de/
 */

namespace Gn2\NewsletterConnect\Application\Controller;

use Exception;
use Gn2\NewsletterConnect\Core\Api\WebService\WebService;
use \OxidEsales\Eshop\Application\Model\Country;
use \OxidEsales\Eshop\Application\Model\Order;
use \OxidEsales\Eshop\Application\Model\Article;
use \OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Session;

/**
 * Class ThankYouController
 * @package Gn2\NewsletterConnect\Application\Controller
 */
class ThankYouController extends ThankYouController_parent
{

    /**
     * ThankYouController constructor.
     * Transfers current order to WebService on thank you page
     */
    public function __construct()
    {
        try {
            $items = array();

            $oSession = oxNew(Session::class);
            $oBasket = $oSession->getBasket();
            $orderId = $oBasket->getOrderId();

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
                        try {
                            $oDb = DatabaseProvider::getDb();
                            $category = $oDb->getOne(
                                'select oxtitle from oxcategories where OXID = "' .
                                $shopCategory->oxcategories__oxid->value . '" LIMIT 1'
                            );
                        } catch (Exception $e) {
                            /* Do nothing */
                        }
                    }
                }
                $items[$i]['ItemVariant'] = $category;

                // ITEM PRICE
                $items[$i]['ItemPrice'] = $oxArticle->oxorderarticles__oxbprice->value;

                // QUANTITY
                $items[$i]['ItemQuantity'] = $oxArticle->oxorderarticles__oxamount->value;

                $i++;
            }

            $oWebService = oxNew( WebService::class );
            $oUser = $this->getUser();

            $oWebServiceUser = $oWebService->getRecipientByEmail(
                $oUser->oxuser__oxusername->rawValue
            );

            $bCountry = oxNew(Country::class);
            $bCountry->load($oxOrder->oxorder__oxbillcountryid->rawValue);
            $dCountry = oxNew(Country::class);
            $dCountry->load($oxOrder->oxorder__oxdelcountryid->rawValue);

            $billCountry = $bCountry->oxcountry__oxisoalpha2->rawValue;

            if ($bCountry->oxcountry__oxisoalpha2->rawValue != "") {
                $delCountry = $bCountry->oxcountry__oxisoalpha2->rawValue;
            } else {
                $delCountry = $bCountry;
            }

            if ($oWebServiceUser !== null) {

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

                $oWebService->transferOrder(
                    $oWebServiceUser,
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