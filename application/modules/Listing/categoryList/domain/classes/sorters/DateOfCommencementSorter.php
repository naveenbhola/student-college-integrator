<?php

class DateOfCommencementSorter extends AbstractSorter
{
    protected $sortKey = CP_SORTER_DATEOFCOMMENCEMENT;
    
    function __construct($params = array())
    {
        parent::__construct($params);
    }
    
    public function extractSortValue(Institute $institute,Course $course)
    {
        return (int) strtotime($course->getDateOfCommencement());
    }
}