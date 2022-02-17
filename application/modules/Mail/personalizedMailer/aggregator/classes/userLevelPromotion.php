<?php

include_once '../WidgetsAggregatorInterface.php';

class userLevelPromotion implements WidgetsAggregatorInterface{

	private $_params = array();
	
	public function __construct($params) {
		$this->_params = $params;
		$this->CI = & get_instance();
	}
	
	public function getWidgetData() {

		$customParams = $this->_params['customParams'];
		$widgetHTML = $this->CI->load->view("personalizedMailer/widgets/userLevelPromotion", $customParams, true);
		
		return array('key'=>'userLevelPromotion','data'=>$widgetHTML);
	}
}