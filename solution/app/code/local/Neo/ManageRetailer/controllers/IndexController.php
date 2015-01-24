<?php

class Neo_ManageRetailer_IndexController extends Mage_Core_Controller_Front_Action
{
		public function indexAction() 
		{
			echo ".";die;
		}

		public function saveAction()
		{

			$post_data=$this->getRequest()->getPost();


				if ($post_data) {

					try {

						

						$model = Mage::getModel("manageretailer/becomeretailer")
						->addData($post_data)
						->setId($this->getRequest()->getParam("id"))
						->save();

						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Becomeretailer was successfully saved"));
						Mage::getSingleton("adminhtml/session")->setBecomeretailerData(false);

						if ($this->getRequest()->getParam("back")) {
							$this->_redirect("*/*/edit", array("id" => $model->getId()));
							return;
						}
						$this->_redirectUrl(Mage::getBaseUrl()."become-a-retailer");
						return;
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						Mage::getSingleton("adminhtml/session")->setBecomeretailerData($this->getRequest()->getPost());
						$this->_redirectUrl(Mage::getBaseUrl()."become-a-retailer", array("id" => $this->getRequest()->getParam("id")));
					return;
					}

				}
				$this->_redirectUrl(Mage::getBaseUrl()."become-a-retailer");
		}
}
