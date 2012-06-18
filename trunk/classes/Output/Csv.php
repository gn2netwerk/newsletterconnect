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
 * GN2_Newsletterconnect_Output_Csv - CSV Output-Class Implementation
 *
 * @category GN2_NewsletterConnect
 * @package  GN2_NewsletterConnect
 * @author   Dave Holloway <dh@gn2-netwerk.de>
 * @license  GN2 Commercial Addon License http://www.gn2-netwerk.de
 * @version  Release: <package_version>
 * @link     http://www.gn2-netwerk.de/
 */
class GN2_Newsletterconnect_Output_Csv extends GN2_Newsletterconnect_Output_Abstract
{
    /**
     * Returns Content Type
     *
     * @return string
     */
    public function getContentType()
    {
        return 'text/plain';
    }

    /**
     * Converts any data into a CSV line. Works recursively.
     *
     * @param array $tree  Any array. Can be multidimensional.
     * @param int   $level Current level.
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
     * Returns formatted output
     *
     * @return string
     */
    public function displayData()
    {
        $data = $this->getData();
        return $this->displayLine($data->results);
    }

}
