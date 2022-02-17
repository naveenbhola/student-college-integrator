<?php

class MatchedResponseGenieFilterer
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
    
    public function set($userId,$genies,$genieData)
    {
        $this->userId = $userId;
        $this->genies = $genies;
        $this->genieData = $genieData;
    }
    
    public function filter($userId,$genies,$genieData,$userDataArray)
    {
        $this->set($userId,$genies,$genieData);
        
        $genies = $this->filterGeniesWithInsufficientCredits($genies,$genieData,$userDataArray);
      

        if(!is_array($genies) || count($genies) == 0) {
            return array();
        }
        
        /**
         * Remove porting genies as we these don't participate
         * in normal delivery
         */ 
        $genies = $this->filterPortingGenies($genies);

        if(count($genies) == 0) {
            return array();
        }
        

        /**
         * Remove disabled genies
         * i.e. genies who haven't set both auto-download and auto-responder
         */ 
        $genies = $this->filterDisabledGenies($genies);
        if(count($genies) == 0) {
            return array();
        }
        
        /**
         * Remove genies to whom the current response
         * has already been allocated
         */ 
        $genies = $this->filterAlreadyAllocatedGenies($genies);
        if(count($genies) == 0) {
            return array();
        }  
        
        return $genies;
    }
    
    public function filterPortingGenies($genies)
    {
        return $this->model->getNormalDeliveryGenies($genies);
    }
    
    public function filterDisabledGenies($genies)
    {
        $filteredGenies = array();
        foreach($genies as $genie) {
            if($this->genieData[$genie]['autoDownload'] || $this->genieData[$genie]['autoResponderSMS'] || $this->genieData[$genie]['autoResponderEmail']) {
                $filteredGenies[] = $genie;
            }
        }
        
        return $filteredGenies;
    }
        
    public function filterAlreadyAllocatedGenies($genies)
    {
        /**
         * Fetch all the clients to whom this user has already been allocated
         */ 
        $allocatedClients = $this->model->getAllocatedClients($this->userId);
    
        /**
         * Remove all genies whose owner (client) has already received this user
         */ 
        $filteredGenies = array();
        foreach($genies as $genie) {
            if(!in_array($this->genieData[$genie]['client'],$allocatedClients)) {
                $filteredGenies[] = $genie;
            }
        }
        
        return $filteredGenies;
    }
    
    public function filterMultipleGeniesOfSameClient($genies)
    {
        $deduplicatedGenies = array();
        
        $clientsIncluded = array();
        foreach($genies as $genie) {
            if(!$clientsIncluded[$this->genieData[$genie]['client']]) {
                $deduplicatedGenies[] = $genie;
                $clientsIncluded[$this->genieData[$genie]['client']] = TRUE;
            }    
        }
        
        return $deduplicatedGenies;
    }

    public function filterGeniesWithInsufficientCredits($genies,$genieData,$userDataArray){

        if(empty($genieData)){
            return array();
        }
        global $MRPricingArray;
        $streamId = $userDataArray[0]['StreamId'];

        $pricePerLead = $MRPricingArray[$streamId]['view'];
        /*$pricePerEmail = $MRPricingArray[$streamId]['email'];
        $pricePerSMS = $MRPricingArray[$streamId]['SMS'];*/

        foreach ($genieData as $agentId => $data) {
            $clientId[] = $data['client'];
            $clientIdMap[$data['client']] =$agentId;
        }

        $priceToCheck = $pricePerLead;

        $allocationModel = $this->CI->load->model('leadAllocationModel');
        $validClients = $allocationModel->getClientWithSuffienctCredits($clientId,$priceToCheck);

        foreach ($validClients as $clientId) {
            $validGenies[] = $clientIdMap[$clientId['ClientUserId']];
        }

        $validGenies = array_unique($validGenies);

        return  $validGenies;
        
    }
}