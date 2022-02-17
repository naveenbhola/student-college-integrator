<?php

/**
 * Validator File for desired course field
 */ 
namespace registration\libraries\FieldValidators;

/**
 * Validator for desired course field
 */ 
class Budget extends AbstractValidator
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
		
                $validValues = $field->getValues();
		
		
                if(isset($validValues[$value])) {
                        return TRUE;
                }
		
		return FALSE;
    }
}