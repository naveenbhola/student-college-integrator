<?php

class SolrSearcher {
	
	private $_ci;
	
	private $config;
	
	private $keyword = '';
	
	private $rawKeyword = '';
	
	private $spellCorrectedKeyword = '';
	
	private $start = 0;
	
	private $rows = 0;
	
	private $countryId = '';
	
	private $cityId = '';
	
	private $zoneId = '';
	
	private $localityId = '';
	
	private $courseType = '';
	
	private $courseLevel = '';
	
	private $searchType = 'course';
	
	private $parentSearchType = '';
	
	private $minDuration = '';
	
	private $maxDuration = '';
	
	private $useRequestHandler = true;
	
	private $searchServer = null;

	private $boostWordsFromClientCourse = array();
	
	public $showSponsoredResults = true;
	
	public $showFeaturedResults = true;
	
	public $showBannerResults = true;
	
	public $searchSource = 'SEARCH';
	
	public $sortType = "best";
	
	public $tempKeywordFix = "";
	
	private $ignore_institute_ids = array();
	private $showOnlyMasterListQuestion; 
	
	private $searchCommonLib;
	public $nameTranslationBoosting;

        public $nameAbroadTranslationBoosting;
        public $nameAbroadCourseTranslationBoosting;
        public $nameAbroadUniTranslationBoosting;
	public $nameTranslationFiltering;
	
	public $autosuggestFilters;
	public $unidentifiedKeywordPart;
	public $excludeQuestionIds;

	public function __construct($searchType='course'){
		$this->_ci = & get_instance();
		$this->_ci->load->helper('search/SearchUtility');
		$this->_ci->config->load('search_config');
		$this->_ci->config->load('search_config_boosting');
		$this->_ci->load->builder('SearchBuilder', 'search');
		$this->_ci->load->model("search/SearchModel", "", true);
                $this->_ci->load->library('listing/AbroadListingCommonLib');
                
		$this->config = $this->_ci->config;
		$this->searchServer = SearchBuilder::getSearchServer($this->config->item('search_server'));
		$this->boostWordsFromClientCourse = $this->config->item('boostWordsFromClientCourse');
		$this->searchCommonLib 	   = SearchBuilder::getSearchCommon();
                $this->nameAbroadCourseTranslationBoosting = $this->config->item('nameAbroadCourseTranslationBoosting');
                $this->nameUniTranslationBoosting = $this->config->item('nameUniTranslationBoosting');
                $this->nameTranslationBoosting = $this->config->item('nameTranslationBoosting');
		$this->nameTranslationFiltering = $this->config->item('nameTranslationFiltering');
		$this->searchType = $searchType;
		global $isMobileApp;
		if($isMobileApp == 1){
			unset($this->boostingParams['question_course_ldb_id']);
		}
	}
	
	/**
	 * @method array getSolrSearchResults : This function is gets the raw search results from solr. No processings
	 * work has been done here in this class. The idea is to provide whatever SOLR gives as a search results.
	 * @return array: Solr results.
	 *
	*/
	public function getSolrSearchResults() {
		$completeResultSet = array();
		switch($this->searchType){
			case 'course':
				//Get normal course results
				$ignoreIds = array();
				if(!empty($this->ignore_institute_ids)){
					$ignoreIds = $this->ignore_institute_ids;
				}
			
				//$ignoreIds[] = 33544;	
				$courseSearchResults = $this->getCourseSearchResults($ignoreIds);
				
				$finalQERURL = $courseSearchResults['final_qer_query'];
				$qerParams = array();
				if(array_key_exists('final_qer_query', $courseSearchResults)){
					$paramPickedByQER = getQerFieldsPresentInUrl($courseSearchResults['final_qer_query']);
					$qerParams 		  = getQERFieldValues($paramPickedByQER, $courseSearchResults['final_qer_query']);
				}
				
				$locationPropertySet = array();
				$locationPropertySet = getLocationPrioritySet($qerParams);
				
				//Fetch special search results like sponsored/banner/featured results from solr.
				$sponsoredListingResults = array();
				
				if($this->showSponsoredResults || $this->showFeaturedResults || $this->showBannerResults){
					$sponsoredListingResults = $this->getSponsoredResults($finalQERURL, $locationPropertySet);
				}
				
				$sponsoredResults = array();
				if($this->showSponsoredResults){
					$sponsoredResults = $sponsoredListingResults['sponsored'];
				}
				
				$featuredResults = array();
				if($this->showFeaturedResults){
					$featuredResults = $sponsoredListingResults['featured'];
				}
				
				$bannerResults = array();
				if($this->showBannerResults){
					$bannerResults = $sponsoredListingResults['banner'];	
				}
				
				//Merge sponsored and course results, merge numFound, num_found_groups etc
				$mergedResultSet = $this->processCourseSearchResults($courseSearchResults, $sponsoredResults);
				$completeResultSet = $mergedResultSet;
				
				//Check whether its a single institute search result
				$singleInstituteSearch = $this->checkForSingleInstituteSearch($mergedResultSet);
				if($singleInstituteSearch['single_result'] === true) { //Yead its a single result case
					switch($singleInstituteSearch['type']){
						case 'institute':
							//User has only typed institute name and otherfields that satisfy single result case.
							$resultSet = $mergedResultSet['results'][0];
							$instituteId = $resultSet['groupValue'];
							
							//We need all courses of this institute, so we have to make separate call to solr now.
							$singleInstituteDataSet = $this->getSolrResultsById(false, $instituteId, "institute", "institute_id", -1, $qerParams);
							$singleInstituteDataSet['general'] = $mergedResultSet['general'];
							$singleInstituteDataSet['general']['single_result'] = true;
							$singleInstituteDataSet['general']['single_result_type'] = $singleInstituteSearch['type'];
							$singleInstituteDataSet['facets'] = $courseSearchResults['facets'];
							
							/*
							Results are grouped on the basis of institute id but we need to have them grouped on categories
							Grouping on multivalued is not possible so convert manually the institute_id group results
							to parent_categories group results.
							*/
							//Call to generic function that changes groupby parameter on results
							$completeResultSet = $this->changeGroupByParameter($singleInstituteDataSet);
							//Add other solr query params to result set
							$completeResultSet = $this->processSingleSearchResultSet($completeResultSet); 
							break;
						
						case 'normal':
							// User has typed such query which automatically leads to single result case
							/*
							We already have all the data that we need to show,
							so just process the result set and group results on the basis
							of course parent categories
							*/
							$completeResultSet['general']['single_result'] = true;
							$completeResultSet['general']['single_result_type'] = $singleInstituteSearch['type'];
							$completeResultSet['general']['group_by'] = "course_parent_categories";
							//Call to generic function that changes groupby parameter on results
							$completeResultSet = $this->changeGroupByParameter($completeResultSet);
							
							break;
					}
				}
				
				/* Extra results for banner/featured resultset  */
				$completeResultSet['featured_institutes'] = $featuredResults;
				$completeResultSet['banner_institutes']   = $bannerResults;
				break;
				
			case 'content':
				//Get content searrch results.
				$contentSearchResults = $this->getContentSearchResults();
				//Add other solr query params to result set
				$completeResultSet = $this->processContentSearchResult($contentSearchResults);
				break;
				
			case 'question':
				//Not in use as of now, we do content(combination of question/article/discussion) as a whole
				$questionSearchResults = $this->getQuestionSearchResults();
				$completeResultSet = $this->processQuestionSearchResult($questionSearchResults);
				break;

			case 'discussion':
				//Not in use as of now, we do content(combination of question/article/discussion) as a whole
				$questionSearchResults = $this->getDiscussionSearchResults();
				$completeResultSet = $this->processQuestionSearchResult($questionSearchResults);
				break;
			
			case 'article':
				//Not in use as of now, we do content(combination of question/article/discussion) as a whole
				$articleSearchResults = $this->getArticleSearchResults();
				$completeResultSet = $this->processQuestionSearchResult($articleSearchResults);
				break;
		}
		return $completeResultSet;
	}
	
        
        public function getSolrSASearchResults($params = array()){
                $results = array();
                $this->keyword = $params['keyword'];
                $this->university_start = 0;
                $this->university_rows = 20;
                $this->course_start = 0;
                $this->course_rows = 20;
                
                if(preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/', $this->keyword)){
                    $this->keyword = '"' . $this->keyword . '"';
                }
                
                if(!empty($params['university_result_offset'])){
                        $this->university_start = $params['university_result_offset'];
                }
                
                if(!empty($params['university_result_rows'])){
                        $this->university_rows = $params['university_result_rows'];
                }
                
                if(!empty($params['course_result_offset'])){
                        $this->course_start = $params['course_result_offset'];
                }
                
                if(!empty($params['course_result_rows'])){
                        $this->course_rows = $params['course_result_rows'];
                }
                
                $this->abroadListingCommonLibInstance = new AbroadListingCommonLib();
                
                $universitySearchUrl = SOLR_INSTI_SELECT_URL_BASE_ABROAD;
                $universitySearchUrl = $universitySearchUrl . "q=" . $this->keyword . "&qt=abroad&fq=facetype:university";
                $universitySearchUrl = $universitySearchUrl . '&wt=phps' . '&start=' . $this->university_start . '&rows=' . $this->university_rows;
                
                $rawResult = $this->makeSolrCallWithNoResultParsing($universitySearchUrl);

                $universityResults['universities'] = $rawResult['response']['docs'];
                $universityResults['university_count'] = $rawResult['response']['numFound']; 
                $universityResults['university_result_offset'] = $rawResult['response']['start'];
                
                // add count of courses for university object.
                $univIds = array_map(function($obj){ return $obj['university_id'];},$universityResults['universities']);
		$val = $this->abroadListingCommonLibInstance->getCourseCountOfUniversities($univIds);
		for ($i=0; $i<count($universityResults['universities']); $i++){
			$universityResults['universities'][$i]['university_course_count']  = $val[$universityResults['universities'][$i]['university_id']];
                        
                }
                
                $courseSearchUrl = SOLR_INSTI_SELECT_URL_BASE_ABROAD;
                $courseSearchUrl = $courseSearchUrl . "q=" . $this->keyword . "&qt=abroad&fq=facetype:abroadcourse&group.field=sa_course_uni_id&group=true&group.limit=-1&group.ngroups=true" ;
                $courseCountUrl = $courseSearchUrl . '&wt=phps' . '&start=' . $this->course_start ;
                $courseSearchUrl  =  $courseCountUrl . '&rows=' . $this->course_rows;
                $rawResult = $this->makeSolrCallWithNoResultParsing($courseSearchUrl);
                $courseResults['sa_course_group_count'] = $rawResult['grouped']['sa_course_uni_id']['ngroups'];
                $courseResults['sa_course_count'] = $rawResult['grouped']['sa_course_uni_id']['matches'];
                $courseResults['course_result_offset'] = $rawResult['responseHeader']['params']['start'];
                $courseResults['courseList'] = array();
                for ($i=0;$i<count($rawResult['grouped']['sa_course_uni_id']['groups']); $i++){
                        $courseResults['courseList'][$i] = $rawResult['grouped']['sa_course_uni_id']['groups'][$i]['doclist']['docs'];
                }
                
                                
                if(empty($universityResults['university_count'])) {
                        $universityResults['university_count'] = 0;
                }
                
                if(empty($courseResults['sa_course_count'])) {
                        $courseResults['sa_course_count'] = 0;
                }
                
                $results = array_merge($courseResults, $universityResults);
                $results['total_count'] = $results['university_count'] + $results['sa_course_count'];
                return $results;
                                                
        }
        
	public function getSearchListingIdsByType($keyword, $type, $requestHandler = 'default'){
		$results = array();
		switch($type){
			case 'question':
				$results = $this->getQuestionIdsByKeyword($keyword);
				break;
			case 'institute':
				$results = $this->getInstituteIdsByKeyword($keyword);
				break;
		}
		return $results;
	}
	
	public function getInstituteIdsByKeyword($keyword){
		$params = array();
		$params['keyword'] 			= $keyword;
		$params['nohighlight'] 	   	= true;
		$params['facet_required'] 	= false;
		$params['request_handler'] = "none";
		$url  = $this->createSolrUrl($params); // Get the standard solr query url
		$url  = $this->setGroupUrlParams($url, 'institute_id', 1);
		$url .= "qf=institute_title&fl=score, institute_id, institute_title";
		$solrResults 	= $this->makeSolrCallWithNoResultParsing($url);
		$groupResults 	= $solrResults['grouped'];
		$results = array();
		if(array_key_exists('institute_id', $groupResults)){
			$groupList = $groupResults['institute_id'];
			if(array_key_exists('groups', $groupList)){
				$groups = $groupList['groups'];
				foreach($groups as $group){
					$tempResult = array();
					$tempResult['id'] 		= $group['groupValue'];
					$tempResult['title'] 	= $group['doclist']['docs'][0]['institute_title'];
					$results[] = $tempResult;
				}
			}
		}
		return $results;
	}
	
	public function getQuestionIdsByKeyword($keyword){
		$params = array();
		$params['keyword'] = $keyword;
		$params['request_handler'] = "relatedData";
		$params['nohighlight'] 	   = true;
		$url 		= $this->createSolrUrl($params); // Get the standard solr query url
		$flParams 	= "&fl=score, question_id, question_title, question_institute_title, question_created_time";
		$sortParams = "&sort=question_created_time desc";
		$idParam 	= "question_id";	
		$url .= $flParams . $sortParams . $mmParam;
		$solrResults = $this->makeSolrCall($url); // curl call
		$results = $solrResults['results'];
		$returnResults['resultset'] = $results;
		if(!empty($idParam) && !empty($results)){
			foreach($results as $key => $value){
				$ids[] = $value[$idParam];
			}
		}
		$returnResults['ids'] = $ids;
		return $returnResults['ids'];
	}
	
	/**
	 * @method array checkForSingleInstituteSearch : This function finds out if we have reached a single institute search or not
	 * and if its a single instititute search than whether we have to show all the courses of institutes or just courses
	 * that are coming in search results.
	 * @param array $results : Solr search results
	 * @return array: array containing two fields with single result or not and second one for what kind of single
	 * result is this.
	 * * Possible Values:
	 * array['single_result'] = true/false
	 * array['type'] = normal/institute
	 *
	*/
	private function checkForSingleInstituteSearch($resultArray = array()){
		$startParamValue = $this->_ci->input->get_post('start'); 
		if($startParamValue > 0){ //If we are not on the first page, than the single result case should not be there.
			$results = array("type" => 'normal', 'single_result' => false);
			return $results;
		}
		
		$results = array();
		$results = $resultArray;
		$generalInformation = $results['general'];
		$finalQerString = $generalInformation['final_qer_query'];
		$qerFieldsPresentInUrl = getQerFieldsPresentInUrl($finalQerString); //Finds out which QER params present in final solr url.
		$instituteSingleSearchResult = false;
		$normalSingleSearchResult = false;
		if(count($qerFieldsPresentInUrl) > 0){
			//User has entered only institute name in the search field and no other params
			$qerFields = $this->config->item('single_result_qer_fields_other_than_institute');
			if(in_array('institute_id', $qerFieldsPresentInUrl)){
				$flag = true;
				foreach($qerFieldsPresentInUrl as $field){
					if($field != 'institute_id' && !in_array($field, $qerFields)){
						$normalSingleSearchResult =  true;
						$flag = false;
						break;
					}
				}
				if($flag){
					$instituteSingleSearchResult = true;	
				} else {
					$normalSingleSearchResult =  true;
				}
			} else {
				$normalSingleSearchResult =  true;
			}
		} else {
			$normalSingleSearchResult =  true;
		}
		$returnResult = array();
		if($normalSingleSearchResult && !$instituteSingleSearchResult){
			$returnResult['type'] = "normal";
		} else if(!$normalSingleSearchResult && $instituteSingleSearchResult){
			$returnResult['type'] = "institute";
		} else {
			$returnResult['type'] = "normal";
		}
		$tempResultCount = count($results['results']) + count($results['sponsored_results']);
		if(is_array($results['results']) && $tempResultCount == 1){
			$returnResult['single_result'] = true;
		} else {
			$returnResult['single_result'] = false;
		}
		return $returnResult;
	}
	
	/**
	 * @method array getQuestionSearchResults : Get question search results.
	 * @return array: Question solr results.
	*/
	private function getQuestionSearchResults(){

		$params = array("facetype" => "question", "keyword" => ".");
		//curl call: get qerparam string for a given keyword

		$qerParamsString = $this->getQerParameters(null,"true","new"); 
		$url = $this->createNewSolrUrl($params); 

		//picking keyword and apply boosting on it if it in list boostWordsFromClientCourse.
		if(!empty($qerParamsString) && $qerParamsString != 'q.alt=*%3A*&' && $qerParamsString != 'q.alt=*:*&'){

			$pos1=strpos($qerParamsString,"q=",0);
			if($pos1>=0){
				$pos1=$pos1+2;
				$pos2=strpos($qerParamsString,"&",$pos1);
				$qerKeywords=substr($qerParamsString, $pos1, $pos2-$pos1);
				$qerParamsString = substr($qerParamsString, $pos2);
				$karr = explode("+",$qerKeywords);
				$qerKeywords="";
				foreach ($karr as $k){
					$qerKeywords=$qerKeywords.$k;
					if(in_array($k,$this->boostWordsFromClientCourse))
						$qerKeywords=$qerKeywords."^3";
					$qerKeywords=$qerKeywords."+";
				}
				$qerParamsString=$qerParamsString."&q=".$qerKeywords;
			}
			$url = $url.$qerParamsString; // append QER results to original solr query url
		}

		$url=$url."&ps=10";

		//removing 0 answer count results.
		$url.="&fq=question_answers_count:[1 TO *]";

		// do not show old questions
		$url.="&fq=question_created_time_date:[".FILTER_OLD_QUESTION_DATE." TO NOW]";

		if(!empty($this->excludeQuestionIds)){
			$url .= '&fq=-question_thread_id:('.implode(" ", $this->excludeQuestionIds).")";
		}

		if($this->showOnlyMasterListQuestion)
		{
			$url.="&fq=question_inMasterList:1";
		}

		$solrResults = $this->makeSolrCall($url); // curl call
		$solrResults['solr_url'] = $url;
		return $solrResults;
	}

	private function getDiscussionSearchResults(){

		$params = array("facetype" => "discussion", "keyword" => ".");
		//curl call: get qerparam string for a given keyword

		$qerParamsString = $this->getQerParameters(null,"discussion","new"); 
		$url = $this->createNewSolrUrl($params); 

		//picking keyword and apply boosting on it if it in list boostWordsFromClientCourse.
		if(!empty($qerParamsString) && $qerParamsString != 'q.alt=*%3A*&' && $qerParamsString != 'q.alt=*:*&'){

			$pos1=strpos($qerParamsString,"q=",0);
			if($pos1>=0 && $pos1!== false){

				$pos1=$pos1+2;
				$pos2=strpos($qerParamsString,"&",$pos1);
				$qerKeywords=substr($qerParamsString, $pos1, $pos2-$pos1);
				$qerParamsString = substr($qerParamsString, $pos2);
				$karr = explode("+",$qerKeywords);
				$keywordExactMatch = "";
				$keywordExactMatch = implode("+",$karr);
				$qerKeywords="";
				$boostingKeywords = array();
				foreach ($karr as $k){
					$qerKeywords=$qerKeywords.$k;
					if(in_array($k,$this->boostWordsFromClientCourse)){
						$qerKeywords=$qerKeywords."^3";
						$boostingKeywords[] = $k;
					}
					$qerKeywords=$qerKeywords."+";
				}
				$boostingKeywords = implode("+",$boostingKeywords);
				$qerKeywords .= '+"'.$keywordExactMatch.'"^3+"'.$boostingKeywords.'"^3';
				$qerParamsString=$qerParamsString."&q=".$qerKeywords;
			}
			$url = $url.$qerParamsString; // append QER results to original solr query url
		}
		// $url=$url."&ps=10&hl=off";
		$url .= "&ps=10&hl.fl=discussion_title";
		// $url .= "&qf=discussion_title^30000+discussion_description^300";

		// do not show old questions
		$url.="&fq=discussion_created_time:[".FILTER_OLD_QUESTION_DATE." TO NOW]";

		$solrResults = $this->makeSolrCall($url); // curl call
		$solrResults['solr_url'] = $url;
		return $solrResults;
	}
	
	/**
	 * @method array getArticleSearchResults : Get article search results.
	 * @return array: Article solr results.
	*/
	private function getArticleSearchResults(){
		$url = $this->createSolrUrl(); // Get the standard solr query url
		$solrResults = $this->makeSolrCall($url); // curl call
		$solrResults['solr_url'] = $url;
		return $solrResults;
	}
	
	/**
	 * @method array getContentSearchResults : Get the content search results.
	 * @return array: content search results.
	 *
	*/
	private function getContentSearchResults(){
		$params = array("facetype" => "content");
		$url = $this->createSolrUrl($params); // Get the standard solr query url
		$solrResults = $this->makeSolrCall($url); // curl call
		$solrResults['solr_url'] = $url;
		return $solrResults;
	}
	
	/**
	 * @method array getSponsoredResults : This function gets the special results from solr. The solr results are
	 * sorted on random order and we fetch good amount of results so that we can have all possible type of results.
	 * @return array: special results in array with different keys.
	 *
	*/
	private function getSponsoredResults($finalQERURL = "", $locationPropertySet = array()){
		//Create solr URL
		if(empty($locationPropertySet)){
			$locationPropertySet = array();
		}
		
		if(is_array($this->autosuggestFilters) && count($this->autosuggestFilters) > 0) {
			$params = array("facetype" => "course", "rows" => 40, "facet_required" => false, "autosuggestFilters" => $this->autosuggestFilters); 
			$url = $this->createSolrUrl($params);
		}
		else {
			if(!empty($finalQERURL) && $finalQERURL != 'q.alt=*%3A*&' && $finalQERURL != 'q.alt=*:*&'){
				$params = array("keyword"=> false, "nochange" => true, "facetype" => "course", "rows" => 40, "facet_required" => false);
				$url = $this->createSolrUrl($params);
				$url = $url . $finalQERURL;
			} else {
				// no information from qer
				$params = array("keyword" => $this->keyword, "facetype" => "course", "rows" => 40, "facet_required" => false); 
				$url = $this->createSolrUrl($params);
			}
		}
		
		$url = $this->setGroupUrlParams($url, 'institute_id', -1, 'random-headoffice'); //Applied groupby caluse
		$url = $this->setSortUrlParams($url, 'random-headoffice'); //Applied random sort order caluse
		$url .= "&fq=course_sponsor_types:(banner sponsored featured)&"; //Applied sponsor type params to solr url
		$solrResults = $this->makeSolrCall($url); //CURL to solr server and gets solr results in php array.
		
		$maxSponsoredResults = 0;
		$maxFeaturedResults  = 0;
		$maxBannerResults    = 0;
		//Read maximum results of each type do we need to show on search page.
		switch($this->searchSource){
			case 'SEARCH':
			case 'STATIC_SEARCH':
				$maxSponsoredResults = $this->config->item('max_sponsored_results');
				$maxFeaturedResults  = $this->config->item('max_featured_results');
				$maxBannerResults    = $this->config->item('max_banner_results');
				break;
			
			case 'CMS':
				$maxSponsoredResults = $this->config->item('cms_max_sponsored_results');
				$maxFeaturedResults  = $this->config->item('cms_max_featured_results');
				$maxBannerResults    = $this->config->item('cms_max_banner_results');
				break;
			
			default:
				$maxSponsoredResults = $this->config->item('max_sponsored_results');
				$maxFeaturedResults  = $this->config->item('max_featured_results');
				$maxBannerResults    = $this->config->item('max_banner_results');
				break;
		}
		
		//updateSolrResults gets the key value as param and return solrResults with only those documents that satisfied this key value pair.
		// With update groupValue param, numfound etc.
		$sponsoredSet = $this->updateSolrResults($solrResults, 'course_sponsor_types', 'sponsored', $locationPropertySet, $maxSponsoredResults, "institute");
		$sponsoredResults = array();
		$sponsoredResults['ids'] 			=  $sponsoredSet['group_values'];
		$sponsoredResults['search_results'] =  $sponsoredSet['solr_results'];
		
		
		//updateSolrResults gets the key value as param and return solrResults with only those documents that satisfied this key value pair.
		// With update groupValue param, numfound etc.
		$featuredSet = $this->updateSolrResults($solrResults, 'course_sponsor_types', 'featured', $locationPropertySet, $maxFeaturedResults);
		$featuredResults  = array();
		//change the SOLR results's groupBy parameter, and updated the normal solr results fields
		$results  = $this->changeGroupByParameter($featuredSet['solr_results'], 'course_sponsor_types');
		foreach($results['results'] as $result){
			if($result['groupValue'] == "featured"){
				$featuredResults = $this->getFeaturedBannerResults($result['doclist']['docs']);
			}
		}
		
		//updateSolrResults gets the key value as param and return solrResults with only those documents that satisfied this key value pair.
		// With update groupValue param, numfound etc.
		$bannerSet = $this->updateSolrResults($solrResults, 'course_sponsor_types', 'banner', $locationPropertySet, $maxBannerResults);
		$bannerResults  = array();
		//change the SOLR results's groupBy parameter, and updated the normal solr results fields
		$results  = $this->changeGroupByParameter($bannerSet['solr_results'], 'course_sponsor_types');
		foreach($results['results'] as $result){
			if($result['groupValue'] == "banner"){
				$bannerResults = $this->getFeaturedBannerResults($result['doclist']['docs']);
			}
		}
		
		$returnList = array(
							'sponsored' => 	$sponsoredResults,
							'featured'  =>  $featuredResults,
							'banner'    =>  $bannerResults
						);
		
		return $returnList;
	}
	
	/**
	 * @method array getFeaturedBannerResults : This function processed the banner/featured results as needed by the
	 * instituteRespository.
	 *
	 * @param array $documentList: DocumentList only, not the solrResults.
	 * @return array: Updated document list.
	 *
	*/
	private function getFeaturedBannerResults($documentList = array()){
		$updatedList = array();
		if(!empty($documentList)){
			foreach($documentList as $doc){
				$instituteId = $doc['institute_id'];
				if(!array_key_exists($instituteId, $updatedList)){
					$updatedList[$instituteId] = array();
					$updatedList[$instituteId]['data'] = array();
				}
				$updatedList[$instituteId]['data'][] = $doc;
			}
		}
		return $updatedList;
	}
	
	/**
	 * @method array changeGroupByParameter : This is a generic function which can be applied on the complete solr result set.
	 * Solr can only group on single valued fields, grouping on multivalued fields is not possible. We have a scenario where
	 * we need grouping on multivalued fields or comma separated fields. This purpose comes in handy when we need to change the
	 * group by parameter, it updated the standard fields like numfound, groupvalue etc based on new groupby params.
	 * 
	 * @param array $resultSet: Solr results returned by SOLR, without any modification
	 * @param string $groupField: New groupby field name
	 * @return array: updated solr results.
	 *
	*/
	private function changeGroupByParameter($resultSet = array(), $groupField = "course_parent_categories"){
		$results = $resultSet['results'];
		$fieldDocList = array();
		foreach($results as $result){
			$docList = $result['doclist'];
			$docs = $docList['docs'];
			foreach($docs as $doc){
				if(array_key_exists($groupField, $doc)){
					$fieldValues  = $doc[$groupField];
					$fieldValues  = trim($fieldValues, ",");
					$tempExplodedList = explode("," , $fieldValues);
					$explodedList = array();
					foreach($tempExplodedList as $val){
						if(trim($val) != ""){
							$explodedList[] = $val;
						}
					}
					if(count($explodedList) > 0){
						foreach($explodedList as $groupFieldValue){
							if(trim($groupFieldValue) != ""){
								$fieldDocList[trim($groupFieldValue)][] = $doc;
							}
						}	
					} else {
						$fieldDocList['-1'][] = $doc;
					}
				} else {
					//fall back if no parent_categories are present
					$fieldDocList['-1'][] = $doc;
				}
			}
		}
		
		$updatedResultList = array();
		foreach($fieldDocList as $groupFieldValue => $docList){
			$updatedResult = array();
			$docsCount = count($docList);
			$updatedResult['groupValue'] = $groupFieldValue;
			$updatedResult['doclist'] = array();
			$updatedResult['doclist']['numfound'] = $docsCount;
			$updatedResult['doclist']['docs'] = $docList;
			$updatedResultList[] = $updatedResult;
		}
		$resultSet['results'] = $updatedResultList;
		return $resultSet;
	}
	
	/**
	 * @method array updateSolrResults : This is a generic function which can be applied on the complete solr result set.
	 * Solr result as they were returned from SOLR. Based on key value, it rejects all those documents that don't have
	 * valid key value pair in it. All the related fields like groupValue, numFound and numfound_groups also gets updated
	 * on similar grounds.
	 * 
	 * @param array $solrResults: Solr results returned by SOLR, without any modification
	 * @param string $key: FieldName that should be present in every document.
	 * @param string $value: Valid fieldValue that FieldName should have in order to add particular document to valid documentList
	 * @param int $totalDocuments: How many documents do we need, if passed -1 than all the valid documents will be part of updated solr results.
	 * @return array: updated solr results.
	 *
	*/
	private function updateSolrResults($solrResults, $key = NULL, $value = NULL, $locationPropertySet, $totalDocuments = -1, $docType = "course"){
		$solrResultKeys = array_keys($solrResults);
		$results = $solrResults['results'];
		$updatedDocuments = array();
		$totalDocumentsEncountered = 0;
		$documentEncountered = array();
		foreach($results as $result){
			$groupValue = $result['groupValue'];
			$validDocumentList = array();
			$tempSolrDocumentList = $result['doclist']['docs'];
			$tempSolrDocumentList = $this->searchCommonLib->applyQERLocationSet($tempSolrDocumentList, $locationPropertySet);
			foreach($tempSolrDocumentList as $doc){
				$validDocument = false;
				$tempCourseId = $doc['course_id'];
				if(array_key_exists($key, $doc)){ //If key(fieldName) is present in the document
					$docFieldValue = $doc[$key];
					if(trim($docFieldValue) == trim($value)){ //If documentFieldValue is same as value(fieldValue).
						$validDocument = true;
					}
					if(!$validDocument){ //the fieldValue is not present as it is, it might be in array format or comma separated.
						//Handle comma separated values
						$commaPosition = strpos($docFieldValue, ",");
						if($commaPosition !== false){ //Bingo!! Its a comma separated field
							$docFieldValue  = trim($docFieldValue, ",");
							$tempExplodedList = explode("," , $docFieldValue);
							$explodedList = array();
							foreach($tempExplodedList as $val){
								if(trim($val) != "" && trim($val) == trim($value)){ //Bingo!! We found the document's field value is same as passed value
									$validDocument = true;
									break;
								}
							}
						}
					}
				}
				if($validDocument === true){ //Its a valid document add this document to valid document list
					if($totalDocuments == -1){
						array_push($validDocumentList, $doc);
					} else if($totalDocuments != -1 && $totalDocumentsEncountered < $totalDocuments){ //Check for already valid documents added to list.
						array_push($validDocumentList, $doc);
						if(!in_array($tempCourseId, $documentEncountered) && $docType == "course"){
							$totalDocumentsEncountered++;
							array_push($documentEncountered, $tempCourseId);
						}
					}
				}
			}
			if(!empty($validDocumentList)){
				$updatedDocuments[$groupValue] = $validDocumentList;
				if(!in_array($groupValue, $documentEncountered) && $docType == "institute"){
					$totalDocumentsEncountered++;
					array_push($documentEncountered, $groupValue);
				}
			}
		}
		
		$finalResults = array();
		$updatedResults = array();
		$groupValues = array();
		$totalDocuments   = 0;
		
		//Recreate  the solr results with valid set of documents.
		foreach($updatedDocuments as $groupValue => $documents){
			$tempArrayList = array();
			$tempArrayList['groupValue'] = $groupValue;
			$tempArrayList['doclist'] = array();
			$tempArrayList['doclist']['numFound'] = count($documents);
			$tempArrayList['doclist']['docs']	  = $documents;
			array_push($updatedResults, $tempArrayList);
			array_push($groupValues, $groupValue);
			$totalDocuments += count($documents);
		}
		
		$finalResults['numfound_institute_groups'] = count($groupValues);
		$finalResults['totalGroups'] 			   = count($groupValues);
		$finalResults['numfound'] 				   = $totalDocuments;
		$finalResults['results'] 				   = $updatedResults;
		
		$finalResultKeys = array_keys($finalResults);
		$keys = array_diff($solrResultKeys, $finalResultKeys);
		foreach($keys as $key){
			$finalResults[$key] = $solrResults[$key];
		}
		$returnArray = array();
		$returnArray['group_values'] = $groupValues;
		$returnArray['solr_results'] = $finalResults;
		return $returnArray;
	}
	
	/**
	 * @method array getCourseSearchResults : Get course normal results
	 * @param array $resultsToBeExcluded: instituteIds that we have to ignore while fetching solr results.
	 * @return array: Solr course results.
	 *
	*/
	private function getCourseSearchResults($resultsToBeExcluded = array()){
		
		/**
		 * If user has selected suggestions from auto-suggestor
		 * we'll skip QER and query Solr directly using selected suggestions as filters
		 */ 
		if(is_array($this->autosuggestFilters) && count($this->autosuggestFilters)) {
			$params = array("autosuggestFilters" => $this->autosuggestFilters);
			$url = $this->createSolrUrl($params);
			
			$url = $this->ignoreResultIds($url, $resultsToBeExcluded);
			$url = $this->setGroupUrlParams($url, 'institute_id'); // append group by institue id params to original url
			$url = $this->setSortUrlParams($url);
			
			$finalResults = $this->makeSolrCall($url); // curl call
			$finalResults['solr_url'] = $url;
			
			$resultStep = "QER";
			$numRows = $finalResults['numfound'];
			$zeroResultCase = $numRows == 0 ? 1 : 0;
			$searchAlternateKeyword = $this->keyword;
		}
		else {
			$resultsUsingQer = $this->getResultsUsingQER($resultsToBeExcluded);
			$numRows = $resultsUsingQer['numfound'];
			//$groupsFound = $resultsUsingQer['numfound_institute_groups'];
			$initialQerParamString = $resultsUsingQer['qer_query_string'];
			$finalQerParamString = $initialQerParamString;
			$finalResults = $resultsUsingQer;
			$searchAlternateKeyword = $this->keyword;
			$zeroResultCase = 0;
			$resultStep = "QER";
			if($numRows == 0){
				$zeroResultCase = 1;
				// Result count from QER + keyword = 0
				// Try with spell corrected keyword + QER 
				$resultsUsingSpellCheckerAndQer = $this->getResultsUsingSpellCheckAndQER($resultsToBeExcluded);
				$finalResults = $resultsUsingSpellCheckerAndQer;
				$urlForEliminateParamsCase = $resultsUsingSpellCheckerAndQer['solr_url'];
				$qerParamsStringForEliminateParamsCase = $resultsUsingSpellCheckerAndQer['qer_query_string'];
				$finalQerParamString = $qerParamsStringForEliminateParamsCase;
				$resultStep = "SPELL_CHECK_QER";
				$numRows = $resultsUsingSpellCheckerAndQer['numfound'];
				//$groupsFound = $resultsUsingSpellCheckerAndQer['numfound_institute_groups'];
				$searchAlternateKeyword = $resultsUsingSpellCheckerAndQer['search_alt_keyword'];
				
				if($numRows == 0){
					// Result count from spell corrected keyword + QER = 0
					// Try with spell corrected keyword but without QER, if spell corrected word is blank than use original keyword
					$resultUsingSpellCorrected = $this->getResultsUsingSpellCorrectedKeyword($resultsToBeExcluded);
					$finalResults = $resultUsingSpellCorrected;
					$numRows = $resultUsingSpellCorrected['numfound'];
					//$groupsFound = $resultUsingSpellCorrected['numfound_institute_groups'];
					$urlForExtraFieldsSearch = $resultUsingSpellCorrected['solr_url'];
					$resultStep = "SPELL_CHECKED_OR_NORMAL_WORD_NO_QER";
					$finalQerParamString = $resultUsingSpellCorrected['qer_query_string'];
					$searchAlternateKeyword = $resultUsingSpellCorrected['search_alt_keyword'];
					
					if($numRows == 0){
						// Result count from spell corrected keyword or notmal word = 0
						// Try with spell corrected keyword's + QER solr URL with QER params eliminate one by one
						$resultsUsingParamEliminate = $this->getResultByEliminateQERParams($urlForEliminateParamsCase, $qerParamsStringForEliminateParamsCase);
						$finalResults = $resultsUsingParamEliminate;
						$numRows = $resultsUsingParamEliminate['numfound'];
						//$groupsFound = $resultsUsingParamEliminate['numfound_institute_groups'];
						$resultStep = "SPELL_CHECK_QER_PARAM_ELIMINATE";
						$finalQerParamString = $resultsUsingParamEliminate['qer_query_string'];
						
						if($numRows == 0){
							// Result count from qer param eliminate = 0
							// Try with spell corrected keyword no qer url as solr URL with field boosts.
							$resultWithExtraFieldSearch = $this->getResultUsingExtraFields($urlForExtraFieldsSearch);
							$finalResults = $resultWithExtraFieldSearch;
							$numRows = $resultWithExtraFieldSearch['numfound'];
							//$groupsFound = $resultWithExtraFieldSearch['numfound_institute_groups'];
							$resultStep = "SEARCH_FIELD_BOOST";
							$finalQerParamString = $resultWithExtraFieldSearch['qer_query_string'];
						
							if($numRows < 5){
								// Result count from extra field boosts didn't work
								// Try with institiute wiki content search
								$resultsWithOrSearch = $this->getResultsUsingOrOperator($resultsToBeExcluded);
								$finalResults = $resultsWithOrSearch;
								$numRows = $resultsWithOrSearch['numfound'];
								//$groupsFound = $resultsWithOrSearch['numfound_institute_groups'];
								$resultStep = "OR_SEARCH_QUERY";
								$finalQerParamString = $resultsWithOrSearch['qer_query_string'];
							}
						}
					}
				}
			}
		}
		$returnResults = array();
		$returnResults['initial_qer_query'] = $initialQerParamString;
		$returnResults['final_qer_query']   = $finalQerParamString;
		$returnResults['search_results']    = $finalResults;
		$returnResults['result_step']       = $resultStep;
		$returnResults['zero_result_case']  = $zeroResultCase;
		$returnResults['search_alt_keyword']= $searchAlternateKeyword;
		$returnResults['facets']  			= $finalResults['facets'];
		$returnResults['url'] 				= $finalResults['url'];
		unset($finalResults['facets']);
		unset($finalResults['url']);
		return $returnResults;
	}
	
	private function processCourseSearchResults($courseResults = array(), $sponsoredResults = array()){
		$sponsoredResultSet 	= (array)$sponsoredResults['search_results']['results'];
		$courseResultSet 		= (array)$courseResults['search_results']['results'];
		
		
		//If we have only one result and that too a sponsored one than show it as a single result case
		$singleResultCase = false;
		if(count($courseResultSet) == 1 && count($sponsoredResults['ids']) == 0){
			$singleResultCase = true;
			$sponsoredResultSet = array();
			$sponsoredResults['search_results'] = array();
			$sponsoredResults['search_results']['results'] = array();
			$searchResults = $courseResultSet;
		} else if(count($courseResultSet) == 0 && count($sponsoredResults['ids']) == 1){
			$singleResultCase = true;
			$courseResultSet = array();
			$courseResults['search_results'] = array();
			$courseResults['search_results']['results'] = array();
			$searchResults = $sponsoredResultSet;
			$sponsoredResultSet = array();
		} else if(count($sponsoredResults['ids']) == count($courseResultSet) && count($sponsoredResults['ids']) == 1){
			if($sponsoredResults['ids'][0] == $courseResultSet[0]['groupValue']){
				$singleResultCase = true;
				$sponsoredResults = array();
				$sponsoredResultSet = array();
				$sponsoredResults['search_results'] = array();
				$sponsoredResults['search_results']['results'] = array();
				$searchResults = $courseResultSet;
			}
		}
		$commonCourseDocuments = 0;
		$commonInstitutes = 0;
		if(!$singleResultCase) { //If its not a single result sponsored case
			$sponsoredResultIds = $sponsoredResults['ids'];
			$indexList = array();
			foreach($courseResultSet as $index => $val){
				if(in_array($val['groupValue'], $sponsoredResultIds)){
					$indexList[] = $index;
					$commonCourseDocuments += count($val['doclist']['docs']);
				}
			}
			foreach($indexList as $index){
				unset($courseResultSet[$index]);
			}
			$commonInstitutes = count($indexList);
			$searchResults = $courseResultSet;
		}
		$sponsoredHighLight 	= (array)$sponsoredResults['search_results']['highlight'];
		$courseHighLight 		= (array)$courseResults['search_results']['highlight'];
		
		//Merged highlight results
		$highlightResults		= array_merge((array)$sponsoredHighLight, (array)$courseHighLight);
		$facetResults 	= array();
		$facetResults 	= (array)$courseResults['search_results']['facets'];
		$generalResult 	= array();
		$generalResult['start'] 					= $courseResults['search_results']['solr']['params']['start'];
		$generalResult['final_qer_query'] 			= $courseResults['final_qer_query'];
		$generalResult['initial_qer_query'] 		= $courseResults['initial_qer_query'];
		$generalResult['result_step'] 				= $courseResults['result_step'];
		$generalResult['zero_result_case'] 			= $courseResults['zero_result_case'];
		$generalResult['search_alt_keyword'] 		= $this->sanitizeSearchKeyword($courseResults['search_alt_keyword']);
		
		$generalResult['url'] 						= $courseResults['url'];
		$generalResult['sponsored_institute_url'] 	= $sponsoredResults['search_results']['general']['sponsored_institute_url'];
		$generalResult['sponsored_course_url'] 		= $sponsoredResults['search_results']['general']['sponsored_course_url'];
		
		
		$generalResult['group_by'] 					= 'institute_id';
		$generalResult['numfound_institute_groups'] = (int)$courseResults['search_results']['totalGroups'] + (int)$sponsoredResults['search_results']['totalGroups'] - (int)$commonInstitutes;
		$generalResult['total_institute_groups'] 	= (int)$courseResults['search_results']['numfound_institute_groups'] + (int)$sponsoredResults['search_results']['numfound_institute_groups'] - (int)$commonInstitutes;
		$generalResult['numfound_course_documents'] = (int)$courseResults['search_results']['numfound'] + (int)$sponsoredResults['search_results']['numfound'] - (int)$commonCourseDocuments;
		$generalResult['sponsored_ids'] 			= $sponsoredResults['ids'];
		
		$generalResult['single_result'] 			= false;
		$generalResult['single_result_type'] 		= "";
		$generalResult['params_picked_by_qer'] 		= getQerFieldsPresentInUrl($courseResults['final_qer_query']);
		$generalResult['raw_keyword'] 				= trim($this->rawKeyword);
		$generalResult['tempKeywordFix'] 			= trim($this->tempKeywordFix);
		$generalResult['qer_params_value'] 			= getQERFieldValues($generalResult['params_picked_by_qer'], $courseResults['final_qer_query']);
		$mergedResults = array(
							'results'   =>  $searchResults,
							'highlight' =>  $highlightResults,
							'facet'     =>  $facetResults,
							'general'   =>  $generalResult,
							'sponsored_results' => $sponsoredResultSet,
							);
		return $mergedResults;
	}
	
	private function processSingleSearchResultSet($result = array()){
		$courseIdList = array();
		$facetResults 	= array();
		$facetResults 	= (array)$result['facets'];
		if(is_array($result['results']) && count($result['results']) > 0){
			foreach($result['results'] as $index => $value){
				foreach($value['doclist']['docs'] as $doc){
					if(!in_array($doc['course_id'], $courseIdList)){
						$courseIdList[] = $doc['course_id'];
					}
				}
			}
		}
		$result['general']['numfound_course_documents'] = count($courseIdList);
		$result['general']['url'] = $result['url'];
		$result['general']['group_by'] = "course_parent_categories";
		
		$completeResultSet = array(
								'results' => $result['results'],
								'facets' => $result['facets'],
								'highlight' => $result['highlight'],
								'general' => $result['general'],
								'facet'	 => $facetResults
								);
		return $completeResultSet;
	}
	
	private function processGeneralSearchResults($result = array()){
		$completeResultSet = array(
								'results' => $result['results'],
								'highlight' => $result['highlight'],
								'general' => array(
												'url' => $result['url'],
                                                'start' => $result['solr']['params']['start'],
                                                'numfound' => $result['numfound']
												)
								);
		return $completeResultSet;
	}
	
	private function processArticleSearchResults($result = array()){
		return $this->processGeneralSearchResults($result);
	}
	
	private function processQuestionSearchResult($result = array()){
		return $this->processGeneralSearchResults($result);
	}
	
	private function processContentSearchResult($result = array()){
		$completeResults = $this->processGeneralSearchResults($result);
		$completeResults['general']['start'] = $result['solr']['params']['start'];
		$completeResults['general']['numfound_question'] = $result['numfound_question'];
		$completeResults['general']['numfound_article'] = $result['numfound_article'];
		$completeResults['general']['numfound_discussion'] = $result['numfound_discussion'];
		$completeResults['general']['numfound_content'] = $result['numfound_content'];
		return $completeResults;
	}
	
	private function getResultsUsingQER($resultsToBeExcluded = array(), $keyword = null){
		$qerParamsString = $this->getQerParameters($keyword); // curl call: get qerparam string for a given keyword
		if(!empty($qerParamsString) && $qerParamsString != 'q.alt=*%3A*&' && $qerParamsString != 'q.alt=*:*&'){
			//QER has successfully retrieved some useful information from keyword
			$urlParams = array('keyword' => '.', 'nochange' => true);
            // as always qerParamsString function return q parameters.
			$url = $this->createSolrUrl($urlParams); // Get the standard solr query url
			$url = $url . $qerParamsString; // append QER results to original solr query url
			//if(strpos($url, "q.alt") !== false){
			//	$url = $this->setRequestHandlerUrlParam($url); // append shiksha request handler to original solr query url
			//}
		} else {
			// no information from qer
			$params = array("keyword" => $keyword);
			$url = $this->createSolrUrl($params); // qer didn't retrieve any good info, so hit the basic solr query url with keyword
		}
		$url = $this->ignoreResultIds($url, $resultsToBeExcluded);
		$url = $this->setGroupUrlParams($url, 'institute_id'); // append group by institue id params to original url
		$url = $this->setSortUrlParams($url);
		$solrResults = $this->makeSolrCall($url); // curl call
		$solrResults['qer_query_string'] = $qerParamsString; // original qer string for stats purpose
		$solrResults['solr_url'] = $url;
		return $solrResults;
	}
	
	private function getResultsUsingSpellCheckAndQER($resultsToBeExcluded = array(), $keyword = null){
		$this->spellCorrectedKeyword = $this->spellChecker($this->keyword); // get spell corrected value of a keyword
		$results = $this->getResultsUsingQER($resultsToBeExcluded, $this->spellCorrectedKeyword);
		$results['search_alt_keyword'] = $this->spellCorrectedKeyword;
		return $results;
	}
	
	private function getResultsUsingSpellCorrectedKeyword($resultsToBeExcluded = array()){
		if(trim($this->spellCorrectedKeyword) != "" && strlen(trim($this->spellCorrectedKeyword)) > 0){
			$params = array("keyword" => $this->spellCorrectedKeyword);
			$url = $this->createSolrUrl($params);
			$search_alt_keyword = $this->spellCorrectedKeyword;
		} else {
			$params = array("keyword" => $this->keyword);
			$search_alt_keyword = $this->keyword;
			$url = $this->createSolrUrl($params);
		}
		$url = $this->ignoreResultIds($url, $resultsToBeExcluded);
		$url = $this->setGroupUrlParams($url, 'institute_id'); // append group by institue id params to original url
		$url = $this->setSortUrlParams($url);
		$solrResults = $this->makeSolrCall($url); // curl call
		$solrResults['solr_url'] = $url;
		$solrResults['search_alt_keyword'] = $search_alt_keyword;
		$urlParamArray = getURLParamValue($url, array("q", "q.alt", "fq"), array("facetype:course"));
		$urlParamString = getURLParamString($urlParamArray);
		$solrResults['qer_query_string'] = $urlParamString;
		return $solrResults;
	}
	
	private function getResultByEliminateQERParams($url, $qerQueryString = NULL){
		$qerParams = getQERFields();
		$qerParamsExploded = explode("&", $qerQueryString);
		$tempQerParams = array();
		foreach($qerParamsExploded as $key=>$value){
			if(trim($value) != ""){
				$tempQerParams[] = $value;
			}
		}
		$qerParamsPresent = count($tempQerParams);
		$finalResult = array();
		$qerParamCounter = 0;
		if(!empty($qerParams) && $qerParamsPresent > 1){
			$originalUrl = $url;
			foreach($qerParams as $paramKey=>$paramValue){
				if($paramValue == "raw_text_query"){ // special handling for rawTextQuery
					if(stripos($originalUrl, "q.alt") !== false){
						$key = "q.alt=";	
					} else {
						$key = "q=";
					}
				} else {
					$filterPrefix = "fq=";
					$key = $filterPrefix . $paramValue;
				}
				$start = false;
				$keyTypeFirst = "?".$key;
				$keyTypeSecond = "&".$key;
				$strPositionForKeyTypeFirst = stripos($originalUrl, $keyTypeFirst);
				$strPositionForKeyTypeSecond = stripos($originalUrl, $keyTypeSecond);
				if($strPositionForKeyTypeFirst !== false){ // check for ?key 
					$start = $strPositionForKeyTypeFirst + 1;
				} else if($strPositionForKeyTypeSecond !== false){
					$start = $strPositionForKeyTypeSecond + 1;
				}
				if($start !== false && $qerParamsPresent > 1) {
					$urlStringLength = strlen($originalUrl);
					$substr =  substr($originalUrl, $start, $urlStringLength);
					$ampersandPosition = stripos($substr, "&");
					if($ampersandPosition === false) { //no ampersand found after filter parameter, means its the last param of URL
						$end = $urlStringLength;
					} else { //ampersand found after filter parameter
						$end = $ampersandPosition;
					}
					if($paramValue == "raw_text_query"){ // special handling for rawTextQuery
						$originalUrl = substr_replace($originalUrl, 'q.alt=*:*', $start, $end);
					} else {
						$originalUrl = substr_replace($originalUrl, '', $start, $end);
					}
					$solrResults = $this->makeSolrCall($originalUrl); // curl call
					if($solrResults['numfound'] > 0){
						$urlParamArray = getURLParamValue($originalUrl, array("q", "q.alt", "fq"), array("facetype:course"));
						$urlParamString = getURLParamString($urlParamArray);
						$solrResults['solr_url'] = $originalUrl;
						$solrResults['qer_query_string'] = $urlParamString;
						$finalResult = $solrResults;
						break;
					}
					$qerParamsPresent--;
				}
			}
		}
		return $finalResult;
	}
	
	private function getResultUsingExtraFields($url, $operatorType = NULL, $fieldsWithBoost = array()) {
		if(!is_array($fieldsWithBoost) || empty($fieldsWithBoost)){
			$fieldsWithBoost = $this->config->item('query_time_fields_boost');
		}
		$fieldsWithBoostString = "";
		if(!empty($fieldsWithBoost) && is_array($fieldsWithBoost)){
			foreach($fieldsWithBoost as $key=>$value){
				if($value == NULL){
					$fieldsWithBoostString .= $key."+";
				} else {
					$fieldsWithBoostString .= $key."^".$value."+";
				}
			}
		}
		$fieldsWithBoostString = trim($fieldsWithBoostString, '+');
		$url = $url . "&qf=" . $fieldsWithBoostString . "&";
		if($operatorType != NULL){
			$url .= "&q.op=".$operatorType."&";
		}
		$solrResults = $this->makeSolrCall($url);
		$urlParamArray = getURLParamValue($url, array("q", "q.alt", "fq"), array("facetype:course"));
		$urlParamString = getURLParamString($urlParamArray);
		$solrResults['solr_url'] = $url;
		$solrResults['qer_query_string'] = $urlParamString;
		return $solrResults;
	}
	
	private function getResultsUsingOrOperator($resultsToBeExcluded = array()){
		$params = array();
		$url = $this->createSolrUrl($params);
		$url = $this->ignoreResultIds($url, $resultsToBeExcluded);
		$url = $this->setGroupUrlParams($url, 'institute_id'); // append group by institue id params to original url
		$url = $this->setSortUrlParams($url);
		$fieldsWithBoost = $this->config->item('query_time_fields_boost');
		$fieldsWithBoost['institute_wiki_content'] = 0.1;
		$solrResults = $this->getResultUsingExtraFields($url, "OR", $fieldsWithBoost);
		$solrResults['search_alt_keyword'] = $this->keyword;
		return $solrResults;
	}

	private $boostingParams = array( 'question_institute_id' => 150,
					 'question_institute_id_predicted' => 100,
                                         'question_category_ids' => 3,
                                         'question_category_ids_predicted' => 3,
                                         'question_course_ldb_id' => 80,
                                         'question_city_id' => 30,
                                         'question_state_name' => 30,
                                         'question_country_id' => 10,
                                         'question_continent_name' => 10,
                                         'question_type_cluster' => 30,
                                         'question_level_cluster' => 10,
					 );
        
        private $boostingParamsAbroad = array( 'university_id' => 50,
					 'university_country_id' => 10,
					 );
					 
	private function getQerParameters($keyword = null, $getCat = "false", $qer_version ='old'){
		//getCat is true for content type results.
		$qerResultString = "";
		if($keyword == null){
			$keyword = $this->keyword;
		}
		
		if($getCat=="true")
		{
			$qerResultString = $this->getQerParametersForQuestions($keyword,$qer_version);
		}
		else if($getCat == 'discussion'){
			$qerResultString = $this->getQerParametersForDiscussion($keyword,$qer_version);
		}
		else
		{
			$qerResultString = $this->getQerParametersForInstitute($keyword);
		}
		return $qerResultString;
	}

	private function getQerParametersForQuestions($keyword=null,$qer_version='old')
	{
		//assume to be solr_version as new.
		if($this->config->item('qer') && ($qer_version=='new'))
		{
			$qerParam = array();
			$qerUrl = $this->config->item('qer_url_new');

			$qerUrl = $qerUrl."?inkeyword=".urlencode($keyword)."&output=xmlcand&action=Submit&doObjectiveDirection=false";
			$qerOutput = $this->searchServer->curl(sanitizeUrl($qerUrl));

			$xml1 = json_decode(json_encode(simplexml_load_string($qerOutput, null, LIBXML_NOCDATA)), true);
			$xml = array();

			if(array_key_exists("institute", $xml1))
			{
				$xml1['question_institute_id_predicted']= $xml1['institute'];
			}

			if(array_key_exists("question_category_ids", $xml1))
			{
				$xml1['question_category_ids_predicted']= $xml1['question_category_ids'];
			}

			foreach($xml1 as $key=>$val){
				if($val==null || $val=="" || empty($val))
					continue;
				if(is_array($val)){ 
					$t= array_filter($val);
					if( empty($t) )
						continue;
				}
				$ftype="&bq=";

				if(array_key_exists($key, $this->nameTranslationBoosting)){
					$key = $this->nameTranslationBoosting[$key];
					$val2="";
					$countEnitityMatched =0;
					if(strpos($val,"::")!==false && strpos($val,",")!==false){
						$commaArr = explode(",", trim($val));
						foreach($commaArr as $ca){
							$splitArr = explode("::",trim($ca));
							$id = $splitArr[0];
							if(trim($id)!==""){
								$val2=$val2.$id.", ";
								$countEnitityMatched ++;
							}
						}
					}else{
						$val2=$val;
					}
					$xml[$key]=$val2;
					//error_log("dhwaj final countEntityMatched $key=".$countEnitityMatched);
					$boost=1;
					if($this->boostingParams[$key]!=null)
					{
						$boost=$this->boostingParams[$key];
						if(strpos($key, "predicted")!==false)
						{
							$boost = 2 + ($boost / $countEnitityMatched);
						}
					}
					else
					{
						continue;
					}	
					$qerResultString = $qerResultString.$ftype.$key.":(";
					$valArr = split(",",$val2);
					if($key == 'question_institute_id' || $key == 'question_institute_id_predicted'){
						$valArr = array_slice($valArr, 0, 10);
					}
					foreach($valArr as $v)
					{
						$v=trim($v);
						if($v=="")
							continue;
						$qerResultString = $qerResultString."\"".$v."\"^".$boost.' ';
					}
					$qerResultString = $qerResultString.")";
				}
			}
			$queryBoosting="";
			/*
			   1 QUERY SEPARABLE
			   1a     for separable candidates: 

			   query-objective <-> doc-objective : 100
			   query-background <-> doc-background : 50
			   query-full-text <-> doc-full-text : 50

			   1b    for non-separable candidates: 

			   query-objective <-> doc-full-text : 125
			   query-background <-> doc-full-text : 75
			 */
			if($xml['question_objective'] && $xml['question_background']){//separable query
				error_log("dhwaj boosting inside separable");
				$query_obj=trim($xml['question_objective']);
				$query_bck=trim($xml['question_background']);
				$query_full=trim($xml['QueryOrig']);
				$queryBoosting = $this->getBoostingStringForSeparableQuery($query_obj,$query_bck,$query_full,51,51,100,100,101);

			}

			/*
			   2 QUERY NOT SEPARABLE
			   2a    for separable candidates: 

			   query-full-text <-> doc-objective : 125
			   query-full-text <-> doc-background : 75

			   2b   for non-separable candidates: 

			   query-full-text <-> doc-full-text : 200
			 */
			else{
				$query_full=trim($xml['QueryOrig']);
				$queryBoosting = $this->getBoostingStringForNonSeparableQuery($query_full,100,1,101);
			}
			$qerResultString = $qerResultString.$queryBoosting;

			$qParam=$xml['QueryOrig'];
			if($qParam!=null && trim($qParam)!="")
			{
				$qerResultString = "&q=".urlencode(trim($qParam)).$qerResultString;
			}
			else
			{
				$qerResultString = "&q.alt=*:*&".$qerResultString;
			}
		}

		return $qerResultString;
	}

	private function getQerParametersForDiscussion($keyword=null,$qer_version='old')
	{
		//assume to be solr_version as new.
		if($this->config->item('qer') && ($qer_version=='new'))
		{
			$qerParam = array();
			$qerUrl = $this->config->item('qer_url_new');

			$qerUrl = $qerUrl."?inkeyword=".urlencode($keyword)."&output=xmlcand&action=Submit&doObjectiveDirection=false";
			$qerOutput = $this->searchServer->curl(sanitizeUrl($qerUrl));

			$xml1 = json_decode(json_encode(simplexml_load_string($qerOutput, null, LIBXML_NOCDATA)), true);
			$xml = array();

			foreach($xml1 as $key=>$val){
				if($val==null || $val=="" || empty($val))
					continue;
				if(is_array($val)){ 
					$t= array_filter($val);
					if( empty($t) )
						continue;
				}
				$ftype="&bq=";

				if(array_key_exists($key, $this->nameTranslationBoosting)){
					$key = $this->nameTranslationBoosting[$key];
					$val2="";
					$countEnitityMatched =0;
					if(strpos($val,"::")!==false && strpos($val,",")!==false){
						$commaArr = explode(",", trim($val));
						foreach($commaArr as $ca){
							$splitArr = explode("::",trim($ca));
							$id = $splitArr[0];
							if(trim($id)!==""){
								$val2=$val2.$id.", ";
								$countEnitityMatched ++;
							}
						}
					}else{
						$val2=$val;
					}
					$xml[$key]=$val2;
					//error_log("dhwaj final countEntityMatched $key=".$countEnitityMatched);
					$boost=1;
					if($this->boostingParams[$key]!=null)
					{
						$boost=$this->boostingParams[$key];
						if(strpos($key, "predicted")!==false)
						{
							$boost = 2 + ($boost / $countEnitityMatched);
						}
					}
					else
					{
						continue;
					}	
					$qerResultString = $qerResultString.$ftype.$key.":(";
					$valArr = split(",",$val2);
					foreach($valArr as $v)
					{
						$v=trim($v);
						if($v=="")
							continue;
						$qerResultString = $qerResultString."\"".$v."\"^".$boost.' ';
					}
					$qerResultString = $qerResultString.")";
				}
			}
			$queryBoosting="";
			/*
			   1 QUERY SEPARABLE
			   1a     for separable candidates: 

			   query-objective <-> doc-objective : 100
			   query-background <-> doc-background : 50
			   query-full-text <-> doc-full-text : 50

			   1b    for non-separable candidates: 

			   query-objective <-> doc-full-text : 125
			   query-background <-> doc-full-text : 75
			 */
			if($xml['question_objective'] && $xml['question_background']){//separable query
				$query_obj=trim($xml['question_objective']);
				$query_bck=trim($xml['question_background']);
				$query_full=trim($xml['QueryOrig']);
				$queryBoosting = $this->getDiscussionBoostingStringForSeparableQuery($query_obj,$query_bck,$query_full,51,51,100,100,101);

			}

			/*
			   2 QUERY NOT SEPARABLE
			   2a    for separable candidates: 

			   query-full-text <-> doc-objective : 125
			   query-full-text <-> doc-background : 75

			   2b   for non-separable candidates: 

			   query-full-text <-> doc-full-text : 200
			 */
			else{
				$query_full=trim($xml['QueryOrig']);
				$queryBoosting = $this->getDiscussionBoostingStringForNonSeparableQuery($query_full,100,1,101);
			}
			$qerResultString = $qerResultString.$queryBoosting;

			$qParam=$xml['QueryOrig'];
			if($qParam!=null && trim($qParam)!="")
			{
				$qerResultString = "&q=".urlencode(trim($qParam)).$qerResultString;
			}
			else
			{
				$qerResultString = "&q.alt=*:*&".$qerResultString;
			}
		}

		return $qerResultString;
	}

        
	private function getQerParametersForAbroadListings($keyword=null,$qer_version='old', $cat="course")
	{
		//assume to be solr_version as new.
		if($this->config->item('qer_abroad') && ($qer_version=='new')) //qer_abroad in config
		{
			$qerParam = array();
			$qerUrl = $this->config->item('qer_abroad_url'); //make new in config
			$qerUrl = $qerUrl."?inkeyword=".urlencode($keyword)."&output=xmlcand&action=Submit&doObjectiveDirection=false";
//                        _p($qerUrl);
                        $qerOutput = $this->searchServer->curl(sanitizeUrl($qerUrl, null, LIBXML_NOCDATA));
//                        _p(simplexml_load_string($qerOutput));
			$xml1 = json_decode(json_encode(simplexml_load_string($qerOutput, null, LIBXML_NOCDATA)), true);
                        $xml = array();

                        //make array of name translation getSolrNames   qer:city_abroad  solr:CITY_NAME
                        //loop se pahle agar kisi entity ki special handling
                        if($cat == 'course'){
                            $this->nameAbroadTranslationBoosting = $this->nameAbroadCourseTranslationBoosting;
                        } else{
                            $this->nameAbroadTranslationBoosting = $this->nameUniTranslationBoosting;
                        }
                        
			foreach($xml1 as $key=>$val){
				if($val==null || $val=="" || empty($val))
					continue;
				if(is_array($val)){ 
					$t= array_filter($val);
					if( empty($t) )
						continue;
				}
				$ftype="&bq=";
				if(array_key_exists($key, $this->nameAbroadTranslationBoosting)){
					$key = $this->nameAbroadTranslationBoosting[$key];
					$val2="";
					$countEnitityMatched =0;
					if(strpos($val,"::")!==false && strpos($val,",")!==false){
						$commaArr = explode(",", trim($val));
						foreach($commaArr as $ca){
							$splitArr = explode("::",trim($ca));
							$id = $splitArr[0];
							if(trim($id)!==""){
								$val2=$val2.$id.", ";
								$countEnitityMatched ++;
							}
						}
					}else{
						$val2=$val;
					}
					$xml[$key]=$val2;
					$boost=1;
					if($this->boostingParamsAbroad[$key]!=null)
					{
						$boost=$this->boostingParamsAbroad[$key];
						if(strpos($key, "predicted")!==false)
						{
							$boost = 2 + ($boost / $countEnitityMatched);
						}
					}
	
					$qerResultString = $qerResultString.$ftype.$key.":(";
					$valArr = split(",",$val2);
					foreach($valArr as $v)
					{
						$v=trim($v);
						if($v=="")
							continue;
						$qerResultString = $qerResultString."\"".$v."\"^".$boost.' ';
					}
					$qerResultString = $qerResultString.")";
				}
			}
			
		}

		return $qerResultString;
	}

    public function getQERFiltersForSearch($keyword) {
     return $this->getQerParametersForInstitute($keyword,true);

    }

	private function getQerParametersForInstitute($keyword = null,$returnArray = false){
		$qerResultString = null;
		$qerversion = isset($_REQUEST['qerversion']) ? $_REQUEST['qerversion'] : 'new'; 
	
		//$qerversion = "old";

		if($this->config->item('qer') && $qerversion == "old")
		{   $qerUrl = $this->config->item('qer_url');
			$qerParam = array();
			$qerUrl = $qerUrl."?inkeyword=".$keyword."&output=solrquery&action=Submit";
			$qerResultString = $this->searchServer->curl(sanitizeUrl($qerUrl));
			$qerResultString = $this->updateQERParameterString($qerResultString);
			
		}elseif($this->config->item('qer_url_upgraded'))
		    {   
			$qerUrl = $this->config->item('qer_url_upgraded');
			//$qerUrl = "http://172.10.16.51:8984/query_entity_recognition/quiet";	
			//$qerUrl = "http://172.16.3.247:8986/query_intent_entity_tagger/quiet";	
			
			$qerSolrFieldMapping = $this->config->item('qer_to_solr_field_mapping');
			$qerParam = array();
			$qerUrl = $qerUrl."?inkeyword=".str_replace(array("&","%"), "", $keyword)."&output=xmlcand&action=Submit";
			$qerResultString = $this->searchServer->curl(str_replace(" ", "+", $qerUrl));

			$qerXMLResult = json_decode(json_encode(simplexml_load_string($qerResultString, null, LIBXML_NOCDATA)), true);
			$solrQueryData = array();
			foreach($qerSolrFieldMapping as $qerField => $solrField) {
				if(array_key_exists($qerField, $qerXMLResult)) {
					if($returnArray == true) {
						$solrQueryData[$qerField] = $this->extractAndSanitizeQERData($qerXMLResult[$qerField],$solrField,$returnArray);
				     } else {
					 	$solrQueryData[$solrField] = $this->extractAndSanitizeQERData($qerXMLResult[$qerField],$solrField);
					 }				
				}
			}
			if($returnArray == true) {
					if(array_key_exists('finalRawQueryCleaned',$qerXMLResult)) {
						$val = trim($qerXMLResult['finalRawQueryCleaned']);
						if(!empty($val)) {
						$val =	str_replace(',',' ',$val);
				 		$val =	str_replace(' ','+',trim($val));
				 	}
				 	if(!empty($val)){
				 	  $solrQueryData['q'] = $val;		
				 	}
				}
				return $solrQueryData;
			} 
			$qerResultString = $this->makeSolrQuery($solrQueryData,$qerXMLResult);
		}
		return $qerResultString;
	}
	
	private function makeSolrQuery($solrQueryData,$qerXMLResult) {
		
		if(array_key_exists('finalRawQueryCleaned',$qerXMLResult)) {
			$val = trim($qerXMLResult['finalRawQueryCleaned']);
			if(!empty($val)) {
				$val =	str_replace(',',' ',$val);
			 	$val =	str_replace(' ','+',trim($val));
			 }
		}
		if(!empty($val)) {
			$solrQuery = "q=".$val;
		}else {
			$solrQuery = "q.alt=*%3A*";	
		}
		
		foreach($solrQueryData as $field=>$val) {
			$solrQuery = $solrQuery."&fq=".$field.":(".$val.")";
		}
		$solrQuery = $solrQuery."&";
		return $solrQuery;
	}

	private function extractAndSanitizeQERData($qerXMLResult,$solrField,$returnArray = false) {
		$qerResultArray =	explode(",",$qerXMLResult);
		
		foreach($qerResultArray as $qerResultElement) {
			$val2 = "";
			$qerResultReqElemt = explode("::",$qerResultElement);
			$val2 = trim($qerResultReqElemt[0]);
			if(!empty($val2)){
			    	 if ($returnArray == true) {
			    	 	$val[] = $val2;
			    	 } else {
			    		$val = $val.'"'.$val2	.'"';
			    	 }
			 }	
		}
		return $val;
	}

	private function updateQERParameterString($qerResultString = ""){
		$newQerResultString = $qerResultString;
		if(!empty($qerResultString)){
			$qerFieldPresent = getQerFieldsPresentInUrl($qerResultString);
			if(in_array('course_city_id', $qerFieldPresent)){
				$qerFieldValues  = getQERFieldValues($qerFieldPresent, $qerResultString);
				$cityMapping = $this->config->item('city_mapping');
				$cities = updateCityUsingCityMapping($qerFieldValues['course_city_id'], $cityMapping);
				$q_alt 	= getParameterValue($qerResultString, 'q_alt');
				$q 		= getParameterValue($qerResultString, 'q');
				$newQerResultString = "";
				if($q !== false){
					$newQerResultString = "q=" . $q . "&";
				} else if($q_alt !== false){
					$newQerResultString = "q.alt=" . $q_alt . "&";
				}
				foreach($qerFieldValues as $param => $values){
					if($param == "course_city_id"){
						$values = $cities;
					}
					$tempString = "";
					foreach($values as $value){
						$tempString .= '"' . $value . '"';
					}
					$newQerResultString .= "fq=".$param. ":(" . $tempString . ")&";
				}
			}
		}
		return $newQerResultString;
	}
	
	private function getSolrResultsById($keyword, $ids, $idType = "institute", $groupBy = 'institute_id', $groupLimit = -1, $qerParams = array()){
		switch($idType){
			
			case "institute":
				$params = array('keyword' => $keyword);
				$url = $this->createSolrUrl($params);
				$instituteIdStr = "";
				if(is_array($ids)){
					foreach($ids as $id){
						$instituteIdStr .= $id . " ";
					}
				} else {
					$instituteIdStr = $ids;
				}
				$idFqParams = "&fq=institute_id:(" . $instituteIdStr . ")&";
				$locationQERParams = array('course_city_id', 'course_locality_id', 'course_country_id', 'course_zone_id', 'course_state_id');
				$locationFQParms = "";
				foreach($qerParams as $key => $value){
					if(in_array($key, $locationQERParams)){
						$tempLocStr = "";
						foreach($value as $v){
							$tempLocStr .= '"' . $v . '" ';
						}
						$locationFQParms .= '&fq=' . $key . ':(' . $tempLocStr . ')&';
					}
				}
				$url .= $idFqParams;
				$url .= $locationFQParms;
				if($groupBy != null){
					switch($groupBy){
						case 'institute_id':
							$url = $this->setGroupUrlParams($url, "institute_id", $groupLimit);
							$url = $this->setSortUrlParams($url);
							break;
						
						case 'course_parent_categories':
							$url = $this->setGroupUrlParams($url, "course_parent_categories", $groupLimit);
							$url = $this->setSortUrlParams($url);
							break;
					}	
				}
				break;
			
			case "course":
				$params = array('keyword' => $keyword);
				$url = $this->createSolrUrl($params);
				$courseIdStr = "";
				if(is_array($ids)){
					foreach($ids as $id){
						$courseIdStr .= $id . " ";
					}
				} else {
					$courseIdStr = $ids;
				}
				$idFqParams = "&fq=course_id:(" . $courseIdStr . ")&";
				$url .= $idFqParams;
				if($groupBy != null){
					switch($groupBy){
						case 'institute_id':
							$url = $this->setGroupUrlParams($url, "institute_id", $groupLimit);
							$url = $this->setSortUrlParams($url);
							break;
					}	
				}
				break;
		}
		
		$solrResults = $this->makeSolrCall($url);
		return $solrResults;
	}
	
	private function ignoreResultIds($url, $ids = array()){
		switch($this->searchType){
			case 'course':
				$fqParamString = "";
				foreach($ids as $id){
					$fqParamString .= "&fq=-institute_id:". $id . "&";
				}
				$url = $url . $fqParamString;
				break;
		}
		return $url;
	}
	
	private function spellChecker($keywords, $removeIfNotPresent = true) {
		$spellCheckedWord = "";
		$keywords = strtolower(trim($keywords));
		$keywords = str_replace(" ","+",$keywords); // replace space with +
		$keywords = str_replace("%20","+",$keywords); // replace encoded space with +
		$keywordArr = explode("+",$keywords);
		foreach ($keywordArr as $word){ // for each word in original word
			$solrQueryUrl = $this->searchServer->getSolrUrl('spellcheck', 'select');
			$solrQueryUrl = $solrQueryUrl . "q=" . $word . "&spellcheck=true&wt=phps&rows=0";
			$solrResult = $this->searchServer->curl($solrQueryUrl);
			$solrResult = unserialize($solrResult);
			$spellSuggestions = array();
			if(is_array($solrResult['spellcheck']['suggestions'][$word]) && is_array($solrResult['spellcheck']['suggestions'][$word]['suggestion'])) {
				$spellSuggestions = $solrResult['spellcheck']['suggestions'][$word]['suggestion'];

			}
			//$exist = $solrResult['exist'];
			
			//if($exist == "true") {
			//	$spellCheckedWord = $spellCheckedWord ."+". $word; // No need to pick from suggestions, as this word already exists in dictionary
			//}
			//else {
				if(count($spellSuggestions) > 0) { //If there are suggestions for present word
					foreach($spellSuggestions as $suggestion) {
						if(trim($suggestion) != "") {
							$spellCheckedWord = $spellCheckedWord ."+". $suggestion; // add all suggestions
						}
					}
				} else {
					if(!$removeIfNotPresent) { // Remove the original keyword from the spell checked word if its not present in dictionary
						$spellCheckedWord = $spellCheckedWord ."+". $word;
					}
				}
			//}
		}
		return trim($spellCheckedWord);
	}
	
	private function makeSolrCall($url){
		$content = $this->searchServer->curl(sanitizeUrl($url));
		$results = unserialize($content);
		switch($this->searchType){
			case 'course':
				$solrResultArray['totalGroups'] 		= count($results['grouped']['institute_id']['groups']);
				$solrResultArray['numfound'] 			= (int)$results['grouped']['institute_id']["matches"];
				$solrResultArray['numfound_institute_groups'] = (int)$results['grouped']['institute_id']["ngroups"];
				$groupBy = $results['responseHeader']['params']['group.field'];
				$solrResultArray['results'] = $results['grouped'][$groupBy]['groups'];
				$solrResultArray['start'] 	= $results['responseHeader']['params']['start'];
				break;
			
			case 'question':
			case 'discussion':
			case 'article':
				$solrResultArray['results'] = $results['response']['docs'];
				$solrResultArray['numfound'] = (int)$results['response']['numFound'];
				$solrResultArray['start'] = $results['responseHeader']['params']['start'];
				break;
			case 'content':
				$facets = $results['facet_counts']['facet_fields'];
				$questionCount = -1;
				$articleCount = -1;
				$discussionCount = -1;
				if(array_key_exists('facetype', $facets)){
					$questionCount = $facets['facetype']['question'];
					$articleCount = $facets['facetype']['article'];
					$discussionCount = $facets['facetype']['discussion'];
				}
				$solrResultArray['results'] = $results['response']['docs'];
				$solrResultArray['numfound_content'] = (int)$results['response']['numFound'];
				$solrResultArray['numfound_question'] = (int)$questionCount;
				$solrResultArray['numfound_article'] = (int)$articleCount;
				$solrResultArray['numfound_discussion'] = (int)$discussionCount;
				$solrResultArray['start'] = $results['responseHeader']['params']['start'];
				break;
		}
		$solrResultArray['facets'] = $results['facet_counts']['facet_fields'];
		$solrResultArray['url'] = $url;
		$solrResultArray['solr'] = $results['responseHeader'];
		$solrResultArray['highlight'] = $results['highlighting'];
		return $solrResultArray;
	}
	
	private function makeSolrCallWithNoResultParsing($url){
		$content = $this->searchServer->curl(sanitizeUrl($url));
		$results = unserialize($content);
		return $results;
	}
	
	private function createNewSolrUrl($params = array()) {
		$url = $params['url'];
		if(empty($url)){
			$url = $this->searchServer->getNewSolrUrl($this->courseType, 'select');
		}

		$url = $this->setKeywordUrlParam($url, $params['keyword'], $params['nochange']);
		$url = $this->setStartUrlParam($url, $params['start']);
		$url = $this->setRowsUrlParam($url, $params['rows']);
		$url = $this->setCountryUrlParam($url, $params['country_id']);
		$url = $this->setCityUrlParam($url, $params['city_id']);
		$url = $this->setLocalityUrlParam($url, $params['locality_id']);
		$url = $this->setZoneUrlParam($url, $params['zone_id']);
		$url = $this->setCourseTypeUrlParam($url, $params['course_type']);
		$url = $this->setCourseLevelUrlParam($url, $params['course_level']);
		$url = $this->setFacetypeUrlParam($url, $params['facetype']);
		$url = $this->setNormalizedDurationUrlParam($url, $params['course_normalized_duration']);
		$url = $this->setFacetUrlParam($url, $params['facet_required']);
		$url = $this->setHighlightUrlParam($url, true, $params['keyword']);
		$url = $this->setResponseTypeUrlParam($url);
		$url = $this->setRelaxFlagUrlParam($url);
		$url = $this->setRequestHandlerUrlParam($url, $params['request_handler']);
		return $url."&qt=shiksha&lowercaseOperators=false&";
	}
	
	private function createSolrUrl($params = array()) {
		$url = $params['url'];
		if(empty($url)){
			$url = $this->searchServer->getSolrUrl($this->courseType, 'select');
		}
		
		$url = $this->setKeywordUrlParam($url, $params['keyword'], $params['nochange'], $params['autosuggestFilters']);
		$url = $this->setStartUrlParam($url, $params['start']);
		$url = $this->setRowsUrlParam($url, $params['rows']);
		$url = $this->setCountryUrlParam($url, $params['country_id']);
		$url = $this->setCityUrlParam($url, $params['city_id']);
		$url = $this->setLocalityUrlParam($url, $params['locality_id']);
		$url = $this->setZoneUrlParam($url, $params['zone_id']);
		$url = $this->setCourseTypeUrlParam($url, $params['course_type']);
		$url = $this->setCourseLevelUrlParam($url, $params['course_level']);
		$url = $this->setFacetypeUrlParam($url, $params['facetype']);
		$url = $this->setNormalizedDurationUrlParam($url, $params['course_normalized_duration']);
		$url = $this->setFacetUrlParam($url, $params['facet_required']);
		$url = $this->setHighlightUrlParam($url, $params['nohighlight'], $params['keyword']);
		$url = $this->setResponseTypeUrlParam($url);
		$url = $this->setRelaxFlagUrlParam($url);
		$url = $this->setRequestHandlerUrlParam($url, $params['request_handler']);
		
		if(array_key_exists('autosuggestFilters', $params) && is_array($params['autosuggestFilters']) && count($params['autosuggestFilters']) > 0) {
			$url = trim($url,'&');
			foreach($params['autosuggestFilters'] as $filterKey => $filterValue) {
				$url .= "&fq=".$filterKey.":\"".urlencode($filterValue)."\"";
			}
			$url .= '&';
		}
		return $url;
	}
	
	private function setCountryUrlParam($url, $countryId = ""){
		if(empty($countryId)){
			$countryId = $this->countryId;
		}
		if(!empty($countryId)){
			switch($this->searchType){
				case 'course':
					$url = $url . "fq=(course_country_id:".trim($countryId).")&";
					break;
			}
		}
		return trim($url);
	}
	
	private function setKeywordUrlParam($url, $keyword = '', $nochange = false, $autosuggestFilters = array()){
		/*	if($nochange){ 
			return trim($url);
			}
			if($keyword !== false){
			if(empty($keyword)){
			$keyword = urlencode(trim($this->keyword));
			}	
			if(!empty($keyword)){
			$url = $url . "q=" . urlencode(trim($keyword)) . "&";
			}	
			} else {
			$url = $url . "q=*:*&";
			}
			return trim($url);
		 */
		
		if(count($autosuggestFilters) > 0) {
			$url = $url . "q=*:*&";
		}
		else {
			if($keyword !== false){
	
				if(empty($keyword)){
					$keyword = $this->keyword;
				}
				if(!empty($keyword) && $keyword!="."){
	
					$url = $url . "q=" .urlencode( trim(strtolower($keyword))) . "&";
				}	
			}
			else if($keyword=="."){
				return trim($url);
			} 
			else {
				$url = $url . "q=*:*&";
			}
		}
		return trim($url);
	}

	private function setStartUrlParam($url, $start = ""){
		if(empty($start)){
			$start = $this->start;
		}
		if(isset($start)){
			$url = $url . "start=" . trim($start) . "&";
		}
		return trim($url);
	}
	
	private function setRowsUrlParam($url, $rows = ""){
		if(empty($rows)){
			$rows = $this->rows;
		}
		if(!empty($rows)){
			$url = $url . "rows=" . trim($rows) . "&";
		}
		return trim($url);
	}
	
	private function setCityUrlParam($url, $cityId = ""){
		if(empty($cityId)){
			$cityId = $this->cityId;
		}
		if(!empty($cityId)){
			switch($this->searchType){
				case 'course':
					$url = $url . "fq=(course_city_id:".trim($cityId).")&";
					break;
			}
		}
		return trim($url);
	}
	
	private function setZoneUrlParam($url, $zoneId = ""){
		if(empty($zoneId)){
			$zoneId = $this->zoneId;
		}
		if(!empty($zoneId)){
			switch($this->searchType){
				case 'course':
					$url = $url . "fq=(course_zone_id:".trim($zoneId).")&";
					break;
			}
		}
		return trim($url);
	}
	
	private function setLocalityUrlParam($url, $localityId = ""){
		if(empty($localityId)){
			$localityId = $this->localityId;
		}
		if(!empty($localityId)){
			switch($this->searchType){
				case 'course':
					$url = $url . "fq=(course_locality_id:".trim($localityId).")&";
					break;
			}
		}
		return trim($url);
	}
	
	private function setCourseTypeUrlParam($url, $courseType = ""){
		if(empty($courseType)){
			$courseType = $this->courseType;
		}
		if(!empty($courseType)){
			switch($this->searchType){
				case 'course':
					$url = $url . "fq=(course_type_cluster:".trim($courseType).")&";
					break;
			}
		}
		return trim($url);
	}
	
	private function setCourseLevelUrlParam($url, $courseLevel = ""){
		if(empty($courseLevel)){
			$courseLevel = $this->courseLevel;
		}
		if(!empty($courseLevel)){
			switch($this->searchType){
				case 'course':
					$url = $url . "fq=(course_level_cluster:".trim($courseLevel).")&";
					break;
			}
		}
		return trim($url);
	}
	
	private function setFacetypeUrlParam($url, $type = ""){
		if(empty($type)){
			$type = $this->searchType;
		}
		switch($this->searchType){
			case 'content':
				$faceTypeInContentSearch = $this->config->item('facetype_in_content_search');
				$facetypeString = "";
				foreach($faceTypeInContentSearch as $facetype){
					$facetypeString .= $facetype . " ";
				}
				$url = $url . "fq=facetype:(".$facetypeString.")&";
				break;
			case 'course':
			case 'article':
			case 'question':
			case 'discussion':
			case 'autosuggestor':
				$url = $url . "fq=facetype:".$type."&";
				break;
		}
		return trim($url);
	}
	
	private function setNormalizedDurationUrlParam($url, $minDuration = "", $maxDuration = ""){
		if(!isset($minDuration) || $minDuration == ""){
			$minDuration = $this->minDuration;
		}
		if(!isset($maxDuration) || $maxDuration == ""){
			$minDuration = $this->maxDuration;
		}
		
		if($minDuration != "" && $maxDuration != ""){
			$url = $url . "fq=(course_duration_normalized:[".$minDuration."%20TO%20".$maxDuration."]&";
		}
		return $url;
	}
	
	private function setFacetUrlParam($url, $required = NULL, $type = ""){
		if($required !== false){
			if(empty($type)){
				$type = $this->searchType;
			}
			switch($type){
				case 'course':
					$facetFields = $this->config->item('facet_fields');
					$facet =  "facet=true&";
					$facet .= "facet.sort=true&";
					$facet .= "facet.limit=-1&";
					$facet .= "facet.zeros=false&";
					foreach($facetFields as $field){
						$facet .= "facet.field=".$field."&";
					}
					$url = $url . $facet;
					break;
				
				case 'content':
					$facetFields = array('facetype');
					$facet =  "facet=true&";
					$facet .= "facet.sort=true&";
					$facet .= "facet.limit=-1&";
					$facet .= "facet.zeros=false&";
					foreach($facetFields as $field){
						$facet .= "facet.field=".$field."&";
					}
					$url = $url . $facet;
					break;
			}
		}
		return $url;
	}
	
	private function setHighlightUrlParam($url, $nohighLight = false, $keyword){
		if(!$nohighLight){
			if($this->config->item('highlight')){
				$highlight  = "hl=on&";
				$highlight .= "hl.fragsize=300&";
				$highLightKeyword = $keyword;
				if($keyword == false){
					$highLightKeyword = $this->keyword;
				}
				$highlight .= "hl.q=".urlencode(str_replace("/","\/",$highLightKeyword))."&";
				$url = $url . trim($highlight);
			}	
		}
		return $url;
	}
	
	private function setRequestHandlerUrlParam($url, $requestHandler = 'shiksha'){
		$validRequestHandlers = array('shiksha', 'relatedData', 'none');
		if(!empty($requestHandler)){
			if(in_array($requestHandler, $validRequestHandlers)){
				if($requestHandler != "none"){
					$url = $url . "qt=" . $requestHandler . "&";
				}
			} else {
				$url = $url . "qt=shiksha&";
			}
		} else {
			$url = $url . "qt=shiksha&";	
		}
		return $url;
	}
	
	private function setResponseTypeUrlParam($url){
		$url = $url . "wt=" . $this->config->item("solr_response_format") . "&";
		return $url;
	}
	
	private function setRelaxFlagUrlParam($url){
		switch($this->searchType){
			case 'content':
				$urlString .= "&mm=1&";
				$url = $url . $urlString;
				break;
		}
		return $url;
	}
	
	private function setGroupUrlParams($url, $groupBy = null, $groupLimit = -1, $sortType = NULL){
		if($groupBy != null){
			$groupStr = "";
			$groupSortStr = "";
			if(empty($sortType)){
				$sortType = $this->sortType;
			}
			
			switch($sortType){
				case 'best':
					$groupSortStr = "group.sort=course_order asc, course_view_count desc&";
					break;
				case 'popular':
					$groupSortStr = "group.sort=course_view_count desc, score desc&";
					break;
				case 'random-headoffice':
					$randStr = getRandomAlphaNumericStr(12);
					$groupSortStr = "group.sort=course_head_office desc, random_".$randStr." desc&";
					break;
				default:
					$groupSortStr = "group.sort=score desc&";
					break;
			}
			
			switch($groupBy){
				case 'institute_id':
					$groupStr .= "group.field=institute_id&";
					$groupStr .= "group.ngroups=true&";
					break;
				case 'course_parent_categories':
					$groupStr .= "group.field=course_parent_categories&";
					break;
			}
			if(strlen(trim($groupStr)) > 0){
				$groupStr .= "group=true&";
				if($groupLimit == -1) {
                                        $groupLimit = 6;
                                }
				$groupStr .= "group.limit=" . $groupLimit . "&";
				$groupStr .= $groupSortStr;
				$url = $url ."&".$groupStr;
			}
		}
		return $url;
	}
	
	private function setSortUrlParams($url, $sortType = null){
		if($sortType == null){
			$sortType = $this->sortType;
		}
		if($sortType != null){
			$sortStr = "";
			switch($sortType){
				case 'best':
					$sortStr .= "sort=score desc&";
					break;
				case 'popular':
					$sortStr .= "sort=institute_view_count desc&";
					break;
				case 'random':
					$randStr = getRandomAlphaNumericStr(12);
					$sortStr .= "sort=random_".$randStr." desc&";
					break;
				case 'random-headoffice':
					$randStr = getRandomAlphaNumericStr(12);
					$sortStr .= "sort=random_".$randStr." desc&";
					break;
				default:
					$sortStr .= "sort=score desc&";
					break;
			}
			if(strlen(trim($sortStr)) > 0){
				$url = $url . $sortStr;
			}
		}
		return $url;
	}
	
	public function setSearcherParams($params = array(), $type = "course"){
		if(is_array($params) && !empty($params)){
			if(isset($params['search_type'])){
				$this->searchType = $params['search_type'];
			} else {
				$this->searchType = 'course';
			}
			
			if(isset($params['keyword'])){
				$this->keyword = $params['keyword'];
			} else {
				$this->keyword = '';
			}
			
			if(isset($params['tempKeywordFix'])){
				$this->tempKeywordFix = $params['tempKeywordFix'];
			} else {
				$this->tempKeywordFix = '';
			}
			
			
			if(isset($params['start'])){
				$this->start = $params['start'];
			} else {
				$this->start = 0;
			}
			
			if(isset($params['institute_rows']) && $params['institute_rows'] >= 0 && isset($params['content_rows']) && $params['content_rows'] >= 0){
				$this->rows = (int)$params['institute_rows'] + (int)$params['content_rows'];
			} else {
				switch($this->searchType){
					case 'institute':
					case 'course':
						$tempRows = $this->searchCommonLib->getMaxRowsCount('institute', $params);
						break;
					
					case 'content':
						$tempRows = $this->searchCommonLib->getMaxRowsCount('content', $params);
						break;
				}
				//$this->rows = (int)$tempRows['institute_rows'] + (int)$tempRows['content_rows'];
				if(!empty($params['rows'])){
					$this->rows = $params['rows'];	
				} else {
					$this->rows = 20;
				}
			}
			
			if(isset($params['keyword'])){
				$this->rawKeyword = $params['keyword'];
			} else {
				$this->rawKeyword = '';
			}
			
			if(isset($params['country_id'])){
				$this->countryId = $params['country_id'];
			} else {
				$this->countryId = '';
			}
			
			if(isset($params['city_id'])){
				$this->cityId = $params['city_id'];
			} else {
				$this->cityId = '';
			}
			
			if(isset($params['zone_id'])){
				$this->zoneId = $params['zone_id'];
			} else {
				$this->zoneId = '';
			}
			
			if(isset($params['locality_id'])){
				$this->localityId = $params['locality_id'];
			} else {
				$this->localityId = '';
			}
			
			if(isset($params['course_type'])){
				$this->courseType = $params['course_type'];
			} else {
				$this->courseType = '';
			}
			
			if(isset($params['course_level'])){
				$this->courseLevel = $params['course_level'];
			} else {
				$this->courseLevel = '';
			}
			
			if(isset($params['min_duration'])){
				$this->minDuration = $params['min_duration'];
			} else {
				$this->minDuration = '';
			}
			
			if(isset($params['max_duration'])){
				$this->maxDuration = $params['max_duration'];
			} else {
				$this->maxDuration = '';
			}
			
			if(isset($params['show_sponsored_results'])){
				$this->showSponsoredResults = $params['show_sponsored_results'];
			} else {
				$this->showSponsoredResults = false;
			}
			
			if(isset($params['show_featured_results'])){
				$this->showFeaturedResults = $params['show_featured_results'];
			} else {
				$this->showFeaturedResults = false;
			}
			
			if(isset($params['show_banner_results'])){
				$this->showBannerResults = $params['show_banner_results'];
			} else {
				$this->showBannerResults = false;
			}
			
			if(isset($params['search_source'])){
				$this->searchSource = $params['search_source'];
			} else {
				$this->searchSource = 'SEARCH';
			}
			
			if(isset($params['sort_type'])){
				$this->sortType = $params['sort_type'];
			} else {
				$this->sortType = "best";
			}
			
			if(!empty($params['ignore_institute_ids'])){
				$this->ignore_institute_ids = $params['ignore_institute_ids'];
			} else {
				$this->ignore_institute_ids = array();
			}
			if($params['show_only_master_list_questions'])
			{
				$this->showOnlyMasterListQuestion=$params['show_only_master_list_questions'];
			}else{
				$this->showOnlyMasterListQuestion =0;
			}
			
			if($params['autosuggest_filters']) {
				$this->autosuggestFilters = $params['autosuggest_filters'];
			}
			if($params['unidentifiedKeywordPart']) {
				$this->unidentifiedKeywordPart = $params['unidentifiedKeywordPart'];
			}

			if(!empty($params['exclude_question_ids'])){
				$this->excludeQuestionIds = $params['exclude_question_ids'];
			}else{
				$this->excludeQuestionIds = array();
			}
		}
	}
	
	private function sanitizeSearchKeyword($keyword){
		$sanitizedWord = $keyword;
		$sanitizedWord = str_replace("+", " ",  $sanitizedWord);
		$sanitizedWord = trim($sanitizedWord);
		return $sanitizedWord;
	}
	
	private function getBoostingStringForNonSeparableQuery($query_full, $boost2a1, $boost2a2, $boost2b1){
		$qString = '&boost=if(exists(question_objective),if(exists($exqq2a1),'.$boost2a1.',1),1)&exqq2a1={!dismax qf=question_objective pf=question_objective bucket=false deftype=edismax tie=1 ps=10 mm=1}'.urlencode($query_full).'&boost=if(not(exists(question_objective)),if(exists($exqq2b1),'.$boost2b1.',1),1)&exqq2b1={!dismax qf=question_title pf=question_title bucket=false deftype=edismax tie=1 ps=10 mm=1}'.urlencode($query_full).'&';
		/*
		   $qString = '&boost=if(and(exists(question_objective),exists(question_background)),if(exists($exqq2a1),'.$boost2a1.',1),1)&exqq2a1={!dismax qf=question_objective pf=question_objective bucket=false deftype=edismax tie=1 ps=10 mm=1}'.$query_full.'&boost=if(and(exists(question_objective),exists(question_background)),if(exists($exqq2a2),'.$boost2a2.',1),1)&exqq2a2={!dismax qf=question_background pf=question_background bucket=false deftype=edismax tie=1 ps=10 mm=1}'.$query_full.'&boost=if(not(exists(question_objective)),if(exists($exqq2b1),'.$boost2b1.',1),1)&exqq2b1={!dismax qf=question_title pf=question_title bucket=false deftype=edismax tie=1 ps=10 mm=1}'.$query_full.'&';
		 */
		/*
		   $qString = '&boost=if(and(exists(question_objective),exists(question_background)),if(exists($exqq2a1),'.$boost2a1.
		   ',1),1)&exqq2a1={!dismax qf=question_objective}'
		   .$query_full.'&boost=if(and(exists(question_objective),exists(question_background)),if(exists($exqq2a2),'.$boost2a2.
		   ',1),1)&exqq2a2={!dismax qf=question_background}'.$query_full.
		   '&boost=if(not(and(exists(question_objective),exists(question_background))),if(exists($exqq2b1),'
		   .$boost2b1.',1),1)&exqq2b1={!dismax qf=question_title}'.$query_full.'&';
		 */
		return $qString;
	}

	private function getDiscussionBoostingStringForNonSeparableQuery($query_full, $boost2a1, $boost2a2, $boost2b1){

		$qString = '&boost=if(exists(discussion_title),if(exists($exqq2a1),'.$boost2a1.',1),1)&exqq2a1={!dismax qf=discussion_title pf=discussion_title bucket=false deftype=edismax tie=1 ps=10 mm=1}'.urlencode($query_full).'&';

		return $qString;
	}

	private function getBoostingStringForSeparableQuery($query_obj,$query_bck,$query_full, $boost1a1, $boost1a2, $boost1a3, $boost1b1, $boost1b2){
		$qString='&boost=if(and(exists(question_objective),exists(question_background)),if(exists($exqq1a1),'.$boost1a1.',1),1)&exqq1a1={!dismax qf=question_objective pf=question_objective bucket=false deftype=edismax tie=1 ps=10 mm=1}'
			.urlencode($query_obj).'&boost=if(and(exists(question_objective),exists(question_background)),if(exists($exqq1a2),'.$boost1a2.
						',1),1)&exqq1a2={!dismax qf=question_background pf=question_background bucket=false deftype=edismax tie=1 ps=10 mm=1}'.urlencode($query_bck).'&boost=if(and(exists(question_objective),exists(question_background)),if(exists($exqq1a3),'.$boost1a3.',1),1)&exqq1a3={!dismax qf=question_title pf=question_title bucket=false deftype=edismax tie=1 ps=10 mm=1}'.urlencode($query_full).
			'&boost=if(not(and(exists(question_objective),exists(question_background))),if(exists($exqq1b1),'.$boost1b1.',1),1)&exqq1b1={!dismax qf=question_title pf=question_title bucket=false deftype=edismax tie=1 ps=10 mm=1}'.
			urlencode($query_obj).'&boost=if(not(and(exists(question_objective),exists(question_background))),if(exists($exqq1b2),'.$boost1b2.
						',1),1)&exqq1b2={!dismax qf=question_title pf=question_title bucket=false deftype=edismax tie=1 ps=10 mm=1}'.urlencode($query_bck).'&';

		return $qString;
	}

	private function getDiscussionBoostingStringForSeparableQuery($query_obj,$query_bck,$query_full, $boost1a1, $boost1a2, $boost1a3, $boost1b1, $boost1b2){
		$qString='&boost=if(and(exists(discussion_title),exists(question_background)),if(exists($exqq1a1),'.$boost1a1.',1),1)&exqq1a1={!dismax qf=discussion_title pf=discussion_title bucket=false deftype=edismax tie=1 ps=10 mm=1}'
			.urlencode($query_obj).'&boost=if(and(exists(discussion_title),exists(question_background)),if(exists($exqq1a2),'.$boost1a2.
						',1),1)&exqq1a2={!dismax qf=question_background pf=question_background bucket=false deftype=edismax tie=1 ps=10 mm=1}'.urlencode($query_bck).'&boost=if(and(exists(discussion_title),exists(question_background)),100,1)&';

		return $qString;
	}
}

