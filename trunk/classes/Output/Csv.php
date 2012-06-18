<?php
/**
 * GN2_NewsletterConnect
 * @package gn2_newsletterconnect
 * @copyright GN2 netwerk
 * @link http://www.gn2-netwerk.de/
 * @author Dave Holloway <dh[at]gn2-netwerk[dot]de>
 * @license GN2 Commercial Addon License
 */

class gn2_newsletterconnect_Output_Csv extends gn2_newsletterconnect_Output_Abstract
{
    public function getContentType()
    {
        return 'text/plain';
    }

    public function displayLine($tree,$level=0)
    {   $line = '';
        foreach ($tree as $branch) {
            $line .= '"'.$level.'";';
            $j = 0;
            foreach ($branch as $k=>$v) {
                if ($k=='childElements') {
                    $newlevel = $level+1;
                    $line .= "\n".$this->displayLine($v,$newlevel);
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

    public function displayData() {
        $data = $this->getData();
        return $this->displayLine($data->results);
    }

}
