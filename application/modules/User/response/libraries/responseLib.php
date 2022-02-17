<?php

/** 
 * Library for response Creation/ Response Mail Sending.
*/

class responseLib {

	function __construct() {
		$this->CI = & get_instance();	
	}

	/**
	* This function load all necessary config, model related to response which will be used in various flows 
	*/
	function _init(){
		
		$this->CI->load->model('response/responsemodel');
		$this->responseModel = new ResponseModel();

		$this->CI->load->config('response/responseConfig');

		$this->CI->load->model('user/usermodel');
		$this->usermodel = new Usermodel();

	}

	/**
	* This function is used for getting data for Response Profile based on user interest fields
	*/
	public function getDataForResponseProfile($stream,$substream,$baseCourses,$subStreamSpecMapping, $mode, $level, $credential){
		
		$this->_init();

		/*Sub Stream Specialization */
		$subStreamSpecMapping = json_decode($subStreamSpecMapping,true);
		$userInterestData = $this->usermodel->getDataForResponseProfile($stream,$substream,$baseCourses,$subStreamSpecMapping);
		
		$subStreamSpecBasecourseMapping = $this->getMergedArrayForSubstreamSpecBaseCourse($stream,$subStreamSpecMapping,$userInterestData, $baseCourses, $level, $credential);

		$user_profile_response = $this->getAllDataForResponseUserProfile($stream,$substream,$subStreamSpecBasecourseMapping,$mode,$level,$credential);

		return $user_profile_response;
	}

	/**
	* This function is used for getting merge array for using user interest fields 
	*/
	public function getMergedArrayForSubstreamSpecBaseCourse($stream,$subStreamSpecMapping,$userInterestData, $baseCourses=array(), $level, $credential){

		$temp = array();
		$counter = 0;

		if(empty($subStreamSpecMapping)){
			$temp['ungrouped'][$counter] = array(
									'course_id' => $userInterestData[$stream]['unMappedCourses']
									);
		}

		$this->CI->load->library('registration/RegistrationLib');
        $registrationLib = new RegistrationLib();
        
        $hyperLocalData = $registrationLib->filterDummyBaseCourses($baseCourses);
 
		foreach ($subStreamSpecMapping as $substream => $spec) {

		    if(!empty($hyperLocalData['dummyCourses'])){
        		$temp[$substream]['course_id'] = $hyperLocalData['dummyCourses'];
        	}

			if(!empty($userInterestData[$stream][$substream])){
				
				// $temp[$substream]['course_id'] = array_values($userInterestData[$stream][$substream]);

				if($substream == 'ungrouped'){
	                $mappedbaseCourses = $userInterestData['mappedbaseCourses'];
	            }else{
	                $mappedbaseCourses = array();
	            }

				$courseSpecCombinations = $this->usermodel->getCourseSpecCombinations($stream, $substream, $spec, $userInterestData[$stream][$substream], array_keys($subStreamSpecMapping), $mappedbaseCourses, array_keys($subStreamSpecMapping), $level, $credential, array());

				unset($courseSpecCombinations['isDummyCourse']);
                unset($courseSpecCombinations['dummyCourseId']);

                foreach($courseSpecCombinations as $key=>$values){
                	$temp[$substream]['course_id'][] = $values['baseCourse'];
                }

			} else if(isset($userInterestData[$stream][$substream])) {
 				// $temp[$substream]['course_id'] = $baseCourses;
 				$temp[$substream]['course_id'] = array();
			}

			if(empty($temp[$substream]['course_id']) && !empty($hyperLocalData['dummyCourses'])){
				$temp[$substream]['course_id'] = $hyperLocalData['dummyCourses'];
			}

			if(!empty($spec)){
				$temp[$substream]['specialization'] = $spec;
			}

			$counter++;
		}
		
		return $temp;
	}

	/**
	* This function is used for getting data for Response Profile
	*/
	public function getAllDataForResponseUserProfile($stream,$substream,$subStreamSpecBasecourseMapping,$mode,$level,$credential){

		$returnData = array();
		if(!empty($subStreamSpecBasecourseMapping)) {
			foreach($subStreamSpecBasecourseMapping as $subStream => $bcSpec){
				$tempData = array();
				$tempData['mode'] = $mode;
				$tempData['stream_id'] = $stream;
				
				if($subStream == 'ungrouped'){
					$subStream = 0;
					
					if(!empty($bcSpec[0])){
						$bcSpec = $bcSpec[0];
					}

				}

				if(!empty($subStream)){
					$tempData['substream_id'] = $subStream;
				}

				if(!empty($bcSpec['course_id'])){
					$tempData['course_id'] = $bcSpec['course_id'];
				} else {
					$tempData['course_id'] = $this->getDummyBaseCourseForResponseProfile($level, $credential);
				}

				if(!empty($bcSpec['specialization'])){
					$tempData['specialization'] = $bcSpec['specialization'];
				}

				$returnData[$subStream] = json_encode($tempData);
			}
		}
		
		return $returnData;
	}
	
	public function getDummyBaseCourseForResponseProfile($level, $credential) {

		$this->CI->load->builder('listingBase/ListingBaseBuilder');
		$listingBase = new \ListingBaseBuilder();
		$baseCourseRepo = $listingBase->getBaseCourseRepository();

		$dummyCourses = $baseCourseRepo->getAllDummyBaseCourses();
		$returnArray = array();
        foreach ($dummyCourses as $key => $dummyCourseData) {

            if($dummyCourseData['level'] == $level && $dummyCourseData['credential'] == $credential){

                $returnArray[] = $dummyCourseData['base_course_id'];

            }else if($dummyCourseData['level'] == $level && empty($credential)){
                
                $returnArray[] = $dummyCourseData['base_course_id'];

            }

        }
        
        return $returnArray;

	}

	/**
	* This Function for checking user currection action type with existing user action type for action type upgradation
	*/
	public function checkForupgradeResponse($currentAction, $newAction,$listingType = 'course') {	

		$this->_init();
		if($listingType == 'exam'){
			global $examResponseGrades;
			$responseGrades = $examResponseGrades;
		}else{
			global $pageTypeResponseActionMapping, $responseGrades;
			$shortlistResponseActions = array_values($pageTypeResponseActionMapping);
			foreach($shortlistResponseActions as $responseAction) {
				$shortlistResponseGrades[$responseAction] = 2;
			}
			
			$responseGrades       = array_merge($responseGrades, $shortlistResponseGrades);			
		}
		
		$currentResponseGrade = $responseGrades[$currentAction];
		$newResponseGrade     = $responseGrades[$newAction];
		
		$makeResponse         = false;
		if($currentResponseGrade > 1) {
			if(stripos($newAction, 'client')) {
				$makeResponse = true;
			}
		}

		$upgradeResponse     = false;
		if(($currentResponseGrade && $newResponseGrade && $newResponseGrade < $currentResponseGrade) || $makeResponse) {
			$upgradeResponse = true;
		}

		return $upgradeResponse;

	}	

	/**
	* This Function is used for getting the encoded message for Brochure URL to be sent to the user
	**/	
	public function getEncodedMsgForBrochureURL( $userId, $courseId, $tracking_key )	{
	    $salt = "tpyrcedottlucffidegassemekamottlas";
	    $message = $userId.$salt.$courseId.$salt.$tracking_key;
	    $encryptedMsg = base64_encode($message);
	    
	    $encryptedMsg = urlencode($encryptedMsg);

	    return $encryptedMsg;
	}

	/**
	* This Function is used for getting the original message out of encoded message
	**/	
	public function getDecodedMsgForBrochureURL( $encryptedMsg )	{
	    $salt = "tpyrcedottlucffidegassemekamottlas";
	    $decryptedMsg = urldecode($encryptedMsg);
	    $decryptedMsg = base64_decode($decryptedMsg);
	    
	    $message = explode($salt, $decryptedMsg);
	    
	    $userId	= $message[0];
	    $courseId = $message[1];
	    $tracking_key = $message[2];
	    
	    $data = array($userId, $courseId, $tracking_key);
	
	    return $data;
	}

	/**
	* This Function is used to make curl request and returns header and body
	**/	
	public function makeCurlRequest($url,$method,$params) {

		$response = array();
		 
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL,$url);
		if($method == 'post') {
			curl_setopt($ch,CURLOPT_POST,1);
			curl_setopt($curl, CURLOPT_POSTFIELDS,$params);
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);

		$data = curl_exec($ch);
		// Then, after your curl_exec call:
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($data, 0, $header_size);
		$body = substr($data, $header_size);

		curl_close($ch);

		$response['header'] = $header;
		$response['header_size'] = $header_size;
		$response['body'] = $body;

		return $response;
	}

	/* API to migrate response of a course to the other
	 * @Params: $courses array
	 */
	function responseMigration($courses = array()){
		$data = array();
		$oldListingId = array_keys($courses);
		$data[0]['listingType'] = 'course';
		$data[0]['oldListingId'] = $oldListingId[0];
		$data[0]['newListingId'] = $courses[$oldListingId[0]];
		$data[0]['isValid'] = 'Yes';
		$data[0]['errors'] = array();

		Modules::run('lms/ResponseMigration/responseMigrationForCourseDeletion',$data);
		return true;
	}

	function sendViewedResponseMail($viewedResponseUsers) {
		foreach ($viewedResponseUsers as $key => $value) {
			$userIdCourseIdMap[$value['userId']] = $value['listing_type_id'];
			$userDetails[$value['userId']]['name'] = $value['displayName'];
			$userDetails[$value['userId']]['email'] = $value['email'];
			$userDetails[$value['userId']]['mobile'] = $value['mobile'];
			$courseIds[] = $value['listing_type_id'];
		}
		
		if(empty($courseIds)) {
			return;
		}
		//$courseIds = array(1653, 111549, 111548, 290502, 109575);
		//$courseIds = array(1653);
		//$userIdCourseIdMap = array(1059=>1653, 1060=>111549, 1061=>111548, 1062=>290502, 1067=>109575);
		//$userIdCourseIdMap = array(1059=>1653);
		//$userDetails[1059]['name'] = 'Nikita';
		//$userDetails[1059]['email'] = 'cmsadmin@shiksha.com';
		
		//load objects
		$this->CI->load->builder("nationalCourse/CourseBuilder");
		$builder = new CourseBuilder();
        $this->courseRepository = $builder->getCourseRepository();

        $this->CI->load->builder("nationalInstitute/InstituteBuilder");
    	$instituteBuilder = new InstituteBuilder();
        $this->instituteRepository = $instituteBuilder->getInstituteRepository();

        $courseObjects = $this->courseRepository->findMultiple($courseIds, array('eligibility','placements_internships'));
        
        foreach ($courseObjects as $key => $courseObj) {
        	if(is_object($courseObj)) {
        		$insttId = $courseObj->getInstituteId();
        		if(!empty($insttId)) {
        			$courseInstituteIds[] = $courseObj->getInstituteId();
        		}
        	}
        }

        $instituteObjects = $this->instituteRepository->findMultiple($courseInstituteIds, array('media'));

        global $COURSE_MESSAGE_KEY_MAPPING, $MESSAGE_MAPPING;
        foreach ($userIdCourseIdMap as $userId => $courseId) {
        	$courseObj = ''; $instituteObj = '';
        	$courseObj = $courseObjects[$courseId];
        	if(is_object($courseObj)) {
        		$instituteId = $courseObj->getInstituteId();
				$instituteObj = $instituteObjects[$instituteId];
			}
			
			if(is_object($instituteObj) && is_object($courseObj)) {

				$userData = array();
				$userData['listing_id'] = $courseId;
				$userData['user_id'] = $userId;
				$userData['mobile'] = $userDetails[$userId]['mobile'];

				$this->sendResponseSMSToUser($userData);
				unset($userData);

				$data = array();
				$data['interestHeaderText'] = $courseObj->getName().' in '.$instituteObj->getName();
				$data['userId'] = $userId;
				$data['courseObj'] = $courseObj;
				$data['instituteObj'] = $instituteObj;
				$data['isAbroadRecommendation'] = false;
				$data['leanHeaderFooterV2'] = 1;
				$data['listing_type_id'] = $courseId;
				$data['listing_type'] = 'course';
				$data['mailer_name'] = 'ViewedResponseMailer';
				$data['userDetails'] = $userDetails[$userId];
				$data['additionalMessage'] = $MESSAGE_MAPPING[$COURSE_MESSAGE_KEY_MAPPING[$courseObj->getId()]]['email'];
				
				$mailerHtml = Modules::run('systemMailer/SystemMailer/sendViewedResponseMail', $data);
			}
        }
    }

	/* API to send Response SMS to User
	 * @Params: $userData array
	 */
	function sendResponseSMSToUser($userData) {

		global $COURSE_MESSAGE_KEY_MAPPING, $MESSAGE_MAPPING;

		$clientName = $COURSE_MESSAGE_KEY_MAPPING[$userData['listing_id']];
		$smsContent = $MESSAGE_MAPPING[$clientName]['SMS'];
		
		if((isset($clientName)) && (!empty($clientName)) && (!empty($smsContent))) {

			$data = array();
			$data['mobileNumber'] = $userData['mobile'];
			$data['smsContent'] = $smsContent;
			$data['userId'] = $userData['user_id'];
	 		$this->sendResponseSMS($data);
	 		unset($data);

 		}

	}

	/* API to send Response SMS
	 * @Params: $smsData array
	 */
	function sendResponseSMS($data) {

		$this->CI->load->library('alerts_client');
        $alertClient = new Alerts_client();
		$alertClient->addSmsQueueRecord("12",$data['mobileNumber'],$data['smsContent'],$data['userId'], '', 'system');

	}

	public function loadResponseDeliveryCriteria(){
		
		$this->CI->load->model('response/responsemodel');
		$this->responseModel = new ResponseModel();

		$responseDeliveryCriteria = $this->responseModel->getAllResponseDeliveryCriteria();		

		foreach ($responseDeliveryCriteria as  $value) {
			
			if($value['entity_type'] == 'university'){
				$value['entity_type'] = 'institute';
			}

			$mapKey = $value['entity_id'].'_'.$value['entity_type'].'_'.$value['action_type'];
			
			$responseDeliveryCriteriaMap[$mapKey]['action_value'] = 	$value['action_value'];

			if($value['location_ids'] != ''){
				$rdcLocations = $value['location_ids'];
				$rdcLocations = explode(',', $rdcLocations);

				foreach ($rdcLocations as $rdcLocationId) {
					$responseDeliveryCriteriaMap[$mapKey]['rdcLocationId'][$rdcLocationId] = 1;
				}
			}

		}

		return $responseDeliveryCriteriaMap;
	}

	public function checkIsClientResponse($queuedData){
		$this->CI->load->config('response/responseConfig');
		$action_type_bucket = $this->CI->config->item('viewedActionBucket');	

		$rdc_action_type = $action_type_bucket[$queuedData['action_type']];

		if($rdc_action_type == '' || empty($rdc_action_type)){
			$rdc_action_type = 'NVR';
		}
		
		$rdc_key = $queuedData['listing_id'].'_'.$queuedData['listing_type'].'_'.$rdc_action_type;

		if($rdc_action_type == 'IVR'){
			$rdc_key = $queuedData['institute_id'].'_institute_'.$rdc_action_type;			
		}

		$rdc_data = $queuedData['rdc'][$rdc_key];

		if($rdc_data && !empty($rdc_data)){

			if($rdc_data['action_value']){
				if(!$rdc_data['rdcLocationId'][$queuedData['city']]){
					$queuedData['isClientResponse'] = 'No';	//mark isClientResponse false if criteria is present but not 													matchiing with city criteria
				}
			}else{
				$queuedData['isClientResponse'] = 'No';		//mark isClientResponse false if criteria is present for exclusion
			}
		}

		return $queuedData['isClientResponse'];
	}
} ?>

