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

namespace GN2\NewsletterConnect\Core;

use GN2\NewsletterConnect\Api\Help\Utilities;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Request;

/**
 * Class Output
 * @package GN2\NewsletterConnect\Api
 */
class Output extends Output_parent
{

    /**
     * Output constructor.
     */
    public function __construct()
    {
        $api = Registry::get(Request::class)->getRequestEscapedParameter('mos_api');

        if ($api == 1) {
            $savedSettings = Utilities::getConfig();

            if (isset($savedSettings['api_ips'])) {
                $ips = explode("\n", $savedSettings['api_ips']);
                foreach ($ips as $k => $v) {
                    $ips[$k] = trim($v);
                }
                if (in_array($_SERVER['REMOTE_ADDR'], $ips) && isset($savedSettings['voucher_series']) && $savedSettings['voucher_series'] != "") {
                    header('Content-Type:application/javascript');

                    $oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();

                    // TODO: multishop support may be not given..?
                    $mode = Registry::get(Request::class)->getRequestEscapedParameter('mode');
                    switch ($mode) {
                        case "getVoucher":
                            $voucherSeries = $savedSettings['voucher_series'];
                            if ($voucherSeries != "") {
                                $sql = 'SELECT OXID, OXVOUCHERNR FROM oxvouchers WHERE OXVOUCHERSERIEID = ' . $oDb->quote($voucherSeries);
                                $sql .= ' && OXUSERID="" && OXRESERVED=0 && OXTIMESTAMP<>"1984-01-01 08:00:00"';

                                $voucherRow = $oDb->getRow($sql);
                                if ($voucherRow) {
                                    $oDb->execute('UPDATE oxvouchers SET OXTIMESTAMP="1984-01-01 08:00:00" WHERE OXID="' . $voucherRow[0] . '"');
                                    $voucher = $voucherRow[1];
                                }
                            }
                            echo json_encode(array(
                                'voucher' => $voucher
                            ));
                            die();
                        case "updateUser":
                            $email = Registry::get(Request::class)->getRequestEscapedParameter('email');
                            $sql = 'select oxid from oxuser where OXUSERNAME = ' . $oDb->quote($email) . ' LIMIT 1';
                            $oxid = $oDb->getOne($sql);
                            $response = array();
                            $response['msg'] = 'error';
                            if ($oxid != "") {
                                $oUser = oxNew(\OxidEsales\Eshop\Application\Model\User::class);
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
                            echo json_encode($response);

                            die();
                    }
                }
            }
        }
        parent::__construct();
    }
}
