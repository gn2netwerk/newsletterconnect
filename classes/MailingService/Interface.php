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
     * Creates a new recipient on the MailingService starts OptIn process
     *
     * @param GN2_NewsletterConnect_Mailing_Recipient $recipient Recipient Object
     *
     * @return void
     */
    public function optInRecipient($recipient);

    /**
     * Subscribes a recipient directly to a mailing list
     *
     * @param GN2_NewsletterConnect_Mailing_List      $list      List Object
     * @param GN2_NewsletterConnect_Mailing_Recipient $recipient Recipient Object
     *
     * @return void
     */
    public function subscribeRecipient($list, $recipient);

    /**
     * Unsubscribes a recipient directly from a mailing list
     *
     * @param GN2_NewsletterConnect_Mailing_List      $list      List Object
     * @param GN2_NewsletterConnect_Mailing_Recipient $recipient Recipient Object
     *
     * @return void
     */
    public function unsubscribeRecipient($list, $recipient);

    /**
     * Finds a recipient by their E-Mail address, returns null if not found
     *
     * @param string $email E-Mail Address
     *
     * @return mixed GN2_NewsletterConnect_Mailing_Recipient or null
     */
    public function getRecipientByEmail($email);

    /**
     * Finds a recipient by their id, returns null if not found
     *
     * @param mixed $id ID
     *
     * @return mixed GN2_NewsletterConnect_Mailing_Recipient or null
     */
    public function getRecipientById($id);

    
    public function importRecipients($listId, $recipients, $mode);
}