<?php

include_once '../WidgetsAggregatorInterface.php';

class questionLinkedStatusChangedMailer implements WidgetsAggregatorInterface{

	private $_params = array();
	
	public function __construct($params) {
		$this->_params = $params;
		$this->CI = & get_instance();
	}
	
	public function getWidgetData() {

		$customParams = $this->_params['customParams'];
		$widgetHTML = $this->CI->load->view("personalizedMailer/widgets/questionLinkedStatusChangedMailer", $customParams, true);
		
		return array('key'=>'questionLinkedStatusChangedMailer','data'=>$widgetHTML);
	}
}