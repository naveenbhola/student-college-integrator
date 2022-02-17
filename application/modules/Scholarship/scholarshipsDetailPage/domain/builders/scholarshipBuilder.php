<?php

class scholarshipBuilder
{
    private $CI;
    
    function __construct()
    {
        $this->CI = &get_instance();    
    }
    
    public function getScholarshipRepository()
    {
        /*
         * Load dependencies for scholarship Repository
         */
        
        $dao = $this->CI->load->model('scholarshipsDetailPage/scholarshipModel');
        $cache = $this->CI->load->library('scholarshipsDetailPage/cache/scholarshipCache');
        /*
         * Load the repository
         */ 
        $this->CI->load->repository('scholarshipsDetailPage/scholarshipRepository');
        $scholarshipRepository = new scholarshipRepository($dao,$cache);
        return $scholarshipRepository;
    }
    
}