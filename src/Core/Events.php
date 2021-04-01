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
        /*
         config_oxbaseshop -> config_1

         config_x -> config

         $config.service_Mailingwork.api_baseurl -> $config.api_baseurl

         INSERT INTO `oxconfig` (`OXID`, `OXSHOPID`, `OXMODULE`, `OXVARNAME`, `OXVARTYPE`, `OXVARVALUE`, `OXTIMESTAMP`) VALUES
         ('3cdaf7ea724f3fdf2c90160f45abe1dd', 'oxbaseshop', 'module:gn2_newsletterconnect', 'config_oxbaseshop', 'aarr', 0x4dba8e247f475c3c5c1799128f795657e7410dde964bc9fb2ebf4aadcd317f70df238c430281a967930a72cbfb36463ef861b490a75a27a22ef1c625c23e02889afc5d5eef687c0db6b701655fd4298338bc1aa5c93e418e8540c7140a472328a58bcb0a6e759a90bc32b654e94894b0727b680f64c16ae30dfc865aa425f0a28f8dd83dbd1091196993bab92b8062a6970497ccd6f1f98dbf8e31da8c62a2513e4c5f4050515ad4e5a5c1f481b9460a0bcf72fa40e18f3f5ce5288ab8f3c0078d0f09409facc2b17de2401ec9f6a35c1e8274e06edc770a39e4fb8455aec84f42ba58b23adea1028c576f871cef7440c15249257562494130806b4559b009aa48352adf3cab37238ca2ab474ff42539333418e260bbf0c3e08d8dd549ff19074d9312a94cae7f910dd331d49090124085327dc7ccfada06f39a02dd2eec6bbf4415df6d996b0cf6b717ce274ab3086f6270b96252808a3900836fc12b3f663afc56366fd92f4ff0a3acda4b567725a2cb, '2019-08-06 21:23:21');
         */

        /*
        $oDb = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC);

        $test = $oDb->select("SELECT * FROM `oxconfig` WHERE oxmodule = :oxmodule AND oxvartype like 'config_' ", [ ':oxmodule' => 'module:gn2_newsletterconnect' ]);

        echo "<pre>";
        print_r( $test );
        echo "</pre>";

        die();
        */


    }

    /**
     * Execute action on deactivate event
     */
    public static function onDeactivate()
    {

    }

}