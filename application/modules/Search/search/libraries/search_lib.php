<?php

class search_lib
{
	function search_curl($url,$q,$start,$rows,$qt)
	{
		if($url=="")
		{
			$url="http://localhost:8983/solr/select";
			$query_url=$url.'?q='.$q.'&start='.$start.'&qt='.$qt.'&rows='.$rows.'&indent=on';
		}
		else
		{
			$query_url=$url;
		}
		error_log("Shivam final query URL:".$query_url);
		$curl = curl_init();
		// Setup headers - I used the same headers from Firefox version 2.0.0.6
		// below was split up because php.net said the line was too long. :/
		$header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
		$header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
		$header[] = "Cache-Control: max-age=0";
		$header[] = "Connection: keep-alive";
		$header[] = "Keep-Alive: 300";
		$header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
		$header[] = "Accept-Language: en-us,en;q=0.5";
		$header[] = "Pragma: "; // browsers keep this blank.

		curl_setopt($curl, CURLOPT_URL, $query_url);
		curl_setopt($curl, CURLOPT_USERAGENT, 'Googlebot/2.1 (+http://www.google.com/bot.html)');
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_REFERER, 'http://www.google.com');
		curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate');
		curl_setopt($curl, CURLOPT_AUTOREFERER, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);

		$html = curl_exec($curl); // execute the curl command

		curl_close($curl); // close the connection

		//error_log_shiksha("Shirish: ".$html);

		return $html; // and finally, return $html
	}

	function xml_curl($url,$content)
	{

		$ch = curl_init(); // initialize curl handle

		curl_setopt($ch, CURLOPT_VERBOSE, 1); // set url to post to
		curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
		curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 40); // times out after 4s
		curl_setopt($ch, CURLOPT_POSTFIELDS, $content); // add POST fields
		curl_setopt($ch, CURLOPT_POST, 1);

		$result = curl_exec($ch); // run the whole process

		return $result;
	}

    function convert_searchxml($a)
    {
        $xml_head="<add>\n<doc>\n";
        $xml_tail="</doc>\n</add>\n";
        $xml_content="";
        foreach ($a as $k => $v) 
        {
            $xml_content.="<field name=\"$k\">$v</field>\n";
        }
        $xml_doc=$xml_head.$xml_content.$xml_tail;
        return $xml_doc;
    }
	
	function getDocumentFieldsAsXMLString($fields = array(), $ignoreFields = array()) {
		error_log("PANKY in getDocumentFieldsAsXMLString");
		$returnValue = false;
		if(sizeof($fields) > 0) {
			foreach($fields as $key=>$value) {
				if(!in_array($key, $ignoreFields)){
					if(!is_array($value) && $value != '') {
						$returnValue.= "<field name=\"$key\"><![CDATA[".htmlentities(strip_tags($this->ascii127Convert(html_entity_decode($value))))."]]></field>"; 
					} else if(is_array($value)) {
						foreach($value as $individualVal) {
							if($individualVal != '') {
								$returnValue .= "<field name=\"$key\"><![CDATA[".htmlentities(strip_tags($this->ascii127Convert(html_entity_decode($individualVal))))."]]></field>";
							}
						}	
					}	
				}
			}
		}
		error_log("PANKY leaving getDocumentFieldsAsXMLString");
		return $returnValue;
	}
	
	/**
	 * ascii127Convert is a function that removes all charecters that fall outside the range of 32-127
	 * @Input: string
	 * @Output: formatted string with no values in ascii range 32-127
	 */
	function ascii127Convert($string)
	{
		return preg_replace('/[^\x20-\x7F]/e', ' ', $string);
	}

	function getDocumentAsXMLString($fieldsXmlContent = '', $boost = NULL) {
		error_log("PANKY in getDocumentAsXMLString");
		$document = false;
		if($fieldsXmlContent != '') {
			$head = "<doc>";
			if($boost != NULL){
				$head = "<doc boost=\"".floatval($boost)."\">";
			}
			$documentHead = "<add>".$head;
			$documentTail = "</doc></add>";
			$document = $documentHead . $fieldsXmlContent . $documentTail;	
		}
		return $document;
	}
	
	/*TODO: this is a generic function should be moved to helper class*/
	function getSolrUrlByType($type, $action="update") {
		error_log("PANKY in getSolrUrlByType");
		$type = trim($type);
		if(strlen($type) < 0 || strlen($action) < 0) {
			return false;
		} else {
			switch($type){
				case 'course':
				case 'institute':
					$solrUrl = SOLR_INSTI_SELECT_URL_BASE;
					if($action == "update"){
						$solrUrl = SOLR_INSTI_UPDATE_URL_BASE;
					}
					break;
				case 'blog':
				case 'question':
				case 'msgbrd':
					$solrUrl = SOLR_SELECT_URL_BASE;
					if($action == "update"){
						$solrUrl = SOLR_UPDATE_URL_BASE;
					}
					break;
				default:
					$solrUrl = SOLR_SELECT_URL_BASE;
					if($action == "update"){
						$solrUrl = SOLR_UPDATE_URL_BASE;
					}
					break;
			}
			return $solrUrl;
		}
	}

	function makeSolrCurl($xmlContent = '', $url = '') {
		error_log("PANKY in makeSolrCurl");
		$returnValue = array();
		$xmlContent = trim($xmlContent);
		$url = trim($url);
		$solrResponse = false;
		$result = false;
		if(strlen($xmlContent) > 0 && strlen($url) > 0){
			$response = $this->xml_curl($url, $xmlContent);
		}
		if($response) {
			if(preg_match('/<int name="status">0<\/int>/',$response)) {
				$result = 1;
			} else {
				$result = 0;
			}
		}
		$returnValue['solr_response'] = $response;
		$returnValue['result'] = $result;
		return $returnValue;
	}
	
	public function getSearchSchemaFieldsWithBoost(){
		$fieldsWithBoosts = array();
		$fieldsWithBoosts['countryList'] = 20;
		$fieldsWithBoosts['cityList'] = 20;
		$fieldsWithBoosts['title'] = 50;
		$fieldsWithBoosts['authorName'] = 5;
		$fieldsWithBoosts['courseType'] = 5;
		$fieldsWithBoosts['levels'] = 5;
		$fieldsWithBoosts['abbr'] = 20;
		$fieldsWithBoosts['hack'] = NULL;
		$fieldsWithBoosts['tags'] = 5;
		$fieldsWithBoosts['hiddenTags'] = 0;
		$fieldsWithBoosts['instituteList'] = NULL; 
		$fieldsWithBoosts['instituteName'] = 500;
		$fieldsWithBoosts['courseTitle'] = 1500;
		return $fieldsWithBoosts;
	}
	
	public function getQERFields(){
		$QERFields = array('courseLevel', 'cType', 'sduration', 'countryId', 'state', 'rawTextQuery', 'cityId', 'ldb_course_id');
		return $QERFields;
	}
	
	public function getValidQERFields(){
		$QERFields = array('courseLevel', 'cType', 'sduration', 'countryId', 'state', 'rawTextQuery', 'cityId', 'ldb_course_id', 'instituteId');
		return $QERFields;
	}
	
	public function getURLParamValue($url, $paramKeyArray = NULL, $excludeValue = array()){
		$paramValueArr = false;
		$validFields = array();
		$validFields = $this->getValidQERFields();
//		error_log("15mar IN FUNCTION getUrlParamValue valid fields " . print_r($validFields, true));
		if($paramKeyArray != NULL){
			if(!is_array($paramKeyArray)){
				$paramKeyArray = (array)$paramKeyArray;
			}
			if(!is_array($excludeValue)){
				$excludeValue = (array)$excludeValue;
			}
			$temp = explode("?", $url);
			$remainingURL = $temp[1];
			$explodedParamArray = explode("&", $remainingURL);
			if(!empty($explodedParamArray)){
				foreach($explodedParamArray as $k=>$v){
					$valueExploded = explode("=", $v);
					if(in_array($valueExploded[0], $paramKeyArray)){
						if(!in_array($valueExploded[1], $excludeValue)){
							if($valueExploded[0] == "fq"){ //TO DO: dirty check, this should be handled elsewhere. Only see this if interested in fq param else skip this check 
								$tempValueExploded = explode(":", $valueExploded[1]);
								if(in_array($tempValueExploded[0], $validFields)){
									$paramValueArr[$valueExploded[0]][] = $valueExploded[1];
								}
							} else {
								$paramValueArr[$valueExploded[0]][] = $valueExploded[1];
							}
						}
					}
				}
			}
		}
		return $paramValueArr;
	}
	
	public function getURLParamString($paramsValueArray = array()){
		$urlParamString = "";
		if(is_array($paramsValueArray) && !empty($paramsValueArray)){
			foreach($paramsValueArray as $key=>$value){
				$tempValueString = "";
				foreach($value as $k=>$v){
					$tempValueString .= $key."=".$v."&";
				}
				$urlParamString .= $tempValueString;
			}
		}
		return $urlParamString;
	}
}
?>
