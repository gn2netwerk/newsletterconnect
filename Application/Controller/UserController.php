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

if (!class_exists('GN2_NewsletterConnect')) {
    include dirname(__FILE__) . '/../../gn2_newsletterconnect.php';
}

use \GN2_NewsletterConnect;


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

            if (!$_SESSION) { session_start(); }

            if (isset($_SESSION['NewsletterConnect_OrderOptIn_Checked'])) {
                $this->_blNewsSubscribed = (bool) $_SESSION['NewsletterConnect_OrderOptIn_Checked'];
                return $this->_blNewsSubscribed;
            }
        }

        return parent::isNewsSubscribed();
    }

}