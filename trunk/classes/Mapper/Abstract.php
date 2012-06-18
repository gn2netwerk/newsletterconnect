<?php
/**
 * GN2_NewsletterConnect
 * @package gn2_newsletterconnect
 * @copyright GN2 netwerk
 * @link http://www.gn2-netwerk.de/
 * @author Dave Holloway <dh[at]gn2-netwerk[dot]de>
 * @license GN2 Commercial Addon License
 */

abstract class gn2_newsletterconnect_Mapper_Abstract
{
    protected $entity;
    abstract function getResults();
    public function restrictEntity($entity)
    {
        $this->entity = $entity;
    }

}