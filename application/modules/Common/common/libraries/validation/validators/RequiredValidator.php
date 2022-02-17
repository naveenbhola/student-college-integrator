<?php

class RequiredValidator extends AbstractValidator
{
    function __construct()
    {
        
    }
    
    public function validate($value,$label)
    {
        $value = trim($value);
        if(!$value) {
            $this->setErrorMsg($label.' field is required');
            return FALSE;
        }
        
        return TRUE;
    }
}