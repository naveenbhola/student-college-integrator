<?php

class SearchPageUrlParser{

	private $searchKeyword;
	private $pageNumber;
	private $categoryId;
	private $subCategoryId;
	private $city;
	private $state;
	private $locality;
	private $specialization;
	private $fees;
	private $exams;
	private $courseLevel;
	private $mode;
	private $qerResults;
	private $searchedAttributes;
	private $degreePref;
	private $affiliation;
	private $facilities;
	private $classTimings;
	private $trackingSearchId;
	private $twoStepClosedSearch;
	private $requestFrom;
	private $trackingFilterId;
	private $selectedLdbId;
	private $oldKeyword;
	private $relevantResults;
	
	public function	__construct(){
		$this->CI = & get_instance();
		$this->CI->load->config("search/SearchPageConfig");
	}

	public function parseSearchUrl(){
		$SEARCH_PARAMS_FIELDS_ALIAS = $this->CI->config->item('SEARCH_PARAMS_FIELDS_ALIAS');
		$searchKeyword = $this->CI->security->xss_clean($_REQUEST[$SEARCH_PARAMS_FIELDS_ALIAS['keyword']]);
		
		$this->setSearchKeyword($searchKeyword);
		$this->setPageNumber($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['pageNumber'],true));
		$this->setCategoryId($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['categoryId'],true));
		$this->setSubCategoryId($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['subCategoryId'],true));
		$this->setCity($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['city'],true));
		$this->setState($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['state'],true));
		$this->setLocality($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['locality'],true));
		$this->setSpecialization($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['specialization'],true));
		$this->setFees($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['fees'],true));
		$this->setExams($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['exams'],true));
		$this->setCourseLevel($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['courseLevel'],true));
		$this->setMode($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['mode'],true));
		$this->setDegreePref($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['degreePref'],true));
		$this->setAffiliation($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['affiliation'],true));
		$this->setQerResults($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['qerResults'],true));
		$this->setSearchedAttributes($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['searchedAttributes'],true));
		$this->setClassTimings($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['classtimings'],true));
		$this->setFacilities($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['facilities'],true));
		$this->setTrackingSearchId((int) $this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['trackingSearchId'],true));
		$this->setTwoStepClosedSearch($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['twoStepClosedSearch'],true));
		$this->setRequestFrom($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['requestFrom'],true));
		$this->setTrackingFilterId((int) $this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['trackingFilterId'],true));
		$this->setSelectedLdbId($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['selectedLdbId'],true));
		$this->setOldKeyword($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['oldKeyword'],true));
		$this->setRelevantResults((int) $this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['relevantResults'],true));
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

	public function setCategoryId($categoryId){
		if(!empty($categoryId) && is_numeric($categoryId)){
			$this->categoryId = intval($categoryId);		
		}
	}

	public function getCategoryId(){
		return $this->categoryId;
	}

	public function setSubCategoryId($subCategoryId){
		if(!empty($subCategoryId) && is_numeric($subCategoryId)){
			$this->subCategoryId = intval($subCategoryId);			
		}
	}

	public function getSubCategoryId(){
		return $this->subCategoryId;
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

	public function setLocality($locality){
		if(!empty($locality) && is_array($locality)){
			$filtered_locality = array();
			foreach($locality as $val){
				if(is_numeric($val)){
					$filtered_locality[]= intval($val);
				}
			}
			$this->locality = $filtered_locality;
		}
	}

	public function getLocality(){
		return $this->locality;
	}

	public function setSpecialization($specialization){
		if(!empty($specialization) && is_array($specialization)){
			$filtered_specialization = array();
			foreach($specialization as $val){
				if(is_numeric($val)){
					$filtered_specialization[]= intval($val);
				}
			}
			$this->specialization = $filtered_specialization;
		}
	}

	public function getSpecialization(){
		return $this->specialization;
	}

	public function setFees($fees){
		$this->fees = $fees;
	}

	public function getFees(){
		return $this->fees;
	}

	public function setExams($exams){
		$this->exams = $exams;
	}

	public function getExams(){
		return $this->exams;
	}

	public function setCourseLevel($courseLevel){
		$this->courseLevel = $courseLevel;
	}

	public function getCourseLevel(){
		return $this->courseLevel;
	}

	public function setMode($mode){
		$this->mode = $mode;
	}

	public function getMode(){
		return $this->mode;
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

	public function setDegreePref($degreePref){
		$this->degreePref = $degreePref;
	}

	public function getDegreePref(){
		return $this->degreePref;
	}

	public function setAffiliation($affiliation){
		$this->affiliation = $affiliation;
	}

	public function getAffiliation(){
		return $this->affiliation;
	}

	public function setClassTimings($clstime){
		$this->classTimings = $clstime;
	}

	public function getClassTimings(){
		return $this->classTimings;
	}

	public function setFacilities($facilities){
		$facilitiesList = array();
		foreach ($facilities as $key => $value) {
			$facilitiesList[] = str_replace("|", ":", $value);
		}
		$this->facilities = $facilitiesList;
	}

	public function getFacilities(){
		return $this->facilities;
	}

	public function getTrackingSearchId(){
		return $this->trackingSearchId;
	}

	public function setTrackingSearchId($id){
		$this->trackingSearchId = $id;
	}
	
	public function setTwoStepClosedSearch($value){
		$this->twoStepClosedSearch = $value;
	}

	public function getTwoStepClosedSearch(){
		return $this->twoStepClosedSearch;
	}

	public function getRequestFrom(){
		return $this->requestFrom;
	}

	public function setRequestFrom($value){
		$this->requestFrom = $value;
	}

	public function getTrackingFilterId(){
		return $this->trackingFilterId;
	}

	public function setTrackingFilterId($value){
		$this->trackingFilterId = $value;
	}

	public function setSelectedLdbId($selectedId){
		if(!empty($selectedId)){
			$this->selectedLdbId = trim($selectedId);			
		}
	}

	public function getSelectedLdbId($selectedId){
		return $this->selectedLdbId;
	}

	public function setOldKeyword($keyword){
		$this->oldKeyword = $keyword;
	}

	public function getOldKeyword(){
		return $this->oldKeyword;
	}

	public function getRelevantResults(){
		return $this->relevantResults;
	}

	public function setRelevantResults($val){
		global $searchReductionCriteria;
		if(in_array($val,$searchReductionCriteria)){
			$this->relevantResults = $val;
		}
		else if(array_key_exists($val,$searchReductionCriteria)){
			$this->relevantResults = $searchReductionCriteria[$val];
		}
	}
}