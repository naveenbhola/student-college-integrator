<?php

include_once '../WidgetsAggregatorInterface.php';

class DetailedRecommendationMailer implements WidgetsAggregatorInterface{

	private $_params = array();
	
	public function __construct($params) {
		$this->_params = $params;
		$this->CI = & get_instance();
	}
	
	public function getWidgetData() {
		$params = $this->_params['customParams'];

		$widgetHTML = $this->CI->load->view("personalizedMailer/widgets/DetailedRecommendationWidget", $params, true);
		
		return array('key'=>'DetailedRecommendationWidget','data'=>$widgetHTML);
	}
}