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
 * GN2_NewsletterConnect_Account_Newsletter
 *
 * @category GN2_NewsletterConnect
 * @package  GN2_NewsletterConnect
 * @author   Dave Holloway <dh@gn2-netwerk.de>
 * @license  GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version  Release: <package_version>
* @link     http://www.gn2-netwerk.de/
*/
class GN2_NewsletterConnect_Account_Newsletter extends GN2_NewsletterConnect_Account_Newsletter_parent
{
    /**
     * Gets the current user as a recipient object
     *
     * @return GN2_NewsletterConnect_Mailing_Recipient Recipient
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
     *
     * @return bool
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
     *
     * @return void
     */
    public function subscribe()
    {
        $status = oxConfig::getParameter('status');
        $recipient = $this->getUser()->gn2NewsletterConnectOxid2Recipient();
        $list = GN2_NewsletterConnect::getMailingService()->getMainShopList();

        if ($status == 1) {
            GN2_NewsletterConnect::getMailingService()->optInRecipient($recipient, 'account');
            /*GN2_NewsletterConnect::getMailingService()->subscribeRecipient($list, $recipient, 'account');*/
        } else if ($status == 0 && $status !== null) {
            GN2_NewsletterConnect::getMailingService()->unsubscribeRecipient($list, $recipient, 'account');
        }


    }

}