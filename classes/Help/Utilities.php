<?php
/**
 * GN2_Utilities class.
 * Holds some general functions
 *
 * PHP version 5
 *
 * @package  GN2_NewsletterConnect
 * @author   Stanley Agu <st@gn2.de>
 * @license  GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version  GIT: <git_id>
 * @link     http://www.gn2-netwerk.de/
 */
class GN2_Utilities{

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
        SUCCESS   = 1
        ,NODATA = 2
        ,NOFILERESOURCE = 3
        ,FAULTY = 4
        ,EXPORTDIRMISSING = 5
    ;

    /**
     * oxconfig object
     * @var null
     */
    private static $_OxConfig = null;


    /**
     * check if the export and module export directory exists.
     * it creates the module export directory if it does not exist.
     * The module export directory is a subdirectory to the oxid export directory
     * @param $sModuleExportDirectoryName
     * @return array - status = true if all the operations were successful,
     *                  ModuleExportDirectoryName = Name of the Module export directory,
     *                  ModuleExportDirectoryPath = the path to the export directory or null if not ascertained
     */
    public static function checkExportDir($sModuleExportDirectoryName)
    {
        $ret = array('status' => false,
                    'ModuleExportDirectoryName' => $sModuleExportDirectoryName,
                    'ModuleExportDirectoryPath' => null);
        
            //if export directory in base shop does not exist, create one
            $config = self::getOXConfig();
            $baseDirPlusExport = $config->getConfigParam('sShopDir') . self::$_sOxExportDir;
            if(! is_dir($baseDirPlusExport)){
                return $ret;
            }

            //if module export directory does not exist, create one
            $sExportDirPath = $baseDirPlusExport. $sModuleExportDirectoryName;
            if(! is_dir($sExportDirPath)){
                if(!mkdir($sExportDirPath)){
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
    public static function getFilePath($sModuleExportDirectory, $fileSuffix = '')
    {
        $sExportDirPath  = self::_getModuleExportPath($sModuleExportDirectory);
        $fileName = self::getFileName($fileSuffix);
        $filePath = $sExportDirPath . $fileName;
        return $filePath;
    }
    
    public static function getExportFilePath()
    {
        $exportPath = trim(self::getExportDirPath(), '/ ');
        $fileName = trim(self::getFileName(), '/ ');

        $filePath = '/' .$exportPath . '/' .$fileName;

        return $filePath;
    }


    /**
     * creates|returns the CSV filename
     * @param string $fileSuffix optional suffix to the filename, default is an empty string
     * @return string
     */
    public static function getFileName($fileSuffix='')
    {
        //the filename should be created each time it is called
        list($usec, $sec) = explode(" ", microtime());
        $sTimestamp =  intval((float)$usec + (float)$sec);
        $sFilename = $fileSuffix. $sTimestamp . '.csv';
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
            header('Content-Disposition: attachment; filename="'.basename($file).'"');
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
        $oConfig = self::getOXConfig();
        $sShopURL = rtrim($oConfig->getConfigParam('sShopURL'), ' /');

        return '<a href="'.$sShopURL.'/' . self::$_sOxExportDir . $sModuleExportDirectoryName . basename($sFile) . '">' . basename($sFile) . ' </a>';
    }


    /**
     * gets the oxconfig object
     * @return null|oxConfig
     */
    public static function getOXConfig()
    {
        if(self::$_OxConfig === null){
            if (class_exists ( "oxconfig")) {
                if (method_exists (oxconfig, "getInstance") ) {
                    self::$_OxConfig =  oxconfig::getInstance();
                }
            }

            if(!is_object(self::$_OxConfig)){
                self::$_OxConfig = oxRegistry::getConfig();
            }
        }
        return self::$_OxConfig;
    }


    /**
     * @return int shop id
     */
    public static function getShopId()
    {
        return self::getOXConfig()->getShopId();
    }


    /**
     * Translates the given report
     * @param $dReport Report code
     * @return string styled report statement
     */
    public static function translateReport($dReport)
    {
        $ret = 'UNKNOWN STATUS';
        switch ( $dReport ){
            case   GN2_Utilities::SUCCESS:
                $ret = '<span style="background-color: forestgreen;"> Successful </span>';
                break;
            case   GN2_Utilities::FAULTY:
                $ret = '<span style="background-color: red;"> Error occurred (Connection failed, Object not found). check next hint. </span>';
                break;
            case   GN2_Utilities::NODATA:
                $ret = '<span style="background-color: orange;"> No Data found </span>';
                break;
            case   GN2_Utilities::NOFILERESOURCE:
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
    public static function MailingWorksUtf8Encode($str)
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
        if(self::$_exportDirPath === null){
            $sExportDir = '/gn2_aboexport/';
            if (function_exists('posix_getuid')) {
                $aUserInfo = posix_getpwuid(posix_getuid());
                self::$_exportDirPath =  $aUserInfo['dir']. $sExportDir;
            }else{
                //use shell
                $sTildeExpanded = hell_exec('echo ~');
                self::$_exportDirPath =  $sTildeExpanded . $sExportDir;
            }
        }

        //create Export folder
        if(!file_exists(self::$_exportDirPath)){
            mkdir(self::$_exportDirPath);
        }
        return self::$_exportDirPath;

    }

}