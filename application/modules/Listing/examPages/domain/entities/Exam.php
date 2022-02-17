<?php

/*
 * Exam Entity
 */
class Exam
{
    private $id;
	private $name;
	private $fullName;
	private $conductedBy;
	private $url;
	private $isRootUrl;
	private $creationDate;
	private $status;
	private $groupMappedToExam;
	private $primaryGroup;

	
	function __construct() {
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function getName() {
		return $this->name;
	}

	public function getFullName() {
		return $this->fullName;
	}

	public function getConductedBy(){
		return $this->conductedBy;
	}
	
	public function getUrl(){
	return SHIKSHA_HOME.$this->url;
	}

	public function isRootUrl() {
		return $this->isRootUrl;
	}
	
	public function getCreationDate() {
		return $this->creationDate;
	}
	
	public function getStatus() {
		return $this->status;
	}

	public function setPrimaryGroup($examObj){
		$groupMappedToExam  = $examObj->getGroupMappedToExam();
		foreach($groupMappedToExam as $details){
			if($details['primaryGroup']){
				$this->primaryGroup = $details;
				break;
			}
		}
	}

	public function getPrimaryGroup(){
		return $this->primaryGroup;
	}

	public function __set($property,$value) {
		$this->$property = $value;
	}

	public function setGroupMappedToExam($examData){
		return $this->groupMappedToExam = $examData['groupMapping'];
	}

	public function getGroupMappedToExam(){
		return $this->groupMappedToExam;
	}
	function getAmpURL($sectionName,$sections)
	{
		$ampUrl    = $this->getUrl();
		$isRootUrl = $this->isRootUrl();

		foreach($sections as $section=>$val){
			$sectionArray[] = $val;
		}

		if($isRootUrl == 'Yes' && in_array($sectionName, $sectionArray) && !empty($sectionName)){
			$ampUrl = $ampUrl.'/'.$sectionName;
		}else if(in_array($sectionName, $sectionArray) && !empty($sectionName)){
			$ampUrl = $ampUrl.'-'.$sectionName;
		}

		if($isRootUrl == 'Yes'){
			$ampUrl = str_replace('exams/', 'exams/amp/', $ampUrl);
		}else{	
			$urlArr    = split('/', $ampUrl);
			$ampPrefix = 'amp/'.end($urlArr);
			$ampUrl = str_replace(end($urlArr), $ampPrefix, $ampUrl);
		}
		return $ampUrl;
	}

}