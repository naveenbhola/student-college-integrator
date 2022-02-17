<?php
class LogPerformance extends MX_Controller{
    function __construct()
    {
	global $shikshaCacheProfile;

	$this->load->config("app_monitor_config");
	$this->load->library('AppMonitor/AppMonitorLib');
	$this->appMonitorLib = new AppMonitorLib();
    }

   function logPerformace(){
	$rtr_team = $this->input->post('teamname');
	
	/**
	 * Server processing time (in milliseconds)
	 */
	$endserverTime   =  microtime(true);
	$timeForTracking = $this->input->post('endserverTime') - $this->input->post('serverStartTime');
	$rtr_class = $rtr_method = $rtr_module = $this->input->post('module');
	
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
				'url' => $this->input->post('requestedUrl'),
				'mobile' => $isMobile,
                'log_time' => date('Y-m-d H:i:s'),
				'cache' => intval($shikshaCacheProfile['cacheReadSize'])
			);

	/**
	 * Log to error log
	 */
    	error_log("SHK_ELOG:: ".implode('~e~',$dataToLog)." ");

	/**
	 * If above threshold, send to RabbitMQ
	 */
	global $ENT_EE_TIMETHRESHOLD;
	global $ENT_EE_MEMORYTHRESHOLD;
	global $ENT_EE_CACHETHRESHOLD;
    
    $timeThreshold = $ENT_EE_TIMETHRESHOLD; //-- milliseconds
    $memoryThreshold = $ENT_EE_MEMORYTHRESHOLD;
    $cacheThreshold = $ENT_EE_CACHETHRESHOLD;

	if($timeForTracking > $timeThreshold || $memoryUsage > $memoryThreshold || $cacheUsage > $cacheThreshold) {
		try{
			$this->config->load('amqp');
			$this->load->library("common/jobserver/JobManagerFactory");
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
		}
		catch(Exception $e){
			error_log("JOBQException: ".$e->getMessage());
		}	
	}
   }
}
?>
