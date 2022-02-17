<?php

/**
 * Validator of preferred study locality field
 */ 

namespace registration\libraries\FieldValidators;

/**
 * Validator of preferred study locality field
 */ 
class PreferredStudyLocality extends AbstractValidator
{
	/**
	 * Constrcutor
	 */ 
	function __construct()
	{
		parent::__construct();
	}
	
    /**
	 * Validate the preferred study locality field
	 *
	 * @param object $field \registration\libraries\RegistrationFormField
	 * @param mixed $value Value against which the field is to be validated
	 * @param array $data Full registration data array
	 * @return bool
	 */ 
	public function validate(\registration\libraries\RegistrationFormField $field,$value,$data)
    {
		/**
		 * For preferred study locality, $value must be an array
		 */ 
		if(!is_array($value)) {
			return FALSE;
		}
		
		return TRUE;
    }
}