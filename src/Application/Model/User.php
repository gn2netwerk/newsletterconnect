<?php
/**
 * Gn2_NewsletterConnect
 * @category Gn2_NewsletterConnect
 * @package  Gn2_NewsletterConnect
 * @author   gn2 netwerk <kontakt@gn2.de>
 * @license  Gn2 Commercial Addon License http://www.gn2-netwerk.de/
 * @link     http://www.gn2-netwerk.de/
 */

namespace Gn2\NewsletterConnect\Application\Model;

use Exception;
use Gn2\NewsletterConnect\Core\Api\WebService\WebService;
use \Gn2\NewsletterConnect\Core\Api\Mailing\Recipient;
use OxidEsales\Eshop\Application\Controller\FrontendController;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Request;
use OxidEsales\Eshop\Core\Session;


/**
 * Class User
 * @package Gn2\NewsletterConnect\Application\Model
 */
class User extends User_parent
{
    /**
     * Converts the current oxUser-Object into an Recipient Object
     * @param string $sEmail the user e-mail address
     * @return Recipient
     */
    public function generateRecipientObject($sEmail = '')
    {
        $recipient = new Recipient;
        if ($sEmail !== '') {
            $recipient->setEmail($sEmail);
        } else {
            $recipient->setEmail($this->oxuser__oxusername->rawValue);
        }

        $salutation = $this->oxuser__oxsal->rawValue;
        switch (strtolower($salutation)) {
            case "mr":
                $salutation = 'Herr';
                break;
            case "mrs":
            case "miss":
                $salutation = 'Frau';
                break;
        }

        $recipient->setSalutation($salutation);
        $recipient->setFirstName($this->oxuser__oxfname->rawValue);
        $recipient->setLastName($this->oxuser__oxlname->rawValue);
        $recipient->setCompany($this->oxuser__oxcompany->rawValue);
        $recipient->setStreet($this->oxuser__oxstreet->rawValue);
        $recipient->setHouseNumber($this->oxuser__oxstreetnr->rawValue);
        $recipient->setZipCode($this->oxuser__oxzip->rawValue);
        $recipient->setCity($this->oxuser__oxcity->rawValue);
        $recipient->setCountry(''); // TODO: Resolve
        $recipient->setTelPrefix(''); // TODO: split & save
        $recipient->setTelNumber(''); // TODO: split & save
        $recipient->setFaxPrefix(''); // TODO: split & save
        $recipient->setFaxNumber(''); // TODO: split & save
        $recipient->setMobPrefix(''); // TODO: split & save
        $recipient->setMobNumber(''); // TODO: split & save

        //set the optin status
        if (isset($this->oxuser__oxdboptin)) {
            if ($this->oxuser__oxdboptin->rawValue == 0 && $this->oxuser__oxunsubscribed->rawValue == '0000-00-00 00:00:00') {
                //nie angemdeldet
                $recipient->setOxidNewsletterStatus(0);
            } elseif ($this->oxuser__oxdboptin->rawValue == 0 && $this->oxuser__oxunsubscribed->rawValue != '0000-00-00 00:00:00') {
                //haben sich mal abgemeldet
                $recipient->setOxidNewsletterStatus(3);
            } else {
                $recipient->setOxidNewsletterStatus($this->oxuser__oxdboptin->rawValue);
            }

        }

        $oUBase = oxNew(FrontendController::class);
        $langISO = $oUBase->getActiveLangAbbr();
        $recipient->setLanguage($langISO);

        return $recipient;
    }


    /**
     * Opts In the recipient
     * @param boolean $blSubscribe Subscribe yes/no
     * @param boolean $blSendOptIn Unused in this implementation, inherited from overridden function
     * @param boolean $blForceCheckOptIn Unused in this implementation, inherited from overridden function
     * @return bool
     * @throws Exception
     */
    public function setNewsSubscription($blSubscribe, $blSendOptIn, $blForceCheckOptIn = false)
    {
        /* Figuring out which setup should be used */
        $cl = Registry::get(Request::class)->getRequestEscapedParameter('cl');

        switch ($cl) {
            case 'account_user':
                $mode = 'account';
                break;
            case 'user':
                $oUser = $this->getUser();

                if ($oUser && !empty($oUser->oxuser__oxpassword->value)) {
                    $mode = 'account';
                } else {
                    $mode = 'general';
                }
                break;
            case 'register':
            case 'newsletter':
            default:
                $mode = 'general';
                break;
        }

        $oWebService = oxNew( WebService::class );
        $newRecipient = $this->generateRecipientObject();
        $email = $newRecipient->getEmail();
        $oSession = oxNew(Session::class);

        if ($blSubscribe) {
            try {
                if (!$oWebService->getRecipientByEmail($email)) {
                    $oWebService->optInRecipient($newRecipient, $mode);
                }

                if ($cl != 'newsletter') {
                    //$oSession->setVariable('NewsletterConnect_Status', 1);
                }
            } catch (Exception $e) {
                /* Do nothing */
            }
        } else {
            try {

                $list = $oWebService->getMainShopList();

                if ($oWebService->getRecipientByEmail($email)) {
                    $oWebService->unsubscribeRecipient($list, $newRecipient, $mode);
                }

                if ($cl != 'newsletter') {
                    //$oSession->setVariable('NewsletterConnect_Status', 0);
                }
            } catch (Exception $e) {
                /* Do nothing */
            }
        }
        return true;
    }


    /**
     * @return mixed
     * @throws Exception
     */
    public function save()
    {
        $blRet = parent::save();

        try {
            $recipient = $this->generateRecipientObject();
            $oWebService = oxNew( WebService::class );
            $oWebService->updateRecipient($recipient);
        } catch (Exception $e) {
            /* Do nothing */
        }

        return $blRet;
    }
}