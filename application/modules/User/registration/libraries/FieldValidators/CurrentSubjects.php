<?php

/**
 * Validator File for Current Stream field
 */ 
namespace registration\libraries\FieldValidators;

/**
 * Validator for Current Stream field
 */ 
class CurrentSubjects extends AbstractValidator
{
	/**
	 * Constrcutor
	 */ 
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Validate the Current Stream field
	 *
	 * @param object $field \registration\libraries\RegistrationFormField
	 * @param mixed $value Value against which the field is to be validated
	 * @param array $data Full registration data array
	 * @return bool
	 */  
    public function validate(\registration\libraries\RegistrationFormField $field,$value,$data)
    {
		if(!is_array($value) || empty($value)) {
			return FALSE;
		}
		
		$validValues = array();
		
		$validValues = $field->getValues('currentSubjects');

		foreach($value as $key=>$val){
			if(!in_array($val, $validValues)){
				return false;
			}
		}
		
		return true;
    }
}