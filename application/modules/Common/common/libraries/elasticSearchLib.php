<?php

require_once('vendor/autoload.php');
class elasticSearchLib{
	function __construct() {
		$this->CI                    = & get_instance();  
		$this->clientParams['hosts'] = array(ELASTIC_SEARCH_HOST);
		$this->client                = new Elasticsearch\Client($this->clientParams);   
    }

    function call($queryParams){
    	$response = $this->client->search($queryParams);
    	return $response;
    }
}