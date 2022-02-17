<?php

class ListingPage extends MX_Controller
{
    private $courses;
    function _init(& $displayData,$typeId,$type = 'institute'){
		define("PAGETRACK_BEACON_FLAG",false);
		$this->load->helper('coursepages/course_page');
		$this->load->helper('shikshaUtility');
		$this->load->helper('image');
		$this->load->builder('CategoryBuilder','categoryList');
		$this->load->builder('LDBCourseBuilder','LDB');
		$this->load->builder('LocationBuilder','location');
		$this->load->builder('ListingBuilder','listing');
		$this->load->library(array('categoryList/categoryPageRequest','listing/listing_client','listing/ListingMbaLib','listing/ListingEngineeringLib'));
		$this->load->model('ldbmodel');
		$this->load->model('QnAModel');
		$this->load->model('listing/coursemodel');
		$this->load->model('listing/institutemodel');
		$this->config->load('zopim_chat_config');
		$categoryBuilder                                = new CategoryBuilder;
		$LDBCourseBuilder                               = new LDBCourseBuilder;
		$locationBuilder                                = new LocationBuilder;
		$listingBuilder                                 = new ListingBuilder;
		$this->ListingClientObj                         = new Listing_client();
		$this->listingMbaLib                            = new ListingMbaLib();
		$this->listingEngineeringLib                    = new ListingEngineeringLib();
		$this->instituteRepository                      = $listingBuilder->getInstituteRepository();
		$this->courseRepository                         = $listingBuilder->getCourseRepository();
		$this->categoryRepository                       = $categoryBuilder->getCategoryRepository();
		$this->LDBCourseRepository                      = $LDBCourseBuilder->getLDBCourseRepository();
		$this->locationRepository                       = $locationBuilder->getLocationRepository();
		$this->universityRepository                     = $listingBuilder->getUniversityRepository();
		$this->abroadCourseRepository                   = $listingBuilder->getAbroadCourseRepository();
		$this->departmentRepository                     = $listingBuilder->getAbroadInstituteRepository();
		$this->national_course_lib                      = $this->load->library('listing/NationalCourseLib');
		$this->cadiscussionmodel                        = $this->load->model('CA/cadiscussionmodel');
		$displayData['instituteRepository']             = $this->instituteRepository;
		$displayData['courseRepository']                = $this->courseRepository;
		$displayData['categoryRepository']              = $this->categoryRepository;
		$displayData['LDBCourseRepository']             = $this->LDBCourseRepository;
		$displayData['locationRepository']              = $this->locationRepository;
		$displayData['validateuser']                    = $this->checkUserValidation();
		$displayData['pageType']                        = $type;
		$displayData['typeId']                          = $typeId;
		$displayData['trackForPages']                   = true;
		global $listings_with_localities;
		$displayData['listings_with_localities']        = json_encode($listings_with_localities);
		
		try{		//Added to track cookie		
			Modules::run("Online/OnlineFormConversionTracking/trackUserPBT",$typeId,'yes');
		} catch(Exception $e){
		
		}
		
		if($type == 'institute') { 			
			$response_returned                                = $this->_getCoursePageParams($displayData);
			if(count($response_returned) >0) {
				$displayData['course_page_required_category'] = $response_returned['course_page_required_category'];
				$displayData['course_pages_tabselected']      = $response_returned['course_pages_tabselected'];
			}
		}
		
		if($displayData['validateuser'] != 'false') {
			if($displayData['validateuser'][0]['usergroup'] == 'cms' || $displayData['validateuser'][0]['usergroup'] == 'enterprise' || $displayData['validateuser'][0]['usergroup'] == 'sums' || $displayData['validateuser'][0]['usergroup'] == 'saAdmin' || $displayData['validateuser'][0]['usergroup'] == 'saCMS'){
				$editedData                                     = $this->getLastModifiedDetails($type, $typeId);
				$displayData['editedData']                      = $editedData;
			}
		}
	}
	
	private function _findInstituteDetails($institute_id,$courseList = array()){
		if($courseList){
			$institute = reset($this->instituteRepository->findWithCourses(array($institute_id => $courseList)));
		}else{
			$institute = $this->instituteRepository->find($institute_id);
		}
		return $institute;
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
		    if(!empty($abroadCourseItem)){
		    	$url = $abroadCourseItem->getURL();
		    	redirect($url,'location',301);
		    }
		    else{
		    	show_404();
		    }
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
			if((($_REQUEST['city'] == $course['city_id']) || !($_REQUEST['city'])) && (($_REQUEST['locality'] == $course['locality_id']) || !($_REQUEST['locality']) || $_REQUEST['locality'] == 'All')){
			  //  $courseList = array_merge($courseList,$course['courselist']); // performance optimization
			   if(is_array($courseList) && is_array($course['courselist'])) {
                       		    $courseList = $course['courselist'] + $courseList;
                           }

			}
		}
		/*
		 *	If course list is not formed for the requested listing location then redirect to the listing's default URL (Head Ofc)..
		 */
		if(count($courseList) == 0){
		    if($courseId != "") {
				$course = $this->courseRepository->find($courseId);
				$instituteId = $course->getInstId();
				$course->getId() == "" || empty($instituteId) ? show_404() : redirect($course->getUrl(), 'location', 301);
		    }
		    elseif($institute_id != "") {			    
			$institute = $this->instituteRepository->find($institute_id);
			$institute->getId() == "" ? show_404() : redirect($institute->getUrl(), 'location', 301);
		    }
		    else {
			show_404();
		    }
		    exit();
		}
		else {
		    return array_unique($courseList);
		}
		    
	    }
	    else {
		/*
		 * Following this is the old code for it being an Indian listing that was deleted
		 */
		$newInstituteId  = $this->instituteRepository->getRedirectionIdForDeletedInstitute($institute_id,"institute");
	        if(!$newInstituteId){
		    show_404();
		}
	        else{			
    		    $institute = $this->instituteRepository->find($newInstituteId);
		    $institute->getId() == "" ? show_404() : redirect($institute->getUrl(), 'location', 301);
		    exit();
		}
	    }			    
	}
	
	
	
	private function _populateAdditionalData(& $displayData, $institute,$course,$pageType = 'institute'){
		$this->_populateSEOData($displayData,$institute,$course,$pageType);
		if(function_exists ('logPerformanceData')) { logPerformanceData($this->startTime); } 
		$this->_populateCurrentLocation($displayData,$institute,$course,$pageType);
		if(function_exists ('logPerformanceData')) { logPerformanceData($this->startTime); } 
		$this->_makeBreadCrumb($displayData,$institute,$course,$pageType);
		if(function_exists ('logPerformanceData')) { logPerformanceData($this->startTime); } 
		$this->_isInstitutePaid($institute, $displayData);
		if(function_exists ('logPerformanceData')) { logPerformanceData($this->startTime); } 
		$this->_getZopimChatScriptForInstitute($displayData, $institute);
		if(function_exists ('logPerformanceData')) { logPerformanceData($this->startTime); } 
		$displayData['alumniFeedbackRatingCount'] = $this->instituteRepository->getAlumniFeedbackRatingCount($institute->getId());
		
                //_p($institute);
	}
    
	private function _getZopimChatScriptForInstitute(& $displayData, $institute){
		$displayData['zopimScriptTag'] = false;
		$displayData['zopimInstituteId'] = false;
		if(!empty($institute)){
			$instituteId = $institute->getId();
			if(!empty($instituteId)){
				$zopimChatEnabledInstitutes = $this->config->item('ZOPIM_CHAT_ENABLED_INSTITUTES');
				if(in_array($instituteId, $zopimChatEnabledInstitutes)){
					$zopimConfigId = "SCRIPT_INSTITUTE_" . $instituteId;
					$zopimScript = $this->config->item($zopimConfigId);
					if(!empty($zopimScript)){
						$displayData['zopimScriptTag'] = $zopimScript;
						$displayData['zopimInstituteId'] = $instituteId;
					}
				}
			}
		}
	}
	
	private function _populateRankingPageWidgetData(& $displayData, $course){
		$courseSpecializationIds = array();
		$courseSubCategoryIds 	 = array();
		if(!empty($course)){
			$course_id = $course->getId();
			$courseModel = new coursemodel();
			$courseSpecializations = $courseModel->getSpecializationIdsByClientCourse($course_id);
			foreach($courseSpecializations as $courseId => $specializationDetails){
				foreach($specializationDetails as $specialization){
					$courseSpecializationIds[] = $specialization['SpecializationId'];
				}
			}
			if(empty($courseSpecializationIds)){
				$courseSubCategoryIds = $this->instituteRepository->getCategoryIdsOfListing($course_id, 'course');
			}
			if(NEW_RANKING_PAGE) {
				$widgetHTML = Modules::run(RANKING_PAGE_MODULE.'/RankingMain/getRankingPageWidgetHTML', $courseSubCategoryIds, $courseSpecializationIds, true, 'listingpage');
			} else {
				$widgetHTML = Modules::run('ranking/RankingMain/getRankingPageWidgetHTML', $courseSubCategoryIds, $courseSpecializationIds, true, 'listingpage');
			}
			$displayData['rankingWidgetHTML'] = $widgetHTML;
		} else {
			$displayData['rankingWidgetHTML'] = "";
		}
		$displayData['courseSpecializationIds'] = $courseSpecializationIds;
	}

	private function _populateRankingPageLinks(& $displayData, $course){
		
		$courseSpecializationIds = array();
		$courseSubCategoryIds 	 = array();
		if(!empty($course)){
			$course_id = $course->getId();
			$courseModel = new coursemodel();
			$courseSpecializations = $courseModel->getSpecializationIdsByClientCourse($course_id);
			foreach($courseSpecializations as $courseId => $specializationDetails){
				foreach($specializationDetails as $specialization){
					$courseSpecializationIds[] = $specialization['SpecializationId'];
				}
			}

			if(empty($courseSpecializationIds)){
				$courseSubCategoryIds = $this->instituteRepository->getCategoryIdsOfListing($course_id, 'course');
			}

			$rankingFiltersData          = array();	
			$rankingFiltersData['city']  = $displayData['currentLocation']->getCity()->getId();
			$rankingFiltersData['state'] = $displayData['currentLocation']->getCity()->getStateId();
			$rankingFiltersData['exams'] = $course->getEligibilityExams();
			
			$rankingSeoUrls = Modules::run(RANKING_PAGE_MODULE.'/RankingMain/getRankingPageCoursePageWidget', $courseSubCategoryIds, $courseSpecializationIds, true, 'listingpage',$rankingFiltersData);
			$displayData['rankingSeoUrls'] = $rankingSeoUrls;
		} else {
			$displayData['rankingSeoUrls'] = "";
		}
		$displayData['courseSpecializationIds'] = $courseSpecializationIds;
	}
	
	private function _populateCurrentLocation(& $displayData, $institute,$course,$pageType = 'institute'){
                $multiple_locations = array();
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
		return $currentLocation;
	}

	private function _populateSEOData(& $displayData, $institute,$course,$pageType = 'institute'){
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
		if($pageType=='course'){
		    $course->setAdditionalURLParams($additionalURLParams);
		    $params = array(
						'courseId'=>$course->getId(),
						'instituteName'=>$institute->getName(),
						'courseName'=>$course->getName(),
						'type'=>'course',
						'course'=>$course
					);
		    $displayData['campusRepTabUrl'] = listing_campus_rep_url($params) . $additionalURLParams;
		}
		
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

		if($displayData['tab'] == 'campusRep'){
		    $identifier = 'campusRepTab';
		    $tagsDescription = get_listing_seo_tags(
												$institute->getName(),
												$course->getMainLocation()->getLocality()?$course->getMainLocation()->getLocality()->getName():"",
												$course?$course->getName():"",
												$course->getMainLocation()->getCity()->getName(),
												$course->getMainLocation()->getCountry()->getName(),
												$identifier,
												$institute->getAbbreviation());
		}
		else{
		    $tagsDescription = get_listing_seo_tags(
												$institute->getName(),
												$institute->getMainLocation()->getLocality()?$institute->getMainLocation()->getLocality()->getName():"",
												$course?$course->getName():"",
												$institute->getMainLocation()->getCity()->getName(),
												$institute->getMainLocation()->getCountry()->getName(),
												$identifier,
												$institute->getAbbreviation());
		}

		if($pageType == "course"){
                        if($displayData['course']->getSeoDataFlag() == 1) {
                            $metaData = $course->getMetaData();
                        } else {
                            $metaData = array();
                        }
		}else{
			$metaData = $institute->getMetaData();
		}
		
		if(!empty($metaData['seoTitle']) && $displayData['tab'] == 'overview'){
			$displayData['title'] = html_escape($metaData['seoTitle']);
		}
		if(!empty($metaData['seoDescription']) && $displayData['tab'] == 'overview'){
			$displayData['metaDescription'] = html_escape($metaData['seoDescription']);
		}
		// note :: default title & description are now created with getDefaultMetaData  from national course lib
		if(!empty($metaData['seoKeywords']) && $displayData['tab'] == 'overview'){
			$displayData['metaKeywords'] = html_escape($metaData['seoKeywords']);
		}else{
			$displayData['metaKeywords'] = html_escape($tagsDescription['Keywords']);
		}
	}
	
	private function _getOverviewTabURL($institute,$course,$pageType,$tab){
		if($tab != 'overview' && $tab!='campusRep' && $tab!='ana'){
				return $course->getURL();
		}else{
			if($pageType == 'course' && $tab == 'overview'){
				return $institute->getURL();
			}
            if($pageType == 'course' && $tab == 'campusRep'){
				return $course->getURL();
            }
	
			return $institute->getURL();
		}
	}
	
	
	/*
	 * 	NEW BreadCrumb Logic
	 * 	
	 */
	
	private	function _makeBreadCrumb(& $displayData, $institute,$course,$pageType = 'institute'){

		$breadcrumbOptions = array('generatorType' 	=> 'ListingPage',
									'options' => array('displayData' => & $displayData,
									'institute' => & $institute,
									'course' => & $course,
									'pageType' => $pageType));
		$BreadCrumbGenerator = $this->load->library('common/breadcrumb/BreadcrumbGenerator', $breadcrumbOptions);

		$currentLocation = $displayData['currentLocation'];
		$displayData['breadcrumbHtml'] = $BreadCrumbGenerator->prepareBreadcrumbHtml();
	    
	    $displayData['googleRemarketingParams'] = $this->_getGoogleRemarketingParams($institute, $course, $pageType, $currentLocation, $displayData);
	    //_p($crumb);die;
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

	//avoid google crawl for edukart to limit resource usage
	function avoidcrawlforlistings($instituteId){
		if(isset($instituteId) && in_array($instituteId, array(35861)) && strstr(strtolower($_SERVER['HTTP_USER_AGENT']), "googlebot")){
			return;
		}
	}	
	
	function listingOverviewTab($typeId, $type = 'institute')
	{

		if(empty($typeId) || !is_numeric($typeId)){
            show_404();
            exit(0);
        }
		if($type == 'course'){
			$this->load->builder("nationalCourse/CourseBuilder");
			$courseBuilder = new CourseBuilder();
			$this->load->library('nationalCourse/CourseDetailLib');
        	$this->courseDetailLib = new CourseDetailLib; 
			$this->courseRepo = $courseBuilder->getCourseRepository();
			$courseObj = $this->courseRepo->find($typeId);
			$this->courseDetailLib->checkForCommonRedirections($courseObj, $typeId);
		}



		if(!in_array(strtolower(trim($type)), array('institute','course'))){
			show_404();
		}
		if(empty($typeId) || !is_numeric($typeId)){
			show_404();	
		}
		global $restrictedInstituteIds, $restrictedCourseIds;
		if(DISABLE_RESTRICTED_LISTING) {
			if(($type == 'institute' && in_array($typeId, $restrictedInstituteIds)) || in_array($typeId, $restrictedCourseIds)) {
				show_404();
				exit;
			}
		}

		//Redirect old listing to new listing page
		if(!empty($typeId) && $type == 'institute'){
			$this->load->builder("nationalInstitute/InstituteBuilder");
	        $instituteBuilder = new InstituteBuilder();
	        $instituteRepo = $instituteBuilder->getInstituteRepository();
	        $instituteObj = $instituteRepo->find($typeId,array('basic'));

	        $this->instituteDetailLib = $this->load->library('nationalInstitute/InstituteDetailLib');
			$this->instituteDetailLib->_checkForCommonRedirections($instituteObj, $typeId, $type);
	        
		}

		/* Adding XSS cleaning (Nikita) */
		$typeId = $this->security->xss_clean($typeId);
		$type = $this->security->xss_clean($type);
		
		//ini_set("memory_limit", "3000M");
	
        $this->startTime = microtime(true);
		require FCPATH.'globalconfig/listingDocumentDownload.php';

		
		
		if($type == "scholarship") {
			Header( "HTTP/1.1 301 Moved Permanently" );
			Header( "Location: http://www.shiksha.com");
			exit();
		}
		
		$this->_init($displayData,$typeId,$type);
		$this->load->library("examPages/ExamPageRequest");

		/*
		Method to validate listing page and redirection
		Input Params  = listing Id and listing type
		Outupt Params = course id and instiute id
		 */
		$listingPageData = $this->_redirectListingPage($typeId,$type);
		$course_id       = $listingPageData['course_id'];
		$institute_id    = $listingPageData['institute_id'];
		$courseObj       = $listingPageData['courseObj'];

		//avoid google crawl for edukart to limit resource usage
		$this->avoidcrawlforlistings($institute_id);

		
        if(isset($institute_id) && in_array($institute_id, array(35861)) && $type == 'course') {
          ini_set('memory_limit','150M');
        }

		// get all course ids and associate course object with basic info in Institute Object
		$courses         = $this->_getCourses($institute_id, $course_id);
		$institute       = $this->getInstituteWithBasicCourseInfoObjs($institute_id,$courses); // institute with basic course info objects
		
		//fetch flagship course detail on Institute Page		
		if(!$course_id){
			$flagShipCourseWithBasicInfo = $institute->getFlagshipCourse(); // get flagship course with basic info
			$flagshipCourseId            = $flagShipCourseWithBasicInfo->getId();
			$course                      = $this->courseRepository->find($flagshipCourseId); // load full object for flagship 
			$course_id                   = $course->getId();
		}
		
		// if course object is blank
		if(!$course){
			//$course = $this->courseRepository->find($course_id);
			if(empty($courseObj)){
				$course = $this->courseRepository->find($course_id);
			}else{
				$course = $courseObj;
			}
		}
		
		// get subcategory of courses
		$categoryListByCourse                 = array();
		if(!empty($courses)) {
			$categoryListByCourse                 = $this->instituteRepository->getCategoryIdsOfListing($courses, 'course', 'true', TRUE);
			$displayData['categorylistByCourse']  = $categoryListByCourse;
			$displayData['institute_course_list'] = $courses;
		}

		/*
		common code used in Institute and Course Pages Start
		 */
		//ldb Code
		$call_back_data = $this->callBackOnUserNumber($institute);
		if(count($call_back_data)>0) {
			$displayData['call_back_yes'] = 1;
			$displayData['toNumber'] = $call_back_data['toNumber'];
			$displayData['fromNumber'] = $call_back_data['fromNumber'];
			$displayData['institute_name'] = $call_back_data['institute_name'];
			$displayData['call_back_message'] =  $call_back_data['call_back_message'];
		}
		$displayData['view'] = 'default';
		$displayData['share'] = array('facebook','twitter','linkedin','google');
		$displayData['subTitle'] = 'Check out this college review. This might be helpful for you.';
		//to get domainant subcategory
		$dominantSubcatData                     = $this->national_course_lib->getDominantSubCategoryForCourse($course, $categoryListByCourse[$course->getId()]);
		
		
		$displayData['subcatNameForGATracking'] = $this->categoryRepository->find($dominantSubcatData['dominant'])->getName();

		/* Last Updated time */
		$updatedTime                = array ();
		$lastUpdatedDate            = ($type == 'institute') ? $institute->getLastUpdatedDate() : $course->getLastUpdatedDate();
		$updatedTime                = explode ( " ", $lastUpdatedDate );
		$displayData['updatedDate'] = $updatedTime [0];
		$shikshaDataLastUpdated     = date("Y-m-d H:i:s",strtotime(SHIKSHA_DATA_LAST_UPDATED));
		if ($lastUpdatedDate > $shikshaDataLastUpdated)
		{
			$displayData['updated']     = "true";
		}
		
		$displayData['dominantSubcatData']      = $dominantSubcatData;
		// set IIM Predictor promotional banner for iim colleges and iim courses instead of tools to prepare
		$displayData['IIMColleges']						= array(307,318,20190,333,20188,29623,23700,32728,32736,36076,36085,32740,36080,47712,47709,47711,47703,38238,47745);
		$displayData['IIMCourses']						= array(1653,1645,1688,22931,241736,111876,111875,22929,119554,27159,164791,130026,130055,164564,164788,130065,164638,241826,241811,241824,241783,193061,242136);
		
		/*
		 * LISTINGS NATIONAL INSTITUTE PAGES REVAMP
		 */
		$this->setVisitedListingCookieForRNRCatPage($institute->getId(), $displayData);

		/*
		common code used in Institute and Course Pages End
		 */
		
		/*
		NATIONAL LISTING PAGE INSTITUTE AND COURSE
		 */
		if($type == 'institute'){
		    $this->nationalInstitutePage($institute, $course, $displayData);
		    if($_REQUEST['profiling']) {
		    _p(" ..........................................PEAK MEMORY USAGE :".(memory_get_peak_usage()/(1024*1024))." MB");
		     }
		    return ;
		}elseif($type == 'course'){
		    $this->nationalCoursePage($course, $institute, $displayData);
		    if($_REQUEST['profiling']) {
		    _p("..........................................PEAK MEMORY USAGE :".(memory_get_peak_usage()/(1024*1024))." MB");
		     }
		    return ;
		}
		
		$displayData['institute'] = $institute;
		$displayData['institute']->instituteCourses=$this->instituteRepository->getCategoryIdsOfListing($institute_id,'institute');
		$displayData['course'] = $course;
		$displayData['pageType'] = $type;
		$displayData['typeId'] = $typeId;
		$displayData['courseComplete'] = $this->courseRepository->findCourseWithValueObjects($course_id,array('description'));
		$displayData['instituteComplete'] = $this->instituteRepository->findInstituteWithValueObjects($institute_id,array('description','joinreason'));
		$displayData['tab'] = 'overview';
		
		/**
		 * Make GET variables i.e. query string params available
		 */ 
		parse_str($_SERVER['QUERY_STRING'], $_GET);
		$inlineView = intval($_GET['inlineView']);
		$displayData['inlineView'] = $inlineView;
		
		$displayData['mainCategoryIdsOnPage'] = $this->instituteRepository->getMainCategoryIdsOfListing($course_id, 'course');
		$this->_populateAdditionalData($displayData, $displayData['institute'],$course,$type);
		$this->_populateRankingPageWidgetData($displayData, $course);
		
		if($_REQUEST['download'] && $listingDocuments[$typeId]) {
			$displayData['documentDownload'] = TRUE;
			if($type == 'course') {
				$displayData['redirectAfterDocumentDownload'] = $displayData['courseComplete']->getURL();
			}
			else {
				$displayData['redirectAfterDocumentDownload'] = $displayData['instituteComplete']->getURL();
			}
		}

		$this->load->model('CA/cadiscussionmodel');
		$this->CADiscussionModel  = new CADiscussionModel();
	
		$result = $this->CADiscussionModel->getCampusReps($course_id, $institute_id);
		$currentLocation=$this->_populateCurrentLocation($displayData, $displayData['institute'],$course,$type);
		$locations = $course->getLocations();
		$location = $locations[$currentLocation->getLocationId()];
	       
		$getInstituteLocation = false;
		if(!$location){
			$getInstituteLocation = true;
		}
		else{
		    $contactDetail = $location->getContactDetail();
		    if($contactDetail->getContactPerson()){
			    $displayname = $contactDetail->getContactPerson();
		    }
		    else{
			    $getInstituteLocation = true;
		    }
		}
		if($getInstituteLocation){	//Check for Institute contact person
				$locations = $institute->getLocations();
				$location = $locations[$currentLocation->getLocationId()];
				$contactDetail = $location->getContactDetail();
				if($contactDetail->getContactPerson()){
					$displayname = $contactDetail->getContactPerson();
				}
		}
		$displayData['instituteRep'] = $result;
		$displayData['displayName']=$displayname;
		
		
		$URLWidgetData = modules::run('recommendation/recommendation_server/getRecommendedCategoryPageURLsForCourse', $course_id);
		$displayData['URLWidgetData'] = $URLWidgetData;
		
		
		$this->load->view('listing/listingPage/overviewTab',$displayData);
	}
	
	function getSourceWiseRankingData($course) {
		$this->load->builder('RankingPageBuilder', RANKING_PAGE_MODULE);
		$rankingCommonLib = RankingPageBuilder::getRankingPageCommonLib();
		$rankingURLManager = RankingPageBuilder::getURLManager();
		
		//get all sources with their overall rank for this course
		$course_id = $course->getId();
		$sourcesWithCourseRank = $rankingCommonLib->getSourceWiseCourseRanks($course_id);
		
		//get ranking page url
		if($course->getMainLocation()->getCity()->getvirtualCityId()>0) {
		    $city_id = $course->getMainLocation()->getCity()->getvirtualCityId();
		} else {
		    $city_id = $course->getMainLocation()->getCity()->getId();
		}
		foreach($sourcesWithCourseRank as $key => $source) {
			$urlIdentifier = $source['ranking_page_id']."-0-0-".$city_id."-0";
			$rankingPageRequest = $rankingURLManager->getRankingPageRequest($urlIdentifier);
			$sourcesWithCourseRank[$key]['ranking_page_url'] = $rankingURLManager->getCurrentPageURL($rankingPageRequest);
		}
		return $sourcesWithCourseRank;
	}
	
	function getRankingData($course, $categoryListByCourse){
		$course_id = $course->getId();
		$institute_id = $course->getInstId();
		$this->load->builder('ranking/RankingPageBuilder', 'ranking');
		$rankingURLManager  	= RankingPageBuilder::getURLManager();
		$rankingCommonLib 	= RankingPageBuilder::getRankingPageCommonLib();
		$rankingDetailsforSpecialization = $rankingCommonLib->getCourseRankingDetails(array($course_id));
		$rankingSubCatId = $rankingDetailsforSpecialization[$course_id]['category_ids'][0];
		
		//get dominant subcat
		$courseCategoryIds = array();
		if(!empty($categoryListByCourse)){
			if(in_array($course_id, array_keys($categoryListByCourse))) {
				$courseCategoryIds = $categoryListByCourse[$course_id];
			}
		}
		$dominantSubcatData = $this->national_course_lib->getDominantSubCategoryForCourse($course_id, $courseCategoryIds, $rankingDetailsforSpecialization[$course_id]['category_ids'][0]);
		$subCatIdForRanking = ($rankingSubCatId > 0 ? $rankingSubCatId : $dominantSubcatData['dominant']);
		
		$queryData = array('subcat_id'=>$subCatIdForRanking,
				   'course_id'=>$course_id,
				   'institute_id'=>$institute_id
				  );
		
		//load model to call getCourseRankingFromRankingPage()
		$this->load->model('coursemodel');
		$course_model_object = new CourseModel();
		$rankData = $course_model_object->getCourseRankingFromRankingPage($queryData);
		
		if($course->getMainLocation()->getCity()->getvirtualCityId()>0) {
		    $city_id = $course->getMainLocation()->getCity()->getvirtualCityId();
		} else {
		    $city_id = $course->getMainLocation()->getCity()->getId();
		}
		
		$city_name= $course->getMainLocation()->getCity()->getName();
		$urlIdentifier = $rankData['ranking_page_id']."-0-0-".$city_id."-0";
		$rankingPageRequest 	= $rankingURLManager->getRankingPageRequest($urlIdentifier); /*Read URL Identifier and create ranking page request object*/
		$link = $rankingURLManager->getCurrentPageURL($rankingPageRequest);
		$course_ranking = array('course_rank'=>$rankData['course_rank'],
						'ranking_page_text'=>$rankData['link_text'],
						'ranking_page_link'=>$link,
						'city_name'=>$city_name);
		return $course_ranking;
	}
	
	private function _validateListingURL($listingObject) {
		return;
		$userInputURL = $_SERVER['SCRIPT_URI'];
		$userInputURL  = trim($userInputURL);
		$userInputURL  = trim($userInputURL,"/");
		$queryString = substr(strrchr($_SERVER['REQUEST_URI'], "?"), 0);
		
		$listingId = $listingObject->getId();
                if(empty($listingObject) || empty($listingId)) {
                        return;
                }
		$actualURL     = $listingObject->getUrl();
		if(!empty($actualURL) && $actualURL != $userInputURL) {
			 redirect($actualURL.$queryString, 'location', 301);
		}
		
	}
	
	/*
	 *  load course objects with basic info and  associate them with institute object
	 * 
	 */
	
	private function getInstituteWithBasicCourseInfoObjs($institute_id,$courses) {
		
		$this->load->builder('ListingBuilder','listing');
		$listingbuilder = new ListingBuilder();
		$courseRepo = $listingbuilder->getCourseRepository();
		$courses = $courseRepo->getDataForMultipleCourses($courses,'basic_info|head_location');
		
		$institute = $this->instituteRepository->find($institute_id);
		$institute->setCourses($courses);
		
		return $institute;
		
	}
	
	/**
	 * SubMethod to render national institute page
	 * @author Aman Varshney <aman.varshney@shiksha.com>
	 * @date   2015-03-03
	 * @param  Object     $institute   Institute Object
	 * @param  [type]     $course      [description]
	 * @param  [type]     $displayData [description]
	 */
	public function nationalInstitutePage($institute, $course, $displayData) {
		//Tracking Code
		$displayData['beaconTrackData'] = array(
		    'pageIdentifier' => 'instituteListingPage',
		    'pageEntityId' => $institute->getId(),
		    'extraData' => array('url'=>get_full_url())
		);

		$displayData['questionTrackingPageKeyId'] = 232;

		if(function_exists ('logPerformanceData')) { logPerformanceData($this->startTime); } 
		$this->national_course_lib = $this->load->library('listing/NationalCourseLib');
		$this->cadiscussionmodel   = $this->load->model('CA/cadiscussionmodel');
		
		// set data for Institute Page		
		$this->_intInstitute($displayData,$institute,$course);
		$institute_id              = $displayData['instituteId'];
		
		
		$categorylistByCourse      = $displayData['categorylistByCourse'];
		
	    // start : change 4 : decreased memomory usages by 16 MB by passing course object insteadof passing id and loading object later on.
	    
	    $this->_fetchCourseAndAlumniReviewsData($displayData,$institute,$institute_id);
	    if(function_exists ('logPerformanceData')) { logPerformanceData($this->startTime); }
		
         // ends:change 4
       
		/* Course Offered Section STARTS */
		$city_param                                = $this->input->get('city', TRUE);
		$locality_param                            = $this->input->get('locality', TRUE);
		// start : change 5 : decreased memomory usages by 40 MB by passing institute object loaded with course object insteadof passing institute id and then loading all the relevent courses
		$courseBrowseSectionData                   = $this->national_course_lib->getCoursesForInstituteBrowseSection($institute_id, $categorylistByCourse, $city_param, $locality_param, $displayData['institute']);
		if(function_exists ('logPerformanceData')) { logPerformanceData($this->startTime); } 
		// end : change 5
		$displayData['course_browse_section_data'] = $courseBrowseSectionData;
		/* Course Offered Section ENDS */
		
		$this->_populateAdditionalData($displayData, $displayData['institute'], $course, $displayData['pageType']);
		// add default seo title & descripotion if not available
		$metaTitleDescription           = $this->national_course_lib->getDefaultMetaData(
																						'institute',
																						$displayData['institute'],
																						array(
																							'title'           =>$displayData['title'],
																							'metaDescription' =>$displayData['metaDescription'],
																							'currentLocation' =>$displayData['currentLocation']
																							)
																						);

		$displayData['title']           = $metaTitleDescription['title']		;
		$displayData['metaDescription'] = $metaTitleDescription['metaDescription'];	
		if((!empty($displayData['dominantSubcatData']['dominant'])) && ($this->input->get('resetpwd') != 1)) {
			$mmp_details = array();
			if(((strpos($this->input->server('HTTP_REFERER'), 'google') !== false) || ($this->input->get('showpopup') != '')) && ((empty($mmp_details))) && ($displayData['validateuser'] == 'false')) {
				$this->load->model('customizedmmp/customizemmp_model');
				$mmp_details = $this->customizemmp_model->getMMPFormbySubCategoryId($displayData['dominantSubcatData']['dominant'], 'newmmpinstitute', 'N');
			}
			$displayData['mmp_details'] = $mmp_details;
			$displayData['showpopup'] = $this->input->get('showpopup');
		}
		
		if(function_exists ('logPerformanceData')) { logPerformanceData($this->startTime); } 

		$this->_fetchMediaForLocation($institute,$displayData,true); //MultiLocation media For institute Page
		if(function_exists ('logPerformanceData')) { logPerformanceData($this->startTime); } 

		// Check and set the values is displayData array necessary for making the response eg. Institute_viewed
		$this->_checkAndSetDataForAutoResponseForInstitutePage($institute, $course, $displayData);
		// populate the courses list for download brochure

		// start : change 6 : decreased memomory usages by 10 MB by passing institute object loaded with course object insteadof passing course ids and then loading course objects
		$this->_populateCoursesListForBrochureData($displayData, $course);
		if(function_exists ('logPerformanceData')) { logPerformanceData($this->startTime); } 
		// end : change 6

		$courseArray = array();
		
		if(!empty($displayData['courses']) && !empty($displayData['freeCourses'])) {
			$courseArray = array_merge($displayData['courses'],$displayData['freeCourses']);
		}else if(!empty($displayData['courses'])) {
			$courseArray = $displayData['courses'];
		}else if(!empty($displayData['freeCourses'])) {
			$courseArray = $displayData['freeCourses'];
		}
		
		$courseIdArray = array();
		foreach($courseArray as $course) {
		    $courseIdArray[] = $course->getId();
		}
		
		//set page category as MBA or Engineering or others
		foreach($courseArray as $course) {
			$courseId = $course->getId();
			$courseCategory = $categorylistByCourse[$courseId];
			$coursePageCategoryIdentifier = $this->_identitifyCoursePageCategory($courseCategory,$course);
			if($coursePageCategoryIdentifier == 'MBA_PAGE') {
				break;
			}
		}
		$displayData['coursePageCategoryIdentifier'] = $coursePageCategoryIdentifier;
		
		$this->_populateCampusRepDetails($displayData, $courseIdArray, 'institute');
		if(function_exists ('logPerformanceData')) { logPerformanceData($this->startTime); } 
		$this->_populateCallToActionData($displayData, 'institute');
		if(function_exists ('logPerformanceData')) { logPerformanceData($this->startTime); } 
		$this->setJumpToNavigationData($displayData);
		//Variable to track which page the widget was clicked from
		$displayData['widgetClickedPage'] = 'College_Detail';		
		$displayData['widgetClickedPageRHS'] = 'College_Detail_Desktop_RHS';

		//AB testing
		$displayData['abTestVersion'] = $this->input->get('version', true);
		$displayData['isAbTestPage'] = 'yes';
		$requestUrl = $this->input->server('REQUEST_URI', true);
		$validAbTestUrl = strpos($requestUrl, 'getListingDetail');
		if($validAbTestUrl === false || 1) {
			$displayData['abTestVersion'] = '';
			$displayData['isAbTestPage'] = 'no';
		}

		// Add location data to be used for beacon tracking for institute listing page
		$currentLocationObject = $displayData['currentLocation'];
		$displayData['beaconTrackData']['extraData']['cityId'] = $currentLocationObject->getCity()->getId();
		$displayData['beaconTrackData']['extraData']['stateId'] = $currentLocationObject->getState()->getId();
		$displayData['beaconTrackData']['extraData']['countryId'] = $currentLocationObject->getCountry()->getId();
		// Till Here

		$this->load->view('listing/national/instituteOverview',$displayData);
	}
	
	private function _populateCampusRepDetails(& $displayData, $courseIdArray, $pageType)
	{
	    $this->load->model('CA/cadiscussionmodel');
	    $this->CADiscussionModel  = new CADiscussionModel();
	    $instituteId = $displayData['institute']->getId();
	    $getCaAnsCount = ($pageType == 'course') ? 'true' : 'false';
	    $campusRepData = $this->cadiscussionmodel->getCampusRepInfoForCourse($courseIdArray, $pageType ,$instituteId, 50, true,$getCaAnsCount);
	    $this->_prepareCampusRepData($displayData,$campusRepData,$instituteId,$pageType);
	}
	
	/***
	 * functionName : _prepareCampusRepData
	 * functionType : no return type
	 * param        : $displayData, $campusRepData(array),$instituteId
	 * desciption   : separate campus rep data for mba/be/b.tech widget on the institute page / course page 
	 * @author      : akhter
	 * @team        : UGC
	***/
	function _prepareCampusRepData(& $displayData, $campusRepData, $instituteId, $pageType)
	{
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
			    $displayData['repData'] = $repData['repData'];    
			}
	    }
	}
	
	public function _populateRelevantCampusRepData(& $displayData, $campusRepData)
	{
	    $numberOfCACourses = sizeof($campusRepData['caInfo']);
	    $displayData['numberOfCACourses'] = $numberOfCACourses;
	    
	    if($numberOfCACourses > 0) {
		$displayData['campusConnectAvailable'] = true;
	    }
	    
	    $campusConnectCourses = array();
	    $repData = array();
	    $numberOfReps = 0;
	    foreach(array_keys($campusRepData['caInfo']) as $courseId)
	    {
		$campusConnectCourses[] = $this->getCourseObject($courseId);
	    }
	    
	    $this->load->library('CA/CADiscussionHelper');
	    $caDiscussionHelper =  new CADiscussionHelper();
	    $repData        = $caDiscussionHelper->formatCADataForListing($campusRepData,3);
	    $numberOfReps = sizeof($repData);
	    $displayData['repData'] = $repData;
	    $displayData['numberOfReps'] = $numberOfReps;
	    $displayData['campusConnectCourses'] = $campusConnectCourses;
	    
	    
	}
	
	private function _populateCallToActionData(& $displayData, $pageType)
	{
	    $campusConnectAvailable = $displayData['campusConnectAvailable'];
	    $paid = $displayData['paid'];
	    
	    if($pageType == 'institute') {
			$subcategoriesOfinstitute = $displayData['institute']->instituteCourses;
			
			$isBrochureAvailable = FALSE;
			if(is_array($displayData['courseListForBrochure']) && count($displayData['courseListForBrochure'])) {
				$isBrochureAvailable = TRUE;
			}
			if($displayData['coursePageCategoryIdentifier'] == 'MBA_PAGE') {
				$displayData['cta_widget_list'] = $this->_loadCTAWidgetsForInstituteMBA($isBrochureAvailable, $campusConnectAvailable, $paid, $subcategoriesOfinstitute);
			} else {
				$displayData['cta_widget_list'] = $this->_loadCTAWidgetsForInstitute($isBrochureAvailable, $campusConnectAvailable, $paid, $subcategoriesOfinstitute);
			}
	    } else {
			$course = $displayData['course'];
			//change for memory optimization : pass course object instead of course id
			$course_reb_url = $this->national_course_lib->getCourseBrochure($course);
			$displayData['course_reb_url'] = $course_reb_url;
			
			if($displayData['coursePageCategoryIdentifier'] == 'MBA_PAGE') {
				$displayData['cta_widget_list'] = $this->_loadCallToActionWidgetsMBA($course_reb_url, $campusConnectAvailable, $course->isPaid());
			} else {
				$displayData['cta_widget_list'] = $this->_loadCallToActionWidgets($course_reb_url, $campusConnectAvailable, $course->isPaid());
				if($displayData['coursePageCategoryIdentifier'] == 'ENGINEERING_PAGE') {
				$displayData['cta_widget_list'][] = "inline_mentor_widget";
				}
			}
	    }
	}

	/**
	 * [nationalCoursePage description]
	 * @author   Aman Varshney <aman.varshney@shiksha.com>
	 * @date     2015-03-08
	 *
	 * @param CourseRepository    $course      The course object
	 * @param InstituteRepository $institute   The institute object
	 * @param Array               $displayData The data to be passed to the view
	 */
	public function nationalCoursePage($course, $institute, $displayData) {

		//get ranking details if any exists for that course
		
		if(function_exists ('logPerformanceData')) { logPerformanceData($this->startTime); }
		$course->course_ranking        = $this->getSourceWiseRankingData($course);
		
		if(function_exists ('logPerformanceData')) { logPerformanceData($this->startTime); }
		$this->national_course_lib = $this->load->library('listing/NationalCourseLib');
		$this->cadiscussionmodel   = $this->load->model('CA/cadiscussionmodel');
		$ExamPageLib               = $this->load->library('examPages/ExamPageLib');
		$institute_id              = $institute->getId();
		$course_id                 = $course->getId();
		
		$this->_intCourses($displayData,$institute,$course);
		
		$categorylistByCourse          = $displayData['categorylistByCourse'];		
		
		// $courseCategory             = $this->instituteRepository->getCategoryIdsOfListing($course_id,'course');		
		$courseCategory                = $categorylistByCourse[$course_id];
		
		//set page category as MBA or Engineering or others
		$coursePageCategoryIdentifier = $this->_identitifyCoursePageCategory($courseCategory,$course);
		$displayData['coursePageCategoryIdentifier'] = $coursePageCategoryIdentifier;
		
		// start : change 4 : decreased memomory usages by 20 MB by passing course object insteadof passing id and loading object later on.
		$displayData['courseComplete'] = $this->courseRepository->findCourseWithValueObjects($course_id,array('description'),$course);
		// end : change 4
		if(function_exists ('logPerformanceData')) { logPerformanceData($this->startTime); }
		
		foreach($displayData['courseComplete']->getDescriptionAttributes() as $attribute)
		{
			/* Adding XSS cleaning (Nikita) */
			$attrName = $this->security->xss_clean($attribute->getName());
			$attrValue = $this->security->xss_clean($attribute->getValue());
			
			$displayData['wikisData'][$attrName] = $attrValue;
		}
		
		// $displayData['instituteComplete'] = $this->instituteRepository->findInstituteWithValueObjects($institute_id,array('description','joinreason'));
		/**
		 * Make GET variables i.e. query string params available
		 */ 
		parse_str($this->input->server('QUERY_STRING'), $_GET);
		$inlineView                           = intval($this->input->get('inlineView'));
		$displayData['inlineView']            = $inlineView;
		
		$displayData['mainCategoryIdsOnPage'] = $this->instituteRepository->getMainCategoryIdsOfListing($course_id, 'course');

        // start : change 5 : makeBreadCrumb decreased memomory usages by 20 MB by passing course object insteadof passing id and loading object later on.
		$this->_populateAdditionalData($displayData, $displayData['institute'],$course,$displayData['pageType']);
		// end : change 5	
		if(function_exists ('logPerformanceData')) { logPerformanceData($this->startTime); }

		// add default seo title & descripotion if not available
		$metaTitleDescription           = $this->national_course_lib->getDefaultMetaData(
																						'course',
																						$displayData['course'],
																						array(
																							'title'                   =>$displayData['title'],
																							'metaDescription'               =>$displayData['metaDescription'],
																							'currentLocation'               =>$displayData['currentLocation']
																						     ),
																						$displayData['institute']
																					    );
		$displayData['title']           = $metaTitleDescription['title']		;
		$displayData['metaDescription'] = $metaTitleDescription['metaDescription'];


		$courseLocationInformation = $displayData['currentLocation'];
		$subCategoryId = $displayData['dominantSubcatData']['dominant'];
		$subCategoryId = $this->categoryRepository->find($subCategoryId);

		// Pass on tracking data for beacon
		$displayData['beaconTrackData'] = array(
			'pageIdentifier' => 'courseDetailsPage',
			'pageEntityId'   => $course->getId(),
			'extraData'      => array(
				'url'           => get_full_url(),
				'categoryId'    => $subCategoryId->getParentId(),
				'subCategoryId' => $subCategoryId->getId(),
				'cityId'        => $courseLocationInformation->getCity()->getId(),
				'stateId'       => $courseLocationInformation->getState()->getId(),
				'countryId'     => $courseLocationInformation->getCountry()->getId()
			)
		);

		//below line is used for conversion tracking purpose
		$displayData['questionTrackingPageKeyId'] = 223;

		//below line is used for compare tracking in MIS
		$displayData['comparetrackingPageKeyId'] = 521;

		$displayData['replyTrackingPageKeyId'] = 793;

		if($coursePageCategoryIdentifier == 'ENGINEERING_PAGE'){
			$this->_populateRankingPageLinks($displayData, $course);
		}else{
			$this->_populateRankingPageWidgetData($displayData, $course);	
		}
		if(!empty($displayData['googleRemarketingParams']) && !empty($displayData['courseSpecializationIds'])) {
			$displayData['googleRemarketingParams']['SpecializationID'] = $displayData['courseSpecializationIds'];
		}
		
		if(function_exists ('logPerformanceData')) { logPerformanceData($this->startTime); }

		$this->setNationalAccordionDateData($displayData);
		if(function_exists ('logPerformanceData')) { logPerformanceData($this->startTime); }

		// issue
		if($_REQUEST['download'] && $listingDocuments[$typeId]) {
			$displayData['documentDownload']              = TRUE;
			$displayData['redirectAfterDocumentDownload'] = $course->getURL();
		}
		
		$courseIdArray             = array();
		$courseIdArray[0]          = $course_id;
		$this->_populateCampusRepDetails($displayData, $courseIdArray, 'course');
		if(function_exists ('logPerformanceData')) { logPerformanceData($this->startTime); }
		// start : change 6 : change in CourseBrochure decreased memomory usages by 20 MB by passing course object insteadof passing id and loading object later on.
		$this->_populateCallToActionData($displayData, 'course');
		if(function_exists ('logPerformanceData')) { logPerformanceData($this->startTime); }
		// end : change 6
		
		$displayData['applyTrackingPageKeyId'] = 706;
		$displayData['applyRightTrackingPageKeyId'] = 707;
		$displayData['applyBottomTrackingPageKeyId'] = 708;
		if($course_id!='164788' && $course_id!='130026' && $course_id!='234231'){
		$displayData['OF_DETAILS'] = $this->national_course_lib->getOnlineFormURL($displayData['typeId'],$institute_id);
        		}
		else{
			$displayData['OF_DETAILS'] = array();
		}

		
		// For showing similar courses from same institute
		$otherCoursesForSameSubCategory                    = $this->national_course_lib->getOtherCoursesFromSameSubCategorySection($course, $displayData['categorylistByCourse'],$institute);
		if(function_exists ('logPerformanceData')) { logPerformanceData($this->startTime); }

		$displayData['other_courses_for_same_subcategory'] = $otherCoursesForSameSubCategory;		
		
		$this->setJumpToNavigationData($displayData);
		
		// Check and set the values is displayData array necessary for making the response eg. Viewed_listing
		$this->_checkAndSetDataForAutoResponse($institute, $course, $displayData);
		
		
		/*
		 * Third Recommendation Widget Data Computation
		 */

		//change 7 : need to optimize below call we can save upto 12 MB memory usages and upto 20 MB Peak Memory usages. 
		$URLWidgetData = modules::run('recommendation/recommendation_server/getRecommendedCategoryPageURLsForCourse', $course_id);
		if(function_exists ('logPerformanceData')) { logPerformanceData($this->startTime); }
		//end :change 7 

		$displayData['URLWidgetData'] = $URLWidgetData;
		
		/*
		 * OTP/OBD User Authentication
		 */
		$this->_toSetOTPANDODBVerification($displayData);

		// code to show overlay on mouse leave. Author : Ankit
		if(mt_rand(1, 10) <= 2 && false) {
			$displayData['showOverlayOnMouseLeave'] = (!isset($_SERVER['HTTP_REFERER']) || !(preg_match('/shiksha.com/', $_SERVER['HTTP_REFERER']))) ? 1 : 0;  
		}
        
        // get all live exam pages
        $liveExamPages = array();
        $categoriesWithExamNames   = $ExamPageLib->getCategoriesWithExamNames();
        foreach($categoriesWithExamNames as $examCategory){
        	foreach($examCategory as $key=>$examData){
        		$liveExamPages[] = $key;
        	}
        }
        $displayData['liveExamPages'] = $liveExamPages;
		           
		$this->getInstitutesOtherCoursesDetails($displayData['institute'], $displayData);
		
		if((!empty($displayData['dominantSubcatData']['dominant'])) && ($this->input->get('resetpwd') != 1)) {
			
			$mmp_details = array();
							
		    if($this->input->get('showpopup') != '')  {
				$this->load->model('customizedmmp/customizemmp_model');
				if($displayData['validateuser'] == 'false') {
					$mmp_details = $this->customizemmp_model->getMMPFormbySubCategoryId($displayData['dominantSubcatData']['dominant'], 'listing');
				}
			}
			
			if(((strpos($this->input->server('HTTP_REFERER'), 'google') !== false) || ($this->input->get('showpopup') != '')) && ((empty($mmp_details))) && ($displayData['validateuser'] == 'false')) {
			    $this->load->model('customizedmmp/customizemmp_model');
				$mmp_details = $this->customizemmp_model->getMMPFormbySubCategoryId($displayData['dominantSubcatData']['dominant'], 'newmmpcourse', 'N');
			}
			
			$displayData['mmp_details'] = $mmp_details;
			$displayData['showpopup'] = $this->input->get('showpopup');
		}
		if(function_exists ('logPerformanceData')) { logPerformanceData($this->startTime); }
		
		if(function_exists ('logPerformanceData')) { logPerformanceData($this->startTime); }

		/*
		 *	SHOWING DIFFERENT TEMPLATES FOR DIFFEENT SUBCATEGORY COURSES..
		 */
		
		
		
		$displayData['collegeOrInstituteRNR'] = 'institute';
		$categoryIds = $this->national_course_lib->getCourseInstituteCategoryId($course_id,'course');
		if(count(array_intersect($categoryIds, array("2", "3"))) != 0) {
			$displayData['collegeOrInstituteRNR'] = 'college';
		}

		global $subCatsForCollegeReviews;
		$dominantSubCat = $displayData['dominantSubcatData']['dominant'];
		if($subCatsForCollegeReviews[$dominantSubCat] == '1'){

			$this->load->helper('listing/listing');

	            $displayData['courseReviews'] = $this->national_course_lib->getCourseReviewsData(array($displayData['course']->getId()));
				$displayData['courseReviews'] = $this->national_course_lib->getCollegeReviewsByCriteria($displayData['courseReviews']);
				

	            $displayData['courseReviewDefaultSort'] = "graduationYear";
				$displayData['courseReviews'][$displayData['course']->getId()]['reviews'] = $this->national_course_lib->sortCollegeReviews($displayData['courseReviews'][$displayData['course']->getId()]['reviews'],$displayData['courseReviewDefaultSort']);
	            if(count($displayData['courseReviews'][$displayData['course']->getId()]['reviews']) > 0) {
	                $displayData['jumpMenuData']['COURSE_REVIEWS'] 	= 1;
	            }
		   
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


	
		// $displayData['onlineApplicationCoursesUrl'] = $this->national_course_lib->getOnlineFormAllCourses();
		// $displayData['coursesWithOnlineForm']       = $this->listingMbaLib->findCourseIdsWithOnlineForm();
		if($coursePageCategoryIdentifier == 'MBA_PAGE')
		{		   
			$displayData['courseShortlistedStatus']     = 0;
			$displayData['isMBATemplate']               = true;

			$displayData['revURL'] = SHIKSHA_HOME.'/management/resources/reviews/';
			$displayData['view'] = 'default';
			$displayData['share'] = array('facebook','twitter','linkedin','google');
			if(isset($displayData['validateuser'][0]['userid'])) 
			{
				$displayData['courseShortlistedStatus'] =  Modules::run('myShortlist/MyShortlist/checkIfCourseIsShortlistedForAUser',$displayData['validateuser'][0]['userid'],$displayData['typeId']);
			}

            
            if(function_exists ('logPerformanceData')) { logPerformanceData($this->startTime); }
			//Variable to track which page the widget was clicked from
			$displayData['widgetClickedPage'] = 'Course_Detail';
			$displayData['widgetClickedPageRHS'] = 'Course_Detail_Desktop_RHS';		
			$displayData['courseExamDetails'] = $course->getEligibilityExams();	
			$displayData['abroadExamData'] = $this->filterAbroadExamData($displayData['courseExamDetails'], $course->getId());
			/*//also on shiksha course slider widget
		    $displayData['alsoViewedClgOnCourse'] = $this->alsoOnShiksha($course->getId(), 'course', 'NL_coursePage');

		    //similar course slider widget
		    $institutesToBeExcluded = '';
			$subCategory = $course->getDominantSubCategory()->getId();
			if(!empty($_REQUEST['recommendedInstitutes']))
			{
				$institutesToBeExcluded = implode(',', $_REQUEST['recommendedInstitutes']);
				unset($_REQUEST['recommendedInstitutes']);
			}
			$displayData['otherClgOnCourse'] = $this->similarOnShiksha($course->getId(), intval($_REQUEST['city']), $institutesToBeExcluded, $subCategory, 'institute', 'NL_coursePage');
			
			//apply online slider widget
			$displayData['onlineFormWidgetOnCourse'] = $this->onlineFormWidget($course->getId(), $displayData['dominantSubcatData']['dominant'], 'html');*/
			
			$this->load->view('listing/national/mbaOverview',$displayData); //RnR
		}elseif($coursePageCategoryIdentifier == 'ENGINEERING_PAGE'){
			// $displayData['cutoffRanks']         = $this->national_course_lib->getCutoffRanksForCourse($course_id);
			$displayData['isCutoffRankPresent'] = false;//$displayData['cutoffRanks'] ? true : false;
			$displayData['courseExamDetails']   = $course->getEligibilityExams();
			$displayData['abroadExamData'] = $this->filterAbroadExamData($displayData['courseExamDetails'], $course->getId());
			$displayData['revURL'] = SHIKSHA_HOME.'/engineering/resources/reviews/';
			$displayData['view'] = 'default';
			$displayData['share'] = array('facebook','twitter','linkedin','google');


			if(function_exists ('logPerformanceData')) { logPerformanceData($this->startTime); }
		    /*//also on shiksha course slider widget
		    $displayData['alsoViewedClgOnCourse'] = $this->alsoOnShiksha($course->getId(), 'course', 'NL_coursePage');

		    //similar course slider widget
		    $institutesToBeExcluded = '';
			$subCategory = $course->getDominantSubCategory()->getId();
			if(!empty($_REQUEST['recommendedInstitutes']))
			{
				$institutesToBeExcluded = implode(',', $_REQUEST['recommendedInstitutes']);
				unset($_REQUEST['recommendedInstitutes']);
			}
			$displayData['otherClgOnCourse'] = $this->similarOnShiksha($course->getId(), intval($_REQUEST['city']), $institutesToBeExcluded, $subCategory, 'institute', 'NL_coursePage');
			
			//apply online slider widget
			$displayData['onlineFormWidgetOnCourse'] = $this->onlineFormWidget($course->getId(), $displayData['dominantSubcatData']['dominant'], 'html');*/

		    $this->load->view('listing/national/defaultCourseOverview',$displayData); //Non RnR
		}
		else
		{
			// $displayData['cutoffRanks']         = $this->national_course_lib->getCutoffRanksForCourse($course_id);
			/*//also on shiksha course slider widget
		    $displayData['alsoViewedClgOnCourse'] = $this->alsoOnShiksha($course->getId(), 'course', 'NL_coursePage');

		    //similar course slider widget
		    $institutesToBeExcluded = '';
			$subCategory = $course->getDominantSubCategory()->getId();
			if(!empty($_REQUEST['recommendedInstitutes']))
			{
				$institutesToBeExcluded = implode(',', $_REQUEST['recommendedInstitutes']);
				unset($_REQUEST['recommendedInstitutes']);
			}
			$displayData['otherClgOnCourse'] = $this->similarOnShiksha($course->getId(), intval($_REQUEST['city']), $institutesToBeExcluded, $subCategory, 'institute', 'NL_coursePage');
			
			//apply online slider widget
			$displayData['onlineFormWidgetOnCourse'] = $this->onlineFormWidget($course->getId(), $displayData['dominantSubcatData']['dominant'], 'html');*/
			$displayData['isCutoffRankPresent'] = false;//$displayData['cutoffRanks'] ? true : false;
			$displayData['courseExamDetails']   = $course->getEligibilityExams();
			$displayData['abroadExamData'] = $this->filterAbroadExamData($displayData['courseExamDetails'], $course->getId());
			if(function_exists ('logPerformanceData')) { logPerformanceData($this->startTime); }
		    $this->load->view('listing/national/defaultCourseOverview',$displayData); //Non RnR
		}
         
        if(function_exists ('logPerformanceData')) { logPerformanceData($this->startTime); }        
                //$this->_getUrlDataForMouseLeaveOverlay($displayData);
                //$this->load->view('listing/national/overlayOnMouseLeave',$displayData);
	}
	
	/*
	 * Purpose : Method to get other courses of course's institute
	*/
	function getInstitutesOtherCoursesDetails($institute,& $displayData)
	{
		$displayData['paid_courses'] = array();
		// seperate paid and free courses of the institute
		if($courses = $institute->getCourses()){
			foreach($courses as $course){
				if($course->isPaid()){
					$displayData['paid_courses'][] = $course;
				} else {
				    $displayData['free_courses'][] = $course;
				}
			}
		}
		
		// populate the courses list for download brochure
		$this->_populateCoursesListForBrochureData($displayData, $displayData['course'], 1);
		
		// set flag to show/hide institutes course picklist in reponse form of course detail page
		$this->_setFlagForCoursePageBrochureList($displayData);
	}
	
	// Function to call user on given number 
	function callBackOnUserNumber($institute) {
	    
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
			    //echo $displayData['phoneCallbackData'];
			}
		    }
		}
	}
	
	function getInstituteRepIfExists($course,$institute)
	{
	    $campusConnectData = array();
		    $image = $institute->getMainHeaderImage()->getThumbURL();
		    
		    $locations = $course->getLocations();
		    $location = $locations[$course->getMainLocation()->getLocationId()];
		    $getInstituteLocation = false;
		    if(!$location){
			    $getInstituteLocation = true;
		    }
		    else{
			    $contactDetail = $location->getContactDetail();
			    if($contactDetail->getContactPerson()){
				    $displayname = $contactDetail->getContactPerson();
			    }
			    else{
				    $getInstituteLocation = true;
			    }
		    }
		    if($getInstituteLocation){	//Check for Institute contact person
				    $locations = $institute->getLocations();
				    $location = $locations[$course->getMainLocation()->getLocationId()];
				    $contactDetail = $location->getContactDetail();
				    if($contactDetail->getContactPerson()){
					    $displayname = $contactDetail->getContactPerson();
				    }
				    else{
					    $displayname = $institute->getName();
				    }
		    }
    
		    $badge = 'Official';				
		    $displayCourse = false;
		    if($image==''){
			    $image = '/public/images/avatar.gif';
		    }
		    $campusConnectData[0]=array('badge'=>$badge,
							  'imageURL'=>$image,
							  'displayName'=>$displayname);
		    return $campusConnectData;
	}

        function seeAllBranches($listing,$locations,$overlay, $sourcePage = NULL){
		$displayData['name']  = html_escape($listing->getName());
		$displayData['url']  = $listing->getURL();
		$displayData['listing']  = $listing;
		$displayData['overlay'] = $overlay;
		$displayData['instituteCourses']= $listing->instituteCourses;
		$locations = $locations ? $locations : $listing->getLocations();
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
                
              
		
		if($sourcePage == "search"){
			return $this->load->view('search/search_course_branches_layer',$displayData,true);
		} else {
			return $this->load->view('listing/national/widgets/seeAllBranches',$displayData,true);
		}
	}
 

	public function reviews($instituteId) {
		$this->load->library('alumni/AlumniReviewsLibrary');
		$alumniReviewsLib = new AlumniReviewsLibrary();
		$reviews = $alumniReviewsLib->getAlumniRatingsForInstitute($instituteId);
		_p($reviews);
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
	
	public function getTotalBrochureDownloads($courseId, $returnedFlag=false) {
	    $coursemodel = $this->load->model('listing/coursemodel');
	    $totalDownloads = $coursemodel->getTotalBrochureDownloads($courseId);
	    if($returnedFlag) {
		return $totalDownloads[0]['totalDownloads'];
	    } else {
		echo $totalDownloads[0]['totalDownloads'];
	    }
	}
	
	private function _loadCTAWidgetsForInstitute($isBrochureAvailable, $campus_connect_available, $paid, $subcategoriesOfinstitute) {	    

		$widget_name_array = array();
		
		if($paid)
		{
			$widget_name_array[] = "download_e_brochure";
			
			if($campus_connect_available) {			    
				$widget_name_array[] = "campus_connect";			
			} else {
				$widget_name_array[] = "ask_question_widget";
			}
		}
		else
		{	    
			if($campus_connect_available && !$isBrochureAvailable) {			     
				$widget_name_array[] = "campus_connect";
				$widget_name_array[] = "find_best_institute";
			} else if(!$campus_connect_available && $isBrochureAvailable) {			    
				$widget_name_array[] = "download_e_brochure";
			} else if(($campus_connect_available && $isBrochureAvailable)) {		    
				$widget_name_array[] = "campus_connect";
				$widget_name_array[] = "download_e_brochure";
			} else {			    
				$widget_name_array[] = "find_best_institute";
			}
		}
		
		if(count($subcategoriesOfinstitute) == 1) {
		    $widget_name_array[] = "show_institute_recommendations";
		}
		
		return $widget_name_array;
		
	}
	
	private function _loadCTAWidgetsForInstituteMBA($isBrochureAvailable, $campus_connect_available, $paid, $subcategoriesOfinstitute) {	    
		$widget_name_array = array();
		if($paid)
		{
			$widget_name_array[] = "download_e_brochure";
			
			if($campus_connect_available) {			    
				$widget_name_array[] = "campus_connect";				
			}
		} else {	    
			if($campus_connect_available && !$isBrochureAvailable) {			     
				$widget_name_array[] = "campus_connect";
				$widget_name_array[] = "find_best_institute";
			} else if(!$campus_connect_available && $isBrochureAvailable) {			    
				$widget_name_array[] = "download_e_brochure";
			} else if(($campus_connect_available && $isBrochureAvailable)) {		    
				$widget_name_array[] = "campus_connect";
				$widget_name_array[] = "download_e_brochure";
			} else {			    
				$widget_name_array[] = "find_best_institute";
			}
		}
		
		if(count($subcategoriesOfinstitute) == 1) {
		    $widget_name_array[] = "show_institute_recommendations";
		}
		
		if(!$campus_connect_available) {			    
			$widget_name_array[] = "ask_question_widget";
		}
		
		return $widget_name_array;
	}
	
	private function _loadCallToActionWidgets($course_reb_url,$campus_connect_available,$paid) {
		
		//var_dump($paid);var_dump($course_reb_url);var_dump($campus_connect_available);

		$widget_name_array = array();
		
		if($paid) {
				
			if(($campus_connect_available && $course_reb_url) || ($campus_connect_available && !$course_reb_url)) {

				$widget_name_array[] = "campus_connect";
				$widget_name_array[] = "download_e_brochure";

			} else if(!$campus_connect_available && $course_reb_url) {

				$widget_name_array[] = "ask_question_widget";
				$widget_name_array[] = "download_e_brochure";

			} else {
				 $widget_name_array[] = "ask_question_widget";		
				$widget_name_array[] = "download_e_brochure";
			}
				
		} else {
				
			if($campus_connect_available && $course_reb_url) {

				$widget_name_array[] = "campus_connect";
				$widget_name_array[] = "download_e_brochure";

			} else if($campus_connect_available && !$course_reb_url) {

				$widget_name_array[] = "campus_connect";
				$widget_name_array[] = "find_best_institute";
					
			} else if(!$campus_connect_available && $course_reb_url) {

				$widget_name_array[] = "download_e_brochure";

			} else {
				$widget_name_array[] = 	"find_best_institute";
			}
		}		
		
		return $widget_name_array;
		
	}
	
	private function _loadCallToActionWidgetsMBA($course_reb_url,$campus_connect_available,$paid) {
		$widget_name_array = array();
		if($paid) {
			if(($campus_connect_available && $course_reb_url) || ($campus_connect_available && !$course_reb_url)) {
				$widget_name_array[] = "campus_connect";
				$widget_name_array[] = "download_e_brochure";
			} else if(!$campus_connect_available && $course_reb_url) {
				$widget_name_array[] = "download_e_brochure";
			} else {				
				$widget_name_array[] = "download_e_brochure";
			}
		} else {
			if($campus_connect_available && $course_reb_url) {
				$widget_name_array[] = "campus_connect";
				$widget_name_array[] = "download_e_brochure";
			} else if($campus_connect_available && !$course_reb_url) {
				$widget_name_array[] = "campus_connect";
				$widget_name_array[] = "find_best_institute";
			} else if(!$campus_connect_available && $course_reb_url) {
				$widget_name_array[] = "download_e_brochure";
			} else {
				$widget_name_array[] = 	"find_best_institute";
			}
		}
		
		//ask question widget will come on all paid and free listings except where campus connect is available or on free listing where no brochure is present
		if(!$campus_connect_available) {
			if($paid || $course_reb_url) { //will be false only when course is free & has no brochure
				$widget_name_array[] = "ask_question_widget";
			}
		}
		
		return $widget_name_array;
	}
	
	public function downloadCustomDocument($listingTypeId)
	{
		require FCPATH.'globalconfig/listingDocumentDownload.php';
		
		if(!$listingDocuments[$listingTypeId]) {
			return FALSE;
		}
		
		$this->load->helper('download');
		$data = file_get_contents($listingDocuments[$listingTypeId]['url']); // Read the file's contents
		$name = $listingDocuments[$listingTypeId]['name'];

		force_download($name, $data);
	}
	
	function getInstituteObject($instituteId) {	    
	    $this->load->builder('ListingBuilder','listing');
	    $listingBuilder = new ListingBuilder;
	    $this->instituteRepository = $listingBuilder->getInstituteRepository();

	    $institute = $this->instituteRepository->find($instituteId);
	    return $institute;
	}
	
	function getCourseObject($courseId) {
	    $this->load->builder('ListingBuilder','listing');
	    $listingBuilder = new ListingBuilder;
	    $this->courseRepository = $listingBuilder->getCourseRepository();
	    $course = $this->courseRepository->find($courseId);
	    return $course;
	}
	
	function redirectToListingPage($listingId, $listingType) {	

		/**
		redirecting to new institute Detail pages
		*/

		if(empty($listingId) || !is_numeric($listingId)){
            show_404();
            exit(0);
        }
		if($listingType == 'course'){
			$this->load->builder("nationalCourse/CourseBuilder");
			$courseBuilder = new CourseBuilder();
			$this->load->library('nationalCourse/CourseDetailLib');
        	$this->courseDetailLib = new CourseDetailLib; 
			$this->courseRepo = $courseBuilder->getCourseRepository();
			$courseObj = $this->courseRepo->find($listingId);
			$this->courseDetailLib->checkForCommonRedirections($courseObj, $listingId);
		}



		if(!in_array(strtolower(trim($listingType)), array('institute','course'))){
			show_404();
		}
		if(empty($listingId) || !is_numeric($listingId)){
			show_404();	
		}
		global $restrictedInstituteIds, $restrictedCourseIds;
		if(DISABLE_RESTRICTED_LISTING) {
			if(($listingType == 'institute' && in_array($listingId, $restrictedInstituteIds)) || in_array($listingId, $restrictedCourseIds)) {
				show_404();
				exit;
			}
		}

		//Redirect old listing to new listing page
		if(!empty($listingId) && $listingType == 'institute'){
			$this->load->builder("nationalInstitute/InstituteBuilder");
	        $instituteBuilder = new InstituteBuilder();
	        $instituteRepo = $instituteBuilder->getInstituteRepository();
	        $instituteObj = $instituteRepo->find($listingId,array('basic'));
	        $seoUrl = $instituteObj->getURL();
	        if(!empty($seoUrl)){
	        	header("Location: $seoUrl",TRUE,301);
                exit;
	        }
	        else{
	        	show_404();
	        }
	        
		}

		/*redirecting to new detail page end*/
		if(!is_numeric($listingId) || $listingId === "") {
		    show_404();
		} else {
		    if($listingType == "course") {
			$listing = $this->getCourseObject($listingId);
		    } else {
			$listing = $this->getInstituteObject($listingId);
		    }
		    
		    if($listing->getMainLocation()->getCountry()->getId() != 2) {
			return ;
		    }
		    
		    if($listing->getId() != "") {
			$queryString = '';
			if(strlen($_SERVER['QUERY_STRING'])) {
			    $queryString = '?'.$_SERVER['QUERY_STRING'];
			}
			redirect($listing->getUrl().$queryString, 'location', 301);
		    } else {
			show_404();
		    }
		}
	}
	
	function listingAnATab($institute_id)
	{	    
		/*
		 *	301 Redirect to Institute Url for Listings Revamp Jan 2014.
		 */		
		$this->redirectToListingPage($institute_id, 'institute');
		
		$this->_init($displayData, $institute_id);
		$courses = $this->_getCourses($institute_id);
		$institute = $this->_findInstituteDetails($institute_id,$courses);
		$displayData['institute'] = $institute;
		$displayData['categories'] = $this->instituteRepository->getCategoryIdsOfListing($institute_id,'institute');
		$displayData['tab'] = 'ana';
		$this->_populateAdditionalData($displayData, $displayData['institute']);
		$this->load->view('listing/listingPage/anaTab',$displayData);
	}
	
	function listingCampusRepTab($param)
	{
		$paramArray = explode('-',$param);
		$course_id  = $paramArray[0];
		$page_no = $paramArray[1];
		
		/*
		 *	301 Redirect to course Url for Listings Revamp Jan 2014.
		 */
		$this->redirectToListingPage($course_id, 'course');
		
		$this->_init($displayData, $course_id, 'course');
		$institute_id = $this->ListingClientObj->getInstituteIdForCourseId(1,$course_id);
		if($institute_id==0){
			show_404();exit();
		}
		$courses = $this->_getCourses($institute_id);
		$institute = $this->_findInstituteDetails($institute_id,$courses);
		$course = $this->courseRepository->find($course_id);

		$displayData['institute'] = $institute;
		$displayData['course'] = $course;
		$displayData['categories'] = $this->instituteRepository->getCategoryIdsOfListing($institute_id,'institute');
		$displayData['tab'] = 'campusRep';
		$displayData["course_id"] = $course_id;
		$displayData["page_no"] = $page_no;

		$this->_populateAdditionalData($displayData, $displayData['institute'], $displayData['course'], 'course');
	 	$this->_populateRankingPageWidgetData($displayData, $course);
		$this->load->view('listing/listingPage/campusRepTab',$displayData);
	}
	
	function listingCoursesTab($institute_id)
	{
		/*
		 *	301 Redirect to All Courses Page (MAB-2313)
		 */
		
		if(empty($institute_id) || !is_numeric($institute_id)){
            show_404();
            exit(0);
        }
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

		/*
		 *	301 Redirect to Institute Url for Listings Revamp Jan 2014.
		 */
		$this->redirectToListingPage($institute_id, 'institute');
		
		$this->_init($displayData, $institute_id);
		$courses = $this->_getCourses($institute_id);
		$institute = $this->_findInstituteDetails($institute_id,$courses);
		$displayData['institute'] = $institute;
		$displayData['tab'] = 'courses';
		$this->_populateAdditionalData($displayData, $displayData['institute']);
		$this->load->view('listing/listingPage/coursesTab',$displayData);
	}
	
	function listingMediaTab($institute_id)
	{
		/*
		 *	301 Redirect to Institute Url for Listings Revamp Jan 2014.
		 */
	        $this->redirectToListingPage($institute_id, 'institute');
		
		$this->_init($displayData, $institute_id);
		$courses = $this->_getCourses($institute_id);
		$institute = $this->_findInstituteDetails($institute_id,$courses);
		$displayData['institute'] = $institute;
		$this->_fetchMediaForLocation($institute,$displayData);
		$displayData['tab'] = 'media';
		$this->_populateAdditionalData($displayData, $displayData['institute']);
		$this->load->view('listing/listingPage/mediaTab',$displayData);
	}
	
    function nationalPagePhotoVideo($institute_id,$mediaDataReqForNationalPage =false, $courseId)
	{
		// ajax url of old listing page and were crawled and should now throw 404
		show_404();
            	exit(0);

		/* Adding XSS cleaning (Nikita) */
		$institute_id = $this->security->xss_clean($institute_id);
		$mediaDataReqForNationalPage = $this->security->xss_clean($mediaDataReqForNationalPage);
		$courseId = $this->security->xss_clean($courseId);
		
		$this->_init($displayData, $institute_id);
		
		//start : change 8 : as media data is present in Institue object so no need to load course objects : Code removed for course loading. 
	    $institute = $this->instituteRepository->find($institute_id);
		$displayData['institute'] = $institute;
		//end : change 8
		
		//change for institute to college story
		$displayData['collegeOrInstituteRNR'] = 'institute';
	    $national_course_lib = $this->load->library('listing/NationalCourseLib');
	    $categoryIds = $national_course_lib->getCourseInstituteCategoryId($courseId,'course');
		if(count(array_intersect($categoryIds, array("2", "3"))) != 0) { 
			$displayData['collegeOrInstituteRNR'] = 'college';
		}
		//institute to college story ends here

		$this->_fetchMediaForLocation($institute,$displayData);
		if($mediaDataReqForNationalPage)
		{
		$this->load->view ( 'listing/national/photoAndVideoWidget',$displayData);
			
		}
	}

	private function _fetchMediaForLocation($institute,& $displayData,$customMedia = False){
		$mediaData['photos'] = $institute->getPhotos();
		$mediaData['videos'] = $institute->getVideos();
		$locations = $institute->getLocations();
		$cityLocalityArray = array();
		foreach($mediaData as $key=>$media){
			foreach($media as $m){
                if($m->getInstituteLocationId()){
                    if(!$locations[$m->getInstituteLocationId()]){
                       continue; 
                    }
                    $location = $locations[$m->getInstituteLocationId()];
                    
                    $localityId = $location->getLocality()?$location->getLocality()->getId():"0";
                    $cityLocalityArray[$location->getCity()->getId()]['cityName'] = $location->getCity()->getName();
                    if(!$localityId){
                        $localityName = "All";
                    }else{
                        $localityName = $location->getLocality()->getName();
                    }
                    $cityLocalityArray[$location->getCity()->getId()]['locality'][$localityId]['name'] = $localityName;
                    $cityLocalityArray[$location->getCity()->getId()]['locality'][$localityId][$key][] = $m;
                }
			}
		}
		
		if(($customMedia || $this->input->get('custommedia') == 1) && $this->input->get('city')){
			$mediaData['photos'] = array();
			$mediaData['videos'] = array();
			if($this->input->get('locality') && $this->input->get('locality') != "All"){
				$mediaData['photos'] = $cityLocalityArray[$this->input->get('city')]['locality'][$this->input->get('locality')]['photos'];
				$mediaData['videos'] = $cityLocalityArray[$this->input->get('city')]['locality'][$this->input->get('locality')]['videos'];
			}else{
				foreach($cityLocalityArray[$this->input->get('city')]['locality'] as $locality){
					$mediaData['photos'] = array_merge($mediaData['photos'],$locality['photos']);
					$mediaData['videos'] = array_merge($mediaData['videos'],$locality['videos']);
				}
			}
		}
		$displayData['cityLocalityArray'] = $cityLocalityArray;
		$displayData['mediaData'] = $mediaData;
		$tempCityLocalityArray = reset($cityLocalityArray);
		if(count($cityLocalityArray) > 1 || count($tempCityLocalityArray['locality']) > 1){
			$showDropDowns = true;
		}
		$displayData['showDropDowns'] = $showDropDowns;
	}
	
	function listingAlumniTab($institute_id){
		/*
		 *	301 Redirect to Institute Url for Listings Revamp Jan 2014.
		 */
		$this->redirectToListingPage($institute_id, 'institute');
	    
		$this->_init($displayData, $institute_id);
		$courses = $this->_getCourses($institute_id);
		$institute = $this->_findInstituteDetails($institute_id,$courses);
		$displayData['institute'] = $institute;
		$displayData['alumnisReviews'] = $this->instituteRepository->findAlumanisReviewsOnInstitute($institute_id);
		$displayData['tab'] = 'alumni';
		$this->_populateAdditionalData($displayData, $displayData['institute']);
		$this->load->view('listing/listingPage/alumniTab',$displayData);
	}
	
	function loadFormsOnListingPage($typeId, $type = 'institute'){return;
		ini_set('memory_limit','700M');
		/* Adding XSS cleaning (Nikita) */
		$typeId = $this->security->xss_clean($typeId);
		$type = $this->security->xss_clean($type);

	        if(!in_array($type,array('course','institute'))){
                        return;
                }
			
		$this->_init($displayData, $typeId,$type);
		if($type == 'course'){
			$course_id = $typeId;
			$course = $this->courseRepository->find($course_id);
			$institute_id = $course->getInstId();
			$displayData['course'] = $course;
		}else{
			$institute_id = $typeId;
		}

		// return if no institute is found
		if(empty($institute_id))
		    return;
        
		$courses = $this->_getCourses($institute_id);
        //change for memory optimization		
		$institute = $this->getInstituteWithBasicCourseInfoObjs($institute_id,$courses);

		//$institute = $this->_findInstituteDetails($institute_id,$courses);
		$displayData['institute'] = $institute;
		//$displayData['course'] = $course;
		$this->_checkSaveInfo($displayData);
		$this->_populateCurrentLocation($displayData, $displayData['institute'],$course,$type);
		
		// for cc layer
		$this->load->model('CA/cadiscussionmodel');
		$this->CADiscussionModel  = new CADiscussionModel();
		$campusRepData = $this->cadiscussionmodel->getCampusRepInfoForCourse($courses, 'institute' ,$institute_id);
		$this->_populateRelevantCampusRepData($displayData, $campusRepData);
		
		$displayData['listingType'] = $type;
		$displayData['listingId'] = $typeId;
		
		if($this->_isInstitutePaid($institute, $displayData)){
			
			$this->_loadResponseWidgets($displayData);
		}
		else{
			$this->_loadRegistationWidgets($displayData);
		}
	}

	private function _isInstitutePaid($institute,& $displayData){
		$displayData['courses'] = array();
		if($courses = $institute->getCourses()){
			foreach($courses as $course){
				if($course->isPaid()){
					$displayData['courses'][] = $course;
				} else {
				    $displayData['freeCourses'][] = $course;
				}
			}
		}
		if(count($displayData['courses'])){
			$displayData['paid'] = true;
		}else{
			$displayData['paid'] = false;
		}
		return $displayData['paid'];
	}
	/*
	 * "isCoursePaid()" accepts courseid 
	 * returns array of true/false values indexed at courseid passed
	 */
	public function isCoursePaid($courseIdCollection)
	{
	    if(empty($courseIdCollection))
	    {
		//echo "fail:courseid empty";
		return false;
	    }
	    //load a listing builder
	    $this->load->builder('ListingBuilder','listing');
	    $listingBuilder = new ListingBuilder;
	    //create course objects
	    $course_obj = $listingBuilder->getCourseRepository()->find($courseIdCollection);
	    $result = $course_obj->isPaid();
	    echo json_encode($result);
	    
	}
	
	private function _loadResponsWidgetsForContactDetails($displayData, & $finalData)
	{
		$displayData['updated'] = "";
		$institute = $displayData['institute'];
		$pageType = $displayData['pageType'];
		$course = $displayData['course'];

		//$lastUpdatedDate = $institute->getLastUpdatedDate();
		$lastUpdatedDate = ($displayData['pageType'] == 'institute') ? $institute->getLastUpdatedDate() : $course->getLastUpdatedDate();
		$shikshaDataLastUpdated = date("Y-m-d H:i:s",strtotime(SHIKSHA_DATA_LAST_UPDATED));
		if ($lastUpdatedDate > $shikshaDataLastUpdated)
		{
		    $displayData['updated'] = "true";
		}
		else
			$displayData['updated'] = "false";
		
		  // start : change 7 : instead of loading course objts again , use already loaded objects in display data.
			// get all courses - free and paid
			$courses = $institute->getCourses();
			$courses = $this->_makeKeysAsCourseId($courses);
			// end : change 7
			
			//$courses = $this->courseRepository->findMultiple($course_ids);
			
			$displayData['courses'] = $courses;

                         $courseIds = array_keys($courses);

                         $listingebrochuregenerator = $this->load->library('ListingEbrochureGenerator');
          
						$localityArray = $listingebrochuregenerator->getMultilocationsForInstitute($courseIds); 
			
						$displayData['localityArray'] = $localityArray;
              
                        $dominantDesiredCourseData = $this->national_course_lib->getDominantDesiredCourseForClientCourses($courseIds);
                        foreach ($dominantDesiredCourseData as $key => $value) {
                            $dominantDesiredCourseData[$key]['name'] = $courses[$key]->getName();
                        }
                        //$displayData['defaultCourseId']                         = ($course->getId() > 0)?$course->getId():$courseIds[0];
                        $displayData['instituteCourses']                        = $dominantDesiredCourseData;
                        $displayData['instituteId']                             = $course->getInstId();
                        $displayData['instituteName']                           = $course->getInstituteName();
                        if($course->getId()){
                            $displayData['courseIdSelected']                = $course->getId();
                            $displayData['defaultCourse']                   = $dominantDesiredCourseData[$course->getId()]['desiredCourse'];
                            $displayData['defaultCategory']                 = $dominantDesiredCourseData[$course->getId()]['categoryId'];
                            $displayData['desiredCourse']                   = $dominantDesiredCourseData[$course->getId()]['desiredCourse'];
                            $displayData['fieldOfInterest']                 = $dominantDesiredCourseData[$course->getId()]['categoryId'];
                        }
		
		
		if($displayData['studyAbroad'] == 1)
		{
			$displayData['updated'] = "false";
		}
		
		if($pageType == 'course'){
			$currentCountry = $course->getMainLocation()->getCountry()->getId();
		}
		else
			$currentCountry = $institute->getMainLocation()->getCountry()->getId();
		
		if($currentCountry != 2)
		    $finalData['topLinks'] = ($this->load->view('listing/listingPage/widgets/topLinks',$displayData,true));
		
		if($displayData['updated'] == "true")
		{
			$finalData['responseFormNew'] 		= ($this->load->view('listing/national/responseFormContactDetailsTop',$displayData,true));
			$finalData['responseFormBottomNew'] 	= ($this->load->view('listing/national/responseFormContactDetailsBottom',$displayData,true));
		}
	}

	private function _makeKeysAsCourseId($courses) {
		$courseObjts = array();
		foreach ($courses as $courseObj) {
			$courseObjts[$courseObj->getId()] = $courseObj;
		}
		return $courseObjts;
	}
	
	private function _loadRegistationWidgets($displayData){
		if($displayData['currentLocation']->getCountry()->getId() > 2){
			$displayData['form'] = 'studyAbroad';
			$displayData['studyAbroad'] = 1;
		}else{
			$displayData['studyAbroad'] = 0;
		}
                $course = $displayData['course'];
                if(!$course){
                    $course = $displayData['institute']->getFlagshipCourse();
                    $course = $this->courseRepository->find($course->getId()); //loading full object of flagship course
            		$displayData['course'] = $course;
                }
		$finalData = array();
		$this->_loadResponsWidgetsForContactDetails($displayData, $finalData);
		$finalData['rightWidget']  = ($this->load->view('listing/listingPage/widgets/registationWidgetRight',$displayData,true));
		$finalData['bottomWidget'] = ($this->load->view('listing/listingPage/widgets/registationWidgetBottom',$displayData,true));
		$finalData['campusRepInstt'] = ($this->load->view('listing/national/campusRepInsttLayer',$displayData,true));
		//$finalData['topLinks'] = ($this->load->view('listing/listingPage/widgets/topLinks',$displayData,true));
		echo json_encode($finalData);
	}
	
	private function _loadResponseWidgets($displayData){
		if($displayData['currentLocation']->getCountry()->getId() > 2){
			$displayData['studyAbroad'] = 1;
		}else{
			$displayData['studyAbroad'] = 0;
		}
		$leads = $this->ListingClientObj->getCountForResponseForm($displayData['institute']->getId());

		$validateuser = $displayData['validateuser'];
        $course = $displayData['course'];
        if(!$course){
            $course = $displayData['institute']->getFlagshipCourse();
            $course = $this->courseRepository->find($course->getId()); //loading full object of flagship course
            $displayData['course'] = $course;
        }

	if(($validateuser != "false") && $displayData['paid'] && !(in_array($validateuser[0]['usergroup'],array("enterprise","cms","experts","sums"))) && $course->isPaid() && (!($this->QnAModel->checkIfAnAExpert($dbHandle,$validateuser[0]['userid']))) && ($validateuser[0]['mobile'] != "")){
	    $displayData['makeAutoResponse'] = modules::run('registration/Forms/isValidResponseUser', $displayData['course']->getId(), null, true);
	}

		$displayData['responseCount'] = $leads;
		$finalData = array();
		$this->_loadResponsWidgetsForContactDetails($displayData, $finalData);
		$finalData['rightWidget'] = ($this->load->view('listing/listingPage/widgets/responseWidgetRight',$displayData,true));
		$finalData['bottomWidget'] = ($this->load->view('listing/listingPage/widgets/responseWidgetBottom',$displayData,true));
		$finalData['campusRepInstt'] = ($this->load->view('listing/national/campusRepInsttLayer',$displayData,true));		
		//$finalData['topLinks'] = ($this->load->view('listing/listingPage/widgets/topLinks',$displayData,true));
		
		if($displayData['pageType'] == "course"){
			$this->load->library('StudentDashboardClient');
			$this->load->library('dashboardconfig');
            $this->load->library('Online_form_client');
            $onlineClient = new Online_form_client();
            $displayOnlineButton = $onlineClient->checkIfListingHasOnlineForm('course',$displayData['course']->getId());
            if(is_array($displayOnlineButton) && isset($displayOnlineButton[0]['courseId'])){
			    //added by akhter
			    //get dashboard config for online form
			    $this->national_course_lib 	= $this->load->library('listing/NationalCourseLib');
 			    $displayData['online_form_institute_seo_url'] = $this->national_course_lib->getOnlineFormAllCourses();
			    $department = $displayOnlineButton[0]['departmentName'];
			    $displayData['institute_features'] = json_decode($this->studentdashboardclient->returnOfInstitutesOfferandOtherDetails(array($displayOnlineButton[0]['instituteId']),$department),true);
                $displayData['instituteId'] = $displayOnlineButton[0]['instituteId'];
                $displayData['onlineCourseId'] = $displayOnlineButton[0]['courseId'];
				global $onlineFormsDepartments;
				$displayData['gdPiName'] = $onlineFormsDepartments[$displayOnlineButton[0]['departmentName']]['gdPiName'];
                            $displayData['externalURL'] = isset($displayOnlineButton[0]['externalURL'])?$displayOnlineButton[0]['externalURL']:'';
		    	$finalData['onlineFormButton'] = ($this->load->view('listing/listingPage/widgets/onlineFormButton',$displayData,true));
	    		$displayData['link'] = true;
    			$finalData['onlineFormLink'] = ($this->load->view('listing/listingPage/widgets/onlineFormButton',$displayData,true));
            }
                }

		echo json_encode($finalData);
	}
	
	public function getPrefferedLocationObject($course, $preferred_city, $preferred_locality)
	{
		$multiple_locations = array();
		if($course){
		    $locations = $course->getLocations();
		    $currentLocation = $course->getMainLocation();
		}
		foreach($locations as $location)
		{
			$localityName = $location->getLocality()?$location->getLocality()->getName():"";
			$localityId = $location->getLocality()?$location->getLocality()->getId():0;
			
			if((!is_numeric($preferred_city) && !is_numeric($preferred_locality)) && $preferred_city != "0" )
			{
				if((trim($preferred_city) == trim($location->getCity()->getName())) && $location->isHeadOffice())
			    {
					if(($preferred_locality == '' || $preferred_locality == "0") && $location->isHeadOffice())
				    {
						$currentLocation = $location;
						break;
				    }
				    if($preferred_locality != "0" && $preferred_locality == $localityName)
				    {
						$currentLocation = $location;
						break;
				    }
				    $matched_city_array[] = $location;
				    $currentLocation = $matched_city_array[0];
			    }
			}
			else if((is_numeric($preferred_city) && is_numeric($preferred_locality)) && $preferred_city != 0)
			{
				if($preferred_city == $location->getCity()->getId())
			    {
					if(($preferred_locality == 0) && $location->isHeadOffice())
				    {
						$currentLocation = $location;
						break;
				    }
				    if($preferred_locality != 0 && $preferred_locality == $localityId)
				    {
						$currentLocation = $location;
						break;
				    }
				    $matched_city_array[] = $location;
				    $currentLocation = $matched_city_array[0];
			    }
			}
			else
			{
				if($_REQUEST['city']!='' && $_REQUEST['city'] == $location->getCity()->getId())
				{
					if((!array_key_exists('locality',$_REQUEST) || empty($_REQUEST['locality'])) && $location->isHeadOffice())
				    {
						$currentLocation = $location;
						break;
				    }
				    if($_REQUEST['locality'] == $localityId)
				    {
						$currentLocation = $location;
						break;
					}
				    $matched_city_array[] = $location;
				    $currentLocation = $matched_city_array[0];
				}
			}
			
		}
		//error_log('check if here currentLocation final: '. print_r($currentLocation, true));
		return $currentLocation;
	}
	
	public function emailSMScontactDetails()
	{
		show_404(); die;
		$this->load->library('Register_client');

		$userInfo = $this->checkUserValidation();

		if($userInfo == 'false') {
			return false;
		}

		$user_id = $userInfo[0]['userid'];
		
		$listing_id          = $this->input->post('listing_id', true);
		$listing_type        = $this->input->post('listing_type', true);
		//$user_id             = $this->input->post('user_id', true);
		$preferred_city      = $this->input->post('preferred_city', true);
		$preferred_locality  = $this->input->post('preferred_locality', true);
		$preferred_course_id = $this->input->post('course_id', true);
		$institute_id        = $this->input->post('institute_id', true);
		
		$temp[] = $user_id;
		$this->load->model("user/usermodel");
		$usermodel_object = new usermodel();
		
		$user = $usermodel_object->getUsersBasicInfo($temp);
		
		$mobile = $user[$user_id]['mobile'];
		$email = $user[$user_id]['email'];
		
		$data['mobile'] = $mobile;
		$data['email'] = $email;
		
		$validateuser = $this->checkUserValidation(); //for first name of the user
		
		$course_id = $preferred_course_id;
		
		$this->_init($displayData, $course_id, 'course');
		$course = $this->courseRepository->find($course_id);
		
		if($institute_id == 0) {
			$institute_id = $course->getInstId();
		}
		$institute = $this->_findInstituteDetails($institute_id);
		$data['institute_name'] = $institute->getName();
		//error_log('check if here institute_name: '.$data['institute_name']);
		$currentLocation = $this->getPrefferedLocationObject($course, $preferred_city, $preferred_locality);
		
		$locations = $course->getLocations();
		$location = $locations[$currentLocation->getLocationId()];
		
		$data['contact_person'] = "";
		$data['contact_numbers'] = "";
		$data['contact_fax'] = "";
		$data['contact_email'] = "";
		$data['contact_website'] = "";
		$data['contact_address'] = "";
		if($contactDetail = $location->getContactDetail())
		{
			if($contactDetail->getContactNumbers())
			{
				$data['contact_numbers'] = $contactDetail->getContactNumbers();
			}
			else
			{
				$locations = $institute->getLocations();
				$location = $locations[$currentLocation->getLocationId()];
				$contactDetail = $location->getContactDetail();
				if($contactDetail->getContactNumbers())
				{
					$data['contact_numbers'] = $contactDetail->getContactNumbers();
				}
			}
			if($contactDetail->getContactPerson())
			{
				$data['contact_person'] = $contactDetail->getContactPerson();
			}
			
			if($contactDetail->getContactFax())
			{
				$data['contact_fax'] = $contactDetail->getContactFax();
			}
			
			if($contactDetail->getContactEmail())
			{
				$data['contact_email'] = $contactDetail->getContactEmail();
			}
			
			if($contactDetail->getContactWebsite())
			{
				$data['contact_website'] = $contactDetail->getContactWebsite();
			}
			
			if($location->getAddress())
			{
				$data['contact_address'] = $location->getAddress();
			}
			
			
		}
		
		
		//$this->load->library('Alerts_client');
        //$alertClient = new Alerts_client();
		// To the user
	    $to_email = $data['email'];
		$from_email = 'info@shiksha.com';
		
        $temp['userdata']=$data;
		if($data['contact_person'] == ""){
			$contact_person = "Name of the Person: Not available";
			$data['contact_person'] = 'Not available';
		}else
			$contact_person = "Name of the Person: ".$data['contact_person'];
		
		if($data['contact_numbers'] == ""){
			$data['contact_numbers'] = 'Not available';
			$contact_numbers = "Contact No.: Not available";
		}else
			$contact_numbers = "Contact No.: ".$data['contact_numbers'];
		
		if($data['contact_fax'] == ""){
			$contact_fax = "Fax No.: Not available";
			$data['contact_fax'] = 'Not available';
		}else
			$contact_fax = "Fax No.: ".$data['contact_fax'];
		
		if($data['contact_email'] == ""){
			$contact_email = "Email: Not available";
			$data['contact_email'] = 'Not available';
		}else
			$contact_email = "Email: ".$data['contact_email'];
		
	    if($data['contact_website'] == ""){
			$contact_website = "Website: Not available";
			$data['contact_website'] = 'Not available';
		}else
			$contact_website = "Website: ".$data['contact_website'];
		
		if($data['contact_address'] == ""){
			$contact_address = "Address: Not available";
			$data['contact_address'] = "Not available";
		}else
			$contact_address = "Address: ".$data['contact_address'];
		
		//$content_mail = "Test mail";

		$params['mailer_name'] = 'nationalContactDetails'; 
		$params['to_mail']     = $to_email;
		$params['from_email']  = $from_email;

		$appId = 1;
		$register_client = new Register_client();
		$categoryId = $register_client->getCategoryIdForUser($appId,$user_id);
        $category = $categoryId[0]['CategoryId'];

        if($category == 3){
    		$data += $params;
    		$data['firstname'] = $validateuser[0]['firstname'];
    		$data['listing_type_id'] = $preferred_course_id;
    		$data['listing_type'] = $listing_type;
    		$data['listing_id'] = $listing_id;
    		Modules::run('systemMailer/SystemMailer/nationalContactDetailsFullTimeMBA',$data);
        	
        }else{
        	$subject = "Contact details for ".$data['institute_name'];
        	$content_mail = "Dear ".$validateuser[0]['firstname'].",<br><br>".
				   "The contact details requested by you for ".$data['institute_name']." are as follows: <br><br>".
				   $contact_person."<br>".
				   $contact_numbers."<br>".
				   $contact_fax."<br>".
				   $contact_email."<br>".
				   $contact_website."<br>".
				   $contact_address."<br><br>".
				   "Regards,<br>".
				   "Shiksha.com";
        	$params['content']     = $content_mail;
        	$params['subject']     = $subject;
        	$responsemail = Modules::run('systemMailer/SystemMailer/nationalMailerTrackingCompiler',$params);
			//$responsemail=$alertClient->externalQueueAdd(1,$from_email,$to_email,$subject,$content_mail,"html");
        }
       
	    $this->load->model('SMS/smsModel');
		$smsmodel_object = new smsModel();
		$SMSto = $data['mobile'];
		
		if(strlen($data['institute_name'])>=30)
		{
			$institute_name = substr($data['institute_name'],0,30)."...";
		}
		else
		    $institute_name = $data['institute_name'];
		    
		//error_log('check if here institute_name2: '.$institute_name);
		if($data['contact_numbers'] == "")
		{
			$final_contact_numbers_string = "Contact Number: NA";
		}
		else
		{
			$final_contact_numbers_array = array();
			$contact_number = explode(",",$data['contact_numbers']);
			
			if(strlen($data['contact_numbers']) > 34)
			{
				if(sizeof($contact_number) > 3)
				{
					//take just 3..check..else..take just 2
					$final_contact_numbers_array[0] = $contact_number[0];
					$final_contact_numbers_array[1] = $contact_number[1];
					$final_contact_numbers_array[2] = $contact_number[2];
					$final_contact_numbers_string = implode(",",$final_contact_numbers_array);
					if(strlen($final_contact_numbers_string)>34)
					{
						$final_contact_numbers_array[2] = "";
					}
				}
				else
				{
					$final_contact_numbers_array[0] = $contact_number[0];
					$final_contact_numbers_array[1] = $contact_number[1];
				}
			}
			else if(sizeof($contact_number) > 3)
			{
				$final_contact_numbers_array[0] = $contact_number[0];
				$final_contact_numbers_array[1] = $contact_number[1];
				$final_contact_numbers_array[2] = $contact_number[2];
			}
			else
			{
				$final_contact_numbers_array = $contact_number;
			}
			$final_contact_numbers_string = implode(",",$final_contact_numbers_array);
		}
		//$content_sms = "Test sms";
		$institute_name = str_replace(array("&", "#"), " ", $institute_name);

		$content_sms = "Dear User,&#032;Contact details requested by you are&#032;-&#032;".
				$institute_name."&#032;".
				$final_contact_numbers_string.
				"&#032;-Shiksha.com";

												//$dbHandle,$toSms,$content,$userId,$sendTime
		$msg = $smsmodel_object->addSmsQueueRecord("1", $SMSto, $content_sms, $user_id,0);
	}
	
	private function _checkSaveInfo(& $displayData){
		$this->load->library('saveProduct_client');
        $sp = new SaveProduct_client();
		if($displayData['validateuser'] != "false"){
			$userId = $displayData['validateuser'][0]['userid'];
		}else{
			$userId = 0;
		}
        $chk = $sp->checkIfSaved(1, $userId,array($displayData['typeId']),array($displayData['pageType']));
		if ($chk[0]['id']==$displayData['typeId'])
            $displayData['saved'] ="saved";
	}

	function alsoOnShiksha($courseId,$pageType = 'course', $fromWhere='')
	{return;
		ini_set('memory_limit', '128M');
		$tracking_id = $pageType == 'course' ? DESKTOP_NL_LP_COURSE_LEFT_ALSO_VIEWED_RECO_DEB : DESKTOP_NL_LP_INST_LEFT_ALSO_VIEWED_RECO_DEB; 
		if($pageType == 'course')
		{
			$comparetrackingPageKeyId = 616;
		}
		else
		{
			$comparetrackingPageKeyId = '';
		}
		if($courseId != '' && $courseId > 0){
			if($fromWhere == 'NL_coursePage'){
				return Modules::run('categoryList/CategoryList/showRecommendation',$courseId,'LP_Reco_AlsoviewLayer','',0,0,'',0,0,'',$tracking_id,$comparetrackingPageKeyId);	
			}else{
				echo Modules::run('categoryList/CategoryList/showRecommendation',$courseId,'LP_Reco_AlsoviewLayer','',0,0,'',0,0,'',$tracking_id,$comparetrackingPageKeyId);
			}
		}
		else{
			if($fromWhere == 'NL_coursePage'){
				return '';
			}else{
				echo '';
			}
		}
	}
	
	function similarOnShiksha($courseId,$cityId=0,$institutesToBeExcluded = '',$subCategoryId =0,$pageType = 'course', $fromWhere='')
	{return;
		ini_set('memory_limit', '128M');
		$tracking_id = $pageType == 'course' ? DESKTOP_NL_LP_COURSE_LEFT_SIMILAR_RECO_DEB : DESKTOP_NL_LP_INST_LEFT_SIMILAR_RECO_DEB;
		$comparetrackingPageKeyId = 619;
		if($fromWhere == 'NL_coursePage'){
			return Modules::run('categoryList/CategoryList/showRecommendation',$courseId,'LP_Reco_SimilarInstiLayer',$institutesToBeExcluded,$subCategoryId,$cityId,'',0,0,'',$tracking_id,$comparetrackingPageKeyId);
		}else{
			echo Modules::run('categoryList/CategoryList/showRecommendation',$courseId,'LP_Reco_SimilarInstiLayer',$institutesToBeExcluded,$subCategoryId,$cityId,'',0,0,'',$tracking_id,$comparetrackingPageKeyId);
		}
	}
	
	function onlineFormWidget($courseId,$domainSubCatId, $type='')
	{
 		if($courseId != '' && $courseId > 0){ 
 			$this->showOnlineFormRecommendations($courseId,$domainSubCatId, $type);
 		}
 	}

	function debugAlsoViewed($institute_id){
		if(empty($institute_id)) {
			return ;
		}

		$this->load->library('categoryList/CategoryPageRecommendations');
		$this->load->library('recommendation/alsovieweddebug');
		$this->load->helper('listing/listing');
		$this->_init($displayData, $institute_id);
		$courses = $this->_getCourses($institute_id);
		$institute = $this->_findInstituteDetails($institute_id,$courses);
		$categories = $this->instituteRepository->getCategoryIdsOfListing($institute_id,"institute");
		
		echo "<table border='1' width='800'>";
		echo "<tr><td width='200'>Institute ID</td><td>".$institute_id."</td></tr>";
		echo "<tr><td width='200'>Institute subcategories</td><td><pre>".print_r($categories,true)."</pre></td></tr>";
		
		if(isset($categories->ERROR_MESSAGE) && $categories->ERROR_MESSAGE == 'NO_DATA_FOUND') {
			echo "<tr><td width='200'>No recommendations</td><td>No categories mapped</td></tr>";
			return ;
		}

		$category = $this->categoryRepository->find($categories[0]);
		echo "<tr><td width='200'>Category selected</td><td>".$category->getParentId()."</td></tr>";
		
		$seedData = array(array('institute_id' => $institute->getId(),'country_id' => $institute->getMainLocation()->getCountry()->getId()));
        $exclusionList = array($institute->getId());
		
		$also_viewed_institutes = $this->alsovieweddebug->getAlsoViewedListings($seedData,$category->getParentId(),10,$exclusionList);
		$alsoViewedInstitutes = $this->categorypagerecommendations->getAlsoViewedInstitutes(array($institute),$category->getParentId());
		echo "</table>";
		if(is_array($alsoViewedInstitutes) && count($alsoViewedInstitutes)) {
			$displayData['institutes'] = array_slice($this->instituteRepository->findWithCourses($alsoViewedInstitutes),0,3);
			$displayData['recommendationPage'] = 1;
			$displayData['alsoOnShiksha'] = 1;
			$displayData['recommendationsApplied'] = isset($_COOKIE['recommendation_applied'])?explode(',',$_COOKIE['recommendation_applied']):array();
			$displayData['validateuser'] = $this->checkUserValidation();
			$template_name = 'listing/listingPage/widgets/alsoOnShiksha';	
			echo $this->load->view($template_name,$displayData,true);
		}
	}
	
	function inlineRecommendCourses($instituteId,$courseId,$widget = '',$extra_info='') {

		if(empty($instituteId) || empty($courseId) || empty($widget)) {
			return false;
		}
		/*
		 If user not logged in,
		 redirect to category page
		 */
		$userInfo = $this->checkUserValidation();

		if($userInfo == 'false') {
			return false;
		}

		$user_id = $userInfo[0]['userid'];

		$this->load->library('categoryList/CategoryPageRecommendations');
		$this->load->library('categoryList/Category_list_client');
		$this->load->helper('listing/listing');

		$this->load->model('coursemodel');
		$course_object = new CourseModel();
		$result = $course_object->getParentCategoryOfCourses(array($courseId));
		$categoryId = $result[$courseId][0];

		$categoryName = $this->category_list_client->get_category_name(1,$categoryId);
		$appliedData = array();
		$appliedData[$instituteId] = $courseId;

		$this->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;
		$instituteRepository = $listingBuilder->getInstituteRepository();

		$appliedInstitutes = $instituteRepository->findWithCourses($appliedData);

		foreach($appliedInstitutes as $instiObj){
			$country_id = $instiObj->getMainLocation()->getCountry()->getId();
			break;
		}

		$data = array();
		$data['extra_info'] = html_entity_decode(base64_decode($extra_info));
		/*
		 Also viewed algo
		 */
		$alsoViewedInstitutes = $this->categorypagerecommendations->getAlsoViewedInstitutes($courseId);

                //error_log('adityabughtecategory'.$categoryId);
                //error_log('adityabughte'.print_r($alsoViewedInstitutes,true));
		if(is_array($alsoViewedInstitutes) && count($alsoViewedInstitutes)>0) {
			$data['institutes'] = $instituteRepository->findWithCourses($alsoViewedInstitutes);
		} else {
			$similar_institutes = $this->categorypagerecommendations->getSimilarInstitutes($appliedInstitutes);

			if(is_array($similar_institutes) && count($similar_institutes)>0) {
				$data['institutes'] = $instituteRepository->findWithCourses($similar_institutes);
			} else {

				$this->load->library('recommendation/recommendation_lib');
				$this->load->model('profilebased_model');
				$this->profilebased_model->init(NULL,NULL);
				$profile_based_recommendations = array();

				$user_profile_info = $this->profilebased_model->getUserProfileInfo(array($user_id));
				$profile_based_results = $this->recommendation_lib->getProfileBasedResults($user_profile_info[$user_id],array(),10);

				foreach ($profile_based_results as $result) {
					$instituteId = $result['institute_id'];
					$courseId = $recommendation['course_id'];
					$profile_based_recommendations[$instituteId] = $courseId;
				}

				if(is_array($profile_based_recommendations) && count($profile_based_recommendations)>0) {
					$data['institutes'] = $instituteRepository->findWithCourses($profile_based_recommendations);
				}
			}
		}
		
                $final_institutes = array();
                foreach($data['institutes'] as $institute) {
			$courses = $institute->getCourses();
                        $course = $courses[0];
                        if($course->isPaid()) {
				$final_institutes[] = $institute; 
			}
                }
               
                $data['institutes'] = array_slice($final_institutes,0,5);
		$data['validateuser'] = $userInfo;
		$data['recommendationsApplied'] = isset($_COOKIE['recommendation_applied'])?explode(',',$_COOKIE['recommendation_applied']):array();
		$template_name = 'listing/listingPage/widgets/alsoOnShiksha'.ucfirst($widget);
		echo $this->load->view($template_name,$data,true);

	}
	
	function callMeNow($instituteId,$courseId,$mobile,$widget){
		$this->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;
		$this->courseRepository = $listingBuilder->getCourseRepository();
		$course = $this->courseRepository->find($courseId);
		$instituteId = $course->getInstId();
		global $callMeWidgetInstitutes;
		
		if(in_array($widget, array(3,4,6))){
			return false;
		}
		if(!array_key_exists($instituteId,$callMeWidgetInstitutes)){
			return false;
		}
		$this->load->library('common/Call');
		$call = new Call();
		$nowTime = strtotime("now");
		$mintime = strtotime($callMeWidgetInstitutes[$instituteId]['mintime']);
		$maxtime = strtotime($callMeWidgetInstitutes[$instituteId]['maxtime']);
		if($mintime > $maxtime){
			$condition = !($nowTime > $mintime || $nowTime < $maxtime);
		}else{
			$condition = $nowTime < $mintime || $nowTime > $maxtime;
		}
		if($condition){
			$this->recordCallWidgetLoad($instituteId,$courseId,"CallCancelled");
			return false;
		}
		//error_log("AMIT".$callMeWidgetInstitutes[$instituteId]['numbers'].$mobile.$course->getName() );
		$call = $call->connectCall($callMeWidgetInstitutes[$instituteId]['numbers'],$mobile,$course->getName());
		$this->recordCallWidgetLoad($instituteId,$courseId,"CallMade");
		return true;
	}
	
	function recordCallWidgetLoad($instituteId,$courseId,$widget){
		$this->load->model('listing/listingmodel');
		$this->listingmodel->recordCallWidgetLoad($instituteId,$courseId,$widget);
	}
	
	function cronToGetEverySeventhDayInformation() {

		try {
			$this->load->library('sums_product_client');
			$objSumsProduct =  new Sums_Product_client();
			$sales_persons_list = $objSumsProduct->getSalesPersonWiseClientList();

			if(count($sales_persons_list) == 0) {
				throw new Exception('No sales persons details found.');
			}

			$this->load->model('listingmodel');
			$model_object = new ListingModel();

			foreach($sales_persons_list as $sales_person_id =>$value) {

				$listings_list = $model_object->getActiveLisitingsForagroupOfOwner($value['client_list']);
				$active_institute_count = 0;
				$paid_course_count = 0;

				foreach($listings_list as $listing) {
					if($listing['listing_type'] == 'institute') {
						$active_institute_count++;
					} else if($listing['listing_type'] == 'course' && ($listing['pack_type'] == GOLD_SL_LISTINGS_BASE_PRODUCT_ID || $listing['pack_type'] == SILVER_LISTINGS_BASE_PRODUCT_ID || $listing['pack_type'] == GOLD_ML_LISTINGS_BASE_PRODUCT_ID)) {
						$paid_course_count++;
					}
				}

				$sales_persons_list[$sales_person_id]['active_institute_count'] = $active_institute_count;
				$sales_persons_list[$sales_person_id]['paid_course_count'] = $paid_course_count;
				$paid_course_having_ebrochure = $model_object->getPaidCouresHavingEbrochureUploadedForagroupOfOwner($value['client_list']);
				$sales_persons_list[$sales_person_id]['paid_course_having_ebrochure'] = $paid_course_having_ebrochure[0]['course_brochure_count'];
				$institutes_having_ebrochure = $model_object->getInstitutesHavingEbrochureUploadedForagroupOfOwner($value['client_list']);
				$sales_persons_list[$sales_person_id]['institutes_having_ebrochure'] = $institutes_having_ebrochure[0]['institute_brochure_count'];
			}

			$this->generateAndSendReport($sales_persons_list);

		} catch(Exception $e) {
			error_log("cronToGetEverySeventhDayInformation error Message : " . $e->getMessage());
			error_log("cronToGetEverySeventhDayInformation error Code : " . $e->getCode());
		}
	}
	function generateAndSendReport($sales_persons_list) {

		try {
			if(count($sales_persons_list) == 0) {
				throw new Exception('No sales persons details found.');
			}

			$filename = date(Ymdhis).'ebrochureuploaddata.csv';
			$mime = 'text/x-csv';
			$columnListArray = array();
			$data_array = array();
			$columnListArray[]='Sales person Name';
			$columnListArray[]='Sales person Email';
			$columnListArray[]='Branch';
			$columnListArray[]='No. of active institutes belonging to his clients';
			$columnListArray[]='No. of paid courses belonging to his clients';
			$columnListArray[]='No. of active institutes belonging to his clients that have brochrue uploaded';
			$columnListArray[]='No. of paid courses belonging to his clients that have brochure uploaded';

			
			$data_array[] = $columnListArray;
			foreach ($sales_persons_list as $info) {
				$data_array[] = array($info['displayname'],$info['email'],$info['BranchName'],$info['active_institute_count'],$info['paid_course_count'],$info['institutes_having_ebrochure'],$info['paid_course_having_ebrochure']);
			}
			$file_pointer = fopen("/tmp/".$filename, "w");
			
			foreach ($data_array as $fields) {
				fputcsv($file_pointer, $fields);
			}
	    fclose($file_pointer);	
            $csv = file_get_contents("/tmp/".$filename); 
            unlink("/tmp/".$filename);
             
			$this->load->library('alerts_client');
			$alertClientObj = new Alerts_client();
			$type_id = time();
			$date = date("d-m-Y");
			$content = "<p>Hi,</p> <p>Please find the attached report for Ebrochureuploaddata Report for last 7 days on Shiksha. </p><p>- Shiksha Tech.</p>";
			$subject = "";
			$subject .=$date .' Ebrochureuploaddata Report for last 7 days';
			$email   = array('Prakash.sangam@naukri.com',
						'ambrish@shiksha.com',
						'saurabh.gupta@shiksha.com');

			$attachmentResponse = $alertClientObj->createAttachment("12",$type_id,'COURSE','E-Brochure',$csv,$filename,'text');
				
			for($i=0;$i<count($email);$i++){
				$attachmentId = $attachmentResponse;
				$attachmentArray=array();
				array_push($attachmentArray,$attachmentId);
				$response = $alertClientObj->externalQueueAdd("12","info@shiksha.com",$email[$i],$subject,$content,$contentType="html",'','y',$attachmentArray);
			}

		} catch(Exception $e) {
			error_log("cronToGetEverySeventhDayInformation error Message : " . $e->getMessage());
			error_log("cronToGetEverySeventhDayInformation error Code : " . $e->getCode());
		}

	}

	function increaseContactCount($listing_id,$listing_type,$tracking_field){		 
		$this->load->model('listingmodel');
        	$updateStatus = $this->listingmodel->increaseContactCountOfListing($listing_id,$listing_type,$tracking_field);
       
	       	echo $updateStatus;
	}

	function getListingData($subCateId) {
	    if($subCateId == ""){
		die("No Input defined.");
	    }
	    
	    ini_set('memory_limit','3500M');
	    $this->load->model('coursefindermodel');
	    $model_object = new coursefindermodel();
	    $instituteIds = "31572,31586,31573,31571,34723,35167,29074,35169,29191,2881,32416,33323,35170,36293,21970,33966,34041,3698,20332,31855,27864,32690,30699,32570,33116,29602,30065,27017,441,28037,33532,28222,27907,30368,26803,27228,35810,35024,24308,35113,28230,28616,27983,19396,34650,26881,32561,36330,33163,26974,74730,475,33192,30738,33183,32427,34508,34010,33293,30762,25137,31083,36439,24759,169,24621,28585,29088,36084,36591,24802,4268,29759";
	    $courses = $model_object->getCoursesOfSubcategory($subCateId, $instituteIds);
	    
	    $this->load->builder('ListingBuilder','listing');
	    $listingBuilder = new ListingBuilder;
	    $this->courseRepository = $listingBuilder->getCourseRepository();	    
	    $filename = 'listingData_'.$subCateId.'_'.date("Y-m-d").'.csv';
	    $mime = 'text/x-csv';
	    $columnListArray = array();
	    $data_array = array();
	    $columnListArray[]='insitute name';
	    $columnListArray[]='institute id';
	    $columnListArray[]='course name';
	    $columnListArray[]='course id';
	    $columnListArray[]='cutoff exam : cutoff score';
	    $columnListArray[]='course fees';
	    $columnListArray[]='course Duration';
	    $columnListArray[]='Affiliated to';
	    
	    $data_array[] = $columnListArray;
	    
	    $coursesObj = $this->courseRepository->findMultiple($courses);
	    $i = 0;
	    foreach($coursesObj as $courseId =>$course) {
		$i++;
		$examObjArray = $course->getEligibilityExams();
		$examData = array();
		if(count($examObjArray)) {
		    foreach($examObjArray as $id => $exam) {
			$examData[] = $exam->getAcronym()." : ".$exam->getMarks()." ".$exam->getMarksType(); 
		    }
		}
		
		$affiliationsObj = $course->getAffiliations();
		$affiliationData = array(); $affiliationValue = ""; $affiliationArray = array();
		if(count($affiliationsObj)) {
		    foreach($affiliationsObj as $id => $affiliation) {			
			$affiliationArray[] = ucfirst($affiliation[0]).($affiliation[1] == "" ? '': (" (".$affiliation[1].")"));
		    }
		    $affiliationValue = implode(", ", $affiliationArray);
		}
		
		$data_array[] = array($course->getInstituteName(),
				    $course->getInstId(),
				    $course->getName(),
				    $course->getId(),
				    implode(", ", $examData),
				    $course->getFees()->getValue()." ".$course->getFees()->getCurrency(),
				    $course->getDuration(),
				    $affiliationValue,
				    );
	    }
	    
	    $file_location = "/tmp/".$filename;
	    $file_pointer = fopen($file_location, "w");
	    
	    foreach ($data_array as $fields) {
		    fputcsv($file_pointer, $fields);
	    }
	    
	    fclose($file_pointer);
	    
	    // Now download the file.. //set appropriate headers first..
	    header('Content-Description: File Transfer');
	    header('Content-Type: application/csv');
	    header('Content-Disposition: attachment; filename='.basename($file_location));
	    header('Expires: 0');
	    header('Cache-Control: must-revalidate');
	    header('Pragma: public');
	    header('Content-Length: ' . filesize($file_location));
	    ob_clean();
	    flush();
    
	    //read the file from disk and output the content.
	    readfile($file_location);
	    exit("All done for Subcategory id: ".$subCateId);	    	
	}
	
	public function reportNumbers() {

		$return_msg = json_encode(array('msg'=>'failed'));
		$listing_id = $this->input->post('listing_id','true');
		$listing_type = $this->input->post('listing_type','true');
		$numbers = $this->input->post('numbers','true');
		$numbers_array = explode(",",$numbers);
		
		if(empty($listing_id) || empty($listing_type) || count($numbers_array) ==0) {
			echo $return_msg;
			return false;
		}
       
		if(count($numbers_array) >0) {
			if($listing_type == 'institute') {
				$this->load->model('institutemodel');
				$return_msg = $this->institutemodel->reportContactNumbers($listing_id,$listing_type,$numbers_array);
			}else if($listing_type == 'course') {
				$this->load->model('coursemodel');
				$return_msg = $this->coursemodel->reportContactNumbers($listing_id,$listing_type,$numbers_array);
			}
		}

		echo $return_msg;
	}

    function generatePdf($listingTypeId, $listingType="course") {	    

	$this->benchmark->mark('code_start');
	if($listingTypeId == "")
	die("No listingTypeId defined");
	    
	$listingebrochuregenerator = $this->load->library('ListingEbrochureGenerator');
	if($listingType == "course") {
	   $urlArray = $listingebrochuregenerator->genearteEbrochure('course', $listingTypeId);
	} else {
	   $urlArray = $listingebrochuregenerator->genearteEbrochure('institute', $listingTypeId);
	}

	$this->benchmark->mark('code_end');
	echo "<br> Total time = ".$this->benchmark->elapsed_time('code_start', 'code_end');
	_p($urlArray); die;

	if($urlArray['RESPONSE'] == 'BROCHURE_FOUND') {
	   $fileName = explode("listingsBrochures/", $urlArray['BROCHURE_URL']);
	   $filePath = "/var/www/html/shiksha/mediadata/listingsBrochures/".$fileName[1];
	   header('Content-disposition: attachment; filename='.$fileName[1]);
	   header('Content-type: application/pdf');
	   readfile($filePath);
	}
    }

	
	private function _getCoursePageParams($data) {

		if(!isset($_SERVER['HTTP_REFERER'])) {
			return array();
		}
		
		$path = parse_url($_SERVER['HTTP_REFERER']);
		
		// explode path
		$path_array = explode("/", $path['path']);

		$last_uri = $path_array[count($path_array)-1];
		$coursepage_sub_cat_array = array();
		$course_page_required_category = 0;
		$response_array = array();
		
		$query_param = $path['query'];
		if(!empty($query_param)) {
			$query_array = explode("&", $query_param);
			foreach ($query_array as $value) {
				if(strpos($value, "cpgs_param") !==FALSE) {
					$cpgs_param_array = explode("=", $value);
					$cpgs_param_array = explode("_", $cpgs_param_array[1]);
					$response_array['course_page_required_category'] = $cpgs_param_array[0];
					$response_array['course_pages_tabselected'] = $cpgs_param_array[1];
					break;
				}
			}
			
			if(count($response_array)>0) {
				return $response_array;
			}
		} 
		
		if(strpos($last_uri,"-listingcourse") !==FALSE) {
			
			$path_params = 	explode("-", $last_uri);
			$course_id = $path_params[count($path_params)-1];

			if(intval($course_id) == 0) {
				return array();
			}
			
			$categories = $data['instituteRepository']->getCategoryIdsOfListing($course_id,"course");
			$coursepage_sub_cat_array = $categories;
			
		} else if(strpos($last_uri,"-application-forms") !==FALSE) {
			
			$path_params = 	explode("-", $last_uri);
			if(in_array("mba", $path_params)) {
				$response_array['course_page_required_category'] = 23;
			} else if(in_array("engineering", $path_params)) {
				$response_array['course_page_required_category'] = 56;
			}
			
		} else if(strpos($last_uri,"-coursepage") !==FALSE) {		
				
			$this->coursePagesUrlRequest = $this->load->library('coursepages/CoursePagesUrlRequest');
			$response = $this->coursePagesUrlRequest->getSubcatFromCoursePageUrlKey(str_replace("-coursepage", "", $last_uri));
			$response_array['course_page_required_category'] = $response['subCatId'];
			
		} else if(strpos($last_uri, "-categorypage-") !==FALSE) {
			$path_params = 	explode("-categorypage-", $last_uri);
			$category_page_params = explode("-", $path_params[1]);
			$response_array['course_page_required_category'] = $category_page_params[1];

		} else if(strpos($path['path'],'/course/') !==FALSE) {
			$course_id = $path_array[2];
			$categories = $data['instituteRepository']->getCategoryIdsOfListing($course_id,"course");
			$coursepage_sub_cat_array = $categories;	
 		} else if(strpos($last_uri, "-article-") !==FALSE) {
 			$explode_array = explode("-article-", $last_uri);
 			$part1 = $explode_array[1];
 			$article_array = explode("-", $part1);
 			$article_id = $article_array[0]; 
 			$response_array['course_page_required_category'] = $this->_getSubcategoryForEntities($article_id, 'article');
 			
 		} else if(strpos($last_uri, "-qna-") !==FALSE) {
                       
                        if(strpos($last_uri, "-qna-expert") !==FALSE) {
                        	return array();
                        }
 			$explode_array = explode("-qna-", $last_uri);
 			$part1 = $explode_array[1];
 			$questions_array = explode("-", $part1);
 			$questions_id = $questions_array[0]; 
                        //echo "sdddddddddddddsdsdsd";   
 			$response_array['course_page_required_category'] = reset($this->_getSubcategoryForEntities($questions_id, 'qna'));
 			
 		}
		else if(strpos($last_uri, "-ctpg") !==FALSE) {
			$categoryPageRequestTemp = new CategoryPageRequest($last_uri, CP_NEW_RNR_URL_TYPE);
			if(!empty($categoryPageRequestTemp)){
				$tempSubCategoryId = $categoryPageRequestTemp->getSubCategoryId();
				$response_array['course_page_required_category'] = $tempSubCategoryId;
			}
		}
		else if(strpos($path['path'], "/colleges/") !==FALSE) {
			$categoryPageRequestTemp = new CategoryPageRequest($last_uri, CP_NEW_RNR_URL_TYPE);
			if(!empty($categoryPageRequestTemp)){
				$tempSubCategoryId = $categoryPageRequestTemp->getSubCategoryId();
				$response_array['course_page_required_category'] = $tempSubCategoryId;
			}
		} 

		if(count($coursepage_sub_cat_array)>0) {
			
			foreach ($coursepage_sub_cat_array as $coursepage_subcat) {
				if(checkIfCourseTabRequired($coursepage_subcat)){
					$response_array['course_page_required_category'] = $coursepage_subcat;
					break;
				}

			}
			
		}
		
		return $response_array;
	}
    
	private function _getSubcategoryForEntities($id,$type) {
					
		if($type == 'article') {
			
			$this->load->model('blogs/articlemodel');
			$response = $this->articlemodel->getArticlesSubcategories(array($id));
			return $response[$id];
			
		} else if($type == 'qna') {
			
			$this->load->model('messageBoard/qnamodel');
			$response = $this->qnamodel->getQuestionsDiscussionsSubcategories(array($id));						
			return $response[$id];
		}
	}
	

	public function loadAjaxWidgetForNationalCourse() {
			
		$type = $this->input->get_post('type',true);
		$userLoggedIn = $this->input->get_post('userLoggedIn',true);
		$listing_type = $this->input->get_post('listing_type',true);
		if(empty($type)) {
			echo "ERROR";
			return;
		}

		if($type == 'fbgooglead') {
			echo $this->load->view('national/widgets/fbGoogleAd',true);
		} else if($type == 'right') {
			echo $this->load->view('national/widgets/registration_right',array('userLoggedIn'=>$userLoggedIn,'listing_type'=>$listing_type),true);
		} else if($type == 'bottom') {
			echo $this->load->view('national/widgets/registration_bottom',array('userLoggedIn'=>$userLoggedIn,'listing_type'=>$listing_type),true);
		}
	}
	
	public function downLoadPDFbrochure($course_id, $bypassUserValidationFlag = false) {
	    
	    if( !$bypassUserValidationFlag )
	    {
		if(empty($course_id) || $this->checkUserValidation() == 'false')
		{
		    echo "You don't have permission to do this";
		    return false;
		}
	    }
	    $national_course_lib = $this->load->library('listing/NationalCourseLib');
	    $course_reb_url = $national_course_lib->getCourseBrochure($course_id);
	    if(empty($course_reb_url))
	    {
		    echo "E brochure not found for this course";
		    return false;
	    }
	    
	    $this->load->helper('download');
	    downloadFileInChunks($course_reb_url, 400000);
	}
	
	public function downLoadEbrochure($course_id, $bypassUserValidationFlag = false) {
		ob_start();
		if( !$bypassUserValidationFlag )
		{
		    if(empty($course_id) || $this->checkUserValidation() == 'false' )
		    {
			echo "You don't have permission to do this";
			return false;
		    }
		}
		
		$national_course_lib = $this->load->library('listing/NationalCourseLib');
		$course_reb_url = $national_course_lib->getCourseBrochure($course_id);
		if(empty($course_reb_url))
		{
			echo "E brochure not found for this course";
			return false;
		}
		
		$national_course_lib = $this->load->library('listing/NationalCourseLib'); 
		$curl_response = $national_course_lib->makeCurlRequest($course_reb_url); 
		
		// Now download the file.. //set appropriate headers first.. 
		header('Content-Description: File Transfer'); 
		header("Content-Type: text/html"); 
		header('Content-Disposition: attachment; filename='.basename($course_reb_url)); 
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($course_reb_url));
		ob_clean();
		flush();
		
		echo $curl_response['body'];
		exit("Brochure downloaded");
	}
	
	
	/**
	* Purpose : Function to check and set the values is displayData array necessary for making the response eg. Viewed_listing
	* 
	**/
	private function _checkAndSetDataForAutoResponse($institute, $course, &$displayData)
	{
		// validate the user
		$displayData['validateuser'] 	= $this->checkUserValidation();
		$validateuser 			= $displayData['validateuser'];
		
		// check if institute is paid or not
		//$this->_isInstitutePaid($institute, $displayData);
		// populate current location
		//$this->_populateCurrentLocation($displayData, $institute,$course,'course');
		
		if($validateuser != 'false') {
		    $userData = modules::run('registration/Forms/getUserProfileInfo',$validateuser[0]['userid']);
		}
		else {
		    $userData = array();
		}
		
		
		// set the makeAutoResponse if following conditions are made
		//if(($validateuser != "false") && $displayData['paid'] && !(in_array($validateuser[0]['usergroup'],array("enterprise","cms","experts","sums"))) && $course->isPaid() && (!($this->QnAModel->checkIfAnAExpert($dbHandle,$validateuser[0]['userid']))) && ($validateuser[0]['mobile'] != ""))
		if(($validateuser != "false") && !(in_array($validateuser[0]['usergroup'],array("enterprise","cms","experts","sums"))) && (!($this->QnAModel->checkIfAnAExpert($dbHandle,$validateuser[0]['userid']))) && ($validateuser[0]['mobile'] != ""))
		{
		    $displayData['makeAutoResponse'] = modules::run('registration/Forms/isValidResponseUser', $course->getId(), null, true);
		    
		    $user_id = $validateuser[0]['userid'];
		    $flag = $this->_isLastDayLeadExist( $user_id, $course->getId(), 'institute' );
		    
		    if( $flag )
			$displayData['reponse_already_created'] = true;
		    else
			$displayData['reponse_already_created'] = false;
		}
		$displayData['viewedListingTrackingPageKeyId'] = DESKTOP_NL_VIEWED_LISTING;

	}
	
	/**
	* Purpose : Function to check and set the values is displayData array necessary for making the response for institute page eg. institute_viewed
	* 
	**/
	private function _checkAndSetDataForAutoResponseForInstitutePage($institute, $course, &$displayData)
	{
		// validate the user
		$displayData['validateuser'] 	= $this->checkUserValidation();
		$validateuser 			= $displayData['validateuser'];
		
		// check if institute is paid or not
		//$this->_isInstitutePaid($institute, $displayData);

		// populate current location
		//$this->_populateCurrentLocation($displayData, $institute,$course,'institute');
		
		// set the makeAutoResponse if following conditions are made 
		//if(($validateuser != "false") && $displayData['paid'] && !(in_array($validateuser[0]['usergroup'],array("enterprise","cms","experts","sums"))) && $course->isPaid() && (!($this->QnAModel->checkIfAnAExpert($dbHandle,$validateuser[0]['userid']))) && ($validateuser[0]['mobile'] != ""))
		if(($validateuser != "false") && !(in_array($validateuser[0]['usergroup'],array("enterprise","cms","experts","sums"))) && (!($this->QnAModel->checkIfAnAExpert($dbHandle,$validateuser[0]['userid']))) && ($validateuser[0]['mobile'] != ""))
		{
		    $displayData['makeAutoResponse'] = modules::run('registration/Forms/isValidResponseUser', $course->getId(), null, true);
		    
		    $user_id = $validateuser[0]['userid'];
		    $flag = $this->_isLastDayLeadExist( $user_id, $course->getId(), 'course' );
		    
		    if( $flag )
			$displayData['reponse_already_created'] = true;
		    else
			$displayData['reponse_already_created'] = false;
		}

		$displayData['instituteViewedTrackingPageKeyId'] = DESKTOP_NL_INSTITUTE_VIEWED;
	}
	
	/**
	* Function to check for reponse made for the given user,course for last 24 hours 
	**/
	private function _isLastDayLeadExist( $userId, $course_id, $listing_type )
	{
		$this->load->model('listing/listingmodel');
		$row = $this->listingmodel->getLastDayLead($userId, $course_id, $listing_type );
		
		if( !empty($row) )
		    return true;
		else
		    return false;
	}	
	/**
	* Function to set the data for course details date data
	**/
	private function setNationalAccordionDateData( &$displayData )
	{
		$courseComplete = $displayData['courseComplete'];
		$currentLocation = $displayData['currentLocation'];
		
	    $dates = array (
	    "dateOfFormSubmission" => $courseComplete->getDateOfFormSubmission($currentLocation->getLocationId()),
	    "dateOfResultDeclaration" => $courseComplete->getDateOfResultDeclaration($currentLocation->getLocationId()),
	    "dateOfCourseComencement" => $courseComplete->getDateOfCourseComencement($currentLocation->getLocationId()),		
	    );
	    
	    foreach ($dates as $dateType => $dateVal)
	    {
		    if($dateVal !="0000-00-00 00:00:00" && $dateVal != 'undefined')
		    {
			$displayDates[$dateType]['dayOfWeek'] = strtoupper(date("D",strtotime($dateVal) ));
			$displayDates[$dateType]['month'] = strtoupper(date("M",strtotime($dateVal) ));
			$displayDates[$dateType]['year'] = date("y",strtotime($dateVal) );
			$displayDates[$dateType]['dayOfMonth'] = date("d",strtotime($dateVal) );
			if(strcmp($dateType,"dateOfFormSubmission") == 0){
			    $displayDates[$dateType]['dateTitle'] = "Form Submission";
			}
			elseif(strcmp($dateType,"dateOfResultDeclaration") == 0){
			    $displayDates[$dateType]['dateTitle'] = "Result Declaration";
			}
			elseif(strcmp($dateType,"dateOfCourseComencement") == 0){
			    $displayDates[$dateType]['dateTitle'] = "Course Comencement";
			}
		    }
	    }
	    
	    $displayData['displayDates'] = $displayDates;
	}
	
	/**
	* Function to set the data for jump to navigation section
	**/
	private function setJumpToNavigationData( &$displayData )
	{
	    if( $displayData['pageType'] == 'course' )
	    {
		    $jumpMenuData['IMPORTANT_INFO'] 	= 1;
		    
		    if( $displayData['courseComplete']->getDescriptionAttributes() || count($displayData['displayDates']) > 1 )
			$jumpMenuData['COURSE_DETAILS'] 	= 1;
		    
			$this->_fetchMediaForLocation($displayData['institute'],$tempDisplayData);
			if(count($tempDisplayData['mediaData']['videos'])>0 || count($tempDisplayData['mediaData']['photos'])>0)
			{
			    $jumpMenuData['PHOTOS_VIDEOS'] 		= 1;
			}
		    
		    if(in_array("campus_connect", $displayData['cta_widget_list']))
		    {
			$jumpMenuData['CAMPUS_REP'] 		= 1;
		    }
		    
		    //$jumpMenuData['SIMILAR_COURSES'] 	= 1;
		    if(!empty($displayData['other_courses_for_same_subcategory']['same_subcat_courses']))
		    {
			$jumpMenuData['SIMILAR_COURSES'] 	= 1;
		    }
		    
		    if(in_array("download_e_brochure", $displayData['cta_widget_list']) )
		    {
			$jumpMenuData['DOWNLOAD_EBROCHURE'] 	= 1;
		    }

		    if(1)
		    {
			$jumpMenuData['ALUMNI_EMPLOYMENT_STATS'] = 1;
		    }		
	    }
	    
	    else if($displayData['pageType'] == 'institute' )
	    {

		    $jumpMenuData['ALL_COURSES'] 	= 1;
		    $jumpMenuData['WHY_JOIN'] 		= 1;
		    $jumpMenuData['ALUMNI_SPEAK'] 	= 1;
		    $jumpMenuData['PHOTOS_VIDEOS'] 	= 1;
		    $jumpMenuData['INSTITUTE_DETAILS'] 	= 1;
		    $jumpMenuData['DOWNLOAD_EBROCHURE'] = 1;
            if(count($displayData['showCourseReviews']) > 0) {
                $jumpMenuData['COURSE_REVIEWS'] 	= 1;
            }
		    
	    }
	    
		$displayData['jumpMenuData'] = $jumpMenuData;
	}

	/**
	* Function to download the brochure given the encoded message having userId and courseId encoded 
	**/	
	public function downloadBrochure( $encodedMsg )
	{
	    $national_course_lib = $this->load->library('listing/NationalCourseLib');
	    
    	    $dataArr 	= $national_course_lib->getDecodedMsgForBrochureURL( $encodedMsg );
	    
	    $user_id	= $dataArr[0];
	    $course_id	= $dataArr[1];
	    
	    $course_reb_url = $national_course_lib->getCourseBrochure($course_id);
	    
	    if(empty($course_reb_url) || count($dataArr) != 2 )
	    {
		//_p("Wrong URL");
		return ;
	    }

	    $userInfo['user_id'] 	= $user_id;
	    $userInfo['course_id'] 	= $course_id;
	    $userInfo['brochureUrl'] 	= $course_reb_url;
	    $userInfo['session_id'] 	= $this->getSessionId();
		
	    $previousDownloadCount = $this->getPreviousDownloadCountForSession($user_id, $course_id, $userInfo['session_id']);
	    
	    if( $previousDownloadCount >= 5 )
	    {
		_p('<div style="font-family:Tahoma, Geneva, sans-serif"><div style="font-weight:bold;font-size:18px;">Download E-Brochure</div><p style="margin-bottom:1px;">You have downloaded this brochure <span style="color:#f27427; font-weight:bold;">5 times</span>.</p><p style="margin-bottom:1px;">Further Downloads are currently not allowed.</p></div>');
		return;
	    }
	    else
	    {
		$this->load->model('listing/coursemodel');
		$courseModel = new coursemodel();
		$courseModel->trackFreeBrochureDownload($userInfo);
		
		$x = explode('.', $course_reb_url);
		$extension = strtolower(end($x));
		
		if($extension == "pdf")
		{
                    $this->downLoadPDFbrochure($course_id, true);
                }
		else
		{
                    //$this->downLoadEbrochure($course_id, true);
		    $curl_response = $national_course_lib->makeCurlRequest($course_reb_url);
    
		    // Now download the file.. //set appropriate headers first.. 
		    header('Content-Description: File Transfer');
		    header("Content-Type: text/html");
		    header('Content-Disposition: attachment; filename='.basename($course_reb_url));
		    header('Expires: 0');
		    header('Cache-Control: must-revalidate');
		    header('Pragma: public');
		    header('Content-Length: ' . filesize($course_reb_url));
		    ob_clean();
		    flush();
    
		    echo $curl_response['body'];
		    exit("Brochure downloaded");
                }


		//$this->load->helper('download');
		//$data = file_get_contents($course_reb_url); // Read the file's contents
		//force_download($course_reb_url,$data);
		//downloadFileInChunks($course_reb_url, 2000)
	    }
	}
	
	/**
	* Function to get the session identifier for the current request
	**/	
	private function getSessionId()
	{
	    // start the session
	    session_start();
	    
	    // get the session id
	    $id =  session_id();
	    
	    //destroy the session data
	    session_destroy();
	    
	    return $id;
	}
	
	/**
	* Function to get the count of downloads happened for the given user, same course and same user session
	**/	
	private function getPreviousDownloadCountForSession( $userId, $courseId, $sessionId )
	{
	    $this->load->model('listing/coursemodel');
	    $courseModel = new coursemodel();
	    $count = $courseModel->getPreviousDownloadCount($userId, $courseId, $sessionId );
	    
	    if( empty($count) )
		return 0;
	    
	    return $count;
	}
	
	private function _populateCoursesListForBrochureData(& $displayData, $course, $fromInstitutePageFlag = 0)
	{
	    if(!$fromInstitutePageFlag)
	    {
		$paidCourses = $displayData['courses'];
		$freeCourses = $displayData['freeCourses'];
	    }
	    else
	    {
		$paidCourses = $displayData['paid_courses'];
		$freeCourses = $displayData['free_courses'];
	    }
		// commenting below code so that default location is not picked from right side widget on listing page & user gets to select one
		//if($_REQUEST['city'])
		//{
		//	$request = new CategoryPageRequest();
		//	$request->setData(array(
		//					'cityId' => $_REQUEST['city'],
		//					'localityId' => $_REQUEST['locality']
		//					));
		//}
		$coursesListData 		= array();
		$localityArray 			= array();
		$freeCoursesIdArr		= array(0,-1);
		$isBrochureExistsForCourse	= array();
		$national_course_lib 		= $this->load->library('listing/NationalCourseLib');
		$course_ids_array 		= array();
		$courses_with_brochure  	= array();
		
		// get all course-ids
		foreach($paidCourses as $c){
		    $course_ids_array[] = $c->getId();
		}
		foreach($freeCourses as $c){
		    $course_ids_array[] = $c->getId();
		}

		// get the brochure links of the courses
		if(!empty($course_ids_array)) {
		    $courses_with_brochure = $national_course_lib->getMultipleCoursesBrochure($course_ids_array,$displayData['institute']);
		}
		
		foreach($courses_with_brochure as $cid => $brochureUrl) {
		    $x = explode('.', $brochureUrl);
		    $extension = strtolower(end($x));
		    $brochurePDFExtArray[] = 0;
		    if($extension == "pdf") {
			$brochurePDFExtArray[] = $cid;
		    }
		}
		
		$allCourseId = array();
		// get all paid courses
		foreach($paidCourses as $c){
			$coursesListData[$c->getId()] = $c->getName();
			$c->setCurrentLocations($request,true);
			$allCourseId[] = $c->getId();
			//$localityArray[$c->getId()] = getLocationsCityWise($c->getCurrentLocations());
			
			if(array_key_exists($c->getId(), $courses_with_brochure))
			    $isBrochureExistsForCourse[] = $c->getId();
			
		}
				
		// get only those free courses that have brochure 
		foreach($freeCourses as $c){
			// for free courses check if that course have brochure or not
			if(array_key_exists($c->getId(), $courses_with_brochure))
			{
			    $coursesListData[$c->getId()] 	= $c->getName();
			    $c->setCurrentLocations($request,true);
			    $allCourseId[] = $c->getId();
			    //$localityArray[$c->getId()] 	= getLocationsCityWise($c->getCurrentLocations());
			    $freeCoursesIdArr[]			= $c->getId();
			    
			    $isBrochureExistsForCourse[] = $c->getId();
			}
		}

		
		$listingebrochuregenerator = $this->load->library('ListingEbrochureGenerator');

			$localityArray = array(); 
	
	
                $courseIds = array_keys($coursesListData);
                $dominantDesiredCourseData = $national_course_lib->getDominantDesiredCourseForClientCourses($courseIds);
                foreach ($dominantDesiredCourseData as $key => $value) {
                    $dominantDesiredCourseData[$key]['name'] = $coursesListData[$key];
                }
                $displayData['instituteCoursesLPR']                = $dominantDesiredCourseData;
                $displayData['defaultCourse']                   = $dominantDesiredCourseData[$course->getId()]['desiredCourse'];
                $displayData['defaultCategory']                 = $dominantDesiredCourseData[$course->getId()]['categoryId'];
                $displayData['defaultCourseId']                 = ($course->getId() > 0)?$course->getId():$courseIds[0];
                $displayData['courseIdSelected']                = $course->getId();
		$displayData['brochurePDFExtArray'] 		= $brochurePDFExtArray;
		$displayData['courseListForBrochure'] 		= $coursesListData;
		$displayData['localityArray']			= $localityArray;
		$displayData['multiLocationCourses']   = json_encode($listingebrochuregenerator->getMultilocationsForInstitute($allCourseId));
		$displayData['freeCoursesHavingIds']		= $freeCoursesIdArr;
		$displayData['isBrochureExistsForCourse'] 	= $isBrochureExistsForCourse;
		
	}

	/**
		* Function to get load all the institute questions in related ques widget.
		* Function also handles the ajax call after user click on load more questions in widget.
		* @author Rahul
	**/
	function getInstituteRelatedQuestions($instituteId, $javascriptEnabled = 1 ) {

                $this->_init();

                $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;

                // gets the ajax params
                $pageNo = isset($_POST['page_no'])?$this->input->post('page_no', true):0;
                $callType = isset($_POST['callType'])?$this->input->post('callType', true):'';
                $instituteId = isset($_POST['institute_id'])?$this->input->post('institute_id', true):$instituteId;
                $javascriptEnabled = isset($_POST['js_enabled'])?$this->input->post('js_enabled', true):$javascriptEnabled;

		$pageSize = 4 ;
		
		$questionsData = array();
                $this->load->model('qnAmodel');
                $this->qnamodel = new QnAModel();
		$pageStart = $pageNo * $pageSize;
		$questionsData = $this->qnamodel->getQuestionsForInstitute($instituteId,$pageStart,$pageSize);

		$displayData = array();
		$displayData['total'] = $questionsData['total'];
		$displayData['data'] = $questionsData['data'];
		$displayData['instituteId'] = $instituteId;
		$displayData['js_enabled'] = $javascriptEnabled;

			
                if($callType=='Ajax'){
                        echo $this->load->view('listing/national/widgets/related_ques_widget_inner',$displayData);
                }else {
                        echo $this->load->view('listing/national/widgets/related_ques_widget',$displayData);
                }

		
	}
	
	/**
	 * Function added to call it from other modules
	 * added by Rahul
	 */
	public function makeCourseBreadCrum($displayData, $institute,$course,$pageType = 'institute' , $sourcePage =  ''){
			
		if($sourcePage == 'questionDetailPage'){
			$this->national_course_lib 	= $this->load->library('listing/NationalCourseLib');
			$this->load->builder('CategoryBuilder','categoryList');
			$categoryBuilder = new CategoryBuilder;
			$this->categoryRepository = $categoryBuilder->getCategoryRepository();
		}	
		$currentLocation = $displayData['currentLocation'];
		$countryId = $currentLocation->getCountry()->getId();
		$cityId = ($countryId==2)?$currentLocation->getCity()->getId():0;
		 
		$this->load->library('coursepages/coursePagesUrlRequest');
		 
		$requestURL = new CategoryPageRequest();
		 
		$crumb = array();
		$arrIndx = 0;
		 
		if($pageType == 'institute'){
			/*
			 *	INSTITUTES BREADCRUMBS..
			*/
	
			$crumb[$arrIndx]['name'] = "Home";
			$crumb[$arrIndx]['url'] = SHIKSHA_HOME;
			$arrIndx += 1;
			if($countryId != 2){
				$requestURL->setData(array('countryId' => $countryId));
				$crumb[$arrIndx]['name'] = $institute->getMainLocation()->getCountry()->getName();
				$crumb[$arrIndx]['url'] = $requestURL->getURL();
				$arrIndx += 1;
			}
			$crumb[$arrIndx]['name'] = $institute->getName();
			$crumb[$arrIndx]['url'] = "";
			$arrIndx += 1;
	
		}else{
			/*
			 *	COURSE BREADCRUMBS..
			*/
	
			$crumb[$arrIndx]['name'] = "Home";
			$crumb[$arrIndx]['url'] = SHIKSHA_HOME;
			$arrIndx += 1;
	
			/*
			 * Lets get the dominant Subcat and then its Parent Category Object of the Course..
			*/
			$subCategory = $this->national_course_lib->getDominantSubCategoryForCourse($course->getId(),$displayData['categorylistByCourse'][$course->getId()]);
			$categoryObj = $this->categoryRepository->find($subCategory['dominant']);
			$parentCatObj = $this->categoryRepository->find($categoryObj->getParentId());
	
			if($categoryObj->getId() == 23 or $categoryObj->getId() == 56){
				$requestURL->setNewURLFlag(1);
			}
	
			if($countryId != 2){
				$crumb[$arrIndx]['name'] = $currentLocation->getCountry()->getName();
				$requestURL->setData(array('countryId' => $countryId));
				$crumb[$arrIndx]['url'] = $requestURL->getURL();
				$arrIndx += 1;
			}
			 
			/*
			 * Lets see if Course Pages exists for this Subcategory..
			*/
			global $COURSE_PAGES_SUB_CAT_ARRAY;
			if(array_key_exists($categoryObj->getId(), $COURSE_PAGES_SUB_CAT_ARRAY) && $parentCatObj->getId() != 14) {
				$crumb[$arrIndx]['name']= $COURSE_PAGES_SUB_CAT_ARRAY[$categoryObj->getId()]["Name"];
				$coursePagesUrlRequest = new CoursePagesUrlRequest();
				$crumb[$arrIndx]['url'] = $coursePagesUrlRequest->getHomeTabUrl($categoryObj->getId());
				$arrIndx += 1;
			}else{
				if($countryId == 2 && $parentCatObj->getId() != 14){
					$crumb[$arrIndx]['name']= $parentCatObj->getShortName();
					$requestURL->setData(array('countryId' => $countryId,'categoryId' => $parentCatObj->getId()));
					$crumb[$arrIndx]['url'] = $requestURL->getURL();
					$arrIndx += 1;
				}
			}
	
			if($countryId == 2){
				$crumb[$arrIndx]['name'] = $categoryObj->getShortName()." Institutes";
				$requestURL->setData(array('categoryId' => $parentCatObj->getId(),'subCategoryId' => $categoryObj->getId(),'countryId' => $countryId,));
				$crumb[$arrIndx]['url'] = $requestURL->getURL();
				$crumb[$arrIndx]['allCityFlag'] = true;
				$arrIndx += 1;
	
				$crumb[$arrIndx]['name'] = $categoryObj->getShortName()." in ".$currentLocation->getCity()->getName();
				$requestURL->setData(array('categoryId' => $parentCatObj->getId(),'subCategoryId' => $categoryObj->getId(),'countryId' => $countryId,'cityId' => $cityId));
				$crumb[$arrIndx]['url'] = $requestURL->getURL();
				$arrIndx += 1;
				/*
				 * If it is Test Prep Course then show the Locality in the Breadcrumb as well..
				*/
				if($parentCatObj->getId() == 14 && $currentLocation->getLocality()->getName() != ""){
					$crumb[$arrIndx]['name'] = $currentLocation->getLocality()->getName();
					$requestURL->setData(array('categoryId' => $parentCatObj->getId(),'subCategoryId' => $categoryObj->getId(),'countryId' => $countryId,'cityId' => $cityId,'localityId' => $currentLocation->getLocality()->getId()));
					$crumb[$arrIndx]['url'] = $requestURL->getURL();
					$arrIndx += 1;
				}
			}
			else
			{
				$crumb[$arrIndx]['name'] = $parentCatObj->getShortName()." Colleges";
				$requestURL->setData(array('categoryId' => $parentCatObj->getId(),'countryId' => $countryId,));
				$crumb[$arrIndx]['allCityFlag'] = true;
				$crumb[$arrIndx]['url'] = $requestURL->getURL();
				$arrIndx += 1;
	
				$crumb[$arrIndx]['name'] = $categoryObj->getShortName();
				$requestURL->setData(array('categoryId' => $parentCatObj->getId(),'subCategoryId' => $categoryObj->getId(),'countryId' => $countryId));
				$crumb[$arrIndx]['url'] = $requestURL->getURL();
				$arrIndx += 1;
			}
	
			$crumb[$arrIndx]['name'] = $course->getName();  //getInstituteName();
			$crumb[$arrIndx]['url'] = "";
			$arrIndx += 1;
	
		}
		 
		return $crumb;
		 
		 
	}
	
	public function getLastModifiedDetails($listingType, $listingId) {
		$this->load->model('listing/listingmodel');
		$this->listingModelObj = new ListingModel;
		$editedData = array();
		if(!empty($listingType) && !empty($listingId)){
		     $editedData = $this->listingModelObj->getListingEditDetails($listingType, $listingId);
		}
		return $editedData;
	}
	
	/*
	 * Method to determine whether to show/hide institutes course picklist in reponse form of course detail page
	*/
	private function _setFlagForCoursePageBrochureList(& $displayData)
	{
	    // get the list of subcategories for which to exlude the courses picklist
	    global $nationalCoursesSubcatsWithoutBrochureList;
	    $showBrochureListOnCoursePageFlag = 1;
	    
	    // check for the courses subcategories and set the flag accordingly
	    if(!empty($displayData["dominantSubcatData"]["dominant"]) &&
	       in_array($displayData["dominantSubcatData"]["dominant"], $nationalCoursesSubcatsWithoutBrochureList))
	    {
		$showBrochureListOnCoursePageFlag = 0;
	    }

	    $displayData["showBrochureListOnCoursePageFlag"] = $showBrochureListOnCoursePageFlag;
	}
	
	public function isStudyAboradListing($listingTypeId = NULL, $listingType = 'course') {
		if(empty($listingTypeId)){
			return false;
		}
		$this->load->model('listing/coursemodel');
		$courseModel = new coursemodel();
		$studyAbroadListingFlag = $courseModel->isStudyAboradListing($listingTypeId, $listingType);
		return $studyAbroadListingFlag;
	}
	
	public function setVisitedListingCookieForRNRCatPage($instituteId = NULL, &$displayData) {
		$displayData['pushIntoRNRListingPageVisitedBucket'] = 'false';
		if(!empty($_SERVER['HTTP_REFERER'])){
			$httpReferer 	= $_SERVER['HTTP_REFERER'];
			$ctpgPosition 	= strpos($httpReferer, "ctpg");
			if($ctpgPosition !== false) {
				$displayData['pushIntoRNRListingPageVisitedBucket'] = 'true';
			}
		}
	}
        
        function _getUrlDataForMouseLeaveOverlay(&$displayData) {
            
            $this->load->library('Category_list_client');
            $category_list_client = new Category_list_client();
            $RNRCategoryToRankingMapping = array('23'=>array('rankingPageId' => 2, 'rankingPageName' => 'Full time MBA/PGDM'),
                                            '56'=>array('rankingPageId' => 44,'rankingPageName' => 'BE/Btech'));
            $examNames = array('23'=>array('CAT','MAT','XAT','CMAT'));
            $RNRSubcategories = array_keys($RNRCategoryToRankingMapping);
            $rankingPageId   = $RNRCategoryToRankingMapping[$displayData['pageSubcategoryId']]['rankingPageId'];
            $rankingPageName = $RNRCategoryToRankingMapping[$displayData['pageSubcategoryId']]['rankingPageName'];

            $course = $displayData['course'];
            $city = $course->getMainLocation()->getCity();
            $cityId = $city->getId();
            $stateId = $city->getStateId();

            $i = 0;
            $countryId = $displayData['currentLocation']->getCountry()->getId();
            $cityId = ($countryId==2) ? $displayData['currentLocation']->getCity()->getId() : 0;
            $RankingURLManager  = $this->load->library('ranking/RankingPageURLManager/RankingURLManager');
            $RankingPageRequest = $this->load->library('ranking/RankingPageURLManager/RankingPageRequest');
            $RankingPageRequest->setPageName($rankingPageName);
            $RankingPageRequest->setPageId($rankingPageId);
            $urlData = $RankingURLManager->buildURL($RankingPageRequest,'urltitle');
            $displayData['rankingPageUrl'][++$i]['url'] = $urlData['url'];
            $displayData['rankingPageUrl'][$i]['text'] = $urlData['title'];
            
            $RankingPageRequest = new RankingPageRequest();
            $RankingPageRequest->setPageName($rankingPageName);
            $RankingPageRequest->setPageId($rankingPageId);
            $RankingPageRequest->setStateId($stateId);
            $RankingPageRequest->setStateName($displayData['currentLocation']->getState()->getName());
            $urlData = $RankingURLManager->buildURL($RankingPageRequest,'urltitle');
            $displayData['rankingPageUrl'][++$i]['url'] = $urlData['url'];
            $displayData['rankingPageUrl'][$i]['text'] = $urlData['title'];
            
            //For state URL
            $request = new CategoryPageRequest();
            $request->setNewURLFlag(1);
            $URLdata = array('stateId'      => $stateId,
                             'countryId'    => $countryId,
                             'subCategoryId'=> $displayData['pageSubcategoryId'],
                             'categoryId'   => $displayData['mainCategoryIdsOnPage'][0]);
            $request->setData($URLdata);
            if($category_list_client->isCategoryPageEmpty($request)) {
                $displayData['categoryPageUrl'][++$i]['url'] = $request->getURL();
                $displayData['categoryPageUrl'][$i]['text'] = $request->getCategoryPageUrlText();
            }
            
            //For city URL
            $request = new CategoryPageRequest();
            $request->setNewURLFlag(1);
            $URLdata = array('stateId'      => $stateId,
                             'countryId'    => $countryId,
                             'cityId'       => $cityId,
                             'subCategoryId'=> $displayData['pageSubcategoryId'],
                             'categoryId'   => $displayData['mainCategoryIdsOnPage'][0]);
            $request->setData($URLdata);
            if($category_list_client->isCategoryPageEmpty($request)) {
                $urlText = $request->getCategoryPageUrlText();
                if($displayData['categoryPageUrl'][3]['text'] != $urlText) {
                $displayData['categoryPageUrl'][++$i]['url'] = $request->getURL();
                $displayData['categoryPageUrl'][$i]['text'] = $request->getCategoryPageUrlText();                    
                }
            }
            
            //for exams URL
            foreach($examNames[$displayData['pageSubcategoryId']] as $examName) {
                $request = new CategoryPageRequest();
                $request->setNewURLFlag(1);
                $URLdata = array('countryId'    => $countryId,
                                'examName'    => $examName,
                                'subCategoryId'=> $displayData['pageSubcategoryId'],
                                'categoryId'   => $displayData['mainCategoryIdsOnPage'][0]);
                $request->setData($URLdata);
                if($category_list_client->isCategoryPageEmpty($request)) {
                    $displayData['categoryPageUrl'][++$i]['url'] = $request->getURL();
                    $displayData['categoryPageUrl'][$i]['text'] = $request->getCategoryPageUrlText();
                }
            }
            
            //condition for adding tier 1 institute
            if($city->getTier() != 1) {
                $this->load->builder('LocationBuilder','location');
                $locationBuilder    = new LocationBuilder;
                $locationRepository = $locationBuilder->getLocationRepository();
                $tier1cities = $locationRepository->getCitiesByMultipleTiers(array('1','2'),2);
                $citiesOfCurrentInstituteState = array();
                foreach($tier1cities[1] as $tier=>$tier1City) {
                    if($tier1City->getStateId() == $stateId) {
                        $request = new CategoryPageRequest();
                        $request->setNewURLFlag(1);
                        $URLdata = array($filterRegionName => $filterRegionId,
                                        'stateId'      => $tier1City->getStateId(),
                                        'countryId'    => $countryId,
                                        'cityId'       => $tier1City->getId(),
                                        'LDBCourseId'  => $displayData['defaultCourseId'],
                                        'subCategoryId'=> $displayData['pageSubcategoryId'],
                                        'categoryId'   => $displayData['mainCategoryIdsOnPage'][0]);
                       $request->setData($URLdata);
                       if($category_list_client->isCategoryPageEmpty($request)) {
                            $displayData['categoryPageUrl'][++$i]['url'] = $request->getURL();
                            $displayData['categoryPageUrl'][$i]['text'] = $request->getCategoryPageUrlText();
                        }
                    }
                }
            }
        }
	
	function showOnlineFormRecommendations($courseId,$domainSubCatId, $type){
		/* Adding XSS cleaning (Nikita) */
		$courseId = $this->security->xss_clean($courseId);
		$domainSubCatId = $this->security->xss_clean($domainSubCatId);
		
		$this->load->model("Online/onlineparentmodel");
		$this->onlineModel = $this->load->model("Online/onlinemodel");
		$this->load->helper('listing/listing');
		$this->load->helper('string');
		$this->load->library("Online/OnlineFormUtilityLib");
		$this->load->library('listing/NationalCourseLib');
		
		$this->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;
		$instituteRepository = $listingBuilder->getInstituteRepository();
		$courseRepository = $listingBuilder->getCourseRepository();
		$onlineFormUtilityLib = new OnlineFormUtilityLib();

		$userInfo = $this->checkUserValidation();
		$data = array();
		$data['userInfo'] = $userInfo;
		$isUserLoggedIn = false;
		$userId = 0;
		$appliedCourse = $courseRepository->find($courseId);
		// set domain Subcategory of a course page
		$data['domainSubCatId'] = $domainSubCatId;
	
		$instituteId = $appliedCourse->getInstId();
		if($userInfo != 'false')
		{
		    $isUserLoggedIn = true;
		    $userId = $userInfo[0]['userid'];
		}

		$courseInstituteMappingArr = array();
		$recommendationsApplied = array();
		if($userId)
		{
		    $courseIds = array();
            $courseInstituteMapping = $this->onlineModel->getIncompleteOnlineFormsByUserId($userId);
		   	
		   	$courseIdsBucket = array();
		    foreach($courseInstituteMapping as $row){
		    	$courseIdsBucket[] = $row['courseId'];
		    }

		    $courseSubCategoryIds = $instituteRepository->getCategoryIdsOfListing($courseIdsBucket, 'course',TRUE,TRUE);
		    
		    foreach($courseInstituteMapping as $row){

		  		//fetch domainant sub category of a course
				$dominantSubcatData              = $this->nationalcourselib->getDominantSubCategoryForCourse($row['courseId'],$courseSubCategoryIds[$row['courseId']]);
				$domainSubCatId4OnlineFormCourse = $dominantSubcatData['dominant'];

				// check if domainant subcategory of a course page is equal to online form course
				if($domainSubCatId               == $domainSubCatId4OnlineFormCourse){
				$courseIds[]                     = $row['courseId'];
				}else{
				// remove course from mapping
				unset($courseInstituteMapping[$row['courseId']]);
				}	
			}
			
		    if($courseIds)
			$percentCompletion = $onlineFormUtilityLib->getOnlineFormStatus($courseIds, $userId);
		    
		    $data['percentCompletion']	= $percentCompletion;
		    
		    //$courseInstituteMapping = array();
		}

		if(empty($courseIds))
		{
		    // get the recommended online forms
		    $courseInstituteMapping 		= $this->getOnlineFormWidgetRecommendations($userId, $instituteId,$domainSubCatId);
		    $data['recommendationsWidgetFlag']	= 1;
		}

		$courseIds = array();
		foreach($courseInstituteMapping as $row)
		{
		    $courseInstituteMappingArr[$row['instituteId']] = $row['courseId'];
		    $courseIds[] = $row['courseId'];
		    
		    // check if brochure has already been applied or not
		    if(isset($_COOKIE['applied_'.$row['courseId']]) && $_COOKIE['applied_'.$row['courseId']] == 1) {
				$recommendationsApplied[] = $row['courseId'];
		    }
		}
		$recommendationsApplied = array_values(array_unique($recommendationsApplied));

		
		$data['brochureURL'] 			= $this->nationalcourselib->getMultipleCoursesBrochure($courseIds);
		$courses 				= array_slice($instituteRepository->findWithCourses($courseInstituteMappingArr),0,9);
		$data['recommendations'] 		= $courses;
		$data['recommendationsExist'] 		= count($courses) > 0 ? 1 : 0;
		$data['onlineFormData'] 		= $courseInstituteMapping;
		$data['numberOfRecommendations'] 	= count($courses) > 9 ? 9 : count($courses);
		if($domainSubCatId == MBA_SUBCAT_ID)
			$data['onlineFormHomePageNewUrl'] 	= SHIKSHA_HOME.'/mba/resources/application-forms';
		elseif($domainSubCatId == ENGINEERING_SUBCAT_ID)
			$data['onlineFormHomePageNewUrl'] 	= SHIKSHA_HOME.'/college-admissions-engineering-online-application-forms';

		$data['appliedCourse'] 			= $appliedCourse;
		$data['recommendationsApplied'] 	= $recommendationsApplied;
		//added by akhter
		//get dashboard config for online form
		$data['institutes_autorization_details_array'] = $this->nationalcourselib->getOnlineFormAllCourses();
		$data['widget'] 			= "OF_Request_E-Brochure";
		$data['comparetrackingPageKeyId'] = 618;
		$data['uniqId'] = random_string('alnum', 6);

		$recommendationHTML = $this->load->view('listing/national/widgets/onlineFormWidget',$data,TRUE);
		
		$response = array(
			    'recommendationHTML' => $recommendationHTML
		    );
		if($type == 'html' && $type != ''){
			echo $recommendationHTML;	
		}
		else {
			echo json_encode($response);
		}
	}
	
	function getOnlineFormWidgetRecommendations($userId, $instituteId,$domainSubCatId)
	{
	    $this->myshortlistmodel  = $this->load->model("myShortlist/myshortlistmodel");
	    $this->load->library('recommendation/alsoviewed');
	    $this->load->library('listing/NationalCourseLib');
	   	// to get all courses set based on subcategory Id
		$allCourseIdsOfSubCat = $this->nationalcourselib->getCourseIdsBySubCategoryId($domainSubCatId);
	
	    $maxRecommendations = 500;
	    
	    if(!$this->onlineModel){
		$this->load->model("Online/onlineparentmodel");
		$this->onlineModel = $this->load->model("Online/onlinemodel");
	    }

	    // initialize
	    $recommendations 		= array();
	    $recommendedCoursesIds 	= array();
	    $courseInstituteMapping	= array();
	    
	    // get all active online form course-ids
	    $activeOnlineFormCourses = $this->onlineModel->getActiveOnlineForms();
	    $activeOnlineFormCourseIds = array_keys($activeOnlineFormCourses);
	    
	    
	    // if user is not logged-in then show him all active online forms
	    //if(!empty($userId))
	    //{
		//======== Start Stage 1 : Get recommendations of courses of which user has applied online form =====
		
		// get course-ids of online forms on which user has applied
        $usersOnlineFormCourses = $this->onlineModel->getIncompleteOnlineFormsByUserId($userId, array('started', 'uncompleted', 'completed','Payment Confirmed'));

		$usersOnlineFormCourses = array_keys($usersOnlineFormCourses);
    
		if($usersOnlineFormCourses)
		    $recommendations = $this->alsoviewed->getAlsoViewedListings($usersOnlineFormCourses, $maxRecommendations, array($instituteId));
		// gather course-ids
		foreach($recommendations as $row)
		{
		    if(in_array($row[1],$activeOnlineFormCourseIds) && in_array($row[1], $allCourseIdsOfSubCat))
		    {
			$recommendedCoursesIds[] = $row[1];
			$courseInstituteMapping[$row[1]] = array('instituteId' => $row[0], 'courseId' => $row[1]);
		    }
		}
		// sort courses in accordance of application deadline 
		$recommendedCoursesIds = array_intersect($activeOnlineFormCourseIds, $recommendedCoursesIds);
		
		//========== End of Stage 1
		
		//======== Start Fallback condition 2 : Get recommendation from courses on which has made non-viewed response =====
		if(empty($courseInstituteMapping))
		{
		    $courseInstituteMapping = array();
		    $userResponseCourses = array();
		    // get courses on which user has made non-viewed response
			if($userId)
		    $userResponseCourses = $this->myshortlistmodel->getCoursesOfResponses($userId, '', array("Viewed_listing"));
		    if($userResponseCourses)
			$recommendations = $this->alsoviewed->getAlsoViewedListings($userResponseCourses, $maxRecommendations, array($instituteId));
		    // gather course-ids
		    foreach($recommendations as $row)
		    {
			if(in_array($row[1],$activeOnlineFormCourseIds) && in_array($row[1], $allCourseIdsOfSubCat))
			{
			    $recommendedCoursesIds[] = $row[1];
			    $courseInstituteMapping[$row[1]] = array('instituteId' => $row[0], 'courseId' => $row[1]);
			}
		    }
		    // sort courses in accordance of application deadline 
		    $recommendedCoursesIds = array_intersect($activeOnlineFormCourseIds, $recommendedCoursesIds);
		}
		//========== End of Fallback condition 2
	    
	    //}
	    
	    //======== Start Fallback condition 3 : Get all active online forms as recommendations =====
	    if(empty($courseInstituteMapping))
	    {
		$courseInstituteMapping = array();
		foreach($activeOnlineFormCourses as $cId=>$instId)
		{
		    if($instId != $instituteId)
		    {
			$recommendedCoursesIds[] = $cId;
			$courseInstituteMapping[$cId] = array('instituteId' => $instId, 'courseId' => $cId);
		    }
		}
		// sort courses in accordance of application deadline 
		$recommendedCoursesIds = array_intersect($activeOnlineFormCourseIds, $recommendedCoursesIds);
	    }
	    
	    // arrange the courses in the sorted order
	    $finalCourseInstituteMapping = array();
	    foreach($recommendedCoursesIds as $cId)
	    {
	    	if(in_array($cId, $allCourseIdsOfSubCat)){
				$finalCourseInstituteMapping[] = $courseInstituteMapping[$cId];
			}
	    }
	    
	    //========== End of Fallback condition 3
	    return $finalCourseInstituteMapping;

	}

	/**
	 * validating and redirection code of  listing page
	 * @author Aman Varshney <aman.varshney@shiksha.com>
	 * @date   2015-03-03
	 * @param  Integer     $typeId listing type Id
	 * @param  String      $type   listing type
	 * @return Array       Contains institute Id, course Id and Course Object
	 */
	private function _redirectListingPage($typeId,$type){

		$course_id    = "";
		$institute_id = "";
		$course       = "";
		if($type == 'course'){
			$course_id       = $typeId;
			$course          = $this->courseRepository->find($course_id);
			if($course instanceof AbroadCourse)
			{
			show_404(); die;
			}
			
			//if course doesn't exist anymore
			$tempCourseId    = $course->getId();
			if(empty($course) || empty($tempCourseId)) {
			$newCourseId     = $this->courseRepository->getRedirectionIdForDeletedCourse($course_id, "course");
			if(!empty($newCourseId)) {
			$course          = $this->courseRepository->find($newCourseId);
			$course->getId() == "" ? show_404() : redirect($course->getUrl(), 'location', 301);
			exit();
			} else {
			// No need for this check to handle migrated abroad courses
			//show_404(); exit();redirectAbroadCourse
			}
			}
			
			$institute_id    = $course->getInstId();
			$this->_validateListingURL($course);
			// $institute_id = $this->ListingClientObj->getInstituteIdForCourseId(1,$course_id);
		}else{
			$institute_id = $typeId;
			$instObj      = $this->instituteRepository->find($institute_id);
			$entered_url  = $_SERVER["REQUEST_URI"];
			if($institute_id == 38359 && $entered_url == "/Asia-Pacific-Flight-Training-Acadmey-Shamshabad-Hyderabad-institute-college-listingoverviewtab-38359") {
                    redirect($instObj->getUrl(), 'location', 301);
            }
			if($instObj instanceof AbroadInstitute) {
			show_404(); die;
			}
			$this->_validateListingURL($instObj);
			// change for memory optimization
			unset($instObj);
		}

		return array('institute_id' => $institute_id, 
		             'course_id'    => $course_id,
		             'courseObj'    => $course);
	}

	private function _intInstitute(& $displayData,$institute,$course){
		$institute_id                               = $typeId = $institute->getId();
		//$type                                       = 'institute';
		$displayData['institute']                   = $institute;
		$displayData['instituteId']                 = $institute->getId();
		$displayData['instituteName']               = $institute->getName();
		$displayData['institute']->instituteCourses = $this->instituteRepository->getCategoryIdsOfListing($institute_id,'institute');
		// start : change 3 : decreased memomory usages by 20 MB by passing Institue object insteadof passing id and loading object later on.
		$displayData['instituteComplete']           = $this->instituteRepository->findInstituteWithValueObjects($institute_id,array('description','joinreason'), $displayData['institute']);
		// end : change 3
		
		$displayData['course']                      = $course;
		//$displayData['pageType']                    = $type;
		$displayData['typeId']                      = $typeId;
		$displayData['tab']                         = 'overview';

		$displayData['pageTypeForGATracking']       = 'INSTITUTE';

	}

	private function _fetchCourseAndAlumniReviewsData(& $displayData,$institute,$institute_id){
		$instituteCourseList       = $displayData['institute_course_list'];
		$categorylistByCourse	   = $displayData['categorylistByCourse'];

		$displayData['courseReviews']                      = $this->national_course_lib->getCourseReviewsData($instituteCourseList);
		$displayData['courseReviews']         			   = $this->national_course_lib->getCollegeReviewsByCriteria($displayData['courseReviews']);
		$displayData['showAlumniReviewsSection']           = false;
		if(count($displayData['courseReviews']) > 0) {
			foreach($displayData['courseReviews'] as $courseId =>$reviewData) {
				$dominantSubcategoryData = $this->national_course_lib->getDominantSubCategoryForCourse($institute->getCourse($courseId), $categorylistByCourse[$courseId]);

				if($this->_checkMBATemplateEligibility($dominantSubcategoryData['subcategory_ids'], $institute->getCourse($courseId)) || $this->_checkEngTemplateEligibility($dominantSubcategoryData['subcategory_ids']) || $this->_checkForCollegeReviewTemplateEligibility($dominantSubcategoryData['subcategory_ids']) ) {
					if($reviewData['overallRating'] > 0) {
						//$displayData['showCourseReviews'][$courseId]       = $reviewData['overallRating']; changed for story LDB -2747,dynamic loading rating params
						$displayData['showCourseReviews'][$courseId]['overallAverageRating'] = $reviewData['overallAverageRating'];
						$displayData['showCourseReviews'][$courseId]['ratingParamCount'] = $reviewData['ratingCount'];
					}
					$displayData['showAlumniReviewsSection']           = false;
				}
			}
		}



		/* Alumni Reviews STARTS */
        if($displayData['showAlumniReviewsSection']) {
            $ListingCache = $this->load->library('listing/cache/ListingCache');
            $reviews = array();
            //fetching from cache
            $reviews = $ListingCache->getNonMbaAlumniReviewsOfInstitute($institute_id);
            if(empty($reviews)) {
                $this->load->library('alumni/AlumniReviewsLibrary');
                $alumniReviewsLib = new AlumniReviewsLibrary();
                $reviews = $alumniReviewsLib->getAlumniRatingsForInstitute($institute_id);
                //storing in cache
                $ListingCache->storeNonMbaAlumniReviewsOfInstitute($institute_id,$reviews);  
            }
            $displayData['reviews'] = $reviews;
        }
        /* Alumni Reviews ENDS */

	}

	private function _intCourses(& $displayData,$institute,$course){
		$institute_id                               = $institute->getId();
		$course_id                                  = $course->getId();
		$institute_name                             = $institute->getName();
		$institute_url                              = $institute->getUrl();
	
		$displayData['institute_name']              = $institute_name;
		$displayData['institute_url']               = $institute_url;
		$displayData['institute']                   = $institute;
		$displayData['institute']->instituteCourses = $this->instituteRepository->getCategoryIdsOfListing($institute_id,'institute');
		$displayData['brochureURL']                 = $this->national_course_lib;
		$displayData['course']                      = $course;
		$displayData['tab']                         = 'overview';
		if($course->isPaid()) {
		$displayData['courseType']                  = 'paidCourse';
		} else {
		$displayData['courseType']                  = 'freeCourse';
		}    

		$displayData['typeId']                      = $course_id;
		$displayData['pageTypeForGATracking']       = 'COURSE';

	}

	private function _toSetOTPANDODBVerification(& $displayData){
		/*
		 * OTP/OBD User Authentication
		 */
		$OTPVerification = 0;
		$ODBVerification = 0;
		if($displayData['validateuser'] !== false) {
		    global $OTPCourses;
		    global $ODBCourses;
		    
		    if($course_id > 0) {
			if($OTPCourses[$course_id]) {
			    $OTPVerification = 1;
			}
			else if($ODBCourses[$course_id]) {
			    $ODBVerification = 1;
			}
		    }
		}
		$displayData['OTPVerification'] = $OTPVerification;
		$displayData['ODBVerification'] = $ODBVerification;
	}

	function _identitifyCoursePageCategory($courseCategory,$course){
		if($this->_checkMBATemplateEligibility($courseCategory, $course)){
			return 'MBA_PAGE';
		}elseif(in_array(ENGINEERING_SUBCAT_ID, $courseCategory)){
			return 'ENGINEERING_PAGE';
		}
	}
	
	function sortCollegeReviews($courseId,$criteria) {
		$this->load->builder('ListingBuilder','listing');
		$this->load->helper('listing/listing');
		$listingBuilder 			  = new ListingBuilder;
		$this->courseRepository       = $listingBuilder->getCourseRepository();
		$this->national_course_lib 	  = $this->load->library('listing/NationalCourseLib');
		$displayData['courseReviews'] = $this->national_course_lib->getCourseReviewsData(array($courseId));
		$displayData['courseReviews'][$courseId]['reviews'] = $this->national_course_lib->sortCollegeReviews($displayData['courseReviews'][$courseId]['reviews'],$criteria);
		$displayData['view'] = 'default';
		$displayData['share'] = array('facebook','twitter','linkedin','google');
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
		
		echo $this->load->view('listing/national/widgets/courseReviewsContent',array('courseReviews' => $displayData['courseReviews'],'course' => $this->courseRepository->find($courseId),'view' => $displayData['view'],'share' => $displayData['share'],'subTitle' => $displayData['subTitle'],'userData' => $displayData['userData'],'validateuser' => $displayData['validateuser'],'institute_name' => $displayData['institute_name']),true); 
		exit;
	}

	/**
	Author : Virender Singh
	Task : Move Request E-brochure on Ajax on Course page bottom section
	Input : Course Id
	*/
	public function loadREBFormInCoursePageMiddle($courseId){
		if($courseId != 'undefined' && $courseId != '' && $courseId > 0){
			$displayData = array();
			$this->load->builder('ListingBuilder','listing');
			$listingBuilder = new ListingBuilder;
			$this->courseRepository = $listingBuilder->getCourseRepository();
			$displayData['course'] = $this->courseRepository->find($courseId);
			if($displayData['course'] instanceof AbroadCourse){
				die('noCourse');
			}
			$instituteId = $displayData['course']->getInstId();
			$this->instituteRepository = $listingBuilder->getInstituteRepository();
			if($instituteId!='' && $instituteId!=0 && $instituteId>0){
				$displayData['institute'] = $this->instituteRepository->find($instituteId);
				if($displayData['institute'] instanceof AbroadInstitute){
					die('noCourse');
				}
			}else{
				die('noCourse');
			}
			$displayData['pageType'] = 'course';
			$displayData['validateuser'] = $this->checkUserValidation();
			$this->national_course_lib = $this->load->library('listing/NationalCourseLib');
			$displayData['collegeOrInstituteRNR'] = 'institute';
			$categoryIds = $this->national_course_lib->getCourseInstituteCategoryId($courseId,'course');
			if(count(array_intersect($categoryIds, array("2", "3"))) != 0) {
				$displayData['collegeOrInstituteRNR'] = 'college';
			}
			$course_reb_url = $this->national_course_lib->getCourseBrochure($displayData['course']);
			$this->_toSetOTPANDODBVerification($displayData);
			
			$dominantDesiredCourseData = $this->national_course_lib->getDominantDesiredCourseForClientCourses(array($courseId));
            $displayData['courseIdSelected'] = $courseId;
            $displayData['defaultCourse'] = $dominantDesiredCourseData[$courseId]['desiredCourse'];
            $displayData['defaultCategory'] = $dominantDesiredCourseData[$courseId]['categoryId'];
            $displayData['course_reb_url'] = $course_reb_url;
            echo $this->load->view('national/widgets/response_bottom', $displayData);
		}else{
			die('noCourse');
		}
	}

	public function filterAbroadExamData($examArr, $courseId){
		$examNames = array();
		foreach ($examArr as $examObj) {
			$examNames[] = $examObj->getAcronym();
		}
		if(!empty($examNames))
		{
			$cacheLib = $this->load->library('cacheLib');
			$abroadExamData = $cacheLib->get('abroadExamsListForCoursePage_'.$courseId);
			if($abroadExamData == 'ERROR_READING_CACHE'){
				$saContentLib = $this->load->library('blogs/saContentLib');
				$abroadExamData = $saContentLib->getSAExamHomePageURLByExamNames($examNames);
				$cacheLib->store('abroadExamsListForCoursePage_'.$courseId, $abroadExamData, -1);
				if(isset($abroadExamData['error'])){
					return false;
				}else{
					return $abroadExamData;
				}
			}else{
				return $abroadExamData;
			}
		}
		return false;
	}

}
