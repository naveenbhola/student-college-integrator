<?php

include('Sums_Common.php');
/**
 * Controller Class for Sums Products
 * 
 */
class Product extends Sums_Common
{
	
	var $appId = 1;
	
	function init()
		{
		$this->load->library(array('sums_product_client'));
		}
	
	function addBaseProduct($prodId=18)
		{
		$this->init();
		$data['sumsUserInfo'] = $this->sumsUserValidation(7,'18');
		$objProduct = new Sums_Product_client();
		$data['BaseProductProperties'] = $objProduct->getAllBaseProductProperties($this->appId);
		$data['Currencies'] = $objProduct->getAllCurrency($this->appId);
		$data['prodId'] = $prodId;
		$this->load->view('sums/addBaseProduct',$data);
		}


      function submitAddBase($prodId=18)
		{
		$this->init();
		//echo "<pre>";print_r($_POST); echo "</pre>";
		$data['sumsUserInfo'] = $this->sumsUserValidation();
		$totalProperties = $this->input->post('totalProperties');
		$totalBasePriceIndex = $this->input->post('totalBasePriceIndex');
		
		$baseProductArray['BaseProdCategory'] = $this->input->post('BaseProdCategory');
		$baseProductArray['BaseProdSubCategory'] = $this->input->post('BaseProdSubCategory');
		$baseProductArray['Description'] = $this->input->post('Description');
		
		$propertyArray = array();
		$propertyArray['1'] = $this->input->post('Base_Quantity');
		$propertyArray['2'] = $this->input->post('Base_Duration_Days');
		$propertyArray['8'] = $this->input->post('Base_Duration_Type');
		
		
		$baseProperties = $this->input->post('BaseProductProp');
		$basePropertiesValue = $this->input->post('BaseProductPropValue');
		
		for($i=0;$i<$totalProperties;$i++)
		{
			if ($basePropertiesValue[$i]!="")
			{
				$propertyArray[$baseProperties[$i]] = $basePropertiesValue[$i];
			}
		}
		
		$currency = $this->input->post('currency');
		$rate = $this->input->post('Rate');
		$priceArray = array();
		
		for ($i=0;$i<=$totalBasePriceIndex;$i++)
		{
			$priceArray[$currency[$i]] = array(array(),'struct');
			$priceArray[$currency[$i]][0]['Rate'] = $rate[$i];
			$priceArray[$currency[$i]][0]['Index'] = array(array(),'struct');
			
			$quantity = $this->input->post('quantity'.$i);
			$duration = $this->input->post('duration'.$i);
			$discountC = $this->input->post('discountC'.$i);
			$discountE = $this->input->post('discountE'.$i);
			
			for ($j=0;$j<=$this->input->post('basePriceRows'.$i);$j++)
			{
				if (($duration[$j]!="" || $quantity[$j]!="") && $discountC[$j]!="" && $discountE[$j]!="")
				{
					array_push($priceArray[$currency[$i]][0]['Index'][0],array(array(
						"BaseProdDurationInDays" => $duration[$j],
						"MaxQuantity"=>$quantity[$j],
						"DiscountCoefficient"=>$discountC[$j],
						"DiscountExponentialFactor"=>$discountE[$j]),'struct'
					));
				}
			}
		}
		
		//echo "<pre>";print_r($propertyArray);echo "</pre>";
		//echo "<pre>";print_r($priceArray);echo "</pre>";
		$objProduct = new Sums_Product_client();
		$response = $objProduct->submitBaseProductForm($baseProductArray,$propertyArray,$priceArray);
		$data['pageInfo']['type'] = "add";
		$data['pageInfo']['product'] = "Base Product";
		$data['pageInfo']['id'] = $response['Added Base Product'];
		$data['prodId'] = $prodId;
		$this->load->view('sums/submitSuccess',$data);
		
		}
	

	
	
	
	function addDerivedProduct($prodId=18)
		{
		$this->init();
		$data['sumsUserInfo'] = $this->sumsUserValidation(10,'18');
		$objProduct = new Sums_Product_client();
		$data['DerivedProductProperties'] = $objProduct->getAllDerivedProductProperties($this->appId);
		$data['Currencies'] = $objProduct->getAllCurrency($this->appId);
		$data['BaseProducts'] = $objProduct->getBaseProductList($this->appId);
		$data['Categories'] = $objProduct->getCategoriesForDerived($this->appId);
		$data['prodId'] = $prodId;
		$this->load->view('sums/addDerivedProduct',$data);
		}
	
	function addDerivedSubmit($prodId=18)
		{
		//echo "<pre>";print_r($_POST);echo "</pre>";
		$this->init();
		$data['sumsUserInfo'] = $this->sumsUserValidation();
		$totalProduct = $this->input->post('totalProduct');
		$totalCurrency = $this->input->post('totalCurrency');
		$totalProperties = $this->input->post('totalProperties');
		
		$derivedInfo['DerivedProductName'] = $this->input->post('DerivedProductName');
		$derivedInfo['DerivedProductDescription'] = $this->input->post('Description');
		$derivedInfo['DerivedProductType'] = ($totalProduct==1)?"Simple":"Complex";
		$derivedInfo['DerivedProductCategoryId'] = $this->input->post('Category');
		
		$propertyArray = array();
		
		if ($this->input->post('online')) {
			$propertyArray['1']=$this->input->post('online');
		}
		if ($this->input->post('offline')) {
			$propertyArray['2']=$this->input->post('offline');
		}
		if ($this->input->post('customizable')) {
			$propertyArray['3'] = $this->input->post('customizable');
		}
		
		$derivedProperties = $this->input->post('DerivedProductProp');
		$derivedPropertiesValue = $this->input->post('DerivedProductPropValue');
		
		for($i=0;$i<$totalProperties;$i++)
		{
			if ($derivedPropertiesValue[$i]!="")
			{
				$propertyArray[$derivedProperties[$i]] = $derivedPropertiesValue[$i];
			}
		}
		
		$baseProducts = $this->input->post('BaseProductId');
		$baseQuantity = $this->input->post('BaseProductQuantity');
		$baseDuration = $this->input->post('BaseProductDuration');
		$currency = $this->input->post('currency');
		
		for($i=0;$i<$totalCurrency;$i++)
		{
			$derivedProductPrice[$currency[$i]] = array(array(
				"ManagementPrice"=>$this->input->post('manTotalPrice'.$i),
				"SuggestedPrice"=>$this->input->post('sugTotalPrice'.$i),
			),'struct');
		}
		
		$productsInfo = array();
		for($i=0;$i<$totalCurrency;$i++)
		{
			$sugPrice = $this->input->post('sugPrice'.$i);
			$manPrice = $this->input->post('manPrice'.$i);
			for($j=0;$j<$totalProduct;$j++)
			{
				array_push($productsInfo,array(array(
					"BaseProductId" => $baseProducts[$j],
					"BaseProdQuantity"=>$baseQuantity[$j],
					"BaseProdDurationInDays"=>$baseDuration[$j],
					"CurrencyId"=>$currency[$i],
					"SuggestedPrice"=>$sugPrice[$j],
					"ManagementPrice"=>$manPrice[$j],
				),'struct'));
			}
		}
		
		//echo "<pre>";print_r($productsInfo);echo "</pre>";
		//echo "<pre>";print_r($derivedInfo);echo "</pre>";
		//echo "<pre>";print_r($propertyArray);echo "</pre>";
		//echo "<pre>";print_r($derivedProductPrice);echo "</pre>";
		
		$objProduct = new Sums_Product_client();
		$response = $objProduct->submitDerivedProductForm($derivedInfo,$propertyArray,$derivedProductPrice,$productsInfo);
		$data['pageInfo']['type'] = "add";
		$data['pageInfo']['product'] = "Derived Product";
		$data['pageInfo']['id'] = $response['Added Derived Product'];
		$data['prodId'] = $prodId;
		$this->load->view('sums/submitSuccess',$data);
		
		
		}
	
	function getDurationType($baseProductId)
		{
		$this->init();
		$objProduct = new Sums_Product_client();
		$response = $objProduct->getDurationTypeForBase($baseProductId);
		print_r($response[0]['BasePropertyValue']);
		}
	
	function getSuggestedPrice()
		{
		$this->init();
		$request['BaseProductId'] = strip_tags($this->input->post('BaseProductId'));
		$request['CurrencyId'] = strip_tags($this->input->post('CurrencyId'));
		$request['BaseProdDurationInDays'] = strip_tags($this->input->post('Duration'));
		$request['MaxQuantity'] = strip_tags($this->input->post('Quantity'));
		$objProduct = new Sums_Product_client();
		$response['0'] = $objProduct->getSuggestedPrice($request);
		$response['1']= $this->input->post('id');
		echo json_encode($response);
		}
	
	function getAllDerivedProducts($prodId=18)
		{
		error_log("posted data for Get Products: ".print_r($_POST,true));
		$this->init();
		$this->load->library(array('register_client','sums_product_client'));
		$data['sumsUserInfo'] = $this->sumsUserValidation();
		$objSumsProduct = new Sums_Product_client();
		$data['products'] = $objSumsProduct->getAllDerivedProducts();
		$data['currencies'] = $objSumsProduct->getAllCurrency();
		$manObj = new Sums_Manage_client();
		$data['prodId'] = $prodId; // 18 Product
		//echo '<pre>';print_r($data);echo '</pre>';
		$this->load->view('sums/derivedEnableDisable',$data);
		}
	
	function updateDerivedProdStatus($prodId=18)
		{
		//echo '<pre>';print_r($_POST);echo '</pre>';
		$this->init();
		$data['sumsUserInfo'] = $this->sumsUserValidation(44,$prodId);
		$request['editingUserId'] = $data['sumsUserInfo']['userid'];
		
		$request['updateTypeDerived']= $this->input->post('updateTypeDerived');
		$derivedProductIds = $this->input->post('selectedDeriveds');
		$request['derivedProds'] = array(array(),'struct');
		foreach ($derivedProductIds as $dids)
		{
			array_push($request['derivedProds'][0],$dids);
			
		}
		
		
		//echo "<pre>";print_r($request);echo "</pre>";
		$this->load->library(array('sums_product_client'));
		$objSubs = new Sums_Product_client();
		$response =  $objSubs->updateDerivedProdStatus($this->appId,$request);
		error_log_shiksha("SS >> ".print_r($response,true)); 
		$data['result'] = $response;
		$data['prodId'] = $prodId;
		$data['type'] = $request['updateTypeDerived'];
		//echo "<pre>";print_r($data);echo "</pre>";
		$this->load->view('sums/updatePageDerived',$data);
		}
}

?>
