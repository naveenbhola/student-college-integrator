<?php

class Campus
{
    private $campus_name;
    private $campus_website_url;
    private $campus_address;
    
    function __construct()
    {
        
    }
    
    public function getName()
    {
        return $this->campus_name;
    }
    
    public function getWebsiteURL()
    {
        return $this->campus_website_url;
    }
    
    public function getAddress()
    {
        return $this->campus_address;
    }
    
    function __set($property,$value)
    {
        $this->$property = $value;
    }
}