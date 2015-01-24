<?php

class Neo_ManageRetailer_Block_Adminhtml_Becomeretailer_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{
				parent::__construct();
				$this->setId("becomeretailerGrid");
				$this->setDefaultSort("id");
				$this->setDefaultDir("DESC");
				$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{
				$collection = Mage::getModel("manageretailer/becomeretailer")->getCollection();
				$this->setCollection($collection);
				return parent::_prepareCollection();
		}
		protected function _prepareColumns()
		{
				$this->addColumn("id", array(
				"header" => Mage::helper("manageretailer")->__("ID"),
				"align" =>"right",
				"width" => "50px",
			    "type" => "number",
				"index" => "id",
				));
                
				$this->addColumn("first_name", array(
				"header" => Mage::helper("manageretailer")->__("First Name"),
				"index" => "first_name",
				));
				$this->addColumn("last_name", array(
				"header" => Mage::helper("manageretailer")->__("Last Name"),
				"index" => "last_name",
				));
				$this->addColumn("title", array(
				"header" => Mage::helper("manageretailer")->__("Title"),
				"index" => "title",
				));
				$this->addColumn("store_name", array(
				"header" => Mage::helper("manageretailer")->__("Store Name"),
				"index" => "store_name",
				));
						$this->addColumn('store_location', array(
						'header' => Mage::helper('manageretailer')->__('Store Location'),
						'index' => 'store_location',
						'type' => 'options',
						'options'=>Neo_ManageRetailer_Block_Adminhtml_Becomeretailer_Grid::getOptionArray4(),				
						));
						
				/*$this->addColumn("street_address", array(
				"header" => Mage::helper("manageretailer")->__("Street Address"),
				"index" => "street_address",
				));
				$this->addColumn("city", array(
				"header" => Mage::helper("manageretailer")->__("City"),
				"index" => "city",
				));
				$this->addColumn("state", array(
				"header" => Mage::helper("manageretailer")->__("State"),
				"index" => "state",
				));
				$this->addColumn("zip", array(
				"header" => Mage::helper("manageretailer")->__("Zip"),
				"index" => "zip",
				));
				$this->addColumn("country", array(
				"header" => Mage::helper("manageretailer")->__("Country"),
				"index" => "country",
				));
				$this->addColumn("vat", array(
				"header" => Mage::helper("manageretailer")->__("Vat"),
				"index" => "vat",
				));
				$this->addColumn("email", array(
				"header" => Mage::helper("manageretailer")->__("Email"),
				"index" => "email",
				));
				$this->addColumn("confirm_email", array(
				"header" => Mage::helper("manageretailer")->__("Confirm Email"),
				"index" => "confirm_email",
				));*/
				$this->addColumn("website", array(
				"header" => Mage::helper("manageretailer")->__("Website"),
				"index" => "website",
				));
				/*$this->addColumn("fax", array(
				"header" => Mage::helper("manageretailer")->__("Fax"),
				"index" => "fax",
				));*/
				$this->addColumn("type_of_retail", array(
				"header" => Mage::helper("manageretailer")->__("Type of Retail"),
				"index" => "type_of_retail",
				));
				$this->addColumn("speciality", array(
				"header" => Mage::helper("manageretailer")->__("Speciality"),
				"index" => "speciality",
				));
				$this->addColumn("price_range", array(
				"header" => Mage::helper("manageretailer")->__("Price Range"),
				"index" => "price_range",
				));
				/*$this->addColumn("clientele", array(
				"header" => Mage::helper("manageretailer")->__("Client Tele"),
				"index" => "clientele",
				));*/
/*				$this->addColumn("decor", array(
				"header" => Mage::helper("manageretailer")->__("Decor"),
				"index" => "decor",
				));
				$this->addColumn("area", array(
				"header" => Mage::helper("manageretailer")->__("Area (Square Footage)"),
				"index" => "area",
				));
						$this->addColumn('store_open', array(
						'header' => Mage::helper('manageretailer')->__('Store Currently Open'),
						'index' => 'store_open',
						'type' => 'options',
						'options'=>Neo_ManageRetailer_Block_Adminhtml_Becomeretailer_Grid::getOptionArray22(),				
						));
						
				$this->addColumn("business_years", array(
				"header" => Mage::helper("manageretailer")->__("Business Years"),
				"index" => "business_years",
				));
				$this->addColumn("number_of_locations", array(
				"header" => Mage::helper("manageretailer")->__("No of Locations"),
				"index" => "number_of_locations",
				));*/
				/*$this->addColumn("brand_one", array(
				"header" => Mage::helper("manageretailer")->__("Brand 1"),
				"index" => "brand_one",
				));
				$this->addColumn("brand_two", array(
				"header" => Mage::helper("manageretailer")->__("Brand 2"),
				"index" => "brand_two",
				));
				$this->addColumn("brand_three", array(
				"header" => Mage::helper("manageretailer")->__("Brand 3"),
				"index" => "brand_three",
				));
				$this->addColumn("mailing_street_address", array(
				"header" => Mage::helper("manageretailer")->__("Mailing Street Address"),
				"index" => "mailing_street_address",
				));
				$this->addColumn("mailing_city", array(
				"header" => Mage::helper("manageretailer")->__("Mailing City"),
				"index" => "mailing_city",
				));
				$this->addColumn("mailing_state", array(
				"header" => Mage::helper("manageretailer")->__("Mailing State"),
				"index" => "mailing_state",
				));
				$this->addColumn("mailing_zip", array(
				"header" => Mage::helper("manageretailer")->__("Mailing Zip"),
				"index" => "mailing_zip",
				));
				$this->addColumn("mailing_country", array(
				"header" => Mage::helper("manageretailer")->__("Mailing Country"),
				"index" => "mailing_country",
				));
				$this->addColumn("phone_number", array(
				"header" => Mage::helper("manageretailer")->__("Phone Number"),
				"index" => "phone_number",
				));*/
			$this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV')); 
			$this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

				return parent::_prepareColumns();
		}

		public function getRowUrl($row)
		{
			   return $this->getUrl("*/*/edit", array("id" => $row->getId()));
		}


		
		protected function _prepareMassaction()
		{
			$this->setMassactionIdField('id');
			$this->getMassactionBlock()->setFormFieldName('ids');
			$this->getMassactionBlock()->setUseSelectAll(true);
			$this->getMassactionBlock()->addItem('remove_becomeretailer', array(
					 'label'=> Mage::helper('manageretailer')->__('Remove Becomeretailer'),
					 'url'  => $this->getUrl('*/adminhtml_becomeretailer/massRemove'),
					 'confirm' => Mage::helper('manageretailer')->__('Are you sure?')
				));
			return $this;
		}
			
		static public function getOptionArray4()
		{
            $data_array=array(); 
			$data_array[0]='United States ';
			$data_array[1]='International';
            return($data_array);
		}
		static public function getValueArray4()
		{
            $data_array=array();
			foreach(Neo_ManageRetailer_Block_Adminhtml_Becomeretailer_Grid::getOptionArray4() as $k=>$v){
               $data_array[]=array('value'=>$k,'label'=>$v);		
			}
            return($data_array);

		}
		
		static public function getOptionArray22()
		{
            $data_array=array(); 
			$data_array[0]='No';
			$data_array[1]='Yes';
            return($data_array);
		}
		static public function getValueArray22()
		{
            $data_array=array();
			foreach(Neo_ManageRetailer_Block_Adminhtml_Becomeretailer_Grid::getOptionArray22() as $k=>$v){
               $data_array[]=array('value'=>$k,'label'=>$v);		
			}
            return($data_array);

		}
		

}