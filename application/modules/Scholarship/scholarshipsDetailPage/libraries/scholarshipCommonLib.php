<?php

class scholarshipCommonLib{

    function __construct(){
        $this->CI = &get_instance();
        $this->scholarshipModel = $this->CI->load->model('scholarshipsDetailPage/scholarshipmodel');
        $this->CI->load->config('scholarshipsDetailPage/scholarshipConfig');

        $this->CI->load->builder('location/LocationBuilder');
        $this->locationBuilder = new LocationBuilder();
        $this->locationRepo = $this->locationBuilder->getLocationRepository();
        $this->abroadCommonLib = $this->CI->load->library('listingPosting/AbroadCommonLib');

        $this->scholarshipDetailPageSolrRequestGenerator  = $this->CI->load->library('scholarshipsDetailPage/solr/scholarshipDetailPageSolrRequestGenerator');
        $this->solrClient = $this->CI->load->library("SASearch/AutoSuggestorSolrClient");
        $this->scholarshipDetailPageSolrResponseParser  = $this->CI->load->library('scholarshipsDetailPage/solr/scholarshipDetailPageSolrResponseParser');
    }
    
    public function getScholarshipIdByUrl($scholarshipUrl){
        $result = array();
        if($scholarshipUrl!=''){
            $result = $this->scholarshipModel->getScholarshipIdByUrl($scholarshipUrl);
        }else{
            $result['error'] = "Please enter URL";
        }
        if(count($result)==0 || empty($result)){
            $result['error'] = "Please enter valid URL";
        }
        return $result;
    }
    
    public function prepareTrackingData($pageIdentifier, $pageEntityId, &$displayData){
        $extraData = array();
        $scholarshipCategory = $displayData['scholarshipObj']->getCategory();
        switch ($scholarshipCategory) {
            case 'internal':
                $extraData = $this->_getExtraDataForInternalScholarships($displayData);
                break;
            
            case 'external':
                $extraData = $this->_getExtraDataForExternalScholarships($displayData);
                break;
        }
        $extraData['scholarshipCategory'] = $scholarshipCategory;

        $beaconTrackData = array(
            'pageIdentifier' => $pageIdentifier,
            'pageEntityId' => $pageEntityId,
            'extraData' => $extraData
        );
        return $beaconTrackData;
    }

    private function _getExtraDataForInternalScholarships(&$displayData){
        $extraData = array();
        foreach ($displayData['internalCourseObjs'] as $courseId => $courseObj) {
            $extraData['LDBCourseId'][] = $courseObj->getLDBCourseId();
        }
        return $extraData;
    }

    private function _getExtraDataForExternalScholarships(&$displayData){
        $extraData = array();
        $LDBCourseId = $displayData['scholarshipObj']->getHierarchy()->getSpecialization();
        if(!empty($LDBCourseId) && $LDBCourseId[0] != 'all'){
            $extraData['LDBCourseId'] = $LDBCourseId;
        }
        return $extraData;
    }

    public function getScholarshipSeoData($scholarshipObj){
        $seoDetails = $this->CI->config->item('seoDetails');
        $seoDetails = $seoDetails['scholarshipDetailPage'];
        $returnArray = array();
        $scholarshipName = $scholarshipObj->getName();
        if($scholarshipObj->getSeoTitle()!=''){
            $returnArray['seoTitle'] = $scholarshipObj->getSeoTitle();
        }
        else{
            $returnArray['seoTitle'] = str_replace('<scholarship_name>', $scholarshipName, $seoDetails['seoTitle']);
        }
        
        if($scholarshipObj->getSeoDescription()!=''){
            $returnArray['seoDescription'] = $scholarshipObj->getSeoDescription();
        }
        else{
            $returnArray['seoDescription'] = str_replace('<scholarship_name>', $scholarshipName, $seoDetails['seoDescription']);
        }
        
        if($scholarshipObj->getSeoKeywords()!=''){
            $returnArray['seoKeywords'] = $scholarshipObj->getSeoKeywords();
        }
        $returnArray['url'] = $scholarshipObj->getUrl();
        return $returnArray;
    }

    public function validateUrl(){
      $currentUrl      = getCurrentPageURLWithoutQueryParams();
      $currentUrl      = trim($currentUrl,'/');
      $currentUrl      = str_replace(SHIKSHA_STUDYABROAD_HOME, '', $currentUrl);
      $scholarshipData = $this->getScholarshipIdByUrl($currentUrl);
      if($scholarshipData['error']!='' || empty($scholarshipData['scholarshipId'])){
        show_404_abroad();
      }
      
      $scholarshipId = $scholarshipData['scholarshipId'];
      //$correctUrl = $this->scholarshipModel->getURLByScholarshipId($scholarshipId);
      $this->CI->load->builder('scholarshipsDetailPage/scholarshipBuilder');
      $scholarshipBuilder        = new scholarshipBuilder();
      $scholarshipRepository     = $scholarshipBuilder->getScholarshipRepository();
      $sections = array("basic"=>"seoUrl");
      $scholarshipObj = $scholarshipRepository->find($scholarshipId,$sections);
      if(is_object($scholarshipObj)){
        $correctUrl = str_replace(SHIKSHA_STUDYABROAD_HOME, '',$scholarshipObj->getUrl());
      }
      if(empty($correctUrl)){  // no live url
          show_404_abroad();
      }
      if($currentUrl!=$correctUrl){
          redirect(SHIKSHA_STUDYABROAD_HOME.$correctUrl, 'location', 301);
      }
      return $scholarshipData['scholarshipId'];
    }

    public function getCountryAndCourseLevelData(&$displayData,&$scholarshipObj){
        $countries = $scholarshipObj->getApplicationData()->getApplicableCountries();
        if($scholarshipObj->getCategory() == 'external'){
            if($countries[0] == -1){
              global $studyAbroadPopularCountries;
              $countries = $this->locationRepo->getAllCountries();
              unset($countries['All']);
              ksort($countries);
              $countryObjs = $countries;
              $displayData['applicableCountries'] = array_values($studyAbroadPopularCountries);
            }else{
              $countryObjs = $this->locationRepo->getAbroadCountryByIds($countries,true);
            }

            if(in_array('all',$scholarshipObj->getHierarchy()->getCourseLevel())){
                $allCourseLevels = $this->abroadCommonLib->getAbroadCourseLevels();
                $displayData['courseLevels'] = array_map(function($a){ return $a['CourseName']; }, $allCourseLevels);
            }
            $displayData['courseLevels'] = array_unique($displayData['courseLevels'] );
            
            foreach ($countryObjs as $countryId => $obj) {
              if(!in_array($obj->getName(), $displayData['applicableCountries']))
                $displayData['applicableCountries'][] = $obj->getName();
            }
          }else if($scholarshipObj->getCategory() == 'internal'){
            $this->CI->load->builder('ListingBuilder','listing');
            $listingBuilder       = new ListingBuilder;
            $this->abroadCourseRepository       = $listingBuilder->getAbroadCourseRepository();
            $this->abroadUniversityRepository   = $listingBuilder->getUniversityRepository();
            $university       = $this->abroadUniversityRepository->find($scholarshipObj->getHierarchy()->getUniversity());
            $displayData['applicableCountries'][] = reset($university->getLocations())->getCountry()->getName();
            $displayData['organisationLogo'] = $university->getLogoLink();
            $displayData['organisationName'] = $university->getName();
            $course = $this->abroadCourseRepository->findMultiple($scholarshipObj->getHierarchy()->getCourses());
            $displayData['internalCourseObjs'] = $course;
            $displayData['universityName'] = $university->getName();
            $displayData['universityUrl'] = $university->getUrl();
            $displayData['internalCourses'] = $course;
            foreach ($course as $courseId => $obj) {
              $displayData['courseLevel'][] = $obj->getCourseLevel1Value();
            }
            $displayData['courseLevel'] = array_unique($displayData['courseLevel']);
        }
    }

    public function getIntakeYearData(&$displayData){
        $intakeYears = $displayData['scholarshipObj']->getApplicationData()->getIntakeYears();
        $timestamps = array();
        $today = strtotime(date('Y-m-d'));
        $currMonth = date('n');
        $intakeYearToShow = date('M Y', strtotime($intakeYears[0]));
        foreach ($intakeYears as $key => $year) {
            $timestamp = strtotime($year);
            if($timestamp - $today > 0){
                $month = date('j', $timestamp);
                if($month != $currMonth){
                    $intakeYearToShow = date('Y-m-d', $timestamp);
                    break;
                }
            }
        }
        $displayData['intakeYearToShow'] = date('M Y', strtotime($intakeYearToShow));
        $displayData['allIntakeYears'] = $intakeYears;
    }

    public function getCurrencyConvertRate(&$displayData){
        $libryObj = $this->CI->load->library('listing/AbroadListingCommonLib');
        $commnObj = $this->CI->load->library('common/studyAbroadCommonLib');
        $amountCurrencyId = $displayData['scholarshipObj']->getAmount()->getAmountCurrency();
        $displayData['currencyName'] = $libryObj->getCurrencyCodeById($amountCurrencyId);
        $currenncyRateDetails = $commnObj->getCurrenncyRateDetails($amountCurrencyId, 1);
        $displayData['currencyRate'] = $currenncyRateDetails['conversion_factor'];
        $displayData['currencyRateUpdateTime'] = $currenncyRateDetails['modified'];
    }

    public function getMisceleneousConfigItems(&$displayData){
        $this->CI->load->config('scholarshiPosting/studyAbroadCMSConfig');
        $displayData['expensesCoveredList'] = $this->CI->config->item('EXPENSES_COVERED_MASTER');
        $displayData['specialRestrictions'] = $this->CI->config->item('SCHOLARSHIP_SPECIAL_RESTRICTION');
        $displayData['scholarshipIntervalArray'] = $this->CI->config->item('SCHOLARSHIP_AMOUNT_INTERVAL');
    }

    public function getEligibilityExamsList(&$displayData){
        $examMasterList = $this->abroadCommonLib->getAbroadExamsMasterList();
        $examList = array();
        foreach ($examMasterList as $value) {
            $examList[$value['examId']] = $value['exam'];
        }
        $displayData['examMasterList'] = $examList;
    }

    public function getEligibleStudentNationalities(&$displayData){
        $applicableNations = $displayData['scholarshipObj']->getApplicationData()->getApplicableNationalities();
        $displayData['applicableNationalities'] = array();

        if(!empty($applicableNations)){
            
            if($applicableNations[0] == -1){
                $countryObjs = $this->locationRepo->getAllCountries();
            }else{
                $countryObjs = $this->locationRepo->getAbroadCountryByIds($applicableNations,true);
            }

            if(in_array('India', array_keys($countryObjs))){
                $displayData['applicableNationalities'][0] = 'India';    
                unset($countryObjs['India']);
            }
            
            ksort($countryObjs);

            foreach ($countryObjs as $countryId => $obj) {
                $displayData['applicableNationalities'][] = $obj->getName();
            }

        }
    }

    public function getRequiredDocuments(&$displayData){
        $docsRequired = array();
        if($displayData['scholarshipObj']->getApplicationData()->getSOP() != ''){
            $docsRequired['SOP'] = $displayData['scholarshipObj']->getApplicationData()->getSOP();
        }
        if($displayData['scholarshipObj']->getApplicationData()->getLOR() != ''){
            $docsRequired['LOR'] = $displayData['scholarshipObj']->getApplicationData()->getLOR();
        }
        if($displayData['scholarshipObj']->getApplicationData()->getEssays() != ''){
            $docsRequired['Essays'] = $displayData['scholarshipObj']->getApplicationData()->getEssays();
        }
        if($displayData['scholarshipObj']->getApplicationData()->getCV() != ''){
            $docsRequired['CV'] = $displayData['scholarshipObj']->getApplicationData()->getCV();
        }
        if($displayData['scholarshipObj']->getApplicationData()->getFinancialDocuments() != ''){
            $docsRequired['Financial/Tax Documents'] = $displayData['scholarshipObj']->getApplicationData()->getFinancialDocuments();
        }
        if($displayData['scholarshipObj']->getApplicationData()->getWorkExperience() != ''){
            $docsRequired['Work Experience'] = $displayData['scholarshipObj']->getApplicationData()->getWorkExperience();
        }
        if($displayData['scholarshipObj']->getApplicationData()->getOfficialTranscripts() != ''){
            $docsRequired['Official transcripts'] = $displayData['scholarshipObj']->getApplicationData()->getOfficialTranscripts();
        }
        if($displayData['scholarshipObj']->getApplicationData()->getExtraCurricularActivities() != ''){
            $docsRequired['Extra curricular activities'] = $displayData['scholarshipObj']->getApplicationData()->getExtraCurricularActivities();
        }
        $displayData['docsRequired'] = $docsRequired;
    }

    public function getImpDatesData(&$displayData){
        $deadLineEndDate = $displayData['scholarshipObj']->getDeadLine()->getApplicationEndDate();
        $deadLineStartDate = $displayData['scholarshipObj']->getDeadLine()->getApplicationStartDate();
        $impDates = $displayData['scholarshipObj']->getDeadLine()->getImportantDates();
        $finalDateData = array();
                
        if($displayData['scholarshipObj']->getDeadLine()->getApplicationStartDate() != '' || 
                $displayData['scholarshipObj']->getDeadLine()->getApplicationStartDateDescription() != ''){
            $finalDateData[] = array(
                            'heading'     => 'Application start date', 
                            'description' => $displayData['scholarshipObj']->getDeadLine()->getApplicationStartDateDescription(),
                            'timestamp' => strtotime($deadLineStartDate)
                            );
        }

        if($displayData['scholarshipObj']->getDeadLine()->getApplicationEndDate() != '' ||
                $displayData['scholarshipObj']->getDeadLine()->getApplicationEndDateDescription() != ''){
            $finalDateData[] = array(
                            'heading'     => 'Application final end date', 
                            'description' => $displayData['scholarshipObj']->getDeadLine()->getApplicationEndDateDescription(),
                            'timestamp' => strtotime($deadLineEndDate)
                    );
        }
        
        foreach ($impDates as $value) {
            $finalDateData[] = array(
                        'heading' => $value['impDateHeading'],
                        'description' => $value['impDateDescription'],
                        'timestamp' => strtotime($value['impDate'])
                        );
        }        
        $displayData['finalImpDateData'] = $finalDateData;
    }
    
    public function saveFeedbackData(& $dataArr) {
        return $this->scholarshipModel->saveFeedbackData($dataArr);
    }

    public function getSimiralScholarships(&$displayData){
        $widgetFilterData = $this->_getWidgetFilterData($displayData['scholarshipObj']);
        $displayData['finalSimilarScholarships'] = array();
        $this->_getSimilarScholarshipsForFilter($displayData, $widgetFilterData);
        if(count($displayData['finalSimilarScholarships']) < 10){
            $scholarshipIdsToSkip = array_map(function($a){return $a['saScholarshipId'];}, $displayData['finalSimilarScholarships']);
            $widgetFilterData = $this->_getWidgetFilterData($displayData['scholarshipObj'], true);
            $widgetFilterData['scholarshipIdsToSkip'] = $scholarshipIdsToSkip;
            $this->_getSimilarScholarshipsForFilter($displayData, $widgetFilterData);
        }
        $displayData['similarScholarships']['scholarships'] = $displayData['finalSimilarScholarships'];
        unset($displayData['finalSimilarScholarships']);
        $this->_getUrlForSimilarScholarships($displayData['similarScholarships']);
    }

    private function _getSimilarScholarshipsForFilter(&$displayData, $widgetFilterData){
        $widgetFilterData['currentScholarshipId'] = $displayData['scholarshipObj']->getId();
        if(!empty($widgetFilterData['scholarshipIdsToSkip'])){
            $widgetFilterData['currentScholarshipId'] .= ' '.implode(' ', $widgetFilterData['scholarshipIdsToSkip']);
        }
        $limit = 10 - count($displayData['finalSimilarScholarships']);
        $solrUrl = $this->scholarshipDetailPageSolrRequestGenerator->getSimilarScholarshipsRequestUrl($widgetFilterData, $limit);
        $solrResponse = $this->solrClient->getCategoryPageResults($solrUrl,'scholarshipDetailPage');
        $scholarshipData = $this->scholarshipDetailPageSolrResponseParser->formatSimilarScholarshipsSolrResponse($solrResponse, $widgetFilterData);
        $displayData['finalSimilarScholarships'] = array_merge($displayData['finalSimilarScholarships'], $scholarshipData['scholarships']);
    }

    private function _getWidgetFilterData(&$scholarshipObj, $skipCountryFilter = false){
        $returnData = array();
        $scholarshipCategory = $scholarshipObj->getCategory();
        if($scholarshipCategory == 'internal'){
            $courseIds = $scholarshipObj->getHierarchy()->getCourses();
            $result = $this->scholarshipModel->getCourseLevelAndCategories($courseIds);
            $returnData['courseLevels'] = array_values($result['courseLevels']);
            $returnData['categories'] = array_values($result['categories']);
            $returnData['applicableCountries'] = array_values($result['countries']);
        }else if($scholarshipCategory == 'external'){
            $returnData['courseLevels'] = $scholarshipObj->getHierarchy()->getCourseLevel();
            $returnData['categories'] = $scholarshipObj->getHierarchy()->getParentCategory();
            $returnData['applicableCountries'] = $scholarshipObj->getApplicationData()->getApplicableCountries();
            if($returnData['courseLevels'][0] == 'all'){
                unset($returnData['courseLevels']);
            }
            if($returnData['categories'][0] == 'all'){
                unset($returnData['categories']);
            }
            if($returnData['applicableCountries'][0] == -1){
                $returnData['applicableCountries'][0] = 1;
            }
            if($skipCountryFilter == true){
                unset($returnData['applicableCountries']);
            }
        }
        return $returnData;
    }

    private function _getUrlForSimilarScholarships(&$scholarshipData){
        $allScholarshipIds = array_map(function($a){return $a['saScholarshipId'];}, $scholarshipData['scholarships']);
        if(count($allScholarshipIds) > 0){
            $this->CI->load->builder('scholarshipsDetailPage/scholarshipBuilder');
            $scholarshipBuilder    = new scholarshipBuilder();
            $this->scholarshipRepository = $scholarshipBuilder->getScholarshipRepository();
            $sections = array('basic'=>array('name', 'seoUrl'), 'amount'=>array('totalAmountPayout', 'convertedTotalAmountPayout'), 'deadline'=>array('numAwards'));
            $scholarshipObjs = $this->scholarshipRepository->findMultiple($allScholarshipIds, $sections);
            foreach ($scholarshipObjs as $obj) {
                $scholarshipData['name'][$obj->getId()]      = $obj->getName();
                $scholarshipData['urls'][$obj->getId()]      = $obj->getUrl();
                $scholarshipData['amount'][$obj->getId()]    = $obj->getAmount()->getConvertedTotalAmountPayout();
                $scholarshipData['awards'][$obj->getId()] = $obj->getDeadLine()->getNumAwards();
            }
        }
    }
    
}
