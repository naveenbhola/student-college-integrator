<?php
class eventCalendarLib {
	private $CI;

	public function __construct(){
    $this->CI = & get_instance();
    $this->CI->load->model('eventcalendarmodel');
    $this->eventCalendarModel = new eventcalendarmodel;
  }

	public function createUrl($eventData){
		$this->examPageRequest=$this->CI->load->library('examPages/examPageRequest');
    $this->CI->load->builder('ExamBuilder','examPages');
    $examPageBuilder          = new ExamBuilder();
    $this->examPageRepository = $examPageBuilder->getExamRepository();
    $i=0;$tempArr = array();$tempArr1 = array();
    foreach($eventData as $index=>$examInfo){
      if(!in_array($examInfo['title'],$tempArr)){
        $this->examPageRequest->setExamName($examInfo['title']);
        $examPageImpDateInfo = $this->examPageRequest->getUrl('imp_dates');
        $eventData[$i]['exam_url'] = $examPageImpDateInfo['url'];
        if(isset($examInfo['article_id']) && $examInfo['article_id']!='' && $examInfo['article_id']>0){
                //$article_info = $this->examPageRepository->getArticleInfo($examInfo['article_id']);
                //$eventData[$i]['article_url'] = SHIKSHA_HOME.$article_info['url'];
        }else{
            $eventData[$i]['article_url'] = '';
        }
        $tempArr1[$examInfo['title']]['article_url'] = $eventData[$i]['article_url'];
        $tempArr1[$examInfo['title']]['exam_url'] = $eventData[$i]['exam_url'];
        $tempArr[] = $examInfo['title'];
      }else{
              $eventData[$i]['article_url'] = $tempArr1[$examInfo['title']]['article_url'];
              $eventData[$i]['exam_url'] = $tempArr1[$examInfo['title']]['exam_url'];
      }
      $i++;
    }
    //_p($eventData);die;
    return $eventData;
	}

	public function getEvents($examIds,$examFilters){
    //_p($examArr);_p($examFilters);die;
    $this->CI->load->model('examPages/exampagemodel');
    $this->examPageModel = new exampagemodel;
    $result = $this->examPageModel->getEvents($examIds,$examFilters);
    foreach($result as $exam){
      $resultArr['examList'][$exam['examId']] = $exam['title']; 
    }
    $resultArr['eventData'] = $result;
    return $resultArr;
	}

  public function getCustomEvents($loggedInUserId, $examFilter){
    $result = $this->eventCalendarModel->getCustomEvents($loggedInUserId, $examFilter);
    return $result;
  }

	public function addEvent($params){
    $this->eventCalendarModel->addEvent($params);
	}

  public function getEventDetails($customEventId){
    $eventData = $this->eventCalendarModel->getEventDetails($customEventId);
    return $eventData;
  }

  public function updateEvent($params){
    $this->eventCalendarModel->updateEvent($params);
  }


	public function addUserSubscription($userId, $streamId,$courseId,$educationTypeId, $examArr, $checkForRedundancy=false,$tracking_keyid=''){
    $this->examPageLib = $this->CI->load->library('examPages/ExamPageLib');
    $this->CI->load->builder('ExamBuilder','examPages');
    $examBuilder = new ExamBuilder();
    $this->examRepository = $examBuilder->getExamRepository();
    $examNameIdMap = array();
    foreach ($examArr as $examName) {
      $exam = strtolower(seo_url($examName));
      $examData = $this->examPageLib->getExamBasicByName($exam);
      $examNameIdMap[$examData['examId']] = $examName;
    }
    unset($examData);

    $examBasicObjs = $this->examRepository->findMultiple(array_keys($examNameIdMap));
    $examGroupIdMap = array();
    foreach ($examBasicObjs as $examId => $examBasicObj) {
      $groupInfo = $examBasicObj->getPrimaryGroup();
      $examGroupIdMap[$examNameIdMap[$examId]] = $groupInfo['id'];
    }
    $this->eventCalendarModel->addUserSubscription($userId, $streamId,$courseId,$educationTypeId, $examArr, $checkForRedundancy,$tracking_keyid, $examGroupIdMap);
	}	

	public function getSubscribedExamsOfUser($userId, $examFilter){
    $userSubscribedExams = $this->eventCalendarModel->getSubscribedExamsOfUser($userId, $examFilter);
    return $userSubscribedExams;	
	}

  public function getUsersActiveReminders($userId){
    $userSetReminders = $this->eventCalendarModel->getUsersActiveReminders($userId);
    return $userSetReminders;
  }

  public function prepareBeaconTrackData($examFilter){   
    $beaconTrackData = array(
        'pageIdentifier' => 'eventCalendar',
        'pageEntityId'   => 0, // No Page entity id for this one
        'extraData' => array(
            'hierarchy' => array(
              'streamId' => $examFilter['streamId'],
              'substreamId' => 0,
              'specializationId' => 0
              ),
            'baseCourseId' => $examFilter['courseId'],
            'educationType' => $examFilter['educationTypeId'],
            'countryId' => 2
            )
    );
    return $beaconTrackData;
  }

  function prepareEventCalDataForExamPage(&$resultSet){
    $i = 0;
    foreach ($resultSet as $key => $value) {
      if($i >= 9){
        break;
      }

      $startDate = $value['start'];
      $endDate = $value['end'];
      if(($startDate == '0000-00-00' && $endDate == '0000-00-00') || (strtotime($startDate) < strtotime(date('Y-m-d')))){
            continue;
      } 
      $eventStartMonth = substr(date('F', strtotime($startDate)),0,3);
      $eventStartYear = date('Y',strtotime($startDate));
      $eventEndMonth = substr(date('F', strtotime($endDate)), 0, 3);
      $eventEndYear = date('Y',strtotime($endDate));
      $eventStartDay = date('d',strtotime($startDate));
      $eventEndDay = date('d',strtotime($endDate));
      $dateIndex = $eventStartDay.' '.$eventStartMonth.'-'.$eventEndDay.' '.$eventEndMonth;
      $resultForExamPage[$dateIndex][$i]['description'] = $value['description'];
      $resultForExamPage[$dateIndex][$i]['title'] = $value['title'];
      $resultForExamPage[$dateIndex][$i]['year'] = $value['year'];
      if($value['isPrimary'] == '1'){
	$resultForExamPage[$dateIndex][$i]['url'] = SHIKSHA_HOME.$value['url'];
      }
      else{
	$resultForExamPage[$dateIndex][$i]['url'] = SHIKSHA_HOME.$value['url']."?course=".$value['groupId'];
      }
      $i++;

    }
    return $resultForExamPage;
  }
}
?>
