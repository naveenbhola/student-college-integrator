<?php
/**
 *  This Class contain Web-Sevice Functions Related to SUMS Product
 */
class Product_Server extends MX_Controller
{
	private $db_sums;
	private $db;
	
	function index()
		{
		$this->load->library('xmlrpc');
		$this->load->library('xmlrpcs');
		$this->load->library('listing_client');
		$this->load->helper('date');

		$config['functions']['getDerivedProducts'] = array('function'=>'Product_Server.getDerivedProducts');
		$config['functions']['sgetProductFeatures'] = array('function'=>'Product_Server.sgetProductFeatures');
		$config['functions']['sgetProductsForUser'] = array('function'=>'Product_Server.sgetProductsForUser');
		$config['functions']['sproductConsume'] = array('function'=>'Product_Server.sproductConsume');
		$config['functions']['getAllBaseProductProperties'] = array('function'=>'Product_Server.getAllBaseProductProperties');
		$config['functions']['getAllDerivedProductProperties'] = array('function'=>'Product_Server.getAllDerivedProductProperties');
		$config['functions']['getAllCurrency'] = array('function'=>'Product_Server.getAllCurrency');
		$config['functions']['sSubmitBaseProductForm'] = array('function'=>'Product_Server.sSubmitBaseProductForm');
		$config['functions']['sSubmitDerivedProductForm'] = array('function'=>'Product_Server.sSubmitDerivedProductForm');
		$config['functions']['addPriceIndex'] = array('function'=>'Product_Server.addPriceIndex');
		$config['functions']['addProperties'] = array('function'=>'Product_Server.addProperties');
		$config['functions']['sgetAllSumsParameters'] = array('function'=>'Product_Server.sgetAllSumsParameters');

		$config['functions']['getCategoriesForDerived'] = array('function'=>'Product_Server.getCategoriesForDerived');
		$config['functions']['sgetBaseProductList'] = array('function'=>'Product_Server.sgetBaseProductList');
		$config['functions']['sgetDurationTypeForBase'] = array('function'=>'Product_Server.sgetDurationTypeForBase');
		$config['functions']['sgetPriceSugestionForBase'] = array('function'=>'Product_Server.sgetPriceSugestionForBase');
		$config['functions']['sgetDerivedProductProperties'] = array('function'=>'Product_Server.sgetDerivedProductProperties');
		$config['functions']['sgetAllBaseProdsForDerived'] = array('function'=>'Product_Server.sgetAllBaseProdsForDerived');
		$config['functions']['sgetPropertiesOfThisDerived'] = array('function'=>'Product_Server.sgetPropertiesOfThisDerived');
		$config['functions']['sgetFreeDerivedId'] = array('function'=>'Product_Server.sgetFreeDerivedId');
		$config['functions']['sgetAllSubscriptionsForUser'] = array('function'=>'Product_Server.sgetAllSubscriptionsForUser');
		$config['functions']['getAllDerivedProducts'] = array('function'=>'Product_Server.getAllDerivedProducts');
		$config['functions']['supdateDerivedProdStatus'] = array('function'=>'Product_Server.supdateDerivedProdStatus');
		$config['functions']['getAllPseudoSubscriptionsForUser'] = array('function'=>'Product_Server.getAllPseudoSubscriptionsForUser');
        $config['functions']['sgetAllSubscriptionsForUserLDB'] = array('function'=>'Product_Server.sgetAllSubscriptionsForUserLDB');
		$config['functions']['getSalesPersonWiseClientList'] = array('function'=>'Product_Server.getSalesPersonWiseClientList');
        $config['functions']['getSalesDataByClientId'] = array('function'=>'Product_Server.getSalesDataByClientId');
        $config['functions']['getOldActiveSubscriptionClients'] = array('function'=>'Product_Server.getOldActiveSubscriptionClients');

		$args = func_get_args(); $method = $this->getMethod($config,$args);
		
		return $this->$method($args[1]);

		}
		
	private function setDBHandle($sums = FALSE)
	{
		if($sums) {
			$this->dbLibObj = DbLibCommon::getInstance('SUMS');
			$this->db_sums = $this->dbLibObj->getWriteHandle();
			return $this->db_sums;
		}
		else {
			$this->dbLibObj = DbLibCommon::getInstance('SUMSShiksha');
			$this->db = $this->dbLibObj->getWriteHandle();
			return $this->db;
		}
	}
		
	/**
	 * This function returns all active derived products
	 */
	function getDerivedProducts($request)
		{
		$parameters = $request->output_parameters();
		$this->setDBHandle(TRUE);

		$query = "select * from Derived_Product_Category where Status = 'live'";
		error_log_shiksha($query);
		$arrResults = $this->db_sums->query($query);


		foreach ($arrResults->result() as $row)
		{

			$response[$row->CategoryId]= array(array(),'struct');
			$response[$row->CategoryId][0]['CategoryName'] = $row->CategoryName;

			$queryProducts = "select * from Derived_Products D,Derived_Prod_Price_Map M where D.DerivedProductCategoryId = ? AND D.DerivedProductId=M.DerivedProductId and D.DerivedProductType in ('Simple','Complex') and D.Status='ACTIVE' order by D.DerivedProductId ASC";
			error_log_shiksha($queryProducts);
			$prodResult = $this->db_sums->query($queryProducts, array($row->CategoryId));

			//error_log_shiksha(print_r($prodResult,true));


			$response[$row->CategoryId][0]['DerivedOfThisBase']=array(array(),'struct');

			foreach ($prodResult->result() as $rowProd)
			{
				if (!isset( $response[$row->CategoryId][0]['DerivedOfThisBase'][0][$rowProd->DerivedProductId])) {
					$response[$row->CategoryId][0]['DerivedOfThisBase'][0][$rowProd->DerivedProductId] = array (array(
						'DerivedProductId' => $rowProd->DerivedProductId,
						'DerivedProductName'=>$rowProd->DerivedProductName,
						'DerivedProductType'=>$rowProd->DerivedProductType,
						'DerivedProductDescription'=>$rowProd->DerivedProductDescription,
						'Currency'=>array(array(),'struct')
					),'struct');
				}

				$response[$row->CategoryId][0]['DerivedOfThisBase'][0][$rowProd->DerivedProductId][0]['Currency'][0][$rowProd->CurrencyId] = array(
					array (
						'ManagementPrice'=>array($rowProd->ManagementPrice,'string'),
						'SuggestedPrice'=>array($rowProd->SuggestedPrice,'string')),'struct');
			}
			// error_log_shiksha("SUBSCCCC ".print_r($response[$row->userid][0]['subscriptions'],true));

		}

		$resp = array($response,'struct');
		//error_log_shiksha(print_r(json_encode($resp),true)." SUMS: Get All Derived Products response!");
		return $this->xmlrpc->send_response ($resp);

		}

	/**
	 * API to get Products and their corresponding properties
	 */
	function sgetProductFeatures($request){
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$Params = $parameters['1']; // All Passed Parameters

		$productId = $Params['productId'];

		//connect DB
		$this->setDBHandle(TRUE);

		// Get all Products and their properties

		if(!isset($productId)){
			$queryCmd = "select B.BaseProductId,BaseProdCategory,BaseProdSubCategory,M.BasePropertyId,BasePropertyName,BasePropertyValue from Base_Products B, Base_Prod_Property_Mapping M,Base_Prod_Properties P where B.BaseProductId = M.BaseProductId AND M.BasePropertyId=P.BasePropertyId order by B.BaseProductId";
		}else{
			$queryCmd = "select B.BaseProductId,BaseProdCategory,BaseProdSubCategory,M.BasePropertyId,BasePropertyName,BasePropertyValue from Base_Products B, Base_Prod_Property_Mapping M,Base_Prod_Properties P where B.BaseProductId=".$this->db_sums->escape($productId)." AND B.BaseProductId = M.BaseProductId AND M.BasePropertyId=P.BasePropertyId";
		}
		error_log_shiksha('Product Feature Selection Query command is: ' . $queryCmd);
		$query = $this->db_sums->query($queryCmd);


		$prodFeatureArr = array();
		foreach ($query->result() as $row){
			if (!is_array($prodFeatureArr[$row->BaseProductId]))
				$prodFeatureArr[$row->BaseProductId] = array(array(),'struct');

			$prodFeatureArr[$row->BaseProductId][0]['BaseProdCategory']=$row->BaseProdCategory;
			$prodFeatureArr[$row->BaseProductId][0]['BaseProdSubCategory']=$row->BaseProdSubCategory;

			if (!is_array($prodFeatureArr[$row->BaseProductId][0]['property']))
				$prodFeatureArr[$row->BaseProductId][0]['property'] = array(array(),'struct');

			$prodFeatureArr[$row->BaseProductId][0]['property'][0][$row->BasePropertyName]=$row->BasePropertyValue;
		}
		$response = array($prodFeatureArr,'struct');
		error_log_shiksha("Product Features RESPONSE: ".print_r($response,true));
		return $this->xmlrpc->send_response($response);
	}
	/**
	* API to get Products for a UserId
	*/

	function sgetProductsForUser($request){
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$Params = $parameters['1']; // All Passed Parameters

		$userId = $Params['userId'];

		//connect DB
		$this->setDBHandle(TRUE);

		// Get all ACTIVE products for a user
		$queryCmd = "select SUM(TotalBaseProdQuantity) as TotalQuantity, SUM(BaseProdRemainingQuantity) as RemainingQuantity, B.BaseProductId, BaseProdCategory,BaseProdSubCategory from Subscription_Product_Mapping S,Base_Products B,Subscription SS where SS.SubscriptionId = S.SubscriptionId and SS.SubscrStatus = 'ACTIVE' and
            SS.ClientUserId = ? AND S.BaseProductId=B.BaseProductId AND S.BaseProdRemainingQuantity >= 1 AND date(S.SubscriptionEndDate) >= curdate() AND date(S.SubscriptionStartDate) <= curdate() AND S.Status='ACTIVE' group by S.BaseProductId";
            
		error_log_shiksha('total Quantity and Remaining Quantities query: ' . $queryCmd);
		$query = $this->db_sums->query($queryCmd, array($userId));

		$response = array();
		foreach ($query->result() as $row)
		{
			$response[$row->BaseProductId]=array(
				array(
					'TotalQuantity'=>array($row->TotalQuantity,'string'),
					'RemainingQuantity'=>array($row->RemainingQuantity,'string'),
					'BaseProdCategory'=>array($row->BaseProdCategory,'string'),
					'BaseProdSubCategory'=>array($row->BaseProdSubCategory,'string')
				),'struct');//close array_push

		}

		$response = array($response,'struct');
		error_log_shiksha("Products for USER RESPONSE: ".print_r($response,true));
		return $this->xmlrpc->send_response($response);
	}
	/**
	 * API to consume a Qunatity-based-Product for a user (client user OR enterprise user)
	 *
	 */
	function sproductConsume($request){
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$Params = $parameters['1'];
		$clientUserId = $Params['clientUserId'];
		$sumsUserId = $Params['sumsUserId'];
		$baseProdId = $Params['baseProdId'];
		$consumedTypeId  = $Params['consumedTypeId'];
		$consumedType  = $Params['consumedType'];

		//connect DB
		$this->setDBHandle(TRUE);

		/*Query for Consumption of a Qunatity-based-Product!
		 *Logic for consumption:
		 *Consume "Oldest-Start-Suscription-Date WITH Earliest-End-Subscription-Date AND Status: Active, Base-Product
		 */

        $queryCmd = "Select * From Subscription_Product_Mapping where SubscriptionId in (select SubscriptionId from Subscription where ClientUserId=? AND SubscrStatus='ACTIVE') AND BaseProductId=? AND BaseProdRemainingQuantity >= 1 AND Status='ACTIVE' AND date(SubscriptionEndDate) >= curdate() AND date(SubscriptionStartDate) <= curdate() order by SubscriptionEndDate ASC,SubscriptionStartDate ASC";
		error_log('Query for getting all qualifying rows for product consumption ' . $queryCmd);
		$query = $this->db_sums->query($queryCmd, array($clientUserId, $baseProdId));

		if($query->result() != NULL){
			foreach ($query->result() as $row){
				if($row->BaseProdRemainingQuantity >=1){
					$numberConsumed = 1;

					if($consumedType != 'institute'){ //TODO: Presently No listing is consumed while adding an institute

                    $queryCmd = "update Subscription_Product_Mapping set BaseProdRemainingQuantity = BaseProdRemainingQuantity-".$this->db_sums->escape_str($numberConsumed)." where SubscriptionId in (select SubscriptionId from Subscription where ClientUserId=? AND SubscrStatus='ACTIVE') AND BaseProductId=? AND BaseProdRemainingQuantity >= 1 AND Status='ACTIVE' AND date(SubscriptionEndDate) >= curdate() AND date(SubscriptionStartDate) <= curdate() order by SubscriptionEndDate ASC,SubscriptionStartDate ASC LIMIT 1";
						error_log('total Quantity and Remaining Quantities query: ' . $queryCmd);
						$this->db_sums->query($queryCmd, array($clientUserId, $baseProdId));

						//Query to deactivate product as remaining reached Zero!
						if($row->BaseProdRemainingQuantity == 1){
                                                    $queryToDeact = "update Subscription_Product_Mapping set Status='INACTIVE' where SubscriptionId= ? and Status='ACTIVE'";
							error_log_shiksha('Query to deactivate product as remaining reached Zero!: ' . $queryToDeact);
							$this->db_sums->query($queryToDeact, array($row->SubscriptionId));
							$queryToDeactMainSub = "update Subscription set SubscrStatus='INACTIVE' where SubscriptionId= ? and SubscrStatus='ACTIVE'";
							error_log_shiksha('Query to deactivate product as remaining reached Zero!: ' . $queryToDeactMainSub);
							$this->db_sums->query($queryToDeactMainSub, array($row->SubscriptionId));
						}
					}else{
						$numberConsumed = 0;
					}

					/*$queryCmd = "select DerivedPropertyValue from Derived_Prod_Property_Mapping where DerivedPropertyId = (select DerivedPropertyId from Derived_Prod_Properties where DerivedPropertyName = 'Validity_Days') and DerivedProductId =  (select DerivedProductId from Subscription where SubscriptionId = $row->SubscriptionId)"; */
					$queryCmd = "select BasePropertyValue from Base_Prod_Property_Mapping where BasePropertyId = (select BasePropertyId from Base_Prod_Properties where BasePropertyName = 'Duration') and BaseProductId =  (select BaseProductId from Subscription where SubscriptionId = ?)";
					error_log_shiksha('Query' . $queryCmd);
					$query1 = $this->db_sums->query($queryCmd, array($row->SubscriptionId));
					//error_log_shiksha("query 1 size PRODUCT CONSUME: ".print_r($query1->result(),true));
					if ($query1->result() != NULL) {
						$validityDays = $query1->first_row()->BasePropertyValue;
					} else {
						$validityDays = 90;
					}

					//error_log_shiksha("validity days SET: ".$validityDays);

					$data['ClientUserId'] = $clientUserId;
					$data['SumsUserId'] = $sumsUserId;
					$data['SubscriptionId'] = $row->SubscriptionId;
					$data['ConsumedBaseProductId'] = $baseProdId;
					$data['ConsumedId'] = $consumedTypeId;
					$data['ConsumedIdType'] = $consumedType;
					$data['NumberConsumed'] = $numberConsumed;
					$data['ConsumptionStartDate'] = date(DATE_ATOM);		//TODO : to be taken from listing forms
					$data['ConsumptionEndDate'] = date(DATE_ATOM,mktime(0, 0, 0, date("m"), date("d")+$validityDays, date("Y")));

					$queryCmd = $this->db_sums->insert_string("SubscriptionLog",$data);
					error_log_shiksha("query : ".$queryCmd);
					$this->db_sums->query($queryCmd);
					$logId= $this->db_sums->insert_id();

					//Inserting listing expiry date in Listings_main
					$this->setDBHandle();
					if(($consumedType == 'course') ||
							($consumedType == 'institute') ||
							($consumedType == 'scholarship') ||
							($consumedType == 'notification')){

						$features = array();
						$features['expiry_date'] = $data['ConsumptionEndDate'];
						$prodClient = new Listing_client();
						$result = $prodClient->consumeProduct(1,$row->SubscriptionId,$consumedType,$consumedTypeId,$features);

						/*$queryCmd = 'update shiksha.listings_main set expiry_date = "'.$data['ConsumptionEndDate'].'" where listing_type = "'.$consumedType.'" and listing_type_id = "'.$consumedTypeId.'" ';
						 error_log_shiksha('Query  to update Listings_main EXPIRY DATE: ' . $queryCmd);
						 $query1 = $this->db_sums->query($queryCmd);*/

					}


					$response=array(
						array(
							'SubscriptionId'=>array($row->SubscriptionId,'string'),
							'BaseProductId'=>array($row->BaseProductId,'string'),
							'ClientUserId'=>array($clientUserId,'string'),
							'SumsUserId'=>array($sumsUserId,'string'),
							'SubscriptionLogId'=>array($logId,'string')
						),'struct');

					error_log_shiksha("SUCCESS in Product Consumption: ".print_r($response,true));
					return $this->xmlrpc->send_response($response);
				}
			}
		}else{
			$response=array(
				array(
					'Error'=>array('No Qualifying Product','string')
				),'struct');
			$response = array($response,'struct');
			error_log_shiksha("ERROR in Product Consumption: ".print_r($response,true));
			return $this->xmlrpc->send_response($response);
		}
	}
	/**
	 * This function gives list of all base product properties
	 */
	function getAllBaseProductProperties($request)
		{
		$parameters = $request->output_parameters();

		$this->setDBHandle(TRUE);
		$queryCmd = "select * from Base_Prod_Properties where isMandatory=0 order by BasePropertyId";
		$query = $this->db_sums->query($queryCmd);
		$response = array();
		foreach ($query->result() as $row)
		{
			array_push($response,array(
				array('BasePropertyId'	=> $row->BasePropertyId,
					'BasePropertyName'=>$row->BasePropertyName,
					'validationType'=>$row->validationType
				),'struct'));
		}
		$response = array ($response,'struct');
		return $this->xmlrpc->send_response($response);
		}
	/**
	 * This function gives list of all derived product properties
	 */
	function getAllDerivedProductProperties($request)
		{
		$parameters = $request->output_parameters();

		$this->setDBHandle(TRUE);
		$queryCmd = "select * from Derived_Prod_Properties where isMandatory=0";
		$query = $this->db_sums->query($queryCmd);
		$response = array();
		foreach ($query->result() as $row)
		{
			array_push($response,array(
				array('DerivedPropertyId'	=> $row->DerivedPropertyId,
					'DerivedPropertyName'=>$row->DerivedPropertyName
				),'struct'));
		}
		$response = array ($response,'struct');
		return $this->xmlrpc->send_response($response);
		}
	/**
	 * This function gives list of all currencies in the system
	 */
	function getAllCurrency($request)
		{
		$parameters = $request->output_parameters();

		$this->setDBHandle(TRUE);
		$queryCmd = "select * from Currency";
		$query = $this->db_sums->query($queryCmd);
		$response = array();
		foreach ($query->result() as $row)
		{
			array_push($response,array(
				array('CurrencyId'	=> $row->CurrencyId,
					'CurrencyCode'=>$row->CurrencyCode,
					'CurrencyName'=>$row->CurrencyName
				),'struct'));
		}
		$response = array ($response,'struct');
		return $this->xmlrpc->send_response($response);
		}
	/**
	 * Function to add base product
	 */
	function sSubmitBaseProductForm($request)
		{
		$parameters = $request->output_parameters();
		$baseProductArray= $parameters['0'];
		$propertyArray=$parameters['1'];
		$priceArray=$parameters['2'];
		//connect DB
		$this->setDBHandle(TRUE);

		$queryCmd=$this->db_sums->insert_string('Base_Products',$baseProductArray);
		error_log_shiksha("query : ".$queryCmd);
		$this->db_sums->query($queryCmd);
		$baseProductId= $this->db_sums->insert_id();
		$this->addProperties($baseProductId,$propertyArray);
		$this->addPriceIndex($baseProductId,$priceArray);
		$response=array(
			array(
				'Added Base Product'=>array($baseProductId,'string')
			),'struct');

		error_log_shiksha("Base Product Added: ".print_r($response,true));
		return $this->xmlrpc->send_response($response);

		}
	/**
	 * Function to add Base Product propery
	 */
	function addProperties($baseProductId,$propertyArray)
		{
		//connect DB
		$this->setDBHandle(TRUE);
		foreach($propertyArray as $key=>$value)
		{
			$queryCmd=$this->db_sums->insert_string('Base_Prod_Property_Mapping',array('BaseProductId'=>$baseProductId,'BasePropertyId'=>$key,'BasePropertyValue'=>$value));
			error_log_shiksha("query : ".$queryCmd);
			$this->db_sums->query($queryCmd);

		}

		}
	/**
	 * Function to add base product price map (BPI)
	 */
	function addPriceIndex($baseProductId,$priceArray)
		{
		//connect DB
		$this->setDBHandle(TRUE);
		foreach($priceArray as $currencyId=>$priceIndex)
		{
			$queryCmd=$this->db_sums->insert_string('Base_Prod_Price_Map',array('BaseProductId'=>$baseProductId,'CurrencyId'=>$currencyId,'CurrencyValue'=>$priceIndex['Rate']));
			error_log_shiksha("query : ".$queryCmd);
			$this->db_sums->query($queryCmd);
			foreach($priceIndex['Index'] as $priceIndexRow)
			{
				$queryCmd=$this->db_sums->insert_string('Price_Quantity',array('BaseProductId'=>$baseProductId,'CurrencyId'=>$currencyId,'BaseProdDurationInDays'=>$priceIndexRow['BaseProdDurationInDays'],'MaxQuantity'=>$priceIndexRow['MaxQuantity'],'DiscountCoefficient'=>$priceIndexRow['DiscountCoefficient'],'DiscountExponentialFactor'=>$priceIndexRow['DiscountExponentialFactor']));
				error_log_shiksha("query : ".$queryCmd);
				$this->db_sums->query($queryCmd);

			}
		}
		}
	/**
	 * Function to provide price suggestion to derived product
	 * 	 */
	function sgetDerivedSuggestedPrice($request)
		{
		$parameters=$request->output_parameters();
		$componentProductArray=$parameters['0'];
		$productType=$componentProductArray['DerivedProductType'];
		$component=$componentProductArray['componentList'];
		foreach($componentArray as $component)
		{
			if($productType == 'Simple')
			{
				$suggestedPrice=$this->getPriceSugestionForBase($component);
			}
			else if($productType == 'Combo')
			{
				$suggestedPrice=$this->getPriceSugestionForDerived($component);
			}
		}
		$response=array(
			array(
				'Suggested Price'=>array($suggestedPrice,'string')
			),'struct');

		error_log_shiksha("Price Suggested: ".print_r($response,true));
		return $this->xmlrpc->send_response($response);


		}
	/**
	 * Function to provide price suggestion for base product
	 */
	function getPriceSugestionForBase($component)
		{
		$this->setDBHandle(TRUE);
		$queryCmd="select CurrencyValue from Base_Prod_Price_Map where BaseProductId=? and CurrencyId=?";
		error_log_shiksha("query : ".$queryCmd);
		$arrResults=  $this->db_sums->query($queryCmd, array($component['BaseProductId'], $component['CurrencyId']));
		foreach ($arrResults->result() as $row)
		{
			$basePrice=$row->CurrencyValue;
		}
		if(isset($component['BaseProdDurationInDays']) && $component['BaseProdDurationInDays']!='')
		{
			$queryCmd="select * from Price_Quantity where BaseProdDurationInDays>=".$this->db_sums->escape($component['BaseProdDurationInDays'])." and BaseProductId=".$this->db_sums->escape($component['BaseProductId'])." and CurrencyId=".$this->db_sums->escape($component['CurrencyId'])." order by BaseProdDurationInDays limit 1";
		}
		else
		{
			$queryCmd="select * from Price_Quantity where MaxQuantity>=".$this->db_sums->escape($component['MaxQuantity'])." and BaseProductId=".$this->db_sums->escape($component['BaseProductId'])." and CurrencyId=".$this->db_sums->escape($component['CurrencyId'])." order by MaxQuantity limit 1";
		}
		error_log_shiksha("query : ".$queryCmd);
		$arrResults=  $this->db_sums->query($queryCmd);
		foreach ($arrResults->result() as $row)
		{
			$discountCoefficient=$row->DiscountCoefficient;
			$discountExponentialFactor=$row->DiscountExponentialFactor;
		}

		if(isset($component['BaseProdDurationInDays']) && $component['BaseProdDurationInDays']!='')
		{
			$suggestedPrice=$basePrice*$component['BaseProdDurationInDays']-$discountCoefficient*$component['BaseProdDurationInDays']*exp($discountExponentialFactor*$component['BaseProdDurationInDays']);
		}
		else
		{
			error_log_shiksha("Discountc ".$discountCoefficient." f ".$discountExponentialFactor);
			$suggestedPrice=$basePrice*$component['MaxQuantity']-$discountCoefficient*$component['MaxQuantity']*exp($discountExponentialFactor*$component['MaxQuantity']);

		}
		return round($suggestedPrice,2);
		}
	/**
	 * Function to provide price suggestion to derived product
	 */
	function getPriceSugestionForDerived($component)
		{
		$this->setDBHandle(TRUE);
		$queryCmd="select * from Derived_Products_Mapping where DerivedProductId=?";
		error_log_shiksha("query : ".$queryCmd);
		$arrResults=  $this->db_sums->query($queryCmd, array($component['DerivedProductId']));
		$suggestedPrice=0;
		foreach ($arrResults->result() as $row)
		{
			$tempArray=array();
			$tempArray['BaseProductId']=$row->BaseProductId;
			$baseProductQuantity=$row->BaseProdQuantity;
			$baseProductDuration=$row->BaseProdDurationInDays;

			$tempArray['BaseProdDurationInDays']=$component['DerivedProdDurationInDays'];
			$tempArray['MaxQuantity']=$baseProductQuantity*$component['MaxQuantity'];
			$tempArray['CurrencyId']=$component['CurrencyId'];
			$suggestedPrice+=$this->getPriceSugestionForBase($component);
		}
		return $suggestedPrice;
		}
	/**
	 * This functions gives list of base products
	 */
	function sgetBaseProductList($request)
		{
		$this->setDBHandle(TRUE);
		$parameters=$request->output_parameters();
	    $queryCmd="select * from Base_Products where Status='ACTIVE'";
		error_log_shiksha("query : ".$queryCmd);
		$arrResults =  $this->db_sums->query($queryCmd);
		$response = array();
		foreach ($arrResults->result_array() as $row)
		{
			array_push($response,array ($row,'struct'));
		}

		$response=array($response,'struct');
		return $this->xmlrpc->send_response($response);
		}
	/**
	 * This functions gives list of all simple derived products
	 */
	function sgetSimpleDerivedProductList($request)
		{
			$this->setDBHandle(TRUE);


		$parameters=$request->output_parameters();
		$queryCmd="select * from Derived_Products where DerivedProductType='Simple'";
		error_log_shiksha("query : ".$queryCmd);
		$arrResults=  $this->db_sums->query($queryCmd);
		$response=array($arrResults,'struct');
		return $this->xmlrpc->send_response($response);

		}
	/**
	 * Function to add derived product
	 */
	function sSubmitDerivedProductForm($request)
		{
		$parameters=$request->output_parameters();
		$derivedProductArray=$parameters[0];
		$derivedProductPropertyArray=$parameters[1];
		$derivedProductPriceArray=$parameters[2];
		$derivedProductElementArray=$parameters[3];
		$this->setDBHandle(TRUE);

		$queryCmd = $this->db_sums->insert_string("Derived_Products",$derivedProductArray);
		error_log_shiksha("query : ".$queryCmd);
		$this->db_sums->query($queryCmd);
		$derivedProductId= $this->db_sums->insert_id();
		$this->addDerivedProductProperties($derivedProductId,$derivedProductPropertyArray);
		$this->addDerivedProductPrice($derivedProductId,$derivedProductPriceArray);
		$this->addDerivedProductElements($derivedProductId,$derivedProductElementArray);
		$response=array(
			array(
				'Added Derived Product'=>array($derivedProductId,'string')
			),'struct');

		error_log_shiksha("Derived Product Added: ".print_r($response,true));
		return $this->xmlrpc->send_response($response);

		}
	/**
	 * Function to add derived product properties
	 */
	function addDerivedProductProperties($derivedProductId,$derivedProductPropertyArray)
		{
		//connect DB
		$this->setDBHandle(TRUE);
		foreach($derivedProductPropertyArray as $key=>$value)
		{
			$queryCmd=$this->db_sums->insert_string('Derived_Prod_Property_Mapping',array('DerivedProductId'=>$derivedProductId,'DerivedPropertyId'=>$key,'DerivedPropertyValue'=>$value));
			error_log_shiksha("query : ".$queryCmd);
			$this->db_sums->query($queryCmd);

		}

		}
	/**
	 * Function to add derived product pricing
	 */
	function addDerivedProductPrice($derivedProductId,$derivedProductPriceArray)
		{
		$this->setDBHandle(TRUE);
		foreach($derivedProductPriceArray as $currencyId=>$value)
		{
			$queryCmd=$this->db_sums->insert_string('Derived_Prod_Price_Map',array('DerivedProductId'=>$derivedProductId,'CurrencyId'=>$currencyId,'ManagementPrice'=>$value['ManagementPrice'],'SuggestedPrice'=>$value['SuggestedPrice']));
			error_log_shiksha("query : ".$queryCmd);
			$this->db_sums->query($queryCmd);
		}
		}
	/**
	 * Function to add derived product elements
	 *  */
	function addDerivedProductElements($derivedProductId,$derivedProductElementArray)
		{
		$this->addDerivedProductBaseElements($derivedProductId,$derivedProductElementArray);
		}
	/**
	 * Function to add Base products to derived products
	 */
	function addDerivedProductBaseElements($derivedProductId,$derivedProductElementArray)
		{
		$this->setDBHandle(TRUE);
		foreach($derivedProductElementArray as $baseProduct)
		{
			$queryCmd=$this->db_sums->insert_string('Derived_Products_Mapping',array('DerivedProductId'=>$derivedProductId,'BaseProductId'=>$baseProduct['BaseProductId'],'BaseProdQuantity'=>$baseProduct['BaseProdQuantity'],'BaseProdDurationInDays'=>$baseProduct['BaseProdDurationInDays'],'SuggestedPrice'=>$baseProduct['SuggestedPrice'],'ManagementPrice'=>$baseProduct['ManagementPrice'],'CurrencyId'=>$baseProduct['CurrencyId']));
			error_log_shiksha("query : ".$queryCmd);
			$this->db_sums->query($queryCmd);

		}
		}
		/**
	 * Function to add derived products to derived products
	 */
	function addDerivedProductDerivedElements($derivedProductId,$derivedProductElementArray)
		{
		$this->setDBHandle(TRUE);
		$tempArray=array();
		foreach($derivedProductElementArray as $derivedProduct)
		{
			$queryCmd="select * from Derived_Products_Mapping where DerivedProductId=?";
			error_log_shiksha("query : ".$queryCmd);
			$arrResults=  $this->db_sums->query($queryCmd, array($derivedProduct['DerivedProductId']));
			foreach ($arrResults->result() as $row)
			{
				$baseProductId=$row->BaseProductId;
				$baseProductQuantity=$row->BaseProdQuantity;
				$baseProductDuration=$row->BaseProdDurationInDays;
				if(array_key_exists($baseProdId,$tempArray))
				{
					$tempArray[$baseProdId]['BaseProdQuantity']+=$baseProductQuantity;
					$tempArray[$baseProdId]['BaseProdDurationInDays']+=$baseProductDuration;
				}
				else
				{
					$tempArray[$baseProdId]['BaseProdQuantity']=$baseProductQuantity;
					$tempArray[$baseProdId]['BaseProdDurationInDays']=$baseProductDuration;
				}
			}

		}
		$baseElementArray=array();
		foreach($tempArray as $key=>$value)
		{
			array_push($baseElementArray,array('BaseProductId'=>$key,'BaseProdQuantity'=>$value['BaseProdQuantity'],'BaseProdDurationInDays'=>$value['BaseProdDurationInDays']));
		}
		$this->addDerivedProductBaseElements($derivedProductId,$baseElementArray);
		}
	/**
	 * Function to give base product price suggestion
	 */
	function sgetPriceSugestionForBase($request)
		{
		$parameters=$request->output_parameters();
		$baseElementArray=$parameters[0];
		error_log_shiksha(print_r($baseElementArray,true));
		$sugPrice = $this->getPriceSugestionForBase($baseElementArray);
		error_log_shiksha($sugPrice);
		$response=array($sugPrice,'string');
		return $this->xmlrpc->send_response($response);
		}
	/**
	 * Function to give duration type for base product
	 */
	function sgetDurationTypeForBase($request)
		{
		$parameters=$request->output_parameters();
		$baseProductId=$parameters[0];
		$this->setDBHandle(TRUE);
		$tempArray=array();
		$queryCmd="select * from Base_Prod_Property_Mapping where BaseProductId=? and BasePropertyId=8";
		error_log_shiksha("query : ".$queryCmd);
		$arrResults=  $this->db_sums->query($queryCmd, array($baseProductId));
		foreach($arrResults->result_array() as $row)
		{
			array_push($tempArray,array($row,'struct'));
		}
		$response=array($tempArray,'struct');
		return $this->xmlrpc->send_response($response);
		}
	/**
	 * Function to get derived product Categories
	 */
	function getCategoriesForDerived($request)
		{
		$parameters=$request->output_parameters();
		$this->setDBHandle(TRUE);
		$response = array();
		$queryCmd = "select CategoryId,CategoryName from Derived_Product_Category where status = 'live'";
		error_log_shiksha("Product Server : ".$queryCmd);
		$results = $this->db_sums->query($queryCmd);
		foreach ($results->result_array() as $row)
		{
			array_push($response,array($row,'struct'));
		}
		$response = array($response,'struct');
		return $this->xmlrpc->send_response($response);
		}

	/**
	 * Function to get Sums Parameters
	 */
	function sgetAllSumsParameters($request)
		{
		$parameters = $request->output_parameters();

		$this->setDBHandle(TRUE);
		$queryCmd = "select * from Sums_Parameters";
		$query = $this->db_sums->query($queryCmd);
		$response = array();
		foreach ($query->result() as $row)
		{
			$response[$row->parameterName]= array(array(),'struct');
			$response[$row->parameterName][0]['parameterId'] = $row->parameterId;
			$response[$row->parameterName][0]['parameterValue'] = $row->parameterValue;
		}
		$response = array ($response,'struct');
		return $this->xmlrpc->send_response($response);
		}
	/**
	 * Function to get Derived Product Properties
	 */
	function sgetDerivedProductProperties($request)
		{
		$parameters = $request->output_parameters();
		$derivedProductId = $parameters[0];
		$this->setDBHandle(TRUE);
		$queryCmd = "select * from Derived_Products where DerivedProductId=?";
		error_log_shiksha("Product Server : ".$queryCmd);
		$results = $this->db_sums->query($queryCmd, array($derivedProductId));
		$response=array();
		foreach ($results->result_array() as $row)
		{
			array_push($response,array($row,'struct'));
		}
		$response = array($response,'struct');
		error_log_shiksha("derived Pro ".print_r($response,true));
		return $this->xmlrpc->send_response($response);
		}
	/**
	 * Functions to get all base products of a derived Product
	 */
	function sgetAllBaseProdsForDerived($request)
		{
		$parameters = $request->output_parameters();
		$derivedProductId = $parameters[0];
		error_log_shiksha(print_r($derivedProductIds,true)."SSSSSSS");

		$this->setDBHandle(TRUE);
		$queryCmd = "select * from Derived_Products_Mapping where DerivedProductId=?";
		error_log_shiksha("Product Server sgetAllBaseProdsForDerived : ".$queryCmd);
		$query = $this->db_sums->query($queryCmd, array($derivedProductId));
		$response[$derivedProductId] = array(array(),'struct');
		foreach ($query->result() as $row)
		{
			array_push($response[$derivedProductId][0],array(
				array('BaseProductId'=>$row->BaseProductId,
					'BaseProdQuantity'=>$row->BaseProdQuantity,
					'BaseProdDurationInDays'=>$row->BaseProdDurationInDays,
					'SuggestedPrice'=>$row->SuggestedPrice,
					'ManagementPrice'=>$row->ManagementPrice,
					'CurrencyId'=>$row->CurrencyId
				),'struct'));
		}
		$response = array ($response,'struct');
		return $this->xmlrpc->send_response($response);
		}
	/**
	 * Function to get properties of a derived product
	 */
	function sgetPropertiesOfThisDerived($request)
		{
		$parameters = $request->output_parameters();
		$derivedProductId = $parameters[0];
		$this->setDBHandle(TRUE);
		$queryCmd = "select * from Derived_Prod_Property_Mapping where DerivedProductId=?";
		error_log_shiksha("Product Server : ".$queryCmd);
		$results = $this->db_sums->query($queryCmd, array($derivedProductId));
		$response=array();
		foreach ($results->result_array() as $row)
		{
			array_push($response,array($row,'struct'));
		}
		$response = array($response,'struct');
		error_log_shiksha("derived Pro ".print_r($response,true));
		return $this->xmlrpc->send_response($response);
		}
	/**
	 * Function to get Id of Basic Bronze Listing
	 */
	function sgetFreeDerivedId($request)
		{
		$parameters = $request->output_parameters();
		$appId = $parameters[0];

		$this->setDBHandle(TRUE);
            $queryCmd = "select DerivedProductId from Derived_Products where DerivedProductName = 'Basic Bronze Listing' and Status='ACTIVE'";
		error_log_shiksha('Query' . $queryCmd);
		$query1 = $this->db_sums->query($queryCmd);
		$dervdProdId = $query1->first_row()->DerivedProductId;

		$response=array(
			array(
				'derivedProdId'=>array($dervdProdId,'string')
			),'struct');

		error_log_shiksha("derived ProdId: ".print_r($response,true));
		return $this->xmlrpc->send_response($response);
		}
	/**
	 * Function to get all subscriptions for a user
	 */
	function sgetAllSubscriptionsForUser($request){
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$Params = $parameters['1']; // All Passed Parameters

		$userId = $Params['userId'];

		//connect DB
		$this->setDBHandle(TRUE);

		// Get all ACTIVE Subscriptions for a user
        $queryCmd = "select S.SubscriptionId,SubscriptionStartDate,SubscriptionEndDate,TotalBaseProdQuantity, BaseProdRemainingQuantity, B.BaseProductId, BaseProdCategory,BaseProdSubCategory from Subscription_Product_Mapping S,Base_Products B, Subscription Su where S.SubscriptionId = Su.SubscriptionId AND Su.ClientUserId=? AND Su.SubscrStatus='ACTIVE' AND S.BaseProductId=B.BaseProductId AND S.BaseProdRemainingQuantity >= 1 AND date(S.SubscriptionEndDate) >= curdate() AND date(S.SubscriptionStartDate) <= curdate() AND S.Status='ACTIVE' order by S.SubscriptionEndDate ASC,S.SubscriptionStartDate ASC,S.BaseProductId";

		$query = $this->db_sums->query($queryCmd, array($userId));
		error_log('total Quantity and Remaining Quantities query: ' . $this->db_sums->last_query());

		$response = array();
		foreach ($query->result() as $row)
		{
			$response[$row->SubscriptionId]=array(
				array(
					'SubscriptionId'=>array($row->SubscriptionId,'string'),
					'SubscriptionStartDate'=>array($row->SubscriptionStartDate,'string'),
					'SubscriptionEndDate'=>array($row->SubscriptionEndDate,'string'),
					'TotalBaseProdQuantity'=>array($row->TotalBaseProdQuantity,'string'),
					'BaseProdRemainingQuantity'=>array($row->BaseProdRemainingQuantity,'string'),
					'BaseProductId'=>array($row->BaseProductId,'string'),
					'BaseProdCategory'=>array($row->BaseProdCategory,'string'),
					'BaseProdSubCategory'=>array($row->BaseProdSubCategory,'string')
				),'struct');//close array_push

		}

		$response = array($response,'struct');
		error_log("Products for USER RESPONSE: ".print_r($response,true));
		return $this->xmlrpc->send_response($response);
	}

    /**
     * Function to get all subscriptions for a user
     */
    function sgetAllSubscriptionsForUserLDB($request){
        $parameters = $request->output_parameters();
        $appId = $parameters['0'];
        $Params = $parameters['1']; // All Passed Parameters

        $userId = $Params['userId'];

        //connect DB
        $this->setDBHandle(TRUE);

        // Get all ACTIVE Subscriptions for a user
        //AJAY
       /* $queryCmd = "select S.SubscriptionId,SubscriptionStartDate,SubscriptionEndDate,TotalBaseProdQuantity, BaseProdRemainingQuantity, B.BaseProductId, BaseProdCategory,BaseProdSubCategory from
        Subscription_Product_Mapping S,Base_Products B where SubscriptionId in (select SubscriptionId from Subscription where ClientUserId=? AND SubscrStatus='ACTIVE') AND
        S.BaseProductId=B.BaseProductId AND S.BaseProdRemainingQuantity >= 0 AND date(S.SubscriptionEndDate) >= curdate() AND date(S.SubscriptionStartDate) <= curdate() AND S.Status='ACTIVE' order by
        S.SubscriptionEndDate ASC,S.SubscriptionStartDate ASC,S.BaseProductId";*/

        $queryCmd = "select S.SubscriptionId,SubscriptionStartDate,SubscriptionEndDate,TotalBaseProdQuantity,
	    			 BaseProdRemainingQuantity, B.BaseProductId, BaseProdCategory,BaseProdSubCategory from 
	    			 Subscription_Product_Mapping S,Base_Products B,Subscription sub
					 where S.BaseProductId=B.BaseProductId AND S.BaseProdRemainingQuantity >= 0
					 AND date(S.SubscriptionEndDate) >= curdate() AND date(S.SubscriptionStartDate) <= curdate() 
					AND S.Status='ACTIVE'
					AND S.SubscriptionId = sub.SubscriptionId
					AND sub.ClientUserId=?
					AND sub.SubscrStatus='ACTIVE'
					order by  S.SubscriptionEndDate ASC,S.SubscriptionStartDate ASC,S.BaseProductId";

        error_log_shiksha('total Quantity and Remaining Quantities query: ' . $queryCmd);
        $query = $this->db_sums->query($queryCmd, array($userId));

        $response = array();
        foreach ($query->result() as $row)
        {
            $response[$row->SubscriptionId]=array(
                array(
                    'SubscriptionId'=>array($row->SubscriptionId,'string'),
                    'SubscriptionStartDate'=>array($row->SubscriptionStartDate,'string'),
                    'SubscriptionEndDate'=>array($row->SubscriptionEndDate,'string'),
                    'TotalBaseProdQuantity'=>array($row->TotalBaseProdQuantity,'string'),
                    'BaseProdRemainingQuantity'=>array($row->BaseProdRemainingQuantity,'string'),
                    'BaseProductId'=>array($row->BaseProductId,'string'),
                    'BaseProdCategory'=>array($row->BaseProdCategory,'string'),
                    'BaseProdSubCategory'=>array($row->BaseProdSubCategory,'string')
                ),'struct');//close array_push

        }

        unset($query);
        $response = array($response,'struct');
        error_log_shiksha("Products for USER RESPONSE: ".print_r($response,true));
        return $this->xmlrpc->send_response($response);
    }
	/**
	 * This functions gives list of all derived products
	 */
	function getAllDerivedProducts($request)
		{
		$parameters = $request->output_parameters();
		$this->setDBHandle(TRUE);

		$query = "select * from Derived_Product_Category where Status='live'";
		error_log_shiksha($query);
		$arrResults = $this->db_sums->query($query);


		foreach ($arrResults->result() as $row)
		{

			$response[$row->CategoryId]= array(array(),'struct');
			$response[$row->CategoryId][0]['CategoryName'] = $row->CategoryName;

			$queryProducts = "select * from Derived_Products D,Derived_Prod_Price_Map M where D.DerivedProductCategoryId = ? AND D.DerivedProductId=M.DerivedProductId and D.DerivedProductType in ('Simple','Complex') and D.Status in ('ACTIVE','INACTIVE') order by D.DerivedProductId ASC";
			error_log_shiksha($queryProducts);
			$prodResult = $this->db_sums->query($queryProducts, array($row->CategoryId));

			//error_log_shiksha(print_r($prodResult,true));


			$response[$row->CategoryId][0]['DerivedOfThisBase']=array(array(),'struct');

			foreach ($prodResult->result() as $rowProd)
			{
				if (!isset( $response[$row->CategoryId][0]['DerivedOfThisBase'][0][$rowProd->DerivedProductId])) {
					$response[$row->CategoryId][0]['DerivedOfThisBase'][0][$rowProd->DerivedProductId] = array (array(
						'DerivedProductId' => $rowProd->DerivedProductId,
						'DerivedProductName'=>$rowProd->DerivedProductName,
						'DerivedProductType'=>$rowProd->DerivedProductType,
						'DerivedProductDescription'=>$rowProd->DerivedProductDescription,
						'Status'=>$rowProd->Status,
						'Currency'=>array(array(),'struct')
					),'struct');
				}

				$response[$row->CategoryId][0]['DerivedOfThisBase'][0][$rowProd->DerivedProductId][0]['Currency'][0][$rowProd->CurrencyId] = array(
					array (
						'ManagementPrice'=>array($rowProd->ManagementPrice,'string'),
						'SuggestedPrice'=>array($rowProd->SuggestedPrice,'string')),'struct');
			}
			// error_log_shiksha("SUBSCCCC ".print_r($response[$row->userid][0]['subscriptions'],true));

		}

		$resp = array($response,'struct');
		//error_log_shiksha(print_r(json_encode($resp),true)." SUMS: Get All Derived Products response!");
		return $this->xmlrpc->send_response ($resp);

		}
	/**
	 * Function to change derived product status
	 */
	function supdateDerivedProdStatus($request)
		{
		$parameters = $request->output_parameters();

		$params = $parameters['1'];
		error_log_shiksha("Product server : ".print_r($params,true));

		$editingUserId = $params['editingUserId'];
		$updateTypeDerived = $params['updateTypeDerived'];
		$derivedProds = $params['derivedProds'];

		$this->setDBHandle(TRUE);

		$resp['subsArr'] = array(array(),'struct');

		switch ($updateTypeDerived){
			case 'enable':
				$status = 'ACTIVE';
				break;
			case 'disable':
				$status = 'INACTIVE';
				break;
			case 'delete':
				$status = 'DELETED';
				break;
		}

		$numTrans = count($derivedProds);

		for($i=0;$i<$numTrans;$i++){
			$last_modify = standard_date('DATE_ATOM',time());

			$queryCmd = "update Derived_Products set Status=?,lastModifyTime=?,sumsEditingUserId=? where DerivedProductId=?";
			error_log_shiksha("Subscription server : ".$queryCmd);
			$query = $this->db_sums->query($queryCmd, array($status, $last_modify, $editingUserId, $derivedProds[$i]));


			array_push($resp['subsArr'][0],$derivedProds[$i]);
		}

		error_log_shiksha("Disabled SUBS".print_r($resp,true));
		$response = array($resp,'struct');
		return $this->xmlrpc->send_response ($response);
		}

    function getAllPseudoSubscriptionsForUser($request){
        $parameters = $request->output_parameters();
        $appId = $parameters['0'];
        $Params = $parameters['1']; // All Passed Parameters

        $userId = $Params['userId'];

        //connect DB
        $this->setDBHandle(TRUE);

        // Get all ACTIVE Subscriptions for a user
        $queryCmd = "select S.SubscriptionId,SubscriptionStartDate,SubscriptionEndDate,TotalBaseProdQuantity, BaseProdPseudoRemainingQuantity, B.BaseProductId, BaseProdCategory,BaseProdSubCategory from Subscription_Product_Mapping S,Base_Products B, Subscription Su where S.SubscriptionId = Su.SubscriptionId AND Su.ClientUserId=? AND Su.SubscrStatus='ACTIVE' AND S.BaseProductId=B.BaseProductId AND S.BaseProdPseudoRemainingQuantity >= 1 AND date(S.SubscriptionEndDate) >= curdate() AND date(S.SubscriptionStartDate) <= curdate() AND S.Status='ACTIVE' order by S.BaseProductId,S.SubscriptionEndDate ASC,S.SubscriptionStartDate ASC";

        error_log_shiksha('total Quantity and Remaining Quantities query: ' . $queryCmd);
        $query = $this->db_sums->query($queryCmd, array($userId));

        $response = array();
        foreach ($query->result() as $row)
        {
            $response[$row->SubscriptionId]=array(
                array(
                    'SubscriptionId'=>array($row->SubscriptionId,'string'),
                    'SubscriptionStartDate'=>array($row->SubscriptionStartDate,'string'),
                    'SubscriptionEndDate'=>array($row->SubscriptionEndDate,'string'),
                    'TotalBaseProdQuantity'=>array($row->TotalBaseProdQuantity,'string'),
                    'BaseProdPseudoRemainingQuantity'=>array($row->BaseProdPseudoRemainingQuantity,'string'),
                    'BaseProductId'=>array($row->BaseProductId,'string'),
                    'BaseProdCategory'=>array($row->BaseProdCategory,'string'),
                    'BaseProdSubCategory'=>array($row->BaseProdSubCategory,'string')
                ),'struct');//close array_push

            }

            $response = array($response,'struct');
            error_log_shiksha("Products for USER RESPONSE: ".print_r($response,true));
        return $this->xmlrpc->send_response($response);
    }
      function getSalesPersonWiseClientList($request) {
	//connect DB
        $this->setDBHandle(TRUE);
        $db_query = "SELECT group_concat( distinct ClientUserId ) as client_list, BranchName, SalesBranch, SalesBy, ".
                 "displayname,email,firstname,lastname FROM SUMS.Transaction, SUMS.Sums_Branch_List,shiksha.tuser ".
                 "WHERE SalesBranch = BranchId AND userid = SalesBy GROUP BY SalesBy";
        $query = $this->db_sums->query($db_query);
        $result_array = array();
        foreach ($query->result_array() as $row) {
		$result_array[$row['SalesBy']] = $row;
        }
        $response = array($result_array,'struct');
        return $this->xmlrpc->send_response($response);
   }


    /**
     * Function to get Sales Data by Client Id
     */
    function getSalesDataByClientId($request){
        $parameters = $request->output_parameters();
        $appId = $parameters['0'];
        $client_id = $parameters['1'];

        //connect DB
        $this->setDBHandle(TRUE);

        $queryCmd = "select SalesBy from  Transaction where ClientUserId = ? order by TransactionId desc limit 1";
        $query = $this->db_sums->query($queryCmd, array($client_id));
        $result = $query->row_array();

 		$finalResult = array();
        if(!empty($result)) {
			$salesBy = $result['SalesBy'];

	        $queryCmd = "select userId from  Sums_User_Details where EmployeeId = ?";
	        $query = $this->db_sums->query($queryCmd, array($salesBy));
	        $sumsresult = $query->row_array();

	        $finalResult = array('userId'=>$sumsresult['userId']);
    	}
        $response = array($finalResult,'struct');

        return $this->xmlrpc->send_response($response);
    }


    /**
     * Function to get Sales Data by Client Id
     */
    function getOldActiveSubscriptionClients($request){
        $parameters = $request->output_parameters();
        $appId = $parameters['0'];

        //connect DB
        $this->setDBHandle(TRUE);

        $db_query = "select ClientUserId,SubscriptionEndDate, unix_timestamp(SubscriptionEndDate) as SubscriptionEndUnixTime  from Subscription s inner join Subscription_Product_Mapping spm on s.SubscriptionId = spm.SubscriptionId  where s.BaseProductId = 127 and spm.BaseProductId = 127 and s.SubscrStatus = 'ACTIVE' and spm.Status ='ACTIVE' and spm.SubscriptionEndDate >= now() and spm.BaseProdRemainingQuantity > 0";
        $query = $this->db_sums->query($db_query);
        
        $finalResult = array();
        foreach ($query->result_array() as $row) {

        	$client_id = $row['ClientUserId'];
        	$SubscriptionEndDate = $row['SubscriptionEndDate'];
        	$SubscriptionEndUnixTime = $row['SubscriptionEndUnixTime'];

        	if(!isset($finalResult[$client_id])) {

	        	$result = array();
		        $queryCmd = "select SalesBy from  Transaction where ClientUserId = ? order by TransactionId desc limit 1";
		        $query = $this->db_sums->query($queryCmd, array($client_id));
		        $result = $query->row_array();

				$sumsresult = array();

		        if(!empty($result)) {

					$salesBy = $result['SalesBy'];

			        $queryCmd = "select userId from  Sums_User_Details where EmployeeId = ?";
			        $query = $this->db_sums->query($queryCmd, array($salesBy));
			        $sumsresult = $query->row_array();

		    	}
		    	$finalResult[$client_id]['salesUserId'] = $sumsresult['userId'];
				$finalResult[$client_id]['SubscriptionEndDate'] = $SubscriptionEndDate;
				$finalResult[$client_id]['SubscriptionEndUnixTime'] = $SubscriptionEndUnixTime;

		    } else {

		    	if($SubscriptionEndUnixTime > $finalResult[$client_id]['SubscriptionEndUnixTime']) {
		    		$finalResult[$client_id]['SubscriptionEndDate'] = $SubscriptionEndDate;
		    		$finalResult[$client_id]['SubscriptionEndUnixTime'] = $SubscriptionEndUnixTime;
		    	}

		    }			
			
    	}
    	
        $response = array($finalResult,'struct');

        return $this->xmlrpc->send_response($response);
    }
}


?>
