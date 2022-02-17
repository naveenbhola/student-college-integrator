<?php

class ModeFilter extends AbstractFilter
{
    function __construct()
    {
        parent::__construct();
    }
    
    public function getFilteredValues()
    {
        return $this->values;
    }
    
    public function extractValue(Institute $institute,Course $course)
    {
        return $course->getCourseType();
    }
    
    public function addValue(Institute $institute,Course $course)
    {
        if($mode = $this->extractValue($institute,$course)) {
            $this->values[$mode] = $mode;
        }
    }
        
    function setFilterValues($filterVaules = array()) {
	foreach($filterVaules as $value => $occurenceCount)
        {
            $this->values[$value] = $value;
        }
    }
}
