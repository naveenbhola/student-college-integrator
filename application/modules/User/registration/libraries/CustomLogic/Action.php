<?php
/**
 * File representing an action in custom rules applied on form fields
 */ 
namespace registration\libraries\CustomLogic;

/**
 * Class representing an action in custom rules applied on form fields
 */ 
class Action
{
    /**
     * @var object \registration\libraries\RegistrationFormField Field on which action is to be applied
     */ 
    private $field;
    
    /**
     * @var string Attribute of the action e.g. visibility
     */ 
    private $attribute;
    
    /**
     * @var value Value of the attribute of the action e.g. for attribute visibility, value can be Yes|No
     */ 
    private $value;
    
    /**
     * Constrcutor
     *
     * @param object $field
     * @param string $attribute
     * @param string $value
     */ 
    function __construct(\registration\libraries\RegistrationFormField $field = NULL,$attribute = NULL,$value = NULL)
    {
        $this->field = $field;
        $this->attribute = $attribute;
        $this->value = $value;
    }
    
    /**
     * Get field of the action
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
     * Get attribute of the action
     *
     * @return string
     */
    public function getAttribute()
    {
        return $this->attribute;
    }
    
    /**
     * Set attribute of the action
     *
     * @param string $attribute
     */
    public function setAttribute($attribute)
    {
        $this->attribute = $attribute;
    }
    
    /**
     * Get value of the attribute of the action
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
    
    /**
     * Set value of the attribute of the action
     *
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}