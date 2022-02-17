<?php

class LeadSearcher
{
    private $CI;
    private $solrServer;
    private $requestGenerator;
    
    private $leadSearchModel;
    
    function __construct()
    {
        $this->CI = & get_instance();
        
        $this->CI->load->builder('SearchBuilder','search');
        $this->solrServer = SearchBuilder::getSearchServer();
        
        $this->CI->load->library('LDB/searcher/LeadSearchRequestGenerator');
        $this->requestGenerator = new LeadSearchRequestGenerator;
        
        $this->CI->load->model('LDB/leadsearchmodel');
        $this->leadSearchModel = new LeadSearchModel;
    }
    
    public function search($searchCriteria,$isMMM=FALSE)  {           

        $leads = array();

        if($isMMM){

            if($searchCriteria['ExtraFlag'] == 'studyabroad'){
                $searchCriteria['includeExcludedUsersType'] = true;
            }
            $searchCriteria['DontShowViewed'] = true;
            $searchCriteria['isMMMSearch'] = true;
            $searchCriteria['email_pref_5'] = true;

            if(!$searchCriteria['countFlag']) {     // Fetching users while sending mail via cron

                if($searchCriteria['totalMailsToBeSent'] > 0) {
                    $leads = $this->getUsersBySearchCriteriaMMMChunkProcess($searchCriteria);
                } else {
                    $leads = $this->getUsersBySearchCriteriaMMM($searchCriteria);
                }

                return $leads;

            } else {                               // Fetching no. of users while scheduling mailers

                $request = $this->requestGenerator->generate($searchCriteria);

                $request_array = explode("?",$request); 

                $response = $this->solrServer->MMMSearchCurl($request_array[0], $request_array[1]);

                $response = unserialize($response);          

                if($searchCriteria['responseField'] == 'matches') {
                    return $response['grouped']['userId']['matches'];
                } else {
                    return $response['grouped']['userId']['ngroups'];
                }
      
            }        

        }else{                                      // Fetching users in LDB Search

            $searchCriteria['isLDBFlag'] = 'YES';
            $leads = $this->getUsersBySearchCriteriaLDBSearch($searchCriteria);

            if($searchCriteria['ExtraFlag'] == 'studyabroad'){
                //return $leads['leadData'];
            }

            return $leads;
   
        }
		
    }
    
    /**
     * Get Users By Searching Criteria In MMM mailers By Chunking Process
     */ 
    private function getUsersBySearchCriteriaMMMChunkProcess($searchCriteria) {        

        if($searchCriteria['totalMailsToBeSent'] < 0) {
            return array();
        }

        $leads = array();$request = '';$nextCursorMark = '*';

        $usersChunkSizeInMMMSolrRequest = USERS_CHUNK_SIZE_IN_MMM_SOLR_REQUEST;
        $noOfSOLRRequestTries = NO_OF_SOLR_REQUEST_TRIES;

        $searchCriteria['count'] = $usersChunkSizeInMMMSolrRequest;
        $request = $this->requestGenerator->generate($searchCriteria);

        $totalLoops = ceil($searchCriteria['totalMailsToBeSent']/$usersChunkSizeInMMMSolrRequest);
        $currentLoop = 0;
        
        while($currentLoop < $totalLoops) {

            $newRequest = ''; $response = '';$docs = array();
            
            $newRequest = $request.'&cursorMark='.$nextCursorMark;   

            $request_array = explode("?",$newRequest); 

            $response = $this->solrServer->MMMSearchCurl($request_array[0], $request_array[1]);

            // Retrying Solr Request for response
            if(!$response) {

                for($j=0; $j < $noOfSOLRRequestTries; $j++) {                            

                    $response = $this->solrServer->MMMSearchCurl($request_array[0], $request_array[1]);

                    if($response) {
                        break;
                    }
                }

            } 

            // If getting response then fetch users else return empty
            if($response) { 

                $response = unserialize($response);
                
                $nextCursorMark = urlencode($response['nextCursorMark']);

                $docs = $response['response']['docs'];

                unset($response);
                
                foreach($docs as $doc) {
                     $leads[] = $doc['userId'];
                     unset($doc);
                }

                unset($docs);
                
            } else {

                $leads = array();
                break;

            }

            $currentLoop++;
        
        }

        return $leads;
    }

     /**
     * Get Users By Searching Criteria In MMM mailers
     */ 
    private function getUsersBySearchCriteriaMMM($searchCriteria) {       

        $response = '';$docs = array();$leads = array();

        $request = $this->requestGenerator->generate($searchCriteria);
    
        $request_array = explode("?",$request); 

        $response = $this->solrServer->MMMSearchCurl($request_array[0], $request_array[1]);

        $response = unserialize($response);

        $docs = $response['response']['docs'];

        foreach($docs as $doc) {
             $leads[$doc['userId']] = $doc['userId'];
        }

        return $leads;
    }

    /**
     * Get Users By Searching Criteria In LDB Search
     */ 
    private function getUsersBySearchCriteriaLDBSearch($searchCriteria) {
        $response = '';$docs = array();$leads = array();

        $searchCriteria['countFlag'] = true;
		if($searchCriteria['DontShowViewed'] && $searchCriteria['ExtraFlag'] != 'studyabroad') {
            $viewedLeads = $this->leadSearchModel->getContactedLeadsCount($searchCriteria);
            
            foreach ($viewedLeads as $value) {
               $contactedLeads[]= $value['UserId'];
            }

            if(count($contactedLeads) >0){
                $searchCriteria['ContactedLeads'] = $contactedLeads;
            }

            unset($contactedLeads);
        }

        if(!$searchCriteria['isMRViewed']) {
            $request = $this->requestGenerator->generate($searchCriteria);      
            $request_array = explode("?",$request); 
            $response = $this->solrServer->leadSearchCurl($request_array[0],$request_array[1]); 
            $response = unserialize($response);
         
            $numFound = $response['grouped']['userId']['ngroups'];

            if($searchCriteria['getUsersCount']) {
                return $numFound;
            }
        }

        unset($users);
        unset($response);
        unset($request_array);
        unset($request);

        $searchCriteria['countFlag'] = false;

        $searchCriteria['grouping']= true;

        $request = $this->requestGenerator->generate($searchCriteria);

        $request_array = explode("?",$request); 
        $response = $this->solrServer->leadSearchCurl($request_array[0],$request_array[1]); 

        if($searchCriteria['ExtraFlag'] == 'studyabroad'){
            $response = unserialize($response);

            $docs = $response['response']['docs'];

            foreach($docs as $doc) {
                 $leads[] = $doc['userId'];
            }    
        }else{
                $response = unserialize($response);        

                $groupResult = $response['grouped']['userId']['groups'];
                
                foreach ($groupResult as $userDocs) {

                    $doc = $userDocs['doclist']['docs'];

                    $viewCredit= $doc[0]['ViewCredit'];

                    foreach ($doc as $userData) {
                        if($userData['ViewCredit'] >= $viewCredit){
                            $userMatchDoc = $userData;
                        }
                    }

                    $userIds[] = $doc[0]['userId'];
                    $resultDoc[] = $userMatchDoc;
                }

                $docs = $resultDoc;
                
                if($searchCriteria['isMR'] && count($userIds)>0){
                    $responseLocations =  $this->leadSearchModel->getResponseLocations($userIds);
                }

                foreach($docs as $doc) {
                     $leads[] = $doc['userId'];
                     $leadData[$doc['userId']]['displayData'] =$doc['displayData'];
                     $leadData[$doc['userId']]['streamId'] =$doc['streamId'];
                     $leadData[$doc['userId']]['subStreamId'] = implode(',', $doc['subStreamId']) ;
                     $leadData[$doc['userId']]['specialization'] = implode(',', $doc['specialization']) ;
                     
                     $leadData[$doc['userId']]['mode'] = implode(',', $doc['attributeValues']) ;
                     $leadData[$doc['userId']]['shikshaCourse'] = implode(',', $doc['courseId']) ;
                     $leadData[$doc['userId']]['workex'] = $doc['workex'] ;
                     
                     if($searchCriteria['isMR']){
                         global $MRPricingArray;

                         $leadData[$doc['userId']]['ResponseLocations'] = $responseLocations[$doc['userId']];

                         $leadData[$doc['userId']]['ViewCredit'] = $MRPricingArray[$doc['streamId']]['view'] ;
                         $leadData[$doc['userId']]['SmsCredit'] = $MRPricingArray[$doc['streamId']]['SMS']  ;
                         $leadData[$doc['userId']]['EmailCredit'] = $MRPricingArray[$doc['streamId']]['email']  ;
                         $leadData[$doc['userId']]['ViewCount'] = $doc['ViewCount'] ;
                     }else{
                         $leadData[$doc['userId']]['ViewCredit'] = $doc['ViewCredit'] ;
                         $leadData[$doc['userId']]['SmsCredit'] = $doc['SmsCredit'] ;
                         $leadData[$doc['userId']]['EmailCredit'] = $doc['EmailCredit'] ;
                         $leadData[$doc['userId']]['ViewCount'] = $doc['ViewCount'] ;
                     }
                     
                     if($searchCriteria['isMR']){
                        $leadData[$doc['userId']]['responseCourse'] =$doc['responseCourse'];
                        $pattern = '/^response_time_/';
                        foreach ($doc as $key => $value) {
                            if (preg_match($pattern,$key)){
                                $responseDate = explode('TO', $doc[$key]);
                                $leadData[$doc['userId']][$key] =$responseDate[0];
                                unset($responseDate);
                            }
                        }
                        
                     }
                }
            }
        
              
        if($searchCriteria['underViewedLimitFlagSet']) {
            $leads = $this->_excludeLeadOverViewLimit($leads,$searchCriteria);
        }
    
                
        if($searchCriteria['ExtraFlag'] == 'studyabroad'){  //national we have seperate search for view/sms/email

            if($searchCriteria['DontShowContacted']  || $searchCriteria['DontShowViewed'] || $searchCriteria['DontShowEmailed'] || $searchCriteria['DontShowSmsed']) {
                $leads = $this->_excludeContactedLeads($leads,$searchCriteria);
            }

            if($searchCriteria['Viewed'] || $searchCriteria['Emailed'] || $searchCriteria['Smsed']) {

                $leads = $this->_filterLeadsByContactType($leads,$searchCriteria);
            }

            return $leads;
        }

        foreach ($leads as $leadId) {
           $returnData[$leadId] = $leadData[$leadId];
        }
		
        if(!$numFound){
            $numFound = count($returnData);
        }
		$returnDataFinal['numFound'] =  $numFound;
        $returnDataFinal['leadData'] = $returnData;
        unset($returnData);
        unset($leadData);
        return $returnDataFinal;
    }

    /**
     * Exclude leads whose view count limit has reached
     */ 
    private function _excludeLeadOverViewLimit($leads,$searchCriteria)
    {
        $leadsOverViewLimit = $this->leadSearchModel->getLeadsOverViewLimit(intval($searchCriteria['groupViewLimit']));
    
        $leadsUnderViewLimit = array();
        foreach($leads as $leadId) {
            if(!$leadsOverViewLimit[$leadId]) {
                $leadsUnderViewLimit[] = $leadId;
            }
        }
        
        return $leadsUnderViewLimit;
    }
    
    /**
     * Exclude leads which have already been contacted
     */ 
    private function _excludeContactedLeads($leads,$searchCriteria)
    {
        $contactedLeads = $this->leadSearchModel->getContactedLeads($searchCriteria);
    
        $notContactedLeads = array();
        foreach($leads as $leadId) {
            if(!$contactedLeads[$leadId]) {
                $notContactedLeads[] = $leadId;
            }
        }
        
        return $notContactedLeads;
    }
    
    /**
     * Filter leads by contact type
     */ 
    private function _filterLeadsByContactType($leads,$searchCriteria)
    {
        $leadsByContactType = $this->leadSearchModel->getLeadsByContactType($searchCriteria);
    
        $filteredLeads = array();
        foreach($leads as $leadId) {
            if($leadsByContactType[$leadId]) {
                $filteredLeads[] = $leadId;
            }
        }
        
        return $filteredLeads;
    }

    //delete once done with MR as well
    public function getUserDetails($userIds,$clientId)
    {
		/**
		 * Get latest view counts for each users
		 */
        if(!empty($userIds)) {
	        $LDBViewCountArray = array();
	        $LDBViewCountArray = $this->leadSearchModel->getLeadViewCountArray($userIds);
            $LeadContactedData = $this->leadSearchModel->getLeadContactedData($userIds,$clientId);
			$responseLocations =  $this->leadSearchModel->getResponseLocations($userIds);

            $request = $this->requestGenerator->generateRequestForUserDetails($userIds);
            $response = $this->solrServer->curl($request);
            $response = unserialize($response);
            $docs = $response['response']['docs'];
            $userDetails = array();
            foreach($docs as $doc) {
                $userData = json_decode($doc['displayData'],TRUE);
                $userData['ViewCountArray'] = $LDBViewCountArray[$doc['userId']];
                //$userData['ContactData'] = $LeadContactedData[$doc['userId']];

                $userData['ContactData'] = $LeadContactedData[$doc['userId']]['ContactType'];
                //$userData['CreditConsumed'] = $LeadContactedData[$doc['userId']]['CreditConsumed'];

				$userData['ResponseLocations'] = $responseLocations[$doc['userId']];
                $userDetails[$doc['userId']] = $userData;
            }
            
            return $userDetails;
        }
    }

    public function getUserDetailsSolr($userIds, $searchCriteria){
       
        //get user details based on stream , sub stream only (profile)
        $newCriteria['stream'] = $searchCriteria['stream'];
        $newCriteria['subStream'] = $searchCriteria['subStream'];
        // $newCriteria['specializationId'] = $searchCriteria['specializationId'];
        $newCriteria['subStreamSpecializationMapping'] = $searchCriteria['subStreamSpecializationMapping'];
        $newCriteria['ProfileType'] = $searchCriteria['ProfileType'];
        $newCriteria['userdataonly'] = $searchCriteria['userdataonly'];
        $newCriteria['grouping'] = $searchCriteria['grouping'];

        if($searchCriteria['numResults']){
            $newCriteria['numResults'] = $searchCriteria['numResults'];
            $newCriteria['resultOffset'] = $searchCriteria['resultOffset'];
        }
    
        if($searchCriteria['excludeMRPRofile']){
             $newCriteria['excludeMRPRofile']= true;
        }
        
        unset($searchCriteria);
        $searchCriteria = $newCriteria;

        $searchCriteria['userChunkFlag'] = true;
        $searchCriteria['userChunk'] = $userIds;

        $request = $this->requestGenerator->generate($searchCriteria);

        $request_array = explode("?",$request); 
        $response = $this->solrServer->leadSearchCurl($request_array[0],$request_array[1]); 
        $response = unserialize($response);
        
        
        if($searchCriteria['grouping']) {
			$groups = $response['grouped']['userId']['groups'];
			foreach($groups as $group) {
					$docs[] = $group['doclist']['docs'][0];
			}
		} else {
			$docs = $response['response']['docs'];	
		}
		
        if($searchCriteria['userdataonly']) {
			foreach($docs as $doc) {							
					$leadData[$doc['userId']] = $doc;
			}	
			
		} else {
        foreach($docs as $doc) {			
             $leads[] = $doc['userId'];
             $leadData[$doc['userId']]['displayData'] =$doc['displayData'];
             $leadData[$doc['userId']]['streamId'] =$doc['streamId'];
             $leadData[$doc['userId']]['subStreamId'] = implode(',', $doc['subStreamId']) ;
             $leadData[$doc['userId']]['specialization'] = implode(',', $doc['specialization']) ;
             
             $leadData[$doc['userId']]['mode'] = implode(',', $doc['attributeValues']) ;
             $leadData[$doc['userId']]['shikshaCourse'] = implode(',', $doc['courseId']) ;
             $leadData[$doc['userId']]['workex'] = $doc['workex'] ;

             $leadData[$doc['userId']]['ViewCredit'] = $doc['ViewCredit'] ;
             $leadData[$doc['userId']]['SmsCredit'] = $doc['SmsCredit'] ;
             $leadData[$doc['userId']]['EmailCredit'] = $doc['EmailCredit'] ;
             $leadData[$doc['userId']]['ViewCount'] = $doc['ViewCount'] ;
             
        }

      }
      
      return $leadData;

    }
}
