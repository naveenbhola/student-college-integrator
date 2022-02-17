<?php 
require_once dirname(__FILE__).'/../DataAbstract.php';
class CourseExamData extends DataAbstract {
	

	public function __construct()
	{
		$this->_CI = & get_instance();
	}


	public function _getDataFromObject($courseObject)
	{	
		$courseExamData = array();
		$eligibilityExamsFromObject = $courseObject->getAllEligibilityExams();
		
		foreach ($eligibilityExamsFromObject as $examId=>$examName) {
				$courseExamData[$examId] = $examName;	
		}
		// _p($courseExamData);die;
		return $courseExamData;
	}

	public function _processData($courseExamData){

		$modifiedData = array();
		foreach ($courseExamData as $exam_id => $exam_name) {
			if(empty($exam_name)) continue;
			$modifiedData['nl_course_exam_id'][] = $exam_id;
			$modifiedData['nl_course_exam_name_id_map'][] = $exam_name.":".$exam_id;
			$modifiedData['nl_course_exam_name'][] = $exam_name;
		}
		
		// This CODE is IMPORTANT => in case of Partial Indexing, when the SECTION DATA GETS DELETED, 
		if(empty($modifiedData)){
				$modifiedData['nl_course_exam_id'] = null;
				$modifiedData['nl_course_exam_name_id_map'] = null;
				$modifiedData['nl_course_exam_name'] = null;
			}
		return $modifiedData;
	}


}

?>
