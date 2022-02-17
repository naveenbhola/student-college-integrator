<?php

class CollegePredictorFilter {
	
	private $name;
	
	private $value;

	private $groupName;
	
	private $selected = false;
	
	public function __construct($name = NULL, $value = NULL,  $selected = false, $groupName = NULL){
		if(!empty($name) && !empty($value)){
			$this->setName($name);
			$this->setValue($value);
			$this->setGroupName($groupName);
			if($selected === true){
				$this->setSelected(true);
			}
		}
	}

	
	public function setName($name){
		if(!empty($name)){
			$this->name = $name;
		}
	}
	
	public function getName(){
		return $this->name;
	}
	
	public function setValue($value){
		if(!empty($value)){
			$this->value = $value;
		}
	}
	
	public function getValue(){
		return $this->value;
	}

	public function setGroupName($groupName){
		if(!empty($groupName)){
			$this->groupName = $groupName;
		}
	}
	
	public function getGroupName(){
		return $this->groupName;
	}
	
	public function setSelected($slectedValue = false){
		$this->selected = $slectedValue;
	}
	
	public function isSelected(){
		return $this->selected;
	}
	
}
