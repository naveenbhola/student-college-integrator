<?php

/**
 * Validator for field of interest field
 */ 

namespace registration\libraries\FieldValidators;

/**
 * Validator for field of interest field
 */ 
class FieldOfInterest extends AbstractValidator
{
	/**
	 * Constrcutor
	 */ 
	function __construct()
	{
		parent::__construct();
		$CI = & get_instance();
	}
	
    /**
	 * Validate the field of interest field
	 *
	 * @param object $field \registration\libraries\RegistrationFormField
	 * @param mixed $value Value against which the field is to be validated
	 * @param array $data Full registration data array
	 * @return bool
	 */ 
	public function validate(\registration\libraries\RegistrationFormField $field,$value,$data)
	{
		if(!isset($value) || empty($value)) return true;
            
		if(is_array($value)) {
			return FALSE;
		}
		
		if($data['mmpFormId']) {
			if($data['isStudyAbroad'] == "yes" && STUDY_ABROAD_NEW_REGISTRATION) {
				$validValues = $field->getValues(array('twoStep'=>1,'registerationDomain'=>'studyAbroad'));
			}
			else {
				$validValues = $field->getValues(array('mmpFormId' => $data['mmpFormId']));
			}
		}
                else if($data['isStudyAbroad'] == "yes" && STUDY_ABROAD_NEW_REGISTRATION){
                    $validValues = $field->getValues(array('twoStep'=>1,'registerationDomain'=>'studyAbroad'));
                }
		else {
			$validValues = $field->getValues();
		}
                
				// To bypass popular courses with 
				$abroadCommonLib = $this->CI->load->library('listingPosting/AbroadCommonLib');
                $abroadDesiredCourses = $abroadCommonLib->getAbroadMainLDBCourses();
                //global $studyAbroadPopularCourses;
                foreach ($abroadDesiredCourses as $abroadDesiredCourse) {
                    $validValues[$abroadDesiredCourse['SpecializationId']] = true;
                }
		
		if($validValues[$value]) {
			return TRUE;
		}
		else {
			return FALSE;
		}
    }
}
