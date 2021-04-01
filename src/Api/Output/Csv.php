<?php
/**
 * Gn2_NewsletterConnect
 * @category Gn2_NewsletterConnect
 * @package  Gn2_NewsletterConnect
 * @author   gn2 netwerk <kontakt@gn2.de>
 * @license  Gn2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version  GIT: <git_id>
 * @link     http://www.gn2-netwerk.de/
 */

namespace Gn2\NewsletterConnect\Api\Output;

/**
 * CSV Output Class
 */
class Csv extends OutputAbstract
{
    /**
     * Gets the output, prepared for the browser.
     *
     * @return string Data output
     */
    public function getContentType()
    {
        return 'text/plain';
    }

    /**
     * Displays one CSV line per array entry.
     *
     * @param array $tree Array of output data.
     * @param int $level Level of tree
     *
     * @return string
     */
    public function displayLine($tree, $level = 0)
    {
        $line = '';
        foreach ($tree as $branch) {
            $line .= '"' . $level . '";';
            $j = 0;
            foreach ($branch as $k => $v) {
                if ($k == 'childElements') {
                    $newlevel = $level + 1;
                    $line .= "\n" . $this->displayLine($v, $newlevel);
                    $newline = false;
                } else {
                    $line .= '"' . addSlashes($v) . '"';
                    if ($j < count($branch)) {
                        $line .= ';';
                    }
                    $newline = true;
                }
                $j++;
            }
            if ($newline) {
                $line .= "\n";
            }
        }
        return $line;
    }

    /**
     * Gets the CSV output, prepared for the browser.
     *
     * @return string Data output
     */
    public function displayData()
    {
        $data = $this->getData();
        return $this->displayLine($data->results);
    }

}
