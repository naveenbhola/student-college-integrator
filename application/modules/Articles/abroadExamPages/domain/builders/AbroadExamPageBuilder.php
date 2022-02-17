<?php

class AbroadExamPageBuilder
{
    private $CI;
    
    function __construct($params) {
        $this->CI = &get_instance();    
    }
    
    public function getAbroadExamPageRepository() {
        //load exampage model
	$dao = $this->CI->load->model('abroadExamPages/abroadexampagemodel', '', TRUE);
        //load exampage cache
        //$cache = $this->CI->load->library('abroadExamPages/cache/AbroadExamPageCache');
        //load exampage repository initialised with model and cache
        $this->CI->load->repository('AbroadExamPageRepository','abroadExamPages');
        $abroadExamPageRepository = new AbroadExamPageRepository($dao/*,$cache*/);
        // return repo
        return $abroadExamPageRepository;
    }
}