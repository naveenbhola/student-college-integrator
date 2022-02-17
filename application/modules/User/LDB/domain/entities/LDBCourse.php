<?php

class LDBCourse
{
    private $SpecializationId;
    private $SpecializationName;
    private $CourseName;
    private $CourseLevel;
    private $CourseLevel1;
    private $CourseLevel2;
    private $SubCategoryId;
    private $CategoryId;
    private $ParentId;
    private $CourseReach;
    private $CourseDetail;
    private $scope;
    
    private $isPopularCourse;
    
    function __construct()
    {
        
    }
        
    public function getId()
    {
        return $this->SpecializationId;
    }
    
    public function getSpecialization()
    {
        return $this->SpecializationName;
    }
    
    public function getCourseName()
    {
        return $this->CourseName;
    }
    
    public function getCourseLevel1()
    {
        return $this->CourseLevel1;
    }
    
    public function getSubCategoryId() {        
        return $this->SubCategoryId;
    }
    
    public function getCategoryId()
    {
        return $this->CategoryId;
    }
    
    public function getParentId()
    {
        return $this->ParentId;
    }
    
    public function isPopularCourse()
    {
        return $this->isPopularCourse;
    }
    
    public function getDisplayName()
    {
        $displayName = '';
        if($this->CourseName != 'All') {
            $displayName = $this->CourseName;
        }
        if($this->SpecializationName != 'All') {
            $displayName .= ' '.$this->SpecializationName;
        }
        return $displayName;
    }
    
    public function getScope(){
        return $this->scope;
    }
    
    function __set($property,$value)
    {
        $this->$property = $value;
    }
}
