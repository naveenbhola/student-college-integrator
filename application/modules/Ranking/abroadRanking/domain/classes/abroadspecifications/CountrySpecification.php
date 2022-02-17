<?php

class CountrySpecification extends CompositeSpecification
{
    function isSatisfiedBy($course)
    {
        $countries = (array) $course['filterValues'][CP_FILTER_COUNTRY];

        $matchingCountries = array_intersect($countries,$this->filterValues);
        
        if(count($matchingCountries) > 0) { 
            return TRUE;
        }
    
        return FALSE;
    }
}