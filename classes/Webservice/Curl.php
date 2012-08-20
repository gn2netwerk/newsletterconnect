<?php
/**
 * GN2_NewsletterConnect
 *
 * PHP version 5
 *
 * @category   GN2_Newsletterconnect
 * @package    GN2_Newsletterconnect
 * @subpackage Output
 * @author     Dave Holloway <dh@gn2-netwerk.de>
 * @license    GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version    GIT: <git_id>
 * @link       http://www.gn2-netwerk.de/
 */

/**
 * GN2_Newsletterconnect_Webservice_Curl
 *
 * @category   GN2_Newsletterconnect
 * @package    GN2_Newsletterconnect
 * @subpackage Output
 * @author     Dave Holloway <dh@gn2-netwerk.de>
 * @license    GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version    Release: <package_version>
 * @link       http://www.gn2-netwerk.de/
 */
abstract class GN2_Newsletterconnect_Webservice_Curl
    extends GN2_Newsletterconnect_Webservice_Abstract
{
    private $_returnTransfer = true;
    private $_post = false;
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

        echo $this->_url.'?'.urldecode($query).'<br>';

        if ($this->_post) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        }
        $result = curl_exec($ch);

        $error = curl_error($ch);
        print_r($error);
        $result = $result;
        print_r($result);
        echo '<hr>';
        return $result;
    }


}