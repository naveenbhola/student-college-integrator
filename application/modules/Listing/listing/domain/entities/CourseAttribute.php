<?php

class CourseAttribute
{
    private $attribute;
    private $value;
    
    function __construct()
    {
        
    }
    
    public function getName()
    {
        return $this->attribute;
    }
    
    function getValue()
    {
        return $this->value;
    }
    
        
    function __set($property,$value)
    {
        $this->$property = $value;
    }
}