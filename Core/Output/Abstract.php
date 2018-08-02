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
 * Abstract class for data
 * output. Can be extended for different output formats.
 */
abstract class GN2_NewsletterConnect_Output_Abstract
{
    /**
     * @var $_data GN2_NewsletterConnect_Data_Result Object
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
     * @param GN2_NewsletterConnect_Data_Result $data Meta & result data
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
     * Gets the data from the GN2_NewsletterConnect_Data_Result object
     *
     * @return stdClass Meta & result data
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
