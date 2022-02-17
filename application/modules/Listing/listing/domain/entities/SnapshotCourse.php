<?php

class SnapshotCourse
{
    private $course_id;
    private $course_name;
    private $course_type;
    private $university_id;
    private $website_link;
    private $category_id;
    private $created;
    private $last_modified;
    private $listing_seo_url;
    private $createdBy;
    private $lastModifiedBy;
    
    function __construct()
    {
        
    }
    
    
    public function getId()
    {
        return $this->course_id;
    }
    
    public function getName()
    {
        return $this->course_name;
    }
    
    public function getType()
    {
        return $this->course_type;
    }
    
    public function getUniversityId()
    {
        return $this->university_id;
    }
    
    public function getWebsiteLink()
    {
        return $this->website_link;
    }
    
    public function getCategoryId()
    {
        return $this->category_id;
    }
    
    public function getCreated()
    {
        return $this->created;
    }
    
    public function getLastModified()
    {
        return $this->last_modified;
    }
    
    public function getSeoUrl(){
        return $this->listing_seo_url;
    }
    
    function __set($property,$value)
    {
        $this->$property = $value;
    }
    
    public function cleanForCategorypage(){
        unset($this->website_link);
        unset($this->created);
        unset($this->last_modified);
        unset($this->createdBy);
        unset($this->lastModifiedBy);
       
    }   
}
