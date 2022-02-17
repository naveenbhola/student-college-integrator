<?php

/**
 * Validator File for course field
 */ 
namespace registration\libraries\FieldValidators;

/**
 * Validator for course field
 */ 
class BaseCourses extends AbstractValidator
{
	/**
	 * Constrcutor
	 */ 
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Validate the course field
	 *
	 * @param object $field \registration\libraries\RegistrationFormField
	 * @param mixed $value Value against which the field is to be validated
	 * @param array $data Full registration data array
	 * @return bool
	 */  
    public function validate(\registration\libraries\RegistrationFormField $field,$value,$data)
    {
			
		if(empty($value) || !is_array($value)){
			return FALSE;
		}

		return TRUE;
    }
}
