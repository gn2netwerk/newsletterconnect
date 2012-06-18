<?php
/**
 * GN2_NewsletterConnect
 * @package gn2_newsletterconnect
 * @copyright GN2 netwerk
 * @link http://www.gn2-netwerk.de/
 * @author Dave Holloway <dh[at]gn2-netwerk[dot]de>
 * @license GN2 Commercial Addon License
 * @license gn2_newsletterconnect_oxoutput.php - MIT License
 */

require_once('copyprotect.php');

class gn2_newsletterconnect
{
    /*
     * looplink($directory);
     * Automatically symlink every file in a specified folder
     * within the module folder to a file with the same path
     * in the root OXID directory.
     */
    function looplink($dir = '',$append=true)
    {
        if (isAdmin()) {
            return true;
        }

        $me = getCwd();
        $moduleSlug = '/modules/'.basename(dirname(__FILE__)).'/';
        if ($append) {
            $me .= $moduleSlug;
        }
        $me .= str_replace(getCwd(),'',$dir);

        if (!file_exists($me)) {
            return false;
        }

        if ($handle = opendir($me)) {
            $files = array();

            while (false !== ($file = readdir($handle))) {
                $path = $me.'/'. $file;
                if (filetype($path) == "dir" && $file != "." && $file != "..") {
                    self::looplink($path,false);
                } else if ($file != "." && $file != "..") {
                    if ( !in_array(basename($file),array('.DS_Store','.project','.git','.gitignore')) ) {
                        $src = $path;
                        $dest = str_replace($moduleSlug,'/',$src);

                        if (!is_link($dest)) {
                            @symlink($src, $dest);
                        }
                    }
                }
            }
            closedir($handle);
        }
    }
}

/**
 * Dummy Class. We're only loading OXOUTPUT as a bootstrap.
 */
class gn2_newsletterconnect_oxoutput extends gn2_newsletterconnect_oxoutput_parent
{
}

if (defined('GN2_NEWSLETTERCONNECT_LOADED')) {
    gn2_newsletterconnect::looplink('core');
    gn2_newsletterconnect::looplink('admin');
    gn2_newsletterconnect::looplink('out');
    gn2_newsletterconnect::looplink('views');
}