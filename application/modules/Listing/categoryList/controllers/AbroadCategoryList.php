<?php
/**
 * Controller for Study abroad category pages.
 */


class AbroadCategoryList extends MX_Controller
{
    /**
     * Class data member declaration section
     */
    private $abroadCategoryPageLib;
    private $abroadCommonLib;
    private $categoryRepository;
    private $courses;

    /**
     * Purpose       : Initialization method for study abroad category pages
     * Params        : 1. Reference of display data array
     * To Do         : none
     * Author        : Romil Goel
     */
    function _init(& $displayData)
    {

        $this->abroadCategoryPageLib  = $this->load->library('categoryList/AbroadCategoryPageLib');
        $this->abroadCommonLib 	= $this->load->library('listingPosting/AbroadCommonLib');
        $this->abroadListingCommonLib   =   $this->load->library('listing/AbroadListingCommonLib');
        $this->abroadListingCacheLib 	= $this->load->library('listing/cache/AbroadListingCache');

        //Loading LocationBuilder for getting locationRepository
        $this->load->builder('LocationBuilder','location');
        $locationBuilder = new LocationBuilder;
        $this->locationRepository = $locationBuilder->getLocationRepository();

        //Loading CategoryBuilder for getting categoryRepository
        $this->load->builder('CategoryBuilder','categoryList');
        $categoryBuilder = new CategoryBuilder;
        $this->categoryRepository = $categoryBuilder->getCategoryRepository();

        //Fetch User Login info
        $displayData['validateuser'] = $this->checkUserValidation ();
        // get user data via write handle if user was redirected upon registration
        $flagForWriteHandle = ($this->input->get('source') == 'Registration' && $this->input->get('newUser') == 1);
        if($displayData['validateuser'] !== 'false') {
            $this->load->model('user/usermodel');
            $usermodel = new usermodel;

            $userId 	= $displayData['validateuser'][0]['userid'];
            $user 	= $usermodel->getUserById($userId, $flagForWriteHandle);

            if(!is_object($user))
            {
                $displayData['loggedInUserData'] = false;
            }
            else
            {
                $name = $user->getFirstName().' '.$user->getLastName();
                $email = $user->getEmail();
                $userFlags = $user->getFlags();
                $isLoggedInLDBUser = $userFlags->getIsLDBUser();
                $displayData['loggedInUserData'] = array('userId' => $userId, 'name' => $name, 'email' => $email, 'isLDBUser' => $isLoggedInLDBUser);

                $pref = $user->getPreference();
                $loc = $user->getLocationPreferences();
                $isLocation = count($loc);
                if(is_object($pref)){
                    $desiredCourse = $pref->getDesiredCourse();
                }else{
                    $desiredCourse = null;
                }
                $displayData['loggedInUserData']['desiredCourse'] = $desiredCourse;
                $displayData['loggedInUserData']['isLocation'] = $isLocation;
            }

        }
        else {
            $displayData['loggedInUserData'] = false;
        }
    }



    /**
     * Purpose       : Main method for study abroad category pages
     * Params        : none
     * To Do         : none
     * Author        : Romil Goel, SA Team
     */
    public function categoryPage(CategoryPageBuilder $categoryPageBuilder)
    {
        //global $indexTime;
        //$conTime = microtime()-$indexTime;
        //error_log( "SRB time taken: index to category Controller = ".$conTime);
        // initialize
        //$this->benchmark->mark("GIN1START");
        $this->_init($displayData);
        //$this->benchmark->mark("GIN1END");
        //error_log( "init displayData time taken:= ".$this->benchmark->elapsed_time('GIN1START', 'GIN1END'));

        //$this->benchmark->mark("GIN2START");
        $request = $categoryPageBuilder->getRequest();
        if($request->useSolrToBuildCategoryPage() && $request->isSolrFilterAjaxCall())
        {
            $this->categoryPageFilters($categoryPageBuilder, $request);
            return ;
        }
        $this->redirectIfObsoleteCatPage($request);
        $this->checkAndClearCookieIfReferralNotOfSamePage($request);

        // exam name in case of exam accepting category pages
        $displayData['acceptedExamName'] = $request->getAcceptedExamName();
        $displayData['acceptedExamId'] = $request->getAcceptedExamId();

        // get filter value of passed as url parameters ...
        $filterWithValidQueryStringFromUrlParams = $this->abroadCategoryPageLib->processUrlParametersToGetFilters($request);
        //$this->benchmark->mark("GIN2END");
        //error_log( "get request & filters from query params time taken:= ".$this->benchmark->elapsed_time('GIN2START', 'GIN2END'));

        //$this->benchmark->mark("GIN3START");
        // ... set into the request obj
        $request->setData(
            array(
                'urlParametersForAppliedFilters'=> $filterWithValidQueryStringFromUrlParams['filtersFromUrlParams'],
                'queryStringFromUrlParameters'  => $filterWithValidQueryStringFromUrlParams['validQueryString']
            )
        );

        //prepare url string for exam accepted page
        if($displayData['acceptedExamName'] != '')
        {   // page url string (not the canonical) for accepting exam page is a bit different from regular category page
            $displayData['urlStringForExamAcceptedPage'] = $request->getUrlStringForExamAcceptedPage();
        }

        //prepare seo data
        $seoData                        = $request->getSeoInfo($request->getPageNumberForPagination());

        if($request->isExamCategoryPage())
        {
            $displayData['canonicalUrl'] = $request->getCanonicalUrl($request->getPageNumberForPagination());
            $displayData['h1Title'] = $seoData['h1Title'];
            $displayData['ajaxurl'] = $request->getURL();
        }
        else{
            $displayData['canonicalUrl'] 	= $request->getCanonicalUrl($request->getPageNumberForPagination());
            $displayData['ajaxurl'] 		= $request->getURL();
        }

        $displayData['pageUrl'] 		= $seoData['url'];
        $displayData['title'] 			= $seoData['title'];
        $displayData['metaDescription'] = $seoData['description'];
        //$this->benchmark->mark("GIN3END");
        //error_log( "SEO data time taken:= ".$this->benchmark->elapsed_time('GIN3START', 'GIN3END'));

        //redirect if invalid params
        //$this->benchmark->mark("GIN4START");
        $catPageRequestValidateLib 		= $this->load->library('AbroadCategoryPageRequestValidations');
        $catPageRequestValidateLib->redirectIfInvalidRequestParamsExist($request, $displayData, $this->categoryRepository);
        $displayData['isAjaxCall'] 		= $request->isAJAXCall();
        //params for sorting
        $displayData['isSortAJAXCall']  = $request->isSortAJAXCall();
        $sortingCriteria 		        = $request->getSortingCriteria();

        if(!empty($sortingCriteria))
        {
            $displayData['sortingCriteria']['sortBy'] = $sortingCriteria['sortBy'];
            $displayData['sortingCriteria']['order'] = $sortingCriteria['params']['order'];
        }

        //User Shortlisted Courses
        $displayData['userShortlistedCourseIds'] = $this->fetchIfUserHasShortListedCourses();

        //populate countries
        $this->_populateAbroadCountries($displayData);
        //$this->benchmark->mark("GIN4END");
        //error_log( "Validation & redirection time taken:= ".$this->benchmark->elapsed_time('GIN4START', 'GIN4END'));

        //$this->benchmark->mark("GIN5START");
        //populate data for layer
        $displayData['desiredCourses'] = $this->abroadCommonLib->getAbroadMainLDBCourses();
        usort($displayData['desiredCourses'],function ($a,$b){
           return strcmp($a['CourseName'],$b['CourseName']);
        });
        $displayData['abroadCategories'] = $this->abroadCommonLib->getAbroadCategories();
        $displayData['levelOfStudy'] = $this->abroadCommonLib->getAbroadCourseLevelsForFindCollegeWidgets();
        $requestClone = clone($request);
        $displayData['recommendedCountryData'] = $this->abroadListingCommonLib->getRecommendedCountryData($requestClone);

//        foreach($displayData['desiredCourses'] as $key=>$course)
//        {
//            $desiredCourseId = $course['SpecializationId'];
//            $displayData['desiredCourses'][$key]['CourseFullName'] = $GLOBALS["studyAbroadPopularCourses"][$desiredCourseId];
//        }
        $categPage                             = $categoryPageBuilder->getCategoryPage();
        $categoryPageRequest                    = $categPage->getRequest();
        $displayData['categoryPageRequest'] 	= $categoryPageRequest;
        $displayData['categorypageKey'] 	    = $categoryPageRequest->getPageKey();
        $displayData['isAllCountryPage']	    = $categoryPageRequest->isAllCountryPage() ? 1 : 0;

        $displayData["showGutterHelpTime"] = 10000;
        if($categoryPageRequest->isAllCountryPage())
        {
            $displayData["showGutterHelpText"] = 1;
            $displayData["showGutterHelpTime"] = 5000;
        }
        else if($_COOKIE['SACategoryPageFirstTimeVisitor'] == "")
        {
            $displayData["showGutterHelpText"] = 1;
            $displayData["showGutterHelpTime"] = 10000;
            setcookie("SACategoryPageFirstTimeVisitor", 0, time()+60*60*24*30, "/", COOKIEDOMAIN);
        }

        //data for layer from request (to pre-fill the layer)
        $this->_populateDataToPreFillLayer($displayData);
        //$this->benchmark->mark('end_con');
        //error_log( "SRB time taken: controller landing = ".$this->benchmark->elapsed_time('start_con', 'end_con'));
        //$this->benchmark->mark("GIN5END");
        //error_log( "data for course country layer time taken:= ".$this->benchmark->elapsed_time('GIN5START', 'GIN5END'));
        //Resultant universities
        //$this->benchmark->mark("GIN6START");
        $totalUniversities 				            = $categPage->getUniversities();
        //$this->benchmark->mark("GIN6END");
        //error_log( "getUniversities called in categorypagesolr class time taken:= ".$this->benchmark->elapsed_time('GIN6START', 'GIN6END'));

        //$this->benchmark->mark("GIN7START");
        $universities 					            = $totalUniversities['universities'];
        // fat footer links
        if(!$displayData['isAjaxCall'] && !$displayData['isSortAJAXCall'])
        {
            $displayData['popularSubcats'] = $this->abroadCategoryPageLib->prepareFatFooterLinkData($request,$this,$totalUniversities['popularSubcats']);
        }
        $snapshotUniversities 				        = $totalUniversities['snapshotUniversities'];
        $displayData['snapshotOnlyPage'] 	        = $totalUniversities['snapshotOnly'];
        $displayData['showScholarshipFilter']       = $totalUniversities['showScholarship'];
        $displayData['snapshotUniversities']        = $snapshotUniversities;
        $displayData['totalTuplesOnPage']	        = $categPage->getNumTuples();
        $displayData['snapshotOnlyPostFilteration'] 	= $totalUniversities['snapshotOnlyPostFilteration'];
        $displayData['onlyCertDiplomaPage'] 		= $totalUniversities['onlyCertDiplomaPage'];

        /*compute counsellor Data
        $displayData['counsellorData']     = $this->getCounsellorDataForUniversities($universities,$snapshotUniversities);
        */

        //get all the filters data
        $filters = $categPage->getFilters();
        $displayData['filters'] = $filters;

        // get all filters applied which will be enabled after user has applied some filters
        $userAppliedfilters = $categPage->getUserAppliedFilters();

        // set all filters computed for individual section
        $displayData['userAppliedfilters'] 			          = $userAppliedfilters;
        //get the user selected filters
        $displayData['appliedFilters']                        = $request->getAppliedFilters();
        if(!$request->useSolrToBuildCategoryPage())
        {
            $displayData['userAppliedfiltersForSpecialization']	  = $categPage->getUserAppliedFiltersForSpecialization();
            $displayData['userAppliedfiltersForExam']		      = $categPage->getUserAppliedFiltersForExam();
            $displayData['userAppliedfiltersForExamsScore']       = $categPage->getUserAppliedFiltersForExamsScore();
            $displayData['userAppliedfiltersForExamsMinScore']	  = $categPage->getUserAppliedFiltersForExamsMinScore();
            $displayData['userAppliedfiltersForLocation']		  = $categPage->getUserAppliedFiltersForLocation();
            $displayData['userAppliedfiltersForMoreoption']		  = $categPage->getUserAppliedFiltersForMoreoption();
            $displayData['userAppliedfiltersForFees']		      = $categPage->getUserAppliedFiltersForFees();
            $this->formatFiltersForCategoryPage($displayData);
        }else{
            $this->formatFiltersForCategoryPageFromSolr($displayData);
        }
        //$this->benchmark->mark("GIN7END");
        //error_log( "format filters time taken:= ".$this->benchmark->elapsed_time('GIN7START', 'GIN7END'));

        //$this->benchmark->mark("GIN8START");
        $displayData['noOfUniversities'] 		= $categPage->getTotalNoOfUniversities();
        $displayData['noOfCourses']         	= $categPage->getTotalNoOfCourses();
        $displayData['breadcrumbData'] 			= $this->_getBreadcrumbs($displayData['categoryPageRequest']);
//        $displayData['catPageTitle']        	= $this->getCategoryPageTitle($displayData['categoryPageRequest']);
        $displayData['banner'] 				    = $categPage->getBanner();

        //Category and Scholarship slider title concept
        $filterData = $request->filterSelectionOrder;
        if(!empty($filterData))
        {
            $filterData = json_decode($filterData,true);
        }
        $filterCountryArray = array();
        if(isset($filterData) && isset($filterData['countryList']) && is_array($filterData['countryList']))
        {
            foreach ($filterData['countryList'] as $fCountryId => $fOrder)
            {
                $filterData['country'][] = intval($fCountryId);
            }
            $filterCountryArray  = $filterData['country'];
        }
        $catAndScholarTitle                     = $this->getCategoryPageTitle($request,
            NULL,NULL,true,$filterCountryArray);
        $displayData['catPageTitle'] =$catAndScholarTitle[0];
        $displayData['scholarshipSliderTitle'] = $catAndScholarTitle[1];
        // Card Fetch Concept
        $scholarshipParams = $this->abroadCategoryPageLib->prepareScholarShipCardApiParams($request,$filterCountryArray);
        if($scholarshipParams !== false)
        {
            $scholarshipCategoryPageLib = $this->load->library('scholarshipCategoryPage/scholarshipCategoryPageLib');
            $displayData['scholarshipCardData'] = $scholarshipCategoryPageLib->getScholarshipTupleDataForInterlinking($scholarshipParams,7);
//            _p( $displayData['scholarshipCardData']);die;
        }

        //appending college count in meta description of seo Data for MS and BE BTECH
        $displayData['metaDescription']         = $this->appendCollegeCountToMeta($request,$categPage->getTotalNoOfUniversities(),$seoData,$this->categoryRepository);

        // prepare data for showing static exam guides widget on category pages
        //$displayData['examPageWidgetData']      = $this->prepareExamGuideWidgetData($request, $this->abroadCategoryPageLib,$this->categoryRepository);

        //title for exam widget
        $displayData['examWidgetTitle']         = $this->getExamWidgetTitle($this->abroadCategoryPageLib,$displayData['categoryPageRequest'],$this->categoryRepository);

        $displayData['resultantUniversityObjects'] = $universities;
        $this->_getFilterSelectionOrderData($displayData);

        // $displayData['locationRepository'] = $this->locationRepository;
        // $displayData['categoryRepository'] = $this->categoryRepository;

        //Moving BMS Banner code here because now it depends on the selected filter values in Case of LDB courses
        $countriesForBMSInventory = $this->_getCountriesForBMSInventories($categoryPageBuilder, $request, $displayData['abroadCountries']);
        $displayData['criteriaArray'] = $this->_getCriteriaParams($countriesForBMSInventory, $request,$displayData);
        $displayData['gutterBannerProperties'] = array('pageId' => 'SA_GUTTER',
            'pageZone' =>    'RIGHT');

        $displayData['isZeroResultPage'] = false;
        if($displayData['noOfCourses'] == 0) {
            $displayData['isZeroResultPage'] = true;
        }
        $displayData['trackForPages'] = true;
        $trackingCountryId = $this->_getCountryByPriority($request->getCountryId(), $displayData['abroadCountries']);
        $displayData['googleRemarketingParams'] = array(
            "categoryId" => ($displayData['currentCategoryId'] == 1 ? 0 : $displayData['currentCategoryId']),
            "subcategoryId" => ($displayData['currentSubCategoryId'] == 1 ? 0 : $displayData['currentSubCategoryId']),
            "desiredCourseId" => ($displayData['currentLDBCourseId'] == 1 ? 0: $displayData['currentLDBCourseId']),
            "countryId" => $trackingCountryId,
            "cityId" => 0
        );
        //$this->benchmark->mark("GIN8END");
        //error_log( "other displayData time taken:= ".$this->benchmark->elapsed_time('GIN8START', 'GIN8END'));

        //$this->benchmark->mark("GIN9START");
        //this is to load rate my chance button on the category page tuples if the course is a shiksha apply course and mapped to a rmcCounsellor as well
        $displayData['rateMyChanceCtlr'] = Modules::load('rateMyChances/rateMyChances');
        if($displayData['validateuser'] != "false")
        {
            $shikshaApplyLib = $this->load->library('rateMyChances/ShikshaApplyCommonLib');
            $displayData['userRmcCourses'] = $shikshaApplyLib->getUserRmcRatings($displayData['validateuser'][0]['userid']);
        }else{
            $displayData['userRmcCourses'] = array();
        }
        $trackingCountryIds = $request->getCountryId();
        $this->compareCourseLib = $this->load->library('studyAbroadCommon/compareCoursesLib');
        $displayData['userComparedCourses'] = $this->compareCourseLib->getUserComparedCourses();
        $this-> _prepareTrackingData($displayData,'categoryPage',0,$trackingCountryIds,$request);
        $this->abroadCategoryPageLib->getPrevNextPageLinksForCategoryPage($displayData);
        $displayData['compareCookiePageTitle'] = str_replace("in All Countries","Abroad",$displayData['catPageTitle']);
        $displayData['compareOverlayTrackingKeyId'] = 589;
        $displayData['compareButtonTrackingId'] = 655;
        if($request->isExamCategoryPage()){
            $displayData['minScoreSelectFilled'] = false;
            $displayData['maxScoreSelectFilled'] = false;
        }
        //$this->benchmark->mark("GIN9END");
        //error_log( "user rmc courses time taken:= ".$this->benchmark->elapsed_time('GIN9START', 'GIN9END'));
        $displayData['loadImagesWithoutLazyCount'] = 2;
        $this->load->view('categoryList/abroad/categoryPageOverview', $displayData);
    }


    private function _prepareTrackingData(&$displayData,$pageIdentity,$entityId=0,$trackingCountryId=0,$request=0){
        if($pageIdentity == 'countryPage'){
            $displayData['googleRemarketingParams'] = array(
                "categoryId" => 0,
                "subcategoryId" => 0,
                "desiredCourseId" => 0,
                "countryId" => $trackingCountryId,
                "cityId" => 0
            );
        }else if($pageIdentity == 'categoryPage'){
            $displayData['googleRemarketingParams'] = array(
                "categoryId" => ($displayData['currentCategoryId'] == 1 ? 0 : $displayData['currentCategoryId']),
                "subcategoryId" => ($displayData['currentSubCategoryId'] == 1 ? 0 : $displayData['currentSubCategoryId']),
                "desiredCourseId" => ($displayData['currentLDBCourseId'] == 1 ? 0: $displayData['currentLDBCourseId']),
                "countryId" => $trackingCountryId,
                "cityId" => 0
            );
        }
        if($request != 0)
        {
            $countryId=$request->getCountryId();
            if($request->getCourseLevel() == "") {
                global $studyAbroadPopularCourseToLevelMapping;
                if(!is_null($studyAbroadPopularCourseToLevelMapping[$request->getLDBCourseId()])) {
                    $courseLevel = $studyAbroadPopularCourseToLevelMapping[$request->getLDBCourseId()];
                }
            }else{
                $courseLevel = ucfirst($request->getCourseLevel());
            }
        }


        $displayData['beaconTrackData'] = array(
            'pageIdentifier' => $pageIdentity,
            'pageEntityId' => $entityId,
            'extraData' => array(
                'categoryId' => $displayData['googleRemarketingParams']['categoryId'],
                'subCategoryId' => $displayData['googleRemarketingParams']['subcategoryId'],
                'LDBCourseId' => $displayData['googleRemarketingParams']['desiredCourseId'],
                'countryId' => $trackingCountryId,
                'courseLevel' => $courseLevel
            )
        );

        if($pageIdentity == 'countryPage' && $entityId == 1){
            $displayData['beaconTrackData']['extraData']['countryId'] =1;
        }elseif($pageIdentity == 'categoryPage' && $countryId[0] == 1){
            $displayData['beaconTrackData']['extraData']['countryId'] =1;
        }
    }
    private function _getCriteriaParams($countriesForBMSInventory, $categoryPageRequest,$displayData=array()) {
        foreach($countriesForBMSInventory as $cid) {
            $countryId = $cid;
            break;
        }

        if($categoryPageRequest->isLDBCoursePage()) {
            $categoryId = $this->_getBMSBannerCategoryIdforDesiredCourseCategoryPages($categoryPageRequest,$displayData);
            if($categoryId=='' || $categoryId < 0){
                $categoryId = $categoryPageRequest->getLDBCourseId();
            }

        } else if($categoryPageRequest->isLDBCourseSubCategoryPage()) {
            $subCatObj = $this->categoryRepository->find($categoryPageRequest->getSubCategoryId());
            $categoryId = $subCatObj->getparentId();
        } else if($categoryPageRequest->isCategorySubCategoryCourseLevelPage()) {
            $subCatObj = $this->categoryRepository->find($categoryPageRequest->getSubCategoryId());
            $categoryId = $subCatObj->getparentId();
        } else { // isCategoryCourseLevelPage
            $categoryId = $categoryPageRequest->getCategoryId();
        }

        // global $pageName;
        $pageName = $this->locationRepository->findCountry($countryId)->getName();
        $keyword = 'BMS_'.strtoupper(str_replace('-','_',str_replace(' ','_',$pageName)));
        if($keyword == "BMS_USA"){
            $keyword = "BMS_UNITED_STATES";
        }

        if($categoryPageRequest->getLDBCourseId() > 1) {
            $courseLevel = strtolower($categoryPageRequest->getCourseLevelForDesiredCourse($categoryPageRequest->getLDBCourseId()));
        } else {
            $courseLevel = strtolower($categoryPageRequest->getCourseLevel());
        }

        switch($courseLevel)
        {
            case 'bachelors': $keyword .= '_UG'; break;
            case 'masters': $keyword .= '_PG'; break;
            case 'phd': $keyword .= '_PHD'; break;
            case 'certificate - diploma': $keyword .= '_CERTIFICATE_DIPLOMA'; break;
        }

        $criteriaArray = array(
            'category' => $categoryId,
            'country' => $countries,
            'city' => $categoryPageRequest->getCityId(),
            'keyword'=>  $keyword
        );

        return $criteriaArray;
    }



    private function _getCriteriaParamsForCountry($countriesForBMSInventory) {

        foreach($countriesForBMSInventory as $cid) {
            $countryId = $cid;
            break;
        }
        if(!is_array($countriesForBMSInventory)){
            $countryId = $countriesForBMSInventory;
        }
        $pageName = $this->locationRepository->findCountry($countryId)->getName();

        $keyword = 'BMS_'.strtoupper(str_replace('-','_',str_replace(' ','_',$pageName)));
        if($keyword == "BMS_USA"){
            $keyword = "BMS_UNITED_STATES";
        }

        $criteriaArray = array(
            'category' => 1,
            'country' => $countryId,
            'city' => 1,
            'keyword'=>  $keyword
        );
        return $criteriaArray;
    }

    private function _getCountryByPriority($countryIdArray, $abroadCountriesObj) {
        if(count($countryIdArray) == 1 && $countryIdArray[0] == 1)
        {
            if($abroadCountriesObj[0]->getName() == "All" ) {
                unset($abroadCountriesObj[0]);
            }

            usort($abroadCountriesObj, function($a, $b)
            {
                return $a->getTrackingPriority() > $b->getTrackingPriority();
            });

            return $abroadCountriesObj[0]->getId();

        }
        else if(count($countryIdArray) > 1)
        {
            foreach($abroadCountriesObj as $countryObj) {
                if(in_array($countryObj->getId(), $countryIdArray)) {
                    $countryObjList[] = $countryObj;
                }
            }

            usort($countryObjList, function($a, $b)
            {
                return $a->getTrackingPriority() > $b->getTrackingPriority();
            });

            return $countryObjList[0]->getId();
        }
        else
        {
            return $countryIdArray[0];
        }
    }

    private function _getCountriesForBMSInventories($categoryPageBuilder, $request, $abroadCountryList){
        $countryArray = $request->getCountryId();
        if(count($countryArray) == 1 && $countryArray[0] == 1) { // All country case..
            foreach($abroadCountryList as $countryObj) {
                if($countryObj->getId() != 1) {
                    $countryArrayNew[] = $countryObj->getId();
                }
            }

            $countriesForBMSInventory = $categoryPageBuilder->getProcessedCountriesForBMSInventory($countryArrayNew);

        } else if(count($countryArray) > 1) {
            $countriesForBMSInventory = $categoryPageBuilder->getProcessedCountriesForBMSInventory($countryArray);
        } else {
            $countriesForBMSInventory = $countryArray;
        }
        return $countriesForBMSInventory;
    }

    private function _getBreadcrumbs($categoryPageRequest,$countryIdForCountryPage = 0) {

        $countryHomeLib	= $this->load->library('countryHome/CountryHomeLib');
        if($countryIdForCountryPage > 0)
        {	// breadcrumb for country page abroad
            $countryData = $this->locationRepository->getAbroadCountryByIds(array($countryIdForCountryPage));
            $countryName =  $countryData[$countryIdForCountryPage]->getName();
            $countryObject = reset($countryData);
            $breadcrumbData[] = array('title' => 'Home', 'url' => SHIKSHA_STUDYABROAD_HOME);

            // This section add new Country Home Page in breadcrumb
            if($countryObject->getName() == 'All'){
                $countryHomeUrl = $countryHomeLib->getAllCountryHomeUrl();
                $breadcrumbData[] = array('title' => 'All countries','url' => $countryHomeUrl);
            }else{
                $countryHomeUrl = $countryHomeLib->getCountryHomeUrl($countryObject);
                $breadcrumbData[] = array('title' => 'Study in '.$countryObject->getName(),'url' => $countryHomeUrl);
            }

            if($countryName == "All") {
                $countryName = 'abroad';
                $titleNew = 'Universities ';
            } else {
                $titleNew = 'Universities in ';
            }
            $breadcrumbData[] = array('title' => $titleNew.$countryName, 'url' => $countryPageUrl);
            return $breadcrumbData;
        }

        $countryIds = implode(",",$categoryPageRequest->getCountryId());
        $countryIdList 	= $categoryPageRequest->getCountryId();
        $countryData 	= $this->locationRepository->getAbroadCountryByIds($countryIdList);
        //$abroadcmsmodel = $this->load->model('listingPosting/abroadcmsmodel');
        //$countryData = $abroadcmsmodel->getAbroadCountries($countryIds);
        if(count($countryData) > 1) {
            $countryName = 'abroad';
        } else {
            $countryName =  $countryData[$countryIdList[0]]->getName();
        }

        if(count($categoryPageRequest->getCountryId()) == 1) {
            $countryPageUrl = $categoryPageRequest->getURLForCountryPage($countryIds);
            if($countryIds == 1) {
                $countryName ="abroad";
            }
        } else {
            $countryPageUrl = $categoryPageRequest->getURLForCountryPage(1);
            $countryName ="abroad";
        }

        $breadcrumbData[] = array('title' => 'Home', 'url' => SHIKSHA_STUDYABROAD_HOME);

        // This section add new Country Home Page in breadcrumb
        if($countryName == 'abroad'){
            $countryHomeUrl = $countryHomeLib->getAllCountryHomeUrl();
            $breadcrumbData[] = array('title' => 'All countries','url' => $countryHomeUrl);
        }else{
            $countryHomeUrl = $countryHomeLib->getCountryHomeUrl($countryData[$countryIdList[0]]);
            $breadcrumbData[] = array('title' => 'Study in '.$countryData[$countryIdList[0]]->getName(),'url' => $countryHomeUrl);
        }

        /*if($countryName == "abroad") {
            $titleNew = 'Universities ';
        } else {
            $titleNew = 'Universities in ';
        }
        $breadcrumbData[] = array('title' => $titleNew.$countryName, 'url' => $countryPageUrl);
        */
        if($categoryPageRequest->isLDBCoursePage())
        {
            $this->load->builder('LDBCourseBuilder','LDB');
            $LDBCourseBuilder = new LDBCourseBuilder;
            $ldbRepository = $LDBCourseBuilder->getLDBCourseRepository();
            $ldbCourse = $ldbRepository->find($categoryPageRequest->getLDBCourseId());
            $breadcrumbData[] = array('title' => $ldbCourse->getCourseName(), 'url' => '');
        }
        elseif($categoryPageRequest->isLDBCourseSubCategoryPage())
        {
            $this->load->builder('LDBCourseBuilder','LDB');
            $LDBCourseBuilder = new LDBCourseBuilder;
            $ldbRepository = $LDBCourseBuilder->getLDBCourseRepository();
            $ldbCourse = $ldbRepository->find($categoryPageRequest->getLDBCourseId());
            $subCategory = $this->categoryRepository->find($categoryPageRequest->getSubCategoryId());
            $request = clone $categoryPageRequest;
            $request->setData(array('subCategoryId'=>1));
            $breadcrumbData[] = array('title' => $ldbCourse->getCourseName(), 'url' => $request->getUrl());

            $breadcrumbData[] = array('title' => $subCategory->getName(), 'url' => '');
        }
        elseif($categoryPageRequest->isCategorySubCategoryCourseLevelPage())
        {
            $subCategory = $this->categoryRepository->find($categoryPageRequest->getSubCategoryId());
            $category = $this->categoryRepository->find($subCategory->getParentId());
            $request = clone $categoryPageRequest;
            $request->setData(array('subCategoryId'=>1, 'categoryId' => $category->getId()));

            if($request->getCourseLevel() == "phd" ) {
                $title = ucwords($request->getCourseLevel())." in ".$category->getName();
            } else {
                $title = ucwords($request->getCourseLevel())." of ".$category->getName();
            }

            $breadcrumbData[] = array('title' => $title, 'url' => $request->getUrl());

            $breadcrumbData[] = array('title' => $subCategory->getName(), 'url' => '');
        }
        elseif($categoryPageRequest->isCategoryCourseLevelPage())
        {
            $category = $this->categoryRepository->find($categoryPageRequest->getCategoryId());
            if($categoryPageRequest->getCourseLevel() == "phd" ) {
                $title = ucwords($categoryPageRequest->getCourseLevel())." in ".$category->getName();
            } else {
                $title = ucwords($categoryPageRequest->getCourseLevel())." of ".$category->getName();
            }

            $breadcrumbData[] = array('title' => $title, 'url' => '');
        }

        return $breadcrumbData;
    }

    public function getCategoryPageTitle($categoryPageRequest,$locationRepository = NULL,$categoryRepository = NULL,
                                         $scholarshipSliderFlag=false,$filterCountryArray=array())
    {
        $countryIdArray = $categoryPageRequest->getCountryId();
        $countryName = $this->_getCountryName($countryIdArray,$locationRepository);
        if(!empty($filterCountryArray))
        {
            $countryNameForScholarshipWidget = $this->_getCountryName($filterCountryArray,$locationRepository);
        }
        else
        {
            $countryNameForScholarshipWidget = $countryName;
        }

        if($categoryRepository != NULL && !$this->categoryRepository){
            $this->categoryRepository = $categoryRepository;
        }
        $scholarshipSliderTitle = '';
        if($categoryPageRequest->isLDBCoursePage())
        {
            $this->load->builder('LDBCourseBuilder','LDB');
            $LDBCourseBuilder = new LDBCourseBuilder;
            $ldbRepository = $LDBCourseBuilder->getLDBCourseRepository();

            $ldbCourse = $ldbRepository->find($categoryPageRequest->getLDBCourseId());
            $ldbCourseName = $ldbCourse->getCourseName();

            $title = $ldbCourseName." in ".$countryName;
            $scholarshipSliderTitle = $ldbCourseName." in ".$countryNameForScholarshipWidget;
            /*In case of BE/BTECH pages we have revised heading tags*/
            if($ldbCourseName!='' && $ldbCourseName=="BE/Btech")
            {
                $title = "Engineering Colleges in ".$countryName;
                $scholarshipSliderTitle = "Engineering in ".$countryNameForScholarshipWidget;
            }

        }
        elseif($categoryPageRequest->isLDBCourseSubCategoryPage())
        {
            $this->load->builder('LDBCourseBuilder','LDB');
            $LDBCourseBuilder = new LDBCourseBuilder;
            $ldbRepository = $LDBCourseBuilder->getLDBCourseRepository();

            $ldbCourse = $ldbRepository->find($categoryPageRequest->getLDBCourseId());
            $ldbCourseName = $ldbCourse->getCourseName();

            $subCategory = $this->categoryRepository->find($categoryPageRequest->getSubCategoryId());
            $subCategoryName = $subCategory->getName();

            $title = $ldbCourseName." in ".$subCategoryName." in ".$countryName;
            $scholarshipSliderTitle = $ldbCourseName." in ".$countryNameForScholarshipWidget;
            /*In case of BE/BTECH sub cat pages we have revised heading tags*/
            if($ldbCourseName!='' && $ldbCourseName=="BE/Btech")
            {
                $title = $this->setBTechCatPageTitle($subCategoryName,$categoryPageRequest->getSubCategoryId(),$countryName);
                $scholarshipSliderTitle = "Engineering in ".$countryNameForScholarshipWidget;
            }
        }
        elseif($categoryPageRequest->isCategoryCourseLevelPage())
        {
            $category = $this->categoryRepository->find($categoryPageRequest->getCategoryId());
            $categoryName = $category->getName();

            $courseLevel = ucwords($categoryPageRequest->getCourseLevel());

            if($courseLevel == "Phd") {
                $courseLevel = "PhD";
                $title = $courseLevel." in ".$categoryName." colleges in ".$countryName;
                $scholarshipSliderTitle = $courseLevel." in ".$categoryName." in ".$countryNameForScholarshipWidget;
            }elseif($courseLevel == "Certificate - Diploma") {
                $courseLevel = "Diploma";
                $title = $courseLevel." of ".$categoryName." colleges in ".$countryName;
                $scholarshipSliderTitle = $courseLevel." of ".$categoryName." in ".$countryNameForScholarshipWidget;
            }
            else {
                $title = $courseLevel." of ".$categoryName." colleges in ".$countryName;
                $scholarshipSliderTitle = $courseLevel." of ".$categoryName." in ".$countryNameForScholarshipWidget;
            }

        }
        elseif($categoryPageRequest->isCategorySubCategoryCourseLevelPage())
        {
            $subCategory = $this->categoryRepository->find($categoryPageRequest->getSubCategoryId());
            $subCategoryName = $subCategory->getName();

            $category = $this->categoryRepository->find($subCategory->getParentId());
            $categoryName = $category->getName();

            $courseLevel = ucwords($categoryPageRequest->getCourseLevel());

            if($courseLevel == "phd" ) {
                $courseLevel = "PhD";
                $title = $courseLevel." in ".$categoryName." in ".$subCategoryName." in ".$countryName;
                $scholarshipSliderTitle = $courseLevel." in ".$categoryName." in ".$countryNameForScholarshipWidget;
            }
            else {
                $title = $courseLevel." of ".$categoryName." in ".$subCategoryName." in ".$countryName;
                $scholarshipSliderTitle = $courseLevel." in ".$categoryName." in ".$countryNameForScholarshipWidget;
            }
        }
        if($scholarshipSliderFlag)
        {
            return array($title,$scholarshipSliderTitle);
        }
        else
        {
            return $title;
        }
    }

    private function _getCountryName($countryIdArray,$locationRepository=NULL)
    {
        if(sizeof($countryIdArray) == 1 && $countryIdArray[0] == 1)
        {
            $countryName = 'All Countries';
        }
        else
        {
            $countryName ='';
            if($locationRepository != NULL && !$this->locationRepository)
            {
                $this->locationRepository = $locationRepository;
            }
            $countryObjArray = $this->locationRepository->getAbroadCountryByIds($countryIdArray);

            foreach($countryObjArray as $countryObj)
            {
                $countryName = $countryName.", ".$countryObj->getName();
            }
            $countryName = ltrim($countryName, ", ");
        }
        return $countryName;
    }


    public function testForCountryPageURLGen($countryId)
    {
        $abroadCategoryPageRequest = $this->load->library("AbroadCategoryPageRequest");
        $url = $abroadCategoryPageRequest->getURLForCountryPage($countryId);
        echo $url;
    }
    /**
     * Purpose       : Main method for university country page
     * Params        : 1. $entityStr : identifier string of country page
     * 		      2. $pageNumber : page number
     * To Do         : none
     * Author        : Romil Goel
     */
    public function countryPage($entityStr, $pageNumber=1) {

        $categoryPageRequest 				= $this->load->library('categoryList/AbroadCategoryPageRequest');

        // initialize local variables
        $pageSize       = 20;	 // page size of the country university page
        $pageNumber     = str_replace("-", "", $pageNumber);
        $countryIdArray = $this->_processDataToGetCountryIds(str_replace("-", "", $entityStr));
        $countryName    = $this->parsedCountryName;

        // "Abroad" to be displayed in the title in case of all country page
        if(empty($countryName)){
            $countryName = "Abroad";
        }
        $countryId = $countryIdArray[0];
        // if no coutnry-id is provided then show 404
        if($countryId == "") {
            show_404_abroad();
        }

        // get SEO data of the page
        $data = $categoryPageRequest->getSeoInfoForCountryPage($countryId, $pageNumber);

        // redirect to the proper url if url is altered or is not correct to the specifications of the university country page URLs
        $userEnteredURL = trim(getCurrentPageURLWithoutQueryParams());
        if($userEnteredURL != $data["url"]) {
            redirect($data["url"], 'location', 301);
        }
        $country_id = $countryId;
        if($countryId == 1){
            $country_id = 0;
        }

        //load the needed files
        $this->load->builder('ListingBuilder','listing');
        $listingBuilder 				= new ListingBuilder;
        $abroadUniversityRepository 	= $listingBuilder->getUniversityRepository();
        $abroadCourseRepository = $listingBuilder->getAbroadCourseRepository();

        $this->load->builder('AbroadCategoryPageBuilder','categoryList');
        $this->abroadCategoryPageLib  		= $this->load->library('categoryList/AbroadCategoryPageLib');
        $abroadListingCommonLib         	= $this->load->library('listing/abroadListingCommonLib');
        $abroadCommonLib         			= $this->load->library('listingPosting/AbroadCommonLib');

        // prepare the pagination data
        $pageNumber = ( empty($pageNumber) || $pageNumber == 1 ) ? 1 : $pageNumber;
        $paginationArr["limitOffset"]	= $pageSize*($pageNumber-1);
        $paginationArr["limitRowCount"]	= $pageSize;
        $paginationArr["pageNumber"]	= $pageNumber;

        // get the list of universities to be displayed on the page(in sorted order of last 14 day viewcount)
        $universityList = $this->abroadCategoryPageLib->getAbroadCountryPageDataFromSolr($paginationArr, $country_id);
        // redirect to country home if country page does not exist
        if(count($universityList['result']) == 0){
            if($universityList['totalCount'] == 0){ // As discussed with Praveen / Azhar Ali (21-04-2016) - case of luxembourg or bahrain
                show_404_abroad();
            } else {
                redirect(SHIKSHA_STUDYABROAD_HOME.'/'.$entityStr.'/universities', 'location', 301); // Correction in target page in case of no extra pages
            }
        }

        $relLinks = $this->abroadCategoryPageLib->getPrevNextPageLinksForCountryPage($countryId,$paginationArr,$universityList['totalCount'],$categoryPageRequest);
        if($relLinks['relNext']!=''){
            $displayData['relNext'] = $relLinks['relNext'];
        }
        if($relLinks['relPrev']!=''){
            $displayData['relPrev'] = $relLinks['relPrev'];
        }
        // get all universities ids
        $allUniversitiesIds = $universityList['universityIds']; 
        $universityArr = array();
        if(!empty($allUniversitiesIds)){
            $universityArr = $abroadUniversityRepository->findMultiple($allUniversitiesIds);
        }

        $courseFeeDetails = array();
        // prepare the university data to be prepared
        foreach($universityArr as $universityObj){
            $universitiesIdsArray[]                       = $id = $universityObj->getId();
            $universityDataObj[$id]["url"]                = $universityObj->getURL();
            $universityDataObj[$id]["university_id"]      = $universityObj->getId();
            $universityDataObj[$id]["university_name"]    = $universityObj->getName();
            $universityDataObj[$id]["university_type"]    = $universityObj->getTypeOfInstitute();
            $universityDataObj[$id]["university_type2"]   = $universityObj->getTypeOfInstitute2();
            $universityDataObj[$id]["establishment_year"] = $universityObj->getEstablishedYear();
            $universityDataObj[$id]["affiliation"]        = $universityObj->getAffiliation();
            $universityDataObj[$id]["accreditation"]      = $universityObj->getAccreditation();
            $universityDataObj[$id]["photos"]             = $universityObj->getPhotos();
            $universityDataObj[$id]["videos"]             = $universityObj->getVideos();
            $universityDataObj[$id]["website"]            = $universityObj->getInternationalStudentsPageLink();
            $locations                                    = $universityObj->getLocation();

            // get location data
            if($locations){
                $stateName 	= $locations->getState();
                $cityName  	= $locations->getCity();
                $country 	= $locations->getCountry();
                $stateName 	= empty($stateName) ? "" : $locations->getState()->getName();
                $cityName  	= empty($cityName) ? "" : $locations->getCity()->getName();
                $country  	= empty($country) ? "" : $locations->getCountry()->getName();
            }

            $universityDataObj[$id]["stateName"]        = $stateName;
            $universityDataObj[$id]["cityName"]         = $cityName;
            $universityDataObj[$id]["country"]          = empty($country_id) ? $country : "";
//            $universityDataObj[$id]["snapshot_courses"] = $universityObj->getSnapshotCoursesArray();
            $responseData                              = array();
            $responseData['sourcePage']                = 'countryPage';
            $responseData['universityId']              = $universityObj->getId();
            $responseData['universityName']            = $universityObj->getName();
            $responseData['destinationCountryId']      = $countryId;
            $responseData['destinationCountryName']    = $countryName;
            $responseData['widget']                    = 'tuple';
            $responseData['trackingPageKeyId']         = 1755;
            $universityDataObj[$id]["response_data"]   = $responseData;

            //prepare popular courses data
            $popularCourses                            = $universityList['result'][$universityObj->getId()]['courseList'];
            if(!empty($popularCourses)){
                $popularCoursesArr                       = explode(',',$popularCourses);
                $popularCoursesObject                    = $abroadCourseRepository->findMultiple($popularCoursesArr); 
                $universityDataObj[$id]['coursesToShow'] = $popularCoursesObject;                

                foreach($popularCoursesObject as $courseId => $courseObj){
                    $fees                                             = $abroadListingCommonLib->convertCurrency($courseObj->getTotalFees()->getCurrency() , 1 , $courseObj->getTotalFees()->getValue());
                    $courseFeeDetails[$courseObj->getId()]['feesVal'] = $abroadListingCommonLib->getIndianDisplableAmount($fees, 2);
                }
            }

            $universityDataObj[$id]["courseCount"]   =  $universityList['result'][$universityObj->getId()]['courseCount'];
        }

        $popularCoursesData = $this->abroadCategoryPageLib->getPopularCoursesForQuickLinks($countryId);
        $displayData['popularCoursesData'] = $popularCoursesData;

        // redirect the page to first page if page number provided doesn't exists
        if($universityList["totalCount"] < (($pageNumber-1)*$paginationArr["limitRowCount"])){
            $countryPageUrl = $categoryPageRequest->getURLForCountryPage($countryId);
            Header("Location: $countryPageUrl");
            exit();
        }
        $displayData["universityList"]      = $universityDataObj;//$universityList["result"];
        $displayData["paginationArr"]       = $paginationArr;
        $displayData["categoryPageRequest"] = $categoryPageRequest;
        $displayData["totalCount"]          = $universityList["totalCount"];
        $displayData["countryId"]           = $countryId;
        $displayData["countryName"]         = $countryName;
        $displayData["seodata"]             = $data;
        $displayData['seoDescription']      = "Check ".(($displayData["totalCount"])?$displayData["totalCount"]:'').(($displayData["totalCount"]>1)?' universities':' university').$data['description'];
        $displayData['validateuser']        = $this->checkUserValidation();
        $displayData['courseFeeDetails']    = $courseFeeDetails;

        $validateuser = $this->checkUserValidation();
        if($validateuser !== 'false') {
            $this->load->model('user/usermodel');
            $usermodel = new usermodel;
            $userId = $validateuser[0]['userid'];
            $user = $usermodel->getUserById($userId);
            if(is_object($user)){
                $name = $user->getFirstName().' '.$user->getLastName();
                $email = $user->getEmail();
                $userFlags = $user->getFlags();
                $isLoggedInLDBUser = $userFlags->getIsLDBUser();
                $checkIfLDBUser = $usermodel->checkIfLDBUser($userId);
                $pref = $user->getPreference();
                if(is_object($pref)){
                    $desiredCourse = $pref->getDesiredCourse();
                }else{
                    $desiredCourse = null;
                }
                $loc = $user->getLocationPreferences();
                $isLocation = count($loc);
            }
            $loggedInUserData = array('userId' => $userId, 'name' => $name, 'email' => $email, 'isLDBUser' => $isLoggedInLDBUser, 'desiredCourse' => $desiredCourse, 'isLocation'=>$isLocation);
        }
        else {
            $loggedInUserData = false;
            $checkIfLDBUser = 'NO';
        }
        $displayData = array_merge($displayData,array('validateuser'=>$validateuser,'loggedInUserData'=>$loggedInUserData,'checkIfLDBUser'=>$checkIfLDBUser));
        $displayData['trackForPages'] = true;
        $this->load->builder('LocationBuilder','location');
        $locationBuilder = new LocationBuilder;
        $this->locationRepository = $locationBuilder->getLocationRepository();
        $this->_populateAbroadCountries($displayData);
        $trackingCountryId = $this->_getCountryByPriority(array($countryId), $displayData['abroadCountries']);
        $displayData['googleRemarketingParams'] = array(
            "categoryId" => 0,
            "subcategoryId" => 0,
            "desiredCourseId" => 0,
            "countryId" => $trackingCountryId,
            "cityId" => 0
        );

        $displayData['breadcrumbData'] 		= $this->_getBreadcrumbs($displayData['categoryPageRequest'],$countryId);

        // save abroad countries for CountryPage Banner
        $abroadCountriesForCountryPageBanner=array();
        $abroadCountriesForCountryPageBanner=$displayData['abroadCountries'];
        $abroadCountries = array();
        $displayData['abroadCountries'] = $this->abroadCategoryPageLib->getCountriesHavingUniversities();
        foreach($displayData['abroadCountries'] as $countries){
            //$countryPageUrl = $categoryPageRequest->getURLForCountryPage($countries['country_id']);
            $countryPageUrl = $this->getURLForCountryPageByName($countries['name']);
            $abroadCountries[] = array('name' => $countries['name'], 'url' => $countryPageUrl);
        }
        $displayData['abroadCountries']               = $abroadCountries;
        $requestData                                  =array();
        $requestData['countryId']                     = $countryId;
        $requestData['pageNumber']                    = $pageNumber;
        $categoryPageBuilder                          = new AbroadCategoryPageBuilder($requestData);
        $request                                      = $categoryPageBuilder->getRequest();
        $countriesForBMSInventoryForCountryPageBanner =array();
        $countriesForBMSInventoryForCountryPageBanner = $this->abroadCategoryPageLib->getCountriesForBMSInventoriesForCountryPageBanner($categoryPageBuilder, $request , $abroadCountriesForCountryPageBanner);
        $displayData['criteriaArray']                 = $this->_getCriteriaParamsForCountry($countriesForBMSInventoryForCountryPageBanner);
        // render the view


        //tracking
        $this->_prepareTrackingData($displayData,'countryPage',$displayData['countryId'],$trackingCountryId);
        $this->load->view('categoryList/abroad/abroadCountryPage', $displayData);
    }

    private function getURLForCountryPageByName($countryName){
        if(empty($countryName)){
            return '';
        }
        global $countryPageUrlInfo;
        $countryNameFormatted = str_replace(" ", "-", $countryName);
        $url = $countryPageUrlInfo['GENERAL']['countryPageUrl'];
        $url = str_replace("{location}", $countryNameFormatted, $url);
        $url = str_replace($unwantedCharArray,'-',$url);
        $url = preg_replace('!-+!', '-', $url);
        $url = strtolower(trim($url,'-'));

        return SHIKSHA_STUDYABROAD_HOME.'/'.$url;
    }

    /**
     * SA-1745
     * Redirect the user to the new country page by parsing the old 'countrypage' url.
     *
     * @param string $entityStr The 'countrypage' url according to the old routes
     * @param int $pageNumber The page number used in pagination
     */
    public function redirect301($entityStr, $pageNumber=1){
        $countryName = explode("-", $entityStr);
        $countryName = $countryName[2];
        $this->countryPage($countryName, $pageNumber);
    }

    public function getCountriesForCourseCountryLayer() {
        $this->_init($displayData);
        $subCatId =  $this->security->xss_clean($this->input->post('subCatId'));
        //  this function can work with multiple subcatIds, therefore check if it is an empty array then it is as good as a zero
        if(is_array($subCatId) && count($subCatId) == 0)
        {
            $subCatId = 0;
        }
        $type = $this->security->xss_clean($this->input->post('type'));
        if($type == "desiredCourse") {
            $ldbCourseId =  $this->security->xss_clean($this->input->post('ldbCourseId'));

            $countryArray 	= $this->abroadCategoryPageLib->getCountriesForDesiredCourses($ldbCourseId, $subCatId);
        }
        elseif($type == "courseLevel") {
            $parentCatId =  $this->security->xss_clean($this->input->post('parentCatId'));
            $courseLevel =  $this->security->xss_clean($this->input->post('courseLevel'));
            $countryArray 	= $this->abroadCategoryPageLib->getCountriesForParentCatAndCourseLevel($parentCatId, $courseLevel, $subCatId);
        }
        if($countryArray) {
            //populate countries
            $this->_populateAbroadCountries($displayData);
            $abroadCountries = $displayData['abroadCountries'];

            $countryIdNameArray = array();
            $i = 0;
            foreach($countryArray as $countryId) {
                foreach($abroadCountries as $countryObj) {
                    if($countryId['country_id'] == $countryObj->getId()) {
                        $countryIdNameArray[$i]['id'] = $countryObj->getId();
                        $countryIdNameArray[$i]['name'] = $countryObj->getName();
                        $i++;
                    }
                }
            }
            //error_log("check if here countryIdNameArray: ".print_r($countryIdNameArray, true));
            echo json_encode($countryIdNameArray);
        }else{
            echo json_encode("");
        }
    }

    function _populateDataToPreFillLayer(& $displayData) {
        $categoryPageRequest = $displayData['categoryPageRequest'];

        $categoryId = $categoryPageRequest->getCategoryId();
        $subCategoryId = $categoryPageRequest->getSubCategoryId();
        $LDBCourseId = $categoryPageRequest->getLDBCourseId();
        $courseLevel = $categoryPageRequest->getCourseLevel();
        $countryIdArray = $categoryPageRequest->getCountryId();

        //send these params to view
        $displayData['currentCategoryId'] = $categoryId;
        $displayData['currentSubCategoryId'] = $subCategoryId;
        $displayData['currentLDBCourseId'] = $LDBCourseId;
        $displayData['currentCourseLevel'] = $courseLevel;

        if(count($countryIdArray) == 1 && $countryIdArray[0] == 1) {
            $countryIdArray = "";
        }
        $displayData['currentCountryIdArr'] = $countryIdArray;

        if($categoryPageRequest->isLDBCoursePage() || $categoryPageRequest->isLDBCourseSubCategoryPage()) {
            if(!$categoryPageRequest->isLDBCourseSubCategoryPage()) {
                $subCategoryId = "";
            }

            $subCatArray = $this->abroadCategoryPageLib->getSubCatsForDesiredCourses($LDBCourseId);

            if($subCatArray) {
                $subCatIdArray = array();
                foreach($subCatArray as $subcatId) {
                    $subCatIdArray[] = $subcatId['sub_category_id'];
                }
            } else {
                $subCategoryId = "";
            }
            if(!in_array($subCategoryId, $subCatIdArray)) {
                $subCategoryId = "";
            }

            $countryArray 	= $this->abroadCategoryPageLib->getCountriesForDesiredCourses($LDBCourseId, $subCategoryId);
        }
        else if ($categoryPageRequest->isCategoryCourseLevelPage() || $categoryPageRequest->isCategorySubCategoryCourseLevelPage()) {
            if($categoryPageRequest->isCategorySubCategoryCourseLevelPage()) {
                $subCatObj = $this->categoryRepository->find($subCategoryId);
                $categoryId = $subCatObj->getParentId();
                $displayData['currentCategoryId'] = $categoryId;
            } else {
                $subCategoryId = "";
            }

            $subCatArray = $this->abroadCategoryPageLib->getSubCatsForParentCatAndCourseLevel($categoryId, $courseLevel);

            if($subCatArray) {
                $subCatIdArray = array();
                foreach($subCatArray as $subcatId) {
                    $subCatIdArray[] = $subcatId['sub_category_id'];
                }
            } else {
                $subCategoryId = "";
            }
            if(!in_array($subCategoryId, $subCatIdArray)) {
                $subCategoryId = "";
            }

            $countryArray = $this->abroadCategoryPageLib->getCountriesForParentCatAndCourseLevel($categoryId, $courseLevel, $subCategoryId);
        }

        if($subCatArray) {
            $subCatObjects = $this->categoryRepository->findMultiple($subCatIdArray);

            foreach($subCatObjects as $obj) {
                $temp[$obj->getId()] = $obj->getName();
            }
            asort($temp);

            $subCatNameIdArray = array();
            $appendOtherSubCatsAtLast = array();
            $i = 0;
            foreach($temp as $subCatId => $subCatName) {
                if($subCatName != 'Other Specializations') {
                    $subCatNameIdArray[$i]['sub_category_id']   = $subCatId;
                    $subCatNameIdArray[$i]['sub_category_name'] = $subCatName;
                } else {
                    $appendOtherSubCatsAtLast['sub_category_id'] = $subCatId;
                    $appendOtherSubCatsAtLast['sub_category_name'] = $subCatName;
                }
                $i++;
            }
            if(!empty($appendOtherSubCatsAtLast)) {
                $subCatNameIdArray[$i]['sub_category_id'] = $appendOtherSubCatsAtLast['sub_category_id'];
                $subCatNameIdArray[$i]['sub_category_name']   = $appendOtherSubCatsAtLast['sub_category_name'];;
            }

            $displayData['subCatArray'] = $subCatNameIdArray;
        }

        if($countryArray) {
            //populate countries
            $this->_populateAbroadCountries($displayData);
            $abroadCountries = $displayData['abroadCountries'];

            $countryIdNameArray = array();
            $i = 0;
            foreach($countryArray as $countryId) {
                foreach($abroadCountries as $countryObj) {
                    if($countryId['country_id'] == $countryObj->getId()) {
                        $countryIdNameArray[$i]['id'] = $countryObj->getId();
                        $countryIdNameArray[$i]['name'] = $countryObj->getName();
                        $i++;
                    }
                }
            }
            $displayData['countryIdNameArray'] = $countryIdNameArray;
        }
    }

    public function ldbCoursePage($entityStr, $entityId)
    {
        //$this->benchmark->mark('start_con');
        $this->abroadCategoryPageLib  = $this->load->library('categoryList/AbroadCategoryPageLib');
        $examName = $this->abroadCategoryPageLib->_getExamNameFromUrlString($entityStr);
        $rawData = explode("-in-", $entityStr);
        $countryNames = $rawData[1];
        $countryIds = $this->_processDataToGetCountryIds($countryNames);

        if(strpos($entityId, '-') === false) {
            $LDBCourseId = $entityId;
            $pageNumber = 1;
        } else {
            $rawData = explode("-", $entityId);
            $LDBCourseId = $rawData[0];
            $pageNumber = $rawData[1];
        }

        $requestData = array();
        $requestData['LDBCourseId'] = $LDBCourseId;
        $requestData['countryId'] = $countryIds;
        $requestData['pageNumber'] = $pageNumber;
        // in case the category page is opened from a url of category page accepting a particular exam score
        $requestData['acceptedExamName'] = $examName;
        $this->checkRedirectionToExamAcceptedCategoryPage($requestData);

        $this->load->builder('AbroadCategoryPageBuilder','categoryList');
        $categoryPageBuilder = new AbroadCategoryPageBuilder($requestData);

        $this->categoryPage($categoryPageBuilder);
    }

    public function ldbCourseSubCategoryPage($entityStr, $entityId) {
        //$this->benchmark->mark('start_con');
        $this->abroadCategoryPageLib  = $this->load->library('categoryList/AbroadCategoryPageLib');
        $examName = $this->abroadCategoryPageLib->_getExamNameFromUrlString($entityStr);
        $rawData = explode("-from-", $entityStr);
        $categoryPageStrArr = explode('-in-',$rawData[0]);
        $ldbCourseId = $this->abroadCategoryPageLib->getDesiredCourseIdByName($categoryPageStrArr[0]);
        $countryNames = $rawData[1];
        $countryIds = $this->_processDataToGetCountryIds($countryNames);
        if(strpos($entityId, '-') === false) {
            $subCatId = str_replace($ldbCourseId,'',$entityId);
            $pageNumber = 1;
        } else {
            $rawData = explode("-", $entityId);
            $subCatId = str_replace($ldbCourseId,'',$rawData[0]);
            $pageNumber = $rawData[1];
        }
        $subCatId = $this->_checkMergedSubcategory($subCatId);

        $requestData = array();
        $requestData['subCategoryId'] = $subCatId;
        $requestData['LDBCourseId'] = $ldbCourseId;
        $requestData['countryId'] = $countryIds;
        $requestData['pageNumber'] = $pageNumber;
        // in case the category page is opened from a url of category page accepting a particular exam score
        $requestData['acceptedExamName'] = $examName;
        $this->checkRedirectionToExamAcceptedCategoryPage($requestData);

        $this->load->builder('AbroadCategoryPageBuilder','categoryList');
        $categoryPageBuilder = new AbroadCategoryPageBuilder($requestData);

        $this->categoryPage($categoryPageBuilder);
    }

    public function categorySubCategoryCourseLevelPage($entityStr, $entityId)
    {
        //$this->benchmark->mark('start_con');
        $this->abroadCategoryPageLib  = $this->load->library('categoryList/AbroadCategoryPageLib');
        $examName = $this->abroadCategoryPageLib->_getExamNameFromUrlString($entityStr);
        $rawData 	= explode("-in-", $entityStr);
        $countryNames 	= $rawData[2];
        $courseLevel 	= $rawData[0];
        $countryIds 	= $this->_processDataToGetCountryIds($countryNames);

        if(strpos($entityId, '-') === false) {
            $subCategoryId = $entityId;
            $pageNumber = 1;
        } else {
            $rawData = explode("-", $entityId);
            $subCategoryId = $rawData[0];
            $pageNumber = $rawData[1];
        }

        $subCategoryId = $this->_checkMergedSubcategory($subCategoryId);

        $requestData = array();
        $requestData['subCategoryId'] 	= $subCategoryId;
        $requestData['countryId'] 	= $countryIds;
        $requestData['courseLevel'] 	= $this->_formatCourseLevel($courseLevel);
        $requestData['pageNumber'] 	= $pageNumber;
        // in case the category page is opened from a url of category page accepting a particular exam score
        $requestData['acceptedExamName'] = $examName;
        $this->_checkRedirectionToExamAcceptedCategoryPage($requestData);

        $this->load->builder('AbroadCategoryPageBuilder','categoryList');
        $categoryPageBuilder = new AbroadCategoryPageBuilder($requestData);

        $this->categoryPage($categoryPageBuilder);
    }

    public function categoryCourseLevelPage($entityStr, $entityId) {
        //$this->benchmark->mark('start_con');
        if(strpos($entityStr, '-of-') === false) {
            $rawData = explode("-in-", $entityStr);
            $countryNames = $rawData[2];
            $courseLevel = $rawData[0];
        } else {
            $rawData = explode("-in-", $entityStr);
            $formatRawData = explode("-of-", $rawData[0]);
            $countryNames = $rawData[1];
            $courseLevel = $formatRawData[0];
        }

        $countryIds = $this->_processDataToGetCountryIds($countryNames);

        if(strpos($entityId, '-') === false) {
            $categoryId = $entityId;
            $pageNumber = 1;
        } else {
            $rawData = explode("-", $entityId);
            $categoryId = $rawData[0];
            $pageNumber = $rawData[1];
        }

        $requestData = array();
        $requestData['categoryId'] = $categoryId;
        $requestData['countryId'] = $countryIds;
        $requestData['courseLevel'] = $this->_formatCourseLevel($courseLevel);
        $requestData['pageNumber'] = $pageNumber;
        // in case the category page is opened from a url of category page accepting a particular exam score
        $requestData['acceptedExamName'] = $examName;
        $this->checkRedirectionToExamAcceptedCategoryPage($requestData);

        $this->load->builder('AbroadCategoryPageBuilder','categoryList');
        $categoryPageBuilder = new AbroadCategoryPageBuilder($requestData);

        $this->categoryPage($categoryPageBuilder);
    }

    public function _formatCourseLevel($courseLevel) {
        return str_replace("-", " - ", $courseLevel);
    }

    public function _processDataToGetCountryIds($countryNames) {
        if($countryNames == "abroad") {
            $qryStr = $_GET['country'];
            if($qryStr == "") {
                $countryNames = "";
            } else {
                $countryNameArr = explode('-',$qryStr);
                //clean up each country name, remove any non alphabet character
                $countryNameArr = array_map(function($a){return preg_replace('/[^a-z]/','',$a);},$countryNameArr);
                $qryStr = implode('-',$countryNameArr);
                $rawDataQryStr = "'".str_replace("-","','",$qryStr)."'";
            }
            $countryNames = $rawDataQryStr;
        } else {
            $countryNames = "'".$countryNames."'";
        }

        if($countryNames != "") {
            $abroadcmsmodel = $this->load->model('categoryList/abroadcategorypagemodel');
            $countryData = $abroadcmsmodel->getCountryInfo($countryNames);
            $this->parsedCountryName = $countryData[0]["name"];
            foreach($countryData as $key => $country) {
                $countryIds[] = $country['countryId'];
            }
        } else {
            $countryIds[0] = 1;
        }

        // If someone tampered location with India country..
        if(in_array(2, $countryIds)) {
            $countryIds = array_diff($countryIds, array(2));
            $countryIds = array_values($countryIds);
            if(count($countryIds) == 0) {
                $countryIds[] = 1;
            }
        }

        return $countryIds;
    }

    public function getCategoryPageUrl() {
        $params['LDBCourseId'] = $this->security->xss_clean($this->input->post('ldbCourseId'));
        $params['categoryId'] = $this->security->xss_clean($this->input->post('categoryId'));
        $params['courseLevel'] = strtolower($this->input->post('courseLevel'));
        if($params['courseLevel'] == 1) {
            $params['courseLevel'] = "";
        }
        $params['subCategoryId'] = $this->security->xss_clean($this->input->post('subCategoryId'));
        $params['countryId'] = $this->security->xss_clean($this->input->post('countryIds'));

        $categoryPageRequest = $this->load->library('categoryList/AbroadCategoryPageRequest');
        $categoryPageRequest->setData($params);
        $url = $categoryPageRequest->getUrl();
        $isHomepage = $this->security->xss_clean($this->input->post('isHomepage'));
        if($isHomepage === 'true'){
            // filter data
            $filterData = array();
            $filterData['subCatFilter'] = $this->security->xss_clean($this->input->post('subCatFilter'));
            $filterData['fees'] = $this->security->xss_clean($this->input->post('fees'));
            $filterData['exam'] = $this->security->xss_clean($this->input->post('exam'));
            $filterData['examsScore'] = $this->security->xss_clean($this->input->post('examsScore'));
            $filterData['sort'] = $this->security->xss_clean($this->input->post('sort'));
            $url = $this->_getRedirectedCategoryPageUrlAndSetFilter($categoryPageRequest,$filterData,$params);
            echo $url;
            return;
        }
        echo $url;
    }

    /*
    * function to get redirection url for a given categoryPageRequest &
    * generate filter & sort cookie based on given filter data
    */
    private function _getRedirectedCategoryPageUrlAndSetFilter($categoryPageRequest, $filterData, $params)
    {
        // check & create url , page key if post params will lead to redirection based on merging of categories
        $this->load->builder('CategoryBuilder','categoryList');
        $categoryBuilder = new CategoryBuilder;
        $categoryRepository = $categoryBuilder->getCategoryRepository();
        $categoryId = $categoryPageRequest->getCategoryId();
        $subcategoryId = $categoryPageRequest->getSubCategoryId();
        if($categoryPageRequest->getCourseLevel() == 'masters')
        {
            if($categoryId==CATEGORY_BUSINESS )  // parent cat lvl page (business)
            {
                $params =  array('categoryId'=>1,'LDBCourseId'=>DESIRED_COURSE_MBA,'courseLevel'=>'');
            }
            else if(in_array($categoryId,array(CATEGORY_ENGINEERING,CATEGORY_COMPUTERS,CATEGORY_SCIENCE)))  // parent cat lvl page (science/engg/comp)
            {
                $params =  array('categoryId'=>1,'LDBCourseId'=>DESIRED_COURSE_MS,'courseLevel'=>'');
            }
            else if($subcategoryId >1 && $categoryId==1) // subcat level page
            {
                $category = $categoryRepository->find($subcategoryId);
                if($category->getParentId() == CATEGORY_BUSINESS) // business
                {
                    $params =  array('LDBCourseId'=>DESIRED_COURSE_MBA,'courseLevel'=>'');
                }
                else if(in_array($category->getParentId(), array(CATEGORY_ENGINEERING,CATEGORY_COMPUTERS,CATEGORY_SCIENCE))){ // Engineering,Computer and Science
                    // set data for MS-subcategory page
                    $params = array('categoryId' => 1,'subCategoryId' => $category->getId(),'LDBCourseId' => DESIRED_COURSE_MS, 'courseLevel' => '');
                }
            }
            else if($subcategoryId > 1){ // lvl is given , subcat is > 1, parent cat not required !!
                //echo "DROPIT";
                $params['categoryId'] = 1;
            }
        }
        else if($categoryPageRequest->getCourseLevel() == 'bachelors')
        {
            // check for BEBTECH
            if($subcategoryId > 1 && $subcategoryId != SUB_CATEGORY_ANIMATION_AND_DESIGN){ // animation & design
                $category = $categoryRepository->find($subcategoryId);
                if(in_array($category->getParentId(), array(CATEGORY_ENGINEERING, CATEGORY_COMPUTERS))){ //engg & computers
                    $params = array('categoryId' => 1,'subCategoryId' => $category->getId(),'LDBCourseId' => DESIRED_COURSE_BTECH, 'courseLevel' => '');
                }
                else if($subcategoryId > 1){ // lvl is given , subcat is > 1, parent cat not required !!
                    //echo "DROPIT";
                    $params['categoryId']=1;
                }
            }elseif($categoryId > 1){
                if(in_array($categoryId, array(CATEGORY_ENGINEERING,CATEGORY_COMPUTERS))){ //engg & computers
                    $params = array('categoryId' => 1,'subCategoryId' => 1,'LDBCourseId' => DESIRED_COURSE_BTECH, 'courseLevel' => '');
                }
            }
        }
        $request = clone $categoryPageRequest;
        $request->setData($params);
        $this->abroadCategoryPageLib  = $this->load->library('categoryList/AbroadCategoryPageLib');
        $this->abroadCategoryPageLib->prepareFilterWithSortCookie($request->getPageKey(),$filterData);
        return $request->getUrl();
    }

    public function _populateAbroadCountries(& $displayData,$locationRepository = NULL)
    {
        if($locationRepository != NULL && !$this->locationRepository){
            $this->locationRepository = $locationRepository;
        }
        $countries = $this->locationRepository->getAbroadCountries();

        //sort countries by name ascending order
        usort($countries,function($c1,$c2){
            return (strcasecmp($c1->getName(),$c2->getName()));
        });

        $displayData['abroadCountries'] = $countries;
    }

    public function getSubCatsForCourseCountryLayer($inputData = array()) {
        $this->abroadCategoryPageLib  = $this->load->library('categoryList/AbroadCategoryPageLib');
        //  Loading CategoryBuilder for getting categoryRepository
        $this->load->builder('CategoryBuilder','categoryList');
        $categoryBuilder = new CategoryBuilder;
        $this->categoryRepository = $categoryBuilder->getCategoryRepository();

        //$this->_init($displayData);
        if(!empty($inputData['type'])){
            $type = $inputData['type'];
        }else{
            $type = $this->security->xss_clean($this->input->post('type'));
        }

        if($type == "desiredCourse") {
            if(!empty($inputData)){
                $ldbCourseId = $inputData['ldbCourseId'];
            }else{
                $ldbCourseId =  $this->security->xss_clean($this->input->post('ldbCourseId'));
            }
            $subCatArray 	= $this->abroadCategoryPageLib->getSubCatsForDesiredCourses($ldbCourseId);
        }
        elseif($type == "courseLevel") {
            if(!empty($inputData)){
                $parentCatId = $inputData['parentCatId'];
                $courseLevel = $inputData['courseLevel'];
            }else{
                $parentCatId =  $this->security->xss_clean($this->input->post('parentCatId'));
                $courseLevel =  $this->security->xss_clean($this->input->post('courseLevel'));
            }
            $subCatArray 	= $this->abroadCategoryPageLib->getSubCatsForParentCatAndCourseLevel($parentCatId, $courseLevel);
        }

        if($subCatArray) {
            $subCatIdArray = array();
            foreach($subCatArray as $subcatId) {
                $subCatIdArray[] = $subcatId['sub_category_id'];
            }

            $subCatObjects = $this->categoryRepository->findMultiple($subCatIdArray);
            foreach($subCatObjects as $obj) {
                $temp[$obj->getId()] = $obj->getName();
            }
            asort($temp);

            $subCatNameIdArray = array();
            $appendOtherSubCatsAtLast = array();
            $i = 0;
            foreach($temp as $subCatId => $subCatName) {
                if($subCatName != 'Other Specializations') {
                    $subCatNameIdArray[$i]['sub_category_id']   = $subCatId;
                    $subCatNameIdArray[$i]['sub_category_name'] = $subCatName;
                } else {
                    $appendOtherSubCatsAtLast['sub_category_id'] = $subCatId;
                    $appendOtherSubCatsAtLast['sub_category_name'] = $subCatName;
                }
                $i++;
            }
            if(!empty($appendOtherSubCatsAtLast)) {
                $subCatNameIdArray[$i]['sub_category_id'] = $appendOtherSubCatsAtLast['sub_category_id'];
                $subCatNameIdArray[$i]['sub_category_name']   = $appendOtherSubCatsAtLast['sub_category_name'];;
            }

            //error_log("subCatArray: ".print_r($subCatArray, true));
            if(!empty($inputData['type'])){
                return $subCatNameIdArray;
            }else{
                echo json_encode($subCatNameIdArray);
            }

        }
        else {
            if(!empty($inputData['type'])){
                return array();
            }else{
                echo json_encode("");
            }

        }
    }

    public function getSubCatsForSARegisteration($inputData = array()) {
        $this->abroadCategoryPageLib  = $this->load->library('categoryList/AbroadCategoryPageLib');
        //$this->_init($displayData);

        if(!empty($inputData['type'])){
            $type = $inputData['type'];
        }else{
            $type = $this->security->xss_clean($this->input->post('type'));
        }

        if($type == "desiredCourse") {
            if(!empty($inputData)){
                $ldbCourseId = $inputData['ldbCourseId'];
            }else{
                $ldbCourseId =  $this->security->xss_clean($this->input->post('ldbCourseId'));
            }
            $subCatArray 	= $this->abroadCategoryPageLib->getSubCatsForDesiredCourses($ldbCourseId);
        }
        elseif($type == "courseLevel") {
            if(!empty($inputData)){
                $parentCatId = $inputData['parentCatId'];
                $courseLevel = $inputData['courseLevel'];
            }else{
                $parentCatId =  $this->security->xss_clean($this->input->post('parentCatId'));
                $courseLevel =  $this->security->xss_clean($this->input->post('courseLevel'));
            }

            $subCatArray 	= $this->abroadCategoryPageLib->getSubCatsForParentCatOnly($parentCatId);
        }

        $sortTemp = array(); $sortTempResult =array();
        foreach ($subCatArray as $key => $value) {
            if($value['sub_category_name'] != 'Other Specializations') {
                $sortTemp[$value['sub_category_name']] = $key;
            }else{
                $appendOtherSubCatsAtLast = $value;
            }
        }
        ksort($sortTemp);
        foreach ($sortTemp as $key => $subCat){
            $sortTempResult[] = $subCatArray[$subCat];
        }
        $sortTempResult[] = $appendOtherSubCatsAtLast;

        $subCatArray = array_filter($sortTempResult);
        if(!empty($subCatArray)) {
            if(!empty($inputData['type'])){
                return $subCatArray;
            }else{
                echo json_encode($subCatArray);
            }
        }
        else {
            if(!empty($inputData['type'])){
                return array();
            }else{
                echo json_encode("");
            }
        }
    }

    function formatFiltersForCategoryPageFromSolr(& $displayData) {
        // get generated filters for category page
        $feeRangesForFilter          = $displayData['filters']['fee'];
        $examsForFilters             = $displayData['filters']['exams'];
        $examsScoreForFilters        = $displayData['filters']['examsScore'];
        $examsMinScoreForFilters     = $displayData['filters']['examsScore'];
        $specializationForFilters    = $displayData['filters']['coursecategory'];
        $moreOptionsForFilters       = $displayData['filters']['moreoptions'];
        $citiesForFilters            = $displayData['filters']['city'];
        $stateForFilters             = $displayData['filters']['state'];
        $countryForFilters           = $displayData['filters']['country'];
        $userAppliedFeeRangesForFilter       = $displayData['userAppliedfilters']['fee'];
        $userAppliedExamsForFilters          = $displayData['userAppliedfilters']['exams'];
        $userAppliedSpecializationForFilters = $displayData['userAppliedfilters']['coursecategory'];
        $userAppliedMoreOptionsForFilters    = $displayData['userAppliedfilters']['moreoptions'];
        $userAppliedCitiesForFilters         = $displayData['userAppliedfilters']['city'];
        $userAppliedStateForFilters          = $displayData['userAppliedfilters']['state'];
        $userAppliedCountryForFilters        = $displayData['userAppliedfilters']['country'];
        $userAppliedExamsScoreForFilters     = $displayData['userAppliedfilters']['examsScore'];
        $userAppliedExamsMinScoreForFilters  = $displayData['userAppliedfilters']['examsScore'];
        //Sort all the filters
        asort($feeRangesForFilter);
        //asort($examsForFilters); //As exam filters will be sorted by result count
        //asort($specializationForFilters);//As specialization filters will be sorted by result count
        asort($moreOptionsForFilters);
        asort($citiesForFilters);
        asort($stateForFilters);
        asort($countryForFilters);

        // sort all the user applied filters
        asort($userAppliedFeeRangesForFilter);
        // asort($userAppliedExamsForFilters);  //As exam filters will be sorted by result count
        //asort($userAppliedSpecializationForFilters); //As specialization filters will be sorted by result count
        asort($userAppliedMoreOptionsForFilters);
        asort($userAppliedCitiesForFilters);
        asort($userAppliedStateForFilters);
        asort($userAppliedCountryForFilters);

        if(!is_array($userAppliedExamsForFilters))      $userAppliedExamsForFilters = array();
        if(!is_array($examsForFilters))                 $examsForFilters = array();
        if(!is_array($examsScoreForFilters))            $examsScoreForFilters = array();
        if(!is_array($examsMinScoreForFilters))         $examsMinScoreForFilters = array();
        if(!is_array($specializationForFilters))        $specializationForFilters = array();
        if(!is_array($moreOptionsForFilters))           $moreOptionsForFilters = array();
        if(!is_array($citiesForFilters))                $citiesForFilters = array();
        if(!is_array($stateForFilters))                 $stateForFilters = array();
        if(!is_array($countryForFilters))               $countryForFilters = array();

        if(!is_array($userAppliedExamsScoreForFilters))         $userAppliedExamsScoreForFilters = array();
        if(!is_array($userAppliedExamsMinScoreForFilters))      $userAppliedExamsMinScoreForFilters = array();
        if(!is_array($userAppliedSpecializationForFilters))     $userAppliedSpecializationForFilters = array();
        if(!is_array($userAppliedMoreOptionsForFilters))        $userAppliedMoreOptionsForFilters = array();
        if(!is_array($userAppliedCitiesForFilters))             $userAppliedCitiesForFilters = array();
        if(!is_array($userAppliedStateForFilters))              $userAppliedStateForFilters = array();
        if(!is_array($userAppliedCountryForFilters))            $userAppliedCountryForFilters = array();

        // code for sorting the enabled filters to the top
        $feeRangesForFilter     = $userAppliedFeeRangesForFilter    + $feeRangesForFilter;
        $examsForFilters        = $userAppliedExamsForFilters       + $examsForFilters;
        // exams are now sorted based on sorting order given by product (see below config)
        $requestData = array('LDBCourseId'=>$displayData['categoryPageRequest']->getLDBCourseId(),
            'categoryId'=>$displayData['categoryPageRequest']->getCategoryId(),
            'courseLevel'=>$displayData['categoryPageRequest']->getCourseLevel());
        $examOrder = $this->abroadCategoryPageLib->getExamOrderByCategory($requestData);

        uasort($examsForFilters, function ($a,$b) use($examOrder){
            return ($examOrder[$a] > $examOrder[$b]?1:-1);
        });
        $examsScoreForFilters       = $userAppliedExamsScoreForFilters      + $examsScoreForFilters;
        $examsMinScoreForFilters    = $userAppliedExamsMinScoreForFilters   + $examsMinScoreForFilters;
        $specializationForFilters   = $userAppliedSpecializationForFilters  + $specializationForFilters;
        $moreOptionsForFilters      = $userAppliedMoreOptionsForFilters     + $moreOptionsForFilters;
        $citiesForFilters           = $userAppliedCitiesForFilters          + $citiesForFilters;
        $stateForFilters            = $userAppliedStateForFilters           + $stateForFilters;
        $countryForFilters          = $userAppliedCountryForFilters         + $countryForFilters;

        //For the fees filter, send "Any Fees" to the bottom
        if(reset($feeRangesForFilter) == "Any Fees"){
            $feeRangesForFilter = array_slice($feeRangesForFilter,1,count($feeRangesForFilter)-1,true) + array(reset(array_keys($feeRangesForFilter))=>reset($feeRangesForFilter));
        }
        // For the more Options filter, if there are no non-degree courses, then unset the "Exclude ..." option
        global $levelFilter;
        if(!$levelFilter || $displayData['categoryPageRequest']->isCertDiplomaPage()||$displayData['onlyCertDiplomaPage']){
            unset($moreOptionsForFilters['DEGREE_COURSE']);
        }

        if($displayData['appliedFilters']["examsMinScore"]!=''){
            $maxScore = explode('--', $displayData['appliedFilters']['examsScore'][0]);
            foreach ($examsMinScoreForFilters[$maxScore[2]] as $key => $value) {
                if($maxScore[1] < $value){
                    unset($examsMinScoreForFilters[$maxScore[2]][$key]);
                    unset($userAppliedExamsMinScoreForFilters[$maxScore[2]][$key]);
                }
            }
        }


        //send them to view
        $displayData['feeRangesForFilter']      = $feeRangesForFilter;
        $displayData['examsForFilter']          = $examsForFilters;
        $displayData['examsScoreForFilter']     = $examsScoreForFilters;
        $displayData['examsMinScoreForFilter']  = $examsMinScoreForFilters;
        $displayData['specializationForFilters']= $specializationForFilters;
        $displayData['moreOptionsForFilters']   = $moreOptionsForFilters;
        $displayData['citiesForFilters']        = $citiesForFilters;
        $displayData['statesForFilters']        = $stateForFilters;
        $displayData['countriesForFilters']     = $countryForFilters;

        foreach($displayData['countriesForFilters'] as $countryId=>$country) {
            $data['countryId']  = array($countryId);
            $request        = clone $displayData['categoryPageRequest'];
            $request->setData($data);
            $countryUrl[$countryId] = $request->getURL();
        }
        $displayData['countryUrl'] = $countryUrl;

        $displayData['userAppliedFeeRangesForFilter']      = $userAppliedFeeRangesForFilter;
        $displayData['userAppliedExamsForFilters']         = $userAppliedExamsForFilters;
        $displayData['userAppliedExamsScoreForFilters']    = $userAppliedExamsScoreForFilters;
        $displayData['userAppliedExamsMinScoreForFilters'] = $userAppliedExamsMinScoreForFilters;
        $displayData['userAppliedSpecializationForFilters']= $userAppliedSpecializationForFilters;
        $displayData['userAppliedMoreOptionsForFilters']   = $userAppliedMoreOptionsForFilters;
        $displayData['userAppliedCitiesForFilters']        = $userAppliedCitiesForFilters;
        $displayData['userAppliedStateForFilters']         = $userAppliedStateForFilters;
        $displayData['userAppliedCountryForFilters']       = $userAppliedCountryForFilters;

    }



    function formatFiltersForCategoryPage(& $displayData)
    {
        // get generated filters for category page
        $feeRangesForFilter 		 = $displayData['filters']['fees']->getFilteredValues();
        $examsForFilters 		     = $displayData['filters']['exams']->getFilteredValues();
        $examsScoreForFilters        = $displayData['filters']['examsScore']->getFilteredValues();
        $examsMinScoreForFilters 	 = $displayData['filters']['examsMinScore']->getFilteredValues();
        $specializationForFilters 	 = $displayData['filters']['coursecategory']->getFilteredValues();
        $moreOptionsForFilters 		 = $displayData['filters']['moreoptions']->getFilteredValues();
        $citiesForFilters 		     = $displayData['filters']['city']->getFilteredValues();
        $stateForFilters 		     = $displayData['filters']['state']->getFilteredValues();
        $countryForFilters 		     = $displayData['filters']['country']->getFilteredValues();
        // get filters generated after filters are applied on category page
        if(!empty($displayData['appliedFilters']))
        {
            $userAppliedFeeRangesForFilter 	 = $displayData['userAppliedfiltersForFees']['fees']->getFilteredValues();
            $userAppliedExamsForFilters 	 = $displayData['userAppliedfiltersForExam']['exams']->getFilteredValues();
            $userAppliedSpecializationForFilters = $displayData['userAppliedfiltersForSpecialization']['coursecategory']->getFilteredValues();
            $userAppliedMoreOptionsForFilters 	 = $displayData['userAppliedfiltersForMoreoption']['moreoptions']->getFilteredValues();
            if(!SKIP_ABROAD_CP_FILTERS){
                $userAppliedCitiesForFilters 	 = $displayData['userAppliedfiltersForLocation']['city']->getFilteredValues();
                $userAppliedStateForFilters 	 = $displayData['userAppliedfiltersForLocation']['state']->getFilteredValues();
                $userAppliedCountryForFilters 	 = $displayData['userAppliedfiltersForLocation']['country']->getFilteredValues();
                $userAppliedExamsScoreForFilters = $displayData['userAppliedfiltersForExamsScore']['examsScore']->getFilteredValues();
                $userAppliedExamsMinScoreForFilters  = $displayData['userAppliedfiltersForExamsMinScore']['examsMinScore']->getFilteredValues();
            }
            else
            {
                $userAppliedFeeRangesForFilter 	 = $displayData['userAppliedfilters']['fees']->getFilteredValues();
                $userAppliedExamsForFilters 	 = $displayData['userAppliedfilters']['exams']->getFilteredValues();
                $userAppliedSpecializationForFilters = $displayData['userAppliedfilters']['coursecategory']->getFilteredValues();
                $userAppliedMoreOptionsForFilters 	 = $displayData['userAppliedfilters']['moreoptions']->getFilteredValues();
                if(!SKIP_ABROAD_CP_FILTERS){
                    $userAppliedCitiesForFilters 	 = $displayData['userAppliedfilters']['city']->getFilteredValues();
                    $userAppliedStateForFilters 	 = $displayData['userAppliedfilters']['state']->getFilteredValues();
                    $userAppliedCountryForFilters 	 = $displayData['userAppliedfilters']['country']->getFilteredValues();
                    $userAppliedExamsScoreForFilters = $displayData['userAppliedfilters']['examsScore']->getFilteredValues();
                    $userAppliedExamsMinScoreForFilters = $displayData['userAppliedfilters']['examsMinScore']->getFilteredValues();
                }
            }
            //Sort all the filters
            asort($feeRangesForFilter);
            //asort($examsForFilters); //As exam filters will be sorted by result count
            //asort($specializationForFilters);//As specialization filters will be sorted by result count
            asort($moreOptionsForFilters);
            asort($citiesForFilters);
            asort($stateForFilters);
            asort($countryForFilters);

            // sort all the user applied filters
            asort($userAppliedFeeRangesForFilter);
            // asort($userAppliedExamsForFilters);  //As exam filters will be sorted by result count
            //asort($userAppliedSpecializationForFilters); //As specialization filters will be sorted by result count
            asort($userAppliedMoreOptionsForFilters);
            asort($userAppliedCitiesForFilters);
            asort($userAppliedStateForFilters);
            asort($userAppliedCountryForFilters);

            if(!is_array($userAppliedExamsForFilters))  	$userAppliedExamsForFilters = array();
            if(!is_array($examsForFilters))    		$examsForFilters = array();
            if(!is_array($examsScoreForFilters))            $examsScoreForFilters = array();
            if(!is_array($examsMinScoreForFilters))    		$examsMinScoreForFilters = array();
            if(!is_array($specializationForFilters))    	$specializationForFilters = array();
            if(!is_array($moreOptionsForFilters))           $moreOptionsForFilters = array();
            if(!is_array($citiesForFilters))           	$citiesForFilters = array();
            if(!is_array($stateForFilters))           	$stateForFilters = array();
            if(!is_array($countryForFilters))           	$countryForFilters = array();

            if(!is_array($userAppliedExamsForFilters))  		$userAppliedExamsForFilters = array();
            if(!is_array($userAppliedExamsScoreForFilters))         $userAppliedExamsScoreForFilters = array();
            if(!is_array($userAppliedExamsMinScoreForFilters))  		$userAppliedExamsMinScoreForFilters = array();
            if(!is_array($userAppliedSpecializationForFilters))    	$userAppliedSpecializationForFilters = array();
            if(!is_array($userAppliedMoreOptionsForFilters))    	$userAppliedMoreOptionsForFilters = array();
            if(!is_array($userAppliedCitiesForFilters))           	$userAppliedCitiesForFilters = array();
            if(!is_array($userAppliedStateForFilters))           	$userAppliedStateForFilters = array();
            if(!is_array($userAppliedCountryForFilters))           	$userAppliedCountryForFilters = array();

            // code for sorting the enabled filters to the top
            $feeRangesForFilter		= $userAppliedFeeRangesForFilter	+ $feeRangesForFilter;
            $examsForFilters 		= $userAppliedExamsForFilters 		+ $examsForFilters;
            // exams are now sorted based on sorting order given by product (see below config)
            $requestData = array('LDBCourseId'=>$displayData['categoryPageRequest']->getLDBCourseId(),
                'categoryId'=>$displayData['categoryPageRequest']->getCategoryId(),
                'courseLevel'=>$displayData['categoryPageRequest']->getCourseLevel());
            $examOrder = $this->abroadCategoryPageLib->getExamOrderByCategory($requestData);

            uasort($examsForFilters, function ($a,$b) use($examOrder){
                return ($examOrder[$a] > $examOrder[$b]?1:-1);
            });
            $examsScoreForFilters       = $userAppliedExamsScoreForFilters      + $examsScoreForFilters;
            $examsMinScoreForFilters 		= $userAppliedExamsMinScoreForFilters 		+ $examsMinScoreForFilters;
            $specializationForFilters 	= $userAppliedSpecializationForFilters 	+ $specializationForFilters;
            $moreOptionsForFilters 		= $userAppliedMoreOptionsForFilters 	+ $moreOptionsForFilters;
            $citiesForFilters 		= $userAppliedCitiesForFilters 		+ $citiesForFilters;
            $stateForFilters 		= $userAppliedStateForFilters 		+ $stateForFilters;
            $countryForFilters 		= $userAppliedCountryForFilters 	+ $countryForFilters;

            //For the fees filter, send "Any Fees" to the bottom
            if(reset($feeRangesForFilter) == "Any Fees"){
                $feeRangesForFilter = array_slice($feeRangesForFilter,1,count($feeRangesForFilter)-1,true) + array(reset(array_keys($feeRangesForFilter))=>reset($feeRangesForFilter));
            }
            // For the more Options filter, if there are no non-degree courses, then unset the "Exclude ..." option
            global $levelFilter;
            if(!$levelFilter || $displayData['categoryPageRequest']->isCertDiplomaPage()||$displayData['onlyCertDiplomaPage']){
                unset($moreOptionsForFilters['DEGREE_COURSE']);
            }


            //send them to view
            $displayData['feeRangesForFilter']      = $feeRangesForFilter;
            $displayData['examsForFilter']          = $examsForFilters;
            $displayData['examsScoreForFilter']     = $examsScoreForFilters;
            $displayData['examsMinScoreForFilter']  = $examsMinScoreForFilters;
            $displayData['specializationForFilters']= $specializationForFilters;
            $displayData['moreOptionsForFilters']   = $moreOptionsForFilters;
            $displayData['citiesForFilters']        = $citiesForFilters;
            $displayData['statesForFilters']        = $stateForFilters;
            $displayData['countriesForFilters']     = $countryForFilters;

            foreach($displayData['countriesForFilters'] as $countryId=>$country) {
                $data['countryId'] 	= array($countryId);
                $request 		= clone $displayData['categoryPageRequest'];
                $request->setData($data);
                $countryUrl[$countryId] = $request->getURL();
            }
            $displayData['countryUrl'] = $countryUrl;

            $displayData['userAppliedFeeRangesForFilter']      = $userAppliedFeeRangesForFilter;
            $displayData['userAppliedExamsForFilters']         = $userAppliedExamsForFilters;
            $displayData['userAppliedExamsScoreForFilters']    = $userAppliedExamsScoreForFilters;
            $displayData['userAppliedExamsMinScoreForFilters']    = $userAppliedExamsMinScoreForFilters;
            $displayData['userAppliedSpecializationForFilters']= $userAppliedSpecializationForFilters;
            $displayData['userAppliedMoreOptionsForFilters']   = $userAppliedMoreOptionsForFilters;
            $displayData['userAppliedCitiesForFilters']        = $userAppliedCitiesForFilters;
            $displayData['userAppliedStateForFilters']         = $userAppliedStateForFilters;
            $displayData['userAppliedCountryForFilters']       = $userAppliedCountryForFilters;
        }}

    public function fetchIfUserHasShortListedCourses(){
        $validity = $this->checkUserValidation ();
        $data = array();
        if (! (($validity == "false") || ($validity == ""))) {
            $data ['userId'] = $validity [0] ['userid'];
        }
        $shortlistListingLib = $this->load->library( 'listing/ShortlistListingLib' );
        return $shortlistListingLib->fetchIfUserHasShortListedCourses ($data);

    }

    public function updateShortListedCourse($courseId, $action, $tab='abroadCategoryPage', $scope='abroad', $user_email= '',$tracking_keyid='') {
        $validity = $this->checkUserValidation ();
        $data['userId'] = ($validity == 'false')? -1 : $validity[0]['userid'];
        $data['sessionId'] = sessionId();
        $data['visitorSessionid'] = getVisitorSessionId();
        if ($action == 'delete') {
            $data['courseId'] = $courseId;
            $data['scope'] = $scope;
            $data['status'] = 'deleted';
        } else {
            if($user_email!=''){
                $this->load->library('register_client');
                $register_client = new Register_client();
                $signedInUser = $register_client->getinfoifexists(1, $user_email, 'email');
                $data['userId'] = $signedInUser [0] ['userid'];
            }
            $data['status'] = 'live';
            $data['scope'] = $scope;
            $data['courseId'] = $courseId;
            $data['pageType'] = $tab;
            $data['shortListTime'] = date ( 'Y-m-d H:i:s' );
        }
        $data['tracking_keyid'] = ($tracking_keyid>0) ? $tracking_keyid : $this->security->xss_clean($this->input->post('trackingPageKeyId'));
        if(empty($data['tracking_keyid'])){
            $data['tracking_keyid'] = $this->security->xss_clean($this->input->post('tracking_keyid'));
        }
        $shortlistListingLib = $this->load->library ( 'listing/ShortlistListingLib' );
        echo $shortlistListingLib->updateShortListedCourse ( $data, $action );
    }


    //@Author : Rahul Bhatnagar
    // The following 3 functions are for shortlist box at the common study abroad header and the shortlist page
    public function getShortListedCourseCount(){
        $data = array('scope' => 'abroad');
        $validity = $this->checkUserValidation();
        if($validity !== "false"){
            $data['userId'] = $validity[0]['userid'];
            $this->load->model('listing/shortlistlistingmodel');
            $shortlistListingModel = new shortlistlistingmodel();
            $totalCount = $shortlistListingModel->getShortlistedCoursesCount($data);
        }
        else
        {
            $shortListedCourses = json_decode($_COOKIE["usc"]);
            $totalCount = count($shortListedCourses);
        }
        echo $totalCount;
    }

    private function cleanShortListedCourseCookie(){
        $shortListedCourses = json_decode($_COOKIE["usc"],true);
        $finalCourses = array();
        foreach($shortListedCourses as $course){
            if($this->_existsCourse($course['csrId'])){
                $finalCourses[] = $course;
            }
        }
        return count($finalCourses);
    }

    private function _existsCourse($courseId){
        $this->load->model('listing/shortlistlistingmodel');
        $shortlistListingModel = new shortlistlistingmodel();
        if($shortlistListingModel->existsCourse($courseId)){
            return true;
        }
        return false;
    }

    public function getShortlistPage($rowIdOfLastTuple = 0)
    {

        //Step 0 : Load needed resources
        $pageUrl 			              = SHIKSHA_STUDYABROAD_HOME . '/my-saved-courses';
        $categoryPageRecommendations 	  = $this->load->library('categoryList/CategoryPageRecommendations');
        $shortlistListingLib 		      = $this->load->library('listing/ShortlistListingLib');
        $shikshaApplyCommonLib		      = $this->load->library('rateMyChances/ShikshaApplyCommonLib');
        $this->abroadListingCommonLib     = $this->load->library ('listing/AbroadListingCommonLib' ); // for counsellor & category data

        //Step 1 : Fix URL with redirection
        if($pageUrl!== getCurrentPageURLWithoutQueryParams()){
            redirect($pageUrl,'location',301);
        }

        //Step 2 : Get the shortlisted courses
        $data['rowIdOfLastTuple'] 	= $rowIdOfLastTuple;
        $data['noOfResultPerPage'] 	= RMC_TAB_TUPLE_COUNT;
        $validity  			        = $this->checkUserValidation();
        if($validity!='false')
        {
            $data['userId'] 		= $validity[0]['userid'];
        }

        $displayData['courseAndUnivObjs']         = $shortlistListingLib->getShortlistedCoursesDetail ($data);
        $displayData['userShortlistedCourseIds']  = $shortlistListingLib->fetchIfUserHasShortListedCourses($data);

        //Step 3 : Get recommendation data
        //Filling up $displayData as required for various widgets now on
        //$breadcrumbData = array ( '0' => array ( 'title' => 'Home', 'url' => SHIKSHA_STUDYABROAD_HOME),
        //			 '1' => array ( 'title' => 'Shortlisted Courses' ,'url' => '')
        //			);
        //
        //$displayData['breadcrumbData'] 		= $breadcrumbData;
        $displayData['validateuser'] 		    = $this->checkUserValidation();
        $displayData['noOfResultPerPage'] 	    = RMC_TAB_TUPLE_COUNT;
        $displayData['thisPageBrochureInfo'] 	= true;
        $displayData['seoUrl'] 			        = $pageUrl;
        $displayData['robotsMetaTag'] 		    = "NOINDEX, FOLLOW";

        if($displayData['validateuser'] !== 'false')
        {
            $this->load->model('user/usermodel');
            $usermodel 				            = new usermodel;
            $userId 				            = $displayData['validateuser'][0]['userid'];
            $user 					            = $usermodel->getUserById($userId);
            $name 					            = $user->getFirstName().' '.$user->getLastName();
            $email 					            = $user->getEmail();
            $userFlags 				            = $user->getFlags();
            $isLoggedInLDBUser 			        = $userFlags->getIsLDBUser();
            $displayData['loggedInUserData'] 	= array('userId' => $userId, 'name' => $name, 'email' => $email, 'isLDBUser' => $isLoggedInLDBUser);
            $pref 				                = $user->getPreference();
            $loc 				                = $user->getLocationPreferences();
            $isLocation 			            = count($loc);
            if(is_object($pref))
            {
                $desiredCourse = $pref->getDesiredCourse();
            }
            else
            {
                $desiredCourse = null;
            }
            $displayData['loggedInUserData']['desiredCourse'] 		= $desiredCourse;
            $displayData['loggedInUserData']['isLocation'] 		= $isLocation;
            // get RMC courses on which current user has made response
            $displayData['RMCCourseAndUnivObjs'] = $shikshaApplyCommonLib->getRMCCoursesAndUniversitiesByUser($userId);
        }
        else
        {
            $displayData['loggedInUserData'] = false;
        }

        // now prepare subcategories data for all courses:
        //prepare all unique courses
        if(is_null($displayData['RMCCourseAndUnivObjs']['courses']))
        {
            $allCourses = array_unique(array_keys($displayData['courseAndUnivObjs']['courses']));
        }else{
            $allCourses = array_unique(array_merge(array_keys($displayData['courseAndUnivObjs']['courses']),array_keys($displayData['RMCCourseAndUnivObjs']['courses'])));
        }

        //if we have unique and nozero courseIds then fetch their category data
        if(count($allCourses)>0){
            $displayData['categoryData'] = $this->abroadListingCommonLib->getCategoryOfAbroadCourse($allCourses);
        }
        //get data for counsellors
        /*if(is_null($displayData['RMCCourseAndUnivObjs']['universities']))
		{
			$counsellorData = $this->abroadListingCommonLib->checkIfUniversityHasCounsellor(array_keys($displayData['courseAndUnivObjs']['universities']));
		}
		else
        {
			$counsellorData = $this->abroadListingCommonLib->checkIfUniversityHasCounsellor(array_unique(array_merge(array_keys($displayData['courseAndUnivObjs']['universities']),array_keys($displayData['RMCCourseAndUnivObjs']['universities']))));
		}
        $displayData['counsellorData'] = $counsellorData;
*/
        if( $validity != "false")
        {
            $displayData['userId'] = $validity[0]['userid'];
        }
        //this is to load rate my chance button on the category page tuples if the course is a shiksha apply course and mapped to a rmcCounsellor as well
        $displayData['rateMyChanceCtlr'] = Modules::load('rateMyChances/rateMyChances');

        if($displayData['validateuser'] != "false")
        {
            $shikshaApplyLib = $this->load->library('rateMyChances/ShikshaApplyCommonLib');
            $displayData['userRmcCourses'] = $shikshaApplyLib->getUserRmcRatings($displayData['validateuser'][0]['userid']);
        }
        else
        {
            $displayData['userRmcCourses'] = array();
        }
        $this->_getCounsellorRatingComments($displayData);
        $displayData['rmcTupleToFocus'] = $this->input->get('notification');
        $displayData['showShortlistTab'] = (integer)$this->input->get('shortlistTab');
        $pageIdentity='savedCoursesPage';
        $this->_prepareTrackingData($displayData,$pageIdentity);
        $this->compareCourseLib = $this->load->library('studyAbroadCommon/compareCoursesLib');
        $displayData['userComparedCourses'] = $this->compareCourseLib->getUserComparedCourses();
        $displayData['compareCookiePageTitle'] = "saved courses";
        $displayData['compareOverlayTrackingKeyId'] = 590;
        $displayData['compareButtonTrackingId'] = 658;
        $this->load->view('categoryList/abroad/abroadShortListedCourses',$displayData);
    }

    private function _getCounsellorRatingComments(& $displayData){
        if($displayData['validateuser'] === "false"){
            return ;
        }
        $this->abroadCategoryPageLib = $this->load->library("categoryList/AbroadCategoryPageLib");
        $courseIds = array_keys($displayData['RMCCourseAndUnivObjs']['courses']);
        $data = $this->abroadCategoryPageLib->getCounsellorRatingComments($courseIds,$displayData['validateuser'][0]['userid']);
        $displayData['courseRatingComments'] = $data;
    }

    /*
	 * function to load more RMC courses
	 */
    public function getRMCCourses($limitOffset = 0)
    {
        $displayData['validateuser'] 		= $this->checkUserValidation();
        if($displayData['validateuser'] != 'false')
        {
            if(!$this->abroadListingCommonLib){
                $this->abroadListingCommonLib 	= $this->load->library ( 'listing/AbroadListingCommonLib' );
            }
            $shikshaApplyCommonLib				= $this->load->library('rateMyChances/ShikshaApplyCommonLib');
            $displayData['userId'] = $displayData['validateuser'][0]['userid'];
            // also get list of shortlisted courses
            $shortlistListingLib 		= $this->load->library('listing/ShortlistListingLib');
            $displayData['userShortlistedCourseIds'] = $shortlistListingLib->fetchIfUserHasShortListedCourses(array('userId'=>$displayData['userId']));
            // get RMC courses on which current user has made response
            $displayData['RMCCourseAndUnivObjs'] = $shikshaApplyCommonLib->getRMCCoursesAndUniversitiesByUser($displayData['userId'],$limitOffset);
            /*$counsellorData = $this->abroadListingCommonLib->checkIfUniversityHasCounsellor(array_keys($displayData['RMCCourseAndUnivObjs']['universities']));
            $displayData['counsellorData'] = $counsellorData;*/
            $displayData['catPageTitle'] = $this->security->xss_clean($this->input->post('currentPageTitle'));
        }

        //this is to load rate my chance button on the category page tuples if the course is a shiksha apply course and mapped to a rmcCounsellor as well
        $displayData['rateMyChanceCtlr'] = Modules::load('rateMyChances/rateMyChances');
        if($displayData['validateuser'] != "false")
        {
            $shikshaApplyLib = $this->load->library('rateMyChances/ShikshaApplyCommonLib');
            $displayData['userRmcCourses'] = $shikshaApplyLib->getUserRmcRatings($displayData['validateuser'][0]['userid']);
        }
        else{
            $displayData['userRmcCourses'] = array();
        }
        $this->_getCounsellorRatingComments($displayData);
        //tracking
        $pageIdentity= $this->security->xss_clean($this->input->post('pageIdentity'));
        $this->_prepareTrackingData($displayData,$pageIdentity);
        //loadBeaconTracker($displayData['beaconTrackData']);
        $this->compareCourseLib = $this->load->library('studyAbroadCommon/compareCoursesLib');
        $displayData['userComparedCourses'] = $this->compareCourseLib->getUserComparedCourses();
        $this->load->view("/categoryList/abroad/widget/rateMyChanceTuples", $displayData);
    }

    /*
    *  This is the main function for shortList page
    */
    public function getShortlistedCourses($rowIdOfLastTuple)
    {
        $data['rowIdOfLastTuple'] 			= $rowIdOfLastTuple;
        $displayData['noOfResultPerPage'] 	= $data['noOfResultPerPage'] = 10;//No Of result for per page load in shortlist section

        $validity = $this->checkUserValidation ();
        if (! (($validity == "false") || ($validity == ""))) {
            $displayData['userId'] = $data ['userId'] = $validity [0] ['userid'];
        }
        $displayData['validateuser'] = $validity;
        $this->abroadListingCommonLib = $this->load->library ( 'listing/AbroadListingCommonLib' );
        if($displayData['userId']){
            // for rmc courses
            $shikshaApplyCommonLib	= $this->load->library('rateMyChances/ShikshaApplyCommonLib');
            // get RMC courses on which current user has made response
            $displayData['RMCCourseAndUnivObjs'] = $shikshaApplyCommonLib->getRMCCoursesAndUniversitiesByUser($displayData['userId']);
        }
        $shortlistListingLib = $this->load->library ( 'listing/ShortlistListingLib' );
        $displayData['courseAndUnivObjs'] = $shortlistListingLib->getShortlistedCoursesDetail ($data);
        $displayData['userShortlistedCourseIds'] = $shortlistListingLib->fetchIfUserHasShortListedCourses($data);
        // get subcat of courses:
        if(is_null($displayData['RMCCourseAndUnivObjs']['courses']))
        {
            $allCourses = array_unique(array_keys($displayData['courseAndUnivObjs']['courses']));
        }else{
            $allCourses = array_unique(array_merge(array_keys($displayData['courseAndUnivObjs']['courses']),array_keys($displayData['RMCCourseAndUnivObjs']['courses'])));
        }
        if(count($allCourses)>0){
            $displayData['categoryData'] = $this->abroadListingCommonLib->getCategoryOfAbroadCourse($allCourses);
        }
        $displayData['catPageTitle'] = $this->security->xss_clean($this->input->post('currentPageTitle'));
        /*		if(is_null($displayData['RMCCourseAndUnivObjs']['universities']))
                {
                    $counsellorData = $this->abroadListingCommonLib->checkIfUniversityHasCounsellor(array_keys($displayData['courseAndUnivObjs']['universities']));
                }
                else{
                    $counsellorData = $this->abroadListingCommonLib->checkIfUniversityHasCounsellor(array_unique(array_merge(array_keys($displayData['courseAndUnivObjs']['universities']),array_keys($displayData['RMCCourseAndUnivObjs']['universities']))));
                }
                $displayData['counsellorData'] = $counsellorData;
        */		 //tracking
        $pageIdentity= $this->security->xss_clean($this->input->post('pageIdentity'));
        $this->_prepareTrackingData($displayData,$pageIdentity);
        //loadBeaconTracker($displayData['beaconTrackData']);
        //this is to load rate my chance button on the category page tuples if the course is a shiksha apply course and mapped to a rmcCounsellor as well
        $displayData['rateMyChanceCtlr'] = Modules::load('rateMyChances/rateMyChances');
        if($displayData['validateuser'] != "false"){
            $shikshaApplyLib = $this->load->library('rateMyChances/ShikshaApplyCommonLib');
            $displayData['userRmcCourses'] = $shikshaApplyLib->getUserRmcRatings($displayData['validateuser'][0]['userid']);
        }else{
            $displayData['userRmcCourses'] = array();
        }

        $this->compareCourseLib = $this->load->library('studyAbroadCommon/compareCoursesLib');
        $displayData['userComparedCourses'] = $this->compareCourseLib->getUserComparedCourses();
        if($rowIdOfLastTuple == "0")
        {
            echo $this->load->view ( 'categoryList/abroad/widget/shortlistMainTable', $displayData );
        }
        else{
            echo $this->load->view ( 'categoryList/abroad/widget/categoryPageShortlistedListings', $displayData );
        }
    }

    public function putShortListCouresFromCookieToDB ($shortlistCourseIdInSignUpProcess,$shortlistCourseIdSourceInSignUpProcess = 'abroadCategoryPage') {
        $validity = $this->checkUserValidation ();
        if (! (($validity == "false") || ($validity == ""))) {
            $data ['userId'] = $validity [0] ['userid'];
            $data ['status'] = 'live';
            $data ['sessionId'] = sessionId ();
            $data ['pageType'] = $shortlistCourseIdSourceInSignUpProcess;
            $data ['shortlistCourseIdInSignUpProcess'] = $shortlistCourseIdInSignUpProcess;
            $data['tracking_keyid'] = $this->security->xss_clean($this->input->post('trackingPageKeyId'));
            $shortlistListingLib = $this->load->library ( 'listing/ShortlistListingLib' );
            echo $shortlistListingLib->putShortListCouresFromCookieToDB ( $data);
        }

    }

    private function _getFilterSelectionOrderData(& $displayData)
    {
        // get filter order
        $filterSelectionOrder = $displayData['categoryPageRequest']->getFilterSelectionOrder();

        // get the present highest order of the selection order
        $highestSelectionOrder = 1;
        $filterSelectionOrderObj = json_decode($filterSelectionOrder);
        foreach( $filterSelectionOrderObj as $filtername=>$filtervalueArr)
        {
            foreach( $filtervalueArr as $filteroptionvalue=>$optionrank)
                $highestSelectionOrder = $optionrank > $highestSelectionOrder ? $optionrank+1 : $highestSelectionOrder;

        }

        $filterSelectionOrder = empty($filterSelectionOrder) ? "{}" : $filterSelectionOrder;

        $displayData["highestSelectionOrder"] = $highestSelectionOrder;
        $displayData["filterSelectionOrder"]  = $filterSelectionOrder;
    }

    /* @param : page key, no of result for a category page
     * @Purpose : track user selected filters
     * @Author : vinay
     *
     */
    public function abroadCategoryPageFiltersTracking($pageKey,$noOfResult){
        $validity = $this->checkUserValidation ();
        $userId = 0;
        if (! (($validity == "false") || ($validity == ""))) {
            $userId = $validity [0] ['userid'];
        }

        $pageKeyData = explode('-',$pageKey);
        $countries = explode(':',$pageKeyData[7]);
        $flterTrackingData = array();
        $filterPageKey 	= "FILTERS-".$pageKey;
        $filterValue 	= $_COOKIE[$filterPageKey];
        $filterValue 	= json_decode($filterValue,true);

        // parse the form string to make the individual variables out of string
        parse_str($filterValue["filterValues"]);

        if(!empty($exam))
            $appliedFilters["exam"] = $exam;
        if(!empty($city))
            $appliedFilters["city"] = $city;
        if(!empty($fee))
            $appliedFilters["fees"] = $fee;
        if(!empty($course))
            $appliedFilters["specialization"] = $course;
        if(!empty($moreopt))
            $appliedFilters["moreoption"] = $moreopt;
        if(!empty($countryList))
            $appliedFilters["country"] = $countryList;
        if(!empty($stateList))
            $appliedFilters["state"] = $stateList;
        if(!empty($cityList))
            $appliedFilters["city"] = $cityList;

        if(!empty($examsScore)){
            foreach($examsScore as $key=>$val){
                if(empty($val)){
                    unset($examsScore[$key]);
                }
            }
        }
        if(!empty($examsMinScore)){
            foreach($examsMinScore as $key=>$val){
                if(empty($val)){
                    unset($examsMinScore[$key]);
                }
            }
        }
        if(!empty($examsScore))
            $appliedFilters["examsScore"] = $examsScore;
        if(!empty($examsMinScore)){
            $appliedFilters["examsMinScore"] = $examsMinScore;
        }

        // If the request is from abroad mobile site..
        if( (isset($_COOKIE['ci_mobile']) && $_COOKIE['ci_mobile'] == "mobile") && !(isset($_COOKIE['user_force_cookie']) && $_COOKIE['user_force_cookie'] == "YES") ){
            $isMobileSiteRequest = '1';
        } else {
            $isMobileSiteRequest = '0';
        }

        foreach($appliedFilters as $filterName => $filterValues) {

            foreach($filterValues as $fiterValue) {
                $filterData = array();
                $filterData['sessionId'] = sessionId();
                $filterData['userId'] = $userId;
                $filterData['filter'] = $filterName;
                $filterData['value'] = $filterName == "moreoption" ? $GLOBALS['MORE_OPTIONS'][$fiterValue] : $fiterValue;
                $filterData['categoryId'] = $pageKeyData[1];
                $filterData['subCategoryId'] = $pageKeyData[2];
                $filterData['LDBCourseId'] = $pageKeyData[3];
                $filterData['courseLevel'] = $pageKeyData[4];
                $filterData['cityId'] = $pageKeyData[5];
                $filterData['stateId'] = $pageKeyData[6];
                foreach($countries as $key => $countryId) {
                    $filterData['countryId'.($key+1)] = $countryId;
                    if(($key+1) == 3)
                        break;
                }
                $filterData['resultCount'] = $noOfResult;
                $filterData['isMobileSiteRequest'] = $isMobileSiteRequest;
                $flterTrackingData[] = $filterData;
            }

        }
        if(!empty($flterTrackingData)) {
            $this->load->model('categoryList/abroadcategorypagemodel', '', TRUE);
            $abroadcategorypagemodel = new abroadcategorypagemodel();
            $abroadcategorypagemodel->abroadCategoryPageFiltersTracking($flterTrackingData);
            echo "DONE";
        }
    }

    /*
     * Function to compute counsellor data for Universities
     */
    public function getCounsellorDataForUniversities($universities = array(),$snapshotUniversities = array()){
        // Collect universityIDs for getting counsellorData
        $universitiesIds = array();
        foreach($universities as $universityObj)
        {
            $universitiesIds[] = $universityObj->getId();
        }
        foreach($snapshotUniversities as $universityObj)
        {
            $universitiesIds[] = $universityObj->getId();
        }
        array_unique($universitiesIds);
        $counsellorData = $this->abroadListingCommonLib->checkIfUniversityHasCounsellor($universitiesIds);
        return $counsellorData;
    }

    public function getConsultantDataForUniversities($universities = array()/*,$snapshotUniversities = array()*/)
    {
        // Collect universityIDs for getting consultantData
        $universitiesIds = array();
        foreach($universities as $universityObj)
        {
            $universitiesIds[] = $universityObj->getId();
        }
        /*
        foreach($snapshotUniversities as $universityObj)
        {
            $universitiesIds[] = $universityObj->getId();
        }*/
        array_unique($universitiesIds);
        $consultantData = $this->abroadListingCommonLib->checkIfUniversityHasConsultants($universitiesIds);
        return $consultantData;
    }

    private function redirectIfObsoleteCatPage($request){
        if($request->isAJAXCall()){
            return;
        }

        $catPageRequestValidateLib 		= $this->load->library('AbroadCategoryPageRequestValidations');
        switch ($request->getCourseLevel()){
            case 'masters'  :	$catPageRequestValidateLib->redirectIfObsoleteCatPageToMBA($request,$this->categoryRepository,$this->abroadCategoryPageLib);
                $catPageRequestValidateLib->redirectIfObsoleteCatPageToMS($request,$this->categoryRepository,$this->abroadCategoryPageLib);
                break;
            case 'bachelors':   $catPageRequestValidateLib->redirectIfObsoleteCatPageToBtech($request,$this->categoryRepository,$this->abroadCategoryPageLib);
                break;
            default         :   break;
        }
        if($request->getLDBCourseId() == DESIRED_COURSE_BTECH){
            $catPageRequestValidateLib->redirectIfObsoleteCatPageToSubCatPage($request);
        }
    }

    private function _checkMergedSubcategory($subCategoryId){
        $this->load->config('studyAbroadRedirectionConfig');
        $redirectionIds = $this->config->item("oldSubcategoryToNewSubscategoryMappings");
        if(!empty($redirectionIds[$subCategoryId])){
            return $redirectionIds[$subCategoryId];
        }
        return $subCategoryId;
    }
    /*
	*Revised heading of Be Btech Category Pages
	*/
    private function _getBMSBannerCategoryIdforDesiredCourseCategoryPages($categoryPageRequest,$displayData){

        $filters = $categoryPageRequest->getAppliedFilters();
        if(count($filters['specialization'])>0){

            $categoryDetails = $this->categoryRepository->findMultiple($filters['specialization']);

            $prevCategoryId = '';
            foreach($categoryDetails as $key=>$catObj){
                if($prevCategoryId !='' && $prevCategoryId != $catObj->getParentId()){
                    return -1;
                }else{
                    $prevCategoryId = intval($catObj->getParentId());
                }
            }
            $subCategoriesCount = $this->categoryRepository->getSubCategories($prevCategoryId,'newAbroad');
            $subCategoryArray = array_map(function($key){return $key->getId();},$subCategoriesCount);
            $remainingSubCategory = array_diff($subCategoryArray,$filters['specialization']);
            $categoryNotSelected = array_intersect(array_keys($displayData['userAppliedSpecializationForFilters']),array_values($remainingSubCategory));
            if(!empty($categoryNotSelected)){
                return -1;
            }

        }
        if(in_array($prevCategoryId,array(CATEGORY_ENGINEERING, CATEGORY_COMPUTERS, CATEGORY_SCIENCE))){
            return $prevCategoryId;
        }else{
            return -1;
        }

    }

    public function setBTechCatPageTitle($subCategoryName ,$subCategoryId,$countryName)
    {
        $title = $subCategoryName." Colleges in ".$countryName;
        /*for other specializations category with boardId 268*/
        if($subCategoryId==268)  /*resetting the description and title to handle other specialization case*/
        {
            $title = "Other Engineering Specializations Colleges in ".$countryName;
        }
        /*for IT and networking subcategory with boardId 275*/
        else if($subCategoryId == 275)
        {
            $title = "IT and Networking Courses in ".$countryName;
        }
        return $title;
    }

    /*function to append number of colleges in meta description of LDB pages according to SEO requirements*/
    public function appendCollegeCountToMeta($request,$collegeCount,$seoData,$categoryRepo)
    {
        $desc = $seoData['description'];
       /* $subCategoryId 	=$request->getSubCategoryId();
        if($subCategoryId!=1)
        {
            $subCategory  	= $categoryRepo->find($subCategoryId);
            $categoryId 	= $subCategory->getParentId();
        }
        $msArray = array(CATEGORY_ENGINEERING,CATEGORY_COMPUTERS, CATEGORY_SCIENCE);
        $btechArray = array(CATEGORY_ENGINEERING,CATEGORY_COMPUTERS);
        if($request->getLDBCourseId()==DESIRED_COURSE_MS && $subCategoryId!=1 && in_array($categoryId, $msArray)==true)  //MS subcategory Page
        {
            $desc = $collegeCount." ".$desc;
        }
        else if($request->getLDBCourseId()==DESIRED_COURSE_BTECH || $request->getLDBCourseId()==DESIRED_COURSE_MBA)
        {
            $desc = str_replace("{universityCount}",$collegeCount,$desc);
        }

        //Bachelors of Computer page animation and design page
        else if($request->getCourseLevel()=="bachelors" &&  $subCategoryId==276 && $categoryId==CATEGORY_COMPUTERS )
        {
            $desc = $collegeCount." ".$desc;
        }*/

        $desc = str_replace("{universityCount}",$collegeCount,$desc);

        return $desc;
    }

    private function checkAndClearCookieIfReferralNotOfSamePage(AbroadCategoryPageRequest $request){
        if($_COOKIE['homepageRedirect'] == 'true') // cookie when redirected from home page
        {
            // unset it
            setcookie('homepageRedirect', NULL, -1, '/', COOKIEDOMAIN);
            return;
        }
        else if($request->getPageNumberForPagination() == 1 && !($request->isAJAXCall()) && !($request->isSortAJAXCall()))
        {
            $refererUrlArray = explode('-', $_SERVER['HTTP_REFERER']);
            $currentUrlArray = explode('-', $request->getURL());
            $urlDiffArray    = array_diff($refererUrlArray,$currentUrlArray);
            $firstElementOfDiff = reset($urlDiffArray);
            if(isset($_COOKIE['pageRefreshCookie'])){
                setcookie('pageRefreshCookie', NULL, -1, '/', COOKIEDOMAIN);
                return;
            }else{
                //if(!(count($urlDiffArray) == 1 && $firstElementOfDiff > 0) || count($urlDiffArray) == 0){ wtf!
                if(count($urlDiffArray) != 0 && reset($refererUrlArray) != ''){
                    $pageKey = $request->getPageKey();
                    setcookie('FILTERS-'.$pageKey, NULL, -1, '/', COOKIEDOMAIN);
                    unset($_COOKIE['FILTERS-'.$pageKey]);
                }
            }
        }
    }

    public function getConsultantDataForTuples(){
        $courseIds = $this->security->xss_clean($this->input->post("courseIds"));
        $courseIds = json_decode(base64_decode($courseIds));
        $courseIds = array_filter($courseIds,is_numeric);
        $this->load->builder('ListingBuilder','listing');
        $listingBuilder = new ListingBuilder;
        $abroadCourseRepository = $listingBuilder->getAbroadCourseRepository();
        if(empty($courseIds)){return;}
        $courseObjs = $abroadCourseRepository->findMultiple($courseIds);
        $views = array();
        $abroadListingCommonLib = $this->load->library('listing/AbroadListingCommonLib');
        foreach($courseObjs as $courseId => $courseObj){
            $displayData = $abroadListingCommonLib->getConsultantDataForTuples($courseObj);
            if(!empty($displayData['consultantData'])){
                $displayData['courseObj'] = $courseObj;
                $views[$courseId] = $this->load->view("categoryList/abroad/widget/categoryPageListingConsultants",$displayData,true);
            }
        }
        echo json_encode($views);
    }

    /*  This function checks the current page and prepares the data for Category Pages STATIC Exam Guide Widget also for old 301 redirected pages
    *   param : Category Page Request Object
    *   Logic followed : Only 2 exams shown*/
    public function prepareExamGuideWidgetData($request,$abroadCategoryPageLib,$categoryRepository)
    {

        $data = $abroadCategoryPageLib->prepareExamGuideWidgetData($request,$categoryRepository);

        //resize image
        for($i=0;$i<2;$i++)
        {
            $data[$i]['imageUrl']=str_replace('_s','_172x115', $data[$i]['imageUrl']);
        }
        return $data;
    }

    /*This is used to prepare the title of the exam widget shown on category pages as per requirement*/
    public function getExamWidgetTitle($abroadCategoryPageLib,$categoryPageRequest,$categoryRepository)
    {
        $title = $abroadCategoryPageLib->prepareExamWidgetTitle($abroadCategoryPageLib,$categoryPageRequest,$categoryRepository);
        return $title;
    }
    /*
     * get post data for filter
     */
    public function getRequestDataForFilters()
    {
        $countryIds 	= $this->security->xss_clean($this->input->post('countryIds'));
        $ldbCourseId 	= $this->security->xss_clean($this->input->post('ldbCourseId'));
        $categoryId 	= $this->security->xss_clean($this->input->post('categoryId'));
        $subCategoryId 	= $this->security->xss_clean($this->input->post('subCategoryId'));
        $courseLevel 	= $this->security->xss_clean($this->input->post('courseLevel'));
        $requestData 	= array();
        if($countryIds)
        {
            $requestData['countryId'] = $countryIds;
        }
        if($ldbCourseId)
        {
            $requestData['LDBCourseId'] = $ldbCourseId;
        }
        if($categoryId)
        {
            $requestData['categoryId'] = $categoryId;
            $requestData['courseLevel'] = $courseLevel;
        }
        if($subCategoryId)
        {
            $requestData['subCategoryId'] = 1;//$subCategoryId;
        }
        return $requestData;
    }
    /*
     * gets filters from cache instead of db & performs necessary sorting operations
     */
    private function getFiltersFromCacheForHomepageWidget($categoryPageBuilder,$requestData,$allCall = false)
    {
        $filtersApplicable = array();
        foreach($requestData['countryId'] as $countryId)
        {
            $request = $categoryPageBuilder->getRequest();
            $request->setData(array('countryId'=>array($countryId)));
            $filters = $this->abroadCategoryPageLib->getFiltersFromCache($request->getPageKey());
            if(count($filtersApplicable)==0){
                $filtersApplicable = $filters;
            }
            else{
                $filtersApplicable['exams'] = array_unique(array_merge($filters['exams'], $filtersApplicable['exams']),SORT_REGULAR);
                $filtersApplicable['fees'] = array_unique(array_merge($filters['fees'], $filtersApplicable['fees']));
                //$filtersApplicable['examsScore'] = array();

                foreach($filters['examsScore'] as $key=>$val){

                    if(array_key_exists($key,$filtersApplicable['examsScore'])){
                        $filtersApplicable['examsScore'][$key] = array_unique(array_merge($val, $filtersApplicable['examsScore'][$key]));
                    }else{
                        $filtersApplicable['examsScore'][$key] = $val;
                    }
                    rsort($filtersApplicable['examsScore'][$key]);
                }
            }
        }
        if($filtersApplicable != 'null')
        {
            $examOrder = $this->abroadCategoryPageLib->getExamOrderByCategory($requestData);
            //var_dump($filtersApplicable);
            $exams = $filtersApplicable['exams'];
            $orderedExams = array();
            foreach($exams as $k=>$v)
            {
                $orderedExams[$v['id']] = array('id'=>$v['id'],'exam'=>$v['exam']);
            }
            usort($orderedExams, function ($a,$b) use($examOrder){
                return ($examOrder[$a['exam']] > $examOrder[$b['exam']]?1:-1);
            });
            $filtersApplicable['exams'] = $orderedExams;
            if(!$allCall){
                echo json_encode($filtersApplicable);
            }else{
                return $filtersApplicable;
            }
            $this->benchmark->mark('get_filters_controller_call_end');
            error_log("SRB1 memory usage at get_filters_controller_call_end :: ".(memory_get_usage(TRUE)/1024));
            return true;
        }
        else{
            return false;
        }
    }
    /*
     * get filters for homepage widget based on:
     * - ldb courseid, subcat(Multiple)
     * 		OR
     * 	 parent categ id, level, subcat(Multiple)
     * 	 @params: array having the above values
     */

    public function getFiltersForAllHomepageWidgets(){
        $countryId = (integer)$this->input->post('countryId');
        $abroadCommonLib = $this->load->library('listingPosting/AbroadCommonLib');
        $desiredCourseIds = $abroadCommonLib->getAbroadMainLDBCourses();
        $desiredCourseIds = array_column($desiredCourseIds,"SpecializationId");

        foreach($desiredCourseIds as $ldbCourseId){
            $result[$ldbCourseId] = $this->getFiltersForHomepageWidget($countryId,$ldbCourseId,true);
        }
        echo json_encode($result);
    }

    public function getFiltersForHomepageWidget($countryId,$ldbCourseId,$allCall = false)
    {
        if(empty($countryId) || empty($ldbCourseId)){
            $requestData = $this->getRequestDataForFilters();
        }else{
            $requestData = array();
            $requestData['countryId'] = array($countryId);
            $requestData['LDBCourseId'] = $ldbCourseId;
        }


        $this->abroadCategoryPageLib  = $this->load->library('categoryList/AbroadCategoryPageLib');
        $this->load->builder('AbroadCategoryPageBuilder','categoryList');
        $categoryPageBuilder = new AbroadCategoryPageBuilder($requestData);
        $categoryPage = $categoryPageBuilder->getCategoryPage();
        // if config variable is set to use the cache
        if(USE_CACHE_FOR_SA_HOME_MORE_OPTIONS === TRUE)
        {
            $result = $this->getFiltersFromCacheForHomepageWidget($categoryPageBuilder,$requestData,$allCall);
            if($result)
            {
                return $result;
            }
        }

        $categoryPage->getUniversities();
        $filtersApplicable = $categoryPage->getFilters();


        // get valid exam order
        $examOrder = $this->abroadCategoryPageLib->getExamOrderByCategory($requestData);
        $exams = $filtersApplicable['exams']->getFilteredValues();
        $examsScore = $filtersApplicable['examsScore']->getFilteredValues();
        $examsScoreSorted = array();
        foreach($examsScore as $key=>$val){
            rsort($val,true);
            $examsScoreSorted[$key] = $val;
        }
        $orderedExams = array();
        foreach($exams as $k=>$v)
        {
            $orderedExams[$k] = array('id'=>$k,'exam'=>$v);
        }
        usort($orderedExams, function ($a,$b) use($examOrder){
            return ($examOrder[$a['exam']] > $examOrder[$b['exam']]?1:-1);
        });
        $response = array(
            'exams'         => $orderedExams,
            'examsScore'    => $examsScoreSorted ,
            'fees'          => $filtersApplicable['fees']->getFilteredValues()
        );
        var_dump ($allCall);
        if(!$allCall){
            echo '?';
            // echo json_encode($response);
        }else{
            return $response;
        }
        //$this->benchmark->mark('get_filters_controller_call_end');
        //error_log("SRB1 memory usage at get_filters_controller_call_end :: ".(memory_get_usage(TRUE)/1024));

        //error_log( "SRB1 Total time taken for filters= ".$this->benchmark->elapsed_time('get_filters_controller_call_start', 'get_filters_controller_call_end'));
    }
    /*
     * exam accepted category page (EACP) with & without score
     * @param: $courseName, $examName, url end string containing $examScoreRange & $pageNumber
     */
    public function examAcceptedCategoryPage($courseName="", $examName="", $entityStr="")
    { //ini_set("memory_limit","500M");
        // perform parameter validation
        $params = array(  'courseName'		=>$courseName,
            'examName'          =>$examName,
            'examScoreRange' =>$entityStr);

        $abroadCategoryPageRequestValidationLib = $this->load->library('categoryList/AbroadCategoryPageRequestValidations');
        $paramsForRequest = $abroadCategoryPageRequestValidationLib->validateParametersForExamAcceptedCategoryPage($params);
        $requestData = array();
        if(is_array($paramsForRequest))
        {
            // create category page request using values returned from parsing the url
            if($paramsForRequest['courseInfo']['ldbCourseId'] > 0)
            {
                $requestData['LDBCourseId'] = $paramsForRequest['courseInfo']['ldbCourseId'];
            }
            else if($paramsForRequest['courseInfo']['categoryId']>0)
            {
                $requestData['categoryId'] = $paramsForRequest['courseInfo']['categoryId'];
                $requestData['courseLevel'] = $paramsForRequest['courseInfo']['courseLevel'];
            }
            $requestData['examId'] = $paramsForRequest['examId'];
            $requestData['examName'] = $params['examName'];
            $requestData['examAcceptingCourseName'] = ($params['courseName']=="be-btech"?"BE/Btech" : $params['courseName']);
            $requestData['examScore'] = $paramsForRequest['examScore'];
            $requestData['pageNumber'] = $paramsForRequest['pageNumber'];
            $requestData['countryId'] = array(1); // all countries
        }
        $this->load->builder('AbroadCategoryPageBuilder','categoryList');
        $categoryPageBuilder = new AbroadCategoryPageBuilder($requestData);
        $this->categoryPage($categoryPageBuilder);
    }

    /*
     * redirects old accepting exam category pages to new exam accepted category pages
     */
    public function checkRedirectionToExamAcceptedCategoryPage($requestData)
    {
        if($requestData['acceptedExamName'] == "" ) // only if exam was used in url
        {
            return false;
        }
        $params = array();
        //check course ..
        if($requestData['LDBCourseId'] >0)
        {
            $this->abroadCommonLib = $this->load->library("listingPosting/AbroadCommonLib");
            $LDBCourses = $this->abroadCommonLib->getAbroadMainLDBCourses();
            $params['courseName'] = strtoupper(reset(array_filter(array_map(function($a)use($requestData){
                if($a['SpecializationId'] == $requestData['LDBCourseId']){
                    if($a['SpecializationId']== DESIRED_COURSE_BTECH)
                    { $a['CourseName'] = str_replace('/','-',$a['CourseName']); }
                    return $a['CourseName'];
                }
            },$LDBCourses))));
        }
        else if($requestData['categoryId']==239 && $requestData['courseLevel'] == 'bachelors')
        {
            $params['courseName'] = "bachelors-of-business";
        }
        $params['examName'] = strtoupper($requestData['acceptedExamName']);
        $params['redirectionFromOldAcceptingExamUrls'] = true;
        // load library
        $abroadCategoryPageRequestValidationLib = $this->load->library('categoryList/AbroadCategoryPageRequestValidations');
        $res = $abroadCategoryPageRequestValidationLib->checkAndPerformRedirection($params, false);
        return $res; // false is the only possible value is this case
    }
    /*
     * landing function for dir based cat page urls
     * @params: country, category page str, page identifier
     */
    public function abroadCategoryPage($countryName, $categoryPageStr, $pageIdentifier)
    {
        // parse country/category/course string from url to get course,category,level,subcategory,countryid
        $this->abroadCategoryPageLib  = $this->load->library('categoryList/AbroadCategoryPageLib');
        $requestData = $this->abroadCategoryPageLib->parseEntityStringFromCountryCategoryPageUrl($countryName, $categoryPageStr,$pageIdentifier);
        if($requestData['subCategoryId']>0)
        {
            $requestData['subCategoryId'] = $this->_checkMergedSubcategory($requestData['subCategoryId']);
        }
        $this->load->builder('AbroadCategoryPageBuilder','categoryList');
        $categoryPageBuilder = new AbroadCategoryPageBuilder($requestData);
        $this->categoryPage($categoryPageBuilder);
    }
    /**
     * Purpose : to get only filters for the category page (used for ajax on page load)
     */
    public function categoryPageFilters($categoryPageBuilder, $request)
    {
        // initialize
        //$this->_init($displayData);
        //$request = $categoryPageBuilder->getRequest();

        //$this->redirectIfObsoleteCatPage($request);
        //$this->checkAndClearCookieIfReferralNotOfSamePage($request);

        // exam name in case of exam accepting category pages
        $displayData['acceptedExamName'] = $request->getAcceptedExamName();
        $displayData['acceptedExamId'] = $request->getAcceptedExamId();

        // get filter value of passed as url parameters ...
        $filterWithValidQueryStringFromUrlParams = $this->abroadCategoryPageLib->processUrlParametersToGetFilters($request);

        // ... set into the request obj
        $request->setData(
            array(
                'urlParametersForAppliedFilters'=> $filterWithValidQueryStringFromUrlParams['filtersFromUrlParams'],
                'queryStringFromUrlParameters'  => $filterWithValidQueryStringFromUrlParams['validQueryString']
            )
        );

        //prepare url string for exam accepted page
        if($displayData['acceptedExamName'] != '')
        {   // page url string (not the canonical) for accepting exam page is a bit different from regular category page
            $displayData['urlStringForExamAcceptedPage'] = $request->getUrlStringForExamAcceptedPage();
        }

        $displayData['isAjaxCall'] 		= $request->isAJAXCall();

        //params for sorting
        $displayData['isSortAJAXCall']  = $request->isSortAJAXCall();
        $sortingCriteria 		        = $request->getSortingCriteria();

        if(!empty($sortingCriteria))
        {
            $displayData['sortingCriteria']['sortBy'] = $sortingCriteria['sortBy'];
            $displayData['sortingCriteria']['order'] = $sortingCriteria['params']['order'];
            if($sortingCriteria['params']['exam'] != '')
            {
                $displayData['sortingCriteria']['exam'] = $sortingCriteria['params']['exam'];
            }
        }

        //populate countries
        $this->_populateAbroadCountries($displayData);

        //populate data for layer
        //$displayData['desiredCourses'] = $this->abroadCommonLib->getAbroadMainLDBCourses();
        //$displayData['abroadCategories'] = $this->abroadCommonLib->getAbroadCategories();
        //$displayData['levelOfStudy'] = $this->abroadCommonLib->getAbroadCourseLevelsForFindCollegeWidgets();
        //$displayData['recommendedCountryData'] = $this->abroadListingCommonLib->getRecommendedCountryData($requestClone);
        $requestClone = clone($request);

        //foreach($displayData['desiredCourses'] as $key=>$course)
        //{
        //    $desiredCourseId = $course['SpecializationId'];
        //    $displayData['desiredCourses'][$key]['CourseFullName'] = $GLOBALS["studyAbroadPopularCourses"][$desiredCourseId];
        //}
        $categPage                             	= $categoryPageBuilder->getCategoryPage();
        $categoryPageRequest                    = $categPage->getRequest();
        $displayData['categoryPageRequest'] 	= $categoryPageRequest;
        $displayData['categorypageKey'] 	    = $categoryPageRequest->getPageKey();
        $displayData['isAllCountryPage']	    = $categoryPageRequest->isAllCountryPage() ? 1 : 0;


        //Resultant universities
        $this->benchmark->mark('start_getUniv_getFilter');
        $totalUniversities 				            = $categPage->getUniversities();
        $this->benchmark->mark('end_getUniv_getFilter');
        error_log( "SRB time taken: getUniversities = ".$this->benchmark->elapsed_time('start_getUniv_getFilter', 'end_getUniv_getFilter'));

        $universities 					            = $totalUniversities['universities'];
        $displayData['showScholarshipFilter']       = $totalUniversities['showScholarship'];
        $displayData['onlyCertDiplomaPage'] 		= $totalUniversities['onlyCertDiplomaPage'];

        $this->benchmark->mark('start_formatFilters');
        //get all the filters data
        $filters = $categPage->getFilters();
        $displayData['filters'] = $filters;

        // get all filters applied which will be enabled after user has applied some filters
        $userAppliedfilters = $categPage->getUserAppliedFilters();

        // set all filters computed for individual section
        $displayData['userAppliedfilters'] 			          = $userAppliedfilters;
        //get the user selected filters
        $displayData['appliedFilters']                        = $request->getAppliedFilters();
        $displayData['isZeroResultPage'] = $this->input->post('isZRP');
        $displayData['onlyCertDiplomaPage'] = $this->input->post('certDiplFlag');
        $this->formatFiltersForCategoryPageFromSolr($displayData);
        $this->benchmark->mark('end_formatFilters');
        error_log( "SRB time taken: format filters = ".$this->benchmark->elapsed_time('start_formatFilters', 'end_formatFilters'));

        $this->benchmark->mark('start_remaining');
        $this->_getFilterSelectionOrderData($displayData);

        if($request->isExamCategoryPage()){
            $displayData['minScoreSelectFilled'] = false;
            $displayData['maxScoreSelectFilled'] = false;
        }
        $this->load->view('categoryList/abroad/categoryPageOverview', $displayData);
        $this->benchmark->mark('end_remaining');
        error_log( "SRB time taken: remaining = ".$this->benchmark->elapsed_time('start_remaining', 'end_remaining'));

    }
}
?>
