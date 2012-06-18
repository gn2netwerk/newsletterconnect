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

class GN2_Newsletterconnect_Data_Result
{
    /**
     * Contains a formatted string result
     * @var string
     */
    private $_data = null;

    /**
     * Array of optional result meta information
     * @var array
     */
    private $_meta = array();

    /**
     * Adds any metadata to the result
     *
     * @param string $key   Key e.g. Count
     * @param string $value Value e.g. 45
     *
     * @return null
     */
    public function setMeta($key,$value)
    {
        if ($key!='' && $value!='') {
            $this->_meta[$key] = $value;
        }
    }

    /**
     * Adds data to the result
     *
     * @param array $data Array of data. Can be anything.
     *
     * @return void
     */
    public function setResult($data)
    {
        $this->_data = $data;
    }

    /**
     * Returns a stdClass with meta and result keys
     *
     * @return stdClass
     */
    public function get()
    {
        $result = new stdClass;
        $result->meta = $this->meta;
        $result->results = $this->data;
        return $result;
    }
}