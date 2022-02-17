<?php

require 'AppMonitorAbstract.php';

class GoogleWebLight extends AppMonitorAbstract
{
	function __construct()
	{
		parent::__construct();
	}
	
	function trends($serverName = "")
	{
		$displayData['dashboardType'] = ENT_DASHBOARD_TYPE_GOOGLEWEBLIGHT;
		$displayData['reportType'] = 'trends';
		
		$toDate = date('Y-m-d', strtotime('-1 Day'));
		$fromDate = date('Y-m-d',strtotime('-30 days',strtotime($toDate)));
		
		$webLightTrends = $this->model->getGoogleWebLightTrends($fromDate, $toDate);
		$clpWebLightTrends = $this->model->getGoogleWebLightTrendsForPage($fromDate, $toDate, 'courseDetailPage');
	
		$displayData['webLightTrendsChartData']    = $webLightTrends;
		$displayData['clpWebLightTrendsChartData'] = $clpWebLightTrends;
		
		$this->load->view("AppMonitor/googleWebLight/trends", $displayData);		
	}
}
