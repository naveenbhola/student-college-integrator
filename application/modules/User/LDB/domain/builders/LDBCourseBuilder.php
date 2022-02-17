<?php

class LDBCourseBuilder
{
    private $CI;
    
    function __construct()
    {
        $this->CI = & get_instance();    
    }
    
    public function getLDBCourseRepository()
    {
        /*
         * Load dependencies for LDB Course Repository
         */
        $this->CI->load->library('LDB/clients/LDBCourseClient');
        $dao = $this->CI->ldbcourseclient;
        
        $this->CI->load->library('LDB/cache/LDBCourseCache');
        $cache = $this->CI->ldbcoursecache;
        
        /*
         * Load the reppository
         */ 
        
        $this->CI->load->repository('LDBCourseRepository','LDB');
        $LDBCourseRepository = new LDBCourseRepository($dao,$cache);	

        return $LDBCourseRepository;
    }
}