<?php

/*
 * Generic entity that stores wiki type of information in examPagesCMS form
 */
class Wiki
{
    private $id;
	private $page_id;
	private $section_name;
	private $entity_type;
	private $entity_value;
    private $external_css;
    private $toc_text;
	private $status;
	private $creationTime;
	private $updationTime;
    private $updatedOn;
    
    function __construct() {
        
    }
	
	function __set($property,$value) {
        $this->$property = $value;
    }

    function getId(){
    	return $this->id;
    }

    function getPageId(){
    	return $this->page_id;
    }

    function getSectionName(){
    	return $this->section_name;
    }

    function getEntityType(){
    	return $this->entity_type;
    }

    function getEntityValue(){
    	return $this->entity_value;
    }

    function getStatus(){
    	return $this->status;
    }

    function getUpdatedOn(){
        return $this->updatedOn;
    }

    function getCreationTime(){
    	return $this->creationTime;
    }

    function getUpdationTime(){
    	return $this->updationTime;
    }

    function getExternalCSS(){
        return $this->external_css;
    }
    
    function setEntityValue($value)
    {
        $this->entity_value = $value;
    }

    function setTocContent($value){
        $this->toc_text = $value;
    }

    function getTocContent(){
        return $this->toc_text;
    }

}