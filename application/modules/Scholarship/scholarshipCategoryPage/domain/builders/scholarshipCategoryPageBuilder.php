<?php

class scholarshipCategoryPageBuilder
{
    private $CI;
    function __construct()
    {
        $this->CI = &get_instance();    
    }
    
    public function getScholarshipCategoryPageRepository($requestData)
    {
        /*
         * Load dependencies for Repository
         */
        
        
        /*
         * Load the repository
         */ 
        $this->CI->load->repository('scholarshipCategoryPage/scholarshipCategoryPageRepository');
        $solrRequestGenerator = $this->CI->load->library('scholarshipCategoryPage/solr/scholarshipCategoryPageSolrRequestGenerator');
        $solrResponseParser = $this->CI->load->library('scholarshipCategoryPage/solr/scholarshipCategoryPageSolrResponseParser');
        $scholarshipCategoryPageRepository = new scholarshipCategoryPageRepository($requestData,$solrRequestGenerator,$solrResponseParser);
        return $scholarshipCategoryPageRepository;
    }
    
}