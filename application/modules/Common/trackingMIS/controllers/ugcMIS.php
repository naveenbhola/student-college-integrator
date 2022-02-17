<?php
/**
 * Controller for UGC MIS.
*/

class ugcMIS extends MX_Controller
{
	private $trackingLib;

	function __construct()
	{
		parent::__construct();
		$this->trackingLib = $this->load->library('trackingMIS/trackingMISCommonLib');
	}

	private function _loadDependecies() {
		$data['userDataArray'] = reset($this->trackingLib->checkValidUser());
		$data['misSource'] = "UGC";
		$this->load->config('saTrackingMISConfig');		
		$data['leftMenuArray'] = $this->config->item("leftMenuArray");		
		return $data;
	}

	function dashboard()
	{		
		$data = $this->_loadDependecies();
 		$this->load->view('misTemplate', $data);
 	}	
}
	
?>