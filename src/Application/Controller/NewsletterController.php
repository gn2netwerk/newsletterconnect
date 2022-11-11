<?php
/**
 * @copyright   (c) gn2
 * @link        https://www.gn2.de/
 */

namespace Gn2\NewsletterConnect\Application\Controller;

use \OxidEsales\Eshop\Application\Model\User;
use OxidEsales\Eshop\Core\MailValidator;
use \OxidEsales\Eshop\Core\Registry;
use \OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\Request;

/**
 * Class NewsletterController
 * @package Gn2\NewsletterConnect\Application\Controller
 */
class NewsletterController extends NewsletterController_parent
{
    /**
     * Overwrites the existing OXID newsletter::send()
     * @return void
     */
    public function send()
    {
        $oConfig = Registry::getConfig();

        $aParams = Registry::get(Request::class)->getRequestEscapedParameter("editval");
        $blSubscribe = Registry::get(Request::class)->getRequestEscapedParameter("subscribeStatus");

        // Überprüfung, der angegebenen E-Mail Adresse auf Gültigkeit
        if (!$aParams['oxuser__oxusername']) {
            Registry::get("oxUtilsView")->addErrorToDisplay('ERROR_MESSAGE_COMPLETE_FIELDS_CORRECTLY');
            return;
        } elseif (!oxNew(MailValidator::class)->isValidEmail($aParams['oxuser__oxusername'])) {
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