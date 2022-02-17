<?php

/**
 * Class for Sums Quotation Web services
 */
class Quotation_Server extends MX_Controller
{
	private $db_sums;
	private $db;
	function index()
		{
		$this->load->library('xmlrpc');
		$this->load->library('xmlrpcs');
		
		$config['functions']['ssearchQuotation'] = array('function'=>'Quotation_Server.ssearchQuotation');
		$config['functions']['sgetQuotationHistory'] = array('function'=>'Quotation_Server.sgetQuotationHistory');
		$config['functions']['saddQuotation'] = array('function'=>'Quotation_Server.saddQuotation');
		$config['functions']['sgetQuotation'] = array('function'=>'Quotation_Server.sgetQuotation');
		$config['functions']['sgetQuotationById'] = array('function'=>'Quotation_Server.sgetQuotationById');
		$config['functions']['sgetPaymentDetails'] = array('function'=>'Quotation_Server.sgetPaymentDetails');
		$config['functions']['supdatePaymentDetails'] = array('function'=>'Quotation_Server.supdatePaymentDetails');
		$config['functions']['sgetQuotationDerivedProds'] = array('function'=>'Quotation_Server.sgetQuotationDerivedProds');
		$config['functions']['sfetchBranchesForExecutive'] = array('function'=>'Quotation_Server.sfetchBranchesForExecutive');
		
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
	 * API to search a Quotation
	 */
	function ssearchQuotation($request)
		{
		$parameters = $request->output_parameters();
		$this->setDBHandle();
		
		$formVal = $parameters['1'];
		//error_log_shiksha("parameters ".print_r($formVal,true));
		if($formVal['displayname']!=''){
			$DISP = "AND U.displayname like '%".$this->db->escape_like_str($formVal['displayname'])."%'";
		}else{
			$DISP = "";
		}
		
		if($formVal['email']!=''){
			$EMAIL = "AND U.email like '%".$this->db->escape_like_str($formVal['email'])."%'";
		}else{
			$EMAIL = "";
		}
		
		if($formVal['collegeName']!=''){
			$COLNAME = "AND E.businessCollege like '%".$this->db->escape_like_str($formVal['collegeName'])."%'";
		}else{
			$COLNAME = "";
		}
		
		if($formVal['contactName']!=''){
			$CNTCTNAME = "AND E.contactName like '%".$this->db->escape_like_str($formVal['contactName'])."%'";
		}else{
			$CNTCTNAME = "";
		}
		
		if($formVal['contactNumber']!=''){
			$CNTCTNUM = "AND U.mobile like '%".$this->db->escape_like_str($formVal['contactNumber'])."%'";
		}else{
			$CNTCTNUM = "";
		}
		
		if($formVal['clientId']!=''){
			$CLIENTID = "AND U.userid like '%".$this->db->escape_like_str($formVal['clientId'])."%'";
		}else{
			$CLIENTID = "";
		}
		
		if($formVal['quotationId']!='')
		{
			$QUOTATIONID = "and S.UIQuotationId = ".$this->db->escape($formVal['quotationId']);
		}
		else
		{
			$QUOTATIONID = "";
		}
		
		if($formVal['quotationValue']!='')
		{
			$QUOTATIONVAL = "and S.NetAmount like '%".$this->db->escape_like_str($formVal['quotationValue'])."%'";
		}
		else
		{
			$QUOTATIONVAL = "";
		}
		
		if($formVal['quotationCreater']!='')
		{
			$QUOTATIONCREATOR = "and S.CreatedBy like '%".$this->db->escape_like_str($formVal['quotationCreater'])."%'";
		}
		else
		{
			$QUOTATIONCREATOR = "";
		}
		$query =  "select * from shiksha.tuser U,shiksha.enterpriseUserDetails E, SUMS.Quotation S where U.userid=E.userId and U.userId=S.ClientId AND usergroup='enterprise' $DISP $EMAIL $COLNAME $CNTCTNAME $CNTCTNUM $CLIENTID $QUOTATIONID $QUOTATIONVAL $QUOTATIONCREATOR and S.Status='ACTIVE'";
		error_log_shiksha($query);
		$arrResults = $this->db->query($query);
		$response=array();
		foreach ($arrResults->result_array() as $row)
		{
			array_push($response,array($row,'struct'));
		}
		
		$resp = array($response,'struct');
		//error_log_shiksha(print_r($resp,true)." SUMS: Get User details response!");
		return $this->xmlrpc->send_response ($resp);
		}
	/**
	 * API to get Payment Details
	 */
	function sgetPaymentDetails($request)
		{
		$parameters = $request->output_parameters();
		$this->setDBHandle(TRUE);
		$quotationUId = $parameters[0];
		$query =  "select TransactionId,SalesBy,PAD.*,P.Sale_Type,T.TotalTransactionPrice from Transaction T,Payee_Address_Details PAD,Payment P  where PAD.Transaction_Id = T.TransactionId and P.Transaction_Id = T.TransactionId and P.Transaction_Id = T.TransactionId and T.UIQuotationId = ?";
		$arrResults = $this->db_sums->query($query, array($quotationUId));
		$transactionArray=array();
		foreach ($arrResults->result_array() as $row)
		{
			$TransactionId = $row['TransactionId'];
			array_push($transactionArray,array($row,'struct'));
		}
		$paymemtDetailsArray = array();
		if(isset($TransactionId))
		{
			$query =  "select * from Payment P, Payment_Details PD where P.Payment_Id = PD.Payment_Id and P.Transaction_Id = ? AND PD.isPaid not in('history')";
			$arrResults = $this->db_sums->query($query, array($TransactionId));
			foreach($arrResults->result_array() as $row)
			{
				array_push($paymemtDetailsArray,array($row,'struct'));
			}
		}
		$finalArray = array();
		array_push($finalArray,array(
			array(
				'transactionResult' => array($transactionArray,'struct'),
				'paymentDetails' => array($paymemtDetailsArray,'struct')
			),'struct')
		);
		
		$response = array($finalArray,'struct');
		return $this->xmlrpc->send_response($response);
		
		}
	/**
	 * API to update Payment Details
	 */
	function supdatePaymentDetails($request)
		{
		$parameters = $request->output_parameters();
		$this->setDBHandle(TRUE);
		$updateArray = $parameters[1];
		$paymentId = $parameters[2];
		$partNmber = $parameters[3];
		$where = "Payment_Id = ".$paymentId." AND Part_Number = ".$partNmber;
		$query = $this->db_sums->update_string('Payment_Details',$updateArray,$where);
		$resultOfUpdate = 0;
		if($this->db_sums->query($query))
		{
			$resultOfUpdate = 1;
		}
		$responseArray = array($resultOfUpdate,'int');
		return $this->xmlrpc->send_response($responseArray);
		}
	/**
	 * API to get Quotation History
	 */
	function sgetQuotationHistory($request)
		{
		$parameters = $request->output_parameters();
		$this->setDBHandle();
		$quotationUId = $parameters[0];
		$query =  "select Q.*,U.displayname as editingUserName from SUMS.Quotation Q,shiksha.tuser U where Q.UIQuotationId=? and Q.sumsLoggedInUser=U.userid";
		error_log_shiksha($query);
		$arrResults = $this->db->query($query, array($quotationUId));
		$response=array();
		foreach ($arrResults->result_array() as $row)
		{
			array_push($response,array($row,'struct'));
		}
		
		$resp = array($response,'struct');
		//error_log_shiksha(print_r($resp,true)." SUMS: Get User details response!");
		return $this->xmlrpc->send_response ($resp);
		
		}
	/**
	 * API to add Quotation
	 */
	function saddQuotation($request)
		{
		$parameters = $request->output_parameters();
		$quotationArray = $parameters['1'];
		$quotationProdMapArray = $parameters['2'];
		error_log_shiksha(print_r($quotationArray,true));
		error_log_shiksha(print_r($quotationProdMapArray,true));
		// $this->db_sums_sums = $this->load->database('sums',TRUE);
        $this->setDBHandle(TRUE);
		if (!array_key_exists('UIQuotationId',$quotationArray))
		{
			$quotationArray['UIQuotationId'] = "QUO-".str_pad($quotationArray['ClientId'],8,'0',STR_PAD_LEFT)."-".time();
			$newQuotation = true;
		}
		$quotationArray['Status'] = "ACTIVE";
		$queryCmd = $this->db_sums->insert_string('Quotation',$quotationArray);
		error_log_shiksha("Quotation_Server - addQuotation: ".$queryCmd);
		$this->db_sums->query($queryCmd);
		$quotationId = $this->db_sums->insert_id();
		
		foreach ($quotationProdMapArray as $prod)
		{
			$queryCmd = $this->db_sums->insert_string('Quotation_Product_Mapping',array(
				'QuotationId'=>$quotationId,
				'DerivedProductId'=>$prod['DerivedProductId'],
				'Quantity'=>$prod['Quantity'],
				'Discount'=>$prod['Discount'],
				'Status'=>'PENDING'
			));
			error_log_shiksha("Quotation_Server - addQuotation: ".$queryCmd);
			$this->db_sums->query($queryCmd);
		}
		
		if ($newQuotation!==true)
		{
			$queryCmd = "update Quotation set Status='HISTORY' where UIQuotationId = ? and QuotationId <> ?";
			error_log_shiksha("Quotation_Server - addQuotation: ".$queryCmd);
			$this->db_sums->query($queryCmd, array($quotationArray['UIQuotationId'], $quotationId));
		}
		$response = array (array(
			'QuotationId'=>$quotationId,
			'UIQuotationId'=>$quotationArray['UIQuotationId'],
		),'struct');
		return $this->xmlrpc->send_response($response);
		}
	/**
	 * API to get Quotation
	 */
	function sgetQuotation($request)
		{
		$parameters = $request->output_parameters();
		$UIquotationId = $parameters['1'];
		$quotationType = $parameters['2'];
		$this->setDBHandle(TRUE);
		
		if($quotationType == "TRANSACTION"){
			$queryCmd = "select * from Quotation where UIQuotationId = ? and Status = 'TRANSACTION'";
		}else{
			$queryCmd = "select * from Quotation where UIQuotationId = ? and Status = 'ACTIVE'";
		}
		error_log_shiksha("Quotation Server : getQuotation GMM ::".$queryCmd);
		
		$result = $this->db_sums->query($queryCmd, array($UIquotationId));
		$Quotation = array();
		foreach ($result->result_array() as $row)
		{
			$Quotation = $row;
			$QuotationId = $row['QuotationId'];
		}
		
		$quotationProdMap = array();
		if(isset($QuotationId))
		{
			$queryCmd = "select * from Quotation_Product_Mapping where QuotationId =?";
			$query = $this->db_sums->query($queryCmd, array($QuotationId));
			foreach ($query->result_array() as $row)
			{
				$quotationProdMap[$row['DerivedProductId']] = array($row,'struct');
			}
		}
		$response = array (array(
			'QuotationDetails'=>array($Quotation,'struct'),
			'QuotationProducts'=>array($quotationProdMap,'struct')
		),'struct');
		return $this->xmlrpc->send_response($response);
		}
	/**
	 * API to get Quotation by Quotation Id
	 */
	function sgetQuotationById($request)
		{
		$parameters = $request->output_parameters();
		$quotationId = $parameters['1'];
		$this->setDBHandle(TRUE);
		
		$queryCmd = "select * from Quotation where QuotationId = ? and Status = 'ACTIVE'";
		error_log_shiksha("Quotation Server : getQuotation ".$queryCmd);
		$result = $this->db_sums->query($queryCmd, array($quotationId));
		$Quotation = array();
		foreach ($result->result_array() as $row)
		{
			$Quotation = $row;
			$QuotationId = $row['QuotationId'];
		}
		
		$quotationProdMap = array();
		$queryCmd = "select * from Quotation_Product_Mapping where QuotationId =?";
		$query = $this->db_sums->query($queryCmd, array($QuotationId));
		foreach ($query->result_array() as $row)
		{
			array_push ($quotationProdMap,array($row,'struct'));
		}
		$response = array (array(
			'QuotationDetails'=>array($Quotation,'struct'),
			'QuotationProducts'=>array($quotationProdMap,'struct')
		),'struct');
		return $this->xmlrpc->send_response($response);
		}
	/**
	 * API to get Derived Products belonging to Quotation
	 */
	function sgetQuotationDerivedProds($request)
		{
		$parameters = $request->output_parameters();
		$UIquotationId = $parameters['1'];
		$derivedProdStatus = $parameters['2'];
		$this->setDBHandle(TRUE);
		
		$queryCmd = "select * from Quotation where UIQuotationId = ? and Status = 'TRANSACTION'";
		error_log_shiksha("Quotation Server : getQuotation GMM ::".$queryCmd);
		
		$result = $this->db_sums->query($queryCmd, array($UIquotationId));
		$Quotation = array();
		foreach ($result->result_array() as $row)
		{
			$Quotation = $row;
			$QuotationId = $row['QuotationId'];
		}
		
		$quotationProdMap = array();
		if(isset($QuotationId))
		{
			$queryCmd = 'select * from Quotation_Product_Mapping where QuotationId = ? and Status = ?';
			$query = $this->db_sums->query($queryCmd, array($QuotationId, $derivedProdStatus));
			foreach ($query->result_array() as $row)
			{
				$quotationProdMap[$row['DerivedProductId']] = array($row,'struct');
			}
		}
		$response = array (array(
			'QuotationDetails'=>array($Quotation,'struct'),
			'QuotationProducts'=>array($quotationProdMap,'struct')
		),'struct');
		return $this->xmlrpc->send_response($response);
		}
	/**
	 * API to fetch Branches for Executive
	 */
	function sfetchBranchesForExecutive($request)
		{
		$parameters = $request->output_parameters();
		$this->setDBHandle(TRUE);
		$execId = $parameters[1];
		$query =  "select U.BranchId,B.BranchName from Sums_User_Branch_Mapping U,Sums_Branch_List B where U.userId=? and U.BranchId=B.BranchId";
		error_log_shiksha($query);
		$arrResults = $this->db_sums->query($query, array($execId));
		$response=array();
		foreach ($arrResults->result_array() as $row)
		{
			array_push($response,array($row,'struct'));
		}
		
		$resp = array($response,'struct');
		error_log_shiksha(print_r($resp,true)." SUMS: Get User details response!");
		return $this->xmlrpc->send_response ($resp);
		}
}
?>
