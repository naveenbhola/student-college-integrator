<?php

/** 
 * Library for response Creation/ Response Mail Sending.
*/

class userResponseIndexingLib {

	function __construct() {
		$this->CI = & get_instance();	
	}

	/**
	* This function load all necessary config, model related to response which will be used in various flows 
	*/
	function _init(){
		$ESConnectionLib = $this->CI->load->library('trackingMIS/elasticSearch/ESConnectionLib');
    	$this->clientConn    = $ESConnectionLib->getESServerConnection();
    	$this->clientConn6    = $ESConnectionLib->getShikshaESServerConnection();
    	$this->clientConn5   = $ESConnectionLib->getESServerConnectionWithCredentials();		
	}

	public function formatTrackingData($user_response_data, $response_listing_data, $user_basic_data){
		$final_indexing_data = array();

		$final_indexing_data['is_response_paid'] = false;
		$totalResponse = 0; 

		if($user_response_data['ExtraFlag'] != 'studyabroad'){
			$userExtraflag = 'national';
		}

		foreach ($user_response_data as $value) {
			$totalResponse ++;

			$final_indexing_data['temp_LMS_id'][]				= $value['id'];
			$final_indexing_data['user_id']						= $value['userId'];
			$final_indexing_data['response_listing_type']		= $value['listing_type'];
			$final_indexing_data['listing_type_id']				= $value['listing_type_id'];

			//$final_indexing_data['str_time'][]				= strtotime($value['submit_date']);
			$final_indexing_data['response_time'][]				= str_replace(' ', 'T', $value['submit_date']);

			$final_indexing_data['latest_response_time']		= str_replace(' ', 'T', $value['submit_date']);
			$final_indexing_data['latest_response_action_type']				= $value['action'];
			
			if($value['user_city']>0){
				$final_indexing_data['user_location']				= $value['user_city'];
			}
			
			if($value['user_locality'] >0 ){
				$final_indexing_data['user_locality']				= $value['user_locality'];
			}

			if($value['tracking_keyid']>0){
				$final_indexing_data['tracking_id'][]				= $value['tracking_keyid'];
			}

			if($value['action'] != ''){
				$final_indexing_data['response_action_type'][]		= $value['action'];
			}

			if($value['keyName'] != ''){
				$final_indexing_data['response_source'][]			= $value['keyName'];
			}

			if($value['site'] != ''){
				$final_indexing_data['site_source'][]				= $value['site'];
			}

			if($value['siteSource'] != ''){
				$final_indexing_data['device'][]					= $value['siteSource'];
			}

			if($value['response_city'] >0){
				$final_indexing_data['response_location']			= $value['response_city'];
				
			}

			if($value['response_locality']>0){
				$final_indexing_data['response_locality']			= $value['response_locality'];
			}


			if($value['examId']>0){
				$final_indexing_data['exam_id']						= $value['examId'];
			}

			if($value['listing_subscription_type'] == 'paid'){
				$final_indexing_data['is_response_paid']		= true;
			}
			
			if($extraflag != ''){
				$final_indexing_data['extra_flag']				= $extraflag;
			}

			$final_indexing_data['first_name']				= $user_basic_data[$value['userId']]['firstname'];
			$final_indexing_data['last_name']				= $user_basic_data[$value['userId']]['lastname'];
			$final_indexing_data['user_email']				= $user_basic_data[$value['userId']]['email'];
			$final_indexing_data['user_mobile']				= $user_basic_data[$value['userId']]['mobile'];
			$final_indexing_data['user_creation_date']		= str_replace(' ', 'T', $user_basic_data[$value['userId']]['usercreationDate']);
			
			$final_indexing_data['user_group']				= $user_basic_data[$value['userId']]['usergroup'];

			if($user_basic_data[$value['userId']]['PrefData'][0]['prefYear']){
				$final_indexing_data['pref_year']				= $user_basic_data[$value['userId']]['PrefData'][0]['prefYear'];

			}

			$final_indexing_data['unsubscribe'] = true;
			if($user_basic_data[$value['userId']]['unsubscribe'] != 1){
				$final_indexing_data['unsubscribe'] = false;
			}

			$final_indexing_data['abused'] = true;
			if($user_basic_data[$value['userId']]['abused'] != 1){
				$final_indexing_data['abused'] = false;
			}

			$final_indexing_data['ownershipchallenged'] = true;
			if($user_basic_data[$value['userId']]['ownershipchallenged'] != 1){
				$final_indexing_data['ownershipchallenged'] = false;
			}

			$final_indexing_data['softbounce'] = true;
			if($user_basic_data[$value['userId']]['softbounce'] != 1){
				$final_indexing_data['softbounce'] = false;
			}

			$final_indexing_data['hardbounce'] = true;
			if($user_basic_data[$value['userId']]['hardbounce'] != 1){
				$final_indexing_data['hardbounce'] = false;
			}


			/*$final_indexing_data['mobile_verified'] = true;
			if($user_basic_data[$value['userId']]['mobileverified'] != 1){
				$final_indexing_data['mobile_verified'] = false;
			}*/
			$final_indexing_data['mobile_verified'] = $user_basic_data[$value['userId']]['mobileverified'];

			$final_indexing_data['is_ndnc']	= true;
			if($user_basic_data[$value['userId']]['isNDNC'] == 'NO'){
				$final_indexing_data['is_ndnc']	= false;
			}
			
			$final_indexing_data['is_test_user'] = true;
			if($user_basic_data[$value['userId']]['isTestUser'] == 'NO'){
				$final_indexing_data['is_test_user'] = false;
			}
			
			$final_indexing_data['is_ldb_user'] = true;
			if($user_basic_data[$value['userId']]['isLDBUser'] == 'NO'){
				$final_indexing_data['is_ldb_user'] = false;
			}
			
		}
		
		unset($user_basic_data);

		if($response_listing_data[0]['client_id']>0){
			$final_indexing_data['client_id'] = $response_listing_data[0]['client_id'];
		}

		if($response_listing_data[0]['institute_id']>0){
			$final_indexing_data['institute_id'] = $response_listing_data[0]['institute_id'];
		}


		$final_indexing_data['response_count'] = $totalResponse;

		$return_indexing_data[] = $final_indexing_data;

		return $return_indexing_data;

	}

	public function indexResponseData($indexData, $indexName, $indexType, $doc_id){
		$this->_init();
		$chunkCounter = 0;
		$params = array();

		foreach ($indexData as $data) {
			$params['body'][] = array('index' => array(
                                            '_index' => $indexName,
                                            '_type' => $indexType,
                                            '_id' => $doc_id,
                                        )
                                    );

        	$params['body'][] = $data;
			
            $chunkCounter++;
			if($chunkCounter % 500 == 0){
	            //$response = $this->clientConn->bulk($params);
	            $response6 = $this->clientConn6->bulk($params);
	            $params = array();
	            $params['body'] = array();
	            unset($response);
                
			}
            

		}

		if (!empty($params['body'])) {
            //$response = $this->clientConn->bulk($params);
            $response6 = $this->clientConn6->bulk($params);
        }

        return $response;      

    }


	public function insertResponseDoc($indexData, $indexName, $indexType,$excludeDocumentId = false){
		$this->_init();
		$chunkCounter = 0;
		$params       = array();

		foreach ($indexData as $doc_id => $data) {
			if($excludeDocumentId == true){
				$params['body'][] = array(
									'index' => array(
                                        '_index' => $indexName,
                                        '_type' => $indexType,
                                      )
                                    );
	
			}else{
				$params['body'][] = array(
										'index' => array(
	                                        '_index' => $indexName,
	                                        '_type' => $indexType,
	                                        '_id' => $doc_id,
	                                      )
	                                    );				
			}

        	$params['body'][] = $data;
			
            $chunkCounter++;
			if($chunkCounter % 500 == 0){
	            $response = $this->clientConn5->bulk($params);
	            $params = array();
	            $params['body'] = array();
	            unset($response);                
			}       
		}

		if (!empty($params['body'])) {
            $response = $this->clientConn5->bulk($params);
        }

        return $response;      

    }

    public function insertInIndexingQueue($user_id, $extraData, $listing_type, $listing_type_id){
		$responsemodel = $this->CI->load->model('response/responsemodel');

		$responsemodel->insertInIndexingQueue($user_id, $listing_type, $listing_type_id, $extraData);
		if($extraData){
			$this->insertInResponseIndexLog($user_id,$extraData);
		}
    }

    public function insertInResponseIndexLog($user_id, $extraData, $queue_id){
		$responsemodel = $this->CI->load->model('response/responsemodel');
		$responsemodel->insertInResponseIndexLog($user_id, $queue_id, $extraData);
    }

    public function partialIndexingOfUserData($userId,$attributesData){
    	if($userId<1){
			return;
		}

		$responsemodel = $this->CI->load->model('responsemodel');
		$extraFlag = $responsemodel->getUserExtraFlag($userId);

		if($extraFlag != 'studyabroad'){
			$userExtraflag = 'national';
		}

		$user_basic_data = modules::run('LDB/LDB_Server/sgetUserDetails', array(),true,$userId,$userExtraflag);
		$user_basic_data = json_decode(base64_decode($user_basic_data),true);
		
		$doc_ids = $this->getUserReponseDocIds($userId);		
		
		if (count($doc_ids)<1) {
			return;
		}		
		
		$user_basic_index_data = $this->prepareUserIndexDoc($user_basic_data[$userId],$attributesData);		

		$this->CI->load->config('response/responseConfig');
		$unsubscribe_category = $this->CI->config->item('unsubscribe_category');

		$unsubscribed_category_data = $responsemodel->getUnsubscribedCategoryFiveOfUser($userId,$unsubscribe_category);

		foreach ($unsubscribe_category as $categoryId){
			$flagForCategory = 0;
			foreach ($unsubscribed_category_data as $category_data){
				if($category_data['unsubscribe_category']==$categoryId){
					$user_basic_index_data['is_unsubscribed_category_'.$categoryId] = true;
					$flagForCategory = 1;
					break;
				}
			}

			if($flagForCategory==0){
				$user_basic_index_data['is_unsubscribed_category_'.$categoryId] = false;
			}
		}

		$indexName = SHIKSHA_RESPONSE_INDEX_NAME;
		$elasticQuery = array();
        $elasticQuery['index'] = $indexName;
        $elasticQuery['type'] = 'response';
        foreach ($doc_ids as $doc_id) {
            $elasticQuery['body'][] = array(
                                            'update'    => array(
                                                    '_id' => $doc_id
                                                )
                                            );
            $elasticQuery['body'][] = array(
                                        'doc_as_upsert' => true,
                                        'doc'           => $user_basic_index_data
                                        );
            
        }
    
       	$indexResponse = $this->clientConn5->bulk($elasticQuery);
    }

    public function getUserReponseDocIds($userId){
    	$this->_init();
		
		$indexName = SHIKSHA_RESPONSE_INDEX_NAME;
		
		$elasticQuery = array();
        $elasticQuery['index'] = $indexName;
        $elasticQuery['type'] = 'response';
        $elasticQuery['body']['size'] = 100;
        $elasticQuery['body']['query']['bool']['must'][]['term']['user_id'] = $userId;        
        $result = $this->clientConn5->search($elasticQuery);
        $userIds = array();
        if(!empty($result['hits']['hits'])){
            foreach ($result['hits']['hits'] as $key => $ElasticsearchDoc) {
                $doc_ids[] = $ElasticsearchDoc['_id'];
                unset($result['hits']['hits'][$key]);
            }
        }

        return $doc_ids;		

    }

    



    public function partialIndexingOfUserResponse($user_id, $user_basic_data){
    	$this->_init();

		$doc_ids = $this->getReponseDocIds($user_id);
		if (count($doc_ids)<1) {
			return;
		}

		$user_basic_index_data = $this->formatUserBasicData($user_basic_data);

		$indexName = LDB_RESPONSE_INDEX_NAME;
		$elasticQuery = array();
        $elasticQuery['index'] = $indexName;
        $elasticQuery['type'] = 'response';
        foreach ($doc_ids as $doc_id) {
            $elasticQuery['body'][] = array(
                                            'update'    => array(
                                                    '_id' => $doc_id
                                                )
                                            );
            $elasticQuery['body'][] = array(
                                        'doc_as_upsert' => true,
                                        'doc'           => $user_basic_index_data
                                        );
            
        }
    
       	//$indexResponse = $this->clientConn->bulk($elasticQuery);
       	$indexResponse6 = $this->clientConn6->bulk($elasticQuery);

    }

    public function getReponseDocIds($user_id){
		$this->_init();
		
		$indexName = LDB_RESPONSE_INDEX_NAME;

		$elasticQuery = array();
        $elasticQuery['index'] = $indexName;
        $elasticQuery['type'] = 'response';
        $elasticQuery['body']['_source'] = array("user_id");
        /*$elasticQuery['body']['from'] = $offset;
        $elasticQuery['body']['size'] =$size;*/
        $elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['user_id'] = $user_id;
        $result = $this->clientConn6->search($elasticQuery);
        $userIds = array();
        if(!empty($result['hits']['hits'])){
            foreach ($result['hits']['hits'] as $key => $ElasticsearchDoc) {
                $doc_ids[] = $ElasticsearchDoc['_id'];
                unset($result['hits']['hits'][$key]);
            }
        }

        return $doc_ids;		

    }

    public function formatUserBasicData($user_basic_data){
		$final_indexing_data['first_name']				= $user_basic_data['firstname'];
		$final_indexing_data['last_name']				= $user_basic_data['lastname'];
		$final_indexing_data['mobile_verified']			= $user_basic_data['mobileverified'];
		$final_indexing_data['user_email']				= $user_basic_data['email'];
		$final_indexing_data['user_mobile']				= $user_basic_data['mobile'];
		$final_indexing_data['user_creation_date']		= str_replace(' ', 'T', $user_basic_data['usercreationDate']);
		$final_indexing_data['user_location']			= $user_basic_data['city'];
		
		$final_indexing_data['user_group']				= $user_basic_data['usergroup'];
		$final_indexing_data['hardbounce']				= $user_basic_data['hardbounce'];
		$final_indexing_data['softbounce']				= $user_basic_data['softbounce'];
		$final_indexing_data['ownershipchallenged']		= $user_basic_data['ownershipchallenged'];
		$final_indexing_data['unsubscribe']				= $user_basic_data['unsubscribe'];
		$final_indexing_data['abused']					= $user_basic_data['abused'];

		if($user_basic_data['PrefData'][0]['prefYear']>0){
			$final_indexing_data['pref_year']				= $user_basic_data['PrefData'][0]['prefYear'];
		}

		$final_indexing_data['unsubscribe'] = true;
		if($user_basic_data['unsubscribe'] != 1){
			$final_indexing_data['unsubscribe'] = false;
		}

		$final_indexing_data['abused'] = true;
		if($user_basic_data['abused'] != 1){
			$final_indexing_data['abused'] = false;
		}

		$final_indexing_data['ownershipchallenged'] = true;
		if($user_basic_data['ownershipchallenged'] != 1){
			$final_indexing_data['ownershipchallenged'] = false;
		}

		$final_indexing_data['softbounce'] = true;
		if($user_basic_data['softbounce'] != 1){
			$final_indexing_data['softbounce'] = false;
		}

		$final_indexing_data['hardbounce'] = true;
		if($user_basic_data['hardbounce'] != 1){
			$final_indexing_data['hardbounce'] = false;
		}


		/*$final_indexing_data['mobile_verified'] = true;
		if($user_basic_data['mobileverified'] != 1){
			$final_indexing_data['mobile_verified'] = false;
		}*/

		$user_basic_data['mobileverified'] = $final_indexing_data['mobile_verified'];

		$final_indexing_data['is_ndnc']	= true;
		if($user_basic_data['isNDNC'] == 'NO'){
			$final_indexing_data['is_ndnc']	= false;
		}
		
		$final_indexing_data['is_test_user'] = true;
		if($user_basic_data['isTestUser'] == 'NO'){
			$final_indexing_data['is_test_user'] = false;
		}
		
		$final_indexing_data['is_ldb_user'] = true;
		if($user_basic_data['isLDBUser'] == 'NO'){
			$final_indexing_data['is_ldb_user'] = false;
		}

		return $final_indexing_data;
    }


    public function indexCourseResponseDoc($queueId,$attributesData,$site){
    	$responsemodel = $this->CI->load->model('response/responsemodel');
		
		if($site  == 'national'){
			$responseData        = $responsemodel->fetchCourseResponse($queueId);   			
		}elseif($site == 'abroad'){
			$responseData  		 = $responsemodel->fetchSAResponse($queueId);
		}
		if(empty($responseData)){
			return;
		}

		
		$listingData = array();
		if($site  == 'national'){
			$listingData                                                = $responsemodel->getCourseClientId(array($responseData['listing_type_id']),array('live'));	        
			$responseProfileResult                                      = $responsemodel->getResponseUserProfileByQueueId($queueId);				
			$listingData[$responseData['listing_type_id']]['hierarchy'] = $this->_formatResponseUserProfile($responseProfileResult);
		}elseif($site == 'abroad'){		
			$listingData                                                = $responsemodel->getCourseClientId(array($responseData['listing_type_id']),array('live'));	        
			$responseProfile   = Modules::run("trackingMIS/MISCrons/_getAbroadCourseinformation",array($responseData['listing_type_id']));			
			$listingData[$responseData['listing_type_id']]['hierarchy'] = $responseProfile[$responseData['listing_type_id']];
		}
		

		$userBasicData = modules::run('LDB/LDB_Server/sgetUserDetails', array(),true,$responseData['userId']);
		$userBasicData = json_decode(base64_decode($userBasicData),true);

		// get user mail info
		if($responseData['user_mail_queue_id']){
			$mailResponse = $responsemodel->getTMailQueueInfo($responseData['user_mail_queue_id']);
			$responseData['user_mail_queue_time'] = $mailResponse['createdTime'];
		}

		// get client sms info
		if($responseData['client_sms_queue_id']){
			$smsResponse = $responsemodel->getSMSQueueInfo($responseData['client_sms_queue_id']);
			$responseData['client_sms_queue_time'] = $smsResponse['createdDate'];
		}

		$indexingDoc[$site.'_'.$queueId] = $this->prepareIndexingDocument($responseData, $listingData[$responseData['listing_type_id']], $userBasicData[$responseData['userId']], $attributesData);
		$indexName     = SHIKSHA_RESPONSE_INDEX_NAME;

		$indexResponse = $this->insertResponseDoc($indexingDoc,$indexName,'response');		
		return $indexResponse;
    }

    public function _formatResponseUserProfile($result){
    	$returnData = $substreamList = $specilizationList = $courseList = $modeList =  array();
		foreach ($result as $key => $value) {
			$returnData['stream'] = $value['stream_id'];
			if($value['substream_id']){
				$returnData['substream_id'][$value['substream_id']] = 1;				
			}
			$userProfile =  json_decode($value['user_profile'],true);
			
			foreach ($userProfile['mode'] as $mode) {
				if($mode > 0){
					$returnData['mode'][$mode] = 1;
				}
			}

			foreach ($userProfile['course_id'] as $course) {
				if($course > 0){
					$returnData['course'][$course] = 1;
				}
			}

			foreach ($userProfile['specialization'] as $specialization) {
				if($specialization > 0){
					$returnData['specialization'][$specialization] = 1;
				}
			}
		}

		return $returnData;
    }


    public function indexExamResponseDoc($queueId,$attributesData){
    	$responsemodel = $this->CI->load->model('response/responsemodel');		
		$responseData  = $responsemodel->fetchExamResponse($queueId);   
		if(empty($responseData)){
			return;
		}

		$responseProfileResult                                      = $responsemodel->getResponseUserProfileByQueueId($queueId);				
		$listingData[$responseData['listing_type_id']]['hierarchy'] = $this->_formatResponseUserProfile($responseProfileResult);

		$userBasicData = modules::run('LDB/LDB_Server/sgetUserDetails', array(),true,$responseData['userId']);
		$userBasicData = json_decode(base64_decode($userBasicData),true);
		$indexingDoc['national_'.$queueId] = $this->prepareIndexingDocument($responseData, $listingData[$responseData['listing_type_id']], $userBasicData[$responseData['userId']], $attributesData);
		$indexName     = SHIKSHA_RESPONSE_INDEX_NAME;
		$indexResponse = $this->insertResponseDoc($indexingDoc,$indexName,'response');		
		return $indexResponse;
    }

   
    public function prepareIndexingDocument($responseData,$listingData,$userBasicData,$attributesData,$session_data){
		$finalIndexingData = array();

		if(empty($userBasicData['userid'])){
			return false;
		}

		if($responseData['queue_id'] > 0){
			$finalIndexingData['queue_id']                     = $responseData['queue_id'];			
		}
		
		if($responseData['temp_lms_id'] > 0){
			$finalIndexingData['temp_lms_id']                  = $responseData['temp_lms_id'];			
		}
		

		if($responseData['queue_time'] != ''){
			$finalIndexingData['queue_time']                   	   = str_replace(' ', 'T', $responseData['queue_time']);			
		}
		
		if($responseData['temp_lms_time'] != ''){
			$finalIndexingData['temp_lms_time']                = str_replace(' ', 'T', $responseData['temp_lms_time']);			
		}

		if($responseData['user_mail_queue_time'] != ''){
			$finalIndexingData['user_mail_sent_time']          = str_replace(' ', 'T', $responseData['user_mail_queue_time']);
		}

		if($responseData['client_sms_queue_time'] != ''){
			$finalIndexingData['client_sms_queue_time']          = str_replace(' ', 'T', $responseData['client_sms_queue_time']);
		}


		// if($responseData['user_mail_queue_id'] != ''){
		// 	$finalIndexingData['actual_user_mail_sent_time']   = str_replace(' ', 'T', $responseData['temp_lms_time']);
		// }

		// if($responseData['user_mail_queue_id'] != ''){
		// 	$finalIndexingData['client_mail_sent_time']        = str_replace(' ', 'T', $responseData['temp_lms_time']);
		// }

		// if($responseData['user_mail_queue_id'] != ''){
		// 	$finalIndexingData['actual_client_mail_sent_time'] = str_replace(' ', 'T', $responseData['temp_lms_time']);
		// }


		$finalIndexingData['considered_for_response']    = 1;
		if($responseData['use_for_response']  == 'n'){
			$finalIndexingData['considered_for_response']    = 0;
		}

		if($responseData['listing_type_id'] > 0){
			$finalIndexingData['response_listing_type_id'] = $responseData['listing_type_id'];
		}

		if($responseData['listing_type'] != ''){
			$finalIndexingData['response_listing_type']    = $responseData['listing_type'];
		}

		if($responseData['action'] != ''){
			$finalIndexingData['response_action_type']     = $responseData['action'];
		}

		if($responseData['institute_id'] > 0){
			$finalIndexingData['institute_id']     = $responseData['institute_id'];
		}

		if($responseData['listing_subscription_type'] != ''){
			if($responseData['listing_subscription_type'] == 'free'){
				$finalIndexingData['is_response_paid']     = '0';
			}else{
				$finalIndexingData['is_response_paid']     = 1;
			}			
		}

		if($responseData['isClientResponse'] != ''){

			if($responseData['isClientResponse'] == 'Yes'){
				$finalIndexingData['is_client_response'] = true;	
			}else{
				$finalIndexingData['is_client_response'] = false;	
			}

		}

		if($responseData['submit_date'] != ''){
			$finalIndexingData['response_time']                = str_replace(' ', 'T', $responseData['submit_date']);			
		}

		if(empty($responseData['listing_location_id']) && $responseData['listing_type'] =='course' && $responseData['use_for_response']  == 'y'){
			//return false;		//Response will not be created without city. Check to be added in response creation cron by naveen -- comments  by Ajay
		}

		if($responseData['listing_location_id'] > 0){
			$finalIndexingData['response_listing_location_id'] = $responseData['listing_location_id'];			
		}

		if($responseData['response_city'] > 0){
			$finalIndexingData['response_city_id']             = $responseData['response_city'];
			$finalIndexingData['response_city_name']           = $attributesData['city'][$responseData['response_city']];
			$finalIndexingData['response_city_tier']           = $attributesData['tier'][$responseData['response_city']];
		}

		if($responseData['response_locality'] > 0){
			$finalIndexingData['response_locality_id']         = $responseData['response_locality'];
			$finalIndexingData['response_locality_name']       = $attributesData['locality'][$responseData['response_locality']];
		}

		if($responseData['listing_type'] =='course' || $responseData['listing_type'] =='exam'){
			if($listingData){			
				$clientId    = $listingData['username']['username'];
				if($clientId > 0){
					$finalIndexingData['client_id'] = $clientId;
				}

				if($listingData['hierarchy']){
					$hierarchyData = $listingData['hierarchy'];

					if($hierarchyData['stream']){
						$finalIndexingData['response_stream_id']           = $hierarchyData['stream'];
						$finalIndexingData['response_stream_name']         = $attributesData['stream'][$hierarchyData['stream']];				
					}

					if($hierarchyData['substream_id']){
						$substreamIds = array_keys($hierarchyData['substream_id']);
						$finalIndexingData['response_sub_stream_id']       = $substreamIds;
						foreach ($substreamIds as $key => $substream) {
							$finalIndexingData['response_sub_stream_name'][]     = $attributesData['substream'][$substream];							
						}
					}

					if($hierarchyData['specialization']){
						$specializationIds = array_keys($hierarchyData['specialization']);
						$finalIndexingData['response_specialization_id']   = $specializationIds;
						foreach ($specializationIds as $key => $specializationId) {
							$finalIndexingData['response_specialization_name'][] = $attributesData['specialization'][$specializationId];							
						}
					}

					if($hierarchyData['course']){
						$courseIds = array_keys($hierarchyData['course']);
						$finalIndexingData['response_base_course_id']      = $courseIds;
						foreach ($courseIds as $key => $course) {
							$finalIndexingData['response_base_course_name'][]    = $attributesData['basecourse'][$course];							
						}
					}				
					if($hierarchyData['ldbCourseId']){
						$finalIndexingData['response_desired_course_id']      = $hierarchyData['ldbCourseId'];
						$finalIndexingData['response_desired_course_name']    = $attributesData['desired_course'][$hierarchyData['ldbCourseId']];
					}

					if($hierarchyData['categoryId']){
						$finalIndexingData['response_category_id']      = $hierarchyData['categoryId'];
					}

					if($hierarchyData['subCategoryId']){
						$finalIndexingData['response_sub_category_id']      = $hierarchyData['subCategoryId'];
					}

					if($hierarchyData['courseLevel']){
						$finalIndexingData['response_course_level']      = $hierarchyData['courseLevel'];
					}

					if($hierarchyData['countryId']){
						$finalIndexingData['response_country_id']   = $hierarchyData['countryId'];
						$finalIndexingData['response_country_name'] = $attributesData['country'][$hierarchyData['countryId']];
					}

					if($hierarchyData['listingId']){
						$finalIndexingData['response_university_id']      = $hierarchyData['listingId'];
					}

					if($hierarchyData['mode']){
						$modeIds = array_keys($hierarchyData['mode']);
						$finalIndexingData['response_mode_id']      = $modeIds;
						foreach ($modeIds as $key => $mode) {
							$finalIndexingData['response_mode_name'][]    = $attributesData['mode'][$mode];							
						}
					}
				}
			}
        }

        if($responseData['examId']>0){
			$finalIndexingData['exam_id']	= $responseData['examId'];
		}
		if($responseData['examName']){
			$finalIndexingData['exam_name']	= $responseData['examName'];
		}
		
		if($responseData['groupName']){
			$finalIndexingData['exam_group_name']	= $responseData['groupName'];
		}

            

        $finalIndexingData['user_id']             = $responseData['userId'];
        if($userBasicData['firstname'] != ''){
			$finalIndexingData['first_name']          = $userBasicData['firstname'];        	
        }
        if($userBasicData['lastname'] != ''){
			$finalIndexingData['last_name']           = $userBasicData['lastname'];        	
        }
        if($userBasicData['email'] != ''){
			$finalIndexingData['user_email']          = $userBasicData['email'];        	
        }
        if($userBasicData['mobile'] != ''){
			$finalIndexingData['user_mobile']         = $userBasicData['mobile'];        	
        }
        $isNumericCityId = is_numeric($userBasicData['city']);
		if($isNumericCityId){
			$finalIndexingData['user_city_id']        = $userBasicData['city'];
			$finalIndexingData['user_city_name']      = $attributesData['city'][$userBasicData['city']];
		}
		$isNumericLocalityId = is_numeric($userBasicData['Locality']);
		if($isNumericLocalityId){
			$finalIndexingData['user_locality_id']    = $userBasicData['Locality'];
			$finalIndexingData['user_locality_name']  = $attributesData['locality'][$userBasicData['Locality']];
		}

		
		$finalIndexingData['user_work_experience']    = $userBasicData['experience'];

		$competitiveExam = "";
		foreach($userBasicData['EducationData'] as $value){		    
		    if($value['Level'] == "Competitive exam"){
		        if($competitiveExam) {
		            $competitiveExam .= ', ';
		        }
		        $competitiveExam.=$value['Name'];
		    }
		}
		if($competitiveExam){
			$finalIndexingData['user_exam_taken']    = $competitiveExam;
		}

		$finalIndexingData['abused']              = $userBasicData['abused'];			

		$finalIndexingData['ownershipchallenged'] = $userBasicData['ownershipchallenged'];			

		if($userBasicData['usercreationDate']){
			$finalIndexingData['user_creation_date']  = str_replace(' ', 'T', $userBasicData['usercreationDate']);			
		}


		$finalIndexingData['is_ndnc'] = 1;
        if($userBasicData['isNDNC'] == 'NO'){
            $finalIndexingData['is_ndnc'] = 0;
        }

        $finalIndexingData['is_ldb_user'] = 1;
        if($userBasicData['isLDBUser'] == 'NO'){
                $finalIndexingData['is_ldb_user'] = 0;
        }

        $finalIndexingData['is_test_user'] = 1;        
        if($userBasicData['isTestUser'] == 'NO'){
            $finalIndexingData['is_test_user'] = 0;
        }

		if($responseData['keyName']           != ''){
			$finalIndexingData['source']          = $responseData['keyName'];
		}

		if($responseData['id']           != ''){
			$finalIndexingData['tracking_id']          = $responseData['id'];
		}
		
		$finalIndexingData['mobile_verified'] = $userBasicData['mobileverified'];
		
		if($responseData['site']              != ''){
			$finalIndexingData['site']            = $responseData['site'];
		}
		if($responseData['siteSource']        != ''){
			$finalIndexingData['device']     = $responseData['siteSource'];
		}
		
		if($responseData['page']              != ''){
			$finalIndexingData['page']            = $responseData['page'];
		}
		
		if($responseData['widget']            != ''){
			$finalIndexingData['widget']          = $responseData['widget'];
		}
			
		$finalIndexingData['user_group']          = $userBasicData['usergroup'];
		$finalIndexingData['hardbounce']          = $userBasicData['hardbounce'];
		$finalIndexingData['softbounce']          = $userBasicData['softbounce'];
		$finalIndexingData['unsubscribe']         = $userBasicData['unsubscribe'];		
		

		/*this is for one time indexing only - starts*/
		if($session_data['utm_source'] != ''){
			$finalIndexingData['utm_source']          = $session_data['utm_source'];		
		}

		if($session_data['utm_medium'] != ''){
			$finalIndexingData['utm_medium']          = $session_data['utm_medium'];		
		}

		if($session_data['utm_campaign'] != ''){
			$finalIndexingData['utm_campaign']          = $session_data['utm_campaign'];		
		}

		if($session_data['source'] != ''){
			$finalIndexingData['response_source']          = $session_data['source'];		
		}
		/*this is for one time indexing only - ends*/

		if($responseData['sessionId'] != ''){
			$finalIndexingData['visitor_session_id']          = $responseData['sessionId'];		
		}

		if($responseData['utm_source'] != ''){
			$finalIndexingData['utm_source']          = $responseData['utm_source'];		
		}

		if($responseData['utm_medium'] != ''){
			$finalIndexingData['utm_medium']          = $responseData['utm_medium'];		
		}

		if($responseData['utm_campaign'] != ''){
			$finalIndexingData['utm_campaign']          = $responseData['utm_campaign'];		
		}

		if($responseData['source'] != ''){
			$finalIndexingData['response_source']          = $responseData['source'];		
		}

		if($responseData['site'] == "Domestic"){

            $finalIndexingData['deviceType']         = $responseData['siteSourceType'];

			if($responseData['siteSource'] == 'Mobile'){
				$site_source_type = $this->getSiteSourceBySessionId($responseData['sessionId']);
			}


            if($site_source_type == 'androidApp'){
				$finalIndexingData['deviceType']     = $site_source_type;            	
            }

            $finalIndexingData['pageGroup']          = $responseData['pageGroup'];
        }
		
		return $finalIndexingData;
    }

    public function prepareUserIndexDoc($userBasicData,$attributesData){
    	if($userBasicData['firstname'] != ''){
			$finalIndexingData['first_name']          = $userBasicData['firstname'];        	
        }
        if($userBasicData['lastname'] != ''){
			$finalIndexingData['last_name']           = $userBasicData['lastname'];        	
        }
        if($userBasicData['email'] != ''){
			$finalIndexingData['user_email']          = $userBasicData['email'];        	
        }
        if($userBasicData['mobile'] != ''){
			$finalIndexingData['user_mobile']         = $userBasicData['mobile'];        	
        }

		if($userBasicData['city']){
			$finalIndexingData['user_city_id']        = $userBasicData['city'];
			$finalIndexingData['user_city_name']      = $attributesData['city'][$userBasicData['city']];
		}

		if($userBasicData['Locality'] > 0){
			$finalIndexingData['user_locality_id']    = $userBasicData['Locality'];
			$finalIndexingData['user_locality_name']  = $attributesData['locality'][$userBasicData['Locality']];
		}else{
			$finalIndexingData['user_locality_id']    = null;
			$finalIndexingData['user_locality_name']  = null;
		}

		$finalIndexingData['abused']              = $userBasicData['abused'];			
		$finalIndexingData['ownershipchallenged'] = $userBasicData['ownershipchallenged'];			

		if($userBasicData['usercreationDate']){
			$finalIndexingData['user_creation_date']  = str_replace(' ', 'T', $userBasicData['usercreationDate']);			
		}

		
		$finalIndexingData['user_work_experience']    = $userBasicData['experience'];


		$competitiveExam = "";
		foreach($userBasicData['EducationData'] as $value){		    
		    if($value['Level'] == "Competitive exam"){
		        if($competitiveExam) {
		            $competitiveExam .= ', ';
		        }
		        $competitiveExam.=$value['Name'];
		    }
		}
		if($competitiveExam){
			$finalIndexingData['user_exam_taken']    = $competitiveExam;
		}


		$finalIndexingData['is_ndnc'] = 1;
        if($userBasicData['isNDNC'] == 'NO'){
            $finalIndexingData['is_ndnc'] = 0;
        }

        $finalIndexingData['is_ldb_user'] = 1;
        if($userBasicData['isLDBUser'] == 'NO'){
                $finalIndexingData['is_ldb_user'] = 0;
        }

        $finalIndexingData['is_test_user'] = 1;        
        if($userBasicData['isTestUser'] == 'NO'){
            $finalIndexingData['is_test_user'] = 0;
        }

    	$finalIndexingData['mobile_verified'] = $userBasicData['mobileverified'];
	    $finalIndexingData['user_group']          = $userBasicData['usergroup'];
		$finalIndexingData['hardbounce']          = $userBasicData['hardbounce'];
		$finalIndexingData['softbounce']          = $userBasicData['softbounce'];
		$finalIndexingData['unsubscribe']         = $userBasicData['unsubscribe'];		

		return $finalIndexingData;
    }

    public function partialIndexingOfResponseTime($tempLMSId,$newSubmitTime,$site){
		$responsemodel     = $this->CI->load->model('responsemodel');
		$data              = $responsemodel->getQueueIdByTempLMSId($tempLMSId,'',$site);

		if(!empty($data)){
			$this->_init();
			$tempLMSId = $site.'_'.$data['queue_id'];
			$params = [
				'index' => SHIKSHA_RESPONSE_INDEX_NAME,
				'type'  => 'response',
				'id'    => $tempLMSId,
			    'body' => [
			        'doc' => [
			            'response_time' => str_replace(' ', 'T', $newSubmitTime)
			        ]
			    ]
			];       	
			
			$response = $this->clientConn5->update($params);
			return $response;
		}
	}


	public function getResponseDocumentId($tempLMSId){
		$this->_init();

		$params                   	= array();
		$params['index']          	= SHIKSHA_RESPONSE_INDEX_NAME;
		$params['type']           	= "response";
		$params['body']['_source']  = array('queue_id');
		$params['filter_path'] 	  	= array("hits.hits._source");

		$params['body']['sort']['queue_id']['order'] = 'asc';

		$params['body']['query']['bool']['filter'][]  =array(
														'term' => array(
															'temp_lms_id' => $tempLMSId
														)
													 );


		$params['body']['query']['bool']['filter'][]  =array(
														'term' => array(
															'considered_for_response' => true
														)
													 );

		 $result = $this->clientConn5->search($params);

		 return $result['hits']['hits'];
		 //return $result['hits']['hits'][0]['_source'];
	}

	public function  partialIndexingofOldResponseDoc($tempLMSId,$excludeQueueId,$site){
		/*$responsemodel     = $this->CI->load->model('responsemodel');
		$data              = $responsemodel->getQueueIdByTempLMSId($tempLMSId,$excludeQueueId,$site);*/

		$responsemodel     = $this->CI->load->model('responsemodel');
		$data_temp = $this->getResponseDocumentId($tempLMSId);

        foreach ($data_temp as $key => $value) {
            if( ($value['_source']['queue_id'] !=$excludeQueueId) && $value['_source']['queue_id']>0 ){
                $data['queue_id'] = $value['_source']['queue_id'];
                break;
            }
        }


		if(!empty($data)){
			$this->_init();
			$queueId = $data['queue_id'];
			$params = [
				'index' => SHIKSHA_RESPONSE_INDEX_NAME,
				'type'  => 'response',
				'id'    => $site."_".$queueId,
			    'body' => [
			        'doc' => [
			            'considered_for_response' => 0
			        ]
			    ]
			];       	
			
			$responsemodel->updateResponseStatus($excludeQueueId,$site,'y');

			if($queueId != $excludeQueueId){
				$responsemodel->updateResponseStatus($queueId,$site,'n');
				$response = $this->clientConn5->update($params);
			}else{
				mail('ajay.sharma@shiksha.com', "Response ES indexing same tempLMSids", $tempLMSId.'-'.$queueId);
			}



			return $response;
		}
		
	}

	public function indexAllCourseResponses($attributesData,$userId,$tempLMSId, $tracking_data_map, $abroad_cache_data, $nat_cache_data){
		$responsemodel     = $this->CI->load->model('responsemodel');
		

		$responses              = $responsemodel->getAllCourseResponses($userId,$tempLMSId);

		if(empty($responses)){
			error_log("===============  no responses ".$userId);
			return false;
		}
		
		// user info
		
		$userBasicData = modules::run('LDB/LDB_Server/sgetUserDetails', array(),true, $userId,'national');
		$userBasicData = json_decode(base64_decode($userBasicData),true);
		
		if(empty($userBasicData[$userId]['userid'])){
			error_log("User obj not found :".$userId);
			return false;
		}
		

		$abroadListings =  $allCourseIds = array();

		$session_ids = array();

		foreach ($responses as $key => $value) {
		
			$session_ids[]  = $value['sessionId'];

			$allCourseIds[$value['listing_type_id']] = $value['listing_type_id'];

			if($nat_cache_data[$value['instituteLocationId']])	{
				$dataToPass['listing_type_id']                           = $value['listing_type_id'];
				$dataToPass['user_id']                                   = $value['userId'];
				$dataToPass['listing_type']                              = $value['listing_type'];
				$dataToPass['submit_date']                               = $value['submit_date'];
				$dataToPass['listingid'][] 								 = $value['listing_type_id'];
				
				$responses[$key]['listing_location_id']				 	 = $nat_cache_data[$value['instituteLocationId']]['listing_location_id'];
				$responses[$key]['response_city']		 			 	 = $nat_cache_data[$value['instituteLocationId']]['response_city'];
				$responses[$key]['response_locality']				 	 = $nat_cache_data[$value['instituteLocationId']]['response_locality'];
				$responses[$key]['institute_id']		 			 	 = $nat_cache_data[$value['instituteLocationId']]['institute_id'];

			}else{
				$abroadListings[$value['listing_type_id']]			 	 = $value['listing_type_id'];				
				$responses[$key]['listing_location_id']				 	 = $abroad_cache_data[$value['instituteLocationId']]['abroadListingLocationId'];
				$responses[$key]['response_city']		 			 	 = $abroad_cache_data[$value['instituteLocationId']]['abroad_response_city'];
				$responses[$key]['response_locality']				 	 = $abroad_cache_data[$value['instituteLocationId']]['abroad_response_locality'];
				$responses[$key]['institute_id']		 			 	 = $abroad_cache_data[$value['instituteLocationId']]['abroad_institute_id'];
			}

			/*if(empty($value['listing_location_id']) && !empty($value['abroadListingLocationId'])){
				$abroadListings[$value['listing_type_id']] = $value['listing_type_id'];				
				$responses[$key]['listing_location_id'] = $value['abroadListingLocationId'];
				$responses[$key]['response_city']       = $value['abroad_response_city'];
				$responses[$key]['response_locality']   = $value['abroad_response_locality'];
				$responses[$key]['institute_id']        = $value['abroad_institute_id'];
			}else {
				$dataToPass['listing_type_id']                           = $value['listing_type_id'];
				$dataToPass['user_id']                                   = $value['userId'];
				$dataToPass['listing_type']                              = $value['listing_type'];
				$dataToPass['submit_date']                               = $value['submit_date'];
				//$nationalResponseProfile[$value['listing_type_id']]['hierarchy'] = $responsemodel->getResponseUserProfile($dataToPass);
				$dataToPass['listingid'][] = 	$value['listing_type_id'];
			}*/

			/*$this->dbHandle->select('tKey.id,tKey.keyName,tKey.page,tKey.widget,tKey.site, tKey.siteSource');*/
			if($value['tracking_keyid']>0){
				$responses[$key]['id']        		= $value['tracking_keyid'];
				$responses[$key]['keyName']   		= $tracking_data_map[$value['tracking_keyid']]['keyName'];
				$responses[$key]['page']      		= $tracking_data_map[$value['tracking_keyid']]['page'];
				$responses[$key]['widget']    		= $tracking_data_map[$value['tracking_keyid']]['widget'];
				$responses[$key]['site']      		= $tracking_data_map[$value['tracking_keyid']]['site'];
				$responses[$key]['siteSource']      = $tracking_data_map[$value['tracking_keyid']]['siteSource'];
			}
		}
		
		$session_data = $this->fetchSessionDataFromES($session_ids);
		
		if(count($dataToPass['listingid'])>0){
			$nationalResponseProfileData = $responsemodel->getResponseUserProfileCall($dataToPass);

			foreach ($nationalResponseProfileData as $data) {
				$temp = $data;
				unset($temp['listing_id']);
				$nationalResponseProfile[$data['listing_id']]['hierarchy'] = $this->_formatResponseUserProfile(array($temp));
				//$nationalResponseProfile[$data['listing_id']]['hierarchy'] = $temp;
			}
			unset($temp);
			unset($nationalResponseProfileData);

		}

		//check this - ajay
		$abroadResponseProfile = array();
        if(!empty($abroadListings)){
        	$abroadResponseProfile   = Modules::run("trackingMIS/MISCrons/_getAbroadCourseinformation",$abroadListings);			        	
        }
		

        $listingData = $responsemodel->getCourseClientId($allCourseIds,array('live','deleted'));


        foreach ($listingData as $listingId => $value) {
        	if($nationalResponseProfile[$listingId]){
        		$listingData[$listingId]['hierarchy'] = $nationalResponseProfile[$listingId]['hierarchy'];        		
        	}else if($abroadResponseProfile[$listingId]){
        		$listingData[$listingId]['hierarchy'] = $abroadResponseProfile[$listingId];        		
        	}
        }

		$documents = array();
		
		foreach ($responses as $key => $value) {	

			$docResponse = $this->prepareIndexingDocument($value,$listingData[$value['listing_type_id']],$userBasicData[$value['userId']],$attributesData,$session_data[$value['sessionId']]);


			if($docResponse != false){
				$documents["temp_".$value['temp_lms_id']] = $docResponse;
			}
		}		

		return $documents;

		$indexName     = SHIKSHA_RESPONSE_INDEX_NAME;
		$indexResponse = $this->insertResponseDoc($documents,$indexName,'response');		
		return $indexResponse;
	}

	private function fetchSessionDataFromES($session_ids){
		$ESConnectionLib = $this->CI->load->library('trackingMIS/elasticSearch/ESConnectionLib');
	        $this->ESClientConn = $ESConnectionLib->getShikshaESServerConnection();

  		$params                   = array();
		$params['index']          = SESSION_INDEX_NAME;
		$params['type']           = "session";
		
		$params['body']['_source'] 						= array("utm_source","utm_medium","utm_campaign","source","sessionId");
		$params['filter_path'] 							= array("hits.hits._source");
		/*$params['body']['query']['terms']['sessionId']  = array("090d6e289219f505a03e4ee25e99e959576ca10820180903210849" , "0754919735853d0c39da8fef94cf8a0ba3ae3a7c20171218125316");
*/
		$response    			= $this->ESClientConn->search($params);
		$response 				= $response['hits']['hits'];


		foreach ($response as $session_data) {
			 
			$returnSessionData[$session_data['_source']['sessionId']]['utm_campaign']  = $session_data['_source']['utm_campaign'];
			$returnSessionData[$session_data['_source']['sessionId']]['utm_source']    = $session_data['_source']['utm_source'];
			$returnSessionData[$session_data['_source']['sessionId']]['utm_medium']    = $session_data['_source']['utm_medium'];
			$returnSessionData[$session_data['_source']['sessionId']]['source']    	 = $session_data['_source']['source'];
		}

		return $returnSessionData;
  	}

  	private function getSiteSourceBySessionId($sessionId){
  		$ESConnectionLib = $this->CI->load->library('trackingMIS/elasticSearch/ESConnectionLib');
	    $this->ESClientConn = $ESConnectionLib->getShikshaESServerConnection();

  		$params                   = array();
		$params['index']          = SESSION_INDEX_NAME_REALTIME_SEARCH;

		$params['type']           = "session";
		
		$params['body']['_source'] 						= array("isMobile");
		$params['filter_path'] 							= array("hits.hits._source");
		$params['body']['query']['terms']['sessionId']  = array($sessionId);

		$response    			= $this->ESClientConn->search($params);
		$response 				= $response['hits']['hits'];

		$site_source_type = $response[0]['_source']['isMobile'];

		return $site_source_type;

  	}
}

?>
