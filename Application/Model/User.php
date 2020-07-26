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

namespace GN2\NewsletterConnect\Application\Model;

if (!class_exists('GN2_NewsletterConnect')) {
    include dirname(__FILE__) . '/../../gn2_newsletterconnect.php';
}

use \GN2_NewsletterConnect;
use \GN2_NewsletterConnect_Mailing_Recipient;


/**
 * Class User
 * @package GN2\NewsletterConnect\Application\Model
 */
class User extends User_parent
{
    /**
     * Converts the current oxUser-Object into an GN2_NewsletterConnect_Mailing_Recipient Object
     * @param string|optional $oxuser__oxemail the user e-mail address
     * @return GN2_NewsletterConnect_Mailing_Recipient
     */
    public function gn2NewsletterConnectOxid2Recipient($oxuser__oxemail = '')
    {
        $recipient = new GN2_NewsletterConnect_Mailing_Recipient;
        if ($oxuser__oxemail !== '') {
            $recipient->setEmail($oxuser__oxemail);
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

        $oUBase = oxNew(\OxidEsales\Eshop\Application\Controller\FrontendController::class);
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
     * @throws \Exception
     */
    public function setNewsSubscription($blSubscribe, $blSendOptIn, $blForceCheckOptIn = false)
    {
        /* Figuring out which setup should be used */
        $cl = GN2_NewsletterConnect::getOXParameter('cl');
        $mode = 'general';

        switch ($cl) {
            case 'account_user':
                $mode = 'account';
                break;
            case 'user':
                $oUser = $this->getUser();

                if (!$oUser || !$oUser->oxuser__oxpassword->value) {
                    $mode = 'general';
                } else {
                    $mode = 'account';
                }
                break;
            case 'register':
                break;
            case 'newsletter':
                break;
        }

        /* Get existing MailingService */
        $mailingService = GN2_NewsletterConnect::getMailingService();
        $newRecipient = $this->gn2NewsletterConnectOxid2Recipient();
        $email = $newRecipient->getEmail();

        if ($blSubscribe) {
            try {
                if (!$mailingService->getRecipientByEmail($email)) {
                    $mailingService->optInRecipient($newRecipient, $mode);
                }

                if ($cl != 'newsletter') {
                    //GN2_NewsletterConnect::setOXSessionVariable('NewsletterConnect_Status', 1);
                }
            } catch (\Exception $e) {
                /* Do nothing */
            }
        } else {
            try {
                $list = GN2_NewsletterConnect::getMailingService()->getMainShopList();

                if ($mailingService->getRecipientByEmail($email)) {
                    $mailingService->unsubscribeRecipient($list, $newRecipient, $mode);
                }

                if ($cl != 'newsletter') {
                    //GN2_NewsletterConnect::setOXSessionVariable('NewsletterConnect_Status', 0);
                }
            } catch (\Exception $e) {
                /* Do nothing */
            }
        }
        return true;
    }


    /**
     * @return mixed
     * @throws \Exception
     */
    public function save()
    {
        $blRet = parent::save();

        try {
            $recipient = $this->gn2NewsletterConnectOxid2Recipient();
            GN2_NewsletterConnect::getMailingService()->updateRecipient($recipient);
        } catch (\Exception $e) {
            /* Do nothing */
        }

        return $blRet;
    }
}