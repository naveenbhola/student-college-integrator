<?php

class SolrServer {
	
	public function getNewSolrUrl($type, $action="update") {
		$type = trim($type);
		if(strlen($type) < 0 || strlen($action) < 0) {
			return '';
		} else {
			switch($type){
				
				case 'question':
					$solrUrl = SOLR_NEW_INSTI_SELECT_URL_BASE;
					if($action == "update"){
						$solrUrl = SOLR_NEW_INSTI_UPDATE_URL_BASE;
					}
					break;
			
				default:
					$solrUrl = SOLR_NEW_INSTI_SELECT_URL_BASE;
					if($action == "update"){
						$solrUrl = SOLR_NEW_INSTI_UPDATE_URL_BASE;
					}
					break;
			}
			return $solrUrl;
		}
	}
	
	public function getSolrUrl($type, $action="update") {
		$type = trim($type);
		if(strlen($type) < 0 || strlen($action) < 0) {
			return '';
		} else {
			switch($type){
				case 'course':
					$solrUrl = SOLR_INSTI_SELECT_URL_BASE;
					if($action == "update"){
						$solrUrl = SOLR_INSTI_UPDATE_URL_BASE;
					}
					break;

				case 'spellcheck':
                                        $solrUrl = SOLR_SPELLCHECK_URL;
                                        break;			
	
				case 'question':
					$solrUrl = SOLR_INSTI_SELECT_URL_BASE;
					if($action == "update"){
						$solrUrl = SOLR_INSTI_UPDATE_URL_BASE;
					}
					break;
				
				case 'article':
					$solrUrl = SOLR_INSTI_SELECT_URL_BASE;
					if($action == "update"){
						$solrUrl = SOLR_INSTI_UPDATE_URL_BASE;
					}
					break;

				case 'mlt':
					$solrUrl = SOLR_INSTI_SELECT_URL_MLT;
					if($action == "update"){
						$solrUrl = SOLR_INSTI_UPDATE_URL_BASE;
					}
					break;

				case 'collegereview':

					$solrUrl = SOLR_CR_SELECT_URL_BASE;
					if($action == "update"){
						$solrUrl = SOLR_CR_UPDATE_URL_BASE;
					}
					break;

				case 'collegeshortlist':
					$solrUrl = SOLR_CSHORTLIST_SELECT_URL_BASE;
					if($action == "update"){
						$solrUrl = SOLR_CSHORTLIST_UPDATE_URL_BASE;
					}
					break;
				default:
					$solrUrl = SOLR_INSTI_SELECT_URL_BASE;
					if($action == "update"){
						$solrUrl = SOLR_INSTI_UPDATE_URL_BASE;
					}
					break;
			}
			return $solrUrl;
		}
	}
		
	public function curl($url, $content = '', $getCurlHeader = '', $page = '') {
        $time_start = microtime_float(); $start_memory = memory_get_usage();
		//ini_set('memory_limit','2500M');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
		if(strpos($url,'/shiksha') === FALSE && strpos($url,'/abroad') === FALSE && strpos($url,'/select') === FALSE && strpos($url,'/spellcheck') === FALSE && strpos($url,'/spell') === FALSE) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));	
		}
		else {
			curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: application/x-www-form-urlencoded"));
		}
        $curlHeader = 0;
        if($getCurlHeader){
            $curlHeader = 1;
        }
        curl_setopt($ch, CURLOPT_HEADER, $curlHeader);
        curl_setopt($ch, CURLOPT_TIMEOUT, 8);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        //curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$curlStart = microtime(true);
        $result = curl_exec($ch);
		$curlEnd = microtime(true);
		$culrRetcode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        curl_close($ch);
		
		
		if($culrRetcode != 200) {
          //Stoping Alert on error
            $this->_initiateErrorReportingLib(); 	
			$culrRetcode = empty($culrRetcode) ? 0 : $culrRetcode;
         	$result = empty($result) ? "NO_DATA_FOUND" : $result;
         	$this->listingErrorReportingLib->registerToSendSolrError($url, $culrRetcode,$result);
         	$this->listingErrorReportingLib->sentErrorAlert();
	 	}
		error_log("SearchQueryTime:: ".$url." :: ".($curlEnd-$curlStart)." ::SearchQueryReferrer:: ".$_SERVER['REQUEST_URI']);
        if($content != "") {
            $url = $url."?".$content;
        }
        //_p($solrUrl);
        //$this->logSolrQueryForMonitoring($url, $page, getLogTimeMemStr($time_start, $start_memory));
	
        return $result;
    }

    function logSolrQueryForMonitoring($solrUrl, $page, $timeString) {

        $timeTakenBySolr = $this->get_string_between($timeString, "Time taken: ", " ms");
        if((int) $timeTakenBySolr <= 10) {
            $bucket = 1;
        }
        elseif ((int) $timeTakenBySolr > 10 && (int) $timeTakenBySolr <= 50) {
            $bucket = 2;
        }
        elseif ((int) $timeTakenBySolr > 50 && (int) $timeTakenBySolr <= 100) {
            $bucket = 3;
        }
        elseif ((int) $timeTakenBySolr > 100 && (int) $timeTakenBySolr <= 500) {
            $bucket = 4;
        }
        elseif ((int) $timeTakenBySolr > 500 && (int) $timeTakenBySolr <= 1000) {
            $bucket = 5;
        }
        elseif ((int) $timeTakenBySolr > 1000) {
            $bucket = 6;
        }

        // if($bucket == 5 || $bucket == 6) {
            //error_log("Date: ".date('y-m-d H:i:s')." | Server: 91 | Page: ".$page." | Query: ".$solrUrl." | ".$timeString."\n\n", 3, "/data/log_solr_query_time_".$bucket.".log");
            error_log("SLOWPAGES | Date: ".date('y-m-d H:i:s')." | Server: 91 | Page: ".$page." | Query: ".$solrUrl." | ".$timeString);
        // }
    }

    function get_string_between($string, $start, $end){
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

    public function curl_mobile($url, $content = '', $getCurlHeader = '') {
		//ini_set('memory_limit','2500M');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (iPhone; CPU iPhone OS 5_0 like Mac OS X) AppleWebKit/534.46 (KHTML, like Gecko) Version/5.1 Mobile/9A334 Safari/7534.48.3");
        curl_setopt($ch, CURLOPT_COOKIE, "ci_mobile=mobile");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
		if(strpos($url,'/shiksha') === FALSE && strpos($url,'/abroad') === FALSE && strpos($url,'/select') === FALSE && strpos($url,'/spellcheck') === FALSE && strpos($url,'/spell') === FALSE) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));	
		}
		else {
			curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: application/x-www-form-urlencoded"));
		}
        $curlHeader = 0;
        if($getCurlHeader){
            $curlHeader = 1;
        }
        curl_setopt($ch, CURLOPT_HEADER, $curlHeader);
        curl_setopt($ch, CURLOPT_TIMEOUT, 8);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        //curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$curlStart = microtime(true);
        $result = curl_exec($ch);
		$curlEnd = microtime(true);
		$culrRetcode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        curl_close($ch);
		
		//echo $culrRetcode;
		
		if($culrRetcode != 200) {
          //Stoping Alert on error
            $this->_initiateErrorReportingLib(); 	
			$culrRetcode = empty($culrRetcode) ? 0 : $culrRetcode;
         	$result = empty($result) ? "NO_DATA_FOUND" : $result;
         	$this->listingErrorReportingLib->registerToSendSolrError($url, $culrRetcode,$result);
         	$this->listingErrorReportingLib->sentErrorAlert();
	 	}
		error_log("SearchQueryTime:: ".$url." :: ".($curlEnd-$curlStart)." ::SearchQueryReferrer:: ".$_SERVER['REQUEST_URI']);
	
        return $result;
    }
	
	public function leadSearchCurl($url,$content) {

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_VERBOSE, 0);

		curl_setopt($ch, CURLOPT_URL, $url);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');

		curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
		
		curl_setopt($ch, CURLOPT_TIMEOUT, 8);

		curl_setopt($ch, CURLOPT_POST, 1);

		$result = curl_exec($ch);

		$culrRetcode = curl_getinfo($ch,CURLINFO_HTTP_CODE);

		//error_log('xxxxxxxxxxxxxxxxxxxxxxxxx'.print_r(curl_getinfo($ch),true));
		//error_log('xxxxxxxxxxxxxxxxxxxxxxxxx'.print_r($result,true));
		//$fp = fopen('/tmp/mrx','a');
		//fwrite($fp,$result);
		//fclose($fp);

		curl_close($ch);

		if($culrRetcode != 200) {
            $this->_initiateErrorReportingLib(); 	
			$culrRetcode = empty($culrRetcode) ? 0 : $culrRetcode;
         	$result = empty($result) ? "NO_DATA_FOUND" : $result;
         	$this->listingErrorReportingLib->registerToSendSolrError($url, $culrRetcode,$result);
	 	}

		return $result;

    }

    public function MMMSearchCurl($url, $content) {

		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_VERBOSE, 0);

		curl_setopt($ch, CURLOPT_URL, $url);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');

		curl_setopt($ch, CURLOPT_POSTFIELDS, $content);

		curl_setopt($ch, CURLOPT_TIMEOUT, 20);

		curl_setopt($ch, CURLOPT_POST, 1);

		$result = curl_exec($ch);

		$culrRetcode = curl_getinfo($ch,CURLINFO_HTTP_CODE);

		curl_close($ch);

		if($culrRetcode != 200) {
            $this->_initiateErrorReportingLib(); 	
			$culrRetcode = empty($culrRetcode) ? 0 : $culrRetcode;
         	$result = empty($result) ? "NO_DATA_FOUND" : $result;
         	$this->listingErrorReportingLib->registerToSendSolrError($url, $culrRetcode,$result);
	 	}

		return $result;

    }

	
	public function indexDocuments($documents = array(), $type,$solr_version='old'){
		$this->logFileName = 'log_data_indexing_solr_'.date('y-m-d');
        $this->logFilePath = '/tmp/'.$this->logFileName;
        $responseList = array();
		if(is_array($documents) && !empty($documents)){
			foreach($documents as $key=>$document){
				$response = $this->makeSolrUpdateCall($document, $type, $solr_version);
				if($response == 0 && ($type == 'institute' || $type == 'course')) {
					error_log("Indexing failed -> Document - ".$key." - for this institute not indexed properly \n", 3, $this->logFilePath);
				}else if($response == 0 && ($type == 'collegereview')) {
					mail("pranjul.raizada@shiksha.com,abhinav.pandey@shiksha.com", "College review indexing failed", "College review index data : \n\n".print_r($documents,true));
				}else if($response == 0 && ($type == 'collegeshortlist')) {
					mail("pranjul.raizada@shiksha.com,abhinav.pandey@shiksha.com", "College Shortlist indexing failed", "College review index data : \n\n".print_r($documents,true));
				}
				array_push($responseList, $response);
			}
		}
		return $responseList;
	}
	
	public function deleteDocument($document, $type,$solr_version='old'){
		$response = $this->makeSolrUpdateCall($document, $type,$solr_version);
		return $response;
	}
	
	public function makeSolrUpdateCall($document, $type = "course",$solr_version='old') {

		$returnValue = array();
		$document = trim($document);
        if($solr_version=='old')    
        {
		    $url = $this->getSolrUrl($type, 'update');
        }
        else
        {
            $url = $this->getNewSolrUrl($type, 'update');
        }
		$url = trim($url);
		$solrResponse = false;
		$result = 0;
		if(strlen($document) > 0 && strlen($url) > 0){
			$solrResponse = $this->curl($url, $document, 1);
		}
		
		if($solrResponse) {
			if(preg_match('/200 OK/',$solrResponse)) {
				$result = 1;
			} else {
				if($type == 'collegereview'){
					mail("pranjul.raizada@shiksha.com,abhinav.pandey@shiksha.com", "College review indexing failed curl response", "College review index data : \n\n".print_r($solrResponse,true));
				}
				$result = 0;
			}
		}
		return $result;
	}
	private function _initiateErrorReportingLib() {
		$this->CI =& get_instance();
		$this->CI->load->library('listing/ListingErrorReportingLib');
		$this->listingErrorReportingLib = $this->CI->listingerrorreportinglib;
	}

}
