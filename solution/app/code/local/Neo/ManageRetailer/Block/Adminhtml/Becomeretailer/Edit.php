<?php
	
class Neo_ManageRetailer_Block_Adminhtml_Becomeretailer_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
		public function __construct()
		{

				parent::__construct();
				$this->_objectId = "id";
				$this->_blockGroup = "manageretailer";
				$this->_controller = "adminhtml_becomeretailer";
				$this->_updateButton("save", "label", Mage::helper("manageretailer")->__("Save Item"));
				$this->_updateButton("delete", "label", Mage::helper("manageretailer")->__("Delete Item"));

				$this->_addButton("saveandcontinue", array(
					"label"     => Mage::helper("manageretailer")->__("Save And Continue Edit"),
					"onclick"   => "saveAndContinueEdit()",
					"class"     => "save",
				), -100);



				$this->_formScripts[] = "

							function saveAndContinueEdit(){
								editForm.submit($('edit_form').action+'back/edit/');
							}
						";
		}

		public function getHeaderText()
		{
				if( Mage::registry("becomeretailer_data") && Mage::registry("becomeretailer_data")->getId() ){

				    return Mage::helper("manageretailer")->__("Edit Item '%s'", $this->htmlEscape(Mage::registry("becomeretailer_data")->getId()));

				} 
				else{

				     return Mage::helper("manageretailer")->__("Add Item");

				}
		}
}