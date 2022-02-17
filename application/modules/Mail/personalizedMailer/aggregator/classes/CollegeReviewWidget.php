<?php

include_once '../WidgetsAggregatorInterface.php';

class CollegeReviewWidget implements WidgetsAggregatorInterface{

	private $_params = array();
	
	public function __construct($params) {
		$this->_params = $params;
		$this->_CI = & get_instance();
	}
	
	/**
	* function to get data for widgets of college reviews
	*/
	public function getWidgetData() {
		
		$customParams = $this->_params['customParams'];
// _p($customParams);die('fgsh');
		if($customParams['listing_type_id'] <= 0 || empty($customParams['listing_type_id'])) {
			return array('key'=>'CollegeReviewWidget','data'=>'');
		}

		if($customParams['listing_type_id'] > 0) {
			$courseId = $customParams['listing_type_id'];
		} 

		$this->_CI->load->library('ContentRecommendation/ReviewRecommendationLib');
		$reviewIds = $this->_CI->reviewrecommendationlib->forCourse($courseId, array(), 1);
		
		$reviewId = $reviewIds['topContent'][0];
		if(empty($reviewId)) {
			return array('key'=>'CollegeReviewWidget','data'=>'');
		}

		$this->_CI->load->model('CollegeReviewForm/collegereviewmodel');
		$collegereviewmodel = new CollegeReviewModel;
		$collegeReviewData = $collegereviewmodel->getReviewDetailbyReviewId($reviewId);

		if(!empty($collegeReviewData['placementDescription'])) {
			$description = $collegeReviewData['placementDescription'];
		} else if(!empty($collegeReviewData['reviewDescription'])) {
			$description = $collegeReviewData['reviewDescription'];
		}

		if(empty($description)) {
			return array('key'=>'CollegeReviewWidget','data'=>'');
		}

		$this->_CI->load->builder("nationalCourse/CourseBuilder");
		$builder = new CourseBuilder();
        $courseRepository = $builder->getCourseRepository();
		$courseObj = $courseRepository->find($courseId, array('basic'));

		$instituteId = $courseObj->getInstituteId();

		if($instituteId <= 0 || empty($instituteId)) {
			return array('key'=>'CollegeReviewWidget','data'=>'');
		}

  		$instituteReviewURL = getSeoUrl($instituteId,'all_content_pages','',array('typeOfPage'=>'reviews','typeOfListing'=>'institute')); 
		$customParams['reviewDescription'] = substr($description, 0, 90);
		$customParams['courseDetailURL'] = $instituteReviewURL.'?course='.$courseId;
		
		$mailerDetails = $customParams['mailerDetails'];
        if($mailerDetails['mailerType'] == 'mmm') {
			$widgetHTML = $this->_CI->load->view("personalizedMailer/widgets/CollegeReviewWidgetMMM", $customParams, true);
		} else {
			$widgetHTML = $this->_CI->load->view("personalizedMailer/widgets/CollegeReviewWidget", $customParams, true);
		}

		return array('key'=>'CollegeReviewWidget','data'=>$widgetHTML);
	}

}