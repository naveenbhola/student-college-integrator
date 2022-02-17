<?php
class AbroadCategoryPageRequestValidations
{    
    private $catPageUrl;
    private $availableCourseLevels = array('',
					   'bachelors',
					 'masters',
					 'phd',
					 'certificate - diploma');
    private $availableLDBCourses = array(1);
	private $CI;
	
	public function __construct() {
	    $this->CI = & get_instance();
		global $listOfValidExamAcceptedCPCombinations;
	   	$this->listOfValidExamAcceptedCPCombinations = $listOfValidExamAcceptedCPCombinations;
        $abroadCommonLib 	= $this->CI->load->library('listingPosting/AbroadCommonLib');
        $this->availableLDBCourses= array_merge(
            $this->availableLDBCourses,
            array_column($abroadCommonLib->getAbroadMainLDBCourses(),'SpecializationId')
        );

    }
    /* 	This function checks for Category Page Request parameters and does the following:
     * 	
     * 	i). Return flase if any of the parameter is not with in the valid range and can't be corrected (i.e Not Numeric / Is not Set / Is Zero etc.) so
     * 		that a 404 error page can be thrown to the user.
     * 		
     * 	ii). Check if we have to redirect the Category Page and redirects it using 301 redirect if any wrong param found
     *
     * 	iii). Return true if all is fine so that the valid Category Page can be served to the end user.
     */    

    public function redirectIfInvalidRequestParamsExist($request, $catPageData, $categoryRepository) {	
	
		$this->catPageUrl = $_SERVER["REQUEST_URI"];
		
		/*
		 * 	Lets check if all params are set and are numeric..
		 */ 
		if(!$this->_areParamsSetAndNumeric($request)) {
			error_log($this->catPageUrl.", :REQUEST_VALIDATION: 1. _areParamsSetAndNumeric FAILS");
			$this->_redirectCategoryPage();
		}

		if(!$this->_areParamsValid($request, $categoryRepository)) {
			error_log($this->catPageUrl.", :REQUEST_VALIDATION: 2. _areParamsValid FAILS");
			$this->_redirectCategoryPage();
		}

		/*
		 * in case of exam accepting category pages, validate exam name, redirect otherwise
		 */
		if($catPageData['acceptedExamName'] != '' && !$this->_isAcceptedExamNameValid($catPageData['acceptedExamName'])){
			error_log($this->catPageUrl.", :REQUEST_VALIDATION: 0. _isAcceptedExamNameValid FAILS, redirecting to 404".$catPageData['pageUrl']);
			// show 404 page if page examname is invalid
			$this->_redirectCategoryPage();
		}
		/*
		*	If the url entered by user is not the same as the actual / Canonical url of the page then do redirect it..
		*/
		if($this->_validateUrl($catPageData)) {
		   error_log($this->catPageUrl.", :REQUEST_VALIDATION: 0. _validateUrl FAILS, redirecting to ".$catPageData['pageUrl']);
		   $this->_redirectCategoryPage($catPageData['pageUrl']);
		}	
			
		return true;
    }
    
    private function _validateUrl($catPageData) {

	$errorFlag = false;
	// if this is the case of exam accepting categorypage, check against the exam accepting page's accepting url which will differ from cat page's base url(withput accepting exam)
	$catPageUrl = ($catPageData['urlStringForExamAcceptedPage'] != ''? $catPageData['urlStringForExamAcceptedPage']:$catPageData['canonicalUrl']);
	
	$pos = strpos($catPageUrl, '?'); 
	if($pos !==  false) {
	    $qryStr = substr($catPageUrl, $pos);
	    $catPageUrl = str_replace($qryStr, '', $catPageUrl);		
	}
	if($catPageUrl != "") {
		$userEnteredURL = getCurrentPageURLWithoutQueryParams();
		$pos = strpos($catPageUrl, "#");
		if($pos !== false) {			
			$len = strlen(substr($catPageUrl, $pos));			
			$catPageUrl = substr($catPageUrl, 0, -$len);
			$data['myCanonical'] = trim($catPageUrl);
		}
		
		// echo "<br> userEnteredURL = ".$userEnteredURL."<br> catPageUrl = ".$catPageUrl;
		/* below commented code made sense earlier when the only url param used was country.
		 * now we have other filters like fees,specialization,moreoptions in url params too
		 * therefore we need to compare the whole query string, not just the value  of country
		 * /
		/*$pos = strpos($catPageData['pageUrl'], '?country=');
		if($pos !==  false) {
		    $qryStr = $_GET['country'];
		    $actualQryStr = substr($catPageData['pageUrl'], $pos+9);
		     //echo "<br> actual = ".$actualQryStr."<br> qrystr = ".$qryStr;
		    if($actualQryStr != $qryStr) {
			$errorFlag = true;
		    }
		}*/
		//echo "cpdurl".$catPageData['pageUrl'];
		$pos = strpos($catPageData['pageUrl'], '?');
		if($pos !==  false) {
		    $qryStr = $_SERVER['QUERY_STRING'];
			$qryStr = str_replace('&source=Registration&newUser=1','',$qryStr);
		    $validQryStr = substr($catPageData['pageUrl'], $pos+1);
		    // echo "<br> valid = ".$validQryStr."<br> qrystr = ".$qryStr;
		    if($validQryStr != $qryStr) {
			$errorFlag = true;
		    }
		}
		// echo "<br>userEnteredURL = ".$userEnteredURL.", catPageUrl = ".$catPageUrl; die; 
		if($userEnteredURL != $catPageUrl) {
		    $errorFlag = true;
		}
	}
	
	return $errorFlag;
    }

    private function _areParamsValid($categoryPageRequest, $categoryRepository) {

        if($categoryPageRequest->getCategoryId() <= 1 && $categoryPageRequest->getSubCategoryId() <=1 && $categoryPageRequest->getLDBCourseId() <=1) {
            return false;
        }

        if($categoryPageRequest->isLDBCoursePage())
        {

            if(!(in_array($categoryPageRequest->getLDBCourseId(), $this->availableLDBCourses))) {
                return false;
            }
        }
        elseif($categoryPageRequest->isLDBCourseSubCategoryPage())
        {
            if(!(in_array($categoryPageRequest->getLDBCourseId(), $this->availableLDBCourses))) {
                return false;
            }

            $subCategory = $categoryRepository->find($categoryPageRequest->getSubCategoryId());
            if($subCategory->getId() == "" || $subCategory->getOldCategoryFlag() == 1 || $subCategory->getFlag() == "national") {
                return false;
            }
        }
        elseif($categoryPageRequest->isCategorySubCategoryCourseLevelPage())
        {
            $subCategory = $categoryRepository->find($categoryPageRequest->getSubCategoryId());
            if($subCategory->getId() == "" || $subCategory->getOldCategoryFlag() == 1 || $subCategory->getFlag() == "national" || $subCategory->getParentId() == 1) {
                return false;
            }
        }
        elseif($categoryPageRequest->isCategoryCourseLevelPage())
        {
            $category = $categoryRepository->find($categoryPageRequest->getCategoryId());
            if($category->getId() == "" || $category->getOldCategoryFlag() == 1 || $category->getFlag() == "national") {
                return false;
            }
        }

        return true;
    }
    
    private function _areParamsSetAndNumeric($request) {
	
	if($this->invalidDataCheck($request->getCategoryId()) || $request->getCategoryId() == 0 ||
		$this->invalidDataCheck($request->getSubCategoryId())  ||  $request->getSubCategoryId() == 0 ||
		    $this->invalidDataCheck($request->getLDBCourseId()) ||  $request->getLDBCourseId() == 0 ||
						    $this->invalidDataCheck($request->getPageNumberForPagination()))
	    {
		return false;
	    }
	    
	    if($request->getCountryId() == "") {
		return false;
	    } else {
		foreach($request->getCountryId() as $key => $cid) {
		    if($this->invalidDataCheck($cid) || $cid == "") {
			return false;
		    }
		}
	    }
	    	    
	    if( !(in_array($request->getCourseLevel(), $this->availableCourseLevels)) ) {
		return false;
	    }	
	    
	    return true;
    }
    
    public function invalidDataCheck($dataVar) {
	    if( !isset($dataVar) OR $dataVar === "" OR !is_numeric($dataVar))
		return true;
	    else
		return false;
    }
    
    private function _redirectCategoryPage($url = "") {	
	if($url === "") { // Show 404 error page..
	    show_404_abroad();    
	} else {	// Redirected to the requested URL using 301 redirect..
	    redirect($url, 'location', 301);
	}
	exit();
    }
    /*
     * this function checks if given exam is among the list of valid abroad exams or not
     * return val : TRUE / FALSE
     */
    private function _isAcceptedExamNameValid($acceptedExamName)
    {
	$CI 			= & get_instance();
	$abroadCommonLib 	= $CI->load->library('listingPosting/AbroadCommonLib');
	// get full exam data
	$examListData 		= $abroadCommonLib->getAbroadExamsMasterList();
	// get only exam name (short names)
	$examList 		= array_map(function ($v){ return $v['exam']; }, $examListData);
	// return true if exam is in our master list , false otherwise
	return in_array(strtoupper($acceptedExamName),$examList);
    }
    
    /*
     * Author   : Abhinav
     * Pupose   : Redirect to MS page if Request have Course level Masters and Category(or its subcategory) belongs to Engineering,Computer and Science
     * Params   : AbroadCategoryPageRequest, CategoryRepository, AbroadCategoryPageLib
     */
    public function redirectIfObsoleteCatPageToMS(AbroadCategoryPageRequest $abroadCategoryPageRequest,$categoryRepository,  AbroadCategoryPageLib $abroadCategoryPageLib){
        $request = clone $abroadCategoryPageRequest;
        if($request->getSubCategoryId() > 1){
            $category = $categoryRepository->find($request->getSubCategoryId());
            if(in_array($category->getParentId(), array(CATEGORY_ENGINEERING,CATEGORY_COMPUTERS,CATEGORY_SCIENCE))){ // added checks to see if this subcategory belongs to Engineering,Computer and Science
                //$subCatArray 	= $abroadCategoryPageLib->getSubCatsForParentCatAndCourseLevel($category->getParentId(), 'masters');
                $subCatArray = array(array('sub_category_id' => $request->getSubCategoryId()));
                // set data for MS-subcategory page
                $data = array('categoryId' => 1,'subCategoryId' => $category->getId(),'LDBCourseId' => DESIRED_COURSE_MS,'courseLevel' => '');
                $request->setData($data);
                $redirectionUrl = $request->getURL();
            }else{
                return;
            }
        }elseif($request->getCategoryId() > 1){
            if(in_array($request->getCategoryId(), array(CATEGORY_ENGINEERING,CATEGORY_COMPUTERS,CATEGORY_SCIENCE))){ // added checks to see if this category belongs to Engineering,Computer and Science
                $subCatArray 	= $abroadCategoryPageLib->getSubCatsForParentCatAndCourseLevel($request->getCategoryId(), 'masters');
                // set data for MS page
                $data = array('categoryId' => 1,'subCategoryId' => 1,'LDBCourseId' => DESIRED_COURSE_MS,'courseLevel' => '');
                $request->setData($data);
                $redirectionUrl = $request->getURL();
            }else{
                return;
            }
        }else{
            return;
        }
        
        $this->prepareFilterValueAndSetCookie('FILTERS-'.$request->getPageKey(),$request->getSubCategoryId(), $subCatArray);
        header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
        header('Pragma: no-cache'); // HTTP 1.0.
        header('Expires: 0'); // Proxies.
        redirect($redirectionUrl, 'location', 301);
    }
    
    /*
     * Author           : Abhinav
     * Pupose           : Redirect to B.E./B.TECH. page if Request have Course level Bachelors and Category(or its subcategory) belongs to Engineering and Computer
     * Params           : AbroadCategoryPageRequest, CategoryRepository, AbroadCategoryPageLib
     * Exceptional Case : Not to be done in case of subcategory ID : 276
     */
    public function redirectIfObsoleteCatPageToBtech(AbroadCategoryPageRequest $abroadCategoryPageRequest,$categoryRepository,  AbroadCategoryPageLib $abroadCategoryPageLib){
        $request = clone $abroadCategoryPageRequest;
        if($request->getSubCategoryId() > 1 && $request->getSubCategoryId() != 276){ // 
            $category = $categoryRepository->find($request->getSubCategoryId());
            if(in_array($category->getParentId(), array(CATEGORY_ENGINEERING, CATEGORY_COMPUTERS))){
                //$subCatArray 	= $abroadCategoryPageLib->getSubCatsForParentCatAndCourseLevel($category->getParentId(), 'bachelors');
                $subCatArray = array(array('sub_category_id' => $request->getSubCategoryId()));
                $data = array('categoryId' => 1,'subCategoryId' => $category->getId(),'LDBCourseId' => DESIRED_COURSE_BTECH,'courseLevel' => '');
                $request->setData($data);
                $redirectionUrl = $request->getURL();
            }else{
                return;
            }
        }elseif($request->getCategoryId() > 1){
            if(in_array($request->getCategoryId(), array(CATEGORY_ENGINEERING, CATEGORY_COMPUTERS))){
                $subCatArray 	= $abroadCategoryPageLib->getSubCatsForParentCatAndCourseLevel($request->getCategoryId(), 'bachelors');
                $data = array('categoryId' => 1,'subCategoryId' => 1,'LDBCourseId' => DESIRED_COURSE_BTECH,'courseLevel' => '');
                $request->setData($data);
                $redirectionUrl = $request->getURL();
            }else{
                return;
            }
        }else{
            return;
        }
        
        $this->prepareFilterValueAndSetCookie('FILTERS-'.$request->getPageKey(),$request->getSubCategoryId(), $subCatArray);
        header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
        header('Pragma: no-cache'); // HTTP 1.0.
        header('Expires: 0'); // Proxies.
        redirect($redirectionUrl, 'location', 301);
    }
    
    private function prepareFilterValueAndSetCookie($cookieName,$subCategoryId,$subCatArray){
		if($_COOKIE['homepageRedirect'] == 'true')
		{
			return;
		}
        $pageKeyCookieValue = array();
        $pageCookieValueFilterVaues = array();
        $pageCookieValueFilterSelectedOrder = array();
        $i = 0;
        foreach($subCatArray as $subCatArrayData){
            if($subCategoryId == 1){ // if no subcategory is selected, then add all sub-category in filters
                $pageCookieValueFilterVaues[] = 'course[]='.$subCatArrayData['sub_category_id'];
                $pageCookieValueFilterSelectedOrder[$subCatArrayData['sub_category_id']] = ++$i;
            }elseif($subCategoryId == $subCatArrayData['sub_category_id']){// if subCategory is selected then add that subcategory only in filter
                $pageCookieValueFilterVaues[] = 'course[]='.$subCatArrayData['sub_category_id'];
                $pageCookieValueFilterSelectedOrder[$subCatArrayData['sub_category_id']] = ++$i;
            }
        }
        $pageCookieValueFilterVaues = implode('&', $pageCookieValueFilterVaues);
        $pageKeyCookieValue['filterValues'] = $pageCookieValueFilterVaues;
        $pageKeyCookieValue['filterSelectedOrder'] = json_encode(array('course' => $pageCookieValueFilterSelectedOrder));
        $pageKeyCookieValue = json_encode($pageKeyCookieValue);
        setcookie($cookieName, $pageKeyCookieValue, time()+1800, "/", COOKIEDOMAIN);
        setcookie('pageRefreshCookie', 'true', time()+600, "/", COOKIEDOMAIN);
    }
    
    public function redirectIfObsoleteCatPageToSubCatPage(AbroadCategoryPageRequest $abroadCategoryPageRequest){
        $request = clone $abroadCategoryPageRequest;
        if(in_array($request->getSubcategoryId(), array(276,281))){
            $data = array('categoryId' => 1,'subCategoryId' => 276,'LDBCourseId'=>1,'courseLevel'=>'bachelors');
            $request->setData($data);
            $redirectionUrl = $request->getURL();
            header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
            header('Pragma: no-cache'); // HTTP 1.0.
            header('Expires: 0'); // Proxies.
            redirect($redirectionUrl, 'location', 301);
        }
    }
    
    /*
     *  All Masters of Business Category X Country pages must be 301 redirected to corresponding MBA x Country pages.
     *  If course level is 'masters' and category/entityId is business i.e CATEGORY_BUSINESS in country then redirect to mba in country
     *  All Categories X Specializations X Country pages must be 301 redirected to corresponding MBA x Specialization X Country pages
    */
    public function redirectIfObsoleteCatPageToMBA(AbroadCategoryPageRequest $request,$categoryRepository, AbroadCategoryPageLib $abroadCategoryPageLib)
    {
	//check if current category is CATEGORY_BUSINESS
	$categoryId = $request->getCategoryId();
	$subcategoryId = $request->getSubCategoryId();
	if($categoryId==CATEGORY_BUSINESS )
	{	
        $subCatArray 	= $abroadCategoryPageLib->getSubCatsForParentCatAndCourseLevel($categoryId, 'masters');
        //reset the object to generate corresponding MBA URL
	    $data =  array('categoryId'=>1,'LDBCourseId'=>DESIRED_COURSE_MBA,'courseLevel'=>'');
	    $request->setData($data);
	    //get the new URL
	    $url = $request->getURL();
	    // set cookie for subcategories
        $this->prepareFilterValueAndSetCookie('FILTERS-'.$request->getPageKey(),$subcategoryId, $subCatArray);
        
        header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
        header('Pragma: no-cache'); // HTTP 1.0.
        header('Expires: 0'); // Proxies.
        //301 redirection of the current URL
	    redirect($url, 'location', 301);
	
	}

	else if($subcategoryId >1 && $categoryId==1)
	{	
        $subCatArray = array(array('sub_category_id' => $subcategoryId));
        //check if the subcategory is a business subacategory
	   	$catobj 	= $categoryRepository->find($subcategoryId);
	    $categoryId 	= $catobj->getParentId();
	    if($categoryId== CATEGORY_BUSINESS)
	    {
		$data =  array('LDBCourseId'=>DESIRED_COURSE_MBA,'courseLevel'=>'');
		$request->setData($data);
		//get the new URL
		$url = $request->getURL();
        // set cookie for subcategory
        $this->prepareFilterValueAndSetCookie('FILTERS-'.$request->getPageKey(),$subcategoryId, $subCatArray);
		header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
        header('Pragma: no-cache'); // HTTP 1.0.
        header('Expires: 0'); // Proxies.
        //301 redirection of the current URL
		redirect($url, 'location', 301);
	    }
	    else
	    {
		return;
	    }
	}
	else
	{
	    return;
	}
    }
	/*
	 * validate parameters for exam accepted category page (EACP)
	 * @param : $params having course,exam,score range
	 * @see: Below are the steps in which this function performs the validations for above params:
	 * 	1. course & exam both are required.
	 * 	2. check if the exam & course are a valid combination, use the private list for this purpose
	 * 	3. check if 3rd param: score is available & has upper & lower limit, if yes then it means a score based url was opened.
	 * 	4. check if the score based url is valid using information from step 2
	 * 	@returnVal : values required to create a categorypage request object
	 */
	public function validateParametersForExamAcceptedCategoryPage($params)
	{
		// 1. course & exam both are required.
		if($params['courseName']=="" || $params['examName']=="")
		{ 
			show_404_abroad();
		}
		$examName = strtoupper($params['examName']);
		$courseName = strtoupper($params['courseName']);
		// extract page number from end string
		$params['pageNumber'] = $this->_getPageNumberFromExamAcceptedCategoryPageURL($params['examScoreRange']);
		// 2. check if the exam & course are a valid combination, use the private list for this purpose
		$ListOfValidExamsForEACP = $this->listOfValidExamAcceptedCPCombinations[$examName];
		//var_dump($ListOfValidExamsForEACP);
		if(
		   count($ListOfValidExamsForEACP)==0 ||
		   in_array($courseName,array_keys($ListOfValidExamsForEACP['coursesApplicable'])) === false
		   )
		{
			// invalid exam course combination
			show_404_abroad();
		}
		$this->CI = &get_instance();
		$this->abroadCommonLib = $this->CI->load->library("listingPosting/AbroadCommonLib");
		$examMaster = $this->abroadCommonLib->getAbroadExamsMasterList();
		$examInfo = reset(array_filter(array_map(function($a)use($examName){if($a['exam'] == $examName) return $a;},$examMaster)));
		// 3. check if 3rd param: score is available & has upper & lower limit
		$hasScore = false;
		$redirectionParams = $params;
		$scoreRangeInUrl = array();
		if($params['examScoreRange']!="")
		{
			$scoreRangeInUrl = explode('-to-',$params['examScoreRange']);
			$redirectionParams['examScoreRange']=$scoreRangeInUrl;
			$scoreLowerLimit = $scoreRangeInUrl[0];
			$scoreUpperLimit = $scoreRangeInUrl[1];
			if($scoreLowerLimit >0 && $scoreUpperLimit>0 && $scoreLowerLimit != $scoreUpperLimit) // both cant be equal
			{
				// Not going to flip them because the page shouldn't open if the range is flipped at all.
				// if($scoreLowerLimit > $scoreUpperLimit)
				// {
				// 	$tmp = $scoreLowerLimit;
				// 	$scoreLowerLimit = $scoreUpperLimit;
				// 	$scoreUpperLimit = $tmp;
				// }
				// 4. the url opened had score, check whether given exam course combination has a version of  url having score in it
				if($ListOfValidExamsForEACP['coursesApplicable'][$courseName] === true && $this->_checkIfScoreRangeValidForExam($examInfo, $scoreLowerLimit, $scoreUpperLimit))
				{
					// url is possible with score
					$hasScore = true;
				}
			}
		}
		$res = $this->checkAndPerformRedirection($redirectionParams, $hasScore);
		if($res == true) // no redirection so far
		{
			// parse params to get values for creation of category page request object
			$courseInfo = $this->_getCourseInfoForExamAcceptedCategoryPage($courseName);
		}
		$returnArr = array(
								'examId'=>$examInfo['examId'],
								'courseInfo'=>$courseInfo,
								'examScore'=>($hasScore === true? $scoreRangeInUrl:false),
								'pageNumber'=>$params['pageNumber']
							);
		return $returnArr;
	}
	/*
	 * function to check if given exam score range is valid for given exam
	 * @param: $examInfo (individual exam info from exam master), $scoreLowerLimit, $scoreUpperLimit 
	 */
	private function _checkIfScoreRangeValidForExam($examInfo=array(), $scoreLowerLimit="", $scoreUpperLimit="")
	{
		if($examInfo['exam']=="" || $scoreLowerLimit=="" || $scoreUpperLimit=="")
		{
			return false;
		}
		else{
			global $listOfValidScoresForExamAcceptedCPCombinations;
			if(in_array(strtoupper($examInfo['exam']), array_keys($listOfValidScoresForExamAcceptedCPCombinations))){
				$validScoreRanges = $listOfValidScoresForExamAcceptedCPCombinations[strtoupper($examInfo['exam'])];
				if($validScoreRanges[$scoreLowerLimit] == $scoreUpperLimit){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}
	}
	/*
	 * function to compute a final url & check if  user entered url was same
	 * if not redirect to the computed one
	 * @param: courseName, examName, examScoreRange
	 */
	public function checkAndPerformRedirection($params, $hasScore = false)
	{
		global $examAcceptedPagePattern;
		//_p($params);
		if($params['redirectionFromOldAcceptingExamUrls'] == true)
		{
			$ListOfValidExamsForEACP = $this->listOfValidExamAcceptedCPCombinations[$params['examName']];
			if(count($ListOfValidExamsForEACP)==0)
			{
				return false;// exam is not among the valid exams list, no redirection required
			}
			else if(in_array($params['courseName'],array_keys($ListOfValidExamsForEACP['coursesApplicable'])) === false)
			{
				// invalid exam course combination
				show_404_abroad(); // valid exam list being used with other course combination is not required, show 404
			}
		}
		// get user entered url
		$userEnteredURL = str_replace('?'.$_SERVER['QUERY_STRING'],'',$_SERVER['REQUEST_URI']);
		// create a url using the correct pattern based on whether the url has a score
		if($hasScore === true)
		{
			$urlPattern = $examAcceptedPagePattern['withScore'];
			$urlPattern = str_replace("{lower-limit}",intval($params['examScoreRange'][0]),$urlPattern);
			$urlPattern = str_replace("{upper-limit}",intval($params['examScoreRange'][1]),$urlPattern);
		}
		else
		{
			$urlPattern = $examAcceptedPagePattern['withoutScore'];
		}
		$urlPattern = str_replace("{course}",strtolower($params['courseName']),$urlPattern);
		$computedUrl = str_replace("{exam}",strtolower($params['examName']),$urlPattern);
		if($params['pageNumber']>1) // append page number, if any
		{
			$computedUrl .= "-".$params['pageNumber'];
		}
		//echo $userEnteredURL." , ".$computedUrl;
		if($userEnteredURL != $computedUrl)
		{
			if($_SERVER['QUERY_STRING'] != '')
			{
				$computedUrl .= '?'.$_SERVER['QUERY_STRING'];
			}
			$this->_redirectCategoryPage($computedUrl);
			exit();
		}
		else{
			return true;
		}
	}
	/*
	 * get  ldb course id[MS/MBA/BE-BTECH] OR courselevel+category[BBA]
	 */
	private function _getCourseInfoForExamAcceptedCategoryPage($courseName)
	{
		$LDBCourses = $this->abroadCommonLib->getAbroadMainLDBCourses();
		if($courseName == 'BE-BTECH')
		{
			$courseName = str_replace('-','/',$courseName);
		}
		$courseInfo = reset(array_filter(array_map(function($a)use($courseName){if(strtoupper($a['CourseName']) == $courseName) return $a;},$LDBCourses)));
		$returnArr =array();
		if(is_array($courseInfo))
		{
			$returnArr['ldbCourseId'] = $courseInfo['SpecializationId'];
		}
		else if($courseName == "BACHELORS-OF-BUSINESS"){
			$returnArr['courseLevel'] = "bachelors";
			$returnArr['categoryId'] = CATEGORY_BUSINESS;
		}
		return $returnArr;
	}
		/*
	 * parse the the end url entity string & get page number from it if any
	 * @param: $entityStr
	 * @return: $pageNumber
	 */
	private function _getPageNumberFromExamAcceptedCategoryPageURL(&$entityStr)
	{
		$scoreEntityStrArr = explode('-to-',$entityStr);
		if(count($scoreEntityStrArr) >1 ) // score is available
		{
			$entityStrArr = explode('-',$scoreEntityStrArr[1]);
			$scoreEntityStrArr[1] = $entityStrArr[0];
			if(count($entityStrArr) >1 && $entityStrArr[1] > 0)
			{
				$pageNumber = $entityStrArr[1];
			}														
			$entityStr = implode('-to-',$scoreEntityStrArr);
		}
		else if($scoreEntityStrArr[0]!= ""){
			$entityStrArr = explode('-',$scoreEntityStrArr[0]);
			if($entityStrArr[count($entityStrArr)-1] >0 )
			{
				$pageNumber = $entityStrArr[count($entityStrArr)-1];
			}
		}
		return $pageNumber;
	}
}