<?php
/**
 * GN2_NewsletterConnect
 * @category GN2_NewsletterConnect
 * @package  GN2_NewsletterConnect
 * @license  GN2 Commercial Addon License http://www.gn2-netwerk.de/
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

$aModule = array(
    'id' => 'gn2_newsletterconnect',
    'title' => 'gn2 :: NewsletterConnect',
    'description' => '',
    'thumbnail' => 'gn2_newsletterconnect.png',
    'version' => '2.0',
    'author' => 'gn2 netwerk',
    'extend' => array(
        \OxidEsales\Eshop\Application\Component\UserComponent::class =>
            \GN2\NewsletterConnect\Application\Component\UserComponent::class,

        \OxidEsales\Eshop\Application\Model\User::class =>
            \GN2\NewsletterConnect\Application\Model\User::class,

        //\OxidEsales\Eshop\Application\Model\Voucher::class =>
        //    \GN2\NewsletterConnect\Application\Model\Voucher::class,

        \OxidEsales\Eshop\Application\Controller\AccountNewsletterController::class =>
            \GN2\NewsletterConnect\Application\Controller\AccountNewsletterController::class,

        \OxidEsales\Eshop\Application\Controller\NewsletterController::class =>
            \GN2\NewsletterConnect\Application\Controller\NewsletterController::class,

        \OxidEsales\Eshop\Application\Controller\ThankYouController::class =>
            \GN2\NewsletterConnect\Application\Controller\ThankYouController::class,

        \OxidEsales\Eshop\Application\Controller\UserController::class =>
            \GN2\NewsletterConnect\Application\Controller\UserController::class,

        \OxidEsales\Eshop\Core\Output::class =>
            \GN2\NewsletterConnect\Core\Output::class,
    ),

    'controllers' => array(
        'newsletterconnect_config' =>
            \GN2\NewsletterConnect\Application\Controller\Admin\newsletterconnect_config::class,
    ),

    'templates' => array(
        'newsletterconnect_config.tpl' =>
            'gn2/newsletterconnect/Application/views/admin/tpl/newsletterconnect_config.tpl',
    ),
);