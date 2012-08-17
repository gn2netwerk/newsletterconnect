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
class GN2_Newsletterconnect_Webservice_Curl
    extends GN2_Newsletterconnect_Webservice_Abstract
{
    private $_returnTransfer = true;
    private $_post = false;

    protected function _setPost($post = false)
    {
        if ($post === true) {
            $this->_post = true;
        } else {
            $this->_post = false;
        }
    }

    protected function send()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->_url);
        if ($this->_returnTransfer) {
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, $this->_returnTransfer);
        }
        if ($this->_mode == 'post') {
            curl_setopt($ch, CURLOPT_POST, 1);
        }

    }
}
