<?php

class rateMyChance extends MX_Controller{
    
    function __construct(){
        $this->load->library('responseAbroad/ResponseAbroadLib');
        $this->responseAbroadLib = new ResponseAbroadLib();
        $this->load->library('rateMyChances/ShikshaApplyCommonLib');
        $this->shikshaApplyCommonLib = new ShikshaApplyCommonLib();
    }
    
    function rateMyChancePage($courseId, $additionalData=array()){

        $courseId = (integer) $courseId;
        if(empty($courseId)){
            show_404_abroad();
        }
        global $rmcTrackingCourseId;
        $rmcTrackingCourseId = $courseId;
        $displayData = array();
        $displayData['courseId'] = $courseId;
        $displayData['hideGDPR']=true;
        $this->_setCookieForReferrer($displayData);
        $this->_checkUserDetails($displayData,true);
        $this->load->builder('ListingBuilder','listing');
        $listingBuilder = new ListingBuilder;
        $abroadCourseRepository = $listingBuilder->getAbroadCourseRepository();
        $abroadCourse = $abroadCourseRepository->find($courseId);
        //$this->_checkURLRedirection($abroadCourse,$displayData);
        
        $displayData['courseObj'] = $abroadCourse;
        $displayData['seoDetails'] = $this->_getSeoDetails($abroadCourse);
        
        $userStartTimePrefWithExamsTaken = $this->responseAbroadLib->getUserStartTimePrefWithExamsTaken($displayData['validateuser']);
        $courseData = array( $displayData['courseObj']->getId() => array(
            'desiredCourse' => ($displayData['courseObj']->getDesiredCourseId()?$displayData['courseObj']->getDesiredCourseId():$displayData['courseObj']->getLDBCourseId()),
            'paid'		=> $displayData['courseObj']->isPaid(),
            'name'		=> $displayData['courseObj']->getName(),
            'subcategory'	=> $displayData['courseObj']->getCourseSubCategory(),
            )
        );
        $displayData['desiredCourse'] = ($displayData['courseObj']->getDesiredCourseId()?$displayData['courseObj']->getDesiredCourseId():$displayData['courseObj']->getLDBCourseId());
        $brochureDataObj = array(
            'sourcePage'       				=> 'mobileRmcPage',
            'courseId'         				=> $displayData['courseObj']->getId(),
            'courseName'       				=> $displayData['courseObj']->getName(),
            'universityId'     				=> $displayData['courseObj']->getUniversityId(),
            'universityName'   				=> $displayData['courseObj']->getUniversityName(),
            'userStartTimePrefWithExamsTaken' 		=> $userStartTimePrefWithExamsTaken,
            'destinationCountryId'			=> $displayData['courseObj']->getCountryId(),
            'destinationCountryName'		=> $displayData['courseObj']->getCountryName(),
            'courseData'       				=> base64_encode(json_encode($courseData)),
            'userDesiredCourse'				=> $userStartTimePrefWithExamsTaken['desiredCourse']
        );
        $displayData['brochureDataObj'] 			= $brochureDataObj;
        if(!empty($additionalData)){
            $title = explode(' - ',$additionalData['refererTitle']);
            $displayData['referrerPageTitle'] = $title[0];
            $displayData['referrerPageURL'] = $additionalData['referer'];
        }else if(!empty($_SERVER['HTTP_REFERER'])){
            $cookieVal = json_decode(base64_decode($_COOKIE['rmcRedirect']));
            $displayData['referrerPageTitle'] = $cookieVal->pageTitle;
            $displayData['referrerPageURL'] = $_SERVER['HTTP_REFERER'];
        }else{
            $displayData['referrerPageTitle'] = "Home";
            $displayData['referrerPageURL'] = SHIKSHA_STUDYABROAD_HOME;
        }
        $displayData['hideRegisterLink'] = true;
        $displayData['trackForPages'] = true; //For JSB9 Tracking
        $displayData['hideHeader']       = true;
        $displayData['forResponse']      = true;
        // For Pageview Tracking
        $this-> _prepareTrackingData($displayData,'rmcRegistrationPage');
        if(!is_null($additionalData['viaModuleRun']) && $additionalData['viaModuleRun'] === true){
            return $this->load->view('rateMyChancePage/rmcPageOverview',$displayData,true);
        }else{
            $this->load->view('rateMyChancePage/rmcPageOverview',$displayData);
        }
    }

    private function _prepareTrackingData(&$displayData,$pageIdentifier){
        $subCategoryId = $displayData['courseObj']->getCourseSubCategory();
        $this->load->builder('CategoryBuilder','categoryList');
        $categoryBuilder = new CategoryBuilder;
        $this->categoryRepository = $categoryBuilder->getCategoryRepository();
        $subCategoryObject = $this->categoryRepository->find($subCategoryId);

        $displayData['beaconTrackData'] = array(
            'pageIdentifier' => $pageIdentifier,
            'pageEntityId' => $displayData['courseId'],
            'extraData' => array(
                                    'categoryId' => $subCategoryObject->getparentId(),
                                    'subCategoryId' => $subCategoryId,
                                    'LDBCourseId' => $displayData['courseObj']->getLDBCourseId(),
                                    'countryId' => $displayData['courseObj']->getCountryId(),
                                    'courseLevel' => $displayData['courseObj']->getCourseLevel1Value()
                                )
        );
    }
    
    private function _checkURLRedirection($abroadCourse, &$displayData){
        // get RMC url for given course
        if(!is_object($abroadCourse) || ($abroadCourse instanceof Course) || $abroadCourse->getCourseApplicationDetail() === NULL){
            show_404_abroad();
        }
        $seoUrl = $this->shikshaApplyCommonLib->getRMCUrlFromCourse($abroadCourse);
        if($seoUrl != getCurrentPageURLWithoutQueryParams()){
            redirect($seoUrl,'location',301);
        }
        $displayData['seoUrl'] = $seoUrl;
    }
    
    private function _checkUserDetails(& $displayData,$makeResponseFlag = false){
        $displayData['validateuser'] = $this->checkUserValidation();
        if($displayData['validateuser'] !== 'false') {
            $this->load->model('user/usermodel');
            $usermodel = new usermodel;
            $userId 	= $displayData['validateuser'][0]['userid'];
            if($makeResponseFlag){
                $alreadyAppliedCourses = $this->shikshaApplyCommonLib->getUserRatingsWithNoRatingGivenAsWell($userId);
                if(in_array($displayData['courseId'],array_keys($alreadyAppliedCourses)) || count($alreadyAppliedCourses) >= ABROADRMCLIMIT){
                    show_404_abroad();
                }
                $userStage = $this->shikshaApplyCommonLib->getUserStage($userId);
                if($userStage == 10){
                    show_404_abroad();
                }
                $checkIsValidResponseUser = Modules::run("registration/Forms/isValidAbroadUser",$userId,$displayData['courseId'],'SAapply');
            }
            else{
                $flagForWriteHandle = true;
            }
            if($checkIsValidResponseUser ){

                /* user hasn't visited for more than 21 days by any means */
                $profilePageShown  =$_COOKIE['profilePageShown'];
                $profilePageShown = $this->security->xss_clean($profilePageShown);
                if($profilePageShown == 1){
                    $showSignupFormFlag = false;
                }else{
                    $this->signUpFormLib = $this->load->library('studyAbroadCommon/AbroadSignupLib');
                    $showSignupFormFlag = $this->signUpFormLib->checkIfUserVisitedInXDays($userId, 21);
                }
                if(!$showSignupFormFlag){
                    /*Exclude RMC user from LDB */
                    $this->load->library('user/UserLib');
                    $userLib = new UserLib;
                    // $userLib->excludeRMCUserFromLDB($userId, $displayData['courseId']);

                    $this->_makeResponseAndSubmit($displayData);
                    exit;
                }
            }
            $user 	= $usermodel->getUserById($userId, $flagForWriteHandle);
            if(!is_object($user)){
                $displayData['loggedInUserData'] = false;
            }
            else{
                $name = $user->getFirstName().' '.$user->getLastName();
                $email = $user->getEmail();
                $userFlags = $user->getFlags();
                $isLoggedInLDBUser = $userFlags->getIsLDBUser();
                $displayData['loggedInUserData'] = array('userId' => $userId, 'name' => $name, 'email' => $email, 'isLDBUser' => $isLoggedInLDBUser);
                $pref = $user->getPreference();
                $loc = $user->getLocationPreferences();
                $isLocation = count($loc);
                if(is_object($pref)){
                    $desiredCourse = $pref->getDesiredCourse();
                }else{
                    $desiredCourse = null;
                }
                $displayData['loggedInUserData']['desiredCourse'] = $desiredCourse;
                $displayData['loggedInUserData']['isLocation'] = $isLocation;
                $displayData['userObj'] = $user;
            }
        }
        else {
                $displayData['loggedInUserData'] = false;
        }
    }
    
    private function _getSeoDetails($abroadCourse){
        $seoDetails = array();
        $seoDetails['title'] = "Rate your chances of admission in <course> from <university>, <country> - Study Abroad";
        $seoDetails['description'] = "Get your profile reviewed by an expert Shiksha counselor to know your chances of admission in <course> from <university>, <country>";
        $seoDetails['title'] = str_replace('<country>',$abroadCourse->getCountryName(),str_replace('<course>',$abroadCourse->getName(),str_replace('<university>',$abroadCourse->getUniversityName(),$seoDetails['title'])));
        $seoDetails['description'] = str_replace('<country>',$abroadCourse->getCountryName(),str_replace('<course>',$abroadCourse->getName(),str_replace('<university>',$abroadCourse->getUniversityName(),$seoDetails['description'])));
        return $seoDetails;
    }
    /*
     * function to load rate my chance button wherever required
     */
     public function loadRateMyChanceButton($data =array())
    {
        // load course
        $this->load->builder('ListingBuilder','listing');
        $listingBuilder             = new ListingBuilder;
        $abroadCourseRepository     = $listingBuilder->getAbroadCourseRepository();
        if(!is_null($data['courseObj']) && $data['courseObj'] instanceof AbroadCourse)
        {
            $courseObject = $data['courseObj'];
        }else{
            $this->load->config('listing/studyAbroadListingCacheConfig');
            $courseObjectFieldsRMCButton = $this->config->item('courseObjectFieldsRMCButton');
            $courseObject = $abroadCourseRepository->find($data['courseId'],$courseObjectFieldsRMCButton);
        }
        // var_dump($courseObject);
        $rmcState = -1;
        if(in_array($data['courseId'],array_keys($data['userRmcCourses']))){
            $rmcState = $data['userRmcCourses'][$data['courseId']];
        }
        $seoUrl = $this->shikshaApplyCommonLib->getRMCUrlFromCourse($courseObject);
        $displayData = array(
            'url'      => $seoUrl,
            'sourcePage' => $data['sourcePage'],
            'widget'   => $data['widget'],
            'pageTitle' => base64_encode($data['pageTitle']),
            'rmcState' => $rmcState,
            'courseId' => $data['courseId'],
        );
        
        $cssTextAndData = $this->_getClassAndTextByRating($rmcState);
        $displayData['ratingText'] = $cssTextAndData['ratingText'];
        $displayData['cssIconText'] = $cssTextAndData['cssIconText'];
        $displayData['cssIconClass'] = $cssTextAndData['cssIconClass'];
        $displayData['trackingPageKeyId'] = $data['trackingPageKeyId'];
        $this->load->view('rateMyChancePage/widget/rateMyChance', $displayData);
        
    }
    
     private function _getClassAndTextByRating($rmcState){
        switch($rmcState) {
            case 0: // This is when he has pressed the button before, but hasn't been rated yet. => Awaiting.
                $ratingText = 'under review';
                $cssIconText = '';
                $cssIconClass='under-review-mark';
                break;
            case 1:
                $ratingText = 'Rated Excellent';
                $cssIconText = '&#10004;';
                $cssIconClass='poor-mark';
                break;
            case 2:
                $ratingText = 'Rated Good';
                $cssIconText = '&#10004;';
                $cssIconClass='poor-mark';
                break;
            case 3:
                $ratingText = 'Rated Average';
                $cssIconText = '&#10004;';
                $cssIconClass='poor-mark';
                break;
            case 4:
                $ratingText = 'Rated Poor';
                $cssIconText = '&times;';
                $cssIconClass='poor-mark';
                break;
            case 5:
                $ratingText = 'No Rating Given';
                $cssIconText = '&times;';
                $cssIconClass='poor-mark';
                break;
            default:
                $ratingText = 'Rate my chances';
                $cssIconText = '';
                $cssIconClass='';
                break;
        }
        return array('ratingText'=>$ratingText,'cssIconText'=>$cssIconText,'cssIconClass'=>$cssIconClass);
    }
    
    private function _setCookieForReferrer($displayData){
        $val = array();
        $cookieVal = json_decode(base64_decode($this->input->cookie('rmcRedirect')),true);
        if(is_array($cookieVal))
        {
                $pageTitle = $cookieVal['pageTitle'];
        }
        else{
                $pageTitle = $this->input->cookie('rmcRedirect');
        }
        $val['pageTitle'] = $pageTitle;
        if(empty($val['pageTitle'])){
            $val['pageTitle'] = "Home";
        }
        $val['referrer'] = $_SERVER['HTTP_REFERER'];
       
        if (preg_match("/submission-success/i", $_SERVER['HTTP_REFERER'])) 
        {
             $val['referrer'] = $cookieVal['referrer'];
        } 
        $val['courseId'] = $displayData['courseId'];
        $_COOKIE['rmcRedirect'] = base64_encode(json_encode($val));
        setcookie("rmcRedirect",base64_encode(json_encode($val)),time()+60*60*12,"/",COOKIEDOMAIN);  //For 12 hours; to fill the form
    }
    
    public function successPage(){

        $this->load->library('user_agent');
        $displayData = array();
        $displayData['rmcShowSuccessPageCookieValue'] = $this->input->cookie('rmcShowSuccessPage');
        $displayData['rmcRedirect'] = $this->input->cookie('rmcRedirect');
        $this->_successPageValidateAndRedirect($displayData);
        $this->_checkUserDetails($displayData);
        $userObj = $displayData['userObj'];
        if(!is_object($userObj)){
            header("Location: ".SHIKSHA_STUDYABROAD_HOME);
            exit;
        }
        $userExams = $this->shikshaApplyCommonLib->getAbroadExamsForUserByUserObject($userObj);
        $displayData['userExams'] = implode(', ',$userExams);
        if(count($userExams) == 0){
            //do nothing!
        }else if(count($userExams) == 1){
            $displayData['userExams'].=" Score";
        }else{
            $displayData['userExams'].=" Scores";
        }
        $courseId               = $displayData['courseId'];
        $this->load->builder('ListingBuilder','listing');
        $listingBuilder         = new ListingBuilder;
        $abroadCourseRepository = $listingBuilder->getAbroadCourseRepository();
        $abroadCourse = $abroadCourseRepository->find($courseId);
        $displayData['courseObj'] = $abroadCourse;
        //Show remaining courses count in thank you message
        $result['courses'] = $this->shikshaApplyCommonLib->getUserRmcRatings($userObj->getId());
        $displayData['courseCount'] = count($result['courses']);
        $seoDetails = array();
        $seoDetails['title']        = 'Profile submitted for '.$abroadCourse->getUniversityName().', '.$abroadCourse->getCountryName().' - Study Abroad';
        $seoDetails['description']  = "Interested in applying to more study abroad colleges? Take help from an expert Shiksha counselor.";
        $displayData['seoDetails'] = $seoDetails;
        $displayData['seoUrl']      = SHIKSHA_STUDYABROAD_HOME."/submission-success";
        $displayData['hideRegisterLink'] = true;
        $displayData['trackForPages'] = true; //For JSB9 Tracking
        $displayData['hideHeader']       = true;
        //fetch user enrolment state
        $this->rmcPostingLib = $this->load->library('shikshaApplyCRM/rmcPostingLib');
        $result              =  $this->rmcPostingLib->getStudentEnrolmentForCounselingSession($userObj->getId(),$courseId);
        $displayData['interestedInEnrolmentSession'] ='';
        if(!empty($result))
        {
            $displayData['interestedInEnrolmentSession'] = reset(reset($result));
        }
        //fetch user shortlisted courses
        $this->shortlistListingLib = $this->load->library('listing/ShortlistListingLib');
        $result = $this->shortlistListingLib->fetchIfUserHasShortListedCourses(array('userId'=>$userObj->getId()));
        $displayData['userShortlistedCourses'] =  $result['courseIds'];
        //fetch user rmc courses
        $displayData['userRmcCourses'] = array();
        $shikshaApplyLib = $this->load->library('rateMyChances/ShikshaApplyCommonLib');
        $displayData['userRmcCourses'] = $shikshaApplyLib->getUserRmcRatings($userObj->getId());
         //Generate recommendation for rmc success page 
        /*if($courseId >0){
            $this->abroadListing   = Modules::load('listing/abroadListings');
            $alsoViewedCourses     = $this->abroadListing->getAbroadRecommendations('alsoViewed', $courseId, '', '','', '','','','rateMyChancesSuccessPage','mobileSA',$displayData['returnPageTitle']);          
            $displayData['rateMyChanceCtlr'] = Modules::load('rateMyChancePage/rateMyChance');
            $displayData['alsoViewedCourses'] = $alsoViewedCourses;
        }*/
        //Remove the CookieForBrochureDownload to stop showing the popup when user goes back to course page
        Modules::run('listing/abroadListings/readRemoveCookieForBrochureDownload');
		$displayData['compareLayerTrackingSource'] = 626;
        $displayData['compareButtonTrackingId'] = 663;
		$displayData['compareCookiePageTitle'] = 'Success Page';
        // For Pageview Tracking
        $this-> _prepareTrackingData($displayData,'rmcSuccessPage');
        $this->load->view('rateMyChancePage/successPageOverview',$displayData);
    }
    
    private function _successPageValidateAndRedirect(& $displayData){
        if($this->input->cookie('rmcShowSuccessPage') !== "1"){
            header("Location: ".SHIKSHA_STUDYABROAD_HOME);
            exit;
        }
        $val = json_decode(base64_decode($this->input->cookie("rmcRedirect")),true);
        $signUpLib = $this->load->library("studyAbroadCommon/AbroadSignupLib");
        $data = array();
        $signUpLib->getSignupFormParams($data);
        if(!((integer)$val['courseId'] > 0)){
            show_404_abroad();
        }
        if(empty($val['referrer'])){
            $val['referrer'] = SHIKSHA_STUDYABROAD_HOME;
            $val['pageTitle'] = "Home";
        }
        $displayData['courseId'] = $val['courseId'];
        $displayData['returnUrl'] = $data['customReferer'];
        $displayData['returnPageTitle'] = $data['refererTitle'];
        setcookie('rmcShowSuccessPage', '', time()-3600,"/",COOKIEDOMAIN);
        setcookie('rmcRedirect', '', time()-3600,"/",COOKIEDOMAIN);
    }
    
    private function _makeResponseAndSubmit($displayData){
        $sourcePage = $this->_getPageSourceByReferrer();
        if(!isset($_COOKIE['rmcTrackingPageKey'.$displayData['courseId']])){
            $_COOKIE['rmcTrackingPageKey'.$displayData['courseId']] = 646;
        }
        $url = Modules::run("responseAbroad/ResponseAbroad/createResponseCallForAbroadListings",'course',$displayData['courseId'],$sourcePage);
        setcookie("rmcShowSuccessPage","1",time()+120,"/",COOKIEDOMAIN);
        header("Location: ".reset(json_decode($url,true)));
    }
    
    private function _getPageSourceByReferrer(){
        $ref = $_SERVER['HTTP_REFERER'];
        if(strpos($ref,"-dc1")){
            return "mobileRmcPage_categoryPage";
        }else if(strpos($ref,"-cl1")){
            return "mobileRmcPage_categorypage";
        }else if(strpos($ref,"-courselisting-")){
            return "mobileRmcPage_coursePage";
        }else if(strpos($ref,"shortlisted-courses-page")){
            return "mobileRmcPage_shortlistPage";
        }else{
            return "mobileRmcPage";
        }
    }
    
    /*
     * Purpose: This function returns a list of courses and ratings for each course which the current logged in user has been rated on.
     *          It has been designed to be called via ajax (or modules::run) and echoes the result as a JSON Encoded array.
     * Params : None
     * Author : Rahul Bhatnagar
     */
    public function getUserStageAndRmcCoursesAppliedToByCurrentUser(){
        $validateUser = $this->checkUserValidation();
        $currentCourse = $this->input->post('courseId');
        $userId = 0;
        if($validateUser === "false"){
            echo json_encode(array('courses'=>array(),'stage'=>array(0)));
            return;
        }else{
            $userId = $validateUser[0]['userid'];
        }
        $result = array();
        $result['courses'] = $this->shikshaApplyCommonLib->getUserRatingsWithNoRatingGivenAsWell($userId);
        $result['courseCount'] = count($result['courses']);
        
        if($result['courses'][$currentCourse]==5){
            $result['rating'] = 5;
            $courseId = $this->input->post('courseId');
            $this->load->builder('ListingBuilder','listing');
            $listingBuilder = new ListingBuilder;
            $abroadCourseRepository = $listingBuilder->getAbroadCourseRepository();
            $abroadCourse = $abroadCourseRepository->find($courseId);
            $result['html'] = $this->load->view('commonModule/layers/rmcErrorMessageLayer',array('courseObj'=>$abroadCourse,'rating'=>5,'drawLayer' => true),true);
        }
        if(count($result['courses'])>=ABROADRMCLIMIT ){
            $courseId = $this->input->post('courseId');
            $this->load->builder('ListingBuilder','listing');
            $listingBuilder = new ListingBuilder;
            $abroadCourseRepository = $listingBuilder->getAbroadCourseRepository();
            $abroadCourse = $abroadCourseRepository->find($courseId);
            $result['html'] = $this->load->view('commonModule/layers/rmcErrorMessageLayer',array('courseObj'=>$abroadCourse,'limit'=>true,'drawLayer' => true),true);
        }
        if(count($result['courses'])< ABROADRMCLIMIT)
        {
            $result['stage'] = $this->shikshaApplyCommonLib->getUserStage($userId);
            if($result['stage'] == 10){
                $courseId = $this->input->post('courseId');
                $this->load->builder('ListingBuilder','listing');
                $listingBuilder = new ListingBuilder;
                $abroadCourseRepository = $listingBuilder->getAbroadCourseRepository();
                $abroadCourse = $abroadCourseRepository->find($courseId);
                $result['html'] = $this->load->view('commonModule/layers/rmcErrorMessageLayer',array('courseObj'=>$abroadCourse,'stage'=>true,'drawLayer' => true),true);
            }
        }
        if(empty($result)){
            $result = array();
        }
        echo json_encode($result);
    }

    public function saveUserEnrolmentForCounseling()
    {
        $userId = $this->input->post('userId');
        $courseId = $this->input->post('courseId');
        $this->shikshaApplyCommonLib->saveUserEnrolmentForCounseling($userId,$courseId);
    }
}

?>
