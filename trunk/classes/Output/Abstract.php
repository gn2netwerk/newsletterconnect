<?php
/**
 * GN2_NewsletterConnect
 * @package gn2_newsletterconnect
 * @copyright GN2 netwerk
 * @link http://www.gn2-netwerk.de/
 * @author Dave Holloway <dh[at]gn2-netwerk[dot]de>
 * @license GN2 Commercial Addon License
 */

abstract class gn2_newsletterconnect_Output_Abstract
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
        if (!ob_start('ob_gzhandler')) {
            ob_start();
        }
        header('Content-Type:' . $this->getContentType() . '; charset=utf-8');
        echo $this->displayData();
        ob_end_flush();
    }

}
