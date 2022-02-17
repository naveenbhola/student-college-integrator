<?php
/*
 * controller class for response generation & related fuctionalities
 */
class responseAbroad extends MX_Controller
{
    // construct
    private $useMessageQueue = true;
    function __construct()
    {
        parent::__construct();
        $this->userStatus               = $this->checkUserValidation();
        $this->responseAbroadLib        = $this->load->library('ResponseAbroadLib');
        $this->abroadListingCommonLib   = $this->load->library('listing/AbroadListingCommonLib');
        $this->createResponseUsingQueue = $this->useMessageQueue;
        $this->showOldForm = false; // we have removed ab testing

        $useMessageQueueQueryParam      = $this->input->get('useQueue');
        
        if(!empty($useMessageQueueQueryParam) && ENVIRONMENT == "production"){
            if($useMessageQueueQueryParam=="true"){
                $this->createResponseUsingQueue = true;
            }
            else if($useMessageQueueQueryParam=="false"){
                $this->createResponseUsingQueue = false;
            }
        }else{
            $this->createResponseUsingQueue = false;
        }
    }

    /*
     * gets the form for download brochure + quick registration
     * returns html of layer
     */
    public function getBrochureDownloadForm($customData = array())
    {
        $pageTitle = $this->input->post("pageTitle");
        // collect data required to prepare the registration form
        $data = $this->responseAbroadLib->collectDataForBrochureForm($this->userStatus);
        if(!empty($customData)){
            $data['conversionType'] = $customData['MISTrackingDetails']['conversionType'];
            $data['keyName'] = $customData['MISTrackingDetails']['keyName'];
        }

        if(!$data['userStartTimePrefWithExamsTaken'])
        {
            // get user preference, education data (to populate when do you plan to start?, exams taken)
            $data['userStartTimePrefWithExamsTaken'] = $this->responseAbroadLib->getUserStartTimePrefWithExamsTaken($this->userStatus);
        }
        $data['consultantRelatedData'] = array();
        //currently Consultant is active on course page only on mobile so we are putting this condition here
        if(!$data['isConsultantAvailable'] && (!$data['mobile'] || ($data['mobile'] && $data['sourcePage']=='course'))){
            //if(!$data['isConsultantAvailable']){
            $data['consultantRelatedData'] = $this->responseAbroadLib->getConsultantData($data,$this->userStatus);
            //_p($data['consultantRelatedData']);die;
        }
        //_p($consultantData);die;
        $userDesiredCourse  = $data['userStartTimePrefWithExamsTaken']['desiredCourse'];
        $isLocation         = $data['userStartTimePrefWithExamsTaken']['isLocation'];
        if(isset($data['userData'][0]['userid'])){
            $courseId = array_keys($data['courseData']);
            if(count($courseId)==1){
                $checkIsValidResponseUser = Modules::run("registration/Forms/isValidAbroadUser",$data['userData'][0]['userid'],$courseId[0],'SAapply');
            }
        }
        $abroadSignupLib = $this->load->library('studyAbroadCommon/AbroadSignupLib');
        $userVisitedBefore21Days = $abroadSignupLib->checkIfUserVisitedInXDays($data['userData'][0]['userid'], 21);
        if($data['sourcePage'] == 'department')
        {
            // required data for courses will be available when download brochure is requested from a department listing page itself...
            //... however when download brochure is requested on department from somewhere else, we compute this
            if(count($data['courseData']) == 0)
            {
                $courses = $this->abroadListingCommonLib->getDepartmentCourses($data['departmentId']);
                // prepare courselist for brochure dropdown
                $courseList = $this->responseAbroadLib->processDepartmentCoursesForBrochureDropdownOnDeptPage($courses);
                $data['courseData'] = $this->responseAbroadLib->getCourseDataForBrochureDownload($courseList);

            }
            $data['universityName'] = $this->abroadListingCommonLib->getUniversityNameOfDepartment($data['departmentId']);

        }
        else if($data['sourcePage'] == 'university' || $data['sourcePage'] == 'university_ranking' || $data['sourcePage'] == 'country_university')
        {
            // required data for courses will be available when download brochure is requested from a university listing page itself...
            //... however when download brochure is requested on university from places like university ranking page, we compute this
            if(count($data['courseData']) == 0)
            {
                $abroadCourseFinderModel = $this->load->model('listing/AbroadCourseFinderModel');
                //$coursesByDept = $abroadCourseFinderModel->getCoursesOfferedByUniversity($data['universityId'  ],'department');
                $courseData = $abroadCourseFinderModel->getCoursesOfferedByMultipleUniversities(array($data['universityId']));
                $courseList = array();
                foreach ($courseData['courses'] as $key=>$course){
                    $courseList[$key]['course_id'] = $course['courseID'];
                    $courseList[$key]['courseTitle'] = $course['courseName'];
                }

//                _p($courseList);

//                foreach($coursesByDept['courses'] as $deptCourses)
//                {	foreach($deptCourses  as $courses)
//                {
//                    foreach($courses as $course)
//                    {
//                        array_push($courseList ,$course);
//                    }
//                }
//                }
                if(count($courseList)==0)
                {
                    $universityCourseBrowseSectionByStream  = $this->abroadListingCommonLib->getUniversityCourses($data['universityId'  ], 'stream');
                    $courseList = $this->responseAbroadLib->processStreamCoursesForBrochureDropdown($universityCourseBrowseSectionByStream);
                }
                $data['courseData'] = $this->responseAbroadLib->getCourseDataForBrochureDownload($courseList);
            } // end if
        }
        else if($data['sourcePage'] == 'course' || $data['sourcePage'] == 'category' || $data['sourcePage'] == 'course_rankingpage_abroad' || $data['sourcePage'] == 'searchPageMobile' || $data['sourcePage'] =='course_ranking')
        {
            /* data in courseData is passed in post because at a category page & course page single course Object is available,
             * while at other pages like recomendations 'n search they are not, so it is better to pass it whenever available & compute when it's not
             */
            // we already have this
            //$data['courseData'] = json_decode(base64_decode($this->input->post('courseData')),TRUE);
            if(empty($data['courseData'])){
                $data['courseData'] = $this->responseAbroadLib->getCourseDataForBrochureDownload($data['courseId'],true);
                unset($data['courseData']['countryId']);
                unset($data['courseData']['countryName']);
            }

        }
        else{ // every case other than category & listing pages
            $countryRequired = true;
            if($data['courseId'] >0){
                $data['courseData'] = $this->responseAbroadLib->getCourseDataForBrochureDownload($data['courseId'],$countryRequired);
                $data['destinationCountryId'] = $data['courseData']['countryId'] ;
                $data['destinationCountryName'] = $data['courseData']['countryName'];
                unset($data['courseData']['countryId']);
                unset($data['courseData']['countryName']);
            }
        }


        // prepare responseSource string for tracking & action type in response creation
        if(in_array($data['sourcePage'],array('course','department','university','university_ranking','course_ranking','country_university'))){
            $pageType   = $data['sourcePage'].'_';
        }
        else if(in_array($data['sourcePage'],array('category','shortlist','savedCoursesPage')) && in_array ($data['widget'], array('request_callback','applicationProcessTab_request_callback')))
        {
            $pageType = $data['sourcePage'].'_';
        }
        // source (for tracking)
        $data['responseSourcePage'] = "response_abroad_".($data['mobile']?'mobile_':'').$pageType.$data['widget'];
        // registration domain
        $registrationDomain = ($data['mobile']?'studyAbroadMobile':'studyAbroad');
        // if user desired course is not available .. (this covers new users as well)
        if(!$userDesiredCourse || !$isLocation || (!$checkIsValidResponseUser))
        {
            // .. complete form will be shown via module run
            echo Modules::run('registration/Forms/showDownloadEbrochureLayer',$registrationDomain, $data);
        }
        // if user desired course is available ,
        else {
            // == course drpdwn is to be shown, call create response via js ...
            if(count($data['courseData'])>1 || (!empty($data['consultantRelatedData']['consultantData'])))
            {
                // .. & hide registration form
                if($checkIsValidResponseUser){
                    $data['showOnlyCourseDropdown'] = true;
                }
                //$data['showOnlyCourseDropdown'] = (count($data['courseData'])>1)?true:false;
                echo Modules::run('registration/Forms/showDownloadEbrochureLayer',$registrationDomain, $data);
            }
            // == course drpdwn is not to be shown, call create response from controller directly
            else{
                //code to handle OTP verification for returning user
                if(isset($_COOKIE['user'])){
                    $cookievalue = explode("|",$_COOKIE['user']);
                    $nregvalue = explode("|",$_COOKIE['nregv']);
                    if(!(isset($_COOKIE['nregv']) && $nregvalue[0] == $cookievalue[0])){
                        $this->load->model('userVerification/verificationmodel');
                        if(!$this->verificationmodel->doesOTPVerified($cookievalue[0])){
                            $data['OTPforReturningUser'] = true;
                            echo Modules::run('registration/Forms/showDownloadEbrochureLayer',$registrationDomain, $data);
                            return;
                        }else if($userVisitedBefore21Days)
                        {
                            echo Modules::run('registration/Forms/showDownloadEbrochureLayer',$registrationDomain, $data);
                            return;
                        }
                    }
                }
                if($data['sourcePage'] == 'course' || $data['sourcePage'] == 'course_ranking')
                {
                    $this->createResponseCallForAbroadListings('course',$data['courseId'],$data['responseSourcePage'],$pageTitle);
                }
                else
                {
                    $courseIds = array_keys($data['courseData']);
                    $this->createResponseCallForAbroadListings('course',$courseIds[0],$data['responseSourcePage'],$pageTitle);
                }
            }
        }
    }

    public function createResponseCallForAbroadListingsAPI($listingType,$listingTypeId,$responseSource,$extraParams = array()){
        $requestHeader = ($_SERVER['HTTP_ORIGIN'] != null) ? $_SERVER['HTTP_ORIGIN'] : SHIKSHA_HOME;
        header("Access-Control-Allow-Origin: ".$requestHeader);
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header('P3P: CP="CAO PSA OUR"'); // Makes IE to support cookies
        header("Content-Type: application/json; charset=utf-8");
        $this->createResponseCallForAbroadListings($listingType,$listingTypeId,$responseSource,$extraParams = array());
    }

    /*
     * Response creation for free, paid both
     * post params : listingType, listingTypeId, source_page
     * return : html for download link layer
     */
    public function createResponseCallForAbroadListings($listingType,$listingTypeId,$responseSource,$extraParams = array()){
        if(isset($extraParams['moduleRunFrom']) && $extraParams['moduleRunFrom'] == 'mailer'){
            $_POST['trackingPageKeyId'] = $extraParams['trackingKey'];
        }

        if(!(is_numeric($listingTypeId) && $listingTypeId > 0) || !$this->userStatus){
            echo json_encode("error");
            exit;
        }
        if($listingType=="scholarship"){
            $data = $this->_getDataForScholarshipResponse($listingTypeId,$responseSource);
        }else{
            $data = $this->_getDataForCourseResponse($listingTypeId,$responseSource);
        }

        if(isset($extraParams['moduleRunFrom']) && $extraParams['moduleRunFrom'] == 'mailer'){
            $data['dataForQueue']['actionType'] = $responseSource;
        }

        if(empty($data)){
            echo json_encode("error");
            exit;
        }
        if(isMobileRequest()){
            $data['dataForQueue']['applicationSource'] = 'mobile';
        }else{
            $data['dataForQueue']['applicationSource'] = 'desktop';
        }
        $sourcePage = $responseSource;
        $insertIds  = $this->responseAbroadLib->addResponseDataToQueue($data['dataForQueue'],$this->createResponseUsingQueue);
        $brochureEmailInsertId = $insertIds['emailTrackingInsertId'];
        if(!$this->createResponseUsingQueue){
            $insertIds['workerId']  = '-2'; // converting response online, messageQ(rabbitMQ) is not used
            $tempLmsTableId = $this->createResponseFromQueue($insertIds, true,true);
            if($tempLmsTableId<=0){
                echo json_encode("error");
                exit;
            }
        }else{
            if($data['dataForQueue']['userId']>0)
            {
                $this->responseAbroadLib->resetProcessedFlag($data['dataForQueue']['userId']);
            }
        }
        // in case of mobile site show success msg // on mobile we show thank you page now
        // if(stripos($sourcePage,'mobile') != FALSE && $sourcePage != "mobileRmcPage"){
        //     $this->_generateResponseDataForMobileNonRMC($data['dataForQueue']['widget']);
        //     return;
        // }

        // skip thank you layer for rmc responses
        if(strpos($sourcePage,'rmcPage') !== FALSE || strpos($sourcePage, "mobileRmcPage") !== FALSE ){
            $this->_generateResponseDataForRMC($data['extraData']['courseObj'],$data['extraData']['user_data']);
            return;
        }
        //as this is running for both course and scholarship
        if(!empty($brochureEmailInsertId)){
            echo json_encode(array('status'=>'success', 'brochureEmailInsertId'=>$brochureEmailInsertId));
        }
        else{
            echo json_encode(array('status'=>'success', 'tempLmsTableId'=>$tempLmsTableId));
        }
        return;
    }


    private function _generateResponseDataForRMC($courseObj,$user_data){
        $rmcLib = $this->load->library('rateMyChances/ShikshaApplyCommonLib');
        $crmLib = $this->load->library('shikshaApplyCRM/rmcPostingLib');

        $rmcLib->addNewUserRating($user_data['userid'],$courseObj->getId());
        // mark this user's contacted state as untouched
        // $userNoteStatusArr[]=array('userId'=>$user_data['userid'],'noteStatus'=>10);// system generated
        // $userContactedState = $crmLib->userContactedStatus($userNoteStatusArr);
        // $this->load->model('shikshaApplyCRM/rmcpostingmodel');
        // $this->rmcPostingModel = new rmcpostingmodel();
        // $this->rmcPostingModel->saveUserContactState(array('counsellorId'=>'3284455','contactState'=>$userContactedState));
		
        $dataForActivityTracking = array();
        $dataForActivityTracking['userId'] = $dataForActivityTracking['addedBy'] = $user_data['userid'];
        $dataForActivityTracking['message'] = "Student rated chances for ".$courseObj->getName()." at ".$courseObj->getUniversityName().", ".$courseObj->getCountryName();
        $dataForActivityTracking['activityType'] = 'studentRmcApplication';
        $dataForActivityTracking['isThisUpdateForStudent'] = 0;
        $crmLib->logRmcActivityForUser($dataForActivityTracking);
        $url = SHIKSHA_STUDYABROAD_HOME."/submission-success";
        echo json_encode(array('url'=>$url));
    }
    private function _generateResponseDataForMobileNonRMC($widget){
        $title = 'Done';
        $message = 'The requested brochure has been mailed to you.';
        echo json_encode(array('widget'=>$widget, 'title'=>$title, 'message'=>$message));
        return;
    }

    public function _getDataForScholarshipResponse($listingTypeId,$sourcePage){
        $trackingPageKeyId= $this->input->post('trackingPageKeyId',true);
        $userId = $this->userStatus[0]['userid'];
        $this->load->builder('scholarshipsDetailPage/scholarshipBuilder');
        $scholarshipBuilder        = new scholarshipBuilder();
        $scholarshipRepository     = $scholarshipBuilder->getScholarshipRepository();
        $sections = array('basic'=>array('scholarshipId','subscriptionType'),'application'=>array('scholarshipBrochureUrl'));
        $scholarshipObj            = $scholarshipRepository->find($listingTypeId,$sections);
        $scholarshipBrochureUrl    = $scholarshipObj->getApplicationData()->getBrochureUrl();
        if(empty($scholarshipObj) || empty($scholarshipBrochureUrl)){
            return array();
        }
        else{
            $dataForQueue['listingTypeId']      = $listingTypeId;
            $dataForQueue['actionType']         = $this->responseAbroadLib->getScholarshipActionTypeByResponse($sourcePage);
            $dataForQueue['MISTrackingId']      = $trackingPageKeyId ;
            $dataForQueue['sourcePage']         = $sourcePage;
            $dataForQueue['userId']             = $userId;
            $dataForQueue['visitorSessionId']   = getVisitorSessionId();
            $dataForQueue['listingType']        = 'scholarship';
            if(empty($dataForQueue['listingTypeId']) || empty($dataForQueue['actionType']) || empty($dataForQueue['MISTrackingId'])
                || empty($dataForQueue['userId']) || empty($dataForQueue['listingType']) || empty($dataForQueue['visitorSessionId'])){
                return array();
            }else{
                $data['dataForQueue'] = $dataForQueue;
            }
            return $data;
        }
    }
    private function _getDataForCourseResponse($listingTypeId,$sourcePage){
        // load listing builder
        $this->load->builder('ListingBuilder','listing');
        $listingBuilder = new ListingBuilder;
        // load repositories get course obj & derive other values
        $this->abroadCourseRepository 	        = $listingBuilder->getAbroadCourseRepository();
        $courseObj  			        = $this->abroadCourseRepository->find($listingTypeId);
        if(empty($courseObj)){
            return array();
        }
        $paid_status 			        = $courseObj->isPaid();
        // set the action type of the response
        $actionType = $this->responseAbroadLib->getActionTypeForAbroadCourseResponse($sourcePage,$paid_status);

        if($this->input->post('trackingPageKeyId')){
            $trackingPageKeyId= $this->input->post('trackingPageKeyId',true);
        }

        else if(isset($_COOKIE['rmcTrackingPageKey'.$listingTypeId])){
            $trackingPageKeyId = $_COOKIE['rmcTrackingPageKey'.$listingTypeId];
            unset($_COOKIE['rmcTrackingPageKey'.$listingTypeId]);
            setcookie('rmcTrackingPageKey'.$listingTypeId, '', time()-3600,"/",COOKIEDOMAIN);
        }

        if(($this->input->post('preRegListingResponse',true) || $this->input->post('shortlistResponse',true))
            && strlen($this->input->post('cookieString',true))) {
            $user_data = $this->checkUserValidation($this->input->post('cookieString',true));
        }
        else {
            $user_data = $this->checkUserValidation();
        }
        $dataForQueue = array();
        $widget = $this->input->post('widget');
        if(!empty($widget)){
            $dataForQueue['widget'] = $widget;
        }
        $userId = $user_data[0]['userid'];
        $dataForQueue['listingTypeId']      = $listingTypeId;
        $dataForQueue['actionType']         = $actionType ;
        $dataForQueue['MISTrackingId']      = $trackingPageKeyId ;
        $dataForQueue['sourcePage']         = $sourcePage;
        $dataForQueue['userId']             = $userId;
        $dataForQueue['visitorSessionId']   = getVisitorSessionId();
        $dataForQueue['listingType']        = 'course';
        $data['dataForQueue'] = $dataForQueue;
        $data['extraData']['courseObj']     =  $courseObj;
        $data['extraData']['user_data']    = $user_data[0];
        return $data;
    }


    public function createResponseFromQueue($inputData,$isCron=false,$returnResponse = false){ //inputData is available when running it through cron, $returnResponse = true for returning response instead of echo
        if(!$isCron){
            $responsesMessageQueueRowId = $this->input->post('messageQueueInsertId',true);
            $emailTrackingRowId         = $this->input->post('emailTrackingInsertId',true);
            $workerId                   = $this->input->post('workerId',true);
        }
        else{
            $responsesMessageQueueRowId = $inputData['messageQueueInsertId'];
            $emailTrackingRowId         = $inputData['emailTrackingInsertId'];
            $workerId                   = $inputData['workerId'];
        }
        //error_log("createResponseUsingQueue Received Data: responsesMessageQueueRowId = $responsesMessageQueueRowId emailTrackingRowId = $emailTrackingRowId workerId = $workerId");
        if(!empty($responsesMessageQueueRowId) && (int)$responsesMessageQueueRowId>0
            && isset($emailTrackingRowId) && isset($workerId)
        ){// emailTrackingRowId may be equal to 0 in case of leadExistsLastDay
            $data = $this->responseAbroadLib->getResponseDataFromQueue($responsesMessageQueueRowId,$workerId);
            // error_log("createResponseUsingQueue Fetched Data From DB". print_r($data,true));
            if(!$data){
                //in case of corrupted data we want the message to be removed from the Rabbit queue
                if($returnResponse){
                    return "-1"; // courroupted templmsId
                }else{
                    echo "success";
                    return;
                }
            }
            
            $this->responseAbroadLib->resetProcessedFlag($data['userId']);

            if($data['listingType']=='course'){
                $tempLmsTableId = $this->_convertResponseFromQueueForCourse($data,$emailTrackingRowId);
            }
            else if($data['listingType']=='scholarship'){
                $tempLmsTableId = $this->_convertResponseFromQueueForScholarship($data,$emailTrackingRowId);
            }
            $this->responseAbroadLib->setResponseProcessed($responsesMessageQueueRowId);
            //error_log("createResponseUsingQueue settingResponseProcessed");
        }
        else{
            $lib = $this->load->library("common/studyAbroadCommonLib");
            //error_log("createResponseUsingQueue corrupted Data Received");
            if($isCron){
                ob_start();
                echo "InputData:";
                var_dump($inputData);
                $output = ob_get_contents();
                ob_end_clean();
            }
            else{
                ob_start();
                echo "postData:";
                var_dump($this->security->xss_clean($_POST));
                $output = ob_get_contents();
                ob_end_clean();
            }
            $lib->selfMailer("Received Corrupted data in createResponseFromQueue function", $output);
        }
        if($returnResponse){
            return $tempLmsTableId;
        }
        echo "success";
        return;
    }

    private function _convertResponseFromQueueForCourse($data,$emailTrackingRowId){
        $data['courseId'] = $data['listingTypeId'];
        if($data['courseId']){
            // load lms client library
            $this->load->library('lmsLib');
            $lms_client_object = new LmsLib();
            $courseObj      = '';
            $universityObj  = '';
            $user_data      = '';
            $listingType    = 'course';
            $listingTypeId  = $data['courseId'];
            $this->responseAbroadLib->getRelatedObjects($data,$courseObj,$universityObj,$user_data);
            //error_log("createResponseUsingQueue Fetched Related Course University Objects");
            $signedInUser   = $user_data;
            $user_data      = $user_data[0];
            $paid_status    = $courseObj->isPaid();
            $sourcePage     = $data['sourcePage'];
            $brochureURL    = $this->abroadListingCommonLib->getCourseBrochureUrl($courseObj->getId());
            $universityBrochureURL = $universityObj->getBrochureLink();
            $sizeData       = $this->_checkBrochureSize($brochureURL,$universityBrochureURL);
            $brochureSize   = $sizeData['brochureSize'];
            $universityBrochureSize   = $sizeData['universityBrochureSize'];
            $attachCourseBrochure     = $sizeData['attachCourseBrochure'];
            $attachUniversityBrochure = $sizeData['attachUniversityBrochure'];
            //check if response was made already a day ago

            $leadExistsFromLastDay = $this->responseAbroadLib->leadExistsLastDay($data['userId'],$courseObj->getId(),$listingType);
            //error_log("createResponseUsingQueue Checked if leadExistsFromLastDay = ".print_r($leadExistsFromLastDay,true));
            $tempLmsTableId = 0;
            $this->load->model('listing/abroadlistingmodel');
            $this->abroadListingModel = new abroadlistingmodel();
            //check if course is paid
            if($paid_status) // response creation for paid course
            {
                $addReqInfoVars  = $this->responseAbroadLib->createResponseDataForPaidCourse($courseObj,$data,$signedInUser);
                //call to insert response for paid course
                $response = $this->createResponseForPaidCourse($signedInUser, $addReqInfoVars, $lms_client_object);
                //  error_log("createResponseUsingQueue Creating response for paid course");
                // get tempLMStable id for last response created by the user
                $tempLmsReqId = $this->abroadListingModel->getLastTempLMSRequestIDForUser($user_data['userid'], $listingType, $listingTypeId);
                if(!empty($tempLmsReqId["id"])){
                    $tempLmsTableId = $tempLmsReqId["id"];
                }
                //error_log("createResponseUsingQueue tempLMSId= $tempLmsTableId");
            }
            else // response creation for free course
            {
                // prepare data to be inserted into response tables for free course
                $data_array  = $this->responseAbroadLib->createResponseDataForFreeCourse($courseObj,$data,$signedInUser);
                // call to insert data
                $response = $lms_client_object->insertResponseForFreeCourse($data_array);
                // error_log("createResponseUsingQueue Creating response for paid course");
                // get tempLMStable id for last response created by the user
                $tempLmsReqId = $this->abroadListingModel->getLastTempLMSRequestIDForUser($user_data['userid'], $listingType, $listingTypeId);
                if(!empty($tempLmsReqId["id"])){
                    $tempLmsTableId = $tempLmsReqId["id"];
                }
                // error_log("createResponseUsingQueue tempLMSId= $tempLmsTableId");
            }

            $abroadLeadAllocationModel = $this->load->model('shikshaApplyCRM/abroadleadallocationmodel');
            $actionTypeMap = $abroadLeadAllocationModel->fetchMultipleTrackingIdDetails(array($data['MISTrackingId']));
            $actionType = $actionTypeMap[$data['MISTrackingId']];
            // if shiksha apply is enabled on the course :
            if($courseObj->getRmcEnabledDetail() !== NULL && $user_data['isdCode'] == '91'){
                $this->responseAbroadLib->recordAndAdduserStage($tempLmsTableId,$user_data,$sourcePage,$actionType);
                //  error_log("createResponseUsingQueue recordAndAdduserStage");
            }

            /************** send brochure mail to user + tracking ******************************/
            $sourcesIneligibleForMail = array('Viewed_Listing','Viewed_Listing_Pre_Reg','courseListingAdmissionProcess','courseListingEligibilityExamGuide','rmcPage','rmcPage_categoryPage','rmcPage_coursePage','rmcPage_shortlistPage','mobileRmcPage','mobileRmcPage_categoryPage','mobileRmcPage_coursePage','mobileRmcPage_shortlistPage');
            if(!$leadExistsFromLastDay && !in_array($sourcePage, $sourcesIneligibleForMail)){
                $dataForMail = $this->responseAbroadLib->getDataForBrochureMail($user_data,$courseObj,$universityObj,$brochureURL,$brochureSize,$attachCourseBrochure,$universityBrochureURL,$universityBrochureSize,$attachUniversityBrochure);
                $tMailQueueId = $this->_sendBrochureMailWithTracking($dataForMail);
                //  error_log("createResponseUsingQueue SendingMail tmailQueueId=$tMailQueueId");
                $this->responseAbroadLib->trackBrochureEmail($courseObj->getId(),$user_data['userid'],$tempLmsTableId,$tMailQueueId,$emailTrackingRowId,'update',$sourcePage);
            }
        }
        return $tempLmsTableId;
    }


    public function _convertResponseFromQueueForScholarship($data,$emailTrackingRowId){
        if($data['listingTypeId']){
            $scholarshipObj = '';
            $userData = '';
            $actionType = $data['actionType'];
            $this->responseAbroadLib->getRelatedObjectsForScholarship($data,$scholarshipObj,$userData);
            $brochureURL = $scholarshipObj->getApplicationData()->getBrochureUrl();
            $applyNowLink = $scholarshipObj->getApplicationData()->getApplyNowLink();
            $sizeData = $this->responseAbroadLib->checkBrochureSizeForMail($brochureURL);
            $leadExistsFromLastDay = $this->responseAbroadLib->leadExistsLastDay($data['userId'],$scholarshipObj->getId(),$data['listingType']);
            $tempLmsTableId = 0;
            $abroadListingModel = $this->load->model('listing/abroadlistingmodel');
            $responseDataArray  = $this->responseAbroadLib->createResponseDataForScholarships($data,$userData);
            $responseDataArray['listing_subscription_type'] = $scholarshipObj->getSubscriptionType();
            $responseGenerationLib  = $this->load->library('responseAbroad/ResponseGenerationLib');
            $tempLmsTableId = $responseGenerationLib->insertResponse($responseDataArray);
            $userData = $userData[0];
            if(empty($tempLmsTableId)){
                return $tempLmsTableId;
            }
            $this->_sendAndTrackScholarshipBrochureMail($leadExistsFromLastDay,$tempLmsTableId,$userData,$scholarshipObj,$brochureURL,$sizeData,$emailTrackingRowId,$actionType,$applyNowLink);
        }
        return $tempLmsTableId;
    }

    private function _sendAndTrackScholarshipBrochureMail($leadExistsFromLastDay,$tempLmsTableId,$userData,$scholarshipObj,$brochureURL,$sizeData,$emailTrackingRowId,$actionType,$applyNowLink){
        if(!$leadExistsFromLastDay){
            $dataForMail = $this->responseAbroadLib->getDataForScholarshipBrochureMail($userData,$scholarshipObj,$brochureURL,$sizeData,$actionType,$applyNowLink);
            $tMailQueueId = $this->_sendScholarshipBrochureMailWithTracking($dataForMail);
            $this->responseAbroadLib->trackBrochureEmail($scholarshipObj->getId(),$userData['userid'],$tempLmsTableId,$tMailQueueId,$emailTrackingRowId,'update');
        }
    }

    private function _sendScholarshipBrochureMailWithTracking($dataForMail){
        $params = array();
        $params['scholarshipName']          = $dataForMail['scholarshipObj']->getName();
        $params['first_name']               = $dataForMail['user_data']['firstname'];
        $params['listing_type']             = 'scholarship';
        $cookie_str                         = $dataForMail['user_data']['cookiestr'];
        $cookie_str_array                   = explode("|", $cookie_str);
        $params['usernameemail']            = $cookie_str_array[0];
        $params['listing_type_id']          = $dataForMail['scholarshipObj']->getId();
        $params['user_id']                  = $dataForMail['user_data']['userid'];
        $params['user_mobile']              = $dataForMail['user_data']['mobile'];
        $params['actionType']               = $dataForMail['actionType'];
        $params['brochureLink']             = $dataForMail['brochureURL'];
        $params['applyNowLink']             = $dataForMail['applyNowLink'];
        $params['brochureSize']             = $dataForMail['brochureSize'];
        $params['attachBrochure']           = $dataForMail['attachBrochure'];
        $params['brochureDownloadLink']     = "";
        if($params['attachBrochure'] && !empty($params['brochureLink'])){
            $attachmentParams = $params;
            $attachmentParams['scholarshipAttachment'] = true;
            $params['scholarship_brochure_attachment_id'] = $this->responseAbroadLib->createBrochureAttachment($attachmentParams, 'CMS');
        }else if(!empty($params['brochureLink'])){
            $this->responseAbroadLib->createBrochureDownloadLinkForMails($params,'scholarship');
        }
        $result = $this->_prepareAndSendMailForBrochure($params);
        $this->load->model('listing/abroadListingModel');
        $this->abroadListingModel = new abroadlistingmodel();
        $tMailQueueId = $this->abroadListingModel->getLastMailQueueId($params['usernameemail']);
        return $tMailQueueId;
    }

    /* get size of brochures from utility helper
     * 1. get raw sizes for both university & course brochures
     * 2. check if combined size is greater than 5 mb
     * 3. decide which one gets to be attached
     * 4. format both accordingly
     */
    private function _checkBrochureSize($brochureURL,$universityBrochureURL){
        $attachCourseBrochure = $attachUniversityBrochure = false;
        // course brochure size
        $brochureSize = getRemoteFileSize($brochureURL, false);
        // university brochure size
        $universityBrochureSize = getRemoteFileSize($universityBrochureURL, false);
        // get total size
        $totalsize = $brochureSize + $universityBrochureSize;

        // check if total size is less than 5mb
        if($totalsize <= 5*1024*1024)
        {
            if($brochureSize > 0)
            {
                $attachCourseBrochure = true;
            }
            if($universityBrochureSize > 0)
            {
                $attachUniversityBrochure = true;
            }
        }
        else // .. greater than 5 mb
        {
            // if course brochure in particular is exceeding 5mb mark or has zero size
            if(
                ($brochureSize > 5*1024*1024) ||
                ($brochureSize == 0 || $brochureSize == -1 ) // -1 is returned as size if there is no brochureurl
            )
            {
                $attachCourseBrochure = false;
            }
            else{
                $attachCourseBrochure = true;
            }
            //now since combined size > 5mb & only one can be attached, check if we are NOT attaching course brochure
            if(!$attachCourseBrochure){
                // if course brochure in particular is exceeding 5mb mark but not zero..
                if(
                    ($universityBrochureSize > 5*1024*1024) ||
                    ($universityBrochureSize == 0 || $universityBrochureSize == -1 )
                )
                {
                    $attachUniversityBrochure = false;
                }
                else{
                    $attachUniversityBrochure = true;
                }
            }
            else { // course brochure has been attached , cant add university brochure now
                $attachUniversityBrochure = false;
            }
        }
        // format raw individual brochure sizes
        $brochureSize 		        = formatFileSize($brochureSize);
        $universityBrochureSize 	= formatFileSize($universityBrochureSize);
        return array('brochureSize'		=> $brochureSize,
            'attachCourseBrochure'	=> $attachCourseBrochure,
            'universityBrochureSize'   => $universityBrochureSize,
            'attachUniversityBrochure' => $attachUniversityBrochure
        );
    }

    /*
     * call to create response for paid courses
     * params : userdata from checkUserValidation, array/object of data required for response creation [, lms client object (it will be created if not passed`)]
     * return : status of response created,
     */
    private function createResponseForPaidCourse($signedInUser,$addReqInfo, $lms_client_object = NULL)
    {
        $appId = 1;
        if($lms_client_object == NULL){
            $this->load->library('lmsLib');
            $lms_client_object = new LmsLib();
        }
        // tempLmsRequest
        $addLeadStatus = $lms_client_object->insertTempLead($appId,$addReqInfo);
        $addReqInfo['userInfo'] = json_encode($signedInUser);
        $addReqInfo['sendMail'] = false;
        $addReqInfo['tempLmsRequest'] = $addLeadStatus['leadId'];
        // tempLMStable
        $addLeadStatus = $lms_client_object->insertLead($appId,$addReqInfo);
        $addLeadStatus['action'] = $addReqInfo['action'];
        return $addLeadStatus;
    }


    /*
     * to send brochure mail to user and track the mail sent in 'listingsBrochureEmailTracking'
     * @params: $dataForMail = array('user_data' => $user_data,
				     'courseObj' => $courseObj,
				     'universityObj' => $universityObj,
				     'brochureURL' => $brochureURL,  // course brochure Url
				     'brochureSize' => $brochureSize,// course brochure size
				     'attachCourseBrochure'=>$attachCourseBrochure, // course brochure attached ?
				     'univBrochureURL' => $universityBrochureURL,  // univ brochure Url
				     'univBrochureSize' => $universityBrochureSize,// univ brochure size
				     'attachUniversityBrochure'=>$attachUniversityBrochure, // univ brochure attached ?
				     'widget' => $widget);
     */
    private function _sendBrochureMailWithTracking($dataForMail)
    {
        // prepare params for mail creation
        $params = array();
        $params['courseName'] 		        = $dataForMail['courseObj']->getName();
        $params['universityName'] 	        = $dataForMail['courseObj']->getUniversityName();
        $params['countryName']		        = $dataForMail['courseObj']->getMainLocation()->getCountry()->getName();
        $params['first_name'] 		        = $dataForMail['user_data']['firstname'];
        $params['listing_type'] 	        = 'course';
        $cookie_str 			        = $dataForMail['user_data']['cookiestr'];
        $cookie_str_array  		        = explode("|", $cookie_str);
        $params['usernameemail'] 	        = $cookie_str_array[0];
        $params['URL']			        = ($dataForMail['courseObj']->getURL() == ""? SHIKSHA_HOME."/listing/abroadListings/courseListing/".$dataForMail['courseObj']->getId():$dataForMail['courseObj']->getURL()) ;
        $params['listing_type_id']	        = $dataForMail['courseObj']->getId();
        $params['university_id']	        = $dataForMail['universityObj']->getId();
        $params['user_id']		        = $dataForMail['user_data']['userid'];
        $params['user_mobile']		        = $dataForMail['user_data']['mobile'];
        $params['widget']		        = $dataForMail['widget'];
        $params['brochureLink']		        = $dataForMail['brochureURL'];
        $params['brochureSize']		        = $dataForMail['brochureSize'];
        $params['attachCourseBrochure']         = $dataForMail['attachCourseBrochure'];
        $params['brochureDownloadLink']	        = ""; // this link will be sent in mail if not attached
        $params['univBrochureLink']		= $dataForMail['univBrochureURL'];
        $params['univBrochureSize']		= $dataForMail['univBrochureSize'];
        $params['attachUniversityBrochure']	= $dataForMail['attachUniversityBrochure'];
        $params['univBrochureDownloadLink']	= ""; // this link will be sent in mail if not attached

        // if none of the brochures are available..
        if($params['brochureSize'] == "0 B" && $params['univBrochureSize'] == "0 B")
        {
            $params['template_type'] 	= 2; //sorry mail
        }
        else{
            $params['template_type'] 	= 4; // attachment / download link
        }

        // prepare & send mail..
        $result = $this->_sendBrochuresViaLinkOrAttachment($params);
        //get last tMailQueue id so that it can be added to mail tracking table
        $this->load->model('listing/abroadListingModel');
        $this->abroadListingModel = new abroadlistingmodel();
        $tMailQueueId = $this->abroadListingModel->getLastMailQueueId($params['usernameemail']);
        return $tMailQueueId ;
    }


    /*
     * function to create & send mail with either attachments or download links or both or a sorry msg :(
     * -SRB
     */
    private function _sendBrochuresViaLinkOrAttachment($params)
    {
        // check if this wasn't a case of no brochures available & a sorry mail is not to be sent..
        if($params['template_type'] != 2)
        { // mail will be sent with brochures either as attachment or as mail
            // if possible to send course as an attachment
            if($params['attachCourseBrochure'])//
            {
                // create attachment from course brochure
                if(!empty($params['brochureLink'])) {
                    $attachmentParams = $params;
                    $attachmentParams['courseAttachment'] = true;
                    $params['course_brochure_attachment_id'] = $this->responseAbroadLib->createBrochureAttachment($attachmentParams, 'CMS');
                }
            }
            // course brochure cant be attached but is available, add a link
            else if($params['brochureSize'] != '0 B')
            {
                $this->responseAbroadLib->createBrochureDownloadLinkForMails($params,'course');
            }
            // if possible to send course as an attachment
            if($params['attachUniversityBrochure'])
            {
                // create attachment from univ brochure
                if(!empty($params['univBrochureLink'])) {
                    $attachmentParams = $params;
                    $attachmentParams['univAttachment'] = true;
                    $params['university_brochure_attachment_id'] = $this->responseAbroadLib->createBrochureAttachment($attachmentParams, 'CMS');
                }
            }
            // course brochure cant be attached but is available, add a link
            else if($params['univBrochureSize'] != '0 B')
            {
                $this->responseAbroadLib->createBrochureDownloadLinkForMails($params,'university');
            }
        }
        //echo "<pre>";var_dump($params);echo "</pre>";
        // now prepare mail content from what attchments/DL links we have ..
        return $this->_prepareAndSendMailForBrochure($params);
    }


    /*
     * function to prepare & send download brochure mails
     */
    private function _prepareAndSendMailForBrochure($params)
    {
        //_p($params);die;
        //$alerts_client = $this->load->library('alerts_client');
        $email_content = $this->responseAbroadLib->getEmailContent($params);

        $params['emailSubject'] = $email_content['subject'];
        $params['emailContent'] = $email_content['content'];
        $params['fromEmail']    = SA_ADMIN_EMAIL;
        // .. when there is any attachment..
        /*if($params['course_brochure_attachment_id'] > 0 || $params['university_brochure_attachment_id'] > 0)
        {
            $mailerData['emailAttachmentFlag']  = 'y';
            $mailerData['emailAttachment']      = array_filter(array($params['course_brochure_attachment_id'] ,$params['university_brochure_attachment_id']));
            //$response = $alerts_client->externalQueueAdd("12",SA_ADMIN_EMAIL,$params['usernameemail'],$email_content['subject'],$email_content['content'],"html",'','y',array($params['course_brochure_attachment_id'] ,$params['university_brochure_attachment_id']));
        }
        /*else // only brochure links are sent or sorry mail is sent
        {
            //$response = $alerts_client->externalQueueAdd("12",SA_ADMIN_EMAIL,$params['usernameemail'],$email_content['subject'],$email_content['content'],"html");
        }*/
        $response = Modules::run('systemMailer/SystemMailer/studyAbroadSendBrochure',$params);

        return $response;
    }


    /*
     * function to send mail & sms to counsellor in case of rms responses
     */
    private function sendMailWithSMSToCounsellor($counsellorMailData)
    {
        $this->load->library('alerts_client');
        $alerts_client = new Alerts_client();
        /************** mail to cunsellor & manager ****************/
        //prepare email content
        $email_content = $this->responseAbroadLib->getCounsellorEmailContent($counsellorMailData);
        //_p($email_content);
        //set subject, content, to email
        $subject 	  = $email_content['subject'];
        $content 	  = $email_content['content'];
        $email	          = $counsellorMailData['counsellor_email'];
        $managerEmail     = $counsellorMailData['counsellor_manager_email'];
        // send the mail
        /*externalQueueAdd($appId,$fromEmail,$toEmail,$subject,$content,$contentType="text",$sendTime="0000-00-00 00:00:00",$attachment='n',$attachmentArray=array(),$ccEmail=null,$bccEmail=null,$fromUserName="Shiksha.com")*/
        $mailResponse	  = $alerts_client->externalQueueAdd("12",SA_ADMIN_EMAIL,$email,$subject,$content,"html",'','n',array(),$managerEmail);
        /************** END : mail to cunsellor & manager ****************/

        if($counsellorMailData['in_working_hours'] && !$counsellorMailData['preventSMS']){
            /********************** sms to counsellor ************************/
            //$smsText = $counsellorMailData['user_name']." (".$counsellorMailData["user_mobile"].") requested a callback :".$counsellorMailData['univ_name']."(course ".$counsellorMailData['course_id'].") at ".$counsellorMailData['sms_response_time'];
            $smsText = "Callback requested on ".$counsellorMailData['univ_name']." (course ".$counsellorMailData['course_id'].") by ".substr($counsellorMailData['user_name'],0,20)." (".$counsellorMailData["user_mobile"].")"." at ".$counsellorMailData['sms_response_time'].".";
            //addSmsQueueRecord($appId,$toSms,$content,$userId,$sendTime="0000-00-00 00:00:00",$smstype="system",$IsRegistration="no")
            $userId = $counsellorMailData['course_client_id'];
            $smsResponse = $alerts_client->addSmsQueueRecord('12',$counsellorMailData['counsellor_mobile'],$smsText,$userId);
            /********************** END : sms to counsellor ************************/
        }
    }

    public function convertPendingResponsesFromDB(){
        if(PHP_SAPI!='cli'){
            echo "Not Accessible through web-browser";
            return;
        }
        $this->load->library("common/jobserver/JobManagerFactory");
        try {
            $jobManager = JobManagerFactory::getClientInstance();
            if ($jobManager) {
                $jobManager->purgeQueue("createAbroadResponse");
                error_log("AbroadResponseCron Purging MessageQueue");
            }
        }catch (Exception $e) {
            error_log("AbroadResponseCron Unable to purge data from rabbit-MQ");
            return;
        }

        error_log("AbroadResponseCron Fetching Data from DB");
        $insertIdsFromDB = $this->responseAbroadLib->getDataForConvertingPendingResponsesFromDB();
        if(count($insertIdsFromDB)==0){
            error_log("AbroadResponseCron No record Found");
            return;
        }
        error_log("AbroadResponseCron Creating Responses");
        foreach ($insertIdsFromDB as $insertData){
            error_log("AbroadResponseCron Creating Response for messageQueueInsertId = " .$insertData['id']);
            $data['messageQueueInsertId']  = $insertData['id'];
            $data['emailTrackingInsertId'] = $insertData['listingsBrochureEmailTrackingId'];
            $data['workerId']              = '-1';
            $this->createResponseFromQueue($data,true);
        }
        error_log("AbroadResponseCron Completed");
    }


    public function correctResponseData(){
        $this->load->model('responseAbroad/response_abroad_model');
        $responseAbroadModel = new response_abroad_model();
        $responseAbroadModel->updateCorruptedResponseData();

    }
    public function getScholarshipResponseSuccessLayer(){
        $userData = $this->checkUserValidation();
        if($userData!=='false'){
            $listingTypeId = $this->input->post('listingTypeId',true);
            $action        = $this->input->post('action',true);
            $title         = $this->input->post('title',true);
            $refr          = $this->input->post('refr',true);
            $this->load->builder('scholarshipsDetailPage/scholarshipBuilder');
            $scholarshipBuilder        = new scholarshipBuilder();
            $scholarshipRepository     = $scholarshipBuilder->getScholarshipRepository();
            $sections = array('basic'=>array('scholarshipId','name','seoUrl'));
            if($action == 'schr_apply'){
                $sections['application'] = array('applyNowLink');
            }
            $scholarshipObj = $scholarshipRepository->find($listingTypeId,$sections);
            $displayData['scholarshipObj'] = $scholarshipObj;
            $displayData['action'] = $action;
            $displayData['email'] = explode('|',$userData[0]['cookiestr'])[0];
            $displayData['title'] = $title;
            $displayData['refr'] = $refr;
            $html =  $this->load->view('/responseAbroad/scholarship/scholarshipResponseSuccesLayerForMobile',$displayData,true);
            if($action =='schr_apply'){
                $response['extUrl'] = $scholarshipObj->getApplicationData()->getApplyNowLink();
            }
            $response['html'] = $html;
            echo json_encode($response);
        }

    }
}//end: class
