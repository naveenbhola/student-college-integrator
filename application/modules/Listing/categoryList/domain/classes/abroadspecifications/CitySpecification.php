<?php

class CitySpecification extends CompositeSpecification
{
    function isSatisfiedBy($course)
    {
        $cities = (array) $course['filterValues'][CP_FILTER_CITY];
        $matchingCities = array_intersect($cities,$this->filterValues);
        if(count($matchingCities) > 0) { 
            return TRUE;
        }
    
        return FALSE;
    }
}