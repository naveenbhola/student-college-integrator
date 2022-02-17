<?php

include_once '../WidgetsAggregatorInterface.php';

class alsoViewedCoursesForCourse implements WidgetsAggregatorInterface{

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
		if($customParams['listing_type_id'] > 0) {
			$courseId = $customParams['listing_type_id'];
			$this->_CI->load->library('recommendation/alsoviewed');
			$results = $this->_CI->alsoviewed->getAlsoViewedCourses(array($courseId), '3');
			foreach ($results as $key => $data) {
			    $courseList[] = $data['courseId'];
			}

			if(!empty($courseList)){		
				$this->_CI->load->builder("nationalCourse/CourseBuilder");
		        $this->courseBuilder = new CourseBuilder();
		        $this->courseRepo    = $this->courseBuilder->getCourseRepository();
				$courseObj = $this->courseRepo->findMultiple($courseList,'',true);
				$alsoViewedCourses = array();

				foreach($courseObj as $courseId =>$course) {
				    if($course->getId()){
				        $alsoViewedCourses[] = array('instituteName'=>$course->getInstituteName(),'courseUrl'=>$course->getURL());
				    }
				}
				$customParams['alsoViewedCoursesForCourse'] = $alsoViewedCourses;
		 		$widgetHTML = $this->_CI->load->view("personalizedMailer/widgets/alsoViewedCoursesForCourse", $customParams, true);
			}
		} 
		return array('key'=>'alsoViewedCoursesForCourse','data'=>$widgetHTML);
	}

}