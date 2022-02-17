<?php
class CampusAmbassadorEnterprise extends MX_Controller {
	private $isUserGroupCRModerator = false;
/*

   Copyright 2013 Info Edge India Ltd

   $Author: Pranjul

   $Id: CampusAmbassadorEnterprise.php

 */

    /*
	 @name: init
	 @description: this is for cms user validation and load CampusAmbassadorEnterprise and Validate for CMS user.
	 @param string $userInput: no paramaters
	*/
    function init(){
        $this->userStatus = $this->checkUserValidation();
        if($this->userStatus!='false'){
       	    $usergroup = $this->userStatus[0]['usergroup'];
	       	if(
	       		($usergroup!= "cms") &&
	       		($this->isUserGroupCRModerator == true && $usergroup != CR_MODERATOR_USER_GROUP)
	       	){
	       		header("location:/enterprise/Enterprise/disallowedAccess");
		    	exit();	
	       		
	       }
		}
		$this->load->model('CAEnterprise/caenterprisemodel');
		$this->caenterprisemodel = new CAEnterpriseModel();
		$this->load->model('reviewenterprisemodel');
		$this->reviewenterprisemodel = new ReviewEnterpriseModel();
    }
    
    /*
	 @name: index
	 @description: this is default function and redirect user to Campus Ambassador Detail tab and also validate user.
	 @param string $userInput: No Input
	*/
    function index(){
        $this->init();
        if($this->userStatus!='false'){
               $usergroup = $this->userStatus[0]['usergroup'];
               if($usergroup == "cms"){
                    $url='/CAEnterprise/CampusAmbassadorEnterprise/getAllCADetails';
                    header("Location:".$url);
                    exit;
               }else{
		    header("location:/enterprise/Enterprise/disallowedAccess");
        	    exit();
	       }
        }
    } 
    /*
	 @name: getAllCADetails
	 @description: this function is for getting Details for all applied users.
	 i.e. Badges, Accepted Users, Reject Users, Incomplete Profile Users
	 @param string $userInput: No Input
	*/
    function getAllCADetails(){ 
	$this->init();
	$cmsUserInfo = Modules::run('enterprise/Enterprise/cmsUserValidation');
	$userid = $cmsUserInfo['userid'];
    $usergroup = $cmsUserInfo['usergroup'];
    $thisUrl = $cmsUserInfo['thisUrl'];
    $validity = $cmsUserInfo['validity'];
    $flagMedia = 1;

	$appId = 12;
	$loggedInUserId = isset($userid)?$userid:0;
	$catFilter = isset($_POST['catFilter'])?$this->input->post('catFilter'):'All'; // Program Id
	$filter = isset($_POST['Filter'])?$this->input->post('Filter'):'All';
	$start = isset($_POST['startFrom'])?$this->input->post('startFrom'):0;
	$rows = isset($_POST['countOffset'])?$this->input->post('countOffset'):5;
	$callType = isset($_POST['callType'])?$this->input->post('callType'):'';
	$instituteId = isset($_POST['instituteId'])?$this->input->post('instituteId'):'0';
	$keywordSuggest = isset($_POST['keywordSuggest'])?$this->input->post('keywordSuggest'):'';
	
	$userNameFieldDataCA=$this->input->post('userNameFieldDataCA');
	$filterTypeFieldDataCA=$this->input->post('filterTypeFieldDataCA');
	$this->load->model('CA/camodel');
	$this->camodel = new CAModel();
	$ccPrograms = $this->camodel->getAllCCPrograms();
    $cmsPageArr = array();
    $cmsPageArr['prodId'] = 784;
	if($userid=='1563'){
	    $cmsPageArr['prodId'] = 800;    
	}
        $cmsPageArr['validateuser'] = $validity;
        $cmsPageArr['headerTabs'] =  $cmsUserInfo['headerTabs'];
        $cmsPageArr['myProducts'] = $cmsUserInfo['myProducts'];
	$cmsPageArr['caPageHeader']['subTabType'] = 1;
	$parameterObj = array('abuse' => array('offset'=>-1,'totalCount'=>0,'countOffset'=>5));
	$parameterObj['abuse']['offset'] = 0;
	$parameterObj['abuse']['totalCount'] = $totalAbuseReport;
	$cmsPageArr['parameterObj'] = json_encode($parameterObj);
	$cmsPageArr['keywordSuggest'] = $keywordSuggest;
	/*
	This condition to filter Information by Institute
	*/
        if($instituteId==0){
	    $result = $this->caenterprisemodel->getAllCADetails($start,$rows,$filter,$userNameFieldDataCA,$filterTypeFieldDataCA,$catFilter);
	}else{
	    $result = $this->caenterprisemodel->getAllCADetailsForInstitute($start,$rows,$filter,$userNameFieldDataCA,$filterTypeFieldDataCA,$instituteId,$catFilter);
	}
	$this->load->library('CAUtilityLib');
	$caUtilityLib =  new CAUtilityLib;
	$resultCA = $caUtilityLib->formatCAData($result);
	$this->load->library('CollegeReviewForm/CollegeReviewLib');
	$collegeReviewLib =  new CollegeReviewLib;
	$filterData = $collegeReviewLib->formatCollegeReviewData($resultCA);
	$this->load->model('CollegeReviewForm/collegereviewmodel');
	$collegereviewmodel = new CollegeReviewModel;
	$collegeReviewData = $collegereviewmodel->getReviewData($filterData);
	$mentorQnaData = $this->caenterprisemodel->getMentorQnA($filterData);
	$mergeData = $collegeReviewLib->mergeData($resultCA , $collegeReviewData);	
	$cmsPageArr['collegeReviewData'] = $mergeData;
	$mergeData = $caUtilityLib->mergeMentorQnaData($mergeData , $mentorQnaData);
	$this->load->config('CA/MentorConfig');
//	$cmsPageArr['enabledSubCats'] = $this->config->item('enabledSubCats');
	$cmsPageArr['userNameFieldDataCA'] = $userNameFieldDataCA;
	$cmsPageArr['filterTypeFieldDataCA'] = $filterTypeFieldDataCA;
	$cmsPageArr['totalCA'] = $mergeData['totalCA'];
	$cmsPageArr['caData'] = isset($mergeData)?$mergeData:array();
	$cmsPageArr['countOffset'] = $rows;
	$cmsPageArr['startFrom'] = $start;
	$cmsPageArr['filterSel'] = $filter;
	$cmsPageArr['catFilter'] = $catFilter;
	$cmsPageArr['ccPrograms'] = $ccPrograms;
	$cmsPageArr['product']    = 'campusConnectModeration';
	/*
	Different view depends upon call type i.e. for refresh data under Campus Ambassador subtab we are using ajax call and
	we come on Campus Ambassador tab directly we are loading without ajax type.
	*/
	if($callType=='Ajax'){
	    echo $cmsPageArr['totalCA']."::".$this->load->view('CAEnterprise/ca_details_innerpart',$cmsPageArr);
	}else{
	    $this->load->view('CAEnterprise/ca_homepage',$cmsPageArr);
	}
    }

    function getCADiscussion(){
	$this->init();
	$cmsUserInfo = Modules::run('enterprise/Enterprise/cmsUserValidation');
	$userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $thisUrl = $cmsUserInfo['thisUrl'];
        $validity = $cmsUserInfo['validity'];
        $flagMedia = 1;

        $cmsPageArr = array();
        $cmsPageArr['prodId'] = 781;
        $cmsPageArr['validateuser'] = $validity;
        $cmsPageArr['headerTabs'] =  $cmsUserInfo['headerTabs'];
        $cmsPageArr['myProducts'] = $cmsUserInfo['myProducts'];
	$cmsPageArr['caPageHeader']['subTabType'] = 2;
	$this->load->view('CAEnterprise/ca_homepage',$cmsPageArr);
    }
    /*
	 @name: removeCAProfilePic
	 @description: this function is to remove pic for Campus Ambassador or Applied User.
	 @param string $userInput: No Input
	*/
    function removeCAProfilePic(){
	$this->init();
	$userId = isset($_POST['userId'])?$this->input->post('userId'):'0';
	$this->caenterprisemodel->removeCAProfilePic($userId);
	 //After removing the image in the Expert table, we will also have to change the User's image in the user table
        $this->load->library('Register_client');
        $registerClient = new Register_client();
        $registerClient->updateUserAttribute($appId, $userId, 'avtarimageurl', '','');
	echo '1';
    }
    /*
	 @name: updateStatusAndBadges
	 @description: this function is to update Status for Applied user.When user apply it's status in DB is 'draft'
	 When you Accept the user it's status in DB becomes 'accepted'
	 When you Reject the user it's status in DB becomes 'deleted'
	 When you send incomplete mailer to user it's status in DB becomes 'incomplete'
	 Moderator can also change the badges here i.e. 'None','Current Student', 'Official', 'Alumni'
	 @param string $userInput: No Input
	*/   
    function updateStatusAndBadges(){
	$this->load->library('listing/cache/ListingCache');
	$ListingCacheObj 	= new ListingCache();	

	$this->init();
	$userId = isset($_POST['userId'])?$this->input->post('userId'):'0';
	$actionType = isset($_POST['actionType'])?$this->input->post('actionType'):'draft';
	$courseBadgeRelation = isset($_POST['courseBadgeRelation'])?rtrim($this->input->post('courseBadgeRelation'),'#'):'';
	$courseBadgeOfficialRelation= isset($_POST['courseBadgeOfficialRelation'])?$this->input->post('courseBadgeOfficialRelation'):'';
	$mailerText = isset($_POST['incompleteMailerText'])?$this->input->post('incompleteMailerText'):'';
	$uniqueId_official_main = isset($_POST['uniqueId_official_main'])?$this->input->post('uniqueId_official_main'):'';
	
	$this->caenterprisemodel->updateStatusAndBadges($userId,$courseBadgeRelation,$courseBadgeOfficialRelation,$actionType,$uniqueId_official_main);

	// update courseId on solr for autosuggetor
	if($actionType == 'accepted' || $actionType == 'removed' || $actionType == 'deleted'){
	    $this->updateCampusConnectSolrIndex($userId);
	}
	$this->load->builder("nationalCourse/CourseBuilder");
	$courseBuilder = new CourseBuilder();
	$courseRepository = $courseBuilder->getCourseRepository();

	$courseBadgeRelationArr = explode('#',$courseBadgeRelation);
	if($actionType=='deleted' || $actionType=='incomplete'){
	    foreach($courseBadgeRelationArr as $key=>$value){
		$uniqueIdCourseBadgeArr = explode(':',$value);
		$courseId = $uniqueIdCourseBadgeArr[1];
		$courseObj = $courseRepository->find($courseId);
		$this->sendCampusRepProfileStatusMailToInternalTeam($actionType, $userId, $courseObj);
	    }
	    $this->_sendMailer($actionType,$userId,$mailerText);
	}else{
	foreach($courseBadgeRelationArr as $key=>$value){
	    $uniqueIdCourseBadgeArr = explode(':',$value);
	    $uniqueId = $uniqueIdCourseBadgeArr[0];
	    $courseId = $uniqueIdCourseBadgeArr[1];
	    $badge = $uniqueIdCourseBadgeArr[2];
		if($badge!='None'){
		    $courseObj = $courseRepository->find($courseId);
			$instiId = $courseObj->getInstituteId();
			$ListingCacheObj->deleteCampusRepCourseData($courseId);	
			$ListingCacheObj->deleteCampusRepInstituteData($instiId);	
			if($actionType!='removed'){
			$this->_sendMailer($actionType,$userId,$mailerText,$badge,$courseObj);
			$this->_sendCampusRepMailer($userId,$badge,$courseObj);
			$this->sendCampusRepProfileStatusMailToInternalTeam($actionType, $userId, $courseObj);
			}
		}
	}
	if($courseBadgeOfficialRelation!=''){
	    $uniqueIdCourseBadgeOfficialRelationArr = explode(':',$courseBadgeOfficialRelation);
	    $uniqueId = $uniqueIdCourseBadgeOfficialRelationArr[0];
	    $courseId = $uniqueIdCourseBadgeOfficialRelationArr[1];
	    $badge = $uniqueIdCourseBadgeOfficialRelationArr[2];
		if($badge!='None'){
	    $courseObj = $courseRepository->find($courseId);
			$instiId = $courseObj->getInstituteId();
			$ListingCacheObj->deleteCampusRepCourseData($courseId);	
			$ListingCacheObj->deleteCampusRepInstituteData($instiId);	
		if($actionType!='removed'){
			    $this->_sendMailer($actionType,$userId,$mailerText,$badge,$courseObj);
			    $this->_sendCampusRepMailer($userId,$badge,$courseObj);
		}
	}
	    }
	}
	echo 'done';
    }
    /*
	 @name: _sendMailer
	 @description: this function is to send mailers to users as per action performed.
	 For each action per bagde we send different mailers
	 @param string $userInput: No Input
	*/ 
    private function _sendMailer($actionType,$userId,$mailerText,$badge,$courseObj){
	$this->init();
	$this->load->library('mailerClient');
	$this->load->library('alerts_client');
	$appId = 1;
	$fromAddress=SHIKSHA_ADMIN_MAIL;
	$fromMail = "noreply@shiksha.com";
	$contentArr = array();
	$MailerClient = new MailerClient();
	$userDetails = $this->caenterprisemodel->getUserDetails($userId);
	$userEmail = $userDetails['email'];
	if($actionType=='accepted'){
	   // $subject = "Your have received a new message.";
	    $contentArr['userDetails'] = $userDetails;
	    $contentArr['type'] = "CAAcceptMailer";
	    $contentArr['badge'] = $badge;
	    $contentArr['courseObj'] = $courseObj;
	    $instId = $courseObj->getInstituteId();
	    $courseId = $courseObj->getId();
	   // $contentArr['parentId'] = $this->caenterprisemodel->getCategoryId($courseId);
	    $courseName = $courseObj->getName();
	    $contentArr['courseName'] = $courseName;
	    
	    
	    $this->load->builder("nationalInstitute/InstituteBuilder");
		$instituteBuilder = new InstituteBuilder();
		$instituteRepo = $instituteBuilder->getInstituteRepository();
        $result = $instituteRepo->find($instId);
	    /*$params = array(
				'instituteId'=>$instId,
				'instituteName'=>$result->getName(),
				'type'=>'institute',
				'abbrevation'=>$result->getAbbreviation()
			   );
	    $urlOfLandingPage = listing_detail_ask_answer_url($params); */

            $params = array(
                                'courseId'=>$courseObj->getId(),
                                'instituteName'=>$result->getName(),
                                'courseName'=>$courseObj->getName(),
                                'type'=>'course',
                                'course'=>$courseObj
                     );
           
            /* Landing Page Url called from CaDiscussion getCourseUrl function
             * changes done by Rahul
            */
            //$urlOfLandingPage = listing_campus_rep_url($params);
            //$url = Modules::run('CA/CADiscussions/getCourseUrl',$courseObj->getId());
            //$urlOfLandingPage = $url.'#connect-wrapp';
	    $urlOfLandingPage = SHIKSHA_HOME."/CA/CRDashboard/getCRUnansweredTab";

	    $contentArr['urlOfLandingPage'] = $urlOfLandingPage;
	    $contentArr['autoLoginUrl'] = $MailerClient->generateAutoLoginLink(1,$userEmail,$urlOfLandingPage);
	    $requested_page = SHIKSHA_HOME.'/CA/CRDashboard/getCRUnansweredTab'; 
        $resetlink = $this->getResetPasswordLink($requested_page, $userEmail); 
        $contentArr['resetlink']        = $resetlink; 
	    Modules::run('systemMailer/SystemMailer/campusAmbassadorAcceptMailer',$userEmail,$contentArr);
	}
	if($actionType=='deleted'){
	   // $subject = "Your have received a new message.";
	    //$urlOfLandingPage = SHIKSHA_HOME."/getTopicDetail/".$mailData[$i]['threadId'];
	    $contentArr['userDetails'] = $userDetails;
	    $contentArr['type'] = "CARejectMalier";
    	    $contentArr['badge'] = $badge;
	    $contentArr['courseObj'] = $courseObj;
	    Modules::run('systemMailer/SystemMailer/campusAmbassadorRejectMailer',$userEmail,$contentArr);
	}
	if($actionType=='incomplete'){
	    $urlOfLandingPage = SHIKSHA_HOME."/CA/CampusAmbassador/getCAProfileForm";
	    //$subject = "Your have received a new message.";
	    $contentArr['urlOfLandingPage'] = $urlOfLandingPage;
	    $contentArr['userDetails'] = $userDetails;
	    $contentArr['type'] = "CAIncompleteMailer";
    	    $contentArr['badge'] = $badge;
	    $contentArr['courseObj'] = $courseObj;
	    $contentArr['mailerText'] = $mailerText;
	    $contentArr['autoLoginUrl'] = $MailerClient->generateAutoLoginLink(1,$userEmail,$urlOfLandingPage);
            Modules::run('systemMailer/SystemMailer/campusAmbassadorIncompleteMailer',$userEmail,$contentArr);
	}
	//$content=$this->load->view("CAEnterprise/Mailer/CAMailer",$contentArr,true);
	//$AlertClientObj = new Alerts_client();
	//$alertResponse = $AlertClientObj->externalQueueAdd($appId,$fromAddress,$userEmail,$subject,$content,"html");
    }
    /*
	 @name: getOtherCourses
	 @description: this function is to get Other courses for CR mapping.
	 These other courses are combination of 3 things i.e. (Same Institute+ applied course locationId + same heirerchy)
	 @param string $userInput: No Input
	 @uther : akhter
	*/ 
    function getOtherCourses(){
		$this->init();
		$uniqueId    = isset($_POST['uniqueId'])?$this->input->post('uniqueId'):'0';
		$instituteId = isset($_POST['instituteId'])?$this->input->post('instituteId'):'0';
		$courseId    = isset($_POST['courseId'])?$this->input->post('courseId'):'0';
		$locationId  = isset($_POST['locationId'])?$this->input->post('locationId'):'0';
		$userId      = isset($_POST['userId'])?$this->input->post('userId'):'0';
		$badge       = isset($_POST['badge'])?$this->input->post('badge'):'None';
		$programId   = isset($_POST['programId'])?$this->input->post('programId'):1;

		$this->load->library('nationalCourse/CourseDetailLib'); 
	    $courseDetailLib = new CourseDetailLib; 
	    // step 1- get all courses of institute for the same location
	    $courseList = $courseDetailLib->getCourseForInstituteLocationWise($instituteId, array($locationId));
	    $courseIdsArr = array_unique($courseList[$locationId]); 
		// step 2- get all course object based on program like stream/substrem/base course
	    $this->campusConnectBaseLib = $this->load->library('CA/ccBaseLib');
		$responseData = $this->campusConnectBaseLib->filterCourseOnProgramId($courseIdsArr, $programId); 

		if(is_array($responseData) && count($responseData)>0){
			$dataArr['arrayOfCourseObj'] = $responseData;
		}
		$dataArr['selectedCourseId'] = $courseId;
		$dataArr['userId'] = $userId;
		$allCourseIds = $this->caenterprisemodel->allOtherCourseForCA($uniqueId,'live',$courseId,$badge);
		$dataArr['allCourseIds'] = $allCourseIds;
		$dataArr['badge'] = $badge;
		$dataArr['uniqueId'] = $uniqueId;
		echo $this->load->view('CAEnterprise/otherCourseSelectionPage',$dataArr);
    }
    /*
       @name: addOtherCourseWithCA
       @description: this function is to add Other courses with a Given Campus Ambassdor.
       After this action the campus ambassador has same badge for other courses also as he has for applied course.
       @param string $userInput: No Input
      */ 
    function addOtherCourseWithCA(){
	$this->init();
	$uniqueId = isset($_POST['uniqueId'])?$this->input->post('uniqueId'):'0';
	$mainCourseId = isset($_POST['mainCourseId'])?$this->input->post('mainCourseId'):'0';
	$userId = isset($_POST['userId'])?$this->input->post('userId'):'0';
	$badge = isset($_POST['badge'])?$this->input->post('badge'):'None';
	$otherCourseQueue = isset($_POST['chkdCourseQueueArray'])?$this->input->post('chkdCourseQueueArray'):'';
	$allCourseIds = $this->caenterprisemodel->allOtherCourseForCA($uniqueId,'',$mainCourseId,$badge);
	$otherCourseIdArr = array();
	if($otherCourseQueue!=''){
	    $otherCourseIdArr = explode(',',$otherCourseQueue);
	}
	$commonCourseIds  = array_intersect($allCourseIds,$otherCourseIdArr);
	$courseIdsForUpdateStatusDelete = array_diff($allCourseIds,$commonCourseIds);
	$courseIdsForInsertStatusLive = array_diff($otherCourseIdArr,$commonCourseIds);
	$appliedCourseIds = $this->caenterprisemodel->addOtherCourseWithCA($userId,$commonCourseIds,$courseIdsForUpdateStatusDelete,$courseIdsForInsertStatusLive,$badge,$mainCourseId,$uniqueId);
	$allOtherCourses = array_merge($commonCourseIds,$courseIdsForInsertStatusLive);
	$this->_sendCampusRepForOtherCoursesMailer($userId,$allOtherCourses);
	echo "done";
    }
    
    private function _sendCampusRepForOtherCoursesMailer($userId,$allOtherCourses){
		$this->load->builder('ListingBuilder','listing');
        $listingBuilder = new ListingBuilder;
        $courseRepository = $listingBuilder->getCourseRepository();
		foreach($allOtherCourses as $key=>$value){
			$courseObj = $courseRepository->find($value);
			$courseId = $courseObj->getId();
			$count = $this->caenterprisemodel->checkAcceptedCountOfCAForCourse($courseId);
			if($count==1){
				$this->_sendCampusRepMailer($userId,'',$courseObj);
			}
		}
    }
    /*
    function showRemoveUpdateLayer(){
	$this->init();
	$userId = isset($_POST['userId'])?$this->input->post('userId'):'0';
	$mainCourseData = $this->caenterprisemodel->getMainCourseData($userId);
	$this->load->builder('ListingBuilder','listing');
        $listingBuilder = new ListingBuilder;
	$this->load->model('listing/coursemodel');
	$this->coursemodel = new CourseModel();
	$this->load->library('CAUtilityLib');
	$caUtilityLib =  new CAUtilityLib;
        $instituteRepository = $listingBuilder->getInstituteRepository();
	$courseRepository = $listingBuilder->getCourseRepository();
	$arr = array();
	$courseIdsArr =array();
	foreach($mainCourseData as $key=>$value){
	    if($key=='official'){
		$courseIdOfficial = $value['officialCourseId'];
		$resultInsData = $instituteRepository->getLocationwiseCourseListForInstitute($value['officialInstituteId']);
		 foreach($resultInsData as $insLocationId=>$courseArr){
		    if($value['officialInstituteLocId'] == $insLocationId){
			    $courseIdsArr['official'] = $courseArr['courselist'];
		    }
		}
	    }else{
		foreach($value as $k=>$v){
		    $resultInsData = $instituteRepository->getLocationwiseCourseListForInstitute($v['instituteId']);
		    foreach($resultInsData as $insLocationId=>$courseArr){
			    if($v['locationId'] == $insLocationId){
				$courseIdsArr['main'][$insLocationId] = $courseArr['courselist'];
				$courseIdMain['main'][$insLocationId] = $v['courseId'];
			    }   
		    }
		}
	    }			
	    foreach($courseIdsArr as $type=>$data){
		if($type=='official'){
		    $allCourseWithSubCat = $this->coursemodel->getSubCategoryOfCourses($data);
		    $currentCourseSubCat = $allCourseWithSubCat[$courseIdOfficial];
		    $resultCoursesIds = $caUtilityLib->getCoursesOfSubcategory($currentCourseSubCat,$allCourseWithSubCat);
		    $arrayOfCourseObj = $courseRepository->findMultiple($resultCoursesIds);
		    $dataArr['arrayOfCourseObj'][$courseIdOfficial] = $arrayOfCourseObj;
		}else{
		    foreach($data as $key=>$value){
			$allCourseWithSubCat = $this->coursemodel->getSubCategoryOfCourses($value);
			$currentCourseSubCat = $allCourseWithSubCat[$courseIdMain[$type][$key]];
			$resultCoursesIds = $caUtilityLib->getCoursesOfSubcategory($currentCourseSubCat,$allCourseWithSubCat);
			if(!empty($resultCoursesIds)){
			    $arrayOfCourseObj = $courseRepository->findMultiple($resultCoursesIds);
			    $dataArr['arrayOfCourseObj'][$courseIdMain[$type][$key]] = $arrayOfCourseObj;
			}else{
			    $dataArr['arrayOfCourseObj'][$courseIdMain[$type][$key]] = '';
			}
		    }
		}
	    }
	}
	$result = $this->caenterprisemodel->getAllCADetails($appId='',$loggedInUserId='',$start=0,$rows=0,$moduleName='AnA',$filter='',$userNameFieldDataCA='',$filterTypeFieldDataCA='',$userId);
	$allCourseIds = $this->caenterprisemodel->allOtherCourseForCA($userId);
	$this->load->library('CAUtilityLib');
	$caUtilityLib =  new CAUtilityLib;
	$resultCA = $caUtilityLib->formatCAData($result);
	$dataArr['resultCA'] = $resultCA;
	$dataArr['userId'] = $userId;
	echo $this->load->view('CAEnterprise/showRemoveLayer',$dataArr);
    }
    
    function removeUpdateData(){
	$tmp =$_POST['jsonStr'];
	$tmp1 = json_decode($tmp,true);
	//$str = '{"data":[{"1":[{"badge":"CurrentStudent", "courseId":"145139" ,"fromDate":"03/10/2007", "toDate":"05/10/2011", "type":"main"}]},
	//{"2":[{"badge":"CurrentStudent", "courseId":"145139" ,"fromDate":"03/10/2007", "toDate":"0555/10/2011", "type":"main"}]},
	//{"2":[{"badge":"CurrentStudent", "courseId":"145139" ,"fromDate":"03/10/2007", "toDate":"0555/10/2011", "type":"main"}]}
	//]}';
    }
    
    function test($insId){
	$this->load->builder('ListingBuilder','listing');
        $listingBuilder = new ListingBuilder;
        $instituteRepository = $listingBuilder->getInstituteRepository();
	$result = $instituteRepository->getLocationwiseCourseListForInstitute($insId);_p($result);
    }
    
    function test1(){
	$this->load->builder('ListingBuilder','listing');
        $listingBuilder = new ListingBuilder;
        $courseRepository = $listingBuilder->getCourseRepository();
	$result = $courseRepository->find(107244);//_p($result->getInstituteName());
	//$res = $result->getLocations();
	_p($result);
    }*/
    
    
     /**
    * Get All discussions depending on the filters applied and then show the view
    */
  
  function getAllDiscussion() {
	$this->init();
	$cmsUserInfo = Modules::run('enterprise/Enterprise/cmsUserValidation');
	$userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $thisUrl = $cmsUserInfo['thisUrl'];
        $validity = $cmsUserInfo['validity'];
        $flagMedia = 1;
	$appId = 12;
	$loggedInUserId = isset($userid)?$userid:0;
	$filter = isset($_POST['Filter'])?$this->input->post('Filter'):'All';
	$start = isset($_POST['startFrom'])?$this->input->post('startFrom'):0;
	$rows = isset($_POST['countOffset'])?$this->input->post('countOffset'):5;
	$callType = isset($_POST['callType'])?$this->input->post('callType'):'';
	$instituteId = isset($_POST['instituteId'])?$this->input->post('instituteId'):'0';
        $keywordSuggest = isset($_POST['keywordSuggest'])?$this->input->post('keywordSuggest'):'';
	$cmsPageArr = array();
        $cmsPageArr['prodId'] = 784;
	if($userid=='1563'){
	    $cmsPageArr['prodId'] = 800;    
	}
        $cmsPageArr['validateuser'] = $validity;
        $cmsPageArr['headerTabs'] =  $cmsUserInfo['headerTabs'];
        $cmsPageArr['myProducts'] = $cmsUserInfo['myProducts'];
        $cmsPageArr['instituteId'] = $instituteId;
	$cmsPageArr['keywordSuggest'] = $keywordSuggest;
	$this->load->model('caenterprisemodel');
	$this->CAEnterpriseModel = new CAEnterpriseModel();
	$courseIds = $this->CAEnterpriseModel->getCACourseIds();	
	$historyData=$this->CAEnterpriseModel->getAllCourseDiscussions($start,$rows,$filter,$instituteId,$courseIds);
	$cmsPageArr['historyData'] = $historyData['data'];
	$cmsPageArr['caPageHeader']['subTabType'] = 2;
	$cmsPageArr['countOffset'] = $rows;
	$cmsPageArr['startFrom'] = $start;
	$cmsPageArr['filterSel'] = $filter;
	$cmsPageArr['totalDiscussionCount']=$historyData['total'];
	$cmsPageArr['historyDataOfCourse']=$historyData['historyDataOfCourse'];

	 if($callType=='Ajax'){
	    echo $cmsPageArr['totalDiscussionCount']."::".$this->load->view('CAEnterprise/ca_discussions_innerpart',$cmsPageArr);
	}else{
	    $this->load->view('CAEnterprise/ca_homepage',$cmsPageArr);
	}
   }
   
   /**********************
    * This function will make a DB call and archive any discussion related to a Course
    *
    *********************/
   function archiveDiscussion(){
	//Get POST Data
	$instituteId = $_POST['instituteId'];
	$courseId = $_POST['courseId'];
	$sessionYear = $_POST['sessionYear'];
	$title = $_POST['title'];
	$type = $_POST['type'];
	
	//Make Backend Call to Archive discussion
	$this->load->model('caenterprisemodel');
	$this->CAEnterpriseModel = new CAEnterpriseModel();
	$this->CAEnterpriseModel->archiveDiscussion($instituteId,$courseId,$sessionYear,$type,$title);
	
	//Send Mailer to all the last 15 Days responses of this Course
	//$this->_sendMailer('CAResponseMailer',$courseId)
	
	return;
   }

    /*
         @name: _sendMailer
         @description: this function is to send mailers to users as per action performed.
         @param string $userInput: No Input
        */
    /*private function _sendMailer($actionType,$courseId){
        $this->init();
        $this->load->library('mailerClient');
        $this->load->library('alerts_client');
        $appId = 1;
        $fromAddress=SHIKSHA_ADMIN_MAIL;
        $fromMail = "noreply@shiksha.com";
        $contentArr = array();
        $MailerClient = new MailerClient();
	$userDetails = $this->CAEnterpriseModel->getCourseResponses($courseId);
	
        if($actionType=='CAResponseMailer'){
           // $subject = "Your have received a new message.";
	    foreach($userDetails as $user){
		$this->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;
		$courseRepository = $listingBuilder->getCourseRepository();
		$courseObj = $courseRepository->find($courseId);
		$params["instituteName"] = $courseObj->getInstituteName();
		$params["courseId"] = $courseId;
		$params["course"] = $courseObj;
    
		$url = listing_campus_rep_url($params);
    
		$contentArr['urlOfLandingPage'] = $url;
		$contentArr['autoLoginUrl'] = $MailerClient->generateAutoLoginLink(1,$userEmail,$urlOfLandingPage);
		$contentArr['courseName'] = $courseObj->getName();
		$contentArr['type'] = "CAResponseMailer";
		$contentArr['name'] = $user['firstname'].' '.$user['lastname'];
		
		Modules::run('systemMailer/SystemMailer/campusAmbassadorResponseMailer',$userEmail,$contentArr);
	    }
        }
    }*/

    private function _sendCampusRepMailer($userId,$badge,$courseObj){
		$courseId = $courseObj->getId();
		$this->load->model('caenterprisemodel');
		$this->caenterprisemodel = new CAEnterpriseModel();
		$count = $this->caenterprisemodel->checkAcceptedCountOfCAForCourse($courseId);
		$contentArr = array();
		$this->load->model('CA/camodel');
		$this->camodel = new CAModel();
		$caUserInfo = $this->camodel->getAllCADetails($userId,'live');
		$instId = $courseObj->getInstituteId();
		$this->load->builder("nationalInstitute/InstituteBuilder");
		$instituteBuilder = new InstituteBuilder();
		$instituteRepo = $instituteBuilder->getInstituteRepository();
        $result = $instituteRepo->find($instId);
		$params = array(
					'courseId'=>$courseObj->getId(),
					'instituteName'=>$result->getName(),
					'courseName'=>$courseObj->getName(),
					'type'=>'course',
					'course'=>$courseObj
			 );
		
		
        /* Landing Page Url called from CaDiscussion getCourseUrl function
          * changes done by Rahul
        */
        //$urlOfLandingPage = listing_campus_rep_url($params);
        $url = Modules::run('CA/CADiscussions/getCourseUrl',$courseObj->getId());
        $urlOfLandingPage = $url.'#connect-wrapp';

        $this->load->library('mailerClient');
		$MailerClient = new MailerClient();
		$contentArr['caUserInfo'] = $caUserInfo;
		$contentArr['urlOfLandingPage'] = $urlOfLandingPage;
		if($count==1){
			$result = $this->caenterprisemodel->getResponseDataForCourseId($courseId);
			$contentArr['type'] = "REB";
			$contentArr['courseObj'] = $courseObj;
			$contentArr['badge'] = $badge;
			foreach($result as $key=>$value){
			$contentArr['userDetails'] = $value;
			$userEmail = $value['email'];
			$contentArr['autoLoginUrl'] = $MailerClient->generateAutoLoginLink(1,$userEmail,$urlOfLandingPage);
			Modules::run('systemMailer/SystemMailer/campusAmbassadorREBMailer',$userEmail,$contentArr);
			}
		}
    }

    public function sendCampusRepProfileStatusMailToInternalTeam($actionType, $userId, $courseObj)
    {
	$courseId = $courseObj->getId();
	$courseName = $courseObj->getName();
	$cityName = $courseObj->getMainLocation()->getCityName();
	$stateName = $courseObj->getMainLocation()->getStateName();
	$countryName = NATIONAL_COUNTRY_NAME;
	$instId = $courseObj->getInstituteId();
	$instName = $courseObj->getInstituteName();
	$locationStr = (($cityName!='')?', '.$cityName:'').(($stateName!='')?', '.$stateName:'').', '.$countryName;
	$instStr = $instName.$locationStr;
	
	$this->load->library('CAEnterprise/CAUtilityLib');
	$caUtilityLib =  new CAUtilityLib;
	$this->load->model('CA/camodel');
	$this->CAModel = new CAModel();
	$result = $this->CAModel->getAllCADetails($userId);
	$resultCA = $caUtilityLib->formatCAData($result);
	$userData = $resultCA[0]['ca'];
	$userName = $userData['firstname'].' '.$userData['lastname'];
	$userEmail = $userData['email'];
	$userMobile = $userData['mobile'];
	
	$this->load->library('alerts_client');
	$alertClient = new Alerts_client();
	$subject = "Submission $actionType for $userName, $instName";
	$emails = array('aneeket.barua@shiksha.com', 'saurabh.gupta@shiksha.com');
	$content = '<p>Hi,</p>
	<p>The following candidate\'s application has been marked '.$actionType.' by the moderator :</p>
	<p>&nbsp;</p>
	<div>
	<table>
	<tr>
		<td>Name</td>
		<td>:</td>
		<td>'.$userName.'</td>
	</tr>
	<tr>
		<td>Email</td>
		<td>:</td>
		<td>'.$userEmail.'</td>
	</tr>
	<tr>
		<td>Mobile</td>
		<td>:</td>
		<td>'.$userMobile.'</td>
	</tr>
	<tr>
		<td>Institute ID</td>
		<td>:</td>
		<td>'.$instId.'</td>
	</tr>
	<tr>
		<td>Institute</td>
		<td>:</td>
		<td>'.$instStr.'</td>
	</tr>
	<tr>
		<td>Course</td>
		<td>:</td>
		<td>'.$courseName.'</td>
	</tr>
	<tr>
		<td>Course ID</td>
		<td>:</td>
		<td>'.$courseId.'</td>
	</tr>
	</table>
	</div>
	<p>&nbsp;</p>
	<p>Best wishes,</p>
	<p>Shiksha.com</p>';
	for($i=0; $i<count($emails); $i++)
	{
		$alertClient->externalQueueAdd("12", ADMIN_EMAIL, $emails[$i], $subject, $content, "html", '');
	}
    }  
    
    
    /*
	 @name: init
	 @description: this is for cms user validation and load CampusAmbassadorEnterprise and Validate for Marketing user.
	 @param string $userInput: no paramaters
	*/
    function initForMarketing() {
        $this->userStatus = $this->checkUserValidation();
        if($this->userStatus!='false'){
       	       $emailId = explode('|',$this->userStatus[0]['cookiestr']);
	       if($emailId[0] != "marketing@shiksha.com"){
   		    header("location:/enterprise/Enterprise/disallowedAccess");
		    exit();
	       }
	}
	$this->load->model('CAEnterprise/caenterprisemodel');
	$this->caenterprisemodel = new CAEnterpriseModel();
    }
    
    /*
    @name       : myTask
    @description: this function allows to add, edit tasks for Campus Connect module
    @param      : $action = specifies the type of action required 
                  $id    = specifies id
    */
    function myTask($action = null,$id = null) {
		$this->initForMarketing();
		$cmsUserInfo       = Modules::run('enterprise/Enterprise/cmsUserValidation');
        $validity          = $cmsUserInfo['validity'];
        $this->load->model('CAEnterprise/mytaskmodel');
        $this->MyTaskModel = new MyTaskModel();
        $this->MyTaskModel->userInfo = $cmsUserInfo['validity'][0]['userid'];
        $cmsPageArr = array();
        $this->load->model('CA/camodel');
		$this->camodel = new CAModel();
		$result = $this->camodel->getAllCCPrograms();
		$cmsPageArr['taskConfig'] = $result;
        switch($action) {
            case 'getTask'   :  $cmsPageArr['taskData'] =  $this->MyTaskModel->getTaskData($id);
                                break;
            case 'live'      :  $this->MyTaskModel->udpateStatus($id,'live');
								$this->sendMailToAllCRForNewOpenTask($id);
                                break;
            case 'stop'      :  $this->MyTaskModel->udpateStatus($id,'stop');
                                break;
        }
        //$cmsPageArr['myTasks']      = $this->MyTaskModel->getAllTasks();
        $cmsPageArr['validateuser'] = $validity;
        $cmsPageArr['headerTabs']   =  $cmsUserInfo['headerTabs'];
        $cmsPageArr['myProducts']   = $cmsUserInfo['myProducts'];
		$cmsPageArr['caPageHeader']['subTabType'] = 3;
		$cmsPageArr['prodId']       = 800;

	
        $this->load->view('CAEnterprise/ca_homepage',$cmsPageArr);
   }

   function getTaskListData(){
   		$programId = $this->input->post('programId');

   		$this->load->model('CAEnterprise/mytaskmodel');
        $this->MyTaskModel = new MyTaskModel();
     
        $cmsPageArr['myTasks']      = $this->MyTaskModel->getTasksByCategory($programId);
   		$result = $this->load->view('CAEnterprise/ca_myTask_list',$cmsPageArr,true);
   		echo $result;
   		exit;
   }


   /*
    @name       : myTask
    @description: this function saves task for Campus Connect module
    @param      : $action = specifies the type of action required 
                  $id    = specifies id
    */
   function processMyTaskData($action,$id) {
        $this->initForMarketing();
	$cmsUserInfo       = Modules::run('enterprise/Enterprise/cmsUserValidation');
        $this->load->model('CAEnterprise/mytaskmodel');
	$this->MyTaskModel = new MyTaskModel();
        $this->MyTaskModel->userInfo = $cmsUserInfo['validity'][0]['userid'];
        switch($action) {
            case 'create'    :  if($id == 0)
                                    echo $this->MyTaskModel->add();
                                else
                                    echo $this->MyTaskModel->updateMyTask($id);
                                break;
        }
        
   }
   
   function sendMailToAllCRForNewOpenTask($taskId)
   {
	$this->load->model('CAEnterprise/mytaskmodel');
	$this->MyTaskModel = new MyTaskModel();
	$taskDetails = $this->MyTaskModel->getTaskDetails($taskId);
	if($taskDetails['status'] == 'live')
	{
	    $contentArr = array();
	    $attachment = array();
	    $dashboard_task = SHIKSHA_HOME.'/CA/CRDashboard/myTaskTab/open/'.$taskId;
	    
	    //added by akhter to send mail to specific category user
	    $this->load->model('qnamodel');
	    $allCRData = $this->qnamodel->getAllCRDetailsForNewOpenTaskMail($taskDetails['programId']);
    
	    $this->load->library('mailerClient');
	    $MailerClient = new MailerClient();
	    
	    if(!empty($allCRData))
	    {
		foreach($allCRData as $cr)
		{
		    $contentArr['crName'] = $cr['firstname'].' '.$cr['lastname'];
		    $contentArr['taskName'] = $taskDetails['name'];
		    $contentArr['type'] = 'CANewOpenTask';
		    $contentArr['urlOfLandingPage']	= $dashboard_task;
		    $contentArr['autoLoginUrl'] = $MailerClient->generateAutoLoginLink(1, $cr['email'], $dashboard_task);
		    Modules::run('systemMailer/SystemMailer/CRNewOpenTaskMail', $cr['email'], $contentArr, $attachment);
		}
	    }
	}
   }
   
   function crWallet()
   {
	$this->initForMarketing();
	$cmsUserInfo = Modules::run('enterprise/Enterprise/cmsUserValidation');
	$userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $thisUrl = $cmsUserInfo['thisUrl'];
        $validity = $cmsUserInfo['validity'];
        $flagMedia = 1;

	$appId = 12;
	$start = isset($_POST['startFrom'])?$this->input->post('startFrom'):0;
	$rows = isset($_POST['countOffset'])?$this->input->post('countOffset'):10;
	
	$cmsPageArr = array();
        $cmsPageArr['prodId'] = 781;
	if($userid=='1563'){
	    $cmsPageArr['prodId'] = 800;    
	}
        $cmsPageArr['validateuser'] = $validity;
        $cmsPageArr['headerTabs'] =  $cmsUserInfo['headerTabs'];
        $cmsPageArr['myProducts'] = $cmsUserInfo['myProducts'];
	$loggedInUserId = isset($userid)?$userid:0;
	$cmsPageArr['caPageHeader']['subTabType'] = 5;
	$cmsPageArr['countOffset'] = $rows;
	$cmsPageArr['startFrom'] = $start;
        $this->load->library('CA/Mywallet');
	
        $cmsPageArr['result'] = $this->mywallet->getAllCRHavingEarnings($start, $rows);
	$cmsPageArr['totalCount'] = $cmsPageArr['result']['result']['totalRows'];
	$callType = isset($_POST['callType'])?$this->input->post('callType'):'';
	if($callType=='Ajax'){
	    echo $cmsPageArr['totalCount'] ."::".$this->load->view('CAEnterprise/cr_wallet_inner_page',$cmsPageArr);
	}else{
	    $this->load->view('CAEnterprise/ca_homepage',$cmsPageArr);    
	}
   }
   
   function getSubmissions($taskId) {
	$this->initForMarketing();
	$cmsUserInfo       = Modules::run('enterprise/Enterprise/cmsUserValidation');
        
        $validity          = $cmsUserInfo['validity'];
        $this->load->model('CAEnterprise/mytaskmodel');
        $this->MyTaskModel = new MyTaskModel();
        $this->MyTaskModel->userInfo = $cmsUserInfo['validity'][0]['userid'];
	
	$start = isset($_POST['startFrom'])?$this->input->post('startFrom'):0;
	$rows = isset($_POST['countOffset'])?$this->input->post('countOffset'):5;
	$callType = isset($_POST['callType'])?$this->input->post('callType'):'';
	$cmsPageArr = array();
	$cmsPageArr['countOffset'] = $rows;
	$cmsPageArr['startFrom'] = $start;
	$cmsPageArr['taskId'] = $taskId  = isset($_POST['taskId'])?$this->input->post('taskId'):$taskId;
	$submissionData =  $this->MyTaskModel->getSubmittedTasks($taskId, $start,$rows);
	$uniqueArr = array();
	$taskName = '';
	foreach($submissionData['resultSet'] as $key=>$value){
	    $submissionInfo['userInfo'][$value['userId']]['userId'] = $value['userId'];
	    $submissionInfo['userInfo'][$value['userId']]['taskName'] = $value['taskName'];
	    $submissionInfo['userInfo'][$value['userId']]['displayName'] = $value['displayName'];
	    $submissionInfo['userInfo'][$value['userId']]['rewardAmout'] = $value['rewardAmout'];
	    $submissionInfo['userInfo'][$value['userId']]['caSubmissions']['fileName'] = $value['fileName'];
	    $submissionInfo['userInfo'][$value['userId']]['caSubmissions']['url'] = $value['url'];
	    $taskName = $value['taskName'];
	    $instIds[] = $value['instituteId'];
	    $instToUserIdMapping[$value['instituteId']][] = $value['userId'];
	    $submissionInfo['userInfo'][$value['userId']]['taskId'] = $taskId;
	}
	if(!empty($submissionData)){
	    $submissionInfo['totalUsers'] = $submissionData['totalUsers'];
	    $instIds = array_unique($instIds);
	    $this->load->builder('ListingBuilder','listing');
	    $listingBuilder = new ListingBuilder;
	    $instituteRepository = $listingBuilder->getInstituteRepository();
	    $result = $instituteRepository->findMultiple($instIds);
	    foreach($result as $key=>$value){
		foreach($instToUserIdMapping[$value->getId()] as $k=>$v){
		    $submissionInfo['userInfo'][$v]['instituteName'] = $value->getName();
		}
		
	    }
	}else{
	    $submissionInfo['userInfo'] = array();
	}
	$cmsPageArr['taskName'] = $taskName;
	$cmsPageArr['submissionInfo'] = $submissionInfo;
        $cmsPageArr['validateuser'] = $validity;
        $cmsPageArr['headerTabs']   =  $cmsUserInfo['headerTabs'];
        $cmsPageArr['myProducts']   = $cmsUserInfo['myProducts'];
	$cmsPageArr['caPageHeader']['subTabType'] = 4;
	$userid = $cmsUserInfo['userid'];
	if($userid=='1563'){
	    $cmsPageArr['prodId'] = 800;    
	}
	$parameterObj = array('abuse' => array('offset'=>-1,'totalCount'=>0,'countOffset'=>5));
	$parameterObj['abuse']['offset'] = 0;
	$parameterObj['abuse']['totalCount'] =  $submissionData['totalUsers'];
	$cmsPageArr['parameterObj'] = json_encode($parameterObj);
	
	if($callType=='Ajax'){
	    echo $submissionData['totalUsers']."::".$this->load->view('CAEnterprise/mytask_submission_innerpart',$cmsPageArr);
	}
	else{
	    $this->load->view('CAEnterprise/ca_homepage',$cmsPageArr);    
	}
   }
   
   function makePaymentForTask(){
	$this->init();
	$amount = $this->input->post('amount');
	$userId = $this->input->post('userId');
	$taskId = $this->input->post('taskId');
	$this->load->model('CAEnterprise/mytaskmodel');
        $this->MyTaskModel = new MyTaskModel();
	$this->MyTaskModel->makePaymentForTask($amount,$userId,$taskId);
   }
   
      
   function downloadAllSubmissions($taskId){
	$columnListArray = array();
	$columnListArray[]='User Id';
	$columnListArray[]='User Name';
	$columnListArray[]='Institute Name';
	$columnListArray[]='Submissions';
	$ColumnList = $columnListArray;
	$csv = '';
	foreach ($ColumnList as $ColumnName){
		$csv .= '"'.$ColumnName.'",';
	}
	$csv .= "\n";
	
	$this->load->model('CAEnterprise/mytaskmodel');
        $this->MyTaskModel = new MyTaskModel();
	$submissionData =  $this->MyTaskModel->getSubmittedTasks($taskId, '-1','-1');
	$uniqueArr = array();
	$taskName = '';
	foreach($submissionData['resultSet'] as $key=>$value){
	    $submissionInfo['userInfo'][$value['userId']]['userId'] = $value['userId'];
	    $submissionInfo['userInfo'][$value['userId']]['displayName'] = $value['displayName'];
	    $taskName = $value['taskName'];
	    $instIds[] = $value['instituteId'];
	    $instToUserIdMapping[$value['instituteId']][] = $value['userId'];
	}
	if(!empty($submissionData)){
	    $submissionInfo['totalUsers'] = $submissionData['totalUsers'];
	    $instIds = array_unique($instIds);
	    $this->load->builder('ListingBuilder','listing');
	    $listingBuilder = new ListingBuilder;
	    $instituteRepository = $listingBuilder->getInstituteRepository();
	    $result = $instituteRepository->findMultiple($instIds);
	    foreach($result as $key=>$value){
		foreach($instToUserIdMapping[$value->getId()] as $k=>$v){
		    $submissionInfo['userInfo'][$v]['instituteName'] = $value->getName();
		}
		
	    }
	}
	foreach($submissionData['resultSet'] as $key=>$value){
	    $submissionInfo['userInfo'][$value['userId']]['caSubmissions']['url'] = $value['url'];
	}
	
	foreach($submissionInfo['userInfo'] as $key=>$value){
	    foreach($value as $k=>$v){
		if($k=='caSubmissions'){
		    foreach($v['url'] as $a=>$b){
			$csv .= $b.',';
			$csv .= "\n";
			$csv .= ',';
			$csv .= ',';
			$csv .= ',';
		    }
		    $csv .= ',';
		}else{
		    $csv .= '"'.$v.'",';    
		}
	    }
	    $csv .= "\n";
	} 
	
	//_p($submissionInfo);die;
	header("Content-type: application/csv");
	header("Content-Disposition: attachment; filename=Submissions_taskId_".$taskId."_".date('Y-m-d').".csv");
	header("Pragma: no-cache");
	header("Expires: 0");
	print_r($csv);
   }
   
   	function getCollegeReviews() {
		if(CR_MOD_SOLR_FLAG){
			$this->getCollegeReviewsFromSolr();
		}else{
			$this->isUserGroupCRModerator = true;
			$this->init();
			$start = 0;
			$count = 10;
			$cmsUserInfo = Modules::run('enterprise/Enterprise/cmsUserValidation');
			if($this->input->is_ajax_request()){
			    $page_number = filter_var($this->input->post('page'), FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
			}
			$data 		= array();
			$userid 	= $cmsUserInfo['userid'];
	        $usergroup 	= $cmsUserInfo['usergroup'];
	        $thisUrl 	= $cmsUserInfo['thisUrl'];
	        $validity 	= $cmsUserInfo['validity'];
			$cookiestr 	= explode('|', $validity[0]['cookiestr']);
			$useremail 	= $cookiestr[0];
			$this->benchmark->mark('get_review_postfix_start');
			//$review_filter_postfix = $this->getReviewPostfixForLoggedInUser($useremail);

		$this->benchmark->mark('get_review_postfix_start');
		//$review_filter_postfix = $this->getReviewPostfixForLoggedInUser($useremail);

		$review_filter_postfix = $this->generateReviewsModeratorMap($userid);
		
		$this->benchmark->mark('get_review_postfix_end');

        $flagMedia = 1;
		$appId = 12;
		
		$cmsPageArr = array();

        $cmsPageArr['prodId'] = 802;
		if($userid == '1563'){
		    //$cmsPageArr['prodId'] = 800;    
		}
		$cmsPageArr['useremail'] 				  = $useremail;
        $cmsPageArr['validateuser'] 		 	  = $validity;
        $cmsPageArr['headerTabs'] 				  = $cmsUserInfo['headerTabs'];
        $cmsPageArr['myProducts'] 				  = $cmsUserInfo['myProducts'];
		$loggedInUserId 						  = isset($userid)?$userid:0;
		$cmsPageArr['caPageHeader']['subTabType'] = 6;
		$cmsPageArr['countOffset'] 				  = $rows;
		$cmsPageArr['startFrom']      			  = $start;
		
		$this->load->builder("nationalCourse/CourseBuilder");
		$courseBuilder 					= new CourseBuilder();
		$this->courseRepository 		= $courseBuilder->getCourseRepository();
		$cmsPageArr['courseRepository'] = $this->courseRepository;
		
		$this->load->builder("nationalInstitute/InstituteBuilder");
		$instituteBuilder 				   = new InstituteBuilder();
		$this->instituteRepository 		   = $instituteBuilder->getInstituteRepository();
		$cmsPageArr['instituteRepository'] = $this->instituteRepository;
		
		$checkForSearchCall 			   = $this->input->post('checkSearchCall'); // variable to check which button this function is being called from
		$cmsPageArr['checkForSearchCall']  = $checkForSearchCall;
		$streamList  					   = new \registration\libraries\FieldValueSources\Stream;
	    $cmsPageArr['streamList']  		   = $streamList->getValues();

		//Search by email id in college review
		if($checkForSearchCall == 'email'){

			$email 						  = $this->input->post('email');
			$cmsPageArr['email'] 		  = trim($email);
		    $cmsPageArr['statusFilter']   = 'Pending'; 
		    $cmsPageArr['instituteName']  = '';
		    $cmsPageArr['typeFilter']     = '';
		    $cmsPageArr['sortReviews']    = isset($_POST['sortReviews'])?$this->input->post('sortReviews'):'';
		    $cmsPageArr['categoryFilter'] = '';
		    
		    $this->benchmark->mark('get_review_source_start');
		    $sources 					  = $this->reviewenterprisemodel->getSourcesFromMainTable();
		    $cmsPageArr['reviewSource']   = $this->_getSourceFilter($sources);
		    $this->benchmark->mark('get_review_source_end');

		} else {
			
			$cmsPageArr['email'] = '';
		    $cmsPageArr['statusFilter'] 	= isset($_POST['statusFilter'])?$this->input->post('statusFilter'):'Pending';
		    
		    if($cmsPageArr['statusFilter'] == 'Later' || $cmsPageArr['statusFilter'] == 'Rejected'){

		    	$cmsPageArr['reasonFilter'] = isset($_POST['reasonFilter'])?$this->input->post('reasonFilter'):'All';
				$this->benchmark->mark('get_review_reason_start');
		    	$cmsPageArr['reasons'] = $this->reviewenterprisemodel->getCollegeReviewReasons($cmsPageArr['statusFilter']);
				$this->benchmark->mark('get_review_reason_end');

		    }

		    $cmsPageArr['instituteName']  = isset($_POST['instituteName'])?$this->input->post('instituteName'):'';
		    $cmsPageArr['typeFilter']     = isset($_POST['typeFilter'])?$this->input->post('typeFilter'):'';
		    $cmsPageArr['sortReviews']    = isset($_POST['sortReviews'])?$this->input->post('sortReviews'):'';
		    $cmsPageArr['categoryFilter'] = isset($_POST['categoryFilter'])?$this->input->post('categoryFilter'):'All';
		    $cmsPageArr['sourceFilter']   = isset($_POST['sourceFilter'])?$this->input->post('sourceFilter'):'All';
		    
		    //new filters added
		    $cmsPageArr['moderator_list']  		   	   = $this->input->post('moderator_list',true);
		    $cmsPageArr['phone_search']  		   	   = $this->input->post('phone_search',true);
		    $cmsPageArr['posted_timeRange']['From']    = $this->input->post('posted_timeRangeFrom',true);
		    $cmsPageArr['posted_timeRange']['To'] 	   = $this->input->post('posted_timeRangeTo',true);
		    $cmsPageArr['moderated_timeRange']['From'] = $this->input->post('moderated_timeRangeFrom',true);
		    $cmsPageArr['moderated_timeRange']['To']   = $this->input->post('moderated_timeRangeTo',true);

		    $this->benchmark->mark('get_review_source_start');
		    $sources 					  = $this->reviewenterprisemodel->getSourcesFromMainTable();
		    $cmsPageArr['reviewSource']   = $this->_getSourceFilter($sources);
		    $this->benchmark->mark('get_review_source_end');

		}

		if($this->input->is_ajax_request()){
		    $cmsPageArr['start'] 		 = isset($_POST['start'])?$this->input->post('start'):$start;
		    $cmsPageArr['startUnmapped'] = isset($_POST['startUnmapped'])?$this->input->post('startUnmapped'):0;
			
			if($cmsPageArr['startUnmapped']<1){
				$cmsPageArr['startUnmapped']=0;
			}

		}else{
		    $cmsPageArr['start'] 		 = 0;
		    $cmsPageArr['startUnmapped'] = 0;
		}

		$cmsPageArr['count']  = isset($_POST['count'])?$this->input->post('count'):$count;
		$cmsPageArr['result'] = array();
		$this->benchmark->mark('get_review_data_start');
		
		// customize moderated by filter for particular email ids
		$showModeratedByFilter 	 = false;
		$whiteListEmailForFilter = $this->config->item('whiteListEmailForFilter');
		
		if(in_array($useremail, $whiteListEmailForFilter)){
			$showModeratedByFilter = true;
		}

        $cmsPageArr['showModeratedByFilter'] = $showModeratedByFilter;
		$searchResult 						 = $this->reviewenterprisemodel->getCollegeReviewData($cmsPageArr, $review_filter_postfix);

		$this->benchmark->mark('get_review_data_end');
		
		$cmsPageArr['result'] 	     = $searchResult;
		$cmsPageArr['startUnmapped'] = $searchResult['startUnmapped'];

		if(isset($cmsPageArr['result']['num_rows'])){
		    $cmsPageArr['num_rows'] = $cmsPageArr['result']['num_rows'];
		}/*else{
		     $cmsPageArr['num_rows'] = count($cmsPageArr['result']-2);
		}*/

		$this->benchmark->mark('get_review_total_awaiting_data_start');
		$cmsPageArr['totalAwaitingReviews'] = $this->reviewenterprisemodel->totalAwaitingReviews($review_filter_postfix);
		$this->benchmark->mark('get_review_total_awaiting_data_end');

		/*
		$this->benchmark->mark('get_review_total_pending_data_start');
		$this->benchmark->mark('get_review_total_awaiting_data_end');
		$this->benchmark->mark('get_review_total_pending_data_start');
		$cmsPageArr['totalPendingReviewsInstituteWise']=$this->reviewenterprisemodel->getTotalPendingReviewsInstituteWise();
		$this->benchmark->mark('get_review_total_pending_data_end');
		$this->benchmark->mark('get_review_total_ignored_data_start');
		$cmsPageArr['totalIgnoredReviewsInstituteWise']=$this->reviewenterprisemodel->getTotalIgnoredReviewsInstituteWise();
		$this->benchmark->mark('get_review_total_ignored_data_end');
		$this->benchmark->mark('get_review_total_published_data_start');
		$cmsPageArr['totalPublishedReviewsInstituteWise']=$this->reviewenterprisemodel->getTotalPublishedReviewsInstituteWise();
		$this->benchmark->mark('get_review_total_published_data_end');
		$cms=array_merge($cmsPageArr['totalPendingReviewsInstituteWise'],$cmsPageArr['totalIgnoredReviewsInstituteWise'],$cmsPageArr['totalPublishedReviewsInstituteWise']);
		
		$val = Array();

		foreach($cms as $cmss)
		{   
		    if($cmss->totalPendingInstituteCount>0){
			$val[$cmss->instituteId]['totalPending'] = $cmss->totalPendingInstituteCount;
		    }
	            else if($cmss->totalIgnoredInstituteCount>0){
	                $val[$cmss->instituteId]['totalIgnored'] = $cmss->totalIgnoredInstituteCount;
	            }
	            if($cmss->totalPublishedInstituteCount>0){
	                $val[$cmss->instituteId]['totalPublished'] = $cmss->totalPublishedInstituteCount;
	            }	    
		    
		}

		$cmsPageArr['InstituteCounts']=$val;
		*/

		$this->benchmark->mark('get_review_total_data_by_status_start');
		$cmsPageArr['InstituteCounts'] = $this->reviewenterprisemodel->getTotalReviewsInstituteAndStatusWise();
		$this->benchmark->mark('get_review_total_data_by_status_end');

		if(isset($_POST['email_status_case'])) {
		    $cmsPageArr['openFilterTab'] = 1;
		} 
	
		// $cmsPageArr['result'] = array_slice($cmsPageArr['result'],$cmsPageArr['start'],$count);
	
		$cmsPageArr['checkForSearchCall'] = $checkForSearchCall;
		$this->benchmark->mark('get_review_details_start');
		$cmsPageArr['result'] 			  = $this->getReviewRatingDetails($cmsPageArr['result']);
		$this->benchmark->mark('get_review_details_end');
		$this->benchmark->mark('get_moderation_details_start');
		$cmsPageArr['result'] 			  = $this->getModerationDetails($cmsPageArr['result']);
		$this->benchmark->mark('get_moderation_details_end');
		
		//load moderators email ids
		$this->load->config('CollegeReviewForm/collegeReviewConfig');
		$cmsPageArr['collegeReviewModerators'] = $this->config->item('collegeReviewModerators');

		if($this->input->is_ajax_request()){	
		    echo $this->load->view('CAEnterprise/college_reviews',$cmsPageArr);
		}else{
		    $this->load->view('CAEnterprise/ca_homepage',$cmsPageArr);	
		}
	
    	}
	}
   
   function getReviewRatingDetails($reviewDetailsObj){

   	$this->load->model('CollegeReviewForm/collegereviewmodel');
	$this->collegereviewmodel = new collegereviewmodel();

   		foreach ($reviewDetailsObj as $key => $review) {

   			$reviewId = $review->reviewId;


   			$reviewDetail = $this->collegereviewmodel->getRatingReviewId($reviewId);
   			$reviewDetail = $this->formatReviewDetail($reviewDetail);

   			/*$motivationDescription = $this->collegereviewmodel->getMotivationDescription($reviewId);

  
   			$review->motivationDescription = $motivationDescription; */
   			$review->reviewDetail = $reviewDetail;

   		}
   		
   		return $reviewDetailsObj;
   }


   function formatReviewDetail($reviewArray){
   		foreach ($reviewArray as $value) {
   			$temp[$value['description']] = $value['rating'];
   		}

   		return $temp;
   }


   function markAllReviewRejected($cr_data){
   		ini_set('memory_limit', '1024M');
   		set_time_limit(30);

   		$all_review_ids = array();
   		$status = 'rejected';
		$reason = 15;	

		$show_buttom = false;

   		foreach ($cr_data['collegeReviewListing'] as $review_data) {
   			$review_data_array = json_decode(json_encode($review_data), True);
   			$all_review_ids[] = $review_data_array['reviewId'];

   			$this->processBulkCRMailers($review_data_array,$show_buttom);
   		}

   		if($all_review_ids[0]<1){
   			return;
   		}
   		
		$getDetailFromCookie = explode('|',$_COOKIE['user']);
		$moderatorEmail = $getDetailFromCookie[0];
		unset($getDetailFromCookie);

		//insert into indexing queue
		$insert_data = $this->formatInsertQueueData($all_review_ids);
		$this->reviewenterprisemodel->insertBatchReviewForIndex($insert_data);
		unset($insert_data);

		//update review status
		$this->reviewenterprisemodel->updateBulkReviewStatus($status,$all_review_ids,$reason);

		//insert moderator details
		$insert_data = $this->formatModerationData($all_review_ids, $moderatorEmail,$status);
		$this->reviewenterprisemodel->storeBatchModerationDetails($insert_data);
	
		unset($insert_data);		 
		//insert in tracking table
		$insert_data = $this->formatCRTrackingData($all_review_ids, $this->userStatus[0]['userid']);
		$this->reviewenterprisemodel->insertBatchTrackingData($insert_data);

   }

   function formatCRTrackingData($all_review_ids, $moderator_id){

   		//add reason here
   		$trackingData = array('status' => 'rejected', 'reason' => 15);
   		$trackingData = json_encode($trackingData);

	   	$return_data = array();

   		foreach ($all_review_ids as $reviewId) {
   			$temp = array();
   			$temp['reviewId'] 			= $reviewId;
   			$temp['addedBy'] 			= $moderator_id;
   			$temp['action'] 			= 'statusUpdated';//"" => $data 
   			$temp['data'] 				= $trackingData;
   			
   			$return_data[] = $temp;
   		}

   		return $return_data;
   }

   function formatInsertQueueData($all_review_ids){
        $return_data = array();

   		foreach ($all_review_ids as $reviewId) {
   			$temp = array();
   			$temp['operation'] 			= 'index';
   			$temp['listing_type'] 		= 'collegereview';
   			$temp['listing_id'] 		= $reviewId;
   			
   			$return_data[] = $temp;
   		}

   		return $return_data;
   }

   function formatModerationData($all_review_ids, $moderatorEmail,$status){
   		$return_data = array();

   		foreach ($all_review_ids as $reviewId) {
   			$temp = array();
   			$temp['reviewId'] 				= $reviewId;
   			$temp['moderatorEmail'] 		= $moderatorEmail;
   			$temp['moderationTime'] 		= date('Y-m-d h:i:s');
   			$temp['actionType'] 			= $status;

   			$return_data[] = $temp;
   		}

   		unset($temp);

   		return $return_data;
   }

   function updateReviewStatus(){
		$this->init();

		$this->load->builder("nationalCourse/CourseBuilder");
		$courseBuilder = new CourseBuilder();
		$this->courseRepository = $courseBuilder->getCourseRepository();
		
		$status = isset($_POST['status'])?$this->input->post('status'):''; 
		$reviewId = isset($_POST['reviewId'])?$this->input->post('reviewId'):'';
		$reason = isset($_POST['reason'])?$this->input->post('reason'):'';
		$getDetailFromCookie = explode('|',$_COOKIE['user']);
		$moderatorEmail = $getDetailFromCookie[0];
		
		$review = $this->reviewenterprisemodel->getReviewDetails($reviewId);
		
		$this->reviewenterprisemodel->updateReviewStatus($status,$reviewId,$reason);
		$this->reviewenterprisemodel->storeModerationDetails($reviewId, $moderatorEmail,$status);

		$reviewerName = $review[0]['firstname'];
		$courseId = $this->reviewenterprisemodel->getReviewsCouresId($reviewId);

		$this->load->model('CollegeReviewForm/collegereviewmodel');
		$collegereviewmodel = new CollegeReviewModel;
		
		/*$categoryDomainMapping = array('3'=>'management', '2'=>'engineering', '4'=>'banking', '10'=>'it');
		$idsArray = $collegereviewmodel->getCatSubCatIdsByCourseId($courseId);
		$categoryId = $idsArray['categoryId'];
		$subCategoryId = $idsArray['subCategoryId'];

		$domain = '';
		if($categoryDomainMapping[$categoryId]){
			$domain = $categoryDomainMapping[$categoryId];
		} */
		
		if($courseId){
			$courseObj = $this->courseRepository->find($courseId,array('basic'));
			$courseName = $courseObj->getName();
			$instituteName = $courseObj->getInstituteName();
		}


		$showEditReviewButtonInMail = false;
		if($status == 'rejected'){		
			$this->load->config('CollegeReviewForm/collegeReviewConfig');
			$crRejectedReasonMapping = $this->config->item('crRejectedReasonMapping');
			if((!empty($reason) && $crRejectedReasonMapping[$reason]) || empty($crRejectedReasonMapping)){
				$showEditReviewButtonInMail = true;
			}
		}

		Modules::run('CollegeReviewForm/CollegeReviewForm/collegeReviewMailers', $status, $review[0]['email'], $review[0]['reviewerId'], $review[0]['userId'], $reviewId,$review[0]['incentiveFlag'],$showEditReviewButtonInMail);

		
		//insert into indexing queue
		Modules::run('CollegeReviewForm/SolrIndexing/insertReviewForIndex',$reviewId);

		// update index on solr
		if($status == 'published' || $status == 'rejected' || $status =='accepted' || $status == 'unverified')
		{
		    
		    if($courseId!='' && $courseId>0){

		    	$this->load->library('indexer/NationalIndexingLibrary');
		        $this->lib3 = new NationalIndexingLibrary;
		        $listingType = "course";
		        $this->lib3->addToNationalIndexQueue($courseId,$listingType,'index',array('courseReviewsSectionData'));
		    	/*Modules::run ( 'search/Indexer/delete', $courseId, "course",false);
		    	Modules::run ( 'search/Indexer/index', $courseId, "course",false);*/
		    }
		}

		// track college review		
		if(!empty($reason)){
			$trackingData = array('status' => $status, 'reason' => $reason);
		}else{
			$trackingData = array('status' => $status);
		}

		$caUtilityLib = $this->load->library('CAEnterprise/CAUtilityLib');
		$caUtilityLib->trackCollegeReview($reviewId, 'statusUpdated', $trackingData, $this->userStatus[0]['userid']);
    }

    function processBulkCRMailers($data, $showEditReviewButtonInMail = false, $delayedMailTime = ''){
    	
		$attachment = array();
		$contentArr = array();
	
		$this->load->library('CollegeReviewForm/CollegeReviewLib');
		$this->CollegeReviewLib = new CollegeReviewLib();

		$this->load->builder("nationalCourse/CourseBuilder");
		$courseBuilder = new CourseBuilder();
		$this->courseRepository = $courseBuilder->getCourseRepository();

		
		if($data['courseId']){
			$courseObj = $this->courseRepository->find($data['courseId'],array('basic'));
			$data['courseName'] = $courseObj->getName();
			$data['instituteName'] = $courseObj->getInstituteName();
		}
		
		if($data['isShikshaInstitute']=='NO'){
			$contentArr['college_name']  = $data['instituteName'];
		}
		
		if($data['isShikshaInstitute']=='YES'){
		
			if($data['courseId']>0)
			{
				$contentArr['college_name']  =  $data['instituteName'];
			}
			
		}
	
		$contentArr['username']  = ucwords($data['firstname']);
		$contentArr['mobile'] = $data['mobile'];
		$contentArr['email'] = $data['email'];
		$contentArr['reviewDescription']  = $data['reviewDescription'];
		$contentArr['placementDescription'] = $data['placementDescription'];
		$contentArr['infraDescription'] = $data['infraDescription'];
		$contentArr['facultyDescription'] = $data['facultyDescription'];
		$contentArr['reviewTitle'] = $data['reviewTitle'];
		$contentArr['showEditReviewButtonInMail'] = $showEditReviewButtonInMail;
		$incentiveFlag = $data['incentiveFlag'];
		
		$form_url = SHIKSHA_HOME.'/college-review-form';
		$contentArr['incentiveFlag'] = '';
		if($incentiveFlag == '1'){
			$form_url = SHIKSHA_HOME.'/college-review-rating-form';
			$contentArr['incentiveFlag'] = 'incentive';
		}

		$email = $data['email'];
		$revid = $data['reviewerId']; //check for its value

		$qryParam = $this->CollegeReviewLib->encodeReviewFormEditURL($email, $revid);
		$contentArr['edit_review_url'] = $form_url.'/'.$qryParam;
		

		Modules::run('systemMailer/SystemMailer/CollegeReviewReject_Mail',$email, $contentArr, $attachment, $delayedMailTime);

		
	}

    function editReviewByModerator()
    {
		$this->init();
		if($this->userStatus!='false'){
	    	$usergroup = $this->userStatus[0]['usergroup'];
			if($usergroup == "cms" || $usergroup == CR_MODERATOR_USER_GROUP){
		    	$reviewId = isset($_POST['reviewId'])?$this->input->post('reviewId'):'';
		    	$reviewType = isset($_POST['reviewType'])?$this->input->post('reviewType'):'';
		    	$reviewTxt = isset($_POST['reviewDesc'])?$this->input->post('reviewDesc'):'';
		    	$getDetailFromCookie = explode('|',$_COOKIE['user']);
		    	$moderatorEmail = $getDetailFromCookie[0];
		    	$res = $this->reviewenterprisemodel->editReviewByModerator($reviewId,$reviewTxt,$reviewType);
		    	$this->reviewenterprisemodel->storeModerationDetails($reviewId, $moderatorEmail,'Edit '.$reviewType.' description');


		    	// track college review	
				$reviewAction = ($reviewType == "other")? "reviewDescriptionUpdated" : $reviewType."DescriptionUpdated";
				$caUtilityLib = $this->load->library('CAEnterprise/CAUtilityLib');
				$caUtilityLib->trackCollegeReview($reviewId, $reviewAction, $reviewTxt, $this->userStatus[0]['userid']);

				
				//insert into indexing queue
		    	Modules::run('CollegeReviewForm/SolrIndexing/insertReviewForIndex',$reviewId);
				echo $res;
	    	} else {
				echo 0;
	    	}
		} else {
	    	echo 0;
		}
	
    }
    
    function updateReviewTitle(){
    	$this->init();
		if($this->userStatus!='false'){
			$usergroup = $this->userStatus[0]['usergroup'];
			if($usergroup == "cms" || $usergroup == CR_MODERATOR_USER_GROUP){
				$reviewId = isset($_POST['reviewId'])?$this->input->post('reviewId'):'';
				$reviewTitle = isset($_POST['reviewTitle'])?$this->input->post('reviewTitle'):'';
				$getDetailFromCookie = explode('|',$_COOKIE['user']);
		    	$moderatorEmail = $getDetailFromCookie[0];
				$this->reviewenterprisemodel->updateReviewTitle($reviewTitle,$reviewId);
				$this->reviewenterprisemodel->storeModerationDetails($reviewId, $moderatorEmail,'Review Title Updated');

				// track college review	
				$caUtilityLib = $this->load->library('CAEnterprise/CAUtilityLib');
				$caUtilityLib->trackCollegeReview($reviewId, 'reviewTitleUpdated', $reviewTitle, $this->userStatus[0]['userid']);
				
				//insert into indexing queue
				Modules::run('CollegeReviewForm/SolrIndexing/insertReviewForIndex',$reviewId);
			}else{
				echo 0;
			}
		}else{
			echo 0;
		}
    }

    function saveQualityFlag(){
    	$this->init();
    	if($this->userStatus!='false'){
			$usergroup = $this->userStatus[0]['usergroup'];
			if($usergroup == "cms" || $usergroup == CR_MODERATOR_USER_GROUP){
				$reviewId = isset($_POST['reviewId'])?$this->input->post('reviewId'):'';
				$reviewQuality = isset($_POST['reviewQuality'])?$this->input->post('reviewQuality'):'';
				$getDetailFromCookie = explode('|',$_COOKIE['user']);
		    	$moderatorEmail = $getDetailFromCookie[0];
				$this->reviewenterprisemodel->saveQualityFlag($reviewQuality,$reviewId);
				$this->reviewenterprisemodel->storeModerationDetails($reviewId, $moderatorEmail,'Quality Flag Updated');

				// track college review	
				$caUtilityLib = $this->load->library('CAEnterprise/CAUtilityLib');
				$caUtilityLib->trackCollegeReview($reviewId, 'qualityFlagUpdated', $reviewQuality, $this->userStatus[0]['userid']);

				
				//insert into indexing queue
				Modules::run('CollegeReviewForm/SolrIndexing/insertReviewForIndex',$reviewId);
			}else{

				echo 0;
			}
		}else{
			echo 0;
		}
    }
    
    function updatecourseIdForNonMappedColleges(){
	$this->init();
	
	$reviewId = isset($_POST['reviewId'])?$this->input->post('reviewId'):'';
	$courseId = isset($_POST['courseId'])?$this->input->post('courseId'):'';
	$yearOfGraduation = isset($_POST['yearOfGraduation'])?$this->input->post('yearOfGraduation'):'';

	if($courseId == '')	{
            echo "INVALID_COURSE_ID";
            exit;	
	}

	$this->load->builder("nationalCourse/CourseBuilder");
	$courseBuilder = new CourseBuilder();
	$courseRepository = $courseBuilder->getCourseRepository();
	
	$courseObj = $courseRepository->find($courseId,array('basic','location'));
	if((is_object($courseObj) &&  ($courseObj->getId()=='' || $courseObj->getId() ==0)) || !is_object($courseObj)){
	    echo "INVALID_COURSE_ID";
	    exit;
	}
	$instituteId = $courseObj->getInstituteId();
	$locationId = $courseObj->getMainLocation()->getLocationId();
        
	$this->reviewenterprisemodel->updatecourseIdForNonMappedColleges($reviewId,$instituteId,$locationId,$courseId,$yearOfGraduation);
	$this->reviewenterprisemodel->deleteEntryForNonMappedColleges($reviewId);
	$this->reviewenterprisemodel->updateIsShikshaFlag($reviewId);
	$getDetailFromCookie = explode('|',$_COOKIE['user']);
	$moderatorEmail = $getDetailFromCookie[0];
	$this->reviewenterprisemodel->storeModerationDetails($reviewId, $moderatorEmail,'Mapped');
	
	//insert into indexing queue
	Modules::run('CollegeReviewForm/SolrIndexing/insertReviewForIndex',$reviewId);

        // update index on solr
        $this->load->library('indexer/NationalIndexingLibrary');
        $this->lib3 = new NationalIndexingLibrary;
        $listingType = "course";
        $this->lib3->addToNationalIndexQueue($courseId,$listingType,'index',array('courseReviewsSectionData'));
	
    }
    // @ akhter
    // update information of CA institute and course
    function getCampusRepInfo()
    {
	$msg = '';
	$instituteId = isset($_POST['instituteCA']) ? $_POST['instituteCA'] : '';
	$courseId = isset($_POST['courseCA']) ? $_POST['courseCA'] : '';
	$caId = isset($_POST['ca_id']) ? $_POST['ca_id'] : '';
	$this->load->model('CAEnterprise/caenterprisemodel');
	if(!empty($instituteId))
	{ 
	    $result = $this->caenterprisemodel->isCheckInstitute($instituteId);
	    if($result !=''){
		$instituteId = $result;
		$msg = 'success';
	    }else{
		$instituteId = '';
		$msg = 'error';
	    }
	}
    
	if(!empty($courseId) && $instituteId && $msg == 'success')
	{
	    // load the course object from Course Repo
	    $this->load->builder("nationalCourse/CourseBuilder");
		$courseBuilder = new CourseBuilder();
		$courseRepository = $courseBuilder->getCourseRepository();
	    $courseObj = $courseRepository->find($courseId);
	    $course_institute_id  =  $courseObj->getInstituteId();
	    if(isset($course_institute_id) && ($instituteId == $course_institute_id))
	    {
		$courseId = $courseId;
		$msg = 'success';
	    }else{
		$courseId = '';
		$msg = 'error';
	    }
	}
	
	if($instituteId && $courseId && $caId && $msg == 'success')
	{
	    // update institute and course into CA table
	    $return =$this->caenterprisemodel->updateCAInfo($instituteId,$courseId,$caId);
	    if(isset($return) && $return >0)
	    {
		$msg = 'success';
		error_log('==ca== updated ca institute/course return rows = '.$return);
	    }else{
		$msg = 'sorry';
	    }
	}
	return array("institute" => $instituteId,
		     "course" => $courseId,
		     "response"=>$msg);
    }
    
    function validateCAProfilePic()
    {
	// Checking for file Validations
	$editRepo = $this->getCampusRepInfo();
	$data['profileImage'] = '';
	$data['error'] = '';
	if(isset($_FILES) && is_array($_FILES) && !empty($_FILES['caProfilePic']['name']) ){
		$returnData = $this->uploadMedia();
		if(is_array($returnData) && isset($returnData['fileUrl'])){
				$data['profileImage'] = ($returnData['fileUrl']) ? $returnData['fileUrl'] : '';
				$affectRows = $this->caenterprisemodel->updateCAProfilePic($data['profileImage'],$_POST['ca_id']);
				error_log('==ca== updated ca profilePic return rows = '.$affectRows);
		}else if(is_array($returnData) && isset($returnData['error'])){
			if( ($editStatus!= 0 || $editStatus!= 0) && $returnData['error'] == 'Please select a file to upload' ){
				$data['error'] = $returnData['error'];
			}else{
				$data['error'] = $returnData['error'];
			}
		}
	}
	echo json_encode(array('error'=>$data['error'],'success'=>$data['profileImage'],'caInfo'=>$editRepo));return;
    }
    
    
    /**
    * Uploads image after checking validations
    **/	
    function uploadMedia() {
	    $this->init();
	    $appId = 1;
	    //$ListingClientObj = new Listing_client();
	    $displayData = array();
	    $displayData['error'] = 'Please select a file to upload';
	    if(isset($_FILES['caProfilePic']['name']) && $_FILES['caProfilePic']['name']!=''){
		    $type = $_FILES['caProfilePic']['type'];
		    $size = $_FILES['caProfilePic']['size'];
		    if(!($type== "image/gif" || $type== "image/jpeg"|| $type=="image/jpg" || $type== "image/png" || $type== "image/pjpeg" || $type== "image/x-png" || $type== "image/pjpg"))
		    {
			    $displayData['error'] = 'Please upload only jpeg,png,jpg';
		    }
		    else if($size>1048576)
		    {
			    $displayData['error'] = 'Size limit of 1 Mb exceeded';
		    }else{
			    $fileName = explode('.',$_FILES['caProfilePic']['name']);
			    $fileNameToBeAdded = $fileName[0];
			    $fileCaption= $fileNameToBeAdded;
			    $fileExtension = $fileName[count($fileName) - 1];
			    $fileCaption .= $fileExtension == '' ? '' : '.'. $fileExtension;
    
			    $userId = isset($_POST['ca_id']) ? $_POST['ca_id'] : 0;
    
			    $this->load->library('upload_client');
			    $uploadClient = new Upload_client();
    
			    $fileType = explode('/',$_FILES['caProfilePic']['type']);
			    $mediaDataType = ($fileType[0]=='image')?'image':'pdf';
    
			    $FILES = array();
			    $FILES['caProfilePic']['type'][0] = $_FILES['caProfilePic']['type'];
			    $FILES['caProfilePic']['name'][0] = $_FILES['caProfilePic']['name'];
			    $FILES['caProfilePic']['tmp_name'][0] = $_FILES['caProfilePic']['tmp_name'];
			    $FILES['caProfilePic']['error'][0] = $_FILES['caProfilePic']['error'];
			    $FILES['caProfilePic']['size'][0] = $_FILES['caProfilePic']['size'];
    
			    //Before uploading the file, check the Size and type of file. Only if they are valid, will we proceed with the uploading
			    $upload_forms = $uploadClient->uploadFile($appId,$mediaDataType,$FILES,array($fileCaption),$userId, 'user','caProfilePic');
		
			    if(is_array($upload_forms)) {
				    $displayData['fileId'] = $upload_forms[0]['mediaid'];
				    $displayData['fileName'] = $fileCaption;
				    $displayData['mediaType'] = $mediaDataType;
				    $displayData['fileUrl'] = $upload_forms[0]['imageurl'];
			    } else {
				    $displayData['error'] = $upload_forms;
			    }
		    }
	    }
	    return $displayData;
    }
    
    /**
     * this function is used when the CA removed,accepet,delete
     * In case re-index on solr
    **/
    function updateCampusConnectSolrIndex($userId)
    {
	$courseArr = array();
	$this->load->model('CAEnterprise/caenterprisemodel');
	$course = explode(',',$this->caenterprisemodel->getAllCACourseIdFromCR($userId));

	if(count($course)>0)
	{
		foreach($course as $courseId)
		{
			if($courseId>0){
				$this->load->library('indexer/NationalIndexingLibrary');
        		$this->lib = new NationalIndexingLibrary;
        		$listingType = "course";
	         	$this->lib->addToNationalIndexQueue($courseId,$listingType,'index',array('courseCAProfileSection'));
			}
		}
	}	
    }
    
    /**
     *Get all courses has campus rep
     *Create index on solr before go live
     *It's call only one time
     *@ akhter
    **/
    function createCampusConnectIndex()
    {
	    $courseArr = array();
	    $this->load->model('CAEnterprise/caenterprisemodel');
	    $course = $this->caenterprisemodel->getAllCoursesWithCA();
	    if(count($course)>0)
	    {
		    foreach($course as $courseId)
		    {
			    Modules::run ( 'search/Indexer/delete', $courseId, "course",false);
			    Modules::run ( 'search/Indexer/index', $courseId, "course",false);
			    $courseArr[] = $courseId;
		    }
		    echo json_encode($courseArr);
	    }
	    
    }
    
    function getReviewPostfixForLoggedInUser($email){
		$postfix = '';
		
		switch($email){
		    case 'shikshawriter1@gmail.com':
			$postfix = array('0');
			break;
		    case 'Shikshawriter11@gmail.com':
			$postfix = array('1');
			break;
		    case 'shikshawriter3@rediffmail.com':
			$postfix = array('2');
			break;
			case 'shikshawriter4@rediffmail.com':
			$postfix = array('3');
			break;
			case 'xyz4@xyz.com':
			$postfix = array('4');
			break;
			case 'xyz5@xyz.com':
			$postfix = array('5');
			break;
			case 'shikshawriter7@gmail.com':
			$postfix = array('6');
			break;
			case 'shikshawriter8@gmail.com':
			$postfix = array('7');
			break;
			case 'shikshawriter9@gmail.com':
			$postfix = array('8');
			break;
			case 'shikshawriter10@gmail.com':
			$postfix = array('9');
			break;
		}

		return $postfix;
    }
    
    /**
    * Function to mark review later
    */
    function markReviewLater(){
    	$this->init();
    	$data['reviewId'] = $this->input->post('reviewId',true);
    	$data['isShikshaInstitute'] = $this->input->post('isShikshaInstitute',true);
    	$data['courseId'] = $this->input->post('courseId',true);
    	$data['yearOfGraduation'] = $this->input->post('yearOfGraduation',true);
    	$data['isMapFlag'] = $this->input->post('isMapFlag',true);
    	$data['thisBtn'] = $this->input->post('thisBtn',true);
    	$data['reasons'] = $this->reviewenterprisemodel->getCollegeReviewReasons('later');
    	$data['reasonId'] = $this->input->post('reasonId',true);
		echo $this->load->view('CAEnterprise/laterOptions',$data);
    }

     /**
    * Function to reject review
    */
    function rejectReview(){
    	$this->init();
    	$data['reviewId'] = $this->input->post('reviewId',true);
    	$data['isShikshaInstitute'] = $this->input->post('isShikshaInstitute',true);
    	$data['courseId'] = $this->input->post('courseId',true);
    	$data['yearOfGraduation'] = $this->input->post('yearOfGraduation',true);
    	$data['isMapFlag'] = $this->input->post('isMapFlag',true);
    	$data['thisBtn'] = $this->input->post('thisBtn',true);
    	$data['reasons'] = $this->reviewenterprisemodel->getCollegeReviewReasons('rejected');
    	$data['reasonId'] = $this->input->post('reasonId',true);
    	echo $this->load->view('CAEnterprise/rejectOptions',$data);
    }

    /**
	 * Function to implement the Fuzzy college similarity for Unmapped college (For College Reviews Alumni Moderation)
	 *	
	 * @param string $search Name of Unmapped college
     */
    function fuzzyMatch($search = "Birla Institute of Technology"){
    		
    		$search = $this->input->post('name');
    		// Load the library
			$this->load->library('CAUtilityLib');
			$caUtilityLib =  new CAUtilityLib;    	

			// Load the Model
			$this->load->model('CAEnterprise/reviewenterprisemodel');


			$instituteData = $this->reviewenterprisemodel->getListofInstitutes();
			$institute_map_array  = $caUtilityLib->generate_institute_map($instituteData);
			unset($instituteData);

			$ngrams_map = $caUtilityLib->generate_ngrams_map($institute_map_array[0]);	
			$result = $caUtilityLib->matchData($ngrams_map,$search);
			unset($ngrams_map);

			foreach ($result as $key => $value) {
				$result[$key] = $institute_map_array[1][$key];
			}

			echo json_encode($result);
			
    }
    
 /*   function getCrMenteeApplications(){
	$this->init();
	$cmsUserInfo = Modules::run('enterprise/Enterprise/cmsUserValidation');
	$userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $thisUrl = $cmsUserInfo['thisUrl'];
        $validity = $cmsUserInfo['validity'];
        $flagMedia = 1;

	$appId = 12;
	$loggedInUserId = isset($userid)?$userid:0;
	$statusFilter = isset($_POST['statusFilter'])?$this->input->post('statusFilter'):'All';
	$start = isset($_POST['startFrom'])?$this->input->post('startFrom'):0;
	$rows = isset($_POST['countOffset'])?$this->input->post('countOffset'):5;
	$callType = isset($_POST['callType'])?$this->input->post('callType'):'';
	
	
	$userNameFieldDataMentee=isset($_POST['userNameFieldDataMentee'])?$this->input->post('userNameFieldDataMentee'):'';
	$filterTypeFieldDataMentee=isset($_POST['userNameFieldDataMentee'])?$this->input->post('filterTypeFieldDataMentee'):'';
	

        $cmsPageArr = array();
        $cmsPageArr['prodId'] = 784;
	if($userid=='1563'){
	    $cmsPageArr['prodId'] = 800;    
	}
        $cmsPageArr['validateuser'] = $validity;
        $cmsPageArr['headerTabs'] =  $cmsUserInfo['headerTabs'];
        $cmsPageArr['myProducts'] = $cmsUserInfo['myProducts'];
	$cmsPageArr['caPageHeader']['subTabType'] = 7;
	//$parameterObj = array('abuse' => array('offset'=>-1,'totalCount'=>0,'countOffset'=>5));
	//$parameterObj['abuse']['offset'] = 0;
	//$parameterObj['abuse']['totalCount'] = $totalAbuseReport;
	//$cmsPageArr['parameterObj'] = json_encode($parameterObj);
	
	$result = $this->caenterprisemodel->getAllMenteeDetails($start,$rows,$statusFilter,$userNameFieldDataMentee,$filterTypeFieldDataMentee);
	
		
	$menteeExams = array();
	$mentorDetails = array();
	$totalMentee = array();
	foreach($result['menteeData'] as $results){
	    
	    $menteeExams[$results['menteeId']] = $this->caenterprisemodel->getMenteeExamDetails($results['menteeId']);
	    $mentorIdArray[$results['menteeId']] =  $this->caenterprisemodel->getMentorIdAssignedToMentee($results['userId']);
	    
	    if(isset($mentorIdArray[$results['menteeId']]) && $mentorIdArray[$results['menteeId']]!= ''){
		foreach($mentorIdArray[$results['menteeId']] as $mentorIdArrays){
				    $mentorDetails[$results['menteeId']] = $this->caenterprisemodel->findMentorDetailsById($mentorIdArrays['mentorId']);
				    $menteeCount[$mentorIdArrays['mentorId']] = $this->caenterprisemodel->findMenteeCount($mentorIdArrays['mentorId']);
		}
	    }
	    
	}
   
	$cmsPageArr['userNameFieldDataMentee'] = $userNameFieldDataMentee;
	$cmsPageArr['filterTypeFieldDataMentee'] = $filterTypeFieldDataMentee;
	$cmsPageArr['totalMentee'] = $result['totalMentee'];
	$cmsPageArr['menteeData'] = $result['menteeData'];
	$cmsPageArr['menteeExams'] = $menteeExams;
	$cmsPageArr['mentorIdArray'] = $mentorIdArray;
	$cmsPageArr['mentorDetails'] = $mentorDetails;
	$cmsPageArr['menteeCount'] = $menteeCount;
	$cmsPageArr['countOffset'] = $rows;
	$cmsPageArr['startFrom'] = $start;
	$cmsPageArr['filterSel'] = $statusFilter;
	
	if($callType=='Ajax'){
	    echo $cmsPageArr['totalMentee']."::".$this->load->view('CAEnterprise/mentee_application_innerpart',$cmsPageArr);
	}else{
	    $this->load->view('CAEnterprise/ca_homepage',$cmsPageArr);
	}
	
	
    }
    
  */
    function getCrMentorDetails(){
	$this->init();
	$mentorEmail = isset($_POST['mentorEmail'])?$this->input->post('mentorEmail'):'';
	if($mentorEmail != ''){
	    $result = $this->caenterprisemodel->findMentorDetailsByEmail($mentorEmail);
	    $menteeCount = $this->caenterprisemodel->findMenteeCount($result[0]['userid']);
	}
	
	$this->load->model('CA/mentormodel');
	$this->mentormodel = new Mentormodel();
	
	
	$data = array();
	if(isset($result[0]['userid']) && $result[0]['userid'] != ''){
	    $mentorCheck = $this->mentormodel->checkUserIfMentor(array($result[0]['userid']));
	    if(!empty($mentorCheck) && $mentorCheck[$result[0]['userid']] != 'false' ){
		$data['userId'] = $result[0]['userid'];
		$data['firstname'] = $result[0]['firstname'];
		$data['lastname'] = $result[0]['lastname'];
		$data['email'] = $result[0]['email'];
		$data['mobile'] = $result[0]['mobile'];
		$data['menteeCount'] = $menteeCount;
		echo json_encode($data);
	    }else{
		echo '';
	    }
	}else{
	    echo '';
	
	}
	
	
    }
    
    
    function assignMentorToMentee(){
	$this->init();
	
	$mentorId = isset($_POST['mentorId'])?$this->input->post('mentorId'):'';
	$menteeId = isset($_POST['menteeId'])?$this->input->post('menteeId'):'';
	$chatId = isset($_POST['chatId'])?$this->input->post('chatId'):'';
	
	if($chatId != ''){
	    $ChatIdExist = $this->caenterprisemodel->checkIfChatIdAlreadyExists($chatId);
	}
	
	if(empty($ChatIdExist) || $chatId == ''){
	    $this->caenterprisemodel->addMentorMenteeRelation($mentorId,$menteeId,$chatId);
	
	    $menteeInfo= $this->caenterprisemodel->getMenteeInformation($menteeId);
	    $mentorInfo = $this->caenterprisemodel->getMentorInformation($mentorId);
	    
	    $this->load->builder('ListingBuilder','listing');
	    $listingBuilder = new ListingBuilder;
	    $courseRepository = $listingBuilder->getCourseRepository();
	    $result = $courseRepository->find($mentorInfo[0]['courseId']);
	    $contentArr['courseName'] = $result->getName();
	    
	    $instituteRepository = $listingBuilder->getInstituteRepository();
	    $result1 = $instituteRepository->find($mentorInfo[0]['instituteId']);
	    $contentArr['collegeName'] = $result1->getName();
	    
	    $examsTaken = $this->caenterprisemodel->getMenteeExamDetails($menteeInfo[0]['menteeId']);
	    
	    foreach($examsTaken as $examsTakens){
		
		$exams[] = $examsTakens['examName'];
	    }
	    $contentArr['examString'] = implode(',',$exams);
	    
	    $urlOfLandingPage = SHIKSHA_HOME."/CA/CRDashboard/myChatTab";
	    $contentArr['urlOfLandingPage'] = $urlOfLandingPage;
	    
	    $contentArr['menteeInfo'] = $menteeInfo;
	    $contentArr['mentorInfo'] = $mentorInfo;
	    
	    $menteeLandingPage = SHIKSHA_HOME."/user/MyShiksha/index";
	    $contentArr['menteeLandingPage'] = $menteeLandingPage;
	    
	    $userProfilePageUrl = SHIKSHA_HOME.'/getUserProfile/'.$mentorInfo[0]['displayname'];
	    $contentArr['userProfilePageUrl'] = $userProfilePageUrl;
	    
	    Modules::run('systemMailer/SystemMailer/assignMentorToMenteeMailer',$menteeInfo[0]['email'],$contentArr);
	    Modules::run('systemMailer/SystemMailer/assignMenteeToMentorMailer',$mentorInfo[0]['email'],$contentArr);
	
	    echo 'success';
	}
	
    }
    
    function updateMentorMenteeRelation(){
	$this->init();
	$mentorId = isset($_POST['mentorId'])?$this->input->post('mentorId'):'';
	$menteeId = isset($_POST['menteeId'])?$this->input->post('menteeId'):'';
	$this->caenterprisemodel->updateMentorMenteeRelation($mentorId,$menteeId);
	
    }

    function getChatModeration(){
   
        redirect(ENTERPRISE_HOME."/CAEnterprise/CampusAmbassadorEnterprise/getAllCADetails", 'location', 301);exit;            
    	$this->init();
    	$cmsUserInfo = Modules::run('enterprise/Enterprise/cmsUserValidation');
		$userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $thisUrl = $cmsUserInfo['thisUrl'];
        $validity = $cmsUserInfo['validity'];
        $flagMedia = 1;

		$appId = 12;
		$loggedInUserId = isset($userid)?$userid:0;
		$statusFilter = isset($_POST['statusFilter'])?$this->input->post('statusFilter'):'All';
		$start = isset($_POST['startFrom'])?$this->input->post('startFrom'):0;
		$rows = isset($_POST['countOffset'])?$this->input->post('countOffset'):5;
		$callType = isset($_POST['callType'])?$this->input->post('callType'):'';
	
	
		$userNameFieldDataChatModeration=isset($_POST['userNameFieldDataChatModeration'])?$this->input->post('userNameFieldDataChatModeration'):'';
		$filterTypeFieldDataChatModeration=isset($_POST['filterTypeFieldDataChatModeration'])?$this->input->post('filterTypeFieldDataChatModeration'):'';
	

        $cmsPageArr = array();
        $cmsPageArr['prodId'] = 784;
		if($userid=='1563'){
		    $cmsPageArr['prodId'] = 800;    
		}
        $cmsPageArr['validateuser'] = $validity;
        $cmsPageArr['headerTabs'] =  $cmsUserInfo['headerTabs'];
        $cmsPageArr['myProducts'] = $cmsUserInfo['myProducts'];
		$cmsPageArr['caPageHeader']['subTabType'] = 7;

		$this->load->builder('ListingBuilder','listing');
	    $listingBuilder = new ListingBuilder;
	    $instituteRepository = $listingBuilder->getInstituteRepository();
	   // $result = $instituteRepository->find($instId);
	    $moderationData = $this->caenterprisemodel->getSchedule($start,$rows,$statusFilter,$userNameFieldDataChatModeration,$filterTypeFieldDataChatModeration);

		$cmsPageArr['caPageHeader']['subTabType'] = 8;
	    $cmsPageArr['moderationData'] = $moderationData['scheduleData'];
	    $cmsPageArr['instituteRepository'] = $instituteRepository;
	    $cmsPageArr['chatHistoryDetails'] = $chatHistoryDetails;
	    $cmsPageArr['userNameFieldDataChatModeration'] = $userNameFieldDataChatModeration;
		$cmsPageArr['filterTypeFieldDataChatModeration'] = $filterTypeFieldDataChatModeration;
		$cmsPageArr['countOffset'] = $rows;
		$cmsPageArr['startFrom'] = $start;
		$cmsPageArr['filterSel'] = $statusFilter;
		$cmsPageArr['totalData'] = $moderationData['totalData'];

	
	if($callType=='Ajax'){
	    echo $cmsPageArr['totalData']."::".$this->load->view('CAEnterprise/chat_moderation_inner',$cmsPageArr);
	}else{
    	$this->load->view('CAEnterprise/ca_homepage',$cmsPageArr);
	}
    }

    function editMentorChatForModerationByCMS()
	{
			$this->load->model('CA/mentormodel');
			$this->mentormodel = new Mentormodel();
	
			if($this->input->is_ajax_request()){
				$chatTxt  = (isset($_POST['chatTxt']) && $_POST['chatTxt'] != '')?$this->input->post('chatTxt'):'';
				$logId    = (isset($_POST['logId']) && $_POST['logId'] != '')?$this->input->post('logId'):0;
				if($logId > 0)
				{
					$this->mentormodel->updateChatHistoryText(base64_encode($chatTxt), $logId);
					echo 'success';die;
				}
			}
		
		echo 'error';
	}

	function saveReasonForDisapproval(){

		$this->load->model('CA/mentormodel');
		$this->mentormodel = new Mentormodel();
		if($this->input->is_ajax_request()){
				$reason  = (isset($_POST['reason']) && $_POST['reason'] != '')?$this->input->post('reason'):'';
				error_log("++++".print_r($reason,true));
				$logId    = (isset($_POST['logId']) && $_POST['logId'] != '')?$this->input->post('logId'):0;
				if($logId > 0)
				{
					$this->mentormodel->insertReasonForDisapproval($reason, $logId);
					echo 'success';die;
				}
			}
		
	}

	function updateOnApproval(){

		$this->load->model('CA/mentormodel');
		$this->mentormodel = new Mentormodel();
		if($this->input->is_ajax_request()){
				$logId    = (isset($_POST['logId']) && $_POST['logId'] != '')?$this->input->post('logId'):0;
				$mentorId  = (isset($_POST['mentorId']) && $_POST['mentorId'] != '')?$this->input->post('mentorId'):0;

				if($logId > 0 && $mentorId > 0)
				{
					$this->mentormodel->insertReasonForApproval($logId);
					echo 'success';die;
				}
			}
		
	}

	function getReasons(){
		$this->load->model('reviewenterprisemodel');
		$status = isset($_POST['status'])?$this->input->post('status'):'';
		$reasons = $this->reviewenterprisemodel->getCollegeReviewReasons($status);
		echo json_encode($reasons);
	}


	private function _getSourceFilter($sources){
		foreach ($sources as $key => $value) {
	    	if(!empty($value['reviewSource'])){	
	    		list($utm_param_1,$utm_param_2,$utm_param_3) = explode('&', $value['reviewSource']);
	    	}

	    	if(strpos($utm_param_1, 'utm_source') !== false){
	    		$val = explode('=', $utm_param_1);
	    		$source[] = $val[1];
	    	}else if(strpos($utm_param_2, 'utm_source') !== false){
	    		$val = explode('=', $utm_param_2);
	    		$source[] = $val[1];
	    	}else if(strpos($utm_param_3, 'utm_source') !== false){
	    		$val = explode('=', $utm_param_3);
	    		$source[] = $val[1];
	    	}
	    }
	    
	    return array_unique($source);
	}

	function getModerationDetails($reviewDetailsObj){
		$this->load->model('reviewenterprisemodel');

	   		foreach ($reviewDetailsObj as $key => $review) {
	   			$reviewId = $review->reviewId;
	   			$moderationDetail = $this->reviewenterprisemodel->getModerationDetails($reviewId);
	   			$review->moderationDetail = $moderationDetail;
			}
			
			return $reviewDetailsObj;
	}

    // make bulk payment to CR
	// added by akhter
	function crPayAll()
	{
			$this->initForMarketing();
			$cmsUserInfo = Modules::run('enterprise/Enterprise/cmsUserValidation');
			$userid = $cmsUserInfo['userid'];
	        $usergroup = $cmsUserInfo['usergroup'];
	        $thisUrl = $cmsUserInfo['thisUrl'];
	        $validity = $cmsUserInfo['validity'];
	        $flagMedia = 1;
			$appId = 12;
			$cmsPageArr = array();
	        $cmsPageArr['prodId'] = 781;
			if($userid=='1563'){
			    $cmsPageArr['prodId'] = 800;    
			}
	        $cmsPageArr['validateuser'] = $validity;
	        $cmsPageArr['headerTabs'] =  $cmsUserInfo['headerTabs'];
	        $cmsPageArr['myProducts'] = $cmsUserInfo['myProducts'];
			$loggedInUserId = isset($userid)?$userid:0;
			$cmsPageArr['caPageHeader']['subTabType'] = 9;
			$this->load->view('CAEnterprise/ca_homepage',$cmsPageArr);  
	}
	
	// make bulk payment to CR
	// added by akhter
	function makeBulkPayment(){
		if(!empty($_FILES['datafile']) && $_FILES['datafile']['tmp_name'] !=''){
				$this->load->library('CA/Mywallet');
				$res = $this->mywallet->readCommonExcel();
				$getData = $res;

				if(count($res)>0){
					foreach ($res as $paid) {
						if(!array_key_exists('paidAmount', $paid)){
							return '<p style="color:red;font-size:13px;margin-left:235px;">Something went wrong ! Please upload correct file.</p>';exit();
						}
						
						$userid = trim($paid['userId']);
						$paidAmount = trim($paid['paidAmount']);

						if(in_array($amt[$userid],$amt)){
							$amt[$userid][] =  $paidAmount; 
						}else{
							$amt[$userid][] =  $paidAmount; 
						}
					}
				}
				
				if(count($getData)>0){
					foreach ($getData as $value) {

						$userid = trim($value['userId']);
						
						$mainPaidAmount = array_sum($amt[$userid]);
						
						$paidAmount = $value['paidAmount'];
						
						$chequeNumber = trim($value['chequeNumber']);
						
						if(is_numeric($userid) && $userid>0 && $this->_validateInt($userid)){

							$amount = $this->mywallet->getCreditedAmount($userid);

							if(($mainPaidAmount >0 && $this->_validateInt($mainPaidAmount) && $mainPaidAmount <= trim($amount[0]['creaditedAmount'])) && ($paidAmount>0 && $this->_validateInt($paidAmount) && $chequeNumber !='')){
								$data['insertData'][] = array('userId' => $userid, 'paidAmount'=>$paidAmount , 'chequeNumber'=>$value['chequeNumber']);
	 						}else{
								$data['log'][] = array('userId' =>$userid, 'creadited'=>$amount[0]['creaditedAmount'] , 'debiting'=>$paidAmount,'checkNo'=>$value['chequeNumber']); 
							}
						}else{
								$data['log'][] = array('userId' => $userid, 'creadited'=>0, 'debiting'=>$paidAmount,'checkNo'=>$value['chequeNumber']); 
						}
					}
				}

				if(count($data['insertData'])>0 && count($data['log'])<=0){
					// insert into wallet and mail to user
					$this->mywallet->addInWallet($data['insertData']);
					foreach ($data['insertData'] as $v) {
						// send payment mailer to user 
						$this->sendCRPaymentMailer($v['userId'], $v['paidAmount']);
					}
				}
				$html = $this->load->view('CAEnterprise/cr_bulkPayment_innerpage',$data,true);
				return $html; 
		}
	}

	function validateCAPayment(){
			$maxsize = 1048576; // 1 MB
			$mimes = array('application/vnd.ms-excel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			if($_FILES['datafile']["tmp_name"] ==''){
				$error = 'Please upload  payment file.';
			}else if(!in_array($_FILES['datafile']['type'],$mimes) && $_FILES['datafile']["tmp_name"] !=''){
			    $error = 'Invalid file type. Only XLSX, XLS types are accepted.';
			}else if($_FILES['datafile']['size'] > $maxsize && in_array($_FILES['datafile']['type'],$mimes) && $_FILES['datafile']["tmp_name"] !=''){
				$error = 'File too large. File must be less than 1 MB.';
			}else if(in_array($_FILES['datafile']['type'],$mimes) && $_FILES['datafile']["tmp_name"] !=''){
				$html = $this->makeBulkPayment();
			}
			echo json_encode(array('e'=>$error,'htm'=>base64_encode($html)));exit();
	}

	function sendCRPaymentMailer($userId, $paidAmount=0)
	{
		$contentArr = array();
		$attachment = array();
		$this->load->model('CA/camodel');
		$this->CAModel = new CAModel();
		$result = $this->CAModel->getAllCADetails($userId);
		if(is_array($result[0]['ca']) && $paidAmount>0)
		{
			$userEmail = $result[0]['ca']['email'];
			$contentArr['crName'] = ucwords($result[0]['ca']['firstname'].' '.$result[0]['ca']['lastname']);
			$contentArr['paidAmount'] = $paidAmount;
			$this->load->library('mailerClient');
			$MailerClient = new MailerClient();
			Modules::run('systemMailer/SystemMailer/CRPayoutMail',$userEmail, $contentArr, $attachment);
		}
	}

	function _validateInt($value){
		return preg_match('/^([0-9]*)$/', $value);
	}

	function getResetPasswordLink($requested_page, $userEmail){ 
		$this->load->library('Register_client'); 
	    $register_client = new Register_client(); 
	    $requested_page = SHIKSHA_HOME.'/CA/CRDashboard/getCRUnansweredTab'; 
	    $response = $register_client->getUserIdForEmail($appId,$userEmail); 
	    $id = base64_encode($response[0]['id'].'||'.$requested_page.'||'.$userEmail); 
	    $resetlink = SHIKSHA_HOME . '/user/Userregistration/ForgotpasswordNew/'.$id; 
	    return $resetlink; 
	} 

	function saveAnonymousFlag(){
		$this->init();
		$reviewId = $this->input->post('reviewId');
		$anonymousFlag = $this->input->post('anonymousFlag');
		$this->load->model('reviewenterprisemodel');
		$reviewenterprisemodel = new ReviewEnterpriseModel;
		$reviewenterprisemodel->saveAnonymousFlag($reviewId,$anonymousFlag);
		
		// track college review
		$caUtilityLib = $this->load->library('CAEnterprise/CAUtilityLib');
		$caUtilityLib->trackCollegeReview($reviewId, 'anonymousFlagUpdated', $anonymousFlag, $this->userStatus[0]['userid']);

		//insert into indexing queue
		Modules::run('CollegeReviewForm/SolrIndexing/insertReviewForIndex',$reviewId);
	}

	function saveNewCourse(){
		$this->init();
		$reviewId = $this->input->post('reviewId');
		$newCourseId = $this->input->post('newCourseId');
		$oldCourseId = $this->input->post('oldCourseId');

		if(empty($newCourseId) || empty($oldCourseId) || !is_numeric($newCourseId) || !is_numeric($oldCourseId)){
			echo json_encode('error');
			die();
		}

		$this->load->builder("nationalCourse/CourseBuilder");
		$courseBuilder = new CourseBuilder();
		$this->load->model('reviewenterprisemodel');
		$reviewenterprisemodel = new ReviewEnterpriseModel;
		$courseRepository = $courseBuilder->getCourseRepository();
		$this->load->builder("nationalInstitute/InstituteBuilder");
		$instituteBuilder = new InstituteBuilder();
		$instituteRepository = $instituteBuilder->getInstituteRepository();
		$courseObj = $courseRepository->find($newCourseId, array('location'));
		if(!($courseObj->getId() > 0)){
			echo json_encode('error');
			die();
		}else{
			$oldCourseObj = $courseRepository->find($oldCourseId, array('location'));
			
			$reviewenterprisemodel->saveNewCourse($reviewId,array(
												'courseId' => $newCourseId,
												'instituteId' => $courseObj->getInstituteId(),
												'locationId' => $courseObj->getMainLocation()->getLocationId()
												));
			// track college review
			$collegeDetails = array(
							'courseId' => $newCourseId,
							'instituteId' => $courseObj->getInstituteId(),
							'locationId' => $courseObj->getMainLocation()->getLocationId()
							);
			$caUtilityLib = $this->load->library('CAEnterprise/CAUtilityLib');
			$caUtilityLib->trackCollegeReview($reviewId, 'courseDetailsUpdated', $collegeDetails, $this->userStatus[0]['userid']);


			//insert into indexing queue
			Modules::run('CollegeReviewForm/SolrIndexing/insertReviewForIndex',$reviewId);

			$newCourseData['name'] = $courseObj->getName();
			$newCourseData['url'] = $courseObj->getUrl();
			$instituteObj = $instituteRepository->find($courseObj->getInstituteId());
			$newCourseData['instituteName'] = $courseObj->getInstituteName();
			$newCourseData['newInstituteUrl'] = $instituteObj->getURL();

			echo json_encode($newCourseData);
		}
	}


	function generateReviewsModeratorMap($userId){

		if($userId<1){
			return false;
		}

		$this->load->config('CollegeReviewForm/collegeReviewConfig');
		$showAllReviewsUserId = $this->config->item('showAllReviewsUserId');
		if($showAllReviewsUserId[$userId]){
			return '';
		}

		$moderatorMapWithDigit = $this->config->item('moderatorMapWithDigit');
		$mapVal = $moderatorMapWithDigit[$userId];
		unset($moderatorMapWithDigit);

		foreach ($mapVal['tens'] as $tensDigit) {
			foreach ($mapVal['ones'] as $onesDigit) {
				$userReviewMap[] = $tensDigit.$onesDigit;
			}
		}

		if(empty($userReviewMap)|| count($userReviewMap)<1){
			$userReviewMap[] =-1;
		}

		return $userReviewMap;
    }

    /**
     * [getCollegeReviewsFromSolr description]
     * @author Aman Varshney <varshney.aman@gmail.com>
     * @date   2017-07-17
     * @return [type]     [description]
     */
    function getCollegeReviewsFromSolr(){
		$this->isUserGroupCRModerator = true;
		$this->init();
    	$this->load->builder("CAEnterprise/CrModerationBuilder");
        
        //loading builder
		$crModBuilder = new CrModerationBuilder();
		$crModRepo    = $crModBuilder->getCrModerationRepository();  
		
		$cmsUserInfo  = Modules::run('enterprise/Enterprise/cmsUserValidation');
		$loggedInUserId = $cmsUserInfo['userid'];
		//get collegereviewrequestObject
		$requestObj            = $crModBuilder->getRequest();        
		$requestObj->setReviewerInfoData($cmsUserInfo);
		$requestObj->setPostData();
		
		//$review_filter_postfix                    = $requestObj->generateReviewsModeratorMap();
		$cmsPageArr                               = array();
		$cmsPageArr['prodId']                     = 802;
		$cmsPageArr['useremail']                  = $requestObj->getReviewerUserEmail();
		$cmsPageArr['validateuser']               = $cmsUserInfo['validity'];
		$cmsPageArr['headerTabs']                 = $cmsUserInfo['headerTabs'];
		$cmsPageArr['myProducts']                 = $cmsUserInfo['myProducts'];
		$cmsPageArr['caPageHeader']['subTabType'] = 6;
		$cmsPageArr['countOffset']                = $rows;
		$cmsPageArr['startFrom']                  = $requestObj->getStart();

		//populate stream list from domain class
		$streamList  					   = new \registration\libraries\FieldValueSources\Stream;
	    $cmsPageArr['streamList']  		   = $streamList->getValues();		

		$this->load->builder("nationalCourse/CourseBuilder");
		$courseBuilder                     = new CourseBuilder();
		$this->courseRepository            = $courseBuilder->getCourseRepository();
		$cmsPageArr['courseRepository']    = $this->courseRepository;
		
		$this->load->builder("nationalInstitute/InstituteBuilder");
		$instituteBuilder                  = new InstituteBuilder();
		$this->instituteRepository         = $instituteBuilder->getInstituteRepository();
		$cmsPageArr['instituteRepository'] = $this->instituteRepository;
		

		if($requestObj->checkForSearchCall == 'email'){
			$cmsPageArr['email'] 		  = trim($requestObj->getFilterByEmail());
			$cmsPageArr['statusFilter']   = 'Pending'; 
		    $cmsPageArr['instituteName']  = '';
		    $cmsPageArr['typeFilter']     = '';
		    $cmsPageArr['sortReviews']    = $requestObj->getSortCriteria();
		    $cmsPageArr['categoryFilter'] = '';
		}else{
			$cmsPageArr['email'] = '';
			$cmsPageArr['statusFilter'] 	= $requestObj->getStatusFilter();

			if($cmsPageArr['statusFilter'] == 'Later' || $cmsPageArr['statusFilter'] == 'Rejected'){
				$cmsPageArr['reasonFilter'] = $requestObj->getReasonFilterId();
				$cmsPageArr['reasons']      = $this->reviewenterprisemodel->getCollegeReviewReasons($cmsPageArr['statusFilter']);
		    }

			$cmsPageArr['instituteName']               = $requestObj->getInstituteId();
			$cmsPageArr['typeFilter']                  = $requestObj->getTypeFilter();
			$cmsPageArr['sortReviews']                 = $requestObj->getSortCriteria();
			$cmsPageArr['categoryFilter']              = $requestObj->getCategoryFilter();
			$cmsPageArr['sourceFilter']                = $requestObj->getSource();
			
			//new filters added
			$cmsPageArr['moderator_list']              = $requestObj->getModeratorsList();
			$cmsPageArr['phone_search']                = $requestObj->getPhoneNumberFilter();
			$cmsPageArr['posted_timeRange']['From']    = $requestObj->getPostedDateFrom();
			$cmsPageArr['posted_timeRange']['To']      = $requestObj->getPostedDateTo();
			$cmsPageArr['moderated_timeRange']['From'] = $requestObj->getModeratedDateFrom();
			$cmsPageArr['moderated_timeRange']['To']   = $requestObj->getModeratedDateTo();
		}

		if($this->input->is_ajax_request()){
		    $cmsPageArr['start'] 		 = isset($_POST['start'])?$this->input->post('start'):$requestObj->getStart();
		    $requestObj->setStart($cmsPageArr['start']);		    
		    $cmsPageArr['startUnmapped'] = isset($_POST['startUnmapped'])?$this->input->post('startUnmapped'):0;
		}else{
		    $cmsPageArr['start'] 		 = 0;
		    $cmsPageArr['startUnmapped'] = 0;
		}

		$cmsPageArr['count']  = isset($_POST['count'])?$this->input->post('count'):$requestObj->getResultCount();
		$cmsPageArr['result'] = array();


		$cmsPageArr['showAllReviewRejectButton'] = false;
		if($loggedInUserId == 670062 ){
			$cmsPageArr['showAllReviewRejectButton'] = true;	
		}


		$cmsPageArr['showModeratedByFilter'] = $requestObj->isShowModeratedByFilter();
		
		$dataFromSolr                        =  $crModRepo->getCollegeReviewData();

		$all_reject_flag = $this->input->post('rejectRevId');
		if($all_reject_flag == '1'){
			$this->markAllReviewRejected($dataFromSolr);
			$url = SHIKSHA_HOME.'/CAEnterprise/CampusAmbassadorEnterprise/getCollegeReviews';
            header("Location: $url");
			exit();
		}

		$cmsPageArr['result']                = $dataFromSolr['collegeReviewListing'];
		$cmsPageArr['reviewSource']          = $dataFromSolr['collegeReviewFilters']['utmSource'];
		$cmsPageArr['startUnmapped']         = 0;
		if(isset($dataFromSolr['totalDocumentCount'])){
		    $cmsPageArr['num_rows'] = $dataFromSolr['totalDocumentCount'];
		}
		$cmsPageArr['checkForSearchCall'] = $checkForSearchCall;
		$cmsPageArr['result'] 			  = $this->getReviewRatingDetails($cmsPageArr['result']);
		$cmsPageArr['InstituteCounts']    = $dataFromSolr['collegeReviewInstituteWiseStatus'];
		
		$cmsPageArr['showModeratedByFilter'] = $requestObj->isShowModeratedByFilter();
		//load moderators email ids
		$this->load->config('CollegeReviewForm/collegeReviewConfig');
		$cmsPageArr['collegeReviewModerators'] = $this->config->item('collegeReviewModerators');
		if($this->input->is_ajax_request()){	
		    echo $this->load->view('CAEnterprise/college_reviews',$cmsPageArr);
		}else{
			$this->load->view('CAEnterprise/ca_homepage',$cmsPageArr);	
		}
    }

   function prepareViewChangesData(){
   		$reviewId = $this->input->post('reviewId');
   		if(empty($reviewId) || !is_numeric($reviewId)){
   			return;
   		}
   		$this->init();
   		$data = array();
   		$mapping = array(
   						'placementDescription'=>'Placements',
   						'infraDescription'=>'Infrastructure',
   						'facultyDescription'=>'Faculty',
   						'reviewDescription'=>'Other Details',
   						'reviewTitle'=>'Title of Review');
   		$recentData   = $this->reviewenterprisemodel->getRecentReviewData($reviewId);  // manual data
   		$trackingData = $this->reviewenterprisemodel->getOriginalData($reviewId);

   		foreach ($trackingData as $index => $value) {
   			if(in_array($value['action'], array('reviewEdited','reviewAdded'))){
   				$originalData = $value['data'];
   			}else if($value['action'] == 'autoModerated'){
   				$autoModeratedData = $value['data'];
   			}
   		}

   		$originalData      = json_decode($originalData,true);
		$autoModeratedData = json_decode($autoModeratedData,true);

		foreach ($recentData as $columnName => $value) {
			$autoModerate = !empty($autoModeratedData[$columnName]) ? $autoModeratedData[$columnName] : '';
			$oriData = !empty($originalData[$columnName]) ? $originalData[$columnName] : '';
			
			$data[$mapping[$columnName]] = array( 'original'=>$oriData,
												  'autoModerated'=>$autoModerate,
												  'manual'=>$value
												);
			unset($autoModerate,$oriData);
		}
		echo json_encode($data);
   }

   function checkAutoModeration(){
   		$reviewId = $this->input->post('reviewId');
   		if(empty($reviewId) || !is_numeric($reviewId)){
   			return;
   		}
   		$this->init();
   		$data = array();
   		$mapping = array(
   						'placementDescription'=>'Placements',
   						'infraDescription'=>'Infrastructure',
   						'facultyDescription'=>'Faculty',
   						'reviewDescription'=>'Other Details',
   						'reviewTitle'=>'Title of Review');
   		$recentData   = $this->reviewenterprisemodel->getRecentReviewData($reviewId);  // manual data
   		

   		$this->load->library(array('v1/AnACommonLib'));
        $this->anaCommonLib = new AnACommonLib();
    
        $this->anamodel     = $this->load->model('messageBoard/AnAModel');
        $keyWord_Data       = $this->anamodel->getAutoModerationKeywordData();
        
        $this->config->load('messageBoard/SuperlativeConfig');
        $superlativeList    = $this->config->item('superlativeList');

		foreach ($recentData as $columnName => $value) {
			
					$maxLength  = 2500;
					$error = '';
                    if($columnName == 'reviewTitle'){
                        $maxLength  = 100;
                    }
            		$text = $value;
                    if(!empty($text)){

                        //Step 1 - remove impurities or unwanted elements from (a substance) string.
                        $updatedText = $this->anaCommonLib->refineElementFromString($text,true);

                        //Step 2 - check the sms lingo in the text If find then replace with actual keyword.
                        $updatedText = $this->anaCommonLib->autoModerationKeywordReplace($updatedText, $keyWord_Data);

                        //Step 3 - check superlative words from string if found then Add "the" before superlative
                        $updatedText = $this->anaCommonLib->findSuperlative($updatedText, $superlativeList);

			            //Step 4 - Python script to find basecourse, exam and institute (acronyms and synonyms) in sentence(s) and replace it with shiksha's basecourse, exama and institue (acronyms and synonyms)
                        $finalText = $this->anaCommonLib->runCleaningProcess($updatedText);

                        if(empty($finalText)){
                            error_log('==ANA==Crons==reviewAutoModerationCron=='.$updatedText);
                        }
                        $updatedText = empty($finalText) ? $updatedText : $finalText;
                        //Step 5 - spellchecker
                        $updatedText = $this->anaCommonLib->spellCheckString($updatedText);

                        if(!empty($updatedText) && $updatedText != $text && strlen($updatedText)<=$maxLength){
                            $autoModerate = $updatedText;
                        }else{
                        	$autoModerate = $updatedText;
                        	$error = '<span style="color:red;">Auto Moderation will not be applicable due to length ( '.$maxLength.' ) exceeded / no changes</span>';
                        }
                    }

            $autoModerate = !empty($autoModerate) ? $autoModerate : '';
			$data[$mapping[$columnName]] = array( 'original'=>$text,
												  'autoModerated'=>array('text'=>$autoModerate,'error'=>$error)
												);
			unset($autoModerate,$oriData);
		}
		echo json_encode($data);
   }
}
?>
