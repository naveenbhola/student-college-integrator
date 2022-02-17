<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class WebsiteTour extends MX_Controller{

	function __construct() {
		$this->load->config('common/websiteTourConfig');
	}

	function getContentMapping($key,$device) {	
		$contentMapping = $this->config->item('websiteTourContentMapping');
		if(empty($device)){
			return $contentMapping[$key];
		}
		else{
			return $contentMapping[$key][$device];
		}
	}

	function trackButtonClick(){
		$data['button_type'] = $this->input->post('buttonType',true);
		$data['current_step'] = $this->input->post('stepNumber',true);
		$data['feature_type'] = $this->input->post('tourName',true);
		$data['initiated_from'] = $this->input->post('pageType',true);
		$data['device'] = $this->input->post('device',true);

		$validity = $this->checkUserValidation();
		if(!empty($validity)){
			$data['user_id'] = $validity[0]['userid'];
		}
		$model = $this->load->model('common/websitetourmodel');
		$model->trackButtonClick($data);
	}

	function checkIfUserSeenTour($feature_type,$userId){
		if(empty($userId) || empty($feature_type)){
			return;
		}
		$model = $this->load->model('common/websitetourmodel');
		return $model->checkIfUserSeenTour($feature_type,$userId);
	}
}