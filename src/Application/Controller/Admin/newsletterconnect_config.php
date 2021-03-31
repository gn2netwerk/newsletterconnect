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

namespace GN2\NewsletterConnect\Application\Controller\Admin;

use \OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController;
use \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException;
use \OxidEsales\Eshop\Core\Exception\DatabaseErrorException;
use \OxidEsales\Eshop\Core\DatabaseProvider;

use \GN2\NewsletterConnect\Api\Export\Export;
use \GN2\NewsletterConnect\Api\Help\Utilities;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Request;

class newsletterconnect_config extends AdminDetailsController
{

    /**
     * @var string
     */
    protected $_sThisTemplate = 'newsletterconnect_config.tpl';

    /**
     * translated export status report
     * @var Utilities string
     */
    private $_sExportStatus = null;

    /**
     * yet another report, like a download link or error details
     * @var string
     */
    private $_sExportReportData = null;


    /**
     * @return string
     * @throws DatabaseConnectionException
     * @throws DatabaseErrorException
     */
    public function render()
    {
        parent::render();

        $oDb = DatabaseProvider::getDb();
        $rows = $oDb->select("SELECT OXID,OXSERIENR FROM oxvoucherseries");

        $voucherSeries = array(
            array('', '--')
        );

        while (!$rows->EOF) {
            $voucherSeries[] = $rows->fields;
            $rows->fetchRow();
        }

        $this->_aViewData['voucherSeries'] = $voucherSeries;

        $this->_aViewData['config'] = Utilities::getConfig();

        //export subscribers
        $this->_aViewData['gn2_ExportStatus'] = $this->_sExportStatus;
        $this->_aViewData['gn2_ExportReportData'] = $this->_sExportReportData;

        $aExportDir = Utilities::checkExportDir();
        $this->_aViewData['gn2_ExportDir'] = $aExportDir['status'];

        $this->_aViewData['totalSubscribers'] = $this->_CountSubscribers();
        $this->_aViewData['activeSubscribers'] = $this->_CountSubscribers('WHERE OXDBOPTIN = 1');
        $this->_aViewData['unconfirmedSubscribers'] = $this->_CountSubscribers('WHERE OXDBOPTIN = 2');
        $this->_aViewData['inactiveSubscribers'] = $this->_CountSubscribers("WHERE OXDBOPTIN = 0 and OXUNSUBSCRIBED != '0000-00-00 00:00:00' ");
        $this->_aViewData['notSubscribed'] = $this->_CountSubscribers("WHERE OXDBOPTIN = 0 and OXUNSUBSCRIBED = '0000-00-00 00:00:00' ");

        return $this->_sThisTemplate;
    }


    /**
     *
     */
    public function save()
    {
        $oConfig = Registry::getConfig();

        // TODO: ...
        $posted = $_REQUEST['config']; // we're not using oxConfig::getRequestParameter here. We know what we're doing.

        // Kristian Berger: Erweiterung der Config Einstellungen um akt. Shop Id (für Multishops notwendig)
        $sShopId = $oConfig->getShopId();
        $oConfig->saveShopConfVar('aarr', 'config_' . $sShopId, $posted, null, 'module:gn2_newsletterconnect');
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

        $oRequest = Registry::get(Request::class);

        //export confirmed subscriptions
        if (!$oRequest->getRequestEscapedParameter('activeSubscription')) {
            $bExportActiveSubscriptions = false;
        }

        //export unconfirmed subscriptions
        if ($oRequest->getRequestEscapedParameter('unconfirmedSubscription')) {
            $bExportUnconfirmedSubscriptions = true;
        }

        //export inactive subscriptions
        if ($oRequest->getRequestEscapedParameter('inactiveSubscription')) {
            $bExportInActiveSubscriptions = true;
        }

        //export  unsubscribed user
        if ($oRequest->getRequestEscapedParameter('noSubscription')) {
            $dExportNotSubscribed = true;
        }

        //mailing works signup setup
        $sMosListId = $oRequest->getRequestEscapedParameter('export_listId');

        //get import art
        $dImportArt = $oRequest->getRequestEscapedParameter('importMode');


        //get the export status flag
        $blExportStatus = false;
        if ($oRequest->getRequestEscapedParameter('export_status')) {
            $blExportStatus = true;
        }

        if ($oRequest->getRequestEscapedParameter('transfermethod')) {
            $sTransferMethod = $oRequest->getRequestEscapedParameter('transfermethod');
        }

        //call exporter
        $oExporter = new Export($bExportActiveSubscriptions,
            $bExportInActiveSubscriptions,
            $bExportUnconfirmedSubscriptions,
            trim($sMosListId),
            trim($dImportArt),
            $blExportStatus,
            $dExportNotSubscribed
        );

        $oExporter->setTransferMethod($sTransferMethod);

        $aReport = $oExporter->transferData();

        if (is_array($aReport)) {
            $this->_sExportStatus = Utilities::translateReport($aReport['REPORT']); //($aReport);
            $this->_sExportReportData = $aReport['LINK'];
        }
    }


    /**
     * Gets the number of all or active or inactive subscribers.
     * The type of subscribers delivered is determined using the optional where-clause
     * @param string $sWhereClause
     * @return mixed
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseErrorException
     */
    private function _CountSubscribers($sWhereClause = '')
    {
        $oConfig = Registry::getConfig();

        //set where clause
        $sWhere = " OXSHOPID = '" . $oConfig->getShopId() . "'";
        if (trim($sWhereClause) !== '') {
            $sWhere = $sWhereClause . ' AND ' . $sWhere;
        } else {
            $sWhere = ' WHERE ' . $sWhere;
        }

        //send query
        $oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $sCountSubscribersQuery = 'SELECT count(*) FROM oxnewssubscribed ' . $sWhere;
        $rows = $oDb->select($sCountSubscribersQuery);
        return $rows->fields[0];
    }

}