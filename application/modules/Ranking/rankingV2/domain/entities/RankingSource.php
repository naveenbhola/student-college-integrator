<?php

class RankingSource {
	
	private $source_id;
	private $publisher_id;
	private $name;
	private $year;
	
	public function __construct(){
		
	}

	public function __set($property,$value) {
		$this->$property = $value;
	}

	public function getId(){
		return $this->source_id;
	}
	
	public function getName(){
		return $this->name;
	}
	
	public function getPublisherId(){
		return $this->publisher_id;
	}
	
	public function getYear(){
		return $this->year;
	}
}