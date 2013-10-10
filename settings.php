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

self::$config = array(
    'mailingService' => 'Mailingwork',

    'service_Mailingwork' => array(
        'api_baseurl'      => 'https://login.mailingwork.de/webservice/webservice/json/',
        'api_username'     => 'gn2test',
        'api_password'     => 'z7Lgssia3g$qg',
        'api_signupsetup'  => '1', // ID des Anmeldesetups
    )
);
