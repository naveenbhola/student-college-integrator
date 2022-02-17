<?php

class SASearchBuilder
{
    private $CI;
    private $request;
    
    function __construct($request = null)
    {
        $this->CI = & get_instance();
        if($request != null){
            $this->request = $request;
        }else{
            $this->CI->load->library('SASearch/SASearchPageRequest');
            $this->request = new SASearchPageRequest();
        }
    }
    
    public function getRequest()
    {
        return $this->request;
    }

    public function getSearchPage(){        
        $searchRepo = $this->getSearchRepository();
        $this->CI->load->domainClass('SASearchPage','SASearch');
        $searchPage = new SASearchPage($this->request,$searchRepo);
        return $searchPage;
    }

    public function getSearchRepository(){

        $this->CI->load->library('SASearch/AutoSuggestorSolrClient');
        $autoSuggestorSolrClient = new AutoSuggestorSolrClient;

        $this->CI->load->repository('SASearchRepository','SASearch');
        $searchPageRepo = new SASearchRepository($this->request, $autoSuggestorSolrClient);
        return $searchPageRepo;
    }
}