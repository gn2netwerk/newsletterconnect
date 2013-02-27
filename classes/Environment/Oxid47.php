<?php
/**
 * GN2_NewsletterConnect
 *
 * PHP version 5
 *
 * @category   GN2_NewsletterConnect
 * @package    GN2_NewsletterConnect
 * @subpackage Environment
 * @author     Heiko Adams <ha@gn2-netwerk.de>
 * @license    GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version    GIT: <git_id>
 * @link       http://www.gn2-netwerk.de/
 */
/**
 * Oxid 4.7 specific class
 *
 * @category   GN2_NewsletterConnect
 * @package    GN2_NewsletterConnect
 * @subpackage Environment
 * @author     Heiko Adams <ha@gn2-netwerk.de>
 * @license    GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version    Release: <package_version>
 * @link       http://www.gn2-netwerk.de/
 */
class GN2_NewsletterConnect_Environment_Oxid47
extends GN2_NewsletterConnect_Environment_Oxid
{
    /**
     * Bootstraps the oxis instance
     *
     * @return void
     */
    public function loadBootstrap()
    {
        include_once dirname(__FILE__) . '/../../../../bootstrap.php';
    }
}
