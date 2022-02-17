<?php

class SASearchPage{

	private $request;
	private $filters;
	private $sortParam;
	private $univData;
	private $pageData;
    private $filterParent;
        
                	function __construct(SearchPageRequest $request,
						 SearchPageRepository $searchPageRepo
						)
	{
		$this->request             = $request;
		$this->searchPageRepo      = $searchPageRepo;
		$this->CI = &get_instance();
		$this->CI->load->config("SASearch/SASearchPageConfig");
		$this->buildSearchData();
	}
    public function getRequest()
    {
        return $this->request;
    }
	public function buildSearchData() {
		$searchData=$this->searchPageRepo->getRawSearchData();
        $this->setFilters($searchData['filters']);
        $this->setUnivData($searchData['univData']);
        $this->setPageData($searchData['pageData']);
        $this->setSortParam($searchData['sortParam']);
        $this->setFilterParent($searchData['filters_parent']);
	}
        
    function getFilters() {
        return $this->filters;
    }

    function setFilters($filters) {
        $this->filters = $filters;
    }

    function setUnivData($univData){
    	$this->univData = $univData;
    }

    function getUnivData(){
    	return $this->univData;
    }

    function setPageData($pageData){
    	$this->pageData = $pageData;
    }

    function getPageData(){
    	return $this->pageData;
    }
	
    function getSortParam() {
            return $this->sortParam;
        }

    function setSortParam($sortParam) {
            $this->sortParam = strtolower($sortParam);
        }  
        function getFilterParent() {
            return $this->filterParent;
        }

        function setFilterParent($filterParent) {
            $this->filterParent = $filterParent;
        }


}