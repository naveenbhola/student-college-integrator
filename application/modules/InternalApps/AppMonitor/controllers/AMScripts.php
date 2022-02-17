<?php

require 'AppMonitorAbstract.php';

class AMScripts extends AppMonitorAbstract
{
	function __construct()
	{
		parent::__construct();
	}
	
	function doCronTeamMapping()
	{
		global $ENT_EE_SERVERS;
    		$servers = $ENT_EE_SERVERS;
		
		$moduleControllerTeamMapping = $this->getModuleControllerTeamMapping(TRUE, TRUE);
		$allCronErrors = $this->model->getAllCronErrors();
		
		
		foreach($allCronErrors as $result) {
			$cronId = $result['id'];
			$cronPath = $this->appMonitorLib->getCronPath($result['cron']);
			$team = $this->appMonitorLib->getCronTeam($cronPath);
			//echo $cronPath."==".$team."<br />";
			$this->model->updateCronTeam($cronId, $team, $cronPath);
		}
	}
	
	function doMemoryTeamMapping()
	{
		global $ENT_EE_SERVERS;
    	$servers = $ENT_EE_SERVERS;
		
		$moduleControllerTeamMapping = $this->getModuleControllerTeamMapping(TRUE, TRUE);
		$results = $this->model->getAllHighMemory();
		
		foreach($results as $result) {
			
			$id = $result['id'];
			
			$moduleName = trim($result['module_name']) ? strtolower(trim($result['module_name'])) : 'global';
			$controllerName = strtolower(trim($result['controller_name']));
			
			$teamName = $moduleControllerTeamMapping[$moduleName][$controllerName] ? $moduleControllerTeamMapping[$moduleName][$controllerName] : 'others';
			
			echo $moduleName." || ".$controllerName." || ".$teamName."<br />";
			
			$this->model->updateMemoryTeam($id, $teamName);
		}
	}
	
	function doSlowTeamMapping()
	{
		global $ENT_EE_SERVERS;
    	$servers = $ENT_EE_SERVERS;
		
		$moduleControllerTeamMapping = $this->getModuleControllerTeamMapping(TRUE, TRUE);
		$results = $this->model->getAllSlowPages();
		
		foreach($results as $result) {
			
			$id = $result['id'];
			
			$moduleName = trim($result['module_name']) ? strtolower(trim($result['module_name'])) : 'global';
			$controllerName = strtolower(trim($result['controller_name']));
			
			$teamName = $moduleControllerTeamMapping[$moduleName][$controllerName] ? $moduleControllerTeamMapping[$moduleName][$controllerName] : 'others';
			
			echo $moduleName." || ".$controllerName." || ".$teamName."<br />";
			
			$this->model->updateSlowTeam($id, $teamName);
		}
	}


	function doExceptionTeamMapping()
        {
                global $ENT_EE_SERVERS;
        	$servers = $ENT_EE_SERVERS;

                $moduleControllerTeamMapping = $this->getModuleControllerTeamMapping(TRUE, TRUE);
                $results = $this->model->getAllExceptions(1);

                foreach($results as $result) {

                        $id = $result['id'];

                        $moduleName = trim($result['module_name']) ? strtolower(trim($result['module_name'])) : 'global';
                        $controllerName = strtolower(trim($result['controller_name']));

                        $teamName = $moduleControllerTeamMapping[$moduleName][$controllerName] ? $moduleControllerTeamMapping[$moduleName][$controllerName] : 'others';

                        echo $moduleName." || ".$controllerName." || ".$teamName."<br />";

                        $this->model->updateExceptionTeam($id, $teamName, 1);
                }
        }

	function logHttpStatusCodes($host, $date)
        {
                $lines = file("/tmp/httpStatusCodes");
                $httpStatusCodes = array();
                foreach($lines as $line) {
                        $line = str_replace("\n", "", $line);
                        list($code, $num) = explode(" ", $line);
                        $httpStatusCodes[$code] = $num;
                }
                $this->model->logHttpStatusCodes($httpStatusCodes, $host, $date);
                error_log($host);
                error_log(print_r($httpStatusCodes, true));
        }

    function logHttpStatusCodesDetails($host)
        {
                $lines = file("/tmp/httpStatusCodesDetails");
                $httpStatusCodes = array();

                foreach($lines as $line) {
                        $line = str_replace("\n", "", $line);
                        $explodedData = explode(" ", $line);
                        $request_time = str_replace(array('[',']'), array('',''), $explodedData[4]);
                        //$request_time = str_replace(']', '', $request_time);
                        $request_time = explode("+", $request_time);
                        $request_time = $request_time[0];
                        $request_time = preg_replace('/:/', " ", $request_time, 1);
                        $request_time = str_replace('/', '-', $request_time);
                        $request_time = date("Y-m-d H:i:s",strtotime($request_time));
                       
                        $httpStatusCodes[] = array(
                        	'status_code' => $explodedData[0],
                        	'transaction_id' => $explodedData[1],
                        	'request_method' => str_replace('"', '', $explodedData[2]),
                        	'request_uri' => $explodedData[3],
                        	'request_time' => $request_time,
                        	'host' => $host
                        );
                }
                // table : 
                $this->model->logHttpStatusCodesDetails($httpStatusCodes);
        }


	function logApplicationErrors()
	{
		$this->validateCron();
		$exceptionsLogged = file("/tmp/edLogs.log");
		foreach($exceptionsLogged as $exception) {
			$parts = explode("~e~",$exception);
			if($parts[0] == 'Exception') {
				$this->logExceptions($parts);
			}
			else if($parts[0] == 'DBError') {
				$this->logDBErrors($parts);
			}
		}
		exec("truncate -s 0 /tmp/edLogs.log");
	}
	
	function logExceptions($data)
	{
		$module = $data[7];
		$controller = $data[8];
		if(!$module) {
			$module = $controller;
			$controller = '';
		}

		// if null referrer coming
		if(empty($data[6])){
			$data[6] = "";
		}
		$team = $this->appMonitorLib->getMappedTeam($module, $controller);
		
		$dbData = array(
			'error_class' => $data[1],
			'error_code' => $data[2],
			'source_file' => $data[3],
			'line_num' => $data[4],
			'url' => $data[5],
			'referrer' => $data[6],
			'team' => $team,
			'module_name' => $data[7],
			'controller_name' => $data[8],
			'method_name' => $data[9],
			'server' => $data[10],
			'isMobile' => $data[11],
			'exception_msg' => $data[12],
			'addition_date' => $data[13]
		);
		
		$this->model->logExceptions($dbData);
	}
	
	function logDBErrors($data)
	{
		$module = $data[8];
		$controller = $data[9];
		if(!$module) {
			$module = $controller;
			$controller = '';
		}
		$team = $this->appMonitorLib->getMappedTeam($module, $controller);
		
		$dbData = array(
			'error_msg' => $data[1],
			'error_code' => $data[2],
			'query' => $data[3],
			'filename' => $data[4],
			'line_num' => $data[5],
			'url' => $data[6],
			'referrer' => $data[7],
			'team' => $team,
			'module_name' => $data[8],
			'controller_name' => $data[9],
			'method_name' => $data[10],
			'server' => $data[11],
			'is_mobile' => $data[12],
			'addition_date' => $data[13]
		);
		
		$this->model->logDBErrors($dbData);
	}
	
	function logPerformanceMetrics()
	{
		$this->validateCron();
		$start = microtime(TRUE);
		set_time_limit(0);
		ini_set('memory_limit','8192M');
		
		global $timeBuckets;
		global $memoryBuckets;
		global $cacheBuckets;
		global $ENT_EE_TIMETHRESHOLD;
		global $ENT_EE_CACHETHRESHOLD;
		global $ENT_EE_MEMORYTHRESHOLD;

		$lines = file('/data/All_shiksha_apache_error_logs/logs/pmetrics');
		
		$numRequests = array();
		$timeMap = array();
		$memoryMap = array();
		$cacheMap = array();
		
		$timeHeap = array();
		$memoryHeap = array();
		$cacheHeap = array();
		
		$timeBucketMap = array();
		$memoryBucketMap = array();
		$cacheBucketMap = array();
		
		$timeThresholdMap = array();
		$memoryThresholdMap = array();
		$cacheThresholdMap = array();
		
		$tmap = array();
		$mmap = array();
		$cmap = array();

		$logDateMap = array();

		
		foreach($lines as $line) {
			$line = ltrim($line, '~e~');
			$parts = explode('~e~',$line);
			
			$time = $parts[0];
			$memory = $parts[1];
			$team = $parts[2];
			$module = $parts[3];
			$controller = $parts[4];
			$method = $parts[5];
			$url = $parts[6];
			$mobile = $parts[7] == 'yes' ? 1 : 0;
			$logTime = str_replace(array("\r","\n","\r\n"), "", $parts[8]);
			$cacheUsage = str_replace(array("\r","\n","\r\n"), "", $parts[9]);
			//-- Convert to bits
			$cacheUsage = intval($cacheUsage) * 8;
			
			
			// -- Data for details table
			$detailsData = array('url' => $url, 'logTime' => $logTime, 'mobile' => $mobile);
			
			if(!$module) {
				$module = 'global';
			}
			
			$key = $team."~".$module."~".$controller."~".$method;
			
			$tmap[$key][] = $time;
			$mmap[$key][] = $memory;
			$cmap[$key][] = $cacheUsage;

			$logDateMap[date('Y-m-d',strtotime($logTime))]++;
			
			/**
			 * Per key stats
			 */ 
			$numRequests[$key]++;
			$timeMap[$key] += $time/100;
			$memoryMap[$key] += number_format($memory/1000000, 3);			
			/**
			 * Cache read in Mb
			 */ 
			$cacheMap[$key] += number_format($cacheUsage/1000000, 3);
			
			if($time > $ENT_EE_TIMETHRESHOLD) {
				$timeThresholdMap[$key]++;
			}
			
			if($memory > $ENT_EE_MEMORYTHRESHOLD) {
				$memoryThresholdMap[$key]++;
			}
			
			if($cacheUsage > $ENT_EE_CACHETHRESHOLD) {
				$cacheThresholdMap[$key]++;
			}
			
			/**
			 * Time buckets
			 */ 
			foreach($timeBuckets as $timeVal) {
				if($time <= $timeVal) {
					$timeBucketMap[$key][$timeVal]++;
					break;
				}
			}
			
			/**
			 * Memory buckets
			 */ 
			foreach($memoryBuckets as $memoryVal) {
				if($memory <= $memoryVal) {
					$memoryBucketMap[$key][$memoryVal]++;
					break;
				}
			}
			
			/**
			 * Cache buckets
			 */ 
			foreach($cacheBuckets as $cacheVal) {
				if($cacheUsage <= $cacheVal) {
					$cacheBucketMap[$key][$cacheVal]++;
					break;
				}
			}
			
			/**
			 * Heap for top time consuming requests for each key
			 */ 
			if(!array_key_exists($key, $timeHeap)) {
				$timeHeap[$key] = new MyHeap;
			}
			
			if($timeHeap[$key]->count() < 100) {
				$timeHeap[$key]->insert(array('val' => $time, 'details' => $detailsData ));
			}
			else {
				$timeHeapTop = $timeHeap[$key]->top();
				if($time > $timeHeapTop['val']) {
					$timeHeap[$key]->extract();
					$timeHeap[$key]->insert(array('val' => $time, 'details' => $detailsData ));
				}
			}
			
			/**
			 * Heap for top memory consuming requests for each key
			 */
			if(!array_key_exists($key, $memoryHeap)) {
				$memoryHeap[$key] = new MyHeap;
			}
			
			if($memoryHeap[$key]->count() < 100) {
				$memoryHeap[$key]->insert(array('val' => $memory, 'details' => $detailsData ));
			}
			else {
				$timeHeapTop = $memoryHeap[$key]->top();
				if($memory > $timeHeapTop['val']) {
					$memoryHeap[$key]->extract();
					$memoryHeap[$key]->insert(array('val' => $memory, 'details' => $detailsData ));
				}
			}
			
			/**
			 * Heap for top cache consuming requests for each key
			 */
			if(!array_key_exists($key, $cacheHeap)) {
				$cacheHeap[$key] = new MyHeap;
			}
			
			if($cacheHeap[$key]->count() < 100) {
				$cacheHeap[$key]->insert(array('val' => $cacheUsage, 'details' => $detailsData ));
			}
			else {
				$cacheHeapTop = $cacheHeap[$key]->top();
				if($cacheUsage > $cacheHeapTop['val']) {
					$cacheHeap[$key]->extract();
					$cacheHeap[$key]->insert(array('val' => $cacheUsage, 'details' => $detailsData ));
				}
			}
		}
		
		arsort($logDateMap);
		reset($logDateMap);
		$logDate = key($logDateMap);

		foreach($numRequests as $key => $num) {
			list($team, $module, $controller, $method) = explode('~', $key);
			
			$averageTime = ceil(($timeMap[$key] / $num) * 100);
			$averageMemory = ceil(($memoryMap[$key] / $num) * 1000000);
			$averageCache = ceil(($cacheMap[$key] / $num) * 1000000);
			
			$slowPagesMasterData = array(
				'team' => $team,
				'module_name' => $module,
				'controller_name' => $controller,
				'method_name' => $method,
				'total_hits' => $num,
				'average_time' => $averageTime,
				'bucket_data' => json_encode($timeBucketMap[$key]),
				'threshold' => $ENT_EE_TIMETHRESHOLD,
				'num_above_threshold' => intval($timeThresholdMap[$key]),
				'log_date' => $logDate
			);
			
			$id = $this->model->logSlowPagesMasterData($slowPagesMasterData);
			
			foreach($timeHeap[$key] as $heapVal) {
				$slowPagesDetailsData = array(
					'slowPageId' => $id,
					'url' => $heapVal['details']['url'],
					'referrer' => $heapVal['details']['referer'],
					'is_mobile' => $heapVal['details']['mobile'],
					'time_taken' => $heapVal['val'],
					'occurence_time' => $heapVal['details']['logTime']
				);
				
				$this->model->logSlowPagesDetailsData($slowPagesDetailsData);
			}
			
			$highMemoryMasterData = array(
				'team' => $team,
				'module_name' => $module,
				'controller_name' => $controller,
				'method_name' => $method,
				'total_hits' => $num,
				'average_memory' => $averageMemory,
				'bucket_data' => json_encode($memoryBucketMap[$key]),
			  	'threshold' => $ENT_EE_MEMORYTHRESHOLD,
			  	'num_above_threshold' => intval($memoryThresholdMap[$key]),
				'log_date' => $logDate
			);
			
			$id = $this->model->logHighMemoryMasterData($highMemoryMasterData);
			
			foreach($memoryHeap[$key] as $heapVal) {
				$highMemoryDetailsData = array(
					'pageId' => $id,
					'url' => $heapVal['details']['url'],
					'referrer' => $heapVal['details']['referer'],
					'is_mobile' => $heapVal['details']['mobile'],
					'memory_occupied' => $heapVal['val'],
					'occurence_time' => $heapVal['details']['logTime']
				);
				
				$this->model->logHighMemoryDetailsData($highMemoryDetailsData);
			}
			
			$highCacheMasterData = array(
				'team' => $team,
				'module_name' => $module,
				'controller_name' => $controller,
				'method_name' => $method,
				'total_hits' => $num,
				'average_cache' => $averageCache,
				'bucket_data' => json_encode($cacheBucketMap[$key]),
			  	'threshold' => $ENT_EE_CACHETHRESHOLD,
			  	'num_above_threshold' => intval($cacheThresholdMap[$key]),
				'log_date' => $logDate
			);
			
			$id = $this->model->logHighCacheMasterData($highCacheMasterData);
			
			foreach($cacheHeap[$key] as $heapVal) {
				$highCacheDetailsData = array(
					'pageId' => $id,
					'url' => $heapVal['details']['url'],
					'referrer' => $heapVal['details']['referer'],
					'is_mobile' => $heapVal['details']['mobile'],
					'cache_size' => $heapVal['val'],
					'occurence_time' => $heapVal['details']['logTime']
				);
				
				$this->model->logHighCacheDetailsData($highCacheDetailsData);
			}
		}
		
		$end = microtime(TRUE);
		error_log("TIME TAKEN:: ".($end-$start));
		error_log("TOP MEMORY:: ".memory_get_peak_usage());
	}

	public function abnormalityOneTimeScript(){

		$date = $this->yesterdayDate;
		 for($k = 1; $k <= 30; $k++){
		 	$currentDate = date('Y-m-d',strtotime("-$k days",strtotime($date)));
	 	 	$this->dailyYesterdayScript($currentDate);
		 }

	}

	public function dailyYesterdayScript($currentDate=''){

		$this->validateCron();
		if($currentDate == ''){
			$currentDate = $this->yesterdayDate;
		}
		$metricesTableMapping = array(
		   'slowpages' => 'slowpages_live',
		   'memory' => 'memorypages_live',
		   'exception' => 'exceptionLogs',
		   'dberror' => 'errorQueryLogs',
		   'slowquery' => 'slow_queries_live',
		   'cronerror' => 'cron_php_errors',
		   'jserror' => 'jsErrors',
		   'heavycache' => 'heavycachepages_live'
		  );

		$dbColumnMapping = array(
		  'slowpages' => 'log_time',
		  'memory' => ' log_time',
		  'exception' => 'addition_date',
		  'dberror' => 'addition_date',
		  'slowquery' => 'record_time',
		  'cronerror' => 'time '  ,
		  'jserror' => 'addition_date',
		  'heavycache' => 'log_time'
		  );
		 $q_cnt = 0;
		  
		  $metricesWiseData  = array();
		    foreach ($metricesTableMapping as $metrices => $dbTable) {
		  
		        for ($i=0;$i<=23;$i++) {
		          for ($j=0;$j<=55;$j=$j+5) {
		                $d = $currentDate." ".$i.':'.str_pad($j, 2, '0', STR_PAD_LEFT).":00";
		                $metricesWiseData[$metrices][$d] = 0;
		              }
		        }
		        
		       $result_array = $this->model->dataEveryFiveMinutesAbnormality($dbColumnMapping,$metrices,$metricesTableMapping,$currentDate);
		       $result  = array();
		       foreach ($result_array as $key => $value) {
		       		$result[strtotime($value['formated_date'])] = $value['cnt'];
		       }
		       

		        $finalcount = 0;
		        foreach ($metricesWiseData[$metrices] as $datetime => $value) {
		          $timestamp = strtotime($datetime);
		          $metricesWiseData[$metrices][$datetime] = $finalcount;
		          foreach ($result as $dbTimeStamp => $count) {
		            
		            if($timestamp >= $dbTimeStamp){      
		              $finalcount += $count;      
		              $metricesWiseData[$metrices][$datetime] = $finalcount;
		              unset($result[$dbTimeStamp]);
		            } else {
		              $metricesWiseData[$metrices][$datetime] = $finalcount;
		              break;
		            }

		          }


		          $insert_array[$q_cnt]['datetime'] = $datetime;
		          $insert_array[$q_cnt]['count'] = $metricesWiseData[$metrices][$datetime];
		          $insert_array[$q_cnt]['type'] = $metrices;
		          $q_cnt++;

		        }

		    }
    		 $this->model->insertMinuteWiseData($insert_array);
 			unset($insert_array);
	}

	function trackClientSidePerformanceMetrics(){

		$urlIndex = date('H');
		$urlIndex = ($urlIndex)%10;
		
		global $ENT_CLIENTSIDE_PERF_METRICS;

		$pageSpeedInsights = "https://www.googleapis.com/pagespeedonline/v2/runPagespeed?key=AIzaSyCLBt11noxWZvzqrf89SB9wynMqo8uG_pM";
		foreach ($ENT_CLIENTSIDE_PERF_METRICS as $page => $urls) {
				
				if(count($urls) >= $urlIndex)
					$url = $urls[$urlIndex];
				else
					$url = $urls[0];

				// desktop
				$requestUrl = $pageSpeedInsights."&strategy=desktop&url=".$url;
				$response   = $this->appMonitorLib->curl($requestUrl);
				$response = (array)json_decode($response, true);
				
				if(!($response['code'] == 400 || !empty($response['error']) || empty($response['pageStats']) )){
					$data       = $this->appMonitorLib->formatClientSideTrackingData($response, $url, $page, 'desktop');
					$this->model->trackClientSidePerformanceMetrics($data);
				}
				else{
					error_log("\n".date("d-m-y h:i:s")."PageSpeedInsights : Error ::  URL = ".$requestUrl." and Response = ".print_r($response, true), 3, '/tmp/clientSideError.log');
				}
				
				// mobile
				$requestUrl = $pageSpeedInsights."&strategy=mobile&url=".$url;
				$response   = $this->appMonitorLib->curl($requestUrl);
				$response = (array)json_decode($response, true);

				if(!($response['code'] == 400 || !empty($response['error']) || empty($response['pageStats']) )){
					$data       = $this->appMonitorLib->formatClientSideTrackingData($response, $url, $page, 'mobile');
					$this->model->trackClientSidePerformanceMetrics($data);
				}
				else{
					error_log("\n".date("d-m-y h:i:s")."PageSpeedInsights : Error ::  URL = ".$requestUrl." and Response = ".print_r($response, true), 3, '/tmp/clientSideError.log');
				}
		}
	}

	function aggregateDaysClientSidePerformanceParams(){

		$this->model->aggregateDailyClientSideAggregateData();
	}

	function vulnerableCronAlert()
    {
    		$this->validateCron();
            $date = date("Y-m-d", strtotime("-1 day"));
            $data = $this->model->getVulnerableCrons($date);
            
            $teamWiseCount = array();
            foreach ($data as $value) {
            	$teamWiseCount[$value['team']]++;
            }

            arsort($teamWiseCount);
			if(count($data) > 0) {

				$message = "<html><body>";
                $message = "<h3>Vulnerable Crons : ".$date."</h3>";
                $message .= "<table border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse;'>";
                $message .= "<tr>";
            	$message .= "<th>Team</th>";
            	$message .= "<th>Number of Crons Vulnerable</th>";
            	$message .= "</tr>";

                foreach ($teamWiseCount as $key => $value) {
                	$message .= "<tr>";
                	$message .= "<td>".addslashes($key)."</td>";
                	$message .= "<td>".addslashes($value)."</td>";
                	$message .= "</tr>";
                }
                $message .= "</table><br/><br/>";

				$message .= "<table border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse;'>";
				$message .= "<tr>";
				$message .= "<th>#</th>";
            	$message .= "<th>Team</th>";
            	$message .= "<th>Module Name</th>";
            	$message .= "<th>Controller Name</th>";
            	$message .= "<th>Method Name</th>";
            	$message .= "<th>Total Occurences</th>";
            	$message .= "</tr>";

                foreach ($data as $key => $value) {
                	$message .= "<tr>";
                	$message .= "<td>".($key+1)."</td>";
                	$message .= "<td>".addslashes($value['team'])."</td>";
                	$message .= "<td>".addslashes($value['module_name'])."</td>";
                	$message .= "<td>".addslashes($value['controller_name'])."</td>";
                	$message .= "<td>".addslashes($value['method_name'])."</td>";
                	$message .= "<td>".addslashes($value['cnt'])."</td>";
                	$message .= "</tr>";
                }

                $message .= "</table>";
                $message .= "</body></html>";
                
                $email_from = "Production Critical Alerts <ProductionCriticalAlerts@shiksha.com>"; // Who the email is from
                $email_subject = "Crons Vulnerable from Browser"; // The Subject of the email 
                $email_message = $message;

                $email_to = "ShikshaProdEmergencyTeam@shiksha.com";//"romil.goel@shiksha.com,ankur.gupta@shiksha.com,amit.kuksal@shiksha.com,sukhdeep.kaur@99acres.com,naveen.bhola@shiksha.com,g.ankit@shiksha.com,abhinav.k@shiksha.com"; // Who the email is to
                // $email_to = "romil.goel@shiksha.com"; // Who the email is to

                $headers = "From: ".$email_from;
                $semi_rand = md5(time());
                $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
                $headers .= "\nMIME-Version: 1.0\n" .
                            "Content-Type: multipart/mixed;\n" .
                            " boundary=\"{$mime_boundary}\"";

                $email_message .= "This is a multi-part message in MIME format.\n\n" .
"--{$mime_boundary}\n" .
"Content-Type:text/html; charset=\"iso-8859-1\"\n" .
"Content-Transfer-Encoding: 7bit\n\n" .
$email_message .= "\n\n";

                @mail($email_to, $email_subject, $email_message, $headers);

		}
    }

    function populateHighSQLCount(){

    	$this->validateCron();
    	$realtimedata = $this->model->getDashboardHighSQLStats($this->realtimeDate);
    	$this->appmonitorcache->storeRealHighSQLCount($realtimedata);
    }
}

class MyHeap extends SplHeap
{
	public function  compare( $value1, $value2 )
	{ 
        return ( intval($value2['val']) - intval($value1['val']) ); 
    } 		
}
