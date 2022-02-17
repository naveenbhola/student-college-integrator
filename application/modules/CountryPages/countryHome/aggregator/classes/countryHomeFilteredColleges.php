<?php

include_once '../AbroadWidgetsAggregatorInterface.php';

class countryHomeFilteredColleges implements AbroadWidgetsAggregatorInterface{

	private $_params = array();
	private $_CI = null;

	public function __construct($params) {

		$this->_params = $params;
		//$this->_CI = & get_instance();
		//$this->_CI->load->model('countryHome/countryhomemodel');
	}
	public function getWidgetData() {

		//$subcat_id = $this->_params["subCatId"];
		//$this->cache->deleteSlideInfo($subcat_id);
		
		

		return array('key'=>'countryHomeFilteredColleges','data'=>array());
	}

}


