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
 * GN2_Newsletterconnect_Output_Json - JSON Output-Class Implementation
 *
 * @category GN2_NewsletterConnect
 * @package  GN2_NewsletterConnect
 * @author   Dave Holloway <dh@gn2-netwerk.de>
 * @license  GN2 Commercial Addon License http://www.gn2-netwerk.de
 * @version  Release: <package_version>
 * @link     http://www.gn2-netwerk.de/
 */
class GN2_Newsletterconnect_Output_Json extends GN2_Newsletterconnect_Output_Abstract
{
    /**
     * Returns Content Type
     *
     * @return string
     */
    public function getContentType()
    {
        return 'application/json';
    }

    /**
     * Returns JSON-Encoded string
     *
     * @return string
     */
    public function displayData()
    {
        $data = json_encode($this->getData());
        return $data;
    }

}
