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
implements GN2_NewsletterConnect_Environment
{
    /**
     * Returns the name of the article table
     *
     * @return string String containing the tablename
     */
    public function getArticleTableName()
    {
        return "oxv_oxarticles";
    }

    /**
     * Bootstraps the oxis instance
     *
     * @return void
     */
    public function loadBootstrap()
    {
        require_once realpath("../..") . "/bootstrap.php";
    }
}
