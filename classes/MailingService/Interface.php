<?php
/**
 * GN2_NewsletterConnect
 *
 * PHP version 5
 *
 * @category   GN2_Newsletterconnect
 * @package    GN2_Newsletterconnect
 * @subpackage Mapper
 * @author     Dave Holloway <dh@gn2-netwerk.de>
 * @license    GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version    GIT: <git_id>
 * @link       http://www.gn2-netwerk.de/
 */

/**
 * GN2_Newsletterconnect_MailingService_Interface
 * Should be extended for different types of webservice.
 *
 * @category   GN2_Newsletterconnect
 * @package    GN2_Newsletterconnect
 * @subpackage Mapper
 * @author     Dave Holloway <dh@gn2-netwerk.de>
 * @license    GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version    Release: <package_version>
 * @link       http://www.gn2-netwerk.de/
 * @abstract
 */
interface GN2_Newsletterconnect_MailingService_Interface
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
     * @param mixed                                   $listId    List Id
     * @param GN2_NewsletterConnect_Mailing_Recipient $recipient Recipient Object
     *
     * @return void
     */
    public function createRecipient($listId, $recipient);

    public function getRecipientByEmail($email);
}