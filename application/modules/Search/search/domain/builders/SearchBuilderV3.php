<?php

class SearchBuilderV3 {
    private $CI;
    private $request;
    private $questionRequest;
    
    function __construct($selectedFilter)
    {
        $this->CI = & get_instance();
        $this->setRequest($selectedFilter);
    }

    public function setRequest($selectedFilter)
    {
        $this->request = $this->CI->load->library('search/SearchV3/nationalSearchPageRequest', array('selectedFilterFromAllCourses' => $selectedFilter));
    }
    
    public function getRequest()
    {
        if(empty($this->request)) {
            $this->setRequest();
        }
        return $this->request;
    }

    public function setQuestionRequest()
    {
        $this->questionRequest = $this->CI->load->library('search/SearchV3/NationalQuestionSearchPageRequest');
    }

    public function getQuestionRequest()
    {
        if(empty($this->questionRequest)) {
            $this->setQuestionRequest();
        }
        return $this->questionRequest;
    }

    public function getSearchRepository() {
        if(empty($this->request)) {
            $this->setRequest();
        }
        $this->CI->load->repository('search/SearchRepositoryV3');
        $searchPageRepo = new SearchRepositoryV3($this->request);
        return $searchPageRepo;
    }

    public function getQuestionSearchRepository() {
        if(empty($this->questionRequest)) {
            $this->setQuestionRequest();
        }
        $this->CI->load->repository('search/QuestionSearchRepositoryV3');
        $searchPageRepo = new QuestionSearchRepositoryV3($this->questionRequest);
        return $searchPageRepo;
    }

    public function getAllCoursesRepository() {
        if(empty($this->request)) {
            $this->setRequest();
        }
        $this->CI->load->repository('nationalCategoryList/AllCoursesRepository');
        $allCoursesRepository = new AllCoursesRepository($this->request);
        return $allCoursesRepository;
    }
}