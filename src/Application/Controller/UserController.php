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

use GN2\NewsletterConnect\Api\WebService\WebService;


/**
 * Class UserController
 * @package GN2\NewsletterConnect\Application\Controller
 */
class UserController extends UserController_parent
{

    /**
     * @return bool
     */
    public function isNewsSubscribed()
    {
        if ($this->_blNewsSubscribed === null) {
            //$oSession = oxNew(\OxidEsales\Eshop\Core\Session::class);
            //$bSessionStatus = $oSession->getVariable('NewsletterConnect_Status');

            if (isset($bSessionStatus)) {
                $this->_blNewsSubscribed = (bool) $bSessionStatus;
                return $this->_blNewsSubscribed;
            }

            $oUser = $this->getUser();
            $oWebService = oxNew( WebService::class );

            if ($oUser) {
                // ziehe aktuellen Status von Mailingwork - benötigt Single-OptIn für Anmeldesetup (Kundenaccount)
                $recipient = $oUser->gn2NewsletterConnectOxid2Recipient();
                $email = $recipient->getEmail();
                $recipientExists = $oWebService->getRecipientByEmail($email);
                $this->_blNewsSubscribed = (bool) $recipientExists;
                return $this->_blNewsSubscribed;
            }

        }

        return parent::isNewsSubscribed();
    }

}