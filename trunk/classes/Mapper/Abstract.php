<?php
/**
 * GN2_NewsletterConnect
 *
 * PHP version 5
 *
 * @category GN2_NewsletterConnect
 * @package  GN2_NewsletterConnect
 * @author   Dave Holloway <dh@gn2-netwerk.de>
 * @license  GN2 Commercial Addon License http://www.gn2-netwerk.de
 * @version  GIT: <git_id>
 * @link     http://www.gn2-netwerk.de/
 */

/**
 * GN2_Newsletterconnect_Mapper_Abstract - Generic Abstract Class
 * for simple data mappers
 *
 * @abstract
 * @category GN2_NewsletterConnect
 * @package  GN2_NewsletterConnect
 * @author   Dave Holloway <dh@gn2-netwerk.de>
 * @license  GN2 Commercial Addon License http://www.gn2-netwerk.de
 * @version  Release: <package_version>
 * @link     http://www.gn2-netwerk.de/
 */
abstract class GN2_Newsletterconnect_Mapper_Abstract
{
    /**
     * Variable to store id of one entity. Unused at the moment.
     * @todo Maybe remove this?
     * @var string
     */
    protected $entity;

    /**
     * Returns any results from the mapper
     *
     * @return gn2_newsletterconnect_Data_Result
     */
    abstract function getResults();

    /**
     * Restricts the mapper to one entity
     *
     * @param string $entity Not implemented
     *
     * @todo Not implemented at the moment.
     *
     * @return void
     */
    public function restrictEntity($entity)
    {
        $this->entity = $entity;
    }

}