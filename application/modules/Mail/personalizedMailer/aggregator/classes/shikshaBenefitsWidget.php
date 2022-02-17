<?php

include_once '../WidgetsAggregatorInterface.php';

class shikshaBenefitsWidget implements WidgetsAggregatorInterface{

	private $_params = array();
	
	public function __construct($params) {
		$this->_params = $params;
		$this->CI = & get_instance();
	}
	
	public function getWidgetData() {

		$customParams = $this->_params['customParams'];

		$widgetHTML = '';
		$widgetHTML = $this->CI->load->view("personalizedMailer/widgets/shikshaBenefitsWidget", $customParams, true);
		
		return array('key'=>'shikshaBenefitsWidget','data'=>$widgetHTML);
	}
}