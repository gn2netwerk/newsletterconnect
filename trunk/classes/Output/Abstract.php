<?php
/**
 * GN2_NewsletterConnect
 *
 * PHP version 5
 *
 * @category   GN2_Newsletterconnect
 * @package    GN2_Newsletterconnect
 * @subpackage Output
 * @author     Dave Holloway <dh@gn2-netwerk.de>
 * @license    GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version    GIT: <git_id>
 * @link       http://www.gn2-netwerk.de/
 */

/**
 * GN2_Newsletterconnect_Output_Abstract - Abstract class for data
 * output. Can be extended for different output formats.
 *
 * @category   GN2_Newsletterconnect
 * @package    GN2_Newsletterconnect
 * @subpackage Output
 * @author     Dave Holloway <dh@gn2-netwerk.de>
 * @license    GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version    Release: <package_version>
 * @link       http://www.gn2-netwerk.de/
 * @abstract
 */
abstract class GN2_Newsletterconnect_Output_Abstract
{
    private $data;

    abstract function displayData();
    abstract function getContentType();

    public function setData($data)
    {
        if (is_object($data)) {
            $this->data = $data;
            return true;
        }
        return false;
    }

    public function getData()
    {
        return $this->data->get();
    }

    public function show() {
        header('Content-Type:' . $this->getContentType() . '; charset=utf-8');
        echo $this->displayData();
    }

}
