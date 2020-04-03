<?php

/**
 * Class Gn2_NewsletterConnect_User
 */
class Gn2_NewsletterConnect_User extends Gn2_NewsletterConnect_User_Parent
{

    /**
     * @return bool
     */
    public function isNewsSubscribed()
    {
        if ($this->_blNewsSubscribed === null) {

            if (!$_SESSION) { session_start(); }

            if (isset($_SESSION['NewsletterConnect_Status'])) {
                $this->_blNewsSubscribed = (bool) $_SESSION['NewsletterConnect_Status'];
                return $this->_blNewsSubscribed;
            }

            $oUser = $this->getUser();

            if ($oUser) {
                // ziehe aktuellen Status von Mailingwork - benötigt Single-OptIn für Anmeldesetup (Kundenaccount)
                $recipient = $oUser->gn2NewsletterConnectOxid2Recipient();
                $email = $recipient->getEmail();
                $recipientExists = GN2_NewsletterConnect::getMailingService()->getRecipientByEmail($email);
                $this->_blNewsSubscribed = (bool) $recipientExists;
                return $this->_blNewsSubscribed;
            }

        }

        return parent::isNewsSubscribed();
    }

}