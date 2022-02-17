<?php

class SASearchPageRequest{

	private $CI;
	private $searchKeyword; 
	private $pageNumber;     
	private $city;           
	private $state;
	private $country;
	private $continent;
	private $universities;
	private $institute;
	private $exams;
	private $categoryIds;   
	private $subCategoryIds;
	private $level;
	private $desiredCourse;
	private $specialization;
	private $specializationIds; 
	private $pageType;
	private $qerResults;
	private $searchedAttributes;
	private $requestFrom;
	private $sortingParam;
	private $examScore;
	private $courseFee;
    private $workExperience;
    private $ugMarks;
    private $class12Marks;
    private $applicationDeadline;
    private $intakeSeason;
    private $scholarship;
    private $sop;
    private $lor;
    private $courseDuration;
    private $initialStateFilter;
    private $textSearchFlag;
    private $remainingKeyword;
	private $filterSpecializationId;

        public function	__construct(){
		$this->CI = & get_instance();
		$this->CI->load->library("SASearch/SASearchPageUrlParser");
		$this->CI->load->config("SASearch/SASearchPageConfig");

		$this->searchUrlParserObj = new SASearchPageUrlParser();

		//this condition is added to run search static url
		if(!strpos($_SERVER['SCRIPT_URL'],'universities-in-')){
			$this->setDataFromUrlParams();			
		}		
	}

	private function setDataFromUrlParams(){
		$this->searchUrlParserObj->parseSearchUrl();
		$this->searchKeyword             =  $this->searchUrlParserObj->getSearchKeyword();
		$this->pageNumber                =  $this->searchUrlParserObj->getPageNumber();
		$this->city                      =  $this->searchUrlParserObj->getCity();
		$this->state                     =  $this->searchUrlParserObj->getState();
		$this->country                   =  $this->searchUrlParserObj->getCountry();
		$this->continent                 =  $this->searchUrlParserObj->getContinent();
		$this->universities              =  $this->searchUrlParserObj->getUniversities();
		$this->institute                 =  $this->searchUrlParserObj->getInstitute();
		$this->exams                     =  $this->searchUrlParserObj->getExams();
		$this->categoryIds               =  $this->searchUrlParserObj->getCategoryIds();
		$this->subCategoryIds            =  $this->searchUrlParserObj->getSubCategoryIds();
		$this->level               		 =  $this->searchUrlParserObj->getLevel();
		$this->desiredCourse             =  $this->searchUrlParserObj->getDesiredCourse();
		$this->specialization            =  $this->searchUrlParserObj->getSpecialization();
		$this->specializationIds         =  $this->searchUrlParserObj->getSpecializationIds();
		$this->qerResults        		 =  $this->searchUrlParserObj->getQerResults();
		$this->requestFrom				 =  $this->searchUrlParserObj->getRequestFrom();
		$this->sortingParam				 =  $this->searchUrlParserObj->getSortingParam();
		$this->examScore            	 =  $this->searchUrlParserObj->getExamScore();
		$this->courseFee            	 =  $this->searchUrlParserObj->getCourseFee();
		$this->workExperience            =  $this->searchUrlParserObj->getWorkExperience();
		$this->ugMarks                   =  $this->searchUrlParserObj->getUgMarks();
		$this->class12Marks              =  $this->searchUrlParserObj->getClass12Marks();
		$this->applicationDeadline       =  $this->searchUrlParserObj->getApplicationDeadline();
		$this->intakeSeason              =  $this->searchUrlParserObj->getIntakeSeason();
		$this->sop                       =  $this->searchUrlParserObj->getSop();
		$this->lor                       =  $this->searchUrlParserObj->getLor();
		$this->courseDuration            =  $this->searchUrlParserObj->getCourseDuration();
		$this->scholarship               =  $this->searchUrlParserObj->getScholarship();
		$this->filterUpdateCallFlag      =  $this->searchUrlParserObj->getFilterUpdateCallFlag();
		$this->filterWithDataFlag     =  $this->searchUrlParserObj->getFilterWithDataFlag();
        $this->initialStateFilter 		 =  $this->searchUrlParserObj->getInitialStateFilter();
        $this->textSearchFlag 		 	 =  $this->searchUrlParserObj->getTextSearchFlag();
        $this->remainingKeyword 		 =  $this->searchUrlParserObj->getRemainingKeyword();
		$this->filterSpecializationId=  $this->searchUrlParserObj->getFilterSpecializationId();
	}


	public function getUrl(){
		
		$searchKeyword             = $this->searchKeyword;
		$pageNumber                = $this->pageNumber;
		$city                      = $this->city;
		$state                     = $this->state;
		$country                   = $this->country;
		$continent                 = $this->continent;
		$universities              = $this->universities;
		$institute             	   = $this->institute;
		$exams                     = $this->exams;
		$categoryIds               = $this->categoryIds;
		$subCategoryIds            = $this->subCategoryIds;
		$level               	   = $this->level;
		$desiredCourse             = $this->desiredCourse;
		$specialization            = $this->specialization;
		$qerResults                = $this->qerResults;
	    $searchedAttributes        = $this->searchedAttributes;
	    $requestFrom 			   = $this->requestFrom;
		$specializationIds         = $this->specializationIds;
		$courseFee             	   = $this->courseFee ;
		$examScore            	   = $this->examScore;
		$trackingId 			   = $this->trackingId;
		$textSearchFlag 		   = $this->textSearchFlag;
		$remainingKeyword 		   = $this->remainingKeyword;
	   $filterSpecId=  $this->filterSpecializationId;

		$queryParams = array();
		$SEARCH_PARAMS_FIELDS_ALIAS = $this->CI->config->item('SA_SEARCH_PARAMS_FIELDS_ALIAS');
		
		$this->prepareQueryParams($queryParams,$searchKeyword,$SEARCH_PARAMS_FIELDS_ALIAS['keyword']);

		$this->prepareQueryParams($queryParams,$pageNumber,$SEARCH_PARAMS_FIELDS_ALIAS['pageNumber']);
		
		$this->prepareQueryParams($queryParams,$city,$SEARCH_PARAMS_FIELDS_ALIAS['city']);

		$this->prepareQueryParams($queryParams,$state,$SEARCH_PARAMS_FIELDS_ALIAS['state']);
		
		$this->prepareQueryParams($queryParams,$country,$SEARCH_PARAMS_FIELDS_ALIAS['country']);
		
		$this->prepareQueryParams($queryParams,$continent,$SEARCH_PARAMS_FIELDS_ALIAS['continent']);
		
		//WE will not need these in uRL so commenting for now will remove later
		
		$this->prepareQueryParams($queryParams,$universities,$SEARCH_PARAMS_FIELDS_ALIAS['universities']);

		$this->prepareQueryParams($queryParams,$institute,$SEARCH_PARAMS_FIELDS_ALIAS['institute']);
		
		$this->prepareQueryParams($queryParams,$exams,$SEARCH_PARAMS_FIELDS_ALIAS['exams']);
		
		$this->prepareQueryParams($queryParams,$desiredCourse,$SEARCH_PARAMS_FIELDS_ALIAS['desiredCourse']);		

		$this->prepareQueryParams($queryParams,$categoryIds,$SEARCH_PARAMS_FIELDS_ALIAS['categoryIds']);

		$this->prepareQueryParams($queryParams,$subCategoryIds,$SEARCH_PARAMS_FIELDS_ALIAS['subCategoryIds']);

		$this->prepareQueryParams($queryParams,$level,$SEARCH_PARAMS_FIELDS_ALIAS['level']);
		
		$this->prepareQueryParams($queryParams,$specialization,$SEARCH_PARAMS_FIELDS_ALIAS['specialization']);
		
		$this->prepareQueryParams($queryParams,$qerResults,$SEARCH_PARAMS_FIELDS_ALIAS['qerResults']);

		$this->prepareQueryParams($queryParams,$searchedAttributes,$SEARCH_PARAMS_FIELDS_ALIAS['searchedAttributes']);

		$this->prepareQueryParams($queryParams,$requestFrom,$SEARCH_PARAMS_FIELDS_ALIAS['requestFrom']);

		$this->prepareQueryParams($queryParams,$specializationIds,$SEARCH_PARAMS_FIELDS_ALIAS['specializationIds']);

		$this->prepareQueryParams($queryParams,$courseFee,$SEARCH_PARAMS_FIELDS_ALIAS['courseFee']);

		$this->prepareQueryParams($queryParams,$examScore,$SEARCH_PARAMS_FIELDS_ALIAS['examScore']);

		$this->prepareQueryParams($queryParams,$trackingId,$SEARCH_PARAMS_FIELDS_ALIAS['trackingId']);

		$this->prepareQueryParams($queryParams,$textSearchFlag,$SEARCH_PARAMS_FIELDS_ALIAS['textSearchFlag']);

		$this->prepareQueryParams($queryParams,$remainingKeyword,$SEARCH_PARAMS_FIELDS_ALIAS['remainingKeyword']);
		$this->prepareQueryParams($queryParams,$filterSpecId,$SEARCH_PARAMS_FIELDS_ALIAS['filterSpecializationId']);
		$queryParams = implode("&",$queryParams);
		if($queryParams != '')
            $suffix = '?'.$queryParams;

		$domainPrefix = SHIKSHA_STUDYABROAD_HOME.SASEARCH_PAGE_URL_PREFIX.'/';
	
		return $domainPrefix.$suffix;
	}

	private function prepareQueryParams(& $queryParams,$item,$key){
		if(!empty($item)){
			if(is_array($item)){
				foreach($item as $val){
					$queryParams[]=$key."[]=".urlencode($val);
				}
			}else{
				$queryParams[]=$key."=".urlencode($item);
			}
		}
	}

	public function getSearchKeyword(){
		return $this->searchKeyword;
	}

	public function getQerResults(){
		return $this->qerResults;
	}
	
	public function getCategoryIds(){
		return $this->categoryIds;
	}

	public function getSubCategoryIds(){
		return $this->subCategoryIds;
	}
	
	public function getSpecializationIds(){
		return $this->specializationIds;
	}
	/**
	 * function to set data in request
	 * @param  array      $data [description]
	 */
	public function setData($data = array()){
		
		foreach($data as $key => $val){
			// setting data in search url parser
			$setterName = "set".ucfirst($key);
			$getterName = "get".ucfirst($key);
			if(method_exists($this->searchUrlParserObj, $setterName)){
				$this->searchUrlParserObj->$setterName($val);
				//setting search page request variable
				$this->$key = $this->searchUrlParserObj->$getterName();
			}
		}
	}
	
	public function getQERFilters(){
		$qerFiltersData = array();
		
		if(!empty($this->searchKeyword)){
			$qerFiltersData['q'] = $this->searchKeyword;
		}

		if(!empty($this->city)){
			$qerFiltersData['city'] = $this->city;
		}

		if(!empty($this->state)){
			$qerFiltersData['state'] = $this->state;
		}
		if(!empty($this->country)){
			$qerFiltersData['country'] = $this->country;
		}

		if(!empty($this->continent)){
			$qerFiltersData['continent'] = $this->continent;
		}

		if(!empty($this->exams)){
			$qerFiltersData['exams'] = $this->exams;
		}
		
		if(!empty($this->categoryIds)){
			$qerFiltersData['categoryIds'] = $this->categoryIds;
		}
		
		if(!empty($this->subCategoryIds)){
			$qerFiltersData['subCategoryIds'] = $this->subCategoryIds;
		}
		
		if(!empty($this->desiredCourse)){
			$qerFiltersData['desiredCourse'] = $this->desiredCourse;
		}
		
		if(!empty($this->level)){
			$qerFiltersData['level'] = $this->level;
		}
		
		if(!empty($this->specialization)){
			$qerFiltersData['specialization'] = $this->specialization;
		}
		
		if(!empty($this->universities)){
			$qerFiltersData['universities'] = $this->universities;
		}
		
		if(!empty($this->institute)){
			$qerFiltersData['institute'] = $this->institute;
		}
		
		if(!empty($this->specializationIds)){
			$qerFiltersData['specializationIds'] = $this->specializationIds;
		}
		
		if(!empty($this->courseFee)){
			$qerFiltersData['courseFee'] = $this->courseFee;
		}
		if(!empty($this->examScore)){
			$qerFiltersData['examScore'] = $this->examScore;
		}
                
		if(!empty($this->workExperience)){
			$qerFiltersData['workExperience'] = $this->workExperience;
		}
                
		if(!empty($this->ugMarks)){
			$qerFiltersData['ugMarks'] = $this->ugMarks;
		}
                
		if(!empty($this->class12Marks)){
			$qerFiltersData['class12Marks'] = $this->class12Marks;
		}
                
		if(!empty($this->applicationDeadline)){
			$qerFiltersData['applicationDeadline'] = $this->applicationDeadline;
		}
                
		if(!empty($this->intakeSeason)){
			$qerFiltersData['intakeSeason'] = $this->intakeSeason;
		}
                
		if(!empty($this->scholarship)){
			$qerFiltersData['scholarship'] = $this->scholarship;
		}
		if(!empty($this->sop)){
			$qerFiltersData['sop'] = $this->sop;
		}
                
		if(!empty($this->lor)){
			$qerFiltersData['lor'] = $this->lor;
		}
                
		if(!empty($this->courseDuration)){
			$qerFiltersData['courseDuration'] = $this->courseDuration;
		}
                
		if(!empty($this->initialStateFilter)){
			$qerFiltersData['originalState'] = $this->initialStateFilter;
		}
                if(!empty($this->filterSpecializationId)){
			$qerFiltersData['filterSpecializationId'] = $this->filterSpecializationId;
		}
		return $qerFiltersData;
		
	}


	public function getSearchedAttributesString(){
		 return $this->searchedAttributes;
	}

	public function getCurrentPageNum() {
		if(empty($this->pageNumber))
			$this->pageNumber = 1;

		return $this->pageNumber;
	}

	public function setPageNumber($pagenum){
		$this->pageNumber = $pagenum;
	}

	public function getPageType(){
		if(empty($this->subCategoryIds)){
			return 'open';
		}else{
			return 'close';
		}
	}

	public function getTrackingSearchId(){
		return $this->trackingSearchId;
	}

	public function getRequestFrom(){
		return $this->requestFrom;
	}

	public function getSortingParam(){
		return $this->sortingParam;
	}

	public function getTextSearchFlag(){
		return $this->textSearchFlag;
	}

	public function getRemainingKeyword(){
		return $this->remainingKeyword;
	}
	
	public function getFilterUpdateCallFlag(){
		return $this->filterUpdateCallFlag;
	}

	public function getFilterWithDataFlag(){
		return $this->filterWithDataFlag;
	}
        public function getInitialStateFilter() {
            return $this->initialStateFilter;
        }

        public function setInitialStateFilter($initialStateFilter) {
            $this->initialStateFilter = $initialStateFilter;
        }
        public function getFilterSpecializationId() {
            return $this->filterSpecializationId;
        }

        public function setFilterSpecializationId($filterSpecializationId) {
            $this->filterSpecializationId = $filterSpecializationId;
        }




}
