<?php

/**
 * Class: SearchWrapper
 * @author: pankaj meena pankaj.meena@shiksha.com
 * @purpose:
 * Acts as a bridge b/w SearchRepository and SolrSearcher. The main purpose of this class is to process the solr results from SolrSearcher and convert them into usable form which searchRepository can consume.
 * Anytime if we have to move from Solr to some other Search Server, We don't have to change SearchRepository to accomodate those changes, all the changes will be on searchWrapper and SolrSearcher Level.
 * Operations performed by SearchWrapper.
 * 1. Fetching search results for institute/content/question/article etc
 * 2. Finding out the query type
 * 3. Make search results bucket based on quantity defined in config
 * 4. Highlighting Documents.
 * 5. Getting facets, Sort facet values
 * etc
 */
class SearchWrapper {
	
	private $_ci;
	private $searchCommonLib;
	private $searchSponsoredLib;
	
	public function __construct(){
		$this->_ci = & get_instance();
		$this->_ci->load->builder('SearchBuilder', 'search');
		$this->_ci->load->helper('search/SearchUtility');
		$this->_ci->load->builder("LocationBuilder", "location");
		$this->_ci->config->load('search_config');
		$this->_ci->load->model("search/SearchModel", "", true);
		$this->config = $this->_ci->config;
		$this->searchServer = SearchBuilder::getSearchServer($this->config->item('search_server'));
		
		$this->searchCommonLib 	   = SearchBuilder::getSearchCommon();
		$this->searchSponsoredLib  = SearchBuilder::getSearchSponsored();
	}
	
	/**
	 * @method array getSearchResults : This function gets the solr search results from solrSearcher in raw format.
	 * Understand URL params first then fetch search results from solrSearcher API
	 * @param array $params : sanitized URL post parameters from search
	 * @return array: search results.
	 *
	*/
	public function getSearchResults($params = array()){
		//What all type of search results, do searchRepository Needs. Its a comma separated value from url post params.
		//Ex. content,institutes.
		$validDataTypes = $this->getSearchDataTypes($params);
		
		//If valid datatypes has institute in it. Go fetch the institute results.
		$instituteSearchResults = array();
		if(in_array('institute', $validDataTypes) && $params['institute_rows'] != 0 ){
			$instituteSearchResults = $this->getInstituteSearchResults($params);
		}
		//If valid datatypes has content in it. Go fetch the content results.
		$contentSearchResults = array();
		if(in_array('content', $validDataTypes) && $params['content_rows'] != 0 ){
			$contentSearchResults = $this->getContentSearchResults($params);
		}
		
		//Find out the type of search query user has typed and identification step for stats
		$searchTypeIdentificationStep = "";
		if(count($validDataTypes) == 1){
			//We need only one kind of data, than no need for making call to QUERace
			$searchType = $validDataTypes[0]; //This would be default searchType
			$searchTypeIdentificationStep = "SINGLE_DATA_TYPE_IN_CONFIG";
		} else if(isset($params['search_type']) && !empty($params['search_type'])){
			//URL params already has search type in it, so no need to make call to QUERace.
			$validDataTypes = $this->config->item('search_types');
			$searchType = $params['search_type'];
			if(!in_array($searchType, $validDataTypes)){
				$searchType = $validDataTypes[0];
			}
			$searchTypeIdentificationStep = "SINGLE_VALID_DATA_TYPE_FROM_URL";
		} else if($contentSearchResults['general']['numfound_content'] <= 0){
			//We don't have any content type data, so its a institute type search
			$searchType = "institute";
			$searchTypeIdentificationStep = "ZERO_CONTENT_COUNT";
		} else if($instituteSearchResults['general']['numfound_course_documents'] <= 0){
			//We don't have any institute type data, so its a content type search
			$searchType = "content";
			$searchTypeIdentificationStep = "ZERO_COURSE_COUNT";
		} else if(isQERFreeFromQueryParameter($instituteSearchResults['general']['initial_qer_query'])){
			//Second check: We don't want user types in location like mumbai only and we treats it like institute type query
			//Final QER has no q parameter, so ideally QER has identified all the valid information from query string
			$searchType = "institute";
			$searchTypeIdentificationStep = "QER_COMPLETE_IDENTIFICATION";
		} else {
			// Make call to QUE Race for finding out query type
			$contentSearchGeneralResult 	= $contentSearchResults['general'];
			$instituteSearchGeneralResult 	= $instituteSearchResults['general'];
			$totalInstituteCount 			= $instituteSearchGeneralResult['total_institute_groups'];
			$minimumInstituteCountForInstituteSearch = $this->config->item('min_threshhold_for_institute_type_search');
			//If total institutes found in search results are less than minimum threshhold then treat it as a institute type query.
			if($totalInstituteCount <= $minimumInstituteCountForInstituteSearch){
				//We only have minimum set of institute results so its a institute type search query
				$searchType = "institute";
				$searchTypeIdentificationStep = "MIN_THRESHHOLD_INSTITUTE_MATCH";
			} else {
				//At this step we can't decide which type of query it is so make a call to QUERace
				$searchType = $this->getSearchTypeUsingQueRace($contentSearchGeneralResult, $instituteSearchGeneralResult);
				$searchTypeIdentificationStep = "QUERACE";
			}
		}
		
		//Make the final bucket based on no of results we need on page
		$finalResultSet 				= $this->processSearchResults($contentSearchResults, $instituteSearchResults, $params, $searchType);
		$maxRowsCount 					= $this->searchCommonLib->getMaxRowsCount($searchType, $params); //Original row count defined in search config
		$facets 						= $this->getFacets($finalResultSet['facet'], $searchType, $params);
		$finalResultSet['rows_count'] 	= $maxRowsCount;
		$finalResultSet['facets'] 		= $facets;
		$finalResultSet['general_institute']['search_type_identification_step'] = $searchTypeIdentificationStep;
		$finalResultSet['featured_institutes'] 	= $instituteSearchResults['featured_institutes'];
		$finalResultSet['banner_institutes'] 	= $instituteSearchResults['banner_institutes'];
		//$finalResultSet['general_institute']['qer_locations'] = $this->searchCommonLib->getLocationDetailsPickedByQER($finalResultSet['general_institute']['qer_params_value']);
	    
    return $finalResultSet;
	}
        
        
        public function getSearchAbroadResults($params = array()){
                $this->solrSearcher = SearchBuilder::getSearcher($this->config->item('search_server'));
                $studyAbroadResults = $this->solrSearcher->getSolrSASearchResults($params); 
                return $studyAbroadResults;
        }
        
	public function getSearchResultsQuestions($params = array()){
		$validDataTypes = $this->getSearchDataTypes($params);
		//If valid datatypes has content in it. Go fetch the content results.
		$contentSearchResults = array();
		if(in_array('content', $validDataTypes) && $params['content_rows'] != 0 )
		{
			$contentSearchResults = $this->getQuestionSearchResults($params);
		}

		//Find out the type of search query user has typed and identification step for stats		
		//Make the final bucket based on no of results we need on page
		$questionsList = $contentSearchResults['results'];
		$finalResultSet = $contentSearchResults;
		foreach($questionsList as $question)
		{
			$finalResultSet['content'][] = $this->processQuestionSearchResults($question);
		}
		return $finalResultSet;
	}

	public function getSearchResultsDiscussions($params = array()){
		$validDataTypes = $this->getSearchDataTypes($params);
		//If valid datatypes has content in it. Go fetch the content results.
		$contentSearchResults = array();
		if(in_array('content', $validDataTypes) && $params['content_rows'] != 0 )
		{
			$contentSearchResults = $this->getDiscussionSearchResults($params);
		}

		//Find out the type of search query user has typed and identification step for stats		
		//Make the final bucket based on no of results we need on page
		$questionsList = $contentSearchResults['results'];
		$finalResultSet = $contentSearchResults;
		foreach($questionsList as $question)
		{
			$finalResultSet['content'][] = $this->processDiscussionSearchResults($question);
		}
		return $finalResultSet;
	}

	/**
	 * @method array getInstituteSearchResults : This function gets the solr institute search results
	 * Understand URL params first then fetch search results from solrSearcher API
	 * @param array $params : sanitized URL post parameters from search
	 * @return array: institute search results.
	 *
	*/
	private function getInstituteSearchResults($params = array()){
		$this->solrSearcher = SearchBuilder::getSearcher($this->config->item('search_server'));
		$params['search_type'] 	= "course";
		$this->solrSearcher->setSearcherParams($params);
		$courseSearchResults = $this->solrSearcher->getSolrSearchResults("course");
		return $courseSearchResults;
	}
	
	/**
	 * @method array getContentSearchResults : This function gets the solr content search results
	 * Understand URL params first then fetch search results from solrSearcher API.
	 * Content is a combination of question/article/documents
	 * @param array $params : sanitized URL post parameters from search
	 * @return array: content search results.
	 *
	*/
	private function getContentSearchResults($params = array()){ 
		$this->contentSearcher = SearchBuilder::getSearcher($this->config->item('search_server'));
		$params['search_type'] = "content";
		$this->contentSearcher->setSearcherParams($params);
		$contentSearchResults = $this->contentSearcher->getSolrSearchResults("content");
		return $contentSearchResults;
	}
	
	/**
	 * @method array getQuestionSearchResults : This function gets the solr question search results
	 * Understand URL params first then fetch search results from solrSearcher API.
	 * @param array $params : sanitized URL post parameters from search
	 * @return array: question search results.
	 * @status: Currently not in use
	 *
	*/
	private function getQuestionSearchResults($params = array()){
		$this->questionSearcher = SearchBuilder::getSearcher($this->config->item('search_server'));
		$params['search_type'] = "question";
		$this->questionSearcher->setSearcherParams($params);
		$questionSearchResults = $this->questionSearcher->getSolrSearchResults("question");
		return $questionSearchResults;
	}

	private function getDiscussionSearchResults($params = array()){
		$this->questionSearcher = SearchBuilder::getSearcher($this->config->item('search_server'));
		$params['search_type'] = "discussion";
		$this->questionSearcher->setSearcherParams($params);
		$questionSearchResults = $this->questionSearcher->getSolrSearchResults("discussion");
		return $questionSearchResults;
	}
	
	/**
	 * @method array getArticleSearchResults : This function gets the solr article search results
	 * Understand URL params first then fetch search results from solrSearcher API.
	 * @param array $params : sanitized URL post parameters from search
	 * @return array: article search results.
	 * @status: Currently not in use
	*/
	private function getArticleSearchResults($params = array()){
		$this->articleSearcher = SearchBuilder::getSearcher($this->config->item('search_server'));
		$params['search_type'] = "article";
		$this->articleSearcher->setSearcherParams($params);
		$articleSearchResults = $this->articleSearcher->getSolrSearchResults("article");
		return $articleSearchResults;
	}
	
	public function getSearchListingIdsByType($keyword, $type, $start = 0, $rows = 50) {
		$this->solrSearcher = SearchBuilder::getSearcher($this->config->item('search_server'));
		$params = array();
		$params['keyword'] 		= $keyword;
		$params['search_type'] 	= $type;
		if($type == "course" || $type == "institute"){
			$params['search_type'] 	= "course";
		}
		$params['start'] 		= $start;
		$params['rows'] 		= $rows;
		$this->solrSearcher->setSearcherParams($params);
		$results = $this->solrSearcher->getSearchListingIdsByType($keyword, $type);
		return $results;
	}
	
	/**
	 * @method array processSearchResults : Based on query type this method process search results and create content/institute searchresults buckets
	 * Understand URL params first then fetch search results from solrSearcher API.
	 * @param array $contentSearchResults: Content type search results
	 * @param array $instituteSearchResults: Institute type search results
	 * @param array $params : sanitized URL post parameters from search
	 * @param String $searchType : searchType identified by QUERace.
	*/
	private function processSearchResults($contentSearchResults = array(), $instituteSearchResults = array(), $params = array(), $searchType = "institute"){
		$finalResultSet = array();
		$sponsoredResultIds = array();
		if(array_key_exists("sponsored_ids", $instituteSearchResults['general'])){
			$sponsoredResultIds = (array)$instituteSearchResults['general']['sponsored_ids'];
		}
		
		$maxRowsCount = $this->searchCommonLib->getMaxRowsCount($searchType, $params);
		$maxInstituteTypeResult = $maxRowsCount['institute_rows'];
		$maxContentTypeResult = $maxRowsCount['content_rows'];
		$maxInstituteTypeResult = $maxInstituteTypeResult - count($sponsoredResultIds);
		
		$actualCourseResultCount = count($instituteSearchResults['results']);
		$courseResultCount = $actualCourseResultCount;
		if($instituteSearchResults['general']['single_result'] == 1){
			$courseResultCount = 1;
		}
		$contentResultCount = count($contentSearchResults['results']);
		//_p("cont: " . count($instituteSearchResults['results']));
		//_p("maxInstituteTypeResult: " . $maxInstituteTypeResult);
		//_p("maxContentTypeResult: " . $maxContentTypeResult);
		//_p("contentResultCount: " . $contentResultCount);
		//_p("courseResultCount: " . $courseResultCount);
		
		$documentsFromInstituteBucket = $courseResultCount;
		if($courseResultCount > $maxInstituteTypeResult){
			$documentsFromInstituteBucket = $maxInstituteTypeResult;
		}
		$instituteDifference = $documentsFromInstituteBucket - $maxInstituteTypeResult;
		
		$documentsFromContentBucket = $contentResultCount;
		if($contentResultCount > $maxContentTypeResult){
			$documentsFromContentBucket = $maxContentTypeResult;
		}
		$contentDifference = $documentsFromContentBucket - $maxContentTypeResult;
		
		if($contentDifference < 0 &&  $instituteDifference >= 0 || $contentDifference >= 0 &&  $instituteDifference < 0){
			if($contentDifference < 0){
				$documentsFromInstituteBucket = $documentsFromInstituteBucket - $contentDifference;
				if($documentsFromInstituteBucket > $courseResultCount){
					$documentsFromInstituteBucket = $courseResultCount;
				}
			}
			if($instituteDifference < 0){
				$documentsFromContentBucket = $documentsFromContentBucket - $instituteDifference;
				if($documentsFromContentBucket > $contentResultCount){
					$documentsFromContentBucket = $contentResultCount;
				}
			}
		}
		//_p("documentsFromInstituteBucket: " . $documentsFromInstituteBucket);
		//_p("documentsFromContentBucket: " . $documentsFromContentBucket);
		//_p("actualCourseResultCount : " . $actualCourseResultCount);
		
		$instituteResults = array();
		$limit = $documentsFromInstituteBucket;
		if($instituteSearchResults['general']['single_result'] == 1){
			$limit = $actualCourseResultCount;
		}
		
		$count = 0;
		foreach($instituteSearchResults['results'] as $searchResult){
			if($count < $limit){
				$instituteId = $searchResult['groupValue'];
				$documentListUpdated = array();
				foreach($searchResult['doclist']['docs'] as $document){ // loop through each document
					$document = $this->highlightDocumentFields($document, $instituteSearchResults['highlight']); // replace normal content with highlighted content
					switch($document['facetype']){
						case 'course':
							$document = $this->processCourseSearchResults($document);// This will add extra information from DB to document
							break;
					}
					$documentListUpdated[] = $document; // append new updated document in list
				}
				$instituteResults[$instituteId]['data'] = $documentListUpdated;
				$instituteResults[$instituteId]['general'] = array();
				$count++;
			}
		}
		
		//Append if any sponsored institutes available
		foreach($instituteSearchResults['sponsored_results'] as $searchResult){
			$instituteId = $searchResult['groupValue'];
			$documentListUpdated = array();
			foreach($searchResult['doclist']['docs'] as $document){ // loop through each document
				$document = $this->highlightDocumentFields($document, $instituteSearchResults['highlight']); // replace normal content with highlighted content 
				$documentListUpdated[] = $document; // append new updated document in list
			}
			$instituteResults[$instituteId]['data'] = $documentListUpdated;
			$instituteResults[$instituteId]['general'] = array();
		}
		
		$contentResults = array();
		$documentListUpdated = array();
		for($count = 0; $count < $documentsFromContentBucket; $count++){
			$searchResult = $contentSearchResults['results'][$count];
			$searchResult = $this->highlightDocumentFields($searchResult, $contentSearchResults['highlight']); // replace normal content with highlighted content
			switch($searchResult['facetype']){
				case 'article':
					$searchResult = $this->processArticleSearchResults($searchResult);// This will add extra information like view count/comment from DB to document
					break;
				
				case 'question':
					$searchResult = $this->processQuestionSearchResults($searchResult);// This will add extra information like view count/comment, best answer etc from DB to document
					break;
				
				case 'discussion':
					$searchResult = $this->processDiscussionSearchResults($searchResult, $contentSearchResults['highlight']);// This will add extra information like view count/comment, best answer etc from DB to document
					break;
			}
			$documentListUpdated[] = $searchResult;
		}
		
		$contentResults['data'] = $documentListUpdated;
		$finalResultSet = array(
							'institute' 				=> $instituteResults,
							'content' 					=> $contentResults,
							'sponsored_institute_ids' 	=> $sponsoredResultIds,
							'facet' 					=> $instituteSearchResults['facet'],
							'general_institute' 		=> $instituteSearchResults['general'],
							'general_content' 			=> $contentSearchResults['general'],
							'search_type' 				=> $searchType
						);
		return $finalResultSet;
	}
	
	/**
	 * @method array highlightDocumentFields : This function highlights the text in document fields
	 * @param array $document : document array
	 * @param array $highlight : highlighted array from solr
	 * @return array $document: Updated document with highlighted fields
	 *
	*/
	private function highlightDocumentFields($document, $highLightList){
		$documentUniqueId = $document['unique_id'];
		$rawResults = array();
		if(is_array($highLightList) && !empty($highLightList)){ // If highlight array present
			foreach($highLightList as $highlightUniqueId => $contentArr){ // loop through highlight elements
				if($documentUniqueId == $highlightUniqueId){ // if highlight element key is same as unique id
					$highLightKeys = array_keys($contentArr); // get all fields from highlight array of unique id
					foreach($highLightKeys as $highLightKey){
						$document[$highLightKey] = $contentArr[$highLightKey][0]; // replace document fields with highlight elements
					}
				}
			}
		}
		return $document;
	}
	
	/**
	 * @method array getSearchDataTypes : This function finds out what type of search results do we need.
	 * @param array $params : sanitized URL post parameters from search
	 * @return array: search data types.
	 * Possible values:
	 * 1. content
	 * 2. institute
	 * 3. article etc
	 *
	*/
	private function getSearchDataTypes($params = array()){
		//Default search datatypes defined in search config
		$validSearchDataTypes = $this->config->item('search_types');
		$searchDataType = array();
		if(array_key_exists('search_data_type', $params)){
			$dataTypes = explode(",", $params['search_data_type']);
			foreach($dataTypes as $type){
				if(trim($type) != '' && in_array(trim($type), $validSearchDataTypes)){
					$searchDataType[] = trim($type);
				}
			}
		}
		if(count($searchDataType) > 0){
			return $searchDataType;
		} else {
			return $validSearchDataTypes;
		}
	}
	
	/**
	 * @method array getSearchTypeUsingQueRace: This function make a curl call to QUERace server and find outs
	 * the type of query user has typed in.
	 * @param array $contentGeneralResult : content general results 
	 * @param array $courseGeneralResult : courseGeneralResult results
	 * General results only contain information like no of documents, solr query URL, groupby information etc
	 * @return string: Type of query
	 * Possible Values
	 * 1. institute
	 * 2. content
	*/
	private function getSearchTypeUsingQueRace($contentGeneralResult = array(), $courseGeneralResult = array()){
		$qerQueryString = "";
		$totalQuestions 		= 0;
		$totalDiscussions 		= 0;
		$totalArticles 			= 0;
		$totalCourseDocuments 	= 0;
		$totalInstituteCount    = 0;
		$searchKeyword  		= trim($courseGeneralResult['raw_keyword']);
		if(array_key_exists('total_institute_groups', $courseGeneralResult)){
			$total_institute_groups = $courseGeneralResult['total_institute_groups'];
		}
		if(array_key_exists('final_qer_query', $courseGeneralResult)){
			$qerQueryString = $courseGeneralResult['final_qer_query'];
		}
		if(array_key_exists('numfound_course_documents', $courseGeneralResult)){
			$totalCourseDocuments = $courseGeneralResult['numfound_course_documents'];
		}
		if(array_key_exists('numfound_question', $contentGeneralResult)){
			$totalQuestions = $contentGeneralResult['numfound_question'];
		}
		if(array_key_exists('numfound_discussion', $contentGeneralResult)){
			$totalDiscussions = $contentGeneralResult['numfound_discussion'];
		}
		if(array_key_exists('numfound_article', $contentGeneralResult)){
			$totalArticles = $contentGeneralResult['numfound_article'];
		}
		$totalQuestions = $totalQuestions + $totalDiscussions;
		
		//QUERace URl defined in search config
		$queRaceURL = $this->config->item('querace_url');
		$queRaceURL = trim($queRaceURL, '?');
		$solrDocumentCountString = "article=" .$totalArticles. ",ask=" . $totalQuestions . ",institute=" . $totalCourseDocuments;
		$queRaceURL .= "?inkeyword=" . urlencode($searchKeyword) . "&qer=" . urlencode($qerQueryString) . "&solr=" . urlencode($solrDocumentCountString) . "&output=result&action=submit";
		$curlResponse = $this->searchServer->curl($queRaceURL);
		/* Sample format of curlResponse
		  * {article=0.11427620172450706, ask=0.13942033010370883, institute=0.7463034681717842}
		  * Parse it accordingly
		  *
		*/
		$searchTypeFromQueRace = "institute";
        if(strlen($curlResponse) > 0){
			$curlResponse = trim($curlResponse, "{");
			$curlResponse = trim($curlResponse, "}");
			$explodedResponse = explode("," , $curlResponse);
			if(count($explodedResponse) > 0){
				$updatedExplodeParams = array();
				foreach($explodedResponse as $value){
					if(trim($value) != "" && strlen($value) > 0){
						$updatedExplodeParams[] = $value;
					}
				}
				$keyValueResponse = array();
				foreach($updatedExplodeParams as $params){
					$tempParams = explode("=", $params);
					if(count($tempParams) == 2){
						$keyValueResponse[trim($tempParams[0])] = (float)trim($tempParams[1]);
					}
				}
				arsort($keyValueResponse);
				$key = key($keyValueResponse);
				if($key == "institute"){
					$searchTypeFromQueRace = "institute";
				} else if($key == "article" || $key == "ask"){
					$searchTypeFromQueRace = "content";
				} else {
					$searchTypeFromQueRace = "institute";
				}
			}
        }
		return $searchTypeFromQueRace;
	}
	
	/**
	 * @method array processArticleSearchResults: Process the article results and add other information that we don't keep in solr.
	 * Like
	 * 1. commentCount,
	 * 2. viewCount This function makes a DB call for these details.
	 * @param array $article : article document.
	 * @return array: updated article document.
	*/
	private function processArticleSearchResults($article = array()){
		$searchModel = new SearchModel();
		$articleDetails = $searchModel->getArticleDisplayInformation($article['article_id']);
		$article['article_view_count'] = $articleDetails['viewCount'];
		$article['article_comment_count'] = $articleDetails['commentCount'];
		return $article;
	}
	
	private function processCourseSearchResults($course = array()){
		$searchModel = new SearchModel();
		if(!empty($course['course_id'])){
			if(!empty($course['course_aof_last_date'])){ //Online Application form case
				$externalURL = $searchModel->getOAFormExternalURL($course['course_id']); //check for external URL exist or not
				if(!empty($externalURL)){
					$course['course_aof_externalurl'] = $externalURL;
				}
			}
		}
		return $course;
	}
	
	/**
	 * @method array processQuestionSearchResults: Process the question results and add other information that we don't keep in solr.
	 * Like
	 * 1. commentCount,
	 * 2. answerCount
	 * 3. viewCount This function makes a DB call for these details.
	 * @param array $question : question document.
	 * @return array: updated question document.
	*/
	private function processQuestionSearchResults($question = array()){
		$searchModel = new SearchModel();
		$questionDetails = $searchModel->getQuestionDisplayInformation($question['question_id']);
		$question['question_comment_count'] = $questionDetails['commentCount'];
		$question['question_answer_count'] 	= $questionDetails['answerCount'];
		$question['question_view_count'] 	= $questionDetails['viewCount'];
		$question = array_merge($question, $questionDetails['bestAnswerDetails']);
		return $question;
	}
	
	/**
	 * @method array processDiscussionSearchResults: Process the discussion results and add other information that we don't keep in solr.
	 * Like
	 * 1. discussion Comment,
	 * @param array $question : question document.
	 * @return array: updated question document.
	*/
	private function processDiscussionSearchResults($discussion = array(), $highlightList = array()){
		$selectedIndex = false;
		if(is_array($discussion) && !empty($discussion) && is_array($highlightList) && !empty($highlightList)){
			$documentId = $discussion['unique_id'];
			if(array_key_exists($documentId, $highlightList)){
				$highlight = $highlightList[$documentId];
				$ignoreKeys = array('discussion_title', 'discussion_description');
				$validCommentKeys = array();
				foreach(array_keys($highlight) as $key){
					if(!in_array($key, $ignoreKeys)){
						$split = explode("_", $key);
						$validCommentKeys[] = $split[count($split) - 1];
					}
				}
				
				if(count($validCommentKeys) > 0){
					asort($validCommentKeys);
					$jsonDecodedComments = json_decode(html_entity_decode($discussion['discussion_commments_json']), true);
					$selectedIndex = $validCommentKeys[0];
				}
			}
		}
		
		$searchModel = new SearchModel();
		$commentCount = $searchModel->getDiscussionCommentCount($discussion['discussion_thread_id']);
		$discussion['discussion_comment_count'] = $commentCount;
		if($selectedIndex !== false){
			$discussion['selected_comment_index'] = $selectedIndex;
			$discussion['selected_comment_text'] =  $discussion['discussion_comment_'.$selectedIndex];
		}
		return $discussion;
	}
	
	private function getFacets($facets = array(), $searchType = "institute", $params = array()){
		$facetResults = array();
		$searchSource = "SEARCH";
		if(!empty($params['search_source'])){
			$searchSource = $params['search_source'];
		}
		if($searchType == "institute"){
			switch($searchSource){
				case 'SEARCH':
				case 'STATIC_SEARCH':
					$facetResults = $this->getExtendedFacets($facets);
					break;
				default:
					$facetResults = $this->getExtendedFacets($facets);
					break;
			}
		}
		return $facetResults;
	}
	
	public function getExtendedFacets($facets = array()){
		$facets = (array)$facets;
		$locationCluster = array();
		$locationBuilder = new LocationBuilder();
		$locationRepo = $locationBuilder->getLocationRepository();
		if(array_key_exists('course_location_cluster', $facets)){
			$locationClusterData = $facets['course_location_cluster'];
			foreach($locationClusterData as $path => $value){
				$explodedPath = array();
				$tempPath = explode("/", $path);
				foreach($tempPath as $tempVal){
					if(trim($tempVal) != ""){
						$explodedPath[] = $tempVal;
					}
				}
				$explodedPathCount = count($explodedPath);
				for($count = 0; $count < $explodedPathCount; $count++){
					if($count == 0){
						if(!array_key_exists($explodedPath[0], $locationCluster)){
							$locationCluster[$explodedPath[0]] = array();
							$locationCluster[$explodedPath[0]]['id'] = $explodedPath[0];
							$countryEntity = $locationRepo->findCountry($explodedPath[0]);
							$locationCluster[$explodedPath[0]]['name'] = $countryEntity->getName();
							if($count == $explodedPathCount - 1){
								$locationCluster[$explodedPath[0]]['value'] = $value;
							}
							$locationCluster[$explodedPath[0]]['cities'] = array();
						}
					} /*else if($count == 1){
						if(!is_array($locationCluster[$explodedPath[0]]['states'])){
							$locationCluster[$explodedPath[0]]['states'] = array();
						}
						$locationCluster[$explodedPath[0]]['states'][$explodedPath[1]]['id'] = $explodedPath[1];
						$stateEntity = $locationRepo->findState($explodedPath[1]);
						$locationCluster[$explodedPath[0]]['states'][$explodedPath[1]]['name'] = $stateEntity->getName();
						if($count == $explodedPathCount - 1){
							$locationCluster[$explodedPath[0]]['states'][$explodedPath[1]]['value'] = $value;
						}
						if(!is_array($locationCluster[$explodedPath[0]]['states'][$explodedPath[1]])){
							$locationCluster[$explodedPath[0]]['states'][$explodedPath[1]] = array();	
						}
						//uasort($locationCluster[$explodedPath[0]]['cities'], 'locationCmp');
					}*/
					else if($count == 2){
						$cityEntity = $locationRepo->findCity($explodedPath[2]);
						$cityName = $cityEntity->getName();
						if(!empty($cityName)) {
							if(!is_array($locationCluster[$explodedPath[0]]['cities'])){
								$locationCluster[$explodedPath[0]]['cities'] = array();
							}
							if(!is_array($locationCluster[$explodedPath[0]]['cities'][$explodedPath[2]])){
								$locationCluster[$explodedPath[0]]['cities'][$explodedPath[2]] = array();
							}
							
							$locationCluster[$explodedPath[0]]['cities'][$explodedPath[2]]['id'] = $explodedPath[2];
							
							$locationCluster[$explodedPath[0]]['cities'][$explodedPath[2]]['name'] = $cityEntity->getName();
							if($count == $explodedPathCount - 1){
								$locationCluster[$explodedPath[0]]['cities'][$explodedPath[2]]['value'] = $value;
							}
						}
						//uasort($locationCluster[$explodedPath[0]]['cities'], 'locationCmp');
					}
					else if($count == 3){
						$zoneEntity = $locationRepo->findZone($explodedPath[3]);
						$zoneName = $zoneEntity->getName();
						if(!empty($zoneName)) {
							if(!is_array($locationCluster[$explodedPath[0]]['cities'])){
								$locationCluster[$explodedPath[0]]['cities'] = array();
							}
							if(!is_array($locationCluster[$explodedPath[0]]['cities'][$explodedPath[2]])){
								$locationCluster[$explodedPath[0]]['cities'][$explodedPath[2]] = array();	
							}
							if(!is_array($locationCluster[$explodedPath[0]]['cities'][$explodedPath[2]]['zones'])){
								$locationCluster[$explodedPath[0]]['cities'][$explodedPath[2]]['zones'] = array();	
							}
							if(!is_array($locationCluster[$explodedPath[0]]['cities'][$explodedPath[2]]['zones'][$explodedPath[3]])){
								$locationCluster[$explodedPath[0]]['cities'][$explodedPath[2]]['zones'][$explodedPath[3]] = array();	
							}
							$locationCluster[$explodedPath[0]]['cities'][$explodedPath[2]]['zones'][$explodedPath[3]]['id'] = $explodedPath[3];
							
							$locationCluster[$explodedPath[0]]['cities'][$explodedPath[2]]['zones'][$explodedPath[3]]['name'] = $zoneEntity->getName();
							if($count == $explodedPathCount - 1){
								$locationCluster[$explodedPath[0]]['cities'][$explodedPath[2]]['zones'][$explodedPath[3]]['value'] = $value;
							}
						}
					}
					else if($count == 4){
						$localityEntity = $locationRepo->findLocality($explodedPath[4]);
						$localityName = $localityEntity->getName();
						if(!empty($localityName)) {
							if(!is_array($locationCluster[$explodedPath[0]]['cities'])){
								$locationCluster[$explodedPath[0]]['cities'] = array();
							}
							if(!is_array($locationCluster[$explodedPath[0]]['cities'][$explodedPath[2]]['zones'])){
								$locationCluster[$explodedPath[0]]['cities'][$explodedPath[2]]['zones'] = array();
							}
							if(!is_array($locationCluster[$explodedPath[0]]['cities'][$explodedPath[2]]['zones'][$explodedPath[3]]['localities'])){
								$locationCluster[$explodedPath[0]]['cities'][$explodedPath[2]]['zones'][$explodedPath[3]]['localities'] = array();
							}
							if(!is_array($locationCluster[$explodedPath[0]]['cities'][$explodedPath[2]]['zones'][$explodedPath[3]]['localities'][$explodedPath[4]])){
								$locationCluster[$explodedPath[0]]['cities'][$explodedPath[2]]['zones'][$explodedPath[3]]['localities'][$explodedPath[4]] = array();	
							}
							$locationCluster[$explodedPath[0]]['cities'][$explodedPath[2]]['zones'][$explodedPath[3]]['localities'][$explodedPath[4]]['id'] = $explodedPath[4];
							
							$locationCluster[$explodedPath[0]]['cities'][$explodedPath[2]]['zones'][$explodedPath[3]]['localities'][$explodedPath[4]]['name'] = $localityEntity->getName();
							if($count == $explodedPathCount - 1){
								$locationCluster[$explodedPath[0]]['cities'][$explodedPath[2]]['zones'][$explodedPath[3]]['localities'][$explodedPath[4]]['value'] = $value;
							}
						}
					}
				}
			}
			
			//Sort on alphabatical order now
			foreach($locationCluster as $countryId => $countryData){
				uasort($locationCluster[$countryId]['cities'], 'locationCmp');
				foreach($locationCluster[$countryId]['cities'] as $cityId => $cityData){
					uasort($locationCluster[$countryId]['cities'][$cityId]['zones'], 'locationCmp');
					foreach($locationCluster[$countryId]['cities'][$cityId]['zones'] as $zoneId => $zoneData){
						uasort($locationCluster[$countryId]['cities'][$cityId]['zones'][$zoneId]['localities'], 'locationCmp');
					}
				}
			}
		}
		
		if(array_key_exists('course_type_cluster', $facets)){
			$clusters = $facets['course_type_cluster'];
			$courseTypeClusters = $this->getCourseClusters($clusters, "course_type");
		} else {
			$courseTypeClusters = $this->getCourseDefaultCluster("course_type");
		}
		
		if(array_key_exists('course_level_cluster', $facets)){
			$clusters = $facets['course_level_cluster'];
			$courseLevelClusters = $this->getCourseClusters($clusters, "course_level");
		} else {
			$courseLevelClusters = $this->getCourseDefaultCluster("course_level");
		}
		
		$clusters = array (
							'location' 	=> $locationCluster,
							'course_type' => $courseTypeClusters,
							'course_level' => $courseLevelClusters,
						);
		
		return $clusters;
	}
	
	public function getLocationClusters($locationFacets)
	{
		
		//_p($locationFacets);die;
		//$locationFacets = $this->_changeLoc($locationFacets);
		//_p($locationFacets);die;
		
		$locationBuilder = new LocationBuilder();
		$locationRepo = $locationBuilder->getLocationRepository();
		
		$countries = array();
		$states = array();
		$cities = array();
		$zones = array();
		$localities = array();
		
		$statesInCountry = array();
		$citiesInState = array();
		$zonesInCity = array();
		$localitiesInZone = array();
		
		/**
		 * Create Maps
		 */ 
		foreach($locationFacets as $key => $value) {
			$keyParts = explode('/',$key);
			$countryId = intval($keyParts[0]);
			$stateId = intval($keyParts[1]);
			$cityId = intval($keyParts[2]);
			$zoneId = intval($keyParts[3]);
			$localityId = intval($keyParts[4]);
			
			$countries[$countryId] += $value;
			
			if($stateId) {
				$states[$stateId] += $value;
				
				if(!in_array($stateId,$statesInCountry[$countryId])) {
					$statesInCountry[$countryId][] = $stateId;
				}
			}
			
			if($cityId) {
				$cities[$cityId] += $value;
				if(!in_array($cityId,$citiesInState[$stateId])) {
					$citiesInState[$stateId][] = $cityId;
				}
			}
			
			if($zoneId) {
				$zones[$zoneId] += $value;
				if(!in_array($zoneId,$zonesInCity[$cityId])) {
					$zonesInCity[$cityId][] = $zoneId;
				}
			}
			
			if($localityId) {
				$localities[$localityId] += $value;
				if(!in_array($localityId,$localitiesInZone[$zoneId])) {
					$localitiesInZone[$zoneId][] = $localityId;
				}
			}
		}
		
		
		/**
		 * Construct objects for all the entites
		 */
		
		$countryObjs = array();
		foreach($countries as $countryId => $count) {
			$countryObjs[$countryId] = $locationRepo->findCountry($countryId);
		}
		
		$stateObjs = array();
		if(count(array_keys($states)) > 0){
			$stateObjs = $locationRepo->findMultipleStates(array_keys($states));
		}
		
		$cityObjs = array();
		if(count($cities) > 0) {
			$cityObjs = $locationRepo->findMultipleCities(array_keys($cities));
		}
		
		$zoneObjs = array();
		if(count(array_keys($zones))) {
			$zoneObjs = $locationRepo->findMultipleZones(array_keys($zones));
		}
		
		$localityObjs = array();
		if(count(array_keys($localities)) > 0){
			$localityObjs = $locationRepo->findMultipleLocalities(array_keys($localities));
		}
		
		
		/**
		 * Populate into desired format
		 */
		$locationClusters = array();
		foreach($countries as $countryId => $countryCount) {
			$locationClusters[$countryId] = array(
				'id' => $countryId,
				'name' => $countryObjs[$countryId]->getName(),
				'value' => $countryCount
			);
			
			if(count($statesInCountry[$countryId]) > 0) {
				$locationClusters[$countryId]['states'] = array();
				foreach($statesInCountry[$countryId] as $stateId) {
					if(!isset($stateObjs[$stateId])){
						continue;
					}
					$locationClusters[$countryId]['states'][$stateId] = array(
						'id' => $stateId,
						'name' => $stateObjs[$stateId]->getName(),
						'value' => $states[$stateId]
					);
					
					if(count($citiesInState[$stateId]) > 0) {
						$locationClusters[$countryId]['states'][$stateId]['cities'] = array();
						foreach($citiesInState[$stateId] as $cityId) {
							if(!isset($cityObjs[$cityId])) {
								continue;
							}
							$locationClusters[$countryId]['states'][$stateId]['cities'][$cityId] = array(
								'id' => $cityId,
								'name' => $cityObjs[$cityId]->getName(),
								'value' => $cities[$cityId]
							);
							if(count($zonesInCity[$cityId]) > 0) {
								$locationClusters[$countryId]['states'][$stateId]['cities'][$cityId]['zones'] = array();
								foreach($zonesInCity[$cityId] as $zoneId) {
									if(!isset($zoneObjs[$zoneId])){
										continue;
									}
									$locationClusters[$countryId]['states'][$stateId]['cities'][$cityId]['zones'][$zoneId] = array(
										'id' => $zoneId,
										'name' => $zoneObjs[$zoneId]->getName(),
										'value' => $zones[$zoneId]
									);
									
									if(count($localitiesInZone[$zoneId]) > 0) {
										$locationClusters[$countryId]['states'][$stateId]['cities'][$cityId]['zones'][$zoneId]['localities'] = array();
										foreach($localitiesInZone[$zoneId] as $localityId) {
											if(!isset($localityObjs[$localityId])){
												continue;
											}
											$locationClusters[$countryId]['states'][$stateId]['cities'][$cityId]['zones'][$zoneId]['localities'][$localityId] = array(
												'id' => $localityId,
												'name' => $localityObjs[$localityId]->getName(),
												'value' => $localities[$localityId]
											);	
										}
									}
									
								}
							}
							
						}
					}
				}
			}	
		}
		return $locationClusters;
	}
	
	
	private function _changeLoc($data)
	{
		$locationBuilder = new LocationBuilder();
		$locationRepo = $locationBuilder->getLocationRepository();
		
		$newData = array();
		
		foreach($data as $key => $value) {
			$keyParts = explode('/',$key);
			$cityId = intval($keyParts[1]);
			if($cityId) {
				$city = $locationRepo->findCity($cityId);
				$stateId = $city->getStateId();
				array_splice($keyParts,1,0,array($stateId));
				$newData[implode('/',$keyParts)] = $value;
			}
			else {
				$newData[$key] = $value;
			}
		}
		return $newData;
	}
	
	private function getCourseClusters($paramClusters = array(), $clusterType){
		$clusters = $paramClusters;
		$queryString = convertArrayToQueryString($_REQUEST);
		$currentURL = $_SERVER['SCRIPT_URI'] . "?" . $queryString;
		$courseClusters = array();
		$currentSelectedFacet = $this->_ci->security->xss_clean($_REQUEST['clusterType']);
		foreach($clusters as $key => $value){
			$arrayKeyIndex = "others";
			if($key == $currentSelectedFacet){
				$arrayKeyIndex = "selected";
			}
			$courseClusters[$arrayKeyIndex][$key] = array();
			$courseClusters[$arrayKeyIndex][$key]['name'] = makeSpaceSeparated($key);
			$courseClusters[$arrayKeyIndex][$key]['count'] = $value;
			$courseClusters[$arrayKeyIndex][$key]['url'] =  changeUrlParamValue($currentURL, $clusterType, $key);
		}
		$clusterKeys = array_keys($clusters);
		$arrayKeyIndex = "others";
		if($currentSelectedFacet == ""){
			$arrayKeyIndex = "selected";
		}
		$key = "all";
		$courseClusters[$arrayKeyIndex][$key] = array();
		$courseClusters[$arrayKeyIndex][$key]['name'] = makeSpaceSeparated($key);
		$courseClusters[$arrayKeyIndex][$key]['url'] =  changeUrlParamValue($currentURL, $clusterType, "");
		
		return $courseClusters;
	}
	
	private function getCourseDefaultCluster($clusterType){
		$courseClusters = array();
		$queryString = convertArrayToQueryString($_REQUEST);
		$currentURL = $_SERVER['SCRIPT_URI'] . "?" . $queryString;
		$currentSelectedFacet = trim($this->_ci->security->xss_clean($_REQUEST['clusterType']));
		$key = $currentSelectedFacet;
		$keyName = $key;
		$value = "";
		if($currentSelectedFacet == ""){
			$key = "all";
			$keyName = "";
		}
		$arrayKeyIndex = "selected";
		$courseClusters[$arrayKeyIndex][$key] = array();
		$courseClusters[$arrayKeyIndex][$key]['name'] = makeSpaceSeparated($key);
		$courseClusters[$arrayKeyIndex][$key]['count'] = $value;
		$courseClusters[$arrayKeyIndex][$key]['url'] =  changeUrlParamValue($currentURL, $clusterType, $keyName);
		if($currentSelectedFacet != ""){
			$arrayKeyIndex = "others";
			$key = "all";
			$courseClusters[$arrayKeyIndex][$key] = array();
			$courseClusters[$arrayKeyIndex][$key]['name'] = makeSpaceSeparated($key);
			$courseClusters[$arrayKeyIndex][$key]['url'] =  changeUrlParamValue($currentURL, $clusterType, "");
		}
		return $courseClusters;
	}

	public function getSearchResultsTags($params = array()){

		$finalResponse = array();
		$this->_ci->load->library("search/Autosuggestor/AutosuggestorLib");
		$AutosuggestorLib = new AutosuggestorLib();

		$optionParams = $params['optionalParams'];
		$response = $AutosuggestorLib->getTagsSearchResults($params['keyword'], $optionParams);

		$finalResponse['numFound'] = $response['response']['numFound'];

		foreach($response['response']['docs'] as $docs){
			$finalResponse['content'][] = $docs;
		}

		
		return $finalResponse;
	}
}
