<?php
/**
 * @copyright   (c) gn2
 * @link        https://www.gn2.de/
 */

namespace Gn2\NewsletterConnect\Core\Api\Export;

use Exception;
use Gn2\NewsletterConnect\Core\Api\Help\Utilities;
use Gn2\NewsletterConnect\Core\Api\WebService\WebService;
use OxidEsales\Eshop\Application\Controller\FrontendController;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Request;
use OxidEsales\Eshop\Core\DatabaseProvider;

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
            }
            else {
                $this->_sWhereClause .= ' OR OXDBOPTIN = 2';
            }
        }

        if ($bInActiveSubscribers) {
            if ($this->_sWhereClause === null) {
                $this->_sWhereClause = " WHERE OXDBOPTIN = 0 and OXUNSUBSCRIBED != '0000-00-00 00:00:00'";
            }
            else {
                $this->_sWhereClause .= " OR ( OXDBOPTIN = 0 and OXUNSUBSCRIBED != '0000-00-00 00:00:00' )";
            }
        }

        if ($dExportNotSubscribed) {
            if ($this->_sWhereClause === null) {
                $this->_sWhereClause = " WHERE OXDBOPTIN = 0 and OXUNSUBSCRIBED = '0000-00-00 00:00:00'";
            }
            else {
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
            $oWebService = oxNew(WebService::class);

            if (is_object($oWebService)) {
                $this->_webService = $oWebService;
            }
        } catch (Exception $e) {
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
            return array("REPORT" => Utilities::FAULTY, "LINK" => 'Webservice-Object can not be found.');
        }

        if ($this->_sTransferMethod == "packetCSV") {
            return self::_packetCSV();
        }
        
        // abonnenten aus datenbank holen
        $oDb = DatabaseProvider::getDb();
        $resultSet = $oDb->select($this->_getSubscribersQuery());
        
        $subscriberList = [];

        if ($resultSet != false && $resultSet->count() > 0) {
            while (!$resultSet->EOF) {
                // $row = [
                //      0 => OXEMAIL,
                //      1 => OXSAL,
                //      2 => OXFNAME,
                //      3 => OXLNAME,
                //      4 => OXUNSUBSCRIBED,
                //      5 => OXBIRTHDATE,
                //      6 => OXDBOPTIN
                // ];
                $row = $resultSet->getFields();
              
                // deutsche Anrede
                $row[1] = (strtolower($row[1]) == 'mr') ? 'Herr' : 'Frau';
                if (strtolower($row[1]) == 'mr') {
                    $row[1] = 'Herr';
                }
                elseif (strtolower($row[1]) == 'mrs' || strtolower($row[1]) == 'miss') {
                    $row[1] = 'Frau';
                }

                // ggfs status
                if ($this->_blExportStatus) {
                    // siehe source/modules/gn2/newsletterconnect/Application/Model/User.php generateRecipientObject()
                    if ($row[6] == 0 && $row[4] != '0000-00-00 00:00:00') {
                        $row[6] = 3; // abgemeldet
                    }
                    // siehe source/modules/gn2/newsletterconnect/Core/Api/Mailing/Recipient.php getOxidNewsletterStatus()
                    if ($row[6] == 1) {
                        $row[6] = 'OXDBOPTIN';
                    }
                    elseif ($row[6] == 2) {
                        $row[6] = 'OXSUBSCRIBED';
                    }
                    elseif ($row[6] == 3) {
                        $row[6] = 'OXUNSUBSCRIBED';
                    }
                    else {
                        $row[6] = 'OXNOTSUBSCRIBED';
                    }
                }

                $subscriberList[] = $row;
                $resultSet->fetchRow();
            }
        }

        $TotalSubscribers = count($subscriberList);

        if (!$TotalSubscribers) {
            return array("REPORT" => Utilities::NODATA, "LINK" => null);
        }

        // set recipients
        $this->_setRecipients($subscriberList);

        if ($this->_sTransferMethod == "csv") {
            return self::_csv();
        }
        else {
            return self::_packet();
        }
    }

    /**
     * Export CSV Datei einlesen, Anzahl X nach mailingworks übertragen
     * und den Rest wieder in die Datei speichern
     * @return array Report
     */
    private function _packetCSV()
    {
        // CSV Datei holen
        $oRequest = Registry::get(Request::class);
        $fileName = $oRequest->getRequestEscapedParameter('filename');
        $firstRun = $oRequest->getRequestEscapedParameter('firstRun');

        // bei der Importart "Ersetzen" nur beim ersten Transfer das replace mitgeben, danach update_add (sonst würde immer alles gelöscht werden)
        if ($firstRun == '0' && $this->_sImportArt == 'replace') {
            $this->_sImportArt = 'update_add';
        }

        if (!$fileName) {
            return [
                "REPORT" => Utilities::FAULTY,
                "LINK" => "filename missing",
            ];
        }
        $filePath = Utilities::getExportDir(true).$fileName;
        if (!is_file($filePath)) {
            return [
                "REPORT" => Utilities::FAULTY,
                "LINK" => "file missing",
            ];
        }

        // Anzahl der zu exportierenden Datensätze
        $chunksize = 500;
        // CSV Datei in einen Array einlesen
        $file = file($filePath);
        $resumeExport = true;
        // erste Zeile der CSV Datei (Spaltennamen)
        $header = array_shift($file);
        // es gibt mehr Datensätze als exportiert werden sollen, die restlichen wieder in die CSV Datei schreiben
        if (count($file) > $chunksize) {
            // zu exportierende Datensätze
            $export = array_slice($file, 0, $chunksize);
            // restliche Datensätze
            $newFile = array_slice($file, $chunksize);
            // Spaltennamen wieder einfügen, CSV Datei leeren und restliche Datensätze speichern
            array_unshift($newFile, $header);
            $f = @fopen($filePath, "r+");
            if ($f !== false) {
                ftruncate($f, 0);
                fclose($f);
            }
            file_put_contents($filePath, implode($newFile));
        }
        // keine weiteren Datensätze nach diesem Export, Datei löschen
        else {
            $export = $file;
            unlink($filePath);
            $resumeExport = false;
        }

        // Datensätze nach mailingwork übertragen
        if (count($export)) {
            // über die Spaltennamen der CSV Datei die Spaltenschlüssel für die Übertragung holen
            // implementiert Spalten Stand 22.3.2033:
            //  1 => E-Mail
            //  2 => Anrede
            //  3 => Vorname
            //  4 => Nachname
            //  9 => Sprache
            // 10 => Anmeldestatus
            $columnKeys = [];
            foreach (str_getcsv($header, ';') as $columnName) {
                $columnKeys[] = $this->_webService->getFieldId($columnName);
            }

            $recipients = [];
            foreach ($export as $exportIdx => $exportLine) {
                $subscriberData = str_getcsv($exportLine, ';');
                foreach ($subscriberData as $k => $v) {
                    $recipients[$exportIdx][$columnKeys[$k]] = $v;
                }
            }

            $aImportResponse = $this->_webService->importRecipients($this->_listId, $recipients, $this->_sImportArt);
            if ($aImportResponse['error'] !== 0) {
                return [
                    "REPORT" => Utilities::FAULTY,
                    "LINK" => $aImportResponse['message'],
                ];
            }
            else {
                if ($resumeExport) {
                    return [
                        "REPORT" => Utilities::SUCCESS,
                        "LINK" => count($export)." subscribers transferred. Export will continue in 5 seconds, don't close or change tab",
                        "RESUME" => $fileName,
                    ];
                }
            }
        }

        return [
            "REPORT" => Utilities::SUCCESS,
            "LINK" => "All subscribers transferred. Check Mailingwork for the final result.".date('H:i'),
        ];

    }

    /**
     * Get the sql query.
     * This can be used to build the CSV array or the oxlist
     * @return string
     */
    private function _getSubscribersQuery()
    {
        $sWhere = $this->_getWhereClause(); //(isset($this->_sWhereClause))? $this->_sWhereClause : '';

        return 'SELECT '.$this->_getSelectClause().'
                                  FROM oxnewssubscribed as t_n 
                                  LEFT JOIN oxuser as t_u 
                                  ON  t_n.OXUSERID = t_u.OXID '.$sWhere;
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

        $sShopIDClause = $table1.".OXSHOPID = '".$oConfig->getShopId()."'";

        if ($this->_sWhereClause === null) {
            //if subscriber type is not chosen, export only the active subscribers
            return " WHERE $sShopIDClause AND $table1.OXDBOPTIN = 1";
        }

        // Bugfix für Oxid Enterprise Subshops
        $sWhereClause = strstr(trim($this->_sWhereClause), ' ');
        $sWhereClause = trim($sWhereClause, " ;,");

        return " WHERE ($sWhereClause) AND $sShopIDClause";

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
     * Get the recipeints for export
     * @param $oUserList object oxuser list
     */
    private function _setRecipients($subscriberList)
    {
        //unset($this->_aRecipients);
        //unset($this->_aCsvHeader);
        $this->_aRecipients = [];
        $this->_aCsvHeader = [];
        // $subscriber = [
        //      0 => OXEMAIL,
        //      1 => OXSAL,
        //      2 => OXFNAME,
        //      3 => OXLNAME,
        //      4 => OXUNSUBSCRIBED,
        //      5 => OXBIRTHDATE,
        //      6 => OXDBOPTIN
        // ];
        $oUBase = oxNew(FrontendController::class);
        $langISO = $oUBase->getActiveLangAbbr();
        foreach ($subscriberList as $subscriber) {
            $fields = [];
            $fields[$this->_webService->getFieldId('E-Mail')] = $subscriber[0];
            $fields[$this->_webService->getFieldId('Anrede')] = $subscriber[1];
            $fields[$this->_webService->getFieldId('Vorname')] = $subscriber[2];
            $fields[$this->_webService->getFieldId('Nachname')] = $subscriber[3];
            if ($this->_webService->getFieldId('Geburtstag')) {
                $fields[$this->_webService->getFieldId('Geburtstag')] = $subscriber[5];
            }
            if ($this->_webService->getFieldId('Sprache')) {
                $fields[$this->_webService->getFieldId('Sprache')] = $langISO;
            }
            if ($this->_blExportStatus) {
                if ($this->_webService->getFieldId('Anmeldestatus')) {
                    $fields[$this->_webService->getFieldId('Anmeldestatus')] = $subscriber[6];
                }
            }
            $this->_aRecipients[] = $fields;
        }
        if ($subscriberList) {
            $this->_aCsvHeader[] = 'E-Mail';
            $this->_aCsvHeader[] = 'Anrede';
            $this->_aCsvHeader[] = 'Vorname';
            $this->_aCsvHeader[] = 'Nachname';
            if ($this->_webService->getFieldId('Geburtstag')) {
                $this->_aCsvHeader[] = 'Geburtstag';
            }
            if ($this->_webService->getFieldId('Sprache')) {
                $this->_aCsvHeader[] = 'Sprache';
            }
            if ($this->_blExportStatus) {
                if ($this->_webService->getFieldId('Anmeldestatus')) {
                    $this->_aCsvHeader[] = 'Anmeldestatus';
                }
            }
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
        }
        else {
            if ($iStatus === Utilities::NODATA) {
                return array("REPORT" => Utilities::FAULTY, "LINK" => 'NO DATA FOUND');
            }
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

            if ($f != false) {
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
            }
            else {
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
            header('Content-Length: '.filesize($this->_sFile));
            header('Content-Disposition: attachment; filename="'.basename($this->_sFile).'"');

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
     * legt eine csv datei mit den zu exportierenden daten an, gibt den dateinamen zurück
     * hat frueher die daten paketweise direkt nach mailingwork exportiert, daher der name
     * @return array Report
     */
    private function _packet()
    {

        $dTotalSubscribers = count($this->_aRecipients);
        $iStatus = $this->_generateCsv();
        if ($iStatus === Utilities::SUCCESS) {
            return [
                "REPORT" => Utilities::SUCCESS,
                "LINK" => "$dTotalSubscribers subscribers prepared for export. Export will start in 5 seconds.".date('H:i'),
                "RESUME" => basename($this->_sFile),
                "FIRSTRUN" => '1'
            ];
        }
        if ($iStatus === Utilities::NODATA) {
            return array("REPORT" => Utilities::FAULTY, "LINK" => 'NO DATA FOUND');
        }

        return array("REPORT" => Utilities::FAULTY, "LINK" => 'NO FILE RESOURCE FOUND');

    }

}