<?php
if (!class_exists('GN2_NewsletterConnect')) {
    include dirname(__FILE__).'/gn2_newsletterconnect.php';
}

/**
 * GN2_NewsletterConnect
 *
 * PHP version 5
 *
 * @category GN2_NewsletterConnect
 * @package  GN2_NewsletterConnect
 * @author   Dave Holloway <dh@gn2-netwerk.de>
 * @license  GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version  GIT: <git_id>
 * @link     http://www.gn2-netwerk.de/
 */

/**
 * GN2_NewsletterConnect_OxUser
 *
 * @category GN2_NewsletterConnect
 * @package  GN2_NewsletterConnect
 * @author   Dave Holloway <dh@gn2-netwerk.de>
 * @license  GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version  Release: <package_version>
 * @link     http://www.gn2-netwerk.de/
 */
class GN2_NewsletterConnect_OxUser extends GN2_NewsletterConnect_OxUser_parent
{
    /**
     * Converts the current oxUser-Object into an GN2_NewsletterConnect_Mailing_Recipient Object
     *
     * @return GN2_NewsletterConnect_Mailing_Recipient
     */
    public function gn2NewsletterConnectOxid2Recipient()
    {
        $recipient = new GN2_NewsletterConnect_Mailing_Recipient;
        $recipient->setEmail($this->oxuser__oxusername->rawValue);

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

        $config = gn2_newsletterconnect::getEnvironment()->getModuleConfig();

        return $recipient;
    }

    /**
     * Opts In the recipient
     *
     * @param boolean $blSubscribe       Subscribe yes/no
     * @param boolean $blSendOptIn       Unused in this implementation, inherited from overridden function
     * @param boolean $blForceCheckOptIn Unused in this implementation, inherited from overridden function
     *
     * @return void
     */
    public function setNewsSubscription($blSubscribe, $blSendOptIn, $blForceCheckOptIn = false)
    {
        /* Get existing MailingService */
        $mailingService = GN2_NewsletterConnect::getMailingService();
        $newRecipient = $this->gn2NewsletterConnectOxid2Recipient();

        if ($blSubscribe) {
            /* Our MailingService takes care of this */
            $blSendOptIn = false;

            /* Get user preference */
            $oNewsSubscription = $this->getNewsSubscription();

            /* Create a recipient from the OXID user-data */
            $email = $oNewsSubscription->oxnewssubscribed__oxemail->value;


            try {
                if (!$mailingService->getRecipientByEmail($email)) {
                    $mailingService->optInRecipient($newRecipient);
                }
            } catch (Exception $e) {
                /* Do nothing */
            }
        } else {
            /* Everywhere but the user page */
            try {
                if (!in_array(oxConfig::getParameter('cl'), array('user', 'register'))) {
                    $list = GN2_NewsletterConnect::getMailingService()->getMainShopList();
                    $mailingService->unsubscribeRecipient($list, $newRecipient);
                }
            } catch(Exception $e) {
                /* Do nothing */
            }
        }
        return true;
    }
}