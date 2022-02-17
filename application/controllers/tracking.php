<?php


class Tracking extends MX_Controller {
	
	private $enabled = false;
	
	function generateTrackingImage($url,$referalUrl){
		if($this->enabled){
			if(!$referalUrl){
				$referalUrl = "NULL";
			}
			return $this->load->view('common/trackingImage',array('url' => url_base64_encode($url),'referalUrl' => url_base64_encode($referalUrl)),true);
		}
	}
	
	
	function trackPageLoad($url,$referalUrl){
		return false;
		$validate = $this->checkUserValidation();
		$this->load->library('session');
		$this->load->library('LDB_Client');
		$this->load->model('common/trackingmodel');
		$this->load->library('common/Personalizer');
		
		if($validate != "false"){
			$userId = $validate[0]['userid'];
			
			$result = $this->ldb_client->isLDBUser($userId);
			$isLDBUser = false;
			if(is_array($result) && isset($result[0]['UserId'])){
				$isLDBUser = true;
			}
		}else{
			$userId = 0;
			$isLDBUser = false;
		}
		
		$sessionId = $this->session->userdata('session_id');
		$url = url_base64_decode($url);
		$referalUrl = ($referalUrl != "NULL")?url_base64_decode($referalUrl):"NULL";
		$personalizedArray = $this->personalizer->isPersonalized();
		if($personalizedArray['isPersonalized'] == 1){
			$personalized = true;
		}else{
			$personalized = false;
		}
		
		$this->trackingmodel->trackPageLoad($userId,$sessionId,$isLDBUser,$url,$referalUrl,$personalized,$personalizedArray);
	}
}
