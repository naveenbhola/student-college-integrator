<?php

require 'AppMonitorAbstract.php';

class CiCd extends AppMonitorAbstract {
	function index() {
		global $ENT_EE_SERVERS;
    	$servers = $ENT_EE_SERVERS;
		
		$displayData['dashboardType'] = ENT_DASHBOARD_CICD;
		$displayData['app'] = "cicd";
		
		$this->getDashboardData($displayData);	

		$this->load->view("AppMonitor/cicd/cicd", $displayData);
	}
}