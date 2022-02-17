<?php

class RankingPage {
	// ranking page details
	private $rankingPageId;
	private $rankingPageName;
	private $rankingPageTitle;
	private $rankingPageType;
	private $parentCategoryId;
	private $subCategoryId;
	private $ldbCourseId;
	private $countryId;
	private $created;
	private $lastModified;
	private $lastModifiedBy;
	private $createdBy;
	private $seoTitle;
	private $seoDescription;
	private $seoKeywords;
	private $rankings;
	
	public function __construct(){
		
	}
	
	public function getId(){
		return $this->rankingPageId;
	}
	
	public function getName(){
		return $this->rankingPageName;
	}

	public function getTitle(){
		return $this->rankingPageTitle;
	}
	
	public function getType(){
		return $this->rankingPageType;
	}
	
	public function getParentCategoryId(){
		return $this->parentCategoryId;
	}
	
	public function getSubCategoryId(){
		return $this->subCategoryId;
	}
	
	public function getLDBCourseId(){
		return $this->ldbCourseId;
	}
	
	public function getCountryId(){
		return $this->countryId;
	}
	
	public function getCreatedTime(){
		return $this->created;
	}
	
	public function getLastUpdatedTime(){
		return $this->updated;
	}
	
	public function getCreatedBy(){
		return $this->createdBy;
	}
	
	public function getLastUpdatedBy(){
		return $this->lastModifiedBy;
	}
	
	public function getMetaData()
	{
		return array(
			     "seoTitle"=>$this->seoTitle,
			     "seoDescription"=>$this->seoDescription,
			     "seoKeywords"=>$this->seoKeywords
			     );
	}
	
	public function getRankingPageData(){
		return $this->rankings;
	}
	/*
	 * set ranked objects (course/univesity)
	 */ 
	public function setRankingPageData($rankedObjects){
		$this->rankings = $rankedObjects;
	}
	
	public function __set($property,$value) {
		$this->$property = $value;
	}
}	
	