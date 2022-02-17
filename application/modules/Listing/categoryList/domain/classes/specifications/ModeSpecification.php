<?php

class ModeSpecification extends CompositeSpecification
{
    function isSatisfiedBy($course)
    {
        $mode = $course['filterValues'][CP_FILTER_MODE];
        if(isset($this->filterValues[$mode])) {
            return TRUE;
        }
        
        return FALSE;
    }
}