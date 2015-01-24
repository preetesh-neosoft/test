<?php
class Neo_ManageRetailer_Model_Mysql4_Becomeretailer extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("manageretailer/becomeretailer", "id");
    }
}