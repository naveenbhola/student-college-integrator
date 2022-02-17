<?php

/**
 * Validator File for Xth Board field
 */ 
namespace registration\libraries\FieldValidators;

/**
 * Validator for Xth Board field
 */ 
class TenthBoard extends AbstractValidator
{
	/**
	 * Constrcutor
	 */ 
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Validate the Xth Board field
	 *
	 * @param object $field \registration\libraries\RegistrationFormField
	 * @param mixed $value Value against which the field is to be validated
	 * @param array $data Full registration data array
	 * @return bool
	 */  
    public function validate(\registration\libraries\RegistrationFormField $field,$value,$data)
    {
    	$allowed = array('CBSE', 'ICSE', 'IGCSE','IBMYP','NIOS');
		
		if(in_array($value, $allowed)){
			return true;
		}

		return FALSE;
    }
}