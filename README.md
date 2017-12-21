# gn2_newsletterconnect Installation

Systemvoraussetzungen:
    - OXID 4.7+
    - PHP 5.3+

## A) Mailingwork-Konto einrichten:
Bitte stellen Sie sicher, dass es im Mailingworkaccount ein Signupsetup mit den folgenden Feldern existiert:
    - E-Mail	
    - Anrede
    - Vorname
    - Nachname
    - Sprache

In den Berechtigungen des Mailingswork-Kontos muss bei Webservice folgendes aktiviert sein:
    - SOAP Server V3
    - JSON Rückgabe
    - XML Rückgabe


## B) gn2_newsletterconnect-Modul installieren
Falls schon vorhanden, das gn2_newsletterconnect-Modul deaktivieren und entfernen (alle Dateien in /modules/gn2_newsletterconnect löschen und vorhandene gn2_newsletterconnect OXID Moduleinträge löschen).

1. gn2_newsletterconnect herunterladen, entpacken, und in /modules/gn2_newsletterconnect hochladen.

2. Im OXID-Backend: "Erweiterungen > Module", das NewsletterConnect-Modul aktivieren.

3. Unter "Shopeinstellungen > NewsletterConnect", das Modul Konfigurieren:
    ### Beispieldaten (bitte entsprechend anpassen):
        Allgemein:
        - Mailingwork API-Base-URL: https://login.mailingwork.de/webservice/webservice/json/
        
        - Benutzername: mailingworkUser
        - Passwort:     mailingworkPasswort
        (am bestem eigenen Benutzer dafür anlegen)
        
        - ID des Hauptanmeldesetups: 2
        - ID des Hauptabmeldesetups: 1
        Hauptanmeldesetup heißt: Wenn man sich ohne Kundenaccount anmeldet. Über die Newsletter-Seite oder zum Beispiel im Footer-Formular.
        
        - ID des Anmeldesetups (Kundenaccount): 2
        - ID des Abmeldesetups (Kundenaccount): 1
        Anmeldesetup (Kundenaccount) heißt: Wenn man den Newsletter über die Adresseingabe im Checkout oder im Kundenkonto abonniert.
        
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
4. Die Produktübergabe kann mit der URL http://www.your-oxid-shop.de/modules/gn2_newsletterconnect/products.json getestet werden. Falls die URL nicht funktioniert, muss die RewriteBase in der Datei /modules/gn2_newsletterconnect/.htaccess entsprechend für Ihre Serverkonfiguration angepasst werden.
