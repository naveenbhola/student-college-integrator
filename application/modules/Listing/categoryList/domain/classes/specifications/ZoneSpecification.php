<?php

class ZoneSpecification extends CompositeSpecification
{
    function isSatisfiedBy($course)
    {
        $zones = (array) $course['filterValues'][CP_FILTER_ZONE];
        $matchingZones = array_intersect($zones,$this->filterValues);
        
        if(count($matchingZones) > 0) { 
            return TRUE;
        }
    
        return FALSE;
    }
}