<?php

class CategoryPageRequest
{
    private $categoryId;
	private $subCategoryId;
	private $LDBCourseId;
	private $localityId;
	private $zoneId;
	private $cityId;
	private $stateId;
	private $countryId;
	private $regionId;
	private $sortOrder;
	private $pageNumber;
	private $naukrilearning;
	private $pageIdentifier;
	/* @var object of The main CodeIgniter object. */
	private $CI;
        
	// new variables added for RnR phase 2
	private $newURLFlag;
	private $examName;
	private $otherExamScoreData;
	private $affiliationName;
	private $feesValue;
	public  $urlManagerObj;
	public  $appliedFilters = array();
	public  $sortingCriteria = array();
	private $customNoOfResultPerPage; 
	
	public  $singleLocationPageHavingAllCityData = false; //RNR pages with single location URL can have all city page result -- Cagtegpry page revamp
	private $hideLocationLayer = 0;
           
	public function __clone(){
		$this->urlManagerObj = clone $this->urlManagerObj;
	}
	
	public function __construct($categoryPageURLQueryString = '', $newUrlFlag = false) {
 		$this->CI = & get_instance();
		$this->CI->config->load('categoryPageConfig');
		$this->CI->load->library("categoryList/CategoryPageURLManager");
		$this->newURLFlag = 0;
		if($newUrlFlag == CP_NEW_RNR_URL_TYPE) {
			$this->newURLFlag = 1;
		}
		$this->urlManagerObj = new CategoryPageURLManager();
		$this->buildFromURLQueryString($categoryPageURLQueryString);
    }
	
	public function buildFromURLQueryString($categoryPageURLQueryString)
	{
		$categoryPageParameters = explode("-",$categoryPageURLQueryString);
		// Check if URL contains combination of numbers(ids)
		if( !($this->newURLFlag) )
		{
			$this->categoryId 				= (int) $categoryPageParameters['0'];
			$this->subCategoryId 			= (int) $categoryPageParameters['1'];
			$this->LDBCourseId 				= (int) $categoryPageParameters['2'];
			$this->localityId 				= (int) $categoryPageParameters['3'];
			$this->zoneId 					= (int) $categoryPageParameters['4'];
			$this->cityId 					= (int) $categoryPageParameters['5'] == "" ? 1 :$categoryPageParameters['5'];
			$this->stateId 					= (int) $categoryPageParameters['6'] == "" ? 1 :$categoryPageParameters['6'];
			$this->countryId 				= (int) $categoryPageParameters['7'] == "" ? 2 :$categoryPageParameters['7'];
			$this->regionId 				= (int) $categoryPageParameters['8']? $categoryPageParameters['8']:0;
			$this->sortOrder 				= $categoryPageParameters['9']!=""?$categoryPageParameters['9']:'none';
			$this->pageNumber 				= (int) $categoryPageParameters['10']!=""?$categoryPageParameters['10']:1;
			$this->naukrilearning 			= (int) $categoryPageParameters['11']?1:0;
			/* 
			    JIRA ID : LF-3237 URL restructure for category pages
				CHECK PURPOSE: To create management category url like ctpg except management main url
			 */
			if($this->categoryId == 3 && $this->subCategoryId > 1){
				$this->urlManagerObj->setDataInUrlManager($this);
				$this->newURLFlag = 1;
			}
		}
        else
		{
			// Added by Romil on 7th Nov 2013 for RnR Phase2
			// Code for parsing the	URL containing the string values instead of IDs
      		// if URL is parsed successfully by the parser
			if( $this->urlManagerObj->URLParser($categoryPageURLQueryString) )
			{
				$parsedURLObj  = $this->urlManagerObj->getURLRequestData();
                //$this->_handleWrongURLs($parsedURLObj);
			}
			$this->categoryId		= (int) $parsedURLObj['categoryID'];
			$this->subCategoryId 	= (int) $parsedURLObj['subCategoryID'];
			$this->LDBCourseId 		= (int) $parsedURLObj['courseID'];
			$this->localityId 		= (int) $parsedURLObj['localityID'];
			$this->zoneId 			= (int) $parsedURLObj['zoneID'];//0;//$categoryPageParameters['4'];
			$this->cityId 			= (int) $parsedURLObj['cityID'];
			$this->stateId 			= (int) $parsedURLObj['stateID'];
			$this->countryId 		= (int) $parsedURLObj['countryID'];
			$this->regionId 		= (int) 0;//$categoryPageParameters['8']? $categoryPageParameters['8']:0;
			$this->sortOrder 		= $parsedURLObj['sortType']!=""?$parsedURLObj['sortType']:'none';
			$this->pageNumber 		= (int) $parsedURLObj['pageNumber']!=""?$parsedURLObj['pageNumber']:1;
			$this->naukrilearning 	= (int) $parsedURLObj['naukriLearning']?1:0;
			$this->hideLocationLayer= (int) $parsedURLObj['hideLocationLayer'];
		}
		$this->examName		      = $this->urlManagerObj->getExamName();
		$this->otherExamScoreData = $this->urlManagerObj->getOtherExamScoreData();
		$this->affiliationName    = $this->urlManagerObj->getAffiliationName();
		$this->feesValue          = $this->urlManagerObj->getFeesValue();
	}
	
	public function setData($data = array() , $setParentDataflag = 1)
	{
		//handled cloned request
		$currentCategoryId    = isset($data['categoryId']) ? $data['categoryId'] : $this->getCategoryId();
		$curretnSubCategoryId = isset($data['subCategoryId']) ? $data['subCategoryId'] : $this->getSubCategoryId();
		if($currentCategoryId == 3 && $curretnSubCategoryId > 1) {
			$this->newURLFlag=1;			
		}

		if($this->newURLFlag)
		{
			$this->setDataForNewUrl($data, $setParentDataflag);
		}
		else
		{
			foreach($data as $key => $value) {
				if(property_exists($this,$key)) {
					$this->$key = $value;
				}
			}
		}
	}
        
	public function setDataForNewUrl( $data , $setParentDataflag = 1) 
	{
		// array having mapping of data to be changed and their corresponding setters of URL manager and CateggoryPageRequest Variables.
		$setterArr = array("affiliation"           => array( "pageReg" => "affiliationName",
															 "urlMgr"  => "setAffiliationName"),
						   "sortOrder"             => array( "pageReg" => "sortOrder",
															 "urlMgr"  => "setSortOrder"),
						   "pageNumber"            => array( "pageReg" => "pageNumber",
															 "urlMgr"  => "setPageNumber"),
						   "naukrilearning"        => array( "pageReg" => "naukrilearning",
															 "urlMgr"  => "setNaukriLearning"),
						   "otherExamScoreData"    => array( "pageReg" => "otherExamScoreData",
															 "urlMgr"  => "setOtherExamScoreData"),
						   "feesValue"             => array( "pageReg" => "feesValue",
															 "urlMgr"  => "setFeesValue"),
						   "localityId"            => array( "pageReg" => "_setLocationData",
															 "urlMgr"  => "setLocality", "pageRegSetterFlag" => 1),
						   "zoneId"                => array( "pageReg" => "_setLocationData",
															 "urlMgr"  => "setZone", "pageRegSetterFlag" => 1),
						   "cityId"                => array( "pageReg" => "_setLocationData",
															 "urlMgr"  => "setCity", "pageRegSetterFlag" => 1),
						   "stateId"               => array( "pageReg" => "_setLocationData",
															 "urlMgr"  => "setState", "pageRegSetterFlag" => 1),
						   "countryId"             => array( "pageReg" => "_setLocationData",
															 "urlMgr"  => "setCountry", "pageRegSetterFlag" => 1),
						   "regionId"              => array( "pageReg" => "regionId",
															 "urlMgr"  => ""),
						   "LDBCourseId"           => array( "pageReg" => "_setCourseData",
															 "urlMgr"  => "setCourse", "pageRegSetterFlag" => 1),
						   "subCategoryId"         => array( "pageReg" => "_setCourseData",
															 "urlMgr"  => "setSubCategory", "pageRegSetterFlag" => 1),
						   "categoryId"            => array( "pageReg" => "_setCourseData",
															 "urlMgr"  => "setCategory", "pageRegSetterFlag" => 1),
						   "examName"              => array( "pageReg" => "examName",
															 "urlMgr"  => "setExamName"),
                           "hideLocationLayer"     => array( "pageReg" => "hideLocationLayer",
                           									 "urlMgr"  => "setHideLocationLayer",)

						   );

		// remove the dependent parent from the array if it child and parent both are present
		// eg. if locality and city are both present than remove city from the array
/*		$locationHierarcyArr  = array("localityId", "zoneId", "cityId", "stateId", "countryId", "regionId");
		$courseCatHierarcyArr = array("LDBCourseId", "subCategoryId", "categoryId"   );
		$locationExistsFlag = 0;
		foreach( $locationHierarcyArr as $key => $val )
		{
			if( $locationExistsFlag )
				unset($data[$val]);
			else
			{
				if( isset($data[$val]) )
					if( $data[$val] == 0 || $data[$val] == 1 )
						unset($data[$val]);
					else
						$locationExistsFlag = 1;
			}
		}

		$courseExistsFlag = 0;
		foreach( $courseCatHierarcyArr as $key => $val )
		{
			if( $courseExistsFlag )
				unset($data[$val]);
			
				if( isset($data[$val]) && ($data[$val] != 1 ))
					$courseExistsFlag = 1;
			
		}
*/
		// todo : check for the case of locality, when setting the locality check for zone also.
		foreach( $data as $key => $val )
		{
			if( array_key_exists($key, $setterArr) )
			{
				// set the Values of the URLManager object by calling the respective 
				// setter funtion to maintain the consistency of data
				if($setterArr[$key]["urlMgr"])
					$this->urlManagerObj->$setterArr[$key]["urlMgr"]($val, $setParentDataflag);
				
				// set the CategoryPageRequest Data-variable
				if( $setterArr[$key]["pageRegSetterFlag"] == 1 )
					$this->$setterArr[$key]["pageReg"]();
				else
					$this->$setterArr[$key]["pageReg"] = $val;
				
			   //echo "Setting key : ".$key." and Value : ".$val." and setter name : ".$setterArr[$key]["urlMgr"]." and pageReq : ".$this->$setterArr[$key]["pageReg"]."<br>";
			}
		}
	}
        
	public function setNewURLFlag( $input )
	{
		$this->newURLFlag = intVal($input);
	}

	public function isMainCategoryPage()
	{
		return (intval($this->categoryId) > 1 && intval($this->subCategoryId) <= 1);
	}
	
	public function isSubcategoryPage()
	{
		return (intval($this->subCategoryId) > 1 && intval($this->LDBCourseId) <= 1);
	}
	
	public function isLDBCoursePage()
	{
		return intval($this->LDBCourseId) > 1;
	}
	
	public function isLocalityPage()
	{
		return intval($this->localityId) > 0;
	}
	
	public function isZonePage()
	{
		return (intval($this->zoneId) > 0 && intval($this->localityId) == 0);
	}
	
	public function isCityPage()
	{
		return (intval($this->cityId) > 1 && intval($this->zoneId) == 0 && intval($this->localityId) == 0);
	}
	
	public function isStatePage()
	{
		return (intval($this->stateId) > 1 && intval($this->cityId) <= 1 && intval($this->zoneId) == 0 && intval($this->localityId) == 0);
	}
	
	public function setAppliedFilters($filters = array())
	{
		$this->appliedFilters = $filters;
	}
	
	public function setSortingCriteria($sortingCriteria = array())
	{
		$this->sortingCriteria = $sortingCriteria;
	}
	
	public function getAppliedFilters()
	{
		if(!empty($this->appliedFilters)){
			return $this->appliedFilters;
		}
		
		$cookiePrefix = '';
		if($this->pageIdentifier == 'ResponseMarketing') {
			$cookiePrefix = 'response_marketing_';
		}
		
		$appliedFilters = json_decode(base64_decode($_COOKIE[$cookiePrefix."filters-".$this->getPageKey()]),true);
		//$this->_setFiltersForURLManager($appliedFilters);
		
		
		//----------------------------
		//Changes for LF-2215 : To enable filtering of exam and score from URL
		if(!isset($appliedFilters['courseexams']))
		{
		    $otherExamScoreData = $this->urlManagerObj->getOtherExamScoreData();
		    $examName 		= $this->getExamName();
		    
		    if(empty($appliedFilters ['courseexams']) && !empty($otherExamScoreData))
		    {
			$appliedFilters ['courseexams'] = array ();
		    }
    
		    foreach ( $otherExamScoreData as $URL_course_exam_elemnt ) 
		    {
			    $courseexam = $URL_course_exam_elemnt [0] . '_' . ($URL_course_exam_elemnt [1] ? $URL_course_exam_elemnt [1] : '0');
			    if(!in_array($courseexam, $appliedFilters ['courseexams']))
				array_push ( $appliedFilters ['courseexams'], $courseexam );
		    }
		}
		//-----------------------------
		
		if(!$this->isAJAXCall() && !array_key_exists('locality', $appliedFilters)) {
			if($this->zoneId > 0 && !$appliedFilters['locality']){
				$appliedFilters['zone'] = array($this->zoneId);
				if($this->localityId > 0){
					$appliedFilters['locality'] = array($this->localityId);
				}
			}
		}
		
		if($this->subCategoryId == 23 || $this->subCategoryId == 56) { //For full time mba and be/btech subcat apply lastmodifed filter, show only lastmodified results after desc 2012
			$appliedFilters['lastmodifieddate'] = '2012-10-01';
		}
		return $appliedFilters;
	}
	
	private function _setFiltersForURLManager(&$appliedFilters) { 
	 	if((empty($appliedFilters) ||
			( empty($appliedFilters['courseexams']) &&
			  empty($appliedFilters['fees']) && 
			  empty($appliedFilters['degreePref'])
 			) )&& $this->getNewURLFlag() == 1) {
			if(empty($appliedFilters)) {
					$appliedFilters 	= array();
			}
			$otherExamScoreData = $this->urlManagerObj->getOtherExamScoreData();
			$examName = $this->getExamName();
			if ( ( !empty($examName) && $examName != "none" ) || (!empty( $otherExamScoreData )))
			{
				if(empty($appliedFilters ['courseexams']))
                                {
				$appliedFilters ['courseexams'] = array ();
				}
				$Exam_in_other_Param 			= false;
				foreach ( $otherExamScoreData as $URL_course_exam_elemnt ) 
				{
					$courseexam = $URL_course_exam_elemnt [0] . '_' . ($URL_course_exam_elemnt [1] ? $URL_course_exam_elemnt [1] : '0');
					if (($examName !== "" && $this->getExamName() != "none" ) && $URL_course_exam_elemnt [0] == $examName)
					{
						$Exam_in_other_Param = true;
					}
					array_push ( $appliedFilters ['courseexams'], $courseexam );
				}
				if (($examName !== "" && $this->getExamName() != "none") && (! $Exam_in_other_Param))
				{
					array_push ( $appliedFilters ['courseexams'], $examName . '_0' );
				}
	   		}
			if ((($this->getAffiliationName() != "") && $this->getAffiliationName() != "none"))
			{
				if(empty($appliedFilters ['degreePref']))
                                {
				$appliedFilters ['degreePref'] = array ();
				}
				array_push ( $appliedFilters ['degreePref'], $this->getAffiliationName () );
			}
			if ($this->getFeesValue() != - 1 && $this->getFeesValue() != "none")
			{
                                if(empty($appliedFilters ['fees']))
                                {
				$appliedFilters ['fees'] = array ();
				}
				array_push ( $appliedFilters ['fees'], $GLOBALS ['CP_FEES_RANGE'] ['RS_RANGE_IN_LACS'] [$this->getFeesValue ()] );
			}
		}
	}

	public function getSortingCriteria()
	{
		if(!empty($this->sortingCriteria)){
			return $this->sortingCriteria;
		}
		
		if(isset($_COOKIE["sortby-".$this->getPageKey()])){
			$this->sortOrder = $_COOKIE["sortby-".$this->getPageKey()];
		}
		$this->sortOrder = trim($this->sortOrder);
		$explode = explode("_" , $this->sortOrder);
		$sortCriteria = false;
		if(count($explode) > 1){
			$this->sortOrder = $explode[0];
    		$sortCriteria 	 = $explode[1];
			// set the sort order in urlManagerObj if newURLflag is ON(to maintain the consistency)
			if( $this->newURLFlag )
				$this->urlManagerObj->setSortOrder($this->sortOrder);
		}
			
		// set the sort order in urlManagerObj if newURLflag is ON(to maintain the consistency)
		if( $this->newURLFlag )
		    $this->urlManagerObj->setSortOrder($this->sortOrder);
		
		//$this->sortOrder = "highfees";
		switch($this->sortOrder){
			case 'highfees':
				$sortingCriteria = array('sortBy' => 'fees', 'params' => array('order' => 'DESC'));
				break;
			case 'lowfees':
				$sortingCriteria = array('sortBy' => 'fees', 'params' => array('order' => 'ASC'));
				break;
			case 'longduration':
				$sortingCriteria = array('sortBy' => 'duration', 'params' => array('order' => 'DESC'));
				break;
			case 'shortduration':
				$sortingCriteria = array('sortBy' => 'duration', 'params' => array('order' => 'ASC'));
				break;
			case 'viewCount':
				$sortingCriteria = array('sortBy' => 'viewCount', 'params' => array('order' => 'DESC'));
				break;
			case 'topInstitutes':
				$sortingCriteria = array('sortBy' => 'topInstitutes', 'params' => array('category' => $this->getCategoryId()));
				break;
			case 'dateOfCommencement':
				$sortingCriteria = array('sortBy' => 'dateOfCommencement', 'params' => array('order' => 'ASC'));
				break;
			case 'reversedateOfCommencement':
				$sortingCriteria = array('sortBy' => 'dateOfCommencement', 'params' => array('order' => 'DESC'));
				break;
			case 'highexamscore':
				$exam = $sortCriteria;
				$sortingCriteria = array('sortBy' => 'examscore', 'params' => array('order' => 'DESC','exam'=>$exam));
				break;
			case 'lowexamscore':
				$exam = $sortCriteria;
				$sortingCriteria = array('sortBy' => 'examscore', 'params' => array('order' => 'ASC','exam'=>$exam));
				break;
                        case 'highformSubmissionDate':
				$sortingCriteria = array('sortBy' => 'date_form_submission', 'params' => array('order' => 'DESC'));
				break;
                        case 'lowformSubmissionDate':
				$sortingCriteria = array('sortBy' => 'date_form_submission', 'params' => array('order' => 'ASC'));
				break;
					
		}
		
		//if(empty($sortingCriteria)) {
		//	$subCategoryId = $this->getSubCategoryId();
		//	$RNRSubcategoryIds = array_keys($this->CI->config->item("CP_SUB_CATEGORY_NAME_LIST"));
		//	if(in_array($subCategoryId, $RNRSubcategoryIds)) {
		//		$sortCondition = "ASC";
		//		if($subCategoryId == 23){
		//			$sortCondition = "DESC";
		//		}
		//		$sortingCriteriaIfSingleExamSelected = $this->applySingleExamSortingCriteria($sortCondition);
		//		if(!empty($sortingCriteriaIfSingleExamSelected)){
		//			$sortingCriteria = $sortingCriteriaIfSingleExamSelected;
		//		}
		//	}
		//}
		
		if($sortingCriteria) {
			return $sortingCriteria;
		}
	}
	
	public function getPageKey() 
	{
		$affiliationNameForKey 	= '-none';
		$feesValueForKey 		= '-none';
		$examNameForKey 		= '-none';
		$affiliationValue 		= $this->getAffiliationName();
		$feesValue 				= $this->getFeesValue();
		$examName 				= $this->getExamName();
		$localityId 			= $this->getLocalityId();
		$categoryId 			= $this->getCategoryId();
		$subcategoryId 			= $this->getSubCategoryId();
		$subcategoryId			= empty($subcategoryId) ? 1 : $subcategoryId;
		$ldbCourseId 			= empty($this->LDBCourseId) ? 1 : $this->LDBCourseId;
		$RNRSubCategories 		= array_keys($this->CI->config->item("CP_SUB_CATEGORY_NAME_LIST"));
		if(empty($localityId) || !in_array($subcategoryId, $RNRSubCategories)){
			$localityId = 0;
		}
		//_p($this->getLocalityId());
		if ( !empty($affiliationValue) && $affiliationValue != "none" )
		{
			$affiliationNameForKey = '-' . $affiliationValue;
		}
		if ( !empty($feesValue) && $feesValue != -1 && $feesValue != "none" )
		{
			$feesValueForKey = '-' . $feesValue;
		}
		if ( !empty($examName) && $examName != "none" )
		{   
			$examName = str_replace(array(' ','/','(',')',',','-'), '_', $examName);
			$examName = preg_replace('!_+!', '_', $examName);
			$examNameForKey = '-' . $examName;
		}
		$pageKey = 'CATPAGE-' . $this->categoryId . '-' . $subcategoryId . '-' . $ldbCourseId . '-' . $localityId . '-' . $this->cityId . '-' . $this->stateId . '-' . $this->countryId . '-' . $this->regionId . $affiliationNameForKey . $examNameForKey . $feesValueForKey;
		return $pageKey;
	}
	
	public function getParentPageKey() 
	{
		//Default values
		$affiliationNameForKey 	= '-none';
		$feesValueForKey 		= '-none';
		$examNameForKey 		= '-none';
		$localityId 			= 0;
		$categoryId 			= $this->getCategoryId();
		$subcategoryId 			= $this->getSubCategoryId();
		$pageKey = 'CATPAGE-' . $this->categoryId . '-' . $this->subCategoryId . '-' . $this->LDBCourseId . '-' . $localityId . '-' . $this->cityId . '-' . $this->stateId . '-' . $this->countryId . '-' . $this->regionId . $affiliationNameForKey . $examNameForKey . $feesValueForKey;
		return $pageKey;
	}
	
	public function getPageKeyString() {
		$pageKey 			= $this->getPageKey();
		$explode 			= explode("CATPAGE", $pageKey);
		$pageKeyString 		= false;
		if(count($explode) > 0){
			if(empty($explode[0]) && !empty($explode[1])) {
				$pageKeyString = trim($explode[1]);
				$pageKeyString = trim($explode[1], "-");
			}
		}
		return $pageKeyString;
		
	}
	
	public function isChildPage() {
		$affiliationValue 		= $this->getAffiliationName();
		$feesValue 				= $this->getFeesValue();
		$examName 				= $this->getExamName();
		$localityId 			= $this->getLocalityId();
		if(!empty($affiliationValue) && $affiliationValue != "none") {
			return TRUE;
		}
		if($feesValue != -1 && $feesValue != "none") {
			return TRUE;
		}
		if(!empty($examName) && $examName != "none") {
			return TRUE;
		}
		if(!empty($localityId)){
			return TRUE;
		}
		return FALSE;
	}
	
	public function getPageNumberForPagination()
	{
		if($this->pageNumber == "") {
			$this->pageNumber = 1;
		}
		//echo "hey".$this->pageNumber;
		return $this->pageNumber;
	}
	
	public function isNaukrilearningPage()
	{
		return $this->naukrilearning;
	}
	
	public function getSortOrder()
	{
		if(isset($_COOKIE["sortby-".$this->getPageKey()])){
			$this->sortOrder = $_COOKIE["sortby-".$this->getPageKey()];
		}
		return $this->sortOrder;
	}

	public function getSortOrderValue(){
		return $this->sortOrder;
	}
	
	/*
	 * Getters
	 */
	
	public function getFeesString()
	{
	    //Set feesString  in manager
	    $this->feesString = $this->urlManagerObj->getFeesString();
	    return $this->feesString;
	}
	
	public function getFullAffiliationName()
	{
	    $fullAffiliationName = $this->urlManagerObj->formatAffiliationName($this->affiliationName);
	    return $fullAffiliationName;
	}
	
	public function getCategoryId()
	{
		return $this->categoryId;
	}
	
	public function getSubCategoryId()
	{
		return $this->subCategoryId;
	}
	
	public function getLocalityId()
	{
		return $this->localityId;
	}
	
	public function getLDBCourseId()
	{
		return $this->LDBCourseId;
	}
	
	public function getZoneId()
	{
		return $this->zoneId;
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
	
	public function getRegionId()
	{
		return $this->regionId;
	}
        
        public function getNewURLFlag()
        {
            return $this->newURLFlag;
        }
	
	public function isStudyAbroadPage()
	{
		return (($this->regionId>0)||($this->countryId!=2));
	}
	
	public function getSnippetsPerPage()
	{
		return $this->_setNoOfResults();
	}

	private function _setNoOfResults()
	{
	  if(isset($this->customNoOfResultPerPage) && !empty($this->customNoOfResultPerPage)){
	  	return $this->customNoOfResultPerPage;
	  }elseif(($_COOKIE['ci_mobile'] == 'mobile') || ($GLOBALS['flag_mobile_user_agent'] == 'mobile')){
			return 10;
	  }elseif($this->pageIdentifier == 'ResponseMarketing') {
			return 15;
	  }else{
		    return 30;
	  }
	}

	public function isAJAXCall()
	{
		return ($_REQUEST['AJAX'] == 1);
	}
	
	public function getURL($pageNumber = 1)
	{
        $this->_checkSubCategoryAndResetFlag();
        if( $this->newURLFlag )
		{
			$this->setData(array("pageNumber" => $pageNumber ));
			$urlData = $this->urlManagerObj->getURL();
		}
        else
		{
			$urlData = $this->_getURLData($pageNumber);	
		}
		
		if($urlData)
		{
			return $urlData['url'];
		}
	}
	
	public function getCanonicalURL($pageNumber = 1)
	{
		$requestData['sortOrder'] = 'none';
		$requestData['naukrilearning'] = 0;
		$this->setData($requestData);
		
		if( $this->newURLFlag )
		{
			// to make the canonical of All Cities engineering category page(2-1-1) same as domain prefix
			if( $this->categoryId 		== 2 &&
				$this->subCategoryId 	== 1 && 
				$this->LDBCourseId		== 1 &&
				$this->cityId		== 1 &&
				$this->stateId		== 1 &&
				$this->countryId 		== 2
			)
			{
				global $categoryURLPrefixMapping;
				$canonicalURL = $categoryURLPrefixMapping[$this->categoryId];
				return $canonicalURL;
			}
			$urlData 			= $this->urlManagerObj->getURL();
			$queryParamsPos 		= strpos($urlData['url'], '?') ;
			$queryParamsPos 		= $queryParamsPos ? $queryParamsPos : strlen($urlData['url']);
			
			$url 				= substr($urlData['url'], 0, $queryParamsPos);
			$suffixMarketing 	= $this->urlManagerObj->formatOtherExamForUrl();
			if($suffixMarketing) {
				$suffix = "?".$suffixMarketing;
			} else {
				$suffix = "";
			}
			return $url.$suffix;
		}
		else
		{
			// to make the canonical of management category page(3-1-1) same as domain prefix
			/*
			//No need for special check: Pankaj(4th Dec 2014)
			if( $this->categoryId 		== 3 &&
				$this->subCategoryId 	== 1 && 
				$this->LDBCourseId		== 1 &&
				$this->countryId 		== 2
			)
			{
				global $categoryURLPrefixMapping;
				$canonicalURL = $categoryURLPrefixMapping[$this->categoryId];
				return $canonicalURL;
			}
			*/
			return $this->getURL((int)($pageNumber));
		}
	}
	
	public function getMetaData($numResults = 0)
	{
        $this->_checkSubCategoryAndResetFlag();

        //LF-3250 :: DIRECTORY STRUCTURE IMPLEMENTATION : NO Change in Meta details. 
        //so 23,56 subcategory will be having meta details from DB and others will be served from config.
        $RNRSubCategories 		= array_keys($this->CI->config->item("CP_SUB_CATEGORY_NAME_LIST"));
        $subCatId = $this->getSubCategoryId();
		if( $this->newURLFlag && (isset($subCatId) && in_array($subCatId, $RNRSubCategories)))
		{
			$urlData = $this->urlManagerObj->getMetaData($numResults);
		}
        else
		{
			$urlData = $this->_getURLData($pageNumber,$numResults);	
		}
		return $urlData;
	}
	
	private function _getURLData($pageNumber,$numResults)
	{
		global $categoryURLDataIndia, $categoryURLDataStudyAbroad, $subCategoryURLData, $LDBCourseURLData;
		global $categoryURLPrefixMapping, $countryURLPrefixMapping, $regionURLPrefixMapping;
		global $typeMappingForCatOrSubcat, $metaTitleTypeMapping, $metaDescriptionMapping, $h1TitleTypeMapping, $categoryMetaTitleMapping, $categoryMetaDescMapping, $subcategoryMetaTitleMapping, $subcategoryMetaDescMapping;
		
		//global $shikshaCountryMap, $shikshaCityMap, $shikshaStateMap, $shikshaRegionMap;
		
		$ci = & get_instance();
		$ci->load->builder('LocationBuilder','location');
		$locationBuilder = new LocationBuilder;
		$locationRepository = $locationBuilder->getLocationRepository();
		
		$ci->load->builder('CategoryBuilder','categoryList');
		$categoryBuilder = new CategoryBuilder;
		$categoryRepository = $categoryBuilder->getCategoryRepository();
		
		$categoryId = (int) $this->categoryId;
		$subCategoryId = (int) $this->subCategoryId;
		$LDBCourseId = (int) $this->LDBCourseId;
		
		$localityId = (int) $this->localityId;
		$zoneId = (int) $this->zoneId;
		$cityId = (int) $this->cityId;
		$stateId = (int) $this->stateId;
		$countryId = (int) $this->countryId;
		$regionId = (int) $this->regionId;
		
		$sortOrder = $this->sortOrder;
		$naukrilearning = (int) $this->naukrilearning;	
		if($countryId > 2 || $regionId >= 1) {
			if(!$categoryId) {
				$categoryId = 1;
			}
		}
		else if($countryId == 2) {
			if($categoryId <= 1 && $subCategoryId <= 1 && $LDBCourseId <= 1) {			    
				return FALSE;
			}
		}
		else {
			if($categoryId > 1 || $subCategoryId > 1 || $LDBCourseId > 1) {
				$countryId = 2;
			}
			else {
				return FALSE;
			}
		}

		$pageType = '';$partialSeoText = 'colleges';
		
		/*
		 * If this is an LDB Course Page
		 */ 
		if($LDBCourseId > 1) {

			$ci->load->builder('LDBCourseBuilder','LDB');
			$ldbBuilder = new LDBCourseBuilder;
			$ldbCourseRepository = $ldbBuilder->getLDBCourseRepository();

			$urlData = $LDBCourseURLData[$LDBCourseId];
			$subCategory = $categoryRepository->getCategoryByLDBCourse($LDBCourseId);
			//_p()die("<br> DATA = ");
			/*
			 * Make sure we have category Id and subcategory Id
			 */
			if($subCategoryId <= 1) {
				$subCategoryId = $subCategory->getId();
				$categoryId = $subCategory->getParentId();
			}
			$pageType = 'specialization';
			foreach($typeMappingForCatOrSubcat as $key => $arr){
				if(in_array($subCategoryId,$arr['subcategory'])){
					$partialSeoText = $key;
					break;
				}
			}
			$LDBCourseObj = $ldbCourseRepository->find($LDBCourseId);
			$pagename = $LDBCourseObj->getSpecialization();
			if(strtolower(trim($pagename)) == 'all'){
				$pagename = $LDBCourseObj->getCourseName();
			}
			// for engineering category, append subcategory name to metatitle too
			if($categoryId == 2){
				$pagename = $subCategory->getName().' in '.$pagename;
			}
		}
		else if($subCategoryId > 1) {
			
			$urlData = $subCategoryURLData[$subCategoryId];
			/*
			 * Make sure we have category Id and LDB Course Id
			 */ 
			$LDBCourseId = 1;
			$subCategory = $categoryRepository->find($subCategoryId);
			if($categoryId <= 1) {
				$categoryId = $subCategory->getParentId();
			}
			$pageType = 'subcategory';
			foreach($typeMappingForCatOrSubcat as $key => $arr){
				if(in_array($subCategoryId,$arr[$pageType])){
					$partialSeoText = $key;
					break;
				}
			}
			$pagename = $subCategory->getName();
		}
		else {
			
			$urlData = $countryId == 2 ? $categoryURLDataIndia[$categoryId] : $categoryURLDataStudyAbroad[$categoryId];			
			$subCategoryId = 1;
			$LDBCourseId = 1;

			$pageType = 'category';
			foreach($typeMappingForCatOrSubcat as $key => $arr){
				if(in_array($categoryId,$arr[$pageType])){
					$partialSeoText = $key;
					break;
				}
			}

			$pagename = $categoryRepository->find($categoryId)->getName();
		}

		if($pageType == 'category'){
			foreach($categoryMetaTitleMapping as $key => $arr){
				if(in_array($categoryId,$arr)){
					$urlData['title'] = $metaTitleTypeMapping[$key];
					$urlData['h1Title'] = $h1TitleTypeMapping[$key];
					break;
				}
			}
			foreach($categoryMetaDescMapping as $key => $arr){
				if(in_array($categoryId,$arr)){
					$urlData['description'] = $metaDescriptionMapping[$key];
					break;
				}
			}
		}
		else{
			foreach($subcategoryMetaTitleMapping as $key => $arr){
				if(in_array($subCategoryId,$arr)){
					$urlData['title'] = $metaTitleTypeMapping[$key];
					$urlData['h1Title'] = $h1TitleTypeMapping[$key];
					break;
				}
			}
			foreach($subcategoryMetaDescMapping as $key => $arr){
				if(in_array($subCategoryId,$arr)){
					$urlData['description'] = $metaDescriptionMapping[$key];
					break;
				}
			}
		}
		if(!isMobileRequest()) {
			$urlData['h1Title'] = preg_replace('/{Location}/i', '<span>{Location}</span>', $urlData['h1Title']);
		}
		else {
			$urlData['h1Title'] = preg_replace('/in {Location}/i', '<span style="display:block;padding-top:3px;">in <strong class="change-loc transparency">{Location}</strong>', $urlData['h1Title']);
		}
		$location = '';
		
		if($localityId > 0) {
			
			$locality = $locationRepository->findLocality($localityId);
			$location = $locality->getName();
			
			$zoneId = $locality->getZoneId();
			$cityId = $locality->getCityId();
			$stateId = $locality->getStateId();
			$countryId = $locality->getCountryId();
		}
		else if($zoneId > 0) {
			
			$zone = $locationRepository->findZone($zoneId);
			$location = $zone->getName();
			
			$localityId = 0;
			$cityId = $zone->getCityId();
			$stateId = $zone->getStateId();
			$countryId = $zone->getCountryId();
		}
		else if($cityId > 1) {
		    
			$localityId = 0;
			$zoneId = 0;
			
			if($countryId > 2) {
			   	$countryObj = $locationRepository->findCountry($countryId);
			   	$location = $countryObj->getName();
			   	$cityId = 1; 
			   	$stateId = 1;
			   	$regionId = $countryObj->getRegionId();
			} else {
				$cityObj = $locationRepository->findCity($cityId);
				$location = $cityObj->getName();
			    $stateId = $cityObj->getStateId();
			    if($stateId < 1) {
				    $stateId = 1;
			    }
			    $countryId = 2;
			}
		}
		else if($stateId > 1) {
			$localityId = 0;
			$zoneId = 0;
			$cityId = 1;
			if($countryId > 2) {
				$countryObj = $locationRepository->findCountry($countryId);
			    $location = $countryObj->getName();
			    $stateId = 1;
			    $regionId = $countryObj->getRegionId();
			} else {
				$stateObj = $locationRepository->findState($stateId);
			    $location = $stateObj->getName();
			    $countryId = 2;
			}
		}
		else if($countryId > 1) {
			$countryObj = $locationRepository->findCountry($countryId);
			$location = $countryObj->getName();
			$localityId = 0;
			$zoneId = 0;
			$cityId = 1;
			$stateId = 1;
			$regionId = $countryObj->getRegionId();
		}
		else if($regionId >= 1) {
			$regionObj = $locationRepository->findRegion($regionId);
			$location = $regionObj->getName();
			$location2 = $regionObj->getName()." - ";
			$countriesArray1 = $locationRepository->getCountriesByRegion($regionId);
			foreach($countriesArray1 as $country){
				$countriesArray[] = $country->getName();
			}
			$location2 .= implode(", ",$countriesArray);

			$localityId = 0;
			$zoneId = 0;
			$cityId = 1;
			$stateId = 1;
			$countryId = 1;
		}
		else {
			
			$localityId = 0;
			$zoneId = 0;
			$cityId = 1;
			$stateId = 1;
			$countryId = 2;
			$location = 'India';
		}
		
		$urlIdentifierKey = $categoryId.'-'.$subCategoryId.'-'.$LDBCourseId.'-'.$localityId.'-'.$zoneId.'-'.$cityId.'-'.$stateId.'-'.$countryId.'-'.$regionId.'-'.$sortOrder.'-'.$pageNumber.'-'.$naukrilearning;
		$urlIdentifier = '-categorypage-'.$categoryId.'-'.$subCategoryId.'-'.$LDBCourseId.'-'.$localityId.'-'.$zoneId.'-'.$cityId.'-'.$stateId.'-'.$countryId.'-'.$regionId.'-'.$sortOrder.'-'.$pageNumber.'-'.$naukrilearning;
		
		$url = '';
		
		if($urlData && is_array($urlData) && count($urlData)) {
			if(!$location2){
				$location2 = $location;
			}
			foreach($urlData as $key => $value)
			{
				if($key != 'url'){
					$urlData[$key] = str_ireplace('{location}',$location2,$urlData[$key]);
					if(!empty($numResults)){
						$urlData[$key] = str_ireplace('{resultCount}',$numResults,$urlData[$key]);
					}
					else{
						$urlData[$key] = str_ireplace('{resultCount} ','',$urlData[$key]);
					}
					if($key == 'description' && $numResults == 1){
						if($partialSeoText == 'classes'){
							$val = 'class';
						}
						else{
							$val = rtrim($partialSeoText,'s');
						}
						$urlData[$key] = str_ireplace('{type}',$val,$urlData[$key]);
					}
					else{
						$urlData[$key] = str_ireplace('{type}',$partialSeoText,$urlData[$key]);
					}
					$urlData[$key] = str_ireplace('{coursetype}',$pagename,$urlData[$key]);
					if($key == 'title' && $this->getPageNumberForPagination() > 1){
						$urlData[$key] = 'Page '.$this->getPageNumberForPagination().' - '.$urlData[$key];
					}
					if($key == 'description' && $categoryId == 13 && $this->getPageNumberForPagination() > 1){
						$urlData[$key] = 'Page '.$this->getPageNumberForPagination().' - '.$urlData[$key];
					}
				}else{
					$urlData[$key] = str_ireplace('{location}',$location,$urlData[$key]);
				}
				
			}
			$url = $urlData['url'];
			$url = str_replace(array(' ','/','(',')',','),'-',$url);
			$url = preg_replace('!-+!', '-', $url);
			$url = strtolower(trim($url,'-'));
		}

		//special check for management url
		if($categoryId == 3 && $subCategoryId == 1){
			$url = 'mba/colleges/'.$url;
		}
	
		$domainPrefix = SHIKSHA_HOME;
		if($naukrilearning) {
			$domainPrefix = NAUKRI_SHIKSHA_HOME;
		}
		else if($countryId > 2) {
			$domainPrefix = $countryURLPrefixMapping[$countryId];
		}
		else if($regionId > 0) {
			$domainPrefix = $regionURLPrefixMapping[$regionId];
		}
		else if($categoryId > 1) { 
			$domainPrefix = $categoryURLPrefixMapping[$categoryId];
		}
	
                if($domainPrefix == "")
                    $domainPrefix = SHIKSHA_HOME;

		// $url = $domainPrefix.'/'.$url.$urlIdentifier;
                if($subCategoryId == 1 && $cityId == 1 && $stateId == 1 && $countryId == 2 && $categoryId != 3 && (!$pageNumber || $pageNumber < 2) && SHIKSHA_ENV != 'dev'){ 
                       $url = $domainPrefix;
               }else if($categoryId == 3 && $subCategoryId == 1){
               		$url = $domainPrefix.'/'.$url.'/'.$urlIdentifierKey;
               } else {
                       $url = $domainPrefix.'/'.$url.$urlIdentifier;
               }
		
		$urlData['url'] = $url;
		
		return $urlData;
	}
	
	public function isTestPrep(){
		return  $this->categoryId == 14;
	}

        // Added by Amit K on 27th April 2012 for Cat pages Gutter banners, ticket #920
        public function showGutterBanner() {
	     return 1; // Need to show on all of the Category Pages.
             /*
             if($this->getSubCategoryId() == 23 && $this->cityId == 10223) {        // Full Time MBA/PGDM Delhi/NCR Cat page.
                return 1;
             } else if($this->getSubCategoryId() == 28 && $this->cityId == 10223) {  // Full Time BBA/BBM Delhi/NCR Cat page.
                return 1;
             }
             return 0;
	     */	
        }
                
        // Name getters
	public function getAffiliationName()
	{
		return ( $this->newURLFlag ? $this->urlManagerObj->getAffiliationName() : "" );
	}

	public function getCategoryName()
	{
		return ( $this->newURLFlag ? $this->urlManagerObj->getCategoryName() : "" );
	}

	public function getSubCategoryName()
	{
		return ( $this->newURLFlag ? $this->urlManagerObj->getSubCategoryName() : "" );
	}

	public function getCourseName()
	{
		return ( $this->newURLFlag ? $this->urlManagerObj->getCourseName() : "" );
	}

	public function getExamName()
	{
		return ( $this->newURLFlag ? $this->urlManagerObj->getExamName() : "" );
	}

	public function getFeesValue()
	{
		return ( $this->newURLFlag ? $this->urlManagerObj->getFeesValue() : -1 );
	}

	public function getLocalityName()
	{
		return ( $this->newURLFlag ? $this->urlManagerObj->getLocalityName() : "" );
	}
        
	public function getZoneName()
	{
		return ( $this->newURLFlag ? $this->urlManagerObj->getZoneName() : "" );
	}
        
	public function getCityName()
	{
		return ( $this->newURLFlag ? $this->urlManagerObj->getCityName() : "" );
	}

	public function getStateName()
	{
		return ( $this->newURLFlag ? $this->urlManagerObj->getStateName() : "" );
	}
        
	public function getCountryName()
	{
		return ( $this->newURLFlag ? $this->urlManagerObj->getCountryName() : "" );
	}
        
        public function getOtherExamScoreData() 
        {
                return ( $this->newURLFlag ? $this->urlManagerObj->getOtherExamScoreData() : "" );
        }        
        
        private function _setCourseData()
        {
            $this->LDBCourseId   = $this->urlManagerObj->getCourseID();
            $this->subCategoryId = $this->urlManagerObj->getSubCategoryID();
            $this->categoryId    = $this->urlManagerObj->getCategoryID();
        }
        
        private function _setLocationData()
        {
            $this->localityId   = $this->urlManagerObj->getLocalityID();
            $this->zoneId       = $this->urlManagerObj->getZoneID();
            $this->cityId       = $this->urlManagerObj->getCityID();
            $this->stateId      = $this->urlManagerObj->getStateID();
            $this->countryId    = $this->urlManagerObj->getCountryID();
        }

		public function applySingleExamSortingCriteria($sortCondition = FALSE) {
		$sortingCriteria = FALSE;
		$appliedFilters = $this->getAppliedFilters();
		if(!empty($appliedFilters) && !empty($sortCondition)) {
			if(array_key_exists('courseexams', $appliedFilters)){
				$courseExams = $appliedFilters['courseexams'];
				$examName = FALSE;
				if(count($courseExams) == 1){
					$examStr = $courseExams[0];
					if(!empty($examStr)){
						$explode = explode("_", $examStr);
						$examName = $explode[0];
						if(!empty($examName)){
							$sortingCriteria = array('sortBy' => 'examscore', 'params' => array('order' => $sortCondition, 'exam'=> $examName));
						}
					}
				}
			}
		}
		return $sortingCriteria;
		}
                
         /***
          * Function to set/reset the newURlFlag depending upon the value of Subcategory 
          * set in the data members.
          * 
          * If subcategory doesn't belongs to the subcategrories covered in RnR ( eg. MBA, BE-Btech)
          * then do not reset the flag
          */       
         private function _checkSubCategoryAndResetFlag()
         {
             $list = $this->CI->config->item("CP_SUB_CATEGORY_NAME_LIST_DIRECTORY");
            
             if( !array_key_exists($this->subCategoryId, $list ) && ($this->subCategoryId != 1 || $this->categoryId != 2) )
                     $this->newURLFlag = 0;
         }
         
         private function _handleWrongURLs( $parsedURLObj )
         {
              if( $parsedURLObj['categoryID'] == -1 )
              {
                  show_404();
              }
         }

	public function setDataByPageKey( $pageKey )
	{
		
		$pageKey = explode("CATPAGE-",$pageKey);
		$Key = !empty($pageKey[0]) ? $pageKey[0] :$pageKey[1];
		$RNRSubCategories 		= array_keys($this->CI->config->item("CP_SUB_CATEGORY_NAME_LIST"));
	    $trackData  		= array();
	    $trackData 			= explode( '-', $Key );
	        $urlData['categoryId']      = $trackData[0];
            $urlData['subCategoryId']   = $trackData[1];
            $urlData['LDBCourseId']     = $trackData[2];
            $urlData['localityId']      = $trackData[3];
            $urlData['cityId']          = $trackData[4];
            $urlData['stateId']         = $trackData[5];
            $urlData['countryId']       = $trackData[6];
            $urlData['regionId']        = $trackData[7];
            $urlData['affiliation']     = $trackData[8];
            $urlData['examName']        = $trackData[9];
            $urlData['feesValue']       = $trackData[10];
            
          	if(in_array($urlData['subCategoryId'], $RNRSubCategories)){
          		$this->setNewURLFlag(1);
          		$urlData['naukrilearning'] = (int) 0;
          		$urlData['sortOrder'] = 'none';
          	}
          	
            	
	    $this->setData($urlData);
	}
	

  /*
   * Category page Revamp - check for RNR pages with Multilocation scenario
   */


	public function checkForSingleLocationPageWithMultiLocationBehaviourRNRCtpg() {
		$appliedFilters = json_decode(base64_decode($_COOKIE[$cookiePrefix."filters-".$this->getPageKey()]),true);
		
		if((count($appliedFilters['city']) + count($appliedFilters['state']))>1 || ((array_key_exists('city', $appliedFilters) && array_key_exists('state', $appliedFilters)) && (count($appliedFilters['city']) + count($appliedFilters['state'])) == 0)){
		   if(count($this->getUserPreferredLocationOrder()) < 1){
		   	$this->singleLocationPageHavingAllCityData = true;
		  	return false; 
		  }
			return true;
		}
		else if((count($appliedFilters['city']) == 1 && !in_array($this->getCityID(),$appliedFilters['city']) ) || (count($appliedFilters['state']) == 1 && !in_array($this->getStateID(),$appliedFilters['state']))) {
		if(count($this->getUserPreferredLocationOrder()) < 1){
		   	$this->singleLocationPageHavingAllCityData = true;
		  	return false; 
		  }
			return true;
		} else {
		
			return false;
		}
	}
	
	/*
	 * function to determine if the page is multilocation or not.
	 */
	public function isMultilocationPage() {
	    // first check if the request is for single city page
	    if(!($this->getCityID() == 1 && $this->getStateID()==1))
	    {
	     if(in_array($this->getSubCategoryId(), array(23,56))) {	
	     return $this->checkForSingleLocationPageWithMultiLocationBehaviourRNRCtpg();
	     } 
	     return false;
		}
		
	    $userCityPreferenceCookie = explode(":",$_COOKIE['userCityPreference']);
	    if($userCityPreferenceCookie[0] == 1 && 	// city id
	       $userCityPreferenceCookie[1] == 1 && 	// state id
	       $userCityPreferenceCookie[2] == 2) 	// country id
	    {
			//check if multiple cities were selected,
			if((in_array($this->getSubCategoryId(), array(23,56)) && count($this->getUserPreferredLocationOrder()) > 0) || count($this->getUserPreferredLocationOrder()) > 1) {
				return true;
			}
			else{
				return false;
			}
	    }
	    else{
			return false;
	    }
	}
	/*
	 * function to get multilocation cookie
	 * @param : optional: $rawCookieFlag : true will give decoded raw cookie itself
	 */
	public function getUserPreferredLocationOrder($rawCookieFlag = false) {
	    //get the filter cookie to get city & state
	    $filtersCookie = json_decode(base64_decode($_COOKIE['filters-'.$this->getPageKey()]));

	    $userMultiLocationsPreference = json_decode(base64_decode($_COOKIE['userMultiLocPref-MainCat-'.$this->getCategoryId()]));
	    if($rawCookieFlag){
		return $userMultiLocationsPreference;
	    }
	    $order = array();
	    foreach($userMultiLocationsPreference as $locationPreference)
	    {
		$locData = explode("_",$locationPreference);
		// if there are any locations in filters cookie 
		if( (count($filtersCookie->city)+count($filtersCookie->state)) > 0 )
		{ 
		    // ... & this location doesnot exist in filters cookie,
		    if(
		       $locData[0]=='c' && !in_array($locData[1],$filtersCookie->city)  ||
		       $locData[0]=='s' && !in_array($locData[1],$filtersCookie->state)
		       )
		    {	// no need to insert

			continue;
		    }
		}
		// insert in order array
		$order[] = array(
				 'location_type' => ($locData[0]=='c'?"city":"state"),
				 'location_id'	 => $locData[1]
				);
	    }//echo "ordaaa...";_p($order);
	    return $order;
	}
	
	public function getPageKeyForCachingSolrResults(){
         if($this->isMultilocationPage()){
             $locations = $this->getUserPreferredLocationOrder();
             $cityBucket  = array();
             $stateBucket = array();
             foreach($locations as $location){
                 if($location['location_type'] == 'state'){
                     $stateBucket[] = $location['location_id'];
                 } else if($location['location_type'] == 'city'){
                     $cityBucket[] = $location['location_id'];
                 }
             }
             $cityString  = "ct";
             $stateString = "st";
             if(!empty($stateBucket)){
                 asort($stateBucket);
                 $stateString .= "-" . implode("-", $stateBucket);
             }
             if(!empty($cityBucket)){
                 asort($cityBucket);
                 $cityString .= "-" . implode("-", $cityBucket);
             }
             $key = $this->getPageKey() . "-" . $cityString . "-" . $stateString;
         } else {
             $key = $this->getPageKey();
         }
         return $key;
     }
     
    function isMultiLocationPageLoad()
    {
	$sameFlag = false;
	$locationSelected = $this->getUserPreferredLocationOrder();
	
	$citiesSelected = array();
	$statesSelected = array();
	foreach($locationSelected as $location)
	{
	    if($location['location_type'] == 'city')
		$citiesSelected[] = $location['location_id'];
	    else if($location['location_type'] == 'state')
		$statesSelected[] = $location['location_id'];
	}
	
	$citiesFilter = array();
	$statesFilter = array();
	$filterArr = $this->getAppliedFilters();
	
	if(isset($filterArr['city']))
	    $citiesFilter = $filterArr['city'];
	if(isset($filterArr['state']))
	    $statesFilter = $filterArr['state'];
	    
	//_p($citiesSelected);
	//_p($citiesFilter);
	//_p($statesSelected);
	//_p($statesFilter);
	//_p($statesSelected)
	if(array_diff($citiesSelected,$citiesFilter) == array_diff($citiesFilter,$citiesSelected) &&
	   array_diff($statesSelected,$statesFilter) == array_diff($statesFilter,$statesSelected))
	{
	    $sameFlag = true;
	}
	if(isset($filterArr['locality']) && !empty($filterArr['locality']))
	    $sameFlag = false;

	// check if no other filter is set other than location city,state,locality and lastmodifieddate filters
	if($sameFlag == true)
	{
	    $numEmpty = 0;
	    foreach($filterArr as $key=>$val)
	    {
		if(!in_array($key,array('city', 'state', 'locality')) && !empty($val))
		    $numEmpty++;
	    }
	    if(!($numEmpty == 0 || ($numEmpty == 1 && isset($filterArr['lastmodifieddate'])))) {
		$sameFlag = false;
	    }
	}

	return $sameFlag;
    }
  
  public function isRequestForSingleCityPageHavingAllCityResult() {
  	return $this->singleLocationPageHavingAllCityData;
  }
  
  function getCategoryPageUrlText() {
      
      $categoryName = $this->urlManagerObj->getCategoryName();
      $categoryID = $this->urlManagerObj->getCategoryID();
      $subCategoryId = $this->urlManagerObj->getSubCategoryID();
      $subCategoryName = $this->urlManagerObj->getSubCategoryName();
      $localityName = $this->urlManagerObj->getLocalityName();
      $localityID = $this->urlManagerObj->getLocalityID();
      $cityName = $this->urlManagerObj->getCityName();
      $cityID = $this->urlManagerObj->getCityID();
      $stateName = $this->urlManagerObj->getStateName();
      $stateID = $this->urlManagerObj->getStateID();
      $countryName = $this->urlManagerObj->getCountryName();
      $affiliationName = $this->urlManagerObj->getAffiliationName();
      $examName = $this->urlManagerObj->getExamName();
      $feesValue = $this->urlManagerObj->getFeesValue();
      $feesString = $this->urlManagerObj->getFeesString();
      
      $this->urlManagerObj->convertFeeNumericToString();
      
      $subCategoryName = $this->urlManagerObj->checkAndFormatSubcategory($subCategoryId, $subCategoryName, $categoryID, $categoryName);
      if(!$subCategoryName)
         return false;
      
      if($courseName && $courseID > 1) {
            $courseName = ucfirst($courseName);
            $courseNameTitle = " in ".$courseName;
            $courseNameDscptn = " ".$courseName;
            if($courseID == 2 || $courseID == 52) {
                $courseName = "";
                $courseNameTitle = "";
                $courseNameDscptn = "";
            }
      }
      
      $locationName = $this->urlManagerObj->checkAndFormatLocation($localityName, $localityID, $cityName, $cityID, $stateName, $stateID, $countryName);
      if(!$locationName)
         return false;
      
      $affiliationName = $this->urlManagerObj->formatAffiliationName($affiliationName);
      
      
      if($affiliationName) {
            if($examName) {
                if($feesValue > 0 && $feesString) {
                    $title = $affiliationName.$subCategoryName.$courseNameTitle." Institutes in ".$locationName.", accepts ".$examName." score & fees upto ".$feesString;
                } else {
                    $title = $affiliationName.$subCategoryName.$courseNameTitle." Institutes in ".$locationName.", accepts ".$examName." score";
                }
            } else {
                if($feesValue > 0 && $feesString) {
                    $title = $affiliationName.$subCategoryName.$courseNameTitle." Institutes in ".$locationName.", fees upto ".$feesString;
                }
                else {
                    $title = $affiliationName.$subCategoryName.$courseNameTitle." Institutes in ".$locationName;
                }
            }
        } else {
            if($examName) {
                if($feesValue > 0 && $feesString) {
                    $title = "Apply to ".$subCategoryName.$courseNameTitle." Institutes in ".$locationName.". Accepts ".$examName." score & Fees upto ".$feesString."";
                }
                else {
                    $title = $subCategoryName.$courseNameTitle." Institutes in ".$locationName." accept ".$examName." score";
                }
            } else {
                if($feesValue > 0 && $feesString) {
                    $title = $subCategoryName.$courseNameTitle." Institutes in ".$locationName.", fees upto ".$feesString."";
                }
                else {
                    if(in_array($subCategoryName,array('MBA','Engineering'))) { // for mba sub-category page, these are different as per user story SEO-6 
                        if($locationName == 'India') {
                            $title = $subCategoryName.$courseNameTitle." Institutes in ".$locationName;
                        } else {
                            $title = $subCategoryName.$courseNameTitle." Institutes in ".$locationName;
                        }
                    } else{
                            $title = $subCategoryName.$courseNameTitle." Institutes in ".$locationName;
                    }
                }
            }
        }
      
      return $title;      
  }

  public function setCustomNoOfResultPerPage($noOfResult){
  	$this->customNoOfResultPerPage = $noOfResult;
  }

  public function getHideLocationLayer(){
  	return $this->hideLocationLayer;
  }
}
