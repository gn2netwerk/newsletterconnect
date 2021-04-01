<?php
/**
 * Gn2_NewsletterConnect
 * @category Gn2_NewsletterConnect
 * @package  Gn2_NewsletterConnect
 * @license  Gn2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version  GIT: <git_id>
 * @link     http://www.gn2-netwerk.de/
 *
 * Credits:
 * @author Dave Holloway <dh@gn2-netwerk.de>
 * @author Christoph Stäblein <cs@gn2.de>
 * @author Stanley Agu <st@gn2.de>
 * @author Kristian Berger <kristian.berger@sellando.de>
 * @author Heiko Adams <ha@gn2-netwerk.de>
 * @author Joachim Dörr <mail@joachim-doerr.com>
 */


/**
 * Module version
 */

$sMetadataVersion = '2.0';


/**
 * Module information
 */

$aModule = [
    'id' => 'gn2_newsletterconnect',
    'title' => 'gn2 :: NewsletterConnect',
    'description' => '',
    'thumbnail' => 'gn2_newsletterconnect.png',
    'version' => '##VERSION##',
    'author' => 'gn2 netwerk',
    'extend' => [
        \OxidEsales\Eshop\Application\Component\UserComponent::class =>
            \Gn2\NewsletterConnect\Application\Component\UserComponent::class,

        \OxidEsales\Eshop\Application\Model\User::class =>
            \Gn2\NewsletterConnect\Application\Model\User::class,

        \OxidEsales\Eshop\Application\Controller\AccountNewsletterController::class =>
            \Gn2\NewsletterConnect\Application\Controller\AccountNewsletterController::class,

        \OxidEsales\Eshop\Application\Controller\NewsletterController::class =>
            \Gn2\NewsletterConnect\Application\Controller\NewsletterController::class,

        \OxidEsales\Eshop\Application\Controller\ThankYouController::class =>
            \Gn2\NewsletterConnect\Application\Controller\ThankYouController::class,

        \OxidEsales\Eshop\Application\Controller\UserController::class =>
            \Gn2\NewsletterConnect\Application\Controller\UserController::class,

        \OxidEsales\Eshop\Core\Output::class =>
            \Gn2\NewsletterConnect\Core\Output::class,
    ],

    'controllers' => [
        'admin_newsletterconnect' =>
            \Gn2\NewsletterConnect\Application\Controller\Admin\AdminNewsletterConnectController::class,
    ],

    'templates' => [
        'admin_newsletterconnect.tpl' =>
            'gn2/newsletterconnect/Application/views/admin/tpl/admin_newsletterconnect.tpl',
    ],

    'events' => [
        'onActivate' => '\Gn2\NewsletterConnect\Core\Events::onActivate',
        'onDeactivate' => '\Gn2\NewsletterConnect\Core\Events::onDeactivate',
    ],

    // TODO: importMode von Exporter prüfen
    // TODO: Sprachdatei checken, Backend-Help buttons, zb. für GN2_NEWSLETTERCONNECT_OXID_STATUS_TITLE
    // TODO: public/api.php und Output.php vereinheitlichen?

];