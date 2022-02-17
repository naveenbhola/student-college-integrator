<?php

/** 
 * Library for response Creation/ Response Mail Sending.
*/

class responseAllocationLib {

	private $userElasticSearch = true;

	function __construct() {
		$this->CI = & get_instance();

		$ESConnectionLib = $this->CI->load->library('trackingMIS/elasticSearch/ESConnectionLib');
        //$this->ESClientConn = $ESConnectionLib->getESServerConnection();
        $this->ESClientConn = $ESConnectionLib->getShikshaESServerConnection();
        
	}

	/**
	* This function load all necessary config, model related to response which will be used in various flows 
	*/
	function _init(){
		
		$this->CI->load->model('response/responsemodel');
		$this->responseModel = new ResponseModel();

	}

	public function markSubscriptionInactive(){
		$this->_init();
		$result = $this->responseModel->getSubscription();
		//_p($result);die;
		$subscriptionIds = array();
		$todayDate = date('Y-m-d');
		foreach ($result as $key => $subscription) {
			if($subscription['campaignType'] == "quantity"){
				if(($subscription['quantityExpected'] - $subscription['quantityDelivered']) < 1){
					$subscriptionIds[] = $subscription['id'];
				}
			}else{
				if($subscription['endDate'] < $todayDate){
					$subscriptionIds[] = $subscription['id'];
				}
			}
		}
		//_p($subscriptionIds);die;
		$this->responseModel->markSubscriptionInactive($subscriptionIds);
	}

	public function updateDeliveredCountForSubscription($matched_subscriptions, $quantity_to_add = 1){
		$this->_init();

		$this->responseModel->updateDeliveredCountForSubscription($matched_subscriptions, $quantity_to_add);
	}

	public  function storeMatchedResponses($responses, $client_id, $entityType, $subscriptionId,$campaignType,$quantity_expected=0){
		$this->_init();
		return $this->responseModel->storeMatchedResponses($responses, $client_id, $entityType, $subscriptionId,$campaignType,$quantity_expected);
	}

	public function getLastProcessedId(){
		$this->_init();
		$last_process_id = $this->responseModel->getLastProcessedId();

		return $last_process_id;
	}

	public function updateLastProcessedId($recent_processed_id){
		$this->_init();
		$this->responseModel->updateLastProcessedId($recent_processed_id);
	}

	public function storeIndividualMatchedSubscription($user_response_data, $client_ids){
		$this->_init();
		$this->responseModel->storeIndividualMatchedSubscription($user_response_data, $client_ids);
	}

	public function getMatchedSubscriptions($exam_group_id, $city_id, $isResponseFlag = false,$submit_date =''){
		$this->_init();

		// -1 is being pushed for all cities
		if(is_array($city_id)){
			array_push($city_id, -1);
		}
		if(!empty($submit_date)){
			$submit_date = date("Y-m-d",strtotime($submit_date));
		}else{
			$submit_date = date("Y-m-d");
		}
		
		$today_date = date('Y-m-d');

		$matched_subscriptions = $this->responseModel->getGroupMatchedSubscriptions($exam_group_id,$submit_date);

		$temp_subscription_id = array();
		
		foreach ($matched_subscriptions as $value) {

			if(!$isResponseFlag){
				if($value->campaignType == 'quantity' && ($value->quantityExpected - $value->quantityDelivered) <1) {
					continue;
				}

				if($value->campaignType == 'duration' && ($value->endDate < $today_date ) ) {
					continue;
				}
			}

			$temp_subscription_id[] =  $value->subscriptionId;
			//$map_subscription_groupId[$value->subscriptionId] = $value->entityValue; 
		}

		$matched_subscriptions = $temp_subscription_id;
		
		unset($temp_subscription_id);
		
		if (count($matched_subscriptions) < 1) {
			if($isResponseFlag){
				return false;
			}
			return array();
		}

		$return_matched_subscription = array();
		
		$matched_subscriptions = $this->responseModel->getCityMatchedSubscriptions($matched_subscriptions, $city_id);
		if($isResponseFlag){
			if(count($matched_subscriptions) > 0){
				return true;
			} else {
				return false;
			}
		}

		foreach ($matched_subscriptions as $subscription) {
			$return_matched_subscription[$subscription->subscriptionId] = $subscription->clientId;
		}

		unset($matched_subscriptions);

		return $return_matched_subscription;

	}

	public function processQuantityBasedSusbcription($subscription, $last_processed_id){
		$this->_init();

		$LOCAL_USE_ELASTIC_SEARCH = $this->userElasticSearch;

		if($LOCAL_USE_ELASTIC_SEARCH){
			$pingElastic = $this->checkElasticRunningStatus();
		}

		if(!$pingElastic){
			$LOCAL_USE_ELASTIC_SEARCH = false;
		}

		if(USE_ELASTIC_SEARCH && $LOCAL_USE_ELASTIC_SEARCH){
			$responses = $this->getExamResponsesFromElastic($subscription, $last_processed_id, 'quantity');

		}else{			
			$user_city 			= $subscription['userLocationIds'];
			$user_exam_group	= explode(',',$subscription['groupIds']);
			$start_date 		= $subscription['startDate'];
			$quantity_expected 	= $subscription['quantityExpected'];

			$responses = $this->responseModel->getUsersForQuantityBased($user_city, $user_exam_group, $last_processed_id, $start_date, $quantity_expected);
			
			foreach ($responses as $value) {
				$user_ids[] = $value->userId;
			}

			if(count($user_ids)>0){
				$responses = $this->responseModel->getResponsesForQuantityBased($user_ids, $user_exam_group);
			}
		}

		if(count($responses)<1){
			return;
		}

		$entityType = 'examGroup';

		$responseCount = $this->storeMatchedResponses($responses, $subscription['clientId'], $entityType, $subscription['id'],$subscription['campaignType'],$quantity_expected);
		$this->updateDeliveredCountForSubscription(array($subscription['id']), $responseCount);
		
	}

	public function processDurationBasedSusbcription($subscription, $last_processed_id){
		$this->_init();

		$LOCAL_USE_ELASTIC_SEARCH = $this->userElasticSearch;

		if($LOCAL_USE_ELASTIC_SEARCH){
			$pingElastic = $this->checkElasticRunningStatus();
		}

		if(!$pingElastic){
			$LOCAL_USE_ELASTIC_SEARCH = false;
		}


		if(USE_ELASTIC_SEARCH && $LOCAL_USE_ELASTIC_SEARCH){
			$responses = $this->getExamResponsesFromElastic($subscription, $last_processed_id, 'duration');

		}else{

			$user_city 			= $subscription['userLocationIds'];
			$user_exam_group	= explode(',',$subscription['groupIds']);
			$start_date 		= $subscription['startDate'];	

			$responses = $this->responseModel->getResponsesForDurationBased($user_city, $user_exam_group, $last_processed_id, $start_date);			
		}

		if(count($responses)<1){
			return;
		}

		$entityType = 'examGroup';

		$responseCount = $this->storeMatchedResponses($responses, $subscription['clientId'], $entityType, $subscription['id']);
		$this->updateDeliveredCountForSubscription(array($subscription['id']), $responseCount);

	}

	public function markSubscriptionProcessed($subscription_id){
		$this->_init();

		$this->responseModel->markSubscriptionProcessed($subscription_id);
	}

	public function checkElasticRunningStatus(){
		/*$ESConnectionLib = $this->CI->load->library('trackingMIS/elasticSearch/ESConnectionLib');
        $this->ESClientConn = $ESConnectionLib->getESServerConnection();
*/
        $elastic_running_status = true;

        if (!$this->ESClientConn->ping() ) {
        	$elastic_running_status = false;

			usleep(100000);
			if (!$this->ESClientConn->ping() ) {
				$elastic_running_status	= false;
			}else{
				$elastic_running_status	= true;
			}
		}

        return $elastic_running_status;

	}

	public function getExamResponsesFromElastic($subscription, $last_processed_id, $subscription_type){
		$queryGenerator   = $this->CI->load->library("response/responseProcessorElasticQueryGenerator");
        $responseParser   = $this->CI->load->library("response/responseProcessorElasticParser");
        /*$ESConnectionLib = $this->CI->load->library('trackingMIS/elasticSearch/ESConnectionLib');
        $this->ESClientConn = $ESConnectionLib->getESServerConnection();*/
		
		if(!empty($subscription['userLocationIds'])){
			$userLocationIds = explode(',',$subscription['userLocationIds']);
		}

		/*if ( $userLocationIds[0] <1 ) {
			unset($userLocationIds);
		}*/

		$entityIds	= explode(',',$subscription['groupIds']);
		$entityType = 'exam';
		$dateRange['from'] = $subscription['startDate'];
		$dateRange['to'] = date('Y-m-d');

		/*if($subscription_type == 'duration'){
			$dateRange['to'] = $subscription['endDate'];	
		}*/

		$quantity_expected = 100000;

		if($subscription['quantityExpected']>0){
			$quantity_expected 	= $subscription['quantityExpected'];
		}

		$getFeilds = array('user_id','listing_type_id','latest_response_action_type','temp_LMS_id');

		$customData['last_processed_id'] = $last_processed_id;
		$customData['sort'][] = array('key'=>'response_time','order'=>'asc');
		$start_row = 0;

		$queryParams 		= $queryGenerator->prepareResponseQuery($entityIds, $userLocationIds, $dateRange, $entityType, $quantity_expected, $start_row, $getFeilds, $customData);
		$response    		= $this->ESClientConn->search($queryParams);
		$queryResults      	= $responseParser->parseExamSubscriptionResponse($response);  	

	    foreach ($queryResults as $result) {
	    	$temp_result['userId'] = $result['_source']['user_id'];
	    	$temp_result['action'] = $result['_source']['latest_response_action_type'];
	    	$temp_result['listing_type_id'] = $result['_source']['listing_type_id'];
	    	$temp_result['id'] = array_values(array_slice($result['_source']['temp_LMS_id'], -1))[0];

	    	$return_result[] = $temp_result;
	    	unset($temp_result);
	    }

	   return $return_result;

	}

	public function getUserAllocationData($userIds){
		if(!is_array($userIds) || count($userIds) <1){
			return false;
		}

		$this->_init();
		$result = $this->responseModel->getUserAllocationData($userIds);
		$userAllocationMapping = array();
		foreach ($result as $key => $value) {
			$userAllocationMapping[$value['userId']][$value['entityValue']][$value['subscriptionId']] = 1;
		}
		//_p($userAllocationMapping);die;
		return $userAllocationMapping;
	}

	public function filterMatchedSubscription($userAllocationMapping, $matched_subscriptions, $response){
		foreach ($matched_subscriptions as $subscriptionId => $clientId) {
			if($userAllocationMapping[$response['userId']][$response['listing_type_id']][$subscriptionId] == 1){
				unset($matched_subscriptions[$subscriptionId]);
			}
		}
		return $matched_subscriptions;
		//_p($userAllocationMapping);_p($matched_subscriptions);_p($response);die;
	}

	public function getSubscriptionContactDetails($subscription_id){
		$this->CI->load->model('enterprise/examresponseaccessmodel');
		$this->examresponseaccessmodel = new examresponseaccessmodel();

		$subscription_data = $this->examresponseaccessmodel->getSubscriptionData('','',$subscription_id);
		
		$contact_data['email'] = $subscription_data[0]['email'];
		$contact_data['mobile'] = $subscription_data[0]['mobile'];
		$contact_data['clientId'] = $subscription_data[0]['clientId'];
		return $contact_data;
	}
}

?>
