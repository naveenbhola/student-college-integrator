<?php 

class ResponseIndexer extends MX_Controller {
	private $clientConn;
	
	private $indexType = 'response';

	private function _init(){

  	  	$ESConnectionLib = $this->load->library('trackingMIS/elasticSearch/ESConnectionLib');
    	$this->clientConn = $ESConnectionLib->getESServerConnection();
    	$this->clientConn5 = $ESConnectionLib->getESServerConnectionWithCredentials();
	}


	public function fetchUserResponseData($user_id, $listing_type, $listing_type_id){
		$responsemodel = $this->load->model('responsemodel');
		$response_listing_data = array();

		if($user_id<1 || $listing_type == '' || empty($listing_type) || $listing_type_id<1 ){
			return false;
		}

		$user_response_data = $responsemodel->fetchResponsesToIndex($user_id, $listing_type, $listing_type_id);

		if(empty($user_response_data)){
			return;
		}

		if($listing_type == 'course'){
			$this->load->builder("nationalCourse/CourseBuilder");
			$builder = new CourseBuilder();
			$this->courseRepository = $builder->getCourseRepository();

			$courseEntityArray[] = 'basic';

			$courseObj = $this->courseRepository->find($listing_type_id, $courseEntityArray);

			$response_listing_data[0]['client_id'] = $courseObj->getClientId();
			$response_listing_data[0]['institute_id'] = $courseObj->getInstituteId(); 
			
		}

		$user_response_lib = $this->load->library('response/userResponseIndexingLib');
    
    	if($user_response_data[0]['ExtraFlag'] != 'studyabroad'){
			$userExtraflag = 'national';
		}		

		$user_basic_data = modules::run('LDB/LDB_Server/sgetUserDetails', array(),true,$user_id,$userExtraflag);
		$user_basic_data = json_decode(base64_decode($user_basic_data),true);


		$indexingData = $user_response_lib->formatTrackingData($user_response_data, $response_listing_data, $user_basic_data);			
		
		unset($user_basic_data);
		unset($user_response_data);
		unset($response_listing_data);

		$doc_id = $user_id.'_'.$listing_type.'_'.$listing_type_id;
		$indexName = LDB_RESPONSE_INDEX_NAME;

		$indexing_response = $user_response_lib->indexResponseData($indexingData,$indexName,$this->indexType, $doc_id);

		return $indexing_response;
		
	}

	public function partialIndexingOfUserResponse($user_id){
		if($user_id<1){
			return;
		}

		$responsemodel = $this->load->model('responsemodel');

		$extraFlag = $responsemodel->getUserExtraFlag($user_id);

		if($extraFlag != 'studyabroad'){
			$userExtraflag = 'national';
		}

		$user_basic_data = modules::run('LDB/LDB_Server/sgetUserDetails', array(),true,$user_id,$userExtraflag);
		$user_basic_data = json_decode(base64_decode($user_basic_data),true);
		
		$user_response_lib = $this->load->library('response/userResponseIndexingLib');
		$user_response_lib->partialIndexingOfUserResponse($user_id,$user_basic_data[$user_id]);
		
	}

	public function  indexUserResponseFromQueue(){

		$this->validateCron();
		$responsemodel = $this->load->model('responsemodel');

		$user_to_index = $responsemodel->getUsersToIndexFromQueue();
		$last_index_id = 0;
		$all_user_ids = array();
		
		foreach ($user_to_index as $user_data) {
			if($user_data['listing_type_id']>0){		
				$index_response = $this->fetchUserResponseData($user_data['user_id'], $user_data['listing_type'],$user_data['listing_type_id']);
			}else{
				$index_response = $this->partialIndexingOfUserResponse($user_data['user_id']);
			}		

			if($index_response['errors'] != 1){
				$all_user_ids[] = $user_data['user_id'];
			}

			$last_index_id = $user_data['id'];
		}

		if(count($all_user_ids)<1){
			return;
		}
		
		$responsemodel->updateUserStatusInQueue($all_user_ids, $last_index_id);
	}


	/**
	 * Cron to index national and abroad course exam response in elastic
	 * @author Aman Varshney <varshney.aman@gmail.com>
	 * @date   2018-02-26
	 * @return
	 */
	public function indexShikshaResponses(){		
		$this->validateCron();
		$responsemodel     = $this->load->model('responsemodel');
		$userResponseLib   = $this->load->library('response/userResponseIndexingLib');
		$leadTrackingLib   = $this->load->library('leadTracking/LeadIndexingLib');
		$delayConfigArray = array('course_upgrade_response' =>1, 'course_downgrade_response' =>1, 'exam_upgrade_response' =>1, 'exam_downgrade_response' =>1,'sa_update_response'=>1);
		$timeDelay = date('Y-m-d H:i:00',strtotime("-15 min"));

		$unqiueIndexLog  = array();
		$indexLog          = $responsemodel->getDataFromResponseLog();	

		foreach ($indexLog as $key => $value) {

			$extraData = json_decode($value['extra_data'],true);
			if($delayConfigArray[$extraData['action']] >0 && ($value['queued_time'] >$timeDelay) ){
				continue;
			}

			if($value['queue_id'] == 0){
				//user indexing 
				if($unqiueIndexLog[$value['user_id']."_userInfo"]){
					//update primaryId of index log
					array_push($unqiueIndexLog[$value['user_id']."_userInfo"]['primaryIds'],$value['id']);
				}else{					
					$unqiueIndexLog[$value['user_id']."_userInfo"] = array('data' => $value, 'primaryIds' => array($value['id']));	
				}
				
			}else{
				$extraData = json_decode($value['extra_data'],true);
				if($unqiueIndexLog[$value['queue_id']."_".$extraData['action']]){
					array_push($unqiueIndexLog[$value['queue_id']."_".$extraData['action']]['primaryIds'],$value['id']);
				}else{
					$unqiueIndexLog[$value['queue_id']."_".$extraData['action']] = array('data' => $value, 'primaryIds' => array($value['id']));	
				}
			}
		}

		if($unqiueIndexLog){
			$attributesData    = $leadTrackingLib->getAllAttributeIdsWithNames();
			$indexLogPrimaryIds = array();
			foreach ($unqiueIndexLog as $val) {	
				$indexRow = $val['data'];							
				if($indexRow['queue_id'] > 0){

					$extraData = json_decode($indexRow['extra_data'],true);

					//skip two upgrade/downgrade indexing in 1 cron run
					if( $delayConfigArray[$extraData['action']]>0 ){
						if($skip_index_map[$indexRow['user_id']][$extraData['action']] == 1){
							continue;
						}

						$skip_index_map[$indexRow['user_id']][$extraData['action']] = 1;
					}
				
					switch ($extraData['action']) {
						case 'course_new_response':						
							$queueId       = $indexRow['queue_id'];
							$indexResponse = $userResponseLib->indexCourseResponseDoc($queueId,$attributesData,'national');																								
							break;
						case 'course_upgrade_response':						
							$newQueueId        = $indexRow['queue_id'];
							$existingTempLMSId = $extraData['tempLMSId'];		
							
							//update old response document
							$userResponseLib->partialIndexingofOldResponseDoc($existingTempLMSId,$newQueueId,'national');

							//index new response document
							$indexResponse = $userResponseLib->indexCourseResponseDoc($newQueueId,$attributesData,'national');
							break;					
						case 'course_downgrade_response':
							$indexResponse = $userResponseLib->partialIndexingOfResponseTime($extraData['tempLMSId'],$extraData['responseTime'],'national');
							break;
						case 'exam_new_response':						
							$queueId       = $indexRow['queue_id'];
							$indexResponse = $userResponseLib->indexExamResponseDoc($queueId,$attributesData);
							break;
						case 'exam_upgrade_response':
							$newQueueId        = $indexRow['queue_id'];
							$existingTempLMSId = $extraData['tempLMSId'];

							//update old response document
							$userResponseLib->partialIndexingofOldResponseDoc($existingTempLMSId,$newQueueId,'national');

							//index new response document
							$indexResponse = $userResponseLib->indexExamResponseDoc($newQueueId,$attributesData);
							break;
						case 'exam_downgrade_response':
							$indexResponse = $userResponseLib->partialIndexingOfResponseTime($extraData['tempLMSId'],$extraData['responseTime'],'national');
							break;
						case 'sa_new_response':
							$queueId         = $indexRow['queue_id'];
							$indexResponse   = $userResponseLib->indexCourseResponseDoc($queueId,$attributesData,'abroad');																								
							break;
						case 'sa_update_response':							
							$newQueueId        = $indexRow['queue_id'];
							$existingTempLMSId = $extraData['tempLMSId'];		

							//update old response document
							$userResponseLib->partialIndexingofOldResponseDoc($existingTempLMSId,$newQueueId,'abroad');
							//index new response document
							$indexResponse = $userResponseLib->indexCourseResponseDoc($newQueueId,$attributesData,'abroad');
							break;
						default:
							break;						
					}					
				}else{
					$indexResponse = $userResponseLib->partialIndexingOfUserData($indexRow['user_id'],$attributesData);
				}


				if($indexResponse['errors'] != 1){
					foreach ($val['primaryIds'] as $key => $indexlogId) {
						$indexLogPrimaryIds[] = $indexlogId;						
					}
				}

				
			}			


			if(count($indexLogPrimaryIds)<1){
				return;
			}
			$responsemodel->updateResponseLogQueue($indexLogPrimaryIds);
			die('completed');
			
		}

	}

	/**
	 * One Time cron to index course response in elastic search
	 * @author Aman Varshney <varshney.aman@gmail.com>
	 * @date   2018-02-26
	 * @return [type]     [description]
	 */
	public function indexCourseResponsesInElastic($counterStart = 0){
		$this->validateCron();
		ini_set("memory_limit",'4048M');
		ini_set("max_execution_time",-1);
		
		$tempLMSId = 25275237;
		
		if(ENVIRONMENT == "production"){
			$logFile          =  '/data/app_logs/indexAllCourseElastic'.date("Ymd").'.log';			
			$benchMarkLogFile =  '/data/app_logs/benchmarkIndexAllCourseElastic'.date("Ymd").'.log';			
			$logFileError     =  '/data/app_logs/errorIndexAllCourseElastic'.date("Ymd").'.log';			
		}else{
			$logFile          =  '/tmp/indexAllCourseElastic'.date("Ymd").'.log';
			$benchMarkLogFile =  '/tmp/benchmarkIndexAllCourseElastic'.date("Ymd").'.log';			
			$logFileError     =  '/tmp/errorIndexAllCourseElastic'.date("Ymd").'.log';			
		}
		
		$leadTrackingLib   = $this->load->library('leadTracking/LeadIndexingLib');
		$attributesData    = $leadTrackingLib->getAllAttributeIdsWithNames();

		$userResponseLib   = $this->load->library('response/userResponseIndexingLib');
		$responsemodel     = $this->load->model('responsemodel');

		$this->load->library('cacheLib');
		$cacheLib = new CacheLib();

		$tracking_data_map = $this->getTrackingKeyData();
		
		$abroad_cache_data 			= $cacheLib->get("abroad_course_loc_data");
		$abroad_cache_data			= unserialize($abroad_cache_data);
		
		$nat_cache_data 			= $cacheLib->get("nat_course_loc");
		$nat_cache_data				= unserialize($nat_cache_data);


		$i = 0;		
		$chunkSize = 50000;

		while ($i < 10) {
			$start = ($i * $chunkSize) +  $counterStart;
			
			$time_start = microtime_float(); $start_memory = memory_get_usage();
			$userSet =$responsemodel->distintResponseUsers($start);		
			error_log("Total User Picked(chunk : ".$start.") : ".count($userSet)." | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, $benchMarkLogFile);

			$time_start = microtime_float(); $start_memory = memory_get_usage();
			$itr=0;

			$documents = array();
			$first_loop = false;
			foreach ($userSet as $key => $value) {		
				$itr++ ;
				// index abroad and national courses responses
				$indexdocs = $userResponseLib->indexAllCourseResponses ($attributesData,$value['userId'],$tempLMSId, $tracking_data_map,$abroad_cache_data, $nat_cache_data);
				
				//$indexResponse = $userResponseLib->indexAllCourseResponses($attributesData,$value['userId'],$tempLMSId);
				//$allIndexDocs = array_merge();

				if(!is_array($indexdocs)){
					$indexdocs = array();
				}

				$documents =   $indexdocs + $documents ;	

				if(count($documents)> 1000 ){
					$indexName     = SHIKSHA_RESPONSE_INDEX_NAME;
					$indexResponse = $userResponseLib->insertResponseDoc($documents,$indexName,'response');
					$documents = array();
					$first_loop = false;
				}


				if($indexResponse['errors'] == 1){					
					error_log(print_r($indexResponse,true)."\n\n", 3, $logFileError);								
				}				
				error_log("start : ".$start." --- userId : ".$value['userId']."\n",3, $logFile);				
			}
			error_log("Preparing doc and indexing (chunk : ".$start."): ".count($userSet)." | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, $benchMarkLogFile);
			$i++;

			if(count($documents)> 0 ){
				$indexName     = SHIKSHA_RESPONSE_INDEX_NAME;
				$indexResponse = $userResponseLib->insertResponseDoc($documents,$indexName,'response');
				$documents = array();
			}

		}

		return;
	}

	public function indexCourseResponsesInElasticIncrement($start_counter = 0){
		$this->validateCron();
		ini_set("memory_limit",'4048M');
		ini_set("max_execution_time",-1);
		
		$tempLMSId = 25275237;
		
		if(ENVIRONMENT == "production"){
			$logFile          =  '/data/app_logs/indexAllCourseElastic'.$start_counter.date("Ymd").'.log';			
			$benchMarkLogFile =  '/data/app_logs/benchmarkIndexAllCourseElastic'.$start_counter.date("Ymd").'.log';			
			$logFileError     =  '/data/app_logs/errorIndexAllCourseElastic'.$start_counter.date("Ymd").'.log';			
		}
		
		$leadTrackingLib   = $this->load->library('leadTracking/LeadIndexingLib');
		$attributesData    = $leadTrackingLib->getAllAttributeIdsWithNames();

		$userResponseLib   = $this->load->library('response/userResponseIndexingLib');
		$responsemodel     = $this->load->model('responsemodel');

		$this->load->library('cacheLib');
		$cacheLib = new CacheLib();

		$tracking_data_map = $this->getTrackingKeyData();
		
		$abroad_cache_data 			= $cacheLib->get("abroad_course_loc_data");
		$abroad_cache_data			= unserialize($abroad_cache_data);
		
		$nat_cache_data 			= $cacheLib->get("nat_course_loc");
		$nat_cache_data				= unserialize($nat_cache_data);


		$i = 0;		
		$chunkSize = 500000;
		$end_counter = $start_counter + $chunkSize;

		//error_log("Total User Picked(chunk : ".$start.") : ".count($userSet)." | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, $benchMarkLogFile);

		$time_start = microtime_float(); $start_memory = memory_get_usage();
		$itr=0;

		$documents = array();
		
		while ($start_counter <= $end_counter) {
		
			$indexdocs = $userResponseLib->indexAllCourseResponses ($attributesData,$start_counter,$tempLMSId, $tracking_data_map,$abroad_cache_data, $nat_cache_data);

			
			if(!is_array($indexdocs)){
				$indexdocs = array();
			}

			$documents =   $indexdocs + $documents ;

			if(count($documents)> 1000 ){
				error_log('=== counter  '.$start_counter);
				$indexName     = SHIKSHA_RESPONSE_INDEX_NAME;
				$indexResponse = $userResponseLib->insertResponseDoc($documents,$indexName,'response');
				$documents = array();
			}

			if($indexResponse['errors'] == 1){					
				error_log(print_r($indexResponse,true)."\n\n", 3, $logFileError);								
			}				
			error_log("start : ".$start." --- userId : ".$start_counter."\n",3, $logFile);				

			$start_counter++;
		}

		/*error_log("Preparing doc and indexing (chunk : ".$start."): ".count($userSet)." | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, $benchMarkLogFile);
		$i++;*/

		if(count($documents)> 0 ){
			$indexName     = SHIKSHA_RESPONSE_INDEX_NAME;
			$indexResponse = $userResponseLib->insertResponseDoc($documents,$indexName,'response');
			$documents = array();
		}

		

		return;
	}


	public function indexExamResponsesInElastic($processed = 0){
		$this->validateCron();
		
		if(ENVIRONMENT == "production"){
			$logFile =  '/data/app_logs/log_exam_responses_'.date("Ymd").'.log';
			$processedLog = '/data/app_logs/examResponseIndexing_'.date("Ymd").'.log';
			$indexErrorLog = '/data/app_logs/examResponseError_'.date("Ymd").'.log';
		}else{
			$logFile =  '/tmp/log_exam_responses_'.date("Ymd").'.log';
			$processedLog = '/tmp/examResponseIndexing_'.date("Ymd").'.log';
			$indexErrorLog = '/tmp/examResponseError_'.date("Ymd").'.log';
		}
		ini_set("memory_limit",'2048M');
		ini_set("max_execution_time",-1);

		$userResponseLib   = $this->load->library('response/userResponseIndexingLib');
		$responsemodel     = $this->load->model('responsemodel');

		$leadTrackingLib   = $this->load->library('leadTracking/LeadIndexingLib');
		$attributesData    = $leadTrackingLib->getAllAttributeIdsWithNames();

		// $processed = 0;
		$batchSize = 5000;
		$tempLMSId = 38585903;

		$time_start = microtime_float(); $start_memory = memory_get_usage();
		// fetch total exam response count
		$totalCount = $responsemodel->totalExamResponses($tempLMSId);
		error_log("Total Exam Responses  : ".$totalCount." | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, $logFile);

		if(($totalCount-$processed) <= 0){			
			return;
		}

		$userBasicData = array();
		while($totalCount > $processed){
			//fetch exam responses data
			$time_start = microtime_float(); $start_memory = memory_get_usage();
			$examResponses = $responsemodel->getExamResponses($processed,$batchSize,$tempLMSId);
			error_log("\n\n\n\n", 3, $logFile);
			error_log("Responses fetched (".$processed.")  : ".count($examResponses)." | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, $logFile);
			
			$documents = $docResponse =  array();
			
			$time_start = microtime_float(); $start_memory = memory_get_usage();
			$aman = array();
			foreach($examResponses as $key => $value){	
				// not fetching the user data again					
				if(!$userBasicData[$value['userId']]){
					$userRawData                     = modules::run('LDB/LDB_Server/sgetUserDetails', array(),true,$value['userId'],'national');
					$userFormattedData               = json_decode(base64_decode($userRawData),true);
					$userBasicData[$value['userId']] = $userFormattedData[$value['userId']];
				}

				$dataToPass                                          = array();
				$dataToPass['listing_type_id']                       = $value['listing_type_id'];
				$dataToPass['user_id']                               = $value['userId'];
				$dataToPass['listing_type']                          = $value['listing_type'];
				$dataToPass['submit_date']                           = $value['submit_date'];
				$responseProfileResult                               = $responsemodel->getResponseUserProfile($dataToPass);				
				$listingData[$value['listing_type_id']]['hierarchy'] = $userResponseLib->_formatResponseUserProfile($responseProfileResult);
				
				if($aman[$value['temp_lms_id']]){
					$aman[$value['temp_lms_id']] = $aman[$value['temp_lms_id']] + 1;
					$varshney[$value['temp_lms_id']] = 1;
				}
				else{
					$aman[$value['temp_lms_id']] = 1;
				}


				$docResponse = $userResponseLib->prepareIndexingDocument($value,$listingData[$value['listing_type_id']],$userBasicData[$value['userId']],$attributesData);															
				if($docResponse != false){				
					if(!$documents["temp_".$value['temp_lms_id']]){
						$documents["temp_".$value['temp_lms_id']] = $docResponse;
						$processed++;
					}
				}else{
					error_log("Missing responses  : ".$value['temp_lms_id']."\n",3,$indexErrorLog);
				}
			}

			error_log(print_r($varshney,true), 3, '/tmp/aman.log');			

			error_log("No of document prepared(".$processed.")  : ".count($documents)." | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, $logFile);

			if(count($userBasicData) > 10000){
				unset($userBasicData);
			}

			$time_start = microtime_float(); $start_memory = memory_get_usage();
			$indexName     = SHIKSHA_RESPONSE_INDEX_NAME;
			$indexResponse = $userResponseLib->insertResponseDoc($documents,$indexName,'response');		
			error_log("Time Taken in indexing(".$processed.")   | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, $logFile);			
			if($indexResponse['errors'] != 1){
				error_log("Processed Count : ".$processed."\n", 3, $processedLog);				
			}else{				
				error_log(print_r($indexResponse,true)."\n\n", 3, $indexErrorLog);								
			}
		}
	}


	function indexAllScholarshipResponses($processed = 0){
		$this->validateCron();
		ini_set("memory_limit",'2048M');
		ini_set("max_execution_time",-1);

		if(ENVIRONMENT == "production"){
			$logFile =  '/data/app_logs/log_scholarship_responses_'.date("Ymd").'.log';
			$processedLog = '/data/app_logs/scholarshipResponseIndexing_'.date("Ymd").'.log';
			$indexErrorLog = '/data/app_logs/scholarshipResponseError_'.date("Ymd").'.log';
		}else{
			$logFile =  '/tmp/log_scholarship_responses_'.date("Ymd").'.log';
			$processedLog = '/tmp/scholarshipResponseIndexing_'.date("Ymd").'.log';
			$indexErrorLog = '/tmp/scholarshipResponseError_'.date("Ymd").'.log';
		}

		$userResponseLib   = $this->load->library('response/userResponseIndexingLib');
		$responsemodel     = $this->load->model('responsemodel');

		$leadTrackingLib   = $this->load->library('leadTracking/LeadIndexingLib');
		$attributesData    = $leadTrackingLib->getAllAttributeIdsWithNames();

		// $processed = 0;
		$batchSize = 5000;
		$tempLMSId = 38585903;

		$time_start = microtime_float(); $start_memory = memory_get_usage();
		// fetch total exam response count
		$totalCount = $responsemodel->totalScholarshipResponses($tempLMSId);
		error_log("Total Scholarship Responses  : ".$totalCount." | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, $logFile);

		if(($totalCount-$processed) <= 0){			
			return;
		}

		$userBasicData = array();
		while($totalCount > $processed){
			$time_start = microtime_float(); $start_memory = memory_get_usage();
			$scholarshipResponses = $responsemodel->getScholarshipResponses($processed,$batchSize,$tempLMSId);
			error_log("\n\n\n\n", 3, $logFile);
			error_log("Responses fetched (".$processed.")  : ".count($scholarshipResponses)." | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, $logFile);

			$documents = $docResponse =  array();
			
			$time_start = microtime_float(); $start_memory = memory_get_usage();
			$aman = array();
			foreach($scholarshipResponses as $key => $value){	
				// not fetching the user data again					
				if(!$userBasicData[$value['userId']]){
					$userRawData                     = modules::run('LDB/LDB_Server/sgetUserDetails', array(),true,$value['userId'],'national');
					$userFormattedData               = json_decode(base64_decode($userRawData),true);
					$userBasicData[$value['userId']] = $userFormattedData[$value['userId']];
				}

				$docResponse = $userResponseLib->prepareIndexingDocument($value,array(),$userBasicData[$value['userId']],$attributesData);															
				if($docResponse != false){				
					if(!$documents["temp_".$value['temp_lms_id']]){
						$documents["temp_".$value['temp_lms_id']] = $docResponse;
						$processed++;
					}
				}else{
					error_log("Missing responses  : ".$value['temp_lms_id']."\n",3,$indexErrorLog);
				}
			}

			error_log("No of document prepared(".$processed.")  : ".count($documents)." | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, $logFile);

			$time_start = microtime_float(); $start_memory = memory_get_usage();
			$indexName     = SHIKSHA_RESPONSE_INDEX_NAME;
			$indexResponse = $userResponseLib->insertResponseDoc($documents,$indexName,'response');		
			error_log("Time Taken in indexing(".$processed.")   | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, $logFile);			
			if($indexResponse['errors'] != 1){
				error_log("Processed Count : ".$processed."\n", 3, $processedLog);				
			}else{				
				error_log(print_r($indexResponse,true)."\n\n", 3, $indexErrorLog);								
			}
		}				
	}
  	
  	function getTrackingKeyData() {
  		$responsemodel     = $this->load->model('responsemodel');
  				
  		$tracking_id_data = $responsemodel->getAllTrackingIdData();
		foreach ($tracking_id_data as $data) {
			$tracking_data_map[$data['id']]['id'] 			= $data['id'];
			$tracking_data_map[$data['id']]['keyName'] 		= $data['keyName'];
			$tracking_data_map[$data['id']]['page'] 		= $data['page'];
			$tracking_data_map[$data['id']]['widget'] 		= $data['widget'];
			$tracking_data_map[$data['id']]['site'] 		= $data['site'];
			$tracking_data_map[$data['id']]['siteSource'] 	= $data['siteSource'];
		}
		
		return $tracking_data_map;
  	}

  	function cacheListingLocationData(){
  		$this->validateCron();
  		ini_set('memory_limit', '1024M');

  		$responsemodel     = $this->load->model('responsemodel');
  		$national_course_data = $responsemodel->getNationalInstiLocation();

  		$cache_data = array();

  		//getting only live or deleted row and not both

  		foreach ($national_course_data as $data) {
			$cache_data[$data['listing_location_id']]['listing_location_id']  	= $data['listing_location_id'];
			$cache_data[$data['listing_location_id']]['response_city']  		= $data['response_city'];
			$cache_data[$data['listing_location_id']]['response_locality']  	= $data['response_locality'];
			$cache_data[$data['listing_location_id']]['institute_id']  			= $data['institute_id'];
		
  		}

  		$cache_data = serialize($cache_data);

  		$this->load->library('cacheLib');
		$this->cacheLib = new CacheLib();

 		$response = $this->cacheLib->store("nat_course_loc" , $cache_data,84800); 		
  	}

  	function cacheAbroadListingLocationData(){
  		ini_set('memory_limit', '1024M');
  		$this->validateCron();

  		$responsemodel     = $this->load->model('responsemodel');
  		$abroad_course_data = $responsemodel->getAbroadInstiLocation();

  		$cache_data = array();

  		//getting only live or deleted row and not both
  		foreach ($abroad_course_data as $data) {
			$cache_data[$data['abroadListingLocationId']]['abroadListingLocationId']  	= $data['abroadListingLocationId'];
			$cache_data[$data['abroadListingLocationId']]['abroad_response_city']  		= $data['abroad_response_city'];
			//$cache_data[$data['abroadListingLocationId']]['abroad_response_locality']  	= $data['abroad_response_locality'];
			$cache_data[$data['abroadListingLocationId']]['abroad_institute_id']  		= $data['abroad_institute_id'];
		
  		}

  		$cache_data = serialize($cache_data);


  		$this->load->library('cacheLib');
		$this->cacheLib = new CacheLib();

  		$response = $this->cacheLib->store("abroad_course_loc_data" , $cache_data, 84800);
  	}


  	public function updatedClientIdCache($clientId,$courseId)
    {
    	$key = 'updateClientId';
        $redis_client = PredisLibrary::getInstance();
        $value = json_encode(array('courseId'=>$courseId,'clientId'=>$clientId));
		$redis_client->addMembersToSet($key,array($value));
        }

  	public function responseMigration()
    {
        $this->validateCron();
        $redis_client = PredisLibrary::getInstance();
        $key = 'updateClientId';
        $redisData = $redis_client->getMembersOfSet($key);
        foreach ($redisData as $index => $value) {
        	$clientData = json_decode($value,true);
        	$data[intval($clientData['courseId'])] = intval($clientData['clientId']);
        }
        
        if (empty($data)){
            error_log("No data to update");
            return;
        }
        else{
           $courseIds =  $this->getDocumentId($data);   
        }
        $redis_client->removeMembersOfSet($key,$courseIds);
    }


    private function getDocumentId($data){
        if(empty($data)){
            error_log("No data to Process");
        }
        $this->_init();
        $queryGenerator = $this->load->library('responseProcessorElasticQueryGenerator');
        foreach ($data as $listingId => $clientId) {
            # code...
            error_log("update for listing Id = ".print_r($listingId));
            $params = $queryGenerator->generateCountQueryForListing($listingId,$clientId);
            $count = $this->clientConn5->count($params);
            $totalResponsesCount = $count['count'];
            error_log("total response Count to update = ".print_r($totalResponsesCount));
            $params = $queryGenerator->addSourceAndSize("",10000,$params);
            while ($totalResponsesCount > 0){
                $search = $this->clientConn5->search($params);
                foreach($search['hits']['hits'] as $result) {
                		$updateParams = $queryGenerator->updateClientFromDocument($result['_id'],$clientId);       
                        $this->clientConn5->update($updateParams);
                }
                $totalResponsesCount = $totalResponsesCount - 10000;
                error_log("remaining update left ".print_r($totalResponsesCount));
                // Sleep for 2 sec is done due to lag in elastic search update so that last 5-6 approx documents wont be picked next time in the for loop
                sleep(2);
            }
            $redisClearKey[] = json_encode(array('courseId'=>intval($listingId),'clientId'=>intval($clientId)));
        }
       error_log("Done for keys on redis ".print_r($redisClearKey,true));
       return $redisClearKey;
    }

    // cron to add isource(internal source field in shiksha response index. eg isource : sa(shiksha assistant))
  	function addInternalSourceToResponseES(){
  		$this->validateCron();
  		ini_set('memory_limit','4906M');
  		//response/ResponseIndexer/addInternalSourceToResponseES
  		$lib = $this->load->library('trackingMIS/elasticSearch/ESConnectionLib');
        $ESConnection5 = $lib->getESServerConnectionWithCredentials();

        $date['startDate'] = date('Y-m-d\TH:i:s', strtotime('-2 hour'));
        $date['endDate'] = date('Y-m-d\TH:i:s', strtotime('-5 minutes'));

        $response = $this->_getDataFromShikshaAssistantIndex($ESConnection5, $date, 0);
        $totalRecords = 0;
        $totalRecords  = $response['hits']['total'];
        unset($response);
        if($totalRecords >0){
        	$response = $this->_getDataFromShikshaAssistantIndex($ESConnection5, $date, $totalRecords);
	        $sessionIds = array();
	        foreach ($response['hits']['hits'] as $key => $details) {
	        	$sessionIds[$details['_source']['sessionId']] = $details['_source']['sessionId'];
	        }
	        unset($response);
	        $chunk = 100;
	        $sessionIdsChunk = array();
	        $sessionIdsChunk = array_chunk($sessionIds, $chunk);
	        unset($sessionIds);
	        $params = array();
			$params['index'] = SHIKSHA_RESPONSE_INDEX_NAME;
			$params['type'] = 'response';

	        foreach ($sessionIdsChunk as $key => $sessionChunkData) {
	        	// fetch response from shisha_response index
	        	$response = $this->_getDataFromShikshaResponseIndex($ESConnection5, $sessionChunkData);
	        	if($response['hits']['total'] >0){
	        		$indexCount = 0;
	        		$remainingCount = 0;
	        		foreach ($response['hits']['hits'] as $index => $responseDetails) {
	        			$indexCount ++;
	        			$remainingCount = 1;
	        			$params['body'][] = array('update' => array('_id' => $responseDetails['_id']));
		                $updateData = array('isource' => 'sa');
		                $params['body'][] = array(
			                'doc_as_upsert' => false,
			                'doc' => $updateData
						);
	        			if($indexCount %100 == 0){
	        				$indexResponse = $ESConnection5->bulk($params);
		                    $params['body'] = array();
		                    $remainingCount = 0;
	        			}
	        		}
	        	}
	        	if($remainingCount == 1){
	        		$indexResponse = $ESConnection5->bulk($params);
	        		$params['body'] = array();
					$remainingCount = 0;
	        	}
	        }
        }
  	}

	private function _getDataFromShikshaResponseIndex($esConn, $sessionIds){
  		// get sessions from shiksha assistant index
        $params = array();
        $params['index'] = SHIKSHA_RESPONSE_INDEX_NAME;
        $params['type'] = 'response';
        $params['size'] = 500000;
        $params['_source'] = array('sessionId');
        $mustFilters = array();
        $mustFilters[] = array('terms' => array('visitor_session_id' => $sessionIds));
        $mustNotFilters[] = array('exists' => array('field' => 'isource'));
        $params['body']['query']['bool']['filter']['bool']['must'] = $mustFilters;
        $params['body']['query']['bool']['filter']['bool']['must_not'] = $mustNotFilters;
        //_p(($params));die;
        $result = $esConn->search($params);
        //_p($result);die;
        return $result;
  	}  	

  	private function _getDataFromShikshaAssistantIndex($esConn, $dateRange, $size =0){
  		// get sessions from shiksha assistant index
        $params = array();
        $params['index'] = SHIKSHA_ASSISTANT_INDEX_NAME_REALTIME_SEARCH;
        $params['type'] = 'chat';
        $params['size'] = $size;
        $params['_source'] = array('sessionId');
        $mustFilters = array();
        $mustFilters[] = array('range' => array('queryTime' => array('gte' => $dateRange['startDate'], 'lte' => $dateRange['endDate'])));
        //$mustFilters[] = array('range' => array('userId' => array('gt' => 0)));
        $params['body']['query']['bool']['filter']['bool']['must'] = $mustFilters;
        //_p(($params));die;
        $result = $esConn->search($params);
        return $result;
  	}
  	
}

?>
