<?php

class responseProcessorElasticParser{
	function __construct() {
		$this->CI                    = & get_instance();  		
    }

    function getResponseCount($response){    	
		return $response['hits']['total'];
    }

    function parseExamSubscriptionResponse($response){    	
    	$data     = $response['hits']['hits'];
    	
        return $data;
    }

    function parseListingResCountByClientId($response){
        $data           = $response['aggregations']['instituteId']['buckets'];
        $returnData     = array();
        $locationIds    = array();
        foreach ($data as $key => $value) {
            $instituteId                             = $value['key'];
            $totalResponseCount                      = $value['responseCount']['buckets'];
            foreach ($totalResponseCount as $key => $value) {                
                $returnData['data'][$instituteId][$value['key']]['institute_id']        = $instituteId;
                $returnData['data'][$instituteId][$value['key']]['locationId']          = $value['key'];
                $returnData['data'][$instituteId][$value['key']]['totalResponses']      = $value['doc_count'];  
                $locationIds[]                                                          = $value['key'];
            }

            $returnData['listingLocationIds']  = $locationIds;
        }
        return $returnData;        
    }

    function parseResponseByListingId($response,$client){
        $result = array();        
        $responses          = $response['hits']['hits'];
        $totalDoc           = $response['hits']['total'];        
        $scroll_id          = $response['_scroll_id'];
        $iterator           = 0;
        $userIds            = array();
        $listings           = array();
        foreach ($responses as $key => $value) {
            $result['responses'][$iterator]['userId']          = $value['_source']['user_id'];
            $result['responses'][$iterator]['id']              = $value['_source']['temp_lms_id'];
            $result['responses'][$iterator]['action']          = $value['_source']['response_action_type'];
            $result['responses'][$iterator]['listing_type']    = $value['_source']['response_listing_type']; 
            $result['responses'][$iterator]['listing_type_id'] = $value['_source']['response_listing_type_id']; 
            $result['responses'][$iterator]['submit_date']     = $value['_source']['response_time']; 
            $iterator ++;
            $userIds[$value['_source']['user_id']]             = $value['_source']['user_id'];
            $listings[$value['_source']['response_listing_type_id']] = 1;
        }              
        
        if($scroll_id){
            while (isset($response['hits']['hits']) && count($response['hits']['hits']) > 0) {            
                $response = $client->scroll([
                    "scroll_id" => $scroll_id,  //...using our previously obtained _scroll_id
                    "scroll"    => "1m"           // and the same timeout window
                    ]
                );
                $responses = $response['hits']['hits'];
                foreach ($responses as $key => $value) {
                    $result['responses'][$iterator]['userId']          = $value['_source']['user_id'];
                    $result['responses'][$iterator]['id']              = $value['_source']['temp_lms_id'];
                    $result['responses'][$iterator]['action']          = $value['_source']['response_action_type'];
                    $result['responses'][$iterator]['listing_type']    = $value['_source']['response_listing_type']; 
                    $result['responses'][$iterator]['listing_type_id'] = $value['_source']['response_listing_type_id']; 
                    $result['responses'][$iterator]['submit_date']     = $value['_source']['response_time']; 
                    $iterator ++;
                    $userIds[$value['_source']['user_id']]             = $value['_source']['user_id'];
                    $listings[$value['_source']['response_listing_type_id']] = 1;
                }
            }                    
        }
        
        $result['totalResponses'] = $totalDoc;
        $result['userIdsList']    = $userIds;
        $result['listingsList']   = $listings;
        if($totalDoc  != count($result)){
            //mail('aman.varshney@shiksha.com','Miss match count from elastic '.date('Y-m-d H:i:s'), 'Miss match count from elastic' );
        }
        unset($responses);
        unset($response);

        return $result;
    }

    public function parseResponsesData($response, $ESClientConn, $scrollTime) {
        $userIds = array();
        $scrollId = $response['_scroll_id'];
        if($scrollId){
            while (isset($response['hits']['hits']) && count($response['hits']['hits']) > 0) { 
                $responses = $response['hits']['hits'];
                foreach ($responses as $key => $value) {
                    $userIds[$value['_source']['user_id']] = TRUE;
                }
                $response = $ESClientConn->scroll(
                    array(
                        "scroll_id" => $scrollId,  
                        "scroll" => $scrollTime
                    )
                );
            }
        }
        return $userIds;
    }

    function getUniqueUsersCount($response, $aggsName){       
        return $response['aggregations'][$aggsName]['value'];
    }
}
