<?php

class LeadDebuggingRequestGenerator
{
    private $CI;
    private $searchCriteria;
    private $requestParamGlobal = array();

    function __construct()
    {
        $this->CI = & get_instance();
        
     /*   $this->CI->load->builder('SearchBuilder','search');
        $this->solrServer = SearchBuilder::getSearchServer();
        
        $this->CI->load->model('searchAgents/matchedresponseagentmodel');
        $this->matchedResponsemodel = new MatchedResponseAgentModel;*/

    }
    
    public function setSearchCriteria($searchCriteria){
        $this->searchCriteria = $searchCriteria;
    }
    
    public function generateSearchRequest($searchCriteria){
        if($searchCriteria['index'] == ''){
            return false;
        }

        $this->setSearchCriteria($searchCriteria);

        /*if(!$searchCriteria['return_fields_flag']){
            $this->requestParamGlobal['_source'] = 'false';
        }else{
             $this->requestParamGlobal['_source'] = $searchCriteria['_source'];
        }*/

        $this->requestParamGlobal['_source'] = $searchCriteria['_source'];
        
        $this->requestParamGlobal['index'] = $searchCriteria['index'];

        if($searchCriteria['type']){
            $this->requestParamGlobal['type'] =  $searchCriteria['type'];
        }

        if($searchCriteria['filter_path']){
            $this->requestParamGlobal['filter_path'] =  $searchCriteria['filter_path'];
        }

        if($searchCriteria['size']){
            $this->requestParamGlobal['body']['size'] =  $searchCriteria['size'];
        }


        if($searchCriteria['multi_match_query']){
            $this->requestParamGlobal['body']['query']['bool']['must']['multi_match']['query'] =  $searchCriteria['multi_match_query'];
        }

        if($searchCriteria['multi_match_fields']){
            $this->requestParamGlobal['body']['query']['bool']['must']['multi_match']['fields'] =  $searchCriteria['multi_match_fields'];
        }

        if($searchCriteria['match']['should']){
            $match_query_bool_should = '';

            foreach ($searchCriteria['match']['should'] as $match_array) {
                foreach ($match_array as $field_name => $field_value) {
                    $temp_query['match'][$field_name]['query'] = $field_value;

                    if($searchCriteria['matched_field_flag']){
                        $temp_query['match'][$field_name]['_name'] = $field_name;
                    }

                    $match_query_bool_should[] = $temp_query;
                    unset($temp_query);
                }
            }

             $this->requestParamGlobal['body']['query']['bool']['should'][] =  $match_query_bool_should;
        }

        if($searchCriteria['match']['must']){
            $match_query_bool_should = '';

            foreach ($searchCriteria['match']['must'] as $match_array) {
                foreach ($match_array as $field_name => $field_value) {
                    $temp_query['match'][$field_name]['query'] = $field_value;

                    if($searchCriteria['matched_field_flag']){
                        $temp_query['match'][$field_name]['_name'] = $field_name;
                    }

                    $match_query_bool_should[] = $temp_query;
                    unset($temp_query);
                }
            }

             $this->requestParamGlobal['body']['query']['bool']['must'][] =  $match_query_bool_should;
        }

        if($searchCriteria['filterTerms']){
            foreach ($searchCriteria['filterTerms'] as $fieldKey => $fieldValue) {
                $termFilter[]['term'][$fieldKey] = $fieldValue;
            }
            $this->requestParamGlobal['body']['query']['bool']['filter'] = $termFilter;
        }
        

        if($searchCriteria['minimum_should_match']){
            $this->requestParamGlobal['body']['query']['bool']['minimum_should_match'] = $searchCriteria['minimum_should_match'];
        }
        
        //sort_order_map -- to change and handle multiple sorts 
        if(is_array($searchCriteria['sort_order_map']) && count($searchCriteria['sort_order_map'])>0 ){

            foreach ($searchCriteria['sort_order_map'] as $field => $order) {
                $this->requestParamGlobal['body']['sort']['user_pick_time']['order'] =  $order;
            }

        }

        if(is_array($searchCriteria['must_term_filter_map']) && count($searchCriteria['must_term_filter_map'])>0 ){
            
            foreach ($searchCriteria['must_term_filter_map'] as $field => $value) {
                $mustFilter[] = array('term' => array($field => $value));
            }

            $this->requestParamGlobal['body']['filter'] =  $mustFilter;
        }

        return $this->requestParamGlobal;
	}

    public function generateSearchRequestOldVersion($searchCriteria){
        if($searchCriteria['index'] == ''){
            return false;
        }

        $this->setSearchCriteria($searchCriteria);

        /*if(!$searchCriteria['return_fields_flag']){
            $this->requestParamGlobal['_source'] = 'false';
        }else{
             $this->requestParamGlobal['_source'] = $searchCriteria['_source'];
        }*/

        $this->requestParamGlobal['_source'] = $searchCriteria['_source'];
        
        $this->requestParamGlobal['index'] = $searchCriteria['index'];

        if($searchCriteria['type']){
            $this->requestParamGlobal['type'] =  $searchCriteria['type'];
        }

        if($searchCriteria['filter_path']){
            $this->requestParamGlobal['filter_path'] =  $searchCriteria['filter_path'];
        }

        if($searchCriteria['size']){
            $this->requestParamGlobal['body']['size'] =  $searchCriteria['size'];
        }


        if($searchCriteria['multi_match_query']){
            $this->requestParamGlobal['body']['query']['bool']['must']['multi_match']['query'] =  $searchCriteria['multi_match_query'];
        }

        if($searchCriteria['multi_match_fields']){
            $this->requestParamGlobal['body']['query']['bool']['must']['multi_match']['fields'] =  $searchCriteria['multi_match_fields'];
        }

        if($searchCriteria['match']['should']){
            $match_query_bool_should = '';

            foreach ($searchCriteria['match']['should'] as $match_array) {
                foreach ($match_array as $field_name => $field_value) {
                    $temp_query['match'][$field_name]['query'] = $field_value;

                    if($searchCriteria['matched_field_flag']){
                        $temp_query['match'][$field_name]['_name'] = $field_name;
                    }

                    $match_query_bool_should[] = $temp_query;
                    unset($temp_query);
                }
            }

             $this->requestParamGlobal['body']['query']['bool']['should'][] =  $match_query_bool_should;
        }

        if($searchCriteria['match']['must']){
            $match_query_bool_should = '';

            foreach ($searchCriteria['match']['must'] as $match_array) {
                foreach ($match_array as $field_name => $field_value) {
                    $temp_query['match'][$field_name]['query'] = $field_value;

                    if($searchCriteria['matched_field_flag']){
                        $temp_query['match'][$field_name]['_name'] = $field_name;
                    }

                    $match_query_bool_should[] = $temp_query;
                    unset($temp_query);
                }
            }

             $this->requestParamGlobal['body']['query']['bool']['must'][] =  $match_query_bool_should;
        }

        if($searchCriteria['filterTerms']){
            foreach ($searchCriteria['filterTerms'] as $fieldKey => $fieldValue) {
                $termFilter[]['term'][$fieldKey] = $fieldValue;
            }
            $this->requestParamGlobal['body']['query']['bool']['filter'] = $termFilter;
        }
        

        if($searchCriteria['minimum_should_match']){
            $this->requestParamGlobal['body']['query']['bool']['minimum_should_match'] = $searchCriteria['minimum_should_match'];
        }
        
        //sort_order_map -- to change and handle multiple sorts 
        if(is_array($searchCriteria['sort_order_map']) && count($searchCriteria['sort_order_map'])>0 ){

            foreach ($searchCriteria['sort_order_map'] as $field => $order) {
                $this->requestParamGlobal['body']['sort']['user_pick_time']['order'] =  $order;
            }

        }

        if(is_array($searchCriteria['must_term_filter_map']) && count($searchCriteria['must_term_filter_map'])>0 ){
            
            foreach ($searchCriteria['must_term_filter_map'] as $field => $value) {
                $mustFilter[] = array('term' => array($field => $value));
            }

            $this->requestParamGlobal['body']['filter'] =  $mustFilter;
        }

        return $this->requestParamGlobal;
    }
    
   
}
