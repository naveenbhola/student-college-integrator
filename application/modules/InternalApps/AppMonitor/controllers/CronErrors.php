<?php

require 'AppMonitorAbstract.php';

class CronErrors extends AppMonitorAbstract
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
		
		$displayData['dashboardType'] = ENT_DASHBOARD_TYPE_CRON_ERROR;
		$displayData['reportType'] = 'trends';
		$displayData['selectedModule'] = $selectedModule;
		$this->getModulesLinks($displayData, '/AppMonitor/CronErrors', 'trends');
				
		$toDate = $_REQUEST['trendEndDate'] ? $_REQUEST['trendEndDate'] : date("Y-m-d");
		$fromDate = $_REQUEST['trendStartDate'] ? $_REQUEST['trendStartDate'] : date("Y-m-d", strtotime("-30 day"));
		
		$trends = $this->model->getCronErrorsTrends($selectedModule, $fromDate,$toDate);
		$uniqueTrends = $this->model->getUniqueCronErrorsTrends($selectedModule, $fromDate,$toDate);
		
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

		$this->load->view("AppMonitor/crons/trends", $displayData);		
	}
	
	function yesterday($serverName = "")
	{
		$this->realtime($serverName, 'yesterday', $this->yesterdayDate);
	}
	
	function realtime($moduleName = "", $reportType = 'realtime', $realtimeDate = '')
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
		
		$displayData['dashboardType'] = ENT_DASHBOARD_TYPE_CRON_ERROR;
		$displayData['reportType'] = $reportType;
		$displayData['selectedModule'] = $selectedModule;
		$this->getModulesLinks($displayData, '/AppMonitor/CronErrors', $reportType);
		
        if($reportType == 'yesterday') {
            $realtimeData = $this->getRealTimeData($selectedModule, $realtimeDate, 0, -1, TRUE);
        }
        else {
            $realtimeDate = $this->realtimeDate;
            $realtimeData = $this->getRealTimeData($selectedModule, $realtimeDate);
        }
		
    	$displayData['timeDistribution'] = $realtimeData['timeDistribution'];
    	$displayData['resultTable'] = $realtimeData['resultTable'];
		$displayData['resultCount'] = $realtimeData['count'];
    	$displayData['modules'] = $modules;
    	$displayData['moduleName'] = $selectedModule;
		
		$displayData['tableHeading'] = 'Cron Errors';
		$displayData['ajaxURL'] = '/AppMonitor/CronErrors/getRealTimeData';
		$displayData['selectedFilter'] = $selectedModule;
		$displayData['realtimeDate'] = $realtimeDate;
		$displayData['reportType'] = $reportType;
		if($reportType == 'yesterday') {
			$displayData['otherDate'] = date("m/d/Y", strtotime($realtimeDate));
		}
		
    	$this->load->view('AppMonitor/common/realtime',$displayData);
	}
	
	public function getRealTimeData($moduleName, $realtimeDate, $isAjax = 0, $rowNum = -1, $yesterday = FALSE)
	{
    	$data = array();
    
		$realtimeData = $this->model->getCronRealTimeData($moduleName, $realtimeDate);
		$data['count'] = $this->getRealTimeCount($realtimeData);
 		$data['timeDistribution'] = $this->getTimeDistribution($realtimeData);
		
		$realtimeDataParams = $this->getRealTimeDataParams($rowNum);
		$realtimeDataParams['yesterday'] = $yesterday;
		$result = $this->model->getCronErrors($moduleName, $realtimeDate, $realtimeDataParams);
		$data['resultTable'] = $this->load->view('AppMonitor/crons/resultTable', array('result' => $result, 'dtype' => ENT_DASHBOARD_TYPE_CRON_ERROR), TRUE);
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
		
		$displayData['dashboardType'] = ENT_DASHBOARD_TYPE_CRON_ERROR;
		$displayData['reportType'] = 'detailedreport';
		$displayData['selectedModule'] = $selectedModule;
		$this->getModulesLinks($displayData, '/AppMonitor/CronErrors', 'detailedreport');
		
		$displayData['ajaxURL'] = "/AppMonitor/CronErrors/getDetailedReportData";
		$displayData['defaultDate'] = $this->detailedReportDate;
		
		$this->load->view("AppMonitor/common/detailedReport", $displayData);
	}
	
	function getDetailedReportData()
	{
		$toDate = date('Y-m-d',strtotime($this->input->post('todate')));
		$fromDate = date('Y-m-d',strtotime($this->input->post('fromdate')));	
		$moduleName = $this->input->post('module');
				
		$result = $this->model->getCronDetailedErrors($moduleName, $fromDate, $toDate);
		
		$data['result']  = $result;
		$data['detailedReport'] = TRUE;
		
		$this->load->view("AppMonitor/crons/resultTable",$data);
 	}
}
