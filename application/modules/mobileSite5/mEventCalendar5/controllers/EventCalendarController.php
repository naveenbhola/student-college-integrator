<?php
class EventCalendarController extends ShikshaMobileWebSite_Controller{
    
    //constructor
    public function __construct(){
        $this->init();
    }
    
    public function init(){
        $helper=array('url','image','shikshautility','utility_helper');
        if(is_array($helper)){
                $this->load->helper($helper);
        }
        $this->load->helper('mcommon5/mobile_html5');
        if(($this->userStatus == ""))
	    $this->userStatus = $this->checkUserValidation();
        $this->load->helper('event/event');
        $this->load->builder('ExamBuilder','examPages');
        $examPageBuilder          = new ExamBuilder();
        $this->examPageRepository = $examPageBuilder->getExamRepository();
        
        $this->load->model('examPages/exampagemodel');
        $this->load->model('event/eventcalendarmodel');
        $this->eventcalendarmodel = new eventcalendarmodel;
        $this->examPageModel = new exampagemodel;
        $this->eventCalendarLib = $this->load->library('event/eventCalendarLib');
    }
	
    public function loadEventCalendar($param){
        $param = strtolower($param);
		$examIds = array();
		if($this->input->is_ajax_request()){
            $examFilter['streamId'] = isset($_POST['streamId']) ? $this->input->post('streamId') : 0;
            $examFilter['courseId'] = isset($_POST['courseId']) ? $this->input->post('courseId') : 0;
            $examFilter['educationTypeId'] = isset($_POST['educationTypeId']) ? $this->input->post('educationTypeId') : 0;
            $examIds = isset($_POST['examList']) ? $this->input->post('examList') : $examIds;
		}else{
            $this->examCalendarRedirectionLib = $this->load->library('event/examCalendarRedirectionLib');
            $examFilter = $this->examCalendarRedirectionLib->urlRedirectionCheck($param);
		}
        $result = array();
        $result['userStatus']   = $this->userStatus;
        $result['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        $examPageEventsInformation = $this->getExamPageEvents($examIds,$examFilter);
        $result['examNameList'] = $examPageEventsInformation['examNameList'];
        $examPageEventsListingData = $examPageEventsInformation['data'];
        $customEventsInformation = $this->getCustomEvents($result['userId'], $examFilter);
        $customEventsListingData = $customEventsInformation['data'];

        $this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_EventCalendar','entity_id'=>$param);
        $result['dfpData']  = $dfpObj->getDFPData($this->userStatus, $dpfParam);
        $this->benchmark->mark('dfp_data_end');
        
        $i=0;
		$resultSet = array();
		foreach($examPageEventsListingData as $key=>$value){
				$resultSet[$i] = $value;		
				$i++;	
		}
		foreach($customEventsListingData as $key=>$value){
				$resultSet[$i] = $value;		
				$i++;	
		}
        usort($resultSet, 'date_compare');
		$result['eventListData'] = rearrageEvent($resultSet);
		$result['eventCount'] = mapEventCountWithMonth($result['eventListData']);
        //$result['eventListData'] = $resultSet;
        $userSubscribedExams = array();
        if($result['userId'] > 0){
            $userSubscribedExams = $this->eventCalendarLib->getSubscribedExamsOfUser($result['userId'], $examFilter);
        }
        $result['userSubscribedExams']     = $userSubscribedExams;
        $result['userSubscribedExamsJson'] = json_encode($userSubscribedExams, JSON_HEX_APOS);
        $userSetReminders = array();
        if($result['userId'] > 0){
            $userSetReminders = $this->eventCalendarLib->getUsersActiveReminders($result['userId']);
        }
        $userSetReminders = escapeSpecialCharacters($userSetReminders);
        $result['userSetReminders']     = $userSetReminders;
        $result['userSetRemindersJson'] = json_encode($userSetReminders, JSON_HEX_APOS);
        $result['boomr_pageid'] = 'EVENT_CALENDAR';
		$result['m_meta_title'] = $examFilter['examCalendarTitle']." Entrance Exam Calendar";
        $result['m_meta_description'] = "Keep update with ".$examFilter['examCalendarTitle']." exam dates, notifications, events, alerts. Customize and synch with Google Calendar for latest ".$examFilter['examCalendarTitle']." events.";
		$result['m_meta_keywords'] = ' ';
		$result['canonicalURL'] = $examFilter['canonicalUrl'];
		
		//below code used for beacon tracking purpose
        $result['beaconTrackData'] = $this->eventCalendarLib->prepareBeaconTrackData($examFilter);        
        //below line is used for conversion tracking purpose
        $result['eventtrackingPageKeyId']=356;
        $result['alerttrackingPageKeyId']=357;
        $result['remindertrackingPageKeyId']=358;

        $result['examFilter'] = $examFilter;

		if($this->input->is_ajax_request()){
			echo $this->load->view('eventListing',$result,TRUE);
			exit;
		}else{
            if(!isset($_COOKIE['EC_CM'])){
                setcookie('EC_CM', 1, time()+(30*24*3600), '/', COOKIEDOMAIN);
                $_COOKIE['EC_CM'] = 1;
            }
			$this->load->view('eventCalendar',$result);
		}
    }
    
    public function getExamPageEvents($examIds,$examFilter){
		$examList = array();
		$this->load->helper('event');
		$resultSet = $this->eventCalendarLib->getEvents($examIds,$examFilter);
		$result['examNameList'] = $resultSet['examList'];
        $eventData = $resultSet['eventData'];
		$eventData = $this->eventCalendarLib->createUrl($eventData);
		$formattedData = limitDescriptionText($eventData,'examPageEvent');
		$result['data'] = $formattedData;
		return $result;
	}
    
	public function getCustomEvents($loggedInUserId,$examFilter){
		$resultSet = $this->eventCalendarLib->getCustomEvents($loggedInUserId,$examFilter);
		$customEventData = limitDescriptionText($resultSet , 'customEvent');
		$result['data'] = $customEventData;
		return $result;
	}

    function addEventCalendarSubscription($examArr, $streamId,$courseId,$educationTypeId, $userId, $checkForRedundancy = false,$tracking_keyid=''){
        if(!empty($examArr) && $streamId > 0 && $courseId> 0 && $educationTypeId> 0 && $userId>0){
            $this->eventCalendarLib->addUserSubscription($userId, $streamId,$courseId,$educationTypeId, $examArr, $checkForRedundancy,$tracking_keyid);
            setcookie('mobile_EC_set_alerts','',time()-3600,'/',COOKIEDOMAIN);
            setcookie('eventCalendarSuccessPopup','yes',time()+3600,'/',COOKIEDOMAIN);
            return;
        }
        if((is_array($this->userStatus) && $this->userStatus[0]['userid']>0) || (isset($_POST['userId']) && $_POST['userId'] > 0))
        {
            $checkForRedundancy = false;
            if(isset($_POST['userId']) && $_POST['userId'] > 0){
                $userId = $this->input->post('userId');
                setcookie('eventCalendarSuccessPopup','yes',time()+3600,'/',COOKIEDOMAIN);
            }
            else{
                $checkForRedundancy = true;
                $userId = $this->userStatus[0]['userid'];
            }
            $streamId     = isset($_POST['streamId']) ? $this->input->post('streamId') : 0;
            $courseId     = isset($_POST['courseId']) ? $this->input->post('courseId') : 0;
            $educationTypeId     = isset($_POST['educationTypeId']) ? $this->input->post('educationTypeId') : 0;
            $examListForAlert = isset($_POST['examListForAlert']) ? $this->input->post('examListForAlert') : '';
           
           //below line is used for conversion tracking purpose
            $tracking_keyid = isset($_POST['tracking_keyid']) ? $this->input->post('tracking_keyid') : '';
           
            
            $examArr = array();
            if($examListForAlert != ''){
                $examArr = explode('@#@', $examListForAlert);
            }
            if($streamId> 0 && $courseId> 0 && $educationTypeId> 0 && $userId>0){
                $this->eventCalendarLib->addUserSubscription($userId, $streamId,$courseId,$educationTypeId, $examArr, $checkForRedundancy,$tracking_keyid);
                setcookie('mobile_EC_set_alerts','',time()-3600,'/',COOKIEDOMAIN);
                echo 'done';
            }else{
                echo 'error';
            }
        }
        else
        {
            echo 'error';
        }
    }
    
    function addEventCalendarReminder($dataArr, $userId, $checkForRedundancy){
        if(!empty($dataArr) && $userId>0 && isset($checkForRedundancy))
        {
            $dataArr['userId'] = $userId;
            $this->eventcalendarmodel->addUserReminder($dataArr, $checkForRedundancy, 'addEdit');
            setcookie('mobile_EC_set_reminders','',time()-3600,'/',COOKIEDOMAIN);
            $cookieValue = $dataArr['event_date'].'@#@'.$dataArr['event_name'];
            setcookie('eventReminderSuccess',$cookieValue,time()+60,'/',COOKIEDOMAIN);
            return;
        }
        if((is_array($this->userStatus) && $this->userStatus[0]['userid']>0) || (isset($_POST['userId']) && $_POST['userId'] > 0))
        {
            $checkForRedundancy = false;
            $dbArr = array();
            $action = 'addEdit';
            $dbArr['event_date']        = isset($_POST['event_date']) ? $this->input->post('event_date') : '';
            $dbArr['event_name']        = isset($_POST['event_name']) ? $this->input->post('event_name') : '';
            if(isset($_POST['userId']) && $_POST['userId'] > 0){
                $userId = $this->input->post('userId');
                $dbArr['status'] = 'live';
                $cookieValue = $dbArr['event_date'].'@#@'.$dbArr['event_name'];
                setcookie('eventReminderSuccess',$cookieValue,time()+60,'/',COOKIEDOMAIN);
            }else{
                $checkForRedundancy = true;
                $userId = $this->userStatus[0]['userid'];
                $action = isset($_POST['action']) ? $this->input->post('action') : 'addEdit';
            }
            $dbArr['userId']            = $userId;
            $dbArr['event_type']        = isset($_POST['event_type']) ? $this->input->post('event_type') : '';
            $dbArr['event_description'] = isset($_POST['event_description']) ? $this->input->post('event_description') : '';
            $dbArr['event_date']        = date('Y-m-d', strtotime($dbArr['event_date']));
            $dbArr['reminder_date']     = isset($_POST['reminder_date']) ? $this->input->post('reminder_date') : '';
            $dbArr['reminder_date']     = date('Y-m-d', strtotime($dbArr['reminder_date']));
            $dbArr['date_created']      = date('Y-m-d H:i:s');

            //below line is used for conversion tracking purpose
            $tracking_keyid = $this->input->post('tracking_keyid');
            if(isset($tracking_keyid))
            {
                $dbArr['tracking_keyid']=$tracking_keyid;
            }
            if($dbArr['event_name']!='' && $dbArr['reminder_date']!='' && $userId>0 && $dbArr['event_description']!='')
            {
                $this->eventcalendarmodel->addUserReminder($dbArr, $checkForRedundancy, $action);
                setcookie('mobile_EC_set_reminders','',time()-3600,'/',COOKIEDOMAIN);
                echo 'done';
            }else{
                echo 'error';
            }
        }
        else
        {
            echo 'error';
        }
    }
    
    function addEventOnCalendar($dataStr = ''){
        $post = array();
        $params = array();
        $remindData = array();
        $redirect = false;
        if(isset($dataStr) && $dataStr != ''){
            $dataArr = explode('&', $dataStr);
            foreach($dataArr as $val){
                $keyValue = explode('=', $val);
                $post[$keyValue[0]] = $keyValue[1];
            }
            $eventStartDate              = isset($post['eventStartDate'])?$post['eventStartDate']:'';
            $eventStartDate              = str_replace('/', '-', $eventStartDate);
            $params['eventStartDate']    = date('Y-m-d',strtotime($eventStartDate));
            $eventEndDate                = isset($post['eventEndDate'])?$post['eventEndDate']:$params['eventStartDate'];
            $eventEndDate                = str_replace('/', '-', $eventEndDate);
            $params['eventEndDate']      = date('Y-m-d',strtotime($eventEndDate));
            $params['eventTitle']        = isset($post['eventTitle'])?$post['eventTitle']:'';
            $params['eventDescription']  = isset($post['eventDescription'])?$post['eventDescription']:'';
            $params['customEventId']     = isset($post['customEventId'])?$post['customEventId']:0;
            $params['streamId']          = isset($post['streamId'])?$this->input->post('streamId'):MANAGEMENT_STREAM;
            $params['courseId']          = isset($post['courseId'])?$this->input->post('courseId'):MANAGEMENT_COURSE;
            $params['educationTypeId']   = isset($post['educationTypeId'])?$this->input->post('educationTypeId'):EDUCATION_TYPE;
            $params['userId']            = isset($post['newUserId'])?$post['newUserId']:0;
            
            $reminder_date               = (isset($post['reminder_date_on_addEvent']) && $post['reminder_date_on_addEvent']!='')?$post['reminder_date_on_addEvent']:'';
            $iosIconSts                  = isset($post['iosIconSts'])?$post['iosIconSts']:'off';
        }else{
            $eventStartDate              = isset($_POST['eventStartDate'])?$this->input->post('eventStartDate'):'';
            $eventStartDate              = str_replace('/', '-', $eventStartDate);
            $params['eventStartDate']    = date('Y-m-d',strtotime($eventStartDate));
            $eventEndDate                = isset($_POST['eventEndDate'])?$this->input->post('eventEndDate'):$params['eventStartDate'];
            $eventEndDate                = str_replace('/', '-', $eventEndDate);
            $params['eventEndDate']      = date('Y-m-d',strtotime($eventEndDate));
            $params['eventTitle']        = isset($_POST['eventTitle'])?$this->input->post('eventTitle'):'';
            $params['eventDescription']  = isset($_POST['eventDescription'])?$this->input->post('eventDescription'):'';
            $params['customEventId']     = isset($_POST['customEventId'])?$this->input->post('customEventId'):0;
            $params['streamId']          = isset($post['streamId'])?$this->input->post('streamId'):MANAGEMENT_STREAM;
            $params['courseId']          = isset($post['courseId'])?$this->input->post('courseId'):MANAGEMENT_COURSE;
            $params['educationTypeId']   = isset($post['educationTypeId'])?$this->input->post('educationTypeId'):EDUCATION_TYPE;
            if(isset($_POST['newUserId']) && $_POST['newUserId'] != '' && $_POST['newUserId'] != 0){
                $params['userId']        = $this->input->post('newUserId');
            }else{
                $params['userId']        = isset($_POST['userId'])?$this->input->post('userId'):0;
                $redirect                = true;
            }
            
            $reminder_date               = (isset($_POST['reminder_date_on_addEvent']) && $_POST['reminder_date_on_addEvent']!='')?$this->input->post('reminder_date_on_addEvent'):'';
            $iosIconSts                  = isset($_POST['iosIconSts'])?$this->input->post('iosIconSts'):'off';
        }
        
        $remindData['userId']            = $params['userId'];
        $remindData['event_name']        = $params['eventTitle'];
        $remindData['event_type']        = 'customEvent';
        $remindData['event_description'] = $params['eventDescription'];
        $remindData['event_date']        = $params['eventStartDate'];
        $remindData['reminder_date']     = $reminder_date;
        $remindData['status']            = 'live';
        $remindData['date_created']      = date('Y-m-d H:i:s');

        //below line is used for conversion tracking purpose
        $tracking_keyid=isset($post['tracking_keyid']) ? $post['tracking_keyid']: (!empty($_POST['tracking_keyid'])?$this->input->post('tracking_keyid'):null);
        if(isset($tracking_keyid)){
            $params['tracking_keyid']=$tracking_keyid;
        }
        
        $remindertracking_keyid=isset($post['remindertracking_keyid']) ? $post['remindertracking_keyid']: (!empty($_POST['remindertracking_keyid'])?$this->input->post('remindertracking_keyid'):null);
        if(isset($remindertracking_keyid)){
            $remindData['tracking_keyid']=$remindertracking_keyid;
        }
        if($params['customEventId']==0){
                $this->eventCalendarLib->addEvent($params);
                if($iosIconSts == 'on' && $reminder_date != ''){
                    $this->eventcalendarmodel->addUserReminder($remindData);
                }
                setcookie('mobile_EC_event_action', 'addEvent', time()+120, '/', COOKIEDOMAIN);
        }else{
                $eventData = $this->eventCalendarLib->getEventDetails($params['customEventId']);
                $remindDataOld = array();
                $remindDataOld['userId']     = $params['userId'];
                $remindDataOld['event_name'] = $eventData[0]['eventTitle'];
                $remindDataOld['event_date'] = $eventData[0]['eventStartDate'];
                $this->eventCalendarLib->updateEvent($params);
                if($iosIconSts == 'off'){
                    $remindData['reminder_date'] = '';
                }
                
                $checkForRedundancy = false;
                $action = 'addEdit';
                $fromWhere = 'editEvent';
                $this->eventcalendarmodel->addUserReminder($remindData, $checkForRedundancy, $action, $fromWhere, $remindDataOld);
                setcookie('mobile_EC_event_action', 'editEvent', time()+120, '/', COOKIEDOMAIN);
        }
        if($redirect){
            $examCalendarTitle = isset($post['examCalendarTitle']) ? $post['examCalendarTitle'] : 'mba';
            header('location: '.SHIKSHA_HOME.'/'.strtolower($examCalendarTitle).'-exams-dates', TRUE, 301);
            exit;
        }
    }
    
    function deleteEventFromCalendar(){
        $customEventId = isset($_POST['customEventId']) ? $this->input->post('customEventId') : 0;
        $customEventTitle = isset($_POST['customEventTitle']) ? $this->input->post('customEventTitle') : '';
        if($customEventId>0){
            $this->eventcalendarmodel->deleteEvent($customEventId);
            $userId = $this->userStatus[0]['userid'];
            $this->eventcalendarmodel->deleteReminder($userId,$customEventTitle);
            setcookie('mobile_EC_event_action', 'deleteEvent', time()+120, '/', COOKIEDOMAIN);
            echo '1';exit;
        }
    }
}
?>
