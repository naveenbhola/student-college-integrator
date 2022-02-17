<?php

class CoursePagesWidgets
{
    private $widgetId;
    private $widgetHeading;
    private $columnPosition;
    private $displayorder;
    private $widgetKey;
    private $courseHomePageId;
            
    function __construct()
    {
        
    }
    
    public function getId()
    {
        return $this->widgetId;
    }
    
    public function getWidgetHeading()
    {
        return $this->widgetHeading;
    }
    
    public function getColumnPosition()
    {
        return $this->columnPosition;
    }

    public function getDisplayOrder()
    {
        return $this->displayorder;
    }
    
    public function getWidgetKey() {
    	
    	return $this->widgetKey;
    }
    
    public function getCourseHomePageId() {
        return $this->courseHomePageId;
    }

    function __set($property,$value)
    {
        $this->$property = $value;
    }
}