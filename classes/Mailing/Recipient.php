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
 * GN2_Newsletterconnect_Mailing_Recipient
 *
 * @category   GN2_Newsletterconnect
 * @package    GN2_Newsletterconnect
 * @subpackage Mailing
 * @author     Dave Holloway <dh@gn2-netwerk.de>
 * @license    GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version    Release: <package_version>
 * @link       http://www.gn2-netwerk.de/
 */

class GN2_NewsletterConnect_Mailing_Recipient
{
    private $_salutation;
    private $_title;
    private $_firstName;
    private $_lastName;
    private $_company;
    private $_street;
    private $_houseNumber;
    private $_zipCode;
    private $_city;
    private $_state;
    private $_country;
    private $_email;
    private $_telPrefix;
    private $_telNumber;
    private $_faxPrefix;
    private $_faxNumber;
    private $_mobPrefix;
    private $_mobNumber;

    public function setCompany($company)
    {
        $this->_company = $company;
    }

    public function getCompany()
    {
        return $this->_company;
    }

    public function setCountry($country)
    {
        $this->_country = $country;
    }

    public function getCountry()
    {
        return $this->_country;
    }

    public function setEmail($email)
    {
        $this->_email = $email;
    }

    public function getEmail()
    {
        return $this->_email;
    }

    public function setFaxNumber($faxNumber)
    {
        $this->_faxNumber = $faxNumber;
    }

    public function getFaxNumber()
    {
        return $this->_faxNumber;
    }

    public function setFaxPrefix($faxPrefix)
    {
        $this->_faxPrefix = $faxPrefix;
    }

    public function getFaxPrefix()
    {
        return $this->_faxPrefix;
    }

    public function setFirstName($firstName)
    {
        $this->_firstName = $firstName;
    }

    public function getFirstName()
    {
        return $this->_firstName;
    }

    public function setHouseNumber($houseNumber)
    {
        $this->_houseNumber = $houseNumber;
    }

    public function getHouseNumber()
    {
        return $this->_houseNumber;
    }

    public function setLastName($lastName)
    {
        $this->_lastName = $lastName;
    }

    public function getLastName()
    {
        return $this->_lastName;
    }

    public function setMobNumber($mobNumber)
    {
        $this->_mobNumber = $mobNumber;
    }

    public function getMobNumber()
    {
        return $this->_mobNumber;
    }

    public function setMobPrefix($mobPrefix)
    {
        $this->_mobPrefix = $mobPrefix;
    }

    public function getMobPrefix()
    {
        return $this->_mobPrefix;
    }

    public function setSalutation($salutation)
    {
        $this->_salutation = $salutation;
    }

    public function getSalutation()
    {
        return $this->_salutation;
    }

    public function setState($state)
    {
        $this->_state = $state;
    }

    public function getState()
    {
        return $this->_state;
    }

    public function setStreet($street)
    {
        $this->_street = $street;
    }

    public function getStreet()
    {
        return $this->_street;
    }

    public function setTelNumber($telNumber)
    {
        $this->_telNumber = $telNumber;
    }

    public function getTelNumber()
    {
        return $this->_telNumber;
    }

    public function setTelPrefix($telPrefix)
    {
        $this->_telPrefix = $telPrefix;
    }

    public function getTelPrefix()
    {
        return $this->_telPrefix;
    }

    public function setTitle($title)
    {
        $this->_title = $title;
    }

    public function getTitle()
    {
        return $this->_title;
    }

    public function setZipCode($zipCode)
    {
        $this->_zipCode = $zipCode;
    }

    public function getZipCode()
    {
        return $this->_zipCode;
    }

    public function setCity($city)
    {
        $this->_city = $city;
    }

    public function getCity()
    {
        return $this->_city;
    }
}

?>