<?php

class responseProcessorLib{
	private $queryGenerator;
    private $responseParser;
	private $elasticSearchLib;
	function __construct($useNewES) {
        $this->CI             = & get_instance();           
		$this->elasticFlag    = (USE_ELASTIC_SEARCH && true);
        $this->responseProcessorModel = '';

        if($this->elasticFlag){
            $this->queryGenerator   = $this->CI->load->library("response/responseProcessorElasticQueryGenerator");
            $this->responseParser   = $this->CI->load->library("response/responseProcessorElasticParser");
            $ESConnectionLib = $this->CI->load->library('trackingMIS/elasticSearch/ESConnectionLib');
            if($useNewES === true){
                $this->ESClientConn = $ESConnectionLib->getESServerConnectionWithCredentials();
            }else{
                $this->ESClientConn = $ESConnectionLib->getShikshaESServerConnection();
            }
            $this->checkIfElasticsearchRunning();
        }else{
            $this->responseProcessorModel = $this->CI->load->model('response/responseprocessormodel');
        }
    }

	function checkIfElasticsearchRunning(){
        if(!($this->ESClientConn->ping() == true)){
            usleep(100000);
            if(!($this->ESClientConn->ping() == true)){
                $this->elasticFlag = false;
                $this->responseProcessorModel = $this->CI->load->model('response/responseprocessormodel');
                // send mail
                mail('naveen.bhola@shiksha.com,praveen.singhal@99acres.com',"Elasticsearch is not running", "Elasticsearch is not running on 10.10.16.91:9200");
            }
        }
    }

    function getResponseCount($entityIds,$userLocationIds,$dateRange,$entityType='exam'){
    	if($this->elasticFlag){
            //elastic
	    	$queryParams = $this->queryGenerator->prepareResponseQuery($entityIds,$userLocationIds,$dateRange,$entityType,0);
            $response    =  $this->ESClientConn->search($queryParams);
	    	$result      = $this->responseParser->getResponseCount($response);
    		return $result;	
    	}else{
            $result = $this->responseProcessorModel->getResponseCount($entityIds,$userLocationIds,$dateRange,$entityType);
            return $result['0']['count'];
        }
    }

    function getListingResponseCountByClientId($clientId,$courses){
        if($this->elasticFlag){
            //elastic
            $queryParams = $this->queryGenerator->fetchListingResCountByClientId($clientId,$courses);
            $response    = $this->ESClientConn->search($queryParams);
            $result      = $this->responseParser->parseListingResCountByClientId($response);
            return $result;
        }
    }

    function getResponsesForListingId($clientId,$listingId,$listingType,$searchCriteria,$timeInterval,$start,$count,$locationId,$startDate,$endDate,$courseList = array(),$responseIds = ''){
        // _p($clientId);
        // _p($listingId);
        // _p($listingType);
        // _p($searchCriteria);
        // _p($timeInterval);
        // _p($start);
        // _p($count);
        // _p($locationId);
        // _p($courseList);
        // die;
        if($this->elasticFlag){
            //elastic
            $queryParams = $this->queryGenerator->fetchResponseByListingId($clientId,$listingId,$listingType,$timeInterval,$start,$count,$startDate,$endDate,$locationId,$courseList,$responseIds);
            // _p($queryParams);die;
            $response    = $this->ESClientConn->search($queryParams);
            $result      = $this->responseParser->parseResponseByListingId($response,$this->ESClientConn);
            return $result;
        }
    }

    // function not in used.
    function getResponses($entityIds,$userLocationIds,$dateRange,$entityType='course',$limit=10,$from=0){
        mail("teamldb@shiksha.com", "Not in used function called", "Function : getResponses");
        return;
    	if($this->elasticFlag){
	    	$queryParams = $this->queryGenerator->prepareResponseQuery($entityIds,$userLocationIds,$dateRange,$entityType,$limit,$from);
            $response    =  $this->elasticSearchLib->call($queryParams);
	    	$result      = $this->responseParser->parseResponse($response);
    		return $result;	
    	}
    }
}
