<?php
class BaseAttribute {
	private $name;
	private $id;
	
	function __set($property,$value) {
		$this->$property = $value;
	}

	function getName(){
		return $this->name;
	}

	function getId(){
		return $this->id;
	}

	function setName($name){
		$this->name = $name;
	}

}