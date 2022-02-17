<?php

	class UserProfileController extends MX_Controller {
	
		private $sectionEditableViews = array('personalInformationSection'=>'addProfilePersonalInformation',
											'educationalBackgroundSection'=>'addProfileEducationBackground',
											'workExperienceSection'=>'addProfileWorkExperience',
											'educationalPreferenceNationalSection'=>'addProfileNationalEducationPreference',
											'educationalPreferenceAbroadSection'=>'addProfileAbroadEducationPreference');
		
		private $sectionReadableViews = array('personalInformationSection'=>'profilePagePersonalInformation',
											'educationalBackgroundSection'=>'profilePageEducationBackground',
											'educationalPreferenceSection'=>'profilePageEducationPreference',
											'workExperienceSection'=>'profilePageWorkExperience',
											'headerSection'=>'profilePageHeader');

		private $entityDetailView = array('Tag'=>'tagDetailTuple',
										'User'=>'followUserDetail',
										'Question'=>'questionDiscussionDetailTuple',
										'Discussion'=>'questionDiscussionDetailTuple'
									);
		private $activityTypeMapping = array('Q'=>'question','D'=>'discussion','T'=>'tag','U'=>'userprofile');

		function __construct()
    	{
        	$this->load->helper('string');
        	$this->load->helper('image');
        	$this->validate= $this->checkUserValidation();
    	}

		function updateUserProfile() {

			if(!verifyCSRF()) { return false; }
	 
            $sectionType = $this->input->post('sectionType');
            $action = $this->input->post('action');
            $isStudyAbroad = $this->input->post('isStudyAbroad');

            $_POST['isStudyAbroadFlag'] = $isStudyAbroad; // education background details not able to edit from national profile page in case user is abroad type because abroad user education fields are differnt. This flag used in tUserPref populator
            if(! ($sectionType == 'educationalPreferenceSection' && $isStudyAbroad == 'yes')){ // By passing while saving for abroad preference section becoz abroad fields not updating in tuserpref
            	unset($_POST['isStudyAbroad']);
			}

            $abroadSpecializationFlag = $this->input->post('abroadSpecializationFlag');
            $abroadSpecialization = $this->input->post('abroadSpecialization');
            
			if(!empty($sectionType)) {

				$this->load->library('UserProfileLib');
				$userData = $this->getLoggedInUserData();	

				$userId = $userData['userId'];
			
			
				if(!empty($userId)) {

					//insert in queue for partial indexing
					$user_response_lib = $this->load->library('response/userResponseIndexingLib');	       					
					$extraData = "{'personalInfo:true'}";
					$user_response_lib->insertInIndexingQueue($userId, $extraData);


					$privacyDetails  = $this->userprofilelib->getUserPrivacyDetails($userId);
					
					if($action == 'save') {

						$_POST['context'] = 'unifiedProfile';
						$_POST['isUnifiedProfile'] = 'yes';
						
						if($sectionType == 'workExperienceSection') {

							$workExGlobalArray = $this->input->post('globalArray');
     
				            $workExGlobalArray = explode(',', $workExGlobalArray);

				            $this->carryForwardWorkExPrivacy($workExGlobalArray,$userId);
				            $privacyDetails  = $this->userprofilelib->getUserPrivacyDetails($userId);

				            
				            foreach ($workExGlobalArray as $count) {
				            	$employerKey = "employer_".$count;
				           		$designationKey = "designation_".$count;
				           		$departmentKey = "department_".$count;

				           		$employer[]	= str_replace(array("<",">"), " ",$_POST[$employerKey]);
				           		unset($_POST[$employerKey]);
				           		$designation[]	= str_replace(array("<",">")," ",$_POST[$designationKey]);
				           		unset($_POST[$designationKey]);
				           		$department[]	= str_replace(array("<",">"), " ", $_POST[$departmentKey]);
				           		unset($_POST[$departmentKey]);
				           		$currentJob[] = $_POST[$count];
				           }   
				           
				           $_POST['employer'] = $employer;
				           $_POST['designation'] = $designation;
				           $_POST['department'] = $department;
				           $_POST['currentJob'] = $currentJob;

			        	} else if($sectionType == 'educationalBackgroundSection'){
			        		$newEducationLevels = $this->input->post('EducationBackground');

			        		$newFields = array('10','12','UG','PG','PHD');

			           		$_POST['EducationBackground'] = array('bachelors','masters','phd','xth','xiith');
			           		
			           		$oldFields = array_diff($newFields,$newEducationLevels);		
			           	
			           		if(empty($newEducationLevels)){
			           			$oldFields = $newFields;
			           		}

			           		$this->setEducationFieldPublic($oldFields,$userId);

           				} else if ($sectionType == 'educationalPreferenceSection' && $isStudyAbroad != 'yes') { 

							if(empty($_POST['exams'])) {
								$_POST['exams'] = array();
							} else {
								$examIds = array();
								foreach($_POST['exams'] as $exam) {
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
							$_POST['examTobeDeleted'] = json_encode(explode(",",$_POST['examTobeDeleted']));
							
						}
						$_POST['tracking_keyid'] = 695;
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
							$finalData = $this->getUserSectionDetails($userId, $sectionType, $action);

							$finalData['privacyDetails']  = $privacyDetails;
							$finalData['userData'] = $userData;

							$sectionView = $this->load->view($viewFile, $finalData,true);
							
							$headerView = '';
							if($sectionType != 'educationalPreferenceSection') {
								$headerView = $this->loadHeaderView($sectionType,$finalData,true);
							} else {
                                if($isStudyAbroad == 'yes') {
                                        $headerView = 'yes'.'#@#@#@'.$abroadSpecialization;
                                } else {
                                        $headerView = 'no'.'#@#@#@'.$abroadSpecialization;
                                }
                            }

							echo $sectionView.'#@#@#@'.$headerView;
						}

					} else if($action == 'cancel') {
						
						$viewFile = $this->sectionReadableViews[$sectionType];
						$finalData = $this->getUserSectionDetails($userId, $sectionType, $action);

						$finalData['userData'] = $userData;
						$finalData['privacyDetails']  = $privacyDetails;
						
						echo $this->load->view($viewFile, $finalData);
					}

				}

			} else {
				echo 'error';
			}
		}

		function showUserProfile() {
			$this->load->library('UserProfileLib');
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

			if($data['personalInfo']['Experience'] === NULL){
				$data['personalInfo']['Experience'] =-1;
			}
	
			$data['flagData'] = $this->userprofilelib->getUserFlagDetails($userId);

			if(isset($data['10'])){
				

				if($data['10']['Board'] == 'CBSE'){
					global $Reverse_CBSE_Grade_Mapping;
					$data['10']['Marks']= $Reverse_CBSE_Grade_Mapping[$data['10']['Marks']];
				
				} else if($data['10']['Board'] == 'ICSE'){
					//global $ICSE_Grade_Mapping;
					//$data['10']['Marks']= $ICSE_Grade_Mapping[$data['10']['Marks']];
				
				} else if ($data['10']['Board'] == 'IGCSE'){
					global $Reverse_IGCSE_Grade_Mapping;
					$data['10']['Marks']= $Reverse_IGCSE_Grade_Mapping[$data['10']['Marks']];

				}

			}
		
			//Get User Location name
			if(isset($data['personalInfo']['Country'])){
				$data['userCountry'] = ($data['personalInfo']['Country'] !=2)? $this->userprofilelib->getCountryName($data['personalInfo']['Country']) : 'India';
				if($data['personalInfo']['Country'] ==2){
					$data['userlocationData'] = $this->userprofilelib->getUserLocationDetails($data['personalInfo']['City'], $data['personalInfo']['Locality']);
				}
			}

			$data['userData'] = $userData;
			$data['userId'] = $userId;
			$data['blacklistedWords'] = file_get_contents("public/blacklisted.txt");
			
            $data['validateuser']  = $this->validate;

			$this->benchmark->mark('dfp_data_start');
	        $dfpObj   = $this->load->library('common/DFPLib');
	        $dpfParam = array('parentPage'=>'DFP_MyProfile');
	        $data['dfpData']  = $dfpObj->getDFPData($this->validate, $dpfParam);
	        $this->benchmark->mark('dfp_data_end');

            $data['xth'] = $data['10'];
			$data['xiith'] = $data['12'];
			unset($data['10']);
			unset($data['12']);

			$data['privacyDetails']  = $this->userprofilelib->getUserPrivacyDetails($userId);
			$data['userLevelDetails'] = $this->userprofilelib->getUserLevelData($userId);
  			
  			$userprofilemodeldesktop = $this->load->model('userProfile/userprofilemodeldesktop');
        	$desiredCourseDetails = $userprofilemodeldesktop->getDesiredCourseDetailsByDesiredCourseId($data['userPreference']['DesiredCourse'], $data['userPreference']['PrefId'], $data['userPreference']['ExtraFlag']);
        	
        	$data['desiredCourseDetails'] = $desiredCourseDetails;
			$competitiveExam = $data['Competitive exam'];
        	unset($data['Competitive exam']);

			if($data['userPreference']['ExtraFlag'] == 'studyabroad') {

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

			} else {
				$data['domesticInterestDetails'] = $this->_getUserDeomesticInterestDetails($data['validateuser'][0]['userid']);				
			}

			$data['competitiveExam'] = $competitiveExam;
			$data['title'] = "Edit Profile | Shiksha.com";

			//user activity stats - My Activity
			$start = 0;
			$count = 10; 
			/*$userActivityStats = $this->getUserActivityStats($userId,$start,$count);
			$formattedUserActivityStats = $this->getFormattedUserActivityStats($userActivityStats);
			
			$data = array_merge($formattedUserActivityStats,$data);*/

			//below code used for beacon tracking
			$data['trackingpageIdentifier'] = 'userProfileEditPage';
			$data['trackingcountryId']=2;


			//loading library to use store beacon traffic inforamtion
			$this->tracking=$this->load->library('common/trackingpages');
			$this->tracking->_pagetracking($data);

			$this->load->config('user/unsubscribeConfig');
			$data['mailerUnsubscribeCategory']                = $this->config->item('mailerCategory');

			$data['unsubscribeData']  = $this->userprofilelib->getUserUnsubscribeMapping($userId);

			$this->_trackProfilePageLoad($userId, 'profilePage', 'pageLoad', 'userProfile');
			$this->load->view('userProfile',$data);
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

		function showUserPublicProfile($userId){

			$userId_int = intval($userId);
	        $userId_Length = strlen($userId);
	        $userId_int_Length = strlen($userId_int);
			if($userId_Length <= 0 || $userId_Length != $userId_int_Length) {
				redirect(SHIKSHA_HOME, 'location');
			}

			$this->load->library('UserProfileLib');

			
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
			
        	
			$data = $this->userprofilelib->getUserProfileDetails($userId,'publicProfile', 'all');
			$data['publicProfile'] = true;
			$data['validateuser']  = $this->checkUserValidation();
			$data['userId'] = $userId;
			$data['userLevelDetails'] = $this->userprofilelib->getUserLevelData($userId);
			$loggedInUserId = $this->getUserIdForLoggedInUser();
			if(($loggedInUserId) > 0 && $loggedInUserId != $userId){
				$isUserFollowing = $this->userprofilelib->getFlagIfIAmFollowing($userId,$loggedInUserId);
				$data['isUserFollowing'] = $isUserFollowing[0]['status'];
			}
			$data['loggedInUserId'] = $loggedInUserId;
			
			//Get User Location name
			if(isset($data['personalInfo']['Country'])){
				$data['userCountry'] = ($data['personalInfo']['Country'] !=2)? $this->userprofilelib->getCountryName($data['personalInfo']['Country']) : 'India';
				if($data['personalInfo']['Country'] ==2){
					$data['userlocationData'] = $this->userprofilelib->getUserLocationDetails($data['personalInfo']['City'], $data['personalInfo']['Locality']);
				}
			}

			if(isset($data['10'])){
				

				if($data['10']['Board'] == 'CBSE'){
					global $Reverse_CBSE_Grade_Mapping;
					$data['10']['Marks']= $Reverse_CBSE_Grade_Mapping[$data['10']['Marks']];
				
				} else if($data['10']['Board'] == 'ICSE'){
					//global $ICSE_Grade_Mapping;
					//$data['10']['Marks']= $ICSE_Grade_Mapping[$data['10']['Marks']]; //keep it commented
				
				} else if ($data['10']['Board'] == 'IGCSE'){
					global $Reverse_IGCSE_Grade_Mapping;
					$data['10']['Marks']= $Reverse_IGCSE_Grade_Mapping[$data['10']['Marks']];

				}

			}
			
			$data['xth'] = $data['10'];
			$data['xiith'] = $data['12'];
			unset($data['10']);
			unset($data['12']);
			
			$userprofilemodeldesktop = $this->load->model('userProfile/userprofilemodeldesktop');
        	$data['desiredCourseDetails'] = $userprofilemodeldesktop->getDesiredCourseDetailsByDesiredCourseId($data['userPreference']['DesiredCourse'], $data['userPreference']['PrefId'], $data['userPreference']['ExtraFlag']);
        	
			$competitiveExam = $data['Competitive exam'];
        	unset($data['Competitive exam']);

			if($data['userPreference']['ExtraFlag'] == 'studyabroad') {

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

			} else if($data['userPreference']['showNationalPref'] == 'yes'){

				$data['domesticInterestDetails'] = $this->_getUserDeomesticInterestDetails($userId);		
			}

			$data['competitiveExam'] = $competitiveExam;
			$data['title'] = $data['personalInfo']['FirstName']." ".$data['personalInfo']['LastName']." | Shiksha.com";

			//below code used for beacon tracking
			$data['trackingpageIdentifier'] = 'userPublicProfilePage';
			$data['trackingcountryId']=2;

			$this->benchmark->mark('dfp_data_start');
	        $dfpObj   = $this->load->library('common/DFPLib');
	        $dpfParam = array('parentPage'=>'DFP_MyProfile','pageType'=>'publicProfile');
	        $data['dfpData']  = $dfpObj->getDFPData($data['validateuser'], $dpfParam);
	        $this->benchmark->mark('dfp_data_end');

			//loading library to use store beacon traffic inforamtion
			$this->tracking=$this->load->library('common/trackingpages');
			$this->tracking->_pagetracking($data);
			$this->load->view('userProfile',$data);

		}

		function getUserLocalities(){
		
			if(!verifyCSRF()) { return false; }

			$cityId = $this->input->post('city');
			$cityId = intval($cityId);
			if($cityId <= 0) {
				echo json_encode(array());
			}
			
			$registrationForm = new \registration\libraries\Forms\LDB; 
	        $localityField = $registrationForm->getField('preferredStudyLocality');
	        
	        $response = array();
	        
	        $localities = $localityField->getValues(array('cityId' => $cityId));
	        
	        $localitiesArr = array();
	        foreach($localities as $zoneId => $localitiesInZone) {
	            $firstLocality = reset($localitiesInZone);
	            $localitiesArr[$firstLocality['zoneName']] = array();
	            foreach($localitiesInZone as $locality) {
	                $localitiesArr[$firstLocality['zoneName']][$locality['localityId']] = $locality['localityName'];
	            }
	        }
	        if(count($localitiesArr)) {
	            $response['localities'] = $localitiesArr;
	        }
	        
	        $cityGroup = $localityField->getValues(array('cityId' => $cityId,'cityGroup' => TRUE));
	        $cityArr = array();
	        foreach($cityGroup as $city) {
	            $cityArr[$city['city_id']] = $city['city_name'];
	        }
	        
	        if(count($cityArr)) {
	            $response['cityGroup'] = $cityArr;
	        }
	        echo json_encode($response);
		}

	function refreshMentorWidget() {
		$userid = $this->validate[0]['userid'];
        $mentorshipData = $this->getMentorshipData($userid);
        echo $this->load->view('profilePageMentorPage',$mentorshipData,true);
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
			error_log("User_Profile_Desktop after updating user attribute : ".print_r((memory_get_peak_usage()/(1024*1024)),true));

			$user_response_lib = $this->load->library('response/userResponseIndexingLib');	       					
			$extraData = "{'personalInfo:true'}";
			$user_response_lib->insertInIndexingQueue($userId, $extraData);
			error_log("User_Profile_Desktop after adding to indexing queue : ".print_r((memory_get_peak_usage()/(1024*1024)),true));

			echo 1;

		}

		function showEditableUserProfileSection () {

			if(!verifyCSRF()) { return false; }

            $sectionType = $this->input->post('sectionType');
			$userEducationalPreference = $this->input->post('userEducationalPreference');
			$isStudyAbroadFlag = $this->input->post('isStudyAbroadFlag');
			$abroadSpecializationFlag = $this->input->post('abroadSpecializationFlag');
			if($abroadSpecializationFlag!=="")
			{
				return;
			}

			if(!empty($sectionType)) {
			
				$this->load->library('UserProfileLib');
				$userData = $this->getLoggedInUserData();	

				$userId = $userData['userId'];

				if(!empty($userId)) {

					$finalData = $this->getUserSectionDetails($userId,$sectionType, 'edit', $userEducationalPreference);

					if($sectionType == 'educationalPreferenceSection') {
						if(empty($userEducationalPreference)) {
		        			$userEducationalPreference = $finalData['userPreference']['ExtraFlag'];
		        		}
						if($userEducationalPreference == 'studyabroad') {
							$sectionType = 'educationalPreferenceAbroadSection';
						} else {
							$sectionType = 'educationalPreferenceNationalSection';
						}
					}
					$finalData['isStudyAbroadFlag'] = $isStudyAbroadFlag;
					$finalData['abroadSpecializationFlag'] = $abroadSpecializationFlag;
					
					$viewFile = $this->sectionEditableViews[$sectionType];
					$this->load->view($viewFile, $finalData);

				} else {
					echo 'notLoggedIn';
				}
			} else {
				echo 'error';
			}
		}


		public function getUserSectionDetails($userId,$sectionType, $action = '', $userEducationalPreference = ''){
			$userData = array();

			if($sectionType == 'personalInformationSection') {	
				$personalInfo = $this->userprofilelib->getUserProfileDetails($userId, 'myProfile', 'personalInfo' );
				$userData['personalInfo'] = $personalInfo['personalInfo'];

				if(isset($userData['personalInfo']['Country'])){
					$userData['userCountry'] = ($userData['personalInfo']['Country'] !=2)? $this->userprofilelib->getCountryName($userData['personalInfo']['Country']) : 'India';
					if($userData['personalInfo']['Country'] ==2){
						$userData['userlocationData'] = $this->userprofilelib->getUserLocationDetails($userData['personalInfo']['City'], $userData['personalInfo']['Locality']);
					}
				}
				unset($personalInfo);

				$socialInfo = $this->userprofilelib->getUserProfileDetails($userId, 'myProfile', 'socialInfo' );
				$userData['socialInfo'] = $socialInfo['socialInfo'];
				unset($socialInfo);

				$additionalInfo = $this->userprofilelib->getUserProfileDetails($userId, 'myProfile', 'additionalInfo');
				$userData['additionalInfo'] = $additionalInfo['additionalInfo'];
				unset($additionalInfo);

				$userData['userLevel'] = $this->userprofilelib->getUserPointsLevel($userId);
				$registrationForm = \registration\builders\RegistrationBuilder::getRegistrationForm('LDB',array('courseGroup' => 'localUG'));

				$userData['fields']['isdCode'] = $registrationForm->getField('isdCode');
				$userData['fields']['residenceCityLocality'] = $registrationForm->getField('residenceCityLocality');
					
			} else if($sectionType == 'workExperienceSection'){
				$workExMaxCount =10;

				for ($workExCount=1; $workExCount <= $workExMaxCount; $workExCount++) { 
					$workExLevel = 'workExp'.$workExCount;
					$data = $this->userprofilelib->getUserProfileDetails($userId, 'myProfile', $workExLevel);
					$userData[$workExLevel] = $data[$workExLevel];
				}

				 $personalInfo = $this->userprofilelib->getUserProfileDetails($userId, 'myProfile', 'personalInfo');

				 $userData['personalInfo']['Experience'] = $personalInfo['personalInfo']['Experience'];

				 unset($personalInfo);

				 $this->workExperienceLib = new \registration\libraries\FieldValueSources\WorkExperience;
				 $expValues = $this->workExperienceLib->getValues();
				 unset($workExperienceLib);
				 $userData['WorkExRange'] =  $expValues;


				 if($userData['personalInfo']['Experience'] === NULL){
					$userData['personalInfo']['Experience'] =-1;
				}

				 
				
			} else if($sectionType == 'educationalPreferenceSection'){

				$userPreferenceData = $this->userprofilelib->getUserProfileDetails($userId, 'myProfile', 'userPreference' );
        		$userPreference = $userPreferenceData['userPreference'];

        		if(empty($userEducationalPreference)) {
        			$userEducationalPreference = $userPreferenceData['userPreference']['ExtraFlag'];
        		}
        		if($userEducationalPreference == 'studyabroad') {

        			if($action == 'cancel' || $action == 'save') { // After click on save or cancel button

						$this->load->model('userProfile/userprofilemodeldesktop');
		        		
		        		$userData['desiredCourseDetails'] = $this->userprofilemodeldesktop->getDesiredCourseDetailsByDesiredCourseId($userPreference['DesiredCourse'], $userPreference['PrefId'], $userPreference['ExtraFlag']);

		        		$locationPreferencesData = $this->userprofilelib->getUserProfileDetails($userId, 'myProfile', 'locationPreferences' );
						$locationPreferences = $locationPreferencesData['locationPreferences'];

						$competitiveExamData = $this->userprofilelib->getUserProfileDetails($userId, 'myProfile', 'Competitive exam');				
						$competitiveExam = $competitiveExamData['Competitive exam'];
						unset($competitiveExamData['Competitive exam']);

						$additionalInfoData = $this->userprofilelib->getUserProfileDetails($userId, 'myProfile', 'additionalInfo' );
						$userData['additionalInfo'] = $additionalInfoData['additionalInfo'];

						$personalInfoData = $this->userprofilelib->getUserProfileDetails($userId, 'myProfile', 'personalInfo' );
						$userData['personalInfo']['Passport'] = $personalInfoData['personalInfo']['Passport'];

						$educationalPreferenceData = $this->loadSAEducationalPreferenceData($userPreference, $locationPreferences, $competitiveExam);

						$userData['desiredCourseDetails']['subCatgoryName'] = $educationalPreferenceData['subCatgoryName'];
						$userData['whenPlanToGo'] = $educationalPreferenceData['whenPlanToGo'];
						$userData['countryNames'] = $educationalPreferenceData['countryNames'];
						$userData['budget'] = $educationalPreferenceData['budget'];
						$userData['sourceOfFunding'] = $educationalPreferenceData['sourceOfFunding'];

						unset($competitiveExam);
						$userData['competitiveExam'] = $educationalPreferenceData['abroadCompetitiveExam'];

        			} else { // After click on edit button

						$registrationForm = \registration\builders\RegistrationBuilder::getRegistrationForm('LDB',array('courseGroup' => 'studyAbroadRevamped','context'=>'abroadUserSetting'));
						
						$userData['fields']['destinationCountry'] = $registrationForm->getField('destinationCountry');
						$userData['fields']['fieldOfInterest'] = $registrationForm->getField('fieldOfInterest');
						$userData['fields']['abroadDesiredCourse'] = $registrationForm->getField('abroadDesiredCourse');
						$userData['fields']['desiredGraduationLevel'] = $registrationForm->getField('desiredGraduationLevel');
						$userData['fields']['abroadSpecialization'] = $registrationForm->getField('abroadSpecialization');
						$userData['fields']['whenPlanToGo'] = $registrationForm->getField('whenPlanToGo');
						$userData['fields']['budget'] = $registrationForm->getField('budget');
						$userData['fields']['examTaken'] = $registrationForm->getField('examTaken');
						$userData['fields']['passport'] = $registrationForm->getField('passport');
						$userData['fields']['examsAbroad'] = $registrationForm->getField('examsAbroad');
						$userData['fields']['bookedExamDate'] = $registrationForm->getField('bookedExamDate');

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
					}

				} else {
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
			
				}

				$userData['userPreference'] = $userPreference;

			} else if($sectionType == 'educationalBackgroundSection'){
				
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
			
			}

			return $userData;
		}

		function getUserCityFields(){
			$regFormId = $this->input->post('regFormId', true);
			$country = $this->input->post('country', true);
			if($country != INDIA_ISD_CODE){
				echo '';
				return;
			}

			$data = array();
			$registrationForm = \registration\builders\RegistrationBuilder::getRegistrationForm('LDB',array('courseGroup' => 'localUG'));
			$data['fields']['residenceCityLocality'] = $registrationForm->getField('residenceCityLocality');
			$data['regFormId'] = $regFormId;
			
			echo  $this->load->view('userProfile/userCityBlock', $data); 
		}

		function loadHeaderView($sectionType,$finalData){
			if($sectionType == 'personalInfo'){
				if($finalData['socialInfo']['FacebookId'] && $finalData['socialInfo']['FacebookId'] != ''){
					
					if (strpos($finalData['socialInfo']['FacebookId'], 'http') === false) {
						$finalData['socialInfo']['FacebookId'] = 'https://'.$finalData['socialInfo']['FacebookId'];
    				}
					$socialInfo = "<li><a href='".$finalData['socialInfo']['FacebookId']."' target='_blank'><i class='icons1 ic_fb1'></i></a></li>";
				}
				if($finalData['socialInfo']['TwitterId'] && $finalData['socialInfo']['TwitterId'] != ''){
					
					if (strpos($finalData['socialInfo']['TwitterId'], 'http') === false) {
						$finalData['socialInfo']['TwitterId'] = 'https://'.$finalData['socialInfo']['TwitterId'];
    				}
					$socialInfo = "<li><a href='".$finalData['socialInfo']['TwitterId']."' target='_blank'><i class='icons1 ic_twtr'></i></a></li>";
				}
				if($finalData['socialInfo']['LinkedinId'] && $finalData['socialInfo']['LinkedinId'] != ''){
					
					if (strpos($finalData['socialInfo']['LinkedinId'], 'https://') === false) {
						$finalData['socialInfo']['LinkedinId'] = 'https://'.$finalData['socialInfo']['LinkedinId'];
    				}
					$socialInfo = "<li><a href='".$finalData['socialInfo']['LinkedinId']."' target='_blank'><i class='icons1 ic_ln'></i></a></li>";
				}
				if($finalData['socialInfo']['YoutubeId'] && $finalData['socialInfo']['YoutubeId'] != ''){
					
					if (strpos($finalData['socialInfo']['YoutubeId'], 'http') === false) {
						$finalData['socialInfo']['YoutubeId'] = 'https://'.$finalData['socialInfo']['YoutubeId'];
    				}
					$socialInfo = "<li><a href='".$finalData['socialInfo']['YoutubeId']."' target='_blank'><i class='icons1 ic_youtube'></i></a></li>";
				}
				if($finalData['socialInfo']['PersonalURL'] && $finalData['socialInfo']['PersonalURL'] != ''){
					
					if (strpos($finalData['socialInfo']['PersonalURL'], 'http') === false) {
						$finalData['socialInfo']['PersonalURL'] = 'https://'.$finalData['socialInfo']['PersonalURL'];
    				}
					$socialInfo = "<li><a href='".$finalData['socialInfo']['PersonalURL']."' target='_blank'><i class='icons1 ic_webicon'></i></a></li>";
				}

				return $finalData['personalInfo']['FirstName'].' '.$finalData['personalInfo']['LastName'].'#@#@#@'.$finalData['additionalInfo']['AboutMe'].'#@#@#@'.$socialInfo;
			} else if($sectionType == 'workExperienceSection'){
				$employer = $this->returnCurrentEmployer($finalData);
				return $employer;	
			} else if($sectionType == 'educationalBackgroundSection'){
				$lastEducation = $this->getLastEducation($finalData);
				return $lastEducation;
			}
		}

		function returnCurrentEmployer($finalData){
			for($x = 1; $x <= 10; $x++) {
                if($finalData['workExp'.$x]['CurrentJob'] == 'YES'){ 
                	$currentEmployer = '<i class="icons1 ic_work"></i><p>'.$finalData['workExp'.$x]['Designation'].' at '.$finalData['workExp'.$x]['Employer'].'</p>';
                }
			}
			return $currentEmployer;	
		}

		function getLastEducation($finalData){
            if($finalData['PHD'] && $finalData['PHD']['InstituteName']){
                $study = 'PHD from '.$finalData['PHD']['InstituteName'];
            }else if($finalData['PG'] && $finalData['PG']['Name'] && $finalData['PG']['InstituteName']){
                $study = $finalData['PG']['Name'].' from '.$finalData['PG']['InstituteName'];
            }else if($finalData['UG'] && $finalData['UG']['InstituteName'] && $finalData['UG']['Name']){
                $study = $finalData['UG']['Name'].' from '.$finalData['UG']['InstituteName'];
            }else if($finalData['xiith'] && $finalData['xiith']['InstituteName'] != ''){
                $study = '12th at '.$finalData['xiith']['InstituteName'];
            }else if($finalData['xth'] && $finalData['xth']['InstituteName'] != ''){
                $study = "10th at ".$finalData['xth']['InstituteName'];
            }

            if($study != ''){
            	$educationBackground = '<i class="icons1 ic_edu"></i><p>'.$study.'</p>';
			}

			return $educationBackground;
		}

		function addUserExams () {

            $desiredCourse = $this->input->post('desiredCourse');
            $regFormId = $this->input->post('regFormId');
            $preSelectedCompetitiveExam = $this->input->post('preSelectedCompetitiveExam');

			if(!empty($desiredCourse)) {
			
				$this->load->library('UserProfileLib');
				$userData = $this->getLoggedInUserData();	

				$userId = $userData['userId'];

				if(!empty($userId)) {
					$registrationForm = \registration\builders\RegistrationBuilder::getRegistrationForm('LDB',array('courseGroup' => 'localUG'));
					$exams = $registrationForm->getField('exams');
					$data['listExams'] = $exams->getValues(array('desiredCourse'=>$desiredCourse));
					unset($data['listExams']['others']['NOEXAM']);
					$data['regFormId'] = $regFormId;
					$data['preSelectedCompetitiveExam'] = $preSelectedCompetitiveExam;
					
					$this->load->view('userProfile/addUserExams', $data); 
				}
			}
		}

		function getMentorshipData($userId) {

			$mentorshipStatus = false;
			$this->load->model('CA/mentormodel');
			$mentorModel = new MentorModel();
			$mentorInformation = $mentorModel->checkIfMentorAssignedToMentee(array($userId));
			
			if($mentorInformation[$userId]){
				
				if(isset($_COOKIE['ci_mobile']) && ($_COOKIE['ci_mobile']=='mobile')){
	                header('location:'.MENTEE_MOBILE_DASHBOARD_URL); exit;
	            }
				
				$mentorId = $mentorInformation[$userId]['mentorId'];
				$chatId = $mentorInformation[$userId]['chatId'];
				$this->load->helper('CA/cr');
				$this->load->model('CA/camodel');
				$caModel = new CAModel();
				$mentorshipStatus = true;
				$caDetails = $caModel->getAllCADetails($mentorId);
				$this->load->builder('ListingBuilder','listing');
				$listingBuilder = new ListingBuilder;
				$courseRepository = $listingBuilder->getCourseRepository();
				$courseObj = $courseRepository->find($caDetails[0]['ca']['mainEducationDetails'][0]['courseId']);
				$data['courseObj'] = $courseObj;
				$data['caDetails'] = $caDetails;
				$instituteRepository = $listingBuilder->getInstituteRepository();
				$instObj = $instituteRepository->find($caDetails[0]['ca']['mainEducationDetails'][0]['instituteId']);	
				$data['instObj'] = $instObj;
				$mentorSlots = $mentorModel->checkMentorSlots($mentorId);
				$formattedMentorSlots  = formatMentorSlots($mentorSlots);
				$data['mentorSlots'] = $formattedMentorSlots;
				$data['mentorId'] = $mentorId;
				$data['chatId'] = $chatId;
				$data['slotData'] = $mentorModel->checkIfMenteeBookedOrRequestSlot($userId,$mentorId);
				$data['scheduleData'] = $mentorModel->checkIfMenteeHasAnyScheduledChat($userId,$mentorId);
				$data['completedChats'] = $mentorModel->getMentorshipChatHistory($mentorId, $userId);
			}
			$data['mentorshipStatus'] = $mentorshipStatus;
			$data['userId'] = $userId;

			return $data;
		}

		public function setUserFieldPrivate(){

			if(!verifyCSRF()) { return false; }

			$userId = $this->input->post('userId');
			$fieldIds = $this->input->post('fieldIds');
			$this->load->library('userProfile/UserProfileLib');
			if(empty($userId) || empty($fieldIds)){
				return;
			}
			
			echo $this->userprofilelib->setUserFieldPrivate($userId,$fieldIds);

		}

		public function setUserFieldPublic(){

			if(!verifyCSRF()) { return false; }

			$userId = $this->input->post('userId');
			$fieldIds = $this->input->post('fieldIds');
			$this->load->library('userProfile/UserProfileLib');
			if(empty($userId) || empty($fieldIds)){
				return;
			}
			echo $this->userprofilelib->setUserFieldPublic($userId,$fieldIds);

		}

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

		public function carryForwardWorkExPrivacy($newWorkExArray = array(),$userId){
				
			$newPrivacy = array();
				
			foreach ($newWorkExArray as $newLevel => $oldLevel) {
				
				if($oldLevel>10){
					continue;;
				}

				$fieldId = 'EmployerworkExp'.$oldLevel;
				

				if($this->checkIfPrivate($fieldId,$userId)){
					$newPrivacy[] = $newLevel;
				}
			}

			if(empty($newPrivacy)){
				return;
			}

			$this->markPreviousPrivacyHistory($userId);

			$this->createNewPrivacyWorkEx($newPrivacy,$userId);

		}

		public function checkIfPrivate($fieldId,$userId){
			$userprofilemodeldesktop = $this->load->model('userProfile/userprofilemodeldesktop');

			$count = $userprofilemodeldesktop->checkIfPrivate($fieldId,$userId);

			if(count($count)> 0){
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

		/* 
		*	Function to save user profile activity in LDB_userTracking
		*	@Params: trackingKey and trackingValue
		*/
		public function userProfileTracking(){
			$data = array();
			
			$data['userId'] = ($this->validate[0]['userid'] > 0)? $this->validate[0]['userid'] : 0;

			/* Tracking information */
			$data['trackingKey'] = isset($_POST['key'])? $this->input->post('key', true): 'NULL';
			$data['trackingValue'] = isset($_POST['value'])? $this->input->post('value', true): 'NULL';

			$data['visitorSessionId'] = getVisitorSessionId();
			$data['type'] = 'userProfile';

			$userprofilemodel = $this->load->model('userProfile/userprofilemodeldesktop');
			if($userprofilemodel->saveUserProfileTracking($data) > 0){
				echo 'success';
			}else{
				echo 'fail';
			}

			return true;
		}

		function getUserActivityStats($userId,$start = 0, $count = 10,$ctaExlusionFlag = false){
			
	        $this->load->library('userProfile/UserProfileLib');
			$this->userprofilelib = new UserProfileLib;

			$activityStats = $this->userprofilelib->getUserProfielStats($userId,$start,$count);
						
			//Pass ctaExclusionFlag as false to get cta working
			$activityStats['stats'] = $this->userprofilelib->formatUserStatsWithCTA($activityStats['stats'],$ctaExlusionFlag);

			return $activityStats;
		}

		public function getFormattedUserActivityStats($userActivityStats){

			$finalData['stats'] = $userActivityStats['stats'];
			$finalData['activities'] = $userActivityStats['activities'];
			$statCount = count($finalData['stats']) + 1;		//1 add for All recent activity
			$statsCardPerRow = 7;		//no of cards per slider

			//to print empty div for stats
			$finalData['statCountSlider'] = $statCount;
			if(($statCount%$statsCardPerRow) > 0){
				$finalData['statCountSlider'] += $statsCardPerRow -($finalData['statCount']%$statsCardPerRow);
			}

			return $finalData;
		}

		public function loadMyActivityStats(){

			if(!verifyCSRF()) { return false; }

			foreach ($_POST as $key => $value) {
				if ($_POST[$key] != strip_tags($value))
                   			 return false;
			}
			$this->load->library('UserProfileLib');
			$userId = $this->input->post('userId');
			$publicProfile = $this->input->post('publicProfile');
			//$userId = '5135105';
			$userData = $this->getLoggedInUserData();	
			$loggedInUser = $userData['userId'];
			unset($userData);
			
			if($loggedInUser != $userId){
				if($this->checkIfPrivate('activitystats',$userId)){
					return;
				}
			}

			$start = 0;
			$count = 10; 

			$privacyDetails  = $this->userprofilelib->getUserPrivacyDetails($userId);
			if(!$publicProfile){
				$userActivityStats = $this->getUserActivityStats($userId,$start,$count,false);
			}	
			else {
				$userActivityStats = $this->getUserActivityStats($userId,$start,$count,true);
			}
			$formattedUserActivityStats = $this->getFormattedUserActivityStats($userActivityStats);
			$formattedUserActivityStats['activities'] = $this->getURLForIndividualTupples($formattedUserActivityStats['activities']);
			$formattedUserActivityStats['privacyDetails'] = $privacyDetails;

			unset($userActivityStats);
			
			echo $this->load->view('profileMyActivity',array('stats' => $formattedUserActivityStats['stats'], 'activities' => $formattedUserActivityStats['activities'],'statCountSlider' => $formattedUserActivityStats['statCountSlider'],'privacyDetails' => $formattedUserActivityStats['privacyDetails'],'userId' => $userId,'publicProfile' => $publicProfile ));
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

			if($typeOfStat == 'All Activity'){
				$start = 0;
				$count = 10; 
				$userActivityStats = $this->getUserActivityStats($userId,$start,$count);
				$formattedUserActivityStats = $this->getFormattedUserActivityStats($userActivityStats);
				$formattedUserActivityStats['activities'] = $this->getURLForIndividualTupples($formattedUserActivityStats['activities']);
				
				$viewFile =$this->load->view('profileAllActivity',array('activities' => $formattedUserActivityStats['activities']),true);
				
				if(!empty($viewFile)){
					echo json_encode(array('html'=>$viewFile, 'activityCount'=>count($formattedUserActivityStats['activities'])));
				} 
				return;
			}

			$data = $this->getDetailedTuplesData($userId, $typeOfStat, 0, 10);
			$publicProfile = $this->input->post('publicProfile');

			$data['publicProfile'] = $publicProfile;
			$data['iter'] = 0;

			$loggedInUserId = $this->getLoggedInUserData();
			$data['isUserLoggedIn'] = true;
			if(!($loggedInUserId['userId']) > 0){
				$data['publicProfile'] = true;
				$data['isUserLoggedIn'] = false;
			}
			$data['loggedInUserId'] = $loggedInUserId['userId'];

			$typeOfStatExclusionArray = array('Answer Later','Comment Later','Following','Followers','Tags Followed');

			if(!in_array($typeOfStat, $typeOfStatExclusionArray) || !($loggedInUserId['userId']) > 0){
				$data['actionNeeded'] = false;
			}else{
				$data['actionNeeded'] = true;
			}

			if($data['entityType'] == 'User')
				$type = 'userprofile';
			else
				$type = strtolower($data['entityType']);
			$data['activities'] = $this->getURLForIndividualTupples($data['activities'], $type);
			
			$viewFile =$this->load->view('userActivityDetailedView',$data,true);
			
			if(!empty($viewFile)){
				echo json_encode(array('html'=>$viewFile, 'activityCount'=>count($data['activities'])));
			} 
		}

		function getDetailedTuplesData($userId, $typeOfStat, $start=0, $count=10){

			$this->load->helper('muserProfile5/activity');
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

		function deleteCommentAnswerAction(){

			$data = $this->getDataForActionInTuples();
			$this->load->library('userProfile/UserProfileLib');
			$this->userprofilelib = new UserProfileLib;
			$data['status'] = 'deleted';
			$deleteEntityData = $this->userprofilelib->deleteCommentAnswerAction($data);

			echo $deleteEntityData;
		}

		function followUnfollowAction(){
			
			$data = $this->getDataForActionInTuples();
			$this->load->library('userProfile/UserProfileLib');
			$this->userprofilelib = new UserProfileLib;
			$data['action'] = $this->input->post('action');
			$followEntityData = $this->userprofilelib->followUnfollowAction($data);

			echo json_encode($followEntityData);
		}

		private function getDataForActionInTuples(){

			$data['userId'] = $this->getUserIdForLoggedInUser();
			$data['entityId'] = $this->input->post('entityId');
			$data['entityType'] = $this->input->post('entityType');
			return $data;
		}

		function getUserIdForLoggedInUser(){

			$userData = $this->getLoggedInUserData();
			$userId = $userData['userId'];
			if(!($userId > 0)){
				return false;
			}else{
				return $userId;
			}
		}

		function getURLForIndividualTupples($activities=array(), $entityType=null){
			$this->load->library('UserProfileLib');
			
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

		function getUserRecentActivities(){
			
			$start = isset($_POST['start'])?$this->input->post('start'):0;
			$count = isset($_POST['count'])?$this->input->post('count'):10;
			$customParams = $this->input->post('customParams');
			$ajaxCallCounterRecent = $customParams[1];
			$userId = $customParams[2];
			$publicProfile = $customParams[3];

			if(!is_numeric($ajaxCallCounterRecent))
			{
				return;
			}

			$finalData = array();
			$finalData['userId']= $userId;
			
			$userActivityStats = $this->getUserActivityStats($userId,$start,$count,false);
			$formattedUserActivityStats = $this->getFormattedUserActivityStats($userActivityStats);

			$formattedUserActivityStats['activities'] = $this->getURLForIndividualTupples($formattedUserActivityStats['activities']);
			
			$finalData = array_merge($formattedUserActivityStats,$finalData);
			$finalData['publicProfile'] = $publicProfile;

			unset($userActivityStats);
			unset($formattedUserActivityStats);
			
			$finalData['ajaxCallCounterRecent'] = $ajaxCallCounterRecent;

			$view = $this->load->view('profileAllActivity',$finalData, true);
			$view = trim($view);
			if(!empty($view)){
				echo json_encode(array('html'=>$view, 'ajaxCallCounterRecent'=>$finalData['ajaxCallCounterRecent'], 'activityCount'=>count($finalData['activities'])));
			} 
			else {
				echo 'NoData';
			}
			
		}

		function getUserDetailedActivities(){
			
			$start = isset($_POST['start'])?$this->input->post('start'):0;
			$count = isset($_POST['count'])?$this->input->post('count'):10;
			$customParams = $this->input->post('customParams');
			$ajaxCallCounterRecent = $customParams[1];
			$userId = $customParams[2];
			$publicProfile = $customParams[3];
			$typeOfStat = $customParams[4];
			$iter = $customParams[5];

			if(empty($typeOfStat)){
				return false;
			}

			$data = $this->getDetailedTuplesData($userId, $typeOfStat, $start, $count);
			$data['ajaxCallCounterRecent'] = $ajaxCallCounterRecent;
			$data['iter'] = $iter;
			$data['publicProfile'] = $publicProfile;
			
			$loggedInUserId = $this->getLoggedInUserData();
			$data['isUserLoggedIn'] = true;
			if(!($loggedInUserId['userId']) > 0){
				$data['publicProfile'] = true;
				$data['isUserLoggedIn'] = false;
			}
			$data['loggedInUserId'] = $loggedInUserId['userId'];

			$typeOfStatExclusionArray = array('Answer Later','Comment Later','Following','Followers','Tags Followed');

			if(!in_array($typeOfStat, $typeOfStatExclusionArray) || !($loggedInUserId['userId']) > 0){
				$data['actionNeeded'] = false;
			}else{
				$data['actionNeeded'] = true;
			}

			if($data['entityType'] == 'User')
				$type = 'userprofile';
			else
				$type = strtolower($data['entityType']);
			$data['activities'] = $this->getURLForIndividualTupples($data['activities'], $type);
			
			$view = $this->load->view('userActivityDetailedView',$data,true);
			$view = trim($view);
			if(!empty($view)){
				echo json_encode(array('html'=>$view, 'ajaxCallCounterRecent'=>$data['ajaxCallCounterRecent'], 'activityCount'=>count($data['activities'])));
			} 
			else {
				echo 'NoData';
			}
			
		}

		public function _getUserDeomesticInterestDetails($userId = 0){
			if(empty($userId)){
				return array();
			}

			$userDetails = array();
			$usermodel = $this->load->model('user/usermodel');
	        $userObj     = $usermodel->getUserById($userId, false);

	        if(is_object($userObj)){
	        	$userPreference = $userObj->getPreference();
	        }
	        if(is_object($userPreference)){
		        $flow = $userPreference->getFlow();
	        }

	        if(is_object($userObj)){
	        	$userinterest = $userObj->getUserInterest();
	        }
	        
	        if (is_object($userinterest)) {
	            $stream = 0;
	            $baseCourses     = array();
	            $subStreamSpec = array();
	            $educationMode   = array();

	            foreach ($userinterest as $interest) {

	                $interestStatus = $interest->getStatus();
	                if ($interestStatus != 'history') {
	                    $stream = $interest->getStreamId();

	                    $substreamId = $interest->getSubStreamId();

	                    if (empty($substreamId)) {
	                        $substreamId = 'ungrouped';
	                    }

	                    $subStreamSpec[$substreamId] = array();
	                    $userCourseSpec = $interest->getUserCourseSpecialization();
	                    $specializations = array();
	                    foreach ($userCourseSpec as $courseSpec) {
	                        /*Getting specializations */
	                        $specializations[$courseSpec->getSpecializationId()] = $courseSpec->getSpecializationId();

	                        /*Getting base course */
	                        $tempBC = $courseSpec->getBaseCourseId();
	                        if(!empty($tempBC)){
		                        $baseCourses[$courseSpec->getBaseCourseId()] = $courseSpec->getBaseCourseId();
	                        }
	                    }
	                    if(!empty($specializations)){
	                        // $subStreamSpec[$substreamId] = array_keys($specializations);
	                        foreach ($specializations as $key => $value) {
	                        	if(!empty($key)) {
	                            	$subStreamSpec[$substreamId][] = (string)$key;
	                        	}
	                        }
	                    }

	                    $attributeObj = $interest->getUserAttributes();
	                    foreach ($attributeObj as $attrObj) {
	                        $educationMode[$attrObj->getAttributeValue()] = $attrObj->getAttributeValue();
	                    }
	                }

	            }

	            $stringifyBaseCourse = array();
	            foreach($baseCourses as $key=>$value){
	            	if(!empty($key)) {
	                	$stringifyBaseCourse[] = (string)$key;
	            	}
	            }

	            $stringifyEducationMode = array();
	            foreach($educationMode as $key=>$value){
	            	if(!empty($key)) {
	                	$stringifyEducationMode[] = (string)$key;
	            	}
	            }

	            $this->load->builder('listingBase/ListingBaseBuilder');
		        $listingBase = new \ListingBaseBuilder();
		        $HierarchyRepository = $listingBase->getHierarchyRepository();

		        if(empty($userDetails['flow'])){
		        	$userDetails['flow'] = 'specialization';
		        }
		        
		        if($stream != '' && $stream > 0) {
	        		$streamObj = $HierarchyRepository->findStream($stream);
					$streamData = $streamObj->getObjectAsArray();		        
		        	$userDetails['stream'] = $streamData['name'];
		    	}

   				if(!empty($stringifyBaseCourse) && $stringifyBaseCourse[0] > 0) {
   					$BaseCourseRepository = $listingBase->getBaseCourseRepository();
			        $coursesObj = $BaseCourseRepository->findMultiple($stringifyBaseCourse);
			        foreach ($coursesObj as $key => $value) {
			            $value = $value->getObjectAsArray();      
			       		$userDetails['baseCourses'][$value['base_course_id']] = $value['name'];
			       		if($value['is_dummy'] == 1){
			       			unset($baseCourses[$value['base_course_id']]);
			       		}	
			        }			        
			    }    

	            $userDetails['flow']            = $flow;
	            $userDetails['subStreamSpec']   = $subStreamSpec;

	            if(!empty($stringifyEducationMode)) {
	            	$educationMode = array();
	            	$masterEducationType  = new \registration\libraries\FieldValueSources\EducationType;
		            $masterEducationType = $masterEducationType->getValues();
		            
		            foreach($stringifyEducationMode as $key=>$modeId){
		            	if(!empty($masterEducationType[$modeId])){
		            		$educationMode[$modeId]['name'] = $masterEducationType[$modeId]['name'];
		            	}else{
		            		$educationMode[21]['name'] = $masterEducationType[21]['name'];
		            		$educationMode[21]['children'][$modeId] = $masterEducationType[21]['children'][$modeId];
		            	}
		            }
	        	}
	        	
	        	if(count($masterEducationType[21]['children']) == count($educationMode[21]['children'])){
	        		$educationMode[21]['children'] = array();
	        		$educationMode[21]['children'][] = 'All';
	        	}
	        	$userDetails['educationMode'] = $educationMode;
	        }
	        
        	$subStreamSpecializations  = new \registration\libraries\FieldValueSources\SubStreamSpecializations;
	        if($userDetails['flow'] == 'specialization'){
	        	$fieldQuery = array('streamIds'=>array($stream));
	        }else{
	        	$fieldQuery = array('streamIds'=>array($stream), 'baseCourseIds'=>$baseCourses);
	        }
            $subStreamSpecializations = $subStreamSpecializations->getValues($fieldQuery);
            $substreams = $subStreamSpecializations[$stream]['substreams'];

	        $substreamSpecData = array();
	        foreach($userDetails['subStreamSpec'] as $substream=>$value){
	        	if(!empty($substreams[$substream]['name'])) {
	        		$substreamSpecData[$substream]['name'] = $substreams[$substream]['name'];
	        	}
	        	if(count($substreams[$substream]['specializations']) && count($substreams[$substream]['specializations']) == count($value)){
	        		$substreamSpecData[$substream]['specializations'] = array();
	        		$substreamSpecData[$substream]['specializations'][0] = 'All Specializations';
	        	}else{
	        		if(!empty($value)) {
		        		foreach($value as $key=>$specialization){
		        			$substreamSpecData[$substream]['specializations'][$specialization] = $substreams[$substream]['specializations'][$specialization]['name'];
		        		}
	        		}
	        	}
	        }

	        if(!empty($userDetails['subStreamSpec']['ungrouped'])){
	        	
	        	unset($substreamSpecData['ungrouped']);
	        	$specializations = $subStreamSpecializations[$stream]['specializations'];
	        	foreach ($userDetails['subStreamSpec']['ungrouped'] as $key => $value) {
	        		$substreamSpecData['ungrouped'][$value] = $specializations[$value]['name'];
	        	}
	        }
            
	        $userDetails['subStreamSpec'] = $substreamSpecData;
	        
	        return $userDetails;

		}

		function storeUnsubscribeCategory(){
			$unsubscribeStatus = $this->input->post('unsubscribeStatus');			
			$unsubscribeCatId  = $this->input->post('unsubscribeCatId');	
			$userId            = $this->validate[0]['userid'];
			$this->load->library('UserProfileLib');
			$this->userprofilelib->userUnsubscribeMapping($userId,$unsubscribeStatus,$unsubscribeCatId);
			exit;
			
		}
	
		public function testExam($userId){
			$this->load->library('UserProfileLib');
			$profile = array();
			$lastInsertId = $this->userprofilelib->saveExamProfileData($userId, $profile);
			return $lastInsertId;
		}

	}

?>
