<?php
include('Sums_Common.php');
/**
 * Controller Class for Sums Nav_Sums_Integration
 * 
 */
class Nav_Integreation extends Sums_Common
{
	private $_model;
	private $appId = 1;
	private $useridgenerated;
	private $Transactionidgenerated;
	private $Paymentidgenerated;
	private $subscriptionidgenerated;
	private $currency;
	private $paymenttype;
	private $i = 5;
	private $LogId;

	function init()
	{
		$this->load->helper(array('form', 'url','date','image'));
		$this->load->library(array('subscription_client','Sums_product_client','Register_client','Nav_Sums_Integration_client'));
		
		$dbLibObj = DbLibCommon::getInstance('SUMS');
		$dbHandleSums = $dbLibObj->getWriteHandle();
		
		$dbLibObj = DbLibCommon::getInstance('SUMSShiksha');
		$dbHandleShiksha = $dbLibObj->getWriteHandle();
		
		$this->load->model('nav_integration_model');
		$this->_model = new nav_integration_model($dbHandleShiksha,$dbHandleSums);
	
	}
	function receiveNavXML()
	{
		$xml = $this->input->post('Request');
	
		$this->parseNavXml($xml);
	}
	
	private function parseNavXml($xml)
	{
		ini_set('memory_limit','300M');
		$startCommitId    = 1;
		$this->load->library('XMLParser');
		$objParser        = new XMLParser();
		$arrNonXmlErrs    = array();
		$arrNonXmlErrs    = &$objParser->arrNonXmlErr;
		$arrFinalData     = $objParser->parseXML($xml);// here we have XML's data into an Array.
		$arrFinalInfo     = $objParser->arrDocInfo;
		$arrErrs          = array();
		$noOfRowsAffected = 0;	
		$navtransactionId = $arrFinalInfo['TransactionID'];				
		$this->load->library(array('Nav_Sums_Integration_client'));
		$objNav           = new Nav_Sums_Integration_client();
		$xmlType          = 'Incoming';
		$status           = 'Success';


		$response         = $objNav->addNAVXML($this->appId,$navtransactionId,$xmlType,$xml,$status);
		$this->LogId      = $response['primaryId'];		
		if(count($objParser->arrXmlErr))
		{
			$arrErrs = $objParser->arrXmlErr;
		}
		else
		{
			$noOfRowsAffected= $this->getQueryType($arrFinalInfo,$arrFinalData,$arrNonXmlErrs,$startCommitId);
		}

		// array to append generated ids in acknowledgement xml
		$Sumsmapping = array(
				'userid'         => $this->useridgenerated,
				'Transactionid'  => $this->Transactionidgenerated,
				'subscriptionid' => $this->subscriptionidgenerated,
				'Paymentid'      => $this->Paymentidgenerated
				);

		$xmlresponse = $objParser->createResponse($objParser,$arrErrs,$arrNonXmlErrs,$noOfRowsAffected,$Sumsmapping);
		$response    = $objNav->updateNAVXML($this->appId,$xmlresponse,$this->LogId);

		echo $xmlresponse;
		return $xmlresponse;
	}

	private function getQueryType($arrDocInfo,$arrFinal,&$arrNonXmlErrs,&$commitPointId)
	{
		$i =0;
		$totalNoRowsAffected = 0;		
		$var = max(array_keys($arrDocInfo['actiontype']));
		for(;$commitPointId <= $var; $commitPointId++ )
		{

			if(strtoupper($arrDocInfo["actiontype"][$commitPointId])=="INSERT")
			{
				$retunStatus = $this->createInsertQry($arrFinal[$commitPointId],$commitPointId,$arrErrs,$arrNonXmlErrs,$arrDocInfo["actiontype"][$commitPointId]);			
				/**
				 *  Payment handled seperately for prepayment cases
				 */
                                $paymentType = strtoupper($arrFinal[$commitPointId]['transaction'][0]['ptype']);
				if($paymentType == 'UPFRONT' )
				{
					$this->addPayment($arrFinal[4]['payment']);
				}
                                elseif($paymentType == 'INSTALLMENT' || $paymentType == 'CREDIT')
				{
                                        $this->modifyInstallmentPayment($arrFinal[4]['payment'], $arrFinal[$commitPointId]['dues']);
				}
			}		
			elseif (strtoupper($arrDocInfo["actiontype"][$commitPointId])=="MODIFY")
			{
				$retunStatus = $this->createUpdateQuery($arrFinal[$commitPointId],$commitPointId,$arrErrs,$arrNonXmlErrs);
			}
			elseif ($arrDocInfo["actiontype"][$commitPointId]=="DELETE")
			{
				$arrQueries = $this->createDeleteQry($arrFinal[$commitPointId],$commitPointId,$arrErrs);
			}
			else
			{
				continue;
			}
			$totalNoRowsAffected += $noOfRowsAffected;

		}

		return $totalNoRowsAffected;
	}


	/**
	 * API for checking insert query type
	 */
	private function createInsertQry($arrXmlData,$commitId,&$arrErrs,&$arrNonXmlErrs,$actiontype){

		foreach ($arrXmlData as $tableName => $tableDataRows ) 
		{
			switch ($tableName)
			{
				case "customer" :
					$arrInsertQueries 		= $this->addProfile($tableDataRows[0],$tableName, $commitId, $actiontype);
					break;

				case "transaction" :
					$arrInsertQueries 		= $this->addTransaction($tableDataRows[0],$tableName);
					break;

				case "subscription" :
					for($i =0;$i < count($tableDataRows);$i++){
						$arrInsertQueries 	= $this->addSubscription($tableDataRows[$i],$tableName);
					}
					break;

				case "dues" :
					$arrInsertQueries 		= $this->addDues($tableDataRows,$tableName);
					break;

				case "payment" :
					$arrInsertQueries 		= $this->addPayment($tableDataRows,$tableName);
					break;
			}
		}
	}

	/**
	 * API for insertion of The Free Listing Derived Product subscriptions - free bronze quantity
	 */
	private function saddFreeSubscription()
	{
		$this->init();
		$this->load->library('sums_product_client');

		$prodClient = new Sums_product_client();
                $result = $prodClient->getFreeDerivedId($this->appId);
		
		$derivedProdId = $result['derivedProdId'];
                $param['derivedProdId'] = $derivedProdId;
                $param['derivedQuantity'] = 1000; // Changing free bronze quantity to 1000 (Discussed with product team , needs to be 1000)
           	$param['clientUserId'] = $this->useridgenerated;
                $param['sumsUserId'] = '2492';
           	$param['subsStartDate'] = date("Y-m-d H:i:s");
		error_log("Array to addFreeSubscription ".print_r($param,true));
                $objSumsClient = new Subscription_client();
                $respSubs = $objSumsClient->addFreeSubscription(1,$param);
		
           	error_log(print_r($respSubs,true).'ReSPONSE');
		
	}
	
	/**
	 * API for insertion of clients profile
	 */
	private function addProfile($insertionarray,$tablename, $commitId, $actiontype)
	{
		
		$this->init();
		// check if nav userid already exist 
		//$insertionarray['nav_company_id'];
		$nav_user_id_mapping = $this->_model->getNavUseridMapping($insertionarray['nav_company_id']);
		
		if(count($nav_user_id_mapping) >0) {
			$this->load->library('XMLParser');
			$objParser      = new XMLParser();
			$objNav         = new Nav_Sums_Integration_client();
			$response       = $insertionarray['nav_company_id'];
			$status         = 'fail';
			$xmlresponse    = $objParser->createErrorResponse($response,$commitId,$actiontype,$addResult['status']);
			$updateresponse = $objNav->updateNAVXML($this->appId,$xmlresponse,$this->LogId,$status);
			exit;
		}
		
		$this->load->library('register_client');
		$regObj                   = new Register_client();
		$sourcename               = 'SUMS_ENTERPRISE_REGISTRATION';
		$finalarray['sourceurl']  = 'https://shiksha.com/nav_integration';
		$finalarray['sourcename'] = $sourcename;
		$finalarray['resolution'] = 'https://shiksha.com/nav_integration';
		$finalarray['appId']      = $this->appId;
		$finalarray['usergroup']  = "enterprise";
		$finalarray['email']      = $insertionarray['Email'];
		$finalarray['firstname']  = $insertionarray['contactperson'];
		$displayname              = sanitizeString($insertionarray['DisplayName']);
		$checkresponse            = $regObj->checkAvailability($this->appId,$displayname,'displayname');
		//condition applied in this case for checking displayname send from NAV
		if($checkresponse == 1){
			/* Loop to check unique displayname START */
			$responseCheckAvail = 1;
			while($responseCheckAvail == 1){
				$displayname = $displayname.rand(10001,99999);
				$responseCheckAvail = $regObj->checkAvailability($this->appId,$displayname,'displayname');
			}
		}
		$finalarray['displayname']       = $displayname;
		$finalarray['password']          = 'shiksha@'.rand(1,1000000);
		$finalarray['confirmpassword']   = $finalarray['password'];
		$finalarray['ePassword']         = sha256($finalarray['password']);		
		$finalarray['busiCollegeName']   = $insertionarray['CompanyName'];
		$finalarray['busiType']          = $insertionarray['BusinessType'];
		$finalarray['categories']        = $insertionarray['CourseID'];
		$finalarray['contactName']       = $insertionarray['FirstName'];
		$finalarray['contactAddress']    = $insertionarray['Address'].$insertionarray['Address2'].$insertionarray['Address3'];
		$finalarray['pincode']           = $insertionarray['Pincode'];
		$finalarray['mobile']            = $insertionarray['MobilePhoneNo'];
		$finalarray['executiveName']     = $insertionarray['ExecutiveName'];
		$finalarray['city']              = $insertionarray['City'];

		$finalarray['city'] = $this->_model->getCityIdByName(strtoupper($finalarray['city']));

		if($finalarray['city']<1){
			$finalarray['city']              = 0;			
		}

		$finalarray['bypassmobilecheck'] = 'true';
		$finalarray['country']           = $insertionarray['country'];
		
		$this->load->library(array('category_list_client','listing_client'));
		
		$arraymap = $this->countryNavArrayMap();
		foreach($arraymap as $key=>$value)
		{
			if($insertionarray['country'] == $value)
			{
				$finalarray['country'] = $key;
				break;
			}
			else{
				$finalarray['country'] = 0;
				
			}
		}
		
		$objNav = new Nav_Sums_Integration_client();
		$cityarr = $objNav->getCities($this->appId);

		$finalarray['city'] = 0;
		foreach($cityarr as $coun)
		{
			if(strtoupper($insertionarray['city']) == strtoupper($coun[0]['city_name']))
			{
				$finalarray['city'] = $coun[0]['city_id'];
				break;
			}
			else{
				$finalarray['city'] = 0;
				
			}
		}


		$userData = $this->_model->validateClientByEmail($finalarray['email']);		
		// if client email Id already exists in Shiksha DB and client user group is user then we need to update client user group to enterprise
		if(!empty($userData) && $userData['usergroup'] == 'user'){												
			//update client user group			
			$this->_model->updateNavClientUserGroupByUserId($userData['userid']);
			if($finalarray['country'] == 2){
				$isdCode = INDIA_ISD_CODE;
			}else{
				//61 is just a dummy value
				$isdCode = '61';
			}

			$isNumberValid = validateEmailMobile('mobile',$finalarray['mobile'],$isdCode);
			if($isNumberValid){				
				// check client mobile number			
				$regNumTestUser  = isTestUser($userData['mobile']);
				$navNumTestUser  = isTestUser($finalarray['mobile']);

				if((empty($userData['mobile']) && !$navNumTestUser) || ($regNumTestUser && !$navNumTestUser)){
					$this->load->model('user/usermodel');
					//update nav client mobile
	 				$usermodel  = new usermodel();
					$usermodel->updateMobileByUserId($userData['userid'],$finalarray['mobile']);
				}
			}

			$finalarray['userid']      = $userData['userid'];
			$finalarray['sumsUserId']  = '2492';			
			$this->useridgenerated     = $userData['userid'];			
			/**
			  * API for insertion of free listing derived product subscriptions
			  */
			$this->saddFreeSubscription();
			
			$this->load->library(array('Nav_Sums_Integration_client'));
			$objNav   = new Nav_Sums_Integration_client();
			$resp     = $objNav->addEnterpriseUser($this->appId,$finalarray);
			$response = $objNav->navUserMapping($this->appId,$this->useridgenerated,$insertionarray['nav_company_id']);			
		}else{			
			$addResult = $regObj->adduser_new($finalarray);
			if($addResult['status'] == '-1' || $addResult['status'] == '0'){
				$this->load->library('XMLParser');
				$objParser      = new XMLParser();
				$response       = false;
				$status         = 'fail';
				$xmlresponse    = $objParser->createErrorResponse($response,$commitId,$actiontype,$addResult['status']);
				$updateresponse = $objNav->updateNAVXML($this->appId,$xmlresponse,$this->LogId,$status);
				exit;
			}
			elseif($addResult['status'] > 0)
			{
				// Send Welcome Mail to the user
				$this->load->library('Alerts_client');
				$alertClient               = new Alerts_client();
				$subject                   = "Congrats! You are a Shiksha member now";
				$email                     = $finalarray['email'];
				$data['usernameemail']     = $finalarray['email'];
				$data['userpasswordemail'] = $finalarray['password'];
				$content                   = $this->load->view('user/RegistrationMail',$data,true);
				$responsemail              = $alertClient->externalQueueAdd("12",ADMIN_EMAIL,$email,$subject,$content,$contentType="html");
				$finalarray['userid']      = $addResult['status'];
				$finalarray['sumsUserId']  = '2492';
				$this->useridgenerated     = $addResult['status'];
				
				/**
				* API for insertion of free listing derived product subscriptions
				*/
				$this->saddFreeSubscription();
				
				$this->load->library(array('Nav_Sums_Integration_client'));
				$objNav = new Nav_Sums_Integration_client();
				$resp = $objNav->addEnterpriseUser($this->appId,$finalarray);
				$response = $objNav->navUserMapping($this->appId,$this->useridgenerated,$insertionarray['nav_company_id']);

			}
		}
		
		return $this->useridgenerated;
	}

	
	/**
	 * API for insertion of nav Transactions
	 */
	private function addTransaction($insertionarray,$tablename)
	{
		$this->init();
		$trans['clientUserId']     = $insertionarray['company_id'];
		$trans['SalesBy']          = $insertionarray['saleby'];
		$trans['SalesBranch']      = $insertionarray['salesBranch'];
		$trans['FinalSalesAmount'] = $insertionarray['net_amount'];
		
		if($insertionarray['SumsCustID'] == '')
		{
			$trans['clientUserId'] = $this->useridgenerated;
		}
		else{
			
			$trans['clientUserId'] = $insertionarray['SumsCustID'];			
		}
		
		$trans['UIQuotationId']      = 'nav';
		$trans['sumsUserId']         = '2492';
		$trans['nav_transaction_id'] = $insertionarray['nav_transaction_id'];
		$trans['company_id']         = $insertionarray['company_id'];
		
		if(strtoupper($insertionarray['currency']) == "INR"){
			$trans['CurrencyId'] = 1;
		}
		else{
			$trans['CurrencyId'] = 2;
		}
		
		$this->paymenttype = $insertionarray['ptype'];
		$this->currency    = $trans['CurrencyId'];
		$this->load->library(array('Nav_Sums_Integration_client'));
		$objNav                       = new Nav_Sums_Integration_client();
		$response                     = $objNav->addTransaction($this->appId,$trans);
		$this->Transactionidgenerated = $response['TransactionId'];
	}
	
	
	/**
	 * API for insertion of nav subscriptions
	 */
	private function addSubscription($insertionarray,$tablename)
	{
		$this->init();
		$newBaseAndDerivedProdIdsMapping = array(
				10870 => array('633','105','106','632')
			);

		$insertionarray['approverType'] = '1';
		$approverid = '2492';// sums admin user
		$transactionId = $this->Transactionidgenerated;
                //in case of duplicate transaction xml received
                if($transactionId === null){
                    return;
                }

        $currency = $insertionarray['currency'];
        if(isset($insertionarray['currency']) && !empty($insertionarray['currency'])){
			
			if($insertionarray['currency'] == "INR" )
			{
				$currency = 1;
			}
			else{
				$currency = 2;
			}
		}
		else{
			$currency =1;
		}

        $baseProdFlag = $insertionarray['IsBase_Id'];
        
        if($baseProdFlag == 'Yes') {
        	$baseProdId = $insertionarray['prod_id'];
        	
        	foreach ($newBaseAndDerivedProdIdsMapping as $derivedId => $baseValues) {
				if(in_array($baseProdId, $baseValues)) {
		       		$derivedProdId = $derivedId;
		        } 
        	}
        	
        	if($derivedProdId == ''){
        		$derivedProdId = $this->_model->getDerivedProductIdFromBaseId($baseProdId, $currency);
        	}
        	
        } else {
        	$baseProdId = '';
			$derivedProdId = $insertionarray['prod_id'];
		}
		
		$subsStartDate = $insertionarray['activate_on'];
		
		if($insertionarray['quantity_check'] != ''){
			$newProductFlag = $insertionarray['quantity_check'];
		} else {
			$newProductFlag = '';
		}
		
		$parameterStringArray = array ();
		$strArray = explode ( "#", $insertionarray['parameter_string']);
		foreach ( $strArray as $temp ) {
			$tempArray = explode ( ":", $temp );
			$parameterStringArray [$tempArray [0]] = $tempArray [1];
		}
				
		$quantity = $parameterStringArray['QUANTITY'];
		
		if($insertionarray['SumsCustID'] == '')
		{
			$clientuserid = $this->useridgenerated;
		}
		else{
			
			$clientuserid = $insertionarray['SumsCustID'];
			
		}
		
		$nav_subscription_line_no = $insertionarray['nav_subscription_line_no'];
		$TotalBaseQuantity        = $insertionarray['TotalBaseQuantity'];
		$end_date                 = $insertionarray['end_date'];
		$nav_transactionid        = $insertionarray['nav_subscription_id'];
		$company_id               = $insertionarray['company_id'];

		$categorySponsorDerivedProductsList = $this->categorySponsorDerivedProductsList();

		foreach($categorySponsorDerivedProductsList as $key=>$value)
		{
			if($derivedProdId == $value)
			{
				$quantity = $quantity * 2; 
				break;
			}
		}
		
		$searchPageDerivedProductsList = $this->searchPageDerivedProductsList();

		foreach($searchPageDerivedProductsList as $key=>$value)
		{
			if($derivedProdId == $value)
			{
				$quantity = $quantity * 20; 
				break;
			}
		}

		$this->load->library(array('Nav_Sums_Integration_client'));
		$objNav = new Nav_Sums_Integration_client();

		if($baseProdFlag == 'Yes') {
        	$respSubs =  $objNav->createSubscription($this->appId,$transactionId,$subsStartDate,$approverid,$derivedProdId,$clientuserid,$quantity,$end_date,$nav_subscription_line_no,$currency,$nav_transactionid,$newProductFlag,$baseProdId,$company_id);
        } else {
			$respSubs =  $objNav->createSubscription($this->appId,$transactionId,$subsStartDate,$approverid,$derivedProdId,$clientuserid,$quantity,$end_date,$nav_subscription_line_no,$currency,$nav_transactionid,$newProductFlag,$baseProdId,$company_id);
		}
		
		$this->subscriptionidgenerated = $respSubs['Created_Subscriptions'];
		$subscriptionsArr = $respSubs['Created_Subscriptions'];
	}
	
	
	
	/**
	 * API for insertion of new payments
	 */
	private function addDues($insertionarray,$tablename)
	{
		$paymentDetails = array();
		for($i=0;$i<count($insertionarray);$i++)
		{
			$paymentDetails[$i] = array(array( 						
						'Amount_Received'=>$insertionarray[$i]['paid_amt'],
						'DueAmount'=>$insertionarray[$i]['due_amt'],
						'isPaid' => 'Un-paid',
						'Due_Date'=>$insertionarray[$i]['due_dt'],
						),'struct');
		}
		$paymentInfo['Sale_Type'] = $this->paymenttype;
		$flag = 0;
		$TransactionId = $insertionarray[0]['TransactionId'];
		if(empty($insertionarray[0]['TransactionId'])){
			$flag = 0;
			$TransactionId = $this->Transactionidgenerated;
			
		}
		else{
			$flag = 1;
			$paymentInfo['Sale_Type'] = 'Credit';	
		}
		
		$paymentInfo['Transaction_Id'] = $TransactionId;
		$this->load->library(array('Nav_Sums_Integration_client'));
		$objNav = new Nav_Sums_Integration_client();
		$finalResponse = $objNav->addNAVPayments($this->appId,$TransactionId,$paymentInfo,$paymentDetails,$flag);
		$this->Paymentidgenerated = $finalResponse['PaymentId'];
		return $finalResponse;
	}


	private function addPayment($insertionarray,$tablename)
	{		
		$paymentDetails = array();
		for($i=0;$i<count($insertionarray);$i++)
		{
			$paymentDetails[0] =  array(array( 					
						'TDS_Amount'=>$insertionarray[0]['tdsamount'],
						'Amount_Received'=>$insertionarray[0]['amount'],
						'isPaid' => 'Paid',
						'Payment_Mode'=>$insertionarray[0]['pmode'],
						'Cheque_Date'=>$insertionarray[0]['rcvd_date'],
						'nav_rcpt_id'=>$insertionarray[0]['nav_rcpt_id']
						),'struct');
		}
		$paymentInfo['Sale_Type'] = $this->paymenttype;
		
		$TransactionId = $insertionarray[0]['TransactionId'];
		if(empty($insertionarray[0]['TransactionId'])){

			$TransactionId = $this->Transactionidgenerated;
		}
		
		$paymentInfo['Transaction_Id'] = $TransactionId;
		$this->load->library(array('Nav_Sums_Integration_client'));
		$objNav = new Nav_Sums_Integration_client();
		$finalResponse = $objNav->addNAVPayments($this->appId,$TransactionId,$paymentInfo,$paymentDetails);
		$this->Paymentidgenerated = $finalResponse['PaymentId'];
		return $finalResponse;
	}
	
	/**
	 * API for checking update query type
	 */
	private function createUpdateQuery($arrXmlData,$commitId,&$arrErrs,&$arrNonXmlErrs,$actiontype){
		$var = array_keys($arrXmlData);
		if($var[0] == 'payment'){
			
			$arrInsertQueries = $this->updatePayment($arrXmlData,$tableName);
			return true;
		}
		
		foreach ( $arrXmlData as $tableName => $tableDataRows ) 
		{
			switch ($tableName)
			{
				case "customer" :
					$arrInsertQueries = $this->updateProfile($tableDataRows[0],$tableName);
					break;
				case "subscription" :
					$arrInsertQueries = $this->updateSubscription($tableDataRows[0],$tableName);
					break;
				case "payment" :
					$arrInsertQueries = $this->updatePayment($tableDataRows[0],$tableName);
					break;
				case "dues" :
					$arrInsertQueries = $this->updateDues($tableDataRows[0],$tableName);
					break;
			}
		}
		
		
	}


	/**
	 * API for updating Subscriptions 
	 */
	private function updateSubscription($insertionarray,$tablename)
	{
		$this->init();
		$request['startDate']                = $insertionarray['activate_on'];
		$request['endDate']                  = $insertionarray['end_date'];
		$request['nav_subscription_line_no'] = $insertionarray['nav_subscription_line_no'];
		$request['nav_subscription_id']      = $insertionarray['nav_subscription_id'];
		$this->load->library(array('subscription_client'));
		$objSubs       = new Subscription_client();
		$subscriptions =  $objSubs->changeSubsDates($this->appId,$request);
		$this->_model->updateShikshaExpiryDate($subscriptions['subsIdArr'],$request['startDate'],$request['endDate']);
	}


	/**
	 * API for updating clients profile
	 */
	private function updateProfile($insertionarray,$tablename)
	{
		$this->init();
		$userFieldsMap = array(
				'email'       => $insertionarray['Email'],
				'mobile'      => $insertionarray['MobilePhoneNo'],
				'firstname'   => $insertionarray['DisplayName'],
				'city'        => $insertionarray['city']			
				);

		
		$userFieldsMap['city'] = $this->_model->getCityIdByName(strtoupper($userFieldsMap['city']));

		if($userFieldsMap['city']<1){
			$userFieldsMap['city']    = 0;			
		}

		$userId = $insertionarray['SumsCustID'];
				
		$arraymap = $this->countryNavArrayMap();
		foreach($arraymap as $key=>$value)
		{
			if($insertionarray['country'] == $value)
			{
				$userFieldsMap['country'] = $key;
				break;
			}
			else{
				$userFieldsMap['country'] = 0;
				
			}
		}		

		if($userFieldsMap['country'] == 2){
			$isdCode = INDIA_ISD_CODE;
		}else{
				//61 is just a dummy value
			$isdCode = '61';
		}
	
		$isNumberValid = validateEmailMobile('mobile',$insertionarray['MobilePhoneNo'],$isdCode);
		if(!$isNumberValid){
			unset($userFieldsMap['mobile']);
		}else{
			$isTestUser = isTestUser($insertionarray['MobilePhoneNo']);
			if($isTestUser){
				unset($userFieldsMap['mobile']);
			}	
		}
		
		$this->load->library('Register_client');
		$registerClient = new Register_client();
		$response = $registerClient->updateUserGen($appId,$userFieldsMap,'userId',$userId,'');
		$this->load->library(array('Nav_Sums_Integration_client'));
		$objNav = new Nav_Sums_Integration_client();

		$request = array(
				'contactName'     => $insertionarray['DisplayName'],
				'contactAddress'  => $insertionarray['Address'],
				'categories'      => $insertionarray['CourseID'],
				'businessCollege' => $insertionarray['CompanyName'],
				'pincode'         => $insertionarray['Pincode'],
				'businessType'    => $insertionarray['BusinessType']
				);
		if(!empty($userId)){
			$resp = $objNav->updateEnterpriseUserDetails($appId,$request,$userId);
		}

		$executeFieldMap = array(
				'Nav_ClientId' => $insertionarray['nav_company_id'],
				'SalesBy' => $insertionarray['ExecutiveID']
				);

		if(!empty($insertionarray['ExecutiveID'])) {
			$updateResponse = $objNav->updateSalesPersonDetails($appId,$executeFieldMap);
		}

		return $response;
	}
	
	
	/**
	 * API for update payment
	 */
	private function updatePayment($traversalarray,$tablename)
	{
		
                foreach($traversalarray as $tableName => $tableDataRows ){
                    if($tableName == 'payment'){
                        $insertionarray = $tableDataRows[0];
                    }
                }
                
                $this->init();
                $this->load->library(array('Nav_Sums_Integration_client'));
                $paymentFieldsMap = array(
                                'nav_rcpt_id' => $insertionarray['nav_rcpt_id'],
                                );

                if(!empty($insertionarray['TransactionId'])){
                    $tid = $insertionarray['TransactionId'];
                }elseif($this->Transactionidgenerated){
                    $tid = $this->Transactionidgenerated;
                }else {
                    $tid = $insertionarray['nav_transaction_id'];
                }

                $objNav = new Nav_Sums_Integration_client();
                $Amountreceived =  $insertionarray['amount'];
                $isReversal =  $insertionarray['reversal'];
                
                if($isReversal == 'Yes'){
                    $navRcptId = $insertionarray['nav_rcpt_id'];
                    $editPaymentRes = $objNav->updateNavPaymentReversal($this->appId,$Amountreceived,$navRcptId);
                }
                else{

                    for($i =0; $i < count($traversalarray['dues']); $i++){

                        if($traversalarray['dues'][$i]['paid_amt'] == 0){
                            $arrInsertQueries = $this->updateDues($traversalarray['dues'][$i],'dues');
                        }
                        else{
                            //after check bounce ref payment case
                            if(isset($traversalarray['dues'][$i]['ref_so_no']) && $traversalarray['dues'][$i]['nav_transaction_id'] != $traversalarray['dues'][$i]['ref_so_no']){
                                $traversalarray['dues'][$i]['nav_rcpt_id'] = $insertionarray['nav_rcpt_id'];
                                $editPaymentRes = $objNav->updateNavRefPaymentDetails($this->appId,$tid,$traversalarray['dues'][$i]);
                            }
                            else{
                                $paymentFieldsMap['DueAmount'] = $traversalarray['dues'][$i]['due_amt'] - $traversalarray['dues'][$i]['paid_amt'];
                                $paymentFieldsMap['Amount_Received'] = $traversalarray['dues'][$i]['paid_amt'];
                                
                                $editPaymentRes = $objNav->updateNavPaymentDetails($this->appId,$tid,$paymentFieldsMap['Amount_Received'],$paymentFieldsMap,$traversalarray['dues'][$i]['due_dt']);
                            }
                        }
                    }
                    
                    if(!count($traversalarray['dues'])){
                        $paymentFieldsMap['DueAmount'] = 0;
                        $paymentFieldsMap['Amount_Received'] = $insertionarray['amount'];
                        $editPaymentRes = $objNav->updateNavPaymentDetails($this->appId,$tid,$paymentFieldsMap['Amount_Received'],$paymentFieldsMap);
                    }
                }
		
                return $editPaymentRes;
	}
        
        function modifyInstallmentPayment($payments, $dues){
            
            foreach ($payments as $i => $payment) {
                $insertionarray = $payment;
                
                if(!empty($insertionarray['TransactionId'])){
                    $tid = $insertionarray['TransactionId'];
                }elseif($this->Transactionidgenerated){
                    $tid = $this->Transactionidgenerated;
                }else {
                    $tid = $insertionarray['nav_transaction_id'];
                }
                
                $this->init();
                $this->load->library(array('Nav_Sums_Integration_client'));
                
                foreach ($dues as $due) {
                    if($due['paid_amt'] > 0){
                        $paymentFieldsMap = array(
                                        'nav_rcpt_id' => $insertionarray['nav_rcpt_id'],
                                        'DueAmount' => $due['due_amt'] - $due['paid_amt'],
                                        'Amount_Received' => $due['paid_amt']
                                        );

                        $objNav = new Nav_Sums_Integration_client();
                        $editPaymentRes = $objNav->updateNavPaymentDetails($this->appId,$tid,2*$due['paid_amt'],$paymentFieldsMap,$due['due_dt']);
                    }
                }
            }
            return $editPaymentRes;
        }


        
	
	/**
	 * API for update dues
	 */
	private function updateDues($insertionarray,$tablename)
	{
		//_p($insertionarray);echo "<br>";
		
		$DuesFieldsMap = array();
		$DuesFieldsMap = array(
				'DueAmount' => $insertionarray['due_amt'] - $insertionarray['paid_amt'],
				'Amount_Received' => $insertionarray['paid_amt'],

				);

		$duedate = $insertionarray['due_dt'];
		$TransactionId = $insertionarray['TransactionId'];
		if(empty($insertionarray['TransactionId'])){

			$TransactionId = $this->Transactionidgenerated;
		}
		$Cheque_Date = $insertionarray['due_dt'];
		$this->load->library(array('Nav_Sums_Integration_client'));
		$objNav = new Nav_Sums_Integration_client();
		$editPaymentRes = $objNav->updateDues($this->appId,$TransactionId,$Cheque_Date,$DuesFieldsMap,$duedate);
		return $editPaymentRes;
	}
					
	/*
	 * SUMS to NAV export functions
	 */ 
	function exportToNAV()
	{
		$this->validateCron();
		$this->init();
		$serverIp = $_SERVER['SERVER_ADDR'];
		$cronPid = getmypid();
		/*
		 * Get cron data like if there is a cron already running, fail count and  
		 * last processed time window
		 */
		$cronData = $this->nav_sums_integration_client->getCronData();
		$alreadyRunningCronStatus = $cronData['alreadyRunningCronStatus'];
		$alreadyRunningCronPid = $cronData['alreadyRunningCronPid'];
		$failCount = (int) $cronData['failCount'];
		$lastProcessedTimeWindow = $cronData['lastProcessedTimeWindow'];
		if($alreadyRunningCronStatus == 'NO' || ($alreadyRunningCronStatus == 'YES' && $failCount >= NAV_MAX_CRON_FAIL_COUNT)) {
			/*
			 * If no, Register new cron with status 'On'
			 */
			if($alreadyRunningCronStatus == 'NO') {
				$registerCronResponse = $this->nav_sums_integration_client->registerCron($cronPid,NAV_CRON_ON);
			}
			else  {
				$registerCronResponse = $this->nav_sums_integration_client->registerCron($cronPid,NAV_CRON_TERMINATE);
				//sendMailAlert('Terminating cron which has been running for a long time','',array('vikas.k@shiksha.com'));
				/*
				 * Kill the already running cron
				 */
				shell_exec('kill  -9 '.$alreadyRunningCronPid);
			}
			if($registerCronResponse === FALSE) {
				/*
				 * Unable to register cron
				 */
				//sendMailAlert('Unable to register a new cron','' ,array('vikas.k@shiksha.com'));
			}
			else {
				$cronId = $registerCronResponse;
				$timeWindow = $this->_getTimeWindowToProcess($lastProcessedTimeWindow);
				if(strtotime($timeWindow['start']) > strtotime($timeWindow['end'])) {
					$this->nav_sums_integration_client->updateCron($cronId,NAV_CRON_OFF,'0000-00-00 00:00:00');
				}
				else {
					$this->exportToNAVInTimeWindow($timeWindow);
					$this->nav_sums_integration_client->updateCron($cronId,NAV_CRON_OFF,$timeWindow['end']);
				}
			}
		}
		else {
			/*
			 * If yes, Register new cron with status 'Fail'
			 */
			$this->nav_sums_integration_client->registerCron($cronPid,NAV_CRON_FAIL);
			//sendMailAlert('A cron is already running','',array('vikas.k@shiksha.com'));
		}
	}

	private function _getTimeWindowToProcess($lastProcessedTimeWindow)
	{
		$timeWindow = array();
		$currentTime = date("Y-m-d H:i:00");
		$currentMinute = intVal(date('i'));
		if($currentMinute > 0 && $currentMinute < 30) {
			$timeWindowEnd = date("Y-m-d H:i:00",strtotime("-$currentMinute minutes",strtotime($currentTime)));
		}
		else if($currentMinute > 30) {
			$minuteLag = $currentMinute - 30;
			$timeWindowEnd = date("Y-m-d H:i:00",strtotime("-$minuteLag minutes",strtotime($currentTime)));
		}
		$timeWindowStart = date("Y-m-d H:i:00",strtotime("-30 minutes",strtotime($timeWindowEnd)));
		if($lastProcessedTimeWindow) {
			$timeWindowStart = $lastProcessedTimeWindow;
		}
		$timeWindow = array('start' => $timeWindowStart,'end' => $timeWindowEnd);
		return $timeWindow; 
	}

	public function exportToNAVInTimeWindow($timeWindow)
	{
		//$timeWindow = array(
		//		'start' => '2012-07-15 02:00:00',
		//		'end' => '2012-07-15 03:00:00',
		//		);
		$this->exportNewEnterpriseUsers($timeWindow);
		//$this->exportUpdatedEnterpriseUsers($timeWindow);
		$this->exportOnlinePayments($timeWindow);
	}
	
	
	public function exportNewEnterpriseUsers($timeWindow)
	{			
		$this->load->library(array('Nav_Sums_Integration_client'));
		$results = $this->nav_sums_integration_client->getEnterpriseUsers('NEW',$timeWindow);
		foreach($results as $result) {
			$this->_exportEnterpriseUser($result,'INSERT');
		}
	}
	
	
	public function exportUpdatedEnterpriseUsers($timeWindow)
	{
		$results = $this->nav_sums_integration_client->getEnterpriseUsers('UPDATED',$timeWindow);
		foreach($results as $result) {
			$this->_exportEnterpriseUser($result,'MODIFY');
		}
	}
	
	public function exportModifiedEnterpriseUsers()
	{
		$this->validateCron();
		$this->load->library(array('Nav_Sums_Integration_client'));
		$navModel = $this->load->model('nav_integration_model');

		$userNavMapping = $navModel->getAllNavUseridMapping();

		foreach ($userNavMapping as $key => $data) {
			$users[] = $data['userid'];
			$userIdToNavClientIdMapping[$data['userid']] = $data['Nav_ClientId'];
		}

		$users = array_chunk($users,1000);
		foreach ($users as $key => $userIds) {
			$chunksData =	$navModel->getUserData($userIds);
			foreach ($chunksData as $key => $userData) {
				if ($userIdToNavClientIdMapping[$userData['userid']]){
					$userData['Nav_ClientId'] = $userIdToNavClientIdMapping[$userData['userid']]; 
					$results[] = $userData;
				}	
			}
		}
		
		// $results = $this->nav_sums_integration_client->getUpdatedUsers($this->appId);
		
		foreach($results as $result) {
			$this->_exportEnterpriseUser($result,'MODIFY');
		}
				
		
	}
	
	
	private function _exportEnterpriseUser($result,$commitType)
	{

		//_p($result);
		$var = $this->MakeGUID();
		$customerXML =  '<?xml version="1.0" encoding="iso-8859-1" ?>'.
			'<WSRequest TransactionID="'.$var.'" UserID="nav" RequestDate="2012-06-20T11:50:10" xmlns="">'.
			'<Object Type="Codeunit" ID="50057">'.
			'<Codeunit>'.
			'<Trigger Name="SyncNAVCustomer">'.
			'<Parameter Name="SyncNAVCustomerXML" Type="XML">'.
			'<SyncNAV ResponsibilityCenter="SHIKSHA">'.
			'<Customer ActionType="'.$commitType.'">';

		$arraymap = $this->countryNavArrayMap();

		foreach($arraymap as $key=>$value)
		{
			if($result['country'] == $key)
			{
				$countryid = $value;
				break;
			}
			else{
				$countryid = 0;

			}
		}


		//$coursearray = explode(",", $result['categories']);
		$studyabroadflag = 'national';
		//$course = $coursearray['0'];

        if($result['country']>2) {
			$studyabroadflag = 'studyabroad';
		}

		/*$this->load->library(array('category_list_client','listing_client'));
		$cat_client = new Category_list_client();

		$categoryList1 = $cat_client->getCategoryTree($appId,'','national');

		$categoryForLeftPanel = array();

		foreach($categoryList1 as $temp)
		{
			if($course == $temp['categoryID']){

				$studyabroadflag = 'national';break;
			}
		}
		$categoryList2 = $cat_client->getCategoryTree($appId,'','studyabroad');

		foreach($categoryList2 as $temp)
		{
			if($course == $temp['categoryID']){

				$studyabroadflag = 'studyabroad';break;
			}
		}*/
		
		$displayname = str_replace( array( ',' , ' ','!','#','$','^','*','(',')','+','=','|','\''), '', $result['displayname']);	
		
		$customerData = array(
				'SumsCompanyID' => $result['userid'],
				'No' =>  $result['Nav_ClientId'],
				'Name' => $result['businessCollege'],
				'Email' => $result['email'],
				'contactperson' => $result['firstname'],
				'RegBy' => '',
				'Address' => $result['contactAddress'],
				'Address2' => '',
				'Address3' => '',
				'PostCode' => '',
				'PostCode' => '',
				'City' => $result['city_name'],
				'StateCode' => $result['StateCode'],
				'CountryCode' => $countryid,
				'PhoneNo' => $result['mobile'],
				'MobilePhoneNo' => '',
				'STD' => '',
				'ISD' => '',
				'UserName' => $displayname,
				'Contact' => '',
				'Designation' => '',
				'IndustryCode' => '',
				'CompanyClassification' => $result['businessType'],
				'Name2' => '',
				'International' => $result['country'] > 2 ? 'TRUE' : 'FALSE',
				'CourseID' => $result['categories'],
				'studyinindia' => $studyabroadflag == 'national' ? 'national' : 'studyabroad'
					);
		foreach($customerData as $key => $value) {
			$customerXML .= "<".$key.">".$value."</".$key.">";			
		}
		$customerXML .= '</Customer>'.
			'</SyncNAV>'.
			'</Parameter>'.
			'</Trigger>'.
			'</Codeunit>'.
			'</Object>'.
			'</WSRequest>';
		$data = "request=$customerXML";

		$navtransactionId = $var;
		$this->load->library(array('Nav_Sums_Integration_client'));
		$objNav = new Nav_Sums_Integration_client();
		$xmlType = 'Outgoing';
		$status = 'success';
		$response = $objNav->addNAVXML($this->appId,$navtransactionId,$xmlType,$customerXML,$status);

		//$responsexml = $this->post("https://172.16.3.246/InfoEdgeIntegrations/Receiver.aspx",$data);
		$responsexml = $this->post("http://192.168.2.187/InfoEdgeIntegrations/Receiver.aspx",$data);

		$this->saveResponse($responsexml);
	}
	
	/*
	 * curl function used to send data 
	 */ 
	 
	function post($url, $data) {
		//open connection
		$process = curl_init ( $url );
		
		//set the url, number of POST vars, POST data
		curl_setopt ( $process, CURLOPT_TIMEOUT, 10 );
		curl_setopt ( $process, CURLOPT_POSTFIELDS, $data );
		curl_setopt ( $process, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $process, CURLOPT_FOLLOWLOCATION, 1 );
		curl_setopt ( $process, CURLOPT_POST, 1 );
		
		//execute post
		$return = curl_exec ( $process );
		//close connection
		curl_close ( $process );
		return $return;
	
	}
	
	
	
	public function exportOnlinePayments($timeWindow)
	{
		$this->load->library(array('Nav_Sums_Integration_client'));
		$results = $this->nav_sums_integration_client->getNewOnlinePayments($timeWindow); 
		foreach($results as $result) {
			$this->_exportOnlinePayment($result);
		}
	}
	
	
	private function _exportOnlinePayment($result)
	{		
		$var = $this->MakeGUID();
		$paymentXML =  '<?xml version="1.0" encoding="iso-8859-1" ?>'.
			'<WSRequest TransactionID="'.$var.'" UserID="nav"  RequestDate="2012-06-22T02:19:09" xmlns="">'.
			'<Object Type="Codeunit" ID="50057">'.
			'<Codeunit>'.
			'<Trigger Name="SyncNAVPayment">'.
			'<Parameter Name="SyncNAVPaymentXML" Type="XML">'.
			'<SyncNAV ResponsibilityCenter="SHIKSHA">'.
			'<SOPaymentDetails>'.
			'<SOPayment ActionType="INSERT">';
			
		$paymentData = array(
				'PostingDate' => date("Y-m-d",strtotime($result['Payment_Modify_Date'])),
				'SumsRcptID' => $result['Cheque_No'],//$result['Payment_Id'].'_'.$result['Part_Number'].'_'.$result['CclUniqueId'],
				'SalesOrderNo' => $result['nav_transaction_id'],
				'Amount' => $result['Partially_Paid_Amount'],
				'PaymentGateway' => $result['PayGatewaymode'],
				'TransactionNumber' => $result['Cheque_No']//$result['Payment_Id'].'_'.$result['Part_Number'].'_'.$result['CclUniqueId']
				);
		
		if($paymentData['Amount'] == 0){
			return;	
		}
				
		foreach($paymentData as $key => $value) {
			$paymentXML .= "<".$key.">".$value."</".$key.">";			
		}
		$paymentXML .= '</SOPayment>'.
			'</SOPaymentDetails>'.
			'</SyncNAV>'.
			'</Parameter>'.
			'</Trigger>'.
			'</Codeunit>'.
			'</Object>'.
			'</WSRequest>';
		$data = "request=$paymentXML";
		
		$navtransactionId = $var;
		$this->load->library(array('Nav_Sums_Integration_client'));
		$objNav = new Nav_Sums_Integration_client();
		$xmlType = 'Outgoing';
		$status = 'success';
		$response = $objNav->addNAVXML($this->appId,$navtransactionId,$xmlType,$paymentXML,$status);
		//$responsexml = $this->post("https://172.16.3.246/InfoEdgeIntegrations/Receiver.aspx",$data);
		$responsexml = $this->post("http://192.168.2.187/InfoEdgeIntegrations/Receiver.aspx",$data);
		
		$this->saveResponse($responsexml);
	}
	
	
	
	function saveResponse($responseFromNav){
		$xmlObj = simplexml_load_string($responseFromNav);
		if($xmlObj){
			$firstAttr = $xmlObj->attributes();
		}
		if($firstAttr['Status'] == 1)
		{
			$allChildren = $xmlObj->children();
			if($allChildren->Customer){
				$a = $allChildren['Customer']['SumsCompanyID'];
				$this->load->library(array('Nav_Sums_Integration_client'));
				$objNav = new Nav_Sums_Integration_client();
				$editPaymentRes = $objNav->navUserMapping($this->appId,$allChildren->Customer->SumsCompanyID,$allChildren->Customer->No);
			}
			if($allChildren->SOPaymentDetails->SOPayment){
				$paymentIdArr = split("_",$allChildren->SOPaymentDetails->SOPayment->SumsRcptID);
				$returnPaymentId = $paymentIdArr[0];
				$returnPartPaymentId= $paymentIdArr[1];
				$returnPartNavId = $paymentIdArr[2];
				$this->load->library(array('Nav_Sums_Integration_client'));
				$objNav = new Nav_Sums_Integration_client();

				if(isset($returnPaymentId) && !empty($returnPaymentId)  && isset($returnPartPaymentId) && !empty($returnPartPaymentId)  ){
					$editPaymentRes = $objNav->updatePaymentDetails($this->appId,$returnPaymentId,$returnPartPaymentId,$returnPartNavId);
				}
			}
		}
	}
	
	
	function MakeGUID() {
		return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
				mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
				mt_rand( 0, 0x0fff ) | 0x4000,
				mt_rand( 0, 0x3fff ) | 0x8000,
				mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ) );
	}
	
	function countryNavArrayMap()
	{
		$navarray = array(
				'2'  =>  '66',
				'3'  =>  '156',
				'4'  =>  '160',
				'5'  =>  '8',
				'6'  =>  '134',
				'7'  =>  '108',
				'8'  =>  '27',
				'9'  =>  '57',
				'10'  =>  '92',
				'11'  =>  '144',
				'12'  =>  '53',
				'13'  =>  '145',
				'15'  =>  '41',
				'16'  =>  '140',
				'17'  =>  '125',
				'18'  =>  '159',
				'19'  =>  '123',
				'20'  =>  '129',
				'21'  =>  '70',
				'22'  =>  '71',
				'23'  =>  '52',
				'24'  =>  '135',
				'25'  =>  '120',
				'26'  =>  '121',
				'27'  =>  '112',
				'28'  =>  '80',
				'29'  =>  '113',
				'30'  =>  '74',
				'31'  =>  '150',
				'32'  =>  '16',
				'33'  =>  '7'
					);

		return $navarray;


	}
	
	
	
	function categorySponsorDerivedProductsList(){
		
		$categorySponsorDerivedProductsList = array(
			'1' => '8517',
			'2' => '8523',
			'3' => '8526',
			'4' => '8529',
			'5' => '8532',
			'6' => '8523',
			'7' => '8535',
			'8' => '8538',
			'9' => '8541',
			'10' => '8544',
			'11' => '8547',
			'12' => '8538',
			'13' => '8550',
			'14' => '8553',
			'15' => '8556',
			'16' => '8559',
			'17' => '8562',
			'18' => '8553',
			'19' => '8162',
			'20' => '8166',
			'21' => '8171',
			'22' => '8174',
			'23' => '8177',
			'24' => '8180',
			'25' => '8183',
			'26' => '8187',
			'27' => '8190',
			'28' => '8193',
			'29' => '10931',
			'30' => '10932',
			'31' => '10933',
			'32' => '10934',
			'33' => '10935',
			'34' => '10936'
			);
		return $categorySponsorDerivedProductsList;
		
		
	}
	
	function searchPageDerivedProductsList(){
		
		$searchPageDerivedProductsList = array(
			'1' => '10582',
			'2' => '10583',
			'3' => '10584',
			'4' => '10585',
			'5' => '10586',
			'6' => '10587',
			'7' => '10588',
			'8' => '10589',
			'9' => '10590',
			'10' => '10591',
			'11' => '10592',
			'12' => '10593',
			'13' => '10594',
			'14' => '10595',
			'15' => '10596',
			'16' => '10597',
			'17' => '10598',
			'18' => '10599',
			'19' => '10600',
			'20' => '10601',
			'21' => '10602',
			'22' => '10603',
			'23' => '10604',
			'24' => '10605',
			'25' => '10606'
			);

		return $searchPageDerivedProductsList;
	}
	
	function TaxAndPaymentDetailsUpdationScript()
	{
		exit();
		$this->init();
		$this->_model->updateTaxAndPaymentDetails();

	}

	function clientSalesMappingInterface() 
	{
		$loggedInUserInfo = array();

		$loggedInUserInfo[0]['userid'] = $this->input->post('userid');
		$loggedInUserInfo[0]['displayname'] = $this->input->post('username');
		
		$data = array();
		$data['loggedInUserInfo'] = $loggedInUserInfo;
		
		if(!empty($loggedInUserInfo[0]['userid']))
			$this->load->view('clientSalesMapping',$data);	
		else
			return false;
	}
	
	function mapSalesToClient($salesEmail='',$clientUserId='')
	{
		$this->init();
		
		if(empty($salesEmail) && empty($clientUserId))
			$csvData = false;
		else
			$csvData = true;

		if(empty($salesEmail))
			$salesEmail = $this->input->post('SalesEmail');
		if(empty($clientUserId))
			$clientUserId = $this->input->post('ClientId');
		
		$salesPersonDetails = $this->_model->getSalesPersonDetailsByEmailId($salesEmail);
		$newSalesBy = $salesPersonDetails[0]['SalesBy'];

		if(empty($newSalesBy)){
			if(!$csvData)
				echo "Sales Person Employee ID doesn't exist.";
			return 0;
		}

		$transactionDetails = $this->_model->getClientTransactionDetails($clientUserId);
	    $transactionId = $transactionDetails[0]['TransactionId'];
	    $oldSalesBy = $transactionDetails[0]['oldSalesBy'];

	    if(empty($transactionId)){
	    	if(!$csvData)
				echo "No transaction exists for the specified Client ID.";
			return 0;
		}

	    if(($transactionId > 0) && ($oldSalesBy != $newSalesBy)) {

        	$status = $this->_model->updateSalesPersonCode($transactionId,$oldSalesBy,$newSalesBy);
        	if(!$csvData)
        		echo "Sales Person has been mapped succesfully.";
            return $status;
        }
        if(!$csvData)
        	echo "Sales Person already mapped.";
        return 1;

	}

	function mapSalesToClientUsingCSV()
	{
		$this->init();

		$this->load->library('common/reader');
		include_once(APPPATH.'/modules/Common/common/libraries/PHPExcel/Reader/Excel2007.php');
		$this->load->library('common/PHPExcel/IOFactory');
		
		if(count($_FILES)>0){
			
			if($_FILES['mappingCSV']['error'] == 0){
				
			    $inputFile = $_FILES['mappingCSV']['name'];
				$extension = strtoupper(pathinfo($inputFile, PATHINFO_EXTENSION));
				
				//check the extension of the uploaded file
				if($extension == 'CSV' || $extension == 'XLSX' || $extension == 'XLS'){
					
					$allData = array();
					
					if($extension == "XLSX" || $extension == "XLS"){

						//Read spreadsheeet workbook
						try {
							$inputFile = $_FILES['mappingCSV']['tmp_name'];
							$inputFileType = PHPExcel_IOFactory::identify($inputFile);
							$objReader = PHPExcel_IOFactory::createReader($inputFileType);
							$objPHPExcel = $objReader->load($inputFile);
							
						} catch(Exception $e) {
							die($e->getMessage());
						}

						$total_sheets = $objPHPExcel->getSheetCount(); 
						$allSheetName = $objPHPExcel->getSheetNames();
						$sheet = $objPHPExcel->getSheet(0);												
						$highestRow = $sheet->getHighestRow(); 
						$highestColumn = $sheet->getHighestColumn();
						//Read a row of data into an array
						$rowData = $sheet->rangeToArray('A' . 1 . ':' . $highestColumn . 1, NULL, TRUE, FALSE);
						
						$number_column = 0;

						foreach($rowData[0] as $row) {
							if(!empty($row)) {
								$number_column++;
							}
						}

						if($number_column==2) {
							$highestColumn = 'B';	
						} else {
						    
						    $messagetext="Please check the fields in your file. There should be only 2 fields.";
							$data = array();
							$data['messagetext'] = $messagetext;
							echo $messagetext;
							return;
						}
						
						if($highestColumn == 'B'){
							
							if((strtoupper($rowData[0][0])=="SALES EMAIL") && (strtoupper($rowData[0][1])=="CLIENT ID")){							
								for($row = 2; $row <= $highestRow; $row++){
									$rowDataValues = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
									
									if(empty($rowDataValues[0][0]) || empty($rowDataValues[0][1])) {
										continue;
									}
								
									$allData[$row]['Sales Email'] = $rowDataValues[0][0];
									$allData[$row]['Client ID'] = $rowDataValues[0][1];
									
								}
								
								$number_row = count($allData);
								if($number_row == 0) {
									$messagetext="Please check the fields in your file. Sales Email or Client ID cannot be empty.";
									$data = array();
									$data['messagetext'] = $messagetext;
									echo $messagetext;
									return;
								}

							}else{
								
								$messagetext="Please check the fields in your file. They should be : Sales Email and Client ID.";
								$data = array();
								$data['messagetext'] = $messagetext;
								echo $messagetext;
								return;
							}
						}else{
							
							$messagetext="Please check the fields in your file. There should be only 2 fields.";
							$data = array();
							$data['messagetext'] = $messagetext;
							echo $messagetext;
							return;
						}

					} else if($extension == "CSV"){
						$allData = $this->buildCSVArray($_FILES['mappingCSV']['tmp_name']);
					}
					
					$incorrectData = '';
					foreach ($allData as $key => $data) {

						if(!empty($data['Sales Email']) && !empty($data['Client ID'])) {
							$updateStatus = $this->mapSalesToClient($data['Sales Email'],$data['Client ID']); 
						} else {
							$updateStatus = 0;
						}
						
						if(!$updateStatus){
							$incorrectData.= $data['Sales Email']." - ".$data['Client ID']." | ";
						}
					}
					
					if($incorrectData != '')
						$messagetext = "Please check Sales Email / Client ID for the following : ".$incorrectData;
					else
						$messagetext = "Sales Persons have been mapped succesfully.";

					$data = array();
					$data['messagetext'] = $messagetext;
					echo $messagetext;
					return;

				} else {
					$messagetext="Please upload an XLSX or XLS or CSV file";
					$data = array();
					$data['messagetext'] = $messagetext;
					echo $messagetext;
					return;
				}
			    
			} else {

				$messagetext = "Please select a file.";
				$data = array();
				$data['messagetext'] = $messagetext;
				echo $messagetext;
				return;

			}
			
		} else {

			$messagetext = "Please select a file.";
			$data = array();
			$data['messagetext'] = $messagetext;
			echo $messagetext;
			return;

		}	

	}

	function buildCSVArray($File)
	{
		$handle = fopen($File, "r");
		$fields = fgetcsv($handle, 1000, ",");
		while($data = fgetcsv($handle, 1000, ",")) {
			$detail[] = $data;			
		}
		$x = 0;
		foreach($fields as $z) {
			foreach($detail as $key=>$i) {
				$stock[$key][$z] = $i[$x];
			}
			$x++;
		}
		return $stock;
	}

	function SalesPersonDetailsUpdationScript()
	{
		$this->init();
		$this->benchmark->mark('code_start');
	    error_log("STARTED ########################### ");

	    $this->readData();
	    
	    error_log("ENDED ########################### ");
	    $this->benchmark->mark('code_end');

	    error_log("total time taken to run script ::: ".$this->benchmark->elapsed_time('code_start', 'code_end')."seconds");
	    register_shutdown_function("self::printErrorStack");
	}
    
    public function readData() {

	    $file_name = "/home/mansi/Downloads/FinalData.xlsx";
	    $this->load->library('common/PHPExcel');
	    $objReader = PHPExcel_IOFactory::createReader('Excel2007');
	    $objReader->setReadDataOnly(true);
	    $objPHPExcel = $objReader->load($file_name);
	    $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
	    $count = 0;
	   	$csv = '';
	   	$csv.="NavId,ClientId,NewSalesBy,OldSalesBy\n";
	    for ( $i=2; $i<=23396; $i++ ) {
	    	
	    	error_log("#####Processing Row ".$i);
	    	$navId = $objWorksheet->getCellByColumnAndRow(0,$i)->getValue();
	    	$userId = $objWorksheet->getCellByColumnAndRow(1,$i)->getValue();
	    	$salesPersonId = $objWorksheet->getCellByColumnAndRow(2,$i)->getValue();

	    	if(($userId != '')) {

	    		$transactionDetails = $this->_model->getClientTransactionDetails($userId);
	    		$transactionId = $transactionDetails[0]['TransactionId'];
	            $oldSalesBy = $transactionDetails[0]['oldSalesBy'];

	    	} else {

	    		continue;

	    	}
            
            //$this->_model->saveClientMapping($userId,$navId);

            $newSalesBy = $salesPersonId;

            if(($transactionId > 0) && ($oldSalesBy != $newSalesBy)) {

            	//_P("Row number : ".$i);
            	//_P($navId." \t ".$userId." \t ".$newSalesBy." \t ".$oldSalesBy);
            	$csv.=$navId.",".$userId.",".$newSalesBy.",".$oldSalesBy."\n";
            	$count++;

            	//$this->_model->updateSalesPersonCode($transactionId,$oldSalesBy,$newSalesBy);
                
            } else {

                continue;

            }

	    }
	    //_P("Total rows to be updated : ".$count);

	    $mime = 'text/x-csv';
        $filename = "Data_To_Be_Updated.csv";
       	
        if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE")) {
            header('Content-Type: "' . $mime . '"');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header("Content-Transfer-Encoding: binary");
            header('Pragma: public');
            header("Content-Length: " . strlen($csv));
        } else {
            header('Content-Type: "' . $mime . '"');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header("Content-Transfer-Encoding: binary");
            header('Expires: 0');
            header('Pragma: no-cache');
            header("Content-Length: " . strlen($csv));
        }
        echo ($csv);
	    //_P($csv);
	    return;
    }

    public function updateClientMappingData() {

    	ini_set('memory_limit','256M');
    	$this->init();
		$file_name            = "/tmp/NAV_Client_Mapping.xlsx";
		$this->load->library('common/PHPExcel');
		$objReader            = PHPExcel_IOFactory::createReader('Excel2007');
		$objReader->setReadDataOnly(true);
		$objPHPExcel          = $objReader->load($file_name);
		$objWorksheet         = $objPHPExcel->setActiveSheetIndex(0);
		$validEmailIds        = array();
		$usergroupArr		  = array();
		$invalidEmailIds      = array();
		$invalidNavIds        = array();
		$existingNavUsers     = array();
		$newlyAddedNavUsers   = array();
		$duplicateNavUsers    = array();
		$multipleNavIdMapping = array();
		$wrongNavUserMapping  = array();
		
	    for ( $i=2; $i<=25131; $i++ ) {
	    	
	    	error_log("#####Processing Row ".$i);
			$navId  = $objWorksheet->getCellByColumnAndRow(0,$i)->getValue();
			// $userId = $objWorksheet->getCellByColumnAndRow(1,$i)->getValue();
			$email  = $objWorksheet->getCellByColumnAndRow(2,$i)->getValue();

	    	if(($email != '')) {

	    		$enterpriseUserId = $this->_model->validateClientByEmail($email);
	    		error_log("####enterpriseUserId ".print_r($enterpriseUserId,true));
	    		if(!empty($enterpriseUserId)){
	    			
	    			if($enterpriseUserId['usergroup'] != 'enterprise'){

	    				$usergroupArr[$email] = $enterpriseUserId['usergroup'];

	    			} else {
	    				$invalidFlag = false;
						$validEmailIds[] = $email;
						$navUserMapping  = $this->_model->getNavUseridMapping($navId);
		    			error_log("####navUserMapping ".print_r($navUserMapping,true));
		    			$reverseMapping  = $this->_model->getReverseNavUseridMapping($enterpriseUserId['userid'], $navId);
		    			error_log("####reverseMapping ".print_r($reverseMapping,true));

		    			if(count($navUserMapping) == 1) {

		    				if($navUserMapping[0]['userid'] == $enterpriseUserId['userid']){
		    					$existingNavUsers[$navId] = $enterpriseUserId;
		    					$invalidFlag = true;
		    				} else {
		    					$wrongNavUserMapping[$navUserMapping[0]['userid']] = $navId;
		    					$invalidFlag = true;
		    				}

		    			} else if(count($navUserMapping) > 1) {

		    				$multipleNavIdMapping[] = $navId;
		    				$invalidFlag = true;

		    			} 

		    			if(count($reverseMapping) > 0) {

		    				$duplicateNavUsers[$enterpriseUserId['userid']] = $email;
		    				$invalidFlag = true;

		    			} 

		    			if(!$invalidFlag){

		    				//add Nav-User-Mapping
		    				// $response = $this->_model->insertNavClientMapping($enterpriseUserId['userid'], $navId);
							// $this->load->library(array('Nav_Sums_Integration_client'));
							// $objNav                     = new Nav_Sums_Integration_client();
							// $response                   = $objNav->navUserMapping($this->appId, $enterpriseUserId['userid'], $navId);
							// error_log("####response ".print_r($response,true));
							$newlyAddedNavUsers[$navId] = $enterpriseUserId['userid'];

		    			}

		    		}

	    		} else {
	    			$invalidEmailIds[] = $email;
	    		}
	    		
	    		// die;
	    	} else {

	    		$invalidNavIds[$i] = $navId;

	    	}
	    	unset($navId);
	    	unset($email);
	    	unset($enterpriseUserId);
	    	unset($navUserMapping);
	    	unset($reverseMapping);
	    	unset($response);

	    }
	    _P('invalidNavIds');
	    _P($invalidNavIds);
	    _P('validEmailIds');
	    _P($validEmailIds);
	    _P('usergroupArr');
	    _P($usergroupArr);
	    _P('invalidEmailIds');
	    _P($invalidEmailIds);
	    _P('existingNavUsers');
	    _P($existingNavUsers);
	    _P('wrongNavUserMapping');
	    _P($wrongNavUserMapping);
	    _P('duplicateNavUsers');
	    _P($duplicateNavUsers);
	    _P('multipleNavIdMapping');
	    _P($multipleNavIdMapping);
	    _P('newlyAddedNavUsers');
	    _P($newlyAddedNavUsers);
	    _P('end');
	    unset($invalidNavIds);
    	unset($validEmailIds);
    	unset($usergroupArr);
    	unset($invalidEmailIds);
    	unset($existingNavUsers);
    	unset($duplicateNavUsers);
    	unset($multipleNavIdMapping);
    	unset($newlyAddedNavUsers);die;
	    //_P("Total rows to be updated : ".$count);

    }

}
