<?php
define("EXAM_PAGES_SEARCH_SUGGESTIONS_COUNT",6);
define("ALL_EXAM_PAGES_SEARCH_SUGGESTIONS_COUNT",2);
class AutoSuggestorSolrClient {
	private $solrSearchSever;
	private $solrRequestGenerator;
    private $solrResponseParser;

    function __construct()
    {
        $this->CI = & get_instance();
        $this->CI->config->load('search_config');
        $this->CI->load->builder('SearchBuilder','search');
        $this->solrSearchSever = SearchBuilder::getSearchServer($this->CI->config->item('search_server'));

        $this->CI->load->library('search/Solr/AutoSuggestorSolrRequestGenerator');
        $this->autoSuggestorSolrRequestGenerator = new AutoSuggestorSolrRequestGenerator;
        
        $this->CI->load->library('search/Solr/AutoSuggestorSolrResponseParser');
        $this->autoSuggestorSolrResponseParser = new AutoSuggestorSolrResponseParser();

        $this->CI->load->library("search/SearchV2/SearchPageUrlGenerator");
        $this->searchPageUrlGenerator = new SearchPageUrlGenerator();
    }

    public function getExamAndAllExamSuggestionsFromSolr($solrRequestData){
        if(empty($solrRequestData['text'])) {
            return;
        }
        $solrExamUrl = $this->autoSuggestorSolrRequestGenerator->getExamSearchSuggestionUrl($solrRequestData);
        // _p($solrExamUrl);die;
        $solrExamContent = unserialize($this->solrSearchSever->curl($solrExamUrl, "", "", "autosuggestor"));
        $response = $this->autoSuggestorSolrResponseParser->parseExamSearchAutoSuggestionResults($solrExamContent);
        $solrExamResult = $response['examResults'];
        $solrAllExamResult = $response['allExamResults'];
        
        $solrResult['data']['course_ldb_course_name_facet'] = array_slice($solrExamResult['data']['course_ldb_course_name_facet'], 0,EXAM_PAGES_SEARCH_SUGGESTIONS_COUNT) + array_slice($solrAllExamResult['data']['course_ldb_course_name_facet'],0,ALL_EXAM_PAGES_SEARCH_SUGGESTIONS_COUNT)+array_slice($solrExamResult['data']['course_ldb_course_name_facet'], EXAM_PAGES_SEARCH_SUGGESTIONS_COUNT)+array_slice($solrAllExamResult['data']['course_ldb_course_name_facet'],ALL_EXAM_PAGES_SEARCH_SUGGESTIONS_COUNT);
        $solrResult['solr_urls'] = array($solrExamUrl, $solrAllExamUrl);
        return $solrResult;
    }

    public function getBaseEntitiesAndInsttSuggestionsFromSolr($solrRequestData) {
        if(empty($solrRequestData['text']) || empty($solrRequestData['maxResultCount'])) {
            return;
        }
        $solrEntityUrl = $this->autoSuggestorSolrRequestGenerator->generateBaseEntitiesAutoSuggestionUrl($solrRequestData);
        $solrEntityContent = unserialize($this->solrSearchSever->curl($solrEntityUrl, "", "", "autosuggestor"));
        $solrEntityResult = $this->autoSuggestorSolrResponseParser->parseEntitiesAutosuggestionResults($solrEntityContent);
        
        $solrInsttUrl = $this->autoSuggestorSolrRequestGenerator->generateInsttAutoSuggestionUrl($solrRequestData);
        $solrInsttContent = unserialize($this->solrSearchSever->curl($solrInsttUrl, "", "", "autosuggestor"));
        $solrInsttResult = $this->autoSuggestorSolrResponseParser->parseInsttAutosuggestionResults($solrInsttContent);

        //merge the 2 result sets
        $solrResult['data'] = $solrEntityResult['data'] + $solrInsttResult['data'];
        unset($solrEntityResult['data']); unset($solrInsttResult['data']);
        $solrResult = $solrResult + $solrEntityResult + $solrInsttResult;
        $solrResult['solr_urls'] = array($solrEntityUrl, $solrInsttUrl);
        
        return $solrResult;
    }

    public function getSuggestionsForAnalyticsFromSolr($solrRequestData) {
        if(empty($solrRequestData['text']) || empty($solrRequestData['maxResultCount'])) {
            return;
        }
        $solrEntityUrl = $this->autoSuggestorSolrRequestGenerator->generateBaseEntitiesAutoSuggestionUrl($solrRequestData);
        $solrEntityContent = unserialize($this->solrSearchSever->curl($solrEntityUrl));
        $solrEntityResult = $this->autoSuggestorSolrResponseParser->parseEntitiesAutosuggestionResults($solrEntityContent);
        
        $solrInsttUrl = $this->autoSuggestorSolrRequestGenerator->generateInsttAutoSuggestionUrl($solrRequestData);
        $solrInsttContent = unserialize($this->solrSearchSever->curl($solrInsttUrl));
        $solrInsttResult = $this->autoSuggestorSolrResponseParser->parseInsttAutosuggestionResults($solrInsttContent);

        $solrExamUrl = $this->autoSuggestorSolrRequestGenerator->getExamSearchSuggestionUrl($solrRequestData);
        $solrExamContent = unserialize($this->solrSearchSever->curl($solrExamUrl));
        $response = $this->autoSuggestorSolrResponseParser->parseExamSearchAutoSuggestionResults($solrExamContent);
        $solrExamResult = $response['examResults'];
        $solrAllExamResult = $response['allExamResults'];

        // _p($solrEntityResult);
        // _p("---------------");
        // _p($solrInsttResult);
        // _p("---------------");
        // _p($response);
        // _p("---------------");
        
        //merge the 2 result sets
        $solrResult['data'] = $solrEntityResult['data'] + $solrInsttResult['data'];// + $response['examResults']['data'] + $response['allExamResults']['data'];

        $solrResult['data']['exam_facet'] = array_merge((array)$response['examResults']['data']['course_ldb_course_name_facet'], (array)$response['allExamResults']['data']['course_ldb_course_name_facet']);
        $solrResult['data']['exam_facet'] = array_filter($solrResult['data']['exam_facet']);
        unset($solrEntityResult['data']); unset($solrInsttResult['data']); unset($response['examResults']['data']); unset($response['allExamResults']['data']);
        $solrResult = $solrResult + $solrEntityResult + $solrInsttResult + $response;

        $solrResult['solr_urls'] = array($solrEntityUrl, $solrInsttUrl);
        
        return $solrResult;
    }

    public function getQuestionAndTopicSuggestionsFromSolr($solrRequestData){
        if(empty($solrRequestData['text'])) {
            return;
        }
        $questionSolrUrl = $this->autoSuggestorSolrRequestGenerator->getQuestionSuggestionUrl($solrRequestData);
        $solrContent = unserialize($this->solrSearchSever->curl($questionSolrUrl, "", "", "autosuggestor"));
        $questionResponse = $this->autoSuggestorSolrResponseParser->parseQuestionAutosggestionResults($solrContent);
        
        $questionTopicSolrUrl = $this->autoSuggestorSolrRequestGenerator->getQuestionTopicSuggestionUrl($solrRequestData);
        $solrContent = unserialize($this->solrSearchSever->curl($questionTopicSolrUrl, "", "", "autosuggestor"));
        $questionTopicResponse = $this->autoSuggestorSolrResponseParser->parseQuestionTopicAutosggestionResults($solrContent);
        
        $solrResult['data']['question_title_facet'] = $questionResponse;
        $solrResult['data']['question_topic_facet'] = $questionTopicResponse;
        
        $solrResult['solr_urls'] = array($questionSolrUrl, $questionTopicSolrUrl);
        return $solrResult;
    }

    public function getInsttSuggestionsFromSolr($solrRequestData) {
        if(empty($solrRequestData['text']) || empty($solrRequestData['maxResultCount'])) {
            return;
        }

        $solrInsttUrl = $this->autoSuggestorSolrRequestGenerator->generateInsttAutoSuggestionUrl($solrRequestData);
        $solrInsttContent = unserialize($this->solrSearchSever->curl($solrInsttUrl, "", "", "autosuggestor"));
        
        $solrResult = $this->autoSuggestorSolrResponseParser->parseInsttAutosuggestionResults($solrInsttContent);
        $solrResult['solr_urls'] = array($solrInsttUrl);
        return $solrResult;
    }

    public function getAdvancedFilterOnInsttSelection($solrRequestData) {
        $solrUrl = $this->autoSuggestorSolrRequestGenerator->generateInsttAdvancedFilterUrl($solrRequestData);
        $solrContent = unserialize($this->solrSearchSever->curl($solrUrl, "", "", "advanced_filter"));
        $solrResult = $this->autoSuggestorSolrResponseParser->parseInsttAdvancedFilters($solrContent);
        return $solrResult;
    }

    public function getLocationOnMultipleInsttSelection($solrRequestData) {
        $solrUrl = $this->autoSuggestorSolrRequestGenerator->generateMultipleInsttLocationUrl($solrRequestData);
        $solrContent = unserialize($this->solrSearchSever->curl($solrUrl, "", "", "advanced_filter"));
        $solrResult = $this->autoSuggestorSolrResponseParser->parseLocationResult($solrContent);
        $isMultilocation = 0;
        if((count($solrResult['popular_city']) + count($solrResult['city']) + count($solrResult['state'])) > 1) {
            $isMultilocation = 1;
        }
        $solrResult['isMultilocation']  = $isMultilocation;

        return $solrResult;
    }

    public function getAdvancedFilterOnEntitySelection($solrRequestData) {
        $solrUrl = $this->autoSuggestorSolrRequestGenerator->generateEntityAdvancedFilterUrl($solrRequestData);
        $solrContent = unserialize($this->solrSearchSever->curl($solrUrl, "", "", "advanced_filter"));
        
        $solrContent['facetCriterion'] = $solrRequestData['facetCriterion'];
        $solrContent['requestType'] = $solrRequestData['requestType'];
        $solrResult = $this->autoSuggestorSolrResponseParser->parseEntityAdvancedFilters($solrContent);
        return $solrResult;
    }

    public function getAllLocations($solrRequestData) {
        $solrUrl = $this->autoSuggestorSolrRequestGenerator->generateUrlToGetAllLocations($solrRequestData);
        $solrContent = unserialize($this->solrSearchSever->curl($solrUrl, "", "", "advanced_filter"));
        $solrResult = $this->autoSuggestorSolrResponseParser->parseLocationResult($solrContent);
        return $solrResult;
    }
    /*
    public function getFiltersAndInstitutes($solrRequestData) {
        $time_start_1 = microtime_float(); $start_memory_1 = memory_get_usage();
        
        $solrUrl = $this->autoSuggestorSolrRequestGenerator->generateUrlOnSearch($solrRequestData);
        if(LOG_SEARCH_PERFORMANCE_DATA) error_log("Section: In AutoSuggestorSolrClient, generate solr url | ".getLogTimeMemStr($time_start_1, $start_memory_1)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);
        
        $time_start = microtime_float(); $start_memory = memory_get_usage();

        $urlComp = explode('?', $solrUrl);
        $solrContent = unserialize($this->solrSearchSever->curl($urlComp[0], $urlComp[1]));
        //$solrContent = unserialize($this->solrSearchSever->curl($solrUrl));

        if(LOG_SEARCH_PERFORMANCE_DATA) error_log("Section: In AutoSuggestorSolrClient, Hit solr curl | Time taken: ".$solrContent['responseHeader']['QTime']." | Memory used: | Memory limit (allocated): \n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);
        if(LOG_SEARCH_PERFORMANCE_DATA) error_log("Section: In AutoSuggestorSolrClient, Hit solr curl total | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);
        
        //parse the result
        $solrContent['subcatId'] = $solrRequestData['userAppliedFilters']['subcatId'];
        $solrContent['catId'] = $solrRequestData['userAppliedFilters']['catId'];
        $solrContent['catName'] = $solrRequestData['userAppliedFilters']['catName'];
        $solrContent['applySort'] = 1;
        if(empty($solrRequestData['userAppliedFilters']['subcatId']) && empty($solrRequestData['qerFilters']['institute']) && empty($solrRequestData['qerFilters']['course'])) {
            $solrContent['applySort'] = 0;
        }

        $time_start = microtime_float(); $start_memory = memory_get_usage();
        $solrResult = $this->autoSuggestorSolrResponseParser->parseSolrResultOnSearch($solrContent);
        if(LOG_SEARCH_PERFORMANCE_DATA) error_log("Section: In AutoSuggestorSolrClient, Parsing | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);
        
        if($solrResult['numOfInstitutes'] == 0) {
            if(!$solrRequestData['isQEmpty']){ // avoid second pass if q is already empty
                //make second pass to show relevant results
                if(!((count($solrRequestData['qerFilters']) == 1) && !empty($solrRequestData['qerFilters']['q']))){
                    $solrResult = $this->makeSecondPassWhenZeroResults($solrRequestData);
                }
            }
            if($solrResult['numOfInstitutes'] == 0 && empty($solrRequestData['oldKeyword'])) {
                //go through spell checker if zero results and if this is the first time it's through spell check
                $solrRequestData['relevantResults'] = 'spellcheck';
                if(!empty($solrResult['relevantResults'])){
                    $solrRequestData['relevantResults'] = 'relaxandspellcheck';
                }
                $solrResult = $this->getSpellCheckResults($solrRequestData);
            }
        }
        if(LOG_SEARCH_PERFORMANCE_DATA) error_log("Section: In AutoSuggestorSolrClient, Total time | ".getLogTimeMemStr($time_start_1, $start_memory_1)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);
        return $solrResult;
    }

    public function makeSecondPassWhenZeroResults($solrRequestData){
        $time_start = microtime_float(); $start_memory = memory_get_usage();

        $solrUrl = $this->autoSuggestorSolrRequestGenerator->generateUrlOnSearch($solrRequestData, 1);
        if(LOG_SEARCH_PERFORMANCE_DATA) error_log("Section: In AutoSuggestorSolrClient, generate solr url, for second pass | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);
        $time_start = microtime_float(); $start_memory = memory_get_usage();

        $urlComp = explode('?', $solrUrl);
        $solrContent = unserialize($this->solrSearchSever->curl($urlComp[0], $urlComp[1]));

        if(LOG_SEARCH_PERFORMANCE_DATA) error_log("Section: In AutoSuggestorSolrClient, Hit solr curl, for second pass | Time taken: ".$solrContent['responseHeader']['QTime']." | Memory used: | Memory limit (allocated): \n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);
        if(LOG_SEARCH_PERFORMANCE_DATA) error_log("Section: In AutoSuggestorSolrClient, Hit solr curl total, for second pass | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);
        
        //parse the result
        $solrContent['subcatId'] = $solrRequestData['userAppliedFilters']['subcatId'];
        $solrContent['catId'] = $solrRequestData['userAppliedFilters']['catId'];
        $solrContent['catName'] = $solrRequestData['userAppliedFilters']['catName'];
        $solrContent['applySort'] = 1;
        if(empty($solrRequestData['userAppliedFilters']['subcatId']) && empty($solrRequestData['qerFilters']['institute']) && empty($solrRequestData['qerFilters']['course'])) {
            $solrContent['applySort'] = 0;
        }

        $time_start = microtime_float(); $start_memory = memory_get_usage();

        $solrResult = $this->autoSuggestorSolrResponseParser->parseSolrResultOnSearch($solrContent);

        if(LOG_SEARCH_PERFORMANCE_DATA) error_log("Section: In AutoSuggestorSolrClient, Parsing, for second pass | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);
        $solrResult['relevantResults'] = 'relax';
        return $solrResult;
    }
    */
    public function solrVersionQER($solrRequestData) {
        $solrFilterUrl = $this->autoSuggestorSolrRequestGenerator->generateUrlToRecognizeEntity($solrRequestData);
        $solrFilterContent = unserialize($this->solrSearchSever->curl($solrFilterUrl, "", "", "advanced_filter"));
        $solrResult = $this->autoSuggestorSolrResponseParser->parseRecognisedEntitites($solrFilterContent);
        
        return $solrResult;
    }

    public function getSpellCheckResults($solrRequestData) {//_p($solrRequestData);;die;
        $time_start = microtime_float(); $start_memory = memory_get_usage();
        $solrUrl = $this->autoSuggestorSolrRequestGenerator->getSpellCheckSuggestions($solrRequestData);//_p($solrUrl);
        if(LOG_SEARCH_PERFORMANCE_DATA) error_log("Section: In AutoSuggestorSolrClient, generate solr url, for spell check | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);
        
        $time_start = microtime_float(); $start_memory = memory_get_usage();
        $solrContent = unserialize($this->solrSearchSever->curl($solrUrl));
        if(LOG_SEARCH_PERFORMANCE_DATA) error_log("Section: In AutoSuggestorSolrClient, Hit solr curl total, for spell check | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_SEARCH_PERFORMANCE_DATA_FILE_NAME);

        $solrResult = $this->autoSuggestorSolrResponseParser->parseSpellCorrection($solrContent);//_p($solrResult);
        if(!empty($solrResult)) {
            $urlData['keyword'] = $solrResult;
            $urlData['tracking'] = 0;
            $urlData['oldKeyword'] = $solrRequestData['keyword'];
            $urlData['relevantResults'] = $solrRequestData['relevantResults'];
            //carry request from
            $urlData['requestComingFrom'] = $solrRequestData['requestFrom'];//_p($urlData);die;
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
            $url = $this->searchPageUrlGenerator->createOpenSearchUrl($urlData);//_p($url);die;

            if(DO_SEARCHPAGE_TRACKING){
                $urlParts = parse_url($url);
                parse_str($urlParts['query'],$params);
                if(!empty($solrRequestData['trackingSearchId'])){
                    $params['ts'] = $solrRequestData['trackingSearchId'];
                }
                if(!empty($solrRequestData['trackingFilterId'])){
                    $params['tf'] = $solrRequestData['trackingFilterId'];
                }
                $urlParts['query'] = http_build_query($params);
                $url = $urlParts['scheme'] . '://' . $urlParts['host'] . $urlParts['path'] . '?' . $urlParts['query'];
            }
// _p('hemath');die;
            header("Location: ".$url, TRUE, 301);
            exit;
        } else {
            $solrResult['numOfInstitutes'] = 0;
        }
        
        return $solrResult;
    }
}
