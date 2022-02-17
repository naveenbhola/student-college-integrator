<?php

require 'AppMonitorAbstract.php';

class CronReport extends AppMonitorAbstract
{
	private $servermappings;
	private $fileType;
	private $ajaxURL;
	function __construct()
	{
		parent::__construct();
		$this->servermappings = array('10.10.16.91'=>'http://localhost/dev/cron/server91.txt',
									'10.10.16.92'=>'http://localhost/dev/cron/server92.txt'
				   					);	// path useless if reading from db
		$this->fileType = false;	//read from database if false
		$this->ajaxURL = "getData";
	}
	

	/**
	* Function loads initial view that sets up ajax call for file read
	*/
	public function cronjobs()
	{
		global $SHIKSHA_PROD_SERVERS;
	
		$displayData['dashboardType'] = ENT_DASHBOARD_TYPE_CRON_REPORT;
		$displayData["ajaxURL"] = $this->ajaxURL;
		//$linkIterator = $ENT_EE_SERVERS;
		$linkIterator = $SHIKSHA_PROD_SERVERS;
		
		$modulesLinks = array();
		$modulesLinks["Shiksha"] = '';
		foreach ($linkIterator as $key => $value) {
			$modulesLinks[$value] = '';
		}
		$displayData['modulesLinks'] = $modulesLinks;
        
        $displayData['fileType'] = json_encode($this->fileType);
		
		$this->load->view("AppMonitor/cronreport/cronreport", $displayData);	
	}


	/**
	* Function to read cron file
	* @args: file path string
	* @returns: n X 2 array, where n is number of cron jobs, each with cron time expression & cron command 
	*/
	public function getData(){
		$type = 'file';
		if(null!==json_decode($this->input->post('file'))){
			$type = json_decode($this->input->post('file'))?'file':'database';
		}

		if($type=='file'){
			
			$file = $this->_getFilePaths();
			$cronType = 'php';
			$server = null;

			$tmpArr = Array();
			foreach ($file as $server => $path) {
				$serverCron = $this->appMonitorLib->readCronTab(true,$path,$cronType,$server);
				for($i=0;$i<sizeof($serverCron);$i++){
					$serverCron[$i]['cronServer'] = $server;
				}
				$tmpArr = array_merge($tmpArr, $serverCron);	
			}
			echo json_encode($tmpArr);
		}
		else if($type=='database'){
			//model already  loaded in constructor
			$crons = $this->model->getCronReport('Shiksha','live');
			echo json_encode($crons);
		}
	}

	/*
	* Function to return paths mapped tp server
	*/
	private function _getFilePaths(){
		return $this->servermappings;
	}
}
