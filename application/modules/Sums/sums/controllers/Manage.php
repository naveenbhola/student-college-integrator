<?php

include('Sums_Common.php');
/**
 * Controller for Sums Basic Functions
 * 
 */
class Manage extends Sums_Common
{
	var $appId = 1;
	function init()
		{
		$this->load->helper(array('form', 'url','date','image'));
		$this->load->library('sums_manage_client');
		}
	
	function login($sendUrl)
		{
		$this->init();
		$sendUrlArr = array('sendUrl' => $sendUrl);
		$this->load->view('sums/login',$sendUrlArr);
		}
	
	function profile($prodId=16)
	{
		$data['sumsUserInfo'] = $this->sumsUserValidation(1,'16');
		$this->load->library(array('category_list_client','listing_client'));
		$cat_client = new Category_list_client();
		$categoryList = $cat_client->getCategoryTree($appId,'','national');
		foreach($categoryList as $temp)
		{
			$categoryForLeftPanel[$temp['categoryID']] =array($temp['categoryName'],$temp['parentId']);
		}
		$data['completeCategoryTreeIndia'] = json_encode($categoryForLeftPanel);

		$categoryList = $cat_client->getCategoryTree($appId,'','studyabroad');
		$categoryForLeftPanel = array();
		foreach($categoryList as $temp)
		{
			$categoryForLeftPanel[$temp['categoryID']] =array($temp['categoryName'],$temp['parentId']);
		}


		$data['completeCategoryTreeAbroad'] = json_encode($categoryForLeftPanel);
		$manObj = new Sums_Manage_client();	
		$data['quoteUsers'] = $manObj->getSumsUsers($this->appId);		
		$ListingClientObj = new Listing_client();
		$data['countryList'] = $ListingClientObj->getCountries($appId);
		$data['prodId'] = $prodId; //16 is for crm

		$this->load->view('sums/profile',$data);
	}


	
	function addProfile($prodId=16)
		{
		$this->init();
		$data['sumsUserInfo'] = $this->sumsUserValidation();
		
		$resolution = $this->input->post('resolutionreg');
		$sourceurl = $this->input->post('refererreg');
		$sourcename = 'SUMS_ENTERPRISE_REGISTRATION';

		$data['sourceurl'] = $sourceurl;
		$data['sourcename'] = $sourcename;
		$data['resolution'] = $resolution;
		$data['appId'] = $this->appId;
		$data['usergroup'] = "enterprise";
		$data['email'] = trim($this->input->post('email'));
		$data['displayname'] = htmlentities(addslashes(trim($this->input->post('displayname'))));
		$data['password'] = addslashes($this->input->post('passwordr'));
		$data['confirmpassword'] = addslashes($this->input->post('confirmpassword'));
		$data['ePassword'] = sha256($data['password']);
		$data['busiCollegeName'] = $this->input->post('busiCollegeName');
		$data['busiType'] = $this->input->post('busiType');
		if($busiType == "Other")
		{
			$busiType = $this->input->post('otherBusiType');
		}
		
		$data['contactName'] = htmlentities(addslashes(trim($this->input->post('contactName'))));
		$data['firstname'] = $data['contactName'];
		$data['contactAddress'] = $this->input->post('contact_address');
		$data['pincode'] = trim($this->input->post('pincode'));
		$data['mobile'] = trim($this->input->post('mobile'));
		$data['executiveName'] = $this->input->post('executiveName');
		
		$country = trim($this->input->post('countries'));
		
		$city = trim($this->input->post('cities'));
		if($city == "-1") //Case of Other city
		{
			$cityArray = array();
			$cityArray['country_id'] = trim($this->input->post('countries'));
			$cityArray['city_name'] = htmlentities(addslashes(trim($this->input->post('otherCity'))));
			$this->load->library('listing_client');
			$ListingClientObj = new Listing_client();
			$city = $ListingClientObj->insertCity($appId,$cityArray);
		}
		$data['city'] = $city;
		$this->load->library('register_client');
		$regObj = new Register_client();
		$data['bypassmobilecheck'] = 'true';
		$addResult = $regObj->adduser_new($data);
		$data['categories'] = implode(',',$this->input->post('c_categories',true));
		
		if ($addResult['status']>0)
		{
			$data['userid'] = $addResult['status'];
			$data['sumsUserId'] = $data['sumsUserInfo']['userid'];
			$entObj = new Enterprise_client();
			$result = $entObj->addEnterpriseUser($data);
		}
		$userDetails = $regObj->userdetail($this->appId,$data['userid']);
		
		$data['userDetails'] = $userDetails[0];
		$data['prodId'] = $prodId;	
		$this->load->view('sums/profileSuccess',$data);
		}
	
	function searchUser($prodId=16)
		{
		$this->init();
		$data['sumsUserInfo'] = $this->sumsUserValidation(2,'16');
		$data['prodId'] = $prodId;
		$this->load->view('sums/userSelect',$data);
		
		}
	
	function getUsersForQuotation($prodId=16)
		{
		error_log("RECEIVED POST DATA: ".print_r($_POST,true));
		$this->init();
		$data['sumsUserInfo'] = $this->sumsUserValidation();
		$request['email'] = $this->input->post('email',true);
		$request['displayname'] = $this->input->post('displayname',true);
		$request['collegeName'] = $this->input->post('collegeName',true);
		$request['contactName'] = $this->input->post('contactName',true);
		$request['contactNumber'] = $this->input->post('contactNumber',true);
		$request['clientId'] = $this->input->post('clientId',true);
		$objSumsManage = new Sums_Manage_client();
		$response['users'] =  $objSumsManage->getUserForQuotation($this->appId,$request);
		
		$response['forListingPost'] = $this->input->post('forListingPost',true);
		$response['prodId'] = $prodId;
		$this->load->view('sums/usersForQuotation',$response);
		}
	
	
	
	function getProducts($prodId=16)
		{
		error_log("posted data for Get Products: ".print_r($_POST,true));
		$this->init();
		$this->load->library(array('register_client','sums_product_client'));
		$data['sumsUserInfo'] = $this->sumsUserValidation();
		$regObj = new Register_client();
		$ud = $regObj->userdetail($this->appId,$this->input->post('selectedUserId',true));
		$data['selectedUserDetails'] = $ud[0];
		$data['selectedUserDetails']['userId'] =$this->input->post('selectedUserId',true);
		$data['countrycounter'] = 'false';
		if(($data['selectedUserDetails']['country'] != 'India') && ($data['selectedUserDetails']['country']) !=2)
		{
			$data['countrycounter'] = 'true';
		}
		
		$objSumsProduct = new Sums_Product_client();
		$data['products'] = $objSumsProduct->getDerivedProducts();
		$data['currencies'] = $objSumsProduct->getAllCurrency();
		$data['parameters'] = $objSumsProduct->getAllSumsParameters();
		$manObj = new Sums_Manage_client();
		$data['quoteUsers'] = $manObj->getSumsUsers($this->appId);
		$data['prodId'] = $prodId; // 16 CRM.
		
		
		
		$this->load->view('sums/productSelect',$data);
		}
	
	function addUser($prodId=19)
		{
		$this->init();
		$response['sumsUserInfo'] = $this->sumsUserValidation(19,'19');
		error_log("RECEIVED POST DATA: ".print_r($_POST,true));
		$objSumsManage = new Sums_Manage_client();
		$response['userGroupList'] =  $objSumsManage->getUserGroupList($this->appId,$request);
		$response['branchList'] =  $objSumsManage->getBranchList($this->appId,$request);
		$response['prodId'] = $prodId;
		$this->load->view('sums/addUser',$response);	
		}
	
	function getManagerList($branchIds,$userGroupId)
		{
		$this->init();
		error_log("RECEIVED POST DATA: ".print_r($_POST,true));
		$objSumsManage = new Sums_Manage_client();
		$managerList =  $objSumsManage->getManagerList($this->appId,$branchIds,$userGroupId);
		$this->load->library('register_client');
		$regObj = new Register_client();
		$userids='';
		foreach($managerList as $manager)
		{
			if($userids=='')
			{
				$userids=$manager['userId'];
			}
			else
			{
				$userids=$userids.",".$manager['userId'];
			}
		}
		error_log('data',print_r($managerList,true));
		$nameList = $regObj->getDetailsforUsers($appId,$userids);
		$response['managerList']=array();
		for($i=0;$i<count($nameList);$i++)
		{
			array_push($response['managerList'],array('userId'=>$nameList[$i]['userid'],'ManagerName'=>$nameList[$i]['displayname']));
		}
		$this->load->view('sums/addUserManagerList',$response);	
		
		}
	
	function addSumsUser($prodId=19)
		{
		$this->init();
		$resolution = $this->input->post('resolutionreg');
		$sourceurl = $this->input->post('refererreg');
		$sourcename = 'SUMS_REGISTRATION_FORM';

		$data['sourceurl'] = $sourceurl;
		$data['sourcename'] = $sourcename;
		$data['resolution'] = $resolution;
		error_log("RECEIVED POST DATA: ".print_r($_POST,true));
		$data['sumsUserInfo'] = $this->sumsUserValidation();
		
		$data['appId'] = $this->appId;
		$data['usergroup'] = "sums";
		$data['email'] = trim($this->input->post('email'));
		$data['displayname'] = htmlentities(addslashes(trim($this->input->post('name'))));
		$data['password'] = addslashes($this->input->post('password'));
		$data['confirmpassword'] = addslashes($this->input->post('password'));
		$data['ePassword'] = sha256($data['password']);
		
		$data['name'] = htmlentities(addslashes(trim($this->input->post('name'))));
		$data['firstname'] = $data['name'];
		$data['mobile'] = trim($this->input->post('phoneNumber'));
		$data['executiveName'] = $this->input->post('name');
		$data['employeeId']= $this->input->post('EmployeeId');
		$data['Role'] = $this->input->post('UserGroupId');
		$data['DiscountLimit'] = $this->input->post('DiscountLimit');
		$branchIds='';
		$branchIdList=$this->input->post('BranchId');
		foreach($branchIdList as $branchId)
		{
			if($branchIds!='')
			{
				$branchIds.=",".$branchId;
			}
			else
			{
				$branchIds=$branchId;
			}
		}
		$data['BranchId']=$branchIds;
		$data['ManagerId'] = $this->input->post('ManagerId');	
		$this->load->library('register_client');
		$regObj = new Register_client();
		$addResult = $regObj->adduser_new($data);
		
		if ($addResult['status']>0)
		{
			$data['userId'] = $addResult['status'];
			$objSumsManage = new Sums_Manage_client();
			$result=$objSumsManage->addSumsUser($appId,$data);
		}
		$data['userDetails'] = $result;
		$data['prodId'] = $prodId;
		$this->load->view('sums/profileSuccessUser',$data);
		
		}
	
	function submitPayment($prodId=16)
		{
		$data['sumsUserInfo'] = $this->sumsUserValidation();
		$sumsUserInfo = $data['sumsUserInfo'];
		$UIQuotationId = $this->input->post('Quotation_Id');
		$transationId = $this->input->post('Transaction_Id');
		$SalesBy = $this->input->post('Sales_By');
		$SalesBranch = $this->input->post('Sales_Branch');
		$this->load->library(array('sums_quotation_client','subscription_client'));
		$validateUser = $this->checkUserValidation();
		$userId = $validateUser[0]['userid'];
		$objQuo = new Sums_Quotation_client();
		$objSubs = new Subscription_client();
		
		if ($UIQuotationId!="") {
			$QuotationInfo = $objQuo->getQuotation($this->appId,$UIQuotationId);
		}
		
		$paymentInfo['Sale_Type'] = $this->input->post('Sale_Type');
		
		$Payment_Modes = $this->input->post('Payment_Mode');
		$cheque_no = $this->input->post('Cheque_No');
		$cheque_date = $this->input->post('Cheque_Date');
		$cheque_rec_date = $this->input->post('Cheque_Receiving_Date');
		$cheque_city = $this->input->post('Cheque_City');
		$cheque_bank = $this->input->post('Cheque_Bank');
		$amount = $this->input->post('Amount_Received');
		$tds_amount = $this->input->post('TDS_Amount');
		$cheque_comment = $this->input->post('Cheque_DD_Comments');
		$paymentDone = $this->input->post('PaymentDone');
		$totalParts = $this->input->post('totalParts');
		$depositedBy = $this->input->post('Deposited_By');
		$depositedBranch = $this->input->post('Deposited_Branch');
		for($i=0;$i<$totalParts;$i++)
		{
			if(isset($paymentDone[$i]) == 'Paid')
				$paymentDone[$i] = 'Paid';
			else
				$paymentDone[$i] = 'Un-paid';
		}	
		$paymentDetails = array();
		for($i=0;$i<$totalParts;$i++)
		{
			$paymentDetails[$i] = array(array( 	
				'Cheque_No'=>$cheque_no[$i],
				'Cheque_Date'=>$cheque_date[$i],
				'Cheque_Receiving_Date'=>$cheque_rec_date[$i],
				'Cheque_City'=>$cheque_city[$i],
				'Cheque_Bank'=>$cheque_bank[$i],
				'Cheque_DD_Comments'=>$cheque_comment[$i],
				'TDS_Amount'=>$tds_amount[$i],
				'Amount_Received'=>$amount[$i],
				'isPaid' => 'Un-paid',
				'Payment_Mode'=>$Payment_Modes[$i],
				'Deposited_By'=>$depositedBy[$i],
				'Deposited_Branch'=>$depositedBranch[$i]		
			),'struct');
		}
		
		$payeeAddress['Address'] = $this->input->post('Address');
		$payeeAddress['Country'] = $this->input->post('Country');
		$payeeAddress['City'] = $this->input->post('City');
		$payeeAddress['Pincode'] = $this->input->post('Pincode');
		$payeeAddress['Phone_No'] = $this->input->post('Phone_No');
		$payeeAddress['Email_Address'] = $this->input->post('Email_Address');
		
		if ($transationId!="") {
			$res = $objSubs->deletePaymentInfo($this->appId,$transationId);
			$finalResponse = $objSubs->addPaymentInfo($this->appId,$transationId,$paymentInfo,$paymentDetails,$payeeAddress,$userId);
		}
		
		if (array_key_exists('QuotationDetails',$QuotationInfo)) {
			if ($QuotationInfo['QuotationDetails']['Status']=="ACTIVE")
			{
				$trans['UIQuotationId'] = $UIQuotationId;
				$trans['clientUserId'] = $QuotationInfo['QuotationDetails']['ClientId'];
				$trans['SalesBy'] = $SalesBy;
				$trans['SalesBranch'] = $SalesBranch;
				$trans['FinalSalesAmount'] = $this->input->post('Final_Sales_Amount');
				$trans['CurrencyId'] = $QuotationInfo['QuotationDetails']['CurrencyId'];
				$trans['sumsUserId'] = $sumsUserInfo['userid'];
				$trans['Sale_Type'] = $paymentInfo['Sale_Type'];	
				$response = $objSubs->addTransaction($this->appId,$trans);
				$finalResponse = $objSubs->addPaymentInfo($this->appId,$response['TransactionId'],$paymentInfo,$paymentDetails,$payeeAddress,$userId);
			}
		}
		
		$data['pageInfo']['type'] = "add";
		$data['pageInfo']['product'] = "Transaction";
		$data['pageInfo']['id'] = $finalResponse['TransactionId'];
		$data['prodId'] = $prodId;	
		//$this->load->view('sums/submitSuccess',$data);
		//Mailer to Executive, Approving Manager
		global $mailEventsArr;
		$mailData['eventType']= $mailEventsArr['APPROVAL_QUEUE'];
		$mailData['transactionId']=$finalResponse['TransactionId'];
		$this->load->library(array('sums_mailer_client'));
		$objSumsMail = new Sums_mailer_client();
		$response = $objSumsMail->sendSumsMails($this->appId,$mailData);
		$this->load->view('sums/submitSuccess',$data);
		}
	
	function validationQueue($viewTrans = -1,$prodId=20)
		{
			echo "This functionality is not in use.";
			exit;
		$this->init();
		if($viewTrans == -1)
		{
			$data['sumsUserInfo'] = $this->sumsUserValidation(13,'20');
			$data['queueType'] = "MANAGER";
		}
		else
		{
			$data['sumsUserInfo'] = $this->sumsUserValidation(16,'20');	
			$data['queueType'] = "View";
		}
		$data['sumsUserType'] = $data['sumsUserInfo']['sumsuserinfo'][0]['Role'];
		$data['viewTrans'] = $viewTrans;
		$data['prodId'] = $prodId;
		$this->load->view('sums/validateTransactions',$data);
		}
	
	function validateFinance($prodId=20)
		{
		$this->init();
		$data['sumsUserInfo'] = $this->sumsUserValidation(15,'20');
		$data['sumsUserType'] = $data['sumsUserInfo']['sumsuserinfo'][0]['Role'];
		//echo '<pre>';print_r($data);echo '</pre>';
		$data['queueType'] = "FINANCE";
		$data['prodId'] = $prodId;
		$data['viewTrans'] = -1;
		$this->load->view('sums/validateTransactions',$data);
		}
	
	function validateOps($prodId=21)
		{
		$this->init();
		$data['sumsUserInfo'] = $this->sumsUserValidation(18,'21');
		$data['sumsUserType'] = $data['sumsUserInfo']['sumsuserinfo'][0]['Role'];
		$data['queueType'] = "OPS";
		$data['prodId'] = $prodId;
		$data['viewTrans'] = -1;
		$this->load->view('sums/validateTransactions',$data);
		}
	
	function getPayments($prodId=20)
		{
		$this->init();
		$response = array();
		$response['sumsUserInfo'] = $this->sumsUserValidation();
		$request['approverId'] = $response['sumsUserInfo']['userid'];
		$request['queueType'] = $this->input->post('queueType',true);
		$request['transactionId'] = $this->input->post('transactionId',true);
		$request['uiQuotationId'] = $this->input->post('uiQuotationId',true);
		$request['paymentId'] = $this->input->post('paymentId',true);
		$request['trans_start_date'] = $this->input->post('trans_start_date',true);
		$request['trans_end_date'] = $this->input->post('trans_end_date',true);
		$request['clientId'] = $this->input->post('clientId',true);
		$request['contactName'] = $this->input->post('contactName',true);
		$request['collegeName'] = $this->input->post('collegeName',true);
		$request['chequeNo'] = $this->input->post('chequeNo',true);
		$request['saleBy'] = $this->input->post('saleBy',true);
		$request['saleType'] = $this->input->post('saleType',true);
		$request['saleType'] = "'".implode("','",$request['saleType'])."'";
		$request['saleFloorAmount'] = $this->input->post('saleFloorAmount',true);
		$request['saleCeilAmount'] = $this->input->post('saleCeilAmount',true);
		$request['discFloorAmount'] = $this->input->post('discFloorAmount',true);
		$request['discCeilAmount'] = $this->input->post('discCeilAmount',true);
		$Payment_Status = (array)$this->input->post('Payment_Status',true);
		$csvPaymentList = '';	
		foreach($Payment_Status as $temp){
			$csvPaymentList .= "'".$temp."',";
		}
		$csvPaymentList = substr($csvPaymentList,0,(strlen($csvPaymentList)-1));	
		$request['csvPaymentList'] = $csvPaymentList;
		
		$this->load->library(array('subscription_client'));
		$objSumsSubscript = new Subscription_client();
		$response['queueType'] = $request['queueType'];
		$filters = '';
		$response['payments'] =  $objSumsSubscript->searchPayment($this->appId,$request,$filters);
		$response['prodId'] = $prodId;	
		$this->load->view('sums/paymentToBeDone',$response);
		}
	
	function doMultiplePayment($prodId=20,$PaymentId = 0,$editvariable = 'false'){		
		$this->init();
		$response = array();
		
		$response['sumsUserInfo'] = $this->sumsUserValidation(22,$prodId);
		$request['approverId'] = $response['sumsUserInfo']['userid'];
		$request['PaymentId'] = $this->input->post('PaymentId',true);	
		if($PaymentId != 0){
		$request['PaymentId'] = explode(",",$PaymentId);
		}
		$paymentIds = array();
		$PartNumbers = array();
		
		if(is_array($request['PaymentId'])){
			foreach($request['PaymentId'] as $temp){
				if($editvariable == 'false'){
					$temp1 = explode("#",$temp);
				}
				else{
					$temp1 = explode("-",$temp);	
				}
				if(!in_array($temp1[0],$paymentIds)){
					$tempPaymentId = $temp1[0];
					$PartNumbers[$tempPaymentId] = array();
					array_push($paymentIds,$temp1[0]);
				}
				array_push($PartNumbers[$tempPaymentId],$temp1[1]);
			}			
		}
		else{
			$temp1 = explode('#',$request['PaymentId']);
			$paymentIds = array_push($temp1[0]);
			$tempPaymentId = $temp1[0];
			$PartNumbers[$tempPaymentId] = array();
			array_push($PartNumbers[$tempPaymentId],$temp1[1]);
		}
		$cvsPaymentIds = implode(",",$paymentIds);
		$this->load->library(array('subscription_client'));
		$manObj = new Sums_Manage_client();	
		$response['quoteUsers'] = $manObj->getSumsUsers($this->appId);		
		$response['branchList'] = $manObj->getBranches($this->appId);
		$objSumsSubscript = new Subscription_client();
		$response['queueType'] = $request['queueType'];
		$response['PaymentDetails'] =  $objSumsSubscript->getPaymentDetails($this->appId,$cvsPaymentIds); 
		$response['prodId']=$prodId;
		$response['normalPage']=1;
		$response['PartNumbers']=$PartNumbers;
		$response['editvariable'] = $editvariable;
		$this->load->view('sums/doMultiplePayment',$response);	
	}
	
	
	
	function submitMultiplePayments($prodId=20){
		$this->init();
		$response['sumsUserInfo'] = $this->sumsUserValidation(22,$prodId);
		$validateUser = $this->checkUserValidation();
		$userId = $validateUser[0]['userid'];
		$request['approverId'] = $response['sumsUserInfo']['userid'];
		$noOfPayments = $this->input->post('numberOfPayments',true);
		$paymentInfo = array();
		$paymentDoneArray = array();
		$paymentsarray = array();
		$flag = true;
		$temp1 = 0;
		// flag for checking receipt date back validation
		$Receiptdateflag = 'unset';
		
		
		for($i=1;$i<=$noOfPayments;$i++){
			$str = 'PaymentAmount_'.$i;
			$str1 = 'paymentIdDetails_'.$i;
			$str2 = 'totalAmount_'.$i;
			$str3 = 'nextPaymetDate_'.$i;
			
			$temporaryvariable =  $this->input->post($str1,true);
			
			$paymentIdDetails = explode('#',$this->input->post($str1,true));
			$paymentInfo[$i][0] = array();	
			$paymentInfo[$i][0]['Payment_Id'] = $paymentIdDetails[0];
			$paymentInfo[$i][0]['Part_Number'] = $paymentIdDetails[1];
			$paymentInfo[$i][0]['AmountBeingPaid'] = (float)$this->input->post($str,true);
			$paymentInfo[$i][0]['totalAmountToBePaid'] = $this->input->post($str2,true);
			$paymentInfo[$i][0]['nextPaymentDate'] = $this->input->post($str3,true);
			$paymentInfo[$i][1] = 'struct';
			$paymentDoneArray[$i]['Payment_Id'] = $paymentIdDetails[0];
			$paymentDoneArray[$i]['Part_Number'] = $paymentIdDetails[1];
			$temp1 += $paymentInfo[$i][0]['AmountBeingPaid'];
			
			$temporaryvariable = str_replace('#','-', $temporaryvariable);
			array_push($paymentsarray,$temporaryvariable);
						
		}
		$csvPaymentIds = implode(",",$paymentsarray);
		
		$today=mktime(0,0,0,date("m"),date("d"),date("Y"));
	
		//echo "<pre>";print_r($paymentInfo);echo "</pre>";
		$otherPaymentDetails = array();
		$otherPaymentDetails['Payment_Mode'] = $this->input->post('Payment_Mode',true);
		$otherPaymentDetails['Cheque_No'] = $this->input->post('Cheque_No',true);
		$otherPaymentDetails['Cheque_Date'] = $this->input->post('Cheque_Date',true);
		$otherPaymentDetails['Cheque_City'] = $this->input->post('Cheque_City',true);
		$otherPaymentDetails['Cheque_Bank'] = $this->input->post('Cheque_Bank',true);
		$otherPaymentDetails['Cheque_Receiving_Date'] = $this->input->post('Cheque_Receiving_Date',true);
		$otherPaymentDetails['Amount_Received'] = (float)$this->input->post('Amount_Received',true);
		$otherPaymentDetails['TDS_Amount'] = (float)$this->input->post('TDS_Amount',true);
		$otherPaymentDetails['Cheque_DD_Comments'] = $this->input->post('Cheque_DD_Comments',true);
		$otherPaymentDetails['Depositedby'] = $this->input->post('Depositedby',true);
		$otherPaymentDetails['branch_name'] = $this->input->post('branch_name',true);	
		$temp2 = $otherPaymentDetails['Amount_Received'] + $otherPaymentDetails['TDS_Amount'];
		//echo "<pre>";print_r($otherPaymentDetails);echo "</pre>";
		
		/**
		* validation appled for bypass certain users given by finance....
		*/
		 $validitycheckarray=array(
		     '0' => '43033',
		     '1' => '2492',
		 );
		 
		foreach($validitycheckarray as $key=>$value){
		       if($response['sumsUserInfo']['validity'][0]['userid'] == $value ){
			   $Receiptdateflag = 'unset';
		       }
		}

		if ( $today > strtotime($otherPaymentDetails['Cheque_Date'])) {
				    $Receiptdateflag = 'set';
		}
					
				
		if($Receiptdateflag == 'set'){
			$redirectUrl = '/sums/Manage/doMultiplePayment/'.'20/'.$csvPaymentIds.'/'.'true';
			header("Location:".$redirectUrl);
			exit();
		}
		
		if($temp1 != $temp2)
		{
			$flag = false;
		}	
		$this->load->library(array('subscription_client'));
		$objSumsSubscript = new Subscription_client();
		if($flag)
		{
			$result = $objSumsSubscript->submitMultiplePayment($this->appId,$paymentInfo,$otherPaymentDetails,$userId); 
		}
		
		$response['paymentDoneArray']=$paymentDoneArray;
		$response['typeOfSuccessPage']='multiplePayment';
		$response['prodId']=$prodId;
		$redirectUrl = '/sums/Manage/successMultiplePayment/'.urlencode(serialize($paymentDoneArray)).'/'.$flag;
		header("Location:".$redirectUrl);
		exit;
		//$this->load->view('sums/paymentSuccessPage',$response);			
	}
	
	
	function updateMultiplePaymentStatus($prodId=20)
		{
		$this->init();
		$response = array();
		$response['sumsUserInfo'] = $this->sumsUserValidation(22,$prodId);
		$validateUser = $this->checkUserValidation();
		$request['PaymentId'] = $this->input->post('PaymentId',true);
		$userId = (isset($validateUser[0]['userid']))?$validateUser[0]['userid']:0;
		$requestData = array();
		$i=0;		
		if(is_array($request['PaymentId'])){
			foreach($request['PaymentId'] as $temp){
				$temp1 = explode("#",$temp);
				$requestData[$i][0]['PaymentId'] = $temp1[0];
				$requestData[$i][0]['PartNumber'] = $temp1[1];	
				$requestData[$i][1] = 'struct';			
				$paymentDoneArray[$i]['Payment_Id'] = $requestData[$i][0]['PaymentId'];
				$paymentDoneArray[$i]['Part_Number'] = $requestData[$i][0]['PartNumber'];
				$i++;
			}			
		}else{
			$temp1 = explode('#',$request['PaymentId']);
			$requestData[$i][0]['PaymentId'] = $temp1[0];
			$requestData[$i][0]['PartNumber'] = $temp1[1];	
			$requestData[$i][1] = 'struct';
			$paymentDoneArray[$i]['Payment_Id'] = $requestData[$i][0]['PaymentId'];
			$paymentDoneArray[$i]['Part_Number'] = $requestData[$i][0]['PartNumber'];			
		}	
		$this->load->library(array('subscription_client'));
		$objSumsSubscript = new Subscription_client();
		$updateRes = $objSumsSubscript->updateMultiplePaymentStatus($this->appId,$requestData,'Paid','EDIT',$userId);
		$flag = true;
		$redirectUrl = '/sums/Manage/successMultiplePayment/'.urlencode(serialize($paymentDoneArray)).'/'.$flag;
		header("Location:".$redirectUrl);
		exit;	
		}
	
	function successMultiplePayment($dataOfPayment,$flag,$prodId=20)
		{
		$this->init();
		$response['sumsUserInfo'] = $this->sumsUserValidation(22,$prodId);
		$temp = unserialize(urldecode($dataOfPayment));
		$response['paymentDoneArray']=unserialize($dataOfPayment);
		$response['typeOfSuccessPage']='multiplePayment';
		$response['prodId']=$prodId;
		$this->load->view('sums/paymentSuccessPage',$response);			
		}

	function editPayment($paymentId,$editPartNumber=-1,$prodId=20,$transaction_id='',$editvariable ='false'){	
		$this->init();
		$response['sumsUserInfo'] = $this->sumsUserValidation(22,$prodId);
		

		$request['approverId'] = $response['sumsUserInfo']['userid'];
		$request['PaymentId'] = $this->input->post('PaymentId',true);
		$this->load->library(array('subscription_client'));
		$objSumsSubscript = new Subscription_client();
		//$transaction_id = $paymentId;
		$FILTER = '';
		if($editPartNumber != -1)
		{
			$FILTER = ' AND pd.Part_Number = '.$editPartNumber;
		}
		$paymentData = $objSumsSubscript->getPaymentDetails($this->appId,$paymentId,$FILTER);
		$response['editvariable'] = $editvariable;
		
		$manObj = new Sums_Manage_client();	
		$response['quoteUsers'] = $manObj->getSumsUsers($this->appId);		
		$response['branchList'] = $manObj->getBranches($this->appId);	
		$response['noOfMaxPartPayment'] = 30;
		$response['Payment'] = $paymentData;
		$response['editPartNumber'] = $editPartNumber;
		$response['queueType'] = $request['queueType'];
		$response['prodId']=$prodId;
		$response['transaction_id']=$transaction_id;
		$this->load->library(array('sums_mis_query_get_data'));
		$objMISClient = new Sums_mis_query_get_data();
        $paymentAndTransactionDetails = $objMISClient->getEditPaymentDetails($this->appId,$transaction_id);
        $response['clientDetails'] = $paymentAndTransactionDetails[0]['clientDetails'][0];
		
		$validitycheckarray=array(
			'0' => '43033',	
			'1' => '2492',	
			);	
		$response['validationcheck'] = 'check';
		
		
		
		foreach($validitycheckarray as $key=>$value){
		
		if($response['sumsUserInfo']['validity'][0]['userid'] == $value )
		{
			$response['validationcheck'] = 'uncheck';

		}
		}
		//echo "<pre>";print_r($response);echo "</pre>";exit;
		$this->load->view('sums/editPaymentDetails',$response);
		
	}



	function submitEditPaymet($prodId=20){
			
		$this->init();
		$response['sumsUserInfo'] = $this->sumsUserValidation(22,$prodId);
		$validateUser = $this->checkUserValidation();
		$paymentId = $this->input->post('PaymentId',true);
		$SaleType = $this->input->post('Sale_Type',true);
		$noOfMainPartPayment = $this->input->post('noOfMainPartPayment',true);
		$noOfSubParts = explode("#",$this->input->post('noOfSubParts',true));
		$flag = true;
		if($this->input->post('noOfSubParts',true) == '')
		{
			$flag = false;
		}
		$request=array();
		$subPayments=array();
		$cancelPayments=array();
		$partNumberArray = array(); 
		$m=0;$n=0;$k=0;
		
		$Transaction_Id = $this->input->post('Transaction_Id',true);
		$Receiptdateflag = 'unset';
				
		for($i=0;($i<$noOfMainPartPayment)&&($flag);$i++)
		{
			$sum = 0;
			$Payment_Mode[$i] = $this->input->post('Payment_Mode'.$i,true);
			$Cheque_No[$i] = $this->input->post('Cheque_No'.$i,true);
			$Cheque_Date[$i] = $this->input->post('Cheque_Date'.$i,true);
			$Cheque_City[$i] = $this->input->post('Cheque_City'.$i,true);
			$Cheque_Bank[$i] = $this->input->post('Cheque_Bank'.$i,true);
			$Cheque_Receiving_Date[$i] = $this->input->post('Cheque_Receiving_Date'.$i,true);
			$Amount_Received[$i] = (float)$this->input->post('Amount_Received'.$i,true);	
			$TDS_Amount[$i] = (float)$this->input->post('TDS_Amount'.$i,true);	
			$Deposited_By[$i] = $this->input->post('Deposited_By'.$i,true);
			$Deposited_Branch[$i] = $this->input->post('Deposited_Branch'.$i,true);
			$Cheque_DD_Comments[$i] = $this->input->post('Cheque_DD_Comments'.$i,true);		
			$Payment_Status[$i] = $this->input->post('Payment_Status'.$i,true);
			$editPartNumber[$i] = $this->input->post('editPartNumber'.$i,true);
			$PartPaymentValue[$i] = $this->input->post('PartPaymentValue'.$i,true);		
			$sum += ($Amount_Received[$i]+$TDS_Amount[$i]);
			$cancelStatus = false;
						
			$today=mktime(0,0,0,date("m"),date("d"),date("Y"));
			
			if(!in_array($editPartNumber[$i],$partNumberArray))
				array_push($partNumberArray,$editPartNumber[$i]);
			
			if($this->input->post('cancelThisPart'.$i))
				$cancelStatus = true;
			if((!$cancelStatus) && ($this->input->post('editThisPart'.$i)))
			{
				$request[$k][0] = array();
				$request[$k][0]['Payment_Mode'] = $Payment_Mode[$i];	
				$request[$k][0]['Cheque_No'] = $Cheque_No[$i];
				$request[$k][0]['Cheque_Date'] = $Cheque_Date[$i];
				$request[$k][0]['Cheque_City'] = $Cheque_City[$i];
				$request[$k][0]['Cheque_Bank'] = $Cheque_Bank[$i];
				$request[$k][0]['Cheque_Receiving_Date'] = $Cheque_Receiving_Date[$i];
				$request[$k][0]['Amount_Received'] = (float)$Amount_Received[$i];	
				$request[$k][0]['TDS_Amount'] = (float)$TDS_Amount[$i];	
				$request[$k][0]['Deposited_By'] = $Deposited_By[$i];
				$request[$k][0]['Deposited_Branch'] = $Deposited_Branch[$i];
				$request[$k][0]['Cheque_DD_Comments'] = $Cheque_DD_Comments[$i];
				$request[$k][0]['Payment_Status'] = $Payment_Status[$i];
				$request[$k][0]['editPartNumber'] = $editPartNumber[$i];
				$request[$k][0]['editThisPart'] = 1; // Hard coding as $this->input->post('editThisPart'.$i) is already checked in main if condition.			
				if ( $today > strtotime($Cheque_Date[$i])) {
				    $Receiptdateflag = 'set';
				}

				$request[$k][1] = 'struct';
				$k++;
				if($noOfSubParts[$i] > 0)
				{
					for($j=0;$j<$noOfSubParts[$i];$j++)
					{
						$subPayments[$m][0] = array();
						$subPayments[$m][0]['Payment_Mode'] = $this->input->post('Payment_Mode'.$i.$j,true);	
						$subPayments[$m][0]['Cheque_No'] = $this->input->post('Cheque_No'.$i.$j,true);
						$subPayments[$m][0]['Cheque_Date'] = $this->input->post('Cheque_Date'.$i.$j,true);
						$subPayments[$m][0]['Cheque_City'] = $this->input->post('Cheque_City'.$i.$j,true);
						$subPayments[$m][0]['Cheque_Bank'] = $this->input->post('Cheque_Bank'.$i.$j,true);
						$subPayments[$m][0]['Cheque_Receiving_Date'] = $this->input->post('Cheque_Receiving_Date'.$i.$j,true);
						$subPayments[$m][0]['Amount_Received'] = (float)$this->input->post('Amount_Received'.$i.$j,true);	
						$subPayments[$m][0]['TDS_Amount'] = (float)$this->input->post('TDS_Amount'.$i.$j,true);
						$subPayments[$m][0]['Deposited_By'] = $this->input->post('Deposited_By'.$i.$j,true);
						$subPayments[$m][0]['Deposited_Branch'] = $this->input->post('Deposited_Branch'.$i.$j,true);
						$subPayments[$m][0]['Cheque_DD_Comments'] = $this->input->post('Cheque_DD_Comments'.$i.$j,true);
						$subPayments[$m][0]['Payment_Status'] = $this->input->post('Payment_Status'.$i.$j,true);
						$subPayments[$m][0]['parent'] = $editPartNumber[$i];			
						$subPayments[$m][1] = 'struct';	
						$sum += ($subPayments[$m][0]['Amount_Received']+$subPayments[$m][0]['TDS_Amount']);
												
						$chequeDateCompare = $subPayments[$m][0]['Cheque_Date'];						
						if ( $today > strtotime($chequeDateCompare ) ) {
							$Receiptdateflag = 'set';
						}
						$m++;						
					}
				}
			}
			else if($cancelStatus)
			{
				$cancelPayments[$n][0]=array();
				$cancelPayments[$n][0]['cancelPartNumber'] = $editPartNumber[$i];
				$cancelPayments[$n][0]['Cheque_DD_Comments'] = $Cheque_DD_Comments[$i];
				$cancelPayments[$n][1]='struct';
				$n++;
			}
			if($PartPaymentValue[$i] != $sum)
				$flag = false;
		}
	/**
	* validation appled for bypass certain users given by finance....
	*/
         $validitycheckarray=array(
             '0' => '43033',
             '1' => '2492',
         );

        if(count($partNumberArray) > 1)
            $partNumber = -1;
        else
            $partNumber = $partNumberArray[0];

        foreach($validitycheckarray as $key=>$value){
            if($response['sumsUserInfo']['validity'][0]['userid'] == $value ){
                $Receiptdateflag = 'unset';
            }
        }

        if($Receiptdateflag == 'set'){
            $redirectUrl = '/sums/Manage/editPayment/'.$paymentId.'/'.$partNumber.'/'.'20'.'/'.$Transaction_Id.'/'.'true';
            header("Location:".$redirectUrl);
            exit();
        }
	
		$mainPaymentInfo = array();
		$mainPaymentInfo['Sale_Type'] = $SaleType;
		$this->load->library(array('subscription_client'));
		$objSumsSubscript = new Subscription_client();	
		$cancelPaymentRes = "";
		$editPaymentRes = "";
		
		if((count($cancelPayments) > 0) && ($flag))
		{
			$cancelPaymentRes = $objSumsSubscript->cancelPaymet($this->appId,$paymentId,$cancelPayments,$validateUser[0]['userid']);
		}
		if((count($request) > 0) && ($flag))
		{	
			$editPaymentRes = $objSumsSubscript->submitEditPaymet($this->appId,$paymentId,$request,$subPayments,$mainPaymentInfo,$validateUser[0]['userid']);
		}
			
		$redirectUrl = '/sums/Manage/successEdit/'.$paymentId.'/'.$partNumber.'/'.$flag;
		header("Location:".$redirectUrl);
		exit;
		//$this->load->view('sums/paymentSuccessPage',$response);
	}


	function successEdit($paymentId=-1,$partNum=-1,$flag=true,$prodId=20)
		{
		$this->init();
		$response['sumsUserInfo'] = $this->sumsUserValidation(22,$prodId);	
		$response['prodId']=$prodId;
		$response['paymentId']=$paymentId;
		$response['partNum']=$partNum;
		$response['flag']=$flag;
		$response['typeOfSuccessPage']='editPayment';
		$this->load->view('sums/paymentSuccessPage',$response);
		}
	function getTransactionsToValidate($prodId=20)
		{
		$this->init();
		$response['sumsUserInfo'] = $this->sumsUserValidation();
		$request['approverId'] = $response['sumsUserInfo']['userid'];
		$request['queueType'] = $this->input->post('queueType',true);
		$request['transactionId'] = $this->input->post('transactionId',true);
		$request['uiQuotationId'] = $this->input->post('uiQuotationId',true);
		$request['trans_start_date'] = $this->input->post('trans_start_date',true);
		$request['trans_end_date'] = $this->input->post('trans_end_date',true);
		$request['clientId'] = $this->input->post('clientId',true);
		$request['contactName'] = $this->input->post('contactName',true);
		$request['collegeName'] = $this->input->post('collegeName',true);
		$request['chequeNo'] = $this->input->post('chequeNo',true);
		$request['saleBy'] = $this->input->post('saleBy',true);
		$request['saleType'] = $this->input->post('saleType',true);
		$request['saleType'] = "'".implode("','",$request['saleType'])."'";
		$request['saleFloorAmount'] = $this->input->post('saleFloorAmount',true);
		$request['saleCeilAmount'] = $this->input->post('saleCeilAmount',true);
		$request['discFloorAmount'] = $this->input->post('discFloorAmount',true);
		$request['discCeilAmount'] = $this->input->post('discCeilAmount',true);
		$request['userId'] = $response['sumsUserInfo']['userid'];
		
		$this->load->library(array('sums_product_client','subscription_client','sums_quotation_client'));
		$objSumsSubscript = new Subscription_client();
		$objProduct = new Sums_Product_client();
		$objQuotation = new Sums_Quotation_client();
		
		$response['queueType'] = $request['queueType'];
		$response['transactions'] =  $objSumsSubscript->searchTransaction($this->appId,$request);
		
		$i=0;
		for($i=0;$i<count($response['transactions']);$i++)
		{
			if($request['queueType'] == 'OPS'){
				$quotationDetails=$objQuotation->getQuotationDerivedProds(1,$response['transactions'][$i]['UIQuotationId'],"PENDING");
			}else{
				$quotationDetails=$objQuotation->getQuotation(1,$response['transactions'][$i]['UIQuotationId'],"TRANSACTION");
			}
			$products='';
			$j=0;	
			foreach($quotationDetails['QuotationProducts'] as $productDetails)
			{
				$derivedProductProperties=$objProduct->getDerivedProductProperties($productDetails['DerivedProductId']);
				if($request['queueType'] == 'OPS'){
					$response['transactions'] [$i]['derivedProducts'][$j]['Quantity']= $productDetails['Quantity'];
					$response['transactions'] [$i]['derivedProducts'][$j]['DerivedProdId']= $productDetails['DerivedProductId'];
				}
				foreach($derivedProductProperties as $derivedProduct)
				{
					if($request['queueType'] == 'OPS'){	
						$response['transactions'] [$i]['derivedProducts'][$j]['DerivedProdName']= $derivedProduct['DerivedProductName'];	
					}
					$products=($products=='')?$products.$productDetails['Quantity']." ".$derivedProduct['DerivedProductName']:$products.", ".$productDetails['Quantity']." ".$derivedProduct['DerivedProductName'];
				}
				$j++;
			}
			
			$paymentArray = $objSumsSubscript->getPaymentInfo(1,$response['transactions'][$i]['TransactionId']);
			$response['transactions'][$i]['Sale_Type']=$paymentArray['Transaction']['Sale_Type'];
			$Payment_Modes='';
			$paymentDate='';
			$paymentAmmount='';
			foreach($paymentArray['Payment'] as $payment)
			{
				$Payment_Modes = ($Payment_Modes=='')?$Payment_Modes.$payment['Payment_Mode']:$Payment_Modes.", ".$payment['Payment_Mode'];
				$paymentdate = split(" ",$payment['Cheque_Receiving_Date']);
				$paymentDate = ($paymentDate=='')?$paymentDate.$paymentdate[0]:$paymentDate.", ".$paymentdate[0];
				$paymentAmmount = ($paymentAmmount=='')?$paymentAmmount.$payment['Amount_Received']:$paymentAmmount.", ".$payment['Amount_Received'];
			}
			$response['transactions'][$i]['Payment_Modes']=$Payment_Modes;
			$response['transactions'][$i]['Payment_Date']=$paymentDate;
			$response['transactions'][$i]['Payment_Ammount']=$paymentAmmount;
			$response['transactions'][$i]['ProductSelected']=$products;
		}
		$response['searchTypeForTransaction'] = 'Validate';
		$response['prodId'] = $prodId;	
		if($request['queueType'] == 'OPS'){
			$this->load->view('sums/transactionsForOpsApproval',$response);	
		}else{
			$this->load->view('sums/transactionsForApproval',$response);	
		}
		}
	
	function getTransactions($prodId=20)
		{
		$this->init();
		$response['sumsUserInfo'] = $this->sumsUserValidation(16,'20');
		$request['approverId'] = $response['sumsUserInfo']['userid'];
		$request['queueType'] = $this->input->post('queueType',true);
		$request['transactionId'] = $this->input->post('transactionId',true);
		$request['uiQuotationId'] = $this->input->post('uiQuotationId',true);
		$request['trans_start_date'] = $this->input->post('trans_start_date',true);
		$request['trans_end_date'] = $this->input->post('trans_end_date',true);
		$request['payment_start_date'] = $this->input->post('payment_start_date',true);
		$request['payment_end_date'] = $this->input->post('payment_end_date',true);			
		$request['clientId'] = $this->input->post('clientId',true);
		$request['contactName'] = $this->input->post('contactName',true);
		$request['collegeName'] = $this->input->post('collegeName',true);
		$request['chequeNo'] = $this->input->post('chequeNo',true);
		$request['saleBy'] = $this->input->post('saleBy',true);
		$request['saleType'] = $this->input->post('saleType',true);
		$request['saleType'] = "'".implode("','",$request['saleType'])."'";
		$request['saleFloorAmount'] = $this->input->post('saleFloorAmount',true);
		$request['saleCeilAmount'] = $this->input->post('saleCeilAmount',true);
		$request['discFloorAmount'] = $this->input->post('discFloorAmount',true);
		$request['discCeilAmount'] = $this->input->post('discCeilAmount',true);
		$request['userId'] = $response['sumsUserInfo']['userid'];
		$request['flowType'] = $this->input->post('flowType',true);
		
		$this->load->library(array('sums_product_client','subscription_client','sums_quotation_client'));
		$objSumsSubscript = new Subscription_client();
		$objProduct = new Sums_Product_client();
		$objQuotation = new Sums_Quotation_client();
		
		$response['queueType'] = $request['queueType'];
		$response['flowType'] = $request['flowType'];
		$response['transactions'] =  $objSumsSubscript->searchTransaction($this->appId,$request);
		$i=0;
		for($i=0;$i<count($response['transactions']);$i++)
		{
			$quotationDetails=$objQuotation->getQuotation(1,$response['transactions'][$i]['UIQuotationId'],"TRANSACTION");
			$products='';
			foreach($quotationDetails['QuotationProducts'] as $productDetails)
			{
				$derivedProductProperties=$objProduct->getDerivedProductProperties($productDetails['DerivedProductId']);
				foreach($derivedProductProperties as $derivedProduct)
				{
					$products=($products=='')?$products.$productDetails['Quantity']." ".$derivedProduct['DerivedProductName']:$products.", ".$productDetails['Quantity']." ".$derivedProduct['DerivedProductName'];
				}
			}
			
			$response['transactions'][$i]['ProductSelected']=$products;
		}
		$response['searchTypeForTransaction'] = 'View';
		$response['prodId'] = $prodId;
		$this->load->view('sums/transactionsForApproval',$response);
		}	
	
	function approveManager($prodId=20)
		{
		$this->init();
		$data['sumsUserInfo'] = $this->sumsUserValidation();
		$request['approverId'] = $data['sumsUserInfo']['userid'];
		$request['approverType'] = $data['sumsUserInfo']['sumsuserinfo'][0]['Role'];
		$request['transactionId'] = array($this->input->post('TransactionId',true),'struct');
		$objSumsManage = new Sums_Manage_client();
		$response['result'] =  $objSumsManage->approveManager($this->appId,$request);
		$data['result'] = $response['result'];
		$data['prodId'] = $prodId; //20 is for manage sales
		$this->load->view('sums/approvalSuccess',$data);
		}
	
	function approveFinance($prodId=20)
		{
		$this->init();
		$data['sumsUserInfo'] = $this->sumsUserValidation();
		$request['approverId'] = $data['sumsUserInfo']['userid'];
		$request['approverType'] = $data['sumsUserInfo']['sumsuserinfo'][0]['Role'];
		$request['transactionId'] = $this->input->post('TransactionId',true);
		
		$objSumsManage = new Sums_Manage_client();
		$response['result'] =  $objSumsManage->approveFinance($this->appId,$request);
		$data['result'] = $response['result'];
		$data['prodId'] = $prodId;
		$this->load->view('sums/approvalSuccess',$data);
		}
	
	function approveOps($prodId=21)
		{
		//echo "<pre>";print_r($_POST);echo "</pre>";
		$this->init();
		$data['sumsUserInfo'] = $this->sumsUserValidation();
		$totalSelectCount = $this->input->post('totalUserCount');
		$request['approverId'] = $data['sumsUserInfo']['userid'];
		$request['approverType'] = $data['sumsUserInfo']['sumsuserinfo'][0]['Role'];
		
		$request['transactionId'] = array(array(),'struct');
		$request['derivedProdId'] = array(array(),'struct');
		$request['subsStartDate'] = array(array(),'struct');
		for($i=1;$i<=$totalSelectCount;$i++){
			$indexName = 'TransactionId'.$i;
			if(isset($_POST[$indexName])){
				$individualData = '';
				$transactionData = $this->input->post($indexName);
				$indexNameForDate = 'subsStartDate'.$i;
				$startDateValue = $this->input->post($indexNameForDate);	
				$individualData = explode('##',$transactionData);
				array_push($request['transactionId'][0], $individualData[0]);
				array_push($request['derivedProdId'][0],$individualData[1]);
				array_push($request['subsStartDate'][0], $startDateValue);
			}
		}
		//echo "<pre>";print_r($request);echo "</pre>";
		$objSumsManage = new Sums_Manage_client();
		$response['result'] =  $objSumsManage->approveOpsDerived($this->appId,$request);
		$this->load->library(array('subscription_client'));
		$objSubs = new Subscription_client();
		$response =  $objSubs->createDerivedSubscription($this->appId,$request);
		error_log_shiksha("SS >> ".print_r($response,true)); 
		$data['result'] = $response;
		$data['prodId'] = $prodId;
		$data['type'] = 'Creat';
		//echo "<pre>";print_r($data);echo "</pre>";
		$this->load->view('sums/approvalSuccessOps',$data);
		}
	
	function cancelTransaction()
		{
		$this->init();
		$data['sumsUserInfo'] = $this->sumsUserValidation();
		$request['approverId'] = $data['sumsUserInfo']['userid'];
		$request['approverType'] = $data['sumsUserInfo']['sumsuserinfo'][0]['Role'];
		$request['transactionId'] = $this->input->post('TransactionId',true);
		$request['cancelComments'] = $this->input->post('CancelComments',true);
		error_log(print_r($request,true));
		$objSumsManage = new Sums_Manage_client();
		$response['result'] =  $objSumsManage->cancelTransaction($this->appId,$request);
		$data['result'] = $response['result'];
		$this->load->view('sums/cancelTransSuccess',$data);
		}
	
	function searchUserForListingPost($prodId=16)
		{
		$this->init();
		$data['sumsUserInfo'] = $this->sumsUserValidation();
		$data['forListingPost'] = true;
		$data['prodId'] = $prodId;
		$this->load->view('sums/userSelect',$data);
		}
	
	function searchTransForSubsEdit($prodId=21)
		{
		$this->init();
		$data['sumsUserInfo'] = $this->sumsUserValidation();
		if(!(array_key_exists(43,$data['sumsUserInfo']['sumsuseracl']) || array_key_exists(44,$data['sumsUserInfo']['sumsuseracl']))){
			header('location:/sums/Sums_Common/permissionDenied/'.$prodId);
			exit();
		}
		$data['sumsUserType'] = $data['sumsUserInfo']['sumsuserinfo'][0]['Role'];
		$data['prodId'] = $prodId;
		$data['queueType'] = "subsView";
		$data['viewTrans'] = 'viewTrans';
		$data['flowType'] = 'Subscription';
		//echo '<pre>';print_r($data);echo '</pre>';
		$this->load->view('sums/validateTransactions',$data);
		}
	
	function searchTransForConsumedEdit($prodId=21)
		{
		$this->init();
		$data['sumsUserInfo'] = $this->sumsUserValidation();
		if(!(array_key_exists(43,$data['sumsUserInfo']['sumsuseracl']) || array_key_exists(44,$data['sumsUserInfo']['sumsuseracl']))){
			header('location:/sums/Sums_Common/permissionDenied/'.$prodId);
			exit();
		}
		$data['sumsUserType'] = $data['sumsUserInfo']['sumsuserinfo'][0]['Role'];
		$data['prodId'] = $prodId;
		$data['queueType'] = "subsView";
		$data['viewTrans'] = 'viewTrans';
		$data['flowType'] = 'Consumption';
		//echo '<pre>';print_r($data);echo '</pre>';
		$this->load->view('sums/validateTransactions',$data);
		}
	
}
?>
