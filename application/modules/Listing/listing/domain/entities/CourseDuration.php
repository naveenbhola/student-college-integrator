<?php

class CourseDuration
{
    private $duration_unit;
    private $duration_value;
    
    function __construct()
    {
        
    }
    
    function __toString()
    {
        return $this->getDisplayValue();
    }
    
    public function getDisplayValue()
    {
        $durationValue = (int) $this->duration_value;
        $durationUnit = ucfirst($this->duration_unit);
        
        if($durationValue > 0 && $durationUnit) {
            if($durationValue == 1) {
                if(substr($durationUnit,-1) == 's') {
                    $durationUnit = substr($durationUnit,0,-1);
                }
            }
            else if($durationValue > 1) {
                if(substr($durationUnit,-1) != 's') {
                    $durationUnit .= 's';
                }
            }
            
            $duration = $durationValue.' '.$durationUnit;   
            return $duration;
        }
    }
    
    function getValueInHours()
    {
        $value = 0;
        
        if($this->duration_unit) {
            $value = (int) $this->duration_value;
            $durationUnit = trim(strtolower($this->duration_unit));
            
            if($durationUnit == 'hour' || $durationUnit == 'hours') {
                $value = $value * 1;
            }
            else if($durationUnit == 'day' || $durationUnit == 'days') {
                $value = $value * 24;
            }
            else if($durationUnit == 'week' || $durationUnit == 'weeks') {
                $value = $value * 7 * 24;
            }
            else if($durationUnit == 'month' || $durationUnit == 'months') {
                $value = $value * 24 * 30;
            }
            else if($durationUnit == 'year' || $durationUnit == 'years') {
                $value = $value * 24 * 365;
            }
        }
        
        return $value;
    }
    
    function __set($property,$value)
    {
        $this->$property = $value;
    }
    
    function getDurationUnit(){
        return ucfirst($this->duration_unit);
    }
    
    function getDurationValue(){
        return (int)$this->duration_value;
    }
    
    function getExactDurationValue()
    {
        return $this->duration_value;
    }
    
}