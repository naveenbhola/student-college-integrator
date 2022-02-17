<?php

/**
 * Validator for abroad exam field
 */ 
namespace registration\libraries\FieldValidators;

/**
 * Validator for abroad exam field
 */ 
class Passport extends AbstractValidator
{
	/**
	 * Constrcutor
	 */ 
	function __construct()
	{
		parent::__construct();
	}
	
    /**
	 * Validate the passport field
	 *
	 * @param object $field \registration\libraries\RegistrationFormField
	 * @param mixed $value Value against which the field is to be validated
	 * @param array $data Full registration data array
	 * @return bool
	 */ 
	public function validate(\registration\libraries\RegistrationFormField $field,$value,$data)
    {
		/**
		 * For passport, $value must be yes or no
		 */
		if(!empty($value)) {
			if(!($value == 'yes' || $value == 'no')) {
				return FALSE;
			}
		}
		
		return TRUE;
    }
}