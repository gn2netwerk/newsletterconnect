<?php

/**
 * Class Gn2_NewsletterConnect_OxCmp_User
 */
class Gn2_NewsletterConnect_OxCmp_User extends Gn2_NewsletterConnect_OxCmp_User_Parent {


    public function login()
    {
        // Remove Checkbox-Parameter to avoid weird behaviour
        if (isset($_SESSION['NewsletterConnect_OrderOptIn_Checked'])) {
            unset($_SESSION['NewsletterConnect_OrderOptIn_Checked']);
        }
        if (isset($_SESSION['NewsletterConnect_OrderOptIn_Sent'])) {
            unset($_SESSION['NewsletterConnect_OrderOptIn_Sent']);
        }

        return parent::login();
    }

    public function logout()
    {
        // Remove Checkbox-Parameter to avoid weird behaviour
        if (isset($_SESSION['NewsletterConnect_OrderOptIn_Checked'])) {
            unset($_SESSION['NewsletterConnect_OrderOptIn_Checked']);
        }
        if (isset($_SESSION['NewsletterConnect_OrderOptIn_Sent'])) {
            unset($_SESSION['NewsletterConnect_OrderOptIn_Sent']);
        }

        return parent::logout();
    }


    /**
     * @return mixed
     */
    public function createUser()
    {
        if (!$_SESSION) { session_start(); }

        // parent-Funktion löst den Versand der Opt-In Mail aus
        $response = parent::createUser();

        // Variable MUSS nach parent-Aufruf gesetzt werden, weil es sonst mit optInRecipient() kollidiert
        if (GN2_NewsletterConnect::getOXParameter('blnewssubscribed')) {
            $_SESSION['NewsletterConnect_OrderOptIn_Checked'] = 1;
            $_SESSION['NewsletterConnect_OrderOptIn_Sent'] = 1;
        } else {
            $_SESSION['NewsletterConnect_OrderOptIn_Checked'] = 0;
        }

        return $response;
    }

    /**
     * @return mixed
     */
    protected function _changeUser_noRedirect()
    {
        if (!$_SESSION) { session_start(); }

        // parent-Funktion löst den Versand der Opt-In Mail aus
        $response = parent::_changeUser_noRedirect();

        // Variable MUSS nach parent-Aufruf gesetzt werden, weil es sonst mit optInRecipient() kollidiert
        if (GN2_NewsletterConnect::getOXParameter('blnewssubscribed')) {
            $_SESSION['NewsletterConnect_OrderOptIn_Checked'] = 1;
            $_SESSION['NewsletterConnect_OrderOptIn_Sent'] = 1;
        } else {
            $_SESSION['NewsletterConnect_OrderOptIn_Checked'] = 0;
        }

        return $response;
    }

}