<?php
#require_once dirname(__FILE__).'/gn2_newsletterconnect_oxoutput.php';

/**
 * GN2_Newsletterconnect_OxNewsletterSubscribed
 *
 * @category GN2_Newsletterconnect
 * @package  GN2_Newsletterconnect
 * @author   Dave Holloway <dh@gn2-netwerk.de>
 * @license  GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version  Release: <package_version>
 * @link     http://www.gn2-netwerk.de/
 */
class GN2_NewsletterConnect_Account_Newsletter extends GN2_NewsletterConnect_Account_Newsletter_parent
{
    private function _getNewsletterConnectUser()
    {
        /* Get existing MailingService */
        $mailingServiceUser = GN2_Newsletterconnect::getMailingService()->getRecipientByEmail(
            $this->getUser()->oxuser__oxusername->rawValue
        );
        return $mailingServiceUser;
    }

    public function isNewsletter()
    {
        if ($this->_getNewsletterConnectUser() !== null) {
            return true;
        }
        return false;
    }


    public function subscribe()
    {
        $status = oxConfig::getParameter('status');
        $recipient = new GN2_NewsletterConnect_Mailing_Recipient;
        $recipient->setEmail($this->getUser()->oxuser__oxusername->rawValue);
        $list = GN2_Newsletterconnect::getMailingService()->getMainShopList();

        if (!$this->isNewsletter() && $status == 1) {
            GN2_Newsletterconnect::getMailingService()->subscribeRecipient($list, $recipient);
        } else if ($status == 0 && $status !== null) {
            GN2_Newsletterconnect::getMailingService()->unsubscribeRecipient($list, $recipient);
        }


    }

}