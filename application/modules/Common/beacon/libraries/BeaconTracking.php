<?php


class BeaconTracking
{
    private $CI = null;
    private $beaconModel = null;
	private $indexer = null;
	private $cacheLib = null;
	
    function __construct()
    {
        $this->CI = &get_instance();
        
		$this->CI->load->model('beacon/beaconmodel');
        	$this->beaconModel = new Beaconmodel();
		
		$this->CI->load->library('cacheLib');
		$this->cacheLib = new cacheLib;
		
		$this->CI->load->library('beacon/TrafficIndexer');
		$this->indexer = new TrafficIndexer;
    }
    
    public function trackSession($userId, $pageIdentifier, $pageEntityId)
    {
		$t_start = microtime(TRUE);
		
		$visitorId = getVisitorId();
		$sessionId = getVisitorSessionId();
		
		$t_scheck_1 = microtime(TRUE);

		if((isset($_REQUEST['isApp']) && $_REQUEST['isApp'] == "yes") &&
                   isset($_REQUEST['visitorSessionId']) && !empty($_REQUEST['visitorSessionId'])
                ){
                  return;
                }else{
		    /**
		     * Check if this session has already been registerd
		     */
		    if($this->_sessionAlreadyRegistered($sessionId)) {
		            return;
		    }
		}
		$t_scheck_2 = microtime(TRUE);
		
        $clientIP = $this->_getClientIP();
        
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        //$landingPageURL = $_SERVER['HTTP_REFERER'];
		$landingPageURL = $_REQUEST['pageURL'];
        $pageReferer = $_REQUEST['pageReferer'];
        

        if((isset($_REQUEST['isApp']) && $_REQUEST['isApp'] == "yes") ||
			(isset($_REQUEST['AndroidSource']) && $_REQUEST['AndroidSource'] == "AndroidWebView")
		){
			$isMobile = "androidApp";
		}else{
			$isMobile = $this->_getIsMobile();
		}
		        
        $isMobile5 = $this->_getIsMobile5();
		$isStudyAbroad = $this->_getIsStudyAbroad($landingPageURL);
		$source = $this->_getSourceType($landingPageURL, $pageReferer);
		
		$landingPageParams = $this->_getURLParameters($landingPageURL);
		$utmSource = $landingPageParams['utm_source'];
		$utmMedium = $landingPageParams['utm_medium'];
		$utmCampaign = $landingPageParams['utm_campaign'];
		
		$t_vcheck_1 = microtime(TRUE);
		$prevSessionCount = $this->beaconModel->getVisitorSessionCount($visitorId);
		$t_vcheck_2 = microtime(TRUE);
		
		$sessionNumber = $prevSessionCount+1;

		// track seo search query
		if($pageReferer != ""){
			$seoSearchData = $this->getSeoSearchData($pageReferer);	
		}
		
		
		$sessionData = array(
			'sessionId' => $sessionId,
			'visitorId' => $visitorId,
			'sessionNumber' => $sessionNumber,
            'userId' => $userId,
            'landingPageURL' => $landingPageURL ? $landingPageURL : "",
            'referralURL' => $pageReferer ? $pageReferer : "",
            'clientIP' => $clientIP,
            'userAgent' => $userAgent ? $userAgent : "",
            'isMobile' => $isMobile,
            'isMobile5' => $isMobile5,
			'isStudyAbroad' => $isStudyAbroad,
			'utm_source' => $utmSource,
			'utm_medium' => $utmMedium,
			'utm_campaign' => $utmCampaign,
			'source' => $source,
            'startTime' => date('Y-m-d H:i:s'),
            'seo_search_engine' => isset($seoSearchData['seo_search_engine'])?$seoSearchData['seo_search_engine']:"",
            'seo_search_query' => isset($seoSearchData['seo_search_query'])?$seoSearchData['seo_search_query']:""
        );
        
		$t_insert_1 = microtime(TRUE);
        // $this->beaconModel->trackSession($sessionData);
        $sessionDBDataToLog = array("trackingtype" => "ViewcountTracking", "logType" => "SessionDB", "data" => json_encode($sessionData));
		$t_insert_2 = microtime(TRUE);
		
		/**
		 * Geo-location
		 */
		$t_geo_1 = microtime(TRUE);
		$geoLocation = $this->_getGeoLocation();
		$sessionData['geocity'] = $geoLocation['city'];
		$sessionData['geocountry'] = $geoLocation['country'];
		$t_geo_2 = microtime(TRUE);

		// add mmpId in case of pageIdentifier = MMP
		if($pageIdentifier == "MMP"){
			$sessionData['mmpId'] = $pageEntityId;
		}
		
		/**
		 * Index session in elasticsearch
		 */
		$t_es_1 = microtime(TRUE);
		$sessionData['startTime'] = str_replace(' ','T',$sessionData['startTime']);

		if($isMobile == 'yes'){
        	$deviceDetails = $this->_getDeviceDetails();
        	$sessionData['brand_name'] = $deviceDetails['brand_name'];
        	$sessionData['model_name'] = $deviceDetails['model_name'];
        	$sessionData['mobile_browser'] = $deviceDetails['mobile_browser'];
        }

        /*if(isset($_COOKIE['SAab'])){
        	$sessionData['SAabVarient'] = intval($_COOKIE['SAab']);
        }*/
        
		// $this->indexer->indexSession($sessionData);
		$sessionESDataToLog = array("trackingtype" => "ViewcountTracking", "logType" => "SessionElastic", "data" => json_encode($sessionData));
		$t_es_2 = microtime(TRUE);

		$this->_registerSession($sessionId);
		$t_end = microtime(TRUE);

		// log data to queue
		try{
	        // get rabbit mq client instance
	        $this->CI->config->load('amqp');
			$this->CI->load->library("common/jobserver/JobManagerFactory");
			$jobManager = JobManagerFactory::getClientInstance();

			$jobManager->addBackgroundJob("BeaconDataQueue", $sessionDBDataToLog);
			//$jobManager->addBackgroundJob("BeaconQueue", $sessionESDataToLog);
		}
		catch(Exception $e){
			error_log("JOBQException: ".$e->getMessage());
		}

		$currentDate = date("2018-11-20 15:22:22");
		if((strtotime($sessionData['startTime']) - strtotime($currentDate)) >=0){
			$sessionData['startTimeIST'] = $sessionData['startTime'];

			$sessionData['startTime'] = str_replace('T',' ',$sessionData['startTime']);
			$sessionData['timeIST'] = date("H:i:s",strtotime($sessionData['startTime']));

			$sessionData['startTime'] = convertDateISTtoUTC($sessionData['startTime']);

			$sessionData['time'] = date("H:i:s",strtotime($sessionData['startTime']));
			$sessionData['startTime'] = str_replace(' ','T',$sessionData['startTime']);

			$sessionESDataToLog = array("trackingtype" => "ViewcountTracking", "logType" => "SessionElastic", "data" => json_encode($sessionData));

			// log data to queue
			try{
		        // get rabbit mq client instance
		        $this->CI->config->load('amqp');
				$this->CI->load->library("common/jobserver/JobManagerFactory");
				$jobManager = JobManagerFactory::getClientInstance();
				$jobManager->addBackgroundJob("BeaconDataQueue", $sessionESDataToLog);
			}
			catch(Exception $e){
				error_log("JOBQException: ".$e->getMessage());
			}
		}
		
		$timeTaken = $t_end - $t_start;
		if($timeTaken >= 1) {
			error_log("BSessionTime: ".($t_scheck_2-$t_scheck_1)." -- ".($t_vcheck_2-$t_vcheck_1)." -- ".($t_insert_2-$t_insert_1)." -- ".($t_geo_2-$t_geo_1)." -- ".($t_es_2-$t_es_1));
		}

		// dumping session data in a log file for analytics purpose
		error_log(json_encode($sessionData)."\n", 3, '/data/app_logs/sessions_'.date("Ymd"));
    }
    
	private function _sessionAlreadyRegistered($sessionId)
	{
        //return $this->beaconModel->isSessionRegistered($sessionId);
        
		$val = $this->cacheLib->get('S_'.$sessionId);
		if($val == 1) {
			return TRUE;
		}
		else {
			return FALSE;
		}
            
	}
	
	private function _registerSession($sessionId)
	{
		$this->cacheLib->store('S_'.$sessionId, 1, 21600);
	}
	
	private function _getGeoLocation()
	{
		$geoCity = 'unknown';
		$geoCountry = 'unknown';

		if(trim($_SERVER['GEOIP_CITY_NAME'])) {
			$geoCity = trim($_SERVER['GEOIP_CITY_NAME']);
		}
		if(trim($_SERVER['GEOIP_COUNTRY_NAME'])) {
			$geoCountry = trim($_SERVER['GEOIP_COUNTRY_NAME']);
		}
		
		return array('city' => $geoCity, 'country' => $geoCountry);
	}
	
	private function _getClientIP()
	{
		$clientIP = getenv("HTTP_TRUE_CLIENT_IP")?getenv("HTTP_TRUE_CLIENT_IP"):(getenv("HTTP_X_FORWARDED_FOR")?getenv("HTTP_X_FORWARDED_FOR"):getenv("REMOTE_ADDR"));
		
		return $clientIP;
	}
	
	private function _getURLParameters($url)
	{
		$urlParts = parse_url($url);
		$queryString = $urlParts['query'];
		parse_str($queryString, $params);
		return $params;
	}
	
    public function trackPageView($userId, $pageIdentifier, $pageEntityId, $extraData)
    {
		$sessionId = getVisitorSessionId();
		
        //$referer = $_SERVER['HTTP_REFERER'];
		//$landingPageURL = $_SERVER['HTTP_REFERER'];
		
		$visitedPageURL = $_REQUEST['pageURL'];

		if((isset($_REQUEST['isApp']) && $_REQUEST['isApp'] == "yes") ||
			(isset($_REQUEST['AndroidSource']) && $_REQUEST['AndroidSource'] == "AndroidWebView")
		){
			$isMobile = "androidApp";
		}else{
			$isMobile = $this->_getIsMobile();
		}

        $isMobile5 = $this->_getIsMobile5();
        $isource = $this->_checkIfShikshaAssistant($visitedPageURL);
		$isStudyAbroad = $this->_getIsStudyAbroad($visitedPageURL);
		$isEnterprise = $this->_getIsEnterprise($visitedPageURL);
		if($isEnterprise == 'yes' && !$pageIdentifier) {
			$pageIdentifier = 'ShikshaEnterprise';
		}
		
		$extraData = $_REQUEST['extraData'];

		$uniqueId = number_format(microtime(TRUE), 4, '-','').'-'.rand(1000,9999);
		
		$pageViewData = array(
            'uniqId' => $uniqueId,
            'sessionId' => $sessionId,
            'userId' => $userId,
            'pageURL' => $visitedPageURL,
            'pageIdentifier' => $pageIdentifier,
            'pageEntityId' => $pageEntityId,
			'extraData' => $extraData,
			'isStudyAbroad' => $isStudyAbroad,
			'isMobile' => $isMobile,
			'isMobile5' => $isMobile5,
            'visitTime' => date('Y-m-d H:i:s')
        );
		
		/**
		 * Store pageview in database
		 */ 
		// $this->beaconModel->trackPageView($pageViewData);
		//$pageviewDBDataToLog = array("trackingtype" => "ViewcountTracking", "logType" => "PageviewDB", "data" => json_encode($pageViewData));
		
		/**
		 * Index pageview
		 * First un-pack extraData
		 */ 
		if($extraData) {
			$extraData = json_decode($extraData, TRUE);
			if(is_array($extraData) && count($extraData) > 0) {
				foreach($extraData as $extraDataKey => $extraDataValue) {
					if($extraDataKey == 'countryId') {
						if(is_array($extraDataValue)) {
							if(count($extraDataValue) == 1) {
								$pageViewData[$extraDataKey] = explode(',', $extraDataValue[0]);
							}
							else {
								$pageViewData[$extraDataKey] = $extraDataValue;
							}
						}
						else {
							$pageViewData[$extraDataKey] = explode(',', $extraDataValue);
						}
					}
					else {
						$pageViewData[$extraDataKey] = $extraDataValue;
					}
				}
			}
		}
		if($isource !== false){
			$pageViewData['isource'] = $isource;	
		}
        
		/**
		 * Geo-location
		 */ 
		$geoLocation = $this->_getGeoLocation();
		$pageViewData['geocity'] = $geoLocation['city'];
		$pageViewData['geocountry'] = $geoLocation['country'];
		
		/**
		 * Remove extraData blob from index
		 */
		unset($pageViewData['extraData']);
		$pageViewData['visitTime'] = str_replace(' ', 'T', $pageViewData['visitTime']);

                /**
                 * Add visitorId
                 */
                $visitorId = getVisitorId();
                $pageViewData['visitorId'] = $visitorId;

        if($isMobile == 'yes'){
        	$deviceDetails = $this->_getDeviceDetails();
        	$pageViewData['brand_name'] = $deviceDetails['brand_name'];
        	$pageViewData['model_name'] = $deviceDetails['model_name'];
        	$pageViewData['mobile_browser'] = $deviceDetails['mobile_browser'];
        }

		// $this->indexer->indexPageView($pageViewData);
        $pageviewESDataToLog = array("trackingtype" => "ViewcountTracking", "logType" => "PageviewElastic", "data" => json_encode($pageViewData));

        // log data to queue
		try{
	        // get rabbit mq client instance
	        $this->CI->config->load('amqp');
			$this->CI->load->library("common/jobserver/JobManagerFactory");
			$jobManager = JobManagerFactory::getClientInstance();

			//$jobManager->addBackgroundJob("BeaconDataQueue", $pageviewDBDataToLog);
			//$jobManager->addBackgroundJob("BeaconQueue", $pageviewESDataToLog);
		}
		catch(Exception $e){
			error_log("JOBQException: ".$e->getMessage());
		}

		$currentDate = date("2018-11-20 15:22:22");
		if((strtotime($pageViewData['visitTime']) - strtotime($currentDate)) >=0){
			$pageViewData['visitTimeIST'] = $pageViewData['visitTime'];
			
			$pageViewData['visitTime'] = str_replace('T',' ',$pageViewData['visitTime']);
			$pageViewData['timeIST'] = date("H:i:s",strtotime($pageViewData['visitTime']));

			$pageViewData['visitTime'] = convertDateISTtoUTC($pageViewData['visitTime']);

			$pageViewData['time'] = date("H:i:s",strtotime($pageViewData['visitTime']));
			$pageViewData['visitTime'] = str_replace(' ','T',$pageViewData['visitTime']);

			if((isset($_REQUEST['isApp']) && $_REQUEST['isApp'] == "yes")){
				$pageViewData['childPageIdentifier'] = $pageViewData['pageIdentifier'];
				$pageViewData['pageIdentifier'] = "androidApp";
			}

			if((!isset($pageViewData['childPageIdentifier']) || $pageViewData['childPageIdentifier'] == "") && $pageViewData['isStudyAbroad'] == "no"){
				error_log(json_encode($pageViewData)."\n", 3, '/data/app_logs/missingPageviewChildPage_'.date("Ymd"));
				$elasticCommonLib = $this->CI->load->library('trackingMIS/elasticSearch/elasticCommonLib');
        		$tempData = $elasticCommonLib->getPageIdentifierMapping($pageViewData['pageIdentifier'], $pageViewData['pageURL'],$pageViewData['childPageIdentifier']);
        		$pageViewData['pageIdentifier'] = $tempData['pageIdentifier'];
        		$pageViewData['childPageIdentifier'] = $tempData['childPageIdentifier'];
				
			}

			$pageviewESDataToLog = array("trackingtype" => "ViewcountTracking", "logType" => "PageviewElastic", "data" => json_encode($pageViewData));

			// log data to queue
			try{
		        // get rabbit mq client instance
		        $this->CI->config->load('amqp');
				$this->CI->load->library("common/jobserver/JobManagerFactory");
				$jobManager = JobManagerFactory::getClientInstance();
				
				$jobManager->addBackgroundJob("BeaconDataQueue", $pageviewESDataToLog);
			}
			catch(Exception $e){
				error_log("JOBQException: ".$e->getMessage());
			}
		}

		
		// dumping pageview data in a log file for analytics purpose
		error_log(json_encode($pageViewData)."\n", 3, '/data/app_logs/pageviews_'.date("Ymd"));
    }
	
	/**
	 * Check if accessed from mobile
	 */ 
	private function _getIsMobile()
	{
		global $flag_mobile_user_agent;
		
		$isMobile = 'no';
        if($_COOKIE['ci_mobile'] == 'mobile' || $flag_mobile_user_agent == 'mobile') {
            $isMobile = 'yes';
        }
		return $isMobile;
	}
	
	/**
	 * Check if current mobile device is HTML5 supported
	 */ 
	private function _getIsMobile5()
	{
		global $flag_mobile_js_support_user_agent;
		
		$isMobile5 = 'no';
        if($_COOKIE['ci_mobile_js_support'] == 'yes' || $flag_mobile_js_support_user_agent == 'yes') {
            $isMobile5 = 'yes';
        }
		return $isMobile5;
	}
	
	/**
	 * Check if it's a study abroad page
	 */ 
	private function _getIsStudyAbroad($url)
	{
		$parts = parse_url($url);
		$host = $parts['host'];
		$hostParts = explode('.',$host);
		return $hostParts[0] == 'studyabroad' ? 'yes' : 'no';
	}
	
	/**
	 * Check if it's a study abroad page
	 */ 
	private function _getIsEnterprise($url)
	{
		$parts = parse_url($url);
		$host = $parts['host'];
		$hostParts = explode('.',$host);
		return $hostParts[0] == 'enterprise' ? 'yes' : 'no';
	}

	private function _checkIfShikshaAssistant($url)
	{
		$parts = parse_url($url);
		parse_str($parts['query'], $searchQuery);
		if(isset($searchQuery['isource'])){
			return $searchQuery['isource'];
		}else{
			return false;
		}
	}
	
	private function _getSourceType($landingPageURL, $refererURL)
	{
		$sourceType = 'notsure';
		
		$landingPageParams = $this->_getURLParameters($landingPageURL);
		$utmSource = $landingPageParams['utm_source'];
		$utmMedium = $landingPageParams['utm_medium'];
		$utmCampaign = $landingPageParams['utm_campaign'];
	    $ppc = $landingPageParams['t_source'];
    
		if($ppc == 'paid') {
			$sourceType = 'paid';
		}
		else if($utmSource == 'shiksha' && $utmMedium == 'email') {
			$sourceType = 'mailer';
		}
		else if($this->_isFromSEO($refererURL)) {
			$sourceType = 'seo';
		}
		else if($this->_isFromSocial($refererURL)) {
			$sourceType = 'social';
		}
		else if(!trim($refererURL)) {
			$sourceType = 'direct';
		}
		
		return $sourceType;
	}
	
	function _isFromSEO($url)
	{
		$searchEngines = $this->_getSearhEngineList();
		
		if (!is_array($searchEngines)) {
			return FALSE;
		}
		
		$urlParts = parse_url($url);
		if (!is_array($urlParts) || !isset($urlParts['host'])) {
			return FALSE;
		}
		
		$host = $urlParts['host'];
		foreach ($searchEngines as $name => $prop) {
			if (FALSE !== stripos($host, $prop['host_pattern'])) {
				return TRUE;
			}
		}
		return FALSE;
	}
	
	function _isFromSocial($url)
	{
		$socialSites = $this->_getSocialSites();
		
		if (!is_array($socialSites)) {
			return FALSE;
		}
		
		$urlParts = parse_url($url);
		if (!is_array($urlParts) || !isset($urlParts['host'])) {
			return FALSE;
		}
		
		$host = $urlParts['host'];
		foreach ($socialSites as $name => $prop) {
			if (FALSE !== stripos($host, $prop['host_pattern'])) {
				return TRUE;
			}
		}
		return FALSE;
	}
	
	function _getSearhEngineList()
	{
		return array(
		  'google' => array(
			'host_pattern' => 'www.google.',
			'query_param' => 'q'
		  ),
		  'yahoo' => array(
			'host_pattern' => 'search.yahoo.com',
			'query_param' => 'p'
		  ),
		  'bing' => array(
			'host_pattern' => 'bing.com',
			'query_param' => 'q'
		  ),
		  'ask' => array(
			'host_pattern' => 'ask.com',
			'query_param' => 'q'
		  ),
		  'aol.de' => array(
			'host_pattern' => '.aol.de',
			'query_param' => 'q'
		  ),
		  'aol' => array(
			'host_pattern' => '.aol.',
			'query_param' => 'query'
		  ),
		  'altavista' => array(
			'host_pattern' => 'altavista.com',
			'query_param' => 'q'
		  ),
		  'alltheweb' => array(
			'host_pattern' => 'alltheweb.com',
			'query_param' => 'q'
		  ),
		);
	}
	
	function _getSocialSites()
	{
		return array(
		  'googleplus' => array(
			'host_pattern' => 'plus.google.',
			'query_param' => 'q'
		  ),
		  'facebook' => array(
			'host_pattern' => 'facebook.com',
			'query_param' => 'p'
		  ),
		  'twitter' => array(
			'host_pattern' => 'twitter.com',
			'query_param' => 'q'
		  ),
		  'quora' => array(
			'host_pattern' => 'quora.com',
			'query_param' => 'q'
		)
		);
	}

	function _getDeviceDetails(){
		$deviceCharacteristics = $_SERVER['HTTP_X_AKAMAI_DEVICE_CHARACTERISTICS'];
		$result =  explode(';', $deviceCharacteristics);
		foreach ($result as $key => $value) {
            $value = explode('=', $value);
            $deviceDetails[$value[0]] = $value[1];
        }
        return $deviceDetails;
	}

	function getSeoSearchData($pageReferer){
		$seoSearchData = array();

		if($pageReferer != ""){
			$pageReferer = urldecode($pageReferer);
            $parts = parse_url($pageReferer);
            parse_str($parts['query'], $searchQuery);

            if(!empty($searchQuery["q"])){
	            $seoSearchData['seo_search_query'] = strtolower($searchQuery[q]);
	            if(!(strpos($referralURL, "google.co") === false)){
	                $seoSearchData['seo_search_engine'] = 'google';
	            }else if(!(strpos($referralURL, "bing.com") === false)){
	                $seoSearchData['seo_search_engine'] = 'bing';
	            }else if(!(strpos($referralURL, "ask.com/web") === false)){
	                $seoSearchData['seo_search_engine'] = 'ask';
	            }else if(!(strpos($referralURL, "aol.com") === false)){
	                $seoSearchData['seo_search_engine'] = 'aol';
	            }else if(!(strpos($referralURL, "duckduckgo.com") === false)){
	                $seoSearchData['seo_search_engine'] = 'duckduckgo';
	            }
	        }else if(!empty($searchQuery["p"])){
	        	$seoSearchData['seo_search_query'] = strtolower($searchQuery[p]);
	            if(!(strpos($referralURL, "yahoo.com") === false)){
	                $seoSearchData['seo_search_engine'] = 'yahoo';
	            }
	        }
		}
		return $seoSearchData;
	}
}
