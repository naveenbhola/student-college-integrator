<?php

/**
 * Validator File for Sub-stream and Specialization field
 */ 
namespace registration\libraries\FieldValidators;

/**
 * Validator for Sub-stream and Specialization field
 */ 
class SubstreamsSpecializations extends AbstractValidator
{
	/**
	 * Constrcutor
	 */ 
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Validate the Sub-stream and Specialization field
	 *
	 * @param object $field \registration\libraries\RegistrationFormField
	 * @param mixed $value Value against which the field is to be validated
	 * @param array $data Full registration data array
	 * @return bool
	 */  
    public function validate(\registration\libraries\RegistrationFormField $field,$value,$data)
    {
		return;
		$validValues = $field->getValues();		
		
		foreach($validValues as $key => $result) {
			if($result == $value){
				return true;
			}
		}
		
		return FALSE;
    }
}