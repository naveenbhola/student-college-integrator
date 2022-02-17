<?php

require 'AppMonitorAbstract.php';

class PageSpeedScore extends AppMonitorAbstract
{
	function __construct()
	{
		parent::__construct();
	}
	
	function displayPageScores($moduleName = ''){
                $displayData = array();
		$displayData['dashboardType'] = ENT_DASHBOARD_TYPE_PAGESCORE;
		$displayData['reportType'] = 'trends';
		
                global $ENT_EE_MODULES;
                $modules = $ENT_EE_MODULES;
                $selectedModule = "";
                if(array_key_exists($moduleName, $ENT_EE_MODULES)) {
                        $selectedModule = $moduleName;
                }
                else {
                        $selectedModule = "shiksha";
                }

                $displayData['selectedModule'] = $selectedModule;
                $this->getModulesLinks($displayData, '/AppMonitor/PageSpeedScore', 'displayPageScores');
		
		
		$pageScores = $this->model->getLatestPageScore($moduleName);
		if(isset($pageScores[0])){
			$displayData['pageScores'] = $pageScores[0];
			$displayData['chartDetails'] = $pageScores[1];
		}
		
                if($selectedModule == 'shiksha') {
                        $modules[] = 'Overall';
                }
                else {
                        $modules = array($selectedModule);
                }
                $displayData['modules'] = $modules;
                $displayData['moduleName'] = $selectedModule;
		
		$this->load->view("AppMonitor/pageScore/pageScore", $displayData);				
	}
	
	function displayPageDetails($pageName = '', $device = 'desktop'){
		
                $displayData = array();
		$displayData['dashboardType'] = ENT_DASHBOARD_TYPE_PAGESCORE;
		$displayData['reportType'] = 'trends';
		
                global $ENT_EE_MODULES;
                $modules = $ENT_EE_MODULES;
                $selectedModule = "";
                if(array_key_exists($moduleName, $ENT_EE_MODULES)) {
                        $selectedModule = $moduleName;
                }
                else {
                        $selectedModule = "shiksha";
                }

                $displayData['selectedModule'] = $selectedModule;
                $this->getModulesLinks($displayData, '/AppMonitor/PageSpeedScore', 'displayPageScores');

                $toDate = $_REQUEST['trendEndDate'] ? $_REQUEST['trendEndDate'] : date("Y-m-d");
                $fromDate = $_REQUEST['trendStartDate'] ? $_REQUEST['trendStartDate'] : date("Y-m-d", strtotime("-90 day"));		
		$displayData['pageName'] = $pageName = str_replace('-',' ',$pageName);
		$displayData['device'] = $device;
		
		$pageDetails = $this->model->getPageDetails($pageName, $device, $fromDate, $toDate);	
		$displayData['pageDetails'] = $pageDetails;
		
		$displayData['speedScoreDetails'] = $this->_formatStatChartData($pageDetails, 'speedScore');
		$displayData['usabilityScoreDetails'] = $this->_formatStatChartData($pageDetails, 'usabilityScore');
		$displayData['httpRequestsDetails'] = $this->_formatStatChartData($pageDetails, 'numberOfResources');
		$displayData['resourceSizeDetails'] = $this->_formatSizeData($pageDetails);
		
                if($selectedModule == 'shiksha') {
                        $modules[] = 'Overall';
                }
                else {
                        $modules = array($selectedModule);
                }
                $displayData['modules'] = $modules;
                $displayData['moduleName'] = $selectedModule;

                $displayData['trendStartDate'] = date("m/d/Y", strtotime($fromDate));
                $displayData['trendEndDate'] = date("m/d/Y", strtotime($toDate));
		
		$this->load->view("AppMonitor/pageScore/pageDetails", $displayData);				
	}
	
        function _formatStatChartData($data, $value){
                $dataArr = array();
                foreach ($data as $dataVal) {
                    $newDate = date("d M,Y", strtotime($dataVal['creationDate']));
                    $dataArr[] = array($newDate, intVal($dataVal[$value]), "<p class='tooltip'>".$dataVal[$value]." on ".$newDate."</p>");
                }
                $dataArr            = json_encode($dataArr);
                return $dataArr;
        }
	
	function _formatSizeData($data){
                $dataArr = array();
                foreach ($data as $dataVal) {
                    $newDate = date("d M,Y", strtotime($dataVal['creationDate']));
                    $dataArr[] = array($newDate, intVal($dataVal['htmlResponseBytes']/1024), intVal($dataVal['cssResponseBytes']/1024), intVal($dataVal['javascriptResponseBytes']/1024), intVal($dataVal['imageResponseBytes']/1024));
                }
                $dataArr            = json_encode($dataArr);
                return $dataArr;		
	}
}
