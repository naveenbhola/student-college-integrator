<?php

class SolrClient {
	private $solrSearchSever;

	private $solrRequestGenerator;
    private $solrResponseParser;

    function __construct() {
        $this->CI = & get_instance();
        
        $this->CI->config->load('search_config');
        $this->CI->load->builder('SearchBuilder','search');
        $this->solrSearchSever = SearchBuilder::getSearchServer($this->CI->config->item('search_server'));

        $this->solrRequestGenerator = $this->CI->load->library('search/Solr/SolrRequestGenerator');
        //$this->solrRequestGenerator = new SolrRequestGenerator;
        
        $this->solrResponseParser = $this->CI->load->library('search/Solr/SolrResponseParser');
        //$this->solrResponseParser = new SolrResponseParser();
    }

    public function getFiltersAndInstitutes($solrRequestData, $forNonSearchPage = false) {
        $time_start = microtime_float(); $start_memory = memory_get_usage(); 
        
        if($solrRequestData['requestType'] == 'category_result') $this->CI->benchmark->mark('Generate_Solr_URL_start');
        if($forNonSearchPage == false){
            $solrUrl = $this->solrRequestGenerator->generateUrlOnSearch($solrRequestData);
        }
        else{
            $solrUrl = $this->solrRequestGenerator->generateUrlOnSearch($solrRequestData,0, 'true');    
        }
        // if($solrRequestData['requestType'] != 'category_result') {
        //     error_log(date("Y-m-d h:i:sa")." | Solr url hit for ".$solrRequestData['requestType'].":".$solrUrl."\n", 3,"/tmp/log_solr_debug.log");
        //     error_log(date("Y-m-d h:i:sa")." | Page URL:".$_SERVER['REQUEST_URI']."\n\n", 3,"/tmp/log_solr_debug.log");
        // }

        if($solrRequestData['requestType'] == 'category_result') $this->CI->benchmark->mark('Generate_Solr_URL_end');
        
        if(LOG_CL_PERFORMANCE_DATA && $solrRequestData['requestType'] == 'category_result')
            error_log("Section: SolrClient, Generate Solr URL | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_CL_PERFORMANCE_DATA_FILE_NAME);
        
        $time_start = microtime_float(); $start_memory = memory_get_usage();
    	
        if($solrRequestData['requestType'] == 'category_result') $this->CI->benchmark->mark('Curl_Solr_Query_start');
        
        $urlComp = explode('?', $solrUrl);
        $solrContent = unserialize($this->solrSearchSever->curl($urlComp[0], $urlComp[1], "", $solrRequestData['requestType']));

        //error_log("Section: SolrClient, Parse Solr result | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3,"/tmp/category_solr_query_thread.log");

        if($solrRequestData['requestType'] == 'category_result') $this->CI->benchmark->mark('Curl_Solr_Query_end');

        if(LOG_CL_PERFORMANCE_DATA && $solrRequestData['requestType'] == 'category_result')
            error_log("Section: SolrClient, Curl Solr query | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_CL_PERFORMANCE_DATA_FILE_NAME);
        
        $time_start = microtime_float(); $start_memory = memory_get_usage();
        if($solrRequestData['requestType'] == 'category_result') $this->CI->benchmark->mark('Parse_Solr_Result_start');

        $solrContent['facetCriterion'] = $solrRequestData['facetCriterion'];
        $solrContent['requestType'] = $solrRequestData['requestType'];
        $solrContent['appliedFilters'] = $solrRequestData['filters'];
        $solrContent['userAppliedFilters'] = $solrRequestData['userAppliedFilters'];
        $solrContent['additionalFacetsToFetch'] = $solrRequestData['additionalFacetsToFetch'];

        $solrResult = $this->solrResponseParser->parseSolrResultOnSearch($solrContent);

        if($solrRequestData['requestType'] == 'category_result') $this->CI->benchmark->mark('Parse_Solr_Result_end');

        if(LOG_CL_PERFORMANCE_DATA && $solrRequestData['requestType'] == 'category_result')
            error_log("Section: SolrClient, Parse Solr result | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_CL_PERFORMANCE_DATA_FILE_NAME);

        if($solrResult['numOfInstitutes'] == 0 && $solrRequestData['requestType'] == "search_result"){
            if(!$solrRequestData['isQEmpty']){ // avoid second pass if q is already empty
                
                // when QER returns some result along with q
                if(!((count($solrRequestData['qerFilters']) == 1) && !empty($solrRequestData['qerFilters']['q']))){
                    $solrResult = $this->makeSecondPassWhenZeroResults($solrRequestData);
                }
            }
        }
        
        if($solrResult['numOfInstitutes'] == 0 && $solrRequestData['requestType'] == "search_result" && empty($solrRequestData['oldKeyword'])){
            $solrRequestData['relevantResults'] = 'spellcheck';
            if(!empty($solrResult['relevantResults'])){
                $solrRequestData['relevantResults'] = 'relaxandspellcheck';
            }
            $solrResult = $this->getSpellCheckResults($solrRequestData);
        }
        return $solrResult;
    }

    public function makeSecondPassWhenZeroResults($solrRequestData){
        $solrUrl = $this->solrRequestGenerator->generateUrlOnSearch($solrRequestData, 1);
        // error_log(date("Y-m-d h:i:sa")." | Solr url hit in second pass:".$solrUrl."\n", 3,"/tmp/log_solr_debug.log");
        // error_log(date("Y-m-d h:i:sa")." | Page URL:".$_SERVER['REQUEST_URI']."\n\n", 3,"/tmp/log_solr_debug.log");

        $urlComp = explode('?', $solrUrl);
        $solrContent = unserialize($this->solrSearchSever->curl($urlComp[0], $urlComp[1], "", $solrRequestData['requestType']."_second_pass"));
        $solrContent['facetCriterion'] = $solrRequestData['facetCriterion'];
        $solrContent['requestType'] = $solrRequestData['requestType'];
        $solrContent['appliedFilters'] = $solrRequestData['filters'];
        $solrContent['userAppliedFilters'] = $solrRequestData['userAppliedFilters'];
        $solrContent['applySort'] = 1;
        $solrResult = $this->solrResponseParser->parseSolrResultOnSearch($solrContent);
        $solrResult['relevantResults'] = 'relax';
        return $solrResult;
    }

    public function getSpellCheckResults($solrRequestData) {
        $solrUrl = $this->solrRequestGenerator->getSpellCheckSuggestions($solrRequestData);//_p($solrUrl);
        //error_log("SolrClient, ".date("Y-m-d h:i:sa")." Solr url hit for spellcheck:".$solrUrl."\n", 3,"/tmp/log_solr_debug.log");

        $solrContent = unserialize($this->solrSearchSever->curl($solrUrl));
        
        $solrResult = $this->solrResponseParser->parseSpellCorrection($solrContent);//_p($solrResult);
        
        if(!empty($solrResult)) {
            $urlData['keyword'] = $solrResult;
            $urlData['tracking'] = 0;
            $urlData['oldKeyword'] = $solrRequestData['keyword'];
            $urlData['relevantResults'] = $solrRequestData['relevantResults'];
            //carry request from
            $urlData['requestComingFrom'] = $solrRequestData['requestFrom'];
            // carry tracking params
            if(DO_SEARCHPAGE_TRACKING){
                $urlData['trackingSearchId'] = $solrRequestData['trackingSearchId'];
                $urlData['trackingFilterId'] = $solrRequestData['trackingFilterId'];
            }
            //carry location
            foreach($solrRequestData['userAppliedFilters']['city'] as $cityId){
                if(!in_array($cityId,$solrRequestData['qerFilters']['city'])){
                    $urlData['locations']['city_'.$cityId] = 'city_'.$cityId;
                }
            }
            foreach($solrRequestData['userAppliedFilters']['state'] as $stateId){
                if(!in_array($stateId,$solrRequestData['qerFilters']['state'])){
                    $urlData['locations']['state_'.$stateId] = 'state_'.$stateId;
                }
            }
            $urlData['createNewUrl'] = true;
            $this->CI->load->library("search/SearchV3/NationalSearchPageUrlGenerator");
            $this->searchPageUrlGenerator = new NationalSearchPageUrlGenerator();
            $url = $this->searchPageUrlGenerator->createOpenSearchUrl($urlData);
            header("Location: ".$url, TRUE, 301);
            exit;
        } else {
            $solrResult['numOfInstitutes'] = 0;
        }
        return $solrResult;
    }

    function getFiltersAndCourses($solrRequestData) {
        $solrUrl = $this->solrRequestGenerator->generateAllCoursesUrl($solrRequestData);
        //error_log("SolrClient, ".date("Y-m-d h:i:sa")." Solr url for ".$solrRequestData['requestType'].":".$solrUrl."\n", 3,"/tmp/log_solr_debug.log");
        
        $urlComp = explode('?', $solrUrl);
        $solrContent = unserialize($this->solrSearchSever->curl($urlComp[0], $urlComp[1], "", $solrRequestData['requestType']));
        // _p($solrContent);die;
        
        $solrContent['facetCriterion'] = $solrRequestData['facetCriterion'];
        $solrContent['requestType'] = $solrRequestData['requestType'];
        $solrContent['appliedFilters'] = $solrRequestData['filters'];
        $solrContent['userAppliedFilters'] = $solrRequestData['filters'];
        
        $solrResult = $this->solrResponseParser->parseSolrResultAllCourses($solrContent);

        return $solrResult;
    }

    function getQuestionsAndTags($solrRequestData) {
        if(empty($solrRequestData['keyword'])) {
            return;
        }
        $questionSolrUrl = $this->solrRequestGenerator->getQuestionSuggestionUrl($solrRequestData);
        $solrContent = unserialize($this->solrSearchSever->curl($questionSolrUrl));
        $questionResponse = $this->solrResponseParser->parseQuestionResults($solrContent);
        
        $unQuestionSolrUrl = $this->solrRequestGenerator->getUnansweredQuestionSuggestionUrl($solrRequestData);
        $solrContent = unserialize($this->solrSearchSever->curl($unQuestionSolrUrl));
        $unQuestionResponse = $this->solrResponseParser->parseQuestionResults($solrContent);
        
        $questionTopicSolrUrl = $this->solrRequestGenerator->getQuestionTopicSuggestionUrl($solrRequestData);
        $solrContent = unserialize($this->solrSearchSever->curl($questionTopicSolrUrl));
        $questionTopicResponse = $this->solrResponseParser->parseQuestionTopicResults($solrContent);
        
        if(!empty($questionResponse['resultCount'])) {
            $solrResult['data']['questions_answered'] = $questionResponse;
        }
        
        if(!empty($unQuestionResponse['resultCount'])) {
            $solrResult['data']['questions_unanswered'] = $unQuestionResponse;
        }
        
        if(!empty($questionTopicResponse['resultCount'])) {
            $solrResult['data']['questions_topics'] = $questionTopicResponse;
        }
        
        $solrResult['solr_urls'] = array($questionSolrUrl, $unQuestionSolrUrl, $questionTopicSolrUrl);

        return $solrResult;
    }

    function getUnansweredQuestions($solrRequestData) {
        if(empty($solrRequestData['keyword'])) {
            return;
        }

        $unQuestionSolrUrl = $this->solrRequestGenerator->getUnansweredQuestionSuggestionUrl($solrRequestData);
        $solrContent = unserialize($this->solrSearchSever->curl($unQuestionSolrUrl));
        $unQuestionResponse = $this->solrResponseParser->parseQuestionResults($solrContent);

        if(!empty($unQuestionResponse['resultCount'])) {
            $solrResult['data'] = $unQuestionResponse;
        }

      //  $solrResult['solr_urls'] = array($unQuestionSolrUrl);

        return $solrResult['data']['result'];
    }

    function getFilteredInstitutesForSpecialization($instIdsToFilter, $valid_spec_id) {

        $filtInstSolrUrl = $this->solrRequestGenerator->getFilteredInstitutesForSpecializationUrl($instIdsToFilter, $valid_spec_id);
        $solrContent = unserialize($this->solrSearchSever->curl($filtInstSolrUrl));

        if(array_key_exists("nl_course_hierarchy_institute_id", $solrContent["facet_counts"]["facet_fields"])) {
            $instIdsFound = array_keys($solrContent["facet_counts"]["facet_fields"]["nl_course_hierarchy_institute_id"]);

            $invalidInstIds = array_diff($instIdsToFilter, $instIdsFound);

            $filteredInstIds = array_diff($instIdsToFilter, $invalidInstIds);

            return $filteredInstIds;

        } else {
            return $instIdsToFilter;
        }
    }

	function getClientCoursesBasedOnCriteria($solrRequestData) {
        $url = $this->solrRequestGenerator->generateUrlForClientCourses($solrRequestData);
        $solrContent = unserialize($this->solrSearchSever->curl($url));
        $solrResult = $this->solrResponseParser->parseSolrResultOnSearch($solrContent);
        
        foreach ($solrResult['instituteIdCourseIdMap'] as $instituteId => $value) {
            $courseIds[] = $value[0];
        }

        return $courseIds;
    }
}