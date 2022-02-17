<?php

class RankPredictorMController extends ShikshaMobileWebSite_Controller {
	
	private $userStatus;
	
	function init($examName){
		$library=array('ajax');
		$helper=array('url','image','shikshautility','utility_helper');
		if(is_array($helper)){
			$this->load->helper($helper);
		}
		if(is_array($library)){
			$this->load->library($library);
		}
		if(($this->userStatus == "")){
			$this->userStatus = $this->checkUserValidation();
		}
		
		$this->load->config('CP/CollegePredictorConfig',TRUE);

        $this->settingArray = $this->config->item('settings','CollegePredictorConfig');
        $this->load->config('mcommon5/mobi_config');

        $this->load->config('RP/RankPredictorConfig',TRUE);
        $this->settingsRankPredictor = $this->config->item('settings','RankPredictorConfig');
        $this->load->helper('mcommon5/mobile_html5');
        $this->load->helper('CP/collegepredictor');
		
	}

	
	function loadNewRankPage($examName = 'jee-main',$folderName = 'b-tech'){
		$this->rankpredictorredirectionrules = $this->load->library('RP/RankPredictorRedirectionRules');
        $this->rankpredictorredirectionrules->redirectionRule($examName);

        $this->load->helper('mcommon5/mobile_html5');

        $this->load->config('RP/RankPredictorConfig',TRUE);
        $this->settingsRankPredictor = $this->config->item('settings','RankPredictorConfig');

        $this->load->helper('listingCommon/listingcommon');
		$this->load->config('CollegeReviewForm/collegeReviewConfig');

        $this->load->config('common/examGroupConfig');
        $examGroupConfig = $this->config->item('examMapping');
        $originalName = $this->settingsRankPredictor[$examName]['examName'];
        $displayData['eResponseData'] = $examGroupConfig[$originalName];
        $this->load->library("CP/CollegePredictorLibrary");
        $displayData['breadCrumbData'] = $this->collegepredictorlibrary->getBreadCrumbDataForPredictor($displayData['eResponseData'], $originalName, "Rank Predictor");
        
        $displayData['intervalsDisplayOrder'] = $this->config->item("intervalsDisplayOrder", 'collegeReviewConfig');
        $displayData['aggregateRatingDisplayOrder'] = $this->config->item('aggregateRatingDisplayOrder','collegeReviewConfig');

        $this->cookieName = 'rankPredictorParam_'.$examName;
        $this->cookiePercentile1Name = 'rankPredictorPer1_'.$examName;
		$this->cookiePercentile2Name = 'rankPredictorPer2_'.$examName;

        $displayData['examName'] = $examName;
        $displayData['cookieName'] = $this->cookieName;
        $displayData['cookieNamePer1'] = $this->cookiePercentile1Name;
        $displayData['cookieNamePer2'] = $this->cookiePercentile2Name;
        $displayData['rpConfig'] = $this->settingsRankPredictor;
        $displayData['displayExamName'] = $this->settingsRankPredictor[$examName]['examName'];
       	$seoDetails = $this->getSeoDataForPages($examName); 
		if(is_array($seoDetails)){
            $displayData['m_meta_title'] = $seoDetails['title'];
            $displayData['m_meta_description'] = $seoDetails['description'];
            $displayData['canonicalURL'] = $seoDetails['canonicalURL'];
            $displayData['seoName'] = $seoDetails['seoName'];
            $displayData['seoYear'] = $seoDetails['seoYear'];
            $displayData['m_meta_keywords'] = $seoDetails['keywords'];

        }

        $rpmodel = $this->load->model('RP/rpmodel');
        $rpLib = $this->load->library("RP/RankPredictor_Utility");

	    $this->userStatus = $this->checkUserValidation();
	    $displayData['validateuser'] = $this->userStatus;
	    $this->collegepredictorlibrary = $this->load->library('CP/CollegePredictorLibrary');

	    $isInputValuesExist = false;

        if($examName == 'jee-main' && (isset($_COOKIE[$this->cookieName]) || isset($_COOKIE[$this->cookiePercentile1Name]) || isset($_COOKIE[$this->cookiePercentile2Name]))){
        	$isInputValuesExist = true;
        }else if(isset($_COOKIE[$this->cookieName])) {
        	$isInputValuesExist = true;
        }

        $displayData['isInputValuesExist'] = $isInputValuesExist;
	    
        if($isInputValuesExist && is_array($this->userStatus)){
        	$displayData['getCookei'] = $_COOKIE[$this->cookieName];
        	$score = $displayData['getCookei'];

        	if($examName == 'jee-main'){

        		$percentile1 = $_COOKIE[$this->cookiePercentile1Name];
        		$percentile2 = $_COOKIE[$this->cookiePercentile2Name];

        		$dbData = $rpLib->getRankBasedOnUserScore($score,$percentile1,$percentile2,$examName);
        		$displayData['userPercentile'] = $dbData['userPercentile'];
        		$displayData['predictedMidPercentile'] = $dbData['midPercentile'];
        		$displayData['predictedMinPercentile'] = $dbData['minPercentile'];
        		$displayData['predictedMaxPercentile'] = $dbData['maxPercentile'];
        	}
        	else{
        		$dbData = $rpmodel->getRankByScore($examName,$score);
        	}
        	$displayData['enteredScore'] = $score;
        	if(!empty($dbData)){
        		$displayData['predictedMinRank'] = $dbData['minRank'];
        		$displayData['predictedMidRank'] = $dbData['midRank'];
        		$displayData['predictedMaxRank'] = $dbData['maxRank'];
        		$data = $this->collegepredictorlibrary->getCollegeDataForRankPredictor('jee-main',$displayData['predictedMidRank']);

        		foreach ($data as $key => $value) {
        			$displayData[$key] = $value;
        		}
        	}
        }
        else{
        	$score = $this->settingsRankPredictor[$examName]['inputField']['score']['maxRange'];
        	if($examName == 'jee-main'){
        		$dbData = $rpLib->getRankBasedOnUserScore($score,$examName);
        	}
        	else{
        		$dbData = $rpmodel->getRankByScore($examName,$score);	
        	}
        	
        	$data = $this->collegepredictorlibrary->getCollegeDataForRankPredictor('jee-main',$dbData['midRank']);
        	foreach ($data as $key => $value) {
        		$displayData[$key] = $value;
        	}
        	$displayData['defaultView'] = true;
        	$displayData['isInputValuesExist'] = false;
        }

        $this->rankPredictorLibrary = $this->load->library('RP/RankPredictor_Utility');
        $beaconTrackData = $this->rankPredictorLibrary->prepareBeaconTrackData('rankPredictor',$examName);
        if($displayData['getCookei'][0] != ''){
        	$gtmParams = $this->rankPredictorLibrary->getGTMArray($beaconTrackData['pageIdentifier'],  $examName);		
        	$displayData['gtmParams'] = $gtmParams;			
        }
        $displayData['beaconTrackData'] = $beaconTrackData;
        $displayData['boomr_pageid'] = 'RANK_PREDICTOR';
        $displayData['validateuser'] = $this->userStatus;

        $this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_RankPredictor','entity_id'=>$examName);
        $displayData['dfpData']  = $dfpObj->getDFPData($this->userStatus, $dpfParam);
        $this->benchmark->mark('dfp_data_end');

        $this->load->view('mRankPredictor/V2/rankPredictorMainPage',$displayData);
	}

	/**
	 *
	 * Show Rank Predictor Mobile Form
	 *
	 * @param    None
	 *
	 */
	function loadRankPage($examName = 'JEE-Mains'){
		if($examName == 'jee-main' || $examName == 'jee-advanced'){
			$this->loadNewRankPage($examName,'b-tech');
			return;
		}

		$this->load->helper('listingCommon/listingcommon');
		$this->load->config('CollegeReviewForm/collegeReviewConfig');

        $displayData['intervalsDisplayOrder'] = $this->config->item("intervalsDisplayOrder", 'collegeReviewConfig');
        $displayData['aggregateRatingDisplayOrder'] = $this->config->item('aggregateRatingDisplayOrder','collegeReviewConfig');

		$this->rankpredictorredirectionrules = $this->load->library('RP/RankPredictorRedirectionRules');
        $this->rankpredictorredirectionrules->redirectionRule($examName);
		$this->init($examName);
		
		$displayData['examName'] = $examName;
		$seoDetails = $this->getSeoDataForPages($examName);
		if(is_array($seoDetails)){
            $displayData['m_meta_title'] = $seoDetails['title'];
            $displayData['m_meta_description'] = $seoDetails['description'];
            $displayData['m_meta_keywords'] = ' ';
            $displayData['canonicalURL'] = $seoDetails['canonicalURL'];
        }

		//Code added by Ankur for GA Custom variable tracking
		$displayData['subcatNameForGATracking'] = 'B.E./B.Tech';
		$displayData['pageTypeForGATracking'] = 'RANK_PREDICTOR_DETAIL';
		$displayData['userStatus']=$this->userStatus;
		$displayData['boomr_pageid'] = 'RANK_PREDICTOR';
		
		$displayData['rankConfigData'] = $this->settingsRankPredictor;
		
        $this->load->config('common/examGroupConfig');
        $examGroupConfig = $this->config->item('examMapping');
        $originalName = $this->settingsRankPredictor[$examName]['examName'];
        $displayData['eResponseData'] = $examGroupConfig[$originalName];
        $this->load->library("CP/CollegePredictorLibrary");
        $displayData['breadCrumbData'] = $this->collegepredictorlibrary->getBreadCrumbDataForPredictor($displayData['eResponseData'], $originalName, "Rank Predictor");

		$cookieName='rankPredictorParam_'.$examName;
		if(isset($_COOKIE[$cookieName]) && $_COOKIE[$cookieName] != '' && isset($this->userStatus[0]['userid']) && $this->userStatus[0]['userid'] != '') {
			$array = explode('||',$_COOKIE[$cookieName]);
			$displayData['msg'] = $this->callBackendAPI($examName,$array[2],$array[0],$array[1],'mobile',false);
		}
		
		//below code used for beacon tracking
		$this->rankPredictorLibrary = $this->load->library('RP/RankPredictor_Utility');
		$beaconTrackData = $this->rankPredictorLibrary->prepareBeaconTrackData('rankPredictor',$examName);
		if($displayData['getCookei'][0] != ''){
			$gtmParams = $this->rankPredictorLibrary->getGTMArray($beaconTrackData['pageIdentifier'],  $examName);		
			$displayData['gtmParams'] = $gtmParams;			
		}
		$displayData['beaconTrackData'] = $beaconTrackData;

		$this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_RankPredictor','entity_id'=>$examName);
        $displayData['dfpData']  = $dfpObj->getDFPData($this->userStatus, $dpfParam);
        $this->benchmark->mark('dfp_data_end');

		//below line is used for conversion tracking purpose
		$displayData['trackingPageKeyId']=307;
		$displayData['downloadtrackingPageKeyId']=304;
		$displayData['shortlistTrackingPageKeyId']=306;
		$displayData['comparetrackingPageKeyId'] = 305;
		$displayData['pageIdentifier'] = 'rankPredictor';
		$this->load->view('rankPredictorMainPage',$displayData);

	}
	
	function getSeoDataForPages($examName){
		if($examName == 'jee-main'){
			$seoName = 'JEE Main';
		}
		$seoYear = date("Y");
		$returnArray['seoYear'] = $this->settingsRankPredictor[$examName]['seoYear'];
		$returnArray['seoName'] = $this->settingsRankPredictor[$examName]['seoName'];
		$returnArray['title'] = $this->settingsRankPredictor[$examName]['seoTitle'];
		$returnArray['description'] = $this->settingsRankPredictor[$examName]['seoDescription'];
		$returnArray['canonicalURL'] = $this->settingsRankPredictor[$examName]['canonicalURL'];
		$returnArray['keywords'] = $this->settingsRankPredictor[$examName]['keywords'];
		return $returnArray;
	}
	
	 /******************

         * Function to get the Search results

         * This will take the POST variables, set the Cookie, fetch the results and return the whole HTML of the result set

         ******************/

    function getCollegePredictorResults($examName = 'JEE-Mains', $rank = '1',$trackingPageKeyId='',$shortlistTrackingPageKeyId='',$comparetrackingPageKeyId='', $downloadtrackingPageKeyId=''){

        $this->init();
		$this->load->config('CP/CollegePredictorConfig',TRUE);
        $this->settingArray = $this->config->item('settings','CollegePredictorConfig');
        //Fetch the Post variables
        //get Shortlisted course from Db/cookie

        $getMShortlistedCourse = Modules::run('mobile_category5/CategoryMobile/getMShortlistedCourse');

        $courseShortArray = $getMShortlistedCourse;

        //request brochureUrl from courseId

        $displayData['courseShortArray'] = $courseShortArray;



        $start = (isset($_POST['start']))?$this->input->post('start'):0;
        $count = (isset($_POST['count']))?$this->input->post('count'):10;
        $rank = $rank;
        $college = '';
        $filterTypeDataStatus = '';
		$examNameInUpper = strtoupper($examName);
		
        if(isset($_COOKIE['collegePredictor_mobile_examName']) && !empty($_COOKIE['collegePredictor_mobile_examName']) ) {
            $examName = $_COOKIE['collegePredictor_mobile_examName'];
        }

        if($rank>0){
            $tabType = 'rank';
			$fetchArray = $this->settingArray['defaultTabInfo']['MOBILE5'][$examNameInUpper];
			//$fetchArray = array('rankType'=>$rankType,'categoryName'=>$category,'domicile'=>'NO','rank'=>$rank,'round'=>$round,'examName'=>$examName,'tabType'=>$tabType,'stateName'=>$stateName);
        }
		$fetchArray['rank'] = $rank;
        //Store these Search values in the Cookie so that we can fetch the results

        setcookie('collegepredictor_search_' . $examName,json_encode($fetchArray),time() + 2592000,'/',COOKIEDOMAIN);

        $settingArray = $this->settingArray;
        $this->load->builder('BranchBuilder','CP');
        $branchBuilder = new BranchBuilder;
        $this->branchRepository = $branchBuilder->getBranchRepository();

        $branchRepoData = $this->branchRepository->findMultiple($fetchArray);
		$totalBranchCount = $branchRepoData['totalBranchCount'];
		$branchObj = $branchRepoData['branchEntityObj'];

        $displayData['totalResultsApplyFilter'] = '-1';
        $displayData['showFiltersOnMobile'] = $this->settingArray[$examNameInUpper]['showFiltersOnMobile'];
        $resultInformation = $branchObj->getPageData();
        $displayData['totalResults'] = $totalBranchCount;
        $resultInformation = createPagesForCollegePredictor($resultInformation,$start,$count);

        $this->load->builder("nationalCourse/CourseBuilder");
		$courseBuilder = new CourseBuilder();
		$courseRepository = $courseBuilder->getCourseRepository();

		$this->load->builder("nationalInstitute/InstituteBuilder");
		$instituteBuilder = new InstituteBuilder();
		$instituteRepository = $instituteBuilder->getInstituteRepository();


        $displayData['start'] = $start;
        $displayData['examName'] = $examName;
        $displayData['resultInformation'] = $resultInformation;
        $displayData['userStatus'] = $this->userStatus;
        $displayData['courseRepository'] = $courseRepository;
        $displayData['instituteRepository'] = $instituteRepository;
        $displayData['round'] = $round;
        $displayData['shiksha_site_current_url'] = $_SERVER['HTTP_REFERER'];
        $displayData['shiksha_site_current_refferal'] =  $_SERVER['HTTP_REFERER'];
		$displayData['rankPredictor'] = '1';

		//below line is used for conversion tracking purpose
		if(isset($trackingPageKeyId))
		{
			$displayData['trackingPageKeyId']=$trackingPageKeyId;
		}
		if(isset($shortlistTrackingPageKeyId))
		{
			$displayData['shortlistTrackingPageKeyId']=$shortlistTrackingPageKeyId;
		}
		if( ! empty($comparetrackingPageKeyId))
		{
			$displayData['comparetrackingPageKeyId'] = $comparetrackingPageKeyId;
		}
		if(isset($downloadtrackingPageKeyId)){
			$displayData['downloadtrackingPageKeyId']=$downloadtrackingPageKeyId;
		}
		$displayData['validateuser'] = $this->userStatus;


		$this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_RankPredictor','entity_id'=>$examName);
        $displayData['dfpData']  = $dfpObj->getDFPData($this->userStatus, $dpfParam);
        $this->benchmark->mark('dfp_data_end');


        echo $this->load->view('mcollegepredictor5/collegePredictorList',$displayData);
    }

	function searchRankPredictor(){
		$examName = $this->input->post('examName');
		$rollNumber = $this->input->post('rollNumber');
		$examScore = $this->input->post('examScore');
		$boardScore = $this->input->post('boardScore');
		$source = $this->input->post('source');
		
		if($examName!=''){
			echo $this->callBackendAPI($examName,$rollNumber,$examScore,$boardScore,$source);
		}		
	}
	
	function callBackendAPI($examName,$rollNumber,$examScore,$boardScore,$source,$isAjax=true){
		$this->init($examName);
        $data['examName'] = $examName;
        $data['rollNumber'] = $rollNumber;
        $data['boardScore'] = $boardScore;
        $data['examScore'] = $examScore;
        $data['source'] = $source;
		$data['userArray'] = $this->userStatus;
 
        $this->load->library('RP/RankPredictor_Utility');
        $rpLibObj = new RankPredictor_Utility;
        $msg = $rpLibObj->getExamData($data);		
		
		if($isAjax){
			echo $msg;	
		}
		else{
			return $msg;
		}
	}
	
}


