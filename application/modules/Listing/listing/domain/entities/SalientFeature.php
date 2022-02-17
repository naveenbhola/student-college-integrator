<?php

class SalientFeature
{
    private $salient_feature_id;
    private $feature_name;
    private $value;
    private $display_order;
    
    function __construct()
    {
        
    }
    
    public function getName()
    {
        return $this->feature_name;
    }
    
    public function getValue()
    {
        return $this->value;
    }
    
    function __set($property,$value)
    {
        $this->$property = $value;
    }
}