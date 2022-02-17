<?php 
class BSBLib{
	private $CI;
	function __construct(){
        $this->CI = &get_instance();
        $this->bsbmodel = $this->CI->load->model('commonModule/bsbmodel');
    }

    public function getUserBSBData($visitorId, $userId = 0){
    	if($userId > 0){
    		$data1 = $this->bsbmodel->getUserBSBDataById($userId, 'shown');
	    	$data2 = $this->bsbmodel->getUserBSBDataById($userId, 'clicked');
	    	return array('shown'=>$data1[0], 'action'=>$data2[0]);
    	}else{
	    	$data1 = $this->bsbmodel->getUserBSBData($visitorId, 'shown');
	    	$data2 = $this->bsbmodel->getUserBSBData($visitorId, 'clicked');
	    	if($data2[0]['userId'] > 0 || $data1[0]['userId'] > 0){
	    		return array('shown'=>array(), 'action'=>array());
	    	}else{
	    		return array('shown'=>$data1[0], 'action'=>$data2[0]);
	    	}
	    }
    }

    public function getShowFlagsForBSB(&$displayData, $userBSBData){
    	if(empty($userBSBData['shown']) && empty($userBSBData['action'])){
			$displayData['showBSBFlag'] = true;
		}else if(!empty($userBSBData) && !empty($userBSBData['shown']) && empty($userBSBData['action'])){
			$displayData['showBSBFlag'] = true;
		}else if(!empty($userBSBData['action'])){
			if($userBSBData['action']['clickedAt'] <= date('Y-m-d', strtotime('-30 days'))){
				$displayData['showBSBFlag'] = true;
				if($displayData['bsbType'] != 'applyPagePromotionCP'){
					$displayData['crossBtnFlag'] = true;
				}
			}else{
				$timeRemaining = (60*60*24*30 - (time() - strtotime($userBSBData['action']['clickedAt'])));
				setcookie('bsb', $displayData['userId'], time() + $timeRemaining, '/', COOKIEDOMAIN);
			}
		}
    }

    public function addBSBTrackingData(&$displayData){
    	$insertData = array();
		$insertData['userId']            = $displayData['userId'];
		$insertData['visitorId']         = getVisitorId();
		$insertData['BSBType']           = $displayData['bsbType'];
		$insertData['BSBAction']         = 'shown';
		$insertData['applicationSource'] = $displayData['sourceApp'];
		$insertData['pageName']          = $displayData['pageName'];
		$insertData['addedAt']           = date('Y-m-d H:i:s');
		$insertData['BSBState']          = $displayData['crossBtnFlag']?'crossEnabled':'crossDisabled';
    	return $this->bsbmodel->addBSBTrackingData($insertData);
    }

    public function trackBSBAction($bsbTrackingId, $userAction){
    	if(empty($bsbTrackingId)){
    		return false;
    	}
    	$BSBAction = '';
    	if($userAction == 'linkClick'){
    		$BSBAction = 'clicked';
    	}else if($userAction == 'closeClick'){
    		$BSBAction = 'closed';
    	}
    	return $this->bsbmodel->trackBSBAction($bsbTrackingId, $BSBAction);
    }

    public function loadBSBHtml(&$displayData){
		$html = '';
		switch ($displayData['bsbType']) {
			case 'applyPagePromotion':
				if($displayData['sourceApp'] == 'mobile'){
					$html = $this->CI->load->view('BSB/applyPromotionBSB', $displayData, true);
				}
				else{
					$html = $this->CI->load->view('studyAbroadCommon/BSB/applyPromotionBSB', $displayData, true);
				}
				break;
			case 'applyPagePromotionCP':
				if($displayData['sourceApp'] == 'mobile'){
					$html = $this->CI->load->view('categoryPage/widgets/overseasCounselingBannerSA', $displayData, true);
				}
				else{
					$html = $this->CI->load->view('categoryList/abroad/widget/overseasCounselingBanner', $displayData, true);
				}
				break;
		}
		return $html;
	}
	public function getBSBPosition($bsbType){
		$bsbPos = 'bottomFixed';
		switch ($bsbType) {
			case 'applyPagePromotion':
				$bsbPos = 'bottomFixed';
				break;
			case 'applyPagePromotionCP':
				$bsbPos = 'inlineTuple';
				break;
		}
		return $bsbPos;
	}

	public function getBSBPageUrl($bsbType){
		$url = '';
		switch ($bsbType) {
			case 'applyPagePromotion':
			case 'applyPagePromotionCP':
				$url = SHIKSHA_STUDYABROAD_HOME.'/apply';
				break;
		}
		return $url;
	}
}
?>