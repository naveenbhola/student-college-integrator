<?php

class ExamSpecification extends CompositeSpecification
{
    function isSatisfiedBy($course)
    {
        $exams = (array) $course['filterValues'][CP_FILTER_EXAMS];
        $matchingExams = array_intersect($exams,$this->filterValues);
        
        if(count($matchingExams) > 0) { 
            return TRUE;
        }
        
        return FALSE;
    }
}