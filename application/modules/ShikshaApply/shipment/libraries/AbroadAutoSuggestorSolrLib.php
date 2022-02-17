<?php

class AbroadAutoSuggestorSolrLib{

	function __construct(){
		$this->CI = &get_instance();
		$this->curlLib = $this->CI->load->library('common/CustomCurl');
		$this->solrRequestGenerator = $this->CI->load->library('SASearch/AutoSuggestorSolrRequestGenerator');
	}

	public function getUniversitySuggestions($text, $resultCount){
		$requestData['text'] = $text;
		$requestData['eachfacetResultCount'] = $resultCount;
		$solrUrl = $this->solrRequestGenerator->generateUnivAutoSuggestionUrl($requestData);
		_p($solrUrl);
		$this->curlLib->setIsRequestToSolr(1);
		$solrContent = unserialize($this->curlLib->curl($solrUrl)->getResult());
		return $solrContent;
	}

	public function getCourseSuggestions($text, $resultCount){
		$requestData['text'] = $text;
		$requestData['eachfacetResultCount'] = $resultCount;
		$solrUrl = $this->solrRequestGenerator->generateCourseAutoSuggestionUrl($requestData);
		$this->curlLib->setIsRequestToSolr(1);
		$solrContent = unserialize($this->curlLib->curl($solrUrl)->getResult());
		return $solrContent;
	}

}