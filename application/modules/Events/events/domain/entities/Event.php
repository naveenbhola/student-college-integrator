<?php

class Event
{
    private $event_id;
    private $event_title;
    private $fromOthers;
    private $start_date;
    private $end_date;
    
    function __construct()
    {
        
    }
    
    public function getStartDate()
    {
        return $this->start_date;
    }
    
    public function isCourseCommencement()
    {
        if(strpos($this->event_title,'Course comencement') === 0) {
            return TRUE;
        }
        
        return FALSE;
    }
    
        
    function __set($property,$value)
    {
        $this->$property = $value;
    }
}