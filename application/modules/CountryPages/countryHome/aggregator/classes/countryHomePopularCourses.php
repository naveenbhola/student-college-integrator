<?php

include_once '../AbroadWidgetsAggregatorInterface.php';

class countryHomePopularCourses implements AbroadWidgetsAggregatorInterface{

	private $_params = array();

	public function __construct($params) {
		$this->_params = $params;
	}
	public function getWidgetData() {
            $countryObj = $this->_params['countryObj'];
            $countryHomeLib = $this->_params['countryHomeLib'];
            $data = $countryHomeLib->getPopularCourseWidgetDataForCountry($countryObj);
	    if(count($data)==0)
            {
                $data = array();
            }
	    return array('key'=>'countryHomePopularCourses','data'=>$data);
	}

}

