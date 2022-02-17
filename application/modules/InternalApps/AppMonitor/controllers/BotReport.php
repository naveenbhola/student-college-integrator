<?php

require 'AppMonitorAbstract.php';

class BotReport extends AppMonitorAbstract
{
	function __construct()
	{
		parent::__construct();
	}
	
	function trends($botStatus = "")
	{
		global $BOT_STATUSES;
    	
		$selectedBotStatus = "";
		if(array_key_exists($botStatus, $BOT_STATUSES)) {
			$selectedBotStatus = $botStatus;
		}
		else {
			$selectedBotStatus = "all";
		}
		
		$displayData['dashboardType'] = ENT_DASHBOARD_TYPE_BOTREPORT;
		$displayData['reportType'] = 'trends';
		$displayData['botStatus'] = $selectedBotStatus;
		$this->getModulesLinks($displayData, '/AppMonitor/BotReport', 'trends', 'botstatus');
		
		$toDate = $_REQUEST['trendEndDate'] ? $_REQUEST['trendEndDate'] : date("Y-m-d", strtotime("-1 day"));
		$fromDate = $_REQUEST['trendStartDate'] ? $_REQUEST['trendStartDate'] : date("Y-m-d", strtotime("-30 day"));
		
		$trends = $this->model->getBotTrends($fromDate, $toDate, $selectedBotStatus);
		$captchaTrends = $this->model->getCaptchaTrends($fromDate, $toDate, $selectedBotStatus);
        //_p($captchaTrends); exit();
		$displayData['trendsChartData'] = $this->prepareTrendChartData($trends, 'botstatus', $selectedBotStatus, $fromDate, $toDate);
		$displayData['captchaTrendsChartData'] = $this->prepareTrendChartData($captchaTrends, 'captcha', $selectedBotStatus, $fromDate, $toDate);
		
        $botStatuses = $BOT_STATUSES;
		if($selectedBotStatus == 'all') {
			$botStatuses[] = 'All';
		}
		else {
			$botStatuses = array($BOT_STATUSES[$selectedBotStatus]);
		}
		$displayData['statuses'] = $botStatuses;
        
        $displayData['captchaStatuses'] = array('show_captcha', 'verify_captcha');
		$displayData['colors'] = $this->getChartColors('botstatus', $selectedBotStatus);
        $displayData['captchaColors'] = $this->getChartColors('captcha', $selectedBotStatus);
		
		$displayData['trendStartDate'] = date("m/d/Y", strtotime($fromDate));
		$displayData['trendEndDate'] = date("m/d/Y", strtotime($toDate));
		
        //_p($displayData['captchaColors']); exit();
        
		$this->load->view("AppMonitor/botReport/trends", $displayData);		
	}
	
	function yesterday($botStatus = "")
	{
		$this->realtime($botStatus, 'yesterday', $this->yesterdayDate);
	}
	
	function realtime($botStatus = "", $reportType = 'realtime', $realtimeDate = '')
	{
		global $BOT_STATUSES;
    	
		$selectedBotStatus = "";
		if(array_key_exists($botStatus, $BOT_STATUSES)) {
			$selectedBotStatus = $botStatus;
		}
		else {
			$selectedBotStatus = "all";
		}
		
		$displayData['dashboardType'] = ENT_DASHBOARD_TYPE_BOTREPORT;
		$displayData['reportType'] = $reportType;
		$displayData['botStatus'] = $selectedBotStatus;
		
		$this->getModulesLinks($displayData, '/AppMonitor/BotReport', $reportType, 'botstatus');
		
        if($reportType == 'yesterday') {
            $realtimeData = $this->getRealTimeData($selectedBotStatus, $realtimeDate, 0, -1, TRUE);
        }
        else {
            $realtimeDate = $this->realtimeDate;
            $realtimeData = $this->getRealTimeData($selectedBotStatus, $realtimeDate);
        }

    	$displayData['timeDistribution'] = $realtimeData['timeDistribution'];
    	$displayData['resultTable'] = $realtimeData['resultTable'];
		$displayData['resultCount'] = $realtimeData['count'];
    	$displayData['statuses'] = $BOT_STATUSES;
        $displayData['selectedFilter'] = $selectedBotStatus;
    	
		$displayData['tableHeading'] = 'Bots Detected';
		$displayData['ajaxURL'] = '/AppMonitor/BotReport/getRealTimeData';
		$displayData['realtimeDate'] = $realtimeDate;
		$displayData['reportType'] = $reportType;
		
		if($reportType == 'yesterday') {
			$displayData['otherDate'] = date("m/d/Y", strtotime($realtimeDate));
		}
		
    	$this->load->view('AppMonitor/common/realtime', $displayData);
	}
	
	function getRealTimeData($statusName, $realtimeDate, $isAjax = 0, $rowNum = -1, $yesterday = FALSE)
	{
		global $BOT_STATUSES;
    	$botStatuses = $BOT_STATUSES;
		
		$data = array();
		
		$realtimeData = $this->model->getBotReportRealTimeStats($statusName, $realtimeDate);
    	$data['count'] = $this->getRealTimeCount($realtimeData);
		$data['timeDistribution'] = $this->getTimeDistribution($realtimeData);
		
		$realtimeDataParams = $this->getRealTimeDataParams($rowNum);
		$realtimeDataParams['yesterday'] = $yesterday;
        if($yesterday) {
            unset($realtimeDataParams['limit']);
            $realtimeDataParams['order'] = 'ASC';
        }
		$result = $this->model->getBotReportRealTimeData($statusName, $realtimeDate, $realtimeDataParams);
		$data['resultTable'] = $this->load->view('AppMonitor/botReport/realtimeTable',array('result' => $result, 'statuses' => $botStatuses), TRUE);
		$data['timeWindowStart'] = $realtimeDataParams['timeStart'];
		$data['timeWindowEnd'] = $realtimeDataParams['timeEnd'];
		$data['numResults'] = count($result);
        
		//$data['sortbox'] = $this->load->view('AppMonitor/common/sortbox', array('dashboard' => ENT_DASHBOARD_TYPE_SLOWQUERY, 'sortURL' => '/AppMonitor/SlowQueries/sortRealTimeData'), TRUE);
		
		if($isAjax) {
			echo json_encode($data);
		}
		else {
			return $data;
		}
	}
	
	function detailedreport($botStatus = '')
	{
		global $BOT_STATUSES;
    	
		$selectedBotStatus = "";
		if(array_key_exists($botStatus, $BOT_STATUSES)) {
			$selectedBotStatus = $botStatus;
		}
		else {
			$selectedBotStatus = "all";
		}
		
		$displayData['dashboardType'] = ENT_DASHBOARD_TYPE_BOTREPORT;
		$displayData['reportType'] = 'detailedreport';
		$displayData['botStatus'] = $selectedBotStatus;
		$this->getModulesLinks($displayData, '/AppMonitor/BotReport', 'detailedreport', 'botstatus');
        
        //_p($displayData['modulesLinks']); exit();
		
		$displayData['sorters'] = array('status' => 'Status', 'ip' => 'IP', 'ua' => 'User Agent');
		$displayData['ajaxURL'] = "/AppMonitor/BotReport/getDetailedReportData";
		$displayData['defaultDate'] = $this->detailedReportDate;
		
		$this->load->view("AppMonitor/common/detailedReport", $displayData);
	}
	
	function getDetailedReportData()
	{
		global $BOT_STATUSES;
    	
		$toDate = date('Y-m-d',strtotime($this->input->post('todate')));
		$fromDate = date('Y-m-d',strtotime($this->input->post('fromdate')));	
		$botStatus = $this->input->post('botStatus');
		$orderby = $this->input->post("orderby");
				
        $data['result'] = $this->model->getBotReportDetailedData($botStatus, $fromDate, $toDate, $orderby);	            
		
		$data['fromDate'] = $fromDate;
		$data['toDate'] = $toDate;
		$data['sorter'] = $sorter;
        $data['statuses'] = $BOT_STATUSES;

		$this->load->view("AppMonitor/botReport/middlePanel", $data);
    }
}
