<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | Authors: Original Author <author@example.com>                        |
// |          Your Name <you@example.com>                                 |
// +----------------------------------------------------------------------+
//
// $Id:$
/**
 * MobileUser class responsible for rendering login,register,
 * forgot-pass,requestEbrouchre page and vaidating the user.
 * It is also repsonsible for generating responses etc
 * Class and Function List:
 * Function list:
 * - __construct()
 * - sanity_preserved()
 * - renderRequestEbrouchre()
 * - request_validation()
 * - registerUser()
 * - sendEbrochureToUser()
 * - login()
 * - login_validation()
 * - logout()
 * - register()
 * - register_validation()
 * - sendWelcomeMailToNewUser()
 * - addLead()
 * - cookie()
 * - mobile_check()
 * - forgot_pass()
 * - forgot_validation()
 * - submit()
 * - sendResetPasswordMail()
 * Classes list:
 * - MobileUser extends ShikshaMobileWebSite_Controller
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');

class MobileUser extends ShikshaMobileWebSite_Controller {

	private $register_client;
	private $login_client;
	private $alerts_client;
//	private $userStatus = 'false';
	function __construct() {
		parent::__construct();
		$this->load->library(array(
					'miscelleneous',
					'category_list_client',
					'listing_client',
					'register_client',
					'alerts_client',
					'lmsLib',
					'Login_client'
					));
		$this->load->config('mcommon5/mobi_config');
		$this->userStatus = $this->checkUserValidation();
		$this->login_client = new Login_client();
		$this->register_client = new Register_client();
		$this->alerts_client = new Alerts_client();
		$this->load->library('form_validation');
		$this->form_validation->CI =& $this; // Grrrr... Need Hack to work form_validation Lib in HMVC
	}
	// prevent sign-in user to enter into Login,Registeration,Forgot Password pages.
	function sanity_preserved() {
		$logged_in_user_array = $this->data['m_loggedin_userDetail'];
		if (!is_array($logged_in_user_array) && $logged_in_user_array == 'false') {
			$logged_in_user_array = array();
		} else {
			$logged_in_user_array = $logged_in_user_array[0];
		}
		$logged_in_userid = (!isset($logged_in_user_array['userid'])) ? '-1' : $logged_in_user_array['userid'];
		$user_logged_in = (!isset($logged_in_user_array['userid'])) ? 'false' : 'true';
		if ($user_logged_in == "true" || ($_COOKIE['ci_mobile'] != 'mobile'))
		{
			return true;        
		} 
		else
		{
			return false;
		}
	}
	
	//to render registration page.
	function renderRequestEbrouchre() {
		
		require FCPATH.'globalconfig/userVerification.php';
	
		$url_data = array();
		$url_data['ODBCourses'] = json_encode($ODBCourses);
		$url_data['current_url'] = $this->input->post('current_url');
		$url_data['referral_url'] = $this->input->post('referral_url');
		$url_data['referrer'] = $this->input->post('current_url');
		$url_data['courseArray'] = $this->input->post('courseAttr');
		$url_data['from_where'] = $this->input->post('from_where',true);
	    $url_data['selected_course'] = $this->input->post('selected_course',true);            
		$url_data['list'] = $this->input->post('list',true);
		$url_data['institute_id'] = $this->input->post('institute_id',true);
		$url_data['pageName'] = $this->input->post('pageName',true);
		$url_data['requestEbrochure'] = 1;
		$url_data['getEBCall'] = isset($_POST['getEBCall'])?$_POST['getEBCall']:0;
		$url_data['action_type'] = isset($_POST['action_type'])?$_POST['action_type']:'';
		$url_data['signedInUser'] = $this->userStatus;
		$url_data['isFullRegisteredUser_mobile'] = 0;
		$url_data['tracking_keyid'] = $this->input->post('tracking_keyid');

		/*variable to hold call back type */
		$url_data['formCallBack'] = $this->input->post('formCallBack');

		//below line is used for conversiopn tracking purpose
		if(!empty($_POST['tracking_keyid']))
		{
			$url_data['trackingPageKeyId']=$this->input->post('tracking_keyid');
		}
		if($url_data['signedInUser'] != 'false')
		{
			$url_data['user_logged_in'] = 'true';
			$userInfoArray = Modules::run('registration/Forms/getUserProfileInfo', $url_data['signedInUser'][0]['userid']);
			
			$isFullRegisteredUser_mobile = false;
			if($url_data['selected_course']!='')
			{
				$isFullRegisteredUser_mobile = Modules::run('registration/Forms/isValidResponseUser', $url_data['selected_course'], $url_data['signedInUser'][0]['userid']);
				$url_data['isFullRegisteredUser_mobile'] = ($isFullRegisteredUser_mobile===true || $isFullRegisteredUser_mobile==1)?1:0;
			}
			
			$url_data['graduationYear'] = isset($userInfoArray['graduationCompletionYear'])?$userInfoArray['graduationCompletionYear']:0;
			$url_data['xiiYear'] = isset($userInfoArray['xiiYear'])?$userInfoArray['xiiYear']:0;
			
		}
		$this->load->helper(array(
					'form',
					'url'
					));
		if (empty($url_data['courseArray'])) {
			redirect(SHIKSHA_HOME);
		}
			
		$responseArray = $this->_getViewToBeLoaded();
		$viewToBeLoaded = $responseArray['viewFile'];
		if(isset($responseArray['listings_with_localities']) && $responseArray['listings_with_localities'] != "") {
			$url_data['listings_with_localities'] = $responseArray['listings_with_localities'];
		}
		
		// Loading Listing builder and repository..
		if($url_data['institute_id']>0)
		{
			$this->load->builder('ListingBuilder','listing');
			$listingBuilder = new ListingBuilder;
			$instituteRepository = $listingBuilder->getInstituteRepository();
			$instObjArr = $instituteRepository->find($url_data['institute_id']);
			$url_data['institute_name'] = $instObjArr->getName();
		}
		
		$displayData['boomr_pageid'] = "request_ebrouchre";
		//below code used for beacon tracking
		$url_data['trackingpageIdentifier'] = 'requestEbrochurePage';
		//loading library to use store beacon traffic inforamtion
		$this->tracking=$this->load->library('common/trackingpages');
		$this->tracking->_pagetracking($url_data);

		$this->load->view($viewToBeLoaded, $url_data);
	}
	
	private function _getViewToBeLoaded() {
		$responseArray = array();
		/*$this->load->library('ANA5/messageBoardProxy');
		$messageboardproxy = new messageBoardProxy($this->ci_mobile_capbilities);			
		if($messageboardproxy->getDeviceObj()) {
			global $listings_with_localities;
			$responseArray['listings_with_localities'] = json_encode($listings_with_localities);
			$responseArray['viewFile'] = "requestEbrochureSmartPhones";				
		} else {
			$responseArray['viewFile'] = "request";
		}
		*/
		global $listings_with_localities;
		$responseArray['listings_with_localities'] = json_encode($listings_with_localities);
		//$responseArray['viewFile'] = "requestEbrochureSmartPhones";
		$responseArray['viewFile'] = "register";
		return $responseArray;
	}

	function request_validation()
	{
		$courseArray = $this->input->post('courseArray');
		if (empty($courseArray)) {
			redirect(SHIKSHA_HOME);
		}
		$signedInUser         = $this->userStatus;
		$logged_in_user_array = $this->data['m_loggedin_userDetail'];
		if (!is_array($logged_in_user_array) && $logged_in_user_array == 'false') {
			$logged_in_user_array = array();
		} else {
			$logged_in_user_array = $logged_in_user_array[0];
		}

		$logged_in_userid = (!isset($logged_in_user_array['userid'])) ? '-1' : $logged_in_user_array['userid'];
		$user_logged_in   = (!isset($logged_in_user_array['userid'])) ? 'false' : 'true';

		//if user logged in then no need to check valdiation on email field.
		$this->form_validation->set_rules('user_mobile', 'Mobile', 'callback_mobile_check');
		$this->form_validation->set_rules('user_first_name', 'First Name', 'callback_name_check');
		$this->form_validation->set_rules('user_last_name', 'Last Name', 'callback_name_check');

		if ($user_logged_in != "true") {
			$this->form_validation->set_rules('user_email', 'Email', 'callback_email_check');
		}
		if (count($_POST) == 0) {
			redirect(SHIKSHA_HOME);
		}
		$institute_id        = $this->input->post('institute_id');
		$form_course_info    = array();
		$form_preferred_city = $this->input->post('preferred_city_dropdown');
		$form_course_Id      = $this->input->post('list');
		$pageName            = $this->input->post('pageName');
		if (strpos($form_course_Id, "|") === false && $form_preferred_city != "") {
			$form_course_info['course_id'] = $form_course_Id;
		}

		if (isset($form_preferred_city) && $form_preferred_city != "") {
			$form_course_info['course_preferred_city'] = $form_preferred_city;
			$form_preferred_locality                   = $this->input->post('preferred_locality_dropdwon');


			if (isset($form_preferred_locality) && $form_preferred_locality != "") {
				$form_course_info['course_preferred_locality'] = $form_preferred_locality;
			}

			// $form_course_info['flag_coursewise_customized_locality'] = $this->input->post('hidden_flag_coursewise_customized_locality');
		}

		$responseArray  = $this->_getViewToBeLoaded();
		$viewToBeLoaded = $responseArray['viewFile'];
		if (isset($responseArray['listings_with_localities']) && $responseArray['listings_with_localities'] != "") {
			$this->form_validation->set_rules('list', 'Course', 'callback_course_name_check');
			$url_data['listings_with_localities'] = $responseArray['listings_with_localities'];
		}
		if ($this->input->post('login')) {

			//getting the values of hidden field.
			$current_url  = $this->input->post('currentUrl');
			$referral_url = $this->input->post('referralUrl');
			$courseArray  = $this->input->post('courseArray');
			$action_type  = isset($_POST['action_type']) ? $this->input->post('action_type') : '';
			//$selectedCourseId = $this->input->post('list');
			$selectedCourseId = explode('|', $selectedCourseId);

			$url_data['current_url']       = $current_url;
			$url_data['referral_url']      = $referral_url;
			$url_data['courseArray']       = $courseArray;
			$url_data['selected_course']   = ($form_course_info['course_id'] == "" ? $form_course_Id : $form_course_info['course_id']);
			$form_course_info['course_id'] = $url_data['selected_course'];

			//hack for international useres
			$temp_user_mobile = $_POST['user_mobile'];
			if (strlen($temp_user_mobile) != '10') {
				$_POST['user_mobile'] = '9999999999';
			}
			if ($this->form_validation->run()) {
				$_POST['user_mobile'] = $temp_user_mobile;

				$user_first_name                = $this->input->post('user_first_name');
				$user_last_name                 = $this->input->post('user_last_name');
				$user_mobile                    = $this->input->post('user_mobile');
				$form_course_info['user_email'] = $user_email = $this->input->post('user_email');
				$form_course_info['from_where'] = $this->input->post('from_where', true);
				$signedInUser                   = $this->register_client->getinfoifexists(1, $user_email, 'email');
				// $addReqInfoVars                 = $this->_getRequiredInfoForEbrochure($courseArray, $form_course_info, $signedInUser);

				if (isset($action_type) && in_array($action_type, array('MOB_COMPARE_EBrochure', 'MOB_COMPARE_VIEWED', 'MOB_COMPARE_EMAIL'))) {
					$addReqInfoVars['action_type'] = $action_type;
				}
				$addReqInfoVars['cityId']     = $this->input->post('preferred_city_dropdown');
				$addReqInfoVars['localityId'] = $this->input->post('preferred_locality_dropdwon');

				//below line is used for conversion tracking purpose
				$trackingPageKeyId = $this->input->post('trackingPageKeyId');

				if (isset($trackingPageKeyId) && $trackingPageKeyId != 'undefined') {
					$addReqInfoVars['trackingPageKeyId'] = $trackingPageKeyId;
				} else { // so that either of tracking_keyid or trackingPageKeyId can be used
					if( $this->input->post('tracking_keyid', true) != ''){
						$addReqInfoVars['trackingPageKeyId'] = $this->input->post('tracking_keyid', true);
					} else if($this->input->post('trackingPageKeyId', true) != '') {
						$addReqInfoVars['trackingPageKeyId'] = $this->input->post('trackingPageKeyId', true);
					}
				}

				$this->sendEbrochureToUser($signedInUser, $addReqInfoVars);
				$applied_courses_array = array();
				if ($_COOKIE['applied_courses']) {
					$applied_courses_array   = json_decode(base64_decode($_COOKIE['applied_courses']), true);
					$applied_courses_array[] = $addReqInfoVars['listing_type_id'];
				} else {
					$applied_courses_array[] = $addReqInfoVars['listing_type_id'];
				}
				$applied_courses_array = array_unique($applied_courses_array);
				setcookie('applied_courses', base64_encode(json_encode($applied_courses_array)), 0, '/', COOKIEDOMAIN);
				setcookie('applied_courses_message', 'YES', 0, '/', COOKIEDOMAIN);

				$autoResponse = (isset($_POST['autoResponse']) && $_POST['autoResponse'] != '') ? $this->input->post('autoResponse', true) : 0;

				if ($autoResponse == '0' && !in_array($action_type, array('MOB_COMPARE_VIEWED', 'MOB_COMPARE_EMAIL'))) {
					if (isset($_COOKIE['MOB_A_C'])) {
						$applied_course_id = $_COOKIE['MOB_A_C'];
						$applied_course_id .= ',' . $form_course_Id;
					} else {
						$applied_course_id = $form_course_Id;
					}
					setcookie('MOB_A_C', $applied_course_id, 0, '/', COOKIEDOMAIN);
				}

				$pageNames = array(
					'CATEGORY_PAGE',
					'INSTITUTE_DETAIL_PAGE',
					'SIMILAR_INSTITUTE_DETAIL_PAGE',
					'SIMILAR_COURSE_DETAIL_PAGE',
					'COURSE_DETAIL_PAGE',
					'COURSE_DETAIL_PAGE_OTHER',
					'SEARCH_PAGE',
					'RANKING_PAGE',
					'COLLEGE_PREDICTOR_PAGE',
					'CP_MOB_Reco_ReqEbrochure',
					'SEARCH_MOB_Reco_ReqEbrochure',
					'RANKING_MOB_Reco_ReqEbrochure',
					'LP_MOB_Reco_ReqEbrochure',
					'SHORTLIST_PAGE',
					'COMPARE_PAGE',
					'Mobile_MyShortlist',
					'RANK_PREDICTOR_PAGE'
				); // In an effort to make the code readable, this is done.
				if (in_array($pageName, $pageNames) && $autoResponse == '0') {
					if ($action_type == 'MOB_COMPARE_VIEWED') {
						echo 'MOB_COMPARE_VIEWED';
						exit;
					} else {
						echo $pageName;
					}
				}
				if ($pageName == 'REQUEST-E-BROCHURE') {
					if ($form_course_info['from_where'] == 'MOBILE5_INSTITUTE_DETAIL_PAGE') {
						storeTempUserData("REB_LOC", $form_course_info['from_where']);
					}
					storeTempUserData("confirmation_message", "Thank you for your request. You will be receiving E-brochure of the selected institute(s) in your mailbox shortly.");
					storeTempUserData("confirmation_message_ins_page", "Thank you for your request. You will be receiving E-brochure of the selected institute(s) in your mailbox shortly.");
					$string              = url_base64_decode($current_url);
					$decode_refferal_url = url_base64_decode($referral_url);
					if (preg_match("/search\/index/", $string)) {
						$querystring = parse_url($string, PHP_URL_QUERY);
						parse_str($querystring, $vars);
						if (array_key_exists("start", $vars)) {
							$num      = 100 * floor($vars['start'] / 100);
							$finalUrl = preg_replace('/start=[0-9]*/', 'start=' . $num, $string);
							echo 'REQUEST-E-BROCHURE#' . $finalUrl;
						} else {
							echo 'REQUEST-E-BROCHURE#' . $string;
						}
					} else if (preg_match("-categorypage-", $string)) {
						$decode_current_url      = url_base64_decode($current_url);
						$explodecurrent_url      = explode('none', $decode_current_url);
						$explodecurrent_url_trim = trim($explodecurrent_url[1], '-');
						$explodecurrent_url_arr  = explode('-', $explodecurrent_url_trim);
						$num                     = $explodecurrent_url_arr[0];
						if ($num % 10 == 0) {
							$pageNum = $num - 9;
						} else {
							$pageNum = 10 * floor($num / 10) + 1;
						}
						$finalUrl = $explodecurrent_url[0] . 'none-' . $pageNum . '-' . $explodecurrent_url_arr[1];
						echo 'REQUEST-E-BROCHURE#' . $finalUrl;
					} else if (preg_match("/msearch5\/Msearch\/showMoreCourses/", $string)) {
						echo 'REQUEST-E-BROCHURE#' . $decode_refferal_url;
					} else {
						echo 'REQUEST-E-BROCHURE#' . $string;
					}
				}
				//redirect(url_base64_decode($current_url));
			} else {
				$url_data['preselected_course_id']   = $url_data['selected_course'];
				$url_data['form_preferred_city']     = $form_preferred_city;
				$url_data['form_preferred_locality'] = $form_preferred_locality;
				$this->load->view($viewToBeLoaded, $url_data);
			}
		}
	}

	
	
	private function _getRequiredInfoForEbrochure($courseArray, $form_course_info, $signedInUser) {
		$courseId = ''; $instiTitle = ''; $couresUrl = ''; $courseName = '';
		$isPaid = "TRUE";
		if(isset($form_course_info['course_id']) && $form_course_info['course_id'] != "" && $form_course_info['course_id']>0 && is_numeric($form_course_info['course_id'])) {
			/*
			 * For Smart Phones (JS support enbaled)..
			 */ 
			$this->load->builder('ListingBuilder','listing');
			$listingBuilder = new ListingBuilder;
			$courseRepository = $listingBuilder->getCourseRepository();
			// Getting Courses info..
			$coursesObj = $courseRepository->find($form_course_info['course_id']);
			$courseName = $coursesObj->getName();
			$courseId = $form_course_info['course_id'];
			$instiTitle = $coursesObj->getInstituteName();
			$courseUrl = $coursesObj->getUrl();
			$preferred_city = $form_course_info['course_preferred_city'];
			$preferred_locality = $form_course_info['course_preferred_locality'];
			$isPaid = $coursesObj->isPaid();
		} else {
			/*
			 * For NON Js supported Mobile phones..
			 */
			$courseAtrr_decoded = (base64_decode($courseArray, false));
			$courseAtrr_unserialized = (unserialize($courseAtrr_decoded));
			
			foreach ($courseAtrr_unserialized as $name => $value) {
				$valueArray = array();
				$valueArray = explode("*", $value);		
				if ($valueArray[0] == $selectedCourseId[0] && $valueArray[3] == $selectedCourseId[1] && $valueArray[4] == $selectedCourseId[2]) {
					$courseName = $name;
					$courseId =$valueArray[0];
					$instiTitle = $valueArray[1];
					$courseUrl = $valueArray[2];
					$preferred_city = $valueArray[3];
					$preferred_locality = $valueArray[4];
					break;
				}
			}			
		}				
		// Forming the desired array and return..
		$addReqInfoVars = array();
		$addReqInfoVars['listing_type_id'] = $courseId;
		$addReqInfoVars['listing_type'] = 'course';
		$addReqInfoVars['listing_title'] = $instiTitle;
		$addReqInfoVars['listing_Url'] = $courseUrl;
		$addReqInfoVars['displayName'] = $signedInUser[0]['displayname'];
		$addReqInfoVars['firstname'] = $signedInUser[0]['firstname'];
		$addReqInfoVars['contact_cell'] = $signedInUser[0]['mobile'];
		$addReqInfoVars['userId'] = $signedInUser[0]['userid'];
		$addReqInfoVars['contact_email'] = $form_course_info['user_email'];
		$addReqInfoVars['preferred_city'] = $preferred_city;
		$addReqInfoVars['preferred_locality'] = $preferred_locality;
		$addReqInfoVars['userInfo'] = $signedInUser;
		$addReqInfoVars['from_where'] = $form_course_info['from_where'];
		$addReqInfoVars['isPaid'] = $isPaid;
		/*
		if(isset($form_course_info['flag_coursewise_customized_locality']) && $form_course_info['flag_coursewise_customized_locality'] == 1) {
			$addReqInfoVars['flag_coursewise_customized_locality'] = $form_course_info['flag_coursewise_customized_locality'];
		}*/
		//Add the Response activity in DB
		$this->_logResponseActivity($addReqInfoVars);
		return $addReqInfoVars;
	}	
	
	function sendEbrochureToUser($signedInUser, $addReqInfoVars) {
		$insTitleName = $addReqInfoVars['listing_title'];
		$displayname = $addReqInfoVars['firstname'];
		$contact_cell = $signedInUser[0]['mobile'];
		$userId = $signedInUser[0]['userid'];
		$contact_email = $addReqInfoVars['contact_email'];
		$userInfo = $signedInUser;
		$ListingClientObj = new Listing_client();
		if ( $addReqInfoVars['flag_already_user'] != 'true')
		{
			/*
			Need to set cookie to display informational/status messages on header
			 */
		//	storeTempUserData("confirmation_message","Thank you for your request. You will be receiving E-brochure of the selected institute(s) in your mailbox shortly.");
			storeTempUserData("flag_google_adservices","E-brochure");
		}
		$this->addLead($signedInUser, $addReqInfoVars);
		$data = array();
		$brochureURL = '';
                
                // called to add or update data in tuserdata table
                $this->load->library('user/UserLib');
                $userLib = new UserLib;
                $userLib->updateUserData($userId);
		
		$brochureData = $ListingClientObj->getUploadedBrochure($appId,$addReqInfoVars['listing_type'],$addReqInfoVars['listing_type_id']);

			$instituteName = $brochureData['instituteName'];
			$courseName = $brochureData['courseName'];
			
			//Create the Content for the Mailer
			if($addReqInfoVars['listing_type'] == 'course') {				
			//	$subject = "E-brochure of ".htmlentities($courseName)." requested by you.";
				$subject = "See ".$instituteName." E-Brochure for ".$courseName." you requested for.";
				$content = 'Dear '.$displayname.','.'<br /><p>Please find attached e-brochure for '.htmlentities($courseName).' at '.htmlentities($instituteName).'.</p>You can also view latest updates about this course by visiting <a href="https://www.shiksha.com/getListingDetail/'.$addReqInfoVars['listing_type_id'].'/course">Shiksha.com</a>.';
			}
			else {
				$subject = "E-brochure of ".htmlentities($instituteName)." requested by you.";
				$content = 'Dear '.$displayname.','.'<br /><p>Please find attached e-brochure for '.htmlentities($instituteName).'.</p>You can also view latest updates about this course by visiting <a href="https://www.shiksha.com/getListingDetail/'.$addReqInfoVars['listing_type_id'].'/institute">Shiksha.com</a>.';
			}
			
			$sendTextMailer = true;
			$isFullTimeMBACategory = 'N';
		    if($addReqInfoVars['listing_type'] == 'course') {
				// Get all the data for brochure mailer
				$params = array();
				$params['courseName'] = $courseName;
				$params['instituteName'] = $instituteName;
				$params['first_name'] = $displayname;
				$params['listing_type'] = $addReqInfoVars['listing_type'];
				$params['template_type'] = 1;
				$params['usernameemail'] = $contact_email;	
				$params['URL'] = $addReqInfoVars['listing_Url'];
				$params['listing_type_id'] = $addReqInfoVars['listing_type_id'];
				
				$this->load->model('categoryList/categorymodel');						
				$userCategoryDetails = $this->categorymodel->getCategoryDataByCourseId($addReqInfoVars['listing_type_id']); // get course category
				$category_ids = array();
				if(!empty($userCategoryDetails)) {
					foreach($userCategoryDetails as $userCategoryDetail) {
						$category_ids[] = $userCategoryDetail['category_id'];
					}
					if(in_array(23, $category_ids)) {  // For MBA category
						$isFullTimeMBACategory = 'Y';
					}
				}										
			}
				
			/* If Course uploaded brochure or Institute Uploaded brochure is found, send it to user */
			if(is_array($brochureData) && isset($brochureData['brochureURL']) && !empty($brochureData['brochureURL']) && $addReqInfoVars['from_where']!='mobile_viewedListing' ) {
				
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
	
				$attachmentId = $this->alerts_client->createAttachment("12",$type_id,'COURSE','E-Brochure','',$attachmentName,$fileExtension,'true',$brochureURL);
	
				$attachmentArray=array($attachmentId);				

				if($isFullTimeMBACategory == 'Y') {  // if MBA category then go to system mailer tracking flow
				    if(!empty($attachmentArray)) {
					    $params['attachment'] = $attachmentArray;
				    }
				    $params['mailer_name'] = 'NationalCourseDownloadBrochureMobile'.$params['template_type'];
					$params['isFullTimeMBACategory'] = $isFullTimeMBACategory;
					Modules::run('MultipleApply/MultipleApply/_emailContent',$params);
				} else {
					$data['content'] = $content;
					$data['usernameemail'] = $contact_email;
					$content = $this->load->view('user/PasswordChangeMail',$data,true);					
					$response=$this->alerts_client->externalQueueAdd("12",ADMIN_EMAIL,$contact_email,$subject,$content,"html",'','y',$attachmentArray);
				}
				$sendTextMailer = false;
			}
			else{	//Check of Brochure created by Shiksha is available 
				$this->load->model('ListingModel');
				$shikshaBrochure = $this->ListingModel->getShikshaUploadedRequestEBrochureData($appId,$addReqInfoVars['listing_type'],$addReqInfoVars['listing_type_id']);
				
				if(is_array($shikshaBrochure) && isset($shikshaBrochure[0]) && $addReqInfoVars['from_where']!='mobile_viewedListing'){
					$brochureURL = $shikshaBrochure[0]['ebrochureUrl'];
					$filecontent = base64_encode(file_get_contents($brochureURL));
					$fileExtension = end(explode(".",$brochureURL));
					
					$this->load->library('Ldbmis_client');
					$misObj = new Ldbmis_client();
					$type_id = $misObj->updateAttachment($appId);
					
					$attachmentName = str_replace(" ",'_',$instituteName);
					$attachmentName = preg_replace("/[^a-zA-Z0-9_]+/", "", $attachmentName);
					$attachmentName = $attachmentName.".".$fileExtension;
					
					$filename = $title.".".$arr[$var];
		
					$attachmentId = $this->alerts_client->createAttachment("12",$type_id,'COURSE','E-Brochure','',$attachmentName,$fileExtension,'true',$brochureURL);
				    $attachmentArray=array($attachmentId);

					if($isFullTimeMBACategory == 'Y') {
				        if(!empty($attachmentArray)) {
					        $params['attachment'] = $attachmentArray;
				        }
						$params['mailer_name'] = 'NationalCourseDownloadBrochureMobile'.$params['template_type'];
						$params['isFullTimeMBACategory'] = $isFullTimeMBACategory;
						Modules::run('MultipleApply/MultipleApply/_emailContent',$params);
					} else {
					    $data['content'] = $content;
					    $data['usernameemail'] = $contact_email;
					    $content = $this->load->view('user/PasswordChangeMail',$data,true);					
		
					    $response=$this->alerts_client->externalQueueAdd("12",ADMIN_EMAIL,$contact_email,$subject,$content,"html",'','y',$attachmentArray);
				    }
					$sendTextMailer = false;
				}
			}
			
			//If non of the Brochures are found, send a Text mailer 
			if($sendTextMailer && $addReqInfoVars['from_where']!='mobile_viewedListing'){
				if($isFullTimeMBACategory == 'Y') {
				    $params['template_type'] = 2;
				    $params['mailer_name'] = 'NationalCourseDownloadBrochureMobile'.$params['template_type'];
				    $params['isFullTimeMBACategory'] = $isFullTimeMBACategory;
				    Modules::run('MultipleApply/MultipleApply/_emailContent',$params);
				} else {
				    $subject = "Your application has been submitted successfully";
				    $content = 'Dear ' . $displayname . '<br /><br />Your request for further information has been successfully submitted to the following institute<br /><br />' . $insTitleName . '<br /><br />The institute(s) shall get in touch with you soon at your contact details mentioned by you to proceed with admission process.<br /><br />Your Email - ' . $contact_email . '<br />Your Phone Number - ' . $contact_cell . '<br/> <br />If the above is not correct kindly login to Shiksha.com account and click on account and
				setting to update the same.<br /><br />Thank you for using <a href="https://www.shiksha.com/">Shiksha.com</a> for your education search.';
				    $data['usernameemail'] = $contact_email;
				    $data['content'] = $content;
				    $content = $this->load->view('PasswordChangeMail', $data, true);
				    $response = $this->alerts_client->externalQueueAdd("12", ADMIN_EMAIL, $contact_email, $subject, $content, "html", "0000-00 -00 00:00:00");
				}
			}

                        //In case a Brochure is available for any listing, we have to track that Brochure sent entry in the DB
                        if($brochureURL!='') {
                                $userInfoArray['user_id'] = $signedInUser[0]['userid'];
                                $userInfoArray['course_id'] = $addReqInfoVars['listing_type_id'];
                                $userInfoArray['brochureUrl'] = $brochureURL;
                                $userInfoArray['downloadedFrom'] = 'MOBILESITE5';
                                $this->_trackFreeBrochureDownload($userInfoArray);
                        }

	}
	
	// render HTML of login page
	function login($isCallFromRegForm=0) {
		
		try {
			
		if($this->sanity_preserved()=='true')
		{
			redirect(SHIKSHA_HOME);
		}
		global $shiksha_site_current_url;

		$url_data['activelink'] = 'login';
		$encoded_current_url = url_base64_encode($shiksha_site_current_url);
		$url_data['current_url'] = $encoded_current_url;

		$url_data['formCallBack'] = $this->input->post('formCallBack');

		if(!$isCallFromRegForm){
			$url_data['pageSource'] = base64_encode($_SERVER["HTTP_REFERER"]);
		}else{
			$pageSource = $this->input->post('referrer');
			
			$url_data['pageSource'] = $pageSource;

			$isDownloadBrochure = $this->input->post('isDownloadBrochure');
			if(!empty($isDownloadBrochure)){
				$url_data['callBackType'] = 'DownloadBrochure';
			}
			$isShortlist_CategoryPage = $this->input->post('isShortlist_CategoryPage');
			if(!empty($isShortlist_CategoryPage)){
				$url_data['callBackType'] = 'Shortlist_CategoryPage';
			}
			$url_data['clientCourseId'] = $this->input->post('courseId');
			$url_data['courseArray'] = $this->input->post('courseArray');
			$url_data['from_where'] = $this->input->post('from_where');
			$url_data['pageName'] = $this->input->post('pageName');
			$url_data['institute_id'] = $this->input->post('institute_id');
			$url_data['tracking_keyid'] = $this->input->post('tracking_keyid');
		}

		$url_data['referral_url'] = '';
		$url_data['status'] = '0';
		$url_data['boomr_pageid'] = "login_page";
		$this->load->helper(array('form','url'));

		//below code used for beacon tracking
	    $url_data['trackingpageIdentifier']='loginPage';
	    $url_data['trackingcountryId']=2;

	    //loading library to use store beacon traffic inforamtion
	    $this->tracking=$this->load->library('common/trackingpages');
	    $this->tracking->_pagetracking($url_data);

		$this->load->view('login', $url_data);
	   } catch(Exception $e) {
		   
	   }
	}

	function login_validation() {
		try {
		if (count($_POST) == 0) {
			redirect(SHIKSHA_HOME);
		}
		if($this->sanity_preserved()=='true')
		{
			redirect(SHIKSHA_HOME);
		}
		$resultDb = false;
		$contact_validation_rules = array(
				array(
					'field' => 'user_pass',
					'label' => 'Password',
					'rules' => 'required'
				     ) ,
				);
		$url_data['activelink'] = 'login';
		$this->form_validation->set_rules('user_email', 'Email', 'callback_email_check');
		$this->form_validation->set_rules($contact_validation_rules);
		if ($this->input->post('login')) {
			$current_url = $this->input->post('currentUrl');
			$referral_url = $this->input->post('referralUrl');
	        $requestType  = $this->input->post('loginType');
			$url_data['current_url'] = $current_url;
			$url_data['referral_url'] = $referral_url;

			$url_data['status'] = '0'; //0 default 1.db validated 2.rules valid but not match with db.
			if($this->input->post('ICP', true) == 'ICP'){
				$url_data['source'] = 'ICP';
			}
			if ($this->form_validation->run()) {

				$isMobileRegistration = $this->input->post('isMobileRegistration');
				$resultDb = $this->submit($this->input->post('user_email') , $this->input->post('user_pass'), $isMobileRegistration);
				if ($resultDb == true) {

					// Login call back, Since call is completely at the back-end, so making call back at the backend. For any operation you need to perform after successful login, please write your clause in the call back.
					
					$callType = $this->input->post('callBackType', true);
					
					$signedInUser = array();
					if($this->input->post('user_email') !='')
					{  
						$user_email = $this->input->post('user_email');
						$signedInUser = $this->register_client->getinfoifexists(1, $user_email, 'email');
					}
					if(!empty($callType)){
						$this->mobileLoginCallBack($callType,$signedInUser[0]['userid']);
					}

					/*Logic to show registration form to in-complete user */
					global $inCompleteUser;
					if(!empty($inCompleteUser) && $inCompleteUser == 1){
						return;
					}

					//After user has loggedin,update userId for made response
					if(!empty($signedInUser[0]['userid']))
					{   
						$this->load->model('comparePage/comparepagemodel');
						$this->comparepagemodel->updateUserIdForMadeResponse($signedInUser[0]['userid']);
					}

					if($this->input->post('ICP', true) == 'ICP'){
						/*Destroying fresh registration flag cookie */
						if(isset($_COOKIE['IIMPredictor'])){
					        setcookie('IIMPredictor', '-1',time() + (8), "/");
				        }
						redirect('mba/resources/iim-call-predictor-result', 'location');
						exit();
					}

					//After user has loggedin,add shortlisted course in Db
					if(isset($_COOKIE['mobile-loginState']) && $_COOKIE['mobile-loginState'] !='' && $this->input->post('user_email') !='')
					{
						$value = explode('::',$_COOKIE['mobile-loginState']);
						$courseId = $value[0];
						$pageType = $value[1];
						$tracking_keyid = $value[2];
						$user_email = $this->input->post('user_email');
						Modules::run('categoryList/AbroadCategoryList/updateShortListedCourse',$courseId,'add',$pageType,'national',$user_email,$tracking_keyid);
					}
				
					$postQuestData = $_COOKIE['mobile_ask_quest']; // post question/reply/comment after user has logged in
					$replyQuestId  = base64_decode($_COOKIE['mobile_post_msgid']); 
					
					if ($postQuestData !='' && $replyQuestId =='') {
						
						$cookieValue = array();
						$value = explode('&',$postQuestData);
						$text = explode('=',$value[0]);
						$instituteId = explode('=',$value[1]);
						$courseId = explode('=',$value[2]);
						$categoryId = explode('=',$value[3]);
						$locationId = explode('=',$value[4]);
						$encoded = explode('=',$value[10]);
						$pref_loc_id = explode('=',$value[12]);
						$pref_city_id = explode('=',$value[13]);
						//below line is used for conversion tracking purpose
						$tracking_keyid = explode('=', $value[15]);
						$cookieValue = array('questionText'=>$text[1],'instituteId'=>$instituteId[1],'courseId'=>$courseId[1],'categoryId'=>$categoryId[1],'locationId'=>$locationId[1],'encoded'=>$encoded[1],'pref_loc_id'=>$pref_loc_id[1],'pref_city_id'=>$pref_city_id[1],'tracking_keyid'=>$tracking_keyid[1]);
						if($instituteId > 0 && $courseId > 0){
							Modules::run('messageBoard/MsgBoard/askQuestionFromListing',$cookieValue);
							setcookie('mobile_post_suc_msg','Thank You! Current students of this college will reply to your question shortly.',0,'/',COOKIEDOMAIN);
						}
					
					}else if($postQuestData !='' && $replyQuestId !=''){
						
						$cookieValue = array();
						$value = explode('&',$postQuestData);
						$text = explode('=',$value[0]);
						$threadid = explode('=',$value[1]);
						$actionPerformed = explode('=',$value[2]);
						$fromOthers = explode('=',$value[3]);
						
						if($actionPerformed[1] == 'addAnswer')
						{
							$mentionedUsers = explode('=',$value[4]);
							$secCodeIndex = explode('=',$value[5]);
						
							//below line is used for conversion tracking purpose
								$tracking_keyid = explode('=',$value[6]);

							$cookieValue = array('replyText'.$replyQuestId=>$text[1],'threadid'.$replyQuestId=>$threadid[1],'actionPerformed'.$replyQuestId=>$actionPerformed[1],'fromOthers'.$replyQuestId=>$fromOthers[1],'mentionedUsers'.$replyQuestId=>$mentionedUsers[1],'secCodeIndex'=>$secCodeIndex[1],'tracking_keyid'=>$tracking_keyid[1]);
						}
						
						if($actionPerformed[1] == 'addComment')
						{
							$mainAnsId = explode('=',$value[4]);
							
							//below line is used for conversion tracking purpose
							$tracking_keyid = explode('=',$value[5]);
							$cookieValue = array('replyCommentText'.$replyQuestId=>$text[1],'threadid'.$replyQuestId=>$threadid[1],'actionPerformed'.$replyQuestId=>$actionPerformed[1],'fromOthers'.$replyQuestId=>$fromOthers[1],'mainAnsId'.$replyQuestId=>$mainAnsId[1],'tracking_keyid'=>$tracking_keyid[1]);
						}
						Modules::run('messageBoard/MsgBoard/replyMsg',$replyQuestId, 'mobileReply', $cookieValue);
					}
					
					$postQuestDataFromCC = $_COOKIE['mobile_CC_ask_quest']; // post question/reply/comment after user has logged in
					if($postQuestDataFromCC!='')
					{
						$cookieValue = array();
						$value = explode('&',$postQuestDataFromCC);
						$text = explode('=',$value[0]);
						$instituteId = explode('=',$value[1]);
						$categoryId = explode('=',$value[2]);
						$locationId = explode('=',$value[3]);
						$referer_ForAskInstitute = explode('=',$value[4]);
						$resolution_ForAskInstitute = explode('=',$value[5]);
						$coordinates_ForAskInstitute = explode('=',$value[6]);
						$courseId = explode('=',$value[7]);
						$page_name = explode('=',$value[8]);
						$tracking_keyid = explode('=',$value[9]);
						
						$cookieValue = array('questionText'=>$text[1],'instituteId'=>$instituteId[1],'courseId'=>$courseId[1],'categoryId'=>$categoryId[1],'locationId'=>$locationId[1],'page_name'=>$page_name[1], 'referer_ForAskInstitute'=>$referer_ForAskInstitute[1], 'resolution_ForAskInstitute'=>$resolution_ForAskInstitute[1], 'coordinates_ForAskInstitute'=>$coordinates_ForAskInstitute[1],'tracking_keyid'=>$tracking_keyid[1]);
						if($instituteId > 0 && $courseId > 0){
							Modules::run('messageBoard/MsgBoard/askQuestionFromListing',$cookieValue);
							setcookie('isUserRegistersForQuestionPosting','yes',time()+3600,'/',COOKIEDOMAIN);
						}
					}
					
					$subscriptionDataFromCalendar = $_COOKIE['mobile_EC_set_alerts'];
					if($subscriptionDataFromCalendar != '')
					{
						$values = explode('&',$subscriptionDataFromCalendar);
						$examListForAlert = explode('=',$values[0]);
						$examArr = explode('@#@', $examListForAlert[1]);
						$categoryName=explode('=',$values[1]);
						$checkForRedundancy = false;
						//below line is used for conversion tyracking purpose for set alerts after loggin into the system
						$tracking_keyid = explode('=',$values[2]);
						Modules::run('mEventCalendar5/EventCalendarController/addEventCalendarSubscription', $examArr, $categoryName[1], $signedInUser[0]['userid'], $checkForRedundancy,$tracking_keyid[1]);
					}
					
					$reminderDataFromCalendar = $_COOKIE['mobile_EC_set_reminders'];
					if($reminderDataFromCalendar != '')
					{
						$dArr = array();
						$values = explode('&',$reminderDataFromCalendar);
						$value = explode('=', $values[0]);
						$dArr['reminder_date'] = $value[1];
						$value = explode('=', $values[1]);
						$dArr['event_name'] = $value[1];
						$value = explode('=', $values[2]);
						$dArr['event_type'] = $value[1];
						$value = explode('=', $values[3]);
						$dArr['event_description'] = $value[1];
						$value = explode('=', $values[4]);
						$dArr['event_date'] = $value[1];
						$dArr['date_created'] = date('Y-m-d H:i:s');
						//below line is used for conversion tracking purpose
						$value = explode('=', $values[6]);
						$dArr['tracking_keyid'] = $value[1];
						$checkForRedundancy = true;
						Modules::run('mEventCalendar5/EventCalendarController/addEventCalendarReminder', $dArr, $signedInUser[0]['userid'], $checkForRedundancy);
					}
					
					$addEventDataFromCalendar = $_COOKIE['mobile_EC_add_event'];
					if($addEventDataFromCalendar != '')
					{
						$addEventDataFromCalendar .= '&newUserId='.$signedInUser[0]['userid'];
						Modules::run('mEventCalendar5/EventCalendarController/addEventOnCalendar', $addEventDataFromCalendar);
					}
					
					$CPResultDataFormat = $_COOKIE['CPSearchLoggedIn'];
					if($CPResultDataFormat != '')
					{
						$dArr = array();
						$values = explode('&',$CPResultDataFormat);
						$value = explode('=', $values[0]);
						$dArr['rank'] = $value[1];
						$value = explode('=', $values[1]);
						$dArr['category'] = $value[1];
						$value = explode('=', $values[2]);
						$dArr['branch'] = $value[1];
						$value = explode('=', $values[3]);
						$dArr['rankType'] = $value[1];
						$value = explode('=', $values[4]);
						$dArr['stateName'] = $value[1];
						$value = explode('=', $values[5]);
						$dArr['round'] = $value[1];
						$value = explode('=', $values[6]);
						$dArr['start'] = $value[1];
						$value = explode('=', $values[7]);
						$dArr['count'] = $value[1];
						$value = explode('=', $values[8]);
						$dArr['college'] = $value[1];
						$value = explode('=', $values[9]);
						$dArr['filterStatus'] = $value[1];
						$value = explode('=', $values[10]);
						$dArr['filterTypeDataStatus'] = $value[1];
						
						Modules::run('mcollegepredictor5/CollegePredictorController/getSearchResults', $dArr);
					}
					
					$postQuestDataForCompare = $_COOKIE['mob_ask_cmpr'];
					if($postQuestDataForCompare != ''){
						$cookieValue = array();
						$value = explode('&',$postQuestDataForCompare);
						$text = explode('=',$value[0]);
						$instituteId = explode('=',$value[1]);
						$courseId = explode('=',$value[2]);
						$categoryId = explode('=',$value[3]);
						$locationId = explode('=',$value[4]);
						$encoded = explode('=',$value[10]);
						$pref_loc_id = explode('=',$value[12]);
						$pref_city_id = explode('=',$value[13]);
						//below line is used for conversion tracking purpose
						$tracking_keyid = explode('=', $value[15]);
						$cookieValue = array('questionText'=>$text[1],'instituteId'=>$instituteId[1],'courseId'=>$courseId[1],'categoryId'=>$categoryId[1],'locationId'=>$locationId[1],'encoded'=>$encoded[1],'pref_loc_id'=>$pref_loc_id[1],'pref_city_id'=>$pref_city_id[1],'tracking_keyid'=>$tracking_keyid[1]);
						if($instituteId > 0 && $courseId > 0){
							Modules::run('messageBoard/MsgBoard/askQuestionFromListing',$cookieValue);
							setcookie('mobSucMsgCmpr_'.$courseId[1],'1',0,'/',COOKIEDOMAIN);
	
						}	
					}


					//added by akhter for mentee add form
					if($_COOKIE['menteeData'] !='')
					{
						$userId = $signedInUser[0]['userid'];
						Modules::run('mCampusAmbassador5/MentorController/addMenteeData', $userId);
					}
					
					// remove setCookie of homepage category tab
					setcookie('hpTab', '', time() - 30, '/', COOKIEDOMAIN);

					$url_data['status'] = '1';
					if($requestType=='ajax'){
						if($isMobileRegistration == 1) {
							$result = array('userId'=>$resultDb);
							echo json_encode($result);
						} else {
							echo "RIGHT_DETAILS";
						}
					}else{
						// redirecting user to the screen from where user clicked on login button
						$pageSource = $this->input->post('pageSource', true);

						if(!empty($pageSource)){
							redirect( base64_decode($pageSource) );							
							return;
						}

						if (empty($referral_url)){ 
							redirect(url_base64_decode($current_url));
						}
						else{ 
							redirect(url_base64_decode($referral_url));
						}
					}
				} else {
					if($requestType=='ajax'){
					    echo "WRONG_DETAILS";
					}else{
						$url_data = $_POST;
						$url_data['activelink'] = 'login';
						$url_data['status'] = '2';
						$this->load->view('login', $url_data);
					}
				}
			} else {
				$this->load->view('login', $url_data);
			}
		}
	    } catch(Exception $e) {
			
				//
		}
	}

	function downloadbrochureForLoginCallback(){
		$userDetails = $this->checkUserValidation();
		$this->userStatus = $userDetails;
		$this->data['m_loggedin_userDetail'] = $userDetails;
		$userDetails = $userDetails[0];
		$email = explode('|', $userDetails['cookiestr']);
		$email = $email[0];
		$_POST['user_email'] = $email;
		$_POST['user_first_name'] = $userDetails['firstname'];
		$_POST['user_last_name'] = $userDetails['lastname'];
		$_POST['user_mobile'] = $userDetails['mobile'];
		$_POST['referralUrl'] = $_POST['currentUrl'];
		$_POST['trackingPageKeyId'] = $_POST['tracking_keyid'];
		$_POST['login'] = 'Request E-Brochure';
		$_POST['autoResponse'] = 0;
		$_POST['list'] = $_POST['clientCourseId'];

		$isValidResponseUser = modules::run('registration/Forms/isValidResponseUser', $_POST['clientCourseId'], $userDetails['userid'], true);
			//harshit
		if($isValidResponseUser == false) {
			/*Flag to stop back navigation */
			global $inCompleteUser;
			$inCompleteUser = 1;

			$_POST['selected_course'] = $_POST['clientCourseId'];
			$_POST['referral_url'] = $_POST['pageSource'];
			$_POST['current_url'] = $_POST['pageSource'];
			
			$addReqInfoVars = serialize(array($_POST['clientCourseId']));
			$addReqInfoVars = base64_encode($addReqInfoVars);
			$_POST['courseAttr'] = $addReqInfoVars;
			
			$this->renderRequestEbrouchre();
		}else{
			
			setcookie('show_recommendation', 'yes', time() + (30), "/", COOKIEDOMAIN);
			setcookie('hide_recommendation', 'no', time() + (30), "/", COOKIEDOMAIN);
			setcookie('recommendation_course', $_POST['clientCourseId'], time() + (30), "/", COOKIEDOMAIN);
			$this->request_validation();
		}
		return;
	}

	function mobileLoginCallBack($callType,$userId){
		switch($callType){
			case 'Shortlist_CategoryPage':
				$_POST['userId'] = $userId;
				Modules::run('mShortlist5/ShortlistMobile/updateShortlistedUser',$_POST);
			break;
			
			default:
				$this->downloadbrochureForLoginCallback();
			break;
		}
	}

	function logout() {
		global $shiksha_site_current_refferal;
		$strcookie = $_COOKIE['user'];
		$appId = 1;
		$response = $this->login_client->logOffUser($strcookie, $appId);
		setcookie('user', '', time() - 864000, '/', COOKIEDOMAIN);
		setcookie('fbSessionKey', '', time() - 864000, '/', COOKIEDOMAIN);
		setcookie('MOB_A_C', '', time() - 864000, '/', COOKIEDOMAIN);
		setcookie('REB_LOC', '', time() - 864000, '/', COOKIEDOMAIN);
		global $logged_in_userid;
		if($logged_in_userid>0){
			$this->load->model('user/usermodel');
			$this->usermodel->trackUserLogout($logged_in_userid);
		}
		setcookie('applied_courses','',time() - 3600,'/',COOKIEDOMAIN);
		setcookie('getFeeDetails','',time() - 3600,'/',COOKIEDOMAIN);
		setcookie('short_courses','',time() - 3600,'/',COOKIEDOMAIN);
		setcookie('mobileTotalUserReco','',time() - 3600,'/',COOKIEDOMAIN);
		setcookie('mobile-shortlisted-courses','',time() - 864000,'/',COOKIEDOMAIN);
		setcookie('instContResp','',time() - 864000,'/',COOKIEDOMAIN);
		setcookie('courContResp','',time() - 864000,'/',COOKIEDOMAIN);
		setcookie('examGuide','',time() - 864000,'/',COOKIEDOMAIN);
		setcookie('examSubscribe','',time() - 864000,'/',COOKIEDOMAIN);
		if(isset($_COOKIE['clientAutoLogin'])) {
	    	setcookie('clientAutoLogin','',time() - 864000,'/',COOKIEDOMAIN);
	    }
		if(isset($_COOKIE['ICP_data'])){ setcookie('ICP_data', '0',time() - 3600, "/"); }
		$allCookies = $_COOKIE;
		$prefix = 'collegepredictor_search_';
		$examprefix = 'allExamGuide_';
		foreach($allCookies as $cookieId => $cookieValue) {
			if(preg_match('/^'.$prefix.'/', $cookieId) == 1){
	            setcookie($cookieId,'',time() - 864000,'/',COOKIEDOMAIN);
	        }
	        if(preg_match('/^'.$examprefix.'/', $cookieId) == 1){
            		setcookie($cookieId,'',time() - 864000,'/',COOKIEDOMAIN);
        	}
		}
		$shiksha_site_current_refferal = str_replace('amp;', '', $shiksha_site_current_refferal);
		redirect($shiksha_site_current_refferal);
	}
	function register() {
		if($this->sanity_preserved()=='true')
		{
			redirect(SHIKSHA_HOME);
		}

		$tracking_keyid_int = intval($_GET['tracking_keyid']);
        $tracking_keyid_Length = strlen($_GET['tracking_keyid']);
        $tracking_keyid_int_Length = strlen($tracking_keyid_int);
		if($tracking_keyid_int <= 0 || $tracking_keyid_Length != $tracking_keyid_int_Length) {
			redirect(SHIKSHA_HOME);
		}

		$url_data['activelink'] = 'register';
		global $shiksha_site_current_url;
		global $shiksha_site_current_refferal;
		$encoded_current_url = url_base64_encode($shiksha_site_current_url);
		$encoded_current_refferal = url_base64_encode($shiksha_site_current_refferal);
		
		if(isset($_POST['referrer_postQuestion']) && $_POST['referrer_postQuestion']!=''){
			$encoded_current_refferal = $_POST['referrer_postQuestion'];
			$url_data['referrer'] = $encoded_current_refferal;
		}
		
		if(isset($_POST['referrer_postQuestion_cc']) && $_POST['referrer_postQuestion_cc']!=''){
			$encoded_current_refferal = $_POST['referrer_postQuestion_cc'];
			$url_data['referrer'] = $encoded_current_refferal;
		}
	
		if(isset($_POST['cmp_url']) && $this->input->post('cmp_url') !='')
		{   
			$url_data['referrer'] = url_base64_encode($this->input->post('cmp_url'));
		}
		
		$url_data['current_url'] = $encoded_current_url;
		$url_data['referral_url'] = $encoded_current_refferal;

		if(isset($_POST['actionPoint']) && $_POST['actionPoint']!=''){
			$actionPoint = $_POST['actionPoint'];
			$url_data['actionPoint'] = $actionPoint;
			
		}
		
		$this->load->helper(array(
					'form',
					'url'
					));
		$url_data['boomr_pageid'] = "register_page";
		$usr_model = $this->load->model('user/usermodel');
		$signedInUser = $this->userStatus;
		if($signedInUser!='false' && !empty($signedInUser[0]['userid'])) {
			$usr_model = $this->load->model('user/usermodel');
			$response_array = $usr_model->getUserDetailsForTwoStepRegistration($signedInUser[0]['userid']);
	    
			//Check if LDB User
			if(isset($response_array['isLDBUser'])){
			    $isLDBUserCheck = $response_array['isLDBUser'];
			}
			else{
			    $isLDBUserCheck = 'NO';
			}
	    
			//If not LDB user, check if Desired course is filled.
			$desiredCourse = 0;
			if($isLDBUserCheck == 'NO'){
			    if(isset($response_array['DesiredCourse']) && $response_array['DesiredCourse']>0){
				$desiredCourse = $response_array['DesiredCourse'];
			    }
			}
	    
			//If not LDB and not DC, this is a short registered user
			if($desiredCourse == 0 && $isLDBUserCheck == 'NO'){
			    $shortRegistered = true;
			}
			else{
			    $shortRegistered = false;
			}
			if($isLDBUserCheck=='YES'){
				redirect(SHIKSHA_HOME);
			}
			$url_data['isLDBUserCheck'] = $isLDBUserCheck;
			$url_data['desiredCourse'] = $desiredCourse;
			$url_data['shortRegistered'] = $shortRegistered;
			$url_data['CategoryId'] = $response_array['CategoryId'];
			$resultSet = $usr_model->checkUserIsResponse($signedInUser[0]['userid']);
			if($resultSet>0){
				$desiredCourseIdAndFieldOfInterest = $usr_model->getDesiredCourseIdAndFieldOfInterest($resultSet);
				$url_data['desiredCourseIdAndFieldOfInterest'] = $desiredCourseIdAndFieldOfInterest;
			}
				
		}else{
			if(isset($_COOKIE['regsitration_desired_course']) && !empty($_COOKIE['regsitration_desired_course'])){
				$desiredCourseIdAndFieldOfInterest = $usr_model->getFieldForInterest($_COOKIE['regsitration_desired_course']);
				$url_data['desiredCourseIdAndFieldOfInterest'] = $desiredCourseIdAndFieldOfInterest;
			}else{
				if(isset($_COOKIE['regsitration_field_of_interest']) && !empty($_COOKIE['regsitration_field_of_interest'])){
					$desiredCourseIdAndFieldOfInterest['CategoryId'] = $_COOKIE['regsitration_field_of_interest'];
					$desiredCourseIdAndFieldOfInterest['ldbCourseId'] = 0;
					$url_data['desiredCourseIdAndFieldOfInterest'] = $desiredCourseIdAndFieldOfInterest;	
				}
			}
			deleteTempUserData('regsitration_field_of_interest');
			deleteTempUserData('regsitration_desired_course');
		}

		//below line is used for conversion tracking purpose
		$trackingPageKeyId=$this->input->post('tracking_keyid');
		if(!empty($trackingPageKeyId))
		{
			$url_data['trackingPageKeyId']=$trackingPageKeyId;
		}
		elseif (!empty($_GET['tracking_keyid'])) {
			$url_data['trackingPageKeyId']=$_GET['tracking_keyid'];
		}

		//below code used for beacon tracking
	    $url_data['trackingpageIdentifier']='registrationPage';
	    $url_data['trackingcountryId']=2;

	    //loading library to use store beacon traffic inforamtion
	    $this->tracking=$this->load->library('common/trackingpages');
	    $this->tracking->_pagetracking($url_data);

		$this->load->view('register', $url_data);
	}
	function register_validation() {
		if (count($_POST) == 0) redirect(SHIKSHA_HOME);
		if($this->sanity_preserved()=='true')
		{
			redirect(SHIKSHA_HOME);
		}
		$resultDb = true;
		$url_data['activelink'] = 'register';
		$this->form_validation->set_rules('user_first_name', 'First Name', 'callback_name_check'); 
 	           $this->form_validation->set_rules('user_last_name', 'Last Name', 'callback_name_check'); 
		$this->form_validation->set_rules('user_email', 'Email', 'callback_email_check');
		$this->form_validation->set_rules('user_mobile', 'Mobile', 'callback_mobile_check');
		if ($this->input->post('login')) {
			$current_url = $this->input->post('currentUrl');
			$referral_url = $this->input->post('referralUrl');
			$url_data['current_url'] = $current_url;
			$url_data['referral_url'] = $referral_url;
			$firstName = $this->input->post('user_first_name');
			$lastName = $this->input->post('user_last_name'); 
			$mobile = $this->input->post('user_mobile');
			$email = $this->input->post('user_email');
			if ($this->form_validation->run()) {
				//checking the status of user
				$this->load->library("muser/User_Utility");
				$register_obj = new User_Utility;
				$resultDb = $register_obj->registerUser($firstName,$lastName,$mobile, $email);
				if ($resultDb['user_register'] == 'true') {
					if (empty($referral_url)) redirect(url_base64_decode($current_url));
					else redirect(url_base64_decode($referral_url));
				} else if ($resultDb['user_exit_in_db'] == 'true') {
					$url_data['errorMessage'] = "User already exists";
					$this->load->view('register', $url_data);
				} else {
					$this->load->view('register', $url_data);
				}
			} else $this->load->view('register', $url_data);
		}
	}
	private function sendWelcomeMailToNewUser($email, $password, $addReqInfo, $addResult, $actiontype, $userinfo) {
		$data = array();
		$isEmailSent = 0;
		try {
			$subject = "Your Shiksha Account has been generated";
			$data['usernameemail'] = $email;
			$data['userpasswordemail'] = $password;
			$content = $this->load->view('user/RegistrationMail', $data, true);
			$response = $this->alerts_client->externalQueueAdd("12", ADMIN_EMAIL, $email, $subject, $content, $contentType = "html");
			/* For Shiksha Inbox. */
			$this->load->library('Mail_client');
			$mail_client = new Mail_client();
			$receiverIds = array();
			array_push($receiverIds, $addResult['status']);
			$body = $content;
			$content = 0;
			$sendmail = $mail_client->send($appId, 1, $receiverIds, $subject, $body, $content);
		}
		catch(Exception $e) {
			// throw $e;
			error_log('Error occoured sendWelcomeMailToNewUser' . $e, 'MultipleApply');
		}
	}
	
	private function addLead($signedInUser, $addReqInfo) {
		$appId = 1;
		$LmsClientObj = new LmsLib();
		$ListingClientObj = new Listing_client();
		$addReqInfo['action'] = "mobilesite";
		if(!empty($addReqInfo['from_where']) && $addReqInfo['from_where'] == 'SEARCH') {
			$addReqInfo['action'] = "mobilesitesearch";
		}
		//	_p($addReqInfo);die('aa');
		//Add uiser to Temp table for collective lead generation
		/*if(in_array($addReqInfo['action_type'], array('MOB_COMPARE_EBrochure','MOB_COMPARE_VIEWED','MOB_COMPARE_EMAIL'))  && $addReqInfo['from_where']=='MOBILE5_COMPARE_PAGE'){
			$addLeadStatus = $LmsClientObj->insertTempLead($appId, $addReqInfo);
		}
		
		if($addReqInfo['isPaid']=="TRUE" && !in_array($addReqInfo['action_type'], array('MOB_COMPARE_EBrochure','MOB_COMPARE_VIEWED','MOB_COMPARE_EMAIL'))  && $addReqInfo['from_where']=='MOBILE5_COMPARE_PAGE'){
			$addLeadStatus = $LmsClientObj->insertTempLead($appId, $addReqInfo);
		}*/
		if($addReqInfo['isPaid']=="TRUE"){
			$addLeadStatus = $LmsClientObj->insertTempLead($appId, $addReqInfo);
		}
		
		$addReqInfo['userInfo'] = json_encode($signedInUser);
		$addReqInfo['sendMail'] = false;

		/*
		if(!empty($addReqInfo['from_where']) && in_array($addReqInfo['from_where'],array('MOBILE5_CATEGORY_PAGE','MOBILE5_INSTITUTE_DETAIL_PAGE','MOBILE5_SIMILAR_INSTITUTE_DETAIL_PAGE','MOBILE5_SIMILAR_COURSE_DETAIL_PAGE','MOBILE5_COURSE_DETAIL_PAGE','MOBILE5_COURSE_DETAIL_PAGE_OTHER','MOBILE5_SEARCH_PAGE','MOBILE5_RANKING_PAGE','MOBILE5_COLLEGE_PREDICTOR_PAGE','MOBILE5_SHORTLIST_PAGE','MOBILE5_COMPARE_PAGE'))) {
			if(in_array($addReqInfo['action_type'], array('MOB_COMPARE_EBrochure','MOB_COMPARE_VIEWED','MOB_COMPARE_EMAIL'))  && $addReqInfo['from_where']=='MOBILE5_COMPARE_PAGE'){
				$addReqInfo['action'] = $addReqInfo['action_type'];
			}
			else{
	                        $addReqInfo['action'] = "MOBILEHTML5";
			}
	        }
		
		if(!empty($addReqInfo['from_where']) && in_array($addReqInfo['from_where'],array('MOBILE5_GETEB_HOMEPAGE','MOBILE5_GETEB_SEARCH_PAGE'))) {
                        $addReqInfo['action'] = "MOBILEHTML5_GETEB";
	        }
		if(!empty($addReqInfo['from_where']) && $addReqInfo['from_where'] == 'mobile_viewedListing') {
                        $addReqInfo['action'] = "mobile_viewedListing";
        }
        if(!empty($addReqInfo['from_where']) && $addReqInfo['from_where'] == 'CP_MOB_Reco_ReqEbrochure') {
        	$addReqInfo['action'] = "CP_MOB_Reco_ReqEbrochure";
        }
        if(!empty($addReqInfo['from_where']) && $addReqInfo['from_where'] == 'MOBILE5_RANKING_PAGE') {
        	$addReqInfo['action'] = "RANKING_MOB_ReqEbrochure";
        }
        if(!empty($addReqInfo['from_where']) && $addReqInfo['from_where'] == 'SEARCH_MOB_Reco_ReqEbrochure') {
        	$addReqInfo['action'] = "SEARCH_MOB_Reco_ReqEbrochure";
        }
        if(!empty($addReqInfo['from_where']) && $addReqInfo['from_where'] == 'RANKING_MOB_Reco_ReqEbrochure') {
        	$addReqInfo['action'] = "RANKING_MOB_Reco_ReqEbrochure";
	}
	if(!empty($addReqInfo['from_where']) && $addReqInfo['from_where'] == 'LP_MOB_Reco_ReqEbrochure') {
        	$addReqInfo['action'] = "LP_MOB_Reco_ReqEbrochure";
	}
	
	if(!empty($addReqInfo['from_where']) && $addReqInfo['from_where'] == 'CAREER_COMPASS_INSTITUTE_DETAIL') {
        	$addReqInfo['action'] = "MOB_CareerCompass_Ebrochure";
	}
	if(!empty($addReqInfo['from_where']) && $addReqInfo['from_where'] == 'Mobile_MyShortlist') {
        	$addReqInfo['action'] = "NM_shortlist_REB";
	}
	*/

                $addReqInfo['action'] = "MOBILEHTML5";
                if(!empty($addReqInfo['from_where'])){
                        switch($addReqInfo['from_where']){
                            case 'MOBILE5_CATEGORY_PAGE'                    : $addReqInfo['action'] = 'MOBILE5_CATEGORY_PAGE'; break;
                            case 'MOBILE5_INSTITUTE_DETAIL_PAGE'            : $addReqInfo['action'] = 'MOBILE5_INSTITUTE_DETAIL_PAGE'; break;
                            case 'MOBILE5_SIMILAR_INSTITUTE_DETAIL_PAGE'    : $addReqInfo['action'] = 'MOBILE5_SIMILAR_INSTITUTE_DETAIL_PAGE'; break;
                            case 'MOBILE5_SIMILAR_COURSE_DETAIL_PAGE'       : $addReqInfo['action'] = 'MOBILE5_SIMILAR_COURSE_DETAIL_PAGE'; break;
                            case 'MOBILE5_COURSE_DETAIL_PAGE'               : $addReqInfo['action'] = 'MOBILE5_COURSE_DETAIL_PAGE'; break;
                            case 'MOBILE5_COURSE_DETAIL_PAGE_OTHER'         : $addReqInfo['action'] = 'MOBILE5_COURSE_DETAIL_PAGE_OTHER'; break;
                            case 'MOBILE5_SEARCH_PAGE'                      : $addReqInfo['action'] = 'MOBILE5_SEARCH_PAGE'; break;
                            case 'MOBILE5_COLLEGE_PREDICTOR_PAGE'           : $addReqInfo['action'] = 'MOBILE5_COLLEGE_PREDICTOR_PAGE'; break;
                            case 'MOBILE5_RANK_PREDICTOR_PAGE'              : $addReqInfo['action'] = 'MOBILE5_RANK_PREDICTOR_PAGE'; break;
                            case 'MOBILE5_SHORTLIST_PAGE'                   : $addReqInfo['action'] = 'MOBILE5_SHORTLIST_PAGE'; break;
                            case 'MOBILE5_GETEB_HOMEPAGE'                   : $addReqInfo['action'] = 'MOBILEHTML5_GETEB'; break;
                            case 'MOBILE5_GETEB_SEARCH_PAGE'                : $addReqInfo['action'] = 'MOBILEHTML5_GETEB'; break;
                            case 'mobile_viewedListing'                     : $addReqInfo['action'] = 'mobile_viewedListing'; break;
                            case 'CP_MOB_Reco_ReqEbrochure'                 : $addReqInfo['action'] = 'CP_MOB_Reco_ReqEbrochure'; break;
                            case 'MOBILE5_RANKING_PAGE'                     : $addReqInfo['action'] = 'RANKING_MOB_ReqEbrochure'; break;
                            case 'SEARCH_MOB_Reco_ReqEbrochure'             : $addReqInfo['action'] = 'SEARCH_MOB_Reco_ReqEbrochure'; break;
                            case 'RANKING_MOB_Reco_ReqEbrochure'            : $addReqInfo['action'] = 'RANKING_MOB_Reco_ReqEbrochure'; break;
                            case 'LP_MOB_Reco_ReqEbrochure'                 : $addReqInfo['action'] = 'LP_MOB_Reco_ReqEbrochure'; break;
                            case 'CAREER_COMPASS_INSTITUTE_DETAIL'          : $addReqInfo['action'] = 'MOB_CareerCompass_Ebrochure'; break;
                            case 'Mobile_MyShortlist'                       : $addReqInfo['action'] = 'NM_shortlist_REB'; break;
                            case 'mobmobile_viewedListing'					:$addReqInfo['action'] = 'mob_reco_widget_mailer'; break;
                            case 'MOBILE5_COMPARE_PAGE'                     : if(in_array($addReqInfo['action_type'], array('MOB_COMPARE_EBrochure','MOB_COMPARE_VIEWED','MOB_COMPARE_EMAIL'))){
                                                                                    $addReqInfo['action'] = $addReqInfo['action_type'];
                                                                                }
                                                                                break;
                            default                                         : $addReqInfo['action'] = 'MOBILEHTML5'; break;
                        }
                }
        

		$addReqInfo['tempLmsRequest'] = $addLeadStatus['leadId'];

		if($addReqInfo['isPaid'] =="TRUE"){
			$addReqInfo['tracking_page_key'] = $this->input->post('tracking_keyid',true);
			$addLeadStatus = $LmsClientObj->insertLead($appId, $addReqInfo);
		
		}
                else if( $addReqInfo['listing_type'] == 'course' && isset($addReqInfo['action']) ) {
                        //check if response was made already a day ago
						$multiLocation=array('cityId'=>$addReqInfo['cityId'],'localityId'=>$addReqInfo['localityId']);
        
                        //make entry in templmstable and templmsrequest 
                        // if( empty($row) || ($addReqInfo['isPaid'] != TRUE && $row['action'] == "mobile_viewedListing"))//if any response exist within past 24 hour then do not add entry in response tables except for "mobile_viewedListing" action of tempLMSTable
                        // {
                            $this->load->builder('ListingBuilder','listing');
                            $listingBuilder = new ListingBuilder;
                            $courseRepository = $listingBuilder->getCourseRepository();
                            $courseForFreeBrochure = $courseRepository->find($addReqInfo['listing_type_id']);
                            $this->_insertTempLmsEntryForFreeCourse($courseForFreeBrochure,$signedInUser,$addReqInfo['action'],$multiLocation,$addReqInfo['trackingPageKeyId']);
                    // }
                }

		//Add Tracking for Category page and Listing page in DB
                if(!empty($addReqInfo['from_where']) && in_array($addReqInfo['from_where'],array('MOBILE5_CATEGORY_PAGE','MOBILE5_INSTITUTE_DETAIL_PAGE','MOBILE5_COURSE_DETAIL_PAGE'))){
                        $courseId = $addReqInfo['listing_type_id'];
			if($addReqInfo['from_where']=='MOBILE5_CATEGORY_PAGE'){
				$this->activityTrack(0,$courseId,0,'MOBILE_CP_ReqEBrochure','ReqEBrochure_CatPage','NULL');
			}
                        if($addReqInfo['from_where']=='MOBILE5_COURSE_DETAIL_PAGE'){
                                $this->activityTrack(0,$courseId,0,'MOBILE_LP_ReqEBrochure','ReqEBrochure_CoursePage','NULL');
                        }			
                        if($addReqInfo['from_where']=='MOBILE5_INSTITUTE_DETAIL_PAGE'){
                                $this->activityTrack(0,$courseId,0,'MOBILE_LP_ReqEBrochure','ReqEBrochure_InstitutePage','NULL');
                        }
                }
		
	}

	// Is this unused? Can this be deleted in that case?
	private function cookie($value) {
		$value1 = $value . '|pendingverification';
		setcookie('user', $value1, time() + 2592000, '/', COOKIEDOMAIN);
		$_COOKIE['user'] = $value1;
	}
	//callback function to check mobile no. rules.
	function mobile_check($str) {
		if ($str == '') {
			$this->form_validation->set_message('mobile_check', 'The %s field is required.');
			return FALSE;
		}
		if (!ctype_digit($str)) {
			$this->form_validation->set_message('mobile_check', 'The %s field must contain digits only.');
			return FALSE;
		}
		if (strlen($str) != 10) {
			$this->form_validation->set_message('mobile_check', 'The %s field must contain a 10 digit valid number.');
			return FALSE;
		}
		$begin = array('9','8','7');
		if (!in_array((substr($str, 0, 1)) , $begin)) {
			$this->form_validation->set_message('mobile_check', 'The %s field must start with 9 or 8 or 7.');
			return FALSE;
		}
		return TRUE;
	}
	function email_check($str)
	{ 
		if ($str == '') {
			$this->form_validation->set_message('email_check', 'The %s field is required.');
			return FALSE;
		}
		$filter ="/^((([a-z]|[A-Z]|[0-9]|\-|_)+(\.([a-z]|[A-Z]|[0-9]|\-|_)+)*)@((((([a-z]|[A-Z]|[0-9])([a-z]|[A-Z]|[0-9]|\-){0,61}([a-z]|[A-Z]|[0-9])\.))*([a-z]|[A-Z]|[0-9])([a-z]|[A-Z]|[0-9]|\-){0,61}([a-z]|[A-Z]|[0-9])\.)[\w]{2,4}|(((([0-9]){1,3}\.){3}([0-9]){1,3}))|(\[((([0-9]){1,3}\.){3}([0-9]){1,3})\])))$/";
		if(!preg_match($filter,$str)){
			$this->form_validation->set_message('email_check', 'The %s field must contain valid email address.');
			return FALSE;        
		}   
		return TRUE;  
	}
	
	function course_name_check($selectValue) {
				 		
		if($selectValue == 0)
		{
		    $this->form_validation->set_message('course_name_check', '%s selection field is required');
		    return false;
		}
		else // user picked something
		{
		    return true;
		}		
		
	}
	
	function name_check($str){
		$strToValidate = stripslashes($str);   
		$allowedChars = "/^([A-Za-z0-9\s\'](,|\.|_|-){0,2})*$/";
		if($strToValidate == '' || $strToValidate == 'Your Name'){
			$this->form_validation->set_message('name_check', 'The %s field is required.');
			return false ;
		}
		$minLength = 1;
		$maxLength =50;
		if(strlen($strToValidate)< $minLength){
			$this->form_validation->set_message('name_check', "The %s field should be atleast ". $minLength ." characters.");
			return false;        
		}
		if(strlen($strToValidate) > $maxLength){
			$this->form_validation->set_message('name_check', "The %s cannot exceed ".$maxLength ." characters.");	
			return false;	 
		}
		$result = preg_match($allowedChars,$strToValidate);
		if(!$result){
			$this->form_validation->set_message('name_check', 'The %s field can not contain special characters.');
			return false;
		}
		return true;    
	}

	function forgot_pass() {
		if($this->sanity_preserved()=='true')
		{
			redirect(SHIKSHA_HOME);
		}
		global $shiksha_site_current_url;
		global $shiksha_site_current_refferal;
		$encoded_current_url = url_base64_encode($shiksha_site_current_url);
		$encoded_current_refferal = url_base64_encode($shiksha_site_current_refferal);
		$url_data['current_url'] = $encoded_current_url;
		$url_data['referral_url'] = $encoded_current_refferal;
		$url_data['status'] = '0';
		$this->load->helper(array(
					'form',
					'url'
					));

		//below code used for beacon tracking
	    $url_data['trackingpageIdentifier']='forgotPasswordPage';
	    $url_data['trackingcountryId']=2;

	    //loading library to use store beacon traffic inforamtion
	    $this->tracking=$this->load->library('common/trackingpages');
	    $this->tracking->_pagetracking($url_data);
		$this->load->view('forget_pass', $url_data);
	}

	function forgot_validation() {
		if (count($_POST) == 0) {
			redirect(SHIKSHA_HOME);
		}
		if($this->sanity_preserved()=='true')
		{
			redirect(SHIKSHA_HOME);
		}
		$resultDb = true;
		$this->form_validation->set_rules('user_email', 'Email', 'callback_email_check');        
		if ($this->input->post('login')) {
			$current_url = $this->input->post('currentUrl');
			$referral_url = $this->input->post('referralUrl');
			$url_data['current_url'] = $current_url;
			$url_data['referral_url'] = $referral_url;
	
			if ($this->form_validation->run()) {
				$resultDb = $this->sendResetPasswordMail($this->input->post('user_email'));
				if ($resultDb == true) {
					$url_data['status'] = '1';
					if (empty($referral_url)) redirect(url_base64_decode($current_url));
				        else redirect(SHIKSHA_HOME.'/muser5/MobileUser/login');
				} else {
					$url_data['status'] = '2';
					$this->load->view('forget_pass', $url_data);
				}
			}else{
				$this->load->view('forget_pass', $url_data);
			}
		}
	}

	function submit($uname, $password, $isMobileRegistration = '') {
		$mdpassword = sha256($password);
		$this->load->library('Login_client');
		$veryQuickSignUp = 'normal';
		$remember = 'on'; 
		if (strcmp($veryQuickSignUp, 'normal') === 0) {
			$remember = 1;
		}
		$appID = 12;
		$strcookie = $uname . '|' . $mdpassword;
		$login_client = new Login_client();
		$Validate = $login_client->validateuser($strcookie, 'login');
		if ($Validate != "false" && is_array($Validate)) {
			$value = $Validate[0]['cookiestr'];
			$status = $Validate[0]['status'];
			$pendingverification = $Validate[0]['pendingverification'];
			$hardbounce = $Validate[0]['hardbounce'];
			$ownershipchallenged = $Validate[0]['ownershipchallenged'];
			$softbounce = $Validate[0]['softbounce'];
			$abused = $Validate[0]['abused'];
			$emailsentcount = $Validate[0]['emailsentcount'];
			if ($abused == 1 || $ownershipchallenged == 1) {
				return false;
			} else {
				$this->load->model('user/usermodel');
				$this->usermodel->trackUserLogin($Validate[0]['userid']);

                                /**
                                 * Put shortList Cookie data into table
                                 *
                                 */
                                if(!empty($Validate[0]['userid']))
                                {
                                    $data[0]['userId'] = $Validate[0]['userid'];
                                    $data[0]['status'] = 'live';
                                    $data[0]['sessionId'] = sessionId ();
				    $shortlistlistingmodel = $this->load->model ( 'listing/shortlistlistingmodel' );
				    $shortlistlistingmodel->putShortListCouresFromCookieToDB($data,false);				    
                                }

				if ($Validate[0]['emailverified'] == 1) $value.= "|verified";
				else {
					if ($hardbounce == 1) $value.= "|hardbounce";
					if ($softbounce == 1) $value.= "|softbounce";
					if ($pendingverification == 1) $value.= '|pendingverification';
				}
				if ($remember == 'on' && $Validate[0]['usergroup'] != 'sums') {
					setcookie('user', $value, time() + 2592000, '/', COOKIEDOMAIN);
					$_COOKIE['user'] = $value;
				} else {
					setcookie('user', $value, 0, '/', COOKIEDOMAIN);
					$_COOKIE['user'] = $value;
				}
				if($isMobileRegistration == '1') {
					return $Validate[0]['userid'];
				} else {
					return true;
				}
			}
		} else {
			return false;
		}
	}

	function sendResetPasswordMail($email) {
		
		$appId = 1;
    	$register_client = new Register_client();
    	$response = $register_client->getUserIdForEmail($appId,$email);
		$requested_page = 'shiksha/index';
		$id = base64_encode($response[0]['id'].'||'.$requested_page.'||'.$email);
		$link = SHIKSHA_HOME . '/user/Userregistration/ForgotpasswordNew/'.$id;
		$is_mobile = 'Y';
		$response = \Modules::run('user/Userregistration/_sendResetPasswordNewMail',$email, $requested_page, $link, $is_mobile);
		/*
		Need to set cookie to display informational/status messages on header
		 */
		storeTempUserData("confirmation_message","Reset password instructions sent successfully to your current email id.");   
		return $response;

						
		/*$this->load->library('Register_client');
		$this->load->library('Category_list_client');
		$this->load->library('mail_client');
		$mail_client = new Mail_client();
		$appId = 1;
		$register_client = new Register_client();
		$response = $register_client->getUserIdForEmail($appId, $email);
		//	_p($response);die();
		if (!is_array($response)) {
			return false;
			exit;
		}
		if ($response[0]['ownershipchallenged'] == 1 || $response[0]['abused'] == 1) {
			if ($response[0]['ownershipchallenged'] == '1' || $response[0]['abused'] == 1)
				// echo 'deleted';
				return false;
		} else {
			$subject = "Reset your password";
			$data['usernameemail'] = $email;
			$requested_page = 'shiksha/index';
			$id = base64_encode($response[0]['id'].'||'.$requested_page.'||'.$email);
			$data['resetlink'] = SHIKSHA_HOME . '/user/Userregistration/Forgotpassword/' .$id;
			$content = $this->load->view('user/ForgotPasswordMail', $data, true);
			$headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type : text/HTML;charset=UTF-8" . "\r\n";
			$headers .= 'From: <info@shiksha.com>' . "\r\n";
			$val=mail($email,$subject,$content, $headers);
			if($val==TRUE){
			    $response=$this->alerts_client->externalQueueAdd("12",ADMIN_EMAIL,$email,$subject,$content,$contentType="html",$sendTime="0000-00-00 00:00:00",$attachment='n',$attachmentArray=array(),$ccEmail=null,$bccEmail=null,$fromUserName="Shiksha.com",$isSent="sent");
			} else {
			    $response=$this->alerts_client->externalQueueAdd("12",ADMIN_EMAIL,$email,$subject,$content,$contentType="html",$sendTime="0000-00-00 00:00:00",$attachment='n',$attachmentArray=array(),$ccEmail=null,$bccEmail=null,$fromUserName="Shiksha.com",$isSent);
			}
			
			//$response = $this->alerts_client->externalQueueAdd("12", ADMIN_EMAIL, $email, $subject, $content, $contentType = "html");
			
			//Need to set cookie to display informational/status messages on header
			
			storeTempUserData("confirmation_message","Reset password instructions sent successfully to your current email id.");   
			return true;
		} */
	}
  
    /*Cron for Send SMS for Recomendation MRecA & MRecB*/
    public function runRecommendationMobileCron(){
        $this->load->helper('mcommon5/mobile_html5');
        $this->load->model('SMS/smsModel');
        $this->load->model('user/usermodel');
        $data = $this->_getRecomendationMailerData();
        if(empty($data)){
            return;
        }
        $userIds = getUserIdFromRecomendationData($data);
     	$userIdsArr = $this->usermodel->getMobileRegisteredUsers($userIds);
        if(empty($userIdsArr)){
            return;
        }
        $formatedData = formatDataMobileUserIds($data,$userIdsArr);
        if(empty($formatedData)){
                return;
        }	
        $smsmodel_object = new smsModel();
        $this->_storeSmsRecomendationInfo($formatedData);
		
     	$dataForShortUrl = $this->usermodel->getDataForShortUrl();
        foreach($dataForShortUrl as $key=>$value){
            //code for sms start
            //$content_sms = "Since you're interested in $firstVariable in $secondVariable, you could check out some good options at ".SHIKSHA_HOME.'/r/a/'.$value['uniqueCode'];
			$content_sms = "Hi ".$value['firstname'].", based on the interest you showed on Shiksha, we have some options for you at ".SHIKSHA_HOME.'/r/a/'.$value['uniqueCode'];
            $msg = $smsmodel_object->addSmsQueueRecord("1", $value['mobile'], $content_sms, $value['userid'],0);
            //code for sms end
            $this->_storeLastMailIdForSMS($value['mailid']);
            $this->usermodel->updateStatusForSMS($value['uniqueCode']);
        }
    }

    /*private function to get for Recomendation mailer data (MRecA & MRecB)*/
	private function _getRecomendationMailerData(){
		$this->load->model('mailer/mailermodel');
		$mailermodelObj = new mailermodel();
		$result = $mailermodelObj->getRecomendationMailerData();
        return $result;
	}
    
    /*private function to store Recomendation mailer data (MRecA & MRecB) for SMS */
    private function _storeSmsRecomendationInfo($data){
        $this->load->model('user/usermodel');
        $this->usermodel->storeSmsRecomendationInfo($data);
    }
    
    /*private function to store Last send SMS in database*/
    private function _storeLastMailIdForSMS($mailid){
        $this->load->model('mailer/mailermodel');
		$mailermodelObj = new mailermodel();
		$result = $mailermodelObj->storeLastMailIdForSMS($mailid);
    }
    
    /*Function to show forgot password page on which user can change his password*/
    function Forgotpassword($id)
    {
    	$this->init();
           if(!isset($_COOKIE['ci_mobile'])){
           	$link ='/user/Userregistration/ForgotpasswordNew/'.$id;
            redirect($link);
            return;
        	}
        
		if($_COOKIE['mobile_site_user'] == 'abroad'){
	        $data['uname'] = $id;
			$data['beaconTrackData'] = array(
                                                'pageIdentifier' => 'forgotPasswordPage',
                                                'pageEntityId' => 0,
                                                'extraData' => null
                                            );
			$this->load->view("commonModule/forgotPassword",$data);
		}else{
	        $id = base64_decode($id);
	    	$uname_array = explode("||", $id);
	    	$id = $uname_array[0];
	    	$data['uname'] = $id;
	    	$email = base64_encode($uname_array[2]);
	    	$data['useremail'] = $email;
			//below code used for beacon tracking
	        $data['trackingpageIdentifier'] = 'forgotPasswordPage';
	        $data['trackingcountryId'] = 2;
			$link = SHIKSHA_HOME."/shiksha/index?uname=$id&resetpwd=1&usremail=$email";    	

	        //loading library to use store beacon traffic inforamtion
	        $this->tracking=$this->load->library('common/trackingpages');
	        $this->tracking->_pagetracking($data);

	        redirect($link);
			//$this->load->view('forgot',$data);
		}
        
    }
    
    function resetPassword()
    {
        $this->init();
        $name = $this->input->post('uname');
        $uname = $this->input->post('email');
        $password = $this->input->post('passwordr');
        $ePassword = sha256($password);

        $remember = $this->input->post('remember');
        if(!($remember == "on"))
            $remember = "off";
        $appID = 1;

        $Registerclient = new Register_client();
        $response = $Registerclient->resetPassword($appID,$name,$uname,$ePassword,$password);
        $value = $uname.'|'.$ePassword;
        if(is_array($response))
        {
            if($response[0]['abused'] == 1 || $response[0]['ownershipchallenged'] == 1)
            {
                echo 'invalid';
		exit;
            }
            else
            {
                if($response[0]['emailverified'] == 1)
                    $value .= "|verified";
                else
                {
                    if($response[0]['hardbounce'] == 1)
                        $value .= "|hardbounce";
                    if($response[0]['softbounce'] == 1)
                        $value .= "|softbounce";
                    if($response[0]['pendingverification'] == 1)
                        $value .= '|pendingverification';
                }
            }
            $userId = $response[0]['userId'];
            $response = 1;
            setcookie('user',$value,time() + 2592000,'/',COOKIEDOMAIN);
	    	$_COOKIE['user'] = $value;

	    	if($userId > 0 && $userId != '') {
	        	$this->load->model('user/usermodel');                
        		$this->usermodel->trackUserLogin($userId);
        	}
        }

        echo $response;
    }

    private function _insertTempLmsEntryForFreeCourse($courseObj,$signedInUser,$sourcePage, $multiLocation,$trackingPageKeyId) {
            if(!is_object($courseObj) || $courseObj->isPaid()) {
                return false;
            }

            // load lms client library
            $this->load->library('lmsLib');
            $lms_client_object = new LmsLib();

            // prepare data to be inserted into
            $data_array = array();
            $data_array['tracking_page_key'] = $this->input->post('tracking_keyid',true);
            $data_array['userId'] = $signedInUser[0]['userid'];
            $data_array['displayName'] = $signedInUser[0]['displayname'];
            $data_array['contact_cell'] = $signedInUser[0]['mobile'];
            $data_array['listing_type'] = "course";
            $data_array['listing_type_id'] = $courseObj->getId();
            $data_array['contact_email'] = $signedInUser[0]['email'];
	    $data_array['institute_location_id'] = $courseObj->getMainLocation()->getLocationId();
	    
            $instituteId    = $courseObj->getInstId();
	    $locData        = array('cityId'    => $multiLocation['cityId'],
				'localityId'    => $multiLocation['localityId'],
				'instituteId'   => $instituteId );
	    	    
	    if($multiLocation['cityId']!='' && $multiLocation['cityId']>0 && $multiLocation['localityId']!='' && $multiLocation['localityId']>0)
	    {
		$nationalCourseLib = $this->load->library('listing/NationalCourseLib');
		if($courseObj->getMainLocation()->getLocality()->getId() != $multiLocation['localityId'])
			$data_array['institute_location_id'] = $nationalCourseLib->getInstituteLocationIdByCityLocality($locData);
	    }

	    $data_array['sourcePage'] = $sourcePage;
        
        //below line is used for conversion tracking purpose to store tracking pagekeyid    
        if(isset($trackingPageKeyId))
        {
        	$data_array['trackingPageKeyId']=$trackingPageKeyId;
        }
	    // insert call start
	    $response = $lms_client_object->insertResponseForFreeCourse($data_array);
            return $response;
    }
	
    private function _trackFreeBrochureDownload($userInfo) {
            $this->load->model('listing/coursemodel');
            $courseModel = new coursemodel();
            $noTrackForDownload = $this->input->post('noTrackForDownload');
            if($noTrackForDownload !=1){
                 $courseModel->trackFreeBrochureDownload($userInfo);
            }
    }

    private function _logResponseActivity($arr){
        //if($arr['from_where']!='Viewed_Listing'){
                $requestArr['courseId'] = $arr['listing_type_id'];
                $requestArr['pageName'] = $this->input->post('pageName');
                $requestArr['fromWhere'] = $arr['from_where'];
                $requestArr['isHTML5'] = '1';
                $requestArr['userId'] = $arr['userId'];
                $requestArr['isPaid'] = $arr['isPaid'];
                $this->load->model('ShikshaModel');
                $this->ShikshaModel->insertResponseTracking($requestArr);
                return true;
        //}
    }

	//to render registration page.
	function renderShortRegistration() {
		$url_data = array();
		$url_data['current_url'] = $this->input->post('current_url');
		$url_data['referral_url'] = $this->input->post('referral_url');		
		$url_data['from_where'] = $this->input->post('from_where',true);
		
		$this->load->helper(array(
					'form',
					'url'
					));
			
		$displayData['boomr_pageid'] = "shortregistrationform";
		$this->load->view('shortRegistration', $url_data);
	}

	function shortRegistrationSubmit() {
		/*$signedInUser = $this->userStatus;
		$logged_in_user_array = $this->data['m_loggedin_userDetail'];
		
		if (!is_array($logged_in_user_array) && $logged_in_user_array == 'false') {
			$logged_in_user_array = array();
		} else {
			$logged_in_user_array = $logged_in_user_array[0];
		}
		
		$logged_in_userid = (!isset($logged_in_user_array['userid'])) ? '-1' : $logged_in_user_array['userid'];
		$user_logged_in = (!isset($logged_in_user_array['userid'])) ? 'false' : 'true';
		
		//if user logged in then no need to check valdiation on email field.
		$this->form_validation->set_rules('user_mobile', 'Mobile', 'callback_mobile_check');
	        $this->form_validation->set_rules('user_first_name', 'First Name', 'callback_name_check'); 
 	        $this->form_validation->set_rules('user_last_name', 'Last Name', 'callback_name_check');		
		
		if ($user_logged_in != "true") {
			$this->form_validation->set_rules('user_email', 'Email', 'callback_email_check');
		}
		if (count($_POST) == 0) {
			redirect(SHIKSHA_HOME);
		}

		$pageName = $this->input->post('pageName');
		*/
		//if ($this->input->post('login')) {
		$signedInUser = $this->userStatus;
			$signedInUser = $this->userStatus;
			//getting the values of hidden field.
			$current_url = $this->input->post('currentUrl');
			$referral_url = $this->input->post('referrer');			
			$user_email = $this->input->post('email');
			$url_data['current_url'] = $current_url;
			$url_data['referral_url'] = $referral_url;

			if ($signedInUser != "false"){		//Register user 
				//if ($this->form_validation->run()) {
					$user_first_name = $this->input->post('firstName'); 
					$user_last_name = $this->input->post('lastName'); 
					$user_mobile = $this->input->post('mobile');

					//checking the status of user
			/*		$this->load->library("muser5/User_Utility");
					$register_obj = new User_Utility;
					$result = $register_obj->registerUser($user_first_name, $user_last_name, $user_mobile, $user_email);
					if ($result['user_exit_in_db'] == 'true') {						
						$url_data['show_error'] = "User already exists.";
						echo 'user_exit_in_db#NONE';
						return;
					} */
				//}
			
			}
			else{
				$user_first_name = $signedInUser[0]['firstname'];
				$user_last_name = $signedInUser[0]['lastname']; 
			}
							
			//After user has registered, perform the operation for the user. After the operation, set the Cookie and redirect the user to the original page
			if(isset($_COOKIE['collegepredictor_email_link']) && $_COOKIE['collegepredictor_email_link']!='' && $user_first_name!='' && $user_first_name!=''){
				$contentArray['name'] = $user_first_name;
				$contentArray['toEmail'] = $user_email;
				$contentArray['link'] = json_decode($_COOKIE['collegepredictor_email_link']); // link url to be clicked
				$contentArray['text'] = 'JEE Mains 2014 College Predictor'; // constant text  : JEE Mains ....
				Modules::run('systemMailer/SystemMailer/collegePredictorMail',$user_email,$contentArray);
				Modules::run('mcollegepredictor5/CollegePredictorController/makeLogEntry',$contentArray,0,0,'Email');
				storeTempUserData("confirmation_message","Results have been mailed to you successfully.");
			}

                        //After user has registered, perform the operation for the user. After the operation, set the Cookie and redirect the user to the original page
                        if(isset($_COOKIE['onlineForm_StartApplication']) && $_COOKIE['onlineForm_StartApplication']!='' && $user_first_name!='' && $user_first_name!=''){
                                $toEmail = $user_email;
                                $params = json_decode($_COOKIE['onlineForm_StartApplication']);
                                $paramArray = explode('--',$params);
                                $courseId = $paramArray[0];
                                $instituteName = $paramArray[1];
                                $isInternal = $paramArray[2];                                
                                Modules::run('mOnlineForms5/OnlineFormsMobile/sendOnlineLinkEmail',$courseId,$instituteName,$isInternal,$toEmail);
                                storeTempUserData("onlineForm_SuccessMessage",$instituteName);
                        }
			
			//After user has loggedin,add shortlisted course in Db
			if(isset($_COOKIE['mobile-loginState']) && $_COOKIE['mobile-loginState'] !='' && $user_first_name !='')
			{
				$value = explode('::',$_COOKIE['mobile-loginState']);
				$courseId = $value[0];
				$pageType = $value[1];
				$tracking_keyid = $value[2];
				Modules::run('categoryList/AbroadCategoryList/updateShortListedCourse',$courseId,'add',$pageType,'national',$user_email,$tracking_keyid);
			}	

			
			if(isset($_COOKIE['comparepage_email_link']) && $_COOKIE['comparepage_email_link'] !='' && $user_first_name!=''){
				$pageurl = $_COOKIE['comparepage_email_link'];
				$sJson = $_COOKIE['comparepage_email_json'];
				Modules::run('MultipleApply/MultipleApply/emailCompareLayer',base64_encode($pageurl),base64_encode($sJson),$user_email);
				storeTempUserData("confirmation_message","Results have been mailed to you successfully.");
			}
			
			//After user has loggedin,update userId for made response
			if($user_first_name !='' && $user_email !='')
			{
				$signedInUser = $this->register_client->getinfoifexists(1, $user_email, 'email');
				$this->load->model('compareInstitute/compare_model');
				$this->compare_model->updateUserIdForMadeResponse($signedInUser[0]['userid']);
			}
			
			
			$string = url_base64_decode($current_url);
            if(strpos($referral_url,'customizedmmp')===false){
				
				$display_on_page = $this->input->post('display_on_page');				
				if($display_on_page == 'newmmp') {
				    $decode_refferal_url = $referral_url;
				} else {
					$decode_refferal_url = url_base64_decode($referral_url);
				}
            }
            else{
                $decode_refferal_url = $referral_url;
            }
			echo 'SUCCESS#'.$decode_refferal_url;						   
			
			
		//}
	}
	
	function showRecommendation($courseId, $widget,$customExclusionList = '',$subCategoryId = 0,$pageCityId = 0,$brochureAvailable = '',$isRankingPage=0, $pageType , $showSlide,$tracking_keyid=0,$trackingPageKeyId='')
	{
		if(!is_numeric($courseId)){
                                $response = array(
                                                'recommendationHTML' => '',
                                                'recommendedInstitutes' => array()
                                );
                                echo json_encode($response);
                                return;
		}
		$userInfo = $this->userStatus;
		$data = array();
		$data['widget'] = $widget;
		$data['userInfo'] = $userInfo;
	
		$this->load->helper('mcommon5/mobile_html5');
		$this->load->helper('listing');
		$this->load->helper('string');
		$this->load->library('categoryList/CategoryPageRecommendations');
		$this->load->library('listing/NationalCourseLib');
		$this->load->builder('ListingBuilder','listing');
		$this->load->builder('LDBCourseBuilder','LDB');
		$this->load->builder('LocationBuilder','location');
		$this->load->builder('CategoryBuilder','categoryList');
	
		$categoryBuilder = new CategoryBuilder;
		$categoryRepository = $categoryBuilder->getCategoryRepository();
	
		$listingBuilder = new ListingBuilder;
		$instituteRepository = $listingBuilder->getInstituteRepository();
		$courseRepository = $listingBuilder->getCourseRepository();
		$locationBuilder = new LocationBuilder;
		$locationRepository = $locationBuilder->getLocationRepository();
	
		$LDBCourseBuilder = new LDBCourseBuilder;
		$LDBCourseRepository = $LDBCourseBuilder->getLDBCourseRepository();
		 
		$recommendations = array();
		if($tracking_keyid == MOBILE_NL_RNKINGPGE_TUPLE_DEB) {
			$tracking_keyid = MOBILE_NL_RNKINGPGE_TUPLE_DEB_RECO;
		}
		if($tracking_keyid == MOBILE_NL_CTPG_TUPLE_DEB) {
			$tracking_keyid = MOBILE_NL_CTPG_TUPLE_DEB_RECO;
		}
		if($widget == 'LP_Reco_SimilarInstiLayer') {
				
			$LDBCourses = $LDBCourseRepository->getLDBCoursesForClientCourse($courseId);
			if(!is_array($LDBCourses) || count($LDBCourses) == 0) {
				$response = array(
						'recommendationHTML' => '',
						'recommendedInstitutes' => array()
				);
				echo json_encode($response);
				return;
			}
	
			/**
			 * If a sub-category id is defined, get similar institutes for an LDB course of that subcategory
			 */
			if($subCategoryId) {
				$subCategoryLDBCourses = $LDBCourseRepository->getLDBCoursesForSubCategory($subCategoryId);
				$subCategoryLDBCourseIds = array();
				foreach($subCategoryLDBCourses as $subCategoryLDBCourse) {
					$subCategoryLDBCourseIds[] = $subCategoryLDBCourse->getId();
				}
	
				foreach($LDBCourses as $LDBCourse) {
					if(in_array($LDBCourse->getId(),$subCategoryLDBCourseIds)) {
						$mainLDBCourse = $LDBCourse;
						break;
					}
				}
				if(!$mainLDBCourse) {
					$mainLDBCourse = $LDBCourses[0];
				}
			}
			else {
				$mainLDBCourse = $LDBCourses[0];
			}
				
				
				
			$pageCityId = intval($pageCityId);
				
			$recommendations = $this->categorypagerecommendations->getSimilarInstitutes(intval($courseId),$mainLDBCourse->getId(),$pageCityId,$customExclusionList);
				
			$stateResultsIncluded = $recommendations['stateResultsIncluded'];
			$recommendations = $recommendations['recommendations'];
				
			$seedCourse = $courseRepository->find($courseId);
				
			/**
			 * If city is not provided, take main city
			 */
			if(!$pageCityId) {
				$pageCityId = $seedCourse->getMainLocation()->getCity()->getId();
			}
				
			$pageCityObj = $locationRepository->findCity($pageCityId);
			$pageStateObj = $locationRepository->findState($pageCityObj->getStateId());
				
			$data['pageCityId'] = $pageCityObj->getId();
			$data['pageStateId'] = $pageCityObj->getStateId();
				
			if($stateResultsIncluded) {
				$data['seedCourseCity'] = $pageStateObj->getName();
			}
			else {
				$data['seedCourseCity'] = $pageCityObj->getName();
			}
				
			$data['seedCourseLDBCourse'] = $mainLDBCourse->getCourseName();
			$subCatId = $mainLDBCourse->getSubCategoryId();
			$categoryObject  = $categoryRepository->find($subCatId);
			if(!empty($categoryObject)){
				$data['seedCourseLDBCourse'] 	 = $categoryObject->getShortName();
			}
		}
		else {
			$recommendations = $this->categorypagerecommendations->getAlsoViewedInstitutes(intval($courseId));
		}
	
		$recommendations = array_slice($instituteRepository->findWithCourses($recommendations),0,9);

		$recommendationsApplied = array();
		$recommendedInstitutes = array();
		$recommendedCourses = array();
		foreach($recommendations as $institute) {
			$course = $institute->getFlagshipCourse();
			$courseID = $course->getId();
			if(isset($_COOKIE['applied_'.$courseID]) && $_COOKIE['applied_'.$courseID] == 1) {
				$recommendationsApplied[] = $courseID;
			}
			$recommendedInstitutes[] = $institute->getId();
			$recommendedCourses[] = $courseID;
		}
		$recommendationsApplied = array_values(array_unique($recommendationsApplied));
		$data['tracking_keyid'] = $tracking_keyid;
		$data['recommendationsExist'] = count($recommendations) > 0 ? 1 : 0;
		$data['numberOfRecommendations'] = count($recommendations) > 9 ? 9 : count($recommendations);
		$data['appliedCourse'] = $courseRepository->find($courseId);
		$data['recommendations'] = $recommendations;
		$data['recommendationsApplied'] = $recommendationsApplied;
		$data['brochureURL'] = $this->nationalcourselib->getMultipleCoursesBrochure($recommendedCourses);
		$data['uniqId'] = random_string('alnum', 6);
		$data['pageType'] = $pageType;
		
		//below line is used for coinversion tracking purpose
		if($trackingPageKeyId!='')
		{
			$data['trackingPageKeyId']=$trackingPageKeyId;
		} else {
			$data['trackingPageKeyId']=$tracking_keyid;
		}
		 
		$data['brochureAvailable'] = $brochureAvailable;
		if($data['brochureAvailable'] && strpos($_SERVER["HTTP_USER_AGENT"],"iPad")){
			$national_course_lib = $this->load->library('listing/NationalCourseLib');
			$data['brochureUrl'] = $national_course_lib->getCourseBrochure($courseId);
		}
		$data['isRankingPage'] = $isRankingPage;
		
	
		if($showSlide == 'yes') {
			$recommendationHTML = $this->load->view('recomendationsLayer',$data,TRUE);			
		}else {
			$recommendationHTML = $this->load->view('recomendationsLayerListing',$data,TRUE);
		}

		if($widget == 'LP_Reco_AlsoviewLayer' || $widget == 'LP_Reco_SimilarInstiLayer') {
			$response = array(
					'recommendationHTML' => $recommendationHTML,
					'recommendedInstitutes' => $recommendedInstitutes
			);
			echo json_encode($response);
		}
		else {
			echo $recommendationHTML;
		}
	}
	
        public function activityTrack($courseId, $clickedCourseId, $instituteId, $action, $widget, $algo) {
            $userInfo = $this->checkUserValidation();
            if($userInfo == 'false') {
                $userId = 0;
            }
            else {
                $userId = $userInfo[0]['userid'];
            }

            $this->load->model('common/viewcountmodel');
            $this->viewcountmodel->increaseActivityTrackCount($courseId, $clickedCourseId, $instituteId, $action, $widget, $algo, $userId);
        }	
	
	public function showRegistrationForm() {
		$url_data = array();
		$url_data['reg_action'] = $this->input->post('action');
		$url_data['course_id'] = $this->input->post('course_id');
		$url_data['selected_course'] = $this->input->post('course_id');
		$url_data['show_course_selected'] = $this->input->post('show_course_selected');
		$url_data['from_where'] = $this->input->post('from_where');
		$url_data['referrer'] = $this->input->post('current_url');
		$url_data['redirect_url'] = $this->input->post('redirect_url');
		$url_data['signedInUser'] = $this->userStatus;
		$url_data['isFullRegisteredUser_mobile'] = 0;
		$url_data['user_logged_in'] = 'false';
		$url_data['residenceCity'] = $this->input->post('residenceCity');
		$url_data['examName'] = $this->input->post('examName');
		// Pass the tracking key id to the login form
		$url_data['tracking_keyid'] = $this->input->post('tracking_keyid');
		if(empty($url_data['course_id'])) {
			redirect(SHIKSHA_HOME);
		}
		

		if(isset($_POST['actionPointShortlist']) && $_POST['actionPointShortlist']!=''){
			$actionPoint = $_POST['actionPointShortlist'];
			$url_data['actionPoint'] = $actionPoint;
			
		}

		if($url_data['signedInUser'] != 'false') {
			$url_data['user_logged_in'] = 'true';
			$url_data['isFullRegisteredUser_mobile'] = 0;
			
			$userInfoArray = Modules::run('registration/Forms/getUserProfileInfo', $url_data['signedInUser'][0]['userid']);
			
			$url_data['graduationYear'] = isset($userInfoArray['graduationCompletionYear']) ? $userInfoArray['graduationCompletionYear'] : 0;
			$url_data['xiiYear'] = isset($userInfoArray['xiiYear']) ? $userInfoArray['xiiYear'] : 0;
		}
		
		$this->load->helper(array('form', 'url'));

		if(!empty($url_data['residenceCity']) && !empty($url_data['examName'])) {
			$this->getRegistrationHookFromSearchData($url_data);
		}
		
		switch ($url_data['reg_action']) {
			case 'shortlist':
				$viewToBeLoaded = 'shortlistRegistrationForm';
				break;
			case 'registrationHookFromSearch':
				$viewToBeLoaded = 'registrationHookFromSearchOverview';
				break;
			default:
				$viewToBeLoaded = 'register';
		}
		
		$this->load->view($viewToBeLoaded, $url_data);
	}


	/**
    * Purpose       : to get some additional data which is to be shown on registration form (LF-2876)
    * Params        : $url_data (contains all the data)
    * Author        : Ankit Garg
    * date          : 2015-04-20
    */
	function getRegistrationHookFromSearchData(&$url_data) {

		$CategoryPageModel = $this->load->model('categoryList/categorypagemodel');
		$searchDetailsArr = array('categoryId' => 3,
								  'subCategoryId' => 23,
								  'cityId' => $url_data['residenceCity'],
								  'examName' => $url_data['examName'],
								  'limit' => 1);		
		$categoryPageData = $CategoryPageModel->getNonZeroCategoryPages($searchDetailsArr);
		
		if(empty($categoryPageData)) {
			$searchDetailsArr = array('categoryId' => 3,
								  'subCategoryId' => 23,
								  'cityId' => $url_data['residenceCity'],
								  'limit' => 1);
			$categoryPageData = $CategoryPageModel->getNonZeroCategoryPages($searchDetailsArr);
			if(empty($categoryPageData)) {
				$categoryPageData[0] = array('category_id' => 3,
											 'sub_category_id' => 23,
											 'city_id' => $url_data['residenceCity'],
											 );
			}
		}
		if(!empty($categoryPageData[0])) {
			$categoryPageData = $categoryPageData[0];
			//get category page url
			$this->load->library('categoryList/CategoryPageRequest');
			$request = new CategoryPageRequest();
			$request->setNewURLFlag(1);
			$request->setData(array(
				'categoryId'    =>	$categoryPageData['category_id'],
				'subCategoryId' =>	$categoryPageData['sub_category_id'],
				'LDBCourseId'   =>	$categoryPageData['ldbCourseId'],
				'cityId'        =>	$categoryPageData['city_id'],
				'stateId'       =>	$categoryPageData['state_id'],
				'countryId'     =>	$categoryPageData['country_id'],
				'feesValue'     =>	$categoryPageData['fees'],
				'examName'      =>	$categoryPageData['exam']
				));

			$categoryPageData['url'] = $request->getURL();
			$categoryPageData['cityName'] = $request->urlManagerObj->getCityName();
			
			$falseAverageSalary 				= array('3-4 Lakhs', '4-5 Lakhs', '5-6 Lakhs');
			$falseCourseRatingData 				= array('3-4 Star', '4-5 Star', '3.5-4.5 Star');
			$url_data['falseAverageSalary'] 	= $falseAverageSalary[array_rand($falseAverageSalary)];
			$url_data['falseCourseRatingData'] 	= $falseCourseRatingData[array_rand($falseCourseRatingData)];
			$url_data['categoryPageData'] 		= $categoryPageData;
			$url_data['redirect_url'] 			= base64_encode($categoryPageData['url']);
		}
	}
}
