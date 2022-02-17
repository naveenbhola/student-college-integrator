<?php
/*
Purpose       : To parse the URL and extract Course/Location/Affiliation/Exam/Fee from it.
	
Author 	      : Romil Goel

Creation Date : 01-11-2013

To Do 	      : 1.) Identify the parts of the location string extracted for locality/city/state in respective priorities.
		2.) Replace the dummy data checks with the Database checks. 
		3.) Remove all echoes and test Data.
*/

// Identifiers 
define("_ID_TYPE_AFFILIATION" , 1 		);
define("_ID_TYPE_EXAM"        , 2 		);
define("_ID_TYPE_FEE" 	      , 3 		);
define("_ID_TYPE_COURSE"      , 4 		);
define("_ID_TYPE_LOCATION"    , 5 		);


/* Purpose      : Class for parsing the URL provided to it and returning following values from the URl - Affiliation/Fee/Course/Location/Exam
*
*  Data Member  : 1.) $urlString
*
*  Member funcs : 1.) setUrl()
*		  2.) getUrl()
*		  3.) parseURL()
*		  4.) getAffiliation()
*		  5.) getCourse()
*		  6.) getFees()
*		  7.) getExams()
*		  8.) getLocations()
*		  9.) checkExistenceAndGetIndex()
*		  10.)getSubStringURL()
*  
*/
class CategoryPageURLParser
{

//********* Data Member Definition ****************
	private $urlString;

	private $CI;
	private $listingCache;
	


//********* Member functions Definition ***********

	public function __construct(){
		$this->CI =& get_instance();

		// load category page config
		$this->CI->config->load('categoryPageConfig');

		$this->CI->load->library('listing/cache/ListingCache');
		$this->listingCache = new ListingCache;
                		
        // load the Category list client library
		$this->CI->load->library('category_list_client');
		$this->categoryClient = new Category_list_client();
		
		$examsList = $this->listingCache->getExamsList();
		if(!empty($examsList)){
			$this->exam_list = $examsList;
		} else {
			$this->exam_list = $this->categoryClient->getTestPrepCoursesList(1);
			$this->listingCache->storeExamsList($this->exam_list);
		}
	}


	/* Purpose	 : Function to parse the URL and identify values like Affiliation/Exam/Course/Fee/Location. 
	*
	*  Input params  : 1.) $urlStr - URL to be parsed		
	*
	*  Output Params : None
	*
	*  Return Vals   : 1.) Array consisting of following values Affiliation/Exam/Course/Fee/Location from the URL. 
	*
	*  To Do         : None
	*/
	public function parse( $urlStr )
	{
		// set the URL string
		if(empty( $urlStr ))
			return 0;
		
		$this->urlString     = urldecode($urlStr);
		
		// remove the category page identifier if present
		$this->urlString     = str_replace("-".CP_CATEGORY_PAGE_URL_IDENTIFIER , "", $this->urlString);
		
		// sanitize the URL
		$this->urlString     = $this->sanitizeURL($this->urlString);
		
		$parsedOutput        = array();
		// this order of extracting page number first is important
		$pageNumber          = $this->getPageNumber();
		// get the values of each required element from the URL
		$affiliationData     = $this->getAffiliation();
		$courseData          = $this->getCatSubcatCourse();
		$locationData        = $this->getLocations();
		$examName            = $this->getExams();
		$feesValue           = $this->getFees();
		$sortType            = $this->getSortType();
		$naukriLearning      = $this->getNaukriLearning();
		
		// get the exam names and score from the request parameters
		$otherExamsAndScores = $this->getOtherExamsAndScores();

		//request parameter to hide location Layer In case for FT MBA AND BE BTECH 
		//passing subCategoryID for check
		$currentSubCat = "";
		if(isset($courseData[2]))
			$currentSubCat = $courseData[2];

		$hideLocationLayer = $this->getHideLocationLayer($currentSubCat);
		
				
		
		// return the array of parsed values	
		return array(
					"examName"            => $examName,
					"feesValue"           => $feesValue,
					"affiliationData"     => $affiliationData,
					"otherExamScoreData"  => $otherExamsAndScores,
					"pageNumber"          => $pageNumber,
					"sortType"            => $sortType,
					"naukriLearning"      => $naukriLearning,
					"categoryID"          => $courseData[0],
					"categoryName"        => $courseData[1],
					"subCategoryID"       => $courseData[2],
					"subCategoryName"     => $courseData[3],
					"courseID"            => $courseData[4],
					"courseName"          => $courseData[5],
					"localityID"          => $locationData[0],
					"localityName"        => $locationData[1],
					"zoneID"              => $locationData[2],
					"zoneName"            => $locationData[3],
					"cityID"              => $locationData[4],
					"cityName"            => $locationData[5],
					"stateID"             => $locationData[6],
					"stateName"           => $locationData[7],
					"countryID"           => $locationData[8],
					"countryName"         => $locationData[9],
					"noLocationFoundFlag" => $locationData[10],
					"hideLocationLayer"   => $hideLocationLayer
				);
	}


	/* Purpose	 : Function to identify the existence of Affiliation in the URL and extract it. 
	*
	*  Input params  : None		
	*
	*  Output Params : None
	*
	*  Return Vals   : 1.) Array consisting of Affiliation Name and its identifier.
	*
	*  To Do         : None
	*/
	private function getAffiliation()
	{
		// get the starting and ending indexes of Affiliation from the URL if it exists otherwise return not-found values(-1,-1)
		if( $this->checkExistenceAndGetIndex( _ID_TYPE_AFFILIATION, $index_arr ) == _ID_FLAG_OFF )
		{
			return array(-1, _ID_EMPTY_STRING);
		}
		
		//if exists then extract the affiliation value
		$this->getSubStringURL( $index_arr[0], $index_arr[1], $subStringURL );

		$affiliationArr = $this->CI->config->item("CP_AFFILIATION_LIST");
		// return the result
		if( ($res = array_search($subStringURL, $this->CI->config->item("CP_AFFILIATION_LIST"))) === FALSE )
			return array(-1, _ID_EMPTY_STRING);
		else			
			return array( $res, $affiliationArr[$res] );
		
	}

	/* Purpose 	 : Function to identify the existence of Course in the URL and extract it. 
	*
	*  Input params  : None		
	*
	*  Output Params : None
	*
	*  Return Vals   : 1.) Array consisting of Course Name and its identifier.
	*
	*  To Do         : None
	*/
	private function getCatSubcatCourse()
	{
		$start_index 	= -1;
		$end_index   	= -1;
		$catId   	= -1;
		$catName 	= '';
		$subCatId 	= CP_DEFAULT_VAL_SUB_CATEGORY_ID;
		$subCatName 	= '';
		$courseId 	= CP_DEFAULT_VAL_COURSE_ID;
		$courseName 	= '';

		$subCatNameList = $this->CI->config->item("CP_SUB_CATEGORY_NAME_LIST");

		if( ($start_index = strpos( $this->urlString, _ENT_AFFILIATION_END1 )) === FALSE )
			if( ($start_index = strpos( $this->urlString, _ENT_AFFILIATION_END2 )) === FALSE )
				$start_index = 0;
			else
				$start_index += strlen(_ENT_AFFILIATION_END2);
		else
			$start_index += strlen(_ENT_AFFILIATION_END1);
		
		// remove affiliation from the URL if any
		$subStringURL = substr( $this->urlString, $start_index);

		// remove any hyphen in the beginning of the substring
		$subStringURL = trim($subStringURL,"-");
		
		// search for the course name end delimiter
		if( ($end_index = strpos( $subStringURL, _ENT_LOCATION_START1 )) === FALSE )
			if( ($end_index = strpos( $subStringURL, _ENT_LOCATION_START2 )) === FALSE )
				if( ($end_index = strpos( $subStringURL, _ENT_EXAM_START )) === FALSE )
					if( ($end_index = strpos( $subStringURL, _ENT_FEE_START )) === FALSE )
					{
						$end_index = strlen($subStringURL);
					}
		// get the string containing only sub-category and course name
		$subStringURL = trim( substr($subStringURL, 0, $end_index), "-");
		
		if( empty($subStringURL) )
			return array(	$catId,
					$catName,
					$subCatId,
					$subCatName,
					$courseId,
					$courseName,
				    );

		//***** Seperate  subcategory  and course name if exists ****** 
		// if course name exists in the URL

		if( ($seperator_index = strpos($subStringURL , "-"._ENT_SUBCAT_COURSE_SEPERATOR )) !== FALSE )
		{
			$seperator_index += 1;
			$subCatUrl 	     = trim( substr($subStringURL, 0, $seperator_index), "-") ;
			$extractedCourseName = trim( substr($subStringURL, $seperator_index+strlen(_ENT_SUBCAT_COURSE_SEPERATOR)) , "-");
		}
		else if( ($seperator_index = strpos($subStringURL , "engineering" )) !== FALSE )
		{
			$subCatUrl 	     = "engineering";
			$extractedCourseName = "";
		}
		// else no course exists in the URL
		else
		{
			$subCatUrl 	     = $subStringURL ;
			$extractedCourseName = "";
		}

		//if( strcasecmp($subCatUrl, _ENT_COURSE_MBA) == 0)

		if( strpos($subCatUrl, _ENT_COURSE_PART_TIME_MBA) !== FALSE )
		{
			$subCatName = _ENT_COURSE_PART_TIME_MBA;
			$subCatId   = array_search( _ENT_COURSE_PART_TIME_MBA, $this->CI->config->item("CP_SUB_CATEGORY_NAME_LIST_DIRECTORY"));
			
		}elseif( strpos($subCatUrl, _ENT_COURSE_EXECUTIVE_MBA) !== FALSE )
		{
			$subCatName = _ENT_COURSE_EXECUTIVE_MBA;
			$subCatId   = array_search( _ENT_COURSE_EXECUTIVE_MBA, $this->CI->config->item("CP_SUB_CATEGORY_NAME_LIST_DIRECTORY"));
			
		}
		elseif( strpos($subCatUrl, _ENT_COURSE_ONLINE_MBA) !== FALSE )
		{
			$subCatName = _ENT_COURSE_ONLINE_MBA;
			$subCatId   = array_search( _ENT_COURSE_ONLINE_MBA, $this->CI->config->item("CP_SUB_CATEGORY_NAME_LIST_DIRECTORY"));
			
		}elseif( strpos($subCatUrl, _ENT_COURSE_MBA_DISTANCE_LEARNING) !== FALSE )
		{
			$subCatName = _ENT_COURSE_MBA_DISTANCE_LEARNING;
			$subCatId   = array_search( _ENT_COURSE_MBA_DISTANCE_LEARNING, $this->CI->config->item("CP_SUB_CATEGORY_NAME_LIST_DIRECTORY"));

		}elseif( strpos($subCatUrl, _ENT_COURSE_INTEGRATED_BTECH_MBA) !== FALSE )
		{
			$subCatName = _ENT_COURSE_INTEGRATED_BTECH_MBA;
			$subCatId   = array_search( _ENT_COURSE_INTEGRATED_BTECH_MBA, $this->CI->config->item("CP_SUB_CATEGORY_NAME_LIST_DIRECTORY"));

		}elseif( strpos($subCatUrl, _ENT_COURSE_INTEGRATED_MBA) !== FALSE )
		{
			$subCatName = _ENT_COURSE_INTEGRATED_MBA;
			$subCatId   = array_search( _ENT_COURSE_INTEGRATED_MBA, $this->CI->config->item("CP_SUB_CATEGORY_NAME_LIST_DIRECTORY"));

		}
		elseif( strpos($subCatUrl, _ENT_COURSE_BBM_BBA_BBS) !== FALSE )
		{
			$subCatName = _ENT_COURSE_BBM_BBA;
			$subCatId   = array_search( _ENT_COURSE_BBM_BBA, $this->CI->config->item("CP_SUB_CATEGORY_NAME_LIST_DIRECTORY"));
		}
		elseif( strpos($subCatUrl, _ENT_COURSE_BBM_BBA) !== FALSE )
		{
			$subCatName = _ENT_COURSE_BBM_BBA;
			$subCatId   = array_search( _ENT_COURSE_BBM_BBA, $this->CI->config->item("CP_SUB_CATEGORY_NAME_LIST_DIRECTORY"));

		}elseif( strpos($subCatUrl, _ENT_COURSE_CERTIFICATION) !== FALSE )
		{
			$subCatName = _ENT_COURSE_CERTIFICATION;
			$subCatId   = array_search( _ENT_COURSE_CERTIFICATION, $this->CI->config->item("CP_SUB_CATEGORY_NAME_LIST_DIRECTORY"));
			
		}elseif( strpos($subCatUrl, _ENT_COURSE_MBA) !== FALSE )
		{
			$subCatName = _ENT_COURSE_MBA;
			$subCatId   = array_search( _ENT_COURSE_MBA, $this->CI->config->item("CP_SUB_CATEGORY_NAME_LIST"));
			
		}
		//else if( strcasecmp($subCatUrl, _ENT_COURSE_BE_BTECH) == 0 )
		else if( strpos($subCatUrl, _ENT_COURSE_BE_BTECH) !== FALSE )
		{
			$subCatName = _ENT_COURSE_BE_BTECH;
			$subCatId   = array_search( _ENT_COURSE_BE_BTECH, $this->CI->config->item("CP_SUB_CATEGORY_NAME_LIST"));
		}
		else
		{
			if( strpos($subCatUrl, "engineering") !== FALSE )
			{
				$catId 		= 2;
				$catName 	= "engineering";
				$subCatName 	= "";
				$subCatId   	= 1;
				$courseId  	= 1;
			}

			return array(	$catId,
					$catName,
					$subCatId,
					$subCatName,
					$courseId,
					$courseName,
				    );
			
		}

		$this->CI->load->repository('LDBCourseRepository','LDB');
		$this->CI->load->builder('LDBCourseBuilder','LDB');
		$this->CI->load->entity('LDBCourse','LDB');

		$builderObj = new LDBCourseBuilder;
		$repoObj = $builderObj->getLDBCourseRepository();
		//_p($repoObj->getLDBCoursesForSubCategory($subCatId));
		$courseList = $repoObj->getLDBCoursesForSubCategory($subCatId);
		
		$LDBCourseList = array();
		if( $extractedCourseName != _ID_EMPTY_STRING )
		{
			foreach( $courseList as $key=>$value )
			{
				if( $value->getSpecialization() != 'All')
					$specialization = $value->getSpecialization();
				else
					$specialization = $value->getCourseName();
				
				$sanitizedSpecialization = str_replace(array('+'), '-', $specialization);
				$sanitizedSpecialization = $this->sanitizeURL($sanitizedSpecialization);
				
				$foundFlag = 0;
				if( strcasecmp($sanitizedSpecialization, $extractedCourseName) == 0 )		
				{
					$courseId   = $value->getId();
					$courseName = $specialization;
				}
			}
		}

		// if no course is found then set the value of course-id as default
		if( $courseId == -1 )
			$courseId = CP_DEFAULT_VAL_COURSE_ID; // for ALL courses case

		$this->CI->load->repository('CategoryRepository','categoryList');
		$this->CI->load->builder('CategoryBuilder','categoryList');
		$this->CI->load->entity('Category','categoryList');

		$builderObj	= new CategoryBuilder;
		$repoObj 	= $builderObj->getCategoryRepository();
		// get the sub-category name from the sub-category id
		$subCatObj 	= $repoObj->find($subCatId);
		$subCatName 	= $subCatObj->getName();

		// get the category name and id from the sub-category id
		$categoryObj 	= $repoObj->find($subCatObj->getParentId());
		$catName 	= $categoryObj->getName();
		$catId		= $categoryObj->getId();
			
		return array(	$catId,
				$catName,
				$subCatId,
				$subCatName,
				$courseId,
				$courseName,
			    );
	}
	
	/* Purpose	 : Function to identify the existence of Fee in the URL and extract it. 
	*
	*  Input params  : None		
	*
	*  Output Params : None
	*
	*  Return Vals   : 1.) Array consisting of Fee Value and its identifier.
	*
	*  To Do         : None
	*/
	private function getFees()
	{
		// get the starting and ending indexes of Fee from the URL if it exists otherwise return not-found values(-1,-1)
		if( $this->checkExistenceAndGetIndex( _ID_TYPE_FEE, $index_arr ) == _ID_FLAG_OFF )
		{
			return -1;
		}

		//if exists then extract the affiliation value
		$this->getSubStringURL( $index_arr[0], $index_arr[1], $subStringURL );

		$feesArray  	= $this->CI->config->item('CP_FEES_RANGE');

		// convert fees string into numeric string eg. convert 2lacs to 200000
		$subStringURL	= str_replace( array("lacs","lakh"), "00000", $subStringURL);

		// return the result
		if( ($res = array_key_exists($subStringURL, $feesArray['RS_RANGE_IN_LACS'])) === FALSE )
			return -1;
		else			
			return intVal($subStringURL);
		
	}

	/* Purpose	 : Function to identify the existence of Exam in the URL and extract it.
	*
	*  Input params  : None		
	*
	*  Output Params : None
	*
	*  Return Vals   : 1.) Array consisting of Exam name and its identifier
	*
	*  To Do         : None
	*/
	private function getExams()
	{
		// get the starting and ending indexes of Exam from the URL if it exists otherwise return not-found values(-1,-1)
		if( $this->checkExistenceAndGetIndex( _ID_TYPE_EXAM, $index_arr ) == _ID_FLAG_OFF )
		{
			return _ID_EMPTY_STRING;
		}	 

		//if exists then extract the affiliation value
		$this->getSubStringURL( $index_arr[0], $index_arr[1], $subStringURL );
		
		$examList = $this->getExamList();
		// if the Cached Exam list is not found then prepare the list from the DB and store it in cache
		//if( ($examList = $this->listingCache->getExamList('CP_EXAM_LIST') ) == FALSE )
		//{
		//	$this->prepareAndStoreExamListInCache();
		//}

		// return the result
		
		$examList['SRMEEE'] = "SRMEEE"; // To handle 301 redirection in CategoryList.php as SRMEEE exam name was changed to SRMJEEE.(LF-2521) 
		
		if( ($res = array_search(strtolower($subStringURL), array_map('strtolower', $examList))) === FALSE )
			return _ID_EMPTY_STRING;
		else			
			return $res;
	}

	/* Purpose	 : Function to extract exam names and their corressponding scores from the Request Variables.
	*
	*  Input params  : None		
	*
	*  Output Params : None
	*
	*  Return Vals   : 1.) Array consisting of Exam name and its score
	*
	*  To Do         : None
	*/
	private function getOtherExamsAndScores( )
	{
            	$examAndScoresList = $this->CI->input->get(CP_REQUEST_VAR_NAME_EXAM);
	
		//$examAndScoresList = $this->sanitizeURL($examAndScoresList);
                // sanitize the data
                $examAndScoresList = str_replace(array(' ','/','(',')',','), '-', $examAndScoresList);
		$examAndScoresList = str_replace("&", "and", $examAndScoresList); 
		$examAndScoresList = preg_replace('!-+!', '-', $examAndScoresList);
		$examAndScoresList = strtolower(trim($examAndScoresList, '-'));


		$finalExamScoreArr = array();
		$examScoreToken    = explode( CP_OTHER_EXAM_AND_EXAM_SEPERATOR, $examAndScoresList );

		// if no exam,score list is found in the request parameters then return emmty array
		if( empty( $examAndScoresList ))
			return $finalExamScoreArr;

		$examList = $this->getExamList();
		
		// loop through each exam,score string
		foreach( $examScoreToken as $key=>$val )
		{
			$tempExamScoreArr = explode( CP_OTHER_EXAM_NAME_AND_SCORE_SEPERATOR, $val);
 			// check if this exam exists or not
			if( ($res = array_search(strtolower($tempExamScoreArr[0]), array_map('strtolower', $examList))) !== FALSE )
			{
				$scoreVal = empty($tempExamScoreArr[1]) ? '0' : $tempExamScoreArr[1];
                                
                                array_push( $finalExamScoreArr, array($res,$scoreVal)); 
			}
		}
		return $finalExamScoreArr;
	}

/*        private function getScoreRange( $examName, $examScore )
        {
             $examTypeMappingObj = $this->getExamTypeMapping();
            _p($examTypeMappingObj);
            
            if( isset($examTypeMappingObj[strtolower($examName)]) )
                $examType = $examTypeMappingObj[strtolower($examName)];
            else
                return -1;
            
            return $examType;
        }
*/        
	/* Purpose	 : Function to identify the existence of Location(Locality/City/state) in the URL and extract it.
	*
	*  Input params  : None		
	*
	*  Output Params : None
	*
	*  Return Vals   : 1.) Array consisting of Location name and its identifier
	*
	*  To Do         : None
	*/
	private function getLocations()
	{
		// get the starting and ending indexes of Location from the URL if it exists otherwise return not-found values(-1,-1)
		if( $this->checkExistenceAndGetIndex( _ID_TYPE_LOCATION, $index_arr ) == _ID_FLAG_OFF )
		{
			return array(	CP_DEFAULT_VAL_LOCALITY_ID,
					CP_DEFAULT_VAL_LOCALITY_NAME,
					CP_DEFAULT_VAL_ZONE_ID,
					CP_DEFAULT_VAL_ZONE_NAME,
					CP_DEFAULT_VAL_CITY_ID,
					CP_DEFAULT_VAL_CITY_NAME,
					CP_DEFAULT_VAL_STATE_ID,
					CP_DEFAULT_VAL_STATE_NAME,
					CP_DEFAULT_VAL_COUNTRY_ID,
					CP_DEFAULT_VAL_COUNTRY_NAME,
                                        _ID_FLAG_OFF
				    );	
		}	 

		//if exists then extract the affiliation value
		$this->getSubStringURL( $index_arr[0], $index_arr[1], $subStringURL );

		//echo "Location substring : ".$subStringURL."<br>";
		if( $this->identifyLocation($subStringURL, $outputArr) == _ID_FLAG_OFF )
			return array(	CP_DEFAULT_VAL_LOCALITY_ID,
					CP_DEFAULT_VAL_LOCALITY_NAME,
					CP_DEFAULT_VAL_ZONE_ID,
					CP_DEFAULT_VAL_ZONE_NAME,
					CP_DEFAULT_VAL_CITY_ID,
					CP_DEFAULT_VAL_CITY_NAME,
					CP_DEFAULT_VAL_STATE_ID,
					CP_DEFAULT_VAL_STATE_NAME,
					CP_DEFAULT_VAL_COUNTRY_ID,
					CP_DEFAULT_VAL_COUNTRY_NAME,
                                        _ID_FLAG_OFF
				    );
		else
			return $outputArr;
		
			
/*		// return the result
		if( ($res = array_search($subStringURL, $affiliationArr)) === FALSE )
			return array( -1, -1 );
		
		else			
			return array( $subStringURL, $res );
*/		
	}

	/* Purpose 	 : Function to get the page number from the Request Variables if exists.
	*
	*  Input params  : None		
	*
	*  Output Params : None
	*
	*  Return Vals   : 1.) Page Number - if exists
	*		       -1	   - if doesn't exists
	*
	*  To Do         : None
	*/
	private function getPageNumber()
	{
		//extract the page number from the end if it exists
		if( is_numeric( substr($this->urlString, -1) ) )
		{
			// extract the page number from the URL
			$keywords 	= explode("-",$this->urlString);
			//$page_number 	 = substr($this->urlString, -1);
			$page_number 	= end($keywords);
			// remove the page number from the URL
			$this->urlString = substr($this->urlString, 0, -1);
			
			$this->urlString = $this->sanitizeURL($this->urlString);
					
			// convert the page number into numeric value		
			$page_number = intVal($page_number);
                
	                if( $page_number < 1 )
	                    $page_number = CP_DEFAULT_VAL_PAGE_NUM;
		}
		else
		{
			return CP_DEFAULT_VAL_PAGE_NUM;
		}
		
                return $page_number;
	}

	/* Purpose 	 : Function to get the sort type of the page if exists.
	*
	*  Input params  : None		
	*
	*  Output Params : None
	*
	*  Return Vals   : 1.) Sort Type
	*
	*  To Do         : None
	*/
	private function getSortType()
	{
		$sortString = $this->CI->input->get(CP_REQUEST_VAR_NAME_SORT_TYPE);

		// if no sort type is found in the request parameters then return empty string
		if( !isset( $sortString ))
			return CP_DEFAULT_VAL_SORT;

		$sortList = $this->CI->config->item('CP_SORTING_LIST');

		// return the result
		if( ($res = array_search(strtolower($sortString), array_map('strtolower',$sortList) )) === FALSE )
			return CP_DEFAULT_VAL_SORT;
		else			
			return $sortList[$res];
	}

	/* Purpose 	 : Function to get the Naukri Learning Flag if exists.
	*
	*  Input params  : None		
	*
	*  Output Params : None
	*
	*  Return Vals   : 1.) Naukri Learning Flag
	*
	*  To Do         : None
	*/
	private function getNaukriLearning()
	{
		$nlString  = $this->CI->input->get(CP_REQUEST_VAR_NAME_NAUKRI_LEARNING);

		// if no page number is found in the request parameters then return 0(flag OFF)
		if( !isset( $nlString ))
			return CP_DEFAULT_VAL_NAUKRI_LEARNING;

		// return the result
		return intVal( $nlString ) == _ID_FLAG_OFF? _ID_FLAG_OFF : _ID_FLAG_ON;
	}

	/* Purpose	 : Function to check for the presence of type of value given to it and if it exists then returns the starting and ending indexes 
	*
	*  Input params  : 1.) $type    - Type of the value to be searched for the existence		
	*		   2.) $indexes - Reference of array of starting and ending indexes  
	*
	*  Output Params : None
	*
	*  Return Vals   : 1.) 0 - Item not found
			       1 - Item found
	*
	*  To Do         : None
	*/
	private function checkExistenceAndGetIndex( $type, &$indexes )
	{
		$start_index    = -1;
		$end_index      = -1;
		$not_found_flag =  1;
		switch( $type )
		{
			// for AFFILIATION
			case _ID_TYPE_AFFILIATION :
				$start_index = -1;

				if( ($end_index = strpos( $this->urlString, _ENT_AFFILIATION_END1 )) === FALSE )
				{
					if( ($end_index = strpos( $this->urlString, _ENT_AFFILIATION_END2 )) === FALSE )
					{
						$end_index = -1;
						$not_found_flag = 0;
					}
				}

				break;

			// for Fee
			case _ID_TYPE_FEE :
			 	$start_index = 0;
				if( ($start_index = strpos( $this->urlString, _ENT_FEE_START )) === FALSE )		
				{
					$not_found_flag = 0;
					break;
				}
				else
				{
					$start_index += strlen(_ENT_FEE_START);
				}
				break;
		
			// for exams
			case _ID_TYPE_EXAM :
				if( ($start_index = strpos( $this->urlString, _ENT_EXAM_START )) === FALSE )		
				{
					if(($start_index = strpos( $this->urlString, _ENT_EXAM_START1 )) === FALSE ){
						$not_found_flag = 0;
						break;						
					}else{
						$start_index += strlen(_ENT_EXAM_START1);
					}
				}
				else
				{
					$start_index += strlen(_ENT_EXAM_START);
				}
			
				if( ($end_index = strpos( $this->urlString, _ENT_EXAM_END )) === FALSE )
					$not_found_flag = 0;
				
				break;
			
			// for Location
			case _ID_TYPE_LOCATION :
				if( ($start_index = strpos( $this->urlString, _ENT_LOCATION_START1 )) === FALSE )		
				{
					if( ($start_index = strpos( $this->urlString, _ENT_LOCATION_START2 )) === FALSE )		
					{
						$not_found_flag = 0;
						//break;
					}
					else
					{	
						$start_index += strlen(_ENT_LOCATION_START2);
					}
				}
				else
				{
					$start_index += strlen(_ENT_LOCATION_START1);
				}
				//echo "<br/>Location Start : ".$start_index." and End : ".$end_index."<br";
			
				if( ($end_index = strpos( $this->urlString, _ENT_LOCATION_END1 )) === FALSE )		
				{
					//echo "Loc1 not found";
					if( ($end_index = strpos( $this->urlString, _ENT_LOCATION_END2 )) === FALSE )		
					{
						if( ($end_index = strpos( $this->urlString, _ENT_LOCATION_END3 )) === FALSE )		
						{
							//echo "Loc2 not found";
							$end_index = -1;
							break;
						}
					}
				}
				break;
				
			// for Courses
			// Check for Course along with sub category here
			case _ID_TYPE_COURSE :
				if( ($start_index = strpos( $this->urlString, _ENT_COURSE_MBA )) === FALSE )		
				{
					if( ($start_index = strpos( $this->urlString, _ENT_COURSE_BE_BTECH )) === FALSE )		
					{
						$start_index = -1;
						break;
					}
					else
					{
						$start_index--;
						$end_index = $start_index + strlen(_ENT_COURSE_BE_BTECH)+2;
					}
				}
				else
				{
					$start_index--;
					$end_index = $start_index + strlen(_ENT_COURSE_MBA)+2;
				}
				break;
		}
		$indexes = array( $start_index, $end_index );
			
		return $not_found_flag;
	}
	
	/* Purpose	 : Function to extract the part of the URL specified by the starting and ending indexes provided as parameter  
	*
	*  Input params  : 1.) $start_index  - Starting index of the sub-URL to be extracted		
	*		   2.) $end_index    - Ending index of the sub-URL to be extracted
	*		   3.) $subStringURL - Reference of the sub-URL to be extracted
	*	
	*  Output Params : None
	*
	*  Return Vals   : 1.) -1 = sub-URL cannot be extracted
	*		   2.) 1  = sub-URL extracted successfully
	*
	*  To Do         : None
	*/
	private function getSubStringURL( $start_index, $end_index, &$subStringURL )
	{
		if( $start_index < 0 && $end_index < 0)
			return -1;
	
		$subStringURL = ""; 

		$keyWords = explode( "-", $this->urlString );
	/*	if( $end_index != -1 )
			$subarr = array_slice( $keyWords, $start_index+1, $end_index-$start_index-1 );	
		else
			$subarr = array_slice( $keyWords, $start_index );	
	*/
		if( $end_index != -1 )
			$subarr = substr( $this->urlString, $start_index+1, $end_index-$start_index-2 );	
		else
			$subarr = substr( $this->urlString, $start_index+1 );
		
		$subStringURL = $subarr; 

		//for($i = $start_index; $i<$end_index; $i++)
		//	$subStringURL .= $keyWords[$i]." ";			

		return 1;
	}

	/* Purpose 	 : Function to prepare the exam list in the desired format and store that in Cache .
	*
	*  Input params  : None		
	*
	*  Output Params : None
	*
	*  Return Vals   : None
	*
	*  To Do         : None
	*/
	public function getExamList()
	{	
	
		// prepare the list of all exams
		$final_exam_list = array();
		if(count($this->exam_list) >0) 
		{
			foreach ($this->exam_list as $list) 
			{
				foreach ($list as $list_child) 
				{
					$exam_name = str_replace(array(' ','/','(',')',','), '-', $list_child['child']['acronym']);
					$exam_name = preg_replace('!-+!', '-', $exam_name);
					$final_exam_list[$list_child['child']['acronym']] = $exam_name;
				}
			}
		}
		return $final_exam_list;
	}

	private function prepareAndStoreExamListInCache()
	{
			// load the Category list client library
			$this->CI->load->library('category_list_client');
			$categoryClient = new Category_list_client();
			$exam_list = $categoryClient->getTestPrepCoursesList(1);

			// prepare the list of all exams
			$final_exam_list = array();
			if(count($exam_list) >0) 
			{
				foreach ($exam_list as $list) 
				{
					foreach ($list as $list_child) 
					{
						$exam_name = str_replace(array(' ','/','(',')',','), '-', $list_child['child']['acronym']);
						$exam_name = preg_replace('!-+!', '-', $exam_name);
						$final_exam_list[$list_child['child']['acronym']] = $exam_name;
					}
				}
			}
			//echo "<h1>Attention : Storing the Exam List in Cache</h1>";
			$this->listingCache->storeExam($final_exam_list, 'CP_EXAM_LIST');

	}

	/* Purpose	 : Function to identify the locality/city/state in priorities in the provided string  
	*
	*  Input params  : 1.) $locationStr  - Location String to be parsed		
	*		   2.) $locationVal  - Reference of the array containing the identified location
	*	
	*  Output Params : None
	*
	*  Return Vals   : 1.) -1 = Failure
	*		   2.) 1  = Success
	*
	*  To Do         : None
	*/
	public function identifyLocation( $locationStr, &$locationArr )
	{
		$localityId               = CP_DEFAULT_VAL_LOCALITY_ID;
		$localityName             = CP_DEFAULT_VAL_LOCALITY_NAME;
		$zoneId	                  = CP_DEFAULT_VAL_ZONE_ID;
		$zoneName                 = CP_DEFAULT_VAL_ZONE_NAME;
		$cityId                   = CP_DEFAULT_VAL_CITY_ID;
		$cityName                 = CP_DEFAULT_VAL_CITY_NAME;
		$stateId                  = CP_DEFAULT_VAL_STATE_ID;
		$stateName                = CP_DEFAULT_VAL_STATE_NAME;
                $countryId                = CP_DEFAULT_VAL_COUNTRY_ID;
                $countryName              = CP_DEFAULT_VAL_COUNTRY_NAME;
                $noLocationFoundFlag      = _ID_FLAG_OFF;

		if( empty($locationStr ))
			return 0;
		
		// check for Locality Name
		$locationKeywords = explode( "-", $locationStr);
		$localityList	  = $this->getLocationList(1);
		//todo : function
		// Check for locality
		$locality_found_flag = 0;
		$localityVal = array('',-1);
		$localitiesFound = array();
		for( $i = 0; $i < count($locationKeywords); $i++ )
		{
			for( $j = $i; $j<count($locationKeywords); $j++ )
			{
				$subLocationStr = implode("-", array_slice( $locationKeywords, $i, $j-$i+1));
				if( ($res = array_keys($localityList, $subLocationStr) ) != FALSE )
				{
					if(strlen($localityVal[0]) <= strlen($subLocationStr))
					{
						$localityVal = array($subLocationStr, $res[0]);
						$localitiesFound = $res;
					}
				}
			}
		}
		
		$citiesList = $this->getLocationList(2);

		$cityVal = array('',-1);
		
		/** Check for city in the case when :
		 * 1. Locality is not found, or
		 * 2. Multiple localities are found to resolve which locality is to be picked based on the city parsed.
		**/
		if( empty($localityVal[0]) || count($localitiesFound) > 1 )
		{
			for( $i = 0; $i < count($locationKeywords); $i++ )
			{
				for( $j = $i; $j<count($locationKeywords); $j++ )
				{
					$subLocationStr = implode("-", array_slice( $locationKeywords, $i, $j-$i+1));
					if( ($res = array_search($subLocationStr, $citiesList) ) != FALSE )
					{
						$cityVal = strlen($cityVal[0]) > strlen($subLocationStr) ? $cityVal : array($subLocationStr, $res);
					}
				}
			}
		}
		
		$stateList = $this->getLocationList(3);
		$stateVal = array('',-1);
		// Check for State
		if( empty($localityVal[0]) && empty($cityVal[0]) )
		{
			for( $i = 0; $i < count($locationKeywords); $i++ )
			{
				for( $j = $i; $j<count($locationKeywords); $j++ )
				{
					$subLocationStr = implode("-", array_slice( $locationKeywords, $i, $j-$i+1));
					if( ($res = array_search($subLocationStr, $stateList) ) != FALSE )
					{
						$stateVal = strlen($stateVal[0]) > strlen($subLocationStr) ? $stateVal : array($subLocationStr, 	$res);
					}
				}
			}
		}

		// Check for country(only india)
		if( $locationStr == strtolower(CP_DEFAULT_VAL_COUNTRY_NAME) )
		{
			$countryVal = array( CP_DEFAULT_VAL_COUNTRY_NAME, CP_DEFAULT_VAL_COUNTRY_ID );
		}

		$locationBuilder = new LocationBuilder;
		$locationRepository = $locationBuilder->getLocationRepository();
		// if single locality is found
		if( !empty($localityVal[0]) && count($localitiesFound) == 1 )
		{
			//if( count($localitiesFound) > 1 )
			$locationObj = $locationRepository->findLocality($localityVal[1]);
			$localityId  = $locationObj->getId();
			$localityName= $locationObj->getName();
			$zoneId      = $locationObj->getZoneId();
			$cityId      = $locationObj->getCityId();
			$stateId     = $locationObj->getStateId();
			//todo : empty check  for obj and add zone
			$locationObj = $locationRepository->findZone($zoneId);
			$zoneName    = $locationObj->getName();

			$locationObj = $locationRepository->findCity($cityId);
			$cityName    = $locationObj->getName();

			$locationObj = $locationRepository->findState($stateId);
			$stateName   = $locationObj->getName();
		}
		// For the cases when same locality name exists in more than one location
		else if( !empty($localityVal[0]) && count($localitiesFound) >= 1 )
		{
			$finalLocalityId = 0;
			if( !empty($cityVal[0] ))
			{
				foreach( $localitiesFound as $localityId )
				{
					$locationObj = $locationRepository->findLocality($localityId);
					if( $locationObj->getCityId() == $cityVal[1] )
						$finalLocalityId = $localityId ;
				}
			}
			if( !$finalLocalityId )
				$finalLocalityId  = $localityVal[1];
			$locationObj = $locationRepository->findLocality($finalLocalityId);
			$localityId  = $locationObj->getId();
			$localityName= $locationObj->getName();
			$zoneId      = $locationObj->getZoneId();
			$cityId      = $locationObj->getCityId();
			$stateId     = $locationObj->getStateId();
			//todo : empty check  for obj and add zone
			$locationObj = $locationRepository->findZone($zoneId);
			$zoneName    = $locationObj->getName();

			$locationObj = $locationRepository->findCity($cityId);
			$cityName    = $locationObj->getName();

			$locationObj = $locationRepository->findState($stateId);
			$stateName   = $locationObj->getName();
		}
		else if( !empty($cityVal[0]) )
		{
			$locationObj = $locationRepository->findCity($cityVal[1]);
			$localityId  = CP_DEFAULT_VAL_LOCALITY_ID;
			$localityName= CP_DEFAULT_VAL_LOCALITY_NAME;
			$cityId      = $locationObj->getId();
			$stateId     = $locationObj->getStateId();
			$cityName    = $locationObj->getName();

			$locationObj = $locationRepository->findState($stateId);
			$stateName   = $locationObj->getName();
		}
		else if( !empty($stateVal[0]) )
		{
			$locationObj = $locationRepository->findState($stateVal[1]);
			$localityId  = CP_DEFAULT_VAL_LOCALITY_ID;
			$localityName= CP_DEFAULT_VAL_LOCALITY_NAME;
			$cityId      = CP_DEFAULT_VAL_CITY_ID;
			$cityName    = CP_DEFAULT_VAL_CITY_NAME;

			$stateId     = $locationObj->getId();
			$stateName   = $locationObj->getName();
		}
		else if( !empty($countryVal[0]) )
		{
			$localityId  = CP_DEFAULT_VAL_LOCALITY_ID;
			$localityName= CP_DEFAULT_VAL_LOCALITY_NAME;
			$cityId      = CP_DEFAULT_VAL_CITY_ID;
			$cityName    = CP_DEFAULT_VAL_CITY_NAME;
			$stateId     = CP_DEFAULT_VAL_STATE_ID;
			$stateName   = CP_DEFAULT_VAL_STATE_NAME;
                        $countryId   = $countryVal[1];
                        $countryName = $countryVal[0];
		}
		else
		{
			$localityId  = CP_DEFAULT_VAL_LOCALITY_ID;
			$localityName= CP_DEFAULT_VAL_LOCALITY_NAME;
			$cityId      = CP_DEFAULT_VAL_CITY_ID;
			$cityName    = CP_DEFAULT_VAL_CITY_NAME;
			$stateId     = CP_DEFAULT_VAL_STATE_ID;
			$stateName   = CP_DEFAULT_VAL_STATE_NAME;
                        $countryId   = CP_DEFAULT_VAL_COUNTRY_ID;
                        $countryName = CP_DEFAULT_VAL_COUNTRY_NAME;
                        
                        $noLocationFoundFlag = _ID_FLAG_ON;
		}

		// prepare the location array to be returned
		$locationArr = array($localityId,
				     $localityName,
				     $zoneId,
				     $zoneName,
				     $cityId,
				     $cityName,
				     $stateId,
				     $stateName,
				     $countryId,
				     $countryName,
                                     $noLocationFoundFlag);

		return 1;
	}

	/* Purpose 	 : Function to sanitize the given URL.
	*
	*  Input params  : None
	*	
	*  Output Params : None
	*
	*  Return Vals   : 1.) Sanitized URL.
	*
	*  To Do         : None
	*/
	private function sanitizeURL( $urlString )
	{
		// sanitize the URL
		$urlString = str_replace(array(' ','/','(',')',','), '-', $urlString);
		$urlString = str_replace(".", "", $urlString); // eg. M.Phil = mphil
		$urlString = str_replace("&", "and", $urlString); 
		$urlString = preg_replace('!-+!', '-', $urlString);
		$urlString = strtolower(trim($urlString, '-'));

		// return the sanitized URL
		return $urlString;
	}

	/* Purpose 	 : Function to get the location lists like Locality,City,State list based on the input given 
	*
	*  Input params  : 1.) $type  - Type of the location list needed
	*	
	*  Output Params : None
	*
	*  Return Vals   : Prepared Location List
	*
	*  To Do         : None
	*/
	private function getLocationList($type)
	{
		$this->CI->load->builder('LocationBuilder','location');
		$locationBuilder = new LocationBuilder;
		$locationRepository = $locationBuilder->getLocationRepository();
		if($type == 1 )
			$localityObj = $locationRepository->getLocalities();
		else if($type == 2 )
			$localityObj = $locationRepository->getCities(2, true);
		else if($type == 3)
			$localityObj = $locationRepository->getStatesByCountry(2);
		$obj;
		foreach($localityObj as $key=>$value )
		{
			$obj[$value->getId()] = $this->sanitizeURL($value->getName()); 
		}
		return $obj;
	}
        
        private function getExamTypeMapping(  ) 
        {
        	$final_exam_list = array();
		if(count($this->exam_list) >0) {
			foreach ($this->exam_list as $list) {
				foreach ($list as $list_child) {
					//$final_exam_list[$list_child['acronym']][] = $list_child['child']['acronym'];
                                    $list_child['child']['acronym'] = $this->sanitizeURL($list_child['child']['acronym']);
                                    $final_exam_list[$list_child['child']['acronym']] = $list_child['acronym'];
				}
			}
		}
		//Entry for no exam required in MBA
		if(!empty($final_exam_list['MBA'])){
			$final_exam_list['MBA'][] = "No Exam Required";
		}
		return $final_exam_list;
	}

	private function getHideLocationLayer($subCategoryId){
		$reqParamsVal = (int) $this->CI->input->get("hl");
		
		if($subCategoryId){
			if($reqParamsVal  == 1 && ($subCategoryId == 23 || $subCategoryId == 56))
				return _ID_FLAG_ON;	
		}

		return _ID_FLAG_OFF;	
	}
	
};
// class definition end
