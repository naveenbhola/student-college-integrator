<?php

include_once '../AbroadWidgetsAggregatorInterface.php';

class countryHomeFeeAffordability implements AbroadWidgetsAggregatorInterface{

	private $_params = array();

	public function __construct($params) {

		$this->_params = $params;
	}
	public function getWidgetData() {
		$countryId = $this->_params['countryId'];
		$courseId  = $this->_params['courseId'];
		$desiredCourseList = $this->_params['desiredCoursesOnPage'];
		$countryHomeLib = $this->_params['countryHomeLib'];
		$data = $countryHomeLib->prepareDataForFeesAndAffordabilityWidget($countryId,$courseId,$desiredCourseList);
		if($data['cheapest']['count']+$data['scholarship']['count']+$data['public']['count'] > 0){
			$result = $data;
		}else{
			$result = array();
		}
		return array('key'=>'countryHomeFeeAffordability','data'=>$result);
	}

}
