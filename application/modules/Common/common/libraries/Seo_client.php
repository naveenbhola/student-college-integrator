<?php

class Seo_client{

    var $CI_Instance;
    var $cacheLib;

    function initSeo()
    {
		$this->CI_Instance = & get_instance();
        $this->CI_Instance->load->helper('url');
        $this->CI_Instance->load->library('xmlrpc');
		// SEO_SEARCH_SERVER_URL
        $this->CI_Instance->xmlrpc->server(SEO_SEARCH_SERVER_URL,SEO_SERVER_PORT);
	$this->CI_Instance->load->library('cacheLib');
	$this->cacheLib = new cacheLib();
    }

    function getSeoUrlNewSchema($type_id ,$identifier){
        $this->initSeo();
	//$key=md5('getSeoUrlNewSchema'.$type_id,$identifier);
	//if($this->cacheLib->get($key)=='ERROR_READING_CACHE' || ($identifier!='question' && $identifier!='discussion') ){
		$this->CI_Instance->xmlrpc->method('getSeoUrlNewSchema');
		$request = array(array($type_id,'int'),array($identifier,'string'));
		$this->CI_Instance->xmlrpc->request($request);
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
		    return ($this->CI_Instance->xmlrpc->display_error());
		} else {
		    $response = ($this->CI_Instance->xmlrpc->display_response());
		    $response = json_decode($response,true);
		    //if($identifier=='question' || $identifier=='discussion')
		    //	$this->cacheLib->store($key,$response);
		    return $response;
		}
	//}else{
	//	return $this->cacheLib->get($key);
	//}
   }

	function getTitleFromId($type_id,$identifier){
        $this->initSeo();
        $this->CI_Instance->xmlrpc->method('getTitleFromId');
        $request = array(array($type_id,'int'),array($identifier,'string'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        } else {
            $response = ($this->CI_Instance->xmlrpc->display_response());
            return json_decode($response,true);
        }
   }

  function getEventLocationAndOthers($type_id,$identifier){
        $this->initSeo();
        $this->CI_Instance->xmlrpc->method('getEventLocationAndOthers');
        $request = array(array($type_id,'int'),array($identifier,'string'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        } else {
            $response = ($this->CI_Instance->xmlrpc->display_response());
            return json_decode($response,true);
        }
   }

  function getTitleAndLocationForInstitute($type_id,$identifier){
        $this->initSeo();
	$key=md5('getListingDetailsForURL1'.$type_id,$identifier);
	if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
		$this->CI_Instance->xmlrpc->method('getTitleAndLocationForInstitute');
		$request = array(array($type_id,'int'),array($identifier,'string'));
		$this->CI_Instance->xmlrpc->request($request);
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
		    return ($this->CI_Instance->xmlrpc->display_error());
		} else {
		    $response = ($this->CI_Instance->xmlrpc->display_response());
		    $response = json_decode($response,true);
		    $this->cacheLib->store($key,$response);
		    return $response;
		}
	}else{
		return $this->cacheLib->get($key);
	}
   }

  function getTitleAndLocationForCourse($type_id,$identifier){
        $this->initSeo();
	$key=md5('getListingDetailsForURL1'.$type_id,$identifier);
	if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
		$this->CI_Instance->xmlrpc->method('getTitleAndLocationForCourse');
		$request = array(array($type_id,'int'),array($identifier,'string'));
		$this->CI_Instance->xmlrpc->request($request);
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
		    return ($this->CI_Instance->xmlrpc->display_error());
		} else {
		    $response = ($this->CI_Instance->xmlrpc->display_response());
		    $response = json_decode($response,true);
		    $this->cacheLib->store($key,$response);
		    return $response;
		}
	}else{
		return $this->cacheLib->get($key);
	}
   }

  function getURLFromDB($type_id,$identifier){
        $this->initSeo();
        $this->CI_Instance->xmlrpc->method('getURLFromDB');
        $request = array(array($type_id,'int'),array($identifier,'string'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        } else {
            $response = ($this->CI_Instance->xmlrpc->display_response());
            return json_decode($response,true);
        }
   }

  function getURLForSitemap($appId, $type ,$start,$count,$noOfDays,$typeSitemap){
        $this->initSeo();
        $this->CI_Instance->xmlrpc->method('getURLForSitemap');
        $request = array(array($appId,'int'),array($type,'string'),array($start,'int'),array($count,'int'),array($noOfDays,'int'),array($typeSitemap,'string'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        } else {
	    $response = ($this->CI_Instance->xmlrpc->display_response());
	    $res = json_decode(gzuncompress(base64_decode($response)),true);
	    return $res;
        }
   }


}

?>
