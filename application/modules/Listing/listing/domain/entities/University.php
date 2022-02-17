<?php

class UniversityOld
{
    private $id;
    private $university_id;
    private $name;
    private $acronym;
    private $establish_year;
    private $logo_link;
    private $type_of_institute;
    private $type_of_institute2;
    private $affiliation;
    private $accreditation;
    private $brochure_link;
    private $percentage_profile_completion;
    private $status;
    
    private $departments;
    private $courses;       // Utilised on categoryPage
    
    private $listing_seo_url;
    private $listing_seo_title;
    private $listing_seo_description;
    private $listing_seo_keywords;
    private $cumulativeViewCount;
    private $pack_type;
    
    private $locations;
    private $admissionContact;
    private $snapshotDepartments;
    private $campusAccommodation;
    private $campuses;
    private $contactDetails;
    private $media;
    private $snapshotCourses;
    
    private $facebook_page;
    private $website_link;
    private $india_consultants_page_link;
    private $international_students_page_link;
    private $sortedOrderForSimilarCourses;
    private $why_join;
    
    private $announcement;
    
    
    function __construct()
    {
    
    }
    
    function __clone()
    {
        foreach($this->departments as $key=>$dept)
        $this->departments[$key] = clone $dept;
    }
    
    function getId()
    {
        return $this->university_id;
    }
    
    function getName()
    {
        return $this->name;
    }
    
    function getAcronym()
    {
        return $this->acronym;
    }
    
    function getEstablishedYear()
    {
        return $this->establish_year;
    }
    
    function getLogoLink()
    {
        if(!empty($this->logo_link)){
            return MEDIAHOSTURL.$this->logo_link;    
        }
        return $this->logo_link;
    }
    
    function getTypeOfInstitute()
    {
        return $this->type_of_institute;
    }
    
    function getTypeOfInstitute2()
    {
        return $this->type_of_institute2;
    }
    
    function getAffiliation()
    {
        return $this->affiliation;
    }
    
    function getAccreditation()
    {
        return $this->accreditation;
    }
    
    function getBrochureLink()
    {
        if(!empty($this->brochure_link)){
            return MEDIAHOSTURL.$this->brochure_link;
        }
        return $this->brochure_link;
    }
    
    function getPercentageProfileCompletion()
    {
        return $this->percentage_profile_completion;
    }
    
    function getStatus()
    {
        return $this->status;
    }
    
    public function addLocation(UniversityLocation $location)
    {
        $this->locations[$location->getLocationId()] = $location;
    }
    
    public function setAdmissionContact(AdmissionContact $admissionContact)
    {
        $this->admissionContact = $admissionContact;
    }
    
    public function addSnapshotDepartment(SnapshotDepartment $snapshotDepartment)
    {
        $this->snapshotDepartments[] = $snapshotDepartment;
    }
    
    public function addSnapshotCourse(SnapshotCourse $snapshotCourse)
    {
        $this->snapshotCourses[] = $snapshotCourse;
    }
    
    public function setCampusAccommodation(CampusAccommodation $campusAccommdation)
    {
        $this->campusAccommodation = $campusAccommdation;
    }
    
    public function setContactDetails(ContactDetail $contactDetails)
    {
        $this->contactDetails = $contactDetails;
    }
    
    public function addCampus(Campus $campus)
    {
        $this->campuses[] = $campus;
    }
    
    public function addMedia(ListingMedia $media)
    {
        $this->media[] = $media;
    }
    
    public function getLocation()
    {
        return reset($this->locations);
    }
    
    public function getMainLocation()
    {
        return reset($this->locations);
    }
    
    public function getLocations()
    {
        return $this->locations;
    }
    
    public function getAdmissionContact()
    {
        return $this->admissionContact;
    }
    
    public function getMainHeaderImage()
    {
        return reset($this->_getMedia('photo'));
    }
    
    public function getPhotos()
    {
        return $this->_getMedia('photo');
    }
    
    public function getPhotoCount() {
        return count($this->_getMedia('photo'));
    }

    public function getVideos()
    {
        return $this->_getMedia('video');
    }
    
    public function getVideoCount()
    {
        return count($this->_getMedia('video'));
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
    
    public function getSnapshotDepartments()
    {
        return $this->snapshotDepartments;
    }
    
    public function getSnapshotCourses($snapshotRepository,$categoryPageFlag) //Repo is optionally passed to this function
    {
        if(empty($snapshotRepository)){
            $CI = &get_instance();
            $builder = $CI->load->builder('ListingBuilder','listing');
            $builder = new ListingBuilder;
            $snapshotRepository = $builder->getSnapshotCourseRepository();
        }
        return $snapshotRepository->findMultiple($this->snapshotCourses,$categoryPageFlag);
    }
    
    public function getCampusAccommodation()
    {
        return $this->campusAccommodation;
    }
    
    /***
     *  Below Funtion will return true if Campus Accomodation Section in univesity CMS form have any filled data.
     */
    
    public function hasCampusAccommodation()
    {
    	return $this->campusAccommodation->hasAnyInformation();
    }
    
    public function getCampuses()
    {
        return $this->campuses;
    }
    
    public function getContactDetails()
    {
        return $this->contactDetails;
    }
    
    public function getFacebookPage()
    {
        return $this->facebook_page;
    }
    
    public function getWebsiteLink()
    {
        return $this->website_link;
    }
    
    public function getIndianConsultantsPageLink()
    {
        return $this->india_consultants_page_link;
    }
    
    public function getInternationalStudentsPageLink()
    {
        return $this->international_students_page_link;
    }
    
    public function getWhyJoin()
    {
        return $this->why_join;
    }
    
    function __set($property,$value)
    {
        $this->$property = $value;
    }
    
    function getURL()
    {   
        if(!empty($this->listing_seo_url)){
            return SHIKSHA_STUDYABROAD_HOME.$this->listing_seo_url;
        }
        return $this->listing_seo_url;
    }
    public function getMetaData()
    {
        $universityName = htmlentities($this->getName());
        $countryName = htmlentities($this->getLocation()->getCountry()->getName());

        $this->listing_seo_title 	= ($this->listing_seo_title 	  == ""? $universityName.", ".$countryName." | Shiksha.com" :$this->listing_seo_title 	);
        $this->listing_seo_description 	= ($this->listing_seo_description == ""? "Find complete information about ".$universityName.", ".$countryName." like courses offered, campus placements, fee structure, contact details, ranking and more on Shiksha.com" :$this->listing_seo_description);
        
        return array(
                                 'seoTitle' => $this->listing_seo_title,
                                 'seoKeywords' => $this->listing_seo_keywords,
                                 'seoDescription' => $this->listing_seo_description
                                 );
    }
    
    function addDepartment(AbroadInstitute $institute)
    {
    	$this->departments [] = $institute;
    }
    
    function setDepartment($institutes)
    {
    	$this->departments [] = $institutes;
    }
    
    function getDepartments()
    {
    	if(!empty($this->departments) && count($this->departments)< 0)
    	{
    		return false;
    	}
    	else {
    		return  $this->departments;
    	}
    }
    
    function setDepartments($institutes = array())
    {
    	$this->departments = $institutes;
    }
    
    
    function isPublicalyFunded()
    {
    	return $this->type_of_institute == "public" ? true : false;
    }
    
    function getCumulativeViewCount() {
    	
    	return $this->cumulativeViewCount;
    }
    
    public function removeDepartment($departmentId)
    {
            foreach($this->departments as $key=>$department)
            {
                    if($department->getId() == $departmentId)
                            unset($this->departments[$key]);
            }
    }
    
    public function setSticky() {
    	$this->sticky = TRUE;
    }
    
    public function isSticky() {
    	return $this->sticky;
    }
    
    public function setMain() {
    	$this->main = TRUE ;
    }
    
    public function isMain() {
    	return $this->main;
    }
    
    public function setSortOrderForSimilarCourses($courseIdsInOrder = array()){
        $this->sortedOrderForSimilarCourses = $courseIdsInOrder;
    }
    
    public function getSortOrderForSimilarCourses(){
        return $this->sortedOrderForSimilarCourses;
    }
    
    public function getMedia(){
        return $this->media;
    }
    
    public function setAnnouncement(UniversityAnnouncement $announcement) {
        $this->announcement = $announcement;
    }
    
    function getAnnouncement() {
        return $this->announcement;
    }
    
    public function removeSnapshotCourse($snapshotCourseId){
        if(($key = array_search($snapshotCourseId, $this->snapshotCourses)) !== false) {
            unset($this->snapshotCourses[$key]);
        }
    }
    
    public function sortInternalSnapshotCourses($courseCountTable){
        //This function sorts the snapshot courses within the university.
        //View counts of the snapshot courses are provided as parameter.
        usort($this->snapshotCourses,function($c1,$c2) use ($courseCountTable){
            $c1Count = empty($courseCountTable[$c1])?0:$courseCountTable[$c1];
            $c2Count = empty($courseCountTable[$c2])?0:$courseCountTable[$c2];
            return -1*($c1Count - $c2Count);
        });
    }
    
    public function getMaxViewCountOfInternalSnapshotCourses($courseCountTable){
        //View counts of the snapshot courses are provided as parameter.
        //Input table may be superset of courses.
        $internalViewCounts = array();
        foreach($this->snapshotCourses as $course){
            $internalViewCounts[$course] = empty($courseCountTable[$course])?0:$courseCountTable[$course];
        }
        return max($internalViewCounts);
    }
    
    public function filterSnapshotCoursesForCategoryPage($subCategoryIds,$courseLevel,$snapshotCourseRepository){
        /*foreach($this->snapshotCourses as $key=>$course){
            //echo $course->getCategoryId();
            if(!in_array($course->getCategoryId(),$subCategoryIds)){
                unset($this->snapshotCourses[$key]);
            }
            else if(strcasecmp($courseLevel,$course->getType())!=0){
                unset($this->snapshotCourses[$key]);
            }
        }*/
        // Now using removeSnapshotCourse
        $courses = $this->getSnapshotCourses($snapshotCourseRepository);
        foreach($courses as $course){
            if(!in_array($course->getCategoryId(),$subCategoryIds)){
                $this->removeSnapshotCourse($course->getId());
            }else if(strcasecmp($courseLevel,$course->getType())!=0){
                $this->removeSnapshotCourse($course->getId());
            }
            
        }
        
    }
    
    public function filterSnapshotCoursesForSubCategoryPage($subcategoryId,$courseLevel,$snapshotCourseRepository){
        $courses = $this->getSnapshotCourses($snapshotCourseRepository);
        foreach($courses as $key=>$course){
            if($course->getCategoryId()!=$subcategoryId){
                $this->removeSnapshotCourse($course->getId());
            }
            else if(strcasecmp($courseLevel,$course->getType())!=0){
                $this->removeSnapshotCourse($course->getId());
            }
        }
    }
    
    public function addSnapshotId($snapshotId){
        if(!is_array($this->snapshotCourses) && empty($this->snapshotCourses)){
            $this->snapshotCourses = array();
        }
        $this->snapshotCourses[] = $snapshotId;
    }
    
    public function setSnapshotCoursesArray($array){
        $this->snapshotCourses = $array;
    }
    
    public function getSnapshotCoursesArray(){
        return $this->snapshotCourses;
    }
    
    public function setCourses($courseObjArray){
        $this->courses = $courseObjArray;
    }
    
    public function getCourses(){
        return $this->courses;
    }
    
    public function addCourse($courseObj){
        if(empty($this->courses) || is_array($this->courses)){
            $this->courses[$courseObj->getId()] = $courseObj;
        }
    }
    
    public function removeCourse($courseId){
        unset($this->courses[$courseId]);
    }
    
    public function cleanForCategoryPage(){
        unset($this->admissionContact);
        unset($this->snapshotDepartments);
        unset($this->why_join);
        unset($this->affiliation);
        unset($this->accreditation);
        unset($this->brochure_link);
        unset($this->percentage_profile_completion);
        unset($this->status);
        unset($this->campuses);
        unset($this->contactDetails);
        foreach($this->media as $key => $value) {
            if($value->getType() == "photo") {
                $this->media = array($value);
                break;
            }
        }
        unset($this->facebook_page);
        unset($this->website_link);
        unset($this->india_consultants_page_link);
        unset($this->international_students_page_link);
        unset($this->sortedOrderForSimilarCourses);
        unset($this->acronym);
        unset($this->logo_link);
        unset($this->departments);
        unset($this->listing_seo_title);
        unset($this->listing_seo_description);
        unset($this->listing_seo_keywords);
        unset($this->cumulativeViewCount);
        unset($this->pack_type);
        unset($this->establish_year);
        $this->campusAccommodation->cleanForCategoryPage();
        foreach($this->locations as $key=>$location){
            $location->cleanForCategoryPage();
            $this->locations[$key] = $location;
        }
        unset($this->type_of_institute2);
    }

    
}
