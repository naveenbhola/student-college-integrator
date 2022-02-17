<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * controller class for registration / response creation / any other user oriented functionality
 */
class User extends MX_Controller //ShikshaMobileWebSite_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}

	function stub()
	{	
	}
	
	function privacyPolicy()
	{
		  
		$this->load->view("privacyPolicy");
	}
	
	function termsandCondition()
	{
		$this->load->view("termsandCondition");
	}
	
	function getUserData(){
		$validateuser = $this->checkUserValidation();
		if($validateuser !== 'false') {
			$this->load->model('user/usermodel');
			$usermodel = new usermodel;
			$userId = $validateuser[0]['userid'];
			$user = $usermodel->getUserById($userId);
			if(!is_object($user))
			{
				$loggedInUserData = false;
				$checkIfLDBUser = 'NO';
			}
			else
			{
				$name = $user->getFirstName().' '.$user->getLastName();
				$email = $user->getEmail();
				$userFlags = $user->getFlags();
				$isLoggedInLDBUser = $userFlags->getIsLDBUser();
				$checkIfLDBUser = $usermodel->checkIfLDBUser($userId);
				// get user preference (tUserPref) for the case when user is already logged in
				$pref = $user->getPreference();
				if(is_object($pref)){
					$desiredCourse = $pref->getDesiredCourse();
				}else{
					$desiredCourse = null;
				}
				$loc = $user->getLocationPreferences();
				$isLocation = count($loc);
				$loggedInUserData = array('userId' => $userId, 'name' => $name, 'email' => $email, 'isLDBUser' => $isLoggedInLDBUser, 'desiredCourse' => $desiredCourse, 'isLocation'=>$isLocation);
			}
		}
		else {
			$loggedInUserData = false;
			$checkIfLDBUser = 'NO';
		}
		return array('validateuser'=>$validateuser,'loggedInUserData'=>$loggedInUserData,'checkIfLDBUser'=>$checkIfLDBUser);
	}

	public function getNewNotificationCount(){
		$validateuser = $this->checkUserValidation();
        if($validateuser === "false"){
            echo json_encode(0);
        }else{
        	$userId = $validateuser[0]['userid'];
        	$abroadNotificationLib = $this->load->library('studyAbroadCommon/AbroadNotificationsLib');
			$notificationData = $abroadNotificationLib->getStudyAbroadUserNotification($userId);
			$newNotificationCount = $notificationData['newNotificationCount'];
	        echo json_encode($newNotificationCount);
        }
	}

	public function getMobileNotificationOverlay(){
        $validateuser = $this->checkUserValidation();
        if($validateuser === "false"){
            echo json_encode(0);
        }else{
        	$userId = $validateuser[0]['userid'];
        	$abroadNotificationLib = $this->load->library('studyAbroadCommon/AbroadNotificationsLib');
			$displayData['notificationData'] = $abroadNotificationLib->getStudyAbroadUserNotification($userId);
			$shortlistListingLib = $this->load->library('listing/ShortlistListingLib' );
			$data = array('userId'=>$userId,'scope'=>'abroad');
            $displayData['shortListedCourses'] = $shortlistListingLib->fetchIfUserHasShortListedCourses($data);
            $rmcLib = $this->load->library('rateMyChances/ShikshaApplyCommonLib');
            $displayData['rmcCount'] = $rmcLib->getRMCCount($userId);
           
           	$displayData['isCandidate'] = $rmcLib->checkUserIsCandidate($userId);	        
	        
            $displayData['name'] = $validateuser[0]['firstname'].' '.$validateuser[0]['lastname'];
            
        	$userProfilePageLib = $this->load->library('userProfilePage/userProfilePageLib');
            $displayData['profilePageUrl'] = $userProfilePageLib->getUserProfilePageURL($validateuser[0]['displayname'], 'viewPage', false);
            $displayData['profilePageUrl'] = json_decode($displayData['profilePageUrl'], true)['url'];

            $displayData['profileImgUrl'] = getUserProfileImageLink(trim($validateuser[0]['avtarurl']));
            $displayData['profileImgUrl'] = resizeImage($displayData['profileImgUrl'], 'm');
            $displayData['profileImgUrl'] = getProfileImageAbsoluteUrl($displayData['profileImgUrl']);

            $result = array(
            		'notificationLayerHTML'	=> json_encode($this->load->view('commonModule/mobileNotificationLayer',$displayData,true)),
            		'shortlistCount'		=> $displayData['shortListedCourses']['count']
            		);
	        echo json_encode($result);
        }
    }
	
	public function inLineLoginForm(){
		$Validate = $this->checkUserValidation();
		if($Validate !== 'false') {
                $userId 	= $Validate[0]['userid'];
				$shikshaApplyCommonLib		= $this->load->library('rateMyChances/ShikshaApplyCommonLib');
				$RMCCourseAndUnivObj = $shikshaApplyCommonLib->getRMCCoursesAndUniversitiesByUser($userId);
				if(count($RMCCourseAndUnivObj['courses'])>0){
					$url = SHIKSHA_STUDYABROAD_HOME.'/my-saved-courses';
				}else{
					$url = SHIKSHA_STUDYABROAD_HOME;
				}
				redirect($url, 'location');
		}else{
			$displayData['hideRegisterLink'] = true;
			$displayData['trackForPages'] = true; //For JSB9 Tracking
			$displayData['hideFooter']       = true;
			$displayData['beaconTrackData'] = array(
										            'pageIdentifier' => 'loginPage',
										            'pageEntityId' => 0,
										            'extraData' => null
										        );

			$signupFormOptionLib = $this->load->library('studyAbroadCommon/signUpFormOptionLib');
			$displayData['skipSignupLink'] = $signupFormOptionLib->checkIfAlreadyRegisteredCase();
			//cookie
			$signupFormLib = $this->load->library('studyAbroadCommon/AbroadSignupLib');
			$signupFormLib->getSignupFormParams($displayData);

			$this->load->view('inLineLoginForm/inLineLogin',$displayData);
		}
	}

	public function getAbroadRegistrationForm($data=array()){
		$layerHeading = $this->input->post("layerHeading",true);
		if(empty($data)){
			$data['trackingPageKeyId'] = $this->input->post("trackingPageKeyId",true);
			$data['responseAction'] = $this->input->post('responseAction',true);
		}
		//else it is already there in the data
		/*else{
			$trackingPageKeyId = $data["trackingPageKeyId"];
		}*/
		$data['layerHeading'] = $layerHeading;
		// cases like shortlist go here 
		if($data['MISTrackingDetails']['conversionType'] == 'Course shortlist' && $data['courseId']>0)
		{
			$data['listingTypeIdForBrochure'] = $data['courseId'];
			$data['shortlistSource'] = $data['MISTrackingDetails']['page'];
			$data['forShortlist'] = true;
		}else if($data['singleSignUpFormType'] == 'scholarshipResponse'){
			$data['listingTypeIdForBrochure'] = $data['scholarshipId'];
			$data['listingTypeForBrochure'] = "scholarship";
		}
		else{ // regular response goes here
			$data['listingTypeIdForBrochure'] = $this->input->post('listingTypeIdForBrochure',true);
		}
		
		$data['scholarshipResponseSource'] = $this->input->post('scholarshipResponseSource',true);
		$data['scholarshipResponse'] = $this->input->post('scholarshipResponse',true);
		if($data['scholarshipResponseSource'] == "" && $data['scholarshipId']>0)
		{
			$data['scholarshipResponseSource'] = $data['sourcePage'];
			$data['scholarshipResponse'] = true;
		}
        $data['layerShow'] = $this->input->post('layerShow',true);
        if(empty($data['layerShow'])){
        	$data['layerShow'] = 'register';
        }               
		$this->_getUserDataForRegistrationForm($data);

		if(!is_null($data['userFormData']) && $data['scholarshipResponse']){
			$data['userFormData']['singleSignUpFormType'] =  'scholarshipResponse';
		}
		$html  = $this->load->view("registration/mobileRegistrationAbroad",$data,true); 
		$response['html'] = $html;
		return $response;
		//echo json_encode($response);
	}
        private function _getUserDataForRegistrationForm(&$data){
            $userData = $this->checkUserValidation();
            if($userData!=='false'){
                $additionalDataWithParams['returnDataFlag'] = 1;
                $checkIsValidResponseUser = Modules::run("registration/Forms/isValidAbroadUser",
                                                    $userData[0]['userid'],
                                                    '',
                                                    'studyAbroadRevamped',
                                                    $additionalDataWithParams
                                                    );
//                _p($checkIsValidResponseUser);die;
                $userFormData['userInfoArray'] = $checkIsValidResponseUser['userInfoArray'];
                $userFormData['userDetails'] = $userData;
                // $userFormData['singleSignUpFormType'] =  'scholarshipResponse';
                $abroadSignUpLib = $this->load->library('studyAbroadCommon/AbroadSignupLib');
                $abroadSignUpLib->prepareDataForLoggedInUser($userFormData);
                $userFormData['userInfoArray'] = $checkIsValidResponseUser['userInfoArray'];
                $data['userFormData'] = $userFormData;
            }            
        }
}