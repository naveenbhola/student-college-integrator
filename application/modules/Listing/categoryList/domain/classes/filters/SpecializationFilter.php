<?php

class SpecializationFilter extends AbstractFilter
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
        return "";
    }
    
    public function addValue(Institute $institute,Course $course)
    {
        if(($courseLevel = $this->extractValue($institute,$course))) {
            $this->values[$courseLevel] = $courseLevel;
        }
    }
}
