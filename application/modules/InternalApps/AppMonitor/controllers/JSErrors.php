<?php
require 'AppMonitorAbstract.php';

class JSErrors extends AppMonitorAbstract
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
		$displayData['dashboardType'] = ENT_DASHBOARD_TYPE_JS_ERROR;
		$displayData['reportType'] = 'trends';
		$displayData['selectedModule'] = $selectedModule;
		$this->getModulesLinks($displayData, '/AppMonitor/JSErrors', 'trends');
				
		$toDate = $_REQUEST['trendEndDate'] ? $_REQUEST['trendEndDate'] : date("Y-m-d");
		$fromDate = $_REQUEST['trendStartDate'] ? $_REQUEST['trendStartDate'] : date("Y-m-d", strtotime("-30 day"));
		
		$trends = $this->model->getDayWiseJSErrors($selectedModule, $fromDate,$toDate);
		$uniqueTrends = $this->model->getUniqueDayWiseJSError($selectedModule, $fromDate,$toDate);
		
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
		$this->load->view("AppMonitor/jsErrors/trends", $displayData);
	}

	function yesterday($moduleName = "")
	{
		$this->realtime($moduleName, 'yesterday', $this->yesterdayDate);
	}

	function realtime($moduleName = "", $reportType = 'realtime', $realtimeDate = '')
	{
		global $ENT_EE_MODULES;
		if(array_key_exists($moduleName, $ENT_EE_MODULES))
		{
			$selectedModule = $moduleName;
		}
		else
		{
			$selectedModule = "shiksha";
		}
		$displayData = array();
		$displayData['dashboardType'] = ENT_DASHBOARD_TYPE_JS_ERROR;
		$displayData['reportType'] = $reportType;
		$displayData['selectedModule'] = $selectedModule;
		$this->getModulesLinks($displayData, '/AppMonitor/JSErrors', $reportType);

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


		$displayData['tableHeading'] = 'JS Errors';
		$displayData['ajaxURL'] = '/AppMonitor/JSErrors/getRealTimeData';
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
		$data = array();
		
		$realtimeData = $this->model->getRealTimeJSErrorData($moduleName, $realtimeDate);
		$data['count'] = $this->getRealTimeCount($realtimeData);
 		$data['timeDistribution'] = $this->getTimeDistribution($realtimeData);
		
		$realtimeDataParams = $this->getRealTimeDataParams($rowNum);
		$realtimeDataParams['yesterday'] = $yesterday;
		$result = $this->model->getTopJSErrors($moduleName, $realtimeDate, $realtimeDataParams);
		$data['resultTable'] = $this->load->view('AppMonitor/jsErrors/resultTable', array('todaysTopJSErrors' => $result, 'dtype' => ENT_DASHBOARD_TYPE_JS_ERROR), TRUE);
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
		
		$displayData['dashboardType'] = ENT_DASHBOARD_TYPE_JS_ERROR;
		$displayData['reportType'] = 'detailedreport';
		$displayData['selectedModule'] = $selectedModule;
		$this->getModulesLinks($displayData, '/AppMonitor/JSErrors', 'detailedreport');
		
		$displayData['sorters'] = array('time' => 'Occurence Time','occurrence' => 'Number of Occurrences');
		$displayData['ajaxURL'] = "/AppMonitor/JSErrors/getDetailedReportData";
		$displayData['defaultDate'] = $this->detailedReportDate;
		
		$this->load->view("AppMonitor/common/detailedReport", $displayData);
	}

	function getDetailedReportData()
	{
		$fromdate   = $this->input->post("fromdate");
		$todate     = $this->input->post("todate");
		$orderby = $this->input->post("orderby");
		$module = $this->input->post("module");
		$pageNumber = $this->input->post("pageNumber");
		$pageNumber = $pageNumber ? $pageNumber : 1;
		$rows = 500;

 		$filters['fromdate'] = $fromdate;
 		$filters['todate'] = $todate;
 		$filters['orderby'] = $orderby;
		$filters['module'] = $module;
 		
		$data = $this->model->getJSErrorsDetailedReportData($filters,$pageNumber, $rows);
		$totalResults = $data['totalResults'];
		//_p($data);die;
		$this->load->view("AppMonitor/jsErrors/detailedReport", array("data" => $data['result'], "filters" => $filters, "dashboard" => ENT_DASHBOARD_TYPE_JS_ERROR, "rows"=>$rows, "pageNumber" => $pageNumber, "totalResults" => $totalResults));
 	}
	
}
?>
