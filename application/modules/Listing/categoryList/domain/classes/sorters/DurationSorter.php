<?php

class DurationSorter extends AbstractSorter
{
    protected $sortKey = CP_SORTER_DURATION;
    
    function __construct($params = array())
    {
        parent::__construct($params);
    }
    
    public function extractSortValue(Institute $institute,Course $course)
    {
        return (int) $course->getDuration()->getValueInHours();
    }
}