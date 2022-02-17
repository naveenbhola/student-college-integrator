<?php

class CourseBuilder
{
    private $CI;
    
    function __construct()
    {
        $this->CI = &get_instance();    
    }
    
    public function getCourseRepository() {
        /*
         * Load dependencies for Institute Repository
         */
       /* $this->CI->load->model('nationalCourse/coursemodel','',TRUE);
		$dao = $this->CI->coursemodel;*/
        
        $this->CI->load->model('nationalCourse/nationalcoursemodel','',TRUE);
        $dao = $this->CI->nationalcoursemodel;

        $cache = $this->CI->load->library('nationalCourse/cache/NationalCourseCache');  
        
        /*
         * Load the repository
         */ 
        $this->CI->load->repository('CourseRepositoryAbstract','nationalCourse'); 
        $this->CI->load->repository('CourseRepository','nationalCourse'); 
        
        return new CourseRepository($dao,$cache);
    }
}