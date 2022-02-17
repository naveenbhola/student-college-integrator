<?php


class IIMPredictor extends MX_Controller
{

	function __construct(){
		parent::__construct();
        $this->iimpredictormodel = $this->load->model('IIMPredictor/iimpredictormodel');
        $loggedInUserData = $this->getLoggedInUserData();
        
        $this->userId = $loggedInUserData['userId'];
	}
	
	/*private function _layerData() {
		//	load config
		require_once APPPATH.'modules/CollegePredictor/IIMPredictor/config/ICPConfig.php';
		$this->board = $Board;
		$this->graduationStream = $graduationStream;
		

	}*/
	private function _layerData() {
		$returnData = $this->iimpredictormodel->getBoardsAndGraduationStream();
		$simplifiedData = $this->_getSimplifiedData($returnData);
		$this->board = $simplifiedData["board"];
		$this->graduationStream = $simplifiedData["graduation_stream"];
	}

	private function _getSimplifiedData($returnData){
		$board  = $returnData["board"];
		$graduationStream = $returnData["graduation_stream"];
		$boardSimplified = array();
		$graduationStreamSimplified = array();
		foreach ($board as $key => $boardName) {
			$boardSimplified[$boardName["board_alias"]] = $boardName["board_name"];
		}
		foreach ($graduationStream as $key => $graduationStreamName) {
			$graduationStreamSimplified[$graduationStreamName["stream_alias"]] = $graduationStreamName["stream_name"];
		}
		$simplifiedData["board"] = $boardSimplified;
		$simplifiedData["graduation_stream"] = $graduationStreamSimplified;
		return $simplifiedData;
	}


	private function _mappingData(){
		require_once APPPATH.'modules/CollegePredictor/IIMPredictor/config/ICPConfig.php';
		$this->iim_articles_link = $article_page_links;
        $this->fieldsmapping = $ICP_fields_Mapping;
        $this->iim_icp_links = $ILP_Page_Links;
        $this->board = $Board;
	$this->graduationStream = $graduationStream;
	}
	
	private function _fetchingUserDataCondition(){ 

		if($_REQUEST['type'] == 'repeat' && $_REQUEST['modify'] == 'yes'){
			$pageId = 'ChangeCatScore';
			$this->trackICPClickData($pageId);
		} else if ($_REQUEST['type'] == 'repeat') {
			$pageId = 'ResultPageStartAgain';

			$this->trackICPClickData($pageId);
		}

		if($_REQUEST['type'] == 'repeat' && $this->userId > 0){
			return true;
		}else{
			// if($_COOKIE['IIMPredictor'] == 'interimResult'){
			// 	return true;
			// }
			return false;
		}
	}

	public function load() {
		$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : NULL;
		if($type != '' && $type != 'repeat'){
    	 header("Location:/mba/resources/iim-call-predictor",TRUE,301);
		}
		$this->_layerData();

		$data['validateuser']  = $this->checkUserValidation();
		if($this->_fetchingUserDataCondition()){
			if($this->userId > 0){
				$data['userData'] = $this->iimpredictormodel->getLatestUserData($this->userId);
			}else{
				$lastId = $this->getLastTrackingId(); 
		        $data['userData'] = $this->iimpredictormodel->getUserTrackingData($lastId);

			}
			$data['userData']['monthEx'] = round($data['userData']['experience']%12);
			$data['userData']['yearEx'] = (int)($data['userData']['experience']/12);
		}

		
		$data['board'] = $this->board;
		$data['gradstream'] = $this->graduationStream;

		$currrentYear = date("Y");
		$data['gradYear'] = range($currrentYear+7, $currrentYear-10);
		$data['userId'] = $this->userId;
		$IIMPredictorLib = $this->load->library('IIMPredictor/IIMPredictorLib');
		$beaconTrackData = $IIMPredictorLib->pageViewTracking('iimPredictorInput');
		$data['beaconTrackData'] = $beaconTrackData;	
		$data['GA_userLevel'] = $this->userId > 0 ? 'Logged In' : 'Non-Logged In';
		
		$this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_IIMPredictor');
        $data['dfpData']  = $dfpObj->getDFPData($data['validateuser'], $dpfParam);
        $this->benchmark->mark('dfp_data_end');

		$this->load->view('IIMPredictor/icpMainInputPage',$data);
	}

	function seoData(){
		$this->load->config("IIMPredictor/percentileConfig");
		$data['title'] = $this->config->item("seo_title");
		$data['description'] = $this->config->item("seo_description");
		$data['keywords'] = $this->config->item("keywords");

		return $data;
	}

	function getPredictedPercentileForScore($score){
		$this->load->config("IIMPredictor/percentileConfig");
		$catScoreToPercentileMapping = $this->config->item("catScoreToPercentileMapping");
		$score = $score / 5;
		return $catScoreToPercentileMapping[$score];
	}

	public function iimPercentilePredictor(){
		
        $this->cookieName = 'catScore';
        $displayData['examName'] = 'Cat';
        $displayData['cookieName'] = $this->cookieName;
	    $this->userStatus = $this->checkUserValidation();
	    $displayData['validateuser'] = $this->userStatus;
		
		if(isset($_COOKIE[$this->cookieName]) && $_COOKIE[$this->cookieName]>0 && is_array($this->userStatus)){
        	redirect('mba/cat-exam-predicted-percentile-cut-off' , 'location');
        }       	

       	$seoDetails = $this->seoData(); 
		if(is_array($seoDetails)){
            $displayData['m_meta_title'] = $seoDetails['title'];
            $displayData['m_meta_description'] = $seoDetails['description'];
            $displayData['m_meta_keywords'] = $seoDetails['keywords'];
            $displayData['canonicalURL'] = SHIKSHA_HOME.'/mba/cat-exam-percentile-predictor';
        }

        $this->load->config('common/examGroupConfig');
        $examGroupConfig = $this->config->item('examMapping');
        $displayData['eResponseData'] = $examGroupConfig['CAT'];

      	$IIMPredictorLib = $this->load->library('IIMPredictor/IIMPredictorLib');
		$beaconTrackData = $IIMPredictorLib->pageViewTracking('iimPercentileInput');
		$displayData['beaconTrackData'] = $beaconTrackData;
		$displayData['GA_userLevel'] = $this->userId > 0 ? 'Logged In' : 'Non-Logged In';

        $displayData['boomr_pageid'] = 'IIM_PERCENTILE';
        $displayData['validateuser'] = $this->userStatus;
		$this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_IIMPredictor','pageType'=>'result');
        $displayData['dfpData']  = $dfpObj->getDFPData($this->checkUserValidation(), $dpfParam);
        $this->benchmark->mark('dfp_data_end');
        $this->load->view('IIMPredictor/iimPercentile/iimPercentileMainPage',$displayData);
	}	

	public function iimPercentileResultPage(){
		
		$this->cookieName = 'catScore';
		$this->userStatus = $this->checkUserValidation();
			if(!(isset($_COOKIE[$this->cookieName]) && is_array($this->userStatus))){
			redirect('mba/cat-exam-percentile-predictor' , 'location');	
        }
		else{
        	$this->_mappingData();
        	$this->load->config("IIMPredictor/percentileConfig");
        	$catScoreDefalutData = $this->config->item("defaultValuesForPercentilePredictor");
        	$data = $catScoreDefalutData;
        	$displayData =  modules::run('IIMPredictor/IIMPredictorController/startIIMPredictor',$data);

        	$displayData['elegibilitylinks'] = $this->iim_articles_link;
        	$displayData['fieldsmapping'] = $this->fieldsmapping;
        	$displayData['iim_icp_links'] = $this->iim_icp_links;

			$displayData['catScore'] = $_COOKIE[$this->cookieName];
        	$score = $displayData['catScore'];
        	$displayData['percentile'] = $this->getPredictedPercentileForScore($score);
        	
    		$seoDetails = $this->seoData(); 
			if(is_array($seoDetails)){
        	    $displayData['m_meta_title'] = $seoDetails['title'];
        	    $displayData['m_meta_description'] = $seoDetails['description'];
            	$displayData['m_meta_keywords'] = $seoDetails['keywords'];
        	    $displayData['canonicalURL'] = SHIKSHA_HOME.'/mba/cat-exam-predicted-percentile-cut-off';
        	}   
        	$displayData['boomr_pageid'] = 'IIM_PERCENTILE_RESULT_PAGE';

          	$IIMPredictorLib = $this->load->library('IIMPredictor/IIMPredictorLib');
    		$beaconTrackData = $IIMPredictorLib->pageViewTracking('iimPercentileOutput');
    		$displayData['beaconTrackData'] = $beaconTrackData;
    		$displayData['GA_userLevel'] = $this->userId > 0 ? 'Logged In' : 'Non-Logged In';
    		    	
    		$this->benchmark->mark('dfp_data_start');
            $dfpObj   = $this->load->library('common/DFPLib');

            $dpfParam = array('parentPage'=>'DFP_IIMPredictor','pageType'=>'result','iimPercentile'=>$displayData['percentile']);
            
            $displayData['dfpData']  = $dfpObj->getDFPData($this->checkUserValidation(), $dpfParam);
            $displayData['validateuser'] = $this->userStatus;
            $this->benchmark->mark('dfp_data_end');    	
        	$this->load->view('IIMPredictor/iimPercentile/iimPercentileMainPage',$displayData);
		}	
	}

	private function getLastTrackingId(){
		if(isset($_COOKIE['ICP_data'])){
			$ids = explode(',', $_COOKIE['ICP_data']);
			return $ids[0];
		}else{
			return 0;
		}
	}

	private function _redirectToHomePage(){
		redirect('mba/resources/iim-call-predictor' , 'location');
	}

	public function loadDetailedResultPage() {
		$this->_mappingData();
		$validateuser  = $this->checkUserValidation();
		if($this->userId < 1){
			$this->_redirectToHomePage();
		}

        $lastId = $this->getLastTrackingId(); 

        $userData = $this->iimpredictormodel->getUserTrackingData($lastId);
        
        if(empty($userData)){
        	$this->_redirectToHomePage();
        }
        
        if($_REQUEST['registration'] == 'new' || $userData['userId'] == 0){	

	        $this->iimpredictormodel->updateUserIdinTracking($this->userId, $_COOKIE['ICP_data'], $flag);

        	if($_COOKIE['IIMPredictor'] == 'interimResult'){    		
	        	$this->iimpredictormodel->updateRegistrationFlag($lastId);
        	}
        }

        foreach ($userData as $key => $value) {
        	if(!isset($userData[$key])){
        		unset($userData[$key]);
        	}else{
        		$_POST[$key] = $value;
        	}
        }

        // destroying cookie
        if(isset($_COOKIE['IIMPredictor'])){
	        setcookie('IIMPredictor', '-1',time() - 3600, "/");
        }

	    $iims_results_data =  modules::run('IIMPredictor/IIMPredictorController/startIIMPredictor');
	    $iims_results_data['elegibilitylinks'] = $this->iim_articles_link;
	    $iims_results_data['fieldsmapping'] = $this->fieldsmapping;
	    $iims_results_data['iim_icp_links'] = $this->iim_icp_links;
	    $iims_results_data['validateuser'] = $validateuser;
	    $IIMPredictorLib = $this->load->library('IIMPredictor/IIMPredictorLib');
		$beaconTrackData = $IIMPredictorLib->pageViewTracking('iimPredictorOutput');
		$iims_results_data['gtmParams'] = $IIMPredictorLib->getGTMArray($beaconTrackData['pageIdentifier'], $iims_results_data);		
		$iims_results_data['beaconTrackData'] = $beaconTrackData;
		$iims_results_data['bannerTier'] = $this->getTierValueForBanner($iims_results_data['userData']);
		$iims_results_data['bannerTier'] = $this->getTierValueForBanner($iims_results_data['userData']);

		$iims_results_data['isOutputPage'] = true;

		$iims_results_data['board'] = $this->board;
		$iims_results_data['gradstream'] = $this->graduationStream;
		$currrentYear = date("Y");
		$iims_results_data['gradYear'] = range($currrentYear+7, $currrentYear-10);
		$iims_results_dat['GA_userLevel'] = $this->userId > 0 ? 'Logged In' : 'Non-Logged In';
		$iims_results_data['cmpTrackingKey'] = 1357;
				$iims_results_data['userData']['monthEx'] = round($iims_results_data['userData']['experience']%12);
		$iims_results_data['userData']['yearEx'] = (int)($iims_results_data['userData']['experience']/12);

		$this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_IIMPredictor','pageType'=>'result');
        $iims_results_data['dfpData']  = $dfpObj->getDFPData($validateuser, $dpfParam);
        $this->benchmark->mark('dfp_data_end');

	    $this->load->view('icpMainOutputPage',$iims_results_data);
	}

	private function getTierValueForBanner($userData){
		$bannerTier = 3;
		if(($userData['cat_percentile'] >= 85) || ($userData['cat_percentile'] == '' && $userData['xiithPercentage'] >= 80)){
			$bannerTier = 1;
		}else if(($userData['cat_percentile'] < 85 && $userData['cat_percentile'] >= 60) || ($userData['cat_percentile'] == '' && $userData['xiithPercentage'] < 80 && $userData['xiithPercentage'] >= 60)){
			$bannerTier = 2;
		}
		return $bannerTier;
	}

	public function getIIMCallPredictorWidget($frompage){
		$displayData['frompage'] = $frompage;
		$this->load->view('mIIMPredictor5/IIMCallPredictorWidget', $displayData);
	}

	function showRegistrationForm(){
		$data['gradYear'] = $this->input->post('gradYear', true);
		$data['catScore'] = $this->input->post('catScore', true);

        echo $this->load->view('IIMPredictor/icpRegistration', $data);
    }

    function trackICPClickData($pageIdArg){
    	$this->load->helper('utility_helper');
		$sessionId = getVisitorSessionId();

		$pageId = $this->input->post('pageId');
		
		if(empty($pageId)){
			$pageId = $pageIdArg;
		}

		$deviceType ='';

		if(isMobileRequest()){
			$deviceType = 'Mobile';
		} else{
			$deviceType = 'Desktop';
		}

		
		if(empty($pageId) || $pageId === 0){
			return;
		}
    	$this->iimpredictormodel->trackICPClickData($sessionId,$pageId,$deviceType);

    	return;
    }

    function getDataForInterimScreen(){
    	$this->_mappingData();
        $isCatFlowStep = !empty($_POST['isCatScoreFlow']) ? $this->input->post('isCatScoreFlow') : false;
    	$dummy_data =  modules::run('IIMPredictor/IIMPredictorController/getEligibilityCount');
		$dummy_data['fieldsmapping'] = $this->fieldsmapping;
		if(!$isCatFlowStep)
        {
			$dummy_data['interimOutputResult'] = $this->load->view('IIMPredictor/iimPredictorInterimResult',$dummy_data,true);
        }
		echo json_encode($dummy_data);
    }

    function loadBannerForInterimScreen(){
    	if($this->input->is_ajax_request()){
    		$bannerTier = $this->input->post('bannerTier');
    		if(in_array($bannerTier, array(1,2,3))){
    			$bannerProperties = array('pageId'=>'IIMCP', 'pageZone'=>'TIER'.$bannerTier.'_DESKTOP_RIGHT');
    			$html = $this->load->view('common/banner', $bannerProperties, true);
    			$heading = '<p class="bnr-hd">Featured College</p>';
    			$html = $heading.$html;
    			echo json_encode(array('bannerHtml' => $html, 'bannerId' => $bannerProperties['pageZone']));
    		}else{
    			echo '';
    		}
    	}else{
    		echo '';
    	}
    }
   

    /*private function _getCompositeScore($instittuteId,$xth,$xiith,$grad,$va,$di,$qa,$cs,$ad,$gd){
    	$compositeScore;
    	switch($instittuteId){
    		case 307:
    			$compositeScore = (($xth+$xiith+$grad)/30)*
    	}

    }*/
}

?>
