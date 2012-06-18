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

/**
 * GN2_Newsletterconnect_Output_Abstract - Abstract class for various output formats
 *
 * @abstract
 * @category GN2_NewsletterConnect
 * @package  GN2_NewsletterConnect
 * @author   Dave Holloway <dh@gn2-netwerk.de>
 * @license  GN2 Commercial Addon License http://www.gn2-netwerk.de
 * @version  Release: <package_version>
 * @link     http://www.gn2-netwerk.de/
 */
abstract class GN2_Newsletterconnect_Output_Abstract
{
    /**
     * Contains an instance of gn2_newsletterconnect_Data_Result,
     * which is sent to the browser via send()
     * @var gn2_newsletterconnect_Data_Result
     */
    private $_data;

    /**
     * Method for child-classes to convert the data result
     * to a string.
     *
     * @return string
     */
    abstract function displayData();

    /**
     * Method for child-classes to return the correct http content type
     * e.g. application/json or text/plain etc.
     *
     * @return string
     */
    abstract function getContentType();

    /**
     * Expects an instance of GN2_Newsletterconnect_Data_Result
     *
     * @param object $data Instance of GN2_Newsletterconnect_Data_Result
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
     * Returns intance of gn2_newsletterconnect_Data_Result
     *
     * @return object
     */
    public function getData()
    {
        return $this->_data->get();
    }

    /**
     * Outputs the content, including any headers.
     * Compresses content, if accepted by the client.
     *
     * @return void
     */
    public function show()
    {
        if (!ob_start('ob_gzhandler')) {
            ob_start();
        }
        header('Content-Type:' . $this->getContentType() . '; charset=utf-8');
        echo $this->displayData();
        ob_end_flush();
    }

}
