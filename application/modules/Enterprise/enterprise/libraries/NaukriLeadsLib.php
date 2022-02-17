<?php

class NaukriLeadsLib {

	private $CI;

	function __construct(){
		$this->CI = & get_instance();
		$this->NLAccessModel = $this->CI->load->model('enterprise/naukrileadsmodel');
		$this->CI->load->config("enterprise/enterpriseConfig");
	}


	

	function saveNaukriLeadsData($naukri_leads_data){

		$lead_state_city_data = $this->NLAccessModel->getStateByCity($naukri_leads_data['city']);

		$naukri_leads_data['city_id']  = $lead_state_city_data['city_id'];
		$naukri_leads_data['state_id'] = $lead_state_city_data['state_id'];
		unset($naukri_leads_data['city']);

		if($naukri_leads_data['city_id']<1 || $naukri_leads_data['state_id']< 1){
		  mail('teamldb@shiksha.com', 'City and state did not match', $naukri_leads_data);
		  return;
		}

		$this->NLAccessModel->saveNaukriLeadsData($naukri_leads_data);

  	}

  	function getStates() {

        $result = array();
        $result = $this->NLAccessModel->getNaukriStatesList();
        $states = array();
        foreach ($result as $key => $value) {
            $states[$value['state_id']] = $value['state_name'];
        }
        return $states;

    }

    public function saveNLSubscriptionData($data) {

        $subscriptionData = array(
            'client_id'     => $data['clientId'],
            'client_name'   => $data['clientName'],
            'state_ids'     => implode(",", $data['states']),
            'created_by'    => $data['createdBy'],
            'campaign_type' => $data['campaignType']
        );

        if(!empty($data['accountManagerName'])) {
            $subscriptionData['account_manager_name'] = $data['accountManagerName'];
        }

        if($data['campaignType'] == "duration") {
            $subscriptionData['start_date'] = $data['timeRangeDurationFrom'];
            $subscriptionData['end_date']   = $data['timeRangeDurationTo'];
        } else {
            $subscriptionData['start_date']        = $data['creationDateFrom'];
            $subscriptionData['quantity_expected'] = $data['quantityExpected'];
        }

        if(!empty($data['email'])){
            $subscriptionData['email'] = $data['email'];
        }

        if(!empty($data['mobileNo'])){
            $subscriptionData['mobile'] = $data['mobileNo'];
        }

        $response = $this->NLAccessModel->saveNaukriLeadsSubscriptionData($subscriptionData);
        
        if($response == false){
            return "FAIL";
        } else {
            return "SUCCESS";
        }
    }

    public function isNewSubscription($clientId) {

        if(!empty($clientId) && $clientId > 0){
            $result = $this->NLAccessModel->checkValidSubscriptionForClient($clientId);

            if($result) {
                return '1';
            } else {
                return '0';
            }

        } else {
            return '0';
        }
        
    }

	public function getSubscriptionData($type,$clientId = ''){
		if(!in_array($type, array("active"))){
		  return array();
		}

		$data = $this->NLAccessModel->getSubscriptionData($type, $clientId);
		if(count($data) <=0 || $data == false){
		  return array();
		}

		$allStates = $this->getUserLocationDetails();

		foreach ($data as $key => $subscriptionDetail) {

		  $toopTipInput = array();	
		  
		  if(empty($subscriptionDetail['state_ids'])){
		    $data[$key]['userLocations'] = "All India";
		    $data[$key]['userLocationsToolTip'] = "All India";
		  }else{
		    $userLocationIds = explode(",", $subscriptionDetail['state_ids']);  
		    $toopTipInput = array();
		    foreach ($userLocationIds as $locationId) {
		      $toopTipInput[]= $allStates[$locationId];
		    }
		    $toopTipInput = $this->createToolTipAndHeading($toopTipInput);
		    $data[$key]['userLocations'] = $toopTipInput['heading'];
		    $data[$key]['userLocationsToolTip'] = $toopTipInput['toopTip'];
		    unset($toopTipInput);
		  }
		  unset($data[$key]['userLocationIds']);

		  if(empty($subscriptionDetail['end_date'])){
		    $data[$key]['end_date'] = "None";
		  }else{
		    $data[$key]['end_date'] = date("d-m-Y",strtotime($subscriptionDetail['end_date']));
		    $data[$key]['quantity_expected'] = "Indefinite";
		  }

		  $data[$key]['start_date'] = date("d-m-Y",strtotime($subscriptionDetail['start_date']));
		}
		//_p($data);die;
		return $data;
	}

	function createToolTipAndHeading($data){
		$returnData = array();
		$returnData['toopTip'] = implode(", ", $data);
		$count = count($data);
		if($count >1){
		  $returnData['heading'] = $data[0].', '.$data[1];
		  $returnData['heading'] .= ($count > 2)?', ...':'';
		}else{
		  $returnData['heading'] = $data[0];
		}
		return $returnData;
	}

	public function getUserLocationDetails(){

		$data = array();
		$data = $this->NLAccessModel->getAllStates();

		$allStates = array();
		foreach ($data as $key => $stateDetail) {
		  $allStates[$stateDetail['state_id']] = $stateDetail['state_name'];
		}

		return $allStates;
	}

	public function updateSubscriptionStatus($subscriptionId, $status){
		if(empty($subscriptionId) || $subscriptionId <=0){
		  return false;
		}

		if(empty($status)){
		  return false;
		}

		$response = $this->NLAccessModel->updateSubscriptionStatus($subscriptionId, $status);
		
		if($response == false){
		  return "FAIL";
		}else{
		  return "SUCCESS";
		}
	}

    public function markSubscriptionProcessed($subscription_id){
		

		$this->NLAccessModel->markSubscriptionProcessed($subscription_id);
	}

	function getLastProcessedId()
	{

		$this->CI->load->config("enterprise/enterpriseConfig");
		$last_process_id = $this->NLAccessModel->getLastProcessedId();

		return $last_process_id;
	}

	public function updateDeliveredCountForSubscription($matched_subscriptions, $quantity_to_add = 1){
		

		$this->NLAccessModel->updateDeliveredCountForSubscription($matched_subscriptions, $quantity_to_add);
	}


	public function getUserAllocationData($userIds){
		if(!is_array($userIds) || count($userIds) <1){
			return false;
		}

		
		$result = $this->NLAccessModel->getUserAllocationData($userIds);
		
		$userAllocationMapping = array();
		foreach ($result as $key => $value) {
			$userAllocationMapping[$value['userId']][$value['subscriptionId']] = 1;
		}

		return $userAllocationMapping;
	}

	public function processQuantityBasedSusbcription($subscription, $last_processed_id)
	{
		
		
		$user_state 			= $subscription['state_ids'];
		$start_date 		= $subscription['start_date'];
		$quantity_expected 	= $subscription['quantity_expected'];

		$leads = $this->NLAccessModel->getUsersForQuantityBased($user_state, $last_processed_id, $start_date, $quantity_expected,$subscription['client_id']);
		//_p("LEADS");
		//_p($subscription);
		//_p($leads);
		$flag = 0;
		foreach ($leads as $value) 
		{
			$flag =1;
			$user_ids[] = $value->id;
		}	
		if($flag ==0)
		{
			return;
		}

		// REduce the amount and track it 

		$leadCount = $this->storeMatchedResponses($leads, $subscription['client_id'], $subscription['id'],$subscription['campaign_type'],$quantity_expected,$subscription['client_id']);
		$this->updateDeliveredCountForSubscription(array($subscription['id']), $leadCount);
		
	}

	public function processDurationBasedSusbcription($subscription, $last_processed_id){
		
		$user_state 			= $subscription['state_ids'];
		$start_date 		= $subscription['start_date'];	
		$leads = $this->NLAccessModel->getLeadsForDurationBased($user_state, $last_processed_id, $start_date,$subscription['client_id']);			
		

		if(count($leads)<1){
			return;
		}
		
		$responseCount = $this->storeMatchedResponses($leads, $subscription['client_id'], $subscription['id']);
		$this->updateDeliveredCountForSubscription(array($subscription['id']), $responseCount);
		
	}

	public function getMatchedSubscriptions($state_id,$submit_date =''){
		
		if(!empty($submit_date)){
			$submit_date = date("Y-m-d",strtotime($submit_date));
		}
		else{
			return;
		}
		
		$today_date = date('Y-m-d');
		
		$matched_subscriptions = $this->NLAccessModel->getGroupMatchedSubscriptions($submit_date);
		
		$temp_subscription_id = array();
		
		foreach ($matched_subscriptions as $value) {

			
				if($value->campaign_type == 'quantity' && ($value->quantity_expected - $value->quantity_delivered) <1) {
					continue;
				}

				if($value->campaign_type == 'duration' && ($value->end_date < $today_date ) ) {
					continue;
				}
			

			$temp_subscription_id[] =  $value->id;
			//$map_subscription_groupId[$value->subscriptionId] = $value->entityValue; 
		}

		$matched_subscriptions = $temp_subscription_id;
		
		unset($temp_subscription_id);
		
		if (count($matched_subscriptions) < 1) {
			
			return array();
		}

		$return_matched_subscription = array();
		
		
		$result = $this->NLAccessModel->getCityMatchedSubscriptions($matched_subscriptions, $state_id);
		
		$matched_subscriptions = array();

		
		
	    foreach ($result as $subscription) {
	    	if (empty($subscription->state_ids))
	    	{
	    		array_push($matched_subscriptions, $subscription);
	    		continue;
	    	}
	    	$state = explode(",",$subscription->state_ids);
	    	
			if (array_intersect($state_id,$state))
			{
				array_push($matched_subscriptions, $subscription);
			}
		}	
		
		foreach ($matched_subscriptions as $subscription) {
			$return_matched_subscription[$subscription->id] = $subscription->client_id;
		}

		unset($matched_subscriptions);

		return $return_matched_subscription;

	}


	public function filterMatchedSubscription($userAllocationMapping, $matchedSubscriptions, $lead){
		foreach ($matchedSubscriptions as $subscriptionId => $clientId) {
			if($userAllocationMapping[$lead['id']][$subscriptionId] == 1){
				unset($matchedSubscriptions[$subscriptionId]);
			}
		}
		return $matchedSubscriptions;
		//_p($userAllocationMapping);_p($matched_subscriptions);_p($response);die;
	}
	public function updateLastProcessedId($recentProcessedId){
		
		$this->NLAccessModel->updateLastProcessedId($recentProcessedId);
	}

	public function markSubscriptionInactive(){
		
		$result = $this->NLAccessModel->getSubscription();
		$subscriptionIds = array();
		$todayDate = date('Y-m-d');
		foreach ($result as $key => $subscription) {
			if($subscription['campaign_type'] == "quantity"){
				if(($subscription['quantity_expected'] - $subscription['quantity_delivered']) < 1){
					$subscriptionIds[] = $subscription['id'];
				}
			}else{
				if($subscription['end_date'] < $todayDate){
					$subscriptionIds[] = $subscription['id'];
				}
			}
		}
		//_p($subscriptionIds);die;
		$this->NLAccessModel->markSubscriptionInactive($subscriptionIds);
	}

	public function getSubscriptionContactDetails($subscriptionId){
		
		$subscription_data = $this->NLAccessModel->getSubscriptionData('','',$subscriptionId);

		$contact_data['email'] = $subscription_data[0]['email'];
		$contact_data['mobile'] = $subscription_data[0]['mobile'];
		$contact_data['clientId'] = $subscription_data[0]['client_id'];
		$contact_data['clientName'] = $subscription_data[0]['client_name'];
		return $contact_data;
	}

	public function storeIndividualMatchedSubscription($lead, $clientIds){
		

		if (count($client_ids)<0)
		{
			return;
		}
		$matched_subscriptions = array();
		$dataForMatchedSubscription = array();
		$validClientMappings = array();
		$query = '';
		foreach($clientIds  as $subscriptionId => $clientId)
		{

			$subscriptionWiseCount = $this->NLAccessModel->deductCreditsForNaukriLeads(array($lead),$clientId,CREDITS_FOR_NAUKRI_LEADS);
			if (!empty($subscriptionWiseCount))
			{
				$dataForMatchedSubscription[] = $subscriptionId;
				$dataForMatchedSubscription[] = $lead['id'];
				$dataForMatchedSubscription[] = $clientId;
				$matched_subscriptions[$subscriptionId] = $clientId;
				$query = $query.'(?,?,?),';
				reset($subscriptionWiseCount);
				$sumsSubscription = key($subscriptionWiseCount);
				$validClientMappings[$subscriptionId] = array($clientId,$sumsSubscription);
			
			}
			
		}
		if (empty($dataForMatchedSubscription))
		{
			return;
		}
		$this->NLAccessModel->storeIndividualMatchedSubscription($dataForMatchedSubscription,substr($query, 0,-1));
		
		$this->NLAccessModel->insertInSubscriptionLogIndividual($validClientMappings,CREDITS_FOR_NAUKRI_LEADS,$lead['id']);
		$this->NLAccessModel->insertInNaukriCreditDeductionIndividual($validClientMappings,CREDITS_FOR_NAUKRI_LEADS,$lead['id']);
		return $matched_subscriptions;
	}

	public  function storeMatchedResponses($leads, $clientId, $subscriptionId,$campaignType,$quantity_expected=0){
		
		if(count($leads)<1){ 
    		return;
    	}
   
		$subscriptionWiseCount = $this->NLAccessModel->deductCreditsForNaukriLeads($leads,$clientId,CREDITS_FOR_NAUKRI_LEADS);
		//transactionOver
		if(empty($subscriptionWiseCount))
		{
			return;
		}

		$allocatedLeads =0;
		foreach ($subscriptionWiseCount as $key => $value) {
			$allocatedLeads += $value;
		}
		$leads = array_slice($leads, 0,$allocatedLeads);
		$this->NLAccessModel->storeMatchedResponses($leads, $clientId, $subscriptionId,$campaignType,$quantity_expected);
		
		$bucketWiseLeads = array();
		$tillProcessed =0;

		foreach($subscriptionWiseCount as $subId => $count)
		{
			$bucketWiseLeads[$subId] = array_slice($leads, $tillProcessed,$count);
			$tillProcessed += $count;
		}

		$this->NLAccessModel->insertInSubscriptionLog($bucketWiseLeads,CREDITS_FOR_NAUKRI_LEADS,$clientId);
		$this->NLAccessModel->insertInNaukriCreditDeduction($bucketWiseLeads,CREDITS_FOR_NAUKRI_LEADS,$clientId,$subscriptionId);

		return $allocatedLeads;
	}

	public function getLeadData($clientId,$startDate,$endDate)
	{
		if(!empty($startDate)){
			$startDate = $this->convertDate($startDate,'start');
			$endDate = $this->convertDate($endDate,'end');
		
		}
		$allocatedLeads = $this->NLAccessModel->getLeadData($clientId,$startDate,$endDate);
		
		return $allocatedLeads;
	}

	public function convertDate($date,$flag)
	{
		$date = str_replace("/", "-", $date);
      	$date = date("Y-m-d",strtotime($date));
		if($flag =='start')
			return $date." 00:00:00";
		return $date." 23:59:59";
	}

	public function getCityStateMapping()
	{
		$cityStateMapping = $this->NLAccessModel->getCityStateMapping();
		$city = array();
		$state = array();
		foreach($cityStateMapping as $cityStateValues)
		{
			$city[$cityStateValues['city_id']] = $cityStateValues['city_name'];
			$state[$cityStateValues['state_id']] = $cityStateValues['state_name'];

		}
		return array($city,$state);
	}
}

?>
