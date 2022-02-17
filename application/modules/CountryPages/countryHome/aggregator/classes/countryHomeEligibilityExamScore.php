<?php

include_once '../AbroadWidgetsAggregatorInterface.php';

class countryHomeEligibilityExamScore implements AbroadWidgetsAggregatorInterface{

	private $_params = array();

	public function __construct($params) {

		$this->_params = $params;
	}
	public function getWidgetData() {
		$countryId = $this->_params['countryId'];
		$ldbCourseId = $this->_params['courseId'];
		$countryHomeLib = $this->_params['countryHomeLib'];
		$desiredCoursesOnPage = $this->_params['desiredCoursesOnPage'];
		$result = $countryHomeLib->getEligibilityExamScoreWidgetData($countryId, $ldbCourseId, $desiredCoursesOnPage);
		//_p($result);
		if(count($result['contentSectionData'])==0 && count($result['graphSectionData'])==0)
		{
			$result = array();
		}
		return array('key'=>'countryHomeEligibilityExamScore','data'=>$result);
	}

}
