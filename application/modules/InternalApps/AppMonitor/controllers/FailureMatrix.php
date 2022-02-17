<?php

require 'AppMonitorAbstract.php';

class FailureMatrix extends AppMonitorAbstract
{
	function __construct()
	{
		parent::__construct();
	}
	
	function temporaryMachineFailure($moduleName = "")
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
		$displayData['appType'] = 'FailureMatrix';
		//$displayData['dashboardType'] = ENT_DASHBOARD_TYPE_SLOWPAGES;
		$displayData['failureType'] = 'temporaryMachineFailure';
		$displayData['selectedModule'] = $selectedModule;
		$this->getModulesLinks($displayData, '/AppMonitor/SlowPages', 'trends');
		
		$this->load->view("AppMonitor/slow/trends", $displayData);
	}
	
	function pagetrends($moduleName = "")
	{
		global $ENT_EE_MODULES, $mainmodules;
		$modules = $ENT_EE_MODULES;
		
		$selectedModule = "";
		if(array_key_exists($moduleName, $ENT_EE_MODULES)) {
			$selectedModule = $moduleName;
		}
		else {
			$selectedModule = "shiksha";
		}
		
		$displayData = array();
		$displayData['dashboardType'] = ENT_DASHBOARD_TYPE_SLOWPAGES;
		$displayData['reportType'] = 'pagetrends';
		$displayData['selectedModule'] = $selectedModule;
		$this->getModulesLinks($displayData, '/AppMonitor/SlowPages', 'pagetrends');
		
		$fromDate = date("Y-m-d", strtotime("-1 day"));
		$toDate = date("Y-m-d", strtotime("-30 day"));
		
		$allMethodsArr = array();
		$allMethods = $this->model->getAllSlowPagesMethods($selectedModule);
		foreach ($allMethods as $value) {
			$allMethodsArr[$value['controller_name']][] = $value['method_name'];
		}
		$displayData['allMethodsArr'] = $allMethodsArr;
		
		$dailyAverageData = $this->model->getSlowPagesDailyReport($selectedModule, $mainmodules, $fromDate, $toDate);
		$totalAverageData = $this->model->getSlowPagesDailyReport($selectedModule, $mainmodules, $fromDate, $toDate, 1);
		
		$moduleWiseDailyAvg = $this->getFormattedSlowPagesGraphData($dailyAverageData, $totalAverageData, ENT_DASHBOARD_TYPE_SLOWPAGES);
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
		
		$displayData['dashboardType'] = ENT_DASHBOARD_TYPE_SLOWPAGES;
		$displayData['reportType'] = $reportType;
		$displayData['selectedModule'] = $selectedModule;
		$this->getModulesLinks($displayData, '/AppMonitor/SlowPages', $reportType);

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
		
		$displayData['tableHeading'] = 'Slow Pages';
		$displayData['ajaxURL'] = '/AppMonitor/SlowPages/getRealTimeData';
		$displayData['selectedFilter'] = $selectedModule;
		$displayData['realtimeDate'] = $realtimeDate;
		$displayData['reportType'] = $reportType;
		
		$this->load->view("AppMonitor/common/realtime", $displayData);
	}
	
	function getRealTimeData($moduleName, $realtimeDate, $isAjax = 0, $rowNum = -1, $yesterday = FALSE)
	{
		$data = array();
		
		$realtimeData = $this->model->getRealTimeSlowPageData($moduleName, $realtimeDate);		
		$data['count'] = $this->getRealTimeCount($realtimeData);
 		$data['timeDistribution'] = $this->getTimeDistribution($realtimeData);
		
		$realtimeDataParams = $this->getRealTimeDataParams($rowNum);
		$realtimeDataParams['yesterday'] = $yesterday;
		$result = $this->model->getTotalRealtimeSlowPages($moduleName, $realtimeDate, $realtimeDataParams);
		$data['resultTable'] = $this->load->view('AppMonitor/slow/resultTable', array('realtimedata' => $result, 'dtype' => ENT_DASHBOARD_TYPE_SLOWPAGES), TRUE);
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
		
		$displayData['dashboardType'] = ENT_DASHBOARD_TYPE_SLOWPAGES;
		$displayData['reportType'] = 'detailedreport';
		$displayData['selectedModule'] = $selectedModule;
		$this->getModulesLinks($displayData, '/AppMonitor/SlowPages', 'detailedreport');
		
		$displayData['sorters'] = array('time' => 'Average Time','hits' => 'Number of Hits', 'threshold' => '> Threshold');
		$displayData['ajaxURL'] = "/AppMonitor/SlowPages/getDetailedReportData";
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
 		
		$data = $this->model->getSlowPagesDetailedData($filters);
		
		$groupedData = array();
		$sumHits = 0;
		$sumTime = 0;
		$sumAboveThreshold = 0;
		foreach($data as $row) {
			$key = $row['team']."~".$row['module_name']."~".$row['controller_name']."~".$row['method_name'];
			$groupedData[$key]['hits'] += $row['total_hits'];
			$groupedData[$key]['total_time'] += ($row['total_hits'] * $row['average_time'])/100;
			$groupedData[$key]['above_threshold'] += $row['num_above_threshold'];
			
			$sumHits += $row['total_hits'];
			$sumTime += ($row['total_hits'] * $row['average_time'])/100;
			$sumAboveThreshold += $row['num_above_threshold'];
		}
		
		$finalData = array();
		
		$allKey = 'All~All~All~All';
		$finalData[$allKey]['hits'] = $sumHits;
		$finalData[$allKey]['total_time'] = $sumTime;
		$finalData[$allKey]['above_threshold'] = $sumAboveThreshold;
		
		$finalData += $groupedData;
		
		foreach($finalData as $key => $val) {
			$finalData[$key]['avg'] = ($finalData[$key]['total_time'] / $finalData[$key]['hits']) * 100;
		}
	
		$displayData = array();
		$displayData['data'] = $finalData;
		$displayData['dashboard'] = ENT_DASHBOARD_TYPE_SLOWPAGES;
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
		
		$displayData['dashboardType'] = ENT_DASHBOARD_TYPE_SLOWPAGES;
		$displayData['reportType'] = 'diffReport';
		$displayData['selectedModule'] = $selectedModule;
		$this->getModulesLinks($displayData, '/AppMonitor/SlowPages', 'diffReport');
		
		$displayData['ajaxURL'] = "/AppMonitor/SlowPages/getDiffReportData";
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
			$dateRange1Data = $this->model->getDataForMonitoringDiff($from1,$to1,$selectedModule,'SlowPages');
		}else {
			$dateRange1Data = $this->model->getDataForMonitoringDiff($from1,$to1,$modules[$selectedModule],'SlowPages');
		}
 		
		if($selectedModule == 'shiksha'){
			$dateRange2Data = $this->model->getDataForMonitoringDiff($from2,$to2,$selectedModule,'SlowPages');
		}else {
			$dateRange2Data = $this->model->getDataForMonitoringDiff($from2,$to2,$modules[$selectedModule],'SlowPages');
		}

		$displayData = array();
		$displayData = $this->appMonitorLib->formatMonitoringDiffData($dateRange1Data, $dateRange2Data);
		
		$this->load->view("AppMonitor/slow/diffMiddlePanel",$displayData);
 	}
}
