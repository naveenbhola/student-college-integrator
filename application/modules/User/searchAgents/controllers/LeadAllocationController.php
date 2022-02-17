<?php 

class LeadAllocationController extends MX_Controller {

	private $matchingParams = array('stream','subStream','specializaton','mode','courseId','exams','location');

	function pickUnalloctedInterest(){

		$allocationModel = $this->load->model('leadAllocationModel');

		$leads= $allocationModel->pickUnallocatedInterest();

        $this->load->library('LDB/searcher/SearchAgentRequestGenerator');
        $this->requestGenerator = new SearchAgentRequestGenerator;

		foreach ($leads as $userId) {
            $userProfile = $this->getUserProfileForAllocation($userId['userId'],$userId['ExtraFlag']);

            foreach ($userProfile as $profile) {
                $searchAgent = array();

                //$profile['workex'] = 4;  //testing only
                $request = $this->requestGenerator->generateSearchRequest($profile);
                
                $result = $this->makeSolrRequest($request);
                
                $matchedAgentGroup = $result['grouped']['clientId']['groups'];

                foreach ($matchedAgentGroup as $matchedAgent) {
                    $searchAgent[] = $matchedAgent['doclist']['docs'][0];
                }

               
                $this->insertInMatchingLog($userId['userId'],$searchAgent);
                _P($searchAgent);
                die;
            }
		}
	}

    public function makeSolrRequest($request){
        $this->load->builder('SearchBuilder','search');
        $this->solrServer = SearchBuilder::getSearchServer();

        $request_array = explode("?",$request); 
        $response = $this->solrServer->leadSearchCurl($request_array[0],$request_array[1]); 
        $response = unserialize($response);
        
        return $response;        
    }

    public function getUserProfileForAllocation($userId, $extraFlag){

        $userId = 5883124;          //testing purpose only

        $request = $this->generateRequest($userId, $extraFlag);        

        $response = $this->makeSolrRequest($request);
        $docs = $response['response']['docs'];
        
        return $docs;
    }

    public function generateRequest($userId, $extraFlag){
        $request = SOLR_LDB_SEARCH_SELECT_URL_BASE;
        $request .= '?q=*%3A*&wt=phps&';
               
        $request .='&fq=userId:'.$userId;
        

        if($extraFlag == 'National'){
           $request .= '&fl=userId,displayData,streamId,subStreamId,specialization,attributeValues,courseId,ViewCredit,workex&sort=ViewCredit+desc';
        }

        if($extraFlag == 'StudyAbroad'){
           $request .= '&fl=userId,desiredCourse,abroad_subcat_id,specializationId,educationName,UGCompletionDate,UGMarks,city,locality,passport,YearOfStart,submitDate,workex&sort=submitDate+desc';
        }

        return $request;
    }

    public function insertInMatchingLog($userId,$searchAgent){
        if(count($searchAgent) <0){
            return false;
        }

        $allocationModel = $this->load->model('leadAllocationModel');

        $final_data = array();
        foreach($searchAgent as $agentId){
                if($agentId['deliveryMethod'] != 'porting'){
                    continue;
                }

                $array = array();
                $array['leadid'] = $userId;
                $array['searchAgentid'] = $agentId['SearchAgentId'];
                $array['clientid'] = $agentId['clientId'];
                $array['matchingTime'] = date("Y-m-d H:i:s");
                $final_data[] = $array;
        }     

        $allocationModel->insertInMatchingLog($final_data);
       
    }

	/*public function tempDeleteDoc(){
       
        $i=5950953;

        while($i<6182240){
            $this->deleteUserDocument($i);
            error_log('################# no '.$i);
            $i++;
        }

    }

    
    public function deleteUserDocument($userId){
        $url ="https://172.16.2.222:8983/solr/update?stream.body=%3Cdelete%3E%3Cquery%3EuserId:".$userId."%3C/query%3E%3C/delete%3E&commit=true";

       $ch = curl_init();

        curl_setopt($ch, CURLOPT_VERBOSE, 0);

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');

        curl_setopt($ch, CURLOPT_TIMEOUT, 20);

        $result = curl_exec($ch);

        curl_close($ch);


    }


	private function _matchStreamId($streamId){
		$requestParam = array();

		$finalParam ="fq=streamId:".$streamId;

		$requestParams[] = $finalParam;
        return $requestParam;
	}

	private function _matchSubStream($subStreamId){
		$requestParams = array();
        
        $finalParam = 'fq=subStreamId:('.implode('%20OR%20', $subStreamId).')';
         
        $requestParams[] = $finalParam;
        return $requestParams;
	}

	private function _matchSpeclizationRequest($specializationId){
        $requestParams = array();
        
        $finalParam = 'fq=specialization:('.implode('%20OR%20', $specializationId).')';

        $requestParams[] = $finalParam;
        return $requestParams;
    }*/



}


?>