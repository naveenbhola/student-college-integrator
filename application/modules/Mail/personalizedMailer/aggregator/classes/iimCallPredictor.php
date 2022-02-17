<?php

include_once '../WidgetsAggregatorInterface.php';

class iimCallPredictor implements WidgetsAggregatorInterface{

	private $_params = array();
	
	public function __construct($params) {
		$this->_params = $params;
		$this->_CI = & get_instance();
	}
	
	/**
	* function to get data for widgets of college reviews
	*/
	public function getWidgetData() {
		
		$customParams = $this->_params['customParams'];

		$widgetHTML = "";
		if($customParams['listing_type_id'] > 0 && !empty($customParams['courseObj'])){
			$courseId = $customParams['listing_type_id'];
			$courseObj = $customParams['courseObj'];
			$baseCourse = $courseObj->getBaseCourse();

			if($baseCourse['entry']==MANAGEMENT_COURSE){		
				$customParams['showIIMCallPredictor'] = true;
	            $customParams['url'] =  SHIKSHA_HOME."/mba/resources/iim-call-predictor";
		 		$widgetHTML = $this->_CI->load->view("personalizedMailer/widgets/iimCallPredictor", $customParams, true);
			}
		} 
		return array('key'=>'iimCallPredictor','data'=>$widgetHTML);
	}

}
