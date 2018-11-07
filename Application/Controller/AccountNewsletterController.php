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
    include dirname(__FILE__) . '/../../gn2_newsletterconnect.php';
}

use \GN2_NewsletterConnect;


/**
 * Class AccountNewsletterController
 * @package GN2\NewsletterConnect\Application\Controller
 */
class AccountNewsletterController extends AccountNewsletterController_parent
{
    /**
     * Gets the current user as a recipient object
     * @return mixed
     * @throws \Exception
     */
    private function _getNewsletterConnectUser()
    {
        /* Get existing MailingService */
        $mailingServiceUser = GN2_NewsletterConnect::getMailingService()->getRecipientByEmail(
            $this->getUser()->oxuser__oxusername->rawValue
        );

        return $mailingServiceUser;
    }


    /**
     * Checks if the current recipient is signed up for the newsletter
     * @return bool
     * @throws \Exception
     */
    public function isNewsletter()
    {
        if (!$_SESSION) { session_start(); }

        if (isset($_SESSION['NewsletterConnect_Status'])) {
            return (bool) $_SESSION['NewsletterConnect_Status'];
        }

        if ($this->_getNewsletterConnectUser() !== null) {
            return true;
        }
        return false;
    }


    /**
     * Subscribes a recipient directly, without opt-in
     * @throws \Exception
     */
    public function subscribe()
    {
        if (!$_SESSION) { session_start(); }

        $status = GN2_NewsletterConnect::getOXParameter('status');
        $list = GN2_NewsletterConnect::getMailingService()->getMainShopList();

        $recipient = $this->getUser()->gn2NewsletterConnectOxid2Recipient();
        $email = $recipient->getEmail();
        $recipientExists = GN2_NewsletterConnect::getMailingService()->getRecipientByEmail($email);

        if ($list !== null) {
            if ($status == 1) {
                if (!$recipientExists) {
                    GN2_NewsletterConnect::getMailingService()->optInRecipient($recipient, 'account');
                    /*GN2_NewsletterConnect::getMailingService()->subscribeRecipient($list, $recipient, 'account');*/
                }
                $_SESSION['NewsletterConnect_Status'] = 1;
            } else if ($status == 0 && $status !== null) {
                if ($recipientExists) {
                    GN2_NewsletterConnect::getMailingService()->unsubscribeRecipient($list, $recipient, 'account');
                }
                $_SESSION['NewsletterConnect_Status'] = 0;
            }
        }
    }

}