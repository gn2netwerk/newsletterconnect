<?php

/**
 * Class Gn2_NewsletterConnect_OxCmp_User
 */
class Gn2_NewsletterConnect_OxCmp_User extends Gn2_NewsletterConnect_OxCmp_User_Parent {


    public function login()
    {
        if (!$_SESSION) { session_start(); }

        // Remove Checkbox-Parameter to avoid weird behaviour
        if (isset($_SESSION['NewsletterConnect_Status'])) {
            unset($_SESSION['NewsletterConnect_Status']);
        }

        return parent::login();
    }


    public function logout()
    {
        if (!$_SESSION) { session_start(); }

        // Remove Checkbox-Parameter to avoid weird behaviour
        if (isset($_SESSION['NewsletterConnect_Status'])) {
            unset($_SESSION['NewsletterConnect_Status']);
        }

        return parent::logout();
    }


    /**
     * @return mixed
     */
    public function createUser()
    {
        $response = parent::createUser();
        return $response;
    }

    /**
     * @return mixed
     */
    protected function _changeUser_noRedirect()
    {
        $response = parent::_changeUser_noRedirect();
        return $response;
    }

}