<?php

/*

Copyright 2007 Info Edge India Ltd

$Rev:: 103           $:  Revision of last commit
$Author: ashishj $:  Author of last commit
$Date: 2010-04-21 11:27:15 $:  Date of last commit

listing_client.php makes call to server using XML RPC calls.

$Id: KeyPagesClient.php,v 1.25 2010-04-21 11:27:15 ashishj Exp $: 

*/

class KeyPagesClient{
	
	var $CI_Instance;

	var $cacheLib;
    function init(){
        $this->CI_Instance = & get_instance();
        $this->CI_Instance->load->helper('url');
        //		$server_url = site_url('listing/listing_server');
        $this->CI_Instance->load->library('xmlrpc');
        $this->CI_Instance->xmlrpc->set_debug(0);
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


/********NEW WS CODE **********/

    function getInstituteListIndexed($appID,$requestArray){ //requestArray is actually _POST array 
        $this->init();
        $this->CI_Instance->xmlrpc->server(Listing_SERVER_URL, Listing_SERVER_PORT);			
        $key = md5('getInstituteListIndexed'.$requestArray['countryId'].$requestArray['catId'].$requestArray['indexOf']);
        error_log_shiksha("key for cache is : ".$key);
        if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
            $this->CI_Instance->xmlrpc->method('sgetInstituteListIndexed');
            $request = array(array($appID,'int'),array($requestArray,'struct'));
            $this->CI_Instance->xmlrpc->request($request);

            if ( ! $this->CI_Instance->xmlrpc->send_request()){
                error_log_shiksha("ERROR: KEYPAGES_CLIENT::getInstituteListIndexed: FAIL".$this->CI_Instance->xmlrpc->display_error());
                error_log_shiksha("DEBUG: KEYPAGES_CLIENT::getInstituteListIndexed: EXIT FAILURE");
                return ($this->CI_Instance->xmlrpc->display_error());
            }else{
                error_log_shiksha("DEBUG: KEYPAGES_CLIENT::getInstituteListIndexed: EXIT SUCCESS");
                $response = $this->CI_Instance->xmlrpc->display_response();
                $this->cacheLib->store($key,$response);
                return $response;
            }
        }else{
            error_log_shiksha("DEBUG: KEYPAGES_CLIENT::getInstituteListIndexed: EXIT SUCCESS Reading from cache");
            return $this->cacheLib->get($key);
        } 

    }

    function getInstitutesForHomePageS($appId, $categoryId, $countryId, $start, $count, $keyValue, $cityId,$relaxFlag = 0){
//	    error_log("DODO: KEYPAGES_CLIENT::getInstitutesForHomePages: ENTRY");
	    $this->init();
	    $this->CI_Instance->xmlrpc->server(Listing_SERVER_URL, Listing_SERVER_PORT);			
	    $key = md5('getInstitutesForHomePageS'.$appId.$categoryId.$countryId.$start.$count.$keyValue.$cityId.$relaxFlag);
        $countKey =  md5('getNumberOfMainInstiS'.$appId.$categoryId.$countryId.$keyValue.$cityId.$relaxFlag);

	    $doCache=0;
	    //do cache only for initial records
	    if(($count<=COUNT_HACK) && ($start==0) && ($categoryId < 200 || $categoryId == '') && ($countryId < 20 || $countryId == '')){
		    $doCache=1;
	    }

	    if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
		    if($keyValue != 1 && $start == 0){
               // if($this->cacheLib->get($countKey)=='ERROR_READING_CACHE'){
                    error_log("Cache Missed DODO".$countKey);
                    $this->CI_Instance->xmlrpc->method('getNumberOfMainInstiS');
                    $request = array($appId,  $categoryId, $countryId , $start, $newCount, $keyValue, $cityId,$relaxFlag); 
//                    error_log("DODO :".print_r($request,true));
                    $this->CI_Instance->xmlrpc->request($request);
                    if ( ! $this->CI_Instance->xmlrpc->send_request()){ 
                        error_log_shiksha("ERROR: KEYPAGES_CLIENT::getInstitutesForHomePageS: FAIL".$this->CI_Instance->xmlrpc->display_error());
                        error_log_shiksha("DEBUG: KEYPAGES_CLIENT::getInstitutesForHomePageS: EXIT FAILURE");
                        return;
                    }else{
                        error_log_shiksha("DEBUG: KEYPAGES_CLIENT::getInstitutesForHomePageS: EXIT SUCCESS");
                        $response = $this->CI_Instance->xmlrpc->display_response();
                    }
                    error_log("DODO ".print_r($response,true));
                    $newCount=$response[0]['count'];
                    $left = $newCount%$count;
                    $newCount = $newCount - $left + $count;
                    $this->cacheLib->store($countKey,$newCount);
                    error_log("DODO  11111111 ".$this->cacheLib->get($countKey));
             /*   }else {
                    $newCount = $this->cacheLib->get($countKey);
                }*/
			    //Not the home page & fetching page other than first page
                //TODO: hack when paid listings are more than 100, fetch 200 results for pan-india pages to avoid paid listings shuffling on page refreshes for pagination > 5
                //Later on it has to be fixed by caching all the buckets till paid listings are coming for any browse criteria
		    }
		    else{
			    $newCount = $count;
		    }
		    $this->CI_Instance->xmlrpc->method('getInstitutesForHomePageS');
		    $request = array($appId,  $categoryId, $countryId , $start, $newCount, $keyValue, $cityId,$relaxFlag); 
		    error_log_shiksha("LISTING:".print_r($request,true));
		    $this->CI_Instance->xmlrpc->request($request);
		    if ( ! $this->CI_Instance->xmlrpc->send_request()){ 
			    error_log_shiksha("ERROR: KEYPAGES_CLIENT::getInstitutesForHomePageS: FAIL".$this->CI_Instance->xmlrpc->display_error());
			    error_log_shiksha("DEBUG: KEYPAGES_CLIENT::getInstitutesForHomePageS: EXIT FAILURE");
			    return;
		    }else{
			    error_log_shiksha("DEBUG: KEYPAGES_CLIENT::getInstitutesForHomePageS: EXIT SUCCESS");
			    $response = $this->CI_Instance->xmlrpc->display_response();
			    if($doCache==1){
                    for($newStart = 0; $newStart < $newCount; $newStart=$newStart+$count){
                        $respArr[0]['total'] = $response[0]['total'];
                        $respArr[0]['logourl'] = $response[0]['logourl'];
                        $respArr[0]['institutes'] = array_slice($response[0]['institutes'],$start,$count);
                        if(count($respArr[0]['institutes']) > 0){
                            $key1 = md5('getInstitutesForHomePageS'.$appId.$categoryId.$countryId.$start.$count.$keyValue.$cityId.$relaxFlag);
                            $this->cacheLib->store($key1,$respArr);
                            $start = $start+$count;
                        }
                        else{
                            break;
                        }
                    }
                    if(count($response[0]['institutes']) > 0){
                        $institutes = $this->cacheLib->get($key);
                    }
			    }
			    else{
				    $institutes = $response;
			    }
			    $this->rotateInstitutes($institutes);
			    return $institutes;
		    }
	    }else{
		    error_log_shiksha("DEBUG: KEYPAGES_CLIENT::getInstitutesForHomePageS: EXIT SUCCESS");
		    $institutes = $this->cacheLib->get($key);
		    $this->rotateInstitutes($institutes);
		    return $institutes;
	    } 
    }

    function getScholarshipsForHomePageS($appId, $categoryId, $countryId, $start, $count, $keyValue,$relaxFlag = 0,$cityId= 1){
		error_log_shiksha("DEBUG: KEYPAGES_CLIENT::getScholarshipsForHomePageS: ENTRY");
		$doCache=0;
		//do cache only for initial records categoryid 1 and countryid 1.
		if(($count<=20) && ($start==0) && ($categoryId < 200 || $categoryId == '') && ($countryId < 20 || $countryId == '')){
		        $doCache=1;
		}


		$this->init();
        $this->CI_Instance->xmlrpc->server(Listing_SERVER_URL, Listing_SERVER_PORT);			
		$key = md5('getScholarshipsForHomePageS'.$appId.$categoryId.$countryId.$start.$count.$keyValue.$relaxFlag.$cityId);
        error_log_shiksha("LISTING:catid:".$categoryId);
        error_log_shiksha("LISTING:countid:".$countryId);
        error_log_shiksha("LISTING:pageKey:".$keyValue);
        error_log_shiksha("LISTING:start:".$start);
        error_log_shiksha("LISTING:count:".$count);
		if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
			$this->CI_Instance->xmlrpc->method('getScholarshipsForHomePageS');
			$request = array($appId,  $categoryId, $countryId , $start, $count, $keyValue,$relaxFlag,$cityId); 
            error_log_shiksha("LISTING:".print_r($request,true));
			$this->CI_Instance->xmlrpc->request($request);
			if ( ! $this->CI_Instance->xmlrpc->send_request()){ 
				error_log_shiksha("ERROR: KEYPAGES_CLIENT::getScholarshipsForHomePageS: FAIL".$this->CI_Instance->xmlrpc->display_error());
				error_log_shiksha("DEBUG: KEYPAGES_CLIENT::getScholarshipsForHomePageS: EXIT FAILURE");
				return;
			}else{
				error_log_shiksha("DEBUG: KEYPAGES_CLIENT::getScholarshipsForHomePageS: EXIT SUCCESS");
				$response = $this->CI_Instance->xmlrpc->display_response();
                if($doCache==1){
                    $this->cacheLib->store($key,$response);
                }
				return $response;
			}
		}else{
			error_log_shiksha("DEBUG: KEYPAGES_CLIENT::getScholarshipsForHomePageS: EXIT SUCCESS");
			return $this->cacheLib->get($key);
		} 
	}

	function getCoursesForHomePageS($appId, $categoryId, $countryId, $start, $count, $keyValue,$cityId,$relaxFlag=0){
		error_log_shiksha("DEBUG: LISTING_CLIENT::getInstitutesForHomePages: ENTRY");
		$this->initListing();
		$key = md5('getCoursesForHomePageS'.$appId.$categoryId.$countryId.$start.$count.$keyValue.$cityId.$relaxFlag);
		$doCache=0;
		//do cache only for initial records
		if(($count<=20) && ($start==0) && ($categoryId < 200 || $categoryId == '') && ($countryId < 20 || $countryId == '')){
		        $doCache=1;
		}

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
                if($doCache==1){
                    $this->cacheLib->store($key,$response);
                }
				return $response;
			}
		}else{
			error_log_shiksha("DEBUG: LISTING_CLIENT::getInstitutesForHomePageS: EXIT SUCCESS");
			return $this->cacheLib->get($key);
		}
	}

    function rotateInstitutes(& $institutes) {
        $toggler = array();
        if(isset($institutes[0]) && isset($institutes[0]['institutes']) && (count($institutes[0]['institutes']) > 0)){
            foreach($institutes[0]['institutes'] as $institute) {
                if($institute['isSponsored'] != 'true') break;
                $toggler[] = $institute;
            }
        }
        if(count($toggler) === 0) return;
        if(CATEGORY_HOME_PAGE_COLLEGES_PERSISTENT_ROTATION === true) {
            if(!isset($_COOKIE['CPLToken']) || $_COOKIE['CPLToken']=='') {
                $token = rand(1,CATEGORY_HOME_PAGE_COLLEGES_COUNT) % count($toggler);
                setcookie('CPLToken',$token,0,'/',COOKIEDOMAIN);
            }else{
                $token = $_COOKIE['CPLToken'];
            }
        } else {
            $token = rand(1,CATEGORY_HOME_PAGE_COLLEGES_COUNT) % count($toggler);
        }
        for($instituteCount = 0, $togglerCount = $token; ;) {
            $institutes[0]['institutes'][$instituteCount++] = $toggler[$togglerCount];
            $togglerCount = ($togglerCount == count($toggler) -1) ? 0 : ($togglerCount + 1);
            if($togglerCount == $token) break;
        }
    }
}
?>
