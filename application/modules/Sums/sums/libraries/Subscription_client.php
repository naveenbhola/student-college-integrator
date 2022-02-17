<?php

class Subscription_client
{
	var $CI="";
	function init()
		{
		$this->CI = & get_instance();
		$this->CI->load->helper ('url');
		$this->CI->load->library('xmlrpc');
		global $ip;
		$server_url = "http://$ip/sums/Subscription_Server";
		$this->CI->xmlrpc->set_debug(0);
		$this->CI->xmlrpc->server($server_url,80);
		}
	
	function initread()
		{
		$this->CI = & get_instance();
		$this->CI->load->helper ('url');
		$this->CI->load->library('xmlrpc');
		global $searchIP;
		$server_url = "https://$searchIP/sums/Subscription_Server";
		$this->CI->xmlrpc->set_debug(0);
		$this->CI->xmlrpc->server($server_url,80);
		}
	function paymentMigration($appId)
		{
		$this->init();
		$this->CI->xmlrpc->method('paymentMigration');
		$requestArr = array (array($appId,'int'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		}	
	function addTransaction($appId,$request)
		{
		$this->init();
		error_log("1");
		$this->CI->xmlrpc->method('saddTransaction');
		$requestArr = array (array($appId,'int'),array($request,'struct'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		}
	
	function updateSubscription($appId,$request)
		{
		$this->init();
		$this->CI->xmlrpc->method('supdateSubscription');
		$requestArr = array (array($appId,'int'),array($request,'struct'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		}
	
	function addPaymentInfo($appId,$transactionId,$paymentInfo,$paymentDetails,$payeeAddress,$userId)
		{
		$this->init();
		$this->CI->xmlrpc->method('saddPaymentInfo');
		$requestArr = array(
			array($appId,'int'),
			array($transactionId,'int'),
			array($paymentInfo,'struct'),
			array($paymentDetails,'struct'),
			array($payeeAddress,'struct'),
			array($userId,'int')
		);
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		}
	function searchTransaction ($appId,$request)
		{
		$this->initread();
		$this->CI->xmlrpc->method('searchTransaction');
		$requestArr = array (array($appId,'int'),array($request,'struct'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		}
	
	function searchPayment($appId,$request,$filters='')
		{
		$this->initread();
		$this->CI->xmlrpc->method('searchPayment');
		$requestArr = array (array($appId,'int'),array($request,'struct'),array($filters,'string'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		}
	
	function submitMultiplePayment($appId,$paymentInfo,$otherPaymentDetails,$userId)
		{
		$this->init();
		$this->CI->xmlrpc->method('submitMultiplePayment');
		$requestArr = array (array($appId,'int'),array($paymentInfo,'struct'),array($otherPaymentDetails,'struct'),array($userId,'int'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		}
	
	function updateMultiplePaymentStatus($appId,$requestData,$status,$action,$userId)
		{
		$this->init();
		$this->CI->xmlrpc->method('updateMultiplePaymentStatus');
		$requestArr = array (array($appId,'int'),array($requestData,'struct'),array($status,'string'),array($action,'string'),array($userId,'string'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		}
	
	function submitEditPaymet($appId,$paymentId,$request,$subPayments,$mainPaymentInfo,$userId)
		{
		$this->init();
		$this->CI->xmlrpc->method('submitEditPaymet');
		$requestArr = array (array($appId,'int'),array($paymentId,'int'),array($request,'struct'),array($subPayments,'struct'),array($mainPaymentInfo,'struct'),array($userId,'int'));
		//echo "\n HHHHH HHHHHHHHH \n <pre>";print_r($requestArr);echo"</pre>";
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		}
	
	function cancelPaymet($appId,$paymentId,$request,$userId)
		{
		$this->init();
		$this->CI->xmlrpc->method('cancelPaymet');
		$requestArr = array (array($appId,'int'),array($paymentId,'int'),array($request,'struct'),array($userId,'int'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		}
	
	function getPaymentDetails($appId,$cvsPaymentIds,$FILTER)
		{
		$this->initread();
		$this->CI->xmlrpc->method('getPaymentDetails');
		$requestArr = array (array($appId,'int'),array($cvsPaymentIds,'string'),array($FILTER,'string'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		}	
	
	function getPaymentInfo($appId,$transactionId)
		{
		$this->initread();
		$this->CI->xmlrpc->method('sgetPaymentInfo');
		$requestArr = array (array($appId,'int'),array($transactionId,'string'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		}
	
		
	function createSubscription($appId,$transactionId,$subStartDate,$opsUserId,$DerivedProductId,$clientuserid,$TotalBaseQuantity,$subsEndDate,$nav_subscription_line_no,$currency,$navsubscriptonid)
		{
		$this->init();
		$this->CI->xmlrpc->method('createSubscription');
		$requestArr = array (array($appId,'int'),array($transactionId,'string'),array($subStartDate,'string'),array($opsUserId,'string'),array($DerivedProductId,'string'),array($clientuserid,'string'),array($TotalBaseQuantity,'string'),array($subsEndDate,'string'),array($nav_subscription_line_no,'string'),array($currency,'string'),array($navsubscriptonid,'string'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		}
	
	function deletePaymentInfo($appId,$transactionId)
		{
		$this->init();
		$this->CI->xmlrpc->method('deletePaymentInfo');
		$requestArr = array (array($appId,'int'),array($transactionId,'string'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		}
	
	function consumeSubscription($appId,$subscriptionId,$remainingQuant,$clientUserId,$sumsUserId,$baseProdId,$consumedTypeId,$consumedType,$startDate,$endDate)
		{
		$this->init();
		$this->CI->xmlrpc->method('sConsumeSubscription');
		$requestArr = array (
			array($appId,'int'),
			array($subscriptionId,'string'),
			array($remainingQuant,'string'),
			array($clientUserId,'string'),
			array($sumsUserId,'string'),
			array($baseProdId,'string'),
			array($consumedTypeId,'string'),
			array($consumedType,'string'),
			array($startDate,'string'),
			array($endDate,'string')
		);
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		}
	
	function getSubscriptionDetails($appId,$subscriptionId)
	{
		$this->initread();
		$this->CI->xmlrpc->method('sGetSubscriptionDetails');
		$requestArr = array (array($appId,'int'),array($subscriptionId,'string'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
	}
	
	function getMultipleSubscriptionDetails($appId,$subscriptionId,$inactiveRequired=false){
		$this->initread();
		$this->CI->xmlrpc->method('sGetMultipleSubscriptionDetails');
		$requestArr = array (array($appId,'int'),array($subscriptionId,'struct'),array($inactiveRequired,'boolean'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
	}
	
	function addFreeSubscription($appId,$request)
		{
		$this->init();
		$this->CI->xmlrpc->method('saddFreeSubscription');
		$requestArr = array (array($appId,'int'),array($request,'struct'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		}
	
	function createDerivedSubscription($appId,$request)
		{
		$this->init();
		$this->CI->xmlrpc->method('screateDerivedSubscription');
		$requestArr = array (array($appId,'int'),array($request,'struct'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		}
	
	function subscriptionsForTrans($appId,$request)
		{
		$this->initread();
		$this->CI->xmlrpc->method('ssubscriptionsForTrans');
		$requestArr = array (array($appId,'int'),array($request,'struct'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		}
	
	function disableSubscriptions($appId,$request)
		{
		$this->init();
		$this->CI->xmlrpc->method('sdisableSubscriptions');
		$requestArr = array (array($appId,'int'),array($request,'struct'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		}
	
	function changeSubsDates($appId,$request)
		{
		$this->init();
		$this->CI->xmlrpc->method('schangeSubsDates');
		$requestArr = array (array($appId,'int'),array($request,'struct'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		}
	
	function changeSubsStatus($appId,$request)
		{
		$this->init();
		$this->CI->xmlrpc->method('schangeSubsStatus');
		$requestArr = array (array($appId,'int'),array($request,'struct'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		}
	
	function fetchConsumedIdsForSubs($appId,$request)
		{
		$this->initread();
		$this->CI->xmlrpc->method('sfetchConsumedIdsForSubs');
		$requestArr = array (array($appId,'int'),array($request,'struct'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		}
	
	function changeConsumedIdDates($appId,$request)
		{
		$this->init();
		$this->CI->xmlrpc->method('schangeConsumedIdDates');
		$requestArr = array (array($appId,'int'),array($request,'struct'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		}
	function getTransactionInfo($appId,$transactionId)
		{
		$this->initread();
		$this->CI->xmlrpc->method('sgetTransactionInfo');
		$requestArr = array (array($appId,'int'),array($transactionId,'string'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		}
	function getMainCollegeLinkSubscriptionDetails($subscriptionId)
		{
		$this->initread();
		$this->CI->xmlrpc->method('getMainCollegeLinkSubscriptionDetails');
		$requestArr = array (array($subscriptionId,'int'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		}
	
	function consumeSubscriptionWithCount($appId,$consumptionArr)
		{
		$this->init();
		$this->CI->xmlrpc->method('sconsumeSubscriptionWithCount');
		$requestArr = array (array($appId,'int'),array($consumptionArr,'struct'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		}
	
	function getCreditCardPaymentDetails($clientUserId,$paymentId='',$partId='')
		{
		$this->init();
		$this->CI->xmlrpc->method('getCreditCardPaymentDetails');
		$requestArr = array (array($clientUserId,'string'),array($paymentId,'string'), array($partId,'string'));
		
			$this->CI->xmlrpc->request($requestArr);
			if ( ! $this->CI->xmlrpc->send_request()){
				return $this->CI->xmlrpc->display_error();
			}else{
				return (json_decode(gzuncompress(stripslashes(base64_decode(strtr($this->CI->xmlrpc->display_response(), '-_,', '+/=')))),true));
			}
		
		
		}
		
		
	function sgetPaymentdetails($paymentId)
		{
		error_log("d");
		$this->init();
		$this->CI->xmlrpc->method('sgetPaymentdetails');
		$requestArr = array (array($paymentId,'string'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		}
	
		
		
		
		
	function updateCreditCardPaymentDetails($paymentId,$partId,$creditCardTransactionId,$paymentDate,$loggedInUser,$key="",$flag="") {
		$this->init();
		$this->CI->xmlrpc->method('updateCreditCardPaymentDetails');
		$requestArr = array (array($paymentId,'int'),array($partId,'int'),array($creditCardTransactionId,'string'),array($paymentDate,'string'),array($loggedInUser,'string'),array($key,'int'),array($flag,'int'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
	}
	
	function updatePayPalPaymentDetails($paymentId,$partId,$creditCardTransactionId,$paymentDate,$loggedInUser,$key="",$flag="")
	{
		$this->init();
		$this->CI->xmlrpc->method('updatePayPalPaymentDetails');
		$requestArr = array (array($paymentId,'int'),array($partId,'int'),array($creditCardTransactionId,'string'),array($paymentDate,'string'),array($loggedInUser,'string'),array($key,'int'),array($flag,'int'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		
		
	}

         function getFeaturesForSubscription($subscriptionId)
	 {
	 	$this->initread();
	 	$this->CI->xmlrpc->method('sgetFeaturesForSubscription');
		$requestArr = array (array($subscriptionId,'int'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
	 }
         
	 function consumePseudoSubscription($appId,$subscriptionId,$remainingQuant,$clientUserId,$sumsUserId,$baseProdId,$consumedTypeId,$consumedType,$startDate,$endDate)
	 {
		$this->init();
		$this->CI->xmlrpc->method('consumePseudoSubscription');
		$requestArr = array (
					array($appId,'int'),
					array($subscriptionId,'string'),
					array($remainingQuant,'string'),
					array($clientUserId,'string'),
					array($sumsUserId,'string'),
					array($baseProdId,'string'),
					array($consumedTypeId,'string'),
					array($consumedType,'string'),
					array($startDate,'string'),
					array($endDate,'string')
				);
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
            }

         function incrementPseudoBaseQuantForSubscription($appId,$request)
	{
		$this->init();
                $this->CI->xmlrpc->method('incrementPseudoBaseQuantForSubscription');
		$requestArr = array (array($appId,'int'),array($request,'struct'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
	}

	 function consumeLDBCredits($appId,$subscriptionId,$consumeQuant,$clientUserId,$sumsUserId)
	 {
		 $this->init();
		 $this->CI->xmlrpc->method('sConsumeLDBCredits');
		 $requestArr = array (
				 array($appId,'int'),
				 array($subscriptionId,'string'),
				 array($consumeQuant,'string'),
				 array($clientUserId,'string'),
				 array($sumsUserId,'string'),
				 array($baseProdId,'string')
				 );
		 $this->CI->xmlrpc->request($requestArr);
		 if (!$this->CI->xmlrpc->send_request())
		 {
			 return $this->CI->xmlrpc->display_error();
		 }
		 else
		 {
			 return $this->CI->xmlrpc->display_response();
		 }
	 }
	 function updateSubscriptionLog($appId,$subscriptionId,$consumeQuant,$clientUserId,$sumsUserId,$baseProdId,$consumedTypeId,$consumedType,$startDate,$endDate)
	 {
		 $this->init();
		 $this->CI->xmlrpc->method('sUpdateSubscriptionLog');
		 $requestArr = array (
				 array($appId,'int'),
				 array($subscriptionId,'string'),
				 array($consumeQuant,'string'),
				 array($clientUserId,'string'),
				 array($sumsUserId,'string'),
				 array($baseProdId,'string'),
				 array($consumedTypeId,'int'),
				 array($consumedType,'string'),
				 array($startDate,'string'),
				 array($endDate,'string')
				 );
		 $this->CI->xmlrpc->request($requestArr);
		 if (!$this->CI->xmlrpc->send_request())
		 {
			 return $this->CI->xmlrpc->display_error();
		 }
		 else
		 {
			 return $this->CI->xmlrpc->display_response();
		 }
	 }
	 function sgetSalesPersonInfo($clientUserId)
	 {
		 $this->init();
		 $this->CI->xmlrpc->method('sgetSalesPersonInfo');
		 if(is_array($clientUserId)){
		 	$clientUserId = implode("','",$clientUserId);
		 }
		 $requestArr = array (
				 array($clientUserId,'string')
				 );
		 $this->CI->xmlrpc->request($requestArr);
		 if (!$this->CI->xmlrpc->send_request())
		 {
			 return $this->CI->xmlrpc->display_error();
		 }
		 else
		 {
			 return $this->CI->xmlrpc->display_response();
		 }
	 }
	 
	  function sdeductLeadPortingCredits($subscriptionId, $required_credit)	 {
		 $this->init();
		 $this->CI->xmlrpc->method('sdeductLeadPortingCredits');
		 $requestArr = array (
				 array($subscriptionId,'string'),
				 array($required_credit, 'int')
				 );
		 $this->CI->xmlrpc->request($requestArr);
		 if (!$this->CI->xmlrpc->send_request())
		 {
			 return $this->CI->xmlrpc->display_error();
		 }
		 else
		 {
			 return $this->CI->xmlrpc->display_response();
		 }
	 }
	 
	 function sgetValidSubscriptions($subscriptionArray,$singleSubscription, $required_credit='')
	 {
		
		$this->init();
                $this->CI->xmlrpc->method('sgetValidSubscriptions');
		$requestArr = array(array($subscriptionArray,'struct'),
                            array($singleSubscription, 'int'),
                            array($required_credit, 'int'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		
	 }
	 
	 function getPortingSubscriptionType($subscriptionArray)
	 {
		$this->init();
                $this->CI->xmlrpc->method('sgetPortingSubscriptionType');
		$requestArr = array(array($subscriptionArray,'struct'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
		
	 }


	function updateSubscriptionDetails($appId,$subscriptionId, $numberConsumed)
		{
		$this->init();
		$this->CI->xmlrpc->method('supdateSubscriptionDetails');
		$requestArr = array (array($appId,'int'),array($subscriptionId,'int'),array($numberConsumed,'int'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
	}

}
