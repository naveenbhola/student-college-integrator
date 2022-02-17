<?php

class ExamsFilter extends AbstractFilter
{
    function __construct()
    {
        parent::__construct();
    }
    
    public function getFilteredValues()
    {
        return $this->values;
    }
    
    public function extractValue(Institute $institute,Course $course)
    {
        $exams = $course->getEligibilityExams();
        $examAcronyms = array();
        foreach($exams as $exam) {
            if($examAcronym = $exam->getAcronym()) {
                $examAcronyms[] = $examAcronym;
            }
        }
        return $examAcronyms;
    }
    
    public function addValue(Institute $institute,Course $course)
    {
        $examAcronyms = $this->extractValue($institute,$course);
        foreach($examAcronyms as $examAcronym) {
            $this->values[$examAcronym] = $examAcronym;
        }
    }
}
