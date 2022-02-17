<?php

class AIMARatingFilter extends AbstractFilter
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
        return $institute->getAIMARating();
    }
    
    public function addValue(Institute $institute,Course $course)
    {
        if($AIMARating = $this->extractValue($institute,$course)) {
            $this->values[$AIMARating] = $AIMARating;
        }
    }
}
