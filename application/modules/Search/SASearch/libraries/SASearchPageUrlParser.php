<?php

class SASearchPageUrlParser{

	private $searchKeyword;
	private $pageNumber;
	private $categoryIds;
	private $subCategoryIds;
	private $city;
	private $state;
	private $locality;
	private $specialization;
	private $specializationIds;
	private $fees;
	private $exams;
	private $courseLevel;
	private $mode;
	private $desiredCourse;
	private $qerResults;
	private $searchedAttributes;
	private $requestFrom;
	private $sortingParam;
	private $selectedLdbId;
	private $oldKeyword;
	private $examScore;
	private $courseFee;
	private $universities;
	private $institute;
	private $workExperience;
	private $ugMarks;
	private $class12Marks;
	private $applicationDeadline;
	private $intakeSeason;
	private $scholarship;
	private $sop;
	private $lor;
	private $courseDuration;
	private $filterUpdateCallFlag;
	private $filterWithDataFlag;
	private $trackingId;
        private $initialStateFilter;
        private $filterSpecializationId;



        public function	__construct(){
		$this->CI = & get_instance();
		$this->CI->load->config("SASearch/SASearchPageConfig");
	}

	public function parseSearchUrl(){
		$SEARCH_PARAMS_FIELDS_ALIAS = $this->CI->config->item('SA_SEARCH_PARAMS_FIELDS_ALIAS');
		$searchKeyword = $this->CI->security->xss_clean($_REQUEST[$SEARCH_PARAMS_FIELDS_ALIAS['keyword']]);
		$this->setSearchKeyword($searchKeyword);
		$this->setPageNumber($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['pageNumber'],true));
		$this->setCity($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['city'],true));
		$this->setState($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['state'],true));
		$this->setCountry($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['country'],true));
		$this->setContinent($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['continent'],true));
		$this->setCategoryIds($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['categoryIds'],true));
		$this->setSubCategoryIds($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['subCategoryIds'],true));
		$this->setSpecialization($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['specialization'],true));
		$this->setDesiredCourse($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['desiredCourse'],true));
		$this->setExams($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['exams'],true));
		$this->setLevel($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['level'],true));
		$this->setQerResults($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['qerResults'],true));
		$this->setSearchedAttributes($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['searchedAttributes'],true));
		$this->setRequestFrom($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['requestFrom'],true));
		$this->setSortingParam($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['sort'],true));
		$this->setSpecializationIds($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['specializationIds'],true));
		$this->setExamScore($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['examScore'],true));
		$this->setCourseFee($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['courseFee'],true));
		$this->setUniversities($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['universities'],true));
		$this->setInstitute($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['institute'],true));
        $this->setWorkExperience($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['workex'],true));
        $this->setApplicationDeadline($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['apdeadline'],true));
        $this->setClass12Marks($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['class12'],true));
        $this->setUgMarks($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['ugMarks'],true));
        $this->setSop($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['sop'],true));
        $this->setLor($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['lor'],true));
        $this->setCourseDuration($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['courseDuration'],true));
        $this->setIntakeSeason($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['intseason'],true));
        $this->setScholarship($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['scholarship'],true));
        $this->setInitialStateFilter($this->CI->input->get('os'));
        $this->setFilterUpdateCallFlag($this->CI->input->post('filterUpdateCall'));
        $this->setFilterWithDataFlag($this->CI->input->post('filterWithData'));
        $this->setTextSearchFlag($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['textSearchFlag'],true));
        $this->setRemainingKeyword($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['remainingKeyword'],true));
	 	$this->setFilterSpecializationId($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['filterSpecializationId'],true));
	}

	public function setSearchKeyword($keyword){
		if(!empty($keyword)){
			$this->searchKeyword = trim($keyword);			
		}
	}

	public function getSearchKeyword(){
		return $this->searchKeyword;
	}

	public function setPageNumber($pageNumber){
		if(!empty($pageNumber) && is_numeric($pageNumber)){
			$this->pageNumber = intval($pageNumber);
		}
	}

	public function getPageNumber(){
		return $this->pageNumber;
	}

	public function setCategoryIds($categoryIds){
		if(!empty($categoryIds) && is_array($categoryIds)){
			$filtered_category = array();
			foreach($categoryIds as $val){
				if(is_numeric($val)){
					$filtered_category[]= intval($val);
				}
			}
			$filtered_category = array_unique($filtered_category);
			$this->categoryIds = $filtered_category;
		}
	}

	public function getCategoryIds(){
		return $this->categoryIds;
	}

	public function setSubCategoryIds($subCategoryIds){
		if(!empty($subCategoryIds) && is_array($subCategoryIds)){
			$filtered_subCategory = array();
			foreach($subCategoryIds as $val){
				if(is_numeric($val)){
					$filtered_subCategory[]= intval($val);
				}
			}
			$this->subCategoryIds = $filtered_subCategory;
		}
	}

	public function getSubCategoryIds(){
		return $this->subCategoryIds;
	}

	public function setCity($city){
		if(!empty($city) && is_array($city)){
			$filtered_city = array();
			foreach($city as $val){
				if(is_numeric($val)){
					$filtered_city[]= intval($val);
				}
			}
			$this->city = $filtered_city;
		}
	}

	public function getCity(){
		return $this->city;
	}

	public function setState($state){
		if(!empty($state) && is_array($state)){
			$filtered_state = array();
			foreach($state as $val){
				if(is_numeric($val)){
					$filtered_state[]= intval($val);
				}
			}
			$this->state = $filtered_state;
		}
	}

	public function getState(){
		return $this->state;
	}

	public function setCountry($country){
		if(!empty($country) && is_array($country)){
			$filtered_country = array();
			foreach($country as $val){
				if(is_numeric($val)){
					$filtered_country[]= intval($val);
				}
			}
			$this->country = $filtered_country;
		}
	}

	public function getCountry(){
		return $this->country;
	}
	
	public function setContinent($continent){
		if(!empty($continent) && is_array($continent)){
			$filtered_continent = array();
			foreach($continent as $val){
				if(is_numeric($val)){
					$filtered_continent[]= intval($val);
				}
			}
			$this->continent = $filtered_continent;
		}
	}

	public function getContinent(){
		return $this->continent;
	}

	public function setDesiredCourse($desiredCourse){
		if(!empty($desiredCourse) && is_array($desiredCourse)){
			$filtered_desiredCourse = array();
			foreach($desiredCourse as $val){
				if(is_numeric($val)){
					$filtered_desiredCourse[]= intval($val);
				}
			}
			$this->desiredCourse = $filtered_desiredCourse;
		}
	}

	public function getDesiredCourse(){
		return $this->desiredCourse;
	}
	
	public function setUniversities($universities){
		if(!empty($universities) && is_array($universities)){
			$filtered_univ = array();
			foreach($universities as $val){
				if(is_numeric($val)){
					$filtered_univ[]= intval($val);
				}
			}
			$this->universities = $filtered_univ;
		}
	}

	public function getUniversities(){
		return $this->universities;
	}
	
	public function setCourseFee($courseFee){
		if(!empty($courseFee) && is_array($courseFee)){
			$filteredCourseFee = array();
			foreach($courseFee as $val){
				if(is_numeric($val)){
					$filteredCourseFee[]= (float)$val;
				}
			}
			$this->courseFee = $filteredCourseFee;
		}
	}

	public function getCourseFee(){
		return $this->courseFee;
	}
	
	public function setExamScore($examScore){
		if(!empty($examScore) && is_array($examScore)){
			$filteredExamScore = array();
			foreach($examScore as $val){
				if(is_numeric($val)){
					$filteredExamScore[]= (float)$val;
				}
			}
			$this->examScore = $filteredExamScore;
		}
	}

	public function getExamScore(){
		return $this->examScore;
	}	
	public function setInstitute($institute){
		if(!empty($institute) && is_array($institute)){
			$filtered_institute = array();
			foreach($institute as $val){
				if(is_numeric($val)){
					$filtered_institute[]= intval($val);
				}
			}
			$this->institute = $filtered_institute;
		}
	}

	public function getInstitute(){
		return $this->institute;
	}
	
	
	public function setSpecialization($specialization){
		if(!empty($specialization) && is_array($specialization)){
			$filtered_specialization = array();
			foreach($specialization as $val){
				if($val !=''){
					$filtered_specialization[]= $val;
				}
			}
			$filtered_specialization = array_unique($filtered_specialization);
			$this->specialization = $filtered_specialization;
		}
	}

	public function setSpecializationIds($specializationIds){
		if(!empty($specializationIds) && is_array($specializationIds)){
			$filteredSpecializationIds = array();
			foreach($specializationIds as $val){
				if(is_numeric($val)){
					$filteredSpecializationIds[]= intval($val);
				}
			}
			$this->specializationIds = $filteredSpecializationIds;
		}
	}


	public function getSpecialization(){
		return $this->specialization;
	}
	
	public function getSpecializationIds(){
		return $this->specializationIds;
	}
	public function setExams($exams){
		$this->exams = $exams;
	}

	public function getExams(){
		return $this->exams;
	}

	public function setLevel($level){
		$this->level = $level;
	}

	public function getLevel(){
		return $this->level;
	}

	public function setQerResults($qerResultString){
		$this->qerResults = $qerResultString;
	}

	public function getQerResults(){
		return $this->qerResults;
	}

	public function setSearchedAttributes($searchedAttributes){
		$this->searchedAttributes = $searchedAttributes;
	}

	public function getSearchedAttributes(){
		return $this->searchedAttributes;
	}
	

	public function getRequestFrom(){
		return $this->requestFrom;
	}

	public function setRequestFrom($value){
		$this->requestFrom = $value;
	}


	public function getSortingParam(){
		return $this->sortingParam;
	}

	public function setSortingParam($value){
		if($value ==''){
			$value = 'populairity_desc';	
		}else{
			$valueArr = explode('_', $value);
			$order = (strtoupper($valueArr[1])=='ASC')?'asc':'desc';
			switch ($valueArr[0]) {
                case 'exam':
                    if($valueArr[2]!=''){
                     	$value = $valueArr[0]."_".$order."_".$valueArr[2];
                    }else{
                    //its just for if someone temper with values    
                       $value = 'exam_asc_IELTS';  
                    }
                    break;
                case 'fees':
                    $value = $valueArr[0]."_".$order;
                    break;
                default:
                   $value = 'populairity_desc';
                    break;
            }	
		}
		$this->sortingParam = $value;
	}
        
        
        public function getWorkExperience() {
            return $this->workExperience;
        }

        public function getUgMarks() {
            return $this->ugMarks;
        }

        public function getClass12Marks() {
            return $this->class12Marks;
        }

        public function getApplicationDeadline() {
            return $this->applicationDeadline;
        }

        public function getIntakeSeason() {
            return $this->intakeSeason;
        }

        public function getScholarship() {
            return $this->scholarship;
        }

        public function getSop() {
            return $this->sop;
        }

        public function getLor() {
            return $this->lor;
        }

        public function getCourseDuration() {
            return $this->courseDuration;
        }

        public function setWorkExperience($workExperience) {
            $this->workExperience = $workExperience;
        }

        public function setUgMarks($ugMarks) {
            $this->ugMarks = $ugMarks;
        }

        public function setClass12Marks($class12Marks) {
            $this->class12Marks = $class12Marks;
        }

        public function setApplicationDeadline($applicationDeadline) {
            $this->applicationDeadline = $applicationDeadline;
        }

        public function setIntakeSeason($intakeSeason) {
            $this->intakeSeason = $intakeSeason;
        }

        public function setScholarship($scholarship) {
            $this->scholarship = $scholarship;
        }

        public function setSop($sop) {
            $this->sop = $sop;
        }

        public function setLor($lor) {
            $this->lor = $lor;
        }

        public function setCourseDuration($courseDuration) {
            $this->courseDuration = $courseDuration;
        }

		public function setFilterUpdateCallFlag($filterUpdateCallFlag) {
            $this->filterUpdateCallFlag = $filterUpdateCallFlag;
        }
		public function getFilterUpdateCallFlag() {
            return $this->filterUpdateCallFlag;
        }

        public function setFilterWithDataFlag($filterWithDataFlag) {
            $this->filterWithDataFlag = $filterWithDataFlag;
        }
		public function getFilterWithDataFlag() {
            return $this->filterWithDataFlag;
        }

        public function setTrackingId($trackingId){
        	$this->trackingId = $trackingId;
        }

        public function getTrackingId(){
        	return $this->trackingId;
        }
        public function getInitialStateFilter() {
            return $this->initialStateFilter;
        }

        public function setInitialStateFilter($initialStateFilter) {
            $this->initialStateFilter = $initialStateFilter;
        }

        public function setTextSearchFlag($textSearchFlag) {
            $this->textSearchFlag = $textSearchFlag;
        }
		public function getTextSearchFlag() {
            return $this->textSearchFlag;
        }

        public function setRemainingKeyword($remainingKeyword) {
            $this->remainingKeyword = $remainingKeyword;
        }
		public function getRemainingKeyword() {
            return $this->remainingKeyword;
        }
		 public function getFilterSpecializationId() {
            return $this->filterSpecializationId;
        }

        public function setFilterSpecializationId($filterSpecializationId) {
            $this->filterSpecializationId = $filterSpecializationId;
        }


}