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
 * GN2_Newsletterconnect_Output_Csv - CSV Output Class
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
class GN2_Newsletterconnect_Output_Csv extends GN2_Newsletterconnect_Output_Abstract
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
     * @param array $tree  Array of output data.
     * @param int   $level Level of tree
     *
     * @return string
     */
    public function displayLine($tree,$level=0)
    {   $line = '';
        foreach ($tree as $branch) {
            $line .= '"'.$level.'";';
            $j = 0;
            foreach ($branch as $k=>$v) {
                if ($k=='childElements') {
                    $newlevel = $level+1;
                    $line .= "\n".$this->displayLine($v, $newlevel);
                    $newline = false;
                } else {
                    $line .= '"'.addSlashes($v).'"';
                    if ($j < count($branch)) {
                        $line .= ';';
                    }
                    $newline = true;
                }
                $j++;
            }
            if ($newline) {
                $line .="\n";
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
