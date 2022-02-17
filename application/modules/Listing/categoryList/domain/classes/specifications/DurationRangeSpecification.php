<?php

class DurationRangeSpecification extends CompositeSpecification
{
    function isSatisfiedBy($course)
    {
        $duration = $course['filterValues'][CP_FILTER_DURATION];
        if(isset($this->filterValues[$duration])) { 
            return TRUE;
        }
        
        return FALSE;
    }
    
    /*
     * Override setFilterValues from parent class
     */ 
    public function setFilterValues($filterValues)
    {
        $durationType = $filterValues['type'];
        $durationTypeSingular = substr($durationType,0,-1);
        
        $range = $filterValues['range'];
        $rangeValues = explode('-',$range);
        
        $durationValues = array();
        foreach($rangeValues as $rangeValue) {
            $durationValue = $rangeValue.' '.($rangeValue == 1 ? $durationTypeSingular : $durationType);
            $durationValues[$durationValue] = $durationValue;
        }
        
        $this->filterValues = $durationValues; 
    }
}