<?php


class Neo_ManageRetailer_Block_Adminhtml_Becomeretailer extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_becomeretailer";
	$this->_blockGroup = "manageretailer";
	$this->_headerText = Mage::helper("manageretailer")->__("Becomeretailer Manager");
	$this->_addButtonLabel = Mage::helper("manageretailer")->__("Add New Item");
	parent::__construct();
	$this->_removeButton('add');
	}

}