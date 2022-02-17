<?php


/**
 * Validator File for contact by consultant field
 */ 
namespace registration\libraries\FieldValidators;

/**
 * Validator for contact by consultant field
 */ 
class contactByConsultant extends AbstractValidator
{
	/**
	 * Constrcutor
	 */ 
	function __construct()
	{
		parent::__construct();
	}
	
    /**
	 * Validate the contactByConsultant field
	 *
	 * @param object $field \registration\libraries\RegistrationFormField
	 * @param mixed $value Value against which the field is to be validated
	 * @param array $data Full registration data array
	 * @return bool
	 */ 
	public function validate(\registration\libraries\RegistrationFormField $field,$value,$data)
        {
		/**
		 * For contactByConsultant, $value must be yes or no
		 */
		if(!empty($value)) {
			if(!($value == 'yes' || $value == 'no')) {
				return FALSE;
			}
		}
		
		return TRUE;
        }
}