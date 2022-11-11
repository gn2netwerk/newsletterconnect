<?php
/**
 * @copyright   (c) gn2
 * @link        https://www.gn2.de/
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