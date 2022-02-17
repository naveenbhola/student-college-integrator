<?php
/**
 * Validator for mode field
 */ 
namespace registration\libraries\FieldValidators;

/**
 * Validator for mode field
 */ 
class Mode extends AbstractValidator
{
	/**
	 * Constrcutor
	 */ 
	function __construct()
	{
		parent::__construct();
	}
	
    /**
	 * Validate the mode field
	 *
	 * @param object $field \registration\libraries\RegistrationFormField
	 * @param mixed $value Value against which the field is to be validated
	 * @param array $data Full registration data array
	 * @return bool
	 */ 
	public function validate(\registration\libraries\RegistrationFormField $field,$value,$data)
    {
		/**
		 * For mode, $value must be an array
		 */ 
		if(!is_array($value)) {
			return FALSE;
		}
		
		$validValues = $field->getValues();
	
		/**
		 * Validate every value in $value array
		 */
		foreach($value as $modeValue) {
			if(!$validValues[$modeValue]) {
				return FALSE;
			}	
		}
	
		return TRUE;
    }
}