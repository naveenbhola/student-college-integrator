<?php

class Ranking
{
    private $ranking;
    private $sourceName;
    private $sourceURL;
    
    function __construct()
    {
        
    }
    
    function __toString()
    {
        return $this->getRankingValue();
    }
    
    public function getRankingValue()
    {
        return $this->ranking;
    }
    
    public function getSource()
    {
        return $this->sourceName;
    }
    
    public function getSourceURL()
    {
        return $this->sourceURL;
    }
        
    function __set($property,$value)
    {
        $this->$property = $value;
    }
}