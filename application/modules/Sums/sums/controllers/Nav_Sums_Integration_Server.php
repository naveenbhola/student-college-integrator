<?php
/*
   Copyright 2007 Info Edge India Ltd
   $Rev:: 196           $:  Revision of last commit
   $Author: build $:  Author of last commit
 */
class Nav_Sums_Integration_Server extends MX_Controller {
	
	private $_model;
	
	function index(){
		
		$this->dbLibObj = DbLibCommon::getInstance('SUMS');
		
		$this->load->library('xmlrpc');
		$this->load->library('xmlrpcs');
		$this->load->helper('date');
		
		$dbLibObj = DbLibCommon::getInstance('SUMS');
		$dbHandleSums = $dbLibObj->getWriteHandle();
		
		$dbLibObj = DbLibCommon::getInstance('SUMSShiksha');
		$dbHandleShiksha = $this->dbLibObj->getWriteHandle();
		
		$this->load->model('nav_integration_model');
		$this->_model = new nav_integration_model($dbHandleShiksha,$dbHandleSums);
		
		$config['functions']['supdateNavPaymentDetails'] = array('function' => 'Nav_Sums_Integration_Server.supdateNavPaymentDetails');
                $config['functions']['supdateNavRefPaymentDetails'] = array('function' => 'Nav_Sums_Integration_Server.supdateNavRefPaymentDetails');
		$config['functions']['snavUserMapping'] = array('function' => 'Nav_Sums_Integration_Server.snavUserMapping');
		$config['functions']['saddNAVPayments'] = array('function' => 'Nav_Sums_Integration_Server.saddNAVPayments');
		$config['functions']['saddTransaction'] = array('function' => 'Nav_Sums_Integration_Server.saddTransaction');
		$config['functions']['supdateDues'] = array('function' => 'Nav_Sums_Integration_Server.supdateDues');
		$config['functions']['supdatePaymentDetails'] = array('function' => 'Nav_Sums_Integration_Server.sUpdatePaymentDetails');
		$config['functions']['saddEnterpriseUser'] = array('function' => 'Nav_Sums_Integration_Server.saddEnterpriseUser');
		$config['functions']['updateEnterpriseUserDetails'] = array('function' => 'Nav_Sums_Integration_Server.updateEnterpriseUserDetails');
		$config['functions']['updateSalesPersonDetails'] = array('function' => 'Nav_Sums_Integration_Server.updateSalesPersonDetails');

		$config['functions']['getEnterpriseUsers'] = array('function' => 'Nav_Sums_Integration_Server.getEnterpriseUsers');
		$config['functions']['getNewOnlinePayments'] = array('function' => 'Nav_Sums_Integration_Server.getNewOnlinePayments');
		$config['functions']['sgetCities'] = array('function' => 'Nav_Sums_Integration_Server.sgetCities');
		$config['functions']['saddNAVXML'] = array('function' => 'Nav_Sums_Integration_Server.saddNAVXML');
		$config['functions']['supdateNAVXML'] = array('function' => 'Nav_Sums_Integration_Server.supdateNAVXML');

		$config['functions']['getCronData'] = array('function' => 'Nav_Sums_Integration_Server.getCronData');
		$config['functions']['registerCron'] = array('function' => 'Nav_Sums_Integration_Server.registerCron');
		$config['functions']['updateCron'] = array('function' => 'Nav_Sums_Integration_Server.updateCron');
		$config['functions']['getUpdatedUsers'] = array('function' => 'Nav_Sums_Integration_Server.getUpdatedUsers');
		$config['functions']['createSubscription'] = array('function' => 'Nav_Sums_Integration_Server.createSubscription');
                $config['functions']['supdateNavPaymentReversal'] = array('function' => 'Nav_Sums_Integration_Server.supdateNavPaymentReversal');
		
		$args = func_get_args(); $method = $this->getMethod($config,$args);
		return $this->$method($args[1]);
	}
	
	private function getDBHandle($sums = FALSE)
	{
		if($sums) {
			$this->dbLibObj = DbLibCommon::getInstance('SUMS');
			return $this->dbLibObj->getWriteHandle();
		}
		else {
			$this->dbLibObj = DbLibCommon::getInstance('SUMSShiksha');
			return $this->dbLibObj->getWriteHandle();
		}
	}
	
	/**
	 * Web service to update nav mapping
	 */
	function snavUserMapping($request)
	{
        $parameters   = $request->output_parameters();
        $appId        = $parameters['0'];
        $userid       = $parameters['1'];
        $navcompanyid = $parameters['2'];
		//connect DB
		$dbHandle = $this->getDBHandle(TRUE);
		if($dbHandle == ''){
			log_message('error',' can not create db handle');
		}
        $details['userid']       = $userid;
        $details['Nav_ClientId'] = $navcompanyid;
        $queryCmd                = $dbHandle->insert_string('Nav_Userid_Mapping',$details);
        $query                   = $dbHandle->query($queryCmd);
        $response                = array("true",'string');
		return $this->xmlrpc->send_response($response);
	}
	function saddNAVPayments($request)
	{
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$transactionId = $parameters['1'];
		$paymentInfo = $parameters['2'];
		$paymentDetails = $parameters['3'];
		//connect DB
		$details = array();
		$dbHandle = $this->getDBHandle(TRUE);
		if($dbHandle == ''){
			log_message('error',' can not create db handle');
		}
		//$paymentInfo['Transaction_Id'] = $transactionId;
                
                $query = "select Payment_Id from Payment where Transaction_Id = ?";
                $results = $dbHandle->query($query, array($transactionId));
                $numRows = $results->num_rows;
                if($numRows){
                    foreach ($results->result_array() as $result){
                        $paymentId = $result['Payment_Id'];
                        
                        foreach ($paymentDetails as $details)
                        {
                            $query = "select 1 from Payment_Details where Payment_Id=? and Due_date =?";
                            $isAlreadyExist = $dbHandle->query($query, array($paymentId, $details['Due_Date']))->num_rows;

                            if(!$isAlreadyExist){

                                $query = "select max(Part_Number) as partNo from Payment_Details where Payment_Id=?";
                                $result = $dbHandle->query($query, array($paymentId))->result_array();
                                $partNumberMax = $result[0]['partNo'];
                                $details['Payment_Id'] = $paymentId;
                                $details['Part_Number'] = ++$partNumberMax;
                                $details['loggedInUserId'] = '2492';
                                $details['Payment_Mode'] = 'Credit Card(Offline)';

                                $queryCmd = $dbHandle->insert_string('Payment_Details',$details);
                                $insResult = $dbHandle->query($queryCmd);
                                
                            }
                        }
                    }
                }else{
                    $queryCmd = $dbHandle->insert_string('Payment',$paymentInfo);
                    //error_log("AAA Subscription Server : ".print_r($paymentInfo,true));
                    $dbHandle->query($queryCmd);
                    $paymentId = $dbHandle->insert_id();
                    $i=1;
                    //error_log("Subscription Server : ".print_r($paymentDetails[0],true));
                    foreach ($paymentDetails as $details)
                    {

                            $details['Payment_Id'] = $paymentId;
                            $details['Part_Number'] = $i;
                            $details['loggedInUserId'] = '2492';
                            $details['Payment_Mode'] = 'Credit Card(Offline)';
                            $queryCmd = $dbHandle->insert_string('Payment_Details',$details);
                            error_log("SS  Server : ".$queryCmd);
                            $query = $dbHandle->query($queryCmd);
                            
                            if(isset($details['nav_rcpt_id']) && !empty($details['nav_rcpt_id']) && $details['nav_rcpt_id'] != NULL){
                                $insertData = array();
                                $insertData['Payment_Id'] = $paymentId;
                                $insertData['Part_Number'] = $i;
                                $insertData['nav_rcpt_id'] = $details['nav_rcpt_id'];
                                $insertData['Amount_Received'] = $details['Amount_Received'];

                                $insertQuery = $dbHandle->insert_string('payment_record',$insertData);
                                $dbHandle->query($insertQuery);
                            }
                            
                            $i++;
                    }
                }
                
		$response = array(array
				('TransactionId'=>$transactionId,
				 'PaymentId'=>$paymentId
				),
				'struct'
				);
		return $this->xmlrpc->send_response($response);
	}
        
        /**
         * Web service to update  Payment details
         */
        function supdateNavPaymentDetails($request)
        {
            $parameters = $request->output_parameters();
            $appId = $parameters['0'];
            $TransactionId  = $parameters['1'];
            $amount  = $parameters['2'];
            $updatearray = $parameters['3'];
            $Due_dt = $parameters['4'];
            
            $dueAmount = $updatearray['DueAmount'];
            
            //connect DB
            $dbHandle = $this->getDBHandle(TRUE);
            if($dbHandle == ''){
                    log_message('error',' can not create db handle');
            }

            /* Start of Update into Payment  */        
            $setstr = "";
            foreach($updatearray as $key=>$value)
            {
                    $setstr .= $key ." = '". $value."',";
            }
            $setstr = substr($setstr,0,strlen($strstr)-1);

            if($Due_dt){
                $queryCmdSelect = "select * from Payment_Details,Payment where Payment_Details.Payment_Id = Payment.Payment_Id and Due_date = ".$dbHandle->escape($Due_dt)." and Transaction_Id = ?";
            }else{
                $queryCmdSelect = "select * from Payment_Details,Payment where Payment_Details.Payment_Id = Payment.Payment_Id and Transaction_Id = ?";
            }
            $result = $dbHandle->query($queryCmdSelect, array($TransactionId));
            $numRows = $result->num_rows;
            
            if($numRows){
                $result = $result->result_array();
                $insertData = array();
                $insertData['Payment_Id'] = $result[0]['Payment_Id'];
                $insertData['Part_Number'] = $result[0]['Part_Number'];
                $insertData['nav_rcpt_id'] = $updatearray['nav_rcpt_id'];
                $insertData['Amount_Received'] = $amount - $result[0]['Amount_Received'];

                if($Due_dt){
                    $queryCmd = "update Payment_Details,Payment set $setstr,isPaid = IF(($dueAmount) > 0, 'Un-Paid', 'Paid') where Payment_Details.Payment_Id = Payment.Payment_Id and Due_date = ".$dbHandle->escape($Due_dt)." and isPaid = 'Un-Paid' and Transaction_Id = ?;";
                }else{
                    if($result[0]['Sale_Type'] == 'Upfront'){
                        $queryCmd = "update Payment_Details,Payment set $setstr,isPaid = IF(($dueAmount) > 0, 'Un-Paid', 'Paid') where Payment_Details.Payment_Id = Payment.Payment_Id and isPaid = 'Un-Paid' and Transaction_Id = ?;";
                    }else{
                        return $this->xmlrpc->send_response(1);
                    }
                }

                $query = $dbHandle->query($queryCmd, array($TransactionId));

                if($dbHandle->affected_rows() == 1){
                    $response = 1;
                    $insertQuery = $dbHandle->insert_string('payment_record',$insertData);
                    $dbHandle->query($insertQuery);
                }else{
                    $response = 0;
                }
            }else{
                $response = 0;
            }

            return $this->xmlrpc->send_response($response);
        }
        
        function supdateNavRefPaymentDetails($request) {
            $parameters = $request->output_parameters();
            $appId = $parameters['0'];
            $TransactionId  = $parameters['1'];
            $updatearray = $parameters['2'];
            
            $receivedAmount = $updatearray['paid_amt'];
            $dueAmount = $updatearray['due_amt'];
            
            //connect DB
            $dbHandle = $this->getDBHandle(TRUE);
            if($dbHandle == ''){
                    log_message('error',' can not create db handle');
            }
            
            $remainingAmount = $receivedAmount;
            $queryCmdSelect = "select * from Payment_Details,Payment where Payment_Details.Payment_Id = Payment.Payment_Id and Transaction_Id = ?";
            $results = $dbHandle->query($queryCmdSelect, array($TransactionId));
            
            foreach ($results->result_array() as $result) {
                if($remainingAmount > 0){
                    $paymentId = $result['Payment_Id'];
                    $partNumber = $result['Part_Number'];
                    $amountReceieved = $result['Amount_Received'];
                    $amountDue = $result['DueAmount'];
                    $isPaid = $result['isPaid'];
                    
                    if($isPaid == 'Paid'){
                        continue;
                    }
                    
                    if($remainingAmount >= $amountDue){
                        $partAmountCredited = $amountDue;
                    }else{
                        $partAmountCredited = $remainingAmount;
                    }

                    $queryCmd = "update Payment_Details set Amount_Received = (Amount_Received + $partAmountCredited), DueAmount = (DueAmount - $partAmountCredited), isPaid = IF((DueAmount - $partAmountCredited) > 0, 'Un-Paid', 'Paid') where Payment_Id = ? and Part_Number = ?";
                    $dbHandle->query($queryCmd, array($paymentId, $partNumber));
                    
                    $insertData = array();
                    $insertData['Payment_Id'] = $paymentId;
                    $insertData['Part_Number'] = $partNumber;
                    $insertData['nav_rcpt_id'] = $updatearray['nav_rcpt_id'];
                    $insertData['Amount_Received'] = $partAmountCredited;
                    
                    $insertQuery = $dbHandle->insert_string('payment_record',$insertData);
                    $dbHandle->query($insertQuery);
                    
                    $remainingAmount -= $partAmountCredited;
                }
            }
            return $this->xmlrpc->send_response(1);
        }
        
        /**
         * Web service to handle Payment reversal 
         */
        function supdateNavPaymentReversal($request)
        {
            $parameters = $request->output_parameters();
            $appId = $parameters['0'];
            $amount  = $parameters['1'];
            $navRcptId = $parameters['2'];

            //connect DB
            $dbHandle = $this->getDBHandle(TRUE);
            if($dbHandle == ''){
                    log_message('error',' can not create db handle');
            }

            $queryCmdSelect = "select * from payment_record where nav_rcpt_id = ?";
            $results = $dbHandle->query($queryCmdSelect, array($navRcptId))->result_array();

            $remainingAmount = $amount;
            foreach ($results as $result) {
                if($remainingAmount > 0 && $remainingAmount >= $result['Amount_Received']){
                    $paymentId = $result['Payment_Id'];
                    $partNumber = $result['Part_Number'];
                    $amountReversing = $result['Amount_Received'];

                    $queryCmd = "update Payment_Details set Amount_Received = (Amount_Received - ?), DueAmount = (DueAmount + ?), isPaid = 'Un-Paid' where Payment_Id = ? and Part_Number = ?";
                    $query = $dbHandle->query($queryCmd, array((int)$amountReversing, (int)$amountReversing, $paymentId, $partNumber));
                    $remainingAmount -= $result['Amount_Received'];
                }

            }

            return $this->xmlrpc->send_response(1);
        }
                
	function saddNAVXML($request)
	{
        $parameters       = $request->output_parameters();
        $appId            = $parameters['0'];
        $navtransactionId = $parameters['1'];
        $xmltype          = $parameters['2'];
        $xml              = $parameters['3'];
        $status           = $parameters['4'];
		//connect DB
		$details = array();
		$dbHandle = $this->getDBHandle(TRUE);
		if($dbHandle == ''){
			log_message('error',' can not create db handle');
		}

        $xmlafterdecode               = html_entity_decode($xml);
        $details['XML_TransactionId'] = $navtransactionId;
        $details['XMLType']           = $xmltype;
        $details['XML']               = $xmlafterdecode;
        $details['XmlStatus']         = $status;
        $queryCmd = $dbHandle->insert_string('NAV_Xml_Logs',$details);
        $query    = $dbHandle->query($queryCmd);

		$Id = $dbHandle->insert_id();
		$response = array(array
				('primaryId'=>$Id,

				),
				'struct'
				);
		return $this->xmlrpc->send_response($response);


	}


	function supdateNAVXML($request)
	{
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$xmlresponse  = $parameters['1'];
		$id  = $parameters['2'];
		$status = $parameters['3'];

        if(empty($status)) {
			$status = "success";
		}
		//connect DB
		$dbHandle = $this->getDBHandle(TRUE);
		if($dbHandle == ''){
			log_message('error',' can not create db handle');
		}

		$xmlafterdecode = html_entity_decode($xmlresponse);
		$queryCmd = 'update NAV_Xml_Logs set XMLResponse = ?,XmlStatus = ? where id = ?';
		$query = $dbHandle->query($queryCmd, array($xmlafterdecode,$status,$id));   


		if($dbHandle->affected_rows() == 1) {
			$response = 1;
		} else { 	
			$response = 0;
		}
			
		return $this->xmlrpc->send_response($response);

	}

			
	/**
	 * API to Add a Tranasaction, 
	 *
	 */
	function saddTransaction($request){
        $parameters         = $request->output_parameters();
        $appId              = $parameters['0'];
        $transactParams     = $parameters['1']; // All Transaction Parameters
        $clientUserId       = $transactParams['clientUserId'];
        $sumsUserId         = $transactParams['sumsUserId'];
        $salesPersonName    = $transactParams['SalesBy'];
        $salesBranch        = $transactParams['SalesBranch'];
        $UIQuotationId      = $transactParams['UIQuotationId'];
        $salesAmount        = $transactParams['FinalSalesAmount'];
        $currencyId         = $transactParams['CurrencyId'];
        $nav_transaction_id = $transactParams['nav_transaction_id'];
        $company_id         = $transactParams['company_id'];
		
		//connect DB
		$dbHandle = $this->getDBHandle(TRUE);
		if($dbHandle == ''){
			log_message('error',' can not create db handle');
		}
                
                $query   = "SELECT TransactionId FROM Transaction WHERE nav_transaction_id = ?;";
                $queryR  = $dbHandle->query($query, array($nav_transaction_id));
                $numRows = $queryR->num_rows;
                if($numRows){
                    
                    foreach ($queryR->result_array() as $result){
                        $transactionId = $result['TransactionId'];
                    }
                    
                }else{
		
                    if(empty($clientUserId)){

                            $query = "SELECT userid FROM Nav_Userid_Mapping WHERE Nav_ClientId = ?;";
                            $query = $dbHandle->query($query, array($company_id));
                            $userids = $query->result_array();
                            $clientUserId = $userids[0]['userid'];
                    }


                    $transData =array();

                    if(!empty($clientUserId)) {
		    	        
                        $transData = array(
                                'ClientUserId'          => $clientUserId,
                                'SumsUserId'            => $sumsUserId,
                                'UIQuotationId'         => $UIQuotationId,
                                'TotalTransactionPrice' => $salesAmount,
                                'CurrencyId'            => $currencyId,
                                'SalesBy'               => $salesPersonName,
                                'SalesBranch'           => $salesBranch,
                                'nav_transaction_id'    => $nav_transaction_id
                        );

                        $queryCmd = $dbHandle->insert_string('Transaction',$transData);
                        error_log('ADD Transaction query cmd is: '.$queryCmd);
                        $query = $dbHandle->query($queryCmd);
                        $transactionId = $dbHandle->insert_id();

                    }
                }
		
		$response = array(array
                (
                    'TransactionId' =>$transactionId,
                    'clientId'      =>$clientUserId
                ),
			'struct'
		);
        if($transactionId == 0 || $clientUserId == 0){
            mail('naveen.bhola@shiksha.com,mansi.gupta@shiksha.com,somendra.verma@naukri.com',"Add Transaction Incorrect data"," TransactionId : ".$transactionId."\n ClientUserId : ".$clientUserId."\n NavTransactionId : ".$nav_transaction_id.'<br/>All POST Data : '.print_r($_POST, true).'<br/>All Input Data : '.print_r($parameters, true));
        }

		error_log("ADD Transaction Server RESPONSE: ".print_r($response,true));
		return $this->xmlrpc->send_response($response);
	}
	
	/**
	* API to create Subscription
	*/
	function createSubscription($request)
	{
        $parameters               = $request->output_parameters();
        $transactionId            = $parameters['1'];
        $subscriptionStartDate    = $parameters['2'];
        $opsUserId                = $parameters['3'];
        $DerivedProductId         = $parameters['4'];
        $clientuserid             = $parameters['5'];
        $TotalBaseQuantity        = $parameters['6'];
        $subscriptionEndDate      = $parameters['7'];
        $nav_subscription_line_no = $parameters['8'];
        $currency                 = $parameters['9'];
        $subscription             = $parameters['10'];
        $newProductFlag           = $parameters['11'];
        $baseProdId               = $parameters['12'];
        $company_id               = $parameters['13'];

	    $dbHandle = $this->getDBHandle(TRUE);

	    $goldSLDerivedProductIds = array(
                        10744,
                        10729,
                        10745,
                        10730,
                        10746,
                        10731,
                        10747,
                        10732,
                        10748,
                        10733,
                        10749,
                        10734,
                        10750,
                        10735,
                        10751,
                        10736,
                        10752,
                        10737,
                        10753,
                        10738,
                        10754,
                        10739,
                        10755,
                        10740,
                        10756,
                        10741,
                        10757,
                        10742,
                        10758,
                        10743
                );

		$goldMLDerivedProductIds = array(
                        10827,
                        10792,
                        10829,
                        10794,
                        10797,
                        10795,
                        10798,
                        10828,
                        10793,
                        10796,
                        10830,
                        10799,
                        10832,
                        10801,
                        10804,
                        10802,
                        10805,
                        10831,
                        10800,
                        10803,
                        10833,
                        10806,
                        10835,
                        10808,
                        10811,
                        10809,
                        10812,
                        10834,
                        10807,
                        10810,
                        10836,
                        10813,
                        10838,
                        10815,
                        10818,
                        10816,
                        10819,
                        10837,
                        10814,
                        10817,
                        10839,
                        10820,
                        10841,
                        10822,
			            10825,
                        10824,
                        10826,
                        10840,
                        10821,
                        10823
                );
		
		$searchPageDerivedProductsList = array(
			10582,
			10583,
			10584,
			10585,
			10586,
			10587,
			10588,
			10589,
			10590,
			10591,
			10592,
			10593,
			10594,
			10595,
			10596,
			10597,
			10598,
			10599,
			10600,		
			10601,
			10602,
			10603,
			10604,
			10605,
			10606
		);
 
        if($baseProdId != '') {
            $baseproductids = array(array('BaseProductId' => $baseProdId));
        }
        else if(in_array($DerivedProductId,$goldSLDerivedProductIds)) {
            $baseproductids = array(array('BaseProductId' => 1));
        }
        else if(in_array($DerivedProductId,$goldMLDerivedProductIds)) {
            $baseproductids = array(array('BaseProductId' => 375));
        }
        else {
            $queryCmd = "SELECT `BaseProductId`,BaseProdQuantity FROM `Derived_Products_Mapping` WHERE CurrencyId = ? and `DerivedProductId` =?";
            $query = $dbHandle->query($queryCmd, array($currency, $DerivedProductId));
            $baseproductids = $query->result_array();
        }	
 
	    $subsStartDate = date('Y-m-d H:i:s', strtotime($subscriptionStartDate));
        if ($subscriptionEndDate == '0000-00-00')
           $subsEndDate = $subscriptionEndDate;
        else
	       $subsEndDate = date('Y-m-d H:i:s', strtotime($subscriptionEndDate));
        
		error_log("baseproductids".print_r($baseproductids,true));

        if(empty($clientuserid)){
            $query = "SELECT userid FROM Nav_Userid_Mapping WHERE Nav_ClientId = ?;";
            $query = $dbHandle->query($query, array($company_id));
            $userids = $query->result_array();
            $clientuserid = $userids[0]['userid'];
        }

	    $subscriptionsArr = array();
		$data = array();
        
	    for($i = 0;$i < count($baseproductids); $i++){

            $data['TransactionId']            = $transactionId;
            $data['ClientUserId']             = $clientuserid;
            $data['SumsUserId']               = $opsUserId;
            $data['BaseProductId']            = $baseproductids[$i]['BaseProductId'];
            $data['DerivedProductId']         = $DerivedProductId;
            $data['nav_subscription_line_no'] = $nav_subscription_line_no;

            if($subscriptionEndDate == '0000-00-00'){
                $data['SubscrStatus'] = "INACTIVE";
            } else {
                $data['SubscrStatus'] = "ACTIVE";
            }
		    
		    $data['NavSubscriptionId'] = $subscription;

            if($transactionId == 0 || $clientuserid == 0){
                mail('naveen.bhola@shiksha.com,mansi.gupta@shiksha.com,somendra.verma@naukri.com',"Add Subscription Incorrect data"," TransactionId : ".$transactionId."\n ClientUserId : ".$clientUserId."\n NavTransactionId : ".$subscription.'<br/>All POST Data : '.print_r($_POST, true).'<br/>All Input Data : '.print_r($parameters, true));
            }

		    $queryCmd = $dbHandle->insert_string('Subscription',$data);

		    error_log_shiksha("Subscription Table : ".$queryCmd);
		    $dbHandle->query($queryCmd);
		    $subscriptionId = $dbHandle->insert_id();
		    array_push($subscriptionsArr,$subscriptionId);

		    $mapData['SubscriptionId'] = $subscriptionId;
		    $mapData['BaseProductId'] = $baseproductids[$i]['BaseProductId'];
		    
		    if (in_array($DerivedProductId,$searchPageDerivedProductsList) && $mapData['BaseProductId'] == 1)
		    {
				$mapData['TotalBaseProdQuantity'] = $TotalBaseQuantity/20;
				$mapData['BaseProdRemainingQuantity'] = $TotalBaseQuantity/20;
				$mapData['BaseProdPseudoRemainingQuantity'] = $TotalBaseQuantity/20;
		    }
		    else if ( $newProductFlag == 'Yes' && ($mapData['BaseProductId'] != 1 && $mapData['BaseProductId'] != 375) ) 
            {
                $mapData['TotalBaseProdQuantity'] = 1;
                $mapData['BaseProdRemainingQuantity'] = 1;
                $mapData['BaseProdPseudoRemainingQuantity'] = 1;
            } 
            else
		    {
				$mapData['TotalBaseProdQuantity'] = $TotalBaseQuantity;
				$mapData['BaseProdRemainingQuantity'] = $TotalBaseQuantity;
				$mapData['BaseProdPseudoRemainingQuantity'] = $TotalBaseQuantity;
		    }
		    
		    $mapData['SubscriptionStartDate'] = $subsStartDate;
		    $mapData['SubscriptionEndDate'] = $subsEndDate;
		    $mapData['SubscrLastModifyTime'] = standard_date('DATE_ATOM',time());

            if($subscriptionEndDate == '0000-00-00'){
                $mapData['Status'] = "INACTIVE";
            } else {
                $mapData['Status'] = "ACTIVE";
            }
           
		    $queryCmd = $dbHandle->insert_string('Subscription_Product_Mapping',$mapData);
		    $dbHandle->query($queryCmd);
	    }


	    $response = array("TransactionId"=>$transactionId ,"Created_Subscriptions"=>$subscriptionsArr);
	    $resp = array($response,'struct');
	    return $this->xmlrpc->send_response ($resp);
	    }
	
	
	
	
	function getUpdatedUsers($request)
	{
		$parameters = $request->output_parameters();
		
		$results = $this->_model->getupdatedUsers();
		
		$response_string = base64_encode(gzcompress(json_encode($results)));
		$response = array($response_string,'string');
		return $this->xmlrpc->send_response($response);
	}
		
	function supdateDues($request)
	{
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		
		$TransactionId  = $parameters['1'];
		$Cheque_Date  = $parameters['2'];
		$updatearray = $parameters['3'];
		$Due_dt = $parameters['4'];
		//connect DB
		$dbHandle = $this->getDBHandle(TRUE);
		if($dbHandle == ''){
			log_message('error',' can not create db handle');
		}
		
		/* Start of Update into Payment  */        
		$setstr = "";
		foreach($updatearray as $key=>$value)
		{
			$setstr .= $key ." = '". $value."',";
		}
		$setstr = substr($setstr,0,strlen($strstr)-1);
		
				
		$queryCmd = "update Payment_Details,Payment set $setstr where Payment_Details.Payment_Id = Payment.Payment_Id and isPaid = 'Un-Paid' and  DATE(Due_date) = ? and Transaction_Id = ?;";
		$query = $dbHandle->query($queryCmd, array($Due_dt, $TransactionId));                        
		if($dbHandle->affected_rows() == 1)
			$response = 1;
		else
			$response = 0;
		return $this->xmlrpc->send_response($response);
	}

	
	
	//Server API for Enterprise User Registration
	function saddEnterpriseUser($request)
	{
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$userData = $parameters['1'];

	//	$appId = $userData['appId'];
		$userid = $userData['userid'];
		$sumsUserId = $userData['sumsUserId'];
		$busiCollegeName = $userData['busiCollegeName'];
		$busiType = $userData['busiType'];
		$contactAddress = $userData['contactAddress'];
		$pincode = $userData['pincode'];
		$categories = $userData['categories'];
		$executiveName = $userData['executiveName'];
		//connect DB

		$dbHandle = $this->getDBHandle();

		$data = array (
				"userId"=>$userid,
				"businessCollege"=>$busiCollegeName,
				"businessType"=>$busiType,
				"contactAddress"=>$contactAddress,
				"pincode"=>$pincode,
				"categories"=>$categories,
				"executiveName"=>$executiveName
			      );
		$queryCmd = $dbHandle->insert_string('enterpriseUserDetails',$data);
		error_log($queryCmd);
		$query = $dbHandle->query($queryCmd);


		$response = array($respSubs,'struct');
		error_log(print_r($response,true).'ReSPONSE');
		return $this->xmlrpc->send_response($response);
	}
	
	function updateEnterpriseUserDetails($request)
	{
			$parameters = $request->output_parameters();
			$vals = $parameters['1'];
			$userId = $parameters['2'];
			error_log("UPDATE user form array ".print_r($vals,true));
			
			$where = "userid= ".$userId;
			$dbHandle = $this->getDBHandle();
			$data = array(
					
					'contactName'=>$vals['contactName'],
					'contactAddress'=>$vals['contactAddress'],
					'categories'=>$vals['categories'], 					
					'businessCollege'=>$vals['businessCollege'],
					'pincode' => $vals['pincode'],
					'businessType' => $vals['businessType']
					
				);
			$queryCmd = $dbHandle->update_string('enterpriseUserDetails',$data,$where);
			error_log("Edit Enterprise Profile :".$queryCmd);
			$query = $dbHandle->query($queryCmd);
			$response = array("query"=>array($query,'int'));
			return $this->xmlrpc->send_response($response);
			
        }

        function updateSalesPersonDetails($request)
        {
            $parameters = $request->output_parameters();
            $executeFieldMap = $parameters['1'];
            
            $dbHandle = $this->getDBHandle(TRUE);

            $navClientId = $executeFieldMap['Nav_ClientId'];
            $newSalesBy = $executeFieldMap['SalesBy'];
            
            $queryCmd = "SELECT a.TransactionId, a.ClientUserId, a.SalesBy as oldSalesBy 
                                    FROM Transaction a, Nav_Userid_Mapping b 
                                    WHERE Nav_ClientId = ? 
                                    AND b.userid = a.ClientUserId
                                    ORDER BY TransactionId DESC LIMIT 1";

            $query = $dbHandle->query($queryCmd,array($navClientId));
            $resultTransaction = $query->result_array();

            $transactionId = $resultTransaction[0]['TransactionId'];
            $oldSalesBy = $resultTransaction[0]['oldSalesBy'];
            $clientUserId = $resultTransaction[0]['ClientUserId'];

            $data = array( 'SalesBy' => $newSalesBy );

            //$where = " TransactionId = ".$transactionId." AND ClientUserId = ".$clientUserId;

            if($transactionId > 0) {

                $where = " TransactionId = ".$transactionId;
                $queryCmd = $dbHandle->update_string('Transaction',$data,$where);
                
                $query = $dbHandle->query($queryCmd);

                $insertData = array(
                        'TransactionId' => $transactionId,
                        'OldSalesBy' => $oldSalesBy,
                        'SalesBy' => $newSalesBy
                        );

                if($oldSalesBy != $newSalesBy){
                    $queryCmdInsert = $dbHandle->insert_string('updateContactCardTracking',$insertData);
                    $queryInsert = $dbHandle->query($queryCmdInsert);
                }
               
                
                $response = array("query"=>array($query,'int'));

                return $this->xmlrpc->send_response($response);

            } else {
                return;
            }
            
        }
	
	
	function supdatePaymentDetails($request)
	{
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$paymentid  = $parameters['1'];
		$partpaymentid  = $parameters['2'];
		$creditCardTableKey  = $parameters['3'];
		
		//connect DB
		$dbHandle = $this->getDBHandle(TRUE);
		if($dbHandle == ''){
			log_message('error',' can not create db handle');
		}
		
		/* Start of Update into Payment  */   
		
		$queryCmd = "update CreditCardLogs set Partial_Payment_Acknowledgement = 'Nav_Acknowledged' where id = ?";
		
		$query = $dbHandle->query($queryCmd, array($creditCardTableKey));                        
		if($dbHandle->affected_rows() == 1)
			$response = 1;
		else
			$response = 0;
		return $this->xmlrpc->send_response($response);
		
	}
	
	function getEnterpriseUsers($request)
	{
		$parameters = $request->output_parameters();
		$criteria = $parameters[0];
		$timeWindow = $parameters[1];
		
		$results = $this->_model->getEnterpriseUsers($criteria,$timeWindow);
		
		$response_string = base64_encode(gzcompress(json_encode($results)));
		$response = array($response_string,'string');
		return $this->xmlrpc->send_response($response);
	}
	
	function getNewOnlinePayments($request)
	{
		$parameters = $request->output_parameters();
		$timeWindow = $parameters[0];
		
		$results = $this->_model->getNewOnlinePayments($timeWindow);
		
		$response_string = base64_encode(gzcompress(json_encode($results)));
		$response = array($response_string,'string');
		return $this->xmlrpc->send_response($response);
	}
	
	/*
	 * Cron management functions
	 */ 
	function getCronData($request)
	{
		$alreadyRunningCronStatus = 'NO';
		$alreadyRunningCronPid = 0;
		$failCount = 0;
		$lastProcessedTimeWindow = '';
		$alreadyRunningCron = $this->_model->getAlreadyRunningCron();

		if($alreadyRunningCron) {
			$alreadyRunningCronId = $alreadyRunningCron->id;
			$alreadyRunningCronPid = $alreadyRunningCron->pid;		
		
			$alreadyRunningCronStatus = 'YES';
			$failCount = $this->_model->getCronFailCount($alreadyRunningCronId);
			
			if($failCount >= NAV_MAX_CRON_FAIL_COUNT) {
				$lastProcessedTimeWindow = $this->_model->getLastProcessedTimeWindow();
			}
		}
		else  {
			$lastProcessedTimeWindow = $this->_model->getLastProcessedTimeWindow();
		}
		
		$response = array(
							'alreadyRunningCronStatus' => $alreadyRunningCronStatus,
							'alreadyRunningCronPid' => $alreadyRunningCronPid,
							'failCount' => $failCount,
							'lastProcessedTimeWindow' => $lastProcessedTimeWindow
						);
		
		$response_string = base64_encode(gzcompress(json_encode($response)));
		$response = array($response_string,'string');
		return $this->xmlrpc->send_response($response);				
	}
	
	function registerCron($request)
	{
		$parameters = $request->output_parameters();
		
		$cronPid = (int) $parameters[0];
		$status = $parameters[1];
		$ipAddress = $parameters[2];
		
		/*
		 * Register cron
		 */
		if($cronId = $this->_model->registerCron($cronPid,$status,$ipAddress)) {
			$response = array($cronId,'string');
			return $this->xmlrpc->send_response($response);
		}
		else  {
			return $this->xmlrpc->send_error_message('700', 'Unable to register cron (DB error)');
		}
	}
	
	function updateCron($request)
	{
		$parameters = $request->output_parameters();
		
		$cronId = (int) $parameters[0];
		$status = $parameters[1];
		$timeWindow = $parameters[2];
		$stats = utility_decodeXmlRpcResponse($parameters[3]);
		$this->_model->updateCron($cronId,$status,$timeWindow,$stats);
		$response = array('success','string');
		return $this->xmlrpc->send_response($response);
	}

	function sgetCities($request)
	{
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		
		//connect DB

		$dbHandle = $this->getDBHandle();

		
		$query = 'SELECT city_id,city_name from countryCityTable C  WHERE C.city_name is not null and C.city_name !="" and C.enabled !=1 order by city_name';
	
			
		$arrResults = $dbHandle->query($query);

		$msgArray=array();
		foreach ($arrResults->result_array() as $row)
		{
			array_push($msgArray,array($row,'struct'));
		}
		
		
		$response = strtr(base64_encode(addslashes(gzcompress( json_encode($msgArray) , 9))) , '+/=', '-_,');
		return $this->xmlrpc->send_response($response);
		
	}

}
