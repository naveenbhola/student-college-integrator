<?php
class LmsLib 
{
/*

Copyright 2007 Info Edge India Ltd

$Rev::               $:  Revision of last commit
$Author: build $:  Author of last commit
$Date: 2010-03-05 12:55:33 $:  Date of last commit
$Id: LmsLib.php,v 1.17 2010-03-05 12:55:33 build Exp $: 
*/

function init()
{
error_log_shiksha('Into the client');
$this->CI = & get_instance();
$this->CI->load->helper('url');
$this->CI->load->library('xmlrpc');
$this->CI->xmlrpc->set_debug(0);
$this->CI->xmlrpc->server(LMS_SERVER_URL, LMS_SERVER_PORT);
}

function initRead()
{
error_log_shiksha('Into the client');
$this->CI = & get_instance();
$this->CI->load->helper('url');
$this->CI->load->library('xmlrpc');
$this->CI->xmlrpc->set_debug(0);
$this->CI->xmlrpc->server(LMS_SERVER_READ_URL, LMS_SERVER_READ_PORT);
}

function getRegisteredData($regId)
{
    $this->init();
    $this->CI->xmlrpc->method('getRegisteredData');
    $request = array($regId,'string');
    $this->CI->xmlrpc->request($request);	
    if ( ! $this->CI->xmlrpc->send_request())
    {
        return  $this->CI->xmlrpc->display_error();
    }
    else
    {
        return unserialize(base64_decode($this->CI->xmlrpc->display_response()));
    } 	
}

function registerStudent($data)
{
    $this->init();
    $this->CI->xmlrpc->method('registerStudent');
    $request = array($data,'string');
    $this->CI->xmlrpc->request($request);	
    if ( ! $this->CI->xmlrpc->send_request())
    {
        return  $this->CI->xmlrpc->display_error();
    }
    else
    {
        return $this->CI->xmlrpc->display_response();
    } 	
}

function registerClient($data)
{
    $this->init();
    $this->CI->xmlrpc->method('registerClient');
    $request = array($data,'string');
    $this->CI->xmlrpc->request($request);	
    if ( ! $this->CI->xmlrpc->send_request())
    {
        return  $this->CI->xmlrpc->display_error();
    }
    else
    {
        return $this->CI->xmlrpc->display_response();
    } 	
}


function adduser($data)
{
		$this->init();
		error_log_shiksha('In LMS the client');
        $this->CI->xmlrpc->method('adduser');
        $request = array(array($data,'struct'));

		$this->CI->xmlrpc->request($request);	

		if ( ! $this->CI->xmlrpc->send_request())
		{
		return  $this->CI->xmlrpc->display_error();
		}
		else
		{
		 return $this->CI->xmlrpc->display_response();
		} 	
}

function insertLead($appID,$requestArray){
    $this->init();
    $this->CI->xmlrpc->method('insertLead');
    $request = array(array($appID,'int'),array($requestArray,'struct'));
    $this->CI->xmlrpc->request($request);
    if ( ! $this->CI->xmlrpc->send_request()){
        return ($this->CI->xmlrpc->display_error());
    }else{
        return ($this->CI->xmlrpc->display_response());
    }
}
function insertTempLead($appID,$requestArray){
    $this->init();
    //error_log("hellos1". print_r($requestArray,true));
    $this->CI->xmlrpc->method('tempLmsRequest');
    $request = array(array($appID,'int'),array($requestArray,'struct'));
    $this->CI->xmlrpc->request($request);
    if ( ! $this->CI->xmlrpc->send_request()){
        return ($this->CI->xmlrpc->display_error());
    }else{
        return ($this->CI->xmlrpc->display_response());
    }
}

function generateBulkLead($appID,$requestArray){
    $this->init();
    $this->CI->xmlrpc->method('generateBulkLead');
    $request = array(array($appID,'int'));
    $this->CI->xmlrpc->request($request);
    if ( ! $this->CI->xmlrpc->send_request()){
        return ($this->CI->xmlrpc->display_error());
    }else{
        return ($this->CI->xmlrpc->display_response());
    }
}
function getLeadsByClient($appID,$clientId,$start="0",$num="10") {
    $this->initRead();
    $this->CI->xmlrpc->method('getLeadsByClient');
    $request = array(array($appID,'int'),array($clientId,'string'),array($start,'string'),array($num,'string'));
    $this->CI->xmlrpc->request($request);
    if ( ! $this->CI->xmlrpc->send_request()){
        return ($this->CI->xmlrpc->display_error());
    }else{
        $response = json_decode($this->CI->xmlrpc->display_response(),true);
        return $response;
    }
}

function getLeadsByListing($appID,$type,$typeId , $start="0",$num="10") {
    $this->initRead();
    $this->CI->xmlrpc->method('getLeadsByListing');
    $request = array(array($appID,'int'),array($type,'string'),array($typeId,'string'),array($start,'string'),array($num,'string'));
    $this->CI->xmlrpc->request($request);
    if ( ! $this->CI->xmlrpc->send_request()){
        return ($this->CI->xmlrpc->display_error());
    }else{
        $response = json_decode($this->CI->xmlrpc->display_response(),true);
        return $response;
    }
}

function getLeadsByListingCSV($appID,$type,$typeId , $start="0",$num="10") {
    $this->initRead();
    $this->CI->xmlrpc->method('getLeadsByListingCSV');
    $request = array(array($appID,'int'),array($type,'string'),array($typeId,'string'),array($start,'string'),array($num,'string'));
    $this->CI->xmlrpc->request($request);
    if ( ! $this->CI->xmlrpc->send_request()){
        return ($this->CI->xmlrpc->display_error());
    }else{
        $response = $this->CI->xmlrpc->display_response();
        return $response;
    }
}

function getInstituteResponseCountForClientId($appID,$clientId,$tabStatus='live') {
    $this->initRead();
    $this->CI->xmlrpc->method('sgetInstituteResponseCountForClientId');
    $request = array($appID, $clientId, $tabStatus);
    $this->CI->xmlrpc->request($request);
    if ( ! $this->CI->xmlrpc->send_request()){
        return ($this->CI->xmlrpc->display_error());
    }else{
        $response = $this->CI->xmlrpc->display_response();
        return $response;
    }
}

function getResponsesForListingId($appId, $clientId, $listingId, $listingType, $searchCriteria, $timeInterval, $startOffset, $countOffset,$locationId, $startDate = '', $endDate = '', $tabStatus = 'live',$responseIds='') {
    $this->initRead();
    $this->CI->xmlrpc->method('sgetResponsesForListingId');
    $responseIds = json_encode($responseIds);
    $request = array($appId, $clientId, $listingId, $listingType, $searchCriteria, $timeInterval, $startOffset, $countOffset,$locationId, $startDate, $endDate, $tabStatus,$responseIds);
    $this->CI->xmlrpc->request($request);
    if ( ! $this->CI->xmlrpc->send_request()){
        return ($this->CI->xmlrpc->display_error());
    }else{
        $response = base64_decode($this->CI->xmlrpc->display_response());
        return $response;
    }
}

function getAllResponsesForFreeCourses($searchCriteria, $timeInterval, $start, $count, $endDate, $cat, $subCat, $ldbCourse, $preferredCity, $email){
    $this->initRead();
    $this->CI->xmlrpc->method('sgetAllResponsesForFreeCourses');
    $request = array($searchCriteria, $timeInterval, $start, $count, $endDate, $cat, $subCat, $ldbCourse, $preferredCity, $email);//array($appId, $clientId, $listingId, $listingType, $searchCriteria, $timeInterval, $startOffset, $countOffset,$locationId, $startDate, $endDate);
    $this->CI->xmlrpc->request($request);
    if ( ! $this->CI->xmlrpc->send_request()){
        return ($this->CI->xmlrpc->display_error());
    }else{
        $response = base64_decode($this->CI->xmlrpc->display_response());
        return $response;
    }
}

function getAllLeadsForOperator($searchCriteria, $timeInterval, $start, $count){
    $this->initRead();
    $this->CI->xmlrpc->method('sgetAllLeadsForOperator');
    $request = array($searchCriteria, $timeInterval, $start, $count);
    $this->CI->xmlrpc->request($request);
    if ( ! $this->CI->xmlrpc->send_request()){
        return ($this->CI->xmlrpc->display_error());
    }else{
        $response = base64_decode($this->CI->xmlrpc->display_response());
        return $response;
    }
}

function getResponseListbyUserId($userId) {
    $this->initRead();
    $this->CI->xmlrpc->method('getResponseListbyUserId');
    $request = array($userId);
    $this->CI->xmlrpc->request($request);
    if ( ! $this->CI->xmlrpc->send_request()){
        return ($this->CI->xmlrpc->display_error());
    }else{
        $response = base64_decode($this->CI->xmlrpc->display_response());
        return $response;
    }		
}

function insertResponseForFreeCourse($data_to_be_inserted = array()) {

	if(count($data_to_be_inserted) == 0) {
		
		return array();
	}
	
	$this->initRead();
	$this->CI->xmlrpc->method('insertResponseForFreeCourse');
	
	$request = array(json_encode($data_to_be_inserted),'string');
	$this->CI->xmlrpc->request($request);
	
	if (! $this->CI->xmlrpc->send_request()){
		
		return ($this->CI->xmlrpc->display_error());
		
	} else{
				
		$response = $this->CI->xmlrpc->display_response();
		return $response;
	}
}

}
?>
