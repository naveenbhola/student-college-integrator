<?php

/** 
 * Library for response Porting to Client.
*/

class responsePortingLib {

	function __construct() {
		$this->CI = & get_instance();	
	}

	/**
	* This function load all necessary config, model related to response which will be used in various flows 
	*/
	function _init(){
		
		$this->CI->load->model('response/responsemodel');
		$this->responseModel = new ResponseModel();

		$this->CI->config->load('response/responseConfig',TRUE);

	}

	public function addDataForPorting($responseId, $responseData) {
		
		$this->_init();

		$userId = $responseData['user_id'];
		$listingId = $responseData['listing_id'];
		$listingType = $responseData['listing_type'];
		$actionType = $responseData['action_type'];
		$userCityId = $responseData['city'];
		if(empty($userCityId)) {
			$userCityId = $responseData['residenceCityLocality'];
		}

		$clientMappingInfo = $this->CI->config->item('clientInfo','responseConfig');	
		$clientInfo = $clientMappingInfo[$listingType];

		$viewedActionTypes = $this->CI->config->item('viewedActionTypes','responseConfig');	
		
		if($listingType == 'course') {
			$courseViewedTypes = $viewedActionTypes['national']['course'];
			$instituteViewedTypes = $viewedActionTypes['national']['institute'];
			$allViewedTypes = array_merge($courseViewedTypes, $instituteViewedTypes);
		} else if($listingType == 'exam') {
			$allViewedTypes = $viewedActionTypes['national']['exam'];
		}

		$usersAllowedFromCities = $clientInfo[$listingId]['usersAllowedFromCities'];

		if( ($clientInfo[$listingId]) && (!in_array($actionType, $allViewedTypes)) && ((empty($usersAllowedFromCities)) || ((!empty($usersAllowedFromCities)) && ($usersAllowedFromCities[$userCityId]))) ) { 

			$existingQueueData = $this->responseModel->getExistingRecordByUserIdListingId($userId, $listingId, $listingType);

			if(empty($existingQueueData)) {
				$this->responseModel->addToResponsePortingQueue($responseId, $userId, $listingId, $listingType);
			}

		}

	}

	/**
	* This function is used for process response for porting
	*/
	public function processResponseDataForPortingToClient(){
		
		$this->_init();

		// check Service running or not
		$noOfPing = $this->CI->config->item('noOfPing','responseConfig');
		for($i=1; $i<=$noOfPing; $i++) {
			if($i > 1) { sleep(1); }
			$response = $this->checkService();
			if($response == 'alive') {
				break;
			}
		}			

		if($response == 'alive') {
			$this->processResponseData();
		} else {
			echo 'Python Service Not Responding<br/>';
			mail('teamldb@shiksha.com,mohd.alimkhan@shiksha.com,mohit.k1@shiksha.com','Python Service Not Responding at '.date('Y-m-d H:i:s'), 'Python Service Not Responding');	
		}

	}

	private function checkService() {

		$checkServiceName = $this->CI->config->item('checkService','responseConfig');

		$url = RESPONSE_PORT_URL.$checkServiceName;
		$response = $this->curlRequest($url);
		$response = json_decode($response,true);

		return $response;
		
	}

	/**
	* This function is used for process response for porting
	*/
	private function processResponseData() {

		$queuedData = $this->getQueuedData();

		if(empty($queuedData)) {
			echo 'No Data in Queue Available<br/>';
			return;
		}

		$filteredData = $this->getQueuedResponseAndUserData($queuedData);

		if(empty($filteredData)) {
			echo 'Some data missing for Queue Available<br/>';
			return;
		} else {
			$this->processData($filteredData);
			echo 'Cron Completed';
		}

	}

	/**
	* This function is used for fetching queued data
	*/
	private function getQueuedData() {

		$noOfTries = $this->CI->config->item('noOfTries','responseConfig');	
		$timeDifference = $this->CI->config->item('timeDifference','responseConfig');
		$recordsCount = $this->CI->config->item('recordsCount','responseConfig');

		$previousTime = time()-$timeDifference;
		$lastUpdatedTime = date("Y-m-d H:i:s", $previousTime);
		
		$courseRecordsCount = $recordsCount/2;
		$queuedData = $this->responseModel->getResponsePortingQueueData($noOfTries, $lastUpdatedTime, $courseRecordsCount, 'course');

		$leftCount = (int)$recordsCount-count($queuedData);
		if($leftCount > 0) {
			$courseQueuedData = $this->responseModel->getResponsePortingQueueData($noOfTries, $lastUpdatedTime, $leftCount, 'exam');
			$queuedData = array_merge($queuedData, $courseQueuedData);
		}
		return $queuedData;
	}

	private function getQueuedResponseAndUserData($queuedData) {

		$responseIds = array(); $formatedQueuedData = array();
		foreach($queuedData as $data) {
			$formatedQueuedData[$data['id']] = $data['responseId'];
			$responseIds[] = $data['responseId'];
		}

		$responses = array(); $userIds = array();
		$responsesData = $this->responseModel->getResponsesData($responseIds);

		if(empty($responsesData)) {
			return;
		}

		foreach($responsesData as $response) {
			$responses[$response['id']]['user_id'] = $response['user_id'];
			$responses[$response['id']]['listing_id'] = $response['listing_id'];
			$responses[$response['id']]['listing_type'] = $response['listing_type'];
			$userIds[] = $response['user_id'];
		}
	
		$userDetails = $this->getUserDetails($userIds);
		if(empty($userDetails)) {
			return;
		}

		$filteredData = array();
		foreach($formatedQueuedData as $queueId=>$responseId) {
			$userId = $responses[$responseId]['user_id'];
			$filteredData[$queueId] = $userDetails[$userId];
			
			$filteredData[$queueId]['response_id'] = $responseId;
			$filteredData[$queueId]['listing_id'] = $responses[$responseId]['listing_id'];			
			$filteredData[$queueId]['listing_type'] = $responses[$responseId]['listing_type'];			
		}
		unset($responses);
		unset($formatedQueuedData);

		return $filteredData;
	}

	private function processData($filteredData) {		

		$clientMappingInfo = $this->CI->config->item('clientInfo','responseConfig');

		$vendorMapping = $this->getVendorMapping($filteredData, $clientMappingInfo);

		foreach($filteredData as $lastQueueId=>$data) {

			$clientInfo = array();
			$clientInfo = $clientMappingInfo[$data['listing_type']];

			$userData = $this->prepareDataForPorting($data, $clientInfo, $vendorMapping);

			$userDataToBePorted = json_encode($userData);
			$lastInsertedId = $this->responseModel->storeResponsePortingData($lastQueueId, $userDataToBePorted);
			$this->responseModel->updateTriesCounts($lastQueueId);

			$clientData = $clientInfo[$data['listing_id']]['clientData'];
			$status = $this->portData($userData, $clientData, $lastInsertedId);

			$this->responseModel->updateSentDataStatus($status, $lastInsertedId); 

			if($status != 'success') {
				$this->responseModel->updateQueueStatus('failed', $lastQueueId);
			}
		}

	}

	private function getUserDetails($userIds) {		

		$this->CI->load->model('user/usermodel');
		$this->usermodel = new Usermodel();

		$userDetails = $this->usermodel->getUserDetailsByUserId($userIds);

		return $userDetails;

	}

	private function getVendorMapping($filteredData, $clientInfo) {

		$cityIds = array(); $stateIds = array();
		foreach($filteredData as $filterdata) {

			$vendorName = $clientInfo[$filterdata['listing_type']][$filterdata['listing_id']]['clientData']['vendor'];

			if($filterdata['city_id'] > 0) {
				$vendorData[$vendorName]['cityIds'][$filterdata['city_id']] = $filterdata['city_id'];
			}
			if($filterdata['state_id'] > 0) {
				$vendorData[$vendorName]['stateIds'][$filterdata['state_id']] = $filterdata['state_id'];
			}
		}

		if(!empty($vendorData)) {

			$vendorMapping = array();
			foreach ($vendorData as $key=>$data) {
				
				$vendor = $key;
				$cityIds = $data['cityIds'];
				$stateIds = $data['stateIds'];

		 		if(!empty($cityIds) || !empty($stateIds)) {
					$vendorMappingData = $this->responseModel->getVendorMapping($vendor, $cityIds, $stateIds);
					
					foreach($vendorMappingData as $data) {
						$vendorMapping[$vendor][$data['entity_type']][$data['shiksha_entity']] = $data['vendor_entity'];
					}
				}

			}
		}

		return $vendorMapping;
	}

	private function prepareDataForPorting($data, $clientInfo, $vendorMapping = array()) {

		$listingId = $data['listing_id'];
		$userId = $data['user_id'];

		$clientShikshaFieldsMapping = $clientInfo[$listingId]['clientShikshaFieldsMapping'];
		$vendorName = $clientInfo[$listingId]['clientData']['vendor'];

		$i = 0; $userData = array();
		foreach($clientShikshaFieldsMapping as $key=>$mapping) {

			if($key == 'default') {	

				foreach($mapping as $mapKey=>$mapValue) {

					$userData[$i]['id'] = $mapKey;
					$userData[$i]['type'] = $mapValue['type'];

					if($mapKey == 'CaptchaImage') {
						if($data['isdCode'] != '91') {
							$userData[$i]['value'] = $mapValue['abroadvalue'];
						} else {
							$userData[$i]['value'] = $mapValue['nationalvalue'];
						}
					} else {
						$userData[$i]['value'] = $mapValue['value'];
					}

					$i++;
				}

			} else if($key == 'custom') {

				foreach($mapping as $mapKey=>$mapValue) {

					$fieldValue = $mapValue['value'];

					if(($data['isdCode'] != '91') && ($fieldValue == 'state_name' || $fieldValue == 'city_name')) {
						continue;
					}

					$userData[$i]['id'] = $mapKey;
					$userData[$i]['type'] = $mapValue['type'];

					if($fieldValue == 'firstname+lastname') {

						$userData[$i]['value'] = $data['firstname'].' '.$data['lastname'];

					} else if($fieldValue == 'state_name'){

						if($data['city_name']=='Chandigarh' && $vendorName=='NPF'){
							$userData[$i]['value'] = 'Chandigarh';
						}
						else{
							$state_id = $data['state_id'];
							if($vendorMapping[$vendorName]['state'][$state_id] != '') {
								$userData[$i]['value'] = $vendorMapping[$vendorName]['state'][$state_id];
							} else {
								$userData[$i]['value'] = $data[$fieldValue];	
							}
						}


					} else if($fieldValue == 'city_name') {				
						
						$city_id = $data['city_id'];					
						if($vendorMapping[$vendorName]['city'][$city_id] != '') {
							$userData[$i]['value'] = $vendorMapping[$vendorName]['city'][$city_id];
						} else {
							$userData[$i]['value'] = $data[$fieldValue];	
						}

					} else if($fieldValue == 'isdCode') {				
						
						$userData[$i]['value'] = '+'.$data[$fieldValue];
					
					} else if($mapKey == 'Button') {

						$userData[$i]['value'] = $fieldValue;

					} else {

						$userData[$i]['value'] = $data[$fieldValue];

					} 

					$i++;
				}

			}

		}
	
		return $userData;
	}

	private function portData($userData, $clientData, $lastInsertedId) {

		$dataPortService = $this->CI->config->item('dataPortService','responseConfig');	

		$dataForPorting = array();
		$dataForPorting['userData'] = $userData;
		$dataForPorting['clientData'] = $clientData;
		$dataForPorting['logId'] = $lastInsertedId;

		$dataForPorting = json_encode($dataForPorting);
		
		$status = 'error';

		try {

			$url = RESPONSE_PORT_URL.$dataPortService;
			$response = $this->curlRequest($url, $dataForPorting);
			
			//if(empty($response)) {
			//	$status = 'error';
			//	echo 'Response Empty Received But Request Sent from Curl Request<br/>';
				//mail('teamldb@shiksha.com,mohd.alimkhan@shiksha.com,mohit.k1@shiksha.com','Respone empty but request sent while Curl Request to python Script at '.date('Y-m-d H:i:s'), 'Curl URL: '.$url.'<br/>Data: '.print_r($dataForPorting, true));	
			//} else {
				$status = 'success';
				echo 'Request sent to Python Service from Curl Request<br/>';
			//}

		} catch(Exception $e) {
			$status = 'exception';
			echo 'Exception in Curl Request<br/>';
			mail('teamldb@shiksha.com,mohd.alimkhan@shiksha.com,mohit.k1@shiksha.com','Exception while Curl Request to python Script at '.date('Y-m-d H:i:s'), 'Curl URL: '.$url.'<br/>Data: '.print_r($dataForPorting, true).'<br/>Exception: '.$e->getMessage());
		}	

		return $status;
	}

	private function curlRequest($url, $str) {

        //echo "URL:::::".$url."<br/>";
        //echo "strigndata ::::::::::::".$str;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,            $url );
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT,        10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);

		if($str != '') {
	        curl_setopt ($ch, CURLOPT_POST, 1);
	        curl_setopt ($ch, CURLOPT_POSTFIELDS, $str);
		    curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                     
	                'Content-Type: application/json',                                                                               
                'Content-Length: ' . strlen($str))
                );
		}

        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function saveResponse() {   	
		
		$logId = $this->CI->input->post('logId');
		$reason = $this->CI->input->post('reason');
		$status = $this->CI->input->post('status');
		$responseTime = $this->CI->input->post('responseTime');

		if($logId <= 0 || $responseTime == '' || $status == '') {
			mail('teamldb@shiksha.com,mohd.alimkhan@shiksha.com,mohit.k1@shiksha.com','Required Reponse Data missing from python after posting data at '.date('Y-m-d H:i:s'), 'Required Reponse Data missing from python<br/>'.print_r($_POST, true));
			return;
		}

		$queueStatus = '';
		if($status == 'success' || $status == 'error') {
			
			if(strpos($reason,'Enter exact characters shown in image.')!==false) {
				$queueStatus = 'failed';
			} else {
				$queueStatus = 'processed';
			}
			
			if($status == 'error') {
				mail('teamldb@shiksha.com,mohd.alimkhan@shiksha.com,mohit.k1@shiksha.com','Validation Errors Received from python after posting data at '.date('Y-m-d H:i:s'), 'Validation Errors Received from python after posting data<br/>'.print_r($_POST, true));
			}
		} else if($status == 'exception') {
			$queueStatus = 'failed';
			mail('teamldb@shiksha.com,mohd.alimkhan@shiksha.com,mohit.k1@shiksha.com','Exception Received from python after posting data at '.date('Y-m-d H:i:s'), 'Exception Received from python after posting data<br/>'.print_r($_POST, true));
		} else {
			mail('teamldb@shiksha.com,mohd.alimkhan@shiksha.com,mohit.k1@shiksha.com','Wrong Status Received from python after posting data at '.date('Y-m-d H:i:s'), 'Wrong Status Received from python after posting data<br/>'.print_r($_POST, true));
			return;
		}

		$this->_init();
		$responseQueueData = $this->responseModel->getQueueDataByLogId($logId);

		if($responseQueueData['response_queue_id'] > 0) {
			$this->responseModel->updateLogResponse($status, $responseTime, $logId, $reason);
			$this->responseModel->updateQueueStatus($queueStatus, $responseQueueData['response_queue_id']);
			echo 'Data Saved';
		}

    }

}
