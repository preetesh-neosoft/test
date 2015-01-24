<?php
class Neo_Neoimport_IndexController extends Mage_Core_Controller_Front_Action{
    public function IndexAction() {
      
	  $this->loadLayout();   
	  $this->getLayout()->getBlock("head")->setTitle($this->__("Titlename"));
	        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
      $breadcrumbs->addCrumb("home", array(
                "label" => $this->__("Home Page"),
                "title" => $this->__("Home Page"),
                "link"  => Mage::getBaseUrl()
		   ));

      $breadcrumbs->addCrumb("titlename", array(
                "label" => $this->__("Titlename"),
                "title" => $this->__("Titlename")
		   ));

      $this->renderLayout(); 
	  
    }
	
	public function getOptionId($attribute_code,$label)
	{
		
		$attribute_model 			= Mage::getModel('eav/entity_attribute'); 
		$attribute_options_model 	= Mage::getModel('eav/entity_attribute_source_table') ;
		$attribute_code 			= $attribute_model->getIdByCode('catalog_product', $attribute_code);
        $attribute 					= $attribute_model->load($attribute_code);
        $attribute_table 			= $attribute_options_model->setAttribute($attribute);
        $options 					= $attribute_options_model->getAllOptions(false);
		
        foreach($options as $option){
			
            if ($option['label'] == $label){
				
                $optionId   =$option['value'];
                break;
            }
        }
        
		return $optionId;
    }
	
	
	public function createAction()
	{
		$path = Mage::getBaseDir('media'). '/' ."infiniteeslatest.csv";
		$io = new Varien_Io_File();
		
		if ($io->fileExists($path)){
			
			$io->streamOpen($path, 'r');	
			$header = $io->streamReadCsv();
			
			
			$attrCodeData = array(
							"size_mens" => 140,
							"size" => 141,
							"us_shoe_size_womens" => 142,                    
							"size_womens"=> 143,
							"size_youth" => 144,
							"us_shoe_size_mens" => 145, 
							"color_infiniti" => 146,         
							"size_mens_second" => 147,
							"metal" => 148,
							"design" => 149,
							"style" => 150,
							"mens_size" => 151,
							"metal_purity" => 152,
							"size_junior_Womens" => 153,
						);
			
			$oldProductRow = array();
			$simpleProducts =array();
			$urlCounter      = 1;
			$productCreateCounter = 1;
			
			while (($row = $io->streamReadCsv()) !== false){
				
				$row = array_combine($header, $row);
				if($row['ebay_listing_listingtype']=="multisku"){
					if(!empty($oldProductRow) && $oldProductRow != NULL){
						
						$cProduct = Mage::getModel('catalog/product');   
						$productData=array(
								'name' => $oldProductRow['name'],
								'sku'  => $oldProductRow['variantid'],
								'description' => $oldProductRow['description'],
								'short_description' => $oldProductRow['short_description'],
								'weight' => $oldProductRow['weight'],
								'status' => '1',
								'visibility' => '4',
								'attribute_set_id' => 4,
								'type_id' => 'configurable',
								'price' => $oldProductRow['price'],
								'tax_class_id' => 0,
								'url_key' => $oldProductRow['handle'],
								'brand'   => $oldProductRow['ebay_itemspecifics_1_value_1']
						);
						
						foreach($productData as $key => $value){
							
							$cProduct->setData($key,$value);
						}
						$cProduct->setWebsiteIds(array(1));
						$cProduct->setStockData(array(
								'manage_stock' => 1,
									'is_in_stock' => 1,
									'qty' => 0,
									'use_config_manage_stock' => 0
						));
						
						$categories = array();
						array_push($categories,$oldProductRow['ebay_storecategory1']);
						if(isset($oldProductRow['ebay_storecategory2']) && $oldProductRow['ebay_storecategory2']!=0){
							array_push($categories,$oldProductRow['ebay_storecategory2']);
						}
						$cProduct->setCategoryIds($categories);
						
						$cProduct->setCanSaveCustomOptions(true);
						$cProductTypeInstance = $cProduct->getTypeInstance();
						
						$attribute_ids = array();
						
						
						if(isset($oldProductRow['optionname1']) && $oldProductRow['optionname1']!=""){
							array_push(						
								$attribute_ids,$attrCodeData[$oldProductRow['optionname1']]
							);
						}
						
						if(isset($oldProductRow['optionname2']) && $oldProductRow['optionname2']!=""){
							
							array_push(						
								$attribute_ids,$attrCodeData[$oldProductRow['optionname2']]
							);
						}
						
						
						$cProductTypeInstance->setUsedProductAttributeIds($attribute_ids);
						$attributes_array = $cProductTypeInstance->getConfigurableAttributesAsArray();
						foreach($attributes_array as $key => $attribute_array) 
						{
							$attributes_array[$key]['use_default'] = 1;
							$attributes_array[$key]['position'] = 0;
				 
							if (isset($attribute_array['frontend_label'])){
								
								$attributes_array[$key]['label'] = $attribute_array['frontend_label'];
							}
							else {
								$attributes_array[$key]['label'] = $attribute_array['attribute_code'];
							}
						}
						// Add it back to the configurable product..
						$cProduct->setConfigurableAttributesData($attributes_array);
				 
						$dataArray = array();
						
						foreach ($simpleProducts as $simpleArray)
						{
							$dataArray[$simpleArray['id']] = array();
							foreach ($attributes_array as $key => $attrArray)
							{
								$associatedProductData = array();
								foreach($simpleArray['attrData'] as $simplearrayData){
									
									array_push($associatedProductData, array(
												"attribute_id" => $simplearrayData['attr_id'][$key],
												"label" => $simplearrayData['label'][$key],
												"is_percent" => 0,
												"pricing_value" => $simpleArray['pricing_value'][$key])
									);
								}		
								
								array_push(
									$dataArray[$simpleArray['id']],$associatedProductData
									
								);
							}
						}
						
						$cProduct->setConfigurableProductsData($dataArray);
						//$cProduct->save();
						
						$simpleProducts = array();
						$urlCounter      = 1;
					}
					
					
					
					$config_product_row = $row;
					$oldProductRow      = $row;
					
					$sProduct = Mage::getModel('catalog/product');
					$sProduct->setName($row['name']);
					$sProduct->setSku($row['sku']);
					$sProduct->setWeight($row['weight']);
					$sProduct->setAttributeSetId(4);
					$sProduct->setDescription($row['description']);
					$sProduct->setShortDescription($row['short_description']);
					$sProduct->setTypeId(Mage_Catalog_Model_Product_Type::TYPE_SIMPLE)
							 ->setWebsiteIds(array(1))
							 ->setStatus(Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
							 ->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE)
							 ->setTaxClassId(0);
					$sProduct->setPrice($row['price']);
					$stockData = array(   
								'manage_stock' => 1,
								'is_in_stock' => 1,
								'qty' => $row['qty'],
								'use_config_manage_stock' => 0
							);
					$sProduct->setStockData($stockData);
					
					$categories = array();
					array_push($categories,$config_product_row['ebay_storecategory1']);
					if(isset($config_product_row['ebay_storecategory2']) && $config_product_row['ebay_storecategory2']!=0){
						array_push($categories,$config_product_row['ebay_storecategory2']);
					}
					
					$sProduct->setCategoryIds($categories);
						
					$optionData = array(); 
					if(isset($config_product_row['optionname1']) && $config_product_row['optionname1']!=""){
						
						$optionId = $this->getOptionId($config_product_row['optionname1'],$row['optionvalue1']);
						
						$sProduct->setData($config_product_row['optionname1'], $optionId);
						array_push(						
							$optionData,
							array(
								"attr_code" => $config_product_row['optionname1'],
								"attr_id" => $attrCodeData[$config_product_row['optionname1']], // i have used the hardcoded attribute id of attribute size, you must change according to your store
								"value" => $optionId,
								"label" => $row['optionvalue1'],
							)
						);
					}
					
					if(isset($config_product_row['optionname2']) && $config_product_row['optionname2']!=""){
						
						$optionId = $this->getOptionId($config_product_row['optionname2'],$row['optionvalue2']);
						
						$sProduct->setData($config_product_row['optionname2'], $optionId);
						array_push(						
							$optionData,
							array(
								"attr_code" => $config_product_row['optionname2'],
								"attr_id" => $attrCodeData[$config_product_row['optionname2']], // i have used the hardcoded attribute id of attribute size, you must change according to your store
								"value" => $optionId,
								"label" => $row['optionvalue2'],
							)
						);
					}
					$sProduct->setUrlKey($row['handle']."-".$urlCounter);
					//$sProduct->save();
					
					// we are creating an array with some information which will be used to bind the simple products with the configurable
					array_push(						
						$simpleProducts,
						array(
							"id" => $sProduct->getId(),
							"price" => $sProduct->getPrice(),
							"attrData" => $optionData
						)
					);
					$urlCounter++;
					
				}elseif($row['ebay_listing_listingtype']=="FixedPriceItem"){
					
					
					
					
				}else{
					
					$sProduct = Mage::getModel('catalog/product');
					$sProduct->setName($config_product_row['name']);
					$sProduct->setSku($row['sku']);
					$sProduct->setWeight($row['weight']);
					$sProduct->setAttributeSetId(4);
					$sProduct->setDescription($config_product_row['description']);
					$sProduct->setShortDescription($config_product_row['short_description']);
					$sProduct->setTypeId(Mage_Catalog_Model_Product_Type::TYPE_SIMPLE)
							 ->setWebsiteIds(array(1))
							 ->setStatus(Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
							 ->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE)
							 ->setTaxClassId(0);
					$sProduct->setPrice($row['price']);
					$stockData = array(   
								'manage_stock' => 1,
								'is_in_stock' => 1,
								'qty' => $row['qty'],
								'use_config_manage_stock' => 0
							);
					$sProduct->setStockData($stockData);
					
					$categories = array();
					array_push($categories,$config_product_row['ebay_storecategory1']);
					if(isset($config_product_row['ebay_storecategory2']) && $config_product_row['ebay_storecategory2']!=0){
						array_push($categories,$config_product_row['ebay_storecategory2']);
					}
					
					$sProduct->setCategoryIds($categories);
						
					$optionData = array(); 
					if(isset($config_product_row['optionname1']) && $config_product_row['optionname1']!=""){
						
						$optionId = $this->getOptionId($config_product_row['optionname1'],$row['optionvalue1']);
						
						$sProduct->setData($config_product_row['optionname1'], $optionId);
						array_push(						
							$optionData,
							array(
								"attr_code" => $config_product_row['optionname1'],
								"attr_id" => $attrCodeData[$config_product_row['optionname1']], // i have used the hardcoded attribute id of attribute size, you must change according to your store
								"value" => $optionId,
								"label" => $row['optionvalue1'],
							)
						);
					}
					
					if(isset($config_product_row['optionname2']) && $config_product_row['optionname2']!=""){
						
						$optionId = $this->getOptionId($config_product_row['optionname2'],$row['optionvalue2']);
						
						$sProduct->setData($config_product_row['optionname2'], $optionId);
						array_push(						
							$optionData,
							array(
								"attr_code" => $config_product_row['optionname2'],
								"attr_id" => $attrCodeData[$config_product_row['optionname2']], // i have used the hardcoded attribute id of attribute size, you must change according to your store
								"value" => $optionId,
								"label" => $row['optionvalue2'],
							)
						);
					}
					$sProduct->setUrlKey($row['handle']."-".$urlCounter);
					try{
						//$sProduct->save();	
					}catch(Exception $e){
						
						echo $e;
						
					}
					
					
					// we are creating an array with some information which will be used to bind the simple products with the configurable
					array_push(						
						$simpleProducts,
						array(
							"id" => $sProduct->getId(),
							"price" => $sProduct->getPrice(),
							"attrData" => $optionData
						)
					);
					$urlCounter++;			
				}
				echo $productCreateCounter;
				$productCreateCounter++;
			}
		}
	}
	
	
	public function simpleAction()
	{
		$path = Mage::getBaseDir('media'). '/' ."infiniteeslatest.csv";
		$io = new Varien_Io_File();
		
		if ($io->fileExists($path)){
			
			$io->streamOpen($path, 'r');	
			$header = $io->streamReadCsv();
			
			
			$attrCodeData = array(
							"size_mens" => 140,
							"size" => 141,
							"us_shoe_size_womens" => 142,                    
							"size_womens"=> 143,
							"size_youth" => 144,
							"us_shoe_size_mens" => 145, 
							"color_infiniti" => 146,         
							"size_mens_second" => 147,
							"metal" => 148,
							"design" => 149,
							"style" => 150,
							"mens_size" => 151,
							"metal_purity" => 152,
							"size_junior_Womens" => 153,
						);
			
			$oldProductRow = array();
			$simpleProducts =array();
			$urlCounter      = 1;
			$productCreateCounter = 1;
			echo "<pre>";
			while (($row = $io->streamReadCsv()) !== false){
				
				$row = array_combine($header, $row);
				if($row['ebay_listing_listingtype']=="FixedPriceItem"){
					
					$config_product_row = $row;
					$oldProductRow      = $row;
					
					$sProduct = Mage::getModel('catalog/product');
					$sProduct->setName($row['name']);
					$sProduct->setSku($row['sku']);
					$sProduct->setWeight($row['weight']);
					$sProduct->setAttributeSetId(4);
					$sProduct->setDescription($row['description']);
					$sProduct->setShortDescription($row['short_description']);
					$sProduct->setTypeId(Mage_Catalog_Model_Product_Type::TYPE_SIMPLE)
							 ->setWebsiteIds(array(1))
							 ->setStatus(Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
							 ->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE)
							 ->setTaxClassId(0);
					$sProduct->setPrice($row['price']);
					$stockData = array(   
								'manage_stock' => 1,
								'is_in_stock' => 1,
								'qty' => $row['qty'],
								'use_config_manage_stock' => 0
							);
					$sProduct->setStockData($stockData);
					
					$categories = array();
					array_push($categories,$config_product_row['ebay_storecategory1']);
					if(isset($config_product_row['ebay_storecategory2']) && $config_product_row['ebay_storecategory2']!=0){
						array_push($categories,$config_product_row['ebay_storecategory2']);
					}
					
					$sProduct->setCategoryIds($categories);
						
					$optionData = array(); 
					if(isset($config_product_row['optionname1']) && $config_product_row['optionname1']!=""){
						
						$optionId = $this->getOptionId($config_product_row['optionname1'],$row['optionvalue1']);
						
						$sProduct->setData($config_product_row['optionname1'], $optionId);
						array_push(						
							$optionData,
							array(
								"attr_code" => $config_product_row['optionname1'],
								"attr_id" => $attrCodeData[$config_product_row['optionname1']], // i have used the hardcoded attribute id of attribute size, you must change according to your store
								"value" => $optionId,
								"label" => $row['optionvalue1'],
							)
						);
					}
					
					if(isset($config_product_row['optionname2']) && $config_product_row['optionname2']!=""){
						
						$optionId = $this->getOptionId($config_product_row['optionname2'],$row['optionvalue2']);
						
						$sProduct->setData($config_product_row['optionname2'], $optionId);
						array_push(						
							$optionData,
							array(
								"attr_code" => $config_product_row['optionname2'],
								"attr_id" => $attrCodeData[$config_product_row['optionname2']], // i have used the hardcoded attribute id of attribute size, you must change according to your store
								"value" => $optionId,
								"label" => $row['optionvalue2'],
							)
						);
					}
					$sProduct->setUrlKey($row['handle']);
					//$sProduct->save();
					
					// we are creating an array with some information which will be used to bind the simple products with the configurable
					
					
				}
				echo $productCreateCounter;
				$productCreateCounter++;
			}
		}
		
		
	}


	
	
	public function imagemyAction()
	{
		$path = Mage::getBaseDir('media'). '/' ."imageUpload.csv";
		$io = new Varien_Io_File();
		
		if ($io->fileExists($path)){
			
			$io->streamOpen($path, 'r');	
			$header = $io->streamReadCsv();
			while (($row = $io->streamReadCsv()) !== false){
				$row = array_combine($header, $row);
				if($row['ebay_listing_listingtype']=="FixedPriceItem"){
					echo "in multi sku";
					$_product = Mage::getModel("catalog/product")->loadByAttribute('sku',$row['sku']);
					
					if($_product->getId()){
						
						$images = explode(",",$row['images']);
						$i=1;
						foreach($images as $image){
							try{
								if($i==1){
									
									$mediaAttribute = array('image', 'small_image', 'thumbnail');
								}else{
									
									$mediaAttribute = array();
								}
								$filepath = Mage::getBaseDir('media') . DS . trim($image); //path for temp storage folder: ./media/import/
								
								if ($this->is_file_exists($filepath)) {
									$_product->addImageToMediaGallery($filepath, $mediaAttribute, false, false);
									
								}
									
							}catch(Exception $e){
								echo $e;
							}
							$i++; 
						}
						//$_product->save();
					}
				}
			}
		}	
		
	} 
	public function is_file_exists($filePath)
	{
      if((is_file($filePath))&&(file_exists($filePath))){
        return true;
      }   
      return false;
	}
	

	
}
