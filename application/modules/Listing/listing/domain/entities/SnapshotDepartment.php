<?php

class SnapshotDepartment
{
    private $department_name;
    private $department_website_url;
    
    function __construct()
    {
        
    }
    
    public function getName()
    {
        return $this->department_name;
    }
    
    public function getWebsiteURL()
    {
        return $this->department_website_url;
    }
    
    function __set($property,$value)
    {
        $this->$property = $value;
    }
}