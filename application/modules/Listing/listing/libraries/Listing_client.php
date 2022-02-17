<?php

/*

Copyright 2007 Info Edge India Ltd

$Rev:: 103           $:  Revision of last commit

listing_client.php makes call to server using XML RPC calls.

$Id: Listing_client.php,v 1.183 2010-09-10 07:12:05 build Exp $:

*/

class Listing_client{

	var $CI_Instance;
	var $cacheLib;
	var $listingCache;
	
    function init()
    {
        $this->CI_Instance = & get_instance();
        $this->CI_Instance->load->helper('url');
        //$server_url = site_url('listing/listing_server');
        $this->CI_Instance->load->library('xmlrpc');
        $this->CI_Instance->xmlrpc->set_debug(0);
        define("CACHE_COUNT",120);
        $this->CI_Instance->xmlrpc->server(Listing_SERVER_URL, Listing_SERVER_PORT);
        $this->CI_Instance->load->library('cacheLib');
        $this->cacheLib = new cacheLib();
		//$this->CI_Instance->load->library('Listing_cache',$this->cacheLib);
        //$this->listingCache = $this->CI_Instance->listing_cache;
    }

    function initCategoryRevampRead()
    {
        $this->CI_Instance = & get_instance();
        $this->CI_Instance->load->helper('url');
        $this->CI_Instance->load->library('xmlrpc');
        $this->CI_Instance->xmlrpc->set_debug(0);
        define("CACHE_COUNT",120);
        $this->CI_Instance->xmlrpc->server(CATEGORY_REVAMP_READ_SERVER_URL, Listing_SERVER_PORT);
        $this->CI_Instance->load->library('cacheLib');
        $this->cacheLib = new cacheLib();
    }

        function initSearch()
    {
        $this->CI =& get_instance();
        $this->CI->load->helper('url');
        $this->CI->load->library('xmlrpc');
//        $server_url = "http://172.16.0.213/shirish/searchCI";
//        $server_url = 'http://172.16.3.227/searchCI';
//        $server_url = 'http://172.16.3.247/search/searchCI';
        $this->CI->xmlrpc->set_debug(0);
        $this->CI->xmlrpc->server(Listing_SEARCH_URL, Listing_SEARCH_PORT);
    }

    function initInstituteSearch()
    {
        $this->CI =& get_instance();
        $this->CI->load->helper('url');
        $this->CI->load->library('xmlrpc');
        $this->CI->xmlrpc->set_debug(0);
        $this->CI->xmlrpc->server(Listing_SEARCH_URL, Listing_SEARCH_PORT);
    }

        function initClientSearch()
    {
        $this->CI =& get_instance();
        $this->CI->load->helper('url');
        $this->CI->load->library('xmlrpc');
        $this->CI->xmlrpc->set_debug(0);
        $this->CI->xmlrpc->server(Client_SEARCH_URL, Listing_SEARCH_PORT);
    }

	function initListing($what="search")
    {
        $this->CI_Instance = & get_instance();
        $this->CI_Instance->load->helper('url');
        //$server_url = site_url('listing/listing_server');
        $this->CI_Instance->load->library('xmlrpc');
        $this->CI_Instance->xmlrpc->set_debug(0);
	$server_url = LISTING_READ_SERVER;
	$server_port = LISTING_READ_SERVER_PORT;
	if($what=='search'){
	    $server_url = Listing_SEARCHSERVER_URL;
	    $server_port = Listing_SERVER_PORT;
	}
	$this->CI_Instance->xmlrpc->server($server_url,$server_port );
		$this->CI_Instance->load->library('cacheLib');
		$this->cacheLib = new cacheLib();
    }

/********NEW WS CODE **********/
    function getSubListings($appID,$type_id,$listing_type) {
        $this->initListing();
        $this->CI_Instance->xmlrpc->method('sgetSubListings');
        $request = array(array($appID,'int'),array($type_id,'int'),array($listing_type,'string'));
        $this->CI_Instance->xmlrpc->request($request);

        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }

    }

    function getCategoryIdsForCourseIds($courseIds){
        $this->initListing();
        $this->CI_Instance->xmlrpc->method('getCategoryIdsForCourseIds');
        $request = array(array($courseIds,'struct'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }

    }
    function getdeletedListingDetails($type_id,$identifier){
	$this->initListing();
        $this->CI_Instance->xmlrpc->method('getdeletedListingDetails');
        $request = array(array($type_id,'int'),array($identifier,'string'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }

    function getCountDataForAnAWidget($instituteId){
	$this->initListing();
        $this->CI_Instance->xmlrpc->method('getCountDataForAnAWidget');
        $request = array(array($instituteId,'int'));
        //$key = md5("AnAwidget-".json_encode($questionIds));
        //if(($this->cacheLib->get($key)=='ERROR_READING_CACHE')||($this->cacheLib->get($key)=='')){
            $this->CI_Instance->xmlrpc->request($request);
            if ( ! $this->CI_Instance->xmlrpc->send_request()){
                return ($this->CI_Instance->xmlrpc->display_error());
            }else{
                $response = $this->CI_Instance->xmlrpc->display_response();
                //$this->cacheLib->store($key,json_encode($response),86400*2);
                return $response;
            }
        //}
        //else{
        //    return json_decode($this->cacheLib->get($key),true);
        //}
}

    function getCourseIdForInstituteId($appID,$institute_id) {
        $this->initListing();

        $this->CI_Instance->xmlrpc->method('getCourseIdForInstituteId');
        $request = array(array($appID,'int'),array($institute_id,'int'));

        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }

    }

function getInstituteIdForCourseId($appID,$courseId) {
        $this->initListing();
        $this->CI_Instance->xmlrpc->method('getInstituteIdForCourseId');
        $request = array(array($appID,'int'),array($courseId,'int'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }

    }


    function getLiveListing($appID,$type_id,$listing_type) {
        $this->initListing();
        $this->CI_Instance->xmlrpc->method('getLiveListings');
        $request = array(array($appID,'int'),array($type_id,'int'),array($listing_type,'string'));
        $this->CI_Instance->xmlrpc->request($request);

        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            $response = ($this->CI_Instance->xmlrpc->display_response());
			//$response = json_decode(gzuncompress($response),true);
			return $response;
        }
    }

    function getListingForEdit($appID,$type_id,$listing_type,$getLiveVersion ="", $isInstituteEditCase = 0) {
        $this->initListing();
        $this->CI_Instance->xmlrpc->method('getListingForEdit');
        $request = array(array($appID,'int'),array($type_id,'int'),array($listing_type,'string'),array($getLiveVersion,'string'), array($isInstituteEditCase,'int'));
        $this->CI_Instance->xmlrpc->request($request);

        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }

    function getSalaryStats($appId,$courseId){
        $this->initListing();
        $this->CI_Instance->xmlrpc->method('getSalaryStats');
        $request = array(array($appID,'int'),array($courseId,'int'));
        $this->CI_Instance->xmlrpc->request($request);

        if ( ! $this->CI_Instance->xmlrpc->send_request()){

            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }

  function checkIfUserExistsForListingAnA($email){
        $this->init();
        $this->CI_Instance->xmlrpc->method('checkIfUserExistsForListingAnA');
        $request = array(array($email,'string'));
        $this->CI_Instance->xmlrpc->request($request);

        if ( ! $this->CI_Instance->xmlrpc->send_request()){

            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }

     function getUploadedBrochure($appID, $listing_type, $listing_type_id)
	{
	$this->initListing();
        $this->CI_Instance->xmlrpc->method('getUploadedBrochure');

	$request = array(array($appID,'int'),array($listing_type,'string'),array($listing_type_id,'string'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request())
        {
            return $this->CI_Instance->xmlrpc->display_error();
        }
        else
        {
            return $this->CI_Instance->xmlrpc->display_response();
        }
	}

    function getReasonToJoinInstitute($instituteId){
        $this->initListing();
        $this->CI_Instance->xmlrpc->method('getReasonToJoinInstitute');
        $request = array(array($instituteId,'int'));
        $this->CI_Instance->xmlrpc->request($request);

        if ( ! $this->CI_Instance->xmlrpc->send_request()){

            return ($this->CI_Instance->xmlrpc->display_error());
        }else{

            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }

    function getSeoTagsForNewListing($listingId,$identifier){
        $this->initListing();
        $this->CI_Instance->xmlrpc->method('getSeoTagsForNewListing');
        $request = array(array($listingId,'int'),array($identifier,'string'));
        $this->CI_Instance->xmlrpc->request($request);

        if ( ! $this->CI_Instance->xmlrpc->send_request()){

            return ($this->CI_Instance->xmlrpc->display_error());
        }else{

            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }

    function getWikiDescriptionCaption($instituteId,$courseId){
        $this->initListing();
        $this->CI_Instance->xmlrpc->method('getWikiDescriptionCaption');
        $request = array(array($instituteId,'int'),array($courseId,'int'));
        $this->CI_Instance->xmlrpc->request($request);

        if ( ! $this->CI_Instance->xmlrpc->send_request()){

            return ($this->CI_Instance->xmlrpc->display_error());
        }else{

            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }

    function makeListingsQueued($appID,$listings, $audit=array()) {
        $this->init();
        $this->CI_Instance->xmlrpc->method('makeListingsLive');
        $request = array(array($appID,'int'),array(base64_encode(serialize($listings)),'string'),array(base64_encode(serialize($audit)),'string'),array('queued','string'));
        $this->CI_Instance->xmlrpc->request($request);

        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            $response = ($this->CI_Instance->xmlrpc->display_response());
            return unserialize(base64_decode($response));
        }
    }

    function makeListingsLive($appID,$listings,$audit) {
        $this->init();
        $this->CI_Instance->xmlrpc->method('makeListingsLive');
		//Amit Singhal: Clearing cache for SEO URL
		foreach($listings as $listing){
			$key = 'getUrlForOverviewTab'.$listing['typeId'].$listing['type'];
			$this->cacheLib->clearCacheForKey($key);
			//Ankur: Clearing cache for SEO URL
			$key = 'getListingDetailsForURL1'.$listing['typeId'].$listing['type'];
			$this->cacheLib->clearCacheForKey($key);
		}
        $request = array(array($appID,'int'),array(base64_encode(serialize($listings)),'string'),array(base64_encode(serialize($audit)),'string'),array('live','string'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            $response = ($this->CI_Instance->xmlrpc->display_response());
            return unserialize(base64_decode($response));
        }
		
    }

    function publishCountrySelection($appID,$countryid) {
        $this->init();
        $this->CI_Instance->xmlrpc->method('publishCountrySelection');
        $request = array(array($appID,'int'),array($countryid,'int'));
        $this->CI_Instance->xmlrpc->request($request);

        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            $response = ($this->CI_Instance->xmlrpc->display_response());
            return $response;
        }
    }
    function publishAll($appID,$institutearr,$categoryid) {
        $this->init();
        $this->CI_Instance->xmlrpc->method('publishAll');
        $request = array(array($appID,'int'),array($categoryid,'int'),array($institutearr,'struct'));
        $this->CI_Instance->xmlrpc->request($request);

        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            $response = ($this->CI_Instance->xmlrpc->display_response());
            return $response;
        }
    }

    function saveCountryOption($appId,$arr) {
        $this->init();
        $this->CI_Instance->xmlrpc->method('saveCountryOption');
        $request = array($appId,json_encode($arr));
        $this->CI_Instance->xmlrpc->request($request);

        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            $response = ($this->CI_Instance->xmlrpc->display_response());
            return $response;
        }
    }
    function saveTopOption($appID,$instituteid,$categoryid,$statusval,$priority) {
        $this->init();
        $this->CI_Instance->xmlrpc->method('saveTopOption');
        $request = array(array($appID,'int'),array($categoryid,'int'),array($instituteid,'string'),array($statusval,'string'),array($priority,'int'));
        $this->CI_Instance->xmlrpc->request($request);

        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            $response = ($this->CI_Instance->xmlrpc->display_response());
            return $response;
        }
    }
    function getCmsTopInstitutes($appID,$categoryid) {
        $this->initListing();
        $this->CI_Instance->xmlrpc->method('getCmsTopInstitutes');
        $request = array(array($appID,'int'),array($categoryid,'int'));
        $this->CI_Instance->xmlrpc->request($request);

        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            $response = ($this->CI_Instance->xmlrpc->display_response());
            return $response;
        }
    }

    function getCmsCountryOptions($appID,$countryid) {
        $this->initListing();
        $this->CI_Instance->xmlrpc->method('getCmsCountryOptions');
        $request = array(array($appID,'int'),array($countryid,'int'));
        $this->CI_Instance->xmlrpc->request($request);

        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            $response = ($this->CI_Instance->xmlrpc->display_response());
            return $response;
        }
    }

    function getMetaInfoForInstitutes($appID,$institutes,$categoryid) {
        $this->initListing();
        $this->CI_Instance->xmlrpc->method('getMetaInfoForInstitutes');
        $request = array(array($appID,'int'),array($institutes,'string'),array($categoryid,'int'));
        $this->CI_Instance->xmlrpc->request($request);

        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            $response = ($this->CI_Instance->xmlrpc->display_response());
            return $response;
        }
    }


    function getMetaInfo($appID,$listings,$status='"live"') {
        $this->initListing();
        $this->CI_Instance->xmlrpc->method('getMetaInfo');
        $request = array(array($appID,'int'),array(base64_encode(serialize($listings)),'string'),array($status,'string'));
        $this->CI_Instance->xmlrpc->request($request);

        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            $response = ($this->CI_Instance->xmlrpc->display_response());
            return unserialize(base64_decode($response));
        }
    }

    function getUrlForOverviewTab($type_id ,$identifier){
        $this->initListing();
        $this->CI_Instance->xmlrpc->method('getUrlForOverviewTab');
		$key = 'getUrlForOverviewTab'.$type_id.$identifier;
		if(($this->cacheLib->get($key)=='ERROR_READING_CACHE')||($this->cacheLib->get($key)=='')){
			$request = array(array($type_id,'int'),array($identifier,'string'));
			$this->CI_Instance->xmlrpc->request($request);
			if ( ! $this->CI_Instance->xmlrpc->send_request()){
				return ($this->CI_Instance->xmlrpc->display_error());
			}else{
				$response = ($this->CI_Instance->xmlrpc->display_response());
				$this->cacheLib->store($key,$response);
				return $response;
			}
		}else{
            return $this->cacheLib->get($key);
		}
       
    }


    function getContactInfo($appID,$type_id,$listing_type) {
        $this->initListing();
        $this->CI_Instance->xmlrpc->method('getContactInfo');
        $request = array(array($appID,'int'),array($type_id,'int'),array($listing_type,'string'));
        $this->CI_Instance->xmlrpc->request($request);

        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            $response = ($this->CI_Instance->xmlrpc->display_response());
            return unserialize(base64_decode($response));
        }
    }

    function checkCourseDuplicacy($appID,$course_id = -1, $institute_id, $courseTitle,$courseType) {
        $this->initListing();
        $this->CI_Instance->xmlrpc->method('checkCourseDuplicacy');
        $argsArray = array();
        $argsArray['course_id'] = $course_id;
        $argsArray['institute_id'] = $institute_id;
        $argsArray['courseTitle'] = $courseTitle;
        $argsArray['courseType'] = $courseType;
        $args = base64_encode(serialize($argsArray));
        $request = array(array($appID,'int'),array($args,'string'));
        $this->CI_Instance->xmlrpc->request($request);

        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            $response = ($this->CI_Instance->xmlrpc->display_response());
            return unserialize(base64_decode($response));
        }
    }


    function checkInstituteDuplicacy($appID,$institute_id = -1, $institute_name, $pincode) {
        $this->initListing();
        $this->CI_Instance->xmlrpc->method('checkInstituteDuplicacy');
        $argsArray = array();
        $argsArray['institute_id'] = $institute_id;
        $argsArray['institute_name'] = $institute_name;
        $argsArray['pincode'] = $pincode;
        $args = base64_encode(serialize($argsArray));
        $request = array(array($appID,'int'),array($args,'string'));
        $this->CI_Instance->xmlrpc->request($request);

        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            $response = ($this->CI_Instance->xmlrpc->display_response());
            return unserialize(base64_decode($response));
        }
    }

    function getCountForResponseForm($institute_id){
        $this->initListing();
	//Modified by Ankur on 16 April to put the Response count in Cache for 1 day
	$key = md5("responseFormCount".$institute_id);
	if(($this->cacheLib->get($key)=='ERROR_READING_CACHE') || ($key == -1) || ($this->cacheLib->get($key)=='')){
	      $this->CI_Instance->xmlrpc->method('sgetCountForResponseForm');
	      $request = array(array($institute_id,'int'));
	      $this->CI_Instance->xmlrpc->request($request);
	      if(! $this->CI_Instance->xmlrpc->send_request()){
		  return ($this->CI_Instance->xmlrpc->display_error());
	      }else{
		  $response = $this->CI_Instance->xmlrpc->display_response();
		  $this->cacheLib->store($key,$response,86400,'misc');
		  return $response;
	      }
	}
	else{
               	return $this->cacheLib->get($key);
	}
    }

    function getListingDetails($appID,$type_id,$listing_type,$otherInstitutesCategory = "", $allDataFlag = 0, $isRequestedfromSearch = 0) {
        $this->initListing();
        $this->CI_Instance->xmlrpc->method('sgetListingDetails');
        $this->CI_Instance->xmlrpc->set_debug(0);
        $request = array(array($appID,'int'),array($type_id,'int'),array($listing_type,'string'),array($otherInstitutesCategory,'string'),array($allDataFlag,'int'),array($isRequestedfromSearch,'int'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            $response = ($this->CI_Instance->xmlrpc->display_response());
			//$response = json_decode(gzuncompress(base64_encode($response)),true);
			return $response;
        }

    }
    function getParentCategoriesForListing($appID,$type_id,$listing_type) {
        $this->initListing();
        $this->CI_Instance->xmlrpc->method('sgetParentCategoriesForListing');
        $request = array(array($appID,'int'),array($type_id,'int'),array($listing_type,'string'));
        $this->CI_Instance->xmlrpc->request($request);

        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }
    function getListingDetailForSms($appID,$type_id,$listing_type) {
        $this->initListing();
        $this->CI_Instance->xmlrpc->method('sgetListingDetailForSms');
        $request = array(array($appID,'int'),array($type_id,'int'),array($listing_type,'string'));
        error_log_shiksha("get Detail for Sms".print_r($request,TRUE));
        $this->CI_Instance->xmlrpc->request($request);

        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }



    function updateThreadId($appID,$type_id,$listing_type,$threadId) {
        $this->init();
        $this->CI_Instance->xmlrpc->method('updateThreadId');
        $request = array(array($appID,'int'),array($type_id,'int'),array($listing_type,'string'),array($threadId,'int'));
        $this->CI_Instance->xmlrpc->request($request);

        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }

    }

    function updateViewCount($appID,$type_id,$listing_type) {
        $this->init();
        $this->CI_Instance->xmlrpc->method('updateViewCount');
        $request = array(array($appID,'int'),array($type_id,'int'),array($listing_type,'string'));
        $this->CI_Instance->xmlrpc->request($request);

        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
	 }

     }
     function getListingsList($appID,$type,$start,$count,$criteriaBox1,$criteriaBox2,$criteriaBox3,$filter1,$filter2,$filter3,$filter4,$usergroup,$userid,$filter5) {
     	$doCache=0;
        if($usergroup=='cms' && ($type=='course' || $type=='institute')){
            $doCache=1;
        }

        $key = md5('getCourseFromCache'.$usergroup.$type.$start.$count.$filter1.$filter2.$filter3.$filter4.$filter5);

        $this->initListing();
//        error_log_shiksha("APP ID and TYPE getListingsList =>$appID :: $type");

        if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
            $this->CI_Instance->xmlrpc->method('sgetListingsList');
            $request = array(array($appID,'int'),array($type,'string'),array($start,'int'),array($count,'int'),array($criteriaBox1,'string'),array($criteriaBox2,'string'),array($criteriaBox3,'string'),array($filter1,'string'),array($filter2,'string'),array($filter3,'string'),array($filter4,'string'),array($usergroup,'string'),array($userid,'string'),array($filter5,'string'));
//            error_log_shiksha("CLIENT REQUEST getListingsList ARRAY".print_r($request,TRUE));
            $this->CI_Instance->xmlrpc->request($request);

            if ( ! $this->CI_Instance->xmlrpc->send_request()){
                return ($this->CI_Instance->xmlrpc->display_error());
            }else{
                $response = $this->CI_Instance->xmlrpc->display_response();
                if($doCache==1){
                    //$this->cacheLib->store($key,$response,14400,'cmsFedCourse');
                }
                return $response;
            }
        }else{
            return $this->cacheLib->get($key);
        }

    }

    function saveCourseOrder($appID, $instituteId,$courseIds,$courseOrders){
    	$this->init();
        $this->CI_Instance->xmlrpc->method('saveCourseOrder');
        $request = array(array($appID,'int'),array($instituteId,'int'),array($courseIds,'array'),array($courseOrders,'array'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
        	return ($this->CI_Instance->xmlrpc->display_error());
		}else{
			$response = $this->CI_Instance->xmlrpc->display_response();
        	return $response;
		}
    }

	function getCourseOrder($appID, $instituteId){
		$this->initListing();
        $this->CI_Instance->xmlrpc->method('getCourseOrder');
        $request = array(array($appID,'int'),array($instituteId,'int'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
        	return ($this->CI_Instance->xmlrpc->display_error());
		}else{
			$response = $this->CI_Instance->xmlrpc->display_response();
        	return $response;
		}
	}

    function getCityList($appID,$countryID) {
        $this->initListing();
        $key = md5('getCityList'.$appID.$countryID);
        error_log_shiksha("key for cache is : ".$key);
        if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){

            error_log_shiksha("APP ID getCityList =>$appID :: $countryID");
            $this->CI_Instance->xmlrpc->method('sgetCityList');
            $request = array(array($appID,'int'),array($countryID,'int'));
            error_log_shiksha("CLIENT REQUEST getCityList ARRAY".print_r($request,TRUE));
            $this->CI_Instance->xmlrpc->request($request);

            if ( ! $this->CI_Instance->xmlrpc->send_request()){
                return ($this->CI_Instance->xmlrpc->display_error());
            }else{
                $response = $this->CI_Instance->xmlrpc->display_response();
                $this->cacheLib->store($key,$response,86400,'citylist');
                return $response;
            }
        }
        else{
            error_log_shiksha("DEBUG: LISTING_CLIENT::getInstituteListIndexed: EXIT SUCCESS Reading from cache");
            return $this->cacheLib->get($key);
        }
    }

    function getInstituteList($appID,$cityID) {
        $this->initListing();
        error_log_shiksha("APP ID getInstituteList =>$appID :: $cityID");
        $this->CI_Instance->xmlrpc->method('sgetInstituteList');
        $request = array(array($appID,'int'),array($cityID,'int'));
        error_log_shiksha("CLIENT REQUEST getInstituteList ARRAY".print_r($request,TRUE));
        $this->CI_Instance->xmlrpc->request($request);

        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }

    function getInstituteListInCountry($appID,$countryId) {
        $this->initListing();
        error_log_shiksha("APP ID getInstituteList =>$appID :: $countryId");
        $this->CI_Instance->xmlrpc->method('sgetInstituteListInCountry');
        $request = array(array($appID,'int'),array($countryId,'int'));
        error_log_shiksha("CLIENT REQUEST getInstituteList ARRAY".print_r($request,TRUE));
        $this->CI_Instance->xmlrpc->request($request);

        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }

    }


    /**
     *
     * Returns the list of entrance exams required
     * @return array
     */
    function getEntranceExams() {
        $this->initListing();
        $this->CI_Instance->xmlrpc->method('sgetEntranceExams');
//        error_log_shiksha("CLIENT REQUEST getCourseList ARRAY".print_r($request,TRUE));
        $this->CI_Instance->xmlrpc->request();
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }
    function getCourseList($appID,$instituteID, $status = "live", $userId = '',$use_old_tables=TRUE) {
        $this->initListing();
        error_log_shiksha("APP ID getCourseList =>$appID :: $instituteID");
        $this->CI_Instance->xmlrpc->method('sgetCourseList');
        $request = array($appID,$instituteID, $status, $userId,$use_old_tables);
        error_log_shiksha("CLIENT REQUEST getCourseList ARRAY".print_r($request,TRUE));
        $this->CI_Instance->xmlrpc->request($request);

        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }

    }
    function getCountryForCity($appID,$cityID) {
        $this->initListing();
        error_log_shiksha("APP ID getCourseList =>$appID :: $cityID");
        $this->CI_Instance->xmlrpc->method('sgetCountryForCity');
        $request = array(array($appID,'int'),array($cityID,'int'));
        error_log_shiksha("CLIENT REQUEST getCourseList ARRAY".print_r($request,TRUE));
        $this->CI_Instance->xmlrpc->request($request);

        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }

    }



    function insertNewCourse($appID,$requestArray){ //requestArray is actually _POST array
        $this->init();
        $this->CI_Instance->xmlrpc->method('sinsertNewCourse');
        $request = array(array($appID,'int'),array($requestArray,'struct'));
        $this->CI_Instance->xmlrpc->request($request);

        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }
    function updateCourse($appID,$courseId,$updateData){ //requestArray is actually _POST array
        $this->init();
        $this->CI_Instance->xmlrpc->method('updateCourse');
        $request = array(array($appID,'int'),array($courseId,'int'),array($updateData,'struct'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }


    function getInstituteListIndexed($appID,$requestArray){ //requestArray is actually _POST array
        $this->init();

        $key = md5('getInstituteListIndexed'.$requestArray['countryId'].$requestArray['catId'].$requestArray['indexOf']);
        error_log_shiksha("key for cache is : ".$key);
        if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
            $this->CI_Instance->xmlrpc->method('sgetInstituteListIndexed');
            $request = array(array($appID,'int'),array($requestArray,'struct'));
            $this->CI_Instance->xmlrpc->request($request);

            if ( ! $this->CI_Instance->xmlrpc->send_request()){
                error_log_shiksha("ERROR: LISTING_CLIENT::getInstituteListIndexed: FAIL".$this->CI_Instance->xmlrpc->display_error());
                error_log_shiksha("DEBUG: LISTING_CLIENT::getInstituteListIndexed: EXIT FAILURE");
                return ($this->CI_Instance->xmlrpc->display_error());
            }else{
                error_log_shiksha("DEBUG: LISTING_CLIENT::getInstituteListIndexed: EXIT SUCCESS");
                $response = $this->CI_Instance->xmlrpc->display_response();
                $this->cacheLib->store($key,$response);
                return $response;
            }
        }else{
            error_log_shiksha("DEBUG: LISTING_CLIENT::getInstituteListIndexed: EXIT SUCCESS Reading from cache");
            return $this->cacheLib->get($key);
        }

    }


    function getListingsByFilters($appID,$requestArray){ //requestArray is actually _POST array
        $this->initListing();
        $this->CI_Instance->xmlrpc->method('sGetListingsByFilters');
        $request = array(array($appID,'int'),array($requestArray,'struct'));
        error_log_shiksha(print_r($request,true));
        $this->CI_Instance->xmlrpc->request($request);

        if ( ! $this->CI_Instance->xmlrpc->send_request()){
        error_log_shiksha("request".print_r($request,true));
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
        error_log_shiksha(print_r($request,true));
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }

       /*
       *       Method to Add a institute
       */
        function add_institute($appID,$requestArray){ //requestArray is actually _POST array
                $this->init();
/*		$this->CI_Instance->load->helper(array('form'));
		$this->CI_Instance->load->library('validation');
*/
                $this->CI_Instance->xmlrpc->method('sadd_institute');
//                $request = array(array($appID,'int'),array(array("listing_title" => "TESTtitle","contact_email" => "temp@email.com"),'struct'));
	        $request = array(array($appID,'int'),array($requestArray,'struct'));
//		error_log_shiksha("CLIENT REQUEST ARRAY".print_r($request,TRUE));
                $this->CI_Instance->xmlrpc->request($request);

                if ( ! $this->CI_Instance->xmlrpc->send_request()){
                    return ($this->CI_Instance->xmlrpc->display_error());
                }else{
                    $this->CI_Instance->load->library('cacheLib');
                    $this->cacheLib = new cacheLib();
                    //$this->cacheLib->clearCache('cmsFedCourse');
                    return ($this->CI_Instance->xmlrpc->display_response());
                }
        }

       /*
       *       Method to Update a institute
       */
        function update_institute($appID,$requestArray){ //requestArray is actually _POST array
                $this->init();
/*		$this->CI_Instance->load->helper(array('form'));
		$this->CI_Instance->load->library('validation');
*/
                $this->CI_Instance->xmlrpc->method('supdate_institute');
//                $request = array(array($appID,'int'),array(array("listing_title" => "TESTtitle","contact_email" => "temp@email.com"),'struct'));
	        $request = array(array($appID,'int'),array($requestArray,'struct'));
//		error_log_shiksha("CLIENT REQUEST ARRAY".print_r($request,TRUE));
                $this->CI_Instance->xmlrpc->request($request);

                if ( ! $this->CI_Instance->xmlrpc->send_request()){
                        return ($this->CI_Instance->xmlrpc->display_error());
                }else{
                        return ($this->CI_Instance->xmlrpc->display_response());
                }
        }


       /*
        *       Method to update Media tables for listing
        */
        function updateMediaContent($appID,$type_id,$listing_type,$media_type,$requestArray){ //requestArray is actually _POST array
                $this->init();
/*		$this->CI_Instance->load->helper(array('form'));
		$this->CI_Instance->load->library('validation');
*/
                $this->CI_Instance->xmlrpc->method('supdateMediaContent');
                $request = array(array($appID,'int'),array(array("type_id" => $type_id,"listing_type" => $listing_type,"media_type" => $media_type),'struct'),array($requestArray,'struct'));
//	        $request = array(array($appID,'int'),array($requestArray,'struct'));
//		error_log_shiksha("CLIENT REQUEST ARRAY".print_r($request,TRUE));
                $this->CI_Instance->xmlrpc->request($request);

                if ( ! $this->CI_Instance->xmlrpc->send_request()){
                        return ($this->CI_Instance->xmlrpc->display_error());
                }else{
                        return ($this->CI_Instance->xmlrpc->display_response());
                }
        }

    function getCountryCityTree($appID) {
        $this->initListing();
        error_log_shiksha("APP ID getCityList =>$appID ");
        $this->CI_Instance->xmlrpc->method('sgetCountryCityTree');
        $request = array(array($appID,'int'));
        error_log_shiksha("CLIENT REQUEST getCityList ARRAY".print_r($request,TRUE));
        $this->CI_Instance->xmlrpc->request($request);

        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }

    }

    function getCountriesRegionsForProduct($appID,$product) {
        $this->initListing();
        error_log_shiksha("APP ID getCityList =>$appID ");
        $key = md5('getCountriesForProduct'.$appID.$product);
        if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
            $this->CI_Instance->xmlrpc->method('sgetCountriesForProduct');
            $request = array(array($appID,'int'),array($product,'string'));
            error_log_shiksha("CLIENT REQUEST getCityList ARRAY".print_r($request,TRUE));
            $this->CI_Instance->xmlrpc->request($request);
            if ( ! $this->CI_Instance->xmlrpc->send_request()){
                return ($this->CI_Instance->xmlrpc->display_error());
            }else{
                $response = $this->CI_Instance->xmlrpc->display_response();
                $this->cacheLib->store($key,$response,14446000);
                return $response;
            }
        }else{
            return $this->cacheLib->get($key);
        }
    }

    function getCountries($appID) {
        $this->initListing();
        error_log_shiksha("APP ID getCityList =>$appID ");
        $key = md5('getCountries'.$appID);
        if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
            $this->CI_Instance->xmlrpc->method('sgetCountries');
            $request = array(array($appID,'int'));
            error_log_shiksha("CLIENT REQUEST getCityList ARRAY".print_r($request,TRUE));
            $this->CI_Instance->xmlrpc->request($request);
            if ( ! $this->CI_Instance->xmlrpc->send_request()){
                return ($this->CI_Instance->xmlrpc->display_error());
            }else{
                $response = $this->CI_Instance->xmlrpc->display_response();
                $this->cacheLib->store($key,$response,14446000);
                return $response;
            }
        }else{
            return $this->cacheLib->get($key);
        }
    }


    function getFullPath($appID) {
        $this->initListing();
        error_log_shiksha("APP ID getCityList =>$appID ");
        $this->CI_Instance->xmlrpc->method('sgetFullPath');
        $request = array(array($appID,'int'));
        error_log_shiksha("CLIENT REQUEST getCityList ARRAY".print_r($request,TRUE));
        $this->CI_Instance->xmlrpc->request($request);

        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }

    }





/********NEW WS CODE **********/


       /*
        *       Method to Add a Listing
        */
        function add_listing($appID,$requestArray){ //requestArray is actually _POST array
                $this->init();
/*		$this->CI_Instance->load->helper(array('form'));
		$this->CI_Instance->load->library('validation');
*/
                $this->CI_Instance->xmlrpc->method('sadd_listing');
//                $request = array(array($appID,'int'),array(array("listing_title" => "TESTtitle","contact_email" => "temp@email.com"),'struct'));
	        $request = array(
				array($appID,'int'),
				array($requestArray,'struct'),'struct'
			);
		error_log_shiksha("ADD LISTING CLIENT REQUEST ARRAY".print_r($request,TRUE));
                $this->CI_Instance->xmlrpc->request($request);

                if ( ! $this->CI_Instance->xmlrpc->send_request()){
			error_log_shiksha("CLIENT_ERROR RECEIVED RESPONSE ARRAY".print_r($this->CI_Instance->xmlrpc->display_response(),TRUE));
                        return ($this->CI_Instance->xmlrpc->display_error());
                }else{
			error_log_shiksha("CLIENT RECEIVED RESPONSE ARRAY".print_r($this->CI_Instance->xmlrpc->display_response(),TRUE));
                        return ($this->CI_Instance->xmlrpc->display_response());
                }
        }


       /*
        *       Method to Get Listing detail for some ListingID
        */
        function getDetailByListingID($appID,$listingID) {
                $this->initListing();
		error_log_shiksha("APP ID getDetailByListingID =>".$appID);
                $this->CI_Instance->xmlrpc->method('sgetDetailByListingID');
	        $request = array(array($appID,'int'),array($listingID,'int'));
		error_log_shiksha("CLIENT REQUEST getDetailByListingID ARRAY".print_r($request,TRUE));
                $this->CI_Instance->xmlrpc->request($request);

                if ( ! $this->CI_Instance->xmlrpc->send_request()){
//			error_log_shiksha("CLIENT_ERROR RECEIVED RESPONSE ARRAY".print_r($this->CI_Instance->xmlrpc->display_response(),TRUE));
                        return ($this->CI_Instance->xmlrpc->display_error());
                }else{
//			error_log_shiksha("CLIENT RECEIVED RESPONSE ARRAY".print_r($this->CI_Instance->xmlrpc->display_response(),TRUE));
                        return ($this->CI_Instance->xmlrpc->display_response());
                }

        }


       /*
        *       Method to Get Category List of all available Categories: NOT being used
        */
        function getCategoryList($appID) {
                $this->initListing();
                $this->CI_Instance->xmlrpc->method('sgetCategoryList');
	        $request = array($appID,'int');
                $this->CI_Instance->xmlrpc->request($request);

                if ( ! $this->CI_Instance->xmlrpc->send_request()){
//			error_log_shiksha("CLIENT_ERROR RECEIVED RESPONSE ARRAY".print_r($this->CI_Instance->xmlrpc->display_response(),TRUE));
                        return ($this->CI_Instance->xmlrpc->display_error());
                }else{
//			error_log_shiksha("CLIENT RECEIVED RESPONSE ARRAY".print_r($this->CI_Instance->xmlrpc->display_response(),TRUE));
                        return ($this->CI_Instance->xmlrpc->display_response());
                }
        }


       /*
        *       Method to Get Institute List of all available Institutes
        */
        function getFullInstituteList($appID,$cityId) {
                $this->initListing();
                $this->CI_Instance->xmlrpc->method('sgetFullInstituteList');
	        $request = array(array($appID,'int'),array($cityId,'int'));
                $this->CI_Instance->xmlrpc->request($request);

                if ( ! $this->CI_Instance->xmlrpc->send_request()){
//			error_log_shiksha("CLIENT_ERROR RECEIVED RESPONSE ARRAY".print_r($this->CI_Instance->xmlrpc->display_response(),TRUE));
                        return ($this->CI_Instance->xmlrpc->display_error());
                }else{
//			error_log_shiksha("CLIENT RECEIVED RESPONSE ARRAY".print_r($this->CI_Instance->xmlrpc->display_response(),TRUE));
                        return ($this->CI_Instance->xmlrpc->display_response());
                }
        }


       /*
        *       Method to Update a Listing
        */
        function update_listing($appID='1',$updateData){
                $this->init();
		error_log_shiksha("APP ID update_listing=>".$appID);
                $this->CI_Instance->xmlrpc->method('supdate_listing');
	        $request = array(
				array($appID,'int'),
				array($updateData,'struct')
			);
		error_log_shiksha("CLIENT REQUEST ARRAY update_listing ".print_r($request,TRUE));
                $this->CI_Instance->xmlrpc->request($request);

                if ( ! $this->CI_Instance->xmlrpc->send_request()){
//			error_log_shiksha("CLIENT_ERROR RECEIVED RESPONSE ARRAY".print_r($this->CI_Instance->xmlrpc->display_response(),TRUE));
                        return ($this->CI_Instance->xmlrpc->display_error());
                }else{
//			error_log_shiksha("CLIENT RECEIVED RESPONSE ARRAY".print_r($this->CI_Instance->xmlrpc->display_response(),TRUE));
                        return ($this->CI_Instance->xmlrpc->display_response());
                }
	}


       /*
        *       Method to Add a Course
        */
        function add_course($appID,$requestArray,$eligibility,$tests){ //requestArray is actually _POST array
                $this->init();
/*		$this->CI_Instance->load->helper(array('form'));
		$this->CI_Instance->load->library('validation');
*/
                $this->CI_Instance->xmlrpc->method('sadd_course');
//                $request = array(array($appID,'int'),array(array("listing_title" => "TESTtitle","contact_email" => "temp@email.com"),'struct'));
//	        $request = array(array($appID,'int'),array($requestArray,'struct'));
	        $request = array(array($appID,'int'),array($requestArray,'struct'),array($eligibility,'struct'),array($tests,'struct'));
//		error_log_shiksha("CLIENT REQUEST ARRAY".print_r($request,TRUE));
                $this->CI_Instance->xmlrpc->request($request);

                if ( ! $this->CI_Instance->xmlrpc->send_request()){
                    return ($this->CI_Instance->xmlrpc->display_error());
                }else{
                    $this->CI_Instance->load->library('cacheLib');
                    $this->cacheLib = new cacheLib();
                    //$this->cacheLib->clearCache('cmsFedCourse');
                    return ($this->CI_Instance->xmlrpc->display_response());
                }
        }

       /*
        *       Method to Add a adm
        */
        function add_admission($appID,$requestArray,$requestArray1){ //requestArray is actually _POST array
                $this->init();
/*		$this->CI_Instance->load->helper(array('form'));
		$this->CI_Instance->load->library('validation');
*/
                $this->CI_Instance->xmlrpc->method('sadd_admission');
//                $request = array(array($appID,'int'),array(array("listing_title" => "TESTtitle","contact_email" => "temp@email.com"),'struct'));
	        $request = array(array($appID,'int'),array($requestArray,'struct'),array($requestArray1,'struct'));
//		error_log_shiksha("CLIENT REQUEST ARRAY".print_r($request,TRUE));
                $this->CI_Instance->xmlrpc->request($request);

                if ( ! $this->CI_Instance->xmlrpc->send_request()){
                        return ($this->CI_Instance->xmlrpc->display_error());
                }else{
                        return ($this->CI_Instance->xmlrpc->display_response());
                }
        }

       /*
        *       Method to Add a Scholarship
        */
        function add_scholarship($appID,$requestArray,$requestArray1){ //requestArray is actually _POST array
                $this->init();
/*		$this->CI_Instance->load->helper(array('form'));
		$this->CI_Instance->load->library('validation');
*/
                $this->CI_Instance->xmlrpc->method('sadd_scholarship');
//                $request = array(array($appID,'int'),array(array("listing_title" => "TESTtitle","contact_email" => "temp@email.com"),'struct'));
	        $request = array(array($appID,'int'),array($requestArray,'struct'),array($requestArray1,'struct'));
//		error_log_shiksha("CLIENT REQUEST ARRAY".print_r($request,TRUE));
                $this->CI_Instance->xmlrpc->request($request);

                if ( ! $this->CI_Instance->xmlrpc->send_request()){
                        return ($this->CI_Instance->xmlrpc->display_error());
                }else{
                        return ($this->CI_Instance->xmlrpc->display_response());
                }
        }

       /*
        *       Method to get status & versions for a listing
        */
        function getCurrentStatusVersions($appID='1',$type_id,$listing_type){
            $this->initListing();
            error_log_shiksha("APP ID getCurrentStatusVersions=>".$appID);
            $this->CI_Instance->xmlrpc->method('getCurrentStatusVersions');
            $request = array(array($appID,'int'),array($type_id,'int'),array($listing_type,'string'));
            $this->CI_Instance->xmlrpc->request($request);
            if ( ! $this->CI_Instance->xmlrpc->send_request()){
                return ($this->CI_Instance->xmlrpc->display_error());
            }else{
                return unserialize(base64_decode($this->CI_Instance->xmlrpc->display_response()));
            }
        }

       /*
        *       Method to Delete draft or queued
        */
        function deleteDraftOrQueued($appID='1',$type_id,$listing_type, $status='draft',$audit){
            $this->init();
            error_log_shiksha("APP ID deleteDraftOrQueued=>".$appID);
            $this->CI_Instance->xmlrpc->method('deleteDraftOrQueued');
            $request = array(array($appID,'int'),array($type_id,'int'),array($listing_type,'string'),array($status,'string'),array(base64_encode(serialize($audit)),'string'));
            $this->CI_Instance->xmlrpc->request($request);
            if ( ! $this->CI_Instance->xmlrpc->send_request()){
                return ($this->CI_Instance->xmlrpc->display_error());
            }else{
                return ($this->CI_Instance->xmlrpc->display_response());
            }
        }

       /*
        *       Method to disapprove queued listings
        */
    function disapproveQueuedListings($appID,$listings, $audit=array()) {
        $this->init();
        $this->CI_Instance->xmlrpc->method('disapproveQueuedListings');
        $request = array(array($appID,'int'),array(base64_encode(serialize($listings)),'string'),array(base64_encode(serialize($audit)),'string'));
        $this->CI_Instance->xmlrpc->request($request);

        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            $response = ($this->CI_Instance->xmlrpc->display_response());
            return unserialize(base64_decode($response));
        }
    }

       /*
        *       Method to Delete a Listing
        */
        function delete_listing($appID='1',$type_id,$listing_type, $user_id = ""){
                $this->init();
		error_log_shiksha("APP ID delete_listing=>".$appID);
                $this->CI_Instance->xmlrpc->method('sdelete_listing');
                $request = array(array($appID,'int'),array($type_id,'int'),array($listing_type,'string'), array($user_id,'int'));
		error_log_shiksha("CLIENT REQUEST ARRAY update_listing ".print_r($request,TRUE));
                $this->CI_Instance->xmlrpc->request($request);

                if ( ! $this->CI_Instance->xmlrpc->send_request()){
//			error_log_shiksha("CLIENT_ERROR RECEIVED RESPONSE ARRAY".print_r($this->CI_Instance->xmlrpc->display_response(),TRUE));
                        return ($this->CI_Instance->xmlrpc->display_error());
                }else{
//			error_log_shiksha("CLIENT RECEIVED RESPONSE ARRAY".print_r($this->CI_Instance->xmlrpc->display_response(),TRUE));
                        return ($this->CI_Instance->xmlrpc->display_response());
                }
	}


       /*
        *       Method to report abuse a Listing
        */
        function reportAbuse($appID='1',$type_id,$listing_type){
                $this->init();
		error_log_shiksha("APP ID reportAbuse=>".$appID);
                $this->CI_Instance->xmlrpc->method('reportAbuse');
                $request = array(array($appID,'int'),array($type_id,'int'),array($listing_type,'string'));
		error_log_shiksha("CLIENT REQUEST ARRAY reportAbuse ".print_r($request,TRUE));
                $this->CI_Instance->xmlrpc->request($request);

                if ( ! $this->CI_Instance->xmlrpc->send_request()){
//			error_log_shiksha("CLIENT_ERROR RECEIVED RESPONSE ARRAY".print_r($this->CI_Instance->xmlrpc->display_response(),TRUE));
                        return ($this->CI_Instance->xmlrpc->display_error());
                }else{
//			error_log_shiksha("CLIENT RECEIVED RESPONSE ARRAY".print_r($this->CI_Instance->xmlrpc->display_response(),TRUE));
                        return ($this->CI_Instance->xmlrpc->display_response());
                }
	}


function indexListing($appId,$requestArray)
    {
        return; /*
        $this->initInstituteSearch();
        $this->CI->xmlrpc->method('indexListingRecord');
		//$request = array(array($appId,'int'),array($requestArray,'struct'));
	$request = array(array($appId,'int'), utility_encodeXmlRpcResponse($requestArray));
        //error_log("dhwaj ldb request xmlrpc in Listing_client ".print_r($request, true));
	$this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request())
        {
            return $this->CI->xmlrpc->display_error();
        }
        else
        {
            return $this->CI->xmlrpc->display_response();
        }
        */
    }
    function deleteListing($AppId,$type,$Id)
    {
		if($type == "msgbrd"){
			$type = "question";
		}
		$indexingType = $type;
		$validIndexTypes = array('course', 'institute', 'question');
		if(in_array($indexingType, $validIndexTypes)){
			modules::run('search/Indexer/addToQueue', $Id, $indexingType, 'delete');
		}
		return true;
    //	$this->initSearch();
    //    $this->CI->xmlrpc->method('deleteListingRecord');
    //    $request = array (
    //            array($AppId, 'int'),
    //            array($type, 'string'),
    //            array($Id, 'string')
    //            );
    //    $this->CI->xmlrpc->request($request);
    //    if ( ! $this->CI->xmlrpc->send_request())
    //    {
    //        return $this->CI->xmlrpc->display_error();
    //    }
    //    else
    //    {
    //        return $this->CI->xmlrpc->display_response();
    //    }
	
    }
    /*This function is used to delete multiple listings in the index
      the $AppId is integer
      the $listingArray is in form $listingArray=array(array(array('type'=>'institute','id'=>26000),'struct'), ..... ,'struct');*/
    function deleteBulkListing($AppId,$listingArray)
    {
        $this->initSearch();
        $this->CI->xmlrpc->method('deleteBulkListingRecord');
        $request = array (
                array($AppId, 'int'),
                array($listingArray, 'struct')
                );
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request())
        {
            return $this->CI->xmlrpc->display_error();
        }
        else
        {
            return $this->CI->xmlrpc->display_response();
        }
    }
    function deleteMsgbrdListing($AppId,$type,$boardId,$topicId)
    {
		return true;
		/*
		$this->initSearch();
        $this->CI->xmlrpc->method('deleteMsgbrdRecord');
        $request = array (
                array($AppId, 'int'),
                array($type, 'string'),
                array($boardId, 'int'),
                array($topicId, 'int')
                );
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request())
        {
            return $this->CI->xmlrpc->display_error();
        }
        else
        {
            return $this->CI->xmlrpc->display_response();
        }
		*/
    }

    function updateSponsorSnippetCount($AppId,$Id)
    {
        $this->initClientSearch();
        $this->CI->xmlrpc->method('updateSponsorSnippetCount');
        $request = array (
                array($AppId, 'int'),
                array($Id, 'int')
                );
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request())
        {
            return $this->CI->xmlrpc->display_error();
        }
        else
        {
            return $this->CI->xmlrpc->display_response();
        }
    }

    function listingSponsorSearch($AppId,$keyword,$location,$countryId='',$categoryId='',$start=0,$rows=10,$type='',$searchType="",$cityId='',$relaxFlag=0,$cType='',$courseLevel='',$minDuration='',$maxDuration='',$listingDetail="")
    {return; /*
	if($searchType!='institute' && $searchType!='course')
	{
		error_log("Am in init search");
        	$this->initSearch();
	}
	else
	{
		error_log("Am in init institute search");
		$this->initInstituteSearch();
	}
        $this->CI->xmlrpc->method('searchListingWithSponsor');
        $request = array (
                array($AppId, 'int'),
                array($keyword, 'string'),
                array($location,'string'),
                array($countryId, 'string'),
                array($categoryId, 'string'),
                array($start, 'int'),
                array($rows, 'int'),
                array($type,'string'),
                array($searchType, 'string'),
                array($cityId, 'string'),
                array($relaxFlag, 'int'),
                array($cType, 'string'),
                array($courseLevel, 'string'),
                array($minDuration, 'string'),
                array($maxDuration, 'string'),
                array($listingDetail, 'string')
                );
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request())
        {
            return $this->CI->xmlrpc->display_error();
        }
        else
        {
            return $this->CI->xmlrpc->display_response();
        } */
    }

    function shikshaApiSearch($AppId,$keyword,$start,$rows,$listingType,$relaxFlag=0,$categoryId="",$filterList="")
    {
        $this->initSearch();
        $this->CI->xmlrpc->method('shikshaApiSearch');
        $request = array (
                array($AppId, 'int'),
                array($keyword, 'string'),
                array($start, 'int'),
                array($rows, 'int'),
                array($listingType, 'string'),
                array($relaxFlag, 'string'),
                array($categoryId, 'string'),
                array($filterList, 'string')
        //$outputData['outputType']= "json";
                );
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request())
        {
            return $this->CI->xmlrpc->display_error();
        }
        else
        {
            return $this->CI->xmlrpc->display_response();
        }
    }

    function documentApiSearch($AppId,$listingType,$listingId)
    {
        $this->initSearch();
        $this->CI->xmlrpc->method('getDocumentDetailFromSearch');
        $request = array (
                array($AppId, 'int'),
                array($listingType, 'string'),
                array($listingId, 'int')
                );
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request())
        {
            return $this->CI->xmlrpc->display_error();
        }
        else
        {
            return $this->CI->xmlrpc->display_response();
        }
    }


    function listingSearch($AppId,$keyword,$countryId,$categoryId,$start,$rows,$type,$searchType='+')
    {
        $this->initSearch();
        $this->CI->xmlrpc->method('searchListingKeyword');
        $request = array (
                array($AppId, 'int'),
                array($keyword, 'string'),
                array($countryId, 'string'),
                array($categoryId, 'string'),
                array($start, 'int'),
                array($rows, 'int'),
                array($type,'string'),
                array($searchType, 'string')
                );
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request())
        {
            return $this->CI->xmlrpc->display_error();
        }
        else
        {
            return $this->CI->xmlrpc->display_response();
        }
    }
    function searchCluster($AppId,$keyword,$location,$type="",$countryId=-1,$categoryId=-1,$searchType='+')
    {
        $this->initSearch();
        $this->CI->xmlrpc->method('searchCluster');
        $request = array (
                array($AppId, 'int'),
                array($keyword, 'string'),
                array($location, 'string'),
                array($type,'string'),
                array($countryId,'string'),
                array($categoryId,'string'),
                array($searchType,'string')
                );
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request())
        {
            return $this->CI->xmlrpc->display_error();
        }
        else
        {
            return $this->CI->xmlrpc->display_response();
        }
    }

    function saveSearch($AppId,$userId,$keyword,$type="",$location="")
    {
        return; /*
        $this->initClientSearch();
        $this->CI->xmlrpc->method('saveSearch');
        $request = array (
                array($AppId, 'int'),
                array($userId, 'int'),
                array($keyword, 'string'),
                array($type,'string'),
                array($location, 'string')
		);
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request())
        {
            return $this->CI->xmlrpc->display_error();
        }
        else
        {
            return $this->CI->xmlrpc->display_response();
        }
        */
    }

    function deleteSaveSearch($AppId,$Id)
    {
        $this->initClientSearch();
        $this->CI->xmlrpc->method('deleteSaveSearch');
        $request = array (
                array($AppId, 'int'),
                array($Id, 'int'),
                );
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request())
        {
            return $this->CI->xmlrpc->display_error();
        }
        else
        {
            return $this->CI->xmlrpc->display_response();
        }
    }

    function updateSaveSearchStatus($AppId,$Id,$status)
    {
        $this->initClientSearch();
        $this->CI->xmlrpc->method('updateSaveSearchStatus');
        $request = array (
                array($AppId, 'int'),
                array($Id, 'int'),
		array($status, 'int')
                );
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request())
        {
            return $this->CI->xmlrpc->display_error();
        }
        else
        {
            return $this->CI->xmlrpc->display_response();
        }
    }

    function updateSaveSearchFrequency($AppId,$Id,$frequency='daily')
    {
        $this->initClientSearch();
        $this->CI->xmlrpc->method('updateSaveSearchFrequency');
        $request = array (
                array($AppId, 'int'),
                array($Id, 'int'),
		array($frequency,'string')
                );
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request())
        {
            return $this->CI->xmlrpc->display_error();
        }
        else
        {
            return $this->CI->xmlrpc->display_response();
        }
    }

    function getSaveSearch($AppId,$userId,$start,$row)
    {
        return; /*
        $this->initClientSearch();
        $this->CI->xmlrpc->method('getSaveSearch');
        $request = array (
                array($AppId, 'int'),
                array($userId, 'int'),
                array($start, 'int'),
                array($row, 'int')
                );
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request())
        {
            return $this->CI->xmlrpc->display_error();
        }
        else
        {
            return $this->CI->xmlrpc->display_response();
        }
        */
    }

    function tagCloud($keyword,$type,$count,$categoryId,$resultCount,$userId,$requestIP,$requestURL)
    {
        $this->initClientSearch();
        $this->CI->xmlrpc->method('tagCloud');
        $request = array (
                array($keyword, 'string'),
                array($type, 'string'),
                array($count,'string'),
                array($categoryId,'string'),
                array($resultCount,'int'),
                array($userId,'int'),
                array($requestIP,'string'),
                array($requestURL,'string')
                );
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request())
        {
            return $this->CI->xmlrpc->display_error();
        }
        else
        {
            return $this->CI->xmlrpc->display_response();
        }
    }

function spellSuggest($keyword,$searchResult)
    {
        $this->initSearch();
        $this->CI->xmlrpc->method('spellSuggest');
        $request = array (
                array($keyword, 'string'),
		array($searchResult, 'string')
                );
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request())
        {
            return $this->CI->xmlrpc->display_error();
        }
        else
        {
            return $this->CI->xmlrpc->display_response();
        }
    }
function checkInstituteTitle($keyword)
 {
        $this->initSearch();
        $this->CI->xmlrpc->method('checkInstituteTitle');
        $request = array (
                array($keyword, 'string'),
                );
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request())
        {
            return $this->CI->xmlrpc->display_error();
        }
        else
        {
            return $this->CI->xmlrpc->display_response();
        }
    }

  function getUserReqInfo($appID,$userId, $listing_type, $listing_type_id) {
        $this->initListing();
        error_log_shiksha("APP ID getCityList =>$appID :: $listing_type_id,$listing_type");
        $this->CI_Instance->xmlrpc->method('sGetUserReqInfoStatus');
        $request = array(array($appID,'int'),array($userId,'string'),array($listing_type,'string'),array($listing_type_id,'string'));
        error_log_shiksha("CLIENT REQUEST getCityList ARRAY".print_r($request,TRUE));
        $this->CI_Instance->xmlrpc->request($request);

        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }

    function add_requestInfo($appID,$requestArray){ //requestArray is actually _POST array
        $this->init();
        /*		$this->CI_Instance->load->helper(array('form'));
                $this->CI_Instance->load->library('validation');
         */
        $this->CI_Instance->xmlrpc->method('sInsertReqInfo');
        //                $request = array(array($appID,'int'),array(array("listing_title" => "TESTtitle","contact_email" => "temp@email.com"),'struct'));
        $request = array(array($appID,'int'),array($requestArray,'struct'));
        //		error_log_shiksha("CLIENT REQUEST ARRAY".print_r($request,TRUE));
        $this->CI_Instance->xmlrpc->request($request);

        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
     }

    function insertCity($appID,$requestArray) {
        $this->init();
        $this->CI_Instance->xmlrpc->method('sInsertCity');
        $request = array(array($appID,'int'),array($requestArray,'struct'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            $this->cacheLib->clearCache('citylist');
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }
	function getCoursesForHomePageS($appId, $categoryId, $countryId, $start, $count, $keyValue,$cityId,$relaxFlag=0){
		error_log_shiksha("DEBUG: LISTING_CLIENT::getInstitutesForHomePages: ENTRY");
		$this->initListing();
		$key = md5('getCoursesForHomePageS'.$appId.$categoryId.$countryId.$start.$count.$keyValue.$cityId.$relaxFlag);
		if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
			$this->CI_Instance->xmlrpc->method('getCoursesForHomePageS');
			$request = array($appId,  $categoryId, $countryId , $start, $count, $keyValue,$cityId,$relaxFlag);
            error_log_shiksha("LISTING:".print_r($request,true));
			$this->CI_Instance->xmlrpc->request($request);
			if ( ! $this->CI_Instance->xmlrpc->send_request()){
				error_log_shiksha("ERROR: LISTING_CLIENT::getInstitutesForHomePageS: FAIL".$this->CI_Instance->xmlrpc->display_error());
				error_log_shiksha("DEBUG: LISTING_CLIENT::getInstitutesForHomePageS: EXIT FAILURE");
				return;
			}else{
				error_log_shiksha("DEBUG: LISTING_CLIENT::getInstitutesForHomePageS: EXIT SUCCESS");
				$response = $this->CI_Instance->xmlrpc->display_response();
				$this->cacheLib->store($key,$response,1800);
				return $response;
			}
		}else{
			error_log_shiksha("DEBUG: LISTING_CLIENT::getInstitutesForHomePageS: EXIT SUCCESS");
			return $this->cacheLib->get($key);
		}
	}

/*
	function getInterestedInstitutes($appId, $categoryId, $cityId,$start, $count){
        $this->initListing();
        $this->CI_Instance->xmlrpc->method('getInterestedInstitutes');
        $request = array($appId, $categoryId, $cityId, $start, $count);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            error_log_shiksha("ERROR: LISTING_CLIENT::getInstitutesForHomePageS: FAIL".$this->CI_Instance->xmlrpc->display_error());
            error_log_shiksha("DEBUG: LISTING_CLIENT::getInstitutesForHomePageS: EXIT FAILURE");
            return;
        }else{
            error_log_shiksha("DEBUG: LISTING_CLIENT::getInstitutesForHomePageS: EXIT SUCCESS");
            $response = $this->CI_Instance->xmlrpc->display_response();
            return $response;
        }
    }
    */


    function getInterestedInstitutes($appId, $categoryId, $cityId,$start, $count){
            $this->initListing();
            $key = md5('getInterestedInstitutes'.$appId.$categoryId.$cityId.$start.$count);
            if($this->cacheLib->get($key)=='ERROR_READING_CACHE')
            {
                   $this->CI_Instance->xmlrpc->method('getInterestedInstitutes');
                   $request = array($appId, $categoryId, $cityId, $start, $count);
                   $this->CI_Instance->xmlrpc->request($request);
                   if ( ! $this->CI_Instance->xmlrpc->send_request()){
                          error_log_shiksha("ERROR: LISTING_CLIENT::getInstitutesForHomePageS: FAIL".$this->CI_Instance->xmlrpc->display_error());
                          error_log_shiksha("DEBUG: LISTING_CLIENT::getInstitutesForHomePageS: EXIT FAILURE");
                          return;
                   }else{
                          error_log_shiksha("DEBUG: LISTING_CLIENT::getInstitutesForHomePageS: EXIT SUCCESS");
                          $response = $this->CI_Instance->xmlrpc->display_response();
                          $this->cacheLib->store($key,$response);
                          return $response;
                   }
            }
            else
                  return  $this->cacheLib->get($key);
                                                                                                                                                                   }

      function getCategoryContentParams($virtualSubCatId,$virtualCourseLevel,$virtualMode){

	$this->initCategoryRevampRead();
        $key = md5('getCategoryContentParams'.$virtualSubCatId.$virtualCourseLevel.$virtualMode);
        if($this->cacheLib->get($key)=='ERROR_READING_CACHE')
        {
            // error_log("In LC: ".print_r($queryCmd,true),3,"/home/infoedge/Desktop/log.txt");            
            $this->CI_Instance->xmlrpc->method('getCategoryContentParams');
            $request = array($virtualSubCatId,$virtualCourseLevel,$virtualMode);
	    $this->CI_Instance->xmlrpc->request($request);

            if ( ! $this->CI_Instance->xmlrpc->send_request()) {               
                return $this->CI_Instance->xmlrpc->display_error();                
            }
            else{
                $timeforcontent=1800;
                $response=$this->CI_Instance->xmlrpc->display_response();
                $this->cacheLib->store($key,$response,$timeforcontent,'catpages');
                return $response;
                }

        }
        else
        {
            return  $this->cacheLib->get($key);
        }
    }

    function getTopInstitutes($appId,$categoryId)
    {
        $this->initListing();
        $key = md5('getTopInstitutes'.$appId.$categoryId);
        $this->CI_Instance->xmlrpc->method('getTopInstitutes');
  //      if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
            $request = array($appId, $categoryId);
            error_log(print_r($request,true).'HEREHERE');
            $this->CI_Instance->xmlrpc->request($request);
            if(!$this->CI_Instance->xmlrpc->send_request()){
                return;
            }
            else
            {
                $response = $this->CI_Instance->xmlrpc->display_response();
//                $this->cacheLib->store($key,$response,1800);
                return $response;
            }
//        }
    }


    function getListingsForNaukriShiksha($appId, $categoryId, $subcategoryId,$countryId,$cityId,$courseLevel = '',$courseLevel1 = '',$courseType = '',$start = 0,$count = 15,$pagename,$noofcourses,$overridecache = 0, $optionalCategoryId = "")
    {
        $starttime = microtime(true);
        // $this->init();
	// By Amit Kuksal on 17th March 2011 as we would read from the 201 server..
        $this->initCategoryRevampRead();

        //Cache Implementation
        $reg_ex = " ";
        $trcourseLevel  = str_replace(" ","",$courseLevel);
        $trcourseLevel1  = str_replace(" ","",$courseLevel1);
        $trcourseLevel1 = $trcourseLevel1 == 'NULL' ? 'All' : $trcourseLevel1;
        $trcourseType = str_replace(" ","",$courseType);
        if($pagename == "categorypages" || $pagename == "naukrishiksha" || $pagename == "countrypage")
            $rotate = 1;
        else
            $rotate = 0;

        // Forming the cache key part now from Course Levels and Course Type information if these are array | By Amit Kuksal on 24th Feb 2011 for Category Page revamp.

        // In case $optionalCategoryId has the categories IDs as value then we have to get the records for these categories
        if($optionalCategoryId != "")
            $resultsToFetchForCategories = $optionalCategoryId;
        else // Else need to get the records those belong to $subcategoryId
            $resultsToFetchForCategories = $subcategoryId;

        // Forming key part from subcategory..
        if(strpos($resultsToFetchForCategories, ",") !== false) {
            $keyPartFromSubcategoryId = str_replace(",", "-", $resultsToFetchForCategories);
        } else {
            $keyPartFromSubcategoryId = $resultsToFetchForCategories;
        }

        // Forming key part from Course Level..
        if(is_array($courseLevel)) {
            $keyPartFromCourseLevel = str_replace(" ","",implode("-", $courseLevel));
        } else {
            $keyPartFromCourseLevel = str_replace(" ","",$courseLevel);
        }

        // Forming key part from Course Level 1..
        if(is_array($trcourseLevel1)) {
            $keyPartFromCourseLevel1 = str_replace(" ","", str_replace("NULL","All", implode("-", $courseLevel1)));
        } else {
            $keyPartFromCourseLevel1 = str_replace(" ","",$courseLevel1);
            $keyPartFromCourseLevel1 == 'NULL' ? 'All' : $keyPartFromCourseLevel1;
        }

        // Forming key part from Course Type..
        if(is_array($courseType)) {
            $keyPartFromCourseType = str_replace(" ","",implode("-", $courseType));
        } else {
            $keyPartFromCourseType = str_replace(" ","",$courseType);
        }

        // error_log("\n\nLISTING CLIENT 1711 \n SUbcat : ".print_r($keyPartFromSubcategoryId,true)."\n c level = ".print_r($keyPartFromCourseLevel,true)."\n c level 1 = ".print_r($keyPartFromCourseLevel1,true)."\n c Type = ".print_r($keyPartFromCourseType,true),3,'/home/infoedge/Desktop/log.txt');

        $key = md5('getListingsForNaukriShiksha'.$appId.$categoryId.$countryId.$keyPartFromSubcategoryId.$cityId.$keyPartFromCourseLevel.$keyPartFromCourseLevel1.$rotate.$keyPartFromCourseType);
        
        // End of Forming the cache key part from Course Levels and Course Type information if these are array | By Amit Kuksal on 24th Feb 2011 for Category Page revamp.
 
        // $key = md5('getListingsForNaukriShiksha'.$appId.$categoryId.$countryId.$subcategoryId.$cityId.$trcourseLevel.$trcourseLevel1.$rotate.$trcourseType);

        if($pagename == "naukrishiksha")
        {
            if($trcourseType == 'All' || $trcourseLevel == 'All')
            {
               // $key = md5('getListingsForNaukriShiksha'.$appId.$categoryId.$countryId.$subcategoryId.$cityId.$trcourseLevel.$trcourseLevel1.$trcourseType.$rotate.$pagename);
		$key = md5('getListingsForNaukriShiksha'.$appId.$categoryId.$countryId.$keyPartFromSubcategoryId.$cityId.$keyPartFromCourseLevel.$keyPartFromCourseLevel1.$keyPartFromCourseType.$rotate.$pagename);
                // error_log('getListingsForNaukriShiksha'.$appId.$categoryId.$subcategoryId.$cityId.$trcourseLevel.$trcourseLevel1.$trcourseType.$rotate.$pagename);
            }
        }
        $cachecount = CACHE_COUNT;
        if($categoryId == 3 && $cityId == 10223)
        {
            $cachecount = 150;
        }
        if($this->cacheLib->get($key)=='ERROR_READING_CACHE' || ($start + $count) > $cachecount || $overridecache)
        {
            $dataret = $this->getdata($appId, $categoryId, $subcategoryId,$countryId,$cityId,$courseLevel,$courseLevel1,$courseType,$start,$count,$pagename,$noofcourses,$key, $optionalCategoryId);
        }
        else
        {
            $dataret = $this->preparedataNaukriShiksha($key,$start,$count,$pagename);
        }
        $endtime = microtime(true);
        error_log(($endtime - $starttime).'TOTALTIMETAKEN');
        return $dataret;
    }

    function preparedataNaukriShiksha1($key,$start,$count,$pagename)
    {
    // Data to be prepared from cache (store main,paid,free separately) Create cache from main rotation,paid,free if required
    // Check for count if main,paid,free even needs to be executed (send the array for the queries to be executed)?? Queries to be executed always as the pagination may vary due to new addition of institutes/courses.
    //
        $this->init();

        $appId = 1;
        //retrieve data from cache
        $response = $this->cacheLib->get($key);
        error_log($key.'KEYKEY');
        error_log($response.'RESPONSE');
        $totalcount = (count($response['instituteIds'])) ;
        $instituteIds = $response['instituteIds'];
        $countArray = $response['countArray'];
        $bannerArray = $response['bannerarr'];
        $i = 0;
        for($k = 0;$k<$totalcount;$k++)
        {
            if($i >= $count)
            {
                break;
            }
            if($k < $start)
            {
                continue;
            }
            foreach($response['instituteIds'][$k] as $instituteId => $coursearr)
            {
                //if institute not present then make the request
                $institutekey = md5('ninstitutekey'.$appId.$instituteId);

                $data =	$this->cacheLib->get($institutekey);
                $institutesarr[$i]['institute'] = $data;
                //                $coursecount = count($coursearr);
                                    for($mn = 0;$mn < count($countArray['categoryselector']);$mn++)
                                    {
                                        if($countArray['categoryselector'][$mn] == $data['id'])
                                        {
                                               $data['flagname'] = 'category';
                                        }
                                        }
                error_log($data['flagname'].'FLAGNAME');
                        $coursecount = 10;
                
		if($pagename != "categorypages") {  // We will show all the courses in Category pages..
			if($data['flagname'] == "main")
        	            $coursecount = 2;
                	if($data['flagname'] == "paid")
	                    $coursecount = 2;
        	        if($data['flagname'] == "free")
                	    $coursecount = 1;
		}

                $coursecount = count($coursearr) < $coursecount ? count($coursearr) : $coursecount;
                $coursearray = array();

                for($j = 0;$j < $coursecount;$j++)
                {
                    $coursekey = md5('ncoursekey'.$appId.$coursearr[$j]);
                    $coursearray[$j] = $this->cacheLib->get($coursekey);
                }

                $institutesarr[$i]['courses'] = $coursearray;
            }
            $i++;
        }
        $finalresponse['countArray'] = $response['countArray'];
      /*  if($pagename == "categorypages" || $pagename == "naukrishiksha")
        {
            $this->rotateInstitutes($institutesarr);
        }*/
        $finalresponse['institutesarr'] = $institutesarr;
        return $finalresponse;
    }

    function preparedataNaukriShiksha($key,$start,$count,$pagename)
    {
        $starttime1 = microtime(true);
       //  $this->init();
	// By Amit Kuksal on 17th March 2011 as we would read from the 201 server..
        $this->initCategoryRevampRead();


        $institutesarr = array();
        $mainInstitutes = array();
        $appId = 1;
        //retrieve data from cache
        $response = $this->cacheLib->get($key);
        $categorycount = count($response['categoryinstituteIds']);        
        $maincount = count($response['maininstituteIds']);
        $paidcount = count($response['paidinstituteIds']);
        $freecount = count($response['freeinstituteIds']);
        $totalcount = $categorycount + $maincount + $paidcount + $freecount;
        $countArray = $response['countArray'];
        $i = 0;
        $mainInstitutes = array();
        $paidInstitutes = array();
        $mainInstitutes = $response['maininstituteIds'];
        $paidInstitutes = $response['paidinstituteIds'];
        $bannerArray = $response['bannerarr'];

        $rotate = 0;
        $rotationkey = md5('categoryrotationkey').$key;
        $rotationkeyns = md5('naukrishiksharotationkey').$key;
        $rotationkeycp = md5('countrypagerotationkey').$key;

        $bankeycat = md5('bancategoryrotationkey').$key;
        $bankeyns = md5('bannaukrishiksharotationkey').$key;
        $bankeycp = md5('bancountrypagerotationkey').$key;

        $token = rand(1,CATEGORY_HOME_PAGE_COLLEGES_COUNT);
        // error_log("\n\n Amit token: ".$token.", CATEGORY_HOME_PAGE_COLLEGES_COUNT =  ".print_r(CATEGORY_HOME_PAGE_COLLEGES_COUNT,true),3,'/home/infoedge/Desktop/log.txt');
        $arr = array();
        $bancount = count($bannerArray) == 0 ? 0 : count($bannerArray);
        //$bantoken = rand(1,$bancount);
        $bantoken = 1;
        // error_log("\n\n Amit ban count = ".$bancount." , ban token ".print_r($bantoken,true),3,'/home/infoedge/Desktop/log.txt');
        $timeforkeys = 1800;
        $timeforbankeys = 12200;
        //error_log("\n\n Neha bantoken = ".$bantoken." , ban tokenbantoken ",3,'/home/infoedge/Desktop/log.txt');
        if(trim($pagename) == "categorypages")
        {
            if($this->cacheLib->get($rotationkey) == 'ERROR_READING_CACHE')
            {
                $this->cacheLib->store($rotationkey,$token,$timeforkeys,'catpages',1);

                if($this->cacheLib->get($bankeycat) == 'ERROR_READING_CACHE')
                {
                    $this->cacheLib->store($bankeycat,$bantoken,$timeforbankeys,'catpages',1);
                       // error_log("\n\n Neha3 bantoken = ".$bantoken." , ban tokenbantoken ",3,'/home/infoedge/Desktop/log.txt');

                }
                else
                {
                    $bantoken = $this->cacheLib->get($bankeycat);
                    // error_log("\n\n Neha2 bantoken = ".$bantoken." , ban tokenbantoken ".count($bannerArray),3,'/home/infoedge/Desktop/log.txt');
                    $this->cacheLib->store($bankeycat,(($bantoken + 1) > count($bannerArray) ? 0: $bantoken + 1),$timeforbankeys,'catpages',1);

                }
            }
            else
            {
                $token = $this->cacheLib->get($rotationkey);
                // error_log("\n\n Amit token =  ".print_r($token,true),3,'/home/infoedge/Desktop/log.txt');
              
                if($this->cacheLib->get($bankeycat) == 'ERROR_READING_CACHE')
                {

                    $this->cacheLib->store($bankeycat,$bantoken,$timeforbankeys,'catpages',1);
                }
                else
                {
                    $bantoken = $this->cacheLib->get($bankeycat);
                }
            }
        }
                    // error_log("\n\n Neha4 bantoken = ".$bantoken." , ban tokenbantoken ",3,'/home/infoedge/Desktop/log.txt');
        if($pagename == "naukrishiksha")
        {
            if($this->cacheLib->get($rotationkeyns) == 'ERROR_READING_CACHE')
            {
                $this->cacheLib->store($rotationkeyns,$token,$timeforkeys,'catpages',1);
                if($this->cacheLib->get($bankeyns) == 'ERROR_READING_CACHE')
                {
                    $this->cacheLib->store($bankeyns,$bantoken,$timeforbankeys,'catpages',1);
                }
                else
                {
                    $bantoken = $this->cacheLib->get($bankeyns);
                    $this->cacheLib->store($bankeyns,(($bantoken + 1) > count($bannerArray) ? 0: $bantoken + 1),$timeforbankeys,'catpages',1);
                }
            }
            else
            {
                $token = $this->cacheLib->get($rotationkeyns);
                if($this->cacheLib->get($bankeyns) == 'ERROR_READING_CACHE')
                {
                    $this->cacheLib->store($bankeyns,$bantoken,$timeforbankeys,'catpages',1);
                }
                else
                {
                    $bantoken = $this->cacheLib->get($bankeyns);
                }
            }
        }

        if($pagename == "countrypage")
        {
            if($this->cacheLib->get($rotationkeycp) == 'ERROR_READING_CACHE')
            {
                $this->cacheLib->store($rotationkeycp,$token,$timeforkeys,'catpages',1);
                if($this->cacheLib->get($bankeycp) == 'ERROR_READING_CACHE')
                {
                    $this->cacheLib->store($bankeycp,$bantoken,$timeforbankeys,'catpages',1);
                }
                else
                {
                    $bantoken = $this->cacheLib->get($bankeycp);
                    $this->cacheLib->store($bankeycp,(($bantoken + 1) > count($bannerArray) ? 0: $bantoken + 1),$timeforbankeys,'catpages',1);
                }

            }
            else
            {
                $token = $this->cacheLib->get($rotationkeycp);
                if($this->cacheLib->get($bankeycp) == 'ERROR_READING_CACHE')
                {
                    $this->cacheLib->store($bankeycp,$bantoken,$timeforbankeys,'catpages',1);
                }
                else
                {
                    $bantoken = $this->cacheLib->get($bankeycp);
                }
            }
        }
        if(($pagename == "categorypages" || $pagename == "naukrishiksha" || $pagename == "countrypage"))
        {

            // error_log("\n\n Amit token = ".$token." , mainInstitutes ".print_r($mainInstitutes,true),3,'/home/infoedge/Desktop/log.txt');
            $this->rotateInstitutes($mainInstitutes,$token%count($mainInstitutes));
            $this->rotateInstitutes($paidInstitutes,$token%count($paidInstitutes));
        }

        $bantoken -= 1;
        // error_log("\n\n Amit ban count = ".$bancount." , ban token ".print_r($bantoken,true),3,'/home/infoedge/Desktop/log.txt');
        //check for the category selector institutes and their rotations
        $keyval = 0;
        $kl = 0;
        $jl = 0;
        $instituteofcatsel = 0;
        $categoryinsids = array();

        if($bancount != 0)
        {
//            $keyval = $bantoken%$bancount;
            $keyval = $bantoken;
            $cn = 0;
            if(is_array($bannerArray[$keyval]))
            $countArray['selectedIcon'] = $bannerArray[$keyval]['bannerurl'];
            // error_log("\n\n Amit ban count = ".$countArray['selectedIcon']." , ban token ",3,'/home/infoedge/Desktop/log.txt');

            $starttime = microtime(true);

            do
            {
                $instituteofcatsel = $bannerArray[$keyval]['institute_id'];
                $keyval += 1;
                if($keyval >= count($bannerArray))
                    $keyval = 0;
                    $cn++;
                    // error_log("\n\n Amit cn = ".$cn." keyval = $keyval, instituteofcatsel ".print_r($instituteofcatsel,true),3,'/home/infoedge/Desktop/log.txt');

            } while($instituteofcatsel == -1 && $cn < count($bannerArray));
            //error_log("\n\n Amit categoryinstituteIds :s ".print_r($response['categoryinstituteIds'],true),3,'/home/infoedge/Desktop/log.txt');
            $endtime = microtime(true);
            error_log($endtime-$starttime.'TIMETIME');
            $starttime = microtime(true);
            // error_log("\n\n Amit categoryinstituteIds :s ".print_r($response['categoryinstituteIds'],true),3,'/home/infoedge/Desktop/log.txt');
            while($kl != 1 && $jl < count($response['categoryinstituteIds']))
            {
            foreach($response['categoryinstituteIds'][$jl] as $key1 => $value)
            {
                // error_log("\n\n kl = $kl, jl = $jl, Amit key1 = ".$key1." value =  ".print_r($value,true),3,'/home/infoedge/Desktop/log.txt');

                if($key1 == "'".$instituteofcatsel."'")
                {
                    $categoryinsids[0] = $response['categoryinstituteIds'][$jl++];
                    $kl = 1;
                    break;
                }
                $jl++;
            }
            }
            $endtime = microtime(true);

            error_log($endtime - $starttime . 'TIME143');
            $starttime = microtime(true);
            if($jl == count($response['categoryinstituteIds']))
                $jl = 0;
        }
        // error_log("\n\n Amit jl = ".$jl.", kl = ".$kl." , categoryinstituteIds ".print_r($response['categoryinstituteIds'],true),3,'/home/infoedge/Desktop/log.txt');
        $categoryinstituteIdsCountVar = count($response['categoryinstituteIds']);
        while($kl < $categoryinstituteIdsCountVar)
        {
                // error_log("\n\n Amit jl = ".$jl.", kl = ".$kl." , categoryinstituteIds ".print_r($response['categoryinstituteIds'][$jl],true),3,'/home/infoedge/Desktop/log.txt');
                $categoryinsids[$kl++] = $response['categoryinstituteIds'][$jl++];
                if($jl >= $categoryinstituteIdsCountVar)
                {
                    $jl = 0;
                }

        }
        // error_log("\n\n Amit categoryinstituteIds ".print_r($categoryinsids,true),3,'/home/infoedge/Desktop/log.txt');
        $endtime = microtime(true);
        error_log($endtime - $starttime . 'TIME2');
        $instituteIds = array_merge($categoryinsids,$mainInstitutes,$paidInstitutes,$response['freeinstituteIds']);

        for($k = 0;$k<$totalcount;$k++)
        {
            if($i >= $count)
            {
                break;
            }
            if($k < $start)
            {
                continue;
            }

            foreach($instituteIds[$k] as $instituteId => $coursearr)
            {
                //if institute not present then make the request
                $institutekey = md5('ninstitutekey'.$appId.$instituteId);
                $data =	$this->cacheLib->get($institutekey);
					
                $coursecount = 10;
                for($mn = 0;$mn < count($countArray['categoryselector']);$mn++)
                {
                    if($countArray['categoryselector'][$mn] == $data['id'])
                    {
                        $data['flagname'] = 'category';
                    }
                }
                
                if($pagename != "categorypages" AND $pagename != "categorymostviewed") {  // We will show all the courses in Category pages..
                    if($data['flagname'] == "main")
                        $coursecount = 2;
                    if($data['flagname'] == "paid")
                        $coursecount = 2;
                    if($data['flagname'] == "free")
                        $coursecount = 1;

                } else {

                    $coursecount = count($coursearr);                    

                }
                // error_log("\n ids arr : ".print_r($institutesarr,true),3,'/home/infoedge/Desktop/log.txt');
                $institutesarr[$i]['institute'] = $data;
                $coursecount = count($coursearr) < $coursecount ? count($coursearr) : $coursecount;
                $coursearray = array();
                $courseIDsNotInCache = "";
                for($j = 0;$j < $coursecount;$j++)
                {
                    $coursekey = md5('ncoursekey'.$appId.$coursearr[$j]);
                    $coursearray[$j] = $this->cacheLib->get($coursekey);

                    // Now if any course is missed from APC Cache reading then collecting all such courses' ids (Ticket #328)..
                    if($coursearray[$j] == "ERROR_READING_CACHE") {
                        // error_log("\n\n instituteId :".print_r($institutesarr[$i]['institute'], true).", course key: $coursekey , course $j = ".print_r($coursearray[$j],true),3,'/home/infoedge/Desktop/log.txt');
                        $coursesKey[$coursearr[$j]] = $j;

                        if($courseIDsNotInCache == "") {
                            $courseIDsNotInCache = $coursearr[$j];
                        } else {
                            $courseIDsNotInCache .= ", ".$coursearr[$j];
                        }

                    }   // End of if($coursearray[$j] == "ERROR_READING_CACHE").

                }

                    // Now making the DB call to get the APC Cache read missed courses' related info..
                    if($courseIDsNotInCache != "") {
                        
                        $courseResponseArray = $this->getCoursesInformation($appId, $courseIDsNotInCache, $pagename);
                        
                        // Now putting all courses in to the actual course array..
                        foreach($courseResponseArray as $key => $value) {                            
                            $coursearray[$coursesKey[$courseResponseArray[$key]['course_id']]] = $courseResponseArray[$key];
                        }
                        
                    }    // End of if($courseIDsNotInCache != "").

                    unset($coursesKey);
                    
                    $institutesarr[$i]['courses'] = $coursearray;
            }
            $i++;
        }

        $finalresponse['countArray'] = $countArray;
        $finalresponse['institutesarr'] = $institutesarr;
        $endtime = microtime(true);
        error_log($endtime - $starttime1.'TIMETAKENTORETRIEVEDATA');
        return $finalresponse;
    }



    function getCoursesInformation($appId, $courseIds, $pagename)
    {        
        $this->initCategoryRevampRead();

        $this->CI_Instance->xmlrpc->method('getCoursesInformation');
        $request = array($appId, $courseIds, $pagename);

        $starttime1 = microtime(true);
        $this->CI_Instance->xmlrpc->request($request);
        if(!$this->CI_Instance->xmlrpc->send_request()){
            return;
        }
        else
        {
            $response = json_decode(base64_decode($this->CI_Instance->xmlrpc->display_response()),true);
            error_log(print_r($response,true).'RESPONSE1');
            $endtime = microtime(true);
            error_log(($endtime - $starttime1).'TIMETAKENFROMBACKEND');
        }
        return ($response);
    }


    function getdata1($appId, $categoryId, $subcategoryId,$countryId,$cityId,$courseLevel = '',$courseLevel1 = '',$courseType = '',$start,$count,$pagename,$noofcourses,$key)
    {
        $this->init();
        $this->CI_Instance->xmlrpc->method('getListingsForNaukriShiksha');
        $vcount = $count;
        $recordstobefetched = $start + $count;

        if($recordstobefetched <= CACHE_COUNT)
            $vcount = CACHE_COUNT;
        $request = array($appId, $categoryId, $subcategoryId,$countryId,$cityId,$courseLevel,$courseLevel1,$courseType,$start,$vcount,$pagename,$noofcourses);
        $this->CI_Instance->xmlrpc->request($request);
        if(!$this->CI_Instance->xmlrpc->send_request()){
            return;
        }else{
            $response = json_decode($this->CI_Instance->xmlrpc->display_response(),true);
            $countArray = $response['countArray'];
            $instituteIds = $response['InstituteIds'];
            $institutes = $response['Institutes'];
            $courses = $response['Courses'];
           /* $instituteIds = array_merge($response['categoryInstituteIds'],$response['mainInstituteIds'],$response['paidInstituteIds'],$response['freeInstituteIds']);
            $institutes = array_merge($response['categoryInstitutes'],$response['mainInstitutes'],$response['paidInstitutes'],$response['freeInstitutes']);
            error_log(print_r(array_keys($instituteIds),true).'AASASAS');
            $courses = array_merge($response['categoryCourses'],$response['mainCourses'],$response['paidCourses'],$response['freeCourses']);*/
            //put data in cache
            if($recordstobefetched <= CACHE_COUNT)
            {
                $arrayforcache['instituteIds'] = $instituteIds;
                $arrayforcache['countArray'] = $countArray;
                //put data in cache
                $this->cacheLib->store($key,$arrayforcache,$timeforkeys);
                //Second Level Cache
                for($j = 0;$j<count($institutes);$j++)
                {
                    $institutekey = md5('ninstitutekey'.$appId."'".$institutes[$j]['id']."'");
                    $this->cacheLib->store($institutekey,$institutes[$j],1800);
                }
                for($k = 0;$k < count($courses);$k++)
                {
                    $coursekey = md5('ncoursekey'.$appId.$courses[$k]['course_id']);
                    $this->cacheLib->store($coursekey,$courses[$k],1800);
                }
                return($this->preparedataNaukriShiksha($key,$start,$count));
            }
            else
            {
              //cache code ends
              //return $courses;
                if($recordstobefetched > CACHE_COUNT)
                {
                    $institutestoSend = array();
                    //prepare data for front-end
                    $totalcount = (count($institutes) < $count) ? count($institutes) : $count;
                    for($i = 0;$i<$totalcount;$i++)
                    {
                        $institutestoSend[$i]['institute'] = $institutes[$i];
                        $instituteid = $institutes[$i]['id'];
                        $cocount = count($instituteIds[$i]["'".$instituteid."'"]);
                        $coursecount = 10;
                        if($institutes[$i]['flagname'] == "main")
                            $coursecount = 2;
                        if($institutes[$i]['flagname'] == "paid")
                            $coursecount = 2;
                        if($institutes[$i]['flagname'] == "free")
                            $coursecount = 1;

                        $coursecount = $cocount < $coursecount ? $cocount : $coursecount;
                        $coursearray = array();
                        for($j = 0;$j < $coursecount;$j++)
                        {
                            $coursekey = md5('ncoursekey'.$appId.$instituteIds[$i]["'".$instituteid."'"][$j]);
                            for($k = 0;$k<count($courses);$k++)
                            {
                                if($courses[$k]['course_id'] == $instituteIds[$i]["'".$instituteid."'"][$j])
                                {
                                    $coursearray[] = $courses[$k];
                                    break;
                                }
                            }
                        }
                        $institutestoSend[$i]['courses'] = $coursearray;
                    }

                    $finalresponse['countArray'] = (array)$countArray;
             /*       if($pagename == "categorypages" || $pagename == "naukrishiksha")
                    {
                        $this->rotateInstitutes($institutestoSend);
                    }*/
                    $finalresponse['institutesarr'] = (array)$institutestoSend;
                    return $finalresponse;
                }
            }
        }
    }

    function getdata($appId, $categoryId, $subcategoryId,$countryId,$cityId,$courseLevel = '',$courseLevel1 = '',$courseType = '',$start,$count,$pagename,$noofcourses,$key, $optionalCategoryId = "")
    {
        // $this->init();
        // By Amit Kuksal on 17th March 2011 as we would read from the 201 server..
        $this->initCategoryRevampRead();

        $this->CI_Instance->xmlrpc->method('getListingsForNaukriShiksha');
        $vcount = $count;
        $vstart = $start;
        $recordstobefetched = $start + $count;
        $cachecount = CACHE_COUNT;
        if($categoryId == 3 && $cityId == 10223)
        {
            $cachecount = 150;
        }
        if($recordstobefetched <= $cachecount)
        {
            $vcount = $cachecount;
            $vstart = 0;
        }

        // $request = array($appId, $categoryId, $subcategoryId,$countryId,$cityId,$courseLevel,$courseLevel1,$courseType,$vstart,$vcount,$pagename,$noofcourses);

        // Updated by Amit Kuksal on 18th Feb 2011 for Category Page revamp || Paasing the Course Levels and Course Type information as an array if required..
        $request = array($appId, $categoryId, $subcategoryId,$countryId,$cityId, is_array($courseLevel) ? array($courseLevel, 'array') : $courseLevel, is_array($courseLevel1) ? array($courseLevel1, 'array') : $courseLevel1, is_array($courseType) ? array($courseType, 'array') : $courseType,$vstart,$vcount,$pagename,$noofcourses, $optionalCategoryId);

        $starttime1 = microtime(true);
        $this->CI_Instance->xmlrpc->request($request);
        if(!$this->CI_Instance->xmlrpc->send_request()){
            return;
        }
        else
        {
            $response = json_decode(base64_decode($this->CI_Instance->xmlrpc->display_response()),true);
            error_log(print_r($response,true).'RESPONSE1');
            $endtime = microtime(true);
            error_log(($endtime - $starttime1).'TIMETAKENFROMBACKEND');
            $starttime2 = microtime(true);
            $countArray = $response['countArray'];
            $mainInstitutes = array();
            $paidInstitutes = array();
            $mainInstitutes = $response['mainInstituteIds'];
            $paidInstitutes = $response['paidInstituteIds'];
            $instituteIds = array_merge($response['categoryInstituteIds'],$mainInstitutes,$paidInstitutes,$response['freeInstituteIds']);
            $institutes = array_merge($response['categoryInstitutes'],$response['mainInstitutes'],$response['paidInstitutes'],$response['freeInstitutes']);
            $courses = array_merge($response['categoryCourses'],$response['mainCourses'],$response['paidCourses'],$response['freeCourses']);
            //put data in cache
            $starttime = microtime(true);
            if($recordstobefetched <= $cachecount)
            {
                $arrayforcache['categoryinstituteIds'] = $response['categoryInstituteIds'];
                $arrayforcache['maininstituteIds'] = $response['mainInstituteIds'];
                $arrayforcache['paidinstituteIds'] = $response['paidInstituteIds'];
                $arrayforcache['freeinstituteIds'] = $response['freeInstituteIds'];
                $arrayforcache['countArray'] = $countArray;
                $arrayforcache['bannerarr'] = $response['bannerarr'];
                // error_log("\n\n Amit categoryInstituteIds =  ".print_r($response['categoryInstituteIds'],true),3,'/home/infoedge/Desktop/log.txt');
                //put data in cache
                $this->cacheLib->store($key,$arrayforcache,43200,'catpages',1);
                //Second Level Cache
                $endtime1 = microtime(true);
                error_log($endtime1 - $starttime2.'TIMETAKENTOCREATEFIRSTCACHE');
                error_log(count($institutes).'COUNTOFCOURSES');
                for($j = 0;$j<count($institutes);$j++)
                {
                    $institutekey = md5('ninstitutekey'.$appId."'".$institutes[$j]['id']."'");
                    $this->cacheLib->store($institutekey,$institutes[$j],43200,'catpages',1);
                }
                $endtime2 = microtime(true);
                error_log($endtime2 - $endtime1.'TIMETAKENTOCREATEINSCACHE');
                error_log(count($courses).'COUNTOFCOURSES');
                for($k = 0;$k < count($courses);$k++)
                {
                    $coursekey = md5('ncoursekey'.$appId.$courses[$k]['course_id']);
                    $this->cacheLib->store($coursekey,$courses[$k],43200,'catpages',1);
                }
                $endtime3 = microtime(true);
                error_log($endtime3 - $starttime.'TIMETIMENEHA123');
                return($this->preparedataNaukriShiksha($key,$start,$count,$pagename));
            }
            else
            {
                //cache code ends
                //return $courses;
                if($recordstobefetched > $cachecount)
                {
                    $institutestoSend = array();
                    //prepare data for front-end
                    $totalcount = (count($institutes) < $count) ? count($institutes) : $count;
                    for($i = 0;$i<$totalcount;$i++)
                    {
                        $institutestoSend[$i]['institute'] = $institutes[$i];
                        $instituteid = $institutes[$i]['id'];
                        $cocount = count($instituteIds[$i]["'".$instituteid."'"]);
                        $coursecount = 10;
                        if($institutes[$i]['flagname'] == "main")
                            $coursecount = 2;
                        if($institutes[$i]['flagname'] == "paid")
                            $coursecount = 2;
                        if($institutes[$i]['flagname'] == "free")
                            $coursecount = 1;

                        $coursecount = $cocount < $coursecount ? $cocount : $coursecount;
                        $coursearray = array();
                        for($j = 0;$j < $coursecount;$j++)
                        {
                            $coursekey = md5('ncoursekey'.$appId.$instituteIds[$i]["'".$instituteid."'"][$j]);
                            for($k = 0;$k<count($courses);$k++)
                            {
                                if($courses[$k]['course_id'] == $instituteIds[$i]["'".$instituteid."'"][$j])
                                {
                                    $coursearray[] = $courses[$k];
                                    break;
                                }
                            }
                        }
                        $institutestoSend[$i]['courses'] = $coursearray;
                    }

                    $finalresponse['countArray'] = (array)$countArray;
                    $finalresponse['institutesarr'] = (array)$institutestoSend;
                    return $finalresponse;
                }
            }
        }
    }

    function rotateInstitutes(& $institutesarr,$token) {
        $toggler = array();
        $toggler = $institutesarr;
        if(count($toggler) === 0) return;
        for($instituteCount = 0, $togglerCount = $token; ;) {
            $institutesarr[$instituteCount++] = $toggler[$togglerCount];
            $togglerCount = ($togglerCount == count($toggler) -1) ? 0 : ($togglerCount + 1);
            if($togglerCount == $token) break;
        }
    }

	function getInstitutesForHomePageS($appId, $categoryId, $countryId, $start, $count, $keyValue,$cityId,$relaxFlag = 0){
		error_log_shiksha("DEBUG: LISTING_CLIENT::getInstitutesForHomePages: ENTRY");
		$this->initListing();
		$key = md5('getInstitutesForHomePageS'.$appId.$categoryId.$countryId.$start.$count.$keyValue.$cityId.$relaxFlag);
		if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
			$this->CI_Instance->xmlrpc->method('getInstitutesForHomePageS');
			$request = array($appId,  $categoryId, $countryId , $start, $count, $keyValue,$cityId,$relaxFlag);
			error_log_shiksha("LISTING:".print_r($request,true));
			$this->CI_Instance->xmlrpc->request($request);
			if ( ! $this->CI_Instance->xmlrpc->send_request()){
				error_log_shiksha("ERROR: LISTING_CLIENT::getInstitutesForHomePageS: FAIL".$this->CI_Instance->xmlrpc->display_error());
				error_log_shiksha("DEBUG: LISTING_CLIENT::getInstitutesForHomePageS: EXIT FAILURE");
				return;
			}else{
				error_log_shiksha("DEBUG: LISTING_CLIENT::getInstitutesForHomePageS: EXIT SUCCESS");
				$response = $this->CI_Instance->xmlrpc->display_response();
				$this->cacheLib->store($key,$response,1200);
				return $response;
			}
		}else{
			error_log_shiksha("DEBUG: LISTING_CLIENT::getInstitutesForHomePageS: EXIT SUCCESS");
			return $this->cacheLib->get($key);
		}
	}
	function getScholarshipsForHomePageS($appId, $categoryId, $countryId, $start, $count, $keyValue, $relaxFlag = 0,$cityId = 1){
		error_log_shiksha("DEBUG: LISTING_CLIENT::getScholarshipsForHomePageS: ENTRY");
		$this->initListing();
		$key = md5('getScholarshipsForHomePageS'.$appId.$categoryId.$countryId.$start.$count.$keyValue.$relaxFlag.$cityId);
		if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
			$this->CI_Instance->xmlrpc->method('getScholarshipsForHomePageS');
			$request = array($appId,  $categoryId, $countryId , $start, $count, $keyValue,$relaxFlag,$cityId);
			error_log_shiksha("LISTING:".print_r($request,true));
			$this->CI_Instance->xmlrpc->request($request);
			if ( ! $this->CI_Instance->xmlrpc->send_request()){
				error_log_shiksha("ERROR: LISTING_CLIENT::getScholarshipsForHomePageS: FAIL".$this->CI_Instance->xmlrpc->display_error());
				error_log_shiksha("DEBUG: LISTING_CLIENT::getScholarshipsForHomePageS: EXIT FAILURE");
				return;
			}else{
				error_log_shiksha("DEBUG: LISTING_CLIENT::getScholarshipsForHomePageS: EXIT SUCCESS");
				$response = $this->CI_Instance->xmlrpc->display_response();
				$this->cacheLib->store($key,$response,1800);
				return $response;
			}
		}else{
			error_log_shiksha("DEBUG: LISTING_CLIENT::getScholarshipsForHomePageS: EXIT SUCCESS");
			return $this->cacheLib->get($key);
		}
	}

    function getCitiesWithCollege($appId,$countryId) {
        $this->initListing();
        $key = md5('getCitiesWithCollege'.$appID.$countryId);
        error_log_shiksha("key for cache is : ".$key);
        if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
            $this->CI_Instance->xmlrpc->method('sGetCitiesWithCollege');
            $request = array(array($appId,'int'),array($countryId,'int'));
            $this->CI_Instance->xmlrpc->request($request);
            if ( ! $this->CI_Instance->xmlrpc->send_request()){
                return ($this->CI_Instance->xmlrpc->display_error());
            }else{
                $response = json_decode($this->CI_Instance->xmlrpc->display_response(),true);
                $this->cacheLib->store($key,$response,86400);
                return $response;
            }
        }
        else
        {
            error_log_shiksha("DEBUG: LISTING CLIENT::getCitiesWithCollege: EXIT SUCCESS Reading from cache");
            return $this->cacheLib->get($key);
        }
    }

    function isSaved($AppId,$userId,$keyword,$type="",$location="")
    {
        $this->initSearch();
        $this->CI->xmlrpc->method('isSaved');
        $request = array (
                array($AppId, 'int'),
                array($userId, 'int'),
                array($keyword, 'string'),
                array($type,'string'),
                array($location, 'string')
		);
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request())
        {
            return $this->CI->xmlrpc->display_error();
        }
        else
        {
            return $this->CI->xmlrpc->display_response();
        }
    }

    function listingSearchCMS($AppId,$keyword,$location,$start,$rows,$type,$sponseredOnTop,$featuredOnTop,$searchType='',$userId='')
    {
        $this->initSearch();
        $this->CI->xmlrpc->method('searchListingKeywordCMS');
        $request = array (
                array($AppId, 'int'),
                array($keyword, 'string'),
                array($location,'string'),
                array($start, 'int'),
                array($rows, 'int'),
                array($type,'string'),
		array($sponseredOnTop,'int'),
		array($featuredOnTop,'int'),
		array($searchType,'string'),
		array($userId,'string')
                );
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request())
        {
            return $this->CI->xmlrpc->display_error();
        }
        else
        {
            return $this->CI->xmlrpc->display_response();
        }
    }
    function updateSponsorListingByKeyword($appID,$keyword,$location,$requestArray,$userId)
    {
        error_log_shiksha("ENterered client");
        $this->initSearch();
        $this->CI->xmlrpc->method('updateSponsorListingByKeyword');
        $request = array(
                array($appID,'int'),
                array($keyword,'string'),
                array($location,'string'),
                array($requestArray,'struct'),
                array($userId,'int')
                );
        $this->CI->xmlrpc->request($request);

        if ( ! $this->CI->xmlrpc->send_request())
        {
            return ($this->CI->xmlrpc->display_error());
        }
        else
        {
            return ($this->CI->xmlrpc->display_response());
        }
    }

    function checkValidKeywordForListing($appID,$listingId,$listingType,$keywordArray)
    {
        error_log_shiksha("Entered client checkValidKeywordForListing");
        $this->initSearch();
        $this->CI->xmlrpc->method('checkValidKeywordForListing');
        $request = array(
                array($appID,'int'),
                array($listingId,'string'),
                array($listingType,'string'),
                array($keywordArray,'struct')
                );
        $this->CI->xmlrpc->request($request);

        if ( ! $this->CI->xmlrpc->send_request())
        {
            return ($this->CI->xmlrpc->display_error());
        }
        else
        {
            return ($this->CI->xmlrpc->display_response());
        }
    }


    function addSponsorListingByKeyword($appID,$keyword,$location,$listingId,$type,$userId,$searchType='',$sponserType='sponsor')
    {
        error_log_shiksha("ENterered client");
        $this->initClientSearch();
        $this->CI->xmlrpc->method('addSponsorListingByKeyword');
        $request = array(
                array($appID,'int'),
                array($keyword,'string'),
                array($location,'string'),
                array($listingId,'int'),
		array($type,'string'),
                array($userId,'int'),
		array($searchType,'string'),
		array($sponserType,'string')
                );
        $this->CI->xmlrpc->request($request);

        if ( ! $this->CI->xmlrpc->send_request())
        {
            return ($this->CI->xmlrpc->display_error());
        }
        else
        {
            return ($this->CI->xmlrpc->display_response());
        }
    }
    function deleteSponsorListingByKeyword($appID,$keyword,$location,$listingId,$type,$userId,$searchType='',$sponserType='sponsor')
    {
        error_log_shiksha("ENterered client");
        $this->initClientSearch();
        $this->CI->xmlrpc->method('deleteSponsorListingByKeyword');
        $request = array(
                array($appID,'int'),
                array($keyword,'string'),
                array($location,'string'),
                array($listingId,'int'),
		array($type,'string'),
                array($userId,'int'),
		array($searchType,'string'),
		array($sponserType,'string')
                );
        $this->CI->xmlrpc->request($request);

        if ( ! $this->CI->xmlrpc->send_request())
        {
            return ($this->CI->xmlrpc->display_error());
        }
        else
        {
            return ($this->CI->xmlrpc->display_response());
        }
    }
    function getSponsorListingStatusByKeyword($appID,$keyword,$location,$listingId,$type,$userId,$searchType='',$sponserType='sponsor')
    {
        error_log_shiksha("ENterered client");
        $this->initSearch();
        $this->CI->xmlrpc->method('getSponsorListingStatusByKeyword');
        $request = array(
                array($appID,'int'),
                array($keyword,'string'),
                array($location,'string'),
                array($listingId,'int'),
		array($type,'string'),
                array($userId,'int'),
		array($searchType,'string'),
		array($sponserType,'string')
                );
        $this->CI->xmlrpc->request($request);

        if ( ! $this->CI->xmlrpc->send_request())
        {
            return ($this->CI->xmlrpc->display_error());
        }
        else
        {
            return ($this->CI->xmlrpc->display_response());
        }
    }

    function getCountriesWithCollegeInCategory($appId,$categoryId,$examprep="false")
    {
       $this->initListing();
       $this->CI_Instance->xmlrpc->method('sGetCountriesWithCollegeInCategory');
       $request = array(array($appId,'int'),array($categoryId,'int'),array($examprep,'string'));
       $this->CI_Instance->xmlrpc->request($request);

       if ( ! $this->CI_Instance->xmlrpc->send_request()){
	  return ($this->CI_Instance->xmlrpc->display_error());
       }else{
	  return ($this->CI_Instance->xmlrpc->display_response());
       }
    }

    function getCitiesWithCollegeInCategory($appId,$categoryId,$countryId=1,$examprep="false")
    {
       $this->initListing();
       $this->CI_Instance->xmlrpc->method('sGetCitiesWithCollegeInCategory');
       $request = array(array($appId,'int'),array($categoryId,'int'),array($countryId,'string'),array($examprep,'string'));
       $this->CI_Instance->xmlrpc->request($request);

       if ( ! $this->CI_Instance->xmlrpc->send_request()){
	  return ($this->CI_Instance->xmlrpc->display_error());
       }else{
	  return ($this->CI_Instance->xmlrpc->display_response());
       }
    }


    function getFeaturedImageUrls($AppId,$keyword,$location,$countryId,$categoryId,$type,$searchType)
    {
        $this->initClientSearch();
        $this->CI->xmlrpc->method('getFeaturedImageUrls');
        $request = array (
                array($AppId, 'int'),
                array($keyword, 'string'),
                array($location,'string'),
                array($countryId, 'string'),
                array($categoryId, 'string'),
                array($type,'string'),
                array($searchType, 'string')
                );
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request())
        {
            return $this->CI->xmlrpc->display_error();
        }
        else
        {
            return $this->CI->xmlrpc->display_response();
        }
    }

    function getSearchSnippetCount($AppId,$type,$typeId)
    {
        $this->initSearch();
        $this->CI->xmlrpc->method('getSearchSnippetCount');
        $request = array (
                array($AppId, 'int'),
                array($type,'string'),
                array($typeId, 'string')
                );
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request())
        {
            return $this->CI->xmlrpc->display_error();
        }
        else
        {
            return $this->CI->xmlrpc->display_response();
        }
    }

    function updateSearchSnippetCount($AppId,$idTypeArray)
    {
        return; /*
        $this->initClientSearch();
        $this->CI->xmlrpc->method('updateSearchSnippetCount');
        $request = array (
                array($AppId, 'int'),
                array($idTypeArray, 'struct')
                );
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request())
        {
            return $this->CI->xmlrpc->display_error();
        }
        else
        {
            return $this->CI->xmlrpc->display_response();
        }
        */
    }


    function getFeaturedPanelLogo($appId,$request)
    {
		$this->initListing();
		$this->CI_Instance->xmlrpc->method('getFeaturedPanelLogo');
        $request = array (
                array($AppId, 'int'),
                array($request, 'struct')
                );
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request())
        {
            return $this->CI_Instance->xmlrpc->display_error();
        }
        else
        {
            return $this->CI_Instance->xmlrpc->display_response();
        }
    }

    function getJoinGroupInfo($AppId=1,$instituteId)
    {
        $this->initListing();
        $this->CI_Instance->xmlrpc->method('sgetJoinGroupInfo');
        $request = array (
                array($AppId, 'int'),
                array($instituteId,'string')
                );
        error_log_shiksha("getjoingroup");
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request())
        {
            return $this->CI_Instance->xmlrpc->display_error();
        }
        else
        {
            return $this->CI_Instance->xmlrpc->display_response();
        }
    }

    function updateApcForSearch($AppId=1)
    {
	$this->initListing('search');
   	$this->CI_Instance->xmlrpc->method('updateApcForSearch');
        $request = array (
                array($AppId, 'int'),
                );
        error_log_shiksha("getjoingroup");
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request())
        {
            return $this->CI_Instance->xmlrpc->display_error();
        }
        else
        {
            return $this->CI_Instance->xmlrpc->display_response();
        }
    }

    function reportChanges($appID,$requestArray){ //requestArray is actually _POST array
        $this->init();
        $this->CI_Instance->xmlrpc->method('reportChanges');
        //                $request = array(array($appID,'int'),array(array("listing_title" => "TESTtitle","contact_email" => "temp@email.com"),'struct'));
        $request = array(array($appID,'int'),array($requestArray,'struct'));
        //		error_log_shiksha("CLIENT REQUEST ARRAY".print_r($request,TRUE));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }

    function getListingsByClient($appID,$clientId,$start="0",$num="20") {
        $this->initListing();
        $this->CI_Instance->xmlrpc->method('getListingsByClient');
        $request = array(array($appID,'int'),array($clientId,'string'),array($start,'string'),array($num,'string'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            $response = $this->CI_Instance->xmlrpc->display_response();
            return $response;
        }
    }

    function getListingsByClientForType($appID,$clientId, $listingType, $start="0",$num="20",$tabStatus) {
        $this->initListing();
        $this->CI_Instance->xmlrpc->method('getListingsByClientForType');
        $request = array(array($appID,'int'),array($clientId,'string'),array($listingType,'array'),array($start,'string'),array($num,'string'),array($tabStatus,'string'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            $response = $this->CI_Instance->xmlrpc->display_response();
            return $response;
        }
    }

    function consumeProduct($appId=1, $subscriptionId,$productType ,$productId, $features = array()){ //requestArray is actually _POST array
        $this->init();
        $this->CI_Instance->xmlrpc->method('consumeProduct');
        $request = array(array($appId,'int'),array($subscriptionId,'string'),array($productType,'string'),array($productId,'string'),array($features,'struct'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }

    /*
     *       Method to cancel a Listing used by SUMS
     */
    function cancelListing($appID='1',$type_id,$listing_type){
        $this->init();
        $this->CI_Instance->xmlrpc->method('cancelListing');
        $request = array(array($appID,'int'),array($type_id,'int'),array($listing_type,'string'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }

    /*
     *       Method to cancel a Subscription used by SUMS
     */
    function cancelSubscription($appID='1',$subscriptionId, $customArr = array()){
        $this->init();
        $this->CI_Instance->xmlrpc->method('cancelSubscription');
        $request = array(array($appID,'int'),array($subscriptionId,'string'),array($customArr,'struct'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }

    function addSponsorListing($appID,$keyword,$listingId,$type,$startDate,$endDate,$subscriptionId,$sponserType,$location)
    {
        return; /*
	    error_log_shiksha("ENterered client");
	    $this->initClientSearch();
	    $this->CI->xmlrpc->method('addSponsorListing');
	    $request = array(
			    array($appID,'int'),
			    array($keyword,'string'),
			    array($listingId,'int'),
			    array($type,'string'),
			    array($startDate,'string'),
			    array($endDate,'string'),
			    array($subscriptionId,'string'),
			    array($sponserType,'string'),
			    array($location,'string')
			    );
	    $this->CI->xmlrpc->request($request);

	    if ( ! $this->CI->xmlrpc->send_request())
	    {
		    return ($this->CI->xmlrpc->display_error());
	    }
	    else
	    {
		    return ($this->CI->xmlrpc->display_response());
	    }
        */
    }

    function cancelKeywordSubscription($appID='1',$subscriptionId, $customArr = array()){
        $this->initClientSearch();
        $this->CI_Instance->xmlrpc->method('cancelSubscription');
        $request = array(array($appID,'int'),array($subscriptionId,'string'),array($customArr,'struct'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }

    function extendExpiryDate($appId,$listingTypeId,$listingType,$extendedDate){
        $this->init();
        $this->CI_Instance->xmlrpc->method('sextendExpiryDate');
        $request = array ($appId,$listingTypeId,$listingType,$extendedDate);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }
function getDurationMinMax($AppId,$keyword,$location,$countryId,$categoryId,$start,$rows,$type,$searchType,$cityId,$relaxFlag=0,$cType,$courseLevel)
    {
        $this->initSearch();
        $this->CI->xmlrpc->method('getDurationMinMax');
        $request = array (
                array($AppId, 'int'),
                array($keyword, 'string'),
                array($location,'string'),
                array($countryId, 'string'),
                array($categoryId, 'string'),
                array($start, 'int'),
                array($rows, 'int'),
                array($type,'string'),
                array($searchType, 'string'),
                array($cityId, 'string'),
                array($relaxFlag, 'int'),
                array($cType, 'string'),
                array($courseLevel, 'string')
                );
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request())
        {
            return $this->CI->xmlrpc->display_error();
        }
        else
        {
            return $this->CI->xmlrpc->display_response();
        }
    }
function getCoursesForExam($appID=1,$examId,$instituteType,$start = 0,$count = 2,$relaxFlag = 1,$countryId = 1, $cityId = 1) {
    $this->initListing();
    $key = md5('getCoursesForExam'.$appID.$examId.$instituteType.$start.$count.$relaxFlag.$countryId.$cityId);
    if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
        $this->CI_Instance->xmlrpc->method('getCoursesForExam');
        $request = array(array($appID,'int'),array($examId,'int'),array($instituteType,'string'),array($start,'int'),array($count,'int'),array($relaxFlag,'int'),array($countryId,'int'),array($cityId,'string'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }
        else{
            $response = $this->CI_Instance->xmlrpc->display_response();
            $this->cacheLib->store($key,$response,1800);
            return $response;
        }
    }else{
        return $this->cacheLib->get($key);
    }
}




function getInstitutesForExam($appID=1,$examId,$instituteType,$start = 0,$count = 2,$relaxFlag = 1,$countryId = 1, $cityId = 1, $pageKey = 0) {
    $this->initListing();
    $key = md5('getInstitutesForExam'.$appID.$examId.$instituteType.$start.$count.$relaxFlag.$countryId.$cityId.$pageKey);
    if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
        $this->CI_Instance->xmlrpc->method('getInstitutesForExam');
        $request = array(array($appID,'int'),array($examId,'int'),array($instituteType,'string'),array($start,'int'),array($count,'int'),array($relaxFlag,'int'),array($countryId,'int'),array($cityId,'string'),array($pageKey,'string'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }
        else{
            $response = $this->CI_Instance->xmlrpc->display_response();
            $this->cacheLib->store($key,$response,1800);
            return $response;
        }
    }else{
        return $this->cacheLib->get($key);
    }
}

function getListingsCount($appID,$criteria=array()) {
        $this->initListing();
        error_log_shiksha("APP ID getListingsCount =>".print_r($criteria,true));
        $this->CI_Instance->xmlrpc->method('getListingsCount');
        $request = array(array($appID,'int'),array(serialize($criteria),'string'));
        error_log_shiksha("CLIENT REQUEST getListingsCount ARRAY".print_r($request,TRUE));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }

    function changeListingDates($appID,$request){
        $this->init();
        $this->CI_Instance->xmlrpc->method('schangeListingDates');
        $request = array(array($appID,'int'),array($request,'struct'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }

    function updateSponsorListingDetails($id,$request){
        return; /*
        $this->initClientSearch();
        $this->CI->xmlrpc->method('updateSponsorListingDetails');
        $request = array(array($id,'int'),array($request,'struct'));
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request()){
            return ($this->CI->xmlrpc->display_error());
        }else{
            return ($this->CI->xmlrpc->display_response());
        }
        */
    }

function editCategFormOpen($appID,$instituteId) {
        $this->init();
        $this->CI_Instance->xmlrpc->method('seditCategFormOpen');
        $request = array(array($appID,'int'),array($instituteId,'int'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }
function getInstituteDetailForIndex($appID,$type_id,$listing_type) {
        $this->init();
        error_log_shiksha("APP ID getCityList =>$appID :: $type_id,$listing_type");
        $this->CI_Instance->xmlrpc->method('sgetInstituteDetailForIndex');
        $request = array(array($appID,'int'),array($type_id,'int'),array($listing_type,'string'));
        error_log_shiksha("CLIENT REQUEST getCityList ARRAY".print_r($request,TRUE));
        $this->CI_Instance->xmlrpc->request($request);

        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }

    }

function getCategoryIdsForEachCourse($course_id_array){
     $this->initListing();
        $this->CI_Instance->xmlrpc->method('getCategoryIdsForEachCourse');
        $request = array(array($course_id_array,'struct'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
}

function getWikiFields($appID,$listingType) {
        $this->initListing();
        $this->CI_Instance->xmlrpc->method('getWikiFields');
        $request = array(array($appID,'int'),array($listingType,'string'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }

    function getWikiForListing($appId,$listingId,$listingType){
        $this->initListing();
        $this->CI_Instance->xmlrpc->method('getWikiForListing');

        $request = array(array($appId,'int'),array($listingId,'int'),array($listingType,'string'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }

    }

    function getUrlForHeaderImages($institute_id){
        $this->initListing();
        $this->CI_Instance->xmlrpc->method('getUrlForHeaderImages');

        //$request = array(array($institute_id,'int'));
        $request = array($institute_id);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{

            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }

    function getUrlForCompaniesLogo($course_id,$institute_id){
         $this->initListing();
        $this->CI_Instance->xmlrpc->method('getUrlForCompaniesLogo');

        //$request = array(array($institute_id,'int'));
        $request = array($course_id,$institute_id);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }

    }


function editInstitute($appID,$instituteId, $requestArray){
    $this->init();
    $this->CI_Instance->xmlrpc->method('editInstitute');
    $request = array(array($appID,'int'),array($instituteId,'int'),array(base64_encode(serialize($requestArray)),'string'));
    $this->CI_Instance->xmlrpc->request($request);
    if ( ! $this->CI_Instance->xmlrpc->send_request()){
        return ($this->CI_Instance->xmlrpc->display_error());
    }else{
        return ($this->CI_Instance->xmlrpc->display_response());
    }
}

function editCourse($appID,$courseId, $requestArray){
    $this->init();
    $this->CI_Instance->xmlrpc->method('editCourse');
    $request = array(array($appID,'int'),array($courseId,'int'),array(base64_encode(serialize($requestArray)),'string'));
    $this->CI_Instance->xmlrpc->request($request);
    if ( ! $this->CI_Instance->xmlrpc->send_request()){
        return ($this->CI_Instance->xmlrpc->display_error());
    }else{
        return ($this->CI_Instance->xmlrpc->display_response());
    }
}

function newAddInstitute($appID,$requestArray){
    $this->init();
    $this->CI_Instance->xmlrpc->method('newAddInstitute');
    $request = array(array($appID,'int'),array(base64_encode(serialize($requestArray)),'string'));
    $this->CI_Instance->xmlrpc->request($request);
    if ( ! $this->CI_Instance->xmlrpc->send_request()){
        return ($this->CI_Instance->xmlrpc->display_error());
    }else{
        $this->CI_Instance->load->library('cacheLib');
        $this->cacheLib = new cacheLib();
        //$this->cacheLib->clearCache('cmsFedCourse');
        return ($this->CI_Instance->xmlrpc->display_response());
    }
}

function getDraftAndLiveInstiContactIds($appId,$request){
    $this->initListing();
    $this->CI_Instance->xmlrpc->method('getDraftAndLiveInstiContactIds');
    $request = array(array($id,'int'),array($request,'struct'));
    $this->CI_Instance->xmlrpc->request($request);
    if ( ! $this->CI_Instance->xmlrpc->send_request()){
        return ($this->CI_Instance->xmlrpc->display_error());
    }else{
        return ($this->CI_Instance->xmlrpc->display_response());
    }
}

function getModerationList($appId,$request){
    $this->initListing();
    $this->CI_Instance->xmlrpc->method('getModerationList');
    $request = array(array($id,'int'),array($request,'struct'));
    $this->CI_Instance->xmlrpc->request($request);
    if ( ! $this->CI_Instance->xmlrpc->send_request()){
        return ($this->CI_Instance->xmlrpc->display_error());
    }else{
        return ($this->CI_Instance->xmlrpc->display_response());
    }
}

function createMultipleInstiForMultiLocation($id=1,$database){
    $this->init();
    $this->CI_Instance->xmlrpc->method('createMultipleInstiForMultiLocation');
    $request = array(array($id,'int'),array($database,'string'));

    $this->CI_Instance->xmlrpc->request($request);
    if ( ! $this->CI_Instance->xmlrpc->send_request()){
        return ($this->CI_Instance->xmlrpc->display_error());
    }else{
        return ($this->CI_Instance->xmlrpc->display_response());
    }
}

function createCoursesForMultipleInstis($id=1, $database){
    $this->init();
    $this->CI_Instance->xmlrpc->method('createCoursesForMultipleInstis');
    $request = array(array($id,'int'),array($database,'string'));
    $this->CI_Instance->xmlrpc->request($request);
    if ( ! $this->CI_Instance->xmlrpc->send_request()){
        return ($this->CI_Instance->xmlrpc->display_error());
    }else{
        return ($this->CI_Instance->xmlrpc->display_response());
    }
}

function getListingUrl($id, $type){
    $this->initSearch();
    $this->CI->xmlrpc->method('getUrlDetail');
    $request = array(array($id,'int'),array($type,'string'));
    $this->CI->xmlrpc->request($request);
    if ( ! $this->CI->xmlrpc->send_request()){
        error_log("Hellos 12111 ".$this->CI->xmlrpc->display_error());
        return ($this->CI->xmlrpc->display_error());
    }else{
        error_log("Hellos 12121");
        return ($this->CI->xmlrpc->display_response());
    }
}

function updateCmsBanners($appId, $bannerDetails, $id){
    $this->init();
    $this->CI_Instance->xmlrpc->method('sUpdateCmsBanners');
    $request = array(array($appId,'int'),array($bannerDetails,'string'),array($id,'int'));
    $this->CI_Instance->xmlrpc->request($request);
    if ( ! $this->CI_Instance->xmlrpc->send_request()){
        return ($this->CI_Instance->xmlrpc->display_error());
    }else{
        return ($this->CI_Instance->xmlrpc->display_response());
    }
}

function getDataForCountryPage($appId, $countryId){
    $this->init();
    $this->CI_Instance->xmlrpc->method('sGetDataForCountryPage');
    $request = array(array($appId,'int'),array($countryId,'string'));
    $this->CI_Instance->xmlrpc->request($request);
    if ( ! $this->CI_Instance->xmlrpc->send_request()){
        return ($this->CI_Instance->xmlrpc->display_error());
    }else{
        $data = ($this->CI_Instance->xmlrpc->display_response());
        return $data;
    }
}

function uploadCountrySelectionToCMS($appId, $bannerDetails){
    $this->init();
    $this->CI_Instance->xmlrpc->method('sUploadCmsCountries');
    $request = array(array($appId,'int'),array($bannerDetails,'string'));
    $this->CI_Instance->xmlrpc->request($request);
    if ( ! $this->CI_Instance->xmlrpc->send_request()){
        return ($this->CI_Instance->xmlrpc->display_error());
    }else{
        return ($this->CI_Instance->xmlrpc->display_response());
    }
}
function uploadBannerToCMS($appId, $bannerDetails){
    $this->init();
    $this->CI_Instance->xmlrpc->method('sUploadCmsBanners');
    $request = array(array($appId,'int'),array($bannerDetails,'string'));
    $this->CI_Instance->xmlrpc->request($request);
    if ( ! $this->CI_Instance->xmlrpc->send_request()){
        return ($this->CI_Instance->xmlrpc->display_error());
    }else{
        return ($this->CI_Instance->xmlrpc->display_response());
    }
}

function getListingAutoComplete($appId, $type ,$phrase , $start=0, $rows=10){
    $this->initListing();
    $this->CI_Instance->xmlrpc->method('sgetListingAutoComplete');
    $request = array(array($appId,'int'),array($type,'string'),array($phrase,'string'), array($start,'int'), array($rows,'int'));
    $this->CI_Instance->xmlrpc->request($request);
    if ( ! $this->CI_Instance->xmlrpc->send_request()){
        return ($this->CI_Instance->xmlrpc->display_error());
    }else{
        return ($this->CI_Instance->xmlrpc->display_response());
    }
}

function addEntityForPriorityLeads($appId, $listingId, $endDate) {
    $this->init();
    $this->CI_Instance->xmlrpc->method('sAddEntityForPriorityLeads');
    $request = array($appId, $listingId, $endDate);
    $this->CI_Instance->xmlrpc->request($request);
    if ( ! $this->CI_Instance->xmlrpc->send_request()){
        error_log($this->CI_Instance->xmlrpc->display_error());
        return ($this->CI_Instance->xmlrpc->display_error());
    }else{
        return ($this->CI_Instance->xmlrpc->display_response());
    }
}

function getEntitiesForPriorityLeads($appId){
    $this->initListing();
    $this->CI_Instance->xmlrpc->method('sGetEntitiesForPriorityLeads');
    $request = array($appId);
    $this->CI_Instance->xmlrpc->request($request);
    if ( ! $this->CI_Instance->xmlrpc->send_request()){
        error_log($this->CI_Instance->xmlrpc->display_error());
        return ($this->CI_Instance->xmlrpc->display_error());
    }else{
        return ($this->CI_Instance->xmlrpc->display_response());
    }
}

function deleteEntityFromPriorityLeads($appId, $listingId){
    $this->init();
    $this->CI_Instance->xmlrpc->method('sDeleteEntityFromPriorityLeads');
    $request = array($appId, $listingId);
    $this->CI_Instance->xmlrpc->request($request);
    if ( ! $this->CI_Instance->xmlrpc->send_request()){
        error_log($this->CI_Instance->xmlrpc->display_error());
        return ($this->CI_Instance->xmlrpc->display_error());
    }else{
        return ($this->CI_Instance->xmlrpc->display_response());
    }
}

function getOptimumCategory($appID,$InstituteId,$pageName='institute') {
        $this->initListing();
        $this->CI_Instance->xmlrpc->method('getOptimumCategory');
        $request = array(array($appID,'int'),array($InstituteId,'int'),array($pageName,'string'));
        $this->CI_Instance->xmlrpc->request($request);

        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
}

function makeCopyListingMapEntry($appId,$paramArray){
	$this->init();
	$this->CI_Instance->xmlrpc->method('makeCopyListingMapEntry');
	$request = array(array($appID,'int'),array(base64_encode(gzcompress(json_encode($paramArray))),'string'));
	$this->CI_Instance->xmlrpc->request($request);
	if ( ! $this->CI_Instance->xmlrpc->send_request()){
		return ($this->CI_Instance->xmlrpc->display_error());
	}else{
		return ($this->CI_Instance->xmlrpc->display_response());
	}
}

function getInstituteDataDetails($appID,$InstituteIds) {
        $this->initlisting();
        $this->CI_Instance->xmlrpc->method('sgetInstituteDataDetails');
        $request = array($appID, $InstituteIds);
        $this->CI_Instance->xmlrpc->request($request);

        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }

function getInstitutesForMultipleCourses($appID,$courseIds) {
        $this->initListing();
        $this->CI_Instance->xmlrpc->method('sgetInstitutesForMultipleCourses');
        $request = array($appID, $courseIds);
        $this->CI_Instance->xmlrpc->request($request);

        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }

function getDataForGenerationOfSeoUrl($AppId,$start=0,$rows=15)
    {
        return; /*
        $this->initClientSearch();
        $this->CI->xmlrpc->method('getDataForGenerationOfSeoUrl');
        $request = array (
                array($AppId, 'int'),
                array($start, 'int'),
                array($rows, 'int')
                );
        $this->CI->xmlrpc->request($request);
        if ( ! $this->CI->xmlrpc->send_request())
        {
            return $this->CI->xmlrpc->display_error();
        }
        else
        {
			$response = $this->CI->xmlrpc->display_response();
			$response = json_decode(gzuncompress(base64_decode($response[0])),true);
            return $response;
        }
        */
    }

function getShoshkeleDetails($AppId,$clientId,$sort)
{
    $this->init();
    $this->CI_Instance->xmlrpc->method('sgetShoshkeleDetails');
    $request = array (
            array($AppId, 'int'),
            array($clientId, 'int'),
            array($sort, 'string')
            );
    $this->CI_Instance->xmlrpc->request($request);
    if(!$this->CI_Instance->xmlrpc->send_request())
    {
        return $this->CI_Instance->xmlrpc->display_error();
    }
    else
    {
        $response = $this->CI_Instance->xmlrpc->display_response();
        //$response = json_decode($response,true);
        return $response;
    }
}
function insertbannerdetails($appId, $clientId,$bannerurl,$bannername){
    $this->init();
    $this->CI_Instance->xmlrpc->method('sinsertbannerdetails');
    $request = array($appId,$clientId,$bannerurl,$bannername);
    $this->CI_Instance->xmlrpc->request($request);
    if ( ! $this->CI_Instance->xmlrpc->send_request()){
        return ($this->CI_Instance->xmlrpc->display_error());
    }else{
        return ($this->CI_Instance->xmlrpc->display_response());
    }
}

function cmsgetlistingdetails($appId, $listingid)
{
    $this->initListing();
    $this->CI_Instance->xmlrpc->method('scmsgetlistingdetails');
    $request = array($appId,$listingid);
    $this->CI_Instance->xmlrpc->request($request);
    if ( ! $this->CI_Instance->xmlrpc->send_request()){
        return ($this->CI_Instance->xmlrpc->display_error());
    }else{
        $response = json_decode($this->CI_Instance->xmlrpc->display_response(),true);
        return $response;
    }
}

function cmsaddstickylisting($appId, $arr)
{
    $this->init();
    $this->CI_Instance->xmlrpc->method('scmsaddstickylisting');
    $request = array(array($appId,'int'),array($arr,'struct'));
    $this->CI_Instance->xmlrpc->request($request);
    if ( ! $this->CI_Instance->xmlrpc->send_request()){
        error_log("Hellos 12111 ".$this->CI_Instance->xmlrpc->display_error());
        return ($this->CI_Instance->xmlrpc->display_error());
    }else{
        error_log("Hellos 12121");
        return ($this->CI_Instance->xmlrpc->display_response());
    }
}

function updatebannerdetails($appId, $bannerId,$bannerurl,$bannername,$clientId,$keyword){
    $this->init();
    $this->CI_Instance->xmlrpc->method('supdatebannerdetails');
    $request = array($appId,$bannerId,$bannerurl,$bannername,$clientId,$keyword);
    $this->CI_Instance->xmlrpc->request($request);
    if ( ! $this->CI_Instance->xmlrpc->send_request()){
        error_log("Hellos 12111 ".$this->CI_Instance->xmlrpc->display_error());
        return ($this->CI_Instance->xmlrpc->display_error());
    }else{
        error_log("Hellos 12121");
        return ($this->CI_Instance->xmlrpc->display_response());
    }
}

function selectnduseshoshkele($appId, $arr)
{
    $this->init();
    $this->CI_Instance->xmlrpc->method('sselectnduseshoshkele');
    $request = array(array($appId,'int'),array($arr,'struct'));
    $this->CI_Instance->xmlrpc->request($request);
    if ( ! $this->CI_Instance->xmlrpc->send_request()){
        error_log("Hellos 12111 ".$this->CI_Instance->xmlrpc->display_error());
        return ($this->CI_Instance->xmlrpc->display_error());
    }else{
        error_log("Hellos 12121");
        return ($this->CI_Instance->xmlrpc->display_response());
    }
}

function getListingSponsorDetails($appId, $clientId,$sort)
{
    $this->initListing();
    $this->CI_Instance->xmlrpc->method('sgetListingSponsorDetails');
    $request = array($appId,$clientId,$sort);
    $this->CI_Instance->xmlrpc->request($request);
    if ( ! $this->CI_Instance->xmlrpc->send_request()){
        return ($this->CI_Instance->xmlrpc->display_error());
    }else{
        $response = $this->CI_Instance->xmlrpc->display_response();
        $response = json_decode($response,true);
        return $response;
    }
}

function getListingndBannersForCoupling($appId,$clientId,$countryId,$cityId,$stateId,$categoryId,$subcategoryId,$catType,$courseLevel)
{
    $this->initListing();
    $this->CI_Instance->xmlrpc->method('sgetListingndBannersForCoupling');
    $request = array($appId,$clientId,$countryId,$cityId,$stateId,$categoryId,$subcategoryId,$catType,$courseLevel);
    $this->CI_Instance->xmlrpc->request($request);
    if ( ! $this->CI_Instance->xmlrpc->send_request()){
        return ($this->CI_Instance->xmlrpc->display_error());
    }else{
        $response = $this->CI_Instance->xmlrpc->display_response();
        $response = json_decode($response,true);
        return $response;
    }
}

function cmsremoveshoshkele($appId,$bannerid,$tablename)
{
    $this->init();
    $this->CI_Instance->xmlrpc->method('scmsremoveshoshkele');
    error_log('dgdfg');
    $request = array($appId,$bannerid,$tablename);
    $this->CI_Instance->xmlrpc->request($request);
    if ( ! $this->CI_Instance->xmlrpc->send_request()){
        error_log("Hellos 12111 ".$this->CI_Instance->xmlrpc->display_error());
        return ($this->CI_Instance->xmlrpc->display_error());
    }else{
        error_log("Hellos 12121");
        $response = $this->CI_Instance->xmlrpc->display_response();
        $response = json_decode($response,true);
        return $response;
    }
}

function changeCouplingStatus($appId,$listingsubsid,$bannerlinkid,$status)
{
    $this->init();
    $this->CI_Instance->xmlrpc->method('schangeCouplingStatus');
    error_log('dgdfg');
    $request = array($appId,$listingsubsid,$bannerlinkid,$status);
    $this->CI_Instance->xmlrpc->request($request);
    if ( ! $this->CI_Instance->xmlrpc->send_request()){
        error_log("Hellos 12111 ".$this->CI_Instance->xmlrpc->display_error());
        return ($this->CI_Instance->xmlrpc->display_error());
    }else{
        error_log("Hellos 12121");
        $response = $this->CI_Instance->xmlrpc->display_response();
        $response = json_decode($response,true);
        return $response;
    }
}

    /*
     * Gets a list of institute id's and their course id's along with them.They are
     * also separated into different types like 'main institutes', 'paid minus main institutes' etc.
     */
    public function get_testprep_listings($blog_id, $city_id = -1, $course_type = 'All') {
        $this->initCategoryRevampRead();
        $key = md5("get_testprep_listings".$blog_id.$city_id.$course_type);
        if ($this->cacheLib->get($key) == 'ERROR_READING_CACHE') {
            //$this->CI_Instance->xmlrpc->set_debug(TRUE);
            $this->CI_Instance->xmlrpc->method('get_testprep_listings');
            $request = array($blog_id, $city_id, $course_type);
            $this->CI_Instance->xmlrpc->request($request);

            if (!$this->CI_Instance->xmlrpc->send_request()) {
                return ($this->CI_Instance->xmlrpc->display_error());
            } else {
                $response = $this->rotate_testprep_listings($this->CI_Instance->xmlrpc->display_response(),$blog_id, $city_id, $course_type);
                $this->cacheLib->store($key, $response, 43200);
                return $response;
            }
        } else {
            return $this->rotate_testprep_listings($this->cacheLib->get($key),$blog_id, $city_id, $course_type);
//            $listings = $this->cacheLib->get($key);
//            $timer_key = md5("listings_timer_key");
//            if( ! $this->cacheLib->isAvailable($timer_key))
//            {
//                //randomize indexes of circular array
//                $listings['free_listings']->set_random_index();
//                $listings['main_listings']->set_random_index();
//                $listings['paid_minus_main_listings']->set_random_index();
//                $listings['cat_sponser_listings']->set_random_index();
//                $listings['banners']->set_random_index();
//                //restore timer key
//                $this->cacheLib->store($timer_key, "some_timer_value");
//                return $listings;
//            }
//            return $listings;
        }
    }

    /*
     * This functions rotates different type of listings starting from a
     * random index and treating the array as a circular array
     */
    private function rotate_testprep_listings($response_array,$blog_id, $city_id, $course_type) {
        $this->CI_Instance->load->library('util');
        //$this->CI_Instance->load->library('circular_array');
        $util = new Util();
        // rotate free listings
        $formatted_array = array();
        $testprep_index_data_key = md5("testprep_index_data_key".$blog_id.$city_id.$course_type);

        if ($this->cacheLib->get($testprep_index_data_key) == 'ERROR_READING_CACHE') {
            //restore the timer
            $this->cacheLib->store($testprep_index_data_key, "somekeytotracktime", 1800);
            //rotate the listings based on the start indexes
            $formatted_array['free_listings'] = $response_array['free_listings'];

            $paid_listings = $util->rotate($response_array['paid_listings']);
            $formatted_array['paid_listings'] = $paid_listings;

            $paid_minus_main_listings = $util->rotate($response_array['paid_minus_main_listings']);
            $formatted_array['paid_minus_main_listings'] = $paid_minus_main_listings;

            $main_listings = $util->rotate($response_array['main_listings']);
            $formatted_array['main_listings'] = $main_listings;

            $num_of_banners = count($response_array['cat_sponser_banners']);
            $banner_start_index = $this->get_banner_start_index($blog_id, $city_id, $course_type);
            $banner_start_index = $num_of_banners == 0 ? 0 : ($banner_start_index + 1)%$num_of_banners;
            $this->set_banner_start_index($blog_id, $city_id, $course_type, $banner_start_index);
            $cat_sponser_banners = $util->rotate($response_array['cat_sponser_banners'], $banner_start_index);

            $formatted_array['cat_sponser_banners'] = $cat_sponser_banners;


            $cat_sponser_listings = $response_array['cat_sponser_listings'];
            $num_cat_spon_listings = count($cat_sponser_listings);


            //find starting index for listing
            $start_index = -1;
            for($i = 0;$i < $num_of_banners; $i++){
                if($cat_sponser_banners[$i]['institute_id'] == NULL) continue;
                $found = false;
                for($j=0;$j<$num_cat_spon_listings;$j++){
                    if($cat_sponser_banners[$i]['institute_id'] == $cat_sponser_listings[$j]['institute_id']){
                        $start_index = $j;
                        $found = true;
                        break;
                    }
                }
                if($found) break;
                //$i = ($i + 1) % $num_of_cat_sponsers;
            }
            if($start_index == -1){
                $start_index = rand(0,$num_cat_spon_listings - 1);
            }


            $formatted_array['cat_sponser_listings'] = $util->rotate($cat_sponser_listings, $start_index);
            $this->cacheLib->store($key, $formatted_array, 43200);
            return $formatted_array;
        } else
        {
            return $response_array;
        }

    }


    private function get_banner_start_index($blog_id, $city_id, $course_type){
        $key = md5("get_banner_start_index".$blog_id.$city_id.$course_type);
        if ($this->cacheLib->get($key) == 'ERROR_READING_CACHE'){
            $this->cacheLib->store($key, 0, 43200);
            return 0;
        } else {
            return $this->cacheLib->get($key);
        }
    }

    private function set_banner_start_index($blog_id, $city_id, $course_type, $index){
        $key = md5("get_banner_start_index".$blog_id.$city_id.$course_type);
        $this->cacheLib->store($key, $index, 43200);
    }

    public function get_institute_name($institute_id) {
        $key = md5("get_institute_name" . $institute_id);
        if ($this->cacheLib->get($key) == 'ERROR_READING_CACHE') {
            //$this->CI_Instance->xmlrpc->set_debug(TRUE);
            $this->CI_Instance->xmlrpc->method('get_institute_name');
            $request = array($institute_id);
            $this->CI_Instance->xmlrpc->request($request);
            if (!$this->CI_Instance->xmlrpc->send_request()) {
                return ($this->CI_Instance->xmlrpc->display_error());
            } else {
                $response = ($this->CI_Instance->xmlrpc->display_response());
                $this->cacheLib->store($key, $response, 21600);
                return $response;
            }
        } else {
            return $this->cacheLib->get($key);
        }
    }

    public function get_course_name($course_id) {
        error_log("get course".$course_id."-");
        $key = md5("get_course_name" . $course_id);
        if ($this->cacheLib->get($key) == 'ERROR_READING_CACHE') {
            $this->CI_Instance->xmlrpc->method('get_course_name');
            $request = array($course_id);
            $this->CI_Instance->xmlrpc->request($request);
            if (!$this->CI_Instance->xmlrpc->send_request()) {
                error_log($this->CI_Instance->xmlrpc->display_error());
                return ($this->CI_Instance->xmlrpc->display_error());
            } else {
                $response = ($this->CI_Instance->xmlrpc->display_response());
                $this->cacheLib->store($key, $response, 21600);
                return $response;
            }
        } else {
            return $this->cacheLib->get($key);
        }
    }


    function get_exam_categories($blog_id)
    {
        $this->init();
        $request = array($blog_id);
        //$this->CI_Instance->xmlrpc->set_debug(TRUE);
        $this->CI_Instance->xmlrpc->method('get_exam_categories');
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()) {
            return $this->CI_Instance->xmlrpc->display_error();
        } else {
            return $this->CI_Instance->xmlrpc->display_response();
        }

    }

    function get_online_test_banner($blog_id) {
        $this->init();
        $key = md5("get_online_test_banner" . $blog_id);
        if ($this->cacheLib->get($key) == 'ERROR_READING_CACHE') {
            $request = array($blog_id);
            $this->CI_Instance->xmlrpc->set_debug(0);
            $this->CI_Instance->xmlrpc->method('get_online_test_banner');
            $this->CI_Instance->xmlrpc->request($request);
            if (!$this->CI_Instance->xmlrpc->send_request()) {
                $response = $this->CI_Instance->xmlrpc->display_error();
            } else {
                $response = $this->CI_Instance->xmlrpc->display_response();
                $this->cacheLib->store($key, $response, 1800);
            }
            return $response;
        } else {
            return $this->cacheLib->get($key);
        }
    }

function getCorrectSeoURL($appId, $listingId, $listing_type){
    $this->initListing();
    $this->CI_Instance->xmlrpc->method('getCorrectSeoURL');
    $request = array($appId, $listingId, $listing_type);
    $this->CI_Instance->xmlrpc->request($request);
    if ( ! $this->CI_Instance->xmlrpc->send_request()){
        error_log($this->CI_Instance->xmlrpc->display_error());
        return ($this->CI_Instance->xmlrpc->display_error());
    }else{
        return ($this->CI_Instance->xmlrpc->display_response());
    }
}

    //Added by Ankur for HTML caching in Listing detail page
    function getBasicDataForListing($appId,$type_id,$listing_type)
    {
	$this->initListing();
	$this->CI_Instance->xmlrpc->method('getBasicDataForListing');
	$request = array($appId,$type_id,$listing_type);
	$this->CI_Instance->xmlrpc->request($request);
	if ( ! $this->CI_Instance->xmlrpc->send_request()){
	    return ($this->CI_Instance->xmlrpc->display_error());
	}else{
	    $response = $this->CI_Instance->xmlrpc->display_response();
	    return $response;
	}
    }

    //Added by Ankur for Storing Question data in DB
    function checkListingQuestions($appId,$type_id)
    {
	$this->initListing();
	$this->CI_Instance->xmlrpc->method('checkListingQuestions');
	$request = array($appId,$type_id);
	$this->CI_Instance->xmlrpc->request($request);
	if ( ! $this->CI_Instance->xmlrpc->send_request()){
	    return ($this->CI_Instance->xmlrpc->display_error());
	}else{
	    $response = $this->CI_Instance->xmlrpc->display_response();
	    return $response;
	}
    }

    //Added by Ankur for Storing Question data in DB
    function updateListingQuestions($appId,$type_id,$questionIds,$All)
    {
	$this->init();
	$this->CI_Instance->xmlrpc->method('updateListingQuestions');
	$request = array($appId,$type_id,$questionIds,$All);
	$this->CI_Instance->xmlrpc->request($request);
	if ( ! $this->CI_Instance->xmlrpc->send_request()){
	    return ($this->CI_Instance->xmlrpc->display_error());
	}else{
	    $response = $this->CI_Instance->xmlrpc->display_response();
	    return $response;
	}
    }

		//Added by Ankur for Storing Listing page Cache
function setListingCacheValue($appId,$key,$value)
{
		$this->init();
		$this->CI_Instance->xmlrpc->method('setListingCacheValue');
		$request = array($appId,$key,$value);
		$this->CI_Instance->xmlrpc->request($request);
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
				return ($this->CI_Instance->xmlrpc->display_error());
		} else {
				$response = $this->CI_Instance->xmlrpc->display_response();
				return $response;
		}
}

		//Added by Ankur for getting Listing page Cache
function getListingCacheValue($appId,$key)
{
		$this->initListing();
		$this->CI_Instance->xmlrpc->method('getListingCacheValue');
		$request = array($appId,$key);
		$this->CI_Instance->xmlrpc->request($request);
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
				return ($this->CI_Instance->xmlrpc->display_error());
		} else {
		$response = $this->CI_Instance->xmlrpc->display_response();
				return $response;
		}
}

    /****************************
    Purpose: Function to delete the Listing Cached HTML files from all the Frontend servers.
	    This is requried because as soon as any Update on done on listings, we need to delete its HTML files from all the servers, so that new HTML files
	    can be created on all of them.
    Input: Listing Id and Listing type
    Output: None
    *****************************/
    function deleteListingCacheHTMLFile($listingId,$listingType,$makeCurl=true)
    {
	//In case of course type, only delete the Overview file
	if($listingType=='course'){
	    $overviewFile = "ListingCache/".$listingId."_".$listingType."_overview.html";
	    if(file_exists($overviewFile))
	      unlink($overviewFile);
	}
	elseif($listingType=='institute'){	//In case of institute, delete the HTML files of all the tabs
	    $tabsArray = array('overview','alumni','media','course');
	    foreach ($tabsArray as $tabVal){ 
		$overviewFile = "ListingCache/".$listingId."_".$listingType."_".$tabVal.".html";
		if(file_exists($overviewFile))
		  unlink($overviewFile);
	    }
	}

	//After deleting the HTML files from the local server, also delete this file from other Frontend servers
	//The variable makeCurl is used so that we don't get in the loop of calling other Front end servers. This will be true only when this function is called from Local server.
	if($makeCurl){
	      global $shikshaRestFrontEndBoxes;
	      $c = curl_init();
	      for($i= 0  ;$i < count($shikshaRestFrontEndBoxes) ; $i++){
		  $url =  "http://".$shikshaRestFrontEndBoxes[$i]."/listing/Listing/deleteListingCacheHTMLFile/".$listingId."/".$listingType;
		  curl_setopt($c, CURLOPT_URL,$url);
		  curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
		  curl_exec($c);
	      }
	      curl_close($c);
	}
    }
	
	
	/*
	 Pankaj
	 @name: clientTrackAutoSuggestStats
	 @description: this is for tracking the user behaviour while using the autosuggest feature. Client Function
	 @param array $requestArray: requestArray has all the parameters required for the tracking. details can be found in the Listing controller file
	*/
	function clientTrackAutoSuggestStats($requestArray) {
		$appID = 1;
        $this->init();
		$this->CI_Instance->xmlrpc->method('serverTrackAutoSuggestStats');
		$request = array(array($appID,'int'),array($requestArray,'struct'));
		$this->CI_Instance->xmlrpc->request($request);
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
				return ($this->CI_Instance->xmlrpc->display_error());
		}else{
				return ($this->CI_Instance->xmlrpc->display_response());
		}
    }
	
	
	function getInformationForCourses($course_ids,$info_types = array())
    {
		/*
		 Convert to array
		*/
		if(!is_array($course_ids) || count($course_ids) == 0)
		{
			return FALSE;
		}
		
        $this->init();
        
		$course_info = array();
		
		/*
		 First check in cache
		*/
		
		$course_info_from_cache = $this->listingCache->getInformationForCourses($course_ids);
		
		/*
		 For courses not found in cache
		 Get data from DB
		*/
		 
		$course_ids_not_found_in_cache = array_diff($course_ids,array_keys($course_info_from_cache));
		
		if(count($course_ids_not_found_in_cache))
		{
			$this->CI_Instance->xmlrpc->method('getInformationForCourses');
			$request = array(array($appID,'int'),array($course_ids,'struct'),array($info_types,'struct'));
			
			$this->CI_Instance->xmlrpc->request($request);
			
			if ( ! $this->CI_Instance->xmlrpc->send_request())
			{
				return ($this->CI_Instance->xmlrpc->display_error());
			}
			else
			{
				$course_info_from_db = utility_decodeXmlRpcResponse($this->CI_Instance->xmlrpc->display_response());
				
				/*
				 Store in cache
				*/
				
				foreach($course_info_from_db as $course_id => $course_data)
				{
					$this->listingCache->storeInformationForCourse($course_id,$course_data);
				}
				
				/*
				 Add info retrieved from cache and db
				*/
				
				$course_info = $course_info_from_cache + $course_info_from_db;
			}
		}
		else
		{
			$course_info = $course_info_from_cache;
		}
		
		return $course_info;
    }
	
	function getInformationForCourse($course_id,$info_types = array())
    {
		if(!$course_id || !is_numeric($course_id))
		{
			return FALSE;
		}
		
		$course_info = $this->getInformationForCourses((array) $course_id);
		return $course_info[$course_id]; 
	}
	
	function getInformationForInstitutes($institute_ids,$info_types = array())
    {
		/*
		 Convert to array
		*/
		if(!is_array($institute_ids) || count($institute_ids) == 0)
		{
			return FALSE;
		}
		
        $this->init();
        
		$institute_info = array();
		
		/*
		 First check in cache
		*/
		
		$institute_info_from_cache = $this->listingCache->getInformationForInstitutes($institute_ids);
		
		/*
		 For institutes not found in cache
		 Get data from DB
		*/
		 
		$institute_ids_not_found_in_cache = array_diff($institute_ids,array_keys($institute_info_from_cache));
		
		if(count($institute_ids_not_found_in_cache))
		{
			$this->CI_Instance->xmlrpc->method('getInformationForInstitutes');
			$request = array(array($appID,'int'),array($institute_ids,'struct'),array($info_types,'struct'));
			
			$this->CI_Instance->xmlrpc->request($request);
			
			if ( ! $this->CI_Instance->xmlrpc->send_request())
			{
				return ($this->CI_Instance->xmlrpc->display_error());
			}
			else
			{
				$institute_info_from_db = utility_decodeXmlRpcResponse($this->CI_Instance->xmlrpc->display_response());
				
				/*
				 Store in cache
				*/
				
				foreach($institute_info_from_db as $institute_id => $institute_data)
				{
					$this->listingCache->storeInformationForInstitute($institute_id,$institute_data);
				}
				
				/*
				 Add info retrieved from cache and db
				*/
				
				$institute_info = $institute_info_from_cache + $institute_info_from_db;
			}
		}
		else
		{
			$institute_info = $institute_info_from_cache;
		}
		
		return $institute_info;
    }
	
	function getInformationForInstitute($institute_id,$info_types = array())
    {
		if(!$institute_id || !is_numeric($institute_id))
		{
			return FALSE;
		}
		
		$institute_info = $this->getInformationForInstitutes((array) $institute_id);
		return $institute_info[$institute_id]; 
	}

	function getInstituteTitle($appId,$id,$type)
	{
		$this->init();
		$this->CI_Instance->xmlrpc->method('getInstituteTitle');
		$request = array($appId,$id,$type);
		$this->CI_Instance->xmlrpc->request($request);
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
				return ($this->CI_Instance->xmlrpc->display_error());
		} else {
				$response = $this->CI_Instance->xmlrpc->display_response();
				return $response;
		}
	}
	
	function getLDBIdForCourseId($course_id)
	{
		$this->init();
		$this->CI_Instance->xmlrpc->method('sgetLDBIdForCourseId');
		$request = array($course_id);
		$this->CI_Instance->xmlrpc->request($request);
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
			return ($this->CI_Instance->xmlrpc->display_error());
		}else{
			$response = utility_decodeXmlRpcResponse($this->CI_Instance->xmlrpc->display_response());
			return $response;
		}
	}
	
	function getLdbCourseDetailsForLdbId($ldb_id, $multipleValues = false){
		$this->init();
		$this->CI_Instance->xmlrpc->method('sgetLdbCourseDetailsForLdbId');
		$request = array($ldb_id, $multipleValues);
		$this->CI_Instance->xmlrpc->request($request);
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
			return ($this->CI_Instance->xmlrpc->display_error());
		}else{
			$response = utility_decodeXmlRpcResponse($this->CI_Instance->xmlrpc->display_response());
			return $response;
		}
	}

	function getEstablishYearAndSeats($appId,$institute_id) {
		$this->initListing();
		$this->CI_Instance->xmlrpc->method('getEstablishYearAndSeats');
		$request = array(array($appId,'int'),array($institute_id,'int'));
		$this->CI_Instance->xmlrpc->request($request);
	        if ( !$this->CI_Instance->xmlrpc->send_request())
		{
			return ($this->CI_Instance->xmlrpc->display_error());
		}else{
			$response = ($this->CI_Instance->xmlrpc->display_response());

			return json_decode($response,true);
		}
	}
	
	function getInstituteLocationDetails($appID,$locationId,$tabStatus='live') {
        $this->initListing();
        $this->CI_Instance->xmlrpc->method('sgetInstituteLocationDetails');
        $request = array(array($appID,'int'),array($locationId,'int'),array($tabStatus,'string'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return json_decode($this->CI_Instance->xmlrpc->display_response(),TRUE);
        }
    }

    function checkIfUserIsResponse($institute_id,$user_id){
        $this->initListing();
        $this->CI_Instance->xmlrpc->method('checkIfUserIsResponse');
        $request = array(array($institute_id,'int'),array($user_id,'int'));
        $this->CI_Instance->xmlrpc->request($request);
        if(! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            $response = $this->CI_Instance->xmlrpc->display_response();
            return $response;
        }
    }

    function updateBulkListingsContactDetails($requestArray){
        $this->init();
        $this->CI_Instance->xmlrpc->method('updateBulkListingsContactDetails');
        $request = array(array($requestArray,'struct'));
        $this->CI_Instance->xmlrpc->request($request);

        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }

    function upgradeCourse($requestArray){
        $this->init();
        $this->CI_Instance->xmlrpc->method('upgradeCourse');
        $request = array(array($requestArray,'struct'));
        $this->CI_Instance->xmlrpc->request($request);

        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }
}
?>
