<?php
/**
 * GN2_NewsletterConnect
 *
 * PHP version 5
 *
 * @category   GN2_Newsletterconnect
 * @package    GN2_Newsletterconnect
 * @subpackage Mapper
 * @author     Dave Holloway <dh@gn2-netwerk.de>
 * @license    GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version    GIT: <git_id>
 * @link       http://www.gn2-netwerk.de/
 */

/**
 * GN2_Newsletterconnect_Mapper_Abstract - Abstract data mapper class.
 * Can be extended for different types of mapper.
 *
 * @category   GN2_Newsletterconnect
 * @package    GN2_Newsletterconnect
 * @subpackage Mapper
 * @author     Dave Holloway <dh@gn2-netwerk.de>
 * @license    GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version    Release: <package_version>
 * @link       http://www.gn2-netwerk.de/
 * @abstract
 */
abstract class GN2_Newsletterconnect_Mapper_Abstract
{
    protected $_entity;

    /**
     * Returns results from the mapper
     *
     * @return GN2_Newsletterconnect_Data_Result Meta & result data
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