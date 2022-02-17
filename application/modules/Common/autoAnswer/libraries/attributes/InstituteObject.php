<?php

class InstituteObject {

	private $value;
	private $id;
	private $type;
	private $preferredChoice;
	private $baseCourse;
	private $clientCourseArray;
	private $selectedAttrList;

	public function __construct(){

		$this->preferredChoice = "";
		$this->id = 0;
		$this->value = "";
		$this->type = "";
		$this->baseCourse = 0;
		$this->clientCourseArray = array();
		$this->selectedAttrList = array();
	}
	
	public function setValue($value){
		$this->value = $value;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function setType($type){
		$this->type = $type;
	}
	
	public function getType(){
		return $this->type;
	}

	public function getValue(){
		return $this->value;
	}
	public function getId(){
		return $this->id;
	}

	public function setPreferredChoice($preferredChoice){
		$this->preferredChoice = $preferredChoice;
	}

	public function getPreferredChoice(){
		return $this->preferredChoice;
	}

	public function setBaseCourse($baseCourse){
		$this->baseCourse = $baseCourse;
	}

	public function getBaseCourse(){
		return $this->baseCourse;
	}

	public function getClientCourseArray(){
		return $this->clientCourseArray;
	}

	public function setClientCourseArray($clientCourseArray){
		$this->clientCourseArray = $clientCourseArray;
	}

	public function setSelectedAttrList($selectedAttrList){
		$this->selectedAttrList = $selectedAttrList;
	}
	public function getSelectedAttrList(){
		return $this->selectedAttrList;
	}
	
}