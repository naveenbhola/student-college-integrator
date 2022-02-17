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
		$url_data = array();
		$url_data['current_url'] = $this->input->post('current_url');
		$url_data['referral_url'] = $this->input->post('referral_url');
		$url_data['courseArray'] = $this->input->post('courseAttr');
		
		$url_data['from_where'] = $this->input->post('from_where',true);
	        $url_data['selected_course'] = $this->input->post('selected_course',true);            
		
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
		
		$displayData['boomr_pageid'] = "request_ebrouchre";
		
		$this->load->view($viewToBeLoaded, $url_data);
	}
	
	private function _getViewToBeLoaded() {
		$responseArray = array();
		$this->load->library('ANA/messageBoardProxy');
		$messageboardproxy = new messageBoardProxy($this->ci_mobile_capbilities);			
		if($messageboardproxy->getDeviceObj()) {
			global $listings_with_localities;
			$responseArray['listings_with_localities'] = json_encode($listings_with_localities);
			$responseArray['viewFile'] = "requestEbrochureSmartPhones";				
		} else {
			$responseArray['viewFile'] = "request";
		}
		
		return $responseArray;
	}

	
	function request_validation() {
		$courseArray = $this->input->post('courseArray');			
		if(empty($courseArray))
		{
			redirect(SHIKSHA_HOME);
		}
		$signedInUser = $this->userStatus;
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
		
		$form_course_info = array();
		$form_preferred_city = $this->input->post('preferred_city_dropdown');
		$form_course_Id = $this->input->post('list');
		if(strpos($form_course_Id, "|") === false && $form_preferred_city != "") {
			$form_course_info['course_id'] = $form_course_Id;
		}
		
		if(isset($form_preferred_city) && $form_preferred_city != "") {			
			$form_course_info['course_preferred_city'] = $form_preferred_city;
			$form_preferred_locality = $this->input->post('preferred_locality_dropdwon');
			
			
			if(isset($form_preferred_locality) && $form_preferred_locality != "") {
				$form_course_info['course_preferred_locality'] = $form_preferred_locality;
			}
			
			// $form_course_info['flag_coursewise_customized_locality'] = $this->input->post('hidden_flag_coursewise_customized_locality');
		}
		
		$responseArray = $this->_getViewToBeLoaded();		
		$viewToBeLoaded = $responseArray['viewFile'];
		if(isset($responseArray['listings_with_localities']) && $responseArray['listings_with_localities'] != "") {
			$this->form_validation->set_rules('list', 'Course', 'callback_course_name_check');
			$url_data['listings_with_localities'] = $responseArray['listings_with_localities'];
		}
				
		if ($this->input->post('login')) {
			
			//getting the values of hidden field.
			$current_url = $this->input->post('currentUrl');
			$referral_url = $this->input->post('referralUrl');
			$courseArray = $this->input->post('courseArray');			
			$selectedCourseId = $this->input->post('list');
			$selectedCourseId = explode('|',$selectedCourseId);
			
			$url_data['current_url'] = $current_url;
			$url_data['referral_url'] = $referral_url;
			$url_data['courseArray'] = $courseArray;
			$url_data['selected_course'] = ($form_course_info['course_id'] == "" ? $form_course_Id : $form_course_info['course_id']);
			
			if ($this->form_validation->run()) {
				$user_first_name = $this->input->post('user_first_name'); 
 	                        $user_last_name = $this->input->post('user_last_name'); 
				$user_mobile = $this->input->post('user_mobile');
				$form_course_info['user_email'] = $user_email = $this->input->post('user_email');
				$form_course_info['from_where'] = $this->input->post('from_where',true);
				if ($user_logged_in == "true")
				{
					if ($user_first_name != $signedInUser[0]['firstname']) { 
 		                                                $updatedStatus = $this->register_client->updateUserAttribute(1, $signedInUser[0]['userid'], 'firstname', $user_first_name); 
 		                                        } 
 		                                        if ($user_last_name != $signedInUser[0]['lastname']) { 
 		                                                $updatedStatus = $this->register_client->updateUserAttribute(1, $signedInUser[0]['userid'], 'lastname', $user_last_name); 
	                                        } 
					if ($user_mobile != $signedInUser[0]['mobile']) {
						$updatedStatus = $this->register_client->updateUserAttribute(1, $signedInUser[0]['userid'], 'mobile', $user_mobile);
					} 
				}
				else
				{
					//checking the status of user
					$this->load->library("muser/User_Utility");
					$register_obj = new User_Utility;
					$result = $register_obj->registerUser($user_first_name, $user_last_name, $user_mobile, $user_email);
					
					if ($result['user_exit_in_db'] == 'true') {						
						$url_data['show_error'] = "User already exists.";
						$url_data['preselected_course_id'] = $url_data['selected_course'];
						$url_data['form_preferred_city'] = $form_preferred_city;
						$url_data['form_preferred_locality'] = $form_preferred_locality;
						
						$this->load->view($viewToBeLoaded, $url_data);						
						return;
					} 
				}
				
				$signedInUser = $this->register_client->getinfoifexists(1, $user_email, 'email');
				$addReqInfoVars = $this->_getRequiredInfoForEbrochure($courseArray, $form_course_info, $signedInUser,$selectedCourseId);
				$this->sendEbrochureToUser($signedInUser, $addReqInfoVars);
				$applied_courses_array = array();
				if($_COOKIE['applied_courses']) {
				    $applied_courses_array = json_decode(base64_decode($_COOKIE['applied_courses']),true);
				    $applied_courses_array[] = $addReqInfoVars['listing_type_id'];
				} else {
				    $applied_courses_array[] = $addReqInfoVars['listing_type_id'];
				}
				$applied_courses_array = array_unique($applied_courses_array);
				setcookie('applied_courses',base64_encode(json_encode($applied_courses_array)),0,'/',COOKIEDOMAIN);
				setcookie('applied_courses_message','YES',0,'/',COOKIEDOMAIN);
				redirect(url_base64_decode($current_url));
			} else {				
				$url_data['preselected_course_id'] = $url_data['selected_course'];
				$url_data['form_preferred_city'] = $form_preferred_city;
				$url_data['form_preferred_locality'] = $form_preferred_locality;				
				$this->load->view($viewToBeLoaded, $url_data);
			}
		}
	}
	
	
	
	private function _getRequiredInfoForEbrochure($courseArray, $form_course_info, $signedInUser,$selectedCourseId) {
		$courseId = ''; $instiTitle = ''; $couresUrl = ''; $courseName = '';
				
		if(isset($form_course_info['course_id']) && $form_course_info['course_id'] != "") {
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
		} else {
			/*
			 * For NON Js supported Mobile phones..
			 */
			$courseAtrr_decoded = (base64_decode($courseArray, false));
			$courseAtrr_unserialized = (unserialize($courseAtrr_decoded));
		    error_log('testsssstheeee'.print_r($courseAtrr_unserialized,true));	
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
		error_log('testsssstheeeetheidcourse'.$courseId);
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
		/*
		if(isset($form_course_info['flag_coursewise_customized_locality']) && $form_course_info['flag_coursewise_customized_locality'] == 1) {
			$addReqInfoVars['flag_coursewise_customized_locality'] = $form_course_info['flag_coursewise_customized_locality'];
		}*/
		//$this->_logResponseActivity($addReqInfoVars);	
		return $addReqInfoVars;
	}	
	
	function sendEbrochureToUser($signedInUser, $addReqInfoVars) {
		$insTitleName = $addReqInfoVars['listing_title'];
		$displayname = $addReqInfoVars['firstname'];
		$contact_cell = $signedInUser[0]['mobile'];
		$userId = $signedInUser[0]['userid'];
		$contact_email = $addReqInfoVars['contact_email'];
		$userInfo = $signedInUser;
		if ( $addReqInfoVars['flag_already_user'] != 'true')
		{
			/*
			Need to set cookie to display informational/status messages on header
			 */
            if($addReqInfoVars['from_where'] !='SEARCH') {
			    storeTempUserData("confirmation_message","Thank you for your request. You will be receiving E-brochure of the selected institute(s) in your mailbox shortly.");
            }
			storeTempUserData("flag_google_adservices","E-brochure");
		}
		$this->addLead($signedInUser, $addReqInfoVars);
		$data = array();
		/*
		$subject = "Your application has been submitted successfully";
		$content = 'Dear ' . $displayname . '<br /><br />Your request for further information has been successfully submitted to the following institute<br /><br />' . $insTitleName . '<br /><br />The institute(s) shall get in touch with you soon at your contact details mentioned by you to proceed with admission process.<br /><br />Your Email - ' . $contact_email . '<br />Your Phone Number - ' . $contact_cell . '<br/> <br />If the above is not correct kindly login to Shiksha.com account and click on account and
			setting to update the same.<br /><br />Thank you for using <a href="http://www.shiksha.com/">Shiksha.com</a> for your education search.';
		$data['usernameemail'] = $contact_email;
		$data['content'] = $content;
		$content = $this->load->view('PasswordChangeMail', $data, true);
		$response = $this->alerts_client->externalQueueAdd("12", ADMIN_EMAIL, $contact_email, $subject, $content, "html", "0000-00 -00 00:00:00");
		*/
                $brochureURL = '';
                $ListingClientObj = new Listing_client();
		if(!empty($addReqInfoVars['listing_type_id'])){
	                $brochureData = $ListingClientObj->getUploadedBrochure($appId,$addReqInfoVars['listing_type'],$addReqInfoVars['listing_type_id']);
        	        $instituteName = $brochureData['instituteName'];
                	$courseName = $brochureData['courseName'];
		}
                
                //Create the Content for the Mailer
                if($addReqInfoVars['listing_type'] == 'course') {				
                        $subject = "E-brochure of ".htmlentities($courseName)." requested by you.";
                        $content = 'Dear '.$displayname.','.'<br /><p>Please find attached e-brochure for '.htmlentities($courseName).' at '.htmlentities($instituteName).'.</p>You can also view latest updates about this course by visiting <a href="http://www.shiksha.com/getListingDetail/'.$addReqInfoVars['listing_type_id'].'/course">Shiksha.com</a>.';
                }
                else {
                        $subject = "E-brochure of ".htmlentities($instituteName)." requested by you.";
                        $content = 'Dear '.$displayname.','.'<br /><p>Please find attached e-brochure for '.htmlentities($instituteName).'.</p>You can also view latest updates about this course by visiting <a href="http://www.shiksha.com/getListingDetail/'.$addReqInfoVars['listing_type_id'].'/institute">Shiksha.com</a>.';
                }
                
                $sendTextMailer = true;
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
                        $data['content'] = $content;
                        $data['usernameemail'] = $contact_email;
                        $content = $this->load->view('user/PasswordChangeMail',$data,true);					

                        $response=$this->alerts_client->externalQueueAdd("12",ADMIN_EMAIL,$contact_email,$subject,$content,"html",'','y',$attachmentArray);
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
                                $data['content'] = $content;
                                $data['usernameemail'] = $contact_email;
                                $content = $this->load->view('user/PasswordChangeMail',$data,true);					
        
                                $response=$this->alerts_client->externalQueueAdd("12",ADMIN_EMAIL,$contact_email,$subject,$content,"html",'','y',$attachmentArray);
                                $sendTextMailer = false;
                        }
                }
                
                //If non of the Brochures are found, send a Text mailer 
                if($sendTextMailer && $addReqInfoVars['from_where']!='mobile_viewedListing'){
                        $subject = "Your application has been submitted successfully";
                        $content = 'Dear ' . $displayname . '<br /><br />Your request for further information has been successfully submitted to the following institute<br /><br />' . $insTitleName . '<br /><br />The institute(s) shall get in touch with you soon at your contact details mentioned by you to proceed with admission process.<br /><br />Your Email - ' . $contact_email . '<br />Your Phone Number - ' . $contact_cell . '<br/> <br />If the above is not correct kindly login to Shiksha.com account and click on account and
                        setting to update the same.<br /><br />Thank you for using <a href="http://www.shiksha.com/">Shiksha.com</a> for your education search.';
                        $data['usernameemail'] = $contact_email;
                        $data['content'] = $content;
                        $content = $this->load->view('PasswordChangeMail', $data, true);
                        $response = $this->alerts_client->externalQueueAdd("12", ADMIN_EMAIL, $contact_email, $subject, $content, "html", "0000-00 -00 00:00:00");
                }

                //In case a Brochure is available for any listing, we have to track that Brochure sent entry in the DB
                if($brochureURL!='') {
                        $userInfoArray['user_id'] = $signedInUser[0]['userid'];
                        $userInfoArray['course_id'] = $addReqInfoVars['listing_type_id'];
                        $userInfoArray['brochureUrl'] = $brochureURL;
                        $userInfoArray['downloadedFrom'] = 'MOBILESITE';
                        $this->_trackFreeBrochureDownload($userInfoArray);
                }



	}
	// render HTML of login page
	function login() {
		if($this->sanity_preserved()=='true')
		{
			redirect(SHIKSHA_HOME);
		}
		global $shiksha_site_current_url;
		global $shiksha_site_current_refferal;
		$url_data['activelink'] = 'login';
		$encoded_current_url = url_base64_encode($shiksha_site_current_url);
		$encoded_current_refferal = url_base64_encode($shiksha_site_current_refferal);
		$url_data['current_url'] = $encoded_current_url;
		$url_data['referral_url'] = $encoded_current_refferal;
		$url_data['status'] = '0';
		$url_data['boomr_pageid'] = "login_page";
		$this->load->helper(array(
					'form',
					'url'
					));
		$this->load->view('login', $url_data);
	}
	function login_validation() {
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
					'rules' => 'required|max_length[25]|min_length[5]'
				     ) ,
				);
		$url_data['activelink'] = 'login';
		$this->form_validation->set_rules('user_email', 'Email', 'callback_email_check');
		$this->form_validation->set_rules($contact_validation_rules);
		if ($this->input->post('login')) {
			$current_url = $this->input->post('currentUrl');
			$referral_url = $this->input->post('referralUrl');
			$url_data['current_url'] = $current_url;
			$url_data['referral_url'] = $referral_url;
			$url_data['status'] = '0'; //0 default 1.db validated 2.rules valid but not match with db.
			if ($this->form_validation->run()) {
				$resultDb = $this->submit($this->input->post('user_email') , $this->input->post('user_pass'));
				if ($resultDb == true) {
					$url_data['status'] = '1';
					if (empty($referral_url)) redirect(url_base64_decode($current_url));
					else redirect(url_base64_decode($referral_url));
				} else {
					$url_data['status'] = '2';
					$this->load->view('login', $url_data);
				}
			} else {
				$this->load->view('login', $url_data);
			}
		}
	}
	function logout() {
		global $shiksha_site_current_refferal;
		$strcookie = $_COOKIE['user'];
		$appId = 1;
		$response = $this->login_client->logOffUser($strcookie, $appId);
		setcookie('user', '', time() - 864000, '/', COOKIEDOMAIN);
		setcookie('fbSessionKey', '', time() - 864000, '/', COOKIEDOMAIN);
		//global $logged_in_userid;
		//$this->load->model('user/usermodel');
		//$this->usermodel->trackUserLogout($logged_in_userid);
        	setcookie('applied_courses','',time() - 3600,'/',COOKIEDOMAIN);
		redirect($shiksha_site_current_refferal);
	}
	function register() {
		if($this->sanity_preserved()=='true')
		{
			redirect(SHIKSHA_HOME);
		}
		$url_data['activelink'] = 'register';
		global $shiksha_site_current_url;
		global $shiksha_site_current_refferal;
		$encoded_current_url = url_base64_encode($shiksha_site_current_url);
		$encoded_current_refferal = url_base64_encode($shiksha_site_current_refferal);
		$url_data['current_url'] = $encoded_current_url;
		$url_data['referral_url'] = $encoded_current_refferal;
		$this->load->helper(array(
					'form',
					'url'
					));
		$url_data['boomr_pageid'] = "register_page";
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
		//Add user to Temp table for collective lead generation
		$addLeadStatus = $LmsClientObj->insertTempLead($appId, $addReqInfo);
		$addReqInfo['userInfo'] = json_encode($signedInUser);
		$addReqInfo['sendMail'] = false;
		$addReqInfo['action'] = 'mobilesite';
		if(!empty($addReqInfo['from_where']) && $addReqInfo['from_where'] == 'SEARCH') {
			$addReqInfo['action'] = "mobilesitesearch";
		}
		$addReqInfo['tempLmsRequest'] = $addLeadStatus['leadId'];
		$addLeadStatus = $LmsClientObj->insertLead($appId, $addReqInfo);
		
	}
	private function cookie($value) {
		$value1 = $value . '|pendingverification';
		setcookie('user', $value1, time() + 2592000, '/', COOKIEDOMAIN);
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
		$allowedChars = "/^([A-Za-z0-9\s](,|\.|_|-){0,2})*$/";
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
					else redirect(url_base64_decode($referral_url));
				} else {
					$url_data['status'] = '2';
					$this->load->view('forget_pass', $url_data);
				}
			}else{
				$this->load->view('forget_pass', $url_data);
			}
		}
	}

	function submit($uname, $password) {
		$epassword = sha256($password);
		$this->load->library('Login_client');
		$veryQuickSignUp = 'normal';
		$remember = 'on'; 
		if (strcmp($veryQuickSignUp, 'normal') === 0) {
			$remember = 1;
		}
		$appID = 12;
		$strcookie = $uname . '|' . $epassword;
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
				if ($Validate[0]['emailverified'] == 1) $value.= "|verified";
				else {
					if ($hardbounce == 1) $value.= "|hardbounce";
					if ($softbounce == 1) $value.= "|softbounce";
					if ($pendingverification == 1) $value.= '|pendingverification';
				}
				if ($remember == 'on' && $Validate[0]['usergroup'] != 'sums') {
					setcookie('user', $value, time() + 2592000, '/', COOKIEDOMAIN);
				} else {
					setcookie('user', $value, 0, '/', COOKIEDOMAIN);
				}
				return true;
			}
		} else {
			return false;
		}
	}

	function sendResetPasswordMail($email) {
		$this->load->library('Register_client');
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
			$data['resetlink'] = SHIKSHA_HOME . '/user/Userregistration/Forgotpassword/' . $response[0]['id'];
			$content = $this->load->view('user/ForgotPasswordMail', $data, true);         
			$response = $this->alerts_client->externalQueueAdd("12", ADMIN_EMAIL, $email, $subject, $content, $contentType = "html");
			/*
			Need to set cookie to display informational/status messages on header
			 */
			storeTempUserData("confirmation_message","Reset password instructions sent successfully to your current email id.");   
			return true;
		}
	}

    private function _logResponseActivity($arr){
        //if($arr['from_where']!='Viewed_Listing'){
                $requestArr['courseId'] = $arr['listing_type_id'];
                $requestArr['pageName'] = '';
                $requestArr['fromWhere'] = $arr['from_where'];
                $requestArr['isHTML5'] = '0';
                $requestArr['userId'] = $arr['userId'];
                $requestArr['isPaid'] = '1';
                $this->load->model('ShikshaModel');
                $this->ShikshaModel->insertResponseTracking($requestArr);
                return true;
        //}
    }

        private function _trackFreeBrochureDownload($userInfo) {
                $this->load->model('listing/coursemodel');
                $courseModel = new coursemodel();
                $noTrackForDownload = $this->input->post('noTrackForDownload');
                if($noTrackForDownload !=1){
                    $courseModel->trackFreeBrochureDownload($userInfo);
                }

        }

}
