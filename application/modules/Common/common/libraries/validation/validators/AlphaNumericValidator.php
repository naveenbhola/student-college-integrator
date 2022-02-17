<?php

class AlphaNumericValidator extends AbstractValidator
{
    function __construct()
    {
        
    }
    
    public function validate($value,$label)
    {
        $value = trim($value);
        $expr = "/^[A-Za-z0-9 ]+$/";
        
        if (!preg_match($expr, $value)) {
            
            $this->setErrorMsg($label.' can contain only alpha-numeric values.');
            return FALSE;
        }
        
        return TRUE;
    }
}