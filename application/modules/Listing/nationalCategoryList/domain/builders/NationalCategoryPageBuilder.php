<?php

class NationalCategoryPageBuilder
{
	private $CI;
	private $nationalCategoryPageRequest;

    function __construct() {
        $this->CI = & get_instance();

        /*
         * Loading Category Page Request..
         * This should be loaded only once on the page. It builds complete category page.
         * It will further be shared among other files.
         */
        $this->nationalCategoryPageRequest = $this->CI->load->library('nationalCategoryList/NationalCategoryPageRequest');

/*        $this->CI->load->library('nationalCategoryList/NationalCategoryPageRequest');
        $this->nationalCategoryPageRequest = new NationalCategoryPageRequest();*/

    }

    public function getCategoryPageRequest() {
        return $this->nationalCategoryPageRequest;
    }

    // public function getCategoryPageResultLib() {
    //     $this->CI->load->library('nationalCategoryList/NationalCategoryPageResults');
    //     $categoryPageResultLib = new NationalCategoryPageResults();
    //     return $categoryPageResultLib;
    // }

    public function getCategoryPageRepository() {
    	$this->CI->load->repository('nationalCategoryList/NationalCategoryPageRepository');
    	$repository = new NationalCategoryPageRepository($this->nationalCategoryPageRequest);
        return $repository;
    }
}