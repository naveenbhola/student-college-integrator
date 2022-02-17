<?php

class Msearch extends ShikshaMobileWebSite_Controller {

	private $searchWrapper;
	private $searchCommon;
	private $searchSponsored;
	private $searchRepository;
	private $msearchlib;

	public function __construct(){

		parent::__construct();
		$this->load->config('mcommon5/mobi_config');
		//$this->load->helper('mcommon5/mobile');
		$this->load->helper('mcommon5/mobile_html5');

		parse_str($_SERVER['QUERY_STRING'],$_GET);
		$this->load->library('msearch5/msearchLib');
		$this->load->helper('search/SearchUtility');
		$this->config->load('search_config');
		$this->load->builder('SearchBuilder', 'search');
		$this->load->builder('CategoryPageBuilder','categoryList');
		$this->load->library('categoryList/categoryPageRequest');
		$this->load->library(array('category_list_client', 'searchmatrix/SearchMatrixLib'));
		$this->load->builder("ListingBuilder", "listing");
		$this->searchCommon 	= SearchBuilder::getSearchCommon();
		$this->searchRepository = SearchBuilder::getSearchRepository();
		$this->searchSponsored  = SearchBuilder::getSearchSponsored();
		$this->msearchlib = new msearchLib($this->ci_mobile_capbilities);

		$this->load->helper(array('form', 'url','image','shikshautility'));
		$this->userStatus = $this->checkUserValidation();
		if(isset($this->userStatus[0]) && is_array($this->userStatus[0])) {
			$this->userid=$this->userStatus[0]['userid'];
		} else {
			$this->userid=-1;
		}

	}

	public function index(){
		if($this->input->get('q')){
			redirect(SHIKSHA_HOME.'/search/?keyword='.$_REQUEST['q'],'location',301);
		}

		global $listings_with_localities;
		$GLOBALS['localityArray'] = array();
		$GLOBALS['studyAbroadIds'] = array();
		$GLOBALS['instituteWithMultipleCourseLocations'] = array();

		// get url parameters and validate it
		$urlParams = $this->searchCommon->readSearchParams('SEARCH');
			
		$keyword  = trim($urlParams['keyword']);
		// check if keyword is empty
		if(empty($keyword)) {
			$this->showSearchHome('Please Enter Keyword to Search');
			return false;
		}

		// set additional parameters
		$urlParams['search_source']   = 'SEARCH';
		$urlParams['institute_rows'] = msearchLib::$number_of_result;
		$urlParams = $this->msearchlib->validateUrlParams($urlParams);
		$this->msearchlib->url_params = $urlParams;
		$results = $this->searchRepository->search($urlParams);
	
		if(empty($results['facets']['location'])) {
			$urlParams['search_type'] = 'institute';
			$results = $this->searchRepository->search($urlParams);
			$data['refine_action'] = '/search/refineSearch';
		}
		
		// set sponsored
		$sponsored_array = array();
		if(count($results['sponsored_institute_ids']) >0) {
			$sponsored_array = $results['sponsored_institute_ids'];
		}

		/* Increase search snippet count, only those listing which comes in search
		 page rows like noraml institutes/sponsored institutes/content */

		$searchListingIds = $results['search_listing_ids'];
		$this->searchCommon->increaseSearchSnippetCount($searchListingIds);

		/*Increase sponsored impressions only banner, sponsored and featured*/
		$courseIds = array();
		$courseIds['banner']   	= $results['banner_course_ids'];
		$courseIds['featured'] 	= $results['featured_course_ids'];
		$courseIds['sponsored'] = $results['sponsored_course_ids'];
		$this->searchSponsored->increaseSponsoredListingImpressions($courseIds);

		/* Show debug information */
		$this->showDebugData($results);

		/* set display data*/
		$displayData = $results;
		$displayData['result_limit'] = msearchLib::$number_of_result;	
		if($urlParams['start'] == 0 && $displayData['result_limit'] < $results['solr_institute_data']['total_institute_groups']) {
			$total_results = $results['solr_institute_data']['total_institute_groups'] - count($sponsored_array);
		} else {
			$total_results = $results['solr_institute_data']['total_institute_groups'];
		}
		
		$displayData['total_results'] = $total_results;
		$displayData['max_offset'] = $this->msearchlib->findMaxOffset($urlParams['start'],msearchLib::$number_of_result,$total_results);
		$displayData['next_result'] = $this->msearchlib->getNextRemainingResults($urlParams['start'],$total_results);
		$displayData['listings_with_localities'] = json_encode($listings_with_localities);
		$displayData['validateuser']  = $this->checkUserValidation();
		$displayData['searchurlparams'] = $urlParams;
		$displayData['categorylist'] = $this->searchCommon->getMainCategoryList();
		$displayData['requestObject'] = $searchRequestObject;
		$displayData['trackForPages'] = true; //For JSB9 Tracking
		$displayData['search_lib_object'] = $this->msearchlib;
		$displayData['current_url'] = $this->shiksha_site_current_url;
		$displayData['referral_url'] = $this->shiksha_site_current_refferal;
		$displayData['boomr_pageid'] = "SEARCH_RESULT_PAGE";
        
		// this is used for request e-brochure
		$applied_courses = array();
		if($_COOKIE['applied_courses']) {
			$applied_courses = json_decode(base64_decode($_COOKIE['applied_courses']),true);
		}
		$displayData['applied_courses'] = $applied_courses;

		/* set tracking variables */
		$track_variables = array();
		$track_variables = $urlParams;
		$track_variables['source'] = 'mobile';
		$track_variables['institute_count'] = $total_results;
		$track_variables['course_count'] = $results['solr_institute_data']['numfound_course_documents'];
		$track_variables['page_id'] = $this->msearchlib->getPageId($urlParams['start']);
		$institute_type_result_ids = array();
		$institute_type_result_ids['institute'] = $results['search_listing_ids']['institute_ids'];
		$institute_type_result_ids['course'] = $results['search_listing_ids']['course_ids'];
		$track_variables['institute_type_result_ids'] = serialize($institute_type_result_ids);
		$track_variables['tsr'] = 'mobilesearch';
		$track_variables['result_step'] = $results['solr_institute_data']['result_step'];
		$track_variables['initial_qer'] = $results['solr_institute_data']['initial_qer_query'];
		$track_variables['final_qer'] = $results['solr_institute_data']['final_qer_query'];
		$track_variables['from_page'] = $urlParams['from_page'] ? $urlParams['from_page'] : 'searchpage_mobile';
		$track_variables['search_type'] = $urlParams['search_type'] ? $urlParams['search_type'] : '';
		// user agent check, if it is a boat, don't track
		if($this->msearchlib->is_robot()) {
			$displayData['result_search_id']  = 0;
		} elseif($this->input->post('type') != 'fetchAjax') {
			$displayData['result_search_id']  = modules::run('searchmatrix/SearchMatrix/trackSearchQuery',$track_variables);
		}

		$displayData['page_id'] = $track_variables['page_id'];
		$l = $displayData['search_lib_object'];
		// parse facets
		$this->msearchlib->parseFacets($results["facets"]);
		
		$refine_action = "/search/showSearchHome";
		if($total_results >0 && (count($this->msearchlib->getLocationFacets()) >0 || count($this->msearchlib->getCourselevelFacets())>0 || count($this->msearchlib->getCoursetypeFacets())>0)) {
			$refine_action = "/search/refineSearch";
		}
		$displayData['refine_action'] = $refine_action;

		//below line is used for conversion tracking purpose and change this value
		$displayData['trackingPageKeyId']=321;
		$displayData['recommendationTrackingPageKeyId']=322;
		$displayData['similardTrackingPageKeyId']=446;

		// The beacon tracking data
		$displayData['beaconTrackData'] = array(
			'pageIdentifier' => 'searchResultPage',
			'pageEntityId' => 0,
			'extraData' => array('url'=>get_full_url())
		);

		
		// strike filters
		$displayData['filters_picked_by_qer_data'] = $this->msearchlib->getFiltersPickedInfo($results['solr_institute_data']['qer_params_value']);
		if(isset($_POST['type']) && $_POST['type']=='fetchAjax'){
			$displayData['ajaxRequest'] = "true";
			if($results['solr_institute_data']['numfound_institute_groups']<=0){
				echo "noresults";
			}
			else{
				$this->load->view("normalsearchsnippet",$displayData);
			}
		}
		else{
			$this->load->view("searchlistings",$displayData);
		}
	}

	private function showDebugData($results = array()){

		if($this->input->get_post('debug') === "general"){
			_p($results['solr_institute_data']);
			_p($results['solr_content_data']);
			var_dump(htmlspecialchars($results['solr_institute_data']['raw_keyword']));
		} else if($this->input->get_post('debug') === "all"){
			_p($results);
		} else if($this->input->get_post('debug') === "facets"){
			_p($results['facets']);
		}
	}

	/*
	 * Function to render the Search Homepage. This will contain a plain Search input box and Search button.
	 */
	public function showSearchHome($error_msg = '') {

		$data = array();
		$course_type = $this->msearchlib->getModeList();
		$course_level = $this->msearchlib->getCourseLevelList();
		$locations = $this->msearchlib->getLocationList();

		$more_city_id = $this->input->get_post('more_city_id',true);
		$more_city_name = base64_decode($this->input->get_post('more_city_name',true));

		if((!empty($more_city_id) && !empty($more_city_name))) {
			$locations[$more_city_id] = $more_city_name;
			$locations_json = base64_encode(json_encode($locations));
			setcookie('msearch_locations',$locations_json,0,'/',COOKIEDOMAIN);
			$_COOKIE['msearch_locations'] = $locations_json;
		}

		$data['posted_course_type'] = $this->input->get_post('course_type',true);
		$data['posted_course_level'] = $this->input->get_post('course_level',true);
		if(!empty($more_city_id) && !empty($more_city_name)) {
			$data['posted_location'] = $more_city_id;
		} else {
			$data['posted_location'] = $this->input->get_post('city_id',true);
		}
		$data['posted_keyword'] =  url_base64_decode($this->input->get_post('keyword',true));


		$data['course_type'] = $course_type;
		$data['course_level'] = $course_level;

		$location_in_cookie = json_decode(base64_decode($_COOKIE['msearch_locations']),true);
		if(count($location_in_cookie) > 0 ) {
			$data['locations'] = $location_in_cookie;
		} else {
			$data['locations'] = $locations;
		}

		$data['current_url'] = $this->shiksha_site_current_url;
		$data['referral_url'] = $this->shiksha_site_current_refferal;
		$data['error_msg'] = $error_msg;
		$data['boomr_pageid'] = "SEARCH_HOME_PAGE";
		$data['autoSuggestorPageName'] = "search_page_mobile";

		$data['beaconTrackData'] = array(
			'pageIdentifier' => 'searchPage',
			'pageEntityId'   => 0,
			'extraData'      => array(
				'url'           => get_full_url(),
			)
		);

		$this->load->view("homepage",$data);

	}

	public function showMoreCourses() {
		$data_str= $this->input->post('to_send_data',true);		
		if(empty($data_str)) {
			redirect($this->shiksha_site_current_refferal);
			// redirect
		}
		$data['results'] = json_decode(gzuncompress(base64_decode($data_str)),true);
		$applied_courses = array();
		if($_COOKIE['applied_courses']) {
			$applied_courses = json_decode(base64_decode($_COOKIE['applied_courses']),true);
		}
		$data['applied_courses'] = $applied_courses;
		$data['referral_url'] = $this->shiksha_site_current_refferal;
                $data['current_url'] = $this->shiksha_site_current_url;
		$data['boomr_pageid'] = "SEARCH_MORE_COURSES_PAGE";
		$data['categorySelected'] = $this->input->post('categorySelected');
		$this->load->view("morecourses",$data);
	}

	public function showSimilarCourses() {
		$data_str= $this->input->post('to_send_data',true);
		//echo "data".$data_str;
		if(empty($data_str)) {
			redirect($this->shiksha_site_current_refferal);
			// redirect
		}
		$data['results'] = json_decode(gzuncompress(base64_decode($data_str)),true);
		$applied_courses = array();
		if($_COOKIE['applied_courses']) {
			$applied_courses = json_decode(base64_decode($_COOKIE['applied_courses']),true);
		}
		$data['applied_courses'] = $applied_courses;
		$data['referral_url'] = $this->shiksha_site_current_refferal;
		$data['boomr_pageid'] = "SEARCH_SIMILAR_COURSES_PAGE";
		//below line is used for conversion tracking purpose
		if(!empty($_POST['tracking_keyid']))
		{
			$data['trackingPageKeyId'] = $this->input->post('tracking_keyid');
		}
		$this->load->view("similarcourses",$data);
	}

	public function displaySearch($searchString){

		/*
		global $listings_with_localities;
		$GLOBALS['localityArray'] = array();
		$GLOBALS['studyAbroadIds'] = array();
		$GLOBALS['instituteWithMultipleCourseLocations'] = array();

		$requestObject = new CategoryPageRequest();
		$requestObject->setData(array('cityId' => 1));
		$requestObject->setData(array('countryId' => 1));
		$requestObject->setData(array('stateId' => 0));

		$urlParams = $this->searchCommon->readSearchParams('SEARCH');
		// Default search behaviour for static search 
		$keyword = $this->getSearchKeywordFromStaticSearchURL($searchString);
		$urlParams['keyword'] = $keyword;
		$urlParams['search_type'] = 'institute'; //Search type is institute, no QUERace required
		$urlParams['search_source'] = 'STATIC_SEARCH'; //Search source is SEARCH PAGE
		$urlParams = $this->msearchlib->setDefaultSearchParams($urlParams);
		$urlParams = $this->msearchlib->validateUrlParams($urlParams);
		$this->msearchlib->url_params = $urlParams;
		$results = $this->searchRepository->search($urlParams);

		// set sponsored
		$sponsored_array = array();
		if(count($results['sponsored_institute_ids']) >0) {
			$sponsored_array = $results['sponsored_institute_ids'];
		}

		// Increase search snippet count, only those listing which comes in search
		// page rows like noraml institutes/sponsored institutes/content

		$searchListingIds = $results['search_listing_ids'];
		$this->searchCommon->increaseSearchSnippetCount($searchListingIds);

		//Increase sponsored impressions only banner, sponsored and featured
		$courseIds = array();
		$courseIds['banner']   	= $results['banner_course_ids'];
		$courseIds['featured'] 	= $results['featured_course_ids'];
		$courseIds['sponsored'] = $results['sponsored_course_ids'];
		$this->searchSponsored->increaseSponsoredListingImpressions($courseIds);

		$this->showDebugData($results);

		$displayData = $results;
		$displayData['result_limit'] = msearchLib::$number_of_result;
		if($urlParams['start'] == 0 && $displayData['result_limit'] < $results['solr_institute_data']['total_institute_groups']) {
			$total_results = $results['solr_institute_data']['total_institute_groups'] - count($sponsored_array);
		} else {
			$total_results = $results['solr_institute_data']['total_institute_groups'];
		}
		$displayData['total_results'] = $total_results;
		$displayData['max_offset'] = $this->msearchlib->findMaxOffset($urlParams['start'],msearchLib::$number_of_result,$total_results);
		$displayData['next_result'] = $this->msearchlib->getNextRemainingResults($urlParams['start'],$total_results);
		$displayData['listings_with_localities'] = json_encode($listings_with_localities);
		$displayData['validateuser'] = $this->checkUserValidation();
		$displayData['searchurlparams'] = $urlParams;
		$displayData['categorylist'] = $this->searchCommon->getMainCategoryList();
		$displayData['requestObject'] = $requestObject;
		$displayData['trackForPages'] = true; //For JSB9 Tracking
		$displayData['search_lib_object'] = $this->msearchlib;
		$displayData['current_url'] = $this->shiksha_site_current_url;
		$displayData['referral_url'] = $this->shiksha_site_current_refferal;
		$displayData['boomr_pageid'] = "SEARCH_RESULT_PAGE";
		$applied_courses = array();
		if($_COOKIE['applied_courses']) {
			$applied_courses = json_decode(base64_decode($_COOKIE['applied_courses']),true);
		}
		$displayData['applied_courses'] = $applied_courses;

		//set tracking variables 
		$track_variables = array();
		$track_variables = $urlParams;
		$track_variables['source'] = 'mobile';
		$track_variables['institute_count'] = $total_results;
		$track_variables['course_count'] = $results['solr_institute_data']['numfound_course_documents'];
		$track_variables['page_id'] = $this->msearchlib->getPageId($urlParams['start']);
		$institute_type_result_ids = array();
		$institute_type_result_ids['institute'] = $results['search_listing_ids']['institute_ids'];
		$institute_type_result_ids['course'] = $results['search_listing_ids']['course_ids'];
		$track_variables['institute_type_result_ids'] = serialize($institute_type_result_ids);
		$track_variables['tsr'] = 'mobilesearch';
		$track_variables['result_step'] = $results['solr_institute_data']['result_step'];
		$track_variables['initial_qer'] = $results['solr_institute_data']['initial_qer_query'];
		$track_variables['final_qer'] = $results['solr_institute_data']['final_qer_query'];
		$track_variables['from_page'] = $urlParams['from_page'] ? $urlParams['from_page'] : 'mobiletopsearchpage';

		if($this->msearchlib->is_robot()) {
			$displayData['result_search_id']  = 0;
		} else {
			$displayData['result_search_id']  = modules::run('searchmatrix/SearchMatrix/trackSearchQuery',$track_variables);
		}
		$displayData['page_id'] = $track_variables['page_id'];

		// parse facets
		$this->msearchlib->parseFacets($results["facets"]);
		$refine_action = "/search/showSearchHome";
		if($total_results >0 && (count($this->msearchlib->getLocationFacets()) >0 || count($this->msearchlib->getCourselevelFacets())>0 || count($this->msearchlib->getCoursetypeFacets()))>0) {
			$refine_action = "/search/refineSearch";
		}
		$displayData['refine_action'] = $refine_action;
		// strike filters
		$displayData['filters_picked_by_qer_data'] = $this->msearchlib->getFiltersPickedInfo($results['solr_institute_data']['qer_params_value']);

		$view_path  = $this->msearchlib->renderView("searchlistings");
		$this->load->view($view_path,$displayData);
		*/
		global $listings_with_localities;
		$GLOBALS['localityArray'] = array();
		$GLOBALS['studyAbroadIds'] = array();
		$GLOBALS['instituteWithMultipleCourseLocations'] = array();

		$requestObject = new CategoryPageRequest();
		$requestObject->setData(array('cityId' => 1));
		$requestObject->setData(array('countryId' => 1));
		$requestObject->setData(array('stateId' => 0));

		$urlParams = $this->msearchlib->setDefaultSearchParams($urlParams);
		$urlParams = $this->searchCommon->readSearchParams('SEARCH');
		// Default search behaviour for static search 
		$keyword = $this->getSearchKeywordFromStaticSearchURL($searchString);
		$urlParams['keyword'] = $keyword;
		$urlParams['search_type'] = 'institute'; //Search type is institute, no QUERace required
		$urlParams['search_source'] = 'SEARCH'; //Search source is SEARCH PAGE
		$urlParams['institute_rows'] = msearchLib::$number_of_result;

		$urlParams = $this->msearchlib->validateUrlParams($urlParams);
		$this->msearchlib->url_params = $urlParams;
		$results = $this->searchRepository->search($urlParams);
		if(empty($results['facets']['location'])) {
			$urlParams['search_type'] = 'institute';
			$results = $this->searchRepository->search($urlParams);
			$data['refine_action'] = '/search/refineSearch';
		}
		
		// set sponsored
		$sponsored_array = array();
		if(count($results['sponsored_institute_ids']) >0) {
			$sponsored_array = $results['sponsored_institute_ids'];
		}

		/* Increase search snippet count, only those listing which comes in search
		 page rows like noraml institutes/sponsored institutes/content */

		$searchListingIds = $results['search_listing_ids'];
		$this->searchCommon->increaseSearchSnippetCount($searchListingIds);

		/*Increase sponsored impressions only banner, sponsored and featured*/
		$courseIds = array();
		$courseIds['banner']   	= $results['banner_course_ids'];
		$courseIds['featured'] 	= $results['featured_course_ids'];
		$courseIds['sponsored'] = $results['sponsored_course_ids'];
		$this->searchSponsored->increaseSponsoredListingImpressions($courseIds);

		/* Show debug information */
		$this->showDebugData($results);

		/* set display data*/
		$displayData = $results;
		$displayData['result_limit'] = msearchLib::$number_of_result;	
		if($urlParams['start'] == 0 && $displayData['result_limit'] < $results['solr_institute_data']['total_institute_groups']) {
			$total_results = $results['solr_institute_data']['total_institute_groups'] - count($sponsored_array);
		} else {
			$total_results = $results['solr_institute_data']['total_institute_groups'];
		}
		
		$displayData['total_results'] = $total_results;
		$displayData['max_offset'] = $this->msearchlib->findMaxOffset($urlParams['start'],msearchLib::$number_of_result,$total_results);
		$displayData['next_result'] = $this->msearchlib->getNextRemainingResults($urlParams['start'],$total_results);
		$displayData['listings_with_localities'] = json_encode($listings_with_localities);
		$displayData['validateuser']  = $this->checkUserValidation();
		$displayData['searchurlparams'] = $urlParams;
		$displayData['categorylist'] = $this->searchCommon->getMainCategoryList();
		$displayData['requestObject'] = $searchRequestObject;
		$displayData['trackForPages'] = true; //For JSB9 Tracking
		$displayData['search_lib_object'] = $this->msearchlib;
		$displayData['current_url'] = $this->shiksha_site_current_url;
		$displayData['referral_url'] = $this->shiksha_site_current_refferal;
		$displayData['boomr_pageid'] = "SEARCH_RESULT_PAGE";
        
		// this is used for request e-brochure
		$applied_courses = array();
		if($_COOKIE['applied_courses']) {
			$applied_courses = json_decode(base64_decode($_COOKIE['applied_courses']),true);
		}
		$displayData['applied_courses'] = $applied_courses;

		/* set tracking variables */
		$track_variables = array();
		$track_variables = $urlParams;
		$track_variables['source'] = 'mobile';
		$track_variables['institute_count'] = $total_results;
		$track_variables['course_count'] = $results['solr_institute_data']['numfound_course_documents'];
		$track_variables['page_id'] = $this->msearchlib->getPageId($urlParams['start']);
		$institute_type_result_ids = array();
		$institute_type_result_ids['institute'] = $results['search_listing_ids']['institute_ids'];
		$institute_type_result_ids['course'] = $results['search_listing_ids']['course_ids'];
		$track_variables['institute_type_result_ids'] = serialize($institute_type_result_ids);
		$track_variables['tsr'] = 'mobilesearch';
		$track_variables['result_step'] = $results['solr_institute_data']['result_step'];
		$track_variables['initial_qer'] = $results['solr_institute_data']['initial_qer_query'];
		$track_variables['final_qer'] = $results['solr_institute_data']['final_qer_query'];
		$track_variables['from_page'] = $urlParams['from_page'] ? $urlParams['from_page'] : 'static';
         
		// user agent check, if it is a boat, don't track
		if($this->msearchlib->is_robot()) {
			$displayData['result_search_id']  = 0;
		} else {
			$displayData['result_search_id']  = modules::run('searchmatrix/SearchMatrix/trackSearchQuery',$track_variables);
		}

		$displayData['page_id'] = $track_variables['page_id'];
		$l = $displayData['search_lib_object'];
		// parse facets
		$this->msearchlib->parseFacets($results["facets"]);
		
		$refine_action = "/search/showSearchHome";
		if($total_results >0 && (count($this->msearchlib->getLocationFacets()) >0 || count($this->msearchlib->getCourselevelFacets())>0 || count($this->msearchlib->getCoursetypeFacets())>0)) {
			$refine_action = "/search/refineSearch";
		}
		$displayData['refine_action'] = $refine_action;
		// strike filters
		$displayData['filters_picked_by_qer_data'] = $this->msearchlib->getFiltersPickedInfo($results['solr_institute_data']['qer_params_value']);
		if(isset($_POST['type']) && $_POST['type']=='fetchAjax'){
			$displayData['ajaxRequest'] = "true";
			if($results['solr_institute_data']['numfound_institute_groups']<=0){
				echo "noresults";
			}
			else{
				$this->load->view("normalsearchsnippet",$displayData);
			}
		}
		else{
			$this->load->view("searchlistings",$displayData);
		}
		
	}

	private function getSearchKeywordFromStaticSearchURL($stringParams){
		if(preg_match("/colleges-institutes-in/i", $stringParams)) {
			$collagePresentFlag = 0;
		} else {
			$collagePresentFlag = 1;
		}
		$keyword = "";
		$location = "";
		$stringParams = str_replace("list-","",$stringParams);
		$searchParamArray = explode("-in-",$stringParams);
		if(is_array($searchParamArray) && count($searchParamArray) > 1) {
			$keyword = $searchParamArray[0];
			$location = $searchParamArray[1];
			if(count($searchParamArray) > 2){
				$keyword = "";
				for($i=0; $i < (count($searchParamArray)-1); $i++) {
					$keyword .= $searchParamArray[$i]." in ";
				}
				$keyword = substr($keyword,0,(strlen($keyword) - 4));
				$location = $searchParamArray[count($searchParamArray)-1];
			}
			$keyword = str_replace(array("-colleges-institutes","-","&"),array(""," "," "), $keyword);
			$location = preg_replace(array("/-/","/\&/","/[0-9]\.html/"),array(" "," ",""), $location);
		}
		return $keyword ." ". $location;
	}


	public function showTopSearches() {
		$data = array();
		$data['top_searches'] = msearchLib::$shiksha_top_searches;
		$data['current_url'] = $this->shiksha_site_current_url;
		$data['referral_url'] = $this->shiksha_site_current_refferal;
		$data['boomr_pageid'] = "SEARCH_TOP_SEARCHES_PAGE";
		$this->load->view("topsearchesmainpage",$data);
	}

	public function showMoreCities() {

		$this->load->builder('LocationBuilder','location');
		$locationBuilder = new LocationBuilder;
		$locationRepository = $locationBuilder->getLocationRepository();
		$cityList = $locationRepository->getCitiesByMultipleTiers(array(2,3),2);

		$locations = array();
		foreach ($cityList as $key=>$value) {
			foreach($value as $city_object) {
				$locations[$city_object->getId()] = $city_object->getName();
			}
		}

		asort($locations);

		$data['locations'] = $locations;
		$data['boomr_pageid'] = "SEARCH_MORE_CITIES_PAGE";

		$view_path  = $this->msearchlib->renderView("morecities");
		$this->load->view($view_path,$data);
	}

	public function refineSearch() {

		$data = array();
		$object = $this->input->post('serialize_object',true);
		if(empty($object)) {
			redirect($this->shiksha_site_current_refferal);
			// redirect
		}
		$data['dataobject'] = unserialize(gzuncompress(base64_decode($object)));
		$data['boomr_pageid'] = "REFINE_SEARCH_PAGE";
		$data['referral_url'] = $this->shiksha_site_current_refferal;
		$view_path  = $this->msearchlib->renderView("refine");
		$this->load->view($view_path,$data);
	}

	public function refineSearchLocation() {

		$data = array();
		$object = $this->input->post('serialize_object',true);
		if(empty($object)) {
			redirect($this->shiksha_site_current_refferal);
			// redirect
		}
		$data['dataobject'] = unserialize(gzuncompress(base64_decode($object)));
		$data['boomr_pageid'] = "REFINE_LOCATION_SEARCH_PAGE";
		$data['referral_url'] = $this->shiksha_site_current_refferal;
		$view_path  = $this->msearchlib->renderView("refine_location");
                if(strpos($view_path,'smartphones/') === false) {
			$data['device_type'] = "low";
		}
		//_P($data['dataobject']->getLocationFacets());
		$this->load->view($view_path,$data);
	}
	
	/**
	 * Get the html for refine filter in case of search
	 */	
	public function getSearchFilterHtml() {
		
		$data = $this->getSearhDataForFilterandLocation();
		echo $this->load->view('refine',$data);
	}

	/**
	 * Get the html for location filters in case of search
	 */	
	public function getSearchFilterHtmlForLocation() {
	
		$data = $this->getSearhDataForFilterandLocation();
		echo $this->load->view('refine_location',$data);
	}
	
	/**
	 * Common function to get the data for search to be returned
	 */
	private function getSearhDataForFilterandLocation() {

		$urlParamString = $_POST['urlParams'];
		$urlParams = $this->searchCommon->getSearchParamsFromAjaxUrl($urlParamString);
		$keyword  = trim($urlParams['keyword']);
		// set additional parameters
		$urlParams['search_source']   = 'SEARCH';
		$urlParams['institute_rows'] = msearchLib::$number_of_result;
		$urlParams = $this->msearchlib->validateUrlParams($urlParams);
		$this->msearchlib->url_params = $urlParams;
		$results = $this->searchRepository->search($urlParams);

		if(empty($results['facets']['location'])) {
			$urlParams['search_type'] = 'institute';
			$results = $this->searchRepository->search($urlParams);
		}
		
		$this->msearchlib->parseFacets($results["facets"]);
		$data['searchurlparams'] = $urlParams;
		$data['current_url'] = $this->shiksha_site_current_url;
		$data['search_lib_object'] = $this->msearchlib;
		
		return $data;
	}
}
