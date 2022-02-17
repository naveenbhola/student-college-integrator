<?php
/*

   Copyright 2013-14 Info Edge India Ltd

   $Author: Rahul

   $Id: CADiscussions.php

 */

class CADiscussions extends MX_Controller
{
	var $userStatus = '';
        function init($library=array('ajax'),$helper=array('url','image','shikshautility','utility_helper')){
		if(is_array($helper)){
			$this->load->helper($helper);
		}
		if(is_array($library)){
			$this->load->library($library);
		}
		if(($this->userStatus == ""))
			$this->userStatus = $this->checkUserValidation();
																																																																																																																												
	}

	/*
	* Function to display the Campus reps and Description on the Course Overview and Campus connect tab
	*/
	function getCourseTuple($courseId, $instituteId, $tab="overview", $url="", $count = 3 ,$studyIndia = false, $isMobile = false){
		$this->init();
		
		//Get the Course object
		$this->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;
		
		$instituteRepository = $listingBuilder->getInstituteRepository();
		$institute = $instituteRepository->find($instituteId);
		$coursesArray = $instituteRepository->getLocationwiseCourseListForInstitute($instituteId);
		$courseCount = 0;
		foreach ($coursesArray as $locArray){
			$courseCount += count($locArray['courselist']);
		}

		$courseRepository = $listingBuilder->getCourseRepository();
		$course = $courseRepository->find($courseId);
		
		// Get Course Reps from Backend
		$this->load->model('cadiscussionmodel');
		$this->CADiscussionModel  = new CADiscussionModel();
		$result = $this->CADiscussionModel ->getCampusReps($courseId, $instituteId, $count);

		$data= array();
		$data['result'] = $result;
		$data['course'] = $course;
		$data['institute'] = $institute;
		$data['tab'] = $tab;
		$data['url'] = base64_decode($url);
		$data['courseCount'] = $courseCount;
		$data['studyIndia'] = $studyIndia;
		
                $data['instituteAnAURL'] = Modules::run('CA/CampusAmbassador/getListingAnaUrl',$instituteId);

		if(isset($result['data'][0]['mainCourseId']) && $result['data'][0]['mainCourseId']!=''){
			 $data['mainCourse'] = $courseRepository->find($result['data'][0]['mainCourseId']);
		}
		//Now we need to display these Campus reps
		if($isMobile){
			$this->load->view('mAnA5/campusRep/courseTuple',$data);
		}
		else{
			$this->load->view('CA/courseTuple',$data);
		}
	}
	
	/**
	 * Function to show the QnA section in course Overview Pages.
	 * does an ajax call and put the new data below the old one
	 * @param unknown_type $courseId
	 * @param unknown_type $instituteId
	 */
	function getCourseOverviewQnA($courseId,$instituteId,$javascriptEnabled,$studyIndia = false, $isMobile = false, $fromPage='',$ctrackingPageKeyId='',$rtrackingPageKeyId='',$atrackingPageKeyId='',$tupaTrackingPageKeyId='',$tdownTrackingPageKeyId='',$fqTrackingPageKeyId='') { 
		$this->init();
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;

		//$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		
		// gets the ajax params
		$pageNo = isset($_POST['page_no'])?$this->input->post('page_no'):0;
		$callType = isset($_POST['callType'])?$this->input->post('callType'):'';
		$courseId = isset($_POST['course_id'])?$this->input->post('course_id'):$courseId;
		$instituteId = isset($_POST['institute_id'])?$this->input->post('institute_id'):$instituteId;

		$javascriptEnabled = isset($_POST['js_enabled'])?$this->input->post('js_enabled'):$javascriptEnabled;
		$studyIndia = isset($_POST['study_india'])?$this->input->post('study_india'):$studyIndia;
		$isMobile = isset($_POST['isMobile'])?$this->input->post('isMobile'):$isMobile;
		
		$fromPage = isset($_POST['fromPage'])?$this->input->post('fromPage'):$fromPage; // check request from desktop My Shortlist page

		//below lines is used for coversion tracking purpose
		$rtrackingPageKeyId = isset($_POST['rtrackingPageKeyId'])?$this->input->post('rtrackingPageKeyId'):$rtrackingPageKeyId;
		$ctrackingPageKeyId = isset($_POST['ctrackingPageKeyId'])?$this->input->post('ctrackingPageKeyId'):$ctrackingPageKeyId;
		$atrackingPageKeyId = isset($_POST['atrackingPageKeyId'])?$this->input->post('atrackingPageKeyId'):$atrackingPageKeyId;
		$tupaTrackingPageKeyId = isset($_POST['tupaTrackingPageKeyId'])?$this->input->post('tupaTrackingPageKeyId'):$tupaTrackingPageKeyId;
		$tdownTrackingPageKeyId = isset($_POST['tdownTrackingPageKeyId'])?$this->input->post('tdownTrackingPageKeyId'):$tdownTrackingPageKeyId;
		$fqTrackingPageKeyId = isset($_POST['fqTrackingPageKeyId'])?$this->input->post('fqTrackingPageKeyId'):$fqTrackingPageKeyId;

		$qtrackingPageKeyId = isset($_POST['tracking_keyid'])?$this->input->post('tracking_keyid'):'';
		
		if((!is_numeric($courseId) || $courseId <= 0 || empty($courseId))){
			return;
		}

		$all = 1;
		$questionType = "All";
		$linkId = "";
		
		$this->load->model('cadiscussionmodel');
		$this->CADiscussionModel = new CADiscussionModel();
				
		if($studyIndia) {
			
			$historyData = array();
			$linkData = array();
			
			if(isset($_COOKIE["courseId"])) {
				$cookieCourseId = $_COOKIE["courseId"];
			}
		
			// resets the cookies if course is changed
			if($cookieCourseId != $courseId) {
				setcookie('questionType',"All",time() + 2592000 ,'/',COOKIEDOMAIN);
				setcookie('linkId',"All",time() + 2592000 ,'/',COOKIEDOMAIN);
				$questionType = "All";
				$linkId = "";
				if(isset($_GET['link_id']) && $_GET['link_id'] != 'All' ) {
					$linkId = $_GET['link_id'];
				}
			}else {
				// Checks if the cookie for questionType is set or not . Get QuestionType on the basis of cookie
				if(isset($_COOKIE["questionType"]) && $fromPage != 'myShortlistAnA') {
					$questionType = $_COOKIE["questionType"];
				}else {
					$questionType = "All";
				}
				
				$linkId = "";
				
				// Checks if the cookie for linkId is set or not . Get linkId on the basis of cookie
				if(isset($_GET['link_id']) && $_GET['link_id'] != 'All' ) {
					$linkId = $_GET['link_id'];
				}			
			}
			
			if(!empty($linkId)) {
				$linkData = $this->CADiscussionModel->getLinkDataForLinkId($linkId);
				$historyData = $linkData[0];
				$all = 0;
			}else {
				$historyData = array("instituteId" => $instituteId,'courseId' => $courseId);
				$all = 1;
			}
						
		}else {		
			$historyData = array("instituteId" => $instituteId,'courseId' => $courseId);
		}


		$this->load->library('CA/CADiscussionHelper');
		$caDiscussionHelper =  new CADiscussionHelper();
		
		// page size hardcoded as 10 : given in req
		if($isMobile){
			$pageSize = 3;
			if($callType=='Ajax'){
				$pageSize = 5;
			}
			if($pageNo==0){
				$pageStart = 0;
			}
			else if($pageNo>0){
				$pageStart = 3 + (($pageNo-1)*5);
			}
			if($fromPage == 'myShortlistAnAMobile'){
				$pageSize = 3;	
				$pageStart = 3 + (($pageNo-1)*3);
			}
		}
		else{
			$pageSize = ($fromPage == 'myShortlistAnA') ? 8 : 10;  // myshortlist pageSize 8
			$pageStart = $pageNo*$pageSize;
		}
		$formatedData = array();
		$campusRepExists = $caDiscussionHelper->checkIfCampusRepExistsForCourse(array($courseId));
		//gets all campus reps live/history
		if($studyIndia && $fromPage == 'courseListingPage' && $campusRepExists[$courseId] == 'true') {
			$campusRepExists['CA_Details'] = $caDiscussionHelper->getAllCampusReps(implode(',', array($courseId)));
		}

		if($javascriptEnabled) {
			if($studyIndia) {
				if($campusRepExists[$courseId] == 'true'){
					//in case of listing page $questionDataReturned is an array of answers answered by campus Rep.
					if(!empty($campusRepExists['CA_Details'][$courseId]) && $questionType == "All"){
						$questionDataReturned = $this->CADiscussionModel->getQnA($historyData,$all,'question','',$pageStart,$pageSize,$questionType,$userId, array(), 'listing',implode($campusRepExists['CA_Details'][$courseId], ','));
					}
					else{
						$questionDataReturned = $this->CADiscussionModel->getQnA($historyData,$all,'question','',$pageStart,$pageSize,$questionType,$userId, array(), 'listing','',$fromPage);
					}
				}else{
					$questionDataReturned = array();
				}
			}else {
				$questionDataReturned = $this->CADiscussionModel->getQnAForStudyAbroad($historyData,$all,'question','',$pageStart,$pageSize,$questionType,$userId);
			}
			
			$totalQuestions = $questionDataReturned["total"];
			$questions = $questionDataReturned['data'];
			if(!empty($questions)) {
				if($questionType == "All") {
					if(!empty($campusRepExists['CA_Details'][$courseId])){
						$questionIds = $caDiscussionHelper->getQuestionIdsFromAnswer($questions);
					}
					else{
						$questionIds = $caDiscussionHelper->getQuestionIds($questions);
					}
					if($studyIndia) {
						if($campusRepExists[$courseId] == 'true' && !empty($questionIds)){
							if(!empty($campusRepExists['CA_Details'][$courseId])){
								//gets questions corresponding to those answers
								$questionsCA = $this->CADiscussionModel->getQnA($historyData,$all,'answer',$questionIds,'','', $questionType,$userId, array(),'listing',implode($campusRepExists['CA_Details'][$courseId], ','));
								//error_log('===== nithish'.print_r($campusRepExists['CA_Details'][$courseId],true));
								$questions = '';
								
								$questions = $questionsCA['data'];
								$questionIds = $caDiscussionHelper->getQuestionIds($questions);
							}
							if(!empty($questionIds)){
								if($isMobile){
									$campusRepIds = implode($campusRepExists['CA_Details'][$courseId],',');
								}
								$answersAnsComments = $this->CADiscussionModel->getQnA($historyData,$all,'answer',$questionIds,'','', $questionType,$userId, array(),'listing',$campusRepIds,'',$isMobile);
							}
							else{
								$answersAnsComments = array();
							}
						}else{
							$answersAnsComments = array();
						}
					}else {
						$answersAnsComments = $this->CADiscussionModel->getQnAForStudyAbroad($historyData,$all,'answer',$questionIds,'','', $questionType,$userId);
					}
					$answers = $answersAnsComments['data'];
					//below Lines are used For new campus connect questions view  In course Detail page
					if($isMobile && $studyIndia)
					{
						if( !empty($questionIds) && $userId > 0 )
						{
							//get the details of user answered question Ids
							$userHasAnsweredThreadIds = $this->CADiscussionModel->getQuestionsAnsweredByUser($questionIds,$userId);
						}	
						if( ! empty($answers))
						{
							$i = 0;
							$answerUserIds = array();
							$entityAnswerIds = array();
							//below lines are used for seo url for userProfile
							foreach ($answers as $answerKey => $answerValue) {
								$answerUserIds[$i++] = $answerValue['userId'];
								$entityAnswerIds[$j++] = $answerValue['msgId'];
								$answers[$answerKey]['userProfileUrl'] = getSeoUrl($answerValue['userId'],'userprofile');
							}	
							if( ! empty($answerUserIds))
							{
								$anamodel = $this->load->model('messageBoard/anamodel');
								//get userLevel in Shiksha
								$aOwnerUserLevel = $anamodel->getAnAUsersLevel($answerUserIds);
								//get about me field of user
								$aboutMeUserText = $this->CADiscussionModel->getAboutMeForUserIds($answerUserIds);
							}	
							if( !empty($entityAnswerIds) && $userId > 0)
							{
								//get Details of user upvoted entity Ids
								$userUpvotedEntities = $this->CADiscussionModel->getUserUpvotedOnEntity($entityAnswerIds,$userId);
							}
							foreach ($answers as $answerKey => $answerValue) {
								$answers[$answerKey]['userLevel'] = $aOwnerUserLevel[$answerValue['userId']];
								$answers[$answerKey]['hasUserVotedUp'] = $userUpvotedEntities[$answerValue['msgId']] == '1' ? 'true' : 'false';
								$answers[$answerKey]['hasUserVotedDown'] = $userUpvotedEntities[$answerValue['msgId']] == '0' ? 'true' : 'false';
								$answers[$answerKey]['aboutMe'] = $aboutMeUserText[$answerValue['userId']] ? $aboutMeUserText[$answerValue['userId']] : '';
								
							}
						}
					}
					$allMessages = array_merge($questions,$answers);
				}else {
					$allMessages = $questions;
				}
				$qnaRelatedData = array();
				if($studyIndia && !empty($questions) && $isMobile)
				{
						$j =0;
						//get Tags attached to question Ids and sort the  tags (college/university comes as first)
						foreach ($questions as $questionKey => $questionValue) {
								$tagsForQuestions = $this->CADiscussionModel->getTagsForEntity($questionValue['msgId']);
					        	usort($tagsForQuestions, function($a , $b){

					        		if(in_array($a['tag_entity'], array('Colleges','University'))){
					        			return -1;
					        		}elseif (in_array($b['tag_entity'], array('Colleges','University'))){
					        			return 1;
					        		}else{
					        			return -1;
					        		}
					        	});
						        
						    	$questionTagMapping[$questionValue['msgId']] = array_slice($tagsForQuestions,0,2);
								$questionThreadIds[$j++] = $questionValue['msgId'];
						}
						if($userId > 0)
						{
							//get Question Ids followed by logged in User
							$isUserFollowingEntity = $this->CADiscussionModel->isUserFollowingEntity($userId,$questionThreadIds);
						}
						if(! empty($questionThreadIds))
						{	
							//getting follow Count for question Entity
							$followers = $this->CADiscussionModel->getThreadFollowers($questionThreadIds);
						}
						$qnaRelatedData = array('tags' => $questionTagMapping,'isFollow' => $isUserFollowingEntity , 'followCount' => $followers,'isMobile' => $isMobile,'userHasAnsweredThreadIds' => $userHasAnsweredThreadIds,'loggedInUserId' => $userId);


				}

				$formatedData = $caDiscussionHelper->rearrangeQnA($allMessages,'All',$qnaRelatedData);
			}
		}else {
			if($studyIndia) {
				if($campusRepExists[$courseId] == 'true'){
					$questionDataReturned = $this->CADiscussionModel->getQnA($historyData,$all,'question','','','','',$userId, array(),'listing');
				}else{
					$questionDataReturned = array();
				}
			}else {
				$questionDataReturned = $this->CADiscussionModel->getQnAForStudyAbroad($historyData,$all,'question','','','','',$userId);
			}
			$questions = $questionDataReturned['data'];
			$totalQuestions = $questionDataReturned["total"];
			if(!empty($questions)) {
				$questionIds = $caDiscussionHelper->getQuestionIds($questions);
				if($studyIndia) {
					if($campusRepExists[$courseId] == 'true'){
						$answersAnsComments = $this->CADiscussionModel->getQnA($historyData,$all,'answer',$questionIds,'','','',$userId, array(),'listing');
					}else{
						$answersAnsComments = array();
					}
				}else {
					$answersAnsComments = $this->CADiscussionModel->getQnAForStudyAbroad($historyData,$all,'answer',$questionIds,'','','',$userId);
				}
				$answers = $answersAnsComments['data'];
				$allMessages = array_merge($questions,$answers);
				$formatedData = $caDiscussionHelper->rearrangeQnA($allMessages);
			}			
		}
		
		$links = $this->CADiscussionModel->getCourseHistoryLinks($courseId);		
		
		$pageData = array();

		//below line is used for GA tracking for user logged in / Non-Logged in state
		$pageData['GA_userLevel'] = $userId > 0 ? 'Logged In':'Non-Logged In';
		
		$pageData["qna"] = $formatedData["data"];
		$pageData["total"] = $totalQuestions;
		$pageData["courseId"] = $courseId;
		$pageData["instituteId"] = $instituteId;
		$pageData["courseId"] = $courseId;
		$pageData['pageNo'] = $pageNo;
		$pageData['pageSize'] = $pageSize;
		$pageData["js_enabled"] = $javascriptEnabled;
		$pageData['studyIndia'] = $studyIndia;
		$pageData["questionType"] = $questionType;
		$pageData['links'] = $links;
		$pageData['validateuser'] = $this->userStatus;
		$pageData['userId'] = $userId;
		$pageData['validateuser'] = $this->userStatus;
		$pageData['campusRepExists']  = $campusRepExists[$courseId];


		//For courses who have Campus Reps, we will not show the Answer box to the Institute owner
                $this->load->builder('ListingBuilder','listing');
                $listingBuilder = new ListingBuilder;
                $courseRepository = $listingBuilder->getCourseRepository();
                $courseObj = $courseRepository->find($courseId);
                $chkInstId = $courseObj->getInstId();
                if(!is_object($courseObj) || empty($courseObj) || empty($chkInstId)){
                	return;
                }
                $ownerId = $courseObj->getClientId();
		$pageData['doNoShowAnswerForm'] = false;
		if($ownerId==$userId && $pageData['campusRepExists']=='true'){
			$pageData['doNoShowAnswerForm'] = true;
		}
		//End Code for Owner and CR Check

		if(isset($qtrackingPageKeyId))
			{
				$pageData['qtrackingPageKeyId']=$qtrackingPageKeyId;
			}
			//below lines is used for conversion ttracking purpose
			if(isset($ctrackingPageKeyId))
			{
				$pageData['ctrackingPageKeyId']=$ctrackingPageKeyId;
			}
			if(isset($rtrackingPageKeyId))
			{
				$pageData['rtrackingPageKeyId']=$rtrackingPageKeyId;
			}
		// Loads different view file for ajax
		if($isMobile){

			$pageData['atrackingPageKeyId'] = $atrackingPageKeyId;
			$pageData['tupaTrackingPageKeyId'] = $tupaTrackingPageKeyId;
			$pageData['tdownTrackingPageKeyId'] = $tdownTrackingPageKeyId;
			$pageData['fqTrackingPageKeyId'] = $fqTrackingPageKeyId;
			if($fromPage == 'myShortlistAnAMobile')
			{
				if($pageNo == 0)
					echo $this->load->view('mobile_myShortlist5/widgets/askTab',$pageData);
				else
					echo $this->load->view('mobile_myShortlist5/widgets/askTabDetails',$pageData);
			}
			else
			{
				if($callType=='Ajax'){
					if($studyIndia)
						echo $this->load->view('mAnA5/campusRep/campusConnectInCourseDetailPage',$pageData);
					else
						echo $this->load->view('mAnA5/campusRep/courseOverviewInner',$pageData);
					
				}else {
					echo $this->load->view('mAnA5/campusRep/courseOverviewQnA',$pageData);
				}			
			}
		}else if($fromPage !='' && $fromPage == 'myShortlistAnA'){  // make view for shortlist page on desktop
			if($pageData['campusRepExists'] == 'true'){
				if($callType=='Ajax'){
					echo $this->load->view('CA/myShortlistAnAPage',$pageData);
				}else{
					echo $this->load->view('CA/myShortlistAnAPage',$pageData);
				}
			}else{
				echo 'No CampusRep Exists';
			}
		}else{
			if($callType=='Ajax'){
				echo $this->load->view('CA/courseOverviewInner',$pageData);
			}else {
				echo $this->load->view('CA/courseOverviewQnA',$pageData);
			}
		}
		
		
	}
	
	/*
	 * Get the Question and Answers for Campus Connect Course Page
	 * QuestionType can be "All" and "Unanswered" 
	 */
	function getCourseQnA($pageStart,$courseId) {
	
		$this->init();

		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
				
		$this->load->library('CA/CADiscussionHelper');
		$caDiscussionHelper =  new CADiscussionHelper();
				
		$this->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;
		$courseRepository = $listingBuilder->getCourseRepository();
		$courseObj = $courseRepository->find($courseId);
		$instituteId = $courseObj->getInstId();
		
		$studyIndia = 1;
		
		if($courseObj->getMainLocation()->getCountry()->getId() != 2) {
			$studyIndia = 0;
		}
		
		$historyData = array();
		$linkData = array();
	
		$callType = isset($_POST['callType'])?$this->input->post('callType'):'';
	
		if(isset($_COOKIE["courseId"])) {
			$cookieCourseId = $_COOKIE["courseId"];
		}
		
		// resets the cookies if course is changed
		if($cookieCourseId != $courseId) {
			setcookie('questionType',"All",time() + 2592000 ,'/',COOKIEDOMAIN);
			setcookie('linkId',"All",time() + 2592000 ,'/',COOKIEDOMAIN);
			$questionType = "All";
			$linkId = "";
		}else {
			// Checks if the cookie for questionType is set or not . Get QuestionType on the basis of cookie
			if(isset($_COOKIE["questionType"])) {
				$questionType = $_COOKIE["questionType"];
			}else {
				$questionType = "All";
			}
			
			$linkId = "";
			// Checks if the cookie for linkId is set or not . Get linkId on the basis of cookie
			if(isset($_COOKIE["linkId"]) && $_COOKIE["linkId"] != 'All' ) {
				$linkId = $_COOKIE["linkId"];
			}			
		}
				
		$this->load->model('cadiscussionmodel');
		$this->CADiscussionModel = new CADiscussionModel();
	
		// Get the Link Data From the HistoryTable 
		if(!empty($linkId)) {
			$linkData = $this->CADiscussionModel->getLinkDataForLinkId($linkId);
			$historyData = $linkData[0];
			$all = 0;
		}else {
			$historyData = array("instituteId" => $instituteId,'courseId' => $courseId);
			$all = 1;
		}
		

		// adjust pageNo on the basis of start index and totla pageSize
		$pageSize = LISTING_QUESTIONS_PER_PAGE;
		if($pageStart == '') {
			$pageNo = 0 ;
		}else {
			$pageNo = $pageStart/$pageSize;
		}
		
		$pageEnd = $pageStart + $pageSize;
		
		if($pageStart == '') {
			$pageStart = 0;
		}		

		$formatedData = array();
		// Get the Data from model on the basis of link data and questionType 
		
		if($studyIndia) {
			$questionDataReturned = $this->CADiscussionModel->getQnA($historyData,$all,'question','',$pageStart,$pageSize,$questionType,$userId);
		}else {
			$questionDataReturned = $this->CADiscussionModel->getQnAForStudyAbroad($historyData,$all,'question','',$pageStart,$pageSize,$questionType,$userId);
		}
		
		$totalQuestions = $questionDataReturned["total"];
		$questions = $questionDataReturned['data'];

		if(!empty($questions)) {
			if($questionType == "All") {
				$questionIds = $caDiscussionHelper->getQuestionIds($questions);
				if($studyIndia) {
					$answersAnsComments = $this->CADiscussionModel->getQnA($historyData,$all,'answer',$questionIds,'','','',$userId);
				}else {
					$answersAnsComments = $this->CADiscussionModel->getQnAForStudyAbroad($historyData,$all,'answer',$questionIds,'','','',$userId);
				}
				$answers = $answersAnsComments['data'];

				$allMessages = array_merge($questions,$answers);
			}else {
				$allMessages = $questions;
			}
			$formatedData = $caDiscussionHelper->rearrangeQnA($allMessages);
		}

		$url = $this->getCourseUrl($courseId,true);
		
		$links = $this->CADiscussionModel->getCourseHistoryLinks($courseId);
		
		$pageData = array();
		$pageData["qna"] = $formatedData["data"];
		$pageData["pageNo"] = $pageStart;
		$pageData["pageSize"] = $pageSize;
		$pageData["totalEntry"] = $totalQuestions;
		$pageData["courseId"] = $courseId;

		$pageData["studyIndia"] = false;
		$pageData["paginationUrl"] = $url;
		$pageData["questionType"] = $questionType;
		$noPages = (ceil($totalQuestions/$pageSize) > 3)?3:ceil($totalQuestions/$pageSize);
		$pageData["noPages"] =$noPages;
		$pageData['userId'] = $userId;
		$pageData['links'] = $links;
		$pageData['validateuser'] = $this->userStatus;
		$pageData['userId'] = $userId;
		$pageData['validateuser'] = $this->userStatus;
	
		echo $this->load->view('CA/courseCCQnA',$pageData);
	}
	
	function getCourseLinks($courseId,$studyIndia = false, $isMobile = false) {
		
		$this->init();
		$this->load->model('cadiscussionmodel');
		$this->CADiscussionModel = new CADiscussionModel();

		$data = array();
		$links = $this->CADiscussionModel->getCourseHistoryLinks($courseId);
		
		if(isset( $_GET['link_id'])  &&  $_GET['link_id']!='') {
			$linkId =  $_GET['link_id'];
		}
		
		$data["links"] = $links;
		$data["cookie_linkId"] = $linkId;
		$data['studyIndia'] = $studyIndia;
		
		if($isMobile){
			echo $this->load->view('mAnA5/campusRep/courseCCLinks',$data);
		}
		else{
			echo $this->load->view('CA/courseCCLinks',$data);
		}
		
	}
	
	/*
	 * Set the cookie value for the QuestionType
	 */
	function setQuestionTypeCookie() {
	
		$questionType = isset($_POST['type'])?$this->input->post('type'):'';
		$callType = isset($_POST['callType'])?$this->input->post('callType'):'';
		$courseId = isset($_POST['course_id'])?$this->input->post('course_id'):'';
	
		setcookie('questionType',$questionType,time() + 2592000 ,'/',COOKIEDOMAIN);
		setcookie('courseId',$courseId,time() + 2592000 ,'/',COOKIEDOMAIN);
		
		$url = $this->getCourseUrl($courseId);
		
		echo $url;
	
	}	
	
	/**
	 * Sets Link id in cookier and returns url for the page
	 */
	function setLinkCookie() {
		
		$callType = isset($_POST['callType'])?$this->input->post('callType'):'';
		$linkId = isset($_POST['link_id'])?$this->input->post('link_id'):'';
		$courseId = isset($_POST['course_id'])?$this->input->post('course_id'):'';
		
		setcookie('linkId',$linkId,time() + 2592000 ,'/',COOKIEDOMAIN);
		setcookie('courseId',$courseId,time() + 2592000 ,'/',COOKIEDOMAIN);
		
		$url = $this->getCourseUrl($courseId);
		
		echo $url;		
	}
	
	/**
	 * Gets course Url for the course_id
	 * @param unknown_type $courseId
	 * @return unknown
	 */
	function getCourseUrl($courseId,$handlePagination = false) {
		$callType = isset($_POST['callType'])?$this->input->post('callType'):'';
		$courseId = isset($_POST['course_id'])?$this->input->post('course_id'):$courseId;
		if($courseId=='' ||  $courseId <=0){
                        echo 'COURSE_ID_ZERO';exit;
                }
		$this->load->builder("nationalCourse/CourseBuilder");
		$courseBuilder = new CourseBuilder();
		$this->courseRepo = $courseBuilder->getCourseRepository();
		$courseObj = $this->courseRepo->find($courseId);
		$params["instituteName"] = $courseObj->getInstituteName();
		$params["courseId"] = $courseId;
		$params["course"] = $courseObj;

		$additionalURLParams = '';
                if($_REQUEST['city']){
                        $additionalURLParams = "?city=".$_REQUEST['city'];
                        if($_REQUEST['locality']){
                                $additionalURLParams .= "&locality=".$_REQUEST['locality'];
                        }
                }        
        
        $countryId = 2;//$courseObj->getMainLocation()->getCountry()->getId();
        $url = $courseObj->getUrl() . $additionalURLParams;
        // Hard Code Country id for India
		/*if($countryId == 2) {
			$url = $courseObj->getUrl() . $additionalURLParams;
		}else {
			if($handlePagination) {
				$url = listing_campus_rep_url($params) . "-@start@".  $additionalURLParams;
			}else {
				$url = listing_campus_rep_url($params) . $additionalURLParams;
			}
		}    */ 
		
		if($callType == 'Ajax') {
			echo $url;
		}else {
			return $url;
		}
		
		
		
	}

		   /* this is used to get all the courses info of  institute */
        function getAllCoursesTuplesForInstitute($instituteId,$currentLocationId,$courseIdToExclude='',$count=4,$start=0,$countFlag=3){
		$this->init();
                $this->load->builder('ListingBuilder','listing');
                $listingBuilder = new ListingBuilder;
                $instituteId = isset($_POST['instituteId'])?$this->input->post('instituteId'):$instituteId;
                $start = isset($_POST['start'])?$this->input->post('start'):0;
                $count = isset($_POST['count'])?$this->input->post('count'):$count;
                $currentLocationId =  isset($_POST['currentLocationId'])?$this->input->post('currentLocationId'):$currentLocationId;
                $courseIdToExclude = isset($_POST['courseIdToExclude'])?$this->input->post('courseIdToExclude'):$courseIdToExclude;
                $instituteRepository = $listingBuilder->getInstituteRepository();
                $institute = $instituteRepository->find($instituteId);
                $courseRepository = $listingBuilder->getCourseRepository();
                $callType = isset($_POST['callType'])?$this->input->post('callType'):'';
                $this->load->model('cadiscussionmodel');
                $this->CADiscussionModel = new CADiscussionModel();
                $coursesList = $this->CADiscussionModel->getCoursesForListing($instituteId,$start,$count,$courseIdToExclude);
                $totalNoOfCourses=$coursesList['total'];
                if($_REQUEST['city']){
                        $additionalURLParams = "?city=".$_REQUEST['city'];
                        if($_REQUEST['locality']){
                                $additionalURLParams .= "&locality=".$_REQUEST['locality'];
                        }
                }

                foreach($coursesList['courses'] as $resultArray){
			$data= array();
			$data['result'] = $this->CADiscussionModel->getCampusReps($resultArray['course_id'], $instituteId, $countFlag);
			$resultant=$data['result'];
			$campusArray[]=$resultant;
			$course = $courseRepository->find($resultArray['course_id']);
			$params["instituteName"] = $course->getInstituteName();
			$params["courseId"] = $resultArray['course_id'];
			$params["course"] = $course;
			$url = listing_campus_rep_url($params) . $additionalURLParams;
                        $data['institute'] = $institute;
                        $data['course'] = $course;
			$data['url']=$url;
                        $result[]=$data;
                }
				$isCampusPresent = 'false';
				foreach($campusArray as $campusRep=>$value){
					if($value['totalReps'] > 0 || $value['instituteRep'] =='true' )
						       $isCampusPresent = 'true';
						   break;
				}
		
				$displayData['isCampusPresent']=$isCampusPresent;
                $displayData['currentLocationId']=$currentLocationId;
                $displayData['start']=$start;
                $displayData['instituteId']=$instituteId;
                $displayData['courseIdToExclude']=$courseIdToExclude;
                $displayData['coursesData']=$result;
                $displayData['count']=$count;
                $displayData['totalNoOfCourses']=$totalNoOfCourses;
	
		if(count($result)<=0){
			$displayData['noResult']= 0;
		}
		if($callType=='ajax'){
			$this->load->view('caTuplesInnerWidget',$displayData);
		}
		else
			$this->load->view('caTuplesWidget',$displayData);
	}	

	function getQuestionForm($courseId,$instituteId,$studyIndia = false, $isMobile = false, $fromPage='',$trackingPageKeyId=''){
                $this->init();

                //Get the Course object
                $this->load->builder('ListingBuilder','listing');
                $listingBuilder = new ListingBuilder;

                $instituteRepository = $listingBuilder->getInstituteRepository();
                $institute = $instituteRepository->find($instituteId);

                $courseRepository = $listingBuilder->getCourseRepository();
                $course = $courseRepository->find($courseId);

                $data= array();
                $data['course'] = $course;
                $url = Modules::run('CA/CADiscussions/getCourseUrl',$course->getId());
                $data['coursUrl'] = $url.'#ca_aqf';
                $data['institute'] = $institute;
                $data['instituteId'] = $instituteId;
                $data['$questionIds'] = '';
                $data['instituteAnAURL'] = Modules::run('CA/CampusAmbassador/getListingAnaUrl',$instituteId);
				$data['categories'] = $instituteRepository->getCategoryIdsOfListing($courseId,'course');
				$data['locationId'] = $institute->getMainLocation()->getCountry()->getId();
				$data['validateuser'] = $this->userStatus; 
				$data['trackingPageKeyId']=$trackingPageKeyId;
		$data['currentLocation'] = $course->getMainLocation();

		//below line is used for GA tracking for user logged in or non-logged in
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$data['GA_userLevel'] = $userId > 0 ? 'Logged In':'Non-Logged In';
                //Now we need to Show the Ask Question Form
		if($isMobile){
			if($fromPage == 'myShortlistAnAMobile'){
				$this->load->view('mobile_myShortlist5/widgets/ask_course_form',$data);	
			}else if($fromPage == 'instituteCampusRep-mba'){
				$formId = explode('-',$fromPage);
				$data['formId'] = $formId[1];
				$this->load->view('mAnA5/campusRep/ask_inst_rep_form',$data);
			}else{
				$this->load->view('mAnA5/campusRep/ask_course_form',$data);
			}
		}else if(!$isMobile && $studyIndia && $fromPage == 'myShortlistAnA'){  // make form for My shortlist page
			
			$this->load->view('CA/myshortlist_ask_form',$data);
		}else{
			if(!$studyIndia) {
				$this->load->view('CA/ask_course_form',$data);
			}else {
				$this->load->view('CA/ask_course_form_national',$data);
			}
		}

	}
        
        /*
	* Function to get comment count done by all the ca for institute page 
	*/
	function getCommentCountForInstitute($instituteId){
		$this->init();
		$this->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;
		
		$instituteRepository = $listingBuilder->getInstituteRepository();
		$institute = $instituteRepository->find($instituteId);
		$this->load->model('cadiscussionmodel');
		$this->CADiscussionModel  = new CADiscussionModel();
		$coursesList = $instituteRepository->getCoursesOfInstitutes(array($instituteId));
		$coursesArray=$coursesList[$instituteId]['course_title_list'];
		$commentCount  =0;
		foreach ($coursesArray as $courseId=>$courseTitle){
			$caJoiningDate = $this->CADiscussionModel->getCAJoinDate($courseId);
			$result= $this->CADiscussionModel ->getCampusReps($courseId, $instituteId,3,false,true,$caJoiningDate);
			
			foreach($result['data'] as $key=>$value){
				$commentCount += $result['data'][$key]['commentCount'];
			}
		}
		$data= array();
		$data['commentCount'] = $commentCount;
		return $data;
	}	
	/*
	function dailyUnansweredMailer(){
		
		$this->init();

		$this->load->library('CA/CADiscussionHelper');
		$caDiscussionHelper =  new CADiscussionHelper();
		
		$this->load->model('cadiscussionmodel');
		$this->CADiscussionModel = new CADiscussionModel();
		
		$mailerContent = $this->CADiscussionModel->getUnsAnsweredMailerContent($caDiscussionHelper);
		
		
	}
	*/
	
}
?>
