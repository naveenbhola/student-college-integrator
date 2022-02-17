<?php
/*
   Copyright 2015 Info Edge India Ltd

   $Author: Ankur

   $Id: RankPredictorController.php

 */

class RankPredictorController extends MX_Controller
{

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
		$this->load->helper('coursepages/course_page');
		
		$this->load->builder("nationalCourse/CourseBuilder");
		$courseBuilder = new CourseBuilder();
		$this->courseRepository = $courseBuilder->getCourseRepository();

		$this->load->builder("nationalInstitute/InstituteBuilder");
		$instituteBuilder = new InstituteBuilder();
		$this->instituteRepository = $instituteBuilder->getInstituteRepository();

		//$this->national_course_lib = $this->load->library('listing/NationalCourseLib'); // this is use for compare tool, to get brochure url
		
		$this->load->config('RP/RankPredictorConfig',TRUE);
		$this->settingsRankPredictor = $this->config->item('settings','RankPredictorConfig');
		$this->cookieName = 'rankPredictorParam_'.$examName;
		$this->rpFeedBackCookieName = 'rankpredictorFeedBack_'.$examName;
		$this->cookiePercentile1Name = 'rankPredictorPer1_'.$examName;
		$this->cookiePercentile2Name = 'rankPredictorPer2_'.$examName;
		
	}

	function loadNewRankPage($examName = 'jee-main',$folderName = 'b-tech'){
		$this->rankpredictorredirectionrules = $this->load->library('RankPredictorRedirectionRules');
        $this->rankpredictorredirectionrules->redirectionRule($examName);

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
            $displayData['metaKeywords'] = $seoDetails['keywords'];

        }

        $rpmodel = $this->load->model('RP/rpmodel');
        $rpLib = $this->load->library("RankPredictor_Utility");
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
        
        $this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_RankPredictor','entity_id'=>$examName);
        $displayData['dfpData']  = $dfpObj->getDFPData($this->userStatus, $dpfParam);
        $this->benchmark->mark('dfp_data_end');
        
        $this->load->helper('string');
        $displayData['regFormId'] = random_string('alnum', 6);

        $this->rankPredictorLibrary = $this->load->library('RP/RankPredictor_Utility');
        $beaconTrackData = $this->rankPredictorLibrary->prepareBeaconTrackData('rankPredictor',$examName);
        if($displayData['getCookei'][0] != ''){
        	$gtmParams = $this->rankPredictorLibrary->getGTMArray($beaconTrackData['pageIdentifier'],  $examName);		
        	$displayData['gtmParams'] = $gtmParams;			
        }
        $displayData['beaconTrackData'] = $beaconTrackData;
        $this->load->view('RP/V2/rankPredictorMain',$displayData);
	}

	function loadRankPage($examName = 'jee-main'){
		if($examName == 'jee-main' || $examName == 'jee-advanced'){
			$this->loadNewRankPage($examName,'b-tech');
			return;
		}
		$this->load->helper('listingCommon/listingcommon');
		$this->load->helper('listingCommon/listingcommon');
		$this->load->config('CollegeReviewForm/collegeReviewConfig');
        
        $displayData['intervalsDisplayOrder'] = $this->config->item("intervalsDisplayOrder", 'collegeReviewConfig');
        $displayData['aggregateRatingDisplayOrder'] = $this->config->item('aggregateRatingDisplayOrder','collegeReviewConfig');

		$this->rankpredictorredirectionrules = $this->load->library('RankPredictorRedirectionRules');
        $this->rankpredictorredirectionrules->redirectionRule($examName);
		$this->init($examName);
		$displayData['examName'] = $examName;
		$displayData['cookieName'] = $this->cookieName;
		$displayData['rpConfig'] = $this->settingsRankPredictor;
		$displayData['rpFeedBackCookieName'] = $this->rpFeedBackCookieName;
		$seoDetails = $this->getSeoDataForPages($examName);
		
		$this->load->config('common/examGroupConfig');
        $examGroupConfig = $this->config->item('examMapping');
        $originalName = $this->settingsRankPredictor[$examName]['examName'];
        $displayData['eResponseData'] = $examGroupConfig[$originalName];
		$this->load->library("CP/CollegePredictorLibrary");
		$displayData['breadCrumbData'] = $this->collegepredictorlibrary->getBreadCrumbDataForPredictor($displayData['eResponseData'], $originalName, "Rank Predictor");

		if(is_array($seoDetails)){
                        $displayData['m_meta_title'] = $seoDetails['title'];
                        $displayData['m_meta_description'] = $seoDetails['description'];
                        $displayData['canonicalURL'] = $seoDetails['canonicalURL'];
                        $displayData['seoName'] = $seoDetails['seoName'];
                        $displayData['seoYear'] = $seoDetails['seoYear'];
                }

		// Hard code for engineering
		$subcatId = "56";
		$displayData['tab_required_course_page'] = checkIfCourseTabRequired($subcatId);
		$displayData['subcat_id_course_page'] = $subcatId;
		$displayData['course_pages_tabselected'] = 'CollegePredictor';
		//Code added by Ankur for GA Custom variable tracking
		$displayData['subcatNameForGATracking'] = 'B.E./B.Tech';
		$displayData['pageTypeForGATracking'] = 'RANK_PREDICTOR_DETAIL';
                $displayData['validateuser'] = $this->userStatus;
		
		if(isset($_COOKIE[$this->cookieName]) && $_COOKIE[$this->cookieName] !='')
		{
			$displayData['getCookei'] = explode("||",$_COOKIE[$this->cookieName]);
		}	
		
		$this->load->helper('string');
		$displayData['regFormId'] = random_string('alnum', 6);
		$displayData['showCustomizedGNB'] = true;
			
		//below code used for beacon tracking
		$this->rankPredictorLibrary = $this->load->library('RP/RankPredictor_Utility');
		$beaconTrackData = $this->rankPredictorLibrary->prepareBeaconTrackData('rankPredictor',$examName);
		if($displayData['getCookei'][0] != ''){
			$gtmParams = $this->rankPredictorLibrary->getGTMArray($beaconTrackData['pageIdentifier'],  $examName);		
			$displayData['gtmParams'] = $gtmParams;			
		}
		
		$this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_RankPredictor','entity_id'=>$examName);
        $displayData['dfpData']  = $dfpObj->getDFPData($this->userStatus, $dpfParam);
        $this->benchmark->mark('dfp_data_end');

		$displayData['beaconTrackData'] = $beaconTrackData;
		$this->load->view('rankPredictorMain',$displayData);						
	}


	function getSeoDataForPages($examName){
		if($examName == 'jee-main'){
			$seoName = 'JEE Main';
		}
		$seoYear = date("Y");
		$returnArray['seoYear'] = $this->settingsRankPredictor[$examName]['seoYear'];
		$returnArray['seoName'] = $this->settingsRankPredictor[$examName]['seoName'];
		$returnArray['title']   = $this->settingsRankPredictor[$examName]['seoTitle'];
		$returnArray['description'] = $this->settingsRankPredictor[$examName]['seoDescription'];
		$returnArray['canonicalURL'] = $this->settingsRankPredictor[$examName]['canonicalURL'];
		$returnArray['keywords'] = $this->settingsRankPredictor[$examName]['keywords'];
		return $returnArray;
	}
	
	function makeLogEntry($exam,$examRollNumber,$examScore,$boardScore,$apiResponse,$source='desktop'){
		$this->init();
		$data = array();
		$this->load->model('RP/rpmodel');
		$collegepredictorlibrary = $this->load->library('CP/CollegePredictorLibrary');
		//Fetch the User Id
		if(isset($this->userStatus) && is_array($this->userStatus)){
			$signedInUser = $this->userStatus;
			$data['userId'] = $signedInUser[0]['userid'];
		}
		else{
			$data['userId'] = 0;
		}
		if(!empty($_REQUEST['examName'])) {
			$exam = $this->input->post('examName');
			$examScore = $this->input->post('score');
			$examRollNumber = "";
			$boardScore 	= "";
			$apiResponse 	= "";
			if(isMobileRequest()) {
				$source = 'mobile';
			}
		}
		
		if(!empty($exam)){
			$data['examName'] = $exam;
			$data['examRollNumber'] = $examRollNumber;
			$data['examScore'] = $examScore;
			$data['boardScore'] = $boardScore;
			$data['apiResponse'] = $apiResponse;
			$data['source'] = $source;
			$data['percentile1'] = $this->input->post('percentile1');
			$data['percentile2'] = $this->input->post('percentile2');
			$data['type'] = 'rankPredictor';
			$collegepredictorlibrary->addToRabbitMQ($data);
			//$this->rpmodel->insertActivityLog($data);	
		}
	}
	

	function saveFeedback(){
		$this->init();
		$this->load->model('CP/cpmodel');
		$this->cpmodel = new CPModel();
		$data['deviceType'] = "Desktop";
		$data['rating'] = $this->input->post('rating');
		if(strlen($this->input->post('pageName')) > 0) {
			$data['pageName']= $this->input->post('pageName');
		}
		if(isset($this->userStatus) && is_array($this->userStatus)){
			$signedInUser = $this->userStatus;
			$data['userId'] = $signedInUser[0]['userid'];
		}
		else{
			$data['userId'] = 0;
		}
		$data['tabUrl'] = $_SERVER['HTTP_REFERER'];
		$message = isset($_POST['message'])?$this->input->post('message'):'';
		$feedbackId = isset($_POST['feedbackId'])?$this->input->post('feedbackId'):'';
		if($feedbackId !='') $data['feedbackId']=$feedbackId;
		if($message !='') $data['message']=$message;
		$result = $this->cpmodel->saveFeedbackData($data);
		echo $result;
	}
	
	function showCollegePredictorData($examName = 'JEE-Mains', $rank = '1'){

		$this->init($examName);
		
		$this->load->helper('CP/collegepredictor');
                $this->load->builder('BranchBuilder','CP');
                $this->branchBuilder = new BranchBuilder;
                $this->branchRepository = $this->branchBuilder->getBranchRepository();
                $this->load->config('CP/CollegePredictorConfig',TRUE);
                $this->settingArray = $this->config->item('settings','CollegePredictorConfig');
                $examNameNew = strtoupper($examName);
                $examinationName = $this->settingArray[$examNameNew]['examName'];
		
		$callType = "";
                $type    = "";
                $linkUrl = false;
                $defaultView = true;
                $start = 0 ;
                $cookieData = array();
		
		$inputData = $this->settingArray['defaultTabInfo']['DESKTOP'][$examNameNew];
		$inputData['rank'] = $rank;
		setcookie('collegepredictor_search_desktop_'.$examinationName,json_encode($inputData),time() + 86400,'/',COOKIEDOMAIN);
                $displayData = array();
                $displayData = $this->commonData;
                $displayData['tabType'] = 'rank';
                $displayData['tab'] = '1';

                $displayData['courseRepository'] = $this->courseRepository;

                $displayData['instituteRepository'] = $this->instituteRepository;  // this is used only for compare tool to get headerimage of institue

		$displayData['notice'] = $this->settingArray[$examNameNew]['notice'];

                $branchRepoData = $this->branchRepository->findMultiple($inputData);
				$totalBranchCount = $branchRepoData['totalBranchCount'];
				$branchObj = $branchRepoData['branchEntityObj'];
                $branchObjData = $branchObj->getPageData();
                setcookie('COLLEGE_PREDICTOR_TOTAL_RESULT_'.$examinationName,count($branchObjData),time() + 2592000,'/',COOKIEDOMAIN);

                $i=0;$j=0;
                foreach($branchObjData as $key=>$value){
                        $md5Data = md5($value->getInstituteId().$value->getCollegeName().$value->getCityName().$value->getStateName());
                        if(!in_array($md5Data,$tmp['str'])){
                                $displayData['institutesFilter'][$i]['id'] =  $value->getInstituteId();
                                $displayData['institutesFilter'][$i]['collegeName'] =  $value->getCollegeName();
                                $displayData['institutesFilter'][$i]['cityName'] =  $value->getCityName();
                                $displayData['institutesFilter'][$i]['stateName'] =  $value->getStateName();
                                $tmp['str'][] = md5($value->getInstituteId().$value->getCollegeName().$value->getCityName().$value->getStateName());
                                $i++;
                        }

                        if(!in_array($value->getCollegeGroupName(),$temp) && $value->getCollegeGroupName()!=''){
                                $displayData['instituteGroupsFilter'][$j]['collegeGroupName'] =  $value->getCollegeGroupName();
                                $temp[] = $value->getCollegeGroupName();
                                $j++;
                        }

                }
                $defaultCollegePredictorFilters = '';
                $collegePredictorFilters        = '';
                $displayData['mainObj'] = $branchObj->getPageData();
		
		$data = $branchObj->getPageData();
		$displayData['total'] = $totalBranchCount;
		$count = COLLEGE_PREDICTOR_COUNT_OFFSET;
		$result = createPagesForCollegePredictor($data,$start,$count);
		$displayData['objAfterAppliedFilter'] = $data;

                $displayData['branchInformation'] = $result;
                $displayData['start'] = $start;
                $displayData['count'] = $count;
                $displayData['print'] = $isPrint;
                $displayData['examName'] = $examName;
                $displayData['inputData'] = $inputData;

                $configData = $this->getConfigData($examNameNew,'rank');
                foreach($configData as $key=>$value){
                        $displayData[$key]  = $value;
                }
		$displayData['defaultView'] = 1;
		$displayData['rankPredictor'] = '1';
		$displayData['predictorType'] = "RankPredictor";

		$this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_RankPredictor','entity_id'=>$examName);

        $displayData['dfpData']  = $dfpObj->getDFPData($this->userStatus, $dpfParam);

        $this->load->config("dfp/dfpConfig");

        $this->benchmark->mark('dfp_data_end');

		$this->load->view('CP/searchResultsCP',$displayData);
	}

	function callAakashAPI($rollNumber, $examScore, $boardScore){
		$this->init();
                if(isset($this->userStatus) && is_array($this->userStatus)){
                        $signedInUser = $this->userStatus;
                        $userId = $signedInUser[0]['userid'];
                        $userFirstName = $signedInUser[0]['firstname'];
                        $userLastName = $signedInUser[0]['lastname'];
                        $array = explode('|',$signedInUser[0]['cookiestr']);
                        $email = $array[0];
                        $mobile = $signedInUser[0]['mobile'];

                }
                else{
                        return 'User not logged-in';
                }

		if($examScore !=''){
			$url = "http://www.aakash.ac.in/global_script/demofunction.php?type=rank_pretict&name=$userFirstName$userLastName&rollno=$rollNumber&jeemarks=$examScore&emailid=$email&mobile=$mobile";
        		$ch = curl_init();
	        	curl_setopt($ch, CURLOPT_URL, $url);
	        	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,30);
        		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		        $output = curl_exec($ch);
			// Check if any error occurred
			if(curl_errno($ch) > 0)
			{
			    curl_close($ch);
			    return 'Something went wrong.';
			}
        		curl_close($ch);
	        	return $output;
		}else{
			return 'Data is wrong';
		}
	}
	
	function resetRank()
	{
		$source     = isset($_POST['source']) ? $this->input->post('source') : 'desktop';
		$examName   = isset($_POST['exam']) ? $this->input->post('exam') : 'jee-main';
		$cookieName = 'rankPredictorParam_'.$examName;

		$this->init($examName);

		if(isset($_COOKIE[$cookieName]) && $_COOKIE[$cookieName] !='')
		{
			$setDefaultData = explode("||",$_COOKIE[$cookieName]);
		}
		
		$examScore  = isset($_POST['examScore']) ? $this->input->post('examScore') : $setDefaultData[0];
		$boardScore = isset($_POST['boardScore']) ? $this->input->post('boardScore') : $setDefaultData[1];
		$rollNumber = isset($_POST['rollNo']) ? $this->input->post('rollNo') : $setDefaultData[2];
		
		// call to api
		$data['examName'] = $examName;
                $data['rollNumber'] = $rollNumber;
                $data['boardScore'] = $boardScore;
                $data['examScore'] = $examScore;
                $data['source'] = $source;
		$data['userArray'] = $this->userStatus;
 
                $this->load->library('RP/RankPredictor_Utility');
                $rpLibObj = new RankPredictor_Utility;
                echo $rpLibObj->getExamData($data);
	}
	
	function getPredictor()
	{
		if(isset($_POST['examName']) && $this->input->post('examName') == 'jee-main')
		{
			$exam = 'JEE-Mains';
		}else if(isset($_POST['examName'])){
			$exam = $this->input->post('examName');
		}else{
			$exam = 'JEE-Mains';
		}
		$rank = isset($_POST['examRank']) ? $this->input->post('examRank') : 1;
		echo $this->showCollegePredictorData($exam,$rank);
	}
	
	function getCollegePredictorWidget()
	{
		$this->load->view('predictorWidget');
	}

	/**
	* type - no return 
	* desc - Show Rank and College predictors at one place
	* popularCP - array value should be same as college predictor CPEXAMS array key
	* added by akhter
	**/	
	function commonPredictorPage(){

		$displayData['meta_title'] = 'Rank and Colleges Predictors for Engineering Exams';
        $displayData['meta_description'] = 'Engineering exams rank and college predictors to predict your rank and colleges based on your score.';
        $displayData['canonicalURL'] = RANK_PREDICTOR_BASE_URL.'/rank-colleges-predictors';

		$this->load->config('RP/RankPredictorConfig',TRUE);
		$examList = $this->config->item('settings','RankPredictorConfig');
		$examList = $examList['RPEXAMS'];
		ksort($examList);
		$displayData['validateuser'] = $this->checkUserValidation();
		$displayData['rankPredictor'] = $examList;
		
		$this->load->config('CP/CollegePredictorConfig',TRUE);
		$settings = $this->config->item('settings','CollegePredictorConfig');
		$cPredictor = $settings['CPEXAMS'];
		ksort($cPredictor);
		$displayData['cPredictor'] = $cPredictor;
		$displayData['popularCP'] = $settings['CPPOPULAR']; // add popular college predictor exam

		$this->load->view('rankCollegePredictor',$displayData);
	}

	function commonPredictorPage301(){
		$url = RANK_PREDICTOR_BASE_URL.'/rank-colleges-predictors';
		header("Location: $url",TRUE,301);exit();
	}

}

?>
