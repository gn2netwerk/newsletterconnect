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

namespace Gn2\NewsletterConnect\Core\Api\Help;

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
    public static function getApiConfig()
    {
        $oConfig = Registry::getConfig();
        return (array) $oConfig->getShopConfVar('config', $oConfig->getShopId(), 'module:gn2_newsletterconnect');
    }

    /**
     * singleton for the export directory
     * @return null|string
     */
    public static function getExportDir($bAbsolute = false)
    {
        $sRelativePath = "export/gn2_newsletterconnect/";

        $oConfig = Registry::getConfig();
        $sAbsolutePath = rtrim($oConfig->getConfigParam('sShopDir'), " /") . "/" . trim($sRelativePath, " /") . "/";

        // create export folder if necessary
        if (!file_exists($sAbsolutePath)) {
            mkdir($sAbsolutePath);
        }

        if ($bAbsolute) {
            return $sAbsolutePath;
        }

        return $sRelativePath;
    }

    /**
     * check if the export and module export directory exists.
     * @return bool
     */
    public static function checkExportDir()
    {
        $sExportDir = self::getExportDir(true);
        return is_dir($sExportDir) && file_exists($sExportDir);
    }

    /**
     * creates the file path
     * @param bool $bAbsolute
     * @param string $fileSuffix optional suffix to the filename, default is an empty string
     * @return string
     */
    public static function generateExportFilePath($bAbsolute = false, $fileSuffix = '')
    {
        $sExportDirPath = self::getExportDir($bAbsolute);
        $fileName = self::generateFileName($fileSuffix);

        return $sExportDirPath . $fileName;
    }

    /**
     * creates|returns the CSV filename
     * @param string $fileSuffix optional suffix to the filename, default is an empty string
     * @return string
     */
    public static function generateFileName($fileSuffix = '')
    {
        //the filename should be created each time it is called
        list($usec, $sec) = explode(" ", microtime());
        $sTimestamp = intval((float)$usec + (float)$sec);
        $sFilename = $fileSuffix . $sTimestamp . '.csv';
        return $sFilename;
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
    public static function Utf8Encode($str)
    {
        if (mb_detect_encoding($str, 'UTF-8', true) === false) {
            $str = utf8_encode($str);
        }

        return $str;
    }

}