<?php

include('Sums_Common.php');
/**
 * Controller class for Sales MIS
 * 
 */
class TargetInput extends Sums_Common {
	
	public $appId = 1;
	public $prodId = 26;
	private $aclId = 0;
	private $Target_Input = "Target_Input";
	private $UserGroupId = '5';
	function init() {
		$this->load->helper(array('form', 'url','date','image'));
		$this->load->library('sums_mis_common');
		$this->load->library('Sums_targetInput');
	}
	
	function collection() {
		echo "This functionality is not in use.";
		exit;
		$this->init();
		$data = array();
		$data['sumsUserInfo'] = $this->sumsUserValidation(47,$this->prodId);
		$data['prodId'] = $this->prodId;
		$data['type'] = $this->Target_Input;
		$objSumsManage = new Sums_mis_common();
		$data['branchList'] = $objSumsManage->getAllBranches($this->appId,$request,$data['sumsUserInfo']['userid']);
		$Sums_targetInput = new Sums_targetInput();
		$data['AllQuarters'] = $Sums_targetInput->getAllQuarters($this->appId);
		$this->load->view('sums/targetHome',$data);
	}
	
	function getAllExecutiveData($id,$Quarter,$year) {
		$this->init();
		$data = array();
		$managerList = array();
		$objSumsManage = new Sums_mis_common();
		$managerList = $objSumsManage->getAllExecutive($this->appId,$id,$this->UserGroupId);
		$Sums_targetInput = new Sums_targetInput();
		$data['result'] = $Sums_targetInput->getExecutiveData($this->appId,$managerList,$id,$Quarter,$year);
		$data['AllQuarters'] = $Sums_targetInput->getAllQuarters($this->appId);
		$this->load->view('sums/alltargets',$data);
	}
	
	function savetargets()
		{
		$this->init();
		$data = array();
		$data['sumsUserInfo'] = $this->sumsUserValidation(47,$this->prodId);
		$data['prodId'] = $this->prodId;
		$data['type'] = $this->Target_Input;
		$objSumsManage = new Sums_mis_common();
		$data['branchList'] = $objSumsManage->getAllBranches($this->appId,$request,$data['sumsUserInfo']['userid']);
		$Sums_targetInput = new Sums_targetInput();
		$data['AllQuarters'] = $Sums_targetInput->getAllQuarters($this->appId);
		$branchid = $this->input->post('branchid');
		$quarter = $this->input->post('quarter');
		$tar_id = $this->input->post('tar_id');
		$exe_id = $this->input->post('exe_id');
		$exe_target = $this->input->post('exe_target');
		$year = $this->input->post('year');
		$assigned_by = $data['sumsUserInfo']['userid'];
		$exe_array = array();
		$i = 0;
		foreach ($exe_id as $exeid) {
			$exe_array[$exeid] = array($exe_target[$i],$tar_id[$i]);
			$i++;	
		}
		$Sums_targetInput = new Sums_targetInput();
		$data['crud_result'] = $Sums_targetInput->updateExecutiveData($this->appId,$exe_array,$branchid,$year,$quarter,$assigned_by);
		$this->load->view('sums/targetHome',$data);	
		}
	
	function Handle_Report($type)
		{
			echo "This functionality is not in use.";
			exit;
		$this->init();
		$data = array();
		$aclId = 0;
		if ($type == 1) {
			$aclId = 48;
		} elseif ($type == 2) {
			$aclId = 49;
		} elseif ($type == 3) {
			$aclId = 50;
		} else {
			$aclId = 47;
		}
		$data['sumsUserInfo'] = $this->sumsUserValidation($aclId,$this->prodId);
		$data['prodId']       = $this->prodId;
		$data['type']         = $this->_check_report_type($type);
		$objSumsManage        = new Sums_mis_common();
		$data['branchList']   = $objSumsManage->getAllBranches($this->appId,$request,$data['sumsUserInfo']['userid']);
		$Sums_targetInput     = new Sums_targetInput();
		$data['AllQuarters']  = $Sums_targetInput->getAllQuarters($this->appId);
		$this->load->view('sums/targetHome',$data);
		}
	
	function _check_report_type ($type)
		{
		switch ($type) {
			case '1':
				$this->Target_Input = 'Month_till_date_sales_Report';
				break;
			case '2':
				$this->Target_Input = 'Quarter_till_date_sales_Report';
				break;
			case '3':
				$this->Target_Input = 'Product_MIX_Report';
				break;
			default:
				$this->Target_Input = 'Target_Input';
		}
		return $this->Target_Input;
		}
	
	function DisplayReports()
		{
		$this->init();
		$search_array        = array();
		$data                = array();
		$data['prodId']      = $this->prodId;
		$objSumsManage       = new Sums_mis_common();
		$data['branchList']  = $objSumsManage->getAllBranches($this->appId,$request,$data['sumsUserInfo']['userid']);
		$Sums_targetInput    = new Sums_targetInput();
		$data['AllQuarters'] = $Sums_targetInput->getAllQuarters($this->appId);
		$report_type         = $this->input->post('report_type');
		$data['type']        = $report_type;
		if ($report_type == 'Product_MIX_Report') {
			$data['sumsUserInfo']               = $this->sumsUserValidation(50,$this->prodId);
			$search_array['report_type']        = $report_type;
			$search_array['Output_Value']       = $this->input->post('Output_Value');
			$search_array['trans_start_date']   = $this->input->post('trans_start_date');
			$search_array['trans_end_date']     = $this->input->post('trans_end_date');
			$search_array['sums_mis_branch']    = $this->handle_multipal_combo($this->input->post('sums_mis_branch'));
			$search_array['sums_mis_executive'] =$this->handle_multipal_combo($this->input->post('sums_mis_executive'));
		} elseif ($report_type == 'Month_till_date_sales_Report') {
			$data['sumsUserInfo']            = $this->sumsUserValidation(48,$this->prodId);
			$search_array['report_type']     = $report_type;
			$search_array['year']            = $this->input->post('year');
			$search_array['month']           = $this->input->post('month');
			$search_array['sums_mis_branch'] = $this->handle_multipal_combo($this->input->post('sums_mis_branch'));
		} elseif ($report_type == 'Quarter_till_date_sales_Report') {
			$data['sumsUserInfo']            = $this->sumsUserValidation(49,$this->prodId);
			$search_array['report_type']     = $report_type;
			$search_array['Quarters']        = $this->input->post('Quarters');
			$search_array['year']            = $this->input->post('year');
			$search_array['sums_mis_branch'] = $this->handle_multipal_combo($this->input->post('sums_mis_branch'));
		}
		$data['search_array'] = $search_array;
		$Sums_targetInput     = new Sums_targetInput();
		$data['ReportResult'] = $Sums_targetInput->handleReport($this->appId,$report_type,$search_array);
		$this->load->view('sums/SumsTargetResultDisplay',$data);
		}
	
	private function handle_multipal_combo($array)
		{
		$requestStr = '';
		if (in_array('-1',$array)) {
			if (count($array) <= 1) {
				$requestStr = "-1";
			} elseif(count($array) > 1) {
				// Hack here if user select "ALL" with other options
				unset($array[0]);
				$requestStr = implode (",",$array);
			}
		} else {
			$requestStr = implode (",",$array);
		}
		return $requestStr;
		}
}