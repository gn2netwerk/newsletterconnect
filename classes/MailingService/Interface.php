<?php
/**
 * GN2_NewsletterConnect
 *
 * PHP version 5
 *
 * @category   GN2_NewsletterConnect
 * @package    GN2_NewsletterConnect
 * @subpackage MailingService
 * @author     Dave Holloway <dh@gn2-netwerk.de>
 * @license    GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version    GIT: <git_id>
 * @link       http://www.gn2-netwerk.de/
 */

/**
 * MailingService_Interface
 * Should be implemented for different types of webservice.
 *
 * @category   GN2_NewsletterConnect
 * @package    GN2_NewsletterConnect
 * @subpackage MailingService
 * @author     Dave Holloway <dh@gn2-netwerk.de>
 * @license    GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version    Release: <package_version>
 * @link       http://www.gn2-netwerk.de/
 * @abstract
 */
interface GN2_NewsletterConnect_MailingService_Interface
{
    /**
     * Gets current lists from the MailingService
     *
     * @abstract
     * @return array Array of GN2_NewsletterConnect_Mailing_List Objects
     */
    public function getLists();

    /**
     * Creates a new list on the MailingService
     *
     * @param string $listName List Name
     *
     * @abstract
     * @return mixed
     */
    public function createList($listName);

    /**
     * Creates a new recipient on the MailingService
     *
     * @param GN2_NewsletterConnect_Mailing_List      $list      List Object
     * @param GN2_NewsletterConnect_Mailing_Recipient $recipient Recipient Object
     *
     * @return void
     */
    public function optInRecipient($list, $recipient);

    public function subscribeRecipient($list, $recipient);

    public function unsubscribeRecipient($list, $recipient);

    public function getRecipientByEmail($email);
}