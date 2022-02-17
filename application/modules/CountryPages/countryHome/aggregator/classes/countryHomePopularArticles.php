<?php

include_once '../AbroadWidgetsAggregatorInterface.php';

class countryHomePopularArticles implements AbroadWidgetsAggregatorInterface{

	private $_params = array();	

	public function __construct($params) {
		$this->_params = $params;
	}
	
	public function getWidgetData() {
		$countryId = $this->_params['countryId'];
		$countryHomeLib = $this->_params['countryHomeLib'];
		$result = $countryHomeLib->getPopularArticlesWidgetData($countryId);
		return array('key'=>'countryHomePopularArticles','data'=>$result);
	}
}


