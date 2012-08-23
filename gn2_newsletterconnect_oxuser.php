<?php
require_once dirname(__FILE__).'/gn2_newsletterconnect_oxoutput.php';

/**
 * GN2_Newsletterconnect_OxUser
 *
 * @category GN2_Newsletterconnect
 * @package  GN2_Newsletterconnect
 * @author   Dave Holloway <dh@gn2-netwerk.de>
 * @license  GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version  Release: <package_version>
 * @link     http://www.gn2-netwerk.de/
 */
class GN2_NewsletterConnect_OxUser extends GN2_NewsletterConnect_OxUser_parent
{
    /**
     * Converts an array of OXID Data into an GN2_NewsletterConnect_Mailing_Recipient Object
     *
     * @param string $email    E-Mail Address of the user
     * @param array  $userInfo OXID User data from the user/register forms
     *
     * @return GN2_NewsletterConnect_Mailing_Recipient
     */
    protected function _gn2NewsletterConnectOxid2Recipient($email, $userInfo)
    {
        $recipient = new GN2_NewsletterConnect_Mailing_Recipient;

        $recipient->setEmail($email);
        $recipient->setSalutation($userInfo['oxuser__oxsal']);
        $recipient->setFirstName($userInfo['oxuser__oxfname']);
        $recipient->setLastName($userInfo['oxuser__oxlname']);
        $recipient->setCompany($userInfo['oxuser__oxcompany']);
        $recipient->setStreet($userInfo['oxuser__oxstreet']);
        $recipient->setHouseNumber($userInfo['oxuser__oxstreetnr']);
        $recipient->setZipCode($userInfo['oxuser__oxzip']);
        $recipient->setCity($userInfo['oxuser__oxcity']);
        $recipient->setCountry(''); // TODO: Resolve
        $recipient->setTelPrefix(''); // TODO: split & save
        $recipient->setTelNumber(''); // TODO: split & save
        $recipient->setFaxPrefix(''); // TODO: split & save
        $recipient->setFaxNumber(''); // TODO: split & save
        $recipient->setMobPrefix(''); // TODO: split & save
        $recipient->setMobNumber(''); // TODO: split & save

        return $recipient;
    }

    public function setNewsSubscription($blSubscribe, $blSendOptIn)
    {
        /* Get existing MailingService */
        $mailingService = GN2_Newsletterconnect::getMailingService();

        if ($blSubscribe) {
            /* Our MailingService takes care of this */
            $blSendOptIn = false;

            /* Get user preference */
            $oNewsSubscription = $this->getNewsSubscription();

            /* Create a recipient from the OXID user-data */
            $email = oxConfig::getParameter('lgn_usr');
            $userinfo = oxConfig::getParameter('invadr');
            $newRecipient = $this->_gn2NewsletterConnectOxid2Recipient($email, $userinfo);

            try {
                if (!$mailingService->getRecipientByEmail($email)) {
                    $mailingService->optInRecipient($mailingService->getMainShopList(), $newRecipient);
                }
            } catch (Exception $e) {
                //TODO: Live Exceptions?
            }
        }
    }
}