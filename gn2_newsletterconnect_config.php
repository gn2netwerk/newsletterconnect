<?php
require_once(dirname(__FILE__).'/gn2_newsletterconnect.php');

class gn2_newsletterconnect_config extends oxAdminView
{
    protected $_sThisTemplate = 'gn2_newsletterconnect_config.tpl';

    public function save()
    {
        $config = $this->getConfig();
        $posted = $_REQUEST['config']; // we're not using oxConfig::getRequestParameter here. We know what we're doing.
        $config->saveShopConfVar('aarr', 'config', $posted, null, 'module:gn2_newsletterconnect');
        gn2_newsletterconnect::$config = gn2_newsletterconnect::getEnvironment()->getModuleConfig();
    }

    public function render()
    {
        $qsql = 'SELECT OXID,OXSERIENR FROM oxvoucherseries';
        $rows = oxDb::getDb()->Execute($qsql);
        $voucherSeries = array(
            array('', '--')
        );
        while (!$rows->EOF) {
            $voucherSeries[] = $rows->fields;
            $rows->moveNext();
        }
        $this->_aViewData['voucherSeries'] = $voucherSeries;
        $this->_aViewData['config'] = gn2_newsletterconnect::$config;
        return parent::render();
    }
}
?>