<?php
/**
 * @copyright   (c) gn2
 * @link        https://www.gn2.de/
 */

namespace Gn2\NewsletterConnect\Core\Api\Mailing;

/**
 * Mailing Recipient Entity Class
 */
class Recipient
{
    /**
     * @var string ID
     */
    private $_id;

    /**
     * @var string Salutation
     */
    private $_salutation;

    /**
     * @var string Title
     */
    private $_title;

    /**
     * @var string First Name
     */
    private $_firstName;

    /**
     * @var string Last Name
     */
    private $_lastName;

    /**
     * @var string Company
     */
    private $_company;

    /**
     * @var string Street Name
     */
    private $_street;

    /**
     * @var string House Number
     */
    private $_houseNumber;

    /**
     * @var string Zip/Postal Code
     */
    private $_zipCode;

    /**
     * @var string Town/City
     */
    private $_city;

    /**
     * @var string State/Region
     */
    private $_state;

    /**
     * @var string Country
     */
    private $_country;

    /**
     * @var string E-Mail Address
     */
    private $_email;

    /**
     * @var string Telephone Prefix
     */
    private $_telPrefix;

    /**
     * @var string Telephone Number without prefix
     */
    private $_telNumber;

    /**
     * @var string Fax Prefix
     */
    private $_faxPrefix;

    /**
     * @var string Fax Number without prefix
     */
    private $_faxNumber;

    /**
     * @var string Mobile prefix
     */
    private $_mobPrefix;

    /**
     * @var string Mobile Number without prefix
     */
    private $_mobNumber;

    /**
     * @var string Voucher Number
     */
    private $_voucher;

    /**
     * @var string Language ISO
     */
    private $_language;

    /**
     * @var integer Oxid newsletter status
     */
    private $_dNewsletterStatus;

    /**
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->_language = $language;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->_language;
    }

    /**
     * @param mixed $voucher
     */
    public function setVoucher($voucher)
    {
        $this->_voucher = $voucher;
    }

    /**
     * @return mixed
     */
    public function getVoucher()
    {
        return $this->_voucher;
    }

    /**
     * Sets Id
     *
     * @param mixed $id ID
     *
     * @return void
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * Gets Id
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Sets Company
     *
     * @param string $company Company Name
     *
     * @return void
     */
    public function setCompany($company)
    {
        $this->_company = $company;
    }

    /**
     * Gets Company
     *
     * @return mixed
     */
    public function getCompany()
    {
        return $this->_company;
    }

    /**
     * Sets Country
     *
     * @param string $country Country Name
     *
     * @return void
     */
    public function setCountry($country)
    {
        $this->_country = $country;
    }

    /**
     * Gets Country
     *
     * @return mixed
     */
    public function getCountry()
    {
        return $this->_country;
    }

    /**
     * Sets E-Mail
     *
     * @param string $email E-Mail Address
     *
     * @return void
     */
    public function setEmail($email)
    {
        $this->_email = $email;
    }

    /**
     * Gets E-Mail
     *
     * @return mixed
     */
    public function getEmail()
    {
        return $this->_email;
    }

    /**
     * Sets Fax Number
     *
     * @param string $faxNumber Fax Number
     *
     * @return void
     */
    public function setFaxNumber($faxNumber)
    {
        $this->_faxNumber = $faxNumber;
    }

    /**
     * Gets Fax Number
     *
     * @return mixed
     */
    public function getFaxNumber()
    {
        return $this->_faxNumber;
    }

    /**
     * Sets Fax Prefix
     *
     * @param string $faxPrefix Fax Prefix
     *
     * @return void
     */
    public function setFaxPrefix($faxPrefix)
    {
        $this->_faxPrefix = $faxPrefix;
    }

    /**
     * Gets Fax Prefix
     *
     * @return mixed
     */
    public function getFaxPrefix()
    {
        return $this->_faxPrefix;
    }

    /**
     * Sets First Name
     *
     * @param string $firstName First Name
     *
     * @return void
     */
    public function setFirstName($firstName)
    {
        $this->_firstName = $firstName;
    }

    /**
     * Gets First Name
     *
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->_firstName;
    }

    /**
     * Sets House Number
     *
     * @param string $houseNumber House Number
     *
     * @return void
     */
    public function setHouseNumber($houseNumber)
    {
        $this->_houseNumber = $houseNumber;
    }

    /**
     * Gets House Number
     *
     * @return mixed
     */
    public function getHouseNumber()
    {
        return $this->_houseNumber;
    }

    /**
     * Sets Last Name
     *
     * @param string $lastName Last Name
     *
     * @return void
     */
    public function setLastName($lastName)
    {
        $this->_lastName = $lastName;
    }

    /**
     * Gets Last Name
     *
     * @return mixed
     */
    public function getLastName()
    {
        return $this->_lastName;
    }

    /**
     * Sets Mobile Number
     *
     * @param string $mobNumber Mobile Number
     *
     * @return void
     */
    public function setMobNumber($mobNumber)
    {
        $this->_mobNumber = $mobNumber;
    }

    /**
     * Gets Mobile Number
     *
     * @return mixed
     */
    public function getMobNumber()
    {
        return $this->_mobNumber;
    }

    /**
     * Sets Mobile Number
     *
     * @param string $mobPrefix Mobile Number Prefix
     *
     * @return void
     */
    public function setMobPrefix($mobPrefix)
    {
        $this->_mobPrefix = $mobPrefix;
    }

    /**
     * Gets Mobile Prefix
     *
     * @return mixed
     */
    public function getMobPrefix()
    {
        return $this->_mobPrefix;
    }

    /**
     * Sets Salutation
     *
     * @param string $salutation Salutation
     *
     * @return void
     */
    public function setSalutation($salutation)
    {
        $this->_salutation = $salutation;
    }

    /**
     * Gets Salutation
     *
     * @return mixed
     */
    public function getSalutation()
    {
        return $this->_salutation;
    }

    /**
     * Sets State
     *
     * @param string $state State
     *
     * @return void
     */
    public function setState($state)
    {
        $this->_state = $state;
    }

    /**
     * Gets State
     *
     * @return mixed
     */
    public function getState()
    {
        return $this->_state;
    }

    /**
     * Sets Street
     *
     * @param string $street Street
     *
     * @return void
     */
    public function setStreet($street)
    {
        $this->_street = $street;
    }

    /**
     * Gets Street
     *
     * @return mixed
     */
    public function getStreet()
    {
        return $this->_street;
    }

    /**
     * Sets Telephone Number
     *
     * @param string $telNumber Telephone Number
     *
     * @return void
     */
    public function setTelNumber($telNumber)
    {
        $this->_telNumber = $telNumber;
    }

    /**
     * Gets Telephone Number
     *
     * @return mixed
     */
    public function getTelNumber()
    {
        return $this->_telNumber;
    }

    /**
     * Sets Telephone Prefix
     *
     * @param string $telPrefix Telephone Prefix
     *
     * @return void
     */
    public function setTelPrefix($telPrefix)
    {
        $this->_telPrefix = $telPrefix;
    }

    /**
     * Gets Telephone Prefix
     *
     * @return mixed
     */
    public function getTelPrefix()
    {
        return $this->_telPrefix;
    }

    /**
     * Sets Title e.g. Dr.
     *
     * @param string $title Title
     *
     * @return void
     */
    public function setTitle($title)
    {
        $this->_title = $title;
    }

    /**
     * Gets Title
     *
     * @return mixed
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * Sets Zip Code
     *
     * @param string $zipCode Zip Code
     *
     * @return void
     */
    public function setZipCode($zipCode)
    {
        $this->_zipCode = $zipCode;
    }

    /**
     * Gets Zip Code
     *
     * @return mixed
     */
    public function getZipCode()
    {
        return $this->_zipCode;
    }

    /**
     * Sets City
     *
     * @param string $city City
     *
     * @return void
     */
    public function setCity($city)
    {
        $this->_city = $city;
    }

    /**
     * Gets City
     *
     * @return mixed
     */
    public function getCity()
    {
        return $this->_city;
    }

    /**
     * sets the oxid newsletter status
     * @return int
     */
    public function getOxidNewsletterStatus()
    {
        //Subscription status: 0 - not subscribed, 1 - subscribed, 2 - not confirmed
        //OXDBOPTIN, OXSUBSCRIBED & OXUNSUBSCRIBED
        $ret = $this->_dNewsletterStatus;
        switch ($this->_dNewsletterStatus) {
            case 0;
                $ret = 'OXNOTSUBSCRIBED';
                break;
            case 1;
                $ret = 'OXDBOPTIN';
                break;
            case 2;
                $ret = 'OXSUBSCRIBED';
                break;
            case 3;
                $ret = 'OXUNSUBSCRIBED';
                break;
        }
        return $ret;
    }


    /**
     * sets the oxid newsletter status
     * @param $dStatus int
     */
    public function setOxidNewsletterStatus($dStatus)
    {
        $this->_dNewsletterStatus = $dStatus;
    }

}
