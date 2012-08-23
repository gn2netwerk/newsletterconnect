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
    private $_fields;

    /**
     * General API-Specific Initialization
     *
     * @return void
     */
    public function init()
    {   $this->setPost(true);
        $this->resetParams();
        $this->addParam('username', $this->_config['api_username']);
        $this->addParam('password', $this->_config['api_password']);
    }

    /**
     * Sets the current JSON-API Url-Key and prepares the param array
     *
     * @param string $param Url-Key from the Mailingwork Api. e.g. getLists, CreateRecipient
     *
     * @return void
     */
    protected function _setMailingworkUrl($param)
    {   $this->init();
        $this->setUrl($this->_config['api_baseurl'].$param);
    }

    /**
     * Decodes the returned JSON-String into an array
     *
     * @return array JSON Array
     */
    private function _getDecodedResponse()
    {
        $result = $this->getResponse();
        return json_decode($result, true);
    }

    /**
     * Gets current lists from the MailingService
     *
     * @abstract
     * @return array Array of GN2_NewsletterConnect_Mailing_List Objects
     */
    public function getLists()
    {
        $this->_setMailingworkUrl('getLists');
        $listResponse = $this->_getDecodedResponse();
        $lists = array();

        if ($listResponse['error']===0) {
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

    /**
     * Creates a new list on the MailingService
     *
     * @param string $listName List Name
     *
     * @return mixed
     * @throws Exception
     */
    public function createList($listName)
    {
        $this->_setMailingworkUrl('createList');
        $this->addParam('name', $listName);
        $listResponse =  $this->_getDecodedResponse();
        if ($listResponse['error']===0) {
            $list = new GN2_Newsletterconnect_Mailing_List;
            $list->setId($listResponse['result']);
            $list->setName($listName);
            return $list;
        } else {
            throw new Exception($list. ' list cannot be created');
        }
    }

    /**
     * Creates a new recipient on the MailingService
     *
     * @param mixed                                   $listId    List Id
     * @param GN2_NewsletterConnect_Mailing_Recipient $recipient Recipient Object
     *
     * @return void
     * @throws Exception
     */
    public function optInRecipient($list, $recipient)
    {
        if (is_object($recipient)) {
            $this->_setMailingworkUrl('optinRecipient');

            $this->addParam('optinSetupId', $this->_config['api_signupsetup']);

            $fields = array();
            $fields[$this->getFieldId('E-Mail')]   = $recipient->getEmail();
            $fields[$this->getFieldId('Anrede')]   = $recipient->getSalutation();
            $fields[$this->getFieldId('Vorname')]  = $recipient->getFirstName();
            $fields[$this->getFieldId('Nachname')] = $recipient->getLastName();

            $this->addParam('fields', $fields);
            $recipientResponse = $this->_getDecodedResponse();
            if ($recipientResponse['error']!==0) {
                throw new Exception('optInRecipient failed: '.$recipientResponse);
            }
        } else {
            throw new Exception('optInRecipient expects an GN2_NewsletterConnect_Mailing_Recipient object');
        }
    }

    public function subscribeRecipient($list, $recipient)
    {
        if (is_object($recipient)) {
            $fields = array();
            $fields[$this->getFieldId('E-Mail')]   = $recipient->getEmail();
            $fields[$this->getFieldId('Anrede')]   = $recipient->getSalutation();
            $fields[$this->getFieldId('Vorname')]  = $recipient->getFirstName();
            $fields[$this->getFieldId('Nachname')] = $recipient->getLastName();

            $this->_setMailingworkUrl('createRecipient');
            $this->addParam('listId', $list->getId());
            $this->addParam('fields', $fields);
            $recipientResponse = $this->_getDecodedResponse();

            if ($recipientResponse['error']!==0) {
                throw new Exception('optInRecipient failed: '.$recipientResponse);
            }
        } else {
            throw new Exception('optInRecipient expects an GN2_NewsletterConnect_Mailing_Recipient object');
        }
    }

    public function unsubscribeRecipient($list, $recipient)
    {
        if (is_object($recipient)) {
            $recipient = $this->getRecipientByEmail($recipient->getEmail());
            if ($recipient) {
                $this->_setMailingworkUrl('deleteRecipientById');
                $this->addParam('recipientId', $recipient->getId());
                $recipientResponse = $this->_getDecodedResponse();
                //$this->addParam('listId', $list->getId()); //TODO: doesn't exist :-(
                if ($recipientResponse['error']!==0) {
                    throw new Exception('optInRecipient failed: '.$recipientResponse);
                }
            }
        } else {
            throw new Exception('optInRecipient expects an GN2_NewsletterConnect_Mailing_Recipient object');
        }
    }



    private function _getFields()
    {
        if ($this->_fields === null) {
            $this->_setMailingworkUrl('getFields');
            $fieldResponse = $this->_getDecodedResponse();
            if ($fieldResponse['error']===0) {
                $this->_fields = array();
                foreach ($fieldResponse['result'] as $field) {
                    $this->_fields[$field['id']] = $field['name'];
                }
            } else {
                throw new Exception('Cannot get MailingWork fields.');
            }
        }
        return $this->_fields;
    }

    public function getFieldName($id=0) {
        $fields = $this->_getFields();
        if (array_key_exists($id, $fields)) {
            return $fields[$id];
        }
        return '';
    }

    public function getFieldId($name='') {
        $fields = $this->_getFields();
        $pos = array_search($name, $fields);
        if ($pos !== false) {
            return $pos;
        }
        return '';
    }

    public function getRecipientByEmail($email)
    {   $fields = $this->_getFields();

        $this->_setMailingworkUrl('getRecipientIdsByEmail');
        $this->addParam('email', $email);
        $recipientResponse = $this->_getDecodedResponse();
        if ($recipientResponse['error']===0) {
            if (isset($recipientResponse['result'][0])) {
                try {
                    $recipient = $this->getRecipientById($recipientResponse['result'][0]);
                    return $recipient;
                } catch (Exception $e) {
                }
            }
        }
        return null;
    }

    public function getRecipientById($id)
    {
        $this->_setMailingworkUrl('getRecipientFieldsById');
        $this->addParam('recipientId', $id);
        $recipientResponse = $this->_getDecodedResponse();
        if ($recipientResponse['error']===0) {
            $recipient = $this->_mailingworkRecipient2Recipient($recipientResponse['result']);
            if (is_object($recipient)) {
                $recipient->setId($id);
            }
            return $recipient;
        } else {
            throw new Exception('Cannot load recipient: '.$id);
        }
    }

    protected function _mailingworkRecipient2Recipient($mailingWorkUserFields)
    {
        $recipient = new GN2_NewsletterConnect_Mailing_Recipient;
        foreach ($mailingWorkUserFields as $k=>$userField) {
            if (isset($this->_fields[$userField['id']])) {
                $fieldName  = $this->_fields[$userField['id']];
                $fieldValue = $userField['value'];
                switch ($fieldName) {
                case 'E-Mail';
                    $recipient->setEmail($fieldValue);
                    break;
                case 'Anrede';
                    $recipient->setSalutation($fieldValue);
                    break;
                case 'Vorname';
                    $recipient->setFirstName($fieldValue);
                    break;
                case 'Nachname';
                    $recipient->setLastName($fieldValue);
                    break;
                }
            }
        }
        return $recipient;
    }

}