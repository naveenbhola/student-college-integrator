<?php
/**
 * File for Generation of redirection URL after successful registration
 */ 
namespace registration\libraries;

/**
 * Generate redirection URL after successful registration
 * Input: Registration post data array in constructor
 * URL is computed basis info provided by user during registration process
 * e.g. desired course, category, preferred and residence locations etc.
 * Computation process is different for national, study abroad and test prep
 */
class PostRegistrationRedirector
{
    /**
     * Registration data
     * @var array
     */
    private $data = array();
    
    /**
     * @var object CategoryPageRequest (type: library, module: Listing/categoryList)
     */
    private $categoryPageRequest;
    
    /**
     * @var object LocationRepository (type: repository, module: Common/location)
     */
    private $locationRepository;
    
    /**
     * @var object customizemmp_model (type: model, module: User/customizemmp)
     */
    private $mmpModel;
    
    /**
     * @var object registrationmodel (type: model, module: User/registration)
     */
    private $registrationModel;
    
    /**
     * @var object Url_manager (type: library, module: Common/common)
     */
    private $urlManager;
    
    /**
     * @var object Category_list_client (type: library, module: Listing/categoryList)
     */
    private $categoryListClient;
    
    /**
     * Constructor
     *
     * @param  array $data Registration Data
     * @return void
     */
    function __construct($data = array())
    {
        $this->data = $data;
        
        /**
         * Load required libraries, models and repositories
         */ 
        $CI = & get_instance();
        $CI->load->library('categoryList/categoryPageRequest');
        $this->categoryPageRequest = new \CategoryPageRequest;
        
        $CI->load->library('categoryList/abroadCategoryPageRequest');
        $this->abroadCategoryPageRequest = new \AbroadCategoryPageRequest;
        
        $CI->load->builder('LocationBuilder','location');
        $locationBuilder = new \LocationBuilder;
        $this->locationRepository = $locationBuilder->getLocationRepository();
        $this->locationRepository->disableCaching();
        
        $CI->load->model('customizedmmp/customizemmp_model');
        $this->mmpModel = new \customizemmp_model;
        
        $CI->load->model('registration/registrationmodel');
        $this->registrationModel = new \registrationmodel;
        
        $CI->load->library('url_manager');
        $this->urlManager = new \Url_manager;
        
        $CI->load->library('categoryList/Category_list_client');
        $this->categoryListClient = new \Category_list_client;

        $CI->load->config('categoryPageConfig.php');
    }
    
    /**
     * Get redirection URL
     *
     * @return string URL
     */
    public function getRedirectionURL(){
        $redirectionURL = '';
        /**
         * Check if there is a preset redirect URL for the MMP
         */
        if($this->data['context'] == "rmcPage"){
            return SHIKSHA_STUDYABROAD_HOME.'';
        }
        if(in_array($this->data['tracking_keyid'],array(1861,1919,1917,1979))){
            $pageReferrer = $this->data['referrer'];
            if($this->data['tracking_keyid'] != 1917)
            {
                $pageReferrer = explode('#', $pageReferrer);
                $pageReferrer = $pageReferrer[0];
                $URLQuery = parse_url($pageReferrer[0],PHP_URL_QUERY);
                if($URLQuery) {
                    $pageReferrer .= '&scrollTo=applicationlnk';
                }
                else {
                    $pageReferrer .= '?scrollTo=applicationlnk';
                }
            }
            return $pageReferrer;
        }

        if($this->data['mmpFormId']) {
            $presetRedirectURL = $this->_getPresetRedirectURLForMMP();
            if($presetRedirectURL) {
                $redirectionURL = $presetRedirectURL;
            }
        }
        
        if(!$redirectionURL) {
            if(($this->data['isStudyAbroad']) == 'yes') {
                STUDY_ABROAD_NEW_REGISTRATION;
                if(STUDY_ABROAD_NEW_REGISTRATION) {
                    $redirectionURL = $this->_getRedirectURLForNewStudyAbroadRegistration();    
                }
                else {
                    $redirectionURL = $this->_getRedirectURLForStudyAbroad();
                }
            }
            else if(($this->data['isTestPrep']) == 'yes') { 
                $redirectionURL = $this->_getRedirectURLForTestPrep();
            }
            else {
                $redirectionURL = $this->_getRedirectURLForNational();
            }
        }
        
        if(!$presetRedirectURL) {
            $URLQuery = parse_url($redirectionURL,PHP_URL_QUERY);
            if($URLQuery) {
                $redirectionURL .= '&source=Registration';
            }
            else {
                $redirectionURL .= '?source=Registration';
            }
            
            if($this->data['mmpFormId']) {
                $redirectionURL .= '&mmpsrc='.$this->data['mmpFormId'];
            }
            
            if($this->data['newUser']) {
                $redirectionURL .= '&newUser=1';
            }
        }
        
        return $redirectionURL;
    }
    
    /**
     * Function to get the Present Redirection URL
     *
     * @return string URL
     */
    private function _getPresetRedirectURLForMMP()
    {
        $queryString = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY);
	parse_str($queryString, $URLParams);
        if($URLParams['disableOTPVerification'] == 'e2b22fef40f50e9588431cf86f32406a' ){
            $skippedUrl =SHIKSHA_HOME."/marketing/Marketing/form/pageID/918";
            return $skippedUrl;
        }

        $mmpDetails = $this->mmpModel->getMMPDetails($this->data['mmpFormId']);
        return $mmpDetails['destination_url'];
    }
    
    /**
     * Get redirection URL for national type registration form
     * URL computed using desired course, residence city,
     * preferred citied and states selected
     *
     * @return string URL
     */
    private function _getRedirectURLForNational()
    {
        $preAppliedFilters = array();
        
        $coursePageCourseDetails = $this->registrationModel->getCoursePageCourseDetails($this->data['desiredCourse']);
        
        $isFullTimeMBA = $coursePageCourseDetails['subCatId'] == 23 ? true : false;
        
        if($this->data['mmpFormId']) {
            $urlData = $this->getRedirectionLocation($isFullTimeMBA);
        }
        
        if(is_array($urlData['cityId'])) {
            $preAppliedFilters['city'] = $urlData['cityId'];
            $urlData = array();
        }
        
        /**
         * If desired course is course page specific, redirect to sub-category page
         * instead of course page, since no lisitngs can be posted on this courses
         */ 
        if(is_array($coursePageCourseDetails) && $coursePageCourseDetails['id'] && $coursePageCourseDetails['allow_global_registration'] == 'no') {
            $urlData["subCategoryId"] = $coursePageCourseDetails['subCatId'];
        }
        else {
            $urlData["LDBCourseId"] = $this->data['desiredCourse'];
        }
        
        if($isFullTimeMBA) {
            $this->categoryPageRequest->setNewURLFlag(true);
            $this->categoryPageRequest->setData($urlData);
            $URL = $this->categoryPageRequest->getURL();
            
            if($this->data['mmpFormId']) {
                $preAppliedFilters['courseexams'] = $this->_getExamFilterValues($URL);
                $this->_setCookieForRedirection($URL, $preAppliedFilters);


                // Code added by Ankit for LDB-2101
                $examURL = $this->getExamRedirectionURL($preAppliedFilters['courseexams']);       
                
                if($examURL != "") {                    
                    $examURL = substr($examURL, 0, -1);                    
                    $URL = $URL."?".CP_REQUEST_VAR_NAME_EXAM."=".$examURL;
                }
                // Code ends

            }

            
            return $URL;
        }
        else {
            $this->categoryPageRequest->setData($urlData);
            return $this->categoryPageRequest->getURL();
        }
    }
    
        
    /**
     * Get redirection URL for study abroad type registration form
     * URL computed using field of interest (category),
     * preferred regions and countries selected
     *
     * @return string URL
     */
    private function _getRedirectURLForStudyAbroad()
    {
        $urlData = $this->getRedirectionLocation();
        $urlData['categoryId'] = $this->data['fieldOfInterest'];
        $this->categoryPageRequest->setData($urlData);
        return $this->categoryPageRequest->getURL();
    }
    
    /**
     * Get redirection URL for test prep type registration form
     * URL computed using desired course (blogId),
     * preferred cities selected
     *
     * @return string URL 
     */
    private function _getRedirectURLForTestPrep()
    {
        global $blogIdToNewSubCategoryMapping;
        
        $testPrepCourseDetails = $this->mmpModel->getTestPrepCourseDetails($this->data['desiredCourse']);
        $mainBlogId = $testPrepCourseDetails['parentId'];
        $subCategoryId = $blogIdToNewSubCategoryMapping[$mainBlogId];
        
        if($this->data['mmpFormId']) {    
            $requestData = $this->getRedirectionLocation();
        }
        $requestData['categoryId'] = 14;
        $requestData['subCategoryId'] = $subCategoryId;
        
        $this->categoryPageRequest->setData($requestData);
	return $this->categoryPageRequest->getURL();
    }
    
    /**
     * Get location used to construct redirection URL
     *
     * @param boolean $isFullTimeMBA
     * @return array containing wither cityId ot stateId
     */
    public function getRedirectionLocation($isFullTimeMBA = false)
    {
        if(($this->data['isStudyAbroad']) == 'yes') {
            $regions = $this->data['regions'];
            if(count($regions) > 0) {
                return array('regionId' => $regions[array_rand($regions)],'countryId'=>1);
            }
            else {
                $countries = $this->data['destinationCountry'];
                return array('countryId' => $countries[array_rand($countries)]);
            }
        }
        else if(($this->data['isTestPrep']) == 'yes') {
            $cityId = $this->data['preferredStudyLocalityCity'][0];
            $cityId = $cityId > 1 ? $cityId : 1;
            return array('cityId' => $cityId);
        }
        else {
            $residenceCityId = $this->data['residenceCity'];
            if($residenceCityId) {
                $residenceCity = $this->locationRepository->findCity($residenceCityId);
                $residenceStateId = $residenceCity->getStateId();
                $residenceVirtualCityId = $residenceCity->getVirtualCityId();
            }
            else {
                $residenceCityId = 0;
                $residenceStateId = 0;
                $residenceVirtualCityId = 0;
            }
            
            if(array_key_exists('preferredStudyLocation',$this->data)) {
                $preferredCityIds = $this->data['preferredStudyLocation']['cities'];
                $preferredStateIds = $this->data['preferredStudyLocation']['states'];
            }
            else {
                $preferredCityIds = array($this->data['preferredStudyLocalityCity'][0]);
                $preferredStateIds = array();
            }
            
            /*
             * Redirection location for Full Time MBA
             */
            if($isFullTimeMBA) {
                if(count($preferredCityIds) == 1) {
                    return array('cityId' => $preferredCityIds[0]);
                }
                
                if(count($preferredCityIds) > 1) {
                    return array('cityId' => $preferredCityIds);
                }
                
                if(count($preferredStateIds) == 1) {
                    return array('stateId' => $preferredStateIds[0]);
                }
            }
            
            /*
             * Match 1: virtual city of residence city is present in preferred cities
             */ 
            if(in_array($residenceVirtualCityId,$preferredCityIds)) {
                return array('cityId' => $residenceVirtualCityId);
            }
            
            /*
             * Match 2: virtual city of any preferred city is same as residence city
             */ 
            $preferredCities = array();
            foreach($preferredCityIds as $preferredCityId) {
                $preferredCities[] = $this->locationRepository->findCity($preferredCityId);
            }
            
            $residenceVirtualCityMatches = array();
            foreach($preferredCities as $preferredCity) {
                if($preferredCity->getVirtualCityId() == $residenceCityId) {
                    $residenceVirtualCityMatches[] = $preferredCity->getId();    
                }
            }
            
            if(count($residenceVirtualCityMatches) > 0) {
                return array('cityId' => $residenceVirtualCityMatches[array_rand($residenceVirtualCityMatches)]);
            }
            
            /*
             * Match 3: residence city is present in preferred cities
             */ 
            if(in_array($residenceCityId,$preferredCityIds)) {
                return array('cityId' => $residenceCityId);
            }
            
            /*
             * Match 4: state of residence city is present in preferred states
             */ 
            if(in_array($residenceStateId,$preferredStateIds)) {
                return array('stateId' => $residenceStateId);
            }
            
            /*
             * Match 5: top-tiered preferred city
             */
            if(count($preferredCities) == 1) {
                return array('cityId' => $preferredCities[0]->getId());
            }
            
            if(count($preferredCities) > 1) {
                usort($preferredCities,function($city1,$city2) {
                    return (intval($city1->getTier()) - intval($city2->getTier));    
                });
                return array('cityId' => $preferredCities[0]->getId());
            }
            /*
             * Match 6: random preferred state
             */
            return array('stateId' => $preferredStateIds[array_rand($preferredStateIds)]);
        }
    }
    
    /**
     * Function to get the Exam Filter values.
     *
     * @return array
     */
    private function _getExamFilterValues() {
        global $GLOBALS;
        $preAppliedFilters = array();
        
        $otherExams = $GLOBALS['MBA_EXAMS_SPECIAL_SCORES'];
        $noOptionsExam  = $GLOBALS['MBA_NO_OPTION_EXAMS'];

        $examScoreBuckets = array();
        $examScoreBuckets['others'] = array_values($GLOBALS['MBA_SCORE_RANGE']);
        $examScoreBuckets['MAT'] = array_values($GLOBALS['MBA_PERCENTILE_RANGE_MAT']);
        $examScoreBuckets['XAT'] = array_values($GLOBALS['MBA_PERCENTILE_RANGE_XAT']);
        $examScoreBuckets['NMAT'] = array_values($GLOBALS['MBA_PERCENTILE_RANGE_NMAT']);
        $examScoreBuckets['CMAT'] = array_values($GLOBALS['MBA_SCORE_RANGE_CMAT']);
        $examScoreBuckets['GMAT'] = array_values($GLOBALS['MBA_SCORE_RANGE_GMAT']);
        
        foreach($this->data['exams'] as $exam) {
            if($exam == 'MICAT') {
                array_push($preAppliedFilters , $exam.'_0');
                continue;
            }
            
            if($exam == 'NOEXAM') {
                array_push($preAppliedFilters , 'No Exam Required_0');
                continue;
            }
            
            if(in_array($exam, $noOptionsExam)){
                array_push($preAppliedFilters , $exam.'_0');
                continue;   
            }

            $examScore = $this->data[$exam.'_score'];
            if(!in_array($exam, $otherExams)) {
                $examScoreBucket = $examScoreBuckets['others'];
                if($examScore <= $examScoreBucket[0]) {
                    array_push($preAppliedFilters , $exam.'_'.$examScoreBucket[0]);
                    continue;
                }
                for($index = 1; $index < count($examScoreBucket); $index++) {
                    if($examScore >= $examScoreBucket[$index] - 5 && $examScore <= $examScoreBucket[$index] + 5) {
                        array_push($preAppliedFilters, $exam.'_'.$examScoreBucket[$index]);
                        break;
                    }
                }
            }
            else {
                $examScoreBucket = $examScoreBuckets[$exam];
                if($exam == 'MAT' || $exam == 'XAT' || $exam == 'NMAT') {
                    if($examScore <= $examScoreBucket[0]) {
                        array_push($preAppliedFilters, $exam.'_'.$examScoreBucket[0]);
                        continue;
                    }
                    if($examScore > $examScoreBucket[count($examScoreBucket) - 1]) {
                        array_push($preAppliedFilters, $exam.'_'.$examScoreBucket[count($examScoreBucket) - 1]);
                        continue;
                    }
                    for($index = 1; $index < count($examScoreBucket) - 1; $index++) {
                        if($examScore >= $examScoreBucket[$index] - 5 && $examScore <= $examScoreBucket[$index] + 5) {
                            array_push($preAppliedFilters, $exam.'_'.$examScoreBucket[$index]);
                            break;
                        }
                    }
                }
                else if($exam == 'CMAT' || $exam == 'GMAT') {
                    if(($exam == 'GMAT') && ($examScore >= $examScoreBucket[count($examScoreBucket) - 1])) {
                        array_push($preAppliedFilters, $exam.'_'.$examScoreBucket[count($examScoreBucket) - 1]);
                        continue;
                    }
                    foreach($examScoreBucket as $score) {
                        $maxScore = explode('-', $score);
                        $maxScore = $maxScore[1];
                        if($examScore <= $maxScore) {
                            array_push($preAppliedFilters, $exam.'_'.$score);
                            break;
                        }
                    }
                }
            }
        }
        
        return $preAppliedFilters;
    }
    
    /**
     * Function to set the cookie for Redirection
     *
     * @param string $URL
     * @param array $preAppliedFilters
     *
     */
    private function _setCookieForRedirection($URL, $preAppliedFilters) {
        $searchString = '.com';
        $position = strpos($URL, $searchString) + strlen($searchString) + 1;
        $parseString = substr($URL, $position, strlen($URL));
        $parseString = explode('?', $parseString);
        $this->categoryPageRequest->buildFromURLQueryString($parseString[0]);
        
        $pageKey = $this->categoryPageRequest->getPageKey();
        
        if(isset($_COOKIE['userCityPreference'])) {
            setcookie('userCityPreference', '', time() - 3600, '/', COOKIEDOMAIN);
        }
        
        if(count($preAppliedFilters['city']) > 1) {
            $cities = array();
            foreach($preAppliedFilters['city'] as $cityId) {
                $cities[] = 'c_'.$cityId;
            }
            
            $cookieVal = base64_encode(json_encode($cities));
            setcookie('userMultiLocPref-MainCat-3', $cookieVal, time() + 1800, '/', COOKIEDOMAIN);
            $_COOKIE['userMultiLocPref-MainCat-3'] = $cookieVal;
        }
        
        $this->categoryListClient->setCookieCategoryPage('filters-'.$pageKey, '');
        $preAppliedFilters = base64_encode(json_encode($preAppliedFilters));
        $this->categoryListClient->setCookieCategoryPage('filters-'.$this->categoryPageRequest->getPageKey(), $preAppliedFilters);
    }
    
      /**
     * Function to get the Redirection URL for new study abroad registration
     */
    private function _getRedirectURLForNewStudyAbroadRegistration() {
        $urlData = array();
        
        if($this->data['destinationCountry']) {
            $urlData['countryId'] = $this->data['destinationCountry'];
        }
        else {
            $urlData['countryId'] = array(1);
        }
        
        if(intval($this->data['desiredCourse'])) {
            $urlData['LDBCourseId'] = $this->data['desiredCourse'];
        }
        else {
            $urlData['LDBCourseId'] = 1;
            
            if(intval($this->data['fieldOfInterest'])) {
                $urlData['categoryId'] = $this->data['fieldOfInterest'];
            }
            else {
                $urlData['categoryId'] = 1;
            }
        }
        
        if(intval($this->data['abroadSpecialization'])) {
            $urlData['subCategoryId'] = $this->data['abroadSpecialization'];
        }
        else {
            $urlData['subCategoryId'] = 1;
        }
        
        if(!intval($this->data['desiredCourse']) &&$this->data['desiredGraduationLevel']) {    
            $urlData['courseLevel'] = $this->data['desiredGraduationLevel'];
        }
        else {
            $urlData['courseLevel'] = "";
        }
	
        $this->abroadCategoryPageRequest->setData($urlData);
        return $this->abroadCategoryPageRequest->getURL();
    }

    // Function to generate the URL for category page with exam search query(?exam=cat:54)
    private function getExamRedirectionURL($examArray){
        $examURL = "";
        foreach ($examArray as $examInfo) {
            $temp = explode("_", $examInfo);
            if(!empty($temp)) {
                $examURL .= $temp[0];
                if(count($temp) > 1) {
                    $examURL .= CP_OTHER_EXAM_NAME_AND_SCORE_SEPERATOR.$temp[1];
                }
                $examURL .= CP_OTHER_EXAM_AND_EXAM_SEPERATOR;
            }
        }
        return $examURL;
    }

}
