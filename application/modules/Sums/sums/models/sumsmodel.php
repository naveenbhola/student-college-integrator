<?php
class SumsModel extends MY_Model {
	function __construct(){
		parent::__construct('SUMS');
	}
	
	function getDbHandle($operation = 'read'){
		//connect DB
		if($operation=='read'){
			$this->dbHandle = $this->getReadHandle();
		}
		else{
        	$this->dbHandle = $this->getWriteHandle();
		}
	}
	
	function getMainCollegeLinkSubscriptionProperties($subscriptionId,$type,$typeId)
		{
		$appId=1;
		
		$this->load->library(array('blog_client','category_list_client','listing_client','Subscription_client'));
		$objSubs = new Subscription_client();
		$response =  $objSubs->getMainCollegeLinkSubscriptionDetails($subscriptionId);
		
		$testprepArray = array();
		$countryArray = array();
		$categoryArray = array();
		$cityArray = array();
		$errorArray=array();
		$stateArray =array();
		$subcategoryArray = array();
		
		foreach($response as $property)
		{
			if($property['BasePropertyId']==18)
			{
				$catClientObj = new Blog_Client();
				$testprepArray = $catClientObj->getExamsCategory($appId,$parentId,$type,$typeId);
				if(count($testprepArray)==0)
				{
					$errorArray[]="This listing does not contain Test Preparation data, Can not be posted as Main College Link on Test Prep Section";
				}
			}
			// Case for checking BasePropertyId for Study Abroad			
			else if($property['BasePropertyId']==17)
			{
				$LmsClientObj = new Category_list_client();
				$leads = $LmsClientObj->getCountriesBasedOnTier($appId,0,$type,$typeId);
				$noOfCountries =  count($leads);
				$mainArr =array();
				for($i = 0 ; $i < $noOfCountries ; $i++){
					if($leads[$i]['countryId'] > 2){
						array_push($mainArr,$leads[$i]);
					}
				}
				$countryArray=$mainArr;
				if(count($countryArray)==0)
				{
					$errorArray[]="This listing does not contain Study Abroad data, Can not be posted as Main College Link on Study Abroad Section";
				}
				
			}
			// Case for checking BasePropertyId for Tier 1 city
			else if($property['BasePropertyId']==11)
			{
				$catClientObj = new Category_list_client();
				$cityArray = $catClientObj->getCityBasedOnTier($appId,1,1,$type,$typeId);
				if(count($cityArray)==0)
				{
					$errorArray[]="This listing does not belong to Tier 1 Cities";
				}
				
				
			}
			// Case for checking BasePropertyId for Tier 2 city			
			else if($property['BasePropertyId']==12)
			{
				$catClientObj = new Category_list_client();
				$cityArray = $catClientObj->getCityBasedOnTier($appId,2,1,$type,$typeId);
				if(count($cityArray)==0)
				{
					$errorArray[]="This listing does not belong to Tier 2 Cities";
				}
				
				
			}
			// Case for checking BasePropertyId for Tier 3 city			
			else if($property['BasePropertyId']==13)
			{
				$catClientObj = new Category_list_client();
				$cityArray = $catClientObj->getCityBasedOnTier($appId,3,1,$type,$typeId);
				if(count($cityArray)==0)
				{
					$errorArray[]="This listing does not belong to Tier 3 Cities";
				}
				
				
			}
			// Case for checking BasePropertyId for Tier 1 category			
			else if($property['BasePropertyId']==14)
			{
				$catClientObj = new Category_list_client();
				
				$categoryArray = $catClientObj->getCategoryBasedOnTier($appId,1,$type,$typeId);
				if(count($categoryArray)==0)
				{
					$errorArray[]="This listing does not belong to Tier 1 Category";
				}
				
				
			}
			// Case for checking BasePropertyId for Tier 2 category			
			else if($property['BasePropertyId']==15)
			{
				$catClientObj = new Category_list_client();
				
				$categoryArray = $catClientObj->getCategoryBasedOnTier($appId,2,$type,$typeId);
				if(count($categoryArray)==0)
				{
					$errorArray[]="This listing does not belong to Tier 2 Category";
				}
				
				
			}
			// Case for checking BasePropertyId for Tier 3 category			
			else if($property['BasePropertyId']==16)
			{
				$catClientObj = new Category_list_client();
				
				$categoryArray = $catClientObj->getCategoryBasedOnTier($appId,3,$type,$typeId);
				if(count($categoryArray)==0)
				{
					$errorArray[]="This listing does not belong to Tier 3 Category";
				}
				
				
			}
			// Case for checking BasePropertyId for Tier 4 category			
			else if($property['BasePropertyId']==24)
			{
				$catClientObj = new Category_list_client();
				
				$categoryArray = $catClientObj->getCategoryBasedOnTier($appId,4,$type,$typeId);
				if(count($categoryArray)==0)
				{
					$errorArray[]="This listing does not belong to Tier 4 Category";
				}
				
			
			}
			// Case for checking BasePropertyId for Tier 5 category			
			else if($property['BasePropertyId']==25)
			{
				$catClientObj = new Category_list_client();
				
				$categoryArray = $catClientObj->getCategoryBasedOnTier($appId,5,$type,$typeId);
				if(count($categoryArray)==0)
				{
					$errorArray[]="This listing does not belong to Tier 5 Category";
				}
			}
			// Case for checking BasePropertyId for Tier 1 Subcategory			
			else if($property['BasePropertyId']==26)
			{
				
				$catClientObj = new Category_list_client();
				
				$subcategoryArray = $catClientObj->getSubcategoryBasedontier($appId,1,$type,$typeId);
				if(count($subcategoryArray)==0)
				{
					$errorArray[]="This listing does not belong to Tier 1 Subcategory";
				}
				
			}
			// Case for checking BasePropertyId for Tier 2 Subcategory			
			else if($property['BasePropertyId']==27)
			{
				
				$catClientObj = new Category_list_client();
				
				$subcategoryArray = $catClientObj->getSubcategoryBasedontier($appId,2,$type,$typeId);
				if(count($subcategoryArray)==0)
				{
					$errorArray[]="This listing does not belong to Tier 2 Subcategory";
				}
				
			}
			// Case for checking BasePropertyId for Tier 3 Subcategory			
			else if($property['BasePropertyId']==28)
			{
				
				$catClientObj = new Category_list_client();
				
				$subcategoryArray = $catClientObj->getSubcategoryBasedontier($appId,3,$type,$typeId);
				if(count($subcategoryArray)==0)
				{
					$errorArray[]="This listing does not belong to Tier 3 Subcategory";
				}
				
			}
			// Case for checking BasePropertyId for Tier 4 Subcategory		
			else if($property['BasePropertyId']==29)
			{
				
				$catClientObj = new Category_list_client();
				
				$subcategoryArray = $catClientObj->getSubcategoryBasedontier($appId,4,$type,$typeId);
				if(count($subcategoryArray)==0)
				{
					$errorArray[]="This listing does not belong to Tier 4 Subcategory";
				}
				
			}
			// Case for checking BasePropertyId for Tier 5 Subcategory			
			else if($property['BasePropertyId']==30)
			{
				
				$catClientObj = new Category_list_client();
				
				$subcategoryArray = $catClientObj->getSubcategoryBasedontier($appId,5,$type,$typeId);
				if(count($subcategoryArray)==0)
				{
					$errorArray[]="This listing does not belong to Tier 5 Subcategory";
				}
				
			}
			// Case for checking BasePropertyId for Tier 1 city
			else if($property['BasePropertyId']==31)
			{
				$catClientObj = new Category_list_client();
				
				$stateArray = $catClientObj->getstateBasedontier($appId,1,$type,$typeId);
				if(count($stateArray)==0)
				{
					$errorArray[]="This listing does not belong to Tier 1 state";
				}

			}
			// Case for checking BasePropertyId for Tier 2 city			
			else if($property['BasePropertyId']==32)
			{
			
				$catClientObj = new Category_list_client();
				
				$stateArray = $catClientObj->getstateBasedontier($appId,2,$type,$typeId);
				if(count($stateArray)==0)
				{
					$errorArray[]="This listing does not belong to Tier 2 state";
				}

			}
			// Case for checking BasePropertyId for Tier 3 city
			else if($property['BasePropertyId']==33)
			{
			
				$catClientObj = new Category_list_client();
				
				$stateArray = $catClientObj->getstateBasedontier($appId,3,$type,$typeId);
				if(count($stateArray)==0)
				{
					$errorArray[]="This listing does not belong to Tier 3 state";
				}

			}
			// Case for checking BasePropertyId for Tier 1 country 
			else if($property['BasePropertyId']==34)
			{
			
				$catClientObj = new Category_list_client();
				
				$countryArray = $catClientObj->getCountrydependOnTier($appId,1,$type,$typeId);
				if(count($countryArray)==0)
				{
					$errorArray[]="This listing does not belong to Tier 1 country";
				}

			}
			// Case for checking BasePropertyId for Tier 2 country 
			else if($property['BasePropertyId']==35)
			{
			
				$catClientObj = new Category_list_client();
				
				$countryArray = $catClientObj->getCountrydependOnTier($appId,2,$type,$typeId);
				if(count($countryArray)==0)
				{
					$errorArray[]="This listing does not belong to Tier 2 country";
				}

			}
				
			
		}
		$resultArray['TestCategory']=$testprepArray;
		$resultArray['CountryList']=$countryArray;
		$resultArray['cityList']=$cityArray;
		$resultArray['categoryList']=$categoryArray;
		$resultArray['subcategoryList']=$subcategoryArray;
		$resultArray['stateList']=$stateArray;

		$result['result']=$resultArray;
		$result['error']=$errorArray;
		return $result;
		}
		
	/*
	 * This function returns subcategory,city,state and country tier based on subscription id
	*/	
		
	function getTiersBasedOnsubscription($subscriptionId) {
		
		if($subscriptionId <=0) {
				return array();
		}
		
		$this->load->library('Subscription_client');
		$objSubs = new Subscription_client();
		
		$data =  $objSubs->getMainCollegeLinkSubscriptionDetails($subscriptionId);
		$response = array();
		
		if(count($data) >0) {
			
			foreach($data as $property) {
				
				if($property['BasePropertyId']==11) {
							$response['city_tier'] = 1;
				} else if($property['BasePropertyId']==12) {
							$response['city_tier'] = 2;
				} else if($property['BasePropertyId']==13) {
							$response['city_tier'] = 3; 	
				} else if($property['BasePropertyId']==26) {
					        $response['subcat_tier'] = 1; 
				} else if($property['BasePropertyId']==27) {
					        $response['subcat_tier'] = 2; 
				} else if($property['BasePropertyId']==28) {
					        $response['subcat_tier'] = 3; 
				} else if($property['BasePropertyId']==29) {
					        $response['subcat_tier'] = 4; 
				} else if($property['BasePropertyId']==30) {
					        $response['subcat_tier'] = 5; 
				} else if($property['BasePropertyId']==31) {
					        $response['subcat_tier'] = 6; 
				} else if($property['BasePropertyId']==32) {
					        $response['state_tier'] = 1; 
				} else if($property['BasePropertyId']==33) {
					        $response['state_tier'] = 2; 
				} else if($property['BasePropertyId']==34) {
					        $response['state_tier'] = 3; 
				} else if($property['BasePropertyId']==35) {
					        $response['country_tier'] = 1; 
				} else if($property['BasePropertyId']==36) {
					        $response['country_tier'] = 2; 
				}
			}
				
		}
		
		return $response;
		
	}

	function getSubscriptionDetails($subscriptionId=''){

		$this->getDbHandle('read');
		$selectFields = array(
			"BaseProdRemainingQuantity",
			"SubscriptionStartDate",
			"SubscriptionEndDate",
		);

		$this->dbHandle->select($selectFields);
		$this->dbHandle->from("Subscription_Product_Mapping mapping");
		$joinConditions = array(
			"mapping.BaseProductId = products.BaseProductId",
			"mapping.Status='ACTIVE'",
			"mapping.SubscriptionId = '$subscriptionId'"
		);
		$this->dbHandle->join("Base_Products products", implode(" AND ", $joinConditions), "inner");
		$result = $this->dbHandle->get()->result();

		return $result;
	}
		
	/*
	 *Function to get SubscriptionIds which have transaction start date after specified date in parameters
	 *Parameters : SubscriptionIds and Date,for which check has to be done
	 *Return: Filtered  SubscriptionIds after Date check is implemented
	*/	
	function getSubscriptionAfterDate($subscriptionIds = array(),$date = '00-00-00'){
		//Check to avoid empty subscriptionIds and null date
		if(empty($subscriptionIds) || $date == '00-00-00'){
			return array();
		}
		
		$sql = "select s.SubscriptionId from Subscription s, Transaction t ";
		$sql .= "where s.TransactionId = t.TransactionId ";
		$sql .= "and DATE( t.TransactTime ) >  ? ";
		$sql .= "and s.SubscriptionId in(?) ";
		//echo "sql:".$sql;
		
		$this->getDbHandle('read');
		
		$resultSet = $this->dbHandle->query($sql, array($date, $subscriptionIds))->result_array();
		
		foreach($resultSet as $result){
			$resultSubscriptionIds[] = $result['SubscriptionId'];
		}
		return $resultSubscriptionIds;
		
	}

    /*
     * Function internal for CRON (NO need to check SQL INJ):: filter zero subscription date subscription ids 
     */

 	function filterSubscriptionWithZeroExpiryDate($subscriptionIds = array(),$date = '00-00-00'){
 		if(empty($subscriptionIds)){
 			return array();
 		}

 		$sql = "SELECT SubscriptionId FROM Subscription_Product_Mapping ".
 			   " WHERE SubscriptionId IN ( ? ) ".
 			   " AND date(SubscriptionEndDate) = '0000-00-00' ".
 			   " AND Status IN ('INACTIVE')";

 		$this->getDbHandle('read');
		
		$resultSubscriptionIds1 = array();
		$resultSet = $this->dbHandle->query($sql, array($subscriptionIds))->result_array();
		foreach($resultSet as $result){
			$resultSubscriptionIds1[] = $result['SubscriptionId'];
		}	   
		
 		$sql = "SELECT SubscriptionId FROM Subscription_Product_Mapping ".
	   		   " WHERE SubscriptionId IN ( ? ) ".
	   		   " AND DATE(SubscriptionEndDate) < ? ".
	   		   " AND Status IN ('INACTIVE','ACTIVE')";	   
 		
 		$resultSubscriptionIds2 = array();
	   	$resultSet = $this->dbHandle->query($sql, array($subscriptionIds, $date))->result_array();
		foreach($resultSet as $result){
			$resultSubscriptionIds2[] = $result['SubscriptionId'];
		}
		$resultSubscriptionIds = array();
		$resultSubscriptionIds = array_merge($resultSubscriptionIds1,$resultSubscriptionIds2);	   
 		return $resultSubscriptionIds;
 	}

 	function getSubscriptionStatusForClient($clientId,$DerivedProductId,$BaseProductId){
 		$this->getDbHandle('read');
		
		$sql = "select S1.SubscriptionId from Subscription S1 join Subscription_Product_Mapping S2 
					on S1.SubscriptionId = S2.SubscriptionId where S1.DerivedProductId =? and S1.BaseProductId =?
					S1.'SubscrStatus' = active and S2.SubscriptionStartDate < now() and S2.SubscriptionEndDate > now()
					and S1.ClientUserId = ?";

		$resultSet = $this->dbHandle->query($sql,array($DerivedProductId,$BaseProductId,$clientId))->num_rows();

		return $resultSet;
 	}


 	public function getSODetails($soNumbers){

 		$this->getDbHandle('read');

 		$sqlSubscriptionDetails = "SELECT S.NavSubscriptionId, S.SubscriptionId, S.TransactionId, 
 									S.DerivedProductId, DP.DerivedProductName, DP.DerivedProductDescription, 
 									S.nav_subscription_line_no, S.SubscrStatus,
 									SPM.TotalBaseProdQuantity, SPM.BaseProdRemainingQuantity, 
 									SPM.SubscriptionStartDate, SPM.SubscriptionEndDate
 									FROM Subscription S 
 									LEFT JOIN Subscription_Product_Mapping SPM 
 									ON S.SubscriptionId = SPM.SubscriptionId AND S.SubscrStatus = SPM.Status 
 									LEFT JOIN Derived_Products DP
 									ON S.DerivedProductId = DP.DerivedProductId 
 									WHERE S.NavSubscriptionId in ( ? )";

 		$resultSubscriptionDetails = $this->dbHandle->query($sqlSubscriptionDetails, array($soNumbers))->result_array();
 		foreach ($resultSubscriptionDetails as $key => $valueArray) {
 			$finalReturnArray[] = $valueArray;
 		}
 		
 		return $finalReturnArray;

 	}

 	public function getAllSubscriptionsForListings($userId){
 		$this->getDbHandle('read');

        $queryCmd = "select S.SubscriptionId,SubscriptionStartDate,SubscriptionEndDate,TotalBaseProdQuantity, BaseProdRemainingQuantity, B.BaseProductId, BaseProdCategory,BaseProdSubCategory, S.Status, SubscrStatus from Subscription_Product_Mapping S,Base_Products B, Subscription Su where S.SubscriptionId = Su.SubscriptionId AND Su.ClientUserId=? AND Su.SubscrStatus in ('ACTIVE','INACTIVE') AND S.BaseProductId=B.BaseProductId AND S.BaseProdRemainingQuantity >= 0 AND date(S.SubscriptionEndDate) >= curdate() AND date(S.SubscriptionStartDate) <= curdate() AND S.Status in ('ACTIVE','INACTIVE') order by S.SubscriptionEndDate ASC";

		$query = $this->dbHandle->query($queryCmd, array($userId));
		
		$response = $query->result_array();

		return $response;

 	}

	// Get all ACTIVE Subscriptions for a user
	function getAllSubscriptionsForUser($userId, $baseProductId) {
	
		$this->getDbHandle('read');
		
        $queryCmd = "select S.SubscriptionId, S.BaseProdRemainingQuantity,S.SubscriptionEndDate from Subscription_Product_Mapping S, Subscription Su where S.SubscriptionId = Su.SubscriptionId AND Su.ClientUserId=? AND Su.SubscrStatus=? AND S.Status=? AND S.BaseProdRemainingQuantity >= ? AND S.BaseProductId = ? AND S.SubscriptionEndDate >= ? AND S.SubscriptionStartDate <= ?";
        $todayDate = date('Y:m:d 00:00:00');
        $todayEndDate = date('Y:m:d 23:59:59');
		$query = $this->dbHandle->query($queryCmd, array($userId, 'ACTIVE','ACTIVE', 1, $baseProductId, $todayDate, $todayEndDate));
		$response = $query->result_array();

		return $response;
	}

}
?>
