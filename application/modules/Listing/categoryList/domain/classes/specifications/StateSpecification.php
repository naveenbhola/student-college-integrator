<?php

class StateSpecification extends CompositeSpecification
{
    function isSatisfiedBy($course)
    {
        $states = (array) $course['filterValues'][CP_FILTER_STATE];
        $matchingStates = array_intersect($states,$this->filterValues);        
        if(count($matchingStates) > 0) { 
            return TRUE;
        }
    
        return FALSE;
    }
}