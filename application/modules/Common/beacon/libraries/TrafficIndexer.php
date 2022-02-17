<?php

require_once('vendor/autoload.php');

class TrafficIndexer
{
    private $elasticClient;
    
    function __construct($params)
    {
    	$this->CI = & get_instance();
		$clientParams = array();
		//error_log('env_xxxxx'.ENVIRONMENT);
		#if(ENVIRONMENT == 'test2') {
		if(in_array(ENVIRONMENT, array('test1', 'test2', 'test3', 'test4', 'test5','development'))) {
			//$clientParams['hosts'] = array('172.16.3.248');
			if($params['ESHost'] == "ES6"){
				$clientParams['hosts'] = array(SHIKSHA_ELASTIC_HOST);
			}else if($params['ESHost'] == "ES5"){
				$clientParams['hosts'] = array(ELASTIC_LDB_MONITORING_HOST);
			}else{
				$clientParams['hosts'] = array(SHIKSHA_ELASTIC_HOST);
			}
		}
		else if(ENVIRONMENT == 'production') {
			//$clientParams['hosts'] = array('172.10.16.72');
			if($params['ESHost'] == "ES6"){
				$clientParams['hosts'] = array(SHIKSHA_ELASTIC_HOST);
			}else if($params['ESHost'] == "ES5"){
				$clientParams['hosts'] = array(ELASTIC_LDB_MONITORING_HOST);
			}else{
				$clientParams['hosts'] = array(SHIKSHA_ELASTIC_HOST);
			}
		}
		
		if(empty($params['timeOutRequired'])){
			$clientParams['guzzleOptions']['curl.options'][CURLOPT_CONNECTTIMEOUT] = 7;
			$clientParams['guzzleOptions']['curl.options'][CURLOPT_TIMEOUT] = 7;
		}

		try {
        	$this->elasticClient = new Elasticsearch\Client($clientParams);
		}
		catch(Exception $e) {
			error_log("ElasticException:: ".$e->getMessage());
		}
    }
    
    function indexSession($sessionData)
    {
    	return;
		/*
	        $params = array();
	    
			$params['body'] = $sessionData;
			$params['index'] = 'trafficdata_sessions';
			$params['type'] = 'session';
			try {
				$this->elasticClient->index($params);
			}
			catch(Exception $e) {
	            error_log("ElasticException:: ".$e->getMessage());
	        }
		*/	
		
		$params = array();
    
		$params['body'] = $sessionData;
		$params['index'] = SESSION_INDEX_NAME;
		$params['type'] = 'session';
		try {
			$this->elasticClient->index($params);
		}
		catch(Exception $e) {
            error_log("ElasticException:: ".$e->getMessage());
        }
    }
    
    function indexPageView($pageViewData)
    {
    	return;
		/*
	        $params = array();
	    
			$params['body'] = $pageViewData;
			$params['index'] = 'trafficdata_pageviews';
			$params['type'] = 'pageview';
			try {
				$this->elasticClient->index($params);
			}
			catch(Exception $e) {
				error_log("ElasticException:: ".$e->getMessage());
			}
		*/	
		$params = array();
    
		$params['body'] = $pageViewData;
		$params['index'] = PAGEVIEW_INDEX_NAME;
		$params['type'] = 'pageview';
		try {
			$this->elasticClient->index($params);
		}
		catch(Exception $e) {
			error_log("ElasticException:: ".$e->getMessage());
		}
    }

    function bulkIndexSession($sessionData)
    {
		$indexParams = array();
        $indexParams['body'] = array();

    	foreach ($sessionData as $key => $doc) {
    		
    		$indexParams['body'][] = array('index' => array(
                                    '_index' => SESSION_INDEX_NAME,
                                    '_type' => 'session',
                                    )
                            );

    		$indexParams['body'][] = $doc;
    	}

		try {
			$this->elasticClient->bulk($indexParams);
		}
		catch(Exception $e) {
            error_log("ElasticException:: ".$e->getMessage());
            $ESVersion = $this->elasticClient->info();
			$ESVersion = $ESVersion['version']['number'];

            mail('praveen.singhal@99acres.com,romil.goel@shiksha.com', 'Error in bulkIndexSession Cron for ES version '.$ESVersion, print_r($e->getMessage(),true)."  ".print_r(json_encode($indexParams),true));
        }
    }

    function bulkIndexToRealTimeSession($sessionData)
    {
		$indexParams = array();
        $indexParams['body'] = array();

    	foreach ($sessionData as $key => $doc) {
    		
    		$indexParams['body'][] = array('index' => array(
                                    '_index' => "shiksha_trafficdata_sessions_realtime_".date('Y.m.d', strtotime('-'.(date('w')+1).' days')),
                                    '_type' => 'session',
                                    )
                            );

    		$indexParams['body'][] = $doc;
    	}

		try {
			$this->elasticClient->bulk($indexParams);
		}
		catch(Exception $e) {
            error_log("ElasticException:: ".$e->getMessage());
            $ESVersion = $this->elasticClient->info();
			$ESVersion = $ESVersion['version']['number'];

            mail('praveen.singhal@99acres.com,romil.goel@shiksha.com', 'Error in bulkIndexSession Cron for ES version '.$ESVersion, print_r($e->getMessage(),true)."  ".print_r(json_encode($indexParams),true));
        }
    }

    function bulkIndexPageView($pageViewData)
    {

		$indexParams = array();
        $indexParams['body'] = array();

    	foreach ($pageViewData as $key => $doc) {
    		
    		$indexParams['body'][] = array('index' => array(
                                    '_index' => PAGEVIEW_INDEX_NAME,
                                    '_type' => 'pageview',
                                    )
                            );

    		$indexParams['body'][] = $doc;
    	}

		try {
			$this->elasticClient->bulk($indexParams);
		}
		catch(Exception $e) {
			error_log("ElasticException:: ".$e->getMessage());
			mail('praveen.singhal@99acres.com,romil.goel@shiksha.com', 'Error in bulkIndexPageView Cron', print_r($e->getMessage(),true));
		}
    }

    function bulkIndexToRealTimePageView($pageViewData)
    {

		$indexParams = array();
        $indexParams['body'] = array();

    	foreach ($pageViewData as $key => $doc) {
    		
    		$indexParams['body'][] = array('index' => array(
                                    '_index' => "shiksha_trafficdata_pageviews_realtime_".date('Y.m.d', strtotime('-'.(date('w')+1).' days')),
                                    '_type' => 'pageview',
                                    )
                            );

    		$indexParams['body'][] = $doc;
    	}

		try {
			$this->elasticClient->bulk($indexParams);
		}
		catch(Exception $e) {
			error_log("ElasticException:: ".$e->getMessage());
			$ESVersion = $this->elasticClient->info();
			$ESVersion = $ESVersion['version']['number'];
			mail('praveen.singhal@99acres.com,romil.goel@shiksha.com', 'Error in bulkIndexPageView Cron  for ES version '.$ESVersion, print_r($e->getMessage(),true)."  ".print_r(json_encode($indexParams),true));
		}
    }



    function trackViewCount($page ='', $startDate, $endDate, $isStudyAbroad =''){
    	if(empty($page)){
    		return;
    	}

    	$viewCountData = array();
    	$groupField = 'pageEntityId';

		$params = array();
        $params['index'] = PAGEVIEW_INDEX_NAME_REALTIME_SEARCH;
        $params['type'] = 'pageview';
        $params['body']['size'] = 0;
		$filters = array();
		$filters[] = array('range' => array('visitTime' => array('gte' => $startDate,'lte' => $endDate)));
		if(!empty($isStudyAbroad)){
			$filters[] = array('term' => array('isStudyAbroad' => $isStudyAbroad));
		}

		if($page == 'examPage'){
			$groupField = 'groupId';
		}

		$filters[] = array('term' => array('pageIdentifier' => $page));	

		$params['body']['query']['bool']['filter']['bool']['must'] = $filters;
		$params['body']['aggs']['View Count']['terms'] = array('field' => $groupField,'size' => ELASTIC_AGGS_SIZE);

		//_p(json_encode($params));die;//_p($params);die;
		$this->CI->benchmark->mark('code_start');
		try {
			$result = $this->elasticClient->search($params);
		} catch (Exception $e) {
			mail('praveen.singhal@99acres.com,romil.goel@shiksha.com', 'updateViewCount Cron : Error While ES Query', print_r($e,true));
			return;
		}

		$this->CI->benchmark->mark('code_end');
		$ESQueryTime = $this->CI->benchmark->elapsed_time('code_start', 'code_end');

		if($ESQueryTime > 5){
			mail('praveen.singhal@99acres.com', 'updateViewCount Cron : ES taking more than 5 sec', 'TIme : '.$ESQueryTime.' Page'.$page.' Date'.$startDate);
		}

		if($result['hits']['total'] >0){
			$result = $result['aggregations']['View Count']['buckets'];
			$count = 0;
			foreach ($result as $key => $data) {
				$count +=$data['doc_count']; // temp
				$viewCountData[$data['key']] = $data['doc_count'];
			}
			return $viewCountData;
		}else{
			return false;	
		}
	}

	function indexChatHistory($chatdata)
    {
		$indexParams = array();
        $indexParams['body'] = array();

    	// foreach ($sessionData as $key => $doc) {
    		
    		$indexParams['body'][] = array('index' => array(
                                    '_index' => SHIKSHA_ASSISTANT_INDEX_NAME_REALTIME,
                                    '_type' => 'chat'
                                    )
                            );

    		$indexParams['body'][] = $chatdata;
    	// }

		try {
			$resp = $this->elasticClient->bulk($indexParams);
			error_log("============== resp : ".print_r($resp, true));
		}
		catch(Exception $e) {
            error_log("ElasticException:: ".$e->getMessage());
            mail('praveen.singhal@99acres.com,romil.goel@shiksha.com', 'Error in bulkIndexSession Cron', print_r($e->getMessage(),true));
        }
    }

    function indexABTestValue($data)
    {
		$indexParams = array();
        $indexParams['body'] = array();

    	// foreach ($sessionData as $key => $doc) {
    		
    		$indexParams['body'][] = array('index' => array(
                                    '_index' => SHIKSHA_ABTEST_INDEX_NAME,
                                    '_type' => 'abtest'
                                    )
                            );

    		$indexParams['body'][] = $data;
    	// }
		try {
			$resp = $this->elasticClient->bulk($indexParams);
			error_log("============== resp : ".print_r($resp, true));
		}
		catch(Exception $e) {
            error_log("ElasticException:: ".$e->getMessage());
            mail('praveen.singhal@99acres.com,romil.goel@shiksha.com', 'Error in bulkIndexSession Cron', print_r($e->getMessage(),true));
        }
    }
}
