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

            if (isset($_SESSION['NewsletterConnect_OrderOptIn_Checked'])) {
                $this->_blNewsSubscribed = (bool) $_SESSION['NewsletterConnect_OrderOptIn_Checked'];
                return $this->_blNewsSubscribed;
            }
        }

        return parent::isNewsSubscribed();
    }

}