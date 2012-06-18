<?php
/**
 * GN2_NewsletterConnect
 * @package gn2_newsletterconnect
 * @copyright GN2 netwerk
 * @link http://www.gn2-netwerk.de/
 * @author Dave Holloway <dh[at]gn2-netwerk[dot]de>
 * @license GN2 Commercial Addon License
 */

/**
 * JSON Output-Class Implementation
 */
class gn2_newsletterconnect_Output_Json extends gn2_newsletterconnect_Output_Abstract
{
    /**
     * Returns Content Type
     * @return string
     */
    public function getContentType()
    {
        return 'application/json';
    }

    /**
     * Returns JSON-Encoded string
     * @return string
     */
    public function displayData() {
        $data = json_encode($this->getData());
        return $data;
    }

}
