<?php
class Neo_ManageRetailer_Block_Adminhtml_Becomeretailer_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{

				$form = new Varien_Data_Form();
				$this->setForm($form);
				$fieldset = $form->addFieldset("manageretailer_form", array("legend"=>Mage::helper("manageretailer")->__("Item information")));

				
						$fieldset->addField("first_name", "text", array(
						"label" => Mage::helper("manageretailer")->__("First Name"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "first_name",
						));
					
						$fieldset->addField("last_name", "text", array(
						"label" => Mage::helper("manageretailer")->__("Last Name"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "last_name",
						));
					
						$fieldset->addField("title", "text", array(
						"label" => Mage::helper("manageretailer")->__("Title"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "title",
						));
					
						$fieldset->addField("store_name", "text", array(
						"label" => Mage::helper("manageretailer")->__("Store Name"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "store_name",
						));
									
						 $fieldset->addField('store_location', 'select', array(
						'label'     => Mage::helper('manageretailer')->__('Store Location'),
						'values'   => Neo_ManageRetailer_Block_Adminhtml_Becomeretailer_Grid::getValueArray4(),
						'name' => 'store_location',					
						"class" => "required-entry",
						"required" => true,
						));
						$fieldset->addField("street_address", "text", array(
						"label" => Mage::helper("manageretailer")->__("Street Address"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "street_address",
						));
					
						$fieldset->addField("city", "text", array(
						"label" => Mage::helper("manageretailer")->__("City"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "city",
						));
					
						$fieldset->addField("state", "text", array(
						"label" => Mage::helper("manageretailer")->__("State"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "state",
						));
					
						$fieldset->addField("zip", "text", array(
						"label" => Mage::helper("manageretailer")->__("Zip"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "zip",
						));
					
						$fieldset->addField("country", "text", array(
						"label" => Mage::helper("manageretailer")->__("Country"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "country",
						));
					
						$fieldset->addField("vat", "text", array(
						"label" => Mage::helper("manageretailer")->__("Vat"),
						"name" => "vat",
						));
					
						$fieldset->addField("email", "text", array(
						"label" => Mage::helper("manageretailer")->__("Email"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "email",
						));
					
						$fieldset->addField("confirm_email", "text", array(
						"label" => Mage::helper("manageretailer")->__("Confirm Email"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "confirm_email",
						));
					
						$fieldset->addField("website", "text", array(
						"label" => Mage::helper("manageretailer")->__("Website"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "website",
						));

						$fieldset->addField("phone_number", "text", array(
						"label" => Mage::helper("manageretailer")->__("Phone Number"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "phone_number",
						));
										
						$fieldset->addField("fax", "text", array(
						"label" => Mage::helper("manageretailer")->__("Fax"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "fax",
						));
					
						$fieldset->addField("type_of_retail", "text", array(
						"label" => Mage::helper("manageretailer")->__("Type of Retail"),
						"name" => "type_of_retail",
						));
					
						$fieldset->addField("speciality", "text", array(
						"label" => Mage::helper("manageretailer")->__("Speciality"),
						"name" => "speciality",
						));
					
						$fieldset->addField("price_range", "text", array(
						"label" => Mage::helper("manageretailer")->__("Price Range"),
						"name" => "price_range",
						));
					
						$fieldset->addField("clientele", "text", array(
						"label" => Mage::helper("manageretailer")->__("Client Tele"),
						"name" => "clientele",
						));
					
						$fieldset->addField("decor", "text", array(
						"label" => Mage::helper("manageretailer")->__("Decor"),
						"name" => "decor",
						));
					
						$fieldset->addField("area", "text", array(
						"label" => Mage::helper("manageretailer")->__("Area (Square Footage)"),
						"name" => "area",
						));
					
						$fieldset->addField("other_details", "textarea", array(
						"label" => Mage::helper("manageretailer")->__("Other Details"),
						"name" => "other_details",
						));
									
						 $fieldset->addField('store_open', 'select', array(
						'label'     => Mage::helper('manageretailer')->__('Store Currently Open'),
						'values'   => Neo_ManageRetailer_Block_Adminhtml_Becomeretailer_Grid::getValueArray22(),
						'name' => 'store_open',
						));
						$fieldset->addField("business_years", "text", array(
						"label" => Mage::helper("manageretailer")->__("Business Years"),
						"name" => "business_years",
						));
					
						$fieldset->addField("number_of_locations", "text", array(
						"label" => Mage::helper("manageretailer")->__("No of Locations"),
						"name" => "number_of_locations",
						));
					
						$fieldset->addField("brand_one", "text", array(
						"label" => Mage::helper("manageretailer")->__("Brand 1"),
						"name" => "brand_one",
						));
					
						$fieldset->addField("brand_two", "text", array(
						"label" => Mage::helper("manageretailer")->__("Brand 2"),
						"name" => "brand_two",
						));
					
						$fieldset->addField("brand_three", "text", array(
						"label" => Mage::helper("manageretailer")->__("Brand 3"),
						"name" => "brand_three",
						));
					
						$fieldset->addField("mailing_street_address", "text", array(
						"label" => Mage::helper("manageretailer")->__("Mailing Street Address"),
						"name" => "mailing_street_address",
						));
					
						$fieldset->addField("mailing_city", "text", array(
						"label" => Mage::helper("manageretailer")->__("Mailing City"),
						"name" => "mailing_city",
						));
					
						$fieldset->addField("mailing_state", "text", array(
						"label" => Mage::helper("manageretailer")->__("Mailing State"),
						"name" => "mailing_state",
						));
					
						$fieldset->addField("mailing_zip", "text", array(
						"label" => Mage::helper("manageretailer")->__("Mailing Zip"),
						"name" => "mailing_zip",
						));
					
						$fieldset->addField("mailing_country", "text", array(
						"label" => Mage::helper("manageretailer")->__("Mailing Country"),
						"name" => "mailing_country",
						));
					
						

				if (Mage::getSingleton("adminhtml/session")->getBecomeretailerData())
				{
					$form->setValues(Mage::getSingleton("adminhtml/session")->getBecomeretailerData());
					Mage::getSingleton("adminhtml/session")->setBecomeretailerData(null);
				} 
				elseif(Mage::registry("becomeretailer_data")) {
				    $form->setValues(Mage::registry("becomeretailer_data")->getData());
				}
				return parent::_prepareForm();
		}
}
