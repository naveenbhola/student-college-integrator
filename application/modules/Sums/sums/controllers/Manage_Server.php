<?php
include('Sums_Mail_Events.php');
/**
 * Class For Sums Basic Web Services
 * 
 */
class Manage_Server extends MX_Controller
{
	private $db;
	private $db_sums;
	
	function index()
	{
		$this->dbLibObj = DbLibCommon::getInstance('SUMS');
		
		$this->load->library('xmlrpc');
		$this->load->library('xmlrpcs');
		
		$config['functions']['getUsers'] = array('function'=>'Manage_Server.getUsers');
		$config['functions']['sgetUserForQuotation'] = array('function'=>'Manage_Server.sgetUserForQuotation');
		$config['functions']['sgetUserGroupList'] = array('function'=>'Manage_Server.sgetUserGroupList');
		$config['functions']['sgetBranchList'] = array('function'=>'Manage_Server.sgetBranchList');
		$config['functions']['sgetManagerList'] = array('function'=>'Manage_Server.sgetManagerList');
		$config['functions']['saddSumsUser'] = array('function'=>'Manage_Server.saddSumsUser');
		$config['functions']['sgetSumsUserInfo'] = array('function'=>'Manage_Server.sgetSumsUserInfo');
		$config['functions']['sgetSumsUserACL'] = array('function'=>'Manage_Server.sgetSumsUserACL');
		$config['functions']['sgetSumsUsers'] = array('function'=>'Manage_Server.sgetSumsUsers');
		$config['functions']['sapproveManager'] = array('function'=>'Manage_Server.sapproveManager');
		$config['functions']['sapproveFinance'] = array('function'=>'Manage_Server.sapproveFinance');
		$config['functions']['sapproveOps'] = array('function'=>'Manage_Server.sapproveOps');
		$config['functions']['scancelTransaction'] = array('function'=>'Manage_Server.scancelTransaction');
		$config['functions']['sgetAllExecutiveList'] = array('function'=>'Manage_Server.sgetAllExecutiveList');
		$config['functions']['sgetAllClientsList'] = array('function'=>'Manage_Server.sgetAllClientsList');
		$config['functions']['sapproveOpsDerived'] = array('function'=>'Manage_Server.sapproveOpsDerived');
		$config['functions']['sgetBranches'] = array('function'=>'Manage_Server.sgetBranches');
		$config['functions']['sfindClientTypeByBaseCatAndSubCat'] = array('function'=>'Manage_Server.sfindClientTypeByBaseCatAndSubCat');
		$args = func_get_args(); $method = $this->getMethod($config,$args);
		return $this->$method($args[1]);
		
	}
	
	private function setDBHandle($sums = FALSE)
	{
		if($sums) {
			$this->dbLibObj = DbLibCommon::getInstance('SUMS');
			$this->db_sums = $this->dbLibObj->getWriteHandle();
			return $this->db_sums;
		}
		else {
			$this->dbLibObj = DbLibCommon::getInstance('SUMSShiksha');
			$this->db = $this->dbLibObj->getWriteHandle();
			return $this->db;
		}
	}
	
	/**
	 * Function to get Users
	 */
	function getUsers($request)
	{
		$parameters = $request->output_parameters();
		
		$this->setDBHandle();
		
		$formVal = $parameters['1'];
		$query =  "select * from shiksha.tuser where displayname like '%".$this->db->escape_like_str($formVal['displayname'])."%' and email like '%".$this->db->escape_like_str($formVal['email'])."%' and usergroup='enterprise'";
		
		$arrResults = $this->db->query($query);
		
		$tmpArr = array();
		foreach ($arrResults->result() as $row)
		{
			
			$response[$row->userid]= array(array(),'struct');
			$response[$row->userid][0]['displayname'] = $row->displayname; 
			$response[$row->userid][0]['email'] = $row->email; 
			
			
			$queryProducts =  "select TransactionId,S.SubscriptionId,ClientUserId,SubscrStatus,DerivedProductName,TotalBaseProdQuantity,BaseProdRemainingQuantity,date(M.SubscriptionStartDate) SubscriptionStartDate,date(M.SubscriptionEndDate) SubscriptionEndDate from SUMS.Subscription S, SUMS.Subscription_Product_Mapping M,SUMS.Derived_Products D where S.ClientUserId=? and S.SubscriptionId=M.SubscriptionId and S.DerivedProductId=D.DerivedProductId and date(M.SubscriptionEndDate) >= curdate() and M.BaseProdRemainingQuantity >=1 and M.Status='ACTIVE' order by M.SubscrLastModifyTime DESC";
			error_log_shiksha($queryProducts);
			$prodResult = $this->db->query($queryProducts, array($row->userid));
			
			
			$response[$row->userid][0]['subscriptions']=array(array(),'struct');
			
			foreach ($prodResult->result() as $rowProd)
			{
				array_push($response[$row->userid][0]['subscriptions'][0],array(
					array (
						'TransactionId'=>array($rowProd->TransactionId,'string'),
						'SubscriptionId'=>array($rowProd->SubscriptionId,'string'),
						'ClientUserId'=>array($rowProd->ClientUserId,'string'),
						'SubscrStatus'=>array($rowProd->SubscrStatus,'string'),
						'DerivedProductName'=>array($rowProd->DerivedProductName,'string'),
						'TotalBaseProdQuantity'=>array($rowProd->TotalBaseProdQuantity,'string'),
						'BaseProdRemainingQuantity'=>array($rowProd->BaseProdRemainingQuantity,'string'),
						'SubscriptionStartDate'=>array($rowProd->SubscriptionStartDate,'string'),
						'SubscriptionEndDate'=>array($rowProd->SubscriptionEndDate,'string')),'struct')
				);
			}
			
		}
		
		$resp = array($response,'struct');
		return $this->xmlrpc->send_response ($resp);
	}
	
	/**
	 * Function To get Enterprise User List for Quotation Creation 
	 */
	function sgetUserForQuotation($request)
		{
		$parameters = $request->output_parameters();
		$this->setDBHandle();
		
		$formVal = $parameters['1'];
		
		if($formVal['displayname']!=''){
			$DISP = "AND U.displayname ='".$this->db->escape_str($formVal['displayname'])."'";
		}else{
			$DISP = "";
		}
		
		if($formVal['email']!=''){
			$EMAIL = "AND U.email ='".$this->db->escape_str($formVal['email'])."'";
		}else{
			$EMAIL = "";
		}
		
		// if($formVal['collegeName']!=''){
		// 	$COLNAME = "AND E.businessCollege like '%".$this->db->escape_like_str($formVal['collegeName'])."%'";
		// }else{
		// 	$COLNAME = "";
		// }
		
		// if($formVal['contactName']!=''){
		// 	$CNTCTNAME = "AND E.contactName like '%".$this->db->escape_like_str($formVal['contactName'])."%'";
		// }else{
		// 	$CNTCTNAME = "";
		// }
		
		// if($formVal['contactNumber']!=''){
		// 	$CNTCTNUM = "AND U.mobile like '%".$this->db->escape_like_str($formVal['contactNumber'])."%'";
		// }else{
		// 	$CNTCTNUM = "";
		// }
		
		if($formVal['clientId']!=''){
			$CLIENTID = "AND U.userid = ".$this->db->escape(intval($formVal['clientId']));
		}else{
			$CLIENTID = "";
		}

		if(empty($CLIENTID) && empty($DISP) && empty($EMAIL)){
			return $this->xmlrpc->send_response (array());
		}
		
		$query =  "select * from shiksha.tuser U,shiksha.enterpriseUserDetails E where U.userid=E.userId AND usergroup='enterprise' $DISP $EMAIL $CLIENTID";
		
		$arrResults = $this->db->query($query);
		
		$tmpArr = array();
		foreach ($arrResults->result() as $row)
		{
			
			$response[$row->userid]= array(array(),'struct');
			$response[$row->userid][0]['displayname'] = $row->displayname; 
			$response[$row->userid][0]['email'] = $row->email; 
			$response[$row->userid][0]['collegeName'] = $row->businessCollege; 
			$response[$row->userid][0]['contactName'] = $row->contactName; 
			$response[$row->userid][0]['contactNumber'] = $row->mobile; 
		}
		
		$resp = array($response,'struct');
		// error_log_shiksha(print_r(json_encode($resp),true)." SUMS: Get User details response!");
		return $this->xmlrpc->send_response ($resp);
		}
	/**
	 * This Function returns the list of all Sums user groups except Admin
	 */
	function sgetUserGroupList($request)
		{
		$parameters = $request->output_parameters();
		$this->setDBHandle();
		
		$formVal = $parameters['1'];
		$query =  "select * from SUMS.Sums_User_Groups where id!=1";
		error_log_shiksha($query);
		$arrResults = $this->db->query($query);
		
		$tmpArr = array();
		foreach ($arrResults->result_array() as $row)
		{
			array_push($tmpArr,array($row,'struct'));
		}
		$resp = array($tmpArr,'struct');
		error_log_shiksha(print_r(json_encode($resp),true)." SUMS: Get User Group List response!");
		return $this->xmlrpc->send_response ($resp);
		}
	/**
	 * This function returns list of all sums branches by default or belonging to a user
	 */
	function sgetBranchList($request)
		{
		$parameters = $request->output_parameters();
		$this->setDBHandle();
		
		$formVal = $parameters['1'];
		$userId = $parameters['2'];
		if(isset($userId) && $userId!='-1')
		{
			$query =  "select SUMS.Sums_Branch_List.BranchId,SUMS.Sums_Branch_List.BranchName from SUMS.Sums_Branch_List,SUMS.Sums_User_Branch_Mapping where SUMS.Sums_User_Branch_Mapping.BranchId=SUMS.Sums_Branch_List.BranchId and SUMS.Sums_User_Branch_Mapping.userId=".$this->db->escape($userId);
		}
		else
		{
			$query="select * from SUMS.Sums_Branch_List";
		}
		error_log_shiksha($query);
		$arrResults = $this->db->query($query);
		
		$tmpArr = array();
		foreach ($arrResults->result_array() as $row)
		{
			array_push($tmpArr,array($row,'struct'));
		}
		$resp = array($tmpArr,'struct');
		error_log_shiksha(print_r(json_encode($resp),true)." SUMS: Get Branch List response!");
		return $this->xmlrpc->send_response ($resp);
		}
	/**
	 * This function gives list of all possible managers for a user
	 */
	
	function sgetManagerList($request)
		{
		$parameters = $request->output_parameters();
		$this->setDBHandle(TRUE);
		$branchId = $parameters['1'];
		$userGroupId = $parameters['2'];
		$managerGroupId='';	
		while($userGroupId!=1)
		{
			$query="select parentId from Sums_User_Groups where id=?";
			$arrResults = $this->db_sums->query($query, array($userGroupId));
			
			foreach ($arrResults->result() as $row)
			{
				$userGroupId=$row->parentId;
				if($managerGroupId!='')
				{
					$managerGroupId=$managerGroupId.",".$userGroupId;
				}
				else
				{
					$managerGroupId=$userGroupId;
				}
			}	
		}
		if($managerGroupId=='')
		{
			$managerGroupId=1;
		}
		$managerGroupIdArray = explode(',', $managerGroupId);
		$query = "select distinct(Sums_User_Details.userId) from SUMS.Sums_User_Details,Sums_User_Branch_Mapping where Sums_User_Details.userId=Sums_User_Branch_Mapping.userId and BranchId in ( ? ) and Role in ( ? )";
		$arrResults = $this->db_sums->query($query, array($branchId, $managerGroupIdArray));
		
		$tmpArr = array();
		foreach ($arrResults->result_array() as $row)
		{
			array_push($tmpArr,array($row,'struct'));
		}
		
		$resp = array($tmpArr,'struct');
		// error_log_shiksha(print_r(json_encode($resp),true)." SUMS: Get Branch List response!");
		return $this->xmlrpc->send_response ($resp);
		}
	/**
	 * API to add Sums User
	 * 
	 */
	function saddSumsUser($request)
	{
		$parameters = $request->output_parameters();
		$this->setDBHandle(TRUE);
		//$this->db_sums = $this->load->database('sums',TRUE);
		//$this->dbHandle = $this->_loadDatabaseHandle();
		$data= $parameters[1];
		$tmpArray=array();
		$tmpArray['EmployeeId']=$data['employeeId'];
		$tmpArray['userId']=$data['userId'];
		$tmpArray['managerId']=$data['ManagerId'];
		$tmpArray['Role']=$data['Role'];
		$tmpArray['DiscountLimit'] = $data['DiscountLimit'];
		$query=$this->db_sums->insert_string('Sums_User_Details',$tmpArray);
		error_log_shiksha("data ".print_r($data,true));
		error_log_shiksha($query);
		//$this->db->query($query);
		$this->db_sums->query($query);
		$branchIdList = $data['BranchId'];
		$branchIdArray=explode(',',$branchIdList);
		foreach($branchIdArray as $branchId)
		{
			$query=$this->db_sums->insert_string('Sums_User_Branch_Mapping',array('userId'=>$data['userId'],'BranchId'=>$branchId));
			error_log_shiksha($query);
			$this->db_sums->query($query);
		}	
		error_log_shiksha("data ".print_r($data,true));
		$response = array (array(
			'EmployeeId'=>$tmpArray['EmployeeId'],
		),'struct');
		return $this->xmlrpc->send_response($response);
	}
	/**
	 * API to get Sums User Details
	 * 
	 */
	function sgetSumsUserInfo($request)
		{
		$parameters = $request->output_parameters();
		$this->setDBHandle(TRUE);
		$userId= $parameters[1];
		$query="select group_concat(BranchId) as BranchIds,S.* from Sums_User_Details S,Sums_User_Branch_Mapping B where S.userId=? and S.userId=B.userId group by B.userId";
		error_log_shiksha($query);	
		$arrResults = $this->db_sums->query($query, array($userId));
		$tempArr=array();
		foreach ($arrResults->result_array() as $row)
		{
			array_push($tempArr,array($row,'struct'));
		}
		$resp = array($tempArr,'struct');
		error_log_shiksha(print_r(json_encode($resp),true)." SUMS: GetSumsUserGroup");
		return $this->xmlrpc->send_response ($resp);
		}
	/**
	 * API to get Sums User List
	 * 
	 */
	function sgetSumsUsers($request)
		{
		$parameters = $request->output_parameters();
		$this->setDBHandle(TRUE);
		
		$queryCmd = "select T.userid, T.displayname,L.BranchName from shiksha.tuser T, Sums_User_Details U,Sums_User_Branch_Mapping B,Sums_Branch_List L where T.usergroup='sums' and U.userId=T.userid and U.userId=B.userId and B.BranchId=L.BranchId group by T.userid order by T.displayname ASC,L.BranchName ASC";
		error_log_shiksha($queryCmd);
		$query = $this->db_sums->query($queryCmd);
		$response = array();
		foreach ($query->result_array() as $row)
		{
			array_push($response,array($row,'struct'));
		}
		$response = array($response,'struct');
		return $this->xmlrpc->send_response($response);
		}
	/**
	 * API to get all Sums Branches
	 * 
	 */
	function sgetBranches($request)
		{
		$parameters = $request->output_parameters();
		$this->setDBHandle(TRUE);
		
		$queryCmd = "select * from Sums_Branch_List";
		error_log_shiksha($queryCmd);
		$query = $this->db_sums->query($queryCmd);
		$response = array();
		foreach ($query->result_array() as $row)
		{
			array_push($response,array($row,'struct'));
		}
		$response = array($response,'struct');
		return $this->xmlrpc->send_response($response);
		}
	
	/**
	 * API for Manager Approval
	 * 
	 */
	function sapproveManager($request){
		$parameters = $request->output_parameters();
		$params = $parameters['1'];
		
		//connect DB
		$this->setDBHandle(TRUE);
		
		$approverId= $params['approverId'];
		$transactionId = $params['transactionId'];
		error_log_shiksha("Transact Ids :: ".print_r(($transactionId),true));
		
		foreach($transactionId as $id)
		{ 
			$queryCmd = "update Transaction_Queue set State='MANAGER_APPROVED',ManagerApprovalDate=now() where TransactionId=?";
			error_log_shiksha('Query for Manager Approval' . $queryCmd);
			$query = $this->db_sums->query($queryCmd, array($id));
			
			//Mailer to Executive, Approving Manager
			global $mailEventsArr;
			$mailData['eventType']= $mailEventsArr['MANAGER_APPROVED'];
			$mailData['transactionId']=$id;
			$this->load->library(array('sums_mailer_client'));
			$objSumsMail = new Sums_mailer_client();
			$mailResponse = $objSumsMail->sendSumsMails(1,$mailData);
		}
		$response=array(
			array(
				'ApprovedTransaction'=>array(implode(",",$transactionId),'string')
			),'struct');
		
		error_log_shiksha("Approved Transaction: ".print_r($response,true));
		return $this->xmlrpc->send_response($response);
	}
	
	/**
	 * API for Finance Approval
	 * 
	 */
	function sapproveFinance($request){
		$parameters = $request->output_parameters();
		$params = $parameters['1'];
		
		//connect DB
		$this->setDBHandle(TRUE);
		
		$approverId= $params['approverId'];
		$transactionId = $params['transactionId'];
		$queryCmd = "update Transaction_Queue set State='FINANCE_APPROVED',FinanceApprovalDate=now(),FinanceApproverId=? where TransactionId=?";
		error_log_shiksha('Query for Finance Approval' . $queryCmd);
		$query = $this->db_sums->query($queryCmd, array($approverId, $transactionId));
		
		$response=array(
			array(
				'ApprovedTransaction'=>array($transactionId,'string')
			),'struct');
		
		error_log_shiksha("Approved Transaction: ".print_r($response,true));
		return $this->xmlrpc->send_response($response);
	}
	
	/**
	 * API for OPS Approval
	 * 
	 */
	function sapproveOps($request){
		$parameters = $request->output_parameters();
		$params = $parameters['1'];
		
		//connect DB
		$this->setDBHandle(TRUE);
		
		$approverId= $params['approverId'];
		$transactionId = $params['transactionId'];
		$queryCmd = "update Transaction_Queue set State='OPS_APPROVED',OpsApprovalDate=now(),OpsApproverId=? where TransactionId=?";
		error_log_shiksha('Query for Ops Approval' . $queryCmd);
		$query = $this->db_sums->query($queryCmd, array($approverId, $transactionId));
		
		
		$response=array(
			array(
				'ApprovedTransaction'=>array($transactionId,'string')
			),'struct');
		
		error_log_shiksha("Approved Transaction: ".print_r($response,true));
		return $this->xmlrpc->send_response($response);
	}
	
	/**
	 * API for OPS Approval
	 * 
	 */
	function sapproveOpsDerived($request){
		$parameters = $request->output_parameters();
		$params = $parameters['1'];
		error_log_shiksha('derived Prod IDS '.print_r($params,true));
		
		//connect DB
		$this->setDBHandle(TRUE);
		
		$approverId= $params['approverId'];
		$transactionIds = $params['transactionId'];
		$derivedProdIds = $params['derivedProdId'];
		
		$countTrans = count($transactionIds);
		
		for($i=0;$i<$countTrans;$i++){
			error_log_shiksha('derived Prod IDS '.print_r($derivedProdIds[$i],true));
			error_log_shiksha('transaction ids '.print_r($transactionIds[$i],true));
			$queryCmd = "update Quotation_Product_Mapping set Status='OPS_APPROVED',OpsApprovalDate=now(),OpsApproverId=? where QuotationId = (select Q.QuotationId from Quotation Q,Transaction T where T.UIQuotationId=Q.UIQuotationId and Q.Status='TRANSACTION' and T.TransactionId=?) and DerivedProductId=? and Status='PENDING'";
			error_log_shiksha('Query for Ops Approval' . $queryCmd);
			$query = $this->db_sums->query($queryCmd, array($approverId, $transactionIds[$i], $derivedProdIds[$i]));
			
			$queryCmd = "select QuotationId from Quotation_Product_Mapping where QuotationId = (select Q.QuotationId from Quotation Q,Transaction T where T.UIQuotationId=Q.UIQuotationId and Q.Status='TRANSACTION' and T.TransactionId=?) and Status='PENDING'"; 
			$query = $this->db_sums->query($queryCmd, array($transactionIds[$i]));
			
			// if($query->result() == NULL){
                        if($query->num_rows() <= 0){
				$queryCmd = "update Transaction_Queue set State='OPS_APPROVED',OpsApprovalDate=now(),OpsApproverId=? where TransactionId=?";                                
				error_log_shiksha('Query for Ops Approval' . $queryCmd);
				$query = $this->db_sums->query($queryCmd, array($approverId, $transactionIds[$i]));
			}
		}
		
		
		$response=array(
			array(
				'ApprovedTransaction'=>"SUCCESS"
			),'struct');
		
		error_log_shiksha("Approved Transaction: ".print_r($response,true));
		return $this->xmlrpc->send_response($response);
	}
	
	
	
	/**
	 * API for Cancel/Disapproval of a Transaction
	 * 
	 */
	function scancelTransaction($request){
		$parameters = $request->output_parameters();
		$params = $parameters['1'];
		
		//connect DB
		$this->setDBHandle(TRUE);
		
		$approverId = $params['approverId'];
		$approverType = $params['approverType'];
		$transactionId = $params['transactionId'];
		$cancelComments = $params['cancelComments'];
		
		$queryCmd="select SubscriptionId from Subscription where TransactionId=?";
		$arrResults = $this->db_sums->query($queryCmd, array($transactionId));
		$this->load->library('listing_client');
		$this->load->library('enterprise_client');
		$listingClient = new Listing_client();
		$EntClient = new Enterprise_client();
		foreach ($arrResults->result() as $row)
		{
			$subsciptionId=$row->SubscriptionId;
			
			$result = $listingClient->cancelSubscription(1,$row->SubscriptionId);
			$result = $listingClient->cancelKeywordSubscription(1,$row->SubscriptionId);
			$result = $EntClient->cancelSubscription(1,$row->SubscriptionId);
			/*error_log_shiksha("query Cancel Trans ".$queryCmd);
			 $queryCmd="select * from SubscriptionLog where SubscriptionId=".$subsciptionId;
			 error_log_shiksha("query Cancel Trans ".$queryCmd);
			 $arrResults = $this->db->query($queryCmd);
			 foreach ($arrResults->result() as $row)
			 {
			 $consumeId = $row->ConsumedId;
			 $consumeType = $row->ConsumedIdType;
			 $queryCmd='update shiksha.listings_main set status= "expired" where listing_type = "'.$consumeType.'" and listing_type_id = "'.$consumeId.'"' ;
			 error_log_shiksha("query Cancel Trans ".$queryCmd);
			 $query = $this->db->query($queryCmd);
			 
			 }*/
		}
		$queryCmd="update Transaction_Queue set State='CANCELLED', CancelCommets=?,CancellerId=?,CancellationTime=now() where TransactionId=?";	
		$query = $this->db_sums->query($queryCmd, array($cancelComments, $approverId, $transactionId));
		$queryCmd="update Subscription set SubscrStatus='CANCELLED' where TransactionId=?";
		$query = $this->db_sums->query($queryCmd, array($transactionId));
		// Status also in Subscription_Product_Mapping
		$queryCmd="update Subscription_Product_Mapping set Status='CANCELLED' where SubscriptionId in (select SubscriptionId from Subscription where TransactionId=?)";
		$query = $this->db_sums->query($queryCmd, array($transactionId));
		/*
		 //saving legacy
		  if($approverType == '')
		  $queryCmd = "update Transaction_Queue set State='CANCELLED',$DATETYPE=now(),$APPROVERTYPE=$approverId where TransactionId=$transactionId";
		  error_log_shiksha('Query for Ops Approval' . $queryCmd);
		  $query = $this->db->query($queryCmd);
		  */
		$response=array(
			array(
				'CancelledTransaction'=>array($transactionId,'string')
			),'struct');
		
		// error_log_shiksha("Approved Transaction: ".print_r($response,true));
		return $this->xmlrpc->send_response($response);
	}
	/**
	 * API to get Access Control List of Sums User
	 */
	function sgetSumsUserACL($request)
		{
		$parameters = $request->output_parameters();
		$this->setDBHandle(TRUE);
		$userId= $parameters[1];
		$query="select ACLId,UserGroupId from Sums_Group_ACL_Mapping,Sums_User_Details where Sums_Group_ACL_Mapping.UserGroupId=Sums_User_Details.Role and userId=?";
		error_log_shiksha($query);	
		$this->db_sums->query($query, array($userId));
		$arrResults = $this->db_sums->query($query, array($userId));
		$tempArr=array();
		foreach ($arrResults->result() as $row)
		{
			$tempArr[$row->ACLId]=$row->UserGroupId;
		}
		$resp = array($tempArr,'struct');
		error_log_shiksha(print_r(json_encode($resp),true)." SUMS: GetSumsUserACL");
		return $this->xmlrpc->send_response ($resp);
		}
	/**
	 * API to get List of all executives belonging to a branch
	 * 
	 */
	function sgetAllExecutiveList($request)
		{
		$parameters = $request->output_parameters();
		$this->setDBHandle(TRUE);
		$branchId = $parameters['1'];
		$query = "select DISTINCT(SUD.userId), SBL.BranchName from Sums_User_Details AS SUD, Sums_User_Branch_Mapping AS SUBM, Sums_Branch_List AS SBL WHERE SUD.userId =  SUBM.userId and SBL.BranchId=SUBM.BranchId";
		if ($branchId != -1) {
			$query .= " AND SUBM.BranchId = ? ";
			$arrResults = $this->db_sums->query($query, array($branchId));
		} else {
			$query .= "";
			$arrResults = $this->db_sums->query($query);
		}
		$tmpArr = array();
		foreach ($arrResults->result_array() as $row) {
			array_push($tmpArr,array($row,'struct'));
		}
		$resp = array($tmpArr,'struct');
		// error_log_shiksha(print_r(json_encode($resp),true)." SUMS: Get All Executive List");
		return $this->xmlrpc->send_response ($resp);
		}
	/**
	 * API to get List of all clients
	 * 
	 */
	function sgetAllClientsList($request)
	{
		$parameters = $request->output_parameters();
		$this->setDBHandle(TRUE);
		$ExecutivesId = $parameters['1'];
		// TODo:- add join condition here to verify client's id
		$query = "select displayName,tuser.userId from shiksha.tuser, shiksha.enterpriseUserDetails where tuser.userId=enterpriseUserDetails.userId";
		$arrResults = $this->db_sums->query($query);
		$tmpArr = array();
		foreach ($arrResults->result_array() as $row) {
			array_push($tmpArr,$row);
		}
		$resp = array(base64_encode(json_encode($tmpArr)),'string');
		return $this->xmlrpc->send_response($resp);
        }
            function sfindClientTypeByBaseCatAndSubCat($request)
            {
                $parameters = $request->output_parameters();
            
				$this->setDBHandle(TRUE);
				
                $clientId = $parameters['0'];
                $baseCategory= $parameters['1'];
                $baseSubCategory= $parameters['2'];
                
                $tmpArr = array();
                $paidflag = 'false';
            
                $query1 = "select BaseProductId from Base_Products where BaseProdCategory=? AND BaseProdSubCategory=?";
                error_log($query1);
                $Results = $this->db_sums->query($query1, array($baseCategory, $baseSubCategory));

                foreach ($Results->result() as $rowTmp){
                    $query2 = "select TransactionId from Subscription where ClientUserId=? and BaseProductId = ?";
                    error_log($query2);
                    $arrResults = $this->db_sums->query($query2, array($clientId, $rowTmp->BaseProductId));

                    foreach ($arrResults->result() as $row)
                    {
                        $query3 = "select count(*) as transCount from Payment where Transaction_Id=?"; 
                        error_log($query3);
                        $transResults = $this->db_sums->query($query3, array($row->TransactionId));
                        
                        foreach ($transResults->result() as $rowTrans){
                            if($rowTrans->transCount > 0){
                                $paidflag='true';
                                break;
                            }
                        }
                    }
                }
                $response=array(
                    array(
                        'paidStatus'=>array($paidflag,'string')
                    ),'struct');
                return $this->xmlrpc->send_response ($response);
            }

}

?>
