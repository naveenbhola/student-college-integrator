<?php

class InstituteBuilder
{
    private $CI;
    
    function __construct()
    {
        $this->CI = &get_instance();    
    }
    
    public function getInstituteRepository()
    {
        /*
         * Load dependencies for Institute Repository
         */
        $this->CI->load->model('nationalInstitute/institutemodel','',TRUE);
		$dao = $this->CI->institutemodel;
        
        $this->CI->load->library('nationalInstitute/cache/NationalInstituteCache');  
        $cache = $this->CI->nationalinstitutecache;
        
        /*
         * Load the repository
         */ 
        $this->CI->load->repository('NationalInstituteRepositoryAbstract','nationalInstitute'); 
        $this->CI->load->repository('InstituteRepository','nationalInstitute'); 
        $this->CI->load->helper("shikshautility");
//        $this->caching = false;
        
        $instituteRepository = new InstituteRepository($dao,$cache);
        return $instituteRepository;
    }
}