<?php

class NationalCourseLib {

	private $CI;
	private $institute_repository;
	private $course_repository;
	private $ebrochure_generator;
	private $listingextendedmodel;

	function __construct() {
		$this->CI =& get_instance();
//		$this->_setDependecies();
	}

	function _setDependecies() {

		$this->CI->load->builder('ListingBuilder','listing');
		$listingbuilder = new ListingBuilder();
		$this->CI->load->builder('LDBCourseBuilder','LDB');
		$LDBBuilder = new LDBCourseBuilder();
		$this->CI->load->library('listing/cache/ListingCache');
		$this->listingcache = $this->CI->listingcache;

		
		$this->institute_repository = $listingbuilder->getInstituteRepository();
		$this->course_repository 	= $listingbuilder->getCourseRepository();
		$this->ebrochure_generator 	= $this->CI->load->library('listing/ListingEbrochureGenerator');
		$this->LDBCourseRepository 	= $LDBBuilder->getLDBCourseRepository();
		$this->listingextendedmodel = $this->CI->load->model('listing/listingextendedmodel');
	}

	public function getCourseBrochure($courseIdOrObject,$return_array = false) {
        
		if(empty($courseIdOrObject)) {
			return array();
		}

		$course_brochure_url = "";
		$generated_ebrochure_response = array();
		$this->_setDependecies();
		$generated = "";

		if($courseIdOrObject instanceof Course) {
          $course_object = $courseIdOrObject;
          $course_id = $course_object->getId();
		} else {
			$course_id = $courseIdOrObject;
			$course_object = $this->course_repository->find($course_id);
        } 

        $cid = $course_object->getId();
        $cname = $course_object->getName();
        if(empty($cid) || empty($cname)){
        	return array();
        }

		if($course_brochure_url = $course_object->getRequestBrochure()) {
			$generated = "CMS";
		} else if($course_brochure_url = $this->institute_repository->find($course_object->getInstId())->getRequestBrochure()){
			$generated = "CMS";
		} else if($generated_ebrochure_response = $this->ebrochure_generator->getEbrochureURL('course',$course_id)) {
			
			if($generated_ebrochure_response['RESPONSE'] == 'BROCHURE_FOUND') {
				$course_brochure_url = 	$generated_ebrochure_response['BROCHURE_URL'];
				$generated = "SHIKSHA";
			}
		}
		
		if($return_array) {
			return array('BROCHURE_URL'=>$course_brochure_url,'generated'=>$generated);	
		}
		
		return $course_brochure_url;
	}
	
	public function getMultipleCoursesBrochure($courses_ids = array(),$instituteWithCoursesObject = NULL) {

		$brochure_url_array = array();
		if(count($courses_ids) == 0) {
			return $brochure_url;
		}

		$this->_setDependecies();
		$course_brochure_url = "";
		$instituteIds = array();
		
		// load courses and institute objects
         if(isset($instituteWithCoursesObject)) {
            $course_object_array =  $this->_getCoursesFromInstituteObj($courses_ids,$instituteWithCoursesObject);
         } else {
		  $course_object_array = $this->course_repository->findMultiple($courses_ids);
		 }

		foreach($course_object_array as $courseObj){
                        if(is_object($courseObj) && $courseObj->getInstId()) {
				$instituteIds[] = $courseObj->getInstId();
                        } 
		}

		if(isset($instituteWithCoursesObject)) {
		   $institute_object_array[$instituteWithCoursesObject->getId()] = $instituteWithCoursesObject;
		} else {
			$institute_object_array = $this->institute_repository->findMultiple($instituteIds);
		}

		foreach ($courses_ids as $course_id) {
				
			$course_object = $course_object_array[$course_id];//$this->course_repository->find($course_id);
			if($course_object instanceof Course) {
			if($course_object->getInstId()=="" || $course_object->getInstId()==0){
				continue;
			}
			$institute_id = $course_object->getInstId();
			if($course_brochure_url = $course_object->getRequestBrochure()) {
				//
			} else if(isset($institute_id) && $institute_object_array[$institute_id] instanceOf Institute && $course_brochure_url = $institute_object_array[$institute_id]->getRequestBrochure()){
				//
			}
				
			if(!empty($course_brochure_url)) {
				$brochure_url_array[$course_id] = $course_brochure_url;
			}
			}
		}

		$exclustion_list = array();
		$exclustion_list = array_diff($courses_ids, array_keys($brochure_url_array));

		if(count($exclustion_list)>0) {
			$startTime = microtime(true);
			$db_brochure = $this->listingextendedmodel->getMultipleListingsEBrochureInfo($exclustion_list,'course');
			if(EN_LOG_FLAG) error_log("\narray( section => 'SQL Query for getMultipleCoursesBrochure', timetaken => ".(microtime(true) - $startTime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);
			if(count($db_brochure)>0) {
					
				foreach ($db_brochure as $key=>$url) {
					$brochure_url_array[$key] = $url;		
				}
					
			}
		}
		
		return $brochure_url_array;
	}

	public function getMultipleCoursesBrochureFromInsttObjects($instituteObjsWithCourse) {
		foreach ($instituteObjsWithCourse as $instituteId => $instituteObj) {
			$courses = $instituteObj->getCourses();
			foreach ($courses as $key => $course) {
				if($course instanceof Course) {
					//$courseIds[] = $course->getId();
					if($course_brochure_url = $course->getRequestBrochure()) {
						//
					}else if(isset($instituteId) && $instituteObjsWithCourse[$instituteId] instanceOf Institute && $course_brochure_url = $instituteObj->getRequestBrochure()){
						//
					}
					if(!empty($course_brochure_url)) {
						$brochure_url_array[$course->getId()] = $course_brochure_url;
					} else {
						$exclustion_list[] = $course->getId();
					}
				}
			}
		}

		if(count($exclustion_list)>0) {
			$startTime = microtime(true);
			$db_brochure = $this->listingextendedmodel->getMultipleListingsEBrochureInfo($exclustion_list,'course');
			if(count($db_brochure)>0) {
				foreach ($db_brochure as $key=>$url) {
					$brochure_url_array[$key] = $url;		
				}
					
			}
		}
		return $brochure_url_array;
	}

	public function getOnlineFormURL($course_id,$institute_id) {

		$this->CI->load->library('StudentDashboardClient');
		$this->CI->load->library('Online_form_client');
		$this->CI->load->library('dashboardconfig');
		$this->CI->load->helper('url');
		$onlineClient = new Online_form_client();
		$online_form_url = "";
		$response = array();

		$displayOnlineButton = $onlineClient->checkIfListingHasOnlineForm('course',$course_id);

		if(is_array($displayOnlineButton) && isset($displayOnlineButton[0]['courseId'])){

			global $onlineFormsDepartments;
			$online_form_institute_seo_url = DashboardConfig::$institutes_autorization_details_array;
			$PBTSeoData = Modules::run('onlineFormEnterprise/PBTFormsAutomation/getExternalFormConfigDetails');
			$online_form_institute_seo_url += $PBTSeoData;
			
			$mainCourseId = $displayOnlineButton[0]['courseId'];
			if(array_key_exists('seo_url', $online_form_institute_seo_url[$mainCourseId]) && ($displayOnlineButton[0]['typeId'] < 0 || $displayOnlineButton[0]['typeId'] =='')) {
				$course_object = $this->course_repository->find($mainCourseId);
				$seoURL = str_replace('<courseName>', strtolower(seo_url($course_object->getName(),'-',30)), $online_form_institute_seo_url[$mainCourseId]['seo_url']);
            	$seoURL = str_replace('<courseId>', $mainCourseId, $seoURL);
				$seo_url = ($seoURL!='')?SHIKSHA_HOME.$seoURL:"/Online/OnlineForms/showOnlineForms/".$course_id;
			} else if($displayOnlineButton[0]['typeId']>0){
				$course_object = $this->course_repository->find($course_id);
				$seoURL = str_replace('<courseName>', strtolower(seo_url($course_object->getName(),'-',30)), $online_form_institute_seo_url[$mainCourseId]['seo_url']);
            	$seoURL = str_replace('<courseId>', $course_id, $seoURL);
            	$urlArray = explode('-',$seoURL);
				$urlArray[count($urlArray)-1] = $course_id;
                $seoURL = implode('-', $urlArray);
				$seo_url = ($seoURL!='')?SHIKSHA_HOME.$seoURL:"/Online/OnlineForms/showOnlineForms/".$course_id;
			}else {
				$seo_url = "/Online/OnlineForms/showOnlineForms/".$course_id;
					
			}

			$externalURL = isset($displayOnlineButton[0]['externalURL'])?$displayOnlineButton[0]['externalURL']:'';
			$response = array('of_seo_url'=>$seo_url,'of_external_url'=>$externalURL,'of_course_id'=>$course_id);
		}

		return $response;
	}

	// this function returns contnent and header
	public function makeCurlRequest($url,$method,$params) {

		// make curl
		$response = array();
		 
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL,$url);
		if($method == 'post') {
			curl_setopt($ch,CURLOPT_POST,1);
			curl_setopt($curl, CURLOPT_POSTFIELDS,$params);
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);

		$data = curl_exec($ch);
		// Then, after your curl_exec call:
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($data, 0, $header_size);
		$body = substr($data, $header_size);

		curl_close($ch);

		$response['header'] = $header;
		$response['header_size'] = $header_size;
		$response['body'] = $body;

		return $response;
	}
	
	public function getDominantSubCategoryForCourse($courseIdOrObject = NULL, $courseCategories = array(), $preferredSubCategoryId = NULL, $courseLevelDetailsOfCourseForPostingFlow = array(), $TEMPLATE_SUBCAT_ID = 23) {
		$subCategoryInfo = FALSE;
		$dominantSubCategory = FALSE;
		$subCategoryIds = FALSE;
        //$courseIdOrObject : this variable can be id or object of course : Memory optimization.
		
		if(!empty($courseIdOrObject) || !empty($courseLevelDetailsOfCourseForPostingFlow)) {
			if($courseIdOrObject instanceof Course) {
             	$courseId = $courseIdOrObject->getId();
             	$courseObj = $courseIdOrObject;
             } else {
             	$courseId = $courseIdOrObject;
             }

			if(empty($courseLevelDetailsOfCourseForPostingFlow)) {
				if(!isset($courseObj)) {
				$courseObj 	= $this->course_repository->find($courseId);
				} 
			}

			if(empty($courseCategories)){
				$courseCategory = $this->institute_repository->getCategoryIdsOfListing($courseId,'course');
				$ldbCourses 	= $this->LDBCourseRepository->getLDBCoursesForClientCourse($courseId);
				$subCategoryIds = array();
				foreach($ldbCourses as $ldbCourse) {
					$ldbCourseDetails = $this->LDBCourseRepository->find($ldbCourse->getId());
					$subCategoryIds[] = $ldbCourseDetails->getSubCategoryId();
					$ldbCourse = null;
				}	
			} else {
				$subCategoryIds = $courseCategories;
			}
			
			$dominantSubCategory = FALSE;
			if($TEMPLATE_SUBCAT_ID == '23'){
			    $MBATemplateEligibility = $this->checkForMBATemplateEligibility($subCategoryIds, $courseObj, $courseLevelDetailsOfCourseForPostingFlow);
			}else if($TEMPLATE_SUBCAT_ID == '56'){
			 	$EnggTemplateEligibility = $this->checkForEngTemplateEligibility($subCategoryIds);
			}

			if($MBATemplateEligibility || $EnggTemplateEligibility) {
				$dominantSubCategory = $TEMPLATE_SUBCAT_ID;
			} else {
				if(!empty($subCategoryIds)) {
					//Failed the check for MBA template, remove 23 from subcatid array
					foreach (array_keys($subCategoryIds, $TEMPLATE_SUBCAT_ID) as $key) {
						unset($subCategoryIds[$key]);
					}
					//If 23 was the only subcat id than it's out dominant subcatid
					if(empty($subCategoryIds)){
						$dominantSubCategory = $TEMPLATE_SUBCAT_ID;
					}
					if(!empty($subCategoryIds) && empty($dominantSubCategory)) {
						$subCategoriesBasedOnCount = array_count_values($subCategoryIds);
						$dominantSubCategory = $this->_applyPreferredSubCategoryCheck($subCategoriesBasedOnCount, $preferredSubCategoryId);
						// if count of all subcats are equal then assign the count array to the 
						if($dominantSubCategory["allSubcatCountEqualFlag"])
						{
							$subCategoryInfo["allSubcatCountEqualFlag"]   = $dominantSubCategory["allSubcatCountEqualFlag"];
							$subCategoryInfo["subCategoriesBasedOnCount"] = $subCategoriesBasedOnCount;
						}
							
						$dominantSubCategory = $dominantSubCategory["dominatSubCategoryId"];
					}
				}
			}
			
			$subCategoryInfo['dominant'] = $dominantSubCategory;
			$subCategoryInfo['subcategory_ids'] = array_unique($subCategoryIds);
		}
		return $subCategoryInfo;
	}
        
        /**
         * API to get LDB Course for client course
         * 
         * @param array $courseIds
         * @return array desiredCourse AND categoryId
         */
        public function getDominantDesiredCourseForClientCourses($courseIds = array()){
	    $this->CI->load->model('LDB/ldbcoursemodel');
            $details = array();
            if(!empty($courseIds)){
                foreach ($courseIds as $courseId) {
                	if(!is_object($this->LDBCourseRepository)){
                		return false;
                	}
                    $ldbCourses = $this->LDBCourseRepository->getLDBCoursesForClientCourse($courseId);
                    foreach($ldbCourses as $ldbCourse) {
                        $ldbCourseDetails = $this->LDBCourseRepository->find($ldbCourse->getId());
			
			if($ldbCourseDetails->getCategoryId() == 14) {
				$details[$courseId]['categoryId'] = 14;
				$testPrepMapping = $this->CI->ldbcoursemodel->getTestPrepCourseMapping($ldbCourseDetails->getId());
				$details[$courseId]['desiredCourse'] = $testPrepMapping[0]['blogId'];
			}
			else {
				$specializationName = $ldbCourseDetails->getSpecialization();
				if(strtolower($specializationName) == 'all'){
				    $details[$courseId]['desiredCourse'] = $ldbCourseDetails->getId();
				}else{
				    $details[$courseId]['desiredCourse'] = $ldbCourseDetails->getParentId();
				}
				$details[$courseId]['categoryId'] = $ldbCourseDetails->getCategoryId();
			}
                        break;
                    }
                }
                return $details;
            }else{
                return NULL;
            }
        }
	
	private function _applyPreferredSubCategoryCheck($subCategoriesBasedOnCount, $preferredSubCategoryId = NULL) {
		$dominatSubCategoryId = FALSE;
		if(!empty($subCategoriesBasedOnCount)){
			arsort($subCategoriesBasedOnCount);
			$maxOccurence = max($subCategoriesBasedOnCount);
			if(!empty($preferredSubCategoryId)){
				foreach($subCategoriesBasedOnCount as $key => $value) {
					if($maxOccurence == $value && $key == $preferredSubCategoryId) {
						$dominatSubCategoryId = $preferredSubCategoryId;
						break;
					}
				}	
			}
			$allSubcatCountEqualFlag = 0;
			if(empty($dominatSubCategoryId)){
				$allSubcatCountEqualFlag = 1;
				$keys = array_keys($subCategoriesBasedOnCount);
				$dominatSubCategoryId = $keys[0];
			}
		}
		return array( "dominatSubCategoryId" => $dominatSubCategoryId, "allSubcatCountEqualFlag" => $allSubcatCountEqualFlag);
	}
	
	public function checkForMBATemplateEligibility($courseCategory, $course, $courseLevelDetailsOfCourse = array()) {
		$flag = FALSE;
		// If one category of Client Course is MBA (but OTHERS should not be BBA OR BE/BTECH) then show the MBA template for this course..
	    if(is_array($courseCategory) && in_array(23, $courseCategory) && !in_array(28, $courseCategory) && !in_array(56, $courseCategory) ) {
			/*
			 * If Level of the course is PG   OR   If the course is of Dual degree and both of the Degrees are PG
			 */
			if(empty($courseLevelDetailsOfCourse))
			{
				$courseLevel	= $course->getCourseLevel();
				$courseLevel1	= $course->getCourseLevel1Value();
				$courseLevel2	= $course->getCourseLevel2Value();
			}
			else
			{
				$courseLevel	= $courseLevelDetailsOfCourse["level"];
				$courseLevel1	= $courseLevelDetailsOfCourse["level1"];
				$courseLevel2	= $courseLevelDetailsOfCourse["level2"];
			}
			
			if(strpos($courseLevel, "Post Graduate") !==  false || ( strpos($courseLevel, "Dual Degree") !==  false &&  strpos($courseLevel1, "Post Graduate") !==  false && strpos($courseLevel2, "Post Graduate") !==  false ) ) {
				$flag = TRUE;
			}
	    }
	    $course = null;
		return $flag;
	}

	public function checkForEngTemplateEligibility($courseCategory) {
		$flag = FALSE;
		// If one category of Client Course is MBA (but OTHERS should not be BBA OR BE/BTECH) then show the MBA template for this course..
	    if(is_array($courseCategory) && in_array(56, $courseCategory) && !in_array(28, $courseCategory) && !in_array(23, $courseCategory) ) {
			$flag = "TRUE";
	    }

		return $flag;
	}

	public function checkForCollegeReviewTemplateEligibility($courseCategory) {
		$flag = FALSE;
		global $subCatsForCollegeReviews;
		if(is_array($courseCategory)){
			foreach ($courseCategory as $key => $subCat) {
				if($subCatsForCollegeReviews[$subCat] == '1'){
					$flag = "TRUE";
					break;
				}
			}
		}
	    
		return $flag;
	}
	
	public function getOtherCoursesFromSameSubCategorySection($courseObj = NULL, $coursesCategories = array(),$instituteObj = NULL) {
		$this->_setDependecies();
		$result = array();
		if(!empty($courseObj)){
			$courseId 		= $courseObj->getId();
			// check if data exists in cache or not
			$cachedData = $this->listingcache->getSimilarCoursesForCourse($courseId);
			if(!empty($cachedData)) {
				return $cachedData;
			}
			$instituteId 	= $courseObj->getInstId();
			$courseMap = $this->course_repository->getCoursesByMultipleInstitutes(array($instituteId));
			$courses = array();
			$currentCourseId = $courseId;
			if(!empty($courseMap)) {
				if(array_key_exists($instituteId, $courseMap)){
					$courses = $courseMap[$instituteId];
				}
			}
			
			$courseCategories = array();
			if(!empty($coursesCategories)) {
				if(in_array($courseId, array_keys($coursesCategories))){
					$courseCategories = $coursesCategories[$courseId];
				}
			}
			$dominantSubCategoryInfoForCurrentCourse = $this->getDominantSubCategoryForCourse($courseObj, $courseCategories);
			$similarSubCategoryCourses = array();
			if(!empty($dominantSubCategoryInfoForCurrentCourse['dominant'])) {
				$dominantSubCategoryIdForCurrentCourse = $dominantSubCategoryInfoForCurrentCourse['dominant'];
				//remove current course from course list
				unset($courses[array_search($courseId,$courses)]);
				foreach($courses as $courseId){
					$courseCategories = array();
					if(!empty($coursesCategories)) {
						if(in_array($courseId, array_keys($coursesCategories))){
							$courseCategories = $coursesCategories[$courseId];
						}
					}

					if(isset($instituteObj) && $courseObject = $instituteObj->getCourse($courseId)) {
						$dominantSubCategoryInfoForCourse = $this->getDominantSubCategoryForCourse($courseObject, $courseCategories, $dominantSubCategoryIdForCurrentCourse);
					} else {
						$dominantSubCategoryInfoForCourse = $this->getDominantSubCategoryForCourse($courseId, $courseCategories, $dominantSubCategoryIdForCurrentCourse);
					}	

					if(!empty($dominantSubCategoryInfoForCourse) && array_key_exists('dominant', $dominantSubCategoryInfoForCourse)) {
						$dominantSubCategoryIdForCourse = $dominantSubCategoryInfoForCourse['dominant'];
						if($dominantSubCategoryIdForCourse == $dominantSubCategoryIdForCurrentCourse){
							$similarSubCategoryCourses[] = $courseId;
						}
					}
				}
			}
			$coursesInformation = array();
			if(!empty($similarSubCategoryCourses)){
				if(isset($instituteObj)) {
				$courseList =	$this->_getCoursesFromInstituteObj($similarSubCategoryCourses,$instituteObj);
				} else {
				$courseList = $this->course_repository->findMultiple($similarSubCategoryCourses);
                }

               	foreach($courseList as $course){
					$temp = array();
					$temp['course_id'] 			= $course->getId();
					$temp['course_title'] 		= $course->getName();
					$temp['course_url'] 		= $course->getURL();
					$temp['duration_str'] 		= $course->getDuration()->getDisplayValue() ? $course->getDuration()->getDisplayValue(): "";
					$temp['course_type_str'] 	= $course->getCourseType() ? $course->getCourseType() : "";
					$coursesInformation[] 		= $temp;
				}
			}
			if(!empty($dominantSubCategoryIdForCurrentCourse)) {
				$this->CI->load->builder('CategoryBuilder','categoryList');
				$categoryBuilder 			= new CategoryBuilder;
				$categoryRepository 		= $categoryBuilder->getCategoryRepository();
				$dominantSubCategoryObj 	= $categoryRepository->find($dominantSubCategoryIdForCurrentCourse);
				$dominantSubCategoryName    = $dominantSubCategoryObj->getName();
				$parentId 					= $dominantSubCategoryObj->getParentId();
				$dominantCategoryObj 		= $categoryRepository->find($parentId);
				$dominantCategoryName		= $dominantCategoryObj->getName();
				$result['current_dominant_subcat_id'] 	= $dominantSubCategoryIdForCurrentCourse;
				$result['current_dominant_subcat_name'] = $dominantSubCategoryName;
				$result['current_dominant_cat_id'] 		= $parentId;
				$result['current_dominant_cat_name'] 	= $dominantCategoryName;
			}
			$result['current_course_id'] 		= $currentCourseId;
			$result['current_institute_name'] 	= $courseObj->getInstituteName();
			$result['current_course_dominant_subcat_info'] = $dominantSubCategoryInfoForCurrentCourse;
			$result['same_subcat_courses'] = $coursesInformation;
			$this->listingcache->storeSimilarCoursesForCourse($currentCourseId, $result);
		}
		return $result;
	}
	
	public function getBranchLocationInformationForCourse($course, $locations = FALSE) {
		$data = array();
		if(!empty($course)){
			$locations = $locations ? $locations : $course->getLocations();
			if(count($locations) > 1) {
				$data['course_name']  	= html_escape($course->getName());
				$data['course_url']   	= $course->getURL();
				$data['course_obj']  	= $course;
				$data['courses']		= $course->instituteCourses;
				$data['locations_with_locality'] 	= array();
				$data['other_locations'] 			= array();
				foreach($locations as $location) {
					if($location->getLocality() && $location->getLocality()->getName()){
						$city = $location->getCity();
						$data['locations_with_locality'][$city->getId()][] = $location;
					} else {
						$data['other_locations'][] = $location;
					}
				}
			}
		}
		return $data;
	}
	
	public function getCoursesForInstituteBrowseSection($instituteId = NULL, $categoryListByCourse = NULL, $cityId = NULL, $localityId = NULL, $instituteWithCoursesObject = NULL) {
		$categoryLevelInfo = array();
		//$courseList = $this->getCoursesByCityLocalityForInstitute($instituteId, $cityId, $localityId);
		//if(!empty($courseList)) {
			$courseListBySubCategory = array();
			if(!empty($categoryListByCourse)){
				foreach($categoryListByCourse as $courseId => $categoryIds) {
					foreach($categoryIds as $courseCatId){
						if(!in_array($courseId, $courseListBySubCategory[$courseCatId])){
							$courseListBySubCategory[$courseCatId][] = $courseId;
						}
					}
				}	
			} else {
				$courseList = $this->getCoursesByCityLocalityForInstitute($instituteId, $cityId, $localityId);
				foreach($courseList as $courseId) {
					$courseCategory = $this->institute_repository->getCategoryIdsOfListing($courseId,'course');
					foreach($courseCategory as $courseCatId){
						if(!in_array($courseId, $courseListBySubCategory[$courseCatId])){
							$courseListBySubCategory[$courseCatId][] = $courseId;
						}
					}
				}
			}

			if(empty($categoryListByCourse) && empty($courseList)) {
				return;
			} 
			
			$this->CI->load->builder('CategoryBuilder','categoryList');
			$categoryBuilder 			= new CategoryBuilder;
			$categoryRepository 		= $categoryBuilder->getCategoryRepository();
			foreach($courseListBySubCategory as $subCategoryId => $courses) {
				if(is_numeric($subCategoryId)){
					$categoryObject  = $categoryRepository->find($subCategoryId);
					if(!empty($categoryObject)){
						$subCategoryName 	= $categoryObject->getName();
						$subCategoryShortName = $categoryObject->getShortName();
						$parentCategoryId	= $categoryObject->getParentId();
						$parentCategoryObj 	= $categoryRepository->find($parentCategoryId);
						$parentCategoryName = $parentCategoryObj->getName();
						$parentCategoryShortName = $parentCategoryObj->getShortName();
						if($instituteWithCoursesObject) {
							// fetch course object list from institute object
							$courseObjList  = $this->_getCoursesFromInstituteObj($courses,$instituteWithCoursesObject);
						} else {
							// load from course repository
							$courseObjList 		= $this->course_repository->findMultiple($courses);
						}
						
						$tempCourseList 	= array();
						foreach($courseObjList as  $course) {
							if(!empty($cityId) && is_numeric($cityId)){
								$additionalURLParams = "?city=".$cityId;
								if(!empty($localityId) && is_numeric($localityId)){
									$additionalURLParams .= "&locality=".$localityId;
								}
								$course->setAdditionalURLParams($additionalURLParams);
							}
							$temp 						= array();
							$temp['course_id'] 			= $course->getId();
							$temp['course_title'] 		= $course->getName();
							$temp['course_url'] 		= $course->getURL();
							$temp['duration_str'] 		= $course->getDuration()->getDisplayValue() ? $course->getDuration()->getDisplayValue(): "";
							$temp['course_type_str'] 	= $course->getCourseType() ? $course->getCourseType() : "";
							$temp['course_is_paid'] 	= $course->isPaid() ? $course->isPaid() : 0;
							$temp['course_order'] 	= $course->getOrder() ? $course->getOrder() : -1;
							$tempCourseList[] = $temp;
						}
						$subCategoryLevelCourseList = array();
						$subCategoryLevelCourseList['subcategory_id'] 	= $subCategoryId;
						$subCategoryLevelCourseList['subcategory_name'] = $subCategoryName;
						$subCategoryLevelCourseList['subcategory_short_name'] = $subCategoryShortName;
						
						$subCategoryLevelCourseList['course_count'] 	= count($tempCourseList);
						$subCategoryLevelCourseList['courses'] 			= $tempCourseList;
						
						if(!array_key_exists($parentCategoryId, $categoryLevelInfo)){
							$categoryLevelInfo[$parentCategoryId] = array();
							$categoryLevelInfo[$parentCategoryId]['category_id'] 	= "";
							$categoryLevelInfo[$parentCategoryId]['category_name'] 	= "";
							$categoryLevelInfo[$parentCategoryId]['count'] 			= 0;
							$categoryLevelInfo[$parentCategoryId]['subcategory_courses'] = array();
						}
						$categoryLevelInfo[$parentCategoryId]['category_id'] 			= $parentCategoryId;
						$categoryLevelInfo[$parentCategoryId]['category_name'] 			= $parentCategoryName;
						$categoryLevelInfo[$parentCategoryId]['category_short_name'] 			= $parentCategoryShortName;
						
						$categoryLevelInfo[$parentCategoryId]['count'] 	 				= $categoryLevelInfo[$parentCategoryId]['count'] + $subCategoryLevelCourseList['course_count'];
						$categoryLevelInfo[$parentCategoryId]['subcategory_courses'][]	= $subCategoryLevelCourseList;
					}	
				}
			}
			foreach($categoryLevelInfo as $categoryId => $categoryInfo) {
				usort($categoryInfo['subcategory_courses'], function($a, $b){
					if($a['course_count'] == $b['course_count']){
						return 0;
					}
					return ($a['course_count'] < $b['course_count']) ? 1 : -1;
				});
				foreach($categoryInfo['subcategory_courses'] as $key => $course) {
					usort($course['courses'], function($a, $b){
						if($a['course_is_paid'] < $b['course_is_paid']) {
							return 1;
						}
						else if($a['course_is_paid'] > $b['course_is_paid']){
							return -1;
						}
						else if($a['course_is_paid'] == $b['course_is_paid']){
							if($a['course_order'] > $b['course_order']) {
								return 1;
							}
							else {
								return 0;
							}
						}
						else {
							return 0;
						}
					});
					$categoryInfo['subcategory_courses'][$key] = $course;
				}
				$categoryLevelInfo[$categoryId] = $categoryInfo;
			}
			uasort($categoryLevelInfo, function($a, $b){
				if($a['count'] == $b['count']){
					return 0;
				}
				return ($a['count'] < $b['count']) ? 1 : -1;
			});
		//}
		
		return $categoryLevelInfo;
	}
	
    private function _getCoursesFromInstituteObj($courses,$instituteWithCoursesObject) {
           $courseObjList = array();
           foreach($courses as $courseId) {
           	$objt = $instituteWithCoursesObject->getCourse($courseId);
           	if($objt instanceOf Course) {
           	$courseObjList[$courseId] = $objt;
           	}
           }

          return  $courseObjList;
    }

	public function getCoursesByCityLocalityForInstitute($instituteId = NULL, $cityId = NULL, $localityId = NULL) {
		$result = array();
		if(empty($instituteId)) {
			return FALSE;
		}
		$coursesByLocation = $this->institute_repository->getLocationwiseCourseListForInstitute($instituteId);
		foreach($coursesByLocation as $courseList) {
			$flag = FALSE;
			if(!empty($cityId)) {
				if($cityId == $courseList['city_id']) {
					if(!empty($localityId)){
						if($localityId == $courseList['locality_id']){
							$flag = TRUE;
						}
					} else {
						$flag = TRUE;
					}
				}
			} else {
				$flag = TRUE;
			}
			if($flag){
				$result = array_merge($result, $courseList['courselist']);
			}
		}
		if(!empty($result)){
			$result = array_unique($result);
		}
		return $result;
	}

	/**
	* Function to get the encoded message for Brochure URL to be sent to the user
	**/	
	public function getEncodedMsgForBrochureURL( $userId, $courseId )
	{
	    $salt 		= "tpyrcedottlucffidegassemekamottlas";
	    $message 		= $userId.$salt.$courseId;
	    $encryptedMsg	= base64_encode($message);
	    
	    $encryptedMsg 	= urlencode($encryptedMsg);

	    return $encryptedMsg;
	}

	/**
	* Function to get the original message out of encoded message
	**/	
	public function getDecodedMsgForBrochureURL( $encryptedMsg )
	{
	    $salt 		= "tpyrcedottlucffidegassemekamottlas";
	    $decryptedMsg 	= urldecode($encryptedMsg);
	    $decryptedMsg	= base64_decode($decryptedMsg);
	    
	    $message		= explode($salt, $decryptedMsg);
	    
	    $userId		= $message[0];
	    $courseId		= $message[1];
	    
	    $data 		= array($userId, $courseId);
	
	    return $data;
	}
	
	/**
	* Function to get the URL of the borchure to be sent to the user
	**/	
	public function getBrochureDownloadURL( $user_id, $course_id, $studyAbroadFlag = 0 )
	{
		$encodedMsg = $this->getEncodedMsgForBrochureURL( $user_id, $course_id );
		if($studyAbroadFlag == 1)
		{
			$url = SHIKSHA_HOME."/listing/abroadListings/downloadBrochureFromMailLink/".$encodedMsg."/response_abroad_mail_download";
		}
		else{
			$url = SHIKSHA_HOME."/listing/ListingPage/downloadBrochure/".$encodedMsg;
		}
		
		return $url;
	}
	
	public function getCampusReps($courseId = NULL, $instituteId = NULL) {
		$this->CI->load->library('listing/cache/ListingCache');
		$ListingCacheObj 	= new ListingCache();
		$campusRepData 		= array();
		if(!empty($courseId) && !empty($instituteId)){
			$campusRepData = $ListingCacheObj->getCampusRepCourseData($courseId);
			if(empty($campusRepData)){
				$this->CI->load->model('CA/cadiscussionmodel');
				$this->cadiscussionmodel = new CADiscussionModel();
				$caJoiningDate = $this->cadiscussionmodel->getCAJoinDate($courseId);
				$campusRepData = $this->cadiscussionmodel->getCampusReps($courseId, $instituteId, 3, false, true,$caJoiningDate);
				$ListingCacheObj->storeCampusRepCourseData($courseId, $campusRepData);
			}
		}
		return $campusRepData;
	}
	
	/*
	 * this function loads default values for seo title & description(IF UNAVAILABLE) for national course & institute 
	 * as per new template given in LF-1163
	 * 
	 * params: listing_type(course/institute),listingObject,seodataarray,$institute(in case oflistingType = 'course')
	 * returns : seodataarray with default seo title & description if they were not available inseodataarray already
	 *
	 */
	public function getDefaultMetaData($listingType,$listingObject,$seoData = array(),$institute=NULL)
	{
		if($listingType == "course"){
			$instituteObj = $institute;
		}
		else if($listingType == "institute"){
			$instituteObj = $listingObject;
		}
		$instituteShortName = htmlentities($instituteObj->getAbbreviation());
		$instituteName = htmlentities($instituteObj->getName());
		$localityCount = 0;
		$mainCityId = $seoData['currentLocation']->getCity()->getId();
		foreach($instituteObj->getLocations() as $loc)
		{
			if($loc->getLocality()->getName()!="" && $loc->getCity()->getId()==$mainCityId)
			$localityCount++;
		}
		if($localityCount >1){
			$instituteLocalityName = htmlentities($seoData['currentLocation']->getLocality()->getName());
		}
		$instituteCityName = htmlentities($seoData['currentLocation']->getCity()->getName());
		
		
		// if title or description are unavailable we need to add a default value for these
		if($seoData['title'] =="" || $seoData['metaDescription']=="")
		{
			if($listingType == 'institute'){
				// here short name section & main section can be used for both title & description, rest of the logic is as per default template given in LF-1163
				$mainSection  = $instituteName.", ";
				if($instituteShortName != "")
				{
					$shortNameSection  = $instituteShortName;
					$shortNameSection .= ($instituteLocalityName !=""?" ".$instituteLocalityName.",":"");
					$shortNameSection .= " ".$instituteCityName;
					$shortNameConjuction = " - ";
					$mainSection .= ($instituteLocalityName!=""?$instituteLocalityName:$instituteCityName);
				}
				else{
					$mainSection .= ($instituteLocalityName!=""?$instituteLocalityName.", ":"");
					$mainSection .= $instituteCityName;
				}
				// set default seo title if it is not available
				if($seoData['title'] =="")
				{
					$seoData['title'] = $shortNameSection.$shortNameConjuction.$mainSection." | Shiksha.com";
				}
				// set default seo description if it is not available
				if($seoData['metaDescription'] =="")
				{
					if($shortNameSection!="")
					{
						$shortNameDescriptionSection = "details of ".$shortNameSection." on Shiksha.com. Get ";
					}
					$seoData['metaDescription'] = "See available courses at ".$instituteName.", find out about fees, admissions, courses, placement, faculty and much more only at Shiksha.com";
				}
			}
			else if($listingType == 'course'){
				$courseName = htmlentities($listingObject->getName());
				// here short name section & main section can be used for both title & description, rest of the logic is as per default template given in LF-1163
				if($instituteShortName != "")
				{
					if(count($instituteObj->getLocations())>1)
					{
						$shortNameSection  = ($instituteShortName!=""?$instituteShortName:$instituteName);
						$shortNameSectionForDesc  = $shortNameSection;
					}
					else{
						$shortNameSection  = $instituteName." (".$instituteShortName.")";
						$shortNameSectionForDesc  = $instituteName." ";
					}
					$shortNameSection .= ($instituteLocalityName !=""?", ".$instituteLocalityName:"");
					$shortNameSectionForDesc .= ($instituteLocalityName !=""?", ".$instituteLocalityName.", ":"");
					$shortNameSection .= ", ".$instituteCityName;
					$shortNameSectionForDesc .= $instituteCityName;
				}
				else{
					$shortNameSection  = $instituteName;
					$shortNameSectionForDesc  = $instituteName;
					$shortNameSection .= ($instituteLocalityName !=""?", ".$instituteLocalityName:"");
					$shortNameSectionForDesc .= ($instituteLocalityName !=""?", ".$instituteLocalityName:"");
					$shortNameSection .= ", ".$instituteCityName;
					$shortNameSectionForDesc .= ", ".$instituteCityName;
				}
				// set default seo title if it is not available
				if($seoData['title'] =="")
				{
					$seoData['title']	 = $courseName." in ".$shortNameSection." - Shiksha.com";
				}
				// set default seo description if it is not available
				if($seoData['metaDescription'] =="")
				{
					$seoData['metaDescription'] = "Read more about ".$courseName." at ".$instituteName.". Find out about fees, admissions, reviews and more only at Shiksha.com";
				}
			}
			
		}
		else {
			
			if($listingType == 'institute'){
				$seoData['title'] = str_replace("&lt;college full name&gt;",$instituteName,$seoData['title']);
				$seoData['metaDescription'] = str_replace("&lt;college full name&gt;",$instituteName,$seoData['metaDescription']);
					
			}
			else if($listingType == 'course'){
	
				$courseName = htmlentities($listingObject->getName());
				$collegeAndCourseNamePlaceholder = array("&lt;course name&gt;", "&lt;college name&gt;");   
				$collegeAndCourseNameValues = array($courseName, $instituteName);   
				$seoData['title'] = str_ireplace($collegeAndCourseNamePlaceholder,$collegeAndCourseNameValues,$seoData['title']);
				$seoData['metaDescription'] = str_ireplace($collegeAndCourseNamePlaceholder,$collegeAndCourseNameValues,$seoData['metaDescription']);
					
			}

		}
		
	//_p($seoData);
		return $seoData;	
	}

	/**
	* Purpose       : Method to generate new URL for engineering course given the required details
	* Params        : 1. $courseId : course id
	* 		  2. $courseTitle : course title
	* 		  3. $instituteNameAcronym : Institute short name
	* 		  4. $instituteTitle : Institute name
	* 		  5. $cityName : city name of the head office
	* Author        : Romil Goel
	*/	
	public function getEnggCourseURL($courseId, $courseTitle, $instituteNameAcronym, $instituteTitle, $cityName)
	{
		$courseTitle 		= seo_url($courseTitle, "-", 100);
		$instituteTitle 	= seo_url($instituteTitle, "-", 100);

		$cityName 		= seo_url($cityName, "-", 100);
		
		$instituteNameAcronym 	= seo_url($instituteNameAcronym, "-", 100);
		//engineering.shiksha.com/<course_name>-<institute_short_name>-<institute_full_name>-<city>-courselisting
		
		$url = SHIKSHA_HOME_URL."/".$courseTitle."-".$instituteNameAcronym."-".$instituteTitle."-".$cityName."-listingcourse-".$courseId;
		$url = strtolower(trim(preg_replace('/-+/', '-', $url), '-'));
		
		return $url;
	}

   

     	public function _checkIfDataIsconsistentForCourseListingPage($resultData) {
		$examWithBranchId = array();
		
		foreach ($resultData as $data ){
			if(isset($examWithBranchId[$data['exams']]))
			{
				if($examWithBranchId[$data['exams']] != $data['branchId'])	
				{
				 return false;
				}	
			
			} else {
				$examWithBranchId[$data['exams']] = $data['branchId'];
			}
		}
		return true;
	}
	
	public function getCutoffRanksForCourse($courseId) {
		$resultData = $this->course_repository->getCutOffRanksForCourse($courseId);
		$isConsitent = $this->_checkIfDataIsconsistentForCourseListingPage($resultData);
		$courseCutOffData = array();
	    if($isConsitent) {
	       foreach ($resultData as $data) {
	       	$courseCutOffData[$data['exams']][$data['roundNum']][$data['rankType']][$data['categoryName']] = $data['closingRank'];
	       	  
	       }
	    }
	    return $courseCutOffData ;
	}
	/*
	 * function that gets institute_location_id based on the city & locality passed to it
	 * params: array(instituteId, cityId, localityId)
	 *
	 */
	public function getInstituteLocationIdByCityLocality($data){
		$this->CI->load->model('listing/institutemodel');
		$this->instituteModel = new InstituteModel();
		return $this->instituteModel->getInstituteLocationIdByCityLocality($data);
	}

	public function getCourseReviewsDataForRankingPage($courseIds,$disableCache = false,$countCriteria4PaidCourse = 3,$countCriteria4FreeCourse = 3){
		$coursesReviewsFromCache = array();
		$orderOfCourseIds = $courseIds;
		//if disable cache is true then reviews will be fetched from db
		if($this->listingcache && !$disableCache) {
		    $coursesReviewsFromCache = $this->listingcache->getMultipleCoursesReviewsForRankingPage($courseIds); //15 mins cache
		    $foundInCache = array_keys($coursesReviewsFromCache);
		    $courseIds = array_diff($courseIds,$foundInCache);
		}
		if(count($courseIds) > 0){
			$this->CI->load->model('CollegeReviewForm/collegereviewmodel');
			$this->crmodel = new CollegeReviewModel();

			$this->CI->load->model('listing/listingmodel');
			$this->ListingModel = new ListingModel();

			$coursesReviewsFromDB = $this->ListingModel->findCourseReviewsData($courseIds);

			$processedCoursesReviewsfromDB = array();
			foreach($coursesReviewsFromDB as $key=>$courseReviews){
				$subCatArray = $this->getDominantSubCategoryForCourse($courseReviews['courseId']);
				$dominantCategory = $subCatArray['dominant'];

	         	$ratingCount = $this->crmodel->getRatingParamCount($dominantCategory);

	         	$processedCoursesReviewsfromDB[$courseReviews['courseId']] ['packType'] 	  = $courseReviews['pack_type'];
	         	$processedCoursesReviewsfromDB[$courseReviews['courseId']] ['overallAverageRating'] += $courseReviews['averageRating'];
	         	$processedCoursesReviewsfromDB[$courseReviews['courseId']] ['ratingCount'] = $ratingCount;
	         	$processedCoursesReviewsfromDB[$courseReviews['courseId']] ['totalReviewCount']++;
			}

			foreach($courseIds as $courseId){
				$courseReviews = $processedCoursesReviewsfromDB[$courseId];
				
				if(!is_array($courseReviews)){
				    $processedCoursesReviewsfromDB[$courseId] = array();
				    $courseReviews = array();
				}
				else{
					$count = $courseReviews['totalReviewCount'];
					if($count >= 1){
						$processedCoursesReviewsfromDB[$courseId] ['overallAverageRating'] = round($courseReviews['overallAverageRating']/$count,1);
						$processedCoursesReviewsfromDB[$courseId] ['overallRating'] = $processedCoursesReviewsfromDB[$courseId] ['overallAverageRating'];

					}
				}
				$this->listingcache->storeCourseReviewsForRankingPage($processedCoursesReviewsfromDB[$courseId],$courseId);
			}
		}
		$courseReviews = array();
		foreach($orderOfCourseIds as $courseId) {
	        if(isset($coursesReviewsFromCache[$courseId])){
	            $courseReviews[$courseId] = $coursesReviewsFromCache[$courseId];
	        }
	        else if(isset($processedCoursesReviewsfromDB[$courseId])){
	            $courseReviews[$courseId] = $processedCoursesReviewsfromDB[$courseId];
	        }
		}
		
		foreach ($courseReviews as $courseId => $review) {
			if(isset($review['packType'])){
				if($review['packType'] == GOLD_SL_LISTINGS_BASE_PRODUCT_ID || $review['packType'] == SILVER_LISTINGS_BASE_PRODUCT_ID || $review['packType'] == GOLD_ML_LISTINGS_BASE_PRODUCT_ID){
					if($review['totalReviewCount'] < $countCriteria4PaidCourse){
						$courseReviews[$courseId] = array();
					}
				}
				else{
					if($review['totalReviewCount'] < $countCriteria4FreeCourse){
						$courseReviews[$courseId] = array();
					}
				}
			}
		}
		return $courseReviews;
	}
        
        /*
	 * Desc : to get review data corresponding to a courseId
	 * params: array(courseId)
	 *
	 */
	function getCourseReviewsData($courseIds, $disableCache = false){
            $orderOfCourseIds = $courseIds;
            $coursesReviewsFromCache = array();
            //if disable cache is true then reviews will be fetched from db
            if($this->listingcache && !$disableCache) {
                $coursesReviewsFromCache = $this->listingcache->getMultipleCoursesReviews($courseIds);
                $foundInCache = array_keys($coursesReviewsFromCache);
                $courseIds = array_diff($courseIds,$foundInCache);
            }
            
            if(count($courseIds) > 0) {
            	$this->CI->load->model('CollegeReviewForm/collegereviewmodel');
				$this->crmodel = new CollegeReviewModel();		

                $this->CI->load->model('listing/listingmodel');
                $this->ListingModel = new ListingModel();

                $coursesReviewsFromDB = $this->ListingModel->findCourseReviewsData($courseIds);                
            
            	
              	$coursesReviewsFromDB = $this->getRatingReviewsForCourses($coursesReviewsFromDB);

                $processedCoursesReviewsfromDB = array();
                
                 foreach($coursesReviewsFromDB as $key=>$courseReviews) {
					/*$subCatArray = $this->getDominantSubCategoryForCourse($courseReviews['courseId']);
					$dominantCategory = $subCatArray['dominant'];

                 	$ratingCount = $this->crmodel->getRatingParamCount($dominantCategory); */

                 	$ratingCount = 5;		/* hard coded to 5 (for denominator), uncomment above code to make it 
                 							 dynamic category based.*/

                    $processedCoursesReviewsfromDB[$courseReviews['courseId']] ['reviews'] [$key] = $courseReviews;
                    $processedCoursesReviewsfromDB[$courseReviews['courseId']] ['packType'] 	  = $courseReviews['pack_type'];
                    $processedCoursesReviewsfromDB[$courseReviews['courseId']] ['overallMoneyRating']               += $courseReviews['moneyRating'] ;
                    $processedCoursesReviewsfromDB[$courseReviews['courseId']] ['overallCrowdCampusRating']         += $courseReviews['crowdCampusRating'] ;
                    $processedCoursesReviewsfromDB[$courseReviews['courseId']] ['overallAvgSalaryPlacementRating']  += $courseReviews['avgSalaryPlacementRating'] ;
                    $processedCoursesReviewsfromDB[$courseReviews['courseId']] ['overallCampusFacilitiesRating']    += $courseReviews['campusFacilitiesRating'] ;
                    $processedCoursesReviewsfromDB[$courseReviews['courseId']] ['overallFacultyRating']             += $courseReviews['facultyRating'] ;
                    $processedCoursesReviewsfromDB[$courseReviews['courseId']] ['overallRecommendations']           += ($courseReviews['recommendCollegeFlag'] == 'YES') ? 1 : 0;
                    $processedCoursesReviewsfromDB[$courseReviews['courseId']] ['review_seo_url']               = $courseReviews['review_seo_url'] ;
                    $processedCoursesReviewsfromDB[$courseReviews['courseId']] ['review_seo_title']               = $courseReviews['review_seo_title'] ;
                    $processedCoursesReviewsfromDB[$courseReviews['courseId']] ['reviews'] [$key] ['overallUserRating'] = $courseReviews['averageRating'];
                    $processedCoursesReviewsfromDB[$courseReviews['courseId']] ['reviews'] [$key] ['ratingParamCount'] = $ratingCount;
                    $processedCoursesReviewsfromDB[$courseReviews['courseId']] ['overallAverageRating'] += $courseReviews['averageRating'];
                    $processedCoursesReviewsfromDB[$courseReviews['courseId']] ['ratingCount'] = $ratingCount;
                    $processedCoursesReviewsfromDB[$courseReviews['courseId']] ['totalReviewCount']++;
                    unset($ratingCount);

                    $nameArr = explode(' ',$courseReviews['username']);
                    $nameArrCount = count($nameArr);
                    if($nameArrCount > 1) {
                        $processedCoursesReviewsfromDB[$courseReviews['courseId']] ['reviews'] [$key] ['userNameInitials'] = strtoupper(substr($nameArr[0],0,1).substr($nameArr[$nameArrCount-1],0,1));
                    } else {
                        $processedCoursesReviewsfromDB[$courseReviews['courseId']] ['reviews'] [$key] ['userNameInitials'] = strtoupper(substr($nameArr[0],0,1));
                    }
                  
                 }
                
                foreach($courseIds as $courseId) {
                    $courseReviews = $processedCoursesReviewsfromDB[$courseId];
                   
                    if(!is_array($courseReviews)) {
                        $processedCoursesReviewsfromDB[$courseId] = array();
                        
                    }
                    else {
                        $count = count($courseReviews['reviews']);
                        if($count >= 1) {
                            $processedCoursesReviewsfromDB[$courseId] ['overallMoneyRating']               = round(($courseReviews['overallMoneyRating'])/ $count,1);
                            $processedCoursesReviewsfromDB[$courseId] ['overallCrowdCampusRating']         = round(($courseReviews['overallCrowdCampusRating'])/ $count,1);
                            $processedCoursesReviewsfromDB[$courseId] ['overallAvgSalaryPlacementRating']  = round(($courseReviews['overallAvgSalaryPlacementRating'])/ $count,1);
                            $processedCoursesReviewsfromDB[$courseId] ['overallCampusFacilitiesRating']    = round(($courseReviews['overallCampusFacilitiesRating'])/ $count,1);
                            $processedCoursesReviewsfromDB[$courseId] ['overallFacultyRating']             = round(($courseReviews['overallFacultyRating'])/ $count,1);
                            $processedCoursesReviewsfromDB[$courseId] ['overallRating']                    = round(($processedCoursesReviewsfromDB[$courseId]['overallMoneyRating'] + 
                                                                                                              $processedCoursesReviewsfromDB[$courseId]['overallCrowdCampusRating'] + 
                                                                                                              $processedCoursesReviewsfromDB[$courseId]['overallAvgSalaryPlacementRating'] + 
                                                                                                              $processedCoursesReviewsfromDB[$courseId]['overallCampusFacilitiesRating'] + 
                                                                                                              $processedCoursesReviewsfromDB[$courseId]['overallFacultyRating']) / 5,1);

                           
                            $processedCoursesReviewsfromDB[$courseId] ['overallAverageRating'] = round($courseReviews['overallAverageRating']/$courseReviews['totalReviewCount'],1);
                  			$processedCoursesReviewsfromDB[$courseId] ['overallRating'] = $processedCoursesReviewsfromDB[$courseId] ['overallAverageRating'];
                        }
                        else {
                            $processedCoursesReviewsfromDB[$courseId] = array();
                        }
                    }
                    
                
                    $this->listingcache->storeCourseReviews($processedCoursesReviewsfromDB[$courseId],$courseId);
                }
               
            }
            
            $coursesReviews = array();
            foreach($orderOfCourseIds as $courseId) {
                    if(isset($coursesReviewsFromCache[$courseId])) {
                        $coursesReviews[$courseId] = $coursesReviewsFromCache[$courseId];
                    }
                    else if(isset($processedCoursesReviewsfromDB[$courseId])) {
                        $coursesReviews[$courseId] = $processedCoursesReviewsfromDB[$courseId];
                    }
                
            }
            return $coursesReviews;
	}

	function getCourseReviewCount($courseIds) {
		$this->CI->load->library('listing/cache/ListingCache');
		$this->listingcache = $this->CI->listingcache;

		$this->CI->load->model('listing/listingmodel');
        $this->ListingModel = new ListingModel();

		$coursesReviewsFromCache = array();
        $coursesReviewsFromCache = $this->listingcache->getMultipleCoursesReviewCount($courseIds);
        
        $foundInCache = array_keys($coursesReviewsFromCache);
        $courseArr = array_diff($courseIds, $foundInCache);
        
        if(count($courseArr) > 0) {
        	$coursesReviewsFromDB = $this->ListingModel->findCourseReviewsData($courseArr);
        	foreach($coursesReviewsFromDB as $key=>$courseReviews) {
        		$processedCoursesReviewsfromDB[$courseReviews['courseId']][] = $courseReviews;
        	}
        }

        $courseReviewCountDB = array();
        foreach ($courseArr as $courseId) {
        	$courseReviewCountDB[$courseId] = count($processedCoursesReviewsfromDB[$courseId]);
        	if(!empty($courseReviewCountDB[$courseId])) {
        		$reviewCount = $courseReviewCountDB[$courseId];
        	} else {
        		$reviewCount = 0;
        	}
        	$this->listingcache->storeReviewCountForCourse($reviewCount, $courseId);
        }

        $coursesReviews = array();
        foreach($courseIds as $courseId) {
            if(isset($coursesReviewsFromCache[$courseId])) {
                $coursesReviews[$courseId] = $coursesReviewsFromCache[$courseId];
            }
            else if(isset($courseReviewCountDB[$courseId])) {
                $coursesReviews[$courseId] = $courseReviewCountDB[$courseId];
            }
        }
        
        return $coursesReviews;
	}
        
    function getRatingReviewsForCourses($coursesReviewsFromDB){


        	foreach ($coursesReviewsFromDB as $key => $courseReview) {
 				$reviewArray = array();
        		$reviewRating = $this->crmodel->getRatingReviewId($courseReview['id']);

        		foreach ($reviewRating as $key1 => $value) {
        			$reviewArray[$value['description']] = $value['rating'];
        		}
        		

        		$coursesReviewsFromDB[$key]['reviewRating'] = $reviewArray;
        		
        	}
        
 
        	return $coursesReviewsFromDB;
        }

        function storeCourseIdsWithReviewsInCache() {
            $this->CI->load->model('listing/listingmodel');
            $this->ListingModel = new ListingModel();
            $coursesReviewsIdFromDB = $this->ListingModel->findCourseIdsWithReviews();
            $courseIds = array();
            foreach($coursesReviewsIdFromDB as $val) {
                $courseIds[] = $val['courseId'];
            }
            $this->getCourseReviewsData($courseIds,true);
            return true;
        }

    function getCourseIdsBySubCategoryId($subCatId){

    	if(empty($subCatId)){
    		return false;
    	}

		$this->CI->load->model('listing/listingmodel');
		$this->ListingModel = new ListingModel();
		
		$courseIdsFromDb    = $this->ListingModel->findCourseIdsBySubCategoryId($subCatId);
		$courseIds          = array();
		foreach($courseIdsFromDb as $val) {
		$courseIds[]        = $val['listing_type_id'];
		}
		
		if(empty($courseIds)){
    		return false;
    	}

		return $courseIds;
    }
    function sortCollegeReviews($collegeReviews, $criteria = "graduationYear") {
    	$dataToSort = array();
    	switch($criteria) {
    		case "graduationYear" : foreach($collegeReviews as $id=>$review) {
    									$dataToSort[$id] = $review['yearOfGraduation'];
									}
									arsort($dataToSort);
									break;
			case "freshness" : 		foreach($collegeReviews as $id=>$review) {
    									$dataToSort[$id] = $review['creationDate'];
									}
									arsort($dataToSort);
									break;
			case "lowestRated" : 	foreach($collegeReviews as $id=>$review) {
    									$dataToSort[$id] = $review['overallUserRating'];
									}
									asort($dataToSort);
									break;
			case "highestRated" : 	foreach($collegeReviews as $id=>$review) {
    									$dataToSort[$id] = $review['overallUserRating'];
									}
									arsort($dataToSort);
									break;
    	}
    	
    	
    	$i = 0;
    	$sortedCourseReviews = array();
    	foreach($dataToSort as $key=>$val) {
    		$sortedCourseReviews[$i] = $collegeReviews[$key];
    		$i++;
    	}
    	unset($collegeReviews);
    	return $sortedCourseReviews;
    }

    function getCollegeReviewsByCriteria($reviewsData,$countCriteria4PaidCourse = 3,$countCriteria4FreeCourse = 3){

    	$courseIdsToUnset = array();
    	foreach($reviewsData as $key=>$res){
    		if(isset($res['packType'])){
	    		if($res['packType'] == GOLD_SL_LISTINGS_BASE_PRODUCT_ID || $res['packType'] == SILVER_LISTINGS_BASE_PRODUCT_ID || $res['packType'] == GOLD_ML_LISTINGS_BASE_PRODUCT_ID){
	    			if(count($res['reviews']) < $countCriteria4PaidCourse){
	    				$reviewsData[$key] = array();
	    			}
	    		}else{
	    			if(count($res['reviews']) < $countCriteria4FreeCourse){
	    				$reviewsData[$key] = array();
	    			}
	    		}
	    	}
    	}

    	return $reviewsData;
    }

    /**
	 * [getCourseDominantSubCategoryDB will give dominant sub categories of multiple courses]
	 * @author Ankit Garg <g.ankit@shiksha.com>
	 * @date   2015-04-03
	 * @param  [array]     $courseIds [contains array of course ids]
	 * @return [array]     $dominantSubCategoryids [contains data of course id]
	 * @return [arary]     $allDominantSubCategoryIds [contains all dominant sub categories of a course]
	 */
    function getCourseDominantSubCategoryDB($courseIds = NULL) {
    	$TEMPLATE_SUBCAT_ID = 23;
		$subCategoryInfo = FALSE;
     	$allDominantSubCategoryIds = false;
     	
		if(!empty($courseIds)) {
			$ListingModel = $this->CI->load->model('listing/listingmodel');
			if(!is_object($this->LDBCourseRepository)){
				return false;
			}
			
			$subCategoriesForMultipleClient	= $this->LDBCourseRepository->getSubCategoriesForMultipleClientCourse($courseIds);
			$multipleSubCategoryIds = array();
			foreach($subCategoriesForMultipleClient as $singleLdbCourse) {
				$multipleSubCategoryIds[$singleLdbCourse['course_id']][] = $singleLdbCourse['category_id'];
			}
			
			$dominantSubCategory = FALSE;

			//getting course levels of multiple courses
			$courseLevelForMultipleCourses = $ListingModel->getCourseLevels($courseIds);
			foreach($courseLevelForMultipleCourses as $courseLevelData) {
				$courseLevelDetailsOfCourseForPostingFlow[$courseLevelData['course_id']]['level'] =  $this->getCourseLevel($courseLevelData['course_level'],$courseLevelData['course_level_1']);
				$courseLevelDetailsOfCourseForPostingFlow[$courseLevelData['course_id']]['level1'] = $courseLevelData['course_level_1'];
				$courseLevelDetailsOfCourseForPostingFlow[$courseLevelData['course_id']]['level2'] = $courseLevelData['course_level_2'];
			}
			
			foreach($courseIds as $courseId) {
				$dominantSubCategory = FALSE;
				if(!empty($courseLevelDetailsOfCourseForPostingFlow[$courseId])) {
					$MBATemplateEligibility = $this->checkForMBATemplateEligibility($multipleSubCategoryIds[$courseId], null, $courseLevelDetailsOfCourseForPostingFlow[$courseId]);
				}
				if($MBATemplateEligibility) {
					$dominantSubCategory = $TEMPLATE_SUBCAT_ID;
				} 
				else {
					if(!empty($multipleSubCategoryIds[$courseId])) {
						//Failed the check for MBA template, remove 23 from subcatid array
						foreach (array_keys($multipleSubCategoryIds[$courseId], $TEMPLATE_SUBCAT_ID) as $key) {
							unset($multipleSubCategoryIds[$courseId][$key]);
						}
						//If 23 was the only subcat id than it's out dominant subcatid
						if(empty($multipleSubCategoryIds[$courseId])){
							$dominantSubCategory = $TEMPLATE_SUBCAT_ID;
						}
						if(!empty($multipleSubCategoryIds[$courseId]) && empty($dominantSubCategory)) {
							$subCategoriesBasedOnCount = array_count_values($multipleSubCategoryIds[$courseId]);
							$dominantSubCategory = $this->_applyPreferredSubCategoryCheck($subCategoriesBasedOnCount, $preferredSubCategoryId);
							// if count of all subcats are equal then assign the count array to the 
							if($dominantSubCategory["allSubcatCountEqualFlag"]) {
								$subCategoryInfo[$courseId]["allSubcatCountEqualFlag"]   = $dominantSubCategory["allSubcatCountEqualFlag"];
								$dominantSubCategory["subCategoriesBasedOnCount"] = $subCategoriesBasedOnCount;
							}
							$dominantSubCategory['dominant'] = $dominantSubCategory["dominatSubCategoryId"];
							unset($dominantSubCategory["dominatSubCategoryId"]);
						}
					}
				}

				//condition to make array structure similar to old getdominantsubcat function
				if(is_array($dominantSubCategory)) {
					$subCategoryInfo[$courseId] = $dominantSubCategory;
				}
				else if($dominantSubCategory != '') {
					$subCategoryInfo[$courseId]['dominant'] = $dominantSubCategory;
				}

				$uniqueSubCategoriesIds = array_filter(array_unique($multipleSubCategoryIds[$courseId]));
				if(!empty($uniqueSubCategoriesId)) {
					$subCategoryInfo[$courseId]['subcategory_ids'] = $uniqueSubCategoriesId;
				}
				
				//saving all the dominant subcategories grouped by course
				if(isset($subCategoryInfo[$courseId]['dominant'])) {
					$allDominantSubCategoryIds[$courseId] = $subCategoryInfo[$courseId]['dominant'];
				}
			}
		}

		return array('subCategoryInfo' => $subCategoryInfo,'allDominantSubCategoryIds' => array_unique($allDominantSubCategoryIds));
    }

    function getCourseLevel($level,$level1) {
    	$mainCourseLevel = trim($level);
		if($mainCourseLevel == 'Dual Degree') {
			return $mainCourseLevel;
		}
		if($level1) {
			if($level1 == 'Post Graduate Diploma') {
				$mainCourseLevel = 'Post Graduate Diploma';
			}
			else if($level1 != $mainCourseLevel) {
				$mainCourseLevel = $level1.' '.$mainCourseLevel;
			}
		}
		return $mainCourseLevel;
    }

    function getCourseInstituteCategoryId($id, $type = null) {
    	if(!empty($id) && !empty($type)) {
	    	$ListingModel = $this->CI->load->model('listing/listingmodel');
			switch($type) {
			case "institute" : $subCategoryIds = array_filter($ListingModel->getAllSubCategoryIdsOfInstitute($id));
								break;
			case "course"    : $subCategoryIds = array_filter($ListingModel->getAllSubCategoryIdsOfCourse($id));
								break;								
			}
			$categoryIds = array();
			if(!empty($subCategoryIds)){
				$CategoriesObject = $this->getCategoryObjectFromCache($subCategoryIds);
				foreach($CategoriesObject as $CategoryObject) {
					$categoryIds[] = $CategoryObject->getParentId();
				}
			}
			return array_unique($categoryIds);
		}
    }

    function getCategoryObjectFromCache($subCategoryIds) {
    	//getching subcategory names from cache
    	$this->CI->load->builder('CategoryBuilder','categoryList');
		$categoryBuilder = new CategoryBuilder;
		$categoryRepository = $categoryBuilder->getCategoryRepository();
		return $categoryRepository->findMultiple($subCategoryIds); 
    }
    
	/***
	 * functionName : getOnlineFormAllCourses
	 * functionType : return an array
	 * desciption   : prepare online form dashboard config with otherCouse's as a virtual array
	 * @author      : akhter
	 * @team        : UGC
	***/
	/*
         *  This function is used in national rankingpages. Please co-ordinate if changes are made.
         */
	function getOnlineFormAllCourses()
	{
		$this->CI->load->library('dashboardconfig');
		$online_form_institute_seo_url = DashboardConfig::$institutes_autorization_details_array;
		$PBTSeoData = Modules::run('onlineFormEnterprise/PBTFormsAutomation/getExternalFormConfigDetails');
		$online_form_institute_seo_url += $PBTSeoData;
		$ListingModel = $this->CI->load->model('listing/listingmodel');
		$allOtherCourse = $ListingModel->getOnlineFormAllCourses();    
		
		if(count($allOtherCourse)>0){
			foreach($allOtherCourse as $c)
			{
				if($c['otherCourses'] !='')
				{
					$other = explode(',',$c['otherCourses']);
					if(count($other)>0)
					{
						foreach($other as $other)
						{
							$mainCourseId = $c['courseId'];
							$online_form_institute_seo_url[$other] = $online_form_institute_seo_url[$mainCourseId];
						}
					}
				}
			}
		}
		return $online_form_institute_seo_url;
	}
	
	// RETURN india/abroad/testprep
	function getCourseTypeById($course_id) {
		
		if($course_id <=0) {
				return;
		}
		
		$this->CI->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder();
		$courseRepository = $listingBuilder->getCourseRepository();
		$courseObj = $courseRepository->find($course_id);
		$countryId = $courseObj->getMainLocation()->getCountry()->getId();		
		
		unset($listingBuilder);
		unset($courseRepository);
		
		if($countryId == 2) {			
			$courseCategoryId = $courseObj->getDominantSubcategory()->getparentId();
			unset($courseObj);
			if($courseCategoryId == 14) {
				return 'testprep';
			} else {
				return 'domestic';
			}			
		} else {
				return 'abroad';
		}
		
	}
}
