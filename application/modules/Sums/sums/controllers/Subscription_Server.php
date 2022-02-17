<?php

/*

 Copyright 2007 Info Edge India Ltd

 $Rev:: 196           $:  Revision of last commit
 $Author: build $:  Author of last commit
 $Date: 2009-11-04 06:27:50 $:  Date of last commit


 */
/**
 * Class for Sums Subscription Web-Services
 *
 */

class Subscription_Server extends MX_Controller {

    private $db;
    private $db_sums;
    
    function index(){

        $this->load->library('xmlrpc');
        $this->load->library('xmlrpcs');
        $this->load->library(array('sums_quotation_client','subscription_client','sums_manage_client'));
        $this->load->helper('date');
        $config['functions']['saddTransaction'] = array('function' => 'Subscription_Server.saddTransaction');
        $config['functions']['saddPaymentInfo'] = array('function' => 'Subscription_Server.saddPaymentInfo');
        $config['functions']['getApprover'] = array('function' => 'Subscription_Server.getApprover');
        $config['functions']['insertTransactionQueue'] = array('function' => 'Subscription_Server.insertTransactionQueue');
        $config['functions']['searchTransaction'] = array('function' => 'Subscription_Server.searchTransaction');
        $config['functions']['sgetPaymentInfo'] = array('function' => 'Subscription_Server.sgetPaymentInfo');
        $config['functions']['sConsumeSubscription'] = array('function' => 'Subscription_Server.sConsumeSubscription');
        $config['functions']['sGetSubscriptionDetails'] = array('function' => 'Subscription_Server.sGetSubscriptionDetails');
        $config['functions']['createSubscription'] = array('function' => 'Subscription_Server.createSubscription');
        $config['functions']['deletePaymentInfo'] = array('function' => 'Subscription_Server.deletePaymentInfo');
        $config['functions']['saddFreeSubscription'] = array('function' => 'Subscription_Server.saddFreeSubscription');
        $config['functions']['searchPayment'] = array('function' => 'Subscription_Server.searchPayment');
        $config['functions']['getPaymentDetails'] = array('function' => 'Subscription_Server.getPaymentDetails');
        $config['functions']['submitMultiplePayment'] = array('function' => 'Subscription_Server.submitMultiplePayment');
        $config['functions']['submitEditPaymet'] = array('function' => 'Subscription_Server.submitEditPaymet');
        $config['functions']['cancelPaymet'] = array('function' => 'Subscription_Server.cancelPaymet');
        $config['functions']['screateDerivedSubscription'] = array('function' => 'Subscription_Server.screateDerivedSubscription');
        $config['functions']['ssubscriptionsForTrans'] = array('function' => 'Subscription_Server.ssubscriptionsForTrans');
        $config['functions']['sdisableSubscriptions'] = array('function' => 'Subscription_Server.sdisableSubscriptions');
        $config['functions']['schangeSubsDates'] = array('function' => 'Subscription_Server.schangeSubsDates');
        $config['functions']['schangeSubsStatus'] = array('function' => 'Subscription_Server.schangeSubsStatus');
        $config['functions']['sfetchConsumedIdsForSubs'] = array('function' => 'Subscription_Server.sfetchConsumedIdsForSubs');
        $config['functions']['schangeConsumedIdDates'] = array('function' => 'Subscription_Server.schangeConsumedIdDates');
        $config['functions']['updateMultiplePaymentStatus'] = array('function' => 'Subscription_Server.updateMultiplePaymentStatus');
        $config['functions']['sgetTransactionInfo'] = array('function' => 'Subscription_Server.sgetTransactionInfo');
        $config['functions']['getMainCollegeLinkSubscriptionDetails'] = array('function' => 'Subscription_Server.getMainCollegeLinkSubscriptionDetails');
        $config['functions']['paymentMigration'] = array('function' => 'Subscription_Server.paymentMigration');
        $config['functions']['sconsumeSubscriptionWithCount'] = array('function' => 'Subscription_Server.sconsumeSubscriptionWithCount');
        $config['functions']['getCreditCardPaymentDetails'] = array('function' => 'Subscription_Server.getCreditCardPaymentDetails');
        $config['functions']['updateCreditCardPaymentDetails'] = array('function' => 'Subscription_Server.updateCreditCardPaymentDetails');
        $config['functions']['sgetFeaturesForSubscription'] = array('function' => 'Subscription_Server.sgetFeaturesForSubscription');
        $config['functions']['consumePseudoSubscription'] = array('function' => 'Subscription_Server.consumePseudoSubscription');
        $config['functions']['incrementPseudoBaseQuantForSubscription'] = array('function' => 'Subscription_Server.incrementPseudoBaseQuantForSubscription');
        $config['functions']['sConsumeLDBCredits'] = array('function' => 'Subscription_Server.sConsumeLDBCredits');
        $config['functions']['sUpdateSubscriptionLog'] = array('function' => 'Subscription_Server.sUpdateSubscriptionLog');
        $config['functions']['updatePayPalPaymentDetails'] = array('function' => 'Subscription_Server.updatePayPalPaymentDetails');
        $config['functions']['sgetSalesPersonInfo'] = array('function' => 'Subscription_Server.sgetSalesPersonInfo');
        
    	$config['functions']['sdeductLeadPortingCredits'] = array('function' => 'Subscription_Server.sdeductLeadPortingCredits');
    	$config['functions']['sgetValidSubscriptions'] = array('function' => 'Subscription_Server.sgetValidSubscriptions');
    	$config['functions']['sgetPortingSubscriptionType'] = array('function' => 'Subscription_Server.sgetPortingSubscriptionType');
        $config['functions']['sGetMultipleSubscriptionDetails'] = array('function' => 'Subscription_Server.sGetMultipleSubscriptionDetails');
    	$config['functions']['supdateSubscriptionDetails'] = array('function' => 'Subscription_Server.supdateSubscriptionDetails');
	
        $args = func_get_args(); $method = $this->getMethod($config,$args);
        return $this->$method($args[1]);
    }

    /**
     * This method adds new marketing page entry in the database.
     * $this->db_sums = $this->getDbHandler();
     * @access	public
     * @return  object
     */
    private function setDBHandle($sums = FALSE,$mode = 'write')
	{
		if($sums) {
			$this->dbLibObj = DbLibCommon::getInstance('SUMS');
			if($mode == 'write') {
				$this->db_sums = $this->dbLibObj->getWriteHandle();
			}
			else {
				$this->db_sums = $this->dbLibObj->getReadHandle();
			}
            return $this->db_sums;
		}
		else {
			$this->dbLibObj = DbLibCommon::getInstance('SUMSShiksha');
			if($mode == 'write') {
				$this->db = $this->dbLibObj->getWriteHandle();
			}
			else {
				$this->db = $this->dbLibObj->getReadHandle();
			}
            return $this->db;
		}
	}


    /**
     * Migration web service for Payment
     */
    function paymentMigration($request){
        $parameters = $request->output_parameters();
        $this->setDBHandle(TRUE);
        $query = "select * from tempMigrationScriptFlag where migrationKey = 'PaymentUserMigration1'";
        $result = $this->db_sums->query($query);
        $flag = 0;
        foreach($result->result_array() as $row){
            $flag = $row['flag'];
        }
        if($flag == 0){
            $query = 'select Payment_Id,Part_Number,loggedInUserId,Modify_Date from Payment_Logs order by Payment_Id ,Part_Number,Modify_Date';
            error_log_shiksha("HHH :: ".$query);
            $result = $this->db_sums->query($query);
            $totalPaymentLogs = array();
            foreach($result->result_array() as $row){
                array_push($totalPaymentLogs,$row);
            }
            $tempFlag=array();
            for($i=0;$i<count($totalPaymentLogs);$i++){
                $index = $totalPaymentLogs[$i]['Payment_Id'].'#'.$totalPaymentLogs[$i]['Part_Number'];
                if(isset($tempFlag[$index])){
                    $tempFlag[$index]++;
                }else{
                    $tempFlag[$index] = 1;
                }
                if($tempFlag[$index] == 1){
                    $queryCmd = "update Payment_Logs pl set loggedInUserId = (select SumsUserId from Transaction t1,Payment pa1 where t1.TransactionId = pa1.Transaction_Id and pa1.Payment_Id = pl.Payment_Id) where pl.Payment_Id = ? and pl.Part_Number = ? and pl.Modify_Date = ?";
                    error_log_shiksha("HHH  :: ".$i."  ".$totalPaymentLogs[$i]['Payment_Id']."   ".$queryCmd);
                    $result = $this->db_sums->query($queryCmd, array($totalPaymentLogs[$i]['Payment_Id'], $totalPaymentLogs[$i]['Part_Number'], $totalPaymentLogs[$i]['Modify_Date']));
                }
                if(isset($totalPaymentLogs[$i+1]['Payment_Id']) && ($totalPaymentLogs[$i]['Payment_Id'] == $totalPaymentLogs[$i+1]['Payment_Id']) && ($totalPaymentLogs[$i]['Part_Number'] == $totalPaymentLogs[$i+1]['Part_Number'])){
                    $queryCmd = "update Payment_Logs pl set loggedInUserId = ? where pl.Payment_Id = ? and pl.Part_Number = ? and pl.Modify_Date = ?";
                    error_log_shiksha("HHH  :: ".$i."  ".$totalPaymentLogs[$i+1]['Payment_Id']."   ".$queryCmd);
                    $result = $this->db_sums->query($queryCmd, array($totalPaymentLogs[$i]['loggedInUserId'], $totalPaymentLogs[$i+1]['Payment_Id'], $totalPaymentLogs[$i+1]['Part_Number'], $totalPaymentLogs[$i+1]['Modify_Date']));
                }
            }
            $query = "update tempMigrationScriptFlag set flag = 1 where migrationKey = 'PaymentUserMigration1'";
            $this->db_sums->query($query);
        } // Closing the main if clause
        $response = array("true",'string');
        return $this->xmlrpc->send_response($response);
    }
    /**
     * Web service to generate TransactionId
     */
    private function generateTransactionId($transactionId)
    {
        $tranlen=strlen($transactionId);
        if($tranlen<11)
        {
            for($i=0;$i<(11-$tranlen);$i++)
            {
                $transactionId="0".$transactionId;
            }
        }
        return $transactionId;
    }
    /**
     * Web service to update Multiple Payment details
     */
    function updateMultiplePaymentStatus($request)
    {
        $parameters = $request->output_parameters();
        $appId = $parameters['0'];
        $requestData = $parameters['1'];
        $status = $parameters['2'];
        $action = $parameters['3'];
        $userId = $parameters['4'];
        $this->setDBHandle(TRUE);
        foreach($requestData as $record)
        {
            /* Start of Entry into Payment log */
            $query = "insert into Payment_Logs (Payment_Id,Part_Number,Cheque_No,Cheque_Date,Cheque_City,Cheque_Bank,Cheque_Receiving_Date,Amount_Received,TDS_Amount,Cheque_DD_Comments,Payment_Mode,isPaid,Deposited_By,Deposited_Branch,Cancel_Date,ReceitId,Sale_Type,loggedInUserId,Modify_Date,Action_Performed) select pd.Payment_Id,pd.Part_Number,pd.Cheque_No,pd.Cheque_Date,pd.Cheque_City,pd.Cheque_Bank,pd.Cheque_Receiving_Date,pd.Amount_Received,pd.TDS_Amount,pd.Cheque_DD_Comments,pd.Payment_Mode,pd.isPaid,pd.Deposited_By,pd.Deposited_Branch,pd.Cancel_Date,pd.ReceitId,p.Sale_Type,pd.loggedInUserId,NOW(),? from Payment_Details pd,Payment p where pd.Payment_Id = ? and pd.Part_Number = ? and p.Payment_Id = pd.Payment_Id";
            $this->db_sums->query($query, array($action, $record['PaymentId'], $record['PartNumber']));
            /* End of Entry into Payment log */
            $dataToUpdate = array('isPaid'=>$status,'loggedInUserId' => $userId);
            $where = "Payment_Id = ".$record['PaymentId']." AND  Part_Number = ".$record['PartNumber'];
            $query = $this->db_sums->update_string('Payment_Details',$dataToUpdate,$where);
            $this->db_sums->query($query);
        }
        $response = array("true",'string');
        return $this->xmlrpc->send_response($response);
    }
    /**
     * Web service to add Payment Information
     */
    function saddPaymentInfo($request)
    {
        $parameters = $request->output_parameters();
        $appId = $parameters['0'];
        $transactionId = $parameters['1'];
        $paymentInfo = $parameters['2'];
        $paymentDetails = $parameters['3'];
        $payeeAddress = $parameters['4'];
        $userId = $parameters['5'];

        $this->setDBHandle(TRUE);
        $paymentInfo['Transaction_Id'] = $transactionId;
        $queryCmd = $this->db_sums->insert_string('Payment',$paymentInfo);
        error_log_shiksha("Subscription Server : ".$queryCmd);
        $this->db_sums->query($queryCmd);

        //$paymentDetails['Payment_Id'] = $this->db_sums->insert_id();
        $paymentId = $this->db_sums->insert_id();
        $i=1;
        foreach ($paymentDetails as $details)
        {
            $details['Payment_Id'] = $paymentId;
            $details['Part_Number'] = $i;
            $details['loggedInUserId'] = $userId;
            $queryCmd = $this->db_sums->insert_string('Payment_Details',$details);
            error_log_shiksha("SS Subscription Server : ".$queryCmd);
            $this->db_sums->query($queryCmd);
            $i++;
        }

        $payeeAddress['Transaction_Id'] = $transactionId;
        $queryCmd = $this->db_sums->insert_string('Payee_Address_Details',$payeeAddress);
        error_log_shiksha("SS Subscription Server : ".$queryCmd);
        $this->db_sums->query($queryCmd);

        $response = array(array
            ('TransactionId'=>$transactionId,
            'PaymentId'=>$paymentId
        ),
        'struct'
    );
        return $this->xmlrpc->send_response($response);
    }
    /**
     * Web service to edit Payment details
     */
    function submitEditPaymet($request)
    {
        $parameters = $request->output_parameters();
        $appId = $parameters['0'];
        $paymentId = $parameters['1'];
        $request = $parameters['2'];
        $subPayments = $parameters['3'];
        $mainPaymentInfo = $parameters['4'];
        $userId = $parameters['5'];

        $this->setDBHandle(TRUE);
        $query = "select * from Payment_Details PD,Payment P where P.Payment_Id = PD.Payment_Id AND P.Payment_Id = ?";
        $result = $this->db_sums->query($query, array($paymentId));
        $existingPaymentInfo = array();
        foreach($result->result_array() as $row)
        {
            $nextPartNumber = $row['Part_Number'];
            $existingPaymentInfo[$row['Part_Number']] = $row;
        }
        $nextPartNumber++;
        $isUpdated = 0;
        foreach($request as $singlePart)
        {
            /* Start of Entry into Payment log */
            $query = "insert into Payment_Logs (Payment_Id,Part_Number,Cheque_No,Cheque_Date,Cheque_City,Cheque_Bank,Cheque_Receiving_Date,Amount_Received,TDS_Amount,Cheque_DD_Comments,Payment_Mode,isPaid,Deposited_By,Deposited_Branch,Cancel_Date,ReceitId,Sale_Type,loggedInUserId,Modify_Date,Action_Performed) select pd.Payment_Id,pd.Part_Number,pd.Cheque_No,pd.Cheque_Date,pd.Cheque_City,pd.Cheque_Bank,pd.Cheque_Receiving_Date,pd.Amount_Received,pd.TDS_Amount,pd.Cheque_DD_Comments,pd.Payment_Mode,pd.isPaid,pd.Deposited_By,pd.Deposited_Branch,pd.Cancel_Date,pd.ReceitId,p.Sale_Type,pd.loggedInUserId,NOW(),'EDIT' from Payment_Details pd,Payment p where pd.Payment_Id = ? and pd.Part_Number = ? and p.Payment_Id = pd.Payment_Id";
            $this->db_sums->query($query, array($paymentId, $singlePart['editPartNumber']));
            /* End of Entry into payment log */

            /* For edition of sale type */
            if(($mainPaymentInfo['Sale_Type'] != "") && ($isUpdated == 0))
            {
                $isUpdated = 1;
                $dataToUpdate = array('Sale_Type' => $mainPaymentInfo['Sale_Type']);
                $where = "Payment_Id = ".$paymentId;
                $query = $this->db_sums->update_string('Payment',$dataToUpdate,$where);
                $this->db_sums->query($query);
            }
            /* For edition of sale type */

            $subPartPayment = $this->getSubParts($singlePart['editPartNumber'],$subPayments);

            if((count($subPartPayment) <= 0) && ($singlePart['editThisPart'] == 1))
            {
                $dataToUpdate = array('Cheque_No'=>$singlePart['Cheque_No'],'Cheque_Date'=>$singlePart['Cheque_Date'],'Cheque_City'=>$singlePart['Cheque_City'],'Cheque_Bank'=>$singlePart['Cheque_Bank'],'Cheque_Receiving_Date'=>$singlePart['Cheque_Receiving_Date'],'Amount_Received'=>$singlePart['Amount_Received'],'TDS_Amount'=>$singlePart['TDS_Amount'],'Deposited_By'=>$singlePart['Deposited_By'],'Deposited_Branch'=>$singlePart['Deposited_Branch'],'Cheque_DD_Comments'=>$singlePart['Cheque_DD_Comments'],'isPaid'=>$singlePart['Payment_Status'],'Payment_Mode'=>$singlePart['Payment_Mode'],'loggedInUserId' => $userId);
                $where = "Payment_Id = ".$paymentId." AND  Part_Number = ".$singlePart['editPartNumber'];
                $query = $this->db_sums->update_string('Payment_Details',$dataToUpdate,$where);
                $this->db_sums->query($query);
            }
            elseif(count($subPartPayment) > 0 )
            {
                $dataToUpdate = array('isPaid'=>'History','loggedInUserId' => $userId);
                $where = "Payment_Id = ".$paymentId." AND  Part_Number = ".$singlePart['editPartNumber'];
                $query = $this->db_sums->update_string('Payment_Details',$dataToUpdate,$where);
                $this->db_sums->query($query);

                $dataToInsert = array('Payment_Id' => $paymentId,'Part_Number'=>$nextPartNumber,'Cheque_No'=>$singlePart['Cheque_No'],'Cheque_Date'=>$singlePart['Cheque_Date'],'Cheque_City'=>$singlePart['Cheque_City'],'Cheque_Bank'=>$singlePart['Cheque_Bank'],'Cheque_Receiving_Date'=>$singlePart['Cheque_Receiving_Date'],'Amount_Received'=>$singlePart['Amount_Received'],'TDS_Amount'=>$singlePart['TDS_Amount'],'Deposited_By'=>$singlePart['Deposited_By'],'Deposited_Branch'=>$singlePart['Deposited_Branch'],'Cheque_DD_Comments'=>$singlePart['Cheque_DD_Comments'],'isPaid'=>$singlePart['Payment_Status'],'Payment_Mode'=>$singlePart['Payment_Mode'],'loggedInUserId' => $userId);
                $query = $this->db_sums->insert_string('Payment_Details',$dataToInsert);
                $this->db_sums->query($query);
                foreach($subPartPayment as $temp)
                {
                    $nextPartNumber++;
                    $dataToInsert = array('Payment_Id' => $paymentId,'Part_Number'=>$nextPartNumber,'Cheque_No'=>$temp['Cheque_No'],'Cheque_Date'=>$temp['Cheque_Date'],'Cheque_City'=>$temp['Cheque_City'],'Cheque_Bank'=>$temp['Cheque_Bank'],'Cheque_Receiving_Date'=>$temp['Cheque_Receiving_Date'],'Amount_Received'=>$temp['Amount_Received'],'TDS_Amount'=>$temp['TDS_Amount'],'Deposited_By'=>$temp['Deposited_By'],'Deposited_Branch'=>$temp['Deposited_Branch'],'Cheque_DD_Comments'=>$temp['Cheque_DD_Comments'],'isPaid'=>$temp['Payment_Status'],'Payment_Mode'=>$temp['Payment_Mode'],'loggedInUserId' => $userId);
                    $query = $this->db_sums->insert_string('Payment_Details',$dataToInsert);
                    $this->db_sums->query($query);
                }
            }

        }
        $response = array("true",'string');
        return $this->xmlrpc->send_response($response);
    }
    /**
     * Web service to get Sub Parts of the Payments
     *
     */
    function getSubParts($partNumber,$subPayments)
    {
        $returnArray = array();
        foreach($subPayments as $temp){
            if($temp['parent'] == $partNumber){
                array_push($returnArray,$temp);
            }
        }
        return $returnArray;
    }
    /**
     * Web service to Cancel a Payment
     */
    function cancelPaymet($request){
        $parameters = $request->output_parameters();
        $appId = $parameters['0'];
        $paymentId = $parameters['1'];
        $request = $parameters['2'];
        $userId = $parameters['3'];

        $this->setDBHandle(TRUE);
        foreach($request as $record)
        {
            /* Start of Entry into Payment log */
            $query = "insert into Payment_Logs (Payment_Id,Part_Number,Cheque_No,Cheque_Date,Cheque_City,Cheque_Bank,Cheque_Receiving_Date,Amount_Received,TDS_Amount,Cheque_DD_Comments,Payment_Mode,isPaid,Deposited_By,Deposited_Branch,Cancel_Date,ReceitId,Sale_Type,loggedInUserId,Modify_Date,Action_Performed) select pd.Payment_Id,pd.Part_Number,pd.Cheque_No,pd.Cheque_Date,pd.Cheque_City,pd.Cheque_Bank,pd.Cheque_Receiving_Date,pd.Amount_Received,pd.TDS_Amount,pd.Cheque_DD_Comments,pd.Payment_Mode,pd.isPaid,pd.Deposited_By,pd.Deposited_Branch,pd.Cancel_Date,pd.ReceitId,p.Sale_Type,pd.loggedInUserId,NOW(),'CANCEL' from Payment_Details pd,Payment p where pd.Payment_Id = ? and pd.Part_Number = ? and p.Payment_Id = pd.Payment_Id";
            $this->db_sums->query($query, array($paymentId, $record['cancelPartNumber']));
            /* End of Entry into Payment log */

            $dataToUpdate = array('Cheque_DD_Comments'=>$record['Cheque_DD_Comments'],'isPaid'=>'Cancelled','Cancel_Date' => date('Y-m-d H:i:s'),'loggedInUserId'=> $userId);
            $where = "Payment_Id = ".$paymentId." AND  Part_Number = ".$record['cancelPartNumber'];
            $query = $this->db_sums->update_string('Payment_Details',$dataToUpdate,$where);
            $this->db_sums->query($query);
        }
        $response = array("true",'string');
        return $this->xmlrpc->send_response($response);
    }


    /**
     * API to Add a Tranasaction, during purchase of a Subscription
     *
     */
    function saddTransaction($request){
        $parameters = $request->output_parameters();
        $appId = $parameters['0'];
        $transactParams = $parameters['1']; // All Transaction Parameters

        $clientUserId = $transactParams['clientUserId'];
        $sumsUserId = $transactParams['sumsUserId'];
        $salesPersonName = $transactParams['SalesBy'];
        $salesBranch = $transactParams['SalesBranch'];
        $UIQuotationId = $transactParams['UIQuotationId'];
        $salesAmount = $transactParams['FinalSalesAmount'];
        $currencyId = $transactParams['CurrencyId'];

        //connect DB
        $this->setDBHandle(TRUE);

        $transData =array();
        $transData = array(
            'ClientUserId'=>$clientUserId,
            'SumsUserId'=>$sumsUserId,
            'UIQuotationId'=>$UIQuotationId,
            'TotalTransactionPrice'=>$salesAmount,
            'CurrencyId'=>$currencyId,
            'SalesBy'=>$salesPersonName,
            'SalesBranch'=>$salesBranch
        );

        $queryCmd = $this->db_sums->insert_string('Transaction',$transData);
        error_log_shiksha('ADD Transaction query cmd is: '.$queryCmd);
        $query = $this->db_sums->query($queryCmd);
        $transactionId = $this->db_sums->insert_id();
        error_log_shiksha("Generated Transaction ID : ".$transactionId);

        $queryCmd = "update Quotation set Status='TRANSACTION' where UIQuotationId = ? and Status='ACTIVE'";
        error_log_shiksha('ADD Transaction query cmd is: '.$queryCmd);
        $query = $this->db_sums->query($queryCmd, array($UIQuotationId));
        $this->insertTransactionQueue($transactionId,$transactParams['Sale_Type']);
        $response = array(array
            ('TransactionId'=>$transactionId,
            'clientId'=>$clientUserId
        ),
        'struct'
    );

        error_log_shiksha("ADD Transaction Server RESPONSE: ".print_r($response,true));
        return $this->xmlrpc->send_response($response);
    }

    /**
     * Adding the multiple payments.
     *
     */
    function submitMultiplePayment($request){
        $parameters = $request->output_parameters();
        $appId = $parameters['0'];
        $paymentInfo = $parameters['1']; // All Transaction Parameters
        $otherPaymentDetails = $parameters['2'];
        $userId = $parameters['3'];
        //connect DB
        $this->setDBHandle(TRUE);

        if(($otherPaymentDetails['Payment_Mode'] == 'Cheque') || ($otherPaymentDetails['Payment_Mode'] == 'Telegraphic Transfer')){
            $isPaidFlag = 'In-process';
        }else{
            $isPaidFlag = 'Paid';
        }
        foreach($paymentInfo as $temp){
            $temp['Payment_Id'] = (int)$temp['Payment_Id'];
            $query = "insert into Payment_Logs (Payment_Id,Part_Number,Cheque_No,Cheque_Date,Cheque_City,Cheque_Bank,Cheque_Receiving_Date,Amount_Received,TDS_Amount,Cheque_DD_Comments,Payment_Mode,isPaid,Deposited_By,Deposited_Branch,Cancel_Date,ReceitId,Sale_Type,loggedInUserId,Modify_Date,Action_Performed) select pd.Payment_Id,pd.Part_Number,pd.Cheque_No,pd.Cheque_Date,pd.Cheque_City,pd.Cheque_Bank,pd.Cheque_Receiving_Date,pd.Amount_Received,pd.TDS_Amount,pd.Cheque_DD_Comments,pd.Payment_Mode,pd.isPaid,pd.Deposited_By,pd.Deposited_Branch,pd.Cancel_Date,pd.ReceitId,p.Sale_Type,pd.loggedInUserId,NOW(),'MAKEPAYMENT' from Payment_Details pd,Payment p where pd.Payment_Id = ? and pd.Part_Number = ? and p.Payment_Id = pd.Payment_Id";
            $this->db_sums->query($query, array($temp['Payment_Id'], $temp['Part_Number']));
            error_log_shiksha("WMM :: ".$query);
            if($temp['AmountBeingPaid'] == $temp['totalAmountToBePaid']){
                $dataToUpdate = array(
                    'Cheque_No' => $otherPaymentDetails['Cheque_No'],
                    'Payment_Mode' => $otherPaymentDetails['Payment_Mode'],
                    'Cheque_Date' => $otherPaymentDetails['Cheque_Date'],
                    'Cheque_City' => $otherPaymentDetails['Cheque_City'],
                    'Cheque_Bank' => $otherPaymentDetails['Cheque_Bank'],
                    'Cheque_Receiving_Date' => $otherPaymentDetails['Cheque_Receiving_Date'],
                    'TDS_Amount' => $otherPaymentDetails['TDS_Amount'],
                    'Cheque_DD_Comments' => $otherPaymentDetails['Cheque_DD_Comments'],
                    'Deposited_By' => $otherPaymentDetails['Depositedby'],
                    'Deposited_Branch' => $otherPaymentDetails['branch_name'],
                    'isPaid' => $isPaidFlag,
                    'loggedInUserId'=> $userId
                );
                $where = "Payment_Id = ".$temp['Payment_Id']." AND Part_Number = ".$temp['Part_Number'];
                $queryCmd = $this->db_sums->update_string('Payment_Details',$dataToUpdate,$where);
                error_log_shiksha("WMM111 :: ".$queryCmd);
                $result = $this->db_sums->query($queryCmd);
            }else{
                $dataToUpdate = array(
                    'isPaid' => 'History',
                    'loggedInUserId'=> $userId
                );
                $where = "Payment_Id = ".$temp['Payment_Id']." AND Part_Number = ".$temp['Part_Number'];
                $queryCmd = $this->db_sums->update_string('Payment_Details',$dataToUpdate,$where);
                error_log_shiksha("WMM2222 :: ".$queryCmd);
                $result = $this->db_sums->query($queryCmd);
                $queryCmd = 'select (count(*)+1) as Nxt_PartNum from Payment_Details where Payment_Id = ?';
                $result = $this->db_sums->query($queryCmd, array($temp['Payment_Id']));
                foreach($result->result() as $row){
                    $NextPartNumber = $row->Nxt_PartNum;
                }

                $dataToUpdate = array(
                    'Payment_Id' => $temp['Payment_Id'],
                    'Part_Number' => $NextPartNumber,
                    'Cheque_No' => $otherPaymentDetails['Cheque_No'],
                    'Payment_Mode' => $otherPaymentDetails['Payment_Mode'],
                    'Cheque_Date' => $otherPaymentDetails['Cheque_Date'],
                    'Cheque_City' => $otherPaymentDetails['Cheque_City'],
                    'Cheque_Bank' => $otherPaymentDetails['Cheque_Bank'],
                    'Cheque_Receiving_Date' => $otherPaymentDetails['Cheque_Receiving_Date'],
                    'Amount_Received' => $temp['AmountBeingPaid'],
                    'TDS_Amount' => $otherPaymentDetails['TDS_Amount'],
                    'Cheque_DD_Comments' => $otherPaymentDetails['Cheque_DD_Comments'],
                    'Deposited_By' => $otherPaymentDetails['Depositedby'],
                    'Deposited_Branch' => $otherPaymentDetails['branch_name'],
                    'isPaid' => $isPaidFlag,
                    'loggedInUserId'=> $userId
                );
                $queryCmd = $this->db_sums->insert_string('Payment_Details',$dataToUpdate);
                error_log_shiksha("WMM3333 :: ".$queryCmd);
                $result = $this->db_sums->query($queryCmd);
                $NextPartNumber++;
                $dataToUpdate = array(
                    'Payment_Id' => $temp['Payment_Id'],
                    'Part_Number' => $NextPartNumber,
                    'Cheque_Date' => $temp['nextPaymentDate'],
                    'Amount_Received' => ($temp['totalAmountToBePaid'] - $temp['AmountBeingPaid']),
                    'loggedInUserId'=> $userId
                );
                $queryCmd = $this->db_sums->insert_string('Payment_Details',$dataToUpdate);
                error_log_shiksha("WMM4444 :: ".$queryCmd);
                $result = $this->db_sums->query($queryCmd);
            }
        }
        $response = array('true','string');
        return $this->xmlrpc->send_response($response);
    }
    /**
     * API to get Transaction Approver
     */
    function getApprover($userId,$discount)
    {

        //connect DB
        $this->setDBHandle(TRUE);
        $queryCmd="select * from Sums_User_Details where userId=?";
        error_log_shiksha($queryCmd);
        $arrResults = $this->db_sums->query($queryCmd, array($userId));
        $approverId = '';
        $managerId = '';
        foreach($arrResults->result() as $row)
        {
            $managerId= $row->managerId;
            $discountLimit= $row->DiscountLimit;
        }

        if($discount*1 > $discountLimit*1)
        {
            $approverId=$this->getApprover($managerId,$discount);
        }
        else
        {
            $approverId=$userId;
        }

        return $approverId;
    }
    /**
     * API to get get Customized Transaction Approver
     */
    function getCustomizedApprover($userId)
    {
        $this->setDBHandle(TRUE);
        $queryCmd="select * from Sums_User_Details where userId=?";
        error_log_shiksha($queryCmd);
        $arrResults = $this->db_sums->query($queryCmd, array($userId));
        $approverId = '';
        $managerId = '';
        foreach($arrResults->result() as $row)
        {
            $managerId= $row->managerId;
            $Role= $row->Role;
        }

        $queryCmd="select * from Sums_Group_ACL_Mapping where ACLId=14 and UserGroupId=?";
        error_log_shiksha($queryCmd);
        $arrResults = $this->db_sums->query($queryCmd, array($Role));
        if(count($arrResults->result_array())>0)
        {
            $approverId=$userId;
        }
        else
        {
            $approverId=$this->getCustomizedApprover($managerId);
        }
        return $approverId;

    }
    /**
     * API to insert into Transaction Queue
     */
    function insertTransactionQueue($transactionId,$SaleType)
    {
        //connect DB
        $this->setDBHandle(TRUE);
        $queryCmd = "select * from Transaction, Quotation where TransactionId=? and Transaction.UIQuotationId=Quotation.UIQuotationId and Status='TRANSACTION'";
        error_log_shiksha($queryCmd);
        $arrResults = $this->db_sums->query($queryCmd, array($transactionId));
        foreach($arrResults->result() as $row)
        {
            $discountValue = $row->TotalDiscount;
            $costPrice = $row->TotalPrice;
            $userId = $row->SalesBy;
            $quoteType = $row->QuoteType;
        }
        $transQueueData=array();
        error_log_shiksha("TransactionQueue".$quoteType);
        if($userId != ''){
            if(($SaleType == 'Trial') || ($SaleType == 'Credit'))
            {
                $approverId = $this->getTrialApprover($userId);
                $state = "PENDING";
            }
            else
            {
                if($quoteType=="CUSTOMIZED")
                {
                    $approverId= $this->getCustomizedApprover($userId);
                }
                else
                {
                    $discount = ($discountValue*100)/$costPrice;
                    $approverId = $this->getApprover($userId,$discount);
                }

                $state = "PENDING";
            }
            if($approverId==$userId)
            {
                $approverId=0;
            }
        }else{
            $approverId=0;
            $state="PENDING";
        }
        $transQueueData=array(
            'TransactionId'=>$transactionId,
            'ApproverId'=>$approverId,
            'State'=>$state
        );
        $queryCmd=$this->db_sums->insert_string('Transaction_Queue',$transQueueData);
        error_log_shiksha($queryCmd);
        $this->db_sums->query($queryCmd);
    }
    /**
     * API to search Transaction
     */
    function searchTransaction($request)
    {
        $parameters = $request->output_parameters();
        error_log_shiksha(print_r($parameters,true));
        $this->setDBHandle(FALSE,'read');

        $formVal = $parameters['1'];

        if($formVal['transactionId']!=''){
            $formVal['transactionId'] = $this->generateTransactionId($formVal['transactionId']);
            $TRANSACTIONID = "and S.TransactionId = ".$this->db->escape($formVal['transactionId']);
        }else{
            $TRANSACTIONID = "";
        }

        if($formVal['uiQuotationId']!=''){
            $QUOTATIONID = "and S.UIQuotationId = ".$this->db->escape($formVal['uiQuotationId']);
        }else{
            $QUOTATIONID = "";
        }

        if(($formVal['trans_start_date']!='') &&($formVal['trans_end_date']!='')){
            $TRANSDATE = "AND date(S.TransactTime) BETWEEN ".$this->db->escape($formVal['trans_start_date'])." and ".$this->db->escape($formVal['trans_end_date'])." ";
        }else{
            $TRANSDATE = "";
        }

        if($formVal['clientId']!=''){
            $CLIENTID = "AND S.ClientUserId like '%".$this->db->escape_like_str($formVal['clientId'])."%'";
        }else{
            $CLIENTID = "";
        }

        if($formVal['contactName']!=''){
            $CNTCTNAME = "AND U.firstname like '%".$this->db->escape_like_str($formVal['contactName'])."%'";
        }else{
            $CNTCTNAME = "";
        }

        if($formVal['collegeName']!=''){
            $COLNAME = "AND E.businessCollege like '%".$this->db->escape_like_str($formVal['collegeName'])."%'";
        }else{
            $COLNAME = "";
        }

        if($formVal['saleBy']!=''){
            $SALESBY = "and (select displayname from shiksha.tuser where userid=D.userId) like '%".$this->db->escape_like_str($formVal['saleBy'])."%'";
        }else{
            $SALESBY = "";
        }

        if(($formVal['saleFloorAmount']!='') &&($formVal['saleCeilAmount']!='')){
            $SALEAMOUNT = "AND Q.FinalSalesAmount BETWEEN ".$this->db->escape($formVal['saleFloorAmount'])." and ".$this->db->escape($formVal['saleCeilAmount'])." ";
        }else{
            $SALEAMOUNT = "";
        }

        if(($formVal['discFloorAmount']!='') &&($formVal['discCeilAmount']!='')){
            $DISCOUNT = "AND ROUND(Q.TotalDiscount/ Q.TotalPrice* 100,2) BETWEEN ".$this->db->escape($formVal['discFloorAmount'])." and ".$this->db->escape($formVal['discCeilAmount'])." ";
        }else{
            $DISCOUNT = "";
        }

        if($formVal['queueType'] =='MANAGER'){
            $APPROVERID = "AND Z.ApproverId= ".$this->db->escape($formVal['approverId'])." and Z.State='PENDING'";
        }else if($formVal['queueType'] =='FINANCE'){
            $APPROVERID = "AND ((Z.ApproverId=0 and Z.State='PENDING') OR (Z.State='MANAGER_APPROVED')) AND P.Sale_Type!='Trial' AND P.Sale_Type!='Credit'";
        }else if($formVal['queueType'] =='OPS'){
            //$APPROVERID = "AND (Z.State='FINANCE_APPROVED' OR ((Z.State='MANAGER_APPROVED' OR Z.ApproverId=0) AND (P.Sale_Type='Trial' OR P.Sale_Type='Credit')))";
            $APPROVERID = "AND (Z.State='FINANCE_APPROVED' OR Z.State='MANAGER_APPROVED' OR (Z.ApproverId=0 and Z.State='PENDING'))"; //FINANCE_APPROVED check to be reoved after aroud 15 days from 12 Nov 2008
        }else if($formVal['queueType'] == 'View'){
            $APPROVERID='';
            if(($formVal['payment_start_date']!='') && ($formVal['payment_end_date']!=''))
            {
                $PAYMENTTABLE = ",SUMS.Payment_Details PD, SUMS.Payment P";
                $PAYMENTCONDITION = "and P.Transaction_Id=S.TransactionId and PD.Payment_Id=P.Payment_Id";
                $PAYMENTCONDITION .= " and PD.Cheque_Date >= ".$this->db->escape($formVal['payment_start_date'])." and  PD.Cheque_Date <= ".$this->db->escape($formVal['payment_end_date'])." AND PD.isPaid in('Un-paid')  group by S.TransactionId";
            }
        }

        if($formVal['chequeNo']!=''){
            $CHQNUM = "AND PD.Cheque_No=".$this->db->escape($formVal['chequeNo']);
        }else{
            $CHQNUM = "";
        }

        if($formVal['saleType']!="''"){
            $SALETYPE = "AND P.Sale_Type in (".$formVal['saleType'].")";
        }else{
            $SALETYPE = "";
        }

        if($formVal['userId']!='')
        {
            $BRANCHTABLE = ", SUMS.Sums_User_Branch_Mapping SUBM ";
            $USERFILTER = "AND SUBM.userId=".$this->db->escape($formVal['userId'])." AND SUBM.BranchId IN (select group_concat(BranchId) from SUMS.Sums_User_Branch_Mapping where Sums_User_Branch_Mapping.userId=".$this->db->escape($formVal['userId']).") ";
        }
        else
        {
            $BRANCHTABLE ="";
            $USERFILTER ="";
        }
  
	$query =  "select S.UIQuotationId,S.TransactionId,S.TransactTime,U.userid as ClientId,businessCollege,S.SalesBy from shiksha.tuser U,shiksha.enterpriseUserDetails E, SUMS.Transaction_Queue Z, SUMS.Transaction S,SUMS.Payment P,SUMS.Payment_Details PD $BRANCHTABLE WHERE U.userid=E.userId and U.userId=S.ClientUserId AND U.usergroup='enterprise' and S.TransactionId = Z.TransactionId AND P.Transaction_Id=S.TransactionId AND P.Payment_Id=PD.Payment_Id $TRANSACTIONID  $TRANSDATE $CLIENTID $CNTCTNAME $COLNAME $SALESBY $SALEAMOUNT $DISCOUNT $APPROVERID $PAYMENTCONDITION $CHQNUM $USERFILTER group by S.TransactionId";

	
        // error_log_shiksha("GMM ::".$query);
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
     * API to get Payment details
     */
    function getPaymentDetails($request)
    {
        $parameters = $request->output_parameters();
        $this->setDBHandle();
        $csvPaymentIds = $parameters['1'];
        $FILTER = $parameters['2'];
        $query =  "select p.Sale_Type,pd.*,t.TotalTransactionPrice,(select displayName from shiksha.tuser t1,SUMS.Payment_Details pd1 where t1.userid=pd.Deposited_By and pd1.Payment_Id = pd.Payment_Id and pd1.Part_Number = pd.Part_Number AND pd1.isPaid not in ('History')) DepositedByName,(select BranchName from SUMS.Sums_Branch_List SBL,SUMS.Payment_Details pd1 where SBL.BranchId = pd.Deposited_Branch and pd1.Payment_Id = pd.Payment_Id and pd1.Part_Number = pd.Part_Number AND pd1.isPaid not in ('History')) BranchName from SUMS.Payment p,SUMS.Payment_Details pd,SUMS.Transaction t where p.Payment_Id in (".$csvPaymentIds.") AND p.Payment_Id = pd.Payment_Id AND p.Transaction_Id = t.TransactionId AND pd.isPaid not in ('History') $FILTER";
        // error_log_shiksha("GMM ::".$query);
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
     * API to search Payments
     */
    function searchPayment($request)
    {
        $parameters = $request->output_parameters();
        $this->setDBHandle();

        $formVal = $parameters['1'];
        $FILTERVALUE = $parameters['2'];

        $PAYMENTIDPARTNUMBERCOND = '';
        if($formVal['transactionId']!=''){
            $formVal['transactionId'] = $this->generateTransactionId($formVal['transactionId']);
            $PAYMENTIDPARTNUMBERCOND .= " and S.TransactionId = ".$this->db->escape($formVal['transactionId']);
        }

        if($formVal['paymentId']!=''){
            $PAYMENTIDPARTNUMBERCOND .= " and PD.Payment_Id = ".$this->db->escape($formVal['paymentId']);
        }

        if($formVal['uiQuotationId']!=''){
            $PAYMENTIDPARTNUMBERCOND .= " and S.UIQuotationId = ".$this->db->escape($formVal['uiQuotationId']);
        }

        if(($formVal['trans_start_date']!='') &&($formVal['trans_end_date']!='')){
            $PAYMENTIDPARTNUMBERCOND .= " AND date(S.TransactTime) BETWEEN ".$this->db->escape($formVal['trans_start_date'])." and ".$this->db->escape($formVal['trans_end_date'])." ";
        }

        if($formVal['clientId']!=''){
            $PAYMENTIDPARTNUMBERCOND .= " AND S.ClientUserId like '%".$this->db->escape_like_str($formVal['clientId'])."%'";
        }

        if($formVal['contactName']!=''){
            $PAYMENTIDPARTNUMBERCOND .= " AND U.firstname like '%".$this->db->escape_like_str($formVal['contactName'])."%'";
        }

        if($formVal['collegeName']!=''){
            $PAYMENTIDPARTNUMBERCOND .= " AND E.businessCollege like '%".$this->db->escape_like_str($formVal['collegeName'])."%'";
        }

        if($formVal['saleBy']!=''){
            $PAYMENTIDPARTNUMBERCOND .= " and (select displayname from shiksha.tuser where userid=D.userId) like '%".$this->db->escape_like_str($formVal['saleBy'])."%'";
        }

        if(($formVal['saleFloorAmount']!='') &&($formVal['saleCeilAmount']!='')){
            $PAYMENTIDPARTNUMBERCOND .= " AND Q.FinalSalesAmount BETWEEN ".$this->db->escape($formVal['saleFloorAmount'])." and ".$this->db->escape($formVal['saleCeilAmount'])." ";
        }

        if(($formVal['discFloorAmount']!='') &&($formVal['discCeilAmount']!='')){
            $PAYMENTIDPARTNUMBERCOND .= " AND ROUND(Q.TotalDiscount/ Q.TotalPrice* 100,2) BETWEEN ".$this->db->escape($formVal['discFloorAmount'])." and ".$this->db->escape($formVal['discCeilAmount'])." ";
        }

        if($formVal['queueType'] =='MANAGER'){
            $PAYMENTIDPARTNUMBERCOND .= " AND Z.ApproverId= ".$this->db->escape($formVal['approverId'])." and Z.State='PENDING'";
        }else if($formVal['queueType'] =='FINANCE'){
            $PAYMENTIDPARTNUMBERCOND .= " AND ((Z.ApproverId=0 and Z.State='PENDING') OR (Z.State='MANAGER_APPROVED')) ";
        }else if($formVal['queueType'] =='OPS'){
            $PAYMENTIDPARTNUMBERCOND .= " AND Z.State='FINANCE_APPROVED'";
        }else if($formVal['queueType'] == 'View'){
            $PAYMENTIDPARTNUMBERCOND .= " AND S.SalesBranch in (select Sums_User_Branch_Mapping.BranchId from SUMS.Sums_User_Branch_Mapping where Sums_User_Branch_Mapping.userId=".$this->db->escape($formVal['approverId']).") ";
            if(($formVal['payment_start_date']!='') && ($formVal['payment_end_date']!=''))
            {
                $PAYMENTIDPARTNUMBERCOND .= " and P.Transaction_Id = S.TransactionId and PD.Payment_Id = P.Payment_Id ";
                $PAYMENTIDPARTNUMBERCOND .= " and PD.Cheque_Date >= ".$this->db->escape($formVal['payment_start_date'])." and  PD.Cheque_Date <= ".$this->db->escape($formVal['payment_end_date'])." group by S.TransactionId";
            }
        }

        if(strlen($formVal['csvPaymentList'])>4){
            $PAYMENTIDPARTNUMBERCOND .= " and PD.isPaid in(".$this->db->escape($formVal['csvPaymentList']).")";
        }else{
            $PAYMENTIDPARTNUMBERCOND .= " and PD.isPaid not in('History')";
        }
        if($formVal['chequeNo']!=''){
            $PAYMENTIDPARTNUMBERCOND .= " AND PD.Cheque_No = ".$this->db->escape($formVal['chequeNo']);
        }
        if($formVal['saleType']!="''"){
            $PAYMENTIDPARTNUMBERCOND .= " AND P.Sale_Type in (".$formVal['saleType'].")";
        }

        $query =  "select P.*,PD.*,ClientId,(select group_concat( distinct displayname) from shiksha.tuser u1,SUMS.Payment_Details pd1 where u1.userid=pd1.Deposited_By and pd1.Payment_Id = PD.Payment_Id and pd1.Part_Number = PD.Part_Number) DepositedByName,(select group_concat(distinct BranchName) from SUMS.Sums_Branch_List SBL,SUMS.Payment_Details pd1 where SBL.BranchId = PD.Deposited_Branch and pd1.Payment_Id = PD.Payment_Id and pd1.Part_Number = PD.Part_Number) BranchName from shiksha.tuser U,shiksha.enterpriseUserDetails E,SUMS.Transaction S, SUMS.Quotation Q, SUMS.Transaction_Queue Z,SUMS.Sums_User_Details D,SUMS.Payment P,SUMS.Payment_Details PD WHERE U.userid=E.userId and U.userId=S.ClientUserId AND U.usergroup='enterprise' AND S.UIQuotationId=Q.UIQuotationId and Q.Status='Transaction' AND Z.TransactionId=S.TransactionId AND D.userId=S.SalesBy AND P.Transaction_Id=S.TransactionId AND P.Payment_Id=PD.Payment_Id $PAYMENTIDPARTNUMBERCOND $FILTERVALUE";
        // error_log_shiksha("GMM ::".$query);

        $arrResults = $this->db->query($query);
        $response=array();
        foreach ($arrResults->result_array() as $row)
        {
            array_push($response,array($row,'struct'));
        }

        $resp = array($response,'struct');
        return $this->xmlrpc->send_response ($resp);

    }
    /**
     * API to get Payment Info
     */
    function sgetPaymentInfo($request)
    {
        $parameters = $request->output_parameters();
        $transactionId = $parameters['1'];

        $this->setDBHandle(TRUE);

        $queryCmd = "select * from Transaction, Payment where TransactionId = ? and Transaction.TransactionId=Payment.Transaction_Id";
        error_log_shiksha("Subscription server : ".$queryCmd);
        $query = $this->db_sums->query($queryCmd, array($transactionId));
        $response['Transaction'] = array($query->first_row('array'),'struct');

        $response['Payment'] = array(array(),'struct');

        $queryCmd = "select * from Payment_Details where Payment_Id = ? AND isPaid not in('History')";
        error_log_shiksha("Subscription Server : ".$queryCmd);
        $query = $this->db_sums->query($queryCmd, array($response['Transaction'][0]['Payment_Id']));
        foreach ($query->result_array() as $row)
        {
            array_push ($response['Payment'][0],array($row,'struct'));
        }

        $queryCmd = "select * from Payee_Address_Details where Transaction_Id = ?";
        error_log_shiksha("Subscription server : ".$queryCmd);
        $query = $this->db_sums->query($queryCmd, array($transactionId));
        $response['Address'] = array($query->first_row('array'),'struct');

        $resp = array($response,'struct');
        return $this->xmlrpc->send_response ($resp);
    }
    /**
     * API to create Subscription
     */
    function createSubscription($request)
    {
	    $parameters = $request->output_parameters();
	    $transactionId = $parameters['1'];
	    $subsStartDate = $parameters['2'];
	    $opsUserId = $parameters['3'];
	    $DerivedProductId = $parameters['4'];

	    $clientuserid = $parameters['5'];
	    $TotalBaseQuantity = $parameters['6'];
	    $subsEndDate = $parameters['7'];
	    $nav_subscription_line_no = $parameters['8'];
	    $currency = $parameters['9'];
	    $subscription = $parameters['10'];
	    $this->setDBHandle(TRUE);

	    /*
	       $queryCmd = "select DISTINCT(QPM.DerivedProductId), T.ClientUserId from Transaction T, Quotation Q, Quotation_Product_Mapping QPM where T.UIQuotationId=Q.UIQuotationId and Q.QuotationId = QPM.QuotationId and T.TransactionId = $transactionId and Q.Status='TRANSACTION'";
	       error_log_shiksha("Subscription server : ".$queryCmd);
	       $query = $this->db_sums->query($queryCmd);
	       $derivedProductIds = $query->result_array();
	       error_log_shiksha(print_r($derivedProductIds,true));
	     */

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
		
		if(in_array($DerivedProductId,$goldSLDerivedProductIds)) {
			$baseproductids = array(1);
		}
		else if(in_array($DerivedProductId,$goldMLDerivedProductIds)) {
			$baseproductids = array(375);
		}
		else {
			$queryCmd = "SELECT `BaseProductId`,BaseProdQuantity FROM `Derived_Products_Mapping` WHERE CurrencyId = ? and `DerivedProductId` =?";
			$query = $this->db_sums->query($queryCmd, array($currency, $DerivedProductId));
			$baseproductids = $query->result_array();
		}

	    error_log("baseproductids".print_r($baseproductids,true));
	    $subscriptionsArr = array();
		
	    for($i = 0;$i < count($baseproductids); $i++){

		    $data['TransactionId'] = $transactionId;
		    $data['ClientUserId'] = $clientuserid;
		    $data['SumsUserId'] = $opsUserId;
		    $data['BaseProductId'] = $baseproductids[$i]['BaseProductId'];
		    $data['DerivedProductId'] = $DerivedProductId;
		    $data['nav_subscription_line_no'] = $nav_subscription_line_no;
		    $data['SubscrStatus'] = "ACTIVE";
		    $data['NavSubscriptionId'] = $subscription;
		    $queryCmd = $this->db_sums->insert_string('Subscription',$data);

		    // error_log_shiksha("Subscription Table : ".$queryCmd);
		    $this->db_sums->query($queryCmd);
		    $subscriptionId = $this->db_sums->insert_id();
		    array_push($subscriptionsArr,$subscriptionId);

		    $mapData['SubscriptionId'] = $subscriptionId;
		    $mapData['BaseProductId'] = $baseproductids[$i]['BaseProductId'];
		    $mapData['TotalBaseProdQuantity'] = ($TotalBaseQuantity*$baseproductids[$i]['BaseProdQuantity']);
		    $mapData['BaseProdRemainingQuantity'] = ($TotalBaseQuantity*$baseproductids[$i]['BaseProdQuantity']);
		    $mapData['BaseProdPseudoRemainingQuantity'] = ($TotalBaseQuantity*$baseproductids[$i]['BaseProdQuantity']);
		    $mapData['SubscriptionStartDate'] = $subsStartDate;
		    $dt = explode('-',$subsStartDate);
		    $mapData['SubscriptionEndDate'] = $subsEndDate;
		    $mapData['SubscrLastModifyTime'] = standard_date('DATE_ATOM',time());
		    $mapData['Status'] = "ACTIVE";
		    $queryCmd = $this->db_sums->insert_string('Subscription_Product_Mapping',$mapData);
		    // error_log_shiksha("Subscription server : ".$queryCmd);
		    $this->db_sums->query($queryCmd);
	    }


	    $response = array("TransactionId"=>$transactionId ,"Created_Subscriptions"=>$subscriptionsArr);
	    $resp = array($response,'struct');
	    return $this->xmlrpc->send_response ($resp);
    }
    /**
     * API to delete Payment Info
     */
    function deletePaymentInfo($request)
    {
        $parameters = $request->output_parameters();
        $transactionId = $parameters['1'];
        $this->setDBHandle(TRUE);

        $queryCmd = "select Payment_Id from Payment where Transaction_Id = ?";
        error_log_shiksha("Subscription Server :".$queryCmd);
        $result = $this->db_sums->query($queryCmd, array($transactionId));
        $paymentId = $result->first_row()->Payment_Id;

        $queryCmd = "delete from Payment where Transaction_Id = ?";
        error_log_shiksha("Subscription Server :".$queryCmd);
        $this->db_sums->query($queryCmd, array($transactionId));

        $queryCmd = "delete from Payment_Details where Payment_Id = ?";
        error_log_shiksha("Subscription Server :".$queryCmd);
        $this->db_sums->query($queryCmd, array($paymentId));

        $queryCmd = "delete from Payee_Address_Details where Transaction_Id = ?";
        error_log_shiksha("Subscription Server :".$queryCmd);
        $this->db_sums->query($queryCmd, array($transactionId));
        $resp = array ("1",'string');
        return $this->xmlrpc->send_response ($resp);

    }
    /*
     * Consumption based on type, typeid and SubscriptionId
     * @param string subscriptionId
     * @param string type
     * @param int typeid
     * @param string startDate (optional)
     * @param string endDate (optional)
     */
    function sConsumeSubscription($request)
    {
        $parameters=$request->output_parameters();
        error_log("sssTTT".print_r($parameters, true));
        $subscriptionId = $parameters[1];
        $remainingQuant = $parameters[2]; // Optional
        $clientUserId = $parameters[3];
        $sumsUserId = $parameters[4];
        $baseProdId = $parameters[5]; // Optional
        $consumedTypeId = $parameters[6];
        $consumedType = $parameters[7];
        $startDate = $parameters[8]; // Optional for listing
        $endDate = $parameters[9]; // Optional for listing
        $this->setDBHandle(TRUE);

        /*
         * Determining current remaining quantitites for the selected
         * subscription and over-writing it on passed remainingQuant
         * Also auto determining optional parameters like baseProdId
         */
        $queryCmd = "Select * From Subscription_Product_Mapping where SubscriptionId in (select SubscriptionId from Subscription where SubscriptionId=? AND SubscrStatus='ACTIVE') AND BaseProdRemainingQuantity >= 1 AND Status='ACTIVE' AND date(SubscriptionEndDate) >= curdate() AND date(SubscriptionStartDate) <= curdate()";
        // error_log_shiksha('Query for getting all qualifying rows for product consumption ' . $queryCmd);
        $query = $this->db_sums->query($queryCmd, array($subscriptionId));
        if($query->result() != NULL){
            foreach ($query->result() as $row){
                $remainingQuant = $row->BaseProdRemainingQuantity;
                $baseProdId = $row->BaseProductId;
		$data['ConsumptionStartDate'] = $row->SubscriptionStartDate;
		$data['ConsumptionEndDate'] = $row->SubscriptionEndDate;
		
            }
        }else{
            $response = array("ERROR"=>1);
            $resp = array($response,'struct');
            return $this->xmlrpc->send_response ($resp);
        }

        if($remainingQuant>=1)
        {
            $queryCmd = "update Subscription_Product_Mapping set BaseProdRemainingQuantity = BaseProdRemainingQuantity-1 where SubscriptionId=? AND BaseProdRemainingQuantity >= 1 AND Status='ACTIVE' AND date(SubscriptionEndDate) >= curdate() AND date(SubscriptionStartDate) <= curdate()";
            // error_log_shiksha('total Quantity and Remaining Quantities query: ' . $queryCmd);
            $this->db_sums->query($queryCmd, array($subscriptionId));
        }else{
            $response = array("ERROR"=>1);
            $resp = array($response,'struct');
            return $this->xmlrpc->send_response ($resp);
        }

        /**
         * Query to deactivate product as remaining reached Zero!
         */
        if($remainingQuant == 1)
        {
            $queryToDeact = "update Subscription_Product_Mapping set Status='INACTIVE' where SubscriptionId= ? and Status='ACTIVE'";
            // error_log_shiksha('Query to deactivate product as remaining reached Zero!: ' . $queryToDeact);
            $this->db_sums->query($queryToDeact, array($subscriptionId));

            $queryToDeactMainSub = "update Subscription set SubscrStatus='INACTIVE' where SubscriptionId= ? and SubscrStatus='ACTIVE'";
            // error_log_shiksha('Query to deactivate product as remaining reached Zero!: ' . $queryToDeactMainSub);
            $this->db_sums->query($queryToDeactMainSub, array($subscriptionId));
        }

        /**
         * SubscriptionLog entry and Listing specific entries flow
         */
        if($remainingQuant>=1)
        {
	    if($startDate == '-1'){
		$data['ConsumptionStartDate'] = date('Y-m-d H:i:s');
	    }	    
	    	    
	    if($startDate != '-1' && $endDate != '-1'){
		
		$data['ConsumptionStartDate'] = $startDate;
		$data['ConsumptionEndDate'] = $endDate;
	    }
	    
            /**
             * Listing specific entries flow
             */
            if(($consumedType == 'course') ||
                ($consumedType == 'institute') ||
		($consumedType == 'university') ||
                ($consumedType == 'scholarship') ||
                ($consumedType == 'notification')){

                    /*
                     * Updating expiry date in Listings_main
                     */
                    $this->setDBHandle();
                    $features = array();
                     // this is commented because when downgrading a course subscription, expiry date of previous entry correspinding to current listing_type_id shoud not get updated.
                    // $features['expiry_date'] = $data['ConsumptionEndDate'];
                    $this->load->library('Listing_client');
                    $ListingClient = new Listing_client();
                    $result = $ListingClient->consumeProduct(1,$subscriptionId,$consumedType,$consumedTypeId,$features);
                }

            $data['ClientUserId'] = $clientUserId;
            $data['SumsUserId'] = $sumsUserId;
            $data['SubscriptionId'] = $subscriptionId;
            $data['ConsumedBaseProductId'] = $baseProdId;
            $data['ConsumedId'] = $consumedTypeId;
            $data['ConsumedIdType'] = $consumedType;
            $data['NumberConsumed'] = 1;

            $queryCmd = $this->db_sums->insert_string("SubscriptionLog",$data);
            // error_log_shiksha("query : ".$queryCmd);
            $this->db_sums->query($queryCmd);
            $logId= $this->db_sums->insert_id();
            $response = array("logId"=>$logId);
            $resp = array($response,'struct');
            return $this->xmlrpc->send_response ($resp);
        }
    }

    static public function verifyDate($date)
    {
	return (DateTime::createFromFormat('m/d/Y', $date) !== false);
    }
    
    /*
     * Consumption based on type, typeid and SubscriptionId
     * @param string subscriptionId
     * @param string type
     * @param int typeid
     * @param string startDate (optional)
     * @param string endDate (optional)
     */
    function consumePseudoSubscription($request)
    {
        $parameters=$request->output_parameters();
        error_log("BAABABAA");
        error_log("sssTTT".print_r($parameters, true));
        $subscriptionId = $parameters[1];
        $remainingQuant = $parameters[2]; // Optional
        $clientUserId = $parameters[3];
        $sumsUserId = $parameters[4];
        $baseProdId = $parameters[5]; // Optional
        $consumedTypeId = $parameters[6];
        $consumedType = $parameters[7];
        $startDate = $parameters[8]; // Optional for listing
        $endDate = $parameters[9]; // Optional for listing
        $this->setDBHandle(TRUE);

        /*
         * Determining current remaining quantitites for the selected
         * subscription and over-writing it on passed remainingQuant
         * Also auto determining optional parameters like baseProdId
         */
        $queryCmd = "Select * From Subscription_Product_Mapping where SubscriptionId in (select SubscriptionId from Subscription where SubscriptionId=? AND SubscrStatus='ACTIVE') AND BaseProdPseudoRemainingQuantity >= 1 AND Status='ACTIVE' AND date(SubscriptionEndDate) >= curdate() AND date(SubscriptionStartDate) <= curdate()";
        error_log_shiksha('Query for getting all qualifying rows for product consumption ' . $queryCmd);
        $query = $this->db_sums->query($queryCmd, array($subscriptionId));

        if($query->result() != NULL){
            foreach ($query->result() as $row){
                $remainingQuant = $row->BaseProdPseudoRemainingQuantity;
                $baseProdId = $row->BaseProductId;
                $SubscriptionEndDate = $row->SubscriptionEndDate;
            }
        }else{
            $response = array("ERROR"=>1);
            $resp = array($response,'struct');
            return $this->xmlrpc->send_response ($resp);
        }

        if($remainingQuant>=1)
        {
            $queryCmd = "update Subscription_Product_Mapping set BaseProdPseudoRemainingQuantity = BaseProdPseudoRemainingQuantity-1 where SubscriptionId=? AND BaseProdPseudoRemainingQuantity >= 1 AND Status='ACTIVE' AND date(SubscriptionEndDate) >= curdate() AND date(SubscriptionStartDate) <= curdate()";

            error_log_shiksha('total Quantity and Remaining Quantities query: ' . $queryCmd);
            $this->db_sums->query($queryCmd, array($subscriptionId));

            if(($consumedType == 'course') ||
                ($consumedType == 'institute') ||
                ($consumedType == 'scholarship') ||
                ($consumedType == 'notification')){

                    /*
                     * Updating SubscriptionId in Listings_main
                     */
                    $this->setDBHandle();
                    $features = array();
                    $features['pack_type']=$baseProdId;
                    $features['subscriptionEndDate']=$SubscriptionEndDate;

                    $this->load->library('Listing_client');
                    $ListingClient = new Listing_client();
                    $result = $ListingClient->consumeProduct(1,$subscriptionId,$consumedType,$consumedTypeId,$features);
                }
        }else{
            $response = array("ERROR"=>1);
            $resp = array($response,'struct');
            return $this->xmlrpc->send_response ($resp);
        }
    }

    /**
     * API to get Subscription Details
     */
    function sGetSubscriptionDetails($request)
    {
        $parameters=$request->output_parameters();
        $subscriptionId = $parameters[1];
        $this->setDBHandle(TRUE);
        $query="select * from Subscription_Product_Mapping,Base_Products where Subscription_Product_Mapping.SubscriptionId=? and Subscription_Product_Mapping.Status='ACTIVE' and Subscription_Product_Mapping.BaseProductId=Base_Products.BaseProductId";
        error_log_shiksha("query : ".$query);
        $arrResults = $this->db_sums->query($query, array($subscriptionId));
        $response=array();
        foreach ($arrResults->result_array() as $row)
        {
            array_push($response,array($row,'struct'));
        }

        $resp = array($response,'struct');
        return $this->xmlrpc->send_response ($resp);
    }

    function sGetMultipleSubscriptionDetails($request)
    {
        $parameters=$request->output_parameters();
        $subscriptionId = $parameters[1];
	$inactiveRequired = $parameters[2];
        $this->setDBHandle(TRUE);
	
	$this->initiateModel('read');
	//$subscriptionId = array('20',"zxvzx'sf",546);
	//To avoid the case of array defind in comment we are using loop with mysql_escape_string
	// foreach($subscriptionId as $key=>$value){
	//     $subscriptionIdstr.= mysql_escape_string($value)."','";
	// }
        $query="SELECT spm.SubscriptionId,
		spm.BaseProductId ,
		spm.TotalBaseProdQuantity ,
		spm.BaseProdRemainingQuantity ,
		spm.SubscriptionStartDate,
		spm.SubscriptionEndDate,
		spm.SubscrLastModifyTime,
		spm.Status as subscriptionStatus,
		spm.sumsEditingUserId,
		spm.oldRemainingQuantity,
		spm.oldSubscriptionStartDate,
		spm.oldSubscriptionEndDate,
		spm.disableComments,
		spm.BaseProdPseudoRemainingQuantity,
		bp.BaseProductId,
		bp.BaseProdCategory,
		bp.BaseProdSubCategory,
		bp.BaseProdType,
		bp.Description,
		bp.Status as baseProdStatus
		from Subscription_Product_Mapping spm,Base_Products bp where spm.SubscriptionId in ( ? )";
	
		//If data for incative records is also required
		if($inactiveRequired==false){
		$query.= " and spm.Status='ACTIVE' ";
		}
	
		$query.= " and spm.BaseProductId=bp.BaseProductId";
        $arrResults = $this->db_sums->query($query, array($subscriptionId));
        $response=array();
        foreach ($arrResults->result_array() as $row)
        {
            array_push($response,array($row,'struct'));
        }

        $resp = array($response,'struct');
        return $this->xmlrpc->send_response ($resp);
    }
    /**
     * API to add Free Subscriptions
     */
    function saddFreeSubscription($request)
    {
        $parameters = $request->output_parameters();
        $freeArr = $parameters['1'];

        $derivedProdId = $freeArr['derivedProdId'];
        $derivedQuantity = $freeArr['derivedQuantity'];
        $clientUserId = $freeArr['clientUserId'];
        $sumsUserId = $freeArr['sumsUserId'];
        $subsStartDate = $freeArr['subsStartDate'];

        $this->setDBHandle(TRUE);
        //Creating Quotation Part
        $quotationArray = array();
        $quotationArray['QuoteType'] = "Simple";
        $quotationArray['ClientId'] = $clientUserId;
        $quotationArray['CurrencyId'] = 1;
        $quotationArray['CreatedBy'] = $sumsUserId;
        $quotationArray['TotalPrice'] = 0;
        $quotationArray['TotalDiscount'] = 0;
        $quotationArray['TotalBasePrice'] = 0;
        $quotationArray['ServiceTax'] = 0;
        $quotationArray['NetAmount'] = 0;
        $quotationArray['RoundOffAmount'] = 0;
        $quotationArray['FinalSalesAmount'] = 0;

        $quotationProductsMap = array();
        array_push($quotationProductsMap,array(array(
            'DerivedProductId'=>$derivedProdId,
            'Quantity'=>$derivedQuantity,
            'Discount'=>0
        ),'struct'));

        //$this->load->library('sums_quotation_client');
        $objQuotation = new Sums_Quotation_client();

        $responseQuote = $objQuotation->addQuotation($this->appId,$quotationArray,$quotationProductsMap);
        $UIQuotationId = $responseQuote['UIQuotationId'];

        //Creating Transaction Part

        $trans['UIQuotationId'] = $UIQuotationId;
        $trans['clientUserId'] = $clientUserId;
        $trans['SalesBy'] = $sumsUserId;
        $trans['FinalSalesAmount'] = 0;
        $trans['CurrencyId'] = 1;
        $trans['sumsUserId'] = $sumsUserId;
        $trans['Sale_Type'] = "Full_Payment";
        /** uncomment this block to get functionality of LF-3233 : Start 
        $lastSalesById = $this->_getLatestSalesByInfo($clientUserId); 
        
        if(!empty($freeArr['byDowngradeCron']))  {
            $trans['SalesBy'] = $lastSalesById; 
        }
        ***  LF-3233 : End  **/    
        //$this->load->library('subscription_client');
        $objSubsClient = new Subscription_client();
        $responseTrans = $objSubsClient->addTransaction($this->appId,$trans);
        $transactionId = $responseTrans['TransactionId'];
        error_log_shiksha("free Trans Id : ".$transactionId);


        $data['approverId'] = $sumsUserId;
        $data['transactionId'] = array(array(),'struct');
        $data['derivedProdId'] = array(array(),'struct');
        $data['subsStartDate'] = array(array(),'struct');

        array_push($data['transactionId'][0], $transactionId);
        array_push($data['derivedProdId'][0],$derivedProdId);
        array_push($data['subsStartDate'][0], $subsStartDate);

        $objSumsManage = new Sums_manage_client();
        $response['result'] =  $objSumsManage->approveOpsDerived($this->appId,$data);
        //$this->load->library(array('subscription_client'));
        $objSubs = new Subscription_client();
        $response =  $objSubs->createDerivedSubscription($this->appId,$data);

        /*
         $queryCmd = "update Transaction_Queue set State='OPS_APPROVED' where TransactionId = $transactionId";
         error_log_shiksha("Changing Transaction Status to OPS_APPROVED for free Bronze-Listing :".$queryCmd);
         $this->db_sums->query($queryCmd);

         //Creating Subscription Part
          $respSubs =  $objSubsClient->createSubscription($this->appId,$transactionId,$subsStartDate,$sumsUserId);
          $subscriptionsArr = $respSubs['Created_Subscriptions'];

          $response = array("QuotationId"=>$UIQuotationId,"TransactionId"=>$transactionId,"Created_Subscriptions"=>$subscriptionsArr);
         */
        // error_log_shiksha("Final Free Subscription".print_r($response,true));
        $resp = array($response,'struct');
        return $this->xmlrpc->send_response ($resp);
    }

    
/**
 * [getLatestSalesByInfo description]
 * @Author Vinay Airan         <airan.vinay@gmail.com>
 * @Date   2015-08-06T13:53:48+0530
 * @param  [type]                   $clientUserId [description]
 * @return [type]                                 [description]
 */
    
    private function _getLatestSalesByInfo($clientUserId) {
        $sql = "select SalesBy from Transaction where ".
               "ClientUserId = ? order by ".
               "TransactionId desc limit 1";
        $this->setDBHandle(TRUE);
        $salesById = $this->db_sums->query($sql,array($clientUserId))->row_array();
        return $salesById['SalesBy'];
    }    


    /**
     * API to get Approver for Trail Sales
     */
    function getTrialApprover($userId)
    {
        $this->setDBHandle(TRUE);
        $queryCmd="select * from Sums_User_Details where userId=?";
        error_log_shiksha($queryCmd);
        $arrResults = $this->db_sums->query($queryCmd, array($userId));
        $approverId = '';
        $managerId = '';
        foreach($arrResults->result() as $row)
        {
            $managerId= $row->managerId;
            $Role= $row->Role;
        }

        $queryCmd="select * from Sums_Group_ACL_Mapping where ACLId=42 and UserGroupId=?";
        error_log_shiksha($queryCmd);
        $arrResults = $this->db_sums->query($queryCmd, array($Role));
        if(count($arrResults->result_array())>0)
        {
            $approverId=$userId;
        }
        else
        {
            $approverId=$this->getTrialApprover($managerId);
        }
        return $approverId;

    }
    /**
     * API to create Subscription for Derived
     */
    function screateDerivedSubscription($request)
    {
        $parameters = $request->output_parameters();

        $params = $parameters['1'];
        error_log_shiksha ("SSS ". print_r($transactionId,true));

        $approverId= $params['approverId'];
        $transactionIds = $params['transactionId'];
        $derivedProdIds = $params['derivedProdId'];
        $subsStartDates = $params['subsStartDate'];

        $this->setDBHandle(TRUE);

        $resp['subsArr'] = array(array(),'struct');
        $resp['transArr'] = array(array(),'struct');
        $resp['dervArr'] = array(array(),'struct');

        $numTrans = count($transactionIds);

        for($i=0;$i<$numTrans;$i++){
            $queryCmd = "select QPM.DerivedProductId, QPM.Quantity, DPM.BaseProductId, DPM.BaseProdQuantity, T.ClientUserId, (QPM.Quantity*DPM.BaseProdQuantity*BPPM.BasePropertyValue) as TotalBaseQuantity, DPRM.DerivedPropertyValue as Validity ,DPRM.DerivedPropertyId from Quotation Q, Transaction T, Quotation_Product_Mapping QPM, Derived_Products_Mapping DPM, Derived_Prod_Property_Mapping DPRM,Base_Prod_Property_Mapping BPPM where Q.UIQuotationId = T.UIQuotationId and Q.QuotationId = QPM.QuotationId and DPM.DerivedProductId = QPM.DerivedProductId and DPM.BaseProductId = BPPM.BaseProductId and BPPM.BasePropertyId = 1 and Q.Status='TRANSACTION' and QPM.Status = 'OPS_APPROVED' and T.TransactionId=? and T.CurrencyId=DPM.CurrencyId and DPRM.DerivedProductId=DPM.DerivedProductId and DPRM.DerivedPropertyId=4 and QPM.DerivedProductId=?";
            error_log_shiksha("Subscription server : ".$queryCmd);
            $query = $this->db_sums->query($queryCmd, array($transactionIds[$i], $derivedProdIds[$i]));
            $DerivedBaseMap = $query->result_array();
            error_log_shiksha(print_r($DerivedBaseMap,true));

            foreach($DerivedBaseMap as $Dbm){
                $data['TransactionId'] = $transactionIds[$i];
                $data['ClientUserId'] = $Dbm['ClientUserId'];
                $data['SumsUserId'] = $approverId;
                $data['BaseProductId'] = $Dbm['BaseProductId'];
                $data['DerivedProductId'] = $Dbm['DerivedProductId'];
                $data['SubscrStatus'] = "ACTIVE";
                $queryCmd = $this->db_sums->insert_string('Subscription',$data);
                error_log_shiksha("Subscription Table : ".$queryCmd);
                $this->db_sums->query($queryCmd);
                $subscriptionId = $this->db_sums->insert_id();
                array_push($resp['subsArr'][0],$subscriptionId);
                array_push($resp['transArr'][0],$transactionIds[$i]);
                array_push($resp['dervArr'][0],$Dbm['DerivedProductId']);

                $mapData['SubscriptionId'] = $subscriptionId;
                $mapData['BaseProductId'] = $Dbm['BaseProductId'];
                $mapData['TotalBaseProdQuantity'] = $Dbm['TotalBaseQuantity'];
                $mapData['BaseProdRemainingQuantity'] = $Dbm['TotalBaseQuantity'];
                $mapData['BaseProdPseudoRemainingQuantity'] = $Dbm['TotalBaseQuantity'];
                $mapData['SubscriptionStartDate'] = $subsStartDates[$i];
                /*
		 * Following lines removed
		   $dt = explode('-',$subsStartDates[$i]);
                   $mapData['SubscriptionEndDate'] = date(DATE_ATOM,mktime(0, 0, 0, $dt[1], $dt[2]+$Dbm['Validity'], $dt[0]));
                 */
		// Code Added As per Amit K check-in http://svn.infoedge.com:8080/Shiksha/changeset/30949
		$daysToAdd = '+1000 day';
		$newdate = strtotime ($daysToAdd, strtotime($subsStartDates[$i]));
		$mapData['SubscriptionEndDate'] = date(DATE_ATOM , $newdate);

                $mapData['SubscrLastModifyTime'] = standard_date('DATE_ATOM',time());
                $mapData['Status'] = "ACTIVE";
                $queryCmd = $this->db_sums->insert_string('Subscription_Product_Mapping',$mapData);
                error_log_shiksha("Subscription server : ".$queryCmd);
                $this->db_sums->query($queryCmd);

                $queryCmd = "update Quotation_Product_Mapping set Status='SUBSCRIPTION' where QuotationId = (select Q.QuotationId from Quotation Q,Transaction T where T.UIQuotationId=Q.UIQuotationId and Q.Status='TRANSACTION' and T.TransactionId=?) and DerivedProductId=? and Status='OPS_APPROVED'";
                error_log_shiksha('Quotation_Product_Mapping status update during Subscription creation ' . $queryCmd);
                $query = $this->db_sums->query($queryCmd, array($transactionIds[$i], $derivedProdIds[$i]));
            }
        }

        error_log_shiksha("CREATED SUBS".print_r($resp,true));
        $response = array($resp,'struct');
        return $this->xmlrpc->send_response ($response);
    }

    /**
     * Find all Subscriptions of a Transaction
     *
     */
    function ssubscriptionsForTrans($request){
        $parameters = $request->output_parameters();
        $params = $parameters['1'];

        //connect DB
        $this->setDBHandle(TRUE);

        $editingUserId = $params['editingUserId'];
        $transactionId = $params['transactionId'];
        error_log_shiksha("Cancel ".print_r($params,true));

        $queryCmd="select *,(select displayname from shiksha.tuser t where t.userid=S.ClientUserId) as ClientName,(select displayname from shiksha.tuser t where t.userid=S.SumsUserId) as SumsUserName from Subscription S,Subscription_Product_Mapping SPM,Base_Products B where S.TransactionId=? and S.SubscriptionId=SPM.SubscriptionId and B.BaseProductId = SPM.BaseProductId and S.SubscrStatus=SPM.Status and S.SubscrStatus in ('INACTIVE','ACTIVE')";
        error_log_shiksha("query Cancel Trans ".$queryCmd);
        $arrResults = $this->db_sums->query($queryCmd, array($transactionId));

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
     * API to disable Subscriptions
     */
    function sdisableSubscriptions($request)
    {
        $parameters = $request->output_parameters();

        $params = $parameters['1'];

        $editingUserId = $params['editingUserId'];
        $subscriptionIds = $params['subscriptionId'];
        $cancelComments = $params['cancelComments'];

        $this->setDBHandle(TRUE);

        $resp['subsArr'] = array(array(),'struct');

        $numTrans = count($subscriptionIds);

        for($i=0;$i<$numTrans;$i++){
            $queryCmd = "update Subscription set SubscrStatus='INACTIVE' where SubscriptionId=? and SubscrStatus='ACTIVE'";
            error_log_shiksha("Subscription server : ".$queryCmd);
            $query = $this->db_sums->query($queryCmd, array($subscriptionIds[$i]));

            $last_modify = standard_date('DATE_ATOM',time());
            $queryCmd = "update Subscription_Product_Mapping set Status='INACTIVE',SubscrLastModifyTime=?,sumsEditingUserId=?,disableComments=? where SubscriptionId=? and Status='ACTIVE'";
            error_log_shiksha("Subscr_prod_map server : ".$queryCmd);
            $query = $this->db_sums->query($queryCmd, array($last_modify, $editingUserId, $cancelComments, $subscriptionIds[$i]));

            array_push($resp['subsArr'][0],$subscriptionIds[$i]);
        }

        error_log_shiksha("Disabled SUBS".print_r($resp,true));
        $response = array($resp,'struct');
        return $this->xmlrpc->send_response ($response);
    }
    /**
     * API to change Subscription Dates
     */
    function schangeSubsDates($request)
    {
	    $parameters = $request->output_parameters();

	    $params = $parameters['1'];

	    $startDate = $params['startDate'];
	    $endDate = $params['endDate'];
	    $nav_subscription_line_no= $params['nav_subscription_line_no'];
	    $nav_subscription_id = $params['nav_subscription_id'];

	    $this->setDBHandle(TRUE);


	    $query1 = "select SubscriptionId from Subscription where nav_subscription_line_no=?  and NavSubscriptionId = ?";

	    $query = $this->db_sums->query($query1, array($nav_subscription_line_no, $nav_subscription_id));
	    $forNew = $query->result_array();
	    error_log(print_r($forNew,true));
	    
	    $subscriptionIdsModified = array();
	    
	    for($i =0;$i <count($forNew); $i++ ){
		    
		    $queryCmd = "select * from Subscription_Product_Mapping where SubscriptionId =? and ( Status='ACTIVE' OR ( Status='INACTIVE' AND SubscriptionEndDate='0000-00-00 00:00' ) ) limit 1";
		    $query = $this->db_sums->query($queryCmd, array($forNew[$i]['SubscriptionId']));
		    $forNewRow = $query->result_array();
		    error_log(print_r($forNewRow,true));

		    foreach($forNewRow as $val){
			    $existingStartDate= explode(" ",$val['SubscriptionStartDate']);
			    $existingEndDate= explode(" ",$val['SubscriptionEndDate']);
			    if( ($startDate == $existingStartDate[0]) && ($endDate == $existingEndDate[0] ) && ($endDate != '0000-00-00 00:00:00') ){
				    $resp = array('result'=>"ERROR: Same Dates as Before!!",'struct');
				    $response = array($resp,'struct');
				    return $this->xmlrpc->send_response ($response);
			    }
			    $data['SubscriptionId'] = $val['SubscriptionId'];
			    $subscriptionsModified[$i]['subsId'] = $data['SubscriptionId'];		    
			    $data['BaseProductId'] = $val['BaseProductId'];
			    $data['TotalBaseProdQuantity'] = $val['TotalBaseProdQuantity'];
			    $data['BaseProdRemainingQuantity'] = $val['BaseProdRemainingQuantity'];
			    $data['BaseProdPseudoRemainingQuantity'] = $val['BaseProdRemainingQuantity'];
			    $data['SubscriptionStartDate'] = $startDate;
			    $data['SubscriptionEndDate'] = $endDate;
			    $data['SubscrLastModifyTime'] = standard_date('DATE_ATOM',time());

                if($endDate == '0000-00-00') {
                    $data['Status'] = 'INACTIVE';
                    $queryCmd = "update Subscription set SubscrStatus = 'INACTIVE' where SubscriptionId = ? ";
                    error_log("Subscription inactive : ".$queryCmd);
                    $query = $this->db_sums->query($queryCmd, array($val['SubscriptionId']));

                } else {
                    $data['Status'] = 'ACTIVE';
                    $queryCmd = "update Subscription set SubscrStatus = 'ACTIVE' where SubscriptionId = ? ";
                    error_log("Subscription active : ".$queryCmd);
                    $query = $this->db_sums->query($queryCmd, array($val['SubscriptionId']));

                }
			    
			    $data['sumsEditingUserId'] = $editingUserId;
			    $data['oldRemainingQuantity'] = $val['BaseProdRemainingQuantity'];
			    $data['oldSubscriptionStartDate'] = $val['SubscriptionStartDate'];
			    $data['oldSubscriptionEndDate'] = $val['SubscriptionEndDate'];
                $queryCmd = $this->db_sums->insert_string('Subscription_Product_Mapping',$data);
			    error_log("Subscription_Prod_Map Table : ".$queryCmd);
			    $this->db_sums->query($queryCmd);

		    }
            
			$queryCmd = "update Subscription_Product_Mapping set Status='HISTORY' where SubscriptionId =? and not (date(SubscriptionStartDate)=? and date(SubscriptionEndDate)=?)";
		    error_log("Subscription_prod_map history : ".$queryCmd);
		    $query = $this->db_sums->query($queryCmd, array($forNew[$i]['SubscriptionId'], $startDate, $endDate));

	    }

	    $resp = array('subsIdArr'=>$subscriptionsModified);
	    error_log("Disabled SUBS".print_r($resp,true));
        
	    return $this->xmlrpc->send_response ($resp);
    }

    
    /**
     * API to change Subscription Status
     */
    function schangeSubsStatus($request)
    {
        $parameters = $request->output_parameters();

        $params = $parameters['1'];

        $editingUserId = $params['editingUserId'];
        $subscriptionId = $params['subscriptionId'];
        $status = $params['status'];

        $this->setDBHandle(TRUE);

        if( !(($status == 'ACTIVE') || ($status=='INACTIVE')) ){
            $resp = array('result'=>"ERROR",'struct');
            $response = array($resp,'struct');
            return $this->xmlrpc->send_response ($response);
        }

        $last_modify = standard_date('DATE_ATOM',time());

        if($status=='ACTIVE'){
            $queryCmd = "update Subscription set SubscrStatus='INACTIVE' where SubscriptionId=? and SubscrStatus=?";
            $queryCmd1 = "update Subscription_Product_Mapping set Status='INACTIVE',SubscrLastModifyTime=?,sumsEditingUserId=? where SubscriptionId=? and Status=?";
        }else if($status=='INACTIVE'){
            $queryCmd = "update Subscription set SubscrStatus='ACTIVE' where SubscriptionId=? and SubscrStatus=?";
            $queryCmd1 = "update Subscription_Product_Mapping set Status='ACTIVE',SubscrLastModifyTime=?,sumsEditingUserId=? where SubscriptionId=? and Status=?";
        }
        error_log_shiksha("Subscription_prod_map history : ".$queryCmd);
        $query = $this->db_sums->query($queryCmd, array($subscriptionId, $status));
        error_log_shiksha("Subscription_prod_map history : ".$queryCmd1);
        $query = $this->db_sums->query($queryCmd1, array($last_modify, $editingUserId, $subscriptionId, $status));

        $resp = array('result'=>"SUCCESS",'struct');
        error_log_shiksha("Disabled SUBS".print_r($resp,true));
        $response = array($resp,'struct');
        return $this->xmlrpc->send_response ($response);
    }

    /**
     * Find all Consumptions of a Subscription
     *
     */
    function sfetchConsumedIdsForSubs($request){
        $parameters = $request->output_parameters();
        $params = $parameters['1'];

        //connect DB
        $this->setDBHandle(TRUE);

        $editingUserId = $params['editingUserId'];
        $subscriptionId = $params['subscriptionId'];
        error_log_shiksha("Cancel ".print_r($params,true));

        $queryCmd="select *,(select displayname from shiksha.tuser t where t.userid=S.ClientUserId) as ClientName,(select displayname from shiksha.tuser t where t.userid=S.SumsUserId) as onBehalfOfCMSuser from SubscriptionLog S where S.SubscriptionId=? and Status='ACTIVE' order by ConsumedId";
        error_log_shiksha("query Cancel Trans ".$queryCmd);
        $arrResults = $this->db_sums->query($queryCmd, array($subscriptionId));

        $response=array();
        foreach ($arrResults->result_array() as $row)
        {
            if( ($row['ConsumedIdType']=='course') ||
                ($row['ConsumedIdType']=='institute') ||
                ($row['ConsumedIdType']=='notification') ||
                ($row['ConsumedIdType']=='scholarship') ){
                    $queryCmd = 'select listing_title from shiksha.listings_main where listing_type_id=? and listing_type=?';
                    $query1 = $this->db_sums->query($queryCmd, array($row['ConsumedId'], $row['ConsumedIdType']));
                    foreach ($query1->result_array() as $rowTmp){
                        $row['IdTitleOrName']=$rowTmp['listing_title'];
                    }
                }
            if( ($row['ConsumedIdType']=='sponsor') ||
                ($row['ConsumedIdType']=='featured') ){
                    $queryCmd = 'select keyword from shiksha.tSponsoredListingByKeyword where id=? and sponsorType=?';
                    $query1 = $this->db_sums->query($queryCmd, array($row['ConsumedId'], $row['ConsumedIdType']));
                    $row['IdTitleOrName']=$query1->first_row()->keyword;
                }
            if($row['ConsumedIdType']=='MainCollegeLink'){
                global $homePageMap;
                $keyPageArray = array_flip($homePageMap);
                $spaceNamedArray = str_replace("_"," ",$keyPageArray);

                $queryCmd = "select (select listing_title from shiksha.listings_main where listing_type_id=L.listing_type_id and listing_type='institute') as MainCollegeInfo, L.KeyId from shiksha.PageCollegeDb L where L.listing_type='institute' and L.id=?";
                $query1 = $this->db_sums->query($queryCmd, array($row['ConsumedId']));
                $row['IdTitleOrName']=$query1->first_row()->MainCollegeInfo;
                $row['IdTitleOrName'] .= " <b>SET AT</b> ".$spaceNamedArray[$query1->first_row()->KeyId];
            }
            array_push($response,array($row,'struct'));
        }

        $resp = array($response,'struct');
        //error_log_shiksha(print_r($resp,true)." SUMS: Get User details response!");
        return $this->xmlrpc->send_response ($resp);

    }

    /**
     * Change Start and End Dates of posted Listings/Keywords/MainCollege-Link
     *
     */
    function schangeConsumedIdDates($request)
    {
        $parameters = $request->output_parameters();

        $params = $parameters['1'];

        $editingUserId = $params['editingUserId'];
        $consumedId = $params['consumedId'];
        $consumedIdType = $params['consumedIdType'];
        $startDate = $params['startDate'];
        $endDate = $params['endDate'];
        $status = $params['status'];
        $subscriptionId = $params['subscriptionId'];

        $this->setDBHandle(TRUE);


        $queryCmd = "select * from SubscriptionLog where SubscriptionId=? and ConsumedId=? and ConsumedIdType=? and Status='ACTIVE' limit 1";
        error_log_shiksha("DB Query >> ".$queryCmd);
        $query = $this->db_sums->query($queryCmd, array($subscriptionId, $consumedId, $consumedIdType));
        $forNewRow = $query->result_array();
        error_log_shiksha(print_r($forNewRow,true));

        foreach($forNewRow as $val){
            $existingStartDate= explode(" ",$val['ConsumptionStartDate']);
            $existingEndDate= explode(" ",$val['ConsumptionEndDate']);
            if( ($startDate == $existingStartDate[0]) && ($endDate == $existingEndDate[0] ) ){
                $resp = array('result'=>"ERROR: Same Dates as Before!!",'struct');
                $response = array($resp,'struct');
                return $this->xmlrpc->send_response ($response);
            }
            $data['SubscriptionId'] = $val['SubscriptionId'];
            $data['ClientUserId'] = $val['ClientUserId'];
            $data['SumsUserId'] = $val['SumsUserId'];
            $data['ConsumedBaseProductId'] = $val['ConsumedBaseProductId'];
            $data['ConsumedId'] = $val['ConsumedId'];
            $data['ConsumedIdType'] = $val['ConsumedIdType'];
            $data['NumberConsumed'] = $val['NumberConsumed'];
            $data['ConsumptionStartDate'] = $startDate;
            $data['ConsumptionEndDate'] = $endDate;
            $data['oldConsumptionStartDate'] = $val['ConsumptionStartDate'];
            $data['oldConsumptionEndDate'] = $val['ConsumptionEndDate'];
            $queryCmd = $this->db_sums->insert_string('SubscriptionLog',$data);
            error_log_shiksha("SubscriptionLog Table : ".$queryCmd);
            $this->db_sums->query($queryCmd);
        }

        $queryCmd = "update SubscriptionLog set Status='HISTORY' where SubscriptionId=? and ConsumedId=? and ConsumedIdType=? and Status='ACTIVE' and not (date(ConsumptionStartDate)=? and date(ConsumptionEndDate)=?)";
        error_log_shiksha("SubscriptionLog history : ".$queryCmd);
        $query = $this->db_sums->query($queryCmd, array($subscriptionId, $consumedId, $consumedIdType, $startDate, $endDate));

        if($consumedIdType == 'course' ||
            $consumedIdType == 'institute' ||
            $consumedIdType == 'scholarship' ||
            $consumedIdType == 'notification')
        {
            $data['listing_type_id']= $consumedId;
            $data['listing_type']= $consumedIdType;
            $data['new_start_date']= $startDate;
            $data['new_end_date']= $endDate;
            $this->load->library(array('Listing_client'));
            $objList = new Listing_client();
            $response =  $objList->changeListingDates($this->appId,$data);
        }

        if($consumedIdType == 'sponsor' ||
            $consumedIdType == 'featured')
        {
            $keydata['set_time']= $startDate;
            $keydata['unset_time']= $endDate;
            $this->load->library(array('Listing_client'));
            $objList = new Listing_client();
            $response =  $objList->updateSponsorListingDetails($consumedId,$keydata);
        }

        if($consumedIdType == 'MainCollegeLink')
        {
            $maindata['StartDate']= $startDate;
            $maindata['EndDate']= $endDate;
            $this->load->library(array('Enterprise_client'));
            $objEnt = new Enterprise_client();
            $response =  $objEnt->supdateMainCollegeLink($consumedId,$maindata);
        }


        $resp = array('result'=>"SUCCESS",'struct');
        error_log_shiksha("Disabled SUBS".print_r($resp,true));
        $response = array($resp,'struct');
        return $this->xmlrpc->send_response ($response);
    }
    /**
     * Get Transaction Informations
     *
     */
    function sgetTransactionInfo($request){
        $parameters = $request->output_parameters();
        $transactionId = $parameters['1'];

        //connect DB
        $this->setDBHandle(TRUE);

        error_log_shiksha("Cancel ".print_r($params,true));

        $queryCmd="select * from Transaction where TransactionId=?";
        error_log_shiksha("query Cancel Trans ".$queryCmd);
        $arrResults = $this->db_sums->query($queryCmd, array($transactionId));

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
     * API to get Main College Link Subscription Details
     */
    function getMainCollegeLinkSubscriptionDetails($request)
    {
        $parameters = $request->output_parameters();
        $subscriptionId = $parameters['0'];

        //connect DB
        $this->setDBHandle(TRUE);
        $queryCmd="select BasePropertyId,BasePropertyValue from Base_Prod_Property_Mapping,Subscription where Subscription.SubscriptionId=? and Subscription.BaseProductId=Base_Prod_Property_Mapping.BaseProductId";
        error_log_shiksha("query getMainCollegeLinkSubs ".$queryCmd);
        $arrResults = $this->db_sums->query($queryCmd, array($subscriptionId));

        $response=array();
        foreach ($arrResults->result_array() as $row)
        {
            array_push($response,array($row,'struct'));
        }
        $resp = array($response,'struct');
        return $this->xmlrpc->send_response ($resp);
    }
    /**
     * API to consume Subscription with count
     */
    function sconsumeSubscriptionWithCount($request)
    {
        $parameters=$request->output_parameters();
        $consumptionArr = $parameters[1];
        $subscriptionId = $consumptionArr['subscriptionId'];
        $consumptionQuant = $consumptionArr['consumptionQuant'];
        $clientUserId = $consumptionArr['clientUserId'];
        $sumsUserId = $consumptionArr['sumsUserId'];
        $consumedTypeId = $consumptionArr['consumedTypeId'];
        $consumedType = $consumptionArr['consumedType'];
        $startDate = $consumptionArr['startDate'];
        $endDate = $consumptionArr['endDate'];
        $this->setDBHandle(TRUE);


        $queryCmd = "Select * From Subscription_Product_Mapping where SubscriptionId in (select SubscriptionId from Subscription where SubscriptionId=? AND SubscrStatus='ACTIVE') AND BaseProdRemainingQuantity >= ? AND Status='ACTIVE' AND date(SubscriptionEndDate) >= curdate() AND date(SubscriptionStartDate) <= curdate()";
        error_log('CONSUME Query for getting all qualifying rows for product consumption ' . $queryCmd);
        $query = $this->db_sums->query($queryCmd, array($subscriptionId, $consumptionQuant));

        if($query->result() != NULL){
            foreach ($query->result() as $row){
                error_log("CONSUME 123 ".$row->BaseProdRemainingQuantity." ".$consumptionQuant);
                if($row->BaseProdRemainingQuantity >=$consumptionQuant){
                    $numberConsumed = $consumptionQuant;

                    if($consumedType == 'mailer'){ //TODO: Presently No listing is consumed while adding an institute

                        $queryCmd = "update Subscription_Product_Mapping set BaseProdRemainingQuantity = BaseProdRemainingQuantity-? where SubscriptionId in (select SubscriptionId from Subscription where SubscriptionId=? AND SubscrStatus='ACTIVE') AND BaseProdRemainingQuantity >= ? AND Status='ACTIVE' AND date(SubscriptionEndDate) >= curdate() AND date(SubscriptionStartDate) <= curdate() ";
                        error_log(' CONSUME total Quantity and Remaining Quantities query: ' . $queryCmd);
                        $this->db_sums->query($queryCmd, array($numberConsumed, $subscriptionId, $numberConsumed));

                        //Query to deactivate product as remaining reached Zero!
                        if($row->BaseProdRemainingQuantity == $consumptionQuant){
                            $queryToDeact = "update Subscription_Product_Mapping set Status='INACTIVE' where SubscriptionId= ? and Status='ACTIVE'";
                            error_log_shiksha('Query to deactivate product as remaining reached Zero!: ' . $queryToDeact);
                            $this->db_sums->query($queryToDeact, array($row->SubscriptionId));
                            $queryToDeactMainSub = "update Subscription set SubscrStatus='INACTIVE' where SubscriptionId= ? and SubscrStatus='ACTIVE'";
                            error_log(' CONSUME Query to deactivate product as remaining reached Zero!: ' . $queryToDeactMainSub);
                            $this->db_sums->query($queryToDeactMainSub, array($row->SubscriptionId));
                        }
                    }else{
                        // Other products with SubscriptionIds to follow
                    }

                    /*$queryCmd = "select DerivedPropertyValue from Derived_Prod_Property_Mapping where DerivedPropertyId = (select DerivedPropertyId from Derived_Prod_Properties where DerivedPropertyName = 'Validity_Days') and DerivedProductId =  (select DerivedProductId from Subscription where SubscriptionId = $row->SubscriptionId)"; */
                    $queryCmd = "select BasePropertyValue from Base_Prod_Property_Mapping where BasePropertyId = (select BasePropertyId from Base_Prod_Properties where BasePropertyName = 'Duration') and BaseProductId =  (select BaseProductId from Subscription where SubscriptionId = ?)";
                    
                    $query1 = $this->db_sums->query($queryCmd, array($row->SubscriptionId));
                    
                    if ($query1->result() != NULL) {
                        $validityDays = $query1->first_row()->BasePropertyValue;
                    } else {
                        $validityDays = 90;
                    }

                    //error_log_shiksha("validity days SET: ".$validityDays);

                    $data['ClientUserId'] = $clientUserId;
                    $data['SumsUserId'] = $sumsUserId;
                    $data['SubscriptionId'] = $row->SubscriptionId;
                    $data['ConsumedBaseProductId'] = $row->BaseProductId;
                    $data['ConsumedId'] = $consumedTypeId;
                    $data['ConsumedIdType'] = $consumedType;
                    $data['NumberConsumed'] = $numberConsumed;
                    $data['ConsumptionStartDate'] = $startDate;
                    $data['ConsumptionEndDate'] = $endDate;

                    $queryCmd = $this->db_sums->insert_string("SubscriptionLog",$data);
                    error_log_shiksha("query : ".$queryCmd);
                    $this->db_sums->query($queryCmd);
                    $logId= $this->db_sums->insert_id();
                    $response = array("logId"=>$logId);
                    $resp = array($response,'struct');
                    return $this->xmlrpc->send_response ($resp);

                }
            }
        }else{
            $response = array("ERROR"=>1);
            $resp = array($response,'struct');
            return $this->xmlrpc->send_response ($resp);
        }
    }
    /**
     * API to get Credit Card Unpaid Payment Details
     * @param Array $request
     * @author shivam
     * @access public
     * @copyright Copyright &copy; 2009, shiksha
     * @return struct Array with Payment Details
     */
     function getCreditCardPaymentDetails($request)
	{
        $parameters=$request->output_parameters();
        $clientId=$parameters[0];
        $paymentId=$parameters[1];
        $partId=$parameters[2];
        $this->setDBHandle(TRUE);
		
        if(trim($paymentId) > 0)
        {
            $PaymentPart="and Payment_Details.Payment_Id=".$this->db_sums->escape($paymentId)." and Payment_Details.Part_Number=".$this->db_sums->escape($partId);
        }
	
	if(trim($clientId) > 0)
        {
            $ClinetPart=" and Transaction.ClientUserId=".$this->db_sums->escape($clientId);
        }
       
	 $query = "SELECT Payment_Details . * , (SELECT CurrencyCode FROM Currency WHERE Currency.CurrencyId = Transaction.CurrencyId ) AS CurrencyType, Transaction.TransactionId, TotalTransactionPrice as NetAmount , ( SELECT DerivedProductName FROM Derived_Products WHERE Derived_Products.DerivedProductId = Subscription.DerivedProductId ) AS ProductList FROM Transaction, Payment, Payment_Details,Subscription WHERE Transaction.TransactionId = Payment.Transaction_Id and Transaction.TransactionId = Subscription.TransactionId AND Payment.Payment_Id = Payment_Details.Payment_Id AND DueAmount > 0 AND Payment_Details.Payment_Mode = 'Credit Card(Offline)' $ClinetPart  group by Payment_Details.Part_Number,Payment_Details.Payment_Id"; 
       
        $arrResults = $this->db_sums->query($query);
      
	$content = $arrResults->result_array();
	
	$response = strtr(base64_encode(addslashes(gzcompress( json_encode($content) , 9))) , '+/=', '-_,');
	return $this->xmlrpc->send_response($response);
     
    }

    
    
    /**
     * API to update Credit Card Payment Information
     * * @param Array $request
     * @author shivam
     * @access public
     * @copyright Copyright &copy; 2009, shiksha
     * @return struct Array with Success/Failure
     */

    function updateCreditCardPaymentDetails($request) {

        $parameters=$request->output_parameters();
        $paymentId = $parameters[0];
        $partId = $parameters[1];
        $creditCardTransactionId= $parameters[2];
        $paymentDate = $parameters[3];
        $loggedInUser = $parameters[4];
        $Credit_Log_Table_Primary_Key = $parameters[5];
        $counter = $parameters[6];

        $this->setDBHandle(TRUE);

	$Credit_Log_Table_Key_query = "select Partially_Paid_Amount, payment_gateway from CreditCardLogs where id = ?";
        $result = $this->db_sums->query($Credit_Log_Table_Key_query, array($Credit_Log_Table_Primary_Key));
        $Partially_Paid_Amount = 0;
        foreach($result->result_array() as $row){
            $Partially_Paid_Amount = $row['Partially_Paid_Amount'];
            $payment_gateway = $row['payment_gateway'];
        }
	
	
        if($payment_gateway == 'ICICI')
        {
	    $query = "update SUMS.Payment_Details as S set S.isPaid='Paid' ,S.Payment_Modify_Date = now(),S.Amount_Received = round((S.Amount_Received + $Partially_Paid_Amount),2),S.DueAmount = round((S.DueAmount - $Partially_Paid_Amount),2) ,S.PayGatewaymode='ICICI', S.Cheque_No=?, S.Cheque_Date=? , S.loggedInUserId=? where S.Payment_Id=? and Part_Number=?"; 
            //$query1 = "update SUMS.CreditCardLogs as S set S.flag='Done' where S.id = $Credit_Log_Table_Primary_Key";
            $arrResults = $this->db_sums->query($query, array($creditCardTransactionId, $paymentDate, $loggedInUser, $paymentId, $partId));
        }
        elseif($payment_gateway == 'CCAVENUE' && $counter == 2)
        {
            $query1 = "update SUMS.CreditCardLogs as S set S.flag='Failed' where S.id = ?";
            $arrResult = $this->db_sums->query($query1, array($Credit_Log_Table_Primary_Key));
        }
        else
        {
            $query = "update SUMS.Payment_Details as S set S.isPaid='Paid' ,S.Payment_Modify_Date = now(),S.Amount_Received = round((S.Amount_Received + $Partially_Paid_Amount),2),S.DueAmount = round((S.DueAmount - $Partially_Paid_Amount),2) ,S.PayGatewaymode='CCAVENUE', S.Cheque_No=?, S.Cheque_Date=? , S.loggedInUserId=? where S.Payment_Id=? and Part_Number=?"; 
            $query1 = "update SUMS.CreditCardLogs as S set S.flag='Done' where S.id = ?";
            $arrResults = $this->db_sums->query($query, array($creditCardTransactionId, $paymentDate, $loggedInUser, $paymentId, $partId));
            $arrResult = $this->db_sums->query($query1, array($Credit_Log_Table_Primary_Key));
        }
        
        $success=1;
        $response = array("result"=>$success);
        $resp = array($response,'struct');
        return $this->xmlrpc->send_response($resp);

    }


    function updatePayPalPaymentDetails($request) {

        $parameters=$request->output_parameters();
        $paymentId = $parameters[0];
        $partId = $parameters[1];
        $creditCardTransactionId= $parameters[2];
        $paymentDate = $parameters[3];
        $loggedInUser = $parameters[4];
        $key = $parameters[5];
        $counter = $parameters[6];

        $this->setDBHandle(TRUE);

        if($counter == 1)
        {


        $query = "update SUMS.Payment_Details as S set S.isPaid='Paid' ,S.Payment_Modify_Date = now(),S.PayGatewaymode='PAYPAL', S.Cheque_No=?,S.Amount_Received = S.DueAmount,S.DueAmount = 0, S.Cheque_Date=? , S.loggedInUserId=? where S.Payment_Id=? and Part_Number=?"; 

            $querycmd = "update SUMS.CreditCardLogs set flag='Done' where id=?";

            $this->db_sums->query($query, array($creditCardTransactionId, $paymentDate, $loggedInUser, $paymentId, $partId));

            $this->db_sums->query($querycmd, array($key));
        }
        elseif($counter == 2)
        {

            $query1 = "update SUMS.CreditCardLogs as S set S.flag='Failed' where S.id=?";

            $this->db_sums->query($query1, array($key));

        }



        $response = array("result"=>"success");
        $resp = array($response,'struct');
        return $this->xmlrpc->send_response($resp);




    }


    /** API to subscription features and corresponding properties
     * @param subscriptionId
     */
    function sgetFeaturesForSubscription($request){
        $parameters = $request->output_parameters();
        $subscriptionId = $parameters['0']; // All Passed Parameters

        //connect DB
        $this->setDBHandle(TRUE);

        // Get all Products and their properties

        $queryCmd = "select Subscription.SubscriptionId,B.BaseProductId,BaseProdCategory,BaseProdSubCategory,M.BasePropertyId,BasePropertyName,BasePropertyValue from Base_Products B, Base_Prod_Property_Mapping M,Base_Prod_Properties P, Subscription where Subscription.SubscriptionId=? AND Subscription.BaseProductId=B.BaseProductId AND B.BaseProductId = M.BaseProductId AND M.BasePropertyId=P.BasePropertyId";
        error_log('Product Feature Selection Query command is: ' . $queryCmd);
        $query = $this->db_sums->query($queryCmd, array($subscriptionId));


        $prodFeatureArr = array();
        foreach ($query->result() as $row){
            if (!is_array($prodFeatureArr['subsFeatures']))
            {
                $prodFeatureArr['subsFeatures'] = array(array(),'struct');
            }
            $prodFeatureArr['subsFeatures'][0]['SubscriptionId']=$row->SubscriptionId;
            $prodFeatureArr['subsFeatures'][0]['BaseProductId']=$row->BaseProductId;
            $prodFeatureArr['subsFeatures'][0]['BaseProdCategory']=$row->BaseProdCategory;
            $prodFeatureArr['subsFeatures'][0]['BaseProdSubCategory']=$row->BaseProdSubCategory;

            if (!is_array($prodFeatureArr['subsFeatures'][0]['Property']))
            {
                $prodFeatureArr['subsFeatures'][0]['Property'] = array(array(),'struct');

            }
            $prodFeatureArr['subsFeatures'][0]['Property'][0][$row->BasePropertyName]=$row->BasePropertyValue;
        }

        $response = array($prodFeatureArr,'struct');
        error_log_shiksha("Product Features RESPONSE: ".print_r($response,true));
        return $this->xmlrpc->send_response($response);
    }

    /** API to increment Subscription count
     * @param Array having subscriptionId and Count
     */
    function incrementPseudoBaseQuantForSubscription($request){
        $parameters = $request->output_parameters();
        $appId = $parameters['0'];
        $Params = $parameters['1']; // All Transaction Parameters

        $subscriptionId= $Params['subscriptionId'];
        $count = isset($Params['count'])?$Params['count']:1;
        error_log("SSSSSSSSS");
        //connect DB
        $this->setDBHandle(TRUE);

        $queryCmd = "update Subscription_Product_Mapping set BaseProdPseudoRemainingQuantity = (BaseProdPseudoRemainingQuantity+?), Status='ACTIVE' where SubscriptionId=? AND Status in ('ACTIVE','INACTIVE')";
        
		$this->db_sums->query($queryCmd, array($count,$subscriptionId));

        $queryCmdSub = "update Subscription set SubscrStatus='ACTIVE' where SubscriptionId=? AND SubscrStatus in ('INACTIVE','ACTIVE')";

        $this->db_sums->query($queryCmdSub, array($subscriptionId));

        $response = array("SUCCESS"=>1);
        $resp = array($response,'struct');
        return $this->xmlrpc->send_response ($resp);
    }

    function sConsumeLDBCredits($request)
    {
        $parameters=$request->output_parameters();
        error_log("sssTTT".print_r($parameters, true));
        $subscriptionId = $parameters[1];
        $consumeQuant = $parameters[2]; // Optional
        $clientUserId = $parameters[3];
        $sumsUserId = $parameters[4];
        $baseProdId = $parameters[5];
        $this->setDBHandle(TRUE);
        if($consumeQuant>0){
        	$queryCmd = "update Subscription_Product_Mapping set BaseProdRemainingQuantity = ABS(BaseProdRemainingQuantity-?)  where (BaseProdRemainingQuantity-?)>=0 and SubscriptionId=? AND BaseProdRemainingQuantity
            >= 1 AND Status='ACTIVE' AND date(SubscriptionEndDate) >= curdate() AND date(SubscriptionStartDate) <= curdate()";
        	
        	$this->db_sums->query($queryCmd, array((int)$consumeQuant,(int)$consumeQuant,$subscriptionId));
        }
        /*
        $queryToDeact = "update Subscription_Product_Mapping set Status='INACTIVE' where SubscriptionId= $subscriptionId and Status='ACTIVE' and BaseProdRemainingQuantity=0";
        error_log('Query to deactivate product as remaining reached Zero!: ' . $queryToDeact);
        $this->db_sums->query($queryToDeact);

        $queryToDeactMainSub = "update Subscription set SubscrStatus='INACTIVE' where SubscriptionId= $subscriptionId and SubscrStatus='ACTIVE' and (select BaseProdRemainingQuantity from Subscription_Product_Mapping where SubscriptionId=$subscriptionId)=0";
        error_log('Query to deactivate product as remaining reached Zero!: ' . $queryToDeactMainSub);
        $this->db_sums->query($queryToDeactMainSub);
         */
        $response = array("result"=>1);
        $resp = array($response,'struct');
        return $this->xmlrpc->send_response ($resp);
    }

    /**
     * SubscriptionLog entry for LDB and Action specific entries flow
     */
    function sUpdateSubscriptionLog($request)
    {
        $parameters=$request->output_parameters();
        $subscriptionId = $parameters[1];
        $consumeQuant = $parameters[2]; // Optional
        $clientUserId = $parameters[3];
        $sumsUserId = $parameters[4];
        $baseProdId = $parameters[5];
        $consumedTypeId = $parameters[6];
        $consumedType = $parameters[7];
        $startDate = $parameters[8];
        $endDate =$parameters[9];

        $data['ConsumptionStartDate'] = $startDate;
        $data['ConsumptionEndDate'] = $endDate;
        $data['ClientUserId'] = $clientUserId;
        $data['SumsUserId'] = $sumsUserId;
        $data['SubscriptionId'] = $subscriptionId;
        $data['ConsumedBaseProductId'] = $baseProdId;
        $data['ConsumedId'] = $consumedTypeId;
        $data['ConsumedIdType'] = $consumedType;
        $data['NumberConsumed'] = $consumeQuant;
        $this->setDBHandle(TRUE);

        $queryCmd = $this->db_sums->insert_string("SubscriptionLog",$data);
        error_log_shiksha("query : ".$queryCmd);
        $this->db_sums->query($queryCmd);
        $logId= $this->db_sums->insert_id();
        $response = array("logId"=>$logId);
        $resp = array($response,'struct');
        return $this->xmlrpc->send_response ($resp);
    }
    
    /**
     * Get sgetSalesPersonInfo Informations
     *
     */
    function sgetSalesPersonInfo($request){
        $parameters = $request->output_parameters();
        $ClientUserId = $parameters['0'];
        //connect DB
        $this->setDBHandle(TRUE);
	
        error_log_shiksha("Cancel ".print_r($params,true));

        $queryCmd="SELECT a.SalesBy, a.ClientUserId, tuser.displayName, tuser.firstname, tuser.lastname, tuser.email FROM Transaction a, Sums_User_Details sud, shiksha.tuser, ( SELECT max( TransactionId ) mx,  ClientUserId FROM Transaction WHERE ClientUserId IN ('".$ClientUserId."') GROUP BY ClientUserId )b WHERE a.TransactionId = b.mx AND a.SalesBy = sud.EmployeeId and tuser.userid = sud.userId";
        
	
	error_log_shiksha("query Cancel Trans ".$queryCmd);
        $arrResults = $this->db_sums->query($queryCmd);

        $data = array();
        $response=array();
        foreach ($arrResults->result_array() as $row)
        {
			$data[$row['ClientUserId']] = $row;    
        }

        $resp = array($data,'struct');
        //error_log_shiksha(print_r($resp,true)." SUMS: Get User details response!");
        return $this->xmlrpc->send_response ($resp);
    }
    

    function sdeductLeadPortingCredits($request)
    {
        $parameters = $request->output_parameters();
        $subscriptionId = $parameters[0];
        
        $required_credit = 1;
        if(!empty($parameters[1])) {
            $required_credit = (int)$parameters[1];
        } 

        //connect DB
        $this->setDBHandle(TRUE);       

        $queryCmd="update Subscription_Product_Mapping set BaseProdRemainingQuantity = (BaseProdRemainingQuantity - ?),SubscrLastModifyTime = curdate() where SubscriptionId = ? and BaseProductId = ? and BaseProdRemainingQuantity > 0 ";

        $arrResults = $this->db_sums->query($queryCmd, array((int)$required_credit, $subscriptionId,(int)LEAD_PORTING_QUANTITY_BASED_BASE_PRODUCT_ID));

        $response = "Credits Deducted";
        $resp = array($response,'struct');
        return $this->xmlrpc->send_response ($resp);
    }



    function sgetValidSubscriptions($request)
    {
        $parameters=$request->output_parameters();
        $subscriptionsArr = $parameters[0];
        $singleSubscription = $parameters[1];
        $required_credit = 1;
        if(!empty($parameters[2])) {
            $required_credit = $parameters[2];
        }

        if($singleSubscription){

            $resp =  $this->checkValidSubscriptions($subscriptionsArr, $required_credit);
            return $this->xmlrpc->send_response($resp);
        }

        //connect DB
        $this->setDBHandle(TRUE);

        $subscriptionId = array();
        $SubscriptionData = array();

        $subids = implode(',',$subscriptionsArr); 

        $queryCmd= "SELECT S.* FROM `Subscription_Product_Mapping` S , Base_Products B where S.`BaseProductId` = B.`BaseProductId` and S.Status='ACTIVE' and SubscriptionId in (?) group by SubscriptionId ";
        $arrResults = $this->db_sums->query($queryCmd, array($subscriptionsArr));

        $response = array();
        foreach ($arrResults->result_array() as $row)
        {

            error_log("cancel ".print_r($row,true));
            $CheckSubscription = $this->checkArrayofPortingSubscriptions($row, $required_credit);
            $SubscriptionData[] = $CheckSubscription;    
        }

        $resp = array($SubscriptionData,'struct');
        return $this->xmlrpc->send_response ($resp);
    }



    function checkValidSubscriptions($subscriptionArray, $required_credit){
        //connect DB
        $this->setDBHandle(TRUE);       

        $subscriptionId = $subscriptionArray[0];
        $queryCmd= "SELECT S.* FROM `Subscription_Product_Mapping` S , Base_Products B where S.`BaseProductId` = B.`BaseProductId` and SubscriptionId = ? AND S.Status='ACTIVE'";

        $arrResults = $this->db_sums->query($queryCmd, array($subscriptionId));

        $subscriptionArray = $arrResults->result_array();

        $todays_date = date("Y-m-d"); 

        if(($required_credit > 0) && ($subscriptionArray[0]['BaseProductId'] == LEAD_PORTING_QUANTITY_BASED_BASE_PRODUCT_ID) && ($subscriptionArray[0]['BaseProdRemainingQuantity'] >= $required_credit) && ($subscriptionArray[0]['SubscriptionEndDate'] > $todays_date)) {
            return 'valid';
        }
        elseif(($subscriptionArray[0]['BaseProductId'] == LEAD_PORTING_DURATION_BASED_BASE_PRODUCT_ID || $subscriptionArray[0]['BaseProductId'] == RESPONSE_PORTING_DURATION_BASED_BASE_PRODUCT_ID  ) && $subscriptionArray[0]['SubscriptionEndDate'] > $todays_date ){
            return 'valid';
        }
        else{
            return 'invalid';
        }
    }


    function checkArrayofPortingSubscriptions($subscriptionArray, $required_credit){

        //connect DB
        $this->setDBHandle(TRUE);
        $todays_date = date("Y-m-d");
        if(($required_credit > 0) && ($subscriptionArray['BaseProductId'] == LEAD_PORTING_QUANTITY_BASED_BASE_PRODUCT_ID) && ($subscriptionArray['BaseProdRemainingQuantity'] >= $required_credit) && ($subscriptionArray['SubscriptionEndDate'] > $todays_date)) {         
	     
           return $subscriptionArray['SubscriptionId'];
	    
        } elseif(($subscriptionArray['BaseProductId'] == LEAD_PORTING_DURATION_BASED_BASE_PRODUCT_ID || $subscriptionArray['BaseProductId'] == RESPONSE_PORTING_DURATION_BASED_BASE_PRODUCT_ID  ) && $subscriptionArray['SubscriptionEndDate'] > $todays_date ){

            return $subscriptionArray['SubscriptionId'];

        } elseif($subscriptionArray['BaseProductId'] == GOLD_SL_LISTINGS_BASE_PRODUCT_ID  || $subscriptionArray['BaseProductId'] == GOLD_ML_LISTINGS_BASE_PRODUCT_ID ){
            return $subscriptionArray['SubscriptionId'];
        }
    }
    
    
    
    function sgetPortingSubscriptionType($request){
        
	$parameters=$request->output_parameters();
        $subscriptionsArr = $parameters[0];

        //connect DB
        $this->setDBHandle(TRUE);

	$SubscriptionData = array();
        $subids = implode(',',$subscriptionsArr); 
	
	$queryCmd= "SELECT S.BaseProductId,SubscriptionId FROM Subscription S where SubscriptionId in (?) group by SubscriptionId ";
       
        $arrResults = $this->db_sums->query($queryCmd, array($subscriptionsArr));

        $response = array();
        foreach ($arrResults->result_array() as $row){

    	    if($row['BaseProductId'] == RESPONSE_PORTING_DURATION_BASED_BASE_PRODUCT_ID || $row['BaseProductId'] == GOLD_ML_LISTINGS_BASE_PRODUCT_ID || $row['BaseProductId'] == GOLD_SL_LISTINGS_BASE_PRODUCT_ID){
    		  $SubscriptionData[(int)$row['SubscriptionId']] = 'response_duration';
    		
    	    }elseif($row['BaseProductId'] == LEAD_PORTING_DURATION_BASED_BASE_PRODUCT_ID){
    		  $SubscriptionData[(int)$row['SubscriptionId']] = 'lead_duration';
    		
    	    }else{
    		  $SubscriptionData[(int)$row['SubscriptionId']] = 'lead_quantity';
    	    }
	  
        }
	

	$resp = array($SubscriptionData,'struct');
        return $this->xmlrpc->send_response($resp);

    }

     /**
     * API to revert consumed Subscription
     */
    function supdateSubscriptionDetails($request)
    {
        $parameters = $request->output_parameters();
        $subscriptionId = $parameters[1];
        $numberConsumed = $parameters[2];
        
        $this->setDBHandle(TRUE);

        $queryCmd = "Select Status From Subscription_Product_Mapping where SubscriptionId = ? AND date(SubscriptionEndDate) >= curdate() AND date(SubscriptionStartDate) <= curdate()";
        error_log('Get Subscription Status ==== ' . $queryCmd);
        $query = $this->db_sums->query($queryCmd, array($subscriptionId));   
        $result = $query->row_array();

        $queryCmd = "update Subscription_Product_Mapping set BaseProdRemainingQuantity = BaseProdRemainingQuantity+?";
        if($result['Status'] == 'INACTIVE') {
            $queryCmd.= ", Status = 'ACTIVE'";
        }
        $queryCmd.= " where SubscriptionId = ?";
        error_log(' update BaseProdRemainingQuantity for a SubscriptionId: ' . $queryCmd);
        $this->db_sums->query($queryCmd, array((int)$numberConsumed,$subscriptionId));

        if($result['Status'] == 'INACTIVE') {
            $queryToDeactMainSub = "update Subscription set SubscrStatus='ACTIVE' where SubscriptionId= ? and SubscrStatus='INACTIVE'";
            error_log(' Query to Activate product as amount restored!: ' . $queryToDeactMainSub);
            $this->db_sums->query($queryToDeactMainSub, array($subscriptionId));
        }
    }

}
?>
