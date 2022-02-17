<?php
class Course {

	// Basic details
	private $course_id;
	private $name;
	private $primary_id;
	private $primary_type;
	private $primary_name;

	private $parent_id;
	private $parent_type;
	private $parent_name;

	private $offered_by_id;
	private $offered_by_name;
	private $offered_by_short_name;

	private $primary_short_name;
	private $main_location;
	private $course_variant;
	private $education_type;
	private $course_order;
	private $course_type_information;
	private $lateral;
	private $twinning;
	private $integrated;
	private $executive;
	private $dual;
	private $brochure_year;
	private $brochure_url;
	private $brochure_size;
	private $is_brochure_auto_generated;
	private $listing_seo_url;
	private $seo_title;
	private $seo_description;
	private $recognition;
	private $affiliated_university_id;
	private $affiliated_university_name;
	private $affiliated_university_scope;
	private $eligibility;
	private $locations;
	private $fees;
	private $pack_type;
	private $delivery_method;
	private $total_seats;
	private $duration_unit;
	private $duration_value;
	private $duration_disclaimer;
	private $disabled_url;
	private $placements_interships;
	private $review_count;
	private $questions_count;
	private $time_of_learning;
	private $difficulty_level;
	// Media
	private $media;
	private $username;
	private $expiry_date;
	private $object_creation_date;
	


	function __set($property,$value) {
		$this->$property = $value;
	}

	function getId(){
		return $this->course_id;
	}

	function getName() {
		return $this->name;
	}

	function addLocations($locations)
	{
		$this->locations = $locations;
	}

	function addAcademics($academicsData){
		$this->academic_staff = $academicsData;
	}

	function addResearchProjects($researchProjects){
		$this->research_projects = $researchProjects;
	}

	function addUSPList($uspList){
		$this->usp_list = $uspList;
	}

	function addMediaList($mediaList){
		$this->media = $mediaList;
	}


	function addEntryCourse($sectionData) {
		$this->entry_course = $sectionData;
	}

	function addExitCourse($sectionData) {
		$this->exit_course = $sectionData;
	}
	
	function addCourseTypeInformation($sectionData) {
		$this->course_type_information = $sectionData;
	}
	function addPlacementsInternships($sectionData) {
		$this->placements_interships = $sectionData;
	}

	function addMainLocation($sectionData) {
		$this->main_location = $sectionData;
	}
	function addEligibility($sectionData) {
		$this->eligibility = $sectionData;
	}

	function addFees($sectionData){
		$this->fees = $sectionData;
	}

	function getInstituteId() {
		return $this->primary_id;
	}

	function getInstituteName() {
		return $this->primary_name;
	}

	function getInstituteType(){
		return $this->primary_type;
	}

	function getInstituteShortName(){
		return $this->primary_short_name;
	}

	function getParentInstituteId() {
		return $this->parent_id;
	}

	function getParentInstituteName() {
		return $this->parent_name;
	}

	function getParentInstituteType(){
		return $this->parent_type;
	}

	function isLateral() {
		if($this->lateral == 1){
			return true;
		}else{
			return false;
		}
	}

	function isTwinning() {
		if($this->twinning == 1){
			return true;
		}else{
			return false;
		}
	}

	function isDual() {
		if($this->dual == 1){
			return true;
		}else{
			return false;
		}
	}

	function isExecutive() {
		if($this->executive == 1){
			return true;
		}else{
			return false;
		}
	}

	function isIntegrated() {
		if($this->integrated == 1){
			return true;
		}else{
			return false;
		}
	}

	function getRecognition() {
		$result = array();
		if(!empty($this->recognition)){
			foreach ($this->recognition as $key => $value) {
				if($value->getId() != NBA_APPROVAL){
					$result[] = $value;
				}
			}
		}
		return $result;
	}

	function isNBAAccredited(){
		if(empty($this->recognition)){
			return false;
		}
		
		if(!empty($this->nbaAccreditation)) {
			return true;
		}

		foreach ($this->recognition as $key => $value) {
			if($value->getId() == NBA_APPROVAL){
				$this->nbaAccreditation = $value;
				// _p($this->nbaAccreditation); die;
				return true;
			}
		}
		return false;
	}

	function getNBAAccreditation() {
		if($this->isNBAAccredited()) {
			return $this->nbaAccreditation;
		}
	}

	function addRecognition($recognition){
		$this->recognition = $recognition;
	}

	function addEducationType($education_type){
		$this->education_type = $education_type;
	}

	function addDeliveryMethod($delivery_method){
		$this->delivery_method = $delivery_method;
	}

	function addMediumOfInstruction($mediumOfInstruction) {
		$this->medium_of_instruction = $mediumOfInstruction;
	}

	function addPartner($partner) {
		$this->partner = $partner;	
	}

	function addTimeOfLearning($time_of_learning){
		$this->time_of_learning = $time_of_learning;
	}

	function addDifficultyLevel($difficulty_level){
		$this->difficulty_level = $difficulty_level;
	}

	
	function getTimeOfLearning(){
		return $this->time_of_learning;
	}

	function getDifficultyLevel(){
		return $this->difficulty_level;
	}

	function getEligibility($category=array()) {
		$result = array();
		if(!isset($this->eligibility)){
			$callStack = getCallStack(1);
			$callStack = implode(' ', $callStack);
			throw new Exception("Please load the Course Object with eligibility section ".$this->getId()." call stack: $callStack", 1);
		}else{
			foreach ($this->eligibility as $key => $value) {
				$categoryFromObject = $value->getCategory();
				if(!empty($category) && !in_array($categoryFromObject, $category)){
					continue;
				}
				$result[$categoryFromObject][] = $value;
			}
			return $result;
		}
		
	}

	function getCourseTypeInformation() {
		return $this->course_type_information;
	}

	function getDeliveryMethod(){
		return $this->delivery_method;
	}

	function getTotalSeats(){
		return $this->total_seats;
	}

	function getEducationType(){
		return $this->education_type;
	}

	function getLocations(){
		if(!isset($this->locations)){
			$callStack = getCallStack(1);
			$callStack = implode(' ', $callStack);
			throw new Exception("Please load the Course Object with location section ".$this->getId()."call stack: $callStack", 1);
			
		}else{
			return $this->locations;	
		}
		
	}

	function isPaid() {
		return ($this->pack_type == GOLD_SL_LISTINGS_BASE_PRODUCT_ID || $this->pack_type == SILVER_LISTINGS_BASE_PRODUCT_ID || $this->pack_type == GOLD_ML_LISTINGS_BASE_PRODUCT_ID);
	}

	/** 
	* FEES For General Category for OVERALL FEES(TOTAL)
	* IF Location not given, the main fees without location
	* If location given and fees prsent on that location than 
	* that particular location fees else main fees without location
	*/	
	function getFees($listing_location_id=NULL){

		if($listing_location_id != NULL && !isset($this->locations)){
/*			$errObj = new stdClass();
			$errObj->error = "Bhai tumse na ho payega. Locations Needed";
			return $errObj;*/
			$callStack = getCallStack(1);
			$callStack = implode(' ', $callStack);
			throw new Exception("Please load course object with location section ".$this->getId()."call stack: $callStack", 1);
			
		}else if(array_key_exists($listing_location_id, $this->locations)){
			$feesObjArr = $this->locations[$listing_location_id]->getFees();
			if(isset($feesObjArr)){
				return $this->_generalCatFees($feesObjArr);
			}else{
				return $this->_generalCatFees($this->fees);
			}
		}else if($listing_location_id == NULL){
			return $this->_generalCatFees($this->fees);
		}
		else{
			/*$errObj = new stdClass();
			$errObj->error = "Bhai tumse na ho payega. No Such Location Exist for this Course";
			return $errObj;*/
			return NULL;
		}
	}

	/** 
	* FEES For General Category for OVERALL FEES(TOTAL), fees irrespective to locations
	*/
	function getTotalFees(){
		return reset($this->fees);
	}

	/** 
	* FEES For General Category for OVERALL FEES(TOTAL)
	* IF Location not given, the main fees without location
	* If location given and fees prsent on that location than 
	* that particular location fees else main fees without location
	*/
	function getFeesCategoryWise($listing_location_id=NULL){
		$result = array();
		if($listing_location_id != NULL && !isset($this->locations)){
			$callStack = getCallStack(1);
			$callStack = implode(' ', $callStack);
			throw new Exception("Please load course object with location section ".$this->getId()."call stack: $callStack", 1);
			
		}else if(array_key_exists($listing_location_id, $this->locations)){			
			$feesObjArr = $this->locations[$listing_location_id]->getFees();
			if(isset($feesObjArr)){
				$fees = $feesObjArr;
			}else{
				$fees = $this->fees;
			}
		}else if($listing_location_id == NULL){
			$fees = $this->fees;
		}
		else{
			return NULL;
		}

		foreach ($fees as $feesObj) {
			$result[$feesObj->getCategory()] = $feesObj;
		}
		return $result;
	}

	function getFeesCategoryLocationWise($listing_location_id=NULL){
		$result = array();
		if($listing_location_id != NULL && !isset($this->locations)){
			$callStack = getCallStack(1);
			$callStack = implode(' ', $callStack);
			throw new Exception("Please load course object with location section ".$this->getId()."call stack: $callStack", 1);
			
		}else if(array_key_exists($listing_location_id, $this->locations)){			
			$feesObjArr = $this->locations[$listing_location_id]->getFees();
			if(isset($feesObjArr)){
				$fees = $feesObjArr;
			}else{
				return NULL;
			}
		} else{
			return NULL;
		}
		
		foreach ($fees as $feesObj) {
			$result[$feesObj->getCategory()] = $feesObj;
		}
		return $result;
	}

	function getURL(){
		if(empty($this->listing_seo_url) && !empty($this->name)) {
			// throw new Exception('Course seo url not set for course id:'.$this->course_id);
			return addingDomainNameToUrl(array('url' => '/abc/course/def-'.$this->course_id , 'domainName' =>SHIKSHA_HOME));			
		}
		return addingDomainNameToUrl(array('url' => $this->listing_seo_url , 'domainName' =>SHIKSHA_HOME));			
	}

	function getSeoTitle(){
		return $this->seo_title;
	}

	function getSeoDescription(){
		return $this->seo_description;
	}

	function getMainLocation() {		
		return $this->main_location;
	}

	function getAffiliations() {
		return array('university_id' => $this->affiliated_university_id, 'scope' => $this->affiliated_university_scope , 'name' =>$this->affiliated_university_name);
	}

	function isCourseMultilocation(){
	    return 0;
	}

	function getCoursePackType(){
	    return $this->pack_type;
	}

	function getBrochureURL() {
		$this->brochure_url =  addingDomainNameToUrl(array('url' => $this->brochure_url , 'domainName' =>MEDIA_SERVER));
		return $this->brochure_url;
	}

	function getBrochureSize() {
		return $this->brochure_size;
	}

	function getBrochureYear() {
		return $this->brochure_year;
	}

	function getPrimaryHierarchy(){
		$courseTypeInformation = $this->course_type_information;
		$entryCourse = $courseTypeInformation['entry_course'];
		$primaryHierarchy = array();
		if(!empty($entryCourse)) {
			$hierachies = $entryCourse->getHierarchies();			
			foreach ($hierachies as $key => $value) {
				if($value['primary_hierarchy'] == 1){
					$primaryHierarchy = $value;
					break;
				}
			}
		}
		return $primaryHierarchy;
	}

	function getDuration(){
		return array('unit' => $this->duration_unit, 'value' => $this->duration_value , 'showDisclaimer' => $this->duration_disclaimer);
	}

	function getPlacements(){
		if(!isset($this->placements_interships)){
			/*$errObj = new stdClass();
			$errObj->error = "Please load the Course Object with placements_interships section";
			return $errObj;*/
			$callStack = getCallStack(1);
			$callStack = implode(' ', $callStack);
			throw new Exception("Please load the Course Object with placements_internships section ".$this->getId()."call stack: $callStack", 1);
		}else{
			if(!empty($this->placements_interships['placements'])){
				return $this->placements_interships['placements'];
			}else{
				return null;
			}
		}
	}

	function getInternships(){
		if(!isset($this->placements_interships)){
			/*$errObj = new stdClass();
			$errObj->error = "Please load the Course Object with placements_interships section";
			return $errObj;*/
			$callStack = getCallStack(1);
			$callStack = implode(' ', $callStack);
			throw new Exception("Please load the Course Object with placements_interships section ".$this->getId()."call stack: $callStack", 1);
			
		}else{
			if(!empty($this->placements_interships['internship'])){
				return $this->placements_interships['internship'];
			}else{
				return null;
			}
		}
	}

	function getPlacementsAndInternships(){
		if(!isset($this->placements_interships)){
			/*$errObj = new stdClass();
			$errObj->error = "Please load the Course Object with placements_interships section";
			return $errObj;*/
			$callStack = getCallStack(1);
			$callStack = implode(' ', $callStack);
			throw new Exception("Please load the Course Object with placements_interships section ".$this->getId()."call stack: $callStack", 1);
		}else{
			return $this->placements_interships;
		}	
	}

	function isDisabled(){
		if(!empty($this->disabled_url)){
			return true;
		}else{
			return false;
		}
	}

	function getOrder(){
		return $this->course_order;
	}

	function getReviewCount(){
		return $this->review_count;
	}

	function getQuestionsCount(){
		return $this->questions_count;
	}

	private function _generalCatFees($feesObjectArray){
		if(empty($feesObjectArray)) return;
		foreach ($feesObjectArray as $key => $value) {
			if($value->getCategory() == "general"){
				return $value;
			}
		}
		return null;
	}

	function getDisableUrl(){
		$this->disabled_url = addingDomainNameToUrl(array('url' => $this->disabled_url , 'domainName' =>SHIKSHA_HOME));
		return $this->disabled_url;
	}

	function getUSP(){
        return $this->usp_list;
    }

    public function getPhotos()
	{
		return $this->_getMedia('photo');
	}

	public function getVideos()
	{
		return $this->_getMedia('video');
	}

	private function _getMedia($type)
	{
		$mediaList = array();
		foreach($this->media as $media) {
			if($media->getType() == $type) {
				$mediaList[] = $media;
			}
		}
		return $mediaList;
	}

	function getVideoCount(){
        return $this->_getMediaCount('video');
    }
    function getPhotosCount(){
        return $this->_getMediaCount('photo');
    }
        
    private function _getMediaCount($type)
	{
		$mediaCount = 0;
		foreach($this->media as $media) {
			if($media->getType() == $type) {
				$mediaCount++;
			}
		}
		return $mediaCount;
	}

	public function getClientId(){
		return $this->username;
	}

	
	public function getExpiryDate(){
		if(empty($this->expiry_date)) {
			return "0000-00-00 00:00:00";
		}
		return $this->expiry_date;
	}

	function getBaseCourse(){
		$finalBaseCourse = array();
		$courseTypeInfornmation = $this->course_type_information;
		$entryCourse = $courseTypeInfornmation['entry_course'];
		if(empty($entryCourse)){
			return $finalBaseCourse;
		}
		$baseCourse = $entryCourse->getBaseCourse();

		if(!empty($baseCourse)){
			$finalBaseCourse['entry'] = $baseCourse;
		}
		if(!empty($courseTypeInfornmation['exit_course'])){
			$exitCourse = $courseTypeInfornmation['exit_course'];
			$baseCourse = $exitCourse->getBaseCourse();
			if(!empty($baseCourse)){
				$finalBaseCourse['exit'] = $baseCourse;
			}	
		}

		return $finalBaseCourse;
	}	

	function getEligibilityMappedExams(){
		$examList  = array();
		$eligibilityData = $this->getEligibility();
		if($eligibilityData){
			foreach ($eligibilityData as $category => $categoryWiseObj) {
				foreach ($categoryWiseObj as $key => $eligibilityObj) {
					if($eligibilityObj->getExamName() && !in_array($eligibilityObj->getExamName(), $examList))
						$examList[] = $eligibilityObj->getExamName();
				}			
			}			
		}

		return $examList;
	}

	function getOfferedById(){
		return $this->offered_by_id;
	}

	function getOfferedByName(){
		return $this->offered_by_name;
	}
	function getOfferedByShortName(){
		return $this->offered_by_short_name;
	}

	function getAllEligibilityExams($withoutOthers=true) {
		$result = array();
		if(!isset($this->eligibility)){
			$callStack = getCallStack(1);
			$callStack = implode(' ', $callStack);
			throw new Exception("Please load the Course Object with eligibility section ".$this->getId()."call stack: $callStack", 1);
		}else{
			
			foreach ($this->eligibility as $key => $value) {
				if(!empty($value) && is_object($value)){
					$exam_id = $value->getExamId();
					if(!empty($exam_id)){
						$examName = $value->getExamName();
						$result[$exam_id] = $examName;
					} else if(!$withoutOthers){
						$examName = $value->getExamName();
						$result[$examName] = $examName;
					}
				}
				
			}
			return $result;
		}
		
	}

	public function getType(){
		return 'course';
	}

	function getMediumOfInstruction() {
		return $this->medium_of_instruction;
	}

	function getPartner() {
		return $this->partner;
	}

	function isBrochureAutoGenerated() {
		return ($this->is_brochure_auto_generated == 1) ? true : false;
	}
	function getAmpURL()
	{
		$ampUrl = $this->getURL();
		return str_replace('/course/', '/course/amp/', $ampUrl);
	}

	function getObjectAsArray() {
		return get_object_vars($this);
	}
}
?>
