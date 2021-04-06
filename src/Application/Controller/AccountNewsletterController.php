<?php
/**
 * Gn2_NewsletterConnect
 * @category Gn2_NewsletterConnect
 * @package  Gn2_NewsletterConnect
 * @author   gn2 netwerk <kontakt@gn2.de>
 * @license  Gn2 Commercial Addon License http://www.gn2-netwerk.de/
 * @link     http://www.gn2-netwerk.de/
 */

namespace Gn2\NewsletterConnect\Application\Controller;

use \Exception;
use Gn2\NewsletterConnect\Core\Api\WebService\WebService;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Request;
use OxidEsales\Eshop\Core\Session;


/**
 * Class AccountNewsletterController
 * @package Gn2\NewsletterConnect\Application\Controller
 */
class AccountNewsletterController extends AccountNewsletterController_parent
{
    /**
     * Gets the current user as a recipient object
     * @return mixed
     * @throws Exception
     */
    private function _getNewsletterConnectUser()
    {
        $oUser = $this->getUser();
        $sUserEmail = $oUser->oxuser__oxusername->rawValue;
        $oWebService = oxNew( WebService::class );

        return $oWebService->getRecipientByEmail(
            $sUserEmail
        );
    }


    /**
     * Checks if the current recipient is signed up for the newsletter
     * @return bool
     * @throws Exception
     */
    public function isNewsletter()
    {
        $oSession = oxNew(Session::class);
        $bSessionStatus = $oSession->getVariable('NewsletterConnect_Status');

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
     * @throws Exception
     */
    public function subscribe()
    {
        $oWebService = oxNew( WebService::class );

        $oUser = $this->getUser();
        $recipient = $oUser->generateRecipientObject();
        $email = $recipient->getEmail();
        $recipientExists = $oWebService->getRecipientByEmail($email);

        $list = $oWebService->getMainShopList();
        $status = Registry::get(Request::class)->getRequestEscapedParameter('status');
        $oSession = oxNew(Session::class);

        if ($list !== null) {
            if ($status == 1) {
                if (!$recipientExists) {
                    $oWebService->optInRecipient($recipient, 'account');
                    /*$oWebService->subscribeRecipient($list, $recipient, 'account');*/
                }
                $oSession->setVariable('NewsletterConnect_Status', 1);
            } else if ($status == 0 && $status !== null) {
                if ($recipientExists) {
                    $oWebService->unsubscribeRecipient($list, $recipient, 'account');
                }
                $oSession->setVariable('NewsletterConnect_Status', 0);
            }
        }
    }

}