<?php

class DegreePrefFilter extends AbstractFilter
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
        return $course->getApprovals();
    }
    
    public function addValue(Institute $institute,Course $course)
    {
        $approvals = $this->extractValue($institute,$course);
        foreach($approvals as $approval) {
            $this->values[$approval] = $approval;
        }
    }
    
    function setFilterValues($filterVaules = array()) {
            foreach($filterVaules as $value => $occurenceCount)
            {
                        $this->values[$value] = $value;
            }
    }   
}
