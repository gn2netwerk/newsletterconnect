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
        /* Fake-it if the user has just changed their preferences, even with douple optin/outs. */
        if (!empty($_POST)) {
            if ($_POST['fnc'] == 'subscribe' && $_POST['status'] == '1') {
                return true;
            }
            if ($_POST['fnc'] == 'subscribe' && $_POST['status'] == '0') {
                return false;
            }
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
        $status = GN2_NewsletterConnect::getOXParameter('status');
        $recipient = $this->getUser()->gn2NewsletterConnectOxid2Recipient();
        $list = GN2_NewsletterConnect::getMailingService()->getMainShopList();

        if ($list!==null) {
            if ($status == 1) {
                GN2_NewsletterConnect::getMailingService()->optInRecipient($recipient, 'account');
                /*GN2_NewsletterConnect::getMailingService()->subscribeRecipient($list, $recipient, 'account');*/
            } else if ($status == 0 && $status !== null) {
                GN2_NewsletterConnect::getMailingService()->unsubscribeRecipient($list, $recipient, 'account');
            }
        }
    }

}