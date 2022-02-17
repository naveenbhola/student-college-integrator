<?php

class NationalSearchPageUrlParser{

	private $searchKeyword;
	private $pageNumber;	
	private $stream;
	private $substream;
	private $specialization;
	private $baseCourse;
	private $educationType;
	private $deliveryMethod;
	private $credential;
	private $city;
	private $state;
	private $affiliation;
	private $locality;
	private $fees;
	private $exams;
	private $qerResults;
	private $popularGroup;
	private $certificateProvider;
	private $courseLevel;
	private $approvals;
	private $grants;
	private $collegeOwnership;
	private $accreditation;
	private $offeredByCollege;
	private $searchedAttributes;
	private $trackingSearchId;
	private $requestFrom;
	private $trackingFilterId;
	private $oldKeyword;
	private $courseStatus;
	private $twoStepClosedSearch;
	private $setSingleStreamClosedSearch;
	private $courseType;
	private $additionalFacetsToFetch;
	private $relevantResults;
	private $typedKeyword;
	private $isTrending;
	private $isInstituteMultiple;
	private $isInterim;
	
	public function	__construct(){
		$this->CI = & get_instance();
		$this->CI->load->config("nationalCategoryList/nationalConfig");
	}

	public function parseSearchUrl($selectedFilter){
		$this->selectedFilter = $selectedFilter;

		$SEARCH_PARAMS_FIELDS_ALIAS = $this->CI->config->item('FIELD_ALIAS');
		$searchKeyword = $this->CI->security->xss_clean($_REQUEST[$SEARCH_PARAMS_FIELDS_ALIAS['keyword']]);
		$this->setSearchKeyword($searchKeyword);
		$this->setPageNumber($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['pageNumber'],true));
		$this->setStream($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['stream'],true));
		$this->setSubstream($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['substream'],true));
		$this->setSpecialization($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['specialization'],true));
		$this->setBaseCourse($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['base_course'],true));
		$this->setEducationType($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['education_type'],true));
		$this->setDeliveryMethod($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['delivery_method'],true));
		$this->setCredential($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['credential'],true));
		$this->setLevelCredential($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['level_credential'],true));
		$this->setExams($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['exam'],true));
		$this->setCity($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['city'],true));
		$this->setState($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['state'],true));
		$this->setLocality($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['locality'],true));

		$this->setAffiliation($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['affiliation'],true));
		$this->setFees($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['fees'],true));

		$this->setPopularGroup($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['popular_group'],true));
		$this->setCourseLevel($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['course_level'],true));
		
		$this->setCertificateProvider($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['certificate_provider'],true));
		$this->setApprovals($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['approvals'],true));
		$this->setGrants($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['grants'],true));
		$this->setFacilities($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['facilities'],true));
		$this->setGrants($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['grants'],true));
		$this->setCollegeOwnership($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['college_ownership'],true));
		$this->setAccreditation($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['accreditation'],true));
		$this->setOfferedByCollege($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['offered_by_college'],true));
		$this->setSearchedAttributes($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['searchedAttributes'],true));

		$this->setQerResults($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['qerResults'],true));
		$this->setOldKeyword(xss_clean($_REQUEST[$SEARCH_PARAMS_FIELDS_ALIAS['oldKeyword']]));
		$this->setRelevantResults((int) $this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['relevantResults'],true));
		$this->setTrackingSearchId( (int)$this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['trackingSearchId'],true));
		$this->setTrackingFilterId( (int)$this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['trackingFilterId'],true));
		$this->setRequestFrom($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['requestFrom'],true));
		$this->setCourseStatus($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['course_status'],true));
		$this->setTwoStepClosedSearch($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['twoStepClosedSearch'],true));
		$this->setSingleStreamClosedSearch($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['singleStreamClosedSearch'],true));
		$this->setAdditionalFacetsToFetch($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['additionalFacetsToFetch'],true));

		$this->setTypedKeyword($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['typedKeyword'],true));
		$this->setIsTrending($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['isTrending'],true));
		$this->setIsInstituteMultiple($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['isInstituteMultiple'],true));
		$this->setIsInterim($this->CI->input->get($SEARCH_PARAMS_FIELDS_ALIAS['isInterim'],true));
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

	public function getStream()
	{
	    return $this->stream;
	}
	 
	public function setStream($stream)
	{
		if(!empty($this->selectedFilter['stream']) && is_array($this->selectedFilter['stream'])){
			$this->stream = array();
			foreach($this->selectedFilter['stream'] as $val){
				if(is_numeric($val)){
					$this->stream[]= intval($val);
				}
			}
		}

		if(!empty($stream) && is_array($stream)){
			foreach($stream as $val){
				if(is_numeric($val)){
					$this->stream[]= intval($val);
				}
			}
		}
	}

	public function getSubstream()
	{
	    return $this->substream;
	}
	 
	public function setSubstream($substream)
	{
		if(!empty($substream) && is_array($substream)){
			$filtered_subtream = array();
			foreach($substream as $val){
				if(is_numeric($val)){
					$filtered_subtream[]= intval($val);
				}
			}
			$this->substream = $filtered_subtream;
		}
	}

	public function getSpecialization()
	{
	    return $this->specialization;
	}
	 
	public function setSpecialization($specialization)
	{
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

	public function getBaseCourse()
	{
	    return $this->baseCourse;
	}
	 
	public function setBaseCourse($baseCourse)
	{	
		/*$categoryFiltersByUser                          = $this->CI->input->get('uaf',true);
		if(!in_array('base_course',$categoryFiltersByUser)) {
			if(!empty($this->selectedFilter['base_course']) && is_array($this->selectedFilter['base_course'])) {
				$this->baseCourse = array();
				foreach($this->selectedFilter['base_course'] as $val){
					if(is_numeric($val)){
						$this->baseCourse[]= intval($val);
					}
				}
			}
		}*/
	    if(!empty($baseCourse) && is_array($baseCourse)){
			foreach($baseCourse as $val){
				if(is_numeric($val)){
					$this->baseCourse[]= intval($val);
				}
			}
		}
	}

	public function getEducationType()
	{
	    return $this->educationType;
	}
	 
	public function setEducationType($educationType)
	{
	    $this->educationType = $educationType;
	    
	}

	public function getDeliveryMethod()
	{
	    return $this->deliveryMethod;
	}
	 
	public function setDeliveryMethod($deliveryMethod)
	{
	    $this->deliveryMethod = $deliveryMethod;
	    
	}

	public function getCredential()
	{
	    return $this->credential;
	}
	 
	public function setCredential($credential)
	{
	    $this->credential = $credential;
	    
	}

	public function getLevelCredential() {
		return $this->levelCredential;
	}

	public function setLevelCredential($levelCredential) {
		$this->levelCredential = $levelCredential;
	}

	public function getCity()
	{
	    return $this->city;
	}
	 
	public function setCity($city)
	{
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

	public function getState()
	{
	    return $this->state;
	}
	 
	public function setState($state)
	{
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

	public function getLocality()
	{
	    return $this->locality;
	}
	 
	public function setLocality($locality)
	{
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

	public function getFees()
	{
	    return $this->fees;
	}
	 
	public function setFees($fees)
	{
	    $this->fees = $fees;
	    
	}

	public function getExams()
	{
	    return $this->exams;
	}
	 
	public function setExams($exams)
	{
	    $this->exams = $exams;
	    
	}

	public function getQerResults()
	{
	    return $this->qerResults;
	}
	 
	public function setQerResults($qerResults)
	{
	    $this->qerResults = $qerResults;
	    
	}

	public function getSearchedAttributes()
	{
	    return $this->searchedAttributes;
	}
	 
	public function setSearchedAttributes($searchedAttributes)
	{
	    $this->searchedAttributes = $searchedAttributes;
	    
	}

	/*public function getTwoStepClosedSearch()
	{
	    return $this->twoStepClosedSearch;
	}
	 
	public function setTwoStepClosedSearch($twoStepClosedSearch)
	{
	    $this->twoStepClosedSearch = $twoStepClosedSearch;
	    
	}
*/
	public function getRequestFrom()
	{
	    return $this->requestFrom;
	}
	 
	public function setRequestFrom($requestFrom)
	{
	    $this->requestFrom = $requestFrom;
	    
	}

	public function getOldKeyword()
	{
	    return $this->oldKeyword;
	}
	 
	public function setOldKeyword($oldKeyword)
	{
	    $this->oldKeyword = $oldKeyword;
	    
	}

	public function getTypedKeyword()
	{
	    return $this->typedKeyword;
	}
	 
	public function setTypedKeyword($keyword)
	{
	    $this->typedKeyword = $keyword;
	    
	}

	public function getIsTrending(){
		return $this->isTrending;
	}

	public function setIsTrending($value){
		$this->isTrending = $value;
	}

	public function getIsInstituteMultiple(){
		return $this->isInstituteMultiple;
	}

	public function setIsInstituteMultiple($value){
		$this->isInstituteMultiple = $value;
	}

	public function getIsInterim(){
		return $this->isInterim;
	}

	public function setIsInterim($value){
		$this->isInterim = $value;
	}

	public function getPopularGroup()
	{
	    return $this->popularGroup;
	}
	 
	public function setPopularGroup($popularGroup)
	{
	    $this->popularGroup = $popularGroup;
	}

	public function getCertificateProvider()
	{
	    return $this->certificateProvider;
	}
	 
	public function setCertificateProvider($certificateProvider)
	{
	    $this->certificateProvider = $certificateProvider;
	}

	public function getCourseLevel()
	{
	    return $this->courseLevel;
	}
	 
	public function setCourseLevel($courseLevel)
	{
	    $this->courseLevel = $courseLevel;
	}

	public function getApprovals()
	{
	    return $this->approvals;
	}
	 
	public function setApprovals($approvals)
	{
	    $this->approvals = $approvals;
	}

	public function getGrants()
	{
	    return $this->grants;
	}
	 
	public function setGrants($grants)
	{
	    $this->grants = $grants;
	}

	public function getCollegeOwnership()
	{
	    return $this->collegeOwnership;
	}
	 
	public function setCollegeOwnership($collegeOwnership)
	{
	    $this->collegeOwnership = $collegeOwnership;
	}

	public function getAccreditation()
	{
	    return $this->accreditation;
	}
	 
	public function setAccreditation($accreditation)
	{
	    $this->accreditation = $accreditation;
	}

	public function getOfferedByCollege()
	{
	    return $this->offeredByCollege;
	}
	 
	public function setOfferedByCollege($offeredByCollege)
	{
	    $this->offeredByCollege = $offeredByCollege;
	}

	public function getTrackingSearchId()
	{
	    return $this->trackingSearchId;
	}
	 
	public function setTrackingSearchId($trackingSearchId)
	{
	    $this->trackingSearchId = $trackingSearchId;
	}

	public function getTrackingFilterId()
	{
	    return $this->trackingFilterId;
	}
	 
	public function setTrackingFilterId($trackingFilterId)
	{
	    $this->trackingFilterId = $trackingFilterId;
	}

	public function getAffiliation()
	{
	    return $this->affiliation;
	}
	 
	public function setAffiliation($affiliation)
	{
	    $this->affiliation = $affiliation;
	}

	public function getFacilities()
	{
	    return $this->facilities;
	}
	 
	public function setFacilities($facilities)
	{
	    $this->facilities = $facilities;
	}

	public function getCourseStatus(){
		return $this->courseStatus;
	}

	public function setCourseStatus($courseStatus){
		$this->courseStatus = $courseStatus;
	}

	public function setTwoStepClosedSearch($value){
		$this->twoStepClosedSearch = $value;
	}

	public function getTwoStepClosedSearch(){
		return $this->twoStepClosedSearch;
	}

	public function setSingleStreamClosedSearch($value){
		$this->setSingleStreamClosedSearch = $value;
	}

	public function getSingleStreamClosedSearch(){
		return $this->setSingleStreamClosedSearch;
	}

	public function setCourseType($value){
		$this->courseType = $value;
	}

	public function getCourseType(){
		return $this->courseType;
	}
	
    public function getAdditionalFacetsToFetch(){
        return $this->additionalFacetsToFetch;
    }

    private function setAdditionalFacetsToFetch($additionalFacetsToFetch){
        $this->additionalFacetsToFetch = $additionalFacetsToFetch;
    }
}