<?php

class Region
{
    private $regionid;
    private $regionname;
    
    function __construct()
    {
        
    }
    
    public function getId()
    {
        return $this->regionid;
    }
    
    public function getName()
    {
        return $this->regionname;
    }
    
    function __set($property,$value)
    {
        $this->$property = $value;
    }
}