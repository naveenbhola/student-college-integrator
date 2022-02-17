<?php

/**
 * Validator File for Sub-stream and Specialization field
 */ 
namespace registration\libraries\FieldValidators;

/**
 * Validator for Sub-stream and Specialization field
 */ 
class SubStreamSpecializations extends AbstractValidator
{
	/**
	 * Constrcutor
	 */ 
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Validate the Sub-stream and Specialization field
	 *
	 * @param object $field \registration\libraries\RegistrationFormField
	 * @param mixed $value Value against which the field is to be validated
	 * @param array $data Full registration data array
	 * @return bool
	 */  
    public function validate(\registration\libraries\RegistrationFormField $field,$value,$data)
    {

    	$stream = $data['stream'];
		// $validValues = $field->getValues(array('streamIds'=> array($stream)));		
		
		// $specializations = array_keys($validValues[$stream]['specializations']);
		// $substreams = array_keys($validValues[$stream]['substreams']);

		if(!empty($data['subStream']) && !is_array($data['subStream'])){
			return FALSE;
		}
		
		if(empty($data['specializations']) || !is_array($data['specializations'])){
			return FALSE;
		}

		return TRUE;
    }
}