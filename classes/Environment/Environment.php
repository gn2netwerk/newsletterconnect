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
 * Environment Interface
 *
 * @category   GN2_NewsletterConnect
 * @package    GN2_NewsletterConnect
 * @subpackage Environment
 * @author     Heiko Adams <ha@gn2-netwerk.de>
 * @license    GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version    Release: <package_version>
 * @link       http://www.gn2-netwerk.de/
 * @interface
 */
interface GN2_NewsletterConnect_Environment
{
    /**
     * Returns the name of the article table
     *
     * @return GN2_NewsletterConnect_Environment_Oxid Meta & result data
     */
    public function getArticleTableName();

    /**
     * Bootstraps the oxid instance
     *
     * @return GN2_NewsletterConnect_Environment_Oxid Meta & result data
     */
    public function loadBootstrap();
}
