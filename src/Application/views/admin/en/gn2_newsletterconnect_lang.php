<?php
/**
 * Gn2_NewsletterConnect
 * @category Gn2_NewsletterConnect
 * @package  Gn2_NewsletterConnect
 * @author   gn2 netwerk <kontakt@gn2.de>
 * @license  Gn2 Commercial Addon License http://www.gn2-netwerk.de/
 * @link     http://www.gn2-netwerk.de/
 */

$aLang = array(
    'charset'                                               => 'UTF-8',
    'admin_newsletterconnect'                               => "NewsletterConnect",

    'GN2_NEWSLETTERCONNECT_API_CONFIG'                      => 'API-Konfiguration',

    'GN2_NEWSLETTERCONNECT_API_BASEURL'                     => 'Mailingwork Webservice-URL',
    'GN2_NEWSLETTERCONNECT_API_BASEURL_HELP'                => 'Standard: <br>https://login.mailingwork.de/webservice/webservice/json/',
    'GN2_NEWSLETTERCONNECT_API_USERNAME'                    => 'Username',
    'GN2_NEWSLETTERCONNECT_API_USERNAME_HELP'               => 'Username to the Mailingwork account. <br>We recommend creating a separate user for the API.',
    'GN2_NEWSLETTERCONNECT_API_PASSWORD'                    => 'Password',
    'GN2_NEWSLETTERCONNECT_API_PASSWORD_HELP'               => 'Password to the Mailingwork account. <br>We recommend creating a separate user for the API.',

    'GN2_NEWSLETTERCONNECT_API_SIGNUPSETUP_GENERAL'         => 'Main opt-in setup ID',
    'GN2_NEWSLETTERCONNECT_API_SIGNUPSETUP_GENERAL_HELP'    => 'Opt-in setup: Double-OptIn. <br>Will be used in registration and standalone newsletter form.',
    'GN2_NEWSLETTERCONNECT_API_SIGNOFFSETUP_GENERAL'        => 'Main opt-out setup ID',
    'GN2_NEWSLETTERCONNECT_API_SIGNOFFSETUP_GENERAL_HELP'   => 'Opt-out setup: Single-OptOut. <br>Will be used in registration and standalone newsletter form.',
    'GN2_NEWSLETTERCONNECT_API_SIGNUPSETUP_ACCOUNT'         => 'Customer account opt-in ID',
    'GN2_NEWSLETTERCONNECT_API_SIGNUPSETUP_ACCOUNT_HELP'    => 'Opt-in setup: Single-OptIn. <br>Will be used in the customer account.',
    'GN2_NEWSLETTERCONNECT_API_SIGNOFFSETUP_ACCOUNT'        => 'Customer account opt-out ID',
    'GN2_NEWSLETTERCONNECT_API_SIGNOFFSETUP_ACCOUNT_HELP'   => 'Opt-out setup: Single-OptOut. <br>Will be used in the customer account.',

    'GN2_NEWSLETTERCONNECT_API_IPS'                         => 'Allowed IP addresses for API access',
    'GN2_NEWSLETTERCONNECT_API_IPS_HELP'                    => 'The following IP addresses are allowed to access the <strong>Voucher/Profile Manager API</strong>. <br>Please enter the IPs of the Mailingwork servers here. You will receive this list from Mailingwork. <br>One IP address per line.',

    'GN2_NEWSLETTERCONNECT_VOUCHERSERIES'                   => 'Voucher series',
    'GN2_NEWSLETTERCONNECT_VOUCHERSERIES_HELP'              => 'The following coupon series can be used for newsletter mailing. Please make sure that enough voucher codes have been generated and the voucher series is active. The voucher codes are retrieved from the Mailingwork API.',

    //export subscribers
    'GN2_NEWSLETTERCONNECT_SELECT_SUBSCRIBER_TYPE'          => 'Choose customer type',
    'GN2_NEWSLETTERCONNECT_EXPORT_BUTTON'                   => 'Export user data (in batches)',
    'GN2_NEWSLETTERCONNECT_EXPORT_BUTTON_TITLE'             => 'Subscribers will be entered into the given Mailing Works subscriber list.',
    'GN2_NEWSLETTERCONNECT_EXPORT_HEADER'                   => 'Export user data',
    'GN2_NEWSLETTERCONNECT_EXPORT_TITLE'                    => 'Export the OXID Newsletter subscribers to  Mailing Works.',
    'GN2_NEWSLETTERCONNECT_TOTAL_SUBSCRIBERS'               => 'Total amount of customer data',
    'GN2_NEWSLETTERCONNECT_OPTIN_SUBSCRIBERS'               => 'Customers with confirmed subscription',
    'GN2_NEWSLETTERCONNECT_UNCONFIRMED_SUBSCRIBERS'         => 'Customers with unconfirmed subscription',
    'GN2_NEWSLETTERCONNECT_OPTOUT_SUBSCRIBERS'              => 'Customers with cancelled subscription',
    'GN2_NEWSLETTERCONNECT_NOT_SUBSCRIBERS'                 => 'Customers without subscription',
    'GN2_NEWSLETTERCONNECT_CHECKBOX_TITLE'                  => 'add to export',
    'GN2_NEWSLETTERCONNECT_LISTID'                          => 'Subscriber List-ID',
    'GN2_NEWSLETTERCONNECT_MODE_ADD_LABEL'                  => 'Add',
    'GN2_NEWSLETTERCONNECT_MODE_ADD_DESC'                   => 'Add imported subscribers, including existing ones (duplicates).</br>(Create new subscriber IDs)',
    'GN2_NEWSLETTERCONNECT_MODE_REPLACE_LABEL'              => 'Replace',
    'GN2_NEWSLETTERCONNECT_MODE_REPLACE_DESC'               => 'Empty the list and replace it with imported subscribers.</br>(Create new subscriber IDs)',
    'GN2_NEWSLETTERCONNECT_MODE_UPDATE_LABEL'               => 'Refresh',
    'GN2_NEWSLETTERCONNECT_MODE_UPDATE_DESC'                => 'Update existing subscribers, do not add new ones.</br>(Preserve subscriber IDs)',
    'GN2_NEWSLETTERCONNECT_MODE_UPDATE_ADD_LABEL'           => 'Refresh and add (recommended)',
    'GN2_NEWSLETTERCONNECT_MODE_UPDATE_ADD_DESC'            => 'Update existing subscribers and add new ones.</br>(Preserve subscriber IDs)',
    'GN2_NEWSLETTERCONNECT_IMPORTART_LEGEND'                => 'Choose type of import',
    'GN2_NEWSLETTERCONNECT_EXPORT_OXID_STATUS'              => 'Export subscriber status',
    'GN2_NEWSLETTERCONNECT_OXID_STATUS_TITLE'               => 'The status of every subscriber will be exported. The corresponding subscriber field -login status- must be existent in Mailingworks. ',
    'GN2_NEWSLETTERCONNECT_EXPORT_CSVMETHOD_BUTTON_TITLE'   => 'Export as a CSV file. The CSV file can be imported into Mailingworks',
    'GN2_NEWSLETTERCONNECT_EXPORT_CSVMETHOD_BUTTON'         => 'Export customer data as a CSV file',
    'GN2_NEWSLETTERCONNECT_LISTID_TITLE'                    => 'The ID of the Mailing Works subscriber list. It is required to transfer in batches.',
);