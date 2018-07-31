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
 * Abstract data mapper class.
 * Can be extended for different types of mapper.
 *
 * @category   GN2_NewsletterConnect
 * @package    GN2_NewsletterConnect
 * @subpackage Mapper
 * @author     Dave Holloway <dh@gn2-netwerk.de>
 * @license    GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version    Release: <package_version>
 * @link       http://www.gn2-netwerk.de/
 * @abstract
 */
abstract class GN2_NewsletterConnect_Mapper_Abstract
{
    /**
     * @var $_entity Unused //TODO: Remove
     */
    protected $_entity;

    /**
     * Returns results from the mapper
     *
     * @return GN2_NewsletterConnect_Data_Result Meta & result data
     */
    abstract function getResults();

    /**
     * Restrict the mapper to one specific entity/id.
     *
     * @param string $_entity e.g. Record ID
     *
     * @return void
     */
    public function restrictEntity($_entity)
    {
        $this->entity = $_entity;
    }

}