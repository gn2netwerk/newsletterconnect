<?php
/**
 * Gn2_NewsletterConnect
 * @category Gn2_NewsletterConnect
 * @package  Gn2_NewsletterConnect
 * @author   gn2 netwerk <kontakt@gn2.de>
 * @license  Gn2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version  GIT: <git_id>
 * @link     http://www.gn2-netwerk.de/
 */

namespace Gn2\NewsletterConnect\Application\Controller;

use Gn2\NewsletterConnect\Core\Api\WebService\WebService;


/**
 * Class UserController
 * @package Gn2\NewsletterConnect\Application\Controller
 */
class UserController extends UserController_parent
{

    /**
     * TODO: getRecipientIdsByEmail gibt nur bestätigte Abonnenten zurück. Wir bräuchten noch eine Abfrage, ob der
     * Nutzer registriert, aber die Optin Mail noch nicht bestätigt hat. GetOptIns funktioniert, gibt aber nur die
     * bestätigten zurück:
     * //$this->_setMailingworkUrl('GetOptins');
     * //$this->addParam('ListId', 1);
     * //$this->addParam('Type', "all");
     * //$response = $this->_getDecodedResponse();
     *
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
                $recipient = $oUser->generateRecipientObject();
                $email = $recipient->getEmail();
                $recipientExists = $oWebService->getRecipientByEmail($email);
                $this->_blNewsSubscribed = (bool) $recipientExists;
                return $this->_blNewsSubscribed;
            }

        }

        return parent::isNewsSubscribed();
    }

}