<?php

include_once '../WidgetsAggregatorInterface.php';

class ForgotPassword implements WidgetsAggregatorInterface{

	private $_params = array();
	
	public function __construct($params) {
		$this->_params = $params;
		$this->_CI = & get_instance();
	}
	
	/**
	* function to get data for widgets of Forgot Password
	*/
	public function getWidgetData() {
		
		$customParams = $this->_params['customParams'];
		$widgetHTML = $this->_CI->load->view("personalizedMailer/widgets/ForgotPasswordFullTimeMBA", $customParams, true);
		
		return array('key'=>'ForgotPasswordFullTimeMBA','data'=>$widgetHTML);
	}
}