<?php

require 'AppMonitorAbstract.php';

class Dashboard extends AppMonitorAbstract
{
	function __construct()
	{
		parent::__construct();
	}
	
	function index()
	{
		global $ENT_EE_SERVERS;
    	$servers = $ENT_EE_SERVERS;
		
		$displayData['dashboardType'] = ENT_DASHBOARD_MAIN;
		
		$this->getDashboardData($displayData);	
		$displayData['cronsForLagMonitoring'] = $this->model->getCronsForLagMonitoring();

		// set exclude cron cookie by default
		if(!isset($_COOKIE['excludeCron'])){
			setcookie('excludeCron', 1, time() + 15552000, '/', COOKIEDOMAIN);   
		}

		$this->load->view("AppMonitor/dashboard", $displayData);
	}
	
	function getDashboardData(&$data,$prevDayFlag=false)
	{
		global $SHIKSHA_PROD_SERVERS;
        global $BOT_STATUSES;
		
		$date = $this->realtimeDate;
		$displayData['prevDayFlag'] = $prevDayFlag;
		if($prevDayFlag){			
			$date = $this->yesterdayDate;
		}
		
 		$moduleControllerToTeamMap = $this->appMonitorLib->getModuleControllerTeamMapping();

 		// HTTP Status Code
 		$result = $this->model->getDashboardHTTPStatusCodeStats($this->yesterdayDate,500);
 		foreach ($result as $key => $value) {
 			$realtimecount += $value['count'];
 			$statusCodeWiseData[$value['status_code']] = intval($value['count']);
 		}

 		global $HTTPSTATUSCODES;
 		foreach ($HTTPSTATUSCODES as $key => $value) {
 			if(!isset($statusCodeWiseData[$key])){
 				$statusCodeWiseData[$key] = 0;
 			}
 		}
 		
		$realtime[ENT_DASHBOARD_TYPE_HTTPSTATUSCODES]['count'] = $realtimecount;
		$realtime[ENT_DASHBOARD_TYPE_HTTPSTATUSCODES]['data'] = $statusCodeWiseData;


		// Slow Pages
 		$teamWiseData = $this->initializeEmptyTeamwiseData();//array("mobile_app" => 0,"listings" => 0, "ugc" => 0 ,"studyabroad" => 0,"ldb" => 0, "common" => 0, "others" => 0, "Service" => 0);
 		
		$realtimedata = $this->model->getDashboardSlowPageStats($date);
		$realtimecount = 0;
		foreach($realtimedata as $realtimedataRow){
			$realtimecount += $realtimedataRow['count'];
			$teamWiseData[$realtimedataRow['team']] = intval($realtimedataRow['count']);
		}
 		
 		$realtime[ENT_DASHBOARD_TYPE_SLOWPAGES]['count'] = $realtimecount;
 		$realtime[ENT_DASHBOARD_TYPE_SLOWPAGES]['data'] = $teamWiseData;

		// High Memory
 		$teamWiseData = $this->initializeEmptyTeamwiseData();//array("mobile_app" => 0,"listings" => 0, "ugc" => 0 ,"studyabroad" => 0,"ldb" => 0, "common" => 0, "others" => 0, "Service" => 0);
		$realtimedata = $this->model->getDashboardHighMemoryStats($date);
		$realtimecount = 0;
		foreach($realtimedata as $realtimedataRow){
			$realtimecount += $realtimedataRow['count'];
			$teamWiseData[$realtimedataRow['team']] = intval($realtimedataRow['count']);
		}

 		$realtime[ENT_DASHBOARD_TYPE_MEMORY]['count'] = $realtimecount;
 		$realtime[ENT_DASHBOARD_TYPE_MEMORY]['data'] = $teamWiseData;
		
		// High Cache
 		$teamWiseData = $this->initializeEmptyTeamwiseData();//array("mobile_app" => 0,"listings" => 0, "ugc" => 0 ,"studyabroad" => 0,"ldb" => 0, "common" => 0, "others" => 0, "Service" => 0);
		$realtimedata = $this->model->getDashboardHighCacheStats($date);
		$realtimecount = 0;
		foreach($realtimedata as $realtimedataRow){
			$realtimecount += $realtimedataRow['count'];
			$teamWiseData[$realtimedataRow['team']] = intval($realtimedataRow['count']);
		}

 		$realtime[ENT_DASHBOARD_TYPE_CACHE]['count'] = $realtimecount;
 		$realtime[ENT_DASHBOARD_TYPE_CACHE]['data'] = $teamWiseData;

		// Exceptions
 		$teamWiseData = $this->initializeEmptyTeamwiseData();//array("mobile_app" => 0,"listings" => 0, "ugc" => 0 ,"studyabroad" => 0,"ldb" => 0, "common" => 0, "others" => 0, "Service" => 0);
		$realtimedata = $this->model->getDashboardExceptionStats($date);
		$realtimecount = 0;
		foreach($realtimedata as $realtimedataRow){
			$realtimecount += $realtimedataRow['count'];
			$teamWiseData[$realtimedataRow['team']] = intval($realtimedataRow['count']);
		}
	
 		$realtime[ENT_DASHBOARD_TYPE_EXCEPTION]['count'] = $realtimecount;
 		$realtime[ENT_DASHBOARD_TYPE_EXCEPTION]['data'] = $teamWiseData;

		// DB Errors
 		$teamWiseData = $this->initializeEmptyTeamwiseData();//array("mobile_app" => 0,"listings" => 0, "ugc" => 0 ,"studyabroad" => 0,"ldb" => 0, "common" => 0, "others" => 0, "Service" => 0);
		$realtimedata = $this->model->getDashboardDBErrorStats($date);
		$realtimecount = 0;
		foreach($realtimedata as $realtimedataRow){
			$realtimecount += $realtimedataRow['count'];
			$teamWiseData[$realtimedataRow['team']] = intval($realtimedataRow['count']);
		}

 		$realtime[ENT_DASHBOARD_TYPE_DB_ERROR]['count'] = $realtimecount;
 		$realtime[ENT_DASHBOARD_TYPE_DB_ERROR]['data'] = $teamWiseData;


		// Cron Errors	
		$teamWiseData = $this->initializeEmptyTeamwiseData();//array("mobile_app" => 0,"listings" => 0, "ugc" => 0 ,"studyabroad" => 0,"ldb" => 0, "common" => 0, "others" => 0, "Service" => 0);

                $realtimedata = $this->model->getDashboardCronErrorStats($date);
                $realtimecount = 0;
                foreach($realtimedata as $realtimedataRow){
                        $realtimecount += $realtimedataRow['count'];
                        $teamWiseData[$realtimedataRow['team']] = intval($realtimedataRow['count']);
                }

		$realtime[ENT_DASHBOARD_TYPE_CRON_ERROR]['count'] = $realtimecount;
		$realtime[ENT_DASHBOARD_TYPE_CRON_ERROR]['data'] = $teamWiseData;


		// Slow Queries	
		$serverWiseData = array("Master:81" => 0, "Slave01:71" => 0 ,"Slave02:72" => 0);

                $realtimedata = $this->model->getDashboardSlowQueryStats($date);
                $realtimecount = 0;
                foreach($realtimedata as $realtimedataRow){
                        $realtimecount += $realtimedataRow['count'];
                        $serverWiseData[$realtimedataRow['host']] = intval($realtimedataRow['count']);
                }

		$realtime[ENT_DASHBOARD_TYPE_SLOWQUERY]['count'] = $realtimecount;
		$realtime[ENT_DASHBOARD_TYPE_SLOWQUERY]['data'] = $serverWiseData;

		// SPEAR alerts
 		$serverWiseData = array();
		foreach($SHIKSHA_PROD_SERVERS as $serverKey => $serverVal) {
			$serverWiseData[$serverKey] = 0;
		}
		$realtimedata = $this->model->getDashboardSpearAlertStats($date);
		$realtimecount = 0;
		foreach($realtimedata as $realtimedataRow){
			$realtimecount += $realtimedataRow['count'];
			$serverWiseData[$realtimedataRow['host']] = intval($realtimedataRow['count']);
		}
	
 		$realtime[ENT_DASHBOARD_TYPE_SPEARALERTS]['count'] = $realtimecount;
 		$realtime[ENT_DASHBOARD_TYPE_SPEARALERTS]['data'] = $serverWiseData;
        
        // Bots
 		$statusWiseData = array();
		foreach($BOT_STATUSES as $statusKey => $statusVal) {
			$statusWiseData[$statusKey] = 0;
		}
		$realtimedata = $this->model->getDashboardBotReportStats($date);
		$realtimecount = 0;
		foreach($realtimedata as $realtimedataRow){
			$realtimecount += $realtimedataRow['count'];
			$statusWiseData[$realtimedataRow['status']] = intval($realtimedataRow['count']);
		}
	
 		$realtime[ENT_DASHBOARD_TYPE_BOTREPORT]['count'] = $realtimecount;
 		$realtime[ENT_DASHBOARD_TYPE_BOTREPORT]['data'] = $statusWiseData;
		
		//JS Errors
		$teamWiseData = $this->initializeEmptyTeamwiseData();//array("mobile_app" => 0,"listings" => 0, "ugc" => 0 ,"studyabroad" => 0,"ldb" => 0, "common" => 0, "others" => 0, "Service" => 0);
		$realtimedata = $this->model->getDashBoardJSErrorStats($date);
		$realtimecount = 0;
		foreach($realtimedata as $realtimedataRow){
			$realtimecount += $realtimedataRow['count'];
			$teamWiseData[$realtimedataRow['team']] = intval($realtimedataRow['count']);
		}
	
 		$realtime[ENT_DASHBOARD_TYPE_JS_ERROR]['count'] = $realtimecount;
 		$realtime[ENT_DASHBOARD_TYPE_JS_ERROR]['data'] = $teamWiseData;
 		$data['realtime'] = $realtime;

 		//SQLi Vulnerabilities
		$teamWiseData = $this->initializeEmptyTeamwiseData();//array("mobile_app" => 0,"listings" => 0, "ugc" => 0 ,"studyabroad" => 0,"ldb" => 0, "common" => 0, "others" => 0, "Service" => 0);
		$realtimedata = $this->model->getDashBoardSQLiStats();
		$realtimecount = 0;
		foreach($realtimedata as $realtimedataRow){
			$realtimecount += $realtimedataRow['count'];
			$teamWiseData[$realtimedataRow['team']] = intval($realtimedataRow['count']);
		}
	
 		$realtime[ENT_DASHBOARD_TYPE_SQLI]['count'] = $realtimecount;
 		$realtime[ENT_DASHBOARD_TYPE_SQLI]['data'] = $teamWiseData;
 		$data['realtime'] = $realtime;

 		// High SQL Queries
 		$teamWiseData = $this->initializeEmptyTeamwiseData();//array("mobile_app" => 0,"listings" => 0, "ugc" => 0 ,"studyabroad" => 0,"ldb" => 0, "common" => 0, "others" => 0, "Service" => 0);
 		
 		if(!$displayData['prevDayFlag'])
 			$realtimedata = $this->appmonitorcache->getRealHighSQLCount();
 		else
			$realtimedata = $this->model->getDashboardHighSQLStats($date);

		$realtimecount = 0;
		foreach($realtimedata as $realtimedataRow){
			$realtimecount += $realtimedataRow['count'];
			$teamWiseData[$realtimedataRow['team']] = intval($realtimedataRow['count']);
		}
 		
 		$realtime[ENT_DASHBOARD_TYPE_HIGH_SQL_QUERIES]['count'] = $realtimecount;
 		$realtime[ENT_DASHBOARD_TYPE_HIGH_SQL_QUERIES]['data'] = $teamWiseData;
 		$data['realtime'] = $realtime;

 		// Solr Errors
 		$teamWiseData = $this->initializeEmptyTeamwiseData();//array("mobile_app" => 0,"listings" => 0, "ugc" => 0 ,"studyabroad" => 0,"ldb" => 0, "common" => 0, "others" => 0, "Service" => 0);
		$realtimedata = $this->model->getDashboardSolrErrorStats($date);
		$realtimecount = 0;
		foreach($realtimedata as $realtimedataRow){
			$realtimecount += $realtimedataRow['count'];
			$teamWiseData[$realtimedataRow['team']] = intval($realtimedataRow['count']);
		}

 		$realtime[ENT_DASHBOARD_TYPE_SOLR_ERRORS]['count'] = $realtimecount;
 		$realtime[ENT_DASHBOARD_TYPE_SOLR_ERRORS]['data'] = $teamWiseData;
 		$data['realtime'] = $realtime;
 	}
	
	public function getCronErrorsCountModuleWise($errors,$moduleName='shiksha')
	{
 		$mapping = $this->mapCronErrorsToModules($errors);

 		$countArray = array();
 		global $ENT_EE_MODULES;
 		$modules = array_keys($ENT_EE_MODULES);
 	
 		if($moduleName == "shiksha"){
 			foreach ($modules as $key => $value) {
 				$countArray[$value] = count($mapping[$value]);
 			}	
 		}
		else {
			foreach ($modules as $key => $value) {
				if($value == $moduleName){
					$countArray[$value] = count($mapping[$value]);
				}
 			}	
 		}

 		return $countArray;
 	}
	
	public function mapCronErrorsToModules($errors)
	{
 		$mapping = array();
 		global $CRON_MAPPING;

 		foreach ($errors as $error) {
 			list($cronName) = explode(" ",$error['cron']);
 			list(,$cronName) = explode("=", $cronName);
 			$module = $CRON_MAPPING[$cronName];
 			if(!isset($module)){
 				$module = "others";
 			}

 			if(array_key_exists($module, $mapping)){
 				array_push($mapping[$module], $cronName);
 			} else {
 				$mapping[$module] = array($cronName);
 			}
 		}
 		
 		return $mapping;
 	}
	
	function getSlowQueriesModuleWise($result)
	{
 		$res = array();
 		$teamWiseData = array();
 		global $ENT_EE_SERVERS;

		foreach ($result as $value) {
			$res[$value['host']] = $value['count'];
		}

		// This is done to maintain the order of servers
		foreach ($ENT_EE_SERVERS as $key => $value) {
			if(array_key_exists($value, $res)){
				$teamWiseData[$key] = intval($res[$value]);
			}	else {
				$teamWiseData[$key] = 0;
			}	
		}

		return $teamWiseData;
 	}
	
	function updateDashBoard($day='today')
	{
 		$data = array();
 		if($day == "yesterday"){
 			$this->getDashboardData($data,true);
 		}
 		else{
 			$this->getDashboardData($data);
 		}
 		global $ENT_EE_SERVERS;
		global $SHIKSHA_PROD_SERVERS;
        global $BOT_STATUSES;
        global $HTTPSTATUSCODES;
 		$serverKeys = array_keys($ENT_EE_SERVERS);
        $botStatusKeys = array_keys($BOT_STATUSES);
		$prodServerKeys = array_keys($SHIKSHA_PROD_SERVERS);
		$httpStatusCodeKeys = array_keys($HTTPSTATUSCODES);
 		$realtime = $data['realtime'];
 		
 		foreach($realtime as $key=>$dataRow){

 			if($key == ENT_DASHBOARD_TYPE_SLOWQUERY){
 				$realtime[$key]['data'] = array("", $dataRow['data'][$serverKeys[0]], $dataRow['data'][$serverKeys[1]],$dataRow['data'][$serverKeys[2]]);	
 			}
			else if($key == ENT_DASHBOARD_TYPE_SPEARALERTS){
				$dataArr = array("");
				foreach($prodServerKeys as $prodServerKey) {
					$dataArr[] = $dataRow['data'][$prodServerKey];
				}
 				$realtime[$key]['data'] = $dataArr;	
 			}
            else if($key == ENT_DASHBOARD_TYPE_BOTREPORT){
				$dataArr = array("");
				foreach($botStatusKeys as $botStatusKey) {
					$dataArr[] = $dataRow['data'][$botStatusKey];
				}
 				$realtime[$key]['data'] = $dataArr;	
 			}else if($key == ENT_DASHBOARD_TYPE_HTTPSTATUSCODES){
				$dataArr = array("");
				foreach($httpStatusCodeKeys as $httpStatusCodeKey) {					
					$dataArr[] = $dataRow['data'][$httpStatusCodeKey];
				}
 				$realtime[$key]['data'] = $dataArr;	
 			}
			else{
 				// $realtime[$key]['data'] = array("", $dataRow['data']['mobile_app'], $dataRow['data']['listings'], $dataRow['data']['ugc'],$dataRow['data']['studyabroad'],$dataRow['data']['ldb'],$dataRow['data']['common'],$dataRow['data']['others'],$dataRow['data']['Service']); 			
 				global $ENT_EE_MODULES;
 				$teamsIdentifiers = array_keys($ENT_EE_MODULES);
 				$tempArr = array("");
 				foreach ($dataRow['data'] as $keynew => $valuenew) {
 					if(in_array($keynew, $teamsIdentifiers)){
 						$tempArr[] = $valuenew;
 					}
 				}
 				// _p($tempArr);die;
 				$realtime[$key]['data'] = $tempArr;
 			}
 		}
	
 		echo json_encode($realtime);
 	}
	
	public function getLag($cron)
	{
		if(ENVIRONMENT != 'production')
			echo 0;
		else
        	echo $this->model->getLag($cron);
    }
	
	function showTopUrls()
	{
		$charttype                   = $this->input->post("charttype");
		$selecteddate                = $this->input->post("date");
		$todate                = $this->input->post("todate");
		$module                = $this->input->post("module");
		$dashboardType               = $this->input->post("dashboardType");
		$sourceFile               = $this->input->post("sourceFile");
		$lineNum               = $this->input->post("lineNum");
		$selecteddate                = date("Y-m-d", strtotime($selecteddate));

		//below user input parameters used for appmonitoring js errors
		$msg 			=    $this->input->post('msg');
		$jsPath 		= $this->input->post('jsPath');
		$col_num	    = $this->input->post('col_num');


		if($todate) {
			$todate                = date("Y-m-d", strtotime($todate));
		}
		
		$charttypeVals               = explode("___", $charttype);
		
		if($dashboardType == ENT_DASHBOARD_TYPE_SLOWPAGES) {
			$urlData = $this->model->getSlowpagesURLs($charttypeVals[0], $charttypeVals[1], $selecteddate, $todate, $module );
		}
		else if($dashboardType == ENT_DASHBOARD_TYPE_MEMORY) {
			$urlData = $this->model->getHighMemorypagesURLs($charttypeVals[0], $charttypeVals[1], $selecteddate, $todate, $module );
		}
		else if($dashboardType == ENT_DASHBOARD_TYPE_CACHE) {
			error_log("1jj1j1jj1");
			$urlData = $this->model->getHighCachePagesURLs($charttypeVals[0], $charttypeVals[1], $selecteddate, $todate, $module );
		}
		else if($dashboardType == ENT_DASHBOARD_TYPE_EXCEPTION) {
			$urlData = $this->model->getExceptionpagesURL($charttypeVals[0], $charttypeVals[1], $selecteddate, $todate, $module ,$sourceFile, $lineNum);
		}
		else if($dashboardType == ENT_DASHBOARD_TYPE_DB_ERROR) {
			$urlData = $this->model->getDBErrorpagesURL($charttypeVals[0], $charttypeVals[1], $selecteddate, $todate, $module ,$sourceFile, $lineNum);
		}else if($dashboardType == ENT_DASHBOARD_TYPE_JS_ERROR)
		{
			$urlData = $this->model->getJSErrorPageUrls($msg,$jsPath,$lineNum,$col_num,$selecteddate,$todate,$module);
		}
		$displaydata['msg'] = $msg;
		$displaydata['selecteddate'] = $selecteddate;
		$displaydata['todate'] = $todate;
		$displaydata['urlData']      = $urlData;
		$displaydata['dashboardType']= $dashboardType;
		$displaydata['controller']= $charttypeVals[0];
		$displaydata['method']= $charttypeVals[1];
		$displaydata['module']= $module;

		$this->load->view("AppMonitor/showURLsDataTable", $displaydata);
	}

    function getDataForCustomChart()
    {
        $custumChartMap = $this->input->post("custumChartMap");
        $dashboardType = $this->input->post("dashboardType");
        
        $custumChartMapArr = explode("___", $custumChartMap);
        
        $fromDate            = date("Y-m-d");
        $toDate              = date("Y-m-d", strtotime("-".ENT_EE_REPORT_DAY_SPAN." day"));

        $mainmodules        = array( array($custumChartMapArr[0], $custumChartMapArr[1]));
        if($dashboardType == ENT_DASHBOARD_TYPE_MEMORY){
                $dailyAverageData   = $this->model->getHighMemoryPagesDailyReport('', $mainmodules, $fromDate, $toDate);
                $totalAverageData   = $this->model->getHighMemoryPagesDailyReport('', $mainmodules, $fromDate, $toDate, 1);
        }else{
                $dailyAverageData   = $this->model->getSlowPagesDailyReport('', $mainmodules, $fromDate, $toDate);
                $totalAverageData   = $this->model->getSlowPagesDailyReport('', $mainmodules, $fromDate, $toDate, 1);
        }
        
        $moduleWiseDailyAvg = $this->getFormattedSlowPagesGraphData($dailyAverageData, $totalAverageData, $dashboardType);

        $data = json_encode($moduleWiseDailyAvg[$custumChartMap]);

        echo $data;   
    }
	
	function Login()
	{
		$this->load->view('AppMonitor/login');
	}
	
	function doLogin()
	{
		$this->loginUser();
	}

	public function checkAbnormality(){


		$minutes = date('i');
		$start = $this->input->post('start');
		$result = array();
		if($minutes == "00" || $minutes % 5 == 0 || $start){

			$time = "";
			if($start){
				$x = $minutes % 5;
				$minutes = $minutes - $x;
				if(strlen($minutes) == 1) {
					$minutes = "0".$minutes;
				}
				$time = date('H').":".$minutes.":00";
			}else {
				$time = date('H:i').":00";
			}
			

			//$time = date('H').":05:00";
	/*		$metricesValueArray = array('slowpages' => 50000,
							'memory' => 15000, 
							'exception' => 120,
							'dberror' => 15,
							'slowquery' => 10000,
							'cronerror' => 10
				);*/
			$metricesValueArray = $this->input->post('inputArray');
			
			$lastNDaysData = $this->model->fetchLastNDaysValue($time,$this->yesterdayDate,30);
			$formattedData = array();

			foreach ($lastNDaysData as $key => $data) {
				$formattedData[$data['type']][] = $data['count'];
			}
	
			$result = array();

			foreach ($formattedData as $metric => $metricData) {

				$metricData = $this->appMonitorLib->removeOutliers($metricData);
				$formattedData[$metric] = $metricData;
				$mean = $this->appMonitorLib->calculateMean($metricData);
				$standardDeviation = $this->appMonitorLib->calculateStandardDeviation($metricData,$mean);
				$zscore = $this->appMonitorLib->calculateZScore($mean,$standardDeviation,$metricesValueArray[$metric]);
				$upperRange = -1;
				$excessValue = -1;
				if($zscore > 1){
					$upperRange = $this->appMonitorLib->calculateUpperAcceptibleRange(1,$standardDeviation,$mean);
					$excessValue = $metricesValueArray[$metric] - $upperRange;
				}
				$result[$metric] = $excessValue;
			
			}
		} 
		if(count($result) > 0){
			echo json_encode($result);	
		}
		else {
			echo "";
		}
		
		
	}

	function initializeEmptyTeamwiseData(){
		// $teamWiseData = array("mobile_app" => 0,"listings" => 0, "ugc" => 0 ,"studyabroad" => 0,"ldb" => 0, "common" => 0, "others" => 0, "Service" => 0);
		
		global $ENT_EE_MODULES;
		foreach ($ENT_EE_MODULES as $key => $value) {
			$teamWiseData[$key] = 0;
		}
		return $teamWiseData;
	}
}
