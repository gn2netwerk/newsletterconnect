<?php
/**
 * Gn2_NewsletterConnect
 * @category Gn2_NewsletterConnect
 * @package  Gn2_NewsletterConnect
 * @author   gn2 netwerk <kontakt@gn2.de>
 * @license  Gn2 Commercial Addon License http://www.gn2-netwerk.de/
 * @link     http://www.gn2-netwerk.de/
 */

header('Content-Type:text/plain');

echo "Gn2_NewsletterConnect API not configured correctly. Please try commenting in or changing the RewriteBase line ";
echo "in /modules/gn2/newsletterconnect/.htaccess\n\n";
echo "Possible values:\n\n";
echo "/\n";
echo "/".dirname(str_replace($_SERVER['DOCUMENT_ROOT'], '', $_SERVER['SCRIPT_FILENAME']))."\n";
