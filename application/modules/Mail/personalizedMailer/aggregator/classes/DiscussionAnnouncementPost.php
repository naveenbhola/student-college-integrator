<?php

include_once '../WidgetsAggregatorInterface.php';

class DiscussionAnnouncementPost implements WidgetsAggregatorInterface{

	private $_params = array();
	
	public function __construct($params) {
		$this->_params = $params;
		$this->CI = & get_instance();
	}
	
	public function getWidgetData() {

		$customParams = $this->_params['customParams'];
		$widgetHTML = $this->CI->load->view("personalizedMailer/widgets/DiscussionAnnouncementPost", $customParams, true);
		
		return array('key'=>'DiscussionAnnouncementPost','data'=>$widgetHTML);
	}
}