<?php
/**
 * @copyright   (c) gn2
 * @link        https://www.gn2.de/
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