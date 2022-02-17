<?php

/*
 * Main entity that stores complete exampage data using ExamPageDate and Wiki entities
 */
class AbroadExamPage
{
    private $examPageId;
    private $examName;
    private $examId;
    private $examPageTitle;
    private $examPageDescription;
    private $examPageSummary;
    private $examPageURL;
    private $relatedDate;
    private $sections;
    private $examPageImageUrl;
    private $seoTitle;
    private $seoDescription;
    private $seoKeywords;
    private $is_downloadable;
    private $download_link;
    private $created_by;
    
    function __construct() {
    }
    
    function __set($property,$value) {
	    $this->$property = $value;
    }
    
    function getExamPageId() {
	    return $this->examPageId;
    }
    
    function getExamName() {
	    return $this->examName;
    }
    
    function getExamPageTitle() {
	    return $this->examPageTitle;
    }
    
    function getExamPageDescription() {
	    return $this->examPageDescription;
    }
    
    function getExamPageSummary() {
	    return $this->examPageSummary;
    }
    
    function getExamPageURL() {
	    return $this->examPageURL;
    }

    function getRelatedDate(){
	    return $this->relatedDate;
    }

    function getSections() {
	    return $this->sections;
    }
    
    function getExamPageImageUrl() {
	    return $this->examPageImageUrl;
    }
    
    function getSeoTitle() {
	    return $this->seoTitle;
    }
    
    function getSeoDescription() {
	    return $this->seoDescription;
    }
    
    function getSeoKeywords() {
	    return $this->seoKeywords;
    }
    
    function getExamId(){
	    return $this->examId;
    }
    
    function getDownloadLink(){
	    if($this->is_downloadable == 'yes'){
		return $this->download_link;
	    }
	    else{
		return false;
	    }
    }
    
    function getAuthorId(){
	    return $this->created_by;
    }
}