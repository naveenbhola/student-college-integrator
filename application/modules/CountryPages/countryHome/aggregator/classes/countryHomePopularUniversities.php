<?php

include_once '../AbroadWidgetsAggregatorInterface.php';

class countryHomePopularUniversities implements AbroadWidgetsAggregatorInterface{

	private $_params = array();

	public function __construct($params) {

		$this->_params = $params;
	}
	public function getWidgetData() {
		$countryId = $this->_params['countryId'];
		$courseId = $this->_params['courseId'];
		$countryHomeLib = $this->_params['countryHomeLib'];
		$result = $countryHomeLib->getUniversityWidgetData($countryId, $courseId);
		 if($result['totalCount']==0)
                 {
                         $result = array();
                 }
		return array('key'=>'countryHomePopularUniversities','data'=>$result);
	}

}
