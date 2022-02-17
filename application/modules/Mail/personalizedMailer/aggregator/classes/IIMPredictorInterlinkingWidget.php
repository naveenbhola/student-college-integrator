<?php

include_once '../WidgetsAggregatorInterface.php';

class IIMPredictorInterlinkingWidget implements WidgetsAggregatorInterface{

	private $_params = array();
	
	public function __construct($params) {
		$this->_params = $params;
		$this->CI = & get_instance();
	}
	
	public function getWidgetData() {

		$customParams = $this->_params['customParams'];

		$widgetHTML = '';
		if($customParams['baseCourseId'] == MANAGEMENT_COURSE && $customParams['stream_id'] == MANAGEMENT_STREAM) {
			$widgetHTML = $this->CI->load->view("personalizedMailer/widgets/IIMPredictorInterlinkingWidget", $customParams, true);
		}
		
		return array('key'=>'IIMPredictorInterlinkingWidget','data'=>$widgetHTML);
	}
}