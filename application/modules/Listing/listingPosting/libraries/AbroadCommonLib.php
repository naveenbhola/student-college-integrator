<?php
class AbroadCommonLib
{
    private $CI;
    private $abroadCMSModelObj;
    private $categoryRepository;
    
    function __construct()
    {
        $this->CI =& get_instance();
        $this->_setDependecies();
    }

    function _setDependecies()
    {
        $this->CI->config->load('studyAbroadCommonConfig');
        $this->CI->load->model('listingPosting/abroadcmsmodel');
        $this->abroadCMSModelObj  = new abroadcmsmodel();
        
        $this->CI->load->builder('CategoryBuilder','categoryList');
        $categoryBuilder = new CategoryBuilder;
        $this->categoryRepository = $categoryBuilder->getCategoryRepository();
    }
    
    function getUniversityDetails($universityId, $status = ENT_SA_PRE_LIVE_STATUS)
    {
        return $this->abroadCMSModelObj->getUniversityDetails($universityId, $status);
    }
    
    function getAllAbroadStates()
    {
        return $this->abroadCMSModelObj->getAllAbroadStates();
    }
    
    function doesUniversityExist($univName,$univCountryId,$univId)
    {
        $univExists = $this->abroadCMSModelObj->findIfUniversityExists($univName,$univCountryId,$univId);
        return $univExists;
    }
    
    function isCityExists($cityName, $countryId, $stateId = 0)
    {
        $similarCities = $this->abroadCMSModelObj->getSimilarCities($cityName, $countryId);
        $cityAlreadyExistsFlag = 0;
        foreach($similarCities as $key=>$value)
        {
            if( ((empty($stateId) && $value['state_id'] == -1) || $stateId == $value['state_id']))
            {
                $cityAlreadyExistsFlag = 1;    
            }
        }
        
        return $cityAlreadyExistsFlag;
    }
    
    function addCity($countryId, $stateId, $cityName, $userId, $tier, $additionalCityFields)
    {
        $additionalCityFields = $this->_formatAdditionalCityFields($additionalCityFields);
        $this->abroadCMSModelObj->addCity($countryId, $stateId, $cityName, $userId, $tier, $additionalCityFields);
    }

    function updateCity($newInfo, $userId, $updateCountryCityTable)
    {
        $newInfo['additionalCityFields'] = $this->_formatAdditionalCityFields($newInfo['additionalCityFields']);
        $this->abroadCMSModelObj->updateCity($newInfo, $userId, $updateCountryCityTable);
    }

    private function _formatAdditionalCityFields(&$cityFields, $cityId=0){
        $formattedFields = array();
        $attrEnumArr = array('latitude','latitudeDirection','longitude','longitudeDirection','citySize','wikiPageUrl','cityPopulation','offCampusAccommodationUrl');
        $i = 0;
        $addetAt = date('Y-m-d H:i:s');
        foreach ($attrEnumArr as $attr) {
            if(is_array($cityFields[$attr])){
                foreach ($cityFields[$attr] as $val) {
                    if(!empty($val)){
                        $formattedFields['primaryAttr'][$i]['cityId']  = $cityId;
                        $formattedFields['primaryAttr'][$i]['attributeType']  = $attr;
                        $formattedFields['primaryAttr'][$i]['attributeValue'] = $val;
                        $formattedFields['primaryAttr'][$i]['addedAt'] = $addetAt;
                        $formattedFields['primaryAttr'][$i]['status'] = 'live';
                        $i++;
                    }
                }
            }else{
                if(!empty($cityFields[$attr])){
                    $formattedFields['primaryAttr'][$i]['cityId']  = $cityId;
                    $formattedFields['primaryAttr'][$i]['attributeType']  = $attr;
                    $formattedFields['primaryAttr'][$i]['attributeValue'] = $cityFields[$attr];
                    $formattedFields['primaryAttr'][$i]['addedAt'] = $addetAt;
                    $formattedFields['primaryAttr'][$i]['status'] = 'live';
                    $i++;
                }
            }
        }
        foreach ($cityFields['videoUrl'] as $key => $vidUrl) {
            if(!empty($vidUrl)){
                $formattedFields['videos'][$key]['cityId']  = $cityId;
                $formattedFields['videos'][$key]['mediaId'] = $cityFields['mediaId'][$key];
                $formattedFields['videos'][$key]['videoTitle']  = $cityFields['videoTitle'][$key];
                $formattedFields['videos'][$key]['videoUrl'] = $vidUrl;
                $formattedFields['videos'][$key]['thumbUrl'] = $cityFields['thumbUrl'][$key];
                $formattedFields['videos'][$key]['addedAt'] = $addetAt;
                $formattedFields['videos'][$key]['status'] = 'live';
            }
        }
        $cityWeatherDetails = $this->_formatCityWeatherDetails($cityFields);
        if($cityWeatherDetails['isTemperatureEmpty']===false || !empty($cityFields['offCampusAccommodationDesc'])){
            $formattedFields['otherAttr']['cityId'] = $cityId;
            $formattedFields['otherAttr']['offCampusAccommodationDesc'] = $cityFields['offCampusAccommodationDesc'];
            $formattedFields['otherAttr']['cityWeatherDetails'] = json_encode($cityWeatherDetails['temperatuteData']);
            $formattedFields['otherAttr']['addedAt'] = $addetAt;
            $formattedFields['otherAttr']['status'] = 'live';
        }
        return $formattedFields;
    }

    private function _formatCityWeatherDetails(&$cityFields){
        $isTempEmpty = true;
        $formattedTempValues = array();
        $months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
        foreach ($cityFields['minTemp'] as $key => $value) {
            if(empty($value) && !is_numeric($value)){
                $formattedTempValues[$months[$key]]['min'] = NULL;
            }else{
                $formattedTempValues[$months[$key]]['min'] = $value;
                $isTempEmpty = false;
            }

            if(empty($cityFields['maxTemp'][$key]) && !is_numeric($cityFields['maxTemp'][$key])){
                $formattedTempValues[$months[$key]]['max'] = NULL;
            }else{
                $formattedTempValues[$months[$key]]['max'] = $cityFields['maxTemp'][$key];
                $isTempEmpty = false;
            }
        }
        return array('isTemperatureEmpty'=>$isTempEmpty, 'temperatuteData'=>$formattedTempValues);
    }

    public function getAbroadCategories(){
        $mainCategories = $this->categoryRepository->getSubCategories(1,'newAbroad');
        $parentCategories = array();
        foreach($mainCategories as $category){
            $catDetails = array();
            $catDetails['categoryName'] = $category->getName();
            $catDetails['categoryID'] 	= $category->getId();
            $parentCategories[] = $catDetails;
        }
        foreach($parentCategories as $categoryInfo){
            $categoryId = $categoryInfo['categoryID'];
            if($categoryId == 1 || $categoryId == 14){ //Skip 'All' and 'Test Prepearation' category
                continue;
            }
            $parentCategoryList[$categoryId]['id']   =  $categoryInfo['categoryID'];
            $parentCategoryList[$categoryId]['name'] =  $categoryInfo['categoryName'];
            $subCategoryInfo = array();
            $subCategoriesOfthisCategory  = $this->categoryRepository->getSubCategories($categoryId, 'newAbroad');
            foreach($subCategoriesOfthisCategory as $subCategory){
                $subCategoryInfo[$subCategory->getId()] = array();
                $subCategoryInfo[$subCategory->getId()]['id'] = $subCategory->getId();
                $subCategoryInfo[$subCategory->getId()]['name'] = $subCategory->getName();
                $parentCategoryList[$categoryId]['subcategory'] = $subCategoryInfo;
            }
        }
        return $parentCategoryList;
    }

    public function getDepartmentBasicInfo($deptId) {
        return $this->abroadCMSModelObj->getDepartmentBasicInfo($deptId);
    }
    
    public function getCourseInfo($courseId) {
        return $this->abroadCMSModelObj->getCourseInfo($courseId);
    }
    
    public function getRecruitingCompanies() {
        $result = $this->abroadCMSModelObj->getRecruitingCompanies();
        return $result;
    }
        
    public function getAbroadExamsMasterList($keyName='',$refreshFlag=0, $hideNewExam=false) {
        $this->CI->load->library('listing/cache/AbroadListingCache');
        $abroadListingCacheLib  = new AbroadListingCache();
        if($refreshFlag == 1){
            $examsList = array();
        }else{
            $examsList = $abroadListingCacheLib->getAbroadExams();
        }
        if(empty($examsList) || $refreshFlag == 1) {
                $examsList 			= $this->abroadCMSModelObj->getAbroadExamsMasterList();
                $abroadListingCacheLib->storeAbroadExams($examsList);
        }
        $requiredExamMasterList = array();
        if($keyName != ""){
            foreach ($examsList as $masterExams) {
                $requiredExamMasterList[$masterExams[$keyName]] = $masterExams;
            }
        }else{
            $requiredExamMasterList = $examsList;
        }

        if($hideNewExam == true) {
            $this->CI->config->load('studyAbroadCommonConfig');
            $hideExamIds = $this->CI->config->item('HIDE_EXAM_IDS');
            foreach ($requiredExamMasterList as $key => $exam) {
                if(in_array($exam['examId'], $hideExamIds)) {
                    unset($requiredExamMasterList[$key]);
                }
            }
        }

        return $requiredExamMasterList;
    }

                function  getAllCityList($searchCityString,$LimitOffset,$LimitRowCount)
    {  
    	return $this->abroadCMSModelObj->getAllCityList($searchCityString,$LimitOffset,$LimitRowCount);
    }

    function getCurrencyList()
    {
        $result = $this->abroadCMSModelObj->getCurrencyData();
        return $result;
    }

    function getCurrencyNameById($currencyIds = array())
    {
        $currencyNameMapping = array();
        $result = $this->abroadCMSModelObj->getCurrencyNameById($currencyIds);
        foreach ($result as $key => $value) {
            $currencyNameMapping[$value['id']] = $value['currency_name'];
        }
        return $currencyNameMapping;
    }
    
    function getAbroadMainLDBCourses()
    {
        $abroadListingCacheLib 	= $this->CI->load->library('listing/cache/AbroadListingCache');
        $mainLDBCourses         = $abroadListingCacheLib->getAbroadMainLDBCourses();
        if(empty($mainLDBCourses)) {
            $mainLDBCourses = $this->abroadCMSModelObj->getAbroadMainLDBCourses();
            if(!empty($mainLDBCourses)){
                $abroadListingCacheLib->storeAbroadMainLDBCourses($mainLDBCourses);
            }
        }
        return $mainLDBCourses;
    }
    
    function _getCitiesByCountry($countryId)
    {
        $result = $this->abroadCMSModelObj->getCities($countryId);
        return $result;
    }
    function _getStatesByCountry($countryId)
    {
        $result = $this->abroadCMSModelObj->getStatesByCountry($countryId);
        return $result;
    }
    function _getCitiesByState($stateId)
    {
        $result = $this->abroadCMSModelObj->getCitiesByState($stateId);
        return $result;
    }
    
    function getDepartmentHighAndLowFields($params) {
        $lowFieldValues = array();
        $highFieldValues = array();
        if(!empty($params)) {
            $lowFieldValues['contactPersonName']        = $params['contactPersonName'];
            $lowFieldValues['contactPhoneNo']           = $params['contactPhoneNo'];
            $lowFieldValues['facultyPageUrl']           = $params['facultyPageUrl'];
            $lowFieldValues['alumniPageUrl']            = $params['alumniPageUrl'];
            $lowFieldValues['fbPageUrl']                = $params['fbPageUrl'];
            
            $highFieldValues['accreditationDetails']     = $params['accreditationDetails'];
            $highFieldValues['contactEmail']             = $params['contactEmail'];
            $highFieldValues['schoolAcronym']            = $params['schoolAcronym'];
        }
        return array(
                    'high_field_values' => $highFieldValues,
                    'low_field_values'  => $lowFieldValues
                    );
    }
    
    function getUniversityHighAndLowFields($params) {
        $lowFieldValues     = array();
        $highFieldValues    = array();
        $arrayFieldValues   = array();
        if(!empty($params)) {
            $lowFieldValues['univEstablishedYear']          = $params['univEstablishedYear'];
            $lowFieldValues['univAffiliation']              = $params['univAffiliation'];
            $lowFieldValues['univContactPhone']             = $params['univContactPhone'];
            $lowFieldValues['univContactAddress']           = $params['univContactAddress'];
            if(!empty($params['pictureArr']) || (!empty($params['univPicturesMediaId']) && !empty($params['univPicturesMediaId'][0]))){
                $lowFieldValues['pictureArr'] = "nonemptyvalue";
            } else {
                $lowFieldValues['pictureArr'] = "";
            }
            if(!empty($params['videoArr']) && !array_key_exists('error',$params['videoArr'])){
                $lowFieldValues['videoArr']                     = "nonemptyvalue";
            } else {
                $lowFieldValues['videoArr']                     = "";
            }
            $lowFieldValues['univFBPage']                   = $params['univFBPage'];
            $lowFieldValues['univAdmissionContact']         = $params['univAdmissionContact'];
            if(!empty($params['univDeptWebsite']) && !empty($params['univDeptWebsite'][0])) {
                $lowFieldValues['univDeptWebsite']          =  "nonemptyvalue";
            } else {
                $lowFieldValues['univDeptWebsite']          =  "";
            }
            $lowFieldValues['univAccomodationDetail']       = $params['univAccomodationDetail'];
            $lowFieldValues['univAccomodationLink']         = $params['univAccomodationLink'];
            $lowFieldValues['univLivingExpenseDescription'] = $params['univLivingExpenseDescription'];
            $lowFieldValues['univLivingExpenseLink']        = $params['univLivingExpenseLink'];
            if(!empty($params['univCampusName']) && !empty($params['univCampusName'][0])) {
                $lowFieldValues['univCampusName']                = "nonemptyvalue";
            } else {
                $lowFieldValues['univCampusName']                =  "";
            }
            if(!empty($params['univCampusWebsite']) && !empty($params['univCampusWebsite'][0])) {
                $lowFieldValues['univCampusWebsite']                = "nonemptyvalue";
            } else {
                $lowFieldValues['univCampusWebsite']                =  "";
            }
            if(!empty($params['univCampusAddress']) && !empty($params['univCampusAddress'][0])) {
                $lowFieldValues['univCampusAddress']                = "nonemptyvalue";
            } else {
                $lowFieldValues['univCampusAddress']                =  "";
            }
            $lowFieldValues['univIndianConsultant']         = $params['univIndianConsultant'];
            
            $highFieldValues['univAcronym']                     = $params['univAcronym'];
            $highFieldValues['univAccreditation']               = $params['univAccreditation'];
            $highFieldValues['univContactEmail']                = $params['univContactEmail'];
            $highFieldValues['univContactWebsite']              = $params['univContactWebsite'];
            if(!empty($params['univDeptName']) && !empty($params['univDeptName'][0])) {
                $highFieldValues['univDeptName']                = "nonemptyvalue";
            } else {
                $highFieldValues['univDeptName']                =  "";
            }
            $highFieldValues['univLivingExpense']               = $params['univLivingExpense'];
            $highFieldValues['institute_request_brochure_link'] = $params['institute_request_brochure_link'];
            $highFieldValues['univInternationalStudentsLink']   = $params['univInternationalStudentsLink'];
        }
        return array(
                    'high_field_values' => $highFieldValues,
                    'low_field_values'  => $lowFieldValues,
                    );
    }

    function getCourseHighAndLowFields($params) {
        $lowFieldValues = array();
        $highFieldValues = array();
        if(!empty($params)) {
            
            $lowFieldValues['affiliationDetails']               = $params['affiliationDetails'];
            // handle this case carefully
            //error_log("::::EXAM::examsRequiredCustomDataArray::".print_r($params['examsRequiredCustomDataArray'],true));
            //error_log("::::EXAM::examsRequiredDataArray::".print_r($params['examsRequiredDataArray'],true));
            
            if(!empty($params['examsRequiredDataArray'])){
                //$lowFieldValues['examsRequiredCustomDataArray']  = "nonemptyvalue";
                foreach($params['examsRequiredDataArray'] as $key=>$value)
                {
                    $value['examId'] = trim($value['examId']);
                    $lowFieldValues['examsRequiredDataArray_'.$key."_id"]     = empty($value['examId']) ? "" : $value['examId'];
                    $value['examComments'] = trim($value['examComments']);
                    $lowFieldValues['examsRequiredDataArray_'.$key."_comments"] = empty($value['examComments']) ? "" : $value['examComments'];
                }
            } else {
                $lowFieldValues['examsRequiredCustomDataArray']  = "";
            }
            
            if(!empty($params['examsRequiredCustomDataArray'])){
                //$lowFieldValues['examsRequiredCustomDataArray']  = "nonemptyvalue";
                foreach($params['examsRequiredCustomDataArray'] as $key=>$value)
                {
                    $value['examName'] = trim($value['examName']);
                    $lowFieldValues['examsRequiredCustomDataArray_'.$key."_name"]     = empty($value['examName']) ? "" : $value['examName'];
                    $value['examComments'] = trim($value['examComments']);
                    $lowFieldValues['examsRequiredCustomDataArray_'.$key."_comments"] = empty($value['examComments']) ? "" : $value['examComments'];
                }
            }
            
            $lowFieldValues['averageWorkExp']                   = $params['averageWorkExp'];
            $lowFieldValues['averageBachelorsGPA']              = $params['averageBachelorsGPA'];
            $lowFieldValues['averageClass12Percentage']         = $params['averageClass12Percentage'];
            $lowFieldValues['averageGMATScore']                 = $params['averageGMATScore'];
            $lowFieldValues['averageAge']                       = $params['averageAge'];
            $lowFieldValues['internationalStudentsPercentage']  = $params['internationalStudentsPercentage'];
            $lowFieldValues['internationalStudentsPercentage']  = $params['internationalStudentsPercentage'];
            $lowFieldValues['percentageEmployed']               = $params['percentageEmployed'];
            $lowFieldValues['avgSalary']                        = $params['avgSalary'];
            $lowFieldValues['popularSectors']                   = $params['popularSectors'];
            $lowFieldValues['internshipsLink']                  = $params['internshipsLink'];
            $lowFieldValues['facultyInfoLink']                  = $params['facultyInfoLink'];
            $lowFieldValues['alumniInfoLink']                   = $params['alumniInfoLink'];
            $lowFieldValues['faqLink']                          = $params['faqLink'];
            $lowFieldValues['examsRequiredFreeText']            = $params['examsRequiredFreeText'];
            $lowFieldValues['courseDurationLink']               = $params['courseDurationLink'];
            $lowFieldValues['feeRoomBoard']                     = $params['roomBoard'];
            $lowFieldValues['feeInsurance']                     = $params['insurance'];
            $lowFieldValues['feeTransportation']                = $params['transportation'];
            $lowFieldValues['scholarshipDescription']           = $params['scholarship']['description'];
            $lowFieldValues['scholarshipMainLink']              = $params['scholarship']['link'];
            $lowFieldValues['scholarshipAmount']                = $params['scholarship']['amount'];
            $lowFieldValues['scholarshipEligibility']           = $params['scholarship']['eligibility'];
            $lowFieldValues['scholarshipDeadline']              = $params['scholarship']['deadline'];
        
            $highFieldValues['accreditationDetails']            = $params['accreditationDetails'];
            if(!empty($params['courseStartDateArray']) && !empty($params['courseStartDateArray'][0])) 
                $highFieldValues['courseStartDateArray'] = "nonemptyvalue";
            else
                $highFieldValues['courseStartDateArray'] =  "";
            
            $highFieldValues['applicationDeadlineLink']         = $params['applicationDeadlineLink'];
            $highFieldValues['admissionWebsiteLink']            = $params['admissionWebsiteLink'];
            $highFieldValues['scholarshipLinkCourseLevel']      = $params['scholarshipLinkCourseLevel'];
            $highFieldValues['scholarshipLinkDeptLevel']        = $params['scholarshipLinkDeptLevel'];
            $highFieldValues['scholarshipLinkUniversityLevel']  = $params['scholarshipLinkUniversityLevel'];
            $highFieldValues['internships']                     = $params['internships'];
            if(!empty($params['recruitingCompaniesArray']) && !empty($params['recruitingCompaniesArray'][0])) 
                $highFieldValues['recruitingCompaniesArray'] = "nonemptyvalue";
            else
                $highFieldValues['recruitingCompaniesArray'] =  "";
            // handle carefully
            $highFieldValues['courseBrochureUrl']               = $params['courseBrochureUrl'];
            
            
        }
        return array(
                    'high_field_values' => $highFieldValues,
                    'low_field_values'  => $lowFieldValues
                    );
    }
    
    function calculatePercentageCompletion($highFieldValues = array(), $lowFieldValues = array()) {
        $totalHighValueFields   = count($highFieldValues);
        $totalLowValueFields    = count($lowFieldValues);
        $totalWeightAge = ( $totalHighValueFields * ENT_SA_HIGH_VALUE_FIELD_STATUS ) + ( $totalLowValueFields * ENT_SA_LOW_VALUE_FIELD_STATUS);
        
        $emptyHighFieldValues = 0;
        foreach($highFieldValues as $key=>$highFieldValue) {
            if(isset($highFieldValue) && trim($highFieldValue) == ""){
                $emptyHighFieldValues++;
            }
        }
        $emptyLowFieldValues = 0;
        foreach($lowFieldValues as $key=>$lowFieldValue) {
            if(isset($lowFieldValue) && trim($lowFieldValue) == ""){
                $emptyLowFieldValues++;
            }
        }
        $totalPenalty = ( $emptyHighFieldValues * ENT_SA_HIGH_VALUE_FIELD_STATUS ) + ( $emptyLowFieldValues * ENT_SA_LOW_VALUE_FIELD_STATUS);
        $netWeightage = (int)$totalWeightAge - (int)$totalPenalty;
        $percentageCompletion = 0;
        if(!empty($totalWeightAge)){
            $percentageCompletion = round( ( $netWeightage / $totalWeightAge ) * 100 );
        }
        return $percentageCompletion;
    }
    function getUniversityDataForEditMode($universityId)
    {
        $data = $this->abroadCMSModelObj->getUniversityFormDataForEditMode($universityId);
        //check if the university already has shiksha apply and also one of it's course is mapped to shiksha apply
        $data ['shikshaApplyMappedCourses'] = $this->getShikshaApplyCoursesForUniversity($universityId);
        return $data;
    }

    function getCityDataForEditMode($cityId)
    {
        $data = $this->abroadCMSModelObj->getCityFormDataForEditMode($cityId);
        return $data;
    }
    
    public function getListingsName($listing_id, $listing_type, $country_id, $courseTypeDetails)
    {
        if($listing_type == 'university')
            return $this->abroadCMSModelObj->getUniversityName($listing_id, $country_id);
        else
            return $this->abroadCMSModelObj->getCourseName($listing_id, $country_id, $courseTypeDetails);
    }
    
    public function getAbroadCourseLevels()
    {
        $abroadListingCacheLib 	= $this->CI->load->library('listing/cache/AbroadListingCache');
        $courseLevels = $abroadListingCacheLib->getAbroadCourseLevels();
        if(empty($courseLevels)){
            $courseLevels = $this->abroadCMSModelObj->getAbroadCourseLevels();
            $abroadListingCacheLib->storeAbroadCourseLevels($courseLevels);
        }
        return $courseLevels;
    }
    /*
     * gets abroad course levels for registration forms where certificate &/|| diploma courses are not shown
     */
    public function getAbroadCourseLevelsForRegistrationForms()
    {
        $abroadListingCacheLib 	= $this->CI->load->library('listing/cache/AbroadListingCache');
        $courseLevels = $abroadListingCacheLib->getAbroadCourseLevelsForRegistrationForms();
        if(empty($courseLevels)){
            $courseLevels = $this->abroadCMSModelObj->getAbroadCourseLevelsForRegistrationForms();
            $abroadListingCacheLib->storeAbroadCourseLevelsForRegistrationForms($courseLevels);
        }
        return $courseLevels;
    }
     /*
     * gets abroad course levels for find college widgets where only certificate-diploma is shown
     * instead of all 4 levels (BD,BC,MD,MC)
     */
    public function getAbroadCourseLevelsForFindCollegeWidgets()
    {
        $courseLevels = $this->getAbroadCourseLevels();
        global $certificateDiplomaLevels;
        $courseLevels = array_unique(array_map(function($a)use($certificateDiplomaLevels){
			return (in_array($a['CourseName'],$certificateDiplomaLevels)?'Certificate - Diploma':$a['CourseName']);
			}, $courseLevels));
        return $courseLevels;
    }
    public function checkForDuplicateRankingPage($rankingType, $desiredCourse, $courseType, $parentCategoryId, $subCategory, $countryId, $rankingId)
    {
        $ldbCourseId = 0;
        
        if(!empty($courseType) && !empty($parentCategoryId))
        {
            // get the ldb id from tCourseSpecializationMapping
            $ldbCourseId = $this->abroadCMSModelObj->fetchAbroadLDBCourse($courseType, $parentCategoryId);
        }
        else{
            $ldbCourseId = $desiredCourse;
        }
        return $this->abroadCMSModelObj->checkForDuplicateRankingPage($rankingType, $ldbCourseId, $subCategory, $countryId, $rankingId);
    }

	public function getTags(){
        return $this->abroadCMSModelObj->getTags();
    }
    
    public function isContentExist($content_type,$tag_stripTitle, $searchTagID, $additionalData){
        return $this->abroadCMSModelObj->isContentExist($content_type,$tag_stripTitle,$searchTagID, $additionalData);
    }
    
    public function convertCurrency($sourceCurrencyId = NULL, $destinationCurrencyId = NULL, $amount = NULL) {
        $convertedCurrency = 0;
        if(!empty($sourceCurrencyId) && !empty($destinationCurrencyId) && !empty($amount)) {
            if($sourceCurrencyId == $destinationCurrencyId){
                $exchangeRate = 1;
            }
            else{
                $exchangeRate = $this->abroadCMSModelObj->getCurrencyExchangeRate($sourceCurrencyId, $destinationCurrencyId);
            }
            if(!empty($exchangeRate)){
                $convertedCurrency = $amount * $exchangeRate;
            }
        }
        return $convertedCurrency;
    }
    public function getUniversityInfo($universityId){
	return $this->abroadCMSModelObj->getUniversityInfo($universityId);
    }
    public function validateSubscriptionInfo($userGroup, $onBehalfOf, $subscriptionId, $clientId)
	{
                $responseArray = array();
		$responseArray[0] = 1;
		if(!($userGroup == 'cms' && $onBehalfOf == "false" ) && $subscriptionId != "") {
			$objSumsProduct = $this->CI->load->library('sums_product_client');
			$subscriptions = $objSumsProduct->getAllPseudoSubscriptionsForUser(1,array('userId'=>$clientId));
			if(is_array($subscriptions[$subscriptionId])) {
				$chosenSubsArray = $subscriptions[$subscriptionId];
			} else {
				$chosenSubsArray = "";
			}

			if(!(is_array($chosenSubsArray) && $chosenSubsArray['BaseProdPseudoRemainingQuantity'] > 0)) {
                            $responseArray[0] = 0;
			    $responseArray[1] = 'Your chosen subscription has been consumed with other listings. Please select some other subscription to proceed.';
			    $responseArray[2] = $subscriptionId;				
			}
		}
		return $responseArray;
	}
        
    public function getExamWithExamPage(){
	return $this->abroadCMSModelObj->getExamWithExamPage();
    }
    
    function getPaidUniversityDetails($universityId, $status = ENT_SA_PRE_LIVE_STATUS){
        return $this->abroadCMSModelObj->getPaidUniversityDetails($universityId, $status);
    }

    /*
     * function to get Lifecycle tags values for generating dropdowns at content CMS
     * @params : array containing lists of abroadCountries, abroad exams, course type
     * @return :data set for all lifecycle tags
     */
    public function getLifecycleTags($data ){
        // collect country, exam, course type lists
        $abroadCountries = $data['abroadCountries'];
        $examMasterList  = $data['abroadExamsMasterList'];
        $courseTypeList  = $data['courseType'];
        // load item from config
        $contentLifecycleTagsFromConfig = $this->CI->config->item('CONTENT_LIFECYCLE_TAGS');
        $contentLifecycleTags = array ();
        foreach($contentLifecycleTagsFromConfig as $k => $v)
        {
            // create a level 1 tag first
            /* the name to be displayed as dropdown option
             * & the value that actually gets saved for that option is same for level 1 tags.
             */
            $lifecycleTag = array('lvl1_value' =>$v['LEVEL1_VALUE']); 
            // time to add level 2 value set now
            $lifecycleTag['lvl2_set'] = array();
            // add an all tag option
            $elemAll = array(array('name'=>'All', 'value'=>'all'));
            // get level 2 value set from corresponding set
            switch($v['LEVEL2_SOURCE']){
                case 'country':
                                $lifecycleTag['lvl2_set'] = array_merge($elemAll,array_map(function($a){return array('name'=>$a->getName(),'value'=>$a->getId());}, $abroadCountries));
                                break;
                case 'exam'   :
                                $lifecycleTag['lvl2_set'] = array_merge($elemAll,array_map(function($a){return array('name'=>$a['exam'],'value'=>$a['examId']);}, $examMasterList));
                                break;
                case 'course' :
                                $lifecycleTag['lvl2_set'] = array_merge($elemAll,array_map(function($a){return array('name'=>$a['CourseName'],'value'=>$a['CourseName']);}, $courseTypeList));
                                break;
                case 'self'   :
                                $lifecycleTag['lvl2_set'] = array_merge($elemAll,array_map(function($a){ return array('name'=>$a,'value'=>$a);},$v['LEVEL2_VALUES']));
                                break;
            }
            // add to lifecycle tag array
            $contentLifecycleTags[$k]= $lifecycleTag;
        }
        return $contentLifecycleTags;
    }
    
    public function getShikshaApplyProfileForUniversity($universityId){
        
        $data = $this->abroadCMSModelObj->getUniversityApplicationProfiles($universityId,'live',false);
        $finalData = array();
        foreach($data as $key=>$value){
            $finalData[$key]['applicationProfileId'] = $value['applicationProfileId'];
            $finalData[$key]['name'] = htmlentities($value['name']);
            $finalData[$key]['applyNowLink'] = $value['applyNowLink'];
        }
        return $finalData;
    }

    public function getUnivesityMappedConsultants($universityId)
    {
        return count($this->abroadCMSModelObj->getUnivesityMappedConsultants($universityId));
    }
    //this function is to fetch all the shiksha apply mapped courses of a university if available
    //this helps us to determine whether we can check or uncheck the shiksha apply enabled flag on university
    public function getShikshaApplyCoursesForUniversity($universityId)
    {
        return count($this->abroadCMSModelObj->getShikshaApplyCoursesForUniversity($universityId));
    }
    /*
	 * does content type have a homepage yet?
	 */
	public function isHomepageAvailable($applyContentType = 0)
	{
        $numHomepages = $this->abroadCMSModelObj->isHomepageAvailable($applyContentType);
        return ($numHomepages>0);
    }
    
    public function isExamHomepageAvailable($examContentType = 0,$contentId=0)
	{
        if($examContentType>0){
            $numHomepages = $this->abroadCMSModelObj->isExamHomepageAvailable($examContentType,$contentId);
        }
        return (($numHomepages==0)?true:false);
    }

    public function getCourseSpecializations($category,$courseLevel,$subCategory)
    {
        return $this->abroadCMSModelObj->getCourseSpecializations($category,$courseLevel,$subCategory);
    }
    public function getPaidDetailsForUniversities($universityIds)
    {
        return $this->abroadCMSModelObj->getPaidDetailsForUniversities($universityIds);
    }
    public function getUniversityNameById($universityId)
    {
        $this->CI->load->model('listing/universitymodel');
        $this->universitymodel  = new universitymodel();
        return $this->universitymodel->getUniversityNameById($universityId);
    }

    public function getDetailsForExamById($examId){
        $examListData       = $abroadCommonLib->getAbroadExamsMasterList();
        foreach($examListData as $exam){
            if($exam['examId'] == $examId){
                return $exam;
            }
        }
    }
    public function getApplyIntakeFields()
    {
        $this->CI->config->load('studyAbroadCMSConfig');
        $returnArr = array();
        $returnArr['intakeSeasons'] = $this->CI->config->item('UNIV_INTAKE_SEASON');
        $returnArr['intakeYears'] = array(
                date('Y',strtotime('-1 year')),
                date('Y'),
                date('Y',strtotime('+1 year')),
                date('Y',strtotime('+2 year'))
        );
        for($i=1;$i<=12;$i++)
        {
            $d = date_create_from_format('n',$i);
            //$returnArr['intakeMonths'][$d->format('F')] = $d->format('M');
            $returnArr['intakeMonths'][$i] = $d->format('M');
            if($i<=7)
            {
                $returnArr['intakeRounds'][$i] = "Round ".$i;
            }
        }
        return $returnArr;
    }

    public function getContentIdByContentTypeId($contentTypeId,$contentIds,$contentType)
    {
        $words = explode('_',$contentType);
        $contentType = $words[0].ucfirst($words[1]);
        return  $this->abroadCMSModelObj->getContentIdByContentTypeId($contentTypeId, $contentIds, $contentType);
    }

    public function getAbroadCountries($excludeAllOption=true)
    {
        $this->CI->load->builder('LocationBuilder','location');
        $locationBuilder = new LocationBuilder;
        $locationRepository = $locationBuilder->getLocationRepository();

        $countries = $locationRepository->getAbroadCountries();
        if($excludeAllOption === true){
            foreach($countries as $key => $country){
                if($country->getId() == 1) {
                    unset($countries[$key]);
                    break;
                }
            }
        }
        //sort countries by name ascending order
        usort($countries,function($c1,$c2){
            return (strcasecmp($c1->getName(),$c2->getName()));
        });
        return $countries;
    }
}
