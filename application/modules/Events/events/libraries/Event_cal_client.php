<?php
/*

   Copyright 2007 Info Edge India Ltd

   $Rev:: $: Revision of last commit
   $Author: pankajt $: Author of last commit
   $Date: 2010-09-01 09:27:08 $: Date of last commit

   event_cal_client.php makes call to server using XML RPC calls.

   $Id: Event_cal_client.php,v 1.73 2010-09-01 09:27:08 pankajt Exp $: 

 */

class Event_cal_client{
    var $CI_Instance;
    var $cacheLib;
	var $eventCache;
	
    function init($what='read'){
        $this->CI_Instance = & get_instance();
        $this->CI_Instance->load->library('xmlrpc');
        $this->CI_Instance->xmlrpc->set_debug(0);
		$server_url = EVENT_READ_SERVER_URL;
		$server_port = EVENT_READ_SERVER_PORT;
		if($what=='write'){
			$server_url = EVENT_SERVER_URL;
			$server_port = EVENT_SERVER_PORT;
		}
        $this->CI_Instance->xmlrpc->server($server_url, $server_port);
        $this->CI_Instance->load->library('cacheLib');
        $this->cacheLib = new cacheLib();
		$this->CI_Instance->load->library('Event_cache',$this->cacheLib);
		$this->eventCache = $this->CI_Instance->event_cache;
    }

    //get category list based on category ID
    function getEventCountByMonth($appId, $month, $year, $countryId, $cityId, $categoryId=1){
        $doCache=0;

        //do cache only for current month, year and category,country selected is all 
        if($month == (int)date("m") && $year == date("Y") && $categoryId == 1 && $countryId == 1){
            $doCache=1;
        }
        $this->init();

        //make key
        $key = md5('getEventCountByMonth'.$appId.$month.$year.$countryId.$cityId.$categoryId);

        if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
            $this->CI_Instance->xmlrpc->method('getEventCountByMonth');
            $request = array($appId, $month, $year, $categoryId, $countryId, $cityId);
            $this->CI_Instance->xmlrpc->request($request);		
            if ( ! $this->CI_Instance->xmlrpc->send_request()){
                return $this->CI_Instance->xmlrpc->display_error();
            }else{
                $response = $this->CI_Instance->xmlrpc->display_response();
                if($doCache==1){
                    $this->cacheLib->store($key,$response,14400,'Events');
                }
                return $response;
            }
        }else{
            return $this->cacheLib->get($key);
        } 
    }

    function index(){
        echo "Use any webservice method to continue";		
    }

    function getEventListByDate($appId, $day, $month, $year, $countryId,$cityId, $categoryId, $start, $count){
        $this->init(); 
        $doCache=0;

        //do cache only for current date, month, year and category,country selected is all
        if($day == (int)date("d") && $month == (int)date("m") && $year == date("Y") && $categoryId == 1 && $countryId == 1 && $cityId == ''){
            $doCache=1;
        }
        $key = md5('getEventListByDate'.$appId.$day.$month.$year.$countryId.$cityId.$categoryId.$start.$count);
        if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
            $this->CI_Instance->xmlrpc->method('getEventListByDate');
            $request = array($appId, $day, $month, $year, $categoryId, $countryId,$cityId, $start, $count); 
            $this->CI_Instance->xmlrpc->request($request);
            if ( ! $this->CI_Instance->xmlrpc->send_request()){
                return $this->CI_Instance->xmlrpc->display_error();
            }else{
                $response = $this->CI_Instance->xmlrpc->display_response();
                if($doCache==1){
                    $this->cacheLib->store($key,$response,14400,'Events');
                }
                return $response;
            }    
        }else{
            return $this->cacheLib->get($key);
        } 
    }

    function getEventDetail($appId,$event_id,$userId=0){
        $this->init();
        $this->CI_Instance->xmlrpc->method('getEventDetail');
        $request = array($appId,$event_id,$userId);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            error_log_shiksha("EVENT_CAL_CLIENT::getEventDetail ---- error:=>".$this->CI_Instance->xmlrpc->display_error());
            return array();
        }else{
            // print_r($this->CI_Instance->xmlrpc->display_response());
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }

    function getRecentEvent($appId,$start, $count, $countryId=1,$cityId = '', $categoryId=1){
        $this->init();
        $this->CI_Instance->xmlrpc->method('getRecentEvent');

        $key = md5('getRecentEvent'.$appId.$start.$count.$countryId.$cityId.$categoryId);
        if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){

        	$request = array($appId,$start, $count, $categoryId, $countryId, $cityId); 
        	$this->CI_Instance->xmlrpc->request($request);
        	if ( ! $this->CI_Instance->xmlrpc->send_request()){ 
            		error_log_shiksha("EVENT_CAL_CLIENT::getRecentEvent: FAIL".$this->CI_Instance->xmlrpc->display_error());
			#echo $this->CI_Instance->xmlrpc->display_error();
        	}else{
            		error_log_shiksha("SUCCESS");
			$response = $this->CI_Instance->xmlrpc->display_response();
			$this->cacheLib->store($key,$response,14400,'Events');
            return $response;
        	}
	}else{
            return $this->cacheLib->get($key);
        } 
    }

    function getRecentEventCMS($appId,$start, $count, $categoryId=1,$srchEvent,$srchVenue,$filter1,$filter2,$showReportedAbuse='false',$usergroup,$userid){
        $this->init();
        $this->CI_Instance->xmlrpc->method('getRecentEventCMS');
        $request = array($appId,$start, $count, $categoryId,$srchEvent,$srchVenue,$filter1,$filter2,$showReportedAbuse,$usergroup,$userid); 
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){ 
            error_log_shiksha("EVENT_CAL_CLIENT::getRecentEventCMS: FAIL".$this->CI_Instance->xmlrpc->display_error());
#echo $this->CI_Instance->xmlrpc->display_error();
        }else{
            error_log_shiksha("SUCCESS");
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }


    function getRelatedEvent($appId,$start,$count,$categoryId=1, $countryId=1, $eventId){
        $this->init();
        $this->CI_Instance->xmlrpc->method('getRelatedEvent');
        $request = array($appId,$start,$count, $categoryId, $countryId, $eventId); 
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){ 
            error_log_shiksha("FAIl".$this->CI_Instance->xmlrpc->display_error());
#echo $this->CI_Instance->xmlrpc->display_error();
        }else{
            error_log_shiksha("SUCCESS");
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }
	
	function getEventsForListing($appId,$start,$count,$listingType,$listingTypeId){	
        $this->init();
        $this->CI_Instance->xmlrpc->method('getEventsForListing');
        $request = array($appId,$start,$count,$listingType,$listingTypeId);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            error_log_shiksha("FAIl".$this->CI_Instance->xmlrpc->display_error());
	#echo $this->CI_Instance->xmlrpc->display_error();
        }else{
            error_log_shiksha("SUCCESS");
            return ($this->CI_Instance->xmlrpc->display_response());
        }
        }
	


    function getCategoryEventsCountForCountry($appId, $countryId,$categoryId=1){
        $this->init();
        $doCache=0;

        //do cache only when country selected is all
        if($countryId == 1){
            $doCache=1;
        }

        $key = md5('getEventBoardCount'.$appId.$countryId.$categoryId);
        if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){		
            $this->CI_Instance->xmlrpc->method('getEventBoardCount');
            $request = array($appId, $countryId,$categoryId); 
            $this->CI_Instance->xmlrpc->request($request);
            if ( ! $this->CI_Instance->xmlrpc->send_request()){ 
                error_log_shiksha("getCategoryEventsCountForCountry::Failure====> ".$this->CI_Instance->xmlrpc->display_error());
#echo $this->CI_Instance->xmlrpc->display_error();
                return ;
            }else{
                error_log_shiksha("getCategoryEventsCountForCountry::SUCCESS");
                $response = $this->CI_Instance->xmlrpc->display_response();
                if($doCache==1){    
                    $this->cacheLib->store($key,$response,14400,'Events');
                }    
                return $response;
            }
        }else{
            return $this->cacheLib->get($key);
        } 
    }

    function addEvent($appId,$requestArray,$fromOthers=0,$listingTypeId=0,$listingType=''){
        $this->init('write'); 
        $this->CI_Instance->xmlrpc->method('addEvent');
 //       $requestArray['board_id'] = 1;
        $request = array(array($appId,'int'),array($requestArray,'struct'),array($fromOthers,'int'),array($listingTypeId,'int'),array($listingType,'string'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            error_log_shiksha("ERROR AT DATABASE". $this->CI_Instance->xmlrpc->display_error());
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            $eventId = array_pop($this->CI_Instance->xmlrpc->display_response());
            error_log_shiksha("Successfully Added to the Database with ID==>". $eventId);
            $this->cacheLib->clearCache('Events');
        }
        return($eventId);
    }
    function addEventNew($appId,$requestArray,$fromOthers=0,$locationArray = array(),$listingTypeId=0,$listingType=''){
        $this->init('write'); 
        $this->CI_Instance->xmlrpc->method('addEventForListing');
//        $requestArray['board_id'] = 1;
        error_log_shiksha(print_r($locationArray,true));
        array_push($requestArray,array(
                    array(
                        'venues'=>array($locationArray,'struct')
                        ),'struct')
                );//close array_push

        $request = array(array($appId,'int'),array($requestArray,'struct'),array($fromOthers,'int'),array($listingTypeId,'int'),array($listingType,'string'));
        error_log_shiksha(print_r($request,true));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            error_log_shiksha("ERROR AT DATABASE". $this->CI_Instance->xmlrpc->display_error());
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            $eventId = array_pop($this->CI_Instance->xmlrpc->display_response());
            error_log_shiksha("Successfully Added to the Database with ID==>". $eventId);
            $this->cacheLib->clearCache('Events');
        }
        return($eventId);
    }

    function updateEvent($appId,$requestArray,$fromOthers=0,$listingTypeId=0,$listingType=''){
        $this->init('write'); 
        $this->CI_Instance->xmlrpc->method('updateEvent');
        //$requestArray['board_id'] = 1;
        $request = array(array($appId,'int'),array($requestArray,'struct'),array($fromOthers,'int'),array($listingTypeId,'int'),array($listingType,'string'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            error_log_shiksha("ERROR AT DATABASE". $this->CI_Instance->xmlrpc->display_error());
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            $eventId = array_pop($this->CI_Instance->xmlrpc->display_response());
            error_log_shiksha("Successfully updated to the Database with ID==>". $eventId);
            $this->cacheLib->clearCache('Events');
            return $eventId;
        }
    }

    function getMyEvents($appId, $categoryId, $start, $count, $countryId, $userId){
        $this->init();
        $this->CI_Instance->xmlrpc->method('getMyEvents');
        $request = array($appId, $categoryId, $start, $count, $countryId, $userId); 
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){ 
            error_log_shiksha("getMyEvents::Failure====> ".$this->CI_Instance->xmlrpc->display_error());
#echo $this->CI_Instance->xmlrpc->display_error();
        }else{
            error_log_shiksha("getMyEvents::SUCCESS");
            return ($this->CI_Instance->xmlrpc->display_response());
        }		
    } 

    function getMySubscribedEvents($appId, $categoryId, $start, $count, $countryId, $userId){
        $this->init();
        $this->CI_Instance->xmlrpc->method('getMySubscribedEvents');
        $request = array($appId, $categoryId, $start, $count, $countryId, $userId);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            error_log_shiksha("getMySubscribedEvents::Failure====> ".$this->CI_Instance->xmlrpc->display_error());
        }else{
            error_log_shiksha("getMySubscribedEvents::SUCCESS");
            return ($this->CI_Instance->xmlrpc->display_response());
        }		
    } 

    function getMyEventsCount($appId, $categoryId, $countryId, $userId){
        $this->init();
        $this->CI_Instance->xmlrpc->method('getMyEventCount');
        $request = array($appId, $categoryId, $countryId, $userId); 
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){ 
            error_log_shiksha("getMyEventCount::Failure====> ".$this->CI_Instance->xmlrpc->display_error());
        }else{
            error_log_shiksha("getMyEventCount::SUCCESS");
            return ($this->CI_Instance->xmlrpc->display_response());
        }		
    } 

    function deleteEventSubscription($appId, $eventId, $status=1, $penalty=0){
        $this->init('write');
        $this->CI_Instance->xmlrpc->method('deleteEventSubscription');
        $request = array($appId, $eventId,$status,$penalty);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            error_log_shiksha("deleteEvent::Failure====> ".$this->CI_Instance->xmlrpc->display_error());
        }else{
            error_log_shiksha("deleteEvent::SUCCESS");
            $dbResponse = $this->CI_Instance->xmlrpc->display_response();
            $this->cacheLib->clearCache('Events');
            return ($dbResponse);
        }
    }
    /** Delete Event */

    function deleteEvent($appId, $eventId, $status=1, $penalty=0){
        $this->init('write');
        $this->CI_Instance->xmlrpc->method('deleteEvent');
        $request = array($appId, $eventId,$status,$penalty); 
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){ 
            error_log_shiksha("deleteEvent::Failure====> ".$this->CI_Instance->xmlrpc->display_error());
#echo $this->CI_Instance->xmlrpc->display_error();
        }else{
            error_log_shiksha("deleteEvent::SUCCESS");
            $dbResponse = $this->CI_Instance->xmlrpc->display_response();
            $this->CI_Instance->load->library('listing_client');
            $listingClientObj =  new Listing_client();
            $searchResponse = $listingClientObj->deleteListing($appId,'Event',$eventId);
            $this->cacheLib->clearCache('Events');
            return ($dbResponse);
        }		
    } 
    /** Report Abuse Event */

    function reportAbuseEvent($appId, $eventId){
        $this->init('write');
        $this->CI_Instance->xmlrpc->method('reportAbuse');
        $request = array($appId, $eventId); 
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){ 
            error_log_shiksha("reportAbuseEvent::Failure====> ".$this->CI_Instance->xmlrpc->display_error());
#echo $this->CI_Instance->xmlrpc->display_error();
        }else{
            error_log_shiksha("reportAbuseEvent::SUCCESS");
            return ($this->CI_Instance->xmlrpc->display_response());
        }		
    } 

    /** Event Search **/
    function initSearch() {
        $this->CI_Instance = & get_instance();
        $server_url = SEARCH_EVENT_SERVER_URL;		
        $this->CI_Instance->load->library('xmlrpc');
        $this->CI_Instance->xmlrpc->set_debug(0);
        $this->CI_Instance->xmlrpc->server($server_url, 80);
    }

    function indexEvent($appId,$request, $eventID) {
        $this->initSearch();
        $request_array['uniqueId']="Event".$eventID;
        $request_array['Id'] = $eventID;
        $request_array['title'] = $request['event_title'];
        $request_array['content'] = $request['description'];
        if(strlen($request['description']) > 200){
            $shortContent = substr($request['description'],0,197);
            $shortContent .= "...";
        } else {	
            $shortContent = $request['description'];
        }
        $request_array['shortContent'] = $shortContent;
        $request_array['venueAddress'] = $request['Address_Line1'];
        $request_array['cityList'] = $request['city'];
        $request_array['countryList'] = $request['country'];
        //$request_array['state'] = $request['state'];
        $request_array['startDate'] = $request['start_date'];
        $request_array['endDate'] = $request['end_date'];
        $request_array['organizerName'] = $request['venue_name'];
        $request_array['type'] = 'Event';
        $request_array['categoryList'] = $request['board_id'];
        $request_array['tags'] = $request['Tag'];
        $request_array['userId'] = $request['user_id'];
        $request_array['packtype'] = $request['packtype'];
        $request_array['detailPageUrl'] = $request['detailPageUrl'];
        $this->CI_Instance->xmlrpc->method('indexEventRecord');
        $this->CI_Instance->xmlrpc->request(array(array($request_array, 'struct')));
        if ( ! $this->CI_Instance->xmlrpc->send_request()) {
            error_log_shiksha("Successfully Added to the Lucene");
            return $this->CI_Instance->xmlrpc->display_error();
        } else {
            error_log_shiksha("Successfully Added to the Lucene");
            return $this->CI_Instance->xmlrpc->display_response();
        }
    }

    function deleteListingRecord($appId,$type,$eventId)
    {
        $this->initSearch();
        $this->CI_Instance->xmlrpc->method('deleteListingRecord');
        $this->CI_Instance->xmlrpc->request(array($appId,$type,$eventId));
        if ( ! $this->CI_Instance->xmlrpc->send_request()) {
            return $this->CI_Instance->xmlrpc->display_error();
        } else {
            return $this->CI_Instance->xmlrpc->display_response();
            error_log_shiksha("Successfully retrived from the lucene");
        }
    }

    function topicSearch($appId,$keyword,$location,$start,$rows) {
        $this->initSearch();
        $this->CI_Instance->xmlrpc->method('searchEventKeyword');
        $request = array ($appId,$keyword,$location,$start,$rows);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()) {
            return $this->CI_Instance->xmlrpc->display_error();
        } else {
            return $this->CI_Instance->xmlrpc->display_response();
            error_log_shiksha("Successfully retrived from the lucene");
        }
    }

    function getDetailsForSearch($appId,$requestArray){
        $this->init();
        $this->CI_Instance->xmlrpc->method('getDetailsForSearch');
        $request=array(array($appId, 'int'),array($requestArray,'struct'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            return $this->CI_Instance->xmlrpc->display_response();
        }

    }
    function getEventForIndex($appId,$id){
        $this->init();
        $this->CI_Instance->xmlrpc->method('getEventForIndex');
        $request=array(array($appId, 'int'),array($id,'int'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            return $this->CI_Instance->xmlrpc->display_response();
        }
    }

    function getEventsForHomePage($appId, $countryId, $categoryId, $start, $count, $keyValue, $fromOthers, $parentId,$cityId){
        error_log_shiksha("DEBUG: EVENT_CAL_CLIENT::getEventsForHomePage: ENTRY");
        $this->init();
        $key = md5('getEventsForHomePageS'.$appId.$categoryId.$countryId.$start.$count.$keyValue.$fromOthers.$parentId.$cityId);
        if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
            $this->CI_Instance->xmlrpc->method('getEventsForHomePageS');
            $request = array($appId, $countryId, $categoryId, $start, $count, $keyValue, $fromOthers, $parentId,$cityId); 
            $this->CI_Instance->xmlrpc->request($request);
            if ( ! $this->CI_Instance->xmlrpc->send_request()){ 
                error_log_shiksha("ERROR: EVENT_CAL_CLIENT::getEventsForHomePage: FAIL".$this->CI_Instance->xmlrpc->display_error());
                error_log_shiksha("DEBUG: EVENT_CAL_CLIENT::getEventsForHomePage: EXIT FAILURE");
                return;
            }else{
                error_log_shiksha("DEBUG: EVENT_CAL_CLIENT::getEventsForHomePage: EXIT SUCCESS");
                $response = $this->CI_Instance->xmlrpc->display_response();
                $this->cacheLib->store($key,$response);
                return $response;
            }
        }else{
            error_log_shiksha("DEBUG: EVENT_CAL_CLIENT::getEventsForHomePage: EXIT SUCCESS");
            return $this->cacheLib->get($key);
        } 
    }

    function getExamForHomePage($appId, $countryId, $categoryId, $start, $count, $keyValue){
        return $this->getEventsForHomePage($appId, $countryId, $categoryId, $start, $count, $keyValue, 3); 
    }	

    function getNotificationsForHomePage($appId, $countryId, $categoryId, $start, $count, $keyValue){
        return $this->getEventsForHomePage($appId, $countryId, $categoryId, $start, $count, $keyValue, 1); 
    }

    function getLatestForHomePage($appId, $countryId, $categoryId, $start, $count, $keyValue){
        return $this->getEventsForHomePage($appId, $countryId, $categoryId, $start, $count, $keyValue, 2); 
    }

    function deleteListingEvent($appId, $listingId, $listingType){
        $this->init('write');
        $this->CI_Instance->xmlrpc->method('deleteListingEvent');
        $request = array($appId, $listingId, $listingType); 
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){ 
            error_log_shiksha("deleteListingEvent::Failure====> ".$this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }

    function getEventsForExams($appId, $examId, $start, $count,$categoryId=1, $fromOthers = ''){
        $this->init();
        $this->CI_Instance->xmlrpc->method('getEventsForExams');
        $request = array($appId, $examId, $start, $count,$categoryId, $fromOthers); 
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){ 
            error_log_shiksha("getEventsForExams::Failure====> ".$this->CI_Instance->xmlrpc->display_error());
        }else{
            $response = ($this->CI_Instance->xmlrpc->display_response());
            return $response;
        }
   }
 
    function getExamsForEvent($appId, $eventId){
        $this->init();
        $this->CI_Instance->xmlrpc->method('getExamsForEvent');
        $request = array($appId, $eventId); 
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){ 
            error_log_shiksha("getExamsForEvent::Failure====> ".$this->CI_Instance->xmlrpc->display_error());
        }else{
            $response = ($this->CI_Instance->xmlrpc->display_response());
            return $response;
        }
    }
	
    function addEventsForListing($appId,$data,$course_id){
//	error_log("inside addEventsForListing Client");
	$this->init('write');
        $this->CI_Instance->xmlrpc->method('addEventsForListing');
        $request = array(array($appID,'int'),array(serialize($data),'string'),array($course_id,'string'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            error_log_shiksha("addEventsForListing::Failure====> ".$this->CI_Instance->xmlrpc->display_error());
        }else{
            $response = ($this->CI_Instance->xmlrpc->display_response());
            return $response;
        }
    }

    function addUpdateEventsForListing($appId,$courseId,$data){
//        error_log("inside addUpdateEventsForListing Client");
        $this->init('write');
        $this->CI_Instance->xmlrpc->method('addUpdateEventsForListing');
        $request = array(array($appID,'int'),array($courseId,'int'),array(base64_encode(serialize($data)),'string'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            error_log_shiksha("addEventsForListing::Failure====> ".$this->CI_Instance->xmlrpc->display_error());
        }else{
            $response = ($this->CI_Instance->xmlrpc->display_response());
            return $response;
        }
    }
	
    function getSpotlightEvents(){
    $this->init();
    $this->CI_Instance->xmlrpc->method('getSpotlightEvents');
    $this->CI_Instance->xmlrpc->request($request);

    if ( ! $this->CI_Instance->xmlrpc->send_request()){
        return ($this->CI_Instance->xmlrpc->display_error());
    }else{
        $response = $this->CI_Instance->xmlrpc->display_response();
        return $response;
    }
    }

    function getEventsByCategoryDateLocation($countryName,$category_id,$location_id,$start_date,$end_date){
    $this->init();
    $this->CI_Instance->xmlrpc->method('getEventsByCategoryDateLocation');
    $request = array(array($countryName,'string'),array($category_id,'int'),array($location_id,'string'),array($start_date,'date'),array($end_date,'date'));
    $this->CI_Instance->xmlrpc->request($request);

    if ( ! $this->CI_Instance->xmlrpc->send_request()){
        return ($this->CI_Instance->xmlrpc->display_error());
    }else{
        $response = $this->CI_Instance->xmlrpc->display_response();
        return $response;
    }
    }

    function viewAllEvents($countryName,$fromOthers,$category_id,$location_id,$start,$count,$days){
	$this->init('write');
        $this->CI_Instance->xmlrpc->method('viewAllEvents');
        $request = array(array($countryName,'string'),array($fromOthers,'int'),array($category_id,'int'),array($location_id,'string'),array($start,'int'),array($count,'int'),array($days,'string'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            error_log_shiksha("addEventsForListing::Failure====> ".$this->CI_Instance->xmlrpc->display_error());
        }else{
            $response = ($this->CI_Instance->xmlrpc->display_response());
            return $response;
        }
	}

    function eventMigration(){
    error_log_shiksha("inside eventMigration in client");
    $this->init('write');
    $this->CI_Instance->xmlrpc->method('eventMigration');
    $this->CI_Instance->xmlrpc->request($request);

    if ( ! $this->CI_Instance->xmlrpc->send_request()){
        return ($this->CI_Instance->xmlrpc->display_error());
    }else{
        $response = $this->CI_Instance->xmlrpc->display_response();
        return $response;
    }
    }

    function subscribeEvents($appId,$userId,$event_id,$subscriptionType,$subscriptionTitle,$mobile_number,$email,$privacySettings,$category,$locationName,$fromOthers,$locationId){
        $this->init('write');
        $this->CI_Instance->xmlrpc->method('subscribeEvents');
        $request = array($appId,$userId,$event_id,$subscriptionType,$subscriptionTitle,$mobile_number,$email,$privacySettings,$category,$locationName,$fromOthers,$locationId);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            error_log_shiksha("subscribeEvents::Failure====> ".$this->CI_Instance->xmlrpc->display_error());
        }else{
            $response = ($this->CI_Instance->xmlrpc->display_response());
            return $response;
        }
    }
	
     function getAllSubscriptionsEmail($appId=1){
        $this->init();
        $this->CI_Instance->xmlrpc->method('getAllSubscriptionsEmail');
        $request = array($appId);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            error_log_shiksha("getAllSubscriptions::Failure====> ".$this->CI_Instance->xmlrpc->display_error());
        }else{
            $response = ($this->CI_Instance->xmlrpc->display_response());
            return $response;
        }
    }

    function getAllSubscriptionsSMS($appId=1){
        $this->init();
        $this->CI_Instance->xmlrpc->method('getAllSubscriptionsSMS');
        $request = array($appId);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            error_log_shiksha("getAllSubscriptions::Failure====> ".$this->CI_Instance->xmlrpc->display_error());
        }else{
            $response = ($this->CI_Instance->xmlrpc->display_response());
            return $response;
        }
    }

    /*
	 Upcoming events widget
	*/
	
	function getUpcomingEvents($category_id,$subcategory_id)
	{
		$this->init();
		
		/*
		 Check in cache first
		*/
		if($events_from_cache = $this->eventCache->getUpcomingEvents($category_id,$subcategory_id))
		{
			return $events_from_cache;
		}
		else
		{
			$this->CI_Instance->xmlrpc->method('getUpcomingEvents');
			$request = array($category_id,$subcategory_id);
			$this->CI_Instance->xmlrpc->request($request);
			
			if ( ! $this->CI_Instance->xmlrpc->send_request())
			{
				error_log_shiksha("getAllSubscriptions::Failure====> ".$this->CI_Instance->xmlrpc->display_error());
			}
			else
			{
				$response = utility_decodeXmlRpcResponse($this->CI_Instance->xmlrpc->display_response());
				$this->eventCache->storeUpcomingEvents($category_id,$subcategory_id,$response);
				return $response;
			}
		}
	}
}