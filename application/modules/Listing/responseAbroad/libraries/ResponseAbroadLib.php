<?php
    /*
     * library to contain common user oriented function that get certain data
     * required for registration/ get brochure forms
     */
    class ResponseAbroadLib {
        private $CI;
        private $responseAbroadModel;
        function __construct()
	{
            $this->CI =& get_instance();
            $this->CI->load->model('responseAbroad/response_abroad_model');
            $this->responseAbroadModel = new response_abroad_model();
  	}
        
        public function getResponseSource($sourcePage,$widget,$mobile){
            // prepare responseSource string for tracking & action type in response creation
            if(in_array($sourcePage,array('course','department','university','abroadSearch','countryPage'))){
                $pageType   = $sourcePage.'_';
            }
            if($mobile){
             return "response_abroad_mobile_.".$pageType.$widget;   
            }
            else{
                return "response_abroad_".$pageType.$widget;
            }
            
        }
        // for course responses only, different function for scholarship responses
        public function getActionTypeForAbroadCourseResponse($sourcePage,$paidStatus){
              // set the action type of the response
            if( !$paidStatus ){
                $actionType = "download_brochure_free_course";
	    }
	    else if( in_array($sourcePage, array("response_abroad_category_page","response_abroad_category_page_shortList_tab","response_abroad_mobile_category_page","response_abroad_mobile_search_page")) ){
                $actionType = "Request_E-Brochure";
	    }
	    else{
                $actionType = "GetFreeAlert";
	    }
	    // if response generated from non-listing page points or RMS responses on listing page
	    $newActionType = $this->getActionTypeBySourcePage($sourcePage);
	    if($newActionType != ''){
                $actionType = $newActionType ;
	    }
            // in case of abroad mobile site append _sa_mobile to the action type (incase of viewed response on mobile, the sourcepage would be Viewed_Listing, then also append _sa_mobile)
            if(strpos($sourcePage,'mobile') !== FALSE || (isMobileSite() == 'abroad' && $sourcePage == 'Viewed_Listing')){
                $actionType .= '_sa_mobile';
            }
            return $actionType;
        }
        //function is for adding scholarship paid/free status in action type in future
        public function getScholarshipActionTypeByResponse($sourcePage){ 
            if(stripos($sourcePage, 'apply_now')){
                return 'APPLY_NOW';
            }else if(stripos($sourcePage, 'download_brochure')){
                return 'DOWNLOAD_BROCHURE';
            }
        }

        public function addResponseDataToDBQueue($dataForDBQueue){
            $queueId = $this->responseAbroadModel->addResponseDataToDBQueue($dataForDBQueue);

            $user_response_lib = $this->CI->load->library('response/userResponseIndexingLib');                  
            $eData['action']   = 'sa_new_response';                       
            $extraData         = json_encode($eData);
            $user_response_lib->insertInResponseIndexLog($dataForDBQueue['userId'],$extraData,$queueId);

            return $queueId;
        }
        public function addResponseDataToMessageQueue($dataForMessageQueue){
            $this->CI->load->library("common/jobserver/JobManagerFactory");
            try {
                $jobManager = JobManagerFactory::getClientInstance();
                if ($jobManager) {
                    $jobManager->addBackgroundJob("createAbroadResponse", $dataForMessageQueue);
                }
            }catch (Exception $e) {
                error_log("Unable to connect to rabbit-MQ");
                $lib = $this->CI->load->library("common/studyAbroadCommonLib");
                $lib->selfMailer("Unable to add messages in CreateAbroadResponse Queue", print_r($dataForMessageQueue,true));
            }
        }
        public function addResponseDataToQueue($dataForQueue,$addToMsgQ=true){
            
            $emailTrackingInsertId = 0;
            $sourcesIneligibleForMail = array('Viewed_Listing','Viewed_Listing_Pre_Reg','courseListingAdmissionProcess','courseListingEligibilityExamGuide','rmcPage','rmcPage_categoryPage','rmcPage_coursePage','rmcPage_shortlistPage','mobileRmcPage','mobileRmcPage_categoryPage','mobileRmcPage_coursePage','mobileRmcPage_shortlistPage');
            // as we do not send mail if leadExistsLastDay courseId userId
            if(!in_array($dataForQueue['sourcePage'], $sourcesIneligibleForMail)){
                $emailTrackingInsertId = $this->responseAbroadModel->checkIfRowExistsForLastDay($dataForQueue);
                if(!$emailTrackingInsertId){
                    $emailTrackingInsertId = $this->responseAbroadModel->addRowToBrochureEmailTracking($dataForQueue);
                }
            }
            $dataForQueue['listingsBrochureEmailTrackingId'] = $emailTrackingInsertId;
            $messageQueueInsertId = $this->addResponseDataToDBQueue($dataForQueue);
            
            $dataForMessageQueue['messageQueueInsertId'] = $messageQueueInsertId;
            $dataForMessageQueue['emailTrackingInsertId'] = $emailTrackingInsertId;
            if($addToMsgQ){
                $this->addResponseDataToMessageQueue($dataForMessageQueue);
            }
            
            return $dataForMessageQueue;
        }
        
        public function setResponseProcessed($responsesMessageQueueRowId){
            $this->responseAbroadModel->setResponseProcessed($responsesMessageQueueRowId);
        }
        
        public function getDataForConvertingPendingResponsesFromDB(){
            return $this->responseAbroadModel->getDataForConvertingPendingResponsesFromDB();
        }
        public function getResponseDataFromQueue($tempResponseRowId,$workerId){
            $queueDataFromDB = $this->responseAbroadModel->getResponseDataFromQueue($tempResponseRowId,$workerId);
            return $this->_validateQueueDataFromDB($queueDataFromDB);
        }
        
        private function _validateQueueDataFromDB($queueDataFromDB){
            if(!empty($queueDataFromDB['listingTypeId']) && !empty($queueDataFromDB['listingType']) && !empty($queueDataFromDB['actionType']) && !empty($queueDataFromDB['MISTrackingId']) && !empty($queueDataFromDB['userId']) && !empty($queueDataFromDB['visitorSessionId']) && !empty($queueDataFromDB['sourcePage'])){
                return $queueDataFromDB;
            }
            $lib = $this->CI->load->library("common/studyAbroadCommonLib");
            $lib->selfMailer("Corrupted data in responsesMessageQueueTable", print_r($queueDataFromDB,true));
            $this->responseAbroadModel->setResponseCorrupted($queueDataFromDB);
            return false;
        }
        
        public function getRelatedObjects(&$queueData,&$courseObj,&$universityObj,&$user_data){
            $courseId = $queueData['courseId'];
            $this->CI->load->builder('ListingBuilder','listing');	    
            $listingBuilder = new ListingBuilder;
            // load repositories
            $abroadCourseRepository      = $listingBuilder->getAbroadCourseRepository();
            $abroadUniversityRepository  = $listingBuilder->getUniversityRepository();
            // get course obj & derive other values
            $courseObj  		 = $abroadCourseRepository->find($courseId);
            $universityId		 = $courseObj->getUniversityId();
            $universityObj		 = $abroadUniversityRepository->find($universityId);
            
            
            $this->CI->load->model('user/usermodel');
            $userModel = new UserModel();
            $user_data = $userModel->getUserValidationInfoById($queueData['userId']);
            $user_data[0]['cookiestr'] = $user_data[0]['email'].'|'.$user_data[0]['password'];
        }
        public function getRelatedObjectsForScholarship(&$queueData,&$scholarshipObj,&$userData){
            $this->CI->load->builder('scholarshipsDetailPage/scholarshipBuilder');
            $scholarshipBuilder        = new scholarshipBuilder();
            $scholarshipRepository     = $scholarshipBuilder->getScholarshipRepository();
            $sections = array('basic'=>array('scholarshipId','name','subscriptionType'),'application'=>array('scholarshipBrochureUrl','applyNowLink'));
            $scholarshipObj            = $scholarshipRepository->find($queueData['listingTypeId'],$sections);
            $this->CI->load->model('user/usermodel');
            $userModel = new UserModel();
            $userData = $userModel->getUserValidationInfoById($queueData['userId']);
            $userData[0]['cookiestr'] = $userData[0]['email'].'|'.$userData[0]['password'];
        }
        public function checkBrochureSizeForMail($brochureUrl){
            if(empty($brochureUrl)){
                return array();
            }else{
                $brochureSize = getRemoteFileSize($brochureUrl, false);
                $returnData['attachBrochure'] = false;
                if($brochureSize<=5*1024*1024){ //5MB
                    $returnData['attachBrochure'] = true;
                }
                $returnData['brochureSize']  = formatFileSize($brochureSize);
            }
            return $returnData;
        }

        public function createResponseDataForPaidCourse($courseObj,&$queueData,&$signedInUser){
            $addReqInfoVars                          = array();
            $addReqInfoVars['listing_type_id']       = $queueData['courseId'];
            $addReqInfoVars['listing_type']          = 'course';
            $addReqInfoVars['listing_title']         = htmlspecialchars($courseObj->getName());
            $addReqInfoVars['listing_Url']           = $courseObj->getURL();
            $addReqInfoVars['displayName']           = $signedInUser[0]['displayname'];
            $addReqInfoVars['contact_cell']          = $signedInUser[0]['mobile'];
            $addReqInfoVars['userId']                = $signedInUser[0]['userid'];
            $addReqInfoVars['contact_email']         = $signedInUser[0]['email'];
            $addReqInfoVars['userInfo']              = $signedInUser;
            $addReqInfoVars['action']                = $queueData['actionType'];
            $addReqInfoVars['institute_location_id'] = $courseObj->getMainLocation()->getLocationId();;
            $addReqInfoVars['tracking_page_key']     = $queueData['MISTrackingId'];
            $addReqInfoVars['visitorSessionid']      = $queueData['visitorSessionId'];
            $addReqInfoVars['submit_date']           = $queueData['creationDate'];
            $addReqInfoVars['queue_id']              = $queueData['id'];
            return $addReqInfoVars;
        }
        
        
        public function createResponseDataForFreeCourse($courseObj,&$queueData,&$signedInUser){
            $data_array   = array();
            $data_array['userId']                   = $signedInUser[0]['userid'];
            $data_array['displayName']              = $signedInUser[0]['displayname'];
            $data_array['contact_cell']             = $signedInUser[0]['mobile'];
            $data_array['listing_type']             = 'course';
            $data_array['listing_type_id']          = $queueData['courseId'];
            $data_array['contact_email']            = $signedInUser[0]['email'];
            $data_array['institute_location_id']    = $courseObj->getMainLocation()->getLocationId();
            $data_array['sourcePage']               = $queueData['actionType'];
            $data_array['tracking_page_key']        = $queueData['MISTrackingId'];
            $data_array['visitorSessionid']         = $queueData['visitorSessionId'];
            $data_array['submit_date']              = $queueData['creationDate'];
            $data_array['queue_id']                 = $queueData['id'];
            return $data_array;
        }
        public function createResponseDataForScholarships(&$queueData,&$userData){
            $data_array   = array();
            $data_array['userId']                   = $userData[0]['userid'];
            $data_array['displayName']              = $userData[0]['displayname'];
            $data_array['contact_cell']             = $userData[0]['mobile'];
            $data_array['listing_type']             = 'scholarship';
            $data_array['listing_type_id']          = $queueData['listingTypeId'];
            $data_array['contact_email']            = $userData[0]['email'];
            $data_array['sourcePage']               = $queueData['actionType'];
            $data_array['tracking_page_key']        = $queueData['MISTrackingId'];
            $data_array['visitorSessionid']         = $queueData['visitorSessionId'];
            $data_array['submit_date']              = $queueData['creationDate'];
            $data_array['queue_id']                 = $queueData['id'];
            return $data_array;
        }

        public function recordAndAdduserStage($tempLmsTableId,&$user_data,$sourcePage,$actionType){
            $rmcLib = $this->CI->load->library('rateMyChances/ShikshaApplyCommonLib');
            $rmcData = array();
            $rmcData['tempLMSId']   = $tempLmsTableId;
            $rmcData['userId']      = $user_data['userid'];
            $rmcData['source']      = $sourcePage;
//            $rmcLib->recordResponse($rmcData);
            if(isset($actionType) && $actionType === 'rateMyChance') {
                $rmcLib->checkAndAddUserStage($rmcData['userId']);
            }
        }
        
        public function getDataForBrochureMail($user_data,$courseObj,$universityObj,$brochureURL,$brochureSize,$attachCourseBrochure,$universityBrochureURL,$universityBrochureSize,$attachUniversityBrochure){
            $dataForMail = array('user_data'               => $user_data,
                                'courseObj'                => $courseObj,
                                'universityObj'            => $universityObj,
                                'brochureURL'              => $brochureURL,  // course brochure Url
                                'brochureSize'             => $brochureSize,// course brochure size
                                'attachCourseBrochure'     => $attachCourseBrochure, // course brochure attached ?
                                'univBrochureURL'          => $universityBrochureURL,  // univ brochure Url
                                'univBrochureSize'         => $universityBrochureSize,// univ brochure size
                                'attachUniversityBrochure' => $attachUniversityBrochure, // univ brochure attached ?
                                'widget'                   => $widget);
            return $dataForMail;
        }

        public function getDataForScholarshipBrochureMail($user_data,$scholarshipObj,$brochureURL,$sizeData,$actionType,$applyNowLink){
            $dataForMail = array('user_data'               => $user_data,
                                'scholarshipObj'           => $scholarshipObj,
                                'brochureURL'              => $brochureURL, 
                                'applyNowLink'             => $applyNowLink, 
                                'actionType'               => $actionType,
                                'brochureSize'             => $sizeData['brochureSize'],
                                'attachBrochure'           => $sizeData['attachBrochure']);
            return $dataForMail;
        }
        
        public function trackBrochureEmail($listingTypeId,$userId,$tempLmsTableId,$tMailQueueId,$emailTrackingRowId,$action='insert',$sourcePage=''){
            if($action=='insert'){
                $this->CI->load->model('listing/coursemodel');
                $courseModel = new coursemodel();
                $mailTrackingInfo = array('course_id'	    => $listingTypeId,
                                          'user_id'		    => $userId,
                                          'tempLmsTableId'	    => $tempLmsTableId,
                                          'tMailQueueId'	    => $tMailQueueId,
                                          'source'		    => $sourcePage);
                $courseModel->trackBrochureEmail($mailTrackingInfo);
            }
            else if($action=='update'){
                $this->responseAbroadModel->updataBrochureEmailTracking($emailTrackingRowId,$tempLmsTableId,$tMailQueueId);
            }
        }
        
        
        public function getDownloadBrochureLayer($sourcePage,$listingTypeId,$trackingPageKeyId,$consultantTrackingPageKeyId,$rmcRecoTrackingPageKeyId,$pageTitle,$data){
            // show recommendations after response is generated (with certain exceptions in sources)
            $sourcesIneligibleForThankYouLayer = array("Viewed_Listing", "Viewed_Listing_Pre_Reg", "reco_widget_mailer", 'courseListingAdmissionProcess', 'courseListingEligibilityExamGuide','User_ShortListed_Course','User_ShortListed_Course_sa_mobile','rmcPage','rmcPage_categoryPage','rmcPage_coursePage','rmcPage_shortlistPage','mobileRmcPage','mobileRmcPage_categoryPage','mobileRmcPage_coursePage','mobileRmcPage_shortlistPage');
            if(!in_array($sourcePage, $sourcesIneligibleForThankYouLayer))
            {
                if($this->CI->input->post('shortlistTrackingPageKeyId')){

                    $shortlistTrackingPageKeyId = $this->CI->input->post('shortlistTrackingPageKeyId');    
                }else{
                    $shortlistTrackingPageKeyId = '';    
                }  
                //$alsoViewedRecommendationHTML = $this->getAbroadRecommendations('alsoViewed', $listingTypeId, '', '','downloadBrochureLayer', $sourcePage);
                $alsoViewedRecommendationHTML = Modules::run('listing/abroadListings/getAbroadRecommendations','alsoViewed', $listingTypeId, '', '',$trackingPageKeyId,$consultantTrackingPageKeyId,$shortlistTrackingPageKeyId,$rmcRecoTrackingPageKeyId,'downloadBrochureLayer', $sourcePage, $pageTitle);

                $data['alsoViewedRecommendationHTML'] = $alsoViewedRecommendationHTML;
                // load the download Link Layer to present the brochure's download Link
                echo $this->CI->load->view('/listing/abroad/downloadLinkLayer',$data);
            }
        }
          /*
         * function to colect data from POST,
         * required to populate certain fields (hidden as well) on the get brochure form
         * @params: logged in user details ($this->checkUserValidation();)
         */
        public function collectDataForBrochureForm( $validateuser )
	{
            /*$_POST['sourcePage']='course';
            $_POST['courseId']=197248;
            $_POST['widget']='belly_link';
            $_POST['courseData'] = base64_encode('{"197248":{"desiredCourse":"1510","paid":true,"name":"Bachelor of Science in Electrical Engineering","subcategory":"263"}}');
	    */
	    // prepare data for form
	    $data = array();
            $data['mobile'        ]                 = $this->CI->input->post('mobile'        );
	    $data['sourcePage'    ]                 = $this->CI->input->post('sourcePage'    );
	    $data['courseId'      ]                 = $this->CI->input->post('courseId'      );
	    $data['courseName'    ]                 = $this->CI->input->post('courseName'    );
	    $data['universityId'  ]                 = $this->CI->input->post('universityId'  );
	    $data['universityName']                 = $this->CI->input->post('universityName');
	    $data['departmentId'  ]                 = $this->CI->input->post('departmentId'  );
	    $data['departmentName']                 = $this->CI->input->post('departmentName');
	    $data['widget'        ]                 = $this->CI->input->post('widget'        );
        $data['tracking_page_key']              = $this->CI->input->post('trackingPageKeyId');
        $data['consultantTrackingPageKeyId']    = $this->CI->input->post('consultantTrackingPageKeyId');
            // get required data for courses if page is a univ/dept page
            $data['courseData'    ]                 = json_decode(base64_decode($this->CI->input->post('courseData')),TRUE);
	    // country id & name would not be available in case other than category page, listing pages 
	    $data['destinationCountryId']           = $this->CI->input->post('destinationCountryId');
	    $data['destinationCountryName']         = $this->CI->input->post('destinationCountryName');
	    // details of user logged in 
	    $data['userData'      ]                 = $validateuser;
            // get user preference, education data (to populate when do you plan to start?, exams taken fields)
            $data['userStartTimePrefWithExamsTaken']= json_decode($this->CI->input->post('userStartTimePrefWithExamsTaken'),TRUE);
        $data['isConsultantAvailable']         = $this->CI->input->post('consultantSelectedFlag');
            
	    return $data;
	}
        
        
        /*
         * function to fetch user preference data (time of starting course) & education
         */
        public function getUserStartTimePrefWithExamsTaken($loggedInUserData)
        {
            $data = array();
            $data['desiredCourse'] = false;
            $data['isLocation'] = 0;
            // the extra fields are optional hence we need to show it if not filled, hide otherwise
            if(is_array($loggedInUserData) && is_array($loggedInUserData[0]) && intval($loggedInUserData[0]['userid']))
            {
                $loggedInUserId = intval($loggedInUserData[0]['userid']);
                //get the user model
                $this->CI->load->model('user/usermodel');
                $userModel = new UserModel;
                
                // load user using userid
                if($user = $userModel->getUserById($loggedInUserId))
                {
                    $LDBDetails = $userModel->getLDBuserDetails($loggedInUserId);
                    // get user preference (tUserPref) for the case when user is already logged in
                    $passport = $user->getPassport();
                    $userCity = $user->getCity();
                    // get user preference (tUserPref) for the case when user is already logged in
                    $pref = $user->getPreference();
                    if(is_object($pref)){
                        $desiredCourse = $pref->getDesiredCourse();
                        $abroadSpecialization = $pref->getAbroadSpecialization();
                    }else{
                        $desiredCourse = null;
                    }

                    $loc = $user->getLocationPreferences();
                    $isLocation = count($loc);
                    
                    $userPreferredDestinations = array();
                    foreach($loc  as $location) {
                        $countryId = $location->getCountryId();
                        if($countryId > 2)
                        {
                            array_push($userPreferredDestinations, $countryId);
                        }
                    }
                    if($pref) { // if there is any preference data...
                        $contactByConsultant = $pref->getContactByConsultant();
                        $timeOfStart = $pref->getTimeOfStart(); // ... get time of start
                        if($timeOfStart && $timeOfStart != '0000-00-00 00:00:00') {
                            $showWhenPlanToGo = FALSE;
                        }
                        else{
                            $showWhenPlanToGo = TRUE;
                        }
                    }
                    // get user preference (tUserEducation) for the case when user is already logged in
                    $userEducation = $user->getEducation();
                    if($userEducation) {// if there is any education data... 
                        foreach($userEducation as $education) {// ... get exams
                                $educationAvailable = true;
                                if($education->getLevel() == 'Competitive exam') {
                                    $showExams = FALSE;
                                }
                                else{
                                    $showExams = TRUE;
                                }
                        }
                        if($educationAvailable !== true){
                            $showExams = TRUE;
                        }
                    }
                }
            }
            else{ 
                $showWhenPlanToGo = TRUE;
                $showExams = TRUE;
            }
    
            $data = array();
            $data['contactByConsultant'] = $contactByConsultant;
            $data['userCity'] = $userCity;
            $data['showWhenPlanToGo'] = $showWhenPlanToGo;
            $data['showExams'] = $showExams;
            $data['desiredCourse'] = $desiredCourse;
            $data['abroadSpecialization'] = $abroadSpecialization;
            $data['isLocation'] = $isLocation;
            $data['passport'] = $passport;
            $data['userPreferredDestinations'] = array_unique($userPreferredDestinations);
            $data['LDBDetails'] = $LDBDetails;
            if($timeOfStart){
                $data['timeOfStart'] = ($timeOfStart->format('Y') > date('Y',strtotime('+1 year')) ? "Later":$timeOfStart->format('Y'));
            }
            // whether user will start this year / next / nxt to nxt/ later
            $data['whenPlanToGoValues'] = array(
                    'thisYear' => date('Y',strtotime('+0 year')),
                    'in1Year' => date('Y',strtotime('+1 year')),
                    'later' => 'Later'
            );
            // get exam score ranges , score type, etc.
            $this->getExamsWithRange($data);

            return $data;
        }


        /*
         * function to get study abroad exams, their score types, score ranges
         */
        public function getExamsWithRange(& $data)
        {
            if(empty($this->abroadListingCommonLib) || $this->abroadListingCommonLib == NULL)
            {
                $this->CI->load->library('listing/AbroadListingCommonLib');
                $this->abroadListingCommonLib = new AbroadListingCommonLib();
            }
            $examMasterList = $this->abroadListingCommonLib->getAbroadExamsMasterListFromCache();
            
            $data['studyAbroadExams'] 	        = array();
            $data['studyAbroadExamScores'] 	= array();
            $data['examScoreType'] 		= array();
            $data['rangeStep']		        = array();
            foreach ($examMasterList as $exam)
            {
                $examName = ($exam['exam']=="TOEFL (IBT)"?"TOEFL":$exam['exam']);
                $data['studyAbroadExams'	 ][strtolower($examName)] = $examName;
                $data['studyAbroadExamScores'][$examName] = array($exam['minScore'],$exam['maxScore']);
                $data['examScoreType'][$examName] = $exam['type'];
                $data['rangeStep'][$examName] = $exam['range'];
            }
        }
        
        /*
         * function to process list of courses in a single department such that it can be used to generate a course dropdown on department page
         * @params : courses in a single Department
         */
        function processDepartmentCoursesForBrochureDropdownOnDeptPage($coursesGroupByDepartment = array())
        {
            if(empty($coursesGroupByDepartment)) {
                    return array();
            }
            $courseList = array();
            foreach($coursesGroupByDepartment as $courseLevelwiseCourses) {
                foreach($courseLevelwiseCourses as $course) {
                    array_push($courseList, array('course_id'=>$course['course_id'],'courseTitle'=>$course['course_name']));
                }
            }
            return $courseList;
        }
    
    
        /*
         * function to process list of courses (grouped by stream) under a university such that it can be used to generate a course dropdown on university page
         * @params : courses in a university (streamwise)
         */
        public function processStreamCoursesForBrochureDropdown($coursesGroupByStream = array())
        {
            if(empty($coursesGroupByStream)) {
                    return array();
            }
            $courseList = array();
            foreach($coursesGroupByStream['stream'] as $streamCourses) {					
                    foreach($streamCourses  as $courses) {
                            foreach($courses['courses'] as $course) {									
                                    array_push($courseList, $course);
                            }
                    }
            }
            return $courseList;
        }
        
        
        /*
         * function to get list of courses in all depts of a university to generate a course dropdown
         * @params : courses in a university (deptwise)
         */
        public function processDepartmentCoursesForBrochureDropdown($coursesGroupByDepartment = array())
        {
            if(empty($coursesGroupByDepartment)) {
                    return array();
            }
            $courseList = array();
            foreach($coursesGroupByDepartment['department']['courses'] as $deptCourses) {
                $deptCourseList = $this->processDepartmentCoursesForBrochureDropdownOnDeptPage($deptCourses);
                $courseList = array_merge($courseList,$deptCourseList);
            }
            return $courseList;
        }
        
        
        /*
         * function to get desiredcourse/ldbcourseid, name , paidstatus for a given list of courses
         * @param : $courseList : array of courses,
         * 				$countryRequired (optional) : condition to return country info along with course
         * 				$objectsPassed (optional) : boolean,if we already have course objects, dont have to fetch them again
         */
        public function getCourseDataForBrochureDownload($courseList,$countryRequired = false, $objectsPassed = false)
        {
			if($objectsPassed === true){
				// nothing to do here as courseList is already an array of course objects
			}else{
				if(is_array($courseList) ){
					$courseList = array_map(function ($ar) {return $ar['course_id'];}, $courseList);
				}
				else{
					$courseList = array($courseList);
				}
				//_p($courseList);
				if(count($courseList)==0)
				{
					return array();
				}
				$this->CI->load->builder('ListingBuilder','listing');
				$listingBuilder 			= new ListingBuilder;
				$this->abroadCourseRepository 	= $listingBuilder->getAbroadCourseRepository();
				if(count($courseList)==0)
				{
					return array();
				}
				$courseList = $this->abroadCourseRepository->findMultiple($courseList);
			}
			
			if(empty($this->abroadListingCommonLib) || $this->abroadListingCommonLib == NULL)
			{
				$this->CI->load->library('listing/AbroadListingCommonLib');
				$this->abroadListingCommonLib = new AbroadListingCommonLib();
			}
			$categoryData = $this->abroadListingCommonLib->getCategoryOfAbroadCourse(array_keys($courseList));
			//_p($categoryData);
            $singleCourse = reset($courseList);
            $courseData = array(); 
            foreach($courseList as $courseObj)
            {
                $courseData[$courseObj->getId()] = array(
                                                            'desiredCourse' => ($courseObj->getDesiredCourseId()?$courseObj->getDesiredCourseId():$courseObj->getLDBCourseId()),
                                                            'paid'		=> $courseObj->isPaid(),
                                                            'name'		=> $courseObj->getName(),
                                                            'subcategory'	=> $categoryData[$courseObj->getId()]['subcategoryId']
                                                        );
            }
            if($countryRequired)
            {
                $courseData['countryId'] = $singleCourse->getCountryId();
                $courseData['countryName'] = $singleCourse->getCountryName();
            }
            return $courseData;
        }
        
        /*
         * function to lookup action type based on sourcepage(representation of how/from where response was generated)
         */
        public function getActionTypeBySourcePage($sourcePage)
	{
	    switch($sourcePage)
            {
                case "Viewed_Listing":
					$actionType = "Viewed_Listing";
                    break;
                case "response_abroad_alsoViewed":
					$actionType = "LP_Reco_AlsoviewLayer";
                    break;
				//Code for SA mobile recommendation layer
				case "response_abroad_mobile_course_alsoViewed":
				case "response_abroad_mobile_alsoViewed":
					$actionType = "reco_also_view_layer";
                    break;							
                case "response_abroad_similarInstitutes":
					$actionType = "LP_Reco_SimilarInstiLayer";
                    break;
                case 'response_abroad_overlay_also_viewed_CP':
					$actionType = "CP_Reco_ReqEbrochure";
                    break;
                case 'response_abroad_overlay_also_viewed_LP':
					$actionType = "LP_Reco_ReqEbrochure";
                    break;
                case 'Viewed_Listing_Pre_Reg':
					$actionType = "Viewed_Listing_Pre_Reg";
                    break;
                case 'Mob_Viewed_Listing_Pre_Reg':              $actionType = "Mob_Viewed_Listing_Pre_Reg";
                                                                            break;
                case 'User_ShortListed_Course':
                case 'User_ShortListed_Course_sa_mobile':
                    $actionType = "User_ShortListed_Course";
                    break;
                case 'reco_widget_mailer':
					$actionType = "reco_widget_mailer";
                    break;
                case "response_abroad_overlay_also_viewed_shortlist_page":
					$actionType = "Shortlist_Page_Reco_ReqEbrochure";
                    break;
                case "response_abroad_universityRankingPage": 		// this response was added as per requirement of story LF-1814 :: Abroad Ranking Pages
                    $actionType = "brochureUnivSARanking";
                    break;
                case "response_abroad_courseRankingPage": 			// this response was added as per requirement of story LF-1814 :: Abroad Ranking Pages
					$actionType = "brochureCourseSARanking";
					break;
                case 'response_abroad_overlay_also_viewed_RP':  		// this response was added as per requirement of story LF-1814 :: Abroad Ranking Pages
					$actionType = "RP_Reco_AlsoviewLayer";
					break;
				case 'courseListingAdmissionProcess':
					$actionType = 'LP_AdmissionGuide';
					break;
                case 'courseListingEligibilityExamGuide':
					$actionType = 'LP_EligibilityExam';
                    break;
                case 'response_abroad_mobile_course_request_callback':
                case 'response_abroad_university_request_callback':
                case 'response_abroad_course_request_callback':
                case 'response_abroad_course_applicationProcessTab_request_callback':
                case 'response_abroad_department_request_callback':
				case 'response_abroad_mobile_course_applicationProcessTab_request_callback':
					$actionType = 'Request_Callback'; // RMS responses
					break;
                case 'response_abroad_mobile_category_request_callback':
                case 'response_abroad_category_request_callback':
					$actionType = 'CP_Request_Callback';
					break;
                case 'response_abroad_mobile_shortlist_request_callback':
                case 'response_abroad_shortlist_request_callback':
                case 'response_abroad_savedCoursesPage_request_callback':
					$actionType = 'Shortlist_Request_Callback';
					break;
		case 'rmcPage':
		//case 'rmcPage_categoryPage': for desktop 'Page' isn't suffixed
                //case 'rmcPage_coursePage': for desktop 'Page' isn't suffixed
                case 'rmcPage_category':
				case 'rmcPage_course':
				case 'rmcPage_shortlistPage':
				case 'mobileRmcPage':
				case 'mobileRmcPage_categoryPage':
				case 'mobileRmcPage_coursePage':
				case 'mobileRmcPage_shortlistPage':
                case 'rmcPage_abroadSearch':
                case 'rmcPage_savedCoursesPage':
                case 'rmcPage_signupThankYouPageReco':
                case 'rmcPage_university':
					$actionType = 'rate_my_chances';
					break;
	    }// end of switch
	    return $actionType;
	}
        
        
        /*
         * to check if response was already generated for course
         * params :  user id, listing type, listing type id (usually course)
         * return : result set/false
         */
        public function leadExistsLastDay($userid, $listingTypeId,$listingType )
        {
            $listingmodel = $this->CI->load->model('listing/listingmodel');
            $dbHandleType = 'write';
            $row = $listingmodel->getLastDayLead($userid, $listingTypeId, $listingType, $dbHandleType);
            if(empty($row) || $row["action"] == 'Viewed_Listing' || $row["action"] == 'Viewed_Listing_sa_mobile' || $row["action"] == 'Viewed_Listing_Pre_Reg' || $row["action"] == 'LP_AdmissionGuide' || $row["action"] == 'LP_EligibilityExam')//if any leads exist within past 24 hour then do not send mail
            {
                return false;
            }
            else{
                return $row;
            }
        }
        
        
        /*
         * to create pdf attachments for mails sent to user upon response generation
         */
        public function createBrochureAttachment($params,$brochureOrigin)
        {
            $alerts_client = $this->CI->load->library('alerts_client');
            $misObj = $this->CI->load->library('Ldbmis_client');
            $appId = 1;
            
            if($brochureOrigin == 'CMS') {
                    if($params['courseAttachment']){
                        $type= 'course';
                        $brochureURL = $params['brochureLink'];
						$name = $params['courseName'].($params['universityName']!=''?'_'.$params['universityName']:'');
                    }
                    else if($params['univAttachment']){
                        $type= 'university';
                        $brochureURL = $params['univBrochureLink'];
						$name = $params['universityName'];
                    } else if($params['scholarshipAttachment']){
                        $type= 'scholarship';
                        $brochureURL = $params['brochureLink'];
                        $name = $params['scholarshipName'];
                    }
                    //$filecontent = base64_encode(file_get_contents($brochureURL)); // ????
                    $fileExtension = end(explode(".",$brochureURL));
                            
                    $type_id = $misObj->updateAttachment($appId);

                    $attachmentName = str_replace(" ",'_',$name);
                    $attachmentName = preg_replace("/[^a-zA-Z0-9_]+/", "", $attachmentName);
                    $attachmentName = $attachmentName.".".$fileExtension;
                    $attachmentId = $alerts_client->createAttachment("12",$type_id,$type,'E-Brochure','',$attachmentName,$fileExtension,'true', $brochureURL);

            } else if($brochureOrigin == 'SHIKSHA') {
                    if($params['courseAttachment']){
                        $type= 'course';
                        $Attachment_Brochure_URL = $params['brochureLink'];
                    }
                    else if($params['univAttachment']){
                        $type= 'university';
                        $Attachment_Brochure_URL = $params['univBrochureLink'];
                    }
                            
                    $type_id = $misObj->updateAttachment($appId);

                    $attachmentName = str_replace(" ",'_',$params['instituteName']);
                    $attachmentName = preg_replace("/[^a-zA-Z0-9_]+/", "", $attachmentName);
                    $fileExtension = 'pdf';
                    $attachmentName = $attachmentName.".".$fileExtension;
                    $attachmentId = $alerts_client->createAttachment("12",$type_id,$type,'E-Brochure','',$attachmentName,$fileExtension,'true',$Attachment_Brochure_URL);
            }
            
            return $attachmentId;
        }
    
    
        /*
         * function to create download link for course/ university brochures
         * that are to be sent in mail
         * @params: reference to params array to get few values like userid , course id ,university id & set brochureDownloadLink etc
         * -SRB
         */
        public function createBrochureDownloadLinkForMails(& $params, $listingType)
        {
            // load library to create brochure download link for mails
            $abroadListingCommonLib = $this->CI->load->library('listing/AbroadListingCommonLib');
            if($params['brochureLink'] !="" && ($listingType == 'course' || $listingType == 'scholarship')) // course brochure
            {
                $brochureUrl = $abroadListingCommonLib->getAbroadListingBrochureDownloadURL( array('userId' => $params['user_id'],
                                                                                                         'listingType' => $listingType,
                                                                                                         'listingTypeId' => $params['listing_type_id'])
                                                                                                  );
                $params['brochureDownloadLink']	= $brochureUrl;
            }
            if($params['univBrochureLink'] !="" && $listingType == 'university')
            {
                $brochureUrl = $abroadListingCommonLib->getAbroadListingBrochureDownloadURL( array('userId' => $params['user_id'],
                                                                                                         'listingType' => 'university',
                                                                                                         'listingTypeId' => $params['university_id'] )
                                                                                                  );
                $params['univBrochureDownloadLink']	= $brochureUrl;
            }
            //since params' reference was passed ... no need to return
        }
        
        
        /*
         * taken from multipleApply (prepare mail content)
         */
        public function getEmailContent($params)
        {
                $params['CI'] = $this->CI;
                $response_array = array();
                if($params['listing_type'] == 'course') {
                        $course = $params['courseName'];
                        if(strlen($params['courseName'])>40) {
                                $course = substr($params['courseName'], 0,38);
                                $course = $course."..";
                        }
                        if(in_array ($params['widget'], array('request_callback','applicationProcessTab_request_callback'))){
                            $response_array['subject'] = "Call back request for ".$params['universityName'];
                        }
                        else{
                            $response_array['subject'] = "E-brochure of ".($course).", ".$params['universityName'];
                        }
                        $response_array['content'] = $this->CI->load->view('user/user_response_abroad_course_'.$params['template_type'],$params,true);
                }else if($params['listing_type'] == 'scholarship'){
                    if($params['actionType']=='DOWNLOAD_BROCHURE'){
                        $response_array['subject'] = "Brochure for ".$params['scholarshipName'];
                        $response_array['content'] = $this->CI->load->view('responseAbroad/scholarship/mailDownloadBrochureForScholarship',$params,true);
                    }else if($params['actionType']=='APPLY_NOW'){
                        $response_array['subject'] = "Apply now link for ".$params['scholarshipName'];
                        $response_array['content'] = $this->CI->load->view('responseAbroad/scholarship/mailApplyLinkForScholarship',$params,true);
                    }
                }
                error_log('data_is'.print_r($response_array,true));
                return $response_array;
        }
        
        
        /*
	 * function to fetch user details required for counsellor mails
	 */
	public function getUserDetailsForCousellorMail($loggedInUserData)
	{
	    $data = array();
	    if(is_array($loggedInUserData) && intval($loggedInUserData['userid'])) {
			$loggedInUserId = intval($loggedInUserData['userid']);
			//get the user model
			$this->CI->load->model('user/usermodel');
			$userModel = new UserModel;
			$user = $userModel->getUserById($loggedInUserId, true); // true for getting this info from master, to prevent issues due to lag
			// load user using userid
			if(is_object($user)) {
				$this->CI->load->builder('LocationBuilder','location');
				$locationBuilder            = new LocationBuilder;
				$locationRepository         = $locationBuilder->getLocationRepository();
				$userShortRegData           = $userModel->getAbroadShortRegistrationData($loggedInUserId);
				$userExamScore              = $userShortRegData['examsAbroad'];
				$userFirstName              = $userShortRegData['firstName'];
				$userLastName               = $userShortRegData['lastName'];
				$userRegistrationDate       = $user->getUserCreationDate();
				$userLastLoginTime          = $user->getLastLoginTime();
				$userFlags                  = $user->getFlags();
				$pref                       = $user->getPreference();
				$loc                        = $user->getLocationPreferences();
				$isd                        = $user->getISDCode();
				
				$userPreferredDestinations  = array();
				foreach($loc  as $location) {
					if($location->getCountryId() > 2)
					{
						$country = $locationRepository->findCountry($location->getCountryId());
						$userPreferredDestinations[$country->getId()] = $country->getName();
					}
				}
				if($isd == '91'){
					$city                           = $locationRepository->findCity($user->getCity());
					$userCity                       = $city->getName();
				}
				$userPassport                       = $user->getPassport();
				$userEducation                      = $user->getEducation();
				$data['userRegistrationDate']       = $userRegistrationDate;
				$data['userLastLoginTime']          = $userLastLoginTime;
				$data['isInNDNC']                   = $userFlags->getIsNDNC();
				$data['userPreferredDestinations']  = $userPreferredDestinations;
				$data['userCity']                   = $userCity;
				$data['passport']                   = $userPassport;
				$data['userExamScore']              = $userExamScore;
				$data['userFirstName']              = $userFirstName;
				$data['userLastName']               = $userLastName;
			}
	    }
	    return $data;
	}
        
        
        /*
         * prepare mail content for RMS counsellor mails
         */
        public function getCounsellorEmailContent($params)
        {
            $response_array = array();
            $response_array['subject'] = "User requested a call back (".$params['user_mobile'].") for ".$params['course_name']." in ".$params['univ_name']." (".$params['course_id'].")";
            $response_array['content'] = $this->CI->load->view('user/RMS_counsellor_mail',$params,true);
            //error_log('data_is'.print_r($response_array,true));
            return $response_array;
        }
        
        /**
         * Purpose : Function to check and set the values is displayData array necessary for making the response eg. Viewed_listing
        **/
        public function checkAndSetDataForAutoResponse($course, &$displayData)
        {
                // get user validation data of user
                $validateuser 			= $displayData['validateuser'];
    
                // set the makeAutoResponse if following conditions are made 
                if(($validateuser != "false") && !(in_array($validateuser[0]['usergroup'],array("enterprise","cms","experts","sums","saAdmin","saCMS"))) && $course->isPaid() && !empty($validateuser[0]["mobile"]) && !empty($validateuser[0]["lastname"]))
                {
                        $displayData['makeAutoResponse'] = true;
                        
                        $user_id = $validateuser[0]['userid'];
                        $flag = $this->leadExistsLastDay( $user_id, $course->getId(), 'course' );
        
                        if( $flag ){
                            $displayData['reponse_already_created'] = true;
                        }else{
                            $displayData['reponse_already_created'] = false;
                            if($displayData['loggedInUserData']['isLDBUser'] == 'NO'){
                                $displayData['makeAutoResponse'] = false;
                            }
                        }
                }
        }
        
        public function getConsultantData($param,$userData){
            $this->CI->load->builder('ListingBuilder','listing');
            //_p($param['sourcePage']);die;
            switch ($param['sourcePage']){
                case 'department'   :   
                                        if($param['departmentId'] >0){
                                            $listingBuilder         = new ListingBuilder();
                                            $instituteRepository    = $listingBuilder->getAbroadInstituteRepository();
                                            $listingObj             = $instituteRepository->find($param['departmentId']);
                                            $listingType            = 'department';
                                            $universityId           = $listingObj->getUniversityId();
                                        }
                                        break;
                case 'university_rankingpage_abroad':
                case 'university'   :   
                                        if($param['universityId'] >0){
                                            $listingBuilder         = new ListingBuilder();
                                            $universityRepository   = $listingBuilder->getUniversityRepository();
                                            $listingObj             = $universityRepository->find($param['universityId']);
                                            $listingType            = 'university';
                                            $universityId           = $listingObj->getId();
                                        }
                                        break;
                case 'course'       :   
                                        if($param['courseId'] >0){
                                            $listingBuilder         = new ListingBuilder();
                                            $abroadCourseRepository = $listingBuilder->getAbroadCourseRepository();
                                            $listingObj             = $abroadCourseRepository->find($param['courseId']);
                                            $listingType            = 'course';
                                            $universityId           = $listingObj->getUniversityId();
                                        }
                                        break;
                // 
                default             :   
                                        if($param['courseId'] >0){
                                            $listingBuilder         = new ListingBuilder();
                                            $abroadCourseRepository = $listingBuilder->getAbroadCourseRepository();
                                            $listingObj             = $abroadCourseRepository->find($param['courseId']);
                                            $listingType            = 'course';
                                            $universityId           = $listingObj->getUniversityId();
                                        }
                                        break;
            }
            $this->CI->load->library('listing/AbroadListingCommonLib');
            $abroadListingCommonLib = new AbroadListingCommonLib();
			$excludedCourses = array();
			if(!array_key_exists('consultantData',$param)){
				$consultantData = $abroadListingCommonLib->getConsultantData($listingType, $listingObj);
				$excludedCourses = $abroadListingCommonLib->getExcludedCoursesForUniversity($universityId);
			}
			else{
				$consultantData = $param['consultantData'];
				foreach($consultantData as $consulId=>$consulData)
				{
					if(count($consulData['excludedCourses'])>0){
						$excludedCourses[$consulId] = $consulData['excludedCourses'];
					}
				}
				//_p($excludedCourses);//die;
			}
            $finalConsultantData = array();
            foreach($consultantData as $key=>$data){
                $finalConsultantData;
                foreach($data['regions'] as $regionId=>$regionData){
                    if(in_array($param['courseId'], $excludedCourses[$data['consultantId']])){
                        continue;
                    }
                    $finalConsultantData[$regionId][] = array(  'consultantId'          => $data['consultantId'],
                                                                'consultantName'        => htmlentities(formatArticleTitle($data['consultantName'],51)),
                                                                'consultantProfileUrl'  => $data['consultantProfileUrl'],
                                                                'officeAddress'         => $regionData['office']['officeAddress']
                                                            );
                }
            }
            $regionMappingData  = $this->_getRegionsMapping();
            // when user is logged in and user city prefernce data exist. Return consultant data for user preferrred city.
            if($userData !== 'false' && array_key_exists($regionMappingData[$userData[0]['city']]['regionId'], $finalConsultantData)){
                $finalConsultantData = $finalConsultantData[$regionMappingData[$userData[0]['city']]['regionId']];
                return array(   'consultantData'    => array($regionMappingData[$userData[0]['city']]['regionId'] => $finalConsultantData),
                                'regionMappingData' => $regionMappingData,
                                'userCity'          => $userData[0]['city'],
                                'excludedCourses'   => $excludedCourses
                            );
            }elseif($userData === 'false' && !empty($finalConsultantData)){ //When user is not logged in but consultant Data exist. Return Consultant data for all cities which are available
                return array(   'consultantData'    => $finalConsultantData,
                                'regionMappingData' => $regionMappingData,
                                'userCity'          => 0,
                                'excludedCourses'   => $excludedCourses
                            );
            }/*elseif(!empty($finalConsultantData)){
                return array(   'consultantData'    => $finalConsultantData,
                                'regionMappingData' => $regionMappingData,
                                'userCity'          => 0,
                                'excludedCourses'   => $excludedCourses
                            );
            }*/else{ // in all other cases return blank array notifying no consultant available for this logged in user/non-logged in user 
                return array();
            }

        }
        
        private function _getRegionsMapping(){
            $this->CI->load->library('consultantPosting/ConsultantPostingLib');
            $consultantPosting = new ConsultantPostingLib();
            $regionsData = $consultantPosting->getRegionsMappingData();
            return $regionsData;
        }
        
        public function prepareDataForConsultantEnquiry($userStatus,$tempLmsTableId,$responseSource,$widget,$universityName){
            $data = array();
            $data['userid']         = $userStatus[0]['userid'];
            $data['source']         = $this->_getConsultantEnquirySource($responseSource,$widget);
            list($email,$randomData)= explode("|", $userStatus[0]['cookiestr']);
            $data['email']          = $email;
            $data['mobile']         = $userStatus[0]['mobile'];
            $data['firstName']      = $userStatus[0]['firstname'];
            $data['lastName']       = $userStatus[0]['lastname'];
            //$data['message']        = $this->input->post('message');
            $data['regionId']       = $this->CI->input->post('userRegion');
            $data['consultantId']   = $this->CI->input->post('consultantList');
            $data['tempLmsId']      = $tempLmsTableId;
            $data['university']     = $universityName;
            //$data['tracking_keyid'] = $this->CI->input->post('consultantTrackingPageKeyId');

            if(!is_array($data['consultantId'])){
                $data['consultantId'] = explode(',', $data['consultantId']);
            }
            return $data;
        }
        
        private function _getConsultantEnquirySource($responseSource,$widget) {
            $consultantEnquirySource = '';
            switch($responseSource){
                //  courseListingPage
                case 'response_abroad_course_email_popout'                  :   $consultantEnquirySource = 'course_emailContactDetail';// course top email popout form
                                                                                break;
                case 'response_abroad_course_belly_link'                    :   $consultantEnquirySource = 'course_bellyButton';// course belly button
                                                                                break;
                case 'response_abroad_alsoViewed'                           :   $consultantEnquirySource = 'course_alsoViewedWidget';// course also viewed widget
                                                                                break;
                case 'response_abroad_course_download_form_bottom'          :   $consultantEnquirySource = 'course_inlineForm';// course inline form
                                                                                        break;
                case 'response_abroad_mobile_course_page_link'              :   $consultantEnquirySource = 'course_bellyButton_mobile';
                                                                                        break;
                case 'response_abroad_mobile_course_email_link'             :   $consultantEnquirySource = 'course_EmailLink_mobile';
                                                                                break;    
									    
                //  departmentListingPage
                case 'response_abroad_department_email_popout'              :   $consultantEnquirySource = 'department_emailContactDetail'; // department top email popout form
                                                                                break;
                case 'response_abroad_department_download_form_bottom'      :   $consultantEnquirySource = 'department_inlineForm'; // department inline form
                                                                                break;							
                //  universityListingPage
                case 'response_abroad_university_email_popout'              :   $consultantEnquirySource = 'university_emailContactDetail'; // university email popout form
                                                                                break;
                case 'response_abroad_university_download_form_bottom'      :   $consultantEnquirySource = 'university_inlineForm'; // university inline form
                                                                                break;						
                case 'response_abroad_university_belly_link'                :   $consultantEnquirySource = 'university_bellyButton'; // university belly button 
                                                                                break;
                // categoryPage
                case 'response_abroad_category_page'                        :   $consultantEnquirySource = 'categoryPage_tuple'; // category page
                                                                                break;
                // shorlistPage
                case 'response_abroad_shortlistPage'                       :
                case 'response_abroad_category_page_shortList_tab'          :   $consultantEnquirySource = 'shortlistPage_tuple'; // shortlist page and shortlist tab on category page
                                                                                break;
                // universityRankingPage
                case 'response_abroad_universityRankingPage'                :   $consultantEnquirySource = 'universityRankingPage_tuple'; // university ranking page
                                                                                break;
                // courseRankingPage
                case 'response_abroad_courseRankingPage'                    :   $consultantEnquirySource = 'courseRankingPage_tuple'; // course ranking page
                                                                                break;
                // searchPage
                case 'response_abroad_search'                               :   $consultantEnquirySource = 'searchPage_tuple'; // search page
                                                                                break;
                                                                
                // recommendation responses
                case 'response_abroad_overlay_also_viewed_CP'              :   $consultantEnquirySource = 'categoryPage_recoLayer'; // category page recommendation
                                                                                break;
                case 'response_abroad_overlay_also_viewed_shortlist_page'  :   $consultantEnquirySource = 'shortlistPage_recoLayer'; // shortlist page recommendation
                                                                                break;
                case 'response_abroad_overlay_also_viewed_LP'               :   if(strpos($_SERVER['HTTP_REFERER'], '-univlisting-') != FALSE){
                                                                                    $consultantEnquirySource = 'university_recoLayer'; // university page recommendation
                                                                                }elseif(strpos($_SERVER['HTTP_REFERER'], '-deptlisting-') != FALSE){
                                                                                    $consultantEnquirySource = 'department_recoLayer'; // department page recommendation
                                                                                }elseif(strpos($_SERVER['HTTP_REFERER'], '-courselisting-') != FALSE){
                                                                                    if( $widget == 'overlay_also_viewed_LP'){
                                                                                        $consultantEnquirySource = 'course_alsoViewedWidget_recoLayer'; // course listing page also viwed widget recommendation
                                                                                    }else{
                                                                                        $consultantEnquirySource = 'course_recoLayer'; // course listing page recommendation
                                                                                    }
                                                                                }/*elseif(strpos($_SERVER['HTTP_REFERER'], '-abroadranking') != FALSE){
                                                                                    if(strpos($_SERVER['HTTP_REFERER'], 'universities') != FALSE){
                                                                                        $consultantEnquirySource = 'universityRankingPage_recoLayer'; // university ranking page recommendation
                                                                                    }else{
                                                                                        $consultantEnquirySource = 'courseRankingPage_recoLayer'; // course ranking page recommendation
                                                                                    }
                                                                                }*/else{
                                                                                    $consultantEnquirySource = 'noPage_consutant'; // if undiscovered page is found
                                                                                }
                                                                                break;
                case 'response_abroad_misc'                                 :   $consultantEnquirySource = 'searchPage_recoLayer'; // search page recommendations
                                                                                break;
                case 'response_abroad_overlay_also_viewed_'                 :   $consultantEnquirySource = 'searchPage_recoLayer'; // search page recommendations
                                                                                break;
                case 'response_abroad_overlay_also_viewed_RP'               :   if(strpos($_SERVER['HTTP_REFERER'], 'universities') != FALSE){
                                                                                    $consultantEnquirySource = 'universityRankingPage_recoLayer'; // university ranking page recommendation
                                                                                }else{
                                                                                    $consultantEnquirySource = 'courseRankingPage_recoLayer'; // course ranking page recommendation
                                                                                }
                                                                                break;
                case 'response_abroad_savedCoursesPage_shortlistTab'        :   $consultantEnquirySource = 'savedCoursesPage_shortlistTab';
                                                                                break;
                
                case 'response_abroad_overlay_also_viewed_savedCoursesPage_shortlistTab' :   $consultantEnquirySource = 'savedCoursesPage_shortlistTab_recoLayer';
                                                                                break;
                case 'response_abroad_savedCoursesPage_RMCTab'         :   $consultantEnquirySource = 'savedCoursesPage_RMCTab';
                                                                            break;
                case 'response_abroad_overlay_also_viewed_savedCoursesPage_RMCTab'         :   $consultantEnquirySource = 'savedCoursesPage_RMCTab_recoLayer';
                                                                                                break;
                case 'response_abroad_savedCoursesTab_shortlistTab'         :   $consultantEnquirySource = 'savedCoursesTab_shortlistTab';
                                                                                break; 
                case 'response_abroad_overlay_also_viewed_savedCoursesTab_shortlistTab'         :   $consultantEnquirySource = 'savedCoursesTab_shortlistTab_recoLayer';
                                                                                                    break; 
                case 'response_abroad_savedCoursesTab_RMCTab'               :   $consultantEnquirySource = 'savedCoursesTab_RMCTab';
                                                                                break; 
                case 'response_abroad_overlay_also_viewed_savedCoursesTab_RMCTab'               :   $consultantEnquirySource = 'savedCoursesTab_RMCTab_recoLayer';
                                                                                                    break;                                                                                                                                                                                                              

                default                                                     :   $consultantEnquirySource = 'noPage_consutant'; // if undiscovered page is found
                                                                                break;
            }
            return $consultantEnquirySource;
        }
        
        
        /*
         * Author   :   Abhinav
         * Purpose  :   Add Entry to ConsultantMailQueue if for any course response is made and 
         *              university of that course is mapped to consultant.
         * Checks   :   1. Given course should not be in excluded course list for university-consultant mapping
         *              2. User's city should be in region where consultant is located
         * Params   :   1. courseId (of which response is made)
         *              2. tempLMSTableId (response Id)
         */
        public function sendConsultantMailToUser($courseId, $tempLMSTableId,  AbroadCourseRepository $abroadCourseRepository,$userData){
            //_p($userData);
            $this->CI->load->model('listing/abroadlistingmodel');
            $abroadListingModel = new abroadlistingmodel();
            $result = $abroadListingModel->getResponseCourseIdForCurrentDate($userData['userid']);
            //_p($result);
            $courseIds = array();
            foreach($result as $data){
                $courseIds[] = $data['listing_type_id'];
            }
            $courseDataArray = $abroadCourseRepository->findMultiple($courseIds);
            foreach ($courseDataArray as $key=>$courseObject){
                if($courseDataArray[$courseId] instanceof AbroadCourse && $courseObject instanceof AbroadCourse){
                    if($key != $courseId && $courseDataArray[$courseId]->getUniversityId() == $courseObject->getUniversityId()){
                        return;
                    }
                }
            }
            $consultantRelatedData = $this->getConsultantData(array('sourcePage'=>'course','courseId'=>$courseId),array($userData));
            $userRegion = $consultantRelatedData['regionMappingData'][$userData['city']]['regionId'];
            //echo 'userRegion:'.$userRegion._p($consultantRelatedData);
            $consultantMailQueueData = array();
            $dayDurationForMail = 1;
            foreach ($consultantRelatedData['consultantData'][$userRegion] as $key=>$value){
                $emailToBeProcessedAt = date("Y-m-d", strtotime("+".$dayDurationForMail." days"));
                $consultantMailQueueData[] = array( 'tempLmsId'             => $tempLMSTableId,
                                                    'consultantId'          => $value['consultantId'],
                                                    'dateAdded'             => date('Y-m-d H:i:s'),
                                                    'emailToBeProcessedAt'  => $emailToBeProcessedAt,
                                                    'emailProcessedAt'      => NULL
                                                    );
                $dayDurationForMail += 2;
            }
            //_p($consultantMailQueueData);
            if(count($consultantMailQueueData)){
                $this->CI->load->model('consultantProfile/consultantmodel');
                $consultantModel = new consultantmodel();
                $consultantModel->scheduleConsultantMailer($consultantMailQueueData);
            }
            return;
        }
        
        /*
         * function to reset is_processed whenever a user having extraflag != studyabroad make a response in abroad
         * @params : userId
         */
        public function resetProcessedFlag($userId)
        {
            $this->usermodel = $this->CI->load->model('user/usermodel');
            $userPref = $this->usermodel->getUserEducationLevel($userId);
            if(is_null($userPref['ExtraFlag']) || $userPref['ExtraFlag'] !== 'studyabroad')
            {
                $updatedData = array('ExtraFlag = "studyabroad"','is_processed="no"');
                $this->usermodel->updateUserPrefData($userId, $updatedData);
            }
        }
    } // end : class
    
?>