<?php

require 'AppMonitorAbstract.php';

class APIReport extends AppMonitorAbstract
{
	function __construct()
	{
		parent::__construct();
	}
	
	function dashboard($date)
	{
		
		$displayData['dashboardType'] =ENT_DASHBOARD_TYPE_API_REPORT;

		if(empty($date)){
			$date = date("d-m-Y");
		}
		$date = $this->_getDate($date);

		$this->apireportmodel = $this->load->model("common_api/apireportmodel");
		$reportData         = $this->apireportmodel->getAPIResponseReport($date);
		$minuteWiseData     = $this->apireportmodel->getMinutewiseAPIData($date);

		$this->_formatChartData($minuteWiseData, $displayData);
		
		$displayData['reportData']           = $reportData;
		$displayData['period']               = $period;
		$displayData['date']                 = $date;
		$displayData['minuteWiseData']       = $minuteWiseData;

		$this->load->view("AppMonitor/apireport/dashboard", $displayData);		
	}

	function _formatChartData($minuteWiseData, &$displayData){

		$minuteArr = array();
		$avgProcessingtimeArr = array();

		foreach ($minuteWiseData as $hour => $minuteWiseHitsData) {
			foreach ($minuteWiseHitsData as $minute => $d) {
				$minuteArr[] = array($hour.":".str_pad($minute*30, 2, "0"), intVal($d['cnt']), "<p class='tooltip'>At ".$hour.":".str_pad($minute*30, 2, "0")." <br/> ".intVal($d['cnt'])." Hits</p>");
				$avgProcessingtimeArr[] = array($hour.":".str_pad($minute*30, 2, "0"), intVal($d['avg_time']), "<p class='tooltip'>At ".$hour.":".str_pad($minute*30, 2, "0")." <br/> ".intVal($d['avg_time'])." sec</p>");
			}
		}
		$minuteArr            = json_encode($minuteArr);
		$avgProcessingtimeArr = json_encode($avgProcessingtimeArr);

		$displayData['minuteArr']            = $minuteArr;
		$displayData['avgProcessingtimeArr'] = $avgProcessingtimeArr;
	}

	function _getDate($date){

		// $date = date("Y-m-d ", strtotime("-".($dateId-1)." days"));
		$date = date("Y-m-d", strtotime($date));
		return $date;
	}

	function getMethodData(){

		$controller = $this->input->post("controller");
		$method = $this->input->post("method");
		$date = $this->input->post("date");

		$date = date("Y-m-d", strtotime($date));

		$this->apireportmodel = $this->load->model("common_api/apireportmodel");
		$minuteWiseData     = $this->apireportmodel->getMinutewiseAPIData($date, $controller, $method);

		$displayData = array();
		$this->_formatChartData($minuteWiseData, $displayData);
		echo json_encode($displayData);
	}
}
