<?php
/**
 * This Software is the property of OXID eSales and is protected
 * by copyright law.
 *
 * Any unauthorized use of this software will be prosecuted by
 * civil and criminal law.
 *
 * @link      http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2017
 * @version   OXID eSales Visual CMS
 */

namespace Gn2\NewsletterConnect\Core;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\DbMetaDataHandler;

/**
 * Class Events
 */
class Events
{

    /**
     * Execute action on activate event
     */
    public static function onActivate()
    {
        self::_rebuildOxConfigVars();
        self::_copyHtaccess();
    }

    /**
     * Execute action on deactivate event
     */
    public static function onDeactivate()
    {
        // nothing to do here
    }

    private static function _rebuildOxConfigVars()
    {
        $oConfig = Registry::getConfig();
        $oDb = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC);

        // refactoring all old configurations into the cleaned-up format
        $aOldConfigs = $oDb->getAll("SELECT `OXID`, `OXSHOPID`, `OXMODULE`, `OXVARNAME` FROM `oxconfig` WHERE `OXMODULE` = 'module:gn2_newsletterconnect' AND `OXVARNAME` like 'config_%' ORDER BY `OXTIMESTAMP` ASC");

        if ($aOldConfigs > 0) {
            $aNewConfigs = array();

            foreach ($aOldConfigs as $aOldConfig) {

                // extract the shop id from OXVARNAME. don't use the OXSHOPID
                // in most shops the varname is "config_1", "config_oxbaseshop" or simply "config"
                $aVarNameParts = explode("_", $aOldConfig['OXVARNAME']);

                if (array_key_exists(1, $aVarNameParts)) {
                    // if the value is config_1, config_2.. take this value
                    // if the value is config_oxbaseshop, then take shop-id 1 instead
                    $sShopId = (intval($aVarNameParts[1]) < 1) ? 1 : $aVarNameParts[1];
                } else {
                    // if the value is config, then take the oxshopid
                    $sShopId = $aOldConfig['OXSHOPID'];
                }

                // get config value. it hasn't changed since oxid 4
                $aConfigValue = (array) $oConfig->getShopConfVar($aOldConfig['OXVARNAME'], $aOldConfig['OXSHOPID'], $aOldConfig['OXMODULE']);

                // collect the new config for later
                // if there are more configs with the same shop-id, it will take the newest config based on its timestamp
                $aNewConfigs[$sShopId] = [
                    'OXSHOPID' => $sShopId,
                    'OXMODULE' => $aOldConfig['OXMODULE'],
                    'OXVARNAME' => 'config',
                    'OXVARVALUE' => $aConfigValue,
                ];

                // delete old entry
                $sDelete = "DELETE FROM `oxconfig` WHERE `OXID` = '" . $aOldConfig['OXID'] . "';";
                $oDb->execute($sDelete);
            }

            foreach($aNewConfigs as $aNewConfig) {
                $oConfig->saveShopConfVar('aarr', $aNewConfig['OXVARNAME'], $aNewConfig['OXVARVALUE'], $aNewConfig['OXSHOPID'], $aNewConfig['OXMODULE']);
            }
        }
    }

    private static function _copyHtaccess()
    {
        // TODO
    }

}