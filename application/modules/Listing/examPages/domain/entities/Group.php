<?php

/*
 * Exam Entity
 */
class Group
{
    private $groupId;
	private $groupName;
	private $examId;
	private $isPrimary;
	private $status;
	private $creationDate;
	private $lastModifiedDate;
	private $entitiesMappedToGroup;
	private $hierarchyData;
	
	function __construct() {
	}
    
    function __set($property,$value) {
		$this->$property = $value;
	}
	
	function getId() {
		return $this->groupId;
	}
	
	function getName() {
		return $this->groupName;
	}

	function getExamId(){
		return $this->examId;
	}

	function isPrimary(){
		return $this->isPrimary;
	}
	
	function getStatus(){
		return $this->status;
	}

	function getCreationDate(){
		return $this->creationDate;
	}

	function getLastModifiedDate(){
		return $this->lastModifiedDate;
	}

	function getExamMappedToGroup($groupId){
		return $this->examMappedToGroup;
	}
	
	function setEntityToGroupMapping($mappingData){
		$this->entitiesMappedToGroup[$mappingData['entityType']][] = $mappingData['entityId'];
	}

	function getEntitiesMappedToGroup($groupId){
		return $this->entitiesMappedToGroup;
	}

        function setHierarchy($primaryId , $hierarchyInfo){
	        foreach($hierarchyInfo as $key=>$value){
        	      $this->hierarchyData[$key]['primary_hierarchy'] = '0';
	                if($key==$primaryId){
                        	$this->hierarchyData[$key]['primary_hierarchy'] = '1';
                	}
        	        $this->hierarchyData[$key]['stream']         = $value['stream_id'];
	                $this->hierarchyData[$key]['substream']      = $value['substream_id'];
                	$this->hierarchyData[$key]['specialization'] = $value['specialization_id'];
        	}
    	}

    	function getHierarchy(){
        	return $this->hierarchyData;
    	}
}