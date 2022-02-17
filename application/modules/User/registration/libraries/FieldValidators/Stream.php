<?php

/**
 * Validator File for stream field
 */
namespace registration\libraries\FieldValidators;

/**
 * Validator for stream field
 */
class Stream extends AbstractValidator
{
	/**
	 * Constrcutor
	 */
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Validate the stream field
	 *
	 * @param object $field \registration\libraries\RegistrationFormField
	 * @param mixed $value Value against which the field is to be validated
	 * @param array $data Full registration data array
	 * @return bool
	 */
    public function validate(\registration\libraries\RegistrationFormField $field,$value,$data)
    {
    	return true;
		if(is_array($value)) {
			return FALSE;
		}

		$validValues = $field->getValues();

		foreach($validValues as $key => $result) {
			if($result == $value){
				return true;
			}
		}

		return FALSE;
    }
}
