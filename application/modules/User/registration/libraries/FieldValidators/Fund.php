<?php
/**
 * Validator for fund field
 */ 
namespace registration\libraries\FieldValidators;

/**
 * Validator for fund field
 */ 
class Fund extends AbstractValidator
{
	/**
	 * Constrcutor
	 */ 
	function __construct()
	{
		parent::__construct();
	}
	
    /**
	 * Validate the fund field
	 *
	 * @param object $field \registration\libraries\RegistrationFormField
	 * @param mixed $value Value against which the field is to be validated
	 * @param array $data Full registration data array
	 * @return bool
	 */ 
	public function validate(\registration\libraries\RegistrationFormField $field,$value,$data)
    {
		return TRUE;
		/**
		 * For fund, $value must be an array
		 */ 
		if(!is_array($value)) {
			return FALSE;
		}
		
		$validValues = $field->getValues();
		
		/**
		 * Check every value in $value array for validity
		 */
		foreach($value as $fundValue) {
			if(!$validValues[$fundValue]) {
				return FALSE;
			}
		}
		
		return TRUE;
    }
}