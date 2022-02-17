<?php

class Search extends MX_Controller {
	
	private $searchWrapper;
	private $searchCommon;
	private $searchSponsored;
	private $searchRepository;
	
	public function __construct(){
		ini_set('memory_limit', '512M');
		$this->load->helper('search/SearchUtility');
		$this->config->load('search_config');
		$this->load->builder('SearchBuilder', 'search');
		$this->load->builder('CategoryPageBuilder','categoryList');
		$this->load->library('categoryList/categoryPageRequest');
		$this->load->library(array('category_list_client', 'searchmatrix/SearchMatrixLib'));
		$this->load->builder("ListingBuilder", "listing");
		$this->load->helper('coursepages/course_page');
		$this->searchCommon 	= SearchBuilder::getSearchCommon();
		$this->searchRepository = SearchBuilder::getSearchRepository();
		$this->searchSponsored  = SearchBuilder::getSearchSponsored();
		
		$this->load->helper(array('form', 'url','image','shikshautility'));
		$this->load->library(array('miscelleneous','message_board_client','blog_client','ajax','category_list_client','listing_client','register_client','alerts_client'));
        $this->userStatus = $this->checkUserValidation();
        if(isset($this->userStatus[0]) && is_array($this->userStatus[0])) {
            $this->userid=$this->userStatus[0]['userid'];
        } else {
            $this->userid=-1;
        }
		
	}
		
	public function index() {
		$time_start_1 = microtime_float(); $start_memory_1 = memory_get_usage();
		global $listings_with_localities;
		$GLOBALS['localityArray'] = array();
		$GLOBALS['studyAbroadIds'] = array();
		$GLOBALS['instituteWithMultipleCourseLocations'] = array();
		// added for coursepage related changes
		$course_page_params_array = $this->_getCoursePageParams();
		$urlParams = $this->searchCommon->readSearchParams('SEARCH');
		
		/**
		 * Redirect all the old URLs to new URL
		 */
		$requestData = array();
		$requestData['keyword'] 		= $urlParams['keyword'];
		$requestData['requestFrom'] 	= "oldsearch";
		$url = Modules::run ('search/SearchV2/createOpenSearchUrl', $requestData, TRUE);
		redirect($url,'location', 301);
		die();
		
		$urlParams['show_featured_panel'] 		= true;
		$urlParams['search_source']   			= 'SEARCH';
		$results = $this->searchRepository->search($urlParams);
		$results['solr_institute_data']['raw_keyword'] = $results['solr_institute_data']['tempKeywordFix'];
		$results['solr_institute_data']['keyword'] = $results['solr_institute_data']['tempKeywordFix'];
		$urlParams['keyword'] = $results['solr_institute_data']['tempKeywordFix'];
		/* Increase search snippet count, only those listing which comes in search page rows like noraml institutes/sponsored institutes/content */
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
		$displayData = $results;
		$displayData['listings_with_localities']	= json_encode($listings_with_localities);
		$displayData['validateuser'] 				= $this->checkUserValidation();
		$displayData['urlparams'] 					= $urlParams;
		$displayData['categorylist'] 				= $this->searchCommon->getMainCategoryList();
		$displayData['requestObject'] 				= $searchRequestObject;
		$displayData['trackForPages'] = true; //For JSB9 Tracking
		$displayData['noIndexFollow'] = true;

                // added if qna search is triggered from course page
		if(!empty($course_page_params_array) >0) {
			$displayData['tab_required_course_page'] = $course_page_params_array['tab_required_course_page'];
			$displayData['subcat_id_course_page'] = $course_page_params_array['subcat_id_course_page'];
			$displayData['course_pages_tabselected'] = $course_page_params_array['course_pages_tabselected'];
		}
		//load library for brochure url
		$national_course_lib = $this->load->library('listing/NationalCourseLib');
		$courseIDs = array();
		$instituteResults= array($results['sponsored_institutes'],$results['normal_institutes']);
		foreach($instituteResults as $instituteArray)
		{
			foreach($instituteArray as $instituteElement){
				foreach($instituteElement as $k=>$courseArray){
					$courseIDs[] = $courseArray->getId();
				}
			}
		}
		$brochureURL = $national_course_lib->getMultipleCoursesBrochure($courseIDs);
		$displayData['brochureURL'] = $brochureURL;//_p($brochureURL);
		
		//harshit 
		$this->load->library('listing/ListingEbrochureGenerator');
		$listingebrochuregenerator = new ListingEbrochureGenerator;
		$multiLocations = $listingebrochuregenerator->getMultilocationsForInstitute($courseIDs);  
		$displayData['multiLocationCourses'] = json_encode($multiLocations);

		if(empty($displayData['search_type'])){
			$displayData['search_type'] = 'institute';
		}
		$displayData['searchKeyword'] = $displayData['urlparams']['keyword'];
		//generate url identifier value for page onload
		$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$displayData['uid'] = substr(str_shuffle(str_repeat($pool, 8)), 0, 8).time();
	
		//Tracking Code
		$displayData['beaconTrackData'] = array(
		    'pageIdentifier' => 'searchPage',
		    'pageEntityId' => 'searchPage-searchString-'.$displayData['searchKeyword'],
		    'extraData' => array('url'=>get_full_url())
		);
	
		$this->load->view('search/search', $displayData);
		if(LOG_SEARCH_PERFORMANCE_DATA)
			error_log("Section: Total Page load with view | ".getLogTimeMemStr($time_start_1, $start_memory_1)."\n\n\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME_OLD);
	}
        
	public function getInstituteSearchResults(){
		$GLOBALS['partial_localityArray'] = array();
		$GLOBALS['partial_studyAbroadIds'] = array();
		$GLOBALS['partial_instituteWithMultipleCourseLocations'] = array();
		$urlParams = $this->searchCommon->readSearchParams('SEARCH');
		//Overwriting already set existing params, if any for institute search only
		$urlParams['search_data_type'] 			= 'institute';
		$urlParams['search_type'] 	   			= 'institute';
		$urlParams['content_rows'] 	   			= 0;
		$urlParams['search_source']   			= 'SEARCH';
		$urlParams['show_featured_results'] 	= false;
		$urlParams['show_sponsored_results'] 	= false;
		$urlParams['show_banner_results'] 		= false;
		if(!empty($urlParams['ignore_institute_ids'])){
			$ignoreIdsStr = trim($urlParams['ignore_institute_ids'], ',');
			$ignoreIds = explode(',', $ignoreIdsStr);
			$urlParams['ignore_institute_ids'] = $ignoreIds;
		}
		$results = $this->searchRepository->search($urlParams);
		/*Increase search snippet count*/
		$searchListingIds = $results['search_listing_ids'];
		$this->searchCommon->increaseSearchSnippetCount($searchListingIds);
		
		$newPageId 	= calculatePageId($results['general']['rows_count']['institute_rows'], $results['solr_institute_data']['start']);
		$this->updateSearchPageId($urlParams['search_unique_insert_id'], $newPageId);
		
		$returnResults['p'] = $urlParams;
		$returnResults['keyword'] 					= urlencode($urlParams['tempKeywordFix']);
		$returnResults['last_start'] 				= $results['solr_institute_data']['start'];
		$returnResults['results_per_page'] 			= $results['general']['rows_count']['institute_rows'];
		$returnResults['total_results'] 			= $results['solr_institute_data']['total_institute_groups'];
		$returnResults['total_results_on_page'] 	= count($results['normal_institutes']);
		$returnResults['search_unique_insert_id'] 	= $urlParams['search_unique_insert_id'];
		
		$results['pagination'] = $returnResults;

		$results['previousRowCount'] = $urlParams['previousRowCount'];
		$results['searchKeyword']    = $urlParams['tempKeywordFix'];
		$results['uid']              = $urlParams['uid'];
		
		$viewHTML = $this->load->view('search/search_institute_listings_pagination', $results, true);
		$returnResults['partial_localityArray'] = $GLOBALS['partial_localityArray'];
		$returnResults['partial_studyAbroadIds'] = $GLOBALS['partial_studyAbroadIds'];
		$returnResults['partial_instituteWithMultipleCourseLocations'] = $GLOBALS['partial_instituteWithMultipleCourseLocations'];
		
		$returnResults['html'] = $viewHTML;
		$returnResults['nextRowCount'] = $results['previousRowCount'] + $returnResults['total_results_on_page'];
		
		$returnResults = json_encode($returnResults);
		echo $returnResults;
	}
	
	public function getContentSearchResults(){
		$urlParams = $this->searchCommon->readSearchParams('SEARCH');
		//Overwriting already set existing params, if any for institute search only
		$urlParams['search_data_type'] = 'content';
		$urlParams['search_type'] 	   = 'content';
		$urlParams['institute_rows']   = 0;
		$urlParams['search_source']    = 'SEARCH';
		
		$results = $this->searchRepository->search($urlParams);
		/*Increase search snippet count*/
		$searchListingIds = $results['search_listing_ids'];
		$this->searchCommon->increaseSearchSnippetCount($searchListingIds);
		
		$newPageId 	= calculatePageId($results['general']['rows_count']['content_rows'], $results['solr_content_data']['start']);
		$this->updateSearchPageId($urlParams['search_unique_insert_id'], $newPageId);
		
		$returnResults = array();
		$viewHTML = $this->load->view('search/search_content_listings_pagination', $results, true);
		$returnResults['html'] = $viewHTML;
		$returnResults['keyword'] 					= urlencode($urlParams['tempKeywordFix']);
		$returnResults['last_start'] 				= $results['solr_content_data']['start'];
		$returnResults['results_per_page'] 			= $results['general']['rows_count']['content_rows'];
		$returnResults['total_results'] 			= $results['solr_content_data']['numfound_content'];
		$returnResults['total_results_on_page'] 	= count($results['content']);
		$returnResults['search_unique_insert_id'] 	= $urlParams['search_unique_insert_id'];
		$returnResults = json_encode($returnResults);
		echo $returnResults;
	}
        
        public function getStudyAbroadSearchResults(){
                $urlParams = $this->searchCommon->readSearchParams('SEARCH');
                $urlParams['search_source'] = 'SEARCH';
                $urlParams['search_domain'] = 'ABROAD';
                $urlParams['search_type'] = 'content';            
                $results = $this->searchRepository->searchAbroad($urlParams);
	/*
                $this->dbLibObj = DbLibCommon::getInstance('User');
                $dbHandle = $this->_loadDatabaseHandle('write');
                $keyword = htmlspecialchars($_GET['keyword']);
                $user = $this->checkUserValidation();
//                $query = $dbHandle->query("insert into study_abroad_search_tracking (`keyword`, `sessionId`, `userId`, `results`) values ('$keyword', '$this->sessionId', '$this->userId'," . $results['total_count'] . ")" );
//                $results = $query->result_array();
*/
		return $results;
        }
	
	public function getCMSSearchResults(){
		$urlParams = $this->searchCommon->readSearchParams('CMS');
		$urlParams['show_sponsored_results'] 	= false;
		$urlParams['show_featured_results'] 	= false;
		$urlParams['show_featured_panel'] 		= false;
		$urlParams['show_banner_results'] 		= false;
		
		if((int)$urlParams['sponsored'] == 1){
			$urlParams['show_sponsored_results'] 	= true; // yup, need sponsored results
		}
		if((int)$urlParams['featured'] == 1){
			$urlParams['show_featured_results'] 	= true; // yup, need featured results
		}
		
		/* Default search behaviour for CMS search */
		$urlParams['search_data_type'] 			= 'institute'; //Show only institute data
		$urlParams['search_type'] 	   			= 'institute'; //Search type is institute, no QUERace required
		$urlParams['content_rows']   			= 0; // No content type result required in CMS
		$urlParams['search_source']   			= 'CMS'; //Search source is CMS
		
		$results = $this->searchRepository->search($urlParams);
		$displayData = $results;
		$displayData['validateuser'] = $this->checkUserValidation();
		$displayData['urlparams'] = $urlParams;
		
		return $displayData;
	}
	
	public function displaySearch($searchString){
		$this->load->config('Top100Url', true);
		$topUrls = $this->config->item('topUrls','Top100Url');
		$metaRobots = false;
		if(isset($topUrls[$searchString]) && $topUrls[$searchString] != '')
		{
			redirect($topUrls[$searchString], 'location', 301);
			exit;
		}
		if(!isset($topUrls[$searchString]))
		{
			$metaRobots = true;
		}
		// redirect to some specific pages for some top education search pages
		$this->_redirectToSpecificPages($searchString);

		global $listings_with_localities;
		$GLOBALS['localityArray'] = array();
		$GLOBALS['studyAbroadIds'] = array();
		$GLOBALS['instituteWithMultipleCourseLocations'] = array();
		
		$requestObject = new CategoryPageRequest();
		$requestObject->setData(array('cityId' => 1));
		$requestObject->setData(array('countryId' => 1));
		$requestObject->setData(array('stateId' => 0));
		
		$urlParams = $this->searchCommon->readSearchParams('SEARCH');
		/* Default search behaviour for static search */
		$keyword = $this->getSearchKeywordFromStaticSearchURL($searchString);
		
		$urlParams['tempKeywordFix']= $keyword;
		$parts = explode(" ", $keyword);
		$keyword = "";
		foreach($parts as $part){
			if(!is_numeric($part)){
				$keyword .= $part . " ";
			}
		}
		$keyword = trim($keyword);
		
		$urlParams['keyword']					= $keyword;
		$urlParams['search_type'] 	   			= 'institute'; //Search type is institute, no QUERace required
		//$urlParams['content_rows']   			= 0; // No content type result required in CMS
		$urlParams['search_source']   			= 'STATIC_SEARCH'; //Search source is SEARCH PAGE
		$urlParams['show_featured_panel'] 		= true;
		$results = $this->searchRepository->search($urlParams);
		
		$this->showDebugData($results);
		$displayData = $results;
		
		$displayData['solr_institute_data']['raw_keyword'] = $results['solr_institute_data']['tempKeywordFix'];
		$displayData['solr_institute_data']['keyword'] = $results['solr_institute_data']['tempKeywordFix'];
		$urlParams['keyword'] = $results['solr_institute_data']['tempKeywordFix'];
		
		$displayData['listings_with_localities']	= json_encode($listings_with_localities);
		$displayData['validateuser'] 				= $this->checkUserValidation();
		$displayData['urlparams'] 					= $urlParams;
		$displayData['categorylist'] 				= $this->searchCommon->getMainCategoryList();
		$displayData['requestObject'] 				= $requestObject;
		$displayData['trackForPages'] = true; //For JSB9 Tracking
		$displayData['noIndexFollow'] = $metaRobots;
		//Tracking Code
		$displayData['beaconTrackData'] = array(
		    'pageIdentifier' => 'topSearchPage',
		    'pageEntityId' => 'topsearch-searchString-'.$searchString,
		    'extraData' => array('url'=>get_full_url())
		);
	

		$this->load->view('search/search', $displayData);
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
        if($keyword == 'top 10 interior design schools') {
            $keyword = "top 10 interior design colleges";
        }
    
		return $keyword ." ". $location;
	}
	
	private function updateSearchPageId($searchResultId = null, $pageId = null){
		if(!empty($searchResultId) && !empty($pageId)){
			$paramArray = array('page_id' => $pageId, 'result_search_id' => $searchResultId);
			$searchMatrixLibObject = new SearchMatrixLib();
			$searchMatrixLibObject->updateSearchPageId($paramArray);
		}
	}
		
	private function showDebugData($results = array()){
		if($this->security->xss_clean($_REQUEST['debug']) === "general"){
			_p($results['solr_institute_data']);
			_p($results['solr_content_data']);
			var_dump(htmlspecialchars($results['solr_institute_data']['raw_keyword']));
		} else if($this->security->xss_clean($_REQUEST['debug']) === "all"){
			_p($results);
		} else if($this->security->xss_clean($_REQUEST['debug']) === "facets"){
			_p($results['facets']);
		}
	}
	
	/**
	 *
	 *
	 * OLD LEGACY FUNCTIONS
	 *
	 *
	 */
	
	/**
    * Search Function for API calls
    * Get all the variable from GET
    *       keyword : the keyword that needs to be searched, required
    *       startCount , rowCount : the start and number of values in the row required. For pagination
    *       listingType : institute or course or event or question or scholarship
    *       outputType : json/xml/php
    *       relaxFlag : for AND or OR query, 1 means its an OR query
    * Webservice Calls:
    * For all Calls:
    * library : Listing Client : shikshaApiSearch-> XMLRPC call: shikshaApiSearch
    *
    * @Output : the output in XML/ API or json Object
    */

    function getSearchResultApi() {
        $appId = 1;
        $this->init();

        $displayData = array();
        $keyword = $_REQUEST['keyword'];
        $keyword=str_replace("&"," ",$keyword);
        $start = (isset($_REQUEST['startCount']) && $_REQUEST['startCount'] != '' && is_numeric($_REQUEST['startCount'])) ? $_REQUEST['startCount'] : 0;
        $rows = (isset($_REQUEST['rowCount']) && $_REQUEST['rowCount'] != '' && is_numeric($_REQUEST['rowCount'])) ? $_REQUEST['rowCount'] : 10;
        $listingType = isset($_REQUEST['listingType']) ? $_REQUEST['listingType'] : 'all';
        $outputType = isset($_REQUEST['outputType']) ? $_REQUEST['outputType'] : 'json';
        $relaxFlag = isset($_REQUEST['relaxFlag']) ? $_REQUEST['relaxFlag'] : 0;
        $categoryId = isset($_REQUEST['categoryId']) ? $_REQUEST['categoryId'] : "";
        $filterList= isset($_REQUEST['filterList']) ? $_REQUEST['filterList'] : "";

        if($filterList!="")
        {
            error_log("Shirish121211 : ".$filterList);
        }
        $searchType = $_REQUEST['searchType'];
        $ListingClientObj = new Listing_client();
        $searchResult = $ListingClientObj->shikshaApiSearch($appId,$keyword,$start,$rows,$listingType,$relaxFlag,$categoryId,$filterList);
        $outputData['numOfResults'] = $searchResult['numOfRecords'];
        $outputData['keyword']= $_REQUEST['keyword'];
        $outputData['start']= $start;
        $outputData['rows']= $rows;
        $outputData['listingType']= $listingType;
        $outputData['outputType']= "json";
        $outputData['resultList'] = $searchResult['results'];
        if(trim($outputType)=="php")
        {
            echo "<pre>";
            print_r($outputData);
            echo "</pre>";
        }
        else if(trim($outputType)=="xml")
        {
            echo ArrayToXML::toXml($outputData);
        }
        else
        {
            echo json_encode($outputData);
        }
    }

    /**
    * Search Widget for partner sites
    * @Input: two Parameters:
    * partnerId : the partner whose logo will be showed
    * searchType: the type of result to be user
    */
    function searchWidget($partnerId="", $searchType="") {
        $this->load->view("search/searchWidget",array('partner'=>$partnerId , 'searchType'=>$searchType));
    }

    /**
    * Save search, as an alert for new documents on Shiksha
    * @Input: three Parameters:
    * Keyword : the keyword on which the alert needs to be created
    * searchType : the type of listing needed in alert
    * Location: the location on which the alert needs to be created
    *
    * Server Call:
    * library : saveSearch -> XMLRPC : saveSearch
    *
    * @Output:
    *       String signifying the alert has been created
    */
    function saveSearch($keyword,$type,$location) {
    	/*
        $appId = '1';
        $this->init();
        $ListingClientObj = new Listing_client();
        $userid=$this->userStatus[0]['userid'];
        error_log_shiksha("$appId,$userid,$keyword,$location,$type");
        $result= $ListingClientObj->saveSearch($appId,$userid,$keyword,$type,$location);
        echo json_encode($result);
        */
    }

    /**
    * updateSaveSearchStatus, to turn alert ON and OFF
    * @Input: two Parameters:
    * id : the id whose alert status needs to be changed
    * status: to turn on or off
    *
    * Server Call:
    * library : updateSaveSearchStatus -> XMLRPC : updateSaveSearchStatus
    *
    * @Output:
    *       String signifying the alert status has been modified
    */
    function updateSaveSearchStatus($id,$status) {
        $appId = '1';
        $this->init();
        $ListingClientObj = new Listing_client();
        error_log_shiksha("$appId,$id,$status");
        $result= $ListingClientObj->updateSaveSearchStatus($appId,$id,$status);
        echo json_encode($result);
    }

    /**
    * updateSaveSearchFrequency, to change the frequency of alert
    * i.e between daily, weekly or monthly
    * @Input:
    * id : the id of the alert whose frequency needs to be changed
    * frequency : the new frequncy of the alert
    *
    * Server Call:
    * library : updateSaveSearchFrequency -> XMLRPC : updateSaveSearchFrequency
    *
    * @Output:
    *   String signifying the alert status has been modified
    */

    function updateSaveSearchFrequency($id,$frequency) {
        $appId = '1';
        $this->init();
        $ListingClientObj = new Listing_client();
        if($location=="-")
            $location="";
        error_log_shiksha("$appId,$id,$frequency");
        $result= $ListingClientObj->updateSaveSearchFrequency($appId,$id,$frequency);
        echo json_encode($result);
    }

    /**
    * getSaveSearch, get the html list of all the alerts created by the user
    * @Input:
    * start, row : Used for pagination of alerts
    *
    * Server Call:
    * library : getSaveSearch -> XMLRPC : getSaveSearch
    *
    * @Output:
    * HTML for displaying of alerts
    *
    */
    function getSaveSearch($start,$row)
    {
    	return; /*
        $appId=1;
        $this->init();
        $userid=$this->userStatus[0]['userid'];
        $ListingClientObj = new Listing_client();
        $ret=$ListingClientObj->getSaveSearch($appId,$userid,$start,$row);
        $totalCount=$ret['totalCount'];
        $data=$ret['results'];
        $i=0;
        foreach($data as $search)
        {
            if(($row-$start)>$i)
            {
                $i++;
                if($search['location']=="")
                {
                    $key=$search['keyword'];
                    $search['location']="-";
                }
                else
                    $key=$search['keyword']."-".$search['location'];
                if(strlen($key)<32)
                    $retval=$retval."<div class=\"lineSpace_20\"><span class=\"float_L lineSpace_28 disBlock W68_per\"><a href=\"javascript:showSaveSearchOverlay('".$search['id']."','".$search['keyword']."','".$search['location']."','".$search['frequency']."')\" title=\"".$key."\">".$key."</a>&nbsp;<img src=\"/public/images/editPencil.gif\" border=\"0\" /></span></div>";
                else
                    $retval=$retval."<div class=\"lineSpace_20\"><span class=\"float_L lineSpace_28 disBlock W68_per\"><a href=\"javascript:showSaveSearchOverlay('".$search['id']."','".$search['keyword']."','".$search['location']."','".$search['frequency']."')\" title=\"".$key."\">".substr($key,0,32)."....</a>&nbsp;<img src=\"/public/images/editPencil.gif\" border=\"0\" /></span></div>";
                if($search['status']==0)
                    $retval=$retval."<span class=\"float_L disBlock w20_per\"><div id=\"status101\" class=\"buttr3\" style=\"padding-left: 20px;\"><button class=\"btn-submit17 w1\" onClick=\"updateSaveSearchStatus('".$search['id']."',1,'".($row-$start)."')\"><div class=\"btn-submit17\"><p class=\"btn-submit18 btnTxtBlog\">OFF</p></div></button>&nbsp;</div></span>";
                else
                    $retval=$retval."<span class=\"float_L disBlock w20_per\"><div id=\"status101\" class=\"buttr3\" style=\"padding-left: 20px;\"><button class=\"btn-submit15 w101\" onClick=\"updateSaveSearchStatus('".$search['id']."',0,'".($row-$start)."')\"><div class=\"btn-submit15\"><p class=\"btn-submit16 btnTxtBlog\">ON</p></div></button>&nbsp;</div></span>";
                $retval=$retval."</div>";
                $retval=$retval."<span class=\"float_L disBlock w10_per\"><a href=\"javascript:deleteSaveSearch('".$search['id']."','".($row-$start)."')\"><img width=\"27\" height=\"25\" border=\"0\" src=\"/public/images/deleteIcon.gif\"/></a></span></div><div class=\"clear_L\"></div>";
            }
        }
        if($totalCount==0)
            $retval=$retval."<div class=\"float_L mar_top_4p\" style=\"width:70%\"><span class=\"grayFont\" style=\"font-size:13px;\">No alerts</span></div>";
        if((($row-$start) == 1) && ($totalCount > 1))
            $retval=$retval."<div class=\"float_L mar_right_10p\"><img src=\"/public/images/eventArrow.gif\" width=\"6\" height=\"6\" /> <a href=\"".site_url('alerts/Alerts/alertsHome').'/12'."#saveSearchDiv\">View all</a></div>";

        $retval=$retval."<input type=\"hidden\" value=\"".$totalCount."\" id=\"saveSearchtotalCount\"/>";
        echo $retval;
        return $data;
        */
    }

    /**
    * deleteSaveSearch, deleting a saved search
    * @Input:
    *   id , identifier of the alert
    *
    * Server Call: deleteSaveSearch
    * library : deleteSaveSearch -> XMLRPC : deleteSaveSearch
    *
    * @Output:
    *   String signifying the success of the action.
    */
    function deleteSaveSearch($id)
    {
        $appId = '1';
        $this->init();
        $ListingClientObj = new Listing_client();
        if($location=="-")
            $location="";
        error_log_shiksha("$appId,$id");
        $result= $ListingClientObj->deleteSaveSearch($appId,$id);
        echo json_encode($result);
    }
    /**
    * Server Call: searchCluster
    * library : searchCluster -> XMLRPC : deleteSaveSearch
    * @Input : Keyword, Location, type and category
    * @Output : The tag clusters and its contents (value, frequency)
    */
    private function getCluster($appId, $keyword, $location,$type,$countryId,$categoryId,$searchType)
    {
        $this->init();
        $ListingClientObj = new Listing_client();
        $searchCluster = $ListingClientObj->searchCluster($appId, $keyword, $location , $type,$countryId,$categoryId,$searchType);
        return $searchCluster;
    }

    /**
    * Server Call:
    * library : tagCloud -> XMLRPC : tagCloud
    * @Input : Keyword, type ,category and number of count
    * @Output : The tags (value, frequency). this generates the html output for displaying the tag Cloud.
    */
    function tagCloud($keyword, $type, $count,$categoryId="",$resultCount=0)
    {
        $this->init();
        $remoteIP= S_REMOTE_ADDR;
        $request_url= $_REQUEST['referer_url'];
        switch($type)
        {
            case "+":
                $type="all";
            break;
        }
        if($categoryId="+")
        {
            $categoryId="";
        }
        $this->init();
        $ListingClientObj = new Listing_client();
        $tagCloud = $ListingClientObj->tagCloud($keyword, $type, $count,$categoryId,$resultCount,$this->userid,$remoteIP,$request_url);
        $output=$tagCloud['results'];
        $displayData['tagCloud']=$output;
        $this->load->view('search/searchHomeLeftPanelTagCloud',$displayData);
    }

    /**
    * Server Call:
    * library : spellSuggest -> XMLRPC : spellSuggest
    * @Input : Keyword, type and number of results of the original query
    * @Output : suggested keyword display html
    */
    function spellSuggest($keyword,$searchType,$searchResult)
    {
		return;
        $this->init();
        $ListingClientObj = new Listing_client();
        $spellSuggest = $ListingClientObj->spellSuggest($keyword,$searchResult);
        if($searchType=='+')
        {
            $searchType='';
        }
        $output['spellSuggest']=$spellSuggest['results'];
        $output['searchType']=$searchType;
        $this->load->view('/search/searchHomeSpellCheck',$output);
    }

    /**
    * Server Call:
    * library : alerts addExternalQueue -> XMLRPC : addExternalQueue
    *           lms insertLead -> XMLRPC : insertLead
    * @Input(post variables) : the email address, listing URl
    * What it does: the function first creates the mail content for the interesting listing and then adds a mail to externalMailQueue. Once done it then adds the user to the lead table so that the lead is generated.
    * @Output : Confimation String and a mail is sent to user (if lead then to listing owner too)
    */
    function sendMail()
    {
        $this->init();
        $toEmail=$_POST['searchEmailAddr'];
        $fromAddress=isset($_POST['fromAddress'])?$_POST['fromAddress']:ADMIN_EMAIL;
        $subject=isset($_POST['subject'])?$_POST['subject']:'Shiksha Info';
        $bodyOfMail=isset($_POST['body'])?$_POST['body']:'';
        $extraParams=isset($_POST['extraParams'])?$_POST['extraParams']:'';
        $Id=isset($_POST['listingIdForMail'])?$_POST['listingIdForMail']:'';
        $type=isset($_POST['listingTypeForMail'])?$_POST['listingTypeForMail']:'';
        $url=isset($_POST['listingUrlForMail'])?$_POST['listingUrlForMail']:'';
        $contentArr['url'] = $url;
        $contentArr['type'] = $type;
        $contentArr['bodyOfMail'] = $bodyOfMail;
        $contentArr['extraParams'] = unserialize(base64_decode($extraParams));
        $content=$this->load->view("search/searchMail",$contentArr,true);
        $AlertClientObj = new Alerts_client();
        $toEmailArray = preg_split("/[,;]/",$toEmail);
        foreach($toEmailArray as $userEmail){
            $alertResponse = $AlertClientObj->externalQueueAdd(12,$fromAddress,$userEmail,$subject,$content,"html");
        }
        $lmsAddArray = array('institute','course','scholarship','notification');
        if(in_array($type,$lmsAddArray)){
            //Lead submission
            $signedInUser = $this->userStatus;
            $email = explode('|',$signedInUser[0]['cookiestr']);
            $addReqInfo['listing_type'] = $type;
            $addReqInfo['listing_type_id'] = $Id;
            $addReqInfo['displayName'] = $signedInUser[0]['displayname'];
            $addReqInfo['contact_cell'] = $signedInUser[0]['mobile'];
            $addReqInfo['userId'] = $signedInUser[0]['userid'];
            $addReqInfo['contact_email'] = $email[0];
            $addReqInfo['action'] = "sentmail";
            $addReqInfo['userInfo'] = json_encode($signedInUser);
            $addReqInfo['sendMail'] = true;
            $this->load->library('LmsLib');
            $LmsClientObj = new LmsLib();
            $addLeadStatus = $LmsClientObj->insertLead(1,$addReqInfo);

            error_log("BC".print_r($addLeadStatus,true));

            return($alertResponse);
        }else{
            echo $alertResponse;
        }
    }

    /**
    * Server Call:
    * library : listing getListingDetailForSms -> XMLRPC : sgetListingDetailForSms
    *           lms insertLead -> XMLRPC : insertLead
    *           alerts addSmsQueueRecord -> XMLRPC :addSmsQueueRecord
    * @Input(post variables) : the mobile Number, listing Id, listing Type
    * What it does: gets the details of the listing and creates an sms
    * @Output : Confimation String and a mail is sent to user (if lead then to listing owner too)
    */
    function sendSms($Id,$type)
    {
        $this->init();
        $toSms=$_POST['searchMobileNumber'];
        $Id=$_POST['listingIdForSms'];
        $type=$_POST['listingTypeForSms'];
        $ListingClientObj= new Listing_client();
        $listing_response=$ListingClientObj->getListingDetailForSms(0,$Id,$type);
        print_r($listing_response);
        $userid=$this->userStatus[0]['userid'];
        error_log_shiksha(print_r($this->userStatus[0],true));
        //$url="http://".THIS_CLIENT_IP.createListingUrlSearch($Id,$type);
        //$content="Dear Customer,\nThe link to the Listing you were interested is ".$url.".\nThanks,\nShiksha Team";
        $content=$listing_response[0]['listing_title'];
        $content=$content."\n".($listing_response[0]['contact_email']==""?'support@shiksha.com':$listing_response[0]['contact_email']);
        $content=$content."\n".($listing_response[0]['contact_cell']);
        $content=$content."\nRegards,Shiksha Team";
        $AlertClientObj = new Alerts_client();
        $alertResponse = $AlertClientObj->addSmsQueueRecord(12,$toSms,$content,$userid);
        //Lead submission
        $signedInUser = $this->userStatus;
        $email = explode('|',$signedInUser[0]['cookiestr']);
        $addReqInfo['listing_type'] = $type;
        $addReqInfo['listing_type_id'] = $Id;
        $addReqInfo['displayName'] = $signedInUser[0]['displayname'];
        $addReqInfo['contact_cell'] = $signedInUser[0]['mobile'];
        $addReqInfo['userId'] = $signedInUser[0]['userid'];
        $addReqInfo['contact_email'] = $email[0];
        $addReqInfo['action'] = "sentsms";
        $addReqInfo['userInfo'] = json_encode($signedInUser);
        $addReqInfo['sendMail'] = true;
        $this->load->library('LmsLib');
        $LmsClientObj = new LmsLib();
        $addLeadStatus = $LmsClientObj->insertLead(1,$addReqInfo);

        error_log("BC".print_r($addLeadStatus,true));

        return($alertResponse);
    }
    /**
    * Server Call:
    * library : listing isSaved -> XMLRPC :isSaved
    * @Input : keyword, location type
    * What it does: checks whether the user has a search alert set on the given keyword, location type
    * @Output : show the Create Search Alert/ Remove Search Alert html to be shown on search page
    */
    function isSaved($keyword,$type,$location)
    {
        $appId = '1';
        $this->init();
        $ListingClientObj = new Listing_client();
        $userId = (isset($this->userStatus[0]['userid']) && $this->userStatus[0]['userid'] ) ? $this->userStatus[0]['userid']:'';
        if($type=='+')
        {
            $type="all";
        }
        if($location='+')
        {
            $location='';
        }
        error_log_shiksha("$appId,$userId,$keyword,$location,$type");
        $result['saveCount']= $ListingClientObj->isSaved($appId,$userId,$keyword,$type,$location);
        $result['validateuser'] = $this->userStatus;
        error_log_shiksha("isSaved ".print_r($result,true));
        $this->load->view('search/searchHomeSearchAlert',$result);
    }

    /**
    * Server Call:
    * library : listing listingSearchCMS -> XMLRPC :listingSearchCMS
    * @Input : keyword, location, start, rows, type, sponseredOnTop, searchType
    * What it does: searches the listings for the CMS, i.e added userId over the existing search
    * @Output : all the results for the expected search.
    */
    function listingSearchCMS($keyword,$location,$start,$rows,$type,$sponseredOnTop,$searchType,$userId)
    {
        $appId = '1';
        $keyword=$keyword;
        $this->init();
        $ListingClientObj = new Listing_client();
        $result=$ListingClientObj->listingSearchCMS($appId,$keyword,$location,$start,$rows,$type,$sponseredOnTop,$searchType,$userId);
        echo print_r($result);
    }

    /**
    * Server Call:
    * library : listing updateSponsorListingByKeyword -> XMLRPC : updateSponsorListingByKeyword
    * @Input : keyword, location , listing Id
    * What it does: updates the sponsored/featured keyword for the given courseId
    * @Output : Confimation String and a mail is sent to user (if lead then to listing owner too)
    */

    function updateSponsorListingByKeyword($keyword,$location,$listingId,$userId)
    {
        $appId = '1';
        $this->init();
        $requestArray=array();
        $requestArray=array(array('listingId'=>array($listingId,'int'),'type'=>array('course','string')),'struct');
        $requestArray1=array($requestArray);
        error_log_shiksha("shivam".print_r($requestArray1,true));
        $ListingClientObj = new Listing_client();
        $result=$ListingClientObj->updateSponsorListingByKeyword($appID,$keyword,$location,$requestArray1,$userId);
        echo json_encode($result);
        return $result;
    }
    /**
    * Server Call:
    * library : listing addSponsorListingByKeyword-> XMLRPC :addSponsorListingByKeyword
    * @Input : keyword, location , listing Id, listing Type, searchType and sponsorType
    * What it does: it adds a sponsored/featured listing from the DB
    * @Output : Confimation String and a mail is sent to user (if lead then to listing owner too)
    */

    function addSponsorListingByKeyword($keyword,$location,$listingId,$type,$userId,$searchType,$sponserType)
    {
        $appId = '1';
        $this->init();
        if($searchType='+')
        {
            $searchType='';
        }
        $ListingClientObj = new Listing_client();
        $result=$ListingClientObj->addSponsorListingByKeyword($appID,$keyword,$location,$listingId,$type,$userId,$searchType,$sponserType);
        echo json_encode($result);
        return $result;
    }
    /**
    * Server Call:
    * library : listing deleteSponsorListingByKeyword -> XMLRPC : deleteSponsorListingByKeyword
    * @Input : keyword, location , listing Id, listing Type, searchType and sponsorType
    * What it does: it removes a sponsored/featured listing from the DB
    * @Output : retuerns sucessful confirmation
    */
    function deleteSponsorListingByKeyword($keyword,$location,$listingId,$type,$userId,$searchType,$sponserType) {
        $appId = '1';
        $this->init();
        $ListingClientObj = new Listing_client();
        $result=$ListingClientObj->deleteSponsorListingByKeyword($appID,$keyword,$location,$listingId,$type,$userId,$searchType,$sponserType);
        echo json_encode($result);
        return $result;
    }

    /**
    * Server Call:
    * Test function for deleteBulkListing i.e library call to delete multiple listings in one go
    */
    function deleteBulkListing() {
        $appId = '12';
        $this->init();
        $ListingClientObj = new Listing_client();
        //$listingArray=array(array('type'=>array("institute",'string'),'id'=>array(26000,'int')),array('type'=>array("institute",'string'),'id'=>array(26000,'int')));
        $listingArray=array(array(array('type'=>'institute','id'=>26000),'struct'),array(array('type'=>'institute','id'=>26001),'struct'));
        $result=$ListingClientObj->deleteBulkListing($appId,$listingArray);
        echo "<pre>";
        print_r($result);
        echo "</pre>";
    }

    /**
    * Server Call:
    * library : listing getSponsorListingStatusByKeyword -> XMLRPC : getSponsorListingStatusByKeyword
    * @Input(post variables) : keyword,location,listingId,type,userId,searchType,sponserType
    * What it does: checks wheter the listing is set as a featured/sponsored listing
    * @Output : whether the listing is set as a featured/sponsored listing
    */
    function getSponsorListingStatusByKeyword($keyword,$location,$listingId,$type,$userId,$searchType,$sponserType) {
        $appId = '1';
        $this->init();
        $ListingClientObj = new Listing_client();
        $result=$ListingClientObj->getSponsorListingStatusByKeyword($appID,$keyword,$location,$listingId,$type,$userId,$searchType,$sponserType);
        echo json_encode($result);
        return $result;
    }
    /**
    * Server Call:
    * library : listing getFeaturedImageUrls -> XMLRPC : getFeaturedImageUrls
    * @Input(post variables) : keyword, location and searchType
    * What it does: Gets the featured Institutes set for the given keyword, location and searchType
    * @Output : Loads the featured panel on the search Page
    */

    function getFeaturedImageUrls($keyword,$location,$searchType) {
        $appID= '1';
        $this->init();
        if($location=='+')
        {
            $location='';
        }
        if($searchType=='+')
        {
            $searchType='';
        }
        $ListingClientObj= new LISTING_CLIENT();
        $result=$ListingClientObj->getFeaturedImageUrls($appID,$keyword,$location,'1','1','1',$searchType);
        $this->load->view('search/searchHomeRightPanelFeatured',$result);
    }
    /**
    * Server Call:
    * Test function to check if the number of search snippets for the liting is wokring or not
    * test function for unit Testing
    */

    function getSearchSnippetCount() {
        $appId='1';
        $this->init();
        $type='institute';
        $typeId=1010;
        $ListingClientObj= new LISTING_CLIENT();
        $result=$ListingClientObj->getSearchSnippetCount($appId,$type,$typeId);
        echo "<pre>";
        print_r($result);
        echo "</pre>";
    }
    /**
    * Server Call:
    * Test function for checking checkValidKeyword for Listing is working or not.
    * function for unit-testing
    */

    function checkValidKeywordForListing() {
        $appId='1';
        $this->init();
        $type='institute';
        $typeId=1010;
        $keywordArray=array('mba','business','hero','test');
        $ListingClientObj= new LISTING_CLIENT();
        $result=$ListingClientObj->checkValidKeywordForListing($appId,$typeId,$type,$keywordArray);
        echo "<pre>";
        print_r($result);
        echo "</pre>";
    }

    /**
    * Server Call:
    * library : listing getDurationMinMax -> XMLRPC : getDurationMinMax
    * @Input(post variables) : get the min and maximum duration for the keyword
    * @Output : loads the duration Cluster for the search Page
    */
    function getDurationMinMax($keyword) {
        $this->init();
        $ListingClientObj= new LISTING_CLIENT();
        $result=$ListingClientObj->getDurationMinMax($keyword);
        /*echo "<pre>";
          print_r($result);
          echo "</pre>";*/
        $this->load->view('search/searchHomeLeftPanelDurationSlider',$result);
    }

    function getListingUrl($id,$type) {
        error_log("Hellos");
        $this->init();
        $ListingClientObj = new LISTING_CLIENT();
        $result=$ListingClientObj->getListingUrl($id,$type);
        if(isset($result['url']))
        {
            echo $result['url']."\n";
        }
        if(isset($result['detail']))
        {
            foreach($result['detail'] as $url)
            {
                echo $url."\n";
            }
        }
    }
    
	function getDocumentApi() {
        $appId = 1;
        $this->init();

        $displayData = array();
        $listingId = isset($_REQUEST['listingId']) ? $_REQUEST['listingId'] : '0';
        $listingType = isset($_REQUEST['listingType']) ? $_REQUEST['listingType'] : 'all';
        $outputType = isset($_REQUEST['outputType']) ? $_REQUEST['outputType'] : 'json';

        $ListingClientObj = new LISTING_CLIENT();
        $searchResult = $ListingClientObj->documentApiSearch($appId,$listingType,$listingId);
        $outputData = array();
        //$outputData['outputType']= "json";
        $outputData['numOfResults'] = $searchResult['numOfRecords'];
        $outputData['keyword']= $_REQUEST['keyword'];
        $outputData['start']= $start;
        $outputData['rows']= $rows;
        $outputData['listingType']= $listingType;
        $outputData['resultList'] = $searchResult['results'];
        if(trim($outputType)=="php")
        {
            echo "<pre>";
            print_r($outputData);
            echo "</pre>";
        }
        else if(trim($outputType)=="xml")
        {
            echo ArrayToXML::toXml($outputData);
        }
        else
        {
            echo json_encode($outputData);
        }
    }

	function topEducationSearches($startCountSearch=0,$countOffsetSearch=100) {
		return; /*
		redirect(SHIKSHA_HOME."/search", 'location', 301);
		$appId = 1;
		$ListingClientObj = new LISTING_CLIENT();
		$Result = $ListingClientObj->getDataForGenerationOfSeoUrl($appId,$startCountSearch,$countOffsetSearch);
		$paginationURL = SHIKSHA_HOME."/search/top-Education-Searches/@start@/@count@";
		$totalCount = $Result['totalRows'];
		$paginationHTML = doPagination($totalCount,$paginationURL,$startCountSearch,$countOffsetSearch,3);
		for ($i=0;$i<count($Result['resultSet']);$i++) {
		  $Result['resultSet'][$i]['urlForKeyWord'] = $this->getStaticURLForSearch($Result['resultSet'][$i]['keyword'],$Result['resultSet'][$i]['location']);
		}
		/*Code for Canonical Url,Next Url, Previous Url Start*/
		/*
		if($startCountSearch ==0 &&  $countOffsetSearch==100){
			/*If user is on first page or default landing page*/
			/*
			$canonicalUrl = SHIKSHA_HOME."/search/top-Education-Searches/";
			$nextUrl = SHIKSHA_HOME."/search/top-Education-Searches/".($startCountSearch+$countOffsetSearch).'/'.$countOffsetSearch;
			$previousUrl = '';
		}
		else{
			/*If user is on page other than first page or default landing page*/
			/*
			if($totalCount > $startCountSearch+$countOffsetSearch){
				/*If there are more pages on pagination from current page*/
				/*
				if($startCountSearch-$countOffsetSearch<=0){
					/*If you are on second page*/
					/*
					$canonicalUrl = SHIKSHA_HOME."/search/top-Education-Searches/".$startCountSearch.'/'.$countOffsetSearch;
					$nextUrl = SHIKSHA_HOME."/search/top-Education-Searches/".($startCountSearch+$countOffsetSearch).'/'.$countOffsetSearch;
					$previousUrl = SHIKSHA_HOME."/search/top-Education-Searches/";		
				}else{
                                        $canonicalUrl = SHIKSHA_HOME."/search/top-Education-Searches/".$startCountSearch.'/'.$countOffsetSearch;
                                        $nextUrl = SHIKSHA_HOME."/search/top-Education-Searches/".($startCountSearch+$countOffsetSearch).'/'.$countOffsetSearch;
                                        $previousUrl = SHIKSHA_HOME."/search/top-Education-Searches/".($startCountSearch-$countOffsetSearch).'/'.$countOffsetSearch;
                }

			}else{
				$canonicalUrl = SHIKSHA_HOME."/search/top-Education-Searches/".$startCountSearch.'/'.$countOffsetSearch;
				$nextUrl = '';
				$previousUrl = SHIKSHA_HOME."/search/top-Education-Searches/".($startCountSearch-$countOffsetSearch).'/'.$countOffsetSearch;	
			}
		}
		
		$displayData['canonicalURL'] = $canonicalUrl;
		$displayData['nextURL'] = $nextUrl;
		$displayData['previousURL'] = $previousUrl;
		/*Code for Canonical Url,Next Url, Previous Url Start*/
		/*
		$displayData['validateuser'] = $this->userStatus;
		$displayData['Result'] = $Result;
		$displayData['paginationHTML'] = $paginationHTML;
		//Tracking Code
		$displayData['beaconTrackData'] = array(
		    'pageIdentifier' => 'Desktop_TopSearchPage_National',
		    'pageEntityId' => 'topsearch-start-'.$startCountSearch.'-offset-'.$countOffsetSearch,
		    'extraData' => array('url'=>get_full_url())
		);
		$this->load->view('search/topSearches',$displayData);
		*/
	}
	
	function generateSeoURLForTopKeywordSearch($withPagination=0) {
		return;
		/*
		ini_set('max_execution_time', '1800000');
		$appId = 1;$start = 0;$urlArray = array();
		$count = ($withPagination==0)?1000:100;
		$this->init();
		$ListingClientObj = new LISTING_CLIENT();
		$Result = $ListingClientObj->getDataForGenerationOfSeoUrl($appId,$start,$count);
		$totalRows = $Result['totalRows'];
		$this->getStaticURLFromData($urlArray,$Result['resultSet'],$withPagination,$count);
		echo implode("\n",$urlArray);
		$start = 0;
		while ($totalRows >= $start) {
		  $urlArray = array();
		  $start = $start+$count;
		  $Result = $ListingClientObj->getDataForGenerationOfSeoUrl($appId,$start,$count);
		  $this->getStaticURLFromData($urlArray,$Result['resultSet'],$withPagination,$count);
		  echo "\n".implode("\n",$urlArray);
		}
		*/
	}

	private function getStaticURLFromData(&$urlArray,$ResultSet,$withPagination,$noOfRows) {
	  if ($withPagination != 0) {
		  $post_array = array();
		  $i = 0;
		  foreach ($ResultSet as $temp) {
			$post_array['keyword'.$i] = $temp['keyword'];
			$post_array['location'.$i] = ($temp['location'] == "")?-1:$temp['location'];
			$post_array['type'.$i] = "institute";
			$i++;
		  }
		  $searchCountUrl = SHIKSHA_HOME."/search/getCountForSearch/".$i;
		  $c = curl_init();
		  curl_setopt($c, CURLOPT_URL,$searchCountUrl);
		  curl_setopt($c, CURLOPT_POST, 1);
		  curl_setopt($c,CURLOPT_POSTFIELDS,$post_array);
		  curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
		  $output =  curl_exec($c);
		  curl_close($c);
		  $countArray = json_decode(gzuncompress(base64_decode($output)),true);
	  }
	  error_log("RRR :: 111 ".print_r($countArray,true));
	  $k = 0;
	  foreach ($ResultSet as $temp) {
		if ($withPagination == 0) {
		  $url = $this->getStaticURLForSearch($temp['keyword'],$temp['location']);
		  array_push($urlArray,$url);
		} else {
		  $searchCount = $countArray[$k];
		  $numerOfPages = ceil($searchCount/$noOfRows);
		  for ($i=1;$i<=$numerOfPages;$i++) {
			$url = $this->getStaticURLForSearch($temp['keyword'],$temp['location'],$i);
			array_push($urlArray,$url);
		  }
		  $k++;
		}
	  }
	  return true;
	}

	private function getStaticURLForSearch($keyword,$location,$pageNumber=-1) {
		$keyword = preg_replace(array("/\+/","/%([\d]+){2}/","/%([\d]+)([a-zA-Z]{1})/","/^([^\w]+)|([^\w]+)$/","/([^\w]+)/"),array("-","-","-","","-"),$keyword);
		$location = preg_replace(array("/\+/","/%([\d]+){2}/","/%([\d]+)([a-zA-Z]{1})/","/^([^\w]+)|([^\w]+)$/","/([^\w]+)/"),array("-","-","-","","-"),$location);
		if (preg_match("/college|colleges|collage|collages|institutes|institute/i", $keyword)) {
		  if ($location == "") {
			$url = SHIKSHA_HOME."/list-".$keyword."-in-India";
		  } else {
			$url = SHIKSHA_HOME."/list-".$keyword."-in-".$location."";
		  }
		} else {
			 if ($location == "") {
				$url = SHIKSHA_HOME."/list-$keyword-colleges-institutes-in-India";
			 } else {
				$url = SHIKSHA_HOME."/list-$keyword-colleges-institutes-in-$location";
			 }
		}
		$url = ($pageNumber != -1)?($url."-".$pageNumber.".html"):($url.".html");
		return $url;
	}

        private function _getCoursePageParams() {
		 
		$response = array();
		$cpgs_param = strip_tags($_REQUEST['cpgs_param']);
		// added if qna search is triggered from course page
		if(!empty($cpgs_param)) {
			$temp = explode("_", $cpgs_param);
			//_P($temp);
			if(!empty($temp[0])) {
				$response['tab_required_course_page'] = checkIfCourseTabRequired($temp[0]);
				$response['subcat_id_course_page'] = $temp[0];
				$response['course_pages_tabselected'] = $temp[1];
			}
		}
		 
		return $response;
	}

	/**
	 * Redirect to some specific pages for some top education search pages
	 * @author Romil Goel <romil.goel@shiksha.com>
	 * @date   2015-06-22
	 * @param  [type]     $searchString [search string part of the url]
	 * @return [type]                   [description]
	 */
	private function _redirectToSpecificPages($searchString){
    	if($searchString == 'top-10-private-engineering-colleges-in-kolkata'){
    		redirect(SHIKSHA_SCIENCE_HOME_PREFIX."/be-btech-courses-in-kolkata-ctpg", 'location', 301);
    	}
    }
}

/**
* Class for converting Array to XML
*/
class ArrayToXML
{
    /**
     * The main function for converting to an XML document.
     * Pass in a multi dimensional array and this recrusively loops through and builds up an XML document.
     *
     * @param array $data
     * @param string $rootNodeName - what you want the root node to be - defaultsto data.
     * @param SimpleXMLElement $xml - should only be used recursively
     * @return string XML
     */
    public static function toXml($data, $rootNodeName = 'shiksha_search', $xml=null)
    {
        // turn off compatibility mode as simple xml throws a wobbly if you don't.
        if (ini_get('zend.ze1_compatibility_mode') == 1)
        {
            ini_set ('zend.ze1_compatibility_mode', 0);
        }

        if ($xml == null)
        {
            $xml = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><$rootNodeName />");
        }

        // loop through the data passed in.
        foreach($data as $key => $value)
        {
            // no numeric keys in our xml please!
            if (is_numeric($key))
            {
                // make string key...
                $key = "List";
            }

            // replace anything not alpha numeric
            $key = preg_replace('/[^a-z0-9_]/i', '', $key);

            // if there is another array found recrusively call this function
            if (is_array($value))
            {
                $node = $xml->addChild($key);
                // recrusive call.
                ArrayToXML::toXml($value, $rootNodeName, $node);
            }
            else
            {
                // add single node.
                $value = htmlentities($value);
                $xml->addChild($key,$value);
            }
        }
        // pass back as string. or simple xml object if you want!
        return $xml->asXML();
    }
}
