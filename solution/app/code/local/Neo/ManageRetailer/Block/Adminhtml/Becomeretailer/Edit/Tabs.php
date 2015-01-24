<?php
class Neo_ManageRetailer_Block_Adminhtml_Becomeretailer_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
		public function __construct()
		{
				parent::__construct();
				$this->setId("becomeretailer_tabs");
				$this->setDestElementId("edit_form");
				$this->setTitle(Mage::helper("manageretailer")->__("Item Information"));
		}
		protected function _beforeToHtml()
		{
				$this->addTab("form_section", array(
				"label" => Mage::helper("manageretailer")->__("Item Information"),
				"title" => Mage::helper("manageretailer")->__("Item Information"),
				"content" => $this->getLayout()->createBlock("manageretailer/adminhtml_becomeretailer_edit_tab_form")->toHtml(),
				));
				return parent::_beforeToHtml();
		}

}
