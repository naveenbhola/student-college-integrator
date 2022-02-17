<?php

class CCHomepageController extends ShikshaMobileWebSite_Controller {
	
	private $userStatus;
	private $noOfInst = 9;
	function init($library=array('ajax'),$helper=array('url','image','shikshautility','utility_helper', 'CA/cahomepage')){
		if(is_array($helper)){
			$this->load->helper($helper);
		}
		if(is_array($library)){
			$this->load->library($library);
		}
		$this->userStatus = $this->checkUserValidation();
		$this->load->model('CA/campusconnectmodel');
		$this->campusconnect = new CampusConnectModel();
		
		$this->campusConnectBaseLib = $this->load->library('CA/ccBaseLib');
	}

	/**
	 *
	 * Show Campus Connect Mobile Homepage
	 *
	 * @param    None
	 * @return   View with the Homepage
	 *
	 */
	function campusConnectHomepage($programId){
        $this->init();
		$programId = !empty($programId)? $programId : 1;

		$programIdMappingData = $this->campusConnectBaseLib->getProgramIdMappingDetails($programId);
		$programIdMappingData = $programIdMappingData[$programId];
		if(empty($programIdMappingData)){
			show_404();
		}

		$this->campusConnectBaseLib->redirectionRule($programIdMappingData['url']);

		$displayData = array();  
		$displayData['validateuser'] = $this->userStatus;
		$displayData['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;	
		$displayData['canonicalURL'] = SHIKSHA_HOME.'/mba/resources/ask-current-mba-students';
		$displayData['boomr_pageid'] = 'CampusConnectHomepage';
		$displayData['m_meta_title'] = $programIdMappingData['title'].' Campus Connect';
        $displayData['m_meta_description'] = 'Talk to '.$programIdMappingData['title'].' college current students online. Ask your questions about courses, eligibility, fees, placements and more to select the right college';
        $displayData['canonicalURL'] = $programIdMappingData['url'];
        $displayData['programId'] = $programId;
        $displayData['setAutoSuggestorOption'] = array('streamId'=>$programIdMappingData['stream_id'],
        	                                           'substreamId'=>$programIdMappingData['substream_id'],
        	                                           'baseCourseId'=>$programIdMappingData['base_course_id']
        	                                          );
		//below code used for beacon tracking
		$displayData['beaconTrackData'] = $this->campusConnectBaseLib->prepareBeaconTrackData($programIdMappingData,$programId);

		$this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_CampusConnect','entity_id'=>$programId,'stream_id'=>$programIdMappingData['stream_id']);
        $displayData['dfpData']  = $dfpObj->getDFPData($displayData['validateuser'], $dpfParam);
        $this->benchmark->mark('dfp_data_end');

		$this->load->view('campus_connect/campusConnectHomepage',$displayData);
	}

/**
	 
	 * Show Campus Connect Mobile Intermediate Page
	 *
	 * Show Campus Connect Mobile Intermediate page
	 *
	 * @param    None
	 * @return   View with the Intermediate page
	 *	NOTE- Intermediatepage of Campus connect has beed droped, so redirect (301) old url to college question page
	 */
	function campusConnectIntermediatePage($url,$instituteId){

		if(!(isset($instituteId)) || !is_numeric($instituteId))
		{
			show_404();exit();
		}
		$interMediateUrl= Modules::run('CA/CampusConnectController/getCampusIntermediateUrl', $instituteId);
		$url = $interMediateUrl['url'];
		header("Location: $url",TRUE,301);
	}
	
	function CampusConnectQuestionsTab()
	{
		
		$this->init();
		$this->load->model('CA/campusconnectmodel');
		$this->ccmodel = new CampusConnectModel();
		$displayData = array();
		$orderOfQuestion = $_POST['orderOfQuestion'];

		$instId = $_POST['instId'];
		if(!is_numeric($instId) || $instId<=0){
			return;
		}
		$courseIds = $this->ccmodel->getCourseIdforInstitute($instId);
		$courseIdArray = array();
		foreach($courseIds as $courseId=>$key){
			$courseIdArray[] = $key['courseIdStr'];
		}

		//Get UserId of all CR's of the Institute
		$courseIds = implode(",",$courseIdArray);
		$userIds = $this->ccmodel->getUserIdforInstitute($courseIds);

		foreach($userIds as $value){
			$finaluserIds[] = $value['userId'];
		}


		$result = $this->ccmodel->getLatestAndPopularQuestions($orderOfQuestion,$finaluserIds,$courseIds);
		$display['displayData'] = $result;
		$display['order']=$orderOfQuestion;
		$display['instituteId'] = $instId;
 		$this->load->view('campus_connect/intermediatePageQuestionsWidget',$display);
	}
	

   /**
	 *
	 * Show Campus Connect College Widget
	 *
	 * @param    None
	 * @return   View with the Homepage
	 *
	 */	
	function campusConnectCollegeCardWidget($programId=1){
		$this->init();
		$instituteIds = array();	
		$widgetType = isset($_POST['widgetType'])?$this->input->post('widgetType'):'topRanked';
		
		if($this->input->is_ajax_request()){
			$programId = isset($_POST['programId'])?$this->input->post('programId'):1;
		}

		$noOfTopInst = $this->noOfInst;
		$instCourseData  =  $this->campusConnectBaseLib->getInstAndCourseOfProgram($programId); 
        foreach($instCourseData as $key=>$val){ 
                        $instituteIds[] = $key; 
        } 
        $instituteIds = array_unique($instituteIds); 
    
        $this->rankingCommonLib = $this->load->library('rankingV2/RankingCommonLibv2'); 

		if($widgetType == 'topRanked'){
			$instRankBySource = $this->rankingCommonLib->getInstituteRankBySource($instituteIds, $noOfTopInst); 
            foreach ($instRankBySource as $instId => $source) { 
                    $sortedInstArr[] = $instId; 
            } 
            // set data for Top Question list
			$setDataForTopQuestions = prepareDataForTopQuestion($instRankBySource, $instCourseData); 
			$displayData['dataForTopQuestion'] = $setDataForTopQuestions;
			$finalResult = $this->campusConnectBaseLib->prepareFinalResultForTopFeaturedCollege($sortedInstArr, $instRankBySource, $instCourseData);
			$displayData['result'] = $finalResult;
			$displayData['questionTrackingPageKeyId'] = 640;
			$displayData['widgetType'] = $widgetType;
		}else if($widgetType == 'featured'){
			
			$instRankBySource = $this->campusConnectBaseLib->getAllPaidInstituteId($instituteIds, $noOfTopInst);
            foreach ($instRankBySource as $instId => $source) { 
                $sortedInstArr[] = $instId; 
            } 
            $finalResult = $this->campusConnectBaseLib->prepareFinalResultForTopFeaturedCollege($sortedInstArr, $instRankBySource, $instCourseData);
			$displayData['result'] = $finalResult;
			$displayData['questionTrackingPageKeyId'] = 641;
			$displayData['widgetType'] = $widgetType;
		}else {
			$instituteData =  $this->campusConnectBaseLib->getInstituteDataForMostViewedAndTrandingWidget($programId,$widgetType,$this->noOfInst,true);

			if($widgetType == 'mostViewed'){
				$displayData['questionTrackingPageKeyId'] = 642;
			}else if($widgetType == 'trending'){
				$displayData['questionTrackingPageKeyId'] = 643;
			}
			$displayData['result'] = $instituteData['finalInstitueDetails'];
			$displayData['widgetType'] = $widgetType;
		}
		if($this->input->is_ajax_request()){
			$this->load->view('campus_connect/collegeCardSliderView',$displayData);
		}else{
			$this->load->view('campus_connect/collegeCardsWidget',$displayData);
		}
	}

	
	function getQuestionsForTopRankedInstitutes()
	{
		if($this->input->is_ajax_request())
		{
			$this->init();
			$displayData = array();
			$postData = (isset($_POST['instIds']) && $_POST['instIds']!='')?$this->input->post('instIds'):'';
			if($postData=='')die('No question available.');

			$getTopRankedInstituteIdsWithCA = json_decode($postData, true);
			$courseIds = array();
			foreach($getTopRankedInstituteIdsWithCA as $val)
			{
				$i++;
				$courseIds[] = $val['courseIdStr'];
				$instituteIds[] = $val['instituteId'];
			}
			$courseIds = array_unique(explode(',',implode(',', $courseIds)));
			$courseIdsStr = implode(',',$courseIds);

			$allQues = $this->campusconnect->getQuestionsForTopRankedColleges($courseIdsStr);
			$qnaData = get24QuestionsForTopRankedColleges($allQues, $instituteIds);
			$qnaData = json_decode($qnaData, true);
			$displayData['quesData'] = $qnaData['finalQuestions'];
			$displayData['finalQuesId'] = $qnaData['finalQuesId'];

			// load the institute builder
		    $this->load->builder("nationalCourse/CourseBuilder");
		    $courseBuilder = new CourseBuilder();
		    $displayData['courseRepo'] = $courseBuilder->getCourseRepository();

			$answerData = $this->campusconnect->getTopRankedRecentAnswers(implode(',', $qnaData['finalQuesId']));
			$answerData = get24AnswersForTopRankedColleges($answerData, $displayData['finalQuesId']);
			$displayData['answerData'] = $answerData;
			
			$this->load->view('campus_connect/questionContainerView',$displayData);
		}
	}
	
	function getQuestionsWithFeaturedAnswers()
	{
		if($this->input->is_ajax_request())
		{
			$this->init();
			$programId = isset($_POST['programId'])?$this->input->post('programId'):1; 
			$allFeaturedAns = $this->campusConnectBaseLib->prepareDataForFeaturedQuestion($programId);
			$ansId = array();
			foreach($allFeaturedAns as $val)
			{
				$ansId[] = $val['threadId'];
				$courseList[$val['threadId']] = $val['courseId'];
			}

			if(!empty($ansId))
			{
				$allQues = $this->campusconnect->getQuestionsWithFeaturedAnswer(implode(',', $ansId));
			}

			foreach ($allQues as $key => $value) {
				if($courseList[$value['msgId']]){
					$value['courseId'] = $courseList[$value['msgId']];
					$allQuesList[] = $value;
				}
			}

			$displayData['quesData'] = $allQuesList;
			$answerData = get24AnswersForQuestionsWithFeaturedAnswer($allFeaturedAns, $allQues);
			$displayData['answerData'] = $answerData;

			// load the institute builder
		    $this->load->builder("nationalCourse/CourseBuilder");
		    $courseBuilder = new CourseBuilder();
		    $displayData['courseRepo'] = $courseBuilder->getCourseRepository();

			$this->load->view('campus_connect/questionContainerView', $displayData);
		}
	}
	
	function getMostViewedOrTrendingQuestions()
	{
		if($this->input->is_ajax_request()){
			$this->init();
			$widgetType = (isset($_POST['widgetType']) && $_POST['widgetType']!='')?$this->input->post('widgetType'):'mostViewed';
			$programId = (isset($_POST['programId']) && $_POST['programId']!='')?$this->input->post('programId'):1;

			$instituteData = $this->campusConnectBaseLib->getInstituteDataForMostViewedAndTrandingWidget($programId,$widgetType,$this->noOfInst,false);	
			if(!($instituteData['finalInstitueDetails'] && count($instituteData['finalInstitueDetails'])))
				die('No question available.');
			$displayData['instituteData'] = $instituteData['finalInstitueDetails'];
			if($widgetType == 'mostViewed'){
				$orderBy = 'viewCount';
			}else{
				$orderBy = 'creationDate';
			}
			$quesData = array();
			if(empty($instituteData['courseIdsStr']))die('No question available.');
			$quesData = $this->campusconnect->getQuestionsForBottomCollegeCard($instituteData['courseIdsStr'], $orderBy);
			$quesData = get24QuestionsForMostViewsColleges($quesData, $instituteData['instituteIds']);
			$quesData = json_decode($quesData, true);
			$displayData['quesData'] = $quesData['finalQuestions'];
			$displayData['finalQuesId'] = $quesData['finalQuesId'];
			$answerData = array();
			if(empty($quesData['finalQuestions']))die('No question available.');
			$answerData = $this->campusconnect->getMostViewedAnswers(implode(',', $quesData['finalQuesId']));
			$answerData = get24AnswersForMostViewsColleges($answerData, $displayData['finalQuesId']);
			$displayData['answerData'] = $answerData;
			$this->load->view('campus_connect/questionContainerView', $displayData);
		}
	}
}
