<?php
header('Content-Type:text/plain');

echo "GN2_NewsletterConnect API not configured correctly. Please try commenting in or changing the RewriteBase line ";
echo "in /modules/gn2_newsletterconnect/.htaccess\n\n";
echo "Possible values:\n\n";
echo "/\n";
echo "/".dirname(str_replace($_SERVER['DOCUMENT_ROOT'], '', $_SERVER['SCRIPT_FILENAME']))."\n";

?>