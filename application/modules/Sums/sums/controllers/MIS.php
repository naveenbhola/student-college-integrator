	<?php
	ob_start();
	include('Sums_Common.php');
	/**
	 * Controller for Sums MIS
	 * 
	 */
	class MIS extends Sums_Common {
		var $appId = 1;
		var $mis_report;
		var $mis_report_type;
		var $type=24;
		
		
		
		function init() {
			$this->load->helper(array('form', 'url','date','image'));
			$this->load->library('sums_mis_common');
		}
		
		function collection($type = 24, $mis_report_type = 1 ) {
			echo "This functionality is not in use.";
			exit;
			$this->init();
			$data['sumsUserInfo'] = $this->sumsUserValidation(39,$type);
			$data['prodId'] = $type;
			$data['mis_reprot_type'] = $this->_check_mis_report_type($mis_report_type);
			$objSumsManage = new Sums_mis_common();
			$data['branchList'] = $objSumsManage->getAllBranches(1,$request,$data['sumsUserInfo']['userid']);
			$data['clientList'] = $this->Ajaxclient();
			$this->load->view('sums/MISHome',$data);
		}
		
		function _check_mis_report_type ($mis_report_type) {
			switch ($mis_report_type) {
				case '1':
					$this->mis_report = 'payment';
					break;
				case '2':
					$this->mis_report = 'transaction';
					break;
				case '3':
					$this->mis_report = 'inventory';
					break;
				default:
					$this->mis_report = 'payment';
			}
			return $this->mis_report;
		}
		
		function handleParams () {
			$startOffsetForMis = $this->input->post('startOffsetForMis');
			$countOffsetForMis = $this->input->post('countOffsetForMis');

			$result = isset($startOffsetForMis) ? $startOffsetForMis : 0;
			$num = isset($countOffsetForMis) ? $countOffsetForMis : 10;
			$type = 24;
			$this->init();
			$data['sumsUserInfo'] = $this->sumsUserValidation(39,$type);
			$appID = 1;
			$branchIds = '';	
			if(isset($_POST['postData'])) {
				$postData = json_decode($this->input->post('postData'),true);
			} else { 
				//$postData = $_POST;
				foreach($_POST as $key=>$val) {
						$postData[$key] = $this->input->post($key);
				}
			}
			if (isset($postData['FormType']) && !empty($postData['FormType'])) {
				$report_type = $postData['FormType'];
				$requestArray = array();
				$report_action = $postData['report'];
				/* Three diff. values  sums_mis_report_show,sums_mis_report_mail,sums_mis_report_download
				 */
				if ($report_type == 'payment') {
					if (!empty($postData['trans_start_date'])) {
						$trans_start_date = trim($postData['trans_start_date'])." 00:00:00";
					} else {
						$trans_start_date = date ("Y-m-d H:i:s", mktime(0, 0, 0, 4, 1, date("Y")));
					}
					$requestArray[] = $trans_start_date;
					if (!empty($postData['trans_end_date'])) {
						$trans_end_date = trim($postData['trans_end_date'])." 23:59:59";
					} else {
						$trans_end_date = date ("Y-m-d H:i:s", time());
					}
					$requestArray[] = $trans_end_date;
					
					if (!empty($postData['sums_mis_executive'])) {
						$sums_mis_executive = array_unique($postData['sums_mis_executive']);
					} else {
						$requestArray[] = $this->_getAllExecutive();
					}
					if (in_array('-1',$sums_mis_executive)) { 		if (count($sums_mis_executive) <= 1) {
						$requestArray[] = $this->_getAllExecutive();
					} elseif(count($sums_mis_executive) > 1) { 
						unset($sums_mis_executive[0]);
						$requestArray[] = implode (",",$sums_mis_executive);
					}
					} else {
						$requestArray[] = implode (",",$sums_mis_executive);
					}
					if (!empty($postData['sums_mis_client'])) {
						$sums_mis_client = array_unique($postData['sums_mis_client']);
					} else {
						$requestArray[] = 'ALL';
					}
					if (in_array('-1',$sums_mis_client)) {
						$requestArray[] = 'ALL';
					} else {
						$requestArray[] = implode (",",$sums_mis_client);
					}
					if (!empty($postData['sums_mis_payment_mode'])) {
						if (in_array('-1',$postData['sums_mis_payment_mode'])) {
							// add Empty string as payment mode
							$requestArray[] = "Cash','','Cheque','Demand Draft','Telegraphic Transfer','Credit Card(Offline)','Later";
						} else {
							$sums_mis_payment_mode = $postData['sums_mis_payment_mode'];
							$requestArray[] = implode ('\',\'',$sums_mis_payment_mode );
						}
					}
					if (!empty($postData['sums_mis_payment_currency'])) {
						$sums_mis_payment_currency = $postData['sums_mis_payment_currency'];
						$requestArray[] = $sums_mis_payment_currency;
					}
					if (!empty($postData['sums_mis_payment_status'])) {
						$sums_mis_payment_status = $postData['sums_mis_payment_status'];
						$requestArray[] = implode ('\',\'',$sums_mis_payment_status);
					}
					if (!empty($postData['sums_mis_branch'])) {
						$sums_mis_branch = array_unique($postData['sums_mis_branch']);
					}
					if (in_array('-1',$sums_mis_branch)) {
						$branchIds = '-1';
					} else {
						$branchIds = implode (",",$sums_mis_branch);
					}
					
				} else if ($report_type == 'transaction') {
					if (!empty($postData['trans_start_date'])) {
						$trans_start_date = trim($postData['trans_start_date'])." 00:00:00";
					} else {
						$trans_start_date = date ("Y-m-d H:i:s", mktime(0, 0, 0, 4, 1, date("Y")));
					}
					$requestArray[] = $trans_start_date;
					if (!empty($postData['trans_end_date'])) {
						$trans_end_date = trim($postData['trans_end_date'])." 23:59:59";
					} else {
						$trans_end_date = date ("Y-m-d H:i:s", time());
					}
					$requestArray[] = $trans_end_date;
					if (!empty($postData['sums_mis_executive'])) {
						$sums_mis_executive = array_unique($postData['sums_mis_executive']);
					} else {
						$requestArray[] = $this->_getAllExecutive();
					}
					if (in_array('-1',$sums_mis_executive)) { 		if (count($sums_mis_executive) <= 1) {
						$requestArray[] = $this->_getAllExecutive();
					} elseif(count($sums_mis_executive) > 1) { 
						unset($sums_mis_executive[0]);
						$requestArray[] = implode (",",$sums_mis_executive);
					}
					} else {
						$requestArray[] = implode (",",$sums_mis_executive);
					}
					if (!empty($postData['sums_mis_client'])) {
						$sums_mis_client = array_unique($postData['sums_mis_client']);
					} else {
						$requestArray[] = 'ALL';
					}
					if (in_array('-1',$sums_mis_client)) {
						$requestArray[] = 'ALL';
					} else {
						$requestArray[] = implode (",",$sums_mis_client);
					}
					if (!empty($postData['sums_mis_sell_type'])) {
						$sums_mis_sell_type = $postData['sums_mis_sell_type'];
					}
					$requestArray[] = $sums_mis_sell_type;
					if (!empty($postData['sums_mis_transaction_state'])) {
						$sums_mis_transaction_state = $postData['sums_mis_transaction_state'];
						$requestArray[] = $sums_mis_transaction_state;
					}
					if (!empty($postData['sums_mis_branch'])) {
						$sums_mis_branch = array_unique($postData['sums_mis_branch']);
					}
					if (in_array('-1',$sums_mis_branch)) {
						$branchIds = '-1';
					} else {
						$branchIds = implode (",",$sums_mis_branch);
					}
					
					
				} else if ($report_type == 'inventory') {
					if ( $postData['shikshainventory'] == 'csm' ) { 
						// getInventryMIS
						if (!empty($postData['trans_start_date'])) {
							$trans_start_date = trim($postData['trans_start_date'])." 00:00:00";
						} else {
							$trans_start_date = date ("Y-m-d H:i:s", mktime(0, 0, 0, 4, 1, date("Y")));
						}
						$requestArray[] = $trans_start_date;
						if (!empty($postData['trans_end_date'])) {
							$trans_end_date = trim($postData['trans_end_date'])." 23:59:59";
						} else {
							$trans_end_date = date ("Y-m-d H:i:s", time());
						}
						$requestArray[] = $trans_end_date;
						if (!empty($postData['sums_mis_client2'])) {
							$sums_mis_client = array_unique($postData['sums_mis_client2']);
						} else {
							$requestArray[] = 'ALL';
						}
						if (in_array('-1',$sums_mis_client)) {
							$requestArray[] = 'ALL';
						} else {
							$requestArray[] = implode (",",$sums_mis_client);
						}
						if (!empty($postData['sums_client_subscription_status'])) {
							$requestArray[] = $postData['sums_client_subscription_status'];
						}
						if (!empty($postData['sums_mis_branch2'])) {
							$sums_mis_branch = array_unique($postData['sums_mis_branch2']);
						}
						if (in_array('-1',$sums_mis_branch)) {
							$branchIds = '-1';
						} else {
							$branchIds = implode (",",$sums_mis_branch);
						}
					} else if ($postData['shikshainventory'] == 'bcpm') { 
						// getProductMIS
						if (!empty($postData['trans_start_date'])) {
							$trans_start_date = trim($postData['trans_start_date'])." 00:00:00";
						} else {
							$trans_start_date = date ("Y-m-d H:i:s", mktime(0, 0, 0, 4, 1, date("Y")));
						}
						$requestArray[] = $trans_start_date;
						if (!empty($postData['trans_end_date'])) {
							$trans_end_date = trim($postData['trans_end_date'])." 23:59:59";
						} else {
							$trans_end_date = date ("Y-m-d H:i:s", time());
						}
						$requestArray[] = $trans_end_date;
						if (!empty($postData['sums_mis_client1'])) {
							$sums_mis_client = array_unique($postData['sums_mis_client1']);
						} else {
							$requestArray[] = 'ALL';
						}
						if (in_array('-1',$sums_mis_client)) {
							$requestArray[] = 'ALL';
						} else {
							$requestArray[] = implode (",",$sums_mis_client);
						}
						if (!empty($postData['sums_mis_branch1'])) {
							$sums_mis_branch = array_unique($postData['sums_mis_branch1']);
						}
						if (in_array('-1',$sums_mis_branch)) {
							$branchIds = '-1';
						} else {
							$branchIds = implode (",",$sums_mis_branch);
						}
					} else if ($postData['shikshainventory'] == 'scpim') {
						// getShikshaInventryMIS
						if (!empty($postData['trans_start_date'])) {
							$trans_start_date = trim($postData['trans_start_date'])." 00:00:00";
						} else {
							$trans_start_date = date ("Y-m-d H:i:s", mktime(0, 0, 0, 4, 1, date("Y")));
						}
						$requestArray[] = $trans_start_date;
						if (!empty($postData['trans_end_date'])) {
							$trans_end_date = trim($postData['trans_end_date'])." 23:59:59";
						} else {
							$trans_end_date = date ("Y-m-d H:i:s", time());
						}
						$requestArray[] = $trans_end_date;
						
						$requestArray[] = "Category";
					} else if ($postData['shikshainventory'] == 'sspm') {
						// getShikshaInventryMIS
						if (!empty($postData['trans_start_date'])) {
							$trans_start_date = trim($postData['trans_start_date'])." 00:00:00";
						} else {
							$trans_start_date = date ("Y-m-d H:i:s", mktime(0, 0, 0, 4, 1, date("Y")));
						}
						$requestArray[] = $trans_start_date;
						if (!empty($postData['trans_end_date'])) {
							$trans_end_date = trim($postData['trans_end_date'])." 23:59:59";
						} else {
							$trans_end_date = date ("Y-m-d H:i:s", time());
						}
						$requestArray[] = $trans_end_date;
						
						$requestArray[] = "Search";
						
					} 
				}
				if (!empty($postData['countOffsetForMis'])) {
					$updateoffsetvalue = $postData['countOffsetForMis'];
				} else {
					$updateoffsetvalue = 10;		
				}
			} else {
				error_log_shiksha('Form Type can not be empty','sums');
			}
			// print_r($requestArray);
			// Request data from server
			$this->load->library('Sums_mis_query_get_data');
			$QueryResult = new Sums_mis_query_get_data;
			$userId = $data['sumsUserInfo']['userid'];
			try {
				if ($report_type == 'inventory' ) {
					// $postData['shikshainventory'] will give follwoing values:-
					// csm mean Client Subscription MIS
					// bcpm mean By Clients Product MIS
					// scpim mean Shiksha Category Product Inventory MIS
					// sspm mean Shiksha Search Product MIS
					$misReportData = $QueryResult->getMISReportData(1,$requestArray,$report_type,$postData['shikshainventory'],$userId,$result,$num,$report_action,$branchIds);
				} else {
					$misReportData = $QueryResult->getMISReportData(1,$requestArray,$report_type,NULL,$userId,$result,$num,$report_action,$branchIds);
				}
				// print_r($misReportData);
				$data['sumsUserInfo'] = $this->sumsUserValidation(39,$type);
				$data['ReportResult'] = $misReportData;
				$data['report_action'] = $report_action;
				$data['prodId'] = $type;
				$data['mis_reprot_type'] = $report_type;
				$data['shikshainventory'] = $postData['shikshainventory'];
				$data['requestArray'] = json_encode($postData);
				$data['updateoffsetvalue'] = $updateoffsetvalue;
				if($report_action=='sums_mis_report_download')
				{ 
					$leads=$misReportData[0]['results'];
					header("Content-type: text/x-csv");
					$filename =preg_replace('/[^A-Za-z0-9]/', '',$report_type);
					header("Content-Disposition: attachment; filename=".$filename.".csv");
					$csv = '';
					foreach ($leads as $lead){
						foreach ($lead as $key=>$val){
							$csv .= '"'.$key.'",'; 
						}
						$csv .= "\n"; 
						break;
					}
					foreach ($leads as $lead){
						foreach ($lead as $key=>$val){
							$csv .= '"'.strip_tags($val).'",'; 
						}
						$csv .= "\n"; 
					}
					echo $csv;
				}
				else
				{ 
					//print_r($data);
					$this->load->view('sums/Display_MIS_Report',$data);
				}
			} catch (Exception $e) {
				throw $e;
				error_log_shiksha('Error occoured'.$e,'sums');
			}
			
		}
		
		function Ajaxbranch ($id) {
			$this->init();
			$managerList = array();
			$objSumsManage = new Sums_mis_common();
			$userEmployeeIdsMap = array();
			if ($id != '-1') {
				$managerList =  $objSumsManage->getAllExecutive(1,$id,5);
				if (count($managerList) > 0 ) {
					$this->load->library('register_client');
					$regObj = new Register_client();
					$userids='';
					foreach($managerList as $manager) {
						if($userids=='') { 
							$userids=$manager['userId'];
						} else {
							$userids=$userids.",".$manager['userId'];
						}
						$userEmployeeIdMap[$manager['userId']] = $manager['EmployeeId'];
					}
				}
			} else {
				$objSumsManage = new Sums_mis_common();
				$data['sumsUserInfo'] = $this->sumsUserValidation();
				$branchList = $objSumsManage->getAllBranches(1,$request,$data['sumsUserInfo']['userid']);
				if (count($branchList) > 0 ) {
					$managerList = array();
					for($i=0;$i<count($branchList);$i++) {
						$id = $branchList[$i]['BranchId'];
						$managerList[] =  $objSumsManage->getAllExecutive(1,$id,5);
					}
					$this->load->library('register_client');
					$regObj = new Register_client();
					$userids='';
					foreach($managerList as $managerl) {
						foreach($managerl as $manager) { 
							if($userids=='') { 
								$userids=$manager['userId'];
							} else {
								$userids=$userids.",".$manager['userId'];
							}
							$userEmployeeIdMap[$manager['userId']] = $manager['EmployeeId'];
						}
					}
				}	
				
			}
			if ($userids != '' ) {
				$nameList = $regObj->getDetailsforUsers($appId,$userids);
				$response['managerList']=array();
				for($i=0;$i<count($nameList);$i++) {
					array_push($response['managerList'],array('userId'=>$userEmployeeIdMap[$nameList[$i]['userid']],'ManagerName'=>$nameList[$i]['displayname'].' ('.$this->_getBranchName($managerList,$nameList[$i]['userid']).')'));
				}
				$managerList = $response['managerList'];	
			}		
			echo json_encode($managerList);
		}
		
		function Ajaxclient () {
			$this->init();
			$objSumsManage = new Sums_mis_common();
			$managerList =  $objSumsManage->getAllclients(1,$id);			$response['managerList']=array();
			$newArray=array();	
			for($i=0;$i<count($managerList);$i++) {
				array_push($newArray,array('userId'=>$managerList[$i]['userId'],'ManagerName'=>$managerList[$i]['displayName']));
			}
			return $newArray;
			
		}
		
		function viewTransactionDetails($transactionId,$prodId=24)
		{
			$this->init();
			$data['sumsUserInfo'] = $this->sumsUserValidation();
			$this->load->library(array('sums_mis_query_get_data'));
			$objMISClient = new Sums_mis_query_get_data();


			$paymentAndTransactionDetails = $objMISClient->getClientTransactionDetails($this->appId,$transactionId);

			if(isset($paymentAndTransactionDetails[0]['transactionDetails'][0]) && is_array($paymentAndTransactionDetails[0]['transactionDetails'][0]))
			{
				$clientId = $paymentAndTransactionDetails[0]['transactionDetails'][0]['ClientUserId'];
			}
			$data['clientDetails'] = $paymentAndTransactionDetails[0]['clientDetails'][0];
			$data['transactionDetails'] = $paymentAndTransactionDetails[0]['transactionDetails'];
			$data['paymentDetails'] = $paymentAndTransactionDetails[0]['paymentDetails'];
			$data['prodId'] = $prodId;
			$data['transactionId'] = $transactionId;			

			$this->load->view('sums/mis_transaction_view',$data);
		}
		function viewPaymentHistory($PaymentId,$PartNumber)
			{
			$this->init();
			$data = array();
			$data['sumsUserInfo'] = $this->sumsUserValidation();
			$data['PaymentId'] = $PaymentId;
			$data['PartNumber'] = $PartNumber;
			$this->load->library(array('sums_mis_query_get_data'));
			$objMISClient = new Sums_mis_query_get_data();
			$PaymentHistory = $objMISClient->getPartPaymentHistory(1,$PaymentId,$PartNumber);
			$data['PaymentHistory'] = $PaymentHistory;
			$this->load->view('sums/payment_history',$data);
			}
		function _getAllExecutive() {
			$data['sumsUserInfo'] = $this->sumsUserValidation();
			$objSumsManage = new Sums_mis_common();
			$branchList = $objSumsManage->getAllBranches(1,$request,$data['sumsUserInfo']['userid']);
			$managerList = array();
			for($i=0;$i<count($branchList);$i++) {
				$id = $branchList[$i]['BranchId'];
				$managerList[] =  $objSumsManage->getAllExecutive(1,$id,5);
			}
			$this->load->library('register_client');
			$regObj = new Register_client();
			$userids='';
			foreach($managerList as $managerl) {
				foreach($managerl as $manager)
				{ 
					if($userids=='') { 
						$userids=$manager['EmployeeId'];
					} else {
						$userids=$userids.",".$manager['EmployeeId'];
					}
				}
			}
			$nameList = $regObj->getDetailsforUsers($appId,$userids);
			$result = array();
			for($i=0;$i<count($nameList);$i++) {
				$result[] = $nameList[$i]['userid'] ;
			}
			return implode (",",$result);
			
			
		}
		
		function _getBranchName ($array,$id) {
			$ArrayCount = count ($array);
			if ($ArrayCount < 0 ) {
				error_log_shiksha('getAllExecutive API Return Empty Array','SUMS');
			} else {			
				foreach ($array as $list) {
					if (isset($list['userId']) && !empty ($list['userId'])) {
						if ( $list['userId'] == $id ) {
							return $list['BranchName'];
						} 
					} else {
						foreach ($list as $listval) {
							if ( $listval['userId'] == $id ) {
								return $listval['BranchName'];
							}
						}	
						
					}	
				}
			}
		}

		/**
		 *
		 *  
		 * 
		 * 
		 * INVOICE REPORT 
		 * REPORT FOR GENERATING PDF
		 */
		function getDataInvoiceGeneration($transactionId,$invoicetype,$paymentamount = '0',$Part_Service_tax_Percentage = '0',$TDSAmount = '0')
		{
		$appId = 1;
		$this->init();
		$data = array();
		$data['sumsUserInfo'] = $this->sumsUserValidation();	
		$this->load->library(array('sums_mis_query_get_data'));
		$objMISClient = new Sums_mis_query_get_data();
		$paymentAndTransactionDetails['data'] = $objMISClient->getTransactionAndPaymentDetails($appId,$transactionId);				
		$paymentAndTransaction['data1']=$objMISClient->getdata($appId,$transactionId);
		
		$PartNumber = --$Part_Number;
		
				
		for($i =0;$i < count($paymentAndTransactionDetails[data][0][transactionDetails]);$i++)
			{
			$transactiondetailsarray[] = $paymentAndTransactionDetails[data][0][transactionDetails][$i];
			}
		
		$AmountexclTax = 0;
		
		for($i =0;$i < count($transactiondetailsarray);$i++)
		{
		$Quantity = $transactiondetailsarray[$i][Quantity];
		$Managementprice = $transactiondetailsarray[$i][ManagementPrice];
		$pricePerProduct = $Quantity * $Managementprice;
		$Discount = $transactiondetailsarray[$i][Discount];
		$FinalsumPerProduct = $pricePerProduct - $Discount;
		$AmountexclTax += $FinalsumPerProduct;
		} 		
		
		
		$usercountry = 	$paymentAndTransactionDetails['data'][0]['clientDetails'][0]['usercountry'];		
		$address = $paymentAndTransactionDetails['data'][0][clientDetails][0][Address];
		$transactionId = $paymentAndTransactionDetails['data'][0][clientDetails][0][Transaction_Id];
		$TDS_Amount = $paymentAndTransactionDetails['data'][0][paymentDetails][0][TDS_Amount];
		$Cheque_No = $paymentAndTransactionDetails['data'][0][paymentDetails][0][Cheque_No];
		$totalAmount = $paymentAndTransactionDetails['data'][0][transactionDetails][0][FinalSalesAmount];
		$mode=	$paymentAndTransactionDetails['data'][0][paymentDetails][0][Payment_Mode];
		$country = $paymentAndTransactionDetails['data'][0][clientDetails][0][Country];
		$clientcity= $paymentAndTransactionDetails['data'][0][clientDetails][0][City];
		$ServiceTax = $paymentAndTransactionDetails['data'][0][transactionDetails][0][ServiceTax];
		$salesperson = 	$paymentAndTransactionDetails['data'][0][transactionDetails][0][saleByUserDisplayName];
		
		$SubscriptionStartDate =$paymentAndTransaction['data1'][0][1][0][SubscriptionStartDate];
		$SubscriptionEndDate = $paymentAndTransaction['data1'][0][1][0][SubscriptionEndDate];
		$Branch = $paymentAndTransaction['data1'][0][2][0][BranchName];
		for ($i = 0; $i <= 50; $i++)
		{
		$productname[] = $paymentAndTransactionDetails['data'][0][transactionDetails][$i][DerivedProductName];
		if ($productname[$i] == '')
			{
			break;	
			}
		}
		
		$SubscriptionstDate =  substr($SubscriptionStartDate,0,-8);
		$Cheque_Date = $paymentAndTransactionDetails['data'][0][paymentDetails][0][Cheque_Receiving_Date];
		$ChequeDate = substr($Cheque_Date,0, -8);			
		$currency = $paymentAndTransactionDetails['data'][0][transactionDetails][0][CurrencyName];

		$SubscriptionendDate =  substr($SubscriptionEndDate,0,-8);
		$servicetaxpercentage = $paymentAndTransactionDetails['data'][0][transactionDetails][0][servicetaxpercentage];
		$pincode= $paymentAndTransactionDetails['data'][0][clientDetails][0][Pincode];
		$displayName= $paymentAndTransactionDetails['data'][0][clientDetails][0][primaryName];
		
		
		if((($usercountry != 'India') && ($usercountry) != '2') && $currency == 'USD')
		{
			$Part_Service_tax_Percentage = 0;
			
		}
		
		$invoiceno =  $transactionId.'-1';
		
				
														
			if($servicetaxpercentage == 0 && $invoicetype == 'proforma')
			{
				$SERVICE_TAX10 = 0;
				$ECess = 0;
				$SHECess = 0;
				$FinalAmount = $AmountexclTax + $SERVICE_TAX10 + $ECess + $SHECess;
			}
			
			elseif($servicetaxpercentage == 10.3 && $invoicetype == 'proforma')  
			{
				$SERVICE_TAX10 = ($AmountexclTax*10)/100;
				$ECess = ($SERVICE_TAX10*2)/100;
				$SHECess = ($SERVICE_TAX10*1)/100;
				$FinalAmount = $AmountexclTax + $SERVICE_TAX10 + $ECess + $SHECess;
			}
			
			elseif($servicetaxpercentage == 12.36 && $invoicetype == 'proforma')  
			{
				$SERVICE_TAX10 = ($AmountexclTax* 12)/100;
				$ECess = ($SERVICE_TAX10*2)/100;
				$SHECess = ($SERVICE_TAX10*1)/100;
				
				$FinalAmount = $AmountexclTax + $SERVICE_TAX10 + $ECess + $SHECess;
				$FinalAmount = floor($FinalAmount);
			}															
			elseif($invoicetype == 'sales' && $Part_Service_tax_Percentage == 10.3)
			{
				$paymentamountonsalesinvoice = $paymentamount;
				$TDS_Amount = $TDSAmount;
				$amountonsalesinvoice = $TDS_Amount + $paymentamountonsalesinvoice;
				$AmountexclTax = $amountonsalesinvoice *(.9066);
				$SERVICE_TAX10 = ($AmountexclTax)/10;
				$ECess = ($AmountexclTax*.2)/100;
				$SHECess = ($AmountexclTax*.1)/100;
				$FinalAmount = $amountonsalesinvoice;
			}
			elseif($invoicetype == 'sales' && $Part_Service_tax_Percentage == 0)
			{
				$paymentamountonsalesinvoice = $paymentamount;
				$TDS_Amount = $TDSAmount;
				$amountonsalesinvoice = $TDS_Amount + $paymentamountonsalesinvoice;
				$AmountexclTax = $amountonsalesinvoice;
				$SERVICE_TAX10 = 0;
				$ECess = 0;
				$SHECess =0;
				$FinalAmount = $amountonsalesinvoice;
			}
			
			elseif($invoicetype == 'sales' && $Part_Service_tax_Percentage == 12.36)
			{
				$paymentamountonsalesinvoice = $paymentamount;
				$TDS_Amount = $TDSAmount;
				$amountonsalesinvoice = $TDS_Amount + $paymentamountonsalesinvoice;
				
				$AmountexclTax = $amountonsalesinvoice *(.8899);				
				$SERVICE_TAX10 = $amountonsalesinvoice *(.1068);
				$ECess = $amountonsalesinvoice *(.002136);
				$SHECess = $amountonsalesinvoice *(.001068);
				
				$FinalAmount = $amountonsalesinvoice;				
			}
				
		$amounttaken = number_format($FinalAmount,2);
		$arr = explode(".",$amounttaken);   
		$ruppee= $arr[0];
		$paise= $arr[1];
		$this->load->library(array('NumberToWords_library'));
		$ruppee = floor( $FinalAmount ); 
		$obj = new NumberToWords_library();
		$str = $obj->numberToWords($ruppee);
		
			if($paise == 0)
			{
				$string ="Zero";
			}
			else
			{
				$string = $obj->numberToWords($paise);
			}
		
		$SERVICE_TAX10 = number_format($SERVICE_TAX10,2);
		$ECess=number_format($ECess,2);
		$SHECess=number_format($SHECess,2);
		$FinalAmount = number_format($FinalAmount,2);
		$AmountexclTax =  number_format($AmountexclTax,2);
		$finalSERVICETAX = $SERVICE_TAX10 ;

		
		
		
		$this->load->helper('path');
			$this->load->library('cezpdf');

			//Adding ShikshaLogo-------------------------------------------------------------------------------------------------------------------------

			$this->cezpdf->ezImage(SHIKSHA_HOME."/public/images/nshik_ShikshaLogo.jpeg",'','150','','left','');

			$this->cezpdf->ezText('Page No.  1', 10, array('justification' => 'right'));



			if($invoicetype == 'sales')
			{
				$this->cezpdf->ezText('Sales Invoice', 16, array('justification' => 'centre'));
			}
			elseif($invoicetype == 'proforma')
			{
				$this->cezpdf->ezText('Proforma Invoice', 16, array('justification' => 'centre'));


			}

			$this->cezpdf->ezSetDy(-10);

			//table 1-------------------------------------------------------------------------------------------------------------------------

			$db_data1[] = array('customer' => $displayName,         		 'order' =>'Order No      : '.$transactionId,      				'branch Address' => 'Shiksha.com, a division of Info Edge (India) Ltd');
			$db_data1[] = array('customer' => $address,   				 'order' => 'Invoice Date : '.$ChequeDate,                      		'branch Address' => $Branch);
			$db_data1[] = array('customer' => $clientcity.'-'.$pincode,              'order' => 'Invoice No    : '.$invoiceno,				        'branch Address' => '');
			$db_data1[] = array('customer' => $country,                              'order' => '',                                   				'branch Address' => 'Salesperson   '.$salesperson);

			$col_name = array(
					'customer' => 'Bill to Customer',
					'order' => 'Order Details',
					'branch Address' => 'Branch Address'
					);

			$this->cezpdf->ezTable($db_data1, $col_name, '', array('width'=>550));

			$this->cezpdf->ezSetDy(-20);

			//table 2--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

			for ($j = 0; $j < $i; $j++)		
			{	$db_data2[] = array('1' => $j+1,         		 '2' =>$productname[$j],                                   '3' => $SubscriptionstDate,		'4'     => $SubscriptionendDate	);
			}	
			$db_data2[] = array('1' => '',   				'2' => '',                             		    	'3' => '',		'4'	=> ''	);
			$db_data2[] = array('1' => '',                  	  	'2' => '',           					'3' => '',		'4'	=>''		);

			$col_name2 = array(
					'1' => 'Sr. No',
					'2' => 'Product Name',
					'3' => 'Subscription Start Date',
					'4' => 'Subscription End Date',
					);

			$this->cezpdf->ezTable($db_data2, $col_name2, 'Products', array('width'=>550));

			//table 3-------------------------------------------------------------------------------------------------------------------------------------------------
			$this->cezpdf->ezSetDy(-10);   

			
			if($servicetaxpercentage == 0 && $invoicetype == 'proforma')
			{
			$db_data3[] = array('1' => 'Amount Excl. Tax',         		 	'2' =>$AmountexclTax,                                   );
			$db_data3[] = array('1' => 'ServiceTax @ 0%',   			'2' => $SERVICE_TAX10,                      		);
			$db_data3[] = array('1' => 'Edu Cess(2% of ST)  ',                    	'2' => $ECess,				                );
			$db_data3[] = array('1' => 'SHE Cess(1% of ST)',               		'2' => $SHECess,                                   	);
			$db_data3[] = array('1' => '',               				'2' => '',                                   		);
			$db_data3[] = array('1' => 'Total '  ,               			'2' => $FinalAmount,                                 	);
			$db_data3[] = array('1' => 'Payable in',               			'2' => $currency,                                   	);
										
			}
			elseif($servicetaxpercentage == 10.3 && $invoicetype == 'proforma') 
			{
			$db_data3[] = array('1' => 'Amount Excl. Tax',         		 	'2' =>$AmountexclTax,                                   );
			$db_data3[] = array('1' => 'ServiceTax @ 10%',   			'2' => $SERVICE_TAX10,                      		);
			$db_data3[] = array('1' => 'Edu Cess(2% of ST)  ',                    	'2' => $ECess,				                );
			$db_data3[] = array('1' => 'SHE Cess(1% of ST)',               		'2' => $SHECess,                                   	);
			$db_data3[] = array('1' => '',               				'2' => '',                                   		);
			$db_data3[] = array('1' => 'Total '  ,               			'2' => $FinalAmount,                                 	);
			$db_data3[] = array('1' => 'Payable in',               			'2' => $currency,                                   	);
													
				
			}
			
			elseif($servicetaxpercentage == 12.36 && $invoicetype == 'proforma')
			{
			$db_data3[] = array('1' => 'Amount Excl. Tax',         		 	'2' =>$AmountexclTax,                                   );
			$db_data3[] = array('1' => 'ServiceTax @ 12%',   			'2' => $SERVICE_TAX10,                      		);
			$db_data3[] = array('1' => 'Edu Cess(2% of ST)  ',                    	'2' => $ECess,				                );
			$db_data3[] = array('1' => 'SHE Cess(1% of ST)',               		'2' => $SHECess,                                   	);
			$db_data3[] = array('1' => '',               				'2' => '',                                   		);
			$db_data3[] = array('1' => 'Total '  ,               			'2' => $FinalAmount,                                 	);
			$db_data3[] = array('1' => 'Payable in',               			'2' => $currency,                                   	);
				
			}
		
			elseif($Part_Service_tax_Percentage == 12.36 && $invoicetype == 'sales')
			{
			$db_data3[] = array('1' => 'Amount Excl. Tax',         		 	'2' =>$AmountexclTax,                                   );
			$db_data3[] = array('1' => 'ServiceTax @ 12%',   			'2' => $SERVICE_TAX10,                      		);
			$db_data3[] = array('1' => 'Edu Cess(2% of ST)  ',                    	'2' => $ECess,				                );
			$db_data3[] = array('1' => 'SHE Cess(1% of ST)',               		'2' => $SHECess,                                   	);
			$db_data3[] = array('1' => '',               				'2' => '',                                   		);
			$db_data3[] = array('1' => 'Total '  ,               			'2' => $FinalAmount,                                 	);
			$db_data3[] = array('1' => 'Payable in',               			'2' => $currency,                                   	);
				
			}
			
			elseif($Part_Service_tax_Percentage == 10.3 && $invoicetype == 'sales')
			{
			$db_data3[] = array('1' => 'Amount Excl. Tax',         		 	'2' =>$AmountexclTax,                                   );
			$db_data3[] = array('1' => 'ServiceTax @ 10%',   			'2' => $SERVICE_TAX10,                      		);
			$db_data3[] = array('1' => 'Edu Cess(2% of ST)  ',                    	'2' => $ECess,				                );
			$db_data3[] = array('1' => 'SHE Cess(1% of ST)',               		'2' => $SHECess,                                   	);
			$db_data3[] = array('1' => '',               				'2' => '',                                   		);
			$db_data3[] = array('1' => 'Total '  ,               			'2' => $FinalAmount,                                 	);
			$db_data3[] = array('1' => 'Payable in',               			'2' => $currency,                                   	);
				
			}
			
			elseif($Part_Service_tax_Percentage == 0 && $invoicetype == 'sales')
			{
			$db_data3[] = array('1' => 'Amount Excl. Tax',         		 	'2' =>$AmountexclTax,                                   );
			$db_data3[] = array('1' => 'ServiceTax @ 0%',   			'2' => $SERVICE_TAX10,                      		);
			$db_data3[] = array('1' => 'Edu Cess(2% of ST)  ',                    	'2' => $ECess,				                );
			$db_data3[] = array('1' => 'SHE Cess(1% of ST)',               		'2' => $SHECess,                                   	);
			$db_data3[] = array('1' => '',               				'2' => '',                                   		);
			$db_data3[] = array('1' => 'Total '  ,               			'2' => $FinalAmount,                                 	);
			$db_data3[] = array('1' => 'Payable in',               			'2' => $currency,                                   	);
				
			}
			
			
			
			$col_name3 = array(
					'1' => '',
					'2' => '',	   );

			$this->cezpdf->ezTable($db_data3,$col_name3, 'Amount Details',array('xPos'=>'right'
						,'xOrientation'=>'left','showHeadings'=>0,'width'=>200,'cols'=>array('num'=>array('justification'=>'right')
							,'name'=>array('width'=>100))
						));


			$this->cezpdf->ezSetDy(-10);
			if($currency == 'INR')
				$this->cezpdf->ezText('Total in words        ****'.$str.' Rupees and '.$string.' Paisa Only', 12, array('justification' => 'left'));
			elseif($currency == 'USD')
				$this->cezpdf->ezText('Total in words        ****'.$str.' Dollars and '.$string.' Cents Only', 12, array('justification' => 'left'));

			$this->cezpdf->ezSetDy(-20);

			$this->cezpdf->ezText('Authorised Signatory                  ', 12, array('justification' => 'right'));
			$this->cezpdf->ezSetDy(-20);
			$this->cezpdf->ezText('      For Shiksha.com                     ', 12, array('justification' => 'right'));
			$this->cezpdf->ezText('A divisions of Info Edge (India) Limited', 12, array('justification' => 'right'));
			$this->cezpdf->ezSetDy(-10);

			//table 4-----------------------------------------------------------------------------------------------------------

			$db_data[] = array('a' => '',          				'corporate Office' =>'A-88,',                                  'regd. Office' => 'GF- 12 A');
			$db_data[] = array('a' => '',   				'corporate Office' => 'Sector 2,',                             'regd. Office' => '95,Meghdoot, Nehru Place');
			$db_data[] = array('a' => 'E-mail :',                  		'corporate Office' => 'NOIDA - 201301, Uttar Pradesh',         'regd. Office' => 'New Delhi - 110019');
			$db_data[] = array('a' => 'sales@shiksha.com',          	'corporate Office' => 'India',                                 'regd. Office' => 'India');

			$col_names = array(
					'a' => 'Customer Support',
					'corporate Office' => 'Corporate Office',
					'regd. Office' => 'Regd. Office'
					);

			$this->cezpdf->ezTable($db_data, $col_names, '', array('width'=>550));

			//Adding content-----------------------------------------------------------------------------------------------------------

			$this->cezpdf->ezSetDy(-10);
			$this->cezpdf->ezText(' Service Tax No :- AAACI1838DST001', 10, array('justification' => 'left'));
			$this->cezpdf->ezText(' Service - Online data access & Retrieval', 10, array('justification' => 'left'));
			$this->cezpdf->ezText(' PAN No - AAACI1838D', 10, array('justification' => 'left'));
			$this->cezpdf->ezSetDy(-10);
			if($invoicetype == 'proforma')
			{
				$this->cezpdf->ezText('*  The tax paid commercial invoice will be generated seperately after payment.', 10, array('justification' => 'left'));
			}
			elseif($invoicetype == 'sales')
			{
				$this->cezpdf->ezText('*  This invoice is recognised subject to realization of Payment.', 10, array('justification' => 'left'));


			}
			
			$this->cezpdf->ezText('*  The Service Tax rate has been raised to 12.36% with effect from 1st April 2012.', 10, array('justification' => 'left'));
			
			$this->cezpdf->ezText('*  All cheques / DD should be made in favour of "Shiksha.com".', 10, array('justification' => 'left'));
			$this->cezpdf->ezText('*  Refer Terms and Conditions attached in the next page.', 10, array('justification' => 'left'));
			$this->cezpdf->ezText('*  All disputes subject to Delhi Juridiction only.', 10, array('justification' => 'left'));
			$this->cezpdf->ezSetDy(-20);
			$this->cezpdf->ezImage(SHIKSHA_HOME."/public/images/nshik_ShikshaLogo.jpeg",'','180','','left','');

			$this->cezpdf->ezText('Page No.  2', 10, array('justification' => 'right'));

			$this->cezpdf->ezText('Terms and Conditions for Invoice', 14, array('justification' => 'center'));
			$this->cezpdf->ezSetDy(-20);

			$this->cezpdf->ezText('1.  The complete Terms & Conditions (TnC) in relation to the product/services  offered & accepted by the customer, and in relation to the general TnC on portal usage are available on the website (portal) for which services have been subscribed. The complete terms and conditions are given at', 12, array('justification' => 'left'));
			$this->cezpdf->ezText('<c:uline>https://www.shiksha.com/shikshaHelp/ShikshaHelp/termCondition</c:uline>', 12, array('justification' => 'left'));
			$this->cezpdf->ezSetDy(-10);

			$this->cezpdf->ezText('2.  The payment released against the invoice deems confirmation & acceptance to all the terms & conditions, as amended from time to time.', 12, array('justification' => 'left'));
			$this->cezpdf->ezSetDy(-10);

			$this->cezpdf->ezText("3.  The usage of the portal and its associated services constitute a binding agreement with Info Edge (India) limited and customer's agreement to abide by the same.", 12, array('justification' => 'left'));
			$this->cezpdf->ezSetDy(-10);

			$this->cezpdf->ezText('4.  The content on the portal is the property of Info Edge (India) Limited or its content suppliers or customers.  Info Edge (India) Ltd. further reserves its right to post the data on the portal or on such other affiliated sites and publications as InfoEdge (India) Ltd. may deem fit and proper at no extra cost to the subscriber / user.', 12, array('justification' => 'left'));
			$this->cezpdf->ezSetDy(-10);

			$this->cezpdf->ezText('5.  Info Edge reserves its right to reject any insertion or information/data provided by the subscriber without assigning any reason either before uploading or after uploading the vacancy details, but in such an eventuality, any amount so paid for, shall be refunded to the subscriber on a pro-rata basis at the sole discretion of Info Edge.', 12, array('justification' => 'left'));		
			$this->cezpdf->ezSetDy(-10);

			$this->cezpdf->ezText('6.  Users/ subscribers shall on demand certify that the classifieds advertised on the website are genuine and pertain to existing vacancies/jobs/properties etc.', 12, array('justification' => 'left'));
			$this->cezpdf->ezSetDy(-10);

			$this->cezpdf->ezText('7.  Subscribers shall be deemed to have given an undertaking to Info Edge (India) Limited that no fee/ service charges shall be charged from jobseekers / users by them.', 12, array('justification' => 'left'));
			$this->cezpdf->ezSetDy(-10);

			$this->cezpdf->ezText('8.  Engaging in any conduct prohibited by law or usage of services in any manner so as to impair the interests and functioning of Info Edge (India) Limited or its Users may result in withdrawal of services.', 12, array('justification' => 'left'));
			$this->cezpdf->ezSetDy(-10);

			$this->cezpdf->ezText('9.  If your Cheque is returned dishonoured or in case of a charge back on an online transaction (including credit card payment), services will be immediately deactivated. In case of cheques dishonoured, the reactivation would be done only on payment of an additional Rs 500 per instance of dishonour.', 12, array('justification' => 'left'));
			$this->cezpdf->ezSetDy(-10);

			$this->cezpdf->ezText('10. Info Edge (India) Limited does not guarantee (i) the quality or quantity of response to any of its services (ii) server uptime or applications working properly. All is on a best effort basis and liability is limited to refund of amount paid on pro rated basis only. Info Edge (India) Limited undertakes no liability for free services.', 12, array('justification' => 'left'));
			$this->cezpdf->ezSetDy(-10);

			$this->cezpdf->ezText('11. This service is neither resaleable nor transferable by the Subscriber to any other person, corporate body, firm or individual.', 12, array('justification' => 'left'));
			$this->cezpdf->ezSetDy(-10);

			$this->cezpdf->ezText('12. Refund, if any, shall be admissible at the sole discretion of Info Edge (India) Limited.', 12, array('justification' => 'left'));
			$this->cezpdf->ezSetDy(-10);

			$this->cezpdf->ezText('13. Laws of India as applicable shall govern. Courts in Delhi will have exclusive Jurisdiction in case of any dispute.', 12, array('justification' => 'left'));

			//---------------------------code to generate pdf file --------------------------------------------------------------------------------------


			/*$directory = './pdf/';
			  set_realpath($directory);*/

			$file = $directory.date('dmYhis').'.pdf';
			$filename = $transactionId.'-'.date('dmYhis').'.pdf';
			$pdfcode = $this->cezpdf->ezOutput();

			/*$fp = fopen($file,'wb');
			  fwrite($fp,$pdfcode);
			  fclose($fp);
			  chmod($file,0777);*/
			$mime = 'application/pdf';

			if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE"))
			{
				header('Content-Type: "'.$mime.'"');
				header('Content-Disposition: attachment; filename="'.$filename.'"');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header("Content-Transfer-Encoding: binary");
				header('Pragma: public');
				header("Content-Length: ".strlen($pdfcode));
			}
			else
			{
				header('Content-Type: "'.$mime.'"');
				header('Content-Disposition: attachment; filename="'.$filename.'"');
				header("Content-Transfer-Encoding: binary");
				header('Expires: 0');
				header('Pragma: no-cache');
				header("Content-Length: ".strlen($pdfcode));
			}
			echo ($pdfcode);		
		  }
			
		/**
		 * INVOICE REPORT 
		 * REPORT FOR GENERATING PDF
		 * @parm => $transactionId is payment transation id in SUMS DB
		 * @param=> $Part_Number is the payment part number as payment can be done in parts
		 */
		function getInstantDataInvoiceGeneration($transactionId, $Part_Number = 1) {
			$appId = 1;
			$this->init ();
			$data = array ();
			$data ['sumsUserInfo'] = $this->sumsUserValidation ();
			$this->load->library ('sums_mis_query_get_data');
			$objMISClient = new Sums_mis_query_get_data ();
			$paymentAndTransactionDetails ['data'] = $objMISClient->getTransactionAndPaymentDetails ( $appId, $transactionId, $Part_Number );
			if($paymentAndTransactionDetails ['data'] == 0){
				echo "<script>alert('Invalid Data');</script>";
				return;
			}
			$paymentAndTransaction ['data1'] = $objMISClient->getdata ( $appId, $transactionId );
			
			for($i = 0; $i < count ( $paymentAndTransactionDetails [data] [0] [transactionDetails] ); $i ++) {
				$transactiondetailsarray [] = $paymentAndTransactionDetails [data] [0] [transactionDetails] [$i];
			}
			
			$AmountexclTax = 0;
			
			$usercountry = $paymentAndTransactionDetails['data'][0]['clientDetails'][0]['usercountry'];
			$address = $paymentAndTransactionDetails['data'][0][clientDetails][0][Address];
			$transactionId = $paymentAndTransactionDetails['data'][0][clientDetails][0][Transaction_Id];
			$totalAmount = $paymentAndTransactionDetails['data'][0][transactionDetails][0][FinalSalesAmount];
			$mode = $paymentAndTransactionDetails['data'][0][paymentDetails][0][Payment_Mode];
			$country = $paymentAndTransactionDetails['data'][0][clientDetails][0][Country];
			$clientcity = $paymentAndTransactionDetails['data'][0][clientDetails][0][City];
			$salesperson = $paymentAndTransactionDetails['data'][0][transactionDetails][0][saleByUserDisplayName];
			$SubscriptionStartDate = $paymentAndTransaction['data1'][0][1][0][SubscriptionStartDate];
			$SubscriptionEndDate = $paymentAndTransaction['data1'][0][1][0][SubscriptionEndDate];
			$Branch = $paymentAndTransaction['data1'][0][2][0][BranchName];
			
			for($i = 0; $i <= 50; $i ++) {
				$productname [] = $paymentAndTransactionDetails ['data'] [0] [transactionDetails] [$i] [DerivedProductName];
				if ($productname [$i] == '') {
					break;
				}
			}
			
			$SubscriptionstDate = substr ( $SubscriptionStartDate, 0, - 8 );
			$Cheque_Date = $paymentAndTransactionDetails ['data'] [0] [paymentDetails] [0] [Cheque_Receiving_Date];
			$ChequeDate = substr ( $Cheque_Date, 0, - 8 );
			$dateArray = array();
			$dateArray = explode('-', $ChequeDate);
			$dataAccTax = array();
			$currency = $paymentAndTransactionDetails ['data'] [0] [transactionDetails] [0] [CurrencyName];
			
			$SubscriptionendDate = substr ( $SubscriptionEndDate, 0, - 8 );
			$servicetaxpercentage = $paymentAndTransactionDetails ['data'] [0] [transactionDetails] [0] [servicetaxpercentage];
			$pincode = $paymentAndTransactionDetails ['data'] [0] [clientDetails] [0] [Pincode];
			$displayName = $paymentAndTransactionDetails ['data'] [0] [clientDetails] [0] [primaryName];
			$amount = $paymentAndTransactionDetails ['data'] [0] ['paymentDetails'] [0] ['Amount_Received'];
			
			if ((($usercountry != 'India') && ($usercountry) != '2') && $currency == 'USD') {
				$Part_Service_tax_Percentage = 0;
			}
			
			$invoiceno = $transactionId . '-' . $Part_Number;
			
			$dataAccTax = $this->TaxCalculation($dateArray, $amount);
			$AmountexclTax = $dataAccTax['AmountexclTax'];
			$SERVICE_TAX10 = $dataAccTax['SERVICE_TAX'];
			$ECess = $dataAccTax['ECess'];
			$SHECess = $dataAccTax['SHECess'];
			$FinalAmount = $dataAccTax['FinalAmount'];
			
			$amounttaken = number_format ( $FinalAmount, 2 );
			$arr = explode ( ".", $amounttaken );
			$ruppee = $arr [0];
			$paise = $arr [1];
			$this->load->library('NumberToWords_library');
			$ruppee = floor ( $FinalAmount );
			$obj = new NumberToWords_library ();
			
			$str = $obj->numberToWords ( $amount );
			if ($paise == 0) {
				$string = "Zero";
			} else {
				$string = $obj->numberToWords ( $paise );
			}
			
			$SERVICE_TAX10 = number_format ( $SERVICE_TAX10, 2 );
			$ECess = number_format ( $ECess, 2 );
			$SHECess = number_format ( $SHECess, 2 );
			$FinalAmount = number_format ( $FinalAmount, 2 );
			$AmountexclTax = number_format ( $AmountexclTax, 2 );
			$finalSERVICETAX = $SERVICE_TAX10;
			
			$this->load->helper ( 'path' );
			$this->load->library ( 'cezpdf' );
			
			// Adding ShikshaLogo-------------------------------------------------------------------------------------------------------------------------
			$this->cezpdf->ezImage ( SHIKSHA_HOME . "/public/images/nshik_ShikshaLogo.jpeg", '', '150', '', 'left', '' );
			$this->cezpdf->ezText ( 'Page No.  1', 10, array (
					'justification' => 'right' 
			) );
			
			if ($invoicetype == 'sales') {
				$this->cezpdf->ezText ( 'Sales Invoice', 16, array (
						'justification' => 'centre' 
				) );
			} elseif ($invoicetype == 'proforma') {
				$this->cezpdf->ezText ( 'Proforma Invoice', 16, array (
						'justification' => 'centre' 
				) );
			}
			
			$this->cezpdf->ezSetDy ( - 10 );
			
			// table 1-------------------------------------------------------------------------------------------------------------------------
			
			$db_data1 [] = array (
					'customer' => $displayName,
					'order' => 'Order No      : ' . $transactionId,
					'branch Address' => 'Shiksha.com, a division of Info Edge (India) Ltd' 
			);
			$db_data1 [] = array (
					'customer' => $address,
					'order' => 'Invoice Date : ' . $ChequeDate,
					'branch Address' => $Branch 
			);
			$db_data1 [] = array (
					'customer' => $clientcity . '-' . $pincode,
					'order' => 'Invoice No    : ' . $invoiceno,
					'branch Address' => '' 
			);
			$db_data1 [] = array (
					'customer' => $country,
					'order' => '',
					'branch Address' => 'Salesperson   ' . $salesperson 
			);
			
			$col_name = array (
					'customer' => 'Bill to Customer',
					'order' => 'Order Details',
					'branch Address' => 'Branch Address' 
			);
			
			$this->cezpdf->ezTable ( $db_data1, $col_name, '', array (
					'width' => 550 
			) );
			
			$this->cezpdf->ezSetDy ( - 20 );
			
			// table 2--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
			
			for($j = 0; $j < $i; $j ++) {
				$productname[$j] = str_replace("<br/>"," ",$productname[$j]);
				$productname[$j] = str_replace("<br/"," ",$productname[$j]);
				$db_data2 [] = array (
						'1' => $j + 1,
						'2' => $productname [$j],
						'3' => $SubscriptionstDate,
						'4' => $SubscriptionendDate 
				);
			}
			$db_data2 [] = array (
					'1' => '',
					'2' => '',
					'3' => '',
					'4' => '' 
			);
			$db_data2 [] = array (
					'1' => '',
					'2' => '',
					'3' => '',
					'4' => '' 
			);
			
			$col_name2 = array (
					'1' => 'Sr. No',
					'2' => 'Product Name',
					'3' => 'Subscription Start Date',
					'4' => 'Subscription End Date' 
			);
			
			$this->cezpdf->ezTable ( $db_data2, $col_name2, 'Products', array (
					'width' => 550 
			) );
			
			// table 3-------------------------------------------------------------------------------------------------------------------------------------------------
			$this->cezpdf->ezSetDy ( - 10 );
			
			if($dateArray[0] >= 2012 && $dateArray[1] > 3){
				$db_data3 [] = array (
						'1' => 'Amount Excl. Tax',
						'2' => $AmountexclTax 
				);
				$db_data3 [] = array (
						'1' => 'ServiceTax @ 12%',
						'2' => $SERVICE_TAX10 
				);
				$db_data3 [] = array (
						'1' => 'Edu Cess(2% of ST)  ',
						'2' => $ECess 
				);
				$db_data3 [] = array (
						'1' => 'SHE Cess(1% of ST)',
						'2' => $SHECess 
				);
			}else{
				$db_data3 [] = array (
						'1' => 'Amount Excl. Tax',
						'2' => $AmountexclTax 
				);
				$db_data3 [] = array (
						'1' => 'ServiceTax @ 10%',
						'2' => $SERVICE_TAX10 
				);
				$db_data3 [] = array (
						'1' => 'Edu Cess(2% of ST)  ',
						'2' => $ECess 
				);
				$db_data3 [] = array (
						'1' => 'SHE Cess(1% of ST)',
						'2' => $SHECess 
				);
				
			}
			$db_data3 [] = array (
					'1' => '',
					'2' => '' 
			);
			$db_data3 [] = array (
					'1' => 'Total ',
					'2' => $FinalAmount 
			);
			$db_data3 [] = array (
					'1' => 'Payable in',
					'2' => $currency 
			);
			
			$col_name3 = array (
					'1' => '',
					'2' => '' 
			);
			$this->cezpdf->ezTable ( $db_data3, $col_name3, 'Amount Details', array (
					'xPos' => 'right',
					'xOrientation' => 'left',
					'showHeadings' => 0,
					'width' => 200,
					'cols' => array (
							'num' => array (
									'justification' => 'right' 
							),
							'name' => array (
									'width' => 100 
							) 
					) 
			) );
			
			$this->cezpdf->ezSetDy ( - 10 );
			if ($currency == 'INR')
				$this->cezpdf->ezText ( 'Total in words        ****' . $str . ' Rupees and ' . $string . ' Paisa Only', 12, array (
						'justification' => 'left' 
				) );
			elseif ($currency == 'USD')
				$this->cezpdf->ezText ( 'Total in words        ****' . $str . ' Dollars and ' . $string . ' Cents Only', 12, array (
						'justification' => 'left' 
				) );
			
			$this->cezpdf->ezSetDy ( - 20 );
			
			$this->cezpdf->ezText ( 'Authorised Signatory                  ', 12, array (
					'justification' => 'right' 
			) );
			$this->cezpdf->ezSetDy ( - 20 );
			$this->cezpdf->ezText ( '      For Shiksha.com                     ', 12, array (
					'justification' => 'right' 
			) );
			$this->cezpdf->ezText ( 'A divisions of Info Edge (India) Limited', 12, array (
					'justification' => 'right' 
			) );
			$this->cezpdf->ezSetDy ( - 10 );
			
			// table 4-----------------------------------------------------------------------------------------------------------
			
			$db_data [] = array (
					'a' => '',
					'corporate Office' => 'A-88,',
					'regd. Office' => 'GF- 12 A' 
			);
			$db_data [] = array (
					'a' => '',
					'corporate Office' => 'Sector 2,',
					'regd. Office' => '95,Meghdoot, Nehru Place' 
			);
			$db_data [] = array (
					'a' => 'E-mail :',
					'corporate Office' => 'NOIDA - 201301, Uttar Pradesh',
					'regd. Office' => 'New Delhi - 110019' 
			);
			$db_data [] = array (
					'a' => 'sales@shiksha.com',
					'corporate Office' => 'India',
					'regd. Office' => 'India' 
			);
			
			$col_names = array (
					'a' => 'Customer Support',
					'corporate Office' => 'Corporate Office',
					'regd. Office' => 'Regd. Office' 
			);
			
			$this->cezpdf->ezTable ( $db_data, $col_names, '', array (
					'width' => 550 
			) );
			
			// Adding content-----------------------------------------------------------------------------------------------------------
			
			$this->cezpdf->ezSetDy ( - 10 );
			$this->cezpdf->ezText ( ' Service Tax No :- AAACI1838DST001', 10, array (
					'justification' => 'left' 
			) );
			$this->cezpdf->ezText ( ' Service - Online data access & Retrieval', 10, array (
					'justification' => 'left' 
			) );
			$this->cezpdf->ezText ( ' PAN No - AAACI1838D', 10, array (
					'justification' => 'left' 
			) );
			$this->cezpdf->ezSetDy ( - 10 );
			if ($invoicetype == 'proforma') {
				$this->cezpdf->ezText ( '*  The tax paid commercial invoice will be generated seperately after payment.', 10, array (
						'justification' => 'left' 
				) );
			} elseif ($invoicetype == 'sales') {
				$this->cezpdf->ezText ( '*  This invoice is recognised subject to realization of Payment.', 10, array (
						'justification' => 'left' 
				) );
			}
			
			$this->cezpdf->ezText ( '*  The Service Tax rate has been raised to 12.36% with effect from 1st April 2012.', 10, array (
					'justification' => 'left' 
			) );
			
			$this->cezpdf->ezText ( '*  All cheques / DD should be made in favour of "Shiksha.com".', 10, array (
					'justification' => 'left' 
			) );
			$this->cezpdf->ezText ( '*  Refer Terms and Conditions attached in the next page.', 10, array (
					'justification' => 'left' 
			) );
			$this->cezpdf->ezText ( '*  All disputes subject to Delhi Juridiction only.', 10, array (
					'justification' => 'left' 
			) );
			$this->cezpdf->ezSetDy ( - 20 );
			$this->cezpdf->ezImage ( SHIKSHA_HOME . "/public/images/nshik_ShikshaLogo.jpeg", '', '180', '', 'left', '' );
			
			$this->cezpdf->ezText ( 'Page No.  2', 10, array (
					'justification' => 'right' 
			) );
			
			$this->cezpdf->ezText ( 'Terms and Conditions for Invoice', 14, array (
					'justification' => 'center' 
			) );
			$this->cezpdf->ezSetDy ( - 20 );
			
			$this->cezpdf->ezText ( '1.  The complete Terms & Conditions (TnC) in relation to the product/services  offered & accepted by the customer, and in relation to the general TnC on portal usage are available on the website (portal) for which services have been subscribed. The complete terms and conditions are given at', 12, array (
					'justification' => 'left' 
			) );
			$this->cezpdf->ezText ( '<c:uline>https://www.shiksha.com/shikshaHelp/ShikshaHelp/termCondition</c:uline>', 12, array (
					'justification' => 'left' 
			) );
			$this->cezpdf->ezSetDy ( - 10 );
			
			$this->cezpdf->ezText ( '2.  The payment released against the invoice deems confirmation & acceptance to all the terms & conditions, as amended from time to time.', 12, array (
					'justification' => 'left' 
			) );
			$this->cezpdf->ezSetDy ( - 10 );
			
			$this->cezpdf->ezText ( "3.  The usage of the portal and its associated services constitute a binding agreement with Info Edge (India) limited and customer's agreement to abide by the same.", 12, array (
					'justification' => 'left' 
			) );
			$this->cezpdf->ezSetDy ( - 10 );
			
			$this->cezpdf->ezText ( '4.  The content on the portal is the property of Info Edge (India) Limited or its content suppliers or customers.  Info Edge (India) Ltd. further reserves its right to post the data on the portal or on such other affiliated sites and publications as InfoEdge (India) Ltd. may deem fit and proper at no extra cost to the subscriber / user.', 12, array (
					'justification' => 'left' 
			) );
			$this->cezpdf->ezSetDy ( - 10 );
			
			$this->cezpdf->ezText ( '5.  Info Edge reserves its right to reject any insertion or information/data provided by the subscriber without assigning any reason either before uploading or after uploading the vacancy details, but in such an eventuality, any amount so paid for, shall be refunded to the subscriber on a pro-rata basis at the sole discretion of Info Edge.', 12, array (
					'justification' => 'left' 
			) );
			$this->cezpdf->ezSetDy ( - 10 );
			
			$this->cezpdf->ezText ( '6.  Users/ subscribers shall on demand certify that the classifieds advertised on the website are genuine and pertain to existing vacancies/jobs/properties etc.', 12, array (
					'justification' => 'left' 
			) );
			$this->cezpdf->ezSetDy ( - 10 );
			
			$this->cezpdf->ezText ( '7.  Subscribers shall be deemed to have given an undertaking to Info Edge (India) Limited that no fee/ service charges shall be charged from jobseekers / users by them.', 12, array (
					'justification' => 'left' 
			) );
			$this->cezpdf->ezSetDy ( - 10 );
			
			$this->cezpdf->ezText ( '8.  Engaging in any conduct prohibited by law or usage of services in any manner so as to impair the interests and functioning of Info Edge (India) Limited or its Users may result in withdrawal of services.', 12, array (
					'justification' => 'left' 
			) );
			$this->cezpdf->ezSetDy ( - 10 );
			
			$this->cezpdf->ezText ( '9.  If your Cheque is returned dishonoured or in case of a charge back on an online transaction (including credit card payment), services will be immediately deactivated. In case of cheques dishonoured, the reactivation would be done only on payment of an additional Rs 500 per instance of dishonour.', 12, array (
					'justification' => 'left' 
			) );
			$this->cezpdf->ezSetDy ( - 10 );
			
			$this->cezpdf->ezText ( '10. Info Edge (India) Limited does not guarantee (i) the quality or quantity of response to any of its services (ii) server uptime or applications working properly. All is on a best effort basis and liability is limited to refund of amount paid on pro rated basis only. Info Edge (India) Limited undertakes no liability for free services.', 12, array (
					'justification' => 'left' 
			) );
			$this->cezpdf->ezSetDy ( - 10 );
			
			$this->cezpdf->ezText ( '11. This service is neither resaleable nor transferable by the Subscriber to any other person, corporate body, firm or individual.', 12, array (
					'justification' => 'left' 
			) );
			$this->cezpdf->ezSetDy ( - 10 );
			
			$this->cezpdf->ezText ( '12. Refund, if any, shall be admissible at the sole discretion of Info Edge (India) Limited.', 12, array (
					'justification' => 'left' 
			) );
			$this->cezpdf->ezSetDy ( - 10 );
			
			$this->cezpdf->ezText ( '13. Laws of India as applicable shall govern. Courts in Delhi will have exclusive Jurisdiction in case of any dispute.', 12, array (
					'justification' => 'left' 
			) );
			
			// ---------------------------code to generate pdf file --------------------------------------------------------------------------------------
			
			/*
			 * $directory = './pdf/';
			 * set_realpath($directory);
			 */
			$file = $directory . date ( 'dmYhis' ) . '.pdf';
			$filename = $transactionId . '-' . date ( 'dmYhis' ) . '.pdf';
			$pdfcode = $this->cezpdf->ezOutput ();
			
			/*
			 * $fp = fopen($file,'wb');
			 * fwrite($fp,$pdfcode);
			 * fclose($fp);
			 * chmod($file,0777);
			 */
			$mime = 'application/pdf';
			
			if (strstr ( $_SERVER ['HTTP_USER_AGENT'], "MSIE" )) {
				header ( 'Content-Type: "' . $mime . '"' );
				header ( 'Content-Disposition: attachment; filename="' . $filename . '"' );
				header ( 'Expires: 0' );
				header ( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
				header ( "Content-Transfer-Encoding: binary" );
				header ( 'Pragma: public' );
				header ( "Content-Length: " . strlen ( $pdfcode ) );
			} else {
				header ( 'Content-Type: "' . $mime . '"' );
				header ( 'Content-Disposition: attachment; filename="' . $filename . '"' );
				header ( "Content-Transfer-Encoding: binary" );
				header ( 'Expires: 0' );
				header ( 'Pragma: no-cache' );
				header ( "Content-Length: " . strlen ( $pdfcode ) );
			}
			echo ($pdfcode);
		}
	
		/**
		 *  
		 * 
		 * 
		 * RECEIPT GENERATION 
		 * REPORT FOR GENERATING PDF
		 */

		function getDataReceiptGeneration($details)
		{
			$appId = 1;
			$this->init();
			$data = array();
			$data['sumsUserInfo'] = $this->sumsUserValidation();
			$this->load->library(array('sums_mis_query_get_data'));
			$objMISClient = new Sums_mis_query_get_data();
			$Arr = split("_",$details);	    
			$transactionId = $Arr[0];
			$partPaymentId  = $Arr[1];


			$paymentAndTransactionDetails['data'] = $objMISClient->getTransactionAndPaymentDetails($appId,$transactionId);
			$paymentAndTransaction['data1']=$objMISClient->getdata($appId,$transactionId);


			for($a=0; $a < count($paymentAndTransactionDetails['data'][0]['paymentDetails']); $a++)
			{

				if($partPaymentId == $paymentAndTransactionDetails['data'][0]['paymentDetails'][$a]['Part_Number'])
				{
					$part = $a;
					break;	
				}

			}

			$Address = $paymentAndTransactionDetails['data'][0]['clientDetails'][0]['Address'];
			$Transaction_Id= $paymentAndTransactionDetails['data'][0]['clientDetails'][0]['Transaction_Id'];
			$Cheque_Date = $paymentAndTransactionDetails['data'][0]['paymentDetails'][$part]['Cheque_Receiving_Date'];
			$TDS_Amount = $paymentAndTransactionDetails['data'][0]['paymentDetails'][$part]['TDS_Amount'];
			$Cheque_No = $paymentAndTransactionDetails['data'][0]['paymentDetails'][$part]['Cheque_No'];
			$country = $paymentAndTransactionDetails['data'][0]['clientDetails'][0]['Country'];
			$amount= $paymentAndTransactionDetails['data'][0]['paymentDetails'][$part]['Amount_Received'];
			$pincode= $paymentAndTransactionDetails['data'][0]['clientDetails'][0]['Pincode'];
			$clientcity= $paymentAndTransactionDetails['data'][0]['clientDetails'][0]['City'];
			$Branch = $paymentAndTransaction['data1'][0][2][0]['BranchName'];

			$Payment_Mode = $paymentAndTransactionDetails['data'][0]['paymentDetails'][0]['Payment_Mode'];
			$rest = substr($Cheque_Date, 0, -8);
			$productname = $paymentAndTransactionDetails['data'][0]['transactionDetails'][0]['DerivedProductName'];
			$ChequeDate = substr($Cheque_Date,0, -8);			
			$currency = $paymentAndTransactionDetails['data'][0]['transactionDetails'][0]['CurrencyName'];
			$displayName= $paymentAndTransactionDetails['data'][0]['clientDetails'][0]['primaryName'];
			$salesperson = 	$paymentAndTransactionDetails['data'][0]['transactionDetails'][0]['saleByUserDisplayName'];
			$totalamount = $amount + $TDS_Amount;
			$amount = number_format($amount, 2, '.', '');
			$TDS_Amount = number_format($TDS_Amount, 2, '.', '');
			$totalamount = number_format($totalamount, 2, '.', '');


			$this->load->helper('path');
			$this->load->library('cezpdf');

			$this->cezpdf->ezImage(SHIKSHA_HOME."/public/images/nshik_ShikshaLogo.jpeg",'','150','','left','');

			$this->cezpdf->ezText('Page No.  1', 10, array('justification' => 'right'));

			$this->cezpdf->ezText('Sales Receipt', 16, array('justification' => 'centre'));

			$this->cezpdf->ezSetDy(-10);

			//table 1----------------------------------------------------------------------------------------------------------------------

			$db_data1[] = array('customer' => $displayName,         		 	'order' =>'Receipt Date  : '.$rest,                                    	'branch Address' => 'Shiksha.com, a division of Info Edge (India) Ltd');
			$db_data1[] = array('customer' => $Address,   					'order' => 'Transaction Id : '.$Transaction_Id,                      		'branch Address' => $Branch);
			$db_data1[] = array('customer' => $clientcity.'-'.$pincode,                     'order' => 'Order Date     : '.$rest,				           'branch Address' => '');
			$db_data1[] = array('customer' => $country,              			'order' => '',                                   			'branch Address' => 'Salesperson  '.$salesperson);

			$col_name = array(
					'customer' => 'Bill to Customer',
					'order' => 'Receipt',
					'branch Address' => 'Branch Address'
					);

			$this->cezpdf->ezTable($db_data1, $col_name, '', array('width'=>550));    


			//table 2----------------------------------------------------------------------------------------------------------------------------------

			$this->cezpdf->ezSetDy(-20);

			$db_data2[] = array('1' => '1',    '2' => $Cheque_No,          '3' =>$ChequeDate,       '4' => $currency   , '5' => $Payment_Mode,    '6' => $amount,       '7' => $TDS_Amount,         '8' => $totalamount);
			$db_data2[] = array('1' => '',          '2' =>'',       '3' => ''   , '4' => '',    '5' => '',       '6' => '',         '7' => '',  '8' => '');
			$db_data2[] = array('1' => '',          '2' =>'',       '3' => ''   , '4' => '',    '5' => '',       '6' => '',        '7' => '',  '8' => '');


			$col_name2 = array(
					'1' => 'Sr. No ',
					'2' => 'Cheque No',
					'3' => 'Cheque/DD/Payment Date',
					'4' => 'Currency ',
					'5' => 'Payment Mode',
					'6' => 'Cheque Amount',
					'7' => 'TDS Amount ',
					'8' => 'Total Amount',	    
					);


			$this->cezpdf->ezTable($db_data2, $col_name2, 'Transaction Details', array('width'=>550));   


			//Displaying Data--------------------------------------------------------------------------------------------------------------------------------

			$this->cezpdf->ezSetDy(-280);       
			$this->cezpdf->ezText('      For Shiksha.com                    ', 12, array('justification' => 'right'));
			$this->cezpdf->ezText('A divisions of Info Edge (India) Limited', 12, array('justification' => 'right'));

			$db_data[] = array('a' => '',          				'corporate Office' =>'A-88,',                                 'regd. Office' => 'GF- 12 A');
			$db_data[] = array('a' => '',   				'corporate Office' => 'Sector 2,',                            'regd. Office' => '95,Meghdoot, Nehru Place');
			$db_data[] = array('a' => 'E-mail :',                        	'corporate Office' => 'NOIDA - 201301, Uttar Pradesh',        'regd. Office' => 'New Delhi - 110019');
			$db_data[] = array('a' => 'sales@shiksha.com',               	'corporate Office' => 'India',                                'regd. Office' => 'India');


			$col_names = array(
					'a' => 'Customer Support',
					'corporate Office' => 'Corporate Office',
					'regd. Office' => 'Regd. Office'
					);

			$this->cezpdf->ezTable($db_data, $col_names, ' ', array('width'=>550));

			//table 3--------------------------------------------------------------------------------------------------------------------------------------

			$this->cezpdf->ezSetDy(-10);
			$this->cezpdf->ezText('Service Tax No :- AAACI1838DST001', 10, array('justification' => 'left'));
			$this->cezpdf->ezText('Service - Online data access & Retrieval', 10, array('justification' => 'left'));
			$this->cezpdf->ezText('PAN No - AAACI1838D', 10, array('justification' => 'left'));


			//---------------------------code to generate pdf file --------------------------------------------------------------------------------------

			/*$directory = './pdf/';
			  set_realpath($directory);*/

			$file = $directory.date('dmYhis').'.pdf';
			$filename = $transactionId.'-'.date('dmYhis').'.pdf';
			$pdfcode = $this->cezpdf->ezOutput();

			/*$fp = fopen($file,'wb');
			  fwrite($fp,$pdfcode);
			  fclose($fp);
			  chmod($file,0777);*/

			$mime = 'application/pdf';
			if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE"))
			{
				header('Content-Type: "'.$mime.'"');
				header('Content-Disposition: attachment; filename="'.$filename.'"');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header("Content-Transfer-Encoding: binary");
				header('Pragma: public');
				header("Content-Length: ".strlen($pdfcode));
			}
			else
			{
				header('Content-Type: "'.$mime.'"');
				header('Content-Disposition: attachment; filename="'.$filename.'"');
				header("Content-Transfer-Encoding: binary");
				header('Expires: 0');
				header('Pragma: no-cache');
				header("Content-Length: ".strlen($pdfcode));
			}
			echo ($pdfcode);
		}
		
		/*
		 *Function to caculate Tax Amount
		 * @param => dateArray hold, 0=>year, 1=>month
		 * 	  => amount fold raw amount	
		 */
		
		function TaxCalculation($dateArray, $amount){
			
			/*
			 * conditional Check, to make tax amount according to time period
			 */
			
			if($dateArray[0] >= 2012 && $dateArray[1] > 3){
				$completeAmount['AmountexclTax'] = $amount / 1.1236;
				$AmountexclTax = $completeAmount['AmountexclTax'];
				$completeAmount['SERVICE_TAX'] = ($AmountexclTax * 12) / 100;
				$completeAmount['ECess'] = ($AmountexclTax * 12 * 2) / 10000;
				$completeAmount['SHECess'] = ($AmountexclTax * 12) / 10000;
				$completeAmount['FinalAmount'] = $amount;
			}else{
				$completeAmount['AmountexclTax'] = $amount / 1.103;
				$AmountexclTax = $completeAmount['AmountexclTax'];
				$completeAmount['SERVICE_TAX'] = ($AmountexclTax * 10) / 100;
				$completeAmount['ECess'] = ($AmountexclTax * 10 * 2) / 10000;
				$completeAmount['SHECess'] = ($AmountexclTax * 10) / 10000;
				$completeAmount['FinalAmount'] = $amount;
			}
			
			return $completeAmount;
		
		}	
	}
	ob_end_flush();
	?>
