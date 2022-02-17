<?php

include_once '../AbroadWidgetsAggregatorInterface.php';

class countryHomeGuideDownloads implements AbroadWidgetsAggregatorInterface{

	private $_params = array();

	public function __construct($params) {

		$this->_params = $params;
	}
	public function getWidgetData() {

		$countryId = $this->_params['countryId'];
		$countryHomeLib = $this->_params['countryHomeLib'];
		$result = $countryHomeLib->getDownloadWidgetData($countryId,'guide',0);
		if(count($result)==0)
		{
		  $result =array();	
		}
		return array('key'=>'countryHomeGuideDownloads','data'=>$result);
	}

}


