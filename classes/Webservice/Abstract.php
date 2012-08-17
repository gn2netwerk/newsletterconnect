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
 * GN2_Newsletterconnect_Webservice_Abstract - Abstract webservice class.
 * Should be extended for different types of webservice.
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
abstract class GN2_Newsletterconnect_Webservice_Abstract
{
    private $_params = array();

    public function addParam($key, $value)
    {
        if (!is_null($key)) {
            if (is_null($value) && array_key_exists($key, $this->_params)) {
                /* Unset */
                $this->removeParam($key);
            } elseif (!is_null($value)) {
                /* Set */
                $this->_params[$key] = $value;
            }
        }
    }

    public function removeParam($key)
    {
        if (array_key_exists($key, $this->_params)) {
            unset($this->_params[$key]);
        }
    }

}