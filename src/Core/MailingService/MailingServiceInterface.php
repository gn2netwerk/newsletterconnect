<?php
/**
 * GN2_NewsletterConnect
 * @category GN2_NewsletterConnect
 * @package  GN2_NewsletterConnect
 * @author   gn2 netwerk <kontakt@gn2.de>
 * @license  GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version  GIT: <git_id>
 * @link     http://www.gn2-netwerk.de/
 */

namespace GN2\NewsletterConnect\Core\MailingService;

use \GN2\NewsletterConnect\Core\Mailing\MailingList;
use \GN2\NewsletterConnect\Core\Mailing\Recipient;

/**
 * MailingService_Interface
 * Should be implemented for different types of webservice.
 */
interface MailingServiceInterface
{
    /**
     * Gets current lists from the MailingService
     *
     * @abstract
     * @return array Array of MailingList Objects
     */
    public function getLists();

    /**
     * Creates a new recipient on the MailingService starts OptIn process
     *
     * @param Recipient $recipient Recipient Object
     *
     * @return void
     */
    public function optInRecipient($recipient);

    /**
     * Subscribes a recipient directly to a mailing list
     *
     * @param MailingList $list List Object
     * @param Recipient $recipient Recipient Object
     *
     * @return void
     */
    public function subscribeRecipient($list, $recipient);

    /**
     * Unsubscribes a recipient directly from a mailing list
     *
     * @param MailingList $list List Object
     * @param Recipient $recipient Recipient Object
     *
     * @return void
     */
    public function unsubscribeRecipient($list, $recipient);

    /**
     * Finds a recipient by their E-Mail address, returns null if not found
     *
     * @param string $email E-Mail Address
     *
     * @return mixed Recipient or null
     */
    public function getRecipientByEmail($email);

    /**
     * Finds a recipient by their id, returns null if not found
     *
     * @param mixed $id ID
     *
     * @return mixed Recipient or null
     */
    public function getRecipientById($id);


    /**
     * @param $listId
     * @param $recipients
     * @param $mode
     * @return mixed
     */
    public function importRecipients($listId, $recipients, $mode);
}