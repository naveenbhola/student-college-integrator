<?php

class CrModerationSolrClient{
	private $requestGenerator;
	private $responseParser;
	private $request;
	private $solrServer;

	function __construct($solrServer,$crModerationSolrRequestGenerator,$crModerationSolrResponseParser,$request){
		$this->requestGenerator  = $crModerationSolrRequestGenerator;
		$this->responseParser    = $crModerationSolrResponseParser; 
		$this->request           = $request; 
		$this->solrServer        = $solrServer; 
    }

    function getCollegeReviewData(){
		$solrRequest = $this->requestGenerator->generate($this->request);
		
		//error_log("AMAN College review SOlr".print_r($solrRequest,true));
		$response    = $this->solrServer->curl($solrRequest);

    	$data = $this->responseParser->parseCollegeReviewData($response);
    	return $data;
    }
}