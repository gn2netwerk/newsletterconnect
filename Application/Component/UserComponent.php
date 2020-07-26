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

use \GN2_NewsletterConnect;
use \OxidEsales\Eshop\Core\Registry;


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
        $response = parent::login();
        //GN2_NewsletterConnect::deleteOXSessionVariable('NewsletterConnect_Status');
        return $response;
    }

    /**
     * @return mixed
     */
    public function logout()
    {
        //GN2_NewsletterConnect::deleteOXSessionVariable('NewsletterConnect_Status');
        return parent::logout();
    }

}