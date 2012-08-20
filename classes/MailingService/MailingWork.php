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
 * GN2_Newsletterconnect_MailingService_Mailingwork
 *
 * @category   GN2_Newsletterconnect
 * @package    GN2_Newsletterconnect
 * @subpackage Output
 * @author     Dave Holloway <dh@gn2-netwerk.de>
 * @license    GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version    Release: <package_version>
 * @link       http://www.gn2-netwerk.de/
 */
class GN2_Newsletterconnect_MailingService_Mailingwork
    extends GN2_Newsletterconnect_Webservice_Curl
    implements GN2_Newsletterconnect_MailingService_Interface
{
    public function init()
    {   $this->_setPost(true);
        $this->resetParams();
        $this->addParam('username', $this->_config['api_username']);
        $this->addParam('password', $this->_config['api_password']);
    }

    protected function _setMailingworkUrl($param)
    {   $this->init();
        $this->setUrl($this->_config['api_baseurl'].$param);
    }

    private function _getDecodedResponse()
    {
        $result = $this->getResponse();
        return json_decode($result, true);
    }

    public function getLists()
    {
        $this->_setMailingworkUrl('getLists');
        $listResponse = $this->_getDecodedResponse();
        $lists = array();
        if ($listResponse['error']==0) {
            foreach ($listResponse['result'] as $listData) {
                $list = new GN2_Newsletterconnect_Mailing_List;
                $list->setId($listData['id']);
                $list->setName($listData['name']);
                $list->setDesc($listData['description']);
                $lists[] = $list;
            }
        }
        return $lists;
    }

    /**
     * Returns the default shop-list, creating if necessary.
     *
     * @return GN2_Newsletterconnect_Mailing_List
     *
     */
    public function getMainShopList()
    {
        $lists = $this->getLists();
        $shopUrl = oxConfig::getInstance()->getConfigParam('sShopURL');

        $mainShopList = 'NewsletterConnect: '.$shopUrl.' - Shop';

        foreach ($lists as $k=>$v) {
            if (strpos($mainShopList, $v->getName()) !== false) {
                return $lists[$k];
            }
        }
        return $this->createList($mainShopList);
    }

    public function createList($listName)
    {
        $this->_setMailingworkUrl('createList');
        $this->addParam('name', $listName);
        $listResponse =  $this->_getDecodedResponse();
        if ($listResponse['error']==0) {
            $list = new GN2_Newsletterconnect_Mailing_List;
            $list->setId($listResponse['result']);
            $list->setName($listName);
            return $list;
        } else {
            throw new Exception($list. ' list cannot be created');
        }
    }

    public function createRecipient($listId, $recipient)
    {
        if (is_object($recipient)) {
            $this->_setMailingworkUrl('createRecipient');

            $fields = array();
            $fields['E-Mail']   = $recipient->getEmail();
            $fields['Anrede']   = $recipient->getSalutation();
            $fields['Vorname']  = $recipient->getFirstName();
            $fields['Nachname'] = $recipient->getSurname();

            $this->addParam('fields', $fields);

            print_r($this->_getDecodedResponse());
        } else {
            throw new Exception('createRecipient expects an GN2_NewsletterConnect_Mailing_Recipient object');
        }
    }
}