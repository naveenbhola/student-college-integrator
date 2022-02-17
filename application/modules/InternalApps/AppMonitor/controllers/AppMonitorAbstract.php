<?php

class AppMonitorAbstract extends MX_Controller
{
	protected $model;
	protected $appMonitorLib;
    protected $filterCookie;
	protected static $validUsers = array(5137653);
	protected $realtimeDate;
	protected $detailedReportDate;
	
	function __construct()
	{
		global $ENT_EE_MODULES_CONTROLLER_MAP;
		
		$this->load->config("app_monitor_config");
		$this->getFilterCookie();
 		$this->load->model("AppMonitor/appmonitormodel");
		$this->model = new Appmonitormodel();
		
		$this->load->library("AppMonitor/AppMonitorLib");
		$this->appMonitorLib = new AppMonitorLib();

		$this->load->library('AppMonitor/cache/AppMonitorCache');  
		$this->appmonitorcache = new AppMonitorCache();
		
		//$this->realtimeDate = date('Y-m-d');
		//$this->realtimeDate = '2015-08-18';
		//$this->yesterdayDate = '2015-08-16';
		//$this->detailedReportDate = '08/18/2015';

		$this->realtimeDate = date('Y-m-d');
		$this->yesterdayDate = date('Y-m-d', strtotime('-1 Day'));
		$this->detailedReportDate = date('m/d/Y', strtotime('-1 Day'));
		$this->diffReportDateStart = date('m/d/Y', strtotime('-2 Day'));
		$this->diffReportDateEnd = date('m/d/Y', strtotime('-1 Day'));
		$this->validateAccess();
	}
	
	function validateAccess()
	{
		// return true;
		if($this->isLoginPage() || defined('CRON')) {
			return TRUE;
		}
		
		$loggedInUserData = $this->getLoggedInUserData();
		if($loggedInUserData['userId'] && in_array($loggedInUserData['userId'], self::$validUsers)) {
			return TRUE;
		}
		else {
			header("Location: /AppMonitor/Dashboard/Login");
			exit();
		}
	}
    
	function isLoginPage()
	{
		$uri = $_SERVER['REQUEST_URI'];
		if(strpos($uri, "/AppMonitor/Dashboard/Login") === 0 || strpos($uri, "/AppMonitor/Dashboard/doLogin") === 0) {
			return TRUE;
		}
		return FALSE;
	}
	
	function loginUser()
	{
		$userId = (int) Modules::run('user/Login/submit');
		error_log($userId);
        if($userId > 0) {
            if(in_array($userId,self::$validUsers)) {
                echo $userId;
            }
            else {
                Modules::run('user/Login/signOut');
                echo '0';
            }
        }    
        else {
            echo '0';
        }
	}
	
    function getFilterCookie()
    {
		$cookieVal = $_COOKIE['shmsFilters'];
		$cookieVal = json_decode($cookieVal, true);
		$this->filterCookie = $cookieVal;
	}
    
    function getModulesLinks(&$data, $controller, $method, $type = 'modules')
	{
 		global $ENT_EE_MODULES;
 		global $ENT_EE_REPORT_TABS;
		global $ENT_EE_SERVERS;
		global $SHIKSHA_PROD_SERVERS;
        global $BOT_STATUSES;
		
		$reqParams = array();
		if($_REQUEST['trendStartDate']) {
			$reqParams[] = 'trendStartDate='.$_REQUEST['trendStartDate'];
		}
		if($_REQUEST['trendEndDate']) {
			$reqParams[] = 'trendEndDate='.$_REQUEST['trendEndDate'];
		}
		
		$reqParamLink = '';
		if(count($reqParams) > 0) {
			$reqParamLink = '?'.implode('&', $reqParams);
		}
		
		$linkIterator = $ENT_EE_MODULES;
		if($type == 'servers') {
			$linkIterator = $ENT_EE_SERVERS;
		}
		else if($type == 'prodservers') {
			$linkIterator = $SHIKSHA_PROD_SERVERS;
		}
        else if($type == 'botstatus') {
			$linkIterator = $BOT_STATUSES;
		}

 		$modulesLinks = array();
        if($type == 'botstatus') {
            $modulesLinks["All"] = $controller.'/'.$method;
        }
        else {
            $modulesLinks["All"] = $controller.'/'.$method;
        }
 		foreach ($linkIterator as $key => $value) {
 			$modulesLinks[$value] = $controller.'/'.$method.'/'.$key.$reqParamLink;
 		}
 		$data['modulesLinks'] = $modulesLinks;

 		$reportLinks = array();

 		foreach($ENT_EE_REPORT_TABS as $tab=>$tabname) {
 			if(!in_array($data['dashboardType'], array(ENT_DASHBOARD_TYPE_SLOWPAGES, ENT_DASHBOARD_TYPE_MEMORY)) && $tab == 'pagetrends') {
 				continue;
 			}

 			if(!in_array($data['dashboardType'], array(ENT_DASHBOARD_TYPE_SLOWQUERY,ENT_DASHBOARD_TYPE_SLOWPAGES,ENT_DASHBOARD_TYPE_MEMORY,ENT_DASHBOARD_TYPE_CACHE)) && $tab == 'diffReport'){
 				continue;
 			}

 			if(in_array($data['dashboardType'], array(ENT_DASHBOARD_TYPE_HIGH_SQL_QUERIES)) && in_array($tab, array('trends', 'detailedreport'))){
 				continue;
 			}

 			if(in_array($data['dashboardType'], array(ENT_DASHBOARD_TYPE_SOLR_ERRORS)) && in_array($tab, array( 'detailedreport'))){
 				continue;
 			}

			$reportLinks[$tab] = array($tabname, $controller."/".$tab);
 		}

 		$data['reportLinks'] = $reportLinks;
 	}
	
	function prepareTrendChartData($trendData, $filterType, $selectedFilter, $fromDate, $toDate)
	{
		global $ENT_EE_SERVERS;
		global $ENT_EE_MODULES;
		global $SHIKSHA_PROD_SERVERS;
        global $BOT_STATUSES;
        global $CAPTCHA_STATUSES;
		
		$filters = $filterType == 'server' ? $ENT_EE_SERVERS : $ENT_EE_MODULES;
		
		$filters = $ENT_EE_MODULES;
		if($filterType == 'server') {
			$filters = $ENT_EE_SERVERS;
		}
		if($filterType == 'prodserver') {
			$filters = $SHIKSHA_PROD_SERVERS;
		}
        if($filterType == 'botstatus') {
			$filters = $BOT_STATUSES;
		}
        if($filterType == 'captcha') {
			$filters = $CAPTCHA_STATUSES;
            //_p($filters);
             //exit();
		}
		//_p($filters);
        //_p($trendData);
		$chartData = array();
		
		$i = 0;
		$date = $fromDate;
		while($date < $toDate) {
			$date = date('Y-m-d',strtotime("+$i days",strtotime($fromDate)));
			$temp = array();
			$temp[] = $date;
			
			if($selectedFilter == 'shiksha' || $selectedFilter == 'all' || $filterType == 'captcha') {
				$sum = 0;			
				foreach($filters as $filterId => $filterName) {
					$temp[] = intval($trendData[$date][$filterId]);
					$sum += intval($trendData[$date][$filterId]);
				}
                if($filterType != 'captcha') {
                    $temp[] = $sum;
                }
			}
			else {
				$temp[] = intval($trendData[$date][$selectedFilter]);
			}
			
			$chartData[] = $temp;
			$i++;
		}
		
		return $chartData;
	}
	
	function getTimeDistribution($results)
	{
        $timeDistribution = array();
		
		for($i=0;$i<24;$i++) {
			$timeKey = ($i<10 ? "0" : "").$i;
			$timeDistribution[$timeKey.":00"] = 0;
			$timeDistribution[$timeKey.":30"] = 0;
		}
		
		foreach ($results as $result) {			
			$hour = ($result['h'] < 10 ? "0" : "").$result['h'];
			$minute = ($result['m'] < 10 ? "0" : "").$result['m'];
			
			$timeKey = 	$hour.($minute < 30 ? ":00" : ":30");
			$timeDistribution[$timeKey] += $result['count'];
		}
		
		return $timeDistribution;
	}
	
	function getRealTimeCount($results)
	{
		$count = 0;
		foreach($results as $result) {
			$count += $result['count'];
		}
		return $count;
	}
	
	function getRealTimeDataParams($rowNum)
	{
		$timeStart = '00:00:00';
		$timeEnd = '23:59:59';
		
		$limit = 100;
		$order = 'DESC';
		
		if($rowNum >= 0) {
			$hour = floor($rowNum/2);
			$hour = ($hour > 9 ? "" : "0").$hour;
			$part = $rowNum % 2;
			
			if($part == 0) {
				$timeStart = $hour.":00:00";
				$timeEnd = $hour.":30:00";
			}
			else {
				$timeStart = $hour.":30:00";
				$timeEnd = $hour.":59:59";
			}
			$limit = null;
			$order = 'ASC';
		}
		
		return array('timeStart' => $timeStart, 'timeEnd' => $timeEnd, 'order' => $order, 'limit' => $limit);
	}
	
	function getChartColors($filterType, $selectedFilter)
	{
		global $ENT_EE_SERVERS_COLORS;
		global $ENT_EE_MODULES_COLORS;
		global $SHIKSHA_PROD_SERVERS_COLORS;
        global $BOT_STATUS_COLORS;
        global $CAPTCHA_STATUS_COLORS;
		
		$filterColors = $ENT_EE_MODULES_COLORS;
		if($filterType == 'server') {
			$filterColors = $ENT_EE_SERVERS_COLORS;
		}
		else if($filterType == 'prodserver') {
			$filterColors = $SHIKSHA_PROD_SERVERS_COLORS;
		}
        else if($filterType == 'botstatus') {
			$filterColors = $BOT_STATUS_COLORS;
		}
        else if($filterType == 'captcha') {
			$filterColors = $CAPTCHA_STATUS_COLORS;
		}
		
		$colors = array();
		if($selectedFilter == "shiksha" || $selectedFilter == "all" || $filterType == 'captcha'){
			foreach ($filterColors as $color) {
				$colors[] = $color;
			}
		}
		else {
			$colors[] = $filterColors[$selectedFilter];
		}
		return $colors;
	}
	
	function getFormattedSlowPagesGraphData($dailyAverageData, $totalAverageData, $dashboardType)
	{
		global $timeBucketsDefine;
		global $timeBucketValConfidenceLevels;
		global $memoryBucketsDefine;

		$totalAverageFormattedData = array();
		foreach ($totalAverageData as $value) {
			$totalAverageFormattedData[$value['controller_name']][$value['method_name']] = $value['avg'];
		}
		
		if($dashboardType == ENT_DASHBOARD_TYPE_MEMORY) {
			$bucket = $memoryBucketsDefine;
		}
		else {
			$bucket = $timeBucketsDefine;
		}

		$moduleWiseDailyAvg = array();
		foreach ($dailyAverageData as $value) {
			$timetext = "<b>Average Value : </b>: ".ceil($value['avg'])."<br/>";
			$timetext.= "<b>Date </b>: ".$value['l_date']."<br/>";
			$timetext.= "<table class='tooltip-tbl' border=1>";
			$timetext.= "<tr><th>Bucket</th><th>Value</th></tr>";
			$value['bucket_data'] = json_decode($value['bucket_data'], true);
			foreach ($bucket as $key => $timeBucketsDefineRow) {
				if($value['bucket_data'][$key])
					$timetext.= "<tr><td>".$timeBucketsDefineRow."</td><td>".($value['bucket_data'][$key] ? $value['bucket_data'][$key] : 0)."</td></tr>";
			}
			$timetext.= "</table>";

			$memorytext = "<b>Average </b>: ".ceil($totalAverageFormattedData[$value['controller_name']][$value['method_name']])."<br/>";
			$moduleWiseDailyAvg[$value['controller_name']."___".$value['method_name']][] = array($value['l_date'], (float)$value['avg'], $timetext, (float)$totalAverageFormattedData[$value['controller_name']][$value['method_name']], $memorytext);
		}
		
		return $moduleWiseDailyAvg;
	}
}
