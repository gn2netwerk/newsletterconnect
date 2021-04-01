<?php
/**
 * Gn2_NewsletterConnect
 * @category Gn2_NewsletterConnect
 * @package  Gn2_NewsletterConnect
 * @author   gn2 netwerk <kontakt@gn2.de>
 * @license  Gn2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version  GIT: <git_id>
 * @link     http://www.gn2-netwerk.de/
 */

namespace Gn2\NewsletterConnect\Core\Api\Mapper;

/**
 * Abstract data mapper class.
 * Can be extended for different types of mapper.
 */
abstract class MapperAbstract
{
    /**
     * @var $_entity
     */
    protected $_entity;

    /**
     * Returns results from the mapper
     *
     * @return \Gn2\NewsletterConnect\Core\Api\Data\Result Meta & result data
     */
    abstract function getResults();

    /**
     * Restrict the mapper to one specific entity/id.
     *
     * @param string $entity e.g. Record ID
     *
     * @return void
     */
    public function restrictEntity($entity)
    {
        $this->_entity = $entity;
    }

}