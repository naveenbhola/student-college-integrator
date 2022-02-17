<?php

class abroadSearchV2 extends MX_Controller{
    
    private $xssCleanedSearchKeyWord=null;
    private $qerResultString;
    private $fromPage;
    private $searchPageLib;
    private $qerExecutionLoggingData;
    private $debugging;
    private $trackingKeyMap = array(
        'homePage' => 1689,
        'homepage_searchBox' => 1687,
        'countryHomePage' => 1691,
        'universityPage' => 1693,
        'allCoursePage' => 2045,
        'guidePage' => 1695,
        'categoryPage' => 1697,
        'articlePage' => 1699,
        'savedCoursesPage' => 1701,
        'savedCoursesTab' => 1703,       // This page doesn't actually exist, but the key has been made anyway.
        'courseRankingPage' => 1705,
        'universityRankingPage' => 1707,
        'examPage' => 1709,
        'searchPage' => 1711,
        'coursePage' => 1713,
        'departmentPage' => 1715,        // This page doesn't actually exist, but the key has been make anyway.
        'MMP' => 1717,                   // This page doesn't actually exist, but the key has been make anyway.
        'countryPage' => 1719,
        'applyContentPage' => 1721,
        'compareCoursesPage' => 1723,
        'rankingPage' => 1725,            // This page doesn't actually exist, but the key has been make anyway.
        'applyHomePage' => 1727,
        'examContentPage'=>1731,
        'allcountryHome'=>1733,
        'counselorPage'=>1735,
        'scholarshipDetailPage'=>1737,
        'scholarshipCategoryPage'=>1739,
        'scholarshipHomepage'=>1741,
        'userProfilePage'=>1743,
        'shikshaCounselingReviewPage' =>2119,
        'rmcChildPage'=>2937,
        'saveCourseChildPage'=>2939,
        'counselorChildPage' =>3199,
        'documentChildPage' =>3201,
        'expertProfilePage' =>3709
    );

    private $staticSearchUrl = false;

    public function __construct() {
         parent::__construct();
         define('SA_SEARCH_PAGE_LIMIT', 20);
         if($this->input->post('q')!=''){
             $q = base64_decode($this->input->post('q'));
         }
         if($q==''){
             $q = $this->security->xss_clean($_REQUEST['q']);
         }
         
         $this->xssCleanedSearchKeyWord =  $this->security->xss_clean($q);
         $this->qerResultString         =  $this->security->xss_clean($this->input->get('qer'));// is QER processed
         $this->filterUpdateCall        =  $this->security->xss_clean($this->input->post('filterUpdateCall'));
         $this->filterAjaxCall          =  $this->security->xss_clean($this->input->post('filterAjaxCall'));
         $this->filterWithData          =  $this->security->xss_clean($this->input->post('filterWithData'));
         $this->load->library('SASearch/SearchQER/AbroadQERLib');
         $this->load->library('common/StudyAbroadLoggingLib');
         $this->saSearchLayerLib        =  $this->load->library('SASearch/SASearchLayerLib');
         $this->fromPage                =  $this->input->post('ref');
         $this->searchPageLibV2         =  $this->load->library('searchPage/SearchPageLibV2');
         $this->debugging               =  $this->security->xss_clean($this->input->post("enableDebugging"));
         $this->debuggingViaURL         =  $this->security->xss_clean($this->input->get("enableDebugging"));
         $this->optimize                =  $this->security->xss_clean($this->input->get('optimize'));
         $this->validity                =  $this->checkUserValidation();
         $currentUrl                    =  trim(getCurrentPageURLWithoutQueryParams(), ' /');
         if(!$this->input->is_ajax_request() && empty($q)){
            $this->staticSearchUrl      = true;
         }else{
             if(!$this->input->is_ajax_request() && $currentUrl != SHIKSHA_STUDYABROAD_HOME.'/search-abroad'){
                redirect(SHIKSHA_STUDYABROAD_HOME.$_SERVER['REQUEST_URI'], 'location');
             }            
         }

    }
    
    public function index(){
        $displayData = array();        
        // serve static search url
        if(isset($this->staticSearchUrl) && $this->staticSearchUrl){        
            $searchModel                    = $this->load->model('SASearch/sasearchmodel'); 
            $uri                            = $_SERVER["SCRIPT_URL"];
            $data                           = $searchModel->getSearchStaticParamsByUrl(trim($uri));            
            if(!empty($data)){
                $setData                        = array();
                foreach ($data as $locLabel     => $locVal) {
                    $setData[$locLabel]             = array($locVal);
                }
                $keywordinurl                   = substr($uri, strrpos($uri, '/') + 1);
                $formatKeyword                  = str_replace("-", " ", $keywordinurl);
                $setData['searchKeyword']       = $formatKeyword;
                $setData['pageNumber']          = $this->input->get('pn');
                
                $this->load->library('SASearch/SASearchPageRequest');
                $request                        = new SASearchPageRequest();            
                $request->setData($setData);              
                $displayData                    = $this->getDataRequiredToRenderSearchResultPage($request);

                //fetch cityname and country name
                $this->load->builder('LocationBuilder','location');
                $locationBuilder = new LocationBuilder;
                $locationRepository = $locationBuilder->getLocationRepository();
                if(!empty($data['city'])){
                    $locObj = $locationRepository->findCity($data['city']);
                    
                }
                if(!empty($data['state'])){
                    $locObj = $locationRepository->findState($data['state']);    
                }

                if(!empty($locObj)){
                    $countryId = $locObj->getCountryId();
                    $displayData['locationName'] = $locObj->getName();
                    if($countryId>0){
                        $countryObj = $locationRepository->findCountry($countryId);    
                        $displayData['countryName'] = $countryObj->getName();                   
                    }
                }             
            }

            $displayData['staticSearchUrl'] = true;
            
        }
        // when you already have query processed by QER, this says "yes"
        elseif(isset($this->qerResultString) && $this->qerResultString != '') { 
            $displayData=$this->getDataRequiredToRenderSearchResultPage();
        }
        // yet to process using QER (when user clicks search, case of ajax)
        elseif (isset($this->xssCleanedSearchKeyWord) && $this->xssCleanedSearchKeyWord != '') { 
            $abroadQERLibraryObect = new AbroadQerLib();
            // get the search string that is to be passed to qer
            $finalSearchString = $this->saSearchLayerLib->getFinalSearchStringParameters($this->xssCleanedSearchKeyWord);
            // full location string for tracking
            $locSearchString = $this->security->xss_clean($this->input->post('lq'));
            // autosuggested location
            $locSearchStringClosed = $this->security->xss_clean($this->input->post('lqClosed'));
            // first we look in synonyms mapping if we can get an exact match so that we can skip QER altogether
            $byPassQerResult = $this->saSearchLayerLib->checkToByPassQer($finalSearchString);
            // if there is still something left ...
            if($byPassQerResult['qerRequired']==true){
                $finalSearchString = $byPassQerResult['remainingString'];
                // QER at work
                $qerResultWithEntities = $abroadQERLibraryObect->parseQERResultToGetEntities($finalSearchString,$this->debugging);
                // merge autosuggested locations here
                $this->saSearchLayerLib->mergeAutosuggestedLocationWithQERResult($qerResultWithEntities, $locSearchStringClosed);
                if($qerResultWithEntities == -1)
                {
                    $qerResultWithEntities =array();
                    $qerOutage = 1;
                }
                if($this->debugging == 1)
                {
                    $this->qerExecutionLoggingData = $qerResultWithEntities['qerExecutionLogArr'];
                    $qerResultWithEntities = $qerResultWithEntities['qerSanatizedResult'];
                }
                if(count($byPassQerResult['entities'])>0){
                    $qerResultWithEntities = array_merge_recursive($qerResultWithEntities,$byPassQerResult['entities']);
                }
            }else{
                $qerResultWithEntities = $byPassQerResult['entities'];
                // merge autosuggested locations here
                $this->saSearchLayerLib->mergeAutosuggestedLocationWithQERResult($qerResultWithEntities, $locSearchStringClosed);
            } 
            $isAjaxCall = $this->input->post('ajaxCall');
            // if entities were derived from QER
            if(!empty($qerResultWithEntities)) {
                $qerResultWithEntities['keyword'] = $this->xssCleanedSearchKeyWord;
                $qerResultWithEntities['searchType'] = $this->input->post('searchType');
                $qerResultWithEntities['historyTracking']=  intval($this->input->post('historyTracking'));
                $qerResultWithEntities['locationText'] = $locSearchString;
                // check if exactly exam is search
                $examPageUrl = $this->saSearchLayerLib->checkIfOnlyExamIsSearched($qerResultWithEntities,$isAjaxCall);
                $displayData['qerResults'] = $qerResultWithEntities;
                if($isAjaxCall){ // ajax call to create SRP url also lands here
                    $url = $this->createClosedSearchUrl($qerResultWithEntities, false);
                }else{
                    $url = $this->createClosedSearchUrl($qerResultWithEntities, true);
                }

                if ($url != '') {
                    redirect($url);
                }
            }else{ // nothing derived from QER
                $closedSearchUrlDataArray = array('remainingKeyword' => $finalSearchString, 'textSearchFlag' => true);
                $closedSearchUrlDataArray['keyword'] = $this->xssCleanedSearchKeyWord;
                $closedSearchUrlDataArray['searchType'] = $this->input->post('searchType');
                $closedSearchUrlDataArray['historyTracking']=  intval($this->input->post('historyTracking'));
                $closedSearchUrlDataArray['locationText'] = $locSearchString;
                if($qerOutage ==1){
                    $closedSearchUrlDataArray['qerOutage'] = $qerOutage;
                }
                if($isAjaxCall){
                    // no redirect, url is returned
                    $url = $this->createClosedSearchUrl($closedSearchUrlDataArray, false);
                }else{
                    $url = $this->createClosedSearchUrl($closedSearchUrlDataArray, true);
                }
                if ($url != '') {
                    redirect($url);
                }
            }
        }
        // _p($displayData);die;
        $displayData['numberOfResults']=$displayData['pageData']['totalResultCount'];
        //set $displayData['numberOfResults'] contains number of results which is used for seo generation , 
        //please use this key or change the key used in function for displaying correct seo information 
        $isPaginatedPage = $this->input->post('ispagination');
        if(!$isPaginatedPage){
            $this->searchPageLibV2->getSeoDetails($displayData,$this->xssCleanedSearchKeyWord);
            $this->searchPageLibV2->prepareTrackingData($displayData);
        }
        $displayData['trackForPages']   = true; //For JSB9 Tracking
        $displayData['isPaginatedPage'] = $isPaginatedPage;
        $displayData['boomr_pageid']    = "SA_SEARCHPAGE";
        
        $trackingKeyId                  = $this->_completeSearchTrack($displayData['numberOfResults'],$displayData['searchTupleType']);
        if(!empty($trackingKeyId)){
            $displayData['trackingKeyId']   = $trackingKeyId;            
        }
        
        $this->performLoggingForQER($trackingKeyId);
        StudyAbroadLoggingLib::downloadLogFile();    
        $displayData['searchLayerPrefillData'] = $this->_prepareSearchLayerPrefillData($trackingKeyId);
        if($displayData['pageData']['totalResultCount'] == 0){
            if($this->_checkIfZRPOpenSearch($displayData)){
                $displayData['zrpByOpenSearch'] = true;
            }else{
                $displayData['zrpByFilter'] = true;
            }
        }
        $displayData['validateuser'] = $this->validity;
        if($displayData['searchTupleType'] == 'course')
        {
            $displayData['rateMyChanceCtlr'] = Modules::load('rateMyChances/rateMyChances');
            if($displayData['validateuser'] != "false"){
                $shikshaApplyLib = $this->load->library('rateMyChances/ShikshaApplyCommonLib');
                $displayData['userRmcCourses'] = $shikshaApplyLib->getUserRmcRatings($displayData['validateuser'][0]['userid']);
            }else{
                $displayData['userRmcCourses'] = array();
            }
            $this->compareCourseLib = $this->load->library('studyAbroadCommon/compareCoursesLib');
            $displayData['userComparedCourses'] =  $this->compareCourseLib->getUserComparedCourses();
            $this->createCTAsForSlider($displayData);
        }
        $this->saSearchLayerLib->createPaginationData($displayData);
        $this->load->view('SASearch/searchOverviewV2',$displayData);
    }
    private function createCTAsForSlider(&$displayData){
        $univData = $displayData['univData'];
        $shortlistListingLib = $this->load->library ( 'listing/ShortlistListingLib' );
        $data = array();
        $data ['userId'] = $displayData['validateuser'][0]['userid'];
        $shortlistedCourse = $shortlistListingLib->fetchIfUserHasShortListedCourses ($data);
        $displayData['shortListedCourses'] = $shortlistedCourse;
        unset($univData['courseSliderData']);
        foreach ($univData as $univId => $coursesData) {
            if(count($coursesData) == 1){
                continue;
            }
            foreach ($coursesData as $courseId => $courseData) {
                $brochureDataObj = array(
                    'sourcePage' => 'abroadSearch',
                    'courseId' => $courseData['a'],
                    'courseName' => $courseData['i'],
                    'universityId' => $univId,
                    'universityName' => $courseData['d'],
    //            'userStartTimePrefWithExamsTaken' => $userStartTimePrefWithExamsTaken,
                    'destinationCountryId' => $displayData['brochureDataArray'][$univId]['destinationCountryId'],
                    'destinationCountryName' => $courseData['e'],
    //            'courseData'       				  => base64_encode(json_encode($courseData)), // send data now required for new single registration form
    //            'userDesiredCourse'				  => $userStartTimePrefWithExamsTaken['desiredCourse'],
                    'widget' => 'search',
                    'trackingPageKeyId' => 33
                );
                $rmcObject = $brochureDataObj;
                $rmcObject['trackingPageKeyId'] = 361;
                $rmcObject['sourcePage'] = 'searchPage';
                $rmcObject['widget'] =  'tuple';
                $rmcObject['userRmcCourses'] = $displayData['userRmcCourses'];
                $rmcObject['class'] = 'tl';
                $rmcObject['loc'] = 'rmcbut';
                $rmcView = $displayData['rateMyChanceCtlr']->loadRateMyChanceButton($rmcObject, 'abroadSearchV2');
                $displayData['univData']['courseSliderData'][$univId][$courseId]['brochureObj'] = $brochureDataObj;
                $displayData['univData']['courseSliderData'][$univId][$courseId]['rmcView'] = $rmcView;
                $displayData['univData']['courseSliderData'][$univId][$courseId]['iSC'] = (in_array($courseData['a'], $shortlistedCourse['courseIds']) == true) ? true : false;
                $displayData['univData']['courseSliderData'][$univId][$courseId]['cDCS'] = (in_array($courseData['a'],$displayData['userComparedCourses']) == true) ? 'checked="checked"':'';
            }
        }
    }
    public function getDataRequiredToRenderSearchResultPage($request = null){
            $validity = $this->validity;
            $displayData=array();
            if(isset($this->staticSearchUrl) && $this->staticSearchUrl){                           
                $searchPageData=$this->searchPageLibV2->getSearchStaticPage($request);                
            }else{
                $searchPageData=$this->searchPageLibV2->getSearchResult();
            }
            $univData                = $searchPageData->getUnivData();
            $displayData['pageData'] = $searchPageData->getPageData();
            $displayData['solrOutage'] = $displayData['pageData']['solrOutage'];
            $displayData['qerOutage'] = $this->security->xss_clean($this->input->get('qo'));
            $displayData['filters']=$searchPageData->getFilters();
            $displayData['searchTupleType'] = $displayData['pageData']['searchPageType'];
            $displayData['filtersParent']=$searchPageData->getFilterParent();
            $univCount = count($univData);
            //_p($univData);die;
            if($univCount>0){
                if($displayData['searchTupleType'] == 'university'){
                   $displayData['univData'] = $this->saSearchLayerLib->formatUnivDataForUniversityTuple($univData,true);
                }else{
                    $displayData['univData'] = $this->saSearchLayerLib->formatUnivDataForCourseTuple($univData, true);
                }
            }

            $this->load->builder('ListingBuilder','listing');
            $listingBuilder             = new ListingBuilder;
            // $abroadCourseRepository     = $listingBuilder->getAbroadCourseRepository();

            if($univCount > 0)
            {
                $abroadUnivRepository     = $listingBuilder->getUniversityRepository();
                $displayData['universityRelatedData']  = $abroadUnivRepository->findMultiple(array_keys($univData));
                $brochureDataArray = array();
                if(is_array($displayData['universityRelatedData']))
                {
                    foreach ($displayData['universityRelatedData'] as $univId => $univObj)
                    {
                        if(!(isset($displayData['universityRelatedData']) && ($displayData['universityRelatedData'][$univId] instanceof University)))
                        {
                            continue;
                        }
                        $brochureDataArray[$univId] = array(
                            'sourcePage'       => 'abroadSearch',
                            'universityId'     => $univObj->getId(),
                            'universityName'   => $univObj->getName(),
                            'destinationCountryId'	=> $univObj->getLocation()->getCountry()->getId(),
                            'destinationCountryName'=> $univObj->getLocation()->getCountry()->getName(),
                        );
                    }
                }
                $displayData['brochureDataArray'] = $brochureDataArray;
            }
            if($this->filterUpdateCall ==1)
            {
                $displayData['filterUpdateCall'] = $this->filterUpdateCall;
            }
            $displayData['request'] = $searchPageData->getRequest();
            $displayData['sortParam']=$searchPageData->getSortParam();
            $displayData['sortParamText'] = $this->searchPageLibV2->getSortParamText($displayData['sortParam'],$displayData['pageData']);
            $displayData['searchedTerm']  = $this->xssCleanedSearchKeyWord;
            $displayData['filterAjaxCall'] = $this->filterAjaxCall;
            if($displayData['searchTupleType'] == 'course')
            {
               /* $courseIdsArr = array();
                foreach($univData as $univId => $courseData){
                    $courseIdsArr = array_merge(array_keys($courseData),$courseIdsArr);
                }
                $courseObj  = $abroadCourseRepository->findMultiple($courseIdsArr);
                $displayData['courseObj'] = $courseObj;
                unset($courseObj);*/

                $displayData['filterWithData'] = $this->filterWithData;
                $displayData['userShortlistedCourses'] = $this->searchPageLibV2->fetchIfUserHasShortListedCourses($validity);
                $childrenLocationFilter= $this->searchPageLibV2->prepareAccordianDataForLocation($displayData,false);
                $parentLocationFilter= $this->searchPageLibV2->prepareAccordianDataForLocation($displayData,true);
                $displayData['locationFilter']=  $this->searchPageLibV2->getFinalLocationFilter($parentLocationFilter,$childrenLocationFilter);
                $displayData['idLocationData'] = $this->searchPageLibV2->getIdVsEntityData($displayData['locationFilter']);
                $childrenSpecFilter=$this->searchPageLibV2->prepareDataForSpecFilter($displayData,false);
                $parentSpecFilter=$this->searchPageLibV2->prepareDataForSpecFilter($displayData,true);
                $displayData['specFilter']=$this->searchPageLibV2->getFinalSpecFilter($parentSpecFilter,$childrenSpecFilter);
                $displayData['idSpecData'] = $this->searchPageLibV2->getIdVsEntityData($displayData['specFilter']);
                $this->searchPageLibV2->detectEntitiesForFilterPresentation($displayData);
                $this->load->config("SASearch/SASearchPageConfig");
                $displayData['maxFeesValue']=  $this->config->item('SA_SEARCH_FEES_UPPER_VALUE');
                $displayData['maxWexValue']=  $this->config->item('SA_SEARCH_WORK_EXPERIENCE_UPPER_VALUE');
                $displayData['searchFieldParams']= $this->config->item('SA_SEARCH_PARAMS_FIELDS_ALIAS');
            }
            //var_dump($this->filterUpdateCall);
           return $displayData;
        }


    public function createClosedSearchUrl($requestData=array(),$redirect=true){
        
        $data               = array();
        $this->searchPageLibV2->extractRequestData($requestData,$data);
        $locationText       = $requestData['locationText'];
        $additionalData     = array('validity'=>$this->validity,'fromPage'=>$this->fromPage,'trackingKeyMap'=>$this->trackingKeyMap);
        $data['trackingId'] = $this->searchPageLibV2->trackQERSearchData($data,$locationText,$additionalData);
        if($this->debugging == 1)
        {
            $this->qerExecutionLoggingData['searchTrackingSAId'] = $data['trackingId'];
            // log QER execution data if required
            $this->_trackQERExecutionData();
        }

        $closeSearchUrl = '';

        $openSearchData = $this->searchPageLibV2->checkIfOpenSearchIsAFullEntity($data);
        if(!empty($openSearchData)){
            $closeSearchUrl = $this->searchPageLibV2->getURLForOpenSearchWhichIsAFullEntity($openSearchData);
        }
        if(empty($closeSearchUrl)){
            $toServeStaticSearch = $this->searchPageLibV2->checkToServeStaticSearchUrl($data);
            if($toServeStaticSearch){
                $searchModel = $this->load->model('SASearch/sasearchmodel'); 
                if(empty($data['city']) && count($data['state']) == 1){
                    $closeSearchUrl = $searchModel->getSearchStaticUrl($data['state'][0]['id'],'state');
                }else if(empty($data['state']) && count($data['city']) == 1){
                    $closeSearchUrl = $searchModel->getSearchStaticUrl($data['city'][0]['id'],'city');
                }
                $trackingId = $data['trackingId'];
                if(!empty($closeSearchUrl) && $trackingId){
                    $closeSearchUrl.="?tid=".$trackingId;
                }                
            }
        }

        if(empty($closeSearchUrl)){
            $this->load->library("SASearch/SASearchPageUrlGenerator");
            $urlGenerator      = new SASearchPageUrlGenerator();
            $closeSearchUrl = $urlGenerator->createClosedSearchUrl($data);            
            if($this->fromPage){
                $closeSearchUrl.="&ref=".$this->fromPage;
            }
            if($requestData['qerOutage'] ==1)
            {
                $closeSearchUrl.="&qo=1";
            }
        }

        if($redirect==true){
            return $closeSearchUrl;
        }else{
            echo json_encode(array('url'=>$closeSearchUrl));
            exit;
        }
    }
    
	/*
	 *  check & get parameters for an autosuggestion based search
	 */
	public function checkIfAutosuggestedSearch()
	{
		$isAutosuggested = $this->input->post('autosuggested');
		$searchParams = array();
		$searchParams['course'] = $this->input->post('course');
		$searchParams['universities'] = $this->input->post('universityId');
		$searchParams['subcategories'] = $this->input->post('subcategories');
		$searchParams['categories'] = $this->input->post('categories');
		$searchParams['level'] = $this->input->post('level');
		if($searchParams['level'][0]['name'] == "Bachelors")
		{
			$searchParams['level'][] = array('name'=>"Bachelors Diploma");
			$searchParams['level'][] = array('name'=>"Bachelors Certificate");
		}
		else if($searchParams['level'][0]['name'] == "Masters")
		{
			$searchParams['level'][] = array('name'=>"Masters Diploma");
			$searchParams['level'][] = array('name'=>"Masters Certificate");
		}
		$searchParams['exams'] = $this->input->post('exams');
		$searchParams['examScore'] = $this->input->post('examScore');
		$searchParams['courseFee'] = $this->input->post('courseFee');
		$searchParams['specializationIds'] = $this->input->post('specialization');
		$searchParams['country'] = $this->input->post('country');
		$searchParams['city'] = $this->input->post('city');
		$searchParams['state'] = $this->input->post('state');
		$searchParams['continent'] = $this->input->post('continent');
        $searchParams['historyTracking']=intval($this->input->post('historyTracking'));
        $qerStatus = $this->_getQERResponseForLocation($searchParams);
        $searchParams['locationText'] = $this->security->xss_clean($this->input->post('lq'));
		$searchParams['keyword'] = $this->xssCleanedSearchKeyWord;
        $searchParams['searchType'] = $this->security->xss_clean($this->input->post('searchType'));
        $this->fromPage = $this->security->xss_clean($this->input->post('ref'));
		//_p($searchParams);
		if($isAutosuggested)
		{
			if($this->input->post('ajaxCall')){
                $url = $this->createClosedSearchUrl($searchParams, false);
                if($qerStatus == -1)
                {
                    $url .= "&qo=1";
                }
				echo $url;
            }
		}
	}
    /*
     * this function get qer response for location typed in the location search box
     * Note: it works in case of autosuggested search, excludes locations selected via autosugg
     */
    private function _getQERResponseForLocation(&$searchParams)
    {
        $locSearchString = $this->security->xss_clean($this->input->post('locSearch'));
        $qerResponse = array();
        if($locSearchString != "")
        {
            $abroadQERLibraryObect = new AbroadQerLib();
            $qerResultWithEntities = $abroadQERLibraryObect->parseQERResultToGetEntities($locSearchString);
            if($qerResultWithEntities == -1)
            {
                $qerResultWithEntities = array();
                // QER outage
                return -1;
            }
            $locKeys = array('city', 'state', 'country', 'continent');
            foreach($locKeys as $location)
            {
                if(count($qerResultWithEntities[$location])>0){
                    $qerResponse[$location] = array_map(function($a){return $a['id'];},$qerResultWithEntities[$location]);
                    if(!is_array($searchParams[$location]))
                    {
                        //$searchParams[$location] = $qerResponse[$location];
                        foreach($qerResponse[$location] as $loc)
                        {
                            $searchParams[$location][] = array('id'=>$loc);
                        }
                    }
                    else{
                        array_map(function($a)use(&$searchParams, $location){
                                if(!in_array($a,array_map(function($b){return $b['id'];},$searchParams[$location])))
                                {
                                    $searchParams[$location][]=array('id'=>$a);
                                }
                            },$qerResponse[$location]);
                    }
                }
            }
        }
        //_p($qerResponse);
    }

    private function _completeSearchTrack($resultCount,$searchTupleType){
        $trackingKeyId = (int)$this->security->xss_clean($this->input->get('tid'));
        if($trackingKeyId <= 0){
            return ;
        }
        $url = SHIKSHA_STUDYABROAD_HOME.$_SERVER['REQUEST_URI'];
        $updateData = array('totalResults' => $resultCount,'searchResultType'=> $searchTupleType,'searchPageUrl' => $url);
        $this->searchPageLibV2->completeSearchTrack($trackingKeyId,$updateData);
        return $trackingKeyId;
    }

    /* ajax lands here */
    public function syncUserSearchHistory(){
        $userDetails = $this->validity;
        if($userDetails == "false"){
            return true;    //Nothing to do here, moving on.
        }
        $userId = $userDetails[0]['userid'];
        $sessionId = sessionId();
        $data = array('userId' => $userId, 'sessionId'=>$sessionId);
        $history = $this->searchPageLibV2->syncUserSearchHistory($data);
        echo json_encode($history);
    }
    /* ajax lands here */
    public function xssCleanTextByAjax(){
        $text = $this->input->post('text');
        if(empty($text)){
            echo 'false';
        }
        $htmlEntityFlag = $this->input->post('htmlEntityFlag');
        if(isset($htmlEntityFlag) && $htmlEntityFlag == 1)
        {
            echo htmlentities($this->security->xss_clean($text));
        }
        else
        {
            echo $this->security->xss_clean($text);
        }
    }
    /* ajax lands here */
    public function addEntityToSearchTracking(){
        $data = json_decode($this->input->post('data'),true);
        $fromPage = $this->security->xss_clean($data['fromPage']);
        $validateUser = $this->validity;
        $userId = ($validateUser == "false")?'-1':$validateUser[0]['userid'];
        $trackingData = array(
            'mainSearchBoxText' => $data['searchStr'],
            'locationSearchBoxText' => $data['locationStr'],
            'advancedFilterSelection' => '[]',
            'sourceApplication' => 'desktop',
            'searchTime' => date('Y-m-d H:i:s'),
            'userId' => $userId,
            'sourcePage' => $fromPage,
            'qerResponse' => '[]',
            'totalResults' => NULL,
            'searchType' => empty($data['searchType'])?'closedSearch':$data['searchType'],
            'searchPageUrl' => $data['url'],
            'sessionId' => sessionId(),
            'visitorSessionId' => getVisitorSessionId(),
            'trackingKeyId' => $this->trackingKeyMap[$fromPage]
        );
        if(isset($data['historyTracking'])){
            $trackingData['historyTracking']=intval($data['historyTracking']);
        }
        else
        {
            $trackingData['historyTracking']=0;
        }
        if($data['type'] == "university"){
            $trackingData['searchEntity'] = 'university';
            $trackingData['searchResultType'] = 'universityListingPage';
            $this->searchPageLibV2->trackSearchData($trackingData);
        }elseif ($data['type'] == "exam") {
            $trackingData['searchEntity'] = 'exam';
            $trackingData['searchResultType'] = 'examPage';
            $this->searchPageLibV2->trackSearchData($trackingData);
        }else{
            // Don't track!
        }
    }
    /*
     get data required to prefill location for university selection
    */
    private function _getUnivPrefillData(&$data, $universityId)
    {
        // univId check
        if($universityId>0){
            $this->load->library('SASearch/AutoSuggestorSolrClient');
            $this->autoSuggestorSolrClient = new AutoSuggestorSolrClient;
            $data['autoSelectedMain'] = $this->autoSuggestorSolrClient->getSelectedUniversityDetails($universityId);
            $data['autoSelectedMain']['id'] = $universityId;
            $data['autoSelectedMain']['type'] = 'university';
        }else{
            return;
        }
    }
    /*
     get data required to prefill course selection
    */
    private function _getCoursePrefillData(&$data)
    {
        // univId check
        if($data['mainSearchBoxText'] != ''){
            $this->load->library('SASearch/AutoSuggestorSolrClient');
            $this->autoSuggestorSolrClient = new AutoSuggestorSolrClient;
            //var_dump($data['mainSearchBoxText']);
            $requestData = array('text'=>$data['mainSearchBoxText'],'eachfacetResultCount'=>1);
            $response = $this->autoSuggestorSolrClient->getCourseAndUnivSuggestionsFromSolr($requestData);
            $response = reset($response['courseSuggestions']);
            $data['courseAdvOptionData'] = $this->autoSuggestorSolrClient->getSelectedCourseDetails($response);//,$locationData);
            // use switch to explode $response's first key's data & set as autoselectedmain
            foreach(array_keys($response) as $key){
                $respArr = explode(':',$response[$key]);
                switch($key){
                    case 'saAutosuggestCourseLevelSpecializationNameIdMap':
                        $data['autoSelectedMain'] = array('courseLevel'=>$respArr[0],'specializationId'=>$respArr[2]);
                        break;
                    case 'saAutosuggestCourseLevelSubcategoryNameIdMap':
                        $data['autoSelectedMain'] = array('courseLevel'=>$respArr[0],'subcategoryId'=>$respArr[2]);
                        break;
                    case 'saAutosuggestCourseLevelCategoryNameIdMap':
                        $data['autoSelectedMain'] = array('courseLevel'=>$respArr[0],'categoryId'=>$respArr[2]);
                        break;
                    case 'saAutosuggestDesiredCourseSpecializationNameIdMap':
                        $data['autoSelectedMain'] = array('desiredCourse'=>$respArr[0],'specializationId'=>$respArr[2]);
                        break;
                    case 'saAutosuggestDesiredCourseSubcategoryNameIdMap':
                        $data['autoSelectedMain'] = array('desiredCourse'=>$respArr[0],'subcategoryId'=>$respArr[2]);
                        break;
                    case 'saAutosuggestDesiredCourseNameIdMap':
                        $data['autoSelectedMain'] = array('desiredCourse'=>$respArr[1]);
                        break;
                    default:break;
                }
            }
            //_p($data['autoSelectedMain']);
            //$data['autoSelectedMain']['id'] = $universityId;
            $data['autoSelectedMain']['type'] = 'course';
        }else{
            return;
        }
    }
    /* this is quite specific to mobile, not moving to desktop */
    private function _prepareSearchLayerPrefillData($trackingKeyId){
        $data = reset($this->searchPageLibV2->prepareSearchLayerPrefillData($trackingKeyId));
        $data['advancedFilterSelection'] = json_decode($data['advancedFilterSelection']);
        $countries = $this->input->get('co',true);
        $countries = array_filter($countries,function($ele){ if((integer)$ele <= 0){return false;} return true;});
        $states = $this->input->get('st',true);
        $states = array_filter($states,function($ele){ if((integer)$ele <= 0){return false;} return true;});
        $cities = $this->input->get('ct',true);
        $cities = array_filter($cities,function($ele){ if((integer)$ele <= 0){return false;} return true;});
        $continents = $this->input->get('cn',true);
        $continents = array_filter($continents,function($ele){ if((integer)$ele <= 0){return false;} return true;});
        $this->load->builder('LocationBuilder','location');
        $locationBuilder = new LocationBuilder;
        $locationRepository = $locationBuilder->getLocationRepository();
        if(!empty($cities)){
            $cityObjs = $locationRepository->findMultipleCities($cities);
        }
        if(!empty($states)){
            $stateObjs = $locationRepository->findMultipleStates($states);    
        }
        $countryObjs = array();
        foreach($countries as $country){
            $country = (int)$country;
            if($country>0){
                $countryObjs[] = $locationRepository->findCountry($country);
            }
        }
        $locs = array();
        if(!empty($continents)){
            $continentNames = $this->searchPageLibV2->getContinentNames($continents);
            foreach($continentNames as $continentId=>$continentName)
            {
                $locs[] = array('locId'=>$continentId,
                'locName'=>$continentName,
                'locType'=>'continent');
            }
        }
        foreach($cityObjs as $city){
            $locs[] = array('locId'=>$city->getId(),
            'locName'=>$city->getName(),
            'locType'=>'city');
            
        }
        foreach($stateObjs as $state){
            $locs[] = array('locId'=>$state->getId(),
            'locName'=>$state->getName(),
            'locType'=>'state');
        }
        foreach($countryObjs as $country){
            $locs[] = array('locId'=>$country->getId(),
            'locName'=>$country->getName(),
            'locType'=>'country');
        }
        $data['locations'] = array_unique($locs,SORT_REGULAR);
        
        $universityId = $this->input->get('u',true);
        $this->_getUnivPrefillData($data, reset($universityId));
        if(is_null($data['autoSelectedMain'])){
            $this->_getCoursePrefillData($data);
        }
        return $data;
    }

    /* ajax call lands here */
    public function trackSortClick(){
        $trackingKeyId = (integer)$this->input->post('trackingKeyId', true);
        if($trackingKeyId <= 0){
            return;
        }
        if($this->validity == "false"){
            $userId = -1;
        }else{
            $userId = $this->validity[0]['userid'];
        }
        $sortedBy = $this->input->post('sortedBy', true);
        $insertData = array(
            'sessionId' => sessionId(),
            'visitorSessionId' => getVisitorSessionId(),
            'searchTrackingSAId' => $trackingKeyId,
            'userId' => $userId,
            'sortingAppliedAt' => date('Y-m-d H:i:s'),
            'sortedBy' => $sortedBy
        );
        $this->searchPageLibV2->trackSortClick($insertData);
    }
    /*
     * function to track qer execution data & save it to the db, so that it can be added later to logging lib's excel
     */
    private function _trackQERExecutionData()
    {
        if($this->debugging == 1)
        {
            StudyAbroadLoggingLib::insertQERExecutionData($this->qerExecutionLoggingData);
        }
        return false;
    }

    public function performLoggingForQER ($trackingKeyId)
    {
        $searchLoggingObject=  StudyAbroadLoggingLib::logExecutionDataForQER($trackingKeyId,$this->validity);
    }

    /* ajax call lands here */
    public function trackFilterClick(){
        $params = $this->input->post('data', true);
        if(!is_array($params)){
            return false;
        }
        $customEncode = $this->searchPageLibV2->prepareFilterApplicationData($params);
        if($customEncode === false){
            return false;
        }
        $trackingId = (integer)$this->input->post('trackingId', true);
        $userId = ($this->validity == "false")?'-1':$this->validity[0]['userid'];
        $insertData = array(
            'userId' => $userId,
            'sessionId' => sessionId(),
            'visitorSessionId' => getVisitorSessionId(),
            'searchTrackingSAId' => $trackingId,
            'filterAppliedAt' => date('Y-m-d H:i:s'),
            'filterData' => $customEncode
        );
        $this->searchPageLibV2->trackFilterClick($insertData);
    }

    public function _checkIfZRPOpenSearch($data){    // If no filters exist, basically
        $f = $this->input->get('es');
        if(!empty($f)){
            return false;
        }
        $f = $this->input->get('ex');
        if(!empty($f)){
            return false;
        }
        $f = $this->input->get('cf');
        if(!empty($f)){
            return false;
        }
        $f = $this->input->get('fc');
        if(!empty($f) && (integer)$f > 0){
            return false;
        }
        return true;
    }
}
