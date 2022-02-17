<?php

include_once '../WidgetsAggregatorInterface.php';

class courseExams implements WidgetsAggregatorInterface{

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

		if($customParams['listing_type_id'] > 0 && !empty($customParams['courseObj'])){
			$courseId = $customParams['listing_type_id'];
			$courseObj = $customParams['courseObj'];
			$courseExams = $courseObj->getAllEligibilityExams();

			$examIds = array_keys($courseExams);
			$this->_CI->load->library('examPages/ExamMainLib');
	        $examData = $this->_CI->exammainlib->getExamDataByExamIds($examIds);                
			$examData = array_slice($examData, 0, 4);

	        $results = array();
	        foreach ($examData as $examObject) {
	        	$domainScope = SHIKSHA_HOME;
	        	if($examObject['scope']=='abroad'){
	        		$domainScope = SHIKSHA_STUDYABROAD_HOME;
	        	}
				$url = addingDomainNameToUrl(array('domainName'=>$domainScope,'url'=>$examObject['url']));
	            $results[] = array('exam_name' => $examObject['name'], 'exam_url' => $url);
	        }
			if(!empty($results)){
			    $customParams['courseExams'] = $results;
		 		$widgetHTML = $this->_CI->load->view("personalizedMailer/widgets/courseExams", $customParams, true);
			}
		} 
		return array('key'=>'courseExams','data'=>$widgetHTML);
	}

}