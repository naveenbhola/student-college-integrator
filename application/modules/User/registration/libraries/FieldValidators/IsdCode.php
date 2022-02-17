<?php

/**
 * Validator File for isdCode field
 */

namespace registration\libraries\FieldValidators;

/**
 * Validator for isdCode field
 */ 
class IsdCode extends AbstractValidator
{
	/**
	 * Constrcutor
	 */ 
	function __construct()
	{
		parent::__construct();
	}
	
    /**
	 * Validate the isdCodes field
	 *
	 * @param object $field \registration\libraries\RegistrationFormField
	 * @param mixed $value Value against which the field is to be validated
	 * @param array $data Full registration data array
	 * @return bool
	 */ 
	public function validate(\registration\libraries\RegistrationFormField $field,$value,$data)
    {
		/**
		 * For isdCodes, $value must be an array
		 */ 
		
		if(empty($value)){
			return FALSE;
		}

		require APPPATH.'modules/User/registration/config/ISDCodeConfig.php';
		$ISDData = explode('-', $value);
		$isdCode = $ISDData[0];
		$countryId = $ISDData[1];

		if($isdCode == $shikshaCountryToISDCodeMapping[$countryId]['ISD']){
			return TRUE;
		}else{
			return FALSE;
		}

    }
}