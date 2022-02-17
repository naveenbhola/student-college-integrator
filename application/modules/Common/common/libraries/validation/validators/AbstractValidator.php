<?php

class AbstractValidator
{
    protected $CI;
    protected $errorMsg;
    
    function __construct()
    {
        $this->CI = & get_instance();
    }
    
    protected function setErrorMsg($errorMsg)
    {
        $this->errorMsg = $errorMsg;
    }
    
    public function getErrorMsg()
    {
        return $this->errorMsg;
    }
}
