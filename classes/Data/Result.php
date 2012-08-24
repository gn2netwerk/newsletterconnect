<?php
/**
 * GN2_NewsletterConnect
 *
 * PHP version 5
 *
 * @category   GN2_NewsletterConnect
 * @package    GN2_NewsletterConnect
 * @subpackage Data
 * @author     Dave Holloway <dh@gn2-netwerk.de>
 * @license    GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version    GIT: <git_id>
 * @link       http://www.gn2-netwerk.de/
 */

/**
 * Wrapper class to contain
 * an output array and metadata about the output. Used by the output classes.
 *
 * @category   GN2_NewsletterConnect
 * @package    GN2_NewsletterConnect
 * @subpackage Data
 * @author     Dave Holloway <dh@gn2-netwerk.de>
 * @license    GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version    Release: <package_version>
 * @link       http://www.gn2-netwerk.de/
 */
class GN2_NewsletterConnect_Data_Result
{
    private $_data = array();
    private $_meta = array();

    /**
     * Sets meta data. Expects key/value pairs.
     *
     * @param string $key   Key
     * @param string $value Value
     *
     * @return void
     */
    public function setMeta($key,$value)
    {
        if ($key!='' && $value!='') {
            $this->_meta[$key] = $value;
        }
    }

    /**
     * Sets the result
     *
     * @param array $data Array of data. Can be multidimensional.
     *
     * @return void
     */
    public function setResult($data)
    {
        $this->_data = $data;
    }

    /**
     * Gets a wrapped class of meta/result data.
     *
     * @return stdClass Meta & result data
     */
    public function get()
    {
        $result = new stdClass;
        $result->meta = $this->_meta;
        $result->results = $this->_data;
        return $result;
    }
}