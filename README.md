# gn2/newsletterconnect Installation

Systemvoraussetzungen:
    - OXID 6.0+
    - PHP 7.0+

## A) Mailingwork-Konto einrichten:
Bitte stellen Sie sicher, dass es im Mailingworkaccount ein Signupsetup mit den folgenden Feldern existiert:
    - E-Mail	
    - Anrede
    - Vorname
    - Nachname
    - Sprache
    - Anmeldestatus (optional)

In den Berechtigungen des Mailingswork-Kontos muss bei Webservice folgendes aktiviert sein:
    - SOAP Server V3
    - JSON Rückgabe
    - XML Rückgabe


## B) gn2/newsletterconnect-Modul installieren
Falls schon vorhanden, bitte das vorherige gn2_newsletterconnect-Modul deaktivieren und aus dem Modules-Verzeichnis entfernen.

1. Legen Sie folgende Verzeichnis-Struktur im **Stammverzeichnis** des Shops an:
   `/packages/gn2/newsletterconnect/`
   _Hinweis: Das **Stammverzeichnis** ist die oberste Ebene des Shops. Hier befinden sich die Verzeichnisse source und vendor und auch die Datei composer.json._

2. Kopieren Sie alle Dateien von gn2_newsletterconnect in das neu angelegte Verzeichnis.

3. Führen Sie folgende Befehle über die Kommandozeile (innerhalb ihres Stammverzeichnisses) aus:
   * `composer config repositories.gn2/newsletterconnect path ./packages/gn2/newsletterconnect`
   * `composer require gn2/newsletterconnect:dev-master`
   * `composer update`

4. Im OXID-Backend: "Erweiterungen > Module", das NewsletterConnect-Modul aktivieren.

5. Unter "Shopeinstellungen > NewsletterConnect", das Modul Konfigurieren:
   **Beispieldaten! Bitte entsprechend anpassen!**

   Allgemein (am besten einen eigenen Benutzer für Newsletterconnect anlegen):

   - Mailingwork API-Base-URL: https://login.mailingwork.de/webservice/webservice/json/
   - Benutzername: mailingworkUser
   - Passwort:     mailingworkPasswort

   Hauptanmeldesetup (Wenn man sich ohne Kundenaccount anmeldet. Über die Newsletter-Seite oder zum Beispiel im Footer-Formular. Im Normalfall wird hier für die Anmeldung ein Double-Opt-In verwendet):

   - ID des Hauptanmeldesetups: 2
   - ID des Hauptabmeldesetups: 1

   Anmeldesetup (Kundenaccount) (Wenn man den Newsletter im 1. Bestellschritt (Adresseingabe) oder im Kundenkonto abonniert. Hier kann für die Anmeldung ein einfaches Opt-In verwendet werden):

   - ID des Anmeldesetups (Kundenaccount): 2
   - ID des Abmeldesetups (Kundenaccount): 1


Mit den 2 Setups kann man die Kunden theoretisch unterschiedlich gruppieren, z.B. für einen Rabatt-Newsletter nur für Registrierte Shopkunden. Braucht man das nicht, dann für beide das selbe Anmeldesetup verwenden.

## C) OPTIONAL: Gutscheincodeerweiterung
Falls Mailingwork Gutscheincodes von dem Shop holen soll, müssen die folgenden Schritte durchgeführt werden.

1. Eine Gutscheinserie anlegen: "Shopeinstellungen > Gutscheinserien".
2. Die gewünschte verfügbare Menge von Gutscheincodes generieren.
3. die API-Konfiguration ausfüllen (siehe Punkt "E").
4. Unter "Shopeinstellungen > NewsletterConnect", die neue Gutscheinserie auswählen.
5. gültige Gutscheine können nun mit der folgenden URL abgerufen werden (diese URL bitte Mailingwork mitteilen): 
http://www.your-oxid-shop.de/?mos_api=1&mode=getVoucher


## D) OPTIONAL: Unterstützung von Profilmanageränderungen
1. die API-Konfiguration ausfüllen (siehe Punkt "E")
2. Die erlaubten IP-Adressen können nun Profiländerungen mit der folgenden URL vornehmen (diese URL bitte Mailingwork mitteilen): http://www.your-oxid-shop.de/?mos_api=1&mode=updateUser&email=bodo@mail.gn2-dev.de&firstname=Bodo&lastname=Ballerböhme

## E) OPTIONAL: API-Freischaltung
1. Unter "Shopeinstellungen > NewsletterConnect", die API-Konfiguration eintragen:
       - IP-Adressen, die die Mailingwork-API aufrufen dürfen.
           Bitte hier die IPs der Mailingworkserver und, falls notwendig, Ihre eigene IP eintragen.
           Diese Liste erhalten Sie von Mailingwork.
            z.B. 127.0.0.0
                 212.50.2.234
                 ...
                 ...
                  
## F) OPTIONAL: Produktübergabe an Mailingwork
Das gn2_newsletterconnnect-Modul unterstützt die Übergabe von OXID-Produkten an Mailingwork bzw. bietet die Möglichkeit, dass Mailingwork Produktdaten auslesen kann. Diese Funktionalität erfordert eine Freischaltung der OXID-Erweiterung im Mailingwork-Account.

1. Im OXID-Backend unter "Benutzer verwalten > Benutzergruppen" eine neue Gruppe "Newsletter Admin" anlegen.
2. Unter "Benutzer verwalten > Benutzer" einen neuen Benutzer anlegen und diesen Benutzer der neuen Gruppe zuweisen.
3. Die Zugangsdaten des neuen Benutzers und die Shop-URL in den OXID-Einstellungen im Mailingworkaccount einpflegen ("Extras > Schnittstellen > OXID").
4. Die Produktübergabe kann mit der URL http://www.your-oxid-shop.de/modules/gn2/newsletterconnect/products.json getestet werden. Falls die URL nicht funktioniert, muss die RewriteBase in der Datei /modules/gn2_newsletterconnect/.htaccess entsprechend für Ihre Serverkonfiguration angepasst werden.
