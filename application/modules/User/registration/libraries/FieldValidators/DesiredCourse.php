<?php

/**
 * Validator File for desired course field
 */ 
namespace registration\libraries\FieldValidators;

/**
 * Validator for desired course field
 */ 
class DesiredCourse extends AbstractValidator
{
	/**
	 * Constrcutor
	 */ 
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Validate the desired course field
	 *
	 * @param object $field \registration\libraries\RegistrationFormField
	 * @param mixed $value Value against which the field is to be validated
	 * @param array $data Full registration data array
	 * @return bool
	 */  
    public function validate(\registration\libraries\RegistrationFormField $field,$value,$data)
    {
		if(is_array($value)) {
			return FALSE;
		}
		
		$validValues = array();
		
		if($data['mmpFormId']) {
			$validValues = $field->getValues(array('mmpFormId' => $data['mmpFormId']));
		}
		else if($data['coursePageSubcategoryId']) {
			$validValues = $field->getValues(array('coursePageSubcategoryId' => $data['coursePageSubcategoryId']));
		}
		else if($data['fieldOfInterest']) {
			$validValues = $field->getValues(array('categoryId' => $data['fieldOfInterest']));
		}
		
		foreach($validValues as $group => $courses) {
			if($courses[$value]) {
				return TRUE;
			}
		}
		
		return FALSE;
    }
}