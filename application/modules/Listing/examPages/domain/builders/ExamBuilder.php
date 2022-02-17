<?php

class ExamBuilder
{
    private $CI;
    
    function __construct($params) {
        $this->CI = &get_instance();
    }

    public function getExamRepository() {
        //load exampage model
        $this->CI->load->model('examPages/exammodel', '', TRUE);
		$dao = $this->CI->exammodel;
        
        //load exam cache
        $this->CI->load->library('examPages/cache/ExamCache');
        $cache = $this->CI->examcache;
        
        //load exampage repository initialised with model and cache
        $this->CI->load->repository('ExamRepository','examPages');
        $examRepository = new ExamRepository($dao,$cache);
        
        return $examRepository;
    }

}