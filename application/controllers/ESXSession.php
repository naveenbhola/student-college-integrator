<?php

require_once(FCPATH.'vendor/autoload.php');

class ESXSession extends MX_Controller
{
	function scanpv()
	{
        	$startDate =   '2017-04-03T17:00:00';
        	$endDate =   '2017-04-03T19:00:00';
        
		ini_set('memory_limit', '4096M');
		set_time_limit(0);

		$clientParams = array();
		$clientParams['hosts'] = array(ELASTIC_SEARCH_HOST);
		$client = new Elasticsearch\Client($clientParams);

		$localClientParams = array();
		$localClientParams['hosts'] = array(ELASTIC_SEARCH_HOST);
		$localClient = new Elasticsearch\Client($localClientParams);

        	$start = microtime(true);

            		$batchSize = 3000;
            		$offset = 0;
            
            		$indexParams = array();
            		$indexParams['body'] = array();
            
            		while(true) {
                		$params = array();
                		$params['index'] = 'trafficdata_sessions_new';
                		$params['type'] = 'session';
                
                		$startDateFilter = array();
                		$endDateFilter = array();
                
                		$startDateFilter['range']['startTime']['gte'] = $startDate;
                		$endDateFilter['range']['startTime']['lt'] = $endDate;

				$params['body']['query']['filtered']['filter']['bool']['must'][] = $startDateFilter;
                		$params['body']['query']['filtered']['filter']['bool']['must'][] = $endDateFilter;
                
                		$params['body']['sort']['startTime']['order'] = 'asc';
                
                		$params["size"] = $batchSize;
                		$params["from"] = $offset * $batchSize;
                		$search = $client->search($params);
                
                		if(count($search['hits']['hits']) > 0) {
					error_log($offset." - ".count($search['hits']['hits']));
					
                    			foreach($search['hits']['hits'] as $result) {
                        			$indexParams['body'][] = array('index' => array(
                                    			'_index' => 'SESSION_INDEX_NAME',
                                    			'_type' => 'session',
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
		                $offset++;
			} 
	}
}	
