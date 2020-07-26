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
        $bSessionStatus = GN2_NewsletterConnect::getOXSessionVariable('NewsletterConnect_Status');

        if (isset($bSessionStatus)) {
            return (bool) $bSessionStatus;
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
        $mailingService = GN2_NewsletterConnect::getMailingService();

        $recipient = $this->getUser()->gn2NewsletterConnectOxid2Recipient();
        $email = $recipient->getEmail();
        $recipientExists = $mailingService->getRecipientByEmail($email);

        $list = $mailingService->getMainShopList();
        $status = GN2_NewsletterConnect::getOXParameter('status');

        if ($list !== null) {
            if ($status == 1) {
                if (!$recipientExists) {
                    $mailingService->optInRecipient($recipient, 'account');
                    /*$mailingService->subscribeRecipient($list, $recipient, 'account');*/
                }
                GN2_NewsletterConnect::setOXSessionVariable('NewsletterConnect_Status', 1);
            } else if ($status == 0 && $status !== null) {
                if ($recipientExists) {
                    $mailingService->unsubscribeRecipient($list, $recipient, 'account');
                }
                GN2_NewsletterConnect::setOXSessionVariable('NewsletterConnect_Status', 0);
            }
        }
    }

}