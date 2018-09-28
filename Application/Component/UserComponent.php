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

namespace GN2\NewsletterConnect\Application\Component;

if (!class_exists('GN2_NewsletterConnect')) {
    include dirname(__FILE__) . '/../../gn2_newsletterconnect.php';
}

use \GN2_NewsletterConnect;


/**
 * Class AccountNewsletterController
 * @package GN2\NewsletterConnect\Application\Controller
 */
class UserComponent extends UserComponent_parent
{

    /**
     * @return mixed
     */
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

    /**
     * @return mixed
     */
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