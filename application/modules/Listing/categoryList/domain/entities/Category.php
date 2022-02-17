<?php

class Category
{
    private $boardId;
    private $name;
    private $shortName; 
    private $tier;
    private $priority;
    private $flag;
    private $parentId;
    private $isOldCategory;
    private $seoUrlDirectoryName;
    
    function __construct()
    {
        
    }
    
    public function getId()
    {
        return $this->boardId;
    }
    
    public function getParentId()
    {
        return $this->parentId;
    }
    
    public function getShortName() {
        return $this->shortName;
    }
    
    public function isManagement()
    {
        return ($this->boardId == 3 || $this->parentId == 3);
    }
    
    public function isEngineering()
    {
        return ($this->boardId == 2 || $this->parentId == 2);
    }
    
    public function isTestPrep()
    {
        return ($this->boardId == 14);
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getTier()
    {
        return $this->tier;
    }

    public function getFlag()
    {
        return $this->flag;
    }
    
    public function getOldCategoryFlag() {
        return $this->isOldCategory;
    }

    public function getSeoUrlDirectoryName() {
        return $this->seoUrlDirectoryName;
    }
    
    function __set($property,$value)
    {
        $this->$property = $value;
    }
}