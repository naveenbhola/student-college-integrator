<?php

class CategoryPageSolrResponseParser
{
    private $CI;
    private $searchWrapper;
    
    function __construct()
    {
        $this->CI = & get_instance();
        
        $this->CI->load->library('search/Searcher/SearchWrapper');
        $this->searchWrapper = new SearchWrapper;
    }
    
    public function parse($solrResponse, $getfilteredResultsFlag = 1, $sortingCriteria)
    {
        
        global $CP_SOLR_FL_LIST;
        global $SOLR_DATA_FIELDS_ALIAS;
        
        $sortFlag = false;
        $sTime = microtime(true);
        $solrResponse = unserialize($solrResponse);
        if($getfilteredResultsFlag)
        {
            if(EN_LOG_FLAG) error_log("\narray( section => 'SOLR RESPONSE PARSE FOR FILTERS', timetaken => ".(microtime(true) - $sTime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3, EN_CP_LOG_FILENAME);
            if(EN_LOG_FLAG) error_log("\narray( section => 'solr query time for filters', timetaken => ".($solrResponse['responseHeader']['QTime']/1000).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);
        }
        else
        {
            if(EN_LOG_FLAG) error_log("\narray( section => 'SOLR RESPONSE PARSE FOR DATA', timetaken => ".(microtime(true) - $sTime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3, EN_CP_LOG_FILENAME);
            if(EN_LOG_FLAG) error_log("\narray( section => 'solr query time for data', timetaken => ".($solrResponse['responseHeader']['QTime']/1000).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);
        }
        
        $restTime = microtime(true);
        if(!empty($sortingCriteria) && $sortingCriteria != 'none') {
            $sortFlag = true;
            $sortParam = $this->getSortingField($sortingCriteria);
        }
        
        
        /*
         * Institutes/Courses
         */ 
        $grouped = $solrResponse['grouped']['institute_id'];
        
        $groups = $grouped['groups'];
        $totalCount = $grouped['ngroups'];
        
        $durations = array();
        
        $instituteWithSortValues = array();
        $institutes = array();
        $aliaFieldNameArr = array_flip($SOLR_DATA_FIELDS_ALIAS);
        foreach($groups as $group) {
            $institutes[$group['groupValue']] = array();
            
            $docs = $group['doclist']['docs'];
            if(!isset($instituteWithSortValues[$group['groupValue']])) {
                    $sortFieldVal = $SOLR_DATA_FIELDS_ALIAS[$sortParam] ? $SOLR_DATA_FIELDS_ALIAS[$sortParam] : $sortParam;
                    $instituteWithSortValues[$group['groupValue']] = $docs[0][$sortFieldVal];
            }
            foreach($docs as $doc) {
                
                $courseId = $doc[$SOLR_DATA_FIELDS_ALIAS['course_id']];
                if(!array_key_exists($courseId, $institutes[$group['groupValue']])){
                    $institutes[$group['groupValue']][$courseId] = array();
                }
                $doc['institute_id'] = $group['groupValue'];
                foreach($doc as $key=>$fieldData)
                    $doc[$aliaFieldNameArr[$key]] = $doc[$key];
                 $institutes[$group['groupValue']][$courseId] = $doc;
                
                //foreach($CP_SOLR_FL_LIST as $fl) {
                //    if($fl == 'course_exam_*') {
                //        foreach($doc['course_RnR_valid_exams'] as $exam) {
                //            $institutes[$group['groupValue']][$doc['course_id']]['course_exam_'.$exam] = $doc['course_exam_'.$exam];
                //        }
                //    } else {
                //        $institutes[$group['groupValue']][$doc['course_id']][$fl] = $doc[$fl];
                //    }
                //}
         
            }
        }
        
        /*
         * Generated filters
         */
        $filters = array();
        if($getfilteredResultsFlag)
        {
            $facets = $solrResponse['facet_counts'];
            $facetFields = $facets['facet_fields'];
            
            // Generate Duration Filter
            if($facets['facet_pivot'])
            {
                foreach($facets['facet_pivot']['course_duration_value,course_duration_unit'] as $durationArr)
                {
                    foreach($durationArr['pivot'] as $durationUnit)
                    {
                        $durations[] = $durationArr['value'].' '.$durationUnit['value'];
                    }
                }
            }
            
            $sTime = microtime(true);
            $locationClusters = $this->searchWrapper->getLocationClusters($facetFields['course_location_cluster']);
            if(EN_LOG_FLAG) error_log("\narray( section => 'solr parse location clusters', timetaken => ".(microtime(true) - $sTime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);
            //$locationClusters["course_virtual_city_id"] = $facetFields["course_virtual_city_id"];
            
            if(isset($locationClusters))
            {
                $locationClusters[2]["course_virtual_city_id"] = $facetFields["course_virtual_city_id"];
            }
            
            $filters['courseLevel']                     = $facetFields['course_level_cp'];
            $filters['mode']                            = $facetFields['course_type_facet'];
            $filters['location']                        = $locationClusters;
            $filters['exams']                           = $facetFields['course_eligibility_required_cp'];
            $filters['courseexams']                     = $facetFields['course_RnR_valid_exams'];//$facetFields['course_eligibility_required'];
            $filters['degreePref']                      = $facetFields['course_degree_pref'];
            $filters['locality']                        = $locationClusters;
            $filters['zone']                            = $locationClusters;
            $filters['city']                            = $locationClusters;
            $filters['state']                           = $locationClusters;
            $filters['country']                         = $locationClusters;
            $filters['duration']                        = $durations;
            $filters['classTimings']                    = $facetFields['course_class_timings'];
            $filters['AIMARating']                      = $facetFields['institute_aima_rating'];
            $filters['subcatIds']                       = $facetFields['course_category_id_list'];
            $filters['ldbCourseIds']                    = $facetFields['course_ldb_id'];
            //$filters["fees"]                            = $facetFields['course_normalised_fees'];
            
            $filters["specialization"]                  = $facetFields['course_ldb_id'];
            $filters["course_ldb_id_current"]           = $facetFields['course_ldb_id_current'];
            $filters["course_state_id_current"]         = $facetFields['course_state_id_current'];
            $filters["course_city_id_current"]          = $facetFields['course_city_id_current'];
            $filters["course_locality_id_current"]      = $facetFields['course_locality_id_current'];
            $filters["course_virtual_city_id_current"]  = $facetFields['course_virtual_city_id_current'];
            $filters["course_RnR_valid_exams_current"]  = $facetFields['course_RnR_valid_exams_current'];
            //$filters["feesCurrent"]                     = $facetFields['course_normalised_fees_current'];
            $filters["facet_queries"]                   = $facets['facet_queries'];
            
           // set fees and exam facet in case of mobile site
           if($_COOKIE['ci_mobile_js_support'] == 'yes' || $GLOBALS['flag_mobile_js_support_user_agent'] == 'yes'){
                    //$filters['exams']                             = $facetFields['course_RnR_valid_exams'];
                    $filters["fees"]                                = $facetFields['course_normalised_fees'];
           }
            
        }
        
        if($getfilteredResultsFlag)
        {
            if(EN_LOG_FLAG) error_log("\narray( section => 'SOLR RESPONSE PARSE REST FOR FILTERS', timetaken => ".(microtime(true) - $restTime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3, EN_CP_LOG_FILENAME);
        }
        else
        {
            if(EN_LOG_FLAG) error_log("\narray( section => 'SOLR RESPONSE PARSE REST FOR DATA', timetaken => ".(microtime(true) - $restTime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3, EN_CP_LOG_FILENAME);
        }
        
        $return = array();
        $sortedInstitutesWithData = array();
        $return['totalResults'] = $totalCount;
        $return['institutes'] = $institutes;
        if($sortFlag) {
            $return['institutesWithSortValues'] = $instituteWithSortValues;
        }
        $return['filters'] = $filters;
        
        return $return;
        exit();
    }
    
    public function parseAllLocationQuery($solrResponse) {
        $solrResponse = unserialize($solrResponse);
        
        $facets = $solrResponse['facet_counts'];
        $facetFields = $facets['facet_fields'];
        $locationClusters['locations'] = $this->searchWrapper->getLocationClusters($facetFields['course_location_cluster']);
        $locationClusters['virtual_cities'] = array_keys($facetFields['course_virtual_city_id']);
        
        return $locationClusters;
    }
    
    private function getSortingField($sortingCriteria) {
        $sortingField = "";
        global $SOLR_DATA_FIELDS_ALIAS;
        switch($sortingCriteria['sortBy']) {
            case 'fees':
                $sortingField = $SOLR_DATA_FIELDS_ALIAS['course_normalised_fees'];
                break;
                
            case 'examscore':
                $exam_name = $sortingCriteria['params']['exam'];
                $exam_name = removeSpacesFromString($exam_name);
                $sortingField = 'course_exam_'.$exam_name;
                break;
                
            case 'duration':
                $sortingField = $SOLR_DATA_FIELDS_ALIAS['course_duration_in_hours'];
                break;
            
            case 'date_form_submission':
                $sortingField = $SOLR_DATA_FIELDS_ALIAS['date_form_submission'];
                break;
        }
        return $sortingField;
    }
    
    public function parseInstituteCountResult($solrResponse) {
        $solrResponse = unserialize($solrResponse);
        $grouped = $solrResponse['grouped']['institute_id'];
        $totalCount = $grouped['ngroups'];
        
        return $totalCount;
    }
}