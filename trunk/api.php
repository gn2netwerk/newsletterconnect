<?php
/**
 * GN2_NewsletterConnect
 * @package gn2_newsletterconnect
 * @copyright GN2 netwerk
 * @link http://www.gn2-netwerk.de/
 * @author Dave Holloway <dh[at]gn2-netwerk[dot]de>
 * @license GN2 Commercial Addon License
 */

require_once('copyprotect.php');

/**
 * Main Class to process API-Requests
 * @author Dave Holloway <dh[at]gn2-netwerk[dot]de>
 */
class gn2_newsletterconnect_Api
{
    /**
     * Initializes the gn2_newsletterconnect-API
     * and processes the URL via RegEx. The found mapper is
     * then instantiated, control is given to the mapper class
     * and the output is processed by the output class.
     * @return void
     */
    static public function init()
    {
        $subject = $_SERVER['REQUEST_URI'];
        $pattern = '/'.
                   '(.*)\/modules\/gn2_newsletterconnect\/'.
                   '(?P<mapper>products|categories)'.
                   '\/?(?P<entity>[A-Za-z0-9]*)?'.
                   '(\.(?P<output>json|csv)?)?'.
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

if (!function_exists('getShopBasePath')) {
    /**
     * Returns the OXID-Shop Base Path. The function returns
     * the correct folder name, even if the module folder has been
     * symlinked.
     * @return void
     */
    function getShopBasePath()
    {
        return $_SERVER['DOCUMENT_ROOT'].'/'.dirname(dirname(dirname($_SERVER['SCRIPT_NAME']))).'/';
    }

    # Include OXID Core Classes
    require getShopBasePath() . 'modules/functions.php';
    require_once getShopBasePath() . 'core/oxfunctions.php';
    oxUtils::getInstance()->stripGpcMagicQuotes();
}


$valid = false;
try {
    # Fix for PHP-CGI
    if (!isset($_SERVER['PHP_AUTH_USER'])) {
        list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = explode(':' , base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
    }

    # Authenticate User via the OXID oxuser Classes
    $oUser = oxNew('oxuser');
    $oUser->login( $_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'] );
    $oGroups = $oUser->getUserGroups();

    foreach ($oGroups as $group) {
        if ($group->oxgroups__oxtitle->rawValue == "Newsletter Admin") {
            $valid = true;
        }
    }
}
catch (Exception $e) {
}

# Constantly ask for username & password via HTTP-Authentification
while ( !$valid ) {
    header('WWW-Authenticate: Basic realm="NewsletterConnect"');
    header('HTTP/1.0 401 Unauthorized');
    exit;
}

# If Authenticated, init() the API
gn2_newsletterconnect_Api::init();
