<?php

require 'AppMonitorAbstract.php';

class HighSQLQueries extends AppMonitorAbstract
{
	function __construct()
	{
		parent::__construct();
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
		
		$displayData['dashboardType'] = ENT_DASHBOARD_TYPE_HIGH_SQL_QUERIES;
		$displayData['reportType'] = $reportType;
		$displayData['selectedModule'] = $selectedModule;
		$this->getModulesLinks($displayData, '/AppMonitor/HighSQLQueries', $reportType);

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
		
		$displayData['tableHeading'] = 'High SQL Queries';
		$displayData['ajaxURL'] = '/AppMonitor/HighSQLQueries/getRealTimeData';
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
		$excludeCron = $_COOKIE['excludeCron'];
		if($excludeCron == ''){
			$excludeCron = 0;
		}

		$realtimeData = $this->model->getRealTimeHighSQLData($moduleName, $realtimeDate, $excludeCron);
		$data['count'] = $this->getRealTimeCount($realtimeData);
 		$data['timeDistribution'] = $this->getTimeDistribution($realtimeData);
		
		$realtimeDataParams = $this->getRealTimeDataParams($rowNum);
		$realtimeDataParams['yesterday'] = $yesterday;
		$result = $this->model->getTotalRealtimeHighSQLQueries($moduleName, $realtimeDate, $realtimeDataParams, $excludeCron);
		$data['resultTable'] = $this->load->view('AppMonitor/highsql/resultTable', array('realtimedata' => $result, 'dtype' => ENT_DASHBOARD_TYPE_MEMORY), TRUE);
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

	function yesterday($serverName = "")
	{
		$this->realtime($serverName, 'yesterday', $this->yesterdayDate);
	}
	
}
