<?php

/* Add APIs for multiple apply  */
class MultipleApply extends MX_Controller {

	var $userStatus;
	function init() {
		$this->load->helper(array('form', 'url', 'image_helper'));
		$this->load->library(array('miscelleneous','category_list_client','listing_client','register_client','alerts_client','lmsLib'));
		$this->userStatus= $this->checkUserValidation();
	}

	function index()
	{
		$this->load->view('common/testoverlay.php');
	}

	function getExtraFieldsForStudyAbroadResponseForm($responsePoint,$widget)
	{
		$showWhenPlanToGo = TRUE;
		$showExams = TRUE;

		$loggedInUserData =  $this->checkUserValidation();
		if(is_array($loggedInUserData) && is_array($loggedInUserData[0]) && intval($loggedInUserData[0]['userid'])) {
			$loggedInUserId = intval($loggedInUserData[0]['userid']);

			$this->load->model('user/usermodel');
			$userModel = new UserModel;
			if($user = $userModel->getUserById($loggedInUserId)) {

				/**
				 * If when plan to start already filled, unset it
				 */
				$pref = $user->getPreference();
				if($pref) {
					$timeOfStart = $pref->getTimeOfStart();
					if($timeOfStart && $timeOfStart != '0000-00-00 00:00:00') {
						$showWhenPlanToGo = FALSE;
					}
				}

				/**
				 * If exams already filled, unset it
				 */
				$userEducation = $user->getEducation();
				if($userEducation) {
					foreach($userEducation as $education) {
						if($education->getLevel() == 'Competitive exam') {
							$showExams = FALSE;
						}
					}
				}
			}
		}

		$data = array();
		$data['showWhenPlanToGo'] = $showWhenPlanToGo;
		$data['showExams'] = $showExams;

		$data['whenPlanToGoValues'] = array(
		
			'thisYear' => date('Y',strtotime('+0 year')),
			'in1Year' => date('Y',strtotime('+1 year')),
			'in2Years' => date('Y',strtotime('+2 year')),
			'later' => 'Later'
		);

		$data['studyAbroadExams'] = array(
			'toefl' => 'TOEFL',
			'ielts' => 'IELTS',
			'sat'   => 'SAT',
			'gmat'  => 'GMAT',
			'gre'   => 'GRE',
			'other' => 'Other'
			);

		$data['studyAbroadExamScores'] = array(
		'toefl' => array(),
		'ielts' => array(),
		'sat'   => array(600,2400),
		'gmat'  => array(400,800),
		'gre'   => array(500,1600),
		'other' => array()
		);

		$data['widget'] = $widget;

		if(!$responsePoint) {
			$responsePoint = 'CategoryPageOverlay';
		}
		$this->load->view('common/StudyAbroadResponseFormFields/'.$responsePoint,$data);
	}

	function showoverlay($str,$keyname = 'MULTIPLE_APPLY_INSTITUTE_LIST_REQUESTE_BROCHURE_OVERLAY_CLICK',$redirect1='false',$redirect2 = 'false')
	{
		// To know User is logged-in or not
		$validity = $this->checkUserValidation();
		
		$instituteId = (int) $this->input->post('instituteId');
		$courseId = (int) $this->input->post('courseId');
		
		$OTPVerification = 0;
		$ODBVerification = 0;
		if($validity !== false) {
			global $OTPCourses;
			global $ODBCourses;
			
			if($courseId > 0) {
				if($OTPCourses[$courseId]) {
					$OTPVerification = 1;
				}
				else if($ODBCourses[$courseId]) {
					$ODBVerification = 1;
				}
			}
		}
		
		
		$studyAbroadResponse = FALSE;
		if($instituteId) {
			$this->load->builder('ListingBuilder','listing');
			$listingBuilder = new ListingBuilder;
			$instituteService = $listingBuilder->getInstituteService();
			$studyAbroadResponse = $instituteService->isInstituteStudyAbroad($instituteId);
		}

		
		$this->load->library('category_list_client');
		$categoryClient = new Category_list_client();
		$categoryList = $categoryClient->getCategoryTree($appId);
		$data['categoryList']=$categoryList;
		if($str == 1) {
			// user login overlay
			$url = 'common/Request-E-Brochure1.php';
		} elseif ($str == 2) {
			// Thanks overlay
			$url = 'common/Request-E-Brochure2.php';
		} elseif ($str == 3) {
			// Main form overlay
			$url = 'common/Request-E-Brochure3.php';
		}elseif ($str == 4) {
			// user registration overlay
			$url = 'common/Request-E-Brochure4.php';
		}elseif ($str == 5) {
			// user registration overlay
			$url = 'common/Request-E-Brochure5.php';
		}elseif ($str == 7) {
			//Normal user registration confirmation.Not for request
			$url = 'common/Request-E-Brochure7.php';
		}elseif ($str == 8) {
			$url = 'common/SubscribeEvents.php';

		}elseif ($str == 9) {
			$url = 'common/emailIdentifiedUser.php';

		}elseif ($str == 10) {
			$url = 'common/subscribeSuccess.php';

		}elseif($str == 11){
			$url = 'common/alreadySubscribed.php';
		}elseif($str == 12){
			$url = 'common/subscribeEventSuccess.php';
		}elseif($str == 13){
			$url = 'common/eventRegister.php';
		}elseif($str == 14){
			$url = 'common/EmailThisOverlay.php';
		}elseif($str == 15){
			$url = 'common/listingThanksEmail.php';
		}elseif($str == 16){
			$url = 'common/listingThanksSMS.php';
		}elseif($str == 17){//Category Page Apply Now
			$url = 'common/Request-E-Brochure-Category.php';
		}elseif($str == 18){//Category Page Apply Now
			$url = 'common/Request-E-Brochure-Category-Login.php';
		}elseif($str == 19){//Category Page Apply Now
			$url = 'common/Request-E-Brochure-Category-Thankyou.php';
		}elseif($str == 20){//Compare Layer Email Overlay
			$url = 'common/Compare-Layer-Email-Overlay.php';
		}
		$data = array();
		if($validity == "false")
		{
			$data['multkeyname'] = $keyname;
		}
		else
		{
			$validity[0]['multkeyname'] = $keyname;
			$data = $validity[0];
		}
		$data['validateuser'] = $validity;
		$data['redirect1'] = $redirect1;
		$data['redirect2'] = $redirect2;
		$data['Category']=$Category;
		$data['studyAbroadResponse'] = $studyAbroadResponse;
		$data['OTPVerification'] = $OTPVerification;
		$data['ODBVerification'] = $ODBVerification;
		
//below line used for conversion tracking purpose--->MIS		
$data['trackingPageKeyId']= $this->input->post('trackingPageKeyId') != '' ? $this->input->post('trackingPageKeyId') : $this->input->post('tracking_keyid');
		
                if($str==17){
                    $encodedlistData 	= $this->input->post('listData');
                    $listData 	= unserialize(base64_decode($encodedlistData));
                    if( !empty($listData) && is_array($listData) && count($listData) > 1){
                        $displayForm = "";
                        $courseIds = array_keys($listData);
                        $national_course_lib = $this->load->library('listing/NationalCourseLib');
                        $dominantDesiredCourseData = $national_course_lib->getDominantDesiredCourseForClientCourses($courseIds);
                        foreach ($dominantDesiredCourseData as $key => $value) {
                            $dominantDesiredCourseData[$key]['name'] = $listData[$key];
                        }
                        $data['instituteCourses'] = $dominantDesiredCourseData;
                        $data['defaultCourse'] = ($courseId > 0)?$dominantDesiredCourseData[$courseId]['desiredCourse']:$dominantDesiredCourseData[$courseIds[0]]['desiredCourse'];
                        $data['defaultCategory'] = ($courseId > 0)?$dominantDesiredCourseData[$courseId]['categoryId']:$dominantDesiredCourseData[$courseIds[0]]['categoryId'];
                        $data['defaultCourseId'] = ($courseId > 0)?$courseId:$courseIds[0];
                    }
                    else if($courseId > 0) {
                        $national_course_lib = $this->load->library('listing/NationalCourseLib');
                        $dominantDesiredCourseData = $national_course_lib->getDominantDesiredCourseForClientCourses(array($courseId));
                        $data['desiredCourse'] = $dominantDesiredCourseData[$courseId]['desiredCourse'];
                        $data['fieldOfInterest'] = $dominantDesiredCourseData[$courseId]['categoryId'];
                        $data['courseIdSelected'] = $courseId;
                    }
                    $data['instituteId'] = $instituteId;
                    $data['isCategoryPage'] = $this->input->post('isCategoryPage');
                    $data['widget'] = 'ebrocher';
                    echo Modules::run('registration/Forms/showResponseRegisterFreeLayer', $data);
                }else{
                    $string = $this->load->view($url,$data,true);
                    echo $string;
                }
	}

	function getUserDetails() {
		$this->init();
		if(is_array($this->userStatus)){
			echo json_encode($this->userStatus);
		}else {
			echo 0;
		}
	}
	
	public function sendFreeBrochureToUser($userId, $courseObj, $sourcePage,$tracking_page_key) {
		try {
			$this->init();
			
			$user_data = $this->checkUserValidation();
			
			if(is_object($courseObj)) {
				$userInfo['user_id'] = $user_data[0]['userid'];
				$userInfo['course_id'] = $courseObj->getId();
			}
			else {
				$userInfo['user_id'] = $this->input->post('user_id');
				$userInfo['course_id'] = $this->input->post('course_id');
				
				$this->load->builder('ListingBuilder','listing');
				$listingBuilder = new ListingBuilder;
				$courseRepository = $listingBuilder->getCourseRepository();

				/*When course Id is invalid */
				if(empty($userInfo['course_id']) && !is_integer(intval($courseIdForFreeBrochure))){
					return false;
				}
				
				$course_model = $this->load->model('course/coursemodel');      
				$is_course_deleted = $course_model->getDeletedCourseInstituteById($userInfo['course_id']);
				
				if($is_course_deleted > 0) {
					return false;
				}
				
		
				$courseObj = $courseRepository->find($userInfo['course_id']);
				
			}
			if(!isset($tracking_page_key) || empty($tracking_page_key)){
				$tracking_page_key = $this->input->post('tracking_keyid', true);

				if( ($tracking_page_key == '' || $tracking_page_key == 0) && $this->input->post('trackingPageKeyId', true) != ''){ // So that if the tracking key is sent using a different get key name, it can be used
					$tracking_page_key = $this->input->post('trackingPageKeyId', true);
				}
			}

			$userInfo['downloadedFrom'] = $this->input->post('downloadedFrom');//sent when call is made from processfinalresponseform in processform.js
			
			if($userInfo['downloadedFrom']==""){
				$userInfo['downloadedFrom'] = $sourcePage;
			}
			if($userInfo['downloadedFrom'] == "request_salaryData"){
				$sourcePage = "request_salaryData";
			}else if($userInfo['downloadedFrom'] == "similar_institute_deb"){
				$sourcePage = "similar_institute_deb";
			}else if($userInfo['downloadedFrom'] == "compare_ebrochure"){
				$sourcePage = "COMPARE_EBrochure";
			}
			else if($userInfo['downloadedFrom'] == 'search_page' && $this->input->post('flag_check') == 'ND_SRP_Request_E_Brochure') {
				$sourcePage = 'ND_SRP_Request_E_Brochure';
			}

			/*When course Id is invalid */
			if(empty($userInfo['course_id']) && !(intval($userInfo['course_id']) > 0)){
				return false;
			}

			$brochureUrl_array = $this->getCourseEbrochure($userInfo['course_id']);
			//check if response was made already a day ago
			$listingmodel = $this->load->model('listing/listingmodel');
			$row = $listingmodel->getLastDayLead($userInfo['user_id'], $userInfo['course_id'], 'course' );
			

			//make entry in templmstable and templmsrequest
			$this->_insertTempLmsEntryForFreeCourse($courseObj, $sourcePage,$tracking_page_key);
			
			// check if the size of brochure to be sent is >5, if so then dont send the attached brochure rather send the link of the brochure
			//variable timings reason being it makes curl request
			$sizeOfBrochure = $this->getSizeOfBrochure($userInfo['course_id']);
			
			/*
			 * Sending Email now..
			 */

			// No need to send brochure to user, for viewing free listing.
			if(isset($_POST['preListingResponseFreeCourse']) && $_POST['preListingResponseFreeCourse'] == 1){
				//return; //Removing the check for not sending brochure on viewing free listing (LF-2960).
			}

			// No need to send brochure to user, for viewing free institute (LF-2960).
			if(isset($sourcePage) && $sourcePage == 'Institute_Viewed'){
				return;
			}

			if(!($_POST['preListingResponseFreeCourse'] == 1 || $sourcePage == 'Viewed_Listing_Pre_Reg' || $sourcePage == 'Viewed_Listing' || $sourcePage == 'Mob_Viewed_Listing_Pre_Reg')){
				//Update DesiredCourse
				$this->load->library('user/userLib');
				$userLib = new UserLib;
				$userLib->updateUserInterest($userInfo['user_id'], $courseObj);
			}

			if($sourcePage!='Compare_Email'){	//Send the Mail if this is not the compare page
			if( $sizeOfBrochure > (1024*1024*5) )
			{
					$cookie_str 			= $user_data[0]['cookiestr'];
					$cookie_str_array  			= explode("|", $cookie_str);
					
					$params 				= array();
					$params['courseName'] 		= $courseObj->getName();;
					$params['instituteName'] 		= $courseObj->getInstituteName();;
					$params['first_name'] 		= $user_data[0]['firstname'];;
					$params['listing_type'] 		= 'course';
					$params['template_type'] 		= 3;
					
					$params['usernameemail'] 		= $cookie_str_array[0];;
					
					$params['URL'] = $courseObj->getURL();
					//'https://www.shiksha.com/getListingDetail/'.$userInfo['course_id'].'/course';
					$params['listing_type_id']		= $userInfo['course_id'];
					$params['user_id']			= $user_data[0]['userid'];
					if( empty($row) )//if any leads exist within past 24 hour then do not send mail
					{
					$this->sendBrochureMailWithLink($params);
					}
					
			}
			else
			{		   
				if( empty($row) )//if any leads exist within past 24 hour then do not send mail
				{

				$this->sendBrochureEmailNotification($courseObj, $brochureUrl_array);
				}
							
			}
			}

			if($brochureUrl_array['BROCHURE_URL']) {
				$userInfo['brochureUrl'] = $brochureUrl_array['BROCHURE_URL'];
				if($sourcePage != 'RANKING_PAGE_REQUEST_EBROCHURE'){
					$this->_trackFreeBrochureDownload($userInfo);
				}
			}
	 } catch(Exception $e) {
			//$post_data = print_r($_POST,true);
		    //$server_data = print_r($_SERVER,true);
		    //mail('aditya.roshan@shiksha.com','exception',$post_data.$server_data);
	 }
	}
	
	private function _insertTempLmsEntryForFreeCourse($courseObj, $sourcePage,$tracking_page_key) {
		if(!is_object($courseObj) || $courseObj->isPaid()) {
		    return false;				
		}
		
		// load lms client library
		$this->load->library('lmsLib');
		$lms_client_object = new LmsLib();
		
		// prepare data to be inserted into
		$user_data = $this->checkUserValidation();
		$user_data = $user_data[0];
		$cookie_str = $user_data['cookiestr'];
		$cookie_str_array  = explode("|", $cookie_str);
		$email = $cookie_str_array[0];
		
		$data_array = array();
		$data_array['userId'] = $user_data['userid'];
		$data_array['displayName'] = $user_data['displayname'];
		$data_array['contact_cell'] = $user_data['mobile'];
		$listing_type = $data_array['listing_type'] = "course";
		$listingTypeId = $data_array['listing_type_id'] = $courseObj->getId();
		$data_array['contact_email'] = $email;

		// in case of multilocation institute get city& locality, then fetch its institute_location_id
		$localityInfo = json_decode(urldecode(str_replace("'","",$this->input->post('localityJSON'))),true);
		//error_log("AK responseLocationFReE loc".print_r($localityInfo,true));
		
		
		/**
		 * Check if the listing has custom locations
		 */
		$location = reset($localityInfo);
		$cityId 	= $location[0];
		$localityId 	= $location[1];
		global $listings_with_localities;
		$instituteId = $courseObj->getInstId();
		$isSMUListings = FALSE;
		$hasCustomLocality = FALSE;
		if($listing_type == 'course') {
			foreach($listings_with_localities as $key => $listings) {
				if($key == "COURSE_WISE_CUSTOMIZED_LOCATIONS" && in_array($listingTypeId, $listings)) {
					$hasCustomLocality = TRUE;
				}
				if($key == "SMU" && in_array($instituteId, $listings)) {
					$isSMUListings = TRUE;					
				}				
			}
		}
		
		if(!$hasCustomLocality){
			foreach($listings_with_localities as $key => $listings) {
				if($key == "COURSE_WISE_CUSTOMIZED_LOCATIONS")
				continue;
				
				if(in_array($instituteId, $listings)) {
					//error_log("AK inside lms responseLocation1");
					$hasCustomLocality = TRUE;
				}
			}
		}

		/**
		 * For custom locations, store response location date in responseLocationPref table
		 */ 
		if($hasCustomLocality) {
			if($cityId) {				

				if($isSMUListings){
					if(is_numeric($cityId)){
						$this->load->library('category_list_client');
						$categoryClient = new Category_list_client();
						$cityId = $categoryClient->getCityName($cityId);
					}
					if(is_numeric($localityId)){
						require_once FCPATH.'globalconfig/SMULocalityMapping.php';
						if(isset($SMULocalityMapping[$localityId])){
							$localityId = $SMULocalityMapping[$localityId];
						}
					}
				}
				$insertCustomLocationPref = true;

			}
		}
		else if(count($localityInfo)>0){
			
			$instituteId 	= $courseObj->getInstId();
			$locData	= array('cityId' 	=> $cityId 	,
						'localityId' 	=> $localityId 	,
						'instituteId' 	=> $instituteId );

			$nationalCourseLib = $this->load->library('listing/NationalCourseLib');

			$data_array['institute_location_id'] = $nationalCourseLib->getInstituteLocationIdByCityLocality($locData);

		}
		else{
			// insert head office otherwise
			$data_array['institute_location_id'] = $courseObj->getMainLocation()->getLocationId();
		}
                
                if(empty($data_array['institute_location_id'])){
                    // insert head office otherwise
                    $data_array['institute_location_id'] = $courseObj->getMainLocation()->getLocationId();
                }

		$data_array['sourcePage'] = $sourcePage;
		$data_array['tracking_page_key'] = $tracking_page_key;
		// insert call start
		//error_log("SRB was here finally".print_r($data_array,true));
		$response = $lms_client_object->insertResponseForFreeCourse($data_array);
		// $response['temp_lms_table_id']
		
		if($insertCustomLocationPref )
		{		$Ldata = array(
						'response_id'=>$response['temp_lms_table_id'],
						'city'=>$cityId,
						'locality'=>$localityId,
						'processed'=>'No'
					      );
				//error_log("inside lms responseLocation PREF".print_r($Ldata,true));
				if(isset($response['temp_lms_table_id']) && $response['temp_lms_table_id'] != NULL){
					$this->load->model('listing/institutemodel');
					$institutemodel = new institutemodel();
					$institutemodel->insertToResponseLocationPref($Ldata);
				}else{
					$toMail = "aditya.roshan@shiksha.com, harshit.mago@shiksha.com";
					$message = "Response Id is Null while making entry in Response Location Table for ".$courseObj->getId();
					mail($toMail,"Response Id is Null while making entry in Response Location Table", $message);
				}
				
		}
		return $response;
	}
	
	public function sendBrochureEmailNotification($courseObj,$brochureUrl) {
	
                //error_log('freemail1_'.print_r($brochureUrl,true));	
		/*
		 * The sending email code would be done with story LF-41 here..
		 */
		if(!empty($brochureUrl['BROCHURE_URL'])) {
			
			$attachement_id = $this->_createAttachement($courseObj, $brochureUrl);
                       // error_log('freemail2_'.$attachement_id); 
		}

		$user_data = $this->checkUserValidation();
		$cookie_str = $user_data[0]['cookiestr'];
		$cookie_str_array  = explode("|", $cookie_str);
		$email = $cookie_str_array[0];
		$params = array();
		$params['courseName'] = $courseObj->getName();
		$params['instituteName'] = $courseObj->getInstituteName();
		$params['first_name'] = $user_data[0]['firstname'];
		$params['listing_type'] = 'course';
		$params['listing_type_id'] = $courseObj->getId();
		
		
		if($attachement_id) {
			$params['template_type'] = 1;
		} else {
			$params['template_type'] = 2;
		}
		
		$params['usernameemail'] = $email;
		if($attachement_id) {
			$params['attachment'] = array($attachement_id);
		}
		$params['URL'] = $courseObj->getURL();
		//'https://www.shiksha.com/getListingDetail/'.$courseObj->getId().'/course';
		
		$this->load->model('categoryList/categorymodel');						
		$userCategoryDetails = $this->categorymodel->getCategoryDataByCourseId($courseObj->getId());
		$category_ids = array();$isFullTimeMBACategory = 'N';
		if(!empty($userCategoryDetails)) {
			foreach($userCategoryDetails as $userCategoryDetail) {
				$category_ids[] = $userCategoryDetail['category_id'];
			}
			if(in_array(23, $category_ids)) {
				$isFullTimeMBACategory = 'Y';
			}
		}
		
		if($isFullTimeMBACategory == 'Y') {
		    $params['mailer_name'] = 'NationalCourseDownloadBrochure'.$params['template_type'];
			$params['isFullTimeMBACategory'] = $isFullTimeMBACategory;
		    $email_content = $this->_emailContent($params);
		} else {
			$params['mailer_name'] = 'NationalCourseDownloadBrochure'.$params['template_type'];
			$email_content         = $this->_emailContent($params);
			// $subject               = $email_content['subject'];
			// $content               = $email_content['content'];
			// $alerts_client         = $this->load->library('alerts_client');

			// if($attachement_id) {
			// 				//error_log('freemail3_');
			// 	$response=$alerts_client->externalQueueAdd("12",ADMIN_EMAIL,$email,$subject,$content,"html",'','y',array($attachement_id));
			// } else {
			// 				//error_log('freemail4_');
			// 	$response=$alerts_client->externalQueueAdd("12",ADMIN_EMAIL,$email,$subject,$content,"html","0000-00-00 00:00:00");
			// }
		}
	}

	public function getCourseEbrochure($course_id) {
		$national_course_lib = $this->load->library('listing/NationalCourseLib');
		return $national_course_lib->getCourseBrochure($course_id,true);
	}

	private function _trackFreeBrochureDownload($userInfo) {
		$this->load->model('listing/coursemodel');
		$courseModel = new coursemodel();
		$noTrackForDownload = $this->input->post('noTrackForDownload');
		if($noTrackForDownload !=1){
		    $courseModel->trackFreeBrochureDownload($userInfo);    
		}
		
	}

	function getActiontypeForBrochureRequest($flag_check){
				//  Added by Amit K for ticket 870..
		if ($flag_check == 1 )
		    { $actiontype = 1; }
		else if($flag_check == 10)
		    {$actiontype = 10;}
		else
		    { $actiontype = 2;}

		if(isset($_POST['fromRecoMailer']) && $_POST['fromRecoMailer'] == 1) {
			$actiontype = 3;
		}
		if(isset($_POST['fromRecoProdMailer']) && $_POST['fromRecoProdMailer'] == 1) {
			$actiontype = 6;
		}
		// In case of auto listing response
		if(isset($_POST['autoListingResponse']) && $_POST['autoListingResponse'] == 1) {
			$actiontype = 4;
		}
		
		if(isset($_POST['shortlistResponse']) && $_POST['shortlistResponse'] == 1) {
			$actiontype = "User_ShortListed_Course";
		}
		
		if(isset($_POST['shortlistResponse']) && $_POST['shortlistResponse'] == 2) {
			$actiontype = "MOB_Course_Shortlist";
		}
		
		if(isset($_POST['shortlistResponse']) && $_POST['shortlistResponse'] == 3) {
			$actiontype = $_POST['sourcePage'];
		}
		
		if(isset($_POST['preRegListingResponse']) && $_POST['preRegListingResponse'] == 1 && $this->input->post('isMOB')) {
			$actiontype = "Mob_Viewed_Listing_Pre_Reg";
		}else if(isset($_POST['preRegListingResponse']) && $_POST['preRegListingResponse'] == 1){
			$actiontype = "Viewed_Listing_Pre_Reg";
		}
		
		if(isset($_POST['sourcePage']) && $_POST['sourcePage'] == "CATEGORY_RECOMMENDATION_PAGE") {
			$actiontype = "CATEGORY_RECOMMENDATION_PAGE";
		}

		$sourcePages = array(
			'LISTING_PAGE_RIGHT_RECOMMENDATION',
			'LISTING_PAGE_BOTTOM_RECOMMENDATION',
			'LP_Reco_ShowRecoLayer',
			'LP_Reco_AlsoviewLayer',
			'LP_Reco_SimilarInstiLayer',
			'CP_Reco_divLayer',
			'CP_Reco_popupLayer',
			'CoursePage_Reco',
			'Institute_Viewed',
			'OF_Request_E-Brochure',
			'MOB_CareerCompass_Shortlist'
		);

		if(isset($_POST['sourcePage']) && (in_array($_POST['sourcePage'], $sourcePages) || in_array($_POST['sourcePage'], $pageTypeResponseActionMapping) ) ){ // $pageTypeResponseActionMapping is global; $sourcePages has been added to make the code readable
			$actiontype = $_POST['sourcePage'];
		}
		
		if($flag_check == "SEARCH_REQUEST_EBROCHURE"){
			$actiontype = "SEARCH_REQUEST_EBROCHURE";
		}
		
		if($flag_check == "RANKING_PAGE_REQUEST_EBROCHURE"){
			$actiontype = "RANKING_PAGE_REQUEST_EBROCHURE";
		}
		
		if($flag_check == "CollegePredictor"){
			$actiontype = "CollegePredictor";
		}
                
		if($flag_check == "request_salaryData"){
			$actiontype = "request_salaryData";
		}
		
		if($flag_check == 'RESPONSE_MARKETING_PAGE') {
			$actiontype = 'RESPONSE_MARKETING_PAGE';
		}

		if($flag_check == 'LISTING_PAGE_EMAIL_SMS_CONTACT_DETAILS') {
			$actiontype = 'LISTING_PAGE_EMAIL_SMS_CONTACT_DETAILS';
		}

		if($flag_check == 'NATIONAL_PAGE') {
			$actiontype = 'NATIONAL_PAGE';
		}

		if($flag_check == 'D_MS_Request_e_Brochure') {
			$actiontype = 'D_MS_Request_e_Brochure';
		}
		
		if($isSimilarInstitutePage == 1){
			$actiontype = 'similar_institute_deb';
		}
		
		
		if((isset($_POST['autoCompareResponse']) && $_POST['autoCompareResponse'] == 1)) {
			$actiontype = "COMPARE_VIEWED";
		}
		
		if($flag_check == "compare_ebrochure"){
			$actiontype = "COMPARE_EBrochure";
		}
		
		if((isset($_POST['autoResponseLogoutCase']) && $_POST['autoResponseLogoutCase']==1) && ($_POST['sourcePage'] == 'COMPARE_VIEWED' || $_POST['sourcePage'] == 'MOB_COMPARE_VIEWED')){
			$actiontype = $_POST['sourcePage'];
		}
		
		if(isset($_POST['sourcePage']) && ($_POST['sourcePage'] == 'Applied' || $_POST['sourcePage'] == 'Applying' || $_POST['sourcePage'] == 'TakenAdmission')){
			$actiontype = $_POST['sourcePage'];
		}

		if($flag_check == "ND_SRP_Request_E_Brochure") {
			$actiontype = $flag_check;
		}
		return $actiontype;
	}

	function sendPaidBrochureToUser($courseForFreeBrochure, $addReqInfo, $addReqInfoForTitles){
		
		try {
		global $pageTypeResponseActionMapping;
		$email = $this->input->post('reqInfoEmail');
		$mobile = $this->input->post('reqInfoPhNumber');
		$firstName = $this->input->post('reqInfofirstName');
		$lastName = $this->input->post('reqInfolastName');
		$displayname = $this->input->post('reqInfofirstName');
		$institutePageIdentifier = $this->input->post('institutePageIdentifier');
		$institutePageIdentifier = isset($institutePageIdentifier) ? $institutePageIdentifier : 0;
		$courseInterestId = isset($_POST['listing_type_course']) ? $_POST['listing_type_course'] : 'institute';
		$courseInterest = isset($_POST['listing_type_id_course']) ? $_POST['listing_type_id_course'] : $_POST['listing_type_id'];
		//if flag_check =2 i.e. cat page, then this indicates if brochure was downloaded form compare slide or not
		$isCompareModeActive = $this->input->post('comparePage');
		$studyAbroad = $this->input->post('categoryPageAbroad');
		$isSimilarInstitutePage = $this->input->post('isSimilarInstitutePage');
		
		// aray of action types
		$action_type_array = array('NATIONAL_PAGE',5,4,2,6,'RANKING_PAGE_REQUEST_EBROCHURE','SEARCH_REQUEST_EBROCHURE','LP_Reco_SimilarInstiLayer','LP_Reco_AlsoviewLayer','LP_Reco_ShowRecoLayer','CP_Reco_popupLayer','CP_Reco_divLayer','CoursePage_Reco',"LISTING_PAGE_EMAIL_SMS_CONTACT_DETAILS", "Asked_Question_On_Listing","Asked_Question_On_Listing_MOB","D_MS_Ask","similar_institute_deb","Viewed_Listing_Pre_Reg","User_ShortListed_Course","MOB_Course_Shortlist","COMPARE_VIEWED","COMPARE_AskQuestion","COMPARE_EBrochure","Compare_Email",'MOB_COMPARE_VIEWED','D_MS_Request_e_Brochure', 'MOB_CareerCompass_Shortlist','Asked_Question_On_CCHome','Asked_Question_On_CCHome_MOB', 'ND_SRP_Request_E_Brochure');
		$actionsDefinedInConstant = array_values($pageTypeResponseActionMapping);
		$action_type_array = array_merge($action_type_array, $actionsDefinedInConstant);
		
		/* got array of institutes */
		$captchatext = $this->input->post('captchatext');
		$localityInfo = json_decode(urldecode(str_replace("'","",$this->input->post('localityJSON'))),true);

		$coordinates = isset($_POST['coordinates']) ? $this->input->post('coordinates') : '';
		$resolution = isset($_POST['resolution']) ? $this->input->post('resolution') : '';
		$sourceurl = isset($_POST['referer']) ? $this->input->post('referer') : '';
		$sourcename = isset($_POST['loginproductname']) ? $this->input->post('loginproductname') : '';

		$ListingClientObj = new Listing_client();
		$register_client = new Register_client();
		$alerts_client = new Alerts_client();
		$signedInUser = $this->userStatus;
		$appId = 1;
		$flag_check = $this->input->post('flag_check');
		$attachmentIdArray = array();
		$instituteTitleArray = array();
		
		$actiontype = $this->getActiontypeForBrochureRequest($flag_check);
		// set the downloadFrom variable required to track the source of downloaded brochure
		switch($actiontype){
			case 2: if($studyAbroad != 1){
				    if($isCompareModeActive==1)
					{$downloadedFrom  = 'national_category_compare_page';}
				    else
					{$downloadedFrom  = 'national_category_page';}
				}
				else{
				    if($isCompareModeActive==1)
					{$downloadedFrom  = 'study_abroad_category_compare_page';}
				    else
					{$downloadedFrom  = 'study_abroad_category_page';}
				}
				break;
			case 'D_MS_Request_e_Brochure' : $downloadedFrom = 'D_MS_Request_e_Brochure';break;
			case 'SEARCH_REQUEST_EBROCHURE': $downloadedFrom  = 'search_page';break;
			case 'NATIONAL_PAGE': $downloadedFrom  = "national_listings";break;
			case 'LP_Reco_SimilarInstiLayer':
			case 'LP_Reco_AlsoviewLayer':
			case 'LP_Reco_ShowRecoLayer':
			case 'CP_Reco_popupLayer':
			case 'CP_Reco_divLayer': $downloadedFrom = "recommendation_widget";
		}
		
		if(is_array($signedInUser)) {
			$register_client->userInfoSystemPoint_Client($signedInUser[0]['userid'], 'requestInfoCourse');
		}
		
		$insTitleName = '';
		foreach ($addReqInfo as $key => $value) {
			$addReqInfoVars = array();
			$addReqInfoVars['listing_type_id'] = isset($value[3]) ? $value[3] : $key;
			$addReqInfoVars['listing_type'] = isset($value[4]) ? htmlspecialchars($value[4]) : $value[2];
			$addReqInfoVars['listing_title'] = htmlspecialchars($value[1]);
			$addReqInfoVars['listing_Url'] = htmlspecialchars($value[0]);
			$addReqInfoVars['displayName'] = $signedInUser[0]['displayname'];
			$addReqInfoVars['contact_cell'] = $signedInUser[0]['mobile'];
			$addReqInfoVars['userId'] = $signedInUser[0]['userid'];
			$addReqInfoVars['contact_email'] = $email;
			$addReqInfoVars['userInfo'] = $signedInUser;
			$addReqInfoVars['widget'] = $this->input->post('widget');

			$addReqInfoVars['preferred_city'] = urldecode($localityInfo[$key][0]);
			$addReqInfoVars['preferred_locality'] = urldecode($localityInfo[$key][1]);

			$tracking_page_key = isset($_POST['tracking_keyid']) ? $this->input->post('tracking_keyid'):0;

			if($tracking_page_key == 0 && $this->input->get_post('trackingPageKeyId') != ''){ // So that if the tracking key is sent using a different get key name, it can be used
				$tracking_page_key = $this->input->get_post('trackingPageKeyId');
			}


			$addLeadStatus = $this->addLead($signedInUser, $addReqInfoVars,$actiontype,$tracking_page_key);
			
			Modules::run('listing/ListingPage/callMeNow',$key,$addReqInfoVars['listing_type_id'],$signedInUser[0]['mobile'],$actiontype);
			$attachmentId = $alerts_client->getAttachmentId(12,$key,htmlspecialchars($value[2]),'E-Brochure');
			if($attachmentId != "")
			{
				$instituteTitleArray[] = "<a href='".$addReqInfoVars['listing_Url']."'>".urldecode($addReqInfoForTitles[$key][1])."</a>";
				$attachmentIdArray[] = $attachmentId[0]['id'];
			}
			$insURLArray = getSeoUrl($key, 'institute', urldecode($addReqInfoForTitles[$key][1]));
			$insTitleName .= '<a href="'.$insURLArray.'">'.urldecode($addReqInfoForTitles[$key][1]).'</a><br />';

			$recomendationflag = 'unset';
			if(!isset($value[3]) )
			{
				$recomendationflag = 'set';
			}
		}

		// bypassing this check as mail needs to be sent in case of Viewed_listing response
		if($actiontype != 'Institute_Viewed' ){

			for($i=0;$i<count($insURLArray);$i=$i+5) {
			    //get the uploaded brochure related data
			    $brochureData = $ListingClientObj->getUploadedBrochure($appId,$addReqInfoVars['listing_type'],$addReqInfoVars['listing_type_id']);
			    $userInfo['user_id']   = $signedInUser[0]['userid'];
			    $userInfo['course_id'] = $addReqInfoVars['listing_type_id'];
			    $userInfo['brochureUrl'] = $brochureData['brochureURL'];

			    //if brochure aint uploaded, get data of generated one...
			    if(!(is_array($brochureData) && isset($brochureData['brochureURL']) && !empty($brochureData['brochureURL']) ))
			    {
				$listingebrochuregenerator = $this->load->library('listing/ListingEbrochureGenerator');
				$listingebrochureobject = new ListingEbrochureGenerator();
				$GeneratedBrochureLink = $listingebrochureobject->getEbrochureURL($addReqInfoVars['listing_type'],$addReqInfoVars['listing_type_id']);
				$userInfo['brochureUrl'] =  $GeneratedBrochureLink['BROCHURE_URL'];
			    }
			    if($addLeadStatus['action'] != "Viewed_Listing" && $addLeadStatus['action'] != "Viewed_Listing_Pre_Reg" && $userInfo['brochureUrl'] !="" && in_array($actiontype,$action_type_array))
						{
						    if($downloadedFrom != "")
						    {
							$userInfo['downloadedFrom'] = $downloadedFrom;
						    }
						    else{
							$userInfo['downloadedFrom'] = $addLeadStatus['action'];
						    }
						    if(!($actiontype ==6 && $_REQUEST['downloadedFrom']!='reco_mailer') && $actiontype != "RANKING_PAGE_REQUEST_EBROCHURE" ){ // in case of reco mailer, and download is not required do not track..
							$this->_trackFreeBrochureDownload($userInfo);
						    }
						}

				if(!($addLeadStatus['action'] == "Viewed_Listing" || $addLeadStatus['action'] == "Viewed_Listing_Pre_Reg" || $addLeadStatus['action'] == 'Mob_Viewed_Listing_Pre_Reg')){
					//Update DesiredCourse 
					$this->load->library('user/userLib');
					$userLib = new UserLib;
					$userLib->updateUserInterest($signedInUser[0]['userid'], $courseForFreeBrochure);
				}

				// Do not send the brochure mail if already a day older reponse already exists as suggested by Aditya
				if( $addLeadStatus['leadId'] == 1 && $institutePageIdentifier != 1 )
				    continue;
			    

				$data=array();
					
				$subject = "Your application has been submitted successfully";
					
				$instituteName = $brochureData['instituteName'];
					
				if($addReqInfoVars['listing_type'] == 'course') {

					$courseName = $brochureData['courseName'];

					$content = 'Dear '.$firstName.','.'<br />Please find attached the e-brochure for '.htmlentities($courseName).'-'.htmlentities($instituteName).'.<br /><br />You can also read more details about the '.htmlentities($courseName).'  on <a href="'.SHIKSHA_HOME.'/getListingDetail/'.$addReqInfoVars['listing_type_id'].'/course">shiksha.com</a>.';

				}
				else {

					$content = 'Dear '.$firstName.','.'<br />Please find attached the e-brochure for '.
					htmlentities($instituteName).'.<br />You can also read more details about the '.htmlentities($instituteName).'  on <a href="'.SHIKSHA_HOME.'/getListingDetail/'.$addReqInfoVars['listing_type_id'].'/institute">shiksha.com</a>.';
				}
					
				/**
				* Section to send the mail in case of brochure having size greater than 5MB
				**/
				if(empty($addReqInfoVars['listing_type_id'])){
					return false;
				}
				
				// check if the size of brochure to be sent is >5, if so then dont send the attached brochure rather send the link of the brochure
				$sizeOfBrochure = $this->getSizeOfBrochure($addReqInfoVars['listing_type_id']);
				if( $sizeOfBrochure > (1024*1024*5))
				{
				    $params 				= array();
				    $params['courseName'] 		= $courseName;
				    $params['instituteName'] 		= $instituteName;
				    $params['first_name'] 		= $firstName;
				    $params['listing_type'] 		= $addReqInfoVars['listing_type'];
				    $params['template_type'] 		= 3;
				    $params['usernameemail'] 		= $email;
				    $params['URL'] 			= SHIKSHA_HOME.'/getListingDetail/'.$addReqInfoVars['listing_type_id'].'/course';
				    $params['listing_type_id']		= $addReqInfoVars['listing_type_id'];
				    $params['user_id']			= $signedInUser[0]['userid'];
					
				    // send the link of the brochure instead of attachment
				    if($params['listing_type'] == 'course')
				    {
					$this->sendBrochureMailWithLink($params);
					continue;
				    }
				}
				/************************************/
				
				$listingebrochuregenerator = $this->load->library('listing/ListingEbrochureGenerator');
				$listingebrochureobject = new ListingEbrochureGenerator();

				$GeneratedBrochureLink = $listingebrochureobject->getEbrochureURL($addReqInfoVars['listing_type'],$addReqInfoVars['listing_type_id']);

				// This Case exist if the course or its institute have its requested E-Brochure uploaded
				if(is_array($brochureData) && isset($brochureData['brochureURL']) && !empty($brochureData['brochureURL']) ) {

					$brochureURL = $brochureData['brochureURL'];
					$filecontent = base64_encode(file_get_contents($brochureURL));
					$fileExtension = end(explode(".",$brochureURL));

					$this->load->library('Ldbmis_client');
					$misObj = new Ldbmis_client();
					$type_id = $misObj->updateAttachment($appId);

					$attachmentName = str_replace(" ",'_',$instituteName);
					$attachmentName = preg_replace("/[^a-zA-Z0-9_]+/", "", $attachmentName);
					$attachmentName = $attachmentName.".".$fileExtension;

					$filename = $title.".".$arr[$var];
					$attachmentId = $alerts_client->createAttachment("12",$type_id,'COURSE','E-Brochure','',$attachmentName,$fileExtension,'true', $brochureURL);
                    error_log('attachement'.$attachmentId);
					$attachmentArray=array($attachmentId);
					$data['content'] = $content;
					$data['usernameemail'] = $email;
						
					if(in_array($actiontype,$action_type_array)) {

						$email_content = array();
						$params = array();

						$params['courseName'] = $courseName;
						$params['instituteName'] = $instituteName;
						$params['first_name'] = $firstName;
						$params['listing_type'] = $addReqInfoVars['listing_type'];
						$params['template_type'] = 1;
						$params['usernameemail'] = $email;
						$params['listing_type_id'] = $addReqInfoVars['listing_type_id'];

						if($params['listing_type'] == 'course') {
							$params['URL'] = $courseForFreeBrochure->getURL();
							//'https://www.shiksha.com/getListingDetail/'.$addReqInfoVars['listing_type_id'].'/course';
						} else {
							$params['URL'] = SHIKSHA_HOME.'/getListingDetail/'.$addReqInfoVars['listing_type_id'].'/institute';
						}

						if(!empty($attachmentArray)) {
							$params['attachment'] = $attachmentArray;
						}
						$this->load->model('categoryList/categorymodel');						
						$userCategoryDetails = $this->categorymodel->getCategoryDataByCourseId($userInfo['course_id']);
						$category_ids = array();$isFullTimeMBACategory = 'N';
						if(!empty($userCategoryDetails)) {
							foreach($userCategoryDetails as $userCategoryDetail) {
								$category_ids[] = $userCategoryDetail['category_id'];
							}
							if(in_array(23, $category_ids)) {
								$isFullTimeMBACategory = 'Y';
							}
						}
						if($isFullTimeMBACategory == 'Y') {
						    $params['mailer_name'] = 'NationalCourseDownloadBrochure'.$params['template_type'];
						    $params['isFullTimeMBACategory'] = $isFullTimeMBACategory;
						    $email_content = $this->_emailContent($params);
						} else {
							$params['mailer_name'] = 'NationalCourseDownloadBrochure'.$params['template_type'];
						    $email_content = $this->_emailContent($params);
						}

					} else {

						$content = $this->load->view('user/PasswordChangeMail',$data,true);
						$response=$alerts_client->externalQueueAdd("12",ADMIN_EMAIL,$email,$subject,$content,"html",'','y',$attachmentArray);

					}
				}
					
				// This case is being added for E-brochure generated via the script

				elseif( $GeneratedBrochureLink['RESPONSE'] == 'BROCHURE_FOUND'){

					$Attachment_Brochure_URL =  $GeneratedBrochureLink['BROCHURE_URL'];

					$this->load->library('Ldbmis_client');
					$misObj = new Ldbmis_client();
					$type_id = $misObj->updateAttachment($appId);

					$attachmentName = str_replace(" ",'_',$instituteName);
					$attachmentName = preg_replace("/[^a-zA-Z0-9_]+/", "", $attachmentName);
					$fileExtension = 'pdf';
					$attachmentName = $attachmentName.".".$fileExtension;

					$filename = $title.".".$arr[$var];

					$attachmentId = $alerts_client->createAttachment("12",$type_id,'COURSE','E-Brochure','',$attachmentName,$fileExtension,'true',$Attachment_Brochure_URL);
                                        error_log('attachement1'.$attachmentId);
					$attachmentArray=array($attachmentId);
					$data['content'] = $content;
					$data['usernameemail'] = $email;
						
					if(in_array($actiontype,$action_type_array)) {
							
						$email_content = array();
						$params = array();

						$params['courseName'] = $courseName;
						$params['instituteName'] = $instituteName;
						$params['first_name'] = $firstName;
						$params['listing_type'] = $addReqInfoVars['listing_type'];
						$params['template_type'] = 1;
						$params['usernameemail'] = $email;
						$params['listing_type_id'] = $addReqInfoVars['listing_type_id'];

						if($params['listing_type'] == 'course') {
						    $params['URL'] = $courseForFreeBrochure->getURL();
							//'https://www.shiksha.com/getListingDetail/'.$addReqInfoVars['listing_type_id'].'/course';
						} else {
							$params['URL'] = SHIKSHA_HOME.'/getListingDetail/'.$addReqInfoVars['listing_type_id'].'/institute';
						}
						
						// For tracking the Download of brochure
						$userInfo['user_id']   = $signedInUser[0]['userid'];
						$userInfo['course_id'] = $addReqInfoVars['listing_type_id'];

						if(!empty($attachmentArray)) {
							$params['attachment'] = $attachmentArray;
						}
						$this->load->model('categoryList/categorymodel');						
						$userCategoryDetails = $this->categorymodel->getCategoryDataByCourseId($userInfo['course_id']);
						$category_ids = array();$isFullTimeMBACategory = 'N';
						if(!empty($userCategoryDetails)) {
							foreach($userCategoryDetails as $userCategoryDetail) {
								$category_ids[] = $userCategoryDetail['category_id'];
							}
							if(in_array(23, $category_ids)) {
								$isFullTimeMBACategory = 'Y';
							}
						}
						
						if($isFullTimeMBACategory == 'Y') {
						    $params['mailer_name'] = 'NationalCourseDownloadBrochure'.$params['template_type'];
						    $params['isFullTimeMBACategory'] = $isFullTimeMBACategory;
						    $email_content = $this->_emailContent($params);
						} else {
						    $params['mailer_name'] = 'NationalCourseDownloadBrochure'.$params['template_type'];
						    $email_content = $this->_emailContent($params);
						} 

					} else {

						$content = $this->load->view('user/PasswordChangeMail',$data,true);
						$response=$alerts_client->externalQueueAdd("12",ADMIN_EMAIL,$email,$subject,$content,"html",'','y',$attachmentArray);
					}

				}
					
				else{
					/*
					 * Href formation link for multiple instititutes selected together at compare page
					 */
					$insTitleName = '';
					foreach($addReqInfo as $key => $value){

						$RequestedLinkInformation['listing_type_id'] = isset($value[3]) ? $value[3] : $key;
						$RequestedLinkInformation['listing_type'] = isset($value[4]) ? htmlspecialchars($value[4]) : $value[2];
						$brochureData = $ListingClientObj->getUploadedBrochure($appId,$RequestedLinkInformation['listing_type'],$RequestedLinkInformation['listing_type_id']);

						$instituteName = $brochureData['instituteName'];

						if($RequestedLinkInformation['listing_type'] == 'course')
						{
							$insTitleName .= '<a href="'.SHIKSHA_HOME.'/getListingDetail/'.$RequestedLinkInformation['listing_type_id'].'/course">'.$instituteName.'</a>.';
							$insTitleName .= '<br />';
						}
					}
					//No Mails for auto responses /// added please don't remove this check..otherwise you will be fined
					$content = 'Dear '.$firstName.'<br /><br />Your request for further information has been successfully submitted to the following institute<br /><br />'.$insTitleName.'<br /><br />The institute(s) shall get in touch with you soon at your contact details mentioned by you to proceed with admission process.<br /><br />Your Email - '.$email.'<br />Your Phone Number - '.$mobile.'<br /><br />If the above is not correct kindly login to Shiksha.com account and click on account and setting to update the same.<br /><br />Thank you for using <a href="'.SHIKSHA_HOME.'">Shiksha.com</a> for your education search.';
					$data['usernameemail'] = $email;
					$data['content'] = $content;
						
					if(in_array($actiontype,$action_type_array)) {
							
							
						$email_content = array();
						$params = array();

						$params['courseName'] = $courseName;
						$params['instituteName'] = $instituteName;
						$params['first_name'] = $firstName;
						$params['listing_type'] = $addReqInfoVars['listing_type'];
						$params['template_type'] = 2;
						$params['usernameemail'] = $email;
						$params['listing_type_id'] = $addReqInfoVars['listing_type_id'];

						if($params['listing_type'] == 'course') {
						    $params['URL'] = $courseForFreeBrochure->getURL();
							//'https://www.shiksha.com/getListingDetail/'.$addReqInfoVars['listing_type_id'].'/course';
						} else {
							$params['URL'] = SHIKSHA_HOME.'/getListingDetail/'.$addReqInfoVars['listing_type_id'].'/institute';
						}

						$this->load->model('categoryList/categorymodel');						
						$userCategoryDetails = $this->categorymodel->getCategoryDataByCourseId($userInfo['course_id']);
						$category_ids = array();$isFullTimeMBACategory = 'N';
						if(!empty($userCategoryDetails)) {
							foreach($userCategoryDetails as $userCategoryDetail) {
								$category_ids[] = $userCategoryDetail['category_id'];
							}
							if(in_array(23, $category_ids)) {
								$isFullTimeMBACategory = 'Y';
							}
						}
						if($isFullTimeMBACategory == 'Y') {
						    $params['mailer_name'] = 'NationalCourseDownloadBrochure'.$params['template_type'];
						    $params['isFullTimeMBACategory'] = $isFullTimeMBACategory;
						    $email_content = $this->_emailContent($params);
						} else {
						    $params['mailer_name'] = 'NationalCourseDownloadBrochure'.$params['template_type'];
						    $email_content = $this->_emailContent($params);
						}
					} else {

						$content = $this->load->view('user/PasswordChangeMail',$data,true);
						$response=$alerts_client->externalQueueAdd("12",ADMIN_EMAIL,$email,$subject,$content,"html","0000-00-00 00:00:00");
					}
						
				}

			}

		}
	   } catch(Exception $e) {
		   //$post_data = print_r($_POST,true);
		   //$server_data = print_r($_SERVER,true);
		   //mail('aditya.roshan@shiksha.com','exception',$post_data.$server_data);
		   
	   }
	}

	function getBrochureRequest() {
		
		$defaultData = $_POST;
		$defaultData['HTTP_REFERER'] = $_SERVER['HTTP_REFERER'];
		$defaultData['SCRIPT_URI'] = $_SERVER['SCRIPT_URI'];
		mail('naveen.bhola@shiksha.com','getBrochureRequest API called at '.date('Y-m-d H:i:s'), '<br/>Data <br/>'.print_r($defaultData, true));

		exit;

		try {
			$this->init();
			global $pageTypeResponseActionMapping;
			$addReqInfo = array();
			
			$addReqInfo = json_decode(urldecode($this->input->post('jSON')),true);
			$addReqInfoForTitles = json_decode($_REQUEST['jSON'],true);
			
			$listingIdForFreeBrochure = key($addReqInfo);
			
			$listingTypeCheck = isset($addReqInfo[$listingIdForFreeBrochure][4]) ? htmlspecialchars($addReqInfo[$listingIdForFreeBrochure][4]) : $addReqInfo[$listingIdForFreeBrochure][2];
			if($listingTypeCheck == 'institute') {
				return;
			}
			
			$courseIdForFreeBrochure = isset($addReqInfo[$listingIdForFreeBrochure][3]) ? $addReqInfo[$listingIdForFreeBrochure][3] : $listingIdForFreeBrochure;
			
			//  added for 500 errors		

			if(empty($courseIdForFreeBrochure) || intval($courseIdForFreeBrochure) < 1) {
						return;
			}
				$course_model = $this->load->model('listing/coursemodel');      
			$is_course_deleted = $course_model->getDeletedCourseInstituteById($courseIdForFreeBrochure);

			if($is_course_deleted > 0) {
				return false;
			}

			$this->load->builder('ListingBuilder','listing');
			$listingBuilder = new ListingBuilder;
			$courseRepository = $listingBuilder->getCourseRepository();
				
			// $courseForFreeBrochure = $courseRepository->find($courseIdForFreeBrochure);
			$courseForFreeBrochure = $courseRepository->getDataForMultipleCourses(array($courseIdForFreeBrochure),'basic_info|head_location');
			$courseForFreeBrochure = $courseForFreeBrochure[$courseIdForFreeBrochure];

			if((is_object($courseForFreeBrochure)) && ($courseForFreeBrochure instanceof AbroadCourse)) {
				
				if(!$courseForFreeBrochure->isPaid()) {
					$flag_check = $this->input->post('flag_check');
					$sourcePage = $_POST['sourcePage'];
					if(empty($sourcePage)){
						$sourcePage = $flag_check;
					}
					if($flag_check == "request_salaryData"){
						$actiontype = "request_salaryData";
					}
					if($this->input->post('preRegListingResponse') == 1 && $this->input->post('isMOB')){
						$sourcePage = 'Mob_Viewed_Listing_Pre_Reg';
					}else if($this->input->post('preRegListingResponse') == 1){
						$sourcePage = 'Viewed_Listing_Pre_Reg';
					}
					if(isset($_POST['autoListingResponse']) && $_POST['autoListingResponse'] == 1) {
						$sourcePage = 'Viewed_Listing';
					}
					$tracking_page_key = isset($_POST['tracking_keyid']) ? $this->input->post('tracking_keyid'):0;
					$this->sendFreeBrochureToUser(null, $courseForFreeBrochure, $sourcePage, $tracking_page_key);
					return;
					
				}else{
					 $this->sendPaidBrochureToUser($courseForFreeBrochure, $addReqInfo, $addReqInfoForTitles);
					return;
				}
				
			}
	   } catch(Exception $e) {
		   
		   $post_data = print_r($_POST,true);
		   $server_data = print_r($_SERVER,true);
		   mail('harshit.mago@shiksha.com','get brochure request exception',$post_data.$server_data);
		}
	
	}


	public function _emailContent($params) {

		$response_array = array();
		if($params['listing_type'] == 'course') {				

		$exclusionCatIds = array('28','29','30','31');		//exclude these sub cats for new emails

		$nationalcourselib = $this->load->library('listing/NationalCourseLib');
		$courseCategoryData = $nationalcourselib->getDominantSubCategoryForCourse($params['listing_type_id']);
	

		if(in_array($courseCategoryData['dominant'], $exclusionCatIds)){
			$newMailFlag = false;
		} else{
			$this->load->model('categoryList/categorymodel');						
			$parentCategory = $this->categorymodel->getCategory($courseCategoryData['dominant']);

			if($parentCategory['parentId'] == 3){			//include parentid =3 only for new email
				$newMailFlag = true;
			} else{
				$newMailFlag = false;
			}
		}

		unset($courseCategoryData);
		unset($parentCategory);
            
	        if($newMailFlag) {

				$response_array['content'] = Modules::run('systemMailer/SystemMailer/sendRequestBrochureMailer',$params['usernameemail'],$params, $params['attachment']);

			} else {				
				$course = $params['courseName'];	
				$institute = $params['instituteName'];
				
				if(strlen($params['courseName'])>40) {	
					$course = substr($params['courseName'], 0,38);
					$course = $course."..";
				}

				$response_array['subject'] = "See ".$institute." E-Brochure for ".($course)." you requested for";
				//$response_array['subject'] = "E-brochure of ".($course)." requested by you.";
			
				$response_array['content'] = $this->load->view('user/user_response_national_course_'.$params['template_type'],$params,true);

				$params['content'] = $response_array['content'];
				$params['subject'] = $response_array['subject'];
				
				// mailer tracking api call
				Modules::run('systemMailer/SystemMailer/sendRequestBrochureNationalMailer',$params['usernameemail'],$params, $params['attachment']);

			}

		} else if($params['listing_type'] == 'institute') {

			// to do
		}

		// error_log('data_is'.print_r($response_array,true));

		return $response_array;
	}
	
	private function _createAttachement($course_obj,$brochureUrl_array) {

  	        //error_log('theeeeee'.print_r($course_obj,true));
         	$count = count($brochureUrl_array);
		if(empty($course_obj) || $count = 0) {
			return false;
		}

		$alerts_client = $this->load->library('alerts_client');
		$misObj = $this->load->library('Ldbmis_client');
		$appId = 1;
                error_log('theeeeee'.print_r($brochureUrl_array,true));
		if($brochureUrl_array['generated'] == 'CMS') {
			$brochureURL = $brochureUrl_array['BROCHURE_URL'];
			$filecontent = base64_encode(file_get_contents($brochureURL));
			$fileExtension = end(explode(".",$brochureURL));
				
			$type_id = $misObj->updateAttachment($appId);

			$attachmentName = str_replace(" ",'_',$course_obj->getInstituteName());
			$attachmentName = preg_replace("/[^a-zA-Z0-9_]+/", "", $attachmentName);
			$attachmentName = $attachmentName.".".$fileExtension;

			//$filename = $title.".".$arr[$var];
			
			
			$attachmentId = $alerts_client->createAttachment("12",$type_id,'COURSE','E-Brochure','',$attachmentName,$fileExtension,'true', $brochureURL);
                        error_log('insideif1'.$attachmentId);

		} else if($brochureUrl_array['generated'] == 'SHIKSHA') {
				
			$Attachment_Brochure_URL =  $brochureUrl_array['BROCHURE_URL'];
			
			$type_id = $misObj->updateAttachment($appId);

			$attachmentName = str_replace(" ",'_',$course_obj->getInstituteName());
			$attachmentName = preg_replace("/[^a-zA-Z0-9_]+/", "", $attachmentName);
			$fileExtension = 'pdf';
			$attachmentName = $attachmentName.".".$fileExtension;

			//$filename = $title.".".$arr[$var];
			$attachmentId = $alerts_client->createAttachment("12",$type_id,'COURSE','E-Brochure','',$attachmentName,$fileExtension,'true',$Attachment_Brochure_URL);
			error_log('insideif3'.$attachmentId);	
		}
		
		return $attachmentId;
	}

	function generateBulkLead()
	{
		$this->validateCron();
		$this->init();
		$appId = 12;
		$LmsClientObj = new LmsLib();
		$addLeadStatus = $LmsClientObj->generateBulkLead($appId);
	}

	private function sendWelcomeMailToNewUser($email, $password, $addReqInfo,$addResult,$actiontype,$userinfo) {
		$this->init();
		$alerts_client = new Alerts_client();
		$data = array();
		$isEmailSent=0;
		try {
			$subject = "Your Shiksha Account has been generated";
			$data['usernameemail'] = $email;
			$data['userpasswordemail'] = $password;
			$content = $this->load->view('user/RegistrationMail',$data,true);
			$response=$alerts_client->externalQueueAdd("12",ADMIN_EMAIL,$email,$subject,$content,$contentType="html");

			/* For Shiksha Inbox. */
			$this->load->library('Mail_client');
			$mail_client = new Mail_client();
			$receiverIds = array();
			array_push($receiverIds,$addResult['status']);
			$body = $content;
			$content = 0;
			$sendmail = $mail_client->send($appId,1,$receiverIds,$subject,$body,$content);

		} catch (Exception $e) {
			// throw $e;
			error_log('Error occoured sendWelcomeMailToNewUser' .
			$e,'MultipleApply');
		}
	}

	private function addLead($signedInUser,$addReqInfo,$actiontype,$tracking_page_key) {
		// error_log('HIHIHIHINEH');
		$this->init();
		$appId = 1;
		$LmsClientObj = new LmsLib();
		$ListingClientObj = new Listing_client();
		global $pageTypeResponseActionMapping;
		
		if ($actiontype == 2 ) {
			if($addReqInfo['widget'] == 'onlineForm') {
				$addReqInfo['action'] = "Online_Application_Started";
			} else {
				$addReqInfo['action'] = "Request_E-Brochure";
			}
		} else if ($actiontype == 3 ) {
			$addReqInfo['action'] = "Mailer_Alert"; // In case of Recommendation mailer referrer
		} else if ($actiontype == 6 ) {
			$addReqInfo['action'] = "reco_widget_mailer"; // In case of Recommendation Product mailer referrer
		} else if ($actiontype == 4 ) {
			$addReqInfo['action'] = "Viewed_Listing"; // In case of auto listing response
		} else if ($actiontype == 5 ) {
			$addReqInfo['action'] = "Compare_Email"; // In case of Compare Email
		} else if ($actiontype == 10 ) {
			$addReqInfo['action'] = "Listing-Photos"; // In case of Compare Email
		} else if ($actiontype == "SEARCH_REQUEST_EBROCHURE") { // Request e-brochure from search page
			$addReqInfo['action'] = "SEARCH_REQUEST_EBROCHURE";
		} else if ($actiontype == "RANKING_PAGE_REQUEST_EBROCHURE") { // Request e-brochure from rankingpage
			$addReqInfo['action'] = "RANKING_PAGE_REQUEST_EBROCHURE";
		} else if ($actiontype == "CATEGORY_RECOMMENDATION_PAGE") { // Request e-brochure from recommendation page which gets open after category page.
			$addReqInfo['action'] = "reco_after_category";
		} else if ($actiontype == "LP_Reco_ShowRecoLayer" || $actiontype == "LP_Reco_AlsoviewLayer" || $actiontype == "LP_Reco_SimilarInstiLayer") {
			$addReqInfo['action'] = "LP_Reco_ReqEbrochure";
		}else if ($actiontype == "CP_Reco_popupLayer" || $actiontype == "CP_Reco_divLayer") {
			$addReqInfo['action'] = "CP_Reco_ReqEbrochure";
		}else if ($actiontype == "CoursePage_Reco") {
			$addReqInfo['action'] = "CoursePage_Reco";
		}else if ($actiontype == "RESPONSE_MARKETING_PAGE") {
			$addReqInfo['action'] = "RESPONSE_MARKETING_PAGE";
		} else if($actiontype == 'LISTING_PAGE_RIGHT_RECOMMENDATION' ||  $actiontype == 'LISTING_PAGE_BOTTOM_RECOMMENDATION' || $actiontype == 'LISTING_PAGE_EMAIL_SMS_CONTACT_DETAILS' || $actiontype == 'Institute_Viewed' || $actiontype == 'CollegePredictor' || $actiontype == "request_salaryData" || $actiontype == 'OF_Request_E-Brochure') {
			$addReqInfo['action'] = $actiontype;
		}
		else if($actiontype == 'similar_institute_deb'){
			$addReqInfo['action'] = 'similar_institute_deb';
		}
		else if($actiontype == 'Viewed_Listing_Pre_Reg') {
			$addReqInfo['action'] = 'Viewed_Listing_Pre_Reg';
		}
		else if($actiontype == 'Mob_Viewed_Listing_Pre_Reg') {
			$addReqInfo['action'] = 'Mob_Viewed_Listing_Pre_Reg';
		}
		else if($actiontype == 'User_ShortListed_Course') {
			$addReqInfo['action'] = 'User_ShortListed_Course';
		}
		else if(in_array($actiontype, $pageTypeResponseActionMapping)) {
			$addReqInfo['action'] = $actiontype;
		}
		else if($actiontype == 'MOB_CareerCompass_Shortlist') {
			$addReqInfo['action'] = 'MOB_CareerCompass_Shortlist';
		}
		else if($actiontype == 'MOB_Course_Shortlist') {
			$addReqInfo['action'] = 'MOB_Course_Shortlist';
		}
		else if($actiontype == 'COMPARE_VIEWED') {
                        $addReqInfo['action'] = "COMPARE_VIEWED";
                }
		else if($actiontype == 'COMPARE_EBrochure') {
                        $addReqInfo['action'] = "COMPARE_EBrochure";
                }
		else if($actiontype == 'MOB_COMPARE_EMAIL') {
                        $addReqInfo['action'] = "MOB_COMPARE_EMAIL";
                }
		else if($actiontype == 'MOB_COMPARE_VIEWED') {
                        $addReqInfo['action'] = "MOB_COMPARE_VIEWED";
                }
		else if($actiontype == 'D_MS_Request_e_Brochure') {
                        $addReqInfo['action'] = "D_MS_Request_e_Brochure";
                }
        else if(isset($_POST['sourcePage']) && ($_POST['sourcePage'] == 'Applied' || $_POST['sourcePage'] == 'Applying' || $_POST['sourcePage'] == 'TakenAdmission')){
			$addReqInfo['action'] = $_POST['sourcePage'];
				}                
		else if($actiontype == "ND_SRP_Request_E_Brochure") {
			$addReqInfo['action'] = $actiontype;
		}else if($actiontype == "Asked_Question_On_Listing") {
			$addReqInfo['action'] = $actiontype;
		}
		else {
			$addReqInfo['action'] = "GetFreeAlert";
		}

		if(isset($_POST['visitorSessionid']) && !empty($_POST['visitorSessionid'])){
			$addReqInfo['visitorSessionid'] = $_POST['visitorSessionid'];
			unset($_POST['visitorSessionid']);
		}

        if($addReqInfo['listing_type'] == 'course') {
        	if(empty($addReqInfo['listing_type_id'])){
        		return false;
        	}

            $this->load->builder('ListingBuilder','listing');
            $listingBuilder = new ListingBuilder;

            $courseRepository = $listingBuilder->getCourseRepository();
			$coursesObj = $courseRepository->getDataForMultipleCourses(array($addReqInfo['listing_type_id']),'basic_info|head_location');
			$coursesObj = $coursesObj[$addReqInfo['listing_type_id']];

			if(is_object($coursesObj)){
	            $isPaid = $coursesObj->isPaid();
			}else{
				$isPaid = "TRUE";
			}
        }
        else {
                $isPaid = "TRUE";
        }
         
                
        $addReqInfo['tracking_page_key'] = $tracking_page_key; 
		//Add user to Temp table for collective lead generation
		
		if($isPaid=="TRUE"){
			$addLeadStatus = $LmsClientObj->insertTempLead($appId,$addReqInfo);
		}

		$addReqInfo['userInfo'] = json_encode($signedInUser);
		$addReqInfo['sendMail'] = false;
		$addReqInfo['tempLmsRequest'] = $addLeadStatus['leadId'];

		if($isPaid=="TRUE"){
			$addLeadStatus = $LmsClientObj->insertLead($appId,$addReqInfo);
		}
		$addLeadStatus['action'] = $addReqInfo['action'];
		return $addLeadStatus;
	}


	private function cookie($value) {
		$value1 = $value . '|pendingverification';
		setcookie('user',$value1,time() + 2592000 ,'/',COOKIEDOMAIN);
		$this->init();
	}

	function get_free_alerts($textbox,$seccodeKey) {
		if(verifyCaptcha($seccodeKey,$textbox))
		{
			echo 'true';
		}
		else
		{
			echo 'code';
		}
	}

	function quicksignupform() {
		$this->init();
		$email = trim($this->input->post('quickemail'));
		$password = addslashes($this->input->post('quickpassword'));
		$confirmpassword = addslashes($this->input->post('quickconfirmpassword'));
		$mdpassword = sha256($password);
		$highesteducation = isset($_POST['quickeducation'])?htmlentities(addslashes(trim($this->input->post('quickeducation')))):'';
		$country = isset($_POST['countryofquickreg']) ? htmlentities(addslashes(trim($this->input->post('countryofquickreg')))) : '';
		$city = isset($_POST['citiesofquickreg']) ? htmlentities(addslashes(trim($this->input->post('citiesofquickreg')))) : '';
		/* CAPTURE START */
		$coordinates = isset($_POST['coordinates']) ? $this->input->post('coordinates') : '';
		$resolution = isset($_POST['resolution']) ? $this->input->post('resolution') : '';
		$sourceurl = isset($_POST['referer']) ? $this->input->post('referer') : '';
		$sourcename = isset($_POST['loginproductname']) ? $this->input->post('loginproductname') : '';

		/* CAPTURE START */

		//Display Name
		$register_client = new Register_client();
		$name = htmlentities(addslashes(trim($this->input->post('quickname'))));
		$gender = isset($_POST['quickgender']) ? $this->input->post('quickgender') : '';
		//Ends
		$landline = isset($_POST['quicklandlineno']) ? $this->input->post('quicklandlineno') : '';
		$landlineext = isset($_POST['quicklandlineext']) ? $this->input->post('quicklandlineext') : '';
		$mobile = isset($_POST['quickmobileno']) ? $this->input->post('quickmobileno') : '';
		$eduinterest = isset($_POST['quickinterest']) ? $this->input->post('quickinterest') : '';
		$age = isset($_POST['quickage']) ? $this->input->post('quickage') : '';
		$appID = 1;
		$register_client = new Register_client();
		$userarray = array();
		$userinterestarray = array();
		$userarray['email'] = $email;
		$userarray['textPassword'] = $password;
		$userarray['password'] = $mdpassword;
		$userarray['firstName'] = $name;
		$userarray['phone'] = $landlineext.$landline;
		$userarray['mobile'] = $mobile;
		$userarray['age'] = $age;
		$userarray['residenceCity'] = $city;
		$userarray['residenceCountry'] = $country;
		$userarray['educationLevel'] = $highesteducation;
		$userarray['gender'] = $gender;
		$userinterestarray['category'] = $eduinterest;
		if(!isset($_POST['signUpFlagForRequestEBrochure'])){
			$userarray['userGroup'] = 'quicksignupuser';
			$userarray['signUpFlag'] = "requestinfouser";
		}else{
			$userarray['userGroup'] = $this->input->post('userGroupForRequestEBrochure');
			$userarray['signUpFlag'] = $this->input->post('signUpFlagForRequestEBrochure');
		}
		if($email == '' || $password == '')
		{
			echo "blank" ;
			exit;
		}
		else
		{
			$Validate = $this->checkUserValidation();
			$userarray['userid'] = $Validate[0]['userid'];

			//            $addResult = $register_client->updateuserGen(12,$userarray,'userid',$userarray['userid'],$userinterestarray);
			if(is_numeric($eduinterest))
			{
				$response = $register_client->getspecializationid($eduinterest);
				$userarray['desiredCourse'] = $response;
			}

			if(trim($eduinterest) == "Study Abroad")
			$userarray['extraFlag'] = 'studyabroad';
			if(trim($eduinterest) == "Undecided")
			$userarray['extraFlag'] = 'undecided';
			$userarray['userid'] = $userarray['userid'];
			global $ug_course_mapping_array;
			global $pg_course_mapping_array;
			if(isset($userarray['educationLevel']))
			{
				error_log(array_key_exists($ug_course_mapping_array[$userarray['educationLevel']]).'USERARRAY');
				if(array_key_exists($userarray['educationLevel'],$ug_course_mapping_array))
				{
					$userarray['name'][] =  $ug_course_mapping_array[$userarray['educationLevel']];
					$userarray['level'][] =  'UG';
				}
				if(array_key_exists($userarray['educationLevel'],$pg_course_mapping_array))
				{
					$userarray['name'][] =  $pg_course_mapping_array[$userarray['educationLevel']];
					$userarray['level'][] =  'PG';
				}
				if(trim($userarray['educationLevel']) == "Other")
				{
					$userarray['name'][] =  trim($userarray['educationLevel']);
					$userarray['level'][] =  '';
				}
				if(trim($userarray['educationLevel']) == "School")
				{
					$userarray['name'][] =  trim($userarray['educationLevel']);
					$userarray['level'][] =  '10';
				}
			}
			$addResult = $register_client->updateUser(12,$userarray,$userarray['userid']);
			error_log(print_r($addResult,true).'FINALARRAY');

			$alerts_client = new Alerts_client();
			$subject = "Your Shiksha.com password and profile has been updated";
			$data['usernameemail'] = $email;
			$data['content'] = "You have successfully changed your profile and your password. Your new password to Shiksha Account is ".$password;
			$content = $this->load->view('user/PasswordChangeMail',$data,true);
			$mailresponse=$alerts_client->externalQueueAdd("12",ADMIN_EMAIL,$email,$subject,$content,$contentType="html");

			/* For Shiksha Inbox */
			$this->load->library('Mail_client');
			$mail_client = new Mail_client();
			$receiverIds = array();
			array_push($receiverIds,$userarray['userid']);
			$body = $content;
			$content = 0;
			$sendmail = $mail_client->send($appId,1,$receiverIds,$subject,$body,$content);

			$values = explode("|",$_COOKIE["user"]);
			$value = $email.'|'.$mdpassword;
			$value1= $value.'|'.$values[2];

			//Cookie Hack. I have absolutely no idea why i am doing this.
			setcookie('user','',time() - 2592000 ,'/',COOKIEDOMAIN);
			setcookie('user',$value1,time() + 2592000 ,'/',COOKIEDOMAIN);
			$this->userStatus = $this->checkUserValidation($value);

			if ( $addResult['status'] > 0) {
				echo $addResult['status'];
				exit;
			} else {
				echo '-1';
				exit;
			}
		}
	}

	function emailCompareLayer($emailURL = '',$sJSON = '', $user_email = '') {
		$this->init();
		$addReqInfo = array();
		$email = $this->input->post('reqInfoEmail');
		$mobile = $this->input->post('reqInfoPhNumber');
		$firstName = $this->input->post('reqInfoDispName');
		$displayname = $this->input->post('reqInfoDispName');
		$coordinates = isset($_POST['coordinates']) ? $this->input->post('coordinates') : '';
		$resolution = isset($_POST['resolution']) ? $this->input->post('resolution') : '';
		$sourceurl = isset($_POST['referer']) ? $this->input->post('referer') : '';
		$sourcename = isset($_POST['loginproductname']) ? $this->input->post('loginproductname') : '';
		//error_log("AMIT".print_r($addReqInfo,true));
		$captchatext = $this->input->post('captchatext');

		//below line is used for conversion tracking purpose
		$trackingPageKeyId = isset($_POST['tracking_keyid'])?$this->input->post('tracking_keyid'):'';

		if(isset($_POST['jSON'])){
			$addReqInfo = json_decode(urldecode($this->input->post('jSON')),true);
		}
		else{			
			$addReqInfo = json_decode(base64_decode($sJSON),true);
		}
		$addReqInfoForTitles = json_decode($_REQUEST['jSON'],true);

		/*
		 Get locality Info
		 By aditya
		 */
		$localityInfo = json_decode($this->input->post('localityJSON'),true);
		$ListingClientObj = new Listing_client();
		$register_client = new Register_client();
		$alerts_client = new Alerts_client();
		$signedInUser = $this->userStatus;
		$appId = 1;
		$flag_check = $this->input->post('flag_check');
		$attachmentIdArray = array();
		$instituteTitleArray = array();
		$cookieKey = isset($_POST['cookieKey'])?$this->input->post('cookieKey'):'';
		if(isset($cookieKey) && $cookieKey=='comparePageMobile'){
			$actiontype = 'MOB_COMPARE_EMAIL';
		}else{
			$actiontype = 5;	
		}
		
		if(is_array($this->userStatus)) {
			//user is logged in
			$signedInUser = $this->checkUserValidation();
			echo 'thanks';
		}
		
		if($user_email!=''){
                        $this->load->library('register_client');
                        $register_client = new Register_client();
                        $signedInUser = $register_client->getinfoifexists(1, $user_email, 'email');
		}

		if(is_array($signedInUser)) {
			$register_client->userInfoSystemPoint_Client($signedInUser[0]['userid'], 'requestInfoCourse');
		}
		$insTitleName = '';

                $this->load->builder('ListingBuilder','listing');
                $listingBuilder = new ListingBuilder;
                $courseRepository = $listingBuilder->getCourseRepository();

		foreach ($addReqInfo as $key => $value) {
			$addReqInfoVars = array();
			$addReqInfoVars['listing_type_id'] = isset($value[3]) ? $value[3] : $key;
			$addReqInfoVars['listing_type'] = 'course';
			$addReqInfoVars['listing_title'] = htmlspecialchars($value[1]);
			$addReqInfoVars['displayName'] = $signedInUser[0]['displayname'];
			$displayname =  $addReqInfoVars['displayName'];
			$addReqInfoVars['contact_cell'] = $signedInUser[0]['mobile'];
			$addReqInfoVars['userId'] = $signedInUser[0]['userid'];
			$email = explode("|",$signedInUser[0]['cookiestr']);
			$userEmail = $email[0];
			if($userEmail==""){
				$userEmail = $signedInUser[0]['email'];
			}
			$addReqInfoVars['contact_email'] =$userEmail;
			$addReqInfoVars['userInfo'] = $signedInUser;
			if(urldecode($localityInfo['localityJSON'][0]) > 1 && !in_array(urldecode($localityInfo['localityJSON'][0]),array(10223,10224))) {
				$addReqInfoVars['preferred_city'] = urldecode($localityInfo['localityJSON'][0]);
				$addReqInfoVars['preferred_locality'] = urldecode($localityInfo['localityJSON'][1]);
				error_log('adimereherorr1d_'.$addReqInfoVars['preferred_city']."_".$addReqInfoVars['preferred_locality']);
			} else {
				$addReqInfoVars['preferred_city'] = $value[5];
				$addReqInfoVars['preferred_locality'] = $value[6];
				error_log('adimereherorr'.$addReqInfoVars['preferred_city']."_".$addReqInfoVars['preferred_locality']);
			}

	                $courseForBrochure = $courseRepository->find(intval($addReqInfoVars['listing_type_id']));
			
        	        if(!$courseForBrochure->isPaid()) {
				if(isset($cookieKey) && $cookieKey=='comparePageMobile'){
					$sourcePage = 'MOB_COMPARE_EMAIL';
				}else{
					$sourcePage = 'Compare_Email';
				}
		                $this->sendFreeBrochureToUser($addReqInfoVars['userId'], $courseForBrochure, $sourcePage,$trackingPageKeyId);
                	}
			else{
				$this->addLead($signedInUser, $addReqInfoVars,$actiontype,$trackingPageKeyId);
			}
			Modules::run('listing/ListingPage/callMeNow',$key,$value[3],$signedInUser[0]['mobile'],$actiontype);
			$RequestedLinkInformation['listing_type_id'] = isset($value[3]) ? $value[3] : $key;
			$RequestedLinkInformation['listing_type'] = isset($value[4]) ? htmlspecialchars($value[4]) : $value[2];

			$brochureData = $ListingClientObj->getUploadedBrochure($appId,$RequestedLinkInformation['listing_type'],$RequestedLinkInformation['listing_type_id']);

			$instituteName = $brochureData['instituteName'];

			if($RequestedLinkInformation['listing_type'] == 'course')
			{
				$insTitleName .= '<a href="'.SHIKSHA_HOME.'/getListingDetail/'.$RequestedLinkInformation['listing_type_id'].'/course">'.$instituteName.'</a>.';
				$insTitleName .= '<br />';
			}
			if(urldecode($value[1])){
				$insURLArray = getSeoUrl($key, 'institute', urldecode($value[1]));
				//$insTitleName .= '<a href="'.$insURLArray.'">'.urldecode($value[1]).'</a><br />';
			}
		}
		//$compareUrl = $alerts_client->getCompareUrl($appId, $signedInUser[0]['userid'], $this->input->post('pageurl'),$this->input->post('cookieKey'));
		if(isset($_POST['pageurl'])){
			$compareUrl = $this->input->post('pageurl');
		}
		else{			
			$compareUrl = base64_decode($emailURL,true);
		}
		
		
		$data=array();
		$subject = "Institute Comparison from Shiksha.com";
		$content = 'Dear '.$displayname.',<br /><br />You have requested the institute comparison to be mailed on this email id.,';
		$content.= '<br/><a href="'.$compareUrl.'">Click to view the comparison</a><br/><br/>Click on the links below to get more information about these institutes.<br/>'.$insTitleName;
		$data['usernameemail'] = $userEmail;
		$data['content'] = $content;
		$content = $this->load->view('user/PasswordChangeMail',$data,true);
		if($actiontype != 4){//No Mails for auto responses
			$response=$alerts_client->externalQueueAdd("12",ADMIN_EMAIL,$userEmail,$subject,$content,"html","0000-00-00 00:00:00");
		}
	}
	
	/**
	* Function to get the size of the brochure in bytes
	**/
	private function getSizeOfBrochure( $course_id )
	{
		if(empty($course_id)){
			return false;
		}

	    $this->load->helper('download');
	    $brochureUrl_array = $this->getCourseEbrochure($course_id);
	   
	    if($brochureUrl_array['BROCHURE_URL'])
		$brochureUrl = $brochureUrl_array['BROCHURE_URL'];               
	    else
		return 0;
	    
	    $sizefromhelper = get_size($brochureUrl);
	    
	    return $sizefromhelper;
	}

	/**
	* Function to send the E-brochure without attachment rather with the link of the brochure
	***/
	private function sendBrochureMailWithLink( $params )
	{
	    $this->load->library('alerts_client');
	    $national_course_lib = $this->load->library('listing/NationalCourseLib');
	    $alerts_client = new Alerts_client();

	    $brochureUrl_array = $this->getCourseEbrochure($params['listing_type_id']);
	    
	    if($brochureUrl_array['BROCHURE_URL'])
	    {
		$brochureUrl = $national_course_lib->getBrochureDownloadURL( $params['user_id'], $params['listing_type_id'] );
	    }
	    else
		return false;
	    
	    $params['brochureDownloadLink']	= $brochureUrl;
		
		$this->load->model('categoryList/categorymodel');						
		$userCategoryDetails = $this->categorymodel->getCategoryDataByCourseId($params['listing_type_id']);
		$category_ids = array();$isFullTimeMBACategory = 'N';
		if(!empty($userCategoryDetails)) {
			foreach($userCategoryDetails as $userCategoryDetail) {
				$category_ids[] = $userCategoryDetail['category_id'];
			}
			if(in_array(23, $category_ids)) {
				$isFullTimeMBACategory = 'Y';
			}
		}

		if($isFullTimeMBACategory == 'Y') {
		    $params['mailer_name'] = 'NationalCourseDownloadBrochure'.$params['template_type'];
			$params['isFullTimeMBACategory'] = $isFullTimeMBACategory;
	        $email_content = $this->_emailContent($params);
			$response = 'Inserted Successfully';
		} else {
			$params['mailer_name'] = 'NationalCourseDownloadBrochure'.$params['template_type'];
		    $response = $this->_emailContent($params);
		 	// $email_content = $this->_emailContent($params);
			// $subject 	= $email_content['subject'];
			// $content 	= $email_content['content'];
			// $email	= $params['usernameemail'];
			
			// send the mail
			// $response	= $alerts_client->externalQueueAdd("12",ADMIN_EMAIL,$email,$subject,$content,"html",'');
		}
	    return $response;
	}
	
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
	* Function to create auto responses for:
	* 	1) pre registration course page views 
	* 	2) shortlisted courses
	* [ Runs every thirty minutes via cron ]
	***/
	public function createAutoResponses() {
		ini_set('memory_limit', "2048M");
		ini_set('time_limit', -1);
		
		global $pageTypeResponseActionMapping;
		
		$this->load->model('user/usermodel');
		$userModel = new UserModel;

		$this->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;

		$this->load->model('response/responsemodel');
		$responseModel = new ResponseModel();
						
		$courseRepository = $listingBuilder->getAbroadCourseRepository();
		
		//get users having pre-registration course views
		$usersIds = $userModel->getUsersHavingPreRegistrationViews();
		$preRegistrationCourseViews = $userModel->getPreRegistrationCourseViews($usersIds);
		
		$usersHavingNoPreRegistrationViews = array_diff($usersIds, array_keys($preRegistrationCourseViews));
		$userModel->updateHasPreRegistrationViews($usersHavingNoPreRegistrationViews);
		
		//get users who have shortlisted courses
		$userAllShortlistedCourses = $userModel->getUserAllShortlistedCourses();
		
		if(count($preRegistrationCourseViews) || count($userAllShortlistedCourses) ) {
			$data = $this->_responseSourceMapping($preRegistrationCourseViews, $userAllShortlistedCourses);
			$responseSourceMapping = $data['responseSourceMapping'] ;
			$courseIds = $data['courseIds'];

			// get some info for those users present in $responseSourceMapping array
			$userBasicInfo = $userModel->getUsersBasicInfo(array_keys($responseSourceMapping));
			
			// get course objects
			$courseObjs = $courseRepository->findMultiple($courseIds);

			// loop across $responseSourceMapping array over each user ..
			foreach($responseSourceMapping as $userId => $courseViews){

				$nationalCourses = array();

				// .. then each course..
				foreach($courseViews as $courseId => $responseSource){
					/* for abroad shortlisted courses (both abroad & national), we need to send an additional parameter.
					   This is required for abroad site users that have choosen a virtual city as their residence city,
					   this is not allowed anymore on national site.
					*/

					if($responseSource['userShortlistedCourses'] || $responseSource['userMobileShortlistedCoursesAbroad'])
					{
						$_POST['shortlistCron'] =1;
					}else if($responseSource['preRegistrationView']){
						$_POST['preRegistrationCron'] =1;
					}

					// get the course obj..
					// $courseObj = $courseObjs[$courseId];

					if(!is_object($courseObjs[$courseId])) {
						$nationalCourses[$courseId] = $courseId;
						echo 'Exclude National Course<br/>';
						// unset($courseObj);
						continue;
					}

					if(is_object($courseObjs[$courseId])){
						$countryId = $courseObjs[$courseId]->getMainLocation()->getCountry()->getId();
					}

					if($countryId == 2 || empty($countryId)) {
						$nationalCourses[$courseId] = $courseId;
						echo 'Exclude National Course<br/>';
						unset($courseObjs[$courseId]);
						// unset($courseObj);
						continue;
					}

					// check if it was a valid response by user..
					$isValidResponseUser = modules::run('registration/Forms/isValidAbroadUser', $userId, $courseId);

					// ..skip if it wasn't
					if($isValidResponseUser == false) {
						echo "Not Valid userId == ".$userId." for course ===".$courseId."<br/>";
						// unset($courseObj);
						continue;
					}

					unset($_POST['shortlistCron']);

					// ..skip the object if it isn't a valid one
					if(!is_object($courseObjs[$courseId])) {
						continue;
					}
					// lets keep this flag as false for now ...
					$makeResponse = false;
					
					if($responseSource['preRegistrationView']){

						if(!($courseObjs[$courseId]->isPaid()) && $countryId > 2){
							// unset($courseObj);
							echo "Free Abroad Listing ".$userId." for course ===".$courseId."<br/>";
							continue;
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
						
						if($countryId > 2) {
							$_POST['listingType'] = 'course';
							$_POST['listingTypeId'] = $courseId;
							$_POST['cookieString'] = $userBasicInfo[$userId]['email'].'|'.$userBasicInfo[$userId]['password'].'|pendingverification';
							$_POST['trackingPageKeyId']=$responseSource['tracking_keyid'];
							$_POST['visitorSessionid'] = $responseSource['visitorSessionid'];
							Modules::run('responseAbroad/ResponseAbroad/createResponseCallForAbroadListings','course',$courseId,$_POST['source_page']);
						}
						// update userShortlistedCourses so that same shortlisted courses are not picked again for response creation
						if($responseSource['userShortlistedCourses'] || $responseSource['userMobileShortlistedCoursesAbroad'] || $responseSource['userMobileShortlistedCourses'] || $responseSource['userNationalShortlistedCourses']) {
							$userModel->updateShortlistedResponses($userId, $courseId);
						}

						// $courseObj = null;
						// unset($courseObj);
						unset($_POST);
					}
				}
				if(!empty($preRegistrationCourseViews[$userId])) {
					if(empty($nationalCourses)) {
						$userModel->updateHasPreRegistrationViews(array($userId));	
					}  else {
						$result = array();
						// $uniquenationalCourses = array_unique($nationalCourses);
						$result = $responseModel->checkIsResponseCreated($userId, $nationalCourses);
						if(count($result) == count($nationalCourses)) {
							$userModel->updateHasPreRegistrationViews(array($userId));	
						}
					}					
				}
			}
		}

		echo 'Cron Completed';
	}	

	public function addLeadForMoveQuestionToListing($quesOwner,$addReqInfo,$actionType,$trackingKeyId){
		$this->addLead($quesOwner,$addReqInfo,$actionType,$trackingKeyId);
	}


}
?>
