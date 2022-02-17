<?php

class personalizedMailerWidgets
{
    private $widgetId;
    private $columnPosition;
    private $displayorder;
    private $widgetName;
    
    function __construct()
    {
        
    }
    
    public function getId()
    {
        return $this->widgetId;
    }
    
    public function getColumnPosition()
    {
        return $this->columnPosition;
    }

    public function getDisplayOrder()
    {
        return $this->displayorder;
    }
    
    public function getWidgetName() {
    	
    	return $this->widgetName;
    }
    
    function __set($property,$value)
    {
        $this->$property = $value;
    }
}