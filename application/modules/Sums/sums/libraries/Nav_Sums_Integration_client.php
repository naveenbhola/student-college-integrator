<?php
/**
 * Class Nav_Sums_Integration_client represents Query APIs 
 *
 * @package 
 * @author  Shiksha Team
 */
class Nav_Sums_Integration_client {
	var $CI="";
	function init()  {
		$this->CI = & get_instance();
		$this->CI->load->helper ('url');
		$this->CI->load->library('xmlrpc');
		global $ip;
		$server_url = "https://$ip/sums/Nav_Sums_Integration_Server";
		$this->CI->xmlrpc->set_debug(0);
		$this->CI->xmlrpc->server($server_url,80);
	}
	function initread()  {
		$this->CI = & get_instance();
		$this->CI->load->helper ('url');
		$this->CI->load->library('xmlrpc');
		global $searchIP;
		$server_url = "https://$searchIP/sums/Nav_Sums_Integration_Server";
		$this->CI->xmlrpc->set_debug(0);
		$this->CI->xmlrpc->server($server_url,80);
	}
	
	function updateNavPaymentDetails($appId,$paymentid,$amountreceived,$requestArr,$duedate)
	{
		
		$this->init();
		$this->CI->xmlrpc->method('supdateNavPaymentDetails');
		$requestArr = array (array($appId,'int'),array($paymentid,'string'),array($amountreceived,'float'),array($requestArr,'struct'),array($duedate,'date'));
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
        
        function updateNavRefPaymentDetails($appId,$paymentid,$requestArr){
                $this->init();
		$this->CI->xmlrpc->method('supdateNavRefPaymentDetails');
		$requestArr = array (array($appId,'int'),array($paymentid,'string'),array($requestArr,'struct'));
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
                
        function updateNavPaymentReversal($appId,$amountreceived,$navRcptId)
        {
                $this->init();
                $this->CI->xmlrpc->method('supdateNavPaymentReversal');
                $requestArr = array (array($appId,'int'),array($amountreceived,'float'),array($navRcptId,'string'));
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
                
	function updateDues($appId,$paymentid,$Cheque_Date,$requestArr,$duedate)
	{
		$this->init();
		$this->CI->xmlrpc->method('supdateDues');
		$requestArr = array (array($appId,'int'),array($paymentid,'string'),array($Cheque_Date,'date'),array($requestArr,'struct'),array($duedate,'date'));
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
	
	function navUserMapping($appId,$userid,$companyid)
	{
		$this->init();
		$this->CI->xmlrpc->method('snavUserMapping');
		$requestArr = array (array($appId,'int'),array($userid,'int'),array($companyid,'string'));
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
	
	function addEnterpriseUser($appId,$userArray)
	{
		$this->init();
		$this->CI->xmlrpc->method('saddEnterpriseUser');
		$requestArr = array(array($appId,'int'),array($userArray,'struct'));
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
	
	function updatePaymentDetails($appId,$paymentid,$partpaymentid,$nav_payment_id)
	{
		$this->init();
		$this->CI->xmlrpc->method('supdatePaymentDetails');
		$requestArr = array (array($appId,'int'),array($paymentid,'int'),array($partpaymentid,'int'),array($nav_payment_id,'int'));
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
	
	
	function updateEnterpriseUserDetails($appId,$request,$userId)
	{
		$this->init();
		$this->CI->xmlrpc->method('updateEnterpriseUserDetails');
		$request = array (array($appId,'int'),array($request,'struct'),array($userId,'int'));
		$this->CI->xmlrpc->request($request);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return $this->CI->xmlrpc->display_response();
		}
	}

	function updateSalesPersonDetails($appId,$executeFieldMap)
	{
		$this->init();
		$this->CI->xmlrpc->method('updateSalesPersonDetails');
		$request = array (array($appId,'int'),array($executeFieldMap,'struct'));
		$this->CI->xmlrpc->request($request);
		if (!$this->CI->xmlrpc->send_request()) {
			return $this->CI->xmlrpc->display_error();
		} else {
			return $this->CI->xmlrpc->display_response();
		}
	}
	
	function addNAVPayments($appId,$transactionId,$paymentInfo,$paymentDetails)
	{
		$this->init();
		$this->CI->xmlrpc->method('saddNAVPayments');
		$requestArr = array(array($appId,'int'),array($transactionId,'int'),array($paymentInfo,'struct'),array($paymentDetails,'struct'));
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

	function addNAVXML($appId,$navtransactionId,$xmltype,$xml,$xmlstatus = 'fail')
	{
		$this->init();
		$this->CI->xmlrpc->method('saddNAVXML');
		$requestArr = array(array($appId,'int'),array($navtransactionId,'string'),array($xmltype,'string'),array($xml,'string'),array($xmlstatus,'string'));
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
	
	function updateNAVXML($appId,$xmlresponse,$id,$status)
	{
		$this->init();
		$this->CI->xmlrpc->method('supdateNAVXML');
		$requestArr = array(array($appId,'int'),array($xmlresponse,'string'),array($id,'int'),array($status,'string'));
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
	
	
	function insertUser($appId,$request)
	{
		$this->init();
		$this->CI->xmlrpc->method('snavUserMapping');
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
	
	function createSubscription($appId,$transactionId,$subStartDate,$opsUserId,$DerivedProductId,$clientuserid,$TotalBaseQuantity,$subsEndDate,$nav_subscription_line_no,$currency,$navsubscriptonid,$newProductFlag,$baseProdId,$company_id)
		{
		$this->init();
		$this->CI->xmlrpc->method('createSubscription');
		$requestArr = array (array($appId,'int'),array($transactionId,'string'),array($subStartDate,'string'),array($opsUserId,'string'),array($DerivedProductId,'string'),array($clientuserid,'string'),array($TotalBaseQuantity,'string'),array($subsEndDate,'string'),array($nav_subscription_line_no,'string'),array($currency,'string'),array($navsubscriptonid,'string'),array($newProductFlag,'string'),array($baseProdId,'string'),array($company_id,'string'));
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

	function getNewOnlinePayments($timeWindow)
	{
		$this->initread();
		$this->CI->xmlrpc->method('getNewOnlinePayments');
		$request = array(array($timeWindow,'struct'));
		$this->CI->xmlrpc->request($request); 
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return json_decode(gzuncompress(base64_decode($this->CI->xmlrpc->display_response())),TRUE);
		}
	}
	function getEnterpriseUsers($criteria,$timeWindow)
	{
		$this->initread();
		$this->CI->xmlrpc->method('getEnterpriseUsers');
		$request = array($criteria,array($timeWindow,'struct'));
		$this->CI->xmlrpc->request($request); 
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return json_decode(gzuncompress(base64_decode($this->CI->xmlrpc->display_response())),TRUE);
		}
	}
	
	function getUpdatedUsers($appid)
	{
		$this->initread();
		$this->CI->xmlrpc->method('getUpdatedUsers');
		$request =array (array($appId,'int'));
		$this->CI->xmlrpc->request($request); 
		
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return json_decode(gzuncompress(base64_decode($this->CI->xmlrpc->display_response())),TRUE);
		}
	}
	
	
	/*
	 * Cron management functions
	 */ 
	function getCronData()
	{
		$this->initread();
		$this->CI->xmlrpc->method('getCronData');
		$request = array();
		$this->CI->xmlrpc->request($request); 
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return json_decode(gzuncompress(base64_decode($this->CI->xmlrpc->display_response())),TRUE);
		}
	}
	
	function registerCron($cronPid,$status)
	{
		$ipAddress = S_REMOTE_ADDR;
		$this->init();
		$this->CI->xmlrpc->method('registerCron');
		$request = array($cronPid,$status,$ipAddress);
		$this->CI->xmlrpc->request($request); 
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return ($this->CI->xmlrpc->display_response());
		}
	}
	
	function updateCron($cronId,$status,$timeWindow)
	{
		$this->init();
		$this->CI->xmlrpc->method('updateCron');
		$request = array($cronId,$status,$timeWindow);
		$this->CI->xmlrpc->request($request); 
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return ($this->CI->xmlrpc->display_response());
		}
		
	}

	function getCities($appID) {
	
		$this->init();
		$this->CI->xmlrpc->method('sgetCities');
		$requestArr = array (array($appId,'int'));
		$this->CI->xmlrpc->request($requestArr);
		if (!$this->CI->xmlrpc->send_request())
		{
			return $this->CI->xmlrpc->display_error();
		}
		else
		{
			return (json_decode(gzuncompress(stripslashes(base64_decode(strtr($this->CI->xmlrpc->display_response(), '-_,', '+/=')))),true));
		}
	}

}
