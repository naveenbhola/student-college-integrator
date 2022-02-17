<?php

include_once '../WidgetsAggregatorInterface.php';

class campusConnectWidget implements WidgetsAggregatorInterface{

	private $_params = array();
	
	public function __construct($params) {
		$this->_params = $params;
		$this->_CI = & get_instance();
	}
	
	/**
	* function to get data for widgets of campus connect
	*/
	public function getWidgetData() {
		
		$customParams = $this->_params['customParams'];

		if($customParams['listing_type_id'] <= 0 || empty($customParams['listing_type_id'])) {
			return array('key'=>'campusConnectWidget','data'=>'');
		}

		if($customParams['listing_type_id'] > 0) {
			$courseId = $customParams['listing_type_id'];
		} 

		$this->_CI->load->model('CA/camodel');
		$camodel = new camodel;
		$CADetails = $camodel->checkIfCampusRepForCourses(array($courseId));

		if($CADetails[$courseId] == 'false'){ 
           return array('key'=>'campusConnectWidget','data'=>'');
        }

        $customParams['CampusRepURL'] = SHIKSHA_HOME.'/getListingDetail/'.$courseId.'/course?hashid=askProposition';

        $mailerDetails = $customParams['mailerDetails'];
        if($mailerDetails['mailerType'] == 'mmm') {
			$widgetHTML = $this->_CI->load->view("personalizedMailer/widgets/campusConnectWidgetMMM", $customParams, true);
		} else {
			$widgetHTML = $this->_CI->load->view("personalizedMailer/widgets/campusConnectWidget", $customParams, true);
		}
		
		return array('key'=>'campusConnectWidget','data'=>$widgetHTML);
	}
}