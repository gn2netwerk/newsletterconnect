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

namespace Gn2\NewsletterConnect\Core\Api\Data;

use stdClass;

/**
 * Wrapper class to contain
 */
class Result
{
    /**
     * @var array Multidimensional key-value pairs
     */
    private $_data = array();

    /**
     * @var array Metadata about the data result
     */
    private $_meta = array();

    /**
     * Sets meta data. Expects key/value pairs.
     *
     * @param string $key Key
     * @param string $value Value
     *
     * @return void
     */
    public function setMeta($key, $value)
    {
        if ($key != '' && $value != '') {
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
        $result = new stdClass();
        $result->meta = $this->_meta;
        $result->results = $this->_data;
        return $result;
    }
}