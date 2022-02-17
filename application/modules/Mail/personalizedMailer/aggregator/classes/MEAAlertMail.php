<?php

include_once '../WidgetsAggregatorInterface.php';

class MEAAlertMail implements WidgetsAggregatorInterface{

	private $_params = array();
	
	public function __construct($params) {
		$this->_params = $params;
		$this->CI = & get_instance();
	}
	
	public function getWidgetData() {
		$customParams = $this->_params['customParams'];
		$widgetHTML = $this->CI->load->view("personalizedMailer/widgets/MEAAlertMail", $customParams, true);
		return array('key'=>'MEAAlertMail','data'=>$widgetHTML);
	}
}
?>