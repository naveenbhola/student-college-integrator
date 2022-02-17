<?php

require 'AppMonitorAbstract.php';

class HighMemoryPages extends AppMonitorAbstract
{
	function __construct()
	{
		parent::__construct();
	}
	
	function trends($moduleName = "")
	{
		global $ENT_EE_MODULES;
		$modules = $ENT_EE_MODULES;
		
		$selectedModule = "";
		if(array_key_exists($moduleName, $ENT_EE_MODULES)) {
			$selectedModule = $moduleName;
		}
		else {
			$selectedModule = "shiksha";
		}
		
		$displayData = array();
		$displayData['dashboardType'] = ENT_DASHBOARD_TYPE_MEMORY;
		$displayData['reportType'] = 'trends';
		$displayData['selectedModule'] = $selectedModule;
		$this->getModulesLinks($displayData, '/AppMonitor/HighMemoryPages', 'trends');
		
		/**
		 * Main trend charts
		 */ 
		$fromDate = $_REQUEST['trendEndDate'] ? $_REQUEST['trendEndDate'] : date("Y-m-d", strtotime("-1 day"));
		$toDate = $_REQUEST['trendStartDate'] ? $_REQUEST['trendStartDate'] : date("Y-m-d", strtotime("-30 day"));
		
		$trends = $this->model->getHighMemoryTrends($selectedModule, $fromDate,$toDate);
		$uniqueTrends = $this->model->getUniqueHighMemoryTrends($selectedModule, $fromDate,$toDate);
		
		$displayData['trendsChartData'] = $this->prepareTrendChartData($trends, 'module', $selectedModule, $toDate, $fromDate);
		$displayData['uniqueTrendsChartData'] = $this->prepareTrendChartData($uniqueTrends, 'module', $selectedModule, $toDate, $fromDate);
		
		if($selectedModule == 'shiksha') {
			$modules[] = 'Overall';
		}
		else {
			$modules = array($selectedModule);
		}
		$displayData['modules'] = $modules;
		$displayData['colors'] = $this->getChartColors('module', $selectedModule);
		
		$displayData['fromDate'] = date("d-m-Y", strtotime($fromDate));
		$displayData['toDate'] = date("d-m-Y", strtotime($toDate));
		
		$displayData['trendStartDate'] = date("m/d/Y", strtotime($toDate));
		$displayData['trendEndDate'] = date("m/d/Y", strtotime($fromDate));
		
		$this->load->view("AppMonitor/slow/trends", $displayData);
	}
	
	function pagetrends($moduleName = "")
	{
		global $mainmodules;
		global $ENT_EE_MODULES;
		$modules = $ENT_EE_MODULES;
		
		$selectedModule = "";
		if(array_key_exists($moduleName, $ENT_EE_MODULES)) {
			$selectedModule = $moduleName;
		}
		else {
			$selectedModule = "shiksha";
		}
		
		$displayData = array();
		$displayData['dashboardType'] = ENT_DASHBOARD_TYPE_MEMORY;
		$displayData['reportType'] = 'pagetrends';
		$displayData['selectedModule'] = $selectedModule;
		$this->getModulesLinks($displayData, '/AppMonitor/HighMemoryPages', 'pagetrends');
		
		$fromDate = date("Y-m-d", strtotime("-1 day"));
		$toDate = date("Y-m-d", strtotime("-30 day"));
		
		$allMethodsArr = array();
		$allMethods = $this->model->getAllHighMemoryPagesMethods($selectedModule);
		foreach ($allMethods as $value) {
			$allMethodsArr[$value['controller_name']][] = $value['method_name'];
		}
		$displayData['allMethodsArr'] = $allMethodsArr;
		
		$dailyAverageData = $this->model->getHighMemoryPagesDailyReport($selectedModule, $mainmodules, $fromDate, $toDate);
		$totalAverageData = $this->model->getHighMemoryPagesDailyReport($selectedModule, $mainmodules, $fromDate, $toDate, 1);
		
		$moduleWiseDailyAvg = $this->getFormattedSlowPagesGraphData($dailyAverageData, $totalAverageData, ENT_DASHBOARD_TYPE_MEMORY);
		$displayData['dailyAverageData'] = $moduleWiseDailyAvg;
		
		$displayData['fromDate'] = date("d-m-Y", strtotime($fromDate));
		$displayData['toDate'] = date("d-m-Y", strtotime($toDate));
		
		$this->load->view("AppMonitor/slow/pagetrends", $displayData);
	}
	
	function yesterday($serverName = "")
	{
		$this->realtime($serverName, 'yesterday', $this->yesterdayDate);
	}
	
	function realtime($moduleName = "", $reportType = 'realtime', $realtimeDate = '')
	{
		global $ENT_EE_MODULES;
		
		$selectedModule = "";
		if(array_key_exists($moduleName, $ENT_EE_MODULES)) {
			$selectedModule = $moduleName;
		}
		else {
			$selectedModule = "shiksha";
		}
		
		$displayData = array();
		
		$displayData['dashboardType'] = ENT_DASHBOARD_TYPE_MEMORY;
		$displayData['reportType'] = $reportType;
		$displayData['selectedModule'] = $selectedModule;
		$this->getModulesLinks($displayData, '/AppMonitor/HighMemoryPages', $reportType);

        if($reportType == 'yesterday') {
            $realtimeData = $this->getRealTimeData($selectedModule, $realtimeDate, 0, -1, TRUE);
        }
        else {
            $realtimeDate = $this->realtimeDate;
            $realtimeData = $this->getRealTimeData($selectedModule, $realtimeDate);
        }
	
		$displayData['resultTable'] = $realtimeData['resultTable'];
		$displayData['resultCount'] = $realtimeData['count'];
 		$displayData['timeDistribution'] = $realtimeData['timeDistribution'];
		
		$displayData['tableHeading'] = 'High Memory Pages';
		$displayData['ajaxURL'] = '/AppMonitor/HighMemoryPages/getRealTimeData';
		$displayData['selectedFilter'] = $selectedModule;
		$displayData['realtimeDate'] = $realtimeDate;
		$displayData['reportType'] = $reportType;
		if($reportType == 'yesterday') {
			$displayData['otherDate'] = date("m/d/Y", strtotime($realtimeDate));
		}
		
		$this->load->view("AppMonitor/common/realtime", $displayData);
	}
	
	function getRealTimeData($moduleName, $realtimeDate, $isAjax = 0, $rowNum = -1, $yesterday = FALSE)
	{
		$excludeCron = $_COOKIE['excludeCron'];
		if($excludeCron == ''){
			$excludeCron = 0;
		}

		$data = array();
		
		$realtimeData = $this->model->getRealTimeMemoryData($moduleName, $realtimeDate,$excludeCron);
		$data['count'] = $this->getRealTimeCount($realtimeData);
 		$data['timeDistribution'] = $this->getTimeDistribution($realtimeData);
		
		$realtimeDataParams = $this->getRealTimeDataParams($rowNum);
		$realtimeDataParams['yesterday'] = $yesterday;
		$result = $this->model->getTotalRealtimeHighMemoryPage($moduleName, $realtimeDate, $realtimeDataParams,$excludeCron);
		$data['resultTable'] = $this->load->view('AppMonitor/slow/resultTable', array('realtimedata' => $result, 'dtype' => ENT_DASHBOARD_TYPE_MEMORY), TRUE);
		$data['timeWindowStart'] = $realtimeDataParams['timeStart'];
		$data['timeWindowEnd'] = $realtimeDataParams['timeEnd'];
		$data['numResults'] = count($result);
				
		if($isAjax) {
			echo json_encode($data);
		}
		else {
			return $data;
		}
	}
	
	function detailedreport($moduleName = '')
	{
		global $ENT_EE_MODULES;
		
		$selectedModule = "";
		if(array_key_exists($moduleName, $ENT_EE_MODULES)) {
			$selectedModule = $moduleName;
		}
		else {
			$selectedModule = "shiksha";
		}

		$displayData = array();
		
		$displayData['dashboardType'] = ENT_DASHBOARD_TYPE_MEMORY;
		$displayData['reportType'] = 'detailedreport';
		$displayData['selectedModule'] = $selectedModule;
		$this->getModulesLinks($displayData, '/AppMonitor/HighMemoryPages', 'detailedreport');
		
		$displayData['sorters'] = array('memory' => 'Average Memory','hits' => 'Number of Hits', 'threshold' => '> Threshold');
		$displayData['ajaxURL'] = "/AppMonitor/HighMemoryPages/getDetailedReportData";
		$displayData['defaultDate'] = $this->detailedReportDate;
		
		$this->load->view("AppMonitor/common/detailedReport", $displayData);
	}
	
	function getDetailedReportData()
	{
		$fromdate   = $this->input->post("fromdate");
		$todate     = $this->input->post("todate");
		$orderby = $this->input->post("orderby");
		$module = $this->input->post("module");

 		$filters['fromdate'] = $fromdate;
 		$filters['todate'] = $todate;
 		$filters['orderby'] = $orderby;
		$filters['module'] = $module;
 		
		$data = $this->model->getHighMemoryDetailedData($filters);
		
		$groupedData = array();
		$sumHits = 0;
		$sumMemory = 0;
		$sumAboveThreshold = 0;
		foreach($data as $row) {
			$key = $row['team']."~".$row['module_name']."~".$row['controller_name']."~".$row['method_name'];
			$groupedData[$key]['hits'] += $row['total_hits'];
			$groupedData[$key]['total_memory'] += ($row['total_hits'] * $row['average_memory'])/(1024 * 1024);
			$groupedData[$key]['above_threshold'] += $row['num_above_threshold'];
			
			$sumHits += $row['total_hits'];
			$sumMemory += ($row['total_hits'] * $row['average_memory'])/(1024 * 1024);
			$sumAboveThreshold += $row['num_above_threshold'];
		}
		
		$finalData = array();
		
		$allKey = 'All~All~All~All';
		$finalData[$allKey]['hits'] = $sumHits;
		$finalData[$allKey]['total_memory'] = $sumMemory;
		$finalData[$allKey]['above_threshold'] = $sumAboveThreshold;
		
		$finalData += $groupedData;
		
		foreach($finalData as $key => $val) {
			$finalData[$key]['avg'] = ($finalData[$key]['total_memory'] / $finalData[$key]['hits']) * (1024 * 1024);
		}
	
		$displayData = array();
		$displayData['data'] = $finalData;
		$displayData['dashboard'] = ENT_DASHBOARD_TYPE_MEMORY;
		$displayData['filters'] = $filters;
		$displayData['selectedModule'] = $module;
		$displayData['ajaxURLList'] = "/AppMonitor/SlowPages/getURLList";
	
		$this->load->view("AppMonitor/slow/detailedReport", $displayData);
 	}
	
	function getURLList()
	{
		$fromdate   = $this->input->post("fromdate");
		$todate     = $this->input->post("todate");
		$module = $this->input->post("module");
		$controller = $this->input->post("controller");
		$method = $this->input->post("method");
		
		$realtimeDataParams = $this->getRealTimeDataParams($rowNum);
		$result = $this->model->getTotalRealtimeSlowPages($moduleName, $this->realtimeDate, $realtimeDataParams);
		$data['resultTable'] = $this->load->view('AppMonitor/slow/resultTable', array('realtimedata' => $result, 'dtype' => ENT_DASHBOARD_TYPE_SLOWPAGES), TRUE);
		$data['timeWindowStart'] = $realtimeDataParams['timeStart'];
		$data['timeWindowEnd'] = $realtimeDataParams['timeEnd'];
		
		if($isAjax) {
			echo json_encode($data);
		}
		else {
			return $data;
		}
	}	

	function diffReport($moduleName = '')
	{
		global $ENT_EE_MODULES;
		
		$selectedModule = "";
		if(array_key_exists($moduleName, $ENT_EE_MODULES)) {
			$selectedModule = $moduleName;
		}
		else {
			$selectedModule = "shiksha";
		}

		$displayData = array();
		
		$displayData['dashboardType'] = ENT_DASHBOARD_TYPE_MEMORY;
		$displayData['reportType'] = 'diffReport';
		$displayData['selectedModule'] = $selectedModule;
		$this->getModulesLinks($displayData, '/AppMonitor/HighMemoryPages', 'diffReport');
		
		$displayData['ajaxURL'] = "/AppMonitor/HighMemoryPages/getDiffReportData";
		$displayData['defaultDateStart'] = $this->diffReportDateStart;
		$displayData['defaultDateEnd'] = $this->diffReportDateEnd;
		
		$this->load->view("AppMonitor/common/diffReport", $displayData);
	}

 	function getDiffReportData(){

		$from1 = date('Y-m-d',strtotime($this->input->post('fromdateStart')));	
		$to1 = date('Y-m-d',strtotime($this->input->post('fromdateEnd')));	

		$from2 = date('Y-m-d',strtotime($this->input->post('todateStart')));
		$to2 = date('Y-m-d',strtotime($this->input->post('todateEnd')));

		global $ENT_EE_MODULES;
    	$modules = $ENT_EE_MODULES;

		$selectedModule = $this->input->post('module');

		$data = array();
		$data['dateRange1'] = array();
		$data['dateRange2'] = array();
		$dateRange1Data = array();
		$dateRange2Data = array();
		
		if($selectedModule == 'shiksha'){
			$dateRange1Data = $this->model->getDataForMonitoringDiff($from1,$to1,$selectedModule,'HighMemoryPages');
		}else {
			$dateRange1Data = $this->model->getDataForMonitoringDiff($from1,$to1,$modules[$selectedModule],'HighMemoryPages');
		}
 		
		if($selectedModule == 'shiksha'){
			$dateRange2Data = $this->model->getDataForMonitoringDiff($from2,$to2,$selectedModule,'HighMemoryPages');
		}else {
			$dateRange2Data = $this->model->getDataForMonitoringDiff($from2,$to2,$modules[$selectedModule],'HighMemoryPages');
		}

		$displayData = array();
		$displayData = $this->appMonitorLib->formatMonitoringDiffData($dateRange1Data, $dateRange2Data);
		
		$this->load->view("AppMonitor/slow/diffMiddlePanel",$displayData);
 	}
}
