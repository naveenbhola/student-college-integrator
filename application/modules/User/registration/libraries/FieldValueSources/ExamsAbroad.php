<?php
/**
 * File for Value source for exam field
 */ 
namespace registration\libraries\FieldValueSources;

/**
 * Value source for exam field
 */ 
class ExamsAbroad extends AbstractValueSource
{
    /**
	 * Get values
	 *
	 * @param array $params Additional parameters
	 * @return array
	 */ 
    public function getValues($params = array())
    {
	    STUDY_ABROAD_NEW_REGISTRATION;
	    
	    if(STUDY_ABROAD_NEW_REGISTRATION) {
			$studyAbroadExams = array();
			$abroadCommonLib = $this->CI->load->library('listingPosting/AbroadCommonLib');
			$examsAbroad = $abroadCommonLib->getAbroadExamsMasterList('', 0, true);

			foreach($examsAbroad as $exam) {
				    if($exam['status'] == 'live') {
						if(stripos($exam['exam'], ' ') === false) {
							    $studyAbroadExams[$exam['examId']]['name'] = $exam['exam'];
						}
						else {
							    $studyAbroadExams[$exam['examId']]['name'] =  substr($exam['exam'], 0, stripos($exam['exam'], ' '));
						}
						$studyAbroadExams[$exam['examId']]['minScore'] = $exam['minScore'];
						$studyAbroadExams[$exam['examId']]['maxScore'] = $exam['maxScore'];
						$studyAbroadExams[$exam['examId']]['scoreType'] = strtolower($exam['type']);
						$studyAbroadExams[$exam['examId']]['range'] = $exam['range'];
				    }
			}
			
			return $studyAbroadExams;
	    }
	    else {
			return array(
				    'TOEFL' => 'TOEFL',
				    'IELTS' => 'IELTS',
				    'SAT' => 'SAT',
				    'GMAT' => 'GMAT',
				    'GRE' => 'GRE'
				    );
	    }	
    }
}