<?php

require 'AppMonitorAbstract.php';

class ClientSideMetrics extends AppMonitorAbstract
{
	function __construct()
	{
		parent::__construct();
	}
	

	function dashboard($page = 'homepage'){
		$clientSidePerformanceData = $this->model->getDailyPerformanceData($page);

		$formattedData = array();
		foreach ($clientSidePerformanceData as $value) {
			if($value['device'] == 'mobile'){
				$formattedData['mobile'][] = $value;
			}
			else{
				$formattedData['desktop'][] = $value;
			}
			// $formattedData[$value['trackingDate']][$value['pageName']][$value['device']] = $value;
		}

		$chartData = array();
		foreach ($formattedData as $key=>$v) {
			if($key == 'mobile'){
				foreach ($v as $value) {
					$chartData['mobile']['speedScore'][]              = array($value['trackingDate'], floatval($value['speedScore']));
					$chartData['mobile']['usabilityScore'][]          = array($value['trackingDate'], floatval($value['usabilityScore']));
					$chartData['mobile']['numberResources'][]         = array($value['trackingDate'], floatval($value['numberResources']));
					$chartData['mobile']['numberHosts'][]             = array($value['trackingDate'], floatval($value['numberHosts']));
					$chartData['mobile']['totalRequestBytes'][]       = array($value['trackingDate'], floatval($value['totalRequestBytes']));
					$chartData['mobile']['numberStaticResources'][]   = array($value['trackingDate'], floatval($value['numberStaticResources']));
					$chartData['mobile']['htmlResponseBytes'][]       = array($value['trackingDate'], floatval($value['htmlResponseBytes']));
					$chartData['mobile']['cssResponseBytes'][]        = array($value['trackingDate'], floatval($value['cssResponseBytes']));
					$chartData['mobile']['imageResponseBytes'][]      = array($value['trackingDate'], floatval($value['imageResponseBytes']));
					$chartData['mobile']['javascriptResponseBytes'][] = array($value['trackingDate'], floatval($value['javascriptResponseBytes']));
					$chartData['mobile']['otherResponseBytes'][]      = array($value['trackingDate'], floatval($value['otherResponseBytes']));
					$chartData['mobile']['numberJsResources'][]       = array($value['trackingDate'], floatval($value['numberJsResources']));
					$chartData['mobile']['numberCssResources'][]      = array($value['trackingDate'], floatval($value['numberCssResources']));
				}
			}
			else{

				foreach ($v as $value) {
				$chartData['desktop']['speedScore'][]              = array($value['trackingDate'], floatval($value['speedScore']));
				$chartData['desktop']['numberResources'][]         = array($value['trackingDate'], floatval($value['numberResources']));
				$chartData['desktop']['numberHosts'][]             = array($value['trackingDate'], floatval($value['numberHosts']));
				$chartData['desktop']['totalRequestBytes'][]       = array($value['trackingDate'], floatval($value['totalRequestBytes']));
				$chartData['desktop']['numberStaticResources'][]   = array($value['trackingDate'], floatval($value['numberStaticResources']));
				$chartData['desktop']['htmlResponseBytes'][]       = array($value['trackingDate'], floatval($value['htmlResponseBytes']));
				$chartData['desktop']['cssResponseBytes'][]        = array($value['trackingDate'], floatval($value['cssResponseBytes']));
				$chartData['desktop']['imageResponseBytes'][]      = array($value['trackingDate'], floatval($value['imageResponseBytes']));
				$chartData['desktop']['javascriptResponseBytes'][] = array($value['trackingDate'], floatval($value['javascriptResponseBytes']));
				$chartData['desktop']['otherResponseBytes'][]      = array($value['trackingDate'], floatval($value['otherResponseBytes']));
				$chartData['desktop']['numberJsResources'][]       = array($value['trackingDate'], floatval($value['numberJsResources']));
				$chartData['desktop']['numberCssResources'][]      = array($value['trackingDate'], floatval($value['numberCssResources']));
			}
		}
		}
		
		$displayData['dailyAverageData'] = $chartData;
		
		$modulesLinks = array();
		global $ENT_CLIENTSIDE_PERF_METRICS;
		global $clientSideModuleNames;
		foreach ($ENT_CLIENTSIDE_PERF_METRICS as $key => $value) {
			$modulesLinks[$clientSideModuleNames[$key]] = "/AppMonitor/ClientSideMetrics/dashboard/".$key;
		}

		$displayData['selectedPage'] = $page;
		$displayData['modulesLinks'] = $modulesLinks;
		$displayData['data'] = $formattedData;
		$displayData['dashboardType'] = ENT_DASHBOARD_TYPE_CLIENT_SIDE;
		$this->load->view("clientside/pagetrends", $displayData);
	}

}
/*
1. speedScore : Speed Score
2. usabilityScore : Usability Score (mobile)
3. numberResources : Number of Resources loaded
4. numberHosts : Number of Hosts
5. totalRequestBytes : Total Request Bytes
6. numberStaticResources : 
7. htmlResponseBytes : 
8. cssResponseBytes
9. imageResponseBytes
10. javascriptResponseBytes
11. otherResponseBytes
12. numberJsResources
13. numberCssResources
 */