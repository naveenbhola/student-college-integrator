<?php

class UserTracking extends MX_Controller{

   /* 
	*	Function to save tracking details into userActionTracking table
	*	@Params: trackingKey, trackingType and trackingValue($_POST)
	*/
	public function userActionTracking(){
		$data = array();

		$userDetails = $this->checkUserValidation();
		$data['userId'] = ($userDetails[0]['userid'] > 0)? $userDetails[0]['userid'] : 0;

		/* Tracking information */
		$data['trackingKey'] = isset($_POST['trackingKey'])? $this->input->post('trackingKey', true): 'NULL';
		$data['trackingValue'] = isset($_POST['trackingValue'])? $this->input->post('trackingValue', true): 'NULL';
		$data['type'] = isset($_POST['trackingType'])? $this->input->post('trackingType', true): 'NULL';

		$data['visitorSessionId'] = getVisitorSessionId();
		
		/*Filtering for tracking value */
		$masterTrackingValue = array('pageLoad', 'edit', 'save', 'addMore', 'cancel', 'upload', 'click');
		if(!in_array($data['trackingValue'], $masterTrackingValue)){
			echo 'fail';
			return false;
		}

		$userTrackingModel = $this->load->model('user/usertrackingmodel');
		if($userTrackingModel->saveUserProfileTracking($data) > 0){
			echo 'success';
		}else{
			echo 'fail';
		}

		return true;
	}



}