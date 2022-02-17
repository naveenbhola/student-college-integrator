<?php
class ShikshaModel extends CI_Model {
    var $CI;
    function __construct(){
        parent::__construct();
        $this->CI = & get_instance();
    }

    function getProductsCountsForCriteria($dbHandle, $criteria){
        $this->load->library('cacheLib');
        $cacheLib = new CacheLib();
        $key = md5('getProductsCountsForCriteria.', serialize($criteria));
        if($cacheLib->get($key)=='ERROR_READING_CACHE'){
		$this->CI->load->model('EventModel');
    		$this->CI->load->model('ArticleModel');
	    	$this->CI->load->model('QnAModel');
	    	$this->CI->load->model('ListingModel');
            $productsCount = array();
            $productsCount['totalEventCount'] = $this->CI->EventModel->getTotalEventCountForCriteria($dbHandle, $criteria);
		    $productsCount['totalArticleCount'] = $this->CI->ArticleModel->getTotalArticlesCountForCriteria($dbHandle, $criteria);
            $listingCriteria = $criteria;
            $listingCriteria['listingType'] = 'institute';
            $tempTotalInsCount = $this->CI->ListingModel->getTotalListingCountForCriteria($dbHandle, $listingCriteria);
            $productsCount['totalInstituteCount'] = $tempTotalInsCount['numInstitutes'];
            $listingCriteria['listingType'] = 'course';
            $tempTotalInsCount = $this->CI->ListingModel->getTotalListingCountForCriteria($dbHandle, $listingCriteria);
            $productsCount['totalCourseCount'] = $tempTotalInsCount['numCourses'];
            $listingCriteria['listingType'] = 'scholarship';
            $tempTotalInsCount = $this->CI->ListingModel->getTotalListingCountForCriteria($dbHandle, $listingCriteria);
            $productsCount['totalScholarshipCount'] = $tempTotalInsCount['numScholarships'];
 
 
           // $productsCount['totalQnACount'] = $this->QnAModel->getTotalQnACountForCriteria($dbhandle, $criteria);
            /*
            $productsCount['totalInstituteCount'] = $this->ListingModel->getTotalListingCountForCriteria($dbhandle, $criteria);
            $productsCount['totalCourseCount'] = $this->ListingModel->getTotalListingCountForCriteria($dbhandle, $criteria);
            $productsCount['totalScholarshipCount'] = $this->ListingModel->getTotalListingCountForCriteria($dbhandle, $criteria);
            */
            $cacheLib->store($key,$productsCount,120,'Shiksha');
        } else {
            return $cacheLib->get($key);
        } 
        //error_log("ASHISH::".print_r($productsCount, true));
        return $productsCount;
    }

    function insertmobileTracking($array)
    {
          $this->dbLibObj = DbLibCommon::getInstance("User");
          $dbHandle = $this->dbLibObj->getWriteHandle();
          $queryCmd = $dbHandle->insert_string('mobile_activity_log',$array);
          $query = $dbHandle->query($queryCmd);
    }

    function insertmobileTrackingForCSS($array)
    {
          $this->dbLibObj = DbLibCommon::getInstance("User");
          $dbHandle = $this->dbLibObj->getWriteHandle();
          $queryCmd = $dbHandle->insert_string('mobile_activity_log_css3',$array);
          $query = $dbHandle->query($queryCmd);
    }
    
    function shikshamobilesitereport($date)
    {
                    $finalResult  = array();
                    //$dateTom = strtotime("1 days",strtotime($date));
                    //$dateTom = date ( 'Y-m-j' , $dateTom );
		    $dateTom = date('Y-m-d 00:00:00');

                     $this->dbLibObj = DbLibCommon::getInstance("User");
                     $dbHandle = $this->dbLibObj->getReadHandle();

                    $queryCmd = "SELECT count(*)  as mobilerespcount FROM `tempLMSTable` WHERE listing_subscription_type='paid' and submit_date >  ? AND submit_date < ?  AND ACTION = 'mobilesite'";
                    error_log("mreport 1" . $queryCmd);
                    $queryRes =  $dbHandle->query($queryCmd, array($date,$dateTom) );
                    foreach ($queryRes->result_array() as $row){
                        $finalResult['mobilerespcount'] = $row['mobilerespcount'];
                    }
                    
                    //$queryCmd = "SELECT count(*)  as mobileresgcount  FROM `tusersourceInfo` WHERE time > '$date' AND time <  '$dateTom'  AND referer = 'mobile'";
		    $queryCmd = "SELECT count(*)  as mobileresgcount  FROM `tusersourceInfo` WHERE time > ? AND time < ?  AND referer = 'mobile' AND keyquery not like '%MOBILE_LDB%' ";
                    error_log("mreport 2" . $queryCmd);
                    $queryRes =   $dbHandle->query($queryCmd, array($date,$dateTom));
                    foreach ($queryRes->result_array() as $row){
                        $finalResult['mobileresgcount'] = $row['mobileresgcount'];
                    }  

                    $queryCmd = "SELECT count(*)  as question_post  FROM `mobile_activity_log` WHERE logged_at > ? AND logged_at < ?  AND activity_type = 'question_post'";
                    error_log("mreport 3" . $queryCmd);
                    $queryRes =   $dbHandle->query($queryCmd, array($date,$dateTom));
                    foreach ($queryRes->result_array() as $row){
                        $finalResult['question_post'] = $row['question_post'];
                    }  

                    $queryCmd = "SELECT count(*)  as ans_post  FROM `mobile_activity_log` WHERE logged_at > ? AND logged_at < ?  AND activity_type = 'answer_post'";
                    error_log("mreport 4" . $queryCmd);
                    $queryRes =   $dbHandle->query($queryCmd, array($date,$dateTom));
                    foreach ($queryRes->result_array() as $row){
                        $finalResult['ans_post'] = $row['ans_post'];
                    }  

                    $queryCmd = "SELECT count(*)  as visits  FROM `mobile_activity_log` WHERE logged_at > ? AND logged_at < ?  AND activity_type = 'request' and isHTML5Site=0";
                    error_log("mreport 5" . $queryCmd);
                    $queryRes =   $dbHandle->query($queryCmd, array($date,$dateTom));
                    foreach ($queryRes->result_array() as $row){
                        $finalResult['visits'] = $row['visits'];
                    }  

		    //Queries for Mobile HTML5 Site
                    $queryCmd = "SELECT count(*)  as mobilerespcount FROM `tempLMSTable` WHERE listing_subscription_type='paid' and submit_date > ? AND submit_date < ?  AND ACTION IN ('mobileHTML5','LP_MOB_Reco_ReqEbrochure','RANKING_MOB_Reco_ReqEbrochure','SEARCH_MOB_Reco_ReqEbrochure','CP_MOB_Reco_ReqEbrochure')";
                    error_log("mreport 1" . $queryCmd);
                    $queryRes =  $dbHandle->query($queryCmd, array($date,$dateTom));
                    foreach ($queryRes->result_array() as $row){
                        $finalResult['mobilerespcount5'] = $row['mobilerespcount'];
                    }


                    //Queries for Mobile HTML5 Site GETEB responses
                    $queryCmd = "SELECT count(*)  as mobilerespcount FROM `tempLMSTable` WHERE listing_subscription_type='paid' and submit_date > ? AND submit_date < ?  AND ACTION = 'mobileHTML5_GETEB'";
                    error_log("mreport 1" . $queryCmd);
                    $queryRes =  $dbHandle->query($queryCmd, array($date,$dateTom));
                    foreach ($queryRes->result_array() as $row){
                        $finalResult['mobilerespcount5_geteb'] = $row['mobilerespcount'];
                    }

                    //Queries for Mobile HTML5 Site FREE responses
                    $queryCmd = "SELECT count(*)  as mobilerespcount FROM `tempLMSTable` WHERE listing_subscription_type='free' and submit_date > ? AND submit_date < ?  AND ACTION IN ('mobileHTML5_GETEB','mobileHTML5','LP_MOB_Reco_ReqEbrochure','RANKING_MOB_Reco_ReqEbrochure','SEARCH_MOB_Reco_ReqEbrochure','CP_MOB_Reco_ReqEbrochure')";
                    error_log("mreport 1" . $queryCmd);
                    $queryRes =  $dbHandle->query($queryCmd, array($date,$dateTom));
                    foreach ($queryRes->result_array() as $row){
                        $finalResult['mobilerespcount5_free'] = $row['mobilerespcount'];
                    }
                    
                    $queryCmd = "SELECT count(*)  as mobileresgcount  FROM `tusersourceInfo` WHERE time > ? AND time < ?  AND keyquery like '%MOBILE_LDB%'";
                    error_log("mreport 2" . $queryCmd);
                    $queryRes =   $dbHandle->query($queryCmd, array($date,$dateTom));
                    foreach ($queryRes->result_array() as $row){
                        $finalResult['mobileresgcount5'] = $row['mobileresgcount'];
                    }  

                    $queryCmd = "SELECT count(*)  as visits  FROM `mobile_activity_log` WHERE logged_at > ? AND logged_at < ?  AND activity_type = 'request'  and isHTML5Site=1";
                    $queryRes =   $dbHandle->query($queryCmd, array($date,$dateTom));
                    foreach ($queryRes->result_array() as $row){
                        $finalResult['visits5'] = $row['visits'];
                    }  

                    return $finalResult;
    }

    function shikshamobileperformancereport($date){
                    $finalResult  = array();
                    //$dateTom = strtotime("1 days",strtotime($date));
                    //$dateTom = date ( 'Y-m-j' , $dateTom );
		    $dateTom = date('Y-m-d 00:00:00');

                     $this->dbLibObj = DbLibCommon::getInstance("User");
                     $dbHandle = $this->dbLibObj->getReadHandle();

                    $queryCmd = "select (select count(*) from mobile_activity_log where activity_type='request' and boomr_pageid='ana_detail' and logged_at > ? and logged_at < ?) calls, avg(server_p_time),avg(perceived_loadtime_page),avg(time_head_page_ready),avg(time_head_page_first_byte),avg(t_head),avg(t_body) from mobile_activity_log WHERE logged_at > ? and logged_at < ? and boomr_pageid='ana_detail' and perceived_loadtime_page>0 and perceived_loadtime_page<100000 and time_head_page_ready>0 and time_head_page_ready<100000 and t_head>0 and t_head<100000";
                    $queryRes =  $dbHandle->query($queryCmd, array($date,$dateTom,$date,$dateTom));
                    foreach ($queryRes->result_array() as $row){
                        $finalResult['ana_detail'] = $row;
                    }
                    
                    $queryCmd = "select (select count(*) from mobile_activity_log where activity_type='request' and boomr_pageid='ana_listing' and logged_at > ? and logged_at < ?) calls, avg(server_p_time),avg(perceived_loadtime_page),avg(time_head_page_ready),avg(time_head_page_first_byte),avg(t_head),avg(t_body) from mobile_activity_log WHERE logged_at > ? and logged_at < ? and boomr_pageid='ana_listing' and perceived_loadtime_page>0 and perceived_loadtime_page<100000 and time_head_page_ready>0 and time_head_page_ready<100000 and t_head>0 and t_head<100000";
                    $queryRes =   $dbHandle->query($queryCmd, array($date,$dateTom,$date,$dateTom));
                    foreach ($queryRes->result_array() as $row){
                        $finalResult['ana_listing'] = $row;
                    }  

                    $queryCmd = "select (select count(*) from mobile_activity_log where activity_type='request' and boomr_pageid='listing_detail' and logged_at > ? and logged_at < ? and isHTML5Site = 0) calls, avg(server_p_time),avg(perceived_loadtime_page),avg(time_head_page_ready),avg(time_head_page_first_byte),avg(t_head),avg(t_body) from mobile_activity_log WHERE logged_at > ? and logged_at < ? and boomr_pageid='listing_detail' and perceived_loadtime_page>0 and perceived_loadtime_page<100000 and time_head_page_ready>0 and time_head_page_ready<100000 and t_head>0 and t_head<100000 and isHTML5Site = 0";
                    $queryRes =   $dbHandle->query($queryCmd, array($date,$dateTom,$date,$dateTom));
                    foreach ($queryRes->result_array() as $row){
                        $finalResult['listing_detail'] = $row;
                    }  

                    $queryCmd = "select (select count(*) from mobile_activity_log where activity_type='request' and boomr_pageid='category_listing' and logged_at > ? and logged_at < ? and isHTML5Site = 0) calls, avg(server_p_time),avg(perceived_loadtime_page),avg(time_head_page_ready),avg(time_head_page_first_byte),avg(t_head),avg(t_body) from mobile_activity_log WHERE logged_at > ? and logged_at < ? and boomr_pageid='category_listing' and perceived_loadtime_page>0 and perceived_loadtime_page<100000 and time_head_page_ready>0 and time_head_page_ready<100000 and t_head>0 and t_head<100000 and isHTML5Site = 0";
                    $queryRes =   $dbHandle->query($queryCmd, array($date,$dateTom,$date,$dateTom));
                    foreach ($queryRes->result_array() as $row){
                        $finalResult['category_listing'] = $row;
                    }  

                    $queryCmd = "select (select count(*) from mobile_activity_log where activity_type='request' and boomr_pageid='home' and logged_at > ? and logged_at < ? and isHTML5Site = 0) calls, avg(server_p_time),avg(perceived_loadtime_page),avg(time_head_page_ready),avg(time_head_page_first_byte),avg(t_head),avg(t_body) from mobile_activity_log WHERE logged_at > ? and logged_at < ? and boomr_pageid='home' and perceived_loadtime_page>0 and perceived_loadtime_page<100000 and time_head_page_ready>0 and time_head_page_ready<100000 and t_head>0 and t_head<100000 and isHTML5Site = 0";
                    $queryRes =   $dbHandle->query($queryCmd, array($date,$dateTom,$date,$dateTom));
                    foreach ($queryRes->result_array() as $row){
                        $finalResult['home'] = $row;
                    }
		    
		    //Queries for Mobile HTML5 Site
                    $queryCmd = "select (select count(*) from mobile_activity_log where activity_type='request' and boomr_pageid IN ('listing_detail_institute','listing_detail_course') and logged_at > ? and logged_at < ? and isHTML5Site = 1) calls, avg(server_p_time),avg(perceived_loadtime_page),avg(time_head_page_ready),avg(time_head_page_first_byte),avg(t_head),avg(t_body) from mobile_activity_log WHERE logged_at > ? and logged_at < ? and boomr_pageid IN ('listing_detail_institute','listing_detail_course') and perceived_loadtime_page>0 and perceived_loadtime_page<100000 and time_head_page_ready>0 and time_head_page_ready<100000 and t_head>0 and t_head<100000 and isHTML5Site = 1";
                    $queryRes =   $dbHandle->query($queryCmd, array($date,$dateTom,$date,$dateTom));
                    foreach ($queryRes->result_array() as $row){
                        $finalResult['listing_detail5'] = $row;
                    }  

                    $queryCmd = "select (select count(*) from mobile_activity_log where activity_type='request' and boomr_pageid='category_listing' and logged_at > ? and logged_at < ? and isHTML5Site = 1) calls, avg(server_p_time),avg(perceived_loadtime_page),avg(time_head_page_ready),avg(time_head_page_first_byte),avg(t_head),avg(t_body) from mobile_activity_log WHERE logged_at > ? and logged_at < ? and boomr_pageid='category_listing' and perceived_loadtime_page>0 and perceived_loadtime_page<100000 and time_head_page_ready>0 and time_head_page_ready<100000 and t_head>0 and t_head<100000 and isHTML5Site = 1";
                    $queryRes =   $dbHandle->query($queryCmd, array($date,$dateTom,$date,$dateTom));
                    foreach ($queryRes->result_array() as $row){
                        $finalResult['category_listing5'] = $row;
                    }  

                    $queryCmd = "select (select count(*) from mobile_activity_log where activity_type='request' and boomr_pageid='home' and logged_at > ? and logged_at < ? and isHTML5Site = 1) calls, avg(server_p_time),avg(perceived_loadtime_page),avg(time_head_page_ready),avg(time_head_page_first_byte),avg(t_head),avg(t_body) from mobile_activity_log WHERE logged_at > ? and logged_at < ? and boomr_pageid='home' and perceived_loadtime_page>0 and perceived_loadtime_page<100000 and time_head_page_ready>0 and time_head_page_ready<100000 and t_head>0 and t_head<100000 and isHTML5Site = 1";
                    $queryRes =   $dbHandle->query($queryCmd, array($date,$dateTom,$date,$dateTom));
                    foreach ($queryRes->result_array() as $row){
                        $finalResult['home5'] = $row;
                    }  

                    $queryCmd = "select (select count(*) from mobile_activity_log where activity_type='request' and boomr_pageid='ranking_page' and logged_at > ? and logged_at < ? and isHTML5Site = 1) calls, avg(server_p_time),avg(perceived_loadtime_page),avg(time_head_page_ready),avg(time_head_page_first_byte),avg(t_head),avg(t_body) from mobile_activity_log WHERE logged_at > ? and logged_at < ? and boomr_pageid='ranking_page' and perceived_loadtime_page>0 and perceived_loadtime_page<100000 and time_head_page_ready>0 and time_head_page_ready<100000 and t_head>0 and t_head<100000 and isHTML5Site = 1";
                    $queryRes =   $dbHandle->query($queryCmd, array($date,$dateTom,$date,$dateTom));
                    foreach ($queryRes->result_array() as $row){
                        $finalResult['ranking5'] = $row;
                    }

		    
                    return $finalResult;	
    }

        //Function to Log Call activity on Mobile phone. This logging will be done for both Institute and Course pages.
        function insertCallTracking($arr){
            $this->dbLibObj = DbLibCommon::getInstance("User");
            $dbHandle = $this->dbLibObj->getWriteHandle();
            $queryCmd = $dbHandle->insert_string('mobile_call_activity_log',$arr);
            $query = $dbHandle->query($queryCmd);
        }

        //Function to Log Response activity on Mobile phone. This logging will be done for all the Pages
        function insertResponseTracking($arr){
            $this->dbLibObj = DbLibCommon::getInstance("User");
            $dbHandle = $this->dbLibObj->getWriteHandle();
	    if($arr['fromWhere']=='mobile_viewedListing' && $arr['userId']>0){
		//If the user has already been tracked for Viewed listing today, we will not make any further entry in the DB
		$queryCmd = "select * from mobile_response_activity_log where courseId = ? and userId = ? and fromWhere = 'mobile_viewedListing' and creationDate >= CURDATE() ";
		$queryRes = $dbHandle->query($queryCmd, array($arr['courseId'],$arr['userId']) );
		if($queryRes->num_rows() > 0){
			return false;
		}
	    }
            $queryCmd = $dbHandle->insert_string('mobile_response_activity_log',$arr);
            $query = $dbHandle->query($queryCmd);
        }
	
	
	
	function autoSuggestorTracking($arr){
	    $this->dbLibObj = DbLibCommon::getInstance("User");
            $dbHandle = $this->dbLibObj->getWriteHandle();
    	    $queryCmd = $dbHandle->insert_string('mobile_search_suggestion_log',$arr);
            $query = $dbHandle->query($queryCmd);    	    
	}

    function insertCachePurgingQueue($arr){
        $this->dbLibObj = DbLibCommon::getInstance("User");
        $dbHandle = $this->dbLibObj->getWriteHandle();
            $arr['added_time'] = date('Y-m-d H:i:s');
        $queryCmd = $dbHandle->insert_string('nginx_cache_purge_log',$arr);
        $query = $dbHandle->query($queryCmd);
    }

    function insertMultipleCachePurgingQueue($arr){
        if(!empty($arr)){
            $this->dbLibObj = DbLibCommon::getInstance("User");
            $dbHandle = $this->dbLibObj->getWriteHandle();
            $dbHandle->insert_batch('nginx_cache_purge_log',$arr);
        }
    }


}
