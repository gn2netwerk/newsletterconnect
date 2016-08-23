<?php
require_once(dirname(__FILE__).'/gn2_newsletterconnect.php');

class gn2_newsletterconnect_config extends oxAdminView
{
    protected $_sThisTemplate = 'gn2_newsletterconnect_config.tpl';

    /**
     * translated export status report, GN2_Utilities
     * @var string
     */
    private $_sExportStatus = null;

    /**
     * yet another report, like a download link or error details
     * @var string
     */
    private $_sExportReportData = null;


    public function save()
    {
        $config = $this->getConfig();
        $posted = $_REQUEST['config']; // we're not using oxConfig::getRequestParameter here. We know what we're doing.
        // Kristian Berger: Erweiterung der Config Einstellungen um akt. Shop Id (für Multishops notwendig)
        $sShopId = $this->getConfig()->getShopId();
        $config->saveShopConfVar('aarr', 'config_' . $sShopId, $posted, null, 'module:gn2_newsletterconnect');
        gn2_newsletterconnect::$config = gn2_newsletterconnect::getEnvironment()->getModuleConfig();
    }


    /**
     * invoked to export the oxid newsletter subscribers as csv.
     * This csv file can be imported in the mailing-works
     */
    public function exportSubscribers()
    {
        //check configuration
        $bExportActiveSubscriptions = true;
        $bExportUnconfirmedSubscriptions = false;
        $bExportInActiveSubscriptions = false;
        $dExportNotSubscribed = false;
        $sTransferMethod = 'packet';
        //export confirmed subscriptions
        if(! GN2_NewsletterConnect::getOXParameter('activeSubscription')){
            $bExportActiveSubscriptions = false;
        }

        //export unconfirmed subscriptions
        if(GN2_NewsletterConnect::getOXParameter('unconfirmedSubscription')){
            $bExportUnconfirmedSubscriptions = true;
        }

        //export inactive subscriptions
        if(GN2_NewsletterConnect::getOXParameter('inactiveSubscription')){
            $bExportInActiveSubscriptions = true;
        }

        //export  unsubscribed user
        if(GN2_NewsletterConnect::getOXParameter('noSubscription')){
            $dExportNotSubscribed = true;
        }

        //mailing works signup setup
        $sMosListId = GN2_NewsletterConnect::getOXParameter('export_listId');

        //get import art
        $dImportArt = GN2_NewsletterConnect::getOXParameter('importMode');


        //get the export status flag
        $blExportStatus = false;
        if(GN2_NewsletterConnect::getOXParameter('export_status')){
            $blExportStatus = true;
        }

        if(GN2_NewsletterConnect::getOXParameter('transfermethod')){
            $sTransferMethod = GN2_NewsletterConnect::getOXParameter('transfermethod');
        }
        
        //call exporter
        $oExporter = new GN2_NewsletterConnect_Export($bExportActiveSubscriptions,
                                                        $bExportInActiveSubscriptions,
                                                        $bExportUnconfirmedSubscriptions,
                                                        trim ($sMosListId),
                                                        trim ($dImportArt),
                                                        $blExportStatus,
                                                        $dExportNotSubscribed
                                                    );
        $oExporter->setTransferMethod($sTransferMethod);
        $aReport = $oExporter->transferData();
        if(is_array($aReport)){
            $this->_sExportStatus = GN2_Utilities::translateReport( $aReport['REPORT']); //($aReport);
            $this->_sExportReportData = $aReport['LINK'];
        }
    }


    public function render()
    {
        $qsql = 'SELECT OXID,OXSERIENR FROM oxvoucherseries';
        $rows = oxDb::getDb()->Execute($qsql);
        $voucherSeries = array(
            array('', '--')
        );
        while (!$rows->EOF) {
            $voucherSeries[] = $rows->fields;
            $rows->moveNext();
        }
        $this->_aViewData['voucherSeries'] = $voucherSeries;
        $this->_aViewData['config'] = gn2_newsletterconnect::$config;

        //export subscribers
        $this->_aViewData['gn2_ExportStatus'] = $this->_sExportStatus;
        $this->_aViewData['gn2_ExportReportData'] = $this->_sExportReportData;
        $this->_aViewData['gn2_ExportDir']  = GN2_Utilities::checkExportDir(EXPORTDIR)['status'];
        $this->_aViewData['totalSubscribers'] = $this->_CountSubscribers();
        $this->_aViewData['activeSubscribers'] = $this->_CountSubscribers('WHERE OXDBOPTIN = 1');
        $this->_aViewData['unconfirmedSubscribers'] = $this->_CountSubscribers('WHERE OXDBOPTIN = 2');
        $this->_aViewData['inactiveSubscribers'] = $this->_CountSubscribers("WHERE OXDBOPTIN = 0 and OXUNSUBSCRIBED != '0000-00-00 00:00:00' ");
        $this->_aViewData['notSubscribed'] = $this->_CountSubscribers("WHERE OXDBOPTIN = 0 and OXUNSUBSCRIBED = '0000-00-00 00:00:00' ");
        return parent::render();
    }

    
    /**
     * Gets the number of all or active or inactive subscribers.
     * The type of subscribers delivered is determined using the optional where-clause
     * @param string $sWhereClause optional where-clause
     * @return int number of all or active or inactive subscribers
     */
    private function _CountSubscribers($sWhereClause = '')
    {
        //set where clause
        $sWhere = " OXSHOPID = '". GN2_Utilities::getShopId() . "'";
        if (trim($sWhereClause) !== ''){
            $sWhere = $sWhereClause . ' AND ' . $sWhere;
        }else{
            $sWhere = ' WHERE ' . $sWhere;
        }

        //send query
        $sCountSubscribersQuery = 'SELECT count(*) FROM oxnewssubscribed ' . $sWhere;
        $rows = oxDb::getDb()->Execute($sCountSubscribersQuery);
        return $rows->fields[0];
    }

}
?>