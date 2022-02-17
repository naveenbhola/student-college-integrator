<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Log memory and server processing time usage for request
 */
function log_performance_metrics()
{
	//return;
	$ci = &get_instance();
	global $serverStartTime;
	global $timeForTracking;
	global $rtr_class;
	global $rtr_method;
	global $rtr_module;
	global $shikshaCacheProfile;


	$dbs = array();
	foreach (get_object_vars($ci) as $CI_object)
	{
		if (is_object($CI_object) && is_subclass_of(get_class($CI_object), 'CI_DB') )
		{
			$dbs[] = $CI_object;
		}
	}

	// track number of db queries fired on each db server
	$totalQueries = 0;
	$hostWiseQueryCount = array();
	foreach ($dbs as $db) {
		$totalQueries += count($db->queries);
		$hostWiseQueryCount[$db->hostname] += count($db->queries);
	}

	$ci->load->config("app_monitor_config");
	$ci->load->library('AppMonitor/AppMonitorLib');
	$appMonitorLib = new AppMonitorLib();

	$rtr_team = $appMonitorLib->getMappedTeam($rtr_module, $rtr_class);
	
	/**
	 * Server processing time (in milliseconds)
	 */
	$endserverTime   =  microtime(true);
	$timeForTracking = ($endserverTime - $serverStartTime) * 1000;
	
	/**
	 * Memory usage
	 */
	$memoryUsage = memory_get_peak_usage();

	/**
	 * Cache usage in bits
	 */
	$cacheUsage = intval($shikshaCacheProfile['cacheReadSize']) * 8;

	/**
	 * Is request from mobile
	 */	
	$isMobile = 'no';
	if(($_COOKIE['ci_mobile'] == 'mobile') || ($GLOBALS['flag_mobile_user_agent'] == 'mobile')) {
		$isMobile = 'yes';
	}

	$dataToLog = array(
				'time' => $timeForTracking,
				'memory' => $memoryUsage,
				'team' => $rtr_team,
				'module' => $rtr_module,
				'controller' => $rtr_class,
				'method' => $rtr_method,
				'url' => $_SERVER['REQUEST_URI'],
				'mobile' => $isMobile,
                'log_time' => date('Y-m-d H:i:s'),
				'cache' => intval($shikshaCacheProfile['cacheReadSize'])
			);

	/**
	 * Log to error log
	 */
    	error_log("SHK_ELOG:: ".implode('~e~',$dataToLog)." ");


    // if cron
    $dataToLog['cron'] = defined("CRON") && CRON == TRUE ?'yes' : 'no' ;
	/**
	 * If above threshold, send to RabbitMQ
	 */
	global $ENT_EE_TIMETHRESHOLD;
	global $ENT_EE_MEMORYTHRESHOLD;
	global $ENT_EE_CACHETHRESHOLD;
	global $ENT_EE_SQLQUERY_COUNT_THRESHOLD;
    
    $timeThreshold = $ENT_EE_TIMETHRESHOLD; //-- milliseconds
    $memoryThreshold = $ENT_EE_MEMORYTHRESHOLD;
    $cacheThreshold = $ENT_EE_CACHETHRESHOLD;
    $sqlQueryCountThreshold = $ENT_EE_SQLQUERY_COUNT_THRESHOLD;

	if(($timeForTracking > $timeThreshold || $memoryUsage > $memoryThreshold || $cacheUsage > $cacheThreshold || $totalQueries > $sqlQueryCountThreshold) && !in_array($rtr_module, array("AppMonitor"))) {

		
		try{
			$ci->config->load('amqp');
			$ci->load->library("common/jobserver/JobManagerFactory");
			$jobManager = JobManagerFactory::getClientInstance();
		
			if($timeForTracking > $timeThreshold) {

				/****** Profiler Code ********/
			    $profile = array();
				foreach ($ci->benchmark->marker as $key => $val)
				{					
					if (preg_match("/(.+?)_end/i", $key, $match))
					{
						if (isset($ci->benchmark->marker[$match[1].'_end']) AND isset($ci->benchmark->marker[$match[1].'_start'])){
							$profile[$match[1]] = $ci->benchmark->elapsed_time($match[1].'_start', $key) *1000;
						}
					}
				}
				$dataToLog['logDetails'] = json_encode($profile);
				/****** Profiler Code ********/
				
				$dataToLog['logType'] = 'SlowPage';
				$jobManager->addBackgroundJob("LogMonitoringData", $dataToLog);
			}
			if($memoryUsage > $memoryThreshold) {
				$dataToLog['logType'] = 'Memory';
				$jobManager->addBackgroundJob("LogMonitoringData", $dataToLog);
			}
			if($cacheUsage > $cacheThreshold) {
				$dataToLog['logType'] = 'Cache';
				$dataToLog['cache'] = $cacheUsage;
				$jobManager->addBackgroundJob("LogMonitoringData", $dataToLog);
            }
            if((defined("CRON") && CRON === TRUE) && !(defined("CRON_VALIDATED") && CRON_VALIDATED === TRUE)){
				$dataToLog['logType'] = 'CronVulnerility';
				$dataToLog['host']    = $_SERVER['HOSTNAME'];
				$jobManager->addBackgroundJob("LogMonitoringData", $dataToLog);
		    }
		    if($totalQueries > $sqlQueryCountThreshold) {
				$dataToLog['logType'] = 'SQLQueryCount';
				$dataToLog['totalQueries'] = $totalQueries;
				if(ENVIRONMENT == 'production'){
					$dataToLog['masterQueries'] = $hostWiseQueryCount['masterdb.shiksha.jsb9.net'];
					$dataToLog['slaveQueries'] = $totalQueries - $hostWiseQueryCount['masterdb.shiksha.jsb9.net'];
				}
				else{
					$dataToLog['masterQueries'] = 0;
					$dataToLog['slaveQueries'] = $totalQueries;
				}
				$dataToLog['hostWiseQueryCount'] = json_encode($hostWiseQueryCount);
				$jobManager->addBackgroundJob("LogMonitoringData", $dataToLog);
            }
		}
		catch(Exception $e){
			error_log("JOBQException: ".$e->getMessage());
		}	
	}
}
