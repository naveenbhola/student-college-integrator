<?php

class AIMARatingSpecification extends CompositeSpecification
{
    function isSatisfiedBy($course)
    {
        $AIMARating = $course['filterValues'][CP_FILTER_AIMARATING];
        if(isset($this->filterValues[$AIMARating])) {
            return TRUE;
        }
        
        return FALSE;
    }
}