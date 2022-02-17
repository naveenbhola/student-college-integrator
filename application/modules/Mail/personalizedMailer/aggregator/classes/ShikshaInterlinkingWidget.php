<?php

include_once '../WidgetsAggregatorInterface.php';

class ShikshaInterlinkingWidget implements WidgetsAggregatorInterface{

	private $_params = array();
	
	public function __construct($params) {
		$this->_params = $params;
		$this->CI = & get_instance();
	}
	
	public function getWidgetData() {

		$customParams = $this->_params['customParams'];

		$widgetHTML = '';
		if($customParams['stream_id'] == MANAGEMENT_STREAM) {
			$widgetHTML = $this->CI->load->view("personalizedMailer/widgets/ShikshaInterlinkingWidget", $customParams, true);
		}
		
		return array('key'=>'ShikshaInterlinkingWidget','data'=>$widgetHTML);
	}
}