<?php
/**
 * @copyright   (c) gn2
 * @link        https://www.gn2.de/
 */

namespace Gn2\NewsletterConnect\Core;

use Gn2\NewsletterConnect\Core\Api\Help\Utilities;
use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Request;

/**
 * Class Output
 * @package Gn2\NewsletterConnect\Api
 */
class Output extends Output_parent
{

    /**
     * Output constructor.
     * Handles profile manager requests:
     * https://www.domain.tld/?mos_api=1&mode=updateUser&email=bodo@mail.gn2-dev.de&firstname=Bodo&lastname=Ballerboehme
     */
    public function __construct()
    {
        $api = Registry::get(Request::class)->getRequestEscapedParameter('mos_api');

        if ($api == 1) {
            if (Utilities::isIpAuthorized()) {
                $oDb = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC);
                $oConfig = Registry::getConfig();

                $sMode = Registry::get(Request::class)->getRequestEscapedParameter('mode');
                
                switch ($sMode) {
                    case "getVoucher":
                        $aSettings = Utilities::getSettings();
                        $voucherSeries = $aSettings['voucher_series'];
                        $voucherNr = "";

                        if ($voucherSeries != "") {
                            $sql = 'SELECT `OXID`, `OXVOUCHERNR` FROM `oxvouchers` 
                                    WHERE `OXVOUCHERSERIEID` = ' . $oDb->quote($voucherSeries) . '
                                        AND `OXUSERID` = "" AND `OXRESERVED` = 0 AND `OXTIMESTAMP` <> "1984-01-01 08:00:00"';

                            $voucherRow = $oDb->getRow($sql);

                            if ($voucherRow) {
                                $oDb->execute('UPDATE `oxvouchers` SET `OXTIMESTAMP` = "1984-01-01 08:00:00" WHERE `OXID` = ' . $oDb->quote($voucherRow['OXID']));
                                $voucherNr = $voucherRow['OXVOUCHERNR'];
                            }
                        }

                        header('Content-Type:application/javascript');
                        echo json_encode(array(
                            'voucher' => $voucherNr
                        ));
                        die();

                    case "updateUser":
                        $response = array();
                        $response['msg'] = 'error';

                        $email = Registry::get(Request::class)->getRequestEscapedParameter('email');

                        if ($email != "") {
                            $sql = 'SELECT `OXID` FROM `oxuser` WHERE `OXUSERNAME` = ' . $oDb->quote($email) . ' 
                                    AND `OXSHOPID` = ' . $oDb->quote($oConfig->getShopId()) . ' LIMIT 1';

                            $oxid = $oDb->getOne($sql);

                            if ($oxid != "") {
                                $oUser = oxNew(User::class);
                                $oUser->load($oxid);

                                $title = Registry::get(Request::class)->getRequestEscapedParameter('title');

                                switch (strtolower($title)) {
                                    case "mr":
                                    case "herr":
                                        $title = 'MR';
                                        break;
                                    case "mrs":
                                    case "frau":
                                        $title = 'MRS';
                                        break;
                                    default:
                                        $title = '';
                                        break;
                                }

                                if ($title != "") {
                                    $oUser->oxuser__oxsal->rawValue = $title;
                                }

                                $oUser->oxuser__oxfname->rawValue = Registry::get(Request::class)->getRequestEscapedParameter('firstname');
                                $oUser->oxuser__oxlname->rawValue = Registry::get(Request::class)->getRequestEscapedParameter('lastname');

                                if ($oUser->save()) {
                                    $response['msg'] = 'ok';
                                }
                            }
                        }

                        header('Content-Type:application/javascript');
                        echo json_encode($response);
                        die();

                    default:
                        // error: mode not found
                        header('Content-Type:application/javascript');
                        echo json_encode(array(
                            'msg' => 'error'
                        ));
                        die();
                }
            } else {
                // error: not authorized
                header('Content-Type:application/javascript');
                echo json_encode(array(
                    'msg' => 'error'
                ));
                die();
            }
        }

        parent::__construct();
    }

}
