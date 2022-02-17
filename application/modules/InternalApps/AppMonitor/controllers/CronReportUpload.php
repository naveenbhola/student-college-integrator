<?php

require 'AppMonitorAbstract.php';

class CronReportUpload extends AppMonitorAbstract
{
	private $ajaxURL;
	private $fileType;
	private $filePath;
	function __construct()
	{
		parent::__construct();
		$this->ajaxURL = "updateCron";
		$this->fileType = 'php';
		$this->isFilePath = false; 
	}

	/**
	* Function loads initial view that binds ajax call to _ajaxURL
	*/
	public function upload(){
		global $SHIKSHA_PROD_SERVERS;
		$displayData["ajaxURL"] = $this->ajaxURL;
		$displayData["servers"]	= $SHIKSHA_PROD_SERVERS;
		$this->load->view("AppMonitor/cronreport/cronreportupload",$displayData);
	}

	/**
	* Function where ajax call arrives with file content and server to be updated in POST. It parses the file and updates 			the db accordingly. 
	*/
	public function updateCron(){
		$file = $this->input->post('file');
		$file = trim($file);
		$server = $this->input->post('selectedServer');

		try{
			$file = $this->appMonitorLib->readCronTab($this->isFilePath,$file,$this->fileType,$server);
		}
		catch(Exception $e){
			echo "File parsing error. ".$e->getMessage();
			return;
		}

		try{
			$rows = $this->model->updateCronReport($file);
			echo "Success: Deleted ".$rows['rowsDeleted']." crons,"."  Inserted ".$rows['rowsInserted']." crons";
		}
		catch(Exception $e){
			echo "Error during db update: ".$e->getMessage().", Code:".$e->getCode();
			return;
		}
	}
}
