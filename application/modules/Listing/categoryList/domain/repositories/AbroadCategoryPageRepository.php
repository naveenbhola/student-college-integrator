<?php

class AbroadCategoryPageRepository extends EntityRepository
{
    private $request;
    private $instituteRepository;
    private $LDBCourseRepository;
    private $locationRepository;
    private $categoryRepository;
    private $universityRepository;
    private $courseRepository;
    private $bannerRepository;
    protected  $cache;
    private $corruptedUniversityIds;
    
    function __construct($dao,$cache = NULL,
                        AbroadCategoryPageRequest $request,
    		            UniversityRepository $universityRepository,
                        AbroadInstituteRepository $instituteRepository,
    					AbroadCourseRepository $courseRepository,
                        LDBCourseRepository $LDBCourseRepository,
                        LocationRepository $locationRepository,
                        CategoryRepository $categoryRepository,
                        BannerRepository $bannerRepository,
						ListingErrorReportingLib $listingErrorReportingLib)
    {
        parent::__construct($dao,$cache);
        $this->cache = $cache;
        $this->request = $request;
        $this->universityRepository = $universityRepository;
        $this->instituteRepository = $instituteRepository;
        $this->courseRepository =  $courseRepository;
        $this->LDBCourseRepository = $LDBCourseRepository;
        $this->locationRepository = $locationRepository;	
        $this->categoryRepository = $categoryRepository;
        $this->bannerRepository = $bannerRepository;
        $this->listingErrorReportingLib = $listingErrorReportingLib;
        
        
    }
    
 
 
 /**
  * Purpose : Universities for category page 
  * @return Universities with domain objects
  */
    
    
    public function getUniversities()
    {
    	$clonedRequest = clone $this->request;
    	if($clonedRequest->isLDBCourseSubCategoryPage() || $clonedRequest->isCategorySubCategoryCourseLevelPage()) /**If page is ldb and Subcat type then inventory will be on category id ***/
    	{
			if(is_array($subCats=$clonedRequest->getSubCategoryId()))
			{
				$subcatObj = $this->categoryRepository->findMultiple($subCats);
				$subcatObj = reset($subcatObj);
			}
    		else{
				$subcatObj = $this->categoryRepository->find($subCats);
			}
			$clonedRequest->setData(array('categoryId' => $subcatObj->getParentId()));
    	}
    	$stickyUniversityIds = $this->dao->getStickyListingForAbroad($clonedRequest);
    	$stickyUniversityIds = $stickyUniversityIds ? explode(',',$stickyUniversityIds) : 0;
    	$mainUniversityIds = $this->dao->getMainListingForAbroad($clonedRequest);
      	$mainUniversityIds = $mainUniversityIds ? explode(',',$mainUniversityIds) : 0;
      	$data = $this->dao->getAbroadCourseForRequest($this->request);
	//_p($data);die;
    	$UniversitiesIdWithDepmtIdAndCourseIds = $data['mainResult'];
	//$snapshotResultIds = $data['snapshotResult'];
	$finalUniversityCount = 0;
	//This is used for sorter!
      	$resultData['countCourse'] =  count($UniversitiesIdWithDepmtIdAndCourseIds);

      	$UniversityIdsWithinstituteIdArrayWithCourseIdMapping = array();
    	foreach($UniversitiesIdWithDepmtIdAndCourseIds as $instituteIdWithCourseId)
    	{  
            if(in_array($instituteIdWithCourseId['university_id'],$stickyUniversityIds)) {
                $stickyUniversityIdsWithinstituteIdArrayWithCourseIdMapping[$instituteIdWithCourseId['university_id']][] = array($instituteIdWithCourseId['course_id'],$instituteIdWithCourseId['sub_category_id'],$instituteIdWithCourseId['pack_type']);
            }else if(in_array($instituteIdWithCourseId['university_id'],$mainUniversityIds)){
                $mainUniversityIdsWithinstituteIdArrayWithCourseIdMapping[$instituteIdWithCourseId['university_id']][] = array($instituteIdWithCourseId['course_id'],$instituteIdWithCourseId['sub_category_id'],$instituteIdWithCourseId['pack_type']);
    	    }else if(in_array($instituteIdWithCourseId['pack_type'],array(GOLD_SL_LISTINGS_BASE_PRODUCT_ID,SILVER_LISTINGS_BASE_PRODUCT_ID,GOLD_ML_LISTINGS_BASE_PRODUCT_ID))) {
    	    	$paidUniversityIdsWithinstituteIdArrayWithCourseIdMapping[$instituteIdWithCourseId['university_id']][] = array($instituteIdWithCourseId['course_id'],$instituteIdWithCourseId['sub_category_id'],$instituteIdWithCourseId['pack_type']);
    	    }else{
                $freeUniversityIdsWithinstituteIdArrayWithCourseIdMapping[$instituteIdWithCourseId['university_id']][] = array($instituteIdWithCourseId['course_id'],$instituteIdWithCourseId['sub_category_id'],$instituteIdWithCourseId['pack_type']);
    	    }
    	}
    	$universities = array();
    	if($stickyUniversityIdsWithinstituteIdArrayWithCourseIdMapping)
    	{
            $universities['sticky'] = $stickyUniversityIdsWithinstituteIdArrayWithCourseIdMapping;
    	}
    	if($mainUniversityIdsWithinstituteIdArrayWithCourseIdMapping)
    	{	
    	   $universities['main'] = $mainUniversityIdsWithinstituteIdArrayWithCourseIdMapping;
    	}
    	if($paidUniversityIdsWithinstituteIdArrayWithCourseIdMapping)
    	{
            $universities['paid'] = $paidUniversityIdsWithinstituteIdArrayWithCourseIdMapping;
    	}	
    	if($freeUniversityIdsWithinstituteIdArrayWithCourseIdMapping) 
    	{
    	   $universities['free'] = $freeUniversityIdsWithinstituteIdArrayWithCourseIdMapping;
    	}
    	$resultData['universities'] = $this->loadUniversitiesForCategoryPage($universities);
	//$resultData['snapshotUniversities'] = $this->_loadSnapshotUniversitiesForCategoryPage($snapshotResultIds);
    	if(!empty($this->corruptedUniversityIds))
    	{
            foreach($universities as $universityType => $universityIdArray) {
                foreach($this->corruptedUniversityIds as $universityId) {
                    unset($universities[$universityType][$universityId]);
                }
            }
    	}
    	$resultData['universityIdsWithType'] = $universities;
        return $resultData;
    }
     
    /***
     * 
     * Get domain objects corresponding to universities with departments and course ids
     * 
     * 
     */
    
    
    public function loadUniversitiesForCategoryPage($UniversitiesIdsArrayWithDepmtIdAndCourseIds){
   	
    	if(is_array($UniversitiesIdsArrayWithDepmtIdAndCourseIds) && count($UniversitiesIdsArrayWithDepmtIdAndCourseIds) > 0) {
    		 
    		return $this->loadAndAssociateUniversityDeptCourseOjects($UniversitiesIdsArrayWithDepmtIdAndCourseIds);
    	}
    	
    }
    
    
    /****
     *
    *  Load Univesrity , Deparment , Course Object and associate with each other.
    */
    
    public function loadAndAssociateUniversityDeptCourseOjects($UniversitiesTypes = array())
    {
    	$universitiesId = array();
    	$departmentIds = array();
    	$courseIdarray = array();
    	$courseSubCatIds = array();
    	foreach($UniversitiesTypes as $UniversityType => $UniversitiesWithCourseIds)
    	{
        	foreach($UniversitiesWithCourseIds as $UniversityId=>$CourseProperties){
				$universitiesId [] = $UniversityId;
				foreach($CourseProperties as $CourseProperty) {
					$courseIdarray [] = $CourseProperty[0];
					$courseSubCatIds [] = $CourseProperty[1];
				}
    	    }
    	}
	// remove invalid course ids if any
	$courseIdarray = array_filter($courseIdarray,function($e){if($e > 0) return true; else return false;});
	if(count($courseIdarray) > 0 && count($universitiesId) > 0 ){
    		$courses = $this->courseRepository->findMultiple($courseIdarray,true);
    		$universities = $this->universityRepository->findMultiple($universitiesId,true);
    		$SubCategoryObj = $this->categoryRepository->findMultiple($courseSubCatIds);
    		foreach($UniversitiesTypes as $UniversityType => $UniversitiesWithCourseProperties)
    		{  		    
				foreach($UniversitiesWithCourseProperties as $UniversityId=>$CourseProperties) {
					$CourseProperties = (array) $CourseProperties;
					$currentUniversity = $universities[$UniversityId];
					foreach($CourseProperties as $CourseProperty) {
						//$CourseProperties is an array containing Course Id on first index and Subcategory Id related to that Course Id on second Index.
						// Validate if University,Department and Course are proper (i.e. able to get from Find method of repository, also check Object is corrupted or not), In case Object is not proper set a alert mail. 
						$isObjectValidationSuccessful  = $this->_validateAndFindCorruptedObjectOfCategoryPage($universities[$UniversityId],$UniversityId,$courses[$CourseProperty[0]],$CourseProperty[0],$SubCategoryObj[$CourseProperty[1]],$CourseProperty[1]);
						// if all objects of current iteration are proper then $isObjectValidationSuccessful would be true.
						//if any error comes in validation then break the current iteration.
						if(!$isObjectValidationSuccessful) {
							unset($universities[$UniversityId]);
							break;
						}
							
						$courses[$CourseProperty[0]]->setCourseSubCategoryObj($SubCategoryObj[$CourseProperty[1]]);
						$currentUniversity->addCourse($courses[$CourseProperty[0]]);
					}
					//if any error comes in validation then continue to next iteration.
					if(!$isObjectValidationSuccessful) {
						continue;
					}
					if($UniversityType == "sticky") {
						$universities[$UniversityId]->setSticky();
					} else if($UniversityType == "main") {
						$universities[$UniversityId]->setMain();
					}
					//error_log("::CPSA:: University Loaded : ID[".$UniversityId."] ; Memory : ".memory_get_usage()."[".(memory_get_usage()/1024)."KB]");
				}
    		}
			//_p(reset($universities));die;
    		return $universities;
    	}
    
    }
    
     
    /*
     * Purpose : 1. Validate all type of Listing Object, a. check if object is set or not. b. Check if object is of valid type 
     *		   : 2. In case any corrupted object found then send error alert mail.
     *         : 3. Push all those university ids which have some corrupted data to  corruptedUniversityIds array,This array is used in code flow for bypassing corrupted universities.
     *		   : 4. Return true if all object are valid. 
     */
    
    private function _validateAndFindCorruptedObjectOfCategoryPage($universityObj,$universityId,$courseObj,$courseId,$subCategoryObj,$subCategoryId)
    {	$errorFoundFlag = false;
    	$scope = "Abroad Category Page";
    	$fileName = "AbroadCategoryPageRepository.php";
    	$lineNo = "about 181";
	$currentPageURL = getCurrentPageURLWithoutQueryParams();
    	if(!isset($universityObj) || $universityObj->getId() == '') {
    		$this->listingErrorReportingLib->registerToSendErrorAlert($universityId, "University", $currentPageURL, "Object not found",$scope,$fileName,$lineNo);
            $errorFoundFlag = true;
    	} else if(!($universityObj instanceof University )) {
    		$this->listingErrorReportingLib->registerToSendErrorAlert($universityId, "University", $currentPageURL, "Object Corrupted, Found :".get_class($universityObj),$scope,$fileName,$lineNo);
    		$errorFoundFlag = true;
    	}
    	
    	if(!isset($courseObj) || $courseObj->getId() == '') {
    		$this->listingErrorReportingLib->registerToSendErrorAlert($courseId, "Department", $currentPageURL, "Object not found",$scope,$fileName,$lineNo);
    		$errorFoundFlag = true;
    	} else if(!($courseObj instanceof AbroadCourse )) {
    		$this->listingErrorReportingLib->registerToSendErrorAlert($courseId, "Abroad Course", $currentPageURL, "Object Corrupted, Found :".get_class($courseObj),$scope,$fileName,$lineNo);
    		$errorFoundFlag = true;
    	}
    	
    	if(!isset($subCategoryObj) || $subCategoryObj->getId() == '') {
    		$this->listingErrorReportingLib->registerToSendErrorAlert($subCategoryId, "Category", $currentPageURL, "Object not found",$scope,$fileName,$lineNo);
    		$errorFoundFlag = true;
    	} else if(!($subCategoryObj instanceof Category )) {
    		$this->listingErrorReportingLib->registerToSendErrorAlert($subCategoryId, "Category", $currentPageURL, "Object Corrupted, Found :".get_class($subCategoryId),$scope,$fileName,$lineNo);
    		$errorFoundFlag = true;
    	}
        
    	if($errorFoundFlag)
    	{
    		$this->corruptedUniversityIds [] = $universityId;
    		return false;
    	}
       return true;	
    }
    
    public function getCategory()
    {
        $categoryId = $this->request->getCategoryId();
        return $this->categoryRepository->find($categoryId);
    }
    
    public function getSubCategory()
    {
        $subCategoryId = $this->request->getSubCategoryId();
        return $this->categoryRepository->find($subCategoryId);
    }
    
    public function getLDBCourse()
    {
        $LDBCourseId = $this->request->getLDBCourseId();
        return $this->LDBCourseRepository->find($LDBCourseId);
    }
    
    public function getCity()
    {
        $cityId = $this->request->getCityId();
        return $this->locationRepository->findCity($cityId);
    }
    
    public function getCountry()
    {
        $countryId = $this->request->getCountryId();
        return $this->locationRepository->findCountry($countryId);
    }
    
    public function getState()
    {
        $stateId = $this->request->getStateId();
        return $this->locationRepository->findState($stateId);
    }
    
  
    
    public function setRequest(AbroadCategoryPageRequest $request)
    {
        $this->request = $request;
    }
    
    public function getCurrencyDetials(){

     if($this->cache && $currencyConversionData = $this->cache->getCurrencyDetails())
     {
     	return $currencyConversionData;
     }
     $currencyData = $this->dao->getCurrencyDetailsForCategoryPage();
     $currencyConversionData[1][1] = 1;
     foreach($currencyData as $row){
     	$currencyConversionData[1][$row['source_currency_id']] = $row['conversion_factor'];
     }
     $this->cache->storeCurrencyDetails($currencyConversionData);
     return $currencyConversionData;
    }
	
	/*
     * Convert array of universities to objects of universities recursively, using already loaded objects
     */ 
    public function loadUniversities($universities_arr_withoutDept, $universities_obj)
    {
		//create a map of object and corresponding id
		$coursesWithObj 		= array();
		$universitiesWithObj 	= array();
		
		foreach($universities_obj as $universityId => $university) {
				$courses = $university->getCourses();
				foreach($courses as $course){
					$coursesWithObj[$course->getId()] = $course;
				}
			$universitiesWithObj[$university->getId()] = $university;
		}
		
		//using the map, create object for courses-depts-universities
		$universityObjFinal = array();
		foreach($universities_arr_withoutDept as $universityId => $courses) {
			$courseKeys = array();
			$courseObjList = array();
			foreach($courses as $courseId => $value) {
				$courseObjList[] = $coursesWithObj[$courseId];
				$courseKeys[$courseId] = $courseId;
			}
			$universitiesWithObj[$universityId]->setCourses($courseObjList);
			$universitiesWithObj[$universityId]->setSortOrderForSimilarCourses($courseKeys);
			$universityObjFinal[] = $universitiesWithObj[$universityId];
		}
		
		return $universityObjFinal;
    }

     
    public function getBanners() {
    	
    	$clonedRequest = clone $this->request;
    	if($clonedRequest->isLDBCourseSubCategoryPage() || $clonedRequest->isCategorySubCategoryCourseLevelPage()) /**If page is ldb and Subcat type then inventory will be on category id ***/
    	{
			if(is_array($subCats=$clonedRequest->getSubCategoryId())) 
			{ 
					$subcatObj = $this->categoryRepository->findMultiple($subCats); 
					$subcatObj = reset($subcatObj); 
			} 
			else{ 
					$subcatObj = $this->categoryRepository->find($subCats); 
			}
    		$clonedRequest->setData(array('categoryId' => $subcatObj->getParentId()));
    	}
    	return $banners = $this->bannerRepository->getAbroadCategoryPageBanners($clonedRequest);
    }
    
    public function getUniversityCountForCategoryPage() {
    	$UniversitiesIdWithDepmtIdAndCourseIds = $this->dao->getAbroadCourseForRequest($this->request);
    	$universityIdsArray = array();
    	if(isset($UniversitiesIdWithDepmtIdAndCourseIds['mainResult'])){
    	    	foreach($UniversitiesIdWithDepmtIdAndCourseIds['mainResult'] as $instituteIdWithCourseId)
        		{
    	    		if(!in_array($instituteIdWithCourseId['university_id'],$universityIdsArray)) {
        				$universityIdsArray[] = $instituteIdWithCourseId['university_id'];
        			}
    	    	}
    	}
        if(isset($UniversitiesIdWithDepmtIdAndCourseIds['snapshotResult'])){
                foreach($UniversitiesIdWithDepmtIdAndCourseIds['snapshotResult'] as $instituteIdWithCourseId)
                {
                        if(!in_array($instituteIdWithCourseId['university_id'],$universityIdsArray)) {
                                $universityIdsArray[] = $instituteIdWithCourseId['university_id'];
                        }
                }
        }
    	return count($universityIdsArray);     	   
	   
    }
	
	private function _loadSnapshotUniversitiesForCategoryPage($resultIds){
		/*
		$CI = & get_instance();
		$CI->benchmark->mark("snapshot_course_load_start");
		$cleanIds = array();
		foreach($universityIds as $universityId){
			if(is_numeric($universityId)){
				$cleanIds[] = $universityId;
			}
		}
		//$cleanIds = array_filter($universityIds,"is_numeric");
		if(!empty($cleanIds)){
			$universities = $this->universityRepository->findMultiple($cleanIds);
		}
		else{
			return ;
		}
		$CI->benchmark->mark("snapshot_course_load_end");
		error_log("::CPSCPA:: Snapshot Courses' Universities Load Time : ".$CI->benchmark->elapsed_time('snapshot_course_load_start','snapshot_course_load_end'));
		//Now we need to only keep courses within this university that belong to our current page.
		//Request is $this->request.
		$CI = & get_instance();
		$CI->load->builder('CategoryBuilder','categorylist');
		$categoryBuilder = new CategoryBuilder;
		$CategoryRepository = $categoryBuilder->getCategoryRepository();
		if($this->request->isCategoryCourseLevelPage()){
			$categoryId = $this->request->getCategoryId();
			$subCats = $CategoryRepository->getSubCategories($categoryId,'newAbroad');
			foreach($subCats as $key=>$subCat){
				unset($subCats[$key]);
				$subCats[] = $subCat->getId();
			}
		}
		//Universities are loaded with ALL snapshot courses. We will remove any irrelevant courses to the page here.
		$finalUnis = array();
		$CI->load->builder('ListingBuilder','listing');
		$listingsBuilder = new ListingBuilder;
		$snapshotCourseRepository = $listingsBuilder->getSnapshotCourseRepository();
		$CI->benchmark->mark("snapshot_course_category_filtering_start");
		foreach($universities as $key=>$university){
			if($this->request->isCategoryCourseLevelPage()){
				$courseLevel = $this->request->getCourseLevel();
				$university->filterSnapshotCoursesForCategoryPage($subCats,$courseLevel,$snapshotCourseRepository);
				$finalUnis[] = $university;
			}
			else if($this->request->isCategorySubCategoryCourseLevelPage()){
				$subcategoryId = $this->request->getSubCategoryId();
				$courseLevel = $this->request->getCourseLevel();
				$university->filterSnapshotCoursesForSubCategoryPage($subcategoryId,$courseLevel,$snapshotCourseRepository);
				$finalUnis[] = $university;
			}
		}
		$CI->benchmark->mark("snapshot_course_category_filtering_end");
		error_log("::CPSCPA:: Preliminary filtering of snapshot courses for category : ".$CI->benchmark->elapsed_time('snapshot_course_category_filtering_start','snapshot_course_category_filtering_end'));
		return $finalUnis;
		*/
		//
		// Re-implementing this logic for performance enhancement
		// Author : Rahul Bhatnagar (Again!)
		$univIds = array();
		$univCourseAssociations = array();
		foreach($resultIds as $result){
			$univIds[$result['university_id']] = $result['university_id'];
			$univCourseAssociations[$result['university_id']][$result['course_id']] = $result['course_id'];
		}
		$CI = & get_instance();
		$univIds = array_filter($univIds,"is_numeric");
		if(!empty($univIds)){
			$universities = $this->universityRepository->findMultiple($univIds);
		}
		else{
			$universities = array();
		}
		foreach($universities as $key=>$univ){
			$univ->setSnapshotCoursesArray($univCourseAssociations[$univ->getId()]);
			$universities[$key] = $univ;
		}
		
		return $universities;
	}

}
