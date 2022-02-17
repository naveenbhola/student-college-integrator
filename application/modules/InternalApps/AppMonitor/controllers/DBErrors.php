<?php

require 'AppMonitorAbstract.php';

class DBErrors extends AppMonitorAbstract
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
		$displayData['dashboardType'] = ENT_DASHBOARD_TYPE_DB_ERROR;
		$displayData['reportType'] = 'trends';
		$displayData['selectedModule'] = $selectedModule;
		$this->getModulesLinks($displayData, '/AppMonitor/DBErrors', 'trends');
		
		$toDate = $_REQUEST['trendEndDate'] ? $_REQUEST['trendEndDate'] : date("Y-m-d");
		$fromDate = $_REQUEST['trendStartDate'] ? $_REQUEST['trendStartDate'] : date("Y-m-d", strtotime("-30 day"));
		
		$trends = $this->model->getDayWiseDbErrors($selectedModule, $fromDate,$toDate);
		$uniqueTrends = $this->model->getUniqueDayWiseDbErrors($selectedModule, $fromDate,$toDate);
		
		$displayData['trendsChartData'] = $this->prepareTrendChartData($trends, 'module', $selectedModule, $fromDate, $toDate);
		$displayData['uniqueTrendsChartData'] = $this->prepareTrendChartData($uniqueTrends, 'module', $selectedModule, $fromDate, $toDate);
	
		if($selectedModule == 'shiksha') {
			$modules[] = 'Overall';
		}
		else {
			$modules = array($selectedModule);
		}
		$displayData['modules'] = $modules;
		$displayData['colors'] = $this->getChartColors('module', $selectedModule);
		
		$displayData['trendStartDate'] = date("m/d/Y", strtotime($fromDate));
		$displayData['trendEndDate'] = date("m/d/Y", strtotime($toDate));
		
		$displayData['moduleName'] = $selectedModule;
		$this->load->view("AppMonitor/exceptions/trends", $displayData);
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
		
		$displayData['dashboardType'] = ENT_DASHBOARD_TYPE_DB_ERROR;
		$displayData['reportType'] = $reportType;
		$displayData['selectedModule'] = $selectedModule;
		$this->getModulesLinks($displayData, '/AppMonitor/DBErrors', $reportType);

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
		
		$displayData['tableHeading'] = 'DB Errors';
		$displayData['ajaxURL'] = '/AppMonitor/DBErrors/getRealTimeData';
		$displayData['selectedFilter'] = $selectedModule;
		$displayData['realtimeDate'] = $realtimeDate;
		$displayData['reportType'] = $reportType;
		if($reportType == 'yesterday') {
			$displayData['otherDate'] = date("m/d/Y", strtotime($realtimeDate));
		}
		
		$this->load->view("AppMonitor/common/realtime", $displayData);
	}
	
	function getRealTimeData($moduleName, $realtimeDate, $isAjax = 0, $rowNum = -1)
	{
		$data = array();
		
		$realtimeData = $this->model->getRealTimeDBQueryErrorData($moduleName, $realtimeDate);
		$data['count'] = $this->getRealTimeCount($realtimeData);
 		$data['timeDistribution'] = $this->getTimeDistribution($realtimeData);
		
		$realtimeDataParams = $this->getRealTimeDataParams($rowNum);
		$result = $this->model->getTopDbErrors($moduleName, $realtimeDate, $realtimeDataParams);
		$data['resultTable'] = $this->load->view('AppMonitor/dberrors/resultTable', array('todaysTopExceptions' => $result, 'dtype' => ENT_DASHBOARD_TYPE_EXCEPTION), TRUE);
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
		
		$displayData['dashboardType'] = ENT_DASHBOARD_TYPE_DB_ERROR;
		$displayData['reportType'] = 'detailedreport';
		$displayData['selectedModule'] = $selectedModule;
		$this->getModulesLinks($displayData, '/AppMonitor/DBErrors', 'detailedreport');
		
		$displayData['sorters'] = array('time' => 'Occurence Time','occurrence' => 'Number of Occurrences');
		$displayData['ajaxURL'] = "/AppMonitor/DBErrors/getDetailedReportData";
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
 		
		$data = $this->model->getDbErrorsDetailedData($filters);
		$this->load->view("AppMonitor/dberrors/detailedReport", array("data" => $data, "filters" => $filters, "dashboard" => ENT_DASHBOARD_TYPE_DB_ERROR));
 	}
}
