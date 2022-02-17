<?php

class CategoryMobile extends ShikshaMobileWebSite_Controller
{     	
	function __construct()
	{
		parent::__construct();
		$this->load->config('mcommon5/mobi_config');
		$this->load->helper('mcommon5/mobile_html5');
		$this->load->library('category_list_client');
		$this->config->load('categoryPageConfig');
	}      

	//$params == string of parameters needed for category page.
	//fetches data and passes to view for rendering the category page.	
	function categoryPage($params, $newUrlFlag = false)
	{
		ob_start();
		
		//get Shortlisted course from Db/cookie 
		$courseShortArray = $this->getMShortlistedCourse();
		$displayData['courseMBAShortlistArray'] = $this->getMBAShortlistedCourses();
		$displayData['courseShortArray'] = $courseShortArray;
		//get brochureUrl from courseId
		$this->national_course_lib = $this->load->library('listing/NationalCourseLib');
		$displayData['brochureURL'] = $this->national_course_lib;
			
		$this->load->builder('CategoryPageBuilder','categoryList');
		$this->load->builder('CategoryBuilder','categoryList');
		$this->load->builder('LocationBuilder','location');
		//$categoryPageBuilder = new CategoryPageBuilder($params);
		$categoryPageBuilder 	= new CategoryPageBuilder($params, $newUrlFlag);
		
		//If study abroad category page, redirect the user to the new Category page
		if($categoryPageBuilder->getRequest()->isStudyAbroadPage())
		{
		    $this->redirectToNewAbroadCategoryPage($categoryPageBuilder);
		}
		
		$categoryBuilder = new CategoryBuilder;
		if(CP_SOLR_FLAG) {
			$displayData['categoryPage'] = $categoryPageBuilder->getCategroyPageSolr();
		} else {
			$displayData['categoryPage'] = $categoryPageBuilder->getCategoryPage();
		}
		$catPageRequestValidateLib = $this->load->library('categoryList/CategoryPageRequestValidations');
		$LDBCourseBuilder = new LDBCourseBuilder;
		$locationBuilder = new LocationBuilder;
		$request = $displayData['categoryPage']->getRequest();
		$catPageRequestValidateLib->redirectIfInvalidRequestParamsExist($request, $categoryBuilder, $LDBCourseBuilder, $locationBuilder);
		$isAjax = $this->input->is_ajax_request();
		$this->_validateAndRedirectNONRNRCategoryPageURLS($request, $isAjax);

		if($request->getCategoryId()==1 && $request->getSubCategoryId()==1 && $request->getCountryId()>0){
		       //This is an All Category Sub-Category page. Redirect to Homepage
	               //header("Location:".SHIKSHA_HOME,TRUE,301);
        	       //exit;
		}

	
		if($request->getStateId()<=1 && $request->getCityId()==1){	//This is a All City Page
			//Check if Cookie is set
			if(isset($_COOKIE['selectedLocation']) && $_COOKIE['selectedLocation'] != ''){
				//Redirect to Correct Category page. This redirection will only happen if his cookie is not All City Page
				if(!(isset($_COOKIE['selectedLocation']) && $_COOKIE['selectedLocation'] == '1' && $_COOKIE['selectedLocationType']=='city')){
					if($_COOKIE['selectedLocationType']=='city'){
						$request->setData(array('categoryId' => $request->getCategoryId(),'subCategoryId'=> $request->getSubCategoryId(),'cityId'=> $_COOKIE['selectedLocation'] ));
					}
					if($_COOKIE['selectedLocationType']=='state'){
						$request->setData(array('categoryId' => $request->getCategoryId(),'subCategoryId'=> $request->getSubCategoryId(),'stateId'=> $_COOKIE['selectedLocation'] ));
					}					
					$categoryPageURL = $request->getURL();
					header("Location: $categoryPageURL");
					exit;		
				}
			}
			else{	//If Cookie is not set. Show him Location Layer to select
				$locationRepository = $locationBuilder->getLocationRepository();
				$cityList = $locationRepository->getCitiesByMultipleTiers(array(1,2,3),2);
				$data['cityList'] = $cityList;
				$data['locationRepository'] = $locationRepository;
				$data['isShowHeader'] = true;
				$data['categoryId'] = $request->getCategoryId();
				$data['subCategoryId'] = $request->getSubCategoryId();
				$data['request'] = $request;
				$data['boomr_pageid'] = "category_listing";
				echo $this->load->view('locationLayerPage',$data);
				exit;
			}			
		}

			
			$locationRepository = $locationBuilder->getLocationRepository();
			$countriesArray = $locationRepository->getCountriesByRegion($request->getRegionId());
			$countryStr = "";
			foreach($countriesArray as $country){
			    $countryStr .= $country->getId() . ",";
			}
			if(count($countriesArray) == 0)
			{
			    $urlRequest = clone $request;
			    $countryStr = $urlRequest->getCountryId()  . ",";
			}
			storeTempUserData("countriesArray",$countryStr);		
		if(empty($request))
		{
			$error = '$displayData[\'categoryPage\']->getRequest();';
			$function = "categoryPage function in CategoryMobile controller";
			sendMailAlert("data not coming from backend issue in".$error."in".$function, "Category mobile controller Issue","vikas.k@shiksha.com");
		}
		
        // This was done for ticket id 1463 (apply default courselevel filters in case of abroad pages)
		
        $this->_setDefaultFilterForStudyAbroad($request);
		$this->checkForRedirection($displayData['categoryPage']);		
		$displayData['categoryRepository'] = $categoryBuilder->getCategoryRepository();
		$displayData['LDBCourseRepository'] = $LDBCourseBuilder->getLDBCourseRepository();
		$displayData['locationRepository'] = $locationBuilder->getLocationRepository();
		$subCategory = $displayData['categoryRepository']->find($request->getSubCategoryId());

		$displayData['request'] = $request;
		$displayData['subCategory']=$subCategory;
		$displayData['institutes'] = $displayData['categoryPage']->getInstitutes();
		//Memory Optimization : fetch CourseIds inspite of loading all similar course Objects
		 $displayData['instituteIdWithCourseIdMapping']  = $displayData['categoryPage']->getInstituteCourseList();

		 $displayData['dynamicLDBCoursesList'] = $displayData['categoryPage']->getDynamicLDBCoursesList();
		$displayData['dynamicCategoryList'] = $displayData['categoryPage']->getDynamicCategoryList();
		$displayData['dynamicLocationList'] = $displayData['categoryPage']->getDynamicLocationList();
		
		if(empty($displayData['institutes']))
		{
			$error = '$displayData[\'categoryPage\']->->getInstitutes();';
			$function = "categoryPage function in CategoryMobile controller";
//			sendMailAlert("data not coming from backend issue in".$error."in".$function, "Category mobile controller Issue","vikas.k@shiksha.com");
		}
		
		$courseIDs = array();
		foreach($displayData['institutes'] as $institute)
		{
			$courses = $institute->getCourses();
			foreach($courses as $course){
				$courseIDs[] = $course->getId();
				if($this->_checkMBATemplateEligibility(array($request->getSubCategoryId()), $course)) {
					$displayData['isMBATemplateByCourse'][$course->getId()] = true;
				}
			}
		}
		$displayData['pageType'] = 'mobileCategoryPage';
		
		//course review rating
		global $subCatsForCollegeReviews;
		if($subCatsForCollegeReviews[$request->getSubCategoryId()] == '1'){
			$NationalCourseLib         = new NationalCourseLib();
			$displayData['reviewData'] = $NationalCourseLib->getCourseReviewsData($courseIDs);
			$displayData['reviewData'] = $this->national_course_lib->getCollegeReviewsByCriteria($displayData['reviewData']);
		}
		
		$this->_checkForRedirectionForNewURLPattern($displayData['categoryPage']);
		/************Meta data for Seo ************/
		// LF-2980
		$totalResults = $displayData['categoryPage']->getTotalNumberOfInstitutes();
		$metaData = $request->getMetaData($totalResults);
		if(empty($totalResults)) {
			$displayData['addNoFollow'] = "true";
		}
		$displayData['m_meta_title'] = $metaData['title'];
		$displayData['m_meta_description'] = $metaData['description'];
		$displayData['m_meta_keywords']= $metaData['keywords'];
		$displayData['h1Title']= $metaData['h1Title'];
		$displayData['m_canonical_url']= $request->getCanonicalURL($request->getPageNumberForPagination());
		$displayData['mobile_website_pagination_count'] = $this->config->item('mobile_website_pagination_count');
		$RNRSubcategories = array_keys($this->config->item('CP_SUB_CATEGORY_NAME_LIST'));
		$displayData['subcategoriesChoosenForRNR'] 	= $RNRSubcategories;
		$displayData['categoryPageTypeFlag']    	= $newUrlFlag;

		$googleRemarketingParams = array(
				"categoryId" 	   => $request->getCategoryId(),
				"subcategoryId"    => $request->getSubCategoryId(),
				"countryId" 	   => $request->getCountryId(),
				"cityId" 		   => $request->getCityId(),
				"SpecializationID" => $request->getLDBCourseId()
		    ); 

                if($request->getCityId() && $request->getCityId()>1){   //Its a City page
                        setcookie('selectedLocation',$request->getCityId(),time() + 2592000,'/',COOKIEDOMAIN);
                        setcookie('selectedLocationType','city',time() + 2592000,'/',COOKIEDOMAIN);
                }
                else if($request->getStateId() && $request->getStateId()>1){    //Its a State page
                        setcookie('selectedLocation',$request->getStateId(),time() + 2592000,'/',COOKIEDOMAIN);
                        setcookie('selectedLocationType','state',time() + 2592000,'/',COOKIEDOMAIN);
                }                
                else if($request->getCityId() && $request->getCityId()>0){   //Its a All City page
                        setcookie('selectedLocation',$request->getCityId(),time() + 2592000,'/',COOKIEDOMAIN);
                        setcookie('selectedLocationType','city',time() + 2592000,'/',COOKIEDOMAIN);
                }
	
		//Set the Sub-Category choice of the user so that it can be selected be default when user comes to Homepage
		setcookie('subCategoryUserChoice',$request->getSubCategoryId(),time() + 2592000,'/',COOKIEDOMAIN);
	
		$displayData['googleRemarketingParams'] = $googleRemarketingParams;
		
		//Code added by Ankur for GA Custom variable tracking
                $displayData['subcatNameForGATracking'] = $displayData['categoryRepository']->find($displayData['request']->getSubCategoryId())->getName();
                $displayData['pageTypeForGATracking'] = 'CATEGORY_MOBILE';
		//$displayData['reviewData'] = $NationalCourseLib->getCourseReviewsData($courseIDs);

                $this->load->library('mcommon5/UrlGenerator');
                $domain_mapping = UrlGenerator::$domain_mapping;
		if(array_key_exists($_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] ,$domain_mapping)){
			$domain = $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] ;
			$displayData['urlString'] = $domain_mapping[$domain];
		}
		else{
			$displayData['urlString'] = $_SERVER['REQUEST_URI'];
		}
	
		if(isset($_COOKIE['show_recommendation']) && $_COOKIE['show_recommendation']=='yes'){
			if(isset($_COOKIE['recommendation_course']) && $_COOKIE['recommendation_course']>0){
                            $this->load->builder('ListingBuilder','listing');
			    $listingBuilder = new ListingBuilder;
                            $courseRepository = $listingBuilder->getCourseRepository();
                            $course = $courseRepository->find($_COOKIE['recommendation_course']);
                            $displayData['responseCreatedInstituteId'] = $course->getInstId();
                            $displayData['responseCreatedCourseId'] = $_COOKIE['recommendation_course'];			
			}
		}
		
		$validity = $this->checkUserValidation();
		if(in_array($request->getSubCategoryId(),array(23)) && $validity == "false") {
			$displayData['showGuideRegWidget'] = TRUE;
			$this->customizemmp_model = $this->load->model('customizedmmp/customizemmp_model');
			$mmp_details = $this->customizemmp_model->getMMPFormbySubCategoryId($request->getSubCategoryId(), 'newmmpcategory', 'N');
			$displayData['mmp_details']	= $mmp_details;
		}

		$displayData['tracking_keyid'] = MOBILE_NL_CATEGORY_TUPLE_COURSESHORTLIST;
		if(!$displayData['request']->isStudyAbroadPage())
		{

			//below line is used for conversion tracking purpose
		$displayData['trackingPageKeyId'] = 269;
		$displayData['guidetrackingPageKeyId'] = 270;
		$displayData['recommendationTrackingPageKeyId'] = 273;
		$displayData['comparetrackingPageKeyId']= 272;


			/*//Tracking Code
			$displayData['beaconTrackData'] = array(
			    'pageIdentifier' => 'Mobile_CategoryPage_National',
			    'pageEntityId' => $request->getPageKey(),
			    'extraData' => array('url'=>get_full_url())
			);*/
			//below lines is used for conversion tracking purpose
			$displayData['trackingpageIdentifier'] = "categoryPage";
			$displayData['trackingpageNo']= $request->getPageKey();
			$displayData['trackingcatID']=$request->getCategoryId();
			$displayData['trackingsubCatID']=$request->getSubCategoryId();
			$displayData['trackingcountryId']=$request->getCountryId();
			$displayData['trackingcityID']=$request->getCityId();
			$displayData['trackingLDBCourseId']=$request->getLDBCourseId();
			//below line is used for loading tracking library file
			$this->tracking = $this->load->library('common/trackingpages');
			$this->tracking->_pagetracking($displayData);
			
			$displayData['subCategoryId'] = $request->getsubCategoryId();
			$displayData['subCategoryId'] = $request->getsubCategoryId();
			$displayData['boomr_pageid'] = "category_listing";
			$displayData['trackForPages'] = true;
			if(isset($_POST['type']) && $_POST['type']=='fetchAjax'){
				$displayData['ajaxRequest'] = "true";
				if(!$displayData['institutes']){
					echo "noresults";
				}
				else{
					echo $this->load->view('mobileCategoryListings',$displayData);
				}
			}
			else if($request->getsubCategoryId() == 23){
				$affiliationSuffix=$this->config->item('CP_AFFILIATION_TO_VALUE_MAP');
				$displayData['affiliationSuffix'] = $affiliationSuffix;
				
				// Code added for adding College Review Widget - LDB-2209
				//$displayData['collegeReviewWidget'] = Modules::run('common/CommonReviewWidget/homePageWidget','mobile','CATEGORYPAGE_MOBILE');

				$displayData['collegeReviewWidget'] = "";
				$displayData['subCategoryIdForWidgetCheck'] = $request->getsubCategoryId();
				echo $this->load->view('mobileCategoryPageFulTimeMBA',$displayData);
			}
			else{
				$this->load->view('mobileCategoryPage',$displayData);
			}
		}else{
			$category_data = $displayData['categoryRepository']->find($request->getCategoryId());
			$displayData['category_data']=$category_data;
			$displayData['boomr_pageid'] = "category_listing";
			$displayData['isStudyAbroad'] = "true";
                        if(isset($_POST['type']) && $_POST['type']=='fetchAjax'){
                                $displayData['ajaxRequest'] = "true";
                                if(!$displayData['institutes']){
                                        echo "noresults";
                                }
                                else{
                                        echo $this->load->view('mobileCategoryListings',$displayData);
                                }
                        }
                        else{
                                $this->load->view('mobileCategoryPageSA',$displayData);
                        }
		}
	}
	
	private function _validateAndRedirectNONRNRCategoryPageURLS($request, $isAjax) {
	    global $categoryURLPrefixMapping;
		$RNRSubcategories = array_keys($this->config->item('CP_SUB_CATEGORY_NAME_LIST'));
		if(((strpos($_SERVER['HTTP_REFERER'], 'google') !== false) || (strpos($_SERVER['QUERY_STRING'], 'showpopup') !== false)) && ($request->getSubCategoryId() == 23)) {
			return;
		}
		if(!$isAjax 
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

	// Re-Direct To New Aborad Category Page
	public function redirectToNewAbroadCategoryPage(CategoryPageBuilder $categoryPageBuilder){
	    $abroadCategoryPageRequest = $this->load->library('categoryList/AbroadCategoryPageRequest');	    
	    $this->load->config('categoryList/studyAbroadRedirectionConfig');
	    $this->load->builder('LocationBuilder','location');
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

	//if user already selects the location before, then updating his location preference.
	function checkForRedirection($categoryPage)
	{
		$request = $categoryPage->getRequest();
		if(!$request->isStudyAbroadPage()){
			if(isset($_COOKIE['userCityPreference'])){
				$location = explode(":",$_COOKIE['userCityPreference']);
				$request->setData(array('cityId'=>$location[0],'stateId'=>$location[1]));
				$categoryPage->setRequest($request);
			}
		}
		if($request->isStudyAbroadPage()){
	        	if(isset($_COOKIE['catIdCookie'])){
		   		$request->setData(array('categoryId'=>$_COOKIE['catIdCookie'],'subCategoryId'=>1,'LDBCourseId'=>1));	
				$categoryPage->setRequest($request);
			}
			
			if(isset($_COOKIE['regionId_countryIdCookie']))
			{
				$regionId = $request->getRegionId();
				$regionCountryCookieArray = explode("|",$_COOKIE['regionId_countryIdCookie']);
				if($regionCountryCookieArray[0]==$regionId)
				{
		   			$request->setData(array('countryId'=>$regionCountryCookieArray[1]));	
					$categoryPage->setRequest($request);
				}
							
			}
			
			/*if(isset($_COOKIE['countryId'])){
		   		$request->setData(array('countryId'=>$_COOKIE['countryId']));	
				$categoryPage->setRequest($request);
			}*/		
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

        public function reorderFilterLocationList($params)
        {
            //$this->load->library('categoryPageRequest');
	    $this->load->library('categoryList/CategoryPageRequest');
	    // on changing course get data by ajax call
		$newURL = false;
		if($_POST['currentUrl']){
		    $currentUrl = $_POST['currentUrl'];
		    if(strpos($currentUrl,'-ctpg')!==false){
			    $tempArray = explode("/", $currentUrl);
			    $count = count($tempArray);
			    $currentUrl = $tempArray[$count-1];
			    $explodedCurrentUrl = explode("-ctpg", $currentUrl);
			    $params=$explodedCurrentUrl['0'];
			    $params = trim($params,'/');
			    $newURL = true;
		    }
		    else{
			    $explodedCurrentUrl = explode("-categorypage-", $currentUrl);
                            if(count($explodedCurrentUrl)<=1){
                                 $explodedCurrentUrl = explode("/categoryPage/", $currentUrl);
                            }
			    $params=$explodedCurrentUrl['1'];
		    }
		}

		if($newURL)
			$categoryPageRequest = new CategoryPageRequest($params,'RNRURL');
		else
			$categoryPageRequest = new CategoryPageRequest($params);
		

            $filter_list_source = $_POST['filter_list_source'];
			
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
                        $list_html = $this->_getListForCountries($params, $appliedFilters, $chkd_location_sequence_array,$newURL);
                    break;

                case 'topFilteredCities' :
                        $list_html = $this->_getListForTopFilteredCitites($params, $categoryPageRequest, $appliedFilters, $locationRepository, $chkd_location_sequence_array,$newURL);
                    break;

                default :
                    $list_html = $this->_getListForDynamicCitites($params, $categoryPageRequest, $appliedFilters, $locationRepository, $chkd_location_sequence_array,$newURL);
                    break;
            }

            echo json_encode($list_html);
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

        private function _getListForDynamicCitites($params, $categoryPageRequest, $appliedFilters, $locationRepository, $chkd_location_sequence_array,$newURL=false) {
                $selectedCityIdsArray = array();
                $selectedStateIdsArray = array();

                if(count($appliedFilters['city']) > 0) {
                    $selectedCityIdsArray = $appliedFilters['city'];
                }

                if(count($appliedFilters['state']) > 0) {
                    $selectedStateIdsArray = $appliedFilters['state'];
                }

                $this->load->builder('CategoryPageBuilder','categoryList');
		if($newURL)
			$categoryPageBuilder = new CategoryPageBuilder($params,'RNRURL');
		else
			$categoryPageBuilder = new CategoryPageBuilder($params);
		
		if(CP_SOLR_FLAG) {
			$categoryPage = $categoryPageBuilder->getCategroyPageSolr();
		} else {
			$categoryPage = $categoryPageBuilder->getCategoryPage();
		}
		$institutes = $categoryPage->getInstitutes();
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
                 $list_html = '<ul id="location_list_ul" class="location-list location-list2">';
                $list_html_selected = '<ul class="location-list location-list2" id="selectedbyu" style="display:none;">';

                if(!in_array($categoryPageRequest->getCountryId(), array(0, 1, 2))) {
                    $dynamicLocationList = $categoryPage->getDynamicLocationList();
                    $states = $locationRepository->getStatesByCountry($categoryPageRequest->getCountryId());
                    foreach($states as $key => $state){

                            $entityInfo['Name'] = $state->getName();
                            $entityInfo['Id'] = $state->getId();

                            if(in_array($state->getId(), $dynamicLocationList['states'])){
                                if($selectedStateIdsArrayLength && in_array($state->getId(), $selectedStateIdsArray)) {
                                    $location_html = $this->_getLocationLiHTML($entityInfo, TRUE, "state");
				    $checked_location=$location_html[1];
                                    $unchecked_location = $location_html[0];
                                    $list_html_selected .= $checked_location;
                                    $list_html .= $unchecked_location;
           
                                } else {
                                    $location_html = $this->_getLocationLiHTML($entityInfo, FALSE, "state");
                                    $checked_location=$location_html[1];
                                    $unchecked_location = $location_html[0];
                                    $list_html_selected .= $checked_location;
                                    $list_html .= $unchecked_location;
                                }
                            }
                    }
                }
                $countVar = 0;
                $regionHiddenFlag = 0;
                $html_part = "";
                foreach($filters['city']->getFilteredValues() as $cityId => $cityName){
                    $entityInfo['Name'] = $cityName;
                    $entityInfo['Id'] = $cityId;

                    if($selectedCityIdsArrayLength && in_array($cityId, $selectedCityIdsArray)) {
                                    $location_html = $this->_getLocationLiHTML($entityInfo, TRUE, "city");
                                    $checked_location=$location_html[1];
                                    $unchecked_location = $location_html[0];
                                    $list_html_selected .= $checked_location;
                                    $list_html .= $unchecked_location;
                    } else {
                        if($regionHiddenFlag != 1 && $countVar == 25) {
                            $nonchked_location_html .=  $this->_getMoreMsgHtml();
                            $regionHiddenFlag = 1;
                        }

                                   $location_html = $this->_getLocationLiHTML($entityInfo, FALSE, "city");
			            $checked_location=$location_html[1];
                                    $unchecked_location = $location_html[0];
                                    $list_html_selected .= $checked_location;
                                    $list_html .= $unchecked_location;
                    }

                    $countVar++;
                }


                /*if(count($chkd_location_html_array)) {
                    ksort($chkd_location_html_array);
                    $list_html .= implode(" ", $chkd_location_html_array).$chkd_location_html . $state_location_html . $nonchked_location_html;
                } else {
                    $list_html .= $state_location_html . $nonchked_location_html;
                }*/
             
		$list_html_selected.='<li id="last_selected"><label>&nbsp;</label></li>';
                $list_html_selected.='</ul>';
		$list_html .= '<li id="last"><label> <span>&nbsp;</span></label></li>';
                $list_html .= "</ul>";
		$final_list['all'] = $list_html;
                $final_list['selected'] = $list_html_selected;
                return $final_list;
        }

        private function _getListForCountries($params, $appliedFilters, $chkd_location_sequence_array,$newURL=false) {
                $selectedCountryIdsArray = array();
                if(count($appliedFilters['country']) > 0) {
                    $selectedCountryIdsArray = $appliedFilters['country'];
                }

                $this->load->builder('CategoryPageBuilder','categoryList');
		if($newURL)
			$categoryPageBuilder = new CategoryPageBuilder($params,'RNRURL');
		else
			$categoryPageBuilder = new CategoryPageBuilder($params);

		if(CP_SOLR_FLAG) {
			$categoryPage = $categoryPageBuilder->getCategroyPageSolr();
		} else {
			$categoryPage = $categoryPageBuilder->getCategoryPage();
		}
		$institutes = $categoryPage->getInstitutes();
                $filters = $categoryPage->getFilters();

                $countVar = 0;
                $regionHiddenFlag = 0;
                $list_html = '<ul id="location_list_ul" class="location-list location-list2">';
		$list_html_selected = '<ul class="location-list location-list2" id="selectedbyu" style="display:none;">';
                $html_part = "";
                $chkd_location_html = "";
                $state_location_html = "";
		$nonchked_location_html ="";
                $chkd_location_html_array = array();
                $chkd_location_sequence_array_length = count($chkd_location_sequence_array);
                $selectedCountryIdsArrayLength = count($selectedCountryIdsArray);
                foreach($filters['country']->getFilteredValues() as $countryId=>$countryName){
                    $entityInfo['Name'] = $countryName;
                    $entityInfo['Id'] = $countryId;

                    if($selectedCountryIdsArrayLength && in_array($countryId, $selectedCountryIdsArray)) {
				$location_html= $this->_getLocationLiHTML($entityInfo, TRUE, "country");
		
				$checked_location=$location_html[1];
				$unchecked_location = $location_html[0];
			        $list_html_selected .= $checked_location;
				$nonchked_location_html .= $unchecked_location;
                    } else {
                        if($countVar == 25) {
                            $nonchked_location_html .=  $this->_getMoreMsgHtml();
                            $regionHiddenFlag = 1;
                       }
                             $location_html = $this->_getLocationLiHTML($entityInfo, FALSE, "country");
     
			     $checked_location=$location_html[1];
			     $list_html_selected .= $checked_location;	
			     $unchecked_location = $location_html[0];
                             $nonchked_location_html .=$unchecked_location;
                    }
                    $countVar++;
                }
                if(count($chkd_location_html_array)) {
                    ksort($chkd_location_html_array);
                    $list_html .= implode(" ", $chkd_location_html_array) . $nonchked_location_html;
                } else {
                    $list_html .= $nonchked_location_html;
                }
                  $list_html_selected.='<li id="last_selected"><label>&nbsp;</label></li>';
                  $list_html_selected.='</ul>';
		$list_html .= '<li id="last"><label> <span>&nbsp;</span></label></li>';
                $list_html .= "</ul>";
		 $final_list['all'] = $list_html;
                $final_list['selected'] = $list_html_selected;
                return $final_list;

        }

        private function _getLocationLiHTML($entityInfo, $isDefaultChecked, $entityType) {
            $checked = "";
            if($isDefaultChecked) {
                $checked = 'checked="checked"';
		$flag='true';
            }

            $entityName = $entityInfo['Name'];
            $entityId = $entityInfo['Id'];
            $liElmName = $entityType."[]";
            $liElmId = $entityType."_".$entityId;
            
                if($flag=="true") {   $html[1]='<li id="cLI'.$entityId.'_new"><label id ="L_cLI'.$entityId.'_new"><input onClick="setCPLocation(this.id);" name="selected_country[]" value="'.$entityId.'" id="cI'.$liElmId.'_new" type="checkbox" '.$checked.' onChange="queueThisLocation(this);" /> <span id="cN'.$entityId.'_new">'.$entityName.'</span></label></li>';}

            $html[0] = "<li id='cLI$entityId'><label id='L_cLI$entityId'><input onClick='setCPLocation(this.id);' type='checkbox' id='cI$liElmId' name='$liElmName' value='$entityId' $checked onChange='queueThisLocation(this);' /> <span id='cN$entityId'>$entityName</span></label></li>";

	    return $html;
        }

        private function _getMoreMsgHtml() {
            //$html = "<div id='moreMsgDiv' style='text-align:right;padding-right:8px;'><a href='javascript:void(0);' onClick='javascript: showAllRegions();'>+ More </a> </div>";
            //$html .=  "<div id='allRegionsContainer' style='display:none;'>";
            //return $html;
        }

        public function getCategoryPageLocation($params, $newURLFlag = 0)
        {
		$this->load->library('categoryList/CategoryPageRequest');
		$category_list_client = new Category_list_client;
		// on changing course get data by ajax

		$newURL = false;
		if($_POST['currentUrl']){
		    $currentUrl = $_POST['currentUrl'];
		    if(strpos($currentUrl,'-ctpg')!==false){
			    $tempArray = explode("/", $currentUrl);
			    $count = count($tempArray);
			    $currentUrl = $tempArray[$count-1];
			    $explodedCurrentUrl = explode("-ctpg", $currentUrl);
			    $params=$explodedCurrentUrl['0'];
			    $params = trim($params,'/');
			    $newURL = true;
		    }else if(strpos($currentUrl,'/colleges/')!==false)
		    {
			$tempArray = explode("/", $currentUrl);
			$params = end($tempArray);
			$newURL = true;
			$countryId = 2;
		    }
		    else{
			    $explodedCurrentUrl = explode("-categorypage-", $currentUrl);
                            if(count($explodedCurrentUrl)<=1){
                                 $explodedCurrentUrl = explode("/categoryPage/", $currentUrl);
                            }
			    $params=$explodedCurrentUrl['1'];
		    }

		if($newURL)
			$categoryPageRequest = new CategoryPageRequest($params,'RNRURL');
		else
			$categoryPageRequest = new CategoryPageRequest($params);


			
		//$urlData = $category_list_client->getRequestDataFromKey($params, $newURLFlag);
		//$categoryPageRequest->setNewURLFlag(1);
		//$categoryPageRequest->setData($urlData);
		
            $appliedFilters = $categoryPageRequest->getAppliedFilters();
			$this->load->builder('LocationBuilder','location');
            $locationBuilder = new LocationBuilder;
            $locationRepository = $locationBuilder->getLocationRepository();
            $final_list = $this->_getCityList($params, $categoryPageRequest, $appliedFilters, $locationRepository,$newURL);
            echo json_encode($final_list);
	    }
	    else{
		echo '';
	    }
        }

        private function _getCityList($params, $categoryPageRequest, $appliedFilters, $locationRepository,$newURL=false) {
                $selectedCityIdsArray = array();
                if(count($appliedFilters['city']) > 0) {
                    $selectedCityIdsArray = $appliedFilters['city'];
                }

                $this->load->builder('CategoryPageBuilder','categoryList');
                $categoryPageBuilder = new CategoryPageBuilder();
                $categoryPageBuilder->setRequest($categoryPageRequest);
		if(CP_SOLR_FLAG) {
			$categoryPage = $categoryPageBuilder->getCategroyPageSolr();
		} else {
			$categoryPage = $categoryPageBuilder->getCategoryPage();
		}
		$institutes = $categoryPage->getInstitutes();
                $filters = $categoryPage->getFilters();

                $selectedCityIdsArrayLength = count($selectedCityIdsArray);
		$list_html = 'No Location Found';
		$list_html_selected = '<ul class="location-list location-list2" id="selectedbyu" style="display:none;">';
		$isAnythingSelected = false;
		
		$localityFilterValues = $filters['locality']->getFilteredValues();
		$locality_html = '';
		if($filters['locality'] && $categoryPageRequest->getCityId() != 1)
		{
			if(count($localityFilterValues) > 0) {
				$locality_html = '<div style="padding: 5px;font-size: 0.9em;background-color:#CCCCCC;">&nbsp;Localities in '.$categoryPage->getCity()->getName().'</div>';
				$locality_html .= '<ul class="location-list location-list2" id="layer-list-ul-2">';

				foreach($localityFilterValues as $zone=>$filter){
					$list_html_selected_temp = $locality_html_temp = '';
					$isZoneSelected = true;

					//Create the Locality HTML for Locality tab and Selected by you tab
					foreach($filter['localities'] as $locality=>$filter1){ 
						$checked = '';
						if(in_array($locality,$appliedFilters['locality'])){
								$checked = "checked";
								$list_html_selected_temp .="<li id='cLI".$locality."_locality_new'><label><input id='inputLocality".$locality."_new' type='checkbox' $checked value='$locality' class='zonelocality$zone' name='selected_locality[]' onclick='applyLocality($zone); setCPLocation(this.id);' onChange='queueThisLocation(this);' /> <span id='cN".$locality."_locality_new'>$filter1</span></label></li>";
								$isAnythingSelected = true;
						}
						else{
							$isZoneSelected = false;		
						}
						$locality_html_temp .= "<li id='cLI".$locality."_locality'><label style='padding-left:30px;'><input id='inputLocality$locality' type='checkbox' $checked value='$locality' class='zonelocality$zone' name='locality[]' onclick='applyLocality($zone); setCPLocation(this.id);' onChange='queueThisLocation(this);' /> <span id='cN".$locality."_locality'>$filter1</span></label></li>";
					}
					
					//Create the ZONE HTML for Locality tab and Selected by you tab.
					//If all the localities are selected, select the Zone too. If not, simply add the Zone as unselected
					if($isZoneSelected){
						$locality_html .= "<li id='cLI".$zone."_zone'><label><input checked type='checkbox' id='zone$zone' value='$zone' onclick='applyFullZone($zone); setCPLocation(this.id);' /> <span id='cN".$zone."_zone'>".$filter['name']."</span></label></li>";
						$locality_html .= $locality_html_temp;
						//$list_html_selected .= "<li id='cLI".$zone."_zone_new'><label><input checked type='checkbox' id='zone".$zone."_new' value='$zone' onclick='applyFullZone($zone); setCPLocation(this.id);'  /> <span id='cN".$zone."_zone_new'>".$filter['name']."</span></label></li>";
						$list_html_selected .= $list_html_selected_temp;
					}
					else{
						$locality_html .= "<li id='cLI".$zone."_zone'><label><input type='checkbox' id='zone$zone' value='$zone' onclick='applyFullZone($zone); setCPLocation(this.id);' /> <span id='cN".$zone."_zone'>".$filter['name']."</span></label></li>";
						$locality_html .= $locality_html_temp;
						$list_html_selected .= $list_html_selected_temp;						
					}

					//if(in_array($zone,$appliedFilters['zone']) && count($appliedFilters['locality'])<1){
					//	$locality_html .= "<script>$('zone$zone').checked = true;applyFullZone('$zone');</script>";
					//}
					
				}
				$locality_html .= "<li><label>&nbsp;</label></li>";
				$locality_html .= '</ul>';
			}
		}
		
		if($filters['city'] && !($categoryPageRequest->getCityId()==1 && $categoryPageRequest->getStateId()==1)){
			$cityFilterValues = $filters['city']->getFilteredValues();
			if(count($cityFilterValues) > 1){
                                if($locality_html!=''){
                                        $list_html = '<div class="location-list2" style="padding: 5px;font-size: 0.9em;background-color:#CCCCCC;">&nbsp;Cities near '.$categoryPage->getCity()->getName().'</div>';
                                }
				if($list_html=='' || $list_html=='No Location Found')
					$list_html = '<ul class="location-list location-list2" id="layer-list-ul">';
				else
					$list_html .= '<ul class="location-list location-list2" id="layer-list-ul">';
				foreach($cityFilterValues as $key=>$city){
					if($key == $categoryPageRequest->getCityId()){
						continue;
					}
					$checked = '';
					if(in_array($key,$appliedFilters['city'])){
						$checked = "checked";
					}
					
					if(strcmp($checked,"checked")==0){
						$list_html_selected.='<li id="cLI'.$key.'_new"><label id ="L_cLI'.$key.'_new"><input onClick="setCPLocation(this.id);" name="selected_city[]" value="'.$key.'" id="cI'.$key.'_new" type="checkbox" '.$checked.' onChange="queueThisLocation(this);" /> <span id="cN'.$key.'_new">'.$city.'</span></label></li>';
						$isAnythingSelected = true;
					}
					$list_html .= '<li id="cLI'.$key.'"><label id ="L_cLI'.$key.'"><input onClick="setCPLocation(this.id);" name="city[]" value="'.$key.'" id="cI'.$key.'" type="checkbox" '.$checked.' onChange="queueThisLocation(this);" /> <span id="cN'.$key.'">'.$city.'</span></label></li>';
				}
				$list_html .= '<li id="last"><label>&nbsp;</label></li>';
                                $list_html .= '</ul>';
			}
		}
		else if($filters['city']){
			$cityList = $locationRepository->getCitiesByMultipleTiers(array(1,2,3),2);
			$cityFilterValues = $filters['city']->getFilteredValues();
			if(count($cityFilterValues) > 1){
                                if($locality_html!=''){
                                        $list_html = '<div class="location-list2" style="padding: 5px;font-size: 0.9em;background-color:#CCCCCC;">&nbsp;Cities near '.$categoryPage->getCity()->getName().'</div>';
                                }

                                if($list_html=='' || $list_html=='No Location Found')
                                        $list_html = '<ul class="location-list location-list2" id="layer-list-ul">';
                                else
                                        $list_html .= '<ul class="location-list location-list2" id="layer-list-ul">';

				foreach($cityList[1] as $city){
					if(array_search($city->getName(),$cityFilterValues)){
						$checked = '';
						if(in_array($city->getId(),$appliedFilters['city'])){
							$checked = "checked";
						}
					        if($checked=="checked"){
							$list_html_selected.= '<li id="cLI'.$city->getId().'_new"><label><input onClick="setCPLocation(this.id);" name="selected_city[]" value="'.$city->getId().'" id="cI'.$city->getId().'_new" type="checkbox" '.$checked.' onChange="queueThisLocation(this);"/> <span id="cN'.$city->getId().'_new">'.$city->getName().'</span></label></li>';
							$isAnythingSelected = true;
						}

						$list_html .= '<li id="cLI'.$city->getId().'"><label><input onClick="setCPLocation(this.id);" name="city[]" value="'.$city->getId().'" id="cI'.$city->getId().'" type="checkbox" '.$checked.' onChange="queueThisLocation(this);" /> <span id="cN'.$city->getId().'">'.$city->getName().'</span></label></li>';
					}
				}
				foreach($cityList[2] as $city){
					if(array_search($city->getName(),$cityFilterValues)){
						$checked = '';
						if(in_array($city->getId(),$appliedFilters['city'])){
							$checked = "checked";
						}
						if(strcmp($checked,"checked")==0) {
							$list_html_selected.='<li id="cLI'.$city->getId().'_new"><label><input onClick="setCPLocation(this.id);" name="selected_city[]" value="'.$city->getId().'" id="cI'.$city->getId().'_new" type="checkbox" '.$checked.' onChange="queueThisLocation(this);"/> <span id="cN'.$city->getId().'_new">'.$city->getName().'</span></label></li>';
							$isAnythingSelected = true;
						}
						$list_html .= '<li id="cLI'.$city->getId().'"><label><input onClick="setCPLocation(this.id);" name="city[]" value="'.$city->getId().'" id="cI'.$city->getId().'" type="checkbox" '.$checked.' onChange="queueThisLocation(this);"/> <span id="cN'.$city->getId().'">'.$city->getName().'</span></label></li>';
					}
				}
		
				foreach(($cityList[3]) as $city){
					if(array_search($city->getName(),$cityFilterValues)){
						$checked = '';
						if(in_array($city->getId(),$appliedFilters['city'])){
							$checked = "checked";
						}
						if($checked=="checked") {
							$list_html_selected.='<li id="cLI'.$city->getId().'_new"><label id ="L_cLI'.$city->getId().'_new"><input onClick="setCPLocation(this.id);" name="selected_city[]" value="'.$city->getId().'" id="cI'.$city->getId().'_new" type="checkbox" '.$checked.' onChange="queueThisLocation(this);" /> <span id="cN'.$city->getId().'_new">'.$city->getName().'</span></label></li>';
							$isAnythingSelected = true;
						}
						$list_html .= '<li id="cLI'.$city->getId().'"><label id = "L_cLI'.$city->getId().'"><input onClick="setCPLocation(this.id);" name="city[]" value="'.$city->getId().'" id="cI'.$city->getId().'" type="checkbox" '.$checked.' onChange="queueThisLocation(this);"/> <span id="cN'.$city->getId().'">'.$city->getName().'</span></label></li>';
					}
				} 
				$list_html .= '<li id = "last"><label>&nbsp;</label></li>';
				$list_html .= '</ul>';
			}
		}

		$list_html_selected.= '<li id="last_selected"><label>&nbsp;</label></li>';
		$list_html_selected.= '</ul>';
	
		$final_list['selected'] = $list_html_selected;
		$final_list['all'] = $list_html; 
		$final_list['locality'] = $locality_html;
                return $final_list;
        }
		
	private function _checkForRedirectionForNewURLPattern($categoryPage)
	{
		$RNRSubcategories = array_keys($this->config->item('CP_SUB_CATEGORY_NAME_LIST'));
		$request = $categoryPage->getRequest();
		if( ( !$request->isStudyAbroadPage()) &&
			( in_array($request->getSubCategoryId(), $RNRSubcategories)) &&
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
	
	
	function getCategoryPageFiltersByAjax(){
		return;
		$currentUrl = $_POST['currentUrl'];
		
		$newURL = false;
		if($_POST['currentUrl']){
		    $currentUrl = $_POST['currentUrl'];
		    if(strpos($currentUrl,'-ctpg')!==false){
			    $tempArray = explode("/", $currentUrl);
			    $count = count($tempArray);
			    $currentUrl = $tempArray[$count-1];
			    $explodedCurrentUrl = explode("-ctpg", $currentUrl);
			    $params=$explodedCurrentUrl['0'];
			    $params = trim($params,'/');
			    $newURL = true;
			    $countryId = 2;
		    }else if(strpos($currentUrl,'/colleges/')!==false)
		    {
			$tempArray = explode("/", $currentUrl);
			$params = end($tempArray);
			$newURL = true;
			$countryId = 2;
		    }
		    else{
			    $explodedCurrentUrl = explode("-categorypage-", $currentUrl);
			    if(count($explodedCurrentUrl)<=1){
				 $explodedCurrentUrl = explode("/categoryPage/", $currentUrl);
			    }
			    $params=$explodedCurrentUrl['1'];
			    $paramsArray = explode("-",$params);
			    $countryId = $paramsArray['7'];
		    }
		
		$this->load->builder('CategoryPageBuilder','categoryList');
		$this->load->builder('CategoryBuilder','categoryList');
		if($newURL){
			$categoryPageBuilder = new CategoryPageBuilder($params,'RNRURL');
		}
		else{
			$categoryPageBuilder = new CategoryPageBuilder($params);
		}
		$categoryBuilder = new CategoryBuilder;
		if(CP_SOLR_FLAG) {
			$displayData['categoryPage'] = $categoryPageBuilder->getCategroyPageSolr();
		} else {
			$displayData['categoryPage'] = $categoryPageBuilder->getCategoryPage();
		}
		$request = $displayData['categoryPage']->getRequest();
		$LDBCourseBuilder = new LDBCourseBuilder;
		$displayData['LDBCourseRepository'] = $LDBCourseBuilder->getLDBCourseRepository();
		$displayData['request']=$request;
		$displayData['categoryRepository'] = $categoryBuilder->getCategoryRepository();
		$displayData['institutes'] = $displayData['categoryPage']->getInstitutes();
		
		// condition to check if it is india or abroad
		if($countryId=='2' && $request->getsubCategoryId() == 23){
			$affiliationSuffix=$this->config->item('CP_AFFILIATION_TO_VALUE_MAP');
			$displayData['affiliationSuffix'] = $affiliationSuffix;
			echo $this->load->view('refineFullTimeMBA',$displayData);
		}
		else if($countryId=='2')
			echo $this->load->view('refine',$displayData);			
		else
			echo $this->load->view('refineSA',$displayData);

                }
                else{
                        return '';
                }

	}
	
	function getExamFiltersByAjax(){
		return;
		$currentUrl = $_POST['currentUrl'];

		$newURL = false;
		if($_POST['currentUrl']){
		    $currentUrl = $_POST['currentUrl'];
		    if(strpos($currentUrl,'-ctpg')!==false){
			    $tempArray = explode("/", $currentUrl);
			    $count = count($tempArray);
			    $currentUrl = $tempArray[$count-1];
			    $explodedCurrentUrl = explode("-ctpg", $currentUrl);
			    $params=$explodedCurrentUrl['0'];
			    $params = trim($params,'/');
			    $newURL = true;
		    }else if(strpos($currentUrl,'/colleges/')!==false)
		    {
			$tempArray = explode("/", $currentUrl);
			$params = end($tempArray);
			$newURL = true;
			$countryId = 2;
		    }
		    else{
			    $explodedCurrentUrl = explode("-categorypage-", $currentUrl);
                            if(count($explodedCurrentUrl)<=1){
                                 $explodedCurrentUrl = explode("/categoryPage/", $currentUrl);
                            }
			    $params=$explodedCurrentUrl['1'];
		    }

		$this->load->builder('CategoryPageBuilder','categoryList');
		$this->load->builder('CategoryBuilder','categoryList');
		if($newURL){
			$categoryPageBuilder = new CategoryPageBuilder($params,'RNRURL');
		}
		else{
			$categoryPageBuilder = new CategoryPageBuilder($params);
		}
		$categoryBuilder = new CategoryBuilder;
		if(CP_SOLR_FLAG) {
			$displayData['categoryPage'] = $categoryPageBuilder->getCategroyPageSolr();
		} else {
			$displayData['categoryPage'] = $categoryPageBuilder->getCategoryPage();
		}
		
		$this->load->library('listing/cache/ListingCache');
		$this->listingCache = new ListingCache;
		$exam_list = $this->listingCache->getExamsList();
		if(empty($exam_list)){
			$exam_list = $categoryClient->getTestPrepCoursesList(1);
			$this->listingCache->storeExamsList($exam_list);
		}
		$displayData['exam_list'] 				= $this->_prepareExamList($exam_list);
		$displayData['exam_list']['MBA'][] 		= "GMAT";
		
		$request = $displayData['categoryPage']->getRequest();
		$displayData['request']=$request;
		$displayData['institutes'] = $displayData['categoryPage']->getInstitutes();
		
		if($request->getSubCategoryId()=='23'){
			echo $this->load->view('refine_exam_mba_ctpg',$displayData);	
		}else if($request->getSubCategoryId()=='56'){
			
			echo $this->load->view('refine_exam_btech_ctpg',$displayData);
			
		}else{
			echo $this->load->view('refine_exam',$displayData);
		}

                }
                else{
                        return '';
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
	
	
	function getFeesFiltersByAjax(){
		$currentUrl = $_POST['currentUrl'];

		$newURL = false;
		if($_POST['currentUrl']){
		    $currentUrl = $_POST['currentUrl'];
		    if(strpos($currentUrl,'-ctpg')!==false){
			    $tempArray = explode("/", $currentUrl);
			    $count = count($tempArray);
			    $currentUrl = $tempArray[$count-1];
			    $explodedCurrentUrl = explode("-ctpg", $currentUrl);
			    $params=$explodedCurrentUrl['0'];
			    $params = trim($params,'/');
			    $newURL = true;
		    }else if(strpos($currentUrl,'/colleges/')!==false)
		    {
			$tempArray = explode("/", $currentUrl);
			$params = end($tempArray);
			$newURL = true;
			$countryId = 2;
		    }
		    else{
			    $explodedCurrentUrl = explode("-categorypage-", $currentUrl);
			    $params=$explodedCurrentUrl['1'];
		    }
		

		$this->load->builder('CategoryPageBuilder','categoryList');
		$this->load->builder('CategoryBuilder','categoryList');
		if($newURL){
			$categoryPageBuilder = new CategoryPageBuilder($params,'RNRURL');
		}
		else{
			$categoryPageBuilder = new CategoryPageBuilder($params);
		}
		$categoryBuilder = new CategoryBuilder;
		if(CP_SOLR_FLAG) {
			$displayData['categoryPage'] = $categoryPageBuilder->getCategroyPageSolr();
		} else {
			$displayData['categoryPage'] = $categoryPageBuilder->getCategoryPage();
		}
		$request = $displayData['categoryPage']->getRequest();
		$displayData['request']=$request;
		$displayData['institutes'] = $displayData['categoryPage']->getInstitutes();
		
		echo $this->load->view('refine_fees',$displayData);
                }
                else{
                        return '';
                }

	}
	
	function getLocationFiltersByAjax(){
		return;
		$currentUrl = $_POST['currentUrl'];
		$newURL = false;
		if($_POST['currentUrl']){
		    $currentUrl = $_POST['currentUrl'];
		    if(strpos($currentUrl,'-ctpg')!==false){
			    $tempArray = explode("/", $currentUrl);
			    $count = count($tempArray);
			    $currentUrl = $tempArray[$count-1];
			    $explodedCurrentUrl = explode("-ctpg", $currentUrl);
			    $params=$explodedCurrentUrl['0'];
			    $params = trim($params,'/');
			    $newURL = true;
			    $countryId = 2;
		    }else if(strpos($currentUrl,'/colleges/')!==false)
		    {
			$tempArray = explode("/", $currentUrl);
			$params = end($tempArray);
			$newURL = true;
			$countryId = 2;
		    }
		    else{
			    $explodedCurrentUrl = explode("-categorypage-", $currentUrl);
                            if(count($explodedCurrentUrl)<=1){
                                 $explodedCurrentUrl = explode("/categoryPage/", $currentUrl);
                            }
			    $params=$explodedCurrentUrl['1'];
			    $paramsArray = explode("-",$params);
			    $countryId = $paramsArray['7'];
		    }

		$this->load->builder('CategoryPageBuilder','categoryList');
		$this->load->builder('CategoryBuilder','categoryList');
		if($newURL){
			$categoryPageBuilder = new CategoryPageBuilder($params,'RNRURL');
		}
		else{
			$categoryPageBuilder = new CategoryPageBuilder($params);
		}
		$categoryBuilder = new CategoryBuilder;
		if(CP_SOLR_FLAG) {
			$displayData['categoryPage'] = $categoryPageBuilder->getCategroyPageSolr();
		} else {
			$displayData['categoryPage'] = $categoryPageBuilder->getCategoryPage();
		}
		$request = $displayData['categoryPage']->getRequest();
		$displayData['request']=$request;
		$displayData['institutes'] = $displayData['categoryPage']->getInstitutes();
		
		// condition to check if it is india or abroad
		if($countryId=='2')
			echo $this->load->view('refine_location',$displayData);
		else
			echo $this->load->view('refine_locationSA',$displayData);
		}
		else{
			return '';
		}
	}
	
	function getCategoryPageLocationsByAjax(){
		return;
		$currentUrl = $_POST['currentUrl'];
		$newURL = false;
		if($_POST['currentUrl']){
		    $currentUrl = $_POST['currentUrl'];
		    if(strpos($currentUrl,'-ctpg')!==false){
			    $tempArray = explode("/", $currentUrl);
			    $count = count($tempArray);
			    $currentUrl = $tempArray[$count-1];
			    $explodedCurrentUrl = explode("-ctpg", $currentUrl);
			    $params=$explodedCurrentUrl['0'];
			    $params = trim($params,'/');
			    $newURL = true;
			    $countryId = 2;
		    }else if(strpos($currentUrl,'/colleges/')!==false)
		    {
			$tempArray = explode("/", $currentUrl);
			$params = end($tempArray);
			$newURL = true;
			$countryId = 2;
		    }
		    else{
			    $explodedCurrentUrl = explode("-categorypage-", $currentUrl);
                            if(count($explodedCurrentUrl)<=1){
                                 $explodedCurrentUrl = explode("/categoryPage/", $currentUrl);
                            }
			    $params=$explodedCurrentUrl['1'];
			    $paramsArray = explode("-",$params);
			    $countryId = $paramsArray['7'];
		    }

	            $this->load->builder('CategoryPageBuilder','categoryList');
		    $this->load->builder('CategoryBuilder','categoryList');
		    if($newURL){
			$categoryPageBuilder = new CategoryPageBuilder($params,'RNRURL');
		    }
		    else{
			$categoryPageBuilder = new CategoryPageBuilder($params);
		    }
		    $categoryBuilder = new CategoryBuilder;
		    if(CP_SOLR_FLAG) {
			$displayData['categoryPage'] = $categoryPageBuilder->getCategroyPageSolr();
		    } else {
			$displayData['categoryPage'] = $categoryPageBuilder->getCategoryPage();
		    }
		    $request = $displayData['categoryPage']->getRequest();
		    $locationBuilder = new LocationBuilder;
		    $displayData['locationRepository'] = $locationBuilder->getLocationRepository();
		    $displayData['categoryRepository'] = $categoryBuilder->getCategoryRepository();
		    $displayData['request']=$request;
		    $displayData['institutes'] = $displayData['categoryPage']->getInstitutes();
		
		    // condition to check if it is india or abroad
		    if($countryId=='2')
			echo $this->load->view('categoryLocationLayer',$displayData);
		    else
			echo $this->load->view('categoryLocationLayerSA',$displayData);
		}
		else{
			return '';
		}
	}
	function renderCategoryPage($params, $newUrlFlag = false){
		$this->load->builder('CategoryPageBuilder','categoryList');
		$this->load->builder('CategoryBuilder','categoryList');
		$categoryPageBuilder 	= new CategoryPageBuilder($params, $newUrlFlag);
		if(CP_SOLR_FLAG) {
			$displayData['categoryPage'] = $categoryPageBuilder->getCategroyPageSolr();
		} else {
			$displayData['categoryPage'] = $categoryPageBuilder->getCategoryPage();
		}
		$request = $displayData['categoryPage']->getRequest();
		$displayData['institutes'] = $displayData['categoryPage']->getInstitutes();
		
		if(($request->getSubCategoryId()=='23' && $request->getCategoryId()=='3') || 
		   ($request->getSubCategoryId()=='56' && $request->getCategoryId()=='2')){
			$this->categoryPage($params,$newUrlFlag);
		}else{
			echo Modules::run('categoryList/CategoryList/categoryPage',$params,$newUrlFlag);
		}
	}
	
	function getMShortlistedCourse()
	{
		//1. Fetch the Shortlisted courses for the user
		$validity = $this->checkUserValidation();
		$courseShortArray[] = '' ;
		if($validity != "false" && $validity[0]['userid'] !='')
		{
			$courseId = Modules::run('myShortlist/MyShortlist/getShortlistedCourse', $validity[0]['userid'], 'national');
			setcookie('mob_shortlist_Count',sizeof($courseId),time() + 2592000,'/',COOKIEDOMAIN);
		}
        echo sizeof($courseId);	
	}
	
	private function _checkMBATemplateEligibility($courseCategory, $course) {
		$flag = $this->national_course_lib->checkForMBATemplateEligibility($courseCategory, $course);
		return $flag;
	}
	
	function getMBAShortlistedCourses() {
		$this->load->model('listing/shortlistlistingmodel');
        $shortlistModel = new shortlistlistingmodel;
		
		$courseShortArray[] = '' ;
		$validity = $this->checkUserValidation();
		if($validity != "false") {
			$resultArray = $shortlistModel->fetchIfUserHasShortListedCourses(array('userId'=>$validity[0]['userid'],'scope'=>'national'));
			$courseShortArray = $resultArray['courseIds'];
		}
		return $courseShortArray;
	}
}
