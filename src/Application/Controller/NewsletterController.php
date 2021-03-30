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

use \GN2_NewsletterConnect;
use \OxidEsales\Eshop\Application\Model\User;
use \OxidEsales\Eshop\Core\Registry;
use \OxidEsales\Eshop\Core\Field;

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
        $oConfig = GN2_NewsletterConnect::getOXConfig();
        $aParams = GN2_NewsletterConnect::getOXParameter("editval");
        $blSubscribe = GN2_NewsletterConnect::getOXParameter("subscribeStatus");

        // Überprüfung, der angegebenen E-Mail Adresse auf Gültigkeit
        if (!$aParams['oxuser__oxusername']) {
            Registry::get("oxUtilsView")->addErrorToDisplay('ERROR_MESSAGE_COMPLETE_FIELDS_CORRECTLY');
            return;
        } elseif (!oxNew(\OxidEsales\Eshop\Core\MailValidator::class)->isValidEmail($aParams['oxuser__oxusername'])) {
            // #1052C - eMail validation added
            Registry::get("oxUtilsView")->addErrorToDisplay('MESSAGE_INVALID_EMAIL');
            return;
        }

        $oUser = oxNew(User::class);
        $oUser->oxuser__oxusername = new Field($aParams['oxuser__oxusername'], Field::T_RAW);
        $oUser->oxuser__oxactive = new Field(1, Field::T_RAW);
        $oUser->oxuser__oxrights = new Field('user', Field::T_RAW);
        $oUser->oxuser__oxshopid = new Field($oConfig->getShopId(), Field::T_RAW);
        $oUser->oxuser__oxfname = new Field($aParams['oxuser__oxfname'], Field::T_RAW);
        $oUser->oxuser__oxlname = new Field($aParams['oxuser__oxlname'], Field::T_RAW);
        $oUser->oxuser__oxsal = new Field($aParams['oxuser__oxsal'], Field::T_RAW);
        $oUser->oxuser__oxcountryid = new Field($aParams['oxuser__oxcountryid'], Field::T_RAW);

        if ($blSubscribe) {
            $oUser->setNewsSubscription(true, true);
            $this->_iNewsletterStatus = 1;
        } else {
            $oUser->setNewsSubscription(false, false);
            $this->_iNewsletterStatus = 3;
        }
    }

}