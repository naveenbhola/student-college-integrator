<?php
/**
 * File for Value source for exam field
 */ 
namespace registration\libraries\FieldValueSources;

/**
 * Value source for exam field
 */ 
class Exams extends AbstractValueSource
{
    /**
	 * Get values
	 *
	 * @param array $params Additional parameters
	 * @return array
	 */
    
    public function getAllValue($params = array()){
	    
		require APPPATH.'modules/User/registration/config/examConfig.php';		
		/**
		 * National exams
		 */ 
		$desiredCourseId = $this->getSelectedDesiredCourse($params);
		if($courseExamMapping[$desiredCourseId]) {
			$examValues = array();
			foreach($courseExamMapping[$desiredCourseId] as $key => $exams) {
				foreach($exams as $exam) {
						$examValues[$key][$exam] = \registration\builders\RegistrationBuilder::getCompetitiveExam($exam);
				}
			}
			return $examValues;
		}		
    }

	    /**
	     * Function to get the Exams list
	     *
	     * @param array $params
	     *
	     */
    public function getValues($params = array())
    {
    	if($params['national'] == 'yes') {
    		return $this->getExamValues($params);
    	} else {
			require APPPATH.'modules/User/registration/config/examConfig.php';		
			/**
			 * National exams
			 */ 
			
			$desiredCourseId = $this->getSelectedDesiredCourse($params);
			$filter_exam_model = $this->CI->load->model('enterprise/filterexammodel');
		        $live_exams = $filter_exam_model->getDesiredLiveExams($desiredCourseId);	
			//$desiredCourseId = 2;
			if($courseExamMapping[$desiredCourseId]) {
				$examValues = array();
				foreach($courseExamMapping[$desiredCourseId] as $key => $exams) {
					foreach($exams as $exam) {
					       if(in_array($exam,$live_exams)) {
							$examValues[$key][$exam] = \registration\builders\RegistrationBuilder::getCompetitiveExam($exam);
						}
					}
				}
				return $examValues;
			}		
		}
    }

    /**
     * Function to get the Exams list using Exam API
     *
     * @param array $params
     *
     */
    public function getExamValues($params = array()) {

    	$this->CI->load->library("examPages/ExamMainLib");
    	$examMainLib = new \ExamMainLib();

    	$baseEntityArr = $params['baseEntityArr'];
    	$baseCourses = $params['courseIds'];
		$data = $examMainLib->getAllMainExamsByAllCombinations($baseEntityArr, $baseCourses);
		
		return $data;

	}
}