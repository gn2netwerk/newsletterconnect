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

namespace GN2\NewsletterConnect\Application\Controller;

if (!class_exists('GN2_NewsletterConnect')) {
    include dirname(__FILE__).'/../../gn2_newsletterconnect.php';
}

use GN2_NewsletterConnect;
use oxRegistry;


/**
 * Class NewsletterController
 * @package GN2\NewsletterConnect\Application\Controller
 */
class NewsletterController extends NewsletterController_parent
{
    /**
     * Overwrites the existing OXID newsletter::send()
     * @return void
     */
    public function send()
    {
        $aParams  = GN2_NewsletterConnect::getOXParameter("editval");
        $blSubscribe = GN2_NewsletterConnect::getOXParameter("subscribeStatus");

        // Überprüfung, der angegebenen E-Mail Adresse auf Gültigkeit
        if (!$aParams['oxuser__oxusername']) {
            oxRegistry::get("oxUtilsView")->addErrorToDisplay('ERROR_MESSAGE_COMPLETE_FIELDS_CORRECTLY');
            return;
        } elseif ( !oxRegistry::getUtils()->isValidEmail($aParams['oxuser__oxusername']) ) {
            // #1052C - eMail validation added
            oxRegistry::get("oxUtilsView")->addErrorToDisplay('MESSAGE_INVALID_EMAIL');
            return;
        }

        $oUser = oxNew( 'oxuser' );
        $oUser->oxuser__oxusername  = new oxField($aParams['oxuser__oxusername'], oxField::T_RAW);
        $oUser->oxuser__oxactive    = new oxField(1, oxField::T_RAW);
        $oUser->oxuser__oxrights    = new oxField('user', oxField::T_RAW);
        $oUser->oxuser__oxshopid    = new oxField($this->getConfig()->getShopId(), oxField::T_RAW);
        $oUser->oxuser__oxfname     = new oxField($aParams['oxuser__oxfname'], oxField::T_RAW);
        $oUser->oxuser__oxlname     = new oxField($aParams['oxuser__oxlname'], oxField::T_RAW);
        $oUser->oxuser__oxsal       = new oxField($aParams['oxuser__oxsal'], oxField::T_RAW);
        $oUser->oxuser__oxcountryid = new oxField($aParams['oxuser__oxcountryid'], oxField::T_RAW);

        if ($blSubscribe) {
            $oUser->setNewsSubscription(true, true);
            $this->_iNewsletterStatus = 1;
        } else {
            $oUser->setNewsSubscription(false, false);
            $this->_iNewsletterStatus = 3;
        }
    }

}