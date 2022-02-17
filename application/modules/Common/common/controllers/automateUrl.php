<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *Description : Parse all the URL's from the current page and report. if found URL status (except 200), blank href of acnhor tag 
 or repeated domain URL or missing HTTPS 
 *URL - https://localshiksha.com/shiksha/index?autoUrl=default (exclude GNB, FOOTER)
 *URL - https://localshiksha.com/shiksha/index?autoUrl=homePage (include GNB,FOOTER)
 *NONE - ALLOW_AUTOURL_SCRIPT, varibale should be TRUE defined in shikshaConfig.php
 *@author: Akhter
 **/


class automateUrl extends MX_Controller
{

    function authCheck(){
    	if(ALLOW_AUTOURL_SCRIPT !== TRUE){
    		echo 'Invalid Request';exit();
    	}
    }

	function getStatus(){
		$this->authCheck();
		$fromWhere    = trim($this->input->post('from'));
		$url          = trim($this->input->post('url'));
		$param        = trim($this->input->post('param'));
		$rtype        = trim($this->input->post('requestType'));
		$scheme       = (trim($this->input->post('scheme')) == 'https:') ? 'https' : 'http';

		$statusCode   = $this->validateUrl($url, $rtype, $scheme);

		if($statusCode != 200){
			echo $this->prepareReport($fromWhere, $param, $url, $statusCode);
		}
		exit();
	}
	
	function validateUrl($url, $rtype, $scheme){
		$parseUrl = parse_url($url);
		if(empty($url)){
			$e = ($rtype) ? $rtype.'=" "'.'&nbsp; Missing URL' : 'href=" "'.'&nbsp; Missing URL';
		}else if(!empty($url) && preg_match('/^(\/\/)[a-zA-Z]/', $url)){ //if url start with //
			$e = 200;
		}else if(($url == $scheme.'://'.$_SERVER['HTTP_HOST']) || ($url == ($scheme.'://'.$_SERVER['HTTP_HOST'].'/'))){
			$e = 'Missing URL';	
		}else if(empty($parseUrl['host'])){
			$e =  'Missing Domain / URL';
		}else if((stripos($parseUrl['host'],'shiksha') == FALSE && !is_int(stripos($parseUrl['host'],'shiksha'))) && $parseUrl['scheme'] != $scheme){
    		$e =  'Third Party URL\'s Scheme - HTTPS';
		}else if($parseUrl['scheme'] != $scheme){
			$e =  'Scheme - '.strtoupper($scheme);
		}else if(is_int(stripos($parseUrl['host'],'shiksha')) && !preg_match('/^http(s)?:\/\/[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(\/.*)?$/i', $url)){ // validate shiksha url's
    		$e =  'Incorrect URL';
		}

		if(($rtype == 'iframe') || !empty($e)){
			$statusCode = ($e) ? $e : 200; // do not curl call if url comes from IFRAME
		}else{
			$statusCode = $this->doReuest($url);
		}
		return $statusCode;
	}

	function doReuest($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, true);    // we wan't headers 
	 	curl_setopt($ch, CURLOPT_NOBODY, true);    // we don't need body 
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 10000);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS,10000);
        $result = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if($result === false) { // Check if any error occurred
                $httpcode = 'Request Timed Out - 10s';
        }
        curl_close($ch);
        return $httpcode;
	}

	function prepareReport($fromWhere, $param, $url, $statusCode){
		$html = '<div style="margin: 10px 0px 0px 15px;font-size: 14px">';
		$strArr = explode(':', $param);
		foreach ($strArr as $key => $value) {
			$html.= '&nbsp;'.$value.' &gt; ';
		}
		$html.='<span style="color:blue">'.$url.'</span>&nbsp;&nbsp; <span style="color:red;">  &nbsp;Error:&nbsp; '.$statusCode.'</span>';
		$html.='</div>';
		return $html;
	}
}
?>
