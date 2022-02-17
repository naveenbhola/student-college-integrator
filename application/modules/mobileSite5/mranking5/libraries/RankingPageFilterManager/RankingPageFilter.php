<?php

class RankingPageFilter {
	
	private $id;
	
	private $name;
	
	private $url;
	
	private $selected = false;
	
	public function __construct($id = NULL, $name = NULL, $url = NULL, $selected = false){
		if(isset($id) && !empty($name) && !empty($url)){
			$this->setId($id);
			$this->setName($name);
			$this->setURL($url);
			if($selected === true){
				$this->setSelected(true);
			}
		}
	}
	
	public function setId($id = NULL){
		if(isset($id)){
			$this->id = $id;
		}
	}
	
	public function getId(){
		return $this->id;
	}
	
	public function setName($name){
		if(!empty($name)){
			$this->name = $name;
		}
	}
	
	public function getName(){
		return $this->name;
	}
	
	public function setURL($url){
		if(!empty($url)){
			$this->url = $url;
		}
	}
	
	public function getURL(){
		return SHIKSHA_HOME . "/". ltrim($this->url, "/");
	}
	
	public function setSelected($slectedValue = false){
		$this->selected = $slectedValue;
	}
	
	public function isSelected(){
		return $this->selected;
	}
	
}