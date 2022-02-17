<?php

class CategoryPageSolrClient
{
    private $CI;
    private $solrServer;
    
    private $solrRequestGenerator;
    private $solrResponseParser;
    
    function __construct()
    {
        $this->CI = & get_instance();
        
        $this->CI->load->builder('SearchBuilder','search');
        $this->solrServer = SearchBuilder::getSearchServer();
        
        $this->CI->load->library('categoryList/solr/CategoryPageSolrRequestGenerator');
        $this->solrRequestGenerator = new CategoryPageSolrRequestGenerator();
        
        $this->CI->load->library('categoryList/solr/CategoryPageSolrResponseParser');
        $this->solrResponseParser = new CategoryPageSolrResponseParser();
    }
    
    public function getInstitutes(CategoryPageRequest $request)
    {
        $RNRSubcategories = array_keys($this->CI->config->item('CP_SUB_CATEGORY_NAME_LIST'));

        // check if subcategory is RnR subcategory and is not mobile category page
        // if( in_array($request->getSubCategoryId(), $RNRSubcategories) && 
        //     !($_COOKIE['ci_mobile_js_support'] == 'yes' || $GLOBALS['flag_mobile_js_support_user_agent'] == 'yes'))
        if( in_array($request->getSubCategoryId(), $RNRSubcategories) )
        {
            $result = $this->_getInstitutesForRnRSubCategories($request);
        }
        else
        {
            $result = $this->_getInstitutesForNonRnRSubCategories($request);
        }
        
        return $result;
    }
    
    public function getInstitutesForAllCities(CategoryPageRequest $request) {
        $solrRequest = $this->solrRequestGenerator->generateQueryToGetAllLocations($request);
        $response = $this->solrServer->curl($solrRequest);
        $locationClusters = $this->solrResponseParser->parseAllLocationQuery($response);
        
        return $locationClusters;
    }
    
    function _getInstitutesForNonRnRSubCategories(CategoryPageRequest $request)
    {
        $sTime = microtime(true);
        $solrRequest = $this->solrRequestGenerator->generate($request, 0);
        if(EN_LOG_FLAG) error_log("\narray( section => 'solr request generate for filters', timetaken => ".(microtime(true) - $sTime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);
        $sTime = microtime(true);
        $response = $this->solrServer->curl($solrRequest);
        if(EN_LOG_FLAG) error_log("\narray( section => 'solr curl for filters', timetaken => ".(microtime(true) - $sTime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);
        $sTime = microtime(true);
        // get the parsed solr response
        $result = $this->solrResponseParser->parse($response, 1, $request->getSortingCriteria());
        if(EN_LOG_FLAG) error_log("\narray( section => 'solr parse results for filters curl', timetaken => ".(microtime(true) - $sTime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);
        
        return $result;
    }
    
    function _getInstitutesForRnRSubCategories(CategoryPageRequest $request)
    {
        $sTime            = microtime(true);
        $RNRSubcategories = array_keys($this->CI->config->item('CP_SUB_CATEGORY_NAME_LIST'));
        
        $solrRequest = $this->solrRequestGenerator->generateRnRSolrQuery($request, 0);

        if(EN_LOG_FLAG) error_log("\narray( section => 'solr request generate for filters', timetaken => ".(microtime(true) - $sTime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);
        $sTime = microtime(true);
        $response = $this->solrServer->curl($solrRequest);
        if(EN_LOG_FLAG) error_log("\narray( section => 'solr curl for filters', timetaken => ".(microtime(true) - $sTime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);
        $sTime = microtime(true);
        // get the parsed solr response
        $result = $this->solrResponseParser->parse($response, 1, $request->getSortingCriteria());
        
        // LF-3045 : to enable exam filters for RNR mobile category pages as they work on "exams" instead of "courseexams"
        if($_COOKIE['ci_mobile_js_support'] == 'yes' || $GLOBALS['flag_mobile_js_support_user_agent'] == 'yes'){
            $result['filters']['exams'] = $result['filters']['courseexams'];
        }

        if(EN_LOG_FLAG) error_log("\narray( section => 'solr parse results for filters curl', timetaken => ".(microtime(true) - $sTime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);
		
        return $result;
    }
    
    function _getInstituteCountNonRnR($categoryPageData, $logFilePath) {
        //$logFilePath = '/tmp/log_category_page_institute_count_'.date('y-m-d');
        
        $time_start = microtime_float();
            $solrRequest = $this->solrRequestGenerator->generateQueryForNonRNRCatPageResultCount($categoryPageData);
        $time_end = microtime_float();
        $time = $time_end - $time_start;
        error_log("\tSolr request created. Time taken: ".(round($time, 5)*1000)." ms\n", 3, $logFilePath);
        
        $time_start = microtime_float();
            $response = $this->solrServer->curl($solrRequest);
        $time_end = microtime_float();
        $time = $time_end - $time_start;
        error_log("\tSolr curl made. Time taken: ".(round($time, 5)*1000)." ms\n", 3, $logFilePath);
        
        $time_start = microtime_float();
            $result = $this->solrResponseParser->parseInstituteCountResult($response);
        $time_end = microtime_float();
        $time = $time_end - $time_start;
        error_log("\tSolr result parsed. Time taken: ".(round($time, 5)*1000)." ms\n", 3, $logFilePath);
        
        return $result;
    }
    
    function _getInstituteCountRnR($categoryPageData, $filtersToHide, CategoryPageRequest $request, $logFilePath) {
        //$logFilePath = '/tmp/log_category_page_institute_count_'.date('y-m-d');
        
        $time_start = microtime_float();
            $solrRequest = $this->solrRequestGenerator->generateQueryForRNRCatPageResultCount($categoryPageData, $filtersToHide, $request);
        $time_end = microtime_float();
        $time = $time_end - $time_start;
        error_log("\tSolr request created. Time taken: ".(round($time, 5)*1000)." ms\n", 3, $logFilePath);
        
        $time_start = microtime_float();
            $response = $this->solrServer->curl($solrRequest);
        $time_end = microtime_float();
        $time = $time_end - $time_start;
        error_log("\tSolr curl made. Time taken: ".(round($time, 5)*1000)." ms\n", 3, $logFilePath);
        
        $time_start = microtime_float();
            $result = $this->solrResponseParser->parseInstituteCountResult($response);
        $time_end = microtime_float();
        $time = $time_end - $time_start;
		error_log("\tParsed count: ".$result."\n", 3, $logFilePath);
        error_log("\tSolr result parsed. Time taken: ".(round($time, 5)*1000)." ms\n", 3, $logFilePath);
        
        return $result;
    }
}