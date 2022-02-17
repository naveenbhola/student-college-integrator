<?php

class CrModerationBuilder{

	private $CI;
    private $request;
    
    function __construct(){
        $this->CI = &get_instance();    
        $this->request = $this->CI->load->library('CAEnterprise/CrModerationPageRequest');
    }
    
    public function getRequest(){
        return $this->request;
    }
    

    public function getCrModerationRepository() {
    	$this->CI->load->domainClass('CrModeration','CAEnterprise');
    	
    	// $this->CI->load->model('CAEnterprise/reviewenterprisemodel','',TRUE);
     //    $dao = $this->CI->reviewenterprisemodel;

        
        $crModerationSolrClient = $this->getCrModerationSolrClientRepo();

    	$crModerationObj = new CrModeration($this->request,$crModerationSolrClient);
    	return $crModerationObj;
    }

    public function getCrModerationSolrClientRepo(){
        $this->CI->load->builder('SearchBuilder','search');
        $solrServer = SearchBuilder::getSearchServer();

    	$this->CI->load->library('CAEnterprise/CrModerationSolrRequestGenerator');
        $crModerationSolrRequestGenerator = new CrModerationSolrRequestGenerator();

        $this->CI->load->library('CAEnterprise/CrModerationSolrResponseParser');
        $crModerationSolrResponseParser = new CrModerationSolrResponseParser();

        $this->CI->load->library('CAEnterprise/CrModerationSolrClient');
        $crModerationSolrClient = new CrModerationSolrClient($solrServer,$crModerationSolrRequestGenerator,$crModerationSolrResponseParser,$this->request);

        return $crModerationSolrClient;
    }
}