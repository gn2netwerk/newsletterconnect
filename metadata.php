<?php

/**
 * Module information
 */

 $aModule = array(
    'id'            => 'gn2_newsletterconnect',
    'title'         => 'GN2 NewsletterConnect',
    'description'   => '',
    'thumbnail'     => 'gn2_newsletterconnect.jpg',
    'version'       => '0.1',
    'author'        => 'GN2 netwerk',
    'extend'        => array(
                        'oxoutput' => 'gn2_newsletterconnect/gn2_newsletterconnect_oxoutput',
                        'oxuser'   => 'gn2_newsletterconnect/gn2_newsletterconnect_oxuser',
                        'account_newsletter'   => 'gn2_newsletterconnect/gn2_newsletterconnect_account_newsletter',
    )
);