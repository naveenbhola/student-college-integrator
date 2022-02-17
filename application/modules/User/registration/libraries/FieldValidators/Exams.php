<?php

/**
 * Validator File for exam field
 */

namespace registration\libraries\FieldValidators;

/**
 * Validator for exam field
 */ 
class Exams extends AbstractValidator
{
	/**
	 * Constrcutor
	 */ 
	function __construct()
	{
		parent::__construct();
	}
	
    /**
	 * Validate the exams field
	 *
	 * @param object $field \registration\libraries\RegistrationFormField
	 * @param mixed $value Value against which the field is to be validated
	 * @param array $data Full registration data array
	 * @return bool
	 */ 
	public function validate(\registration\libraries\RegistrationFormField $field,$value,$data)
    {
		/**
		 * For exams, $value must be an array
		 */ 
		if(!is_array($value)) {
			return FALSE;
		}
		
		//$validValues = $field->getValues();
		//
		///**
		// * Check every value in $value array for validity
		// */
		//foreach($value as $examValue) {
		//	if(!in_array($examValue,$validValues)) {
		//		return FALSE;
		//	}
		//}
		
		return TRUE;
    }
}