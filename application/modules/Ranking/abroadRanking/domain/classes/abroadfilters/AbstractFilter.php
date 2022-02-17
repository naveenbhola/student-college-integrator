<?php

abstract class AbstractFilter
{
    protected $values;
    
    function __construct()
    {
        $this->values = array();    
    }
    abstract function extractValue(University $university,AbroadCourse $course);
    abstract function addValue(University $university,AbroadCourse $course);
    abstract function getFilteredValues();
    
    function __sleep()
    {
        return array('values');
    }
}