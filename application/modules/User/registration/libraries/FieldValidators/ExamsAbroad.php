<?php

/**
 * Validator File for abroad exam field
 */ 

namespace registration\libraries\FieldValidators;

/**
 * Validator for abroad exam field
 */ 
class ExamsAbroad extends AbstractValidator
{
	/**
	 * Constrcutor
	 */ 
	function __construct()
	{
		parent::__construct();
	}
	
    /**
	 * Validate the exam abroad field
	 *
	 * @param object $field \registration\libraries\RegistrationFormField
	 * @param mixed $value Value against which the field is to be validated
	 * @param array $data Full registration data array
	 * @return bool
	 */ 
	public function validate(\registration\libraries\RegistrationFormField $field,$value,$data)
    {
		/**
		 * For exam abroad, $value must be an array
		 */
		if(!empty($value)) {
			if(!is_array($value)) {
				return FALSE;
			}
		}
		
		return TRUE;
    }
}