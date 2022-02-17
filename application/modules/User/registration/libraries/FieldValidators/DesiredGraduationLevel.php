<?php

/**
 * Validator File for desired graduation level field
 */ 

namespace registration\libraries\FieldValidators;

/**
 * Validator for desired graduation level field
 */ 
class DesiredGraduationLevel extends AbstractValidator
{
	/**
	 * Constrcutor
	 */ 
	function __construct()
	{
		parent::__construct();
	}
	
    /**
	 * Validate the desired graduation level field
	 *
	 * @param object $field \registration\libraries\RegistrationFormField
	 * @param mixed $value Value against which the field is to be validated
	 * @param array $data Full registration data array
	 * @return bool
	 */ 
	public function validate(\registration\libraries\RegistrationFormField $field,$value,$data)
    {
                if(!isset($value)) return true;
		if(is_array($value)) {
			return FALSE;
		}
		
		$validValues = $field->getValues();
                if(STUDY_ABROAD_NEW_REGISTRATION){
                    foreach ($validValues as $key => $validValue) {
                        if($validValue['CourseName'] == $value) return true;
                    }
                    return false;
                }
                
		if(in_array($value,$validValues)) {
			return TRUE;
		}
		else {
			return FALSE;
		}
    }
}