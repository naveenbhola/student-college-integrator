<?php

class CategoryList extends MX_Controller
{
    function categoryPage($params, $newUrlFlag = false){
    	$startTime = microtime(true);
		$this->load->builder('CategoryPageBuilder','categoryList');
		$categoryPageBuilder = new CategoryPageBuilder($params, $newUrlFlag);
		$request = $categoryPageBuilder->getRequest();
		
		$params = array();
		$params['category_id'] = $request->getCategoryId();
		$params['subcategory_id'] = $request->getSubCategoryId();
		$params['ldb_course_id'] = $request->getLDBCourseId();
		$params['exam'] = $request->getExamName();
		$params['fees'] = $request->getFeesValue();
		if($params['subcategory_id'] == 27) { // 'Online MBA' pages to be redirected to all India
			$params['city_id'] = 1;
			$params['state_id'] = 1;
		} else {
			$params['city_id'] = $request->getCityId();
			$params['state_id'] = $request->getStateId();
		}
		
		$nationalCategoryPageLib = $this->load->library('nationalCategoryList/NationalCategoryPageLib');
		$nationalCategoryPageLib->redirectOldCategoryPages($params);
		die;
		
		ini_set("memory_limit","512M");
		define("PAGETRACK_BEACON_FLAG",false);
		$this->load->builder('CategoryBuilder','categoryList');
		$this->load->builder('LDBCourseBuilder','LDB');
		$this->load->builder('LocationBuilder','location');
		$this->load->library('common/Personalizer');
		$this->load->library('category_list_client');
		$this->config->load('categoryPageConfig');
		$this->load->library('listing/cache/ListingCache');
		$this->load->library('categoryList/AbroadCategoryPageRequest'); // For Generating Abroad URLS 
		$this->listingCache = new ListingCache;
		
		if($newUrlFlag) {  // 301 redirection for Category Page URLS having SRMEEE (Change for LF-2521)
			$request = $categoryPageBuilder->getRequest();
			if(strtolower($request->getExamName()) == "srmeee") {
				$request->setData(array('examName'=>"SRMJEEE")); 
				redirect($request->getURL(), 'location', 301);
			}
		}
		if($categoryPageBuilder->getRequest()->isStudyAbroadPage())
		{
			$this->redirectToNewAbroadCategoryPage($categoryPageBuilder);
		}
		$this->buildCategoryPage($categoryPageBuilder, $newUrlFlag);
		if(EN_LOG_FLAG) error_log("\narray( section => 'fullpage', timetaken => ".(microtime(true) - $startTime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);
	}
	
	
	// Re-Direct To New Aborad Category Page
	public function redirectToNewAbroadCategoryPage(CategoryPageBuilder $categoryPageBuilder){
	    $abroadCategoryPageRequest = $this->load->library('AbroadCategoryPageRequest');	    
	    $this->load->config('studyAbroadRedirectionConfig');
	    $studyAbroadParentCategoryIdMappings = $this->config->item('studyAbroadParentCategoryIdMappings');
	    $studyAbroadSubcategoryIdMappings = $this->config->item('studyAbroadSubcategoryIdMappings');
	    $studyAbroadRegionIdCountryMappings = $this->config->item('studyAbroadRegionIdCountryMappings');
	    //$studyAbroadDomainCountryMappings = $this->config->item('studyAbroadDomainCountryMappings');
	    $categoryId = $categoryPageBuilder->getRequest()->getCategoryId();
	    $subCategoryId = $categoryPageBuilder->getRequest()->getSubCategoryId();
	    $countryId = $categoryPageBuilder->getRequest()->getCountryId();
	    $regionId = $categoryPageBuilder->getRequest()->getRegionId();
	    if( $categoryId == 1 && $subCategoryId == 1){
			if( $countryId > 2){
				$locationBuilder = new LocationBuilder();
				$locationRepository = $locationBuilder->getLocationRepository();
				$country = $locationRepository->getAbroadCountryByIds(array($countryId));
				if(!empty($country)){
					$url = $abroadCategoryPageRequest->getURLForCountryPage($countryId);
				redirect($url, 'location', 301);
				}
			}else{
				$url = $abroadCategoryPageRequest->getURLForCountryPage($studyAbroadRegionIdCountryMappings[$regionId]);
				redirect($url, 'location', 301);
			}
	    }
	    
	    $data = array();
	    if($categoryPageBuilder->getRequest()->isSubcategoryPage()){
			$data['subCategoryId'] = $studyAbroadSubcategoryIdMappings[$subCategoryId]['id'];
			$data['courseLevel'] = $studyAbroadSubcategoryIdMappings[$subCategoryId]['defaultLevel'];
			if($data['subCategoryId'] == ""){
				$data['subCategoryId'] = 1;
				$data['categoryId'] = $studyAbroadSubcategoryIdMappings[$subCategoryId]['parentId'];
				if($data['categoryId'] == ""){
					$data['categoryId'] = 1;
					$data['LDBCourseId'] = $studyAbroadSubcategoryIdMappings[$subCategoryId]['ldbId'];
				}
			}
	    }
	    
	    if($categoryPageBuilder->getRequest()->isMainCategoryPage()){
			$data['categoryId'] = $studyAbroadParentCategoryIdMappings[$categoryId]['id'];
			$data['subCategoryId'] = 1;
			$data['courseLevel'] = $studyAbroadParentCategoryIdMappings[$categoryId]['defaultLevel'];
			if($data['categoryId'] == ""){
				$data['categoryId'] = 1;
				$data['LDBCourseId'] = $studyAbroadParentCategoryIdMappings[$categoryId]['ldbId'];
			}
	    }
	    
	    if($countryId > 2){
			$locationBuilder = new LocationBuilder();
			$locationRepository = $locationBuilder->getLocationRepository();
			$country = $locationRepository->getAbroadCountryByIds(array($countryId));
			if(!empty($country)){
				$data['countryId'] = array($countryId);
			}
	    }
	    
	    if(!isset($data['countryId']) && $regionId){
			$data['countryId'] = array($studyAbroadRegionIdCountryMappings[$regionId]);
	    }
		
	    $abroadCategoryPageRequest->setData($data);
	    
	    $url = $abroadCategoryPageRequest->getURL();
	    redirect($url, 'location', 301);
	}
	
	private function _validateAndRedirectNONRNRCategoryPageURLS($request) {
	    global $categoryURLPrefixMapping;
		$RNRSubcategories = array_keys($this->config->item('CP_SUB_CATEGORY_NAME_LIST'));
		if(((strpos($_SERVER['HTTP_REFERER'], 'google') !== false) || (strpos($_SERVER['QUERY_STRING'], 'showpopup') !== false)) && ($request->getSubCategoryId() == 23)) {
			return;
		}
		if(!$request->isAJAXCall() 
			&& !in_array($request->getSubCategoryId(), array(56)) //only for engineering
			&& !( $request->getCategoryId() == 2 && $request->getSubCategoryId() == 1 && $request->getLDBCourseId() == 1 ))
		{
		    if($request->getCategoryId() == 3)
		    {
			if($_SERVER['QUERY_STRING'])
			    $userInputURL = $_SERVER['SCRIPT_URI']."?".$_SERVER['QUERY_STRING'];
			else
			    $userInputURL = $_SERVER['SCRIPT_URI'];
			$userInputURL  = trim($userInputURL,"/");
		    }
		    else
		    {
			$userInputURL  = trim($_SERVER['SCRIPT_URI']);
			$userInputURL  = trim($_SERVER['SCRIPT_URI'],"/");
		    }

		    if($request->getCategoryId() == 3  && $request->getSubCategoryId() > 1){
				$request->setNewURLFlag(1);
			}
			$categoryPageRequestURL = $request->getURL($request->getPageNumberForPagination());
			if($userInputURL != $categoryPageRequestURL)
			{
				$url = $categoryPageRequestURL;
				redirect($url, 'location', 301);
			}
		}
	}
	
	private function _redirectFullTimeMBASubDomainToMBADomain($request){
		global $categoryURLPrefixMapping;
		$url = false;
		if($request->getCategoryId() == 3 && $request->getSubCategoryId() == 23 && $request->getLDBCourseId() == 1 && $request->getCityId() == 1 && $request->getStateId() == 1 && $request->getCountryId() == 2 && $request->getExamName() && $request->getFeesValue() && $request->getAffiliationName() && $request->getOtherExamScoreData() && (!$request->getPageNumberForPagination() || $request->getPageNumberForPagination() < 2) && SHIKSHA_ENV != 'dev') {
			$url = $categoryURLPrefixMapping[$request->getCategoryId()];
		}
		if(!empty($url)){
			redirect($url, 'location', 301);
			exit;
		}
	}
	
	public function buildCategoryPage(CategoryPageBuilder $categoryPageBuilder, $categoryPageTypeFlag = false) {
		$startTime = microtime(true);
		$categoryBuilder 		= new CategoryBuilder;
		$LDBCourseBuilder 		= new LDBCourseBuilder;
		$locationBuilder 		= new LocationBuilder;
		$categoryClient 		= new Category_list_client();
		$request 				= $categoryPageBuilder->getRequest();
		$RNRSubcategories 		= array_keys($this->config->item('CP_SUB_CATEGORY_NAME_LIST'));
		$this->load->helper('url');
		
		//$this->_redirectFullTimeMBASubDomainToMBADomain($request);
		
		// Set cookie for current request for pageKey
		$this->setCookieForCurrentRequest($request);
		
		if($_REQUEST['profiling'] == 1) {
			_p($request->getPageKey());
		}
		$this->_validateAndRedirectNONRNRCategoryPageURLS($request);
	
		$originalPageRequest 	= clone $request;
		$originalPageFilters 	= $originalPageRequest->getAppliedFilters();		
		
		if(!$request->isAJAXCall())
		{
		    $catPageRequestValidateLib = $this->load->library('CategoryPageRequestValidations');
		    $catPageRequestValidateLib->redirectIfInvalidRequestParamsExist($request, $categoryBuilder, $LDBCourseBuilder, $locationBuilder);
		}
		/*
		if(!$catPageRequestValidateLib->areValidRequestParameters($request, $categoryBuilder, $LDBCourseBuilder, $locationBuilder)) {
			show_404();
		}
		*/
		// This was done for ticket id 1463 (apply default courselevel filters in case of abroad pages)
		$this->_setDefaultFilterForStudyAbroad($request);
		
		if(CP_SOLR_FLAG) {
			$categoryPage 			= $categoryPageBuilder->getCategroyPageSolr();
		} else {
			$categoryPage 			= $categoryPageBuilder->getCategoryPage();
		}
		
		$this->checkForRedirection($categoryPage);
		$tempFiltersForZeroResuls = $request->getAppliedFilters();
		
		$sTime = microtime(true);
		$categoryPageInstitutes = $categoryPage->getInstitutes();
		if(EN_LOG_FLAG) error_log("\narray( section => 'categorypage getInstitutesCall', timetaken => ".(microtime(true) - $sTime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);
		
		$sTime = microtime(true);
		//Zero result handling
		$zeroResultCategoryPageInstitutesCount = 0;
		$zeroResultFlag = FALSE;
		$zeroResultStep = FALSE;
		$zeroResultRequest = FALSE;
		$zeroResultCookieSetFlag = FALSE;

		if(EN_LOG_FLAG) error_log("\narray( section => 'categorypage checkforzeroresult', timetaken => ".(microtime(true) - $sTime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);
		
		$sTime = microtime(true);
		$bannersForCurrentPage = $categoryPage->getBannersForCurrentRequest();
		$filteredBanner = $this->_getBannerForFilteredResults($categoryPageInstitutes, $bannersForCurrentPage);
		if(EN_LOG_FLAG) error_log("\narray( section => 'categorypage getBannerforpage', timetaken => ".(microtime(true) - $sTime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);
		
		$sTime = microtime(true);
		//Check for redirection -current category page to new RNR URLs 
		$this->_checkForRedirectionForNewURLPattern($categoryPage);
		
		if(!$request->isAJAXCall() && in_array($categoryPage->getRequest()->getSubCategoryId(), array(23,56))){
		    $rankingRelatedLinks = $this->getRankingRelatedLinks($categoryPage->getRequest());
		}
		
		$displayData['categoryPage'] 					= $categoryPage;
		$displayData['categoryRepository'] 				= $categoryBuilder->getCategoryRepository();
		$displayData['LDBCourseRepository'] 			= $LDBCourseBuilder->getLDBCourseRepository();
		$displayData['locationRepository'] 				= $locationBuilder->getLocationRepository();
		$displayData['request'] 						= $categoryPage->getRequest();
		$displayData['original_request'] 				= $originalPageRequest;
		$displayData['resultset_request'] 				= $categoryPage->getRequest();
		$displayData['zero_result_request'] 			= $zeroResultRequest;
		$displayData['zero_result_categorypage_count'] 	= $zeroResultCategoryPageInstitutesCount;
		$displayData['original_filters'] 				= $originalPageFilters;
		$displayData['subcat_id_course_page'] 			= $displayData['request']->getSubCategoryId();
		$displayData['course_pages_tabselected'] 		= 'Institutes';
		$displayData['validateuser'] 					= $this->checkUserValidation();
		$displayData['institutes'] 						= $categoryPageInstitutes;
		if(empty($displayData['institutes'])) {
			$displayData['noIndexFollow'] = true;
		}
		$displayData['trackForPages'] 					= true;
		$displayData['dynamicLDBCoursesList'] 			= $categoryPage->getDynamicLDBCoursesList();
		$displayData['dynamicCategoryList'] 			= $categoryPage->getDynamicCategoryList();
		$displayData['dynamicLocationList'] 			= $categoryPage->getDynamicLocationList();
		$displayData['instituteIdsOfCoursesToRotate'] 	= $this->_getInstituteIdsOfCoursesToRotate($displayData['institutes'], $request->getSubCategoryId());
		$displayData['filteredBanner'] 					= $filteredBanner;
		
		$displayData['abroadCategoryPageRequest'] 		= new AbroadCategoryPageRequest();
		$displayData['solrFacetValues'] 				= $categoryPage->getSolrFacetValues();
		$displayData['tracking_keyid'] 					= DESKTOP_NL_CTPG_TUPLE_DEB;
		$displayData['questionTrackingPageKeyId']       = 519;

		//below line is used for conversion traking purpose for compare tool
		$displayData['comparetrackingPageKeyId']        = 508;
		
		//Compute canonical URL
		$displayData['canonicalurl'] = $this->_computeCanonicalUrl($categoryPage->getRequest(),$locationBuilder->getLocationRepository());
		
		if(EN_LOG_FLAG) error_log("\narray( section => 'categorypage displaydata array prepare', timetaken => ".(microtime(true) - $sTime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);
		
		$displayData['FB_Google_Pixel_Script_required'] = FALSE;
		if($request->getSubcategoryId() == 23) {
		    $displayData['FB_Google_Pixel_Script_required'] = TRUE;
		}

		$googleRemarketingParams = array(
				"categoryId" 	=> $request->getCategoryId(),
				"subcategoryId" => $request->getSubCategoryId(),
				"specializationId" => $request->getLDBCourseId(),
				"countryId" 	=> $request->getCountryId(),
				"cityId" 		=> $request->getCityId()
		    ); 
		
		$displayData['googleRemarketingParams'] = $googleRemarketingParams;
		
		$displayData['subcatNameForGATracking'] = $displayData['categoryRepository']->find($displayData['request']->getSubCategoryId())->getName();
		$displayData['pageTypeForGATracking'] = 'CATEGORY';
		
		$sTime = microtime(true);
		$exam_list = $this->listingCache->getExamsList();
		if(empty($exam_list)){
			$exam_list = $categoryClient->getTestPrepCoursesList(1);
			$this->listingCache->storeExamsList($exam_list);
		}
		$displayData['exam_list'] 				= $this->_prepareExamList($exam_list);
		$displayData['exam_list']['MBA'][] 		= "GMAT";
		$displayData['categoryPageTypeFlag']    = $categoryPageTypeFlag;
		$displayData['zeroResultFlag']    		= $zeroResultFlag;
		$displayData['zeroResultStep']    		= $zeroResultStep;
		$displayData['rankingRelatedLinks']		= $rankingRelatedLinks;
		
		$displayData['CI_INSTANCE'] = &get_instance();
		/*
		 * Listings with localities (SMU/UTS)
		 */
		global $listings_with_localities;
		$displayData['listings_with_localities'] 	= json_encode($listings_with_localities);
		$displayData['mainCategoryIdsOnPage'] 		= array($displayData['request']->getCategoryId());
		$displayData['subcategoriesChoosenForRNR'] 	= $RNRSubcategories;
		$this->personalizer->triggerPersonalization($displayData['request']->getSubCategoryId(),'categorypage', $displayData['request']->getCountryId(), $displayData['request']->getRegionId());
		
		if(EN_LOG_FLAG) error_log("\narray( section => 'categorypage personalization examlist', timetaken => ".(microtime(true) - $sTime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);
		
		$pageSource = $this->pageSourceInfo();
		$displayData['isMarketing'] = $pageSource['marketing'];
		
		$coursePageUrlObj = $this->load->library('coursepages/CoursePagesUrlRequest');
		$displayData['coursePageUrlObj'] = $coursePageUrlObj;
		
		$recommendationsApplied = array();
		foreach($_COOKIE as $cookie=>$value){
		    if(substr(trim($cookie), 0, 8) == 'applied_') {
			if($value == 1){
			    $recommendationsApplied[] = intval(str_replace('applied_', '', trim($cookie)));
			}
		    }
		}
		$displayData['recommendationsApplied'] = $recommendationsApplied;
		if(in_array($displayData['request']->getSubCategoryId(), $RNRSubcategories)) {
			$appliedFiltersForCookie = $displayData['request']->getAppliedFilters();
			$encoded_filtersForCookie = base64_encode ( json_encode ($appliedFiltersForCookie));
			if((!$displayData['request']->isAJAXCall()) && (!$zeroResultCookieSetFlag))
			{
				$categoryClient->setCookieCategoryPage('filters-' . $displayData['request']->getPageKey (), $encoded_filtersForCookie, 0, '/', COOKIEDOMAIN);
			}	
		}

		$sTime = microtime(true);
		$this->_makeBreadCrumb($displayData,clone $request,$categoryPageBuilder->getCategoryPageRepository()); //query
		if(EN_LOG_FLAG) error_log("\narray( section => 'categorypage make breadcrumb', timetaken => ".(microtime(true) - $sTime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);
	
		$displayData['isSourceRegistration'] = $_GET["source"] == 'Registration' ? true : false;
		$displayData['pageTitleForFilters'] = (($displayData['isSourceRegistration'] && $displayData['subcat_id_course_page'] == 23)?"MBA colleges matching your preference ":$displayData['pageTitleForFilters']);

		//load library for brochure url
		$national_course_lib = $this->load->library('listing/NationalCourseLib');
		$courseIDs = array();
		$courseInstituteMap = array();

		$courseInstituteMapping = $categoryPage->getInstituteCourseList();
		foreach ($courseInstituteMapping as $instId => $coursesArr) {
			$courseIDs = array_merge(array_keys($coursesArr), $courseIDs);
			foreach (array_keys($coursesArr) as $courseIdRow) {
				$courseInstituteMap[$courseIdRow] = $instId;
			}
		}

		$coursesBasicDetails                = $this->_getCoursesBasicDetails($courseIDs, $courseInstituteMap);
		$displayData['coursesBasicDetails'] = $coursesBasicDetails['courseBasicDetails'];
		
		// get locations for all multilocation institute
		$displayData['localityArray'] = array();
		$displayData['multiLocationCourses'] = json_encode($this->getMultilocationsForCompare($courseIDs));
		// _p($displayData['multiLocationCourses']); die;
		$sTime = microtime(true);
		$brochureURL = $coursesBasicDetails['brochure_url_array'];//$national_course_lib->getMultipleCoursesBrochure($courseIDs);
		$displayData['brochureURL'] = $brochureURL;//_p($brochureURL);
		
		if(EN_LOG_FLAG) error_log("\narray( section => 'categorypage getmultiple brochures', timetaken => ".(microtime(true) - $sTime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);

		//Tracking Code
		$displayData['beaconTrackData'] = array(
		    'pageIdentifier' => 'categoryPage',
		    'pageEntityId' => $request->getPageKey(),
		    'extraData' => array(
				'url'=>get_full_url(),
				'categoryId' => $request->getCategoryId() ? $request->getCategoryId() : '',
				'subCategoryId' => $request->getSubCategoryId() ? $request->getSubCategoryId() : '',
				'LDBCourseId' => $request->getLDBCourseId() ? $request->getLDBCourseId() : '',
				'cityId' => $request->getCityId() ? $request->getCityId() : '',
				'stateId' => $request->getStateId() ? $request->getStateId() : '',
				'countryId' => $request->getCountryId() ? $request->getCountryId() : '',
			)
		);

		$sTime = microtime(true);
		if(!$displayData['request']->isStudyAbroadPage()) {
			$displayData['isCollegeReviewSubcat'] = false;
			//college review Course Rating 
			global $subCatsForCollegeReviews;
			if($subCatsForCollegeReviews[$request->getSubCategoryId()] == '1'){
				$this->load->library('listing/NationalCourseLib');
				$NationalCourseLib         = new NationalCourseLib();
				$displayData['reviewData'] = $NationalCourseLib->getCourseReviewsData($courseIDs);
			    $displayData['reviewData'] = $NationalCourseLib->getCollegeReviewsByCriteria($displayData['reviewData']);

			    foreach($displayData['reviewData'] as $courseId =>$reviewData) {
					if($reviewData['overallRating'] > 0) {
						//$displayData['showCourseReviews'][$courseId]       = $reviewData['overallRating']; changed for story LDB -2747,dynamic loading rating params
						$displayData['reviewsData'][$courseId]['overallAverageRating'] = $reviewData['overallAverageRating'];
						$displayData['reviewsData'][$courseId]['ratingParamCount'] = $reviewData['ratingCount'];
					
					}
				}
				unset($displayData['reviewData']);

			    $displayData['isCollegeReviewSubcat'] = true;
			}

			if(in_array($request->getSubCategoryId(), $RNRSubcategories)){
                            //getting placement data
                            $displayData['institutesWithPlacementData'] = $this->_getInstitutesWithPlacementData($displayData['institutes']);
                            
                            //getting data for ask a question to current student
                            
                           	if(isset($displayData['validateuser'][0]['userid'])) {
                             $displayData['shortlistedCoursesOfUser'] =  Modules::run('myShortlist/MyShortlist/fetchShortlistedCoursesOfAUser',$displayData['validateuser'][0]['userid']); 
                           	}
                          
                            $displayData['lastViewedCourse'] = $this->getSessionViewedCourseListings();
                           
//                            $displayData['campusRepCourses']            = $this->_getCampusRepCourses($displayData['institutes']);
                            
                            $this->myshortlistmodel = $this->load->model("myShortlist/myshortlistmodel");
                            $displayData['coursesWithOnlineForm'] = $this->myshortlistmodel->findCourseIdsWithOnlineForm();
                            
			    //added by akhter
			    //get dashboardconfig for online form
                            $displayData['onlineApplicationCoursesUrl'] = $national_course_lib->getOnlineFormAllCourses();
			    
							$this->update_exam_score();

							//to get CategoryPage Related Links
							if($request->getSubCategoryId() == 23 || $request->getSubCategoryId() == 56){

								
								$category_page_related_lib = $this->load->library('categoryList/CategoryPageRelatedLib');
								$displayData['quickLinks'] = $category_page_related_lib->getCategoryPageRelatedLinks($request);
								
								$mmp_details = array();
								if(((strpos($_SERVER['HTTP_REFERER'], 'google') !== false) || ($_GET['showpopup'] != '')) && ((empty($mmp_details))) && ($_GET['resetpwd'] != 1) && ($displayData['validateuser'] == 'false')) {
									$this->load->model('customizedmmp/customizemmp_model');
									$mmp_details = $this->customizemmp_model->getMMPFormbySubCategoryId($request->getSubCategoryId(), 'newmmpcategory', 'N');
								}						   
								$displayData['mmp_details'] = $mmp_details;
								$displayData['showpopup'] = $_GET['showpopup'];
							}
					         
			    $displayData['widgetClickedPage'] = 'Category_Page_Desktop';
                            $this->load->view('categoryList/RNR/category_page', $displayData);
				//$this->load->view('categoryList/categoryPage',$displayData);
			} else {
				$this->update_exam_score();
				//check to show/hide sort on ranking
				$categoryPageRelatedLib = $this->load->library('categoryList/CategoryPageRelatedLib');
				$dataForRanking = array();
				$dataForRanking['subcatId'] = $request->getSubCategoryId();
				$dataForRanking['specializationId'] = 0;
				if($request->isLDBCoursePage()) {
					$dataForRanking['specializationId'] = $request->getLDBCourseId();
				}
				$dataForRanking['instituteIds'] = array_keys($categoryPageInstitutes);
				$topInstitutesOfThisCategory = $categoryPageRelatedLib->sortInstitutesOnRanking($dataForRanking);
				$displayData['showSortOnRanking'] = true;
				if(empty($topInstitutesOfThisCategory)) {
					$displayData['showSortOnRanking'] = false;
				}
				$this->load->view('categoryList/categoryPage',$displayData);
			}
		} else {
			$displayData['topFilteredCitieslist'] = $this->_getTopFilteredCitieslist($displayData['request'], $displayData['locationRepository']);
			$this->load->view('categoryList/categoryPageSA',$displayData);
		}
		if(EN_LOG_FLAG) error_log("\narray( section => 'categorypage preparing view', timetaken => ".(microtime(true) - $sTime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);
		if(EN_LOG_FLAG) error_log("\narray( section => 'categorypage buildCategoryPage End', timetaken => ".(microtime(true) - $startTime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);
	}
	
	/*
	 *
	 *  NEW Function To Add BreadCrumb
	 *
	 *	
	*/
	private	function _makeBreadCrumb(& $displayData, $requestClone,$categoryRepository ){
	    $crumb = array();
	    $arrIndx = 0;
	    
	    $this->load->library('coursepages/coursePagesUrlRequest');
	    $catPageRequest = new CategoryPageRequest();
	    $locationName = "";
	    if($requestClone->getLocalityId()){
		$locationName = $displayData['locationRepository']->findLocality($requestClone->getLocalityId())->getName();
	    }
	    
	    $crumb[$arrIndx]['name'] = "Home";
	    $crumb[$arrIndx]['url'] = SHIKSHA_HOME;
	    $arrIndx += 1;
	    
	    if($requestClone->getSubCategoryId() == 23 or $requestClone->getSubCategoryId() == 56){
			$catPageRequest->setNewURLFlag(1);
	    }
	    
	    if($requestClone->getCountryId() != 2){
			if($requestClone->getCountryId() == 1) {
				$crumb[$arrIndx]['name'] = $categoryRepository->getRegion()->getName();
				$catPageRequest->setData((array('regionId' => $requestClone->getRegionId(),'countryId' => $requestClone->getCountryId())));
				$crumb[$arrIndx]['url'] = $catPageRequest->getUrl();
			} else {
				$crumb[$arrIndx]['name'] = $categoryRepository->getCountry()->getName();
				$catPageRequest->setData((array('countryId' => $requestClone->getCountryId())));
				$crumb[$arrIndx]['url'] = $catPageRequest->getUrl();
			}
			$arrIndx += 1;
	    }


		$lastCrumb = $this->computeFinalCrumb($requestClone);
//		_p($lastCrumb);


		global $COURSE_PAGES_SUB_CAT_ARRAY;
		if ($requestClone->isMainCategoryPage() || $requestClone->isSubcategoryPage() || $requestClone->isLDBCoursePage()) {
			/* Check If course Page Exist for Categories Other Than TestPrep		 */
			if (array_key_exists($requestClone->getSubCategoryId(), $COURSE_PAGES_SUB_CAT_ARRAY) && $requestClone->getCategoryId() != 14) {

				$courseName = $COURSE_PAGES_SUB_CAT_ARRAY[$requestClone->getSubCategoryId()]["Name"];

				if ($requestClone->getSubCategoryId() != 23 && preg_match('/(MBA){1}/i', $courseName)){
					$crumb[$arrIndx]['name'] = "MBA";

					//find out all india MBA page
					$categoryPageRequest = new CategoryPageRequest();
					$allIndiaMBAData = array(
						'categoryId' => $requestClone->getCategoryId()
					);
					$categoryPageRequest->setData($allIndiaMBAData);
					$crumb[$arrIndx]['url'] = $categoryPageRequest->getURL();
					$arrIndx += 1;
				}


				$crumb[$arrIndx]['name'] = $courseName;
				$coursePagesUrlRequest = new CoursePagesUrlRequest();
				if( $requestClone->getSubCategoryId() == 23 ){
					$categoryPageRequest = new CategoryPageRequest();
					$allIndiaMBAData = array(
						'categoryId' => $requestClone->getCategoryId()
					);
					$categoryPageRequest->setData($allIndiaMBAData);
					$crumb[$arrIndx]['url'] = $categoryPageRequest->getURL();
				} else {
					$crumb[$arrIndx]['url'] = $coursePagesUrlRequest->getHomeTabUrl($requestClone->getSubCategoryId());
				}
				$arrIndx += 1;

				if(preg_match('/full time mba\\/pgdm/i', $lastCrumb['subCategoryName'])){
					$crumb[$arrIndx]['name'] = $lastCrumb['subCategoryName'];
					$crumb[$arrIndx]['url'] = $coursePagesUrlRequest->getHomeTabUrl($requestClone->getSubCategoryId());
					$arrIndx += 1;
				}
			} else {
				if ($requestClone->getCategoryId() != 14) {
					$crumb[$arrIndx]['name'] = $categoryRepository->getCategory()->getShortName();
					$catPageRequest->setData(array('regionId' => $requestClone->getRegionId(), 'countryId' => $requestClone->getCountryId(), 'categoryId' => $requestClone->getCategoryId()));
					//$catPageRequest->setData(array('categoryId' => $requestClone->getCategoryId()));
					$crumb[$arrIndx]['url'] = $catPageRequest->getURL();
					$arrIndx += 1;
				}
			}
		}

		if ( $requestClone->isSubcategoryPage() || $requestClone->isLDBCoursePage() ) {
			if( !preg_match('/(MBA){1}/i', $courseName) ) {

				if($categoryRepository->getSubCategory()->getId() == 56) {
					$crumb[$arrIndx]['name'] = 'Engineering Colleges';
				} else {
					if(!$requestClone->isStudyAbroadPage()) {
						//if ($requestClone->getCategoryId() != 3) {
							$crumb[$arrIndx]['name'] = $categoryRepository->getSubCategory()->getShortName() . " in " . $categoryRepository->getCountry()->getName();
						//} else {
						// 	$arrIndx --; // The last breadcrumb name needs to be overwritten : LF-3324
						// 	$crumb[$arrIndx]['name'] = $categoryRepository->getSubCategory()->getShortName();
						// }
					} else {
						$crumb[$arrIndx]['name'] = '';
					}
//					$crumb[$arrIndx]['name'] = $categoryRepository->getSubCategory()->getShortName() . ((!$requestClone->isStudyAbroadPage()) ? " in " . $categoryRepository->getCountry()->getName() : "");
				}

//				$crumb[$arrIndx]['name'] = (($categoryRepository->getSubCategory()->getId() == 56) ? 'Engineering Colleges' : $categoryRepository->getSubCategory()->getShortName()) . ((!$requestClone->isStudyAbroadPage()) ? " in " . $categoryRepository->getCountry()->getName() : "");
				$catPageRequest->setData((array('regionId' => $requestClone->getRegionId(), 'countryId' => $requestClone->getCountryId(), 'categoryId' => $requestClone->getCategoryId(), 'subCategoryId' => $requestClone->getSubCategoryId())));
				$crumb[$arrIndx]['url'] = $catPageRequest->getUrl();
				$crumb[$arrIndx]['allCityFlag'] = true;
				$arrIndx += 1;
			}
		}

	    /*
	     * If this is Subcategory ALL city page, then step back ..
	     * This condition is not needed now LF-3262 + LF-3318
	     */

//	    if($requestClone->isSubcategoryPage() && $requestClone->getCityId() == 1 && $requestClone->getStateId() == 1){
//			array_splice($crumb, $arrIndx);
//			$crumb[$arrIndx-1]['url'] = "";
//	    } else {
		//$tmpCity = (($requestClone->getCityId() == 1)?$categoryRepository->getCountry()->getName():$categoryRepository->getCity()->getName());
			if($requestClone->getCityId() == 1){
				if($requestClone->getStateId() == 1){
					$tmpCity = $categoryRepository->getCountry()->getName();
				}
				else{
					$tmpCity = $categoryRepository->getState()->getName();
				}
			}else{
				$tmpCity = $categoryRepository->getCity()->getName();
			}
			if($locationName != ""){
				$tmpCity = " in ".$locationName.", ".$tmpCity;
			}else{
				$tmpCity = " in ".$tmpCity;
			}
			if($lastCrumb == " Institutes "){
				$tmpName = (($categoryRepository->getLDBCourse()->getCourseName()=="")?$categoryRepository->getSubCategory()->getShortName():$categoryRepository->getLDBCourse()->getCourseName());
				if(!$tmpName == ""){
					$crumb[$arrIndx]['name'] = $tmpName.$lastCrumb.$tmpCity;
					$crumb[$arrIndx]['url'] = "";
				}else{
					array_splice($crumb, $arrIndx);
					$crumb[$arrIndx-1]['url'] = "";
				}
			}
			else{
				$crumb[$arrIndx]['name'] = $lastCrumb['listingType'].$tmpCity;

				$categoryPageRequest = new CategoryPageRequest();
				$categoryData = array(
					'categoryId'    => $requestClone->getCategoryId(),
					'subCategoryId' => $requestClone->getSubCategoryId(),
					'localityId'    => $requestClone->getLocalityId(),
					'regionId'      => $requestClone->getregionId(),
					'cityId'        => $requestClone->getcityId(),
					'stateId'       => $requestClone->getstateId(),
					'countryId'     => $requestClone->getcountryId(),
				);
				$categoryPageRequest->setData($categoryData);
				$crumb[ $arrIndx ]['url'] = $categoryPageRequest->getURL();

				if (!empty($lastCrumb['subCategoryName']) && !empty($lastCrumb['courseName']) && !empty($lastCrumb['examName'])) {

					$subCategoryName = $lastCrumb['subCategoryName'] . ' in ' . $lastCrumb['courseName'] . ', ' . $lastCrumb['examName'];

					$arrIndx++;
					$crumb[ $arrIndx ]['name'] = $subCategoryName;
					$crumb[ $arrIndx ]['url'] = '';
				} else if (!empty($lastCrumb['courseName'])) {
					$arrIndx++;
					$crumb[ $arrIndx ]['name'] = $lastCrumb['courseName'];
					$crumb[ $arrIndx ]['url'] = '';
				} else if (!empty($lastCrumb['examName']) && !empty($lastCrumb['feesString'])) {
					$arrIndx++;
					if( !empty($lastCrumb['affiliationName']) ) {
						$crumb[ $arrIndx ]['name'] = $lastCrumb['affiliationName'] . 'colleges ' . $lastCrumb['examName'] . ', ' . $lastCrumb['feesString'];
					} else {
						$crumb[ $arrIndx ]['name'] = 'Colleges ' . $lastCrumb['examName'] . ', ' . $lastCrumb['feesString'];
					}
					$crumb[ $arrIndx ]['url'] = '';
				} else if (!empty($lastCrumb['examName'])) {
					$arrIndx++;
					$crumb[ $arrIndx ]['name'] = ucfirst($lastCrumb['examName']);
					$crumb[ $arrIndx ]['url'] = '';
				} else if (!empty($lastCrumb['feesString'])) {
					$arrIndx++;
					$crumb[ $arrIndx ]['name'] = ucfirst($lastCrumb['feesString']);
					$crumb[ $arrIndx ]['url'] = '';
				} else if (!empty($lastCrumb['affiliationName'])) {
					$arrIndx++;
					$crumb[ $arrIndx ]['name'] = $lastCrumb['affiliationName'];
					$crumb[ $arrIndx ]['url'] = '';
				} else { // This happens to be the last crumb - hence remove the URL which was added expecting that it is NOT the last crumb
					$crumb[ $arrIndx ]['url'] = '';
				}

			}
//		} Not needed now - LF-3262 + LF-3318

		$displayData['pageTitleForFilters'] = implode("", array_values($lastCrumb));
		$displayData['breadCrumb'] = $crumb;
	}
	
	
	private function _getBannerForFilteredResults($categoryPageInstitutes = array(), $bannersForCurrentPage = array()){
		$filteredResultBanner = FALSE;
		if(!empty($categoryPageInstitutes) && !empty($bannersForCurrentPage)){
			reset($categoryPageInstitutes);
			$institute = current($categoryPageInstitutes);
			if(!empty($institute)){
				$instituteId = $institute->getId();
				foreach($bannersForCurrentPage as $banner){
					if($banner->getInstituteId() == $instituteId){
						$filteredResultBanner = $banner;
						break;
					}
				}
			}
		}
		return $filteredResultBanner;
	}




	/*
	 * Function to Compute BreadCrum Last Crumb String
	 *
	 */
	private function computeFinalCrumb($request)
	{


		$this->load->helper('category_page');
		return computeFinalCrumb($request);

		//    global $pageHeading;

		//    $affiliationName = $request->getFullAffiliationName();
		//    $feesString = $request->getFeesString();
		//    $examName = $request->getExamName();
		//    $subCategoryName = $request->getSubCategoryName();
		//    $subCategoryId = $request->getSubCategoryId();
		//    $courseName = $request->getCourseName();
		//    $courseId = $request->getLDBCourseId();

		//    if($subCategoryName == "Full Time MBA/PGDM" || $subCategoryId == 23){
		// $subCategoryName = "MBA";
		//    }
		//    if($subCategoryName == "B.E./B.Tech" || $subCategoryId == 56){
		//     $subCategoryName = "B.Tech";
		//    }

		//    if($courseId == 2 || $courseId == 52)
		//     $courseName = "";
		//    if($courseName)
		//     $courseName = " in ".$courseName;

		//    if($examName)
		//     $examName = "accepting ".$examName." score ";

		//    if($feesString)
		//     $feesString = "fees upto ".$feesString." ";
		//    $ListingType= " Institutes ";
		//    if(in_array($subCategoryId, array(23,56))) {
		//    	$ListingType= " Colleges ";
		//    }
		//    // error_log("AMITK ".$affiliationName.$subCategoryName.$courseName." colleges ".$examName.$feesString);
		//    return $affiliationName.$subCategoryName.$courseName.$ListingType.$examName.$feesString;

	}

	public function reorderFilterLocationList($params)
	{
	    $filter_list_source = $_POST['filter_list_source'];
	    $this->load->library('categoryPageRequest');
	    $categoryPageRequest = new CategoryPageRequest($params);		
	    	    
	    $appliedFilters = $categoryPageRequest->getAppliedFilters();
	    
	    $this->load->builder('LocationBuilder','location');
	    $locationBuilder = new LocationBuilder;
	    $locationRepository = $locationBuilder->getLocationRepository();
	    
	    $chkd_location_sequence_array = array();
	    if($_POST['chkd_location_queue'] != "") {
		$chkd_location_sequence_array = $this->_getChkdlocationDataQueue(explode(",", $_POST['chkd_location_queue']));
	    }	    
	    
	    switch($filter_list_source) {
		case 'country' :
			$list_html = $this->_getListForCountries($params, $appliedFilters, $chkd_location_sequence_array);
		    break;
		
		case 'topFilteredCities' :
			$list_html = $this->_getListForTopFilteredCitites($params, $categoryPageRequest, $appliedFilters, $locationRepository, $chkd_location_sequence_array);
		    break;
		
		default :
		    $list_html = $this->_getListForDynamicCitites($params, $categoryPageRequest, $appliedFilters, $locationRepository, $chkd_location_sequence_array);
		    break;
	    }
	    
	    echo $list_html;
	}
	
	private function _getChkdlocationDataQueue($chkd_location_queue) {
		if( !(is_array($chkd_location_queue) AND count($chkd_location_queue)) ) {
		    return array();
		}
		
		$chkd_location_array = array();
		foreach($chkd_location_queue as $key => $location) {
		    $loc_array = explode("_", $location);
		    $chkd_location_array[$loc_array[0]][$loc_array[1]]['pos'] = $key;
		}
		
		return $chkd_location_array;
	}	
	
	private function _getListForDynamicCitites($params, $categoryPageRequest, $appliedFilters, $locationRepository, $chkd_location_sequence_array) {
		$selectedCityIdsArray = array();
		$selectedStateIdsArray = array();
		
		if(count($appliedFilters['city']) > 0) {
		    $selectedCityIdsArray = $appliedFilters['city'];
		}
		
		if(count($appliedFilters['state']) > 0) {
		    $selectedStateIdsArray = $appliedFilters['state'];
		}
			
		$this->load->builder('CategoryPageBuilder','categoryList');
		$categoryPageBuilder = new CategoryPageBuilder($params);
		
		if(CP_SOLR_FLAG) {
		$categoryPage = $categoryPageBuilder->getCategroyPageSolr();
		} else {
		$categoryPage = $categoryPageBuilder->getCategoryPage();
		}
		
		$filters = $categoryPage->getFilters();
		
		/*
		 * Have to show the States list on the Abroad Country Page only..
		 */
		$onChangeEvent = "onChange='queueThisLocation(this)'";
		$chkd_location_html = "";
		$state_location_html = "";
		$chkd_location_html_array = array();
		$chkd_location_sequence_array_length = count($chkd_location_sequence_array);
		$selectedCityIdsArrayLength = count($selectedCityIdsArray);
		$selectedStateIdsArrayLength = count($selectedStateIdsArray);
		
		if(!in_array($categoryPageRequest->getCountryId(), array(0, 1, 2))) {
		    $dynamicLocationList = $categoryPage->getDynamicLocationList();
		    $states = $locationRepository->getStatesByCountry($categoryPageRequest->getCountryId());		    
		    foreach($states as $key => $state){
			
			    $entityInfo['Name'] = $state->getName();
			    $entityInfo['Id'] = $state->getId();
			    
			    if(in_array($state->getId(), $dynamicLocationList['states'])){
				if($selectedStateIdsArrayLength && in_array($state->getId(), $selectedStateIdsArray)) {				    
				    if($chkd_location_sequence_array_length) {
					$pos = $chkd_location_sequence_array['state'][$state->getId()]['pos'];					
					$chkd_location_html_array[$pos] = $this->_getLocationLiHTML($entityInfo, TRUE, "state");
				    } else {					
					$chkd_location_html .= $this->_getLocationLiHTML($entityInfo, TRUE, "state");
				    }
				} else {				    
				    $state_location_html .= $this->_getLocationLiHTML($entityInfo, FALSE, "state");
				}
			    }
		    }
		}
	    
		$countVar = 0;
		$regionHiddenFlag = 0;
		$list_html = '<ul id="location_list_ul">';
		$html_part = "";
                if(!empty($filters['city'])) {
		foreach($filters['city']->getFilteredValues() as $cityId => $cityName){
		    $entityInfo['Name'] = $cityName;
		    $entityInfo['Id'] = $cityId;
		    
		    if($selectedCityIdsArrayLength && in_array($cityId, $selectedCityIdsArray)) {			
			if($chkd_location_sequence_array_length) {
			    $pos = $chkd_location_sequence_array['city'][$cityId]['pos'];
			    $chkd_location_html_array[$pos] = $this->_getLocationLiHTML($entityInfo, TRUE, "city");
			} else {
			    $chkd_location_html .= $this->_getLocationLiHTML($entityInfo, TRUE, "city");
			}
		    } else {
			if($regionHiddenFlag != 1 && $countVar == 25) {			    
			    $nonchked_location_html .=  $this->_getMoreMsgHtml();
			    $regionHiddenFlag = 1;
			}
			
			$nonchked_location_html .= $this->_getLocationLiHTML($entityInfo, FALSE, "city");
		    }
		    
		    $countVar++;
		}
	        }	
		if($regionHiddenFlag == 1) {
		    $nonchked_location_html .= '</div>'; // Closing the "#allRegionsContainer" div if it is opened.
		}
 
		if(count($chkd_location_html_array)) {
		    ksort($chkd_location_html_array);		
		    $list_html .= implode(" ", $chkd_location_html_array).$chkd_location_html . $state_location_html . $nonchked_location_html;
		} else {
		    $list_html .= $chkd_location_html . $state_location_html . $nonchked_location_html;
		}
		
		$list_html .= "</ul>";
		
		return $list_html;
	}
	
	private function _getListForCountries($params, $appliedFilters, $chkd_location_sequence_array) {
		$selectedCountryIdsArray = array();
		if(count($appliedFilters['country']) > 0) {
		    $selectedCountryIdsArray = $appliedFilters['country'];
		}
		
		$this->load->builder('CategoryPageBuilder','categoryList');
		$categoryPageBuilder = new CategoryPageBuilder($params);
		
		if(CP_SOLR_FLAG) {
		$categoryPage = $categoryPageBuilder->getCategroyPageSolr();		
		} else {
		$categoryPage = $categoryPageBuilder->getCategoryPage();
		}
		
		$filters = $categoryPage->getFilters();
	    
		$countVar = 0;
		$regionHiddenFlag = 0;		
		$list_html = '<ul id="location_list_ul">';
		$html_part = "";
		$chkd_location_html = "";
		$state_location_html = "";
		$chkd_location_html_array = array();
		$chkd_location_sequence_array_length = count($chkd_location_sequence_array);
		$selectedCountryIdsArrayLength = count($selectedCountryIdsArray);
		foreach($filters['country']->getFilteredValues() as $countryId=>$countryName){
		    $entityInfo['Name'] = $countryName;
		    $entityInfo['Id'] = $countryId;
			    
		    if($selectedCountryIdsArrayLength && in_array($countryId, $selectedCountryIdsArray)) {
			if($chkd_location_sequence_array_length) {
			    $pos = $chkd_location_sequence_array['country'][$countryId]['pos'];			    
			    $chkd_location_html_array[$pos] = $this->_getLocationLiHTML($entityInfo, TRUE, "country");
			} else {			    
			    $chkd_location_html .= $this->_getLocationLiHTML($entityInfo, TRUE, "country");
			}
		    } else {
			if($countVar == 25) {
			    $nonchked_location_html .=  $this->_getMoreMsgHtml();
			    $regionHiddenFlag = 1;
			}
						
			$nonchked_location_html .= $this->_getLocationLiHTML($entityInfo, FALSE, "country");
		    }
		    $countVar++;
		}
		
		if($regionHiddenFlag == 1) {
		    $nonchked_location_html .= '</div>'; // Closing the "#allRegionsContainer" div if it is opened.
		}
		
		if(count($chkd_location_html_array)) {
		    ksort($chkd_location_html_array);
		    $list_html .= implode(" ", $chkd_location_html_array) . $nonchked_location_html;
		} else {
		    $list_html .= $chkd_location_html . $nonchked_location_html;    
		}
		
		$list_html .= "</ul>";
		
		return $list_html;
	}
		
	private function _getListForTopFilteredCitites($params, $categoryPageRequest, $appliedFilters, $locationRepository, $chkd_location_sequence_array) {
		$selectedCityIdsArray = array();
		$selectedStateIdsArray = array();
		
		if(count($appliedFilters['city']) > 0) {
		    $selectedCityIdsArray = $appliedFilters['city'];
		}
		
		if(count($appliedFilters['state']) > 0) {
		    $selectedStateIdsArray = $appliedFilters['state'];
		}
		
		/*
		 * Have to show the States list on the Abroad Country Page only..
		 */		
		$onChangeEvent = "onChange='queueThisLocation(this)'";
		$chkd_location_html = "";
		$state_location_html = "";
		$chkd_location_html_array = "";
		$chkd_location_sequence_array_length = count($chkd_location_sequence_array);
		$selectedCityIdsArrayLength = count($selectedCityIdsArray);
		$selectedStateIdsArrayLength = count($selectedStateIdsArray);
		
		$this->load->builder('CategoryPageBuilder','categoryList');
		$categoryPageBuilder = new CategoryPageBuilder($params);
		
		if(CP_SOLR_FLAG) {
		$categoryPage = $categoryPageBuilder->getCategroyPageSolr();
		} else {
		$categoryPage = $categoryPageBuilder->getCategoryPage();
		}
		
		$dynamicLocationList = $categoryPage->getDynamicLocationList();
		
		// error_log("CIties ".print_r($dynamicLocationList['cities'], true), 3, '/home/amitkuksal/Desktop/log.txt');
		if(!in_array($categoryPageRequest->getCountryId(), array(0, 1, 2))) {
		    $states = $locationRepository->getStatesByCountry($categoryPageRequest->getCountryId());		    
		    foreach($states as $key => $state){
			
			    $entityInfo['Name'] = $state->getName();
			    $entityInfo['Id'] = $state->getId();
			    
			    if(in_array($state->getId(), $dynamicLocationList['states'])){
				if($selectedStateIdsArrayLength && in_array($state->getId(), $selectedStateIdsArray)) {				    
				    if($chkd_location_sequence_array_length) {
					$pos = $chkd_location_sequence_array['state'][$state->getId()]['pos'];					
					$chkd_location_html_array[$pos] = $this->_getLocationLiHTML($entityInfo, TRUE, "state");
				    } else {					
					$chkd_location_html .= $this->_getLocationLiHTML($entityInfo, TRUE, "state");
				    }
				} else {				    
				    $state_location_html .= $this->_getLocationLiHTML($entityInfo, FALSE, "state");
				}
			    }
		    }
		}

		$topFilteredCitieslist = $this->_getTopFilteredCitieslist($categoryPageRequest, $locationRepository);
		$countVar = 0;
		$regionHiddenFlag = 0;
		$list_html = '<ul id="location_list_ul">';
		$html_part = "";
		
		$processedCityIds = array();
		foreach($topFilteredCitieslist as $city){
		    if(!in_array($city->getId(), $dynamicLocationList['cities'])) {			
			continue;
		    }
		    
		    $processedCityIds[] = $city->getId();
		    $entityInfo['Name'] = $city->getName();
		    $entityInfo['Id'] = $city->getId();		    
		    
		    if($selectedCityIdsArrayLength && in_array($city->getId(), $selectedCityIdsArray)) {			
			if($chkd_location_sequence_array_length) {
			    $pos = $chkd_location_sequence_array['city'][$city->getId()]['pos'];			    
			    $chkd_location_html_array[$pos] = $this->_getLocationLiHTML($entityInfo, TRUE, "city");
			    
			} else {			    
			    $chkd_location_html .= $this->_getLocationLiHTML($entityInfo, TRUE, "city");
			}
		    } else {
			if($countVar == 25) {
			    $nonchked_location_html .=  $this->_getMoreMsgHtml();
			    $regionHiddenFlag = 1;
			}
						
			$nonchked_location_html .= $this->_getLocationLiHTML($entityInfo, FALSE, "city");
		    }
		    $countVar++;
		}			    
						
		$filters = $categoryPage->getFilters();
		foreach($filters['city']->getFilteredValues() as $cityId => $cityName){
		    if(!in_array($cityId, $processedCityIds)) {
			$entityInfo['Name'] = $cityName;
			$entityInfo['Id'] = $cityId;
			
		        if($selectedCityIdsArrayLength && in_array($cityId, $selectedCityIdsArray)) {			
			    if($chkd_location_sequence_array_length) {
				$pos = $chkd_location_sequence_array['city'][$cityId]['pos'];
				$chkd_location_html_array[$pos] = $this->_getLocationLiHTML($entityInfo, TRUE, "city");
			    } else {
				$chkd_location_html .= $this->_getLocationLiHTML($entityInfo, TRUE, "city");
			    }
			} else {
			    if($regionHiddenFlag != 1 && $countVar == 25) {
				$nonchked_location_html .=  $this->_getMoreMsgHtml();
				$regionHiddenFlag = 1;
			    }
			    
			    $nonchked_location_html .= $this->_getLocationLiHTML($entityInfo, FALSE, "city");
			}
			$countVar++;			
		    }
		}

		if($regionHiddenFlag == 1) {
		    $nonchked_location_html .= '</div>'; // Closing the "#allRegionsContainer" div if it is opened.
		}
		
		if(count($chkd_location_html_array)) {
		    ksort($chkd_location_html_array);		
		    $list_html .= implode(" ", $chkd_location_html_array).$chkd_location_html . $state_location_html . $nonchked_location_html;
		} else {
		    $list_html .= $chkd_location_html . $state_location_html . $nonchked_location_html;
		}
		
		$list_html .= "</ul>";

		return $list_html;
	}
	
	
	private function _getLocationLiHTML($entityInfo, $isDefaultChecked, $entityType) {	    
	    $checked = "";
	    if($isDefaultChecked) {
		$checked = 'checked="checked"';
	    }	    	    
	    
	    $entityName = $entityInfo['Name'];
	    $entityId = $entityInfo['Id'];
	    $liElmName = $entityType."[]";
	    $liElmId = $entityType."_".$entityId;
	    
	    $html = '<li><label><input type="checkbox" id="'.$liElmId.'" name="'.$liElmName.'" value="'.$entityId.'" onChange="queueThisLocation(this)" '.$checked.' /> <span>'.$entityName.'</span></label></li>';
	    return $html;
	}
	
	private function _getMoreMsgHtml() {
	    $html = "<div id='moreMsgDiv' style='text-align:right;padding-right:8px;'><a href='javascript:void(0);' onClick='javascript: showAllRegions();'>+ More </a> </div>";
	    $html .=  "<div id='allRegionsContainer' style='display:none;'>";
	    return $html;	    
	}
	
	private function _getTopFilteredCitieslist($request, $locationRepository) {
	    
	    $this->load->helper('categoryList/abroadCatPageTopSearchedCities');
	    
	    $countryId = $request->getCountryId();
	    $catId = $request->getCategoryId();
	    $regionId = $request->getRegionId();
	    $subCatId = $request->getSubCategoryId();	    	    
	    
	    if($request->isSubcategoryPage()) {		
		if($countryId > 2) {
		    $citiesArray = getTopFilteredCitiesForSubCategoryCountryPage();
		    $topCities = $citiesArray[$subCatId][$countryId];
		} else {
		    $citiesArray = getTopFilteredCitiesForSubCategoryRegionPage();		    
		    $topCities = $citiesArray[$subCatId][$regionId];
		}
	    } else {
		if($countryId > 2) {
		    $citiesArray = getTopFilteredCitiesForCategoryCountryPage();		
		    $topCities = $citiesArray[$catId][$countryId];		    
		} else {
		    $citiesArray = getTopFilteredCitiesForCategoryRegionPage();		    
		    $topCities = $citiesArray[$catId][$regionId];
		}
	    }
	    	    
	    if(count($topCities)) {
		$topCitiesObj = $locationRepository->findMultipleCities($topCities);
	    } else {
		$topCitiesObj = array();
	    }
	    
	    return $topCitiesObj;
	}

	private function _getInstituteIdsOfCoursesToRotate($institutes, $subCategoryId) {
	    $instituteIdsOfCoursesToRotate = array();
	    if($subCategoryId == 1) {
		return $instituteIdsOfCoursesToRotate;
	    }
	    
	    global $RANDOM_ROTATE_CATPAGE_COURSES_FOR_INSTITUTEIDS;
	    $instituteIdsArray = array_keys($RANDOM_ROTATE_CATPAGE_COURSES_FOR_INSTITUTEIDS);
	    foreach($institutes as $institute) {
		$subcatIdsForInstituteArray = $RANDOM_ROTATE_CATPAGE_COURSES_FOR_INSTITUTEIDS[$institute->getId()];
		if(in_array($institute->getId(), $instituteIdsArray) && in_array($subCategoryId, $subcatIdsForInstituteArray)){
		    $instituteIdsOfCoursesToRotate[] = $institute->getId();
		}
	    }
	    //_p($instituteIdsOfCoursesToRotate); die;
	    return $instituteIdsOfCoursesToRotate;
	}
	
	private function _checkForRedirectionForNewURLPattern($categoryPage)
	{
		$RNRSubcategories = array_keys($this->config->item('CP_SUB_CATEGORY_NAME_LIST'));
		$request = $categoryPage->getRequest();
		$ajaxCall = $request->isAJAXCall();
        //redirection for Naukri Learning			
		$this->_redirectionForNaukriLearningPageToCoursePage($request);		
		if( ( !$request->isStudyAbroadPage()) &&
			(    in_array($request->getSubCategoryId(), $RNRSubcategories)
			 ||   ( $request->getCategoryId() == 2 && $request->getSubCategoryId() == 1 && $request->getLDBCourseId() == 1 && empty($ajaxCall))
			 )
			&&
			( $request->getNewURLFlag() != 1 )
		) 
		{
			$clonedRequest = clone $request;
			$url = $clonedRequest->getURL();
			 
			$URLData['categoryId'] 			= (int) $clonedRequest->getCategoryId();
			$URLData['subCategoryId'] 		= (int) $clonedRequest->getSubCategoryId();
			$URLData['LDBCourseId'] 		= (int) $clonedRequest->getLDBCourseId();
			$URLData['localityId'] 			= (int) $clonedRequest->getLocalityId();
			$URLData['zoneId'] 				= (int) $clonedRequest->getZoneId();
			$URLData['cityId'] 				= (int) $clonedRequest->getCityId();
			$URLData['stateId'] 			= (int) $clonedRequest->getStateId();
			$URLData['countryId'] 			= (int) $clonedRequest->getCountryId();
			$URLData['regionId'] 			= (int) $clonedRequest->getRegionId() ? $clonedRequest->getRegionId() : 0;
			$URLData['sortOrder'] 			= $clonedRequest->getSortOrder() !="" ? $clonedRequest->getSortOrder() : 'none';
			$URLData['pageNumber'] 			= (int) $clonedRequest->getPageNumberForPagination() != "" ? $clonedRequest->getPageNumberForPagination() : 1;
			$URLData['naukrilearning'] 		= (int) $clonedRequest->isNaukrilearningPage() ? 1 : 0;
			
			$this->load->library('categoryList/CategoryPageRequest');
			$categoryPageRequest = new CategoryPageRequest;
			$categoryPageRequest->setNewURLFlag(1); //New RNR URLS
			$categoryPageRequest->setData($URLData);
			$newRNRURL = $categoryPageRequest->getURL();
			if($newRNRURL === FALSE){
				show_404();
				exit();
			} else {
				Header( "HTTP/1.1 301 Moved Permanently" );
				Header( "Location: $newRNRURL");
				exit();
			}
		}
	}

	private function _redirectionForNaukriLearningPageToCoursePage($request){
      if( $request->getCategoryId() == 3 
			&& $request->getLDBCourseId() == 1 
			&& $request->getLocalityId() == 0
			&& $request->getZoneId() == 0
			&& $request->getCityId() == 1
			&& $request->getStateId() == 1
			&& $request->getCountryId() == 2
			&& $request->getRegionId() == 0
			&& $request->isNaukrilearningPage() == 1
	      )
			{
			
				if($request->getSubCategoryId() == 23)
				{
					$coursePageURL = SHIKSHA_MANAGEMENT_HOME_PREFIX."/mba-coursepage";
					Header( "HTTP/1.1 301 Moved Permanently" );
					Header( "Location: $coursePageURL");
					exit();
					
				}else if($request->getSubCategoryId() == 24)
				{
					$coursePageURL = SHIKSHA_MANAGEMENT_HOME_PREFIX."/distance-mba-coursepage";
					Header( "HTTP/1.1 301 Moved Permanently" );
					Header( "Location: $coursePageURL");
					exit();
					
				}else if($request->getSubCategoryId() == 26)
				{
					$coursePageURL = SHIKSHA_MANAGEMENT_HOME_PREFIX."/part-time-mba-coursepage";
					Header( "HTTP/1.1 301 Moved Permanently" );
					Header( "Location: $coursePageURL");
					exit();
				}	
					
			}
		
	}
	
	
	function checkForRedirection($categoryPage)
	{
		$request = $categoryPage->getRequest();
                if($request->isStudyAbroadPage() && $request->getCountryId() == 7 && $request->getRegionId() == 0) {
			$request->setData(array('regionId'=>5));
                        $url = $request->getURL();
                        Header( "HTTP/1.1 301 Moved Permanently" );
                        Header( "Location: $url");
                }
		if($request->getCityId() == 1 && $request->getStateId() == 1 && !$request->isStudyAbroadPage()){
			if(isset($_COOKIE['userCityPreference'])){
				$location = explode(":",$_COOKIE['userCityPreference']);
				if($location[0] > 1 || $location[1] > 1){	
					// Bug Fix : LF-2571
					//$request->setData(array('cityId'=>$location[0],'stateId'=>$location[1]));
					$request->setData(array('stateId'=>$location[1], 'cityId'=>$location[0]));
					$categoryPage->setRequest($request);
				}
			}
		}

                global $categoryPageMappingDataMadeHistory;
                $keyPositionArray = array_keys($categoryPageMappingDataMadeHistory['CATEGORYID'], $request->getSubCategoryId());                
                $keyPositionArrayCount = count($keyPositionArray);
                for($i = 0; $i < $keyPositionArrayCount; $i++) {
                        // echo "<hr> Key : ".$keyPositionArray[$i].", Subcat: ".$categoryPageMappingDataMadeHistory['CATEGORYID'][$keyPositionArray[$i]].", ldb course: ".$categoryPageMappingDataMadeHistory['LDBCOURSEID'][$keyPositionArray[$i]];
                        if($request->getLDBCourseId() == $categoryPageMappingDataMadeHistory['LDBCOURSEID'][$keyPositionArray[$i]]) {
                            $subcategory = $categoryPageMappingDataMadeHistory['CATEGORYID_TO_REDIRECT'][$keyPositionArray[$i]];
                            $ldbCourse = $categoryPageMappingDataMadeHistory['LDBCOURSEID_TO_REDIRECT'][$keyPositionArray[$i]];                            
                            $request->setData(array('subCategoryId'=> $subcategory, 'LDBCourseId' => $ldbCourse));
                            $this->load->library('categoryList/CategoryPageRequest');
                            $categoryPageRequest = new CategoryPageRequest;
                            $categoryPageRequest->setData($request);
                            $url = $categoryPageRequest->getURL();

                            Header( "HTTP/1.1 301 Moved Permanently" );
                            Header( "Location: $url");
                        }
                }

	}
	
	public function trackFilters($params, $resultCount = -1)
	{
		
		$this->load->library('categoryPageRequest');
		$categoryPageRequest = new CategoryPageRequest($params);
		$appliedFilters = json_decode(base64_decode($_COOKIE["filters-".$categoryPageRequest->getPageKey()]),true);
		
		$this->load->library('clients/CategoryPageClient');
		$categoryPageClient = new CategoryPageClient;
		$sessionId = sessionId();
		
		$categoryPageClient->trackFilters($sessionId, $categoryPageRequest, $appliedFilters, $resultCount);
	}
	
	public function rnrTrackFilters($params, $resultCount = -1) {
		$this->load->library('categoryPageRequest');
		$urlData = array();
		$trackData = explode("-", $params);
		if(count($trackData >= 10)){
			$urlData['categoryId'] 				= $trackData[0];
			$urlData['subCategoryId'] 			= $trackData[1];
			$urlData['LDBCourseId'] 			= $trackData[2];
			$urlData['localityId'] 				= $trackData[3];
			$urlData['cityId'] 					= $trackData[4];
			$urlData['stateId'] 				= $trackData[5];
			$urlData['countryId'] 				= $trackData[6];
			$urlData['regionId'] 				= $trackData[7];
			$urlData['affiliation'] 			= $trackData[8];
			$urlData['examName'] 				= $this->input->post("examName");
			$urlData['feesValue'] 				= $trackData[10];
			$categoryPageRequest = new CategoryPageRequest();
			$categoryPageRequest->setNewURLFlag(1);
			$categoryPageRequest->setData($urlData);
			$appliedFilters = $categoryPageRequest->getAppliedFilters();
			$this->load->library('clients/CategoryPageClient');
			$categoryPageClient = new CategoryPageClient;
			$sessionId = sessionId();
			$categoryPageClient->trackFilters($sessionId, $categoryPageRequest, $appliedFilters, $resultCount);
		}
	}
	
	function oldCategoryPage($categoryName, $country = 'All', $cityId = 'All', $subCategory = '',$urlCityNameSelected = '',$pagename = 'categorypages',$courseLevel = 'All',$courseLevel_1 = 'All',$courseType = 'All',$start = 0,$count = 20)
    {
		require_once(realpath(APPPATH.'../').'/globalconfig/oldNewCategoryMapping.php');
		
		$subCategory = strtolower($subCategory);
		
		$this->load->builder('LocationBuilder','location');
		$locationBuilder = new LocationBuilder;
		$locationRepository = $locationBuilder->getLocationRepository();
	
		$requestData = array();
		
		/*
		 * Handle a special case
		 * When city is abroad and URL is national
		 */ 
		$studyabroadCity = FALSE;
		if($cityId != 'All' && intval($cityId) > 1) {
			$city = $locationRepository->findCity($cityId);
			if($city->getCountryId() > 2) {
				$studyabroadCity = TRUE;
			}
		}
		
		if($studyabroadCity) {
			
			$studyabroadCountryId = $city->getCountryId();
			$requestData['countryId'] = $studyabroadCountryId;
			$requestData['categoryId'] = $oldNewCategoryUrlNameMapping[$categoryName]['id'];
			$requestData['subCategoryId'] = $oldNewCategoryUrlNameMapping[$categoryName]['subcategories'][$subCategory]['abroad'];
		}
		else if($categoryName == 'studyabroad' || $categoryName == 'studyabroaddetail' || $studyabroadCity) {
			
			/*
			 * First check if country if a region
			 */
			$region = $locationRepository->getRegionByURLName($country);	
			if($region && ($regionId = $region->getId())) {
				$requestData['regionId'] = $regionId;
			}
			else {
				$country = $locationRepository->getCountryByURLName($country);
				if($country && ($countryId = $country->getId())) {
					$requestData['countryId'] = $countryId;
				}
			}
			
			$categoryId = $oldNewCategoryUrlNameMapping[$subCategory]['id'];
			$requestData['categoryId'] = $categoryId;
		}
		else {
			
			$categoryId = $oldNewCategoryUrlNameMapping[$categoryName]['id'];
			$requestData['categoryId'] = $categoryId;
			
			/*
			 * Redirect logic for course pages
			 */
			
			if($subCategory == '' || $subCategory == "All"){
				if($categoryName == 'management') {
					if($courseLevel == 'Degree' && $courseLevel_1 == 'Post Graduate' && $courseType == 'Part Time') {
						$requestData['subCategoryId'] = 26;
					}
					else if($courseLevel == 'Degree' && $courseLevel_1 == 'Post Graduate' && $courseType == 'Correspondence') {
						$requestData['subCategoryId'] = 24;
					}
					else if($courseLevel == 'Degree' && $courseLevel_1 == 'Under Graduate' && $courseType == 'Full Time') {
						$requestData['subCategoryId'] = 28;
					}
					else if($courseLevel == 'Certification' && $courseLevel_1 == 'NULL' && $courseType == 'All') {
						$requestData['subCategoryId'] = 31;
					}
				}
				else if($categoryName == 'science') {
					if($courseLevel == 'All' && $courseLevel_1 == 'All' && $courseType == 'All') {
						$requestData['subCategoryId'] = 56;
					}
					else if($courseLevel == 'Degree' && $courseLevel_1 == 'Post Graduate' && $courseType == 'All') {
						$requestData['subCategoryId'] = 59;
					}
				}
				else if($categoryName == 'it') {
					if($courseLevel == 'All' && $courseLevel_1 == 'All' && $courseType == 'All') {
						$requestData['subCategoryId'] = 100;
					}
					else if($courseLevel == 'Degree' && $courseLevel_1 == 'Post Graduate' && $courseType == 'All') {
						$requestData['subCategoryId'] = 98;
					}
					else if($courseLevel == 'Degree' && $courseLevel_1 == 'Post Graduate' && $courseType == 'Part Time') {
						$requestData['subCategoryId'] = 97;
					}
					else if($courseLevel == 'Degree' && $courseLevel_1 == 'Post Graduate' && $courseType == 'Correspondence') {
						$requestData['subCategoryId'] = 97;
					}
				}
				else if($categoryName == 'hospitality') {
					if($courseLevel == 'All' && $courseLevel_1 == 'All' && $courseType == 'All') {
						$requestData['subCategoryId'] = 84;
					}
				}
			}
			
			/*
			 * If not course page, check for sub-category
			 */ 
			if(!isset($requestData['subCategoryId'])) {
				if($subCategory && $subCategory != 'All') {
					$subCategoryId = $oldNewCategoryUrlNameMapping[$categoryName]['subcategories'][strtolower($subCategory)]['india'];
					if($subCategoryId) {
						if(is_array($subCategoryId)){
							$requestData['LDBCourseId'] = $subCategoryId['course'];	
						}else{
							$requestData['subCategoryId'] = $subCategoryId;	
						}
							
					}
				}
			}
			
			if($cityId != 'All' && intval($cityId) > 1) {
				$requestData['cityId'] = $cityId;
			}
			
			if($pagename == 'categorymostviewed') {
				$requestData['sortOrder'] = 'viewCount';
			}
			else if($pagename == 'topinstitutes') {
				$requestData['sortOrder'] = 'topInstitutes';
			}
		}

		$this->load->library('categoryList/CategoryPageRequest');
		$categoryPageRequest = new CategoryPageRequest;
		$categoryPageRequest->setData($requestData);
		$url = $categoryPageRequest->getURL();
	
		Header( "HTTP/1.1 301 Moved Permanently" ); 
		Header( "Location: $url"); 
	}
	
	function oldTestPrepCategoryPage($url)
	{
		global $blogIdToNewSubCategoryMapping;
		
		$this->load->library('url_manager');
		$this->load->library('category_list_client');
		
		$url_params = $this->url_manager->get_testprep_params_from_url($url);		
		$blogAcronym = $url_params['blog_acronym'];
		$cityName = $url_params['city_name'];
		
		$category_client = new Category_list_client();
        
		$appId = 1;
		$cityId = (($cityName == NULL or $cityName == "") ? -2 : $category_client->getCityId($appId, $cityName));
		$blogId = $category_client->getBlogId($appId, $blogAcronym);
		
		if($blogId == "") {
		    $subCategoryId = 1;		    
		} else {        
		    if(isset($blogIdToNewSubCategoryMapping[$blogId])) {
			    $subCategoryId = $blogIdToNewSubCategoryMapping[$blogId];
		    }
		    else {			
			    $blogParentId = $category_client->getBlogParent($blogId);
			    $subCategoryId = $blogIdToNewSubCategoryMapping[$blogParentId];
		    }
		}
		
		$requestData = array();
		$requestData['categoryId'] = 14;
		$requestData['subCategoryId'] = $subCategoryId;
		$requestData['cityId'] = $cityId > 1 ? $cityId : 1;
		
		if($url_params['page_type'] == 'most-viewed') {
			$requestData['sortOrder'] = 'viewCount';
		}
		
		$this->load->library('categoryList/CategoryPageRequest');
		$categoryPageRequest = new CategoryPageRequest;
		$categoryPageRequest->setData($requestData);
		$url = $categoryPageRequest->getURL();
	
		Header( "HTTP/1.1 301 Moved Permanently" ); 
		Header( "Location: $url"); 
	}
	
	function recommendations($appliedListings,$thanks ='YES', $showHeading = 'true',$tracking_keyid = 0)
	{
		$this->load->library('CategoryPageRecommendations');
		$this->load->library('listing/NationalCourseLib');
		$this->load->library('Category_list_client');
		$this->load->helper('listing');

		if($tracking_keyid == DESKTOP_NL_LP_INST_RIGHT_DEB) {
			$tracking_keyid = DESKTOP_NL_LP_INST_RIGHT_DEB_RECO;
		}

		if($tracking_keyid == DESKTOP_NL_LP_INST_MIDDLE_DEB) {
			$tracking_keyid = DESKTOP_NL_LP_INST_MIDDLE_DEB_RECO;
		}

        if($tracking_keyid == DESKTOP_NL_LP_INST_BELLY_DEB) {
        	$tracking_keyid = DESKTOP_NL_LP_INST_BELLY_DEB_RECO;
        }

        if($tracking_keyid == DESKTOP_NL_LP_COURSE_RIGHT_DEB) {
        	$tracking_keyid = DESKTOP_NL_LP_COURSE_RIGHT_DEB_RECO;
        } 

        if($tracking_keyid == DESKTOP_NL_LP_COURSE_MIDDLE_DEB) {
          $tracking_keyid = DESKTOP_NL_LP_COURSE_LEFT_SIMILAR_RECO_DEB;
        }


        if($tracking_keyid == DESKTOP_NL_LP_COURSE_BELLY_DEB) {
          $tracking_keyid = DESKTOP_NL_LP_COURSE_BELLY_DEB_RECO;
        
        }
        	
		/**
		 * Institutes applied from category page
		 */
		$appliedListings = explode("|",$appliedListings);
		
		$appliedData = array();
		foreach($appliedListings as $v) {
			list($instituteId,$courseId) = explode("-",$v);
			if($instituteId > 0 && $courseId > 0) {
				$appliedData[$instituteId] = $courseId;
			}
		}
		if(empty($appliedData)) {
			return;
		}
		$appliedCourses = array_values($appliedData);
		$appliedInstitute = array_keys($appliedData);
		
		$this->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;
		$instituteRepository = $listingBuilder->getInstituteRepository();
		
		$appliedInstitutes = $instituteRepository->findWithCourses($appliedData);
	
		foreach($appliedInstitutes as $instiObj){
			$country_id = $instiObj->getMainLocation()->getCountry()->getId();
			break;
		}

		$data = array();
		$data['country_id'] = $country_id;
		$data['showApplyCheckboxes'] = 'no';
		$data['recommendationPage'] = 1;
		$data['appliedInstitutes'] = $appliedInstitutes;
		$data['appliedCourse'] = $appliedCourses[0];
		$data['appliedInstitute'] = $appliedInstitute[0];
		$data['recommendationsApplied'] = isset($_COOKIE['recommendation_applied'])?explode(',',$_COOKIE['recommendation_applied']):array();
		$data['validateuser'] = $this->checkUserValidation();
        $data['applied_data'] = $appliedData;
        $data['tracking_keyid'] = $tracking_keyid;
        $data['comparetrackingPageKeyId'] = 622;
		/*
		 Also viewed algo
		*/
		$rand = rand(1,100000);
        $rand = $rand % 2 == 0 ? 1 : 2;
		if($rand == 1) {
			$rstart = microtime(TRUE);
			$alsoViewedInstitutes = $this->categorypagerecommendations->getAlsoViewedInstitutes($appliedCourses);
			$rend = microtime(TRUE);
			error_log("RECOXY: By Also Viewed in ".($rend-$rstart)." -- ".count($alsoViewedInstitutes));
		}
		else {
			$rstart = microtime(TRUE);
			$alsoViewedInstitutes = $this->categorypagerecommendations->getMahoutAlsoViewedInstitutes($appliedCourses);
			$rend = microtime(TRUE);
                        error_log("RECOXY: By Mahout in ".($rend-$rstart)." -- ".count($alsoViewedInstitutes));
		}
		
		if(is_array($alsoViewedInstitutes) && count($alsoViewedInstitutes)) {
			$data['recoAlgo'] = $rand == 1 ? 'also_viewed' : 'mahout';
			$data['courseSubcat'] = $this->recommendation_model->getCourseSubcategory($appliedCourses[0]);
		}
		
		$data['numReco'] = count($alsoViewedInstitutes);
		
		//$alsoViewedInstitutes = $this->categorypagerecommendations->getAlsoViewedInstitutes($appliedCourses);
		
		
		if(is_array($alsoViewedInstitutes) && count($alsoViewedInstitutes)) {
			$data['institutes'] = $instituteRepository->findWithCourses($alsoViewedInstitutes);
			$data['headingVerbiage'] = 'Students who showed interest in '.(count($appliedInstitutes)>1?'these':'this').' institute'.(count($appliedInstitutes)>1?'s':'').' also looked at';
		}
		else {
			$similar_institutes = $this->categorypagerecommendations->getSimilarInstitutes($appliedCourses[0]);
			$similar_institutes = $similar_institutes['recommendations'];
			if(is_array($similar_institutes) && count($similar_institutes)) {
				$data['institutes'] = $instituteRepository->findWithCourses($similar_institutes);
				$data['headingVerbiage'] = 'Similar institutes and courses on Shiksha';
			}
		}

		if(strpos($_SERVER['HTTP_USER_AGENT'],'iPad') !== false && $thanks){
		    $national_course_lib = $this->load->library('listing/NationalCourseLib');
		    $course_reb_url = $national_course_lib->getCourseBrochure($appliedCourses[0]);
		    $data['course_brochure'] = $course_reb_url;
		}

		
		$recommendedCourses = array();
		foreach($data['institutes'] as $institute) {
		    $course = $institute->getFlagshipCourse();
		    $courseID = $course->getId();
		    $recommendedCourses[] = $courseID;
		}
		
		$data['brochureURL'] = $this->nationalcourselib->getMultipleCoursesBrochure($recommendedCourses);
		$data['thanks'] = $thanks;
		$data['showHeading'] = $showHeading;
		$this->load->view('categoryList/recommendations',$data);
	}


   function doRecommendationsExist() {
                $categoryId = $_POST['categoryId'];
                $appliedListings = $_POST['appliedListings'];

                $this->load->library('CategoryPageRecommendations');

		/*
		 If user not logged in, exit here
		*/                
		$userInfo = $this->checkUserValidation();
		if($userInfo == 'false') {			
			die("0");
		}

                /*
                 Institutes applied from category page
                */
		$appliedListings = explode("|",$appliedListings);
		$appliedData = array();

		foreach($appliedListings as $v) {
			list($instituteId,$courseId) = explode("-",$v);
			$appliedData[$instituteId] = $courseId;
		}
		
		$appliedCourses = array_values($appliedData);

		$this->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;
		$instituteRepository = $listingBuilder->getInstituteRepository();
		$appliedInstitutes = $instituteRepository->findWithCourses($appliedData);
                
		/*
		 Also viewed algo
		*/
		$alsoViewedInstitutes = $this->categorypagerecommendations->getAlsoViewedInstitutes($appliedCourses);
		if(is_array($alsoViewedInstitutes) && count($alsoViewedInstitutes)) {
                    die("1");
		} else {
			$similar_institutes = $this->categorypagerecommendations->getSimilarInstitutes($appliedInstitutes);

			if(is_array($similar_institutes) && count($similar_institutes)) {
                           die("1");
			}
		}
                
               die("0");
        }
	
	
	function mahoutRecommendations($courseId, $widget,$customExclusionList = '',$subCategoryId = 0,$pageCityId = 0,$brochureAvailable = '',$isRankingPage=0,$new_layer='')
	{
	    $userInfo = $this->checkUserValidation();
		$data = array();
		$data['widget'] = $widget;
		$data['userInfo'] = $userInfo;
	   
	    $this->load->helper('listing');
		$this->load->helper('string');
	    $this->load->library('CategoryPageRecommendations');
	    $this->load->library('listing/NationalCourseLib');
	    $this->load->builder('ListingBuilder','listing');
		$this->load->builder('LDBCourseBuilder','LDB');
		$this->load->builder('LocationBuilder','location');
		$this->load->builder('CategoryBuilder','categoryList');
		
		$categoryBuilder = new CategoryBuilder;
		$categoryRepository = $categoryBuilder->getCategoryRepository();
		
	    $listingBuilder = new ListingBuilder;
	    $instituteRepository = $listingBuilder->getInstituteRepository();
	    $courseRepository = $listingBuilder->getCourseRepository();
		$locationBuilder = new LocationBuilder;
	    $locationRepository = $locationBuilder->getLocationRepository();
		
		$LDBCourseBuilder = new LDBCourseBuilder;
		$LDBCourseRepository = $LDBCourseBuilder->getLDBCourseRepository();
	    
		$recommendations = $this->categorypagerecommendations->getMahoutAlsoViewedInstitutes(intval($courseId));
		
		
	    $recommendations = array_slice($instituteRepository->findWithCourses($recommendations),0,9);
	    
	    $recommendationsApplied = array();
	    $recommendedInstitutes = array();
	    $recommendedCourses = array();
	    foreach($recommendations as $institute) {
			$course = $institute->getFlagshipCourse();
			$courseID = $course->getId();
			if(isset($_COOKIE['applied_'.$courseID]) && $_COOKIE['applied_'.$courseID] == 1) {
				$recommendationsApplied[] = $courseID;
			}
			$recommendedInstitutes[] = $institute->getId();
			$recommendedCourses[] = $courseID;
	    }
	    $recommendationsApplied = array_values(array_unique($recommendationsApplied));
	    
	    $data['recommendationsExist'] = count($recommendations) > 0 ? 1 : 0;
	    $data['numberOfRecommendations'] = count($recommendations) > 9 ? 9 : count($recommendations);
	    $data['appliedCourse'] = $courseRepository->find($courseId);
	    $data['recommendations'] = $recommendations;
	    $data['recommendationsApplied'] = $recommendationsApplied;
	    $data['brochureURL'] = $this->nationalcourselib->getMultipleCoursesBrochure($recommendedCourses);
	    $data['uniqId'] = random_string('alnum', 6);
	    
	    $data['brochureAvailable'] = $brochureAvailable;
	    if($data['brochureAvailable'] && strpos($_SERVER["HTTP_USER_AGENT"],"iPad")){
		$national_course_lib = $this->load->library('listing/NationalCourseLib');
		$data['brochureUrl'] = $national_course_lib->getCourseBrochure($courseId);
	    }
	    $data['isRankingPage'] = $isRankingPage;
	    if(!empty($new_layer) && $new_layer == 'new_layer') {
			$recommendationHTML = $this->load->view('recommendationsLayer_cat_page',$data,TRUE);
		} else {
			$recommendationHTML = $this->load->view('recommendationsLayer',$data,TRUE);
		} 
	    
		echo "<html><head><style type='text/css'>.recommend-items {width:1300px !important; overflow:auto !important;} ul { width: 1300px !important} </style></head><body>";
			
		echo $recommendationHTML;
		
		echo "</body></html>";
		
	//    if($widget == 'LP_Reco_AlsoviewLayer' || $widget == 'LP_Reco_SimilarInstiLayer') {
	//	    $response = array(
	//		    'recommendationHTML' => $recommendationHTML,
	//		    'recommendedInstitutes' => $recommendedInstitutes
	//	    );
	//	    echo json_encode($response);
	//    }
	//    else {
	//	    echo $recommendationHTML;
	//    }
	}
	
	function showRecommendation($courseId, $widget,$customExclusionList = '',$subCategoryId = 0,$pageCityId = 0,$brochureAvailable = '',$isRankingPage=0,$isMyShortlistPage = 0 ,$new_layer='',$tracking_keyid = 0,$comparetrackingPageKeyId = '')
	{
		
		/* Adding XSS cleaning (Nikita) */
		$courseId = $this->security->xss_clean($courseId);
		if(!is_numeric($courseId)){
			echo '';
			exit;
		}
		$widget = $this->security->xss_clean($widget);
		$customExclusionList = $this->security->xss_clean($customExclusionList);
		$subCategoryId = $this->security->xss_clean($subCategoryId);
		$pageCityId = $this->security->xss_clean($pageCityId);
		$brochureAvailable = $this->security->xss_clean($brochureAvailable);
		$isRankingPage = $this->security->xss_clean($isRankingPage);
		$isMyShortlistPage = $this->security->xss_clean($isMyShortlistPage);
		$new_layer = $this->security->xss_clean($new_layer);
		
	    $categoryPage_SubCat = $this->input->post("categoryPage_SubCat", true);
	    $userInfo = $this->checkUserValidation();
		$data = array();
		$data['widget'] = $widget;
		$data['userInfo'] = $userInfo;
	   
	    $this->load->helper('listing');
		$this->load->helper('string');
	    $this->load->library('CategoryPageRecommendations');
	    $this->load->library('listing/NationalCourseLib');
	    $this->load->builder('ListingBuilder','listing');
		$this->load->builder('LDBCourseBuilder','LDB');
		$this->load->builder('LocationBuilder','location');
		$this->load->builder('CategoryBuilder','categoryList');
		
		$categoryBuilder = new CategoryBuilder;									//can be unset beyond this
		$categoryRepository = $categoryBuilder->getCategoryRepository();	
		unset($categoryBuilder);				//unset to check for memory utilization
		
	    $listingBuilder = new ListingBuilder;
	    $instituteRepository = $listingBuilder->getInstituteRepository();
	    $courseRepository = $listingBuilder->getCourseRepository();			//can be unset beyond this
	    unset($listingBuilder);				//unset to check for memory utilization
	    
		$locationBuilder = new LocationBuilder;
	    $locationRepository = $locationBuilder->getLocationRepository();		//can be unset beyond this
	    unset($locationBuilder);				//unset to check for memory utilization
		
		$LDBCourseBuilder = new LDBCourseBuilder;
		$LDBCourseRepository = $LDBCourseBuilder->getLDBCourseRepository();		//can be unset beyond this
		unset($LDBCourseBuilder);				//unset to check for memory utilization
	    
		$recommendations = array();
	

	    if($tracking_keyid == DESKTOP_NL_CTPG_TUPLE_DEB) {
	       $tracking_keyid = DESKTOP_NL_CTPG_TUPLE_DEB_RECO;
	       $comparetrackingPageKeyId = 623; 
	    }	
	    
	    if($tracking_keyid == DESKTOP_NL_RNKINGPGE_TUPLE_DEB) {
	       $tracking_keyid = DESKTOP_NL_RNKINGPGE_TUPLE_DEB_RECO; 
	       $comparetrackingPageKeyId = 624;
	    }

		if($tracking_keyid == DESKTOP_NL_SEARCHV2_TUPLE_DEB){
	       $tracking_keyid = DESKTOP_NL_SEARCHV2_TUPLE_DEB_RECO;
	       $comparetrackingPageKeyId = 625;
		}

		if($widget == 'LP_Reco_SimilarInstiLayer') {
			
			$LDBCourses = $LDBCourseRepository->getLDBCoursesForClientCourse($courseId);
			
			if(!is_array($LDBCourses) || count($LDBCourses) == 0) {
				$response = array(
									'recommendationHTML' => '',
									'recommendedInstitutes' => array()
								);
				echo json_encode($response);
				return;
			}

			/**
			 * If a sub-category id is defined, get similar institutes for an LDB course of that subcategory
			 */ 
			if($subCategoryId) {
				$subCategoryLDBCourses = $LDBCourseRepository->getLDBCoursesForSubCategory($subCategoryId);	//$LDBCourseRepository can be unset beyond this
				
				$subCategoryLDBCourseIds = array();
				foreach($subCategoryLDBCourses as $subCategoryLDBCourse) {
					$subCategoryLDBCourseIds[] = $subCategoryLDBCourse->getId();
				}
				
				foreach($LDBCourses as $LDBCourse) {
					if(in_array($LDBCourse->getId(),$subCategoryLDBCourseIds)) {
						$mainLDBCourse = $LDBCourse;
						break;
					}
				}
				if(!$mainLDBCourse) {
					$mainLDBCourse = $LDBCourses[0];
				}
			}
			else {
				$mainLDBCourse = $LDBCourses[0];
			}
			
			unset($categoryBuilder);				//unset to check for memory utilization
			
			$pageCityId = intval($pageCityId);
			
			$recommendations = $this->categorypagerecommendations->getSimilarInstitutes(intval($courseId),$mainLDBCourse->getId(),$pageCityId,$customExclusionList);

			$stateResultsIncluded = $recommendations['stateResultsIncluded'];
			$recommendations = $recommendations['recommendations'];
			
			$seedCourse = $courseRepository->find($courseId);
			
			/**
			 * If city is not provided, take main city
			 */ 
			if(!$pageCityId) {
				$pageCityId = $seedCourse->getMainLocation()->getCity()->getId();
				if(!$pageCityId){
					return false;
				}
			}
			
			$pageCityObj = $locationRepository->findCity($pageCityId);
			$pageStateObj = $locationRepository->findState($pageCityObj->getStateId());	//can be unset beyond this

			unset($locationRepository);			//unset to check for memory utilization
			
			$data['pageCityId'] = $pageCityObj->getId();
			$data['pageStateId'] = $pageCityObj->getStateId();
			
			if($stateResultsIncluded) {
				$data['seedCourseCity'] = $pageStateObj->getName();
			}
			else {
				$data['seedCourseCity'] = $pageCityObj->getName();
			}
			
			$data['seedCourseLDBCourse'] = $mainLDBCourse->getCourseName();
			$subCatId = $mainLDBCourse->getSubCategoryId();
			$categoryObject  = $categoryRepository->find($subCatId);


			unset($categoryRepository);
			
			if(!empty($categoryObject)){
				$data['seedCourseLDBCourse'] 	 = $categoryObject->getShortName();
			}
		}
		else if($widget == 'CP_Reco_divLayer' || $widget == 'CP_Reco_popupLayer') {
			
			$rand = rand(1,100000);
            $rand = $rand % 2 == 0 ? 1 : 2;
			$data['recoAlgo'] = 'also_viewed';
			if($rand == 1) {
				$rstart = microtime(TRUE);
				$recommendations = $this->categorypagerecommendations->getAlsoViewedInstitutes(intval($courseId));
				$rend = microtime(TRUE);
				error_log("RECOXY: By Also Viewed for ".$courseId." in ".($rend-$rstart)." -- ".count($recommendations));
			}
			else {
				$rstart = microtime(TRUE);
				$recommendations = $this->categorypagerecommendations->getMahoutAlsoViewedInstitutes(intval($courseId));
				$data['recoAlgo'] = 'mahout';
				$rend = microtime(TRUE);
				error_log("RECOXY: By Mahout for ".$courseId." in ".($rend-$rstart)." -- ".count($recommendations));
			}
			
			$data['courseSubcat'] = $this->recommendation_model->getCourseSubcategory($courseId);
		}
		else {
			
			$recommendations = $this->categorypagerecommendations->getAlsoViewedInstitutes(intval($courseId));
		}

	    $recommendations = array_slice($instituteRepository->findWithCourses($recommendations),0,9);
	    
	    $recommendationsApplied = array();
	    $recommendedInstitutes = array();
	    $recommendedCourses = array();
	    foreach($recommendations as $institute) {
			$course = $institute->getFlagshipCourse();
			$courseID = $course->getId();
			if(isset($_COOKIE['applied_'.$courseID]) && $_COOKIE['applied_'.$courseID] == 1) {
				$recommendationsApplied[] = $courseID;
			}
			$recommendedInstitutes[] = $institute->getId();
			$recommendedCourses[] = $courseID;
	    }
	    $recommendationsApplied = array_values(array_unique($recommendationsApplied));
	    
	    $data['recommendationsExist'] = count($recommendations) > 0 ? 1 : 0;
	    $data['numberOfRecommendations'] = count($recommendations) > 9 ? 9 : count($recommendations);
	    $data['appliedCourse'] = $courseRepository->find($courseId);
	    $data['recommendations'] = $recommendations;
	    $data['recommendationsApplied'] = $recommendationsApplied;
	    $data['brochureURL'] = $this->nationalcourselib->getMultipleCoursesBrochure($recommendedCourses);
	    $data['uniqId'] = random_string('alnum', 6);
	    
	    $data['brochureAvailable'] = $brochureAvailable;
	    if($data['brochureAvailable'] && strpos($_SERVER["HTTP_USER_AGENT"],"iPad")){
		$national_course_lib = $this->load->library('listing/NationalCourseLib');
		
		$data['brochureUrl'] = $national_course_lib->getCourseBrochure($courseId);
	    }
	    $data['isRankingPage'] = $isRankingPage;
	    $data['categoryPage_SubCat'] = $categoryPage_SubCat;
	    
	    if($categoryPage_SubCat == 23)
	    {
		if(isset($data['userInfo'][0]['userid'])) {
		                 $data['shortlistedCoursesOfUser'] =  Modules::run('myShortlist/MyShortlist/fetchShortlistedCoursesOfAUser',$data['userInfo'][0]['userid']);
		}
	    }


	    //check for institute to college story
	    $data['collegeOrInstituteRNR'] = 'institute';
	    $national_course_lib = $this->load->library('listing/NationalCourseLib');
	    $categoryIds = $national_course_lib->getCourseInstituteCategoryId($courseId,'course');
		if(count(array_intersect($categoryIds, array("2", "3"))) != 0) { 
			$data['collegeOrInstituteRNR'] = 'college';
		}
	    //check for institute to college story ends here

		$data['tracking_keyid'] = $tracking_keyid;

		if( ! empty($comparetrackingPageKeyId))
				$data['comparetrackingPageKeyId'] = $comparetrackingPageKeyId;
	    //condition if myshortlist  = 1
	    if($isMyShortlistPage == 1){
	    	$data['recommendationsExist'] =0;
	    }
	    $data['actionType'] = ($new_layer == 'ND_SRP_Reco_popupLayer') ? $new_layer : '';
	    if(!empty($new_layer) && $new_layer == 'new_layer') {
			$recommendationHTML = $this->load->view('recommendationsLayer_cat_page',$data,TRUE);
		} else {
			$recommendationHTML = $this->load->view('recommendationsLayer',$data,TRUE);
		} 
		
	    if($widget == 'LP_Reco_AlsoviewLayer' || $widget == 'LP_Reco_SimilarInstiLayer') {
		    $response = array(
			    'recommendationHTML' => $recommendationHTML,
			    'recommendedInstitutes' => $recommendedInstitutes
		    );
		    echo json_encode($response);
	    }
	    else {
		    echo $recommendationHTML;
	    }
	}
	
	function compareOverlay($cookiename,$selectedCourseList)
	{
		$this->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;
		$instituteRepository = $listingBuilder->getInstituteRepository();
		$courseRepository = $listingBuilder->getCourseRepository();
		$this->load->library('categoryList/CategoryPageRequest');
		$request = new CategoryPageRequest();
		$keyElements = explode('-',$cookiename);
		$data = array('categoryId' => $keyElements[2],
					  'subCategoryId' => $keyElements[3],
					  'LDBCourseId' => $keyElements[4],
				      'localityId'  => $keyElements[5], /*change for RNR2*/
					  'cityId' => $keyElements[6],
					  'stateId' => $keyElements[7],
					  'countryId' => $keyElements[8],
					  'regionId' => $keyElements[9]
					);
		$request->setData($data);
		
		if(isset($_COOKIE[$cookiename])){
			$cookieArray = array();
                        // code added for IE			
			$this->load->library('user_agent');
			$time_plus = 300;
			if ($this->agent->browser() == 'Internet Explorer') {
				$time_plus = 6000;
			}
	
			setcookie($cookiename,$_COOKIE[$cookiename],time() + $time_plus ,'/',COOKIEDOMAIN);
			$cookieArray = explode("|||",$_COOKIE[$cookiename]);
			if(count($cookieArray)<=1){
				return "Some Error Occoured";
			}
			$data = array();
			$i = 0;
			$instituteIDs = array();
			$courseIDs = array();
			$selectedCourseArray = array();
			$selectedCourseArray = explode("||",$selectedCourseList);
			
			foreach($cookieArray as $element){
				$institute = explode("::",$element);
				$data[$i]['instituteId'] = $institute[0];
				$data[$i]['courseList'] = explode("---",$institute[1]);
				$k = 0;
				$pos = 0;
				$data[$i]['courseList'] = $courseRepository->findMultiple(array_values(array_filter($data[$i]['courseList'])));
				$data[$i]['courseList'] = array_values($data[$i]['courseList']);
				foreach($data[$i]['courseList'] as $course){
					if($selectedCourseArray[$i] == $course->getId()){
						$pos = $k;
						break;
					}
					$k++;
				}
				if($pos > 0){
					$temp = $data[$i]['courseList'][0];
					$data[$i]['courseList'][0] = $data[$i]['courseList'][$k];
					$data[$i]['courseList'][$k] = $temp;
				}
				$data[$i]['courseId'] = $data[$i]['courseList'][0]->getId();
				$data[$i]['instituteName'] =  $institute[3];
				$data[$i]['urlImage'] = $institute[2];
				$instituteIDs[$institute[0]] = $data[$i]['courseId'];
				$i++;
			}
			

			$appliedInstitutes = $instituteRepository->findWithCourses($instituteIDs);
			$this->load->library('listing/Listing_client');
			$displayData = array();
			$displayData['institutes'] = $appliedInstitutes;
			$displayData['instituteList'] = $data;
			$displayData['request'] = $request;
			$keyArray = explode("-",$cookiename);
			$displayData['coursePageId'] = $keyArray[1];
			$validity = $this->checkUserValidation();
			if($validity != "false")
			{
				$displayData['validity'] = $validity[0];
			}
			$returnText = $this->load->view('categoryList/categoryPageComparePage',$displayData,true);
		}
		//sleep(5);
		echo $returnText;
	}
	
	
	function showPhotoVideoOverlay($type,$institute_id,$institute_name,$calledfrom){
		
		$this->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;
		$instituteRepository = $listingBuilder->getInstituteRepository();
		$institute = $instituteRepository->find($institute_id);
		$displayData['institute'] = $institute;
		$displayData['type'] = $type;
		$displayData['name'] = base64_decode($institute_name);
                $displayData['institute_id'] = $institute_id;
				
				$studyAbroadResponse = FALSE;
				if($institute_id) {
					$instituteService = $listingBuilder->getInstituteService();
					$studyAbroadResponse = $instituteService->isInstituteStudyAbroad($institute_id);
				}
				$displayData['studyAbroadResponse'] = $studyAbroadResponse;
				
                $displayData['validateuser'] = $this->checkUserValidation();
                //_P($displayData['validateuser']);
                if(is_array($displayData['validateuser'])) {
                        $this->load->library('LmsLib');
                        $displayData['response_list'] = $this->lmslib->getResponseListbyUserId($displayData['validateuser'][0]['userid']);
			$displayData['username'] = $displayData['validateuser'][0]['displayname'];
                        $displayData['userfirstname'] = $displayData['validateuser'][0]['firstname'];
                        $displayData['userlastname'] = $displayData['validateuser'][0]['lastname'];
                        $array = explode('|',$displayData['validateuser'][0]['cookiestr']);
                        $displayData['email'] = $array[0];
                        $displayData['mobile'] = $displayData['validateuser'][0]['mobile'];
                        $displayData['user_login_is_the'] = "YES"; 
                }
                if($calledfrom == 'fromcallback') {
                	$displayData['thanks_layer'] = $this->load->view('categoryList/photovideo_thanks',$displayData,true);
                }
                $displayData['response_form'] = $this->load->view('categoryList/photovideo_response_form',$displayData,true); 
                
		if($type == 'Photo') {
			$this->load->view('categoryList/photoVideoOverlay',$displayData);
                } else {
			$this->load->view('categoryList/videoOverlay',$displayData);
                }
	}
       
       public function showAnawidget() {
       		$this->load->view('categoryList/anaLayer');

       }

       function recoOnMail($userId)
       {
   		   $ajax = ($this->input->is_ajax_request()) ? 1 : 0;
   		   if(!$ajax && (empty($userId) || !is_numeric($userId))){
   		   	show_404();
   		   }
   		   
       	   ini_set("memory_limit", "256M");
           $this->load->library('recommendation_front_lib');
           $this->load->library('categoryList/CategoryPageRequest');
           $this->load->builder('ListingBuilder','listing');
           $listingBuilder = new ListingBuilder;
           $instituteRepository = $listingBuilder->getInstituteRepository();
		   
			$this->load->library('recommendation/profile_based_collaborative_filter_lib');
	
			/**
			 * Prepare data for collaborative filtering recommendation algo
			 */
			$collaborativeFilterBasedDataSet = array();
			if(0 && USE_PROFILE_BASED_COLLABORATIVE_FILTERING)
			{
				$collaborativeFilterBasedDataSet = $this->profile_based_collaborative_filter_lib->prepareUserBucketsForCollaborativeFiltering();
			}

           $obj = new Recommendation_Front_Lib();

           $user_request_data = array();

           if(is_numeric($userId)){
           	$user_request_data[] = array($userId,'string');
           }
           $instiWithCourses = array();
           if(count($user_request_data) > 0){
	           $recommendation_data = $obj->getRecommendations($user_request_data,$collaborativeFilterBasedDataSet,'yes',20);
	           $recommendation_data = json_decode(gzuncompress(base64_decode($recommendation_data)),true);
	           $recommendations = $recommendation_data['recommendations'];
			   
	           $recommendationuseridskeys = array_keys($recommendations);
	           $user_recommendation_data = $recommendations[$userId];
	           foreach($user_recommendation_data as $k=>$v){
	               foreach ($v as $k1=>$v1){
	                   foreach($v1['recommendations'] as $k2=>$v2){
	                       foreach($v2 as $k3=>$v3){
	                            if($v3['institute_id'] != 35861) {
								    $instituteArr[] = $v3['institute_id'];
								}
	                       }
	                   }
	               }
	           }

	           $instituteWithCourses = $instituteRepository->getCoursesOfInstitutes($instituteArr);
	           foreach($instituteWithCourses as $insti=>$val){
	               $instiWithCourses[$insti] = explode(",",$val['courseList']);
	           }
			   
			   $instiWithCourses = array();
			   foreach($instituteArr as $instiId) {
					if($instituteWithCourses[$instiId] && $instituteWithCourses[$instiId]['courseList']) {
						$instiWithCourses[$instiId] = explode(",",$instituteWithCourses[$instiId]['courseList']);
					}
			   }
			}

		if(isset($instiWithCourses)){
	   		$instituteDataWithCourses = $instituteRepository->findWithCourses($instiWithCourses);
		}
		$data['institutes']          = $instituteDataWithCourses;
		$data['headingVerbiage']     = 'Similar institutes and courses on Shiksha';
		$data['showApplyCheckboxes'] = 'no';
		$data['request']             = new CategoryPageRequest();
		$data['validateuser']        = $this->checkUserValidation();
		$data['recommendationPage']  = 1;
		$this->load->helper('listing');
		
		$data['tracking_keyid']      = MAILER_RECO;
        $this->load->view('categoryList/RecoOnMail',$data);
       }
       
   /**
    * function : showFatFooter
    * param: $request (categorypage request)
    * desc : create fatFooter for NON RNR page only
    * cachekey : like this CATPAGE-3-30-1-0-278-106-2-0-none-none-none
    * added by akhter
    **/ 
	public function showFatFooter($request) {
		$time_start = microtime_float(); $start_memory = memory_get_usage();
		$RNRSubcategories = array_keys($this->config->item('CP_SUB_CATEGORY_NAME_LIST'));
		if(in_array($request->getSubCategoryId(), $RNRSubcategories) || (empty($request) || !is_object($request))){
			return;
		}
		$cache = $this->load->library('categoryList/cache/CategoryPageCache');
		$fatFooterKey = $request->getParentPageKey().'-fftr';
		if(!$finalFooter = $cache->getFatFooter($fatFooterKey)) {
				$data['ldbCourseId'] = 1;
				$data['cityId']      = 1;
				$data['stateId']     = 1;
			if($request->isLDBCoursePage()){ // specialization
				$data['ldbCourseId'] = $request->getLDBCourseId ();
			}

			if ($request->isSubcategoryPage() || $request->isLDBCoursePage()) {
				$data['categoryId'] = $request->getCategoryId();
				$data['subCategoryId'] = $request->getSubCategoryId();
				$data['entityType'] = 'subcat';
			}else {
				$data['categoryId'] = $request->getCategoryId();
				$data['entityType'] = 'category';
			}
		
			if($request->isCityPage()){
				$data['cityId'] = $request->getCityId ();	
			}else if($request->isStatePage()){
				$data['stateId'] = $request->getStateId();	
			}
			$data['countryId'] = 2;	
			
			$this->load->library ('categoryList/categoryPageFatFooterLib');
			$categoryPageFatFooterLib = new categoryPageFatFooterLib();
			$finalFooter = $categoryPageFatFooterLib->getFatFooterData($data);
		
			$cache->storeFatFooter($fatFooterKey,$finalFooter);
		
		}

		if (count ( $finalFooter ) > 0) {
			$this->load->view ( 'categoryList/categoryPageFatFooter', array (
					'finalFooter' => $finalFooter 
			) );
		}
		if(LOG_HOMEPAGE_PERFORMANCE_DATA)
				error_log("Section: Load FateFooter | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_HOMEPAGE_PERFORMANCE_DATA_FILE_NAME);
	}
	
	private function _parseUrlForRequest($url) {
		$newURLFlag = false;
		$categorypageRequest;
		if(stristr($url, "-ctpg"))
		{   
			$newURLFlag = CP_NEW_RNR_URL_TYPE;
			$URLForRequest = substr(strrchr($url, "/"), 1);
			$categorypageRequest = new CategoryPageRequest ( $URLForRequest,$newURLFlag );
		}elseif(stristr($url, "/colleges/"))
		{   
			$newURLFlag = CP_NEW_RNR_URL_TYPE;
			$URLForRequest = substr(strrchr($url, "/"), 1);
			$categorypageRequest = new CategoryPageRequest ($URLForRequest,$newURLFlag );
		}
		elseif(stristr($url, "-categorypage-"))
		{
		$url = explode ( "-categorypage-", $url );
		$URLForRequest = $url [1];
		$categorypageRequest = new CategoryPageRequest ( $URLForRequest,$newURLFlag );
		
		}
		return $categorypageRequest;
	}
              
       

	private function _makeLocalityFatFooter(& $tempFooter,$request,$localityFilterValues,$localityAnchor){
		
		
		$RNRSubcategories = array_keys($this->config->item('CP_SUB_CATEGORY_NAME_LIST'));
		$requestUrl = clone $request;
		
		foreach($localityFilterValues as $zone=>$filter){
			if(!in_array($request->getSubCategoryId(), $RNRSubcategories))
			{
			$requestUrl->setData(array('zoneId'=>$zone,'localityId'=>0));
			$field['text'] = str_ireplace("location",$filter['name'],$localityAnchor);
			$field['url'] = $requestUrl->getURL();
			if($field['text'] && valid_url($field['url'])){
				$tempFooter[] = $field;
			}
			}
			foreach($filter['localities'] as $locality=>$filter1){
				
				$requestUrl->setData(array('zoneId'=>$zone,'localityId'=>$locality));
				$field['text'] = str_ireplace("location",$filter1,$localityAnchor);
				$field['url'] = $requestUrl->getURL();
				if($field['text'] && valid_url($field['url'])){
					$tempFooter[] = $field;
				}
			}
		}
	}

    public function downloadCategorywiseCourseData($category_id) {
        ob_start();
        ini_set("memory_limit", '900M');
		
		if(empty($category_id)) {
			return false;
		}
		
		// allowed only for cms usergroup
		$userInfo = $this->checkUserValidation();
		if($userInfo == 'false' || $userInfo[0]['usergroup'] != "cms")
		{
			redirect(SHIKSHA_HOME, 'location', 301);
		}
		$this->load->model('categoryList/categorymodel');
		$this->load->model('listing/listingmodel');
		$get_course_list               = $this->categorymodel->getClientCoursesForLocationandSubcategory2(array($category_id),array('country_id'=>2));
		
		$course_ids = explode(",", $get_course_list[$category_id]);
		if(!empty($course_ids)) {
			$this->load->builder('ListingBuilder','listing');
			$listingBuilder = new ListingBuilder;
			$course_list_array = $listingBuilder->getCourseRepository()->findMultiple($course_ids);
			
			// load LDBCourseBuilder to get LDB courses for given client course
			$this->load->builder('LDBCourseBuilder','LDB');
			$LDBCourseBuilder = new LDBCourseBuilder;
			$LDBCourseRepository = $LDBCourseBuilder->getLDBCourseRepository();
			
			// prepare headings array for csv
			$data_array[] = array('institute_id',
					  'institute_name',
					  'Course Type',
					  'courseTitle',
					  'course_id',
					  'mode_of_learning',
					  'duration_value',
					  'fees_value',
					  'fees_unit',
					  'course_level',
					  'seats_total',
					  'approvedBy',
					  'city',
					  'Zone',
					  'aima_rating',
					  'usp',
					  'marks-marks_type',
					  'admission_procedure',
					  'min_Salary',
					  'avg_Salary',
					  'max_Salary',
					  'salary_currency',
					  'other_eligibility',
					  'recruiting_companies',
					  'affiliation',
					  'ldb_course_ids',
					  'website',
					  'contact_person',
					  'contact_email',
					  'contact_numbers',
					  'contact_fax',
					  'last_modified_date',
					  'last_modifed_by',
					  'Reviews',
					  'brochure_link',
					  'video_link',
					  'application_last_date',
					  'result_declaration_date',
					  'course_commencement_date',
					  'application_form_url'
					);
			
			/**************************** get course reviews ***************************/
			$coursesWithReviewsData = $this->listingmodel->findCourseIdsWithReviews();
			$coursesWithReviewsArr  = array();
			foreach($coursesWithReviewsData as $coursesWithReviewsDataRow){
				$coursesWithReviewsArr[] = $coursesWithReviewsDataRow['courseId'];
			}
			
			/**************************** get application form url for all course ids ***************************/
			$coursesApplicationFormUrl = $this->listingmodel->getApplicationForUrl($course_ids);
			
			foreach ($course_list_array as $course_id=>$course_object) {
				$approved_string = "";
				/************************** get admission procedure **************************/
				$courseValueObjectsData = $listingBuilder->getCourseRepository()->findCourseWithValueObjects($course_id,array('description'));
				$admission_procedure = "";
				foreach($courseValueObjectsData->getDescriptionAttributes() as $attribute)
				{
					if($attribute->getName() == 'Admission Procedure') {
						$admission_procedure = $attribute->getValue();
					}
				}
				
				/*************************** extract all(salary) attributes ****************************/
				$courseAttributes = $course_object->getAttributes();
				
				/************************* get Recruiting Companies *************************/
				$recruitersArray = $course_object->getRecruitingCompanies();
				$recruiting_companies = "";
				foreach($recruitersArray as $recruiter)
				{
					$recruiting_companies .= $recruiter->getName().",";
				}
				$recruiting_companies = rtrim($recruiting_companies, ',');
				
				/************************** course affiliations **************************/
				$course_affiliations = $course_object->getAffiliations();
				$affiliation_string = "";
				foreach($course_affiliations as $affiliation)
				{
					$affiliation_string .= $affiliation[0].($affiliation[1] != ""? ":".$affiliation[1]."," : ",");
				}
				$affiliation_string = rtrim($affiliation_string,',');
				
				/*************************** get LDB courses to which given course is mapped ***************************/
				$ldb_courses = $LDBCourseRepository->getLDBCoursesForClientCourse($course_object->getId());
				$ldb_course_ids = "";
				foreach($ldb_courses as $ldb_course)
				{
					$ldb_course_ids .= $ldb_course->getId().",";
				}
				$ldb_course_ids = rtrim($ldb_course_ids,',');
				
				/***************************************** Contact details ******************************************/
				$location = $course_object->getMainLocation();
				$contactDetails = $location->getContactDetail();
				$website = $contactDetails->getContactWebsite();
				$contact_detail = array();
				$contact_detail['contact_person'] 		= $contactDetails->getContactPerson();
				$contact_detail['contact_email'] 		= $contactDetails->getContactEmail();
				$contact_detail['contact_numbers'] 		= $contactDetails->getContactNumbers();
				$contact_detail['contact_fax'] 			= $contactDetails->getContactFax();
				
				if($course_object->getInstId()>0)
				{
					$institute_object = $listingBuilder->getInstituteRepository()->find($course_object->getInstId());
				}
				else{
					continue;
				}
				$aima_rating  = $institute_object->getAIMARating();
				$usp =  $institute_object->getUsp();
				
				//get video urls of institute
				$videos = '';
				$videoObjs = $institute_object->getVideos();
				foreach($videoObjs as $videoObj) {
					$videoUrl[] = $videoObj->getURL();
				}
				if(!empty($videoUrl)) {
					$videos = implode(' | ', $videoUrl);
				}
				unset($videoUrl);
				
				unset($institute_object);
				if($course_object->isAICTEApproved()) {
					$approved_string = $approved_string."AICTEStatus - ".$course_object->isAICTEApproved()." ";
				}
				if($course_object->isUGCRecognised()) {
					$approved_string = $approved_string."UGCStatus - ".$course_object->isUGCRecognised()." ";
				}
				if($course_object->isDECApproved()) {
					$approved_string = $approved_string."DECStatus - ".$course_object->isDECApproved()." ";
				}
				$exams = $course_object->getEligibilityExams();
				$examAcronyms = array();
				foreach($exams as $exam) {
					$tempExam = $exam->getAcronym();
					if($exam->getMarks()){
						$tempExam .= " - ".$exam->getMarks()." - ".titleCase(str_replace("_"," ",$exam->getMarksType()));
					}
					$examAcronyms[] = $tempExam;
				}
				
				$reviewExists             = in_array($course_id, $coursesWithReviewsArr) ? "Yes" : "No";
				
				
				$data_array[] = array(
					$course_object->getInstId(),
					$course_object->getInstituteName(),
					$course_object->isPaid() ? "Paid":"Unpaid",
					$course_object->getName(),
					$course_object->getId(),
					$course_object->getCourseType(),
					$course_object->getDuration()->getDisplayValue(),
					$course_object->getFees()->getValue(),
					$course_object->getFees()->getCurrency(),
					$course_object->getCourseLevel(),
					$course_object->getTotalSeats(),
					$approved_string,
					$course_object->getMainLocation()->getCity()->getName(),
					$course_object->getMainLocation()->getZone()->getName(),
					$aima_rating,
					$usp,
					implode(" ", $examAcronyms),
					$admission_procedure,
					($courseAttributes['SalaryMin'] ? $courseAttributes['SalaryMin']->getValue(): 0),
					($courseAttributes['SalaryAvg'] ? $courseAttributes['SalaryAvg']->getValue(): 0),
					($courseAttributes['SalaryMax'] ? $courseAttributes['SalaryMax']->getValue(): 0),
					($courseAttributes['SalaryCurrency'] ? $courseAttributes['SalaryCurrency']->getValue() : ""),
					($courseAttributes['otherEligibilityCriteria'] ? $courseAttributes['otherEligibilityCriteria']->getValue() : ""),
					$recruiting_companies,
					$affiliation_string,
					$ldb_course_ids,
					$website,
					$contact_detail['contact_person'] ,
					$contact_detail['contact_email'] ,
					$contact_detail['contact_numbers'] ,
					$contact_detail['contact_fax'] 	,
					$course_object->getLastUpdatedDate(),
					$course_object->getLastModifiedBy(),
					$reviewExists,
					$course_object->getRequestBrochure(),
					$videos,
					$course_object->getDateOfFormSubmission(),
					$course_object->getDateOfResultDeclaration(),
					$course_object->getDateOfCourseComencement(),
					$coursesApplicationFormUrl[$course_object->getId()]
				);
				//break;
			}
			
			//_p($data_array);die;//return;
			unset($course_list_array);
			$filename = 'report.csv';
			$file_pointer = fopen("/tmp/".$filename, "w");
			foreach ($data_array as $fields) {
			   fputcsv($file_pointer, $fields);
			}
			unset($data_array);
			fclose($file_pointer);
			$csv = file_get_contents("/tmp/".$filename);
			$csv = trim($csv);
			header("Content-type: text/csv");
			header("Content-language: en");
			header("Content-Disposition: attachment; filename=file.csv");
			header("Pragma: no-cache");
			header("Expires: 0");
			
			print_r($csv);
			ob_end_flush();
		}
    }

	private function _setDefaultFilterForStudyAbroad($request) {
		// set default filters for study abroad
		if($request->isStudyAbroadPage() && !empty($_COOKIE['ug-pg-phd-catpage'])) {

			global $COURSELEVEL_TOBEHIDDEN_CONFIG;
			$preapplied_filters = json_decode(base64_decode($_COOKIE['filters-'.$request->getPageKey()]),true);			
			$course_level_array = $COURSELEVEL_TOBEHIDDEN_CONFIG[$_COOKIE['ug-pg-phd-catpage']];
			if($request->isAJAXCall()) {

				if(!array_key_exists('courseLevel', $preapplied_filters) || count($preapplied_filters['courseLevel']) == 0 ) {

					$preapplied_filters['courseLevel'] = $course_level_array;

				}

			} else {
				if(empty($preapplied_filters)) {
					$preapplied_filters =
					array(
                                      'duration'=>array(),
                                      'exams'=>array(),
		                              'mode'=>array(),
		                              'courseLevel'=>$course_level_array,
		                              'country'=>array()
					);
				} else {
					$cookie_course_level_array = (array)$preapplied_filters['courseLevel'];
					$its_same_page = "YES";
                                        foreach($cookie_course_level_array as $value) {
						if($value!='Dual Degree' && !in_array($value,$course_level_array)) {
							$its_same_page = "NO";
							break;
						}
					}
					$intersect_array = array_intersect($course_level_array,$cookie_course_level_array);
					$new_intersection_array = array();
					foreach($intersect_array as $value) {
						$new_intersection_array[] = $value;
					}
					if(count($intersect_array) == 0 || $its_same_page == "NO") {
						$preapplied_filters['courseLevel'] = $course_level_array;
					}
					
				}

			}
			$encoded_filters = base64_encode(json_encode($preapplied_filters));
			setcookie('filters-'.$request->getPageKey(),$encoded_filters,0,'/',COOKIEDOMAIN);
			$_COOKIE['filters-'.$request->getPageKey()] = $encoded_filters;
		}
			
	}

	private function _prepareExamList($exam_list = array()) {

		$final_exam_list = array();
		if(count($exam_list) >0) {
			foreach ($exam_list as $list) {
				foreach ($list as $list_child) {
					$final_exam_list[$list_child['acronym']][] = $list_child['child']['acronym'];
				}
			}
		}
		//Entry for no exam required in MBA
		if(!empty($final_exam_list['MBA'])){
			$final_exam_list['MBA'][] = "No Exam Required";
		}
		return $final_exam_list;
	}
	
	// Returns an array with keys 'int_ext' and 'marketing'
	public function pageSourceInfo()
	{
	    $catMiscelleneous = $this->load->library('common/Miscelleneous');
	    $output = $catMiscelleneous->catPageSourceInfo();
	    return($output);
	}
	
	public function activityTrack($courseId, $clickedCourseId, $instituteId, $action, $widget, $algo) {
	    $userInfo = $this->checkUserValidation();
	    if($userInfo == 'false') {			
		$userId = 0;
	    }
	    else {
		$userId = $userInfo[0]['userid'];
	    }
	    
	    $this->load->model('common/viewcountmodel');
	    $this->viewcountmodel->increaseActivityTrackCount($courseId, $clickedCourseId, $instituteId, $action, $widget, $algo, $userId);
	}

	public function getSessionViewedCourseListings(){
		$this->load->model('common/viewcountmodel');
		$lastViewdCourse = $this->viewcountmodel->getSessionViewedCourseListings();
	    return isset($lastViewdCourse['0']['course_id']) ? $lastViewdCourse['0']['course_id'] : 0;
		
	}
	
	
	/**
	 * Purpose : Cron for creating all possible non-zero category page keys
	**/
	public function createNonZeroCategoryPageKeys()
	{
		ini_set("memory_limit","3000M");
		ini_set("max_execution_time","3000");
		
		$this->load->library('CategoryPageZeroResultHandler');
		$handler = new CategoryPageZeroResultHandler();
		
		sendMailAlert('Starting Non-zero category page Cron '.date("Y-m-d:H:i:s"),array('pankaj.meena@shiksha.com','romil.goel@shiksha.com'), TRUE);
		$handler->createCategoryPageKeys();
		sendMailAlert('Ending Non-zero category page Cron '.date("Y-m-d:H:i:s"),array('pankaj.meena@shiksha.com','romil.goel@shiksha.com'), TRUE);
	}
	
	/**
	* Purpose       : Method to render the similar institute page for national courses
	* Params        : 1. hyphen seperated instituteId and courseId
	* To Do         : none
	* Author        : Romil Goel
	*/
	function similarInstitutes($listingIds)
	{
	    // load files
	    $this->load->library('categoryList/CategoryPageRequest');
	    
	    // prepare local vars
	    $listingIds 	= trim($listingIds,"-");
	    $listingIds 	= explode("-", $listingIds);
	    $instituteId 	= $listingIds[0];
	    $courseId 		= $listingIds[1];
	    $isAjaxCall 	= $this->input->post("AJAX");
	    $resultSetOffset 	= $this->input->post("resultSetOffset");
	    $moreresultflag	= $this->input->post("moreresultflag");
	    $listLength		= $this->input->get("listLength");
	    $courseToFocus	= $this->input->get("focus");
	    
	    $resultSetOffset 	= $resultSetOffset ? $resultSetOffset : 0;
	    $resultSetChunksSize= 20;

	    $currResultOffsetPos = 0;
	    if($listLength && $listLength > 0)
	    {
		$resultSetChunksSize += $listLength;
		$currResultOffsetPos = $listLength;
	    }
		
	    // determine the page type
	    $pageType = "similar_listing";
	    if(empty($courseId))
		$pageType = "similar_home";

	    if($isAjaxCall)
		$courseId = $this->input->post("selectedCourse");

	    // get the category page url for mba
	    $categoryPageRequest = new CategoryPageRequest;		
	    $categoryPageRequest->setNewURLFlag( _ID_FLAG_ON );
	    $requestData['subCategoryId'] = 23;
	    $categoryPageRequest->setData($requestData);
	    
	    // get the applied brochure data from cookie
	    $recommendationsApplied = array();
	    foreach($_COOKIE as $cookie=>$value){
		if(substr(trim($cookie), 0, 8) == 'applied_') {
		    if($value == 1){
			$recommendationsApplied[] = intval(str_replace('applied_', '', trim($cookie)));
		    }
		}
	    }
	    $displayData['recommendationsApplied'] = $recommendationsApplied;

	    // if not home page
	    if(!empty($courseId))
	    {
		// load libraries
		$this->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;
		$instituteRepository = $listingBuilder->getInstituteRepository();
		$courseRepository = $listingBuilder->getCourseRepository();
    
		$this->load->library('recommendation/alsoviewed');
		$recommendations = $this->alsoviewed->getAlsoViewedListings(array($courseId), 100, array(), 100);
		$totalSimilarCourseCount = count($recommendations);
		$this->load->model ( 'listing/coursefindermodel' );
    
		$national_course_lib = $this->load->library('listing/NationalCourseLib');
    
		// get the details of the course
		$courseObj = $courseRepository->find($courseId);
		$instituteIds = array();
		$courseIds = array();
		$recommendations = array_slice($recommendations, $resultSetOffset, $resultSetChunksSize);
		foreach($recommendations as $recommendationsRow)
		{
		    $instituteIds[] 	= $recommendationsRow[0];
		    $courseIds[]	= $recommendationsRow[1];
		    $courseIdsOfInstitute[$recommendationsRow[0]][] = $recommendationsRow[1];
		}
	
		if($courseIds)
		{
		    $courses 	= $courseRepository->findMultiple($courseIds);
		    $institutes = $instituteRepository->findWithCourses($courseIdsOfInstitute);
		}
		foreach($courses as $course)
		{
			$formattedcourses[$course->getInstId()] = $course;
			//$localityArray[$course->getId()] = getLocationsCityWise($course->getCurrentLocations());
		}

		$selectedCoursesOfInstitutes = $this->coursefindermodel->getCoursesOfInstituteBySubCategories(array($courseObj->getInstId()), array(23));
		
		$brochureURL = $national_course_lib->getMultipleCoursesBrochure($courseIds);

		$displayData['brochureURL']			= $brochureURL;
		$displayData["institutes"] 			= $institutes;
		$displayData["formattedcourses"] 		= $formattedcourses;
		$displayData["isAjaxCall"] 			= $isAjaxCall;
		$displayData["courseId"] 			= $courseId;
		$displayData["resultSetOffset"] 		= $resultSetOffset;
		$displayData["totalSimilarCourseCount"] 	= $totalSimilarCourseCount;
		$displayData["resultSetChunksSize"] 		= $resultSetChunksSize;
		$displayData["instituteName"]			= $courseObj->getInstituteName();
		$displayData["instituteId"]			= $courseObj->getInstId();
		$displayData["courseName"]			= $courseObj->getName();
		$displayData["selectedCoursesOfInstitutes"]	= $selectedCoursesOfInstitutes;
		$displayData["currentURL"]			= $_SERVER["SCRIPT_URI"];
		$displayData["courseToFocus"]			= $courseToFocus;
		$displayData['localityArray']			= $localityArray;
		global $listings_with_localities;
		$displayData['listings_with_localities'] 	= json_encode($listings_with_localities);
	    }
	    $displayData['validateuser'] 			= $this->checkUserValidation();
	    $displayData["pageType"] 				= $pageType;
	    $displayData["categoryPageUrl"]			= $categoryPageRequest->getURL();;
	    $displayData["subcatSameAsldbCourseCategoryPage"]	= 1; // this is a flag used in category page code, which needs to be set here as 1
	    $displayData['currResultOffsetPos'] 		= $currResultOffsetPos;

	    if($isAjaxCall)
	    {
		if($moreresultflag)
		    $viewObj = $this->load->view('categoryList/similarCourseListings',$displayData, true);
		else
		    $viewObj = $this->load->view('categoryList/similarCourseLeftPane',$displayData, true);

		echo json_encode(array("viewObj" => $viewObj, "totalSimilarCourseCount" => $totalSimilarCourseCount));
	    }
	    else if($pageType == "similar_home")
		$this->load->view('categoryList/similarCourseHome',$displayData);
	    else
	        $this->load->view('categoryList/similarCourses',$displayData);
	}

	/**
	* Purpose       : Method to get all MBA courses of the provided institute(ajax call)
	* Params        : 1. institute id(POST)
	* To Do         : none
	* Author        : Romil Goel
	*/
	function getMBACoursesOfInstitutes()
	{
	    $instituteId = $this->input->post("instituteId");
	    
	    $this->load->model ( 'listing/coursefindermodel' );

	    $courses = $this->coursefindermodel->getCoursesOfInstituteBySubCategories(array($instituteId), array(23));
	    
	    echo json_encode($courses);return;
	}
	
	function trackViewSimilarCount() {
		//get all variables
		$data = array();
		$data['courseIdSearched'] 	= $this->input->post('courseIdSearched');
        $data['instituteIdSearched']= $this->input->post('instituteIdSearched');
		$data['pageType'] 			= $this->input->post('pageType');
        $data['buttonType'] 		= $this->input->post('buttonType');
		$data['textEntered'] 		= $this->input->post('textEntered');
		$data['courseIdChosen'] 	= $this->input->post('courseIdChosen');
		$data['zeroResult'] 		= $this->input->post('zeroResult');
		
		//get user id in case of logged in user
		$userInfo = $this->checkUserValidation();
		$data['userId'] = $userInfo != 'false'?$userInfo[0]['userid']:'NULL';
		$data['courseIdSearched'] = !empty($data['courseIdSearched'])?$data['courseIdSearched']:'NULL';
		$data['instituteIdSearched'] = !empty($data['instituteIdSearched'])?$data['instituteIdSearched']:'NULL';
		$data['pageType'] = !empty($data['pageType'])?$data['pageType']:'';
		$data['buttonType'] = !empty($data['buttonType'])?$data['buttonType']:'';
		$data['textEntered'] = !empty($data['textEntered'])?$data['textEntered']:'';
		$data['courseIdChosen'] = !empty($data['courseIdChosen'])?$data['courseIdChosen']:'NULL';
		$data['zeroResult'] = !empty($data['zeroResult'])?$data['zeroResult']:'';
		
		//call to db
		$this->load->model('categoryList/categorypagemodel');
		$this->categorypagemodel->dbTrackViewSimilarInstt($data);
	}
	
	function trackLocationSelected(){
	    $data = array();
	    //$data['locationSelected']	= $this->input->post('locationSelected');
	    $data['ldbCourseId']	= $this->input->post('ldbCourseId');
	    $data['subCategoryId']	= $this->input->post('subCategoryId');
	    $data['categoryId']		= $this->input->post('categoryId');
	    //$data['locationSearch']	= $this->input->post('locationSearch');
	    $data['url']		= $this->input->post('url');
	    
	    $locationSelected	= $this->input->post('locationSelected');
	    $locationSelected	= json_decode($locationSelected);
	    $locationSelectedString = '';
	    foreach($locationSelected as $key=>$value){
		$locationSelectedString .= $key.",";
	    }
	    $locationSelectedString = rtrim($locationSelectedString,',');
	    $data['locationSelected']	= $locationSelectedString;
	    $locationSearch	= $this->input->post('locationSearch');
	    $locationSearch	= json_decode($locationSearch);
	    $locationSearchString = '';
	    foreach($locationSearch as $key=>$value){
		$locationSearchString .= $key.",";
	    }
	    $locationSearchString = rtrim($locationSearchString,',');
	    $data['locationSearch']	= $locationSearchString;
	    $userInfo = $this->checkUserValidation();
	    $data['userId'] = $userInfo != 'false'?$userInfo[0]['userid']:'-1';
	    
	    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	    } else {
		$ip = $_SERVER['REMOTE_ADDR'];
	    }
	    
	    $data['ipAddress'] = $ip;
	    $data['sessionId'] = sessionId();
	    $data['insertedAt'] = date('Y-m-d H:i:s');
	    
	    //error_log("testABC".print_r($data,true));
	    
	    //call to db
	    $this->load->model('categoryList/categorypagemodel');
	    $this->categorypagemodel->trackLocationSelected($data);
	    
	    return 1;
	}
	
	function performanceAnalysis()
	{
	    //$handle = fopen("/tmp/categoryPagePerformace.log", "r");
	    $handle = fopen(EN_CP_LOG_FILENAME, "r");
	    $parentArr = array();
	    if ($handle) {
		while (($line = fgets($handle)) !== false) {
		    $line = trim($line);
		    if(!$line)
			continue;
		    
		    $line = trim($line, ",");
		    $line = trim($line, ")");
		    $line = trim($line, "array( ");
		    $line = explode(",",$line);
		    
		    $arr = array();
		    foreach($line as $row)
		    {
			$row = explode("=>", $row);
			$arr[trim($row[0])] = trim(trim($row[1]),"'");
		    }
		    
		    if($line)
			$parentArr[$arr['section']][] = $arr;
		    
		}
	    }
	    else {
		_p("Error opening file");
	    }
       echo "<style type='text/css'>
    body {background:#eee; margin:0; padding:0; font:normal 14px arial;}
    table {border-left:1px solid #ccc; border-top:1px solid #ccc;}
    td {border-right:1px solid #ccc; border-bottom:1px solid #ccc; padding:8px 5px; font-size:13px;}
    th {border-right:1px solid #ccc; border-bottom:1px solid #ccc; padding:5px; text-align:left; background:#f6f6f6; font-size:13px;}
    h1 {font-size:30px; margin-top: 10px;}
    a {text-decoration:none; color:#444;}
    #overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: #000;
                filter:alpha(opacity=50);
                -moz-opacity:0.5;
                -khtml-opacity: 0.5;
                opacity: 0.3;
                z-index: 10;
            }
</style>";
	   
	    echo "<table width='1000' cellspacing='0' cellpadding='0'><tbody><tr><th>#</th><th> Function </th><th>Total Time</th><th>No of Request</th><th>Avg Time (Sec)</th><th>Avg Time (MS)</th></tr>";

		$count = 1;
	    foreach($parentArr as $key=>$row)
	    {
		$totalTime = 0;
		foreach($row as $typeRow)
		{
		    $totalTime += $typeRow['timetaken']; 
		}
		echo "	    <tr>
	    <td valign='top'>".$count++."</td>
	    <td valign='top'>$key</td>
	    <td valign='top'>$totalTime</td>
	    <td valign='top'>".count($row)."</td>
	    <td valign='top'>".($totalTime/count($row)) ."</td>
	    <td valign='top'>".($totalTime/count($row)*1000) ."</td>
	 	</tr>";
		//_p("Total time for ".$key." is ".$totalTime." for ".count($row)." entries. Average = ".($totalTime/count($row)) . " sec ==> " . ($totalTime/count($row)*1000) . " ms" );
	    }
	    echo "</tbody></table>";
	    //_p($parentArr);
	    fclose($handle);
	    
	}

	/*
	 * Author : Abhinav
	 */
	function setCookieForCurrentRequest($request){ 
	    // Not to process filter cookie if it is ajax call as it is from refine box or other places like there is a call from 2 page onwards
	    if($_POST['AJAX'] || $request->getPageNumberForPagination() > 1){
		return ;
	    }
	    // If call is not from AJAX, rather direct URL is hit, we need to process filter cookie
	    $multiLocations = $request->getUserPreferredLocationOrder();
	    // In case of single city/state selection no need to reset filter cookie for category page. So returning
	    if(count($multiLocations) == 0 || !($request->getCityId() == 1 && $request->getStateId()==1)){
		return ;
	    }
	    $city = array();
	    $state = array();
	    $locality = array();
	    $zone = array();
	    foreach($multiLocations as $locationObj){
		switch($locationObj['location_type']){
		    case "city"		:	$city[] = $locationObj['location_id'];break;
		    case "state"	:	$state[] = $locationObj['location_id'];break;
		    default : break;
		}
	    }
	    
	    $cookieString = json_decode(base64_decode($_COOKIE['filters-'.$request->getPageKey()]));
	    $cookieString->city = $city;
	    $cookieString->state = $state;
	    $cookieString->locality = $locality;
	    $cookieString->zone = $zone;
	    $cookieValue = base64_encode(json_encode($cookieString));
	    $cookieKeyString = 'filters-'.$request->getPageKey();
	    setcookie($cookieKeyString, $cookieValue, time()+1800,'/',COOKIEDOMAIN);
	    $_COOKIE['filters-'.$request->getPageKey()] = $cookieValue;
	    $cookieString = json_decode(base64_decode($_COOKIE['filters-'.$request->getPageKey()]));
	    return ;
	}
	
	/*
	 * Author	: Abhinav
	 * Purpose	: To avoid duplicate pages
	 * Solution	: Canonical URLs are changed: if same name is found in city->zone->locality hierarchy, canonical URL will be of parent
	 */
	function _computeCanonicalUrl($request,$locationRepository){
	    $cityName = ($request->getCityId() > 0)?$locationRepository->findCity($request->getCityId())->getName(): '';
	    $zoneName = ($request->getZoneId() > 0)?$locationRepository->findZone($request->getZoneId())->getName(): '';
	    $localityName = ($request->getLocalityId() > 0)?$locationRepository->findLocality($request->getLocalityId())->getName(): '';
	    $canonicalUrlForCurrentRequest = '';
	    if($localityName == $zoneName){
		//zone url
		$request->setData(array("localityId" => 0));
	    }
	    if($zoneName == $cityName){
		//city url
		$request->setData(array("zoneId" => 0,"localityId" => 0));
	    }
	    $canonicalUrlForCurrentRequest = $request->getCanonicalURL($request->getPageNumberForPagination());
	    return $canonicalUrlForCurrentRequest;
	}
	
	function saveFeedbackRatingsFromCategoryPage() {
		$institute_id 	= $this->input->post("institute_id");
		$rating 		= $this->input->post("rating");
		$message 		= $this->input->post("message");
		$location 		= $this->input->post("location");
		$sessionId  	= sessionId();
		$userInfo 		= $this->checkUserValidation();
		$userId 		= -1;
		
		if(!empty($userInfo) && $userInfo != "false"){
			$userId = $userInfo[0]['userid'];
		}
		$this->load->model('categoryList/categorypagemodel');
		$categoryPageModelObj = new categorypagemodel();
		$feedbackAlreadyGiven = $categoryPageModelObj->getFeedbackIdForSameSessionSameInstitute($sessionId, $institute_id);
		$success = false;
		if(!empty($feedbackAlreadyGiven)){
			$feedbackId = $feedbackAlreadyGiven[0]['id'];
			$categoryPageModelObj->updateFeedbackRatingsFromCategoryPage($feedbackId, $message);
			$success = true;
		} else {
			$feedbackId = $categoryPageModelObj->saveFeedbackRatingsFromCategoryPage($institute_id, $rating, $sessionId, $userId, $location);
			$success 	= true;
		}
		$return = array();
		$return['success'] 		= "false";
		if(!empty($success)) {
			$return['feedback_id']  = $feedbackId;
			$return['success'] 		= "true";
		}
		echo json_encode($return);
	}
	
	function loadLocalityLayer() {
		$data['cityId'] 				= $this->input->post('cityId');
		$data['localityFilterValues'] 	= $this->input->post('localityFilterValues');
		$data['appliedLocality'] 		= $this->input->post('appliedLocality');
		$data['enabledLocalitiesWithCount'] = $this->input->post('enabledLocalitiesWithCount');
		$data['localitySearchText'] = $this->input->post('localitySearchText');
		
		$this->load->view('categoryList/RNR/filter_locality_layer', $data);
	}
        
        /*
         @Desc - To check for institutes with their naukri data
         @params    - institutes object
         @returns   - array containing institutes as key and value as naukri data
         */
        function _getInstitutesWithPlacementData(&$institutes) {
            $this->load->library('listing/NaukriData');
            $NaukriData = new NaukriData;
            return $NaukriData->getNaukriSalaryData(array_keys($institutes));
        }
        
        /*
         @Desc - To check for courses with their campus rep data
         @params    - institutes object
         @returns   - array containing courses as key and value as true/false
         */
        function _getCampusRepCourses(&$institutes) {
            $courseIds = array();
            foreach($institutes as $institute) {
                $course = $institute->getFlagshipCourse();
                $courseIds[] = $course->getId();
            }
            $this->load->library('CA/CADiscussionHelper');
            $caDiscussion =  new CADiscussionHelper();
            return $caDiscussion->checkIfCampusRepExistsForCourse($courseIds);
        }
		
	private function update_exam_score() {
		
		$loggedInUserData = $this->getLoggedInUserData();
		if((isset($_GET['exam'])) && (!empty($_GET['exam'])) && (!empty($loggedInUserData['userId']))) {
			$exam_detail = explode(":",$_GET['exam']);
			$exam_name = strtoupper($exam_detail[0]);
			$exam_score = $exam_detail[1];
			if($exam_score < 55) {
				$exam_score = 55;
			} else if($exam_score > 96) {
				$exam_score = 96;
			}
			if($exam_name == 'CAT') {
				$exam_score_type = 'percentile';
				$this->load->model('user/usermodel');
				if((!empty($exam_name)) && (!empty($exam_score))) {
					$this->usermodel->addUserEducation($loggedInUserData['userId'],$exam_name,$exam_score,$exam_score_type);
				}
			}
		}		
	}

	function getRankingRelatedLinks($categoryPageRequest){

	    $this->load->library("CategoryPageRelatedLib");
	    $categoryPageRelatedLib = new CategoryPageRelatedLib();

	    // if single location page
	    $rankingRelatedLinks = $categoryPageRelatedLib->getRankingPageRelatedLinks($categoryPageRequest);

	    return $rankingRelatedLinks;
	}
	
	/*
	 * Purpose: Populate count of national category pages.
	 * 			To be run after the cron that creates and populates category_page_non_zero_pages_<nextmonth> table
	 * 			
	 * PARAMS: 	type => rnr or non_rnr else all will be picked
	 * 			batch size => if not given, all will be picked
	 * 			round => if not given, it will pick 1st <batchSize> rows
	 * 			rowId => if we want to start picking rows from specific row id
	 */
	public function categoryPageResultCount($type = 'both', $batchSize = '', $round = 1, $rowId = 0) {
		//set memory and execution time
		ini_set("memory_limit", "5000M");
		ini_set("max_execution_time", "-1");
		
		//log memory usage
		global $memoryLog;
		$this->log_memory('Cron started');
		
		//send cron started mail
		$benchmarkData['type'] = $type;
		$benchmarkData['batch_size'] = $batchSize;
		$benchmarkData['round'] = $round;
		$benchmarkData['row_offset'] = $rowId - 1;
		$this->sendCatPageCountCronMail('started', $benchmarkData);
		
		$logFilePath = '/tmp/log_category_page_institute_count_'.$type.$round.'_'.date('y-m-d');
		
		if(empty($rowId)) {
			$offsetStart = ($round-1)*$batchSize;
		} else {
			$offsetStart = $rowId - 1;
		}
		
		$time_start_0 = microtime_float();
		error_log("Type: $type, Round: $round, Offset: $offsetStart, Batch size: $batchSize...\n", 3, $logFilePath);
		
		//read from table category_page_non_zero_pages_<next_month>
		$time_start = microtime_float();
		error_log("Fetching category page data from DB...\n", 3, $logFilePath);
		
		$this->load->model('categoryList/categorypagemodel');
		$categoryPageModel 		= new categorypagemodel();
		$categoryPageDetails 	= $categoryPageModel->getNonZeroCategoryPagesDataNextMonth($type, $batchSize, $offsetStart);
		$currentBatchSize 		= sizeof($categoryPageDetails);
		
		if(empty($categoryPageDetails)) {
			error_log("No data fetched from DB for this round.\n", 3, $logFilePath);
			$this->sendCatPageCountCronMail('ended', $benchmarkData);
			_p('No Data fetched.');
			return;
		}
		
		$time_end = microtime_float();
        $time = $time_end - $time_start;
        error_log("Data fetched from DB. Time taken: ".(round($time, 5)*1000)." ms\n", 3, $logFilePath);
		$benchmarkData['db_read_time_1'] = round($time, 5)*1000;
		
		//load request and solr client libraries
		$this->load->library('categoryPageRequest');
		$this->load->library('categoryList/clients/CategoryPageSolrClient');
		$categoryPageSolrClient = new CategoryPageSolrClient();
		$RNRSubcategories 		= array_keys($this->config->item('CP_SUB_CATEGORY_NAME_LIST'));
		
		//get all filters to hide from db
		$time_start = microtime_float();
		error_log("Fetching filters to hide from DB...\n", 3, $logFilePath);
		
		$allFiltersToHide = $categoryPageModel->getAllFiltersToHide();
		
		$time_end = microtime_float();
        $time = $time_end - $time_start;
        error_log("Data fetched from DB. Time taken: ".(round($time, 5)*1000)." ms\n", 3, $logFilePath);
		$benchmarkData['db_read_time_2'] = round($time, 5)*1000;
		
		$this->log_memory('All data fetched from DB');
		
		$time_start_1 = microtime_float();
		foreach($categoryPageDetails as $categoryPage) {
			if(in_array($categoryPage['subCategoryId'], $RNRSubcategories)) {
				error_log("Row ".$categoryPage['id'].". RNR.\n", 3, $logFilePath);
				
				$time_start_2 = microtime_float();
				error_log("Creating new request...\n", 3, $logFilePath);
				
				//create request
				$request = new CategoryPageRequest();
				$request->setNewURLFlag(1);
				$request->setData($categoryPage);
				
				$time_end_2 = microtime_float();
				$time_2 = $time_end_2 - $time_start_2;
				error_log("Request created. Time taken: ".(round($time_2, 5)*1000)." ms\n", 3, $logFilePath);
				
				//filters to hide
				if(intval($categoryPage['LDBCourseId']) > 1) {
					$filtersToHide = $allFiltersToHide['ldbcourse'][$categoryPage['LDBCourseId']];
				}else if(intval($categoryPage['subCategoryId']) > 1) {
					$filtersToHide = $allFiltersToHide['subcategory'][$categoryPage['subCategoryId']];
				}
				
				$time_start_2 = microtime_float();
				error_log("Hitting solr...\n", 3, $logFilePath);
				
				//hit solr
				$count[$categoryPage['id']] = $categoryPageSolrClient->_getInstituteCountRnR($categoryPage, $filtersToHide, $request, $logFilePath);
				
				$time_end_2 = microtime_float();
				$time_2 = $time_end_2 - $time_start_2;
				error_log("Got result from solr: {$count[$categoryPage['id']]}. Time taken: ".(round($time_2, 5)*1000)." ms\n", 3, $logFilePath);
			} else {
				error_log("Row ".$categoryPage['id'].". NON RNR.\n", 3, $logFilePath);
				$time_start_2 = microtime_float();
				error_log("Hitting Solr...\n", 3, $logFilePath);
				
				$count[$categoryPage['id']] = $categoryPageSolrClient->_getInstituteCountNonRnR($categoryPage, $logFilePath);
				
				$time_end_2 = microtime_float();
				$time_2 = $time_end_2 - $time_start_2;
				error_log("Got result from solr: {$count[$categoryPage['id']]}. Time taken: ".(round($time_2, 5)*1000)." ms\n", 3, $logFilePath);
			}
		}
		
		error_log("--------------\n", 3, $logFilePath);
		$time_end_1 = microtime_float();
		$time_1 = $time_end_1 - $time_start_1;
		error_log("Solr work completed. Total time taken: ".round($time_1, 4)." seconds\n", 3, $logFilePath);
		error_log("Total number of hits to solr in this batch: ".$currentBatchSize.".\n", 3, $logFilePath);
		error_log("Average solr time taken per row: ".(round(($time_1/$currentBatchSize), 4)*1000)." ms\n", 3, $logFilePath);
		
		$benchmarkData['total_solr_time'] 		= round($time_1, 4);
		$benchmarkData['avg_solr_time_per_hit'] = round(($time_1/$currentBatchSize), 4)*1000;
		
		$this->log_memory('Solr work completed');
		
		//write in table category_page_non_zero_pages_<next_month>
		$time_start = microtime_float();
		error_log("Writing results into table...\n", 3, $logFilePath);
		
		$totalNumOfZeroResults = $categoryPageModel->writeCategoryPageResultCount($count);
		
		$time_end = microtime_float();
        $time = $time_end - $time_start;
        error_log("Write done. Time taken: ".(round($time, 5)*1000)." ms\n", 3, $logFilePath);
		$benchmarkData['db_write_time'] = round($time, 5)*1000;
		
		$this->log_memory('DB write done');
		
		$time_end_0 = microtime_float();
        $time_0 = $time_end_0 - $time_start_0;
		error_log("Batch completed. Total time taken: ".round($time_0, 4)." seconds\n", 3, $logFilePath);
		
		$benchmarkData['total_time_sec'] 		= round($time_0, 4);
		$benchmarkData['total_time_min'] 		= round((round($time_0, 4)/60), 4);
		$benchmarkData['num_of_rows'] 			= $currentBatchSize;
		$benchmarkData['total_db_read_time'] 	= $benchmarkData['db_read_time_1'] + $benchmarkData['db_read_time_2'];
		$benchmarkData['zero_result_count'] 	= $totalNumOfZeroResults;
		
		$this->log_memory('Cron ended');
		$benchmarkData['memory_usage'] = $memoryLog['memory'];
		$this->sendCatPageCountCronMail('ended', $benchmarkData);
		
		error_log("Memory usage statistics: ".print_r($memoryLog['memory'], true)."\n", 3, $logFilePath);
		error_log("Cron completed. Mail sent.\n\n", 3, $logFilePath);
		
		_p('DONE');
	}
	
	public function sendCatPageCountCronMail($startOrEnd, $benchmarkData) {
		//load library to send mail
        $this->load->library('alerts_client');
        $this->alertClient = new Alerts_client();
		
		$subject      	= "Cron ".$startOrEnd." for category page result count.";
		$emailIdarray 	= array('nikita.jain@shiksha.com', 'pankaj.meena@shiksha.com');
		
		if($startOrEnd == 'started') {
			$body = "CRON STATUS - <br><br>".
					"<b>Type: </b>".$benchmarkData['type']."<br>".
					"<b>Batch Size: </b>".$benchmarkData['batch_size']."<br>".
					"<b>Round: </b>".$benchmarkData['round']."<br>".
					"<b>Row Offset: </b>".$benchmarkData['row_offset']."<br>";
		} else {
			$body = "CRON STATUS - <br><br>".
					"<b>Number of Rows: </b>".$benchmarkData['num_of_rows']."<br>".
					"<b>Type: </b>".$benchmarkData['type']."<br>".
					"<b>Total DB Read Time: </b>".$benchmarkData['db_read_time_1']." + ".$benchmarkData['db_read_time_2']." = ".$benchmarkData['total_db_read_time']." ms<br>".
					"<b>Total Solr Time: </b>".$benchmarkData['total_solr_time']." sec<br>".
					"<b>Avg Solr Time per Hit: </b>".$benchmarkData['avg_solr_time_per_hit']." ms<br>".
					"<b>DB Write Time: </b>".$benchmarkData['db_write_time']." ms<br>".
					"<b>Total Time: </b>".$benchmarkData['total_time_sec']." sec/".$benchmarkData['total_time_min']." min<br>".
					"<b>Zero result count: </b>".$benchmarkData['zero_result_count']."<br>".
					"<b>Memory Usage Statistics: </b><br>";
					$memoryLogStr = "";
					foreach($benchmarkData['memory_usage'] as $key => $memory) {
						$memoryLogStr = $memoryLogStr.
						"\t<b>Comment: </b>".$benchmarkData['memory_usage'][$key]['comment']."<br>".
						"\t<b>Memory used: </b>".$benchmarkData['memory_usage'][$key]['memory_used_in_mb']." MB<br>".
						"\t<b>Memory allocated: </b>".$benchmarkData['memory_usage'][$key]['memory_allocated_in_mb']." MB<br><br>";
					}
			$body = $body.$memoryLogStr;
		}
		
		foreach($emailIdarray as $key=>$emailId) {
			$this->alertClient->externalQueueAdd("12", ADMIN_EMAIL, $emailId, $subject, $body, "html", '', 'n');
		}
	}
	
	function log_memory($comment) {
		global $memoryLog;
		$memoryLog['memory'][] = array(
			'comment' => $comment,
			'memory_used_in_mb' => round((memory_get_usage(0)/1024)/1024, 4),
			'memory_allocated_in_mb' => round((memory_get_usage(1)/1024)/1024, 4)
		);
	}
	
	function getURLByPageKey($pageKey) {
		$this->load->library('categoryList/CategoryPageRequest');
		$request = new CategoryPageRequest();
		$request->setDataByPageKey($pageKey);
		$url = $request->getURL();
		_p($url);
	}

	function _getCoursesBasicDetails($courseIDs, $courseInstituteMap){

		$courseIds          = array();
		$categorypagemodel  = $this->load->model('categoryList/categorypagemodel');
		$listingextendedmodel = $this->load->model('listing/listingextendedmodel');
		$data 				= $categorypagemodel->getCoursesBasicDetails($courseIDs);

		$brochure_url_array = array();

		foreach ($data as $value) {
			$courseBasicDetails[$value['institute_id']][] = array("course_id" => $value['course_id'], "course_name" => $value['courseTitle']);
			if($value['course_request_brochure_link'])
				$brochure_url_array[$value['course_id']] = $value['course_request_brochure_link'];
		}

		$institutes = array();
		$coursesNotHavingBrochure = array_diff($courseIDs, array_keys($brochure_url_array));
		if(!empty($coursesNotHavingBrochure)){
			foreach ($coursesNotHavingBrochure as $courseId) {
				$institutes[] = $courseInstituteMap[$courseId];
			}
			$instituteBrochures = $categorypagemodel->getInstitutesBrochureLinks($institutes);
			
			foreach ($coursesNotHavingBrochure as $courseId) {
				if($instituteBrochures[$courseInstituteMap[$courseId]])
					$brochure_url_array[$courseId] = $instituteBrochures[$courseInstituteMap[$courseId]];
			}
		}

		$coursesNotHavingBrochure = array_diff($courseIDs, array_keys($brochure_url_array));
		if(!empty($coursesNotHavingBrochure)){
			$db_brochure = $listingextendedmodel->getMultipleListingsEBrochureInfo($coursesNotHavingBrochure,'course');
			if(count($db_brochure)>0) {
					foreach ($db_brochure as $key=>$url) {
						$brochure_url_array[$key] = $url;		
					}
			}
		}
		
		return array("courseBasicDetails" => $courseBasicDetails, "brochure_url_array" => $brochure_url_array);
	}

	private function getMultilocationsForCompare($courseList){
		$listingebrochuregenerator = $this->load->library('ListingEbrochureGenerator');
			
		$multiLocations = $listingebrochuregenerator->getMultilocationsForInstitute($courseList); 
			
			return $multiLocations;
	}
}
