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

	private $streamId;
	
	private $substreamId;
	
	private $shikshaSpecializationId;
	
	private $baseCourseId;
	
	private $credential;
	
	private $educationType;
	
	private $deliveryMethod;

	private $isUGCategory = false;

	private $isRankingPageLive = true;
	
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

	public function setSpecializationId($id = NULL){
		$this->shikshaSpecializationId = (integer)$id;
	}

	public function getSpecializationId(){
		return $this->shikshaSpecializationId;
	}

	public function getPageKey()
	{
	
		$ExamName            = $this->examId;
		$ExamNameForKey      = empty($ExamName) ? "none" : $ExamName;
		$cityId              = $this->cityId;
		$cityIdForKey        = empty($cityId) ? "0" : $cityId;
		$stateId             = $this->stateId;
		$stateIdForKey       = empty($stateId) ? "0" : $stateId;
		$LDBCourseId         = $this->specializationId;
		$LDBCourseIdForKey   = empty($LDBCourseId) ? "1" : $LDBCourseId;
		$categoryId          =  $this->categoryId;
		$categoryIdForKey    = empty($categoryId) ? "1" : $categoryId;
		$subCategoryId       =  $this->subCategoryId;
		$subCategoryIdForKey = empty($subCategoryId) ? "1" : $subCategoryId;
		
		return $categoryIdForKey."-".$subCategoryIdForKey."-".$LDBCourseIdForKey."-".$stateIdForKey."-".$cityIdForKey."-".$ExamNameForKey;
	
	}

	function setUGcategory($status) {
		$this->isUGCategory = $status;
	}

	function isUGCategory() {
		return $this->isUGCategory;
	}

	function isRankingPageLive() {
		return $this->isRankingPageLive;
	}
	
	function setIsRankingPageLive($boolStatus) {
		$this->isRankingPageLive = $boolStatus;
	}

	function getStreamId(){
		return $this->streamId;
	}

	function setStreamId($streamId){
		$this->streamId = (integer)$streamId;
	}

	function getSubstreamId(){
		return $this->substreamId;
	}

	function setSubstreamId($substreamId){
		$this->substreamId = (integer)$substreamId;
	}

	function getBaseCourseId(){
		return $this->baseCourseId;
	}

	function setBaseCourseId($baseCourseId){
		$this->baseCourseId = (integer)$baseCourseId;
	}

	function getCredential(){
		return $this->credential;
	}

	function setCredential($credential){
		$this->credential = (integer)$credential;
	}
	
	function getEducationType(){
		return $this->educationType;
	}

	function setEducationType($educationType){
		$this->educationType = (integer)$educationType;
	}
	
	function getDeliveryMethod(){
		return $this->deliveryMethod;
	}
	
	function setDeliveryMethod($deliveryMethod){
		$this->deliveryMethod = (integer)$deliveryMethod;
	}
}