<?php
require_once(dirname(__FILE__).'/gn2_newsletterconnect.php');

class gn2_newsletterconnect_oxoutput extends gn2_newsletterconnect_oxoutput_parent
{
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
                foreach ($ips as $k=>$v) {
                    $ips[$k] = trim($v);
                }
                if (in_array($_SERVER['REMOTE_ADDR'], $ips) && isset($savedSettings['voucher_series']) && $savedSettings['voucher_series'] != "") {
                    header('Content-Type:application/javascript');
                    $mode = $_REQUEST['mode'];
                    switch ($mode) {
                        case "getVoucher":
                            $voucherSeries = $savedSettings['voucher_series'];
                            if ($voucherSeries != "") {
                                $oOXDB = oxNew('oxdb');
                                $oDb = oxDb::getDb();
                                $sql = 'SELECT OXID, OXVOUCHERNR FROM oxvouchers WHERE OXVOUCHERSERIEID = "'.$oOXDB->escapeString($voucherSeries).'"';
                                $sql .= ' && OXUSERID="" && OXRESERVED=0 && OXTIMESTAMP<>"1984-01-01 08:00:00"';

                                $voucherRow = $oDb->getRow($sql);
                                if ($voucherRow) {
                                    $oDb->execute('UPDATE oxvouchers SET OXTIMESTAMP="1984-01-01 08:00:00" WHERE OXID="'.$voucherRow[0].'"');
                                    $voucher = $voucherRow[1];
                                }
                            }
                            echo json_encode(array(
                                'voucher' => $voucher
                            ));
                            die();
                        case "updateUser":
                            $oDb = oxDb::getDb();
                            $sql = 'select oxid from oxuser where OXUSERNAME = '.$oDb->quote($_REQUEST['email']).' LIMIT 1';
                            $oxid = $oDb->getOne($sql);
                            $response = array();
                            $response['msg'] = 'error';
                            if ($oxid != "") {
                                $oUser = oxNew('oxuser');
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
