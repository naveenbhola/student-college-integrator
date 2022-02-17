<?php
class Institute {

	// Basic details
	private $listing_id;
	private $name;
	private $listing_type;
	private $short_name;
	private $institute_specification_type;
	private $university_specification_type;
	private $university_type;
	private $is_dummy;
	private $disabled_url;
	private $parent_listing_id;
	private $parent_listing_type;
	private $parent_listing_name;
	private $primary_listing_type;
	private $primary_listing_id;
	private $is_satellite;
	private $abbreviation;
	private $synonym;
	private $establish_year;
	private $establish_university_year;
	private $ownership;
	private $student_type;
	private $is_autonomous;
	private $is_national_importance;
	private $is_aiu_membership;
	private $is_open_university;
	private $is_ugc_approved;
	private $logo_url;
	private $accreditation;
	private $brochure_url;
	private $brochure_size;
	private $brochure_year;
	private $admission_cta_pdf_url;
	private $courses_cta_pdf_url;
	private $questions_cta_pdf_url;
	private $scholarship_cta_pdf_url;
	private $reviews_cta_pdf_url;
	private $cutoff_cta_pdf_url;
	private $pack_type;
	private $seo_url;
	private $seo_title;
	private $seo_description;
	private $main_location;
	private $courses;
	private $admissionDetails;
	private $admissionPostedDate;
	private $showAdmissionFlag;
	private $placementPageExists;
	private $flagshipCoursePlacementDataExists;
	private $naukriPlacementDataExists;
	private $cutoffPageExists;
	private $cutoffPageExamName;
	private $reviewPageExists;
	private $admissionPageExists;
	private $allCoursePageExists;
	private $aboutCollege;
	private $aboutCollegeCutOff;
	private $aboutCollegePlacement;
	private $childPages = array('articles', 'questions', 'reviews', 'courses','admission','scholarships','cutoff','placement');
	
	// Location Details
	private $locations;

	// Academics Staff Details
	private $academic_staff;

	// Research projects
	private $research_projects;

	// USP
	private $usp_list;

	// Event Data
	private $events;

	// Scholarships
	private $scholarships;

	// Companies
	private $companies;

	// Media
	private $media;

	// Facilities
	private $facilities;

	//secondary name
	private $secondary_name;

	//seo data
	private $seoData;

	function getSecondaryName(){
		return $this->secondary_name;
	}

	function getId(){
		return $this->listing_id;
	}

	function getType(){
		return $this->listing_type;
	}
	
	function getDisableUrl(){
		$this->disabled_url =  addingDomainNameToUrl(array('url' => $this->disabled_url , 'domainName' =>SHIKSHA_HOME));
		return $this->disabled_url;
	}

	function getAbbreviation(){
		return $this->abbreviation;
	}

	function isDummy(){
		return $this->is_dummy;
	}

	function getMainLocation(){
		return $this->main_location;
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

	function addEventList($eventList){
		$this->events = $eventList;	
	}

	function addScholarshipList($scholashipList){
		$this->scholarships = $scholashipList;
	}

	function addCompanyList($companiesList){
		$this->companies = $companiesList;
	}

	function addMediaList($mediaList){
		$this->media = $mediaList;
	}

	function addFacilities($facilities){
		$this->facilities = $facilities;
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
     
    public function  getInstituteSpecificationType(){
    	return $this->institute_specification_type;
    } 
    
	function getLocations(){
		return $this->locations;
	}

	public function addCourse($course)
	{
		$this->courses[] = $course;
	}

	public function getCourse()
	{
		return $this->courses;
	}

	function getShortName(){
		return $this->short_name;
	}

	function getName(){
		return $this->name;
	}

	function setName($name){
		$this->name = $name;
	}

	function isNationalImportance(){
		return $this->is_national_importance;
	}

	function getURL(){
		$this->seo_url =  addingDomainNameToUrl(array('url' => $this->seo_url , 'domainName' =>SHIKSHA_HOME));
		return $this->seo_url;
	}

	function getSeoTitle(){
		return $this->seo_title;
	}

	function getSeoDescription(){
		return $this->seo_description;
	}

	function getSynonym(){
		return $this->synonym;
	}

	function getAccreditation(){
		if($this->accreditation) {
			list($grade, $accreditation) = explode('_', $this->accreditation);
			return strtoupper($accreditation);

		}
		return $this->accreditation;
	}

	function getOwnership(){
		return $this->ownership;
	}

	function getLogoUrl(){
		$this->logo_url =  addingDomainNameToUrl(array('url' => $this->logo_url , 'domainName' =>MEDIA_SERVER));
		return $this->logo_url;
	}

	function getFacilities(){
		return $this->facilities;
	}

	function __set($property,$value)
	{
		$this->$property = $value;
	}
        
    function getHeaderImage($locationId = ""){

		foreach($this->media as $media) {
			if($media->getType() == 'photo') {
				if($locationId){
					$locationIds = $media->getLocationIds();
					if(in_array($locationId, $locationIds) || $media->isMappedToAllLocation()){
						return $media;
					}
				}else{
					return $media;	
				}
			}
		}
		return "";
            
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
	function getBrochureURL() {
		$this->brochure_url =  addingDomainNameToUrl(array('url' => $this->brochure_url , 'domainName' =>MEDIA_SERVER));
		return $this->brochure_url;
	}

	function getAdmissionCtaPdfURL() {
		$this->admission_cta_pdf_url =  addingDomainNameToUrl(array('url' => $this->admission_cta_pdf_url , 'domainName' =>MEDIA_SERVER));
		return $this->admission_cta_pdf_url;
	}

	function getCoursesCtaPdfURL() {
		$this->courses_cta_pdf_url =  addingDomainNameToUrl(array('url' => $this->courses_cta_pdf_url , 'domainName' =>MEDIA_SERVER));
		return $this->courses_cta_pdf_url;
	}

	function getQuestionsCtaPdfURL() {
		$this->questions_cta_pdf_url =  addingDomainNameToUrl(array('url' => $this->questions_cta_pdf_url , 'domainName' =>MEDIA_SERVER));
		return $this->questions_cta_pdf_url;
	}

	function getScholarshipCtaPdfURL() {
		$this->scholarship_cta_pdf_url =  addingDomainNameToUrl(array('url' => $this->scholarship_cta_pdf_url , 'domainName' =>MEDIA_SERVER));
		return $this->scholarship_cta_pdf_url;
	}

	function getReviewsCtaPdfURL() {
		$this->reviews_cta_pdf_url =  addingDomainNameToUrl(array('url' => $this->reviews_cta_pdf_url , 'domainName' =>MEDIA_SERVER));
		return $this->reviews_cta_pdf_url;
	}

	function getCutoffCtaPdfURL() {
		$this->cutoff_cta_pdf_url =  addingDomainNameToUrl(array('url' => $this->cutoff_cta_pdf_url , 'domainName' =>MEDIA_SERVER));
		return $this->cutoff_cta_pdf_url;
	}

	function getPackType(){
		return $this->pack_type;
	}

	function getBrochureSize() {
		return $this->brochure_size;
	}

	function getBrochureYear() {
		return $this->brochure_year;
	}

	function getListingType(){
		return $this->listing_type;
	}
	function getUniversitySpecificationType(){
		return $this->university_specification_type;
	}

	function isAutonomous(){
		return $this->is_autonomous;
	}

	function getEstablishedYear(){
		return $this->establish_year;
	}

	public function getCourses()
	{
		if(!is_array($this->courses) || count($this->courses) == 0) {
			return FALSE;
		}

		/*
		 * Sort by course order
		 */
		$tempCourses = array();
		foreach($this->courses as $course){
			if(is_object($course)) {
				$id = $course->getId();
				if(!empty($id)){
					$tempCourses[] = $course;
				}
			}
		}

		$this->courses = $tempCourses;
		$courses = $this->courses;
		usort($courses,array('Institute','sortCoursesByCourseOrder'));
		return $courses;
	}	

	public static function sortCoursesByCourseOrder($course1,$course2)
	{
		if(!empty($course1) && !empty($course2)){
			return intval($course1->getOrder()) - intval($course2->getOrder());
		} else if(!empty($course1) && empty($course2)){
			return intval($course1->getOrder());
		} else if(empty($course1) && !empty($course2)){
			return intval($course2->getOrder());
		} else {
			return -1;
		}
	}

	public function getFlagshipCourse()
	{
		$courses = $this->getCourses();
		foreach ($courses as $key => $value) {
			if($value->getOrder() == 1){
				return $value;
			}
		}
		return "";
	}

    function getUSP(){
        return $this->usp_list;
    }

    function getEvents(){
        return $this->events;
    }

    function getScholarships(){
        return $this->scholarships;
    }

    function getStudentType(){
    	return $this->student_type;
    }

    function addCourses($courses){

    	if(empty($courses))
    		return;

    	foreach ($courses as $course) {
    		$this->addCourse($course);
    	}

    	// sort courses by course order
    	usort($this->courses,array('Institute','sortCoursesByCourseOrder'));
    }

    function isUGCApproved(){
    	return ($this->is_ugc_approved ? true : false);
    }

    function isAIUMember(){
    	return ($this->is_aiu_membership ? true : false);
    }

    function getAdmissionDetails(){
    	return $this->admissionDetails;
    }

    function getAdmissionPostedDate(){
    	return $this->admissionPostedDate;
    }

    function isAdmissionDetailsAvailable(){
    	return $this->showAdmissionFlag;
    }

    function setAdmissionDetailsFlag($showAdmissionFlag){
    	$this->showAdmissionFlag = $showAdmissionFlag;
    }
    function getAmpURL()
	{
		$ampUrl = $this->getURL();
		if($this->listing_type == 'university'){
			return str_replace('/university/', '/university/amp/', $ampUrl);
		}else if($this->listing_type == 'institute')
			return str_replace('/college/', '/college/amp/', $ampUrl);
	}


	function getAllContentPageUrl($typeOfPage) {
		if(in_array($typeOfPage, $this->childPages) ) {
			return $this->getURL().'/'.$typeOfPage;
		}
	}

	function getRelativeURL(){
		return $this->seo_url;
	}

	function getRelativeAllContentPageUrl($typeOfPage) {
		if(in_array($typeOfPage, array('articles', 'questions', 'reviews', 'courses','admission','scholarships','cutoff', 'placement')) ) {
			return $this->getRelativeURL().'/'.$typeOfPage;
		}
	}

	function getChildPages(){
		$childPageDataMethodMap = array('reviews' => 'isReviewPageExists',
										'courses' => 'isAllCoursePageExists',
										'admission' => 'isAdmissionPageExists',
										'cutoff' => 'isCutoffPageExists',
										'placement' => 'isPlacementPageExists');
		$childPageExists = array();

		foreach ($this->childPages as $childPage){
			if(array_key_exists($childPage, $childPageDataMethodMap)){

				if($this->{$childPageDataMethodMap[$childPage]}())
					$childPageExists[] = $childPage;
			}
		}

		return $childPageExists;
	}

	function isPlacementPageExists() {
		return $this->placementPageExists;
	}

	function isFlagshipCoursePlacementDataExists() {
		return $this->flagshipCoursePlacementDataExists;
	}

	function isNaukriPlacementDataExists() {
		return $this->naukriPlacementDataExists;
	}

	function isCutoffPageExists() {
		return $this->cutoffPageExists;
	}

	function getCutoffPageExamName() {
		return $this->cutoffPageExamName;
	}

	function isReviewPageExists() {
		return $this->reviewPageExists;
	}

	function isAdmissionPageExists() {
		return $this->admissionPageExists;
	}

	function isAllCoursePageExists() {
		return $this->allCoursePageExists;
	}

	function getAboutCollege() {
		return $this->aboutCollege;
	}

	function getAboutCollegeCutOff() {
		return $this->aboutCollegeCutOff;
	}

	function getAboutCollegePlacement() {
		return $this->aboutCollegePlacement;
	}

	function getPropertyObject($property) {
		switch ($property) {
			case 'main_location':
				return new NationalLocation();

			case 'contact_details':
				return new NationalContact();

			case 'locations':
				return array(new NationalLocation());
			
			case 'usp_list':
				return array(new NationalUSP());

			case 'events':
				return array(new NationalEvent());

			case 'scholarships':
				return array(new NationalScholarship());

			case 'companies':
				return array(new NationalCompany());

			case 'media':
				return array(new NationalMedia());

			case 'facilities':
				return array(new NationalFacility());

			case 'child_facilities':
				return array(new NationalFacility());

			default:
				# code...
				break;
		}
	}

	function getSeoData(){
        return $this->seoData;
    }

    function addSeoData($seoData){
		$this->seoData = $seoData;
	}
}
?>
