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
 * MailingService implementation for W3Work MailingWork
 */
class GN2_NewsletterConnect_MailingService_Mailingwork
    extends GN2_NewsletterConnect_Webservice_Curl
    implements GN2_NewsletterConnect_MailingService_Interface
{
    /**
     * @var Set of fields to send to the MailingService
     */
    private $_fields;

    /**
     * General API-Specific Initialization
     *
     * @return void
     */
    public function init()
    {
        $this->setPost(true);
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
    {
        $this->init();
        $this->setUrl($this->_config['api_baseurl'] . $param);
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

        if ($listResponse['error'] === 0) {
            foreach ($listResponse['result'] as $listData) {
                $list = new GN2_NewsletterConnect_Mailing_List;
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
     * @return GN2_NewsletterConnect_Mailing_List
     *
     */
    public function getMainShopList()
    {
        #$lists = $this->getLists();
        //$shopUrl = GN2_NewsletterConnect::getOXConfig()->getConfigParam('sShopURL');

        $this->_setMailingworkUrl('getoptinsetups');
        $setups = $this->_getDecodedResponse();

        $listID = -1;
        if (is_array($setups['result'])) {
            foreach ($setups['result'] as $setup) {
                if ($setup['id'] == $this->_config['api_signupsetup']) {
                    if (isset($setup['subscriberlists'])) {
                        $listID = $setup['subscriberlists'][0]['id'];
                    }
                }
            }
        }

        if ($listID > -1) {
            $list = new GN2_NewsletterConnect_Mailing_List;
            $list->setId($listID);
            return $list;
        } else {
            return null;
            /*throw new Exception('No lists found in the configured opt-in-setup:'.
                                ' please check your Mailingwork configuration');*/
        }
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
        $listResponse = $this->_getDecodedResponse();
        if ($listResponse['error'] === 0) {
            $list = new GN2_NewsletterConnect_Mailing_List;
            $list->setId($listResponse['result']);
            $list->setName($listName);
            return $list;
        } else {
            throw new Exception($listName . ' list cannot be created. Please check your Mailingwork Login details.');
        }
    }

    /**
     * Creates a new recipient on the MailingService
     *
     * @param GN2_NewsletterConnect_Mailing_Recipient $recipient Recipient Object
     * @param $mode
     * @return void
     * @throws GN2_NewsletterConnect_Exception_MailingService
     */
    public function optInRecipient($recipient, $mode = 'general')
    {
        if (is_object($recipient)) {

            $optinId = $this->_config['api_signupsetup'];
            if ($mode == "account") {
                $optinId = $this->_config['api_signupsetup_account'];
            }

            $fields = array();
            $fields[$this->getFieldId('E-Mail')] = $recipient->getEmail();
            $fields[$this->getFieldId('Anrede')] = $recipient->getSalutation();
            $fields[$this->getFieldId('Vorname')] = $recipient->getFirstName();
            $fields[$this->getFieldId('Nachname')] = $recipient->getLastName();
            if ($this->getFieldId('Sprache')) {
                $fields[$this->getFieldId('Sprache')] = $recipient->getLanguage();
            }
            $this->_setMailingworkUrl('optInRecipient');

            $this->addParam('optinSetupId', $optinId);
            $this->addParam('fields', $fields);
            $recipientResponse = $this->_getDecodedResponse();

            if ($recipientResponse['error'] !== 0) {
                throw new GN2_NewsletterConnect_Exception_MailingService('optInRecipient failed: please check ' .
                    'if your optinSetup contains all fields for E-Mail, Salutation, Firstname and Lastname.');
            }
        } else {
            throw new GN2_NewsletterConnect_Exception_MailingService(
                'optInRecipient expects an GN2_NewsletterConnect_Mailing_Recipient object'
            );
        }
    }

    /**
     * Subscribes a recipient directly to a mailing list
     *
     * @param GN2_NewsletterConnect_Mailing_List $list List Object
     * @param GN2_NewsletterConnect_Mailing_Recipient $recipient Recipient Object
     *
     * @return void
     * @throws GN2_NewsletterConnect_Exception_MailingService
     */
    public function subscribeRecipient($list, $recipient)
    {
        if (is_object($recipient)) {
            $fields = array();
            $fields[$this->getFieldId('E-Mail')] = $recipient->getEmail();
            $fields[$this->getFieldId('Anrede')] = $recipient->getSalutation();
            $fields[$this->getFieldId('Vorname')] = $recipient->getFirstName();
            $fields[$this->getFieldId('Nachname')] = $recipient->getLastName();
            if ($this->getFieldId('Sprache')) {
                $fields[$this->getFieldId('Sprache')] = $recipient->getLanguage();
            }

            $this->_setMailingworkUrl('createRecipient');
            $this->addParam('listId', $list->getId());
            $this->addParam('fields', $fields);
            $recipientResponse = $this->_getDecodedResponse();

            if ($recipientResponse['error'] !== 0) {
                throw new GN2_NewsletterConnect_Exception_MailingService('optInRecipient failed: ' . $recipientResponse);
            }
        } else {
            throw new GN2_NewsletterConnect_Exception_MailingService(
                'optInRecipient expects an GN2_NewsletterConnect_Mailing_Recipient object'
            );
        }
    }

    /**
     * Unsubscribes a recipient directly from a mailing list
     * @param GN2_NewsletterConnect_Mailing_List $list
     * @param GN2_NewsletterConnect_Mailing_Recipient $recipient
     * @param string $type
     * @throws GN2_NewsletterConnect_Exception_MailingService
     */
    public function unsubscribeRecipient($list, $recipient, $type = 'general')
    {
        if (is_object($recipient)) {
            $recipient = $this->getRecipientByEmail($recipient->getEmail());
            if ($recipient) {

                $optoutId = $this->_config['api_signoffsetup'];
                if ($type == 'account') {
                    $optoutId = $this->_config['api_signoffsetup_account'];
                }

                $this->_setMailingworkUrl('optoutRecipientById');
                $this->addParam('optoutSetupId', $optoutId);
                $this->addParam('recipientId', $recipient->getId());
                $recipientResponse = $this->_getDecodedResponse();
                //$this->addParam('listId', $list->getId());


                if ($recipientResponse['error'] !== 0) {
                    /* Mailingwork returns a bad response. Don't throw exceptions. */
                    /*
                     throw new GN2_NewsletterConnect_Exception_MailingService(
                        'unsubscribeRecipient failed: '.$recipientResponse
                    );*/
                }
            }
        } else {
            throw new GN2_NewsletterConnect_Exception_MailingService(
                'unsubscribeRecipient expects an GN2_NewsletterConnect_Mailing_Recipient object'
            );
        }
    }


    /**
     * Gets fields associated with the MailingWork user table
     *
     * @return array Array of id=>field Names
     */
    private function _getFields()
    {
        if ($this->_fields === null) {
            $this->_setMailingworkUrl('getFields');
            $fieldResponse = $this->_getDecodedResponse();
            if ($fieldResponse['error'] === 0) {
                $this->_fields = array();
                foreach ($fieldResponse['result'] as $field) {
                    $this->_fields[$field['id']] = $field['name'];
                }
            } else {
                //throw new GN2_NewsletterConnect_Exception_MailingService('Cannot get MailingWork fields.');
                return array();
            }
        }
        return $this->_fields;
    }

    /**
     * @param $recipient
     */
    public function updateRecipient($recipient)
    {
        $existingRecipient = $this->getRecipientByEmail($recipient->getEmail());
        if (is_object($existingRecipient)) {
            $id = $existingRecipient->getId();
            if ($id) {

                $this->_setMailingworkUrl('getRecipientListsById');
                $this->addParam('recipientId', $id);
                $listIds = $this->_getDecodedResponse();

                $fields = array();
                $fields[$this->getFieldId('E-Mail')] = $recipient->getEmail();
                $fields[$this->getFieldId('Anrede')] = $recipient->getSalutation();
                $fields[$this->getFieldId('Vorname')] = $recipient->getFirstName();
                $fields[$this->getFieldId('Nachname')] = $recipient->getLastName();
                $advanced = array();
                if (isset($listIds['result'][0])) {
                    $advanced['lists'] = '{' . implode(',', $listIds['result'][0]) . '}';
                }

                if ($this->getFieldId('Sprache')) {
                    $fields[$this->getFieldId('Sprache')] = $recipient->getLanguage();
                }

                $this->_setMailingworkUrl('updateRecipientById');
                $this->addParam('recipientId', $id);
                $this->addParam('fields', $fields);
                $this->addParam('advanced', $advanced);

                $updateResponse = $this->_getDecodedResponse();
            }
        }
    }

    /**
     * Finds the fieldname associated with a field ID
     *
     * @param int $id Id of field
     *
     * @return string Name of field
     */
    public function getFieldName($id = 0)
    {
        $fields = $this->_getFields();
        if (array_key_exists($id, $fields)) {
            return $fields[$id];
        }
        return '';
    }

    /**
     * Finds the field ID associated with a field name
     *
     * @param string $name Name of field
     *
     * @return int Id of field
     */
    public function getFieldId($name = '')
    {
        $fields = $this->_getFields();

        $pos = array_search($name, $fields);
        if ($pos !== false) {
            return $pos;
        }
        return '';
    }

    /**
     * Finds a recipient by their E-Mail address, returns null if not found
     *
     * @param string $email E-Mail Address
     *
     * @return mixed GN2_NewsletterConnect_Mailing_Recipient or null
     */
    public function getRecipientByEmail($email)
    {
        $fields = $this->_getFields();

        $this->_setMailingworkUrl('getRecipientIdsByEmail');
        $this->addParam('email', $email);
        $recipientResponse = $this->_getDecodedResponse();
        if ($recipientResponse['error'] === 0) {
            if (isset($recipientResponse['result'][0])) {
                try {
                    $recipient = $this->getRecipientById($recipientResponse['result'][0]);
                    return $recipient;
                } catch (\Exception $e) {
                    /* Do nothing */
                }
            }
        }
        return null;
    }

    /**
     * Finds a recipient by their id, returns null if not found
     *
     * @param mixed $id ID
     *
     * @return mixed GN2_NewsletterConnect_Mailing_Recipient or null
     * @throws GN2_NewsletterConnect_Exception_MailingService
     */
    public function getRecipientById($id)
    {
        $this->_setMailingworkUrl('getRecipientFieldsById');
        $this->addParam('recipientId', $id);
        $recipientResponse = $this->_getDecodedResponse();
        if ($recipientResponse['error'] === 0) {
            $recipient = $this->_mailingworkRecipient2Recipient($recipientResponse['result']);
            if (is_object($recipient)) {
                $recipient->setId($id);
            }
            return $recipient;
        } else {
            throw new GN2_NewsletterConnect_Exception_MailingService('Cannot load recipient: ' . $id);
        }
    }

    /**
     * Converts a MailingWork Recipient to a GN2_NewsletterConnect_Mailing_Recipient
     *
     * @param array $mailingWorkUserFields MailingWork fields array
     *
     * @return GN2_NewsletterConnect_Mailing_Recipient
     */
    protected function _mailingworkRecipient2Recipient($mailingWorkUserFields)
    {
        $recipient = new GN2_NewsletterConnect_Mailing_Recipient;
        foreach ($mailingWorkUserFields as $k => $userField) {
            if (isset($this->_fields[$userField['id']])) {
                $fieldName = $this->_fields[$userField['id']];
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


    /**
     * @param $recipient
     * @param $basketData
     * @param $positions
     * @throws GN2_NewsletterConnect_Exception_MailingService
     */
    public function transferOrder($recipient, $basketData, $positions)
    {
        $this->_setMailingworkUrl('transferOxidOrder');
        $this->addParam('recipientId', $recipient->getId());
        $this->addParam('orderData', $basketData);
        $this->addParam('orderPositions', $positions);
        $recipientResponse = $this->_getDecodedResponse();
        if ($recipientResponse['error'] !== 0) {
            throw new GN2_NewsletterConnect_Exception_MailingService('transferOrder failed: ' . $recipientResponse);
        }
    }


    /**
     * imports recipients
     * @param $listId int The ID of the list you want to import in
     * @param $recipients array of recipients
     * @param $mode string define import mode (update, replace, add, update_add)
     * @return array mailing work response (Keys: errorcode,message,result)
     */
    public function importRecipients($listId, $recipients, $mode)
    {
        $this->_setMailingworkUrl('importRecipients');
        $this->addParam('listId', $listId);
        $this->addParam('recipients', $recipients);
        $this->addParam('mode', $mode);
        $this->addParam('advanced', array('updateAllFields' => 1,));
        $recipientResponse = $this->_getDecodedResponse();

        return $recipientResponse;
    }


    /**
     * gets the fields from a recipient object
     * @param $recipient recipient object
     * @param boolean $blExportStatus true to export the oxid newsletter status (Subscription status: 0 - not subscribed, 1 - subscribed, 2 - not confirmed)
     * @return array|null
     */
    public function getFields($recipient, $blExportStatus = false)
    {
        if (is_object($recipient)) {
            $fields = array();
            $fields[$this->getFieldId('E-Mail')] = $recipient->getEmail();
            $fields[$this->getFieldId('Anrede')] = $recipient->getSalutation();
            $fields[$this->getFieldId('Vorname')] = $recipient->getFirstName();
            $fields[$this->getFieldId('Nachname')] = $recipient->getLastName();
            if ($this->getFieldId('Sprache') && $recipient->getLanguage()) {
                $fields[$this->getFieldId('Sprache')] = $recipient->getLanguage();
            }

            if ($blExportStatus) {
                if ($this->getFieldId('Anmeldestatus')) {
                    $fields[$this->getFieldId('Anmeldestatus')] = $recipient->getOxidNewsletterStatus();
                }
            }
            return $fields;
        }

        return null;
    }


    /**
     * gets the Header from a recipient object for the csv export
     * @param $recipient recipient object
     * @param boolean $blExportStatus true to export the oxid newsletter status (Subscription status: 0 - not subscribed, 1 - subscribed, 2 - not confirmed)
     * @return array|null
     */
    public function getCSVHeader($recipient, $blExportStatus = false)
    {
        if (is_object($recipient)) {
            $fields = array();

            if ($recipient->getEmail()) {
                $fields[] = 'E-Mail';
            }

            if ($recipient->getSalutation()) {
                $fields[] = 'Anrede';
            }

            if ($recipient->getFirstName()) {
                $fields[] = 'Vorname';
            }

            if ($recipient->getLastName()) {
                $fields[] = 'Nachname';
            }


            if ($this->getFieldId('Sprache') && $recipient->getLanguage()) {
                $fields[] = 'Sprache';
            }

            if ($blExportStatus) {
                if ($this->getFieldId('Anmeldestatus')) {
                    $fields[] = 'Anmeldestatus';
                }
            }
            return $fields;
        }

        return null;
    }

}