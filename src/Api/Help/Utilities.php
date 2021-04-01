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

namespace Gn2\NewsletterConnect\Api\Help;

use OxidEsales\Eshop\Core\Registry;

/**
 * Utilities class.
 * Holds some general functions
 */
class Utilities
{

    /**
     * export directory path
     * @var null
     */
    private static $_exportDirPath = null;

    /**
     * oxid export directory - 'export/'
     * @var string
     */
    private static $_sOxExportDir = 'export/';

    /**
     * Report constants
     */
    const
        SUCCESS = 1
    , NODATA = 2
    , NOFILERESOURCE = 3
    , FAULTY = 4
    , EXPORTDIRMISSING = 5;

    /**
     * @return array
     */
    public static function getConfig()
    {
        $oConfig = Registry::getConfig();

        // Kristian Berger: Erweiterung der Config Einstellungen um akt. Shop Id (für Multishops notwendig)
        $sShopId = $oConfig->getShopId();

        return (array) $oConfig->getShopConfVar('config_' . $sShopId, null, 'module:gn2_newsletterconnect');
    }

    /**
     * check if the export and module export directory exists.
     * it creates the module export directory if it does not exist.
     * The module export directory is a subdirectory to the oxid export directory
     * @param $sModuleExportDirectoryName
     * @return array - status = true if all the operations were successful,
     *                  ModuleExportDirectoryName = Name of the Module export directory,
     *                  ModuleExportDirectoryPath = the path to the export directory or null if not ascertained
     */
    public static function checkExportDir($sModuleExportDirectoryName = "export")
    {
        $ret = array('status' => false,
            'ModuleExportDirectoryName' => $sModuleExportDirectoryName,
            'ModuleExportDirectoryPath' => null);

        //if export directory in base shop does not exist, create one
        $oConfig = Registry::getConfig();
        $baseDirPlusExport = rtrim($oConfig->getConfigParam('sShopDir'), " /") . "/" . ltrim($sModuleExportDirectoryName, " /");

        if (!is_dir($baseDirPlusExport)) {
            return $ret;
        }

        //if module export directory does not exist, create one
        $sExportDirPath = $baseDirPlusExport . $sModuleExportDirectoryName;
        if (!is_dir($sExportDirPath)) {
            if (!mkdir($sExportDirPath)) {
                return $ret;
            }
        }

        //set variable
        $ret['status'] = true;
        $ret['ModuleExportDirectoryName'] = $sModuleExportDirectoryName;
        $ret['ModuleExportDirectoryPath'] = $sExportDirPath;

        return $ret;
    }


    /**
     * gets the export path for the calling module.
     * this calls the function -@see checkExportDir- to create the directory if it does not exist.
     * @param $sModuleExportDirectoryName the export directory name
     * @return string the path of the export directory
     */
    public static function _getModuleExportPath($sModuleExportDirectoryName)
    {
        $moduleExportParameters = self::checkExportDir($sModuleExportDirectoryName);
        return $moduleExportParameters['ModuleExportDirectoryPath'];
    }


    /**
     * creates the file path
     * @param $sModuleExportDirectory the export directory
     * @param string $fileSuffix optional suffix to the filename, default is an empty string
     * @return string
     */
    public static function getFilePath($sModuleExportDirectory, $fileSuffix = ''): string
    {
        $sExportDirPath = self::_getModuleExportPath($sModuleExportDirectory);
        $fileName = self::getFileName($fileSuffix);
        $filePath = $sExportDirPath . $fileName;
        return $filePath;
    }


    /**
     * @return string
     */
    public static function getExportFilePath()
    {
        $exportPath = trim(self::getExportDirPath(), '/ ');
        $fileName = trim(self::getFileName(), '/ ');

        $filePath = '/' . $exportPath . '/' . $fileName;

        return $filePath;
    }


    /**
     * creates|returns the CSV filename
     * @param string $fileSuffix optional suffix to the filename, default is an empty string
     * @return string
     */
    public static function getFileName($fileSuffix = '')
    {
        //the filename should be created each time it is called
        list($usec, $sec) = explode(" ", microtime());
        $sTimestamp = intval((float)$usec + (float)$sec);
        $sFilename = $fileSuffix . $sTimestamp . '.csv';
        return $sFilename;
    }


    /**
     * sends the csv to the client
     * @param $file the file path
     */
    public static function sendToClient($file)
    {
        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . basename($file) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            exit;
        }
    }


    /**
     * creates a download link to the given file
     * @param $sFile the file - filename with path
     * @param $sModuleExportDirectoryName the module export directory path with backslash ending
     * @return string a href link to the file for download
     */
    public static function createLink($sFile, $sModuleExportDirectoryName)
    {
        $oConfig = Registry::getConfig();
        $sShopURL = rtrim($oConfig->getConfigParam('sShopURL'), ' /');

        return '<a href="' . $sShopURL . '/' . self::$_sOxExportDir . $sModuleExportDirectoryName . basename($sFile) . '">' . basename($sFile) . ' </a>';
    }


    /**
     * Translates the given report
     * @param $dReport Report code
     * @return string styled report statement
     */
    public static function translateReport($dReport)
    {
        $ret = 'UNKNOWN STATUS';
        switch ($dReport) {
            case Utilities::SUCCESS:
                $ret = '<span style="background-color: forestgreen;"> Successful </span>';
                break;
            case Utilities::FAULTY:
                $ret = '<span style="background-color: red;"> Error occurred (Connection failed, Object not found). check next hint. </span>';
                break;
            case Utilities::NODATA:
                $ret = '<span style="background-color: orange;"> No Data found </span>';
                break;
            case Utilities::NOFILERESOURCE:
                $ret = '<span style="background-color: lightgreen;"> File system not found </span>';
                break;
        }

        return $ret;
    }


    /**
     * converts given string to utf-8 if necessary
     * @param $str string str being converted
     * @return string
     */
    public static function MailingWorkUtf8Encode($str)
    {
        if (mb_detect_encoding($str, 'UTF-8', true) === false) {
            $str = utf8_encode($str);
        }

        return $str;
    }


    /**
     * singleton for the export directory
     * @return null|string
     */
    public static function getExportDirPath()
    {
        if (self::$_exportDirPath === null) {
            $oConfig = Registry::getConfig();
            self::$_exportDirPath = rtrim($oConfig->getConfigParam('sShopDir'), "/") . "/export/gn2_newsletterconnect/";
        }

        //create Export folder
        if (!file_exists(self::$_exportDirPath)) {
            mkdir(self::$_exportDirPath);
        }
        return self::$_exportDirPath;

    }

}