<?php
/**
 * Validator file for graduation completion year field
 */ 

namespace registration\libraries\FieldValidators;

/**
 * Validator for graduation completion year field
 */ 
class GraduationCompletionYear extends AbstractValidator
{
	/**
	 * Constrcutor
	 */ 
	function __construct()
	{
		parent::__construct();
	}
	
    /**
	 * Validate the graduation completion year field
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
		
		if($data['desiredGraduationLevel'] == 'UG') {
			return TRUE;
		}
		
		$validValues = $field->getValues();
		if(in_array($value,$validValues)) {
			return TRUE;
		}
		else {
			return FALSE;
		}
    }
}