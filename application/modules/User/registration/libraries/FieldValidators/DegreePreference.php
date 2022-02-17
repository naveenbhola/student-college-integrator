<?php

/**
 * Validator File for degree preference field
 */ 
namespace registration\libraries\FieldValidators;

/**
 * Validator for degree preference field
 */ 
class DegreePreference extends AbstractValidator
{
	/**
	 * Constrcutor
	 */ 
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Validate the degree preference field
	 *
	 * @param object $field \registration\libraries\RegistrationFormField
	 * @param mixed $value Value against which the field is to be validated
	 * @param array $data Full registration data array
	 * @return bool
	 */  
    public function validate(\registration\libraries\RegistrationFormField $field,$value,$data)
    {
		/**
		 * For degree preference, $value must be an array
		 */ 
		if(!is_array($value)) {
			return FALSE;
		}
		
		$validValues = $field->getValues();
		
		/**
		 * Check every value in the $value array for validity
		 */ 
		foreach($value as $degreePrefValue) {
			if(!$validValues[$degreePrefValue]) {
				return FALSE;
			}	
		}
		
		return TRUE;
    }
}