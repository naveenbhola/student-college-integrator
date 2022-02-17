<?php
	/**
	 * 
	 */
	class MBAPredictor extends ShikshaMobileWebSite_Controller
	{
		
		

		function __construct(){
			parent::__construct();
	        $this->iimpredictormodel = $this->load->model('IIMPredictor/iimpredictormodel');
	        $loggedInUserData = $this->getLoggedInUserData();
	        /*$this->load->helper(array('mcommon5/mobile_html5'));*/
	        $this->userId = $loggedInUserData['userId'];
	        $this->mbaLib = $this->load->library('IIMPredictor/MBAPredictorLib');
	        $this->load->config('IIMPredictor/newPredictorConfig');
	        $this->load->helper('IIMPredictor/callPredictor');
	        $this->load->config('CollegeReviewForm/collegeReviewConfig');
		}
		
		/*private function _layerData() {
			//	load config
			require_once APPPATH.'modules/CollegePredictor/IIMPredictor/config/ICPConfig.php';
			$this->board = $Board;
			$this->graduationStream = $graduationStream;
			

		}*/
		/*private function _layerData() {
			$returnData = $this->iimpredictormodel->getBoardsAndGraduationStream();
			$simplifiedData = $this->_getSimplifiedData($returnData);
			$this->board = $simplifiedData["board"];
			$this->graduationStream = $simplifiedData["graduation_stream"];
		}*/

		


		/*private function _mappingData(){
			require_once APPPATH.'modules/CollegePredictor/IIMPredictor/config/ICPConfig.php';
			$this->iim_articles_link = $article_page_links;
	        $this->fieldsmapping = $ICP_fields_Mapping;
	        $this->iim_icp_links = $ILP_Page_Links;
	        $this->board = $Board;
			$this->graduationStream = $graduationStream;
		}*/
		function getMappingData(){
			$mappingData = $this->mbaLib->getBoardsAndGraduationStreamsData();
			$mappingData['fieldsMapping'] = $this->config->item("ICP_fields_Mapping");
			return $mappingData;
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
				if($_COOKIE['IIMPredictor'] == 'interimResult'){
					return true;
				}
				return false;
			}
		}

		public function load() {
			$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : NULL;
			if($type != '' && $type != 'repeat'){
	    	 header("Location:/mba/resources/iim-call-predictor",TRUE,301);
			}
			
			$boradStreamsData = $this->mbaLib->getBoardsAndGraduationStreamsData();

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

			$this->load->config('common/examGroupConfig');
	        $examGroupConfig = $this->config->item('examMapping');
	        $data['eResponseData'] = $examGroupConfig['CAT'];

			$data['board'] = $boradStreamsData['board'];
			$data['gradstream'] = $boradStreamsData['graduation_stream'];

			$data['instituteTableData'] = $this->mbaLib->getInstituteNamesInPredictorTable();

			$currrentYear = date("Y");
			$data['gradYear'] = range($currrentYear+7, $currrentYear-10);
			$data['userId'] = $this->userId;
			$IIMPredictorLib = $this->load->library('IIMPredictor/IIMPredictorLib');
			$beaconTrackData = $IIMPredictorLib->pageViewTracking('iimPredictorInput');
			$data['beaconTrackData'] = $beaconTrackData;	

			$data['GA_userLevel'] = $this->userId > 0 ? 'Logged In' : 'Non-Logged In';

			$data['noJqueryMobile'] = true;
			$data['boomr_pageid'] = 'mobile_icp_page';
			
			$this->benchmark->mark('dfp_data_start');
	        $dfpObj   = $this->load->library('common/DFPLib');
	        $dpfParam = array('parentPage'=>'DFP_IIMPredictor');
	        $data['dfpData']  = $dfpObj->getDFPData($data['validateuser'], $dpfParam);
	        $this->benchmark->mark('dfp_data_end');
	        $this->load->view('iimPredictorMainPage',$data);
		}

		/*function seoData(){
			$this->load->config("IIMPredictor/percentileConfig");
			$data['title'] = $this->config->item("seo_title");
			$data['description'] = $this->config->item("seo_description");
			$data['keywords'] = $this->config->item("keywords");

			return $data;
		}
*/
		/*function getPredictedPercentileForScore($score){
			$this->load->config("IIMPredictor/percentileConfig");
			$catScoreToPercentileMapping = $this->config->item("catScoreToPercentileMapping");
			$score = $score / 5;
			return $catScoreToPercentileMapping[$score];
		}*/

		/*public function iimPercentilePredictor(){
			
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
		}*/	

		/*public function iimPercentileResultPage(){
			
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
		}*/

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

	        //delete below code after testing before going to production
	        $pageNum = 1;

	        /*if(!empty($_GET['pagenum']) && is_numeric($_GET['pagenum'])) {
	        	$pageNum = $_GET['pagenum'];
	        }*/

		    //$iims_results_data =  modules::run('IIMPredictor/IIMPredictorController/startIIMPredictor');
		    $iims_results_data = $this->mbaLib->startIIMPredictor(array(),$this->userId,IIM_RESULTS_YEAR,$pageNum,true,MOB_IIM_RESULTS_LIMIT);

		    // check and unset user_tracking_data from $iims_results_data varaible

		    $mappingData = $this->getMappingData();
		    //$iims_results_data['elegibilitylinks'] = $this->iim_articles_link;
		    $iims_results_data['fieldsmapping'] = $mappingData['fieldsMapping'];
		    //$iims_results_data['iim_icp_links'] = $this->iim_icp_links;
		    $iims_results_data['validateuser'] = $validateuser;
		    $IIMPredictorLib = $this->load->library('IIMPredictor/IIMPredictorLib');
			$beaconTrackData = $IIMPredictorLib->pageViewTracking('iimPredictorOutput');
			$iims_results_data['gtmParams'] = $IIMPredictorLib->getGTMArray($beaconTrackData['pageIdentifier'], $iims_results_data);		
			$iims_results_data['beaconTrackData'] = $beaconTrackData;
			$iims_results_data['bannerTier'] = $this->getTierValueForBanner($iims_results_data['userData']);
			//$iims_results_data['bannerTier'] = $this->getTierValueForBanner($iims_results_data['userData']);

			$iims_results_data['instituteTableData'] = $this->mbaLib->getInstituteNamesInPredictorTable();

			$iims_results_data['noJqueryMobile'] = true;
			$iims_results_data['isOutputPage'] = true;
			$iims_results_data['boomr_pageid'] = 'mobile_icp_page';

			$iims_results_data['board'] = $mappingData['board'];
			$iims_results_data['gradstream'] = $mappingData['graduation_stream'];
			$currrentYear = date("Y");
			$iims_results_data['gradYear'] = range($currrentYear+7, $currrentYear-10);
			$iims_results_data['GA_userLevel'] = $this->userId > 0 ? 'Logged In' : 'Non-Logged In';
			$iims_results_data['cmpTrackingKey'] = 1357;
			$iims_results_data['userData']['monthEx'] = round($iims_results_data['userData']['experience']%12);
			$iims_results_data['userData']['yearEx'] = (int)($iims_results_data['userData']['experience']/12);

			$this->benchmark->mark('dfp_data_start');
	        $dfpObj   = $this->load->library('common/DFPLib');
	        $dpfParam = array('parentPage'=>'DFP_IIMPredictor','pageType'=>'result');
	        $iims_results_data['dfpData']  = $dfpObj->getDFPData($validateuser, $dpfParam);
	        $this->benchmark->mark('dfp_data_end');

	        $iims_results_data['pageNum'] = $pageNum;
	        if($iims_results_data['predictorData']['eligibilityCount'] > 0){
	        	$iims_results_data['resultType'] = "eligibility";	
	        }else{
	        	$iims_results_data['resultType'] = "inEligibility";
	        }
	        
	        $iims_results_data['limit_results'] = MOB_IIM_RESULTS_LIMIT;

	        //_p($iims_results_data);die;
	    	$this->benchmark->mark('shortlist_data_start');
	    	$iims_results_data['shortlistedCoursesOfUser']	= Modules::run('myShortlist/MyShortlist/getShortlistedCourse', $this->userId);
	    	$this->benchmark->mark('shortlist_data_end');

	    	$this->benchmark->mark('eb_data_start');
	    	// this is used for request e-brochure
			$applied_courses = array();
			if($_COOKIE['applied_courses']) {
				$applied_courses = json_decode(base64_decode($_COOKIE['applied_courses']),true);
			}
			$iims_results_data['applied_courses'] = $applied_courses;
			$this->benchmark->mark('eb_data_end');
			
		    $this->load->view('iimpredictorOutPutMainPage',$iims_results_data);
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

			$deviceType ='Mobile';
/*
			if(isMobileRequest()){
				$deviceType = 'Mobile';
			} else{
				$deviceType = 'Desktop';
			}*/

			
			if(empty($pageId) || $pageId === 0){
				return;
			}
	    	$this->iimpredictormodel->trackICPClickData($sessionId,$pageId,$deviceType);

	    	return;
	    }

	    function getDataForInterimScreen(){
	    	//$this->_mappingData();
	        $isCatFlowStep = !empty($_POST['isCatScoreFlow']) ? $this->input->post('isCatScoreFlow') : false;
	        $dummy_data = $this->getEligibilityCount();
	    	//$dummy_data =  modules::run('IIMPredictor/IIMPredictorController/getEligibilityCount');
			$dummy_data['fieldsmapping'] = $this->fieldsmapping;
			if(!$isCatFlowStep)
	        {
				$dummy_data['interimOutputResult'] = $this->load->view('IIMPredictor/iimPredictorInterimResult',$dummy_data,true);
	        }
			echo json_encode($dummy_data);
	    }

	    function getEligibilityCount(){
	    	return $this->mbaLib->getEligibilityCount($this->userId,IIM_RESULTS_YEAR);
	    }

	    function loadBannerForInterimScreen(){
	    	if($this->input->is_ajax_request()){
	    		$bannerTier = $this->input->post('bannerTier');
	    		if(in_array($bannerTier, array(1,2,3))){
	    			$bannerProperties = array('pageId'=>'IIMCP', 'pageZone'=>'TIER'.$bannerTier.'_MOBILE');
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

	    function getIIMPredictorResultsForLoadMore(){
	    	$validateuser  = $this->checkUserValidation();
			if($this->userId < 1){
				echo "";
			}
			$pageNum = $this->input->post('pageNum');
			$resultType = $this->input->post("resultType");
			$userData = $this->input->post("userData");

			if(empty($pageNum) || $pageNum < 1 || !in_array($resultType, array('eligibility','inEligibility')))
				return;
			$iims_results_data = $this->mbaLib->startIIMPredictor(json_decode($userData,true),$this->userId,IIM_RESULTS_YEAR,$pageNum,true,MOB_IIM_RESULTS_LIMIT,$resultType);
			
			$mappingData = $this->getMappingData();
			$iims_results_data['fieldsmapping'] = $mappingData['fieldsMapping'];
			$iims_results_data['isAjaxCall'] = true;
			$iims_results_data['pageNum'] = $pageNum;

			$this->benchmark->mark('dfp_load_data_start');
	        $dfpObj   = $this->load->library('common/DFPLib');
	        $dpfParam = array('parentPage'=>'DFP_IIMPredictor','pageType'=>'result');
	        $iims_results_data['dfpData']  = $dfpObj->getDFPData($validateuser, $dpfParam);
	        $this->benchmark->mark('dfp_load_data_end');

			$htmlOutput = $this->load->view('outputPage',$iims_results_data,true);
			echo $htmlOutput;

	    }

	    /*function getSortedInstitutes($institutes,$category){
	    	$returnData = $this->mbaLib->getSortedInstitutes(1,'General');
	    	$this->mbaLib->getInstituteRating(array(1));
	    	return $returnData;	
	    }*/
	   

	    /*private function _getCompositeScore($instittuteId,$xth,$xiith,$grad,$va,$di,$qa,$cs,$ad,$gd){
	    	$compositeScore;
	    	switch($instittuteId){
	    		case 307:
	    			$compositeScore = (($xth+$xiith+$grad)/30)*
	    	}

	    }*/

	}
?>
