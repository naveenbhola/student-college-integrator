<?php

class RankingPageRequest {
	
	private $rankingPageId;
	
	private $rankingPageName;
	
	private $cityId;
	
	private $cityName;
	
	private $stateId;
	
	private $stateName;
	
	private $countryId;
	
	private $countryName;
	
	private $examId;
	
	private $examName;
	
	public function __construct(){
		
	}
	
	public function setPageId($id = NULL){
		if(isset($id)){
			$this->rankingPageId = $id;
		}
	}
	
	public function getPageId(){
		return $this->rankingPageId;
	}
	
	public function setPageName($name){
		if(isset($name)){
			$this->rankingPageName = $name;
		}
	}
	
	public function getPageName(){
		return $this->rankingPageName;
	}
	
	public function setCityId($id = NULL){
		if(isset($id)){
			$this->cityId = $id;
		}
	}
	
	public function getCityId(){
		return $this->cityId;
	}
	
	public function setCityName($name = NULL){
		if(isset($name)){
			$this->cityName = $name;
		}
	}
	
	public function getCityName(){
		return $this->cityName;
	}
	
	public function setStateId($id = NULL){
		if(isset($id)){
			$this->stateId = $id;
		}
	}
	
	public function getStateId(){
		return $this->stateId;
	}
	
	public function setStateName($name = NULL){
		if(isset($name)){
			$this->stateName = $name;
		}
	}
	
	public function getStateName(){
		return $this->stateName;
	}
	
	public function setCountryId($id = NULL){
		if(isset($id)){
			$this->countryId = $id;
		}
	}
	
	public function getCountryId(){
		return $this->countryId;
	}
	
	public function setCountryName($name = NULL){
		if(isset($name)){
			$this->countryName = $name;
		}
	}
	
	public function getCountryName(){
		return $this->countryName;
	}
	
	public function setExamId($id = NULL){
		if(isset($id)){
			$this->examId = $id;	
		}
	}
	
	public function getExamId(){
		return $this->examId;
	}
	
	public function setExamName($name = NULL){
		if(isset($name)){
			$this->examName = $name;
		}
	}
	
	public function getExamName(){
		return $this->examName;
	}
}