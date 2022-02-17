<?php

class rateMyChances extends MX_Controller{
    
    function __construct(){
        $this->load->library('responseAbroad/ResponseAbroadLib');
        $this->responseAbroadLib = new ResponseAbroadLib();
        $this->load->library('rateMyChances/ShikshaApplyCommonLib');
        $this->shikshaApplyCommonLib = new ShikshaApplyCommonLib();
    }
    
    function rateMyChancesHomepage($courseId){
        redirect(SHIKSHA_STUDYABROAD_HOME.'/signup','location',301);
        $courseId = (integer) $courseId;
        if(empty($courseId)){
            show_404_abroad();
        }
        global $rmcTrackingCourseId;
        $rmcTrackingCourseId = $courseId;
        $displayData = array();
        $displayData['courseId'] = $courseId;
        $this->_setCookieForReferrer($displayData);
        $this->_checkUserDetails($displayData,true);
        $this->load->builder('ListingBuilder','listing');
        $listingBuilder = new ListingBuilder;
        $abroadCourseRepository = $listingBuilder->getAbroadCourseRepository();
        $abroadCourse = $abroadCourseRepository->find($courseId);
        $this->_checkURLRedirection($abroadCourse,$displayData);
        
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
            'sourcePage'       				=> 'rmcPage',
            'courseId'         				=> $displayData['courseObj']->getId(),
            'courseName'       				=> $displayData['courseObj']->getName(),
            'universityId'     				=> $displayData['courseObj']->getUniversityId(),
            'universityName'   				=> $displayData['courseObj']->getUniversityName(),
            'userStartTimePrefWithExamsTaken' 		=> $userStartTimePrefWithExamsTaken,
            'destinationCountryId'			=> $displayData['courseObj']->getCountryId(),
            'destinationCountryName'			=> $displayData['courseObj']->getCountryName(),
            'courseData'       				=> base64_encode(json_encode($courseData)),
            'userDesiredCourse'				=> $userStartTimePrefWithExamsTaken['desiredCourse']
        );
        $displayData['brochureDataObj'] 			= $brochureDataObj;
        if(!empty($_SERVER['HTTP_REFERER'])){
            $cookieVal = json_decode(base64_decode($_COOKIE['rmcRedirect']));
            $displayData['referrerPageTitle'] = $cookieVal->pageTitle;
            $displayData['referrerPageURL'] = $_SERVER['HTTP_REFERER'];
        }else{
            $displayData['referrerPageTitle'] = "Home";
            $displayData['referrerPageURL'] = SHIKSHA_STUDYABROAD_HOME;
        }
        // For Pageview Tracking
        $this-> _prepareTrackingData($displayData,'rmcRegistrationPage');

        $this->load->view('rmcHomepageOverview',$displayData);
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
                /*Exclude RMC user from LDB */
                $this->load->library('user/UserLib');
                $userLib = new UserLib;
                // $userLib->excludeRMCUserFromLDB($userId, $displayData['courseId']);

                $this->_makeResponseAndSubmit($displayData);
                exit;
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
    public function loadRateMyChanceButton($data =array(), $source = '')
    {
        // load course
        $this->load->builder('ListingBuilder','listing');
        $listingBuilder 			= new ListingBuilder;
        $abroadCourseRepository 	= $listingBuilder->getAbroadCourseRepository();
        if(!is_null($data['courseObj']) && $data['courseObj'] instanceof AbroadCourse)
        {
            $courseObject = $data['courseObj'];
        }else{
            $courseObject = $abroadCourseRepository->find($data['courseId']);
        }
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
            'courseId' => $data['courseId']
        );
        $cssTextAndData = $this->_getClassAndTextByRating($rmcState);
        $displayData['ratingText'] = $cssTextAndData['ratingText'];
        $displayData['cssIconText'] = $cssTextAndData['cssIconText'];
        $displayData['cssIconClass'] = $cssTextAndData['cssIconClass'];
        $displayData['trackingPageKeyId'] = $data['trackingPageKeyId'];
        switch($source){
            case 'abroadSearchV2' :
                if($courseObject -> showRmcButton() == 1) {
                    $displayData['class'] = $data['class'];
                    $displayData['loc'] = $data['loc'];
                    return $this->load->view('SASearch/rmcWidget', $displayData, true);
                }
                else {
                    return "";
                }
                break;
            default:
            $this->load->view('rateMyChances/widget/rateMyChance', $displayData);
        }
 
    }
    
    private function _getClassAndTextByRating($rmcState){
        switch($rmcState) {
            case 0: // This is when he has pressed the button before, but hasn't been rated yet. => Awaiting.
                $ratingText = 'under review';
                $cssIconText = '';
                $cssIconClass='under-reviewed-mark';
                break;
            case 1:
                $ratingText = 'Rated Excellent';
                $cssIconText = '&#10004;';
                $cssIconClass='rated-good-mark';
                break;
            case 2:
                $ratingText = 'Rated Good';
                $cssIconText = '&#10004;';
                $cssIconClass='rated-good-mark';
                break;
            case 3:
                $ratingText = 'Rated Average';
                $cssIconText = '&#10004;';
                $cssIconClass='rated-good-mark';
                break;
            case 4:
                $ratingText = 'Rated Poor';
                $cssIconText = '&times;';
                $cssIconClass='rated-poor-mark';
                break;
            case 5:
                $ratingText = 'No Rating Given';
                $cssIconText = '&times;';
                $cssIconClass='rated-poor-mark';
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
        if($_SERVER['HTTP_REFERER'] == getCurrentPageURLWithoutQueryParams()){
            // User refreshed the page, it is fine, don't remake the cookie at all!
            return ;
        }
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
        
         //if current page is a rmc Success page and rate my chance is done on a recommendation then referre should be the first place rmc was done
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
        $displayData    = array();
        $displayData['rmcShowSuccessPageCookieValue'] = $this->input->cookie('rmcShowSuccessPage');
        $displayData['rmcRedirect'] = $this->input->cookie('rmcRedirect');
        $this->_successPageValidateAndRedirect($displayData);
        $this->_checkUserDetails($displayData);
        $userObj        = $displayData['userObj'];
        if(!is_object($userObj)){
            header("Location: ".SHIKSHA_STUDYABROAD_HOME);
            exit;
        }
        if(is_object($userObj))
        {
            $userExams = $this->shikshaApplyCommonLib->getAbroadExamsForUserByUserObject($userObj);
        }
        $displayData['userExams'] = implode(', ',$userExams);
        if(count($userExams) == 0){
            //do nothing!
        }else if(count($userExams) == 1){
            $displayData['userExams'].=" Score";
        }else{
            $displayData['userExams'].=" Scores";
        }
        $courseId                   = $displayData['courseId'];
        $this->load->builder('ListingBuilder','listing');
        $listingBuilder             = new ListingBuilder;
        $abroadCourseRepository     = $listingBuilder->getAbroadCourseRepository();
        $abroadCourse               = $abroadCourseRepository->find($courseId);
        $displayData['courseObj']   = $abroadCourse;
        //Show remaining courses count in thank you message
        $result['courses']          = $this->shikshaApplyCommonLib->getUserRmcRatings($userObj->getId());
        $displayData['courseCount'] = count($result['courses']);
        $seoDetails = array();
        $seoDetails['title'] = 'Profile submitted for '.$abroadCourse->getUniversityName().', '.$abroadCourse->getCountryName().' - Study Abroad';
        $seoDetails['description'] = "Interested in applying to more study abroad colleges? Take help from an expert Shiksha counselor.";
        $displayData['seoDetails'] = $seoDetails;
        $displayData['seoUrl'] = SHIKSHA_STUDYABROAD_HOME."/submission-success";

        //Remove the CookieForBrochureDownload to stop showing the popup when user goes back to course page
        Modules::run('listing/abroadListings/readRemoveCookieForBrochureDownload');
		$displayData['compareCookiePageTitle'] = "Success Page";
		$displayData['compareOverlayTrackingKeyId'] = 592;
        $displayData['compareButtonTrackingId'] = 657;
        // For Pageview Tracking
        $this-> _prepareTrackingData($displayData,'rmcSuccessPage');
        $this->load->view('rateMyChances/successPageOverview',$displayData);
    }
    
    private function _successPageValidateAndRedirect(& $displayData){
        if($this->input->cookie('rmcShowSuccessPage') !== "1"){
            header("Location: ".SHIKSHA_STUDYABROAD_HOME);
            exit;
        }
        $val = json_decode(base64_decode($this->input->cookie("signUpFormParams")),true);

        if(!((integer)$val['cId'] > 0)){
            parse_str($_SERVER['QUERY_STRING'], $_GET);     
            $entityInfo = json_decode(base64_decode($this->security->xss_clean($this->input->get('c'))),true);
            if(is_null($entityInfo))
            {
                show_404_abroad();
            }
            $val['cId'] = $entityInfo['courseId'];
        }
        if(empty($val['rf'])){
            $val['rf'] = SHIKSHA_STUDYABROAD_HOME;
            $val['rftl'] = "Home";
        }
        $displayData['courseId'] = $val['cId'];
        $displayData['returnUrl'] = $val['rf'];
        $displayData['returnPageTitle'] = urldecode($val['rftl']);
        setcookie('rmcShowSuccessPage', '', time()-3600,"/",COOKIEDOMAIN);
        setcookie('signUpFormParams', '', time()-3600,"/",COOKIEDOMAIN);
    }
    
    private function _makeResponseAndSubmit($displayData){
        $sourcePage = $this->_getPageSourceByReferrer();
        if(!isset($_COOKIE['rmcTrackingPageKey'.$displayData['courseId']])){
            $_COOKIE['rmcTrackingPageKey'.$displayData['courseId']] = 645;
        }
        $url = Modules::run("responseAbroad/ResponseAbroad/createResponseCallForAbroadListings",'course',$displayData['courseId'],$sourcePage);
        setcookie("rmcShowSuccessPage","1",time()+120,"/",COOKIEDOMAIN);
        header("Location: ".reset(json_decode($url,true)));
    }
    
    private function _getPageSourceByReferrer(){
        $ref = $_SERVER['HTTP_REFERER'];
        if(strpos($ref,"-dc1")){
            return "rmcPage_categoryPage";
        }else if(strpos($ref,"-cl1")){
            return "rmcPage_categorypage";
        }else if(strpos($ref,"-courselisting-")){
            return "rmcPage_coursePage";
        }else if(strpos($ref,"shortlisted-courses-page")){
            return "rmcPage_shortlistPage";
        }else{
            return "rmcPage";
        }
    }
    

    public function getUserStageAndRmcCoursesAppliedToByCurrentUserAPI(){
        $requestHeader = ($_SERVER['HTTP_ORIGIN'] != null) ? $_SERVER['HTTP_ORIGIN'] : SHIKSHA_HOME;
        header("Access-Control-Allow-Origin: ".$requestHeader);
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header('P3P: CP="CAO PSA OUR"'); // Makes IE to support cookies
        header("Content-Type: application/json; charset=utf-8");
        $this->getUserStageAndRmcCoursesAppliedToByCurrentUser();
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
            $result['html'] = $this->load->view('rateMyChances/widget/rmcPopupNoRatingGivenErrorMessage',array(),true);
        }
        
        if(count($result['courses'])>=ABROADRMCLIMIT){
            $result['html'] = $this->load->view('rateMyChances/widget/rmcPopupSaturatedMessage',array(),true);
        }
        if(count($result['courses'])< ABROADRMCLIMIT)
        {
            $result['stage'] = $this->shikshaApplyCommonLib->getUserStage($userId);
            if($result['stage'] == 10){
                $result['html'] = $this->load->view('rateMyChances/widget/rmcPopupDropoffMessage',array(),true);
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
