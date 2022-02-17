<?php

/**
 * Validator File for Current Class field
 */ 
namespace registration\libraries\FieldValidators;

/**
 * Validator for Current Class field
 */ 
class CurrentClass extends AbstractValidator
{
	/**
	 * Constrcutor
	 */ 
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Validate the Current Class field
	 *
	 * @param object $field \registration\libraries\RegistrationFormField
	 * @param mixed $value Value against which the field is to be validated
	 * @param array $data Full registration data array
	 * @return bool
	 */  
    public function validate(\registration\libraries\RegistrationFormField $field,$value,$data)
    {
		
		$validValues = array();
		$validValues = $field->getValues('currentClass');
		if(!array_key_exists($value, $validValues)){
			return false;
		}
		
		return true;
    }
}