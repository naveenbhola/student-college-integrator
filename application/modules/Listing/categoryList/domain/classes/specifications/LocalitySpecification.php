<?php

class LocalitySpecification extends CompositeSpecification
{
    function isSatisfiedBy($course)
    {
		
		$localities = (array) $course['filterValues'][CP_FILTER_LOCALITY];
        $matchingLocalities = array_intersect($localities,$this->filterValues);
        
        if(count($matchingLocalities) > 0) { 
            return TRUE;
        }
    
        return FALSE;
    }
}