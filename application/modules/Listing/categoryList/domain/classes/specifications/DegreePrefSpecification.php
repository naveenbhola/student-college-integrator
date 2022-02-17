<?php

class DegreePrefSpecification extends CompositeSpecification
{
    function isSatisfiedBy($course)
    {
        $approvals = (array) $course['filterValues'][CP_FILTER_DEGREEPREF];
        $matchingApprovals = array_intersect($approvals,$this->filterValues);
    
        if(count($matchingApprovals) > 0) { 
            return TRUE;
        }
        
        return FALSE;
    }
}