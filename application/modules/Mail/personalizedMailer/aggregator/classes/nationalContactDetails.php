<?php

include_once '../WidgetsAggregatorInterface.php';

class nationalContactDetails implements WidgetsAggregatorInterface{

	private $_params = array();
	
	public function __construct($params) {
		$this->_params = $params;
		$this->_CI = & get_instance();
	}
	
	/**
	* function to get data for widgets of request contact details
	*/
	public function getWidgetData() {
		
		$customParams = $this->_params['customParams'];
		$widgetHTML = $this->_CI->load->view("personalizedMailer/widgets/nationalContactDetails", $customParams, true);
		
		return array('key'=>'nationalContactDetails','data'=>$widgetHTML);
	}
}