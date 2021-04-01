<?php
/**
 * Gn2_NewsletterConnect
 * @category Gn2_NewsletterConnect
 * @package  Gn2_NewsletterConnect
 * @author   gn2 netwerk <kontakt@gn2.de>
 * @license  Gn2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version  GIT: <git_id>
 * @link     http://www.gn2-netwerk.de/
 */

namespace Gn2\NewsletterConnect\Api\WebService;

/**
 * Curl WebService Implementation
 */
abstract class Curl
{
    /**
     * @var bool Curl Parameter to return response from server or not
     */
    private $_returnTransfer = true;

    /**
     * @var bool Curl Parameter GET/POST
     */
    private $_post = false;

    /**
     * @var null URL to the WebService
     */
    private $_url = null;

    /**
     * @var array $_params Parameters to be sent to the webservice
     */
    protected $_params = array();

    /**
     * @var array $_config Configuration array
     */
    protected $_config = array();

    /**
     * Starts init()
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * Sets the curl mode to post or get
     *
     * @param bool $post True/False
     *
     * @return void
     */
    protected function setPost($post = false)
    {
        if ($post === true) {
            $this->_post = true;
        } else {
            $this->_post = false;
        }
    }

    /**
     * Sets the API-Url
     *
     * @param string $url API-Url
     *
     * @return void
     */
    final public function setUrl($url)
    {
        $this->_url = $url;
    }

    /**
     * Contacts the webservice, sets parameters and returns response
     *
     * @return string API-Response
     */
    final public function getResponse()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->_url);
        if ($this->_returnTransfer) {
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, $this->_returnTransfer);
        }

        $query = http_build_query($this->_params);
        //echo $this->_url."&".$query."<br><br>";

        if ($this->_post) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        }
        $result = curl_exec($ch);
        //echo $this->_url.'<br>';
        //echo $result.'<br><hr>';
        //$error = curl_error($ch);
        return $result;
    }

    /**
     * General initialization function to cleanup/prepare the params array
     * Sets configuration settings
     *
     * @abstract
     * @return void
     */
    abstract public function init();

    /**
     * Adds a parameter to the webservice parameter list
     *
     * @param string $key Key
     * @param mixed $value Value
     *
     * @return void
     */
    public function addParam($key, $value)
    {
        if (!is_null($key)) {
            if (is_null($value) && array_key_exists($key, $this->_params)) {
                /* Unset */
                $this->removeParam($key);
            } elseif (!is_null($value)) {
                /* Set */
                $this->_params[$key] = $value;
            }
        }
    }

    /**
     * Resets the parameter list
     *
     * @return void
     */
    public function resetParams()
    {
        $this->_params = array();
    }

    /**
     * Removes a parameter from the webservice parameter list
     *
     * @param string $key Key
     *
     * @return void
     */
    public function removeParam($key)
    {
        if (array_key_exists($key, $this->_params)) {
            unset($this->_params[$key]);
        }
    }

}