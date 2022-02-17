<?php 
/*
1) Number of questions asked on listings, course ID, course name, institute ID, institute Name, CR Name of last n days
2) Questions answered among the questions asked above
*/
class CAReports extends MX_Controller {
	public function __construct(){
		$this->userStatus = $this->checkUserValidation();
		if($this->userStatus!='false'){
			$usergroup = $this->userStatus[0]['usergroup'];
			if($usergroup != "cms"){
				header("location:/enterprise/Enterprise/disallowedAccess");
				exit();
			}
		}
	}

	private function init(){
		$this->careportsmodel = $this->load->model('CAEnterprise/careportsmodel');
		$this->load->helper('security');

		$this->load->builder("nationalCourse/CourseBuilder");
		$courseBuilder = new CourseBuilder();
		$this->courseRepository = $courseBuilder->getCourseRepository();
	}

	public function caQnAReport($days = 1){
		$this->init();
		$days = (int) $days;
		if($days > 30){
			$days = 30;
		}
		$days = xss_clean($days);
		//get all CA list
		$CAList = $this->careportsmodel->getAllCAList();
		
		//extract data from CA list
		$CAIds = $CANames = $CACourses = $courseWithCA = $CAPrograms = array();
		foreach ($CAList as $key => $value) {
			$CAIds[$value['userId']]               = $value['userId'];
			$CANames[$value['userId']]             = $value['displayname'];
			$CAPrograms[$value['userId']]             = $value['programId'];
			
			$CACourses[$value['mainCourse']]       = $value['mainCourse'];
			if(!empty($value['otherCourse'])){
				$CACourses[$value['otherCourse']]      = $value['otherCourse'];
			}
			
			$courseWithCA[$value['mainCourse']][$value['userId']]  = $value['userId'];
			if(!empty($value['otherCourse'])){
				$courseWithCA[$value['otherCourse']][$value['userId']] = $value['userId'];
			}
		}
		//remove duplicates
		array_walk($courseWithCA, array($this, 'getUniqueValues'));

		//get question data
		$quesData = $this->careportsmodel->getQuestionsOnCourses($CACourses, $days);

		//format output
		$finalData = array();
		foreach ($CACourses as $courseId) {
			//course name from repo
			$crsObj = $this->courseRepository->find($courseId);
			$objCheck = $crsObj->getId();
			if(empty($objCheck)){
				continue;
			}
			$finalData[$courseId]['courseName'] = $crsObj->getName();
			$finalData[$courseId]['instId'] = $crsObj->getInstituteId();
			$finalData[$courseId]['instName'] = $crsObj->getInstituteName();
			foreach ($courseWithCA[$courseId] as $caId) {
				$finalData[$courseId]['caData'][$caId] = $CANames[$caId];
				$finalData[$courseId]['programs'][$caId] = $CAPrograms[$caId];
			}
			if(isset($quesData['quesCount'][$courseId])){
				$finalData[$courseId]['quesCount'] = $quesData['quesCount'][$courseId];
			}else{
				$finalData[$courseId]['quesCount'] = 0;
			}
			$finalData[$courseId]['quesIds'] = $quesData['quesIds'][$courseId];
		}

		//get answer data
		foreach ($quesData['quesIds'] as $cId => $qIds) {
			if(!empty($qIds)){
				$caIds = $courseWithCA[$cId];
				$ansData[$cId] = $this->careportsmodel->getAnswerCountByCA($qIds, $caIds, $days);
			}
		}

		//format final output
		foreach ($finalData as $crsId => &$value) {
			if(isset($ansData[$crsId])){
				$value['ansCount'] = $ansData[$crsId];
			}else{
				$value['ansCount'] = 0;
			}
		}
		//_p($finalData);die;
		$this->createCSV($finalData, $days);
		die;
	}

	private function getUniqueValues(&$value, $key){
		$value = array_unique($value);
	}

	private function createCSV(&$finalData, $days){
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=caQnAReport_'.date('d_m_Y', 
			strtotime("-".($days+1)." days")).'.csv');
		$output = fopen('php://output', 'w');
		fputcsv($output, array('CA Name', 'Program ID', 'Course', 'Course ID', 'Institute', 'Institute ID', 'Question Count', 'Answer Count'));
		$row = array();
		foreach ($finalData as $courseId => $value) {
			foreach ($value['caData'] as $caId => $caName) {
				$row = array($caName, $value['programs'][$caId], $value['courseName'], $courseId, $value['instName'], $value['instId'], $value['quesCount'], $value['ansCount']);
				fputcsv($output, $row);
			}
		}
		return;
	}
}