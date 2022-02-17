<?php

class SearchCommon {
	
	private $_ci;
	
	public function __construct(){
		$this->_ci = & get_instance();
		$this->_ci->load->builder('SearchBuilder', 'search');
		$this->_ci->load->builder("LocationBuilder", "location");
		$this->_ci->load->helper('search/SearchUtility');
		$this->_ci->load->model('search/SearchModel', '', true);
		$this->_ci->config->load('search_config');
		
		$this->config = $this->_ci->config;
	}
	
	
	public function readSearchParams($type = 'SEARCH'){
		$paramsArray = array();
		switch($type){
			case 'SEARCH':
				$paramsArray = $this->readSearchGetParameters();
				break;
			case 'CMS':
				$paramsArray = $this->readCMSSearchGetParameters();
				break;
		}
		return $paramsArray;
	}
	
	/**
	 * Valid URL Parameters and their meaning For Search
	 *	1. keyword => keyword which user wants to search.
	 *	2. start   => start row number, used for pagination
	 *	3. institute_rows => How many institute type rows you want in search results, If not mentioned searchAPI will pick from search_config file.
	 *	4. content_rows => How many content type rows you want in search results, If not mentioned searchAPI will pick from search_config file.
	 *	5. show_sponsored_results => If sets to true, searchAPI will return sponsored results for the keyword.
	 *	6. show_featured_results => If sets to true, searchAPI will return featured results for the keyword.
	 *	7. search_type => which kind of search is this, if mentioned than no call to QUERace for identifying the search results.
	 * 
	 *
	*/
	public function readSearchGetParameters(){
		$paramArray = array();
		$altKeyWord = $this->_ci->security->xss_clean($_REQUEST['altkeyword']);
		if(isset($altKeyWord) && $altKeyWord != ""){
			$paramArray['keyword']       	= $altKeyWord;
		} else {
			$paramArray['keyword']       	= $this->_ci->security->xss_clean($_REQUEST['keyword']);
		}
		$paramArray['tempKeywordFix'] = $paramArray['keyword'];
		$parts = explode(" ", $paramArray['keyword']);
		$string = "";
		foreach($parts as $part){
			if(!is_numeric($part)){
				$string .= $part . " ";
			}
		}
		$paramArray['keyword'] 			= trim($string);
		$startParam 					= $this->_ci->security->xss_clean($_REQUEST['start']);
		$instituteRowsParam 			= $this->_ci->security->xss_clean($_REQUEST['institute_rows']);
		$contentRowsParam 				= $this->_ci->security->xss_clean($_REQUEST['content_rows']);
		$countryIdParam 				= $this->_ci->security->xss_clean($_REQUEST['country_id']);
		$localityIdParam 				= $this->_ci->security->xss_clean($_REQUEST['locality_id']);
		$zoneIdParam 					= $this->_ci->security->xss_clean($_REQUEST['zone_id']);
		$cityIdParam 					= $this->_ci->security->xss_clean($_REQUEST['city_id']);
		$courseTypeParam 				= $this->_ci->security->xss_clean($_REQUEST['course_type']);
		$courseLevelParam 				= $this->_ci->security->xss_clean($_REQUEST['course_level']);
		$minDurationParam 				= $this->_ci->security->xss_clean($_REQUEST['min_duration']);
		$maxDurationParam 				= $this->_ci->security->xss_clean($_REQUEST['max_duration']);
		$searchTypeParam 				= $this->_ci->security->xss_clean($_REQUEST['search_type']);
		$searchDataTypeParam 			= $this->_ci->security->xss_clean($_REQUEST['search_data_type']);
		$sortTypeParam 					= $this->_ci->security->xss_clean($_REQUEST['sort_type']);
		$searchUniqueInsertIdParam 		= $this->_ci->security->xss_clean($_REQUEST['search_unique_insert_id']);
		$showFeaturedResultsParam 		= $this->_ci->security->xss_clean($_REQUEST['show_featured_results']);
		$showSponsoredResultsParam 		= $this->_ci->security->xss_clean($_REQUEST['show_sponsored_results']);
		$showBannerResultsParam 		= $this->_ci->security->xss_clean($_REQUEST['show_banner_results']);
		$ignoreInstituteIdsParam 		= $this->_ci->security->xss_clean($_REQUEST['ignore_institute_ids']);
		$autosuggestorSuggestionShownParam = $this->_ci->security->xss_clean($_REQUEST['autosuggestor_suggestion_shown']);
		
		$autosuggestorSelectedFilters = trim($this->_ci->security->xss_clean($_REQUEST['autosuggest_selected_filters']));
		$autosuggestorSelectedFilterValues = trim($this->_ci->security->xss_clean($_REQUEST['autosuggest_selected_filter_values']));
		$autosuggestFilters = array();
		if($autosuggestorSelectedFilters && $autosuggestorSelectedFilterValues) {
			$autosuggestorSelectedFilters = explode('_$$_',$autosuggestorSelectedFilters);
			$autosuggestorSelectedFilterValues = explode('_$$_',$autosuggestorSelectedFilterValues);
			
			for($i=0;$i<count($autosuggestorSelectedFilters);$i++) {
				if($autosuggestorSelectedFilters[$i] && $autosuggestorSelectedFilterValues[$i]) {
					$autosuggestFilters[$autosuggestorSelectedFilters[$i]] = $autosuggestorSelectedFilterValues[$i];
				}
			}

            if(!$autosuggestFilters['institute_title_facet'] || strpos($autosuggestFilters['institute_title_facet'],'&')) {
                $autosuggestFilters = array();
            }
		}
		
		$identifiedKeywordPart = implode(" ",$autosuggestFilters);
		$unidentifiedKeywordPart = trim(substr($paramArray['keyword'], strlen($identifiedKeywordPart)));
		
		$universityOffsetParam = $this->_ci->security->xss_clean($_REQUEST['university_result_offset']);
		$universityCount = $this->_ci->security->xss_clean($_REQUEST['university_result_rows']);
		$courseOffsetParam = $this->_ci->security->xss_clean($_REQUEST['course_result_offset']);
		$courseCount = $this->_ci->security->xss_clean($_REQUEST['course_result_rows']);
		$previousRowCount = $this->_ci->security->xss_clean($_REQUEST['previousRowCount']);
		$uid = $this->_ci->security->xss_clean($_REQUEST['uid']);
		
		$paramArray['start']         				= (isset($startParam) && $startParam != '' && is_numeric($startParam)) ? $startParam : 0;
		$paramArray['institute_rows']   			= (isset($instituteRowsParam) && $instituteRowsParam != '' && is_numeric($instituteRowsParam)) ? $instituteRowsParam : -1;
		$paramArray['content_rows']    				= (isset($contentRowsParam) && $contentRowsParam != '' && is_numeric($contentRowsParam)) ? $contentRowsParam : -1;
        $paramArray['country_id']    				= (isset($countryIdParam) && $countryIdParam != '' && $countryIdParam != '-1' && is_numeric($countryIdParam)) ? $countryIdParam : '';
		$paramArray['locality_id']    				= (isset($localityIdParam) && $localityIdParam != '' && $localityIdParam != '-1' && is_numeric($localityIdParam)) ? $localityIdParam : '';
		$paramArray['zone_id']    					= (isset($zoneIdParam) && $zoneIdParam != '' && $zoneIdParam != '-1' && is_numeric($zoneIdParam)) ? $zoneIdParam : '';
		$paramArray['city_id']       				= (isset($cityIdParam) && $cityIdParam != '-1' && $cityIdParam != '0' )? $cityIdParam : '';
		$paramArray['course_type']   				= (isset($courseTypeParam) && $courseTypeParam != '-1' && $courseTypeParam != '0' )? $courseTypeParam : '';
		$paramArray['course_level']  				= (isset($courseLevelParam) && $courseLevelParam != '-1' && $courseLevelParam != '0' )? $courseLevelParam : '';
		$paramArray['min_duration']  				= (isset($minDurationParam) && $minDurationParam != '-1' && $minDurationParam != '0' )? $minDurationParam : '';
		$paramArray['max_duration']  				= (isset($maxDurationParam) && $maxDurationParam != '-1' && $maxDurationParam != '0' )? $maxDurationParam : '';
        $paramArray['search_type']   				= (isset($searchTypeParam) && $searchTypeParam != '')? $searchTypeParam : '';
		$paramArray['search_data_type'] 			= (isset($searchDataTypeParam) && $searchDataTypeParam != '' ) ?  $searchDataTypeParam : '';
		$paramArray['sort_type'] 					= (isset($sortTypeParam) && $sortTypeParam != '' ) ?  $sortTypeParam : 'best';
		$paramArray['search_unique_insert_id'] 		= (isset($searchUniqueInsertIdParam) && $searchUniqueInsertIdParam != '' ) ?  $searchUniqueInsertIdParam : '';
		$paramArray['show_featured_results'] 		= (isset($showFeaturedResultsParam) && $showFeaturedResultsParam != '' ) ?  $showFeaturedResultsParam : true;
		$paramArray['show_sponsored_results'] 		= (isset($showSponsoredResultsParam) && $showSponsoredResultsParam != '' ) ?  $showSponsoredResultsParam : true;
		$paramArray['show_banner_results'] 			= (isset($showBannerResultsParam) && $showBannerResultsParam != '' ) ?  $showBannerResultsParam : true;
		$paramArray['ignore_institute_ids'] 		= (isset($ignoreInstituteIdsParam) && $ignoreInstituteIdsParam != '' ) ?  $ignoreInstituteIdsParam : '';
		$paramArray['autosuggestor_suggestion_shown'] = (isset($autosuggestorSuggestionShownParam) && $autosuggestorSuggestionShownParam != '' ) ?  $autosuggestorSuggestionShownParam : '-1';
		$paramArray['university_result_offset'] = (isset($universityOffsetParam) && $universityOffsetParam != '')? $universityOffsetParam : '';
		$paramArray['university_result_rows'] = (isset($universityCount) && $universityCount != '')? $universityCount : '';                
		$paramArray['course_result_offset'] = (isset($courseOffsetParam) && $courseOffsetParam != '')? $courseOffsetParam : '';
		$paramArray['course_result_rows'] = (isset($courseCount) && $courseCount != '')? $courseCount : '';   
        $paramArray['previousRowCount'] = (isset($previousRowCount) && $previousRowCount != '')? $previousRowCount : '';   
        $paramArray['uid'] = (isset($uid) && $uid != '')? $uid : '';
		$paramArray['autosuggest_filters'] = $autosuggestFilters;
		$paramArray['unidentifiedKeywordPart'] = $unidentifiedKeywordPart;
		
		return $paramArray;
	}
	
	public function readCMSSearchGetParameters(){
		$paramArray = array();
		
		$keywordParam 					= $this->_ci->security->xss_clean($_REQUEST['keyword']);
		$startParam 					= $this->_ci->security->xss_clean($_REQUEST['start']);
		$typeParam 						= $this->_ci->security->xss_clean($_REQUEST['type']);
		$sponsoredParam 				= $this->_ci->security->xss_clean($_REQUEST['sponsored']);
		$featuredParam 					= $this->_ci->security->xss_clean($_REQUEST['featured']);
		$searchTypeParam 				= $this->_ci->security->xss_clean($_REQUEST['searchType']);
		$userIdParam 					= $this->_ci->security->xss_clean($_REQUEST['userId']);
		$instituteRowsParam 			= $this->_ci->security->xss_clean($_REQUEST['institute_rows']);
		
		$paramArray['keyword']       	= $keywordParam;
		$paramArray['start']   			= (isset($startParam) && $startParam != '-1') ? $startParam : 0;
		$paramArray['type']   			= (isset($typeParam) && $typeParam != '-1') ? $typeParam : '';
		$paramArray['sponsored']   		= (isset($sponsoredParam) && $sponsoredParam != '-1') ? $sponsoredParam : 0;
		$paramArray['featured']   		= (isset($featuredParam) && $featuredParam != '-1') ? $featuredParam : 0;
		$paramArray['search_type']   	= (isset($searchTypeParam) && $searchTypeParam != '-1') ? $searchTypeParam : '';
		$paramArray['search_data_type'] = 'institute';
		$paramArray['userId']   		= (isset($userIdParam) && $userIdParam != '-1') ? $userIdParam : '';
		$paramArray['institute_rows']   = (isset($instituteRowsParam) && $instituteRowsParam != '' && is_numeric($instituteRowsParam)) ? $instituteRowsParam : -1;
		return $paramArray;
	}
	
	public function getListingIdsFromSearchResults($params = array()){
		$listingIdsList = array();
		$instituteIdsList = array();
		$courseIdsList = array();
		$articleIdsList = array();
		$questionIdsList = array();
		$discussionIdsList = array();
		if(is_array($params) && !empty($params)){
			if(array_key_exists('institute', $params)){ 
				$instituteResults = $params['institute'];
				foreach($instituteResults as $instituteId => $result){ //Extract institute ids from institute search data
					if(!in_array($instituteId, $instituteIdsList)){
						$instituteIdsList[] = $instituteId;
					}
					foreach($result['data'] as $document){ // extract courseid from each document
						if(!in_array($document['course_id'], $courseIdsList)){
							$courseIdsList[] = $document['course_id'];
						}
					}
				}
			}
			
			if(array_key_exists('content', $params)){ //Check for the content data list in search results
				$contentResults = $params['content'];
				foreach($contentResults['data'] as  $document){
					switch($document['facetype']){
						case 'question': // if document is of question type
							if(!in_array($document['question_id'], $questionIdsList)){ 
								$questionIdsList[] = $document['question_id'];
							}
							break;
						case 'article': // if document is of article type
							if(!in_array($document['article_id'], $articleIdsList)){ 
								$articleIdsList[] = $document['article_id'];
							}
							break;
						case 'discussion': // if document is of discussion type
							if(!in_array($document['discussion_id'], $discussionIdsList)){
								$discussionIdsList[] = $document['discussion_id'];
							}
							break;
					}
				}
			}
		}
		
		$listingIdsList = array(
							'institute_ids' 	=> $instituteIdsList,
							'course_ids' 		=> $courseIdsList,
							'question_ids' 		=> $questionIdsList,
							'article_ids' 		=> $articleIdsList,
							'discussion_ids' 	=> $discussionIdsList
							);
		
		return $listingIdsList;
	}
	
	public function getListingIdsFromBannerFeaturedResults($results = array()){
		$courseIds = array();
		foreach($results as $instituteId => $courseData){
			foreach($courseData as $courseList){
				foreach($courseList as $course){
					array_push($courseIds, $course['course_id']);
				}
			}
		}
		return $courseIds;
	}
	
	/**
	 * @method  increaseSearchSnippetCount : This method takes an ids array with key as listing type and value as an ids array,
	 * and generated sql string for insert and calls searchmodel to store data
	 * @param array $params : Params array is multidimension array with key as listing type e.g institute_ids, question_ids etc and values are
	 * array of ids
	 * @return  : Its a delayed mysql insert so it returns nothing
	   @example  params array:
	 * Array (
			[institute_ids] => array(
								[0] => 29786
								[1] => 33908
								...
								...
								),
			[question_ids] => array(
								[0] => 34521
								[1] => 56123
								...
								...
								),
            ....
            ....
        )
    */
	public function increaseSearchSnippetCount($params = array()){
		$valueSqlString = "";
		if(is_array($params) && !empty($params)){
			foreach($params as $key => $data){
				switch($key){
					case 'institute_ids':
						foreach($data as $value){
							$valueSqlString .= " ('institute' , '".$value."') , ";
						}
						break;
					
					case 'course_ids':
						foreach($data as $value){
							$valueSqlString .= " ('course' , '".$value."') , ";
						}
						break;
					
					case 'article_ids':
						foreach($data as $value){
							$valueSqlString .= " ('blog' , '".$value."') , ";
						}
						break;
					
					case 'question_ids':
						foreach($data as $value){
							$valueSqlString .= " ('question' , '".$value."') , ";
						}
						break;
				}
			}
		}
		$valueSqlString = trim($valueSqlString, ", \t\n");
		if(strlen($valueSqlString) > 0){
			$searchModel = new SearchModel();
			//$searchModel->increaseSearchSnippetCount($valueSqlString);	
		}
	}
	
	function getFeaturedListingResults($keyword = "", $location = "") {
		if(!isset($keyword) || trim($keyword) == ""){
			return array();
		}
		$searchModel = new SearchModel();
		$featuredListings = $searchModel->getFeaturedListings($keyword, $location);
		return $featuredListings;
	}
	
	public function getMainCategoryList(){
		$categoryClient = new Category_list_client();
		$subCategoryList = $categoryClient->getSubCategories(1,1);
		$categoryList = array();
		foreach($subCategoryList as $category){
			$categoryList[$category['boardId']] = $category;
		}
		return $categoryList;
	}
	
	/**
	 * @method array getMaxRowsCount: This function gets the rows defined in search config for each type of results
	 * based on search query type
	 * @param String $searchType : search query type
	 * @return array: array with max rows of type.
	*/
	public function getMaxRowsCount($searchType, $params = array()){
		//search source is from where the user is searching, i.e main search page, static results page or enterprise side.
		$searchSource = $params['search_source'];
		if($searchType == "institute"){
			switch($searchSource){
				case 'SEARCH':
				case 'STATIC_SEARCH':
					$maxInstituteTypeResult = $this->config->item('institute_rows_in_institute_search');
					$maxContentTypeResult 	= $this->config->item('content_rows_in_institute_search');
					break;
				
				case 'CMS':
					$maxInstituteTypeResult = $this->config->item('cms_institute_rows_in_institute_search');
					$maxContentTypeResult 	= $this->config->item('cms_content_rows_in_institute_search');
					break;
				
				default:
					$maxInstituteTypeResult = $this->config->item('institute_rows_in_institute_search');
					$maxContentTypeResult 	= $this->config->item('content_rows_in_institute_search');
					break;
			}
		} else {
			switch($searchSource){
				case 'SEARCH':
				case 'STATIC_SEARCH':
					$maxInstituteTypeResult = $this->config->item('institute_rows_in_content_search');
					$maxContentTypeResult 	= $this->config->item('content_rows_in_content_search');
					break;
				
				case 'CMS':
					$maxInstituteTypeResult = $this->config->item('cms_institute_rows_in_content_search');
					$maxContentTypeResult = $this->config->item('cms_institute_rows_in_content_search');
					break;
				
				default:
					$maxInstituteTypeResult = $this->config->item('institute_rows_in_content_search');
					$maxContentTypeResult 	= $this->config->item('content_rows_in_content_search');
					break;
			}
		}
		if(isset($params['institute_rows']) && (int)$params['institute_rows'] != -1){
			$maxInstituteTypeResult = (int)$params['institute_rows'];
		}
		if(isset($params['content_rows']) && (int)$params['content_rows'] != -1){
			$maxContentTypeResult = (int)$params['content_rows'];
		}
		$returnArray = array(
							'institute_rows' => $maxInstituteTypeResult,
							'content_rows' 	 => $maxContentTypeResult
							);
		return $returnArray;
	}
	
	public function applyQERLocationSet($solrCourseDocumentList = array(), $locationPropertySet = array()){
		$documents = array();
		$locationPriority = array('course_locality_id', 'course_zone_id', 'course_city_id', 'course_state_id', 'course_country_id');
		$returnResult = $solrCourseDocumentList;
		if(!empty($solrCourseDocumentList)){
			foreach($solrCourseDocumentList as $courseDocument){
				$courseId = $courseDocument['course_id'];
				if(empty($documents[$courseId])){
					$documents[$courseId] = array();
				}
				$documents[$courseId][] = $courseDocument;
			}
			
			$locationKeys = array_keys($locationPropertySet);
			$validDocuments = array();
			if(!empty($documents)){
				foreach($documents as $courseId => $docs){
					$matchFound = false;
					$matchFoundForCourse = false;
					$headOfficeDoc = array();
					foreach($docs as $doc){
						if(!$matchFound){
							$docLocalityId = $doc['course_locality_id'];
							$docZoneId 	   = $doc['course_zone_id'];
							$docStateId    = $doc['course_state_id'];
							$docCityId 	   = $doc['course_city_id'];
							$docCountryId  = $doc['course_country_id'];
							
							$localityIdURLParam = $this->_ci->security->xss_clean('locality_id');
							$zoneIdURLParam 	= $this->_ci->security->xss_clean('zone_id');
							$cityIdURLParam 	= $this->_ci->security->xss_clean('city_id');
							$countryIdURLParam 	= $this->_ci->security->xss_clean('country_id');
							$stateIdURLParam 	= $this->_ci->security->xss_clean('state_id');
							
							$urlLocalityId  = !empty($localityIdURLParam) ? $localityIdURLParam : 0;
							$urlZoneId		= !empty($zoneIdURLParam) ? $zoneIdURLParam : 0;
							$urlCityId 		= !empty($cityIdURLParam) ? $cityIdURLParam : 0;
							$urlCountryId 	= !empty($countryIdURLParam) ? $countryIdURLParam : 0;
							$urlStateId 	= !empty($stateIdURLParam) ? $stateIdURLParam : 0;
							
							if($doc['course_head_office'] == 1){
								$headOfficeDoc = $doc;
							}
							if(in_array('course_locality_id', $locationKeys) || !empty($urlLocalityId)){
								if(in_array($docLocalityId, $locationPropertySet['course_locality_id']) || $docLocalityId == $urlLocalityId){
									$validDocuments[] = $doc;
									$matchFound = true;
									$matchFoundForCourse = true;
								}
							} else if(in_array('course_zone_id', $locationKeys) || !empty($urlZoneId)){
								if(in_array($docZoneId, $locationPropertySet['course_zone_id']) || $docZoneId == $urlZoneId){
									$validDocuments[] = $doc;
									$matchFound = true;
									$matchFoundForCourse = true;
								}
							} else if(in_array('course_city_id', $locationKeys) || !empty($urlCityId)){
								if(in_array($docCityId, $locationPropertySet['course_city_id']) || $docCityId == $urlCityId){
									$validDocuments[] = $doc;
									$matchFound = true;
									$matchFoundForCourse = true;
								}
							} else if(in_array('course_state_id', $locationKeys) || !empty($urlStateId)){
								if(in_array($docStateId, $locationPropertySet['course_state_id']) || $docStateId == $urlStateId){
									$validDocuments[] = $doc;
									$matchFound = true;
									$matchFoundForCourse = true;
								}
							} else if(in_array('course_country_id', $locationKeys) || !empty($urlCountryId)){
								if(in_array($docCountryId, $locationPropertySet['course_country_id']) || $docCountryId == $urlCountryId){
									$validDocuments[] = $doc;
									$matchFound = true;
									$matchFoundForCourse = true;
								}
							}
						} else {
							break;
						}
					}
					
					if(!$matchFoundForCourse && empty($locationPropertySet) && !empty($headOfficeDoc)){
						$validDocuments[] = $headOfficeDoc;
					}
				}
			}
			if(!empty($validDocuments)){
				$returnResult = $validDocuments;	
			}
		}
		return $returnResult;
	}
	
	public function getLocationDetailsPickedByQER($qerPramsValue = array()){
		$qerLocations = array();
		$locationBuilder = new LocationBuilder();
		$locationRepo = $locationBuilder->getLocationRepository();
		if(array_key_exists('course_city_id', $qerPramsValue)){
			$qerLocations['cities'] = array();
			foreach($qerPramsValue['course_city_id'] as $cityId){
				$cityInfo = array();
				$cityInfo = $locationRepo->findCity($cityId);
				if(trim($cityInfo->getName()) != ""){
					$qerLocations['cities'][$cityId] = $cityInfo->getName();	
				}
			}
		}
		if(array_key_exists('course_locality_id', $qerPramsValue)){
			$qerLocations['localities'] = array();
			foreach($qerPramsValue['course_locality_id'] as $localityId){
				$localityInfo = array();
				$localityInfo = $locationRepo->findLocality($localityId);
				if(trim($localityInfo->getName()) != ""){
					$qerLocations['localities'][$localityId] = $localityInfo->getName();	
				}
			}
		}
		if(array_key_exists('course_country_id', $qerPramsValue)){
			$qerLocations['countries'] = array();
			foreach($qerPramsValue['course_country_id'] as $countryId){
				$countryInfo = array();
				$countryInfo = $locationRepo->findCountry($countryId);
				if(trim($countryInfo->getName()) != ""){
					$qerLocations['countries'][$countryId] = $countryInfo->getName();	
				}
			}
		}
		if(array_key_exists('course_zone_id', $qerPramsValue)){
			$qerLocations['zones'] = array();
			foreach($qerPramsValue['course_zone_id'] as $zoneId){
				$zoneInfo = array();
				$zoneInfo = $locationRepo->findZone($zoneId);
				if(trim($zoneInfo->getName()) != ""){
					$qerLocations['zones'][$zoneId] = $zoneInfo->getName();	
				}
			}
		}
		return $qerLocations;
	}
	
	public function getSearchListingIdsByType($keyword, $type, $start = 0, $rows = 50) {
		$searchWrapper = SearchBuilder::getSearchWrapper();
		$returnResults = $searchWrapper->getSearchListingIdsByType($keyword, $type, $start, $rows);
		return $returnResults;
	}
	public function readQuestionSearchParameters($questionText,$count, $excludeQuestionIds = array()){
		$paramArray = array();
		$paramArray['keyword'] = urldecode($questionText);	
		$paramArray['start']   =  0;
		$paramArray['institute_rows'] = -1;
		$paramArray['content_rows'] = is_numeric($count) ?  $count : 0; 
		$paramArray['country_id']   = '';
		$paramArray['locality_id'] = '';
		$paramArray['zone_id']  = '';
		$paramArray['city_id']  = '';
		$paramArray['course_type'] = '';
		$paramArray['course_level'] ='';
		$paramArray['min_duration']  = '';
		$paramArray['max_duration']  = '';
		$paramArray['search_type']   = '';
		$paramArray['search_data_type'] = 'question';
		$paramArray['sort_type'] = 'best';
		$paramArray['search_unique_insert_id'] = '';
		$paramArray['show_featured_results'] = true;
		$paramArray['show_sponsored_results'] = true; //should be false
		$paramArray['show_banner_results'] 	 = true;
		$paramArray['ignore_institute_ids'] = '';
		$paramArray['autosuggestor_suggestion_shown'] = '-1';
		$paramArray['show_only_master_list_questions'] = '1';
		$paramArray['exclude_question_ids'] = $excludeQuestionIds;
		return $paramArray;
	}
	
	/** 
	 * Fucniotn which get the ajax url and returns associative array
	 * @author : Rahul
	 */
	public function getSearchParamsFromAjaxUrl($url) {
		
		$returnParamArray = array();
		$urlArray = explode('&',$url);
		
		foreach ($urlArray as $index => $val) {
			$keyWordArray = explode("=", $val);
			$returnParamArray[$keyWordArray[0]] = $keyWordArray[1]; 
		}
		
		return $returnParamArray;
	}
	
	public function getVirtualCityMappingForSearch() {
		$this->_ci->load->library('listing/cache/ListingCache');
		$listingCache 			= new ListingCache();
		$virtualCityMappingData = $listingCache->getVirtualCityMappingForSearch();
		if(empty($virtualCityMappingData)) {
			$searchModel  		= new SearchModel();
			$virtualCityMappingData = $searchModel->getVirtualCityMappingForSearch();
			if(!empty($virtualCityMappingData)) {
				$listingCache->storeVirtualCityMappingForSearch($virtualCityMappingData);
			}
		}
		return $virtualCityMappingData;
	}

	public function getTagQualityFactor($tagid){

		$searchModel = new SearchModel();

		// get follows
		$followCount = $searchModel->getTagFollowCount($tagid);

		// get discussions and questions
		$contentMappingCount = $searchModel->getTagContentMappingCount($tagid);
		$questionCount = intval($contentMappingCount['question']);
		$discussionCount = intval($contentMappingCount['discussion']);
		$boost_params = $this->config->item('tag_quality_factor_boost_params');

		$quality_factor = 0;
		$quality_factor += $followCount*$boost_params['follow'];
		$quality_factor += $questionCount*$boost_params['questions'];
		$quality_factor += $discussionCount*$boost_params['discussions'];

		// normalize the quality factor
		$qualityFactorNormalizationFactor = 1000;
		$quality_factor = $quality_factor/$qualityFactorNormalizationFactor;
		$quality_factor = $quality_factor > 1 ? 1 : $quality_factor;
 
		return $quality_factor;
	}
}

