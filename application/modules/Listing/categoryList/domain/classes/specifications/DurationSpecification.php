<?php

class DurationSpecification extends CompositeSpecification
{
    function isSatisfiedBy($course)
    {
        $duration = $course['filterValues'][CP_FILTER_DURATION];
        if(isset($this->filterValues[$duration])) { 
            return TRUE;
        }
        
        return FALSE;
    }
}