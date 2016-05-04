<?php
if (!class_exists('GN2_NewsletterConnect')) require dirname(__FILE__).'/gn2_newsletterconnect.php';

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
$er = error_reporting(E_ALL ^E_NOTICE);

/**
 * GN2_NewsletterConnect_Api - Main API Initialization Class
 *
 * @category GN2_NewsletterConnect
 * @package  GN2_NewsletterConnect
 * @author   Dave Holloway <dh@gn2-netwerk.de>
 * @license  GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version  Release: <package_version>
 * @link     http://www.gn2-netwerk.de/
 */
class GN2_NewsletterConnect_Api
{
    /**
     * Initializes the API. Parses URL and starts mapper/output classes.
     *
     * @return void
     */
    static public function init()
    {
        $subject = $_SERVER['REQUEST_URI'];
        $pattern = '/'.
                   '(.*)\/modules\/gn2_newsletterconnect\/'.
                   '(?P<mapper>products|categories)'.
                   '\/?(?P<entity>[A-Za-z0-9?U\.\-\_]*)'.
                   '(\.(?P<output>json|csv)?)'.
                   '/';
        preg_match($pattern, $subject, $matches);

        $prefix = 'gn2_newsletterconnect_';
        $mapperClass = $prefix.'Mapper_'.ucfirst($matches['mapper']);

        if (!isset($matches['output'])) {
            $matches['output'] = 'json';
        }

        $outputClass = $prefix.'Output_'.ucfirst($matches['output']);

        if (class_exists($mapperClass) && class_exists($outputClass)) {

            $mapper = new $mapperClass;
            if (isset($matches['entity'])) {
                $mapper->restrictEntity($matches['entity']);
            }

            $output = new $outputClass;
            $output->setData($mapper->getResults());
            $output->show();
        }

        die();
    }

}

require_once 'gn2_newsletterconnect.php';
$env = GN2_NewsletterConnect::getEnvironment();

$env->loadBootstrap();

$valid = false;
try {
    /* Fix for PHP-CGI */
    if(preg_match('/Basic+(.*)$/i', $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], $matches))
    {
        list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = explode(':' , base64_decode(substr($_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 6)));
    }
    $oUser = oxNew('oxuser');
    $oUser->login($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
    $oGroups = $oUser->getUserGroups();

    foreach ($oGroups as $group) {
        if ($group->oxgroups__oxtitle->rawValue == "Newsletter Admin") {
            $valid = true;
        }
    }
}
catch (Exception $e) {
}

while ( !$valid ) {
    header('WWW-Authenticate: Basic realm="NewsletterConnect"');
    header('HTTP/1.0 401 Unauthorized');
    exit;
}
gn2_newsletterconnect_api::init();
ob_end_flush();
error_reporting($er);