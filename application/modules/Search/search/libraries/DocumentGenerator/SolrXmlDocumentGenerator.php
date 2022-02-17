<?php

class SolrXmlDocumentGenerator {
	private $_ci;
	public function __construct(){
		$this->_ci = & get_instance();
		$this->_ci->load->helper("search/SearchUtility", "", true);
	}
	
	private function getDocumentXMl($documentFieldXMLString = "", $boost = NULL){
		$document = false;
		if($documentFieldXMLString != '') {
			$head = "<doc>";
			if($boost != NULL){
				$head = "<doc boost=\"".floatval($boost)."\">";
			}
			$documentHead = "<add>".$head;
			$documentTail = "</doc></add>";
			$document = $documentHead . $documentFieldXMLString . $documentTail;
		}
		return $document;
	}
	
	private function getDocumentFields($data = array(), $updateFlag){
		$documentFields = '';
		if(count($data) > 0) {
			foreach($data as $key=>$value) {
				if(!is_array($value)) {
					$value = trim($value);
					$key = trim($key);
					if($value != ""){
						//$documentFields.= "<field name=\"$key\"><![CDATA[".htmlentities(strip_tags(ascii127Convert(html_entity_decode($value))))."]]></field>";
						$documentFields .= "<field name=\"$key\" ".(($key!='unique_id' && $updateFlag)?" update=\"set\" ":"")."><![CDATA[".htmlentities(strip_tags(ascii127Convert(html_entity_decode($value))))."]]></field>";
					}else if($updateFlag){
						$documentFields.= "<field name=\"$key\" ".(($key!='unique_id' && $updateFlag)?" update=\"set\" null=\"true\" ":"")."/>";
					}
				} else if(is_array($value)) {
					foreach($value as $individualVal) {
						$individualVal = trim($individualVal);
						if($individualVal != "") {
							//$documentFields .= "<field name=\"$key\"><![CDATA[".htmlentities(strip_tags(ascii127Convert(html_entity_decode($individualVal))))."]]></field>";
							$documentFields .= "<field name=\"$key\" ".($key!='unique_id' && $updateFlag?" update=\"set\" ":"")."><![CDATA[".htmlentities(strip_tags(ascii127Convert(html_entity_decode($individualVal))))."]]></field>";
						}
					}
				}
			}
		}
		$documentFields = trim($documentFields);
		if(strlen($documentFields) <= 0){
			$documentFields = false;
		}
		return $documentFields;
	}
	
	private function getBoostByPacktype($packtype) {
		if(SOLR_BUCKET) { // defined in shikshaConfig
			return(1);
		} else {
			$boostValue = $this->getPackTypeBoostFactor($packtype);
			if($boostValue){
				return $boostValue;
			} else {
				return(1);
			}
		}
	}
	
	public function getPackTypeBoostFactor($packType){
		$returnValue = false;
		$packtypeBoostMapping = array(0=>1.2, 1=>3.0, 2=>3.0, 3=>3.0, 4=>3.0, 5=>3.0, 6=>3.0, 7=>1.3, 8=>3.0, 9=>3.0, 10=>3.0, 11=>1.2);
		if(array_key_exists($packType, $packtypeBoostMapping)){
			$returnValue = $packtypeBoostMapping[$packType];
		}
		return $returnValue;
	}
	
	public function getDocuments($indexDataList = array(),$updateFlag = false){
		$xmlDocuments = array();
		if(count($indexDataList) > 0){
			$dataList = $indexDataList;
			foreach($dataList as $data){
				$documentFieldXMLString = $this->getDocumentFields($data, $updateFlag);
				if($documentFieldXMLString != false){
					$boostFactor = 0;
					if(!empty($data['institute_pack_type']) && isset($data['institute_pack_type'])){
						$boostFactor = $this->getBoostByPacktype($data['institute_pack_type']);	
					}
					$documentXML = $this->getDocumentXMl($documentFieldXMLString, $boostFactor);
					if($documentXML != false && $documentXML != ""){
						array_push($xmlDocuments, $documentXML);	
					}
				}
			}
		}
		return $xmlDocuments;
	}

	public function getNLDocuments($indexDataList = array(),$updateFlag = false){
		$xmlDocument = array();
		if(count($indexDataList) > 0){
			$dataList = $indexDataList;
			$docPartMain = "";
			foreach($dataList as $data){
				$documentFieldXMLString = $this->getDocumentFields($data, $updateFlag);
				if($documentFieldXMLString != false){
					$documentPart = $this->getDocPart($documentFieldXMLString);
					if($documentPart != false && $documentPart != ""){
						$docPartMain = $docPartMain.$documentPart;
					}
				}
			}
			$xmlDocument = $this->getFinalDoc($docPartMain);
		}
		return $xmlDocument;
	}

	public function getFinalDoc($documents = ""){
		$document = false;
		if($documents != '') {
			$head = "<add>";
			$tail = "</add>";
			$document = $head.$documents.$tail;
		}
		return $document;
	}

	public function getDocPart($documentFieldXMLString=''){
		$documentPart = false;
		if($documentFieldXMLString != '') {
			$partHead = "<doc>";
			$partTail = "</doc>";
			$documentPart = $partHead.$documentFieldXMLString.$partTail;
		}
		return $documentPart;
	}
}