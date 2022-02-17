<?php
/*
Purpose       : To parse the URL and extract Course/Location/Affiliation/Exam/Fee from it.
	
Author 	      : Romil Goel/Nikita

Creation Date : 01-11-2013

To Do 	      : 1.) Identify the parts of the location string extracted for locality/city/state in respective priorities.
		2.) Replace the dummy data checks with the Database checks. 
*/

/* Purpose      : Class for parsing the URL provided to it and returning following values from the URl - Affiliation/Fee/Course/Location/Exam
*  
*/
class CategoryPageURLManager
{

//********* Data Member Definition ****************
	private $urlString;

	private $affiliationID;
	private $affiliationName;
	private $categoryID;
	private $categoryName;
	private $subCategoryID;
	private $subCategoryName;
	private $courseID;
	private $courseName;
	private $examName;
	private $feesValue;
	private $feesString;
	private $localityID;
	private $localityName;
	private $zoneID;
	private $zoneName;
	private $cityID;
	private $cityName;
	private $stateID;
	private $stateName;
	private $countryID;
	private $countryName;
    private $noLocationFoundFlag;
	private $top = "";

	// variables whose values comes from Request parameters
	private $otherExamScoreData;
	private $pageNumber;
	private $sortType;
	private $naukriLearning;
        
    private $urlParserObj;
    private $hideLocationLayer;
	
	private $CI;
	
//********* Member functions Definition ***********

	public function __construct()
        {
		// load category page config
		$this->CI =& get_instance();
		
                $this->CI->load->library('categoryList/CategoryPageURLParser');
                $this->urlParserObj = new CategoryPageURLParser();
		
		// load category page config
		$this->CI->config->load('categoryPageConfig');
                
                $this->init();
                
	}
        
        public function init()
        {
                $this->localityID           = CP_DEFAULT_VAL_LOCALITY_ID;
		$this->localityName         = CP_DEFAULT_VAL_LOCALITY_NAME;
		$this->zoneID               = CP_DEFAULT_VAL_ZONE_ID;
		$this->zoneName             = CP_DEFAULT_VAL_ZONE_NAME;
		$this->cityID               = CP_DEFAULT_VAL_CITY_ID;
		$this->cityID               = CP_DEFAULT_VAL_CITY_NAME;
		$this->cityID               = CP_DEFAULT_VAL_CITY_ID;
                $this->stateID              = CP_DEFAULT_VAL_STATE_ID;
		$this->stateName            = CP_DEFAULT_VAL_STATE_NAME;
                $this->countryID            = CP_DEFAULT_VAL_COUNTRY_ID;
                $this->countryName          = CP_DEFAULT_VAL_COUNTRY_NAME;
		
		$this->naukriLearning	    = CP_DEFAULT_VAL_NAUKRI_LEARNING;
		$this->sortType		    = CP_DEFAULT_VAL_SORT;
        }

	// Getter functions
	public function getAffiliationID()
	{
		return $this->affiliationID;
	}

	public function getAffiliationName()
	{
		return $this->affiliationName;
	}

	public function getCategoryID()
	{
		return $this->categoryID;
	}

	public function getCategoryName()
	{
		return $this->categoryName;
	}

	public function getSubCategoryID()
	{
		return $this->subCategoryID;
	}

	public function getSubCategoryName()
	{
		return $this->subCategoryName;
	}

	public function getCourseID()
	{
		return $this->courseID;
	}

	public function getCourseName()
	{
		return $this->courseName;
	}

	public function getExamName()
	{
		return $this->examName;
	}

	public function getFeesValue()
	{
		return $this->feesValue;
	}

	public function getLocalityID()
	{
		return $this->localityID;
	}

	public function getLocalityName()
	{
		return $this->localityName;
	}
        
	public function getZoneID()
	{
		return $this->zoneID;
	}

	public function getZoneName()
	{
		return $this->zoneName;
	}
        
	public function getCityID()
	{
		return $this->cityID;
	}

	public function getCityName()
	{
		return $this->cityName;
	}

	public function getStateID()
	{
		return $this->stateID;
	}

	public function getStateName()
	{
		return $this->stateName;
	}
        
        public function getCountryID()
	{
		return $this->countryID;
	}

	public function getCountryName()
	{
		return $this->countryName;
	}
        
        public function getOtherExamScoreData() 
        {
            return $this->otherExamScoreData;
        }
        
        public function getPageNumber() 
        {
            return $this->pageNumber;
        }
         
        public function getSortOrder() 
        {
            return $this->sortType;
        }
         
        public function getNaukriLearning() 
        {
            return $this->naukriLearning;
        }

        
        // Setter functions
        public function setAffiliationID( $input ) 
        {
            $affiliationList = $this->CI->config->item("CP_AFFILIATION_LIST");
            
            $input = intVal($input);
            
            if(!array_key_exists($input, $affiliationList) )
                    return _ID_FLAG_OFF;
            else
            {
                $this->affiliationID    = $input;
                $this->affiliationName  = $affiliationList[$input];
            }
            return _ID_FLAG_ON;
        }
        
        public function setAffiliationName( $input ) 
        {
            $affiliationList = $this->CI->config->item("CP_AFFILIATION_LIST");
            
            $input = strtolower($input);
            
            if(($key = array_search($input, $affiliationList)) === FALSE )
            {
                $this->affiliationName    = "";
            }
            else
            {
                $this->affiliationName    = $input;
                $this->affiliationID      = $key;
            }
            return _ID_FLAG_ON;
        }

        public function setExamName( $input ) 
        {
            $examList = $this->urlParserObj->getExamList();
            $examList = array_map("strtoupper", $examList);
            
            if( $input == "" || strtolower($input) == "no exam required" )
            {
                 $this->examName = "";
                 return _ID_FLAG_ON;
            }
            
            $input = str_replace(array(' ','/','(',')',','), '-', $input);
	    $input = preg_replace('!-+!', '-', $input);
           
		 if( array_search(strtoupper($input), $examList) === FALSE )
                return _ID_FLAG_OFF;
            else
                $this->examName = array_search(strtoupper($input), $examList);

            return _ID_FLAG_ON;
        }
        
        public function setFeesValue( $input ) 
        {
            $feeList = $this->CI->config->item("CP_FEES_RANGE");
            $input   = intVal($input);
            
            if( $input == "" )
            {
                $this->feesValue = $input;
                return _ID_FLAG_ON;
            }
                  
            if( !array_key_exists( $input, $feeList['RS_RANGE_IN_LACS']) )
                return _ID_FLAG_OFF;
            else
                $this->feesValue = $input;

            return _ID_FLAG_ON;
        }
        
        public function setOtherExamScoreData( $input ) 
        {
               if( is_array($input) === FALSE )
                   return _ID_FLAG_OFF;
               else
                   $this->otherExamScoreData = $input;
               
               return _ID_FLAG_ON;
        }
        
        public function setPageNumber( $input )
        {
            if( is_numeric($input) === FALSE )
                return _ID_FLAG_OFF;
            
            $this->pageNumber = intVal($input);
            
            if( $this->pageNumber < 1 )
                $this->pageNumber = CP_DEFAULT_VAL_PAGE_NUM;
            
            return _ID_FLAG_ON;
        }
        
        public function setSortOrder( $input )
        {
            if( empty($input) )
                return _ID_FLAG_OFF;
            
            $sortList = $this->CI->config->item("CP_SORTING_LIST");
            if( ($key = array_search(strtolower($input), array_map("strtolower", $sortList))) === FALSE )
                    return _ID_FLAG_OFF;
            else
                $this->sortType = $sortList[$key];
            
            return _ID_FLAG_ON;
        }
        
        public function setNaukriLearning( $input ) 
        {
            if(is_numeric($input) === FALSE )
                return _ID_FLAG_OFF;
            
            $this->naukriLearning = ( empty($input) ? _ID_FLAG_OFF : _ID_FLAG_ON );
            
            return _ID_FLAG_ON;
        }
   
/*        public function setCatSubcatCourse( $categoryId, $subCatId, $courseId )
        {
            if( $courseId   == _ID_EMPTY_PARAMETER &&
                $subCatId   == _ID_EMPTY_PARAMETER &&
                $categoryId == _ID_EMPTY_PARAMETER )
            {
                return _ID_FLAG_OFF;
            }
            
            if( $categoryId > 0 )
            {
                $this->CI->load->repository('CategoryRepository','categoryList');
		$this->CI->load->builder('CategoryBuilder','categoryList');
		$this->CI->load->entity('Category','categoryList');
                
       		$catBuilderObj	= new CategoryBuilder;
		$catRepoObj 	= $catBuilderObj->getCategoryRepository();
                try{
                    $catObj 	= $catRepoObj->find($categoryId);
                }catch(Exception $ex)
                { return _ID_FLAG_OFF; }
                
                $this->categoryID   = $catObj->getId();
                $this->categoryName = $catObj->getName();

            }
            else
            {
                    
            }
            
            if( $subCatId == _ID_EMPTY_PARAMETER )
            {
                $this->subCategoryID   = -1;
                $this->subCategoryName = _ID_EMPTY_STRING;
            }
            
            //if( $sub)
         }
 */
        
        public function setLocality( $localityId , $setParentFlag = 1)
        {
            if( intVal($localityId) <= 0 )
	    {
	   	$this->localityID   = CP_DEFAULT_VAL_LOCALITY_ID;
                $this->localityName = CP_DEFAULT_VAL_LOCALITY_NAME;
		return _ID_FLAG_ON;
	    }
            
            $this->CI->load->builder('LocationBuilder','location');
            $locationBuilder    = new LocationBuilder;
            $locationRepository = $locationBuilder->getLocationRepository();
            
            $localityObj        = $locationRepository->findLocality($localityId);
            $this->localityID   = $localityObj->getId();
            $this->localityName = $localityObj->getName();
            
	    if($setParentFlag)
		$this->setZone($localityObj->getZoneId() , 1, 0 );
                        
            return _ID_FLAG_ON;
        }

        public function setZone( $zoneId , $setParentFlag = 1, $clearChildFlag = 0 )
        {
            if( intVal($zoneId) <= 0 )
	    {
                $this->zoneID       = CP_DEFAULT_VAL_ZONE_ID;
                $this->zoneName     = CP_DEFAULT_VAL_ZONE_NAME;
		return _ID_FLAG_ON;
	    }
            
            $this->CI->load->builder('LocationBuilder','location');
            $locationBuilder    = new LocationBuilder;
            $locationRepository = $locationBuilder->getLocationRepository();
            
            $zoneObj        = $locationRepository->findZone($zoneId);
            $this->zoneID   = $zoneObj->getId();
            $this->zoneName = $zoneObj->getName();
            
            if( $clearChildFlag )
            {
                $this->localityID   = CP_DEFAULT_VAL_LOCALITY_ID;
                $this->localityName = CP_DEFAULT_VAL_LOCALITY_NAME;
            }

            //$this->setCity($zoneObj->getCityId() , 1 , 0 );
                        
            return _ID_FLAG_ON;
        }
                
        public function setCity( $cityId, $setParentFlag = 1, $clearChildFlag = 0 )
        {
            if( intVal($cityId) <= 1 )
	    {
                $this->cityID       = CP_DEFAULT_VAL_CITY_ID;
                $this->cityName     = CP_DEFAULT_VAL_CITY_NAME;
		return _ID_FLAG_ON;
	    }
            
            $this->CI->load->builder('LocationBuilder','location');
            $locationBuilder    = new LocationBuilder;
            $locationRepository = $locationBuilder->getLocationRepository();
            
            $cityObj        = $locationRepository->findCity($cityId);
            $this->cityID   = $cityObj->getId();
            $this->cityName = $cityObj->getName();
            
            if( $clearChildFlag )
            {
                $this->localityID   = CP_DEFAULT_VAL_LOCALITY_ID;
                $this->localityName = CP_DEFAULT_VAL_LOCALITY_NAME;
                $this->zoneID       = CP_DEFAULT_VAL_ZONE_ID;
                $this->zoneName     = CP_DEFAULT_VAL_ZONE_NAME;
            }

	    if($setParentFlag)
		$this->setState  ($cityObj->getStateId() , 1 , 0 );
                        
            return _ID_FLAG_ON;
        }
        
        public function setState( $stateId ,$setParentFlag = 1, $clearChildFlag = 0 )
        {
            if( intVal($stateId) <= 1 )
	    {
                $this->stateID      = CP_DEFAULT_VAL_STATE_ID;
                $this->stateName    = CP_DEFAULT_VAL_STATE_NAME;
		return _ID_FLAG_ON;
	    }
            
            $this->CI->load->builder('LocationBuilder','location');
            $locationBuilder    = new LocationBuilder;
            $locationRepository = $locationBuilder->getLocationRepository();
            
            $stateObj        = $locationRepository->findState($stateId);
            $this->stateID   = $stateObj->getId();
            $this->stateName = $stateObj->getName();
            
            if( $clearChildFlag )
            {                
                $this->localityID   = CP_DEFAULT_VAL_LOCALITY_ID;
                $this->localityName = CP_DEFAULT_VAL_LOCALITY_NAME;
                $this->zoneID       = CP_DEFAULT_VAL_ZONE_ID;
                $this->zoneName     = CP_DEFAULT_VAL_ZONE_NAME;
                $this->cityID       = CP_DEFAULT_VAL_CITY_ID;
                $this->cityName     = CP_DEFAULT_VAL_CITY_NAME;
            }

	    if($setParentFlag)
		$this->setCountry($stateObj->getCountryId(), 1, 0 );
                        
            return _ID_FLAG_ON;
        }
                
        public function setCountry( $countryId ,$setParentFlag = 1, $clearChildFlag = 0 )
        {
            if( intVal($countryId) <= 1 )
	    {
                $this->countryID      = CP_DEFAULT_VAL_COUNTRY_ID;
                $this->countryName    = CP_DEFAULT_VAL_COUNTRY_NAME;
		return _ID_FLAG_ON;
	    }
            
            //$this->CI->load->builder('LocationBuilder','location');
            //$locationBuilder    = new LocationBuilder;
            //$locationRepository = $locationBuilder->getLocationRepository();
            //
            //$countryObj         = $locationRepository->findCountry($countryId);
            $this->countryID    = CP_DEFAULT_VAL_COUNTRY_ID; // $countryObj->getId();
            $this->countryName  = CP_DEFAULT_VAL_COUNTRY_NAME; // $countryObj->getName();
                       
            if( $clearChildFlag )
            {                
                $this->localityID   = CP_DEFAULT_VAL_LOCALITY_ID;
                $this->localityName = CP_DEFAULT_VAL_LOCALITY_NAME;
                $this->zoneID       = CP_DEFAULT_VAL_ZONE_ID;
                $this->zoneName     = CP_DEFAULT_VAL_ZONE_NAME;
                $this->cityID       = CP_DEFAULT_VAL_CITY_ID;
                $this->cityName     = CP_DEFAULT_VAL_CITY_NAME;
                $this->stateID      = CP_DEFAULT_VAL_STATE_ID;
                $this->stateName    = CP_DEFAULT_VAL_STATE_NAME;
            }

            return _ID_FLAG_ON;
        }
        
        public function setCourse( $courseId )
        {
                if( !is_numeric($courseId) )
                    return _ID_FLAG_OFF;
                
                // if course-id is 1 then set the course name as empty and do not update the parent values
                if( intVal($courseId) == CP_DEFAULT_VAL_COURSE_ID )
                {
                    $this->courseID   = CP_DEFAULT_VAL_COURSE_ID;
                    $this->courseName = "";
                    return _ID_FLAG_ON;
                }
            
            	$this->CI->load->repository('LDBCourseRepository','LDB');
		$this->CI->load->builder('LDBCourseBuilder','LDB');
		$this->CI->load->entity('LDBCourse','LDB');

		$builderObj = new LDBCourseBuilder;
		$repoObj    = $builderObj->getLDBCourseRepository();
		$courseObj = $repoObj->find($courseId);
                //_p($courseObj);
                $this->courseID     = $courseObj->getId();
                if(strtolower($courseObj->getSpecialization()) != 'all')
                    $this->courseName = $courseObj->getSpecialization ();
                else
                    $this->courseName   = $courseObj->getCourseName();
                
                $this->setSubCategory($courseObj->getSubCategoryId() ,1 , 0);
                
                return _ID_FLAG_ON;
        }
        
        public function setSubCategory( $subCatId , $setParentFlag = 1, $clearChildFlag = 0 )
        {
                if( !is_numeric($subCatId) )
                    return _ID_FLAG_OFF;

                // if sub-categoryid is 1 then set the course name as empty and do not update the parent values
                if( intVal($subCatId) == CP_DEFAULT_VAL_SUB_CATEGORY_ID )
                {
                    $this->subCategoryID   = CP_DEFAULT_VAL_SUB_CATEGORY_ID;
                    $this->subCategoryName = "";
                    return _ID_FLAG_ON;
                }
                
		$this->CI->load->repository('CategoryRepository','categoryList');
		$this->CI->load->builder('CategoryBuilder','categoryList');
		$this->CI->load->entity('Category','categoryList');

		$builderObj	= new CategoryBuilder;
		$repoObj 	= $builderObj->getCategoryRepository();
		$subCatObj 	= $repoObj->find($subCatId);
                
                $this->subCategoryID   = $subCatObj->getId();
                $this->subCategoryName = $subCatObj->getName(); 
                
                if( $clearChildFlag )
                {
                    $this->courseID   = CP_DEFAULT_VAL_COURSE_ID;
                    $this->courseName = _ID_EMPTY_STRING;
                }
                //_p($subCatObj);
                $this->setCategory($subCatObj->getParentId() ,1 , 0 );
                
                return _ID_FLAG_ON;
        }
                
        public function setCategory( $categoryId , $setParentFlag = 1, $clearChildFlag = 0 )
        {
                if( !is_numeric($categoryId) )
                    return _ID_FLAG_OFF;
                
		$this->CI->load->repository('CategoryRepository','categoryList');
		$this->CI->load->builder('CategoryBuilder','categoryList');
		$this->CI->load->entity('Category','categoryList');

		$builderObj	= new CategoryBuilder;
		$repoObj 	= $builderObj->getCategoryRepository();
		$categoryObj 	= $repoObj->find($categoryId);
                $this->categoryID   = $categoryObj->getId();
                $this->categoryName = $categoryObj->getName();
                                
                if( $clearChildFlag )
                {
                    $this->courseID         = CP_DEFAULT_VAL_COURSE_ID;
                    $this->courseName       = _ID_EMPTY_STRING;
                    $this->subCategoryID    = CP_DEFAULT_VAL_SUB_CATEGORY_ID;
                    $this->subCategoryName  = _ID_EMPTY_STRING;
                }

                return _ID_FLAG_ON;
        }
        
	/* Purpose : Function to parse the URL and identify values like Affiliation/Exam/Course/Fee/Location. 
	*
	*  Input params  : 1.) $urlStr - URL to be parsed		
	*
	*  Output Params : None
	*
	*  Return Vals   : 1.) Array consisting of following values Affiliation/Exam/Course/Fee/Location from the URL. 
	*
	*  To Do         : None
	*/
	public function URLParser( $urlStr )
	{
                    
        $dataArr                   = $this->urlParserObj->parse($urlStr);
        
        $this->examName            = $dataArr['examName'];
        $this->courseName          = $dataArr['courseName'];
        $this->affiliationID       = (int) $dataArr['affiliationData'][0];		
        $this->affiliationName     = $dataArr['affiliationData'][1];		
        $this->feesValue           = (int) $dataArr['feesValue'];
        
        $this->otherExamScoreData  = $dataArr['otherExamScoreData'];
        $this->pageNumber          = $dataArr['pageNumber'];
        $this->sortType            = $dataArr['sortType'];
        $this->naukriLearning      = $dataArr['naukriLearning'];
        
        $this->categoryID          = (int) $dataArr['categoryID'];
        $this->categoryName        = $dataArr['categoryName'];
        $this->subCategoryID       = (int) $dataArr['subCategoryID'];
        $this->subCategoryName     = $dataArr['subCategoryName'];
        $this->courseID            = (int) $dataArr['courseID'];
        $this->courseName          = $dataArr['courseName'];
        
        $this->localityID          = (int) $dataArr['localityID'];
        $this->localityName        = $dataArr['localityName'];
        $this->zoneID              = (int) $dataArr['zoneID'];
        $this->zoneName            = $dataArr['zoneName'];
        $this->cityID              = (int) $dataArr['cityID'];
        $this->cityName            = $dataArr['cityName'];
        $this->stateID             = (int) $dataArr['stateID'];
        $this->stateName           = $dataArr['stateName'];
        $this->countryID           = (int) $dataArr['countryID'];
        $this->countryName         = $dataArr['countryName'];
        $this->noLocationFoundFlag = $dataArr['noLocationFoundFlag'];
        $this->hideLocationLayer   = $dataArr['hideLocationLayer'];

		// return the output	
		return 1;
	}

    /**
     * Purpose to set non ctpg page (i.e management) url data into url manager request
     * @author Aman Varshney <varshney.aman@gmail.com>
     * @date   2015-08-13
     * @param  [type]     $categoryPageRequest
     */
    public function setDataInUrlManager($categoryPageRequest){
        $this->setCategory($categoryPageRequest->getCategoryId());
        $this->setSubCategory($categoryPageRequest->getSubCategoryId());
        $this->setCourse($categoryPageRequest->getLDBCourseId());
        $this->setLocality($categoryPageRequest->getLocalityId());
        $this->setZone($categoryPageRequest->getZoneId());
        $this->setCity($categoryPageRequest->getCityId(),1);
        $this->setState($categoryPageRequest->getStateId());
        $this->setCountry($categoryPageRequest->getCountryId());
        $this->noLocationFoundFlag = 0;
        $this->sortType = $categoryPageRequest->getSortOrderValue();        
    }

	/* Purpose : It will return an araray key=>value  pair of class variables and their values.	 
	*
	*  Input params  : None		
	*
	*  Output Params : None
	*
	*  Return Vals   : 1.) Array consisting of class variables and their values.
	*
	*  To Do         : None
	*/
	public function getURLRequestData()
	{
		$valArr = array(
                "affiliationId"       => $this->affiliationID,
                "affiliationName"     => $this->affiliationName,
                "feesValue"           => $this->feesValue,
                "examName"            => $this->examName,
                "otherExamScoreData"  => $this->otherExamScoreData,
                "pageNumber"          => $this->pageNumber,
                "sortType"            => $this->sortType,
                "naukriLearning"      => $this->naukriLearning,
                "categoryID"          => $this->categoryID,
                "categoryName"        => $this->categoryName,
                "subCategoryID"       => $this->subCategoryID,
                "subCategoryName"     => $this->subCategoryName,
                "courseID"            => $this->courseID,
                "courseName"          => $this->courseName,
                "localityID"          => $this->localityID,
                "localityName"        => $this->localityName,
                "zoneID"              => $this->zoneID,
                "zoneName"            => $this->zoneName,
                "cityID"              => $this->cityID,
                "cityName"            => $this->cityName,
                "stateID"             => $this->stateID,
                "stateName"           => $this->stateName,
                "countryID"           => $this->countryID,
                "countryName"         => $this->countryName,
                "noLocationFoundFlag" => $this->noLocationFoundFlag,
                "hideLocationLayer"   => $this->hideLocationLayer
				);
		return ($valArr);
	}

    public function convertFeeNumericToString()
    {
        $feesValue = $this->feesValue;
        if($feesValue > 0)
        {
            switch($feesValue/100000)
            {
                case 1: $this->feesString = "1 Lakh"; break;
                case 2: $this->feesString = "2 Lacs"; break;
                case 5: $this->feesString = "5 Lacs"; break;
                case 7: $this->feesString = "7 Lacs"; break;
                case 10: $this->feesString = "10 Lacs"; break;
				default: $this->feesString = "";
            }
        }
        else
            $this->feesString = "";
    }
    
    public function getURL()
    {
        $affiliationName    = $this->affiliationName;
        $categoryName       = $this->categoryName;
        $categoryID         = $this->categoryID;
        $subCategoryName    = $this->subCategoryName;
        $subCategoryID      = $this->subCategoryID;
        $courseName         = $this->courseName;
        $courseID           = $this->courseID;
        $examName           = $this->examName;
        $feesValue          = $this->feesValue;
        $localityName       = $this->localityName;
        $localityID         = $this->localityID;
        $cityName           = $this->cityName;
        $cityID             = $this->cityID;
        $stateName          = $this->stateName;
        $stateID            = $this->stateID;
        $countryName        = $this->countryName;
        $top                = $this->top;
        $otherExamScoreData = $this->otherExamScoreData;
        $sortType           = $this->sortType;
        $naukriLearning     = $this->naukriLearning;
        $pageNumber         = $this->pageNumber;
        $hideLocationLayer  = $this->hideLocationLayer;
        
        $this->convertFeeNumericToString();
        $feesString = $this->feesString;
        $feesString = str_replace(" ","",$feesString);
        
        global $categoryURLPrefixMapping, $countryURLPrefixMapping, $regionURLPrefixMapping;
        
        $affiliationName = $this->formatAffiliationName($affiliationName);
        
        if($top)
        {
            $top = $top." ";
        }
        
        $subCategoryName = $this->checkAndFormatSubcategory($subCategoryID, $subCategoryName, $categoryID, $categoryName);
        
        if($courseName && $courseID > 1)
        {
            $courseName = "-in-".$courseName;
            $this->CI->load->repository('LDBCourseRepository','LDB');
            $this->CI->load->builder('LDBCourseBuilder','LDB');
            $this->CI->load->entity('LDBCourse','LDB');

            $builderObj = new LDBCourseBuilder;
            $repoObj    = $builderObj->getLDBCourseRepository();
            $courseObj  = $repoObj->find($courseID);
                if(strtolower($courseObj->getSpecialization()) == 'all')
                    $courseName = "";
            // if($courseID == 2 || $courseID == 52)
            //     $courseName = "";
        }
        
        $locationName = $this->checkAndFormatLocation($localityName, $localityID, $cityName, $cityID, $stateName, $stateID, $countryName);
        
        $suffixMarketing = $this->formatOtherExamForUrl();
        
        if($examName)
        {
            $examName = "-"._ENT_LOCATION_END3."-".$examName."-score";
        }
        
        if($feesValue > 0 && $feesString)
        {
            $feesString = "-"._ENT_LOCATION_END2."-".$feesString;
        }
        
		$originalPageNumber = 0;
        if($pageNumber > 1){
				$originalPageNumber = $pageNumber;
				$pageNumber = "-".$pageNumber;	
		} else {
            $pageNumber = "";
		}
        if(!empty($affiliationName) && $categoryID  == 3)
            $url = $affiliationName.$top.$subCategoryName.$courseName."-"._ENT_LOCATION_START1."-".$locationName.$examName.$feesString.$pageNumber;
        elseif($affiliationName)
            $url = $affiliationName.$top.$subCategoryName.$courseName."-"._ENT_LOCATION_START1."-".$locationName.$examName.$feesString.$pageNumber."-".CP_CATEGORY_PAGE_URL_IDENTIFIER;
        elseif($categoryID  == 3)
            $url = $affiliationName.$top.$subCategoryName.$courseName."-"._ENT_LOCATION_START1."-".$locationName.$examName.$feesString.$pageNumber;
        else
            $url = $affiliationName.$top.$subCategoryName.$courseName."-"._ENT_LOCATION_START2."-".$locationName.$examName.$feesString.$pageNumber."-".CP_CATEGORY_PAGE_URL_IDENTIFIER;
            
        $url = str_replace(array(' ','/','(',')',',','+'), '-', $url);
        $url = str_replace('&', 'and', $url);
        
        //handling query string if sort & naukri learning is 0
        $queryParams = array();
        if(!empty($suffixMarketing))
            $queryParams[] = $suffixMarketing;
        if($sortType != 'none')
            $queryParams[] = CP_REQUEST_VAR_NAME_SORT_TYPE."=".$sortType;
        if($naukriLearning != 0)
            $queryParams[] = CP_REQUEST_VAR_NAME_NAUKRI_LEARNING."=".$naukriLearning;
        if($_REQUEST['profiling'])
            $queryParams[] = "profiling=".$_REQUEST['profiling'];
        if($hideLocationLayer && $cityID == 1 && $stateID == 1)
            $queryParams[] = "hl=".$hideLocationLayer;
	    
	// append query parameters of other exam name and score in category page url
	if($otherExamScoreData)
	{
		$examList = $this->urlParserObj->getExamList();
		$otherexamParam = array();
		foreach($otherExamScoreData as $otherExamScoreDataRow)
		{
			$otherexamParam[] = $examList[$otherExamScoreDataRow[0]].CP_OTHER_EXAM_NAME_AND_SCORE_SEPERATOR.$otherExamScoreDataRow[1];
		}
		$otherexamParam = implode(CP_OTHER_EXAM_AND_EXAM_SEPERATOR ,$otherexamParam);
		$queryParams[] 	= CP_REQUEST_VAR_NAME_EXAM."=".$otherexamParam;
	}
        
        $queryParams = implode("&",$queryParams);
        
        if($queryParams != '')
            $suffix = '?'.$queryParams;
        //handling of the query string ends here
        
        /**
         * Code to get directory name from category Object
         * Author Aman Varshney
         */
        $this->CI->load->builder('CategoryBuilder','categoryList');
        $builderObj    = new CategoryBuilder;
        $repoObj       = $builderObj->getCategoryRepository();
        $subCatObj     = $repoObj->find($subCategoryID);
        $directoryName = $subCatObj->getSeoUrlDirectoryName();
        if(!empty($directoryName))
            $prefix = $directoryName.'/colleges/';
        else
            $prefix = '';

        $url = $prefix.$url.$suffix;
        $url = strtolower(trim($url, '-'));
        $url = preg_replace('!-+!', '-', $url);
	
	// to allow percentile values to be 98.99
	if(!$otherExamScoreData)
		$url = str_replace('.', '', $url);

   
    // to allow query params if the user comes through registration layer
    if($_REQUEST['source']){
        if (strpos($url, '?') !== false) {
            $url = $url."&source=".$_REQUEST['source'];
        }else{
            $url = $url."?source=".$_REQUEST['source'];
        }
    }

    if($_REQUEST['newUser']){
        if (strpos($url, '?') !== false) {
            $url = $url."&newUser=".$_REQUEST['newUser'];
        }else{
            $url = $url."?newUser=".$_REQUEST['newUser'];
        }
    }      
        
        $domainPrefix = base_url();
        $domainPrefix = trim($domainPrefix, '/');
        
        if(!$subCategoryName || !$locationName)
        {
           $url = "";
        }
        
        if($naukriLearning) {
                $domainPrefix = NAUKRI_SHIKSHA_HOME;
        }
        else if($countryId > 2) {
                $domainPrefix = $countryURLPrefixMapping[$countryId];
                $url = "";
        }
        else if($categoryID > 1) {
                $domainPrefix = $categoryURLPrefixMapping[$categoryID];
        }
        
		$urlData['suffix'] = "/".$url;
        //if(( $subCategoryID == 1 || $subCategoryID == 23) && $cityID == 1 && $stateID == 1 && $this->countryID == 2 && (empty($originalPageNumber) || $originalPageNumber < 2)  && SHIKSHA_ENV != 'dev' && $courseID == 1) {
        if(( $subCategoryID == 1) && $cityID == 1 && $stateID == 1 && $this->countryID == 2 && (empty($originalPageNumber) || $originalPageNumber < 2)  && SHIKSHA_ENV != 'dev' && $courseID == 1) {
            //Code commented to redirect mba.shiksha.com to shiksha.com/mba/colleges/mba-colleges-in-india by Aman Varshney
            /*if($subCategoryID == 23){
				if(empty($affiliationName) && empty($examName) && empty($feesString) && empty($otherExamScoreData)){
						$url = $domainPrefix;	
				} else {
						$url = $domainPrefix.'/'.$url;
				}
			} else {*/
				$url = $domainPrefix;
			//}
        } else {
            $url = $domainPrefix.'/'.$url;
        }
        	   
        $urlData['domainPrefix'] = $domainPrefix;
        $urlData['url'] = $url;
		//error_log("check if here....domainPrefix: ".$domainPrefix);
        //error_log("check if here....URL: ".$url);
        return $urlData;
    }
    
    public function getMetaData($numResults = 0)
    {
        $affiliationName    = $this->affiliationName;
        $categoryID         = $this->categoryID;
        $categoryName       = $this->categoryName;
        $subCategoryName    = $this->subCategoryName;
        $subCategoryID      = $this->subCategoryID;
        $courseName         = $this->courseName;
        $courseID           = $this->courseID;
        $examName           = $this->examName;
        $feesValue          = $this->feesValue;
        $localityName       = $this->localityName;
        $localityID         = $this->localityID;
        $cityName           = $this->cityName;
        $cityID             = $this->cityID;
        $stateName          = $this->stateName;
        $stateID            = $this->stateID;
        $countryName        = $this->countryName;
        $top                = $this->top;
        $otherExamScoreData = $this->otherExamScoreData;
        $pageNumber         = $this->pageNumber;
        
        $this->convertFeeNumericToString();
        $feesString         = $this->feesString;
        
        $subCategoryName    = $this->checkAndFormatSubcategory($subCategoryID, $subCategoryName, $categoryID, $categoryName);
        
        if(!$subCategoryName)
            return false;
        /*check added to change the text from btech to engineering on category pages for title and description
        Story Id : LF-2875
        Changed By: Aman Varshney
        */
        if($subCategoryID == ENGINEERING_SUBCAT_ID)
            $subCategoryName ='Engineering';


        if($courseName && $courseID > 1)
        {
            $courseName       = ucfirst($courseName);
            $courseNameTitle  = " in ".$courseName;
            $courseNameDscptn = " ".$courseName;
            if($courseID == 2 || $courseID == 52)
            {
                $courseName       = "";
                $courseNameTitle  = "";
                $courseNameDscptn = "";
            }
        }
        
        $locationName = $this->checkAndFormatLocation($localityName, $localityID, $cityName, $cityID, $stateName, $stateID, $countryName);
        if(!$locationName)
            return false;
        
        if($pageNumber > 1)
        {
            $pageNumberFormatted = "Page ".$pageNumber." - ";
        }
		else
			$pageNumberFormatted = "";

        $pluralClgTxtSufix = '';
        if($numResults > 1)
            $pluralClgTxtSufix = 's';
        
        if(!$top)
        {
            $affiliationName = $this->formatAffiliationName($affiliationName);
            if($affiliationName)
            {
                if($examName)
                {
                    if($feesValue > 0 && $feesString)
                    {
                        $title = $pageNumberFormatted.$affiliationName.$subCategoryName.$courseNameTitle." colleges in ".$locationName.", accepts ".$examName." score & fees upto ".$feesString;
                        $description = $pageNumberFormatted."Find ".$affiliationName.$subCategoryName.$courseNameDscptn." courses in ".$locationName.". Colleges accept ".$examName." scores. Fees below ".$feesString.". More courses & colleges on Shiksha.com";
                    }
                    else
                    {
                        $title = $pageNumberFormatted.$affiliationName.$subCategoryName.$courseNameTitle." colleges in ".$locationName.", accepts ".$examName." score";
                        $description = $pageNumberFormatted."Enroll at ".$affiliationName.$subCategoryName.$courseNameDscptn." courses in ".$locationName.". Colleges accept ".$examName." scores. More courses & colleges on Shiksha.com";
                    }
                }
                else
                {
                    if($feesValue > 0 && $feesString)
                    {
                        $title = $pageNumberFormatted.$affiliationName.$subCategoryName.$courseNameTitle." colleges in ".$locationName.", fees upto ".$feesString;
                        $description = $pageNumberFormatted."Searching for ".$affiliationName.$subCategoryName.$courseNameDscptn." courses in ".$locationName.". Fees below ".$feesString.". More courses & colleges on Shiksha.com";
                    }
                    else
                    {
                        $title = $pageNumberFormatted.$affiliationName.$subCategoryName.$courseNameTitle." colleges in ".$locationName;
                        $description = $pageNumberFormatted."Get the best ".$affiliationName.$subCategoryName.$courseNameDscptn." courses in ".$locationName.". More courses & colleges on Shiksha.com";
                    }
                }
            }
            else
            {
                if($examName)
                {
                    if($feesValue > 0 && $feesString)
                    {
                        //Location +exam+spec+fees
                        if($subCategoryName == 'MBA')
                        {
                            $title = $subCategoryName.$courseNameTitle." college".$pluralClgTxtSufix." in ".$locationName." accepting ".$examName." scores with fees upto ".$feesString." | Shiksha.com";
                            $description = "View ".$numResults." ".$subCategoryName.$courseNameTitle." college".$pluralClgTxtSufix." in ".$locationName." accepting ".$examName." scores with fees upto ".$feesString.", check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.";
                        } 
                        else 
                        {
                            $title = $pageNumberFormatted."Apply to ".$subCategoryName.$courseNameTitle." courses in ".$locationName.". Accepts ".$examName." score & Fees upto ".$feesString.".";
                            $description = "Apply for Best ".$subCategoryName." courses in ".$locationName.". Find the list of colleges that accept ".$examName." Score & have fees below ".$feesString.".";

                        }
                    }
                    //LOC + SPEC + EXAM
                    else if($courseNameTitle)
                    {
                        $courseNameTitle = trim(str_replace("in ", "", $courseNameTitle));
                        if($subCategoryName == 'MBA'){
                            $title = $subCategoryName." in ".$courseNameTitle." college".$pluralClgTxtSufix." in ".$locationName." accepting ".$examName." scores | Shiksha.com";
                            $description ="View ".$numResults." ".$subCategoryName." in ".$courseNameTitle." college".$pluralClgTxtSufix." in ".$locationName." accepting ".$examName.", check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details";                            
                        }else{
                            $title = $courseNameTitle." college".$pluralClgTxtSufix." in ".$locationName." accepting ".$examName." scores | Shiksha.com";
                            $description ="View ".$numResults." ".$courseNameTitle." college".$pluralClgTxtSufix." in ".$locationName." accepting ".$examName.", check courses, admissions, fees, reviews and more";                            
                        }
                    }
                    else
                    {
                        //Location + exam
                        if($subCategoryName == 'MBA')
                        {
                            $title = $subCategoryName.$courseNameTitle." college".$pluralClgTxtSufix." in ".$locationName." accepting ".$examName." score | Shiksha.com";
                            $description = "View ".$numResults." ".$subCategoryName." college".$pluralClgTxtSufix." in ".$locationName." accepting ".$examName." scores, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.";
                        }
                        else
                        {  
                            $title = $subCategoryName." college".$pluralClgTxtSufix." in ".$locationName." accepting ".$examName." scores | Shiksha.com";
                            $description ="View ".$numResults." ".$subCategoryName.$courseNameDscptn." college".$pluralClgTxtSufix." in ".$locationName." accepting ".$examName.", check courses, admissions, fees, reviews and more";                            
                        }
                    }
                }
                else
                {
                    if($feesValue > 0 && $feesString)
                    {
                        if($courseNameTitle){
                            $courseNameTitle = trim(str_replace("in ", "", $courseNameTitle));

                            //LOC + SPEC + FEES
                            if($subCategoryName == 'MBA')
                            {
                                $title = $subCategoryName." in ".$courseNameTitle." college".$pluralClgTxtSufix." in ".$locationName." with fees upto ".$feesString." | Shiksha.com";
                                $description = "View ".$numResults." ".$subCategoryName." college".$pluralClgTxtSufix." in ".$locationName." with fees upto ".$feesString.", check their courses, placements, admissions, alumni reviews, eligibility, and more details.";
                            }
                            else
                            {
                                $title = $courseNameTitle." college".$pluralClgTxtSufix." in ".$locationName." with fees upto ".$feesString." | Shiksha.com";
                                $description ="View ".$numResults." ".$courseNameTitle." college".$pluralClgTxtSufix." in ".$locationName." with fees upto ".$feesString.", check admissions, exams, reviews and more";
                            }    
                        }else{
                            //LOC + FEES
                            if($subCategoryName == 'MBA')
                            {
                                $title = $subCategoryName.$courseNameTitle." college".$pluralClgTxtSufix." in ".$locationName." with fees upto ".$feesString." | Shiksha.com";
                                $description = "View ".$numResults." ".$subCategoryName.$courseNameDscptn." college".$pluralClgTxtSufix." in ".$locationName." with fees upto ".$feesString.", check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.";
                            }
                            else
                            {
                                $title = $subCategoryName." college".$pluralClgTxtSufix." in ".$locationName." with fees upto ".$feesString." | Shiksha.com";
                                $description ="View ".$numResults." ".$subCategoryName.$courseNameDscptn." college".$pluralClgTxtSufix." in ".$locationName." with fees upto ".$feesString.", check courses, admissions, exams, reviews and more";
                            }    
                        }                        
                    }
                    else
                    {
                  		if($subCategoryName == 'MBA') // for mba sub-category page, these are different as per user story SEO-6
            			{
            				// if($locationName == 'India')
            				// {
            				// 	$title = $pageNumberFormatted.$subCategoryName.$courseNameTitle." Colleges in ".$locationName." - ".$pageNumberFormatted.$subCategoryName.$courseNameTitle." Courses in ".$locationName." - ".$pageNumberFormatted.$subCategoryName.$courseNameTitle." Institutes";
            				// 	$description = $pageNumberFormatted."Looking for the best ".$subCategoryName.$courseNameDscptn." courses in ".$locationName."? Find all about ".$subCategoryName.$courseNameDscptn." colleges, courses & institutes on Shiksha.com";
            				// }
            				// else
            				// {
                                //Loc + Specialization
            					$title = $subCategoryName.$courseNameTitle." college".$pluralClgTxtSufix." in ".$locationName." | Shiksha.com";
            					$description = "View ".$numResults." ".$subCategoryName.$courseNameTitle." college".$pluralClgTxtSufix." in ".$locationName.", check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details";
                            //}
            			}elseif ($subCategoryName == 'Engineering') {
                            if($courseNameTitle)
                            {
                                $courseNameTitle = trim(str_replace("in ", "", $courseNameTitle));
                                $title = $courseNameTitle." college".$pluralClgTxtSufix." in ".$locationName." | Shiksha.com";
                                $description = "View ".$numResults." ".$courseNameTitle." college".$pluralClgTxtSufix." in ".$locationName.", check fees, admissions, exams, reviews and more";
                            }
                            else
                            {
                                $title = $subCategoryName.$courseNameTitle." college".$pluralClgTxtSufix." in ".$locationName." - Shiksha.com";
                                $description = "View ".$numResults." ".$subCategoryName.$courseNameDscptn." college".$pluralClgTxtSufix." in ".$locationName.", check their courses, fees, admissions, exams, reviews and more details";                                
                            }
                            
            			}else{
            				$title = $pageNumberFormatted.$subCategoryName.$courseNameTitle." courses in ".$locationName." - Shiksha.com";
            				$description = $pageNumberFormatted."Looking for the best ".$subCategoryName.$courseNameDscptn." courses in ".$locationName."? Find the list of best courses & colleges on Shiksha.com";
            			}
                    }
                }
            }
        }

        // LF-2980 : New title and description for SubCat + location page for MBA        
        // LF-3704 : New title and description for SubCat + location page for MBA changed by Aman Varshney on 10 Dec 2015       
        if($numResults && $subCategoryID == 23 && ($courseID <= 1 || $courseID == 2 ) && !$localityID && !$feesString && !$examName && !$affiliationName){

            $pluralSufix = '';
            if($numResults > 1)
                $pluralSufix = 's';
            $title       = $subCategoryName." college".$pluralSufix." in ".$locationName." | Shiksha.com";
            $description = "View ".$numResults." ".$subCategoryName." college".$pluralSufix." in ".$locationName.", check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.";
        }
        //error_log("check if here....TITLE: ".$title);
        //error_log("check if here....DESCRIPTION: ".$description);
        
        $metaData['title'] = $title;
        $metaData['description'] = $description;
        return $metaData;
    }
    
    public function formatOtherExamForUrl()
    {
        $otherExamScoreData = $this->otherExamScoreData;
        
        $suffixMarketing = "";
        $catMiscelleneous = $this->CI->load->library('common/Miscelleneous');
        $output = $catMiscelleneous->catPageSourceInfo($_SERVER['HTTP_REFERER']);
        
	if($output['marketing']=='true' && $otherExamScoreData)   //if($marketing_page)
        {
            $examName = "";
            $suffixMarketing_prefix = CP_REQUEST_VAR_NAME_EXAM."=";
            foreach($otherExamScoreData as $examDetails)
            {
                $suffixMarketing .= $examDetails[0].CP_OTHER_EXAM_NAME_AND_SCORE_SEPERATOR.$examDetails[1].CP_OTHER_EXAM_AND_EXAM_SEPERATOR;
            }
            $suffixMarketing = rtrim($suffixMarketing, "$"); //remove trailing comma
            $suffixMarketing = str_replace(' ', '-', $suffixMarketing);
            $suffixMarketing = preg_replace('!-+!', '-', $suffixMarketing);
            $suffixMarketing = $suffixMarketing_prefix.$suffixMarketing."&";
        }
        else
            $suffixMarketing = "";
    }
    
    public function checkAndFormatSubcategory($subCategoryID, $subCategoryName, $categoryID, $categoryName)
    {
        //error_log("check if here....subCategoryID: ".$subCategoryID);
        //error_log("check if here....subCategoryName: ".$subCategoryName);
        if($subCategoryID > 0)
        {
            if($subCategoryName == "Full Time MBA/PGDM")
                $subCategoryName = "MBA";

            if($subCategoryID == 1 && $categoryID == 2)
            {
                $categoryName = "Engineering";
                $subCategoryName = $categoryName;
            }
            $subCategoryName = ucfirst($subCategoryName);
        }
        else
            $subCategoryName = "";
        
        return($subCategoryName);
    }
    
    public function getFeesString()
    {
        $this->convertFeeNumericToString();
	return $this->feesString;
    }
    
    public function formatAffiliationName($affiliationName)
    {
        if($affiliationName)
        {
            $affiliationName = strtolower($affiliationName);
            $mapArr = $this->CI->config->item("CP_AFFILIATION_TO_VALUE_MAP");
            $affiliationType = $mapArr[$affiliationName];
            
            $affiliationName = strtoupper($affiliationName);
            $affiliationName = $affiliationName.' '.$affiliationType.' ';
        }
        
        return $affiliationName;
    }
    
    public function checkAndFormatLocation($localityName, $localityID, $cityName, $cityID, $stateName, $stateID, $countryName)
    {
        //error_log("check if here....localityName: ".$localityName);
        //error_log("check if here....localityID: ".$localityID);
        //error_log("check if here....cityName: ".$cityName);
        //error_log("check if here....cityID: ".$cityID);
        $locationName1 = "";
        $locationName2 = "";
        
        if($localityName && $localityID > 0)
        {
            $locationName1 = $localityName.", ";
        }
        
        $locationName1 = ucfirst($locationName1);
        
        if($cityID > 0)
        {
            $locationName2 = $cityName;
            if($cityID == 1 && $stateID > 0)
            {
                $locationName2 = $stateName;
                if($stateID == 1 && $countryName)
                {
                    $locationName2 = $countryName;
                }
            }
            $locationName2 = ucfirst($locationName2);
            $locationName = $locationName1.$locationName2;
        }
        else
            $locationName = "";
        
        return $locationName;
    }

    function setHideLocationLayer($val){
        if(!isset($this->getSubCategoryID)){
            $this->hideLocationLayer = 0;    
        }

        if($val == 1 && ($this->getSubCategoryID() == 23 || $this->getSubCategoryID() == 56 )) 
            $this->hideLocationLayer = $val;
    }
	
};
// class definition end

?>