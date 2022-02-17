<?php

include('Sums_Common.php');
/**
 * Controller Class for Sums Quotation
 * 
 */
class Quotation extends Sums_Common
{
	
	var $appId = 1;
	function init()
		{
		$this->load->helper(array('form', 'url','date','image'));
		$this->load->library('sums_quotation_client');
		}




	
	function createQuote($prodId=20)
		{
		
		
		
		$this->init();
		$data['sumsUserInfo'] = $this->sumsUserValidation();
		
		
		$quotationArray['sumsLoggedInUser'] = $data['sumsUserInfo']['userid'];
		$quotationArray['ClientId'] = $this->input->post('selectedUserId');
		$quotationArray['CurrencyId'] = $this->input->post('currencySelected');
		$quotationArray['CreatedBy'] = $this->input->post('quotationCreator');
		$quotationArray['TotalPrice'] = $this->input->post('totalPrice');
		$quotationArray['TotalDiscount'] = $this->input->post('totalDiscount');
		$quotationArray['TotalBasePrice'] = $this->input->post('totalBasePrice');
		$quotationArray['ServiceTax'] = $this->input->post('serviceTax');
		$quotationArray['NetAmount'] = $this->input->post('netAmount');
		$quotationArray['RoundOffAmount'] = $this->input->post('roundOffAmount');
		$quotationArray['FinalSalesAmount'] = $this->input->post('finalSalesAmount');
		$quotationArray['serviceTaxPercentage'] = $this->input->post('serviceTaxVal');
		$countrycounter = $this->input->post('countrycounter');

		if($quotationArray['CurrencyId'] == 2 && $countrycounter == 'true')
		{
			$quotationArray['serviceTaxPercentage'] = 0;
		}
		else
		{
			$quotationArray['serviceTaxPercentage'] = 12.36;
		}
		$quotationArray['ServiceTax'] = (($quotationArray['TotalBasePrice'] * $quotationArray['serviceTaxPercentage'])/100);
		
		$this->load->library('sums_quotation_client');
		$objQuotation = new Sums_Quotation_client();
		
		
		$quotationProductsMap = array();
		$derivedProductIds = $this->input->post('selectedDeriveds');
	
	
		foreach ($derivedProductIds as $dids)
		{
			array_push($quotationProductsMap,array(array(
				'DerivedProductId'=>$dids,
				'Quantity'=>$this->input->post('quantity_'.$dids),
				'Discount'=>$this->input->post('discount_'.$dids)
			),'struct'));
		}
		
		
		
		$quotationChanged = true;
		if ($this->input->post('UIQuotationId'))
		{
			$quotationChanged = false;
			$quotationArray['UIQuotationId'] = $this->input->post('UIQuotationId');
			$quotation = $objQuotation->getQuotation($this->appId,$quotationArray['UIQuotationId']); 
			
			foreach ($quotationArray as $key=>$vals)
			{
				if ($quotation['QuotationDetails'][$key]!=$vals)
				{
					$quotationChanged = true;
				}
			}
			
			
			foreach ($derivedProductIds as $dids)
			{
				if (in_array($dids,array_keys($quotation['QuotationProducts'])))
				{
					if( ($quotation['QuotationProducts'][$dids]['Quantity']!=$this->input->post('quantity_'.$dids)) || $quotation['QuotationProducts'][$dids]['Discount']!=$this->input->post('discount_'.$dids) )
					{
						$quotationChanged = true;
						break;
					}
				}
			}
			
			
		}
		
		
		
		error_log_shiksha(print_r($quotationArray,true));
		if ($quotationChanged)
		{
			$response = $objQuotation->addQuotation($this->appId,$quotationArray,$quotationProductsMap);
			$data['pageInfo']['id'] = $response['UIQuotationId'];
		}
		else
		{
			$data['pageInfo']['id'] = $this->input->post('UIQuotationId');
		}
		
		$data['pageInfo']['type'] = "add";
		
		
		
		if (array_key_exists('UIQuotationId',$quotationArray)) {
			$data['pageInfo']['type'] = "edit";
		}
		
		
		
		$data['pageInfo']['product'] = "Quotation";
		
		
		
		$this->load->library('sums_manage_client');	
		$manObj = new Sums_Manage_client();		
		$data['branchList'] = $manObj->getBranches($this->appId);	
		$data['SalesUsers'] = $manObj->getSumsUsers($this->appId);
		
								
		if ($this->input->post('Transaction') && $this->input->post('Transaction')!=-1) {
		
			$data['Quotation'] = $objQuotation->getQuotation($this->appId,$quotationArray['UIQuotationId']);
			$data['TotalPrice'] = $data['Quotation']['QuotationDetails']['FinalSalesAmount'];
			$data['viewTrans'] = 0;	
			$this->load->library('enterprise_client');
			$objent = new Enterprise_client();
			$data['ClientDetails'] = $objent->getEnterpriseUserDetails($this->appId,$data['Quotation']['QuotationDetails']['ClientId']);
			$data['prodId'] = $prodId;	
		
			$this->load->view('sums/paymentDetails',$data);
		
		}
		
		
		
		
		else {
			$data['prodId'] = 16;
			$data['quoteType'] = 'standard';
			$this->load->view('sums/submitSuccess',$data);
		}
		}
	
	
	
	
	
	function MakePartPayment()
		{
		$this->init();
		$this->load->library('sums_quotation_client');
		$objQuotation = new Sums_Quotation_client();
		$requestArray = array();
		$requestArray['Cheque_No'] = $this->input->post('Cheque_No_MakePayment');
		$requestArray['Cheque_City'] = $this->input->post('Cheque_City_MakePayment');
		$requestArray['Cheque_Bank'] = $this->input->post('Cheque_Bank_MakePayment');
		$requestArray['Cheque_Receiving_Date'] = $this->input->post('Cheque_Receiving_Date_MakePayment');
		$requestArray['Cheque_DD_Comments'] = $this->input->post('Cheque_DD_Comments_MakePayment');
		$requestArray['isPaid'] = 'paid';
		$requestArray['Payment_Mode'] = $this->input->post('Payment_Mode_MakePayment');
		$paymentId = $this->input->post('Payment_Id_MakePayment');
		$partNumber = $this->input->post('partNumber_MakePayment') + 1; //In database part number start from 1.
		$resultOfUpdate = $objQuotation->updatePaymentInfo($appId,$requestArray,$paymentId,$partNumber);
		echo $resultOfUpdate;
		}
	
	
	
	
	function viewTransaction($UIQuotationId=-1,$prodId=20)
		{
		$this->init();
		$data['sumsUserInfo'] = $this->sumsUserValidation();
		
		$this->load->library(array('sums_quotation_client','register_client'));
		$objQuotation = new Sums_Quotation_client();
		if($UIQuotationId == -1){
			$UIQuotationId = $this->input->post('UIQuotationId');
		}	
		
		if($UIQuotationId != '')
		{
			$transactionData = $objQuotation->getPaymentDetails($UIQuotationId);
			
			$data['Payment'] = array();
			for($i=0;$i<count($transactionData[0]['paymentDetails']);$i++)
			{
				$Result = $transactionData[0]['paymentDetails'][$i];
				$data['Payment'][$i] = array();
				$data['Payment'][$i]['Payment_Id'] = $Result['Payment_Id'];
				$data['Payment'][$i]['Cheque_No'] = $Result['Cheque_No'];
				$data['Payment'][$i]['Cheque_Date'] = $Result['Cheque_Date'];	
				$data['Payment'][$i]['Cheque_City'] = $Result['Cheque_City'];
				$data['Payment'][$i]['Cheque_Bank'] = $Result['Cheque_Bank'];
				$data['Payment'][$i]['Cheque_Receiving_Date'] = $Result['Cheque_Receiving_Date'];
				$data['Payment'][$i]['Amount_Received'] = $Result['Amount_Received'];
				$data['Payment'][$i]['TDS_Amount'] = $Result['TDS_Amount'];
				$data['Payment'][$i]['Cheque_DD_Comments'] = $Result['Cheque_DD_Comments'];
				$data['Payment'][$i]['isPaid'] = $Result['isPaid'];
				$data['Payment'][$i]['Payment_Mode'] = $Result['Payment_Mode'];
			}
			$transactionType = $transactionData[0]['transactionResult'][0];
			$data['Transaction'] = array();
			$data['Transaction']['Sale_Type'] = $transactionType['Sale_Type'];	
			$data['TotalPrice'] = $transactionType['TotalTransactionPrice'];
			$clientId = $transactionType['SalesBy'];
			$registerClient = new Register_client();
			$userData =  $registerClient->userdetail($this->appId,$clientId);
			$data['SalesBy'] = $userData[0]['displayname'];
			$data['ClientDetails'] = array();
			$data['ClientDetails']['contactAddress'] = $transactionType['Address'];	
			$data['ClientDetails']['country'] = $transactionType['Country'];
			$data['ClientDetails']['cityName'] = $transactionType['City'];
			$data['ClientDetails']['pincode'] = $transactionType['Pincode'];	
			$data['ClientDetails']['mobile'] = $transactionType['Phone_No'];	
			$data['ClientDetails']['email'] = $transactionType['Email_Address'];
			$data['viewTrans'] = 1;
			$data['UIQuotationId'] = $UIQuotationId;
			$data['prodId'] = $prodId;
			$this->load->view('sums/paymentDetails',$data);	
		}
		
		}
	
	function searchQuotation($prodId=20)
		{
		$this->init();
		$data['sumsUserInfo'] = $this->sumsUserValidation(3,'16');
		$data['prodId'] = $prodId;
		$this->load->view('sums/quotationSelect',$data);
		}
	
	function getUsersForQuotation($prodId=20)
		{
		error_log_shiksha("RECEIVED POST DATA: ".print_r($_POST,true));
		$this->init();
		$response['sumsUserInfo'] = $this->sumsUserValidation();
		$request['email'] = $this->input->post('email',true);
		$request['displayname'] = $this->input->post('displayname',true);
		$request['collegeName'] = $this->input->post('collegename',true);
		$request['contactName'] = $this->input->post('contactName',true);
		$request['contactNumber'] = $this->input->post('contactNumber',true);
		$request['clientId'] = $this->input->post('clientId',true);
		$request['quotationId'] = $this->input->post('quotationId',true);
		$request['quotationValue'] = $this->input->post('quotationValue',true);
		$request['quotationCreater'] = $this->input->post('quotationCreater',true);
		$objQuotation = new Sums_Quotation_client();
		$response['users'] =  $objQuotation->searchQuotation($this->appId,$request);
		$this->load->library('sums_product_client');
		$objProduct = new Sums_product_client();
		$i=0;
		for($i=0;$i<count($response['users']);$i++)
		{
			$quotationDetails=$objQuotation->getQuotation(1,$response['users'][$i]['UIQuotationId']);
			$products='';
			foreach($quotationDetails['QuotationProducts'] as $productDetails)
			{
				$derivedProductProperties=$objProduct->getDerivedProductProperties($productDetails['DerivedProductId']);
				foreach($derivedProductProperties as $derivedProduct)
				{
					$products=($products=='')?$products.$productDetails['Quantity']." ".$derivedProduct['DerivedProductName']:$products.", ".$productDetails['Quantity']." ".$derivedProduct['DerivedProductName'];
				}
			}
			$response['users'][$i]['ProductSelected']=$products;
		}
		$response['prodId'] = $prodId;
		$this->load->view('sums/quotations',$response);
		
		}
	
	function getQuotationHistory($quotationUId)
		{
		$this->init();
		$response['sumsUserInfo'] = $this->sumsUserValidation();
		$objQuotation = new Sums_Quotation_client();
		$response['users'] =  $objQuotation->getQuotationHistory($quotationUId);
		$this->load->library('sums_product_client');
		$objProduct = new Sums_product_client();
		$i=0;
		for($i=0;$i<count($response['users']);$i++)
		{
			$quotationDetails=$objQuotation->getQuotationById(1,$response['users'][$i]['QuotationId']);
			$products='';
			foreach($quotationDetails['QuotationProducts'] as $productDetails)
			{
				$derivedProductProperties=$objProduct->getDerivedProductProperties($productDetails['DerivedProductId']);
				foreach($derivedProductProperties as $derivedProduct)
				{
					$products=($products=='')?$products.$productDetails['Quantity']." ".$derivedProduct['DerivedProductName']:$products.", ".$productDetails['Quantity']." ".$derivedProduct['DerivedProductName'];
				}
			}
			$response['users'][$i]['ProductSelected']=$products;
		}
		
		/*echo '<pre>';
		 echo(print_r($response,true));
		 echo '</pre>'; */
		$this->load->view('sums/quotationHistory',$response);
		}
	
	function editQuotation($UIQuotationId,$transaction=-1,$quoteType='ACTIVE',$prodId=16)
		{
		$this->init();
		$data['sumsUserInfo'] = $this->sumsUserValidation();
		if ($transaction==1)
		{
			$data['sumsUserInfo'] = $this->sumsUserValidation(6,'16');
		}
		else if ($transaction==2)
		{
			$data['sumsUserInfo'] = $this->sumsUserValidation(16,'16');
		}
		else
		{
			$data['sumsUserInfo'] = $this->sumsUserValidation(3,'16');
		}
		$this->load->library('sums_quotation_client');
		$this->load->library(array('register_client','sums_product_client'));
		$objSumsProduct = new Sums_Product_client();
		
		$objQuot = new Sums_Quotation_client();
		$data['Quotation']= $objQuot->getQuotation($this->appId,$UIQuotationId,$quoteType);
		
		$regObj = new Register_client();
		$ud = $regObj->userdetail($this->appId,$data['Quotation']['QuotationDetails']['ClientId']);
		
		$data['selectedUserDetails'] = $ud[0];
		$data['selectedUserDetails']['userId'] = $data['Quotation']['QuotationDetails']['ClientId'];
		$data['countrycounter'] = 'false';
		

		if(($data['selectedUserDetails']['country'] != 'India') && ($data['selectedUserDetails']['country']) !=2)
		{
			$data['countrycounter'] = 'true';

		}
				
		$data['products'] = $objSumsProduct->getDerivedProducts();
		$data['currencies'] = $objSumsProduct->getAllCurrency();
		$data['parameters'] = $objSumsProduct->getAllSumsParameters();
		$manObj = new Sums_Manage_client();
		$data['quoteUsers'] = $manObj->getSumsUsers($this->appId);
		if (isset($transaction))
		{
			$data['Transaction'] = $transaction;
		}
		$data['prodId'] = $prodId;


		$this->load->view('sums/productSelect',$data);
		}
	
	function createCustomizedQuote($selectedUserId,$prodId=16)
		{
		$this->init();
		$data['sumsUserInfo'] = $this->sumsUserValidation(4,'16');
		
		$this->load->library(array('register_client','sums_product_client'));
		$objSumsProduct = new Sums_Product_client();
		
		//$this->load->library('sums_quotation_client');
		//$objQuot = new Sums_Quotation_client();
		//$data['Quotation']= $objQuot->getQuotation($this->appId,$UIQuotationId);
		
		$regObj = new Register_client();
		$ud = $regObj->userdetail($this->appId,$selectedUserId);
		
		$data['selectedUserDetails'] = $ud[0];
		$data['selectedUserDetails']['userId'] = $selectedUserId;
		$data['countrycounter'] = 'false';
		

		if(($data['selectedUserDetails']['country'] != 'India') && ($data['selectedUserDetails']['country']) !=2)
		{
			$data['countrycounter'] = 'true';

		}
		$data['DerivedProductProperties'] = $objSumsProduct->getAllDerivedProductProperties($this->appId);
		$data['BaseProducts'] = $objSumsProduct->getBaseProductList($this->appId);
		$data['currencies'] = $objSumsProduct->getAllCurrency($this->appId);
		$data['parameters'] = $objSumsProduct->getAllSumsParameters();
		$manObj = new Sums_Manage_client();
		$data['quoteUsers'] = $manObj->getSumsUsers($this->appId);
		$data['prodId'] = $prodId;
		$this->load->view('sums/customizedQuote',$data);
			
		}
	
	function customizedQuoteSubmit($prodId=20)
		{
		$this->init();
		$data['sumsUserInfo'] = $this->sumsUserValidation();
		
		$totalProduct = $this->input->post('totalProduct');
		$totalCurrency = $this->input->post('totalCurrency');
		$totalProperties = $this->input->post('totalProperties');
		
		$propertyArray = array();
		
		$derivedProperties = $this->input->post('DerivedProductProp');
		$derivedPropertiesValue = $this->input->post('DerivedProductPropValue');
		$countrycounter = $this->input->post('countrycounter');
		
		
		
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
		$currency = $this->input->post('currencySelected');
		
		for($i=0;$i<$totalCurrency;$i++)
		{
			$derivedProductPrice[$currency] = array(array(
				"ManagementPrice"=>$this->input->post('totalBasePrice'),
				"SuggestedPrice"=>$this->input->post('totalPrice'),
			),'struct');
		}
		
		$derivedInfo['DerivedProductName'] = "";
		$derivedInfo['DerivedProductDescription'] = "Customized derived-product !!";
		$derivedInfo['DerivedProductType'] = "CUSTOMIZED";
		$derivedInfo['DerivedProductCategoryId'] = "9";
		$this->load->library('sums_product_client');
		$objSumsProduct = new Sums_Product_client();
		$BaseProds = $objSumsProduct->getBaseProductList($this->appId);
		
		$productsInfo = array();
		for($i=0;$i<$totalCurrency;$i++)
		{
			$sugPrice = $this->input->post('BaseProdSuggestedPrice');
			$salePrice = $this->input->post('BaseProdSalePrice');
			//$manPrice = $this->input->post('manPrice'.$i);
			for($j=0;$j<$totalProduct;$j++)
			{
				array_push($productsInfo,array(array(
					"BaseProductId" => $baseProducts[$j],
					"BaseProdQuantity"=>$baseQuantity[$j],
					"BaseProdDurationInDays"=>$baseDuration[$j],
					"CurrencyId"=>$currency,
					"SuggestedPrice"=>$sugPrice[$j],
					"ManagementPrice"=>$salePrice[$j]
				),'struct'));
				
				foreach($BaseProds as $bp){
					if($bp['BaseProductId'] == $baseProducts[$j]){
						$derivedInfo['DerivedProductName'] .=$bp['BaseProdCategory'].'-'.$bp['BaseProdSubCategory'].':'.$baseQuantity[$j].'<br/>';
					}else{
						continue;
					}
				}
			}
		}
		
		/*
		 echo "<pre>";print_r($productsInfo);echo "</pre>";
		 echo "<pre>";print_r($derivedInfo);echo "</pre>";
		 echo "<pre>";print_r($propertyArray);echo "</pre>";
		 echo "<pre>";print_r($derivedProductPrice);echo "</pre>";
		 */
		
		$this->load->library('sums_product_client');
		$objProduct = new Sums_Product_client();
		$response = $objProduct->submitDerivedProductForm($derivedInfo,$propertyArray,$derivedProductPrice,$productsInfo);
		//echo "<pre>";print_r($response);echo "</pre>";
		$data['pageInfo']['type'] = "add";
		$data['pageInfo']['product'] = "Derived Product";
		$data['pageInfo']['id'] = $response['Added Derived Product'];
		
		//echo "<pre>";print_r($data);echo "</pre>";
		
		
		$quotationArray = array();
		$quotationArray['sumsLoggedInUser'] = $data['sumsUserInfo']['userid'];
		$quotationArray['QuoteType'] = $derivedInfo['DerivedProductType'];
		$quotationArray['ClientId'] = $this->input->post('selectedUserId');
		$quotationArray['CurrencyId'] = $this->input->post('currencySelected');
		$quotationArray['CreatedBy'] = $this->input->post('quotationCreator');
		$quotationArray['TotalPrice'] = $this->input->post('totalPrice');
		$quotationArray['TotalDiscount'] = $this->input->post('totalDiscount');
		$quotationArray['TotalBasePrice'] = $this->input->post('totalBasePrice');
		$quotationArray['ServiceTax'] = $this->input->post('serviceTax');
		$quotationArray['NetAmount'] = $this->input->post('netAmount');
		$quotationArray['RoundOffAmount'] = $this->input->post('roundOffAmount');
		$quotationArray['FinalSalesAmount'] = $this->input->post('finalSalesAmount');
		$quotationArray['serviceTaxPercentage'] = $this->input->post('serviceTaxVal');
		
		if($quotationArray['CurrencyId'] == 2 && $countrycounter == 'true')
		{
			$quotationArray['serviceTaxPercentage'] = 0;
		}
		else
		{
			$quotationArray['serviceTaxPercentage'] = 12.36;
		}
		$quotationArray['ServiceTax'] = (($quotationArray['TotalBasePrice'] * $quotationArray['serviceTaxPercentage'])/100);
		
		if ($this->input->post('UIQuotationId'))
		{
			$quotationArray['UIQuotationId'] = $this->input->post('UIQuotationId');
		}
		//echo "<pre>";print_r($quotationArray);echo "</pre>";
		
		$quotationProductsMap = array();
		$derivedProductId = $data['pageInfo']['id'];
		
		array_push($quotationProductsMap,array(array(
			'DerivedProductId'=>$derivedProductId,
			'Quantity'=>1,
			'Discount'=>0
		),'struct'));
		
		$this->load->library('sums_quotation_client');
		$objQuotation = new Sums_Quotation_client();
		$response = $objQuotation->addQuotation($this->appId,$quotationArray,$quotationProductsMap);
		$data['pageInfo']['type'] = "add";
		if (array_key_exists('UIQuotationId',$quotationArray)) {
			$data['pageInfo']['type'] = "edit";
		}
		$data['pageInfo']['product'] = "Quotation";
		$data['pageInfo']['id'] = $response['UIQuotationId'];
		if ($this->input->post('Transaction') && $this->input->post('Transaction')!=-1) {
			$data['Quotation'] = $objQuotation->getQuotation($this->appId,$response['UIQuotationId']);
			$data['TotalPrice'] = $data['Quotation']['QuotationDetails']['FinalSalesAmount'];
			$data['viewTrans'] = 0;
			$this->load->library('enterprise_client');
			$objent = new Enterprise_client();
			$data['ClientDetails'] = $objent->getEnterpriseUserDetails($this->appId,$data['Quotation']['QuotationDetails']['ClientId']);
			$this->load->library('sums_manage_client');
			$manObj = new Sums_Manage_client();
			$data['branchList'] = $manObj->getBranches($this->appId);	
			$data['SalesUsers'] = $manObj->getSumsUsers($this->appId);
			$data['prodId'] = $prodId;
			$data['validationcheck'] = 'check';
			$validitycheckarray=array(
			'0' => '43033',	
			'1' => '2492',	
			);	
			foreach($validitycheckarray as $key=>$value){
		
			if($data['sumsUserInfo']['validity'][0]['userid'] == $value )
			{
				$data['validationcheck'] = 'uncheck';
	
			}
			}
			$this->load->view('sums/paymentDetails',$data);
		}
		else {
			$data['prodId'] = 16;
			$data['quoteType'] = 'customized';
			$this->load->view('sums/submitSuccess',$data);
		}
		}
	
	function editCustomizedQuote($UIQuotationId,$transaction=-1,$quoteType='ACTIVE',$prodId=16)
		{
		$this->init();
		// $transaction =1 Create Transaction
		// $transaction =2 View Transaction details/Payment Details
		if($transaction==1)
		{
			$data['sumsUserInfo'] = $this->sumsUserValidation(6,'16');
		}
		else if ($transaction==2)
		{
			$data['sumsUserInfo'] = $this->sumsUserValidation(16,'16');
		}
		else
		{
			$data['sumsUserInfo'] = $this->sumsUserValidation(5,'16');
		}
		$this->load->library('sums_quotation_client');
		$this->load->library(array('register_client','sums_product_client'));
		$objSumsProduct = new Sums_Product_client();
		$objQuot = new Sums_Quotation_client();
		$data['Quotation']= $objQuot->getQuotation($this->appId,$UIQuotationId,$quoteType);
		
		
		$regObj = new Register_client();
		$ud = $regObj->userdetail($this->appId,$data['Quotation']['QuotationDetails']['ClientId']);
		
		$data['selectedUserDetails'] = $ud[0];
		$data['selectedUserDetails']['userId'] = $data['Quotation']['QuotationDetails']['ClientId'];
		
		$data['countrycounter'] = 'false';
		

		if(($data['selectedUserDetails']['country'] != 'India') && ($data['selectedUserDetails']['country']) !=2)
		{
			$data['countrycounter'] = 'true';
		}
		
		$derivedProdId;
		foreach($data['Quotation']['QuotationProducts'] as $key => $val){
			$derivedProdId = $key;
		}
		$data['derivedProdId'] = $derivedProdId;
		$data['PropertiesOfThisDerived'] = $objSumsProduct->getPropertiesOfThisDerived($derivedProdId);
		$data['baseProdsOfDerived'] = $objSumsProduct->getAllBaseProdsForDerived($derivedProdId);
		
		$data['DerivedProductProperties'] = $objSumsProduct->getAllDerivedProductProperties($this->appId);
		$data['BaseProducts'] = $objSumsProduct->getBaseProductList($this->appId);
		$data['currencies'] = $objSumsProduct->getAllCurrency($this->appId);
		$data['parameters'] = $objSumsProduct->getAllSumsParameters();
		$manObj = new Sums_Manage_client();
		$data['quoteUsers'] = $manObj->getSumsUsers($this->appId);
		
		$data['editcustomizevar'] = 'true';
		
		//if (isset($transaction) && $transaction==1)
		if (isset($transaction))
		{
			$data['Transaction'] = $transaction;
		}
		$data['prodId'] = $prodId;
			
		
		
		$this->load->view('sums/customizedQuote',$data);
		}
	
	function fetchBranchesForExecutive($execId)
		{
		$this->init();
		$response['sumsUserInfo'] = $this->sumsUserValidation();
		$objQuotation = new Sums_quotation_client();
		$response = $objQuotation->fetchBranchesForExecutive($this->appId,$execId);
		$data['branches'] = $response;
		$this->load->view('sums/branchForExecutive',$data);
		}
	
	
}
?>
