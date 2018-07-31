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
 * Curl WebService Implementation
 *
 * @category   GN2_NewsletterConnect
 * @package    GN2_NewsletterConnect
 * @subpackage WebService
 * @author     Dave Holloway <dh@gn2-netwerk.de>
 * @license    GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version    Release: <package_version>
 * @link       http://www.gn2-netwerk.de/
 */
abstract class GN2_NewsletterConnect_WebService_Curl
    extends GN2_NewsletterConnect_WebService_Abstract
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


}