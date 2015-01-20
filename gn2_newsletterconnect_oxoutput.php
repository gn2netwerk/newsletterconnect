<?php
require_once(dirname(__FILE__).'/gn2_newsletterconnect.php');

class gn2_newsletterconnect_oxoutput extends gn2_newsletterconnect_oxoutput_parent
{
    public function __construct()
    {
        $voucherApi = $_REQUEST['voucher_api'];
        if ($voucherApi == 1) {
            $config = oxRegistry::getConfig();
            $savedSettings = (array)$config->getShopConfVar('config', null, 'module:gn2_newsletterconnect');

            if (isset($savedSettings['api_voucherips'])) {
                $voucherIps = explode("\n", $savedSettings['api_voucherips']);
                foreach ($voucherIps as $k=>$v) {
                    $voucherIps[$k] = trim($v);
                }
                if (in_array($_SERVER['REMOTE_ADDR'], $voucherIps) && isset($savedSettings['voucher_series']) && $savedSettings['voucher_series'] != "") {
                    $mode = $_REQUEST['mode'];
                    switch ($mode) {
                        case "getVoucher":

                            $voucherSeries = $savedSettings['voucher_series'];
                            if ($voucherSeries != "") {
                                $oDb = oxDb::getDb();
                                $sql = 'SELECT OXID, OXVOUCHERNR FROM oxvouchers WHERE OXVOUCHERSERIEID = "'.mysql_real_escape_string($voucherSeries).'"';
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
                    }
                }
            }
        }
        parent::__construct();
    }
}
