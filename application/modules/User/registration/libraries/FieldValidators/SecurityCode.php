<?php
/**
 * File for security code field validation
 */ 

namespace registration\libraries\FieldValidators;

/**
 * Validator for security code field
 */ 
class SecurityCode extends AbstractValidator
{
	/**
	 * Constrcutor
	 */ 
	function __construct()
	{
		parent::__construct();
	}
	
    /**
	 * Validate the security code field
	 *
	 * @param object $field \registration\libraries\RegistrationFormField
	 * @param mixed $value Value against which the field is to be validated
	 * @param array $data Full registration data array
	 * @return bool
	 */ 
	public function validate(\registration\libraries\RegistrationFormField $field,$value,$data)
    {
		if(isCaptchaFreeReferer($data['pageReferer']) || $_REQUEST['shkNoCaptchafrAutomation'] == '1') {
			return TRUE;
		}
		
		if(!$value || is_array($value)) {
			return FALSE;
		}
		
		$securityCodeVar = $data['securityCodeVar'];
        
		
        if(verifyCaptcha($securityCodeVar,$value,1)) {
            return TRUE;
        }
        else {
            return FALSE;
        }
    }
}
