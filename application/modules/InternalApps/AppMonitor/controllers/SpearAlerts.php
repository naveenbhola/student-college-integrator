<?php

require 'AppMonitorAbstract.php';

class SpearAlerts extends AppMonitorAbstract
{
	function __construct()
	{
		parent::__construct();
	}
	
	function trends($serverName = "")
	{
		global $SHIKSHA_PROD_SERVERS;
    	$servers = $SHIKSHA_PROD_SERVERS;
		
		$selectedServer = "";
		if(array_key_exists($serverName, $SHIKSHA_PROD_SERVERS)) {
			$selectedServer = $SHIKSHA_PROD_SERVERS[$serverName];
		}
		else {
			$selectedServer = "shiksha";
		}
		
		$displayData['dashboardType'] = ENT_DASHBOARD_TYPE_SPEARALERTS;
		$displayData['reportType'] = 'trends';
		$displayData['serverName'] = $selectedServer;
		$this->getModulesLinks($displayData, '/AppMonitor/SpearAlerts', 'trends', 'prodservers');
		
		$toDate = $_REQUEST['trendEndDate'] ? $_REQUEST['trendEndDate'] : date("Y-m-d", strtotime("-1 day"));
		$fromDate = $_REQUEST['trendStartDate'] ? $_REQUEST['trendStartDate'] : date("Y-m-d", strtotime("-30 day"));
		
		$trends = $this->model->getSpearAlertTrend($fromDate,$toDate,$selectedServer);
		//_p($trends); exit();
		//$uniqueTrends = $this->model->getUniqueSlowQueriesTrend($fromDate,$toDate,$selectedServer);
		
		$displayData['trendsChartData'] = $this->prepareTrendChartData($trends, 'prodserver', $selectedServer, $fromDate, $toDate);
		//$displayData['uniqueTrendsChartData'] = $this->prepareTrendChartData($uniqueTrends, 'server', $selectedServer, $fromDate, $toDate);
		
		if($selectedServer == 'shiksha') {
			$servers[] = 'Overall';
		}
		else {
			$servers = array($selectedServer);
		}
		$displayData['servers'] = $servers;
		$displayData['colors'] = $this->getChartColors('prodserver', $selectedServer);
		
		$displayData['trendStartDate'] = date("m/d/Y", strtotime($fromDate));
		$displayData['trendEndDate'] = date("m/d/Y", strtotime($toDate));
		
		$this->load->view("AppMonitor/spearAlerts/trends", $displayData);		
	}
	
	function yesterday($serverName = "")
	{
		$this->realtime($serverName, 'yesterday', $this->yesterdayDate);
	}
	
	function realtime($serverName = "", $reportType = 'realtime', $realtimeDate = '')
	{
		global $SHIKSHA_PROD_SERVERS;
    	$servers = $SHIKSHA_PROD_SERVERS;
		
		$selectedServer = "";
		$selectedServerName = "";
		if(array_key_exists($serverName, $SHIKSHA_PROD_SERVERS)) {
			$selectedServer = $SHIKSHA_PROD_SERVERS[$serverName];
			$selectedServerName = $serverName;
		}
		else {
			$selectedServer = "shiksha";
			$selectedServerName = "shiksha";
		}
		
		$displayData['dashboardType'] = ENT_DASHBOARD_TYPE_SPEARALERTS;
		$displayData['reportType'] = $reportType;
		$displayData['serverName'] = $selectedServer;
		$displayData['selectedServerName'] = $selectedServerName;
		
		$this->getModulesLinks($displayData, '/AppMonitor/SpearAlerts', $reportType, 'prodservers');
		
        if($reportType == 'yesterday') {
            $realtimeData = $this->getRealTimeData($selectedServer, $realtimeDate, 0, -1, TRUE);
        }
        else {
            $realtimeDate = $this->realtimeDate;
            $realtimeData = $this->getRealTimeData($selectedServer, $realtimeDate);
        }

    	$displayData['timeDistribution'] = $realtimeData['timeDistribution'];
    	$displayData['resultTable'] = $realtimeData['resultTable'];
		$displayData['resultCount'] = $realtimeData['count'];
    	$displayData['servers'] = $servers;
    	
		$displayData['tableHeading'] = 'Alerts';
		$displayData['ajaxURL'] = '/AppMonitor/SpearAlerts/getRealTimeData';
		$displayData['selectedFilter'] = $selectedServer;
		$displayData['realtimeDate'] = $realtimeDate;
		$displayData['reportType'] = $reportType;
		
		if($reportType == 'yesterday') {
			$displayData['otherDate'] = date("m/d/Y", strtotime($realtimeDate));
		}
		
    	$this->load->view('AppMonitor/common/realtime',$displayData);
	}
	
	function getRealTimeData($serverName, $realtimeDate, $isAjax = 0, $rowNum = -1, $yesterday = FALSE)
	{
		global $SHIKSHA_PROD_SERVERS;
    	$servers = $SHIKSHA_PROD_SERVERS;
		
		$data = array();
		
		$realtimeData = $this->model->getSpearAlertsRealTimeStats($serverName, $realtimeDate);
    	$data['count'] = $this->getRealTimeCount($realtimeData);
		$data['timeDistribution'] = $this->getTimeDistribution($realtimeData);
		
		$realtimeDataParams = $this->getRealTimeDataParams($rowNum);
		$realtimeDataParams['yesterday'] = $yesterday;
		$result = $this->model->getSpearAlertsRealTimeData($serverName, $realtimeDate, $realtimeDataParams);
		$data['resultTable'] = $this->load->view('AppMonitor/spearAlerts/realtimeTable',array('result' => $result),TRUE);
		$data['timeWindowStart'] = $realtimeDataParams['timeStart'];
		$data['timeWindowEnd'] = $realtimeDataParams['timeEnd'];
		$data['numResults'] = count($result);
		
		//$data['sortbox'] = $this->load->view('AppMonitor/common/sortbox', array('dashboard' => ENT_DASHBOARD_TYPE_SLOWQUERY, 'sortURL' => '/AppMonitor/SlowQueries/sortRealTimeData'), TRUE);
		
		if($isAjax) {
			echo json_encode($data);
		}
		else {
			return $data;
		}
	}
	
	function detailedreport($serverName = '')
	{
		global $SHIKSHA_PROD_SERVERS;
    	$servers = $SHIKSHA_PROD_SERVERS;
		
		$selectedServer = "";
		if(array_key_exists($serverName, $SHIKSHA_PROD_SERVERS)) {
			$selectedServer = $serverName;
		}
		else {
			$selectedServer = "shiksha";
		}
		
		$displayData['dashboardType'] = ENT_DASHBOARD_TYPE_SPEARALERTS;
		$displayData['reportType'] = 'detailedreport';
		$displayData['serverName'] = $selectedServer;
		$this->getModulesLinks($displayData, '/AppMonitor/SpearAlerts', 'detailedreport', 'prodservers');
		
		$displayData['sorters'] = array('occurrence' => 'Occurrences');
		$displayData['ajaxURL'] = "/AppMonitor/SpearAlerts/getDetailedReportData";
		$displayData['defaultDate'] = $this->detailedReportDate;
		
		$this->load->view("AppMonitor/common/detailedReport", $displayData);
	}
	
	function getDetailedReportData()
	{
		global $SHIKSHA_PROD_SERVERS;
    	$servers = $SHIKSHA_PROD_SERVERS;
		
		$toDate = date('Y-m-d',strtotime($this->input->post('todate')));
		$fromDate = date('Y-m-d',strtotime($this->input->post('fromdate')));	
		$serverName = $this->input->post('server');
		$orderby = $this->input->post("orderby");
				
		if($serverName == "shiksha"){
			$data['result'] = $this->model->getSpearAlertDetailedData($serverName,$fromDate,$toDate,$orderby);	
		} else {
			$data['result'] = $this->model->getSpearAlertDetailedData($servers[$serverName],$fromDate,$toDate,$orderby);
		}
		
		$data['serverName'] = $serverName;
		$data['fromDate'] = $fromDate;
		$data['toDate'] = $toDate;
		$data['sorter'] = $sorter;

		$this->load->view("AppMonitor/spearAlerts/middlePanel",$data);
 	}

 	function diffReport($serverName='')
	{
 		global $ENT_EE_SERVERS;
    	$servers = $ENT_EE_SERVERS;
		
		$selectedServer = "";
		if(array_key_exists($serverName, $ENT_EE_SERVERS)) {
			$selectedServer = $serverName;
		}
		else {
			$selectedServer = "shiksha";
		}
		
		$displayData['dashboardType'] = ENT_DASHBOARD_TYPE_SLOWQUERY;
		$displayData['reportType'] = 'diffReport';
		$displayData['serverName'] = $selectedServer;
		$this->getModulesLinks($displayData, '/AppMonitor/SlowQueries', 'diffReport', 'servers');
		
		$displayData['ajaxURL'] = "/AppMonitor/SlowQueries/getDiffReportData";
		$displayData['defaultDateStart'] = $this->diffReportDateStart;
		$displayData['defaultDateEnd'] = $this->diffReportDateEnd;
		
		$this->load->view("AppMonitor/common/diffReport", $displayData);

 	}

 	function getDiffReportData()
	{
		global $ENT_EE_SERVERS;
    	$servers = $ENT_EE_SERVERS;
		
		$fromStart = date('Y-m-d',strtotime($this->input->post('fromdateStart')));	
		$fromEnd = date('Y-m-d',strtotime($this->input->post('fromdateEnd')));	

		$toStart = date('Y-m-d',strtotime($this->input->post('todateStart')));
		$toEnd = date('Y-m-d',strtotime($this->input->post('todateEnd')));
		
		$serverName = $this->input->post('server');
		
		/**
		 * Fetch slow queries for date range 1 (FROM range)
		 */ 
		$fromRangeData = $this->model->getSlowQueriesDetail($serverName, $fromStart, $fromEnd);
		$fromRangeData = $this->appMonitorLib->mergeSimilarQueries($fromRangeData);
		
		/**
		 * Fetch slow queries for date range 2 (TO range)
		 */ 
		$toRangeData = $this->model->getSlowQueriesDetail($serverName, $toStart, $toEnd);
		$toRangeData = $this->appMonitorLib->mergeSimilarQueries($toRangeData);
		
		/**
		 * Queries removed
		 */ 
		$queriesRemovedDiff = array_diff(array_keys($fromRangeData), array_keys($toRangeData));
		$queriesRemoved = array();
		$countArray =array();
		
		foreach ($queriesRemovedDiff as $query) {
			$queriesRemoved[$query] = $fromRangeData[$query];
			$countArray[$query] = $fromRangeData[$query]['count'];
		}
		array_multisort($countArray, SORT_DESC, $queriesRemoved);

		/**
		 * New queries
		 */ 
		$queriesAddedDiff = array_diff(array_keys($toRangeData), array_keys($fromRangeData));
		$countArray =array();
		$queriesAdded= array();
		
		foreach ($queriesAddedDiff as $query) {
			$queriesAdded[$query] = $toRangeData[$query];
			$countArray[$query] = $toRangeData[$query]['count'];
		}

		array_multisort($countArray, SORT_DESC, $queriesAdded);

		/**
		 * Persistent queries (count likely changed)
		 */ 
		$persistentQueriesDiff = array_values(array_intersect(array_keys($fromRangeData), array_keys($toRangeData)));

		$reducedCountQueries = array();
		$increasedCountQueries = array();
		
		$reducedCountArray = array();
		$increasedCountArray = array();

		foreach ($persistentQueriesDiff as $query) {
			$countDiff = $fromRangeData[$query]['count'] - $toRangeData[$query]['count'];
			
			if($countDiff > 0){
				$reducedCountQueries[$query]['count'] = $countDiff;
				$reducedCountQueries[$query]['server'] = $fromRangeData[$query]['server'];
				$reducedCountArray[$query] = $countDiff;
			}
			else if($countDiff < 0) {
				$increasedCountQueries[$query]['count'] = abs($countDiff);
				$increasedCountQueries[$query]['server'] = $fromRangeData[$query]['server'];
				$increasedCountArray[$query] = abs($countDiff);
			}
		}
		
		array_multisort($reducedCountArray, SORT_DESC, $reducedCountQueries);
		array_multisort($increasedCountArray, SORT_DESC, $increasedCountQueries);

		$displayData = array();
		$displayData['newSlowQueries'] = $queriesAdded;
		$displayData['queriesReduced'] = $queriesRemoved;
		$displayData['queriesReducedCount'] = $reducedCountQueries;
		$displayData['queriesIncreasedCount'] = $increasedCountQueries;
		
		$displayData['diffNew'] = count($queriesAdded);
		$displayData['diffRemoved'] = count($queriesRemoved);
		$displayData['diffSame'] = count($reducedCountQueries) + count($increasedCountQueries);
		
		$this->load->view("AppMonitor/slowQueries/diffMiddlePanel", $displayData);	
 	}
}
