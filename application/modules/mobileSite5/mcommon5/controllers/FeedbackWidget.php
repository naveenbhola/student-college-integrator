<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class FeedbackWidget extends ShikshaMobileWebSite_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	function init(){
	}

	function getFeedbackWidget($star = 0){
		if($this->input->is_ajax_request() && $star > 0){
			$this->load->config('mcommon5/feedbackConfig');
			$ratingStars = $this->config->item('ratingStars');
			$ratingStarTags = $this->config->item('ratingStarTags');
			$displayData['rating'] = $star;
			$displayData['ratingStars'] = $ratingStars;
			$displayData['ratingStarTags'] = $ratingStarTags;
			echo json_encode(array(
				'form' => $this->load->view('feedbackWidget/feedbackForm', $displayData, true),
				'ratingStars' => $ratingStars,
				'ratingStarTags' => $ratingStarTags
			));
		}
	}
	function postFeedbackValues(){
		if($this->input->is_ajax_request()){
			$inputs = json_encode($this->input->post('feedbackValues', true));
			$apiCallerlib = $this->load->library("common/apiservices/APICallerLib");
			$output = $apiCallerlib->makeAPICall("TRACKINGSERVICE","/trackinggateway/trackingApi/v1/common/info/feedbackTracking", "POST", '', $inputs, '', true);
			echo json_encode(array(
				'msg' => 'success',
				'html'=> $this->load->view('feedbackWidget/saveFeedbackSuccessMsg', $displayData, true)
			));
			return;
		}
		echo json_encode(array(
			'msg' => 'fail',
			'html'=> $this->load->view('feedbackWidget/saveFeedbackFailureMsg', $displayData, true)
		));
	}
}
