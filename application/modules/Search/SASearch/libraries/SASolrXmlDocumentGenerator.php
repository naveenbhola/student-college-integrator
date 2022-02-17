<?php

class SASolrXmlDocumentGenerator {
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
						$documentFields.= "<field name=\"$key\" ".(($key!='unique_id' && $updateFlag)?" update=\"set\" ":"")."><![CDATA[".htmlentities(strip_tags(ascii127Convert(html_entity_decode($value))))."]]></field>";
					}
				} else if(is_array($value)) {
					foreach($value as $individualVal) {
						$individualVal = trim($individualVal);
						if($individualVal != "") {
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
		
	public function getDocuments($indexDataList = array(),$updateFlag = false){
		$xmlDocuments = array();
		if(count($indexDataList) > 0){
			$dataList = $indexDataList;
			foreach($dataList as $data){
				$documentFieldXMLString = $this->getDocumentFields($data, $updateFlag);
				if($documentFieldXMLString != false){
					$boostFactor = 0;
					$documentXML = $this->getDocumentXMl($documentFieldXMLString, $boostFactor);
					//var_dump(htmlentities($documentXML));die;
					if($documentXML != false && $documentXML != ""){
						array_push($xmlDocuments, $documentXML);	
					}
				}
			}
		}
		//var_dump(($xmlDocuments));
		return $xmlDocuments;
	}
}