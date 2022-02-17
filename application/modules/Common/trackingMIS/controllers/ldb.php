<?php
/**
 * Controller for Study abroad MIS.
*/
exit;
class ldb extends MX_Controller
{
	private $trackingLib;

	function __construct()
	{
		parent::__construct();
		$this->trackingLib = $this->load->library('trackingMIS/trackingMISCommonLib');
		$this->ldbTrackingLib = $this->load->library('trackingMIS/ldbTrackingLib');
	}

	private function _loadDependecies() {
		$data['userDataArray'] = reset($this->trackingLib->checkValidUser());
		$data['misSource'] = "ldb";
		$this->load->config('ldbTrackingMISConfig');		
		$data['leftMenuArray'] = $this->config->item("leftMenuArray");		
		return $data;
	}

	function dashboard()
	{	
		//$this->load->view('sample');	
		$data = $this->_loadDependecies();
 		$this->load->view('trackingMIS/misTemplate', $data);
 	}


 	function LeadMatching($isMR)
 	{		
 		$data = $this->_loadDependecies();
 		$data['ajaxUrl'] = "/trackingMIS/ldb/getLeadMatchingData/".$isMR;
 		$this->load->view('trackingMIS/misTemplate', $data);
 	}	

 	function getLeadMatchingData($isMR)
 	{
 		$filterArray = array();
 		$filterArray['startDate'] = $this->input->get('startDate');
 		$filterArray['endDate'] = $this->input->get('endDate');
 		$filterArray['isMR'] = $isMR;

 		$LeadMatchingData = $this->ldbTrackingLib->getLeadMatchingData($filterArray);
 		echo json_encode($LeadMatchingData);
 	}

 	function LeadAllocation($isMR)
 	{		
 		$data = $this->_loadDependecies();
 		$data['ajaxUrl'] = "/trackingMIS/ldb/getLeadAllocationData/".$isMR;
 		$this->load->view('trackingMIS/misTemplate', $data);
 	}	

 	function getLeadAllocationData($isMR)
 	{
 		$filterArray = array();
 		$filterArray['startDate'] = $this->input->get('startDate');
 		$filterArray['endDate'] = $this->input->get('endDate');
 		$filterArray['isMR'] = $isMR;

 		$LeadAllocationData = $this->ldbTrackingLib->getLeadAllocationData($filterArray);
 		echo json_encode($LeadAllocationData);
 	}
}
	
?>	
