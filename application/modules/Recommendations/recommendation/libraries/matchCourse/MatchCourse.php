<?php

class MatchCourse
{
    private $id;
    private $instituteId;
    private $educationType;
    private $deliveryMethod;
    private $courseLevel;
    private $credential;
    private $baseCourse;
    private $hierarchies;
    private $cities;
    
    function __construct($id,
                      $instituteId,
                      $educationType,
                      $deliveryMethod,
                      $courseLevel,
                      $credential,
                      $baseCourse,
                      $hierarchies,
                      $cities)
    {
        $this->id = intval($id);
        $this->instituteId = intval($instituteId);
        $this->educationType = intval($educationType);
        $this->deliveryMethod = intval($deliveryMethod);
        $this->courseLevel = intval($courseLevel);
        $this->credential = intval($credential);
        $this->hierarchies = $hierarchies;
        $this->baseCourse = intval($baseCourse);
        $this->cities = $cities;
    }

    public function matches(MatchCourse $course)
    {
        if($this->instituteId == $course->instituteId) {
            //echo "Institute: ".$this->instituteId." | ".$course->instituteId."<br />";
            return FALSE;
        }

        if($this->educationType != $course->educationType) {
            //echo "educationType: ".$this->educationType." | ".$course->educationType."<br />";
            return FALSE;
        }

        if($this->deliveryMethod != $course->deliveryMethod) {
            //echo "deliveryMethod: ".$this->deliveryMethod." | ".$course->deliveryMethod."<br />";
            return FALSE;
        }

        if($this->courseLevel != $course->courseLevel) {
            //echo "courseLevel: ".$this->courseLevel." | ".$course->courseLevel."<br />";
            return FALSE;
        }

        if($this->credential != $course->credential) {
            //echo "credential: ".$this->credential." | ".$course->credential."<br />";
            return FALSE;
        }

        if($this->baseCourse != $course->baseCourse) {
            //echo "baseCourse: ".$this->baseCourse." | ".$course->baseCourse."<br />";
            return FALSE;
        }

        if(count(array_intersect(array_keys($this->hierarchies), array_keys($course->hierarchies))) == 0) {
            //error_log($course1.",".$course2."\n", 3, "/tmp/cfdata_ex_cat.csv");
            return FALSE;
        }
        
        return TRUE;
    }

    public function getInstituteId()
    {
        return $this->instituteId;
    }
    
    public function getEducationType()
    {
        return $this->educationType;
    }
    
    public function getDeliveryMethod()
    {
        return $this->deliveryMethod;
    }
    
    public function getCourseLevel()
    {
        return $this->courseLevel;
    }
    
    public function getCredential()
    {
        return $this->credential;
    }
    
    public function getBaseCourse()
    {
        return $this->baseCourse;
    }
    
    public function getHierarchies()
    {
        return $this->hierarchies;
    }

    public function getCities()
    {
        return $this->cities;
    }
    
    public function getId()
    {
        return $this->id;
    }
}
