<?php
/*

   Copyright 2015 Info Edge India Ltd

   $Author: Pranjul

   $Id: EventController.php

 */

class EventController extends MX_Controller
{
	private $eventcalendarmodel = NULL;
        function init($library=array('ajax'),$helper=array('url','image','shikshautility','utility_helper')){
		if(is_array($helper)){
			$this->load->helper($helper);
		}
		if(is_array($library)){
			$this->load->library($library);
		}
		
		$this->load->helper('coursepages/course_page');
		
		if(($this->userStatus == ""))
			$this->userStatus = $this->checkUserValidation();
		
		$this->load->builder('ExamBuilder','examPages');
		$examPageBuilder          = new ExamBuilder();
		$this->examPageRepository = $examPageBuilder->getExamRepository();
		
		$this->load->model('examPages/exampagemodel');
		$this->load->model('eventcalendarmodel');
		$this->eventcalendarmodel = new eventcalendarmodel;

		$this->eventCalendarLib = $this->load->library('eventCalendarLib');
	}
	
	/*
	 @name: eventCalendar
	 @description: this is for getting Events.
	 @param string $userInput: 
	*/
	public function eventCalendar($param){
    	$param = strtolower($param);
    	$this->init();
		$examIds = array();
		if($this->input->is_ajax_request()){
			$examFilter['streamId'] = isset($_POST['streamId']) ? $this->input->post('streamId') : 0;
			$examFilter['courseId'] = isset($_POST['courseId']) ? $this->input->post('courseId') : 0;
			$examFilter['educationTypeId'] = isset($_POST['educationTypeId']) ? $this->input->post('educationTypeId') : 0;
			$examIds = isset($_POST['examList']) ? $this->input->post('examList') : $examIds;
		}else{
			$this->examCalendarRedirectionLib = $this->load->library('examCalendarRedirectionLib');
			$examFilter = $this->examCalendarRedirectionLib->urlRedirectionCheck($param);
		}

		$examEvents = $this->getExamPageEvents($examIds,$examFilter);
		$result['examNameList'] = $examEvents['examNameList'];
		$result['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$customEvents = $this->getCustomEvents($result['userId'], $examFilter);
		$customDecodedData = json_decode($customEvents['data'],true);
		$examDecodedData = json_decode($examEvents['data'],true);
		$i=0;
		$resultSet = array();
		foreach($examDecodedData as $key=>$value){
				$resultSet[$i] = $value;		
				$i++;	
		}
		foreach($customDecodedData as $key=>$value){
				$resultSet[$i] = $value;		
				$i++;	
		}

		$result['data'] = json_encode($resultSet, JSON_HEX_APOS);
		
		$result['eventListData'] = restructuredArray($resultSet);
		$result['title'] = $examFilter['examCalendarTitle']." Entrance Exam Calendar";
        $result['metaDescription'] = "Keep update with ".$examFilter['examCalendarTitle']." exam dates, notifications, events, alerts. Customize and synch with Google Calendar for latest ".$examFilter['examCalendarTitle']." events.";
        $result['canonicalURL'] = $examFilter['canonicalUrl'];
		$result['validateuser'] = $this->userStatus;
		$userSubscribedExams = array();
		if($result['userId'] > 0){
			$userSubscribedExams = $this->eventCalendarLib->getSubscribedExamsOfUser($result['userId'], $examFilter);
		}
		$result['userSubscribedExams']     = $userSubscribedExams;
		$result['userSubscribedExamsJson'] = json_encode($userSubscribedExams, JSON_HEX_APOS);
		
		$this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_EventCalendar','entity_id'=>$param);
        $result['dfpData']  = $dfpObj->getDFPData($this->userStatus, $dpfParam);
        $this->benchmark->mark('dfp_data_end');

		$userSetReminders = array();
		if($result['userId'] > 0){
			$userSetReminders = $this->eventCalendarLib->getUsersActiveReminders($result['userId']);
		}
		$userSetReminders = escapeSpecialCharacters($userSetReminders);
		$result['userSetReminders']     = $userSetReminders;
		$result['userSetRemindersJson'] = json_encode($userSetReminders, JSON_HEX_APOS);
		$result['examFilter'] = $examFilter;
		if($this->input->is_ajax_request()){
			echo $result['data'];exit;
		}
		$result['showCustomizedGNB'] = true;
		
		//preparing breadcrumbs
		$breadcrumbOptions = array('generatorType' 	=> 'ExamCalendarHomePage',
							   'options' 		=> $examFilter);		
						
		$BreadCrumbGenerator = $this->load->library('common/breadcrumb/BreadcrumbGenerator', $breadcrumbOptions);
		$result['breadcrumbHtml'] = $BreadCrumbGenerator->prepareBreadcrumbHtml();

		//below code used for beacon tracking purpose
		$result['beaconTrackData'] = $this->eventCalendarLib->prepareBeaconTrackData($examFilter);
		//below line is used for conversion trackingpurpose
		$result['eventTrackingPageKeyId']=189;
		$result['alertTrackingPageKeyId']=190;
		$result['reminderTrackingPageKeyId']=191;
		$result['reminderEventLayerTrackingPageKeyId']=899;

		$this->load->view('eventCalender',$result);
    }

	public function getCustomEvents($loggedInUserId,$examFilter){
		$this->init();
		$resultSet = $this->eventCalendarLib->getCustomEvents($loggedInUserId, $examFilter);
		$customEventData = limitDescriptionText($resultSet , 'customEvent');
		$result['data'] = json_encode($customEventData, JSON_HEX_APOS);
		$eventListData = restructuredArray($customEventData);
		$result['eventListData'] = $eventListData;
		return $result;
	}

	public function getExamPageEvents($examIds,$examFilter){
		$this->init();
		$examArrCheck = array();
		$examList = array();
		$examArrCheck = $examIds;
		
		$this->load->helper('event');
		$resultSet = $this->eventCalendarLib->getEvents($examIds,$examFilter);
		$result['examNameList'] = $resultSet['examList'];
        $eventData = $resultSet['eventData'];
        $eventData = $this->eventCalendarLib->createUrl($eventData);
		$formattedData = limitDescriptionText($eventData , 'examPageEvent');
		$result['data'] = json_encode($formattedData, JSON_HEX_APOS);
		$eventListData = restructuredArray($formattedData);
		$result['eventListData'] = $eventListData;
		$result['yearRangeString'] = getEventYearRange($eventListData);
		$result['examFilter'] = $examFilter;
		if(!empty($examArrCheck)){
			if($this->input->is_ajax_request()){
				echo $result['data'];exit;
			}
		}
		return $result;
	}
	
	function addEvent(){
		$this->init();
		$remindData = array();
		$eventStartDate                   = isset($_POST['eventStartDate'])?$this->input->post('eventStartDate'):'';
		$eventStartDate = str_replace('/', '-', $eventStartDate);
		$params['eventStartDate'] = date('Y-m-d',strtotime($eventStartDate));
		$eventEndDate             = isset($_POST['eventEndDate'])?$this->input->post('eventEndDate'):$params['eventStartDate'];
		$eventEndDate = str_replace('/', '-', $eventEndDate);
		$params['eventEndDate'] = date('Y-m-d',strtotime($eventEndDate));
		$params['eventTitle']             = isset($_POST['eventTitle'])?$this->input->post('eventTitle'):'';
		$params['eventDescription']   = isset($_POST['eventDescription'])?$this->input->post('eventDescription'):'';
		$params['userId']             = isset($_POST['userId'])?$this->input->post('userId'):0;
		$params['customEventId']      = isset($_POST['customEventId'])?$this->input->post('customEventId'):0;
		$params['streamId'] = isset($_POST['streamId'])?$this->input->post('streamId'):MANAGEMENT_STREAM;
		$params['courseId'] = isset($_POST['courseId'])?$this->input->post('courseId'):MANAGEMENT_COURSE;
		$params['educationTypeId'] = isset($_POST['educationTypeId'])?$this->input->post('educationTypeId'):EDUCATION_TYPE;

		//below line is used for conversion tracking purpose
		if(!empty($_POST['tracking_keyid'])){
			$params['tracking_keyid'] = $this->input->post('tracking_keyid');
		}	
		
		$reminder_date = isset($_POST['reminder_date_on_addEvent'])?$this->input->post('reminder_date_on_addEvent'):'';
		$remindData['userId']            = $params['userId'];
		$remindData['event_name']        = $params['eventTitle'];
		$remindData['event_type']        = 'customEvent';
		$remindData['event_description'] = $params['eventDescription'];
		$remindData['event_date']        = $params['eventStartDate'];
		$remindData['reminder_date']     = $reminder_date;
		$remindData['status']            = 'live';
		$remindData['date_created']      = date('Y-m-d H:i:s');

		//below line is used for checking conversion tracking purpose
		if(!empty($_POST['remindertracking_keyid'])){
			$remindData['tracking_keyid'] = $this->input->post('remindertracking_keyid');	
		}
		
		
		if($params['customEventId']==0){
			$this->eventCalendarLib->addEvent($params);
			if($reminder_date != ''){
				$this->eventcalendarmodel->addUserReminder($remindData);
			}
		}else{
			$eventData = $this->eventCalendarLib->getEventDetails($params['customEventId']);
			$remindDataOld = array();
			$remindDataOld['userId']     = $params['userId'];
			$remindDataOld['event_name'] = $eventData[0]['eventTitle'];
			$remindDataOld['event_date'] = $eventData[0]['eventStartDate'];
			
			$this->eventCalendarLib->updateEvent($params);
			
			$checkForRedundancy = false;
			$action = 'addEdit';
			$fromWhere = 'editEvent';
			$this->eventcalendarmodel->addUserReminder($remindData, $checkForRedundancy, $action, $fromWhere, $remindDataOld);
		}
	}

	function addEventCalendarSubscription()
	{
		$this->init();
		if($this->input->is_ajax_request() && ((is_array($this->userStatus) && $this->userStatus[0]['userid']>0) || (isset($_POST['userRegisters']) && $_POST['userRegisters']=='yes')))
		{
			$checkForRedundancy = false;
			if(isset($_POST['userRegisters']) && $_POST['userRegisters']=='yes'){
				$userId = $this->input->post('userId');
				setcookie('eventSubscriptionSuccess','success',time()+60,'/',COOKIEDOMAIN);
			}else{
				$checkForRedundancy = true;
				$userId = $this->userStatus[0]['userid'];
			}
			$streamId = isset($_POST['streamId'])?$this->input->post('streamId'):0;
			$courseId = isset($_POST['courseId'])?$this->input->post('courseId'):0;
			$educationTypeId = isset($_POST['educationTypeId'])?$this->input->post('educationTypeId'):0;
			$examArr      = isset($_POST['subscribe_examList']) ? $this->input->post('subscribe_examList') : array();

			//below line is used for conversion tracking purpose
			if(!empty($_POST['tracking_keyid']))
			{
				$tracking_keyid = $this->input->post('tracking_keyid');
			}
			if(!empty($examArr) && $streamId > 0 && $courseId > 0&& $educationTypeId > 0 && $userId>0)
			{
				if(isset($_POST['fromWhere']) && $_POST['fromWhere'] == 'shortRegistrationExamPages')
				{
					$checkForRedundancy = false;
				}
				$this->eventCalendarLib->addUserSubscription($userId, $streamId,$courseId,$educationTypeId, $examArr, $checkForRedundancy,$tracking_keyid);
			}
		}
	}
	
	function sendNotificationToSubscribedUsers()
	{
		$this->validateCron();
		$this->init();
		$examPageModel = new exampagemodel;
		$this->load->library('MailerClient');
		$MailerClient  = new MailerClient();
		$this->load->model('SMS/smsModel');
		$smsmodel_object = new smsModel();
		$userData = $this->eventcalendarmodel->getUserDetailsToSendNotification();
		$examPageRequest = $this->load->library('examPages/examPageRequest');
		foreach($userData as $key=>$contentArr){
			$examPageRequest->setExamName($contentArr['exam_name']);
            $examUrl       = $examPageRequest->getUrl('homepage',true);
            $examDatesUrl  = $examPageRequest->getUrl('importantdates',true);

            $examUrl      .= '?course='.$contentArr['entityId'];
            $examDatesUrl .= '?course='.$contentArr['entityId'];

        	$contentArr['examUrl'] = $MailerClient->generateAutoLoginLink(1, $contentArr['email'], $examUrl);
        	$contentArr['examDatesUrl'] = $MailerClient->generateAutoLoginLink(1, $contentArr['email'], $examDatesUrl);
        	$contentArr['entityType'] = 'exam';
			Modules::run('systemMailer/SystemMailer/sendNotificationToUserForExamAlertSubscription', $contentArr['email'], $contentArr, array());
		}
		echo 'Success';
	}
	
	function addEventCalendarReminder()
	{
		$this->init();
		if($this->input->is_ajax_request() && ((is_array($this->userStatus) && $this->userStatus[0]['userid']>0) || (isset($_POST['userRegisters']) && $_POST['userRegisters']=='yes')))
		{
			$checkForRedundancy = false;
			$dbArr = array();
			$action = 'addEdit';
			if(isset($_POST['userRegisters']) && $_POST['userRegisters']=='yes'){
				$userId = $this->input->post('userId');
				$dbArr['status'] = 'live';
				setcookie('eventReminderSuccess','success',time()+60,'/',COOKIEDOMAIN);
			}else{
				$checkForRedundancy = true;
				$userId = $this->userStatus[0]['userid'];
				$action = isset($_POST['action']) ? $this->input->post('action') : 'addEdit';
			}

			//below line is used for conversion tracking
			$dbArr['tracking_keyid']        = isset($_POST['trackingPageKeyId']) ? $this->input->post('trackingPageKeyId') : '';
			$dbArr['userId']            = $userId;
			$dbArr['event_name']        = isset($_POST['event_name']) ? $this->input->post('event_name') : '';
			$dbArr['event_type']        = isset($_POST['event_type']) ? $this->input->post('event_type') : '';
			$dbArr['event_description'] = isset($_POST['event_description']) ? $this->input->post('event_description') : '';
			$dbArr['event_date']        = isset($_POST['event_date']) ? $this->input->post('event_date') : '';
			$dbArr['event_date']        = date('Y-m-d', strtotime($dbArr['event_date']));
			$dbArr['reminder_date']     = isset($_POST['reminder_date']) ? $this->input->post('reminder_date') : '';
			$dbArr['reminder_date']     = date('Y-m-d', strtotime($dbArr['reminder_date']));
			$dbArr['date_created']      = date('Y-m-d H:i:s');
			if($dbArr['event_name']!='' && $dbArr['reminder_date']!='' && $userId>0 && $dbArr['event_description']!=''){
				$this->eventcalendarmodel->addUserReminder($dbArr, $checkForRedundancy, $action);
			}
		}
	}
	
	function sendReminderToUsers()
	{
		$this->validateCron();
		$this->init();
		$examPageModel = new exampagemodel;
		$this->load->model('SMS/smsModel');
		$smsmodel_object = new smsModel();
		$userData = $this->eventcalendarmodel->getUserDetailsToSendReminders();
		$statusArr = array();
		$totalCharInSms = 160;
		$reminders = array();
		foreach($userData as $key=>$contentArr){
			$userId = $contentArr['userId'];
			$fname  = $contentArr['firstname'];
			$exam   = $contentArr['event_name'];
			$event  = $contentArr['event_description'];
			$date   = date('d-m-y', strtotime($contentArr['event_date']));
			$mobile = $contentArr['mobile'];
			
			$part1  = "Hi $fname, Alert: $exam, ";
			$part2  = $event;
			$part3  = " When: ".$date;
			$p2len  = $totalCharInSms - (strlen($part1) + strlen($part3));
			if(strlen($part2) > $p2len)
			{
				$part2 = substr($part2, 0, $p2len-3);
			}
			$content_sms = $part1.$part2.$part3;
			
			$statusArr[$contentArr['reminder_id']]['sms'] = $smsmodel_object->addSmsQueueRecord("1", $mobile, $content_sms, $userId, 0);
			$reminders[] = $contentArr['reminder_id'];
		}
		$this->eventcalendarmodel->updateReminderQueue($reminders);
		echo 'Number of Reminders sent today : '.count($reminders);
	}

	function getExamWidget($inputData){
		$this->init();
		$examArrWidget = array();
		$resultWidget['examNameListWidget'] = $inputData['eventDataForExamWidget'];
		$examCount = count($resultWidget['examNameListWidget']);
		if($examCount >2){
			$examCount = 3;
		}
		$resultWidget['randomExams']=array_rand($resultWidget['examNameListWidget'],$examCount);
		$this->load->builder('RankingPageBuilder', RANKING_PAGE_MODULE);
		$RankingURLManager  = RankingPageBuilder::getURLManager();
		$displayData['rankingPageUrl'][++$i]['url'] = $urlData['url'];
		$displayData['rankingPageUrl'][$i]['text'] = $urlData['title'];
		$filters= array();
		$rankingPageData = array();
		$this->rankingCommonLib = $this->load->library('rankingV2/RankingCommonLibv2');
		$i=0;
		foreach($resultWidget['randomExams'] as $examId){
			$filters['stream_id'] = $inputData['examFilter']['streamId'];
			$filters['base_course_id'] = $inputData['examFilter']['courseId'];
			$filters['education_type'] = $inputData['examFilter']['educationTypeId'];
			$filters['countryId'] = 2;
			$filters['examId'] = $examId;
			$rankPageData = $this->rankingCommonLib->getNonZeroRankingPages($filters);
			$rankingPageData[$i]['ranking_page_id'] = $rankPageData[0]['ranking_page_id'];
			$rankingPageData[$i]['exam_id'] = $rankPageData[0]['exam_id'];
			if($rankingPageData[$i]['exam_id'] != '')
			{
				$pageIdentifier = $rankingPageData[$i]['ranking_page_id']."-2-0-0-".$rankingPageData[$i]['exam_id'];
				$RankingPageRequest = $RankingURLManager->getRankingPageRequest($pageIdentifier);
				$urlData[$resultWidget['examNameListWidget'][$examId]]['topRankPageUrl'] = $RankingURLManager->buildURL($RankingPageRequest);
			}else{
				$urlData[$resultWidget['examNameListWidget'][$examId]]['topRankPageUrl'] = '';
			}
			$i++;
		}
		$resultWidget['urlData'] = $urlData;
		$this->load->view('examPageWidgets',$resultWidget);
			
	}
	
	function getPredictorWidget(){
		$this->load->view('examPredictorWidget');
	}

	function deleteEvent(){
		$this->init();
		$customEventId = isset($_POST['customEventId']) ? $this->input->post('customEventId') : 0;
		$customEventTitle = isset($_POST['customEventTitle']) ? $this->input->post('customEventTitle') : '';
        if($customEventId>0){
            $this->eventcalendarmodel->deleteEvent($customEventId);
            $userId = $this->userStatus[0]['userid'];
            $this->eventcalendarmodel->deleteReminder($userId,$customEventTitle);
			echo '1';exit;
        }
	}
function getCalendarWidget($callFrom, $filterArr){
		$this->init();
		$examModel = new exampagemodel;
		$calendarWidgetArr = array();
		$calendarWidgetList = array();
		if($this->input->is_ajax_request()){
			$filterArr['categoryName'] = $this->input->post('categoryName');
			$filterArr['courseId'] = $this->input->post('courseId');
			$filterArr['educationTypeId'] = $this->input->post('educationTypeId');
			$callFrom = $this->input->post('fromWhere');
		}
		$categoryName = $filterArr['categoryName'];
		$this->load->helper('event');
		$resultSet = $examModel->getEvents('', $filterArr);
		if($callFrom == 'examPage' || $callFrom == 'examPageMobile' || $callFrom == 'examPageAMP'){
			$resultSetForExam = $this->eventCalendarLib->prepareEventCalDataForExamPage($resultSet);
			$calendarWidgetData['calendarWidgetData'] = $resultSetForExam;
			$calendarWidgetData['categoryName'] = $categoryName;
		}else{
			$calendarWidgetData['calendarWidgetData'] = calendarWidgetArray($resultSet);
		}
		if($categoryName == 'MBA' || $categoryName == 'mba')
		{
			$calendarLink = SHIKSHA_MBA_CALENDAR;
		}
		else
		{
			$calendarLink = SHIKSHA_ENGINEERING_CALENDAR;
		}
		$calendarWidgetData['calendarWidgetLink'] = $calendarLink;
		$calendarWidgetData['fromWhere'] = $callFrom;
		if($callFrom == 'examPage'){
			$this->load->view('/examPages/newExam/widgets/examCalender',$calendarWidgetData);	
		}else if($callFrom == 'examPageMobile'){
			$this->load->view('/mobile_examPages5/newExam/widgets/eventCalendar',$calendarWidgetData);			
		}else if($callFrom == 'examPageAMP'){
			$this->load->view('/mobile_examPages5/newExam/AMP/widgets/eventCalendar',$calendarWidgetData);
		}else {
		$this->load->view('examCalendarWidget',$calendarWidgetData);
		}
	}
	
	/**
   	 * This Controller expires all the User subscription which are older than 8 Months.
   	 * @author Virender Singh <virender.singh@shiksha.com>
   	 * @date   2015-07-22
   	 * @param  none    
   	 * @return none
   	 */
	function expireExamSubscriptionCron()
	{
		$this->validateCron();
		$this->load->model('eventcalendarmodel');
		$this->eventcalendarmodel = new eventcalendarmodel;
		$this->eventcalendarmodel->expireExamSubscriptionForAllUsers();
	}

	function getPrimaryHierarchyForExamCrons($allHierarchies = array()){
		foreach ($allHierarchies as $key => $hier) {
			if($hier['primary_hierarchy'] == 1){
				return $hier;
			}
		}
	}

	/**
	* This Controller Auto-Subscribe Exam Response to Exam Calendar Alerts 
	*/
	function examAlertAutoSubscriptionForExamResponsesCron()
	{
		$this->validateCron();
		$mbaStream = 1;
		$enggStream = 2;
		$category = array(
			2 => array(
				'name' => 'Engineering',
				'bcourse' => 10,
				'eduType' => 20
			),
			1 => array(
				'name' => 'MBA',
				'bcourse' => 101,
				'eduType' => 20
			)
		);
		$this->load->model('eventandexammodel');
		$this->model = new eventandexammodel;
		$responses = $this->model->getAllExamResponsesByInterval();
		if(empty($responses)){
			echo 'No responses';
			return;
		}

		$examIds = array();
		$examStream = array();
		if(!empty($responses['groupIds'])){
			$this->load->builder('ExamBuilder','examPages');
			$examBuilder = new ExamBuilder();
			$this->examRepository = $examBuilder->getExamRepository();
			$groupObjs = $this->examRepository->findMultipleGroup($responses['groupIds']);
			foreach ($groupObjs as $groupId => $groupObj) {
				$hier = $groupObj->getHierarchy();
				$hier = $this->getPrimaryHierarchyForExamCrons($hier);
				$examIds[$groupId] = $groupObj->getExamId();
				$examStream[$groupId] = $hier['stream'];
			}
		}
		$examStreamName = $this->model->getStreamNameForExamCrons(array_unique(array_values($examStream)));
		$examStreamName[1] = 'MBA';
		$examName = array();
		if(!empty($examIds)){
			$examObjs = $this->examRepository->findMultiple($examIds);
			foreach ($examObjs as $examId => $examObj) {
				$examName[$examId] = $examObj->getName();
			}
		}
		$batchInsertData = array();
		$curr_time = date('Y-m-d H:i:s');
		foreach ($responses['responses'] as $groupId => $userIds) {
			$batch = array();
			$users = array_keys($userIds);
			$alreadySubscribed = $this->model->checkIfExamAlreadySubscribed($users, $groupId, $examStream[$groupId]);
			$this->model->updateModifiedDateForNewExamResponses($alreadySubscribed, $groupId);
			$newSubscribe = array_diff($users, $alreadySubscribed);
			foreach ($newSubscribe as $userId) {
				if(empty($examName[$examIds[$groupId]])){
					continue;
				}
				$batch = array();
				$batch['userId'] = $userId;
				$batch['exam_name'] = $examName[$examIds[$groupId]];
				$batch['exam_group_id'] = $groupId;
				$batch['category_name'] = $examStreamName[$examStream[$groupId]];
				$batch['status'] = 'live';
				$batch['date_created'] = $curr_time;
				$batch['streamId'] = $examStream[$groupId];
				$batch['courseId'] = 0;
				$batch['educationTypeId'] = 20;
				$batch['tracking_keyid'] = 190;
				if(!empty($responses['ctaId'][$userId.'_'.$groupId])){
					$batch['tracking_keyid'] = $responses['ctaId'][$userId.'_'.$groupId];
				}
				$batchInsertData[] = $batch;
			}
		}
		if(!empty($batchInsertData)){
			$this->model->autoSubscriberUsers($batchInsertData);
		}
		echo 'Success';
	}

	function subscribeJEEMainUsersToOtherExamsCron(){
		$this->validateCron();
		$this->load->model('eventandexammodel');
		$this->model = new eventandexammodel;
		$this->eventCalendarCronLib = $this->load->library('eventCalendarCronLib');
		$this->load->config('eventAutoSubscribeConfig');
		$inputArr = $this->config->item('examGroupInfo');

		foreach ($inputArr as $grpId => $grpData) {
			$responses = array();
			$responseData = $this->model->getAllExamResponsesForJeeMain($grpId);
		    foreach ($responseData as $value) {
		      $responses['cities'][$value['userCity']] = $value['userCity'];
		      $responses['data'][$value['userCity']][] = $value;
		      $responses['userIds'][$value['userId']] = $value['userId'];
		      $responses['ctaIds'][$value['userId']] = $value['tracking_keyid'];
		    }
		    if(!empty($responseData)){
				$this->eventCalendarCronLib->subscribeExamUsersToOtherExams($responses, $grpData['st'], $grpId, $grpData);
		    }
		}		
	}
}
?>
