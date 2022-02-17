<?php

class UserProfile extends ShikshaMobileWebSite_Controller{

	private $sectionEditableViews = array('educationalPreferenceSection'=>'addUserEducationalPreference',
										'personalInformationSection'=>'addUserPersonalInformation',
										'workExperienceSection' => 'editWorkEx',
										'educationBackground' => 'userEducationBackground',
										'accountSettingsSection' => 'userAccountSettings'
										);
	
	private $sectionReadableViews = array('educationalPreferenceSection'=>'userEducationalPreference',
										'workExperienceSection' => 'userProfileWorkEx',
										'personalInformationSection'=>'profilePagePersonalInformation',
										'educationalBackgroundSection'=>'userProfileEducationBackground'
										);

	
	private $entityDetailView = array('Tag'=>'tagDetailTuple',
										'User'=>'followUserDetail',
										'Question'=>'questionDiscussionDetailTuple',
										'Discussion'=>'questionDiscussionDetailTuple'
									);

	private $activityTypeMapping = array('Q'=>'question','D'=>'discussion','T'=>'tag','U'=>'userprofile');

	// Function to get user profile page 
	public function displayUserProfile(){
		$this->load->library('userProfile/UserProfileLib');
		$this->userprofilelib = new UserProfileLib;

		$userData = $this->getLoggedInUserData();
		$userId = $userData['userId'];

		if(!($userId > 0)){
			header('Location: '.SHIKSHA_HOME);
			exit();
		}

		
		$this->load->model('user/usermodel');
        $usermodel 	= new usermodel;
    	$userObj   	= $usermodel->getUserById($userId);

    	if(!is_object($userObj) || !is_object($userObj->getPreference())){

    		$loggedInUserData = $this->checkUserValidation();
    		$display_name = $loggedInUserData[0]['displayname'];

    		$userPrefData = $usermodel->getUserPrefById(array($userId));
    		$extra_flag = $userPrefData[$userId]['ExtraFlag'];
    	}else{
    		$display_name = $userObj->getDisplayName();
    		$user_pref 	= $userObj->getPreference();
    		$extra_flag = $user_pref->getExtraFlag();
    	}

    	if($extra_flag == 'studyabroad'){
    		$redirect_url = $this->userprofilelib->redirectionUrl($display_name);
    		header("Location: ".$redirect_url);
    		exit;
    	}
    	
    	unset($user_pref);
    	unset($userObj);
    	unset($extra_flag);
    	unset($usermodel);
    	

		$data = $this->userprofilelib->getUserProfileDetails($userId,$profileType = 'myProfile', 'all');
		
		$data = $this->_rectifyEducationData($data);
		$data['userId'] = $userId;
		$finalData = $this->getLocationData($data['personalInfo']);
		$finalData = $this->loadUserEducationalPreferenceData($data)+$finalData;

		$finalData['WorkExRange'] =  $this->getExperienceValues();

		if($finalData['personalInfo']['Experience'] === NULL){
			$finalData['personalInfo']['Experience'] =-1;
		}

		$finalData['userId']= $userId;
		$finalData['privacyDetails']  = $this->userprofilelib->getUserPrivacyDetails($userId);

		$finalData['userLevelDetails'] = $this->userprofilelib->getUserLevelData($userId);

		$start = 0;
		$count = 10;
		$statsCardPerRow = 3;
		$userActivityStats = $this->getUserActivityStats($userId,$start,$count);
		
		$formattedUserActivityStats = $this->getFormattedUserActivityStats($userActivityStats,$statsCardPerRow);
		$formattedUserActivityStats['activities'] = $this->getURLForIndividualTupples($formattedUserActivityStats['activities']);
		
		$finalData = array_merge($formattedUserActivityStats,$finalData);

		unset($userActivityStats);
		unset($formattedUserActivityStats);

		//below code used for beacon tracking
		$finalData['trackingpageIdentifier'] = 'userProfileEditPage';
		$finalData['trackingcountryId']=2;

		//loading library to use store beacon traffic inforamtion
		$this->tracking=$this->load->library('common/trackingpages');
		$this->tracking->_pagetracking($finalData);
		

		// MAB-1387
		$finalData['expertiseInfo'] = $this->getExpertiseData($userId,$userId);
		$finalData['educationalPref'] = $this->getEduPrefTagsData($userId,$userId);
		$this->_trackProfilePageLoad($userId, 'profilePage', 'pageLoad', 'userProfileMobile');

		$this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_MyProfile');
        $finalData['dfpData']  = $dfpObj->getDFPData($this->checkUserValidation(), $dpfParam);
        $this->benchmark->mark('dfp_data_end');

		$this->load->view('userProfileMainPage',$finalData);
	}

	private function _trackProfilePageLoad($userid, $trackingKey, $trackingValue, $trackingType){
		if(empty($userid)){
			return;
		}

		$data = array();
		$data['userId'] = $userid;

		/* Tracking information */
		$data['trackingKey'] = $trackingKey;
		$data['trackingValue'] = $trackingValue;
		$data['type'] = $trackingType;

		$data['visitorSessionId'] = getVisitorSessionId();
		
		$userTrackingModel = $this->load->model('user/usertrackingmodel');
		if($userTrackingModel->saveUserProfileTracking($data) > 0){
			return true;
		}else{
			return false;
		}
	}

	/**
	* Function to fetch the Expertise data for User
	* @param $userId Integer userId for the Logged In user
	* MAB-1387 Ankit bansal
	*/
	public function getExpertiseData($loggedInUserId='',$profileUserId=''){
		$this->load->library("APIClient");  
		$APIClient = new APIClient();
		$isAjaxCall = false;
		if($loggedInUserId == ""){
			$userData = $this->getLoggedInUserData();
			$userId = $userData['userId'];
			$loggedInUserId = $profileUserId = $userId;

			if(isset($_POST['isAjaxCall'])){
				$isAjaxCall = true;
			}
		}
        $APIClient->setUserId($loggedInUserId);
        $APIClient->setVisitorId(getVisitorId());
        $APIClient->setRequestType("get");
        $jsonDecodedData =$APIClient->getAPIData("UserProfile/getUserSectionwiseDetails/".$profileUserId."/expertise");

        $result = array();

        if(isset($jsonDecodedData['expertiseInfo'])){
        	$result = $jsonDecodedData['expertiseInfo'];
        }

        if($isAjaxCall){
        	echo json_encode($result);
        }else {
        	return $result;	
        }
        
	}

		/**
	* Function to fetch the Educational Prefernce data for User
	* @param $userId Integer userId for the Logged In user
	* MAB-1387 Ankit bansal
	*/
	public function getEduPrefTagsData($loggedInUserId,$profileUserId){
		$this->load->library("APIClient");
		$APIClient = new APIClient();       
		
		if($loggedInUserId == ""){
			$userData = $this->getLoggedInUserData();
			$userId = $userData['userId'];
			$loggedInUserId = $profileUserId = $userId;
			
			if(isset($_POST['isAjaxCall'])){
				$isAjaxCall = true;
			}
		} 
        $APIClient->setUserId($loggedInUserId);
        $APIClient->setVisitorId(getVisitorId());
        $APIClient->setRequestType("get");
        $jsonDecodedData =$APIClient->getAPIData("UserProfile/getUserSectionwiseDetails/".$profileUserId."/eduPref");
        $result = array();

        if(isset($jsonDecodedData['educationalPref'])){
        	$result = $jsonDecodedData['educationalPref'];
        }
        if($isAjaxCall){
        	echo json_encode($result);
        }else {
        	return $result;	
        }
        
	}
	
	public function displayUserPublicProfile($userId){
		
		if(!($userId > 0)){
			header('Location: '.SHIKSHA_HOME);
			exit();
		}

		$this->load->library('userProfile/UserProfileLib');
		$this->userprofilelib = new UserProfileLib;	

				
		$this->load->model('user/usermodel');
        $usermodel 	= new usermodel;
    	$userObj   	= $usermodel->getUserById($userId);
    	$user_pref 	= $userObj->getPreference();
    	if(!is_object($user_pref)){
    		$extra_flag = "";
    	}else{
    		$extra_flag = $user_pref->getExtraFlag();
    	}

    	if($extra_flag == 'studyabroad'){
    		$display_name = $userObj->getDisplayName();
    		$redirect_url = $this->userprofilelib->redirectionUrl($display_name);

    		header("Location: ".$redirect_url);
    		exit;
    	}
    	
    	unset($user_pref);
    	unset($userObj);
    	unset($extra_flag);
    	unset($usermodel);
		    

		$data = $this->userprofilelib->getUserProfileDetails($userId,'publicProfile', 'all');
		$data = $this->_rectifyEducationData($data);
		$data['userId'] = $userId;
		$finalData = $this->getLocationData($data['personalInfo']);

		if(empty($finalData) || count($finalData) ==0){
			$finalData = array();
		}

		$finalData['userId'] = $userId;
		$finalData = $this->loadUserEducationalPreferenceData($data)+$finalData;
		
		$finalData['WorkExRange'] =  $this->getExperienceValues();

		$finalData['title'] = $finalData['personalInfo']['FirstName']." ".$finalData['personalInfo']['LastName']." | Shiksha.com";
		$finalData['publicProfile'] = true;
		$finalData['userLevelDetails'] = $this->userprofilelib->getUserLevelData($userId);
		$loggedInUserId = $this->getLoggedInUserData();
		if(($loggedInUserId['userId']) > 0 && $loggedInUserId['userId'] != $userId){
			$isUserFollowing = $this->userprofilelib->getFlagIfIAmFollowing($userId,$loggedInUserId['userId']);
			$finalData['isUserFollowing'] = $isUserFollowing[0]['status'];
		}
		$finalData['loggedInUserId'] = $loggedInUserId;

		$loggedInUserId = $this->getLoggedInUserData();
		if(($loggedInUserId['userId']) > 0 && $loggedInUserId['userId'] != $userId){
			$isUserFollowing = $this->userprofilelib->getFlagIfIAmFollowing($userId,$loggedInUserId['userId']);
			$finalData['isUserFollowing'] = $isUserFollowing[0]['status'];
		}
		$finalData['loggedInUserId'] = $loggedInUserId;

		$userProfilePrivacy = $this->userprofilelib->getUserPrivacyDetails($userId);

		$finalData['userProfilePrivacy']['DesiredCourse'] = $userProfilePrivacy['DesiredCourse'];
		$finalData['privacyDetails']['activitystats'] = 'private';
		if($userProfilePrivacy['activitystats'] == 'public'){
			$startCount = 0;
			$endCount = 10;
			$userActivityStats = $this->getUserActivityStats($userId,$startCount,$endCount,true);

			$finalData['privacyDetails']['activitystats'] = 'public';
			$formattedUserActivityStats = $this->getFormattedUserActivityStats($userActivityStats);
			$formattedUserActivityStats['activities'] = $this->getURLForIndividualTupples($formattedUserActivityStats['activities']);
			$finalData = array_merge($formattedUserActivityStats,$finalData);

			unset($userActivityStats);
		}
		
		// MAB-1387

		$userData = $this->getLoggedInUserData();
		$loggedInUserId = $userData['userId'];

		if($userProfilePrivacy['expertise'] == "public"){
			$finalData['expertiseInfo'] = $this->getExpertiseData($loggedInUserId,$userId);
			$finalData['educationalPref'] = array();	
		}else {
			$finalData['expertiseInfo'] = array();
			$finalData['educationalPref'] = array();
		}
		
		//below code used for beacon tracking
		$finalData['trackingpageIdentifier'] = 'userPublicProfilePage';
		$finalData['trackingcountryId']=2;

		//loading library to use store beacon traffic inforamtion
		$this->tracking=$this->load->library('common/trackingpages');
		$this->tracking->_pagetracking($finalData);

		$this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_MyProfile','pageType'=>'publicProfile');
        $finalData['dfpData']  = $dfpObj->getDFPData($this->checkUserValidation(), $dpfParam);
        $this->benchmark->mark('dfp_data_end');

		$this->load->view('userProfileMainPage',$finalData);
		unset($userProfilePrivacy);

	}

	public function getFormattedUserActivityStats($userActivityStats,$statsCardPerRow=3){

		$finalData['stats'] = $userActivityStats['stats'];
		$finalData['activities'] = $userActivityStats['activities'];
		$finalData['statCount'] = count($finalData['stats']);
		$finalData['statCountSlider'] = ($statsCardPerRow-($finalData['statCount']%$statsCardPerRow)) + $finalData['statCount']; //to print empty div for stats
		return $finalData;
	}
	

	public 	function getExperienceValues(){
		$this->workExperienceLib = new \registration\libraries\FieldValueSources\WorkExperience;
		$expValues = $this->workExperienceLib->getValues();
		unset($workExperienceLib);

		return $expValues;
	}

	/* 
	* Purpose of this private function is that integer variable names are not allowed in php;
	* Due to the php variable name constraints it was not possible to use these variable in the view.
	* As the indexes of $data are directly used as variables in views; so we need to change the indexes name. 
	*/
	private function _rectifyEducationData($data){
		$data['tenth'] = $data['10'];
		$data['twelfth'] = $data['12'];

		$levels = array('tenth', 'twelfth', 'UG', 'PG', 'PHD');
		foreach ($levels as $key => $value) {
			if(!empty($data[$value]) && is_object($data[$value]['CourseCompletionDate'])){
				$data[$value]['CourseCompletionDate'] = $data[$value]['CourseCompletionDate']->format('Y');
			}
		}

		unset($data['10']);
		unset($data['12']);
		return $data;
	}

	// Function for loading user educational preference data while displaying profile page
	public function loadUserEducationalPreferenceData($data) {

		if($data['userPreference']['ExtraFlag'] == 'studyabroad') {

			$userprofilemodeldesktop = $this->load->model('userProfile/userprofilemodeldesktop');
	    	$desiredCourseDetails = $userprofilemodeldesktop->getDesiredCourseDetailsByDesiredCourseId($data['userPreference']['DesiredCourse'], $data['userPreference']['PrefId'], $data['userPreference']['ExtraFlag']);
	    	
	    	$data['desiredCourseDetails'] = $desiredCourseDetails;
			$competitiveExam = $data['Competitive exam'];
	    	unset($data['Competitive exam']);

			$userPreference = $data['userPreference'];
			$locationPreferences = $data['locationPreferences'];

			$educationalPreferenceData = $this->loadSAEducationalPreferenceData($userPreference, $locationPreferences, $competitiveExam);

			$data['desiredCourseDetails']['subCatgoryName'] = $educationalPreferenceData['subCatgoryName'];
			$data['whenPlanToGo'] = $educationalPreferenceData['whenPlanToGo'];
			$data['countryNames'] = $educationalPreferenceData['countryNames'];
			$data['budget'] = $educationalPreferenceData['budget'];
			$data['sourceOfFunding'] = $educationalPreferenceData['sourceOfFunding'];

			unset($competitiveExam);
			$competitiveExam = $educationalPreferenceData['abroadCompetitiveExam'];

			if(!empty($competitiveExam)) {
				$data['examTaken'] = "yes";
			} else {
				$data['examTaken'] = "no";
			}

			$data['competitiveExam'] = $competitiveExam;

		} else {	
			
			$finalData = Modules::run('userProfile/UserProfileController/_getUserDeomesticInterestDetails', $data['userId']);
			$data['desiredCourseDetails'] = $finalData;
			//_p($data);exit;

			/*
			$userprofilemodeldesktop = $this->load->model('userProfile/userprofilemodeldesktop');
	    	$desiredCourseDetails = $userprofilemodeldesktop->getDesiredCourseDetailsByDesiredCourseId($data['userPreference']['DesiredCourse'], $data['userPreference']['PrefId'], $data['userPreference']['ExtraFlag']);
	    	
	    	$data['desiredCourseDetails'] = $desiredCourseDetails;
			$competitiveExam = $data['Competitive exam'];
	    	unset($data['Competitive exam']);

			$registrationForm = \registration\builders\RegistrationBuilder::getRegistrationForm('LDB',array('courseGroup' => 'localUG'));
			$exams = $registrationForm->getField('exams');
			$listExams = $exams->getValues(array('desiredCourse'=>$data['userPreference']['DesiredCourse']));
			$examNames = array();
			foreach($listExams as $exams) {
				foreach($exams as $examId => $exam) { 
					$examNames[$examId] = $exam->getDisplayNameForUser();
				}
			}
			$data['examNames'] = $examNames;
			if(!empty($data['competitiveExam'])){
				$competitiveExam = $data['competitiveExam'];
			}

			$data['competitiveExam'] = $competitiveExam;
			*/

		}

		return $data;
	}

	// Function to get user profile data while editing 
	public function showEditableUserProfileSection () {

		if(!verifyCSRF()) { return false; }

        $sectionType = $this->input->post('sectionType');
		$userEducationalPreference = $this->input->post('userEducationalPreference');
		$regFormId = $this->input->post('regFormId');
		$addMoreWorkEx = $this->input->post('addMoreWorkEx');
		$isStudyAbroadFlag = $this->input->post('isStudyAbroadFlag');
		$abroadSpecializationFlag = $this->input->post('abroadSpecializationFlag');

		if(!empty($sectionType)) {
		
			$this->load->library('userProfile/UserProfileLib');
			$userData = $this->getLoggedInUserData();	

			$userId = $userData['userId'];

			if(!empty($userId)) {

				$finalData = array();
				$skipAPICallFlag = false;


				if($sectionType == 'workExperienceSection' && $addMoreWorkEx){
					$skipAPICallFlag = true;
					$finalData['addMoreWorkEx'] = $addMoreWorkEx;
					$finalData['workExCountGlobal'] = $this->input->post('workExCountGlobal');
				}

				if(!$skipAPICallFlag){
					$finalData = $this->getUserSectionDetails($userId,$sectionType, 'edit', $userEducationalPreference);
				}
				$finalData['regFormId'] = $regFormId;
				$finalData['isStudyAbroadFlag'] = $isStudyAbroadFlag;
				$finalData['abroadSpecializationFlag'] = $abroadSpecializationFlag;

				$viewFile = $this->sectionEditableViews[$sectionType];

				$this->load->view($viewFile, $finalData);

			} else {
				echo 'error';
			}
		} else {
			echo 'error';
		}
	}

	function getUserEducationData($userId){
		$userData = array();

		$educationLevel = array('10','12','UG','PG','PHD');
		foreach ($educationLevel as $level) {

			$data = $this->userprofilelib->getUserProfileDetails($userId, 'myProfile', $level);
			$userData[$level] = $data[$level];
		}
		
		$userData['xth'] =$userData['10'];
		$userData['xiith'] =$userData['12'];
		unset($userData['10']);
		unset($userData['12']);

		if(isset($userData['xth'])){

			if($userData['xth']['Board'] == 'CBSE'){
				global $Reverse_CBSE_Grade_Mapping;
				$userData['xth']['Marks']= $Reverse_CBSE_Grade_Mapping[$userData['xth']['Marks']];
			
			} else if ($userData['xth']['Board'] == 'IGCSE'){
				
				global $Reverse_IGCSE_Grade_Mapping;
				
				$userData['xth']['Marks']= $Reverse_IGCSE_Grade_Mapping[$userData['xth']['Marks']];
				

			}
		}
		if(isset($_POST['isAddMore']) && $_POST['isAddMore'] == 'YES'){
			$userData['isAddMore'] = 'YES';
		}

		return $userData;
	}

	/* 
	 * 
	*/
	public function getEducationalPreferenceSectionData($userId, $userEducationalPreference){
		$userPreferenceData = $this->userprofilelib->getUserProfileDetails($userId, 'myProfile', 'userPreference' );
		$userPreference = $userPreferenceData['userPreference'];

		if(empty($userEducationalPreference)) {
			$userEducationalPreference = $userPreferenceData['userPreference']['ExtraFlag'];
		}

		if($userEducationalPreference == 'studyabroad') {
			$userPreference['ExtraFlag'] = 'studyabroad';
			return $this->getAbroadEducationalPreferenceDataOnEdit($userId, $userPreference);

		} else {
			//$userPreference['ExtraFlag'] = 'national';
			//return $this->getNationalEducationalPreferenceData($userId, $userPreference);
		}
	}

	// Function to get user data while editing section wise
	public function getUserSectionDetails($userId,$sectionType, $action = '', $userEducationalPreference = ''){
		$userData = array();

		switch($sectionType) {
			case 'personalInformationSection':
				$userData = $this->getPersonalInfoSectionDetails($userId);
			break;

			case 'workExperienceSection':

				$workExMaxCount =10;

				for ($workExCount=1; $workExCount <= $workExMaxCount; $workExCount++) { 
					$workExLevel = 'workExp'.$workExCount;
					$data = $this->userprofilelib->getUserProfileDetails($userId, 'myProfile', $workExLevel);
					$userData[$workExLevel] = $data[$workExLevel];
				}
/*
				 $personalInfo = $this->userprofilelib->getUserProfileDetails($userId, 'myProfile', 'personalInfo');
				 $userData['personalInfo']['Experience'] = $personalInfo['personalInfo']['Experience'];*/

				 unset($personalInfo);

				 $this->workExperienceLib = new \registration\libraries\FieldValueSources\WorkExperience;
				 $expValues = $this->workExperienceLib->getValues();
				 unset($workExperienceLib);
				 $userData['WorkExRange'] =  $expValues;

				 if($userData['personalInfo']['Experience'] === NULL){
					$userData['personalInfo']['Experience'] =-1;
				}

			break;
			
			case 'educationalPreferenceSection':
				$userData = $this->getEducationalPreferenceSectionData($userId, $userEducationalPreference);
			break;

			case 'educationBackground':
				$userData = $this->getUserEducationData($userId);
			break;

			case 'accountSettingsSection':
				$userData['flagData'] = $this->userprofilelib->getUserFlagDetails($userId);
				$this->load->config('user/unsubscribeConfig');
				$userData['mailerUnsubscribeCategory']                = $this->config->item('mailerCategory');

				$userData['unsubscribeData']  = $this->userprofilelib->getUserUnsubscribeMapping($userId);

				break;
		}

		return $userData;
	}

	// Function to get abroad educational preference data while editing
	public function getAbroadEducationalPreferenceDataOnEdit($userId, $userPreference) {

		$userData = array();

		$registrationForm = \registration\builders\RegistrationBuilder::getRegistrationForm('LDB',array('courseGroup' => 'studyAbroadRevamped','context'=>'abroadUserSetting'));
					
		$formFields = array('destinationCountry', 'fieldOfInterest', 'abroadDesiredCourse', 'desiredGraduationLevel', 'abroadSpecialization', 'whenPlanToGo', 'budget', 'examTaken', 'passport', 'examsAbroad','bookedExamDate');

		foreach($formFields as $formField) {
			$userData['fields'][$formField] = $registrationForm->getField($formField);
		}

		$this->load->model('user/usermodel');
		$userModel = new UserModel;
		$userData['abroadShortRegistrationData'] = $userModel->getAbroadShortRegistrationData($userId);

		$userData['fields']['fund'] = new \registration\libraries\FieldValueSources\Fund;

		$this->load->model('userProfile/userprofilemodeldesktop');
		
		$userData['desiredCourseDetails'] = $this->userprofilemodeldesktop->getDesiredCourseDetailsByDesiredCourseId($userPreference['DesiredCourse'], $userPreference['PrefId'], $userPreference['ExtraFlag']);

		global $studyAbroadPopularCourses;
		if(!array_key_exists($userPreference['DesiredCourse'], $studyAbroadPopularCourses)) {
			$desiredCourseForStudyAbroad = $userModel->getDesiredCourseForStudyAbroad($userPreference['DesiredCourse']);                	
    		$userData['desiredCourseDetails']['desiredGraduationLevel'] = $desiredCourseForStudyAbroad['desiredGraduationLevel'];
    	}
    	$userData['desiredCourseDetails']['abroadSpecialization'] = $userPreference['AbroadSpecialization'];

    	$locationPreferencesData = $this->userprofilelib->getUserProfileDetails($userId, 'myProfile', 'locationPreferences' );
		$userData['locationPreferences'] = $locationPreferencesData['locationPreferences'];

		$additionalInfoData = $this->userprofilelib->getUserProfileDetails($userId, 'myProfile', 'additionalInfo' );
		$userData['additionalInfo'] = $additionalInfoData['additionalInfo'];

		$sourceOfFunding = '';
		if($userPreference['UserFundsOwn'] == 'yes') {
			$sourceOfFunding = 'own';
		} else if($userPreference['UserFundsBank'] == 'yes') {
			$sourceOfFunding = 'bank';
		} else if($userPreference['UserFundsNone'] == 'yes') {
			$sourceOfFunding = 'other';
		}
		$userPreference['sourceOfFunding'] = $sourceOfFunding;
		
		$userData['userPreference'] = $userPreference;

		return $userData;

	}
	
	// Function to get national educational preference data while editing
	public function getNationalEducationalPreferenceData($userId, $userPreference) {

		$userData = array();

		$registrationForm = \registration\builders\RegistrationBuilder::getRegistrationForm('LDB',array('courseGroup' => 'localUG'));
		$userData['fields']['fieldOfInterest'] = $registrationForm->getField('fieldOfInterest');

		$competitiveExamData = $this->userprofilelib->getUserProfileDetails($userId, 'myProfile', 'Competitive exam');				

		$userData['competitiveExam'] = $competitiveExamData['Competitive exam'];

		$userprofilemodeldesktop = $this->load->model('userProfile/userprofilemodeldesktop');
		$userData['desiredCourseDetails'] = $userprofilemodeldesktop->getDesiredCourseDetailsByDesiredCourseId($userPreference['DesiredCourse'], $userPreference['PrefId'], $userPreference['ExtraFlag']);
		
		$registrationForm = \registration\builders\RegistrationBuilder::getRegistrationForm('LDB',array('courseGroup' => 'localUG'));
		$exams = $registrationForm->getField('exams');
		$listExams = $exams->getValues(array('desiredCourse'=>$userPreference['DesiredCourse']));
		$examNames = array();
		foreach($listExams as $exams) {
			foreach($exams as $examId => $exam) { 
				$examNames[$examId] = $exam->getDisplayNameForUser();
			}
		}
		$userData['examNames'] = $examNames;

		return $userData;
	}

	public function uploadProfilePicture(){
			$_POST['changeavtar']= 'upload';
			
			$files = $_FILES;

			$_FILES['myImage'] = $_FILES['file'];

			unset($_FILES['file']);

			$uploadStatus = Modules::run('user/MyShiksha/uploadFile',0,1,1);

			
			//check for all the messages and put here
			if($uploadStatus == 'File uploading failed.Please try again' || $uploadStatus == 'Please select a photo to upload'){
				echo 'Failure';
			} else{
				echo $uploadStatus;
			}

			return ;
	}

	// Function for loading user educational preference data while displaying profile page
	function loadSAEducationalPreferenceData($userPreference, $locationPreferences, $competitiveExam) {

		$data = array();

		if(!empty($userPreference['AbroadSpecialization'])) {
			$this->load->builder('CategoryBuilder','categoryList');
	    	$categoryBuilder = new CategoryBuilder;
	    	$this->categoryRepository = $categoryBuilder->getCategoryRepository();
        	$categoryData = $this->categoryRepository->find($userPreference['AbroadSpecialization']);
        	$data['subCatgoryName'] = $categoryData->getName();
    	}

    	if(!empty($userPreference['TimeOfStart'])) {
    		$whenPlanToGo = $userPreference['TimeOfStart']->format('Y');
    		if($whenPlanToGo == date('Y')+3) {
    			$whenPlanToGo .= ' or later';
    		}
    		$data['whenPlanToGo'] = $whenPlanToGo;
    	}

    	if(!empty($locationPreferences['CountryId'])) {
	        $this->load->builder('LocationBuilder','location');
    		$locationBuilder = new LocationBuilder;
    		$this->locationRepository = $locationBuilder->getLocationRepository();
        	$countryData = $this->locationRepository->getAbroadCountryByIds($locationPreferences['CountryId']);
    	
    	    $countryNames = array();
			foreach($countryData as $countryId=>$countryData) {
				$countryNames[] = $countryData->getName();
			} 
			$data['countryNames'] = $countryNames;
    	}

    	if(!empty($userPreference['Budget'])) {
			$budget = new \registration\libraries\FieldValueSources\Budget;
			$budgetValues = $budget->getValues();
			$data['budget'] = $budgetValues[$userPreference['Budget']];
		}

		if(($userPreference['UserFundsOwn'] == 'yes') || ($userPreference['UserFundsBank'] == 'yes') || ($userPreference['UserFundsNone'] == 'yes')) {

			$fund = new \registration\libraries\FieldValueSources\Fund;
			$fundValues = $fund->getValues();

			$sourceOfFunding = '';
			if($userPreference['UserFundsOwn'] == 'yes') {
				$sourceOfFunding = 'himself';
			} else if($userPreference['UserFundsBank'] == 'yes') {
				$sourceOfFunding = 'bank';
			} else if($userPreference['UserFundsNone'] == 'yes') {
				$sourceOfFunding = 'other source';
			}
			$data['sourceOfFunding'] = $sourceOfFunding;
		}

		$abroadCommonLib = $this->load->library('listingPosting/AbroadCommonLib');
		$examsAbroadMasterList = $abroadCommonLib->getAbroadExamsMasterList();
		$examsAbroadList = array();$examsAbroadListRange = array();
		foreach($examsAbroadMasterList as $exam) {
		    if($exam['status'] == 'live') {
		    	$examsAbroadList[] = $exam['exam'];
		    	$examsAbroadListRange[$exam['exam']] = explode(",",$exam['range']);
		    }
		}

		$abroadCompetitiveExam = array();
		foreach($competitiveExam['Name'] as $key=>$competitiveExamName) {
			if(in_array($competitiveExamName, $examsAbroadList)) {
				$abroadCompetitiveExam['Name'][] = $competitiveExam['Name'][$key];
				$abroadCompetitiveExam['MarksType'][] = $competitiveExam['MarksType'][$key];
				$marks = '';
				$marks = $competitiveExam['Marks'][$key];

				if($competitiveExam['MarksType'][$key] == 'grades') {
					$abroadCompetitiveExam['Marks'][] = $examsAbroadListRange[$competitiveExamName][$marks-1];
				} else {
					$abroadCompetitiveExam['Marks'][] = $marks;
				
				}
			}
		}

		$data['abroadCompetitiveExam'] = $abroadCompetitiveExam;

		return $data;
	}

	// Function to get update user profile 
	function updateUserProfile() {
		if(!verifyCSRF()) { return false; }

        $sectionType = $this->input->post('sectionType');
        $userPrivacyData = $this->input->post('privUserData');    

        $isNewWorkExpFlag ='';

    	if(!empty($sectionType)) {

			$this->load->library('userProfile/UserProfileLib');
			$userData = $this->getLoggedInUserData();	

			$userId = $userData['userId'];

			if(!empty($userId)) {
				//insert in queue for partial indexing
				$user_response_lib = $this->load->library('response/userResponseIndexingLib');	       					
				$extraData = "{'personalInfo:true'}";
				$user_response_lib->insertInIndexingQueue($userId, $extraData);
					
				$userPrivacyData = unserialize($userPrivacyData);

				$_POST['context'] = 'unifiedProfile';
				$_POST['isUnifiedProfile'] = 'yes';
				$isStudyAbroad = $this->input->post('isStudyAbroad');

				$_POST['isStudyAbroadFlag'] = $isStudyAbroad; // education background details not able to edit from national profile page in case user is abroad type because abroad user education fields are differnt. This flag used in tUserPref populator
				if(! ($sectionType == 'educationalPreferenceSection' && $isStudyAbroad == 'yes')){ // By passing while saving for abroad preference section becoz abroad fields not updating in tuserpref
            		unset($_POST['isStudyAbroad']);
            	}

            	$abroadSpecializationFlag = $this->input->post('abroadSpecializationFlag');
            	$abroadSpecialization = $this->input->post('abroadSpecialization');

				switch($sectionType) {

					case 'workExperienceSection':						
				      // $_POST['currentJob'] = $currentJob;
						$isNewWorkExpFlag = $this->input->post('isNewWorkExp');

						if(empty($isNewWorkExpFlag) ){
							unset($_POST['isNewWorkExp']);
						}

					    $this->updateWorkExSection($userId,$userPrivacyData);

			        break;

	        		case 'educationalBackgroundSection':

		        		$newEducationLevels = $this->input->post('EducationBackground');

			    		$newFields = array('10','12','UG','PG','PHD');

			       		$_POST['EducationBackground'] = array('bachelors','masters','phd','xth','xiith');
			       		
			       		$oldFields = array_diff($newFields,$newEducationLevels);		
			       	
			       		if(empty($newEducationLevels)){
			       			$oldFields = $newFields;
			       		}

			       		$this->setEducationFieldPublic($oldFields,$userId);

		           	break;

   					case 'educationalPreferenceSection':

   						if($isStudyAbroad != 'yes') { 
   							$this->setEducationalPreferenceDataForUpdate();						
						}
						
					break;

				}
				$_POST['tracking_keyid'] = 696;
				if($studyabroad == 'yes' && empty($abroadSpecialization)){
					$abroadSpecialization = $abroadSpecializationFlag;
					$_POST['abroadSpecialization'] = $abroadSpecialization;
				}
				$response = Modules::run('registration/Registration/updateUser');
				$data = json_decode($response);

				if($data->status == 'FAIL') {
					echo 'error';
				} else {

					$viewFile = $this->sectionReadableViews[$sectionType];				

					/*$finalData['privacyDetails']  = $privacyDetails;
					$finalData['userData'] = $userData;*/

					$finalData = array();

					switch($sectionType){
						case 'personalInformationSection':
							$finalData = $this->getFormattedPersonalInformationSection();
						break;

						case 'educationalPreferenceSection':
							if($isStudyAbroad == 'yes'){
								$finalData = $this->getFormattedEducationalPreferenceAbroad();
							}else{
								$finalData = $this->getFormattedEducationalPreferenceNational();
							}
						break;

						case 'workExperienceSection':
							$globalWorkExData = $this->input->post('globalWorkExData');

							$globalWorkExData = unserialize($globalWorkExData);

							if($isNewWorkExpFlag == 1){
								$finalData = $this->getFormattedWorkExperienceData($globalWorkExData);
							} else{
								$finalData = $this->getFormattedWorkExperienceData();
							}

							$personalInfo = $this->userprofilelib->getUserProfileDetails($userId, 'myProfile', 'personalInfo');
				 			$finalData['personalInfo']['Experience'] = $personalInfo['personalInfo']['Experience'];
							unset($personalInfo);

						break;

						case 'educationalBackgroundSection':
							$finalData = $this->getEducationalBackgroundDataThroughPost();
						break;

					}
					
					$finalData['privacyDetails']  = $this->userprofilelib->getUserPrivacyDetails($userId);
					$carryForwardPriv = serialize($finalData['privacyDetails']);
					
					$returnObj['privacy'] = $carryForwardPriv;
					if(!empty($finalData['currentHeader']) && $finalData['currentHeader'] !='')
						$returnObj['currentHeader'] = $finalData['currentHeader'];

					$returnObj['sectionView'] = $this->load->view($viewFile, $finalData,true);
					
					echo json_encode($returnObj);

				}

			}

		} else {
			echo 'error';
		}
	}
	
	//Function to get work experience section data
	function getFormattedWorkExperienceData($globalWorkExData = array()){
		$workExMaxCount =10;
		$userData = array();

		if(!empty($globalWorkExData)){
			$itrCounter=1;
			foreach ($globalWorkExData as $prevWorkExData) {
				$workExKey ='workExp'.$itrCounter;

				$userData[$workExKey]['Employer'] = $prevWorkExData['Employer'];
				$userData[$workExKey]['Designation'] = $prevWorkExData['Designation'];
				$userData[$workExKey]['Department'] = $prevWorkExData['Department'];
				$userData[$workExKey]['CurrentJob'] = $prevWorkExData['CurrentJob'];

				$itrCounter++;
			}

			$itrCounter--;
		} else{
			$itrCounter =0;
		}

		$checkCount = $itrCounter;

		$employerArray = $this->input->post('employer');
		$designationArray = $this->input->post('designation');
		$departmentArray = $this->input->post('department');
		$currentJobArray = $this->input->post('currentJob');

		for ($itr=1; $itr <= ($workExMaxCount-$checkCount); $itr++) { 

			if($itrCounter ==0){
				$workExKey ='workExp'.$itr;
			} else{
				$itrCounter++;
				$workExKey ='workExp'.$itrCounter;
				
			}

			$userData[$workExKey]['Employer'] = $employerArray[$itr-1];
			$userData[$workExKey]['Designation'] = $designationArray[$itr-1];
			$userData[$workExKey]['Department'] = $departmentArray[$itr-1];
			$userData[$workExKey]['CurrentJob'] = $currentJobArray[$itr-1];

			if($currentJobArray[$itr-1] == 'YES'){
					$userData['currentHeader']['Designation'] = $designationArray[$itr-1];
					$userData['currentHeader']['Employer'] = $employerArray[$itr-1];
				}

		}

		return $userData;
	}
	
function getEducationalBackgroundDataThroughPost(){
		$returnArray = array();
		
		$levelMapping = array(
				'tenth'=>array('tenthBoard'=> 'Board', 'xthSchool'=> 'InstituteName', 'xthCompletionYear'=>'CourseCompletionDate', 'tenthmarks'=>'Marks'),
				'twelfth'=>array('xiithSchool'=>'InstituteName', 'xiiMarks'=>'Marks', 'xiiSpecialization'=>'Specialization', 'xiiYear'=>'CourseCompletionDate', 'xiiBoard'=>'Board'),
				'UG' => array('bachelorsDegree'=>'Name', 'bachelorsCollege'=>'InstituteName', 'bachelorsUniv'=>'Board', 'bachelorsMarks'=>'Marks', 'bachelorsStream'=>'Subjects', 'graduationCompletionYear'=>'CourseCompletionDate'), 
		   		'PG' => array('mastersDegree'=>'Name', 'mastersUniv'=>'Board', 'mastersCollege'=>'InstituteName', 'mastersMarks'=>'Marks', 'mastersCompletionYear'=>'CourseCompletionDate', 'mastersStream'=>'Subjects'),
		   		'PHD' => array('phdDegree'=>'Name', 'phdUniv'=>'Board', 'phdCollege'=>'InstituteName', 'phdMarks'=>'Marks', 'phdStream'=>'Subjects', 'phdCompletionYear'=>'CourseCompletionDate')
			);

		 $marks = array(
                            'CBSE' => array('4 - 4.9'=>4, '5 - 5.9'=>5, '6 - 6.9'=>6, '7 - 7.9'=>7, '8 - 8.9'=>8, '9 - 10.0'=>9),
                            'ICSE' => array('50'=>'50','60'=>'60','70' => '70','80'=>'80','90'=>'90','100' => '100'),
                            'IGCSE' => array('A*'=>1,'A'=>2,'B'=>3, 'C'=>4, 'D'=>5, 'E'=>6,'F'=>7, 'G'=>8),
                            'IBMYP'=> array('28'=>'28','29'=>'29','30'=>'30','31'=>'31','32'=>'32','33'=>'33','34'=>'34','35'=>'35','36'=>'36','37'=>'37','38'=>'38','39'=>'39','40'=>'40','41'=>'41','42'=>'42','43'=>'43','44'=>'44','45'=>'45','46'=>'46','47'=>'47','48'=>'48','49'=>'49','50'=>'50','51'=>'51','52'=>'52','53'=>'53','54'=>'54','55'=>'55','56'=>'56'),
     					    'NIOS' => array('50'=>'50','60'=>'60','70' => '70','80'=>'80','90'=>'90','100' => '100')
                    );

		$headerContentHolder = '';
		foreach($levelMapping as $key=>$fields){
			foreach ($fields as $k => $field) {
				if(isset($_POST[$k])){
					$returnArray[$key][$field] = $this->input->post($k, true);
				} 
			}
		}

		$headerContentHolderMapping = array(
				'PHD'=> array('phdDegree', 'phdCollege', 0),
				'PG'=> array('mastersDegree', 'mastersCollege', 0),
				'UG'=> array('bachelorsDegree', 'bachelorsCollege', 0),
				'twelfth'=> array('12th', 'xiithSchool', 1),
				'tenth'=> array('10th', 'xthSchool', 1),
			);
		
		foreach ($headerContentHolderMapping as $key => $value) {
			if(!empty($_POST[$value[1]])){
				if($value[2]){
					$headerContentHolder = $value[0];
				}else{
					$headerContentHolder = $this->input->post($value[0], true);
				}
				$headerContentHolder .= ' from '.$this->input->post($value[1], true);
				$returnArray['currentHeader'] = $headerContentHolder;
				break;
			}
		}

		foreach($returnArray as $key=>$value){
			
			if($key == 'tenth' && !empty($returnArray[$key]['Marks'])){
				$returnArray[$key]['Marks'] = $marks[$returnArray[$key]['Board']][$returnArray[$key]['Marks']];
			}
		}
		
		return $returnArray;
	}

	//Function to update work ex section in user profile
	function updateWorkExSection($userId,$userPrivacyData){
		$workExGlobalArray = $this->input->post('globalArray');

        $workExGlobalArray = explode(',', $workExGlobalArray);

        $this->carryForwardWorkExPrivacy($workExGlobalArray,$userId,$userPrivacyData);
  
        foreach ($workExGlobalArray as $count) {
        	$employerKey = "employer_".$count;
       		$designationKey = "designation_".$count;
       		$departmentKey = "department_".$count;
       	
       		if(empty($_POST[$employerKey]) ){
       			continue;
       		}

       		$employer[]	= $this->input->post($employerKey);
       		unset($_POST[$employerKey]);

       		$designation[]	= $this->input->post($designationKey);
       		unset($_POST[$designationKey]);

       		$department[]	= $this->input->post($departmentKey);
       		unset($_POST[$departmentKey]);
        }
       
       $_POST['employer'] = $employer;
       $_POST['designation'] = $designation;
       $_POST['department'] = $department;

	}

	// Function to set educational preference data for updation in user data
	function setEducationalPreferenceDataForUpdate() {
		$postExams = $this->input->post("exams");
		if(empty($postExams)) {
			$_POST['exams'] = array();
		} else {
			$examIds = array();
			foreach($postExams as $exam) {
				if((!empty($exam)) && (isset($exam))) {
					$examIds[] = $exam;									
				} 
			}
			if(empty($examIds)) {
				$_POST['exams'] = array();
			} else {
				$_POST['exams'] = $examIds;
			}
		}
		$examTobeDeleted = $this->input->post("examTobeDeleted");
		$_POST['examTobeDeleted'] = json_encode(explode(",",$examTobeDeleted));	

	}

	function getFormattedEducationalPreferenceNational(){
		$prefData = array(); 
		$prefData['userPreference']['PrefId'] = $this->input->post('PrefId', true);
		$prefData['userPreference']['DesiredCourse'] = $this->input->post('desiredCourse', true);
		$exams = $this->input->post('exams', true);
		foreach ($exams as $key => $value) {
			if($value == 'Other'){
				$prefData['competitiveExam']['Name'][] = $this->input->post('otherExamName');
			}else{
				$prefData['competitiveExam']['Name'][] = $value;
			}
			$prefData['competitiveExam']['MarksType'][] = $this->input->post($value.'_scoreType', true);
			$prefData['competitiveExam']['Marks'][] = $this->input->post($value.'_score', true);
		}
		
		return $this->loadUserEducationalPreferenceData($prefData);
	}

	// Function to get formatted data for abroad educational preference section after saving user data
	function getFormattedEducationalPreferenceAbroad () {

		$finalData = array();

		$finalData['userPreference']['ExtraFlag'] = "studyabroad";
		
		$desiredGraduationLevel = $this->input->post('desiredGraduationLevel');
		$regFormId = $this->input->post('regFormId');
		$fieldOfInterest = $this->input->post('fieldOfInterest_'.$regFormId.'_input');
		$abroadSpecialization = $this->input->post('abroadSpecialization_'.$regFormId.'_input');
		$budget = $this->input->post('budget_'.$regFormId.'_input');
		$destinationCountryNames = $this->input->post('destinationCountryNames');
		$whenPlanToGo = $this->input->post('whenPlanToGo');
		if($whenPlanToGo == 'thisYear') {
			$whenPlanToGo = date("Y");
		} else if($whenPlanToGo == 'in1Year') {
			$whenPlanToGo = date("Y")+1;
		} else if($whenPlanToGo == 'later') {
			$whenPlanToGo = date("Y")+2;
		} 
		$sourceOfFunding = $this->input->post('sourceOfFunding');
		if($sourceOfFunding == 'own') {
			$sourceOfFunding = 'himself';
		} else if($sourceOfFunding == 'bank') {
			$sourceOfFunding = 'bank';
		} else if($sourceOfFunding == 'other') {
			$sourceOfFunding = 'other source';
		} 

		$extracurricular = $this->input->post('extracurricular');
		$specialConsiderations = $this->input->post('specialConsiderations');
		$preferences = $this->input->post('preferences');
		$passport = $this->input->post('passport');
		$exams = $this->input->post('exams');
		$examTaken = $this->input->post('examTaken');

		if(!empty($desiredGraduationLevel)) {
			$finalData['desiredCourseDetails']['courseName'] = $desiredGraduationLevel;
			if(!empty($fieldOfInterest)) {
				$finalData['desiredCourseDetails']['categoryName'] = $fieldOfInterest;
			}
		} else {
			$finalData['desiredCourseDetails']['courseName'] = $fieldOfInterest;
		}

		if(!empty($abroadSpecialization)) {
			$finalData['desiredCourseDetails']['subCatgoryName'] = $abroadSpecialization;
		}
		$finalData['countryNames'] = explode(", ",$destinationCountryNames);
		$finalData['whenPlanToGo'] = $whenPlanToGo;
		$finalData['budget'] = $budget;
		$finalData['sourceOfFunding'] = $sourceOfFunding;
		$finalData['additionalInfo']['Extracurricular'] = $extracurricular;
		$finalData['additionalInfo']['SpecialConsiderations'] = $specialConsiderations;
		$finalData['additionalInfo']['Preferences'] = $preferences;
		$finalData['personalInfo']['Passport'] = $passport;
		$finalData['examTaken'] = $examTaken;

		if(!empty($exams)) {
			sort($exams);

			$i=0; $competitiveExamScores = array();
			foreach($exams as $examName) {
				$finalData['competitiveExam']['Name'][$i] = $examName;

				$examScore = '';
				$examScore = $this->input->post($examName.'_score');
				$competitiveExamScores[$examName] = $examScore;

				if($examName == 'IELTS') {
					$finalData['competitiveExam']['Marks'][$i] = number_format((float)$examScore, 1, '.','');
				} else {
					$finalData['competitiveExam']['Marks'][$i] = $examScore;
				}

				$i++;
			}
		}

		return $finalData;
	}

	/**
	* Function to get data for showing updated view after saving
	*/

	function getFormattedPersonalInformationSection(){

		$finalData = array();
		$postDataKeys = array('Bio' => 'bio','Mobile' => 'mobile','Email' => 'email','StudentEmail' => 'studentEmail', 'City' => 'residenceCityLocality', 'Locality' => 'residenceLocality');
		foreach ($postDataKeys as $key => $value) {
			if($value == 'bio' || $value == 'studentEmail'){
				$finalData['additionalInfo'][$key] = $this->input->post($value);
			}else{
				$finalData['personalInfo'][$key] = $this->input->post($value);
			}
		}

		$isdCode = split('-',($this->input->post('isdCode')));
		$finalData['personalInfo']['ISDCode'] = $isdCode[0];
		$finalData['personalInfo']['Country'] = $isdCode[1];
		$countryData = $this->getLocationData($finalData['personalInfo']);
		$finalData = array_merge($countryData,$finalData);
		if(isset($_POST['dob']) == true  && $_POST['dob'])
			$finalData['personalInfo']['DateOfBirth'] = new DateTime($_POST['dob']);

		return $finalData;
		

	}

	function getLocationFields(){

		if(!verifyCSRF()) { return false; }

		$regFormId = $this->input->post('regFormId', true);
		$country = $this->input->post('country', true);
		if($country != INDIA_ISD_CODE){
			echo '';
			return;
		}

		$data = array();
		$returnArray = array();
		$registrationForm = \registration\builders\RegistrationBuilder::getRegistrationForm('LDB',array('courseGroup' => 'localUG'));
		$data['fields']['residenceCityLocality'] = $registrationForm->getField('residenceCityLocality');
		$data['regFormId'] = $regFormId;

		$data['type'] = 'city';
		$returnArray['city'] = $this->load->view('muserProfile5/userLocationFields', $data, true); 

		$data['type'] = 'locality';
		$returnArray['locality'] = $this->load->view('muserProfile5/userLocationFields', $data, true);

		echo json_encode($returnArray);
	}

/**
 * function to get city, locality and country data
*/
function getLocationData($personalInfo){
		$finalData['personalInfo'] = $personalInfo;

		if(isset($finalData['personalInfo']['Country'])){
			$finalData['userCountry'] = ($finalData['personalInfo']['Country'] !=2)? $this->userprofilelib->getCountryName($finalData['personalInfo']['Country']) : 'India';
			if($finalData['personalInfo']['Country'] ==2){
				$finalData['userlocationData'] = $this->userprofilelib->getUserLocationDetails($finalData['personalInfo']['City'], $finalData['personalInfo']['Locality']);
			}
		}
		return $finalData;
	}

	/**
	* Function to get user data while editing for personal information
	*/

	private function getPersonalInfoSectionDetails($userId){

		$userData = array();
		if(empty($userId)){
			return $userData;
		}

		//create array of personalInfo , socialInfo , additionalInfo n then loop through

		$sectionName = array('personalInfo', 'socialInfo', 'additionalInfo');

		foreach ($sectionName as $value) {
			${$value} = $this->userprofilelib->getUserProfileDetails($userId, 'myProfile', $value);
			$userData[$value] = ${$value}[$value];
		}

		$userData['userLevelDetails'] = $this->userprofilelib->getUserLevelData($userId);
		$registrationForm = \registration\builders\RegistrationBuilder::getRegistrationForm('LDB',array('courseGroup' => 'localUG'));

		$userData['fields']['isdCode'] = $registrationForm->getField('isdCode');
		$userData['fields']['residenceCityLocality'] = $registrationForm->getField('residenceCityLocality');
		$userData = array_merge($this->getLocationData($userData['personalInfo']),$userData);

		return $userData;
	}

	/* Function to send OTP */

	public function userVerificationLayer(){
		$this->load->helper('security');
        $postData = xss_clean($_POST);

	 	$Data['regFormId']=$postData['regData'];
	 	$Data['mobile']=$postData['mobile'];
		$Data['showVerificationLayer']=$postData['showVerificationLayer'];
	 	$Data['changeMobileLink'] = $postData['changeMobileLink'];
		$this->load->view('muserProfile5/OTPVerification',$Data);
	}

    public function updateTotalWorkEx(){
		if(!verifyCSRF()) { return false; }

		$isStudyAbroadFlag = $this->input->post('isStudyAbroadFlag');
		$abroadSpecializationFlag = $this->input->post('abroadSpecializationFlag');
		if($isStudyAbroadFlag == 'yes') {
			$_POST['abroadSpecialization'] = $abroadSpecializationFlag;
			$_POST['isStudyAbroadFlag'] = $isStudyAbroadFlag;
		}
    	$workExperience = $this->input->post('workExperience');
    	if(isset($workExperience)) {
    		$_POST['tracking_keyid'] = 696;
        	$response = Modules::run('registration/Registration/updateUser');
			$data = json_decode($response);

			if($data->status == 'FAIL') {
				echo 'error';
			} else {
				echo 'success';
			}
		}
    }

    function changePassword() {
			
		$userData = $this->getLoggedInUserData();
		$userId = $userData['userId'];
		$appId = 12;
		$this->load->library('Register_client');
		$registerClient = new Register_client();
		$currentPassword = $this->input->post('currentPassword');
		$newPassword = $this->input->post('newPassword');
		
		$status = $registerClient->changePassword($appId,$userId,sha256($currentPassword),sha256($newPassword),$newPassword);
		if($status == 1) {
			$values = explode("|",$_COOKIE["user"]);
			$email = $values[0];
			$value = $email.'|'.sha256($newPassword) .'|' .$values[2];
			setcookie('user','',time() - 2592000 ,'/',COOKIEDOMAIN);
			setcookie('user',$value,time() + 2592000 ,'/',COOKIEDOMAIN);
			$_COOKIE["user"] = $value;
		}		
		echo $status;
	}


	function changeCommPref() {
		ini_set("memory_limit", "300M");
		error_log("User_Profile_Desktop before getting user data : ".print_r((memory_get_peak_usage()/(1024*1024)),true));
		$userData = $this->getLoggedInUserData();
		error_log("User_Profile_Desktop after getting user data : ".print_r((memory_get_peak_usage()/(1024*1024)),true));
		$userId = $userData['userId'];
		$appId = 12;
		$this->load->library('Register_client');
		$registerClient = new Register_client();
		$viaemail = $this->input->post('viaemail');
		$status = $registerClient->updateUserAttribute($appId,$userId,'viaemail',$viaemail);
		error_log("User_Profile_Mobile after updating user attribute : ".print_r((memory_get_peak_usage()/(1024*1024)),true));

		$user_response_lib = $this->load->library('response/userResponseIndexingLib');	       					
		$extraData = "{'personalInfo:true'}";
		$user_response_lib->insertInIndexingQueue($userId, $extraData);
		error_log("User_Profile_Mobile after adding to indexing queue : ".print_r((memory_get_peak_usage()/(1024*1024)),true));
		echo 1;

	}
	//function to set fields private	
    public function setUserFieldPrivate(){

			if(!verifyCSRF()) { return false; }

			$userId = $this->input->post('userId');
			$fieldIds = $this->input->post('fields');
			$privacyData = $this->input->post('privacyData');

			$fieldIds  = unserialize($fieldIds);
			$privacyData  = unserialize($privacyData);
			
			$this->load->library('userProfile/UserProfileLib');
			if(empty($userId) || empty($fieldIds)){
				return;
			}
			
			$this->userprofilelib->setUserFieldPrivate($userId,$fieldIds);

			foreach ($privacyData as $key => $value) {
				foreach ($fieldIds as $field) {
					if($key == $field){
						$privacyData[$key] = 'private';
					}
				}
				
			}			
			
			$privacyData = serialize($privacyData);
			echo $privacyData;
		}

	//function to set fields public	
	public function setUserFieldPublic(){

		if(!verifyCSRF()) { return false; }

		$userId = $this->input->post('userId');
		$fieldIds = $this->input->post('fields');
		$privacyData = $this->input->post('privacyData');

		$fieldIds  = unserialize($fieldIds);
		$privacyData  = unserialize($privacyData);			
			
		$this->load->library('userProfile/UserProfileLib');
		if(empty($userId) || empty($fieldIds)){
			return;
		}
		
		$this->userprofilelib->setUserFieldPublic($userId,$fieldIds);

		foreach ($privacyData as $key => $value) {
			if($key == $fieldIds){
				$privacyData[$key] = 'public';
			}
		}	
		
		$privacyData = serialize($privacyData);
		echo $privacyData;
	}

		//function to carry forward privacy of work ex, it takes care of order of work ex
		public function carryForwardWorkExPrivacy($newWorkExArray = array(),$userId,$userPrivacyData){

			$newPrivacy = array();

			foreach ($newWorkExArray as $newLevel => $oldLevel) {
				
				if($oldLevel>10){
					continue;;
				}

				$fieldId = 'EmployerworkExp'.$oldLevel;
				
				if($this->checkIfPrivate($fieldId,$userPrivacyData)){
					$newPrivacy[] = $newLevel;
				}
			}

			$privacyUserData = array();

			foreach ($newPrivacy as $key => $value) {
				$privacyUserData[$value] = 1; 		//key is being treat as level in model
			}
			

			$this->markPreviousPrivacyHistory($userId);

			if(!empty($privacyUserData) ){
				$this->createNewPrivacyWorkEx($privacyUserData,$userId);
			}

		}

		public function checkIfPrivate($fieldId,$userPrivacyData){

			if($userPrivacyData[$fieldId] == 'private'){
				return true;	
			} else{
				return false;
			}
			
		}

		public function markPreviousPrivacyHistory($userId){
			$userprofilemodeldesktop = $this->load->model('userProfile/userprofilemodeldesktop');

			$userprofilemodeldesktop->markPreviousPrivacyHistory($userId);

		}

		public function createNewPrivacyWorkEx($newPrivacy,$userId){
			$userprofilemodeldesktop = $this->load->model('userProfile/userprofilemodeldesktop');

			$userprofilemodeldesktop->createPrivacyWorkEx($newPrivacy,$userId);
		}

		public function setEducationFieldPublic($levels = array(),$userId){

			if(empty($levels)){
				return;
			}

			$this->load->library('userProfile/UserProfileLib');

			$privacyFieldsAll = array('InstituteName','CourseCompletionDate','Board','Subjects','Marks','Name','CourseSpecialization');

			$privacyFieldsXII = array('InstituteName','CourseCompletionDate','Board','Subjects','Marks','Specialization');					
			$privacyFieldsX = array('InstituteName','CourseCompletionDate','Board','Subjects','Marks');	

			$privacyFields = $privacyFieldsAll;

			foreach ($levels as $level) {
				$fieldIds=array();

				if($level == '12'){
					$privacyFields = $privacyFieldsXII;
				}

				if($level == '10'){
					$privacyFields = $privacyFieldsX;
				}

				foreach ($privacyFields as $field) {
					$fieldIds[] = $field.$level;
				}

				if($level == '12'){
					$privacyFields = $privacyFieldsAll;
				}

				$this->userprofilelib->setUserFieldPublic($userId,$fieldIds);

				 unset($fieldIds);
			}

		}

		function addUserExams () {

            $desiredCourse = $this->input->post('desiredCourse');
            $regFormId = $this->input->post('regFormId');
            $preSelectedCompetitiveExam = $this->input->post('preSelectedCompetitiveExam');

			if(!empty($desiredCourse)) {

				$userData = $this->getLoggedInUserData();	

				$userId = $userData['userId'];

				if(!empty($userId)) {
					$registrationForm = \registration\builders\RegistrationBuilder::getRegistrationForm('LDB',array('courseGroup' => 'localUG'));
					$exams = $registrationForm->getField('exams');
					$data['listExams'] = $exams->getValues(array('desiredCourse'=>$desiredCourse));
					unset($data['listExams']['others']['NOEXAM']);
					$data['regFormId'] = $regFormId;
					$data['preSelectedCompetitiveExam'] = $preSelectedCompetitiveExam;
					
					$this->load->view('nationalExamBlocks', $data); 
				}
			}
		}

		/*function to get user activity stats
		*	Params: UserId- User Id whose activity stats are required
		*	loggedInUserId	- user who is logged in current; could be same with userid
		*/

		function getUserActivityStats($userId,$start = 0, $count = 10,$flagForCTA = false){
			
	        //$userId = '5135105';
			
			$this->load->library('userProfile/UserProfileLib');
			$this->userprofilelib = new UserProfileLib;

			$activityStats = $this->userprofilelib->getUserProfielStats($userId,$start,$count);

			$activityStats['stats'] = $this->userprofilelib->formatUserStatsWithCTA($activityStats['stats'],$flagForCTA);

			return $activityStats;
		}

		function followUnfollowAction(){


			$data['userId'] = $this->getUserIdForLoggedInUser();
			$this->load->library('userProfile/UserProfileLib');
			$this->userprofilelib = new UserProfileLib;
			$data['entityId'] = $this->input->post('entityId');
			$data['entityType'] = $this->input->post('entityType');
			$data['action'] = $this->input->post('action');
			$followEntityData = $this->userprofilelib->followUnfollowAction($data);

			echo json_encode($followEntityData);
		}

		function deleteCommentAnswerAction(){

			$data['userId'] = $this->getUserIdForLoggedInUser();
			$this->load->library('userProfile/UserProfileLib');
			$this->userprofilelib = new UserProfileLib;
			$data['entityId'] = $this->input->post('entityId');
			$data['entityType'] = $this->input->post('entityType');
			$data['status'] = $this->input->post('status');
			$deleteEntityData = $this->userprofilelib->deleteCommentAnswerAction($data);

			echo $deleteEntityData;
		}

		private function getUserIdForLoggedInUser(){

			$userData = $this->getLoggedInUserData();
			$userId = $userData['userId'];

			if(!($userId > 0)){
				return false;
			}else{
				return $userId;
			}
		}

		function showDetailedTuples(){

			$userId = $this->input->post('userId');
			if(!($userId > 0)){
				header('Location: '.SHIKSHA_HOME);
			}

			$typeOfStat = $this->input->post('typeOfStat');
			if(empty($typeOfStat)){
				return false;
			}

			$data = $this->getDetailedTuplesData($userId, $typeOfStat, 0, 20);
			$publicProfile = $this->input->post('publicProfile');

			$data['publicProfile'] = $publicProfile;
			$data['iter'] = 0;

			$loggedInUserId = $this->getLoggedInUserData();
			$data['isUserLoggedIn'] = true;
			if(!($loggedInUserId['userId']) > 0){
				$data['publicProfile'] = true;
				$data['isUserLoggedIn'] = false;
			}
			$data['loggedInUserId'] = $loggedInUserId;

			if($data['entityType'] == 'User')
				$type = 'userprofile';
			else
				$type = strtolower($data['entityType']);
			$data['activities'] = $this->getURLForIndividualTupples($data['activities'], $type);
			
			$viewFile =$this->load->view('activityDetailedTuples',$data,true);

			echo $viewFile;
		}

		function getUserRecentActivities(){

			if(!verifyCSRF()) { return false; }

			$start = isset($_POST['start'])?$this->input->post('start'):0;
			$count = isset($_POST['count'])?$this->input->post('count'):10;
			$customParams = $this->input->post('customParams');
			$ajaxCallCounterRecent = $customParams[1];
			$userId = $customParams[2];
			$publicProfile = $customParams[3];
			$finalData = array();
			$finalData['userId']= $userId;
			$userActivityStats = $this->getUserActivityStats($userId,$start,$count);
			$formattedUserActivityStats = $this->getFormattedUserActivityStats($userActivityStats);

			$formattedUserActivityStats['activities'] = $this->getURLForIndividualTupples($formattedUserActivityStats['activities']);
			
			$finalData = array_merge($formattedUserActivityStats,$finalData);

			unset($userActivityStats);
			unset($formattedUserActivityStats);
			
			$finalData['ajaxCallCounterRecent'] = $ajaxCallCounterRecent;
			$finalData['publicProfile'] = $publicProfile;

			$view = $this->load->view('userProfileRecentActivity',$finalData, true);
			$view = trim($view);
			if(!empty($view)){
				echo json_encode(array('html'=>$view, 'ajaxCallCounterRecent'=>$finalData['ajaxCallCounterRecent']));
			} 
			else {
				echo 'NoData';
			}
			
		}

		function getUserDetailedActivities(){

			$start = isset($_POST['start'])?$this->input->post('start'):0;
			$count = isset($_POST['count'])?$this->input->post('count'):10;
			$customParams = $this->input->post('customParams');
			$ajaxCallCounterDetailed = $customParams[1];
			$typeOfStat = $customParams[2];
			$iter = $customParams[3];
			$userId = $customParams[4];
			$publicProfile = $customParams[5];
			if(empty($typeOfStat)){
				return false;
			}
			$data = $this->getDetailedTuplesData($userId, $typeOfStat, $start, $count);
			$data['ajaxCallCounterDetailed'] = $ajaxCallCounterDetailed;
			$data['iter'] = $iter;

			$loggedInUserId = $this->getLoggedInUserData();
			$data['isUserLoggedIn'] = true;
			if(!($loggedInUserId['userId']) > 0){
				$data['publicProfile'] = true;
				$data['isUserLoggedIn'] = false;
			}
			$data['loggedInUserId'] = $loggedInUserId;
			$data['publicProfile'] = $publicProfile;

			if($data['entityType'] == 'User')
				$type = 'userprofile';
			else
				$type = strtolower($data['entityType']);
			$data['activities'] = $this->getURLForIndividualTupples($data['activities'], $type);

			$view = $this->load->view('userProfileDetailedActivity',$data,true);
			$view = trim($view);
			if(!empty($view)){
				echo json_encode(array('html'=>$view, 'ajaxCallCounterDetailed'=>$data['ajaxCallCounterDetailed'], 'activityCount'=>count($data['activities'])));
			} 
			else {
				echo 'NoData';
			}
			
		}

		function getDetailedTuplesData($userId, $typeOfStat, $start=0, $count=10){

			$this->load->helper('activity');
			$entityType = getEntityTypeMapping($typeOfStat);

			$entityCategory = getEntityCategoryMapping($typeOfStat);
			if(empty($entityCategory)){
				$entityCategory=$typeOfStat;
			}

			$apiForANA = getAPIForANA($entityCategory);
			$loggedInUserId = $this->getUserIdForLoggedInUser();
			
			$this->load->library('userProfile/UserProfileLib');
			$this->userprofilelib = new UserProfileLib;
			$activities = $this->userprofilelib->showDetailedTuples($apiForANA, $userId,$entityType,$entityCategory, $start, $count,$loggedInUserId);
			$viewFile = '';
			$data['activities'] = $activities;
			$data['typeOfStat'] = $typeOfStat;
			$data['entityType'] = $entityType;
			$data['detailTuple'] = $this->entityDetailView[$entityType];

			return $data;

		}

		function getURLForIndividualTupples($activities=array(), $entityType=null){

			$this->load->library('userProfile/UserProfileLib');
			$this->userprofilelib = new UserProfileLib;

			foreach ($activities as $key => $activity) {
				if(!$entityType){
					$type = $this->activityTypeMapping[$activity['type']];
					if($type == 'question' || $type == 'discussion') {
						$id = $activity[$type]['id'];
						$title = $activity[$type]['title'];
					} else if($type == 'tag') {
						$id = $activity[$type]['tagId'];
						$title = $activity[$type]['tagName'];
					} else if($type == 'userprofile') {
						$id = $activity['user']['userId'];
						$title = $activity['user']['userName'];
					}
				} else {
					$type = $entityType;
					if($type == 'question' || $type == 'discussion') {
						$id = $activity['id'];
						$title = $activity['title'];
					} else if($type == 'tag') {
						$id = $activity['tagId'];
						$title = $activity['tagName'];
					} else if($type == 'userprofile') {
						$id = $activity['userId'];
						$title = $activity['userName'];
					}
				}
				
				$activities[$key]['url'] = $this->userprofilelib->getURLForTupple($id,$type,$title);
			}
			return $activities;
		}

		public function fetchDataForTagsList($type='EduPrefTags'){

			$userData = $this->getLoggedInUserData();
			$userId = $userData['userId'];

			$APIClient = $this->load->library("APIClient");        
	        $APIClient->setUserId($userId);
	        $APIClient->setVisitorId(getVisitorId());
	        $APIClient->setRequestType("get");
	        $url = "";
	        $jsonDecodedData = array();
	        $finalData = array();
	        $combinedFinalResult = array();

	        if($type == "Expertise" || $type=='EduPrefTags'){

	        	// Fetch the Stream Tags
	        	$jsonDecodedData =$APIClient->getAPIData("UserProfileBuilder/userProfileBuilderData/consumer/false/true/true");
	        	$result = array();
		        if(isset($jsonDecodedData['courses'])){
		        	$result = $jsonDecodedData['courses'];
		        	foreach ($result as $key => $value) {
		        		$result[$key]['obj'] = $value['label'];
		        		unset($result[$key]['label']);
		        	}		        	
		        }

		        // Setting the result
		        if($type == "Expertise"){
		        	$finalData['stream_expertise']['Field/Stream Of Interest'] = $result;
		        }else {
		        	$finalData['stream_edupref']['Field/Stream Of Interest'] = $result;
		        }
		        
		        // Fetch the Country Tags Popular
		        $jsonDecodedData =$APIClient->getAPIData("UserProfileBuilder/userProfileBuilderData/consumer/true/false/true");
	        	$result = array();
		        if(isset($jsonDecodedData['popularCountries'])){
		        	$result = $jsonDecodedData['popularCountries'];
		        	foreach ($result as $key => $value) {
		        		$result[$key]['obj'] = $value['label'];
		        		unset($result[$key]['label']);
		        	}
		        	
		        }
		        // Set the Result
		        if($type == "Expertise"){
		        	$finalData['countries_expertise']['Popular Countries'] = $result;
		        }else {
		        	$finalData['countries_edupref']['Popular Countries'] = $result;
		        }
		        
		        // Fetch the Country Tags Other than Popular
		        if(isset($jsonDecodedData['otherCountries'])){
		        	$result = $jsonDecodedData['otherCountries'];
		        	foreach ($result as $key => $value) {
		        		$result[$key]['obj'] = $value['label'];
		        		unset($result[$key]['label']);
		        	}
		        }
		        // Set the Resilt
		        if($type == "Expertise"){
		        	$finalData['countries_expertise']['Other Countries'] = $result;
		        }else {
		        	$finalData['countries_edupref']['Other Countries'] = $result;
		        }
		        
	        }

	        if($type == "EduPrefTags"){

	        	// Fetch the Course Level
	        	$result = array();
	        	$jsonDecodedData =$APIClient->getAPIData("UserProfileBuilder/userProfileBuilderData/consumer/true/true/false");

		        if(isset($jsonDecodedData['courseLevel'])){
		        	$result = $jsonDecodedData['courseLevel'];
		        	foreach ($result as $key => $value) {
		        		$result[$key]['obj'] = $value['label'];
		        		unset($result[$key]['label']);
		        	}		        	
		        }
		        $finalData['courselevel_edupref']['Course Level'] = $result;


		        // fetch the specializations
		        $jsonDecodedData =$APIClient->getAPIData("UserProfileBuilder/getSpecializationList/");
		        if(isset($jsonDecodedData['Streams'])){
		        	$result = $jsonDecodedData['Streams'];
		        	foreach ($result as $key => $value) {
		        		foreach ($value['specializations'] as $key1 => $value1) {
		        			$value['specializations'][$key1]['obj'] = $value1['label'];
		        			unset($value['specializations'][$key1]['label']);
		        		}
						$finalData['specializations_eduperf'][$value['label']] = $value['specializations'];		        		
		        	}
		        }

		        $jsonDecodedData =$APIClient->getAPIData("UserProfileBuilder/getCoursesList/");
		        
		        if(isset($jsonDecodedData['courses'])){
		        	$result = $jsonDecodedData['courses'];

		        	foreach ($result as $key => $value) {
		        		$result[$key]['obj'] = $value['label'];
		        		unset($result[$key]['label']);
		        	}
		        }
		        $finalData['course_edupref']['Degree/Diploma'] = $result;


		         // Fetch the Country Tags Popular
		        $jsonDecodedData =$APIClient->getAPIData("UserProfileBuilder/getCities/");
		        
	        	$result = array();
		        if(isset($jsonDecodedData['popularCities'])){
		        	$result = $jsonDecodedData['popularCities'];
		        	foreach ($result as $key => $value) {
		        		$result[$key]['obj'] = $value['label'];
		        		unset($result[$key]['label']);
		        	}
		        	
		        }
		        // Set the Result

				$finalData['cities_edupref']['Popular Cities'] = $result;
		       
		        // Fetch the Country Tags Other than Popular
		        if(isset($jsonDecodedData['otherCities'])){
		        	$result = $jsonDecodedData['otherCities'];
		        	foreach ($result as $key => $value) {
		        		$result[$key]['obj'] = $value['label'];
		        		unset($result[$key]['label']);
		        	}
		        }
		        // Set the Resilt
		        
		        $finalData['cities_edupref']['Other Cities'] = $result;
		        

	        }
	        echo json_encode($finalData);
		}

		/**
		* Function to follow/unfollow the tags from User Profile
		* Call the API UserProfile/updateFollowFieldsForUser
		* MAB-1387 Ankit bansal
		*/
		public function updateTagsForUser(){
			$userData = $this->getLoggedInUserData();
			$userId = $userData['userId'];

			if($userId != 0){
				$entityIds = $this->input->post('entityId');
				$entityType = $this->input->post('entityType');
				$status = $this->input->post('status');
				$followType = $this->input->post('followType');	
				$postData = array(
							'entityIds' => $entityIds,
							'entityType' => $entityType,
							'status' => $status,
							'followType' => $followType
					);

				$APIClient = $this->load->library("APIClient");        
		        $APIClient->setUserId($userId);
		        $APIClient->setVisitorId(getVisitorId());
		        $APIClient->setRequestType("post");
		        $APIClient->setRequestData($postData);
		        $jsonDecodedData =$APIClient->getAPIData("UserProfile/updateFollowFieldsForUser/");
		        
			}
			


		}

	// Function to get update user national educational preference section 
	function updateUserNationalEducationalPreference($userData) { 
		if(!verifyCSRF()) { return false; }

		$userData = $this->getLoggedInUserData();	
		$userId = $userData['userId'];		

		if($userId > 0) {

			$viewFile = $this->sectionReadableViews['educationalPreferenceSection'];				

			$finalData = array();
			
			$finalData = $this->loadUserEducationalPreferenceData($userData);
			$this->load->library('userProfile/UserProfileLib');
			$finalData['privacyDetails']  = $this->userprofilelib->getUserPrivacyDetails($userId);

			$sectionView = $this->load->view($viewFile, $finalData,true);
			
			echo $sectionView;

		}

	}

}
