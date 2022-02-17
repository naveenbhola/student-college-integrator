<?php

require 'AppMonitorAbstract.php';

class JSB9Tracking extends AppMonitorAbstract{
	function __construct(){
		parent::__construct();
	}
	
	function pagetrends($moduleName = ""){
		global $ENT_EE_MODULES;
		$modules = $ENT_EE_MODULES;
		$selectedModule = "";
		if(array_key_exists($moduleName, $ENT_EE_MODULES)) {
			$selectedModule = $moduleName;
		}else{
			$selectedModule = "shiksha";
		}
		
		$displayData = array();
		$displayData['dashboardType'] = ENT_DASHBOARD_TYPE_JSB9_REPORT;
		$displayData['reportType'] = 'pagetrends';
		$displayData['selectedModule'] = $selectedModule;
		$this->getModulesLinks($displayData, '/AppMonitor/JSB9Tracking', 'pagetrends');
	
		$reqParams = array();
		if($_REQUEST['trendStartDate']) {
			$reqParams[] = 'trendStartDate='.$_REQUEST['trendStartDate'];
		}
		if($_REQUEST['trendEndDate']) {
			$reqParams[] = 'trendEndDate='.$_REQUEST['trendEndDate'];
		}
		if($_REQUEST['sourceApplication']) {
			$reqParams[] = 'sourceApplication='.$_REQUEST['sourceApplication'];
		}
		
		$reqParamLink = '';
		if(count($reqParams) > 0) {
			$reqParamLink = '?'.implode('&', $reqParams);
		}
		
		$displayData['iframeUrl'] = '/JSB9Tracking/JSB9Tracking/pagetrends/shiksha/'.$selectedModule.$reqParamLink;
		$this->load->view("AppMonitor/JSB9Tracking/pagetrends", $displayData);
	}
}
