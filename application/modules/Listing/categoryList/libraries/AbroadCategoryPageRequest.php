<?php

class AbroadCategoryPageRequest
{
    private $categoryId;
    private $subCategoryId;
    private $LDBCourseId;
    private $courseLevel;
    private $cityId;
    private $stateId;
    private $countryId;	    
    private $pageNumber;
    private $acceptedExamName; // in case of category page accepting a particular exam
    private $acceptedExamId;   // in case of category page accepting a particular exam
    private $canonicalUrlForExamAcceptedPage;// referred to as canonical in case of category page accepting a particular exam
    private $urlStringForExamAcceptedPage; // url string of the exam accepting page opened
    private $urlParametersForAppliedFilters; // filters from url parameters for fees,specialization,moreoptions
    private $queryStringFromUrlParameters; // a valid query string generated from $urlParametersForAppliedFilters
    public  $sortingCriteria;
    public  $examId; // used for the new exam accepting category pages
    private $examName; // used for the new exam accepting category pages
    private $examAcceptingCourseName; // used for the new exam accepting category pages
    public  $examScore; // used for the new exam accepting category pages
    private $createExamCategoryPageUrlFlag = false;
    private $resultsPerPage = 20;
    private $includeSnapshotCourse = true;
    private $filtersSet;
    public  $isExamCategoryPageWithScore;
    private $buildCategoryPageViaSolr = true;
    private $flagToGetCertDiplomaResults = false; // this variable is set and used when a non cert-dipl category page is opened, but only C-D results exist


    public function __construct($categoryPageURLQueryString = '') {
	    $this->buildFromURLQueryString($categoryPageURLQueryString);
    }
	
    public function buildFromURLQueryString($categoryPageURLQueryString)
    {
		$this->categoryId = (int) ($categoryPageURLQueryString['categoryId'] == "" ? 1 : $categoryPageURLQueryString['categoryId']);
		if(is_array($categoryPageURLQueryString['subCategoryId']) && count($categoryPageURLQueryString['subCategoryId'])>0)
		{
			$this->subCategoryId = array_map(function($a){return (int)$a;},$categoryPageURLQueryString['subCategoryId']);
		}
		else{
			$this->subCategoryId = (int) ($categoryPageURLQueryString['subCategoryId'] == "" ? 1 : $categoryPageURLQueryString['subCategoryId']);
		}
		$this->LDBCourseId = (int) ($categoryPageURLQueryString['LDBCourseId'] == "" ? 1 : $categoryPageURLQueryString['LDBCourseId']);
		$this->cityId = 1;
		$this->stateId = 1;
		$this->countryId = $categoryPageURLQueryString['countryId'];
		$this->courseLevel = $categoryPageURLQueryString['courseLevel'];
		$this->pageNumber = (int) $categoryPageURLQueryString['pageNumber']!=""?$categoryPageURLQueryString['pageNumber']:1;
		$this->acceptedExamName = $categoryPageURLQueryString['acceptedExamName']!=""?$categoryPageURLQueryString['acceptedExamName']:'';
		
		$this->examId = $categoryPageURLQueryString['examId']!=""?$categoryPageURLQueryString['examId']:'';
		$this->examName = $categoryPageURLQueryString['examName']!=""?$categoryPageURLQueryString['examName']:'';
		$this->examAcceptingCourseName = $categoryPageURLQueryString['examAcceptingCourseName']!=""?$categoryPageURLQueryString['examAcceptingCourseName']:'';
		$this->examScore = $categoryPageURLQueryString['examScore']!=""?$categoryPageURLQueryString['examScore']:'';// this is supposed to be an array
		if(is_array($this->examScore)){
			$this->isExamCategoryPageWithScore = true;
		}else{
			$this->isExamCategoryPageWithScore = false;
		}
		$this->createExamCategoryPageUrlFlag = $categoryPageURLQueryString['createExamCategoryPageUrlFlag']!=""?$categoryPageURLQueryString['createExamCategoryPageUrlFlag']:'';
		if(!is_null($categoryPageURLQueryString['buildCategoryPageViaSolr']))
		{ // for save filters to cache for all cat pages
			$this->buildCategoryPageViaSolr = $categoryPageURLQueryString['buildCategoryPageViaSolr'];
		}
		if($_COOKIE["filterInteracted".$this->getPageKey()] == "1"){
			$this->filterInteracted = true;
		}
		// get accepted exam's id
		$CI 			= & get_instance();
		$abroadCommonLib 	= $CI->load->library('listingPosting/AbroadCommonLib');
		// get full exam data
		$examListData 		= $abroadCommonLib->getAbroadExamsMasterList();
		// get only exam id for accepted exam
		foreach ($examListData as $key => $val) {
			if ($val['exam'] == strtoupper($this->acceptedExamName)) {
			$exam =$val['examId'];
			break;
			}
		}
		if($exam>0)
		{
			$this->acceptedExamId   = $exam;
		}
		if($this->isAJAXCall() && !$this->isSortAJAXCall()){ // If filters have been interacted with
			setcookie("filterInteracted".$this->getPageKey(),1,time()+1800,'/',COOKIEDOMAIN);
			$this->filterInteracted = true;
		}
		if(!$this->isAJAXCall()){
			setcookie("filterInteracted".$this->getPageKey(),1,time()-1,'/',COOKIEDOMAIN);
			$this->filterInteracted = false;
		}
    }
	
    public function setData($data = array())
    {
	foreach($data as $key => $value) {
		if(property_exists($this,$key)) {
			$this->$key = $value;
		}
	}
    } 
		    
	      
	public function isLDBCoursePage() {
	    if($this->LDBCourseId > 1 && $this->subCategoryId == 1 && $this->categoryId == 1 && $this->courseLevel == "") {
		return true;
	    }
	     
	    return false;
	}
	
	public function isLDBCourseSubCategoryPage() {
	    if($this->LDBCourseId > 1 &&
		   (
			$this->subCategoryId > 1 ||
			(is_array($this->subCategoryId) && count($this->subCategoryId)>0)
			) &&
		   $this->categoryId == 1 &&
		   $this->courseLevel == "") {
		return true;
	    }
	     
	    return false;
	}
	
	public function isCategorySubCategoryCourseLevelPage() {
	    if($this->LDBCourseId == 1 &&
		   (
			$this->subCategoryId > 1 ||
			(is_array($this->subCategoryId) && count($this->subCategoryId)>0)
			) &&
		   $this->categoryId >= 1 &&
		   $this->courseLevel != "") {
		return true;
	    }
	     
	    return false;
	}
	
	public function isCategoryCourseLevelPage() {
	    if($this->LDBCourseId == 1 && $this->subCategoryId == 1 && $this->categoryId > 1 && $this->courseLevel != "") {
		return true;
	    }
	     
	    return false;
	}
	
	public function getSeoInfo($pageNumber = 1) {
		if($this->isExamCategoryPage())
		{
			$seoData = $this->_getSEODataForExamAcceptedCategoryPage();
		}
		else{
			$seoData = $this->_getSEOData($pageNumber);
		}
	    return $seoData;
	}
	
	public function getURL($pageNumber = 1)
	{
		if($this->isExamCategoryPage())
		{
			$urlData = $this->_getSEODataForExamAcceptedCategoryPage($pageNumber,true);
		}
		else{
			$urlData = $this->_getSEOData($pageNumber);
		}
		
		if($urlData)
		{
			return $urlData['url'];
		}
		
	}
	
	private function _getSEOData($pageNumber)
	{
		global $courseLevelAndMainCategoryPages, $ldbCourseCategoryPages, $ldbCourseAndSubCategoryPages, $courseLevelSubCategoryAndMainCategoryPages,$categoryPageAcceptingExam;
		
		if(!count($this->countryId))
		{
		    return false;
		}
		
		$ci = & get_instance();		
		$ci->load->builder('LocationBuilder','location');
		$locationBuilder 	= new LocationBuilder;
		$locationRepository 	= $locationBuilder->getLocationRepository();
		
		// $abroadcmsmodel = $ci->load->model('listingPosting/abroadcmsmodel');
		$ci->load->builder('CategoryBuilder','categoryList');		
		$categoryBuilder 	= new CategoryBuilder;
		$categoryRepository 	= $categoryBuilder->getCategoryRepository();
		
		$categoryId 	 = (int) ($this->categoryId == "" ? 1 : $this->categoryId);
		$subCategoryId 	 = (int) ($this->subCategoryId == "" ? 1 : $this->subCategoryId);
		$LDBCourseId 	 = (int) ($this->LDBCourseId == "" ? 1 : $this->LDBCourseId);
		$courseLevel	 = ucwords($this->courseLevel);

		$cityId 	= (int) ($this->cityId == "" ? 1 : $this->cityId);
		$stateId 	= (int) ($this->stateId == "" ? 1 : $this->stateId);
		$countryIds 	= implode(",",$this->countryId);
		//exam name in case of "accepting exam" category page
		    $examName 	= strtoupper($this->getAcceptedExamName());
    
		/*
		 * LDB Course - Country Page
		 */
	    if($this->isLDBCoursePage())
	    {		   
			$ci->load->builder('LDBCourseBuilder','LDB');
			$LDBCourseBuilder  = new LDBCourseBuilder;
			$ldbRepository 	   = $LDBCourseBuilder->getLDBCourseRepository();
			$ldbCourse 		   = $ldbRepository->find($LDBCourseId);
			$subCategory 	   = $categoryRepository->find($subCategoryId);
			$categoryId 	   = $subCategory->getParentId();
			$ldbCourseId	   = $ldbCourse->getId();
			// check if this is the case of category page accepting exam page
			if ($examName != ''){
				if($countryIds != "1" && count($this->countryId) == 1){
					$url    = $categoryPageAcceptingExam['LDB_COURSE']['countryCatPageUrl'];
				}else{
					$url    = $categoryPageAcceptingExam['LDB_COURSE']['url'];
				}
			    $url 	= str_replace('{ldbCourse}', seo_url_lowercase($ldbCourse->getCourseName()), $url);
			    $url 	= str_replace('{pageIdentifier}', 'dc'.($countryIds=="1" || count($this->countryId) > 1?'1'.$ldbCourseId:''), $url);
			    $url 	= str_replace('{exam}', $examName, $url);
	    
			    $pageTitle = str_replace('{ldbCourse}', $ldbCourse->getCourseName(), $categoryPageAcceptingExam['LDB_COURSE']['title']);
			    $pageTitle = str_replace('{exam}', $examName, $pageTitle);
			    
			    $pageDescription = str_replace('{ldbCourse}', $ldbCourse->getCourseName(), $categoryPageAcceptingExam['LDB_COURSE']['description']);
			    $pageDescription = str_replace('{exam}', $examName, $pageDescription);
			
			    $this->urlStringForExamAcceptedPage = $url;
			    $this->canonicalUrlForExamAcceptedPage = '';
			}
			else
			{
			    /*if its a be btech page then generate a different SEO data where DESIRED_COURSE_BTECH is ldbcourse Id of BE BTECH*/
			    if($ldbCourseId== DESIRED_COURSE_BTECH || $ldbCourseId== DESIRED_COURSE_MBA){
			    	if($countryIds != "1" && count($this->countryId) == 1){
			    		$url = $ldbCourseCategoryPages['GENERAL']['countryCatPageUrl'];
			    	}else{
			    		$url = $ldbCourseCategoryPages['GENERAL']['url'];
			    	}
					$url 			= str_replace('{ldbCourse}', seo_url_lowercase($ldbCourse->getCourseName()), $url);
					$url 			= str_replace('{pageIdentifier}', 'dc'.($countryIds=="1" || count($this->countryId) > 1?'1'.$ldbCourseId:''), $url);
				//For abroad Case Check
					if($this->countryId[0]==1){
						$pageTitle 		= $ldbCourseCategoryPages['LDBCOURSE']["abroadPage_".$ldbCourseId]['title'];
						$pageDescription 	= $ldbCourseCategoryPages['LDBCOURSE']["abroadPage_".$ldbCourseId]['description'];
					}else{
						$pageTitle 		= $ldbCourseCategoryPages['LDBCOURSE']["countryPage_".$ldbCourseId]['title'];
						$pageDescription 	= $ldbCourseCategoryPages['LDBCOURSE']["countryPage_".$ldbCourseId]['description'];
					}
			    }
			    else
			    {
			    	if($countryIds != "1" && count($this->countryId) == 1){
			    		$url = $ldbCourseCategoryPages['GENERAL']['countryCatPageUrl'];
			    	}else{
			    		$url = $ldbCourseCategoryPages['GENERAL']['url'];
			    	}
				    $url 		= str_replace('{ldbCourse}', seo_url_lowercase($ldbCourse->getCourseName()), $url);
				    $url 		= str_replace('{pageIdentifier}', 'dc'.($countryIds=="1" || count($this->countryId) > 1?'1'.$ldbCourseId:''), $url);
				    $pageTitle 		= str_replace('{ldbCourse}', $ldbCourse->getCourseName(), $ldbCourseCategoryPages['GENERAL']['title']);
				    $pageDescription 	= str_replace('{ldbCourse}', $ldbCourse->getCourseName(), $ldbCourseCategoryPages['GENERAL']['description']);
			    }
			
			}
		}
		/*
		 * LDB Course - Subcategory - Country Page
		 */
		if($this->isLDBCourseSubCategoryPage())
		{		   
		    $ci->load->builder('LDBCourseBuilder','LDB');
		    $LDBCourseBuilder   = new LDBCourseBuilder;
		    $ldbRepository 	= $LDBCourseBuilder->getLDBCourseRepository();
		    $ldbCourse 		= $ldbRepository->find($LDBCourseId);
		    $subCategory 	= $categoryRepository->find($subCategoryId);
		    $categoryId 	= $subCategory->getParentId();
		    $ldbCourseId        = $ldbCourse->getId();
		   
		    $msArray = array(CATEGORY_ENGINEERING, CATEGORY_COMPUTERS, CATEGORY_SCIENCE);
		    $btechArray = array(CATEGORY_ENGINEERING, CATEGORY_COMPUTERS);
		    
		     /*if its MS subcategory page then generate the conditional SEO Data where LDB course Id of MS is DESIRED_COURSE_MS for
		    All Specialization pages of Masters of Engineering, Masters of Science, and Masters of Computer Categories.*/ 
		    if($ldbCourseId==DESIRED_COURSE_MS && in_array($categoryId, $msArray)==true)
		    {
			$seoData = $ldbCourseAndSubCategoryPages['LDBCOURSE'][$ldbCourseId]; 		
		    }
		    /*if its BE BTECH subcategory page then generate the conditional SEO Data where LDB course Id of BE BTECH is DESIRED_COURSE_BTECH
		    All Specialization pages of Bachelors of Engineering and Computers Category.*/
		    else if($ldbCourseId==DESIRED_COURSE_BTECH && in_array($categoryId, $btechArray)==true)
		    {
			$seoData = $this->setBTechSeoData($ldbCourseId,$categoryId,$subCategoryId);
		    }
		    else if(is_array($ldbCourseAndSubCategoryPages['SUBCATEGORY'][$subCategoryId]))
		    {
			 $seoData = $ldbCourseAndSubCategoryPages['SUBCATEGORY'][$subCategoryId];
		    }
		    elseif(is_array($ldbCourseAndSubCategoryPages['CATEGORY'][$categoryId]))
		    {
			 $seoData = $ldbCourseAndSubCategoryPages['CATEGORY'][$categoryId];
		    }
		    else
		    {
			 $seoData = $ldbCourseAndSubCategoryPages['GENERAL'];
		    }
 			if($countryIds !="1" && count($this->countryId) == 1){
 				$url = $seoData['countryCatPageUrl'];
 			}else{
 				$url = $seoData['url'];
 			}
			$url = str_replace('{ldbCourse}', seo_url_lowercase($ldbCourse->getCourseName()), $url);
			$url = str_replace('{subcategory}', seo_url_lowercase($subCategory->getName()), $url);
			$url = str_replace('{pageIdentifier}', 'ds'.($countryIds=="1" || count($this->countryId) > 1?'1'.$ldbCourseId.$subCategory->getId():''), $url);
			if($examName!='')
			{
			    $this->canonicalUrlForExamAcceptedPage = $url;
			    if($countryIds != "1"){
			    	$url = $categoryPageAcceptingExam['LDB_COURSE_SUBCAT']['countryCatPageUrl'];
			    }else{
			    	$url = $categoryPageAcceptingExam['LDB_COURSE_SUBCAT']['url'];
			    }
			    $url = str_replace('{ldbCourse}', seo_url_lowercase($ldbCourse->getCourseName()), $url);
			    $url = str_replace('{subcategory}', seo_url_lowercase($subCategory->getName()), $url);
			    $url = str_replace('{pageIdentifier}', 'ds'.($countryIds=="1" || count($this->countryId) > 1?'1'.$ldbCourseId.$subCategory->getId():''), $url);
			    $url = str_replace('{exam}', $examName, $url);
			    $this->urlStringForExamAcceptedPage = $url;
			}
			$pageTitle = str_replace('{ldbCourse}', $ldbCourse->getCourseName(), $seoData['title']);
			$pageTitle = str_replace('{subcategory}', ($subCategory->getName()), $pageTitle);
		
			$pageDescription = str_replace('{ldbCourse}', $ldbCourse->getCourseName(), $seoData['description']);
			$pageDescription = str_replace('{subcategory}', ($subCategory->getName()), $pageDescription);
		}

		/*
		 * Category - Subcategory - Course Level - Country Page
		 */
		if($this->isCategorySubCategoryCourseLevelPage())
		{		   
		   	$subCategory  = $categoryRepository->find($subCategoryId);
		   	$categoryId 	 = $subCategory->getParentId();

		    /*if its computers category with boardID CATEGORY_COMPUTERS of Bachelors then set the meta data and title for its subCategory*/
		   	if($categoryId== CATEGORY_COMPUTERS && $courseLevel=="Bachelors")
		   	{
				$seoData = $courseLevelSubCategoryAndMainCategoryPages['COURSE_LEVEL']['BACHELORS'][$categoryId];
		   	}
		   	else if(is_array($courseLevelSubCategoryAndMainCategoryPages['SUBCATEGORY'][$subCategoryId])) {		    
				$seoData = $courseLevelSubCategoryAndMainCategoryPages['SUBCATEGORY'][$subCategoryId];
		   	}elseif(is_array($courseLevelSubCategoryAndMainCategoryPages['CATEGORY'][$categoryId])) {		    
				$seoData = $courseLevelSubCategoryAndMainCategoryPages['CATEGORY'][$categoryId];			
		   	} else{
				$seoData = $courseLevelSubCategoryAndMainCategoryPages['GENERAL'];
		   	}
		   	if($countryIds != "1" && count($this->countryId) == 1){
		   		$url 	= $seoData['countryCatPageUrl'];
		   	}else{
		   		$url 	= $seoData['url'];
		   	}
		   
		   	$url 	= str_replace('{level}', $courseLevel, $url);
		   	$url 	= str_replace('{subcategory}', seo_url_lowercase($subCategory->getName()), $url);
		   	$url 	= str_replace('{pageIdentifier}', 'sl'.($countryIds=="1" || count($this->countryId) > 1?'1'.$subCategory->getId():''), $url);		   
		   
		   	if($examName!=''){
			    $this->canonicalUrlForExamAcceptedPage = $url;
			    if($countryIds != "1"){
			    	$url = $categoryPageAcceptingExam['CAT_SUBCAT_COURSELEVEL']['countryCatPageUrl'];
			    }else{
			    	$url = $categoryPageAcceptingExam['CAT_SUBCAT_COURSELEVEL']['url'];
			    }
			    $url = str_replace('{level}', $courseLevel, $url);
			    $url = str_replace('{subcategory}', seo_url_lowercase($subCategory->getName()), $url);
			    $url = str_replace('{pageIdentifier}', 'sl'.($countryIds=="1" || count($this->countryId) > 1?'1'.$subCategory->getId():''), $url);
			    $url = str_replace('{exam}', $examName, $url);
			    // now save the url string for exam accepted page (required to prevent 301 redirection because canonical != page url)
			    $this->urlStringForExamAcceptedPage = $url;
			}
		   $pageTitle = str_replace('{level}', $courseLevel, $seoData['title']);
		   $pageTitle = str_replace('{subcategory}', ($subCategory->getName()), $pageTitle);
		   
		   $pageDescription = str_replace('{level}', $courseLevel, $seoData['description']);
		   $pageDescription = str_replace('{subcategory}', ($subCategory->getName()), $pageDescription);
		}
		
		
		/*
		 * Category - Course Level - Country Page
		 */
		if($this->isCategoryCourseLevelPage())
		{
		    $category = $categoryRepository->find($categoryId);
		    if(is_array($courseLevelAndMainCategoryPages['COURSE_LEVEL'][$courseLevel])) {			
			 	$seoData = $courseLevelAndMainCategoryPages['COURSE_LEVEL'][$courseLevel];
		    } else {			
			 	$seoData = $courseLevelAndMainCategoryPages['GENERAL'];
		    }
 			if($countryIds != "1" && count($this->countryId) == 1){
 				$url = $seoData['countryCatPageUrl'];
 			}else{
 				$url = $seoData['url'];
 			}
		    $url = str_replace('{level}', $courseLevel, $url);
		    $url = str_replace('{category}', $category->getName(), $url);
		    $url = str_replace('{pageIdentifier}', 'cl'.($countryIds=="1" || count($this->countryId) > 1?'1'.$categoryId:''), $url);
		    
		    if($examName!='')
			{
		    $this->canonicalUrlForExamAcceptedPage = $url;
		    if($countryIds != "1" && count($this->countryId) == 1){
		    	$url = $categoryPageAcceptingExam['CAT_COURSELEVEL']['countryCatPageUrl'];
		    }else{
		    	$url = $categoryPageAcceptingExam['CAT_COURSELEVEL']['url'];
		    }
		    $url = str_replace('{level}', $courseLevel, $url);
		    $url = str_replace('{category}', $category->getName(), $url);
		    $url = str_replace('{pageIdentifier}', 'cl'.($countryIds=="1" || count($this->countryId) > 1?'1'.$categoryId:''), $url);
		    $url = str_replace('{exam}', $examName, $url);
		    // now save the url string for exam accepted page (required to prevent 301 redirection because canonical != page url)
		    $this->urlStringForExamAcceptedPage = $url;
			}
		    $pageTitle = str_replace('{level}', $courseLevel, $seoData['title']);
		    $pageTitle = str_replace('{category}', $category->getName(), $pageTitle);
		    
		    $pageDescription = str_replace('{level}', $courseLevel, $seoData['description']);
		    $pageDescription = str_replace('{category}', $category->getName(), $pageDescription);
		}
		
		if($pageNumber > 1)
		{
		    $url = $url."-".$pageNumber;
			$this->urlStringForExamAcceptedPage = $this->urlStringForExamAcceptedPage."-".$pageNumber;
			if($this->canonicalUrlForExamAcceptedPage!='')
			{
			$this->canonicalUrlForExamAcceptedPage = $this->canonicalUrlForExamAcceptedPage."-".$pageNumber;
			}
		}
		
		/*
		 * Handling location now..
		 */
		if(count($this->countryId) > 1)
		{
		    $countryIdsQryStr 	= ''; $locationToReplaced = '';			
		    $countryData 	= $locationRepository->getAbroadCountryByIds($this->countryId);
		    foreach($this->countryId as $key => $countryId)
		    {
			$countryName 		=  $countryData[$countryId]->getName();
			$countryNameFormatted 	= str_replace(" ", "", $countryName);
			$countryIdsQryStr 	.= ($countryIdsQryStr == '' ? '?country='.$countryNameFormatted : '-'.$countryNameFormatted);
			$locationToReplaced 	.= ($locationToReplaced == '' ? $countryName : ', '.$countryName);
		    }
		    
		    $url = str_replace("{location}", 'abroad', $url);
		    $filterUrlParams 	= $this->getQueryStringFromUrlParameters();
		    if($filterUrlParams != '')
		    {
			$countryIdsQryStr .= '&'.$filterUrlParams;
		    }
            $url = strtolower($url);
		    $url .= $countryIdsQryStr;
		    if($this->canonicalUrlForExamAcceptedPage != '')
		    {
			$this->canonicalUrlForExamAcceptedPage 	= str_replace("{location}", 'abroad', $this->canonicalUrlForExamAcceptedPage);
			$this->canonicalUrlForExamAcceptedPage .= $countryIdsQryStr;
		    }
		    $this->urlStringForExamAcceptedPage 	= str_replace("{location}", 'abroad', $this->urlStringForExamAcceptedPage);
		    $this->urlStringForExamAcceptedPage 	.= $countryIdsQryStr;
		    $pageTitle 					= str_replace('{location}', $locationToReplaced, $pageTitle);
		    $pageDescription 				= str_replace('{location}', $locationToReplaced, $pageDescription);
		}else{
		    if($countryIds == 1){
				$countryNameFormatted = "abroad";
				$countryName = "abroad";
		    }else{
				$countryData 	= $locationRepository->getAbroadCountryByIds($this->countryId);
				$countryName 	= "";
				if($countryData[$countryIds]){
				    $countryName =  $countryData[$countryIds]->getName();
				}
				$countryNameFormatted = str_replace(" ", "-", $countryName);
		    }
		    $url 		= str_replace("{location}", $countryNameFormatted, $url);
		    $url = strtolower($url);
		    $filterUrlParams 	= $this->getQueryStringFromUrlParameters();
		    
		    if($filterUrlParams != ''){
				$url .= '?'.$filterUrlParams;
		    }

		    if($this->canonicalUrlForExamAcceptedPage!=''){
				$this->canonicalUrlForExamAcceptedPage = str_replace("{location}", $countryNameFormatted, $this->canonicalUrlForExamAcceptedPage);
		    }

		    $this->urlStringForExamAcceptedPage = str_replace("{location}", $countryNameFormatted, $this->urlStringForExamAcceptedPage);
		    
		    if($filterUrlParams != ''){
				$this->urlStringForExamAcceptedPage .= '?'.$this->getQueryStringFromUrlParameters();
		    }
		    $pageTitle = str_replace('{location}', $countryName, $pageTitle);
		    $pageDescription = str_replace('{location}', $countryName, $pageDescription);
			
			if($countryName == "abroad"){
				$pageTitle = str_replace('in abroad', 'abroad', $pageTitle);
			    $pageDescription = str_replace('in abroad', 'abroad', $pageDescription);
			}
		}
		$url = str_replace(array(' ','(',')',','),'-',$url);
		$url = preg_replace('!-+!', '-', $url);
		$url = strtolower(trim($url,'-'));

	    /*
	     * Avoid unwanted chars from canonical url as well as url string in case of exam accepting pages
	     */
	    if($this->canonicalUrlForExamAcceptedPage !='')
		{
			$this->canonicalUrlForExamAcceptedPage = str_replace(array(' ','(',')',','),'-',$this->canonicalUrlForExamAcceptedPage);
			$this->canonicalUrlForExamAcceptedPage = preg_replace('!-+!', '-', $this->canonicalUrlForExamAcceptedPage);
			$this->canonicalUrlForExamAcceptedPage = strtolower(trim($this->canonicalUrlForExamAcceptedPage,'-'));
	    }

	    $this->urlStringForExamAcceptedPage = str_replace(array(' ','(',')',','),'-',$this->urlStringForExamAcceptedPage);
	    $this->urlStringForExamAcceptedPage = preg_replace('!-+!', '-', $this->urlStringForExamAcceptedPage);
	    $this->urlStringForExamAcceptedPage = strtolower(trim($this->urlStringForExamAcceptedPage,'-'));
	
		
	    // prefix domain to url
	    $domainPrefix = SHIKSHA_STUDYABROAD_HOME;		
	    $url = $domainPrefix.'/'.$url.$urlIdentifier;
	    // prefix domain to canonical & url string in case of exam accepting pages also..
	    if($this->canonicalUrlForExamAcceptedPage !='')
	    {
		    $this->canonicalUrlForExamAcceptedPage = ($this->canonicalUrlForExamAcceptedPage!='' ? $domainPrefix.'/'.$this->canonicalUrlForExamAcceptedPage:'');
	    }

		$this->urlStringForExamAcceptedPage    = ($this->urlStringForExamAcceptedPage != ''  ? $domainPrefix.'/'.$this->urlStringForExamAcceptedPage:'');
		
		if($pageNumber > 1)
		{
		    $pageTitle = 'Page '.$pageNumber.' - '.$pageTitle;
		    $pageDescription = 'Page '.$pageNumber.' - '.$pageDescription;
		}

		$catPageSeoData['url'] = $url;
		$catPageSeoData['title'] = $pageTitle;
		
		if($this->courseLevel == "certificate - diploma")
		{
		    $pageDescription = str_replace("degree ", "", $pageDescription);
		}
		
		$catPageSeoData['description'] = $pageDescription;
		return $catPageSeoData;
	}
	
	public function getCanonicalUrl($pageNumber = 1) {
	    if($this->acceptedExamName != '' && $this->canonicalUrlForExamAcceptedPage != '')
		{
	    	$url = $this->canonicalUrlForExamAcceptedPage;
	    	//echo "Canonical for exam accepting page";_p($url);
		}
		else{
	    	$url = $this->getURL($pageNumber);
		}
	    $pos = strpos($url, '?');
	    if($pos !==  false) {
		$qryStr = substr($url, $pos);
		$url = str_replace($qryStr, '', $url);		
	    }
	    //echo "url = ".$url; die;
	    return $url;
	}
	
	public function getCourseLevel(){
		return $this->courseLevel;
	}
	
	public function isCityPage()
	{
		return (intval($this->cityId) > 1);
	}
	
	public function isStatePage()
	{
		return (intval($this->stateId) > 1 && intval($this->cityId) <= 1);
	}
	
	public function setAppliedFilters($filters = array())
	{
		$this->appliedFilters = $filters;
	}
	
	public function getCourseLevelForDesiredCourse($desiredCourseId) {
        global $studyAbroadPopularCourseToLevelMapping;
        if(!is_null($studyAbroadPopularCourseToLevelMapping[$desiredCourseId])) {
            return strtolower($studyAbroadPopularCourseToLevelMapping[$desiredCourseId]);
        }
        return null;
	}
	
	/**
	* Purpose : Method to collect all filters applied by the user
	* To Do   : none
	* Author  : Romil Goel
	*/
	public function getAppliedFilters()
	{
		if($this->filtersSet === true){
			return $this->appliedFilters;
		}
		else
		{
		    // get filter data from the cookie
		    $pageKey 		= $this->getPageKey();
		    $filterPageKey 	= "FILTERS-".$pageKey;
		    $filterValue 	= $_COOKIE[$filterPageKey];
		    $filterValue 	= json_decode($filterValue,true);
		    $firstTimer     = $_COOKIE["ftu-".$pageKey];
		    // get the filter selection order from the cookie to be used to set the javascript object in the header
		    $this->filterSelectionOrder = $filterValue["filterSelectedOrder"];
		    
		    // parse the form string to make the individual variables out of string
		    parse_str($filterValue["filterValues"]);
		    // in case of exam accepting pages, get exam id to appply in filters
		    if($this->acceptedExamId){
		    	$res = $this->_applyExamFilterFromUrl($exam, $filterPageKey);
		    	$exam = $res['exam'];
		    	$setCookieFlagForExamAcceptingPage = $res['flag'];
		    }
			// in case of new exam category page, apply exam as a filter
			if($this->isExamCategoryPage()){
		    	$res = $this->_applyExamCategoryPageFilters($filterPageKey);
		    	$exam = $res['exam'];
		    	$setCookieFlagForExamAcceptingPage = $res['flag'];
		    }
		    // filterset that can be passed via url params
		    $filterSet = array(
					'fee'=>$fee,
					'course'=>$course,
					'moreopt'=>$moreopt
					);
		    // apply them as filters
		    if(count($res = $this->_applyFiltersFromUrlParameters($filterSet))>0)
		    {
				$setCookieFlagForUrlParams = 1;
				foreach($res as $key => $val)
				{
				    $$key = $val;
				}
		    }
		    
		    // set filter cookie
		    if($setCookieFlagForUrlParams ==1 || $setCookieFlagForExamAcceptingPage ==1){	// when user lands on page via url,first page, not from 2nd/3rd/.. page
				if(!$this->isAJAXCall() && $this->getPageNumberForPagination() == 1){
			    	$this->_rebuildFilterCookie($filterPageKey, $setCookieFlagForExamAcceptingPage);
				}
		    }
		    if(!empty($exam)){
				$appliedFilters["exam"] = $exam;
		    }
		    if(!empty($city)){
				$appliedFilters["city"] = $city;
		    }
		    if(!empty($fee)){
				$appliedFilters["fees"] = $fee;
		    }
		    if(!empty($course)){
				$appliedFilters["specialization"] = $course;
		    }
		    if(!empty($moreopt)){
				$appliedFilters["moreoption"] = $moreopt;
		    }
		    if(!empty($countryList)){
				$appliedFilters["country"] = $countryList;
		    }
		    if(!empty($stateList)){
				$appliedFilters["state"] = $stateList;
		    }
		    if(!empty($cityList)){
				$appliedFilters["city"] = $cityList;
		    }
		    if((!$this->isAJAXCall() && $this->isExamCategoryPage()) || ($this->isExamCategoryPage() && $this->isSortAJAXCall() && !$this->filterInteracted)){
		    	$examsMinScore = array("--".$this->examScore[0]."--".reset($exam));
		    	$examsScore = array("--".$this->examScore[1]."--".reset($exam));
		    }
	    	if(!empty($examsScore)){
				foreach($examsScore as $key=>$val){
					if(empty($val)){
						unset($examsScore[$key]);
					}
				}
			}
			if(!empty($examsMinScore)){
				foreach($examsMinScore as $key=>$val){
					if(empty($val)){
						unset($examsMinScore[$key]);
					}
				}
			}
			if(!empty($examsScore)){
				$appliedFilters["examsScore"] = $examsScore;
			}
			if(!empty($examsMinScore)){
				$appliedFilters["examsMinScore"] = $examsMinScore;
			}
			if(empty($firstTimer)){
				$appliedFilters['moreoption'][] = 'DEGREE_COURSE';
		    }
		    $this->appliedFilters = $appliedFilters;
		    $this->filtersSet = true;
		    return $this->appliedFilters;
		}
	}

	/*
	 * function that sets the $flagToGetCertDiplomaResults if country among the set of cert diploma countries
	 * (countries where certifcate diploma must be shown be default)
	 */
	public function checkIfCertDiplomaCountryCatPage()
	{
		$requestCountries = $this->getCountryId();
		
		$filterPageKey 	= "FILTERS-".$this->getPageKey();
		$filterValue 	= $_COOKIE[$filterPageKey];
		$filterValue 	= json_decode($filterValue,true);
		parse_str($filterValue["filterValues"], $vals);
		
		$filterInCookie = (!is_null($vals['moreopt']) && count($vals['moreopt']) > 0);
	
		global $certificateDiplomaCountries;
		if (
			count($requestCountries) ==1 && 		// check only for solo country page
			in_array(reset($requestCountries),$certificateDiplomaCountries) &&   // check if among the list
			// check that it shouldn't be ajax call  (not ajax filter call on page load but the one that causes filter to be applied)
			(!($this->isAJAXCall() && !$this->isSolrFilterAjaxCall())) &&
			$filterInCookie === false 
		   )
		{
			return true;
		}else{
			return false;
		}
	}
    /*
     * function to apply exam to filter from exam accepting page Url
     * 
     */
    private function _applyExamFilterFromUrl($exam, $filterPageKey)
    {
		// landed on page via url, first page, not refreshed or reached via pagination
		if(!$this->isAJAXCall() && $this->getPageNumberForPagination() == 1 && strpos($_SERVER['HTTP_REFERER'],$this->urlStringForExamAcceptedPage) === FALSE)
		{
			// get exam from url selected in exam filter
			if($exam === NULL) {
					//no exam filter selected
					$exam = array($this->acceptedExamId);
					$setCookieFlag =1;
					//$this->_rebuildCookieForExamAcceptingPage($filterPageKey);
			}
			else{
					// add to existing exam filter selected (if page was not refreshed)
					if(!in_array($this->acceptedExamId,$exam)){
					array_push($exam,$this->acceptedExamId);
					$setCookieFlag =1;
					//$this->_rebuildCookieForExamAcceptingPage($filterPageKey);
					}
			}
		}
		return array('exam'=>$exam,'flag'=>$setCookieFlag);
    }
	/*
     * function to apply exam, score  to filter for exam category page
     * 
     */
    private function _applyExamCategoryPageFilters($filterPageKey)
    {
		// new exam category page
		if($this->isExamCategoryPage()) {
				$exam = array($this->examId);
				$setCookieFlag =1;
		}
		return array('exam'=>$exam,'flag'=>$setCookieFlag);
    }
    /*
     * function to apply filter from url parameters
     */
    private function _applyFiltersFromUrlParameters($filters= array())
    {
	// traverse through each filter avalaible from url parameters...
	if(!$this->isAJAXCall() && $this->getPageNumberForPagination() == 1 && $this->_checkRefererWithUrlParams()){
	    foreach($this->urlParametersForAppliedFilters as $key=>$value)
	    {
		// add those values from url parameters to current set of $filters passed
		foreach($value as $v)
		{
		    if($filters[$key] === NULL)
		    {
			$filters[$key] = array($v);
		    }
		    else if(!in_array($v,$filters[$key])){
			array_push($filters[$key],$v);
		    }
		}
	    }
	}
	// remove key from $filters if null
	foreach($filters as $key=>$val)
	{
	    if($filters[$key] === NULL)
	    {
		unset($filters[$key]);
	    }
	}
	//_p($filters);
	return $filters;
    }
    /*
    * function to add filter values passed via url parameters
    * as well as exam from url of exam accepting category pages to the filter cookie
    */
    private function _rebuildFilterCookie($filterPageKey,$flagToSetExamFromUrl)
    {	//_p($filterPageKey);
	    // decode whole cookie
	    $cookieVal = json_decode($_COOKIE[$filterPageKey],true);
	    // decode selection order
	    $cookieVal['filterSelectedOrder'] = json_decode($cookieVal['filterSelectedOrder'],true);
	    if($flagToSetExamFromUrl == 1) // exam accepting category page case OR exam category page case
	    {
			$examId = $this->isExamCategoryPage()?$this->examId:$this->acceptedExamId;
			$cookieVal['filterValues'] .= ($cookieVal['filterValues'] == '' ? '':"&")."exam[]=".$examId;
			if($this->isExamCategoryPage()){
				$cookieVal['filterValues'] .= "&examsMinScore[]=--".$this->examScore[0]."--".$examId;
				$cookieVal['filterValues'] .= "&examsScore[]=--".$this->examScore[1]."--".$examId;
			}
			$cookieVal['filterSelectedOrder']['exam'][$examId] = 1;
	    }
	    // add filter values from url parameters
	    if(!$this->isAJAXCall() && $this->getPageNumberForPagination() == 1 && $this->_checkRefererWithUrlParams())
		{
			foreach($this->urlParametersForAppliedFilters as $key=>$value)
			{
				//prepare filter string for appending to filtervalues cookie string
				$keywiseFilterValueString = '';
				foreach($value as $v)
				{
					$str = $key."[]=".$v;
					if(strpos($cookieVal['filterValues'],$str) === FALSE){ // check it isn't already present
						$keywiseFilterValueString .= ($keywiseFilterValueString == '' || $str == '' ? '':"&").$str;
						// assuming it wouldn't be in selection order either
						$cookieVal['filterSelectedOrder'][$key][$v] = 1;
					}
				}
				$cookieVal['filterValues'] .= ($cookieVal['filterValues'] == '' || $keywiseFilterValueString == ''? '':"&").$keywiseFilterValueString;
			}
	    }
	    
	    // encode selection order back
	    $cookieVal['filterSelectedOrder'] = json_encode($cookieVal['filterSelectedOrder']);
	    // must set request's filterSelectionOrder
	    $this->filterSelectionOrder = $cookieVal['filterSelectedOrder'];
	    // encode & set cookie
	    $cookieVal = json_encode($cookieVal);
	    $res = setcookie($filterPageKey,$cookieVal,time()+1800,'/',COOKIEDOMAIN);
	    $_COOKIE[$filterPageKey] = $cookieVal;
    }
    /*
     * function to check if current page is being refered through one of its paginated links
     */
    private function _checkRefererWithUrlParams()
    {
	$referer = $_SERVER['HTTP_REFERER'];
	$currentUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	$str = explode('?',$referer);
	$refererMainUrl = $str[0];
	$refererQueryString = $str[1];
	$str = explode('?',$currentUrl);
	$currentMainUrl = $str[0];
	$currentQueryString = $str[1];
	if($refererQueryString == $currentQueryString && strpos($refererMainUrl,$currentMainUrl) !== FALSE)
	{ // navigated by refresh / via paginated url
	    return false;
	}
	else{
	    return true;
	}
    }

	/**
	* Purpose : Method to get sorting criteria applied by the user
	* Author  : Nikita Jain
	*/
	public function getSortingCriteria()
	{
		if(!empty($this->sortingCriteria)){
			return $this->sortingCriteria;
		}
		$ci = &get_instance();
		$val = $ci->input->get("sortby");
		//set the sortby cookie from url parameters here!
		if(!$this->isAJAXCall() && !empty($val)){	// if this is a page load, and url has sortby query params, set the "sortby" cookie.
			$this->_getSortingCriteriaFromURLParams($val,$ci);
		}
		unset($ci);
		if(isset($_COOKIE["sortby-".$this->getPageKey()])){
			$sortingCriteria = $_COOKIE["sortby-".$this->getPageKey()];
		}
		
		$sortingCriteria = trim($sortingCriteria);
		
		if($sortingCriteria != 'none') {
			$explode = explode("_" , $sortingCriteria);
			if(count($explode) > 1){
				$sortBy = $explode[0];
				$order 	= $explode[1];
				$exam	= $explode[2];
			}
		} else {
			$sortBy = 'none';
		}
		
		switch($sortBy){
			case 'fees':
				$sortingCriteria = array('sortBy' => 'fees', 'params' => array('order' => $order));
				break;
				
			case 'viewCount':
				$sortingCriteria = array('sortBy' => 'viewCount', 'params' => array('order' => $order));
				break;
				
			case 'exam':
				$sortingCriteria = array('sortBy' => 'exam', 'params' => array('order' => $order,'exam'=>$exam));
				break;
				
			case 'none':
				$sortingCriteria = array('sortBy' => 'none', 'params' => array('order' => 'none'));
				break;

			default:
				$sortingCriteria = array('sortBy' => 'viewCount', 'params' => array('order' => $order));
				break;
		}
		if(!empty($sortingCriteria)) {
			$this->sortingCriteria = $sortingCriteria;
			return $sortingCriteria;
		}
	}
	
	public function getPageNumberForPagination()
	{
		if($this->pageNumber == "") {
			$this->pageNumber = 1;
		}
		return $this->pageNumber;
	}
	
	public function getSortOrder()
	{
		return $this->sortOrder;
	}
	
	/*
	 * Getters
	 */
	
	public function getCategoryId()
	{
		return $this->categoryId;
	}
	
	public function getSubCategoryId()
	{
		return $this->subCategoryId;
	}
	
	
	public function getLDBCourseId()
	{
		return $this->LDBCourseId;
	}
	
	
	public function getCityId()
	{
		return $this->cityId;
	}
	
	public function getStateId()
	{
		return $this->stateId;
	}
	
	public function getCountryId()
	{
		return $this->countryId;
	}
	
	public function isAllCountryPage()
	{
		return in_array(1, $this->countryId);
	}
	
	public function getSnippetsPerPage()
	{
		//return $this->_setNoOfResults();
            return $this->resultsPerPage;
	}

	private function _setNoOfResults()
	{
		return 20;
	}

	public function isAJAXCall()
	{
		$CI = &get_instance();
		return $CI->input->is_ajax_request();
	}
	
	public function isSortAJAXCall()
	{
		return ($_REQUEST['SORTAJAX'] == 1);
	}
	
	public function isSolrFilterAjaxCall()
	{
		$CI = &get_instance();
		return ($CI->input->post('filterAJAX') == 1);
	}
	public function getSeoInfoForCountryPage($countryId, $pageNumber = 1) {
	    $seoData = $this->_getSEODataForCountryPage($countryId, $pageNumber);
	    return $seoData;
	}
	
	public function getURLForCountryPage($countryId, $pageNumber = 1)
	{
		$urlData = $this->_getSEODataForCountryPage($countryId, $pageNumber);
		
		if($urlData)
		{
			return $urlData['url'];
		}
	}
	
	
	private function _getSEODataForCountryPage($countryId, $pageNumber)
	{
		$ci = & get_instance();
		
		$ci->load->builder('LocationBuilder','location');
		$locationBuilder	 	= new LocationBuilder;
		$locationRepository 	= $locationBuilder->getLocationRepository();
		global $countryPageUrlInfo;
		
		if($countryId == "") {
		    return false;
		}
		
		if($countryId == 1) { // All country page..
		    $countryNameFormatted = "abroad";
		    $countryName = "abroad";
			$url = str_replace('{pageIdentifier}', 'countrypage', $countryPageUrlInfo['GENERAL']['url']);
			$unwantedCharArray = array(' ','/','(',')',',');
		} else {
		    $countryInfo 	= $locationRepository->getAbroadCountryByIds(array($countryId));
		    $countryObj 	= $countryInfo[$countryId];
		    $countryName 	= "";
		    if($countryObj)
			{
				$countryName 	= $countryObj->getName();
				$countryNameFormatted = str_replace(" ", "-", $countryName);
				$url = $countryPageUrlInfo['GENERAL']['countryPageUrl'];
				$unwantedCharArray = array(' ','(',')',',');
			}
			else{
				return false;
			}
		}


		$pageTitle = $countryPageUrlInfo['GENERAL']['title'];
		$pageDescription = $countryPageUrlInfo['GENERAL']['description'];		
		
		$url = str_replace("{location}", $countryNameFormatted, $url);
		$pageTitle = str_replace('{location}', $countryName, $pageTitle);
		$pageDescription = str_replace('{location}', $countryName, $pageDescription);


		/*
		 * Avoid unwanted chars from url..
		 */
		$url = str_replace($unwantedCharArray,'-',$url);
		$url = preg_replace('!-+!', '-', $url);
		$url = strtolower(trim($url,'-'));
		$domainPrefix = SHIKSHA_STUDYABROAD_HOME;		
		$url = $domainPrefix.'/'.$url;

        $canonical = $url;
		if($pageNumber > 1) {
            $url = $url."-".$pageNumber;
		    $pageTitle = 'Page '.$pageNumber.' - '.$pageTitle;
		    $pageDescription = 'Page '.$pageNumber.' - '.$pageDescription;
		}
		$catPageSeoData['canonical'] = $canonical;
		$catPageSeoData['url'] = $url;
		$catPageSeoData['title'] = $pageTitle;
		$catPageSeoData['description'] = $pageDescription;
		
		return $catPageSeoData;
	}
	
	/*
	 * Purpose: Method to create the page key of the abroad category page
	 * Author : Romil Goel
	 * To Do  : None
	 * Note   : Structure of page key
	 * 	   SACATPAGE-categoryId-subCategoryId-ldbCourseId-courseLevel-cityId-stateId-countryId(colonSeperated)
	*/
	public function getPageKey()
	{
	    $categoryId 	= empty($this->categoryId) 	? 1 	: $this->categoryId;
	    $subCategoryId 	= empty($this->subCategoryId) 	? 1 	: $this->subCategoryId;
	    $LDBCourseId 	= empty($this->LDBCourseId) 	? 1 	: $this->LDBCourseId  ;
	    $courseLevel 	= empty($this->courseLevel) 	? 'none': $this->courseLevel;
	    $cityId 		= empty($this->cityId) 		? 1 	: $this->cityId;
	    $stateId 		= empty($this->stateId) 	? 1 	: $this->stateId;
	    $countryId 		= empty($this->countryId) 	? 1 	: $this->countryId;


	    $countryId = implode(":",$countryId);
	    /* page key structure
	     SACATPAGE-categoryId-subCategoryId-ldbCourseId-courseLevel-cityId-stateId-countryId(colonSeperated)
	    */
	    $pageKey = "SACATPAGE-".$categoryId."-".$subCategoryId."-".$LDBCourseId."-".$courseLevel."-".$cityId."-".$stateId."-".$countryId;
	
	    // show stopper : certificate - diploma category pages filters were not working without this
	    $pageKey 	= str_replace(" ","_",$pageKey);
	    $pageKey 	= strtoupper($pageKey);
	    
	    return $pageKey;
	}
	
	public function getFilterSelectionOrder()
	{
	    return $this->filterSelectionOrder;
	}
	public function isExamAcceptingPage()
    {
	return ($this->acceptedExamName!=''?TRUE:FALSE);
    }
    public function getAcceptedExamName()
    {
	return $this->acceptedExamName;
    }

    public function getAcceptedExamId()
    {
	return $this->acceptedExamId;
    }

    public function getUrlStringForExamAcceptedPage()
    {
	return $this->urlStringForExamAcceptedPage;
    }
    
    public function getUrlParametersForAppliedFilters()
    {
	return $this->urlParametersForAppliedFilters;
    }
    
    public function getQueryStringFromUrlParameters()
    {
	return $this->queryStringFromUrlParameters;
    }
    public function includeSnapshotCourse(){
        return $this->includeSnapshotCourse;
    }
    public function setBTechSeoData($ldbCourseId,$categoryId ,$subCategoryId)
    {
		global $ldbCourseAndSubCategoryPages;
		if($categoryId == CATEGORY_ENGINEERING)   /*Bachelors of Engineering*/
		{
			 /*for all subcategories of be btech*/
			$seoData = $ldbCourseAndSubCategoryPages['LDBCOURSE'][$ldbCourseId];
			/*for other specializations category with boardId 268*/
			if($subCategoryId==268)  /*resetting the description and title to handle other specialization case*/
			{
			$seoData['description']  = str_replace('{subcategory}','Other Engineering Specializations', $seoData['description']);
			$seoData['title'] 	 = str_replace('{subcategory}','Other Engineering Specializations', $seoData['title']);
			}
		}
		else if($categoryId == CATEGORY_COMPUTERS)   /*Bachelors of Computers*/
		{
			$seoData = $ldbCourseAndSubCategoryPages['LDBCOURSE'][$ldbCourseId];
			
			if($subCategoryId == 275)  /* Resetting the description and title to handle IT and networking subcategory with boardId 275*/
			{
			$seoData['description'] = str_replace('{subcategory}','IT and Networking', $seoData['description']);
			$seoData['description'] = str_replace('Colleges','Courses', $seoData['description']);
			$seoData['title']	= str_replace('{subcategory}','IT and Networking', $seoData['title']);
			$seoData['title']	= str_replace('Colleges','Courses', $seoData['title']);
			}
		}
		
		return $seoData;
    }
	/*
	 * get seo info for new exam category pages
	 * @param: $pageNumber [this is used while generating the paginated links for the same page, then by passing thepage number we can have the url]
	 */ 
	private function _getSEODataForExamAcceptedCategoryPage($pageNumber=0, $callForURL = false)
	{
		$seoData = array();
		$pageNumberString = $this->pageNumber>1?"Page ".$this->pageNumber." - ":"";
		if($this->createExamCategoryPageUrlFlag){
			$seoData['url'] = $this->_createExamCategoryPageUrlFlag($pageNumber);
		}
		else{
			$seoData['url'] = SHIKSHA_STUDYABROAD_HOME.$_SERVER['REQUEST_URI'];
		}
		$courseName = implode(' ',explode('-',$this->examAcceptingCourseName));
		if($this->LDBCourseId>1 && $this->LDBCourseId!= DESIRED_COURSE_BTECH) // we already formatted be-btech as BE/Btech
		{	
			$courseName = strtoupper($courseName);
		}
		else
		{
			$courseName = ucwords($courseName); // bachelors of business
		}
		$examName = strtoupper($this->examName);
		if(count($this->examScore)==2) // score based url
		{
			$examScore = implode(' to ',$this->examScore );
			//title
			$seoData['title'] = $pageNumberString.$courseName." Colleges for ".$examName." score ".$examScore." - Study Abroad";
			// h1 title
			$seoData['h1Title'] = $courseName." Colleges for ".$examName." score ".$examScore;
			//description
			$seoData['description'] = $pageNumberString."See ".$courseName." colleges and universities for ".$examName." score ".$examScore." with their fees, specializations, eligibility, scholarship, and more details.";
		}
		else
		{
			//title
			$seoData['title'] = $pageNumberString.$courseName." Colleges Accepting ".$examName." scores - Study Abroad";
			// h1 title
			$seoData['h1Title'] = $courseName." Colleges Accepting ".$examName." scores";
			//description
			$seoData['description'] = $pageNumberString."See ".$courseName." colleges and universities accepting ".$examName." scores with their fees, specializations, eligibility, scholarship, and more details.";
		}
		if($this->pageNumber>1)
		{
			$pos = strrpos($seoData['url'],'-'.$this->pageNumber,-1);
			$seoData['ajaxUrl'] = substr_replace($seoData['url'],'',$pos,strlen('-'.$this->pageNumber));
		}
		else{
			$seoData['ajaxUrl'] = $seoData['url'];
		}
		 //var_dump($this->pageNumber);
		if($callForURL && $pageNumber>0 && $this->pageNumber>0) // page number passed to get url for a particular page
		{
			//$seoData['url'] = str_replace('-'.$this->pageNumber,'',$seoData['url']);
			if($this->pageNumber>1){
				$pos = strrpos($seoData['url'],'-'.$this->pageNumber,-1);
                $seoData['url'] = substr_replace($seoData['url'],'',$pos,strlen('-'.$this->pageNumber));
			}

			if($pageNumber > 1){ // no need to append -1
				$urlArr = explode("?", $seoData['url']);
				if(count($urlArr)>1){
				$seoData['url'] = $urlArr[0]."-".$pageNumber."?".$urlArr[1];
				}else{
				$seoData['url'] = $urlArr[0]."-".$pageNumber;	
				}
			}
		}
		return $seoData;
	}
	/*
	 * check if it is an exam based category page
	 */
    public function isExamCategoryPage()
	{
		return $this->examId>0?true:false;
	}
	/*
	 * function to create url for exam category page
	 */
	private function _createExamCategoryPageUrlFlag($pageNumber)
	{
		global $examAcceptedPagePattern;
		if($this->examScore)
		{
			$urlPattern = $examAcceptedPagePattern['withScore'];
			$urlPattern = str_replace("{lower-limit}",intval($this->examScore[0]),$urlPattern);
			$urlPattern = str_replace("{upper-limit}",intval($this->examScore[1]),$urlPattern);
		}
		else
		{
			$urlPattern = $examAcceptedPagePattern['withoutScore'];
		}
		$urlPattern = str_replace("{course}",strtolower(seo_url_lowercase($this->examAcceptingCourseName)),$urlPattern);
		$computedUrl = str_replace("{exam}",strtolower(seo_url_lowercase($this->examName)),$urlPattern);
		if($params['pageNumber']>1) // append page number, if any
		{
			$computedUrl .= "-".$pageNumber;
		}
		$computedUrl = SHIKSHA_STUDYABROAD_HOME.$computedUrl;
		return $computedUrl;
	}

	private function _getSortingCriteriaFromURLParams($val,$ci){
		switch($val){
			case 'popularity':
				setcookie("sortby-".$this->getPageKey(),'viewCount_DESC',time()+1800,'/',COOKIEDOMAIN);
				$_COOKIE["sortby-".$this->getPageKey()] = 'viewCount_DESC';
				break;
			case 'increasingfees':
				setcookie("sortby-".$this->getPageKey(),'fees_ASC',time()+1800,'/',COOKIEDOMAIN);
				$_COOKIE["sortby-".$this->getPageKey()] = 'fees_ASC';
				break;
			case 'decreasingfees':
				setcookie("sortby-".$this->getPageKey(),'fees_DESC',time()+1800,'/',COOKIEDOMAIN);
				$_COOKIE["sortby-".$this->getPageKey()] = 'fees_DESC';
				break;
			case 'increasingexam':
				$exam = $this->_getRelevantExamForSorting($ci);
				setcookie("sortby-".$this->getPageKey(),'exam_ASC_'.strtoupper($exam),time()+1800,'/',COOKIEDOMAIN);
				$_COOKIE["sortby-".$this->getPageKey()] = 'exam_ASC_'.strtoupper($exam);
				break;
			case 'decreasingexam':
				$exam = $this->_getRelevantExamForSorting($ci);
				setcookie("sortby-".$this->getPageKey(),'exam_DESC_'.strtoupper($exam),time()+1800,'/',COOKIEDOMAIN);
				$_COOKIE["sortby-".$this->getPageKey()] = 'exam_DESC_'.strtoupper($exam);
				break;
			case 'sponsored':
			default: 	
				setcookie("sortby-".$this->getPageKey(),'none',time()+1800,'/',COOKIEDOMAIN);
				$_COOKIE["sortby-".$this->getPageKey()] = 'none';
				break;
		}
	}

	private function _getRelevantExamForSorting($ci){
		$examName = '';
		if($this->isExamCategoryPage()){
			return $this->examName;
		}
		if($this->isExamAcceptingPage()){
			$examName = strtoupper($this->acceptedExamName);
			return $examName;
		}
		//If it is neither of the above, then we must show the "top" exam in ascending or descending order.
		$lib = $ci->load->library('categoryList/AbroadCategoryPageLib');
		$cachedFilters = $lib->getFiltersFromCache($this->getPageKey());
		$examName = strtoupper($cachedFilters['exams'][0]['exam']);
		return $examName;
	}
	public function isCertDiplomaPage()
	{
		global $certificateDiplomaLevels;
		if(in_array($this->courseLevel,$certificateDiplomaLevels) ||
		   strtolower($this->courseLevel) == "certificate - diploma"
		  )
		{
			return true;
		}
		else{
			return false;
		}
	}
	public function useSolrToBuildCategoryPage()
	{
		//!isMobileRequest()
		return ($this->buildCategoryPageViaSolr);
	}
	
	public function getFlagToGetCertDiplomaResults()
	{
		return $this->flagToGetCertDiplomaResults;
	}
	public function setFlagToGetCertDiplomaResults($flag)
	{
		$this->flagToGetCertDiplomaResults = $flag;
	}
}
