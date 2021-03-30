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

namespace GN2\NewsletterConnect\Core\WebService;

/**
 * Abstract WebService class.
 * Should be extended for different types of WebService.
 */
abstract class WebServiceAbstract
{
    /**
     * @var array $_params Parameters to be sent to the webservice
     */
    protected $_params = array();

    /**
     * @var array $_config Configuration array
     */
    protected $_config = array();

    /**
     * Sets configuration settings and starts init()
     *
     * @param array $config Configuration array
     *
     * @return void
     */
    public function __construct($config = array())
    {
        if (is_array($config)) {
            $this->_config = $config;
        }
        $this->init();
    }

    /**
     * General initialization function to cleanup/prepare the params array
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