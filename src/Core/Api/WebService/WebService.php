<?php
/**
 * @copyright   (c) gn2
 * @link        https://www.gn2.de/
 */

namespace Gn2\NewsletterConnect\Core\Api\WebService;

use Exception;
use Gn2\NewsletterConnect\Core\Api\Help\Utilities;
use \Gn2\NewsletterConnect\Core\Api\Mailing\MailingList;
use \Gn2\NewsletterConnect\Core\Api\Mailing\Recipient;

/**
 * MailingService implementation for W3Work MailingWork
 */
class WebService extends Curl
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
        $this->_setConfig();
        $this->setPost(true);
        $this->resetParams();
        $this->addParam('username', $this->_config['api_username']);
        $this->addParam('password', $this->_config['api_password']);
    }

    protected function _setConfig()
    {
        $aConfig = Utilities::getSettings();
        $this->_config = $aConfig;
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
     * @return array Array of MailingList Objects
     */
    public function getLists()
    {
        $this->_setMailingworkUrl('getLists');
        $listResponse = $this->_getDecodedResponse();
        $lists = array();

        if ($listResponse['error'] === 0) {
            foreach ($listResponse['result'] as $listData) {
                $list = new MailingList;
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
     * @return MailingList
     *
     */
    public function getMainShopList()
    {
        //$lists = $this->getLists();
        //$oConfig = Registry::getConfig();
        //$shopUrl = $oConfig->getConfigParam('sShopURL');

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
            $list = new MailingList;
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
     * @throws \Exception
     */
    public function createList($listName)
    {
        $this->_setMailingworkUrl('createList');
        $this->addParam('name', $listName);
        $listResponse = $this->_getDecodedResponse();
        if ($listResponse['error'] === 0) {
            $list = new MailingList;
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
     * @param Recipient $recipient Recipient Object
     * @param $mode
     * @return void
     * @throws \Exception
     */
    public function optInRecipient($recipient, $mode = 'general')
    {
        if (is_object($recipient)) {
            if ($mode == "account") {
                $optinId = $this->_config['api_signupsetup_account'];
            } else {
                $optinId = $this->_config['api_signupsetup'];
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
                throw new Exception('optInRecipient failed: please check ' .
                    'if your optinSetup contains all fields for E-Mail, Salutation, Firstname, Lastname, Language.');
            }
        } else {
            throw new Exception(
                'optInRecipient expects an Recipient object'
            );
        }
    }

    /**
     * Subscribes a recipient directly to a mailing list
     *
     * @param MailingList $list List Object
     * @param Recipient $recipient Recipient Object
     *
     * @return void
     * @throws \Exception
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
                throw new Exception('optInRecipient failed: ' . $recipientResponse);
            }
        } else {
            throw new Exception(
                'optInRecipient expects an Recipient object'
            );
        }
    }

    /**
     * Unsubscribes a recipient directly from a mailing list
     * @param MailingList $list
     * @param Recipient $recipient
     * @param string $type
     * @throws \Exception
     */
    public function unsubscribeRecipient($list, $recipient, $type = 'general')
    {
        if (is_object($recipient)) {
            // recipient von mailingwork laden (id notwendig)
            $recipient = $this->getRecipientByEmail($recipient->getEmail());

            if ($recipient) {

                $optoutId = $this->_config['api_signoffsetup'];
                if ($type == 'account') {
                    $optoutId = $this->_config['api_signoffsetup_account'];
                }

                $this->_setMailingworkUrl('optoutRecipientById');
                $this->addParam('optoutSetupId', $optoutId);
                $this->addParam('recipientId', $recipient->getId());
                //$this->addParam('listId', $list->getId());

                $recipientResponse = $this->_getDecodedResponse();

                /*
                if ($recipientResponse['error'] !== 0) {
                    // Mailingwork returns a bad response. Don't throw exceptions.
                     throw new Exception(
                        'unsubscribeRecipient failed: '.$recipientResponse
                    );
                }
                */
            }
        } else {
            throw new Exception(
                'unsubscribeRecipient expects an Recipient object'
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
                //throw new Exception('Cannot get MailingWork fields.');
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
     * @return mixed Recipient or null
     */
    public function getRecipientByEmail($email)
    {
        $recipientId = "";
        $subscriberListData = $this->getSubscriberListsByEmail($email);

        //$this->_setMailingworkUrl('GetOptins');
        //$this->addParam('ListId', 1);
        //$this->addParam('Type', "all");
        //$response = $this->_getDecodedResponse();

        $mainShopList = $this->getMainShopList();
        $mainShopListId = (is_object($mainShopList)) ? $mainShopList->getId() : false;

        // Überprüfe, ob der Kunde in der korrekten Liste eingetragen ist.
        // Ist der Kunde nicht in der Liste, die dem Anmeldesetup zugewiesen ist, wird der Kunde nicht ausgelesen,
        // selbst wenn der Kunde in anderen Listen vorhanden ist. Nur die Shop-Liste ist relevant.

        if ($mainShopListId && is_array($subscriberListData)) {
            foreach( $subscriberListData as $subscriberEntry ) {
                if (is_array($subscriberEntry['subscriberLists'])) {
                    if (in_array($mainShopListId, $subscriberEntry['subscriberLists'])) {
                        $recipientId = $subscriberEntry['recipientId'];
                        break;
                    }
                }
            }
        }

        if ($recipientId != "") {
            try {
                $recipient = $this->getRecipientById($recipientId);
                return $recipient;
            } catch (Exception $e) {
            }
        }
        return null;
    }

    /**
     * @param $email
     * @return array
     */
    public function getSubscriberListsByEmail($email)
    {
        $this->_setMailingworkUrl('getRecipientIdsByEmail');
        $this->addParam('email', $email);
        $recipientResponse = $this->_getDecodedResponse();

        $subscriberLists = array();

        try {
            if ($recipientResponse['error'] === 0 && is_array($recipientResponse['result'])) {
                // go through each user-id and collect all subscriber lists
                foreach ($recipientResponse['result'] as $recipientId) {
                    if ($recipientId) {
                        $subscriberLists[] = array(
                            'recipientId' => $recipientId,
                            'subscriberLists' => $this->getSubscriberListsByRecipientId($recipientId),
                        );
                    }
                }
            }

        } catch (Exception $e) {
        }
        return $subscriberLists;
    }

    /**
     * @param $recipientId
     * @return array
     */
    public function getSubscriberListsByRecipientId($recipientId)
    {
        $subscriberListIds = array();

        if ($recipientId) {
            $this->_setMailingworkUrl('getRecipientListsById');
            $this->addParam('recipientId', $recipientId);
            $listIdsResponse = $this->_getDecodedResponse();

            if ($listIdsResponse['error']===0 && is_array($listIdsResponse['result'])) {
                foreach($listIdsResponse['result'] as $subsciberListData) {
                    if ($subsciberListData['id'] != "") {
                        $subscriberListIds[$subsciberListData['id']] = $subsciberListData['id'];
                    }
                }
            }
        }
        return $subscriberListIds;
    }

    /**
     * Finds a recipient by their id, returns null if not found
     *
     * @param mixed $id ID
     *
     * @return mixed Recipient or null
     * @throws \Exception
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
            throw new Exception('Cannot load recipient: ' . $id);
        }
    }

    /**
     * Converts a MailingWork Recipient to a Recipient
     *
     * @param array $mailingWorkUserFields MailingWork fields array
     *
     * @return Recipient
     */
    protected function _mailingworkRecipient2Recipient($mailingWorkUserFields)
    {
        $fields = $this->_getFields();

        $recipient = new Recipient;
        foreach ($mailingWorkUserFields as $k => $userField) {
            if (isset($fields[$userField['id']])) {
                $fieldName  = $fields[$userField['id']];
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
     * @throws \Exception
     */
    public function transferOrder($recipient, $basketData, $positions)
    {
        $this->_setMailingworkUrl('transferOxidOrder');
        $this->addParam('recipientId', $recipient->getId());
        $this->addParam('orderData', $basketData);
        $this->addParam('orderPositions', $positions);
        $recipientResponse = $this->_getDecodedResponse();
        if ($recipientResponse['error'] !== 0) {
            throw new Exception('transferOrder failed: ' . $recipientResponse);
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
     * @param $recipient Recipient object
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