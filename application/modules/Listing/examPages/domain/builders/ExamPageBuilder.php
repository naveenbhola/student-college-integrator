<?php

class ExamPageBuilder
{
    private $CI;
    
    function __construct($params) {
        $this->CI = &get_instance();    
         // load exam page request
        $this->request = $this->CI->load->library('examPages/ExamPageRequest',$params);
    }


    /**
     *  get request
     * @return object
     */
    public function getRequest()
    {
        return $this->request;
    }
    
    /**
     * set request
     * @param ExamPageRequest $request
     */
    public function setRequest(ExamPageRequest $request)
    {
        $this->request = $request;
    }
    
    public function getExamPageRepository() {
        //load exampage model
        $this->CI->load->model('examPages/exampagemodel', '', TRUE);
		$dao = $this->CI->exampagemodel;
        
        //load exampage cache
        $this->CI->load->library('examPages/cache/ExamPageCache');
        $cache = $this->CI->exampagecache;
        
        //load exampage repository initialised with model and cache
        $this->CI->load->repository('ExamPageRepository','examPages');
        $examPageRepository = new ExamPageRepository($dao,$cache);
        
        return $examPageRepository;
    }
}