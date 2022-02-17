<?php

class MatchedResponseProcessor
{
    private $CI;
    
    private $responseUserId;
    private $responseCourseId;
    private $responseProfile;
    
    private $matcher;
    private $genieDataGenerator;
    private $filterer;
    private $allocator;
    private $creditDeductor;
    private $model;
    private $listingmodel;
    
    function __construct()
    {
        $this->CI = & get_instance();
        
        $this->CI->load->library('searchAgents/MatchedResponse/MatchedResponseMatcher');
        $this->matcher = new MatchedResponseMatcher;
        
        $this->CI->load->library('searchAgents/MatchedResponse/MatchedResponseGenieDataGenerator');
        $this->genieDataGenerator = new MatchedResponseGenieDataGenerator;
        
        $this->CI->load->library('searchAgents/MatchedResponse/MatchedResponseGenieFilterer');
        $this->filterer = new MatchedResponseGenieFilterer;
        
        $this->CI->load->library('searchAgents/MatchedResponse/MatchedResponseAllocator');
        $this->allocator = new MatchedResponseAllocator;
        
        $this->CI->load->library('searchAgents/MatchedResponse/MatchedResponseCreditDeductor');
        $this->creditDeductor = new MatchedResponseCreditDeductor;
        
        $this->CI->load->model('searchAgents/matchedresponseagentmodel');
        $this->model = new MatchedResponseAgentModel;

        $this->CI->load->model('listing/listingmodel');
        $this->listingmodel = new ListingModel;
    }
    
    public function set($responseUserId,$responseCourseId,$responseProfile)
    {
        $this->responseUserId = $responseUserId;
        $this->responseCourseId = $responseCourseId;
        $this->responseProfile = $responseProfile;
    }
    
    public function process($responseUserId,$responseCourseId,$responseProfile,$responseCourseName)
    {
        
        $this->set($responseUserId,$responseCourseId,$responseProfile);
        
        /**
         * Get matching lead genies
         */
        
        $matchedGeniesData = $this->matcher->getMatchedGenies($responseUserId,$responseCourseId,$responseProfile);
        
        $matchedGenies = $matchedGeniesData['finalMatchedGenies'];

        $matchedFor = $this->getMatchedForData($matchedGeniesData['matchedFor']);
     
        /**
         * End if no matching genies
         */ 
        if(!is_array($matchedGenies) || count($matchedGenies) == 0) {        
            return TRUE;
        }
        
        $genieData = $this->genieDataGenerator->generate($matchedGenies);
        
        $result = $this->model->getResponseTime($responseUserId,$responseCourseId);
        $responseTime = $result['submit_date'];

        $userDataArray[0]['StreamId'] = $responseProfile['StreamId'];
        
        /**
         * Log matched genies and responses
         */ 

        $this->model->logMatchedGenies($this->responseUserId,$matchedGenies,$genieData, $matchedFor, $responseProfile, $responseTime);
        
        /**
         * Filter matched genies for allocation
         */ 

        $finalGenies = $this->filterer->filter($this->responseUserId,$matchedGenies,$genieData,$userDataArray);


        $this->allocator->allocate($responseUserId,$finalGenies,$genieData, $responseTime,$matchedFor);
        
        // Commented below delivery, now delivery will be async
        //Modules::run('searchAgents/searchAgents_Server/runDeliveryCronASAP');

        // Get LDB Desired Course by client course of a user
        //$LDBCourseIDsForClientCourse = $this->listingmodel->getLDBCoursesForClientCourse($responseCourseId);

        // Get actual Desired Course by multple LDB course ids of a user
        //$actual_course_id = $this->getDesiredCourseIdByLDBCourseIDs($LDBCourseIDsForClientCourse);


        /**
         * Deduct credits
         */
        $this->creditDeductor->deductCredits($responseUserId,$finalGenies,$genieData, $actual_course_id,$userDataArray);
    }
    
    public function getDesiredCourseIdByLDBCourseIDs($LDBCourseIDsForClientCourse) {
        $actual_course_id = '';
        if(!empty($LDBCourseIDsForClientCourse)) {
            if(in_array(2, $LDBCourseIDsForClientCourse)) {
                $actual_course_id = 2;
            } else if(in_array(52, $LDBCourseIDsForClientCourse)) {
                 $actual_course_id = 52;
            }            
        }
        return $actual_course_id;
    }


    function getMatchedForData($matchedGenieData){

        $matchedForArray = array();

        foreach ($matchedGenieData as $key => $value) {
           foreach ($value as $row => $val) {
               $matchedForArray[$val['searchAlertId']] = $val['course_id'];
           }
        }

        return $matchedForArray;
    }
}
