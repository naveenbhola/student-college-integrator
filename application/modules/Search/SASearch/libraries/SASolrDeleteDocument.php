<?php

class SASolrDeleteDocument {
	private $_ci;
	public function __construct(){
		$this->_ci = & get_instance();
		//$this->_ci->load->helper("search/SearchUtility", "", true);
	}

	public function getDeleteXMLByCourseId($id){
		$deleteXML = "";
		$deleteXML .= "<delete>";
			$deleteXML .= "<query>saCourseId:".$id." AND facetype:abroadlisting</query>";
			// course autosuggestor documents are managed separately
			//$deleteXML .= "<query>saAutosuggestCourseId:".$id." AND facetype:saAutosuggestor</query>";
			$deleteXML .= "<query>saAutosuggestCourseId:".$id." AND facetype:saLocationAutosuggestor</query>";	
			$deleteXML .= "<query>saAutosuggestCourseId:".$id." AND facetype:saExamAutosuggestor</query>";	
		$deleteXML .= "</delete>";
		return $deleteXML;
	}
	public function getDeleteXMLByUnivId($id){
		$deleteXML = "";
		$deleteXML .= "<delete>";
			$deleteXML .= "<query>saUnivId:".$id." AND facetype:abroadlisting</query>";
			$deleteXML .= "<query>saAutosuggestUnivId:".$id." AND facetype:saUnivAutosuggestor</query>";
			$deleteXML .= "<query>saAutosuggestUnivId:".$id." AND facetype:saLocationAutosuggestor</query>";	
			$deleteXML .= "<query>saAutosuggestUnivId:".$id." AND facetype:saExamAutosuggestor</query>";	
		$deleteXML .= "</delete>";
		return $deleteXML;
	}
	public function getCourseAutoSuggestorDeleteXML(){
		$deleteXML = "";
		$deleteXML .= "<delete>";
			$deleteXML .= "<query>facetype:saCourseAutosuggestor</query>";
		$deleteXML .= "</delete>";
		return $deleteXML;
	}

	public function getDeleteXMLByScholarshipId($id){
		$deleteXML = "";
		$deleteXML .= "<delete>";
			$deleteXML .= "<query>saScholarshipId:".$id." AND facetype:abroadScholarship</query>";
		$deleteXML .= "</delete>";
		return $deleteXML;
	}
}