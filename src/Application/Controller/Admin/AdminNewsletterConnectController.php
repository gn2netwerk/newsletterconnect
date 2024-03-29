<?php
/**
 * @copyright   (c) gn2
 * @link        https://www.gn2.de/
 */

namespace Gn2\NewsletterConnect\Application\Controller\Admin;

use \OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController;
use \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException;
use \OxidEsales\Eshop\Core\Exception\DatabaseErrorException;
use \OxidEsales\Eshop\Core\DatabaseProvider;

use \Gn2\NewsletterConnect\Core\Api\Export\Export;
use \Gn2\NewsletterConnect\Core\Api\Help\Utilities;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Request;

class AdminNewsletterConnectController extends AdminDetailsController
{

    /**
     * @var string
     */
    protected $_sThisTemplate = 'admin_newsletterconnect.tpl';

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
     * resume export
     * @var string
     */
    private $_sResumeExport = null;

    /**
     * list id
     * @var string
     */
    private $_sExportListId = '';

    /**
     * import mode
     * @var string
     */
    private $_sImportMode = '';

    /**
     * first run
     * @var bool
     */
    private $_sFirstRun = '';

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

        $this->_aViewData['config'] = Utilities::getSettings();

        //export subscribers
        $this->_aViewData['gn2_ExportStatus'] = $this->_sExportStatus;
        $this->_aViewData['gn2_ExportReportData'] = $this->_sExportReportData;
        $this->_aViewData['gn2_ResumeExport'] = $this->_sResumeExport;
        $this->_aViewData['gn2_ListId'] = $this->_sExportListId;
        $this->_aViewData['gn2_ImportMode'] = $this->_sImportMode;
        $this->_aViewData['gn2_FirstRun'] = $this->_sFirstRun;

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
        $aParam = Registry::get(Request::class)->getRequestEscapedParameter('config');
        $oConfig->saveShopConfVar('aarr', 'config', $aParam, $oConfig->getShopId(), 'module:gn2_newsletterconnect');
    }


    /**
     * invoked to export the oxid newsletter subscribers as csv.
     * This csv file can be imported in the mailing-works
     */
    public function exportSubscribers()
    {
        $oRequest = Registry::get(Request::class);

        // collect variables
        $bExportActiveSubscriptions = ($oRequest->getRequestEscapedParameter('activeSubscription')) ? true : false;
        $bExportUnconfirmedSubscriptions = ($oRequest->getRequestEscapedParameter('unconfirmedSubscription')) ? true : false;
        $bExportInActiveSubscriptions = ($oRequest->getRequestEscapedParameter('inactiveSubscription')) ? true : false;
        $dExportNotSubscribed = ($oRequest->getRequestEscapedParameter('noSubscription')) ? true : false;
        $sTransferMethod = $oRequest->getRequestEscapedParameter('transfermethod');

        $sMosListId = $oRequest->getRequestEscapedParameter('export_listId');
        $dImportArt = $oRequest->getRequestEscapedParameter('importMode');

        $blExportStatus = ($oRequest->getRequestEscapedParameter('export_status')) ? true : false;

        // call exporter
        $oExporter = new Export($bExportActiveSubscriptions,
            $bExportInActiveSubscriptions,
            $bExportUnconfirmedSubscriptions,
            trim($sMosListId),
            trim($dImportArt),
            $blExportStatus,
            $dExportNotSubscribed,
            $sTransferMethod
        );

        $aReport = $oExporter->transferData();

        if (is_array($aReport)) {
            $this->_sExportStatus = Utilities::translateReport($aReport['REPORT']);
            $this->_sExportReportData = $aReport['LINK'];
            $this->_sExportListId = $sMosListId;
            $this->_sResumeExport = $aReport['RESUME'] ?? '';
            $this->_sFirstRun = $aReport['FIRSTRUN'] ?? '0';
            $this->_sImportMode = trim($dImportArt) ?? '';
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