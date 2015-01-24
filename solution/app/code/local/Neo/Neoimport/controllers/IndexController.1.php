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
	
	
    public function addAttributeValue($arg_attribute, $arg_value)
    {
        $attribute_model        = Mage::getModel('eav/entity_attribute');

        $attribute_code         = $attribute_model->getIdByCode('catalog_product', $arg_attribute);
        $attribute              = $attribute_model->load($attribute_code);

        if(!$this->attributeValueExists($arg_attribute, $arg_value))
        {
            $value['option'] = array($arg_value,$arg_value);
            $result = array('value' => $value);
            $attribute->setData('option',$result);
            $attribute->save();
        }

		$attribute_options_model= Mage::getModel('eav/entity_attribute_source_table') ;
        $attribute_table        = $attribute_options_model->setAttribute($attribute);
        $options                = $attribute_options_model->getAllOptions(false);

        foreach($options as $option)
        {
            if ($option['label'] == $arg_value)
            {
                return $option['value'];
            }
        }
       return false;
    }

    

	public function getOptionId($attribute_code,$label)
	{
		
		$attribute_model 			= Mage::getModel('eav/entity_attribute'); 
		$attribute_options_model 	= Mage::getModel('eav/entity_attribute_source_table') ;
		$attribute_code 			= $attribute_model->getIdByCode('catalog_product', $attribute_code);
        $attribute 					= $attribute_model->load($attribute_code);
        $attribute_table 			= $attribute_options_model->setAttribute($attribute);
        $options 					= $attribute_options_model->getAllOptions(false);
	
		if(!$this->attributeValueExists($attribute_code, $label))
	        {
	            $value['option'] = array($attribute_code,$label);
	            $result = array('value' => $value);
	            $attribute->setData('option',$result);
	            $attribute->save();
	        }
			
	        foreach($options as $option){
				
	            if ($option['label'] == $label){
					
	                return $option['value'];
	                //break;
	            }
	        }
	        
			return false;
	}

	public function getProductIdBySku($current_product_id){
		$productid = Mage::getModel('catalog/product')->getIdBySku(trim($current_product_id));
		if($productid){
			return $productid;
		}else{
			return 0;
		}
	}

    public function attributeValueExists($arg_attribute, $arg_value)
    {
        $attribute_model        = Mage::getModel('eav/entity_attribute');
        $attribute_options_model= Mage::getModel('eav/entity_attribute_source_table') ;

        $attribute_code         = $attribute_model->getIdByCode('catalog_product', $arg_attribute);
        $attribute              = $attribute_model->load($attribute_code);

        $attribute_table        = $attribute_options_model->setAttribute($attribute);
        $options                = $attribute_options_model->getAllOptions(false);

        foreach($options as $option)
        {
            if ($option['label'] == $arg_value)
            {
                return $option['value'];
            }
        }

        return false;
    }
	
	public function fetchMainCategoryId($current_cat)
	{
		$cat = Mage::getResourceModel('catalog/category_collection')->addFieldToFilter('name', $current_cat)->addAttributeToFilter('level',2);
		$category_exists = ( $cat->getFirstItem()->getEntityId() ) ?  $cat->getFirstItem()->getEntityId()  : 0 ;
        return $category_exists;
    }
	

    public function fetchSubCategoryId($sub_category_parent,$current_cat)
	{
		$cat1 = Mage::getResourceModel('catalog/category_collection')->addFieldToFilter('name', $current_cat)->addAttributeToFilter('parent_id',$sub_category_parent);
		$category_exists1 = ( $cat1->getFirstItem()->getEntityId() ) ?  $cat1->getFirstItem()->getEntityId()  : 0 ;
        
		return $category_exists1;
    }


	public function createAction()
	{
	    //exit("reached");
	    $path = Mage::getBaseDir('media'). '/' ."sample.csv";
	     $io = new Varien_Io_File();
		
		if ($io->fileExists($path)){
			
			$io->streamOpen($path, 'r');	
			$header = $io->streamReadCsv();
			
			$attrCodeData = array(
							"materials" => 135,
							"size_imp" => 134,
							"size_met" => 136,
							"power" => 137,                    
							"trade_price"=> 138,
							"rrp" => 148,
							"mpn" => 139, 
							"manufacturer" => 81,         
							"is_man" => 143,
							"is_woman" => 141,
							"is_lingerie" => 142,
							"is_couples" => 144,
							"is_fetish" => 145,
							"is_gay_male" => 146,
							"is_gay_female" => 147,
						);
			
			$oldProductRow = array();
			$simpleProducts =array();
			$urlCounter      = 1;
			$productCreateCounter = 1;
			
			while (($row = $io->streamReadCsv()) !== false){
				$row = array_combine($header, $row);
				
				if( !empty($row['Subproduct Code']) && $row['Subproduct Code']!=NULL){
                                        
                                        if(!empty($oldProductRow) && $oldProductRow != NULL)
					{
						
						$cProduct = Mage::getModel('catalog/product');   
//						$product_id = $cProduct->getIdBySku($oldProductRow['Product Code']);
//                                                if($product_id){
//                                                    $current_product_exists = $cProduct->load($product_id);
//                                                    foreach($current_product_exists as $data){
//                                                        $cProduct = $data;
//                                                    }
//                                                }
                                                $url_string = str_replace(" ","_",$oldProductRow['Product Name']).'cfg';
                                                $productData=array(
								'name' => $oldProductRow['Product Name'],
								'sku'  => $oldProductRow['Product Code'],
								'description' => $oldProductRow['Description'],
								'short_description' => $oldProductRow['Description'],
								'weight' => '0',
								'status' => '1',
								'visibility' => '4',
								'attribute_set_id' => 4,
								'type_id' => 'configurable',
								'price' => $oldProductRow['Trade Price'],
								'tax_class_id' => 0,
								'url_key' => $url_string
								//'brand'   => $oldProductRow['ebay_itemspecifics_1_value_1']
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
						
						//$categories = array();
						//array_push($categories,$oldProductRow['ebay_storecategory1']);
						//if(isset($oldProductRow['ebay_storecategory2']) && $oldProductRow['ebay_storecategory2']!=0){
						//	array_push($categories,$oldProductRow['ebay_storecategory2']);
						//}
						//$cProduct->setCategoryIds($categories);
						//                                          
                                                $categories = array();
                                                    $main_category_id = $this->fetchMainCategoryId($oldProductRow['Category']);
                                                    array_push($categories,$main_category_id);
                                                    if($oldProductRow['Range'] != '' || $oldProductRow['Range'] != NULL){
                                                        $sub_cat_id = $this->fetchSubCategoryId($main_category_id,$oldProductRow['Range']);
                                                        array_push($categories,$sub_cat_id);
                                                    }
							
						$cProduct->setCategoryIds($categories);
							
						
						$cProduct->setCanSaveCustomOptions(true);
						$cProductTypeInstance = $cProduct->getTypeInstance();
						
						$attribute_ids = array();
						
						
						if(isset($oldProductRow['materials']) && $oldProductRow['materials']!=""){
							array_push(						
								$attribute_ids,$attrCodeData[$oldProductRow['materials']]
							);
						}
						
						//if(isset($oldProductRow['optionname2']) && $oldProductRow['optionname2']!=""){
						//	
						//	array_push(						
						//		$attribute_ids,$attrCodeData[$oldProductRow['optionname2']]
						//	);
						//}
						
						
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
						$cProduct->save();
						
						$simpleProducts = array();
                                                $oldProductRow = NULL;
						$urlCounter      = 1;
					}    
					
                                        $oldProductRow      = $row;
					$sProduct = Mage::getModel('catalog/product');
						//$current_product_already_exists = $sProduct->loadByAttribute('sku',);
						$product_id = $sProduct->getIdBySku( $row['Subproduct Code'] );
						if($product_id){
						    $current_product_already_exists = $sProduct->load($product_id);
						    foreach($current_product_already_exists as $data)
							$sProduct = $data;
						}	
							
							$sProduct->setName($row['Product Name']);
							$sProduct->setSku($row['Subproduct Code']);
							$sProduct->setWeight('0');
							$sProduct->setAttributeSetId(4);
							$sProduct->setDescription($row['Description']);
							$sProduct->setShortDescription($row['Description']);
							$sProduct->setTypeId(Mage_Catalog_Model_Product_Type::TYPE_SIMPLE)
									 ->setWebsiteIds(array(1))
									 ->setStatus(Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
									 ->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE)
									 ->setTaxClassId(0);
							$sProduct->setPrice($row['Trade Price']);
							$stockData = array(   
										'manage_stock' => 1,
										'is_in_stock' => 1,
										'qty' => $row['StockLevel'],
										'use_config_manage_stock' => 0
									);
							$sProduct->setStockData($stockData);
							
							/*$categories = array();
							array_push($categories,'23851');*/
							//if(isset($config_product_row['ebay_storecategory2']) && $config_product_row['ebay_storecategory2']!=0){
							//	array_push($categories,$config_product_row['ebay_storecategory2']);
							//}
							
							$categories = array();
							$main_category_id = $this->fetchMainCategoryId($row['Category']);
							array_push($categories,$main_category_id);
							if($row['Range'] != '' || $row['Range'] != NULL){
								$sub_cat_id = $this->fetchSubCategoryId($main_category_id,$row['Range']);
								array_push($categories,$sub_cat_id);
							}
							
							$sProduct->setCategoryIds($categories);
								
							$optionData = array(); 
							if(isset($row['materials']) && $row['materials']!=""){
								
								$optionId = $this->addAttributeValue('materials',$row['materials']);
								//exit($optionId);
								
								$sProduct->setData('materials', $optionId);
								array_push(						
									$optionData,
									array(
										"attr_code" => 'materials',
										"attr_id" => $attrCodeData['materials'], // i have used the hardcoded attribute id of attribute size, you must change according to your store
										"value" => $optionId,
										"label" => $row['materials'],
									)
								);
							}
							
							//if(isset($config_product_row['optionname2']) && $config_product_row['optionname2']!=""){
							//	
							//	$optionId = $this->getOptionId($config_product_row['optionname2'],$row['optionvalue2']);
							//	
							//	$sProduct->setData($config_product_row['optionname2'], $optionId);
							//	array_push(						
							//		$optionData,
							//		array(
							//			"attr_code" => $config_product_row['optionname2'],
							//			"attr_id" => $attrCodeData[$config_product_row['optionname2']], // i have used the hardcoded attribute id of attribute size, you must change according to your store
							//			"value" => $optionId,
							//			"label" => $row['optionvalue2'],
							//		)
							//	);
							//}
							$url_string = str_replace(' ','_',$row['Product Name']); 
							$sProduct->setUrlKey($url_string."-".$urlCounter);
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
							$sProduct->save();
					
				}/*elseif($row['ebay_listing_listingtype']=="FixedPriceItem"){
					
					
					
					
				}*/else{
                                        
                                        
					
					//exit("reached");
					if( empty($row['Subproduct Code']) ){
						$sProduct = Mage::getModel('catalog/product');
						//$current_product_already_exists = $sProduct->loadByAttribute('sku',);
						$product_id = $sProduct->getIdBySku( $row['Product Code'] );
						if($product_id){
						    $current_product_already_exists = $sProduct->load($product_id);
						    foreach($current_product_already_exists as $data)
							$sProduct = $data;
						}	
							
							$sProduct->setName($row['Product Name']);
							$sProduct->setSku($row['Product Code']);
							$sProduct->setWeight('0');
							$sProduct->setAttributeSetId(4);
							$sProduct->setDescription($row['Description']);
							$sProduct->setShortDescription($row['Description']);
							$sProduct->setTypeId(Mage_Catalog_Model_Product_Type::TYPE_SIMPLE)
									 ->setWebsiteIds(array(1))
									 ->setStatus(Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
									 ->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE)
									 ->setTaxClassId(0);
							$sProduct->setPrice($row['Trade Price']);
							$stockData = array(   
										'manage_stock' => 1,
										'is_in_stock' => 1,
										'qty' => $row['StockLevel'],
										'use_config_manage_stock' => 0
									);
							$sProduct->setStockData($stockData);
							
							/*$categories = array();
							array_push($categories,'23851');*/
							//if(isset($config_product_row['ebay_storecategory2']) && $config_product_row['ebay_storecategory2']!=0){
							//	array_push($categories,$config_product_row['ebay_storecategory2']);
							//}
							
							$categories = array();
							$main_category_id = $this->fetchMainCategoryId($row['Category']);
							array_push($categories,$main_category_id);
							if($row['Range'] != '' || $row['Range'] != NULL){
								$sub_cat_id = $this->fetchSubCategoryId($main_category_id,$row['Range']);
								array_push($categories,$sub_cat_id);
							}
							
							$sProduct->setCategoryIds($categories);
								
							$optionData = array(); 
							if(isset($row['materials']) && $row['materials']!=""){
								
								$optionId = $this->addAttributeValue('materials',$row['materials']);
								//exit($optionId);
								
								$sProduct->setData('materials', $optionId);
								array_push(						
									$optionData,
									array(
										"attr_code" => 'materials',
										"attr_id" => $attrCodeData['materials'], // i have used the hardcoded attribute id of attribute size, you must change according to your store
										"value" => $optionId,
										"label" => $row['materials'],
									)
								);
							}
							
							//if(isset($config_product_row['optionname2']) && $config_product_row['optionname2']!=""){
							//	
							//	$optionId = $this->getOptionId($config_product_row['optionname2'],$row['optionvalue2']);
							//	
							//	$sProduct->setData($config_product_row['optionname2'], $optionId);
							//	array_push(						
							//		$optionData,
							//		array(
							//			"attr_code" => $config_product_row['optionname2'],
							//			"attr_id" => $attrCodeData[$config_product_row['optionname2']], // i have used the hardcoded attribute id of attribute size, you must change according to your store
							//			"value" => $optionId,
							//			"label" => $row['optionvalue2'],
							//		)
							//	);
							//}
							$url_string = str_replace(' ','_',$row['Product Name']); 
							$sProduct->setUrlKey($url_string."-".$urlCounter);
							try{
								//$sProduct->save();	
							}catch(Exception $e){
								
								echo $e;
								
							}
							
							
							// we are creating an array with some information which will be used to bind the simple products with the configurable
							//array_push(						
							//	$simpleProducts,
							//	array(
							//		"id" => $sProduct->getId(),
							//		"price" => $sProduct->getPrice(),
							//		"attrData" => $optionData
							//	)
							//);
							$urlCounter++;
							$sProduct->save();
						
					}
				}
				echo $productCreateCounter;
				$productCreateCounter++;
			}
		}
	}
	

	public function createConfigProdAction()
	{
	    //exit("reached");
	    $path = Mage::getBaseDir('media'). '/' ."sample.csv";
	    $io = new Varien_Io_File();
		
		if ($io->fileExists($path)){
			
			$io->streamOpen($path, 'r');	
			$header = $io->streamReadCsv();
			
			$attrCodeData = array(
							"materials" => 135,
							"size_imp" => 134,
							"size_met" => 136,
							"power" => 137,                    
							"trade_price"=> 138,
							"rrp" => 148,
							"mpn" => 139, 
							"manufacturer" => 81,         
							"is_man" => 143,
							"is_woman" => 141,
							"is_lingerie" => 142,
							"is_couples" => 144,
							"is_fetish" => 145,
							"is_gay_male" => 146,
							"is_gay_female" => 147,
						);
			
			$oldProductRow = array();
			$simpleProducts =array();
			$urlCounter      = 1;
			$productCreateCounter = 1;
			
			while (($row = $io->streamReadCsv()) !== false){
				$row = array_combine($header, $row);
				
				if( !empty($row['Subproduct Code'])){
					$current_product_already_exists = getProductIdBySku($row['Product Code']);

					if(!$current_product_already_exists)
					{
						
						$cProduct = Mage::getModel('catalog/product');   
						$productData=array(
								'name' => $row['name'],
								'sku'  => $row['Product Code'],
								'weight' => 0,
								'status' => '1',
								//'visibility' => Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH,
								//'attribute_set_id' => 4,
								'type_id' => 'configurable',
								'price' => $row['Trade Price'],
								'tax_class_id' => 0,
								'url_key' => $row['handle']
						);
						
						foreach($productData as $key => $value){
							
							$cProduct->setData($key,$value);
						}
						$cProduct->setWebsiteIds(array(1));
						$cProduct->setStockData(array(
								'manage_stock' => 1,
									'is_in_stock' => $row['Stock'],
									'qty' => $row['StockLevel'],
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
						
						
						if(isset($row['materials']) && $row['materials']!=""){
							array_push(						
								$attribute_ids,$attrCodeData[$row['materials']]
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
					
				}/*elseif($row['ebay_listing_listingtype']=="FixedPriceItem"){
					
					
					
					
				}*/else{
					//exit("reached");
					$sProduct = Mage::getModel('catalog/product');
					$sProduct->setName($row['Product Name']);
					$sProduct->setSku($row['Product Code']);
					$sProduct->setWeight('0');
					$sProduct->setAttributeSetId(4);
					$sProduct->setDescription($row['Description']);
					$sProduct->setShortDescription($row['Description']);
					$sProduct->setTypeId(Mage_Catalog_Model_Product_Type::TYPE_SIMPLE)
							 ->setWebsiteIds(array(1))
							 ->setStatus(Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
							 ->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE)
							 ->setTaxClassId(0);
					$sProduct->setPrice($row['Trade Price']);
					$stockData = array(   
								'manage_stock' => 1,
								'is_in_stock' => 1,
								'qty' => $row['StockLevel'],
								'use_config_manage_stock' => 0
							);
					$sProduct->setStockData($stockData);
					
					/*$categories = array();
					array_push($categories,'23851');*/
					//if(isset($config_product_row['ebay_storecategory2']) && $config_product_row['ebay_storecategory2']!=0){
					//	array_push($categories,$config_product_row['ebay_storecategory2']);
					//}
					
					$categories = array();
					$main_category_id = $this->fetchMainCategoryId($row['Category']);
					array_push($categories,$main_category_id);
					if($row['Range'] != '' || $row['Range'] != NULL){
						$sub_cat_id = $this->fetchSubCategoryId($main_category_id,$row['Range']);
						array_push($categories,$sub_cat_id);
					}
					
					$sProduct->setCategoryIds($categories);
						
					$optionData = array(); 
					if(isset($row['materials']) && $row['materials']!=""){
						
						$optionId = $this->addAttributeValue('materials',$row['materials']);
						//exit($optionId);
						
						$sProduct->setData('materials', $optionId);
						array_push(						
							$optionData,
							array(
								"attr_code" => 'materials',
								"attr_id" => $attrCodeData['materials'], // i have used the hardcoded attribute id of attribute size, you must change according to your store
								"value" => $optionId,
								"label" => $row['materials'],
							)
						);
					}
					
					//if(isset($config_product_row['optionname2']) && $config_product_row['optionname2']!=""){
					//	
					//	$optionId = $this->getOptionId($config_product_row['optionname2'],$row['optionvalue2']);
					//	
					//	$sProduct->setData($config_product_row['optionname2'], $optionId);
					//	array_push(						
					//		$optionData,
					//		array(
					//			"attr_code" => $config_product_row['optionname2'],
					//			"attr_id" => $attrCodeData[$config_product_row['optionname2']], // i have used the hardcoded attribute id of attribute size, you must change according to your store
					//			"value" => $optionId,
					//			"label" => $row['optionvalue2'],
					//		)
					//	);
					//}
					$url_string = str_replace(' ','_',$row['Product Name']); 
					$sProduct->setUrlKey($url_string."-".$urlCounter);
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
					$sProduct->save();
				}
				echo $productCreateCounter;
				$productCreateCounter++;
			}
		}
	}


	
	public function simpleAction()
	{
		$path = Mage::getBaseDir('media'). '/' ."sample.csv";
		$io = new Varien_Io_File();
		
		if ($io->fileExists($path)){
			
			$io->streamOpen($path, 'r');	
			$header = $io->streamReadCsv();
			
			
			$attrCodeData = array(
							"materials" => 133,
							"size_imp" => 134,
							"size_met" => 136,
							"power" => 137,                    
							"trade_price"=> 138,
							"rrp" => 148,
							"mpn" => 139, 
							"manufacturer" => 81,         
							"is_man" => 143,
							"is_woman" => 141,
							"is_lingerie" => 142,
							"is_couples" => 144,
							"is_fetish" => 145,
							"is_gay_male" => 146,
							"is_gay_female" => 147,
						);
			
			$oldProductRow = array();
			$simpleProducts =array();
			$urlCounter      = 1;
			$productCreateCounter = 1;
			echo "<pre>";
			while (($row = $io->streamReadCsv()) !== false){
				
				$row = array_combine($header, $row);
				if($row['Subproduct Code']==""){
					$config_product_row = $row;
					$oldProductRow      = $row;
					
					$sProduct = Mage::getModel('catalog/product');
					$sProduct->setName($row['Product Name']);
					$sProduct->setSku($row['Product Code']);
					//$sProduct->setWeight($row['weight']);
					//$sProduct->setAttributeSetId(4);
					//$sProduct->setDescription($row['description']);
					//$sProduct->setShortDescription($row['short_description']);
					$sProduct->setTypeId(Mage_Catalog_Model_Product_Type::TYPE_SIMPLE)
							 ->setWebsiteIds(array(1))
							 ->setStatus(Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
							 ->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE)
							 ->setTaxClassId(0);
					$sProduct->setPrice($row['Trade Price']);
					$stockData = array(   
								'manage_stock' => 1,
								'is_in_stock' => $row['Stock'],
								'qty' => $row['StockLevel'],
								'use_config_manage_stock' => 0
							);
					$sProduct->setStockData($stockData);
					
					$categories = array();
					$main_category_id = $this->fetchMainCategoryId();
					array_push($categories,$main_category_id);
					if($row['Range'] != '' || $row['Range'] != NULL){
						$sub_cat_id = $this->fetchSubCategoryId($main_category_id,$row['Range']);
						array_push($categories,$sub_cat_id);
					}
					
					$sProduct->setCategoryIds($categories);
						
					$optionData = array(); 
					if(isset($row['materials']) && $row['materials']!=""){
						
						$optionId = $this->addAttributeValue('materials',$row['materials']);
						//exit($optionId);
						
						$sProduct->setData('materials', $optionId);
						array_push(						
							$optionData,
							array(
								"attr_code" => 'materials',
								"attr_id" => $attrCodeData['materials'], // i have used the hardcoded attribute id of attribute size, you must change according to your store
								"value" => $optionId,
								"label" => $row['materials'],
							)
						);
					}
					//$sProduct->setUrlKey($row['handle']);
					$url_string = str_replace(' ','_',$row['Product Name']); 
					$sProduct->setUrlKey($url_string."-".$urlCounter);
					$urlCounter++;
					$sProduct->save();

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
	
	public function createcatgAction(){
		
		$path = Mage::getBaseDir('media'). '/' ."sample.csv";
		$io = new Varien_Io_File();
		$main_cat = '';
		if ($io->fileExists($path))
		{
			$io->streamOpen($path, 'r');	
			$header = $io->streamReadCsv();
			$i = 0;
			while (($row = $io->streamReadCsv()) !== false){
				$row = array_combine($header, $row);
				//print_r($row);exit;
				/* check category exists or not and if not create it code start here */
				$cat = Mage::getResourceModel('catalog/category_collection')->addFieldToFilter('name', $row['Category'])->addAttributeToFilter('level',2);
				$category_exists = ( $cat->getFirstItem()->getEntityId() ) ?  $cat->getFirstItem()->getEntityId()  : 0 ;
				$inserted_category_id = 0;
				if(!$category_exists)
				{
					try{
						$main_cat[$i]['main_cat'] = $row['Category'];
					    $category = Mage::getModel('catalog/category');
					    $category->setName($row['Category']);
					    $category->setUrlKey(str_replace(' ', '_' , strtolower($row['Category'])));
					    $category->setIsActive(1);
					    $category->setDisplayMode('PRODUCTS');
					    $category->setIsAnchor(1); //for active achor
					    $parentCategory = Mage::getModel('catalog/category')->load(2);
					    $category->setPath($parentCategory->getPath());
					    $category->save();
					    $inserted_category_id = Mage::getResourceModel('catalog/category_collection')->addFieldToFilter('name', $row['Category']);
					} catch(Exception $e) {
					    var_dump($e);
					}
					
				}
			
					$sub_category_parent = ($category_exists) ? $category_exists : $inserted_category_id ;
					/* check if sub category means range exits or not and if not craete code start here */
					if($row['Range'] != '' || $row['Range'] != NULL)
					{
						$insert_sub_cate = 0;
						
					
						
						/* Load category by id*/
						$cat1 = Mage::getResourceModel('catalog/category_collection')->addFieldToFilter('name', $row['Range'])->addAttributeToFilter('parent_id',$sub_category_parent);
						$category_exists1 = ( $cat1->getFirstItem()->getEntityId() ) ?  $cat1->getFirstItem()->getEntityId()  : 0 ;
						echo "<br/>".$category_exists1."<br/>";
						if(!$category_exists1)
						{
							$insert_sub_cate = 1;
						}


						//$sub_category = ( $cat1->getFirstItem()->getEntityId() ) ?  $cat1->getFirstItem()->getEntityId()  : 0 ;
						if($insert_sub_cate)
						{
							try{
								$main_cat[$i]['main_cat'] = $row['Range'];
								$category1= Mage::getModel('catalog/category');
								$category1->setName($row['Range']);
								$category1->setUrlKey(str_replace(' ', '_' , strtolower($row['Range'])));
								$category1->setIsActive(1);
								$category1->setDisplayMode('PRODUCTS');
								$category1->setIsAnchor(1); //for active achor
								$parentCategory1 = Mage::getModel('catalog/category')->load($sub_category_parent);
								$category1->setPath($parentCategory1->getPath());
								$category1->save();
							} catch(Exception $e) {
								var_dump($e);
							}
						}
						echo "<br/>".$category_exists1."<br/>";
					    
					}
					/* check if sub category means range exits or not and if not craete code ends  here */
				/* check category exists or not and if not create it code end   here */
				$i++;
			}
		}else{
			echo "file not read";
		}
		//print_r($main_cat);
		echo "file executed";

	}
	


	public function createAttributeOptionAction(){
		
		$path = Mage::getBaseDir('media'). '/' ."allproducts-119295.csv";
		$io = new Varien_Io_File();
		$main_cat = '';
		if ($io->fileExists($path))
		{
			$io->streamOpen($path, 'r');	
			$header = $io->streamReadCsv();
			$i = 0;
			while (($row = $io->streamReadCsv()) !== false){
				$row = array_combine($header, $row);
				//print_r($row);exit;
				/* check category exists or not and if not create it code start here */
				$cat = Mage::getResourceModel('catalog/category_collection')->addFieldToFilter('name', $row['Category'])->addAttributeToFilter('level',2);
				$category_exists = ( $cat->getFirstItem()->getEntityId() ) ?  $cat->getFirstItem()->getEntityId()  : 0 ;
				$inserted_category_id = 0;
				if(!$category_exists)
				{
					try{
						$main_cat[$i]['main_cat'] = $row['Category'];
					    $category = Mage::getModel('catalog/category');
					    $category->setName($row['Category']);
					    $category->setUrlKey(str_replace(' ', '_' , strtolower($row['Category'])));
					    $category->setIsActive(1);
					    $category->setDisplayMode('PRODUCTS');
					    $category->setIsAnchor(1); //for active achor
					    $parentCategory = Mage::getModel('catalog/category')->load(2);
					    $category->setPath($parentCategory->getPath());
					    $category->save();
					    $inserted_category_id = Mage::getResourceModel('catalog/category_collection')->addFieldToFilter('name', $row['Category']);
					} catch(Exception $e) {
					    var_dump($e);
					}
					
				}
			
					$sub_category_parent = ($category_exists) ? $category_exists : $inserted_category_id ;
					/* check if sub category means range exits or not and if not craete code start here */
					if($row['Range'] != '' || $row['Range'] != NULL)
					{
						$insert_sub_cate = 0;
						
					
						
						/* Load category by id*/
						$cat1 = Mage::getResourceModel('catalog/category_collection')->addFieldToFilter('name', $row['Range'])->addAttributeToFilter('parent_id',$sub_category_parent);
						$category_exists1 = ( $cat1->getFirstItem()->getEntityId() ) ?  $cat1->getFirstItem()->getEntityId()  : 0 ;
						echo "<br/>".$category_exists1."<br/>";
						if(!$category_exists1)
						{
							$insert_sub_cate = 1;
						}


						//$sub_category = ( $cat1->getFirstItem()->getEntityId() ) ?  $cat1->getFirstItem()->getEntityId()  : 0 ;
						if($insert_sub_cate)
						{
							try{
								$main_cat[$i]['main_cat'] = $row['Range'];
								$category1= Mage::getModel('catalog/category');
								$category1->setName($row['Range']);
								$category1->setUrlKey(str_replace(' ', '_' , strtolower($row['Range'])));
								$category1->setIsActive(1);
								$category1->setDisplayMode('PRODUCTS');
								$category1->setIsAnchor(1); //for active achor
								$parentCategory1 = Mage::getModel('catalog/category')->load($sub_category_parent);
								$category1->setPath($parentCategory1->getPath());
								$category1->save();
							} catch(Exception $e) {
								var_dump($e);
							}
						}
						echo "<br/>".$category_exists1."<br/>";
					    
					}
					/* check if sub category means range exits or not and if not craete code ends  here */
				/* check category exists or not and if not create it code end   here */
				$i++;
			}
		}else{
			echo "file not read";
		}
		print_r($main_cat);
		echo "file executed";die;

	}


	public function deleteAction(){
		Mage::app()->setCurrentStore(Mage::getModel('core/store')->load(Mage_Core_Model_App::ADMIN_STORE_ID));
		 
		$resource = Mage::getSingleton('core/resource');
		$db_read = $resource->getConnection('core_read');
		 
		$categories = $db_read->fetchCol("SELECT entity_id FROM " . $resource->getTableName("catalog_category_entity") . " WHERE entity_id>1 ORDER BY entity_id DESC");
		foreach ($categories as $category_id) {
			echo $category_id." delete this category"."<br/>";
		    try {
		        Mage::getModel("catalog/category")->load($category_id)->delete();
		    } catch (Exception $e) {
		        echo $e->getMessage() . "\n";
		    }
		}
		die;
	}
	
	public function testAction(){
		/*$cat = Mage::getResourceModel('catalog/category_collection')->addFieldToFilter('name', 'Female')->addAttributeToFilter('level',2);
		$category_exists = ( $cat->getFirstItem()->getEntityId() ) ?  $cat->getFirstItem()->getEntityId()  : 0 ;
        echo $category_exists;die;*/
        $SKU = "N0606";
$productid = Mage::getModel('catalog/product')->getIdBySku(trim($SKU));
echo $productid;die;
	}
	
}