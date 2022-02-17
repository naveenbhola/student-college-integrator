<?php

class ClassTimingSpecification extends CompositeSpecification
{
    function isSatisfiedBy($course)
    {
        $classTimings = (array) $course['filterValues'][CP_FILTER_CLASSTIMINGS];
        $matchingTimings = array_intersect($classTimings,$this->filterValues);
    
        if(count($matchingTimings) > 0) { 
            return TRUE;
        }
        
        return FALSE;
    }
}