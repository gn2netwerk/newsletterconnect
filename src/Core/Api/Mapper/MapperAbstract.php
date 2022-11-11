<?php
/**
 * @copyright   (c) gn2
 * @link        https://www.gn2.de/
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