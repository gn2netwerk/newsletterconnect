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

/**
 * Environment Interface
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
     * Returns the article long description
     *
     * @param $article
     * @return string Emptystring
     */
    public function getArticleLongDesc($article);


    /**
     * Bootstraps the oxid instance
     *
     * @return GN2_NewsletterConnect_Environment_Oxid Meta & result data
     */
    public function loadBootstrap();


    /**
     * @return mixed
     */
    public function getModuleConfig();

}
