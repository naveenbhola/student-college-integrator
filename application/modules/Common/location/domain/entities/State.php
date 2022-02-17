<?php

class State
{
    private $state_id;
    private $state_name;
    private $enabled;
    private $countryId;
    private $is_popular;
    private $tier;
    
    function __construct()
    {
        
    }
    
    public function getName()
    {
        return $this->state_name;
    }
    
    public function getId()
    {
        return $this->state_id;
    }
    
    public function getCountryId()
    {
        return $this->countryId;
        
    }
    
    public function getTier()
    {
        return $this->tier;
    }

    public function isPopular() {
        return $this->is_popular;
    }
    
    function __set($property,$value)
    {
        $this->$property = $value;
    }
}
