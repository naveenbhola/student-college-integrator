<?php

/*
 
 Copyright 2007 Info Edge India Ltd
 
 $Rev:: 411           $:  Revision of last commit
 $Author: build $:  Author of last commit
 $Date: 2009-09-02 10:50:02 $:  Date of last commit
 
 This class provides the MIS Web Services. 
 
 $Id: mis_server.php,v 1.54 2009-09-02 10:50:02 build Exp $: 
 
 */

/**
 * Class for Sums MIS Web Services 
 * 
 */
class Mis_server extends MX_Controller {
	
	/**
	 *	index function to recieve the incoming request
	 */
	private $db_sums;
	private $db;
	
	function index(){
		
		$this->dbLibObj = DbLibCommon::getInstance('SUMS');
		//load XML RPC Libs
		$this->load->library('xmlrpc');
		$this->load->library('xmlrpcs');
		
		//Define the web services method
		$config['functions']['getPaymentMIS'] = array('function' => 'Mis_server.getPaymentMIS');
		$config['functions']['getTransactionMIS'] = array('function' => 'Mis_server.getTransactionMIS');
		$config['functions']['getInventryMIS'] = array('function' => 'Mis_server.getInventryMIS');
		$config['functions']['getShikshaInventryMIS'] = array('function' => 'Mis_server.getShikshaInventryMIS');
		$config['functions']['getProductMIS'] = array('function' => 'Mis_server.getProductMIS');
		$config['functions']['getTransactionAndPaymentDetails'] = array('function' => 'Mis_server.getTransactionAndPaymentDetails');
		$config['functions']['getPartPaymentHistory'] = array('function' => 'Mis_server.getPartPaymentHistory');
		$config['functions']['getdata'] = array('function' => 'Mis_server.getdata');
		$config['functions']['sgetClientTransactionDetails'] = array('function' => 'Mis_server.sgetClientTransactionDetails');
        $config['functions']['sgetEditPaymentDetails'] = array('function' => 'Mis_server.sgetEditPaymentDetails');

		//initialize
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
	

	function getTransactionAndPaymentDetails($request)
	{
		$parameters = $request->output_parameters();
		$this->setDBHandle(TRUE);
		$transactionId = $parameters['1'];
		if(isset($parameters['1'])){
			$Part_Number = $parameters['2'];
		}else{
			$Part_Number = 1;
		}
		$query =  "SELECT t.email, t.userid, t.displayName, t.firstName, p . * , pd . * 
		FROM  `shiksha`.`tuser` t
		LEFT JOIN  `SUMS`.`Transaction` tran ON t.userid = tran.ClientUserId
		LEFT JOIN  `SUMS`.`Payment` p ON tran.TransactionId = p.Transaction_Id
		LEFT JOIN  `SUMS`.`Payment_Details` pd ON p.Payment_Id = pd.Payment_Id
		WHERE tran.TransactionId = ? AND pd.Part_Number = ?";
		
		$sqlParameter =array($transactionId, $Part_Number);
		$arrResults = $this->db_sums->query($query, $sqlParameter);

		$msgArray=array();
		if($arrResults->num_rows == 0){
			return $this->xmlrpc->send_response ("0");
		}

		foreach ($arrResults->result_array() as $row)
		{
			$clientID = $row['userid'];
			array_push($msgArray,array($row,'struct'));
		}
		$query =  "select (select CurrencyCode from SUMS.Currency c1 where c1.CurrencyId = t.CurrencyId) as CurrencyName,userRes.*,userRes1.*,q.FinalSalesAmount,q.ServiceTax,q.serviceTaxPercentage as servicetaxpercentage,qpm.Quantity,qpm.Discount,t.ClientUserId,t.SumsUserId,t.SalesBy,t.TotalTransactionPrice,tq.State TransactionStatus,dp.DerivedProductName,dppm.ManagementPrice,dppm.SuggestedPrice,tq.CancelCommets from (select t1.email trUSerEmail,t1.firstname trUsreName,t1.displayName trUserDisplayName from shiksha.tuser t1 where t1.userid = (select tr1.SumsUserId from SUMS.Transaction tr1 where tr1.TransactionId = ?)) userRes,(select t2.email saleByUSerEmail,t2.firstname saleByUsreName,t2.displayName saleByUserDisplayName from shiksha.tuser t2 where t2.userid = (select tr2.SalesBy from SUMS.Transaction tr2 where tr2.TransactionId = ?)) userRes1,SUMS.Transaction t,SUMS.Quotation q,SUMS.Quotation_Product_Mapping qpm,SUMS.Derived_Products dp,SUMS.Derived_Prod_Price_Map dppm,shiksha.enterpriseUserDetails eud,SUMS.Transaction_Queue tq where q.UIQuotationId = t.UIQuotationId and q.Status = 'TRANSACTION' and q.QuotationId = qpm.QuotationId and qpm.DerivedProductId = dp.DerivedProductId and  dp.DerivedProductId = dppm.DerivedProductId and eud.userId = t.ClientUserId and tq.TransactionId = t.TransactionId and t.CurrencyId = dppm.CurrencyId and t.TransactionId = ?";

		/*$query =  "select  q.FinalSalesAmount,q.ServiceTax,qpm.Quantity,qpm.Discount,t.ClientUserId,t.SumsUserId,t.SalesBy,t.TotalTransactionPrice,tq.State TransactionStatus,dp.DerivedProductName,dppm.ManagementPrice,dppm.SuggestedPrice,tq.CancelCommets from SUMS.Transaction t,SUMS.Quotation q,SUMS.Quotation_Product_Mapping qpm,SUMS.Derived_Products dp,SUMS.Derived_Prod_Price_Map dppm,shiksha.enterpriseUserDetails eud,SUMS.Transaction_Queue tq  where q.UIQuotationId = t.UIQuotationId and q.Status = 'TRANSACTION' and q.QuotationId = qpm.QuotationId and qpm.DerivedProductId = dp.DerivedProductId and  dp.DerivedProductId = dppm.DerivedProductId and eud.userId = t.ClientUserId and tq.TransactionId = t.TransactionId and t.TransactionId = ".$transactionId;*/
		//echo $query;

		$arrResults = $this->db_sums->query($query, array($transactionId, $transactionId, $transactionId));
		$msgArray1=array();
		foreach ($arrResults->result_array() as $row)
		{
				
			array_push($msgArray1,array($row,'struct'));
		}

		$query =  "select PAD.*,concat('<a href=\"mailto:',user.email,'\">',user.displayName,'</a>') as displayName,user.displayName as primaryName,user.email emailId,user.city userCity,user.firstname from SUMS.Payee_Address_Details PAD,shiksha.tuser user where user.userid = ? AND PAD.Transaction_Id = ?";
		$arrResults = $this->db_sums->query($query, array($clientID, $transactionId));
		$msgArray2=array();

		foreach ($arrResults->result_array() as $row)
		{
			array_push($msgArray2,array($row,'struct'));
		}

		$response = array();
		array_push($response,array(array('paymentDetails' => array($msgArray,'struct'),'transactionDetails' => array($msgArray1,'struct'),'clientDetails' => array($msgArray2,'struct')),'struct'));
		$resp = array($response,'struct');
		return $this->xmlrpc->send_response ($resp);

	}


    function sgetEditPaymentDetails($request)
    {
        $parameters = $request->output_parameters();
        $this->setDBHandle(TRUE);
        $transactionId = $parameters['1'];

        //connect DB
        $details = array();
        if($dbHandle == ''){
            log_message('error',' can not create db handle');
        }

        $query =  "select t1.email,t1.userid,t1.displayName,t1.firstName,p.*,pd.*,ifnull((select displayName from shiksha.tuser where tuser.userid=pd.loggedInUserId),'N.A.') as EditedBy from  SUMS.Payment p INNER JOIN SUMS.Payment_Details pd ON (p.Payment_Id = pd.Payment_Id) LEFT JOIN shiksha.tuser t1 ON (pd.Deposited_By = t1.userid) where pd.isPaid not in('History') and p.Transaction_Id = ?";


        $arrResults = $this->db_sums->query($query, array($transactionId));

        $msgArray=array();

        foreach ($arrResults->result_array() as $row)
        {
            array_push($msgArray,array($row,'struct'));
        }


        $query = "SELECT p. * , pd. * , T. * ,eud.* FROM Payment p, Payment_Details pd, Transaction T,shiksha.enterpriseUserDetails eud WHERE p.Transaction_Id = T.TransactionId   AND p.Payment_Id = pd.Payment_Id and eud.userId = T.ClientUserId AND T.TransactionId =?";

        $arrResults = $this->db_sums->query($query, array($transactionId));
        $msgArray1=array();
        foreach ($arrResults->result_array() as $row)
        {
            array_push($msgArray1,array($row,'struct'));
            $clientID = $row['ClientUserId'];
        }               

        $query =  "select user.*,concat('<a href=\"mailto:',user.email,'\">',user.displayName,'</a>') as displayName,user.displayName as primaryName,user.email emailId,user.city userCity,user.firstname from shiksha.tuser user where user.userid = ?";
        $arrResults = $this->db_sums->query($query, array($clientID));
        $msgArray2=array();

        foreach ($arrResults->result_array() as $row)
        {
            array_push($msgArray2,array($row,'struct'));
        }   

        $response = array();
        array_push($response,array(array('paymentDetails' => array($msgArray,'struct'),'transactionDetails' => array($msgArray1,'struct'),'clientDetails' => array($msgArray2,'struct')),'struct'));
        $resp = array($response,'struct');
        return $this->xmlrpc->send_response ($resp);


    }


	function sgetClientTransactionDetails($request)
	{

		$parameters = $request->output_parameters();
		$this->setDBHandle(TRUE);
		$transactionId = $parameters['1'];

		$query =  "select t1.email,t1.userid,t1.displayName,t1.firstName,p.*,pd.*,ifnull((select displayName from shiksha.tuser where tuser.userid=pd.loggedInUserId),'N.A.') as EditedBy from  SUMS.Payment p INNER JOIN SUMS.Payment_Details pd ON (p.Payment_Id = pd.Payment_Id) LEFT JOIN shiksha.tuser t1 ON (pd.Deposited_By = t1.userid) where pd.isPaid not in('History') and p.Transaction_Id = ?";
		/*$query =  "select p.*,pd.* from SUMS.Payment_Details pd,SUMS.Payment p where p.Payment_Id = pd.Payment_Id and pd.isPaid not in('History') and p.Transaction_Id = ".$transactionId;*/

		//connect DB
		$details = array();
		if($dbHandle == ''){
			log_message('error',' can not create db handle');
		}

		$arrResults = $this->db_sums->query($query, array($transactionId));

		$msgArray=array();

		foreach ($arrResults->result_array() as $row)
		{
			array_push($msgArray,array($row,'struct'));
		}


		$query = "SELECT p. * , pd. * , T. * , S. * , Spm. * , Dpm . *,eud.*,D.* FROM Payment p, Payment_Details pd, Transaction T,Derived_Products D, Subscription S, Subscription_Product_Mapping Spm, Derived_Products_Mapping Dpm,shiksha.enterpriseUserDetails eud WHERE D.DerivedProductId = Dpm.DerivedProductId and p.Transaction_Id = T.TransactionId AND S.TransactionId = T.TransactionId AND Spm.SubscriptionId = S.SubscriptionId AND Dpm.DerivedProductId = S.DerivedProductId AND p.Payment_Id = pd.Payment_Id and eud.userId = T.ClientUserId AND T.TransactionId =?";

		$arrResults = $this->db_sums->query($query, array($transactionId));
		$msgArray1=array();
		foreach ($arrResults->result_array() as $row)
		{
			array_push($msgArray1,array($row,'struct'));
			$clientID = $row['ClientUserId'];
		}				

		$query =  "select user.*,concat('<a href=\"mailto:',user.email,'\">',user.displayName,'</a>') as displayName,user.displayName as primaryName,user.email emailId,user.city userCity,user.firstname from shiksha.tuser user where user.userid = ?";
		$arrResults = $this->db_sums->query($query, array($clientID));
		$msgArray2=array();

		foreach ($arrResults->result_array() as $row)
		{
			array_push($msgArray2,array($row,'struct'));
		}	



		$response = array();
		array_push($response,array(array('paymentDetails' => array($msgArray,'struct'),'transactionDetails' => array($msgArray1,'struct'),'clientDetails' => array($msgArray2,'struct')),'struct'));
		$resp = array($response,'struct');
		return $this->xmlrpc->send_response ($resp);


	}



	function getdata($request)
	{
		$parameters = $request->output_parameters();

		$this->setDBHandle(TRUE);
		$appId = $parameters['0'];
		$transactionId = $parameters['1'];

		$query ="select T.SubscriptionStartDate,T.SubscriptionEndDate from  SUMS.Subscription_Product_Mapping T, SUMS.Subscription S where S.SubscriptionId = T.SubscriptionId and S.TransactionId = ?";

		$arrResults = $this->db_sums->query($query, array($transactionId));
		$msgArray=array();
		foreach ($arrResults->result_array() as $row)
		{
			array_push($msgArray,array($row,'struct'));
		}
		$querycmd ="select B.BranchName from SUMS.Sums_Branch_List B,SUMS.Transaction N where B.BranchId = N.SalesBranch and N.TransactionId = ?";
		$arrResult = $this->db_sums->query($querycmd, array($transactionId));

		$msgArray1=array();
		foreach ($arrResult->result_array() as $row)
		{
			array_push($msgArray1,array($row,'struct'));
		}

		$response = array();
		array_push($response,array(array('1' => array($msgArray,'struct'),'2' => array($msgArray1,'struct')),'struct'));
		$resp = array($response,'struct');
		return $this->xmlrpc->send_response ($resp);

	}

	
	/**
	 * get Part Payment History information.
	 */
	function getPartPaymentHistory($request)
		{
		$parameters = $request->output_parameters();
		$this->setDBHandle(TRUE);
		$paymentId = $parameters['1'];
		$PartNumber = $parameters['2'];
		$query =  "select  pl.*,t1.*,ifnull((select displayName from shiksha.tuser t2 where t2.userid=pl.loggedInUserId),'N.A.') as EditedBy from SUMS.Payment_Logs pl LEFT JOIN shiksha.tuser t1 ON (pl.Deposited_By = t1.userid)  where  pl.Payment_Id = ? and pl.Part_Number = ?";
		$arrResults = $this->db_sums->query($query, array($paymentId, $PartNumber));
		$msgArray=array();
		foreach ($arrResults->result_array() as $row)
		{
			array_push($msgArray,array($row,'struct'));
		}
		$query =  "select p1.*,t1.*,ifnull((select displayName from shiksha.tuser t2 where t2.userid=p1.loggedInUserId),'N.A.') as EditedBy from SUMS.Payment_Details p1 LEFT JOIN shiksha.tuser t1 ON (p1.Deposited_By = t1.userid)  where  p1.Payment_Id = ? and p1.Part_Number = ?";
		$arrResults = $this->db_sums->query($query, array($paymentId, $PartNumber));
		$msgArray1=array();
		foreach ($arrResults->result_array() as $row)
		{
			array_push($msgArray1,array($row,'struct'));
		}
		$response = array();
		array_push($response,array(array('paymentLogs' => array($msgArray,'struct'),'recentPayment' => array($msgArray1,'struct')),'struct'));
		$resp = array($response,'struct');
		return $this->xmlrpc->send_response ($resp);
		
		}
	/**
	 * get Transaction MIS
	 */
	function getTransactionMIS($request){
		$parameters = $request->output_parameters();
		$startDate=$parameters[0];
		$endDate=$parameters[1];
		$executiveNameCSV=$parameters[2]; //get executive id in csv =ALL for all executive
		$clientNameCSV=$parameters[3]; //get client id in csv =ALL for all clients
		$saleType=$parameters[4]; //Credit Sale=2, Trial Sale=3, Non Credit Sale=1
		$transactionStatus=$parameters[5]; //transaction status
		$queryCmd_offset =  $parameters[7]; // offset of the 1st row 2 return
		$queryCmd_rows = $parameters[8]; //maximum number of rows to return
		$limit_flag = $parameters[9];
		$branchIds = $parameters[10]; // get Branch Ids as CSV in ALL -1
		if($branchIds!=-1)
		{
			$branchFilter="and Transaction.SalesBranch=(".$branchIds.")";
		}
		//connect DB
		$this->setDBHandle(TRUE);
		$dbHandle = $this->db_sums;
		
		$paymentRecv=" SUM(Payment_Details.Amount_Received),SUM(Payment_Details.TDS_Amount) ";
		$groupByPaymentRecv=" group by Payment_Details.Payment_Id ";
		if ($saleType ==1) {
			$saleTypeString=" 'Full Payment', 'Part Payment' ";
		}else if($saleType==2){
			$saleTypeString=" 'Credit' ";
		}else if($saleType=3){
			$saleTypeString=" 'Trial' ";
		}
		
		
		$clientFilter=" and ClientUserId in ($clientNameCSV) ";
		$userFilter=" and SalesBy in ($executiveNameCSV) ";
		
		if(strcasecmp($clientNameCSV,'ALL')==0){
			$clientFilter = "";
		}
		if(strcasecmp($executiveNameCSV,'ALL')==0){
			$userFilter= "";
		}
		
		$queryCmd="select SQL_CALC_FOUND_ROWS     TransactionId,concat(TotalTransactionPrice,' ',CurrencyCode) TotalTransactionPrice,TransactTime, Sale_Type,concat(TotalDiscount,' ',CurrencyCode)TotalDiscount,concat(ServiceTax,' ',CurrencyCode) ServiceTax,concat(NetAmount,' ',CurrencyCode)NetAmount,salesBy, QuotationBy,branchName,ClientUserName,concat(totalPaymentRecieved,' ',CurrencyCode)totalPaymentRecieved,concat(amountOutstanding,' ',CurrencyCode)amountOutstanding,concat(totalTDS,' ',CurrencyCode)totalTDS ,NoOfPayment,State from (select Transaction_Queue.State, concat('<a href=\"/sums/MIS/viewTransactionDetails/',Transaction.TransactionId,'\">',Transaction.TransactionId,'</a>') as TransactionId,TotalTransactionPrice,TransactTime, Sale_Type,TotalDiscount,ServiceTax,NetAmount,(select concat('<a href=\"mailto:',email,'\">',displayName,'</a>') as displayName from shiksha.tuser where shiksha.tuser.userId=SalesBy) as salesBy,(select concat('<a href=\"mailto:',email,'\">',displayName,'</a>') as displayName from shiksha.tuser where shiksha.tuser.userId=CreatedBy) as QuotationBy,(select group_concat(Sums_Branch_List.BranchName) from Sums_Branch_List where Transaction.SalesBranch=Sums_Branch_List.BranchId) as branchName,(select concat('<a href=\"mailto:',email,'\">',displayName,'</a>') as displayName from shiksha.tuser where shiksha.tuser.userId=ClientUserId) as ClientUserName,(select sum(Amount_Received) from Payment_Details where isPaid='Paid' and Payment_Details.Payment_Id=Payment.Payment_Id) totalPaymentRecieved,(select sum(Amount_Received) from Payment_Details where isPaid in ('Un-paid','In-process') and Payment_Details.Payment_Id=Payment.Payment_Id) as amountOutstanding,(select sum(TDS_Amount) from Payment_Details where isPaid='Paid' and Payment_Details.Payment_Id=Payment.Payment_Id)totalTDS,(select count(*) from Payment_Details where isPaid='Paid' and Payment_Details.Payment_Id=Payment.Payment_Id)NoOfPayment,(select CurrencyCode from Currency where Currency.CurrencyId=Transaction.CurrencyId) as CurrencyCode from Transaction,Payment,Quotation,Transaction_Queue where Quotation.UIQuotationId=Transaction.UIQuotationId and Payment.Transaction_Id=Transaction.TransactionId and State=? $clientFilter $userFilter  and Payment.Sale_Type in ($saleTypeString) AND  Transaction.TransactTime<=? and Transaction.TransactTime >= ? and Transaction_Queue.TransactionId=Transaction.TransactionId and Quotation.Status!='HISTORY' $branchFilter) as XYZ order by TransactionId";
		if ($limit_flag == 'sums_mis_report_show') {
			$queryCmd .= "  LIMIT $queryCmd_offset , $queryCmd_rows ";
		}
		$query = $dbHandle->query($queryCmd, array($transactionStatus, $endDate, $startDate));
		/*  Add For Pagination   */
		$response=array();
		$tempArray=array();
		foreach ($query->result_array() as $row)
		{
			array_push ($tempArray,array($row,'struct'));
		}
		$queryCmd = 'SELECT FOUND_ROWS() as totalRows';
		$query = $dbHandle->query($queryCmd);
		$totalRows = 0;
		foreach ($query->result() as $row) {
			$totalRows = $row->totalRows;
		}
		array_push($response,array(
			array(
				'results'=>array($tempArray,'struct'),
				'totalCount'=>array($totalRows,'string'),
			),'struct')
		);
		$resp = array($response,'struct');
		return $this->xmlrpc->send_response ($resp);
		/*  Add For Pagination   */
		
	}
	
	
	
	/**
	 * get Payment MIS
	 */
	function getPaymentMIS($request){
		$parameters = $request->output_parameters();
		$startDate=$parameters[0];
		$endDate=$parameters[1];
		$executiveNameCSV=$parameters[2]; //get executive id in csv =ALL for all
		$clientNameCSV=$parameters[3]; //get client id in csv =ALL for all
		$paymentMode=$parameters[4]; //Cash, cheque, dd, tt, credit card offline, latter in csv
		$currencyMode=$parameters[5];//INR=1 USD=2
		$paymentStatus=$parameters[6]; //paid, unpaid, inProcess, cancelled in csv
		$queryCmd_offset =  $parameters[8]; // offset of the 1st row 2 return
		$queryCmd_rows = $parameters[9]; //maximum number of rows to return
		$limit_flag = $parameters[10];
		$branchIds = $parameters[11]; // get Branch Ids as CSV in ALL -1
		//connect DB
		$this->setDBHandle(TRUE);
		$dbHandle = $this->db_sums;
		
		//check if any topic is posted on this topic or not?
		
		$clientFilter=" and ClientUserId in ($clientNameCSV) ";
		$userFilter=" and SalesBy in ($executiveNameCSV) ";
		$dateFilter="and Cheque_Date >= ".$dbHandle->escape($startDate)." and Cheque_Date <= ".$dbHandle->escape($endDate);
		if(strcasecmp($clientNameCSV,'ALL')==0){
			$clientFilter = "";
		}
		if(strcasecmp($executiveNameCSV,'ALL')==0){
			$userFilter = "";
		}
		if($branchIds!=-1)
		{
			$branchFilter=" and Transaction.SalesBranch=(".$branchIds.") ";
		}
		if($limit_flag == 'sums_mis_report_show')
		{
			$queryCmd="select SQL_CALC_FOUND_ROWS  TransactionId,ClientUserName,concat(TotalTransactionPrice,' ',CurrencyCode)`Sale Amount`,TransactTime,salesBy,concat(Payment_Id,'-',Part_Number) as `PaymentID-PartNumber`,Sale_Type,Cheque_No, Cheque_Date as `Reciept Date`,Cheque_City,Cheque_Bank,Cheque_Receiving_Date as `Cheque Date`,concat(Amount_Received,' ',CurrencyCode)`Collection Amount`,concat(TDS_Amount,' ',CurrencyCode)TDS_Amount,concat(totalPaymentRecieved,' ',CurrencyCode)`total Collection`, TotalCheckAmmount, Cheque_DD_Comments,Payment_Mode,Deposited_By,Deposited_Branch,Cancel_Date,isPaid as `Payment Status`,branchName from (select concat('<a href=\"/sums/MIS/viewTransactionDetails/',Transaction.TransactionId,'\">',Transaction.TransactionId,'</a>') as TransactionId,(select sum(PD.Amount_Received) from Payment_Details PD where PD.Cheque_No=Payment_Details.Cheque_No  and PD.isPaid='Paid' and PD.Payment_Mode='Cheque' group by PD.Cheque_No ) `TotalCheckAmmount`,(select concat('<a href=\"mailto:',email,'\">',displayName,'</a>') as displayName from shiksha.tuser where shiksha.tuser.userId=ClientUserId) as ClientUserName,TotalTransactionPrice,(select CurrencyCode from Currency where Currency.CurrencyId=Transaction.CurrencyId) as CurrencyCode,TransactTime,(select concat('<a href=\"mailto:',email,'\">',displayName,'</a>') as displayName from shiksha.tuser where shiksha.tuser.userId=SalesBy) as salesBy,Payment_Details.Payment_Id,Payment_Details.Part_Number,Sale_Type,Cheque_No, Cheque_Date,Cheque_City,Cheque_Bank,Cheque_Receiving_Date,Amount_Received,TDS_Amount,(select sum(Amount_Received) from Payment_Details where isPaid='Paid' and Payment_Details.Payment_Id=Payment.Payment_Id) totalPaymentRecieved,Cheque_DD_Comments,Payment_Mode,Deposited_By,Deposited_Branch,Cancel_Date,isPaid,(select group_concat(Sums_Branch_List.BranchName) from Sums_Branch_List where Sums_Branch_List.BranchId=Transaction.SalesBranch) as branchName, (select group_concat(DerivedProductName) from Derived_Products,Quotation,Quotation_Product_Mapping  where Derived_Products.DerivedProductId=Quotation_Product_Mapping.DerivedProductId and Quotation.QuotationId=Quotation_Product_Mapping.QuotationId and Quotation.UIQuotationId=Transaction.UIQuotationId) as ProductSold from Transaction,Payment, Payment_Details,Transaction_Queue where Transaction.TransactionId  = Payment.Transaction_Id and Payment.Payment_Id=Payment_Details.Payment_Id $clientFilter $userFilter and Transaction_Queue.TransactionId=Transaction.TransactionId and Payment_Mode in ('$paymentMode') and isPaid in ('$paymentStatus') and CurrencyId=? $dateFilter $branchFilter) as XYZ";
		}
		else
		{
			$queryCmd="select SQL_CALC_FOUND_ROWS  TransactionId,ClientUserName,TotalTransactionPrice `Sale Amount`,TransactTime,salesBy,concat(Payment_Id,'-',Part_Number) as `PaymentID-PartNumber`,Sale_Type,Cheque_No, Cheque_Date as `Reciept Date`,Cheque_City,Cheque_Bank,Cheque_Receiving_Date as `Cheque Date`,Amount_Received `Collection Amount`,TDS_Amount,totalPaymentRecieved `total Collection`,TotalCheckAmmount,Cheque_DD_Comments,Payment_Mode,Deposited_By,Deposited_Branch,Cancel_Date,isPaid as `Payment Status`,branchName, CurrencyCode from (select concat('<a href=\"/sums/MIS/viewTransactionDetails/',Transaction.TransactionId,'\">',Transaction.TransactionId,'</a>') as TransactionId,(select sum(PD.Amount_Received) from Payment_Details PD where PD.Cheque_No=Payment_Details.Cheque_No  and PD.isPaid='Paid' and PD.Payment_Mode='Cheque' group by PD.Cheque_No ) `TotalCheckAmmount`,(select concat('<a href=\"mailto:',email,'\">',displayName,'</a>') as displayName from shiksha.tuser where shiksha.tuser.userId=ClientUserId) as ClientUserName,TotalTransactionPrice,(select CurrencyCode from Currency where Currency.CurrencyId=Transaction.CurrencyId) as CurrencyCode,TransactTime,(select concat('<a href=\"mailto:',email,'\">',displayName,'</a>') as displayName from shiksha.tuser where shiksha.tuser.userId=SalesBy) as salesBy,Payment_Details.Payment_Id,Payment_Details.Part_Number,Sale_Type,Cheque_No, Cheque_Date,Cheque_City,Cheque_Bank,Cheque_Receiving_Date,Amount_Received,TDS_Amount,(select sum(Amount_Received) from Payment_Details where isPaid='Paid' and Payment_Details.Payment_Id=Payment.Payment_Id) totalPaymentRecieved,Cheque_DD_Comments,Payment_Mode,Deposited_By,Deposited_Branch,Cancel_Date,isPaid,(select group_concat(Sums_Branch_List.BranchName) from Sums_Branch_List where Sums_Branch_List.BranchId=Transaction.SalesBranch) as branchName, (select group_concat(DerivedProductName) from Derived_Products,Quotation,Quotation_Product_Mapping  where Derived_Products.DerivedProductId=Quotation_Product_Mapping.DerivedProductId and Quotation.QuotationId=Quotation_Product_Mapping.QuotationId and Quotation.UIQuotationId=Transaction.UIQuotationId) as ProductSold from Transaction,Payment, Payment_Details,Transaction_Queue where Transaction.TransactionId  = Payment.Transaction_Id and Payment.Payment_Id=Payment_Details.Payment_Id $clientFilter $userFilter and Transaction_Queue.TransactionId=Transaction.TransactionId and Payment_Mode in ('$paymentMode') and isPaid in ('$paymentStatus') and CurrencyId=? $dateFilter $branchFilter) as XYZ";	
		}
		if ($limit_flag == 'sums_mis_report_show') {
			$queryCmd .= "  LIMIT $queryCmd_offset , $queryCmd_rows ";
		}
		$query = $dbHandle->query($queryCmd, array($currencyMode));
		/*  Add For Pagination   */
		$response=array();
		$tempArray=array();
		foreach ($query->result_array() as $row)
		{
			array_push ($tempArray,array($row,'struct'));
		}
		$queryCmd = 'SELECT FOUND_ROWS() as totalRows';
		$query = $dbHandle->query($queryCmd);
		$totalRows = 0;
		foreach ($query->result() as $row) {
			$totalRows = $row->totalRows;
		}
		array_push($response,array(
			array(
				'results'=>array($tempArray,'struct'),
				'totalCount'=>array($totalRows,'string'),
			),'struct')
		);
		$resp = array($response,'struct');
		return $this->xmlrpc->send_response ($resp);
		/*  Add For Pagination   */
	}
	
	/**
	 *	Get Client Inventry MIS
	 */
	function getInventryMIS($request) {
		$parameters = $request->output_parameters();
		$startDate=$parameters[0];
		$endDate=$parameters[1];
		$clientNameCSV=$parameters[2]; //get client id in csv
		$queryCmd_offset =  $parameters[5]; // offset of the 1st row 2 return
		$queryCmd_rows = $parameters[6]; //maximum number of rows to return
		$limit_flag = $parameters[7];
		$branchIds = $parameters[8]; // get Branch Ids as CSV in ALL -1
		if($branchIds!=-1)
		{
			$branchFilter="and T.SalesBranch=(".$branchIds.") ";
		}
		//connect DB
		$this->setDBHandle(TRUE);
		$dbHandle = $this->db_sums;
		$queryCmd="select  SQL_CALC_FOUND_ROWS concat('<a href=\"/sums/MIS/viewTransactionDetails/',T.TransactionId,'\">',T.TransactionId,'</a>') as TransactionId,(select concat('<a href=\"mailto:',email,'\">',displayName,'</a>') as displayname from shiksha.tuser U where U.userid=T.ClientUserId) as ClientName, (select concat('<a href=\"mailto:',email,'\">',displayName,'</a>') as displayname from shiksha.tuser U where U.userid=T.SalesBy) as Saleby, BaseProdCategory as ProductType, BaseProdSubCategory as ProductName, TotalBaseProdQuantity as TotalQuantity, BaseProdRemainingQuantity as RemainingQuantity, SubscriptionEndDate as LastSubscriptionExpiryDate, (select sum(PD.Amount_Received) from Payment P, Payment_Details PD where P.Payment_Id=PD.Payment_Id and P.Transaction_Id=T.TransactionId and isPaid in ('Paid','Un-paid','In-process')) as TotalTransactionAmount, (select sum(PD.Amount_Received) from Payment P, Payment_Details PD where P.Payment_Id=PD.Payment_Id and P.Transaction_Id=T.TransactionId and PD.isPaid='Paid') as `Amount Recieved` from Base_Products, Subscription_Product_Mapping, Subscription, Transaction T,Transaction_Queue TQ where Subscription_Product_Mapping.BaseProductId = Base_Products.BaseProductId and Subscription.SubscriptionId=Subscription_Product_Mapping.SubscriptionId and Subscription.TransactionId= T.TransactionId and Subscription_Product_Mapping.BaseProductId!=7 and  TQ.State!='CANCELLED' and TQ.TransactionId=T.TransactionId";
		if ($clientNameCSV != 'ALL') {
			$queryCmd .= " and T.ClientUserId in (".$clientNameCSV.") ";	
		}
		$queryCmd .= " and T.TransactTime > ? and T.TransactTime < ? $branchFilter order by T.ClientUserId, T.TransactionId";
		
		if ($limit_flag == 'sums_mis_report_show') {
			$queryCmd .= "  LIMIT $queryCmd_offset , $queryCmd_rows ";
		}
		
		$query = $dbHandle->query($queryCmd, array($startDate, $endDate));
		/*  Add For Pagination   */
		$response=array();
		$tempArray=array();
		foreach ($query->result_array() as $row)
		{
			array_push ($tempArray,array($row,'struct'));
		}
		$queryCmd = 'SELECT FOUND_ROWS() as totalRows';
		$query = $dbHandle->query($queryCmd);
		$totalRows = 0;
		foreach ($query->result() as $row) {
			$totalRows = $row->totalRows;
		}
		array_push($response,array(
			array(
				'results'=>array($tempArray,'struct'),
				'totalCount'=>array($totalRows,'string'),
			),'struct')
		);
		$resp = array($response,'struct');
		return $this->xmlrpc->send_response ($resp);
		/*  Add For Pagination   */
		
	}	
	
	/**
	 *	Get Shiksha Inventry MIS
	 */
	function getShikshaInventryMIS($request) {
		$parameters = $request->output_parameters();
		$startDate=$parameters[0];
		$endDate=$parameters[1];
		$type=$parameters[2]; //by Category or by Search
		
		$queryCmd_offset =  $parameters[4]; // offset of the 1st row 2 return
		$queryCmd_rows = $parameters[5]; //maximum number of rows to return
		$limit_flag = $parameters[6];
		$response=array();
		
		//connect DB
		$this->setDBHandle();
		$dbHandle = $this->db_sums;
		if($type=="Category")
		{
			$queryCmd="select SQL_CALC_FOUND_ROWS tPageKeyCriteriaMapping.flag, (select name from categoryBoardTable where boardId=categoryId) as Category, (select countryTable.name from countryTable where countryTable.countryId = tPageKeyCriteriaMapping.countryId ) as Country,if((cityId=0),'ALL',(select city_name from countryCityTable where countryCityTable.city_id=cityId)) as city ,  (select concat('<a href=\"/getListingDetail/',listing_type_id,'/',listing_type,'\">',listing_title,'</a>') from listings_main where listing_type_id=PageCollegeDb.listing_type_id and listing_type='institute' and status='live') as ListingTitle, StartDate, EndDate from PageCollegeDb,tPageKeyCriteriaMapping where PageCollegeDb.listing_type='institute' EndDate>= ? and EndDate <= ? and Status='live' and tPageKeyCriteriaMapping.keyPageId=KeyId order by tPageKeyCriteriaMapping.flag,Category,Country,city";
			if ($limit_flag == 'sums_mis_report_show') {
				$queryCmd .= "  LIMIT $queryCmd_offset , $queryCmd_rows ";
			}
			error_log($queryCmd);
			$query = $dbHandle->query($queryCmd, array($startDate, $endDate));
			
//			global $homePageMap;
//			$homePageMapFlipped = array_flip($homePageMap);
			$tempArray=array();
			foreach ($query->result_array() as $row)
			{
/*				$queryCmd1="select flag, keyPageId, if((cityId=0),'ALL',(select city_name from countryCityTable where countryCityTable.city_id=cityId)) as city , (select countryTable.name from countryTable where countryTable.countryId = tPageKeyCriteriaMapping.countryId ) as Country, (select name from categoryBoardTable where boardId=categoryId) as Category from tPageKeyCriteriaMapping where keyPageId=".$row['Key_Title'];
			error_log($queryCmd1);
				
				$query1 = $dbHandle->query($queryCmd1);
				foreach ($query1->result_array() as $row1)
				{
					if($row1['flag']=='testprep')
					{
						$row['Key_Title']='testprep';
					}
					else
					{
						$row['Key_Title']=$row1['Country']."|".$row1['city']."|".$row1['Category'];	
					}
				}*/
				array_push ($tempArray,array($row,'struct'));
			}
			$queryCmdz = 'SELECT FOUND_ROWS() as totalRows';
			$queryz = $dbHandle->query($queryCmdz);
			$totalRows = 0;
			foreach ($queryz->result() as $rowz) {
				$totalRows = $rowz->totalRows;
			}
			array_push($response,array(
				array(
					'results'=>array($tempArray,'struct'),
					'totalCount'=>array($totalRows,'string'),
				),'struct')
			);
			
			
		}
		else
		{
			$queryCmd="select SQL_CALC_FOUND_ROWS keyword,(select concat('<a href=\"/getListingDetail/',listing_type_id,'/',listing_type,'\">',listing_title,'</a>') from listings_main where listing_type_id=listingId and listing_type=type) as ListingTitle, count, sponsorType, set_time, unset_time from  tSponsoredListingByKeyword where unset_time> ? and unset_time < ? and isDeleted!=1 order by Keyword,count desc";
			if ($limit_flag == 'sums_mis_report_show') {
				$queryCmd .= "  LIMIT $queryCmd_offset , $queryCmd_rows ";
			}
            error_log($queryCmd);
			$query = $dbHandle->query($queryCmd, array($startDate, $endDate));
			$tempArray=array();
			foreach ($query->result_array() as $row)
			{
				array_push ($tempArray,array($row,'struct'));
			}
			$queryCmd = 'SELECT FOUND_ROWS() as totalRows';
			$query = $dbHandle->query($queryCmd);
			$totalRows = 0;
			foreach ($query->result() as $row) {
				$totalRows = $row->totalRows;
			}
            error_log($totalRows);
			array_push($response,array(
				array(
					'results'=>array($tempArray,'struct'),
					'totalCount'=>array($totalRows,'string'),
				),'struct')
			);
			
		}
		$resp = array($response,'struct');
		return $this->xmlrpc->send_response ($resp);
	}
	/** 
	 * Get Product MIS
	 */
	function getProductMIS($request) {
		$parameters = $request->output_parameters();
		$startDate=$parameters[0];
		$endDate=$parameters[1];
		$clientNameCSV=$parameters[2]; //get client id in csv
		$queryCmd_offset =  $parameters[4]; // offset of the 1st row 2 return
		$queryCmd_rows = $parameters[5]; //maximum number of rows to return
		$limit_flag = $parameters[6];
		$branchIds = $parameters[7]; // get Branch Ids as CSV in ALL -1
		if($branchIds!=-1)
		{
			$branchFilter="and T.SalesBranch=(".$branchIds.") ";
		}
		//connect DB
		$this->setDBHandle(TRUE);
		$dbHandle = $this->db_sums;
		$queryCmd="select  SQL_CALC_FOUND_ROWS ClientName, TransactionId, TransactTime, SalesBy, BranchName, DerivedProductName, Quantity, concat(Discount,' ',CurrencyCode) as Discount, concat(ManagementPrice,' ',CurrencyCode) as ManagementPrice, concat(SuggestedPrice,' ',CurrencyCode) as SuggestedPrice from (select (select concat('<a href=\"mailto:',email,'\">',displayName,'</a>') as displayname from shiksha.tuser U where U.userid=T.ClientUserId) as ClientName ,concat('<a href=\"/sums/MIS/viewTransactionDetails/',T.TransactionId,'\">',T.TransactionId,'</a>') as TransactionId, T.TransactTime, (select concat('<a href=\"mailto:',email,'\">',displayName,'</a>') as displayname from shiksha.tuser U where U.userid=T.SalesBy) as SalesBy, (select group_concat(BranchName) from Sums_Branch_List BL where T.SalesBranch=BL.BranchId) as BranchName, DP.DerivedProductName, QPM.Quantity, QPM.Discount, DPPM.ManagementPrice, DPPM.SuggestedPrice, (select CurrencyCode from Currency C where C.CurrencyId=DPPM.CurrencyId) as CurrencyCode  from Quotation Q, Quotation_Product_Mapping QPM, Derived_Products DP, Derived_Prod_Price_Map DPPM, Transaction T, Transaction_Queue TQ where Q.UIQuotationId=T.UIQuotationId and Q.Status='TRANSACTION' and Q.QuotationId=QPM.QuotationId and DP.DerivedProductId=QPM.DerivedProductId and DPPM.DerivedProductId=DP.DerivedProductId and Q.CurrencyId=DPPM.CurrencyId and TQ.State!='CANCELLED' and TQ.TransactionId=T.TransactionId";
		if ($clientNameCSV != 'ALL') {
			$queryCmd .= " and T.ClientUserId in (".$clientNameCSV.") ";
		} 
		$queryCmd .= " and T.TransactTime > ? and T.TransactTime < ? $branchFilter order by T.ClientUserId, T.TransactionId) as XYZ";
		if ($limit_flag == 'sums_mis_report_show') {
			$queryCmd .= "  LIMIT $queryCmd_offset , $queryCmd_rows ";
		}
		$query = $dbHandle->query($queryCmd, array($startDate, $endDate));
		/*  Add For Pagination   */
		$response=array();
		$tempArray=array();
		foreach ($query->result_array() as $row)
		{
			array_push ($tempArray,array($row,'struct'));
		}
		$queryCmd = 'SELECT FOUND_ROWS() as totalRows';
		$query = $dbHandle->query($queryCmd);
		$totalRows = 0;
		foreach ($query->result() as $row) {
			$totalRows = $row->totalRows;
		}
		array_push($response,array(
			array(
				'results'=>array($tempArray,'struct'),
				'totalCount'=>array($totalRows,'string'),
			),'struct')
		);
		$resp = array($response,'struct');
		return $this->xmlrpc->send_response ($resp);
		/*  Add For Pagination   */
	}
}
?>
