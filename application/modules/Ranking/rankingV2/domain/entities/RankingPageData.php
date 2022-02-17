<?php

class RankingPageData {
	
	private $id;
	
	private $courseId;
	
	private $instituteId;

	private $sourceRank;
	
	public function __construct(){
		
	}
	
	public function getId(){
		return $this->id;
	}
	
	public function getCourseId(){
		return $this->courseId;
	}
	
	public function getInstituteId(){
		return $this->instituteId;
	}

	public function getSourceWiseRank(){
		return $this->sourceRank;
	}
	
	public function __set($property,$value) {
		$this->$property = $value;
	}
}	
	