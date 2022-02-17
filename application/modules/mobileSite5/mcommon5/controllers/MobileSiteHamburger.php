<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MobileSiteHamburger extends ShikshaMobileWebSite_Controller
{
    private $userStatus = 'false';
    function __construct()
    { 
    	$this->benchmark->mark('load_Parent_Constuctor_start');
        parent::__construct();
        $this->benchmark->mark('load_Parent_Constuctor_end');
 
        $this->benchmark->mark('load_Constructor_Data_start');
        $this->load->config('mcommon5/mobi_config');
	    $this->userStatus = $this->checkUserValidation();
 		$this->benchmark->mark('load_Constructor_Data_end');
    }

    function getHamburgerMenu($panelId,$isSearchPage=false){

	if(!MOB_HAMBURGER_CUSTOMIZE || $this->input->is_ajax_request())
	{
	    global $typeOfMachine;
	    $typeOfMachine = 'mobile';
	    $signedInUser = $this->userStatus;
	    $data['id'] = $id;
	    $data['width'] = $width;
	    if(isset($_COOKIE['categoryId']))
	    {
		    $data['QandAPageUrl'] = '';	
	    }else{
		    $data['QandAPageUrl'] = SHIKSHA_ASK_HOME . '/1/1/1/answer/';
	    }
	    //$data['institutePageUrl'] = SHIKSHA_ASK_HOME . '/1/1/1/answer/';
	    $data['signedInUser']     = $signedInUser;
	    $data['loginUrl']	  = $this->config->item('loginUrl');
	    $data['registerUrl']         = $this->config->item('registerUrl');
	    $data['contactusUrl']     = $this->config->item('contactusUrl');
	    $data['logoutUrl']	  = $this->config->item('logoutUrl');
	    $data['termUrl']          = $this->config->item('termUrl');
	    $data['aboutusUrl']       = $this->config->item('aboutusUrl');
	    $data['policyUrl']	  = $this->config->item('policyUrl');
	    $data['helplineUrl']      = $this->config->item('helplineUrl');
	    $data['articleUrl']      =  $this->config->item('articleUrl');
	    $data['communityGuidelineUrl']      =  $this->config->item('communityGuidelineUrl');
	    $data['userPointSystemUrl']      =  $this->config->item('userPointSystemUrl');
	    
	    if($signedInUser!='false'){
		    if($signedInUser[0]['firstname']!=''){
			    $data['displayname'] = $signedInUser[0]['firstname']." ".$signedInUser[0]['lastname'];
		    }
		    else{
			    $data['displayname'] = $signedInUser[0]['displayname'];
		    }
		    $data['avtarurl']    = $signedInUser[0]['avtarurl'];
	    }
    
	    if($signedInUser!='false' && !empty($signedInUser[0]['userid'])) {
		$usr_model = $this->load->model('user/usermodel');
		$response_array = $usr_model->getUserDetailsForTwoStepRegistration($signedInUser[0]['userid']);
    
		//Check if LDB User
		if(isset($response_array['isLDBUser'])){
		    $isLDBUserCheck = $response_array['isLDBUser'];
		}
		else{
		    $isLDBUserCheck = 'NO';
		}
    
		//If not LDB user, check if Desired course is filled.
		$desiredCourse = 0;
		if($isLDBUserCheck == 'NO'){
		    if(isset($response_array['DesiredCourse']) && $response_array['DesiredCourse']>0){
			$desiredCourse = $response_array['DesiredCourse'];
		    }
		}
    
		//If not LDB and not DC, this is a short registered user
		if($desiredCourse == 0 && $isLDBUserCheck == 'NO'){
		    $shortRegistered = true;
		}
		else{
		    $shortRegistered = false;
		}
		$data['isLDBUserCheck'] = $isLDBUserCheck;
		$data['desiredCourse'] = $desiredCourse;
		$data['shortRegistered'] = $shortRegistered;
	    }
	    $data['isSearchPage'] = $isSearchPage;
	}
	
        if($this->input->is_ajax_request() && $this->input->post('isCustomizeMenu') == 'yes')
	{
	    echo $this->load->view('hamburgerMenuInnerHtml',$data);return;
	}
	
	if(MOB_HAMBURGER_CUSTOMIZE && $this->input->post('isCustomizeMenu') == ''){
	    $this->load->view('hamburgerCustomize');
	}else{
	    $this->load->view('hamburgerMenu',$data);   
	}
    }
    
   // This function is used for right panel only
    
    function getRightPanel($panelId,$isSearchPage=false,$pageName=''){
	
	$signedInUser = $this->userStatus;
        $data['id'] = $id;
        $data['width'] = $width;
	if(isset($_COOKIE['categoryId']))
	{
		$data['QandAPageUrl'] = '';	
	}else{
		$data['QandAPageUrl'] = SHIKSHA_ASK_HOME . '/1/1/1/answer/';
	}
	$data['signedInUser']     = $signedInUser;
	$data['loginUrl']	  = $this->config->item('loginUrl');
	$data['registerUrl']         = $this->config->item('registerUrl');
	$data['logoutUrl']	  = $this->config->item('logoutUrl');
	$data['pageName'] = $pageName;
	
	
	if($signedInUser!='false'){
		if($signedInUser[0]['firstname']!=''){
			$data['displayname'] = $signedInUser[0]['firstname']." ".$signedInUser[0]['lastname'];
		}
		else{
			$data['displayname'] = $signedInUser[0]['displayname'];
		}
		$data['avtarurl']    = $signedInUser[0]['avtarurl'];
	}

        if($signedInUser!='false' && !empty($signedInUser[0]['userid'])) {
            $usr_model = $this->load->model('user/usermodel');
            $response_array = $usr_model->getUserDetailsForTwoStepRegistration($signedInUser[0]['userid']);

            //Check if LDB User
            if(isset($response_array['isLDBUser'])){
                $isLDBUserCheck = $response_array['isLDBUser'];
            }
            else{
                $isLDBUserCheck = 'NO';
            }

            //If not LDB user, check if Desired course is filled.
            $desiredCourse = 0;
            if($isLDBUserCheck == 'NO'){
                if(isset($response_array['DesiredCourse']) && $response_array['DesiredCourse']>0){
                    $desiredCourse = $response_array['DesiredCourse'];
                }
            }

            //If not LDB and not DC, this is a short registered user
            if($desiredCourse == 0 && $isLDBUserCheck == 'NO'){
                $shortRegistered = true;
            }
            else{
                $shortRegistered = false;
            }
	    $data['isLDBUserCheck'] = $isLDBUserCheck;
            $data['desiredCourse'] = $desiredCourse;
            $data['shortRegistered'] = $shortRegistered;
        }
	
	$data['isSearchPage'] = $isSearchPage;
	
        $data['anaInAppNotificationCountForMobileSite'] = 0;
        if(isset($signedInUser[0]['userid']) && $signedInUser[0]['userid'] != ""){
        	$this->load->library('common_api/APICommonCacheLib');
       		$apiCommonCacheLib = new APICommonCacheLib();
    		$data['anaInAppNotificationCountForMobileSite'] = $apiCommonCacheLib->getUserNotificationCount($signedInUser[0]['userid']);	
    		if($data['anaInAppNotificationCountForMobileSite'] > 30){
	    		$data['anaInAppNotificationCountForMobileSite'] = 30;
	    	}
        }
        setCookie("anaInAppNotificationCountForMobileSite",$data['anaInAppNotificationCountForMobileSite'],time()+86400*30,'/',COOKIEDOMAIN);
        $this->load->view('hamburgerRightMenu',$data);
    }
    
    function getMainHeader($displayHamburger, $isBackBtnCookei, $pageName,$isShowIcpBanner = false)
    {
	$data['displayHamburger'] = $displayHamburger;
	$data['isBackBtnCookei']  = ($isBackBtnCookei !='') ? $isBackBtnCookei : '';
	$data['pageName']  = ($pageName !='') ? $pageName : '';
	$data['isShowIcpBanner'] = false;
	$signedInUser = $this->userStatus[0];
	if(isset($signedInUser['userid']) && $signedInUser['userid'] != ""){
		$this->load->library('common_api/APICommonCacheLib');
    	$apiCommonCacheLib = new APICommonCacheLib();
    	$data['anaInAppNotificationCountForMobileSite'] = $apiCommonCacheLib->getUserNotificationCount($signedInUser['userid']);
    	if($data['anaInAppNotificationCountForMobileSite'] > 30){
    		$data['anaInAppNotificationCountForMobileSite'] = 30;
    	}
	}else {
		$data['anaInAppNotificationCountForMobileSite'] = 0;
	}

	$this->load->view('headerMain',$data);
    }
    
    function getMobilRecommendation($userId, $totalItmes=5)
    {
	$this->load->library('recommendation/recommendation_front_lib');
	$recommendations = $this->recommendation_front_lib->getRecommendations(array($userId), array(), 'no', $totalItmes);
	    $recommendations = json_decode(gzuncompress(base64_decode($recommendations)));
	    $recommendations = $recommendations->recommendations;
	    
	    foreach($recommendations as $user) {
			$user = $user->recommendations;
			foreach($user as $category){
				$category = $category->recommendations;
				foreach($category as $algo => $results) {
					foreach($results as $result) {
						$recommendedCourses[$result->institute_id] = array($result->course_id);
						$algoForRecommendation[$result->institute_id] = $algo;
					}
				}
			}
	    }
	
	$this->load->builder('ListingBuilder','listing');
	$listingBuilder = new ListingBuilder;
	
	$instituteRepository = $listingBuilder->getInstituteRepository();
	$recommendations = $instituteRepository->findWithCourses($recommendedCourses);	
			
		foreach($recommendations as $institute) {
			$course = $institute->getFlagshipCourse();
			$instituteId = $institute->getId();
			$courseID = $course->getId();
			$courseData[$instituteId]['instituteFullName'] = $institute->getName();
			$courseData[$instituteId]['instituteName'] = strlen($institute->getName()) > 40 ? substr($institute->getName(), 0, 40).'...' : $institute->getName();
			$courseData[$instituteId]['courseURL'] = $course->getURL();
			
			$mainLocation = $course->getMainLocation();
			$mainLocationId = $mainLocation->getLocationId();
			$mainCity = $mainLocation->getCity();
			$mainCityId = $mainCity->getId();
			$mainLocalityId = $mainLocality ? $mainLocality->getId() : 0;
    
			$courseData[$instituteId]['mainCityName'] = $mainCity->getName();
		}
	return $courseData;
    }
    
    // get user Recommendations ,when user loggedIn
    function getMobileReco()
    {
	$signedInUser = $this->userStatus;
	$this->load->library('Common/cacheLib');
	$this->cacheLib = new cacheLib();
	if($signedInUser !='false' && !empty($signedInUser[0]['userid'])){
	    
	    $key = "getMReco_".$signedInUser[0]['userid'];
	    
	    if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
 		$recommendations = $this->getMobilRecommendation($signedInUser[0]['userid'],5);
		if(count($recommendations)<=0){
		    $recommendations = "No Data";
		}
		$this->cacheLib->store($key,$recommendations,300);
		$data['recommendations'] = ($recommendations != 'No Data') ? $recommendations : array();
		
	    }else{
		$data['recommendations'] = ($this->cacheLib->get($key) !='' && is_array($this->cacheLib->get($key))) ? $this->cacheLib->get($key) : array();
	    }
	    setcookie('mobileTotalUserReco',count($data['recommendations']),0,'/',COOKIEDOMAIN); 
	    $this->load->view('hamburgerRecoPage',$data);
	}
    }

    
    function getGroupedShortlistedCoursesHTML() {
    	$response = array();
    	$response['userSubCategory'] = 0;
    	//check if the user has registered with preferred course id 23
    	if(is_array($this->userStatus) && $this->userStatus[0]['userid'] != '') {
    		$usr_model = $this->load->model('user/usermodel');
    		$subCategoryId = $usr_model->getUserDesiredSubCategory($this->userStatus[0]['userid']);
    		if($subCategoryId > 0) {
    			$response['userSubCategory'] = $subCategoryId['subCategoryId'];
    		}
    	}
    	
    	$data['courseShortArray'] = Modules::run('mobile_category5/CategoryMobile/getMShortlistedCourse');
    	$data['groupedSubCategoryData'] = $this->groupShortlistedCoursesBySubCategory($data);
    	$response['html'] = $this->load->view('groupedShortlistedCoursesHamburger',$data,true);
    	
    	if(empty($data['groupedSubCategoryData'])){
    		$response['showGrouping'] = 0;
    	}
    	else {
    		$response['showGrouping'] = 1;	
    	}
    	echo json_encode($response);
    }
    

    /**
	 * [groupShortlistedCoursesBySubCategory returns subcategory name and its count]
	 * @author Ankit Garg <g.ankit@shiksha.com>
	 * @date   2015-04-03
	 * @param  [array]     $data [contains array of course ids]
	 * @return [array]     $groupedSubCategoryData [contains subcategory name and its count]
	 */
    function groupShortlistedCoursesBySubCategory($data) {
		$groupedSubCategoryData = array();
    	if(count(array_filter($data['courseShortArray'])) > 0) {
        	$courseSubCatregoriesDat = array();
	        $NationalCourseLib = $this->load->library('listing/NationalCourseLib');
	    	//fetching dominant subcategory for courses
	    	$courseSubCatregoriesData = $NationalCourseLib->getCourseDominantSubCategoryDB($data['courseShortArray']);
	    	
	    	//getching subcategory names from cache
	    	$this->load->builder('CategoryBuilder','categoryList');
			$categoryBuilder = new CategoryBuilder;
			$categoryRepository = $categoryBuilder->getCategoryRepository();
			if(!empty($courseSubCatregoriesData['allDominantSubCategoryIds'])) {
				$subCategoryDataRepository = $categoryRepository->findMultiple($courseSubCatregoriesData['allDominantSubCategoryIds']);
			}
			else {
				return array();
			}
			
			//merging subcategory names corresponding to the number of course shortlisted
			foreach($courseSubCatregoriesData['subCategoryInfo'] as $courseId=>$subCategoryData) {
				$subCategoryName = $subCategoryDataRepository[$subCategoryData['dominant']]->getName();
				$groupedSubCategoryData[$subCategoryData['dominant']][$subCategoryName]++;
			}
    	}
    	return $groupedSubCategoryData;
    }

	function checkIfMentorAssignedToMentee()
	{
		$this->init();
		$displayData = array();
		$displayData['validateuser'] = $this->userStatus;
		$userId = array();
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$this->load->model('CA/mentormodel');	
		$mentorModel = new mentormodel;
		$result = $mentorModel->checkIfMentorAssignedToMentee(array($userId));
		if($result[$userId] == '')
		{
			return false;
		}
		return true;
	}

    /**
	* Function to enable APP Banner
	* MOB_APPMARKETING_MOBILEHEADER flag should be true (define in shikshaConstant.php)
	* added by akhter
	**/	
	function getAppBanner($deviceType='mobile', $pageName='misc',$isShowIcpBanner = false, $predBannerStream=''){
		$deviceType = ($this->input->post('deviceType') !='') ? $this->input->post('deviceType') : $deviceType;
		$pageName = ($this->input->post('pageName') !='') ? $this->input->post('pageName') : $pageName;
		$data['sessionID'] = $this->getSessionId();
		$deviceType = strtolower($deviceType);
		$deviceOS = ($this->getDeviceOS() !='') ? $this->getDeviceOS() : 'none';
		$data['appCloseCookieName'] = $deviceType.'_appBannerClose';
		$data['icpCloseCookieName'] = $deviceType.'_icpBannerClose';
		$data['pageName'] = $pageName;
		$isClosed = ($_COOKIE[$data['appCloseCookieName']] !='') ? $_COOKIE[$data['appCloseCookieName']] : '';
		$isIcpClosed = ($_COOKIE[$data['icpCloseCookieName']] !='') ? $_COOKIE[$data['icpCloseCookieName']] : '';
		/*if((MOB_APPMARKETING_MOBILEHEADER && $deviceType == 'mobile') && ($data['sessionID'] != $isClosed)){
			$this->load->view('mcommon5/mobileAppBanner',$data);
		}*/

		if((MOB_ICPMARKETING_MOBILEHEADER && $isShowIcpBanner && $deviceType == 'mobile') && ($data['sessionID'] != $isIcpClosed)){
			if($predBannerStream == 'mba'){
				$data['imgSrc'] = '/public/mobile5/images/MBA.jpg' ;
				$data['landingURL'] = '/mba/colleges/mba-colleges-india';
				$data['altText'] = 'check mba colleges in India ';
				$data['gaText'] = 'MBA_BANNER_MOBILEHEADER';
			
			}else if($predBannerStream == 'btech'){
				$data['imgSrc'] = '/public/mobile5/images/B-tech.jpg' ;
				$data['landingURL'] = '/b-tech/colleges/b-tech-colleges-india';
				$data['altText'] = 'check btech colleges in India';
				$data['gaText'] = 'BTECH_BANNER_MOBILEHEADER';


			}else if($predBannerStream == 'design'){
				$data['imgSrc'] = '/public/mobile5/images/Fashion-design.jpg' ;
				$data['landingURL'] = '/design/fashion-design/colleges/colleges-india';
				$data['altText'] = 'check design colleges in India';
				$data['gaText'] = 'DESIGN_BANNER_MOBILEHEADER';


			}else if($predBannerStream == 'hospitality'){
				$data['imgSrc'] = '/public/mobile5/images/BHM.jpg' ;
				$data['landingURL'] = '/hospitality-travel/hotel-hospitality-management/colleges/bhm-colleges-india';
				$data['altText'] = 'check hospitality colleges in India';
				$data['gaText'] = 'HOSPITALITY_BANNER_MOBILEHEADER';


			}else {
				$data['imgSrc'] = '/public/mobile5/images/icp-mob-3.jpg' ;
				$data['landingURL'] = '/mba/resources/iim-call-predictor';
				$data['altText'] = 'check iim call predictor';
				$data['gaText'] = 'ICPBANNER_MOBILEHEADER';

			}
			$this->load->view('mcommon5/mobileIcpBanner',$data);
		}else if(((DESKTOP_APPMARKETING_BANNER || $pageName == 'home') && $deviceType == 'desktop')){
			echo json_encode($this->load->view('common/desktopAppBanner',$data,true));return;
		}else if($deviceType == 'desktop'){
			$string = "<script type='text/javascript'>
					var appClose = '$appCloseCookieName';
					var appSessionId = '$sessionID';
				</script>";
			echo json_encode($string); 
			return;
		}
	}
	/**
	* Function to get the session identifier for the current request
	**/	
	private function getSessionId()
	{
	    //session_start();
	    $id =  sessionId();
	    //session_destroy();
	    return $id;
	}

	function getDeviceOS(){
		$deviceTypeList = array('Android');
		foreach($deviceTypeList as $os)
		{
			if(isset($_SERVER["HTTP_USER_AGENT"]) && strstr($_SERVER["HTTP_USER_AGENT"],$os) && !strstr($_SERVER["HTTP_USER_AGENT"],'Windows')) {
				return $os;
			}
		}
	}

	/**
	* getCompared() Function is used to prepare compared HTML in RHL
	* added by akhter
	**/
	function getComparedList(){
		$this->benchmark->mark('load_librray_start');
		$cmplibObj      = $this->load->library('comparePage/comparePageLib');
		$this->benchmark->mark('load_librray_end');
		$this->benchmark->mark('get_compare_data_start');
		$courseIdBucket = $cmplibObj->getComparedData('mobile');
		$this->benchmark->mark('get_compare_data_end');
		$courseIdArr    = array_keys($courseIdBucket);
		if(count($courseIdArr)>0){	
		    	$this->benchmark->mark('get_course_data_start');
			// load the course builder
			$this->load->builder("nationalCourse/CourseBuilder");
			$builder = new CourseBuilder();
			$courseRepo = $builder->getCourseRepository();
			$courseObj = $courseRepo->findMultiple($courseIdArr); 
			$this->benchmark->mark('get_course_data_end');
			$displayData['courseObj'] = $courseObj;
		}
		$displayData['courseBucket'] = $courseIdBucket;		
		$displayData['boomr_pageid'] = $this->input->post('fromPage');
		$displayData['signedInUser'] = $this->userStatus;
		$this->benchmark->mark('load_view_start');
		$html = $this->load->view('mcommon5/hamburgerComparedPage',$displayData,true);
		$this->benchmark->mark('load_view_end');
		//$html = sanitize_output($html);
		$this->benchmark->mark('json_encode_start');
		echo json_encode(array('html'=>$html));
		$this->benchmark->mark('json_encode_end');
	}

	public function fetchRightPanelAnANotifications(){

		$signedInUser = $this->userStatus[0];
		if(is_array($signedInUser)){
			$loggedInUserId = $signedInUser['userid'];
			$APIClient = $this->load->library("APIClient");        

	        $APIClient->setUserId($loggedInUserId);
	        //$APIClient->setVisitorId(getVisitorId());
	        $APIClient->setRequestType("get");
	        //$APIClient->setRequestData(array("text"=>"mba", "type" => "tag" ,"count" => 10));
	        $jsonDecodedData =$APIClient->getAPIData("NotificationInfo/fetchInAppNotification/");

	        if(empty($jsonDecodedData['notifications'])){
	        	$displayData['emptyNotifications'] = $jsonDecodedData['responseMessage'];
	        }
	        else {
	        	$displayData['emptyNotifications'] = "";
	        }

	        $jsonDecodedData = $jsonDecodedData['notifications'];
	        
	        foreach ($jsonDecodedData as $key => $value) {
	        	$jsonDecodedData[$key]['landingURL'] = $this->generateURL($value,$loggedInUserId);

	        }
	        $displayData['data'] = $jsonDecodedData;
	        setCookie("anaInAppNotificationCountForMobileSite",0,time()+86400*30,'/',COOKIEDOMAIN);
	        echo $this->load->view('mcommon5/inAppNotificationsForMobileSite',$displayData);
	    
		}
	}

	function generateURL($data,$loggedInUserId){

		$notificationConfig = $this->load->config('NotificationConfig');
		$notificationType = $data['notificationType'];
		$primaryId = $data['primaryId'];
		$secondaryData = $data['secondaryData'];
		$landingURL = "";

		if($notificationType == QUESTION_DETAIL_PAGE_DEFAULT){
			//$landingURL = SHIKSHA_ASK_HOME_URL."/getTopicDetail/".$primaryId;
			$landingURL = SHIKSHA_ASK_HOME_URL."/-qna-".$primaryId;	
		}
		else if($notificationType == QUESTION_DETAIL_PAGE_WITH_REFERENCE_ANSWER){
			//$landingURL = SHIKSHA_ASK_HOME_URL."/getTopicDetail/".$primaryId."?referenceEntityId=".$secondaryData[0];
			$landingURL = SHIKSHA_ASK_HOME_URL."/-qna-".$primaryId."?referenceEntityId=".$secondaryData[0];
		}
		else if($notificationType == DISCUSSION_DETAIL_PAGE_DFEAULT){
			//$landingURL = SHIKSHA_ASK_HOME_URL."/getTopicDetail/".$primaryId;
			$landingURL = SHIKSHA_ASK_HOME_URL."/-dscns-".$primaryId;
		}
		else if($notificationType == DISCUSSION_DETAIL_PAGE_WITH_REFERENCE_COMMENT){
			//$landingURL = SHIKSHA_ASK_HOME_URL."/getTopicDetail/".$primaryId."?referenceEntityId=".$secondaryData[0];
			$landingURL = SHIKSHA_ASK_HOME_URL."/-dscns-".$primaryId."?referenceEntityId=".$secondaryData[0];
		}
		else if($notificationType == USER_PROFILE_PAGE_DEFAULT){
			if($data['userId'] == $primaryId){
				$landingURL = SHIKSHA_HOME."/userprofile/edit";
			}
			else{
				$landingURL = SHIKSHA_HOME."/userprofile/".$primaryId;
			}				
		}
		else if($notificationType == TAG_DETAIL_PAGE_DEFAULT){
			// only primary
		}
		else if($notificationType == QUESTION_DETAIL_PAGE_WITH_LINK_QUESTION){
			//$landingURL = SHIKSHA_ASK_HOME_URL."/getTopicDetail/".$primaryId;	
			$landingURL = SHIKSHA_ASK_HOME_URL."/-qna-".$primaryId;	
		}
		else if($notificationType == DISCUSSION_DETAIL_PAGE_WITH_LINK_QUESTION){
			//$secondaryData[1]
			//$landingURL = SHIKSHA_ASK_HOME_URL."/getTopicDetail/".$primaryId;
			$landingURL = SHIKSHA_ASK_HOME_URL."/-dscns-".$primaryId;
		}
		else if($notificationType == LEVEL_UP_DIALOG){

			if($data['userId'] == $primaryId){

				$landingURL = SHIKSHA_HOME."/userprofile/edit";
			}
			else{
				$landingURL = SHIKSHA_HOME."/userprofile/".$primaryId;
			}
		}
		else if($notificationType == POINTS_UP_DIALOG){
			// webpage - title => seconday[0]
						// - url => secondary[1]
			//$landingURL = SHIKSHA_HOME."/mcommon5/MobileSiteStatic/loadHelpPagesOfApp/userPointSystem";
			$landingURL = $secondaryData[1];
		}
		else if($notificationType == RATING_DIALOG){
			// Do nothing
		}
		else if($notificationType == GENERAL_NOTIFICATION){
			//// Do nothing
		}
		else if($notificationType == WEBVIEW_NOTIFICATION){
			$landingURL = $secondaryData[1];
		}	
		else if($notificationType == INAPP_NOTIFICATION){
			// Do nothing
		}
		return $landingURL;
	}


	function getPredictorSaveListUrl(){
		$signedInUser = $this->userStatus[0];
		if(isset($signedInUser['userid']) && $signedInUser['userid'] != ""){
			$userId = $signedInUser['userid'];
			$apiCallerlib = $this->load->library("common/apiservices/APICallerLib");
			$output = $apiCallerlib->makeAPICall("COLLEGEPREDICTOR","/predictor/api/v1/info/shortlist/getUserBookmarkUrl","GET",array("userId" => $userId));
	        $output = json_decode($output['output'], true);
        	if(!empty($output['data'])){
        		echo $output['data'];
        	}
		}
	}
	function trackAllPredictorData(){
		if(!empty($_POST['data'])){
			$inputs = base64_decode($this->input->post('data', true));
			$apiCallerlib = $this->load->library("common/apiservices/APICallerLib");
			$output = $apiCallerlib->makeAPICall("TRACKINGSERVICE","/trackinggateway/trackingApi/v1/common/info/collegeShortlistTracking", "POST", '', $inputs);
		}
	}
	function saveFeedback(){
		if(!empty($_POST['data'])){
			$inputs = base64_decode($this->input->post('data', true));
			$apiCallerlib = $this->load->library("common/apiservices/APICallerLib");
			error_log('shiksha::feedback php api::'.print_r($inputs, true));
			$output = $apiCallerlib->makeAPICall("TRACKINGSERVICE","/trackinggateway/trackingApi/v1/common/info/feedbackTracking", "POST", '', $inputs, '', true);
		}
	}
}
?>
