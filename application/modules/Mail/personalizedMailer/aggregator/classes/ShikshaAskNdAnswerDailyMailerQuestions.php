<?php

include_once '../WidgetsAggregatorInterface.php';

class ShikshaAskNdAnswerDailyMailerQuestions implements WidgetsAggregatorInterface{
	private $_params = array();
	
	public function __construct($params) {
		$this->_params = $params;
		$this->CI = & get_instance();
	}
	
	public function getWidgetData() {
	
		$customParams = $this->_params['customParams'];
		$widgetHTML = $this->CI->load->view("personalizedMailer/widgets/ShikshaAskNdAnswerDailyMailerQuestions", $customParams, true);
	
		return array('key'=>'ShikshaAskNdAnswerDailyMailerQuestions','data'=>$widgetHTML);
	}
}