<?php

/*
 * Main entity that stores complete exampage data using ExamPageDate and Wiki entities
 */
class ExamPage
{
    private $exampageId;
	private $examName;
	private $examId;
	private $categoryName;
	private $examFullForm;
	private $examArticleTags;
	private $gradeType;
	private $lastModifyDate;
	private $lastModifedBy;
	private $discussionIds;
	private $tileLink;
	private $status;
	
	private $sectionInfo;

	private $homepage;
	private $syllabus;
	private $importantDates;
	private $results;
	
	function __construct() {
	}
    
    function __set($property,$value) {
		$this->$property = $value;
	}
	
	function getExampageId() {
		return $this->exampageId;
	}
	
	function getExamName() {
		return $this->examName;
	}

	function getExamId() {
		return $this->examId;
	}

	function getExamFullForm(){
		return $this->examFullForm;
	}
	
	function getExamArticleTags(){
		return $this->examArticleTags;
	}

	function getCategoryName() {
		return $this->categoryName;
	}
	
	function getGradeType() {
		return $this->gradeType;
	}
	
	function getLastModifyDate() {
		return $this->lastModifyDate;
	}
	
	function getLastModifedBy() {
		return $this->lastModifedBy;
	}

	function getTileLink(){
		return $this->tileLink;
	}

	function getStatus() {
		return $this->status;
	}
	
	/*
	 * Returns array of discussion ids
	 */
	function getDiscussionsIds() {
		$commaSeparatedDiscussionsIds = $this->discussionIds;
		$discussionIdsArray = explode(',', $commaSeparatedDiscussionsIds);
		return $discussionIdsArray;
	}
	
	/*
	 * Sets section information: description and priority for each section/tile
	 */
	function setSectionInfo($sectionData) {
		$this->sectionInfo = $sectionData;
	}
	
	/*
	 * Returns simple array of sections with description and priority for each section/tile
	 */
	function getSectionInfo() {
		return $this->sectionInfo;
	}
	
	/*
	 * Sets homepage data that contains array of wiki fields
	 */
	function setHomepageData(Wiki $wiki) {
		$this->homepage[] = $wiki;
	}
	
	/*
	 * Returns array of Wiki objects
	 */
	function getHompageData() {
		return $this->homepage;
	}
	
	/*
	 * Sets syllabus data which is a single wiki field
	 */
	function setSyllabus(Wiki $wiki) {
		$this->syllabus = $wiki;
	}
	
	/*
	 * Returns single Wiki object
	 */
	function getSyllabus() {
		return $this->syllabus;
	}
	
	/*
	 * Sets important dates that is an array of ExamPageDate type object
	 */
	function setImportantDates(ExamPageDate $date) {
		$this->importantDates[] = $date;
	}
	
	/*
	 * Returns array of examPageDate type object
	 */
	function getImportantDates() {
		return $this->importantDates;
	}
	
	/*
	 * Sets data under Results section with:
	 * 		- single ExamPageDate object
	 * 		- array of wiki fields
	 * 		- topper interviews, which is also array of wiki fields
	 */
	function setResultsData(Wiki $wiki, ExamPageDate $date, $key) {
		switch ($key) {
			case 'Declaration Date':
				$this->results[$key] = $date;
				break;
			
			case 'Exam Analysis':
				$this->results[$key] = $wiki;
				break;
			
			case 'Exam Reaction':
				$this->results[$key] = $wiki;
				break;
			
			case 'Topper interview':
				$this->results[$key][] = $wiki;
				break;
		}
	}
	
	/*
	 * Returns formatted array with keys:
	 * 		['declarationDate'] => contains single date object
	 * 		['otherFields'] => contains array of wiki objects
	 * 		['interviews'] => contains array of wiki objects
	 */
	function getResultsData() {
		return $this->results;
	}
}