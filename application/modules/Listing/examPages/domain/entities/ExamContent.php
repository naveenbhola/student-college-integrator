<?php

/*
 * Main entity that stores complete exampage data using ExamPageDate and Wiki entities
 */
class ExamContent
{
	private $homepage;
	private $pattern;
	private $syllabus;
	private $importantdates;
	private $examdates;
	private $files;
	private $results;
	private $cutoff;
	private $admitcard;
	private $answerkey;
	private $applicationform;
	private $counselling;
	private $samplepapers;
	private $slotbooking;
	private $sectionOrder;
        private $vacancies;
        private $news;
        private $callletter;
    private $preptips;
	
	function __construct() {
	}
    
    	function __set($property,$value) {
		$this->$property = $value;
	}		

	function setHomeContent(Wiki $wiki){
		$this->homepage[] = $wiki;
	}

	function setPatternContent(Wiki $wiki){
		$this->pattern[] = $wiki;
	}

	function setSyllabusContent(Wiki $wiki){
		$this->syllabus[] = $wiki;
	}

	function setImportantDateContent(Wiki $wiki){
		$this->importantdates[] = $wiki;
	}
	
	function setResultContent(Wiki $wiki){
		$this->results[] = $wiki;
	}

	function setCutoffContent(Wiki $wiki){
		$this->cutoff[] = $wiki;
	}

	function setAdmitcardContent(Wiki $wiki){
		$this->admitcard[] = $wiki;
	}

	function setAnswerkeyContent(Wiki $wiki){
		$this->answerkey[] = $wiki;
	}

	function setApplicationformContent(Wiki $wiki){
		$this->applicationform[] = $wiki;
	}

	function setCounsellingContent(Wiki $wiki){
		$this->counselling[] = $wiki;
	}

	function setSlotbookingContent(Wiki $wiki){
		$this->slotbooking[] = $wiki;
	}

	function setSamplepapersContent(Wiki $wiki){
		$this->samplepapers[] = $wiki;
	}

	function setContentDates(){

	}

	function setFileData(ExamFile $examFile){	
			$this->files[$examFile->getFileType()][] = $examFile;
	}

	function setEventDates(ExamDate $examDate){
		$this->examdates[$examDate->getSectionName()][] = $examDate;
	}

	function getHomepageContent(){
		return $this->homepage;
	}

	function getPatternContent(){
		return $this->pattern;
	}

	function getSyllabusContent(){
		return $this->syllabus;
	}

	function getImportantDates(){
		return $this->importantdates;
	}

	function getExamDates(){
		return $this->examdates;
	}

	function getFiles(){
		return $this->files;
	}

	function getResults(){
		return $this->results;
	}

	function getCutOff(){
		return $this->cutoff;
	}

	function getAdmitCard(){
		return $this->admitcard;
	}

	function getAnswerKey(){
		return $this->answerkey;
	}

	function getApplicationform(){
		return $this->applicationform;
	}

	function getCounselling(){
		return $this->counselling;
	}

	function getSamplePapers(){
		return $this->samplepapers;
	}

	function getSlotBooking(){
		return $this->slotbooking;
	}

	function setSectionOrder($sectionData){
		$this->sectionOrder = $sectionData;
	}

	function getSectionOrder(){
		return $this->sectionOrder;
	}

	function setVacanciesContent(Wiki $wiki){
                $this->vacancies[] = $wiki;
        }

        function setNewsContent(Wiki $wiki){
                $this->news[] = $wiki;
        }

        function setCallletterContent(Wiki $wiki){
                $this->callletter[] = $wiki;
        }

        function setPreptipsContent(Wiki $wiki){
                $this->preptips[] = $wiki;
        }

        function getVacancies(){
                return $this->vacancies;
        }

        function getNews(){
                return $this->news;
        }

        function getCallletter(){
                return $this->callletter;
        }

        function getPreptips(){
                return $this->preptips;
        }	
}
