<?php
/**
 * GN2_NewsletterConnect
 *
 * PHP version 5
 *
 * @category   GN2_Newsletterconnect
 * @package    GN2_Newsletterconnect
 * @subpackage Mailing
 * @author     Dave Holloway <dh@gn2-netwerk.de>
 * @license    GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version    GIT: <git_id>
 * @link       http://www.gn2-netwerk.de/
 */

/**
 * GN2_Newsletterconnect_Mailing_List
 *
 * @category   GN2_Newsletterconnect
 * @package    GN2_Newsletterconnect
 * @subpackage Mailing
 * @author     Dave Holloway <dh@gn2-netwerk.de>
 * @license    GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version    Release: <package_version>
 * @link       http://www.gn2-netwerk.de/
 */
class GN2_Newsletterconnect_Mailing_List
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