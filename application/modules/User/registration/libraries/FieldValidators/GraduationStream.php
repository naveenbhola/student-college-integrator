<?php

/**
 * Validator File for Graduation Stream field
 */ 
namespace registration\libraries\FieldValidators;

/**
 * Validator for Graduation Stream field
 */ 
class GraduationStream extends AbstractValidator
{
	/**
	 * Constrcutor
	 */ 
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Validate the Graduation Stream field
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
		
		$validValues = array('Engineering', 'Science', 'Business', 'Humanities', 'Social Science', 'Commerce');
		if(in_array($value, $validValues)){
			return true;
		}
		
		return FALSE;
    }
}