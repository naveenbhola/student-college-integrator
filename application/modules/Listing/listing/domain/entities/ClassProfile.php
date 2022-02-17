<?php

class ClassProfile
{
    private $average_work_experience;
    private $average_gpa;
    private $average_xii_percentage;
    private $average_gmat_score;
    private $average_age;
    private $percentage_international_students;
    
    function __construct()
    {
        
    }
    
    public function getAverageWorkExperience()
    {
        return $this->average_work_experience;
    }
    
    public function getAverageGPA()
    {
        return $this->average_gpa;
    }
    
    public function getAverageXIIPercentage()
    {
        return $this->average_xii_percentage;
    }
    
    public function getAverageGMATScore()
    {
        return $this->average_gmat_score;
    }
    
    public function getAverageAge()
    {
        return $this->average_age;
    }
    
    public function getPercenatgeInternationalStudents()
    {
        return $this->percentage_international_students;
    }
    
    function __set($property,$value)
    {
        $this->$property = $value;
    }
}
