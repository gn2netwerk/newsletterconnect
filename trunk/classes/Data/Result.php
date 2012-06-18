<?php
/**
 * GN2_NewsletterConnect
 * @package gn2_newsletterconnect
 * @copyright GN2 netwerk
 * @link http://www.gn2-netwerk.de/
 * @author Dave Holloway <dh[at]gn2-netwerk[dot]de>
 * @license GN2 Commercial Addon License
 */

class gn2_newsletterconnect_Data_Result
{
    private $data = array();
    private $meta = array();
    public function setMeta($key,$value)
    {
        if ($key!='' && $value!='') {
            $this->meta[$key] = $value;
        }
    }
    public function setResult($data)
    {
        $this->data = $data;
    }
    public function get()
    {
        $result = new stdClass;
        $result->meta = $this->meta;
        $result->results = $this->data;
        return $result;
    }
}