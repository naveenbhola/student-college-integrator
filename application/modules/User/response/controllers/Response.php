<?php

/**
 * Class Response Responsible for various purposes like:
 *   1) Creating Response By Post/Parameters
 *	 2) Sending Response Mail like Download Brochure/Placement/Internship
 *   3) Creating Response By Cron
 *
 * @author Shiksha LDB Team
 */

class Response extends MX_Controller 
{
	/**
	* This function load all necessary libraries, model related to response which will be used in various flows 
	*/
	function _init(){
		$this->load->library(array('responseLib', 'mailer/ProductMailerEventTriggers', 'alerts_client', 'register_client'));

		$this->load->model('response/responsemodel');
		$this->responseModel = new ResponseModel();

		$this->load->builder("nationalCourse/CourseBuilder");
		$builder = new CourseBuilder();
        $this->courseRepository = $builder->getCourseRepository();

        $this->load->builder("examPages/ExamBuilder");
		$builder = new ExamBuilder();
        $this->examRepository   = $builder->getExamRepository();

        $this->load->model('user/usermodel');
		$this->usermodel = new Usermodel();

		// load the builder
	    $this->load->builder("nationalInstitute/InstituteBuilder");
	    $instituteBuilder = new InstituteBuilder();

	    // get institute repository with all dependencies loaded
	    $this->instituteRepo = $instituteBuilder->getInstituteRepository();

	    $this->course_model = $this->load->model('nationalCourse/nationalcoursemodel');      

	    $this->load->helper(array('image'));
	}

	/**
	* This Function is used for creating response directly from Front End Response Form
	*/
	public function createResponse() {

		$listing_id 	= $this->input->post('listing_id');
		
		// XSS prevention

		if (!is_numeric($listing_id))
		{
			// Invalid ListingID
			return;
		}		
		$tracking_keyid = trim($this->input->post('tracking_keyid'));
		$action_type 	= $this->input->post('action_type');
		$listing_type   = $this->input->post('listing_type');
		
		$defaultData = array();
		//if(empty($tracking_keyid) || empty($action_type) || empty($listing_id)) {
		if(empty($action_type) || empty($listing_id)) {
			$defaultData['listing_id'] 	   = $listing_id;
			$defaultData['tracking_keyid'] = $tracking_keyid;
			$defaultData['action_type']    = $action_type;
			$defaultData['listing_type']   = $listing_type;
			$defaultData['postData']       = $_POST;
		}

		if(empty($tracking_keyid)) {
			if($listing_type == 'course'){
				if (isMobileRequest()) {
					$tracking_keyid = DEFAULT_TRACKING_KEY_MOBILE;
				} else {
					$tracking_keyid = DEFAULT_TRACKING_KEY_DESKTOP;
				}
			}
			if($listing_type == 'exam'){
				if (isMobileRequest()) {
					$tracking_keyid = DEFAULT_TRACKING_KEY_EXAM_MOBILE;
				} else {
					$tracking_keyid = DEFAULT_TRACKING_KEY_EXAM_DESKTOP;
				}
			}
			$_POST['tracking_keyid'] = $tracking_keyid;
		}

		if(empty($action_type)) {
			$action_type          = 'other';
			$_POST['action_type'] = $action_type;
		}

		if((!empty($_POST)) && ($listing_id > 0)) {

			try {

				$this->_init();

				$queuedData = $this->savePostDataToQueue(); // Save in Response Queue

				$queuedData['action_type'] = $action_type;
				
				if(($queuedData['user_id'] > 0) && (!empty($queuedData['email_id'])) && ($queuedData['listing_id'] > 0) && ($queuedData['tracking_key'] > 0)) {

					if($queuedData['listing_type'] == 'course'){

						$this->createCourseResponse($queuedData);

					}

					if($queuedData['listing_type'] == 'exam'){

						$this->createExamResponse($queuedData);

					}

				} else {

					$result = array('userId'=> $user_id, 'listingId'=> $listing_id, 'status' => 'FAIL-DATA-INCOMPLETE');
					$jsonResult = json_encode($result);
					echo $jsonResult;

				}


			} catch(Exception $e) {

				mail('naveen.bhola@shiksha.com','Exception in Response Creation By Post at '.date('Y-m-d H:i:s'), 'From page: '.$_SERVER['HTTP_REFERER'].'<br/>'.print_r($queuedData, true).$e->getMessage());

				$result = array('userId'=> $user_id, 'listingId'=> $listing_id, 'status' => 'FAIL-EXCEPTION-OCCURRED');
				$jsonResult = json_encode($result);
				echo $jsonResult;

			}	

		} else {

			$result = array('userId'=> $user_id, 'listingId'=> $listing_id, 'status' => 'FAIL-DATA-INCOMPLETE');
			$jsonResult = json_encode($result);
			echo $jsonResult;

		}		

		if(!empty($defaultData)) {

			$defaultData['HTTP_REFERER']    = $_SERVER['HTTP_REFERER'];
			$defaultData['SCRIPT_URI']      = $_SERVER['SCRIPT_URI'];
			$defaultData['HTTP_USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
			$defaultData['user_id']         = $queuedData['user_id'];
			$defaultData['email_id']        = $queuedData['email_id'];
			$defaultData['defaultData']     = 'yes';
			mail('naveen.bhola@shiksha.com','Data Incomplete in createResponse API at '.date('Y-m-d H:i:s'), '<br/>Some data missing <br/>'.print_r($defaultData, true));

		}

	}

	private function createCourseResponse($queuedData = array()){

		$courseEntityArray = array('basic', 'location', 'course_type_information','eligibility');

		global $placementBrochureTrackingKeys, $internshipBrochureTrackingKeys;
		if((in_array($queuedData['tracking_key'], $placementBrochureTrackingKeys)) || (in_array($queuedData['tracking_key'], $internshipBrochureTrackingKeys))) {
			$courseEntityArray[] = 'placements_internships';
		}

		$courseObj = $this->courseRepository->find($queuedData['listing_id'], $courseEntityArray);

		$isViewedResponse = 'no';
		if($queuedData['isViewedResponse'] == 'yes') {
			$isViewedResponse = $queuedData['isViewedResponse'];
		}

		if(($queuedData['action_type'] == 'Institute_Viewed' || $queuedData['action_type'] == "MOB_Institute_Viewed" || $queuedData['action_type'] == "Viewed_Listing" || $queuedData['action_type'] == "mobile_viewedListing") && $isViewedResponse == 'no') {
			mail('naveen.bhola@shiksha.com','Viewed Data via Post at '.date('Y-m-d H:i:s'), 'From page: '.$_SERVER['HTTP_REFERER'].'<br/>'.print_r($queuedData, true).'<br/>'.print_r($_POST, true));
		}

		$levelData = $this->updateUserStatusByLevelChange($courseObj, $queuedData['user_id'], $queuedData['isdCode'], $isViewedResponse); // Update Status & education level

		$courseLevel   = $levelData['courseLevel'];
		$isLevelChange = $levelData['isLevelChange'];

		$isViewedLevelChange = 'no';
		if($isViewedResponse  == 'yes' && $isLevelChange == 'yes') {
			$isViewedLevelChange = 'yes';
		}
		
		$is_empty_profile = 0;
		$is_empty_profile = $this->savePostDataToResponseProfile($queuedData, $courseLevel, $courseObj, $isViewedLevelChange); // save in Response Profile

		if($is_empty_profile > 0) {
			mail('naveen.bhola@shiksha.com','User Profile Empty via PostAPI at '.date('Y-m-d H:i:s'), 'From page: '.$_SERVER['HTTP_REFERER'].'<br/>'.print_r($queuedData, true).'<br/>'.print_r($_POST, true));
		}

		if(RESPONSE_CREATION_METHOD == "direct" && (!empty($queuedData))){

			$this->createResponseFromTempData($queuedData, $courseObj);

			$this->responseModel->updateTempResponseQueue($queuedData['id']);

			$this->sendResponseMailFromData($queuedData, $courseObj);

			$this->responseModel->updateTempResponseQueue($queuedData['id'], 'Y');
		
		}

		$isPaid = false;

		if(!empty($courseObj)){
			$isPaid = $courseObj->isPaid();
		}

		$result = array('userId'=> $queuedData['user_id'], 'listingId'=> $queuedData['listing_id'], 'status' => 'SUCCESS', 'isPaid' => $isPaid);
		$jsonResult = json_encode($result);
		echo $jsonResult;

	}

	private function createExamResponse($queuedData = array()){

		$isViewedResponse = 'no';
		if($queuedData['isViewedResponse'] == 'yes') {
			$isViewedResponse = $queuedData['isViewedResponse'];
		}

		$levelData = $this->updateUserStatusForExamResponse($queuedData['level'], $queuedData['user_id'], $queuedData['isdCode'], $queuedData['listing_id'], $queuedData['city'], $isViewedResponse); // Update status & education level

		$groupLevel    = $levelData['groupLevel'];
		$isLevelChange = $levelData['isLevelChange'];
		
		$isViewedLevelChange = 'no';
		if($isViewedResponse == 'yes' && $isLevelChange == 'yes') {
			$isViewedLevelChange = 'yes';
		}
		
		$is_empty_profile = 0;
		$is_empty_profile = $this->savePostDataToResponseProfile($queuedData, $groupLevel, array(), $isViewedLevelChange); // save in Response Profile

		if($is_empty_profile > 0) {
			mail('naveen.bhola@shiksha.com','User Profile Empty via PostAPI at '.date('Y-m-d H:i:s'), 'From page: '.$_SERVER['HTTP_REFERER'].'<br/>'.print_r($queuedData, true).'<br/>'.print_r($_POST, true));
		}

		if(RESPONSE_CREATION_METHOD == "direct" && !empty($queuedData)){
			$this->createExamResponseFromTempData($queuedData);
			$this->responseModel->updateTempResponseQueue($queuedData['id']);
			 // send mail for Exam Response
            $this->sendMailForExamResponse($queuedData);
			$this->responseModel->updateTempResponseQueue($queuedData['id'], 'Y');

		}else if(RESPONSE_CREATION_METHOD == "queue"){
			$this->_addResponseDataToMessageQueue($queuedData['id']);
		}

		$result = array('userId'=> $queuedData['user_id'], 'listingId'=> $queuedData['listing_id'], 'status' => 'SUCCESS');
		$jsonResult = json_encode($result);
		echo $jsonResult;
	}

	private function _addResponseDataToMessageQueue($tempResQueueId){
		if(empty($tempResQueueId) || $tempResQueueId<=0){
			return false;
		}

		$this->load->library("common/jobserver/JobManagerFactory");
        try {
            $jobManager = JobManagerFactory::getClientInstance();
            if ($jobManager) {
            	$dataForMsgQueue = array();
            	$dataForMsgQueue['tempResQueueId'] = $tempResQueueId;
                $jobManager->addBackgroundJob("createNationalResponse", $dataForMsgQueue);
            }
        }catch (Exception $e) {
            //error_log("Unable to connect to rabbit-MQ");
            mail('teamldb@shiksha.com','Unable to add messages in createNationalResponse Queue '.date('Y-m-d H:i:s'), 'Response Queue Id : '.$tempResQueueId);
        }
	}

	/**
	* This Function is used for saving Post Data sent from Response Form in temp_Response_queue table
	*/
	private function savePostDataToQueue() {

		$this->load->config('response/responseConfig');
		$excludeTrackingIdsForMailer = $this->config->item('excludeTrackingIdsForMailer');
		$tracking_keyid = trim($this->input->post('tracking_keyid'));

		$responseTempData 				  	  = $this->getDataToAddToTempQueue();
		$responseTempData['is_mail_sent']     = 'no';

		if(in_array($tracking_keyid,$excludeTrackingIdsForMailer)){
			$responseTempData['is_mail_sent']     = 'yes';
		}

		$responseTempData['is_response_made'] = 'no';
		$responseTempData['id']               = $this->responseModel->storeTempResponseData($responseTempData);
		$responseTempData['stream_id'] 		  = $this->input->post('stream');
		$responseTempData['isViewedResponse'] = $this->input->post('isViewedResponse');

		if($responseTempData['listing_type'] == 'course'){
			$eData['action']   = 'course_new_response';			
		}else if($responseTempData['listing_type'] == 'exam'){
			$eData['action']   = 'exam_new_response';			
		}
		$extraData         = json_encode($eData);
		$this->indexResponseToElastic($extraData,$responseTempData['user_id'],$responseTempData['id']);
		
		return $responseTempData;
	}

	/**
	* This Function is used for saving Post Data sent from Response Form in user_response_profile table
	*/
	public function savePostDataToResponseProfile($queuedData, $courseLevel, $courseObj, $isViewedLevelChange) {
		
		$profileData = array();
		$profileData['stream_id'] 			 = $this->input->post('stream');
		$profileData['substream_id'] 		 = $this->input->post('subStream');
		$profileData['baseCourses'] 		 = $this->input->post('baseCourses');
		$profileData['subStreamSpecMapping'] = $this->input->post('subStreamSpecMapping');
		$profileData['mode'] 				 = $this->input->post('educationType');
		$profileData['level'] 				 = $this->input->post('level');
		$profileData['credential'] 			 = $this->input->post('credential');
		$profileData['action_type'] 		 = $queuedData['action_type'];

		$responseDataProfile = array();
		
		if($queuedData['listing_type'] == 'exam'){
			$responseDataProfile = $this->getExamResponseProfile($queuedData['listing_id'], $profileData, $isViewedLevelChange);
		} else {
			$responseDataProfile = $this->getResponseProfile($courseObj, $profileData, $isViewedLevelChange);
		}

		$responseTempData = array_merge($queuedData,$responseDataProfile);			

		$responseTempData['education_level'] = $courseLevel;

		if((empty($responseDataProfile['stream_id'])) || (empty($responseDataProfile['baseCourses'])) || (empty($responseDataProfile['mode']))) {
			mail('teamldb@shiksha.com, shikshaqa@Infoedge.com','Data missing while creating response at '.date('Y-m-d H:i:s'), 'From page: '.$_SERVER['HTTP_REFERER'].'<br/> response profiledata below '.print_r($responseDataProfile, true)."<br> post data below ".print_r($_POST, true)."<br> queue data below ".print_r($queuedData, true));
			return;
		}

		/*for sending stream digest mail for shortlist, brochure and compare, compare coming as viewed response so extra check*/
		if($queuedData['isViewedResponse'] != 'yes' || $queuedData['action_type'] == 'COMPARE_VIEWED' || $queuedData['action_type'] == 'MOB_COMPARE_VIEWED'){
			$userLib = $this->load->library('user/UserLib');
			$userLib->sendStreamDigestMailForUser($responseTempData['user_id'],$responseTempData['stream_id']);
		}

		$is_empty_profile = $this->responseModel->storeUserResponseProfile($responseTempData);

		// mapping e-response data to profile in queue
		if($queuedData['listing_type'] == 'exam' && $queuedData['action_type'] != 'exam_viewed_response'){
			$this->processEResponseProfileData(array('user_id'=>$responseTempData['user_id']));
		}

		return $is_empty_profile;

	}

	/**
	* This Function is used creating response by passing parameters directly
	*/
	public function createResponseByParams ($data) {

		$defaultData = array();

		if(empty($data['tracking_keyid']) || empty($data['action_type']) || empty($data['listing_id'])) {
			$defaultData['listing_id'] = $data['listing_id'];
			$defaultData['tracking_keyid'] = $data['tracking_keyid'];
			$defaultData['action_type'] = $data['action_type'];
		}

		if(empty($data['tracking_keyid'])) {
			if (isMobileRequest()) {
				$tracking_keyid = DEFAULT_TRACKING_KEY_MOBILE;
			} else {
				$tracking_keyid = DEFAULT_TRACKING_KEY_DESKTOP;
			}
			$data['tracking_keyid'] = $tracking_keyid;			
		}

		if(empty($data['action_type'])) {
			$action_type = 'other';
			$data['action_type'] = $action_type;
		}

		if((!empty($data)) && ($data['listing_id'] > 0)) {

			try{

				$this->_init();
				
				$queuedData = $this->saveParamsDataToQueue($data); // Save in Respone Queue

				if(($queuedData['user_id'] > 0) && (!empty($queuedData['email_id'])) && ($queuedData['listing_id'] > 0) && ($queuedData['listing_type'] == 'course')) {

					$courseObj = $this->courseRepository->find($queuedData['listing_id'], array('basic', 'location', 'course_type_information'));

					$isViewedResponse = 'no';
					if($queuedData['isViewedResponse'] == 'yes') {
						$isViewedResponse = $queuedData['isViewedResponse'];
					}

					if(($queuedData['action_type'] == 'Institute_Viewed' || $queuedData['action_type'] == "MOB_Institute_Viewed" || $queuedData['action_type'] == "Viewed_Listing" || $queuedData['action_type'] == "mobile_viewedListing") && $isViewedResponse == 'no') {
						mail('naveen.bhola@shiksha.com','Viewed Data via Params at '.date('Y-m-d H:i:s'), 'From page: '.$_SERVER['HTTP_REFERER'].'<br/>'.print_r($queuedData, true).'<br/>'.print_r($data, true));
					}

					$levelData = $this->updateUserStatusByLevelChange($courseObj, $queuedData['user_id'], $queuedData['isdCode'], $isViewedResponse); // Update Status & education level

					$courseLevel = $levelData['courseLevel'];
					$isLevelChange = $levelData['isLevelChange'];

					$isViewedLevelChange = 'no';
					if($isViewedResponse == 'yes' && $isLevelChange == 'yes') {
						$isViewedLevelChange = 'yes';
					}

					$is_empty_profile = $this->saveParamsDataToResponseProfile($queuedData, $courseLevel, $courseObj, $isViewedLevelChange); // save in Response Profile

					if($is_empty_profile > 0) {
						mail('naveen.bhola@shiksha.com','User Profile Empty via Params at '.date('Y-m-d H:i:s'), 'From page: '.$_SERVER['HTTP_REFERER'].'<br/>'.print_r($queuedData, true).'<br/>'.print_r($data, true));
					}

					echo 'Response Data saved in Queue Tables for user id = '.$queuedData['user_id'].', listing id = '.$queuedData['listing_id'].'<br/>';

					if(RESPONSE_CREATION_METHOD == "direct" && (!empty($queuedData))){

						$this->createResponseFromTempData($queuedData);

						$this->responseModel->updateTempResponseQueue($queuedData['id']);

						$this->sendResponseMailFromData($queuedData);

						$this->responseModel->updateTempResponseQueue($queuedData['id'], 'Y');

						echo '<br/>Response Created<br/>';
					
					}

				} else {

					echo 'Data Incomplete';

				}


			} catch(Exception $e) {

				mail('naveen.bhola@shiksha.com','Exception in Response Creation By Params at '.date('Y-m-d H:i:s'), 'From page: '.$_SERVER['HTTP_REFERER'].'<br/>'.print_r($queuedData, true).$e->getMessage());

			}			

		} else {

			echo 'Data Incomplete. <br/> listing_id: '.$listing_id.'<br/>tracking_keyid: '.$tracking_keyid.'<br/>action_type: '.$action_type;

		}

		if(!empty($defaultData)) {

			$defaultData['HTTP_REFERER'] = $_SERVER['HTTP_REFERER'];
			$defaultData['SCRIPT_URI'] = $_SERVER['SCRIPT_URI'];
			$defaultData['user_id'] = $queuedData['user_id'];
			$defaultData['email_id'] = $queuedData['email_id'];
			$defaultData['defaultData'] = 'yes';
			mail('naveen.bhola@shiksha.com','Data Incomplete in createResponseByParams API at '.date('Y-m-d H:i:s'), '<br/>Some data missing <br/>'.print_r($defaultData, true));

		}
	
	}

	/**
	* This Function is used for saving data which is sent by createResponseByParams API in temp_response_queue table
	*/
	private function saveParamsDataToQueue($data) {

		$userBasicData = $this->getUserBasicData($data['user_id'], $data['email_id']);

		if(empty($userBasicData)) {
			return;
		}

		$finalData = array();
		$finalData = array_merge($data,$userBasicData);

		$finalData['is_mail_sent'] = 'no';
		$finalData['is_response_made'] = 'no';
		
		if(empty($finalData['visitor_session_id'])) {
			$finalData['visitor_session_id'] = getVisitorSessionId();
		}
		if(!empty($data['submit_date'])){
			$finalData['submit_time'] = $data['submit_date'];
		}else{
			$finalData['submit_time'] = date("Y-m-d H:i:s");
		}

		if(!empty($data['is_response_made']) && $data['is_response_made'] == 'yes'){
			$finalData['is_response_made'] = $data['is_response_made'];
		}

		if(!empty($data['is_mail_sent']) && $data['is_mail_sent'] == 'yes'){
			$finalData['is_mail_sent'] = $data['is_mail_sent'];
		}

		$finalData['listing_type'] = 'course';
		$finalData['tracking_key'] = $finalData['tracking_keyid'];
		unset($finalData['tracking_keyid']);

		$finalData['id'] = $this->responseModel->storeTempResponseData($finalData);

		return $finalData;
	}

	/**
	* This Function is used for saving data which is sent by createResponseByParams API in user_response_profile table
	*/
	private function saveParamsDataToResponseProfile($profileData, $courseLevel, $courseObj, $isViewedLevelChange) {

		$allProfileData = array();
		$allProfileData = $this->getResponseProfile($courseObj, $profileData, $isViewedLevelChange);
		
		$profileData['stream_id'] 			 = $allProfileData['stream_id'];
		$profileData['substream_id'] 		 = $allProfileData['substream_id'];
		$profileData['baseCourses'] 		 = $allProfileData['baseCourses'];
		$profileData['subStreamSpecMapping'] = $allProfileData['subStreamSpecMapping'];
		$profileData['mode'] 				 = $allProfileData['mode'];
		$profileData['user_profile'] 		 = $allProfileData['user_profile'];
		$profileData['status'] 				 = $allProfileData['status'];
		$profileData['education_level'] 	 = $courseLevel;

		if ( empty($profileData['stream_id']) || empty($profileData['mode']) || empty($profileData['baseCourses']) ){
			mail('teamldb@shiksha.com, shikshaqa@Infoedge.com','Data missing saveParamsDataToResponseProfile in response file at '.date('Y-m-d H:i:s'), '<br/>  profile data below '.print_r($profiledata, true));
			return;
		}
		$is_empty_profile = $this->responseModel->storeUserResponseProfile($profileData);

		return $is_empty_profile;

	}

	/**
	* This Function is used for updating user status in tuserPref and user_response_profile table, if education level is changing
	*/
	private function updateUserStatusByLevelChange($courseObj, $user_id, $isdCode, $isViewedResponse = 'no') {

		if($courseObj == '') {
			return;
		}

		if(empty($isViewedResponse)) {
			$isViewedResponse = 'no';
		}

		$partialIndexFlag = false;

		$courseLevel = '';$courseLevelDetail = array();$userLevel = '';$extraFlag = ''; $isLevelChange = 'no';
		$finalData = array();
		$courseTypeInfo = $courseObj->getCourseTypeInformation();

		if(!empty($courseTypeInfo)) {
       		$courseLevelId = $courseTypeInfo['entry_course']->getCourseLevel()->getId(); 
       		
       		if(!empty($courseLevelId)) {

				$this->load->library('listingBase/BaseAttributeLibrary');
				$BaseAttributeLibrary = new \BaseAttributeLibrary(); 
				$courseLevelDetail = $BaseAttributeLibrary->getValueNameByValueId($courseLevelId);
				$courseLevel = $courseLevelDetail[$courseLevelId];

       			$userDetails = $this->usermodel->getUserEducationLevel($user_id);

       			$userLevel = $userDetails['educationLevel'];
       			$extraFlag = $userDetails['ExtraFlag'];

       			if($extraFlag == 'studyabroad' || $extraFlag == 'testprep') {

       				if($isViewedResponse == 'no') {
						$updatedData = array('ExtraFlag = NULL','DesiredCourse = NULL','abroad_subcat_id = NULL','SubmitDate = now()','is_processed="no"');
					}

					if((empty($userLevel)) && (!empty($courseLevel))) {
	       				$updatedData[] = 'educationLevel = "'.$courseLevel.'"';	       				
	       			}

	       			if(!empty($updatedData)) {
	       				$this->usermodel->updateUserPrefData($user_id, $updatedData);
	       			}

	       			if((!empty($courseLevel)) && (!empty($userLevel)) && ($userLevel != 'None') && ($courseLevel != 'None') && ($courseLevel != $userLevel) && ($isViewedResponse == 'yes')) {	       				
						$isLevelChange = 'yes';
					}

       			} else {
       				
	       			if((empty($userLevel)) && (!empty($courseLevel))) {

	       				$updatedData = array('educationLevel = "'.$courseLevel.'"');
	       				$this->usermodel->updateUserPrefData($user_id, $updatedData);

	       			} else if((!empty($courseLevel)) && (!empty($userLevel)) && ($courseLevel != 'None') && ($courseLevel != $userLevel) && ($isViewedResponse == 'no')) {
						
						$updatedData = array('educationLevel = "'.$courseLevel.'"','SubmitDate = now()','is_processed="no"');
						$this->usermodel->updateUserPrefData($user_id, $updatedData);

	       			}

	       			if((!empty($courseLevel)) && (!empty($userLevel)) && ($userLevel != 'None') && ($courseLevel != 'None') && ($courseLevel != $userLevel)) {
	       				
	       				if($isViewedResponse == 'no') {
							$this->usermodel->_deleteOldUserPrefs($user_id, 'draft');
						}
						$isLevelChange = 'yes';

					}

					if($isViewedResponse == 'no') {
		       			$responseProfileLevel = '';
						$responseProfileLevel = $this->responseModel->getEducationLevelOfUserResponseProfile($user_id);

						if((!empty($responseProfileLevel)) && (!empty($courseLevel)) && ($responseProfileLevel != 'None') && ($courseLevel != 'None') && ($responseProfileLevel != $courseLevel)) {
						
							$this->responseModel->updateResponseProfileStatusByUserId($user_id);

						}
					}
       			}
       		}
		}

		$email = $this->input->post('email');
		if(empty($email) && $isdCode == '91'){
			if(!$courseObj->isPaid()){
				$this->responseModel->updateUserFlag($user_id);
				$partialIndexFlag = true;
			}
		}

		if($partialIndexFlag){
			$user_response_lib = $this->load->library('response/userResponseIndexingLib');
			$extraData         = "{'personalInfo:true'}";
			$user_response_lib->insertInIndexingQueue($user_id, $extraData);
		}

		$finalData['courseLevel']   = $courseLevel;
		$finalData['isLevelChange'] = $isLevelChange;
		return $finalData;
	}

	/**
	* This Function is used in case of Exam Response for updating user status in tuserPref and user_response_profile table, if education level is changing
	*/
	private function updateUserStatusForExamResponse($groupLevelId, $user_id, $isdCode, $examGroupId, $user_city, $isViewedResponse = 'no') {
		
		if(empty($groupLevelId)) {
			$examResponseData = array();
			$requiredList     = array();
	        $requiredList     = array('primary_hierarchy', 'baseCourse', 'level');
	        $examResponseData = modules::run('registration/RegistrationForms/getExamResponseData', $examGroupId, $requiredList);
	        $key              = array_keys($examResponseData['level']);
			$groupLevelId     = $key[0];
		}

		if(empty($isViewedResponse)) {
			$isViewedResponse = 'no';
		}

		$partialIndexFlag = false;

		$groupLevel       = '';
		$groupLevelDetail = array();
		$userLevel        = '';
		$extraFlag 		  = '';
		$isLevelChange    = 'no';
		$finalData 		  = array();
		
   		if(!empty($groupLevelId)) {

			$this->load->library('listingBase/BaseAttributeLibrary');
			$BaseAttributeLibrary = new \BaseAttributeLibrary(); 
			$groupLevelDetail = $BaseAttributeLibrary->getValueNameByValueId($groupLevelId);
			
			$groupLevel = $groupLevelDetail[$groupLevelId];

   			$userDetails = $this->usermodel->getUserEducationLevel($user_id);

   			$userLevel = $userDetails['educationLevel'];
   			$extraFlag = $userDetails['ExtraFlag'];

   			if($extraFlag == 'studyabroad' || $extraFlag == 'testprep') {

   				if($isViewedResponse == 'no') {
   					$updatedData = array('ExtraFlag = NULL','DesiredCourse = NULL','abroad_subcat_id = NULL','SubmitDate = now()','is_processed="no"');
   				}
				
				if((empty($userLevel)) && (!empty($groupLevel))) {
       				$updatedData[] = 'educationLevel = "'.$groupLevel.'"';	       				
       			}

       			if(!empty($updatedData)) {
       				$this->usermodel->updateUserPrefData($user_id, $updatedData);
       			}

       			if((!empty($groupLevel)) && (!empty($userLevel)) && ($userLevel != 'None') && ($groupLevel != 'None') && ($groupLevel != $userLevel) && ($isViewedResponse == 'yes')) {	       				
					$isLevelChange = 'yes';
				}

   			} else {
   				
       			if((empty($userLevel)) && (!empty($groupLevel))) {

       				$updatedData = array('educationLevel = "'.$groupLevel.'"');
       				$this->usermodel->updateUserPrefData($user_id, $updatedData);

       			} else if((!empty($groupLevel)) && (!empty($userLevel)) && ($groupLevel != 'None') && ($groupLevel != $userLevel) && ($isViewedResponse == 'no')) {
					
					$updatedData = array('educationLevel = "'.$groupLevel.'"','SubmitDate = now()','is_processed="no"');
					$this->usermodel->updateUserPrefData($user_id, $updatedData);

       			}

       			if((!empty($groupLevel)) && (!empty($userLevel)) && ($userLevel != 'None') && ($groupLevel != 'None') && ($groupLevel != $userLevel)) {
       				
       				if($isViewedResponse == 'no') {
						$this->usermodel->_deleteOldUserPrefs($user_id, 'draft');
					}
					$isLevelChange = 'yes';

				}

				if($isViewedResponse == 'no') {
	       			$responseProfileLevel = '';
					$responseProfileLevel = $this->responseModel->getEducationLevelOfUserResponseProfile($user_id);

					if((!empty($responseProfileLevel)) && (!empty($groupLevel)) && ($responseProfileLevel != 'None') && ($groupLevel != 'None') && ($responseProfileLevel != $groupLevel)) {
						
						$this->responseModel->updateResponseProfileStatusByUserId($user_id);

					}
				}
   			}
   		}
		
   		// for direct response flow, in case of logged-in user, updating the user flag on the basis of paid flag, ie. if any subscription exists for a group-city combination or not
		$email = $this->input->post('email');
		if(empty($email) && $isdCode == '91'){

			$this->load->library('response/responseAllocationLib');
			$responseLib = new responseAllocationLib();
			$isPaid 	 = $responseLib->getMatchedSubscriptions($examGroupId, array($user_city), true);
			if(!$isPaid){
				$this->responseModel->updateUserFlag($user_id);
				$partialIndexFlag = true;
			}

		}

		if($partialIndexFlag){
			$user_response_lib = $this->load->library('response/userResponseIndexingLib');
			$extraData         = "{'personalInfo:true'}";
			$user_response_lib->insertInIndexingQueue($user_id, $extraData);
		}

		$finalData['groupLevel']    = $groupLevel;
		$finalData['isLevelChange'] = $isLevelChange;
		return $finalData;
	}

	/**
	 * Function to insert data into temp_response_queue table by getting data from POST
	 * @param: NULL (post)
	 * @return: data array containing user data for temp insertion
	*/
	private function getDataToAddToTempQueue() {

		$data = array();

		$email_id = $this->input->post('email_id');	

		$user_id = 0;
		$user_id_post = $this->input->post('user_id');				
		if($user_id_post>0){
			$user_id = $user_id_post;
		}

		$data 	  = $this->getUserBasicData($user_id,$email_id);

		$data['visitor_session_id'] = getVisitorSessionId();

		if($user_id_post>0){
			$data['visitor_session_id'] = $this->input->post('visitor_session_id');
		}

		$data['listing_id'] 	    = $this->input->post('listing_id');
		$data['listing_type'] 		= $this->input->post('listing_type');
		if(empty($data['listing_type'])) {
			$data['listing_type'] = 'course';
		}
		$data['preferred_city_id']     = $this->input->post('prefCity');
		$data['preferred_locality_id'] = $this->input->post('prefLocality');
		$data['tracking_key']          = $this->input->post('tracking_keyid');
		$data['action_type']           = $this->input->post('action_type');
		$data['level']                 = $this->input->post('level');
		$data['residenceCityLocality'] = $this->input->post('residenceCityLocality');
		$data['residenceLocality']     = $this->input->post('residenceLocality');
		$data['submit_time']           = date("Y-m-d H:i:s");
		$is_mail_sent                  = $this->input->post('is_mail_sent');
		if($is_mail_sent != '') {
			$data['is_mail_sent'] = $is_mail_sent;
		}

		return $data;

	}

	/**
	* This Function is used for getting User Basic Information based on its user id or email id or from logged-in state
	*/
	private function getUserBasicData($user_id = '', $email_id = '', $getUserInfoFromCookie = 'Y'){

		$finalData = array();
		
		$userStatus = false;
		if($getUserInfoFromCookie == 'Y') {
			$userStatus = $this->checkUserValidation();
		}

		if($userStatus == 'false' || $userStatus == false){

			if((!empty($user_id)) && ($user_id > 0)) {

				$userIds = array();
				if(!is_array($user_id)){
					$userIds = array($user_id);
				} else {
					$userIds = $user_id;
				}
				$data                  = $this->usermodel->getUsersBasicInfoById($userIds);
				$finalData['user_id']  = $user_id;
				$finalData['email_id'] = $data[$user_id]['email'];

			} elseif (!empty($email_id)) {

				$emailIds              = array("'".$email_id."'");

				$handle = 'read';
				$isFBCall = $this->input->post('isFBCall',true);
				if($isFBCall == 1){
					$handle = 'write';
				}
				$data                  = $this->usermodel->getUsersBasicInfoByEmail($emailIds, $handle);
				$userIds               = array_keys($data);
				$user_id               = $userIds[0];
				$finalData['user_id']  = $user_id;
				$finalData['email_id'] = $email_id;
			}

			if(!empty($data[$user_id])) {

				$finalData['display_name'] = $data[$user_id]['displayname'];
				$finalData['mobile']       = $data[$user_id]['mobile'];
				$finalData['first_name']   = $data[$user_id]['firstname'];
				$finalData['isdCode']      = $data[$user_id]['isdCode'];
				$finalData['city']         = $data[$user_id]['city'];
				
			}
			
		}else{

			$finalData['user_id']      = $userStatus[0]['userid'];
			$finalData['display_name'] = $userStatus[0]['displayname'];
			$finalData['mobile']       = $userStatus[0]['mobile'];
			$finalData['first_name']   = $userStatus[0]['firstname'];
			$cookiestr                 = explode("|", $userStatus[0]['cookiestr']);
			$finalData['email_id']     = $cookiestr[0];
			$finalData['isdCode'] 	   = $userStatus[0]['isdCode'];
			$finalData['city']    	   = $userStatus[0]['city'];

		}

		return $finalData;
	}	

	/**
	* This Function is used for getting data for user_response_profile table from course Object
	*/
	public function getResponseProfile($courseObj, $profileData, $isViewedLevelChange) {

		$stream_id            = $profileData['stream_id'];
		$substream_id         = $profileData['substream_id'];
		$baseCourses          = $profileData['baseCourses'];
		$subStreamSpecMapping = $profileData['subStreamSpecMapping'];
		$mode                 = $profileData['mode'];
		$level                = $profileData['level'];
		$credential           = $profileData['credential'];
		$action_type          = $profileData['action_type'];

		/*Loading dependent files for indirect flows */
		if(empty($this->responseModel)){
			$this->_init();
		}

		if((empty($stream_id)) || (empty($baseCourses)) || (empty($mode)) || (empty($level)) || (empty($credential))) {

			$courseData           = array();
			$courseData           = $this->getClientCourseDataByCourseObj($courseObj);
			
			$stream_id            = $courseData['stream_id'];
			$substream_id         = $courseData['substream_id'];
			$baseCourses          = $courseData['baseCourses'];
			$subStreamSpecMapping = $courseData['subStreamSpecMapping'];
			$mode                 = $courseData['mode'];

			if(is_object($courseData['level'])){
				$level = $courseData['level']->getId();
			} else {
				$level = $courseData['level'];
			}
			if(is_object($courseData['credential'])){
				$credential = $courseData['credential']->getId();
			} else {
				$credential = $courseData['credential'];
			}

		}

		if(!empty($substream_id) && is_array($substream_id)) {
			$newsubstream_id = array();
			foreach($substream_id as $substream) {
				if($substream == 'ungrouped' || $substream > 0) {
					$newsubstream_id[] = $substream;
				}
			}
			unset($substream_id);
			$substream_id = $newsubstream_id;
			unset($newsubstream_id);
		}

		$responseLibObj        = new responseLib();
		$user_profile_response = $responseLibObj->getDataForResponseProfile($stream_id, $substream_id, $baseCourses, $subStreamSpecMapping, $mode, $level, $credential);

		if($action_type == 'Institute_Viewed' || $action_type == "MOB_Institute_Viewed") {
			$status = 'history';
		} else {
			if($isViewedLevelChange == 'yes') {
				$status = 'draft';
			} else {
				$status = 'live';
			}
		}

		$data                         = array();
		$data['stream_id']            = $stream_id;
		$data['substream_id']         = $substream_id;
		$data['baseCourses']          = $baseCourses;
		$data['subStreamSpecMapping'] = $subStreamSpecMapping;
		$data['mode']                 = $mode;
		$data['user_profile']         = $user_profile_response;
		$data['status']               = $status;

		return $data;

	}


	/**
	* This Function is used for getting data for user_response_profile table for Exam response
	*/
	public function getExamResponseProfile($examGroupId, $profileData, $isViewedLevelChange) {

		$stream_id 	  		  = $profileData['stream_id'];
		$substream_id 		  = $profileData['substream_id'];
		$baseCourses  		  = $profileData['baseCourses'];
		$subStreamSpecMapping = $profileData['subStreamSpecMapping'];
		$mode 				  = $profileData['mode'];
		$level 				  = $profileData['level'];
		$action_type 		  = $profileData['action_type'];

		/*Loading dependent files for indirect flows */
		if(empty($this->responseModel)){
			$this->_init();
		}

		if((empty($stream_id)) || (empty($baseCourses)) || (empty($mode)) || (empty($level))) {

			// for logged-in user
			$examGroupData        = array();
			$examGroupData        = $this->getExamGroupDataByGroupId($examGroupId);
			$stream_id            = $examGroupData['stream_id'];
			$substream_id         = $examGroupData['substream_id'];
			$baseCourses          = $examGroupData['baseCourses'];
			$subStreamSpecMapping = $examGroupData['subStreamSpecMapping'];
			$mode                 = $examGroupData['mode'];
			$level                = $examGroupData['level'];
			
		}

		$responseLibObj 	   = new responseLib();
		$user_profile_response = $responseLibObj->getDataForResponseProfile($stream_id, $substream_id, $baseCourses, $subStreamSpecMapping, $mode, $level);

		if($action_type == 'exam_viewed_response') {
			$status = 'history';
		} else {
			if($isViewedLevelChange == 'yes') {
				$status = 'draft';
			} else {
				$status = 'live';
			}
		}

		$data = array();
		$data['stream_id'] 			  = $stream_id;
		$data['substream_id'] 		  = $substream_id;
		$data['baseCourses'] 		  = $baseCourses;
		$data['subStreamSpecMapping'] = $subStreamSpecMapping;
		$data['mode'] 			      = $mode;
		$data['user_profile'] 		  = $user_profile_response;
		$data['status'] 			  = $status;

		return $data;

	}

	/**
	* This Function is used for response creation by Rabbit MQ if response is not creating directly or by cron
	*/
	public function createResponseByQueue(){
		//CREATE_RESPONSE_METHOD
		if(RESPONSE_CREATION_METHOD != "queue") {
			mail('teamldb@shiksha.com','Constant RESPONSE_CREATION_METHOD value is not "queue"','Constant RESPONSE_CREATION_METHOD value is not "queue".<br> '.print_r($this->security->xss_clean($_POST),true));
			echo "success";
			return;
		}

		$this->benchmark->mark('queue_start');

		$tempResQueueId = $this->input->post('tempResQueueId',true);
		if(empty($tempResQueueId) || $tempResQueueId<1){
			error_log("Response Creation By Rabbit MQ. Corrupted Data Received");
			mail('teamldb@shiksha.com','Received Corrupted data in createResponseByQueue function','Received Corrupted data in createResponseByQueue function.<br> '.print_r($this->security->xss_clean($_POST),true));
			echo "success";
			return;
		}


		$this->_init();
		$queuedData = $this->responseModel->getDataFromQueue('response', 'exam', $tempResQueueId);
		
		$response = $this->_processResponseData($queuedData,false);

		$this->benchmark->mark('queue_end');

		$totalTime = $this->benchmark->elapsed_time('queue_start','queue_end');
		
		error_log("Response Creation By Rabbit MQ. Total execution time : ".$totalTime." Sec. For tempResponseQueueId : ".$tempResQueueId);
		echo $response;
		return;
	}


	/**
	* This Function is used for response creation by CRON if response is not creating directly
	*/
	public function createResponseByCron() {
		$this->validateCron();
		if(RESPONSE_CREATION_METHOD == "direct"){
			echo 'Response Creation From Cron Not Allowed';
			return;
		}
		
		$this->_init();
		if(RESPONSE_CREATION_METHOD == "cron"){
			$queuedData = $this->responseModel->getDataFromQueue();
		}else{
			$queuedData = $this->responseModel->getDataFromQueue('response', 'course');
		}
		
		$this->_processResponseData($queuedData);
	}

	private function _processResponseData($queuedData, $isCron = true){
		$this->_init();

		$response = '';
		$responseLibObj        = new responseLib();

		$responseDeliveryCriteria = array();
		$responseDeliveryCriteria = $responseLibObj->loadResponseDeliveryCriteria();


		if(!empty($queuedData)) {
			foreach($queuedData as $data) {
				$userData  = array();
				$userData  = $this->getUserBasicData($data['user_id'],'','N');
				
				$finalData = array();

				// default isClientResponse is yes, to handle exam and abroad responses
				$data['rdc'] = $responseDeliveryCriteria;
				$data['isClientResponse'] = 'Yes';


				$finalData = array_merge($data, $userData);
				if(!empty($finalData)) {
			//		if(($finalData['user_id'] > 0) && (!empty($finalData['email_id'])) && ($finalData['listing_id'] > 0) && ($finalData['listing_type'] == 'course') && ($finalData['tracking_key'] > 0) && ($finalData['id'] > 0)) {
					if(($finalData['user_id'] > 0) && (!empty($finalData['email_id'])) && ($finalData['listing_id'] > 0) &&  ($finalData['tracking_key'] > 0) && ($finalData['id'] > 0)) {
						try {
							if($data['listing_type'] == 'exam'){
								$this->createExamResponseFromTempData($finalData);
							}else if($data['listing_type'] == 'course'){
								$this->createResponseFromTempData($finalData);
							}
							$this->responseModel->updateTempResponseQueue($finalData['id']);
						} catch(Exception $e) {
							mail('naveen.bhola@shiksha.com','Exception in Response Creation By '.(($isCron==true)?"Cron" : "Queue" ).' Exception at '.date('Y-m-d H:i:s'), 'From page: '.$_SERVER['HTTP_REFERER'].'<br/>'.print_r($finalData, true).$e->getMessage());
							if(!$isCron){
								return "fail";
							}
						}											
					}
				}

				$response .= '<br/>Response Created for id = '.$data['id'].'<br>';

				unset($data);
				unset($userData);
				unset($finalData);

			}

			$response .= '<br/>Cron Ends';

		} else {
			$response .= 'Queue Empty';
		}

		if($isCron){
			echo $response;
		}else{
			return "success";	
		}
	}

	/**
	* This Function is used getting data from temp_response_table and insert data in various response related tables
	*/
	private function createResponseFromTempData($queuedData, $courseObj = '') {

		// Get all Queue Ids for same user, same listing and update status in response profile except latest queue record (For Recommendation Algo)
		$profileData = $this->responseModel->getQueueIdByUserAndListing($queuedData['user_id'], $queuedData['listing_id'],$queuedData['listing_type']);
		if(!empty($profileData)) {
			$this->responseModel->updateResponseProfileStatusById($queuedData['user_id'], $queuedData['listing_id'], $profileData['queue_id']);
		}

		if(empty($courseObj)) {
			$courseObj = $this->courseRepository->find($queuedData['listing_id'], array('basic', 'location'));
			$course_id = $courseObj->getId();
		} else {
			$course_id = $courseObj->getId();
			if((empty($course_id)) || ($course_id != $queuedData['listing_id'])) {
				$courseObj = $this->courseRepository->find($queuedData['listing_id'], array('basic', 'location'));
				$course_id = $courseObj->getId();
			}
		}

		if(empty($course_id)) {
			return;
		}

		$deletedCourseData = array();
		$deletedCourseData = $this->course_model->getCourseDetailById($queuedData['listing_id']); // Check that course is deleted or not
		if(empty($deletedCourseData)) {
			return;
		}

    	$isPaid = $courseObj->isPaid();

		$responseLibObj        = new responseLib();

    	if($isPaid){
    		$queuedData['institute_id'] = $courseObj->getInstituteId();
    		$queuedData['isClientResponse'] = $responseLibObj->checkIsClientResponse($queuedData);
  
    	}

    	unset($queuedData['rdc']);

    	$defaultActionType = 'GetFreeAlert';
    	if($isPaid) {
    		$queuedData['listing_subscription_type'] = 'paid';
    		$defaultActionType = 'GetFreeAlert';
    	} else {
    		$queuedData['listing_subscription_type'] = 'free';
    		$defaultActionType = 'download_brochure_free_course';
    	}

    	if($queuedData['action_type'] == 'other') {
    		$queuedData['action_type'] = $defaultActionType;
    	}

    	$institute_id = $courseObj->getInstituteId();
    	$queuedData['institute_id'] = $institute_id;

		//Logic to avoid more than 5 course responses on an institute.
		if($queuedData['action_type'] == "Viewed_Listing" || $queuedData['action_type'] == "mobile_viewedListing" || $queuedData['action_type'] ==  "Viewed_Listing_Pre_Reg") {

			$instituteIds  = array($institute_id);
			$courses = $this->instituteRepo->getCoursesListForInstitutes($instituteIds,'readHandle');

			$allCourses = $courses[$institute_id];			
			$sameCourseResponse = $this->responseModel->checkForSameCourseResponse($queuedData['user_id'], $allCourses);

			if($sameCourseResponse == false) {
				return;
			}
			
		}

		$existingResponse = $this->responseModel->checkExistingResponse($queuedData);

		if(!empty($existingResponse)) {

			$currentActionType = $existingResponse['action'];
			$newActionType     = $queuedData['action_type'];

    		/*$responseLibObj = new responseLib();*/
			$upgradeResponse = $responseLibObj->checkForupgradeResponse($currentActionType, $newActionType);
			
			$existingResponseId = $existingResponse['id'];

			if($upgradeResponse) {
				// $this->responseModel->upgradedResponseDataForMis($existingResponseId);

				$this->updateResponseData($queuedData, $existingResponseId);
				
				$eData['tempLMSId']  = $existingResponse['id'];
				$eData['action']     = 'course_upgrade_response';
				$extraData           = json_encode($eData);
				$this->indexResponseToElastic($extraData,$queuedData['user_id'],$queuedData['id']);

			} else {

				$this->responseModel->saveTempResponseData($queuedData);

				if(!empty($profileData)) {

					$this->responseModel->updateResponseTablesTime($queuedData, $existingResponseId);

					$eData['tempLMSId']    = $existingResponse['id'];
					$eData['responseTime'] = $queuedData['submit_time'];
					$eData['action']       = 'course_downgrade_response';
					$extraData             = json_encode($eData);
					$this->indexResponseToElastic($extraData,$queuedData['user_id'],$queuedData['id']);
					
					$this->usermodel->addUserToIndexingQueue($queuedData['user_id']);
				}

			}

			//echo 'Cron Ends via Response Updation Case for id = '.$queuedData['id'].'<br/>';

		} else {

			$result = $this->saveResponseData($queuedData, $courseObj);
			$existingResponseId = $result['id'];
			//echo 'Cron Ends via Response Insertion Case for id = '.$queuedData['id'].'<br/>';

		}

		$responsePortingLib = $this->load->library('response/responsePortingLib');		
		$responsePortingLib->addDataForPorting($existingResponseId, $queuedData);

	}

	/**
	* This Function is used for data updation of response related tables if user create response within 24 hours
	*/
	private function updateResponseData($queuedData, $existingResponseId) {

    	$this->responseModel->saveTempResponseData($queuedData);

		//Update response action in tempLMSTable
		$this->responseModel->updateResponseData($queuedData, $existingResponseId);

		//Insert updated response id in upgradedresponse table
		//$this->responseModel->saveUpgradedResponseData($existingResponseId, $queuedData['submit_time']);

		//Update latest response data in latestResponseData table
		$this->responseModel->updateLatestUserResponseData($queuedData);

		//add user to  tuserIndexingQueue
		$this->usermodel->addUserToIndexingQueue($queuedData['user_id']);

	}

	/**
	* This Function is used saving response data in response related tables when user is creating response for first time
	*/
	private function saveResponseData($queuedData, $courseObj) {

    	$lastTempResponseId = '';
    	$lastTempResponseId = $this->responseModel->saveTempResponseData($queuedData);

		//Insert response action in tempLMSTable
		$lastResponseId = '';
		$lastResponseId = $this->responseModel->saveResponseData($queuedData);

		//update recent response flat table
		$this->insertLatestUserResponseData($queuedData);

		// Update userMailerSentCount table to reset triggers of product mailers when a response is made
		$this->productmailereventtriggers->resetMailerTriggers($queuedData['user_id'], 'responseCreated');

        // Register apply on recommendation
        $institute_id = $queuedData['institute_id'];
        $this->registerApplyOnRecommendation($queuedData['user_id'],$queuedData['listing_id'], $institute_id, $queuedData['submit_time']);

		//If preferred city/locality specified
		$locationId = '';
		$locationId = $this->addResponseLocationInfo($queuedData, $lastResponseId, $lastTempResponseId, $institute_id, $courseObj);

		// add all responses user wise
		$this->saveConsolidatedResponseData($queuedData);

		if($queuedData['listing_subscription_type'] == 'free') {
			$this->responseModel->updateUserData($queuedData['user_id']);
		}

		//add user to  tuserIndexingQueue
		$this->usermodel->addUserToIndexingQueue($queuedData['user_id']);

		$courseName = $courseObj->getName();
		if($queuedData['listing_subscription_type'] == 'paid' && $queuedData['action_type'] != 'Request_Callback' && $queuedData['isClientResponse'] =='Yes' ){ // since SMS for request call back has already been sent, no need to send it now

			if($locationId > 0) {
				$locations = $courseObj->getLocations();
				if(!empty($locations[$locationId]) && is_object($locations[$locationId])) {
					$locationContactDetails = $locations[$locationId]->getContactDetail();
					if(!empty($locationContactDetails) && is_object($locationContactDetails)) {
						$courseMobile = $locationContactDetails->getAdmissionContactNumber();
						if(!$courseMobile || $courseMobile == '') {
							$courseMobile = $locationContactDetails->getGenericContactNumber();
						}
					}
				}
			}
			$this->sendResponseSMSToClient($queuedData, $courseName, $courseMobile);
		}

		$eData['action']   = 'course_new_response';
		$extraData         = json_encode($eData);

		$this->indexResponseToElastic($extraData,$queuedData['user_id'],$queuedData['id']);

		$result = array('id'=> $lastResponseId);
		return $result;

	}

	/**
	* This Function is used for insertion of response data in latestUserResponseData table
	*/
	private function insertLatestUserResponseData($data) {
		
		$existingData = $this->responseModel->checkExistingResponseInLatestUserResponse($data['user_id']);

		$data['country_id'] = 2;
		if(!empty($existingData)) { 
			if($data['action_type'] != 'Viewed_Listing_Pre_Reg') {
				$this->responseModel->updateLatestUserResponseDataByUserId($data);

				if($data['listing_subscription_type'] == 'free') {
					$this->responseModel->updateUserFlag($data['user_id']);
				}
			}
		} else {
			$this->responseModel->saveLatestUserResponseData($data);
		}

	}

	/*
	 * Register apply on a recommendation
	 * Apply can either be on institute (request e-brochure button) or course (apply from listing detail page)
	 */	
	private function registerApplyOnRecommendation($user_id,$listing_id,$institute_id, $submit_time)	{

		$this->responseModel->registerApplyOnRecommendationInstitute($institute_id, $user_id, $submit_time);
		$this->responseModel->registerApplyOnRecommendationCourse($listing_id, $user_id, $submit_time);
		
	}

	/**
	* Function for insertion of consolidated response data
	*/
	private function saveConsolidatedResponseData($data) {
		
		$existData = $this->responseModel->checkExistingResponseData($data['listing_id'], $data['action_type']);

		if(!empty($existData)) {
			$userIds = $existData['userIds'].','.$data['user_id'];
			$userIdArray = explode(",", $userIds);
			$this->responseModel->updateConsolidatedUserResponseData($userIdArray, $existData['id']);
		} else {
			$this->responseModel->saveConsolidatedUserResponseData($data['listing_id'], $data['action_type'], $data['user_id']);
		}

	}

	/**
	* Function for insertion of data in response location table
	*/
	private function addResponseLocationInfo($queuedData, $lastResponseId, $lastTempResponseId, $institute_id, $courseObj) {
		
		$institute_location_id = 0;$cityId = 0;
		if($queuedData['preferred_city_id'] > 0) {
			$institute_location_id = $this->getInstituteLocationId($institute_id, $queuedData['preferred_city_id'], $queuedData['preferred_locality_id']);
			$cityId = $queuedData['preferred_city_id'];
		} else {
			$courseMainLocation = $courseObj->getMainLocation();
			if((is_object($courseMainLocation)) && (!empty($courseMainLocation))) {
				$institute_location_id = $courseMainLocation->getLocationId();
				$cityId = $courseMainLocation->getCityId();
			}
		}

		if($institute_location_id > 0) {
			$this->responseModel->saveResponseLocationData($lastResponseId, $institute_location_id, $lastTempResponseId);
		}

		if($cityId > 0) {
			
			// logic to add data for virtual city
			$this->load->builder('LocationBuilder','location');
			$locationBuilder = new LocationBuilder;
			$locationRepository = $locationBuilder->getLocationRepository();
			$cityObject = $locationRepository->findCity($cityId);
			$virtualCityId = $cityObject->getVirtualCityId();			
			
			$this->responseModel->updateResponseLocationAffinity($queuedData['user_id'], $cityId);
			// code for virtual city id			
			if($virtualCityId >0 && $virtualCityId!=$cityId) {
				$this->responseModel->updateResponseLocationAffinity($queuedData['user_id'], $virtualCityId);	
			}
		}

		return $institute_location_id;
	}

	/**
	* Function for getting institute location Id
	*/
	private function getInstituteLocationId($institute_id, $preferred_city_id = 0, $preferred_locality_id = 0) {

		$institute_location_id = '';
		if($preferred_city_id > 0) {

		    $instituteIds = array($institute_id);
		    $stateCityLocalityFilter = array();
		    $stateCityLocalityFilter['cities'] = array($preferred_city_id);
		    if($preferred_locality_id > 0) {
		    	$stateCityLocalityFilter['localities'] = array($preferred_locality_id);
			}

	    	$instituteObj = $this->instituteRepo->getInstituteLocations($instituteIds, '', $stateCityLocalityFilter);
	    	
	    	if(!empty($instituteObj)) {
	    		$institute_locations = array_keys($instituteObj[$institute_id]);
	    		$institute_location_id = $institute_locations[0];
	    	}
	    }
	    return $institute_location_id;

	}

	/**
	* Function for sending response sms to clients
	*/
	private function sendResponseSMSToClient($queuedData, $courseName, $courseMobile = '') {

		$courseContactDetails = $this->responseModel->getCourseContactDetails($queuedData['listing_id'], $queuedData['listing_type']);			
		$userId = trim($courseContactDetails['userid']);

		$userDetails = $this->usermodel->getUserFlagDetails($queuedData['user_id']);

		$mobileNumber = $courseMobile; 											   // First send sms on institute location mobile no
		if(!$mobileNumber || $mobileNumber == '') {
			$mobileNumber = trim($courseContactDetails['admission_contact_number']); // If not available send sms on course location mobile no
		}
		if(!$mobileNumber || $mobileNumber == '') {
			$mobileNumber = trim($courseContactDetails['generic_contact_number']);    			   // If not available send sms on course user mobile no
		}

		//$isValidNumber = validateMobile($mobileNumber, 2);
		$isValidNumber = validateEmailMobile('mobile',$mobileNumber);	//First digit six is allowed
		
		global $IVR_Action_Types;

		if( in_array($queuedData['action_type'], $IVR_Action_Types) ){
			$courseName = 'Interested in your college/university, yet to make course specific action';

			if(strlen($queuedData['first_name'])>25){
				$queuedData['first_name'] = substr($queuedData['first_name'], 0,25);
				$queuedData['first_name'] .= '...';
			}
		}

		if($mobileNumber && $mobileNumber != '' && $isValidNumber) {			
			
			$smsContent = "New response \nName: ".$queuedData['first_name'];	
     
			$smsContent .= "\nMobile: ".$queuedData['mobile']; 
		
            $smsContent .= "\nNDNC: ".$userDetails['isNDNC'];

			$smsContent .= "\nCourse: ".$courseName;		
			
			$smsQueueId = $this->alerts_client->addSmsQueueRecord("12",$mobileNumber,$smsContent,$userId,'','','','Y');

			if($smsQueueId){
				$this->responseModel->updateDataForResponseElasticMap($queuedData, $smsQueueId,'client_sms_queue_id');						
			}

		}
	}

	/**
	* Function for calling to users after creating response
	*/
	private function callMeNow($data, $instituteId, $courseName) {

		$courseId = $data['listing_id'];
		$sessionId = $data['visitor_session_id'];
		$action_type = $data['action_type'];

		global $callMeWidgetInstitutes;
		
		if(in_array($action_type, array(3,4,6))) {
			return false;
		}
		if(!array_key_exists($instituteId,$callMeWidgetInstitutes)){
			return false;
		}

		$this->load->library('common/Call');
		$call = new Call();

		$nowTime = strtotime("now");
		$mintime = strtotime($callMeWidgetInstitutes[$instituteId]['mintime']);
		$maxtime = strtotime($callMeWidgetInstitutes[$instituteId]['maxtime']);
		if($mintime > $maxtime){
			$condition = !($nowTime > $mintime || $nowTime < $maxtime);
		}else{
			$condition = $nowTime < $mintime || $nowTime > $maxtime;
		}

		if($condition){
			$this->responseModel->recordCallWidgetLoad($instituteId,$courseId,"CallCancelled");
			return;
		}

		$call = $call->connectCall($callMeWidgetInstitutes[$instituteId]['numbers'], $mobile, $courseName);

		$this->responseModel->recordCallWidgetLoad($instituteId, $courseId, "CallMade", $sessionId);

	}

	/**
	* Function for sending response Mail to user after creating response by CRON
	*/
	public function sendResponseMailByCron() {
		$this->validateCron();
		if(RESPONSE_CREATION_METHOD == "direct"){
			echo 'Response Mail Sending From Cron Not Allowed';
			return;
		}
		$this->_init();



		/*Get data for online form mailers - starts*/
		$excludeActionTypeFromOAFMails = array('COMPARE_VIEWED', 'MOB_COMPARE_VIEWED', 'MOB_Viewed', 'Mob_Viewed_Listing_Pre_Reg', 'mobile_viewedListing', 'Viewed_Listing', 'Viewed_Listing_Pre_Reg', 'Viewed_Listing_Pre_Reg_sa_mobile', 'Viewed_Listing_sa_mobile', 'Institute_Viewed','MOB_Institute_Viewed');

		$liveOnlineFormMap = array();
		$liveOnlineIsInternalMap = array();

		$oafMailerData = $this->getOnlineOAFDataForMailer();
		$liveOnlineFormMap 			= $oafMailerData['liveOnlineFormMap'];
		$liveOnlineIsInternalMap 	= $oafMailerData['liveOnlineIsInternalMap'];
		/*Get data for online form mailers - ends*/

		$queuedData = $this->responseModel->getDataFromQueue('mail');

		if(!empty($queuedData)) {

			foreach($queuedData as $data) {

				$userData = array();
				$userData = $this->getUserBasicData($data['user_id'],'','N');

				$finalData = array();
				$finalData = array_merge($data, $userData);
				if(!empty($finalData)) {

					if(($finalData['user_id'] > 0) && (!empty($finalData['email_id'])) && ($finalData['listing_id'] > 0) && ($finalData['tracking_key'] > 0) && ($finalData['id'] > 0)) {

						try {
							if($finalData['listing_type'] == 'course'){
								$this->sendResponseMailFromData($finalData);
							}
							else if($finalData['listing_type'] == 'exam'){
								// send mail for Exam Response
									$this->sendMailForExamResponse($finalData);
							}
						}catch(Exception $e){
							if($finalData['listing_type'] == 'exam'){
								$to = "pranjul.raizada@shiksha.com,ankur.gupta@shiksha.com";
							}else if($finalData['listing_type'] == 'course'){
								$to = "naveen.bhola@shiksha.com";				
							}
							mail($to,'Exception in Response Mail Sending By Cron Exception at '.date('Y-m-d H:i:s'), 'From page: '.$_SERVER['HTTP_REFERER'].'<br/>'.print_r($finalData, true).$e->getMessage());
						}

							$this->responseModel->updateTempResponseQueue($finalData['id'], 'Y');

					}

				}

				/*Send online form mailers to responses on those courses - start*/
				if($data['listing_type'] == 'course' && $liveOnlineFormMap[$data['listing_id']] && !in_array($data['action_type'],$excludeActionTypeFromOAFMails) && $liveOnlineIsInternalMap[$data['listing_id']] ){  

					$courseEntityArray = array('basic');
					$courseObj = $this->courseRepository->find($data['listing_id'], $courseEntityArray);
					$instituteName = $courseObj->getInstituteName();

					Modules::run('mOnlineForms5/OnlineFormsMobile/sendOnlineLinkEmail', $data['listing_id'],base64_encode($instituteName), $liveOnlineIsInternalMap[$data['listing_id']], $userData['email_id']);

					unset($courseObj);
					unset($instituteName);
				}
				/*Send online form mailers to responses on those courses - ends*/


			}
			
			echo '<br/>Brochure Mail Processed for id = '.$data['id'].'<br/>';

			unset($data);
			unset($finalData);
			unset($userData);
		
			echo '<br/>Cron Ends for Mail';

		} else {
			echo 'Queue Empty';
		}
	}

	/*
	 * Refer LF-7137
	 * Function will pick yesterday's viewed responses and send viewed response mail to them
	 */
	public function sendViewedResponseMailByCron() {
		ini_set("memory_limit", "3000M");
		ini_set('max_execution_time', -1);
		$this->validateCron();
		if(RESPONSE_CREATION_METHOD == "direct"){
			echo 'Response Mail Sending From Cron Not Allowed';
			return;
		}

		$this->_init();

		//Fetch users made who've made viewed response yesterday and no other response today (till before cron hit)
		$data['listing_type'] = 'course';
		$data['action'] = array('Viewed_Listing', 'mobile_viewedListing', 'Mob_Viewed_Listing_Pre_Reg', 'Viewed_Listing_Pre_Reg');
		$viewedResponseUsers = $this->responseModel->getPreviousDayResponses($data);

		if(empty($viewedResponseUsers)) {
			return;
		}
		
		//Create mail for these users
		$this->responseLib = new responseLib();
		$this->responseLib->sendViewedResponseMail($viewedResponseUsers);
	}

	/**
	* Function for gathering data and sending mail when response is created like 
	* DEB, compare, shortlist, send contact details
	*/
	private function sendResponseMailFromData($queuedData, $courseObj = '') {

		$action_type = $queuedData['action_type'];
		$actionTypesForNotSendingMail = array('Institute_Viewed', 'MOB_Institute_Viewed', 'Viewed_Listing', 'mobile_viewedListing', 'Mob_Viewed_Listing_Pre_Reg', 'Viewed_Listing_Pre_Reg');
		if(in_array($action_type, $actionTypesForNotSendingMail)) {
			unset($queuedData);
			unset($courseObj);
			return;
		}
		$queuedData['actionTypesForNotSendingMail'] = $actionTypesForNotSendingMail;

		$courseEntityArray = array('basic', 'location','eligibility');

		global $placementBrochureTrackingKeys, $internshipBrochureTrackingKeys;
		if((in_array($queuedData['tracking_key'], $placementBrochureTrackingKeys)) || (in_array($queuedData['tracking_key'], $internshipBrochureTrackingKeys))) {
			$courseEntityArray[] = 'placements_internships';
		}

		if(empty($courseObj)) {
			$courseObj = $this->courseRepository->find($queuedData['listing_id'], $courseEntityArray);
			$course_id = $courseObj->getId();
		} else {
			$course_id = $courseObj->getId();
			if((empty($course_id)) || ($course_id != $queuedData['listing_id'])) {
				$courseObj = $this->courseRepository->find($queuedData['listing_id'], $courseEntityArray);
				$course_id = $courseObj->getId();
			}
		}
		
		if(empty($course_id)) {
			unset($queuedData);
			unset($courseObj);
			return;
		}

		$deletedCourseData = array();
		$deletedCourseData = $this->course_model->getCourseDetailById($queuedData['listing_id']); // Check that course is deleted or not
		if(empty($deletedCourseData)) {
			unset($deletedCourseData);
			unset($queuedData);
			unset($courseObj);
			return;
		}

		// Do not send the brochure mail if already a day older reponse already exists as suggested by Aditya

		$responseLibObj = new responseLib();
		$responseLibObj->sendResponseSMSToUser($queuedData);

		$mailData = $this->getDataForBrochureMail($queuedData, $courseObj);		

		unset($courseObj);
		if(!empty($mailData)) {
			$mailerId = $this->sendBrochureMail($mailData);	
		} 

		$this->responseModel->updateDataForResponseElasticMap($queuedData, $mailerId,'user_mail_queue_id');
		
		/*$eData['action']   = 'course_new_response';
		$extraData         = json_encode($eData);
		$this->indexResponseToElastic($extraData,$queuedData['user_id'],$queuedData['id']);*/
		unset($queuedData);
	}

	/**
	* Function for gathering data reqired for brochure mail
	*/
	private function getDataForBrochureMail($queuedData, $courseObj) {

        $courseName = $courseObj->getName();
        $instituteName = $courseObj->getInstituteName();
        $instituteId = $courseObj->getInstituteId();
        $courseURL = $courseObj->getURL();
        $brochureURL = ''; $brochureSize = ''; $mailContentFirstLine = ''; $mailContentSecondLineText = '';

		$brochureDetails = array();
        $brochureDetails = $this->getCourseBrochure($courseObj, $queuedData['tracking_key']);

        $brochureURL = $brochureDetails['brochureURL'];
        $brochureSize = $brochureDetails['brochureSize'];
        $mailContentFirstLine = $brochureDetails['mailContentFirstLine'];
        $mailContentSecondLineText = $brochureDetails['mailContentSecondLineText'];

		$this->load->config('response/responseConfig');

		global $pageTypeResponseActionMapping, $action_type_array, $placementBrochureTrackingKeys, $internshipBrochureTrackingKeys , $shortlistTrackingKeys , $compareTrackingKeys;
		$actionsDefinedInConstant = array_values($pageTypeResponseActionMapping);
		$action_type_array = array_merge($action_type_array, $actionsDefinedInConstant);

		$action_type = $queuedData['action_type'];

	    if(($action_type != "Viewed_Listing") && ($action_type != "Viewed_Listing_Pre_Reg") && ($action_type != 'reco_mailer') && ($action_type != 'RANKING_PAGE_REQUEST_EBROCHURE') && ($brochureURL != "") && (in_array($action_type,$action_type_array))) {

			$this->responseModel->trackDownloadBrochure($queuedData, $brochureURL);
		    
		}

		if((!in_array($queuedData['tracking_key'], $placementBrochureTrackingKeys)) && (!in_array($queuedData['tracking_key'], $internshipBrochureTrackingKeys))) {

			if(in_array($queuedData['tracking_key'],$shortlistTrackingKeys ))	
			{
				$queuedData['tracking_keys'] = $shortlistTrackingKeys;
				$existingResponse = $this->responseModel->checkExistingResponseFromQueue($queuedData);	
			}	
			else if(in_array($queuedData['tracking_key'],$compareTrackingKeys))
			{
				$queuedData['tracking_keys'] = $compareTrackingKeys;
				$existingResponse = $this->responseModel->checkExistingResponseFromQueue($queuedData);
			}
			else
			{
				$existingResponse = $this->responseModel->checkExistingResponseFromQueue($queuedData);
			}
			if(!empty($existingResponse)) {
				unset($existingResponse);
				unset($queuedData);
				unset($courseObj);
				return;
			}

		}

		$stream_id = $queuedData['stream_id'];
		if(empty($stream_id)) {
			$hierarchy = $courseObj->getPrimaryHierarchy();
			$stream_id = $hierarchy['stream_id'];
			if(empty($stream_id)) {
				mail('naveen.bhola@shiksha.com','Stream Not Found in Response Mail Sending By Cron Exception at '.date('Y-m-d H:i:s'), 'From page: '.$_SERVER['HTTP_REFERER'].'<br/>'.print_r($queuedData, true));
				return;
			}
		}

		$mailData = array();		
		$mailData['courseName'] = $courseName;
		$mailData['courseObj'] = $courseObj;
		$mailData['instituteName'] = $instituteName;
		$mailData['instituteId'] = $instituteId;
		$mailData['courseId'] = $queuedData['listing_id'];
		$mailData['listing_type_id'] = $queuedData['listing_id'];
		$mailData['userId'] = $queuedData['user_id'];
		$mailData['brochureURL'] = $brochureURL;
		$mailData['brochureSize'] = $brochureSize;
		$mailData['emailId'] = $queuedData['email_id'];
		$mailData['URL'] = $courseURL;
		$mailData['first_name'] = $queuedData['first_name'];
		$mailData['stream_id'] = $stream_id;
		$mailData['queue_id'] = $queuedData['id'];
		$mailData['mailContentFirstLine'] = $mailContentFirstLine;
		$mailData['mailContentSecondLineText'] = $mailContentSecondLineText;
		$mailData['tracking_key'] = $queuedData['tracking_key'];
		$mailData['documentTypeForAttachment'] = $brochureDetails['documentTypeForAttachment'];
		$mailData['leanHeaderFooterV2'] = 1;
		$mailData['entityType'] = "course";
		$mailData['entityId'] = $queuedData['listing_id'];

		global $contactDetailsResponseTrackingKeys;
		//checking if reponse type is contact details type
		if(in_array($queuedData['tracking_key'], $contactDetailsResponseTrackingKeys)){
			$contactDetailsData = $this->prepareContactResponseMailData($queuedData, $courseObj);
			$mailData = array_merge($mailData, $contactDetailsData);
			$mailData['sendContactDetails'] = 1;
		}

		return $mailData;

	}

	function prepareContactResponseMailData($queuedData, $courseObj){

		$institute_location_id = 0;
		$cityId = 0;
		$institute_id = $courseObj->getInstituteId();
		if($queuedData['preferred_city_id'] > 0) {
			$institute_location_id = $this->getInstituteLocationId($institute_id, $queuedData['preferred_city_id'], $queuedData['preferred_locality_id']);
			$cityId = $queuedData['preferred_city_id'];
		} else {
			$courseMainLocation = $courseObj->getMainLocation();
			if((is_object($courseMainLocation)) && (!empty($courseMainLocation))) {
				$institute_location_id = $courseMainLocation->getLocationId();
				$cityId = $courseMainLocation->getCityId();
			}
		}
		$locations = $courseObj->getLocations();
		if(array_key_exists($institute_location_id, $locations)){
			$currentLocation = $locations[$institute_location_id];
		}
		else{
			mail('romil.goel@shiksha.com','Current Location not found in Contact Response Mail Sending By Cron Exception at '.date('Y-m-d H:i:s'), 'From page: '.$_SERVER['HTTP_REFERER'].'<br/>'.print_r($queuedData, true));
			error_log("error3");
				return;
		}
		$contactDetails = $currentLocation->getContactDetail();
		
		if(empty($contactDetails) || !is_object($contactDetails)) {
			mail('romil.goel@shiksha.com','Contact Details not found in Contact Response Mail Sent By Cron Exception at '.date('Y-m-d H:i:s'), 'From page: '.$_SERVER['HTTP_REFERER'].'<br/>'.print_r($queuedData, true));
			return;
		}

		$instituteName = $courseObj->getInstituteName();

		$data['contact_website'] = "NA";
		$data['contact_address'] = "NA";
		$data['contact_email']   = "NA";
		$data['contact_numbers'] = "NA";

		// phone numbers
		$genericContact   = trim($contactDetails->getGenericContactNumber());
		$admissionContact = trim($contactDetails->getAdmissionContactNumber());
		if(!empty($genericContact) && !empty($admissionContact) && $genericContact != $admissionContact){
			$data['contact_numbers'] = $genericContact." (for generic enquiry) <br/>".$admissionContact." (for admissions related enquiry)";
		}
		else{
			if(!empty($genericContact)){
				$data['contact_numbers'] = $genericContact;
			}
			if(!empty($admissionContact)){
				$data['contact_numbers'] = $admissionContact;
			}
		}
		
		// mobile numbers
		$genericEmail   = trim($contactDetails->getGenericEmail());
		$admissionEmail = trim($contactDetails->getAdmissionEmail());
		if(!empty($genericEmail) && !empty($admissionEmail) && $genericEmail != $admissionEmail){
			$data['contact_email'] = $genericEmail." (for generic enquiry) <br/>".$admissionEmail." (for admissions related enquiry)";
		}
		else{
			if(!empty($genericEmail)){
				$data['contact_email'] = $genericEmail;
			}
			if(!empty($admissionEmail)){
				$data['contact_email'] = $admissionEmail;
			}
		}

		$website = $contactDetails->getWebsiteUrl();
		if(!empty($website)){
			$data['contact_website'] = $website;
		}

		$address = $contactDetails->getAddress();
		if(!empty($address)){
			$data['contact_address'] = $address;
		}
		$data['mailer_name'] = 'nationalContactDetails'; 
		$data['to_mail']     = $queuedData['email_id'];
		$data['from_email']  = 'info@shiksha.com';
		$data['listing_type_id'] = $queuedData['listing_id'];
		$data['listing_type'] = $courseObj->getInstituteId();
		$data['listing_id'] = $courseObj->getInstituteType();
		return $data;
	}

	private function getCourseBrochure($courseObj, $tracking_key_id) {

		$brochureURL = ''; $brochureSize = ''; $mailContentFirstLine = ''; $mailContentSecondLineText = '';$documentTypeForAttachment = 'E-Brochure';

		global $placementBrochureTrackingKeys, $internshipBrochureTrackingKeys;

		if(in_array($tracking_key_id, $placementBrochureTrackingKeys)) {

			$placement = $courseObj->getPlacements();
			if(!empty($placement)) {
	        	$brochureURL = $courseObj->getPlacements()->getReportUrl();
	        	// $mailContentFirstLine = 'Your Placement report is here. ';
	        	$mailContentSecondLineText = 'placement ';
	        	$documentTypeForAttachment = 'Placement';
			}

        } else if(in_array($tracking_key_id, $internshipBrochureTrackingKeys)) {

        	$internship = $courseObj->getInternships();
        	if(!empty($internship)) {
				$brochureURL = $courseObj->getInternships()->getReportUrl();
				// $mailContentFirstLine = 'Your Internship report is here. ';
				$mailContentSecondLineText = 'internship ';
				$documentTypeForAttachment = 'Internship';
        	}

		} else {

	        $coursebrochureURL = $courseObj->getBrochureURL();
	        $coursebrochureSize = $courseObj->getBrochureSize();
	        $isBrochureAutoGenerated = $courseObj->isBrochureAutoGenerated();

	        if(($coursebrochureURL != '') && (!$isBrochureAutoGenerated)) {
				$brochureURL = $coursebrochureURL;
				$brochureSize = $coursebrochureSize;
	        } else {
				$instituteId = $courseObj->getInstituteId();
	        	if($instituteId > 0) {
	        		$instituteObj = '';
					$instituteObj = $this->instituteRepo->find($instituteId);
					$brochureURL = $instituteObj->getBrochureURL();
					$brochureSize = $instituteObj->getBrochureSize();
				}
				if(($brochureURL == '') && ($coursebrochureURL != '') && ($isBrochureAutoGenerated)) {
					$brochureURL = $coursebrochureURL;
					$brochureSize = $coursebrochureSize;
				}				
			}

		}

		$result = array();
		$result['brochureURL'] = $brochureURL;
		$result['brochureSize'] = $brochureSize;
		$result['mailContentFirstLine'] = $mailContentFirstLine;
		$result['mailContentSecondLineText'] = $mailContentSecondLineText;
		$result['documentTypeForAttachment'] = $documentTypeForAttachment;

		return $result;
	}

	/**
	* Function for sending Brochure Mail
	*/
	private function sendBrochureMail($data) {
		
		$brochureURL = $data['brochureURL'];
		$send_sorry_mail = 'no';

		if($brochureURL != '') {

			$brochureSize = $data['brochureSize'];
			
			if($brochureSize == '') {
				$this->load->helper('download');
				$brochureSize = get_size($brochureURL);
			}

			if($brochureSize > 0) {
				if($brochureSize > (1024*1024*5)) {

					$mailerId = $this->sendBrochureMailWithLink($data);

				} else {

					$mailerId = $this->sendBrochureMailWithAttachment($data);

				}
			} else {

				$send_sorry_mail = 'yes';

			}

		} else {

			$send_sorry_mail = 'yes';

		}

		if($send_sorry_mail == 'yes') {

			$template_type = 2; // Sorry Mail for No Brochure Template
			$data['template_type'] = $template_type;

			$mailerId = $this->sendMail($data);
		}

		return $mailerId;

	}

	/**
	* Function to send the E-brochure with the link of the brochure
	***/
	private function sendBrochureMailWithLink($data) {

		$responseLibObj = new responseLib();
		$encodedMsg = $responseLibObj->getEncodedMsgForBrochureURL($data['userId'], $data['courseId'], $data['tracking_key']);
		$brochureDownloadLink = SHIKSHA_HOME."/response/Response/downloadBrochure/".$encodedMsg;

	    if($brochureDownloadLink != '') {
	    	$data['brochureDownloadLink'] = $brochureDownloadLink;
			$template_type = 3; // Mail with Link Template
		} else {			
			$template_type = 2; // Sorry Mail for No Brochure Template
		}
		$data['template_type'] = $template_type;
	    $mailerId = $this->sendMail($data);

	    return $mailerId;
	}

	/**
	* Function to send the E-brochure with the attachment of the brochure
	***/
	private function sendBrochureMailWithAttachment($data) {

		$fileExtension = end(explode(".",$data['brochureURL']));
		$attachmentName = str_replace(" ",'_',$data['instituteName']);
		$attachmentName = preg_replace("/[^a-zA-Z0-9_]+/", "", $attachmentName);
		$attachmentName = $attachmentName.".".$fileExtension;
		$documentTypeForAttachment = $data['documentTypeForAttachment'];

		$alerts_client = new Alerts_client();
		
		$attachmentId = $alerts_client->createAttachment("12", $data['courseId'], 'course', $documentTypeForAttachment, '', $attachmentName, $fileExtension, 'true', $data['brochureURL']);
		
		if($attachmentId > 0) {
			$data['attachment'] = array($attachmentId);
			$template_type = 1;
		} else {
			$attachmentDetails = $alerts_client->getAttachmentId("12", $data['courseId'], 'course', $documentTypeForAttachment);
			if(!empty($attachmentDetails)) {
				$attachmentId = $attachmentDetails[0]['id'];
				$data['attachment'] = array($attachmentId);
				$template_type = 1; // Mail with Attachment Template
			} else {
				$template_type = 2; // Sorry Mail for No Brochure Template
			}
		}

		$data['template_type'] = $template_type;

		$mailerId = $this->sendMail($data);

		return $mailerId;

	}

	/**
	* Function to send the E-brochure Mail after response creation
	***/
	private function sendMail($data) {
		
		global $shortlistTrackingKeys,$compareTrackingKeys;
	
		if(in_array($data['tracking_key'],$shortlistTrackingKeys ))
		{
			$data['mailer_name'] = 'shortlistMailer'.$data['template_type'];
		}
		else if(in_array($data['tracking_key'],$compareTrackingKeys ))
		{
			$data['mailer_name'] = 'compareMailer'.$data['template_type'];
		}
		else if(!empty($data['sendContactDetails'])) {
			$data['mailer_name'] = 'nationalContactDetails'.$data['template_type'];
		}
		else
		{
			$data['mailer_name'] = 'NationalCourseDownloadBrochure'.$data['template_type'];
		}

		$response = Modules::run('systemMailer/SystemMailer/sendRequestBrochureMailer',$data['emailId'], $data, $data['attachment']);

		unset($data);
		return $response;
	}

	/**
	* Function to download the brochure given the encoded message having userId and courseId encoded 
	**/	
	public function downloadBrochure( $encodedMsg )	{

		$this->_init();

		if($encodedMsg == '') {
			return;
		}

		$responseLibObj = new responseLib();	    
   	    $dataArr = $responseLibObj->getDecodedMsgForBrochureURL( $encodedMsg );
	    
	    $userId = $dataArr[0];
	    $courseId = $dataArr[1];
	    $tracking_key_id = $dataArr[2];

	    if(count($dataArr) != 3) {
	    	return;
	    }

		global $placementBrochureTrackingKeys, $internshipBrochureTrackingKeys;
	    if(in_array($tracking_key_id, $placementBrochureTrackingKeys)) {

			$courseObj = $this->courseRepository->find($courseId, array('basic', 'placements_internships'));
			$placement = $courseObj->getPlacements();
			if(!empty($placement)) {
	        	$brochureURL = $courseObj->getPlacements()->getReportUrl();
			}

        } else if(in_array($tracking_key_id, $internshipBrochureTrackingKeys)) {

			$courseObj = $this->courseRepository->find($courseId, array('basic', 'placements_internships'));
        	$internship = $courseObj->getInternships();
        	if(!empty($internship)) {
				$brochureURL = $courseObj->getInternships()->getReportUrl();
        	}

		} else {

			$courseObj = $this->courseRepository->find($courseId, array('basic'));
        	$coursebrochureURL = $courseObj->getBrochureURL();
	        $isBrochureAutoGenerated = $courseObj->isBrochureAutoGenerated();

  			if(($coursebrochureURL != '') && (!$isBrochureAutoGenerated)) {
				$brochureURL = $coursebrochureURL;
	        } else {
				$instituteId = $courseObj->getInstituteId();
	        	if($instituteId > 0) {
	        		$instituteObj = '';
					$instituteObj = $this->instituteRepo->find($instituteId);
					$brochureURL = $instituteObj->getBrochureURL();
				}
				if(($brochureURL == '') && ($coursebrochureURL != '') && ($isBrochureAutoGenerated)) {
					$brochureURL = $coursebrochureURL;
				}				
			}

    	}

	    unset($courseObj);
	    unset($instituteObj);
	    if($brochureURL == '') {
			return;
	    }

	    $userInfo = array();
	    $userInfo['user_id'] = $userId;
	    $userInfo['listing_id'] = $courseId;
	    $userInfo['listing_type'] = 'course';
	    $userInfo['visitor_session_id'] = getVisitorSessionId();
	    $userInfo['submit_time'] = date('Y-m-d H:i:s');

    	$this->responseModel->trackDownloadBrochure($userInfo, $brochureURL);

    	unset($userInfo);
		$x = explode('.', $brochureURL);
		$extension = strtolower(end($x));
		
		if($extension == "pdf")	{

			$this->load->helper('download');
    		downloadFileInChunks($brochureURL, 400000);

        } else {
               
		    $curl_response = $responseLibObj->makeCurlRequest($brochureURL);
		    if (preg_match('/Content-Length: (\d+)/', $curl_response['header'], $matches)) {			
		 		$contentLength = (int)$matches[1];
			}
		    if (preg_match('/Content-Type: (.*)/i', $curl_response['header'], $matches1)) {			
		 		$contentType = $matches1[1];
			}

		    // Now download the file.. //set appropriate headers first.. 
		    header('Content-Description: File Transfer');
		    header("Content-Type: ".$contentType);
		    header('Content-Disposition: attachment; filename='.basename($brochureURL));
		    header('Expires: 0');
		    header('Cache-Control: must-revalidate');
		    header('Pragma: public');
		    header('Content-Length: ' . $contentLength);
   			readfile($brochureURL);
        }
	}	

	/**
	* Function to get client course Data by course Object
	**/	
	public function getClientCourseDataByCourseObj($courseObj) {

		if(!is_object($courseObj)) {
			return;
		}

		$courseTypeInfo = $courseObj->getCourseTypeInformation();

		if(empty($courseTypeInfo)) {
			unset($courseObj);
			return;
		}

		$hierarchy = $courseObj->getPrimaryHierarchy();
		$stream_id = $hierarchy['stream_id'];

		$courseHierarchies = $courseTypeInfo['entry_course']->getHierarchies();

		$data = array();$substream_id = array();$subStreamSpecMapping = array();$streams=array();

		foreach($courseHierarchies as $key => $courseHierarchy) {        

			$streams[$courseHierarchy['stream_id']] = 1;

			if($courseHierarchy['stream_id'] == $stream_id) {

		        $data['hierarhies'][$courseHierarchy['stream_id']][$courseHierarchy['substream_id']][] = $courseHierarchy['specialization_id'];

				if(empty($courseHierarchy['substream_id']) && empty($courseHierarchy['specialization_id'])) {
					continue;
				}

				if($courseHierarchy['substream_id'] > 0) {

					if(!in_array($courseHierarchy['substream_id'], $substream_id)) {
						$substream_id[] = $courseHierarchy['substream_id'];
					}
					if($courseHierarchy['specialization_id'] > 0 && !in_array($courseHierarchy['specialization_id'], $subStreamSpecMapping[$courseHierarchy['substream_id']])) {
						$subStreamSpecMapping[$courseHierarchy['substream_id']][] = $courseHierarchy['specialization_id'];
					}

				}

				if($courseHierarchy['substream_id'] == 0) {
					if(!in_array('ungrouped', $substream_id)) {
						$substream_id[] = 'ungrouped';
					}
					if($courseHierarchy['specialization_id'] > 0 && !in_array($courseHierarchy['specialization_id'], $subStreamSpecMapping['ungrouped'])) {
						$subStreamSpecMapping['ungrouped'][] = $courseHierarchy['specialization_id'];
					}
				}
			}
		}
		
		$data['multipleStreams'] = false;
		if(count($streams) > 1) {
			$data['multipleStreams'] = true;
		}

		$data['level'] = $courseTypeInfo['entry_course']->getCourseLevel();
		$data['credential'] = $courseTypeInfo['entry_course']->getCredential();

		$baseCourse = $courseTypeInfo['entry_course']->getBaseCourse();
		if($baseCourse > 0) {
			$baseCourses = array($baseCourse);
		}

		if(!empty($data['hierarhies'])) {

			$data['baseCourse'] = $baseCourse;
			
			$responseData = modules::run('registration/RegistrationForms/getResponseData', $data);
			
			if(!empty($responseData)) {
				if(!empty($responseData['baseCourse'])) {
					$baseCourses = array($responseData['baseCourse']);
				}
				$mapping = $responseData['mappedHierarchies']['hierarchies'];
				if(!empty($mapping)) {
					foreach($mapping as $substreamId=>$hierarchy) {
						
							if($substreamId == 0) {
								if($hierarchy[0] > 0) {
									$newsubStreamSpecMapping['ungrouped'] = $hierarchy;
								} 
							} else {
								$newsubStreamSpecMapping[$substreamId] = $hierarchy;
							}
						
					}
				}
			}
		} 

		if(!empty($newsubStreamSpecMapping)) {
			$subStreamSpecMapping = $newsubStreamSpecMapping;
		}

		$delivery = $courseObj->getDeliveryMethod();
        $mode = $courseObj->getEducationType();
        if(is_object($mode)){
	        if($mode->getId() != FULL_TIME_MODE && !empty($delivery)){
	            $mode = $delivery;
	        }

			if(is_object($mode)) {
				$educationType = array($mode->getId());
			}
        }

		$clientCourseData                         = array();
		$clientCourseData['stream_id']            = $stream_id;
		$clientCourseData['substream_id']         = $substream_id;
		$clientCourseData['subStreamSpecMapping'] = json_encode($subStreamSpecMapping, true);
		$clientCourseData['baseCourses']          = $baseCourses;
		$clientCourseData['mode']                 = $educationType;
		$clientCourseData['level']                = $data['level'];
		$clientCourseData['credential']           = $data['credential'];
		
		return $clientCourseData;		

	}


	/**
	* Function to get Exam Group Data by group Id
	**/	
	public function getExamGroupDataByGroupId($examGroupId) {

		if(empty($examGroupId)){
			return false;
		}

        $examResponseData = array();
        $requiredList     = array();
        $requiredList     = array('primary_hierarchy', 'hierarchies', 'baseCourse', 'mode', 'level');
        $examResponseData = modules::run('registration/RegistrationForms/getExamResponseData', $examGroupId, $requiredList);
        
		$stream_id = $examResponseData['mappedHierarchies']['stream'];

		$data 				  = array();
		$substream_id         = array();
		$subStreamSpecMapping = array();

		foreach($examResponseData['hierarchies'] as $stream => $hierarchy) {        

			foreach ($hierarchy as $substream => $specializations) {

				$data['hierarchies'][$stream][$substream] = $specializations;
				if(empty($substream) && empty($specializations)) {
					continue;
				}

				if($substream > 0) {
					$specializations = array_filter($specializations);
					if(!in_array($substream, $substream_id)) {
						$substream_id[] = $substream;
					}
					$subStreamSpecMapping[$substream] = $specializations;
				}

				if($substream == 0) {
					$specializations = array_filter($specializations);
					if(count($specializations) > 0) {
						if(!in_array('ungrouped', $substream_id)) {
							$substream_id[] = 'ungrouped';
						}
						$subStreamSpecMapping['ungrouped'] = $specializations;
					}
				}

			}

		}
		
		$key         = array_keys($examResponseData['level']);
		$level       = $key[0];
		$mode        = $examResponseData['mode'];
		$baseCourses = $examResponseData['baseCourse'];
		if($baseCourse > 0) {
			$baseCourses = array($baseCourse);
		}
        
		$examGroupData                         = array();
		$examGroupData['stream_id']            = strval($stream_id);
		$examGroupData['substream_id']         = $substream_id;
		$examGroupData['subStreamSpecMapping'] = json_encode($subStreamSpecMapping, true);
		$examGroupData['baseCourses']          = $baseCourses;
		$examGroupData['mode']                 = $mode;
		$examGroupData['level']                = $level;

		return $examGroupData;		

	}


	private function _createAbroadAutoResponse($userModel, $responseSource, $courseId, $userId){

		if($responseSource['userShortlistedCourses'] || $responseSource['userMobileShortlistedCoursesAbroad']){
			$_POST['shortlistCron'] =1;
		}else if($responseSource['preRegistrationView']){
			$_POST['preRegistrationCron'] =1;
		}

		// check if it was a valid response by user..
		$isValidResponseUser = modules::run('registration/Forms/isValidAbroadUser', $userId, $courseId);
		// ..skip if it wasn't
		if($isValidResponseUser == false) {
			echo "Not Valid userId == ".$userId." for course ===".$courseId."<br/>";
			return;;
		}

		unset($_POST['shortlistCron']);

		$this->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;
		$courseRepository1 = $listingBuilder->getAbroadCourseRepository();
		$courseObjs = $courseRepository1->findMultiple(array($courseId));
		// die;
		// ..skip the object if it isn't a valid one
		if(!is_object($courseObjs[$courseId])) {
			_p('object not defined');
			return;
		}
		// lets keep this flag as false for now ...
		$makeResponse = false;

		if($responseSource['preRegistrationView']){

			if(!($courseObjs[$courseId]->isPaid())){
				echo "Free Abroad Listing ".$userId." for course ===".$courseId."<br/>";
				return;
			}

			$lastResponseType = $userModel->getLastResponseType($userId);
			global $responseActionTypeMappingMobile;
		
			if(in_array($lastResponseType, $responseActionTypeMappingMobile)){
				$_POST['source_page'] = 'Mob_Viewed_Listing_Pre_Reg';
				$_POST['isMOB'] = TRUE;
				if( empty($responseSource['tracking_keyid']))
					$responseSource['tracking_keyid'] = 680;
			}else{
				$_POST['source_page'] = 'Viewed_Listing_Pre_Reg';
				if( empty($responseSource['tracking_keyid']))
					$responseSource['tracking_keyid'] = 679;
			}

			$_POST['preRegListingResponse'] = 1;

			$_POST['preListingResponseFreeCourse'] = 1;

			$makeResponse = true;
		}
		else if($responseSource['userShortlistedCourses']) {
			$_POST['shortlistResponse'] = 1;
			$_POST['source_page'] = 'User_ShortListed_Course';
			// now we have decided that we will make response for this one
			$makeResponse = true;
		}
		else if($responseSource['userMobileShortlistedCoursesAbroad']) {
			// set flag in POST to denote shortlist response at source page = User_ShortListed_Course (for mobile it should be appended by _sa_mobile)
			$_POST['shortlistResponse'] = 1;
			$_POST['source_page'] = 'User_ShortListed_Course_sa_mobile';
			// now we have decided that we will make response for this one
			$makeResponse = true;
		}
		else if($responseSource['userMobileShortlistedCourses']) {
			$_POST['shortlistResponse'] = 2;
			$_POST['source_page'] = 'MOB_Course_Shortlist';
			$_POST['sourcePage'] = 'MOB_Course_Shortlist';
			$makeResponse = true;
		}
		else if($responseSource['userNationalShortlistedCourses']) {
			$_POST['shortlistResponse'] = 3;

			//Set response action based on shortlist pageType
			if(array_key_exists($responseSource['userNationalShortlistedCourses'], $pageTypeResponseActionMapping)) {
				$_POST['sourcePage'] = $pageTypeResponseActionMapping[$responseSource['userNationalShortlistedCourses']];
			}

			$makeResponse = true;
		}
		if($makeResponse) {

			$userBasicInfo = $userModel->getUsersBasicInfo(array($userId));
			$userBasicInfo = $userBasicInfo[$userId];

			$_POST['listingType'] = 'course';
			$_POST['listingTypeId'] = $courseId;
			$_POST['cookieString'] = $userBasicInfo['email'].'|'.$userBasicInfo['password'].'|pendingverification';
			$_POST['trackingPageKeyId']=$responseSource['tracking_keyid'];
			$_POST['visitorSessionid'] = $responseSource['visitorSessionid'];
			Modules::run('responseAbroad/ResponseAbroad/createResponseCallForAbroadListings','course',$courseId,$_POST['source_page']);

			// update userShortlistedCourses so that same shortlisted courses are not picked again for response creation
			if($responseSource['userShortlistedCourses'] || $responseSource['userMobileShortlistedCoursesAbroad'] || $responseSource['userMobileShortlistedCourses'] || $responseSource['userNationalShortlistedCourses']) {
				$userModel->updateShortlistedResponses($userId, $courseId);
			}
			unset($courseObjs);
			unset($_POST);
		}
	}

	/**
	* Function to create auto responses for:
	* 	1) pre registration course page views 
	* 	2) shortlisted courses
	* [ Runs every thirty minutes via cron ]
	***/
	public function createAutoResponses() {
		$this->validateCron();
		ini_set('memory_limit', "2048M");
		ini_set('time_limit', -1);
		
		global $pageTypeResponseActionMapping, $responseActionTypeMappingMobile;
		
		$this->load->model('user/usermodel');
		$userModel = new UserModel;

		$this->load->model('response/responsemodel');
		$responseModel = new ResponseModel();
		
		$processedUserIds = array();

		//get users having pre-registration course views
		$usersIds = $userModel->getUsersHavingPreRegistrationViews();
		$preRegistrationCourseViews = $responseModel->getPreRegistrationCourseViews($usersIds);
		
		$usersHavingNoPreRegistrationViews = array_diff($usersIds, array_keys($preRegistrationCourseViews));
		$userModel->updateHasPreRegistrationViews($usersHavingNoPreRegistrationViews);
		
		//get users who have shortlisted courses
		$userAllShortlistedCourses = $responseModel->getUserAllShortlistedCourses();

		if(count($preRegistrationCourseViews) || count($userAllShortlistedCourses) ) {
			$data = $this->_getResponseSourceMapping($preRegistrationCourseViews, $userAllShortlistedCourses);
			$responseSourceMapping = $data['responseSourceMapping'] ;
			$courseIds = $data['courseIds'];
			
	    	$course_model = $this->load->model('nationalCourse/nationalcoursemodel'); 
			$liveCourseIds = $course_model->getLiveCoursesByCourseIds($courseIds);
			
			// loop across $responseSourceMapping array over each user ..
			foreach($responseSourceMapping as $userId => $courseViews){

				$abroadCourses = array();

				// .. then each course..
				foreach($courseViews as $courseId => $responseSource){

					// ..skip the course if it isn't live 
					if(empty($liveCourseIds[$courseId])) {
						/* Abroad Users */
						_p($userId."=> ".$courseId."=> Abroad");
						$this->_createAbroadAutoResponse($userModel, $responseSource, $courseId, $userId);
					}else{
						_p($userId."=> ".$courseId."=> National");

						// check if it was a valid response by user..
						$isValidResponseUser = modules::run('registration/RegistrationForms/isValidUser', $courseId, $userId, false, true);
						
						// ..skip if it wasn't
						if($isValidResponseUser == false) {
							echo "Not Valid userId == ".$userId." for course ===".$courseId."<br/>";
							continue;
						}

						// lets keep this flag as false for now ...
						$makeResponse = false;
						$responseData = array();
						if($responseSource['preRegistrationView']) {
									
							$lastResponseDetails = $responseModel->getLastResponseActionType($userId);
							$lastResponseType = $lastResponseDetails['action_type'];

							if(in_array($lastResponseType, $responseActionTypeMappingMobile)) {

								$responseData['action_type'] = 'Mob_Viewed_Listing_Pre_Reg';
								$responseData['isViewedResponse'] = 'yes';

								if( empty($responseSource['tracking_keyid']))
									$responseSource['tracking_keyid'] = 678;							
								
							}else{

								$responseData['action_type'] = 'Viewed_Listing_Pre_Reg';
								$responseData['isViewedResponse'] = 'yes';

								if( empty($responseSource['tracking_keyid']))
									$responseSource['tracking_keyid'] = 677;							
								
							}												
							$makeResponse = true;
						}
						else if($responseSource['userShortlistedCourses']) {

							$responseData['action_type'] = 'User_ShortListed_Course';
							// now we have decided that we will make response for this one
							$makeResponse = true;

						}
						else if($responseSource['userMobileShortlistedCoursesAbroad']) {

							// set flag in POST to denote shortlist response at source page = User_ShortListed_Course (for mobile it should be appended by _sa_mobile)
							$responseData['action_type'] = 'User_ShortListed_Course';
							// now we have decided that we will make response for this one
							$makeResponse = true;

						}
						else if($responseSource['userMobileShortlistedCourses']) {

							$responseData['action_type'] = 'MOB_Course_Shortlist';
							$makeResponse = true;

						}
						else if($responseSource['userNationalShortlistedCourses']) {
							
							//Set response action based on shortlist pageType
							if(array_key_exists($responseSource['userNationalShortlistedCourses'], $pageTypeResponseActionMapping)) {
								$responseData['action_type'] = $pageTypeResponseActionMapping[$responseSource['userNationalShortlistedCourses']];
							} else {
								$responseData['action_type'] = $responseSource['userNationalShortlistedCourses'];
							}				
							$makeResponse = true;
						}

						if($makeResponse) { 						

							$responseData['user_id'] = $userId;						
							$responseData['listing_id'] = $courseId;						
							$responseData['listing_type'] = 'course';		
							$responseData['tracking_keyid'] = $responseSource['tracking_keyid'];
							$responseData['visitor_session_id'] = $responseSource['visitorSessionid'];
							
							$this->createResponseByParams($responseData);						
							
							// update userShortlistedCourses so that same shortlisted courses are not picked again for response creation
							if($responseSource['userShortlistedCourses'] || $responseSource['userMobileShortlistedCoursesAbroad'] || $responseSource['userMobileShortlistedCourses'] || $responseSource['userNationalShortlistedCourses']) {
								$userModel->updateShortlistedResponses($userId, $courseId);
							}

							unset($responseData);
						}
					}
				_p("------------------------------");
				}
				if(!empty($userId)){
					unset($userBasicInfo[$userId]);
					$processedUserIds[] = $userId;
				}
			}

			$userModel->updateHasPreRegistrationViews($processedUserIds);
		}

		echo 'Cron Completed';
	}	

	/**
	* Function for mapping response source
	**/	
	private function _getResponseSourceMapping($preRegistrationCourseViews, $userAllShortlistedCourses){
		$courseIds = array();
		$responseSourceMapping = array();
		
		foreach($preRegistrationCourseViews as $userId => $courseViews) {
			$courseIds = array_merge($courseIds, $courseViews);
			foreach($courseViews as $courseId) {
				$responseSourceMapping[$userId][$courseId]['preRegistrationView'] = TRUE;
			}
		}

		foreach($userAllShortlistedCourses as $key => $userValues) {
		$courseIds[] =$userValues['courseId']; 
		if($userValues['scope'] == 'abroad'){
			if(!strpos($userValues['pageType'],'_mob')){
					$responseSourceMapping[$userValues['userId']][$userValues['courseId']] = array('userShortlistedCourses' => TRUE, 'tracking_keyid' => $userValues['tracking_keyid'], 'visitorSessionid' => $userValues['visitorSessionid']);
				}
				else{
					$responseSourceMapping[$userValues['userId']][$userValues['courseId']] = array('userMobileShortlistedCoursesAbroad' => TRUE, 'tracking_keyid' => $userValues['tracking_keyid'], 'visitorSessionid' => $userValues['visitorSessionid']);
				}
				//.. if the course was among other pre reg view courses, unset that flag for the course
				if($responseSourceMapping[$userValues['userId']][$userValues['courseId']]['preRegistrationView']) {
					unset($responseSourceMapping[$userValues['userId']][$userValues['courseId']]['preRegistrationView']);
				}
			}else{
				if( in_array($userValues['pageType'], $mobileShortlistActions)){
					$responseSourceMapping[$userValues['userId']][$userValues['courseId']] = array('userMobileShortlistedCourses' => TRUE, 'tracking_keyid' => $userValues['tracking_keyid'], 'visitorSessionid' => $userValues['visitorSessionid']);
					if($responseSourceMapping[$userValues['userId']][$userValues['courseId']]['preRegistrationView']) {
						unset($responseSourceMapping[$userValues['userId']][$userValues['courseId']]['preRegistrationView']);
					}
					if($responseSourceMapping[$userValues['userId']][$userValues['courseId']]['userShortlistedCourses']) {
						unset($responseSourceMapping[$userValues['userId']][$userValues['courseId']]['userShortlistedCourses']);
					}
				}else{
					$responseSourceMapping[$userValues['userId']][$userValues['courseId']] = array('userNationalShortlistedCourses' => $userValues['pageType'], 'tracking_keyid' => $userValues['tracking_keyid'], 'visitorSessionid' => $userValues['visitorSessionid']);
					if($responseSourceMapping[$userValues['userId']][$userValues['courseId']]['preRegistrationView']) {
						unset($responseSourceMapping[$userValues['userId']][$userValues['courseId']]['preRegistrationView']);
					}
					if($responseSourceMapping[$userValues['userId']][$userValues['courseId']]['userShortlistedCourses']) {
						unset($responseSourceMapping[$userValues['userId']][$userValues['courseId']]['userShortlistedCourses']);
					}
					if($responseSourceMapping[$userValues['userId']][$userValues['courseId']]['userMobileShortlistedCourses']) {
						unset($responseSourceMapping[$userValues['userId']][$userValues['courseId']]['userMobileShortlistedCourses']);
					}
				}
			}
		}
		$data['responseSourceMapping'] =  $responseSourceMapping;
		$data['courseIds'] =  $courseIds;
		return $data;
	}

	/**
	* Function for mapping response source
	**/	
	private function _responseSourceMapping($preRegistrationCourseViews, $userAllShortlistedCourses){
		$courseIds = array();
		$responseSourceMapping = array();
		
		foreach($preRegistrationCourseViews as $userId => $courseViews) {
			$courseIds = array_merge($courseIds, $courseViews);
			foreach($courseViews as $courseId) {
				$responseSourceMapping[$userId][$courseId]['preRegistrationView'] = TRUE;
			}
		}

		foreach($userAllShortlistedCourses as $key => $userValues) {
			$courseIds[] = $userValues['courseId']; 
			$responseSourceMapping[$userValues['userId']][$userValues['courseId']] = array('userNationalShortlistedCourses' => $userValues['pageType'], 'tracking_keyid' => $userValues['tracking_keyid'], 'visitorSessionid' => $userValues['visitorSessionid']);
			if($responseSourceMapping[$userValues['userId']][$userValues['courseId']]['preRegistrationView']) {
				unset($responseSourceMapping[$userValues['userId']][$userValues['courseId']]['preRegistrationView']);
			}
			if($responseSourceMapping[$userValues['userId']][$userValues['courseId']]['userShortlistedCourses']) {
				unset($responseSourceMapping[$userValues['userId']][$userValues['courseId']]['userShortlistedCourses']);
			}
			if($responseSourceMapping[$userValues['userId']][$userValues['courseId']]['userMobileShortlistedCourses']) {
				unset($responseSourceMapping[$userValues['userId']][$userValues['courseId']]['userMobileShortlistedCourses']);
			}		
		}
		$data['responseSourceMapping'] =  $responseSourceMapping;
		$data['courseIds'] =  $courseIds;
		
		return $data;
	}

	/**
	* desc : Function to create auto responses for compare on mobile and desktop, for non loggedIn user
	* [ Runs every thirty minutes via cron ]
	* @uther : akhter
	***/
	public function compareAutoResponses() {
		$this->validateCron();
		ini_set('memory_limit', '-1');
		ini_set('time_limit', '-1');
		
		$this->load->builder("nationalCourse/CourseBuilder");
		$courseBuilder = new CourseBuilder();
		$courseRepository = $courseBuilder->getCourseRepository();
			
		//get users who has compared courses
		$this->compare_model = $this->load->model('comparePage/comparepagemodel');
		$userComparedCourses = $this->compare_model->getUserComparedCourses();

		foreach($userComparedCourses as $key=>$value){
			$compareTrackingIds[]  = $value['id'];
		}
	
		$courseIndexArr   = array('courseId_1','courseId_2','courseId_3','courseId_4');
		$coursePageKeyArr = array('pageKeyId_1','pageKeyId_2','pageKeyId_3','pageKeyId_4');
		
		foreach($userComparedCourses as $key=>$value){
			foreach($value as $k=>$v){
				if(in_array($k,$courseIndexArr)){
					if($value[$k]!=0 && !in_array($value[$k],$res[$value['userId']])){
						$res[$value['userId']][] = $value[$k];
					}
				}

				if(in_array($k,$coursePageKeyArr)){
					if($value[$k]!=0){
						$resPageKey[$value['userId']][] = $value[$k];
					}
				}

				if($value[$k]!=0 && !in_array($value['source'],$sourceType[$value['userId']][$value[$k]]['source']) && in_array($k,$courseIndexArr)){
					$sourceType[$value['userId']][$value[$k]]['source'][] = $value['source'];
				}
			}
		}
	
		$userComparedCourses = $res;
		if(count($userComparedCourses))
		{
			$courseIds = array();
			$responseSourceMapping = array();
			
			foreach($userComparedCourses as $userId => $courseViews)
			{
				$courseIds = array_merge($courseIds, $courseViews);
				foreach($courseViews as $ci => $courseId)
				{
					$responseSourceMapping[$userId][$courseId]['userComparedCourses'] = TRUE;
					$finalComparePageKey[$userId][$courseId] = $resPageKey[$userId][$ci];
				}
			}
		
		    foreach($responseSourceMapping as $getUserId => $courseViews)
			{				
			
			$courseObjs = $courseRepository->findMultiple($courseIds);
			
			foreach($responseSourceMapping as $userId => $courseViews)
			{
				foreach($courseViews as $courseId => $responseSource)
				{
					$isValidResponseUser = modules::run('registration/RegistrationForms/isValidUser', $courseId, $userId, false, true);
					
					if($isValidResponseUser == false) {
						continue;
					}
					
					$courseObj = $courseObjs[$courseId];
					
					if(!is_object($courseObj)) {
						continue;
					}
					
					$makeResponse = false;
					$responseData = array();
					if($responseSource['userComparedCourses']) {
						if(in_array('desktop',$sourceType[$userId][$courseId]['source']) && count($sourceType[$userId][$courseId]['source'])==1){
							$responseData['action_type'] = 'COMPARE_VIEWED';
						}
						if(in_array('mobile',$sourceType[$userId][$courseId]['source']) && count($sourceType[$userId][$courseId]['source'])==1){
							$responseData['action_type'] = 'MOB_COMPARE_VIEWED';
						}
						if(count($sourceType[$userId][$courseId]['source'])==2){
							$responseData['action_type'] = 'COMPARE_VIEWED';
						}
						$makeResponse = true;
					}
					if($makeResponse) {
						$responseData['user_id'] = $userId;						
						$responseData['listing_id'] = $courseId;						
						$responseData['listing_type'] = 'course';		
						$responseData['tracking_keyid'] = $finalComparePageKey[$userId][$courseId];
						$responseData['isViewedResponse'] = 'yes';
						$this->createResponseByParams($responseData);		
						unset($responseData);
					}
				}
				
			}
			$this->compare_model->updateIsResponseMade($compareTrackingIds);
			}
		}		
	}

	public function encryptCourseId($courseId) {

		$newcourseId = (int)$courseId;
		if($courseId != $newcourseId || !is_numeric($courseId)) { return false; }

		$this->load->library('common_api/APISecurityLib');
		$APISecurityLibObj = new APISecurityLib();
		$encryptCourseId = $APISecurityLibObj->encrypt($courseId);

		echo $encryptCourseId;
	}

	public function createViewedResponse($encryptedCourseId) {

		$pos = strpos($_SERVER['HTTP_REFERER'], "ieplads");
		if($encryptedCourseId == '' || $pos === false) { return false; }

		$this->load->library('common_api/APISecurityLib');
		$APISecurityLibObj = new APISecurityLib();
		$courseId = $APISecurityLibObj->decrypt($encryptedCourseId);

		if($courseId == '' || !is_numeric($courseId)) { return false; }

		$validateuser = $this->checkUserValidation();

        $this->load->model('qnAmodel');
        $this->qnamodel = new QnAModel();
        $validResponseUser = 0;
        if(($validateuser != "false") && !(in_array($validateuser[0]['usergroup'],array("enterprise","cms","experts","sums"))) && (!($this->qnamodel->checkIfAnAExpert($dbHandle,$validateuser[0]['userid']))) && ($validateuser[0]['mobile'] != ""))
        {
            $validResponseUser = 1;
        }

        if($validResponseUser == 1) {
        	$responseData = array();
			$responseData['listing_id']       = $courseId;
			$responseData['listing_type']     = 'course';
			$responseData['action_type']      = (!isMobileRequest()) ? 'Viewed_Listing' : 'mobile_viewedListing';
			$responseData['isViewedResponse'] = 'yes';
			$responseData['tracking_keyid']   = (!isMobileRequest()) ? DESKTOP_NL_VIEWED_LISTING : '1103';

			Modules::run("response/Response/createResponseByParams", $responseData);      
			echo 'Response Created';
        }
	}
	
	private function createExamResponseFromTempData($queuedData){

		// Get all Queue Ids for same user, same listing and update status in response profile except latest queue record (For Recommendation Algo)
		$profileData = $this->responseModel->getQueueIdByUserAndListing($queuedData['user_id'], $queuedData['listing_id'],$queuedData['listing_type']);

		if(!empty($profileData)) {
			$this->responseModel->updateResponseProfileStatusById($queuedData['user_id'], $queuedData['listing_id'], $profileData['queue_id']);
		}

		// validating group
		$examGroupObj = $this->examRepository->findGroup($queuedData['listing_id']);	
		if(empty($examGroupObj)){
			mail("teamldb@shiksha.com", "error in createExamResponseFromTempData function", "queue_data <br>".print_r($queuedData,true));
			return;
		}		
		$exam_id      = $examGroupObj->getExamId();
		
		if(empty($exam_id)) {
			return;
		}

		$this->load->library('response/responseAllocationLib');
		$responseLib               = new responseAllocationLib();
		$isMatchedSubscriptionPaid = $responseLib->getMatchedSubscriptions(array($queuedData['listing_id']),array($queuedData['city']), true);
		
		if($isMatchedSubscriptionPaid){
			$queuedData['listing_subscription_type'] = 'paid';
		}else{
			$queuedData['listing_subscription_type'] = 'free';			
		}

		$existingResponse = $this->responseModel->checkExistingResponse($queuedData);

		if(!empty($existingResponse)) {

			$currentActionType  = $existingResponse['action'];
			$newActionType      = $queuedData['action_type'];

			$responseLibObj     = new responseLib();
			$upgradeResponse    = $responseLibObj->checkForupgradeResponse($currentActionType, $newActionType,'exam');			
			$existingResponseId = $existingResponse['id'];
			
			if($upgradeResponse){				
				//Update response action in tempLMSTable
				$this->responseModel->updateResponseData($queuedData, $existingResponseId);

				//index user to Solr and Elastic Search
				$this->indexUsertoSolrAndElastic($queuedData);

				$eData['tempLMSId']  = $existingResponseId;
				$eData['action']     = 'exam_upgrade_response';
				$extraData           = json_encode($eData);
				$this->indexResponseToElastic($extraData,$queuedData['user_id'],$queuedData['id']);

			} else {				
				if(!empty($profileData)) {

					$this->responseModel->updateExamResponseTablesTime($queuedData, $existingResponseId,'exam');

					//index user to Solr and Elastic Search
					$this->indexUsertoSolrAndElastic($queuedData);	

					$eData['tempLMSId']    = $existingResponseId;
					$eData['responseTime'] = $queuedData['submit_time'];
					$eData['action']       = 'exam_downgrade_response';
					$extraData             = json_encode($eData);
					$this->indexResponseToElastic($extraData,$queuedData['user_id'],$queuedData['id']);					
				}
			}
		}else{			
			// save response data
			$result = $this->saveExamResponseData($queuedData);
			$existingResponseId = $result['id'];
		}

		$responsePortingLib = $this->load->library('response/responsePortingLib');		
		$responsePortingLib->addDataForPorting($existingResponseId, $queuedData);

	}


	/**
	* This Function is used saving exam response data in response related tables when user is creating response for first time
	*/
	private function saveExamResponseData($queuedData) {

		//Insert response action in tempLMSTable
		$lastResponseId = $this->responseModel->saveResponseData($queuedData);

		// Update userMailerSentCount table to reset triggers of product mailers when a response is made
		$this->productmailereventtriggers->resetMailerTriggers($queuedData['user_id'], 'responseCreated');        

		$this->responseModel->updateUserData($queuedData['user_id']);

		if($lastResponseId){
			//update tempLmsId in examResponseAllocation to show updated Action Type in response Viewer
			$this->responseModel->updateTempIdInExamResponseAllocation($queuedData['listing_id'],$queuedData['user_id'],$lastResponseId);			
		}

		//index user to Solr and Elastic Search
		$this->indexUsertoSolrAndElastic($queuedData);	

		$eData['action']   = 'exam_new_response';
		$extraData         = json_encode($eData);

		$this->indexResponseToElastic($extraData,$queuedData['user_id'],$queuedData['id']);

		$result = array('id'=> $lastResponseId);
		return $result;
	}


	//update counter of each subscription after each allocation - To do
	public function examResponseAllocationCron(){

		$this->validateCron(); 
		$response_allocation_lib = $this->load->library('response/responseAllocationLib');
		
		$last_processed_id = $response_allocation_lib->getLastProcessedId();

		/*Step 1*/
		$this->allocateResponsesToNewSusbcriptions($last_processed_id);

		/*Step 2*/
		$recent_processed_id = $this->allocateIndividualResponses($last_processed_id);

		$response_allocation_lib->updateLastProcessedId($recent_processed_id);		

		/*Step 3 */
		$this->markSubscriptionInactive();
	}

	function markSubscriptionInactive(){
		$response_allocation_lib = $this->load->library('response/responseAllocationLib');
		$response_allocation_lib->markSubscriptionInactive();
	}

	public function getUserAllocationData($responses){
		$userIds = array();
		foreach ($responses as $key => $response) {
			$userIds[$response['userId']] = $response['userId'];
		}
		$response_allocation_lib = $this->load->library('response/responseAllocationLib');
		$userAllocationMapping = $response_allocation_lib->getUserAllocationData($userIds);
		return $userAllocationMapping;
	}

	public function allocateIndividualResponses($last_processed_id){
		$this->load->model('response/responsemodel');
		$this->responseModel = new ResponseModel();

		$response_allocation_lib = $this->load->library('response/responseAllocationLib');
		$responses = $this->responseModel->getUnallocatedResponses($last_processed_id);

		try {

			if(count($responses) > 0){
				$userAllocationMapping = $this->getUserAllocationData($responses);
			}

			foreach ($responses as $response) {
				$recent_processed_id = $response['id'];
				
				$matched_subscriptions = $response_allocation_lib->getMatchedSubscriptions(array($response['listing_type_id']), array($response['city']),false,$response['submit_date']);
				if(count($matched_subscriptions)<1){
					continue;
				}

				$matched_subscriptions = $response_allocation_lib->filterMatchedSubscription($userAllocationMapping, $matched_subscriptions, $response);
				
				if(count($matched_subscriptions) >0){
					$response['entityType'] = 'examGroup';
					$response_allocation_lib->storeIndividualMatchedSubscription($response,$matched_subscriptions);

					$update_subscription_quantity = array_keys($matched_subscriptions);
					$response_allocation_lib->updateDeliveredCountForSubscription($update_subscription_quantity);
				}
				unset($update_subscription_quantity);
				unset($matched_subscriptions);
			}	
		} catch (Exception $e) {
			mail('teamldb@shiksha.com','Exception in Exam Response allocation', '<br/>Exception in Exam Response allocation <br/>');
			return $recent_processed_id-1;
		}
	
		return $recent_processed_id;
	}

	public function allocateResponsesToNewSusbcriptions($last_processed_id){
		/*$last_processed_id = 17691064;*/
		$this->load->model('response/responsemodel');
		$this->responseModel = new ResponseModel();
		$response_allocation_lib = $this->load->library('response/responseAllocationLib');

		$unprocessed_subscriptions = $this->responseModel->getNewExamResponseSubscription();

		foreach ($unprocessed_subscriptions as $subscription) {

			if($subscription['campaignType'] == 'quantity'){
				$response_allocation_lib->processQuantityBasedSusbcription($subscription, $last_processed_id);
			}

			if($subscription['campaignType'] == 'duration'){
				$response_allocation_lib->processDurationBasedSusbcription($subscription, $last_processed_id);
			}

			$response_allocation_lib->markSubscriptionProcessed($subscription['id']);

		}
		
	}

	function sendMailForExamResponse($queuedData){
		if(empty($this->responseModel)){
            $this->load->model('response/responsemodel');
            $this->responseModel = new ResponseModel();
        }

        $action_type = $queuedData['action_type'];
		$actionTypesForNotSendingMail = array('exam_viewed_response');
		if(in_array($action_type, $actionTypesForNotSendingMail)) {
			unset($queuedData);
			return;
		}

		$queuedData['actionTypesForNotSendingMail'] = $actionTypesForNotSendingMail;


		$existingResponse = $this->responseModel->checkExistingResponseFromQueue($queuedData);
        if(!empty($existingResponse) && $queuedData['action_type']!='exam_download_guide') {
                unset($existingResponse);
                unset($queuedData);
                return;
        }

        $examPageLib         = $this->load->library('examPages/ExamPageLib');
        $groupId             = $queuedData['listing_id'];
        $userId              = $queuedData['user_id'];
        $data['digestInfo']  = $examPageLib->createExamDigestMailerData($userId, $groupId);
		if(empty($data['digestInfo'])){
                return;
        }
        $data['digestInfo']['userInfo']    = $this->getUserBasicData($queuedData['user_id'],'','N');
        $toEmail             = $data['digestInfo']['userInfo']['email_id'];
        $attachmentData =  array();
        if($queuedData['action_type']=='exam_download_guide'){
        	$attachmentData = $this->createAttachmentForExam($groupId);
        	$data['template_type'] = $attachmentData['template_type'];
	}
        $data['entityId'] = $groupId;
	$data['entityType'] = 'exam';
	$data['userId'] = $userId;
        Modules::run('systemMailer/SystemMailer/sendExamMailerDigest',$toEmail, $data, $attachmentData['attachment']);
    }

    function createAttachmentForExam($groupId){
    	$examPageLib    = $this->load->library('examPages/ExamPageLib');
    	$data           = $examPageLib->getAttachementData($groupId);
    	$url  = SITE_PROTOCOL.MEDIA_SERVER_IP.$data['guide_url'];
		$tokens = explode('/', $url);
		$fileNameWithExtension   = $tokens[sizeof($tokens)-1];
		$path_parts = pathinfo($fileNameWithExtension);
		$filename   = $path_parts['filename'];
		$extension  = $path_parts['extension'];
    	
    	$fileExtension  = end(explode(".",$url));
		$attachmentName = str_replace(" ",'_',$filename);
		$attachmentName = preg_replace("/[^a-zA-Z0-9_]+/", "", $attachmentName);
		$attachmentName = $attachmentName.".".$extension;
		$documentTypeForAttachment = 'ExamGuide';

		$alerts_client = new Alerts_client();
		
		$attachmentId = $alerts_client->createAttachment("12", $groupId, 'examguide', $documentTypeForAttachment, '', $attachmentName, $extension, 'true', $url);
		
		if($attachmentId > 0) {
			$data['attachment'] = array($attachmentId);
			$template_type = 1;
		} else {
			$attachmentDetails = $alerts_client->getAttachmentId("12", $groupId, 'examguide', $documentTypeForAttachment);
			if(!empty($attachmentDetails)) {
				$attachmentId = $attachmentDetails[0]['id'];
				$data['attachment'] = array($attachmentId);
				$template_type = 1; // Mail with Attachment Template
			}
		}
		$data['template_type'] = $template_type;
		return $data;
    }

    public function indexUsertoSolrAndElastic($queuedData) {
		//add user to  tuserIndexingQueue
		$this->usermodel->addUserToIndexingQueue($queuedData['user_id']);

		//add user to elastic search Queue
		$user_response_lib = $this->load->library('response/userResponseIndexingLib');
		$user_response_lib->insertInIndexingQueue($queuedData['user_id'],'',$queuedData['listing_type'],$queuedData['listing_id']);
    }

	public function deliverResponsesToClient($response_type='exam'){
		if($response_type == 'exam'){
			$this->deliverExamREsponseToClient();
		}
	}

	public function deliverExamResponseToClient(){

		$this->validateCron();
		$this->load->model('response/responsemodel');
		$this->responseModel = new ResponseModel();

		$this->load->library(array('alerts_client'));
		$mail_client = new Alerts_client();
		$user_lib = $this->load->library('user/UserLib');
			
		$response_allocation_lib = $this->load->library('response/responseAllocationLib');
		$response_viewer_lib = $this->load->library('response/responseViewerLib');

		$distinct_subscription = $this->responseModel->getDistinctSubscriptionsForDelivery();
		$subject = "Response to your subscription on Shiksha.com";

		foreach ($distinct_subscription as $subscription) {
			$response_time_map = array();
			$user_group_map = array();
			$exam_groups = array();
			$response_time_map = array();


			$responses_to_deliver = $this->responseModel->getExamResponseToDeliver($subscription['subscriptionId']);

			$responses_to_deliver_user_id = array();
			$min_processed_id = 0;
			$response_count = 0;
			foreach ($responses_to_deliver as $response) {
				$response_count++;
				$response_time_map[$response['userId']][$response['entityValue']] = 	$response['submit_date'];
				$responses_to_deliver_user_id[] = $response['userId'];
				$user_group_map[$response['userId']][] = $response['entityValue'];
				$exam_groups[] = $response['entityValue'];
				$last_processed_id = $response['id'];

				if($min_processed_id<1){
					$min_processed_id = $response['id'];
				}
			}
			
			$group_name_data = $response_viewer_lib->getGroupExamDetails($exam_groups);

			$user_data = $user_lib->getUserDataFromSolr($responses_to_deliver_user_id);

			if(count($user_data)<1){
				continue;
			}

			unset($exam_groups);
			unset($responses_to_deliver);

			$contact_details = $response_allocation_lib->getSubscriptionContactDetails($subscription['subscriptionId']);

			if($contact_details['email'] !=''){
				$mailer_html = $this->load->view('response/ExamResponseMailer',array('user_data'=>$user_data,'response_time_map'=>$response_time_map,'group_name_data'=>$group_name_data,'user_group_map'=>$user_group_map,'response_count'=>$response_count),true);

				$response = $mail_client->externalQueueAdd("12",ADMIN_EMAIL,$contact_details['email'],$subject,$mailer_html,$contentType="html",'0000-00-00 00:00:00','n',array(),'','');
			}

			if($contact_details['mobile'] !=''){
				$this->sendSMSToExamSubscription($contact_details, $user_data, $group_name_data, $user_group_map);
			}

			if($last_processed_id<1){
				continue;
			}
			
			$this->responseModel->markResponsesProcessed($subscription['subscriptionId'], $last_processed_id, $min_processed_id);	
		}

	}

	private function sendSMSToExamSubscription($contact_details, $user_data, $group_name_data, $user_group_map){
		$this->load->library('alerts_client');

		$mobile_number = $contact_details['mobile'];
		$client_id = $contact_details['clientId'];

		foreach ($user_data as $user_id => $data) {

			foreach ($user_group_map[$data['userId']] as $group_id) {
				$smsContent ='';
				$exam_name  ='';
				$group_name  ='';

				$exam_name = $group_name_data[$group_id]['name'];
				$group_name = $group_name_data[$group_id]['groupName'];

				$smsContent = "New response detail\nName: ".$data['Full Name'];
			
	            $smsContent .= "\nMobile: +".$data['ISD Code']."-".$data['Mobile'];
	            
	            $smsContent .= "\nNDNC: ".$data['NDNC Status'];

				$smsContent .= "\nExam Course: ".$exam_name."- ".$group_name;
				
				
				$this->alerts_client->addSmsQueueRecord("12",$mobile_number,$smsContent,$client_id);
			}

			
		}

	}

	private function  indexResponseToElastic($extraData,$userId,$queueId){
		$user_response_lib = $this->load->library('response/userResponseIndexingLib');		
		$user_response_lib->insertInResponseIndexLog($userId,$extraData,$queueId);
	}

	private function getOnlineOAFDataForMailer(){
		
		$liveOnlineForm = $this->responseModel->getAllLiveOAF();

		$liveOnlineFormMap = array();
		$liveOnlineIsInternalMap = array();

		foreach ($liveOnlineForm as  $formCourseId) {
			$liveOnlineFormMap[$formCourseId['courseId']] = true;

			$liveOnlineIsInternalMap[$formCourseId['courseId']] = false;
			if($formCourseId['externalURL'] ==''){
				$liveOnlineIsInternalMap[$formCourseId['courseId']] = true;
			}

			if($formCourseId['otherCourses']!=''){
				$otherCourse = explode(',', $formCourseId['otherCourses']);
				foreach ($otherCourse as $courseId) {
					$liveOnlineFormMap[$courseId] = true;					

					$liveOnlineIsInternalMap[$courseId] = false;
					if($formCourseId['externalURL'] ==''){
						$liveOnlineIsInternalMap[$courseId] = true;
					}	
				}
			}
		}

		$returnData['liveOnlineFormMap'] 		= $liveOnlineFormMap;
		$returnData['liveOnlineIsInternalMap']  = $liveOnlineIsInternalMap;

		return $returnData;
	}
	
	/**
	* This Function is used for sending response created to client on their site form page directly
	*/
	public function sendResponseToClientPage() {
		$this->validateCron();				
		return;
		$responsePortingLib = $this->load->library('response/responsePortingLib');		
		$responsePortingLib->processResponseDataForPortingToClient();

	}

	/**
	* This Function is used for saving response data which we ported to client form page
	*/
	public function saveResponse() {
		
		error_log('curlresponse=='.print_r($_POST, true));
		if(empty($_POST)){
			mail('teamldb@shiksha.com,mohd.alimkhan@shiksha.com,mohit.k1@shiksha.com','Response Not Receiving from python after posting data at '.date('Y-m-d H:i:s'), 'Response Not Receiving from python after posting data'.print_r($_POST, true));
			return;
		}

		$responsePortingLib = $this->load->library('response/responsePortingLib');		
		$responsePortingLib->saveResponse();

	}

	//for ajax to push data to REDIS
	public function createResponseByQ($post_data){
		$userStatus = $this->checkUserValidation();
		$post_data = $_POST;
		$post_data['user_id'] 				= $userStatus[0]['userid'];
		$post_data['visitor_session_id'] 	= getVisitorSessionId();

		if($post_data['user_id']<1){
			return ;
		}
		
		//redis call - write
		$redis_key = 'user_response_queue';
		$redis_client = PredisLibrary::getInstance();
		$redis_client->addMembersToSet($redis_key, array(json_encode($post_data)));

		echo true;
	}


	/*cron to process response form REDIS*/
	public function cronToProcessRedisResponseQueue(){
		$this->validateCron();
		$redis_key = 'user_response_queue';
		$redis_client = PredisLibrary::getInstance();
		$response_queue_data = $redis_client->getMembersOfSet($redis_key);

		foreach ($response_queue_data as $queue_data) {
			$this->createResponseFromRedisQueue($queue_data);
		}
		
		if(count($response_queue_data)>0){
			$redis_client->removeMembersOfSet($redis_key, $response_queue_data);
		}
	}

	private function createResponseFromRedisQueue($queue_data){

		$queue_data = json_decode($queue_data,true);
		$post_data = $queue_data;
        $post_data['examGroupId'] = $queue_data['listing_id'];
        $post_data['isViewedCall'] = 'yes';

		$is_valid_response = Modules::run('registration/RegistrationForms/isValidExamResponse',$queue_data['listing_id'],$queue_data['user_id'],'yes');

		if($is_valid_response){
			$_POST = $post_data;
			$response_created = Modules::run('response/Response/createResponse');
		}

	}

	/*cron to insert user session info form REDIS*/
	public function cronToProcessRedisUserSessionInfo(){
		$this->validateCron();
		$redis_key = 'user_session_info';
		$redis_client = PredisLibrary::getInstance();
		$user_session_info = $redis_client->getMembersOfSet($redis_key);

        if (empty($user_session_info)){
        	return;
        }
        $user_data = array();
        $itr = 0;
        foreach ($user_session_info as $queue_data) {
            $itr++;
            $user_data[] = $queue_data;
            $this->insertUserSessionInfoFromRedisQueue(json_decode($queue_data,true));
            if($itr>500){
                break;
            }
        }
        
        $redis_client->removeMembersOfSet($redis_key, $user_data);        
    }

	private function insertUserSessionInfoFromRedisQueue($queue_data){
		
		$modelObj = $this->load->model('mcommon5/homepagemodel');
        $sessionId = $queue_data["sessionId"];
        $clientIP = $queue_data["clientIP"];
        $userId = $queue_data["userId"];
        $modelObj->insertUserSessionInfo($sessionId, $userId, $clientIP);
	}

	private function processEResponseProfileData($ePropfiledata){
		$examName    = $this->input->post('examName',true);
		$examGroupId = $this->input->post('examGroupId',true);
		$examGroupId = ($examGroupId) ? $examGroupId : $this->input->post('listing_id',true);
		$examScoreType = $this->input->post('examScoreType',true);
		$examScore   = $this->input->post('examScore',true);
		$examScore   = ($examScore) ? $examScore : 0;
		if($ePropfiledata['user_id']>0 && !empty($examName) && !empty($examGroupId)){
			$tempUserInputs  = array(
				'examName' => $examName,
				'examGroupId' => $examGroupId,
				'examScoreType' => $examScoreType,
				'examScore' => $examScore
			);
			if($examScore==0 || empty($examScore)){
				unset($tempUserInputs['examScore'], $tempUserInputs['examScoreType']);
			}
			$userExamDetails = array();
			$userExamDetails['userId'] = $ePropfiledata['user_id'];
			$userExamDetails['userInputs'][] = $tempUserInputs;
			$cpLib = $this->load->library('CP/CollegePredictorLibrary');
			$cpLib->addDataToRabbitMQ(json_encode($userExamDetails), 'predictor_response_mapping_to_profile');
			unset($userExamDetails, $tempUserInputs);	
		}
		
	}	

}
