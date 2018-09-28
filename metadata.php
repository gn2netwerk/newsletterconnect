<?php

/**
 * Module information
 */

 $aModule = array(
    'id'            => 'gn2_newsletterconnect',
    'title'         => 'gn2 :: NewsletterConnect',
    'description'   => '',
    'thumbnail'     => 'gn2_newsletterconnect.png',
    'version'       => '##VERSION##',
    'author'        => 'gn2 netwerk',
    'extend'        => array(
        'oxuser'             => 'gn2_newsletterconnect/gn2_newsletterconnect_oxuser',
        'account_newsletter' => 'gn2_newsletterconnect/gn2_newsletterconnect_account_newsletter',
        'newsletter'         => 'gn2_newsletterconnect/gn2_newsletterconnect_newsletter',
        'thankyou'           => 'gn2_newsletterconnect/gn2_newsletterconnect_thankyou',
        // 'oxvoucher'          => 'gn2_newsletterconnect/gn2_newsletterconnect_oxvoucher',
        'oxoutput'           => 'gn2_newsletterconnect/gn2_newsletterconnect_oxoutput',
        'user'               => 'gn2_newsletterconnect/gn2_newsletterconnect_user',
        'oxcmp_user'         => 'gn2_newsletterconnect/gn2_newsletterconnect_oxcmp_user',
    ),
    'files' => array(
        'gn2_newsletterconnect_config' => 'gn2_newsletterconnect/gn2_newsletterconnect_config.php',
     ),
     'templates' => array(
         'gn2_newsletterconnect_config.tpl' => 'gn2_newsletterconnect/gn2_newsletterconnect_config.tpl'
     )
);
