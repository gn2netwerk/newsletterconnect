<?php
/**
 * GN2_NewsletterConnect
 *
 * PHP version 5
 *
 * @category GN2_NewsletterConnect
 * @package  GN2_NewsletterConnect
 * @author   Dave Holloway <dh@gn2-netwerk.de>
 * @license  GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version  GIT: <git_id>
 * @link     http://www.gn2-netwerk.de/
 */

require_once 'copyprotect.php';

/**
 * GN2_NewsletterConnect - Main OXID Module Initialization Class
 *
 * @category GN2_NewsletterConnect
 * @package  GN2_NewsletterConnect
 * @author   Dave Holloway <dh@gn2-netwerk.de>
 * @license  GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version  Release: <package_version>
 * @link     http://www.gn2-netwerk.de/
 */
class GN2_NewsletterConnect
{
    public static $config = array();

    private function __construct()
    {
        $this->looplink('core');
        $this->looplink('admin');
        $this->looplink('out');
        $this->looplink('views');
    }

    /**
     * looplink($directory);
     * Automatically symlink every file in a specified folder
     * within the module folder to a file with the same path
     * in the root OXID directory.
     *
     * @param string  $dir    Folder to link
     * @param boolean $append Prepend module folder to path yes/no
     *
     * @return void
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
                    $this->looplink($path, false);
                } else if ($file != "." && $file != "..") {
                    if ( !in_array(
                        basename($file),
                        array('.DS_Store','.project','.git','.gitignore')
                    )
                    ) {
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

    public static function main()
    {
        try {
            include_once dirname(__FILE__).'/settings.php';
            $newsletterConnect = new self;
        } catch (Exception $e) {
            // TODO: Live ErrorTracking
        }
    }

    public static function getMailingService()
    {
        if (isset(self::$config['mailingService'])) {
            $key = self::$config['mailingService'];
            $className = 'GN2_NewsletterConnect_MailingService_'.$key;
            if (class_exists($className)) {
                $config = (isset(self::$config['service_'.$key])) ? self::$config['service_'.$key] : array();
                return new $className($config);
            }
            throw new Exception('gn2_newsletterConnect- Cannot find class:'.$className);
        }
    }

}


/**
 * GN2_NewsletterConnect_Oxoutput - Dummy Class. We're only loading OXOUTPUT as a bootstrap.
 *
 * @category GN2_NewsletterConnect
 * @package  GN2_NewsletterConnect
 * @author   Dave Holloway <dh@gn2-netwerk.de>
 * @license  GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version  Release: <package_version>
 * @link     http://www.gn2-netwerk.de/
 */
class gn2_newsletterconnect_oxoutput extends gn2_newsletterconnect_oxoutput_parent
{

}

if (defined('GN2_NEWSLETTERCONNECT_LOADED')) {
    GN2_NewsletterConnect::main();
}