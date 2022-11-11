<?php
/**
 * @copyright   (c) gn2
 * @link        https://www.gn2.de/
 */

header('Content-Type:text/plain');

echo "Gn2_NewsletterConnect API not configured correctly. Please try commenting in or changing the RewriteBase line ";
echo "in /modules/gn2/newsletterconnect/.htaccess\n\n";
echo "Possible values:\n\n";
echo "/\n";
echo "/".dirname(str_replace($_SERVER['DOCUMENT_ROOT'], '', $_SERVER['SCRIPT_FILENAME']))."\n";
