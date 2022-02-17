<?php
/**
 * File to handle User Registration
 */ 


/**
 * This class is used to do user registration
 */ 
class Registration extends MX_Controller
{

    private $sent_verify_users;
    /**
     * Register a new user using data submitted by user in a registration form
     */ 
    public function register()
    {
          
        $this->load->model('user/usermodel');
        $userModel = new usermodel;
        
        $this->load->library('user/UserLib');
        $userLib = new UserLib;

		$this->load->helper('security');
        $data = xss_clean($_POST);
        if(isset($data['isdCode']))
        {
            if($data['isdCode']!='91-2')
            {
                unset($data['residenceCityLocality']);
            }
        }

        $data['newUser'] = true;
        $response = array();

        if(empty($data['tracking_keyid'])) {
            // $defaultData['tracking_keyid'] = $tracking_keyid;
            if($data['listing_type'] == 'exam'){
                if (isMobileRequest()) {
                    $tracking_keyid = DEFAULT_TRACKING_KEY_EXAM_MOBILE;
                } else {
                    $tracking_keyid = DEFAULT_TRACKING_KEY_EXAM_DESKTOP;
                }
            } else {
                if (isMobileRequest()) {
                    $data['tracking_keyid'] = DEFAULT_TRACKING_KEY_MOBILE;
                } else {
                    $data['tracking_keyid'] = DEFAULT_TRACKING_KEY_DESKTOP;
                }
            }
        }
  
        /**
         * Check if user with this email id already exists
         */
        $user = $userModel->getUserByEmail($data['email']); 
        if($user && $user != FALSE) {
            $response['status'] = 'USER_ALREADY_EXISTS';
            $userFlags = $user->getFlags();
            $response['isLDBUser'] = $userFlags->getIsLDBUser();
        }
        else {
            if(!empty($data['specializations']) || !empty($data['subStream'])){
                $data['subStreamSpecializations']['subStream'] = $data['subStream'];
                $data['subStreamSpecializations']['specializations'] = $data['specializations'];
            }

            if($this->_validateData($data)) {
                try {
                    $data = $this->_preprocessRegistrationData($data);
                    if($data['desiredCourse'] || $data['fieldOfInterest']) {
                        $redirector = new \registration\libraries\PostRegistrationRedirector($data);
                        $redirectURL = $redirector->getRedirectionURL();
                        $response['redirectURL'] = $redirectURL;
                        $data['landingPage'] = $redirectURL;
                        $response['desiredCourse'] = $data['desiredCourse']?$data['desiredCourse']:null;
                        $response['preferredStudyLocation'] = is_array($data['preferredStudyLocation'])?implode(',', $data['preferredStudyLocation']):(is_array($data['preferredStudyLocalityCity'])?implode(',', $data['preferredStudyLocalityCity']):null);
                    }
                    
                    $user = $userLib->createUser($data); 
                    $userLib->loginUser($user);

                    $this->load->library('common/CookieBannerTrackingLib');
                    $this->cookieBanner = new CookieBannerTrackingLib();
                    $this->cookieBanner->newUserCookieSet();



                    $response['status'] = 'SUCCESS';
                    $response['userId'] = $user->getId();
                    $response['regFormId'] = $data['regFormId'];
                    if(isset($data['registrationStep'])) {
                        $response['registrationStep'] = $data['registrationStep'];
                    }
                    // load abroadcommonlib for desired course
                    $abroadCommonLib = $this->load->library('listingPosting/AbroadCommonLib');
                    $abroadDesiredCourses = $abroadCommonLib->getAbroadMainLDBCourses();
                    $abroadDesiredCourseIds = array_map(function($a){return $a['SpecializationId'];},$abroadDesiredCourses);
                    $userCourseLevel = '';
                    if($data['isStudyAbroad'] == "yes"){
                        if(!empty($data['courseLevel'])){
                            $userCourseLevel = $data['courseLevel'];
                        }else if(!empty($data['desiredCourse'])){
                            $userCourseLevel = $data['desiredCourse'];
                        }else if(!empty($data['desiredGraduationLevel'])){
                            $userCourseLevel = $data['desiredGraduationLevel'];
                        }

                        $extraDataForExclusion = array(
                            'tracking_keyid' => $data['tracking_keyid'],
                            'desiredCountry' => $data['destinationCountry']
                        );
                        if(in_array($data['fieldOfInterest'], $abroadDesiredCourseIds)){
                            $extraDataForExclusion['LDBCourseId'] = $data['fieldOfInterest'];
                        }else{
                            $extraDataForExclusion['categoryId'] = $data['fieldOfInterest'];
                        }
                    }

                    $userLib->checkUserForLDBExclusion($user->getId(), 'registration', $data['listingTypeForBrochure'], $data['listingTypeIdForBrochure'], $userCourseLevel,$userModel,'',$extraDataForExclusion);

                    $userLib->trackUserInfo($data, $user->getId());

                    if($data['saABTracking'] == 'yes'){
                        $pageReferrer = explode('#', $data['referrer']);
                        $pageReferrer = $pageReferrer[0];
                        $pageReferrer = str_replace(SHIKSHA_HOME, '', $pageReferrer);
                        $signUpFormOptionLib = $this->load->library('studyAbroadCommon/signUpFormOptionLib' );
                        $signUpFormOptionLib->updateConversionData($response['userId'],$data['tracking_keyid'],$pageReferrer);
                    }
               

                if($_COOKIE['AndroidSource'] && $_COOKIE['AndroidSource']=='AndroidWebView'){
                    $this->load->library("common_api/Response");
                    $this->response  = new Response();
                    $this->response->setAuthChecksum($user->getId(), 'dummy@gmail.com','dummy', 'dummy','dummy');
                    $response['checkSum'] = $this->response->getAuthChecksum();
                }

                }
                catch(Exception $e) {
                    error_log($e->getMessage());
                    $response['status'] = 'FAIL';
                    $response['error'] = $e->getMessage();
                }
            }
            else {
                $response['status'] = 'FAIL';
                $response['error'] = 'VALIDATION';
            }
        }
        echo json_encode($response);
    }
    
     /**
     * Function to validate the entered data for Registration
     */ 
	public function validateRegistrationData()
	{
	    $this->load->model('user/usermodel');
	    $userModel = new usermodel;
	    $this->load->helper('security');
            $data = xss_clean($_POST);
            $response = 'FAIL';
		
		/**
         * Check if user with this email id already exists
         */ 
        if($user = $userModel->getUserByEmail($data['email'])) {
            $response = 'USER_ALREADY_EXISTS';
        }
        else { 
            if($this->_validateData($data)) {
				$response = 'SUCCESS';
            }
            else {
                $response = 'FAIL';
            }
        }
        return $response;
	}
	
	/**
	 * Function for storing marketing form data and register user 
	 */ 
	public function registerMarketingFormUser()
	{
		$this->load->model('registration/registrationmodel');
		
		$uniqId = $this->input->post('uniqId',true);
		$this->load->model('mailer/marketingFormmailermodel');
		$marketingFormRegistrationData = $this->marketingFormmailermodel->getMarketingFormRegistrationData($uniqId);
		
		$response = '';
	
		if($marketingFormRegistrationData['id'] && $marketingFormRegistrationData['email'] && $marketingFormRegistrationData['formData']['mobile'] && $marketingFormRegistrationData['status'] == 'otp') {
			if(!OTP_VERIFICATION || $this->registrationmodel->isOTPVerified($marketingFormRegistrationData['email'])) {
				$_POST = $marketingFormRegistrationData['formData'];
				
				$mfid = $this->input->post('mfid',true);
				$_POST['referrer'] = 'MarketingFormMailer#'.$mfid;
				$_POST['registrationSource'] = 'MARKETING_FORM';
				
				if(!$_POST['desiredCourse'] && !$_POST['desiredGraduationLevel']) {
					$_POST['registrationType'] = 'short';
				}
				
				if($_POST['destinationCountry']) {
					$_POST['isStudyAbroad'] = 'yes';
				}
				
				$response = Modules::run('registration/Registration/register');
				$this->marketingFormmailermodel->updateLogStatus($marketingFormRegistrationData['id'],'success');
				setcookie('ci_mobile','',time()-3600,'/',COOKIEDOMAIN);
			}
		}
		
		if(!$response) {
			$response = json_encode(array('status' => 'FAILED'));
		}
		
		echo $response;
	}
	
    /**
     * Update details of an existing user
     */ 
    public function updateUser($isPWACall = false)
    {
        if(!$isPWACall && $_POST['isFBCall'] != 1){
            if(!verifyCSRF()) { return false; }
        }
        
        $this->init();  // not defined  // temp
		
		$this->load->helper('security');
        $data = xss_clean($_POST);
        $len = strlen($data['regFormId']);
        $data['regFormId'] = strip_tags($data['regFormId']);

        if($len!==strlen($data['regFormId']) || $len>6 || preg_match('/[\'^£$%&*()}{@#~?><>,|=+¬]/', $data['regFormId']))
        {
            return;
        }
        
        $response = array();
        
        global $isMobileApp;
        if($isMobileApp){
            $authchecksum = $this->input->server("HTTP_AUTHCHECKSUM");
            
            $apiSecurityLib = $this->load->library("common_api/APISecurityLib");
            
            $userDetails = $apiSecurityLib->decrypt($authchecksum);
            $userDetails = unserialize($userDetails);
            $userId = intval($userDetails['userId']);
            $data['userId'] = (ctype_digit($userDetails['userId']) && $userId > 0)? $userId : 0; 
            $data['context'] = 'unifiedProfile';          

            unset($userDetails);
            unset($apiSecurityLib);
            
        }else{
            if($data['isFBCall'] != 1){
                $loggedInUserData = $this->getLoggedInUserData();
                //exclusively handled for offlineOps panel
                if($loggedInUserData['usergroup'] != 'lead_operator'){
                    $data['userId'] = $loggedInUserData['userId'];
                }
            }

            if(!empty($data['subStream']) || !empty($data['specialization'])){
                $data['subStreamSpecialization']['subStream'] = $data['subStream'];
                $data['subStreamSpecialization']['specialization'] = $data['specialization'];
            }
        }

        if($data['userId']>0){
            $user_response_lib = $this->load->library('response/userResponseIndexingLib');
                        
            $extraData = "{'personalInfo:true'}";
            $user_response_lib->insertInIndexingQueue($data['userId'], $extraData);
        }
        
        /**
         * User must be logged in
         */
        if(!$data['userId']) {
            echo json_encode(array());
            exit();
        }
        
        if($this->_validateData($data)) {
            $this->load->library('user/UserLib');
            $userLib = new UserLib;
            try {
                $data = $this->_preprocessRegistrationData($data);
                $userLib->trackUserInfo($data, $data['userId']);
                $user = $userLib->updateUser($data);
                
                if(!$data['userIdOffline']){ //to prevent auto login in case of offline ops
                    /**
                     * Invalidate user login cache
                     */ 
                    $this->load->library('user/Login_client');
                    $this->login_client->invalidateUserLoginCacheWithUserId($data['userId']);
                    $userLib->loginUser($user);
                    $redirector = new \registration\libraries\PostRegistrationRedirector($data);
                    $redirectURL = $redirector->getRedirectionURL();
                }else{
                    // update status of offline operator table entry
                    $this->load->library('offlineOps/Offline_client');
                    $this->offline_client->updateOfflineOpsTable($data['userId']);
                    // to reload same page
                    $redirectURL = '';
                }
                $response['status'] = 'SUCCESS';
                $response['userId'] = $data['userId'];
                $response['regFormId'] = $data['regFormId'];

                $response['redirectURL'] = $redirectURL;
                if($data['desiredCourse']) {
                    $response['desiredCourse'] = $data['desiredCourse']?$data['desiredCourse']:null;
                    $response['preferredStudyLocation'] = is_array($data['preferredStudyLocation'])?implode(',', $data['preferredStudyLocation']):(is_array($data['preferredStudyLocalityCity'])?implode(',', $data['preferredStudyLocalityCity']):null);
                }

                $abroadCommonLib = $this->load->library('listingPosting/AbroadCommonLib');
                $abroadDesiredCourses = $abroadCommonLib->getAbroadMainLDBCourses();
                $abroadDesiredCourseIds = array_map(function($a){return $a['SpecializationId'];},$abroadDesiredCourses);

                $userCourseLevel = '';
                if($data['isStudyAbroad'] == "yes" || $data['isStudyAbroadFlag'] == "yes"){
                    if(!empty($data['courseLevel'])){
                        $userCourseLevel = $data['courseLevel'];
                    }else if(!empty($data['desiredCourse'])){
                        $userCourseLevel = $data['desiredCourse'];
                    }else if(!empty($data['desiredGraduationLevel'])){
                        $userCourseLevel = $data['desiredGraduationLevel'];
                    }
                    $extraDataForExclusion = array(
                        'tracking_keyid' => $data['tracking_keyid'],
                        'desiredCountry' => $data['destinationCountry']
                    );
                    if(in_array($data['fieldOfInterest'], $abroadDesiredCourseIds)){
                        $extraDataForExclusion['LDBCourseId'] = $data['fieldOfInterest'];
                    }else{
                        $extraDataForExclusion['categoryId'] = $data['fieldOfInterest'];
                    }
                    $extraDataForExclusion['examScoreUpdated']=true;
                }

                $userLib->checkUserForLDBExclusion($user->getId(), 'registration', $data['listingTypeForBrochure'], $data['listingTypeIdForBrochure'],$userCourseLevel,'','',$extraDataForExclusion);

                if(isset($data['registrationStep'])) {
                    $response['registrationStep'] = $data['registrationStep'];
                    echo json_encode($response);
                }
                else {
                    echo json_encode($response);
                }
            }
            catch(Exception $e) {
                echo json_encode(array('status' => 'FAIL', 'error' => $e->getMessage()));
            }
        }
        else {
            echo json_encode(array('status' => 'FAIL', 'error' => 'VALIDATION'));
        }
    }
    
    /**
     * Function to find the Institute based on form data
     */ 
    public function findInstitute()
    {
        $this->load->helper('security');
        $data = xss_clean($_POST);
        $data = $this->_preprocessRegistrationData($data);
        $redirector = new \registration\libraries\PostRegistrationRedirector($data);
        $redirectURL = $redirector->getRedirectionURL();
        $response = array();
        $response['status'] = 'SUCCESS';
        $response['redirectURL'] = $redirectURL;
        echo json_encode($response);
    }
    
    /**
     *  Function to Handle data input for LDB User Pref Log 
     */ 
    public function ldbUserPrefLog() {
	
		$this->load->model('user/usermodel');
        $userModel = new usermodel;
	
		$this->load->helper('security');
        $data = xss_clean($_POST);
        $data = $this->_preprocessRegistrationData($data);
        
        $userId = $data['userId'];
        if(empty($userId)) {
            $userId = $userModel->getUserIdByEmail($data['email']);
        }
        
	$lastInsertId = $userModel->logLDBuserPref(json_encode($data),$userId);
        $redirector = new \registration\libraries\PostRegistrationRedirector($data);
        $redirectURL = $redirector->getRedirectionURL();
        $response = array();
        $response['status'] = 'USER_ALREADY_EXISTS';
        $response['redirectURL'] = $redirectURL;
	$response['trackLDB'] = 'YES';
        $response['lastInsertId'] = $lastInsertId;
        echo json_encode($response);
    }
    
    /**
     * Function to update the LDB User Pref Log.
     */ 
    public function updateldbUserPrefLog() {
		$this->load->model('user/usermodel');
        $userModel = new usermodel;
        $this->load->helper('security');
        $data = xss_clean($_POST);
        $userModel->updateLDBuserPrefLog($data['insertId'], $data['text']);
        echo $data['redirectURL'];
    }
    
    /**
     * Preprocess registration data so that it can be passed to user creation/updation module
     * @param array $data Array having the preprocessing data
     */ 
    private function _preprocessRegistrationData($data)
    {
        /**
         * Preferred study location is a mixed array of cities and states
         * e.g. ['C:45','C:110','S:2','S:45','C:12']
         * Extract cities and states into separate arrays
         */ 
        if($data['context'] == 'mobile') {
            if(count($data['preferredStudyLocation']) && (isset($data['preferredStudyLocation'][0]) && $data['preferredStudyLocation'][0]!='' ) ){
                $preferredStudyLocations = $data['preferredStudyLocation'];
                $preferredStudyLocations = explode(",",$preferredStudyLocations[0]);
            }
            else{
                unset($data['preferredStudyLocation']);
            }
            
            if(count($data['destinationCountry']) && (isset($data['destinationCountry'][0]) && $data['destinationCountry'][0]!='' )){
                $data['destinationCountry'] = explode(",",$data['destinationCountry'][0]);
            }
            else{
                unset($data['destinationCountry']);
            }
        }
        else{
            $preferredStudyLocations = $data['preferredStudyLocation'];
        }

        if(is_array($preferredStudyLocations) && count($preferredStudyLocations)) {
            $data['preferredStudyLocation']['states'] = array();
            $data['preferredStudyLocation']['cities'] = array();
            
            foreach($preferredStudyLocations as $location) {
                list($prefix,$locationId) = explode(':',$location);
                if($prefix == 'S') {
                    $data['preferredStudyLocation']['states'][] = $locationId;
                }
                else {
                    $data['preferredStudyLocation']['cities'][] = $locationId;
                }
            }
        }

        if($data['registrationType'] == 'short') {
            unset($data['desiredCourse']);
            unset($data['categoryId']);
            unset($data['studyAbroad']);
        }
        
        if($data['fieldOfInterest'] == 14) {
            $data['isTestPrep'] = 'yes';
        }
        return $data;
    }
    
    /**
     * Validate registration data
     * Data is validated based on the registration form type submitted
     *
     * @param array $data
     * @return bool
     */ 
    private function _validateData($data)
    {
        return $this->_getForm($data)->validate($data);
    }
    
    /**
     * Get form based on data submitted
     *
     * @param array $data
     * @return object \registration\libraries\Forms\AbstractForm
     */ 
    private function _getForm($data)
    {
        $mmpFormId = (int) $data['mmpFormId'];
        $desiredCourse = $data['desiredCourse'];
        $context = $data['context'];
        $registrationType = $data['registrationType'];
        $secondLayer = $data['secondLayer'];
        $fieldOfInterest = $data['fieldOfInterest'];
        
        if($secondLayer == 1) {
            $userData = $this->getLoggedInUserData();
            return \registration\builders\RegistrationBuilder::getRegistrationForm('SecondLayer',$userData);
        }
        
        if($registrationType == 'short') {
            return \registration\builders\RegistrationBuilder::getLDBForm(array('context' => 'shortRegistration'));        
        }
        
        if($mmpFormId && empty($data['stream'])) {
            return \registration\builders\RegistrationBuilder::getMMPForm(array('mmpFormId' => $mmpFormId,'desiredCourseId' => $desiredCourse));
        }else if($data['isProfilePage'] == 'yes' && $data[
            'sectionType']=='educationalPreferenceSection'){
            return \registration\builders\RegistrationBuilder::getLDBForm(array('courseGroup' => 'nationalProfile','context' => $context));
        }
        
        if($context == 'rmcPage' || $context == 'mobileRmcPage'){
            return \registration\builders\RegistrationBuilder::getLDBForm(array('courseGroup' => 'SAapply','context' => 'rmcPage'));
        }

        if($context == 'unifiedProfile' ){
            if($data['courseGroup'] == 'abroadUnifiedProfile'){
                return \registration\builders\RegistrationBuilder::getLDBForm(array('courseGroup' => 'abroadUnifiedProfile','context' => 'unifiedProfile'));
            }else{
                return \registration\builders\RegistrationBuilder::getLDBForm(array('courseGroup' => 'domesticUnifiedProfile','context' => 'unifiedProfile'));
            }
        }
        if($context=='SASingleRegistrationForm'){
            return \registration\builders\RegistrationBuilder::getLDBForm(array('courseGroup' => 'SASingleRegistration','context' => 'SASingleRegistrationForm'));
        }
		if($context=='editProfileSAMobile'){
            return \registration\builders\RegistrationBuilder::getLDBForm(array('courseGroup' => 'editProfileSAMobile','context' => 'editProfileSAMobile'));
        }
        if($data['isStudyAbroad'] == 'yes') {
            if(STUDY_ABROAD_NEW_REGISTRATION) {
                $courseGroup = 'studyAbroadRevamped';
            }
            else {
                $courseGroup = 'studyAbroad';
            }
            return \registration\builders\RegistrationBuilder::getLDBForm(array('courseGroup' => $courseGroup,'context' => $context));
        }

        if(!empty($data['stream'])){
            return \registration\builders\RegistrationBuilder::getLDBForm(array('courseGroup' => 'nationalDefault','context' => $context));
        }

        return \registration\builders\RegistrationBuilder::getLDBForm(array('desiredCourseId' => $desiredCourse,'context' => $context,'fieldOfInterest' => $fieldOfInterest));
    }
    
    /**
     * Validate registration captcha
     *
     * @param string $securityCode Captcha as filled by user
     */ 
    public function validateSecurityCode()
    {
        $securityCodeVar = $this->input->post('securityCodeVar', true);
        $securityCode = $this->input->post('securityCode', true);
        
        if(verifyCaptcha($securityCodeVar,$securityCode)) {
            echo "correct";
        }
        else {
            echo "incorrect";
        }
    }
    
    /**
     * Get category page landing URL for an LDB user
     *
     * @param integer $userId
     */
    public function getUserCategoryLandingPage($userId)
    {
        $this->load->library('user/UserLib');
        $userLib = new UserLib;
        echo $userLib->getLandingPageURL($userId);
    }
    
    /**
     * Function to add Data(URL) based on the exam name
     */
    public function addExamScore()
    {
        $loggedInUserData = $this->getLoggedInUserData();
        if($loggedInUserData['userId']) {
            $this->load->model('user/usermodel');
			$exam_name = $this->input->post('exam_name', true);
			$exam_score = $this->input->post('exam_score', true);
			$exam_score_type = $this->input->post('exam_score_type', true);
			
            $this->usermodel->addUserEducation($loggedInUserData['userId'],$exam_name,$exam_score,$exam_score_type);
        }
        
        $redirectionMap = array(
            'CMAT' => '/top-mba-colleges-in-india-accepting-cmat-score-rankingpage-2-2-0-0-5822',
            'NMAT' => '/top-mba-colleges-in-india-accepting-nmat-score-rankingpage-2-2-0-0-3275',
            'MAT'  => '/top-mba-colleges-in-india-accepting-mat-score-rankingpage-2-2-0-0-306',
            'CAT'  => '/top-mba-colleges-in-india-accepting-cat-score-rankingpage-2-2-0-0-305',
            'XAT'  => '/top-mba-colleges-in-india-accepting-xat-score-rankingpage-2-2-0-0-309',
            'SNAP'  => '/top-mba-colleges-in-india-accepting-snap-score-rankingpage-2-2-0-0-307'
        );
        $exam_name = $this->input->post('exam_name', true);
        header('Location: '.$redirectionMap[$exam_name]);
    }
    /**
     * Function to load the Download Comfirmation Form
     *
     * @param int $mmpId Marketing Form Id
     */
    public function downloadConfirmation($mmpId)
    {
        $this->load->model('customizedmmp/customizemmp_model');
        $mmpMailerDetails = $this->customizemmp_model->getMMPMailer($mmpId);
        if(trim($mmpMailerDetails['download_confirmation_message'])) {
            $this->load->view('registration/downloadConfirmationMessage',array('msg' => trim($mmpMailerDetails['download_confirmation_message'])));
        }
    }

    /*
    * Function to store/Edit user profile from SA CRM interface
    *  @input :- user details from POST
    */
    public function crmStudentProfile(){     
        
        $this->load->model('user/usermodel');
        $usermodel = new usermodel;

        $this->load->helper('security');
        $data = xss_clean($_POST);

        if(empty($data['userId']) || $data['userId'] < 1){
            return false;
        }

        //$isRMCAbroadUserValidation= Modules::run('shikshaApplyCRM/rmcPosting/RMCAbroadUserValidation');
        $usergroupAllowed   = array('saAdmin','saShikshaApply', 'saAuditor');
        $validity           = $this->checkUserValidation();
        $this->saCMSToolsLib= $this->load->library('saCMSTools/SACMSToolsLib');     
        $isRMCAbroadUserValidation = $this->saCMSToolsLib->cmsAbroadUserValidation($validity, $usergroupAllowed,$noRedirectionButReturn);

        if(empty($isRMCAbroadUserValidation['userid']) || $isRMCAbroadUserValidation['error'] == 'true'){
            $response['status'] = 'FAIL';
            $response['error'] = 'RMC-VALIDATION';
            echo json_encode($response);
        }
        $data['counsellorId'] = $isRMCAbroadUserValidation['userid'];
        $rmcPostingLib = $this->load->library('shikshaApplyCRM/rmcPostingLib');
        $rmcPostingLib->insertAndTrackCMSIntroFields($data);

        //$data['context'] = 'rmcPage';
        if($this->_validateData($data)) { 
            if(!empty($data['appliedCourseId']) && is_array($data['appliedCourseId'])){

                $courseDetails = $usermodel->getAbroadCourseDetails($data['appliedCourseId']);

                $formatData = array();
                $appliedCourseName =array();
                $courseCategory =array();
                $courseSubCategory =array();
                $LDBCourseId =array();
                $universityName = array();

                /* make data in format */
                foreach ($courseDetails as $key => $value) {
                    $formatData[$value['course_id']][] = $value;
                }
                
                /* Incase of multiple LDB_course, give priority to desired courses  1508, 1509, 1510 and many more */
                $abroadCommonLib = $this->load->library('listingPosting/AbroadCommonLib');
                $abroadDesiredCourses = $abroadCommonLib->getAbroadMainLDBCourses();
                $abroadDesiredCourseIds = array_map(function($a){return $a['SpecializationId'];},$abroadDesiredCourses);
                foreach( $formatData as $courseId=> $cdata){
                    foreach($cdata as $key =>$innerdata){
                        if(in_array($innerdata['ldb_course_id'],$abroadDesiredCourseIds)){
                            $formatData[$courseId] = $innerdata;
                            break;
                        }
                        $formatData[$courseId] = $innerdata;
                    }
                }

                foreach($data['appliedCourseId'] as $key => $value){
                    foreach($formatData as $courseId => $courseData){
                        if($courseId == $value){
                            $appliedCourseName[] = $courseData['listing_title'];
                            $courseCategory[] = $courseData['category_id'];
                            $courseSubCategory[] = $courseData['sub_category_id'];
                            $LDBCourseId[] = $courseData['ldb_course_id'];
                            $universityName[] = $courseData['name'];
                        }
                    }
                }

                $data['appliedCourseName'] = $appliedCourseName;
                $data['courseCategory'] = $courseCategory;
                $data['courseSubCategory'] = $courseSubCategory;
                $data['LDBCourseId'] = $LDBCourseId;
                $data['universityName'] = $universityName;
            }

            $usermodel->update($data);
            
            $response['status'] = 'SUCCESS';
            $response['userId'] = $data['userId'];
           
        }else{ 
                $response['status'] = 'FAIL';
                $response['error'] = 'VALIDATION';
        }

        echo json_encode($response);
    }

    public function sendReminderToUnregisteredUser(){

        $this->validateCron();
        $this->load->config('registration/registrationFormConfig');
        $frequency_for_reminders  = $this->config->item('frequency_for_reminders');
        
        foreach ($frequency_for_reminders as $frequency) {
            $this->sendReminderToUser($frequency);
        }    
    }

    public function sendReminderToUser($frequency){

        $this->load->library('registration/RegistrationLib');
        $registrationLib = new RegistrationLib();

        $this->load->model('registration/registrationmodel');
        $registrationmodel = new Registrationmodel();

        $mobile_vfy_reminder = $this->config->item('mobile_vfy_reminder');
        $actionData = array_keys($mobile_vfy_reminder['trackingMap']);
        $frequency_date = date("Y-m-d", strtotime("- $frequency day"));
        
        $users_for_reminder = $registrationmodel->getUsersToSendReminder($frequency_date,$actionData);

        $map_users = $this->sent_verify_users;

        foreach ($users_for_reminder as $data) {
            
            if(!$map_users[$data['user_id']]){
                $user_ids[$data['user_id']] = $data['user_id'];
            }

        }

        if (count($user_ids)== 0) {
            return;
        }


        $user_contact_data =  $registrationmodel->getUserContactData($user_ids);
        $user_contact_data = $this->formatUserContactData($user_contact_data);

        foreach ($users_for_reminder as $user_data) {
            if($map_users[$user_data['user_id']]){
                continue;
            }

            $map_users[$user_data['user_id']] = 1;

            $api_data = array();
            $api_data['mobile']        = $user_contact_data[$user_data['user_id']]['mobile'];
            $api_data['email']         = $user_contact_data[$user_data['user_id']]['email'];
            $api_data['frequency']     = $frequency;
            $api_data['listingType']   = $user_data['listing_type'];
            $api_data['listingId']     = $user_data['listing_id'];
            $api_data['userId']        = $user_data['user_id'];
            $api_data['action']        = $mobile_vfy_reminder['trackingMap'][$user_data['keyname']];
            $api_data['actionMapping'] = $mobile_vfy_reminder['actionMapping'][$api_data['action']];
            $api_data['smsDetails']    = $mobile_vfy_reminder['SMSContent'];
            $api_data['firstName']     = $user_contact_data[$user_data['user_id']]['firstname'];

            if($frequency == 1 && $user_contact_data[$user_data['user_id']]['isdCode'] == '91'){
                $registrationLib->sendMobVerifySMSReminder($api_data);
            }

            $this->sendEmailReminder($api_data);
        }

        $this->sent_verify_users = $map_users;
    }

    private function sendEmailReminder($data){
        if(empty($data['email']) || $data['frequency'] <=0){
            $mailData = 'To Email : '.$toEmail.'  Frequency : '.$frequency;
            mail('teamldb@shiksha.com','Fields are empty while sending Reg reminder email',$data);
            return;
        }
        
        Modules::run('systemMailer/SystemMailer/sendMobileVerificationMailer', $data);
    }

	public function redirectReminderUser($frequency){

        $this->load->config('registration/registrationFormConfig');
        $frequency_for_reminders  = $this->config->item('frequency_for_reminders');

        $campaign = $frequency.'Day';

        if(!in_array($campaign, $frequency_for_reminders)){
            $campaign = 'other';
        }
        
        $utm_campaign   = 'RegistrationReminder_'.$campaign;
        $utm_source     = 'shiksha';
        $utm_medium     = 'sms';

        $redirectUrl    = $url = SHIKSHA_HOME."/shiksha/index?"."utm_source=$utm_source&utm_medium=$utm_medium&utm_campaign=$utm_campaign&showSignUp=1";
        header("Location: $redirectUrl", TRUE, 301);
    }

    function formatUserContactData($user_contact_data){
        $map = array();

        foreach ($user_contact_data as $data) {
            $map[$data['userid']]['email'] = $data['email'];
            $map[$data['userid']]['mobile'] = $data['mobile'];
            $map[$data['userid']]['isdCode'] = $data['isdCode'];
            $map[$data['userid']]['firstname'] = $data['firstname'];
        }
        return $map;
    }

    public function redirectMVReminderUser($encodedData){
        $data = base64_decode($encodedData);
        $data = explode('_', $data);        
        $redirectUrl = '';
        $queryParameters = '';
        if($data[0]>0 && !empty($data[1]) && $data[2]>0 && !empty($data[3]) && $data[4]>0){
            if($data[4]>0){
                $usermodel = $this->load->model('user/usermodel');
                $encodedEmail  = $usermodel->getEncodedEmailByUserId($data[4]);
                if(!empty($encodedEmail)){
                    if($data[1] == 'c'){
                        $this->load->builder("nationalCourse/CourseBuilder");
                        $courseBuilder = new CourseBuilder();
                        $this->courseRepo = $courseBuilder->getCourseRepository();   
                        $courseObj = $this->courseRepo->find($data[2],array('basic_info'));

                        if(is_object($courseObj)){
                            $courseURL = $courseObj->getURL();
                            if(!empty($courseURL)){
                                $redirectUrl = $courseURL;
                                $queryParameters = 'action='.$data[3].'&';
                                if($data[3] == 'cd'){
                                    $queryParameters .= 'scrollTo=contact&';
                                }                                
                            }
                        }
                    }else if($data[1] == 'e'){
                        $this->load->builder('ExamBuilder','examPages');
                        $examBuilder          = new ExamBuilder();
                        $examRepository = $examBuilder->getExamRepository();
                        $groupObj = $examRepository->findGroup($data[2]);
                        if(is_object($groupObj) &&  $groupObj->getExamId()>0){
                            $examId = $groupObj->getExamId();
                            $examBasicObj = $examRepository->find($examId);
                            if(is_object($examBasicObj)){
                                $examURL = $examBasicObj->getUrl();
                                if(!empty($examURL)){
                                    $redirectUrl = $examURL;
                                    $queryParameters = 'actionType='.$data[3].'&';                                
                                }
                            }
                        }
                    }

                    // auto login user
                    $objmailerClient = $this->load->library('MailerClient');
                    $result = $objmailerClient->autoLogin($this->appId,$encodedEmail);
                    setcookie('user',$result,0,'/',COOKIEDOMAIN);
                }
            }
        }

        if(empty($redirectUrl)){
            $redirectUrl = SHIKSHA_HOME.'/shiksha/index';
        }
        
        $queryParameters .= 'utm_source=Shiksha&utm_medium=SMS&utm_campaign=MobileVerificationReminder_'.$data[0].'Day&fromwhere=MobileVerificationMailer';

        $redirectUrl = $redirectUrl.'?'.$queryParameters;
        header('Location: '.$redirectUrl);
    }
}
