<?php
/**
 * File for Value source for exam field
 */ 
namespace registration\libraries\FieldValueSources;

/**
 * Value source for exam field
 */ 
class abroadDesiredCourse extends AbstractValueSource
{
    /**
	 * Get values
	 *
	 * @param array $params Additional parameters
	 * @return array
	 */ 
    public function getValues($params = array())
    {
        global $studyAbroadPopularCourses;
        
        $abroadCommonLib = $this->CI->load->library('listingPosting/AbroadCommonLib');
        $examsAbroad = $abroadCommonLib->getAbroadMainLDBCourses();
        //error_log('####'.print_r($examsAbroad, true));
        foreach ($examsAbroad as $key => $value) {
            foreach ($studyAbroadPopularCourses as $key1 => $value1) {
                if($value['SpecializationId'] == $key1){
                    $examsAbroad[$key]['label'] = $value1;
                }
            }
        }

        if($mmpFormId = $params['mmpFormId']) {

		$this->CI->load->model('customizedmmp/customizemmp_model');
		$saved_course_ids = $this->CI->customizemmp_model->getSavedPopularCourseForAbroad($mmpFormId);
		$final_saved_ids = array();

		foreach($saved_course_ids as $row) {
			$final_saved_ids[] = $row['course_id'];
		}

		$copy_exams_aborad = $examsAbroad;

		foreach($examsAbroad as $key=>$val) {
			if(!in_array($val['SpecializationId'],$final_saved_ids)) {
				unset($examsAbroad[$key]);
			}
		}		

		if(count($examsAbroad) == 0 ) {
			//$examsAbroad = $copy_exams_aborad;
		}
	}	

        return $examsAbroad;
    }
    
}
