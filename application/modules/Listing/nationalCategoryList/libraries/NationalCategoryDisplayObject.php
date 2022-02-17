<?php


class NationalCategoryDisplayObject
{

    private $CI;
    private $instituteFacilities;
    private $displayLocation;
    private $displayLocationString;
    private $locationSuffixForUrl;
    /*private $displayLocation;
    
    private $feesObj;
    private $isFeesExistsForOtherCategories;*/
     

    function __construct() {
        $this->CI =& get_instance();
        $this->courseDetailLib = $this->CI->load->library('nationalCourse/CourseDetailLib');

        //$this->CI->load->library('nationalCategoryList/NationalCategoryPageDisplayLib');
    }

    public function setDisplayDataObject($instituteObj,$appliedFilters){
        
        $courseObj = reset($instituteObj->getCourses());
        if(is_object($instituteObj) && is_object($courseObj)) {
            $this->_setDisplayFacilites($instituteObj);
            $this->_setDisplayLocation($courseObj,$appliedFilters);
            $this->_setCourseInfoString($courseObj);
        }
        unset($this->CI);
        unset($this->courseDetailLib);
    }

    public function setDisplayDataObjectForLoadMore($courseObj, $appliedFilters){
        if(is_object($courseObj)) {
            $this->_setDisplayLocation($courseObj,$appliedFilters);
            $this->_setCourseInfoString($courseObj);
        }
        unset($this->CI);
        unset($this->courseDetailLib);
    }


    private function _setDisplayLocation($courseObj, $appliedFilters){

        // Get Display Location from Library
        $displayLocation = $this->courseDetailLib->getCourseCurrentLocationWithMultipleLocationsInput($courseObj, $appliedFilters['city'], $appliedFilters['locality'],$appliedFilters['state']);

        // Generate the display location string (locality, city)
        $displayLocationString = "";
        $locationSuffixForUrl = "";
        if(!empty($displayLocation)){
            $displayLocality = $displayLocation->getLocalityName();
            $displayLocalityId = $displayLocation->getLocalityId();
            $displayCity = $displayLocation->getCityName();
            $displayCityId = $displayLocation->getCityId();

            if(!empty($displayLocality)){
                $displayLocationString = $displayLocality.", ";
                $localitySuffix = "locality=".$displayLocalityId;
            }
            if(!empty($displayCity)){
                $displayLocationString .= $displayCity;
                $citySuffix = "city=".$displayCityId;
            }

            if(!empty($localitySuffix) || !empty($citySuffix)){
                $locationSuffixForUrl = "?";
                if(!empty($citySuffix)){
                    $locationSuffixForUrl .= $citySuffix;
                }
                if(!empty($localitySuffix)){
                    $locationSuffixForUrl .= "&".$localitySuffix;
                }
            }
        }

        // Set data in object
        $this->displayLocation = $displayLocation;
        $this->displayLocationString = $displayLocationString;
        $this->locationSuffixForUrl = $locationSuffixForUrl;
    }

    private function _setCourseInfoString($course){

        $this->courseInfoString = getCourseTupleExtraData($course, 'categoryPage', false);
        if(!empty($course) && is_object($course)){
            $courseTypeInfornmation = $course->getCourseTypeInformation();
            $entryCourse = $courseTypeInfornmation['entry_course'];
            $courseLevel = $entryCourse->getCourseLevel();
            $courseLevelName = "";
            if(!empty($courseLevel)){
                $courseLevelName = $courseLevel->getName()." ";
                if($courseLevelName == " "){
                    $courseLevelName = "";
                }
            }

            $credential = $entryCourse->getCredential();
            $credentialName = "";
            if(!empty($credential)){
                $credentialName = $credential->getName()." ";
                if($credentialName == " "){
                    $credentialName = "";
                }
            }
        }
        if(!empty($courseLevelName) || !empty($credentialName)){
            $this->courseInfoString = $courseLevelName."".$credentialName."<i></i>".$this->courseInfoString;
        }

        $recognitions = $course->getRecognition();

        foreach ($recognitions as $key => $value) {
            if($value->getId() == NBA_APPROVAL)    continue;
            $this->courseInfoString = $this->courseInfoString."<i></i>".$value->getName()." Approved";   
        }
        
    }

    private function _setDisplayFacilites($institute){
        
        global $FACILITY_ID_CSS_ICON_NAME_MAPPING;
        global $DISPLAY_FACILTIES_ORDER;
        $displyOrderFaciltites = array_keys($DISPLAY_FACILTIES_ORDER);
        $instituteFacilities = $institute->getFacilities();
        
        $instituteFacilitiesList = array();

        // Fetch All the facilities for Institute
        foreach ($instituteFacilities as $key => $facilityObj) {
            $facilityId = $facilityObj->getFacilityId();
            if($facilityId == 17){
                $childFacitlites = $facilityObj->getChildFacilities();
                foreach ($childFacitlites as $childFacitlitesObj) {
                    if($childFacitlitesObj->getFacilityStatus() == 1){
                        $facilityId = $childFacitlitesObj->getFacilityId();
                        $instituteFacilitiesList[] = $facilityId;        
                    }   
                }
            }else{
                if($facilityObj->getFacilityStatus() == 1){
                    $instituteFacilitiesList[] = $facilityId;    
                }
            }
        }

        // Sort the facilities in defined Order
        $instituteFacilitiesListSorted = array(); // result array
        foreach($displyOrderFaciltites as $val){ // loop
            $index = array_search($val, $instituteFacilitiesList);
            if($index !== false){
                $instituteFacilitiesListSorted[$index] = array($val,$DISPLAY_FACILTIES_ORDER[$val]); // adding values
            }
        }
        $this->instituteFacilities = $instituteFacilitiesListSorted;
    }


    public function getFacilities(){
        return $this->instituteFacilities;
    }

    public function getDisplayLocationString(){
        return $this->displayLocationString;
    }

    public function getDisplayLocation(){
        return $this->displayLocation;
    }

    public function getCourseInfoString(){
        return $this->courseInfoString;
    }

    function getLocationSuffixForUrl(){
        return $this->locationSuffixForUrl;
    }




}