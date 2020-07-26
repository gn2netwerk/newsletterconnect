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

namespace GN2\NewsletterConnect\Core\Output;

/**
 * JSON Output Class
 */
class Json extends OutputAbstract
{
    /**
     * Gets the output, prepared for the browser.
     *
     * @return string Data output
     */
    public function getContentType()
    {
        return 'application/json';
    }

    /**
     * Gets the JSON-encoded output, prepared for the browser.
     *
     * @return string Data output
     */
    public function displayData()
    {
        $data = json_encode($this->getData());
        return $data;
    }

}
