<?php

/**
 * User library class
 */ 

/**
 * User library
 */ 
class UserLib
{
    /**
     * @var object CodeIgniter object
     */ 
    private $CI;
    
    /**
     * Constructor
     */ 
    function __construct()
    {
        $this->CI = & get_instance();
    }
    
    /**
     * Create a new user using data provided
     *
     * @param array $data
     * @return object Newly created user \user\Entities\User
     */ 
    public function createUser($data)
    {
        $this->CI->load->model('user/usermodel');
        $userModel = new UserModel;
        // $userModel->addRegistrationObserver(new \user\libraries\RegistrationObservers\EmailVerificationMailer);
        $userModel->addRegistrationObserver(new \user\libraries\RegistrationObservers\WelcomeMailer);
        $userModel->addRegistrationObserver(new \user\libraries\RegistrationObservers\MMPCustomData);
		$userModel->addRegistrationObserver(new \user\libraries\RegistrationObservers\Personalization);
		$userModel->addRegistrationObserver(new \user\libraries\RegistrationObservers\UserDataConsolidator);
		$userModel->addRegistrationObserver(new \user\libraries\RegistrationObservers\SendSMStoAllDistanceLeads);
        $userModel->addRegistrationObserver(new \user\libraries\RegistrationObservers\UserPointUpdation);
        $userModel->addRegistrationObserver(new \user\libraries\RegistrationObservers\TagsAssignment);

        if($data['isFBCall'] == 1){
            $userModel->addRegistrationObserver(new \user\libraries\RegistrationObservers\WelcomeSMS);            
        }

        if($data['isStudyAbroad'] == 'yes' || $data['isFBCall'] == 1){
            $userModel->addRegistrationObserver(new \user\libraries\RegistrationObservers\MobileVerificationSMS);    
        }
        if (count($data['exams'])){
            $data['examGroupIds'] =  $this->getExamGroupsIds($data['exams']);
        }
        $userObj = $userModel->createUser($data);

        if($data['isResponseForm'] == 'yes' && $data['isStudyAbroad'] != "yes" && $data['isFBCall'] != 1){
            $this->storeTempResponseInterestData($data,$userObj->getId());
        }

        $streamId = $this->getUserInterestStreamId($userObj);
        $this->sendStreamDigestMailForUser($userObj->getId(),$streamId);

        //$this->sendSMSToUserCriteriaWise($data,$userObj);

        return $userObj;
    }

    private function getUserInterestStreamId($userObj){
        $UserInterest = $userObj->getUserInterest();       
        if(count($UserInterest)>0) {
            foreach($UserInterest as $interest_object) {
                $streamId = $interest_object->getStreamId();
                break;
            }
        }
        return $streamId;
    }
    
    /**
     * Update user details
     *
     * @param array $data
     * @return object
     */ 
    public function updateUser($data)
    {
        $this->CI->load->model('user/usermodel');
        $userModel = new UserModel;
       
        $userModel->addRegistrationObserver(new \user\libraries\RegistrationObservers\TagsAssignment);
        if($data['isStudyAbroad'] == 'yes' || $data['isFBCall'] == 1){
            $userModel->addRegistrationObserver(new \user\libraries\RegistrationObservers\MobileVerificationSMS);
        }
         if (count($data['exams'])){
            $data['examGroupIds'] = $this->getExamGroupsIds($data['exams']);
        }

        $userObj = $userModel->update($data);
        $streamId = $this->getUserInterestStreamId($userObj);
        $this->sendStreamDigestMailForUser($userObj->getId(),$streamId);

        if(is_object($userObj) && is_object($userObj->getFlags()) && $userObj->getFlags()->getMobileVerified() != 1 && $data['isStudyAbroad'] != "yes"  && $data['isFBCall'] != 1){
            if($data['isResponseForm'] == 'yes'){
                $this->storeTempResponseInterestData($data,$userObj->getId());
            }
        }
            
        return $userObj;
    }

    public function sendStreamDigestMailForUser($userId,$streamId){
        if(empty($userId) || empty($streamId) || $streamId == GOVERNMENT_EXAMS_STREAM){
            return;
        }
        $mailerData              = array();
        $mailerData['userId']    = $userId;
        $mailerData['streamId']  = $streamId;

        global $isMobileApp;
        global $isWebAPICall;
        if($isMobileApp && !$isWebAPICall){
            $source = "mobileapp";
        }
        else{
            $source = isMobileRequest() ? 'mobile': 'desktop';
        }

        $mailerData['source'] = $source;

        /*$mailerData = array('userId' => 11, 'streamId' => 1);*/
        
        $this->CI->load->library("common/jobserver/JobManagerFactory");
        try {
            $jobManager = JobManagerFactory::getClientInstance();
            if ($jobManager) {
                $jobManager->addBackgroundJob("StreamDigestMailerQueue", $mailerData);
            }
        }catch (Exception $e) {
            error_log("Unable to connect to rabbit-MQ");
        }
    }

    public function sendSMSToUserCriteriaWise($userData,$userObj){       
        if(empty($userData)){
            return;
        }

        $userId    = $userObj->getId();
        $userFlags = $userObj->getFlags();
        $isLDBUser = $userFlags->getIsLDBUser();
       
        if($isLDBUser == 'NO'){
            return false;
        }
        
        $this->CI->load->config('registration/smsCriteriaConfig');
        $smsCriteria = $this->CI->config->item('smsCriteria');
        $this->CI->load->model('smsModel');

        foreach ($smsCriteria as $index => $criteria) {
            $cFlag = false;
            foreach ($criteria['criteria'] as $key => $data) {                
                if(is_array($data) && $userData[$key]){   
                    $result = array_intersect($data, $userData[$key]);                        
                    if(count($result) > 0){
                        $cFlag = true;
                    }else{
                        $cFlag = false;                            
                        break;
                    }                        
                }elseif($data == $userData[$key]){                                            
                    $cFlag = true;
                }else{       
                    $cFlag = false;
                    break;
                }                                    
            }

            if($cFlag && count($criteria['smsText']) > 0){                
                foreach ($criteria['smsText'] as $key => $smsText) {
                    $this->CI->smsModel->addSmsQueueRecord('',$userData['mobile'],$smsText,$userId,'0000-00-00 00:00:00',"");                    
                }
            }               
        }
    }
    
    /**
     * Login a user to site
     *
     * @param object $user \user\Entities\User
     */ 
    public function loginUser(\user\Entities\User $user)
    {
        $validate = $this->CI->checkUserValidation();
        if(!isset($validate[0]['userid'])){
            $value = $user->getEmail().'|'.$user->getePassword().'|pendingverification';
            // setcookie('user',$value,time() + 2592000 ,'/',COOKIEDOMAIN);
            setUserCookie($value,'/',COOKIEDOMAIN);
            $_COOKIE['user'] = $value;
        
            /**
			 * Track user login time
			 */ 	
			$this->CI->load->model('user/usermodel');
			$this->CI->usermodel->trackUserLogin($user->getId());
			/**
			 * Put shortList Cookie data into table
			 *
			 */
            $userId = $user->getId();
            $userPreference = $user->getPreference();
            if(is_object($userPreference)){
                $extraFlag = $userPreference->getExtraFlag();
            }
			if(!empty($userId) && $extraFlag == 'studyabroad')
			{
				$data ['userId'] = $userId;
				$data ['status'] = 'live';
				$data ['sessionId'] = sessionId ();
				$data ['pageType'] = 'abroadCategoryPage';
				$shortlistListingLib = $this->CI->load->library ( 'listing/ShortlistListingLib' );
				$shortlistListingLib->putShortListCouresFromCookieToDB ($data);
			}
        }
    }
    
    /**
     * Get ladning page URL of a user
     * Landing page URL is the URL of category page based on user's preferences
     *
     * @param integer $userId
     * @return string URL
     */ 
    public function getLandingPageURL($userId)
    {
        $userRepository = \user\Builders\UserBuilder::getUserRepository();
        $user = $userRepository->find($userId);
        $redirectURL = NULL;
        
        if($user && $user->isLDB()) {
            
            /**
             * Get User's location preferences
             */
            $locationPrefs = $user->getLocationPreferences();
            $preferredCountries = array();
            $preferredCities = array();
            $preferredStates = array();
            if($locationPrefs && count($locationPrefs) > 0) {
                foreach($locationPrefs as $locationPref) {
                    if($cityId = $locationPref->getCityId()) {
                        $preferredCities[] = $cityId;
                    }
                    else if($stateId = $locationPref->getStateId()) {
                        $preferredStates[] = $stateId;
                    }
                    if($countryId = $locationPref->getCountryId()) {
                        $preferredCountries[] = $countryId;
                    }
                }
            }
            
            $prefs = $user->getPreference();
            $desiredCourseId = $prefs->getDesiredCourse();
            
            /**
             * If user is registered for study abroad
             */ 
            if($prefs->getExtraFlag() == 'studyabroad') {
                if($desiredCourseId) {
                    
                    /**
                     * Get category Id corresponding to user's desired course
                     * This is same as field of interest filled during registration
                     */ 
                    $this->CI->load->builder('LDBCourseBuilder','LDB');
                    $LDBCourseBuilder = new LDBCourseBuilder;
                    $LDBCourseRepository = $LDBCourseBuilder->getLDBCourseRepository();
                    $LDBCourse = $LDBCourseRepository->find($desiredCourseId);
                    if($LDBCourse) {
                        $categoryId = $LDBCourse->getCategoryId();
                        if(count($preferredCountries) > 0) {
                            $redirectionData = array(
                                'isStudyAbroad' => TRUE,
                                'fieldOfInterest' => $categoryId,
                                'destinationCountry' => $preferredCountries
                            );
                            $redirector = new \registration\libraries\PostRegistrationRedirector($redirectionData);
                            $redirectURL = $redirector->getRedirectionURL();
                        }
                    }
                }
            }
            /**
             * If user is registered for tesp prep
             */ 
            else if($prefs->getExtraFlag() == 'testprep') {
                $testPrepPrefs = $prefs->getTestPrepSpecializationPreferences();
                if($testPrepPrefs && count($testPrepPrefs) > 0) {
                    $testPrepSpecialization = $testPrepPrefs[0]->getSpecializationId();
                    if($testPrepSpecialization && count($preferredCities) > 0) {
                        $redirectionData = array(
                            'isTestPrep' => TRUE,
                            'desiredCourse' => $testPrepSpecialization,
                            'preferredStudyLocalityCity' => $preferredCities
                        );
                        $redirector = new \registration\libraries\PostRegistrationRedirector($redirectionData);
                        $redirectURL = $redirector->getRedirectionURL();
                    }
                }
            }
            /**
             * If user is registered for national course
             */ 
            else {
                $residenceCity = $user->getCity();                
                if($residenceCity || count($preferredCities) > 0 || count($preferredStates) > 0) {
                    $redirectionData = array(
                        'desiredCourse' => $desiredCourseId,
                        'preferredStudyLocation' => array('cities' => $preferredCities,'states' => $preferredStates),
                        'residenceCity' => $residenceCity
                    );
                    $redirector = new \registration\libraries\PostRegistrationRedirector($redirectionData);
                    $redirectURL = $redirector->getRedirectionURL();
                }
            }
        }
        return $redirectURL;
    }
	/**
	 * Function to send welcome mail on registration
	 *
	 * @param integer $userId
	 * @param array $data
	 */
	public function sendEmailsOnRegistration($userId,$data = array(), $textPassword ='')
	{
		$this->CI->load->model('user/usermodel');
        $userModel = new UserModel;
		$user = $userModel->getUserById($userId,true);
        if(!is_object($user) || $user == FALSE) {
            // error_log("xxxxxxxxxxxxxxxxxxxxx== 1 ===".var_dump($user));
            $user = $userModel->getUserById($userId, true);
            // error_log("xxxxxxxxxxxxxxxxxxxxx== 2 ===".var_dump($user));
        }

        if($textPassword != ''){
            $user->setTextPassword($textPassword);
        }

		$welcomeMailer = new \user\libraries\RegistrationObservers\WelcomeMailer;
		// $emailVerificationMailer = new \user\libraries\RegistrationObservers\EmailVerificationMailer;
		
		$welcomeMailer->update($user,$data);
		// $emailVerificationMailer->update($user,$data);
	}
	
	/**
	 * Update tuserdata de-normalized table
	 * Should be called when
	 * 1. New user is created
	 * 2. User info is updated
	 *
	 * @param integer $userId
	 */ 
	function updateUserData($userId)
	{
		$this->CI->load->model('user/usermodel');
        $userModel = new UserModel;
		$userModel->updateUserData($userId);
	}

    public function updateUserInterest($userId, $courseObj){
        if(empty($userId) && !is_object($courseObj)){
            return false;
        }

        $this->CI->load->model('user/usermodel');
        $userModel = new UserModel;

        $courseId = $courseObj->getId();

        /*Get Desired Course from client Course Id */
        $national_course_lib = $this->CI->load->library('listing/NationalCourseLib');
        $dominantDesiredCourseData = $national_course_lib->getDominantDesiredCourseForClientCourses(array($courseId));
        $desiredCourse = $dominantDesiredCourseData[$courseId]['desiredCourse'];

        // $oldDesiredCourse = 0;
        if($courseObj->getCourseLevel() == 'Exam Preparation'){

            $prefId = $userModel->getUserPrefId($userId);
            $userModel->updateUserPref($userId, 0, 'testprep');

            $oldDesiredCourse = $userModel->getBlogIdByPrefId($prefId);
            $testPrepMappingId = $userModel->getTestPrepMappingId($prefId);
            if($testPrepMappingId > 0){
                $userModel->updateTestPrepMapping($testPrepMappingId, $desiredCourse);
            }else{
                $userModel->insertIntoTestPrepMapping($prefId, $desiredCourse);
            }

            $userType = 'testprep';
        }else{
            $oldDesiredCourse = $userModel->getDesiredCourseByUserId($userId);
            $prefId = $userModel->updateUserPref($userId, $desiredCourse, NULL);
            $userType = 'national';
        }

        // add tags corresponding to the desired course updated
        $userModel->updateUserTags($userId, $desiredCourse, $userType);

        if($oldDesiredCourse != $desiredCourse){
            $this->insertDataIntoRegTracking($userId, $desiredCourse, $userType, $userModel);
        }
    }

    /*
     * Function to insert data into the registration tracking table
     * @params: userId : Primary key of tuser table
     *          desiredCourse: column in tUserPref(hold user pref)           
     *          userType: National/Test Prep 
     *          userModel (optional): Usermodel object
     *          oldDesiredCourse: previous/old desired course of the user
     */
    public function insertDataIntoRegTracking($userId, $desiredCourse, $userType, $userModel){
       
        if(empty($userId) || (intval($userId) < 1)){
            return;
        }

        if(empty($desiredCourse) || (intval($desiredCourse) < 1)){
            return;
        }

        if(empty($userModel)){
            $this->CI->load->model('user/usermodel');
            $userModel = new UserModel;
        }

        $userData = $userModel->getUserDetailsForRegistrationTracking($userId);

        $data = array();
        $prefCountry = array();
        foreach ($userData as $key => $value) {
            $data['city'] = $value['city'];
            $data['country'] = $value['country'];
            $prefCountry[] = $value['prefCountry'];
        }
        $data['userId'] = $userId;
        if($userType == 'testprep'){
            $data['desiredCourse'] = 0;
            $testprepData = $userModel->getTestPrepSubCat($desiredCourse);
            $data['subCatId'] = $testprepData['boardId'];
            $data['categoryId'] = $testprepData['parentId'];
            $data['blogId'] = $desiredCourse;
        }else{
            $data['desiredCourse'] = $desiredCourse;
            $data['subCatId'] = $userModel->getSubCategoryIdFromLDBCourseId($desiredCourse);
            $data['categoryId'] = $userModel->getCategoryIdFromLDBCourseId($desiredCourse);
            $data['blogId'] = NULL;
        }
        if(!empty($prefCountry[0])){
            $data['prefCountry1'] = $prefCountry[0];
        }else{
            $data['prefCountry1'] = NULL;
        }

        if(!empty($prefCountry[1])){
            $data['prefCountry2'] = $prefCountry[1];
        }else{
            $data['prefCountry2'] = NULL;
        }

        if(!empty($prefCountry[2])){
            $data['prefCountry3'] = $prefCountry[2];
        }else{
            $data['prefCountry3'] = NULL;
        }

        if($userModel->getSessionSourceType() == 'paid'){
            $data['source'] = 'paid';
        }else{
            $data['source'] = 'free';
        }
        
        $data['userType'] = $userType;
        $data['isNewReg'] = 'no';
        $data['trackingKeyId'] = $this->CI->input->post('tracking_keyid');
        $data['visitorSessionId'] = getVisitorSessionId();
        $data['submitDate'] = date("Y-m-d");
        $data['SubmitTime'] = date("Y-m-d H:i:s");

        $data['referer'] = $_SERVER['HTTP_REFERER'];
        $pageReferer = $this->CI->input->post('pageReferer');
        if(!empty($pageReferer)){
            $data['pageReferer'] = $pageReferer;
        }else{
            $data['pageReferer'] = NULL;
        }

        $userModel->insertIntoRegistrationTracking($data);
        // $userModel->insertIntoMISTracking($data);
    }

    /**
    * Extracts user competitive exam  
    * @param : userIds is an array containing userids
    * @return array containing userId, exam name, exam score and marks type 
    */
    public function getUserExamInfo($userIds = array()){
        $this->CI->load->model('user/userinfomodel');
        $userinfomodel = new userinfomodel;

        $returnData = array();
        $examData = $userinfomodel->getUserExamInfo($userIds);  
        foreach($examData as $key=>$value){
            $returnData[$value['UserId']][$value['Name']] = array(
                                                'Marks' =>$value['Marks'],
                                                'Type' =>$value['Type']
                                                );
           
        }

        return $returnData;
    }

    /**
    * Function to exclude user from LDB allocation and search
    * @param : $userId, primary column of tuser table
    *          $courseId:- client course id        
    */
    public function excludeRMCUserFromLDB($userId, $courseId){
        if(empty($courseId) || empty($userId)){
            return false;
        }

        $this->CI->load->model('user/usermodel');
        $usermodel = new usermodel;

        $this->CI->load->builder('ListingBuilder','listing');
        $listingBuilder = new ListingBuilder;
        $abroadCourseRepository = $listingBuilder->getAbroadCourseRepository();
        $courseObj = $abroadCourseRepository->find($courseId);
        /*Logic: 
         * 1) If form is SA apply and user has made response on paid listing then exclude user from LDB
         * 2) If form is SA Apply and user has given exam then exclude user from the LDB
         */
        $isPaid = $courseObj->isPaid();
        
        unset($courseObj);
        unset($listingBuilder);
        unset($abroadCourseRepository);

        if($isPaid){
            $usermodel->addUserToLDBExclusionList($userId, 'saApply');

            return;
        }
        
        /*Getting SA exams */
        $abroadExams = array();
        $abroadCommonLib = $this->CI->load->library('listingPosting/AbroadCommonLib');
        $examsAbroad = $abroadCommonLib->getAbroadExamsMasterList();
        foreach ($examsAbroad as $key => $value) {
           $abroadExams[] = '"'.$value['exam'].'"';
        }

        /*Getting user exams */
        $userExams = $usermodel->getUserSAExams(array($userId), $abroadExams);

        /*If exams exist, then insert user into  LDBExclusionList table*/
        if(!empty($userExams)){
            $usermodel->addUserToLDBExclusionList($userId, 'saApply');
        }

        return;
    }

    public function getHyperAndNonhyperCoursesCount($baseCourses = array()){
        if(empty($baseCourses) || empty($baseCourses[0])){
            return array();
        }
        
        $baseCourses = is_array($baseCourses)? $baseCourses: array($baseCourses);

        $this->CI->load->builder('listingBase/ListingBaseBuilder');
        $listingBase = new \ListingBaseBuilder();

        $BaseCourseRepository = $listingBase->getBaseCourseRepository();
        $returnData = array('hyperlocal'=>0, 'nonHyperLocal'=>0);
        $baseCoursesObject = $BaseCourseRepository->findMultiple($baseCourses);

        foreach($baseCoursesObject as $row=>$value){
            if($value->getIsHyperlocal()){
                 $returnData['hyperlocal']++;
            }else{
                 $returnData['nonHyperLocal']++;
            }
        }

        return $returnData;
    }

    function addUserToLDBExclusionList($context, $userId,$isdCode=''){
        $this->CI->load->model('user/usermodel');
        $this->usermodel = new UserModel;

        $exclusionType = $context;

        $this->usermodel->addUserToLDBExclusionList($userId, $exclusionType,$isdCode);
    }

    function getAbroadUserExamDetails($userEducation){
        $examDetails = array();
        foreach($userEducation as $education) {
            if($education->getLevel() == "Competitive exam"){
                $examDetails[$education->getName()] = $education->getMarks();
            }
        }
        return $examDetails;
    }

    function checkForExamExceptionRules($examDetails,$usermodel){
        $abroadCommonLib = $this->CI->load->library('listingPosting/AbroadCommonLib');
        $allExams =  $abroadCommonLib->getAbroadExamsMasterList();
        $userExamIds = array();
        foreach ($allExams as $key => $exam) {
            if($examDetails[$exam['exam']]){
                $userExamIds[] = $exam['examId'];
                $examDetails[$exam['examId']] = $examDetails[$exam['exam']];
                unset($examDetails[$exam['exam']]);
            }
        }


        $result = $usermodel->getSAExamExceptionRanges();
        $userInExamExceptionRange = 0;
        foreach ($result as $key => $examExceptionDetails) {
            if(
                isset($examDetails[$examExceptionDetails['examId']]) && 
                $examDetails[$examExceptionDetails['examId']] >= $examExceptionDetails['minScore'] &&
                $examDetails[$examExceptionDetails['examId']] <= $examExceptionDetails['maxScore']
            ){
                $userInExamExceptionRange = 1;
                break;
            }
        }
        return $userInExamExceptionRange;
    }

    function checkForNonExamExceptionRules($user, $courseId ='', $courseLevel ='', $usermodel,$courseObj,$extraData = array()){
        // get course level
        if(empty($courseId)){
            global $studyAbroadLevelWiseDesiredCourses;
            if($courseLevel == 'UG' || in_array($courseLevel, $studyAbroadLevelWiseDesiredCourses['UG'])) { //$courseLevel == '1510'){
                $courseLevel = 'Bachelors';
            }else if($courseLevel == 'PG'|| in_array($courseLevel, $studyAbroadLevelWiseDesiredCourses['PG'])) { //$courseLevel == '1508' || $courseLevel == '1509'){
                $courseLevel = 'Masters';
            }

            if(empty($courseLevel)){
                $preferences = $user->getPreference();
                if(!is_object($preferences)){
                    return 0;
                }
                $ldbCourseId = $preferences->getDesiredCourse();
                if(empty($ldbCourseId)){
                    return 0;   
                }

                global $studyAbroadPopularCourseToLevelMapping;
                if(!is_null($studyAbroadPopularCourseToLevelMapping[$ldbCourseId])){
                    $courseLevel = $studyAbroadPopularCourseToLevelMapping[$ldbCourseId];
                }

                if(empty($courseLevel)) {
                    $regModel = $this->CI->load->model('registration/registrationmodel');
                    $courseLevel = $regModel->getCurrentLevelByLDBCourseIdForSA($ldbCourseId);
                    $courseLevel = $courseLevel['CourseName'];
                }
            }


            $userPreferredCountries = array();
            if(!empty($extraData['desiredCountry'])){
                $userPreferredCountries = $extraData['desiredCountry'];
            }else{
                // get user countries
                $loc = $user->getLocationPreferences();
                foreach($loc  as $location) {
                  $userPreferredCountries[] = $location->getCountryId();
                }
            }

            if(count($userPreferredCountries) <=0){
                return 0;
            }
        }else{
            $courseLevel = $courseObj->getCourseLevel1Value();
            $courseCountryId = $courseObj->getMainLocation()->getCountry()->getId();
            if(empty($courseCountryId) || $courseCountryId <=0){
                return 0;
            }
            $userPreferredCountries = array($courseCountryId);
        }

        if(empty($courseLevel)){
            return 0;
        }

        $userEducation = $user->getEducation();
        if(!is_object($userEducation)){
            return 0;
        }

        if(strpos($courseLevel, 'Bachelors') !== false){
            $percentageFor = '10th';
        }else if(strpos($courseLevel, 'Masters') !== false || strpos($courseLevel, 'PhD') !== false ){
            $percentageFor = 'graduation';
        }

        $data = array();
        foreach($userEducation as $education) {
            if($percentageFor == '10th'){
                if($education->getName() == '10'){
                    $data['EduName'] = '10';
                    $data['tenthBoard'] = $education->getBoard();
                    $data['tenthMarks'] = $education->getMarks();
                }
            }else{
                if($education->getName() == 'Graduation'){
                    $data['EduName'] = 'Graduation';
                    $data['graduationPercentage'] = $education->getMarks();
                }
            }
        }

        
        if(count($data) <=0){
            return 0;
        }
        
        global $ABROAD_Exam_Grade_Mapping;
        if($data['EduName'] == '10'){
            if($ABROAD_Exam_Grade_Mapping[$data['tenthBoard']]){
                $gradeToPercentage = $ABROAD_Exam_Grade_Mapping[$data['tenthBoard']][$data['tenthMarks']];
            }else{
                $gradeToPercentage = $data['tenthMarks'];
            }
        }else{
            $gradeToPercentage = $data['graduationPercentage'];
        }
        $result = $usermodel->getSANonExamExceptionRules($userPreferredCountries, $courseLevel,$gradeToPercentage);
        return array_column($result,'country');
    }    

    function checkIfUserAlreadyProcessed($userId, $usermodel){
        $result = $usermodel->checkIfUserAlreadyProcessed($userId);
        if(count($result)>0){
            return 1;
        }else{
            return 0;
        }
    }

    function checkUserForLDBExclusion(
                                    $userId, 
                                    $type="registration", 
                                    $listingType='', 
                                    $listingTypeId='', 
                                    $courseLevel = '', 
                                    $usermodel ='', 
                                    $actionType='',
                                    $extraData = array()
                                    )
    {
        if($type == "registration" && $listingType == "scholarship"){
            return ;
        }


        
        if(!is_object($usermodel)){
            $this->CI->load->model('user/usermodel');
            $usermodel = new usermodel;
        }

        $user   = $usermodel->getUserById($userId,true);

        if (!is_object($user)) {
            mail('naveen.bhola@shiksha.com','User object not found','User object not found for userId : '.$userId);
            return;
        }

        $isdCode = $user->getISDCode().'-'.$user->getCountry();
        $preferences = $user->getPreference();
        $exclusionType = '';
        $historyUserInExclusionTable = 0;
        //only abroad users
        if($preferences->getExtraFlag() == 'studyabroad'){
            // for response there should be course
            if($listingType == 'course' && !empty($listingTypeId) && $listingTypeId >0){
                $this->CI->load->builder('ListingBuilder','listing');
                $listingBuilder = new ListingBuilder;
                $abroadCourseRepository = $listingBuilder->getAbroadCourseRepository();
                // find that course
                $courseObj = $abroadCourseRepository->find($listingTypeId);
            }
            // check if user isn't already processed or is a case of exam score update/ exam doc upload
            if(!$this->checkIfUserAlreadyProcessed($user->getId(),$usermodel) || 
                $extraData['examScoreUpdated'] === true)
            {
                $coursePaidStatus = false;
                // check for international user
                if($isdCode == INDIA_ISD_CODE){
                    // if it is response..
                    if($type=="response" && $listingType == 'course'){
                        // .. & action was rate my chances, exclusion type /reason is RMC...
                        if(strpos($actionType,'rate_my_chances')!== false){
                            $exclusionType = 'RMC';
                        }else if($courseObj->isPaid() == 1){ // ... or for paid course
                            $coursePaidStatus = true;
                        }                        
                    }
                    if(
                        // so... for response on free course..
                        ($type=="response" && $listingType == 'course' && $exclusionType == '') || 
                        // .. or plain old registration.. or profile eval call where there is no change in profile(yes this wont have any impact but this is being done to bring back a user who is processed in ldb already).
                        $type=="registration" || $type=="gafpec" ||
                        // .. or response on scholarship..
                        ($type=="response" && $listingType == 'scholarship')
                    ){
                        // check if user gave exams
                        $userEducation = $user->getEducation();
                        $userHasExam = 0;
                        if(is_object($userEducation)){
                            $examDetails = $this->getAbroadUserExamDetails($userEducation);
                            if(count($examDetails) > 0){
                                $userHasExam = 1;
                                // check for exam exception rules (exam score is in range that counsellors can serve)
                                if($this->checkForExamExceptionRules($examDetails,$usermodel)){
                                    $exclusionType = 'SAExamException';
                                    if(isset($coursePaidStatus) && !empty($coursePaidStatus) && $coursePaidStatus === true){
                                        $exclusionType = 'SAPaidCourse';
                                    }
                                }else{
                                    $historyUserInExclusionList = 1;
                                }
                            }
                        }
                        // if use hasn't given any exam, check for no exam exception rule
                        if(!is_object($userEducation) || 
                            (is_object($userEducation) && $userHasExam == 0)
                        )
                        {
                            if(empty($examDetails)){
                                // check for non exam exception rules
                                $listingIdForNonExamExpRule = ($listingType == 'course')?$listingTypeId:'';
                                $nonExamExceptionCountryIds = $this->checkForNonExamExceptionRules($user, $listingIdForNonExamExpRule,$courseLevel,$usermodel,$courseObj,$extraData);
                                if(isset($nonExamExceptionCountryIds) && !empty($nonExamExceptionCountryIds) && count($nonExamExceptionCountryIds) > 0){
                                    $exclusionType = 'SANonExamException';
                                    if(isset($coursePaidStatus) && !empty($coursePaidStatus) && $coursePaidStatus === true){
                                        $exclusionType = 'SAPaidCourse';
                                    }
                                }else{
                                    $historyUserInExclusionList = 1;
                                }
                            }
                        }
                    }
                }else{ // if isd code not of india (still in !isprocessed || exam update/upload case check)
                    $exclusionType = 'International User';
                }
            }else{ // user already processed & not exam update/upload case
                if($isdCode != INDIA_ISD_CODE){ // exclude from ldb if international user
                    $exclusionType = 'International User';
                }else{ 
                    /* users that were already processed & were not exam update/upload cases with a domestic isd code should be
                    marked as history in ldb exclusion*/
                    $historyUserInExclusionList = 1;
                }
            }
        }else{ // domestic user
            if($isdCode != INDIA_ISD_CODE){
                $exclusionType = 'International User';
            }
        }

        // any reason found to exclude from LDB ?( reasons being:International user/ exam exception/nonexam exception/RMC/paid course)

        //adding this check to exclude international user from shikshaApplyLoop as well as from ldb
        if(!empty($exclusionType) && $exclusionType == 'International User' && $preferences->getExtraFlag() == 'studyabroad'){
            $rmcRuleLib = $this->CI->load->library('shikshaApplyCRM/rmcRuleLib');
            $userActiveFlag = $rmcRuleLib->isUserActiveInShikshaApplyLoop($user->getId());
            if($userActiveFlag == "Yes"){
                // active ones wont be marked history in ldb exclusion list
                $historyUserInExclusionList = 0;
            }else{
                // in active ones will be marked history in ldb exclusion list
                $shikshaApplyLib = $this->CI->load->library('shikshaApplyCRM/shikshaApplyLib');
                $shikshaApplyLib->markUserRmcStageHistory($userId,$userId);
            }
            $this->addUserToLDBExclusionList('International User', $user->getId(),$isdCode);
        }
        else if(!empty($exclusionType)){
            // abroad user
            if($preferences->getExtraFlag() == 'studyabroad'){
                if(
                    ($type == "gafpec") || 
                    ($type == "registration" && empty($listingTypeId)) || 
                    ($type == "response")
                )
                {
                    $userData = array(
                        'MISTrackingId' => $extraData['tracking_keyid'],
                        'userId' => $user->getId()
                    );

                    if($type == "registration"){
                        $userData['registrationType'] = "lead";
                    }else if($type == "response" && $listingType == "scholarship"){
                        $userData['registrationType'] = "scholarshipLead";
                    }else{
                        $userData['registrationType'] = $type;
                    }

                    if($type == "registration" || $type == "gafpec" || ($type == "response" && $listingType == 'scholarship')){
                        if(!empty($extraData['desiredCountry'])){
                            $userData['countryId'] = $extraData['desiredCountry'];
                        }else{
                            $loc = $user->getLocationPreferences();
                            $userPreferredCountries = array();
                            foreach($loc  as $location) {
                                $userPreferredCountries[] = $location->getCountryId();
                            }
                            $userData['countryId'] = $userPreferredCountries;
                        }
                        
                        
                        if($extraData['LDBCourseId']){
                            $userData['LDBCourseId'] = $extraData['LDBCourseId'];
                        }else{
                            $preferences = $user->getPreference();
                            $userData['LDBCourseId'] = $preferences->getDesiredCourse();
                        }
                        
                        if($type == "registration" || $type == "gafpec"){
                            $userData['categoryId'] = $extraData['categoryId'];
                            $userData['courseLevel'] = $courseLevel;
                        }else if($type == "response" && $listingType == 'scholarship'){
                            $userData['clientCourseId'] = $listingTypeId;
                        }
                    }else{
                        $userData['clientCourseId'] = $listingTypeId;
                        $countryLocationObj = $courseObj->getMainLocation();
                        $courseCountryId = 0;
                        if(is_object($countryLocationObj)){
                            $courseCountryId =$countryLocationObj->getCountry()->getId();
                        }
                        $userData['countryId'] = array($courseCountryId);
                        $courseDesireCourse = $courseObj->getDesiredCourseId();
                        $userData['LDBCourseId'] = (!empty($courseDesireCourse))?$courseDesireCourse:$courseObj->getLDBCourseId();
                        $userData['courseLevel'] = $courseObj->getCourseLevel1Value();
                        $userData['subCategoryId'] = $courseObj->getCourseSubCategory();
                    }
                    
                    // get user missing fields
                    $userData = $this->getMissingFieldDataForSALDBExcludeUser($userData, $listingType);
                    // check if user is from focus course and focus country
                    $counselorAllocationLib = $this->CI->load->library('shikshaApplyCRM/abroadLeadAllocationLib');
                    //adding this check to include those countries which passed non-exam-exception
                    if(isset($nonExamExceptionCountryIds) && !empty($nonExamExceptionCountryIds)){
                        $userData['countryId'] = array_values(array_intersect($userData['countryId'],$nonExamExceptionCountryIds));
                    }
                    $inputData = array('countryIds' => $userData['countryId'],'ldbCourseId' => $userData['LDBCourseId'] );

                    //push 0 for other countries in $inputData countryIds
                    $this->CI->load->config('studyAbroadCMSConfig');
                    global $abroadLeadAllocationCountries;
                    if(!empty(array_diff($inputData['countryIds'], array_keys($abroadLeadAllocationCountries)))){
                        array_push($inputData['countryIds'],0);
                    }

                    $foucsFlag = $counselorAllocationLib->isFocusedCombinationForShikshaApply($inputData);
                    unset($inputData);
                    if($foucsFlag === true){
                        // if given country course combination is focused by counselors
                        $this->addUserToLDBExclusionList($exclusionType, $user->getId(),$isdCode);
                        // dump all the data fetched above for this user in to studyAbroadUserDataForCounselorAllocation
                        $this->dumpSAExcludeUser($userData, $listingType);
                    }else{// if there is no focus by counsellor for given country course combination...
                        // ... still add to ldb exclusion list if user was from abroad...
                        if($isdCode != INDIA_ISD_CODE){
                            $this->addUserToLDBExclusionList('International User', $user->getId(),$isdCode);
                        }else{  
                            // check if user being serviced by counsellors (active in apply loop)
                            /**
                             * Users that are not considered 'active' in apply loop : 
                             * - dropoff users 
                             * - users that dont have a stage
                             * - users that were rated poor in all courses 
                             */
                            $rmcRuleLib = $this->CI->load->library('shikshaApplyCRM/rmcRuleLib');
                            $userActiveFlag = $rmcRuleLib->isUserActiveInShikshaApplyLoop($user->getId());
                            if($userActiveFlag == "Yes"){
                                // active ones wont be marked history in ldb exclusion list
                                $historyUserInExclusionList = 0;
                            }else{
                                // in active ones will be marked history in ldb exclusion list
                                $historyUserInExclusionList = 1;
                            }
                        }
                    }
                }
            }else{ // domestic user but still there was a reason to exclude : International user
                $this->addUserToLDBExclusionList($exclusionType, $user->getId(),$isdCode);
            }
        }else{ 
            /** no reason found to exclude from LDB, no just check if we need to 
             *  mark existing user entry as history in LDB Exclusion
             */
            // if user belongs to SA...
            if($preferences->getExtraFlag() == 'studyabroad'){
                $rmcRuleLib = $this->CI->load->library('shikshaApplyCRM/rmcRuleLib');
                $userActiveFlag = $rmcRuleLib->isUserActiveInShikshaApplyLoop($user->getId());
                // ...active users must not be marked history in LDB exclusion ...
                if($userActiveFlag == "Yes"){
                    $historyUserInExclusionList = 0;
                }else{
                    // ...while inactive ones must be marked history in LDBexclusion
                    $historyUserInExclusionList = 1;
                }
            }
        }

        // mark as history
        if($historyUserInExclusionList == 1){
            $usermodel->markUserHistoryInLDBExclusionList($userId,"International User");
        }
    }

    function getMissingFieldDataForSALDBExcludeUser($userData, $listingType =''){
        if($userData['registrationType'] == 'lead' || $userData['registrationType'] == "gafpec" || ($userData['registrationType'] == "scholarshipLead")){
            
            if(count($userData['countryId'])<=0){
                mail('naveen.bhola@shiksha.com','Data is missing for SA User dump '.date('Y-m-d H:i:s'), 'From page: '.$_SERVER['HTTP_REFERER'].'<br/>'.print_r($_POST, true));
            }
            $regModel = $this->CI->load->model('registration/registrationmodel');
            if(count($userData['countryId'])<=0){
                // get abroad countries ids
                $result = $regModel->getUserDesiredCountries($userData['userId']);
                foreach ($result as $key => $value) {
                    $userData['countryId'][] = $value['CountryId'];
                }
            }

            if(empty($userData['LDBCourseId'])){
                $result = $regModel->getUserLDBCourseId($userData['userId']);
                $userData['LDBCourseId'] =$result[0]['DesiredCourse'];
            }
        }
        return $userData;
    }

    function dumpSAExcludeUser($userData){
        $counselorAllocationLib = $this->CI->load->library('shikshaApplyCRM/abroadLeadAllocationLib');
        $counselorAllocationLib->dumpUserDataForCounselorAllocation($userData);
    }
    
    /**
     * Fetch user details from solr
     * @author Aman Varshney <varshney.aman@gmail.com>
     * @date   2017-09-18
     * @param  array     $userIds [description]
     * @return 
     */
    function getUserDataFromSolr($userIds,$returnDisplayData = false){
        if(empty($userIds) || count($userIds) <= 0){
            return  false;
        }
        $this->CI->load->builder('SearchBuilder','search');
        $solrServer = SearchBuilder::getSearchServer();

        $this->CI->load->library('LDB/searcher/SearchAgentRequestGenerator');
        $requestGenerator = new SearchAgentRequestGenerator;

        $request = $requestGenerator->getUserDataForGenieCSV($userIds);
        $request_array = explode("?",$request); 
        $response = $solrServer->leadSearchCurl($request_array[0],$request_array[1]); 

        $response = unserialize($response);

        $docResponse = $response['grouped']['userId']['groups'];
        unset($response);

        $uniqueUserDoc = array();
        foreach ($docResponse as $res) {
            $doc = $res['doclist']['docs'][0];
            $uniqueUserDoc[$doc['userId']] = $doc;
        }
            
        unset($docResponse);
        
        foreach ($uniqueUserDoc as $userId => $doc) {              
            $userData[$userId] =  $this->formatUserDisplayData($doc,$returnDisplayData);
        }
            
        unset($uniqueUserDoc);
        return $userData;
    }

    function formatUserDisplayData($doc,$returnDisplayData){

            $displaydata = json_decode($doc['displayData'],true);

            if($returnDisplayData == true){
                return $displaydata;
            }

            $userDisplayData['userid']         = $displaydata['userId'];
            $userDisplayData['userId']         = $displaydata['userId'];    //put two value to cater the impact
            $userDisplayData['First Name']     = $displaydata['firstname'];
            $userDisplayData['Last Name']      = $displaydata['lastname'];
            $userDisplayData['Full Name']      = $displaydata['firstname'].' '.$displaydata['lastname'];
            $userDisplayData['Email']          = $displaydata['email'];
            $userDisplayData['ISD Code']       = $displaydata['isdCode'];
            $userDisplayData['mobileverified'] = $displaydata['mobileverified'];

            if($displaydata['isNDNC'] == 'YES'){
                $isNDNC = 'Do not call';
            } else if($displaydata['isNDNC'] == 'NO'){
                $isNDNC = 'Can call';
            } else{
                $isNDNC = '';
            }
            $userDisplayData['NDNC Status'] = $isNDNC;

            $workex = '';
            if(isset($displaydata['experience'])){
                $value = $displaydata['experience'];
                if($value == -1){
                    $workex = 'No Experience';
                }else if($value == 10){
                    $workex = '> 10 Years';
                } else if($value == 0){
                    $workex = $value.'-'.(intval($value+1)).' Year';
                } else {
                    $workex = $value.'-'.(intval($value+1)).' Years';
                }
            }
            
            $userDisplayData['Work Experience']  = $workex;
            
            $countryName                         = $this->getCountryName($displaydata['country']);
            $userDisplayData['Current Country']  = $countryName;
            unset($countryName);
            
            $userDisplayData['Current City']     = $displaydata['CurrentCity'];
            $userDisplayData['Current Locality'] = $displaydata['localityName'];
            $userDisplayData['Mobile']           = $displaydata['mobile'];
            
            $examsArray                          = array();
            foreach ($displaydata['EducationData'] as $educationDetails) {
                if($educationDetails['Level'] == 'Competitive exam'){
                    $examsArray[] = $educationDetails['Name'];
                }else{
                    $userDisplayData['EducationData'][$educationDetails['Level']]   = $educationDetails;    
                }
            }
            asort($examsArray);
            $userDisplayData['Exams Taken'] = implode(', ', $examsArray);
            unset($examsArray);

            return $userDisplayData;
             
        }

        function getCountryName($countryId){
            $this->articlesAbroadWidgetsModel = $this->CI-> load->model('studyAbroadArticleWidget/articlesabroadwidgetsmodel');
            $countryName = $this->articlesAbroadWidgetsModel->getCountryNameById($countryId);
            return $countryName;
        }


        /**
         * Function to update education details in tUserEducation
         *
         * @params : 3 keys expected in this API
                    $params['examType'] = yes/no/booked
                    $params['userId'] = 3814277
                    $params['exams'] = array(
                                             array('Name'=>'GMAT','Marks'=>300,'MarksType'=>'score'), 
                                             array('Name'=>'TOEFL','Marks'=>100,'MarksType'=>'marks')
                                        );

         */
        function updateUserEducationDetails($params) {

            if(empty($params)) { return 'failed'; }

            $examType = $params['examType'];
            $userId = $params['userId'];
            $exams = $params['exams'];

            $examTypes = array('yes','no','booked');
            if(empty($examType) || ( (!empty($examType)) && (!in_array($examType, $examTypes))) || empty($userId) || $userId <= 0) { echo 'failed';return; }

            $examInfoTrackingData = array();
            $examInfoTrackingData['examTaken'] = $examType;
            $examInfoTrackingData['userId'] = $userId;

            if(($examType == 'yes') && (!empty($exams))) {

                $abroadExamMasterList = array(); $abroadExams = array();
                $abroadCommLib = $this->CI->load->library('listingPosting/AbroadCommonLib');
                $abroadExamMasterList = $abroadCommLib->getAbroadExamsMasterList();
                foreach ($abroadExamMasterList as $key => $abroadExam) {
                    $abroadExams[] = $abroadExam['exam'];
                }

                $allExamsData = array();
                foreach($exams as $key=>$exam) {

					if(empty($exam['Name']) || $exam['Marks'] < 0 || empty($exam['MarksType'])) { return 'failed'; }
                    $examInfoTrackingData['exams'][$exam['Name']] = (int)$exam['Marks'];
                    $allExamsData[$key]['Name'] = $exam['Name'];
                    $allExamsData[$key]['Marks'] = $exam['Marks'];
                    $allExamsData[$key]['MarksType'] = $exam['MarksType'];
                    $allExamsData[$key]['Level'] = 'Competitive exam';
                    $allExamsData[$key]['Country'] = 2;
                    $allExamsData[$key]['SubmitDate'] = date('Y-m-d H:i:s');
                    $allExamsData[$key]['Status'] = 'live';
                    $allExamsData[$key]['UserId'] = $userId;
                    $allExamsData[$key]['CourseCompletionDate'] = '';
                }
            }

            if($examType == 'yes' || $examType == 'no') {
                $data = array('bookedExamDate'=>'0');
            } else {
                $examInfoTrackingData['examTaken'] = "bookedExamDate";
                $data = array('bookedExamDate'=>'1');
            }
            // track abroad exam history
            $examInfoTrackingData['isStudyAbroad'] = "yes";
            $examInfoTrackingData['actualExamGiven'] = $examInfoTrackingData['exams'];
            $this->trackUserExamInfo($examInfoTrackingData);

            $this->CI->load->model('user/usermodel');
            $userModel = new UserModel;
            $userModel->_backupUserExams($userId, $abroadExams);
            $userModel->deleteUserExams($userId, $abroadExams);

            if(($examType == 'yes') && (!empty($allExamsData))) {                
                $userModel->insertUserEducationDetails($allExamsData);
            }

            $userModel->updateUserAdditionalInfo($userId, $data);
            $userModel->addUserToIndexingQueue($userId);    

            return 'success';
        }

    public function getUserExamsDetails($userIds, $usermodel, $abroadExams = "no", $LastUpdateTimeReq = "no"){
        if(empty($userIds) || !is_array($userIds) || count($userIds) <=0){
            return false;
        }

        if(!is_object($usermodel)){
            $usermodel = $this->CI->load->model('user/usermodel');    
        }

        $userCurrentExamDetails = $usermodel->getUserExamsDetails($userIds);
        if(!is_array($userCurrentExamDetails) || count($userCurrentExamDetails) <=0){
            return array();
        }

        $examDetails = array();
        if($abroadExams == "yes"){
            $abroadCommLib = $this->CI->load->library('listingPosting/AbroadCommonLib');
            $abroadExamMasterList = $abroadCommLib->getAbroadExamsMasterList();
            foreach ($abroadExamMasterList as $key => $abroadExam) {
                $abroadExamMasterList[$abroadExam['exam']] =1;
                unset($abroadExamMasterList[$key]);
            }
            foreach ($userCurrentExamDetails as $key => $userExam) {
                if($abroadExamMasterList[$userExam['Name']]){
                    $examDetails[$userExam['UserId']][$userExam['Name']] = (int)$userExam['Marks'];
                    if($LastUpdateTimeReq == "yes"){
                        if(!isset($examDetails[$userExam['UserId']]['submitDate'])){
                            $examDetails[$userExam['UserId']]['submitDate'] = $userExam['SubmitDate'];
                        }
                        if(strtotime($examDetails[$userExam['UserId']]['submitDate']) < strtotime($userExam['SubmitDate'])){
                            $examDetails[$userExam['UserId']]['submitDate'] = $userExam['SubmitDate'];
                        }
                    }
                    
                }
            }
        }else{
            foreach ($userCurrentExamDetails as $key => $userExam) {
                $examDetails[$userExam['UserId']][$userExam['Name']] = (int)$userExam['Marks'];
            }
        }
        return $examDetails;
    }

    public function trackUserInfo($data, $userId){
        if(empty($userId) || $userId<=0){
            return false;
        }

        $exams = array();
        $isStudyAbroad = ($data['isStudyAbroad'] == "yes") ?"yes" : "no";
        if($isStudyAbroad == "yes"){
            $examTypes = array('yes','no','bookedExamDate');
            $examTaken = $data['examTaken'];

            if(empty($examTaken) || (!empty($examTaken) && !in_array($examTaken, $examTypes))) {
                return false;
            }

            if($examTaken == "yes"){
                if(is_array($data['exams']) && count($data['exams'])>0){
                    foreach($data['exams'] as $exam) {
                        $exams[$exam] = (int)$data[$exam.'_score'];
                    }
                }else{
                    $examTaken = ($data['bookedExamDate'] == 1) ? "bookedExamDate":"no";
                }
            }
        }else{
            if(is_array($data['exams']) && count($data['exams'])>0){
                // check common exam with abroad
                $abroadCommLib = $this->CI->load->library('listingPosting/AbroadCommonLib');
                $response = $abroadCommLib->getAbroadExamsMasterList();
                foreach ($response as $key => $examDetails) {
                    if(in_array($examDetails['exam'], $data['exams'])){
                        $exams[$examDetails['exam']] = (int)0;
                    }
                }
                if(count($exams) <=0){
                    return true;
                }
                $examTaken = 'yes';
            }else{
                return true;
            }
        }

        $inputData = array(
            'userId'          => $userId,
            'examTaken'       => $examTaken,
            'exams'           => $exams,
            'isStudyAbroad'   => $isStudyAbroad
        );

        if($isStudyAbroad == "yes"){
            $inputData['actualExamGiven'] = $exams;
        }else{
            $inputData['actualExamGiven'] = $data['exams'];
        }
        //_p($inputData);die;
        return $this->trackUserExamInfo($inputData);
    }    

    public function trackUserExamInfo($examInfo){
        $userId = $examInfo['userId'];
        if(empty($userId) || $userId<=0){
            return false;
        }
    
        // check if row already exist
        $usermodel = $this->CI->load->model('user/usermodel');
        $response = $usermodel->getUserInfoByAttribute($userId, 'abroadExam');
        //_p($response);die;
        $isExamInfoChanged = false;

        if(is_array($response) && count($response)>0){
            $userCurrentExamDetails = $response[0];
            //_p($userCurrentExamDetails);
            if($userCurrentExamDetails['attributeValue'] == $examInfo['examTaken']){
                if($userCurrentExamDetails['attributeValue'] == "yes"){
                    // get user previous exams
                    $examDetails = $this->getUserExamsDetails(array($userId), $usermodel, "yes");
                    if(!is_array($examDetails) || count($examDetails) <=0){
                        $isExamInfoChanged = true;
                    }else{
                        $examDetails = $examDetails[$userId];
                        if(($examInfo['isStudyAbroad'] == "yes") && count($examInfo['exams']) != count($examDetails)){
                            $isExamInfoChanged = true;
                        }else{
                            foreach ($examDetails as $examName => $examScore) {
                                if(!isset($examInfo['exams'][$examName]) || ($examInfo['exams'][$examName] != $examScore)){
                                    $isExamInfoChanged = true;
                                }
                            }
                        }
                    }
                        
                }
            }else{
                $isExamInfoChanged = true;
            }

            // mark previous entry as history            
            if($isExamInfoChanged == true){
                $usermodel->updateStatusInUserInfoTracking($response[0]['id'], 'history');    
            }
        }else{
            $isExamInfoChanged = true;
        }

        if($isExamInfoChanged == true){
            $this->_insertRowInUserInfoTrackingForExam($examInfo, $usermodel);
        }
    }

    private function _insertRowInUserInfoTrackingForExam($examInfo, $usermodel){
        $row = array(
            'userId' => $examInfo['userId'],
            'attributeName'   => 'abroadExam',
            'referer'   => $_SERVER['HTTP_REFERER'],
            'visitorSessionId' => getVisitorSessionId()
        );

        if($examInfo['examTaken'] == "yes"){
            $row['attributeValue'] = "yes";
            // track exam details
            $examDetails = json_encode($examInfo['actualExamGiven']);
            $row['extraData'] = $examDetails;
        }else if($examInfo['examTaken'] == "no"){
            $row['attributeValue'] = "no";
        }else if($examInfo['examTaken'] == "bookedExamDate"){
            $row['attributeValue'] = "booked";
        }

        // check if user is in 'Dropoff' stage, bring them back to 'Ready'
        $rmcPostingLib = $this->CI->load->library('shikshaApplyCRM/rmcPostingLib');
        $rmcPostingLib->moveStudentFromDropoffToReady($examInfo['userId'],"Student updated exam score via edit profile.");
        
        $usermodel->insertRowInUserInfoTracking($row);
    }

    public function getUserCurrentExamDetails($userId){
        if(empty($userId) || $userId <=0){
            return false;
        }

        $usermodel = $this->CI->load->model('user/usermodel');
        $response = $usermodel->getUserInfoByAttribute($userId, "abroadExam");
        $returnData = array();
        if(is_array($response) && count($response) >0){
            $response = $response[0];
            $returnData[$userId]['examGiven'] = $response['attributeValue'];
            $returnData[$userId]['submitDate'] = $response['addedOn'];
            if($response['attributeValue'] == "yes"){
                // get exam details from tusereducation
                $abroadExams = $this->getUserExamsDetails(array($userId), $usermodel, "yes");
                $abroadExams = $abroadExams[$userId];
                if(is_array($abroadExams) && count($abroadExams) >0){
                    $returnData[$userId]['examDetails'] = $abroadExams;
                }else{
                    $returnData =  array($userId => array());
                }
            }
        }else{
            // get exam details from tusereducation
            $abroadExams = $this->getUserExamsDetails(array($userId), $usermodel, "yes","yes");
            $abroadExams = $abroadExams[$userId];
            if(is_array($abroadExams) && count($abroadExams) >0){
                $returnData[$userId]['examGiven'] = "yes";
                $returnData[$userId]['submitDate'] = $abroadExams['submitDate'];
                unset($abroadExams['submitDate']);
                $returnData[$userId]['examDetails'] = $abroadExams;
            }else{
                $returnData =  array($userId => array());
            }
        }

        return $returnData;
    }

    public function userWithUpdatedExamDetails($hours){
        if(empty($hours) || $hours <=0){
            return false;
        }

        $date = date("Y-m-d H:i:s", strtotime("-$hours hours"));

        $usermodel = $this->CI->load->model('user/usermodel');
        $response = $usermodel->userWithUpdatedExamDetails($date,'abroadExam');
        
        $userIdsWithExamGiven = array();
        $returnData = array();

        if(is_array($response) && count($response) >0){
            foreach ($response as $key => $userExamDetails) {
                $returnData[$userExamDetails['userId']] = array(
                    'examGiven' => $userExamDetails['attributeValue'],
                    'submitDate'    => $userExamDetails['addedOn']
                );
                if($userExamDetails['attributeValue'] == "yes"){
                    $userIdsWithExamGiven[] = $userExamDetails['userId'];
                }
            }
        }else{
            $returnData =  array();
        }

        if(count($userIdsWithExamGiven) > 0){
            $abroadExams = $this->getUserExamsDetails($userIdsWithExamGiven, $usermodel, "yes");
            if(is_array($abroadExams) && count($abroadExams) >0){
                foreach ($abroadExams as $userId => $examDetails) {
                    if(is_array($examDetails) && count($examDetails) >0){
                        $returnData[$userId]['examDetails'] = $examDetails;
                    }
                }
            }
        }

        return $returnData;
    }

    function storeTempResponseInterestData($data,$userId){
        if(empty($userId) || $userId<=0){
            return false;
        }
        
        $response_data = array(
            'user_id'               => $userId,
            'listing_id'            => $data['clientCourse'],
            'listing_type'          => $data['listing_type'],
            'preferred_city_id'     => $data['prefCity'],
            'preferred_locality_id' => $data['prefLocality'],
            'visitor_session_id'    => getVisitorSessionId(),
            'tracking_key'          => $data['tracking_keyid'],
            'action_type'           => $data['action_type'],
            'reg_form_id'           => $data['regFormId']
        );

        $this->CI->load->model('response/responsemodel');
        $responseModel = new ResponseModel();
        $lastInsertId = $responseModel->storeResponseInterestData($response_data);
        return $lastInsertId;
    }

    function getExamGroupsIds($examNames){
        $this->CI->load->model('examPages/exammodel');
        $examModel = new exammodel;    
        $redis_client = PredisLibrary::getInstance();
        $finalData = array();
        $prefixRediskey = "examGroupId_";
        foreach ($examNames as $key => $examName) {
            $redisKey = $prefixRediskey.$examName;
            $examGroupId = $redis_client->getMemberOfString($redisKey);
            if (!empty($examGroupId)){
                $finalData[$examName] = $examGroupId;
                unset($examNames[$key]);
            }
        }
        if (!empty($examNames))
        {
            $examGroupIds = $examModel->getExamPrimaryGroupIdByName($examNames);
            $examGroupIds = array_column($examGroupIds,"groupId","name");
            foreach ($examGroupIds as $exam => $groupId) {
                $finalData[$exam] = $groupId;
                $redisKey = $prefixRediskey.$exam;
                $redis_client->addMemberToString($redisKey,$groupId,604800);
            }
        }
        return $finalData;  
    }
}
