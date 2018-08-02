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

        \OxidEsales\Eshop\Core\Output::class =>
            \GN2\NewsletterConnect\Core\Output::class,
    ),

    'files' => array(
        'GN2_NewsletterConnect_Config' =>
            'gn2/newsletterconnect/Application/Controller/admin/GN2_NewsletterConnect_Config.php',
    ),

    'templates' => array(
        'gn2_newsletterconnect_config.tpl' =>
            'gn2/newsletterconnect/views/admin/tpl/gn2_newsletterconnect_config.tpl',
    ),
);
