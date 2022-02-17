<?php

class RankingPageSorter {
	
	private $key;
	
	private $order;
	
	private $keyValue;
	
	public function __construct(){
	}
	
	public function setKey($key = NULL){
		if(isset($key)){
			$this->key = $key;
		}
	}
	
	public function setKeyValue($keyValue = NULL){
		if(isset($keyValue)){
			$this->keyValue = $keyValue;
		}
	}
	
	public function setOrder($order = NULL){
		if(isset($order)){
			$this->order = $order;
		}
	}
	
	public function getKey(){
		return $this->key;
	}
	
	public function getOrder(){
		return $this->order;
	}
	
	public function getKeyValue(){
		return $this->keyValue;
	}
}