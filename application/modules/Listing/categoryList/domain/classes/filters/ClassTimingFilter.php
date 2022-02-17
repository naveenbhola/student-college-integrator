<?php

class ClassTimingFilter extends AbstractFilter
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
        return $course->getClassTimings();
    }
    
    public function addValue(Institute $institute,Course $course)
    {        
        $classTimings = $this->extractValue($institute,$course);
        foreach($classTimings as $classTiming) {
            $this->values[$classTiming] = $classTiming;
        }
    }
}
