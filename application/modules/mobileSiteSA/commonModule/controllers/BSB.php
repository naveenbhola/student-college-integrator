<?php 
class BSB extends MX_Controller
{
	private $validateuser;
	private $BSBLib;
	function __construct(){
		parent::__construct();
		$this->validateuser = $this->checkUserValidation();
		$this->load->config('bsbPageList');
		$this->BSBLib = $this->load->library('commonModule/BSBLib');
	}

	public function getBSBDataAvailableForPage($beaconPageName){
		$bsbTypes = $this->config->item('bsbTypes');
		$bsbTypeToPageMapping = $this->config->item('bsbTypeToPageMapping');
		$bsbParams = array();
	    foreach ($bsbTypes as $bsbType => $status) {
			if($status === true){
				if(in_array($beaconPageName, $bsbTypeToPageMapping[$bsbType])){
					$bsbParams['pageName'] = $beaconPageName;
					$bsbParams['bsbType']  = $bsbType;
				}
			}
		}
		return $bsbParams;
	}

	public function getBSB(){
		$displayData = array();
		$displayData['showBSBFlag'] = false;
		$bsbTypeToPageMapping = $this->config->item('bsbTypeToPageMapping');
		$displayData['crossBtnFlag'] = false;
		$displayData['pageName']     = $this->input->post('pageName', true);
		$displayData['bsbType']      = $this->input->post('bsbType', true);
		$displayData['sourceApp']    = isMobileRequest() ? 'mobile' : 'desktop';
		$displayData['bsbPageList']  = $bsbTypeToPageMapping[$displayData['bsbType']];
		$displayData['userId']       = (isset($this->validateuser[0]['userid'])) ? $this->validateuser[0]['userid'] : 0;

		if(!in_array($displayData['pageName'], $displayData['bsbPageList'])){
			echo json_encode(array('html'=>'', 'bsbTrackingId'=>0)); return;
		}
		$userBSBData = $this->BSBLib->getUserBSBData(getVisitorId(), $displayData['userId']);
		$this->BSBLib->getShowFlagsForBSB($displayData, $userBSBData);
		$viewHtml = '';
		$bsbPos = '';
		$bsbTrackingId = 0;
		if($displayData['showBSBFlag'] == true){
			$viewHtml = $this->BSBLib->loadBSBHtml($displayData);
			$bsbPos   = $this->BSBLib->getBSBPosition($displayData['bsbType']);
			$bsbTrackingId = $this->BSBLib->addBSBTrackingData($displayData);
		}
		$finalData = array('html'=>$viewHtml, 'bsbTrackingId'=>$bsbTrackingId, 'bsbPos'=>$bsbPos);
		echo json_encode($finalData);
	}

	public function trackBSBAction(){
		$userAction    = $this->input->post('action', true);
		$bsbTrackingId = $this->input->post('bsbTrackingId', true);
		$bsbType       = $this->input->post('bsbType', true);
		$userId        = (isset($this->validateuser[0]['userid'])) ? $this->validateuser[0]['userid'] : 0;
		$trackSts = $this->BSBLib->trackBSBAction($bsbTrackingId, $userAction);
		if($trackSts == true){
			$url = $this->BSBLib->getBSBPageUrl($bsbType);
			setcookie('bsb', $userId, time() + (60*60*24*30), '/', COOKIEDOMAIN);
			echo json_encode(array('url'=>$url));
			return;
		}
		echo 'error';
	}
}