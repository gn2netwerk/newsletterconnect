gn2 :: NewsletterConnect - für OXID 4.7+
========================================

Neuinstallation/Update
----------------------

1. Die Moduldateien in den Ordner modules/gn2_newsletterconnect hochladen.
2. Das gn2_newsletterconnect-Modul aktivieren.
3. Alle Cachedateien im Ordner tmp/ löschen.

Konfiguration
-------------

Das Modul kann unter Shopeinstellungen > NewsletterConnect konfiguriert werden.

API-Base-URL: https://login.mailingwork.de/webservice/webservice/json/

Gutscheinübertragung
--------------------

gn2_newsletterconnect kann bei Neuanmeldungen einen verfügbaren OXID-Gutschein auslesen und 
an Mailingwork als Custom-Field übertragen. Falls Sie diese Funktionalität benutzen möchten, 
muss in der Abonnentenliste und im Anmeldesetup das Feld "Gutschein" existieren.
