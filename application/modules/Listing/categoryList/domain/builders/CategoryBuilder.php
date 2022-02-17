<?php

class CategoryBuilder
{
    private $CI;
    
    function __construct()
    {
        $this->CI = & get_instance();    
    }
    
    public function getCategoryRepository()
    {   	
        /*
         * Load dependencies for Category Repository
         */
        $this->CI->load->library('categoryList/clients/CategoryClient');
        $dao = $this->CI->categoryclient;
        
        $this->CI->load->library('categoryList/cache/CategoryCache');
        $cache = $this->CI->categorycache;
        
        /*
         * Load the reppository
         */ 
        
        $this->CI->load->repository('CategoryRepository','categoryList');

        $categoryRepository = new CategoryRepository($dao,$cache);
                
        return $categoryRepository;
    }
}