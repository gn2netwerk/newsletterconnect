<?php

/**
 * GN2_NewsletterConnect_Export
 *
 * PHP version 5
 *
 * @category GN2_NewsletterConnect
 * @package  GN2_NewsletterConnect
 * @author   Stanley Agu <st@gn2.de>
 * @license  GN2 Commercial Addon License http://www.gn2-netwerk.de/
 * @version  GIT: <git_id>
 * @link     http://www.gn2-netwerk.de/
 */
class GN2_NewsletterConnect_Export{

    /**
     * where clause
     * @var null|string
     */
    private $_sWhereClause = null;

    /**
     * Mailing works service object
     * @var null
     */
    private $_mailingWorks = null;

    /**
     * list Id
     * @var null
     */
    private $_listId;

    /**
     * Import art / mode
     * @var
     */
    private $_sImportArt;


    /**
     * GN2_NewsletterConnect_Export constructor.
     * Builds the where-clause using the given parameters
     * @param $bActiveSubscribers true if active subscribers are to be exported
     * @param $bInActiveSubscribers true if inactive subscribers are to be exported
     * @param $bUnconfirmedSubscribers true if unconfirmed subscribers are to be exported
     * @param $dListId
     * @param $sImportArt
     */
    public function __construct($bActiveSubscribers, $bInActiveSubscribers, $bUnconfirmedSubscribers, $dListId, $sImportArt)
    {

        if($bActiveSubscribers){
            if($this->_sWhereClause === null){
                $this->_sWhereClause = ' WHERE OXDBOPTIN = 1';
            }
        }

        if($bInActiveSubscribers){
            if($this->_sWhereClause === null){
                $this->_sWhereClause = ' WHERE OXDBOPTIN = 0';
            }else{
                $this->_sWhereClause .= ' OR OXDBOPTIN = 0';
            }
        }

        if($bUnconfirmedSubscribers){
            if($this->_sWhereClause === null){
                $this->_sWhereClause = ' WHERE OXDBOPTIN = 2';
            }else{
                $this->_sWhereClause .= ' OR OXDBOPTIN = 2';
            }
        }

        //set the list ID
        $this->_listId = $dListId;

        //set import art
        $this->_sImportArt = $sImportArt;

        //set mailing works object
        $this->_setMailingWorks();
    }


    /**
     * sets the mailing works service object
     */
    private function _setMailingWorks()
    {
        $mailingService = GN2_NewsletterConnect::getMailingService();
        if(is_object($mailingService)){
           $this->_mailingWorks = $mailingService;
        }
    }


    /**
     * transfer subscribers direct to mailing works
     * @return array report
     */
    public function transferData()
    {
        //try get mailing works object
       if($this->_mailingWorks === null){
           return array("REPORT" => GN2_Utilities::FAULTY, "LINK" => ' Mailingworks-Object can not be found.');
       }

        //get user list
        $oUserList = oxNew('oxuserlist');
        $oUserList->selectString($this->_getSubscribersQuery());
        $TotalSubscribers = $oUserList->count();
        if (!$TotalSubscribers) {
            return array("REPORT" => GN2_Utilities::NODATA, "LINK" => null);
        }

        //export to mailing works
        $recipients = $this->_getRecipients( $oUserList );
        $aImportResponse = $this->_mailingWorks->importRecipients( $this->_listId, $recipients, $this->_sImportArt);
        if ($aImportResponse['error']!==0) {
            return array("REPORT" => GN2_Utilities::FAULTY, "LINK" => $aImportResponse['message']);
        }

        return array("REPORT" => GN2_Utilities::SUCCESS, "LINK" => "$TotalSubscribers subscriber(s) transferred/processed.");
    }


    /**
     * Get the recipeints for export
     * @param $oUserList oxuser list
     * @return array array of the recipients
     */
    private function _getRecipients($oUserList)
    {
        $ret = array();
        foreach($oUserList as $oUser){
            $ret[] =  $this->_mailingWorks->getFields($oUser->gn2NewsletterConnectOxid2Recipient($oUser->oxuser__oxemail->rawValue));
        }
        return $ret;
    }


    /**
     * Get the sql query.
     * This can be used to build the CSV array or the oxlist
     * @return string
     */
    private function _getSubscribersQuery()
    {
        $sWhere = $this->_getWhereClause (); //(isset($this->_sWhereClause))? $this->_sWhereClause : '';
        $sSubscribersQuery = 'SELECT ' . $this->_getSelectClause() .'
                                  FROM oxnewssubscribed as t_n 
                                  LEFT JOIN oxuser as t_u 
                                  ON  t_n.OXUSERID = t_u.OXID ' . $sWhere;
        return $sSubscribersQuery;
    }

    
    /**
     * Get the select clause
     * @param string $table1 optional table name
     * @param string $table2 optional table name
     * @return string
     */
    private function _getSelectClause($table1 = 't_n', $table2 = 't_u')
    {
        return "$table1.OXEMAIL, $table1.OXSAL, $table1.OXFNAME, $table1.OXLNAME, $table2.OXBIRTHDATE ";
    }


    /**
     * Gets the where clause
     * @param string $table1 optional table name
     * @param string $table2 optional table name
     * @return string where clause
     */
    private function _getWhereClause($table1 = 't_n', $table2 = 't_u')
    {
        $sShopIDClause = $table1 . ".OXSHOPID = '". GN2_Utilities::getShopId() . "'";
        if($this->_sWhereClause === null){
            return " WHERE $sShopIDClause";
        }

        return "$this->_sWhereClause AND $sShopIDClause";
    }
}