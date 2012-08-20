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
    protected function _gn2NewsletterConnect_Oxid2Recipient($email, $userInfo)
    {
        $recipient = new GN2_NewsletterConnect_Mailing_Recipient;
        $recipient->setEmail($email);
        $recipient->setSalutation($userinfo['oxuser__oxsal']);
        $recipient->setFirstName($userinfo['oxuser__oxfname']);
        $recipient->setLastName($userinfo['oxuser__oxlname']);
        $recipient->setCompany($userinfo['oxuser__oxcompany']);
        $recipient->setStreet($userinfo['oxuser__oxstreet']);
        $recipient->setHouseNumber($userinfo['oxuser__oxstreetnr']);
        $recipient->setZipCode($userinfo['oxuser__oxzip']);
        $recipient->setCity($userinfo['oxuser__oxcity']);
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
        echo '<pre>';
        if ($blSubscribe) {
            /* Our MailingService takes care of this */
            $blSendOptIn = false;

            /* Get user preference */
            $oNewsSubscription = $this->getNewsSubscription();

            /* Get existing MailingService */
            $mailingService = GN2_Newsletterconnect::getMailingService();

            /* Get main shop list */
            $list = $mailingService->getMainShopList();

            /* Create a recipient from the OXID user-data */
            $email = oxConfig::getParameter('lgn_usr');
            $userinfo = oxConfig::getParameter('invadr');
            $recipient = $this->_gn2NewsletterConnect_Oxid2Recipient($email, $userinfo);

            try {
                $mailingService->createRecipient($list->getId(), $recipient);
            } catch (Exception $e) {
                print_r($e);
                die();
            }

        }
        echo '<hr>';
        print_r($_POST);

        echo '</pre>';

        die('died');
    }
}