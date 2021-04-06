<?php
/**
 * Gn2_NewsletterConnect
 * @category Gn2_NewsletterConnect
 * @package  Gn2_NewsletterConnect
 * @author   gn2 netwerk <kontakt@gn2.de>
 * @license  Gn2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version  GIT: <git_id>
 * @link     http://www.gn2-netwerk.de/
 */

namespace Gn2\NewsletterConnect\Core\Api\Export;

use \Gn2\NewsletterConnect\Core\Api\Help\Utilities;
use \Gn2\NewsletterConnect\Core\Api\WebService\WebService;
use OxidEsales\Eshop\Application\Model\UserList;
use \OxidEsales\Eshop\Core\Registry;

/**
 * Gn2_NewsletterConnect Export-Class
 */
class Export
{

    /**
     * where clause
     * @var null|string
     */
    private $_sWhereClause = null;

    /**
     * Mailingwork service object
     * @var WebService
     */
    private $_webService = null;

    /**
     * list Id
     * @var null
     */
    private $_listId;

    /**
     * Import art / mode
     * @var
     */
    private $_sImportArt;

    /**
     * true to export the status of the newsletter
     * @var boolean
     */
    private $_blExportStatus;

    /**
     * builds the list og the import art to be applied once.
     * The key is the import art to be applied once, while the key is the import art
     * to be used for subsequent packets
     * @var array
     */
    private $_aImportArtApplyOnce = array('replace' => 'update_add');

    /**
     * @var array of recipients
     */
    private $_aRecipients;

    /**
     * The method used for the transfer
     * @var string
     */
    private $_sTransferMethod;

    /**
     * current csv filename plus path
     * @var null
     */
    private $_sFile = null;

    /**
     * array of the csv file header
     * @var null
     */
    private $_aCsvHeader = null;


    /**
     * Main constructor.
     * Builds the where-clause using the given parameters
     * @param $bActiveSubscribers true if active subscribers are to be exported
     * @param $bInActiveSubscribers true if inactive subscribers are to be exported
     * @param $bUnconfirmedSubscribers true if unconfirmed subscribers are to be exported
     * @param $dListId
     * @param $sImportArt
     */
    public function __construct($bActiveSubscribers, $bInActiveSubscribers, $bUnconfirmedSubscribers, $dListId, $sImportArt, $blExportStatus, $dExportNotSubscribed, $sTransferMethod)
    {

        if ($bActiveSubscribers) {
            if ($this->_sWhereClause === null) {
                $this->_sWhereClause = ' WHERE OXDBOPTIN = 1';
            }
        }

        if ($bUnconfirmedSubscribers) {
            if ($this->_sWhereClause === null) {
                $this->_sWhereClause = ' WHERE OXDBOPTIN = 2';
            } else {
                $this->_sWhereClause .= ' OR OXDBOPTIN = 2';
            }
        }

        if ($bInActiveSubscribers) {
            if ($this->_sWhereClause === null) {
                $this->_sWhereClause = " WHERE OXDBOPTIN = 0 and OXUNSUBSCRIBED != '0000-00-00 00:00:00'";
            } else {
                $this->_sWhereClause .= " OR ( OXDBOPTIN = 0 and OXUNSUBSCRIBED != '0000-00-00 00:00:00' )";
            }
        }

        if ($dExportNotSubscribed) {
            if ($this->_sWhereClause === null) {
                $this->_sWhereClause = " WHERE OXDBOPTIN = 0 and OXUNSUBSCRIBED = '0000-00-00 00:00:00'";
            } else {
                $this->_sWhereClause .= " OR ( OXDBOPTIN = 0 and OXUNSUBSCRIBED = '0000-00-00 00:00:00' )";
            }
        }

        // set the list ID
        $this->_listId = $dListId;

        // set import art
        $this->_sImportArt = $sImportArt;

        // set export Status flag
        $this->_blExportStatus = $blExportStatus;

        // set transfer method
        $this->_sTransferMethod = $sTransferMethod;

        // set mailing works object
        $this->_setWebService();
    }


    /**
     * sets the mailing works service object
     */
    private function _setWebService()
    {
        try {
            $oWebService = oxNew( WebService::class );

            if (is_object($oWebService)) {
                $this->_webService = $oWebService;
            }
        } catch (\Exception $e) {
            /* Do nothing */
        }
    }


    /**
     * transfer subscribers initializer
     * @return array report
     */
    public function transferData()
    {
        // try get webservice object
        if ($this->_webService === null) {
            return array("REPORT" => Utilities::FAULTY, "LINK" => ' Webservice-Object can not be found.');
        }

        // get user list
        $oUserList = oxNew(UserList::class);
        $oUserList->selectString($this->_getSubscribersQuery());

        $TotalSubscribers = $oUserList->count();

        if (!$TotalSubscribers) {
            return array("REPORT" => Utilities::NODATA, "LINK" => null);
        }

        // set recipients
        $this->_setRecipients($oUserList);

        if ($this->_sTransferMethod == "csv") {
            return self::_csv();
        } else {
            return self::_packet();
        }
    }


    /**
     * Transfers the data in packets.
     * We use this to place import tasks in Mailingwork
     * @return array Report
     */
    private function _packet()
    {
        $dTotalSubscribers = count($this->_aRecipients);

        //divide recipient
        $aImportResponseContainer = array();
        $aRecipientParts = array_chunk($this->_aRecipients, 150);
        $dParts = count($aRecipientParts);
        $blImportArtAppliedOnce = false;

        foreach ($aRecipientParts as $key => $value) {
            $this->replaceImportArt($blImportArtAppliedOnce);
            $aImportResponseContainer[] = $this->_webService->importRecipients($this->_listId, $value, $this->_sImportArt);
            $blImportArtAppliedOnce = true;
            //sleep(20);
        }

        //calculate error
        $errorOccurred = false;
        $errorMessages = '';
        $chunkIndex = 1;
        foreach ($aImportResponseContainer as $aImportResponse) {
            if ($aImportResponse['error'] !== 0) {
                $errorOccurred = true;
                $errorMessages .= '<p>' . $chunkIndex . '. Paket: ' . $aImportResponse['message'] . '</p>';
            }
            $chunkIndex++;
        }

        if (!$errorOccurred) {
            return array("REPORT" => Utilities::SUCCESS, "LINK" => "$dTotalSubscribers subscriber(s) in $dParts parts transferred. Check Mailingwork for the final result.");
        } else {
            return array("REPORT" => Utilities::FAULTY, "LINK" => $errorMessages);
        }
    }


    /**
     * CSV Transfer methods, the user becomes a csv file that can be imported in mailingwork
     * @return array report
     */
    private function _csv()
    {
        //generate CSV
        $iStatus = $this->_generateCsv();

        //create report
        if ($iStatus === Utilities::SUCCESS) {
            //return array("REPORT" => Utilities::SUCCESS, "LINK" => "$dTotalSubscribers subscriber(s) transferred/processed.");
            //send file to client
            $this->_sendFileToClient();
        } else if ($iStatus === Utilities::NODATA) {
            return array("REPORT" => Utilities::FAULTY, "LINK" => 'NO DATA FOUND');
        }

        return array("REPORT" => Utilities::FAULTY, "LINK" => 'NO FILE RESOURCE FOUND');
    }


    /**
     * generates the CSV file
     * @return int
     */
    private function _generateCsv()
    {
        // write csv to export folder
        if (count($this->_aRecipients) > 0) {
            $this->_sFile = Utilities::generateExportFilePath(true, "gn2_newsletterconnect_");

            $f = fopen($this->_sFile, 'w');

            if ($f != FALSE) {
                //add heading
                if (is_array($this->_aCsvHeader)) {
                    fputcsv($f, $this->_aCsvHeader, ";");
                }

                foreach ($this->_aRecipients as $line) {
                    //write line to csv file
                    fputcsv($f, $line, ";");
                }
                fclose($f);
                return Utilities::SUCCESS;
            } else {
                return Utilities::NOFILERESOURCE;
            }

        }

        return Utilities::NODATA;
    }


    /**
     * offers the csv file as download to the user
     */
    private function _sendFileToClient()
    {
        //check if file exist
        if (file_exists($this->_sFile)) {
            header('Content-type:  application/csv');
            header('Content-Length: ' . filesize($this->_sFile));
            header('Content-Disposition: attachment; filename="' . basename($this->_sFile) . '"');

            //to get a clean file, clear old outputs
            ob_clean();
            flush();

            readfile($this->_sFile);

            //delete from server
//            if (connection_aborted()) {
//                unlink($this->_sFile);
//            }

            unlink($this->_sFile);
            exit;
        }
    }


    /**
     * Replaces the initial import art if necessary
     * @param $blImportArtAppliedOnce boolean true if the initial import art has been applied once
     */
    private function replaceImportArt($blImportArtAppliedOnce)
    {
        //import art like replace should only be applied once when we are sending the recipient in packets
        if ($blImportArtAppliedOnce) {
            if (isset($this->_aImportArtApplyOnce[$this->_sImportArt])) {
                $this->_sImportArt = $this->_aImportArtApplyOnce[$this->_sImportArt];
            }
        }
    }


    /**
     * Get the recipeints for export
     * @param $oUserList object oxuser list
     */
    private function _setRecipients($oUserList)
    {
        unset($this->_aRecipients);
        unset($this->_aCsvHeader);
        $this->_aRecipients = array();
        $this->_aCsvHeader = array();

        foreach ($oUserList as $oUser) {
            $this->_aRecipients[] = $this->_webService->getFields($oUser->generateRecipientObject($oUser->oxuser__oxemail->rawValue), $this->_blExportStatus);
        }

        //use one user to get the header
        $oUser = $oUserList[0];
        $this->_aCsvHeader = $this->_webService->getCSVHeader($oUser->generateRecipientObject($oUser->oxuser__oxemail->rawValue), $this->_blExportStatus);
    }


    /**
     * Get the sql query.
     * This can be used to build the CSV array or the oxlist
     * @return string
     */
    private function _getSubscribersQuery()
    {
        $sWhere = $this->_getWhereClause(); //(isset($this->_sWhereClause))? $this->_sWhereClause : '';
        $sSubscribersQuery = 'SELECT ' . $this->_getSelectClause() . '
                                  FROM oxnewssubscribed as t_n 
                                  LEFT JOIN oxuser as t_u 
                                  ON  t_n.OXUSERID = t_u.OXID ' . $sWhere;
        return $sSubscribersQuery;
    }


    /**
     * Get the select clause
     * @param string $table1 optional table name (oxnewssubscribed)
     * @param string $table2 optional table name (oxuser)
     * @return string
     */
    private function _getSelectClause($table1 = 't_n', $table2 = 't_u')
    {
        $sStatusColumn = $this->_blExportStatus ? ", $table1.OXDBOPTIN " : '';
        return "$table1.OXEMAIL, $table1.OXSAL, $table1.OXFNAME, $table1.OXLNAME, $table1.OXUNSUBSCRIBED, $table2.OXBIRTHDATE $sStatusColumn";
    }


    /**
     * Gets the where clause
     * @param string $table1 optional table name
     * @param string $table2 optional table name
     * @return string where clause
     */
    private function _getWhereClause($table1 = 't_n')
    {
        $oConfig = Registry::getConfig();

        $sShopIDClause = $table1 . ".OXSHOPID = '" . $oConfig->getShopId() . "'";

        if ($this->_sWhereClause === null) {
            //if subscriber type is not chosen, export only the active subscribers
            return " WHERE $sShopIDClause AND $table1.OXDBOPTIN = 1";
        }

        // Bugfix für Oxid Enterprise Subshops
        $sWhereClause = strstr(trim($this->_sWhereClause), ' ');
        $sWhereClause = trim($sWhereClause, " ;,");
        $sWhereClause = " WHERE ($sWhereClause) AND $sShopIDClause";
        return $sWhereClause;

        //$sWhereClause = "$this->_sWhereClause AND $sShopIDClause";
        //return $sWhereClause;
    }

}