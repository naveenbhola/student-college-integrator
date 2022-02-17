<?php

include_once '../AbroadWidgetsAggregatorInterface.php';

class countryHomeFeaturedColleges implements AbroadWidgetsAggregatorInterface{

	private $_params = array();

	public function __construct($params) {
		$this->_params = $params;
	}
	public function getWidgetData() {
		$countryId = $this->_params['countryId'];
		$countryHomeLib = $this->_params['countryHomeLib'];
		$data = $countryHomeLib->getFeaturedCollegesData($countryId);
		if(empty($data['universities'])){
			$data = array();
		}
		return array('key'=>'countryHomeFeaturedColleges','data'=>$data);
	}

}


