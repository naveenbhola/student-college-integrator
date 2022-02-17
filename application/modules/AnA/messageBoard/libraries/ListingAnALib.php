<?php 
class ListingAnALib
{
	function init(){
		$this->CI=& get_instance();
		$this->listingqnamodel = $this->CI->load->model('listingqnamodel');
		$this->cacheLib  = $this->CI->load->library('cacheLib');
	}

	function getQuestionsBasedOnParams($param){
		$this->init();
		$quesType       = $param['quesType'];
		$crAvailability = $param['crAvailability'];
		$allQues = array();
		//Case 1
		if($quesType == 'allListingQues' && $crAvailability == 'allCases'){
			$allQues = $this->listingqnamodel->getAllCourseAndInstQuestions($param);
		}
		//Case 2
		else if($quesType == 'allListingQues' && $crAvailability == 'courseCR'){
			$caData = $this->listingqnamodel->getAllCAAndCourseIds();
			$allQues = $this->listingqnamodel->getAllCourseQuestionsHavingCR($param, $caData['caCourseIds']);
		}
		//Case 3
		else if($quesType == 'allListingQues' && $crAvailability == 'collegeOnlyCR'){
			$caData = $this->listingqnamodel->getAllCAAndCourseIds();
			$allQues = $this->listingqnamodel->getAllCourseAndInstQuestionsHavingIndirectCR($param, $caData['caCourseIds'], $caData['caInstIds']);
		}
		//Case 4
		else if($quesType == 'allListingQues' && $crAvailability == 'noCR'){
			$caData = $this->listingqnamodel->getAllCAAndCourseIds();
			$allQues = $this->listingqnamodel->getAllCourseAndInstQuestionsNotHavingCR($param, $caData['caCourseIds'], $caData['caInstIds']);
		}
		//Case 5
		else if($quesType == 'courseQues' && $crAvailability == 'allCases'){
			$allQues = $this->listingqnamodel->getAllCourseQuestions($param);
		}
		//Case 6
		else if($quesType == 'courseQues' && $crAvailability == 'courseCR'){
			$caData = $this->listingqnamodel->getAllCAAndCourseIds();
			$allQues = $this->listingqnamodel->getAllCourseQuestionsHavingCR($param, $caData['caCourseIds']);
		}
		//Case 7
		else if($quesType == 'courseQues' && $crAvailability == 'collegeOnlyCR'){
			$caData = $this->listingqnamodel->getAllCAAndCourseIds();
			$allQues = $this->listingqnamodel->getAllCourseQuestionsHavingIndirectCR($param, $caData['caCourseIds'], $caData['caInstIds']);
		}
		//Case 8
		else if($quesType == 'courseQues' && $crAvailability == 'noCR'){
			$caData = $this->listingqnamodel->getAllCAAndCourseIds();
			$allQues = $this->listingqnamodel->getAllCourseQuestionsNotHavingCR($param, $caData['caCourseIds'], $caData['caInstIds']);
		}
		//Case 9
		else if($quesType == 'instituteQues' && $crAvailability == 'allCases'){
			$allQues = $this->listingqnamodel->getAllInstQuestions($param);
		}
		//Case 10
		else if($quesType == 'instituteQues' && $crAvailability == 'courseCR'){
			//No data in this case as this is an invalid case
		}
		//Case 11
		else if($quesType == 'instituteQues' && $crAvailability == 'collegeOnlyCR'){
			$caData = $this->listingqnamodel->getAllCAAndCourseIds();
			$allQues = $this->listingqnamodel->getAllInstQuestionsHavingCR($param, $caData['caInstIds']);
		}
		//Case 12
		else if($quesType == 'instituteQues' && $crAvailability == 'noCR'){
			$caData = $this->listingqnamodel->getAllCAAndCourseIds();
			$allQues = $this->listingqnamodel->getAllInstQuestionsNotHavingCR($param, $caData['caInstIds']);
		}
		return $allQues;
	}

	public function getAnswerData(&$threadIdTimeArr, $param){
		if(empty($threadIdTimeArr)){
			return array();
		}
		$caData = array();
		if(in_array($param['crAvailability'], array('courseCR', 'collegeOnlyCR'))){
			$caData = $this->listingqnamodel->getAllCAAndCourseIds();
		}
		$threadIdArr = array_keys($threadIdTimeArr);
		$ansDataRaw = $this->listingqnamodel->getAnswersOfQuestions($threadIdArr);
		
		$finalArr = array();
		foreach ($ansDataRaw as $ansRow) {
			$threadCreationTime = $threadIdTimeArr[$ansRow['questionId']];
			$max2dayTime = strtotime(date('Y-m-d 23:59:59', strtotime($threadCreationTime.' +2 days')));
			$max7dayTime = strtotime(date('Y-m-d 23:59:59', strtotime($threadCreationTime.' +7 days')));

			if(strtotime($ansRow['answerDate']) <= $max2dayTime){
				$finalArr[$ansRow['questionId']]['2dayAnsFlag'] = 'yes';
				if(in_array($param['crAvailability'], array('courseCR', 'collegeOnlyCR'))){
					if(!empty($caData['caUserIds'][$ansRow['answeredBy']])){
						$finalArr[$ansRow['questionId']]['2dayAnsFlagOfCA'] = 'yes';
					}
				}
			}
			if(strtotime($ansRow['answerDate']) <= $max7dayTime){
				$finalArr[$ansRow['questionId']]['7dayAnsFlag'] = 'yes';
				if(in_array($param['crAvailability'], array('courseCR', 'collegeOnlyCR'))){
					if(!empty($caData['caUserIds'][$ansRow['answeredBy']])){
						$finalArr[$ansRow['questionId']]['7dayAnsFlagOfCA'] = 'yes';
					}
				}
			}
		}
		return $finalArr;
	}

	function setDefaultValues($param){
		$finalValues = array();
		if(empty($param['startDate'])){
			$finalValues['startDate'] = date('Y-m-d', strtotime('-1 day'));
		}else{
			$finalValues['startDate'] = $param['startDate'];
		}

		if(empty($param['endDate'])){
			$finalValues['endDate'] = date('Y-m-d', strtotime('-1 day'));
		}else{
			$finalValues['endDate'] = $param['endDate'];
		}

		if(empty($param['quesType'])){
			$finalValues['quesType'] = 'allListingQues';
		}else{
			$finalValues['quesType'] = $param['quesType'];
		}

		if(empty($param['crAvailability'])){
			$finalValues['crAvailability'] = 'allCases';
		}else{
			$finalValues['crAvailability'] = $param['crAvailability'];
		}
		return $finalValues;
	}

	function generateDataInCSV(&$quesData, &$ansData, $param){
		foreach ($quesData as $instId => $instQuesData) {
			foreach ($instQuesData as $courseId => $courseQuesData) {
				$quesAnsweredWithin2days[$instId.'-'.$courseId] = 0;
				$quesAnsweredWithin7days[$instId.'-'.$courseId] = 0;
				if(in_array($param['crAvailability'], array('courseCR', 'collegeOnlyCR'))){
					$quesAnsweredByCAWithin2days[$instId.'-'.$courseId] = 0;
					$quesAnsweredByCAWithin7days[$instId.'-'.$courseId] = 0;
				}

				foreach ($courseQuesData as $threadId) {
					if($ansData[$threadId]['2dayAnsFlag'] == 'yes'){
						$quesAnsweredWithin2days[$instId.'-'.$courseId]++;
					}
					if($ansData[$threadId]['7dayAnsFlag'] == 'yes'){
						$quesAnsweredWithin7days[$instId.'-'.$courseId]++;
					}
					if(in_array($param['crAvailability'], array('courseCR', 'collegeOnlyCR'))){
						if($ansData[$threadId]['2dayAnsFlagOfCA'] == 'yes')
							$quesAnsweredByCAWithin2days[$instId.'-'.$courseId]++;
						if($ansData[$threadId]['7dayAnsFlagOfCA'] == 'yes')
							$quesAnsweredByCAWithin7days[$instId.'-'.$courseId]++;
					}
				}
			}
		}
		//outputData start
		/*_p($quesData);
		_p('+++++++++++++++++++++++++++++++++++');
		_p($ansData);
		_p('-----------------------------------');
		_p($quesAnsweredWithin2days);
		_p($quesAnsweredWithin7days);
		_p('===================================');
		_p($quesAnsweredByCAWithin2days);
		_p($quesAnsweredByCAWithin7days);
		die;*/
		//outputData end
		$this->CI->load->builder("nationalCourse/CourseBuilder");
		$courseBuilder = new CourseBuilder();
		$courseRepo = $courseBuilder->getCourseRepository();

		$this->CI->load->builder("nationalInstitute/InstituteBuilder");
	    $instituteBuilder = new InstituteBuilder();
	    $instituteRepo = $instituteBuilder->getInstituteRepository();

	    header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=listingAnaMis.csv');
		$output = fopen('php://output', 'w');
		if(in_array($param['crAvailability'], array('courseCR', 'collegeOnlyCR'))){
			$headings = array('Institute ID', 'Course ID', 'Institute Name', 'Course Name', 'Questions Asked', 'Questions Answered within 2 Days', 'Questions Answered within 7 Days', 'Questions Answered by CR within 2 Days', 'Questions Answered by CR within 7 Days');
		}else{
			$headings = array('Institute ID', 'Course ID', 'Institute Name', 'Course Name', 'Questions Asked', 'Questions Answered within 2 Days', 'Questions Answered within 7 Days');
		}
		fputcsv($output, $headings);
		foreach ($quesData as $instId => $instQuesData) {
			foreach ($instQuesData as $courseId => $courseQuesData) {
				$totalQuestions = count($courseQuesData);
				//get course name if $courseId > 0
				if($courseId > 0){
					$courseObj = $courseRepo->find($courseId);
					$instObj = $instituteRepo->find($instId);
					if(in_array($param['crAvailability'], array('courseCR', 'collegeOnlyCR'))){
						$excelRow = array($instObj->getId(), $courseId, $instObj->getName(), $courseObj->getName(), $totalQuestions, $quesAnsweredWithin2days[$instId.'-'.$courseId], $quesAnsweredWithin7days[$instId.'-'.$courseId], $quesAnsweredByCAWithin2days[$instId.'-'.$courseId], $quesAnsweredByCAWithin7days[$instId.'-'.$courseId]);
					}else{
						$excelRow = array($instObj->getId(), $courseId, $instObj->getName(), $courseObj->getName(), $totalQuestions, $quesAnsweredWithin2days[$instId.'-'.$courseId], $quesAnsweredWithin7days[$instId.'-'.$courseId]);
					}
				}
				//else get inst name
				else{
					$instObj = $instituteRepo->find($instId);
					if($instObj->getId()!=''){	//Only if the Institute exists, we will show the questions
						if(in_array($param['crAvailability'], array('courseCR', 'collegeOnlyCR'))){
							$excelRow = array($instObj->getId(), 'N/A', $instObj->getName(), 'N/A', $totalQuestions, $quesAnsweredWithin2days[$instId.'-0'], $quesAnsweredWithin7days[$instId.'-0'], $quesAnsweredByCAWithin2days[$instId.'-0'], $quesAnsweredByCAWithin7days[$instId.'-0']);
						}else{
							$excelRow = array($instObj->getId(), 'N/A', $instObj->getName(), 'N/A', $totalQuestions, $quesAnsweredWithin2days[$instId.'-0'], $quesAnsweredWithin7days[$instId.'-0']);
						}
					}
				}
				fputcsv($output, $excelRow);
			}
		}
		return;
	}
}
