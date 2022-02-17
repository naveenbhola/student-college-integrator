<?php

class msearchDisplayObject {
	private $CI;
	private $displayLocation;
    private $displayLocationString;
    private $fees;
    private $feesUnit;
    private $courseStatusString;
    private $showExams;
    private $locationSuffixForUrl;

    function __construct() {
        $this->CI =& get_instance();
        $this->courseDetailLib = $this->CI->load->library('nationalCourse/CourseDetailLib');
        $this->abroadLib = $this->CI->load->library('listing/AbroadListingCommonLib');
    }

    public function setDisplayDataObject($instituteObj,$appliedFilters){
        
        $courseObj = reset($instituteObj->getCourses());
        if(is_object($instituteObj) && is_object($courseObj)) {
            $this->_setDisplayLocation($courseObj,$appliedFilters);
            $this->_setCourseInfoString($courseObj);
            $this->_setFees($courseObj);
            // $this->_setCourseStatus($courseObj);
            $this->setIsShowExams($courseObj);
        }
        unset($this->CI);
        unset($this->courseDetailLib);
        unset($this->abroadLib);
    }

    public function setDisplayDataObjectForLoadMore($courseObj,$appliedFilters){
        if(is_object($courseObj)) {
            $this->_setDisplayLocation($courseObj,$appliedFilters);
            $this->_setCourseInfoString($courseObj);
            $this->_setFees($courseObj);
            // $this->_setCourseStatus($courseObj);
            $this->setIsShowExams($courseObj);
        }
        unset($this->CI);
        unset($this->courseDetailLib);
        unset($this->abroadLib);
    }

    public function setDisplayDataObjectForRecommendationOverlay($courseObj){
        if(is_object($courseObj)) {
            $this->_setDisplayLocation($courseObj);
            $this->_setCourseInfoString($courseObj);
            $this->_setFees($courseObj);
            // $this->_setCourseStatus($courseObj);
            $this->setIsShowExams($courseObj);
        }
        unset($this->CI);
        unset($this->courseDetailLib);
        unset($this->abroadLib);
    }

    public function setDisplayDataObjectForAllCoursesPage($courseObj){
        if(is_object($courseObj)) {
            $this->_setDisplayLocation($courseObj);
            $this->_setCourseInfoString($courseObj);
            $this->_setFees($courseObj);
            // $this->_setCourseStatus($courseObj);
            $this->setIsShowExams($courseObj);
        }
        unset($this->CI);
        unset($this->courseDetailLib);
        unset($this->abroadLib);
    }

    private function _setDisplayLocation($courseObj, $appliedFilters){

        // Get Display Location from Library
        $locationSuffixForUrl = "";
        $displayLocation = $this->courseDetailLib->getCourseCurrentLocationWithMultipleLocationsInput($courseObj, $appliedFilters['city'], $appliedFilters['locality'],$appliedFilters['state']);

        // Generate the display location string (locality, city)
        $displayLocationString = "";
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

    private function _setFees($course){
    	$displayLocation = $this->getDisplayLocation();
        if(empty($displayLocation)) return;
    	$feesObj = $course->getFeesCategoryWise($displayLocation->getLocationId());
    	$courseFees = '-';
		$courseFeesUnit = '';

		if(!empty($feesObj['general'])){
		    $courseFees = $feesObj['general']->getFeesValue();
		    $courseFeesUnit = $feesObj['general']->getFeesUnit();
		}
		unset($feesObj['general']);
		$this->fees = $courseFees;
		$this->feesUnit = $courseFeesUnit;        
        if($courseFeesUnit != 1 && !empty($courseFeesUnit) && $courseFees != "-"){
            $this->fees = $this->abroadLib->convertCurrency($courseFeesUnit,1,$this->fees);
            $this->feesUnit = 1;
        }

        if($this->fees != "-"){
            $this->fees = getRupeesDisplableAmount($this->fees);
        }
    }

    private function _setCourseInfoString($course){
        $this->courseInfoString = getCourseTupleExtraData($course, 'mobileCategoryPage', false);        
    }

    private function _setCourseStatus($course){
        $courseId = $course->getId();
        if(!empty($courseId)){
            $courseStatusData = $this->courseDetailLib->getCourseStatus(array($course->getId()));
            $courseStatus = implode(", ", $courseStatusData[$course->getId()]['courseStatusDisplay']);
            $courseStatus = (strlen($courseStatus) > 112) ? substr($courseStatus, 0, 110).'..' : $courseStatus;
            $this->courseStatusString = $courseStatus;
        }
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

    public function getFees(){
    	return array(
    		'fees' => $this->fees,
    		'feesUnit' => $this->feesUnit
    	);
    }

    public function getCourseStatus(){

        return $this->courseStatusString;
    }

    private function setIsShowExams($courseObj){
        global $nursingStream;
        global $medicineStream;
        global $beautyStream;
        $this->showExams = true;

        $courseTypeInfornmation = $courseObj->getCourseTypeInformation();
        $entryCourse = $courseTypeInfornmation['entry_course'];
        $credential = $entryCourse->getCredential();

        if(!empty($credential)){
            if($credential->getId() == CERTIFICATE_CREDENTIAL){
                $this->showExams = false;
            }
        }
        if($this->showExams){
            $allowedStreams = array($nursingStream, $medicineStream, $beautyStream);
            $hierarchy = $entryCourse->getHierarchies();
            foreach ($hierarchy as $key => $value) {
                if(in_array($value['stream_id'], $allowedStreams)){
                    $this->showExams = false;
                    break;
                }
            }
        }
          
        
    }



    public function getIsShowExams(){
        return $this->showExams;
    }

   function getLocationSuffixForUrl(){
        return $this->locationSuffixForUrl;
    }

}
