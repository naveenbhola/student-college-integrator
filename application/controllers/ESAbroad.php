<?php

exit();
require_once(FCPATH.'vendor/autoload.php');

class ESAbroad extends MX_Controller
{
	function scanpv($startDate, $endDate)
	{
		$logFile = "/home/vikasa/esrun/logs/log.".$startDate;
	
        	$this->dbLibObj = DbLibCommon::getInstance('User');
        	$dbHandle = $this->_loadDatabaseHandle();
    
        	//$startDate = '2015-10-01';
        	//$endDate = '2015-11-01';
        
		ini_set('memory_limit', '4096M');
		set_time_limit(0);

		$clientParams = array();
		$clientParams['hosts'] = array(ELASTIC_SEARCH_HOST);
		$client = new Elasticsearch\Client($clientParams);

		$localClientParams = array();
		$localClientParams['hosts'] = array(ELASTIC_SEARCH_HOST);
		$localClient = new Elasticsearch\Client($localClientParams);

        	$start = microtime(true);

		/**
         	 * Process one day at a time
         	 */ 
        	$currentDate = $startDate;
        	while($currentDate < $endDate) {
            
            		$nextDate = date('Y-m-d', strtotime("+1 day", strtotime($currentDate)));
			error_log("Processing ".$currentDate." to ".$nextDate."\n", 3, $logFile);
            
            		$batchSize = 2000;
            		$offset = 0;
            
            		$indexParams = array();
            		$indexParams['body'] = array();
            
            		while(true) {
				error_log("Processing offset ".$offset."\n", 3, $logFile);
                		$params = array();
                		$params['index'] = 'trafficdata_pageviews_new';
                		$params['type'] = 'pageview';
                
                		$startDateFilter = array();
                		$endDateFilter = array();
                		$abroadFilter = array();
                
                		$startDateFilter['range']['visitTime']['gte'] = $currentDate;
                		$endDateFilter['range']['visitTime']['lt'] = $nextDate;
                		//$abroadFilter['match']['isStudyAbroad'] = 'yes';

				$params['body']['query']['filtered']['filter']['bool']['must'][] = $startDateFilter;
                		$params['body']['query']['filtered']['filter']['bool']['must'][] = $endDateFilter;
                		//$params['body']['query']['filtered']['filter']['bool']['must'][] = $abroadFilter;
                
                		$params['body']['sort']['visitTime']['order'] = 'asc';
                
                		$params["size"] = $batchSize;
                		$params["from"] = $offset * $batchSize;
                		$search = $client->search($params);
                
                		if(count($search['hits']['hits']) > 0) {
                    			foreach($search['hits']['hits'] as $result) {
                        			$indexParams['body'][] = array('index' => array(
                                    			'_index' => 'shiksha_trafficdata_pageviews',
                                    			'_type' => 'pageview',
                                    			)
                                		);

                        			$indexParams['body'][] = $result['_source'];
                    			}
                    
                    			$indexResponse = $localClient->bulk($indexParams);
                    			$indexParams = array();
                    			$indexParams['body'] = array();
                    			unset($indexResponse);
                		}
                		else {
                    			break;
               			}
                		error_log("Processed offset ".$offset."\n", 3, $logFile);
		                $offset++;
			}
			error_log("Processed ".$currentDate." to ".$nextDate."\n", 3, $logFile);
            		$currentDate = $nextDate;
        	}
        
        	$end = microtime(true);
       		error_log("\n\n".($end-$start)."\n", 3, $logFile);
	}
}	
