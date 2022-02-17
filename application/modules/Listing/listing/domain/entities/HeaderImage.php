<?php

class HeaderImage
{
    private $id;
    private $name;
    private $full_url;
    private $thumb_url;
    private $header_order;
        
    function __construct()
    {
        
    }
          
    public function getThumbURL()
    {
        return $this->thumb_url;
    }
	
    public function getFullURL()
    {
        return $this->full_url;
    }
    
    public function getURL()
    {
        return $this->full_url;
    }
    
    function __set($property,$value)
    {
        $this->$property = $value;
    }
}