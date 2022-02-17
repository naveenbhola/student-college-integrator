<?php
/**
 Main Controller class for rendering Listing detail page for mobile.
 **/
class Listing_mobile extends ShikshaMobileWebSite_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library(array('listing_client','alumniSpeakClient'));
		$this->load->builder('ListingBuilder','listing');
		$this->load->helper('mailalert');
		$this->load->config('mcommon/mobi_config');
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
	}

	function listingDetailWap($listing_id,$type){
		if(($type !='course' && $type != 'institute') || !is_numeric($listing_id))
		{
			show_404();
		}

		//Redirect old listing to new listing page
		if(!empty($typeId) && $type = 'institute'){
			$this->load->builder("nationalInstitute/InstituteBuilder");
	        $instituteBuilder = new InstituteBuilder();
	        $instituteRepo = $instituteBuilder->getInstituteRepository();
	        $instituteObj = $instituteRepo->find($typeId,array('basic'));
	        $seoUrl = $instituteObj->getURL();
	        if(!empty($seoUrl)){
	        	header("Location: $seoUrl",TRUE,301);
                exit;
	        }
	        
		}
		
		$displayData = array();

		if($type == 'institute'){
			$displayData['institute_id'] = $listing_id;
			$displayData['tracking_url'] = $this->getTrackingURL();
			$this->getBasicInstituteDetails($displayData);
		}
		else{
			$displayData['course_id'] = $listing_id;
			$displayData['tracking_url'] = $this->getTrackingURL();
			//echo $listing_id."_".$type."_".$displayData['tracking_url'];exit;
			$this->getBasicCourseDetails($displayData);
		}

		// 301 redirection in case of tampered URL
		if($type == 'course'){
			$this->_validateListingsURL($displayData['course']);
		}

		$this->populateAdditionalInfo($displayData);
		$displayData['boomr_pageid'] = "listing_detail";

                $this->load->view('mobileListing',$displayData);

                //If user is logged-in and this is a paid course, create a Viewed Type Response for this user
                if($displayData['course']->isPaid() && isset($displayData['validateuser'][0]['userid']) && $type != 'institute' && !(in_array($displayData['validateuser'][0]['usergroup'],array("enterprise","cms","experts","sums"))) && ($displayData['validateuser'][0]['mobile'] != "")){
                        $addReqInfo['listing_type_id'] = $listing_id;
                        $addReqInfo['listing_type'] = 'course';
                        $addReqInfo['preferred_city'] = $displayData['currentLocation']->getCity()->getId();
                        $addReqInfo['preferred_locality'] = $displayData['currentLocation']->getLocality()->getId();
                        $this->addViewedLead($addReqInfo,$displayData['validateuser']);
                }

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


	function getTrackingURL() {

		$query_array = array();
		$url = "";
		$result_search_id = $this->input->get_post('result_search_id',true);
		if($result_search_id >0) {
			$base_url = SHIKSHA_HOME.'/searchmatrix/SearchMatrix/updateLogResultClickedStatusById';
			foreach($_GET as $key=>$value) {
				if(!in_array($key,array('city','locality'))) {
					$query_array[] = $key."=".$this->input->get_post($key,true);
				}
			}
			$url = $base_url."?".implode('&',$query_array);
		}
		return $url;
	}
	function getBasicInstituteDetails(&$displayData){

		$listingBuilder = new ListingBuilder;
		$instituteRepository = $listingBuilder->getInstituteRepository();
		$ListingClientObj = new Listing_client();
		$type = 'institute';
		$appId=1;
		$institute_id = $displayData['institute_id'];
		$this->_init($displayData,$institute_id,'institute');

		if($institute_id){
			$coursesArray = $this->instituteRepository->getLocationwiseCourseListForInstitute($institute_id);
		}
		$this->redirectAbroadInstitute($institute_id, "");
		$courseList = array();
		foreach($coursesArray as $course){
			if((($_REQUEST['city'] == $course['city_id']) || !($_REQUEST['city']))
			&& (($_REQUEST['locality'] == $course['locality_id']) || !($_REQUEST['locality']) || $_REQUEST['locality'] == 'All')){
				$courseList = array_merge($courseList,$course['courselist']);
			}
		}
		if(count($courseList) == 0){
			$newInstituteId  = $this->instituteRepository->getRedirectionIdForDeletedInstitute($institute_id,"institute");
			if(!$newInstituteId){
				$error = '$this->instituteRepository->getRedirectionIdForDeletedInstitute($institute_id,"institute")';
				$function = "did not found redirect institute for institute id " . $institute_id;
				//sendMailAlert("data not coming from backend issue in".$error."in".$function,"Listing mobile controller Issue","vikas.k@shiksha.com");
				show_404();
				exit;
			}else{
				header( "HTTP/1.1 301 Moved Permanently" );
				header( "Location:  ".getSeoUrl($newInstituteId,"institute") );
				exit;
			}
		}
		$courseList =array_unique($courseList);
		if(empty($courseList))
		{
			$error = '$ListingClientObj->getCourseList($appId,$institute_id,$status="live",$userId=\'\')';
			$function = "getBasicInstituteDetails function in Listing_mobile Controller";
			show_404();
			sendMailAlert("data not coming from backend issue in".$error."in".$function,"Listing mobile controller Issue","vikas.k@shiksha.com");
		}

		if($courseList){
			$institute = reset($this->instituteRepository->findWithCourses(array($institute_id => $courseList)));
		}else{
			$institute = $this->instituteRepository->find($institute_id);
		}
		$course=$institute->getFlagshipCourse();

		$displayData['pageType'] = 'institute';
		$displayData['institute'] = $institute;
		$displayData['course'] = $course;
		$displayData['type'] = $type;
		$displayData['course_id'] =  $course->getId();
		$displayData['details'] = $this->getContactInfo($appId,$institute_id,$type);
		$this->getSeoTagsForListing($displayData);
	}
	function getBasicCourseDetails(&$displayData){
		$listingBuilder = new ListingBuilder;
		$instituteRepository = $listingBuilder->getInstituteRepository();
		$ListingClientObj = new Listing_client();
		$course_id = $displayData['course_id'];
		$appId =1;
		$type = 'course';
		$this->_init($displayData,$course_id,'course');
		$institute_id = $ListingClientObj->getInstituteIdForCourseId($appId,$course_id);
		$this->redirectAbroadCourse($institute_id, $course_id);
		if(!$institute_id)
		{
			error_log("institute not found. throwing 404...");
			show_404();
		}
		if($institute_id){
			$coursesArray = $this->instituteRepository->getLocationwiseCourseListForInstitute($institute_id);
		}
		$courseList = array();
		foreach($coursesArray as $course){
			if((($_REQUEST['city'] == $course['city_id']) || !($_REQUEST['city']))
			&& (($_REQUEST['locality'] == $course['locality_id']) || !($_REQUEST['locality']) || $_REQUEST['locality'] == 'All')){
				$courseList = array_merge($courseList,$course['courselist']);
			}
		}

		if(count($courseList) == 0){
			$newInstituteId  = $this->instituteRepository->getRedirectionIdForDeletedInstitute($institute_id,"institute");
			if(!$newInstituteId){
				show_404();
			}else{
				header( "HTTP/1.1 301 Moved Permanently" );
				header( "Location:  ".getSeoUrl($newInstituteId,"institute") );
				exit;
			}
		}
		$courseList =array_unique($courseList);
		if(empty($courseList))
		{
			$error = '$ListingClientObj->getCourseList($appId,$institute_id, $status = "live", $userId = \'\')';
			$function = "getBasicCourseDetails function in Listing_mobile controller";
			sendMailAlert("data not coming from backend issue in ".$error."in".$function,"Listing mobile controller Issue","vikas.k@shiksha.com");
		}
		if($courseList){
			$institute = reset($this->instituteRepository->findWithCourses(array($institute_id => $courseList)));
		}else{
			$institute = $this->instituteRepository->find($institute_id);
		}
		$courses = $institute->getCourses();

		foreach($courses as $courseAll){
			if($courseAll->getId()==$course_id)
			{
				$course=$courseAll;
				break;
			}
		}
		$displayData['pageType'] = 'course';
		$displayData['institute']=$institute;
		$displayData['course'] = $course;
		$displayData['type'] = $type;
		$displayData['institute_id'] = $institute_id;
		$displayData['details'] = $this->getContactInfo($appId,$course_id,$type);
		$this->getSeoTagsForListing($displayData);
	}
	function populateAdditionalInfo(&$displayData){
		$institute_id = $displayData['institute_id'];
		$course_id = $displayData['course_id'];
		$type = $displayData['type'];
		$appId =1;
		$this->getAlumniSpeakData($displayData);
		$this->_populateCurrentLocation($displayData,$displayData['institute'],$displayData['course'],$displayData['pageType']);
		$this->_makeBreadCrumb($displayData,$displayData['institute'],$displayData['course'],$type);
		$displayData['whyJoin'] = $this->getReasonToJoinInstitute($institute_id);
		$displayData['establishedYearAndSeats']=$this->getEstablishYearAndSeats($appId,$institute_id,$course_id);
		$displayData['category'] = $this->getCategoryforCourseIds($course_id);
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
	function getSeoTagsForListing(&$displayData){
		$institute = $displayData['institute'];
		$institute_id = $displayData['institute_id'];
		$course = $displayData['course'];
		$type = $displayData['type'];

		$tagsDescription = get_listing_seo_tags(
		$institute->getName(),
		$institute->getMainLocation()->getLocality()?$institute->getMainLocation()->getLocality()->getName():"",
		$course?$course->getName():"",
		$institute->getMainLocation()->getCity()->getName(),
		$institute->getMainLocation()->getCountry()->getName(),
		$type,
		$institute->getAbbreviation());
		if($type == "course"){
			$metaData = $course->getMetaData();
		}else{
			$metaData = $institute->getMetaData();
		}

		if(!empty($metaData['seoTitle'])){
			$displayData['m_meta_title'] = html_escape($metaData['seoTitle']);
		}else{
			$displayData['m_meta_title'] = html_escape($tagsDescription['Title']);
		}
		if(!empty($metaData['seoDescription']) && $displayData['tab'] == 'overview'){
			$displayData['m_meta_description'] = html_escape($metaData['seoDescription']);
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
	//function to get category name for the corresponding course_id.
	function getCategoryforCourseIds($course_id){
		$this->load->builder('CategoryBuilder','categoryList');
		$categoryBuilder = new CategoryBuilder;
		$ListingClientObj = new Listing_client();
		$categoryIds=array();
		$course_id_array=array($course_id);
		$categoryIds[] = $ListingClientObj->getCategoryIdsForCourseIds($course_id_array);
		$categoryId = $categoryIds[0][0];
		$categoryRepository = $categoryBuilder->getCategoryRepository();
		$category = $categoryRepository->find($categoryId);
		return $category;
	}
	private function _populateCurrentLocation(&$displayData, $institute,$course,$pageType = 'institute'){
		if($pageType == "course"){
			$locations = $course->getLocations();
			$currentLocation = $course->getMainLocation();
		}else{
			$locations = $institute->getLocations();
			$currentLocation = $institute->getMainLocation();
		}
		foreach($locations as $location){
			$localityId = $location->getLocality()?$location->getLocality()->getId():0;
			if($_REQUEST['city'] == $location->getCity()->getId()){
				$currentLocation = $location;
				if($_REQUEST['locality'] == $localityId){
					$currentLocation = $location;
					break;
				}
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


        private function addViewedLead($addReqInfo,$signedInUser) {
                $appId = 1;

                $addReqInfo['userId'] = $signedInUser[0]['userid'];
                $addReqInfo['displayName'] = $signedInUser[0]['displayname'];
                if(!empty($signedInUser[0]['cookiestr'])) {
                    $a = $signedInUser[0]['cookiestr'];
                    $b = explode('|',$a);
                    $addReqInfo['contact_email'] = $b[0];
                }
                $addReqInfo['contact_cell'] = $signedInUser[0]['mobile'];

                $this->load->library(array('lmsLib'));
                $LmsClientObj = new LmsLib();
                $addLeadStatus = $LmsClientObj->insertTempLead($appId, $addReqInfo);
                $addReqInfo['userInfo'] = json_encode($signedInUser);
                $addReqInfo['sendMail'] = false;
                $addReqInfo['action'] = "mobile_viewedListing";
                $addReqInfo['message'] = "";
                $addReqInfo['tempLmsRequest'] = $addLeadStatus['leadId'];
                $addLeadStatus = $LmsClientObj->insertLead($appId, $addReqInfo);
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
}

?>

