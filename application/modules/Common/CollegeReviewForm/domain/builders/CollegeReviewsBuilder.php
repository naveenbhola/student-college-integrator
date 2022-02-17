<?php 

class CollegeReviewsBuilder{
	
    private $CI;
    private $ReviewsPage;
    
    function __construct()
    {
        $this->CI = &get_instance();
        $this->dao = $this->CI->load->model('CollegeReviewForm/collegereviewmodel');    
        $this->lib = $this->CI->load->library('CollegeReviewForm/CollegeReviewLib');
    }

    function getReviewsPageRepository(){
    	$reviewsCache = $this->CI->load->library('cacheLib');
    	
    	$this->CI->load->repository('CollegeReviewForm/ReviewsPage');
        $this->ReviewsPage = new ReviewsPage($this->dao, $reviewsCache, $this->lib);
        return $this->ReviewsPage;
    }

}
