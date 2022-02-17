<?php
class logJSErrorsData extends MX_Controller
{
	function logJSErrors()
	{
		$message = isset($_REQUEST['msg'])?$_REQUEST['msg']:'';
		$sourceUrl = isset($_REQUEST['url'])?$_REQUEST['url']:'';
		$lineNumber = isset($_REQUEST['line'])?$_REQUEST['line']:'';
		$columnNumber = isset($_REQUEST['col'])?$_REQUEST['col']:'';
		$exception = isset($_REQUEST['exception'])?$_REQUEST['exception']:'';
		$methodName = isset($_REQUEST['methodName'])?$_REQUEST['methodName']:'';
		$className = isset($_REQUEST['className'])?$_REQUEST['className']:'';
		$moduleName = isset($_REQUEST['moduleName'])?$_REQUEST['moduleName']:'';
		$currentUrl = isset($_REQUEST['currentUrl'])?$_REQUEST['currentUrl']:'';
		$userAgent = isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'';

		$currentUrl = urldecode($currentUrl);
		//blocking bot calls 
		$blockUserAgent = array('bingbot','AdsBot','Googlebot','MsnBot','BingPreview');
		foreach ($blockUserAgent as $url) {
		    if (strpos($userAgent, $url) !== FALSE) { 
		        return true;
		    }
		}
		if($sourceUrl == 'undefined') {
			return true;
		}

		$this->load->config("app_monitor_config");
		$this->load->library('AppMonitor/AppMonitorLib');
		$appMonitorLib = new AppMonitorLib();

		$team = 'others';
		$team = $appMonitorLib->getMappedTeamPwa($sourceUrl);
		if(strpos($sourceUrl,'saassets') !==false){
			$team = 'studyabroad';
		}
		if(!empty($message) && !empty($sourceUrl))
		{
			//get team Name based on module name and controller name
			if( !empty($moduleName) && !empty($className))
			{
				if(preg_match('/shiksha/i', $sourceUrl)) {
					$team = $appMonitorLib->getMappedTeam($moduleName, $className);	
				}
			}
			

			// Check is it mobile request

			$isMobile = 'no';
			if(($_COOKIE['ci_mobile'] == 'mobile') || ($GLOBALS['flag_mobile_user_agent'] == 'mobile')) {
				$isMobile = 'yes';
			}
			$dataToLog = array(
						'message' => $message,
						'file' => $sourceUrl,
						'lineNum' => $lineNumber,
						'colNum' => $columnNumber,
						'exception' => $exception,
						'team' => $team,
						'mobile' => $isMobile,
		                'log_time' => date('Y-m-d H:i:s'),
		                'url' => $currentUrl,
		                'module' => $moduleName,
		                'controller' => $className,
		                'method' => $methodName,
		                'userAgent' => $userAgent
					);

			$this->config->load('amqp');
			$this->load->library("common/jobserver/JobManagerFactory");
			$jobManager = JobManagerFactory::getClientInstance();

			$dataToLog['logType'] = 'JSErrors';
			$jobManager->addBackgroundJob("LogMonitoringData", $dataToLog);
		}
	}	
}
?>
