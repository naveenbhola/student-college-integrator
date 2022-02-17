<?php
/**
 * Description of CategoryListModule
 *
 * @author abhinav
 */
class CategoryPage extends MX_Controller{

    private $abroadCategoryPageLib;
    private $abroadCategoryList;
    private $abroadCommonLib;
    private $categoryRepository;
    private $scholarshipCardCount=8;
    public function __construct() {
        parent::__construct();
        $this->load->builder('AbroadCategoryPageBuilder','categoryList');
        $this->abroadCategoryPageLib  = $this->load->library('categoryList/AbroadCategoryPageLib');
        $this->abroadCategoryList     = modules::load('categoryList/AbroadCategoryList/');
    }
    function _init(){
        //$this->abroadCategoryPageLib  = $this->load->library('categoryList/AbroadCategoryPageLib');
        $this->abroadCommonLib 	= $this->load->library('listingPosting/AbroadCommonLib');
        $this->abroadListingCommonLib   =   $this->load->library('listing/AbroadListingCommonLib');
        $this->abroadListingCacheLib 	= $this->load->library('listing/cache/AbroadListingCache');

        //Loading LocationBuilder for getting locationRepository
        $this->load->builder('LocationBuilder','location');
        $locationBuilder = new LocationBuilder;
        $this->locationRepository = $locationBuilder->getLocationRepository();

        //Loading CategoryBuilder for getting categoryRepository
        //$this->load->builder('CategoryBuilder','categoryList');
        $categoryBuilder = new CategoryBuilder;
        $this->categoryRepository = $categoryBuilder->getCategoryRepository();
    }


    public function categoryCourseLevelPage($entityStr, $entityId) {

        //        $this->abroadCategoryPageLib  = $this->load->library('categoryList/AbroadCategoryPageLib');
        $examName = $this->abroadCategoryPageLib->_getExamNameFromUrlString($entityStr);
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

        //        $this->abroadCategoryList = modules::load('categoryList/AbroadCategoryList/');
        $countryIds = $this->abroadCategoryList->_processDataToGetCountryIds($countryNames);

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
        $requestData['courseLevel'] = $this->abroadCategoryList->_formatCourseLevel($courseLevel);
        $requestData['pageNumber'] = $pageNumber;
        // in case the category page is opened from a url of category page accepting a particular exam score
        $requestData['acceptedExamName'] = $examName;

//        $this->load->builder('AbroadCategoryPageBuilder','categoryList');
        $categoryPageBuilder = new AbroadCategoryPageBuilder($requestData);

        $this->categoryPageMobile($categoryPageBuilder);
    }

    public function categorySubCategoryCourseLevelPage($entityStr, $entityId) {

//        $this->abroadCategoryPageLib  = $this->load->library('categoryList/AbroadCategoryPageLib');
        $examName = $this->abroadCategoryPageLib->_getExamNameFromUrlString($entityStr);
        $rawData = explode("-in-", $entityStr);
        $countryNames = $rawData[2];
        $courseLevel = $rawData[0];
//        $this->abroadCategoryList = modules::load('categoryList/AbroadCategoryList/');
        $countryIds = $this->abroadCategoryList->_processDataToGetCountryIds($countryNames);

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
        $requestData['subCategoryId'] = $subCategoryId;
        $requestData['countryId'] = $countryIds;
        $requestData['courseLevel'] = $this->abroadCategoryList->_formatCourseLevel($courseLevel);
        $requestData['pageNumber'] = $pageNumber;
        // in case the category page is opened from a url of category page accepting a particular exam score
        $requestData['acceptedExamName'] = $examName;
        $this->abroadCategoryList->checkRedirectionToExamAcceptedCategoryPage($requestData);

//        $this->load->builder('AbroadCategoryPageBuilder','categoryList');
        $categoryPageBuilder = new AbroadCategoryPageBuilder($requestData);

        $this->categoryPageMobile($categoryPageBuilder);

    }

    public function ldbCourseSubCategoryPage($entityStr, $entityId) {

//        $this->abroadCategoryPageLib  = $this->load->library('categoryList/AbroadCategoryPageLib');
        $examName = $this->abroadCategoryPageLib->_getExamNameFromUrlString($entityStr);
        $rawData = explode("-from-", $entityStr);
        $countryNames = $rawData[1];
//        $this->abroadCategoryList = modules::load('categoryList/AbroadCategoryList/');
        $categoryPageStrArr = explode('-in-',$rawData[0]);
        $ldbCourseId = $this->abroadCategoryPageLib->getDesiredCourseIdByName($categoryPageStrArr[0]);
        $countryNames = $rawData[1];
        $countryIds = $this->abroadCategoryList->_processDataToGetCountryIds($countryNames);
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

//        $this->load->builder('AbroadCategoryPageBuilder','categoryList');
        $categoryPageBuilder = new AbroadCategoryPageBuilder($requestData);

        $this->categoryPageMobile($categoryPageBuilder);
    }

    public function ldbCoursePage($entityStr, $entityId){
//        $this->abroadCategoryPageLib  = $this->load->library('categoryList/AbroadCategoryPageLib');
        $examName = $this->abroadCategoryPageLib->_getExamNameFromUrlString($entityStr);
        $rawData = explode("-in-", $entityStr);
        $countryNames = $rawData[1];
//        $this->abroadCategoryList = modules::load('categoryList/AbroadCategoryList/');
        $countryIds = $this->abroadCategoryList->_processDataToGetCountryIds($countryNames);
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

//        $this->load->builder('AbroadCategoryPageBuilder','categoryList');
        $categoryPageBuilder = new AbroadCategoryPageBuilder($requestData);

        $this->categoryPageMobile($categoryPageBuilder);
    }


    public function categoryPageMobile(CategoryPageBuilder $categoryPageBuilder)
    {
        $displayData = array();
        $this->_init($displayData);
        $displayData['trackForPages'] = true; //For JSB9 Tracking
        $request = $categoryPageBuilder->getRequest();
        if($request->useSolrToBuildCategoryPage() && $request->isSolrFilterAjaxCall()){
            $this->categoryPageFilters($categoryPageBuilder, $request);
            return ;
        }

        $this->redirectIfObsoleteCatPage($request);
        $this->checkAndClearCookieIfReferralNotOfSamePage($request);

        $request->setData(array('resultsPerPage'        => 15,'includeSnapshotCourse' => false));
        $displayData['acceptedExamName'] = $request->getAcceptedExamName();
        $displayData['acceptedExamId'] = $request->getAcceptedExamId();

        $filterWithValidQueryStringFromUrlParams = $this->abroadCategoryPageLib->processUrlParametersToGetFilters($request);
        $request->setData(
            array(
                'urlParametersForAppliedFilters'=> $filterWithValidQueryStringFromUrlParams['filtersFromUrlParams'],
                'queryStringFromUrlParameters'  => $filterWithValidQueryStringFromUrlParams['validQueryString']
            )
        );

        if($displayData['acceptedExamName'] != '')
        {   // page url string (not the canonical) for accepting exam page is a bit different from regular category page
            $displayData['urlStringForExamAcceptedPage'] = $request->getUrlStringForExamAcceptedPage();
        }

        $displayData['ajaxurl'] = $request->getURL();
        $displayData['isAjaxCall'] = $request->isAJAXCall();

        if(!$request->isAJAXCall()){
            $seoData = $request->getSeoInfo($request->getPageNumberForPagination());  //not required in ajax
            $displayData['canonicalUrl'] = $request->getCanonicalUrl($request->getPageNumberForPagination());
            $displayData['pageUrl'] = $seoData['url'];
            $displayData['title'] = $seoData['title'];
            $displayData['metaDescription'] = $seoData['description'];
        }

        $catPageRequestValidateLib = $this->load->library('categoryList/AbroadCategoryPageRequestValidations');
        $catPageRequestValidateLib->redirectIfInvalidRequestParamsExist($request, $displayData, $this->categoryRepository);

        $displayData['isSortAJAXCall']  = $request->isSortAJAXCall();
//        $sortingCriteria                = $request->getSortingCriteria();

        if(!$request->isAJAXCall()){
            $filterData = $request->getAppliedFilters();
            $filterCountryArray = array();
            if(isset($filterData) && isset($filterData['country']) && is_array($filterData['country']))
            {
                $filterCountryArray  = $filterData['country'];
            }
            $catAndScholarTitle = $this->abroadCategoryList->getCategoryPageTitle($request,$this->locationRepository,$this->categoryRepository,true,$filterCountryArray);
            $displayData['catPageTitle'] =$catAndScholarTitle[0];
            $displayData['scholarshipSliderTitle'] = $catAndScholarTitle[1];

        }

        //Resultant universities
        $categPage = $categoryPageBuilder->getCategoryPage();
        $totalUniversities = $categPage->getUniversities();
        $universities =  $totalUniversities['universities'];
        $displayData['totalTuplesOnPage'] = $categPage->getNumTuples();
        $displayData['resultsPerPage'] = $request->getSnippetsPerPage();

        //get counsellor Data

        $displayData['noOfUniversities'] 	= $categPage->getTotalNoOfUniversities();
        $displayData['noOfCourses']         = $categPage->getTotalNoOfCourses();

        //appending college count in meta description of seo Data for MS and BE BTECH
        if(!$request->isAJAXCall()){
            $displayData['metaDescription'] = $this->appendCollegeCountToMeta($request,$categPage->getTotalNoOfUniversities(),$seoData,$this->categoryRepository); // not required in ajax
        }

        $displayData['resultantUniversityObjects'] = $universities;

        $displayData['isZeroResultPage'] = FALSE;
        if($displayData['noOfCourses'] == 0) {
            $displayData['isZeroResultPage'] = TRUE;
        }
        $displayData['categoryPageRequest'] = $request;
        $displayData['categorypageKey'] = $request->getPageKey();

        $displayData['isAllCountryPage'] = $request->isAllCountryPage() ? 1 : 0;
        $displayData['abroadCategoryPageLib'] = $this->abroadCategoryPageLib;
        $displayData['abroadListingCommonLib'] = $this->abroadListingCommonLib;

        if(!$request->isAJAXCall()){
            $requestClone = clone($request);
            $displayData['recommendedCountryData'] = $this->abroadListingCommonLib->getRecommendedCountryData($requestClone); // not used in ajax
            $scholarshipParams = $this->abroadCategoryPageLib->prepareScholarShipCardApiParams($request,$filterCountryArray);
            if($scholarshipParams !== false)
            {
                $scholarshipCategoryPageLib = $this->load->library('scholarshipCategoryPage/scholarshipCategoryPageLib');
                $displayData['scholarshipCardData'] = $scholarshipCategoryPageLib->getScholarshipTupleDataForInterlinking($scholarshipParams,$this->scholarshipCardCount);
            }
            // fat footer links...
            $displayData['popularSubcats'] = $this->abroadCategoryPageLib->prepareFatFooterLinkData($request,$this->abroadCategoryList,$totalUniversities['popularSubcats']);
        }

        $displayData['userShortlistedCourses'] = $this->fetchIfUserHasShortListedCourses();
        $displayData['userShortlistedCourses'] = $displayData['userShortlistedCourses']['courseIds'];
        //Data for filters

        if(!$request->isAJAXCall() && !$request->useSolrToBuildCategoryPage()){
            $this->prepareCategoryFilterData($categPage,$displayData,$request); // not used in ajax call
        }
        $sortingCriteria = $request->getSortingCriteria();
        $this->_getSortByText($sortingCriteria, $displayData);
        //$displayData['sortByText'] = $sortByText;

        $countryIdForTracking = implode(',', $request->getCountryId());
        if(is_array($displayData['appliedFilters']) && is_array($displayData['appliedFilters']['country'])) {
            $countryIdForTracking = implode(',', $displayData['appliedFilters']['country']);
        }
        //tracking
        $this->_prepareTrackingData($displayData,'categoryPage',0,$request,$countryIdForTracking);
        $displayData['rateMyChanceCtlr'] = Modules::load('rateMyChancePage/rateMyChance');
        $displayData['validateuser'] = $this->checkUserValidation();
        if($displayData['validateuser'] != "false"){
            $shikshaApplyLib = $this->load->library('rateMyChances/ShikshaApplyCommonLib');
            $displayData['userRmcCourses'] = $shikshaApplyLib->getUserRmcRatings($displayData['validateuser'][0]['userid']);
        }else{
            $displayData['userRmcCourses'] = array();
        }

        if(!$request->isAJAXCall()){
            $this->abroadCategoryPageLib->getPrevNextPageLinksForCategoryPage($displayData);
        }

        $displayData['compareCookiePageTitle'] = $displayData['catPageTitle'];
        $displayData['compareLayerTrackingSource'] = 604;
        $displayData['compareButtonTrackingId'] = 661;
        $displayData['loadLazyJSFile'] = true;
        $displayData['loadImagesWithoutLazyCount'] = 2;
        $displayData['dontLoadJQueryUIMin'] = true;
        $this->load->view('categoryPage/categoryPageOverview', $displayData);
    }

    private function _getSortByText($sortingCriteria, &$displayData, $sorterExamName = "")
    {
        $sortByText = 'Sponsored';
        if(!empty($sortingCriteria)){
            $displayData['sortingCriteria']['sortBy'] = $sortingCriteria['sortBy'];
            $displayData['sortingCriteria']['order'] = $sortingCriteria['params']['order'];
            if($sortingCriteria['sortBy'] == 'exam'){
                if($sorterExamName == "")
                {
                    $sorterExamName = $sortingCriteria['params']['exam'];
                }
                if($sortingCriteria['params']['order'] == 'ASC'){
                    $sortByText = 'Low to high '.$sorterExamName.' exam score';
                }else{
                    $sortByText = 'High to low '.$sorterExamName.' exam score';
                }
            }elseif($sortingCriteria['sortBy'] == 'fees'){
                if($sortingCriteria['params']['order'] == 'ASC'){
                    $sortByText = 'Low to high fees';
                }else{
                    $sortByText = 'High to low fees';
                }
            }elseif($sortingCriteria['sortBy'] == 'viewCount'){
                $sortByText = 'By popularity';
            }
        }
        $displayData['sortByText'] = $sortByText;
    }
    private function _prepareTrackingData(&$displayData,$pageIdentity,$pageId,$request,$countryIdForTracking)
    {
        if($request != 0)
        {
            $countryId=$request->getCountryId();
            if($request->getCourseLevel() == "") {
                global $studyAbroadPopularCourseToLevelMapping;
                if(!is_null($studyAbroadPopularCourseToLevelMapping[$request->getLDBCourseId()]))
                {
                    $courseLevel = $studyAbroadPopularCourseToLevelMapping[$request->getLDBCourseId()];
                }
            }else{
                $courseLevel = ucfirst($request->getCourseLevel());

            }
        }

        if($pageIdentity=='categoryPage')
        {
            //$pageId = $request->getLDBCourseId() == 1 ? 0: $request->getLDBCourseId();
            $displayData['googleRemarketingParams'] = array(
                "categoryId" => ($request->getCategoryId() == 1 ? 0 : $request->getCategoryId()),
                "subcategoryId" => ($request->getSubCategoryId() == 1 ? 0 : $request->getSubCategoryId()),
                "desiredCourseId" => ($request->getLDBCourseId() == 1 ? 0: $request->getLDBCourseId()),
                "countryId" => $countryIdForTracking,
                "cityId" => 0
            );
            $displayData['beaconTrackData'] = array(
                'pageIdentifier' => $pageIdentity,
                'pageEntityId' => $pageId,
                'extraData' => array(
                    'categoryId' => ($request->getCategoryId() == 1 ? 0 : $request->getCategoryId()),
                    'subCategoryId' => ($request->getSubCategoryId() == 1 ? 0 : $request->getSubCategoryId()),
                    'LDBCourseId' => ($request->getLDBCourseId() == 1 ? 0: $request->getLDBCourseId()),
                    'countryId' => $countryIdForTracking,
                    'courseLevel' => $courseLevel
                )
            );
        }elseif($pageIdentity == 'countryPage')
        {
            $displayData['beaconTrackData'] = array(
                'pageIdentifier' => $pageIdentity,
                'pageEntityId' => $pageId,
                'extraData' => array(
                    'countryId' => $pageId
                )
            );
        }
        if($pageIdentity == 'countryPage' && $pageId == 1){
            $displayData['beaconTrackData']['extraData']['countryId'] =1;
        }elseif($pageIdentity == 'categoryPage' && $countryId[0] == 1){
            $displayData['beaconTrackData']['extraData']['countryId'] =1;
        }

    }

    function prepareCategoryFilterData($categPage,& $displayData,$request)
    {
        $filters = $categPage->getFilters();
        $displayData['filters'] = $filters;

        // get all filters applied which will be enabled after user has applied some filters
        $userAppliedfilters = $categPage->getUserAppliedFilters();

        // set all filters computed for individual section
        $displayData['userAppliedfilters']                    = $userAppliedfilters;
        //get the user selected filters
        $displayData['appliedFilters']                        = $request->getAppliedFilters();
        if(!$request->useSolrToBuildCategoryPage())
        {
            $displayData['userAppliedfiltersForSpecialization']   = $categPage->getUserAppliedFiltersForSpecialization();
            $displayData['userAppliedfiltersForExam']             = $categPage->getUserAppliedFiltersForExam();
            $displayData['userAppliedfiltersForExamsScore']       = $categPage->getUserAppliedFiltersForExamsScore();
            $displayData['userAppliedfiltersForExamsMinScore']    = $categPage->getUserAppliedFiltersForExamsMinScore();
            $displayData['userAppliedfiltersForLocation']         = $categPage->getUserAppliedFiltersForLocation();
            $displayData['userAppliedfiltersForMoreoption']       = $categPage->getUserAppliedFiltersForMoreoption();
            $displayData['userAppliedfiltersForFees']             = $categPage->getUserAppliedFiltersForFees();
            $this->formatFiltersForCategoryPage($displayData);
        }else{
            $this->formatFiltersForCategoryPageFromSolr($displayData);
        }

        $this->_getFilterSelectionOrderData($displayData);
        $this->prepareFilterCountData($displayData);
    }

    function prepareFilterCountData(& $displayData){
        $appliedFilterCount = 0;
        $individualFilterCount = array();
        if($displayData['appliedFilters']["fees"] >0){
            $feecount = count(array_intersect($displayData['appliedFilters']["fees"],array_keys($displayData['feeRangesForFilter'])));
            if($feecount >0){
                $appliedFilterCount+= 1;
                $individualFilterCount['fees']= 1;
            }else{
                $individualFilterCount['fees']=0;
            }
        }else{
            $individualFilterCount['fees']=0;
        }

        if($displayData['appliedFilters']["exam"] >0){
            $individualFilterCount['exam']= count(array_intersect($displayData['appliedFilters']["exam"],array_keys($displayData['examsForFilter'])));
            $appliedFilterCount+= $individualFilterCount['exam'];
        }else{
            $individualFilterCount['exam'] = 0;
        }

        if($displayData['appliedFilters']["specialization"] >0){

            $individualFilterCount['specialization'] = count(array_intersect($displayData['appliedFilters']["specialization"],array_keys($displayData['specializationForFilters'])));
            $appliedFilterCount+= $individualFilterCount['specialization'];
        }else{
            $individualFilterCount['specialization'] = 0;
        }

        if($displayData['appliedFilters']["moreoption"] >0){
            $individualFilterCount['moreoption'] = count(array_intersect(array_unique($displayData['appliedFilters']["moreoption"]),array_keys($displayData['moreOptionsForFilters'])));
            if($individualFilterCount['moreoption'] == 1 &&
                $displayData['categoryPageRequest']->checkIfCertDiplomaCountryCatPage()
            )
            {
                $individualFilterCount['moreoption']  = 0;
            }
            $appliedFilterCount+= $individualFilterCount['moreoption'];
        }else{
            $individualFilterCount['moreoption'] = 0;
        }

        $locationCount = 0;
        if($displayData['appliedFilters']["country"] >0){
            $individualFilterCount['country'] = count(array_intersect($displayData['appliedFilters']["country"],array_keys($displayData['countriesForFilters'])));
            $appliedFilterCount+= $individualFilterCount['country'];
            $locationCount+= $individualFilterCount['country'];
        }

        if($displayData['appliedFilters']["state"] >0){
            $individualFilterCount['state'] = count(array_intersect($displayData['appliedFilters']["state"],array_keys($displayData['statesForFilters'])));
            $appliedFilterCount+= $individualFilterCount['state'];
            $locationCount+= $individualFilterCount['state'];
        }

        if($displayData['appliedFilters']["city"] >0){
            $individualFilterCount['city'] = count(array_intersect($displayData['appliedFilters']["city"],array_keys($displayData['citiesForFilters'])));
            $appliedFilterCount+= $individualFilterCount['city'];
            $locationCount+= $individualFilterCount['city'];
        }
        $individualFilterCount['location'] = $locationCount;
        $displayData['appliedFilterCount'] = $appliedFilterCount;
        $displayData['individualFilterCount'] = $individualFilterCount;
    }

    function formatFiltersForCategoryPage(& $displayData) {
        // get generated filters for category page
        $feeRangesForFilter 		= $displayData['filters']['fees']->getFilteredValues();
        $examsForFilters 		    = $displayData['filters']['exams']->getFilteredValues();
        $examsScoreForFilters        = $displayData['filters']['examsScore']->getFilteredValues();
        $specializationForFilters 	= $displayData['filters']['coursecategory']->getFilteredValues();
        $moreOptionsForFilters 		= $displayData['filters']['moreoptions']->getFilteredValues();
        $citiesForFilters 		    = $displayData['filters']['city']->getFilteredValues();
        $stateForFilters 		    = $displayData['filters']['state']->getFilteredValues();
        $countryForFilters 		    = $displayData['filters']['country']->getFilteredValues();

        // get filters generated after filters are applied on category page
        if(!empty($displayData['appliedFilters']))
        {
            $userAppliedFeeRangesForFilter 	 = $displayData['userAppliedfiltersForFees']['fees']->getFilteredValues();
            $userAppliedExamsForFilters 	 = $displayData['userAppliedfiltersForExam']['exams']->getFilteredValues();
            $userAppliedSpecializationForFilters = $displayData['userAppliedfiltersForSpecialization']['coursecategory']->getFilteredValues();
            $userAppliedMoreOptionsForFilters 	 = $displayData['userAppliedfiltersForMoreoption']['moreoptions']->getFilteredValues();
            if(!SKIP_ABROAD_CP_FILTERS){
                $userAppliedExamsScoreForFilters = $displayData['userAppliedfiltersForExamsScore']['examsScore']->getFilteredValues();
                $userAppliedCitiesForFilters 	 = $displayData['userAppliedfiltersForLocation']['city']->getFilteredValues();
                $userAppliedStateForFilters 	 = $displayData['userAppliedfiltersForLocation']['state']->getFilteredValues();
                $userAppliedCountryForFilters 	 = $displayData['userAppliedfiltersForLocation']['country']->getFilteredValues();
            }
        }
        else
        {
            $userAppliedFeeRangesForFilter 	 = $displayData['userAppliedfilters']['fees']->getFilteredValues();
            $userAppliedExamsForFilters 	 = $displayData['userAppliedfilters']['exams']->getFilteredValues();
            $userAppliedSpecializationForFilters = $displayData['userAppliedfilters']['coursecategory']->getFilteredValues();
            $userAppliedMoreOptionsForFilters 	 = $displayData['userAppliedfilters']['moreoptions']->getFilteredValues();
            if(!SKIP_ABROAD_CP_FILTERS){
                $userAppliedExamsScoreForFilters = $displayData['userAppliedfilters']['examsScore']->getFilteredValues();
                $userAppliedCitiesForFilters 	 = $displayData['userAppliedfilters']['city']->getFilteredValues();
                $userAppliedStateForFilters 	 = $displayData['userAppliedfilters']['state']->getFilteredValues();
                $userAppliedCountryForFilters 	 = $displayData['userAppliedfilters']['country']->getFilteredValues();
            }
        }

        //Sort all the filters
        asort($feeRangesForFilter);
        //Sorting of exam accoring to custom order depening if its MBA OR BTECH ETC
        $examsForFilters  = $displayData['abroadCategoryPageLib']->sortEligibilityExam($displayData['categoryPageRequest'],array(),$examsForFilters);

        asort($specializationForFilters);//As specialization filters will be sorted by result count
        asort($moreOptionsForFilters);
        asort($citiesForFilters);
        asort($stateForFilters);
        asort($countryForFilters);

        // sort all the user applied filters
        asort($userAppliedFeeRangesForFilter);
        $userAppliedExamsForFilters  = $displayData['abroadCategoryPageLib']->sortEligibilityExam($displayData['categoryPageRequest'],array(),$userAppliedExamsForFilters);
        asort($userAppliedSpecializationForFilters); //As specialization filters will be sorted by result count
        asort($userAppliedMoreOptionsForFilters);
        asort($userAppliedCitiesForFilters);
        asort($userAppliedStateForFilters);
        asort($userAppliedCountryForFilters);

        if(!is_array($userAppliedExamsForFilters))  	$userAppliedExamsForFilters = array();
        if(!is_array($examsForFilters))                 $examsForFilters = array();
        if(!is_array($examsScoreForFilters))            $examsScoreForFilters = array();
        if(!is_array($specializationForFilters))    	$specializationForFilters = array();
        if(!is_array($moreOptionsForFilters))           $moreOptionsForFilters = array();
        if(!is_array($citiesForFilters))           	    $citiesForFilters = array();
        if(!is_array($stateForFilters))           	    $stateForFilters = array();
        if(!is_array($countryForFilters))           	$countryForFilters = array();

        if(!is_array($userAppliedExamsForFilters))  		    $userAppliedExamsForFilters = array();
        if(!is_array($userAppliedExamsScoreForFilters))         $userAppliedExamsScoreForFilters = array();
        if(!is_array($userAppliedSpecializationForFilters))    	$userAppliedSpecializationForFilters = array();
        if(!is_array($userAppliedMoreOptionsForFilters))    	$userAppliedMoreOptionsForFilters = array();
        if(!is_array($userAppliedCitiesForFilters))           	$userAppliedCitiesForFilters = array();
        if(!is_array($userAppliedStateForFilters))           	$userAppliedStateForFilters = array();
        if(!is_array($userAppliedCountryForFilters))           	$userAppliedCountryForFilters = array();

        // code for sorting the enabled filters to the top
        $feeRangesForFilter		= $userAppliedFeeRangesForFilter	+ $feeRangesForFilter;
        $examsForFilters 		= $userAppliedExamsForFilters 		+ $examsForFilters;
        $examsScoreForFilters       = $userAppliedExamsScoreForFilters      + $examsScoreForFilters;
        $specializationForFilters 	= $userAppliedSpecializationForFilters 	+ $specializationForFilters;
        $moreOptionsForFilters 		= $userAppliedMoreOptionsForFilters 	+ $moreOptionsForFilters;
        $citiesForFilters 		= $userAppliedCitiesForFilters 		+ $citiesForFilters;
        $stateForFilters 		= $userAppliedStateForFilters 		+ $stateForFilters;
        $countryForFilters 		= $userAppliedCountryForFilters 	+ $countryForFilters;

        //For the fees filter, send "Any Fees" to the bottom
        if(reset($feeRangesForFilter) == "Any Fees"){
            $feeRangesForFilter = array_slice($feeRangesForFilter,1,count($feeRangesForFilter)-1,true) + array(reset(array_keys($feeRangesForFilter))=>reset($feeRangesForFilter));
        }

        //send them to view
        $displayData['feeRangesForFilter']      = $feeRangesForFilter;
        $displayData['examsForFilter']          = $examsForFilters;
        $displayData['examsScoreForFilter']     = $examsScoreForFilters;
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
        $displayData['userAppliedSpecializationForFilters']= $userAppliedSpecializationForFilters;
        $displayData['userAppliedMoreOptionsForFilters']   = $userAppliedMoreOptionsForFilters;
        $displayData['userAppliedCitiesForFilters']        = $userAppliedCitiesForFilters;
        $displayData['userAppliedStateForFilters']         = $userAppliedStateForFilters;
        $displayData['userAppliedCountryForFilters']       = $userAppliedCountryForFilters;
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

    public function fetchIfUserHasShortListedCourses(){
        $validity = $this->checkUserValidation ();
        $data = array();
        if (! (($validity == "false") || ($validity == ""))) {
            $data ['userId'] = $validity [0] ['userid'];
        }
        $shortlistListingLib = $this->load->library ( 'listing/ShortlistListingLib' );
        return $shortlistListingLib->fetchIfUserHasShortListedCourses ( $data);

    }

    private function redirectIfObsoleteCatPage($request){
        if($request->isAJAXCall()){
            return;
        }
        $catPageRequestValidateLib 		= $this->load->library('categoryList/AbroadCategoryPageRequestValidations');
        switch ($request->getCourseLevel()){
            case 'masters'  :	$catPageRequestValidateLib->redirectIfObsoleteCatPageToMBA($request,$this->categoryRepository,$this->abroadCategoryPageLib);
                $catPageRequestValidateLib->redirectIfObsoleteCatPageToMS($request,$this->categoryRepository,$this->abroadCategoryPageLib);
                break;
            case 'bachelors':   $catPageRequestValidateLib->redirectIfObsoleteCatPageToBtech($request,$this->categoryRepository,$this->abroadCategoryPageLib);
                break;
            default         :   break;
        }
        if($request->getLDBCourseId() == 1510){
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

    private function checkAndClearCookieIfReferralNotOfSamePage(AbroadCategoryPageRequest $request)
    {
        if($request->getPageNumberForPagination() == 1 && !($request->isAJAXCall())){
            $refererUrlArray = explode('-', $_SERVER['HTTP_REFERER']);
            $currentUrlArray = explode('-', $request->getURL());
            $urlDiffArray    = array_diff($refererUrlArray,$currentUrlArray);
            $firstElementOfDiff = reset($urlDiffArray);
            if(isset($_COOKIE['pageRefreshCookie'])){
                setcookie('pageRefreshCookie', NULL, -1, '/', COOKIEDOMAIN);
                return;
            }else{
                //if(!(count($urlDiffArray) == 1 && $firstElementOfDiff > 0) || count($urlDiffArray) == 0){
                if(count($urlDiffArray) != 0 && reset($refererUrlArray) != ''){
                    $pageKey = $request->getPageKey();
                    setcookie('FILTERS-'.$pageKey, NULL, -1, '/', COOKIEDOMAIN);
                    unset($_COOKIE['FILTERS-'.$pageKey]);
                }
            }
        }
    }

    /*function to append number of colleges in meta description of LDB pages according to SEO requirements*/
    public function appendCollegeCountToMeta($request,$collegeCount,$seoData,$categoryRepo)
    {
        $desc = $seoData['description'];
//        $subCategoryId  =$request->getSubCategoryId();
//        if($subCategoryId!=1)
//        {
//            $subCategory    = $categoryRepo->find($subCategoryId);
//            $categoryId     = $subCategory->getParentId();
//        }
////        _p($categoryId);die;
//        $msArray = array('240','241','242');  /*BoardId Engineering-240 ,Computers-241 ,Science-242 for MS pages */
//        $btechArray = array("240","241");
//
//        if($request->getLDBCourseId()==1509 && $subCategoryId!=1 && in_array($categoryId, $msArray)==true)  //MS subcategory Page
//        {
//            $desc = $collegeCount." ".$desc;
//        }
//        else if($request->getLDBCourseId()==1510 || $request->getLDBCourseId()==1508)
//        {
//            $desc = str_replace("{universityCount}",$collegeCount,$desc);
//        }
//        /*Bachelors of Computer page animation and design page*/
//        else if($request->getCourseLevel()=="bachelors" &&  $subCategoryId==276 && $categoryId=='241' )
//        {
//            $desc = $collegeCount." ".$desc;
//        }
        $desc = str_replace("{universityCount}",$collegeCount,$desc);

        return $desc;
    }

    public function getConsultantDataForTuples(){
        $courseIds = $this->input->post("courseIds");
        $courseIds = json_decode(base64_decode($courseIds));

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
                $views[$courseId] = $this->load->view("categoryPage/widgets/categoryPageTupleConsultants",$displayData,true);
            }
        }
        echo json_encode($views);
    }

    public function countryPage($entityStr,$pageNumber){
        if($this->input->is_ajax_request()){ $ajaxCall = true; }
        $this->load->library('responseAbroad/ResponseAbroadLib');
        $this->responseAbroadLib = new ResponseAbroadLib();
        $this->abroadListing = Modules::load('listing/abroadListings');
        $this->load->library('listing/AbroadListingCommonLib');
        $this->abroadListingCommonLib = new AbroadListingCommonLib();

        $countryIdArray = $this->abroadCategoryList->_processDataToGetCountryIds(str_replace("-", "", $entityStr));
        $countryName 	= $this->abroadCategoryList->parsedCountryName;
        if(empty($countryName)){
            $countryName = "Abroad";
        }
        $countryId = $countryIdArray[0];
        if($countryId == "") {
            show_404_abroad();
        }
        $this->load->builder('ListingBuilder','listing');
        $listingBuilder			= new ListingBuilder;
        $abroadUniversityRepository = $listingBuilder->getUniversityRepository();
        $this->abroadCategoryPageLib= $this->load->library('categoryList/AbroadCategoryPageLib');
        $categoryPageRequest		= $this->load->library('categoryList/AbroadCategoryPageRequest');
        $pageSize = 15;
        if(empty($pageNumber)){
            $pageNumber = $this->input->post('pageNumber');
        }
        $pageNumber 			    = str_replace("-", "", $pageNumber);
        if(empty($pageNumber)){
            $pageNumber = 1;
        }else{
            $pageNumber = (integer)$pageNumber;
        }
        $seodata = $categoryPageRequest->getSeoInfoForCountryPage($countryId, $pageNumber);
        if((trim(getCurrentPageURLWithoutQueryParams()) != $seodata["url"]) && !$ajaxCall) {
            redirect($seodata["url"], 'location', 301);
        }
        $displayData['title'] = $seodata['title'];
        $displayData['canonical'] = $seodata['canonical'];
        $displayData['metaDescription'] = "Check {univCount} universities ".$seodata['description'];

        $paginationArr["limitOffset"]	= $pageSize*($pageNumber-1);
        $paginationArr["limitRowCount"]	= $pageSize;
        $paginationArr["pageNumber"]	= $pageNumber;
        $universityList = $this->abroadCategoryPageLib->getAbroadCountryPageDataFromSolr($paginationArr, $countryId);

        if(count($universityList['result']) == 0 && !$ajaxCall){
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

        $universityIds = array();
        $universityCourseCount = array();
        foreach($universityList['result'] as $universityData){
            $universityIds[] = $universityData['university_id'];
            //$universityCourseCount[$universityData['university_id']] = count(explode(',', $universityData['courseList']));
            $universityCourseCount[$universityData['university_id']]  = $universityData['courseCount'];
        }
        $universityIds = array_unique($universityIds);

        $universityObjArray = $abroadUniversityRepository->findMultiple($universityIds);
        $universityDataResult = array();
        foreach($universityObjArray as $universityObj){
            $universityDataResult[$universityObj->getId()]['universityName'] = $universityObj->getName();
            $universityDataResult[$universityObj->getId()]['url'] = $universityObj->getURL();
            $location = $universityObj->getLocation();
            $universityDataResult[$universityObj->getId()]['city'] = $location->getCity()->getName();
            $universityDataResult[$universityObj->getId()]['state'] = $location->getState()->getName();
            $universityDataResult[$universityObj->getId()]['courseCount'] = $universityCourseCount[$universityObj->getId()];
            $universityDataResult[$universityObj->getId()]['instituteType1'] = $universityObj->getTypeOfInstitute();
            $universityDataResult[$universityObj->getId()]['instituteType2'] = $universityObj->getTypeofInstitute2();
            $universityDataResult[$universityObj->getId()]['establishYear'] = $universityObj->getEstablishedYear();
            $universityDataResult[$universityObj->getId()]['brochureDataObj'] = array(
                'universityId' => $universityObj->getId(),
                'universityName' => $universityObj->getName(),
                'sourcePage' => 'country_university',
                'mobile' => true,
                'widget' => 'tuple',
                'trackingPageKeyId' => 1763,
                'destinationCountryId'  => $universityObj->getLocation()->getCountry()->getId(),
                'destinationCountryName'=> $universityObj->getLocation()->getCountry()->getName()
            );
        }

        $displayData['tupleData'] = $universityDataResult;
        if($ajaxCall){
            $resultHtml = '';
            foreach($displayData['tupleData'] as $data){
                $resultHtml .= $this->load->view("categoryPage/widgets/countryPageTuple",$data,true);
            }
            echo $resultHtml;
            return ;
        }

        $displayData['countryName'] = $countryName;
        $displayData['totalCount'] = $universityList['totalCount'];
        $displayData['metaDescription'] = str_replace('{univCount}',$displayData['totalCount'],$displayData['metaDescription']);
        $displayData['trackForPages'] = true; //For JSB9 Tracking
        $displayData['pageNumber'] = $pageNumber;

        //tracking
        $this->_prepareTrackingData($displayData,'countryPage',$countryId);
        $popularCoursesData = $this->abroadCategoryPageLib->getPopularCoursesForQuickLinks($countryId);
        $displayData['popularCourseData'] = $popularCoursesData;
        $displayData['pageSize'] = $pageSize;
        $this->load->view("categoryPage/countryPageOverview",$displayData);

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
    /*
     * landing function for dir based cat page urls
     * @params: country, category page str, page identifier
     */
    public function abroadCategoryPage($countryName, $categoryPageStr, $pageIdentifier)
    {
//		$this->abroadCategoryList = modules::load('categoryList/AbroadCategoryList/');
        // parse country/category/course string from url to get course,category,level,subcategory,countryid
        $this->abroadCategoryPageLib  = $this->load->library('categoryList/AbroadCategoryPageLib');
        $requestData = $this->abroadCategoryPageLib->parseEntityStringFromCountryCategoryPageUrl($countryName, $categoryPageStr,$pageIdentifier);
        //_p($requestData);
        if($requestData['subCategoryId']>0)
        {
            $requestData['subCategoryId'] = $this->_checkMergedSubcategory($requestData['subCategoryId']);
        }
//		$this->load->builder('AbroadCategoryPageBuilder','categoryList');
        $categoryPageBuilder = new AbroadCategoryPageBuilder($requestData);
        $this->categoryPageMobile($categoryPageBuilder);
    }

    public function categoryPageFilters($categoryPageBuilder, $request)
    {
        $displayData['acceptedExamName'] = $request->getAcceptedExamName();
        $displayData['acceptedExamId'] = $request->getAcceptedExamId();
        $filterWithValidQueryStringFromUrlParams = $this->abroadCategoryPageLib->processUrlParametersToGetFilters($request);

        $request->setData(
            array(
                'urlParametersForAppliedFilters'=> $filterWithValidQueryStringFromUrlParams['filtersFromUrlParams'],
                'queryStringFromUrlParameters'  => $filterWithValidQueryStringFromUrlParams['validQueryString']
            )
        );

        if($displayData['acceptedExamName'] != ''){
            $displayData['urlStringForExamAcceptedPage'] = $request->getUrlStringForExamAcceptedPage();
        }

        $displayData['isAjaxCall']      = $request->isAJAXCall();

        $displayData['isSortAJAXCall']  = $request->isSortAJAXCall();
        $sortingCriteria                = $request->getSortingCriteria();

        if(!empty($sortingCriteria)){
            $displayData['sortingCriteria']['sortBy'] = $sortingCriteria['sortBy'];
            $displayData['sortingCriteria']['order'] = $sortingCriteria['params']['order'];
            if($sortingCriteria['params']['exam'] != '')
            {
                $displayData['sortingCriteria']['exam'] = $sortingCriteria['params']['exam'];
            }
        }
        $this->_populateAbroadCountries($displayData);
        $requestClone = clone($request);
        $categPage                              = $categoryPageBuilder->getCategoryPage();
        $categoryPageRequest                    = $categPage->getRequest();
        $displayData['categoryPageRequest']     = $categoryPageRequest;
        $displayData['categorypageKey']         = $categoryPageRequest->getPageKey();
        $displayData['isAllCountryPage']        = $categoryPageRequest->isAllCountryPage() ? 1 : 0;
        $totalUniversities                          = $categPage->getUniversities();
        $universities                               = $totalUniversities['universities'];
        $displayData['showScholarshipFilter']       = $totalUniversities['showScholarship'];
        $displayData['onlyCertDiplomaPage']         = $totalUniversities['onlyCertDiplomaPage'];
        $filters = $categPage->getFilters();
        $displayData['filters'] = $filters;

        $userAppliedfilters = $categPage->getUserAppliedFilters();

        $displayData['userAppliedfilters']                    = $userAppliedfilters;
        $displayData['appliedFilters']                        = $request->getAppliedFilters();
        $this->formatFiltersForCategoryPageFromSolr($displayData);
        $this->prepareFilterCountData($displayData);
        $this->_getFilterSelectionOrderData($displayData);
        foreach ($displayData['examsForFilter'] as $examId=>$exam){
            if(in_array($exam, $displayData['userAppliedExamsForFilters']) && in_array($examId, $displayData['appliedFilters']["exam"])){
                $sorterExamName = $displayData['examsForFilter'][$examId];
                break;
            }elseif($sorterExamName == '' && in_array($exam, $displayData['userAppliedExamsForFilters'])){
                $sorterExamName = $displayData['examsForFilter'][$examId];
            }
        }
        $this->_getSortByText($sortingCriteria, $displayData, $sorterExamName);
        $displayData['isZeroResultPage'] = $this->input->post('isZRP');
        if($request->isExamCategoryPage()){
            $displayData['minScoreSelectFilled'] = false;
            $displayData['maxScoreSelectFilled'] = false;
        }
        $response = array();
        $response['filters'] = $this->load->view('categoryPage/categoryPageFilters', $displayData, true);
        $response['appliedFilterCount'] = $displayData['appliedFilterCount'];
        $response['sortBy'] = $this->load->view('categoryPage/widgets/categoryPageSorter', $displayData, true);
        $response['sortByText'] = $displayData['sortByText'];
        //$this->load->view('categoryPage/categoryPageFilters', $displayData); // This needs work
        echo json_encode($response);
    }


    function formatFiltersForCategoryPageFromSolr(& $displayData)
    {
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
        $this->categoryPageMobile($categoryPageBuilder);
    }
}
