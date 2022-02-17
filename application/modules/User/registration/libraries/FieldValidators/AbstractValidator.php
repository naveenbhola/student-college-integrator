<?php
/**
 * Abstract class File for field validator
 */ 

namespace registration\libraries\FieldValidators;

/**
 * Abstract class for field validator
 */ 
abstract class AbstractValidator
{
    /**
     * @var object CodeIgniter object
     */ 
    protected $CI;
    
    /**
     * Constructor
     */ 
    function __construct()
    {
        $this->CI = & get_instance();
    }
    
    /**
	 * Validate a field
	 *
	 * @param object $field \registration\libraries\RegistrationFormField
	 * @param mixed $value Value against which the field is to be validated
	 * @param array $data Full registration data array
	 * @return bool
	 */ 
    abstract function validate(\registration\libraries\RegistrationFormField $field,$value,$data);
}