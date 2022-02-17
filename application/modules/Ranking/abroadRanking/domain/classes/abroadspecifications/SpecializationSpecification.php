<?php

class SpecializationSpecification extends CompositeSpecification
{
    function isSatisfiedBy($course)
    {
        $specialization = (array) $course['filterValues'][CP_FILTER_COURSECATEGORY];
        $matchingSpecialization = array_intersect($specialization,$this->filterValues);
        if(count($matchingSpecialization) > 0) { 
            return TRUE;
        }

        return FALSE;
    }
}