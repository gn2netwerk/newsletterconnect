<?php

/**
 * Module information
 */

$version = @file_get_contents(dirname(__FILE__).'/version.php');

 $aModule = array(
    'id'            => 'gn2_newsletterconnect',
    'title'         => 'GN2 NewsletterConnect',
    'description'   => '',
    'thumbnail'     => 'gn2_newsletterconnect.jpg',
    'version'       => $version,
    'author'        => 'GN2 netwerk',
    'extend'        => array(
        'oxuser'             => 'gn2_newsletterconnect/gn2_newsletterconnect_oxuser',
        'account_newsletter' => 'gn2_newsletterconnect/gn2_newsletterconnect_account_newsletter',
        'newsletter'         => 'gn2_newsletterconnect/gn2_newsletterconnect_newsletter',
        'thankyou'           => 'gn2_newsletterconnect/gn2_newsletterconnect_thankyou',
    )
);