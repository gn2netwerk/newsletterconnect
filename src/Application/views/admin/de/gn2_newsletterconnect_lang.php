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
    'GN2_NEWSLETTERCONNECT_API_USERNAME'                    => 'Benutzername',
    'GN2_NEWSLETTERCONNECT_API_USERNAME_HELP'               => 'Benutzername zum Mailingwork-Account. <br>Wir empfehlen einen eigenen Benutzer für die API anzulegen.',
    'GN2_NEWSLETTERCONNECT_API_PASSWORD'                    => 'Passwort',
    'GN2_NEWSLETTERCONNECT_API_PASSWORD_HELP'               => 'Passwort zum Mailingwork-Account. <br>Wir empfehlen einen eigenen Benutzer für die API anzulegen.',

    'GN2_NEWSLETTERCONNECT_API_SIGNUPSETUP_GENERAL'         => 'ID des Hauptanmeldesetups',
    'GN2_NEWSLETTERCONNECT_API_SIGNUPSETUP_GENERAL_HELP'    => 'Anmeldesetup: Double-OptIn. <br>Wird verwendet in der Registrierung und im Standalone-Newsletterformular.',
    'GN2_NEWSLETTERCONNECT_API_SIGNOFFSETUP_GENERAL'        => 'ID des Hauptabmeldesetups',
    'GN2_NEWSLETTERCONNECT_API_SIGNOFFSETUP_GENERAL_HELP'   => 'Abmeldesetup: Single-OptOut. <br>Wird verwendet in der Registrierung und im Standalone-Newsletterformular.',
    'GN2_NEWSLETTERCONNECT_API_SIGNUPSETUP_ACCOUNT'         => 'ID des Anmeldesetups (Kundenaccount)',
    'GN2_NEWSLETTERCONNECT_API_SIGNUPSETUP_ACCOUNT_HELP'    => 'Anmeldesetup: Single-OptIn. <br>Wird verwendet im Kundenaccount.',
    'GN2_NEWSLETTERCONNECT_API_SIGNOFFSETUP_ACCOUNT'        => 'ID des Abmeldesetups (Kundenaccount)',
    'GN2_NEWSLETTERCONNECT_API_SIGNOFFSETUP_ACCOUNT_HELP'   => 'Abmeldesetup: Single-OptOut. <br>Wird verwendet im Kundenaccount.',

    'GN2_NEWSLETTERCONNECT_API_IPS'                         => 'Erlaubte IP-Adressen für API-Zugriff',
    'GN2_NEWSLETTERCONNECT_API_IPS_HELP'                    => 'Die folgenden IP-Adressen dürfen auf die <strong>Gutschein/Profilmanager-API</strong> zugreifen. <br>Bitte hier die IPs der Mailingwork-Server eintragen. Diese Liste erhalten Sie von Mailingwork. <br>Eine IP-Adresse pro Zeile.',

    'GN2_NEWSLETTERCONNECT_VOUCHERSERIES'                   => 'Gutscheinserie',
    'GN2_NEWSLETTERCONNECT_VOUCHERSERIES_HELP'              => 'Die folgende Gutscheinserie kann zum Newsletterversand verwendet werden. Bitte achten Sie darauf, dass ausreichend Gutschein-Codes generiert wurden und die Gutscheinserie aktiv ist. Die Gutschein-Codes werden von der Mailingwork-API abgerufen.',

    //export subscribers
    'GN2_NEWSLETTERCONNECT_SELECT_SUBSCRIBER_TYPE'          => 'Abonnenten auswählen',
    'GN2_NEWSLETTERCONNECT_EXPORT_BUTTON'                   => 'Export Kundendaten (Paketweise)',
    'GN2_NEWSLETTERCONNECT_EXPORT_BUTTON_TITLE'             => 'Abonnenten werden direkt in der eingegebenen Mailing-Works-Abonnentenliste eingetragen.',
    'GN2_NEWSLETTERCONNECT_EXPORT_HEADER'                   => 'Export Kundendaten',
    'GN2_NEWSLETTERCONNECT_EXPORT_TITLE'                    => 'Exportieren Sie die OXID Newsletter-Abonnenten zur Mailing-Works.',
    'GN2_NEWSLETTERCONNECT_TOTAL_SUBSCRIBERS'               => 'Gesamtzahl der Kundendaten',
    'GN2_NEWSLETTERCONNECT_OPTIN_SUBSCRIBERS'               => 'Kunden mit best&auml;tigtem Abonnement',
    'GN2_NEWSLETTERCONNECT_UNCONFIRMED_SUBSCRIBERS'         => 'Kunden mit unbest&auml;tigtem Abonnement',
    'GN2_NEWSLETTERCONNECT_OPTOUT_SUBSCRIBERS'              => 'Kunden mit abgemeldetem Abonnement',
    'GN2_NEWSLETTERCONNECT_NOT_SUBSCRIBERS'                 => 'Kunden ohne Abonnement',
    'GN2_NEWSLETTERCONNECT_CHECKBOX_TITLE'                  => 'zum Export hinzufügen',
    'GN2_NEWSLETTERCONNECT_LISTID'                          => 'Abonnenten List-ID',
    'GN2_NEWSLETTERCONNECT_MODE_ADD_LABEL'                  => 'Hinzuf&uuml;gen',
    'GN2_NEWSLETTERCONNECT_MODE_ADD_DESC'                   => 'Importierte Abonnenten hinzuf&uuml;gen, auch bereits vorhandene (Dubletten).</br>(Abonnenten-IDs neu anlegen)',
    'GN2_NEWSLETTERCONNECT_MODE_REPLACE_LABEL'              => 'Ersetzen',
    'GN2_NEWSLETTERCONNECT_MODE_REPLACE_DESC'               => 'Liste leeren und durch importierte Abonnenten ersetzen.</br>(Abonnenten-IDs neu anlegen)',
    'GN2_NEWSLETTERCONNECT_MODE_UPDATE_LABEL'               => 'Aktualisieren',
    'GN2_NEWSLETTERCONNECT_MODE_UPDATE_DESC'                => 'Vorhandene Abonnenten aktualisieren, keine neuen hinzuf&uuml;gen.</br>(Abonnenten-IDs erhalten)',
    'GN2_NEWSLETTERCONNECT_MODE_UPDATE_ADD_LABEL'           => 'Aktualisieren und hinzuf&uuml;gen (empfohlen)',
    'GN2_NEWSLETTERCONNECT_MODE_UPDATE_ADD_DESC'            => 'Vorhandene Abonnenten aktualisieren und neue hinzuf&uuml;gen.</br>(Abonnenten-IDs erhalten)',
    'GN2_NEWSLETTERCONNECT_IMPORTART_LEGEND'                => 'Importart ausw&auml;hlen',
    'GN2_NEWSLETTERCONNECT_EXPORT_OXID_STATUS'              => 'Abonnement-Status exportieren',
    'GN2_NEWSLETTERCONNECT_OXID_STATUS_TITLE'               => 'Das Status zu jedem Benutzer wird exportiert. Dafür muss aber das entsprechende Abonnentenfeld -Anmeldestatus- in Mailworks vorhanden sein. ',
    'GN2_NEWSLETTERCONNECT_EXPORT_CSVMETHOD_BUTTON_TITLE'   => 'Export als CSV-Datei. Die CSV-Datei kann wiederum im Mailworks importiert werden',
    'GN2_NEWSLETTERCONNECT_EXPORT_CSVMETHOD_BUTTON'         => 'Export Kundendaten als CSV-Datei',
    'GN2_NEWSLETTERCONNECT_LISTID_TITLE'                    =>  'Die ID der Mailing-Works-Abonnentenliste. Diese müssen Sie eingeben, wenn Sie paketweise übertragen wollen.',
);