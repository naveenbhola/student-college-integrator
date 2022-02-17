<?php

abstract class AbstractFilter
{
    protected $values;
    
    function __construct()
    {
        $this->values = array();    
    }
   
    abstract function extractValue(Institute $institute,Course $course);
    abstract function addValue(Institute $institute,Course $course);
    abstract function getFilteredValues();
    
    function __sleep()
    {
        return array('values');
    }
    
    function setFilterValues($filterVaules = array()) {
	foreach($filterVaules as $value => $occurenceCount)
    	$this->values[$value] = $value;
    }
}