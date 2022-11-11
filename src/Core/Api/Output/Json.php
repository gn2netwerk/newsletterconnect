<?php
/**
 * @copyright   (c) gn2
 * @link        https://www.gn2.de/
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
