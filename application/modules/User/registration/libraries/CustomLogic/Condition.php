<?php
/**
 * File representing a condition in custom rules applied on form fields
 */ 
namespace registration\libraries\CustomLogic;

/**
 * Class representing a condition in custom rules applied on form fields
 */ 
class Condition
{
    /**
     * @var object \registration\libraries\RegistrationFormField Field on which condition is to be applied
     */ 
    private $field;
    
    /**
     * @var string value of the field (to evaluate the condition)
     */ 
    private $value;
    
    /**
     * Constrcutor
     *
     * @param object $field
     * @param string $value
     */ 
    function __construct(\registration\libraries\RegistrationFormField $field = NULL,$value = NULL)
    {
        $this->field = $field;
        $this->value = $value;
    }
    
    /**
     * Get field of the condition
     *
     * @return object \registration\libraries\RegistrationFormField
     */ 
    public function getField()
    {
        return $this->field;
    }
    
    /**
     * Set field of the action
     *
     * @param object $field \registration\libraries\RegistrationFormField
     */ 
    public function setField(\registration\libraries\RegistrationFormField $field)
    {
        $this->field = $field;
    }
    
    /**
     * Get value of the field
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
    
    /**
     * Set value of the field
     *
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}