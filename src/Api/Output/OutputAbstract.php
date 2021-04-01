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

namespace Gn2\NewsletterConnect\Api\Output;

use \Gn2\NewsletterConnect\Api\Data\Result;

/**
 * Abstract class for data
 * output. Can be extended for different output formats.
 */
abstract class OutputAbstract
{
    /**
     * @var $_data Result Object
     */
    private $_data;

    /**
     * Gets the output, prepared for the browser.
     *
     * @return string Data output
     */
    abstract function displayData();

    /**
     * Gets the content-type for any HTTP-Headers.
     *
     * @return string Content type
     */
    abstract function getContentType();

    /**
     * Sets the data for the output
     *
     * @param Result $data Meta & result data
     *
     * @return boolean
     */
    public function setData($data)
    {
        if (is_object($data)) {
            $this->_data = $data;
            return true;
        }
        return false;
    }

    /**
     * Gets the data from the Gn2\NewsletterConnect\Api\Data\Result object
     *
     * @return \stdClass Meta & result data
     */
    public function getData()
    {
        return $this->_data->get();
    }

    /**
     * Sets HTTP-Headers and sends output to the browser.
     *
     * @return void
     */
    public function show()
    {
        header('Content-Type:' . $this->getContentType() . '; charset=utf-8');
        echo $this->displayData();
    }

}
