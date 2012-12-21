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
 * Oxid 4.4 specific class
 *
 * @category   GN2_NewsletterConnect
 * @package    GN2_NewsletterConnect
 * @subpackage Environment
 * @author     Heiko Adams <ha@gn2-netwerk.de>
 * @license    GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version    Release: <package_version>
 * @link       http://www.gn2-netwerk.de/
 */
class GN2_NewsletterConnect_Environment_Oxid44
extends GN2_NewsletterConnect_Environment_Oxid
{
    /**
     * Returns the Oxid 4.4 specific name of the article table
     *
     * @return string String containing the tablename
     */
    public function getArticleTableName()
    {
        return "oxarticles";
    }

    /**
     * Returns an empty string as article long description
     *
     * @return string Emptystring
     */
    public function getArticleLongDesc($article)
    {
        return '';
    }
}
