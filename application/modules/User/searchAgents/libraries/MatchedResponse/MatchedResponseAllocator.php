<?php

class MatchedResponseAllocator
{
    private $CI;
    
    private $userId;
    private $genies;
    private $genieData;
    
    private $model;
    
    function __construct()
    {
        $this->CI = & get_instance();
        
        $this->CI->load->model('searchAgents/matchedresponseagentmodel');
        $this->model = new MatchedResponseAgentModel;
    }
    
    public function allocate($userId,$genies,$genieData,$responseTime,$matchedFor)
    {    
        if(!is_array($genies) || count($genies) == 0) {
            return TRUE;
        }
                
        $this->model->allocateToGenies($userId,$genies,$genieData, $responseTime,$matchedFor);
        $this->model->updateGeniesLeftOverStatus($genies,$genieData);
    }
}