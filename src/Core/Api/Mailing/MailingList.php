<?php
/**
 * Gn2_NewsletterConnect
 * @category Gn2_NewsletterConnect
 * @package  Gn2_NewsletterConnect
 * @author   gn2 netwerk <kontakt@gn2.de>
 * @license  Gn2 Commercial Addon License http://www.gn2-netwerk.de/
 * @link     http://www.gn2-netwerk.de/
 */

namespace Gn2\NewsletterConnect\Core\Api\Mailing;

/**
 * Mailing List Entity Class
 */
class MailingList
{
    /**
     * @var List identification ID;
     */
    private $_id;

    /**
     * @var Name of list
     */
    private $_name;

    /**
     * @var Description of list
     */
    private $_desc;

    /**
     * Set the ID
     *
     * @param mixed $id Id
     *
     * @return void
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * Get the list ID
     * Can be a string or an integer, depending on system
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Sets list description
     *
     * @param string $desc Description
     *
     * @return void
     */
    public function setDesc($desc)
    {
        $this->_desc = $desc;
    }

    /**
     * Returns the description
     *
     * @return string
     */
    public function getDesc()
    {
        return $this->_desc;
    }

    /**
     * Set the list name
     *
     * @param string $name Name of list
     *
     * @return void
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * Get name of list
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }
}