<?php
class PaymentProcessor
{
	private $ci;
    private $client;
	private $builder;
	private $error_msg;
	private $paymentRequest = NULL;
    
    function __construct()
    {
        $this->ci = & get_instance();
		$this->ci->load->library('Online_form_client');
        $this->client = new Online_form_client();
		
		$this->ci->load->library('Online/payment/builder/PaymentBuilder');
        $this->builder = new PaymentBuilder($this->client);
    }
	
	function setPaymentRequest($paymentRequest)
	{
		$this->paymentRequest = $paymentRequest;
	}
	
    function addOnlinePayment()
    {
		if(!$this->paymentRequest)
		{
			throw new Exception('No payment request has been set');
		}
			
		$this->builder->setObservers(array('PaymentLog'));
		$payment = $this->builder->getPayment();
		
		$payment->setData(array('onlineFormId' => $this->paymentRequest->getOnlineFormId(),
								'amount' => $this->paymentRequest->getAmount(),
								'userId' => $this->paymentRequest->getUserId(),
								'instituteId' => $this->paymentRequest->getInstituteId()
						));
		
        if($previousPaymentDetails = $payment->getDetails())
		{
			$payment->setData($previousPaymentDetails);
			if(!$payment->restartOnline())
			{
				$this->setErrorMsg('Unable to process the request'); return false;
			}
		}
		else
		{
			if(!$payment->addNewOnline())
			{
				$this->setErrorMsg('Unable to process the request'); return false;
			}
		}
        
        $paymentFields = $this->getPaymentFields($this->paymentRequest->getAmount(),$payment->getOrderId());
		return json_success($paymentFields);
    }
    

	function addOfflinePayment()
	{
		if(!$this->paymentRequest)
		{
			throw new Exception('No payment request has been set');
		}
		
		$this->builder->setObservers(array('PaymentLog','UserFormStatus','PaymentNotification'));
		$payment = $this->builder->getPayment();
		
		$payment->setData(array('onlineFormId' => $this->paymentRequest->getOnlineFormId(),
								'amount' => $this->paymentRequest->getAmount(),
								'userId' => $this->paymentRequest->getUserId(),
								'instituteId' => $this->paymentRequest->getInstituteId()
						));
		
		if($previousPaymentDetails = $payment->getDetails())
		{
			$payment->setData($previousPaymentDetails);
			if(!$payment->restartOffline())
			{
				$this->setErrorMsg('Unable to process the request'); return false;
			}
		}
		else
		{
			if(!$payment->addNewOffline())
			{
				$this->setErrorMsg('Unable to process the request'); return false;
			}
		}
		
		return true;
	}

    function processOnlinePayment($userId)
    {
		$this->builder->setObservers(array('PaymentLog','UserFormStatus','PaymentNotification'));
		$payment = $this->builder->getPayment();
		
		$this->ci->config->load('ccavenue_PaymentGatewayINR_settings',TRUE);
		$working_key = $this->ci->config->item('working_key','ccavenue_PaymentGatewayINR_settings');			

		$orderId = explode("-",$this->ci->input->post('orderNo'));

		$post_field = $this->ci->input->post('encResp');
		$post_field = $this->decrypt($post_field, $working_key);
		$dump_post_fields = $post_field;

		$post_field = explode('&', $post_field);

		foreach ($post_field as  $value) {
			$value = explode('=', $value);
			$new_post_fields[$value[0]] = $value[1];
		}

	    $returnPaymentId = $orderId[0];
		$returnUserId = $orderId[1];
		$returnFormId = $orderId[2];
		
		$payment->setPaymentId($returnPaymentId);
		
		$this->ci->load->model('Online/onlinepaymentmodel');
		$onlinepaymentmodel = new onlinepaymentmodel();
		$insert_data['payment_id']= $returnPaymentId;
		$insert_data['payment_data']= $dump_post_fields;

		//$onlinepaymentmodel->dump_oaf_payment_data($insert_data);

		if($paymentDetails = $payment->getDetails())
		{
			if($returnUserId != $userId || $paymentDetails['onlineFormId'] != $returnFormId)
			{
				throw new Exception("Invalid Transaction");
			}
			
			/*
			 * Update Payment object with data received from database
			 */ 
			$payment->setData($paymentDetails);			
						
			$merchantId = $this->ci->config->item('OF_Merchant_Id','ccavenue_PaymentGatewayINR_settings');			
						
            /*$this->ci->load->library('enterprise/ccavenue_rupeegateway_lib');
            $pgLib = new ccavenue_rupeegateway_lib();
			$pgLib->setMerchantId($merchantId);
            $verifyCheckSum = $pgLib->calculateverifyCheckSum($_POST['Order_Id'],$_POST['Amount'],$_POST['AuthDesc'],$_POST['Checksum']);
            
            if ($verifyCheckSum == "true" && $_POST['AuthDesc'] == "Y")
            {
                $status = "Success";
            }
            else if($verifyCheckSum == "true" && $_POST['AuthDesc'] == "N")
            {
                $status = "Failed";
            }
            else if( $verifyCheckSum == "true" && $_POST['AuthDesc']=="B")
            {    
                $status = "Waiting";
            }	
            else
            {
                $status = "Failed";
            }*/
			
			if($new_post_fields['order_status'] == 'Aborted'){
				$status = "Failed";
			}else if ($new_post_fields['order_status'] == 'Success') {
				$status = "Success";
			}

			$returnDataFromPaymentGateway = array(
				'status' => $status,
				'bankName' => $new_post_fields['card_name']
			);
			
			$payment->setData($returnDataFromPaymentGateway);
			if(!$payment->update())
			{
				throw new Exception("Unable to process");
			}
			
			return $status;
		}
		else
		{
			throw new Exception("Invalid Transaction");
		}
    }

	function updateDraftDetails($by,$onlineFormId,$userId,$data)
	{
		$this->builder->setObservers(array('PaymentLog','UserFormStatus'));
		$payment = $this->builder->getPayment();
		
		$payment->setData(array('onlineFormId'=>$onlineFormId,
								'userId'=>$userId
						));
		
		if($paymentDetails = $payment->getDetails())
		{
			$payment->setData($paymentDetails);
			
			$status = $by == 'user'?'Pending':'Success';
			$payment->setStatus($status);
			$payment->setData($data);
			
			return $payment->update();
		}
	}
	
    function getPaymentFields($amount,$orderId, $type ='oaf')
    {
        $this->ci->config->load('ccavenue_PaymentGatewayINR_settings',TRUE);

		if($type == 'enterprise') {
   			$redirect_url = $this->ci->config->item('redirectURL','ccavenue_PaymentGatewayINR_settings');
   		} else if($type == 'oaf') {
   			$redirect_url = $this->ci->config->item('OF_redirectURL','ccavenue_PaymentGatewayINR_settings');
   		}
		$paymentFields = array();
		
		$paymentFields['order_id'] 					= $orderId;
		$paymentFields['amount'] 					= $amount;
        $paymentFields['redirect_url'] 			= $redirect_url;
        $paymentFields['merchant_id'] 				= $this->ci->config->item('OF_Merchant_Id','ccavenue_PaymentGatewayINR_settings');
		$paymentFields['language'] 					= $this->ci->config->item('language','ccavenue_PaymentGatewayINR_settings');
	$paymentFields['cancel_url'] 				= $redirect_url;  //check with product
	$paymentFields['currency'] 					= $this->ci->config->item('currency','ccavenue_PaymentGatewayINR_settings');

        $payment_gateway_url 						= $this->ci->config->item('payment_gateway_url','ccavenue_PaymentGatewayINR_settings');
        $access_code 								= $this->ci->config->item('access_code','ccavenue_PaymentGatewayINR_settings');
        $workingKey 								= $this->ci->config->item('working_key','ccavenue_PaymentGatewayINR_settings');
		

        foreach ($paymentFields as $key => $value) {
			$merchant_data .= $key.'='.$value.'&';
        }

        $merchant_data = substr($merchant_data, 0,-1);
		$merchant_data = $this->encrypt($merchant_data, $workingKey);

		$return_data['access_code'] 			=  $access_code;
		$return_data['merchant_data']   		=  $merchant_data;
		$return_data['payment_gateway_url']   	=  $payment_gateway_url;

		return $return_data;

	}
	
	function setErrorMsg($error_msg)
	{
		$this->error_msg = $error_msg;
	}
	
	function getErrorMsg()
	{
		return $this->error_msg;
	}

	function encrypt($plainText,$key){
		
		$secretKey = $this->hextobin(md5($key));
		$initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
	  	$openMode = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '','cbc', '');
	  	$blockSize = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, 'cbc');
		$plainPad = $this->pkcs5_pad($plainText, $blockSize);
	  	if (mcrypt_generic_init($openMode, $secretKey, $initVector) != -1) 
		{
		      $encryptedText = mcrypt_generic($openMode, $plainPad);
	      	      mcrypt_generic_deinit($openMode);
		      			
		} 
		return bin2hex($encryptedText);
	}

	function decrypt($encryptedText,$key)
	{
		$secretKey = $this->hextobin(md5($key));
		$initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
		$encryptedText=$this->hextobin($encryptedText);
	  	$openMode = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '','cbc', '');
		mcrypt_generic_init($openMode, $secretKey, $initVector);
		$decryptedText = mdecrypt_generic($openMode, $encryptedText);
		$decryptedText = rtrim($decryptedText, "\0");
	 	mcrypt_generic_deinit($openMode);
		return $decryptedText;
		
	}

	function pkcs5_pad ($plainText, $blockSize)
	{
	    $pad = $blockSize - (strlen($plainText) % $blockSize);
	    return $plainText . str_repeat(chr($pad), $pad);
	}

	//********** Hexadecimal to Binary function for php 4.0 version ********

	function hextobin($hexString) 
   	 { 
        	$length = strlen($hexString); 
        	$binString="";   
        	$count=0; 
        	while($count<$length) 
        	{       
        	    $subString =substr($hexString,$count,2);           
        	    $packedString = pack("H*",$subString); 
        	    if ($count==0)
		    {
				$binString=$packedString;
		    } 
        	    
		    else 
		    {
				$binString.=$packedString;
		    } 
        	    
		    $count+=2; 
        	} 
  	        return $binString; 
    	  }


}
