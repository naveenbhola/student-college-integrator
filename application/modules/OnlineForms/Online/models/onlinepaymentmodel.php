<?php

class OnlinePaymentModel extends OnlineParentModel {

	function getPaymentDetailsByUserId($userid,$formid){
		$this->initiateModel();
		$payment_query = "select * from OF_Payments WHERE userId = ? AND onlineFormId = ? ";
		error_log('OF_USER_PAYMENT_DETAILS'.$payment_query);
		$query = $this->dbHandle->query($payment_query,array($userid,$formid));
		$results = $query->result_array();
		$payment_details_array = array();
		if(!empty($results) && is_array($results)) {
			foreach ($results as $row){
				$payment_details_array[] = $row;
			}
		}
		return $payment_details_array;
	}

	function getPaymentDetailsById($paymentId){
		$this->initiateModel();
		$payment_query = "select * from OF_Payments WHERE paymentId = ?";
		error_log('OF_USER_PAYMENT_DETAILS'.$payment_query);
		$query = $this->dbHandle->query($payment_query,array($paymentId));
		$results = $query->result_array();
		$payment_details_array = array();
		if(!empty($results) && is_array($results)) {
			foreach ($results as $row){
				$payment_details_array[] = $row;
			}
		}
		return $payment_details_array;
	}

	function addPayment($paymentData)
	{
		$this->initiateModel('write');

		$this->dbHandle->insert('OF_Payments', $paymentData);
		$paymentId = $this->dbHandle->insert_id();

		return $paymentId;
	}

	function updatePayment($paymentId,$paymentData)
	{
		$this->initiateModel('write');
		
		$this->dbHandle->where('paymentId', $paymentId);
		
		if($this->dbHandle->update('OF_Payments', $paymentData)) {
			return 1;	
		}
		else{
			return 0;
		}
	}
	
	function updatePaymentInformation($paymentId,$status)
        {
                $this->initiateModel('write');

                $paymentData = array('status'=>$status);
                $this->dbHandle->where('paymentId', $paymentId);

                if($this->dbHandle->update('OF_Payments', $paymentData)) {
                        return 1;       
                }
                else{
                        return 0;
                }
        }

	function updatePaymentLog($orderId,$paymentId,$date,$log,$status,$receiptDate='',$financeStatus='')
	{

		$this->initiateModel('write');
		$paymentData = array('status'=>$status,'log'=>$log);
		$this->dbHandle->where('paymentId', $paymentId);
		$this->dbHandle->where('orderId', $orderId);
		$this->dbHandle->where('date', $date);

		if($this->dbHandle->update('OF_PaymentLog', $paymentData)) {
			//After updating the OrderId status in Log table, we will also update the same in the Payments table
			if($status=='Success'){
				$query = "UPDATE OF_Payments set orderId=? where paymentId=? ";
				$this->dbHandle->query($query,array($orderId,$paymentId));
			}

			if($receiptDate!=''){
	                        $query = "UPDATE OF_PaymentFinanceFields set receiptDate=?, status=? where paymentId=? AND orderId=? AND transactionDate=?";
        	                $this->dbHandle->query($query,array($receiptDate,$financeStatus,$paymentId,$orderId,$date));
			}
			else{
	                        $query = "UPDATE OF_PaymentFinanceFields set status=? where paymentId=? AND orderId=? AND transactionDate=?";
        	                $this->dbHandle->query($query,array($financeStatus,$paymentId,$orderId,$date));
			}

			return 1;	
		}
		else{
			return 0;
		}


	}
	function addPaymentActivityLog($orderId,$paymentId,$currentdate,$status)
	{
		$this->initiateModel('write');

		$paymentLogData = array('activityDate'=>$currentdate,'orderId'=>$orderId,'paymentId'=>$paymentId,'status'=>$status);
		$this->dbHandle->insert('OF_PaymentActivityLog', $paymentLogData);

	}



	function addPaymentLog($paymentLogData)
	{
		$this->initiateModel('write');
		$paymentLogData['log'] = json_decode($paymentLogData['log']);
		$this->dbHandle->insert('OF_PaymentLog', $paymentLogData);
		$logId = $this->dbHandle->insert_id();

                $query = "INSERT INTO OF_PaymentFinanceFields (paymentId,orderId,status,receiptDate,transactionDate) VALUES (?,?,?,?,?)";
                $this->dbHandle->query($query,array($paymentLogData['paymentId'],$paymentLogData['orderId'],$paymentLogData['status'],$paymentLogData['date'],$paymentLogData['date']));

		return $logId;
	}

	public function getUserPaymentData($paymentId){
		$this->initiateModel('read');
		$query = "SELECT userId,onlineFormId from OF_Payments where paymentId=?";

		$result = $this->dbHandle->query($query,array($paymentId))->result_array();

		return $result;
	}


	public function insertData($data){
		$this->initiateModel('write');
		$this->dbHandle->insert_batch('OF_Response_queue',$data);
	}

	public function dump_oaf_payment_data($insert_data){
		$this->initiateModel('write');
		$this->dbHandle->insert('OF_Payment_Data',$insert_data);
	}
}
