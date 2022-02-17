<?php
include_once '../WidgetsAggregatorInterface.php';

class ShortlistReminderMail implements WidgetsAggregatorInterface{

	private $_params = array();
	
	public function __construct($params) {
		$this->_params = $params;
		$this->CI = & get_instance();
	}
	
	public function getWidgetData() {
		$customParams = $this->_params['customParams'];
		$widgetHTML = $this->CI->load->view("personalizedMailer/widgets/ShortlistReminderMail", $customParams, true);
		
		return array('key'=>'ShortlistReminderMail','data'=>$widgetHTML);
	}
}