<?php

class LengthValidator extends AbstractValidator
{
    private $_minLength;
    private $_maxLength;
    
    function __construct($params = array())
    {
        $this->_minLength = $params[0];
        $this->_maxLength = $params[1];
    }
    
    public function validate($value,$label)
    {
        $value = trim($value);
    
        if(strlen($value) < $this->_minLength) {
            
            $this->setErrorMsg($label.' value must have at least '.$this->_minLength.' characters');
            return FALSE;
        }
        else if(strlen($value) > $this->_maxLength) {
            
            $this->setErrorMsg($label.' value can not have more than '.$this->_maxLength.' characters');
            return FALSE;
        }
        
        return TRUE;
    }
}