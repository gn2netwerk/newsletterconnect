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

require_once dirname(__FILE__) . '/../gn2_newsletterconnect.php';

use \GN2_NewsletterConnect;

/**
 * Class Output
 * @package GN2\NewsletterConnect\Core
 */
class Output extends Output_parent
{

    /**
     * Output constructor.
     */
    public function __construct()
    {
        $api = $_REQUEST['mos_api'];

        if ($api == 1) {
            $config = GN2_NewsletterConnect::getOXConfig();

            // Kristian Berger: Erweiterung der Config Einstellungen um akt. Shop Id (für Multishops notwendig)
            $sShopId = $config->getShopId();
            $savedSettings = (array)$config->getShopConfVar('config_' . $sShopId, null, 'module:gn2_newsletterconnect');

            if (isset($savedSettings['api_ips'])) {
                $ips = explode("\n", $savedSettings['api_ips']);
                foreach ($ips as $k => $v) {
                    $ips[$k] = trim($v);
                }
                if (in_array($_SERVER['REMOTE_ADDR'], $ips) && isset($savedSettings['voucher_series']) && $savedSettings['voucher_series'] != "") {
                    header('Content-Type:application/javascript');

                    $oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();

                    $mode = $_REQUEST['mode'];
                    switch ($mode) {
                        case "getVoucher":
                            $voucherSeries = $savedSettings['voucher_series'];
                            if ($voucherSeries != "") {
                                $sql = 'SELECT OXID, OXVOUCHERNR FROM oxvouchers WHERE OXVOUCHERSERIEID = "' . $oDb->escapeString($voucherSeries) . '"';
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
                            $sql = 'select oxid from oxuser where OXUSERNAME = ' . $oDb->quote($_REQUEST['email']) . ' LIMIT 1';
                            $oxid = $oDb->getOne($sql);
                            $response = array();
                            $response['msg'] = 'error';
                            if ($oxid != "") {
                                $oUser = oxNew(\OxidEsales\Eshop\Application\Model\User::class);
                                $oUser->load($oxid);

                                $title = $_REQUEST['title'];
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
                                $oUser->oxuser__oxfname->rawValue = $_REQUEST['firstname'];
                                $oUser->oxuser__oxlname->rawValue = $_REQUEST['lastname'];
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
