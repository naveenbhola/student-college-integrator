<?php

class RankingCommonLib {

	private $_ci;
	private $categoryRepo;
	private $instituteRepo;
	// private $rankingFilterManager;
	private $courseRepo;
	private $locationRepo;
	private $ldbCourseRepo;
	private $reviewRatingCacheTTL = '86400';
	public function __construct($ci, $instituteRepo, $categoryRepo, $courseRepo, $locationRepo, $ldbCourseRepo){
		if(!empty($instituteRepo) &&
		   !empty($categoryRepo) &&
		   !empty($courseRepo) &&
		   !empty($locationRepo) &&
		   !empty($ldbCourseRepo)){
			// $this->rankingFilterManager = $rankingFilterManager;
			$this->instituteRepo 		= $instituteRepo;
			$this->categoryRepo 		= $categoryRepo;
			$this->courseRepo 			= $courseRepo;
			$this->locationRepo			= $locationRepo;
			$this->ldbCourseRepo		= $ldbCourseRepo;
			$this->_ci = $ci;
		}else{
			$this->_ci = &get_instance();
		}
		$this->_ci->load->model(RANKING_PAGE_MODULE.'/ranking_model');
		$this->rankingModel = new ranking_model();

		$this->_ci->load->builder("listingBase/ListingBaseBuilder");
        $baseCourseBuilder = new ListingBaseBuilder();
        $this->baseCourseRepo = $baseCourseBuilder->getBaseCourseRepository();
        $this->rankingCache = $this->_ci->load->library(RANKING_PAGE_MODULE.'/cache/RankingPageCache');
        $this->courseDetailLib = $this->_ci->load->library('nationalCourse/CourseDetailLib');
	}
        
       
	public function getScanParams($extraData,$userStatus){
		$gtmParams = array();
		$gtmParams['stream'] 			= $extraData['hierarchy']['streamId'];
		$gtmParams['substream'] 		= $extraData['hierarchy']['substreamId'];
		$gtmParams['specialization'] 	= $extraData['hierarchy']['specializationId'];
		$gtmParams['baseCourseId'] 		= $extraData['hierarchy']['baseCourseId'];
		$gtmParams['cityId'] 			= $extraData['cityId'];
		$gtmParams['stateId'] 			= $extraData['stateId'];
		$gtmParams['countryId'] 		= $extraData['countryId'];
		$gtmParams['educationType'] 	= $extraData['educationType'];
		$gtmParams['deliveryMethod'] 	= $extraData['deliveryMethod'];
		$gtmParams['credential'] 		= $extraData['credential'];
		$gtmParams['pageType'] 			= "Ranking";
		$gtmParams = array_filter($gtmParams);
		if($userStatus!='false' && $userStatus[0]['experience']!==""){
            $gtmParams['workExperience'] = $userStatus[0]['experience'];
        }
        return $gtmParams;
	}

        /*
         * A function to return overAllAverageReviewRating for all the courses of a Ranking Page
         * Input  : A rankingPage Object
         * OutPut : An array of overAllAverageRatings indexed at courseIds. If a course does not have any reviews value at it's index will be zero.
         *  [reviewRatingData] => Array
            (
            [250231] => 0
            [250233] => 0
            [250460] => 2.5
            )
         */
   //      public function getAverageReviewRatingPerCourseId($rankingPage){
   //          $overAllAverageRatingPerCourse = array();
   //          if(empty($rankingPage))
   //              return $overAllAverageRatingPerCourse;
            
   //          $rankingPageId = $rankingPage->getId();
			// $this->rankingCache = $this->_ci->load->library(RANKING_PAGE_MODULE.'/cache/RankingPageCache');
   //          if($this->_ci->enableRankingPageCache) {
	  //           $overAllAverageRatingPerCourse = $this->rankingCache->getAverageReviewRatingsForRankingPage($rankingPageId);
   //          }
   //          if(!empty($overAllAverageRatingPerCourse)){
   //              return $overAllAverageRatingPerCourse;
   //          }
   //          $courseIds = array();
   //          $params['ranking_page_id'] = $rankingPageId;
   //          $rankingPageDataFromDB = $this->rankingModel->getRankingPagesData($params);
   //          foreach($rankingPageDataFromDB['results'] as $rankDataPerCourse){
   //              $courseIds[] = $rankDataPerCourse['course_id'];
   //          }
   //          $courseIds = array_unique($courseIds);
   //          $collegeReviewModel         = $this->_ci->load->model('CollegeReviewForm/collegereviewmodel');
   //          $collegeReviewsByCourseId   = $collegeReviewModel->getAllReviewsByCourse($courseIds);
   //          $rankingPageData = $rankingPage->getRankingPageData();
   //          foreach($rankingPageData as $rankDataPerCourse){
   //              $courseId = $rankDataPerCourse->getCourseId();
   //              $overAllAverageRating = 0;
   //              if(isset($collegeReviewsByCourseId[$courseId])){
   //                  foreach($collegeReviewsByCourseId[$courseId]['reviews'] as $review){
   //                      $overAllAverageRating = $overAllAverageRating + $review['averageRating'];
   //                  }
   //                  $overAllAverageRating = $overAllAverageRating/$collegeReviewsByCourseId[$courseId]['totalCount'];
   //              }
   //              $overAllAverageRating = round($overAllAverageRating,1);
   //              $overAllAverageRatingPerCourse[$courseId] = $overAllAverageRating;
   //          }
   //          $this->rankingCache->storeAverageReviewRatingsForRankingPage($overAllAverageRatingPerCourse,$rankingPageId,$this->reviewRatingCacheTTL);
   //          return $overAllAverageRatingPerCourse;
   //      }
	/*
	*  Function to check the type of the ranking page. Based on the type some banners/widgets appear on ranking page.
	*/
	public function checkForRankingPageTupleWidget($rankingPage){
		$rankingPageOf = array();
		if(empty($rankingPage))
			return $rankingPageOf;
		$rankingPageOf['FullTimeMba'] = 0;
		$rankingPageOf['FullTimeEngineering'] = 0;
		$rankingPageOf['Engineering'] = 0;
		$streamId                   = $rankingPage->getStreamId();
		$subStreamId                = $rankingPage->getSubstreamId();
		$shikshaSpecializationId    = $rankingPage->getShikshaSpecializationId();
		$educationType              = $rankingPage->getEducationType();
		$deliveryMethod             = $rankingPage->getDeliveryMethod();
		$credential                 = $rankingPage->getCredential();
		$baseCourseId               = $rankingPage->getBaseCourseId();
                global $mbaBaseCourse;
                global $fullTimeEdType;
                global $btechBaseCourse;
		if($educationType == $fullTimeEdType && $baseCourseId == $mbaBaseCourse ){
			$rankingPageOf['FullTimeMba'] = 1;
		}
		if($educationType == $fullTimeEdType && $baseCourseId == $btechBaseCourse){
			$rankingPageOf['FullTimeEngineering'] = 1;
		}
		if($baseCourseId == $btechBaseCourse){
			$rankingPageOf['Engineering'] = 1;
		}
		return $rankingPageOf;
	}

	public function getTierUsingCombination(&$rankingPage){
		if(empty($rankingPage))
			return NULL;
		else {
			return $this->rankingModel->getTierUsingCombination($rankingPage);
		}
	}

	public function getCategories(){
		$appId = 1;
		$mainCategories = $this->categoryRepo->getSubCategories(1,'');
		$parentCategories = array();
		foreach($mainCategories as $category){
			$catDetails = array();
			$catDetails['categoryName'] = $category->getName();
			$catDetails['categoryID'] 	= $category->getId();
			$parentCategories[] = $catDetails;
		}
		foreach($parentCategories as $categoryInfo){
			$categoryId = $categoryInfo['categoryID'];
			if($categoryId == 1 || $categoryId == 14){ //Skip 'All' and 'Test Prepearation' category
				continue;
			}
			$parentCategoryList[$categoryId]['id']   =  $categoryInfo['categoryID'];
			$parentCategoryList[$categoryId]['name'] =  $categoryInfo['categoryName'];
			$subCategoryInfo = array();
			$subCategoriesOfthisCategory  = $this->categoryRepo->getSubCategories($categoryId, 'national');
			foreach($subCategoriesOfthisCategory as $subCategory){
				$subCategoryInfo[$subCategory->getId()] = array();
				$subCategoryInfo[$subCategory->getId()]['id'] = $subCategory->getId();
				$subCategoryInfo[$subCategory->getId()]['name'] = $subCategory->getName();
				$parentCategoryList[$categoryId]['subcategory'] = $subCategoryInfo;
			}
		}
		return $parentCategoryList;
	}
	
	public function getSpecializationDetails($parentCategories = array()){
		$subCategoryIds = array();
		$specializationDetails = array();
		foreach($parentCategories as $parentCategory){
			$ids = array_keys($parentCategory['subcategory']);
			foreach($ids as $id){
				$specializationDetails[$id] = $this->getSpecializationByCategory($id);
			}
		}
		return $specializationDetails;
	}
	
	public function getSpecializationByCategory($categoryId = NULL){
		$specializationList = array();
		if(!empty($categoryId)){
			$specializationDetails  = $this->ldbCourseRepo->getLDBCoursesForSubCategory($categoryId);
			foreach($specializationDetails as $specialization){
				$specializationName = $specialization->getSpecialization();
				if(strtolower($specializationName) != "all"){
					$tempSpecialization = array();
					$tempSpecialization['SpecializationId'] 	= $specialization->getId();
					$tempSpecialization['SpecializationName'] 	= $specialization->getSpecialization();
					$specializationList[] = $tempSpecialization;
				}
			}
		}
		return $specializationList;
	}
	
	public function getInstituteCourses($instituteId = NULL){
		$courses = array();
		if(!empty($instituteId)){
			if(empty($this->instituteRepo)){
				$this->_ci->load->builder('nationalInstitute/InstituteBuilder');
				$instituteBuilder = new InstituteBuilder();
				$this->instituteRepo = $instituteBuilder->getInstituteRepository();
			}
    		$instituteObject = $this->instituteRepo->find($instituteId);
			if(!empty($instituteObject)){
				$instituteName 	= $instituteObject->getName();
				$instituteId 	= $instituteObject->getId();
				if(!empty($instituteName)){
					$courseDetailsLocationWise = $this->instituteRepo->getLocationwiseCourseListForInstitute($instituteId);
					$courseIds = array();
					foreach($courseDetailsLocationWise as $courseList){
						$courseIds = array_merge($courseIds, $courseList['courselist']);
					}
					if(count($courseIds) > 0){
						$courses = $this->getCourseRankingDetails($courseIds);
					}
				}
			}
		}
		return $courses;
	}
	
	public function getCourseRankingDetails($courseIds = array()){
		$courses = array();
		if(empty($courseIds)){
			return $courses;
		}
		
		if(count($courseIds) > 0){
			$courseObjList = array();
			try {
				$courseObjList 	= $this->courseRepo->findMultiple($courseIds);
			} catch(Exception $e){
				error_log("RANKING function: getCourseRankingDetails error while fetching courses using findMultiple");
			}
			
			$tempCourseObjList = array();
			if(count($courseObjList) > 0){
				foreach($courseObjList as $courseObj){
					if(!empty($courseObj)){
						$courseName = $courseObj->getName();
						$courseId = $courseObj->getId();
						if(!empty($courseName)){
							$tempCourseObjList[$courseId] = $courseObj;
						}
					}
				}
			}
			$courseObjList  = $tempCourseObjList;
		
			$institueIds 	= array();
			foreach($courseObjList as $courseObj){
				$institueIds[] = $courseObj->getInstId();
			}
			
			$instituteObjsList = array();
			if(count($institueIds) > 0){
				$tempInstituteObjList = $this->instituteRepo->findMultiple($institueIds);
				foreach($tempInstituteObjList as $instituteObj){
					if(!empty($instituteObj)){
						$instituteName = $instituteObj->getName();
						$instituteId = $instituteObj->getId();
						if(!empty($instituteName)){
							$instituteObjsList[$instituteId] = $instituteObj;
						}
					}
				}
			}
			$searchModel 	= new searchmodel();
			$rankingModel 	= new ranking_model();
			foreach($courseObjList as $courseId => $courseObj){
				$courseInstituteId 		= $courseObj->getInstId();
				if(!array_key_exists($courseInstituteId, $instituteObjsList)){
					continue;
				}
				$instituteObject 		= $instituteObjsList[$courseInstituteId];
				$courseId 				= $courseObj->getId();
				$courseTitle 			= $courseObj->getName();
				$courseURL 				= $courseObj->getURL();
				$courseMainLocation 	= $courseObj->getMainLocation();
				$courseLocationId       = $courseMainLocation->getLocationId();
				
				$courseCityName 		= $courseMainLocation->getCity()->getName();
				$courseStateName 		= $courseMainLocation->getState()->getName();
				$examsObject			= $courseObj->getEligibilityExams();
				$exams = array();
				foreach($examsObject as $exam){
					$examAcronym  = $exam->getAcronym();
					$examMarks 	  = $exam->getMarks();
					$examMarkType = $exam->getMarksType();
					$exams[$examAcronym] = array();
					$exams[$examAcronym]['name'] 		= $examAcronym;
					$exams[$examAcronym]['marks'] 		= $examMarks;
					$exams[$examAcronym]['marks_type'] 	= $examMarkType;
				}
				
				$courseFeesObject 		= $courseObj->getFees($courseLocationId);
				$courseFeesValue		= $courseFeesObject->getValue();
				$courseFeesCurrency		= $courseFeesObject->getCurrency();
				
				$categories = $searchModel->getListingCategory($courseId, 'course');
				$courseSpecialization = $this->getSpecializationIdsByClientCourse($courseId);
				$specializationIds = array();
				foreach($courseSpecialization as $specialization){
					array_push($specializationIds, $specialization['SpecializationId']);
				}
				
				$instituteName 		= $instituteObject->getName();
				$instituteURL  		= $instituteObject->getURL();

				$courses[$courseId] = array();
				$courses[$courseId]['institute_id']  	= $courseInstituteId;
				$courses[$courseId]['institute_name']  	= $instituteName;
				$courses[$courseId]['institute_url']  	= $instituteURL;
				$courses[$courseId]['id']  				= $courseId;
				$courses[$courseId]['name']  			= $courseTitle;
				$courses[$courseId]['url']  			= $courseURL;
				$courses[$courseId]['city']  			= $courseCityName;
				$courses[$courseId]['state']  			= $courseStateName;
				$courses[$courseId]['exams']  			= $exams;
				$courses[$courseId]['fees_value']  		= $courseFeesValue;
				$courses[$courseId]['fees_currency']  	= $courseFeesCurrency;
				$courses[$courseId]['specialization_ids']  	= $specializationIds;
				$courses[$courseId]['category_ids']  	= $categories;
			}
		}
		return $courses;
	}
	
	public function getSpecializationIdsByClientCourse($courseId = NULL){
		$specializationDetails = array();
		if(!empty($courseId)){
			$courseModel = new coursemodel();
			$specializationDetails = $courseModel->getSpecializationIdsByClientCourse($courseId);
			if(!is_array($courseId)){
				$specializationDetails = $specializationDetails[$courseId];
			}
		}
		return $specializationDetails;
	}
	
	public function getPageHeadlineDetails(RankingPage $rankingPageObject = NULL, RankingPageRequest $rankingPageRequestObject = NULL){
		$details = array("page_name" => "", "location" => "", "exam" => "");
		if(empty($rankingPageObject) || empty($rankingPageRequestObject)){
			return $details;
		}
		
		$locationName 		= "";
		$cityName 			= $rankingPageRequestObject->getCityName();
		$stateName 			= $rankingPageRequestObject->getStateName();
		$countryName 		= $rankingPageRequestObject->getCountryName();
		$examName			= $rankingPageRequestObject->getExamName();
		$rankingPageName 	= $rankingPageObject->getName();
		
		/* Priority should be given to city than state than country */
		if(!empty($cityName)){
			$locationName = $cityName;
		} else if(!empty($stateName)){
			$locationName = $stateName;
		} else if(!empty($countryName)){
			$locationName = $countryName;
		}
		
		$details["page_name"] = $rankingPageName;
		if(!empty($locationName)){
			$details["location"] = $locationName;
		}
		if(!empty($examName)){
			$details["exam"] = $examName;
		}
		return $details;
	}
	
	/**
	 *@method: If based on filters selected by user the ranking page has less no of data rows than fetch extra ranking page data blocks.
	 *Exam and location based data blocks.
	 */
	// public function getRankingPartialResults(RankingPage $rankingPageObject = NULL, RankingPageRequest $rankingPageRequestObject = NULL){
	// 	$partialResults = array("exam" => array(), "location" => array());
	// 	if(empty($rankingPageObject) || empty($rankingPageRequestObject)){
	// 		return $partialResults;
	// 	}
	// 	$rankingPageData 	= $rankingPageObject->getRankingPageData();
	// 	$rankingRowsCount 	= count($rankingPageData);
	// 	$minimumRows 		= $this->_ci->config->item('MINIMUM_RANKING_PAGE_RESULTS');
	// 	$maxResultCountInExtraDataBlock = $this->_ci->config->item('MAXIMUM_RESULTS_COUNT_IN_EXTRA_DATA_BLOCK');
	// 	if($rankingRowsCount > $minimumRows){
	// 		return $partialResults;
	// 	}
		
	// 	$rankingPageId  = $rankingPageObject->getId();
	// 	$cityId 		= $rankingPageRequestObject->getCityId();
	// 	$cityName		= $rankingPageRequestObject->getCityName();
	// 	$stateId 		= $rankingPageRequestObject->getStateId();
	// 	$stateName 		= $rankingPageRequestObject->getStateName();
	// 	$examId			= $rankingPageRequestObject->getExamId();
	// 	$examName 		= $rankingPageRequestObject->getExamName();
	// 	$locationId		= "";
	// 	$locationType	= "";
	// 	if(!empty($cityId)){
	// 		$locationId 	= $cityId;
	// 		$locationType 	= "city";
	// 	} else if(!empty($stateId)){
	// 		$locationId 	= $stateId;
	// 		$locationType 	= "state";
	// 	}
	// 	if(empty($locationId) || empty($examId)) {
	// 		return $partialResults;
	// 	}
		
	// 	$rankingPageRepository  = RankingPageBuilder::getRankingPageRepository();
	// 	$rankingPageComplete 	= $rankingPageRepository->find($rankingPageId);
		
	// 	$examRequestObject 			= clone $rankingPageRequestObject;
	// 	$locationRequestObject 		= clone $rankingPageRequestObject;
	// 	$examRankingPageObject 		= clone $rankingPageComplete;
	// 	$locationRankingPageObject 	= clone $rankingPageComplete;
		
	// 	$examRequestObject->setExamId($examId);
	// 	$examRequestObject->setExamName($examName);
	// 	$examRequestObject->setCityId(0);
	// 	$examRequestObject->setCityName("");
	// 	$examRequestObject->setStateId(0);
	// 	$examRequestObject->setStateName("");
	// 	$examRequestObject->setCountryId(2);
	// 	$this->rankingFilterManager->applyFilters($examRankingPageObject, $examRequestObject);
	// 	$examRankingPageHeadline = $this->getPageHeadlineDetails($examRankingPageObject, $examRequestObject);
		
	// 	$locationRequestObject->setExamId(0);
	// 	$locationRequestObject->setExamName("");
	// 	if($locationType == "city"){
	// 		$locationRequestObject->setCityId($cityId);
	// 		$locationRequestObject->setCityName($cityName);
	// 		$locationRequestObject->setStateId(0);
	// 		$locationRequestObject->setStateName("");
	// 	} else if($locationType == "state"){
	// 		$locationRequestObject->setCityId(0);
	// 		$locationRequestObject->setCityName("");
	// 		$locationRequestObject->setStateId($stateId);
	// 		$locationRequestObject->setStateName($stateName);
	// 	}
	// 	$locationRequestObject->setCountryId(0);
	// 	$locationRequestObject->setCountryName("");
		
	// 	$this->rankingFilterManager->applyFilters($locationRankingPageObject, $locationRequestObject);
	// 	$locationRankingPageHeadline = $this->getPageHeadlineDetails($locationRankingPageObject, $locationRequestObject);
		
	// 	$examRankingPageData = $examRankingPageObject->getRankingPageData();
	// 	$examRankingPageDataCount = count($examRankingPageData);
	// 	if($examRankingPageDataCount > $maxResultCountInExtraDataBlock){
	// 		$examRankingPageData = array_slice($examRankingPageData, 0, $maxResultCountInExtraDataBlock);
	// 		$examRankingPageObject->setRankingPageData($examRankingPageData);
	// 	}
		
	// 	$locationRankingPageData = $locationRankingPageObject->getRankingPageData();
	// 	$locationRankingPageDataCount = count($locationRankingPageData);
	// 	if(count($locationRankingPageData) > $maxResultCountInExtraDataBlock){
	// 		$locationRankingPageData = array_slice($locationRankingPageData, 0, $maxResultCountInExtraDataBlock);
	// 		$locationRankingPageObject->setRankingPageData($locationRankingPageData);
	// 	}
		
	// 	$partialResults['exam']['ranking_page']  = $examRankingPageObject;
	// 	$partialResults['exam']['page_request']  = $examRequestObject;
	// 	$partialResults['exam']['page_heading']  = $examRankingPageHeadline;
	// 	$partialResults['exam']['total_results'] = $examRankingPageDataCount;
	// 	$partialResults['exam']['max_rows'] 	 = $maxResultCountInExtraDataBlock;
	// 	$partialResults['exam']['offset_reach']  = (int)$maxResultCountInExtraDataBlock;
		
	// 	$partialResults['location']['ranking_page']  = $locationRankingPageObject;
	// 	$partialResults['location']['page_request']  = $locationRequestObject;
	// 	$partialResults['location']['page_heading']  = $locationRankingPageHeadline;
	// 	$partialResults['location']['total_results'] = $locationRankingPageDataCount;
	// 	$partialResults['location']['max_rows'] 	 = $maxResultCountInExtraDataBlock;
	// 	$partialResults['location']['offset_reach']  = (int)$maxResultCountInExtraDataBlock;
		
	// 	return $partialResults;
	// }
	
	public function applyLimitOnRankingPage(RankingPage $rankingPage, $offset_reach = 10){
		if(empty($rankingPage)){
			return;
		}
		$rankingPageData 		= $rankingPage->getRankingPageData();
		$rankingPageDataCount 	= count($rankingPageData);
		$rankingPageDataSlice = array();
		if($offset_reach < $rankingPageDataCount){
			$rankingPageDataSlice = array_slice($rankingPageData, 0, $offset_reach);
		}
		if(!empty($rankingPageDataSlice)){
			$rankingPage->setRankingPageData($rankingPageDataSlice);
		}
	}
	
	public function getBrochureRelatedData($rankingPageData = array(), $resultType = "complete"){
		global $localityArray;
		global $partial_localityArray;
		$coursesWithMultipleLocation 	= array();
		$studyAbroadCourses 		 	= array();
		$instituteDetails 				= array();
		$courseDetails 					= array();
		$encounteredCourseIds           = array();
		foreach($rankingPageData as $pageData){
			//if($pageData->isCoursePaid()){
			if(true){
				$instituteId 	= $pageData->getInstituteId();
				$instituteName 	= $pageData->getInstituteName();
				$courseId 		= $pageData->getCourseId();
				$courseName 	= $pageData->getCourseName();
				// $cityObject 	= $pageData->getLocation()->getCity();
				$cityName 		= $pageData->getCityName();
				$cityId 		= $pageData->getCityId();
				
				$instituteMainLocation 	= $pageData->getInstituteMainLocation();
				$instituteCityName 		= $instituteMainLocation->getCity()->getName();
				$instituteCityId		= $instituteMainLocation->getCity()->getId();
				$courseAllLocations 	= $pageData->getAllLocations();
				if(!array_key_exists($instituteId, $instituteDetails)){
					$instituteDetails[$instituteId] = array();
					$instituteDetails[$instituteId]['id'] 		= trim($instituteId);
					$instituteDetails[$instituteId]['name'] 	= strip_tags(trim($instituteName));
					$instituteDetails[$instituteId]['city_id'] 	= trim($instituteCityId);
					$instituteDetails[$instituteId]['city_name']= trim($instituteCityName);
				}
				if(!array_key_exists($instituteId, $courseDetails)){
					$courseDetails[$instituteId] = array();
				}
				if(!array_key_exists($courseId, $encounteredCourseIds)){
					$courseDetail = array();
					$courseDetail['id'] 				= trim($courseId);
					$courseDetail['name'] 				= trim($courseName);
					$courseDetails[$instituteId][] 		= $courseDetail;
					$encounteredCourseIds[] 			= $courseId;
					if($resultType == "partial"){
						$partial_localityArray[$courseId] 	= getLocationsCityWise($courseAllLocations);
					} else {
						$localityArray[$courseId] 			= getLocationsCityWise($courseAllLocations);
					}
				}
				if(count($courseAllLocations) > 1){
					if(!in_array($courseId, $coursesWithMultipleLocation)){
						$coursesWithMultipleLocation[] = $courseId;
					}
				}
				if($pageData->isStudyAbroadCourse()){
					if(!in_array($courseId, $studyAbroadCourses)){
						$studyAbroadCourses[] = $courseId;
					}
				}
			}
		}
		$returnData = array();
		$returnData['multiple_location_courses'] = $coursesWithMultipleLocation;
		$returnData['study_abroad_courses'] 	 = $studyAbroadCourses;
		$returnData['institute_details'] 		 = $instituteDetails;
		$returnData['course_details'] 	 		 = $courseDetails;
		if($resultType == "partial"){
			$returnData['partial_localityArray'] = $partial_localityArray;
		} else {
			$returnData['localityArray'] 		 = $localityArray;
		}
		return $returnData;
	}
	
	public function getRankingPageCategoryDetails(RankingPage $rankingPage = NULL){
		$categoryDetails = array();
		if(empty($rankingPage)){
			return $categoryDetails;
		}
		$categoryId 	= $rankingPage->getCategoryId();
		$subCategoryId 	= $rankingPage->getSubCategoryId();
		$category 		= $this->categoryRepo->find($categoryId);
		$categoryName 	= $category->getName();
		
		$subcategory     = $this->categoryRepo->find($subCategoryId);
		$subcategoryName = $subcategory->getName();
		$categoryDetails['name'] 		= $categoryName;
		$categoryDetails['subcat_name'] = $subcategoryName;
		
		return $categoryDetails;
	}
	
	public function populateCityStateInBannerData($bannerData = array()){
		$bannerDataUpdated = array();
		foreach($bannerData as $banner){
			$cityId 	= $banner['city_id'];
			$stateId 	= $banner['state_id'];
			if(!empty($cityId)){
				$city = $this->locationRepo->findCity($cityId);
				if($city){
					$cityName = $city->getName();
					$banner['city_name'] = $cityName;
				}
			}
			if(!empty($stateId)){
				$state = $this->locationRepo->findState($stateId);
				if($state){
					$stateName = $state->getName();
					$banner['state_name'] = $stateName;
				}
			}
			$bannerDataUpdated[] = $banner;
		}
		return $bannerDataUpdated;
	}
	
	/**
	@method: Get banner details that needs to be displayed on ranking page.
	@return: banner details in array format
	*/
	public function getRankingPageBannerDetails(RankingPage $rankingPage, RankingPageRequest $rankingPageRequest){
		$banner = array();
		if(empty($rankingPage) || empty($rankingPageRequest)){
			return $banner;
		}
		
		$rankingPageId 	= $rankingPage->getId();
		$cityId 		= $rankingPageRequest->getCityId();
		$stateId 		= $rankingPageRequest->getStateId();
		
		$rankingModel 	= new ranking_model();
		$banners 		= array();
		if(!empty($cityId) && empty($stateId)){
			$banners = $rankingModel->getBannersByLocationAndRankingPage($rankingPageId, "city", $cityId);
		}
		if(!empty($stateId) && empty($cityId)){
			$banners = $rankingModel->getBannersByLocationAndRankingPage($rankingPageId, "state", $stateId);
		}
		if(empty($cityId) && empty($stateId)){
			$banners = $rankingModel->getBannersByLocationAndRankingPage($rankingPageId, "city", 1); //If city and state is blank than consider this as all cities page
		}
		
		$banner = $this->_rotateBanners($banners, $rankingPageId);
		return $banner;
	}
	
	private function _rotateBanners($banners = array(), $rankingPageId){
		if(empty($banners)){
			return array();
		}
		$this->_ci->load->library('listing/cache/ListingCache');
		$totalBanners 		= count($banners);
		$listingCacheObj 	= new ListingCache();
		$cacheCounterKey 	= "RPB_CNT_".$rankingPageId;
		$counter 			= $listingCacheObj->increment($cacheCounterKey);
		$mod = $counter % $totalBanners;
		$banner = $banners[$mod];
		return $banner;
	}
	
	public function getArticleQuickLinks(RankingPage $rankingPage = NULL, RankingPageRequest $rankingPageRequest = NULL){
		if(empty($rankingPage) || empty($rankingPageRequest)){
			return false;
		}
		$categoryId 	= $rankingPage->getCategoryId();
		$subCategoryId 	= $rankingPage->getSubCategoryId();
		$this->_ci->load->library('Blog_client');
        $blogClientObj = new Blog_client();
		$countryId 	= $rankingPageRequest->getCountryId();
		if($countryId == 0){
			$countryId = 2;
		}
		$regionId 	= 0;
		
		$this->_ci->load->library('categoryList/categoryPageRequest');
    	$returnData = array();
		
		$category 			= $this->categoryRepo->find($subCategoryId);
		$subCategoryName 	= $category->getName();
		if(!empty($categoryId) && !empty($subCategoryId)){
			$requestURL = new CategoryPageRequest();
			$requestURL->setData(array('categoryId'=>$categoryId, 'subCategoryId'=> $subCategoryId));
			$returnData['quickLinkURL'] 	 = $requestURL->getURL();
			$returnData['articleQuickLinks'] = $blogClientObj->getArticleWidgetsData('quick_links', $categoryId, $subCategoryId, $countryId, $regionId);
			$returnData['subCategoryName']	 = $subCategoryName;
		}
		return $returnData;
    }
	
	public function shareRankingPageViaEmail($emailIdString = NULL, $rankingPage = NULL, $email = NULL, $rankingPageMetaDetails = NULL, $userName = NULL, $currentPageUrl = NULL){
		$return = array();
		$emailList = array();
		$loggedInUserEmail = $email;
		if(!empty($emailIdString)){
			$emailList = explode(",", $emailIdString);
			$emailList = array_map('trim', $emailList);
		}
		if(empty($emailList)){
			$return['success'] = "false";
			$return['error_type'] 	= "INVALID_INPUT_PARAMS";
			return $return;
		}
		
		$emailSent = array();
		$emailUnsent = array();
		$this->_ci->load->library("alerts_client");
		$alertClientObj = new Alerts_client();
		foreach($emailList as $userEmail){
			$userEmail = trim($userEmail);
			if(strlen($userEmail) > 0){
				$mailType = "second_person";
				if(!empty($loggedInUserEmail)){
					if(trim($loggedInUserEmail) == trim($userEmail)){
						$mailType = "first_person";
					}
				}
				$subject = $this->getRankingEmailSubject($rankingPageMetaDetails);
				$content = $this->getRankingEmailContent($rankingPageMetaDetails, $rankingPage, $mailType, $userEmail, $userName, $currentPageUrl);
				$alertResponse = false;
				if(!empty($subject) && !empty($content)){
					$alertResponse = $alertClientObj->externalQueueAdd(12, ADMIN_EMAIL, $userEmail, $subject, $content, "html");
				}
				if($alertResponse){
					$emailSent[] = $userEmail;
				} else {
					$emailUnsent[] = $userEmail;
				}
			}
		}
		if(count($emailSent) > 0){
			$return['success'] = "true";
		} else {
			$return['success'] = "false";
		}
		return $return;
	}
	
	public function getRankingEmailSubject($rankingPageMetaDetails = NULL){
		if(empty($rankingPageMetaDetails)){
			return false;
		}
		return $rankingPageMetaDetails['title'];
	}
	
	public function getRankingEmailContent($rankingPageMetaDetails = NULL, $rankingPage = NULL, $mailType = "second_person", $userEmail = NULL, $userName = NULL, $currentPageURL = NULL) {
		if(empty($rankingPageMetaDetails) || empty($rankingPage) || empty($mailType) || empty($userEmail) || empty($currentPageURL) ){
			return false;
		}
		$emailContentPrefix = $this->getRankingPageHTMLPrefix();
		$pageTitle = $rankingPageMetaDetails['title'];
		if($mailType == "first_person"){
			$userTitle = $userName;
			if(empty($userName)){
				$userTitle = $userEmail;
			}
			$dynamicContent = 'Dear '.$userTitle.',<br/><br/>
							You were looking for '. trim($pageTitle, "."). ' on Shiksha<span style="font-size:1px;"></span>.com. 
							As per your request, we are happy to mail you the link to this page.<br><br>
							Now access the exact information you need anytime, anywhere.</font>
							';
		} else if($mailType == "second_person"){
			$dynamicContent = 'Dear '.$userEmail.',<br/>
								<br/>
								It seems you are interested in '. trim($pageTitle, ".") . '.
								Your friend or a well-wisher found this information on Shiksha.com and wants you also to benefit from it. We hope you too find it as interesting and useful.<br>
								<br>
								In case you need rankings to compare institutes in any other category, you can do that too on Shiksha<span style="font-size:1px;"> </span>.com.
								</font>';
		}
		$emailContentSuffix = $this->getRankingPageHTMLSuffix($rankingPage, $currentPageURL);
		if(empty($emailContentSuffix)){
			return false;
		}
		$mailContent = $emailContentPrefix . $dynamicContent . $emailContentSuffix;
		return $mailContent;
	}
	
	public function getRankingPageHTMLPrefix(){
		$html = '
		<html>
		<head>
		<title>Shiksha.com</title>
		</head>
		<body>
		<table border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#e3e5e8" style="max-width:600px; min-width:320px; -webkit-text-size-adjust: 100%;">
		  <tr>
			<td height="40" width="20"></td>
			<td align="right" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#a3a3a3; padding-right:5px;"></td>
			<td width="20"></td>
		  </tr>
		  <tr>
			<td></td>
			<td><table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#333333">
				<tr>
				  <td valign="top" width="280"><img src="http://ieplads.com/mailers/2013/shiksha/prod/rank3apr/images/img1.gif" width="6" height="6" vspace="0" hspace="0" align="left" /></td>
				  <td valign="top" width="280" align="right"><img src="http://ieplads.com/mailers/2013/shiksha/prod/rank3apr/images/img2.gif" width="6" height="6" vspace="0" hspace="0" align="right" /></td>
				</tr>
			  </table></td>
			<td></td>
		  </tr>
		  <tr>
			<td></td>
			<td bgcolor="#333333" style="padding-left:18px;"><table border="0" cellspacing="0" cellpadding="0" align="left">
				<tr>
				  <td height="75" width="214"><a href="http://www.shiksha.com/" target="_blank"><img src="http://ieplads.com/mailers/2013/shiksha/prod/rank3apr/images/logo.gif" width="214" height="48" vspace="0" hspace="0" align="left" border="0" title="shiksha.com" alt="shiksha.com" style="font-family:Verdana, Arial, sans-serif; font-size:25px; font-weight:bold; color:#faab4e;" /></a></td>
				  <td width="29"></td>
				</tr>
			  </table>
			  <table border="0" cellspacing="0" cellpadding="0" style="text-align:right;">
				<tr>
				  <td height="75" width="281"></td>
				  <td width="18"></td>
				</tr>
			  </table></td>
			<td></td>
		  </tr>
		  <tr>
			<td></td>
			<td><table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
				<tr>
				  <td height="20"></td>
				  <td></td>
				  <td></td>
				</tr>
				<tr>
				  <td width="30"></td>
				  <td width="500"><font face="Arial, Helvetica, sans-serif" color="#444648" style="font-size:15px; line-height:18px;">';
		  
		return $html;
	}
	
	public function getRankingPageHTMLSuffix($rankingPage, $currentPageURL){
		$tableHTML = $this->getRankingDataTableHTML($rankingPage);
		if(empty($tableHTML)){
			return false;
		}
		$html = '</td>
					<td width="29"></td>
				  </tr>
				  <tr>
					<td height="18"></td>
					<td></td>
					<td></td>
				  </tr>';
				  
		$html .= $tableHTML;
		$html .= '<tr>
					<td height="15"></td>
					<td></td>
					<td></td>
				  </tr>
				  <tr>
					<td height="15"></td>
					<td><table width="166" border="0" cellspacing="0" cellpadding="0" background="http://ieplads.com/mailers/2013/shiksha/prod/rank3apr/images/rank_butt.gif" bgcolor="#ffd00f">
						<tr>
						  <td width="166" height="35" style="font-family:Arial, Helvetica, sans-serif; font-size:15px; color:#333333;"><a href="'.$currentPageURL.'" style="font-size:15px; text-align:center; display:block; height:35px; line-height:35px; text-decoration:none; color:#333333;" title="Click here to view all" target="_blank">Click here to view all</a></td>
						</tr>
					  </table></td>
					<td></td>
				  </tr>
				  <tr>
					<td height="30"></td>
					<td></td>
					<td></td>
				  </tr>
				  <tr>
					<td height="20"></td>
					<td width="500"><font face="Arial, Helvetica, sans-serif" color="#444648" style="font-size:15px; line-height:18px;">Regards,<br/>
					  Shiksha<span style="font-size:1px;"> </span>.com</font></td>
					<td></td>
				  </tr>
				  <tr>
					<td height="20"></td>
					<td></td>
					<td></td>
				  </tr>
				</table></td>
			  <td></td>
			</tr>
			<tr>
			  <td></td>
			  <td><table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#333333" style="font-size:12px; color:#FFFFFF;">
				  <tr>
					<td height="30" width="30" bgcolor="#FFFFFF"></td>
					<td width="15"></td>
					<td><font face="Georgia, Times New Roman, Times, serif">Plan your career. <a href="http://ask.shiksha.com/" target="_blank" style="font-size:12px; text-decoration:underline; color:#ffe474;"><strong>Ask our experts now!</strong></a></font></td>
					<td width="29" bgcolor="#FFFFFF"></td>
				  </tr>
				</table></td>
			  <td></td>
			</tr>
			<tr>
			  <td></td>
			  <td><table width="100%" border="0" cellspacing="0" bgcolor="#FFFFFF" cellpadding="0" style="font-size:12px; color:#FFFFFF;">
				  <tr>
					<td width="30" height="90"></td>
					<td width="500"><font face="Tahoma, Arial, sans-serif" color="#9d9d9d" style="font-size:10px; line-height:14px;">This is a system generated e-mail, please do not reply to this message. We recommend that you visit <a href="http://www.shiksha.com/shikshaHelp/ShikshaHelp/termCondition" target="_blank" style="font-size:10px; text-decoration:none; color:#3465e8;">Terms &amp;
					  Conditions</a> for more comprehensive information.<br/></font></td>
					<td width="29"></td>
				  </tr>
				</table></td>
			  <td></td>
			</tr>
			</tr>
			
			<tr>
			  <td></td>
			  <td><table width="100%" border="0" cellspacing="0" bgcolor="#FFFFFF" cellpadding="0">
				  <tr>
					<td valign="bottom"><img src="http://ieplads.com/mailers/2013/shiksha/prod/rank3apr/images/img9.gif" width="6" height="6" vspace="0" hspace="0" align="left" /></td>
					<td></td>
					<td valign="bottom"><img src="http://ieplads.com/mailers/2013/shiksha/prod/rank3apr/images/img10.gif" width="6" height="6" vspace="0" hspace="0" align="right" /></td>
				  </tr>
				</table></td>
			  <td></td>
			</tr>
			<tr>
			  <td height="20"></td>
			  <td></td>
			  <td></td>
			</tr>
		  </table>
		  </body>
		  </html>
		  ';
		return $html;
	}
	
	public function getRankingDataTableHTML($rankingPage){
		$rankingPageData = array();
		if(!empty($rankingPage)){
			$rankingPageData = $rankingPage->getRankingPageData();
		}
		if(empty($rankingPageData)){
			return false;
		}
		$cutOffExist = false;
		$tempCount = 0;
		foreach($rankingPageData as $pageData){
			if($tempCount >= 5){
				break;
			}
			$tempExams = $pageData->getExams();
			if(!empty($tempExams)){
				$cutOffExist =  true;
				break;
			}
			$tempCount++;
		}
		$lastColStyle = "";
		if($cutOffExist){
			$lastColStyle = "border-right:1px solid #e3e3e3;";
		}
		$html = '
		<tr>
          <td height="18"></td>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #e3e3e3; font-family:\'Trebuchet MS\', Arial, Helvetica, sans-serif; font-size:13px; text-align:left;">
              <tr bgcolor="#f2f8ff">
                <td height="50" align="center" width="43" style="border-right:1px solid #e3e3e3; border-bottom:1px solid #e3e3e3;">Rank</td>
                <td width="5" style="border-bottom:1px solid #e3e3e3;"><font></font></td>
                <td width="187" style="border-right:1px solid #e3e3e3; border-bottom:1px solid #e3e3e3;">Name of Institute</td>
                <td width="5" style="border-bottom:1px solid #e3e3e3;"><font></font></td>
                <td width="83" style="border-right:1px solid #e3e3e3; border-bottom:1px solid #e3e3e3;">City</td>
                <td width="5" style="border-bottom:1px solid #e3e3e3;"><font></font></td>
                <td width="53" style="'. $lastColStyle .' border-bottom:1px solid #e3e3e3;">Course</td>
		';
		if($cutOffExist){
			$html .= '<td width="5" style="border-bottom:1px solid #e3e3e3;"><font></font></td>
					  <td style="border-bottom:1px solid #e3e3e3;">Cutt-off</td>';
		}
		$html .= '</tr>';
		
		$tableHTML = "";
		$count = 0;
		foreach($rankingPageData as $pageData){
			if($count >= 5){
				break;
			}
			$id 			= $pageData->getId();
			$exams 			= $pageData->getExams();
			usort($exams, 'sortExamsInUI');
			$validExams = $exams;
			$examString 	= "";
			if(count($exams) > 0){
				$mainExams = array_slice($exams, 0, 1);
				$examString .= $mainExams[0]['name'];
				if(!empty($mainExams[0]['marks'])){
					$examString .= ": ". $mainExams[0]['marks'];
				}
				$examString = trim($examString);
				$examString = trim($examString, ",");
			}
			
			$lastColStyle = "";
			if($cutOffExist){
				$lastColStyle = "border-right:1px solid #e3e3e3;";
			}
			$tableHTML .= '
				<tr style="font-size:12px; color:#353535;">
					<td height="35" align="center" style="border-right:1px solid #e3e3e3;">'.$pageData->getRank().'</td>
					<td></td>
					<td style="border-right:1px solid #e3e3e3;">'.$pageData->getInstituteName().'</td>
					<td></td>
					<td style="border-right:1px solid #e3e3e3;">'.$pageData->getCityName().'</td>
					<td></td>
					<td style="'.$lastColStyle.'">'.$pageData->getCourseAltText().'</td>
					';
			if($cutOffExist){
				$tableHTML .= '<td></td><td>'.$examString.'</td>';
			}
			$tableHTML .= '</tr>';
			$count++;
		}
		$html .= $tableHTML;
		$html .= '</table></td><td></td></tr>';
		return $html;
	}
	
	public function getAllSources() {
		return $this->rankingModel->getAllSources();
	}
        
        function getExamPageLinks($subCatId) {
            $examTabExamPageLinks = array();
            switch($subCatId) {
                case 56 : global $engineeringExamPageLinks;
                          $examTabExamPageLinks = $engineeringExamPageLinks;
                          break;
                case 23 : global $mbaExamPageLinks;
                          $examTabExamPageLinks = $mbaExamPageLinks;
                          break;
            }
            return $examTabExamPageLinks;
        }
	
	public function getSourceWiseCourseRanks($courseId, $rank) {
		if(empty($courseId)) {
			return false;
		}
		$sourcesWithRanks = $this->rankingModel->getSourceWiseCourseRanks($courseId, $rank);
		
		$latestRanksCheck = array();
		$sourcesWithRanksLatest = array();
		foreach ($sourcesWithRanks as $row) {
			if(empty($latestRanksCheck[$row['publisher_id']])){
				$latestRanksCheck[$row['publisher_id']] = 1;
				$sourcesWithRanksLatest[] = $row;
			}
		}
		$sourcesWithRanks = $sourcesWithRanksLatest;
		
		$uniqueSourceIds = array();
		$uniqueSourcesWithRanks = array();
		foreach($sourcesWithRanks as $source) {
			if(!in_array($source['source_id'], $uniqueSourceIds) && !empty($source['rank'])) {
				$uniqueSourceIds[] = $source['source_id'];
				$uniqueSourcesWithRanks[] = $source;
			}
		}
		return $uniqueSourcesWithRanks;
	}

	public function deleteNaukriData($instituteIds){
		if(!isset($this->rankingCache)){
			$this->rankingCache = $this->_ci->load->library(RANKING_PAGE_MODULE.'/cache/RankingPageCache');
		}
		foreach ($instituteIds as $id) {
			$this->rankingCache->deleteInstituteNaukriData($id);
		}
	}

	public function getInstitutesNaukriData(&$rankingPage, $instituteIds){
		$instituteWiseNaukriData = array();
		if(!isset($this->rankingCache)){
			$this->rankingCache = $this->_ci->load->library(RANKING_PAGE_MODULE.'/cache/RankingPageCache');
		}

		if($this->_ci->input->get('resetNaukriData') != 1){
			$instituteWiseNaukriData = $this->rankingCache->getMultipleInstitutesNaukriData($instituteIds);//_p($instituteWiseNaukriData);die;
		}
		$missingInstitutes = array_diff($instituteIds, array_keys($instituteWiseNaukriData));//_p($missingInstitutes);die;
		if(count($missingInstitutes) > 0){
			$instituteDetailsModel = $this->_ci->load->model('nationalInstitute/institutedetailsmodel');
			//$coursemodel = $this->_ci->load->model('listing/coursemodel');

			$salaryDataResults 	= $instituteDetailsModel->getNaukriSalaryData($instituteIds, 'multiple');//_p($salaryDataResults);die;
			//$not_mapped_to_full_time_mba = array();
			
			//$specialization_list = $coursemodel->getSpecializationIdsByClientCourse($courseIds,TRUE);
			// get the naukri salary data
			foreach($salaryDataResults as $naukriDataRow)
			{
				if($naukriDataRow['exp_bucket'] == '2-5')
					$instituteWiseNaukriData[$naukriDataRow['institute_id']] = $naukriDataRow;
				
				$totalEmployees[$naukriDataRow['institute_id']] += $naukriDataRow['tot_emp'];
			}
			
			// check which-all courses are not mapped to full-time mba
			/*foreach($specialization_list as $courseId=>$rowArray) {
				foreach($rowArray as $row)
				{
					if(!$not_mapped_to_full_time_mba[$courseId]) {
						if(!($row['ParentId'] == 2 || $row['SpecializationId'] == 2)) {
							$not_mapped_to_full_time_mba[$courseId] = TRUE;
						} 
					}
				}
			} */
			
			// unset the naukri data for institutes whose employee count is less than 30
			foreach($totalEmployees as $instituteId => $employeeCount)
			{
				if($employeeCount < 30)
					$instituteWiseNaukriData[$instituteId] = 0;
			}
			
			// unset the naukri data for institutes whose courses are not mapped to full-time mba
			/*foreach($not_mapped_to_full_time_mba as $courseId=>$value)
			{
				if($value)
					$instituteWiseNaukriData[$instituteCourseMapping[$courseId]] = 0;
			}
                         * *
                         */

			$missingInstitutes = array_diff($instituteIds, array_keys($instituteWiseNaukriData));
			foreach($missingInstitutes as $instId){
				$instituteWiseNaukriData[$instId] = 0;
			}
			
			foreach ($instituteWiseNaukriData as $instId => $arr) {
				$this->rankingCache->storeInstituteNaukriData($instId,$arr);
			}
		}
		$rankingPageDetails = $rankingPage->getRankingPageData();
		foreach($rankingPageDetails as $rankingPageRow)
		{
			$instituteId = $rankingPageRow->getInstituteId();
			$rankingPageRow->setNaukriSalaryData($instituteWiseNaukriData[$instituteId]);
		}
		// _p($instituteWiseNaukriData);die;
	}

	public function getRankingPagesUsingCache($params){
            
		if(!isset($this->rankingCache)){
			$this->rankingCache = $this->_ci->load->library(RANKING_PAGE_MODULE.'/cache/RankingPageCache');
		}
		if($this->CI->enableRankingPageCache && isset($params['status']) && count($params['status']) == 1){
			$rankingPages = $this->rankingCache->getRankingPages($params['id'],implode(',', $params['status']));
			if(empty($rankingPages)){
				$rankingPages = $this->rankingModel->getRankingPages($params);
				if(!empty($rankingPages)){
					$this->rankingCache->storeRankingPages($params['id'],implode(',', $params['status']),$rankingPages);
				}
			}
		}
		else{
                    
			$rankingPages = $this->rankingModel->getRankingPages($params);
		}
		return $rankingPages;
	}

	public function invalidateRankingObjectCache($ids = array()){
		if(!isset($this->rankingCache)){
			$this->rankingCache = $this->_ci->load->library(RANKING_PAGE_MODULE.'/cache/RankingPageCache');
		}
		$validStatus = array('live','draft','disable','delete');
		if(empty($ids)){
			$ids = $this->rankingModel->getAllValidRankingPageIds();//_p($ids);die;
		}
		foreach ($ids as $id) {
			foreach ($validStatus as $status) {
				$this->rankingCache->deleteRankingPageObject($id,$status);	
			}
		}
	}

	public function removeCompleteRankingCache(){
		$params['status'] = 'live';
		$rankingPages = $this->rankingModel->getRankingPages($params);
		// $ids = array(2,18,44,45,46,47,48,49,50,51,52,53,54,55,56,93,94,95,96,97,98,99,100,101);
		$rankingURLManager		= RankingPageBuilder::getURLManager();
		if(!isset($this->rankingCache)){
			$this->rankingCache = $this->_ci->load->library(RANKING_PAGE_MODULE.'/cache/RankingPageCache');
		}
		foreach ($rankingPages as $rankingPage) {
			$id = $rankingPage['id'];
			$urlIdentifier = $id.'-2-0-0-0';
			$rankingPageRequest		= $rankingURLManager->getRankingPageRequest($urlIdentifier);
			// $this->rankingCache->deleteCategoryRelatedLinks($rankingPageRequest);
			// $this->rankingCache->deleteAverageReviewRatingsForRankingPage($id);
			// $this->rankingCache->deleteRankingPageUrl($id);
			// $this->rankingCache->deleteRankingPageObject($id);
			$this->rankingCache->deleteRankingPages($id);
			$this->rankingCache->deleteRankingPageFilters($id);
			$this->rankingCache->deleteRankingPageInterlinkingData($id);
			// $this->rankingCache->deleteStreamWiseRankingPageUrl();
		}
	}

	public function invalidateRankingPagesCache($ids=array()){
		if(!isset($this->rankingCache)){
			$this->rankingCache = $this->_ci->load->library(RANKING_PAGE_MODULE.'/cache/RankingPageCache');
		}
		$validStatus = array('live','draft','disable','delete');
		if(empty($ids)){
			$ids = $this->rankingModel->getAllValidRankingPageIds();//_p($ids);die;
		}
		foreach ($ids as $id) {
			foreach ($validStatus as $status) {
				$this->rankingCache->deleteRankingPageObject($id,$status);	
			}
		}	
	}

	private function _filterCourseRankingPageData($rankingPage, $rankingPageRequest, $courseObjects, $tupleType){
		$this->_ci->load->builder("LocationBuilder", "location");
		$LocationBuilder 	= new LocationBuilder();
		$locationRepo			= $LocationBuilder->getLocationRepository();
		$pageData = $rankingPage->getRankingPageData();
		$examId = $rankingPageRequest->getExamId();
		$cityId = $rankingPageRequest->getCityId();
		$stateId = $rankingPageRequest->getStateId();
		$cities = $locationRepo->getCitiesByVirtualCity($cityId);
		
		//handling virtual city case
		$virtualCityMapping = array();
		if(count($cities) > 1) {
			foreach ($cities as  $cityObj) {
				$virtualCityMapping[$cityObj->getId()] = $cityObj->getId();
			}
		}
		
		// Country ID is always 2 so no need to filter by country Id.
		$filteredData = array();
		foreach($pageData as $key=>$data){
			$courseId = $data->getCourseId();
			$courseObject = $courseObjects[$courseId];
			if(empty($courseObject)){
				unset($pageData[$key]);
				continue;
			}
			// Country ID is always 2 so no need to filter by country Id.
			$courseMainLocation 	= $courseObject->getMainLocation();
			if($tupleType == 'course' && !empty($examId)) {
				$exams  = $courseObject->getAllEligibilityExams(false);
                		$examKeys = array_keys($exams);
				if(!in_array($examId, $examKeys)){
					unset($pageData[$key]);
					continue;
				}
			}
			
			if(!empty($cityId)){
				$city = $courseMainLocation->getCityId();
				if($city != $cityId && empty($virtualCityMapping[$city])){
					unset($pageData[$key]);
					continue;
				}
			}elseif(!empty($stateId)){
				$state = $courseMainLocation->getStateId();
				if($state != $stateId){
					unset($pageData[$key]);
					continue;
				}
			}
		}
		$rankingPage->setRankingPageData($pageData);
		return $rankingPage;
	}

	/*private function _filterInstituteRankingPageData($rankingPage, $rankingPageRequest, $instituteObjects){
		$pageData = $rankingPage->getRankingPageData();
		$examId = $rankingPageRequest->getExamId();
		if($cityId = $rankingPageRequest->getCityId()){
		}elseif($stateId = $rankingPageRequest->getStateId()){}
		// Country ID is always 2 so no need to filter by country Id.
		$filteredData = array();
		foreach($pageData as $key=>$data){
			$instituteId = $data->getInstituteId();
			$instituteObject = $instituteObjects[$instituteId];
			if(empty($instituteObjects[$instituteId])){
				unset($pageData[$key]);
				continue;
			}
			$instituteMainLocation 	= $instituteObject->getMainLocation();
			// no exam filter for institute
			if(!empty($cityId)){
				$city = $instituteMainLocation->getCityId();
				if($city != $cityId){
					unset($pageData[$key]);
					continue;
				}
			}elseif(!empty($stateId)){
				$state = $instituteMainLocation->getStateId();
				if($state != $stateId){
					unset($pageData[$key]);
					continue;
				}
			}
		}
		$rankingPage->setRankingPageData($pageData);
		return $rankingPage;
	}*/

	private function _loadDependencies() {

		$this->_ci->load->builder("nationalCourse/CourseBuilder");
	    $courseBuilder = new CourseBuilder();
        $this->_ci->courseRepo = $courseBuilder->getCourseRepository();

        $this->_ci->load->builder("nationalInstitute/InstituteBuilder");
        $instituteBuilder = new InstituteBuilder();
        $this->_ci->instituteRepo = $instituteBuilder->getInstituteRepository();       
        
        $this->_ci->load->library("nationalInstitute/InstituteDetailLib");

                        
        $this->_ci->load->helper("listingCommon/listingcommon");
        $this->_ci->load->helper(array('image'));
    }

	public function prepareRankingTupleData($rankingPage,$rankingPageRequest,&$displayData){
		$this->_loadDependencies();
		$tupleType=$rankingPage->getTupleType();
		// get listing Ids
		$courseIds=array_keys($rankingPage->getRankingPageData());
		$courseSections = array();
    	if($tupleType == 'course'){
    		$courseSections = array('eligibility');
    	}
		
		$this->_ci->benchmark->mark('findMultiple_course_start');
		$courseInfo=$this->_ci->courseRepo->findMultiple($courseIds,$courseSections);
    	$this->_ci->benchmark->mark('findMultiple_course_start');


    	$this->_ci->benchmark->mark('filtering_start');
		$rankingPage = $this->_filterCourseRankingPageData($rankingPage,$rankingPageRequest,$courseInfo, $tupleType);
    	$this->_ci->benchmark->mark('filtering_end');
		$instituteIds=array();

		$courseLocationInfo = array();
		foreach ($rankingPage->getRankingPageData() as $courseId => $rankingPageData) {
			array_push($instituteIds, $rankingPageData->getInstituteId()); 
			$courseObject = $courseInfo[$courseId];
			$courseMainLocation=$courseObject->getMainLocation();
			if(!empty($courseMainLocation)){
				$courseLocationInfo[$courseId]['mainLocationId'] = $courseMainLocation->getLocationId();
				$courseLocationInfo[$courseId]['cityId'] = $courseMainLocation->getCityId();
				$courseLocationInfo[$courseId]['cityName'] = $courseMainLocation->getCityName();
			}

		}
		if($tupleType == 'institute'){
			unset($courseInfo);
		}

    	//load objects
    	if(empty($instituteIds)){
    		show_404();
    	}
    	$this->_ci->benchmark->mark('findMultiple_institute_start');
    	$instituteSections = array();
    	if(!isMobileRequest()){
    		$instituteSections = array('media');
    	}
    	if(empty($instituteIds)) {
    		show_404();
    	}
    	$instituteInfo = $this->_ci->instituteRepo->findMultiple($instituteIds, $instituteSections);
    	$this->_ci->benchmark->mark('findMultiple_institute_end');
		
		//get Fees info & eligibility urls
		$courseFees=array();
		$eligibility  =array();
		// $courseMainLocation     = $courseInfo[$courseId]->getMainLocation();
		if(!isMobileRequest() && $tupleType == 'course'){
			foreach ($courseInfo as $courseId => $courseObj) {
		        $courseFeesObject = $courseObj->getFees($courseLocationId);
		        if($courseFeesObject instanceof Fees){
		            $feesUnit         = $courseFeesObject->getFeesUnitName();
		            $feesValue        = $courseFeesObject->getFeesValue();
		            if($feesValue!=''){
			       		$feesValue = formatAmountToNationalFormat($feesValue, 1);
			       		$courseFees[$courseId] = array('value'=>$feesValue,'unit'=>$feesUnit);
				    }
					$allExamsList = $courseObj->getAllEligibilityExams(false);
					foreach ($allExamsList as $examId=>$examName) {
					    if($examId>0){
	        				$eligibility[] = $examId;
	        			}
					}
		        }
			}
		}
		$eligibility = array_unique($eligibility);
		$eligibilityUrls = array();
		if(count($eligibility)>0){
			$this->_ci->benchmark->mark('examUrls_start');
        	$this->examPageLib    = $this->_ci->load->library('examPages/ExamPageLib');
        	$eligibilityUrls = $this->examPageLib->getExamMainUrlsById($eligibility);
			$this->_ci->benchmark->mark('examUrls_end');
		}
		
    	//get media image,count,mappings for search and Exam Info
        $instituteDataMapping = array();
    	$instituteMediaInfo=array();
    	$examNames=array();
    	foreach ($rankingPage->getRankingPageData() as $courseId => $rankingPageData) {
    		$instituteId=$rankingPageData->getInstituteId();
			$instituteObject = $instituteInfo[$instituteId];
			if(!empty($instituteObject)){
	       		if($tupleType=='course'){
	       				$courseObject = $courseInfo[$courseId];
					$examNames[$courseId] = getExamHtml($courseObject,$eligibilityUrls);
					$reviewCounts[$instituteId] = $courseObject->getReviewCount();
				}
    		if(!isMobileRequest()){
    			$instituteMediaInfo[$instituteId]=array('instituteThumbURL'=>'','totalMediaCount'=>0);
					$courseMainLocationId= $courseLocationInfo[$courseId]['mainLocationId'];
			
				
				$instituteThumbURL = $instituteObject->getHeaderImage($courseMainLocationId);
				if(empty($instituteThumbURL)){
					$instituteMainLocation=$instituteObject->getMainLocation();
					$instituteThumbURL = $instituteObject->getHeaderImage($instituteMainLocation->getLocationId());
				}
				
				if(!empty($instituteThumbURL)){
	                $instituteMediaInfo[$instituteId]['instituteThumbURL'] =getImageVariant($instituteThumbURL->getUrl(),2);
	            	$instituteMediaInfo[$instituteId]['totalMediaCount'] = getMediaCountForInstitute($instituteObject,$courseMainLocationId);
	            }
    		}
	            $instituteDataMapping[$instituteId] = array('id' => $instituteId,'name' => $instituteObject->getName(),'synonyms' => $instituteObject->getSynonym());
			}
		}


		unset($instituteObject);
		unset($courseObj);

		//get counts
        $this->_ci->load->library('ContentRecommendation/AnARecommendationLib');
		$questionCounts = $this->_ci->anarecommendationlib->getInstituteAnaCounts($instituteIds, 'question');
		if($tupleType=='institute'){
        	$this->_ci->load->library('ContentRecommendation/ReviewRecommendationLib');
        	$reviewCounts = $this->_ci->reviewrecommendationlib->getInstituteReviewCounts($instituteIds);
		}

		
		$this->_ci->benchmark->mark('allCourse_start');
        $allCourses = $this->_ci->institutedetaillib->getAllCoursesForMultipleInstitutes($instituteIds,'all',true,false);
        $courseCounts = array();
        foreach ($instituteIds as $instituteId) {
        	if(!empty($allCourses[$instituteId])){
        		$courseCounts[$instituteId] = count($allCourses[$instituteId]['courseIds']); 
        	}
        }

		if($tupleType=='institute') {

    		$this->_ci->benchmark->mark('customCourse_start');
			$rankingCriteriaCourses = $this->getRankingCoursesBasedOnCriteria($allCourses, $rankingPage);
    		$this->_ci->benchmark->mark('customCourse_end');
		}

		//check to show all course page * base_course page only for MBA
        if($rankingPage->getBaseCourseId() == 101) {
        	$allCoursesPageLib = $this->_ci->load->library('nationalCategoryList/AllCoursesPageLib');
        	$displayData['allCoursesPageLib'] = $allCoursesPageLib;
        	$this->_ci->load->builder('listingBase/ListingBaseBuilder');
			$builder = new ListingBaseBuilder;
			$baseCourseRepo = $builder->getBaseCourseRepository();
			$baseCourseObj = $baseCourseRepo->find($rankingPage->getBaseCourseId());
        	$displayData['allCoursePageUrlData']['base_course'] = array('id' => 101, 'name' => $baseCourseObj->getName());
        }

        unset($instituteIds);
        unset($courseIds);
		
    	$this->_ci->benchmark->mark('allCourse_end');

        $displayData['mobileRequest']        = isMobileRequest();
		$displayData['brochuresMailed'] = getBrochuresFromCookie();
		$displayData['rankingCriteriaCourses'] = $rankingCriteriaCourses;
		$displayData["tupleType"]            = $tupleType;
		$displayData["questionCounts"]       = $questionCounts;
		$displayData["reviewCounts"]         = $reviewCounts;
		$displayData["courseCounts"]         = $courseCounts;
		$displayData['instituteInfo']        = $instituteInfo;
		$displayData['courseInfo']           = $courseInfo;
		$displayData['instituteMediaInfo']   = $instituteMediaInfo;
		$displayData['courseFees']           = $courseFees;
		$displayData['instituteDataMapping'] = $instituteDataMapping;
		$displayData["examNames"]     = $examNames;
		$displayData["courseLocationInfo"] = $courseLocationInfo;
        return;
	}

	public function getArticlesByHeirarchies($heirarchies){
		$articles = $this->rankingModel->getArticlesByHeirarchies($heirarchies);
		return $articles;
	}

	public function getArticlesByBaseCourseId($baseCourseId){
		return $this->rankingModel->getArticlesByBaseCourseId($baseCourseId);
	}

	public function getHomePageIdByRankingPageRequest($rankingPageRequest){
		$stream = $rankingPageRequest->getStreamId();
		$substream = $rankingPageRequest->getSubstreamId();
		$specialization = $rankingPageRequest->getSpecializationId();
		$baseCourse = $rankingPageRequest->getBaseCourseId();
		if(!empty($stream)){
			$params['stream'] = $stream;
			if(!empty($substream)){
				$params['substream'] = $substream;
				if(!empty($specialization)){
					$params['specialization'] = $specialization;
				}
			}
		}else{
			$params['popularCourse'] = $baseCourse;
			if(!empty($specialization)){
				$params['specialization'] = $specialization;
			}
		}
		
		return $this->rankingModel->getCourseHomePageIdCorrespondingToParams($params);
	}
	
	function getRankingCoursesBasedOnCriteria($instituteWiseCourses, $rankingPageObject) {
		if(empty($instituteWiseCourses)) {
			return;
		}

		//Get ranking page criteria
		$streamId = $rankingPageObject->getStreamId();
		$baseCourseId = $rankingPageObject->getBaseCourseId();

		if(!empty($baseCourseId)) {
			$baseCourseObj = $this->baseCourseRepo->find($baseCourseId);
	        $popularCourse = $baseCourseObj->getIsPopular();
	    }

	    if($popularCourse) {
	    	$criteria['base_course_id'][] = $baseCourseId;
	    } else {
	    	if(!empty($streamId)) {
				$criteria['stream_id'][] = $streamId;
	    	}
	    	if(!empty($baseCourseId)) {
				$criteria['base_course_id'][] = $baseCourseId;
	    	}
	    }

	    //Get all course ids based on ranking page criteria
	    $courseIds = array();
        foreach ($instituteWiseCourses as $tupleInstituteId => $courses) {
            $courseIds = array_merge($courseIds, $courses['courseIds']);
        }
        
        $courseIds = array_unique($courseIds);
	    $filteredCourseList = array_flip($this->courseDetailLib->filterCoursesBasedOnHeirarchy($courseIds, $criteria));
	    // $filteredCourseList = array_flip($filteredCourseList);

	    //Filter courses, institute wise
	    $tempInstituteWiseCourses = $instituteWiseCourses;
	    foreach ($instituteWiseCourses as $tupleInstituteId => $insttCourses) {
	    	foreach ($insttCourses['instituteWiseCourses'] as $heirarchyInstituteId => $courses) {
	    		foreach ($courses as $key => $courseId) {
	    			if(!array_key_exists($courseId, $filteredCourseList)) {
	    				unset($instituteWiseCourses[$tupleInstituteId]['instituteWiseCourses'][$heirarchyInstituteId][$key]);
	    			}
	    		}

	    		if(empty($instituteWiseCourses[$tupleInstituteId]['instituteWiseCourses'][$heirarchyInstituteId])) {
		    		unset($instituteWiseCourses[$tupleInstituteId]['instituteWiseCourses'][$heirarchyInstituteId]);
		    	}
	    	}

	    	foreach ($insttCourses['courseIds'] as $key => $courseId) {
    			if(!array_key_exists($courseId, $filteredCourseList)) {
    				unset($instituteWiseCourses[$tupleInstituteId]['courseIds'][$key]);
    			}
	    	}

	    	// Fallback for institutes if 0 courses above
	    	if(empty($instituteWiseCourses[$tupleInstituteId]['courseIds'])) {
	    		$instituteWiseCourses[$tupleInstituteId] = $tempInstituteWiseCourses[$tupleInstituteId];
	    	}
	    }

	    return $instituteWiseCourses;
	    
	    /*
		$courseIds = array();
        foreach ($allCourseList as $courseArray) {
        	if(!empty($courseArray)){
        		$courseIds=array_merge($courseIds,$courseArray);
        	}
        }
        
        $courseObjectArray = $this->_ci->courseRepo->findMultiple($courseIds);

		$data = array();
		foreach ($allCourseList as $instituteKey => $courseArray) {
			$instituteObject = $instituteInfo[$instituteKey];
			foreach ($courseArray as $courseId) {
				$courseObject = $courseObjectArray[$courseId];
				
				$courseName = $courseObject->getName();
	            $instituteName = $courseObject->getOfferedByShortName();
	            $instituteName = $instituteName ? $instituteName : $instituteObject->getShortName();
	            $instituteName = $instituteName ? $instituteName : $instituteObject->getName();
	            if($listingType == 'university') {
	                $courseName .= ", ".$instituteName;
	            }
	            
		        $data[$instituteKey][] = array('course_id' => $courseId,'course_name' => htmlentities($courseName));
	        }

	    	//sort course alphabetically
			$unsortedList=$data[$instituteKey];
	        usort($unsortedList,"course_sort");
    	    $data[$instituteKey]=$unsortedList;
        }
	    return $data;
		*/
	}
	
	function prepareArticlesInterlinkingData($rankingPageRequest){
		$data = $this->rankingCache->getRankingPageInterlinkingData($rankingPageRequest->getPageId(), $rankingPageRequest->getPageKey(), 'article');
		if(!empty($data)) {
			return $data;
		}
		$homePageId = $this->getHomePageIdByRankingPageRequest($rankingPageRequest);
		$result = array();
		if($homePageId != false){
			$articlesData = modules::run('coursepages/CoursePage/getArticleWidgetForCourseHomePage',$homePageId);
			foreach($articlesData as $data){
				$result[] = array('artcileTitle'=>$data['blogTitle'],'url'=>$data['url']);
			}
		}
		if(empty($result)){
			$stream = $rankingPageRequest->getStreamId();
			if($stream > 0){
				$substream = $rankingPageRequest->getSubstreamId();
				$this->_ci->load->builder('listingBase/ListingBaseBuilder');
				$builder = new ListingBaseBuilder;
				$repo = $builder->getHierarchyRepository();
				$heirarchies = array();

				$heirarchies = array_merge($heirarchies,$repo->getHierarchyIdByBaseEntities($stream,'none','none','array'));
				if($substream > 0) {
					$heirarchies = array_merge($heirarchies,$repo->getHierarchyIdByBaseEntities($stream,$substream,'none','array'));
				}
				$heirarchies = array_filter($heirarchies);
				// _p($heirarchies); die('11');
				if(!empty($heirarchies)){
					$result = $this->getArticlesByHeirarchies($heirarchies);
				}else{
					$result = array();
				}
			}else{
				$baseCourseId = $rankingPageRequest->getBaseCourseId();
				$result = $this->getArticlesByBaseCourseId($baseCourseId);
			}
		}
		$this->rankingCache->storeRankingPageInterlinkingData($rankingPageRequest->getPageId(), $rankingPageRequest->getPageKey(), 'article', $result);
		return $result;
	}

	public function prepareExamWidgetData($rankingPageRequest) {
		$data = $this->rankingCache->getRankingPageInterlinkingData($rankingPageRequest->getPageId(), $rankingPageRequest->getPageKey(), 'exam');
		if(!empty($data)) {
			return $data;
		}
		$stream = $rankingPageRequest->getStreamId();
		if($stream > 0){
			$this->_ci->load->builder('listingBase/ListingBaseBuilder');
			$builder     = new ListingBaseBuilder;
			$repo        = $builder->getHierarchyRepository();
			$heirarchies = array();
			$substream 	 = $rankingPageRequest->getSubstreamId();
			$specialization = $rankingPageRequest->getSpecializationId();
			
			if($specialization > 0){
				$heirarchies = $repo->getHierarchyIdByBaseEntities($stream,$substream,$specialization,'array');	
			}
			else if($substream > 0) {
				$heirarchies = $repo->getHierarchyIdByBaseEntities($stream,$substream,'none','array');
			}
			else {
				$heirarchies = $repo->getHierarchyIdByBaseEntities($stream,'none','none','array');
			}
			$heirarchies = array_filter($heirarchies);
			// _p($heirarchies); die;
			if(!empty($heirarchies)){
				$examIds = $this->rankingModel->getExamsByHeirarchies($heirarchies);
			}
		}else{
			$baseCourseId = $rankingPageRequest->getBaseCourseId();
			$examIds = $this->rankingModel->getExamsByBaseCourse($baseCourseId);
		}
		if(empty($examIds)){
			return array();
		}
		$data = $this->rankingModel->getExamWidgetDataByIds($examIds);
		$this->rankingCache->storeRankingPageInterlinkingData($rankingPageRequest->getPageId(), $rankingPageRequest->getPageKey(), 'exam', $data);
		return $data;
	}

	function getInterlinkingWidgetHeading($filters, $rankingPageRequest, $rankingPage) {
		$heading = array();
		$locationName = $filters['selectedLocationFilter']->getName();
        if($rankingPageRequest->getCityId() == 0 && $rankingPageRequest->getStateId() == 0) {
            $locationName = $rankingPageRequest->getCountryName();
        }
        $heading['exam'] = "Top ".$rankingPage->getName().' Colleges in '.$locationName.' Accepting';
        $heading['location'] = "Top Ranked ".$rankingPage->getName().' Colleges in ';
        return $heading;
	}

	function getBecaonTrackData($rankingPageRequest) {
		return array(
			'pageIdentifier' => 'rankingPage',
			'pageEntityId'   => $rankingPageRequest->getPageId(),
			'extraData'      => array(
								'url'               => get_full_url(),
								'hierarchy' 		=> array(
														'streamId'          => $rankingPageRequest->getStreamId(),
														'substreamId'       => $rankingPageRequest->getSubstreamId(),
														"specializationId"  => $rankingPageRequest->getSpecializationId(),
														"baseCourseId"      => $rankingPageRequest->getBaseCourseId(),
													   ),
								"cityId"            => $rankingPageRequest->getCityId(),
								"stateId"           => $rankingPageRequest->getStateId(),
								"countryId"         => $rankingPageRequest->getCountryId(),
								"educationType"     => $rankingPageRequest->getEducationType(),
								"deliveryMethod"    => $rankingPageRequest->getDeliveryMethod(),
								"credential"        => $rankingPageRequest->getCredential(),
					            "pageType"          => "Ranking",
								)
		);
	}

	public function getRankingPageMetaData($rankingPageId = NULL, $cityId=0,$stateId = 0){
		$rankingModel = new ranking_model();
		$cityIds = array();
		$stateIds = array();
		$cityIds[0] = 0;
		$stateIds[0] =0;
		if($cityId!=0){
			$cityIds[1] = $cityId;
		}
		if($stateId!=0){
			$stateIds[1] =$stateId;
		}
		$rankingMetaData =  $rankingModel->getRankingPageMetaData($rankingPageId,$cityIds,$stateIds);
		
		if(count($rankingMetaData)==1){
			return $rankingMetaData[0];
		} 
			
		$retunData = array();
		$index = 0;
		foreach($rankingMetaData as $data){
			if($data['city_id'] == $cityId && $data['state_id'] == $stateId){
				$retunData['h1'] = !empty($data['h1']) ? $data['h1'] : $rankingMetaData[!$index]['h1'];
				$retunData['ranking_page_title'] = !empty($data['ranking_page_title']) ? $data['ranking_page_title'] : $rankingMetaData[!$index]['ranking_page_title'];
				$retunData['ranking_page_description'] = !empty($data['ranking_page_description']) ? $data['ranking_page_description'] : $rankingMetaData[!$index]['ranking_page_description'];
				$retunData['breadcrumb'] = !empty($data['breadcrumb']) ? $data['breadcrumb'] : $rankingMetaData[!$index]['breadcrumb'];
				$retunData['ranking_page_title_exam'] = !empty($data['ranking_page_title_exam']) ? $data['ranking_page_title_exam'] : $rankingMetaData[!$index]['ranking_page_title_exam'];
				$retunData['ranking_page_description_exam'] = !empty($data['ranking_page_description_exam']) ? $data['ranking_page_description_exam'] : $rankingMetaData[!$index]['ranking_page_description_exam'];
				$retunData['disclaimer'] = !empty($data['disclaimer']) ? $data['disclaimer'] : $rankingMetaData[!$index]['disclaimer'];

			}
		    $index++;
		}
		return $retunData;
	}
	public function checkIfRankingPageExists($rankPageId){
		$data = array('status'=>'live', 'id'=>$rankPageId);
		return $this->rankingModel->_checkIfRankingPageExists($data);
	}

	public function indexCourseForRanking($courseIdArr){
		$this->rankingModel->indexCourseForRanking($courseIdArr);
	}

	public function updateCoursesForSource($id, $type){
		$sourceIds = array();
		if($type == 'publisher'){
			//get all sources for publisher id
			$sourceIds = $this->rankingModel->getAllSourcesForPublisher($id);
		}else{
			if(!empty($id)){
				$sourceIds = array($id);
			}
		}
		if(!empty($sourceIds)){
			//get courses for source ids
			$courseIds = $this->rankingModel->getAllCoursesForSources($sourceIds);
			$this->indexCourseForRanking($courseIds);
		}
	}
}
