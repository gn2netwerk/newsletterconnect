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
        if (!$_SESSION) { session_start(); }

        // Remove Checkbox-Parameter to avoid weird behaviour
        if (isset($_SESSION['NewsletterConnect_Status'])) {
            unset($_SESSION['NewsletterConnect_Status']);
        }

        return parent::login();
    }

    /**
     * @return mixed
     */
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