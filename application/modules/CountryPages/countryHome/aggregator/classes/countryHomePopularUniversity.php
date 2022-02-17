<?php

include_once '../AbroadWidgetsAggregatorInterface.php';

class countryHomePopularUniversity implements AbroadWidgetsAggregatorInterface{

	private $_params = array();

	public function __construct($params) {

		$this->_params = $params;
	}
	public function getWidgetData() {
		$countryId = $this->_params['countryId'];
		$countryHomeLib = $this->_params['countryHomeLib'];
		$result = $countryHomeLib->getUniversityWidgetData($countryId);
		 if($result['totalCount']==0)
                 {
                         $result = array();
                 }
		return array('key'=>'countryHomePopularUniversity','data'=>$result);
	}

}
