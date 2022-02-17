<?php

/**
 * Class for Sums Sales MIS web Services
 * 
 */
class TargetInputServer extends MX_Controller {
	
	/**
	 *	index function to recieve the incoming request
	 */
	
	function index(){
		
		//load XML RPC Libs
		$this->load->library('xmlrpc');
		$this->load->library('xmlrpcs');
		
		//Define the web services method
		
		$config['functions']['getTargetDetails'] = array('function' => 'TargetInputServer.getTargetDetails');
		$config['functions']['updateTargetDetails'] = array('function' => 'TargetInputServer.updateTargetDetails');
		$config['functions']['deleteTarget'] = array('function' => 'TargetInputServer.deleteTarget');
		$config['functions']['getALLTargetBranch'] = array('function' => 'TargetInputServer.getALLTargetBranch');
		$config['functions']['getALLTarget'] = array('function' => 'TargetInputServer.getALLTarget');
		$config['functions']['getAllTargetManagers'] = array('function' => 'TargetInputServer.getAllTargetManagers');
		$config['functions']['getAllQuarters'] = array('function' => 'TargetInputServer.getAllQuarters');
		$config['functions']['getAllYears'] = array('function' => 'TargetInputServer.getAllYears');
		$config['functions']['updateExecutiveData'] = array('function' => 'TargetInputServer.updateExecutiveData');
		$config['functions']['getExecutiveData'] = array('function' => 'TargetInputServer.getExecutiveData');
		$config['functions']['Month_till_date_sales_Report'] = array('function' => 'TargetInputServer.Month_till_date_sales_Report');
		$config['functions']['Quarter_till_date_sales_Report'] = array('function' => 'TargetInputServer.Quarter_till_date_sales_Report');
		$config['functions']['Product_MIX_Report'] = array('function' => 'TargetInputServer.Product_MIX_Report');
		
		//initialize
		$args = func_get_args(); $method = $this->getMethod($config,$args);
		return $this->$method($args[1]);
	}
	/**
	 * Function to get Traget Details
	 */
	function getTargetDetails($request)
		{
		error_log_shiksha("IN server ravi");
		$parameters = $request->output_parameters();
		$appID = $parameters['0'];
		$targetId = $parameters['1'];
		error_log_shiksha(print_r($parameters,true)."ravi123");
		$this->load->database();
		$queryCmd = "select * from SUMS.target_sums where is_active = '1' and id=?";
		$query = $this->db->query($queryCmd, array($targetId));
		error_log_shiksha($queryCmd."ravi1234");
		$msgArray = array();
		foreach ($query->result() as $row){
			array_push($msgArray,array(
				array(
					'id'=>array($row->id,'string'),
					'branch_id'=>array($row->branch_id,'string'),
					'executive_id'=>array($row->executive_id,'string'),
					'assigned_by'=>array($row->assigned_by,'string'),
					'quarter'=>array($row->quarter,'string'),
					'year'=>array($row->year,'string'),
					'target'=>array($row->target,'string'),
					'logged'=>array($row->logged,'string'),
					'is_active'=>array($row->is_active,'string')
				),'struct'));//close array_push
		}
		$response = array($msgArray,'struct');
		error_log_shiksha("Ravi Info ".print_r($response,true));
		return $this->xmlrpc->send_response($response);
		}
	/**
	 * Function to update Target Details
	 * 
	 */
	private function updateTargetDetails($appID,$targetId,$branch_id,$executive_id,$assigned_by,$quarter,$year,$target)
		{
		$this->load->database();
		$msgArray = array();
		
		if ($targetId == "-1") { 
			$queryCmd = "insert into SUMS.target_sums values('', '".mysql_escape_string($branch_id)."','".mysql_escape_string($executive_id)."', '".mysql_escape_string($assigned_by)."','".mysql_escape_string($quarter)."', '".mysql_escape_string($year)."', '".mysql_escape_string($target)."',now(), '1')";
			error_log_shiksha($queryCmd."ravi1234");
			$query = $this->db->query($queryCmd);
			$insertedtargetId = $this->db->insert_id();
		} else {
			$queryCmd = "update SUMS.target_sums set branch_id='".mysql_escape_string($branch_id)."', executive_id='".mysql_escape_string($executive_id)."', assigned_by='".mysql_escape_string($assigned_by)."',quarter='".mysql_escape_string($quarter)."',year='".mysql_escape_string($year)."', target='".mysql_escape_string($target)."' where id=".$this->db->escape($targetId)." and is_active='1'";
			error_log_shiksha($queryCmd."ravi1234");
			$query = $this->db->query($queryCmd);
			$insertedtargetId = $targetId;
		}
		$msgArray = $insertedtargetId;
		return $msgArray;
		}
	/**
	 * Function to delete Target
	 */
	function deleteTarget($request)
		{
		error_log_shiksha("IN server ravi");
		$parameters = $request->output_parameters();
		$appID = $parameters['0'];
		$targetId = $parameters['1'];
		error_log_shiksha(print_r($parameters,true)."ravi123");
		$this->load->database();
		$msgArray = array();
		$queryCmd = "update SUMS.target_sums set is_active='0' where id=? and is_active='1'";
		$query = $this->db->query($queryCmd, array($targetId));
		error_log_shiksha($queryCmd."ravi1234");
		$insertedtargetId = $targetId;
		$msgArray[0] = $insertedtargetId;
		$response = array($msgArray,'struct');
		error_log_shiksha(print_r($insertedtargetId,true)."Ravi");
		return $this->xmlrpc->send_response($response);
		}
	/**
	 * Function to get branch specific target
	 */
	function getALLTargetBranch($request)
		{
		error_log_shiksha("IN server ravi");
		$parameters = $request->output_parameters();
		$appID = $parameters['0'];
		$branchId = $parameters['1'];
		error_log_shiksha(print_r($parameters,true)."ravi123");
		$this->load->database();
		$queryCmd = "select * from SUMS.target_sums where is_active = '1' and branch_id=?";
		$query = $this->db->query($queryCmd, array($branchId));
		error_log_shiksha($queryCmd."ravi1234");
		$msgArray = array();
		foreach ($query->result() as $row){
			array_push($msgArray,array(
				array(
					'id'=>array($row->id,'string'),
					'branch_id'=>array($row->branch_id,'string'),
					'executive_id'=>array($row->executive_id,'string'),
					'assigned_by'=>array($row->assigned_by,'string'),
					'quarter'=>array($row->quarter,'string'),
					'year'=>array($row->year,'string'),
					'target'=>array($row->target,'string'),
					'logged'=>array($row->logged,'string'),
					'is_active'=>array($row->is_active,'string')
				),'struct'));//close array_push
		}
		$response = array($msgArray,'struct');
		error_log_shiksha("Ravi Info ".print_r($response,true));
		return $this->xmlrpc->send_response($response);
		}
	/**
	 * Function to get Executive data
	 */
	function getExecutiveData($request)
		{
		error_log_shiksha("IN server ravi");
		$parameters = $request->output_parameters();
		$appID = $parameters['0'];
		$branchId = $parameters['2'];
		$managerList = json_decode($parameters['1'],true);
		$Quarter = $parameters['3'];
		$year = $parameters['4'];
		$this->load->database();
		$msgArray = array();
		$j=0;
		error_log_shiksha("countmgr".count($managerList));
		foreach ($managerList as $key=>$value) {
			$id = $value['userId'];
			$BranchName = $value['BranchName'];
			$queryCmd = "select *,shiksha.tuser.displayname from SUMS.target_sums,shiksha.tuser where SUMS.target_sums.executive_id=shiksha.tuser.userid and is_active = '1' and ";
			$queryCmd .=" branch_id=? and executive_id=?";
			$queryCmd .=" and quarter=? and year=?";
			$query = $this->db->query($queryCmd, array($branchId, $id, $Quarter, $year));
			error_log_shiksha("Ravi".$queryCmd);
			if (count($query->result()) > 0 ) { 
				foreach ($query->result() as $row){
					$msgArray[$j][0]['BranchName'] = $BranchName;
					$msgArray[$j][0]['Branchid'] = $branchId;
					$msgArray[$j][0]['executive_id'] = $row->executive_id;
					$msgArray[$j][0]['executive_name'] = $row->displayname;
					$msgArray[$j][0]['executive_target'] = $row->target;
					$msgArray[$j][0]['quarter'] = $row->quarter;
					$msgArray[$j][0]['year'] = $row->year;
					$msgArray[$j][0]['targetId'] = $row->id;
					$msgArray[$j][1] = 'struct';
					$i++;
				}
			} else {
				$queryCmd = "select displayname from shiksha.tuser where userid=?";
				$query = $this->db->query($queryCmd, array($id));
				foreach ($query->result() as $row){
					$msgArray[$j][0]['BranchName'] = $BranchName;
					$msgArray[$j][0]['Branchid'] = $branchId;
					$msgArray[$j][0]['executive_id'] = $id;
					$msgArray[$j][0]['executive_name'] = $row->displayname;
					$msgArray[$j][0]['executive_target'] = "-1";
					$msgArray[$j][0]['quarter'] = $Quarter;
					$msgArray[$j][0]['year'] = $year;
					$msgArray[$j][0]['targetId'] = -1;
					$msgArray[$j][1] = 'struct';
				}
			}
			$j++;	
		}
		$response = array($msgArray,'struct');
		error_log_shiksha("final".print_r($response,true));
		return $this->xmlrpc->send_response($response);	
		}
	/**
	 * Function to update executive data
	 */
	function updateExecutiveData($request)
		{
		error_log_shiksha("IN server ravi");
		$parameters = $request->output_parameters();
		$appID = $parameters['0'];
		$branchId = $parameters['2'];
		$managerDetailList = json_decode($parameters['1'],true);
		$Quarter = $parameters['4'];
		$year = $parameters['3'];
		$assigned_by = $parameters['5'];
		$this->load->database();
		error_log_shiksha("managerDetailList".print_r($managerDetailList,true));
		foreach ($managerDetailList as $key=>$value) {
			$executive_id = $key;
			$executive_target = $value[0];
			$targetId = $value[1];
			error_log_shiksha("Details".$executive_id.$executive_target.$targetId);
			$this->updateTargetDetails($appID,$targetId,$branchId,$executive_id,$assigned_by,$Quarter,$year,$executive_target);
		}
		$response = array(
			array(
				'result'=>1,
			),
		'struct');
		return $this->xmlrpc->send_response($response);
		}
	/**
	 * Function to get all Traget
	 */
	function getALLTarget($request) 
		{
		error_log_shiksha("IN server ravi");
		$parameters = $request->output_parameters();
		$appID = $parameters['0'];
		$branchId = $parameters['1'];
		$quarter = $parameters['2'];
		$year = $parameters['3'];
		$executiveId = $parameters['4'];
		error_log_shiksha(print_r($parameters,true)."ravi123");
		$this->load->database();
		$queryCmd = "select * from SUMS.target_sums where is_active = '1'";
		if (!empty($branchId)) {
			$queryCmd .= " and branch_id=".$this->db->escape($branchId);
		}
		if (!empty($quarter)) {
			$queryCmd .= " and quarter=".$this->db->escape($quarter);
		}
		if (!empty($year)) {
			$queryCmd .= " and year=".$this->db->escape($year);
		}
		if (!empty($executiveId)) {
			$queryCmd .= " and executiveId=".$this->db->escape($executiveId);
		}
		$query = $this->db->query($queryCmd);
		error_log_shiksha($queryCmd."ravi1234");
		$msgArray = array();
		foreach ($query->result() as $row){
			array_push($msgArray,array(
				array(
					'id'=>array($row->id,'string'),
					'branch_id'=>array($row->branch_id,'string'),
					'executive_id'=>array($row->executive_id,'string'),
					'assigned_by'=>array($row->assigned_by,'string'),
					'quarter'=>array($row->quarter,'string'),
					'year'=>array($row->year,'string'),
					'target'=>array($row->target,'string'),
					'logged'=>array($row->logged,'string'),
					'is_active'=>array($row->is_active,'string')
				),'struct'));//close array_push
		}
		$response = array($msgArray,'struct');
		error_log_shiksha("Ravi Info ".print_r($response,true));
		return $this->xmlrpc->send_response($response);
		}
	/**
	 * Function to get Quarters
	 */
	function getAllQuarters($request)
		{
		$parameters = $request->output_parameters();
		$appID = $parameters['0'];
		error_log_shiksha("IN server ravi");
		$response = array(
			array(
				'FQ_detail'  => array(
					array(
						'1'=> array('First Quarter','string'),
						'2'=> array('Second Quarter','string'),
						'3'=> array('Third Quarter','string'),
						'4'=> array('Fourth Quarter','string')
					),	
					'struct'
				),
				'FQ_short'    => array(
					array(
						'1'=> array('4,5,6','string'),
						'2'=> array('7,8,9','string'),
						'3'=> array('10,11,12','string'),
						'4'=> array('1,2,3','string')
					),	
					'struct'
				)
			),
			'struct'
		);
		error_log_shiksha("Ravi Info".print_r($response,true));
		return $this->xmlrpc->send_response($response);
		}
	/**
	 * Function to get Month Target Report
	 */
	function Month_till_date_sales_Report($request)
		{
		error_log_shiksha('Shivam '.print_r($request,true));
		$parameters = $request->output_parameters();
		$appID = $parameters['0'];
		$report_type = $parameters['1'];
		$search_array = json_decode($parameters['2'],true);
		
		//connect DB
		$dbHandle = $this->load->database('sums',TRUE);
		if($dbHandle == ''){
			log_message('error','deleteTopic can not create db handle');
		}
		if($search_array['sums_mis_branch']==-1)
		{
			$branch="";
		}
		else
		{
			$branch="and  branch_id=".$dbHandle->escape($search_array['sums_mis_branch']);
		}
		$rem=$search_array['month']%3;
		if($rem==0)
		{
			$remquery="and quarter=".$dbHandle->escape(($search_array['month']-2).",".($search_array['month']-1).",".($search_array['month']));
		}
		if($rem==1)
		{
			$remquery="and quarter=".$dbHandle->escape(($search_array['month']).",".($search_array['month']+1).",".($search_array['month']+2));
		}
		if($rem==2)
		{
			$remquery="and quarter=".$dbHandle->escape(($search_array['month']-1).",".($search_array['month']).",".($search_array['month']+1));
		}
		if(strlen($search_array['month'])==1)
		{
			$month="0".$search_array['month'];
		}
		else
			$month=$search_array['month'];

		$last_day = $search_array['year']."-".$month."-07";
		$queryCmd="select Branch, Executive, Target, Achievement as `Month Achievement`, Pending as `Pending Amount`, (Target-Achievement) as `Short-fall`,(Achievement*100/Target) as `Percentage Achievement`  from (select (select BranchName from Sums_Branch_List where branch_id=BranchId) as Branch, (select displayname from shiksha.tuser where userid=executive_id) as Executive , target/3 `Target`,IFNULL((select sum(Amount_Received) from Transaction, Payment, Payment_Details where Transaction.SalesBy=executive_id and Transaction.TransactionId=Payment.Transaction_Id and Payment_Details.Payment_Id=Payment.Payment_Id and Payment_Details.isPaid='Paid' and month(Cheque_Date)=".$search_array['month']." and year(Cheque_Date)=".$search_array['year']."),0) as `Achievement`, IFNULL((select sum(Amount_Received) from Transaction, Payment, Payment_Details where Transaction.SalesBy=executive_id and Transaction.TransactionId=Payment.Transaction_Id and Payment_Details.Payment_Id=Payment.Payment_Id and Payment_Details.isPaid='Un-paid' and Cheque_Date<=last_day(".$dbHandle->escape($last_day).")),0) as `Pending` from target_sums where is_active=1 and year=".$dbHandle->escape($search_array['year'])." ".$remquery." ".$branch." ) `XYZ` " ;
		//check if any topic is posted on this topic or not?
		error_log("Shivam Query ".$queryCmd);
		$query = $dbHandle->query($queryCmd);
		/*  Add For Pagination   */
		$response=array();
		$tempArray=array();
		foreach ($query->result_array() as $row)
		{
			array_push ($tempArray,array($row,'struct'));
		}
		
		array_push($response,array(
			array(
				'results'=>array($tempArray,'struct'),
				
			),'struct')
		);
		$resp = array($response,'struct');
		error_log('Shivam here '.print_r($resp,true));
		return $this->xmlrpc->send_response ($resp);
		/*  Add For Pagination   */
		
		
		}
	/**
	 * Function to get Quarter Target Report
	 */
	function Quarter_till_date_sales_Report($request)
		{
		$parameters = $request->output_parameters();
		$appID = $parameters['0'];
		$report_type = $parameters['1'];
		$search_array = json_decode($parameters['2'],true);
		
		//connect DB
		$dbHandle = $this->load->database('sums',TRUE);
		if($dbHandle == ''){
			log_message('error','deleteTopic can not create db handle');
		}
		if($search_array['sums_mis_branch']==-1)
		{
			$branch="";
		}
		else
		{
			$branch="and  branch_id=".$dbHandle->escape($search_array['sums_mis_branch']);
		}
		
		$remquery="and quarter=".$dbHandle->escape($search_array['Quarters']);
		$monthArray=explode(",",$search_array['Quarters']);
		$length=count($monthArray);
		$month=$monthArray[$length-1];
		if(strlen($month)==1)
		{
			$month="0".$month;	
		}

		$last_day = $search_array['year']."-".$month."-07";
		$queryCmd="select Branch, Executive, Target, Achievement as `Quarter Achievement`, Pending as `Pending Amount`, (Target-Achievement) as `Short-fall`,(Achievement*100/Target) as `Percentage Achievement`  from (select (select BranchName from Sums_Branch_List where branch_id=BranchId) as Branch, (select displayname from shiksha.tuser where userid=executive_id) as Executive , target `Target`,IFNULL((select sum(Amount_Received) from Transaction, Payment, Payment_Details where Transaction.SalesBy=executive_id and Transaction.TransactionId=Payment.Transaction_Id and Payment_Details.Payment_Id=Payment.Payment_Id and Payment_Details.isPaid='Paid' and month(Cheque_Date) in (".$search_array['Quarters'].") and year(Cheque_Date)=".$search_array['year']."),0) as `Achievement`, IFNULL((select sum(Amount_Received) from Transaction, Payment, Payment_Details where Transaction.SalesBy=executive_id and Transaction.TransactionId=Payment.Transaction_Id and Payment_Details.Payment_Id=Payment.Payment_Id and Payment_Details.isPaid='Un-paid' and Cheque_Date<=last_day(".$dbHandle->escape($last_day).")),0) as `Pending` from target_sums where is_active=1 and year=".$dbHandle->escape($search_array['year'])." ".$remquery." ".$branch." ) `XYZ` " ;
		//$queryCmd="select (select BranchName from Sums_Branch_List where branch_id=BranchId) as Branch, (select displayname from shiksha.tuser where userid=executive_id) as Executive , target from target_sums where is_active=1 and year=".$search_array['year']." ".$remquery." ".$branch;
		//check if any topic is posted on this topic or not?
		error_log("Shivam Query ".$queryCmd);
		$query = $dbHandle->query($queryCmd);
		/*  Add For Pagination   */
		$response=array();
		$tempArray=array();
		foreach ($query->result_array() as $row)
		{
			array_push ($tempArray,array($row,'struct'));
		}
		
		array_push($response,array(
			array(
				'results'=>array($tempArray,'struct'),
				
			),'struct')
		);
		$resp = array($response,'struct');
		error_log('Shivam here '.print_r($resp,true));
		return $this->xmlrpc->send_response ($resp);
		/*  Add For Pagination   */
		
		
		}
	/**
	 * Function to get Product Mix Report
	 */
	function Product_MIX_Report($request)
		{
		$parameters = $request->output_parameters();
		$appID = $parameters['0'];
		$report_type = $parameters['1'];
		$search_array = json_decode($parameters['2'],true);
		error_log_shiksha("Product_MIX_Report".print_r($search_array,true));
		//connect DB
		$dbHandle = $this->load->database('sums',TRUE);
		if($dbHandle == ''){
			log_message('error','deleteTopic can not create db handle');
		}
		if($search_array['sums_mis_branch']==-1)
		{
			/*	$this->load->library('sums_manage_client');
			 $manObj = new Sums_Manage_client();
			 $branchData = $manObj->getBranches($appID);
			 
			 $branchList='';
			 foreach ($branchData as $branch)
			 {
			 if($branchList!='')
			 { 
			 $branchList.=",".$branch['BranchId'];
			 }	
			 else
			 {
			 $branchList.=$branch['BranchId'];
			 }
			 }
			 error_log_shiksha("BranchData".$branchList);*/
			$branchquery='';
		}
		else
		{
			$branchquery= ' and SalesBranch in ('.$search_array['sums_mis_branch'].')';
			$branchList=$search_array['sums_mis_branch'];
		}
		if($search_array['sums_mis_executive']==-1)
		{
			$executivequery='';
			/*
			 $this->load->library('sums_manage_client');
			 $manObj = new Sums_Manage_client();
			 $executiveData = $manObj->getSumsUsers($appId);
			 $executiveList='';
			 foreach ($executiveData as $executive)
			 {
			 if($executiveList!='')
			 { 
			 $executiveList.=",".$executive['userid'];
			 }	
			 else
			 {
			 $executiveList.=$executive['userid'];
			 }
			 }*/
		}
		else
		{
			$executivequery=' and SalesBy in ('.$search_array['sums_mis_executive'].')';
			$executiveList=$search_array['sums_mis_executive'];
		}
		$trans_start_date = $search_array['trans_start_date']." 00:00:00";
		$trans_end_date = $search_array['trans_end_date']." 23:59:59";
		$timequery="and TransactTime >= ".$dbHandle->escape($trans_start_date)." and TransactTime <= ".$dbHandle->escape($trans_end_date);
		$queryCmd="select BaseProdCategory from Base_Products";
		error_log("Shivam Query ".$queryCmd);
		$query = $dbHandle->query($queryCmd);
		$categoryArray=array();
		foreach ($query->result_array() as $categoryrow)
		{
			$categoryArray[$categoryrow['BaseProdCategory']]=$categoryrow['BaseProdCategory'];	
		}
		if($search_array['Output_Value']=='counts')
		{
			$queryCmd="select group_concat(Transaction.TransactionId) `TransactionList`, (select displayname from shiksha.tuser where userid=SalesBy) `SalesBy`, (select BranchName from Sums_Branch_List where BranchId=SalesBranch) `SalesBranch` from Transaction,Transaction_Queue, Payment where Payment.Transaction_Id=Transaction.TransactionId and Payment.Sale_Type!='Trial' and Transaction_Queue.TransactionId=Transaction.TransactionId and Transaction_Queue.State ='OPS_APPROVED' ".$branchquery." ".$executivequery." ".$timequery." group by SalesBy, SalesBranch order by SalesBranch, SalesBy";
			error_log("Shivam Query ".$queryCmd);
			$query = $dbHandle->query($queryCmd);
			/*  Add For Pagination   */
			$response=array();
			$tempArray=array();
			$transactArray=array();
			foreach ($query->result_array() as $row)
			{
				$newrow=array();
				$transactArray[]=$row;
				$newrow['SalesBy']=$row['SalesBy'];
				$newrow['SalesBranch']=$row['SalesBranch'];
				$queryCmd="select BaseProdCategory, count(*) as count from Transaction, Quotation, Quotation_Product_Mapping, Derived_Products_Mapping, Base_Products where Transaction.UIQuotationId=Quotation.UIQuotationId and Quotation.Status!=\"HISTORY\" and Quotation_Product_Mapping.QuotationId=Quotation.QuotationId and Derived_Products_Mapping.DerivedProductId=Quotation_Product_Mapping.DerivedProductId and Base_Products.BaseProductId=Derived_Products_Mapping.BaseProductId and Transaction.TransactionId in (".$row['TransactionList'].") group by BaseProdCategory";
				//error_log("Shivam Query ".$queryCmd);
				$query = $dbHandle->query($queryCmd);
				foreach ($query->result_array() as $row1)
				{
					$newrow[$row1['BaseProdCategory']]=$row1['count'];
				}
				
				foreach($categoryArray as $category)
				{ 
					if(!array_key_exists($category,$newrow))
					{
						error_log_shiksha("shivam ".$category);
						$newrow[$category]= 0;		
					}
				}
				krsort($newrow);
				array_push ($tempArray,array($newrow,'struct'));
			}
			
			array_push($response,array(
				array(
					'results'=>array($tempArray,'struct'),
					
				),'struct')
			);
			$resp = array($response,'struct');
			//error_log('Shivam here '.print_r($resp,true));
			return $this->xmlrpc->send_response ($resp);
			/*  Add For Pagination   */
		}
		else
		{
			$queryCmd="select group_concat(Transaction.TransactionId) `TransactionList`, (select displayname from shiksha.tuser where userid=SalesBy) `SalesBy`, (select BranchName from Sums_Branch_List where BranchId=SalesBranch) `SalesBranch` from Transaction,Transaction_Queue, Payment where Payment.Transaction_Id=Transaction.TransactionId and Payment.Sale_Type!='Trial' and Transaction_Queue.TransactionId=Transaction.TransactionId and Transaction_Queue.State ='OPS_APPROVED' ".$branchquery." ".$executivequery." ".$timequery." group by SalesBy, SalesBranch order by SalesBranch, SalesBy";
			error_log("Shivam Query ".$queryCmd);
			$query = $dbHandle->query($queryCmd);
			/*  Add For Pagination   */
			$response=array();
			$tempArray=array();
			$transactArray=array();
			foreach ($query->result_array() as $row)
			{
				$newrow=array();
				$transactArray[]=$row;
				$newrow['SalesBy']=$row['SalesBy'];
				$newrow['SalesBranch']=$row['SalesBranch'];
				$queryCmd="select BaseProdCategory, sum(Derived_Products_Mapping.ManagementPrice*Quantity - (Quotation_Product_Mapping.Discount*Derived_Products_Mapping.ManagementPrice/(Derived_Prod_Price_Map.ManagementPrice))) as count from Transaction, Quotation, Quotation_Product_Mapping, Derived_Products_Mapping, Base_Products, Derived_Prod_Price_Map where Transaction.UIQuotationId=Quotation.UIQuotationId and Quotation.Status!=\"HISTORY\" and Quotation_Product_Mapping.QuotationId=Quotation.QuotationId and Derived_Products_Mapping.DerivedProductId=Quotation_Product_Mapping.DerivedProductId and Base_Products.BaseProductId=Derived_Products_Mapping.BaseProductId and Derived_Products_Mapping.DerivedProductId=Derived_Prod_Price_Map.DerivedProductId and Transaction.TransactionId in (".$row['TransactionList'].") group by BaseProdCategory";
				//error_log("Shivam Query ".$queryCmd);
				//error_log("Shivam Query ".$queryCmd);
				$query = $dbHandle->query($queryCmd);
				foreach ($query->result_array() as $row1)
				{
					$newrow[$row1['BaseProdCategory']]=$row1['count'];
				}
				
				foreach($categoryArray as $category)
				{ 
					if(!array_key_exists($category,$newrow))
					{
						error_log_shiksha("shivam ".$category);
						$newrow[$category]= 0;		
					}
				}
				krsort($newrow);
				array_push ($tempArray,array($newrow,'struct'));
			}
			
			array_push($response,array(
				array(
					'results'=>array($tempArray,'struct'),
					
				),'struct')
			);
			$resp = array($response,'struct');
			//error_log('Shivam here '.print_r($resp,true));
			return $this->xmlrpc->send_response ($resp);
			/*  Add For Pagination   */
		}
		
		}
}
?>