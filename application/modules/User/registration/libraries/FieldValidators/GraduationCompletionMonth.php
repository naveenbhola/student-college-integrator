<?php
/**
 * Validator for graduation completion month field
 */ 
namespace registration\libraries\FieldValidators;

/**
 * Validator for graduation completion month field
 */ 
class GraduationCompletionMonth extends AbstractValidator
{
	/**
	 * Constrcutor
	 */ 
	function __construct()
	{
		parent::__construct();
	}
	
    /**
	 * Validate the graduation completion month field
	 *
	 * @param object $field \registration\libraries\RegistrationFormField
	 * @param mixed $value Value against which the field is to be validated
	 * @param array $data Full registration data array
	 * @return bool
	 */ 
	public function validate(\registration\libraries\RegistrationFormField $field,$value,$data)
    {
		return TRUE;
		if(is_array($value)) {
			return FALSE;
		}
		
		if($data['desiredGraduationLevel'] == 'UG') {
			return TRUE;
		}
		
		$validValues = $field->getValues();
		if($validValues[$value]) {
			return TRUE;
		}
		else {
			return FALSE;
		}
    }
}
