<?php

class Country
{
    private $countryId;
    private $name;
    private $enabled;
    private $urlName;
    private $regionid;
    private $countryTier;
    private $trackingPriority;
    
    function __construct()
    {
        
    }
    
    public function getId()
    {
        return $this->countryId;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getTier()
    {
        return $this->countryTier;
    }
    
    public function getRegionId()
    {
        return $this->regionid;
    }
    
    public function getTrackingPriority()
    {
        return $this->trackingPriority;
    }
    
    function __set($property,$value)
    {
        $this->$property = $value;
    }
}