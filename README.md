# gn2/newsletterconnect Installation

Systemvoraussetzungen:
    - OXID 6.0+
    - PHP 7.0+

## A) Mailingwork-Konto einrichten:
Bitte stellen Sie sicher, dass in Ihrem Mailingwork-Account zwei Anmeldesetups existieren. Ein Setup mit Double Opt-In (Hauptanmeldesetup) und ein Setup mit Single Opt-In (Kundenaccount).
Beide Anmeldesetups sollten auf die selbe Abonnentenliste zeigen und folgende Felder enthalten:
    - E-Mail	
    - Anrede
    - Vorname
    - Nachname
    - Sprache
    - Anmeldestatus (optional)

Zudem benötigen Sie mindestens ein Abmeldesetup mit Single Opt-Out, welches auf die gleiche Abonnentenliste zeigt.

In den Berechtigungen des Mailingswork-Kontos muss bei Webservice folgendes aktiviert sein:
    - SOAP Server V3
    - JSON Rückgabe
    - XML Rückgabe

Wir empfehlen zusätzlich einen eigenen Mailingwork-Nutzer für die API anzulegen.


## B) gn2/newsletterconnect-Modul installieren

1. OPTIONAL: Vorheriges Modul entfernen
Falls Sie bereits ein älteres Newsletterconnect-Modul installiert haben, dann deaktivieren Sie bitte zuerst das Modul.

   Sollte Ihr aktuelles Modul *älter als Version 3.0.0* sein, dann führen Sie nun folgende Composer-Befehle aus:
   * `composer remove gn2/newsletterconnect`
   * `composer config --unset repositories.gn2/newsletterconnect`
    
   Entfernen Sie nun folgendes Verzeichnis aus Ihrem Shop (falls vorhanden):
   * `/packages/gn2/newsletterconnect/`

2. Führen Sie folgenden Befehl aus, um das neueste Modul zu installieren:
   * `composer require gn2/newsletterconnect`
   * `composer update`

3. Aktivieren Sie das Modul im OXID-Backend unter "Erweiterungen > Module".

4. Leeren Sie nun den Cache des Shops.

5. Nun können Sie unter "Shopeinstellungen > NewsletterConnect", das Modul konfigurieren.
   * Mailingwork Webservice-URL: https://login.mailingwork.de/webservice/webservice/json/
   * Benutzername: Benutzer zum Mailingwork-Account
   * Passwort:     Passwort zum Mailingwork-Account

   Wie bereits erwähnt, empfehlen wir eigene Benutzerdaten für Newsletterconnect.

   * ID des Hauptanmeldesetups: Dieses Anmeldesetup wird in der Registrierung und im Standalone-Newsletterformular verwendet. Wir empfehlen ein Double Opt-In Setup.
   * ID des Hauptabmeldesetups: Dieses Abmeldesetup wird in der Registrierung und im Standalone-Newsletterformular verwendet. Wir empfehlen ein Single Opt-Out Setup.
   * ID des Anmeldesetups (Kundenaccount): Dieses Anmeldesetup wird im Kundenaccount verwendet. Wir empfehlen ein Single Opt-In Setup.
   * ID des Abmeldesetups (Kundenaccount): Dieses Anmeldesetup wird im Kundenaccount verwendet. Wir empfehlen ein Single Opt-Out Setup. Bestenfalls verwenden Sie das selbe Setup wie für die Hauptabmeldung, sofern Sie mit der selben Abonnentenliste arbeiten.

Mit den 2 Setups kann man die Kunden theoretisch unterschiedlich gruppieren, z.B. für einen Rabatt-Newsletter nur für Registrierte Shopkunden. Braucht man das nicht, dann für beide das selbe Anmeldesetup verwenden.


## C) OPTIONAL: Gutscheincodeerweiterung
Falls Mailingwork Gutscheincodes vom Shop holen soll, dann führen Sie bitte die folgenden Schritte durch:

1. Eine Gutscheinserie anlegen: "Shopeinstellungen > Gutscheinserien".
2. Die gewünschte verfügbare Menge von Gutscheincodes generieren.
3. Die API-Konfiguration ausfüllen (siehe Punkt "E").
4. Unter "Shopeinstellungen > NewsletterConnect", die neue Gutscheinserie auswählen.
5. Die gültigen Gutscheincodes Ihrer Gutscheinserie können nun mit der folgenden URL abgerufen werden (diese URL bitte Mailingwork mitteilen):
   http://www.ihr-oxid-shop.de/?mos_api=1&mode=getVoucher


## D) OPTIONAL: Unterstützung von Profilmanageränderungen
1. Die API-Konfiguration ausfüllen (siehe Punkt "E")
2. Die erlaubten IP-Adressen können nun Profiländerungen mit der folgenden URL vornehmen (diese URL bitte Mailingwork mitteilen):
   http://www.ihr-oxid-shop.de/?mos_api=1&mode=updateUser&email=bodo@mail.gn2-dev.de&firstname=Bodo&lastname=Ballerböhme
   

## E) OPTIONAL: API-Freischaltung
Tagen Sie unter "Shopeinstellungen > NewsletterConnect" alle IPs der Mailingworkserver und, falls notwendig, Ihre eigene IP ein:
Pro Zeile eine IP-Adresse:
    127.0.0.0
    212.50.2.234
    ...
    ...

 
## F) OPTIONAL: Produktübergabe an Mailingwork
Das gn2_newsletterconnnect-Modul unterstützt die Übergabe von OXID-Produkten an Mailingwork. 
Diese Funktionalität erfordert eine Freischaltung der OXID-Erweiterung im Mailingwork-Account. Wenden Sie sich hierzu an Mailingwork.

1. Im OXID-Backend unter "Benutzer verwalten > Benutzergruppen" eine neue Gruppe namens "Newsletter Admin" anlegen.
2. Unter "Benutzer verwalten > Benutzer" einen neuen Benutzer anlegen und diesen Benutzer der Benutzergruppe "Newsletter Admin" zuweisen.
3. Nachdem Mailingwork die Oxid-Erweiterung freigeschaltet hat, können Sie unter "Extras > Schnittstellen > OXID" nun die Zugangsdaten und die Shop-URL zu ihrem Shop eintragen.
4. Die Produktübergabe kann mit der URL http://www.ihr-oxid-shop.de/modules/gn2/newsletterconnect/products.json getestet werden. 
   Falls die URL nicht funktioniert, überprüfen Sie bitte ob die .htaccess Datei unter /source/modules/gn2_newsletterconnect/.htaccess vorhanden ist. 
   Prüfen Sie auch, ob die RewriteBase der .htaccess entsprechend für Ihre Serverkonfiguration angepasst werden muss.