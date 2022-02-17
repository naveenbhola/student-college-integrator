<?php

class University
{
    private $id;
    private $name;
    private $establishmentYear;
    private $acronym;
    private $logoURL;
    private $type;
    private $fundingType;
    private $seoURL;
    private $listing_seo_title;
    private $listing_seo_description;
    private $listing_seo_keywords;
    private $universityLocation;
    private $universityEndowments;
    private $percentIntlStudents;
    private $totalIntlStudents;
	private $campusSize;
	private $campusCount;
	private $maleFemaleRatio;
	private $ugPgRatio;
	private $facultyStudentRatio;
	private $livingExpenses;
	private $accomodationDetails;
	private $accomodationWebsite;
	private $conditionalOffer;
	private $scoreReporting;
	private $campuses;
	private $announcement;
	private $media;
	private $universityDefaultImgUrl;
	private $applicationProfiles;
	private $contactEmail;
	private $contactNumber;
	private $contactWebsite;
	private $contactPersonName;
	private $highlights;
    private $contactDetails;
	private $wiki;

	private $brochureURL;
	private $website;
	private $facebookURL;
	private $indianConsultantsURL;
	private $intlStudentsURL;

	private $indianStudentsURL;
	private $affiliation;
	private $accreditation;
	private $seoDetails;
	private $courseCount;
	private $courses;
    private $cumulativeViewCount;
    private $packType;
    private $expertId;
    private $campusAccommodation;
    private $admissionContact;
    

	function __set($property,$value)
    {
        $this->$property = $value;
    }

    public function addMedia(ListingMedia $media)
    {
        $this->media = $media;
    }

     public function addLocation(UniversityLocation $location)
    {
        $this->universityLocation = $location;
    }

    public function addCampus(Campus $campus)
    {
        $this->campuses = $campus;
    }

    public function addCampusAccommodation(CampusAccommodation $campusAccommdation)
    {
        $this->campusAccommodation = $campusAccommdation;
    }

    public function addAnnouncement(UniversityAnnouncement $announcement) {
        $this->announcement = $announcement;
    }

    public function addContactDetails(ContactDetail $contactDetails) {
        $this->contactDetails = $contactDetails;
    }

    public function addAdmissionContact(AdmissionContact $admissionContact) {
        $this->admissionContact = $admissionContact;
    }

    function getId()
    {
        return $this->id;
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
        return $this->establishmentYear;
    }

    function getLogoLink()
    {
        if(!empty($this->logoURL)){
            return MEDIAHOSTURL.$this->logoURL;    
        }
        return $this->logoURL;
    }

    function getTypeOfInstitute()
    {
        return $this->fundingType;
    }
    
    function getTypeOfInstitute2()
    {
        return $this->type;
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
        if(!empty($this->brochureURL)){
            return MEDIAHOSTURL.$this->brochureURL;
        }
        return $this->brochureURL;
    }
   
    public function getLocation()
    {
        return $this->universityLocation;
    }
    
    public function getMainLocation()
    {
        return $this->universityLocation;
    }
    
    public function getLocations()
    {
        return array($this->id=>$this->universityLocation);
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
        return $this->facebookURL;
    }
    
    public function getWebsiteLink()
    {
        return $this->website;
    }
    
    public function getIndianConsultantsPageLink()
    {
        return $this->indianConsultantsURL;
    }
    
    public function getInternationalStudentsPageLink()
    {
        return $this->intlStudentsURL;
    }
    
    public function getWhyJoin()
    {
        return $this->highlights;
    }

    //todo
    function getURL()
    {   
        if(!empty($this->seoURL)){
            return SHIKSHA_STUDYABROAD_HOME.$this->seoURL;
        }
        return $this->seoURL;
    }

    //todo
    public function getMetaData()
    {
        $universityName = htmlentities($this->getName());
        $countryName = htmlentities($this->getLocation()->getCountry()->getName());

        $this->listing_seo_title    = ($this->listing_seo_title       == ""? $universityName.", ".$countryName." | Shiksha.com" :$this->listing_seo_title   );
        $this->listing_seo_description  = ($this->listing_seo_description == ""? "Find complete information about ".$universityName.", ".$countryName." like courses offered, campus placements, fee structure, contact details, ranking and more on Shiksha.com" :$this->listing_seo_description);
        
        return array(
                                 'seoTitle' => $this->listing_seo_title,
                                 'seoKeywords' => $this->listing_seo_keywords,
                                 'seoDescription' => $this->listing_seo_description
                                 );
    }

    function isPublicalyFunded()
    {
        return $this->fundingType == "public" ? true : false;
    }

    function getCumulativeViewCount() {
        
        return $this->cumulativeViewCount;
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

    //todo
    public function setSortOrderForSimilarCourses($courseIdsInOrder = array()){
        $this->sortedOrderForSimilarCourses = $courseIdsInOrder;
    }

    //todo
    public function getSortOrderForSimilarCourses(){
        return $this->sortedOrderForSimilarCourses;
    }


    public function getMedia(){
        return $this->media;
    }


    function getAnnouncement() {
        return $this->announcement;
    }

    public function setCourses($courseObjArray){
        $this->courses = $courseObjArray;
    }
    
    public function getCourses(){
        return $this->courses;
    }
    
    public function setExpertId($expertId){
        $this->expertId = $expertId;
    }
    public function getExpertId(){
        return $this->expertId;
    }

    public function addCourse($courseObj){
        if(empty($this->courses) || is_array($this->courses)){
            $this->courses[$courseObj->getId()] = $courseObj;
        }
    }
    
    public function removeCourse($courseId){
        unset($this->courses[$courseId]);
    }
    public function getUniversityDefaultImgUrl($size='')
    {       
        $replace = ""; 
		if($size == "medium"){
            $replace = "_m.";
		}elseif($size == "large"){
            $replace = "_l.";
		}elseif($size == "300x200"){
            $replace = "_300x200.";
		}elseif($size == "172x115"){
            $replace = "_172x115.";
		}elseif($size == "75x50"){
            $replace = "_75x50.";
		}elseif($size == "135x90"){
            $replace = "_135x90.";
		}
        if($replace == "")
        {
            return MEDIAHOSTURL.$this->universityDefaultImgUrl;
        }else{
            return MEDIAHOSTURL.str_replace(".",$replace,$this->universityDefaultImgUrl);
        }
    }
    public function cleanForCategoryPage(){
         unset($this->admissionContact);
        // unset($this->snapshotDepartments);
        unset($this->highlights);
        unset($this->affiliation);
        unset($this->accreditation);
        unset($this->brochureURL);                
        unset($this->campuses);
        unset($this->contactDetails);
        foreach($this->media as $key => $value) {
            if($value->getType() == "photo") {
                $this->media = array($value);
                break;
            }
        }
        unset($this->facebookURL);
        unset($this->website);
        unset($this->indianConsultantsURL);
        unset($this->intlStudentsURL);
        //unset($this->sortedOrderForSimilarCourses);
        unset($this->acronym);
        unset($this->logoURL);
        //unset($this->departments);
        unset($this->listing_seo_title);
        unset($this->listing_seo_description);
        unset($this->listing_seo_keywords);
        unset($this->cumulativeViewCount);
        unset($this->packType);
        unset($this->establishmentYear);
        $this->campusAccommodation->cleanForCategoryPage();
        foreach($this->universityLocation as $key=>$location){
            $location->cleanForCategoryPage();
            $this->universityLocation = $location;
        }
        unset($this->type);
    }

	// function setName($name)
 //    {
 //        $this->name = $name;
 //    }
}
