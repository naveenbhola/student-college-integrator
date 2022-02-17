<?php

require 'AppMonitorAbstract.php';

class Exceptions extends AppMonitorAbstract
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
		$displayData['dashboardType'] = ENT_DASHBOARD_TYPE_EXCEPTION;
		$displayData['reportType'] = 'trends';
		$displayData['selectedModule'] = $selectedModule;
		$this->getModulesLinks($displayData, '/AppMonitor/Exceptions', 'trends');
				
		$toDate = $_REQUEST['trendEndDate'] ? $_REQUEST['trendEndDate'] : date("Y-m-d");
		$fromDate = $_REQUEST['trendStartDate'] ? $_REQUEST['trendStartDate'] : date("Y-m-d", strtotime("-30 day"));
		
		$trends = $this->model->getDayWiseExceptions($selectedModule, $fromDate,$toDate);
		$uniqueTrends = $this->model->getUniqueDayWiseExceptions($selectedModule, $fromDate,$toDate);
		
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
		
		$displayData['dashboardType'] = ENT_DASHBOARD_TYPE_EXCEPTION;
		$displayData['reportType'] = $reportType;
		$displayData['selectedModule'] = $selectedModule;
		$this->getModulesLinks($displayData, '/AppMonitor/Exceptions', $reportType);

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
		
		$displayData['tableHeading'] = 'Exceptions';
		$displayData['ajaxURL'] = '/AppMonitor/Exceptions/getRealTimeData';
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
		
		$realtimeData = $this->model->getRealTimeExceptionData($moduleName, $realtimeDate);
		$data['count'] = $this->getRealTimeCount($realtimeData);
 		$data['timeDistribution'] = $this->getTimeDistribution($realtimeData);
		
		$realtimeDataParams = $this->getRealTimeDataParams($rowNum);
		$realtimeDataParams['yesterday'] = $yesterday;
		$result = $this->model->getTopExceptions($moduleName, $realtimeDate, $realtimeDataParams);
		$data['resultTable'] = $this->load->view('AppMonitor/exceptions/resultTable', array('todaysTopExceptions' => $result, 'dtype' => ENT_DASHBOARD_TYPE_EXCEPTION), TRUE);
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
		
		$displayData['dashboardType'] = ENT_DASHBOARD_TYPE_EXCEPTION;
		$displayData['reportType'] = 'detailedreport';
		$displayData['selectedModule'] = $selectedModule;
		$this->getModulesLinks($displayData, '/AppMonitor/Exceptions', 'detailedreport');
		
		$displayData['sorters'] = array('time' => 'Occurence Time','occurrence' => 'Number of Occurrences');
		$displayData['ajaxURL'] = "/AppMonitor/Exceptions/getDetailedReportData";
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
 		
		$data = $this->model->getExceptionDetailedData($filters);
		$this->load->view("AppMonitor/exceptions/detailedReport", array("data" => $data, "filters" => $filters, "dashboard" => ENT_DASHBOARD_TYPE_EXCEPTION));
 	}

 	function showStackTrace(){

        $exceptionId = $this->input->post('id');
        $type = $this->input->post('type');

        $result = $this->model->getExceptionDetails($exceptionId);
        $html = "<h2>Stack Trace Details</h2><br/>";
        if(empty($result['identifier']) && empty($result['stack_trace'])){
                echo "<h2>No Details Available !!!</h2>";return;
        }

        if($result['identifier'])
                $html .= "<h4>Exception Identifier</h4><p>".htmlentities($result['identifier'])."</p><br/>";

        if($result['stack_trace'])
                $html .= "<h4>Stack Trace</h4><p>".htmlentities($result['stack_trace'])."</p>";

        echo $html;
    }
}
