<?php
class UserCommonLib {

    private $CI;
    private $validationLibObj;

    function __construct() {
        $this->CI = &get_instance();
        $this->CI->load->library('common_api/APIValidationLib');
        $this->validationLibObj = new APIValidationLib();
        $this->educationOrderOfList = array("PHD","PG","UG","12","10");
    }
        
     function sendForgotPasswordNewEmail($response, $email){
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: <info@shiksha.com>' . "\r\n";
                
        $this->CI->load->library('Alerts_client');
        $alertClient = new Alerts_client();
        $subject = "Shiksha password reset";
        $requested_page = SHIKSHA_HOME."/shiksha/index/";
        $id = base64_encode($response[0]['id'].'||'.$requested_page.'||'.$email);
        $resetlink = SHIKSHA_HOME . '/user/Userregistration/ForgotpasswordNew/'.$id;
                
        $data = array();
        $data['usernameemail'] = $email;
        $data['resetlink'] = $resetlink;
        $content = $this->CI->load->view('user/ForgotPasswordNewEmail',$data,true);

        $val=mail($email,$subject,$content,$headers);             //to send mail immediately

        if($val=='TRUE'){
              $response=$alertClient->externalQueueAdd("12",ADMIN_EMAIL,$email,$subject,$content,$contentType="html",$sendTime="0000-00-00 00:00:00",$attachment='n',$attachmentArray=array(),$ccEmail=null,$bccEmail=null,$fromUserName="Shiksha.com",$isSent="sent");
        } else {
              $response=$alertClient->externalQueueAdd("12",ADMIN_EMAIL,$email,$subject,$content,$contentType="html",$sendTime="0000-00-00 00:00:00",$attachment='n',$attachmentArray=array(),$ccEmail=null,$bccEmail=null,$fromUserName="Shiksha.com",$isSent="unsent");
        }
        return $response;                
     }        

    function showIntermediatePageOrNot($userId){
	$this->usermodel = $this->CI->load->model('user/usermodel');
	$showIntermediatePage = $this->usermodel->showIntermediatePageOrNot($userId);
	if(!empty($showIntermediatePage)){
	    return false;		
	}else{
	    $this->usermodel->insertAppUserProfileData($userId,'consumer');
	}
	return true;
    }

    function isUserHasProfile($userId){
        $this->usermodel = $this->CI->load->model('user/usermodel');
        return $this->usermodel->isUserHasProfile($userId);
    }    

    function getUserDetailById($userId){
        $usermodel = $this->CI->load->model('user/usermodel');
        return $usermodel->getUserDetailById($userId);
    }

     function registerUser($userData, $response){

                //Step 3: Fetch the Data from DB + Logic
                $this->CI->load->model('UserPointSystemModel');
                $this->UserPointSystemModel = new UserPointSystemModel();
                $this->usermodel = $this->CI->load->model('user/usermodel');
                $userarray['email']       = $userData['email'];
                $userarray['firstname']   = htmlentities(addslashes(trim($userData['firstName'])));
                $userarray['lastname']    = htmlentities(addslashes(trim($userData['lastName'])));
                $userarray['displayname'] = htmlentities(addslashes(trim($userData['firstName'])));
                $userarray['displayname'] = sanitizeString($userarray['displayname']);
                $password                 = $userData['password'];

                if($password == ""){
                    $password = 'shiksha@'.rand(1,1000000);
                }
                $userarray['password']        = $password;
                $userarray['ePassword']       = sha256($userarray['password']);
                $userarray['quicksignupFlag'] = 'veryshortregistration';
                $userarray['usergroup']       = 'veryshortregistration';

		//Set the Registration Source info as AndroidShiksha-V1
		$sourceName		      = isset($_SERVER['HTTP_SOURCE'])?$_SERVER['HTTP_SOURCE']:'AndroidShiksha';
		$versionNo		      = isset($_SERVER['HTTP_APPVERSIONCODE'])?$_SERVER['HTTP_APPVERSIONCODE']:'1';
		$registrationSource	      = $sourceName."-V".$versionNo;
                $userarray['sourceurl']       = $registrationSource;
                $userarray['sourcename']      = $registrationSource;
		$userarray['tracking_keyid']  = '599';

                $addResult = $this->UserPointSystemModel->doQuickRegistration($userarray,$secCode=0,$secCodeIndex="default",$secCodeChecked=true);
                if($addResult > 0) {
                        $userData = $this->usermodel->getUserBasicInfoById($addResult);
                        if(!isset($userData['userid'])){ //In case, details of the user could not be retreived due to Server lag or something
                                $responseArr = array(
                                                      'userId'      => $addResult,
                                                      'firstName'   => $userarray['firstname'],
                                                      'lastName'    => $userarray['lastname'],
                                                      'picUrl'   => '/public/images/photoNotAvailable.gif',
                                                      'displayName' => $userarray['displayname'],
                                                      'email'       => $userData['email'],
						      						  'showIntermediate' => SHOW_INTERMEDIATE_PAGE
                                                );
                        }
                        else{
                                $responseArr = array(
                                                          'userId'      => $userData['userid'],
                                                          'firstName'   => $userData['firstname'],
                                                          'lastName'    => $userData['lastname'],
                                                          'picUrl'   => $userData['avtarimageurl'],
                                                          'displayName' => $userData['displayname'],
                                                          'email'       => $userData['email'],
							  							  'showIntermediate' => SHOW_INTERMEDIATE_PAGE
                                                    );
                        }

			//Default entry in DB for user profile
			if(SHOW_INTERMEDIATE_PAGE == TRUE){    
			    $this->usermodel->insertAppUserProfileData($addResult,'consumer' );
			}
			
			//Track User Login
			$this->usermodel->trackUserLogin($responseArr['userId']);

                }
                else if($addResult == "Blank" || $addResult == "email" || $addResult == "displayname" || $addResult == "both" || $addResult == "code"){ //In case of some error in user registration
						$response->setStatusCode(STATUS_CODE_FAILURE);
                        if($addResult == "email" || $addResult == "both"){
                                $response->setFieldError(array(array("field" => "email", "errorMessage" => "Email is already registered.")));
                                $response->setResponseMsg("Unsuccessful");
                        }
                        else if($addResult == "displayname"){
                                $response->setFieldError(array(array("field" => "lastName", "errorMessage" => "You cannot use this name.")));
                                $response->setResponseMsg("Unsuccessful");
                        }
                        else if($addResult == "Blank"){
                                $response->setResponseMsg("Something went wrong. Please try again.");
                        }
                        $response->output();
                        return false;
                }

        // set authchecksum
        $response->setAuthChecksum($responseArr['userId'], $responseArr['email'], $responseArr['firstName'], $responseArr['lastName'], $responseArr['displayName']);

        // set HTTP code as created(201)
        $response->setHttpCode(Response::CREATED);

        // set response body
        $response->setBody($responseArr);
        return true;
     }

     function getFBUserDetailsByAccessToken($fbAccessToken){
            // $fbApiUrl = "https://graph.facebook.com/me?fields=id,email,first_name,last_name,picture&access_token=".$fbAccessToken;
            // $response = file_get_contents($fbApiUrl);
            // $response = json_decode($response, true);
            
            $this->CI->load->library("facebook");
            $FB = new Facebook();
            try{
                $User = $FB->api("/me?locale=en_US&fields=id,name,email",array("access_token" => $fbAccessToken));
            }catch(FacebookApiException $e){
                return $e->getResult();
            }

            return $User;
     }

     function updateUserAvtarImage($userId, $newImage, $oldImage = ""){

        // strpos($oldImage, 'fbcdn') == false
        $defaultAvatarImages = array(   "/public/images/girlav1.gif",
                                        "/public/images/girlav2.gif",
                                        "/public/images/girlav4.gif",
                                        "/public/images/girlav5.gif",
                                        "/public/images/girlav6.gif",
                                        "/public/images/men3.gif",
                                        "/public/images/men6.gif",
                                        "/public/images/men1.gif",
                                        "/public/images/men4.gif",
                                        "/public/images/men5.gif");

        if(!empty($newImage) && (empty($oldImage) || (strpos($oldImage, "photoNotAvailable") !== false) || (strpos($oldImage, "fbcdn") !== false) || in_array($oldImage, $defaultAvatarImages))){

            // download user's fb pic to shiksha server and make its variants
            $userPic = $this->uploadUserFBProfilePic($userId, $newImage);

            $userapimodel = $this->CI->load->model('userapimodel');
            $userapimodel->updateUserAvatarUrl($userId, $userPic);
            return $userPic;
        }
        return $oldImage;

     }

     /**
      * Method to get the User Details for User Profile page
      * @author Romil Goel <romil.goel@shiksha.com>
      * @date   2015-10-13
      * @param  [type]     $userId [user id]
      * @return [type]             [array cantaining user's details]
      */
    function getUserDetails($userId, $loggedInUserId = null, $viewType = ''){

        // load resources and initiate instances
        $this->CI->load->helper('image');
        $usermodel        = $this->CI->load->model("user/usermodel");
        $anamodel         = $this->CI->load->model("messageBoard/anamodel");
        $userprofilemodel = $this->CI->load->model('userprofilemodel');
        $tagsmodel        = $this->CI->load->model("v1/tagsmodel");
        $this->CI->load->library("common_api/APICommonCacheLib");
        $apiCommonCacheLib = new APICommonCacheLib();
        
        // get ana point of the user
        $anaUserDetails = $anamodel->getAnAUsersDetails(array($userId));
        $this->privateFields  = $userprofilemodel->getUsersPrivacySettings($userId);
        $anaUserDetails = $anaUserDetails[$userId];

        $data     = array();
        $userData = $usermodel->getUserById($userId);

        if(empty($userData)){
            return $data;
        }

        $data['id']            = $userData->getId();
        $data['firstName']     = $userData->getFirstName();
        $data['lastName']      = $userData->getLastName();
        $data['userFlags']     = $userData->getFlags();
        $data['privateFields'] = $this->privateFields;

        // fetch user's active tags
        $activeTagIds = $apiCommonCacheLib->getUserActiveTags($userId);
        if($activeTagIds){
            $activeTagIds = array_slice($activeTagIds, 0, 10);
            $activeTagsData = $tagsmodel->getTagsDetailsById($activeTagIds);
            foreach ($activeTagsData as $value) {
                $data['activeTags']['tags'][] = array("id" => $value['id'], "tag" => $value['tags']);
            }
            if($data['activeTags']['tags'])
                $data['activeTags']['text'] = "Most active user on";
        }else{
            $data['activeTags'] = null;
        }
        
        if($loggedInUserId && ($loggedInUserId == $userId) && $viewType != 'public'){
            $data['isSelf']   = true;
        }else{
            $data['isSelf']   = false;
            $data['isUserFollowed']   = $anamodel->checkIfUserHasFollowedAnEntity($userId, 'user', $loggedInUserId);
        }
        $userAdditionalInfo = $userData->getUserAdditionalInfo();

        if($userAdditionalInfo){
            $data['aboutme'] = html_entity_decode($userAdditionalInfo->getAboutMe());
        }
        else{
            $data['aboutme'] = "";
        }
        $data['points'] = $anaUserDetails['userpointvaluebymodule'] ? $anaUserDetails['userpointvaluebymodule'] : 0;
        $data['level']  = $anaUserDetails['levelName'] ? $anaUserDetails['levelName'] : 'Beginner-Level 1';
        $data['photo']  = $userData->getAvatarImageURL();
        $data['photo']  = checkUserProfileImage($data['photo']);
        
        // get employement details only in following cases
        // 1. Logged-in user details are being fetched
        // 2. Work Experience details are not private
        $data['currentEmployment'] = null;
        $workExperience = $userData->getUserWorkExp();
        if($workExperience){
            foreach ($workExperience as $key => $workExperienceRow) {

                if(in_array("EmployerworkExp".($key+1), $this->privateFields) && !$data['isSelf'])
                continue;

                if($workExperienceRow->getCurrentJob() == 'YES'){
                    $currentDesignation = $workExperienceRow->getDesignation();
                    $currentEmployer = $workExperienceRow->getEmployer();
                    $data['currentEmployment'] = ($currentDesignation && $currentEmployer) ? $currentDesignation." at ".$currentEmployer : null;
                }
            }
        }

        // get last education details only in following cases
        // 1. Logged-in user details are being fetched
        // 2. Education details are not private
        $data['lastEducation'] = null;
        $mapArr = array('UG'=> "bachelors",
                            'PG' => 'masters',
                            '10' => 'xth',
                            '12' => 'xiith',
                            'PHD' => 'phd');
        if(!in_array("education",$privateFields) || $data['isSelf']){
            $educationDetails = $userData->getEducation();
            $educationList = array();
            $lastEducationStr = "";
            foreach ($educationDetails as $value) {
                $level           = $value->getLevel();
                $instituteName   = $value->getInstituteName();
                $degreeName      = $value->getName();

                if($level && $instituteName && in_array($level, array("10","12")))
                    $lastEducationStr  = $level."th from ".$instituteName;
                else if($degreeName && $instituteName && in_array($level, array("UG","PG","PHD")))
                    $lastEducationStr = $degreeName." from ".$instituteName;

                $educationList[$level][] = $lastEducationStr;
            }

            foreach ($this->educationOrderOfList as $value) {

                if(in_array($mapArr[$value], $this->privateFields) && !$data['isSelf'] ){
                        continue;
                }

                if($educationList[$value]){
                    $data['lastEducation'] = $educationList[$value][0];
                    break;
                }
            }
        }

        // get social profile details
        $data['socialLinks'] = null;
        $data['levelId'] = $anaUserDetails['levelId'] ? $anaUserDetails['levelId'] : 1;
        if($anaUserDetails && $anaUserDetails['levelId'] >= 11){
            $socialProfile = $userData->getSocialProfile();
            $data['socialLinks'] = $this->_fetchSocialLinks($socialProfile);
        }

        return $data;
    }

    /**
      * Method to get About be Tab details of the User on User profile page
      * @author Romil Goel <romil.goel@shiksha.com>
      * @date   2015-10-14
      * @param  [type]     $userId [user id]
      * @return [type]             [array cantaining user's details]
      */
    function getUserSectionDetails($userId, $loggedInUserId, $userObj, $sections = array("personal", "education", "work", "expertise", "eduPref"), $viewType = ''){

        // load resources and initiate instances
        $this->CI->load->helper('image');
        $usermodel        = $this->CI->load->model("user/usermodel");
        $anamodel         = $this->CI->load->model("messageBoard/anamodel");
        $userprofilemodel = $this->CI->load->model('userprofilemodel');
        $tagsmodel        = $this->CI->load->model('v1/tagsmodel');

        $data               = array();
        $personalInfo       = array();
        $educationalInfo    = array();
        $sectionWiseFollowedTags = array();

        if(!$this->privateFields)
            $this->privateFields  = $userprofilemodel->getUsersPrivacySettings($userId);

        $data['privateFields'] = $this->privateFields;

        if($loggedInUserId && ($userId == $loggedInUserId) && $viewType != 'public'){
            $fetchPrivateDetailsFlag = true;
        }
        else{
            $fetchPrivateDetailsFlag = false;
        }

        if(empty($userObj))
            $userData           = $usermodel->getUserById($userId);

        // get ana point of the user
        $anaUserDetails = $anamodel->getAnAUsersDetails(array($userId));
        $anaUserDetails = $anaUserDetails[$userId];

        if(in_array("expertise", $sections) || in_array("eduPref", $sections)){
            $sectionWiseFollowedTags = $userprofilemodel->getUsersSectionwiseFollowedTags($userId);

            $sectionTags = array();
            foreach ($sectionWiseFollowedTags as $value) {
                $sectionTags = array_merge($sectionTags, $value);
            }

            if($sectionTags){
                $tagsInfo = $tagsmodel->getTagsDetailsById($sectionTags);
                foreach ($tagsInfo as $key=>$value) {
                    $tagsInfo[$key]['tagId'] = $tagsInfo[$key]['id'];
                    $tagsInfo[$key]['tagName'] = $tagsInfo[$key]['tags'];
                    unset($tagsInfo[$key]['id']);
                    unset($tagsInfo[$key]['tags']);
                }
            }

            foreach ($sectionWiseFollowedTags as $sectionName => $value) {
                foreach ($value as $index => $tagId) {
                    unset($sectionWiseFollowedTags[$sectionName][$index]);
                    $sectionWiseFollowedTags[$sectionName][$index] = $tagsInfo[$tagId];
                }
            }
        }

        // get personal information
        if(in_array("personal", $sections)){
            $personalInfo = $this->_fetchUserPersonalDetails($userData, $anaUserDetails, $fetchPrivateDetailsFlag, $this->privateFields, $viewType);
            $data['personalInfo'] = $personalInfo ? $personalInfo : null;
        }

        // get educational information
        if(in_array("education", $sections)){
            $educationalInfo = $this->_fetchUserEducationalDetails($userData, $fetchPrivateDetailsFlag, $this->privateFields);
            $data['educationalInfo'] = $educationalInfo ? $educationalInfo : null;
        }

        // get work experience information
        if(in_array("work", $sections)){
            $workExperienceInfo = $this->_fetchUserWorkExperienceDetails($userData, $fetchPrivateDetailsFlag, $this->privateFields);
            $data['workExperienceInfo'] = $workExperienceInfo ? $workExperienceInfo : null;
        }

        // get expertise information
        if(in_array("expertise", $sections)){
            $expertiseInfo = $this->_fetchUserExpertiseDetails($sectionWiseFollowedTags, $fetchPrivateDetailsFlag, $this->privateFields);
            $data['expertiseInfo'] = $expertiseInfo ? $expertiseInfo : null;
        }

        // get educational preference information
        if(in_array("eduPref", $sections)){
            $educationalPref = $this->_fetchUserEducationalPrefDetails($sectionWiseFollowedTags, $fetchPrivateDetailsFlag, $this->privateFields);
            $data['educationalPref'] = $educationalPref ? $educationalPref : null;
        }

        return $data;
    }

    private function _fetchUserPersonalDetails($userData, $anaUserDetails, $fetchPrivateDetailsFlag, $privateFields, $viewType = ''){
        $personalInfo = array();
        if(empty($userData)){
            return $personalInfo;
        }

        $isdCode = $userData->getISDCode()."-".$userData->getCountry();
        // get personal information
        if($fetchPrivateDetailsFlag){

            if($viewType != 'public'){
                $personalInfo['isdCode'] = $isdCode;
                $personalInfo['mobile'] = $userData->getMobile();
                $personalInfo['email']  = $userData->getEmail();
                $personalInfo['dob'] = $userData->getDateOfBirth();

                if($personalInfo['dob']->format("Y") > 0){           
                    $personalInfo['dob'] = $personalInfo['dob']->format("d F Y");
                }else{
                    $personalInfo['dob'] = null;
                }
            }
        }

        $personalInfo['showCountry'] = false;
        if($isdCode != "91-2"){
            $personalInfo['showCountry'] = true;
        }

        if(!in_array("location", $privateFields) || $fetchPrivateDetailsFlag){
            $personalInfo['cityId'] = $userData->getCity();
            $objn = new \registration\libraries\FieldValueSources\IsdCode;
            $isdCodesArray = $objn->getValues();
            $personalInfo['countryName'] = "";

            if(array_key_exists($isdCode, $isdCodesArray)){

                list($c_name) = explode("(",$isdCodesArray[$isdCode]);
                $personalInfo['countryName'] = trim($c_name);
            }
            
        }
            
        
        if(!is_numeric($userData->getCity()) || $userData->getCity() < 0)
            $personalInfo['cityId'] = null;

        if($personalInfo['cityId']){
            $this->CI->load->builder('LocationBuilder','location');
            $locationBuilder    = new LocationBuilder();
            $locationRepository = $locationBuilder->getLocationRepository();
            $cityObj            = $locationRepository->findCity($personalInfo['cityId']);

            if($cityObj && $cityObj->getId()){
                $personalInfo['cityName']  = $cityObj->getName();
                $personalInfo['cityName']  = $cityObj->getName();
                $personalInfo['stateId']   = $cityObj->getStateId();
                $stateObj          = $locationRepository->findState($personalInfo['stateId']);
                $personalInfo['stateName'] = $stateObj->getName();
            }
        }

        $userAdditionalData = $userData->getUserAdditionalInfo();
        $personalInfo['aboutMe'] = null;
        if($userAdditionalData){
            $personalInfo['aboutMe'] = html_entity_decode($userAdditionalData->getAboutMe());
            
            if(strlen($personalInfo['aboutMe']) > 45){
                $personalInfo['aboutMe'] = substr($personalInfo['aboutMe'], 0,45);
            }
        }
            

        if(!in_array("shortbio", $privateFields) || $fetchPrivateDetailsFlag){
            if($userAdditionalData){
                $personalInfo['shortbio'] = html_entity_decode($userAdditionalData->getBio());
            }
            else{
                $personalInfo['shortbio'] = null;
            }
        }

        // get social profile page
        $personalInfo['socialLinks'] = null;
        $personalInfo['levelId'] = $anaUserDetails['levelId'] ? $anaUserDetails['levelId'] : 1;
        if($anaUserDetails && $anaUserDetails['levelId'] >= 11){
            $socialProfile = $userData->getSocialProfile();
            $personalInfo['socialLinks'] = $this->_fetchSocialLinks($socialProfile);
        }
        $personalInfo['firstName'] = $userData->getFirstName()?$userData->getFirstName():"";
        $personalInfo['lastName'] = $userData->getLastName()?$userData->getLastName():"";

        return $personalInfo;
    }

    private function _fetchUserEducationalDetails($userData, $fetchPrivateDetailsFlag, $privateFields){

        $educationalInfo = array();
        $tempInfo = array();

        if(empty($userData)){
            return $educationalInfo;
        }

        $educationDetails = $userData->getEducation();

        $i = 0;
        
        $lastEducationStr = "";
        foreach ($educationDetails as $value) {
            $level                = $value->getLevel();
            $instituteName        = $value->getInstituteName();
            $degreeName           = $value->getName();
            $courseCompletionDate = $value->getCourseCompletionDate();
            $specialization       = $value->getSpecialization();

            if($courseCompletionDate){
                $courseCompletionYear = $courseCompletionDate->format('Y');
            }


           $mapArr = array('UG'=> "bachelors",
                            'PG' => 'masters',
                            '10' => 'xth',
                            '12' => 'xiith',
                            'PHD' => 'phd');

           $reverseMapArr = array();
           foreach ($mapArr as $key => $value) {
               $reverseMapArr[$value] = $key;
           }

            if(in_array($level, $this->educationOrderOfList)){
                $tempInfo[$level][$i]['level']                = $level;
                $tempInfo[$level][$i]['instituteName']        = $instituteName;
                $tempInfo[$level][$i]['degreeName']           = $degreeName;
                $tempInfo[$level][$i]['specialization']       = $specialization;
                $tempInfo[$level][$i]['courseCompletionYear'] = ($courseCompletionYear <= 0) ? null : $courseCompletionYear;

                if($level && $mapArr[$level]){
                    $tempInfo[$level][$i]['levelText'] = $mapArr[$level];
                }else{
                    $tempInfo[$level][$i]['levelText'] = null;
                }

                $text1 = array();
                $text2 = array();
                $className = array(10 => "Xth", 12 => "XIIth");
                if(in_array($degreeName, array('10', '12')))
                    $text1[] = "<b>".ucfirst($className[$degreeName])."</b>";
                else
                    $text1[] = "<b>".ucfirst($degreeName)."</b>";

                if($instituteName){
                    $text1[] = $instituteName;
                }

                if($specialization){
                    $text2[] =ucfirst($specialization);
                }
                
                if($tempInfo[$level][$i]['courseCompletionYear']){
                    $text2[] = $tempInfo[$level][$i]['courseCompletionYear'];
                }

                if($level && $instituteName && in_array($level, array("10","12")))
                    $lastEducationStr  = $level."th from ".$instituteName;
                else if($degreeName && $instituteName && in_array($level, array("UG","PG","PHD")))
                    $lastEducationStr = $degreeName." from ".$instituteName;

                $tempInfo[$level][$i]['text'] = "";
                $tempInfo[$level][$i]['eduText'][0]          = implode(" at ", $text1);
                $tempInfo[$level][$i]['eduText'][1]          = implode(", ", $text2);
                $tempInfo[$level][$i]['eduText'][0]          = strip_tags($tempInfo[$level][$i]['eduText'][0]) ? $tempInfo[$level][$i]['eduText'][0] : "<b>".ucfirst($mapArr[$level])."</b>";
                $tempInfo[$level][$i]['topText'] = $lastEducationStr;

                $i++;
            }
        }

        foreach ($this->educationOrderOfList as $value) {
            
            if(in_array($mapArr[$value], $privateFields) && !$fetchPrivateDetailsFlag ){
                    continue;
            }

            if($tempInfo[$value]){
                $educationalInfo = array_merge($educationalInfo, $tempInfo[$value]);
            }
        }

        return $educationalInfo;
    }

    private function _fetchUserWorkExperienceDetails($userData, $fetchPrivateDetailsFlag, $privateFields){

        $data = array();

        $workExperienceLib = new \registration\libraries\FieldValueSources\WorkExperience;
        $workExpPicklistData = $workExperienceLib->getValues();

        $workExpPicklistDataFormatted = array();

        $count = 0;
        foreach ($workExpPicklistData as $key => $value) {
            $workExpPicklistDataFormatted[$count]['key']  = $key;            
            $workExpPicklistDataFormatted[$count]['value']  = $value;

            $count++;
        }


        $data['workExpPicklistData'] = $workExpPicklistDataFormatted;

        if(empty($userData))
            return $data;
        $totExp = $userData->getExperience();
        if($totExp !== null){
            $data['totalExperience'] = array(
                                            'key' => $totExp,
                                            'value' => $workExpPicklistData[$totExp]
                                        );
        }else {
            $data['totalExperience'] = $totExp;    
        }

        if(in_array("Experience", $privateFields) && !$fetchPrivateDetailsFlag)
            $data['totalExperience'] = null;
        
        $workExpData = $userData->getUserWorkExp();

        $i = 0;
        if($workExpData){
            foreach ($workExpData as $index=>$value) {

                if(in_array("EmployerworkExp".($index+1), $privateFields) && !$fetchPrivateDetailsFlag)
                    continue;

                $data['workExp'][$i]['isPrivate'] = "NO";
                if(in_array("EmployerworkExp".($index+1), $privateFields))
                    $data['workExp'][$i]['isPrivate'] = "YES";

                $text1 = "";
                $text2 = array();

                $data['workExp'][$i]['employer'] = $value->getEmployer();
                $data['workExp'][$i]['designation'] = $value->getDesignation();
                $data['workExp'][$i]['department'] = $value->getDepartment();
                $data['workExp'][$i]['startDate'] = $value->getStartDate() ? $value->getStartDate()->format("Y-m-d") : null;
                $data['workExp'][$i]['endDate'] = $value->getEndDate() ? $value->getEndDate()->format("Y-m-d") : null;
                $data['workExp'][$i]['isCurrentJob'] = $value->getCurrentJob();

                if($data['workExp'][$i]['employer'])
                    $text1 = "<b>".$data['workExp'][$i]['employer']."</b>";

                if($data['workExp'][$i]['designation'])
                    $text2[] = $data['workExp'][$i]['designation'];
                if($data['workExp'][$i]['department'])
                    $text2[] = $data['workExp'][$i]['department'];

                $data['workExp'][$i]['workText'][0] = $text1;
                $data['workExp'][$i]['workText'][1] = implode(", ", $text2);

                if($data['workExp'][$i]['isCurrentJob'] == 'YES'){
                    $data['currentJob'] = null;
                    if($data['workExp'][$i]['designation'])
                        $data['currentJob'][] = $data['workExp'][$i]['designation'];
                    if($data['workExp'][$i]['department'])
                        $data['currentJob'][] = $data['workExp'][$i]['department'];
                    if($data['workExp'][$i]['employer'])
                        $data['currentJob'][] = $data['workExp'][$i]['employer'];
                    
                    $data['currentJob'] = implode(", ", $data['currentJob']);
                    
                    $data['currentJobText'][0] = $data['workExp'][$i]['workText'][0];
                    $data['currentJobText'][1] = $data['workExp'][$i]['workText'][1];
                    

                        $currentDesignation = $data['workExp'][$i]['designation'];
                        $currentEmployer = $data['workExp'][$i]['employer'];
                        $data['topText'] = ($currentDesignation && $currentEmployer) ? $currentDesignation." at ".$currentEmployer : null;
                }
                $i++;
            }
        }

        return $data;
    }

    private function _fetchUserExpertiseDetails($sectionWiseFollowedTags, $fetchPrivateDetailsFlag, $privateFields){

        $data = array();

        if(empty($sectionWiseFollowedTags) || (in_array("expertise", $privateFields) && !$fetchPrivateDetailsFlag))
            return $data;
        $data['stream'] = array_values(array_filter($sectionWiseFollowedTags['stream']));
        //$data['stream'] = array_values(array_filter($sectionWiseFollowedTags['stream']));
        //$data['country'] = array_filter($sectionWiseFollowedTags['country']);
        $data['country'] = array_values(array_filter($sectionWiseFollowedTags['country']));

        return $data;
    }

    private function _fetchUserEducationalPrefDetails($sectionWiseFollowedTags, $fetchPrivateDetailsFlag, $privateFields){

        $data = array();

        if(empty($sectionWiseFollowedTags) || (in_array("eduPref", $privateFields) && !$fetchPrivateDetailsFlag))
            return $data;

        $data['course_level']       = array_values(array_filter($sectionWiseFollowedTags['course_level']));
        $data['stream_interest']    = array_values(array_filter($sectionWiseFollowedTags['stream_interest']));
        $data['specialization']     = array_values(array_filter($sectionWiseFollowedTags['specialization']));
        $data['degree']             = array_values(array_filter($sectionWiseFollowedTags['degree']));
        $data['countries_interest'] = array_values(array_filter($sectionWiseFollowedTags['countries_interest']));
        $data['cities_interest']    = array_values(array_filter($sectionWiseFollowedTags['cities_interest']));

        return $data;
    }

    private function _fetchSocialLinks($socialProfile){
    
        $personalInfo                 = array();
        $personalInfo['facebookLink'] = null;
        $personalInfo['linkedInLink'] = null;
        $personalInfo['twitterLink']  = null;
        $personalInfo['youtubeLink']  = null;
        $personalInfo['personalLink'] = null;
        if($socialProfile){
            $personalInfo['facebookLink'] = $socialProfile->getFacebookId();
            $personalInfo['linkedInLink'] = $socialProfile->getLinkedinId();
            $personalInfo['twitterLink']  = $socialProfile->getTwitterId();
            $personalInfo['youtubeLink']  = $socialProfile->getYoutubeId();
            $personalInfo['personalLink'] = $socialProfile->getPersonalURL();
        }        

        return $personalInfo;
    }


    function getUserActivitiesAndStats($profileUserId, $userId, $start = 0, $count = 10){
        //Get all the Stats
        $userProfileModelObj = $this->CI->load->model('userprofilemodel');
	$stats = array();
	if($start == 0){
        	$stats['stats'] = $userProfileModelObj->getUserStats($profileUserId);
	}
        
        //Get the Latest Activities upto 100
	if($start < 120){
		$stats['activities'] = $userProfileModelObj->getUserActivity($profileUserId, $userId, $start, $count);
	}

	return $stats;
    }

    function uploadUserFBProfilePic($userId, $facebookProfilePic){

        if(empty($userId) || empty($facebookProfilePic))
            return "";

        $imagename = "userImage".microtime(true).$userId.".jpg";
        $imagepath = "/tmp/".$imagename;
        
        $image = file_get_contents($facebookProfilePic);
        file_put_contents($imagepath, $image);

        $_FILES = array();
        $_FILES['myImage']['name'][]     = $imagename;
        $_FILES['myImage']['tmp_name'][] = $imagepath;
        $_FILES['myImage']['type'][]     = "image/jpg";
        $_FILES['myImage']['size'][]     = filesize($imagepath);
        
        // error_log("image ========== ".print_r($_FILES, true));
        $this->CI->load->library('Upload_client');
        $uploadClient = new Upload_client();    

        $upload = $uploadClient->uploadFile(1,'image',$_FILES,array(),$userId,"newProfilePage", 'myImage');

        if(!is_array($upload) || empty($upload[0]['imageurl'])){
            return "";
        }
        else{
            return $upload[0]['imageurl'];
        }

    }

    function sendInterFeedbackMailers($userId,$feedbackText,$usefulness,$easeOfUser,$lookAndFeel){

        $usermodel  = $this->CI->load->model("user/usermodel");
        $userObj   = $usermodel->getUserById($userId);
        if(empty($userObj) || !$userObj->getId()){
            return false;
        }

        $mailerData                  = array();
        $userData                    = array();
        $userData['userId']          = $userObj->getId();
        $userData['firstName']       = $userObj->getFirstName();
        $userData['lastName']        = $userObj->getLastName();
        $userData['email']           = $userObj->getEmail();
        $userData['mobile']          = $userObj->getMobile();
        $userData['userProfileLink'] = getSeoUrl($userData['userId'],'userprofile');;

        // 1. send thank you mailer to the user
        $mailerData = array("NameOfUser" => $userData['firstName']);
        Modules::run('systemMailer/SystemMailer/sendInternalFeedbackThankyouMailer', $userData['email'], $mailerData);

        // 2. send the details of the internal feedback to the internal team
        $mailerData                         = array();
        $mailerData                         = array("NameOfUser" => $userData['firstName']);
        $mailerData['userDetails']          = $userData;
        $mailerData['date']                 = date("d/M/Y");
        $mailerData['feedbackText']         = $feedbackText;
        $mailerData['rating']['easeOfUse']  = $easeOfUser;
        $mailerData['rating']['usefulness'] = $usefulness;
        $mailerData['rating']['looknFeel']  = $lookAndFeel;
        Modules::run('systemMailer/SystemMailer/sendInternalFeedbackToTeam', APP_FEEDBACK_EMAIL, $mailerData);
    }

    function getAnAUserData($userIds, $listOfFieldsNeeded = array("basicDetails","levelDetails","answercount","followercount","isUserFollowing","upvotescount","aboutMe"), $loggedInUserId = 0){

        $userData = array();
        $userapimodel = $this->CI->load->model('v1/userapimodel');

        foreach ($listOfFieldsNeeded as $fieldName) {
            
            switch ($fieldName) {
                case 'basicDetails':
                    $userBasicDetails = $userapimodel->getUsersBasicDetailsByIds($userIds);
                    $userData = $this->mergeListOnIndex($userData, $userBasicDetails);
                    break;

                case 'levelDetails':
                    $userLevelDetails = $userapimodel->getUsersLevelDetails($userIds);
                    $userData = $this->mergeListOnIndex($userData, $userLevelDetails);
                    break;
                
                case 'followercount':
                    $userFollowerDetails = $userapimodel->getUsersFollowerCount($userIds);
                    $userData = $this->mergeListOnIndex($userData, $userFollowerDetails);
                    break;

                case 'answercount':
                    $userAnswerDetails = $userapimodel->getUsersAnswerCount($userIds);
                    $userData = $this->mergeListOnIndex($userData, $userAnswerDetails);
                    break;

                case 'upvotescount':
                    $userUpvotesDetails = $userapimodel->getUsersUpvotesCount($userIds);
                    $userData = $this->mergeListOnIndex($userData, $userUpvotesDetails);
                    break;

                case 'isUserFollowing':
                    $userFollowDetails = $userapimodel->isUserFollowingEntity($loggedInUserId, $userIds, 'user');
                    $userData = $this->mergeListOnIndex($userData, $userFollowDetails);
                    break;

                case 'aboutMe':
                    $userAboutMeDetails = $userapimodel->getAboutMeOfUsers($userIds);
                    $userData = $this->mergeListOnIndex($userData, $userAboutMeDetails);
                    break;
                case 'designation':
                    $userDesignationDetails = $userapimodel->getDesignationOfUsers($userIds);
                    $userData = $this->mergeListOnIndex($userData, $userDesignationDetails);
                    break;
                case 'higherEducation':
                    $userHigherEducationDetails =  $userapimodel->getHigherEducationDetailsofUsers($userIds);
                    $userData = $this->mergeListOnIndex($userData, $userHigherEducationDetails);
                    break;
                case 'socialProfile':
                    $userSocialProfileDetails = $userapimodel->getSocialProfileForUsers($userIds);
                    $userData = $this->mergeListOnIndex($userData, $userSocialProfileDetails);
                    break;
                default:
                    # code...
                    break;
            }
        }
        return $userData;
    }

    function mergeListOnIndex($list1, $list2){

        foreach ($list2 as $key => $value) {
            $list1[$key] = (array)$list1[$key] + (array)$value;
        }

        return $list1;
    }

    public function sendStreamDigestMailByTagIds($userId,$tagIds){
        if(empty($userId)){
            return;
        }
        
        $universalModel = $this->CI->load->model('common/UniversalModel');
        $streamIds = $universalModel->getStreamsByTagIds($tagIds);
        $this->CI->load->library("common/jobserver/JobManagerFactory");

        global $isMobileApp;
        global $isWebAPICall;
        if($isMobileApp && !$isWebAPICall){
            $source = "mobileapp";
        }
        else{
            $source = isMobileRequest() ? 'mobile': 'desktop';
        }

        foreach ($streamIds as $streamId) {
            if($streamId == GOVERNMENT_EXAMS_STREAM){
                continue;
            }
            try {
                $jobManager = JobManagerFactory::getClientInstance();
                if ($jobManager) {
                    $jobManager->addBackgroundJob("StreamDigestMailerQueue", array('streamId' => $streamId,'userId' => $userId, 'source' => $source));
                }
            }catch (Exception $e) {
                error_log("Unable to connect to rabbit-MQ");
            }
        }
    }
}
?>
