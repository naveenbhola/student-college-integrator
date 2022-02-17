<?php

class Banner
{
    private $bannerurl;
    private $institute_id;
    
    function __construct()
    {
        
    }
    
    public function getURL()
    {
        return $this->bannerurl;
    }
    
    public function getInstituteId()
    {
        return $this->institute_id;
    }
    
    function __set($property,$value)
    {
        $this->$property = $value;
    }
}