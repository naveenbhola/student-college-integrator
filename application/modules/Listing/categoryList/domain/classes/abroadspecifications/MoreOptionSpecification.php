<?php

class MoreOptionSpecification extends CompositeSpecification
{
    function isSatisfiedBy($course)
    {
        $moreoption = (array) $course['filterValues'][CP_FILTER_MOREOPTIONS];
        $matchingOptions = array_intersect($moreoption,$this->filterValues);
        if(count($matchingOptions) == count($this->filterValues)) {     // All selected should be present post intersection
            return TRUE;
        }

        return FALSE;
    }
}