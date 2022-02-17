<?php

/*

Copyright 2007 Info Edge India Ltd

$Rev::               $:  Revision of last commit
$Author: manishz $:  Author of last commit
$Date: 2010/02/19 06:18:53 $:  Date of last commit

message_board_client.php makes call to server using XML RPC calls.
$Id: Message_board_client.php,v 1.73 2010/02/19 06:18:53 manishz Exp $: 
*/
class Message_board_client  {

	var $CI;
	var $CI_operation;
	var $cacheLib;
	function init($what='read')
	{
		$this->CI_operation = & get_instance();
		$this->CI = & get_instance();
		$this->CI->load->helper('url');
		$this->CI->load->library('xmlrpc');
		$server_url = MESSAGEBOARD_READ_SERVER;
		$server_port = MESSAGEBOARD_READ_SERVER_PORT;
		if($what=='write'){
	        $server_url = MESSAGEBOARD_SERVER;
		$server_port = MESSAGEBOARD_SERVER_PORT;
		}
		$this->CI->xmlrpc->set_debug(0);
		$this->CI->xmlrpc->server($server_url,$server_port );
		$this->CI->load->library('cacheLib');
		$this->cacheLib = new cacheLib();
	}

	function initSearch()
	{
		$this->CI =& get_instance();
		$this->CI->load->helper('url');
		$this->CI->load->library('xmlrpc');
		//$server_url = "http://172.16.0.20/codeigniter/searchCI";
		$server_url = MESSAGEBOARD_SEARCH_SERVER;		
		$this->CI->xmlrpc->set_debug(0);
		$this->CI->xmlrpc->server($server_url, MESSAGEBOARD_SEARCH_SERVER_PORT);	
	}
	

    //insert country for message boardid
	function updateCountry($threadCountryArray){
		$this->init('write');
		$this->CI->xmlrpc->method('updateCountry');
		$request = array(json_encode($threadCountryArray),'string'); 
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}	
	}

	//insert country for message boardid
	function addToEditorialBin($appId,$productId,$productType,$userId){
		$this->init('write');
		$this->CI->xmlrpc->method('addToEditorialBin');
		$request = array($appId,$productId,$productType,$userId); 
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}	
	}
	
	
	
	//post expert reply
	function postExpertReply($userId,$msgTxt,$threadId,$requestIP,$categoryList,$tags){
		$this->init('write');
		$this->CI->xmlrpc->method('postExpertReply');
		$request = array($userId,$msgTxt,$threadId,$requestIP,$categoryList,$tags); 
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{	
			return $this->CI->xmlrpc->display_response();
		}	
	
	}

        function tenDaysInactiveDiscussionAnnoucement($appId){
             $this->init('write');
		$this->CI->xmlrpc->method('tenDaysInactiveDiscussionAnnoucement');
		$request = array($appId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}
        }

        function tenDaysInactivityMailer($appId){
            $this->init();
		$this->CI->xmlrpc->method('tenDaysInactivityMailer');
		$request = array($appId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}
        }

        function getUserIdAboveAdvisor($appId){
            $this->init();
		$this->CI->xmlrpc->method('getUserIdAboveAdvisor');
		$request = array($appId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}
        }
	
        function getSomeDetailsForGoogleResults($IdArray){
                $this->init();
		$this->CI->xmlrpc->method('getSomeDetailsForGoogleResults');
		$request = array(array($IdArray,'struct'));
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}
        }
	/**
	*	get tree reply for a given topic id for a board id
	*/
	function getMsgTree($appId,$threadId,$start,$count,$giveMeQuestion = 0,$userId = 0,$userGroup = 'normal',$filter='upvotes',$pageTypeName,$referenceEntityId){

		$this->init();	
		$this->CI->xmlrpc->method('getMsgTree');
		$request = array($appId,$threadId,$start,$count,$giveMeQuestion,$userId,$userGroup,$filter,$pageTypeName,$referenceEntityId); 
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			//Modified for Shiksha performance task on 8 March
			//return $this->CI->xmlrpc->display_response();
			$response = ($this->CI->xmlrpc->display_response());
			$res = json_decode(gzuncompress(base64_decode($response)),true);
			return $res;
		}
	}
	function getDescriptionForQuestion($topicId){
                $this->init();
		$this->CI->xmlrpc->method('getDescriptionForQuestion');
		$request = array($topicId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}
        }
	/**
	*	Get the info about the threadIds ( viz. creationDate,displayName ,etc.)
	*/
	function getDataForRelatedQuestions($appId,$threadIdCsv){

		$this->init();	
		$this->CI->xmlrpc->method('getDataForRelatedQuestions');
		$request = array($appId,$threadIdCsv); 
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}
	}

	/**
	*	Get the info about the threadIds ( viz. creationDate,displayName ,etc.)
	*/
	function getInfoForThreads($appId,$threadIdCsv,$fieldsRequired=array(),$userId=0){
		$this->init();	
		$this->CI->xmlrpc->method('getInfoForThreads');
		$request = array(
						array($appId,'string'),
						array($threadIdCsv,'string'),
						array($fieldsRequired,'struct'),
						array($userId,'string'),
						'struct'
					);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}
	}

	/**
	*	get details of comment/answer.
	*/
	function getMsgDetails($appId,$msgId){

		$this->init();	
		$this->CI->xmlrpc->method('getMsgDetails');
		$request = array($appId,$msgId); 
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}
	
	}

	/*
	*	get details of comment/answer.
	*/
	function editMsgDetails($appId,$msgDetailsArray,$userId){

		$this->init('write');	
		$this->CI->xmlrpc->method('editMsgDetails');
		$request = array(
						array($appId,'int'),
						array($msgDetailsArray,'struct'),
						array($userId,'int'),
						'struct'
						);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}
	
	}

	function getMyTopics($appId,$categoryId,$uesrId,$start,$count,$countryId=1){
		$this->init();
		$this->CI->xmlrpc->method('getMyTopics');
		$request = array ($appId,$categoryId,$uesrId,$start,$count,$countryId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}
	}
	
	function getUserQuestions($appId,$uesrId,$start,$count,$flag='false',$loggedInUserId=0){
		$this->init();
		$this->CI->xmlrpc->method('getUserQuestions');
		$request = array ($appId,$uesrId,$start,$count,$flag,$loggedInUserId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}
	
	}

        function tuserReputationPreviousPointEntry($appId){ error_log('i m in client tuserReputationPreviousPointEntry');
            $this->init('write');
		$this->CI->xmlrpc->method('tuserReputationPreviousPointEntry');
		$request = array ($appId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}
        }
        
	function getUserAnswers($appId,$uesrId,$start,$count,$flag='false',$loggedUserId=0){
		$this->init();
		$this->CI->xmlrpc->method('getUserAnswers');
		$request = array ($appId,$uesrId,$start,$count,$flag,$loggedUserId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}
	
	}
	
	/*
	*	add a new topic (which is also a message) in a message table
	*/
	function addTopic($appId,$userId,$mesgTxt,$categoryList,$requestIp,$fromOthers='user',$listingTypeId=0,$listingType="",$toBeIndex = 1,$displayname="",$countryId=1,$extraParamCsv='',$courseId='',$trackingPageKeyId='',$source='desktop'){
		$this->init('write');	
		$this->CI->xmlrpc->method('addTopic');
		$request = array($appId,$userId,$mesgTxt,$categoryList,$requestIp,$fromOthers,$listingTypeId,$listingType,$toBeIndex,$displayname,$countryId,$extraParamCsv,$courseId,$trackingPageKeyId,$source);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			$response = $this->CI->xmlrpc->display_response();
			$this->cacheLib->clearCache('messageBoard');
			if(is_array($response) && isset($response['ThreadID'])){
			if($toBeIndex == 1)
			{	
				$this->indexMessageBoard($response['ThreadID']);
			}

			//Modified by Ankur on 8 March for Shiksha cafe performance. If the count is set, then add 1 in the count
			if(is_array($response) && isset($response['categoryID']) && $fromOthers=='user')
			{
				if(!($countryId==1 && $response['categoryID']==1)){
				    if($response['categoryID'] != '' && $response['categoryID'] < 20){
					$countKey = md5('getPostedTopicsCount'.$appId.$response['categoryID'].$countryId);
					if(($this->cacheLib->get($countKey)!='ERROR_READING_CACHE') && intval($this->cacheLib->get($countKey)) > 0){
					    $newCount = intval($this->cacheLib->get($countKey))+1;
					    $this->cacheLib->store($countKey,$newCount,1440000,'misc');
					}
				    }
				}
			}
			//Also, set the All Category - All country count key
			$countKey = md5('getPostedTopicsCount'.$appId.'11');
			if(($this->cacheLib->get($countKey)!='ERROR_READING_CACHE')  && intval($this->cacheLib->get($countKey)) > 0 && $fromOthers=='user'){
			    $newCount = intval($this->cacheLib->get($countKey))+1;
			    $this->cacheLib->store($countKey,$newCount,1440000,'misc');
			}
			//End modifications
			}
			return $response;
		}


	}

        function calViewAnswerComment($questionTitleArray,$topicId='',$bestAns = 'false',$googleSearch='false',$type='false'){
		$this->init('write');
		$this->CI->xmlrpc->method('calViewAnswerComment');
		$request = array(json_encode($questionTitleArray),$topicId,$bestAns,$googleSearch,$type);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			//Modified for Shiksha performance task on 8 March
			return $this->CI->xmlrpc->display_response();
			//$response = ($this->CI->xmlrpc->display_response());
			//$res = json_decode(gzuncompress(base64_decode($response)));
			//return $res;
		}
	}

        function updateTitle($appId,$userId,$msgId,$questionUserId,$msgTitle,$msgDescription){
                $this->init('write');
		$this->CI->xmlrpc->method('updateTitle');
		$request = array($appId,$userId,$msgId,$questionUserId,$msgTitle,$msgDescription);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			$response = $this->CI->xmlrpc->display_response();
			
			return $response;
		}

        }

        

        function checkInQuestionLog($appId,$msgId){
            $this->init('write');
		$this->CI->xmlrpc->method('checkInQuestionLog');
		$request = array($appId,$msgId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			$response = $this->CI->xmlrpc->display_response();
			$this->cacheLib->clearCache('messageBoard');
			return $response;
		}

        }
        
	function indexMessageBoard($threadId)
	{
		$this->init();
		error_log_shiksha("Entering IndexMessageBoard");
		$result = $this->getBoardForIndex(12,$threadId);
		if(!(is_array($result[0]) && isset($result[0]['creationDate']))){
			return;
		}
		$solrDate = $this->dateFormater($result[0]['creationDate']);
		if($result[0]['fromOthers']=='user')
		{
			if($result[0]['docBoost'])
				$request=array(array(array('countryList' => $result[0]['countryId'],'categoryList' => $result[0]['categoryList'],'title' => $result[0]['msgTxt'],'Id' => $result[0]['msgId'],'timestamp'=>$solrDate,'noOfViews'=>$result[0]['viewCount'],'noOfComments'=>$result[0]['msgCount'],'type' => 'question', 'answerText' => $result[0]['answer'], 'answerUserInfo'=> $result[0]['answerUserData'], 'questionUserInfo' => $result[0]['questionUserData'],'questionTime'=>$result[0]['creationDate'],'answerTime'=>$result[0]['answerTime'],'instituteName'=>$result[0]['instituteName'],'instituteLocation'=>$result[0]['location'],'docBoost'=>$result[0]['docBoost']),'struct'));
			else
				$request=array(array(array('countryList' => $result[0]['countryId'],'categoryList' => $result[0]['categoryList'],'title' => $result[0]['msgTxt'],'Id' => $result[0]['msgId'],'timestamp'=>$solrDate,'noOfViews'=>$result[0]['viewCount'],'noOfComments'=>$result[0]['msgCount'],'type' => 'question', 'answerText' => $result[0]['answer'], 'answerUserInfo'=> $result[0]['answerUserData'], 'questionUserInfo' => $result[0]['questionUserData'],'questionTime'=>$result[0]['creationDate'],'answerTime'=>$result[0]['answerTime'],'instituteName'=>$result[0]['instituteName'],'instituteLocation'=>$result[0]['location']),'struct'));


		}
		else
		{
			if($result[0]['docBoost'])
				$request=array(array(array('countryList' => $result[0]['countryId'],'categoryList' => $result[0]['categoryList'],'title' => $result[0]['msgTxt'],'Id' => $result[0]['msgId'],'timestamp' => $solrDate, 'noOfViews'=>$result[0]['viewCount'],'noOfComments'=>$result[0]['msgCount'], 'type' => 'discussion', 'answerText' => $result[0]['answer'], 'answerUserInfo'=> $result[0]['answerUserData'], 'questionUserInfo' => $result[0]['questionUserData'],'questionTime'=>$result[0]['creationDate'],'answerTime'=>$result[0]['answerTime'],'instituteName'=>$result[0]['instituteName'],'instituteLocation'=>$result[0]['location'],'docBoost'=>$result[0]['docBoost']),'struct'));
			else
				$request=array(array(array('countryList' => $result[0]['countryId'],'categoryList' => $result[0]['categoryList'],'title' => $result[0]['msgTxt'],'Id' => $result[0]['msgId'],'timestamp' => $solrDate, 'noOfViews'=>$result[0]['viewCount'],'noOfComments'=>$result[0]['msgCount'], 'type' => 'discussion', 'answerText' => $result[0]['answer'], 'answerUserInfo'=> $result[0]['answerUserData'], 'questionUserInfo' => $result[0]['questionUserData'],'questionTime'=>$result[0]['creationDate'],'answerTime'=>$result[0]['answerTime'],'instituteName'=>$result[0]['instituteName'],'instituteLocation'=>$result[0]['location']),'struct'));


		}
		$indexResult = $this->indexTopic(12,$request);
		//print_r($indexResult);
	}
	
	function dateFormater($date)
	{
		$datesp=explode(" ",$date);
		$newdate=$datesp[0]."T".$datesp[1]."Z";
		return $newdate;
	}
	/**
	*	post reply to a given message in a particular board
	*/
	function postReply($appId,$userId,$msgTxt,$threadId,$parentId,$requestIp,$fromOthers = 'user',$displayname,$mainAnswerId,$trackingPageKeyId=''){
		$this->init('write');	
		$this->CI->xmlrpc->method('postReply');
		$request = array($appId,$userId,$msgTxt,$threadId,$parentId,$requestIp,$fromOthers,$displayname,$mainAnswerId,$trackingPageKeyId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			$this->cacheLib->clearCache('messageBoard');
		
			if($fromOthers == 'discussion'){
				$indexingType = 'discussion';
			}else if($fromOthers == 'user'){
				$indexingType = 'question';
			}

			if($indexingType == "discussion" || $indexingType == "question"){
				modules::run('search/Indexer/addToQueue', $threadId, $indexingType, 'index');
			}
			return $this->CI->xmlrpc->display_response();
		}
	}
	
    function getPopularTopics($appId,$categoryId,$start,$count,$countryId=1,$userId = 0,$userGroup='normal',$showQuestionsWithNoAnswers=1){
		$doCache=0;
		//do cache only for initial records categoryid 1 and countryid 1.
		if(($categoryId<=20)&&($start<=80)&&(($countryId==1)||($countryId==2))){
		        $doCache=1;
		}
		$this->init();

		//make key
		$key = md5('getPopularTopics'.$appId.$categoryId.$start.$count.$countryId);
		
		if(($this->cacheLib->get($key)=='ERROR_READING_CACHE') || ($key == -1)){
			$this->CI->xmlrpc->method('getPopularTopics');
			$request = array($appId,$categoryId,$start,$count,$countryId,$userId,$userGroup,$showQuestionsWithNoAnswers);
			$this->CI->xmlrpc->request($request);
			if ( ! $this->CI->xmlrpc->send_request()){
				return $this->CI->xmlrpc->display_error();
			}else{
				$response = $this->CI->xmlrpc->display_response();
				//Modified for Shiksha performance task on 8 March
				$response = json_decode(gzuncompress(base64_decode($response)),true);
				if($doCache==1){
		                    $this->cacheLib->store($key,$response,14400,'messageBoard');
		                }
				return $response; 
			}
		}
		else{
			 return $this->cacheLib->get($key);
		}
	}
	
	function getUnansweredTopics($appId,$categoryId,$start,$count,$countryId=1){
		$doCache=0;
		//do cache only for initial records categoryid 1 and countryid 1.
		if(($categoryId<=20)&&($start<=80)&&(($countryId==1)||($countryId==2))){
		        $doCache=1;
		}
		$this->init();

		//make key
		$key = md5('getUnansweredTopics'.$appId.$categoryId.$start.$count.$countryId);
		
		if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
			$this->CI->xmlrpc->method('getUnansweredTopics');
			//Modified by Ankur on 8 March for Shiksha cafe performance
			$isSetCount = 'false';
			$countKey = md5('getUnansweredTopicsCount'.$appId.$categoryId.$countryId);
			if(($this->cacheLib->get($countKey)!='ERROR_READING_CACHE') && ($countKey != -1) && intval($this->cacheLib->get($countKey)) > 0 ){
			    $isSetCount = 'true';
			}
			$request = array($appId,$categoryId,$start,$count,$countryId,$isSetCount);
			//End modifications
			$this->CI->xmlrpc->request($request);
			if ( ! $this->CI->xmlrpc->send_request()){
				return $this->CI->xmlrpc->display_error();
			}else{
				$response = $this->CI->xmlrpc->display_response();
				//Modified for Shiksha performance task on 8 March
				if($isSetCount == 'false' && is_array($response) && isset($response[0]['totalCount'])){
					$this->cacheLib->store($countKey,$response[0]['totalCount'],604800,'misc');
				}
				else{
					$response[0]['totalCount'] = $this->cacheLib->get($countKey);
				}
				//End Modifications
				if($doCache==1){
		                    $this->cacheLib->store($key,$response,28800,'misc');
		                }
				return $response; 
			}
		}
		else{
			 return $this->cacheLib->get($key);
		}
	}


	function getRecentPostedTopics($appId,$categoryId,$start,$count,$countryId=1,$userId = 0,$userGroup='normal'){
		$doCache=0;
		//do cache only for initial records categoryid 1 and countryid 1.
		if(($categoryId<=20)&&($start<=80)&&(($countryId==1)||($countryId==2))){
		        $doCache=1;
		}
		$this->init();

		//make key
		$key = md5('getRecentPostedTopics'.$appId.$categoryId.$start.$count.$countryId);
		
		if(($this->cacheLib->get($key)=='ERROR_READING_CACHE') || ($key == -1)){
			$this->CI->xmlrpc->method('getRecentPostedTopics');
			
			//Modified by Ankur on 8 March for Shiksha cafe performance
			$isSetCount = 'false';
			$countKey = md5('getPostedTopicsCount'.$appId.$categoryId.$countryId);
			if(($this->cacheLib->get($countKey)!='ERROR_READING_CACHE') && ($countKey != -1) && intval($this->cacheLib->get($countKey)) > 0 ){
			    $isSetCount = 'true';
			}
			$request = array($appId,$categoryId,$start,$count,$countryId,$userId,$userGroup,$isSetCount);
			//End modifications
			$this->CI->xmlrpc->request($request);
			if ( ! $this->CI->xmlrpc->send_request()){
				return $this->CI->xmlrpc->display_error();
			}else{
				$response = $this->CI->xmlrpc->display_response();
				//Modified for Shiksha performance task on 8 March
				$response = json_decode(gzuncompress(base64_decode($response)),true);
				//If the count is not set, then set it in the Cache. Else, set the total count from the Cache
				if($isSetCount == 'false' && is_array($response) && isset($response[0]['totalCount'])){
					$this->cacheLib->store($countKey,$response[0]['totalCount'],1440000,'misc');
				}
				else{
					$response[0]['totalCount'] = $this->cacheLib->get($countKey);
				}
				//End Modifications
				if($doCache==1){
					$this->cacheLib->store($key,$response,14400,'messageBoard');
				}
				return $response; 
			}
		}
		else{
                	return $this->cacheLib->get($key);
        	}
	}

	
	/**
	*	Report Abuse for a given primary key in message table
	*/
	function reportAbuse($appId,$msgId,$userId){
		$this->init('write');	
		$this->CI->xmlrpc->method('reportAbuse');
		$request = array($appId,$msgId,$userId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}
		
	}
	
	/**
	*	Delete topic for a given primary key in message table
	*/
	function deleteTopic($appId,$msgId){
		$this->init('write');	
		$this->CI->load->library('listing_client');
		$this->CI->xmlrpc->method('deleteTopic');
		$request = array($appId,$msgId);
		$listingClient = new Listing_client();
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			$responseForDeleteMsgBrd =  $this->CI->xmlrpc->display_response();			
			$deleteIndexResponse = $listingClient->deleteListing($appId,'msgbrd',$msgId);
			$this->cacheLib->clearCache('messageBoard');
			//Modified by Ankur on 8 March for SHiksha cafe performance
			//After deleting or abusing a question, we will have to reduce the count of questions from Cache
			if(is_array($responseForDeleteMsgBrd) && isset($responseForDeleteMsgBrd['categoryId'])){
			    $categoryId = $responseForDeleteMsgBrd['categoryId'];
			    $countryId = $responseForDeleteMsgBrd['countryId'];
			    $fromOthers = $responseForDeleteMsgBrd['fromOthers'];
			    $countKey = md5('getPostedTopicsCount'.$appId.$categoryId.$countryId);
			    if(($this->cacheLib->get($countKey)!='ERROR_READING_CACHE')  && intval($this->cacheLib->get($countKey)) > 0 && $fromOthers=='user'){
				$newCount = intval($this->cacheLib->get($countKey))-1;
				$this->cacheLib->store($countKey,$newCount,1440000,'misc');
			    }
			    //Also, reduce the count for All Category - All Country 
			    $countKey = md5('getPostedTopicsCount'.$appId.'11');
			    if(($this->cacheLib->get($countKey)!='ERROR_READING_CACHE')  && intval($this->cacheLib->get($countKey)) > 0 && $fromOthers=='user'){
				$newCount = intval($this->cacheLib->get($countKey))-1;
				$this->cacheLib->store($countKey,$newCount,1440000,'misc');
			    }
			}
			//End Modifications by Ankur
			return $responseForDeleteMsgBrd;
		}
		
	}
	
	/**
	*	Delete topic from CMS for a given primary key in message table
	*/
	function deleteTopicFromCMS($appId,$msgId,$status='deleted'){
		$this->init('write');	
        	$this->CI->load->library('listing_client');
		$this->CI->xmlrpc->method('deleteTopicFromCMS');
		$request = array($appId,$msgId,$status);
        	$listingClient = new Listing_client();
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			$responseForDeleteMsgBrd =  $this->CI->xmlrpc->display_response();			
			$deleteIndexResponse = $listingClient->deleteListing($appId,'msgbrd',$msgId);
			$this->cacheLib->clearCache('messageBoard');
			//Modified by Ankur on 8 March for SHiksha cafe performance
			//After deleting or abusing a question, we will have to reduce the count of questions from Cache
			if(is_array($responseForDeleteMsgBrd) && isset($responseForDeleteMsgBrd['categoryId'])){
			    $categoryId = $responseForDeleteMsgBrd['categoryId'];
			    $countryId = $responseForDeleteMsgBrd['countryId'];
			    $fromOthers = $responseForDeleteMsgBrd['fromOthers'];
			    $countKey = md5('getPostedTopicsCount'.$appId.$categoryId.$countryId);
			    if(($this->cacheLib->get($countKey)!='ERROR_READING_CACHE')  && intval($this->cacheLib->get($countKey)) > 0 && $fromOthers=='user'){
				$newCount = intval($this->cacheLib->get($countKey))-1;
				$this->cacheLib->store($countKey,$newCount,1440000,'misc');
			    }
			    //Also, reduce the count for All Category - All Country 
			    $countKey = md5('getPostedTopicsCount'.$appId.'11');
			    if(($this->cacheLib->get($countKey)!='ERROR_READING_CACHE')  && intval($this->cacheLib->get($countKey)) > 0 && $fromOthers=='user'){
				$newCount = intval($this->cacheLib->get($countKey))-1;
				$this->cacheLib->store($countKey,$newCount,1440000,'misc');
			    }
			}
			//End Modifications by Ankur
			return $responseForDeleteMsgBrd;
		}
		
	}
	
	/**
	*	Delete comment from CMS for a given primary key in message table
	*/
	function deleteCommentFromCMS($appId,$msgId,$threadId,$userId,$status='deleted'){
		$this->init('write');	
		$this->CI->xmlrpc->method('deleteCommentFromCMS');
		$request = array($appId,$msgId,$threadId,$userId,$status);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			$this->cacheLib->clearCache('messageBoard');
			return $this->CI->xmlrpc->display_response();
		}
		
	}
	
	/**
	*	Close Discussion of topic for a given primary key in message table
	*/

        //to get reputation points

        function getUserReputationPoints($appId,$userId){
                $this->init('write');
		$this->CI->xmlrpc->method('getUserReputationPoints');
		$request = array($appId,$userId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}
        }
        ///
	function closeDiscussion($appId,$msgId,$userId=0){
		$this->init('write');	
		$this->CI->xmlrpc->method('closeDiscussion');
		$request = array($appId,$msgId,$userId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			$this->cacheLib->clearCache('messageBoard');
			modules::run('search/Indexer/addToQueue', $msgId, 'question');
			return $this->CI->xmlrpc->display_response();
		}
		
	}
	

	/**
        *       Return the last poster comment id
        */
	 function getDetailsForSearch($appId,$requestArray){
                $this->init();
                $this->CI->xmlrpc->method('getDetailsForSearch');
                $request=array(array($appId, 'int'),array($requestArray,'struct'));
                $this->CI->xmlrpc->request($request);
                if ( ! $this->CI->xmlrpc->send_request()){
                        return $this->CI->xmlrpc->display_error();
                }else{
			$this->cacheLib->clearCache('messageBoard');
                        return $this->CI->xmlrpc->display_response();
                }

        }
	
	/**
        *	Get the most contributing users
	*/
        function getMostContributingUser($appId,$count,$count1){
		$doCache=1;
		$start = 0;
		$this->init();
                if($this->cacheLib->get("expertUserCount")!='ERROR_READING_CACHE'){
			$expertUserCount = $this->cacheLib->get("expertUserCount");
			if($expertUserCount > $count1)
			{
				$randNum = rand(0,(int)($expertUserCount/$count1));
				$start = $randNum*$count1;
				if(($expertUserCount-($randNum*$count))<$count1)
				 	$start = $expertUserCount-$count1; 
				//simplification of $start = (($randNum-1)*$count)+($expertUserCount-($randNum*$count))
			}			
		}
		//make key
		$key = md5('getMostContributingUser'.$appId,$count.$start.$count1);
		if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
	                $this->CI->xmlrpc->method('getMostContributingUser');
	                $request=array(array($appId, 'int'),array($count,'int'),array($start,'int'),array($count1,'int'));
	                $this->CI->xmlrpc->request($request);
	                if ( ! $this->CI->xmlrpc->send_request()){
	                        return $this->CI->xmlrpc->display_error();
	                }else{
				$response = $this->CI->xmlrpc->display_response();
				$noOfExpertUsers = isset($response[0]['numOfExpertUsers'])?$response[0]['numOfExpertUsers']:0;
                                if($this->cacheLib->get("expertUserCount") == 'ERROR_READING_CACHE')
                                            $this->cacheLib->store('expertUserCount', $response['totalExpertCount']);
				if($doCache==1){
					$this->cacheLib->store($key,$response,86400,'misc');
				}
				return $response; 
	                }
		}
		else{
                	return $this->cacheLib->get($key);
        	} 

        }

	/**
        *	Get the most contributing users
	*/
        function getTopContributors($appId,$count,$weekly=1,$start=0,$tc=1,$tp=1,$catId=1){
		$doCache=1;
		$this->init();
		$key = md5('getTopContributors'.$appId.$count.$start.$weekly.$tc.$tp.$catId);
		if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
		  $this->CI->xmlrpc->method('getTopContributors');
		  $request=array(array($appId, 'int'),array($count,'int'),array($weekly,'int'),array($start,'int'),array($tc,'int'),array($tp,'int'),array($catId,'int'));
		  $this->CI->xmlrpc->request($request);
		  if ( ! $this->CI->xmlrpc->send_request()){
			  return $this->CI->xmlrpc->display_error();
		  }else{
			  $response = $this->CI->xmlrpc->display_response();
			  if($doCache==1){
				  $this->cacheLib->store($key,$response,259200,'misc');
			  }
			  return $response; 
		  }
		}
		else{
                	return $this->cacheLib->get($key);
        	} 

        }

	function indexTopic($appId,$request)
	{
		$this->initSearch();
		if(!(is_array($request[0]) && is_array($request[0][0]))){
			return;
		}
		if($request[0][0]['type'] == "discussion" || $request[0][0]['type'] == "question"){
			modules::run('search/Indexer/addToQueue', $request[0][0]['Id'], $request[0][0]['type']);
			return true;	
		}
//		$request[0][0]['uniqueId']='msgbrd'.$request[0][0]['Id'];
//		$this->CI->xmlrpc->method('indexMsgRecord');
//		$this->CI->xmlrpc->request($request);
//		if ( ! $this->CI->xmlrpc->send_request())
//		{
//			  return $this->CI->xmlrpc->display_error();
//		}
//		else
//		{
//        		  return $this->CI->xmlrpc->display_response();
//		}
	}


	function getBoardForIndex($appId,$threadId)
	{
		$this->init('write');
		$this->CI->xmlrpc->method('getBoardForIndex');
		$request = array ($appId,$threadId);
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

	/* Not in Use */
	/*
	function getTopicsForHomePage($appId,$categoryId,$keyValue,$start,$rows,$countryId=1) {
		$this->init();
        	$key=md5('getTopicsForHomePageS'. $appId.$categoryId.$keyValue.$start.$rows.$countryId);
	       	if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
		        $this->CI->xmlrpc->method('getTopicsForHomePageS');
			$request = array( $appId,$categoryId,$keyValue,$start,$rows,$countryId);
			$this->CI->xmlrpc->request($request);
			if ( ! $this->CI->xmlrpc->send_request()){
				return false;
			}
			else{
				$response = $this->CI->xmlrpc->display_response();
				$this->cacheLib->store($key,$response,14400,'messageBoard');
				return $response;
			}
	        }
		else{
				return  $this->cacheLib->get($key);
	        }
	}	
	

	function getTopicsForListing($appId,$categoryId,$listingTypeId,$listingType,$startFrom,$count,$countryId=1){
		$this->init();
		$this->CI->xmlrpc->method('getTopicsForListing');
		$request = array ($appId,$categoryId,$listingTypeId,$listingType,$startFrom,$count,$countryId);
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
	*/

	function updateTopic($appId,$msgId,$mesgTxt,$requestIp){
		$this->init('write');			
		
		$this->CI->xmlrpc->method('updateTopic');
		$request = array($appId,$msgId,$mesgTxt,$requestIp);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			$this->cacheLib->clearCache('messageBoard');	
			return $this->CI->xmlrpc->display_response();
		}
	}		
	
	function getNewReplyCount($appId,$categoryId,$userId,$countryId=1){		
		$this->init();			
		$this->CI->xmlrpc->method('getNewReplyCount');
		$request = array($appId,$categoryId,$userId,$countryId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}
	}
	
	function getNewReplyCountForQuestions($appId,$userId,$questionIds){		
		$this->init();			
		$this->CI->xmlrpc->method('getNewReplyCountForQuestions');
		$request = array($appId,$userId,$questionIds);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}
	}
		
    function showrelatedquestions($appId,$start,$count,$collegeId,$categories,$type)
	{
		$this->init();
		$this->CI->xmlrpc->method('getTopicsForListing');	
		$request = array (
				array($appId, 'int'),
				array($categories, 'string'),
				array($collegeId, 'int'),
				array($type, 'string'),
				array($start, 'int'),
				array($count, 'int'),
				'struct'			
				);
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
    function getTopicsForGroups($appId,$listingTypeId,$listingType,$startFrom,$count){
		$this->init('read');
		$this->CI->xmlrpc->method('getTopicsForGroups');
		$request = array($appId,$listingTypeId,$listingType,$startFrom,$count); 
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			
			return $this->CI->xmlrpc->display_response();
			
		}	
	
	}
	
	/**
		updateDigVal function which update the digcount of the answer/comment.
		urserId is logged in user id.msgId is answer/comment id.
		digValue 0-digdown 1-digup
		product is taken for reusability in other modules.
	*/
        function updateDigVal($appId,$userId,$msgId,$digVal,$product='qna',$pageType,$trackingPageKeyId,$isLoginFlow=FALSE){
		$this->init('write');
		$this->CI->xmlrpc->method('updateDigVal');
		if($isLoginFlow === TRUE){
			$isLoginFlow = 'true';
		}else{
			$isLoginFlow = 'false';
		}
		$request = array($appId,$userId,$msgId,$digVal,$product,$pageType,$trackingPageKeyId,$isLoginFlow);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}	
	}
	
	/**
		setBestAnsForThread function which choose the best answer for the question.
		urserId is logged in user id.msgId is answer/comment id.threadId is the id of question.
		commentuserId is Id of person who have posted the comment.
		doclose (1 = close and 0 = do not close) is flag which whether to close question or not.
	*/

/*
	function setBestAnsForThread($appId,$userId,$threadId,$msgId,$commentUserId,$doClose){
		$this->init('write');
		$this->CI->xmlrpc->method('setBestAnsForThread');
		$request = array($appId,$userId,$threadId,$msgId,$commentUserId,$doClose); 
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			$this->cacheLib->clearCache('messageBoard');
			return $this->CI->xmlrpc->display_response();
		}	
	}
*/	
	/**
		getQuestionFromQuestionCategories function which returns 
		the questions beloginging to list of categories 
		OR 
		catoegories in which question with msgId falls.
		msgId : Question Id
		categories : comma seperated list of categories.
		Either msgId is taken or categories.Both at a time is not needed.
	*/
	function getQuestionFromQuestionCategories($appId,$msgId=-1,$categories=-1,$start=0,$count=10){
		$this->init('read');
		$this->CI->xmlrpc->method('getQuestionFromQuestionCategories');
		$request = array($appId,$msgId,$categories,$start,$count); 
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}	
	}
	
	/**
	*	getQuestionAnswersForHome function which returns 
	*	flag has values question/answer/bestanswer
	*	countFlag if true returns count of questions,questions answered and bestanswers count.
	*/
        
        /* function getQuestionAnswersForHome($appId,$userId,$categoryId,$countryId,$start,$count,$flag,$countFlag,$myqnaTab=''){
		$doCache=0;
                $key = -1;
		//do cache only for initial records categoryid 1 and countryid 1.
		if(($categoryId<=20)&&($start<=80)&&(($countryId==1)||($countryId==2)) && $myqnaTab=='untitledQuestion'){
		        $doCache=1;
        		$key = md5('getQuestionAnswersForHome'.$appId.$categoryId.$start.$count.$countryId.$myqnaTab);
		}
		$this->init();

		if(($this->cacheLib->get($key)=='ERROR_READING_CACHE') || ($key == -1)){
			$this->CI->xmlrpc->method('getQuestionAnswersForHome');
			$request = array($appId,$userId,$categoryId,$countryId,$start,$count,$flag,$countFlag);
			$this->CI->xmlrpc->request($request);
			if ( ! $this->CI->xmlrpc->send_request()){
				return $this->CI->xmlrpc->display_error();
			}else{
				$response = $this->CI->xmlrpc->display_response();
				if($doCache==1){
					$this->cacheLib->store($key,$response,14400,'messageBoard');
				}
				return $response;
			}
		}
		else{
                	return $this->cacheLib->get($key);
        	}
	}
*/

function getQuestionAnswersForHome($appId,$userId,$categoryId,$countryId,$start,$count,$flag,$countFlag,$myqnaTab=''){
            $doCache=0;
            $key = -1;

            $this->init();

	    //Modified by Ankur on 1 June for Shiksha cafe performance
	    $isSetCount = 'false';
	    $countKey = md5('getUnTitledTopicsCount'.$appId.$userId.$categoryId.$countryId.$myqnaTab);
	    if(($this->cacheLib->get($countKey)!='ERROR_READING_CACHE') && ($countKey != -1) && intval($this->cacheLib->get($countKey)) > 0 ){
		$isSetCount = 'true';
	    }
            $request = array($appId,$userId,$categoryId,$countryId,$start,$count,$flag,$countFlag,$isSetCount);
	    //End modifications
            $this->CI->xmlrpc->method('getQuestionAnswersForHome');
            $this->CI->xmlrpc->request($request);
            if ( ! $this->CI->xmlrpc->send_request()){
                return $this->CI->xmlrpc->display_error();
            }else{
                $response = $this->CI->xmlrpc->display_response();
		//Modified for Shiksha performance task on 1 June
		if($isSetCount == 'false' && is_array($response) && isset($response[0]['totaluntitledQuestion']) && isset($response[0]['storeCountInCache']) ){
			$this->cacheLib->store($countKey,$response[0]['totaluntitledQuestion'],604800,'misc');
		}
		else{
			$response[0]['totaluntitledQuestion'] = $this->cacheLib->get($countKey);
		}
		//End Modifications
                return $response;
            }
       
    }
	/**
	*	close question for which the best answer is selected after three days.
	*/

	/*
	function closeQestionCron($appId){
		$this->init('write');
		$this->CI->xmlrpc->method('closeQestionCron');
		$request = array($appId); 
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}	
	}

*/
	/**
    * Operate on editorial bin. This web service basically add and remove from editorial bin.
	* Value for action is 'add' OR 'delete'
	*/
	function updateEditorialBin($appId,$productId,$productType,$userId,$action){
		$this->init('write');
		$this->CI->xmlrpc->method('updateEditorialBin');
		$request = array($appId,$productId,$productType,$userId,$action); 
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}	
	}

	/**
    * getQnAForEditorialBin function returns the questions in the editorial bin.
	*/
	function getQnAForEditorialBin($appId,$categoryId,$start,$count,$countryId=1,$userId = 0){
		$this->init();
		$this->CI->xmlrpc->method('getQnAForEditorialBin');
		$request = array($appId,$categoryId,$start,$count,$countryId,$userId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			$response = $this->CI->xmlrpc->display_response();
			return $response;
		}
	}

	/**
    * getQuestionForActivityLandingPages function returns the questions in the same/parent category.
	*/
	function getQuestionForActivityLandingPages($appId,$userId,$threadId,$start,$count,$noOfMaxAnswersPerQuestion = 4,$categoryId = -1){
		$this->init();
		$this->CI->xmlrpc->method('getQuestionForActivityLandingPages');
		$request = array($appId,$userId,$threadId,$start,$count,$noOfMaxAnswersPerQuestion,$categoryId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			$response = $this->CI->xmlrpc->display_response();
			return $response;
		}
	}

	/**
    * getLastQnAOfUser function returns the latest question/answer posted by the user.
	*/
	function getLastQnAOfUser($appId,$userId,$action){
		$this->init();
		$this->CI->xmlrpc->method('getLastQnAOfUser');
		$request = array($appId,$userId,$action);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			$response = $this->CI->xmlrpc->display_response();
			return $response;
		}
	}
	
	/**
    * getUserInfoForLeaderBaord function gives the user's answer and question count and user's level and points.
	*/
	function getUserInfoForLeaderBaord($appId,$userId){
		$this->init();
		$this->CI->xmlrpc->method('getUserInfoForLeaderBaord');
		$request = array($appId,$userId,$action);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			$response = $this->CI->xmlrpc->display_response();
			return $response;
		}
	}

	/**
    * getDataForInformationWidgetInAnA function gives the data for the information widget.
	*/
	function getDataForInformationWidgetInAnA($appId){
		$this->init();
		$key=md5('getDataForInformationWidgetInAnA'.$appId);
	    if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
			$this->CI->xmlrpc->method('getDataForInformationWidgetInAnA');
			$request = array($appId);
			$this->CI->xmlrpc->request($request);
			if ( ! $this->CI->xmlrpc->send_request()){
				return $this->CI->xmlrpc->display_error();
			}else{
				$response = $this->CI->xmlrpc->display_response();
				$this->cacheLib->store($key,$response,86400,'misc');
				return $response;
			}
		}else{
			return $this->cacheLib->get($key);
		}
	}

	/**
    * getEditorialQuestionsForHomePages function gives the data for the information widget.
	*/
	function getEditorialQuestionsForHomePages($appId,$categoryId=1,$countryId=1,$userId=0,$start=0,$count=5,$useRandom=0){
		$this->init();
		$key=md5('getEditorialQuestionsForHomePages'.$appId.$categoryId.$countryId.$start.$count);
	    if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
			$this->CI->xmlrpc->method('getEditorialQuestionsForHomePages');
			$request = array($appId,$categoryId,$countryId,$userId,$start,$count,$useRandom);
			$this->CI->xmlrpc->request($request);
			if ( ! $this->CI->xmlrpc->send_request()){
				return $this->CI->xmlrpc->display_error();
			}else{
				$response = $this->CI->xmlrpc->display_response();
				$this->cacheLib->store($key,$response,14400,'misc');
				return $response;
			}
		}else{
			return $this->cacheLib->get($key);
		}
	}

	function getAverageTimeForAswer($appId){
		$this->init();
		$key=md5('getAverageTimeForAswer'.$appId);
	    if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
            $this->cacheLib->store($key,'30',86400,'misc');
			$this->CI->xmlrpc->method('getAverageTimeForAswer');
			$request = array($appId);
			$this->CI->xmlrpc->request($request);
			if ( ! $this->CI->xmlrpc->send_request()){
				return $this->CI->xmlrpc->display_error();
			}else{
				$response = $this->CI->xmlrpc->display_response();
				$this->cacheLib->store($key,$response,86400,'misc');
				return $response;
			}
		}else{
			return $this->cacheLib->get($key);
		}
	}

	function getReportAbuseForm($appId,$category){
		$this->init();
		$this->CI->xmlrpc->method('getReportAbuseForm');
		$request = array($appId,$category);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			$response = $this->CI->xmlrpc->display_response();
			return $response;
		}
	}

	function getUserLevel($appId,$userId,$moduleName){
		$this->init();	
		$this->CI->xmlrpc->method('getUserLevel');
		$request = array($appId,$userId,$moduleName);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}
		
	}

	function setAbuseRecord($appId,$userId,$userLevel,$abuseRating,$msgId,$reasonIdList,$entityType,$topEntityId,$otherReason,$trackingPageKeyId){
		$this->init('write');	
		$this->CI->xmlrpc->method('setAbuseRecord');
		$request = array($appId,$userId,$userLevel,$abuseRating,$msgId,$reasonIdList,$entityType,$topEntityId,$otherReason,$trackingPageKeyId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}
		
	}

	/**
	*	Get user name and email to send the mail
	*/
	function getUserDetails($appId,$userId){
		$this->init();	
		$this->CI->xmlrpc->method('getUserDetails');
		$request = array($appId,$userId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}
		
	}

	/**
	*	Get message text for a thread ID
	*/
	function getMsgText($appId,$msgId,$type){
		$this->init();	
		$this->CI->xmlrpc->method('getMsgText');
		$request = array($appId,$msgId,$type);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}
		
	}

	/**
	*	Get name and email of all the users who reported abuse on entity
	*/
	function getAbuseUsersDetails($appId,$entityId,$entityType){
		$this->init();	
		$this->CI->xmlrpc->method('getAbuseUsersDetails');
		$request = array($appId,$entityId,$entityType);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}
		
	}

	/**
	*	Get name and email of all the users who reported abuse on entity
	*/
	function getUserFlag($appId,$userId, $userGroup, $threadIdList){
		$this->init();	
		$this->CI->xmlrpc->method('getUserFlag');
		$request = array($appId,$userId, $userGroup, $threadIdList);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}
		
	}

	/**
	*	Get details of the user V Card
	*/
	function getUserVCardDetails($appId,$userId,$form='',$status='Live'){
		$this->init();	
		$this->CI->xmlrpc->method('getUserVCardDetails');
		$request = array($appId,$userId,$form,$status);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}
		
	}

	/**
	*	Set details of the user V Card
	*/
	function setUserVCardDetails($appId,$userId,$name,$designation,$institute,$qualification,$imageURL,$blogURL,$brijjURL,$linkedInURL,$twitterURL,$youtubeURL,$aboutMe){
		$this->init('write');
		$this->CI->xmlrpc->method('setUserVCardDetails');
		$request = array($appId,$userId,$name,$designation,$institute,$qualification,$imageURL,$blogURL,$brijjURL,$linkedInURL,$twitterURL,$youtubeURL,$aboutMe);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}
		
	}

	/**
	*	Get details of the user V Card
	*/
	function getVCardStatus($appId,$userId){
		$this->init();	
		$this->CI->xmlrpc->method('getVCardStatus');
		$request = array($appId,$userId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}
		
	}

	/**
	*	Set details of the user V Card
	*/
	function setUserVCardParam($appId,$userId,$type,$value){
		$this->init('write');
		$this->CI->xmlrpc->method('setUserVCardParam');
		$request = array($appId,$userId,$type,$value);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}
		
	}

	function sendMailToAbusePeople($appId,$msgId){
		$this->init();
		$this->CI->xmlrpc->method('sendMailToAbusePeople');
		$request = array($appId,$msgId); 
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}	
	}
        
        function getDataUser($appId,$alluserId,$userId,$percentage=0){
		$this->init('write');
		$this->CI->xmlrpc->method('getDataUser');
		$request = array($appId,$alluserId,$userId,$percentage);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}
	}

	function getMailDataOnCommentPosting($appId,$answerId,$userId)
	{
		$this->init();
		$this->CI->xmlrpc->method('getMailDataOnCommentPosting');
		$request = array ($appId,$answerId,$userId);
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

	function getCategoryCountry($appId,$relatedQuesCsv){
		$this->init();
		$this->CI->xmlrpc->method('getCategoryCountry');
		$request = array($appId,$relatedQuesCsv); 
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}	
	}

	/**
	*	Get the data for A&A Wall
	*/
	function getWallData($appId,$userId=0,$start=0,$count=10,$categoryId=1,$countryId=1,$threadIdCsv='',$lastTimeStamp){
		$this->init();
		//Sanitize input parameters
                if($threadIdCsv==''){
                        $threadIdCsv = 0;
                }
		$this->CI->xmlrpc->method('getWallData');
		$request = array($appId,$userId,$start,$count,$categoryId,$countryId,$threadIdCsv,$lastTimeStamp); 
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			//Modified for Shiksha performance task on 8 March
			//return $this->CI->xmlrpc->display_response();
			$response = ($this->CI->xmlrpc->display_response());
			$res = json_decode(gzuncompress(base64_decode($response)),true);
			return $res;
		}	
	}

        function getWallDataForListings($appId=1,$userId=0,$start=0,$count=10,$categoryId=1,$countryId=1,$threadIdCsv='1',$lastTimeStamp,$questionIds,$type,$instituteId=0){
                $this->init();
		$this->CI->xmlrpc->method('getWallDataForListings');
		$request = array($appId,$userId,$start,$count,array($categoryId,'struct'),$countryId,$threadIdCsv,$lastTimeStamp,array($questionIds,'struct'),$type,$instituteId);
                $this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			//Modified for Shiksha performance task on 8 March
			//return $this->CI->xmlrpc->display_response();
			$response = ($this->CI->xmlrpc->display_response());
			$res = json_decode(gzuncompress(base64_decode($response)),true);
			return $res;
		}
        }

        function getTopAnswersWall($appId,$threadId,$start,$count,$displayAnswerId,$userId = 0,$userGroup = 'normal'){
		$this->init();
		$this->CI->xmlrpc->method('getTopAnswersWall');
		$request = array($appId,$threadId,$start,$count,$displayAnswerId,$userId,$userGroup); 
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}	
	}

	function getCommentSection($appId,$answerId,$userId){
		$this->init();
		$this->CI->xmlrpc->method('getCommentSection');
		$request = array($appId,$answerId,$userId); 
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}	
	}

	function getEntityComments($appId,$threadId,$startFrom,$count,$userId){
		$this->init();
		$this->CI->xmlrpc->method('getEntityComments');
		$request = array($appId,$threadId,$startFrom,$count,$userId); 
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
                        $response = $this->CI->xmlrpc->display_response();
                        $response = json_decode(gzuncompress(base64_decode($response)),true);
			return $response;
		}	
	}

	function showOtherRating($appId,$answerId,$shownUserId,$loggedUserId){
		$this->init();
		$this->CI->xmlrpc->method('showOtherRating');
		$request = array($appId,$answerId,$shownUserId,$loggedUserId); 
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}	
	}

	/**
	*	Get the data for A&A Profile Wall
	*/
	function getProfileData($appId,$userId=0,$start=0,$count=10,$threadIdCsv='',$lastTimeStamp,$type='',$viewedUserId,$product='user'){
		$this->init();
		$this->CI->xmlrpc->method('getProfileData');
		$request = array($appId,$userId,$start,$count,$threadIdCsv,$lastTimeStamp,$type,$viewedUserId,$product); 
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			//Modified for Shiksha performance task on 8 March
			//return $this->CI->xmlrpc->display_response();
			$response = ($this->CI->xmlrpc->display_response());
			$res = json_decode(gzuncompress(base64_decode($response)),true);
			return $res;
		}	
	}

	function getUserProfileDetails($appId,$viewedUserId){
		$this->init();
		$this->CI->xmlrpc->method('getUserProfileDetails');
		$request = array($appId,$viewedUserId); 
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}	
	}

	function getFollowUser($appId,$followingUserId, $followedUserId){
		$this->init();
		$this->CI->xmlrpc->method('getFollowUser');
		$request = array($appId,$followingUserId, $followedUserId); 
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			//Modified for Shiksha performance task on 8 March
			//return $this->CI->xmlrpc->display_response();
			$response = ($this->CI->xmlrpc->display_response());
			$res = json_decode(gzuncompress(base64_decode($response)),true);
			return $res;
		}	
	}

       function calculateRankByRepuationPoints($appId,$userId){
		$this->init();
                $appId=1;
		$this->CI->xmlrpc->method('calculateRankByRepuationPoints');
		$request = array($appId,$userId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}
	}


	function setFollowUser($appId,$followingUserId, $followedUserId){
		$this->init('write');
		$this->CI->xmlrpc->method('setFollowUser');
		$request = array($appId,$followingUserId, $followedUserId); 
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}	
	}

	function advisoryBoard($appId, $level, $start, $count){		
		$this->init();
		$key = md5('getAdvisoryBoard'.$appId.$level.$start.$count);
		if(($this->cacheLib->get($key)=='ERROR_READING_CACHE') || ($key == -1)){
			$this->CI->xmlrpc->method('advisoryBoard');
			$request = array($appId, $level, $start, $count);
			$this->CI->xmlrpc->request($request);
			if ( ! $this->CI->xmlrpc->send_request()){
				return $this->CI->xmlrpc->display_error();
			}else{
				$response = $this->CI->xmlrpc->display_response();
				$this->cacheLib->store($key,$response,86400,'misc');
				return $response; 
			}
		}
		else{
                	return $this->cacheLib->get($key);
        	}
	}

	function getBestAnswerMailData($appId,$threadId,$msgId,$commentUserId){		
		$this->init();
		$this->CI->xmlrpc->method('getBestAnswerMailData');
		$request = array($appId,$threadId,$msgId,$commentUserId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}
	}

	function getParentComments($appId,$msgId){		
		$this->init();
		$this->CI->xmlrpc->method('getParentComments');
		$request = array($appId,$msgId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}
	}

	function getHomepageData($appId,$entityType='discussion',$categoryId='1',$countryId='1',$start=0,$count=10,$userId=0,$orderBy=''){		
		$this->init();
		/*$this->CI->xmlrpc->method('getHomepageData');
		$request = array($appId,$entityType,$categoryId,$countryId,$start,$count,$userId,$orderBy);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			//Modified for Shiksha performance task on 8 March
			//return $this->CI->xmlrpc->display_response();
			$response = ($this->CI->xmlrpc->display_response());
			$res = json_decode(gzuncompress(base64_decode($response)),true);
			return $res;
		}*/

        $key=md5('getCafeHomepageData'.$appID.$entityType.$categoryId.$countryId.$start.$count);
        if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
            $this->CI->xmlrpc->method('getHomepageData');
            $request = array($appId,$entityType,$categoryId,$countryId,$start,$count,$userId,$orderBy);
            $this->CI->xmlrpc->request($request);
            if ( ! $this->CI->xmlrpc->send_request()){
                  return $this->CI->xmlrpc->display_error();
            }else{
                  $response = ($this->CI->xmlrpc->display_response());
                  $res = json_decode(gzuncompress(base64_decode($response)),true);
                  $this->cacheLib->store($key,$res,43200,'misc');
                  return $res;
            }
        }else{
            return  $this->cacheLib->get($key);
        }
	}

	function getTopicDetailForEdit($appId,$entityType='question',$topicId,$userId=0){
		$this->init();
		$this->CI->xmlrpc->method('getTopicDetailForEdit');
		$request = array($appId,$entityType,$topicId,$userId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}
	}

	function updateCafePost($appId,$editTopicId,$userId,$topicdesc,$selectedCategoryCsv,$requestIp,$fromOthers,$listingTypeId,$listingType,$isIndex,$displayname,$countryCsv,$otherParamCsv,$questionMoveToIns='off' ,$courseId='0',$instId='0',$isPaid='false',$questionMoveToCafe='off', $source='Desktop'){
		$this->init('write');
		$this->CI->xmlrpc->method('updateCafePost');

		//Ankur: Clearing cache for SEO URL
		if($fromOthers=='user' || $fromOthers=='question') $type = 'question';
		if($fromOthers=='discussion' || $fromOthers=='announcement') $type = 'discussion';
		$key = 'getSeoUrlNewSchema'.$editTopicId.$type;
		$this->cacheLib->clearCacheForKey($key);

		$request = array($appId,$editTopicId,$userId,$topicdesc,$selectedCategoryCsv,$requestIp,$fromOthers,$listingTypeId,$listingType,$isIndex,$displayname,$countryCsv,$otherParamCsv,$questionMoveToIns ,$courseId,$instId,$isPaid,$questionMoveToCafe,$source);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			if($fromOthers == 'user' || $fromOthers == 'question') {
				$indexingType = 'question';
			}
			if($fromOthers == 'discussion'){
				$indexingType = 'discussion';
			}
			if($indexingType == "question" || $indexingType == "discussion"){
				modules::run('search/Indexer/addToQueue', $editTopicId, $indexingType);	
			}
			return $this->CI->xmlrpc->display_response();
		}
	}

	function getCountCommentsToBeDisplayed($appId,$topicId){
		$this->init();
		$this->CI->xmlrpc->method('getCountCommentsToBeDisplayed');
		$request = array($appId,$topicId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}
	}

	/*
	function setFBSessionKey($appId,$userId, $sessionKey){
		$this->init('write');
		$this->CI->xmlrpc->method('setFBSessionKey');
		$request = array($appId,$userId, $sessionKey);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}
	}

	function getFBSessionKey($appId,$userId){
		$this->init();
		$this->CI->xmlrpc->method('getFBSessionKey');
		$request = array($appId,$userId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}
	}
	*/

	function setFBWallLog($appId,$userId, $sessionKey, $content, $ipAddress){
		$this->init('write');
		$this->CI->xmlrpc->method('setFBWallLog');
		$request = array($appId,$userId, $sessionKey, $content, $ipAddress);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}
	}

	function getDataForFacebook($appId,$userId,$type,$parentId=0,$mainAnswerId=0){
		$this->init();
		$this->CI->xmlrpc->method('getDataForFacebook');
		$request = array($appId,$userId,$type,$parentId,$mainAnswerId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			return $this->CI->xmlrpc->display_response();
		}
	}

	//Added by Ankur for Homepage-Rehash
	function getHomepageCafeWall($appId=1,$categoryId=1,$userId=0,$start=0,$count=10,$countryId=1){
		$this->init();
		//make key
		$key = md5('getHomepageCafeWall'.$appId.$categoryId.$start.$count,$countryId);
		if(($this->cacheLib->get($key)=='ERROR_READING_CACHE') || ($key == -1)){
			$this->CI->xmlrpc->method('getHomepageCafeWall');
			$request = array($appId,$categoryId,$userId,$start,$count,$countryId);
			$this->CI->xmlrpc->request($request);
			if ( ! $this->CI->xmlrpc->send_request()){
				return $this->CI->xmlrpc->display_error();
			}else{
				$response = $this->CI->xmlrpc->display_response();
				$response = json_decode(gzuncompress(base64_decode($response)),true);
				//Storing in the Cache for 2 hours
				$this->cacheLib->store($key,$response,7200,'misc');
				return $response; 
			}
		}
		else{
                	return $this->cacheLib->get($key);
        	}

	}

	function checkBestAnswer($Id){ 
  
		$this->init(); 
		$this->CI->xmlrpc->method('checkBestAnswer'); 
		$request = array($Id); 
		$this->CI->xmlrpc->request($request); 
		if ( ! $this->CI->xmlrpc->send_request()){ 
			return $this->CI->xmlrpc->display_error(); 
		}else{ 
			return $this->CI->xmlrpc->display_response(); 
		} 
	} 

	function getMasterListSitemap($appId, $count){
		$this->init(); 
		$this->CI->xmlrpc->method('getMasterListSitemap'); 
		$request = array($appId, $count); 
		$this->CI->xmlrpc->request($request); 
		if ( ! $this->CI->xmlrpc->send_request()){ 
			return $this->CI->xmlrpc->display_error(); 
		}else{ 
			$response = $this->CI->xmlrpc->display_response();
			$response = json_decode(gzuncompress(base64_decode($response)),true);
			return $response; 
		} 
	}

	function updateQnAMasterListTable($appId){
		$this->init('write'); 
		$this->CI->xmlrpc->method('updateQnAMasterListTable'); 
		$request = array($appId); 
		$this->CI->xmlrpc->request($request); 
		if ( ! $this->CI->xmlrpc->send_request()){ 
			return $this->CI->xmlrpc->display_error(); 
		}else{ 
			return $this->CI->xmlrpc->display_response();
		} 
	}
	
	function getExpertsTopContributors($appId,$count,$weekly=1,$start=0,$tc=1,$tp=1,$catId=1)
	{
		$doCache=1;
		$this->init();		
		$key = md5('getExpertTopContributors'.$appId.$count.$start.$weekly.$tc.$tp.$catId);
		if($this->cacheLib->get($key)=='ERROR_READING_CACHE')
		{ 
			$this->CI->xmlrpc->method('getTopContributors');
			$request=array(array($appId, 'int'),array($count,'int'),array($weekly,'int'),array($start,'int'),array($tc,'int'),array($tp,'int'),array($catId,'int'));
			$this->CI->xmlrpc->request($request);
			if(!$this->CI->xmlrpc->send_request())
			{	  
				return $this->CI->xmlrpc->display_error();
			}
			else
			{				
				$response = $this->CI->xmlrpc->display_response();			  
				if($doCache==1)
				{
					$this->cacheLib->store($key,$response,259200,'misc');
				}
				return $response; 
			}
		}
		else
		{
				return $this->cacheLib->get($key);
		}
	}

	function getUserInNetwork($appId,$userId){
		$this->init(); 
		$this->CI->xmlrpc->method('getUserInNetwork'); 
		$request = array($appId,$userId); 
		$this->CI->xmlrpc->request($request); 
		if ( ! $this->CI->xmlrpc->send_request()){ 
			return $this->CI->xmlrpc->display_error(); 
		}else{ 
			return $this->CI->xmlrpc->display_response();
		} 
	}

	function getMentionMailersData($appId,$newNameArr,$msgId,$userId,$type){
		$this->init(); 
		$this->CI->xmlrpc->method('getMentionMailersData'); 
		$request = array($appId,$newNameArr,$msgId,$userId,$type); 
		$this->CI->xmlrpc->request($request); 
		if ( ! $this->CI->xmlrpc->send_request()){ 
			return $this->CI->xmlrpc->display_error(); 
		}else{ 
			$response = $this->CI->xmlrpc->display_response();
			$response = json_decode(gzuncompress(base64_decode($response)),true);
			return $response; 
		} 
	}
	
	function linkQuestionResult($topicId){error_log("linkQuestionResult client");
                $this->init();
                $this->CI->xmlrpc->method('linkQuestionResult');
                $request = array($topicId);
                $this->CI->xmlrpc->request($request);
                if ( ! $this->CI->xmlrpc->send_request()){
                        return $this->CI->xmlrpc->display_error();
                }else{
                        $response = $this->CI->xmlrpc->display_response();
                       $response = json_decode(gzuncompress(base64_decode($response)),true);
                        return $response;
                }

        }

        function getRelatedSearchDiscussion($topicId='',$discussionText,$searchType='full'){error_log("getRelatedSearchDiscussion client".print_r($discussionText,true));
                $this->init();
                $this->CI->xmlrpc->method('getRelatedSearchDiscussion');
                $request = array($topicId,$discussionText,$searchType);
                $this->CI->xmlrpc->request($request);
                if ( ! $this->CI->xmlrpc->send_request()){
                        return $this->CI->xmlrpc->display_error();
                }else{
                        $response = $this->CI->xmlrpc->display_response();
                       $response = json_decode(gzuncompress(base64_decode($response)),true);
                        return $response;
                }

        }

        function checkForDiscussionStatus($topicId){
                $this->init();
                $this->CI->xmlrpc->method('checkForDiscussionStatus');
                $request = array($topicId);
                $this->CI->xmlrpc->request($request);
                if ( ! $this->CI->xmlrpc->send_request()){
                        return $this->CI->xmlrpc->display_error();
                }else{
                        $response = $this->CI->xmlrpc->display_response();
                       //$response = json_decode(gzuncompress(base64_decode($response)),true);
                        return $response;
                }
        }

        function getLinkedDiscussion($topicId){
                $this->init();
                $this->CI->xmlrpc->method('getLinkedDiscussion');
                $request = array($topicId);
                $this->CI->xmlrpc->request($request);
                if ( ! $this->CI->xmlrpc->send_request()){
                        return $this->CI->xmlrpc->display_error();
                }else{
                        $response = $this->CI->xmlrpc->display_response();
                        $response = json_decode(gzuncompress(base64_decode($response)),true);
                        return $response;
                }
        }
	
	function getRelatedDiscussions($categoryId,$subCategoryId,$countryId){
                $this->init();
                $key = md5('getRelatedDiscussions'.$categoryId.$subCategoryId.$countryId);
                $this->CI->xmlrpc->method('getRelatedDiscussions');
                $request = array($categoryId,$subCategoryId,$countryId);
                $this->CI->xmlrpc->request($request);
                if(($this->cacheLib->get($key)=='ERROR_READING_CACHE')){
                        if ( ! $this->CI->xmlrpc->send_request()){
                                return $this->CI->xmlrpc->display_error();
                        }else{
                                //$this->cacheLib->clearCache('messageBoard');
                                $response = $this->CI->xmlrpc->display_response();
                                $response = json_decode(gzuncompress(base64_decode($response)),true);
                                $this->cacheLib->store($key,$response,14400,'misc');
                                return $response;
                        }
                }else{
                        return $this->cacheLib->get($key);
                }
        }

	function setExpertData($appId,$data,$userId,$expertId=0){
		$this->init('write'); 
		$this->CI->xmlrpc->method('setExpertData'); 
		$request = array($appId,$data,$userId,$expertId); 
		$this->CI->xmlrpc->request($request); 
		if ( ! $this->CI->xmlrpc->send_request()){ 
			return $this->CI->xmlrpc->display_error(); 
		}else{ 
			return $this->CI->xmlrpc->display_response();
		} 
	}

	function getLatestPostedQuestions($appId,$categoryId,$start,$rows,$userId,$inputDate){
		$this->init();
		$this->CI->xmlrpc->method('getLatestPostedQuestions');
		
		$request = array($appId,$categoryId,$start,$rows,$userId,$inputDate);
		
		//End modifications
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return $this->CI->xmlrpc->display_error();
		}else{
			$response = $this->CI->xmlrpc->display_response();
			$response = json_decode(gzuncompress(base64_decode($response)),true);
			return $response; 
		}
	}


}
?>
