<?php

class SearchPageLibV2 {

    private $CI = '';
    private $searchAliases;
            function __construct() {
        $this->CI = &get_instance();
        $this->CI->load->config("SASearch/SASearchPageConfig");
        $this->searchAliases=$this->CI->config->item('SA_SEARCH_PARAMS_FIELDS_ALIAS');
    }

    public function prepareAccordianDataForLocation($displayData,$prepareParentFilter) {
        if($prepareParentFilter){
            $location=$displayData['filtersParent']['location'];
        }else{
            $location=$displayData['filters']['location'];
        }
        $finalLocationFilters = array();
        $stateAccordianData = $this->_prepareLocationDataForState($location);
        $finalLocationFilters = array_merge($finalLocationFilters, $stateAccordianData);
        if (isset($location['country'])) {
            $countryLocationFilter = $this->_prepareLocationDataForCountry($location['country']);
            $finalLocationFilters = array_merge($finalLocationFilters, $countryLocationFilter);
        }
        if (isset($location['all'])) {
            $allLocationFilter = $this->_prepareLocationDataForAll($location['all']);
            $finalLocationFilters = array_merge($finalLocationFilters, $allLocationFilter);
        }

        $continentLocationFilter = $this->_prepareLocationDataForContinent($location);
        $finalLocationFilters = array_merge($finalLocationFilters, $continentLocationFilter);
        return $finalLocationFilters;
    }

    private function _prepareLocationDataForState($location) {
        $accordianData = array();
        if (isset($location['state'])) {
            foreach ($location['state'] as $stateId => $stateCities) {
                $singleAccordianData = array();
                if (!is_array($stateCities)) {
                    continue;
                }
                foreach ($stateCities as $cityId => $cities) {
                    if (!is_array($cities)) {
                        continue;
                    }
                    $singleAccordianData['heading'] = $cities['name'];
                    $singleAccordianData['count'] = $cities['count'];
                    $singleAccordianData['id'] = $cityId;
                    $singleAccordianData['type'] = $this->searchAliases['city'];
                    $singleAccordianData['children'] = array();
                    array_push($accordianData, $singleAccordianData);
                }
               
            }
        }
        return $accordianData;
    }

    private function _prepareLocationDataForCountry($locationCountry) {
        $accordianData = array();
        if ($locationCountry) {
            $countriesWithStates = $locationCountry['stateCountries'];
            $countriesWithOnlyCities = $locationCountry['cityCountries'];
            $this->_processCountriesWithStates($countriesWithStates, $accordianData);
            $this->_processStateLessCountries($countriesWithOnlyCities, $accordianData);
        }
        return $accordianData;
    }

    private function _prepareLocationDataForAll($locationAll) {
        $accordianData = array();
        if (isset($locationAll)) {
            $countriesWithStates = $locationAll['stateCountries'];
            $countriesWithOnlyCities = $locationAll['cityCountries'];
            $this->_processCountriesWithStatesForAll($countriesWithStates, $accordianData);
            $this->_processStateLessCountriesForAll($countriesWithOnlyCities, $accordianData);
        }
        return $accordianData;
    }

    private function _processStateLessCountries($countriesWithOnlyCities, &$accordianData) {
        foreach ($countriesWithOnlyCities as $countryId => $countryWithcityDetails) {
            if (!is_array($countryWithcityDetails)) {
                continue;
            }
            $countryName = $countryWithcityDetails['name'];
            $countryCount = $countryWithcityDetails['count'];
            if (!is_array($countryWithcityDetails)) {
                continue;
            }
            foreach ($countryWithcityDetails as $cityId => $cityDetails) {
                if (!is_array($cityDetails)) {
                    continue;
                }
                $singleAccordianData = array();
                $singleAccordianData['heading'] = $cityDetails['name'];
                $singleAccordianData['id'] = $cityId;
                $singleAccordianData['count'] = $cityDetails['count'];
                $singleAccordianData['type'] = $this->searchAliases['city'];
                $singleAccordianData['children'] = array();
                array_push($accordianData, $singleAccordianData);
            }
        }
    }
    
    private function _processStateLessCountriesForAll($countriesWithOnlyCities, &$accordianData) {
        foreach ($countriesWithOnlyCities as $countryId => $countryWithcityDetails) {
            if (!is_array($countryWithcityDetails)) {
                continue;
            }
            $countryName = $countryWithcityDetails['name'];
            $countryCount = $countryWithcityDetails['count'];
            $singleAccordianData = array();
            $singleAccordianData['heading'] = $countryName;
            $singleAccordianData['id'] = $countryId;
            $singleAccordianData['count'] = $countryCount;
            $singleAccordianData['type'] = $this->searchAliases['country'];
            $singleAccordianData['children'] = array();
            foreach ($countryWithcityDetails as $cityId => $cityDetails) {
                if (!is_array($cityDetails)) {
                    continue;
                }
                $childrenDetails = array('id' => $cityId, 'heading' => $cityDetails['name'], 'count' => $cityDetails['count'], 'type' => $this->searchAliases['city']);
                array_push($singleAccordianData['children'], $childrenDetails);
            }
            array_push($accordianData, $singleAccordianData);
        }
    }

    private function _processCountriesWithStates($countriesWithStates, &$accordianData) {
        foreach ($countriesWithStates as $countryId => $stateDetails) {
            if (!is_array($stateDetails)) {
                continue;
            }
            $countryName = $stateDetails['name'];
            $countryCount = $stateDetails['count'];

            foreach ($stateDetails as $stateId => $cityInStates) {
                if (!is_array($cityInStates)) {
                    continue;
                }
                $singleAccordianData = array();
                $singleAccordianData['heading'] = $cityInStates['name'];
                $singleAccordianData['id'] = $stateId;
                $singleAccordianData['count'] = $cityInStates['count'];
                $singleAccordianData['type'] = $this->searchAliases['state'];
                $singleAccordianData['children'] = array();
                if (!is_array($cityInStates)) {
                    continue;
                }
                foreach ($cityInStates as $cityId => $cityDetails) {
                    if (!is_array($cityDetails)) {
                        continue;
                    }
                    $childrenDetails = array('id' => $cityId, 'heading' => $cityDetails['name'], 'count' => $cityDetails['count'], 'type' => $this->searchAliases['city']);
                    array_push($singleAccordianData['children'], $childrenDetails);
                }
                array_push($accordianData, $singleAccordianData);
            }
        }
    }
        private function _processCountriesWithStatesForAll($countriesWithStates, &$accordianData) {
        foreach ($countriesWithStates as $countryId => $stateDetails) {
            if (!is_array($stateDetails)) {
                continue;
            }
            $countryName = $stateDetails['name'];
            $countryCount = $stateDetails['count'];
            $singleAccordianData = array();
            $singleAccordianData['heading'] = $countryName;
            $singleAccordianData['id'] = $countryId;
            $singleAccordianData['count'] = $countryCount;
            $singleAccordianData['type'] = $this->searchAliases['country'];;
            $singleAccordianData['children'] = array();
            foreach ($stateDetails as $stateId => $cityInStates) {
                if (!is_array($cityInStates)) {
                    continue;
                }
                $childrenDetails = array('id' => $stateId, 'heading' => $cityInStates['name'], 'count' => $cityInStates['count'], 'type' => $this->searchAliases['state']);
                array_push($singleAccordianData['children'], $childrenDetails);
            }
            array_push($accordianData, $singleAccordianData);
        }
    }

    private function _prepareLocationDataForContinent($location) {
        $accordianData = array();

        if (isset($location['continent'])) {
            foreach ($location['continent'] as $continentId => $continentWithCountryDetails) {
                if (!is_array($continentWithCountryDetails)) {
                    continue;
                }
                foreach ($continentWithCountryDetails as $countryID => $countryStateDetails) {
                    if (!is_array($countryStateDetails)) {
                        continue;
                    }
                    $singleAccordianData = array();
                    $singleAccordianData['heading'] = $countryStateDetails['name'];
                    $singleAccordianData['id'] = $countryID;
                    $singleAccordianData['type'] = $this->searchAliases['country'];;
                    $singleAccordianData['count'] = $countryStateDetails['count'];
                    $singleAccordianData['children'] = array();
                    foreach ($countryStateDetails as $stateId => $stateDetails) {
                        if (!is_array($stateDetails)) {
                            continue;
                        }
                        $childrenDetails = array('heading' => $stateDetails['name'], 'id' => $stateId, 'count' => $stateDetails['count'], 'type' => $this->searchAliases['state']);
                        array_push($singleAccordianData['children'], $childrenDetails);
                    }
                    array_push($accordianData, $singleAccordianData);
                }
            }
        }
        return $accordianData;
    }

    public function prepareDataForSpecFilter($displayData,$prepareParentFilter) {
        if($prepareParentFilter){
            $courseDetails=$displayData['filtersParent']['course'];
        }else{
            $courseDetails=$displayData['filters']['course'];
        }
    
        $subCategoryAccordianData = $this->_prepareDataForSubCategory($displayData,$courseDetails);
    //    _p($subCategoryAccordianData);exit;
        $categoryAccordianData = $this->_prepareDataForCategory($displayData,$courseDetails);
        $specializationAccordianData = $this->_prepareDataForSpecialization($displayData,$courseDetails);
        $finalSpData = array_merge($subCategoryAccordianData, $categoryAccordianData, $specializationAccordianData);
        return $finalSpData;
    }

    private function _prepareDataForSubCategory($displayData,$courseDetails) {
        $accordianData = array();
        if (isset($courseDetails['subcategory'])) {
            foreach ($courseDetails['subcategory'] as $subCategoryId => $subCategoryDetails) {
                if (!is_array($subCategoryDetails)) {
                    continue;
                }
                
            //    $updatedsubCategoryDetails=  $this->_removeDuplicateSpecialization($subCategoryDetails);
                foreach ($subCategoryDetails as $specializationName => $specializationDetails) {
                    if (!is_array($specializationDetails)) {
                        continue;
                    }
                    $specializationID=$specializationDetails['id'];
                    $singleAccordianData = array();
                    $singleAccordianData['heading'] = $specializationName;
                    $singleAccordianData['count'] = $specializationDetails['count'];
                    $singleAccordianData['id'] = $specializationID;
                    $singleAccordianData['type'] = $this->searchAliases['specializationIds'];
                    $singleAccordianData['repeat']=$specializationDetails['repeat'];
                    $singleAccordianData['children'] = array();
                    array_push($accordianData, $singleAccordianData);
                }
            }
        }
        return $accordianData;
    }

    private function _prepareDataForCategory($displayData,$courseDetails) {
        $accordianDetails = array();
        if (isset($courseDetails['category'])) {
            foreach ($courseDetails['category'] as $subCategoryId => $subCategoryDetails) {
                if (!is_array($subCategoryDetails)) {
                    continue;
                }
                $singleAccordianData = array();
                $singleAccordianData['heading'] = $subCategoryDetails['name'];
                $singleAccordianData['type'] =  $this->searchAliases['subCategoryIds'];
                $singleAccordianData['count'] = $subCategoryDetails['count'];
                $singleAccordianData['id'] = $subCategoryId;
                $singleAccordianData['children'] = array();
          //      $updatedsubCategoryDetails=  $this->_removeDuplicateSpecialization($subCategoryDetails);
                foreach ($subCategoryDetails as $specilizationName => $specializationDetails) {
                    $specilizationId=$specializationDetails['id'];
                    if (!is_array($specializationDetails)) {
                        continue;
                    }
                    $spDetails = array('heading' =>$specilizationName, 'count' => $specializationDetails['count'],
                        'id' => $specilizationId, 'type' => $this->searchAliases['specializationIds'],'repeat'=>$specializationDetails['repeat']);
                    array_push($singleAccordianData['children'], $spDetails);
                }
                array_push($accordianDetails, $singleAccordianData);
            }
        }
    
        return $accordianDetails;
    }

    private function _prepareDataForSpecialization($displayData,$courseDetails) {
        $accordianDetails = array();
        if (isset($courseDetails['specialization'])) {
            foreach ($courseDetails['specialization'] as $categoryId => $categoryDetails) {
                if (!is_array($categoryDetails)) {
                    continue;
                }
                foreach ($categoryDetails as $subCatId => $subCatAndSpecializationDetails) {
                    if (!is_array($subCatAndSpecializationDetails)) {
                        continue;
                    }
                    $singleAccordianDetails = array();
                    $singleAccordianDetails['heading'] = $subCatAndSpecializationDetails['name'];
                    $singleAccordianDetails['count'] = $subCatAndSpecializationDetails['count'];
                    $singleAccordianDetails['type'] = $this->searchAliases['subCategoryIds'];
                    $singleAccordianDetails['id'] = $subCatId;
                    $singleAccordianDetails['children'] = array();
                //    $updatedSubCatAndSpecializationDetails=  $this->_removeDuplicateSpecialization($subCatAndSpecializationDetails);
                   
                    foreach ($subCatAndSpecializationDetails as $specializationName => $specializationDetails) {
                  
                        if (!is_array($specializationDetails)) {
                            continue;
                        }
                        $specializationId=$specializationDetails['id'];
                        $spDetails = array('id' => $specializationId, 'heading' => $specializationName,
                            'count' => $specializationDetails['count'], 'type' => $this->searchAliases['specializationIds'],'repeat'=>$specializationDetails['repeat']);
                        array_push($singleAccordianDetails['children'], $spDetails);
                    }
                    array_push($accordianDetails, $singleAccordianDetails);
                }
                
            }
        }
       // _p($accordianDetails);exit;
        return $accordianDetails;
    }

    public function trackSearchData($trackingData){
        $model = $this->CI->load->model('SASearch/sasearchmodel');
        return $model->trackSearchData($trackingData);
    }

    public function trackSearchPageInteraction($trackingData){
        $model = $this->CI->load->model('SASearch/sasearchmodel');
        $model->trackSearchPageInteraction($trackingData);
    }
   
    private function _removeDuplicateSpecialization($specializationList) {
        $totalCountPerSpecializationNameHash=array();
        $specializationNameHash=array();
        foreach ($specializationList as $specializationID => $specializationDetails) {
            $specializationName=$specializationDetails['name'];
            $specializationDetails['specId']=$specializationID;
            if(!isset($specializationNameHash[$specializationName])){
                $specializationNameHash[$specializationName]=array();
                $totalCountPerSpecializationNameHash[$specializationName]=$specializationDetails['count'];
                array_push($specializationNameHash[$specializationName], $specializationDetails);
            }else{
                if($specializationDetails['count']>$totalCountPerSpecializationNameHash[$specializationName]){
                      $totalCountPerSpecializationNameHash[$specializationName]=$specializationDetails['count'];
                }
                array_push($specializationNameHash[$specializationName], $specializationDetails);
            }
        }
        $specializationNormalState=$this->getSpecializationToNormalState($specializationNameHash, $totalCountPerSpecializationNameHash);
        return $specializationNormalState;
     }
    private function getSpecializationToNormalState($specializationHash,$totalCountPerSpecializationNameHash){
        $modifiedSpecializationList=array();
        foreach ($specializationHash as $specializationName=>$specializationList){
            if(count($specializationList)>1){
                foreach ($specializationList as $specializationDetails){
                    $specializationId=$specializationDetails['specId'];
                    $specializationCount=$totalCountPerSpecializationNameHash[$specializationName];
                    $specializationDetails['count']=$specializationCount;
                    $specializationDetails['repeat']=1;
                    $modifiedSpecializationList[$specializationId]=$specializationDetails;
                }
            }else{
                $specializationDetails=$specializationList[0];
                $specializationId=$specializationDetails['specId'];
                $specializationDetails['repeat']=0;
                $modifiedSpecializationList[$specializationId]=$specializationDetails;
            }
        }
        return $modifiedSpecializationList;
    }
    public function getFinalEntityFilter($parentEntityFilter,$orignalEntityFilter){
        foreach ($parentEntityFilter as $key=>$parentEntity){
            $parentEntityId=$parentEntity['id'];
            $parentEntityType=$parentEntity['type'];
            $originalEntityFound=0;
            foreach ($orignalEntityFilter as $originalEntity){
                $originalEntityId=$originalEntity['id'];
                $originalEntityType=$originalEntity['type'];
                if($originalEntityId==$parentEntityId && $parentEntityType==$originalEntityType){
                    $originalEntityFound=1;
                    $parentEntityFilter[$key]['count']=$originalEntity['count'];
                    $parentChildrenAcc=$parentEntity['children'];
                    $originalEntityAcc=$originalEntity['children'];
                    foreach ($parentChildrenAcc as $childKey=>$parentChildDetails){
                        $parentChildId=$parentChildDetails['id'];
                        $parentChildType=$parentChildDetails['type'];
                        $childFoundInChildren=0;
                        foreach ($originalEntityAcc as $orignalEntityChildrenDetails){
                            $originalEntityChildId=$orignalEntityChildrenDetails['id'];
                            $originalEntityChildType=$orignalEntityChildrenDetails['type'];
                            if($parentChildId==$originalEntityChildId && $parentChildType==$originalEntityChildType){
                                $parentEntityFilter[$key]['children'][$childKey]['count']=$orignalEntityChildrenDetails['count'];
                                $childFoundInChildren=1;
                            }
                        }
                        if(!$childFoundInChildren){
                            $parentEntityFilter[$key]['children'][$childKey]['disabled']=true;
                            $parentEntityFilter[$key]['children'][$childKey]['count']=0;
                        }
                    }
                }
            }
            if(!$originalEntityFound){
                 $parentChildrenAcc=$parentEntity['children'];
                 $parentEntityFilter[$key]['disabled']=true;
                 $parentEntityFilter[$key]['count']=0;
                  foreach ($parentChildrenAcc as $childKey=>$parentChildDetails){
                      $parentEntityFilter[$key]['children'][$childKey]['disabled']=true;
                      $parentEntityFilter[$key]['children'][$childKey]['count']=0;
                  }
            }
        }
        usort($parentEntityFilter, function($a, $b){
             return $b['count'] - $a['count'];
        });
        foreach ($parentEntityFilter as $key=>$parentEntityDetails){
             $childrenArray=$parentEntityDetails['children'];
             usort($childrenArray, function($a, $b){
             return $b['count'] - $a['count'];
             });
             $parentEntityFilter[$key]['children']=$childrenArray;
        }
        return $parentEntityFilter;
    }
    public function sortParentChildEntityFilter($parentEntityFilter){
        foreach ($parentEntityFilter as $key=>$parentDetails){
            $parentCount=$parentDetails['count'];
            $parentId=$parentDetails['id'];
            
        }
    }
   

    public function getFinalSpecFilter($parentSpecFilter,$childrenSpecFilter){
        $revisedFilter=$this->getFinalEntityFilter($parentSpecFilter,$childrenSpecFilter);
        return $revisedFilter;
    }

    public function getIdVsEntityData($entityData){
        $idVsSpecData = array();
        foreach($entityData as $entityItem){
            $idVsSpecData[$entityItem['id']]['type'] = $entityItem['type'];
            $idVsSpecData[$entityItem['id']]['value'] = $entityItem['heading'];
            if(count($entityItem['children']) <=1){
                continue;
            }
            foreach($entityItem['children'] as $entityChild){
                $idVsSpecData[$entityChild['id']]['type'] = $entityChild['type'];
                $idVsSpecData[$entityChild['id']]['value'] = $entityChild['heading'];
            }
        }
        return $idVsSpecData;
    }
    public function getFinalLocationFilter($parentLocFilter,$childrenLocFilter){
        $revisedFilter=$this->getFinalEntityFilter($parentLocFilter,$childrenLocFilter);
        return $revisedFilter;
    }

    public function syncUserSearchHistory($data){
        $model = $this->CI->load->model('SASearch/sasearchmodel');
        $model->updateExistingSearchHistory($data);
        $history = $model->getUserSearchHistory($data['userId']);
        $history = array_filter($history);
        if(isMobileRequest())
        {
            $history = array_slice($history, 0,6);
        }
        else
        {
            $history = array_slice($history, 0,8);
        }
        return $history;
    }

    public function completeSearchTrack($trackingKeyId,$updateData){
        $model = $this->CI->load->model('SASearch/sasearchmodel');
        $model->completeSearchTrack($trackingKeyId,$updateData);
    }

    public function prepareSearchLayerPrefillData($trackingKeyId){
        $model = $this->CI->load->model('SASearch/sasearchmodel');
        return $model->prepareSearchLayerPrefillData($trackingKeyId);
    }

    public function getContinentNames($continents){
        $model = $this->CI->load->model('SASearch/sasearchmodel');
        return $model->getContinentNames($continents);
    }

    public function trackSortClick($insertData){
        $model = $this->CI->load->model('SASearch/sasearchmodel');
        $model->trackSortClick($insertData);
    }

    public function trackFilterClick($insertData){
        $model = $this->CI->load->model('SASearch/sasearchmodel');
        $model->trackFilterClick($insertData);
    }
    /* function to format given (time in miliseconds) to date time format */
	public function formatMilliseconds($milliseconds) {
		$seconds = floor($milliseconds / 1000);
		$hours = floor($seconds / 3600);
		$seconds = $seconds % 60;
		return '0000-00-00 '.str_pad( $hours, 2, '0', STR_PAD_LEFT ).gmdate( ':i:s', $seconds );
	}
	/* function to clean ,prepare custom encode filter application data so that it can be tracked */
	public function prepareFilterApplicationData($params)
	{
        if(empty($params)){
            return false;
        }
        $this->_cleanForFilterTrack($params);
        $customParts = array();
        foreach($params as $key => $value){
            if(empty($value) || $value == "deleted"){
                unset($params[$key]);
            }else{
                if(is_array($value)){
                    $sub = implode(",", $value);
                }else{
                    $sub = $value;
                }
                $customParts[] = $key." : [".$sub."]";
            }
        }
        if(empty($params)){
            return false;
        }else{
            $customString = implode(" | ", $customParts);
            return "{ ".$customString." }";
        }
	}
    private function _cleanForFilterTrack(&$params)
    {
        unset($params['q']);
        unset($params['qer']);
        unset($params['ref']);
        unset($params['tid']);
        unset($params['os']);
        unset($params['fc']);
    }
    public function fetchIfUserHasShortListedCourses($validity){
        $data = array();
        if (! (($validity == "false") || ($validity == ""))) {
            $data ['userId'] = $validity [0] ['userid'];
        } 
        $shortlistListingLib = $this->CI->load->library ( 'listing/ShortlistListingLib' );
        return $shortlistListingLib->fetchIfUserHasShortListedCourses ( $data);
    }
    public function getSearchResult(){    
		$this->CI->load->builder('SASearchBuilder','SASearch');
		$searchBuilder    = new SASearchBuilder();
		$searchPageData   = $searchBuilder->getSearchPage();
        return $searchPageData;
    }

    public function getSearchStaticPage($request){    
        $this->CI->load->builder('SASearchBuilder','SASearch');
        $searchBuilder    = new SASearchBuilder($request);
        $searchPageData   = $searchBuilder->getSearchPage();
        return $searchPageData;
    }

    public function getSortParamText($sortParam,$pageData){
        if($sortParam == '' || $sortParam=='populairity_desc')
        {
            $value = '';
        }else{
            $value = 'applied';
            if(count($pageData['appliedFilter']['exams'])>0)
            {
                $sortParamArr = explode('_', $sortParam);
                if(strtoupper($sortParamArr[2]) != strtoupper($pageData['appliedFilter']['exams'][0]))
                {
                    $value = '';
                }
            }
        }

        return $value;
    }
    public function prepareTrackingData(&$displayData)   
    {    
        $displayData['beaconTrackData'] = array(
            'pageIdentifier' => $displayData['pageType'],
            'pageEntityId' => '0',
            'extraData' => null
        );
    }
    public function getSeoDetails(& $displayData,$xssCleanedSearchKeyWord) {             
        $displayData['pageType'] = 'searchPage';
        $displayData['canonicalURL'] = getCurrentPageURLWithoutQueryParams();
        if(isset($displayData['staticSearchUrl']) && $displayData['staticSearchUrl'] == 1){
            $this->_getSEODetailsForStaticSearchPage($displayData);
        }elseif ($xssCleanedSearchKeyWord == '') {
            $this->_getSEODetailsForSearchHomePage($displayData);
        } else {
            $this->_getSEODetailsForSearchPageWithResults($displayData,$xssCleanedSearchKeyWord);
        }

        $this->saSearchLayerLib = $this->CI->load->library('SASearch/SASearchLayerLib');
        $url = SHIKSHA_STUDYABROAD_HOME.$_SERVER['REQUEST_URI'];
        $this->saSearchLayerLib->validatePaginatedSearchPageUrl($url,$displayData['pageData']['totalResultCount'],$displayData['pageData']['currentPageNum']);
       
        $relLinks = $this->saSearchLayerLib->getPrevNextPageLinksForSearchPage($url,$displayData['pageData']['totalResultCount'],$displayData['pageData']['currentPageNum']);
        if($relLinks['relNext']!=''){
            $displayData['relNext'] = $relLinks['relNext'];
        }
        if($relLinks['relPrev']!=''){
            $displayData['relPrev'] = $relLinks['relPrev'];
        }
    }

    private function _getSEODetailsForStaticSearchPage(&$displayData){
        if($displayData['numberOfResults'] > 1){
            $universityStr = 'Universities';
        }else{
            $universityStr = 'University';
        }
        $displayData['seoTitle']    = 'Universities in '.$displayData['locationName'].' ('.$displayData['countryName'].') - Courses, Rank, Fees, Eligibility, Exams, Admission Process';
        $displayData['metaTitle']   = 'Universities in '.$displayData['locationName'].' ('.$displayData['countryName'].') - Courses, Rank, Fees, Eligibility, Exams, Admission Process';
        $displayData['metaDescription'] = 'Check '.$displayData['numberOfResults'].' '.$universityStr.' in '.$displayData['locationName'].' ('.$displayData['countryName'].') along with fees, eligibility, rank, admission process, exams, scholarship and accommodation details and more at studyabroad.shiksha.com';
        $displayData['customSEORobotsString']="INDEX, FOLLOW";
    }

    private function _getSEODetailsForSearchPageWithResults(&$displayData,$xssCleanedSearchKeyWord){
        $displayData['seoTitle'] 	= 'Search Results for '."'".$xssCleanedSearchKeyWord."'".' - Study Abroad';
		$displayData['metaTitle'] 	= 'Search Results for '."'".$xssCleanedSearchKeyWord."'".' - Study Abroad';
		$displayData['metaDescription'] = $displayData['numberOfResults'].' results found for '."'".$xssCleanedSearchKeyWord."'".'. Perform Search by program, college, university, location, exam, and more parameters';
		$displayData['customSEORobotsString']="NOINDEX, FOLLOW";
    }
    private function _getSEODetailsForSearchHomePage(&$displayData){
    	$displayData['seoTitle'] 	= 'Search for Study Abroad Programs, Colleges, and Exams';
		$displayData['metaTitle'] 	= 'Search for Study Abroad Programs, Colleges, and Exams';
		$displayData['metaDescription'] = 'Find study abroad programs in more than 50 countries around the world. Perform Search by program, college, university, location, exam, and more parameters';
		$displayData['robots']          = 'All';
    }
    public function cleanseNonQerData($data){
        unset($data['keyword']);
        unset($data['searchType']);
        unset($data['requestFrom']);
        unset($data['courseFee']);
        unset($data['examScore']);
        $data = array_filter($data);
        return $data;
    }
    public function mineSearchEntity($qerResponse){
        $type = array();
        if(!empty($qerResponse['universities'])){
            $type[] ='university';
        }
        if(!empty($qerResponse['categoryIds']) || !empty($qerResponse['subCategoryIds']) || !empty($qerResponse['desiredCourse']) || !empty($qerResponse['specializationIds'])){
            $type[] = 'course';
        }
        if(!empty($qerResponse['city']) || !empty($qerResponse['country']) || !empty($qerResponse['state']) || !empty($qerResponse['continent'])){
            $type[] = 'location';
        }
        if(!empty($qerResponse['exams'])){
            $type[] = 'exam';
        }
        $str = implode('-', $type);
        if(empty($str)){
            $str = 'others';
        }
        return $str;
    }
    public function getAdvancedFilterSelection($data,$entity){
        $selection = array();
        if(strpos($entity, 'university') !== false){
            $courseLevel = $data['level'][0]['name'];
            if(!empty($courseLevel)){
                $selection['courseLevel'] = $courseLevel;
            }
            $stream = $data['categoryIds'];
            if(!empty($stream)){
                $selection['stream'] = $stream;
            }    
        }else if(strpos($entity, 'course') !== false){
            $examScores = $data['examScore'];
            if(!empty($examScores)){
                $selection['examScores'] = $examScores;
            }
            $courseFees = $data['courseFee'];
            if(!empty($courseFees)){
                $selection['courseFees'] = $courseFees;
            }
            $exam = $data['exams'];
            if(!empty($exam)){
                $selection['exam'] = $exam;
            }
        }
        return $selection;
    }

    public function extractRequestData($requestData,&$data)
    {
        $data['keyword']        = $requestData['keyword'];
        $data['city']           = $requestData['city'];
        $data['state'] 	        = $requestData['state'];
        $data['country']        = $requestData['country'];
        $data['continent']      = $requestData['continent'];
        $data['universities']   = $requestData['universities'];
        $data['institute']      = $requestData['institute'];
        $data['exams']          = $requestData['exams'];
        $data['categoryIds']    = $requestData['categories'];
        $data['subCategoryIds'] = $requestData['subcategories'];
        $data['level']          = $requestData['level'];
        $data['desiredCourse']  = $requestData['course'];
        $data['specialization'] = $requestData['specialization']; // qer's case where spcialization name & category id are returned for specialization
        $data['specializationIds'] = $requestData['specializationIds']; // autosuggestor's case where only spcialization Id is returned for specialization
        $data['requestFrom']    = $requestData['requestFrom'];
        $data['courseFee']    = $requestData['courseFee'];
        $data['examScore']    = $requestData['examScore'];
        $data['searchType']   = $requestData['searchType'];
        if($requestData['textSearchFlag']==true){
            $data['textSearchFlag']     = $requestData['textSearchFlag'];
            $data['remainingKeyword']   = $requestData['remainingKeyword']; 
        }
        $data['historyTracking']=$requestData['historyTracking'];
    }

    public function trackQERSearchData($data,$locationText,$additionalData){
        $trackingData = array();
        $trackingData['mainSearchBoxText'] = $data['keyword'];
        $trackingData['locationSearchBoxText'] = $locationText;
        $trackingData['sourceApplication'] = (isMobileRequest()?'mobile':'desktop');
        $trackingData['searchResultType'] = 'course';
        $trackingData['searchTime'] = date('Y-m-d H:i:s');
        $validity = $additionalData['validity'];
        if($validity == 'false'){
            $trackingData['userId'] = '-1';
        }else{
            $trackingData['userId'] = $validity[0]['userid'];
        }
        $trackingData['sourcePage'] = $additionalData['fromPage'];
        $trackingData['totalResults'] = '-1';
        $trackingData['searchPageUrl'] = '';
        $trackingData['sessionId'] = sessionId();
        $trackingData['visitorSessionId'] = getVisitorSessionId();
        $trackingData['trackingKeyId'] = $additionalData['trackingKeyMap'][$trackingData['sourcePage']];
        $trackingData['searchType'] = $data['searchType'];
        if(isset($data['historyTracking'])){
            $trackingData['historyTracking'] = $data['historyTracking'];
        }
        else
        {
            $trackingData['historyTracking'] = 0;
        }
        $qerResponse = $this->cleanseNonQerData($data);
        $trackingData['qerResponse'] = json_encode($qerResponse);
        $trackingData['searchEntity'] = $this->mineSearchEntity($qerResponse);
        if(in_array($data['searchType'], array('closedSearch','mainBoxClosedSearch'))){
            $trackingData['advancedFilterSelection'] = json_encode($this->getAdvancedFilterSelection($data,$trackingData['searchEntity']));
        }else{
            $trackingData['advancedFilterSelection'] = '[]';
        }
        $id = $this->trackSearchData($trackingData);
        return $id;
    }
    public function checkIfOpenSearchIsAFullEntity($postData){
        $openSearchData = array();
        if($postData['searchType'] == 'openSearch'){
            if((strtolower(trim($postData['keyword'])) == strtolower($postData['exams'][0]['name']) && count($postData['exams']) == 1) ||
                (strtolower(trim($postData['keyword'])) == strtolower($postData['universities'][0]['name']) && count($postData['universities']) == 1)){
                if(!empty($postData['exams'][0]['name'])){
                    $openSearchData['landingPage'] = 'exam';
                    $openSearchData['name'] = $postData['exams'][0]['name'];
                    $openSearchData['id'] = $postData['exams'][0]['id'];
                }
                if(!empty($postData['universities'][0]['name'])){
                    $openSearchData['landingPage'] = 'university';
                    $openSearchData['name'] = $postData['universities'][0]['name'];
                    $openSearchData['id'] = $postData['universities'][0]['id'];
                }
            }
        }
        return $openSearchData;
    }

    public function getURLForOpenSearchWhichIsAFullEntity($openSearchData){
        $closeSearchUrl = '';
        switch ($openSearchData['landingPage']) {
            case 'exam':
                $saContentLib = $this->CI->load->library('blogs/saContentLib');
                $examUrls = $saContentLib->getSAExamHomePageURLByExamNames(array($openSearchData['name']));
                $closeSearchUrl = SHIKSHA_STUDYABROAD_HOME.$examUrls[strtoupper($openSearchData['name'])]['contentURL'];
                break;

            case 'university':
                $this->CI->load->builder('ListingBuilder','listing');
                $listingBuilder = new ListingBuilder;
                $abroadUniversityRepository = $listingBuilder->getUniversityRepository();
                $universityObj = $abroadUniversityRepository->find($openSearchData['id']);
                $closeSearchUrl = $universityObj->getUrl();
                break;
        }
        return $closeSearchUrl;
    }

    public function detectEntitiesForFilterPresentation(&$displayData)
    {
        $qerFilters = $displayData['request']->getQERFilters();
        // as per SA-3543, we need to determine presence of entities so that some logic can be applied for presentation
        // 1. detection of mba - for workex
        if(in_array(1508,$qerFilters['desiredCourse']) || 
            (in_array(239,$qerFilters['categoryIds']) && strpos(strtolower($qerFilters['level']),'masters')!==false)
        )
        {
            $displayData['showWorkXP'] = true;
        }
        // 2. detection of master/bachelor level - for relevant cutoff
        if(in_array(1510,$qerFilters['desiredCourse']) ||
            (!is_array($qerFilters['level']) && strpos(strtolower($qerFilters['level']),'bachelors')!==false) ||
            (is_array($qerFilters['level']) && in_array('bachelors',array_map(strtolower,$qerFilters['level']))!== false)
        )
        {
            $displayData['show12thCutoff'] = true;
        }else{
            $displayData['showBachCutoff'] = true;
        }
    }
    public function unmapClickSourceAndId($clickSource){
        switch($clickSource){
            case 'img':
                return array('clickSource'=>'image','listingType'=>'university');
                break;
            case 'utitle':
                return array('clickSource'=>'univName','listingType'=>'university');
                break;
            case 'ctitle':
                return array('clickSource'=>'firstCourseName','listingType'=>'course');
                break;
            case 'rtitle':
                return array('clickSource'=>'firstCourseRank','listingType'=>'ranking');
                break;
            case 'rutitle':
                return array('clickSource'=>'universityRankLink','listingType'=>'university');
                break;    
            case 'cdbut':
                return array('clickSource'=>'courseDetailButton','listingType'=>'course');
                break;
            case 'udbut':
                return array('clickSource'=>'universityDetailButton','listingType'=>'university');
                break;    
            case 'sclink':
                return array('clickSource'=>'similarCourseName','listingType'=>'course');
                break;
            case 'srlink':
                return array('clickSource'=>'similarCourseRank','listingType'=>'ranking');
                break;
            case 'scdbut':
                return array('clickSource'=>'similarCourseDetailButton','listingType'=>'course');
                break;
            case 'popCTitle':
                return array('clickSource'=>'popularCourseDetailLink','listingType'=>'course');
                break;  
            case 'ebbut':
                return array('clickSource'=>'emailBrochureButton','listingType'=>'course');
                break;
            case 'rmcbut':
                return array('clickSource'=>'rateMyChanceButton','listingType'=>'course');
                break;
            case 'save':
                return array('clickSource'=>'saveCourse','listingType'=>'course');
                break;
            case 'compare':
                return array('clickSource'=>'compareCourse','listingType'=>'course');
                break;
            case 'cinuniv':
                return array('clickSource'=>'courseInUniv','listingType'=>'course');
                break;
            case 'univbroch':
                return array('clickSource'=>'universityBrochureButton','listingType'=>'university');
                break;
        }
    } //end : fn

    public function checkToServeStaticSearchUrl($data){
        //check the following fields are empty
        $serveStaticSearchUrl = true;
        $fieldConfig = array('institute','universities','exams','categoryIds','subCategoryIds','level','desiredCourse','specialization','specializationIds');

        foreach ($fieldConfig as $key => $field) {
            if(!empty($data[$field])){
                $serveStaticSearchUrl = false;
                break;
            }
        }
       
        return $serveStaticSearchUrl;
    }

    public function addUnivAnnouncementInfo($univData)
        {
            $this->CI->load->builder('ListingBuilder','listing');
            $listingBuilder = new ListingBuilder;
            $abroadUnivRepo = $listingBuilder->getUniversityRepository();
            
            foreach ($univData as $key => $value) {
                if(is_int($key)){
                 $univFields = $abroadUnivRepo->findMultipleFieldsByUniversityId($key,array('announcement'));
                 if(!empty($univFields['announcement'])){
                $announcementObj=  json_decode($univFields['announcement']);
                
                 $announcementArr[$key]['announcement']['text']=$announcementObj->announcementText;
                 $announcementArr[$key]['announcement']['startdate']=$announcementObj->announcementStartDate;
                 $announcementArr[$key]['announcement']['enddate']=$announcementObj->announcementEndDate;
                 $announcementArr[$key]['announcement']['actiontext']=$announcementObj->announcementActionText;
                }
            }
            }
           // _p($univData);
            return $announcementArr;
        }
}
