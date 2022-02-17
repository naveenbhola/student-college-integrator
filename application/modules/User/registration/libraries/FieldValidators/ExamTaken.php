<?php

/**
 * Validator File for abroad exam field
 */ 

namespace registration\libraries\FieldValidators;

/**
 * Validator for abroad exam field
 */ 
class ExamTaken extends AbstractValidator
{
	/**
	 * Constrcutor
	 */ 
	function __construct()
	{
		parent::__construct();
	}
	
    /**
	 * Validate the exam taken field
	 *
	 * @param object $field \registration\libraries\RegistrationFormField
	 * @param mixed $value Value against which the field is to be validated
	 * @param array $data Full registration data array
	 * @return bool
	 */ 
	public function validate(\registration\libraries\RegistrationFormField $field,$value,$data)
    {
		/**
		 * For exam taken, $value must be yes or no
		 */
		//if(!($value == 'yes' || $value == 'no' || empty($value))) {
		if(!(in_array($value ,$field->getValues()) || empty($value))) {
			return FALSE;
		}
		
		return TRUE;
    }
}