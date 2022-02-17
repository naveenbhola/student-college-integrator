<?php
/**
 * Validator for when plan to start field
 */ 
namespace registration\libraries\FieldValidators;

/**
 * Validator for when plan to start field
 */ 
class WhenPlanToStart extends AbstractValidator
{
	/**
	 * Constrcutor
	 */ 
	function __construct()
	{
		parent::__construct();
	}
	
    /**
	 * Validate the when plan to start field
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
		
		$validValues = $field->getValues();
		if($validValues[$value]) {
			return TRUE;
		}
		else {
			return FALSE;
		}
    }
}