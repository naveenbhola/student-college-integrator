<?php
class AbroadCategoryPageLib {

	private $CI;
	private $abroadCategoryPageModelObj;
	private $abroadListingCacheLib;
	private $europeCountryIds;
	
	function __construct() {
		$this->CI =& get_instance();
		$this->_setDependecies();
		$this->europeCountryIds = array();
	}
	
	function _setDependecies()
    {
        $this->CI->load->model('categoryList/abroadcategorypagemodel');
        $this->abroadCategoryPageModelObj  = new abroadcategorypagemodel();
	$this->abroadListingCacheLib = $this->CI->load->library('listing/cache/AbroadListingCache');
		
        //$this->CI->load->builder('CategoryBuilder','categoryList');
        //$categoryBuilder = new CategoryBuilder;
        //$this->categoryRepository = $categoryBuilder->getCategoryRepository();
    }
	
	function getSubCatsForParentCatAndCourseLevel($parentCatId, $courseLevel) {
		return $this->abroadCategoryPageModelObj->getSubCatsForParentCatAndCourseLevel($parentCatId, $courseLevel);
	}
        
        function getSubCatsForParentCatOnly($parentCatId) {
		return $this->abroadCategoryPageModelObj->getSubCatsForParentCatOnly($parentCatId);
	}
	
	function getSubCatsForDesiredCourses($ldbCourseId) {
		$this->_setDependecies();
		$data = $this->abroadListingCacheLib->getSubCatsForDesiredCourses($ldbCourseId);
		if(empty($data)){
			$data = $this->abroadCategoryPageModelObj->getSubCatsForDesiredCourses($ldbCourseId);
			$this->abroadListingCacheLib->storeSubCatsForDesiredCourses($ldbCourseId, $data);
		}
		return $data;
	}
	
	function getCountriesForParentCatAndCourseLevel($parentCatId, $courseLevel, $subcatId) {
		return $this->abroadCategoryPageModelObj->getCountriesForParentCatAndCourseLevel($parentCatId, $courseLevel, $subcatId);
	}
	
	function getCountriesForDesiredCourses($ldbCourseId, $subcatId) {
		return $this->abroadCategoryPageModelObj->getCountriesForDesiredCourses($ldbCourseId, $subcatId);
	}
	
	function getAbroadCountryPageData($paginationArr, $countryId = 0){
		$result = $this->getCountryPageCourseDataForUniversities($countryId);
		$LimitOffset 	= $paginationArr["limitOffset"];
		$LimitRowCount 	= $paginationArr["limitRowCount"];
		
		$resultArr['totalCount'] = $result['totalCount'];
		
		$abroadListingCommonLib = $this->CI->load->library('listing/abroadListingCommonLib');
		$universityViewCountArray = $abroadListingCommonLib->getViewCountForListingsByDays($result['universityIds'],'university',21);
		arsort($universityViewCountArray);

		$universityViewCountArray = array_slice($universityViewCountArray,$LimitOffset,$LimitRowCount,true);
	
		$universitiesAfterPagination = array();
		foreach($universityViewCountArray as $key=>$value){
			$universitiesAfterPagination[$key] = $result['result'][$key];
		}
		unset($result['result']);
		$resultArr["result"] = $universitiesAfterPagination;		
		
	
		return $resultArr;
	}
    function getAbroadCountryPageDataFromSolr($paginationArr, $countryId = 0){
        $solrRequestData['countryId'] = $countryId;
        $solrRequestData['LimitOffset'] = $paginationArr["limitOffset"];
        $solrRequestData['LimitRowCount'] = $paginationArr["limitRowCount"];
        $CI =& get_instance();
        $autoSuggestorSolrClient = $CI->load->library('SASearch/autoSuggestorSolrClient');
        $resultArr = $autoSuggestorSolrClient->getUnivGroupedCourseListFromSolr($solrRequestData);
        //_p($resultArr);die();
        $finalResult = array();
        $tempResult = array();
        $universityIds = array();
        $courselist = array();
        foreach ($resultArr['groups'] as $key=>$value){
            $universityId = $value['groupValue'];
            array_push($universityIds,$universityId);
            $tempResult[$universityId]['university_id'] = $universityId;
            $tempResult[$universityId]['courseCount'] = $value['doclist']['numFound'];
            foreach($value['doclist']['docs'] as $key1=>$value1){
                if(empty($tempResult[$universityId]['courseList'])) {
                    $tempResult[$universityId]['courseList'] = $tempResult[$universityId]['courseList'] . '' . $value1['saCourseId'];
                }
                else{
                    $tempResult[$universityId]['courseList'] = $tempResult[$universityId]['courseList'] . ',' . $value1['saCourseId'];
                }
                $courselist[$value1['saCourseId']] = $universityId;
            }
        }
        $finalResult['courselist'] = $courselist;
        $finalResult['result'] = $tempResult;
        $finalResult['universityIds'] = $universityIds;
        $finalResult['totalCount'] = $resultArr['ngroups'];
        //_p($finalResult);die();
        return $finalResult;
    }

	function getCoursesCountOfUniversity($countryId = 0){
		return $this->abroadCategoryPageModelObj->getCoursesCountOfUniversity($countryId);
	}
	function getAllCoursesForUniversities($universityIds = array()){
		return $this->abroadCategoryPageModelObj->getAllCoursesForUniversities($universityIds);
	}
	function getCountriesHavingUniversities(){
		return $this->abroadCategoryPageModelObj->getCountriesHavingUniversities();
	}

	//function to fetch popular course data for quick links for country all university page
    function getPopularCoursesForQuickLinks($countryId)
    {
        $CI =& get_instance();
        $categoryPageRequest = $CI->load->library('categoryList/AbroadCategoryPageRequest');
        $abroadCommonLib = $CI->load->library('listingPosting/AbroadCommonLib');
        $popularCourses = $abroadCommonLib->getAbroadMainLDBCourses();
        // get Urls for category pages of MBA ,MS, Be-Btech in given country
        $popularCoursesData = array();
        foreach ($popularCourses as $popularCourse) {
            $categoryPageRequest->setData(array('countryId' => array($countryId), 'LDBCourseId' => $popularCourse['SpecializationId']));
            $popularCoursesData[$popularCourse['SpecializationId']] = array(
                'id' => $popularCourse['SpecializationId'],
                'name' => $popularCourse['CourseName'],
                'url' => $categoryPageRequest->getURL()
            );
        }
        $popularCourseIds = array();
        foreach ($popularCoursesData as $id => $course) {
            $popularCourseIds[] = $id;
        }
        global $studyAbroadAllDesiredCourses;
        $universityCountsForPopularCourses = $this->getPopularCourseCountForCountry($countryId, $studyAbroadAllDesiredCourses);
        foreach ($popularCoursesData as $id => $popularCourse) {
            $popularCoursesData[$id]['universityCount'] = $universityCountsForPopularCourses[$id];
        }
        return $popularCoursesData;
    }
	/*  Reason :For getting BMS inventory countries to display banner on country page
	 *  Params : 
	 *  author : Abhay
	 */
	function getCountriesForBMSInventoriesForCountryPageBanner($categoryPageBuilder, $request, $abroadCountryList){
		$countryArray = $request->getCountryId();
			
		if(count($countryArray) == 1 && $countryArray == 1) { // All country case..
			// _p($abroadCountryList); die; 
			foreach($abroadCountryList as $countryObj) {
				if($countryObj->getId() != 1) {
				$countryArrayNew[] = $countryObj->getId();
				}
			}
			$countriesForBMSInventory = $categoryPageBuilder->getProcessedCountriesForCountryPage($countryArrayNew);
		} else if(count($countryArray) > 1) {
			$countriesForBMSInventory = $categoryPageBuilder->getProcessedCountriesForCountryPage($countryArray);
		} else {
			$countriesForBMSInventory = $countryArray;
		}
		return $countriesForBMSInventory;
	}
	
	public function getPopularCourseCountForCountry($countryId, $ldbCourseIds){
		return $this->abroadCategoryPageModelObj->getPopularCourseCountForCountry($countryId, $ldbCourseIds);
	}
	
	public function getLDBCourseCountsForCountries($countryIdArray,$ldbCourseIds,$examId = ''){
		return $this->abroadCategoryPageModelObj->getLDBCourseCountsForCountries($countryIdArray,$ldbCourseIds,$examId);
	}

	/*
	 * this function reads exam name from url string separating it from rest of the string such that the remaining string can be processed just as it was processed when exam name was not in the url.
	 * @params : $urlString: url string before the identifier & entity id e.g. "be-btech-in-usa-accepting-gre"
	 */
	public function _getExamNameFromUrlString(&$urlString){
		// if string is empty.. something wrong.. return false
		if($urlString == '')
		{
		    return false;
		}
		// remove "-accepting-<examname>" from the string
		$processedStringArray = explode("-accepting-", $urlString);
		$urlString = $processedStringArray[0];
		//_p($processedStringArray);
		// return examname
		return $processedStringArray[1];
	}
	/*
	 * function to get filters values : firstYearFees, specialization, moreOptions from URL parameters
	 * returns array of  3filter values that are further array(being multiple values)
	 * input: abroadcategorypagerequest to check if page is of subcategory (then no need for specialization)
	 * - SRB
	 */
	public function processUrlParametersToGetFilters($request) {
		$UrlParametersForFilters = array();
		// get values from request
		$UrlParametersForFilters['firstYearFees'] 	= ($_GET['1styearfees'] == ''? array():explode('_',$_GET['1styearfees']));
		$UrlParametersForFilters['specialization'] 	= ($_GET['specialization'] == ''? array():explode('_',$_GET['specialization']));
		$UrlParametersForFilters['moreOptions'] 	= ($_GET['moreoptions'] == ''? array():explode('_',$_GET['moreoptions']));
		//get a set of valid filter values..
		$validFeeRanges   = $GLOBALS['ABROAD_CP_URL_PARAMS_TO_FILTER_MAPPINGS']['FEE_URL_PARAM_TO_RANGE_KEY_MAPPING'];
		$validMoreOptions = $GLOBALS['ABROAD_CP_URL_PARAMS_TO_FILTER_MAPPINGS']['MORE_OPTIONS_URL_PARAM_TO_KEY_MAPPING'];
		$validSpecializations = array();
		if(!$request->isLDBCourseSubCategoryPage() && !$request->isCategorySubCategoryCourseLevelPage())
		{
			if($request->isLDBCoursePage()){ // ldb course country page
				$subCatArray = $this->getSubCatsForDesiredCourses($request->getLDBCourseId());
			}
			else if ($request->isCategoryCourseLevelPage()){
				$subCatArray = $this->getSubCatsForParentCatAndCourseLevel($request->getCategoryId(), $request->getCourseLevel());
			}
			$validSpecializations = array_map(function ($v){ return $v['sub_category_id']; }, $subCatArray);
		}

		$fees = $specialization = $moreoptions =array();
		
		//get respective filter values to be set in the filter cookie
		foreach($UrlParametersForFilters as $key=>$UrlParam)
		{
			foreach($UrlParam as $k=>$filterVal){
				if($key == 'firstYearFees'){
					$fees[] = $validFeeRanges[$filterVal];
				}
				else if($key == 'specialization'){
					if(in_array($filterVal,$validSpecializations))
					$specialization[] = $filterVal;
				}
				else{
					$moreoptions[] = $validMoreOptions[$filterVal];
				}
			}
		}
		
		$filtersFromUrlParams = array();// note that the key names here are those used in filter cookie
		if(!empty($fees))
		{
			$filtersFromUrlParams['fee'] = array_filter($fees);
		}
		if(!empty($specialization))
		{
			$filtersFromUrlParams['course'] = array_filter($specialization);
		}
		if(!empty($moreoptions))
		{
			$filtersFromUrlParams['moreopt'] = array_filter($moreoptions);
		}
		
		if($request->isLDBCourseSubCategoryPage() || $request->isCategorySubCategoryCourseLevelPage()){
			unset($filtersFromUrlParams['course']);
		}
		// also prepare valid query string from the filters processed out of orginal url params
		$qryString = $this->_prepareQueryStringFromUrlParams($filtersFromUrlParams);
		return array( 'filtersFromUrlParams' => $filtersFromUrlParams,
			      'validQueryString' => $qryString) ;
	}
	
	/*
	 * function to prepare Query String From Url Params
	 * input params: filters generated from Url Params
	 */
	private function _prepareQueryStringFromUrlParams($filtersFromUrlParams = array())
	{
		if(count($filtersFromUrlParams) == 0)
		{
			return '';
		}	
		$qryString = "";
		foreach($filtersFromUrlParams as $key=>$value)
		{
		    $qryString .= ($qryString == ''? '':'&').$GLOBALS['ABROAD_CP_URL_PARAMS_TO_FILTER_MAPPINGS']['FILTER_NAME_TO_URL_PARAM_NAME_MAPPING'][$key].'=';
		    $paramValueString = "";
		    foreach($value as $k=>$v)
		    {
			switch($key){
			    case 'fee' :
				$keys = array_keys($GLOBALS['ABROAD_CP_URL_PARAMS_TO_FILTER_MAPPINGS']['FEE_URL_PARAM_TO_RANGE_KEY_MAPPING'],$v);
				break;
			    case 'course' : 
				$keys = array($v); // in case of specialization, we have direct ids hence no mapping required
				break;
			    case 'moreopt' :
				$keys = array_keys($GLOBALS['ABROAD_CP_URL_PARAMS_TO_FILTER_MAPPINGS']['MORE_OPTIONS_URL_PARAM_TO_KEY_MAPPING'],$v);
				break;
			}
			$paramValueString .= ($paramValueString == ''?'':'_').$keys[0];
		    }
		    $qryString .= $paramValueString;
		}
		$sortBy = $_GET['sortby'];
		if(!empty($sortBy)){
			$qryString.="&sortby=".$sortBy;
		}
		return $qryString;
	}
        
    public function sortEligibilityExam(AbroadCategoryPageRequest $abroadCategoryPageRequest, $courseObjects = array(),$examlist=array()){
        
        if(empty($courseObjects) && empty($examlist)){
            return array();
        }
		$examOrder   = array();
		$ldbCourseId = $abroadCategoryPageRequest->getLDBCourseId();
		$categoryId  = $abroadCategoryPageRequest->getCategoryId();
		$courseLevel = strtolower($abroadCategoryPageRequest->getCourseLevel());

        $this->CI->config->load('studyAbroadListingConfig');
        $allExamOrder = $this->CI->config->item("ENT_COURSE_WISE_EXAM_ORDER");

        if(isset($ldbCourseId) && $ldbCourseId > 0) {
            $examOrder = $allExamOrder[$ldbCourseId];
        }
        else if($categoryId == CATEGORY_BUSINESS) {
            $examOrder = $allExamOrder[DESIRED_COURSE_MBA];
        }
        else if((in_array($categoryId, array(CATEGORY_ENGINEERING,CATEGORY_COMPUTERS,CATEGORY_SCIENCE)) && $courseLevel != 'bachelors')) {
            $examOrder = $allExamOrder[DESIRED_COURSE_MS];
        }
        else if((in_array($categoryId, array(CATEGORY_ENGINEERING,CATEGORY_COMPUTERS,CATEGORY_SCIENCE)) && $courseLevel == 'bachelors')) {
            $examOrder = $allExamOrder[DESIRED_COURSE_BTECH];
        }
        else {
            $examOrder = $allExamOrder['OTHERS'];
        }
        
	    //If we are reciveing the examlist then sort exam
        if(count($examlist) > 0) {
			$exams = $examlist;
			$tempArr = array();
			foreach($examlist as $key=>$value){
				$tempArr[$value] = $key;
			}
			usort($exams, function ($a,$b) use($examOrder){
                if($examOrder[$a] > $examOrder[$b]){
                    return 1;
                }else{
                    return -1;
                }
            });
            $finalArr = array();
			foreach($exams as $key=>$value){
				$finalArr[$tempArr[$value]] = $value;
			}
			return $finalArr;
		
	    }
	    else { //when we are recieving the courseobject 
            foreach($courseObjects as &$courseObj){
                $exams = $courseObj->getEligibilityExams();
                usort($exams, function ($a,$b) use($examOrder){
                    if($examOrder[$a->getName()] > $examOrder[$b->getName()]){
                        return 1;
                    }else{
                        return -1;
                    }
                });
                $courseObj->__set('exams',$exams);
            }
            return $courseObjects;
	    }
        
	}
		
	public function getCountryPageCourseDataForUniversities($countryIds,$queryData = array()){
		$data = $this->abroadCategoryPageModelObj->getCountryPageCourseDataForUniversities($countryIds , $queryData);
		$finalData = array();
		$universityCount = 0;
		$universityIds   = array();
		foreach($data as $row){
			if(empty($finalData[$row['university_id']])){
				$universityCount++;
				$finalData[$row['university_id']]['university_id'] = $row['university_id'];
				$finalData[$row['university_id']]['courseList'] = ''.$row['course_id'];
				$universityIds[] = $row['university_id'];
			}else{
				$finalData[$row['university_id']]['courseList'].= ','.$row['course_id'];
			}
			$finalArr['courselist'][$row['course_id']] = $row['university_id'];
		}
		$finalArr['result'] = $finalData;
		$finalArr['universityIds'] = $universityIds;
		$finalArr['totalCount'] = $universityCount;
		return $finalArr;
	}
    
	/*  This function checks the current page and prepares the data for Category Pages STATIC Exam Guide Widget also for old 301 redirected pages
    *   param : Category Page Request Object
    *   Logic followed : Only 2 exams shown
    *   MBA and MAsters Of Business : GMAT, IELTS (for all countries except for USA and Canada where we will show TOEFL)
    *   MS and Masters of Engineering, Computers, Science : GRE, IELTS (except for USA and Canada :TOEFL)
    *   BTech and Bachelors of Engineering, Computers, Science : SAT,IELTS (except for USA and Canada : TOEFL)
    *   Rest all courses and categories : IELTS,TOEFL 
    *   EXAM PAGE ID : 321 => GMAT, 324 => SAT, 320 => TOEFL, 323 => IELTS, 322 => GRE
    *	EXCEPTION COUNTRY IDs : CANADA => 8, USA=> 3
    */

     public function prepareExamGuideWidgetData($request,$categoryRepository)
    {
        
        $ldbCourseId = $request->getLDBCourseId();
        //_p($request);
		$id = $ldbCourseId;

		//to handle case of Bachelors of Science Page which is a not LDB Page

		if($ldbCourseId=="1")
		{

			$courseLevel = $request->getCourseLevel();
			$categoryId  = $request->getCategoryId();
			$subCategoryId = $request->getSubCategoryId();
			//var_dump($categoryId);
			//var_dump($subCategoryId);
			//die;
			//check if category page
			if($categoryId!=1 && $courseLevel=="bachelors" && $categoryId==242 || $categoryId==241)
			{
				$id=242;
			}

			else if($subCategoryId!=1)//check if subcategory page
			{
        		$subCategory = $categoryRepository->find($subCategoryId);
        		$category = $categoryRepository->find($subCategory->getParentId());
        		$categoryId = $category->getId();
        		//_p($category);
        		if($courseLevel=="bachelors" && $categoryId==242 || $categoryId==241)  //this is to check sub category level page
				{
					$id=242;
				}
			}
		}

        $countryIds   = $request->getCountryId();
     	$examPageIds = array();
     	//var_dump($countryIds);
		if(empty($countryIds)!=true)
		{
			if(array_search("8", $countryIds)!==false ||  array_search("3", $countryIds)!==false) 
			{
				$examPageIds['TOEFL'] = '320'; //TOEFL
			}
			else
			{
				$examPageIds['IELTS'] = '323'; 
			}

		}
		
        //var_dump($countryId);
       	// function calling this code is not in use; hence not removing these hard-coded ids
        switch ($id) 
        {
            //MBA
            case 1508:
            	//Exams : 321,323,320
            	$examPageIds['GMAT'] = '321';
                break;
            //MS
            case 1509:
                //Exams : 322,323,320
            	$examPageIds['GRE'] = '322';
            	break;

            //BE BTECH and Bachelors of Science
            case 1510:
            case 242:
                //Exams : 324,323,320
            	$examPageIds['SAT'] = '324';              
                break;
            //rest of category pages
            default:
            //Exams : 323,320
            	$examPageIds = array('TOEFL'=>'320','IELTS'=>'323');
                break;
        }
         //fetch image,exampage url,desc,
         //create section urls
        $examWidgetData = array();
        $sectionUrls    = array();

         $examPageIds 	 = array_slice($examPageIds, 0, 2); 
         $examWidgetData = $this->prepareExamWidgetContent($examPageIds);
         $sectionUrls 	 = $this->prepareUrls($examPageIds);

         $data = array();

     	$data = array_merge($examWidgetData,$sectionUrls);
    	 //_p($data);
         return $data;
    }

    public function prepareExamWidgetContent($examPageIds)
    {

    	 $abroadExamPageModel          = $this->CI->load->model('abroadExamPages/abroadexampagemodel');
    	 $examWidgetData 			   = $abroadExamPageModel->prepareCatPageExamWidgetContent($examPageIds);


   		 if($examWidgetData!= false)
   		 {
   		 	return $examWidgetData;
   		 }
    	 
    	 else{
    	  return false;	
    	 }

    }

    public function prepareUrls($examPageIds)
    {
       	$abroadExamPageCommonib   = $this->CI->load->library('abroadExamPages/AbroadExamPageCommonLib');
   		$sectionUrls		      = $abroadExamPageCommonib->prepareSectionUrls($examPageIds);

    	return $sectionUrls;
    }

    public function prepareExamWidgetTitle($abroadCategoryPageLib,$categoryPageRequest,$categoryRepository)
    {

    	//_p($abroadCategoryPageLib);
        //_p($categoryPageRequest);
        //_p($categoryRepository);
        //die;
    	 if($categoryPageRequest->isLDBCoursePage() || $categoryPageRequest->isLDBCourseSubCategoryPage())
        {            
            $this->CI->load->builder('LDBCourseBuilder','LDB');
            $LDBCourseBuilder = new LDBCourseBuilder;
            $ldbRepository    = $LDBCourseBuilder->getLDBCourseRepository();
            
            $ldbCourse = $ldbRepository->find($categoryPageRequest->getLDBCourseId());
            $ldbCourseName = $ldbCourse->getCourseName();
            
            $title = $ldbCourseName;
             /*In case of BE/BTECH pages we have revised heading tags*/
            if($ldbCourseName!='' && $ldbCourseName=="BE/Btech")
            {
                $title = "Bachelor of Engineering";
            }
        
        }
        elseif($categoryPageRequest->isCategoryCourseLevelPage())
        {
            $category = $categoryRepository->find($categoryPageRequest->getCategoryId());
            $categoryName = $category->getName();
            
            $courseLevel = ucwords($categoryPageRequest->getCourseLevel());
            
            if($courseLevel == "Phd")
            {
                $courseLevel = "PhD";
                $title = $courseLevel." in ".$categoryName;
            } else {
                $title = $courseLevel." of ".$categoryName;
            }
        }
        elseif($categoryPageRequest->isCategorySubCategoryCourseLevelPage())
        {            
            $subCategory = $categoryRepository->find($categoryPageRequest->getSubCategoryId());
            $subCategoryName = $subCategory->getName();
            
            $category = $categoryRepository->find($subCategory->getParentId());
            $categoryName = $category->getName();
            
            $courseLevel = ucwords($categoryPageRequest->getCourseLevel());
            
            if($courseLevel == "phd" ) {
                $courseLevel = "PhD";
                $title = $courseLevel." in ".$categoryName;
            }
            else
            {
                $title = $courseLevel." of ".$categoryName;
            }
        }
        return $title;
    }
	/*
	 * to set filter & sort by cookie based on exam, subcat, 
	 */
	public function prepareFilterWithSortCookie($pageKeyName,$data){
		// prepare filter cookie name
		$filterCookieName = 'FILTERS-'.$pageKeyName;
		$filterCookie = array();

		// & sort cookie name
		$sortCookieName = 'sortby-'.$pageKeyName;
        $sortCookie = "";
		
		// fill filter cookie values
        $filterCookieValues = array();
        $filterCookieSelectedOrder = array();
		$filterSelectionOrder = array(); // for individual filters
        $i = 0;
		// fees
		$filterCookieValues[] = 'fee[]='.$data['fees'];
		$filterSelectionOrder[$data['fees']] = ++$i;
		$filterCookieSelectedOrder['fee'] = $filterSelectionOrder;
		// exam
		$filterSelectionOrder = array();
		foreach($data['exam'] as $exam)
		{
			$filterCookieValues[] = 'exam[]='.$exam;
			$filterSelectionOrder[$exam] = ++$i;
		}
		if(count($filterSelectionOrder)>0){
			$filterCookieSelectedOrder['exam'] = $filterSelectionOrder;
		}
		// subcategory
		$filterSelectionOrder = array();
		foreach($data['subCatFilter'] as $subCategoryId){
                $filterCookieValues[] = 'course[]='.$subCategoryId;
                $filterSelectionOrder[$subCategoryId] = ++$i;
        }
		if(count($filterSelectionOrder)>0){
			$filterCookieSelectedOrder['course'] = $filterSelectionOrder;
		}
        //examScore Selection
		if($data['examsScore']!=''){
		$filterCookieValues[] = 'examsScore[]='.$data['examsScore'];
		}
		// fill sort cookie values
        $sortCookie = $data['sort'];
		//_p($sortCookie);
		// set filter cookie
        $filterCookieValues = implode('&', $filterCookieValues);
        $filterCookie['filterValues'] = $filterCookieValues;
        $filterCookie['filterSelectedOrder'] = json_encode($filterCookieSelectedOrder);
		//_p($filterCookie);
        $filterCookie = json_encode($filterCookie);
        setcookie($filterCookieName, $filterCookie, time()+1800, "/", COOKIEDOMAIN);
		// set sort cookie
        setcookie($sortCookieName, $sortCookie, time()+1800, "/", COOKIEDOMAIN);
        setcookie('homepageRedirect', 'true', time()+600, "/", COOKIEDOMAIN);
    }
	/*
	 * get exam order based on whether the category page is of
	 * a certain ldb course or belongs to a particular set of categories
	 */
	public function getExamOrderByCategory($requestData)
	{
        $abroadListingCommonLib = $this->CI->load->library('listing/abroadListingCommonLib');
        return $abroadListingCommonLib->getExamOrderByDesiredCourseAndCategory($requestData);
	}
        /**
         * 
         * @param type $requestData Array
         * @return type Array
         * This function returns order of exam given country and ldbCourse combination 
         */
        public function getExamOrderByCountryAndLDBCourse($requestData,$ldbCourseIds) {
                $this->CI->config->load('studyAbroadListingConfig');
                if(empty($this->europeCountryIds)){
                	$this->CI->load->builder('LocationBuilder', 'location');
	                $locationBuilder = new LocationBuilder;
	                $locationRepository = $locationBuilder->getLocationRepository();
	                $countryListOfEurope = $locationRepository->getCountriesByRegion(2);
	                foreach ($countryListOfEurope as $countryObject) {
	                    $this->europeCountryIds[] = (int)$countryObject->getId();
	                }
                }
                $examOrderByCountryAndLdbCourse = $this->CI->config->item("ENT_COURSE_AND_COUNTRY_WISE_EXAM_ORDER");
                $ldbCourseID = (int) $requestData['LDBCourseId'];
                $countryID = (int) $requestData['countryId'];
                if (!(in_array($ldbCourseID, $ldbCourseIds)) || $countryID <= 0) {
                    return array();
                }
                if (isset($examOrderByCountryAndLdbCourse[$countryID][$ldbCourseID])) {
                    return $examOrderByCountryAndLdbCourse[$countryID][$ldbCourseID];
                } else {
                    if (in_array($countryID, $this->europeCountryIds)) {
                        return $examOrderByCountryAndLdbCourse["europeexceptuk"][$ldbCourseID];
                    } else {
                        return $examOrderByCountryAndLdbCourse["others"][$ldbCourseID];
                    }
                }
    }

    /*
	 * function to get filters  from cache based on the page key
	 */
	public function getFiltersFromCache($pageKey="")
	{
		if($pageKey == "")
		{
			return false;
		}
		// get filters from cache
		$abroadCPCache = $this->CI->load->library('categoryList/cache/AbroadCategoryPageCache');
		$filtersApplicable = $abroadCPCache->getFilters($pageKey);
		return json_decode($filtersApplicable,true);
	}
	
	public function getCounsellorRatingComments($courseIds,$userId){
		$data = $this->abroadCategoryPageModelObj->getCounsellorRatingComments($courseIds,$userId);
		$result = array();
		foreach($data as $row){
			$result[$row['courseId']] = $row['message'];
		}
		return $result;
	}
	
	public function getPrevNextPageLinksForCategoryPage(& $displayData){
		
		if($displayData['totalTuplesOnPage'] > ($displayData['categoryPageRequest']->getPageNumberForPagination()*$displayData['categoryPageRequest']->getSnippetsPerPage()))
		{
			$displayData['relNext'] = $displayData['categoryPageRequest']->getURL(($displayData['categoryPageRequest']->getPageNumberForPagination())+1);
		}
		if($displayData['categoryPageRequest']->getPageNumberForPagination() != 1)
		{
			$displayData['relPrev'] = $displayData['categoryPageRequest']->getURL(($displayData['categoryPageRequest']->getPageNumberForPagination())-1);
		}
	}
	
	public function getPrevNextPageLinksForCountryPage($countryId,$paginationArr,$totalCount,$categoryPageRequest){
		
		$result = array();
		if($totalCount > ($paginationArr["limitOffset"]+$paginationArr["limitRowCount"]))
		{
			$data = $categoryPageRequest->getSeoInfoForCountryPage($countryId, ($paginationArr["pageNumber"]+1));
			$result['relNext'] = $data['url'];
		}
		if($paginationArr["pageNumber"] > 1)
		{
			$data = $categoryPageRequest->getSeoInfoForCountryPage($countryId, ($paginationArr["pageNumber"]-1));
			$result['relPrev'] = $data['url'];
		}
		return $result;
	}
	/*
	 * function to parse single category url string from new dir based catgeory page url
	 * @param: category page string, page identifier
	 */
	public function parseEntityStringFromCountryCategoryPageUrl($countryName, $categoryPageStr, $pageIdentifier)
	{
		$parsedData = array();
		$this->_setDependecies();
		
		// parse country name to get countryId
		$countryHomeLib = $this->CI->load->library('countryHome/CountryHomeLib');
		$country = $countryHomeLib->getCountry($countryName);
		if($country === false)
		{ 
			show_404_abroad();
		}
		else{
			$parsedData['countryId'] = array($country->getId());
			if($parsedData['countryId'] =="")
			{
				show_404_abroad();
			}
		}
		// parse page Identifier to separate page number from identifier
		$pageIdentifierArr = explode('-',$pageIdentifier);
		$pageIdentifier = $pageIdentifierArr[0];
		$parsedData['pageNumber'] = $pageIdentifierArr[1]>0?$pageIdentifierArr[1]:0;
		switch($pageIdentifier)
		{
			// desired course (e.g. mba-colleges)
			case 'dc':	$this->_parseUrlStringForLDBCourseCategoryPage($parsedData, $categoryPageStr);
							break;
			// category & level (e.g. bachelors-of-business-colleges)
			case 'cl':	$this->_parseUrlStringForLevelCategoryPage($parsedData, $categoryPageStr);
							break;
			// desired course & subcategory (e.g. mba-in-finance-colleges)
			case 'ds':	$this->_parseUrlStringForLDBCourseSubCategoryPage($parsedData, $categoryPageStr);
							break;
			// subcategory & level (e.g. bachelors-in-finance-courses)
			case 'sl':	$this->_parseUrlStringForLevelSubCategoryPage($parsedData, $categoryPageStr);
							break;
			default : return false;
		}
		return $parsedData;
	}
	/*
	 * function to parse url string & get values for ldb course cat page
	 */
	private function _parseUrlStringForLDBCourseCategoryPage(&$parsedData, $categoryPageStr){
		$desiredCourseName = str_replace('-colleges','',$categoryPageStr);
		// get desired course id by name
		$parsedData['LDBCourseId'] = $this->getDesiredCourseIdByName($desiredCourseName);
		if(!($parsedData['LDBCourseId']>0))
		{
			show_404_abroad();
		}
	}
	
	/*
	 * function to parse url string & get values for ldb course cat page
	 */
	private function _parseUrlStringForLevelCategoryPage(&$parsedData, $categoryPageStr){
		$categoryPageStr = str_replace('-colleges','',$categoryPageStr);
		// in case of phd & cert. diploma we use "in" instead of "or"
		if(strpos($categoryPageStr,"-in-")!== false)
		{
			$categoryPageStrArr = explode('-in-',$categoryPageStr);
		}
		else{
			$categoryPageStrArr = explode('-of-',$categoryPageStr);
		}
		// get level & category
		$parsedData['courseLevel'] = str_replace("-", " - ", $categoryPageStrArr[0]);
		$categoryPageStrArr[1] = seo_url_lowercase($categoryPageStrArr[1]);
		$parsedData['categoryId'] = $this->abroadCategoryPageModelObj->getCategoryIdByName(ucwords(str_replace('-',' ',$categoryPageStrArr[1])),true);
		if($parsedData['courseLevel'] =="" || !($parsedData['categoryId'] > 0) )
		{
			show_404_abroad();
		}
	}

	/*
	 * function to parse url string & get values for ldb course cat page
	 */
	private function _parseUrlStringForLDBCourseSubCategoryPage(&$parsedData, $categoryPageStr){
		$categoryPageStr = str_replace('-colleges','',$categoryPageStr);
		$categoryPageStrArr = explode('-in-',$categoryPageStr);
		// get desired course id by name & category
		$parsedData['LDBCourseId'] = $this->getDesiredCourseIdByName($categoryPageStrArr[0]);
		$categoryPageStrArr[1] = seo_url_lowercase($categoryPageStrArr[1]);
		$parsedData['subCategoryId'] = $this->abroadCategoryPageModelObj->getCategoryIdByName(ucwords(str_replace('-',' ',$categoryPageStrArr[1])),false);
		if(!($parsedData['LDBCourseId'] >0) || !($parsedData['subCategoryId'] >0) )
		{
			show_404_abroad();
		}
	}
	/*
	 * function to parse url string & get values for ldb course cat page
	 */
	private function _parseUrlStringForLevelSubCategoryPage(&$parsedData, $categoryPageStr){
		$categoryPageStr = str_replace('-courses','',$categoryPageStr);
		$categoryPageStrArr = explode('-in-',$categoryPageStr);
		// get level & subcategory
		$parsedData['courseLevel'] = str_replace("-", " - ", $categoryPageStrArr[0]);
		$categoryPageStrArr[1] = seo_url_lowercase($categoryPageStrArr[1]);
		$parsedData['subCategoryId'] = $this->abroadCategoryPageModelObj->getCategoryIdByName(ucwords(str_replace('-',' ',$categoryPageStrArr[1])),false);
		if($parsedData['courseLevel'] =="" || !($parsedData['subCategoryId'] >0) )
		{
			show_404_abroad();
		}
	}
	/*
	 * get desired course id by name
	 */
	public function getDesiredCourseIdByName($desiredCourseName = "")
	{
		if($desiredCourseName =="")
		{
			return false;
		}
		$abroadCommonLib = $this->CI->load->library('listingPosting/AbroadCommonLib');
		$desiredCourses = $abroadCommonLib->getAbroadMainLDBCourses();
		$desiredCourseId = 	array_filter(
											array_map(
												function($a)use($desiredCourseName){
                                                    $courseName = str_replace(' ','-',strtolower($a['CourseName']));
													$courseName = str_replace('/','-',strtolower($courseName));
													if($courseName==$desiredCourseName){
														return $a['SpecializationId'];
													}
												},$desiredCourses
											)
										);
		return reset($desiredCourseId);
	}

    public function prepareScholarShipCardApiParams($request,$filterCountryArray)
    {
        $catId = $request->getCategoryId();
        if(empty($catId))
            return false;
        $returnArray = array();
        if(!empty($filterCountryArray))
        {
            $returnArray['country'] = $filterCountryArray;
        }
        else
        {
            $returnArray['country'] = $request->getCountryId();
        }
        if($catId == 1)
        {
            $ldbCourseId = $request->getLDBCourseId();
            if(!empty($ldbCourseId))
            {
                $ldbCourseCat = $this->getLDBLevelAndCategory($ldbCourseId);
                $returnArray['category'] = $ldbCourseCat['category'];
                $returnArray['level'] = $ldbCourseCat['level'];
            }
            else
            {
                return false;
            }
        }
        else
        {
            if($request->getcourseLevel() == 'certificate - diploma')
            {
                return false;
            }
            $returnArray['level'] = (strpos($request->getcourseLevel(),'bachelor')!==false)?'bachelors':'masters';
            $returnArray['category'] = array($catId);
        }
        return $returnArray;
    }

    public function getLDBLevelAndCategory($ldbCourseId)
    {
    	global $studyAbroadPopularCourseToLevelMapping;
    	global $studyAbroadPopularCourseToCategoryMapping;
		$courseLevel   = strtolower($studyAbroadPopularCourseToLevelMapping[$ldbCourseId]);
		$categoryArray = $studyAbroadPopularCourseToCategoryMapping[$ldbCourseId];
        return array('level'=>$courseLevel,'category'=>$categoryArray);
	}
	/*
	 * function to get fat footer links & other data based on categorypagerequest obj
	 * @params: array containing request obj at key 'categoryPageRequest'
	 */
	public function prepareFatFooterLinkData($categoryPageRequest, $abroadCategoryList, $subCatIds = array())
	{ 
		if ($categoryPageRequest->isLDBCourseSubCategoryPage() || $categoryPageRequest->isCategorySubCategoryCourseLevelPage()){
			return false;
		}
		$clonedRequest = clone $categoryPageRequest;
		$countries = $clonedRequest->getCountryId();
		if(count($countries)>1){
			$clonedRequest->setData(array('countryId'=>array(1)));
		}
		if($countries[0]===1){
			$this->abroadCategoryPageCache = $this->CI->load->library('categoryList/cache/AbroadCategoryPageCache');				
			$key = $this->_getFatFooterCacheKey($clonedRequest);
			$widgetData = $this->abroadCategoryPageCache->getFatFooterWidgetData($key);
		}else{
			$widgetData = array();
		}
		if(count($widgetData)==0 || $widgetData === false){ // not in cache
			$widgetData['widgetTitle'] = $abroadCategoryList->getCategoryPageTitle($clonedRequest);
			foreach($subCatIds as $subCatId)
			{
				$clonedRequest->setData(array('subCategoryId'=>$subCatId));
				$title = $abroadCategoryList->getCategoryPageTitle($clonedRequest);
				$widgetData['linkData'][] = array('url'=>$clonedRequest->getURL(),'title'=>$title);
			}
			if($countries[0]===1){ // save in cache
				$this->abroadCategoryPageCache->storeFatFooterWidgetData($key,$widgetData);
			}
		}
		return $widgetData;
	}
	/*
	 * create cache key 
	 * key will have (courseLevel + category) OR (desired course) & countryId 1
	 */
	private function _getFatFooterCacheKey($request)
	{
		$country = $request->getCountryId();
		if($request->getLDBCourseId()!==1)
		{
			return $request->getLDBCourseId()."_".$country[0];
		}
		else
		{
			return $request->getCourseLevel()."_".$request->getCategoryId()."_".$country[0];
		}
	}
}
?>