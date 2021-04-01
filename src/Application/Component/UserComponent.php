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

namespace Gn2\NewsletterConnect\Application\Component;

/**
 * Class AccountNewsletterController
 * @package Gn2\NewsletterConnect\Application\Controller
 */
class UserComponent extends UserComponent_parent
{

    /**
     * @return mixed
     */
    public function login()
    {
        $response = parent::login();

        //$oSession = oxNew(\OxidEsales\Eshop\Core\Session::class);
        //$oSession->deleteVariable('NewsletterConnect_Status');

        return $response;
    }

    /**
     * @return mixed
     */
    public function logout()
    {
        //$oSession = oxNew(\OxidEsales\Eshop\Core\Session::class);
        //$oSession->deleteVariable('NewsletterConnect_Status');

        return parent::logout();
    }

}