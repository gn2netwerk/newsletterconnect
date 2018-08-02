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

/**
 * Class to handle MailingService-specific exceptions
 */
class GN2_NewsletterConnect_Exception_MailingService extends Exception
{

    /**
     * @var array of observer objects
     */
    private $_observers;

    /**
     * Unused
     *
     * @param object $observer Unused
     *
     * @return void
     */
    public function addObserver($observer)
    {
        if (is_object($observer)) {
            if (method_exists($observer, 'notify')) {
                if (!is_array($this->_observers)) {
                    $this->_observers = array();
                }
                $this->_observers[] = $observer;
            }
        }
    }
}