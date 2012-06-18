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

require_once 'copyprotect.php';

/**
 * Generic Linker Class. Contains functions to symlink
 * parts of the module into specific OXID core folders
 *
 * @category GN2_NewsletterConnect
 * @package  GN2_NewsletterConnect
 * @author   Dave Holloway <dh@gn2-netwerk.de>
 * @license  GN2 Commercial Addon License http://www.gn2-netwerk.de
 * @version  Release: <package_version>
 * @link     http://www.gn2-netwerk.de/
 */
class GN2_Newsletterconnect_Linker
{
    /**
     * looplink($directory);
     * Automatically symlink every file in a specified folder
     * within the module folder to a file with the same path
     * in the root OXID directory.
     *
     * @param string $dir    Folder to looplink
     * @param string $append Appends correct parent path to $dir
     *
     * @return boolean
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
        $me .= str_replace(getCwd(), '', $dir);

        if (!file_exists($me)) {
            return false;
        }

        if ($handle = opendir($me)) {
            $files = array();

            while (false !== ($file = readdir($handle))) {
                $path = $me.'/'. $file;
                if (filetype($path) == "dir" && $file != "." && $file != "..") {
                    self::looplink($path, false);
                } else if ($file != "." && $file != "..") {
                    if (!in_array(
                        basename($file),
                        array('.DS_Store','.project','.git','.gitignore')
                    )) {
                        $src = $path;
                        $dest = str_replace($moduleSlug, '/', $src);

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
 * Dummy Class. We are using OXOUTPUT as a bootstrap.
 *
 * @category GN2_NewsletterConnect
 * @package  GN2_NewsletterConnect
 * @author   Dave Holloway <dh@gn2-netwerk.de>
 * @license  GN2 Commercial Addon License http://www.gn2-netwerk.de
 * @version  Release: <package_version>
 * @link     http://www.gn2-netwerk.de/
 */

class GN2_Newsletterconnect_Oxoutput extends GN2_Newsletterconnect_Oxoutput_parent
{
}

/* Looplink subfolders of this module */
if (defined('GN2_NEWSLETTERCONNECT_LOADED')) {
    gn2_newsletterconnect_Linker::looplink('core');
    gn2_newsletterconnect_Linker::looplink('admin');
    gn2_newsletterconnect_Linker::looplink('out');
    gn2_newsletterconnect_Linker::looplink('views');
}