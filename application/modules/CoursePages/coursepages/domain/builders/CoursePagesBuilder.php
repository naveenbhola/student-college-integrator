<?php

class CoursePagesBuilder
{
    private $CI;
    
    function __construct()
    {
        $this->CI = &get_instance();    
    }
    
    public function getCoursePagesRepository()
    {
        /*
         * Load dependencies for Course Page Repository
         */

	$this->CI->load->model('coursepages/coursepagemodel','',TRUE);
	$dao = $this->CI->coursepagemodel;
	
        $this->CI->load->library('coursepages/cache/CoursePagesCache');
        $cache = $this->CI->coursepagescache;
	
        /*
         * Load the repository
         */ 
        $this->CI->load->repository('CoursePagesRepository','coursepages');        
	$coursePagesRepository = new CoursePagesRepository($dao, $cache);
        return $coursePagesRepository;
    }
}
