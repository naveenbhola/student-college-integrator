<?php

require_once('vendor/autoload.php');

class ElasticSearchCron extends MX_Controller
{
	private $client;

	function __construct()
	{
		$clientParams = array();
        	//$clientParams['hosts'] = array('172.10.16.71', '172.10.16.72', '172.10.16.82');
		//$clientParams['hosts'] = array('172.10.16.72');
		$clientParams['hosts'] = array(ELASTIC_SEARCH_HOST);
        	//$this->client = new Elasticsearch\Client($clientParams);	
	}

	function updateSessions()
	{
		$this->validateCron();
		$this->updateSessionsForIndex(SESSION_INDEX_NAME, PAGEVIEW_INDEX_NAME);
	}

	function updateESSessions(){
		$this->validateCron();
		$this->updateSessionsForShikshaESIndex(SESSION_INDEX_NAME, PAGEVIEW_INDEX_NAME_REALTIME_SEARCH);
	}

	function updateSessionsForShikshaESIndex($sessionIndexName, $pageviewIndexName)
	{
		ini_set('memory_limit','4906M');
		error_log("hello");

		$ESConnLib = $this->load->library("trackingMIS/elasticSearch/ESConnectionLib");
    	$client = $ESConnLib->getShikshaESServerConnection();

		/**
 		 * Get unprocessed sessions
 		 */
		$params = array();
		$params['index'] = $sessionIndexName;
		$params['type'] = 'session';

		/**
 		 * Look back max 2 days duration
 		 */
		$startDateFilter = array();
		$startDate = date('Y-m-d', strtotime('-2 day')).' 00:00:00';
		$startDate = convertDateISTtoUTC($startDate);
		$startDate = str_replace(" ", "T", $startDate);
		$startDateFilter['range']['startTime']['gte'] = $startDate;

		/**
 		 * For unprocessed, we check whether duration field exists or missing
 		 * If duration field exists, it has been processed, else it's unprocessed
 		 */
		$unprocessedFilter = array();
		$unprocessedFilter['exists']['field'] = 'duration';

		$params['body']['query']['bool']['filter']['bool']['must'][] = $startDateFilter;
		$params['body']['query']['bool']['filter']['bool']['must_not'][] = $unprocessedFilter;
		$params['body']['_source'] = array('sessionId');
		$params['body']['size'] = 500000;
		//_p(json_encode($params));die;
		$trackChunkTimeT1 = time();
		$search = $client->search($params);
		$trackChunkTimeT2 = time();
		error_log(" ES query : ".print_r(json_encode($params),true)."\n", 3, "/data/app_logs/sessionUpdateIndexing.log_".date('Y-m-d'));
		error_log(" Total time".($trackChunkTimeT2-$trackChunkTimeT1)."\n", 3, "/data/app_logs/sessionUpdateIndexing.log_".date('Y-m-d'));

		$sessionIds = array();
		$sessionMapping = array();
		$sessionToIndexMapping = array();
		foreach($search['hits']['hits'] as $result) {
        		$sessionId = $result['_source']['sessionId'];
        		$sessionDocumentId = $result['_id'];
        		$sessionIds[] = $sessionId;
        		$sessionMapping[$sessionId] = $sessionDocumentId;
        		$sessionToIndexMapping[$result['_index']][] = $sessionId;
		}

		foreach ($sessionToIndexMapping as $indexNameToUpdateData => $sessionDataToUpdate) {
			/**
			 * Process in batches
			 */
			//$numSessions = count($sessionIds);
			$numSessions = count($sessionDataToUpdate);
			$batchSize = 100;
			for($i=0;$i<$numSessions;$i+=$batchSize) {
				error_log("START:: ".$i." :: ".time()."\n", 3, "/data/app_logs/sessionUpdateIndexing.log_".date('Y-m-d'));
				//$slice = array_slice($sessionIds, $i, $batchSize);
				$slice = array_slice($sessionDataToUpdate, $i, $batchSize);
				$params = array();
				$params['index'] = $pageviewIndexName;
				$params['type'] = 'pageview';
				$params['body']['size'] = 1000000;
				$params['body']['sort']['visitTime']['order'] = 'asc';
				$sessionFilter = array();
				$sessionFilter['terms']['sessionId'] = $slice;
				$params['body']['query']['bool']['filter']['bool']['must'][] = $sessionFilter;
				$search = $client->search($params);
				$results = $search['hits']['hits'];
			
				$data = array();
				foreach($results as $result) {
					$sessionId =  $result['_source']['sessionId'];
					$visitTime = strtotime($result['_source']['visitTime']);
				
					if(array_key_exists($sessionId, $data)) {
						$data[$sessionId]['exitPage'] = $result['_source'];
						$data[$sessionId]['bounce'] = 0;
						$data[$sessionId]['duration'] += ($visitTime - $data[$sessionId]['lastVisitTime']);
						$data[$sessionId]['pageviews'] += 1;
						$data[$sessionId]['lastVisitTime'] = $visitTime;
					}
					else {
						$data[$sessionId] = array();
						$data[$sessionId]['landingPage'] = $result['_source'];
						$data[$sessionId]['exitPage'] = $result['_source'];
						$data[$sessionId]['bounce'] = 1;
						$data[$sessionId]['duration'] = 0;
						$data[$sessionId]['pageviews'] = 1;
						$data[$sessionId]['lastVisitTime'] = $visitTime;
					}

					if(isset($result['_source']['isource'])){
						$data[$sessionId]['isource'] = $result['_source']['isource'];
						if(isset($data[$sessionId]['isourcePageViewCount'])){
							$data[$sessionId]['isourcePageViewCount']  += 1;
						}else{
							$data[$sessionId]['isourcePageViewCount']  = 1;
						}
					}
				}
			
				/**
				 * Update session documents with extra info
				 */
				$params = array();
				//$params['index'] = $sessionIndexName;
				$params['index'] = $indexNameToUpdateData;
				$params['type'] = 'session';
				$currentTime = date("Y-m-d H:i:s");
				$currentTime = convertDateISTtoUTC($currentTime);
				$currentTime = strtotime($currentTime);
			
				foreach($data as $sessionId => $sessionData) {
					$sessionLastVisitTime = $sessionData['lastVisitTime'];
					$timeElapsed = $currentTime - $sessionLastVisitTime;
					
					/**
					 * Update only if session has expired
					 */
					if($timeElapsed >= 2700) {
						$sessionDocument = array();
						$sessionDocument['landingPageDoc'] = $sessionData['landingPage'];
						$sessionDocument['exitPage'] = $sessionData['exitPage'];
						$sessionDocument['bounce'] = intval($sessionData['bounce']);
						$sessionDocument['duration'] = intval($sessionData['duration']);
						$sessionDocument['pageviews'] = intval($sessionData['pageviews']);

						if(isset($sessionData['isource'])){
							$sessionDocument['isource']      = $sessionData['isource'];
							$sessionDocument['isourcePageViewCount'] = $sessionData['isourcePageViewCount'];
						}
				
						$sessionDocumentId = $sessionMapping[$sessionId];
						$params['id'] = $sessionDocumentId;
						$params['body']['doc'] = $sessionDocument;
						$client->update($params);
					}
				}
			}
		}		
	}
	
	function updateSessionsForIndex($sessionIndexName, $pageviewIndexName)
	{
		ini_set('memory_limit','4906M');
		error_log("hello");

		/**
 		 * Get unprocessed sessions
 		 */
		$params = array();
		$params['index'] = $sessionIndexName;
		$params['type'] = 'session';

		/**
 		 * Look back max 2 days duration
 		 */
		$startDateFilter = array();
		$startDateFilter['range']['startTime']['gte'] = date('Y-m-d', strtotime('-2 day')).'T00:00:00';

		/**
 		 * For unprocessed, we check whether duration field exists or missing
 		 * If duration field exists, it has been processed, else it's unprocessed
 		 */
		$unprocessedFilter = array();
		$unprocessedFilter['missing']['field'] = 'duration';

		$params['body']['query']['filtered']['filter']['bool']['must'][] = $startDateFilter;
		$params['body']['query']['filtered']['filter']['bool']['must'][] = $unprocessedFilter;
		$params['body']['fields'] = array('sessionId');
		$params['body']['size'] = 500000;

		$search = $this->client->search($params);

		$sessionIds = array();
		$sessionMapping = array();
		foreach($search['hits']['hits'] as $result) {
        		$sessionId = $result['fields']['sessionId'][0];
        		$sessionDocumentId = $result['_id'];
        		$sessionIds[] = $sessionId;
        		$sessionMapping[$sessionId] = $sessionDocumentId;
		}

		error_log(count($sessionIds));

		/**
		 * Process in batches
		 */
		$numSessions = count($sessionIds);
		$batchSize = 100;
		for($i=0;$i<$numSessions;$i+=$batchSize) {
			error_log("START:: ".$i."\n");
			$slice = array_slice($sessionIds, $i, $batchSize);
		
			$params = array();
			$params['index'] = $pageviewIndexName;
			$params['type'] = 'pageview';
			$params['body']['size'] = 1000000;
			$params['body']['sort']['visitTime']['order'] = 'asc';
		
			$sessionFilter = array();
			$sessionFilter['terms']['sessionId'] = $slice;
			$params['body']['query']['filtered']['filter']['bool']['must'][] = $sessionFilter;
		
			$search = $this->client->search($params);
			$results = $search['hits']['hits'];
		
			$data = array();
			foreach($results as $result) {
				$sessionId =  $result['_source']['sessionId'];
				$visitTime = strtotime($result['_source']['visitTime']);
			
				if(array_key_exists($sessionId, $data)) {
					$data[$sessionId]['exitPage'] = $result['_source'];
					$data[$sessionId]['bounce'] = 0;
					$data[$sessionId]['duration'] += ($visitTime - $data[$sessionId]['lastVisitTime']);
					$data[$sessionId]['pageviews'] += 1;
					$data[$sessionId]['lastVisitTime'] = $visitTime;
				}
				else {
					$data[$sessionId] = array();
					$data[$sessionId]['landingPage'] = $result['_source'];
					$data[$sessionId]['exitPage'] = $result['_source'];
					$data[$sessionId]['bounce'] = 1;
					$data[$sessionId]['duration'] = 0;
					$data[$sessionId]['pageviews'] = 1;
					$data[$sessionId]['lastVisitTime'] = $visitTime;
				}
			}
		
			/**
			 * Update session documents with extra info
			 */
			$params = array();
			$params['index'] = $sessionIndexName;
			$params['type'] = 'session';
			$currentTime = time();
		
			foreach($data as $sessionId => $sessionData) {
				$sessionLastVisitTime = $sessionData['lastVisitTime'];
				$timeElapsed = $currentTime - $sessionLastVisitTime;
				
				/**
				 * Update only if session has expired
				 */
				if($timeElapsed >= 2700) {
					$sessionDocument = array();
					$sessionDocument['landingPageDoc'] = $sessionData['landingPage'];
					$sessionDocument['exitPage'] = $sessionData['exitPage'];
					$sessionDocument['bounce'] = intval($sessionData['bounce']);
					$sessionDocument['duration'] = intval($sessionData['duration']);
					$sessionDocument['pageviews'] = intval($sessionData['pageviews']);
				
					$sessionDocumentId = $sessionMapping[$sessionId];
					$params['id'] = $sessionDocumentId;
					$params['body']['doc'] = $sessionDocument;
					$this->client->update($params);
				}
			}
		}		
	}
	
	function updatePageViews()
	{
		ini_set('memory_limit','4906M');
		$this->validateCron();
		$this->updatePageViewsForIndex(SESSION_INDEX_NAME, PAGEVIEW_INDEX_NAME);
	}

	function updateESPageViews()
	{
		ini_set('memory_limit','4906M');
		$this->validateCron();
		$this->updatePageViewsForShikshaESIndex(SESSION_INDEX_NAME, "shiksha_trafficdata_pageviews_1");
	}

	function updatePageViewsForShikshaESIndex($sessionIndex, $pageviewIndex)
	{		
		$clientParams = array();
        $clientParams['hosts'] = array(SHIKSHA_ELASTIC_HOST);
        $client = new Elasticsearch\Client($clientParams);
		
		$params = array();
		$params['index'] = $pageviewIndex;
        $params['type'] = 'pageview';
		
		$startDateFilter = array();
		$startDate = date('Y-m-d', strtotime('-2 day')).' 00:00:00';
		$startDate = convertDateISTtoUTC($startDate);
		$startDate = str_replace(" ", "T", $startDate);
		$startDateFilter['range']['visitTime']['gte'] = $startDate;
		
		$unprocessedFilter = array();
		$unprocessedFilter['exists']['field'] = 'source';

		$params['body']['query']['bool']['filter']['bool']['must'][] = $startDateFilter;
		$params['body']['query']['bool']['filter']['bool']['must_not'][] = $unprocessedFilter;
		$params['body']['_source'] = array('sessionId');
		$params['body']['size'] = 500000;
		
		$sessionIds = array();
		$docMapping = array();
		//_p(json_encode($params));die;
		$search = $client->search($params);
		foreach($search['hits']['hits'] as $hit) {
			$sessionId = $hit['_source']['sessionId']; 
			$sessionIds[$sessionId] = true;
			$docMapping[$hit['_id']] = $sessionId;
		}
		
		$sessionIds = array_keys($sessionIds);

		$params = array();
		$params['index'] = $sessionIndex;
        $params['type'] = 'session';
	
		$sessionData = array();
		for($i=0; $i<count($sessionIds); $i+=500)
		{
			$chunkSessionIds = array_slice($sessionIds, $i, 500);
			
			$params['body']['query']['terms']['sessionId'] = $chunkSessionIds;
			$params['size'] = 500;
			$params['_source'] = array('sessionId', 'source', 'utm_source', 'utm_medium', 'utm_campaign');
			//_p(json_encode($params));die;
			$search = $client->search($params);
			foreach($search['hits']['hits'] as $result) {
				
				$thisSessionId = $result['_source']['sessionId'];
				$thisSource = null;
				$thisUTMSource = null;
				$thisUTMMedium = null;
				$thisUTMCampaign = null;
				
				if($result['_source']['source']) {
					$thisSource = $result['_source']['source'];
				}
				if($result['_source']['utm_source']) {
					$thisUTMSource = $result['_source']['utm_source'];
				}
				if($result['_source']['utm_medium']) {
					$thisUTMMedium = $result['_source']['utm_medium'];
				}
				if($result['_source']['utm_campaign']) {
					$thisUTMCampaign = $result['_source']['utm_campaign'];
				}
				
				$sessionData[$thisSessionId] = array($thisSource, $thisUTMSource, $thisUTMMedium, $thisUTMCampaign);
			}
		}
		
		$indexParams = array();
		$indexParams['index'] = $pageviewIndex;
		$indexParams['type'] = 'pageview';
		$indexParams['body'] = array();
		
		$cnt = 0;
		
		foreach($docMapping as $docId => $docSessionId) {
			$indexParams['body'][] = array('update' => array(
											'_id' => $docId
										)
									);
					
			$indexParams['body'][] = array(
				'doc_as_upsert' => true,
				'doc' => array(
					'source' => $sessionData[$docSessionId][0],
					'utm_source' => $sessionData[$docSessionId][1],
					'utm_medium' => $sessionData[$docSessionId][2],
					'utm_campaign' => $sessionData[$docSessionId][3]
				)
			);
			
			if($cnt++ == 500) {
				$client->bulk($indexParams);
				$indexParams['body'] = array();
			}
		}
		
		if(count($indexParams['body']) > 0) {
			$client->bulk($indexParams);
			$indexParams['body'] = array();
		}
	}

	function updatePageViewsForIndex($sessionIndex, $pageviewIndex)
	{		
		$clientParams = array();
        //$clientParams['hosts'] = array('10.10.16.71', '10.10.16.72', '10.10.16.82');
		//$clientParams['hosts'] = array('10.10.16.72');
		$clientParams['hosts'] = array(ELASTIC_SEARCH_HOST);
        $client = new Elasticsearch\Client($clientParams);
		
		$params = array();
		$params['index'] = $pageviewIndex;
        $params['type'] = 'pageview';
		
		$startDateFilter = array();
		$startDateFilter['range']['visitTime']['gte'] = date('Y-m-d', strtotime('-2 day')).'T00:00:00';
		
		$unprocessedFilter = array();
		$unprocessedFilter['missing']['field'] = 'source';

		$params['body']['query']['filtered']['filter']['bool']['must'][] = $startDateFilter;
		$params['body']['query']['filtered']['filter']['bool']['must'][] = $unprocessedFilter;
		$params['body']['fields'] = array('sessionId');
		$params['body']['size'] = 500000;
		
		$sessionIds = array();
		$docMapping = array();
		
		$search = $client->search($params);
		foreach($search['hits']['hits'] as $hit) {
			$sessionId = $hit['fields']['sessionId'][0]; 
			$sessionIds[$sessionId] = true;
			$docMapping[$hit['_id']] = $sessionId;
		}
		
		$sessionIds = array_keys($sessionIds);
		
		$params = array();
		$params['index'] = $sessionIndex;
        $params['type'] = 'session';
	
		$sessionData = array();
	
		for($i=0; $i<count($sessionIds); $i+=500)
		{
			$chunkSessionIds = array_slice($sessionIds, $i, 500);
			
			$params['body']['query']['terms']['sessionId'] = $chunkSessionIds;
			$params['size'] = 500;
			$params['fields'] = array('sessionId', 'source', 'utm_source', 'utm_medium', 'utm_campaign');
			
			$search = $client->search($params);
			
			foreach($search['hits']['hits'] as $result) {
				
				$thisSessionId = $result['fields']['sessionId'][0];
				$thisSource = null;
				$thisUTMSource = null;
				$thisUTMMedium = null;
				$thisUTMCampaign = null;
				
				if($result['fields']['source']) {
					$thisSource = $result['fields']['source'][0];
				}
				if($result['fields']['utm_source']) {
					$thisUTMSource = $result['fields']['utm_source'][0];
				}
				if($result['fields']['utm_medium']) {
					$thisUTMMedium = $result['fields']['utm_medium'][0];
				}
				if($result['fields']['utm_campaign']) {
					$thisUTMCampaign = $result['fields']['utm_campaign'][0];
				}
				
				$sessionData[$thisSessionId] = array($thisSource, $thisUTMSource, $thisUTMMedium, $thisUTMCampaign);
			}
		}
		
		$indexParams = array();
		$indexParams['index'] = $pageviewIndex;
		$indexParams['type'] = 'pageview';
		$indexParams['body'] = array();
		
		$cnt = 0;
		
		foreach($docMapping as $docId => $docSessionId) {
			$indexParams['body'][] = array('update' => array(
											'_id' => $docId
										)
									);
					
			$indexParams['body'][] = array(
				'doc_as_upsert' => true,
				'doc' => array(
					'source' => $sessionData[$docSessionId][0],
					'utm_source' => $sessionData[$docSessionId][1],
					'utm_medium' => $sessionData[$docSessionId][2],
					'utm_campaign' => $sessionData[$docSessionId][3]
				)
			);
			
			if($cnt++ == 500) {
				$client->bulk($indexParams);
				$indexParams['body'] = array();
			}
		}
		
		if(count($indexParams['body']) > 0) {
			$client->bulk($indexParams);
			$indexParams['body'] = array();
		}
	}
	
	private function _getElasticClusterDetails(){
		// for pageview index : need to add previous and current month index
		$elasticClusterDetails = array(
			array(
				"ip" => "elastic2.shiksha.jsb9.net:9200",
				"indices" => "lead_tracking,ldb_search_agent,shiksha_response,autosuggestor_tracking"
				),
			array(
				"ip" => "elastic3.shiksha.jsb9.net:9200",
				"indices" => SESSION_INDEX_NAME_PREVIOUS_MONTH.",".SESSION_INDEX_NAME_CURRENT.",".PAGEVIEW_INDEX_NAME_PREVIOUS_MONTH.",".PAGEVIEW_INDEX_NAME_CURRENT.",".SESSION_INDEX_NAME_REALTIME.",".PAGEVIEW_INDEX_NAME_REALTIME.",".ANALYTICS_ELASTIC_INDEX.",".REGISTRATION_INDEX_NAME.",".LDB_RESPONSE_INDEX_NAME
				)
		);
		return $elasticClusterDetails;
	}

    function snapshot()
	{
		$this->validateCron();

		$elasticClusters = $this->_getElasticClusterDetails();
		foreach ($elasticClusters as $elasticCluster) {
			$url = "http://".$elasticCluster['ip']."/_snapshot/shiksha_elasticsearch_backup/snapshotv2_".date("Ymd_H");
			$data = array("indices" => $elasticCluster['indices']);
			$data_json = json_encode($data);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_json)));
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$response = curl_exec($ch);
			error_log(print_r($response,true));
		}	
	}
	
	function _deleteSnapshot($snapshot,$elasticIP)
	{
		$url = "http://".$elasticIP."/_snapshot/shiksha_elasticsearch_backup/".$snapshot;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
		curl_setopt($ch, CURLOPT_TIMEOUT,1);

		$response = curl_exec($ch);
		error_log("DONE:: ".$snapshot);
		error_log(print_r($response,true));
	}

	function purgeOldSnapshot(){
		$ESConnLib = $this->load->library("trackingMIS/elasticSearch/ESConnectionLib");
    	$ES6Client = $ESConnLib->getShikshaESServerConnection();
		$oldSnapshots = array("snapshotv2_20190223_09","snapshotv2_20190223_10","snapshotv2_20190223_11","snapshotv2_20190223_12","snapshotv2_20190223_13","snapshotv2_20190223_14","snapshotv2_20190223_15","snapshotv2_20190223_16","snapshotv2_20190223_17","snapshotv2_20190223_18","snapshotv2_20190223_19","snapshotv2_20190223_20","snapshotv2_20190223_21","snapshotv2_20190223_22","snapshotv2_20190224_00","snapshotv2_20190224_01","snapshotv2_20190224_02","snapshotv2_20190224_03","snapshotv2_20190224_04","snapshotv2_20190224_05","snapshotv2_20190224_06","snapshotv2_20190224_07","snapshotv2_20190224_08","snapshotv2_20190224_09","snapshotv2_20190224_10","snapshotv2_20190224_11","snapshotv2_20190224_12","snapshotv2_20190224_13","snapshotv2_20190224_14","snapshotv2_20190224_15","snapshotv2_20190224_17","snapshotv2_20190224_19","snapshotv2_20190224_21","snapshotv2_20190224_22","snapshotv2_20190224_23","snapshotv2_20190225_01","snapshotv2_20190225_02","snapshotv2_20190225_03","snapshotv2_20190225_04","snapshotv2_20190225_05","snapshotv2_20190225_06","snapshotv2_20190225_07","snapshotv2_20190225_08","snapshotv2_20190225_10","snapshotv2_20190225_11","snapshotv2_20190225_12","snapshotv2_20190225_13","snapshotv2_20190225_14","snapshotv2_20190225_15","snapshotv2_20190225_16","snapshotv2_20190225_17","snapshotv2_20190225_18","snapshotv2_20190225_20","snapshotv2_20190225_21","snapshotv2_20190225_22","snapshotv2_20190225_23","snapshotv2_20190226_00","snapshotv2_20190226_01","snapshotv2_20190226_02","snapshotv2_20190226_03","snapshotv2_20190226_04","snapshotv2_20190226_05","snapshotv2_20190226_06","snapshotv2_20190226_07","snapshotv2_20190226_09","snapshotv2_20190226_10","snapshotv2_20190226_11","snapshotv2_20190226_12","snapshotv2_20190226_13","snapshotv2_20190226_14","snapshotv2_20190226_15","snapshotv2_20190226_16","snapshotv2_20190226_17","snapshotv2_20190226_18","snapshotv2_20190226_19","snapshotv2_20190226_20","snapshotv2_20190226_21","snapshotv2_20190226_22","snapshotv2_20190226_23","snapshotv2_20190227_00","snapshotv2_20190227_01","snapshotv2_20190227_02","snapshotv2_20190227_03","snapshotv2_20190227_05","snapshotv2_20190227_06","snapshotv2_20190227_07","snapshotv2_20190227_08","snapshotv2_20190227_09","snapshotv2_20190227_10","snapshotv2_20190227_11","snapshotv2_20190227_12","snapshotv2_20190227_14","snapshotv2_20190227_16","snapshotv2_20190227_17","snapshotv2_20190227_18","snapshotv2_20190227_21","snapshotv2_20190227_22","snapshotv2_20190227_23","snapshotv2_20190228_00","snapshotv2_20190228_01","snapshotv2_20190228_02","snapshotv2_20190228_03","snapshotv2_20190228_04","snapshotv2_20190228_05","snapshotv2_20190228_06","snapshotv2_20190228_07","snapshotv2_20190228_08","snapshotv2_20190228_09","snapshotv2_20190228_10","snapshotv2_20190228_12","snapshotv2_20190228_13","snapshotv2_20190228_14","snapshotv2_20190228_15","snapshotv2_20190228_16","snapshotv2_20190228_17","snapshotv2_20190228_18","snapshotv2_20190228_19","snapshotv2_20190228_20","snapshotv2_20190228_21","snapshotv2_20190228_22","snapshotv2_20190228_23","snapshotv2_20190301_00","snapshotv2_20190301_01","snapshotv2_20190301_02","snapshotv2_20190301_03","snapshotv2_20190301_04","snapshotv2_20190301_05","snapshotv2_20190301_06","snapshotv2_20190301_07","snapshotv2_20190301_08","snapshotv2_20190301_10","snapshotv2_20190301_11","snapshotv2_20190301_12","snapshotv2_20190301_13","snapshotv2_20190301_14","snapshotv2_20190301_15","snapshotv2_20190301_17","snapshotv2_20190301_18","snapshotv2_20190301_19","snapshotv2_20190301_20","snapshotv2_20190301_21","snapshotv2_20190301_23","snapshotv2_20190302_00","snapshotv2_20190302_01","snapshotv2_20190302_02","snapshotv2_20190302_03","snapshotv2_20190302_04","snapshotv2_20190302_05","snapshotv2_20190302_06","snapshotv2_20190302_07","snapshotv2_20190302_08","snapshotv2_20190302_09","snapshotv2_20190302_10","snapshotv2_20190302_11","snapshotv2_20190302_12","snapshotv2_20190302_13","snapshotv2_20190302_14","snapshotv2_20190302_15","snapshotv2_20190302_16","snapshotv2_20190302_17","snapshotv2_20190302_18","snapshotv2_20190302_19","snapshotv2_20190302_20","snapshotv2_20190302_21","snapshotv2_20190302_22","snapshotv2_20190302_23","snapshotv2_20190303_00","snapshotv2_20190303_01","snapshotv2_20190303_02","snapshotv2_20190303_03");
		foreach ($oldSnapshots as $snapshot) {
			try{
				$indexData = $ES6Client->snapshot()->delete(array('repository' => "shiksha_elasticsearch_backup","snapshot"=>$snapshot));
				error_log(date("Y-m-d H:i:s")."  Snapshot Name : ".$snapshot);

			}catch(Exception $e){
				continue;
			}
			
			$minute = intVal(date('i'));;
			if($minute < 30){
				exit();
			}
			error_log("End Time ".date("Y-m-d H:i:s"));
		}
	}


	function purgeSnapshots()
        {
        		$this->validateCron();
        		$elasticClusters = $this->_getElasticClusterDetails();
                $thresholdDay = date('Ymd', strtotime('-4 day'));
                $thresholdSnapshot = 'snapshotv2_'.$thresholdDay;
                foreach ($elasticClusters as $elasticCluster) {
                	$url = "http://".$elasticCluster['ip']."/_snapshot/shiksha_elasticsearch_backup/_all";
	                $ch = curl_init();
	                curl_setopt($ch, CURLOPT_URL, $url);
	                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	                $response = curl_exec($ch);
	                $response = json_decode($response, true);

	                if(is_array($response) && is_array($response['snapshots']) && count($response['snapshots']) > 0) {
	                        $snapshots = $response['snapshots'];
	                        foreach($snapshots as $snapshot) {
	                                if($snapshot['snapshot'] < $thresholdSnapshot) {
	                                        error_log($snapshot['snapshot']);
	                                        $this->_deleteSnapshot($snapshot['snapshot'],$elasticCluster['ip']);
	                                }
	                        }
	                }
                }
        }


    private function _closeIndex($elasitcAWSIP,$indexList){
    	$url = "http://".$elasitcAWSIP."/".$indexList."/_close";
    	//error_log("In close index ".print_r($url));
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
		curl_setopt($ch, CURLOPT_TIMEOUT,1);

		$response = curl_exec($ch);
		error_log("ELASITC_CRON ERROR LOG (Close index) :".print_r($response,true));

    }

    private function _restoreIndex($elasitcAWSIP,$snapshot,$indexList){
    	$this->_closeIndex($elasitcAWSIP,$indexList);
    	$url = "http://".$elasitcAWSIP."/_snapshot/shiksha_elasticsearch_backup/".$snapshot."/_restore?wait_for_completion=true";
    	$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
		curl_setopt($ch, CURLOPT_TIMEOUT,1);

		$response = curl_exec($ch);
		error_log("ELASITC_CRON ERROR LOG (Restore index) :".print_r($response,true));
    }

    function restoreSnapshotToAWS(){
    	///ElasticSearchCron/restoreSnapshotToAWS
        $this->validateCron();
        //http://10.10.16.101:9200/_snapshot/shiksha_elasticsearch_backup/snapshotv2_20190402_13/_status
        $elasitcAWSIPs = array("elastic1.shiksha.jsb9.net:9200","elastic2.shiksha.jsb9.net:9200" );
        foreach ($elasitcAWSIPs as $elasitcAWSIP) {
            $url = "http://".$elasitcAWSIP."/_snapshot/shiksha_elasticsearch_backup/_all";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);

            $response = (array)json_decode($response);
            if(is_array($response) && is_array($response['snapshots']) && count($response['snapshots']) > 0) {
                $snapshots = $response['snapshots'];
                $latestSnapshot = $snapshots[count($snapshots)-1];
                $indexList = implode(",", $latestSnapshot->indices);
                $latestSnapshotName = $latestSnapshot->snapshot;
                $this->_restoreIndex($elasitcAWSIP,$latestSnapshotName,$indexList);
            }
        }
    }

    // cron starting function
    function updateAndbulkIndexPageviewData(){
    	$this->validateCron();
    	ini_set('memory_limit','4096M');
    	$startTime = time();
    	error_log("================================ Indexing Cron Started at : ".date("Y-m-d H:i:s")."\n", 3, "/tmp/pageviewIndexing.log_".date('Y-m-d'));    	

    	$ESConnLib = $this->load->library("trackingMIS/elasticSearch/ESConnectionLib");
    	$ESClientConn = $ESConnLib->getShikshaESServerConnection();

    	// last last document date
    	$inputParams = array("index" => SESSION_INDEX_NAME_REALTIME_SEARCH,"type"  => "session","dateField" => "startTime");
    	$sessionLastDocDate = $this->getESIndexLastDocDate($ESClientConn, $inputParams);
    	
    	// last last document date
    	$inputParams = array("index" => PAGEVIEW_INDEX_NAME,"type"  => "pageview","dateField" => "visitTime");

    	$cacheLib = $this->load->library('common/cacheLib');
    	$shikshaPageviewLastDocDate = $cacheLib->get('shikshaPageviewLastDocDate');
    	if($shikshaPageviewLastDocDate == 'ERROR_READING_CACHE' || !$shikshaPageviewLastDocDate){
			$lastDocDate = $this->getESIndexLastDocDate($ESClientConn, $inputParams);// need to change query as we have now monthly index
			$shikshaPageviewLastDocDate = strtotime($lastDocDate);
			error_log("=================================== CACHE_MISS :".$shikshaPageviewLastDocDate." \n", 3, "/tmp/sessionIndexing.log_".date('Y-m-d'));
		}else{

			$lastDocDate = date("Y-m-d H:i:s",$shikshaPageviewLastDocDate);
			$docExists = $this->_checkIfDocExistAfterDate($ESClientConn, $lastDocDate, $inputParams);
			if($docExists == true){
				error_log("=================================== INVAILD_CACHE_ENTRY for shikshaPageviewLastDocDate :".$shikshaPageviewLastDocDate." \n", 3, "/tmp/sessionIndexing.log_".date('Y-m-d'));
				mail('praveen.singhal@99acres.com','Invaild Cache Entry for shikshaPageviewLastDocDate', 'shikshaPageviewLastDocDate : '.$shikshaPageviewLastDocDate);
				exit();
			}
		}

    	$startDate = date("Y-m-d H:i:s",strtotime($lastDocDate."+1 seconds"));

    	$endDate = date("Y-m-d H:i:s",strtotime("-10 minutes"));
    	$endDate = convertDateISTtoUTC($endDate);

    	if(strtotime($endDate) > strtotime($sessionLastDocDate)){
    		$endDate = $sessionLastDocDate;
    	}

    	// check if enddate is lessthan startdate
    	if(strtotime($endDate) <strtotime($startDate)){
    		return;
    	}

    	$newDocuments = array();
    	$inputParams['startDate'] = $startDate;
    	$inputParams['endDate'] = $endDate;
    	$inputParams['index'] = PAGEVIEW_INDEX_NAME_REALTIME_SEARCH;    	
    	$this->getNewDocToIndex($ESClientConn, $newDocuments, $inputParams);

    	// fetch additional Details(source, utm_source, utm_medium, utm_campaign) details from session index
    	$sessionIds = array();
    	foreach($newDocuments as $key => $newDoc) {
			$sessionIds[$newDoc['_source']['sessionId']] = true;
			$newDocuments[$key] = $newDoc['_source'];
		}

		$sessionIds = array_keys($sessionIds);
		error_log("Total Doc To Index : ".count($sessionIds)."\n", 3, "/tmp/pageviewIndexing.log_".date('Y-m-d'));
		$chunkSize = 100;
		$sessionIdsChunk =  array_chunk($sessionIds, $chunkSize);
		unset($sessionIds);
		$sessionData = array();

		error_log("Data fatching start : ".date("Y-m-d H:i:s")."\n", 3, "/tmp/pageviewIndexing.log_".date('Y-m-d'));
		foreach ($sessionIdsChunk as $key => $chunk) {
			$params = array();
			$params['index'] = SESSION_INDEX_NAME_REALTIME_SEARCH;
	        $params['type'] = 'session';

			$sessionFilter = array();
			$sessionFilter['terms']['sessionId'] = $chunk;
			$params['size'] = 100000;
			$params['_source'] = array('sessionId', 'source', 'utm_source', 'utm_medium', 'utm_campaign');
			$params['body']['query']['bool']['filter']['bool']['must'][] = $sessionFilter;
			$search = $ESClientConn->search($params);
			$logData = "Loop Query Time taken : ".$search['took']."  ===== sessions count : ".count($search['hits']['hits'])."\n";
			error_log($logData, 3, "/tmp/pageviewIndexing.log_".date('Y-m-d'));

			foreach($search['hits']['hits'] as $result) {
				$thisSessionId = $result['_source']['sessionId'];
				$thisSource = null;
				$thisUTMSource = null;
				$thisUTMMedium = null;
				$thisUTMCampaign = null;
				
				if($result['_source']['source']) {
					$thisSource = $result['_source']['source'];
				}
				if($result['_source']['utm_source']) {
					$thisUTMSource = $result['_source']['utm_source'];
				}
				if($result['_source']['utm_medium']) {
					$thisUTMMedium = $result['_source']['utm_medium'];
				}
				if($result['_source']['utm_campaign']) {
					$thisUTMCampaign = $result['_source']['utm_campaign'];
				}

				$sessionData[$thisSessionId] = array($thisSource, $thisUTMSource, $thisUTMMedium, $thisUTMCampaign);
			}
			usleep(100000);
		}
		error_log("Data fatching end : ".date("Y-m-d H:i:s")."\n", 3, "/tmp/pageviewIndexing.log_".date('Y-m-d'));

		foreach ($newDocuments as $key => $doc) {
			$newDocuments[$key]['source']       = $sessionData[$doc['sessionId']][0];
			$newDocuments[$key]['utm_source']   = $sessionData[$doc['sessionId']][1];
			$newDocuments[$key]['utm_medium']   = $sessionData[$doc['sessionId']][2];
			$newDocuments[$key]['utm_campaign'] = $sessionData[$doc['sessionId']][3];
		}

		unset($sessionData);

		$i = 1;
		$params = array();
		foreach($newDocuments as $doc) {
			$currentIndex = PAGEVIEW_INDEX_NAME_PREFIX.date('Ym',strtotime($doc['visitTimeIST']));
            $params['body'][] = array('index' => array(
                                    '_index' => $currentIndex,//PAGEVIEW_INDEX_NAME, // need to get index name based on document date
                                    '_type' =>  "pageview",
                                )
                            );

            if($shikshaPageviewLastDocDate < strtotime($doc['visitTime'])){
				$shikshaPageviewLastDocDate = strtotime($doc['visitTime']);
			}

            $params['body'][] = $doc;
            if ($i % 500 == 0) {
                $indexingResponse = $ESClientConn->bulk($params);
                usleep(10000);
                $this->parseESIndexingResponse($indexingResponse);
                $params = array();
                $params['body'] = array();
                unset($indexingResponse);
            }
            $i++;
        }
        if (!empty($params['body'])) {
            $indexingResponse = $ESClientConn->bulk($params);
            $this->parseESIndexingResponse($indexingResponse);
        }
        $cacheLib->store("shikshaPageviewLastDocDate", $shikshaPageviewLastDocDate, -1);die;

        $endTime = time();
		error_log("Total Time taken : ".($endTime - $startTime)."\n", 3, "/tmp/pageviewIndexing.log_".date('Y-m-d'));
    }

    // cron starting function
    function updateAndbulkIndexSessionData(){
    	$this->validateCron();
    	$startTime = time();
    	error_log("=================================== Indexing Cron Started at : ".date("Y-m-d H:i:s")."\n", 3, "/tmp/sessionIndexing.log_".date('Y-m-d'));
    	ini_set('memory_limit','4096M');
    	$ESConnLib = $this->load->library("trackingMIS/elasticSearch/ESConnectionLib");
    	$ESClientConn = $ESConnLib->getShikshaESServerConnection();

    	// last last document date
    	$inputParams = array("index" => SESSION_INDEX_NAME,"type"  => "session","dateField" => "startTime");

    	$cacheLib = $this->load->library('common/cacheLib');
    	$shikshaSessionLastDocDate = $cacheLib->get('shikshaSessionLastDocDate');
    	if($shikshaSessionLastDocDate == 'ERROR_READING_CACHE' || !$shikshaSessionLastDocDate){
			$lastDocDate = $this->getESIndexLastDocDate($ESClientConn, $inputParams);
			$shikshaSessionLastDocDate = strtotime($lastDocDate);
			error_log("=================================== CACHE_MISS :".$shikshaSessionLastDocDate." \n", 3, "/tmp/sessionIndexing.log_".date('Y-m-d'));
		}else{
			$lastDocDate = date("Y-m-d H:i:s",$shikshaSessionLastDocDate);

			$docExists = $this->_checkIfDocExistAfterDate($ESClientConn, $lastDocDate, $inputParams);
			if($docExists == true){
				error_log("=================================== INVAILD_CACHE_ENTRY for shikshaSessionLastDocDate :".$shikshaSessionLastDocDate." \n", 3, "/tmp/sessionIndexing.log_".date('Y-m-d'));
				mail('praveen.singhal@99acres.com','Invaild Cache Entry for shikshaSessionLastDocDate', 'shikshaSessionLastDocDate : '.$shikshaSessionLastDocDate);
				exit();
			}

		}

    	//_p($lastDocDate);die;
    	$startDate = date("Y-m-d H:i:s",strtotime($lastDocDate."+1 seconds"));

    	$endDate = date("Y-m-d H:i:s",strtotime("-60 minutes"));
    	$endDate = convertDateISTtoUTC($endDate);

    	// check if enddate is lessthan startdate
    	if(strtotime($endDate) <strtotime($startDate)){
    		return;
    	}
   
    	//_p($startDate);_p($endDate);die;
    	$inputParams['startDate'] = $startDate;
    	$inputParams['endDate'] = $endDate;
    	$inputParams['index'] = SESSION_INDEX_NAME_REALTIME_SEARCH;
    	$newDocuments = array();
    	$this->getNewDocToIndex($ESClientConn, $newDocuments, $inputParams);

    	error_log("Total Doc To Index : ".count($newDocuments)."\n", 3, "/tmp/sessionIndexing.log_".date('Y-m-d'));
    	// fetch additional Details(landingPage, exitPage, duration, bounce, totalPageviews) details from pageview index
    	$sessionIds = array();
    	$sessionIdToDocMapping = array();
    	foreach($newDocuments as $key => $newDoc) {
			$sessionIds[] = $newDoc['_source']['sessionId'];
			$sessionIdToDocMapping[$newDoc['_source']['sessionId']] = $newDoc["_source"];
			if(isset($newDoc["_source"]['time'])){
				$sessionIdToDocMapping[$newDoc['_source']['sessionId']]['timeUTC']= $newDoc["_source"]['time'];	
			}
			
			unset($newDocuments[$key]);
		}
		unset($newDocuments);
		//_p($sessionIds);_p($sessionIdToDocMapping);die;

		// Start processing in batch
		$numSessions = count($sessionIds);
		$sessionIdsChunk =  array_chunk($sessionIds, 100);
		unset($sessionIds);

		foreach ($sessionIdsChunk as $key => $chunk) {
			$trackChunkTimeT1 = time();
			$logData = "";

			$params = array();
			$params['index'] = PAGEVIEW_INDEX_NAME_REALTIME_SEARCH;
			$params['type'] = 'pageview';
			$params['body']['size'] = 1000000;
			$params['body']['sort']['visitTime']['order'] = 'asc';

			$sessionFilter = array();
			$sessionFilter['terms']['sessionId'] = $chunk;
			$params['body']['query']['bool']['filter']['bool']['must'][] = $sessionFilter;
			$search = $ESClientConn->search($params);
			$results = $search['hits']['hits'];
			if(!(count($results)>0)){
				mail('praveen.singhal@99acres.com','Data Incomplete ES session indexing ', '<br/>Some data missing <br/>'.print_r($sessionFilter, true));
				continue;
			}

			$logData = "Loop Query Time taken : ".$search['took']."  ===== pageviews count : ".count($results);

			$data = array();
			foreach($results as $result) {
				$sessionId =  $result['_source']['sessionId'];
				$visitTime = strtotime($result['_source']['visitTime']);

				if(array_key_exists($sessionId, $data)) {
					$data[$sessionId]['exitPage'] = $result['_source'];
					$data[$sessionId]['bounce'] = 0;
					$data[$sessionId]['duration'] += ($visitTime - $data[$sessionId]['lastVisitTime']);
					$data[$sessionId]['pageviews'] += 1;
					$data[$sessionId]['lastVisitTime'] = $visitTime;
				}else {
					$data[$sessionId] = array();
					$data[$sessionId]['landingPage'] = $result['_source'];
					$data[$sessionId]['exitPage'] = $result['_source'];
					$data[$sessionId]['bounce'] = 1;
					$data[$sessionId]['duration'] = 0;
					$data[$sessionId]['pageviews'] = 1;
					$data[$sessionId]['lastVisitTime'] = $visitTime;
				}

				if(isset($result['_source']['isource'])){
					$data[$sessionId]['isource'] = $result['_source']['isource'];
					if(isset($data[$sessionId]['isourcePageViewCount'])){
						$data[$sessionId]['isourcePageViewCount']  += 1;
					}else{
						$data[$sessionId]['isourcePageViewCount']  = 1;
					}
					
				}
			}

			unset($results);

			// Bulk insert session data
			$currentTime = date("Y-m-d H:i:s");
			$currentTime = convertDateISTtoUTC($currentTime);
			$currentTime = strtotime($currentTime);

			$params = array();
			$indexingDocCount = 0;

			foreach ($chunk as $sessionId) {
				if(isset($data[$sessionId])){
					$sessionLastVisitTime =  $data[$sessionId]['lastVisitTime'];
					$timeElapsed          = $currentTime - $sessionLastVisitTime;
					if($timeElapsed >= 2700) {
						$sessionIdToDocMapping[$sessionId]['landingPageDoc'] = $data[$sessionId]['landingPage'];
						$sessionIdToDocMapping[$sessionId]['exitPage']       = $data[$sessionId]['exitPage'];
						$sessionIdToDocMapping[$sessionId]['bounce']         = intval($data[$sessionId]['bounce']);
						$sessionIdToDocMapping[$sessionId]['duration']       = intval($data[$sessionId]['duration']);
						$sessionIdToDocMapping[$sessionId]['pageviews']      = intval($data[$sessionId]['pageviews']);
					}else{
						//error_log("Session Missing 1  : ".$sessionId, 3, "/tmp/sessionIndexing.log_".date('Y-m-d'));	
					}

					if(isset($data[$sessionId]['isource'])){
						$sessionIdToDocMapping[$sessionId]['isource']      = $data[$sessionId]['isource'];
						$sessionIdToDocMapping[$sessionId]['isourcePageViewCount'] = $data[$sessionId]['isourcePageViewCount'];
					}
				}else{
					error_log("Session Missing 2  : ".$sessionId, 3, "/tmp/sessionIndexing.log_".date('Y-m-d'));	
				}

				$indexingDocCount ++;
				$currentIndex = SESSION_INDEX_NAME_PREFIX.date('Ym',strtotime($sessionIdToDocMapping[$sessionId]['startTimeIST']));
				$params['body'][] = array('index' => array(
                        '_index' => $currentIndex,//SESSION_INDEX_NAME, // need to get index name based on document date
                        '_type' =>  "session",
                    )
                );
				/*$params['body'][] = array('index' => array(
                        '_index' => SESSION_INDEX_NAME,
                        '_type' =>  "session",
                    )
                );*/
				$params['body'][] = $sessionIdToDocMapping[$sessionId];

				if($shikshaSessionLastDocDate < strtotime($sessionIdToDocMapping[$sessionId]['startTime'])){
					$shikshaSessionLastDocDate = strtotime($sessionIdToDocMapping[$sessionId]['startTime']);
				}

				unset($sessionIdToDocMapping[$sessionId]);
			}
			unset($data);
			if($indexingDocCount >0){
				// bulk  index
				error_log("Total Doc to index : ".$indexingDocCount, 3, "/tmp/sessionIndexing.log_".date('Y-m-d'));
				$indexingResponse = $ESClientConn->bulk($params);
				usleep(10000);
				$this->parseESIndexingResponse($indexingResponse);
				$cacheLib->store("shikshaSessionLastDocDate", $shikshaSessionLastDocDate, -1);
			}

			$trackChunkTimeT2 = time();
			$logData .= " ==== Total chunk indexing time".($trackChunkTimeT2-$trackChunkTimeT1)." at :  ".date("Y-m-d H:i:s")."\n";
			error_log($logData, 3, "/tmp/sessionIndexing.log_".date('Y-m-d'));
		}

		$endTime = time();
		error_log("Total Time taken : ".($endTime - $startTime)."\n", 3, "/tmp/sessionIndexing.log_".date('Y-m-d'));

		error_log("Update Flow start Time  : ".date("Y-m-d H:i:s")."\n", 3, "/tmp/sessionUpdateIndexing.log_".date('Y-m-d'));
		$startTime = time();
		$this->updateESSessions();
		$endTime = time();
		error_log("Update Flow Total Time  : ".($endTime - $startTime)."\n", 3, "/tmp/sessionUpdateIndexing.log_".date('Y-m-d'));

		// need to add Shiksha - Assistant message count
		if(SHIKSHA_ASSISTANT_FLAG == 1 ) {
			$startTime = time();
			$this->_addSAMessageCountToSession();
			$endTime = time();
			error_log("Update Flow Total Time  : ".($endTime - $startTime)."\n", 3, "/data/app_logs/addSAMsgCountIndexing.log_".date('Y-m-d'));


			//$startTime = time();
			//$this->_addSAabTestValue();
			//$endTime = time();
			//error_log("Update Flow Total Time  : ".($endTime - $startTime)."\n", 3, "/data/app_logs/addSAabTestValueIndexing.log_".date('Y-m-d'));
		}
    }

    private function parseESIndexingResponse(&$indexingResponse){

    }

    private function getNewDocToIndex($ESClientConn, &$newDocuments, $inputParams = array()){
		$dateRangeFilter = array(
			"range" => array(
				$inputParams["dateField"] => array(
					"gte" => str_replace(" ", "T", $inputParams["startDate"]),
					"lte" => str_replace(" ", "T", $inputParams["endDate"])
				)
			)
		);

		$params = array();
		$params = array(
			"index" => $inputParams['index'],
			"type" => $inputParams['type'],
			"body" => array(
				"size" => 500000,
				"query" => array(
					"bool" => array(
						"filter" => array(
							"bool" => array(
								"must" => array($dateRangeFilter)
							)
						)
					)
				)
			)
		);

		$result = $ESClientConn->search($params);
		$newDocuments = $result['hits']['hits'];

		$logData = "ES QUERY (get newly added data) : ".json_encode($params)." Time taken : ".$result['took']."\n";

		if($inputParams['type'] == "session"){
			error_log($logData, 3, "/tmp/sessionIndexing.log_".date('Y-m-d'));
		}else{
			error_log($logData, 3, "/tmp/pageviewIndexing.log_".date('Y-m-d'));
		}
    }

    private function getESIndexLastDocDate($ESClientConn, $inputParams = array()){
    	$params = array();
		$params = array(
			"index" => $inputParams['index'],
			"type" => $inputParams['type'],
			"body" => array(
				"size" => 1,
				"_source" => array($inputParams['dateField']),
				"sort" => array(
					$inputParams['dateField'] => array(
						"order" => "desc"
					)
				)
			)
		);

		$result = $ESClientConn->search($params);
		$lastDocDate = $result['hits']['hits']['0']['_source'][$inputParams['dateField']];
		$lastDocDate = str_replace("T", " ", $lastDocDate);

		$logData = "ES QUERY (Last Doc Date) : ".json_encode($params)." Time taken : ".$result['took']."\n";

		if($inputParams['type'] == "session"){
			error_log($logData, 3, "/tmp/sessionIndexing.log_".date('Y-m-d'));
		}else{
			error_log($logData, 3, "/tmp/pageviewIndexing.log_".date('Y-m-d'));
		}
		// check last doc date not greater than 6 hours
			// add mail
		return $lastDocDate;
    }

    private function _checkIfDocExistAfterDate($ESClientConn, $lastDocDate, $inputParams){
    	$lastDocDate = date("Y-m-d\TH:i:s",strtotime($lastDocDate."+1 seconds"));
    	//_p($lastDocDate);

    	$params = array();
		$params = array(
			"index" => $inputParams['index'],
			"type" => $inputParams['type'],
			"body" => array(
				"size" => 1
			)
		);
		$params["body"]["query"]["bool"]["filter"]["bool"]["must"]["range"][$inputParams['dateField']]["gte"] = $lastDocDate;
		//_p($params);die;
		$result = $ESClientConn->search($params);
		if($result['hits']['total'] > 0){
			return true;
		}
		return false;
    }

    private function _getLastDocInSassistant($ES5Client){
    	$lastDocTime = str_replace(" ", "T", convertDateISTtoUTC(date("Y-m-d H:i:s", strtotime('-3 hours')))); // default current time

    	$params = array();
    	$params['index'] = SHIKSHA_ASSISTANT_INDEX_NAME_REALTIME_SEARCH;
    	$params['type'] = "chat";
    	$params['body']['size'] = 1;
    	$params['body']['_source'] = array("queryTimeUTC");
		$params['body']['sort']['queryTimeUTC']['order'] = 'desc';
		$response = $ES5Client->search($params);
		if($response['hits']['total'] >0){
			$lastDocTime = $response['hits']['hits'][0]["_source"]['queryTimeUTC'];
			$lastDocTime = str_replace("T", " ", $lastDocTime);
			$lastDocTime = date("Y-m-d\TH:i:s",strtotime($lastDocTime."-3 hours")); // take end date  = lastdocdate - 3 hour so that is completed
		}

		return $lastDocTime;
    }


    private function _getLastDocInShikshaABTestIndex($ES5Client){
    	$lastDocTime = str_replace(" ", "T", convertDateISTtoUTC(date("Y-m-d H:i:s", strtotime('-3 hours')))); // default current time

    	$params = array();
    	$params['index'] = SHIKSHA_ABTEST_INDEX_NAME;
    	$params['type'] = "abtest";
    	$params['body']['size'] = 1;
    	$params['body']['_source'] = array("startTimeUTC");
		$params['body']['sort']['startTimeUTC']['order'] = 'desc';

		$response = $ES5Client->search($params);
		if($response['hits']['total'] >0){
			$lastDocTime = $response['hits']['hits'][0]["_source"]['startTimeUTC'];
			$lastDocTime = str_replace("T", " ", $lastDocTime);
			$lastDocTime = date("Y-m-d\TH:i:s",strtotime($lastDocTime."-3 hours")); // take end date  = lastdocdate - 3 hour so that is completed
		}

		return $lastDocTime;
    }

    private function _getSessionDetailsFromSassistant($sessionIds, $ES5Client){
    	//echo "fdf";_p($sessionIds);
    	$response = array();

    	if(count($sessionIds) >0){
    		$params = array();
	    	$params['index'] = SHIKSHA_ASSISTANT_INDEX_NAME_REALTIME_SEARCH;
	    	$params['type'] = "chat";
	    	$params['body']['size'] = 1000000;
	    	$params['body']['_source'] = array("sessionId");
	    	$mustFilters = array();
	    	$mustFilters = array("terms" => array("sessionId" => $sessionIds));
			$params['body']['query']['bool']['filter']['bool']['must'] = $mustFilters;
			$params['filter_path'] =array("hits.hits._source","hits.total");
			//_p($params);die;
			$result = $ES5Client->search($params);
			//_p($result['hits']['total']);_p($result['hits']['hits']);die;
			foreach ($result['hits']['hits'] as $key => $doc) {
				if(isset($response[$doc['_source']['sessionId']])){
					$response[$doc['_source']['sessionId']] += 1;
				}else{
					$response[$doc['_source']['sessionId']] = 1;
				}
			}
    	}
    	//_p($response);die;
    	return $response;
    }


    private function _getSessionDetailsFromShikshaABTestIndex($sessionIds, $ES5Client){
    	//echo "fdf";_p($sessionIds);
    	$response = array();

    	if(count($sessionIds) >0){
    		$params = array();
	    	$params['index'] = SHIKSHA_ABTEST_INDEX_NAME;
	    	$params['type'] = "abtest";
	    	$params['body']['size'] = 1000000;
	    	$params['body']['_source'] = array("sessionId", "abVarient");
	    	$mustFilters = array();
	    	$mustFilters[] = array("terms" => array("sessionId" => $sessionIds));
	    	$mustFilters[] = array("term" => array("isource" => "sa"));

			$params['body']['query']['bool']['filter']['bool']['must'] = $mustFilters;
			$params['filter_path'] =array("hits.hits._source","hits.total");
			//_p($params);die;
			$result = $ES5Client->search($params);
			//_p($result['hits']['total']);_p($result['hits']['hits']);
			foreach ($result['hits']['hits'] as $key => $doc) {
				$response[$doc['_source']['sessionId']] = $doc['_source']['abVarient'];
			}
    	}
    	//_p($response);die;
    	return $response;
    }

    private function _addSAMessageCountToSession(){
    	// need to add Shiksha - Assistant message count in session index

		ini_set('memory_limit','4906M');

		$ESConnLib = $this->load->library("trackingMIS/elasticSearch/ESConnectionLib");
    	$ES6Client = $ESConnLib->getShikshaESServerConnection();
    	$ES5Client = $ESConnLib->getESServerConnectionWithCredentials();

    	$endDate = $this->_getLastDocInSassistant($ES5Client);  // end date to process session data

    	$startDate = date('Y-m-d H:i:s', strtotime('-48 hours'));  // today and previous day
		$startDate = convertDateISTtoUTC($startDate);
		$startDate = str_replace(" ", "T", $startDate);

		// Get unprocessed sessions 
		$params = array();
		$params['index'] = SESSION_INDEX_NAME;
		$params['type'] = 'session';
		
		$startDateFilter = array();
		$startDateFilter['range']['startTime']['gte'] = $startDate;
		$startDateFilter['range']['startTime']['lte'] = $endDate;

		$mustFilters = array();
		$mustFilters[] = $startDateFilter;
		$mustFilters[] = array("term" => array("isStudyAbroad" => "no"));

		/**
 		 * For unprocessed, we check whether SAMessageCount field exists or missing for national users
 		 * If SAMessageCount field exists, it has been processed, else it's unprocessed
 		 */
		$unprocessedFilter = array();
		$unprocessedFilter['exists']['field'] = 'SAMessageCount';

		$params['body']['query']['bool']['filter']['bool']['must'] = $mustFilters;
		$params['body']['query']['bool']['filter']['bool']['must_not'][] = $unprocessedFilter;
		$params['body']['_source'] = array('sessionId');
		$params['body']['size'] = 500000;
		//_p(json_encode($params));die;
		$search = $ES6Client->search($params);
		$sessionIds = array();
		$sessionMapping = array();
		$sessionToIndexMapping = array();
		foreach($search['hits']['hits'] as $result) {
    		$sessionId = $result['_source']['sessionId'];
    		$sessionDocumentId = $result['_id'];
    		$sessionIds[] = $sessionId;
    		$sessionMapping[$sessionId] = $sessionDocumentId;
    		$sessionToIndexMapping[$result['_index']][] = $sessionId;
		}
		unset($search);
		foreach ($sessionToIndexMapping as $indexNameToUpdateData => $sessionDataToUpdate) {

			/**
			 * Process in batches
			 */
			//get document form ES 
	        $params = array();
			$params['index'] = $indexNameToUpdateData;//SESSION_INDEX_NAME;
			$params['type'] = 'session';

			//$sessionIdsChunk = array_chunk($sessionIds, 100);
			$sessionIdsChunk = array_chunk($sessionDataToUpdate, 100);
			$sessionIds = array();
					
			foreach ($sessionIdsChunk as $key => $chunk) {
				$params['body'] = array();

				// search these session ids in shiksha_assistant_conversations
				$esResult = array();
				$esResult = $this->_getSessionDetailsFromSassistant($chunk, $ES5Client);
				
				foreach ($chunk as $key => $sessionId) {
					$params['body'][] = array('update' => array('_id' => $sessionMapping[$sessionId]));
	                $newDoc = array("SAMessageCount" => (isset($esResult[$sessionId]))?$esResult[$sessionId]:0);
	                $params['body'][] = array(
		                'doc_as_upsert' => false,
		                'doc' => $newDoc
					);
				}

				$indexResponse = $ES6Client->bulk($params);
			}
		}
    }

    private function _addSAabTestValue(){
    	// need to change this function for new session index format
    	die("dsdsd");
    	// need to add Shiksha - Assistant message count in session index

		ini_set('memory_limit','4906M');

		$ESConnLib = $this->load->library("trackingMIS/elasticSearch/ESConnectionLib");
    	$ES6Client = $ESConnLib->getShikshaESServerConnection();
    	$ES5Client = $ESConnLib->getESServerConnectionWithCredentials();

    	$endDate = $this->_getLastDocInShikshaABTestIndex($ES5Client);  // end date to process session data
    	$startDate = date('Y-m-d H:i:s', strtotime('-48 hours'));  // today and previous day
		$startDate = convertDateISTtoUTC($startDate);
		$startDate = str_replace(" ", "T", $startDate);

		// Get unprocessed sessions 
		$params = array();
		$params['index'] = SESSION_INDEX_NAME;
		$params['type'] = 'session';
		
		$startDateFilter = array();
		$startDateFilter['range']['startTime']['gte'] = $startDate;
		$startDateFilter['range']['startTime']['lte'] = $endDate;

		$mustFilters = array();
		$mustFilters[] = $startDateFilter;
		$mustFilters[] = array("term" => array("isStudyAbroad" => "no"));

		/**
 		 * For unprocessed, we check whether SAMessageCount field exists or missing for national users
 		 * If SAMessageCount field exists, it has been processed, else it's unprocessed
 		 */
		$unprocessedFilter = array();
		$unprocessedFilter['exists']['field'] = 'SAabVarient';

		$params['body']['query']['bool']['filter']['bool']['must'] = $mustFilters;
		$params['body']['query']['bool']['filter']['bool']['must_not'][] = $unprocessedFilter;
		$params['body']['_source'] = array('sessionId');
		$params['body']['size'] = 500000;
		//_p(json_encode($params));die;
		$search = $ES6Client->search($params);
		$sessionIds = array();
		$sessionMapping = array();
		foreach($search['hits']['hits'] as $result) {
    		$sessionId = $result['_source']['sessionId'];
    		$sessionDocumentId = $result['_id'];
    		$sessionIds[] = $sessionId;
    		$sessionMapping[$sessionId] = $sessionDocumentId;
		}
		unset($search);
		//_p($sessionIds);die;
		/**
		 * Process in batches
		 */
		//get document form ES 
        $params = array();
		$params['index'] = SESSION_INDEX_NAME;
		$params['type'] = 'session';

		$sessionIdsChunk = array_chunk($sessionIds, 100);
		$sessionIds = array();
				
		foreach ($sessionIdsChunk as $key => $chunk) {
			$params['body'] = array();

			// search these session ids in shiksha_assistant_conversations
			$esResult = array();
			$esResult = $this->_getSessionDetailsFromShikshaABTestIndex($chunk, $ES5Client);
			$bulkReqRequired = 0;
			foreach ($esResult as $sessionId => $abVarient) {
				$bulkReqRequired = 1;
				$params['body'][] = array('update' => array('_id' => $sessionMapping[$sessionId]));
                $newDoc = array("SAabVarient" => $abVarient);
                $params['body'][] = array(
	                'doc_as_upsert' => false,
	                'doc' => $newDoc
				);
			}
			if($bulkReqRequired == 1){
				$indexResponse = $ES6Client->bulk($params);	
				$bulkReqRequired = 0;
			}

		}
    }

    private function _convertDateISTtoUTCForES($date){
    	$date = convertDateISTtoUTC($date);
    	$date = str_replace(" ", "T", $date);
    	return $date;
    }

    public function prepareActiveUsersData(){
    	$ESConnLib = $this->load->library("trackingMIS/elasticSearch/ESConnectionLib");
    	$ESClient = $ESConnLib->getShikshaESServerConnection();
    	/*
			site cut : study abroad /national
			sourceApplication : desktop / mobile / app
			type : visitor / user
    	*/
		$activeUsersData = array();
		$startDay = 1;
		$yesterdayDate = date('Y-m-d', strtotime('-'.$startDay.' day'));
		$yesterdayDate = str_replace(" ", "T", $yesterdayDate);
		$day_1 = $startDay;
		$day_7 = $startDay + 7 -1 ;
		$day_14 = $startDay + 14 -1 ;
		$day_28 = $startDay + 28 -1;

		$inputData = array(
			'Domestic' => array("Desktop","Mobile","App","All"),
			'Study Abroad' => array("Desktop","Mobile","All"),
			'All' => array("Desktop","Mobile","App","All"),
		);
		$types = array("visitor","user");
		$activeUsersRanges = array(
			'1_day' => array(
				'startDate' => $this->_convertDateISTtoUTCForES(date('Y-m-d', strtotime('-'.$day_1.' day')).' 00:00:00'), 
				"endDate" => $this->_convertDateISTtoUTCForES(date('Y-m-d', strtotime('-'.$day_1.' day')).' 23:59:59')
			),
			'7_day' => array(
				'startDate' => $this->_convertDateISTtoUTCForES(date('Y-m-d', strtotime('-'.$day_7.' day')).' 00:00:00'), 
				"endDate" => $this->_convertDateISTtoUTCForES(date('Y-m-d', strtotime('-'.$day_1.' day')).' 23:59:59')
			),
			'14_day' => array(
				'startDate' => $this->_convertDateISTtoUTCForES(date('Y-m-d', strtotime('-'.$day_14.' day')).' 00:00:00'), 
				"endDate" => $this->_convertDateISTtoUTCForES(date('Y-m-d', strtotime('-'.$day_1.' day')).' 23:59:59')
			),
			'28_day' => array(
				'startDate' => $this->_convertDateISTtoUTCForES(date('Y-m-d', strtotime('-'.$day_28.' day')).' 00:00:00'), 
				"endDate" => $this->_convertDateISTtoUTCForES(date('Y-m-d', strtotime('-'.$day_1.' day')).' 23:59:59')
			)
		);
		
		$esDocumentData = array();
		$queryExecutionTime = array();
		foreach ($inputData as $site => $sourceApplicationDetails) {
			foreach ($sourceApplicationDetails as $sourceApplication) {
				foreach ($types as $type) {
					foreach ($activeUsersRanges as $activeUsersRange => $dateRange) {
						$activeUsersData[$site][$sourceApplication][$type][$activeUsersRange] = $this->_fetchActiveUsersFromES($site, $sourceApplication, $type, $dateRange, $ESClient);
					}
				}
			}
		}

		foreach ($activeUsersData as $site => $sourceApplicationDetails) {
			foreach ($sourceApplicationDetails as $sourceApplication => $types) {
				foreach ($types as $type => $activeUserDateRange) {
					$esDocumentData[] = array(
						"site" => $site,
						"sourceApplication" => $sourceApplication,
						"type" => $type,
						"1_day" => $activeUserDateRange["1_day"]["activeUsers"],
						"7_day" => $activeUserDateRange["7_day"]["activeUsers"],
						"14_day" => $activeUserDateRange["14_day"]["activeUsers"],
						"28_day" => $activeUserDateRange["28_day"]["activeUsers"],
						"date" => $yesterdayDate
					);
					$queryExecutionTime[] = array(
						"site" => $site,
						"sourceApplication" => $sourceApplication,
						"type" => $type,
						"1_day" => $activeUserDateRange["1_day"]["timeTaken"],
						"7_day" => $activeUserDateRange["7_day"]["timeTaken"],
						"14_day" => $activeUserDateRange["14_day"]["timeTaken"],
						"28_day" => $activeUserDateRange["28_day"]["timeTaken"],
					);
				}
				
			}
		}

		// index documet to elasticsearch
		$i = 1;
		$params = array();
		foreach($esDocumentData as $doc) {
            $params['body'][] = array('index' => array(
                                    '_index' => ES_ACTIVE_USER_INDEX,
                                    '_type' =>  "_doc"
                                )
                            );


            $params['body'][] = $doc;
            if ($i % 500 == 0) {
                $indexingResponse = $ESClient->bulk($params);
                $params = array();
                $params['body'] = array();
                unset($indexingResponse);
            }
            $i++;
        }
        if (!empty($params['body'])) {
            $indexingResponse = $ESClient->bulk($params);
        }

        // send mail
        mail('praveen.singhal@99acres.com','Active users cron execution time ', print_r($queryExecutionTime, true));
        error_log(print_r($queryExecutionTime, true));
    }

    private function _fetchActiveUsersFromES($site, $sourceApplication, $type, $dateRange, $ESClient){
    	$params = array();
    	$params['index'] = PAGEVIEW_INDEX_NAME_REALTIME_SEARCH;
    	$params['type'] = "pageview";
    	$params['size'] = 0;
    	$params['body'] = array();
    	$mustFilters = array();

    	//date range filter
    	$mustFilters[] = array("range" => array("visitTime" => array("gte" => $dateRange['startDate'],"lte" => $dateRange['endDate'])));

    	// site filter
    	if(!empty($site) && $site != "All"){
    		$isStudyAbroad = ($site == "Study Abroad")?"yes":"no";
	    	$mustFilters[] = array("term" => array("isStudyAbroad" => $isStudyAbroad));
    	}

    	// sourceApplication filter
    	if(!empty($sourceApplication) && $sourceApplication != "All"){
    		$isMobile = "yes";
	    	if($sourceApplication == "Desktop"){
	    		$isMobile = "no";
	    	}else if($sourceApplication == "App"){
	    		$isMobile = "app";
	    	}
	    	$mustFilters[] = array("term" => array("isMobile" => $isMobile));
    	}

    	$activeUserField = ($type == "visitor")?"visitorId":"userId";

		$params['body']['query']['bool']['filter']['bool']['must'] = $mustFilters;
		$params['body']['aggs']['activeUsers']['cardinality']['field'] = $activeUserField;
		//_p(json_encode($params));
		$search = $ESClient->search($params);
		return array(
			"timeTaken" => $search['took'],
			"activeUsers" => $search['aggregations']['activeUsers']['value']
		);
    }
}
