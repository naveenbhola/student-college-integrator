<?php

class RankingPageFilter {
	
	private $id;
	
	private $name;
	
	private $url;
	
	private $count;
	
	private $selected = false;
	
	public function __construct($id = NULL, $name = NULL, $url = NULL, $selected = false, $count = null){
		if(isset($id) && !empty($name)){
			$this->setId($id);
			$this->setName($name);
			$this->setURL($url)->setCount($count);
			if($selected === true){
				$this->setSelected(true);
			}
		}
	}
	
	public function setId($id = NULL){
		if(isset($id)){
			$this->id = $id;
		}
		return $this;
	}
	
	public function getId(){
		return $this->id;
	}
	
	public function setName($name){
		if(!empty($name)){
			$this->name = $name;
		}
		return $this;
	}
	
	public function getName(){
		return $this->name;
	}
	
	public function setURL($url){
		if(!empty($url)){
			$this->url = $url;
		}
		return $this;
	}
	
	public function getURL(){
		return SHIKSHA_HOME . "/". ltrim($this->url, "/");
	}
	
	public function setSelected($slectedValue = false){
		$this->selected = $slectedValue;
		return $this;
	}
	
	public function isSelected(){
		return $this->selected;
	}
	
	function setCount($count) {
		$this->count = $count;
		return $this;
	}	
	function getCount() {
		return $this->count;
	}	
}