<?php
/**
 * GN2_NewsletterConnect
 * @package gn2_newsletterconnect\Mapper
 * @copyright GN2 netwerk
 * @link http://www.gn2-netwerk.de/
 * @author Dave Holloway <dh[at]gn2-netwerk[dot]de>
 * @license GN2 Commercial Addon License
 */

/**
 * Generic Abstract Class for simple data mappers
 * @abstract
 */
abstract class gn2_newsletterconnect_Mapper_Abstract
{
    /**
     * Variable to store id of one entity. Unused at the moment.
     * @todo Maybe remove this?
     * @var string
     */
    protected $entity;

    /**
     * Returns any results from the mapper
     * @return gn2_newsletterconnect_Data_Result
     */
    abstract function getResults();

    /**
     * Restricts the mapper to one entity
     * TODO: Not implemented at the moment.
     * @param string $entity
     */
    public function restrictEntity($entity)
    {
        $this->entity = $entity;
    }

}