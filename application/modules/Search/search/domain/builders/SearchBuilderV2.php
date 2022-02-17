<?php

class SearchBuilderV2
{
    private $CI;
    private $request;
    
    function __construct()
    {
        $this->CI = & get_instance();
        $this->CI->load->library('search/SearchV2/searchPageRequest');
        $this->request = new SearchPageRequest();
    }
    
    public function getRequest()
    {
        return $this->request;
    }

    public function getSearchPage(){
        $searchRepo = $this->getSearchRepository();
        $this->CI->load->domainClass('SearchPage','search');

        $this->CI->load->builder('ListingBuilder','listing');
        $listingBuilder = new ListingBuilder;
        $instituteRepository = $listingBuilder->getInstituteRepository();

        $this->CI->load->builder('LocationBuilder','location');
        $locationBuilder = new LocationBuilder;
        $locationRepository = $locationBuilder->getLocationRepository();
        
        $this->CI->load->builder('CategoryBuilder','categoryList');
        $categoryBuilder = new CategoryBuilder;
        $categoryRepository = $categoryBuilder->getCategoryRepository();
        
        $this->CI->load->builder('LDBCourseBuilder','LDB');
        $LDBCourseBuilder = new LDBCourseBuilder;
        $LDBCourseRepository = $LDBCourseBuilder->getLDBCourseRepository();

        $searchPage = new SearchPage($this->request,$searchRepo,$instituteRepository,$locationRepository,$categoryRepository,$LDBCourseRepository);
        return $searchPage;
    }

    public function getSearchRepository(){

        $this->CI->load->library('search/Searcher/SolrSearcher');
        $solrSearcher = new SolrSearcher;

        $this->CI->load->library('search/Solr/AutoSuggestorSolrClient');
        $autoSuggestorSolrClient = new AutoSuggestorSolrClient;

        $this->CI->load->builder('CategoryBuilder','categoryList');
        $categoryBuilder = new CategoryBuilder;
        $categoryRepository = $categoryBuilder->getCategoryRepository();

        $this->CI->load->repository('SearchRepositoryV2','search');
        $searchPageRepo = new SearchRepositoryV2($this->request, $solrSearcher, $autoSuggestorSolrClient, $categoryRepository);
        return $searchPageRepo;
    }
}