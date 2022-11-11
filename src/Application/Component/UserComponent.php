<?php
/**
 * @copyright   (c) gn2
 * @link        https://www.gn2.de/
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