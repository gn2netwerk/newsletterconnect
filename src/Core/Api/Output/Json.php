<?php
/**
 * Gn2_NewsletterConnect
 * @category Gn2_NewsletterConnect
 * @package  Gn2_NewsletterConnect
 * @author   gn2 netwerk <kontakt@gn2.de>
 * @license  Gn2 Commercial Addon License http://www.gn2-netwerk.de/
 * @link     http://www.gn2-netwerk.de/
 */

namespace Gn2\NewsletterConnect\Core\Api\Output;

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
