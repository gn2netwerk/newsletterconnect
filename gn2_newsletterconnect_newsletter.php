<?php
if (!class_exists('GN2_NewsletterConnect')) include(dirname(__FILE__).'/gn2_newsletterconnect.php');

/**
 * GN2_NewsletterConnect
 *
 * PHP version 5
 *
 * @category GN2_NewsletterConnect
 * @package  GN2_NewsletterConnect
 * @author   Dave Holloway <dh@gn2-netwerk.de>
 * @license  GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version  GIT: <git_id>
 * @link     http://www.gn2-netwerk.de/
 */

/**
 * GN2_NewsletterConnect_Newsletter
 *
 * @category GN2_NewsletterConnect
 * @package  GN2_NewsletterConnect
 * @author   Dave Holloway <dh@gn2-netwerk.de>
 * @license  GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version  Release: <package_version>
 * @link     http://www.gn2-netwerk.de/
 */
class GN2_NewsletterConnect_Newsletter extends GN2_NewsletterConnect_Newsletter_parent
{
    /*
     * Overwrites the existing OXID newsletter::send()
     */
    public function send()
    {
        $aParams  = oxConfig::getParameter("editval");
        $blSubscribe = oxConfig::getParameter("subscribeStatus");

        $oUser = oxNew( 'oxuser' );
        $oUser->oxuser__oxusername  = new oxField($aParams['oxuser__oxusername'], oxField::T_RAW);
        $oUser->oxuser__oxactive    = new oxField(1, oxField::T_RAW);
        $oUser->oxuser__oxrights    = new oxField('user', oxField::T_RAW);
        $oUser->oxuser__oxshopid    = new oxField($this->getConfig()->getShopId(), oxField::T_RAW);
        $oUser->oxuser__oxfname     = new oxField($aParams['oxuser__oxfname'], oxField::T_RAW);
        $oUser->oxuser__oxlname     = new oxField($aParams['oxuser__oxlname'], oxField::T_RAW);
        $oUser->oxuser__oxsal       = new oxField($aParams['oxuser__oxsal'], oxField::T_RAW);
        $oUser->oxuser__oxcountryid = new oxField($aParams['oxuser__oxcountryid'], oxField::T_RAW);

        if ($blSubscribe) {
            $oUser->setNewsSubscription(true, true);
            $this->_iNewsletterStatus = 1;
        } else {
            $oUser->setNewsSubscription(false, false);
            $this->_iNewsletterStatus = 3;
        }
    }
}