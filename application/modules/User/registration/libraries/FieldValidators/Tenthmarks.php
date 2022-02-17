<?php

/**
 * Validator File for Tenth marks field
 */ 
namespace registration\libraries\FieldValidators;

/**
 * Validator for Tenth marks field
 */ 
class Tenthmarks extends AbstractValidator
{
	/**
	 * Constrcutor
	 */ 
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Validate the Tenth marks field
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
		$validValues = $field->getValues('tenthmarks');
		
		foreach($validValues[$data['tenthBoard']] as $key=>$val ){
			if($key == $value){
				return true;
			}
		}
		
		return FALSE;
    }
}