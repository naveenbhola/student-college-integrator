<?php

class MatchedResponseAgent extends MX_Controller
{
    private $model;
    private $ldbClient;
    private $matchedResponseProcessor;
    const   WAITPERIOD = 30;
    
    function __construct()
    {
        $this->load->model('searchAgents/matchedresponseagentmodel');
        $this->model = new MatchedResponseAgentModel;
                
        $this->load->library('LDB_Client');
        $this->ldbClient = new LDB_Client;
        
        $this->load->library('searchAgents/MatchedResponse/MatchedResponseProcessor');
        $this->matchedResponseProcessor = new MatchedResponseProcessor;
    }
    
    public function processNonMR(){
        return; //puting return since this cron is not active now
		$this->validateCron();
        $waitPeriod = self::WAITPERIOD;        
        $responses = $this->model->getNonMRUnprocessedResponses($waitPeriod);
        
        if(count($responses) == 0) {
            exit();    
        }
        
        foreach($responses as $responseId) {
            $this->model->setResponseProcessed($responseId['responseId']);
        }
        
    }

    public function run() {
        $this->validateCron();
        $waitPeriod = self::WAITPERIOD;
        $responses = $this->model->getUnprocessedResponses($waitPeriod);
        
        /**
         * No responses to process
         */
        if(count($responses) == 0) {
            exit();    
        }
        
        $responseProfiles = $this->_getResponseProfiles($responses);        
        
        foreach($responses as $response) {
            
            $responseId = $response['responseId'];
            $responseUserId = $response['userId'];
            $responseCourseId = $response['courseId'];
            $responseCourseName = $response['courseName'];
            $responseProfile = $responseProfiles[$responseUserId];
            
            if(is_array($responseProfile) && count($responseProfile)>0 ){
                $this->matchedResponseProcessor->process($responseUserId,$responseCourseId,$responseProfile,$responseCourseName);
            }                       
            
            $this->model->setResponseProcessed($responseId);
        }
        
    }
    
    /**
     * Get complete profiles for all the users in responses
     */ 
    private function _getResponseProfiles($responses)
    {
        
        $userStreamMap= array();
        $responseUserIds = array();

        foreach($responses as $response) {
            $responseUserIds[$response['userId']] = $response['userId'];
            $userStreamMap[$response['userId']] = $response['streamId'];
        }
       
        $this->load->library('LDB/searcher/LeadSearcher');
        $leadSearcher = new LeadSearcher;
        $searchParams = array();
        $searchParams['ProfileType'] = "implicit";
        $searchParams['userdataonly'] = true;
        //$searchParams['grouping'] =  true;
        $responseProfiles = $leadSearcher->getUserDetailsSolr($responseUserIds,$searchParams);
        
        $final_profiles = array();
        foreach($responseProfiles as $user_id=>$value) {
       
    		$display_data = json_decode($value['displayData'],true);
    		$affinity_city_id_mapping =array();								
    		if($display_data['city']>0) {
    			$final_profiles[$user_id]['CurrentLocation'][] = $display_data['city']; 					
    		}	
    				
                    foreach($value as $key=>$data) {
    			if(strpos($key,"location_affinity_") !==FALSE) {
    				$affinity_city_id_array = explode("location_affinity_",$key);
    				$affinity_city_id = $affinity_city_id_array[1];
    				$affinity_city_id_mapping[$affinity_city_id] = $data;
    			}
    		}				

    	 	$final_profiles[$user_id]['SAPreferedMRCity'] = $affinity_city_id_mapping;				
    		$final_profiles[$user_id]['ExamScore'] =  $value['educationName']; 
            $final_profiles[$user_id]['StreamId'] =  $userStreamMap[$user_id];
		}
		
        return $final_profiles;
    }

    function cronMatchingGenieForAll(){
        $this->model->markOldGenieHistory();

        $this->cronMatchingGenie('daily');

        $this->model->deleteHistoryGenie();        
    }


    function cronMatchingGenie($cronFrequency = 'hourly'){
        return;
        $this->validateCron();
        $genieData = $this->model->getUnmatchedGenie($cronFrequency);

        if(count($genieData) > 0){
            foreach ($genieData as $key => $genie) {
                $genieID = $genie['searchAlertId'];     //check for key names
                $courseId = $genie['genieCourse'];

                $firstLevelAlsoViewed = array();
                $firstLevelAlsoViewed = $this->model->getFirstAlsoViewedCourses($courseId);

                if(count($firstLevelAlsoViewed) == 0){
                    continue;
                }

                $str = array();
                foreach ($firstLevelAlsoViewed as $value) {
                    $str[] = $value['recommended_course_id'];
                }
                $inClause = implode(',',$str );

                $secondLevelAlsoViewed = array();
                $secondLevelAlsoViewed = $this->model->getSecondAlsoViewedCourses($inClause);     
                     
                $alsoViewedCourses = $this->getAllUniqueAlsoViewed($firstLevelAlsoViewed,$secondLevelAlsoViewed); 

                $batchData = $this->computeBatchData($genieID,$courseId,$alsoViewedCourses);

                $this->model->insertBatchData($batchData);
            }
        }

    }

    function computeBatchData($genieID,$courseId,$alsoViewedCourses){
        $batchArray = array();

        foreach ($alsoViewedCourses as $key => $value) {
            $alsoViewedCourses[$key]['alsoViewedCourse'] = $value['recommended_course_id'];
            $alsoViewedCourses[$key]['searchAlertId'] = $genieID;
            $alsoViewedCourses[$key]['genieCourse'] = $courseId;
            unset($alsoViewedCourses[$key]['recommended_course_id']);
        }
        return $alsoViewedCourses;
    }

    function getAllUniqueAlsoViewed($firstLevelAlsoViewed,$secondLevelAlsoViewed){
        $alsoViewedCourses = array();


        foreach ($firstLevelAlsoViewed as $value) {
            $alsoViewedCourses[] = $value['recommended_course_id'];
        }

        foreach ($secondLevelAlsoViewed as $value) {
            $alsoViewedCourses[] = $value['recommended_course_id'];
        }

        $alsoViewedCourses = array_unique($alsoViewedCourses);

        $i=0;
        foreach ($alsoViewedCourses as $key => $value) {
            $uniqueAlsoViewedCourses[$i++]['recommended_course_id'] = $value;
        }

        return $uniqueAlsoViewedCourses;
    }

}
