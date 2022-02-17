<?php

class MatchedResponseMatcher
{
    private $CI;
    
    private $responseUserId;
    private $responseCourseId;
    private $responseProfile;
    
    private $model;
    
    function __construct()
    {
        $this->CI = & get_instance();
        
        $this->CI->load->model('searchAgents/matchedresponseagentmodel');
        $this->model = new MatchedResponseAgentModel;
        
        $this->CI->load->library('LDB/searcher/MatchedResponseRequestGenerator');
        $this->requestGenerator = new MatchedResponseRequestGenerator;
         
    }
    
    public function set($responseUserId,$responseCourseId,$responseProfile)
    {
        $this->responseUserId = $responseUserId;
        $this->responseCourseId = $responseCourseId;
        $this->responseProfile = $responseProfile;
    }
    
    /**
     * Fetch genies matched to specified user
     * and courses the user has made responses for
     */ 
    public function getMatchedGenies($responseUserId,$responseCourseId,$responseProfile)
    {
        $this->set($responseUserId,$responseCourseId,$responseProfile);
        
        /**
         * Get matching lead genies
         */
        $matchedGenies = array();
        
        /**
         * First apply the course match
         * The courses on which responses has been made must appear
         * as also viewed of seed courses set in genies
         * with proper quality threshold
         */ 

         $matchedGeniesData[] = $this->_applyCourseMatch();
         

         foreach ($matchedGeniesData as $key => $value) {
             foreach ($value as $key1 => $value1) {
                unset($value1['course_id']);
                $matchedGeniesTemp[$key][$key1] = $value1;
             }
         }

         $i = 0;
         foreach($matchedGeniesTemp as $result) {
                foreach ($result as $key => $value) {
                   $matchedGenies[$i][$key] = $value['searchAlertId'];
                }
                $i++;
         }

        
        /**
         * No need to process further if there are no
         * matching genies here
         */ 
        if(count($matchedGenies[0]) == 0) {
            return array();    
        }
        
        /**
         * Apply other filters set duing genie creation
         * Exam match
         * Current Location Match
         * Preffered location match
         */
        
        $searchCriteria = $this->responseProfile;
        $searchCriteria['includeSearchAgent'] = $matchedGenies[0];

        $searchCriteria['excludeSearchAgent'] = $this->fetchGenieWithQuotaReached();

	$request = $this->requestGenerator->generateSearchRequest($searchCriteria);
        $result = $this->makeSolrRequest($request);
        
        $matchedGenies = array();
        $matchedAgentGroup = $result['grouped']['clientId']['groups'];               
        foreach ($matchedAgentGroup as $matchedAgent) {
            $matchedGenies[] = $matchedAgent['doclist']['docs'][0]['SearchAgentId'];
        }
            
        /**
         * Compute intersection of all matches
         * to get final list of matched genies
         */
        //$finalMatchedGenies = call_user_func_array('array_intersect',$matchedGenies);
        
        $returnArray = array();
        $returnArray['finalMatchedGenies'] = $matchedGenies;
        $returnArray['matchedFor'] = $matchedGeniesData;


        return $returnArray;
    }
    
    /**
     * Apply response course match
     * This fetches genies matching courses in responses
     */ 
    private function _applyCourseMatch()
    {   
        $matching_with_redis = false;

        $redis_key ='sa_mr_courses_'.$this->responseCourseId;
        $this->redisLib = PredisLibrary::getInstance();
        $redis_set_data = $this->redisLib->getMembersOfSet($redis_key);

        foreach ($redis_set_data as $temp) {
            $redis_set_data_decoded                                     = json_decode($temp,true);
            $redis_matched_genie[$redis_set_data_decoded['sa_id']]      = $redis_set_data_decoded['sa_id'];
            $redis_return_data[]                                        = array('searchAlertId'=>$redis_set_data_decoded['sa_id'],'course_id'=>$redis_set_data_decoded['c_id']);
        }


        $db_data =  $this->model->getGeniesMatchingResponseCourse($this->responseCourseId);
        foreach ($db_data as $temp) {
            $db_matched_genie[$temp['searchAlertId']] = $temp['searchAlertId'];
        }

        $diff_array_redis   = array_diff($redis_matched_genie, $db_matched_genie);
        $diff_array_db      = array_diff($db_matched_genie, $redis_matched_genie);

        if(count($diff_array_redis)>0){
           // mail('ajay.sharma@shiksha.com', 'MR matching - Redis has more data', $redis_key);
        }

        if(count($diff_array_db)>0){
            //mail('ajay.sharma@shiksha.com', 'MR matching - Database has more data', $redis_key);   
        }

        if($matching_with_redis){
            return $redis_return_data;
        }else{
            return $db_data;
        }

        //return $this->model->getGeniesMatchingResponseCourse($this->responseCourseId);
    }
    
    private function makeSolrRequest($request){
        $this->CI->load->builder('SearchBuilder','search');
        $this->solrServer = SearchBuilder::getSearchServer();

        $request_array = explode("?",$request); 
        $response = $this->solrServer->leadSearchCurl($request_array[0],$request_array[1]); 
        $response = unserialize($response);
         
        return $response;        
    }
    
    /**
     * Apply preferred MR location match
     */ 
    private function _applyPreferredMRLocationMatch()
    {
        $responseLocations = $this->responseProfile['responseLocations'];
        return $this->model->getGeniesMatchingPreferredMRLocation($responseLocations);
    }

    private function fetchGenieWithQuotaReached(){
        $this->CI->load->model('search_agent_main_model');
        $saModel = new Search_agent_main_model;

        $genieIds = $saModel->fetchGenieWithQuotaReached();

        return $genieIds;
    }
}
