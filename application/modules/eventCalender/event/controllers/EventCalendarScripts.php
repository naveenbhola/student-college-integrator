<?php 
class EventCalendarScripts extends MX_Controller
{
	public function __construct(){
		$this->examPageLib = $this->load->library('examPages/ExamPageLib');
		$this->load->builder('ExamBuilder','examPages');
		$examBuilder = new ExamBuilder();
		$this->examRepository = $examBuilder->getExamRepository();
		$this->load->model('event/eventandexammodel');
		$this->model = new eventandexammodel;
		$this->chunkSize = 100;
	}
	function addPrimaryGroupIdToExistingSubscribedExams(){
		$this->validateCron();
		$oldSubscriptions = $this->model->getOldExamSubscriptions();
		$subscriptionIdNameMap = $examNamesArr = $examNameIdMap = array();
		$skippedExams = array();
		foreach ($oldSubscriptions as $subscriptions) {
			$subscriptionIdNameMap[$subscriptions['id']] = $subscriptions['exam_name'];
			$examNamesArr[$subscriptions['exam_name']] = $subscriptions['exam_name'];
		}
		foreach ($examNamesArr as $examName) {
			$exam = strtolower(seo_url($examName));
			$examData = $this->examPageLib->getExamBasicByName($exam);
			if(empty($examData['examId'])){
				$skippedExams[] = $examName;
				continue;
			}
			$examNameIdMap[$examData['examId']] = $examData['examName'];
		}
		if(!empty($examNameIdMap)){
			$examBasicObjs = $this->examRepository->findMultiple(array_keys($examNameIdMap));
			$examGroupIdMap = array();
			foreach ($examBasicObjs as $examId => $examBasicObj) {
				$groupInfo = $examBasicObj->getPrimaryGroup();
				$examGroupIdMap[$examNameIdMap[$examId]] = $groupInfo['id'];
			}
			$examSubscriptionTuples = array();
			foreach ($subscriptionIdNameMap as $subscriptionId => $examName) {
				if(!empty($examGroupIdMap[$examName])){
					$examSubscriptionTuples[$examGroupIdMap[$examName]][] = $subscriptionId;
				}else{
					//$examSubscriptionTuples[$examName][] = $subscriptionId;
				}
			}
			foreach ($examSubscriptionTuples as $groupId => $subscriptionIds) {
				$updateData = array();
				foreach ($subscriptionIds as $subscriptionId) {
					$updateData[] = array('id' => $subscriptionId, 'exam_group_id' => $groupId);
				}
				$this->model->updateGroupIdsInEventSubscriptionTable($updateData, 'id');
			}
		}
		if(!empty($skippedExams)){
			_p('Following are the exam names which does not exist now :');
			_p($skippedExams);
		}
		_p('Done');
	}
}
