<?php

include_once '../WidgetsAggregatorInterface.php';

class courseRecommendedQuestions implements WidgetsAggregatorInterface{

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
		$widgetHTML = "";

		if($customParams['listing_type_id'] > 0 && $customParams['instituteId']){
			$courseId = $customParams['listing_type_id'];
			$instituteId = $customParams['instituteId'];
	
			$this->_CI->load->library('ContentRecommendation/AnARecommendationLib');
			$questionIds = $this->_CI->anarecommendationlib->forCourse($courseId, array(), 3, 0,'RELEVANCY');
			if(is_array($questionIds) && isset($questionIds['topContent'])){
			    $finalArray = $questionIds['topContent'];
			}
			
			//Get questions details from DB
			if(!empty($finalArray)){		
		        $totalNumber = $questionIds['numFound'];
				$questionIds = implode(',',$finalArray);
			    $this->_CI->load->model("messageBoard/anamodel");
			    $questionsDetail = $this->_CI->anamodel->getQuestionsDetails($questionIds);
				$allQuestionURL = getSeoUrl($instituteId,'all_content_pages','',array('typeOfPage'=>'questions','typeOfListing'=>'', 'courses' => array($courseId)));
				
				$customParams['courseRecommendedQuestions'] = array('questionCount'=>$totalNumber, 'questionsDetail'=>$questionsDetail, 'allQuestionURL'=>$allQuestionURL);
		 		$widgetHTML = $this->_CI->load->view("personalizedMailer/widgets/courseRecommendedQuestions", $customParams, true);
			}
		} 
		return array('key'=>'courseRecommendedQuestions','data'=>$widgetHTML);
	}

}