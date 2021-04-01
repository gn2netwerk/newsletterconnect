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

/**
 * API-Aufrufe sehen folgendermaßen aus:
 *
 * https://www.domain.tld/modules/gn2/newsletterconnect/categories.json
 * https://www.domain.tld/modules/gn2/newsletterconnect/categories/oia9ff5c96f1f29d527b61202ece0829.json
 *
 * https://www.domain.tld/modules/gn2/newsletterconnect/products.json
 * https://www.domain.tld/modules/gn2/newsletterconnect/products/05848170643ab0deb9914566391c0c63.json
 *
 * Die Authentifikationsdaten gehören zu einem Oxid-Admin der Nutzergruppe "Newsletter Admin".
 * Wie in der Installationsanleitung des Moduls beschrieben.
 */

require_once dirname(__FILE__) . "/../../../../bootstrap.php";

//$er = error_reporting(E_ALL ^ E_NOTICE);

$valid = false;

try {
    if (preg_match('/Basic+(.*)$/i', $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], $matches)) {
        list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = explode(':', base64_decode(substr($_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 6)));
    }

    $oUser = oxNew(\OxidEsales\Eshop\Application\Model\User::class);

    if ($_SERVER['PHP_AUTH_USER'] != "" && $_SERVER['PHP_AUTH_PW'] != "") {
        if ($oUser->login($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])) {
            // if authentication is oxid admin, then check if the user has newsletterconnect access rights

            foreach ($oUser->getUserGroups() as $group) {
                if ($group->oxgroups__oxtitle->rawValue == "Newsletter Admin") {
                    $valid = true;
                }
            }
        }
    }
} catch (\Exception $e) {
    /* Do nothing */
}

while (!$valid) {
    header('WWW-Authenticate: Basic realm="NewsletterConnect"');
    header('HTTP/1.0 401 Unauthorized');
    exit;
}

// Authentification successful. Now handle the requested data: products or categories

$subject = $_SERVER['REQUEST_URI'];
$pattern = '/' .
    '(.*)\/modules\/gn2\/newsletterconnect\/' .
    '(?P<mapper>products|categories)' .
    '\/?(?P<entity>[A-Za-z0-9?U\.\-\_]*)' .
    '(\.(?P<output>json|csv)?)' .
    '/';
preg_match($pattern, $subject, $matches);

if (!isset($matches['output'])) {
    $matches['output'] = 'json';
}

$mapperClass = '\Gn2\NewsletterConnect\Core\Api\Mapper\\' . ucfirst(strtolower($matches['mapper']));
$outputClass = '\Gn2\NewsletterConnect\Core\Api\Output\\' . ucfirst(strtolower($matches['output']));

if (class_exists($mapperClass) && class_exists($outputClass)) {

    $mapper = new $mapperClass;
    if (isset($matches['entity'])) {
        $mapper->restrictEntity($matches['entity']);
    }

    $output = new $outputClass;
    $output->setData($mapper->getResults());
    $output->show();

}

ob_end_flush();
//error_reporting($er);
die();