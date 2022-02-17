<?php 
/**
  Main Controller class for rendering Listing detail page for mobile.
 **/
class Listing_mobile extends ShikshaMobileWebSite_Controller
{
	private $courses;
	function __construct()
	{
		parent::__construct();
		$this->load->library(array('listing_client','alumniSpeakClient'));
		$this->load->builder('ListingBuilder','listing');
		$this->load->helper('mailalert');	
		$this->load->config('mcommon5/mobi_config');	
		$this->load->helper('mcommon5/mobile_html5');
	}
	
	function _init(& $displayData,$typeId,$type = 'institute'){
	//	error_reporting(E_ALL);
		define("PAGETRACK_BEACON_FLAG",false);
		$this->load->builder('CategoryBuilder','categoryList');
		$this->load->builder('LDBCourseBuilder','LDB');
		$this->load->builder('LocationBuilder','location');
		$this->load->builder('ListingBuilder','listing');
		$this->load->library(array('categoryList/categoryPageRequest','listing/listing_client'));
		$this->load->model('ldbmodel');
		$this->load->model('QnAModel');
		$this->load->model('listing/coursemodel');
		$categoryBuilder = new CategoryBuilder;
		$LDBCourseBuilder = new LDBCourseBuilder;
		$locationBuilder = new LocationBuilder;
		$listingBuilder = new ListingBuilder;
		$this->ListingClientObj = new Listing_client();
		$this->instituteRepository = $listingBuilder->getInstituteRepository();
		$this->courseRepository = $listingBuilder->getCourseRepository();
		$this->categoryRepository = $categoryBuilder->getCategoryRepository();
		$this->LDBCourseRepository = $LDBCourseBuilder->getLDBCourseRepository();
		$this->locationRepository = $locationBuilder->getLocationRepository();
		$this->universityRepository = $listingBuilder->getUniversityRepository();
		$this->abroadCourseRepository = $listingBuilder->getAbroadCourseRepository();
		$this->departmentRepository = $listingBuilder->getAbroadInstituteRepository();
		$displayData['instituteRepository'] = $this->instituteRepository;
		$displayData['courseRepository'] = $this->courseRepository;
		$displayData['categoryRepository'] = $this->categoryRepository;
		$displayData['LDBCourseRepository'] = $this->LDBCourseRepository;
		$displayData['locationRepository'] = $this->locationRepository;
		$displayData['validateuser'] = $this->checkUserValidation();
		$displayData['pageType'] = $type;
		$displayData['typeId'] = $typeId;
		$displayData['trackForPages'] = true;
		global $listings_with_localities;
		$displayData['listings_with_localities']= json_encode($listings_with_localities);

                if(isset($_COOKIE['show_recommendation']) && $_COOKIE['show_recommendation']=='yes'){
                        if(isset($_COOKIE['recommendation_course']) && $_COOKIE['recommendation_course']>0){
                            $course = $this->courseRepository->find($_COOKIE['recommendation_course']);
                            $displayData['responseCreatedInstituteId'] = $course->getInstId();
                            $displayData['responseCreatedCourseId'] = $_COOKIE['recommendation_course'];
                        }
                }

	}
	
	function listingDetailWap($listing_id,$type){
		if(($type !='course' && $type != 'institute') || !is_numeric($listing_id) || empty($listing_id))
		{
			show_404();
			exit;
		}
        if($type == 'course'){
			$this->load->builder("nationalCourse/CourseBuilder");
			$this->courseDetailLib = $this->load->library('nationalCourse/CourseDetailLib');
			$courseBuilder = new CourseBuilder();
			$this->courseRepo = $courseBuilder->getCourseRepository();
			$courseObj = $this->courseRepo->find($listing_id);
			$this->courseDetailLib->checkForCommonRedirections($courseObj, $listing_id);
		}
		// load apiconfig(for question/answer viewtracking)
		$this->load->config('apiConfig');

		//Redirect old listing to new listing page
		if(!empty($listing_id) && $type = 'institute')
		{
			$this->load->builder("nationalInstitute/InstituteBuilder");
	        $instituteBuilder = new InstituteBuilder();
	        $instituteRepo = $instituteBuilder->getInstituteRepository();
	        $instituteObj = $instituteRepo->find($listing_id,array('basic'));
	        $seoUrl = $instituteObj->getURL();
	        if(!empty($seoUrl)){
	        	header("Location: $seoUrl",TRUE,301);
                exit;
	        }
	        else{

                         /*If institute id does'nt exist, check whether the status of institute is deleted,
                          if yes then 301 redirect to migrated institute page Or show 404 */
                        $this->institutedetailmodel = $this->load->model('nationalInstitute/institutedetailsmodel');
                        $newUrl = $this->institutedetailmodel->checkForDeletedInstitute($listing_id,$type);
                        if(!empty($newUrl)){
                           header("Location: $newUrl",TRUE,301);
                           exit;
                        }

	        	show_404();
	        }
	        
		}
		$this->national_course_lib 	= $this->load->library('listing/NationalCourseLib');
		$displayData = array();
		$course_id = "";
		if($type == 'institute'){
			$displayData['institute_id'] = $listing_id;

			$this->getBasicInstituteDetails($displayData);
			//added by akhter
			$this->populateAdditionalInfo($displayData,$type);
			$displayData['boomr_pageid'] = "listing_detail_institute";
			
			$currentLocation = $displayData['currentLocation'];
			$courses = $displayData['courses'];
			$institute = $displayData['institute'];
			
			$displayData['scriptData'] = $this->createSeoScriptForInst($institute, $currentLocation);		
			$displayData['IIMColleges']	= array(307,318,20190,333,20188,29623,23700,32728,32736,36076,36085,32740,36080,47712,47709,47711,47703,38238,47745);
			
		
			$call_back_data = $this->callBackOnUserNumber($institute);
			if(count($call_back_data)>0) {
				$displayData['call_back_yes'] = 1;
				$displayData['toNumber'] = $call_back_data['toNumber'];
				$displayData['fromNumber'] = $call_back_data['fromNumber'];
				$displayData['institute_name'] = $call_back_data['institute_name'];
				$displayData['call_back_message'] =  $call_back_data['call_back_message'];
			}
			
			$course=$displayData['course'];
			$displayData['googleRemarketingParams'] = $this->_getGoogleRemarketingParams($institute, $course, $type, $currentLocation, $displayData);
			if(!empty($courses)) {
				$displayData['institute_course_list'] 	= $courses;
			}
			$instituteCourseList 	= $displayData['institute_course_list'];
			
			$dominantSubcatData = $this->national_course_lib->getDominantSubCategoryForCourse($course, $categorylistByCourse[$course->getId()]);
			$displayData['courseReviews'] = $this->national_course_lib->getCourseReviewsData($instituteCourseList);
			$displayData['courseReviews'] = $this->national_course_lib->getCollegeReviewsByCriteria($displayData['courseReviews']);
			
			$dominantSubcatDataCourseWise = array();
			$dominantSubcatDataCourseWise[$course->getId()] = $dominantSubcatData;
			$flagShipCourseWithBasicInfo = $institute->getFlagshipCourse(); // get flagship course with basic info
			$flagshipCourseId            = $flagShipCourseWithBasicInfo->getId();

			if(count($displayData['courseReviews']) > 0) {
				foreach($displayData['courseReviews'] as $courseId=>$reviewData) {
				
				$dominantSubcatData = $this->national_course_lib->getDominantSubCategoryForCourse($institute->getCourse($courseId), $categorylistByCourse[$courseId]);
				$dominantSubcatDataCourseWise[$courseId] = $dominantSubcatData;
				if($this->_checkMBATemplateEligibility($dominantSubcatData['subcategory_ids'], $institute->getCourse($courseId)) || $this->_checkEngTemplateEligibility($dominantSubcatData['subcategory_ids']) || $this->_checkForCollegeReviewTemplateEligibility($dominantSubcatData['subcategory_ids'])) {
					if($reviewData['overallRating'] > 0) {

						$displayData['showCourseReviews'][$courseId] = $reviewData['overallRating'] ;
						$displayData['ratingCount'][$courseId] = $reviewData['ratingCount'] ;
					 }
				 }
			 }
        	}
			


			//change for institute to college story
			$displayData['collegeOrInstituteRNR'] = 'institute';
			$national_course_lib = $this->load->library('listing/NationalCourseLib');
			$categoryIds = $national_course_lib->getCourseInstituteCategoryId($listing_id,$type);
			if(count(array_intersect($categoryIds, array("2", "3"))) != 0) { 
				$displayData['collegeOrInstituteRNR'] = 'college';
			}
			//institute to college story ends here
		
			//$displayData['collegeReviewWidget'] = Modules::run('common/CommonReviewWidget/homePageWidget','mobile','INSTITUTE_LISTING_MOBILE');
			$displayData['collegeReviewWidget'] = "";
            //$displayData['dominantSubCatData'] = $dominantSubcatData['dominant'];
            $displayData['dominantSubCatData'] = $dominantSubcatDataCourseWise[$flagshipCourseId]['dominant'];

            /* Course Offered Section STARTS */
			$city_param = $this->input->get('city', TRUE);
			$locality_param = $this->input->get('locality', TRUE);
			$displayData['course_browse_section_data'] = $this->national_course_lib->getCoursesForInstituteBrowseSection($listing_id, $displayData['categorylistByCourse'], $city_param, $locality_param, $institute);
            $displayData['abTestVersion'] = $this->input->get('version', true);
            $displayData['isAbTestPage'] = 'yes';
            $requestUrl = $this->input->server('REQUEST_URI', true);
			$validAbTestUrl = strpos($requestUrl, 'getListingDetail');
			if($validAbTestUrl === false || 1) { //removing ab testing
				$displayData['abTestVersion'] = '';
				$displayData['isAbTestPage'] = 'no';
			}
			//below lines is used for beacon tracking purpose
			$displayData['trackingpageIdentifier'] = 'instituteListingPage';
			$displayData['trackingpageNo'] = $displayData['institute_id'];
			$displayData['trackingcityID'] = $displayData['googleRemarketingParams']['cityId'];
			$displayData['trackingstateID'] = $currentLocation->getState()->getId();
			$displayData['trackingcountryId'] = $displayData['googleRemarketingParams']['countryId'];
			//$displayData['trackingcatID'] = $displayData['googleRemarketingParams']['categoryId'];
			//$displayData['trackingsubCatID'] = $displayData['googleRemarketingParams']['subcategoryId'];
			$this->tracking=$this->load->library('common/trackingpages');
			$this->tracking->_pagetracking($displayData);
			
			//below line is used for conversion tracking purpose
			$displayData['contactdTrackingPageKeyId']=316;
			$displayData['bottomdTrackingPageKeyId']=317;
			$displayData['qtrackingPageKeyId']=318;
			$displayData['recommendationTrackingPageKeyId']=320;
                        $url = $displayData['instituteComplete']->getURL();
                        $currentURL =  explode("?", $url);
                        $canonicalurl = $currentURL[0];
                        $displayData['canonicalURL'] = $canonicalurl;
                        $displayData['trackForPages'] = true;
			$this->load->view('mobileListingInstitute',$displayData);
		}
		else{
	                //Load exampage library for exam url. Added by Virender Singh for UGC-3174.
			$this->load->library('examPages/ExamPageRequest');
			$ExamPageLib = $this->load->library('examPages/ExamPageLib');
			// get all live exam pages
			$liveExamPages = array();
			foreach($ExamPageLib->getCategoriesWithExamNames() as $examCategory){
				foreach($examCategory as $key=>$examData){
					$liveExamPages[] = $key;
				}
			}
			unset($ExamPageLib);
			$displayData['liveExamPages'] = $liveExamPages;
			//get Shortlisted course from Db/cookie
        	        $getMShortlistedCourse = Modules::run('mobile_category5/CategoryMobile/getMShortlistedCourse');
                	$courseShortArray = $getMShortlistedCourse;
	                //get brochureUrl from courseId
        	        $this->national_course_lib = $this->load->library('listing/NationalCourseLib');
                	$brochureURL = $this->national_course_lib;
			
			$displayData['courseShortArray'] = $courseShortArray; //mylist
			$displayData['brochureURL'] = $brochureURL; // check brochure
			
			
			$displayData['course_id'] = $listing_id;
			$this->getBasicCourseDetails($displayData);
			$this->populateAdditionalInfo($displayData);
			
			$validateuser=$displayData['validateuser'];
			if($validateuser!='false'){
				$firstName=$validateuser[0]['firstname'];
				$lastName=$validateuser[0]['lastname'];
				$mobile=$validateuser[0]['mobile'];
			}
			
			if(is_array($validateuser[0]) && isset($validateuser[0]['userid'])){
				
				$formData_mobile = false;
				$formData_mobile = Modules::run('registration/Forms/isValidResponseUser', $listing_id, $validateuser[0]['userid']);
				$isFullRegisteredUser = ($formData_mobile===true || $formData_mobile==1)?1:0;
			}
			else{
				$isFullRegisteredUser = 0;
			}
			if(!empty($validateuser[0]['cookiestr'])) { $a = $validateuser[0]['cookiestr']; $b = explode('|',$a); $email= $b[0]; }
			if($firstName!='' && $lastName!='' && $mobile!='' && $email!=''){
				if(($validateuser != "false")  && !(in_array($validateuser[0]['usergroup'],array("enterprise","cms","experts","sums"))) && (checkEBrochureFunctionality($displayData['course'])) && $isFullRegisteredUser==1){
					$displayData['makeAutoResponse'] = true;
				}
			}
			$displayData['boomr_pageid'] = "listing_detail_course";
			$displayData['tab'] = 'courses';
			
			$currentLocation = $displayData['currentLocation'];
			$course = $displayData['course'];//	$this->courseRepository->find($listing_id); // memory optimization changes
			$institute_id = $course->getInstId();

			// 301 redirection in case of tampered URL
			if($course->getInstId() !='' && $course->getName() !=''){
				$this->_validateListingsURL($course);
			}
			
			$institute = $displayData['institute'];
			$call_back_data = $this->callBackOnUserNumber($institute);
			$categorylistByCourse	= $displayData['categorylistByCourse'];			
			$courseCategory = $categorylistByCourse[$displayData['course_id']];
			
			
			$displayData['googleRemarketingParams'] = $this->_getGoogleRemarketingParams($institute, $course, $type, $currentLocation, $displayData);
			if(count($call_back_data)>0) {
				$displayData['call_back_yes'] = 1;
				$displayData['toNumber'] = $call_back_data['toNumber'];
				$displayData['fromNumber'] = $call_back_data['fromNumber'];
				$displayData['institute_name'] = $call_back_data['institute_name'];
				$displayData['call_back_message'] =  $call_back_data['call_back_message'];
			}
			
			if($this->_checkMBATemplateEligibility($courseCategory, $course) || $this->_checkEngTemplateEligibility($courseCategory) )
			{
				$displayData['courseShortlistedStatus'] = 0;
				$displayData['isMBATemplate'] = true;
				$displayData['isMBATemplateByCourse'][$course->getId()] = true;
				if(isset($displayData['validateuser'][0]['userid']))
				{
						$displayData['courseShortlistedStatus'] =  Modules::run('myShortlist/MyShortlist/checkIfCourseIsShortlistedForAUser',$displayData['validateuser'][0]['userid'],$displayData['typeId']);
				}
			}

			if($this->_checkMBATemplateEligibility($courseCategory, $course) || $this->_checkEngTemplateEligibility($courseCategory) || $this->_checkForCollegeReviewTemplateEligibility($courseCategory)){
				$this->load->helper('listing/listing');
				$displayData['courseReviews'] = $this->national_course_lib->getCourseReviewsData(array($displayData['course']->getId()));
				
				$displayData['courseReviews'] = $this->national_course_lib->getCollegeReviewsByCriteria($displayData['courseReviews']);
				$displayData['courseReviewDefaultSort'] = "graduationYear";
				$displayData['courseReviews'][$displayData['course']->getId()]['reviews'] = $this->national_course_lib->sortCollegeReviews($displayData['courseReviews'][$displayData['course']->getId()]['reviews'],$displayData['courseReviewDefaultSort']);
				
				$displayData['userData']['sessionId'] = sessionId();
				
				if(isset($displayData['validateuser'][0]['userid'])) {
				    $displayData['userData']['userId'] = $displayData['validateuser'][0]['userid'];
				} else {
				    $displayData['userData']['userId'] = 0;
				}
				
				//Get User Session Data
				$userSessionData = array();
				$userSessionData = Modules::run("CollegeReviewForm/CollegeReviewController/getUserSessionData",$displayData['userData']['userId'], $displayData['userData']['sessionId']);
				
				if(is_array($userSessionData)){
					$displayData['userData']['userSessionData'] = $userSessionData;
				}
			}

			if(in_array(23,$courseCategory)) {
				$displayData['isMBAPage'] = true;
				// to get location for find college by exam widget
				$registrationForm      = \registration\builders\RegistrationBuilder::getRegistrationForm('LDB'); 
				$fields                = $registrationForm->getFields();
				$displayData['fields'] = $fields;

				$displayData['searchExamFormName'] = "searchByExam";
			}
			
			$displayData['share'] = array('facebook','twitter','linkedin','google','whatsapp'); 
			$displayData['subTitle'] = 'Check out this college review. This might be helpful for you.';

			//change for institute to college story
			$displayData['collegeOrInstituteRNR'] = 'institute';
			$national_course_lib = $this->load->library('listing/NationalCourseLib');
			$categoryIds = $national_course_lib->getCourseInstituteCategoryId($listing_id,$type);
			if(count(array_intersect($categoryIds, array("2", "3"))) != 0) { 
				$displayData['collegeOrInstituteRNR'] = 'college';
			}
			//institute to college story ends here
			
			//$displayData['collegeReviewWidget'] = Modules::run('common/CommonReviewWidget/homePageWidget','mobile','COURSE_LISTING_MOBILE');
			$displayData['collegeReviewWidget'] = "";
			
			//below lines is used for beacon tracking purpose
			$displayData['trackingpageIdentifier'] = 'courseDetailsPage';
			$displayData['trackingpageNo'] = $displayData['course_id'];
			$displayData['trackingcityID'] = $displayData['googleRemarketingParams']['cityId'];
			$displayData['trackingstateID'] = $currentLocation->getState()->getId();
			$displayData['trackingcountryId'] = $displayData['googleRemarketingParams']['countryId'];
			$displayData['trackingcatID'] = $course->getDominantSubcategory()->getParentId();
			$displayData['trackingsubCatID'] = $course->getDominantSubcategory()->getId();
			$this->tracking=$this->load->library('common/trackingpages');
			$this->tracking->_pagetracking($displayData);

			//below line is used for conversion tracking purpose
			$displayData['contactdTrackingPageKeyId']=326;
			$displayData['similardTrackingPageKeyId']=329;
			$displayData['topdTrackingPageKeyId']=325;
			$displayData['IIMCourses']	= array(1653,1645,1688,22931,241736,111876,111875,22929,119554,27159,164791,130026,130055,164564,164788,130065,164638,241826,241811,241824,241783,193061,242136);
			$displayData['ctrackingPageKeyId']=333;
			$displayData['rtrackingPageKeyId']=334;
			$displayData['qtrackingPageKeyId']=330;
			$displayData['atrackingPageKeyId'] = 779;
			$displayData['tupaTrackingPageKeyId'] = 780;
			$displayData['tdownTrackingPageKeyId'] = 781;
			$displayData['fqTrackingPageKeyId'] = 782;
			$displayData['shortlistTrackingPageKeyId']=323;
			$displayData['similarShortlistTrackingPageKeyId']=327;
			$displayData['recommendationTrackingPageKeyId']=335;
			$displayData['applicationTrackingPageKeyId']=504;
			$displayData['searchTrackingPageKeyId'] =332;
			$displayData['comparetrackingPageKeyId'] = 324;
			$displayData['similarcomparetrackingPageKeyId'] =328;

			//below line is used for GA tracking Purpose where user is logged in/Not-logged In
			$displayData['GA_userLevel'] = $displayData['userData']['userId'] > 0 ? 'Logged In':'Non-Logged In';
                        $url = $displayData['courseComplete']->getURL();
                        $currentURL =  explode("?", $url);
                        $canonicalurl = $currentURL[0];
                        $displayData['canonicalURL'] = $canonicalurl; 	
                        $displayData['trackForPages'] = true;
            $displayData['abroadExamData'] = Modules::run("listing/ListingPage/filterAbroadExamData", $course->getEligibilityExams(), $course->getId());
            $displayData['viewTrackParams'] = array("question" => QUESTION_VIEW_DURATION, "answer" => ANSWER_VIEW_DURATION, "trackDuration" => VIEWCOUNT_TRACKING_INTERVAL, "trackingPageType" => "mobileCourseDetailPage");
			$this->load->view('mobileListingCourse',$displayData);
		}
	}
	
	
	private function createSeoScriptForInst($institute, $currentLocation){
		
		$instName = $institute->getName();
		$locations = $institute->getLocations();
		$location = $locations[$currentLocation->getLocationId()];

		if($location){
			$contactDetail = $location->getContactDetail();
		}
		if(!$contactDetail){
		    $locations = $institute->getLocations();
	    	$location = $locations[$currentLocation->getLocationId()];
		if(is_object($location)){
		    $contactDetail = $location->getContactDetail();
		}
	    }
	     $scriptData = array();
                if(empty($contactDetail)){
                        return $scriptData;
                }

	    if($contactDetail->getContactNumbers()){ 
			 $contactNumbers = $contactDetail->getContactNumbers();
	  }  

	  	$email = $contactDetail->getContactEmail();
	  	$website = $contactDetail->getContactWebsite();
		$scriptData['emailForHeader'] = $email;
 		$scriptData['websiteForHeader'] = $website;
		$scriptData['nameForHeader'] = $instName;
		$scriptData['phoneForHeader'] = $contactNumbers;
		return $scriptData;

	}

	/*
	 *  load course objects with basic info and  associate them with institute object
	*
	*/
	
	private function _getInstituteWithBasicCourseInfoObjs($institute_id,$courses,$course_Id = NULL) {
	
		$this->load->builder('ListingBuilder','listing');
		$listingbuilder = new ListingBuilder();
		$courseRepo = $listingbuilder->getCourseRepository();
		$instituteRepo = $listingbuilder->getInstituteRepository();
		$courses = $courseRepo->getDataForMultipleCourses($courses,'basic_info|head_location');
	   
		$institute = $instituteRepo->find($institute_id);
		$institute->setCourses($courses);    //Associated courses (with basic info) with institute object 
		//identify flagship course id	
		if(!isset($course_Id)) {
		$flagshipCourse = $institute->getFlagshipCourse(); 
		$flagshipCourseId = $flagshipCourse->getId();
		} else {
			$flagshipCourseId = $course_Id;
		}

		//load and set flagship course with other course object in institute object
		$flagshipCourseFullObject = $courseRepo->find($flagshipCourseId);
		$courses[$flagshipCourseId] = $flagshipCourseFullObject;
	    $institute->setCourses($courses);
		
		return $institute;
	
	}
	
	private function _checkMBATemplateEligibility($courseCategory, $course) {
		$flag = $this->national_course_lib->checkForMBATemplateEligibility($courseCategory, $course);
		return $flag;
	}
	private function _checkEngTemplateEligibility($courseCategory) {
		$flag = $this->national_course_lib->checkForEngTemplateEligibility($courseCategory);
		return $flag;
	}
	private function _checkForCollegeReviewTemplateEligibility($courseCategory){
		$flag = $this->national_course_lib->checkForCollegeReviewTemplateEligibility($courseCategory);
		return $flag;
	}
	
	// Function to call user on given number
	function callBackOnUserNumber($institute) {
	    $data = array();
	    global $institutePhoneNumber;	//defined in shikshaConstants.php
	    $callbackVar = $this->input->get("callback");	//Variable derived from URL for callback value
    		    
	    $validateuser = $this->checkUserValidation();
	    
	    if($validateuser != "false"){
		    
	        $userid = $validateuser[0]['userid'];
		$institute_id = $institute->getId();
		$institute_name = $institute->getName();
		    
		    if($callbackVar == 1){			
			if(empty($institutePhoneNumber[$institute_id])){			    
			    return;
			}else{
			    $from_number  = implode(',',$institutePhoneNumber[$institute_id]);
			    $mobile = $validateuser[0]['mobile'];
			    $phoneCallbackData = modules::run('UserFlowRedirection/callUserPhone', $mobile,$from_number);
			    
			    $data['toNumber'] = $validateuser[0]['mobile'];
			    $data['fromNumber'] = $from_number;
			    $data['institute_name'] = $institute_name;
			    $data['call_back_message'] =  $phoneCallbackData;
			    return $data;				
			    }
			    
			}
		    }
		return $data;
	}
	
	private function _getGoogleRemarketingParams($institute, $course, $pageType, $currentLocation, & $displayData) {
		$categorylistByCourse = $displayData['categorylistByCourse'];
		$googleRemarketingParams = array();
		$googleRemarketingParams['categoryId'] = array();
		$googleRemarketingParams['subcategoryId'] = array();
		$googleRemarketingParams['countryId'] = $currentLocation->getCountry()->getId();
		$googleRemarketingParams['cityId'] = $currentLocation->getCity()->getId();
		$googleRemarketingParams['instituteId'] = $institute->getId();
    
		if($pageType == "course") {
		    $type_id = $course->getId();
		    // $categories = $this->instituteRepository->getCategoryIdsOfListing($type_id,$pageType);
		    $categories = $categorylistByCourse[$type_id];
		} else {
		    $type_id = $institute->getId();
		    $localityId = $currentLocation->getLocality()?$currentLocation->getLocality()->getId():0;		
		    $instCourses = array();
		    foreach($this->courses as $arr){
			if($arr["city_id"] == $currentLocation->getCity()->getId()){
			    if($localityId == 0 || ($localityId != 0 && $arr["locality_id"] == $localityId)){
				$instCourses = array_unique(array_merge($instCourses,$arr["courselist"]));
			    }
			}
		    }
		    
		    // $categories = $this->instituteRepository->getCategoryIdsOfListing($instCourses,'course');
		    foreach($categorylistByCourse as $cid => $catIdsArray) {
			foreach($catIdsArray as $key => $catId) {
			    $categories[] = $catId;
			}
		    }		    
		}
    
		$finalCategories = array();
		$finalSubcategories = array();
		$categoryCount = count($categories);
		for($i = 0; $i < $categoryCount; $i++){
			$subCategory = $this->categoryRepository->find($categories[$i]);
			$finalCategories[] = $subCategory->getParentId();
			$finalSubcategories[] = $categories[$i];
		}
		
		$googleRemarketingParams['categoryId'] = array_unique($finalCategories);
		$googleRemarketingParams['subcategoryId'] = array_unique($finalSubcategories);
		$specialization = $course->getLdbCourses();
		if(!empty($specialization))
		{
			$googleRemarketingParams['SpecializationID'] = implode(',', $specialization);
		}
		return $googleRemarketingParams;
	}
	
	private function _getCountryOfDeletedInstitute($institute_id){
	    $institutemodel = $this->load->model('listing/institutemodel');
	    $countryId=$institutemodel->getCountryForDeletedInstitute($institute_id);
	    return $countryId;
	}

	private function redirectAbroadCourse($institute_id,$courseId){
	    $this->load->model('listing/coursemodel');
	    $coursemodel = new CourseModel();
	    $courseSubcategoryOld = $coursemodel->getDeletedCourseCategory($courseId);
	    $DeletedInstituteId = $coursemodel->getDeletedCourseInstituteById($courseId);
	    if($DeletedInstituteId == '')
		return;
	    $courseCountryId = $this->_getCountryOfDeletedInstitute($DeletedInstituteId);
	    if($courseCountryId > 2){    //if(course is studyabroad)
		$this->load->config('studyAbroadRedirectionConfig');
		$courseCourse = $this->config->item('oldToNewCourseIDMappings');
		$courseDepartment = $this->config->item('oldCourseToNewDepartmentIDMappings');
		$courseRedirectionId = $courseCourse[$courseId];
		$departmentRedirectionId =$courseDepartment[$courseId];
		if($courseRedirectionId){
		    $abroadCourseItem = $this->abroadCourseRepository->find($courseRedirectionId);
		    $url = $abroadCourseItem->getURL();
		    redirect($url,'location',301);
		    exit();
		}
		if($departmentRedirectionId){
		   $departmentItem = $this->departmentRepository->find($departmentRedirectionId);
		   $url = $departmentItem->getURL();
		   redirect($url,'location',301);
		   exit();
		}
		$countryDataForEmptyCheck = $this->locationRepository->getAbroadCountryByIds(array($courseCountryId));
		if(empty($countryDataForEmptyCheck))
		    $courseCountryId = 1; 
		$abroadCategoryPageRequest = $this->load->library("categoryList/AbroadCategoryPageRequest");
		//If there is no $courseSubcategoryOld then we need to send to country page
		if($courseSubcategoryOld == ''){
		    redirect($abroadCategoryPageRequest->getURLForCountryPage($courseCountryId),'location',301);
		}
		$subcategoryMappings = $this->config->item('studyAbroadSubcategoryIdMappings');
		$courseSubcategoryMap = $subcategoryMappings[$courseSubcategoryOld];
		
		$courseSubCategoryNew = $courseSubcategoryMap['id']; //Now we have new subcategory and category
		$courseParentCategoryNew = $courseSubcategoryMap['parentId'];
		$courseLevel = $coursemodel->getDeletedCourseLevelById($courseId);
		if(!(trim($courseLevel)) || $courseLevel=='0'){			
		    $courseLevel = $courseSubcategoryMap['defaultLevel'];
		}
		else{
		    $courseLevelMappings = $this->config->item('studyAbroadLevelMappings');
		    $courseLevel = $courseLevelMappings[str_replace(' ','_',strtolower($courseLevel))];
		}
		
		
		if($courseSubCategoryNew != ''){
		    $data=array('countryId'=>array($courseCountryId),'subCategoryId'=>$courseSubCategoryNew,'courseLevel'=>$courseLevel);
		    $abroadCategoryPageRequest->setData($data);
		    $url = $abroadCategoryPageRequest->getURL();
		    redirect($url,'location',301);
		    exit();
		}
		else{
		    $data=array('countryId'=>array($courseCountryId),'categoryId'=>$courseParentCategoryNew,'courseLevel'=>$courseLevel);
		    $abroadCategoryPageRequest->setData($data);
		    $url = $abroadCategoryPageRequest->getURL();
		    redirect($url,'location',301);
		    exit();
		}
	    }
	}
	
	private function redirectAbroadInstitute($institute_id,$courseId){
	    $instituteCountryId = $this->_getCountryOfDeletedInstitute($institute_id);
	    if($instituteCountryId > 2){
		$this->load->config('studyAbroadRedirectionConfig');
		$instituteRedirectionArray = $this->config->item('oldToNewInstituteUniversityMappings');
		$redirectionUniversity = $instituteRedirectionArray[$institute_id];
		if($redirectionUniversity!=''){ //Institute is mapped to a university ; send us there!
		    $university = $this->universityRepository->find($redirectionUniversity);
		    $url = $university->getURL();
		    redirect($url,'location',301);
		    exit();
		}
		else{ //Institute is not mapped to university, send us to the country page
		    $abroadCategoryPageRequest = $this->load->library('categoryList/AbroadCategoryPageRequest');
		    $countryDataForEmptyCheck = $this->locationRepository->getAbroadCountryByIds(array($instituteCountryId));
		    if(empty($countryDataForEmptyCheck))
			$instituteCountryId = 1;
		    $url = $abroadCategoryPageRequest->getURLForCountryPage($instituteCountryId);
		    redirect($url,'location',301);
		    exit();
		}
	    }
	}

	private function _getCourses($institute_id, $courseId = ""){
		if($institute_id){
			$this->courses = $this->instituteRepository->getLocationwiseCourseListForInstitute($institute_id);
		}

	        if($courseId != ''){
			$this->redirectAbroadCourse($institute_id, $courseId);   
	        }
	        else{
			$this->redirectAbroadInstitute($institute_id, $courseId);
	        }
		
		/*
		 * 	Firstly check if the Listing is still live OR not?
		 */		
		if(!(isset($this->courses->ERROR_MESSAGE) && $this->courses->ERROR_MESSAGE == "NO_DATA_FOUND")) {
		    $courseList = array();
		    foreach($this->courses as $course){		    
			    if((($_REQUEST['city'] == $course['city_id']) || !($_REQUEST['city']))
			       && (($_REQUEST['locality'] == $course['locality_id']) || !($_REQUEST['locality']) || $_REQUEST['locality'] == 'All')){
				    $courseList = array_merge($courseList,$course['courselist']);
			    }
		    }

		    /*
		     *	If course list is not formed for the requested listing location then redirect to the listing's default URL (Head Ofc)..
		     */
		    if(count($courseList) == 0){
			if($courseId != "") {
			    $course = $this->courseRepository->find($courseId);
			    $course->getId() == "" ? show_404() : redirect($course->getUrl(), 'location', 301);
			} elseif($institute_id != "") {			    
			    $institute = $this->instituteRepository->find($institute_id);
			    $institute->getId() == "" ? show_404() : redirect($institute->getUrl(), 'location', 301);
			} else {
			    show_404();
			}
			exit();
		    } else {
			return array_unique($courseList);
		    }
		    
		} else {
		    /*
		     *	Listing not live now, so redirect it to the mapped listing URL (if set) else throw 404 error page..
		     */
		    $newInstituteId  = $this->instituteRepository->getRedirectionIdForDeletedInstitute($institute_id,"institute");
		    if(!$newInstituteId){
			    show_404();
		    }else{			
			    $institute = $this->instituteRepository->find($newInstituteId);
			    $institute->getId() == "" ? show_404() : redirect($institute->getUrl(), 'location', 301);
			    exit();
		    }
		}			    
	}
	
		function getBasicInstituteDetails(&$displayData){
			ini_set('memory_limit','500M');
			$type = 'institute';
			$appId=1;
			$institute_id = $displayData['institute_id'];
			$this->_init($displayData,$institute_id,'institute');
			
			$courseList = $this->_getCourses($institute_id);
			if(empty($courseList))
			{		
				$error = '$ListingClientObj->getCourseList($appId,$institute_id,$status="live",$userId=\'\')';
				$function = "getBasicInstituteDetails function in Listing_mobile Controller";
				show_404();
				sendMailAlert("data not coming from backend issue in".$error."in".$function,"Listing mobile controller Issue","vikas.k@shiksha.com");
			}			
		
			if($courseList){
				//$institute = reset($this->instituteRepository->findWithCourses(array($institute_id => $courseList)));
				$institute = $this->_getInstituteWithBasicCourseInfoObjs($institute_id,$courseList);
			}else{
				$institute = $this->instituteRepository->find($institute_id);
			} 
			// 301 redirection in case of tampered URL
                        if($institute->getId() !='' && $institute->getName() !=''){
                                $this->_validateListingsURL($institute);
                        }
			$course=$institute->getFlagshipCourse();

			//Code added by Ankur for GA Custom variable tracking
	                $categoryListByCourse   = array();
	                if(!empty($courseList)) {
	                        $categoryListByCourse   = $this->instituteRepository->getCategoryIdsOfListing($courseList, 'course', 'true', TRUE);
	                }
	                $national_course_lib      = $this->load->library('listing/NationalCourseLib');
	                $dominantSubcatData = $national_course_lib->getDominantSubCategoryForCourse($course, $categoryListByCourse[$course->getId()]);
	                $displayData['subcatNameForGATracking'] = $this->categoryRepository->find($dominantSubcatData['dominant'])->getName();
	                $displayData['pageTypeForGATracking'] = 'INSTITUTE_MOBILE';
			//End code for GA Custom tracking
			
			$displayData['categorylistByCourse'] = $categoryListByCourse;
			$displayData['courses'] = $courseList;
			$displayData['pageType'] = 'institute';
			$displayData['tab'] = 'overview';
			$displayData['institute'] = $institute;
			$displayData['course'] = $course;
			$displayData['type'] = $type;
			$displayData['course_id'] =  $course->getId();
			$displayData['details'] = $this->getContactInfo($appId,$institute_id,$type);
			$displayData['alumniFeedbackRatingCount'] = $this->instituteRepository->getAlumniFeedbackRatingCount($institute_id);
			$displayData['instituteComplete'] = $this->instituteRepository->findInstituteWithValueObjects($institute_id,array('description','joinreason'),$institute);


			$this->getSeoTagsForListing($displayData);
			
		}


		function getBasicCourseDetails(&$displayData){
		$course_id                              = $displayData['course_id'];
		$this->_init($displayData,$course_id,'course');

		$appId                                  =1;
		$type                                   = 'course';
		
		$institute_id                           = $this->ListingClientObj->getInstituteIdForCourseId($appId,$course_id);
		$this->redirectAbroadCourse($institute_id, $course_id);
		
		if(!$institute_id)
		{
		error_log("institute not found. throwing 404...");
		show_404();
		} 	 

		if($institute_id){
		$coursesArray                           = $this->instituteRepository->getLocationwiseCourseListForInstitute($institute_id);
		}		
		$courseList                             = array();

		foreach($coursesArray as $course){
			if((($_REQUEST['city']                  == $course['city_id']) || !($_REQUEST['city']))
				&& (($_REQUEST['locality']              == $course['locality_id']) || !($_REQUEST['locality']) || $_REQUEST['locality'] == 'All')){
				$courseList                             = array_merge($courseList,$course['courselist']);
			}
		}
		
		if(count($courseList)                   == 0){
			$newInstituteId                         = $this->instituteRepository->getRedirectionIdForDeletedInstitute($institute_id,"institute");
			if(!$newInstituteId){
				show_404();
			}else{
				header( "HTTP/1.1 301 Moved Permanently" );
				header( "Location:  ".getSeoUrl($newInstituteId,"institute") );
				exit;
			}	
		}
		
		$courseList                             =array_unique($courseList);
		if(empty($courseList))
		{
			$error                                  = '$ListingClientObj->getCourseList($appId,$institute_id, $status = "live", $userId = \'\')';
			$function                               = "getBasicCourseDetails function in Listing_mobile controller"; 
			sendMailAlert("data not coming from backend issue in ".$error."in".$function,"Listing mobile controller Issue","vikas.k@shiksha.com");		
		}

		if($courseList){
			
			//$institute                            = reset($this->instituteRepository->findWithCourses(array($institute_id => $courseList)));
			$institute                              = $this->_getInstituteWithBasicCourseInfoObjs($institute_id,$courseList,$course_id);
		
		}else{
			$institute                              = $this->instituteRepository->find($institute_id);
		}

		$courses                                = $institute->getCourses();

		$displayData['courseComplete']          = $this->courseRepository->findCourseWithValueObjects($course_id,array('description'),$institute->getCourse($course_id));
		
		$courseObj                              =$displayData['courseComplete'];
		if($courseObj->isCourseMultilocation()  =='true'){
		$this->seeAllBranches($displayData,$courseObj);
		}
		
		/*
		foreach($courses as $courseAll){
		if($courseAll->getId()                  ==$course_id)
		{
		$course                                 =$courseAll;
		break;
		}
		}
		*/
		if(!empty($course_id)) {
		$course                                 = $displayData['courseComplete'];
		}
		
		$displayData['noOfCourses']             =count($courses);
		$displayData['pageType']                = 'course';
		$displayData['tab']                     = 'courses';
		$displayData['institute']               =$institute;
		if(is_object($course)){
		$displayData['course']                  = $course;
		}else{
		$displayData['course']                  = $courseObj;
		}
		$displayData['type']                    = $type;
		$displayData['institute_id']            = $institute_id;
		$displayData['details']                 = $this->getContactInfo($appId,$course_id,$type);
		
		//Code added by Ankur for GA Custom variable tracking		
		$categoryListByCourse                   = array();
		if(!empty($courseList)) {
		$categoryListByCourse                   = $this->instituteRepository->getCategoryIdsOfListing($courseList, 'course', 'true', TRUE);
		}
		
		$displayData['categorylistByCourse']    = $categoryListByCourse;        
		$courseCategory                         = $categoryListByCourse[$course_id];
		$national_course_lib                    = $this->load->library('listing/NationalCourseLib');
		$dominantSubcatData                     = $national_course_lib->getDominantSubCategoryForCourse($course, $courseCategory);
		$displayData['subcatNameForGATracking'] = $this->categoryRepository->find($dominantSubcatData['dominant'])->getName();
		$displayData['pageTypeForGATracking']   = 'COURSE_MOBILE';
		//End code for Tracking
		
		
		// Setting the Dominant Sub Cat ID for widget display check for only full time mba
		$displayData['subcatIDForWidgetDisplayCheck'] = $this->categoryRepository->find($dominantSubcatData['dominant'])->getId();
		
		//Set the Sub-Category choice of the user so that it can be selected be default when user comes to Homepage
		if(isset($dominantSubcatData['dominant'])){
		setcookie('subCategoryUserChoice',$dominantSubcatData['dominant'],time() + 2592000,'/',COOKIEDOMAIN);
		}
		
		$this->getSeoTagsForListing($displayData);
		}

	function populateAdditionalInfo(&$displayData, $pageType){
		$institute_id = $displayData['institute_id'];
		$course_id = $displayData['course_id'];	
		$type = $displayData['type'];
		$appId =1;
		$this->getAlumniSpeakData($displayData);
		$this->_populateCurrentLocation($displayData,$displayData['institute'],$displayData['course'],$displayData['pageType']);
		$this->_makeBreadCrumb($displayData,$displayData['institute'],$displayData['course'],$type);
		//added by akhter
		if($pageType == 'institute')
		{
			$this->_isCampusRepOnInstitute($displayData,$pageType);	
		}else{
			$this->_campusRepData($displayData,$course_id,$institute_id);
		}
		$displayData['whyJoin'] = $this->getReasonToJoinInstitute($institute_id);       
		$displayData['establishedYearAndSeats']=$this->getEstablishYearAndSeats($appId,$institute_id,$course_id);
		$displayData['category'] = $this->getCategoryforCourseIds($course_id);
	}
	
	function _campusRepData(&$displayData,$course_id,$institute_id){
		$courseIdArray = array();
		$courseIdArray[0] = $course_id;               
		$this->load->model('CA/cadiscussionmodel');
		$this->CADiscussionModel  = new CADiscussionModel();
		$campusRepData = $this->cadiscussionmodel->getCampusRepInfoForCourse($courseIdArray, 'course' ,$institute_id);
		$numberOfCACourses = sizeof($campusRepData['caInfo']);
		$displayData['campusConnectAvailable'] = false;
		if($numberOfCACourses > 0) {
		    $displayData['campusConnectAvailable'] = true;
		}		
	}
	
	function listingCoursesTab($institute_id)
	{

		if(empty($institute_id) || !is_numeric($institute_id)){
            show_404();
            exit(0);
        }
		$this->load->builder("nationalInstitute/InstituteBuilder");
		$instituteBuilder = new InstituteBuilder();
		$instituteRepo    = $instituteBuilder->getInstituteRepository();
		$instituteObj     = $instituteRepo->find($institute_id,array('basic'));
		$seoUrl           = $instituteObj->getURL()."/courses";
        if(!empty($seoUrl)){
        	header("Location: $seoUrl",TRUE,301);
            exit;
        }
        else{
        	show_404();
        }

		if(!preg_match('/^\d+$/',$institute_id) || $institute_id == ''){
			show_404();
		}

		/*
		 *	301 Redirect to All Courses Page (MAB-2313)
		 */
		$this->load->builder("nationalInstitute/InstituteBuilder");
		$instituteBuilder = new InstituteBuilder();
		$repo  = $instituteBuilder->getInstituteRepository();
		$instituteObj = $repo->find($institute_id);
		if($instituteObj->getId()){
			$allCoursePageUrl = getSeoUrl($institute_id, 'all_content_pages', '', array('typeOfListing'=>$instituteObj->getType(), 'typeOfPage'=>'courses'));
			redirect($allCoursePageUrl,'location',301);
			exit(0);
		}
		else{
			show_404();
			exit(0);
		}
		
			ini_set('memory_limit', '512M');

			//Load exampage library for exam url. Added by Virender Singh for UGC-3174.
			$this->load->library('examPages/ExamPageRequest');
			$ExamPageLib = $this->load->library('examPages/ExamPageLib');
			// get all live exam pages
			$liveExamPages = array();
			foreach($ExamPageLib->getCategoriesWithExamNames() as $examCategory){
				foreach($examCategory as $key=>$examData){
					$liveExamPages[] = $key;
				}
			}
			unset($ExamPageLib);
			$displayData['liveExamPages'] = $liveExamPages;
			//get Shortlisted course from Db/cookie
			$getMShortlistedCourse = Modules::run('mobile_category5/CategoryMobile/getMShortlistedCourse'); 
			$courseShortArray = $getMShortlistedCourse;
			//get brochureUrl from courseId
			$this->national_course_lib = $this->load->library('listing/NationalCourseLib');
			$brochureURL = $this->national_course_lib;
			$displayData['courseShortArray'] = $courseShortArray;
			$displayData['brochureURL'] = $brochureURL;
			
			$this->_init($displayData, $institute_id);
			$courses = $this->_getCourses($institute_id);
			//$institute = $this->_findInstituteDetails($institute_id,$courses);
			$coursesObj = $this->courseRepository->getDataForMultipleCourses($courses,'basic_info|head_location');
			$institute = $this->instituteRepository->find($institute_id);
			$institute->setCourses($coursesObj);
			
			$displayData['institute'] = $institute;
			$displayData['tab'] = 'courseTabs';
			$displayData['courses']=$courses;
			$displayData['category'] = $this->getCategoryforCourseIds($courses);

			$course=$institute->getFlagshipCourse();
			$displayData['course'] = $course;

			$this->_populateCurrentLocation($displayData,$displayData['institute']);
			$this->getSeoTagsForListing($displayData);
			//$this->load->view('listing/listingPage/coursesTab',$displayData);


			//below lines is used for beacon tracking purpose
			$displayData['trackingpageIdentifier'] = 'allCoursesPage';
			$displayData['trackingpageNo'] = $institute_id;
			$displayData['trackingcityID'] = $displayData['currentLocation']->getCity()->getId();
			$displayData['trackingstateID'] = $displayData['currentLocation']->getState()->getId();
			$displayData['trackingcountryId'] = $displayData['currentLocation']->getCountry()->getId();
			//trackingcatIDtrackingsubCatID

			$this->tracking=$this->load->library('common/trackingpages');
			$this->tracking->_pagetracking($displayData);

			//below line is used for conversion tracking purpose
			$displayData['coursedTrackingPageKeyId']=338;
			$displayData['shortlistTrackingPageKeyId']=336;
			$displayData['recommendationTrackingPageKeyId']=339;
			$displayData['comparetrackingPageKeyId'] = 337;
			$displayData['canonicalURL'] = $displayData['courseTabUrl'];

			$displayData['catIdFromSearchPage'] = $this->input->get('cat');
			$displayData['trackForPages'] = true;

			$this->load->view('courseTab',$displayData);
	}
	
	private function _findInstituteDetails($institute_id,$courseList = array()){
		if($courseList){
			$institute = reset($this->instituteRepository->findWithCourses(array($institute_id => $courseList)));
			//$institute = $this->_getInstituteWithBasicCourseInfoObjs($institute_id,$courseList);
		}else{
			$institute = $this->instituteRepository->find($institute_id);
		}
		return $institute;
	}
	
	function getAlumniSpeakData(&$displayData){
		$objAlumniSpeakClientObj= new AlumniSpeakClient();
		$institute_id = $displayData['institute_id'];
		$pageNum = 0;
		$numRecords = 20000;
		$sort = array('criteria_id,course_comp_year desc');
		$response = $objAlumniSpeakClientObj->getFeedbacksForInstitute($appId, $institute_id, json_encode($sort),$institute_id, $pageNum, $numRecords);
		$displayData['alumniReviews'] = $response;
		$threadIdList = '';
		for($i=0;$i<count($displayData['alumniReviews']);$i++) {
			if($displayData['alumniReviews'][$i]['thread_id'] != '')
			{ 
				$threadIdList .= $displayData['alumniReviews'][$i]['thread_id'];
			}else{
				$threadIdList .= ",".$displayData['alumniReviews'][$i]['thread_id'];
			}    
		}    
	}
	
	private function _getOverviewTabURL($institute,$course,$pageType,$tab){
		if($_COOKIE['OverviewTabURL'.$institute->getId()] && $tab != 'overview'){
			return $_COOKIE['OverviewTabURL'.$institute->getId()];
		}else{
			if($pageType == 'course'){
				setcookie('OverviewTabURL',$institute->getId(),$course->getURL(),0,'/',COOKIEDOMAIN);
			}
			return $institute->getURL();
		}
	}
	
	function getSeoTagsForListing(&$displayData){
		$institute = $displayData['institute'];
		$institute_id = $displayData['institute_id'];
		$course = $displayData['course'];
		$pageType = $displayData['type'];
		$params = array(
						'instituteId'=>$institute->getId(),
						'instituteName'=>$institute->getName(),
						'type'=>'institute',
						'locality'=>$institute->getMainLocation()->getLocality()?$institute->getMainLocation()->getLocality()->getName():"",
						'city'=>$institute->getMainLocation()->getCity()->getName()
					);
		if($_REQUEST['city']){
			$additionalURLParams = "?city=".$_REQUEST['city'];
			if($_REQUEST['locality']){
				$additionalURLParams .= "&locality=".$_REQUEST['locality'];
			}
		}
		
		$institute->setAdditionalURLParams($additionalURLParams);
		$displayData['overviewTabUrl'] = $this->_getOverviewTabURL($institute,$course,$pageType,$displayData['tab']);
		$displayData['askNAnswerTabUrl'] = listing_detail_ask_answer_url($params) . $additionalURLParams;
		$displayData['mediaTabUrl'] = listing_detail_media_url($params) . $additionalURLParams;
		$displayData['alumniTabUrl'] = listing_detail_alumni_speak_url($params) . $additionalURLParams;
		$displayData['courseTabUrl'] = listing_detail_course_url($params) . $additionalURLParams;
		
		$identifier = $pageType;
		if($identifier == 'institute'){
			switch($displayData['tab']){
				case 'ana':
					$identifier = 'AnaTab';
					break;
				case 'media':
					$identifier = 'PhotoTab';
					break;
				case 'alumni':
					$identifier = 'AlumniTab';
					break;
				case 'courses':
					$identifier = 'CourseTab';
					break;
			}
		}
		
		if($displayData['tab'] == 'courseTabs'){$pageType = 'institute';}
		
		$tagsDescription = get_listing_seo_tags(
							$institute->getName(),
							$institute->getMainLocation()->getLocality()?$institute->getMainLocation()->getLocality()->getName():"",
							$course?$course->getName():"",
							$institute->getMainLocation()->getCity()->getName(),
							$institute->getMainLocation()->getCountry()->getName(),
							$pageType,
							$institute->getAbbreviation());
		if($pageType == "course"){
			$metaData = $course->getMetaData();
		}else{
			$metaData = $institute->getMetaData();
		}

		if(!empty($metaData['seoTitle'])){
			$displayData['m_meta_title'] = html_escape($metaData['seoTitle']);
			$displayData['m_meta_title'] = str_replace("&lt;college name&gt;",$institute->getName(),$displayData['m_meta_title']);
			$displayData['m_meta_title'] = str_replace("&lt;course name&gt;",$course->getName(),$displayData['m_meta_title']);
			$displayData['m_meta_title'] = str_replace("&lt;college full name&gt;",$institute->getName(),$displayData['m_meta_title']);
		}else{
			$displayData['m_meta_title'] = html_escape($tagsDescription['Title']);
		}
		if(!empty($metaData['seoDescription']) && $displayData['tab'] == 'overview'){
			$displayData['m_meta_description'] = html_escape($metaData['seoDescription']);
			$displayData['m_meta_description'] = str_replace("&lt;college full name&gt;",$institute->getName(),$displayData['m_meta_description']);
		}else{
			$displayData['m_meta_description'] = html_escape($tagsDescription['Description']);
		}
		if(!empty($metaData['seoKeywords']) && $displayData['tab'] == 'overview'){
			$displayData['m_meta_keywords'] = html_escape($metaData['seoKeywords']);
		}else{
			$displayData['m_meta_keywords'] = html_escape($tagsDescription['Keywords']);
		}
	}

	function getReasonToJoinInstitute($instituteId){
		$ListingObj = new Listing_client();
		$reasonJoin = $ListingObj->getReasonToJoinInstitute($instituteId);
		return $reasonJoin;
	}
	function getContactInfo($appId,$listing_id,$type){
		$ListingClientObj = new Listing_client();
		$contactInfo = $ListingClientObj->getContactInfo($appId,$listing_id,$type);
		return $contactInfo;
	}
	function getEstablishYearAndSeats($appId,$institute_id){
		$ListingClientObj = new Listing_client();
		$establishYearAndSeats =array();
		$establishYearAndSeats[] = $ListingClientObj->getEstablishYearAndSeats($appId,$institute_id);
		return $establishYearAndSeats;

	}
	//function to get category name for the course_id array. 
	function getCategoryforCourseIds($course_id_array){
		$this->load->builder('CategoryBuilder','categoryList');		
		$categoryBuilder = new CategoryBuilder;        
		$ListingClientObj = new Listing_client();
		$categoryIds=array();
		foreach($course_id_array as $key=>$courseId){
			$course_id_array=array($courseId);
			$categoryId= $ListingClientObj->getCategoryIdsForCourseIds($course_id_array);
			$categoryIds[$courseId]=$categoryId[0];
		}
		$courseInfo['categoryIdList']=$categoryIds;
		foreach($categoryIds as $key=>$categoryId){
			if($categoryId!=''){
				$categoryRepository = $categoryBuilder->getCategoryRepository();
				$category = $categoryRepository->find($categoryId);
				$categoryInfoArray[$categoryId]=$category;
			}
		}
		$courseInfo['categoryInfoArray']=$categoryInfoArray;
		return $courseInfo;
	}
	private function _populateCurrentLocation(&$displayData, $institute,$course,$pageType = 'institute'){
		//if($pageType == "course" || $pageType == "courseTabs" ){
		if($course){
			$locations = $course->getLocations();
			$currentLocation = $course->getMainLocation();
		}else{
			$locations = $institute->getLocations();
			$currentLocation = $institute->getMainLocation();
		}
		foreach($locations as $location){
			$localityId = $location->getLocality()?$location->getLocality()->getId():0;
			if($_REQUEST['city'] == $location->getCity()->getId()){
				//$currentLocation = $location;
                                if((!array_key_exists('locality',$_REQUEST) || empty($_REQUEST['locality'])) && $location->isHeadOffice()) {
                                        $currentLocation = $location;
                                        break;
                                }
                                if($_REQUEST['locality'] == $localityId){
                                        $currentLocation = $location;
                                        break;
                                }
                                $matched_city_array[] = $location;
                                $currentLocation = $matched_city_array[0];
			}
		}
		$displayData['currentLocation'] = $currentLocation;
	}
	
	private	function _makeBreadCrumb(&$displayData, $institute,$course,$pageType = 'institute'){
        $refrer = $_SERVER['HTTP_REFERER'];
		$refrer = strtolower($refrer);

			if(stripos($refrer,"-categorypage-") !== FALSE){
				$categoryPage = explode("-categorypage-",$refrer);
				$requestURL = new CategoryPageRequest($categoryPage[1]);
				$request = explode("-",$categoryPage[1]);
			}else{
				if($pageType == "course"){
					$type_id = $course->getId();
                                        $mainLocation = $course->getMainLocation();
				}else{
					$type_id = $institute->getId();
                                        $mainLocation = $institute->getMainLocation();
				}
                $countryId = $mainLocation->getCountry()->getId();  
                $cityId = $mainLocation->getCity()->getId();
                $cityName = $mainLocation->getCity()->getName();
				$categories = $this->instituteRepository->getCategoryIdsOfListing($type_id,$pageType);              
                if($categories->ERROR_MESSAGE == "NO_DATA_FOUND"){
					return array();
				}
				$requestURL = new CategoryPageRequest();
				$i = rand(0,count($categories)-1);
				$subCategory = $this->categoryRepository->find($categories[$i]);
				$category = $this->categoryRepository->find($subCategory->getParentId());
				$requestURL->setData(array('categoryId' => $category->getId(),'subCategoryId' => $subCategory->getId(),'countryId' => $countryId ));
			}
			$crumb[0]['url'] = $requestURL->getURL();
			if($request[1] == 1 || $requestURL->getSubCategoryId() == 1){
				$category = $this->categoryRepository->find($requestURL->getCategoryId());
				$crumb[0]['name'] = $category->getName();
			}else{
				$subCategory = $this->categoryRepository->find($requestURL->getSubCategoryId());
				$category = $this->categoryRepository->find($subCategory->getParentId());
				$crumb[0]['name'] = $subCategory->getName();
				
				if($pageType == "course"){
					$type_id = $course->getId();
                                        $mainLocation = $course->getMainLocation();
				}else{
					$type_id = $institute->getId();
                                        $mainLocation = $institute->getMainLocation();
				}
                $countryId = $mainLocation->getCountry()->getId();
                $cityName = $mainLocation->getCity()->getName();
				$cityList = $this->locationRepository->getCitiesByMultipleTiers(array(1),2);
				if($countryId == 2){
						$cityId = $mainLocation->getCity()->getId();
						$tierOneCityList =array();
						foreach($cityList[1] as $city){
							$tierOneCityList[]= $city->getId();
						}
						if(isset($_COOKIE['userCityPreference']) && $_COOKIE['userCityPreference']!=''){
							$cityIdFromCookie = explode(':',$_COOKIE['userCityPreference']);
							$requestURL->setData(array('categoryId' => $category->getId(),'subCategoryId'=> $subCategory->getId(),'LDBCourseId'=>1,'cityId'=>$cityIdFromCookie[0],'stateId'=>$cityIdFromCookie[1],'countryId'=>2,'localityId'=>0,'zoneId'=>0,'regionId'=>0));	
							$crumb[1]['name'] = $this->locationRepository->findCity($cityIdFromCookie[0])->getName();
						}
						else if(in_array($cityId,$tierOneCityList)){
							$requestURL->setData(array('categoryId' => $category->getId(),'subCategoryId'=> $subCategory->getId(),'LDBCourseId'=>1,'cityId'=>$cityId));
							$crumb[1]['name'] = $cityName;
						}		
						else 
						{
							$requestURL->setData(array('categoryId' => $category->getId(),'subCategoryId'=> $subCategory->getId(),'LDBCourseId'=>1,'cityId'=>1,'stateId'=>1,'countryId'=>2,'localityId'=>0,'zoneId'=>0,'regionId'=>0));
							$crumb[1]['name'] = "All Cities";
						}
                }
			}  
		$displayData['breadCrumb'] =  $crumb;
	}

	function alsoOnShiksha($courseId, $type = 'course',$trackingPageKeyId='',$similarShortlistTrackingPageKeyId='',$comparetrackingPageKeyId=''){
		show_404();
		exit;	
		if(empty($courseId)) {
				return ;
		}
		
		$this->load->library('categoryList/CategoryPageRecommendations');
		$this->load->helper('listing/listing');
		$this->_init($displayData, $courseId);
		
		$alsoViewedInstitutes = $this->categorypagerecommendations->getAlsoViewedInstitutes($courseId);
		
		if(is_array($alsoViewedInstitutes) && count($alsoViewedInstitutes)) {
				//get Shortlisted course from Db/cookie
				$displayData['courseShortArray'] = Modules::run('mobile_category5/CategoryMobile/getMShortlistedCourse');
				$displayData['courseMBAShortlistArray'] = Modules::run('mobile_category5/CategoryMobile/getMBAShortlistedCourses');
				
				//get brochureUrl from courseId
				$this->national_course_lib = $this->load->library('listing/NationalCourseLib');
				$brochureURL = $this->national_course_lib;
				$displayData['brochureURL'] = $brochureURL;
				
				$displayData['institutes'] = array_slice($this->instituteRepository->findWithCourses($alsoViewedInstitutes),0,3);
				$displayData['recommendationPage'] = 1;
				$displayData['alsoOnShiksha'] = 1;
				$displayData['recommendationsApplied'] = isset($_COOKIE['recommendation_applied'])?explode(',',$_COOKIE['recommendation_applied']):array();
				$displayData['validateuser'] = $this->checkUserValidation();
				$displayData['ajaxRequest'] = "true";
				$displayData['alsoOnShiksha'] = "true";

				//below lines used for conversion tracking purpose
				if(isset($trackingPageKeyId))
				{
					$displayData['trackingPageKeyId']=$trackingPageKeyId;
				}
				if(isset($similarShortlistTrackingPageKeyId))
				{
					$displayData['tracking_keyid']=$similarShortlistTrackingPageKeyId;
				}
				if( ! empty($comparetrackingPageKeyId))
				{
					$displayData['comparetrackingPageKeyId'] = $comparetrackingPageKeyId;
				}
				if($type) {
					$displayData['pageType'] = $type;
				} else {
					$displayData['pageType'] = 'mobileAlsoViewedSection';
				}
				
				$courseList = array_values($alsoViewedInstitutes);
				$categoryListByCourse = $this->instituteRepository->getCategoryIdsOfListing($courseList, 'course', 'true', TRUE);
				foreach($alsoViewedInstitutes as $alsoViewedCourseId) {
					$course = $this->courseRepository->find($alsoViewedCourseId);
					if($this->_checkMBATemplateEligibility($categoryListByCourse[$alsoViewedCourseId], $course)) {
						$displayData['isMBATemplateByCourse'][$alsoViewedCourseId] = true;
					}
					unset($course);
				}

				//change for institute to college story
				$displayData['collegeOrInstituteRNR'] = 'institute';
			    $national_course_lib = $this->load->library('listing/NationalCourseLib');
			    $categoryIds = $national_course_lib->getCourseInstituteCategoryId($courseId,'course');
				if(count(array_intersect($categoryIds, array("2", "3"))) != 0) { 
					$displayData['collegeOrInstituteRNR'] = 'college';
				}
				
				$template_name = 'mobile_category5/mobileCategoryListings';
				$this->load->view($template_name,$displayData);
		}
	}
	
	function seeAllBranches(&$displayData,$listing){
		
		$displayData['name']  = html_escape($listing->getName());
		$displayData['url']  = $listing->getURL();
		$displayData['listing']  = $listing;
		$locations = $listing->getLocations();
		if(count($locations) <= 1){
			return "";
		}
		$displayData['loctionsWithLocality'] = array();
		$displayData['otherLocations'] = array();
		foreach($locations as $location){
			if($location->getLocality() && $location->getLocality()->getName()){
				$city = $location->getCity();
				$displayData['loctionsWithLocality'][$city->getId()][] = $location;
			}else{
				$displayData['otherLocations'][] = $location;
			}
		}
	}
	 function increaseContactCount($listing_id,$listing_type,$tracking_field){
                $listingmodel=$this->load->model('listing/listingmodel');
                $updateStatus = $listingmodel->increaseContactCountOfListing($listing_id,$listing_type,$tracking_field);
                echo $updateStatus;
        }

        //Function to Log Call activity on Mobile phone. This logging will be done for both Institute and Course pages.
        function logCallActivity(){
            $requestArr['listingType'] = isset($_POST['listingType'])?$_POST['listingType']:'course';
            $requestArr['listingId'] = isset($_POST['listingId'])?$_POST['listingId']:0;
            $requestArr['userId'] = (isset($_POST['userId']))?$_POST['userId']:-1;
            $requestArr['numberToCall'] = isset($_POST['numberToCall'])? trim($_POST['numberToCall']):0;
            $this->load->model('ShikshaModel');
            $this->ShikshaModel->insertCallTracking($requestArr);
            return true;
        }

    function sortCollegeReviewsMobile($courseId,$criteria) {
		$this->load->builder('ListingBuilder','listing');
		$this->load->helper('listing/listing');
		$listingBuilder 			  = new ListingBuilder;
		$this->courseRepository       = $listingBuilder->getCourseRepository();
		$this->national_course_lib 	  = $this->load->library('listing/NationalCourseLib');
		$displayData['courseReviews'] = $this->national_course_lib->getCourseReviewsData(array($courseId));
		$displayData['courseReviews'][$courseId]['reviews'] = $this->national_course_lib->sortCollegeReviews($displayData['courseReviews'][$courseId]['reviews'],$criteria);
		$displayData['share'] = array('facebook','twitter','linkedin','google','whatsapp');
        $displayData['subTitle'] = 'Check out this college review. This might be helpful for you.';

        $displayData['validateuser'] = $this->checkUserValidation();
        $displayData['userData']['sessionId'] = sessionId();		
		if(isset($displayData['validateuser'][0]['userid'])) {
		    $displayData['userData']['userId'] = $displayData['validateuser'][0]['userid'];
		} else {
		    $displayData['userData']['userId'] = 0;
		}
		$displayData['institute_name'] = $this->courseRepository->find($courseId)->getInstituteName();
		
		//Get User Session Data
		$userSessionData = array();
		$userSessionData = Modules::run("CollegeReviewForm/CollegeReviewController/getUserSessionData",$displayData['userData']['userId'], $displayData['userData']['sessionId']);
		
		if(is_array($userSessionData)){
			$displayData['userData']['userSessionData'] = $userSessionData;
		}
				
		echo $this->load->view('mobile_listing5/mobileCollegeReviewContent',array('courseReviews' => $displayData['courseReviews'],'course' => $this->courseRepository->find($courseId),'share' => $displayData['share'],'subTitle' => $displayData['subTitle'],'userData' => $displayData['userData'],'validateuser' => $displayData['validateuser'],'institute_name' => $displayData['institute_name']),true); 
		exit;
	}

	/**
	 * Method to redirect(301) listings to their proper seo url
	 * @author Romil Goel <romil.goel@shiksha.com>
	 * @date   2015-04-16
	 * @param  [Object]     $listingObject [course/institute object]
	 * @return [type]                    [description]
	 */
	private function _validateListingsURL($listingObject) {
		$userInputURL = $_SERVER['SCRIPT_URI'];
		$userInputURL = trim($userInputURL);
		$userInputURL = trim($userInputURL,"/");
		$queryString  = substr(strrchr($_SERVER['REQUEST_URI'], "?"), 0);
		
		$listingId = $listingObject->getId();
        if(empty($listingObject) || empty($listingId)) {
                return;
        }
        
		$actualURL     = $listingObject->getUrl();
		if(!empty($actualURL) && $actualURL != $userInputURL) {
			redirect($actualURL.$queryString, 'location', 301);
		}
		
	}
	
	/***
	 * functionName : _isCampusRepOnInstitute
	 * functionType : no return type
	 * param        : $displayData, $pageType
	 * desciption   : this is using to check campus rep is available on the insitute or not , if yes then make a ajax call to load view
	 * @author      : akhter
	 * @team        : UGC
	***/
	function _isCampusRepOnInstitute(& $displayData, $pageType)
	{
		$this->load->model('CA/cadiscussionmodel');
		$this->CADiscussionModel  = new CADiscussionModel();
		$instituteId = $displayData['institute']->getId();
		$courseIdArray = $displayData['courses'];
		$getCaAnsCount = ($pageType == 'course') ? 'true' : 'false';
		$campusRepData = $this->cadiscussionmodel->getCampusRepInfoForCourse($courseIdArray, $pageType ,$instituteId, 50, true,$getCaAnsCount);
		$numberOfCACourses = sizeof($campusRepData['caInfo']);
		$displayData['campusConnectAvailable'] = false;
		if($numberOfCACourses > 0) {
			$this->load->config('CA/MentorConfig',TRUE);
      		$allowSubCatArr = array_keys($this->config->item('enabledSubCats','MentorConfig'));
			$this->load->library('CA/CADiscussionHelper');
			$caDiscussionHelper =  new CADiscussionHelper();
			$repData = $caDiscussionHelper->_separateCampusRepData($campusRepData, $instituteId, $pageType, $allowSubCatArr);
			if($repData['repData']['repInfo']['totalRep'] >0){
				$displayData['campusConnectAvailable'] = true;
			}
		}
	}
}	

?>

