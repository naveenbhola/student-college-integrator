<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Purpose       : This Class enables you to perform all sort of operations relating Paytm gratification APIs.
* Author        : Romil/Nikita
* Creation Date : 9th Jan 2015
*/
define('HMAC_SHA256_ALGORITHM'                  , 'sha256');
define('WALLET_HEADER_KEY_CONTENT_MD5'          , 'x-wallet-content-hash');
define('WALLET_HEADER_KEY_CONTENT_HASH_ALGO'    , 'x-wallet-content-hash-algo');
define('WALLET_HEADER_KEY_CONTENT_LENGTH'       , 'x-wallet-content-length');
define('WALLET_HEADER_KEY_UNIX_DATE'            , 'x-wallet-content-unix-date');
define('WALLET_HEADER_KEY_CONTENT_USER_KEY'     , 'x-wallet-content-user-key');
define('WALLET_HEADER_KEY_NONCE'                , 'x-wallet-content-nonce');
define('WALLET_HEADER_KEY_CONTENT_HMAC'         , 'x-wallet-content-hmac');
define('WALLET_HEADER_KEY_CONTENT_HMAC_ALGO'    , 'x-wallet-content-hmac-algo');
define('WALLET_HEADER_KEY_CONTENT_TYPE'         , 'Content-Type');

class PaytmLib
{
    function __construct(){
        $this->CI = & get_instance();
    }
    
    private function getHmacHeaderMap($httpMethod,$contentType, $content,$dateStr,$uri,$queryString, $nonce, $userKey,$macKey,$hmacAlgo) {
    
        $hmacHeaders = Array();
        $hmac        = null;
        $toSignBuff  = "";
    
        $toSignBuff= $toSignBuff.strtoupper($httpMethod)."\n";
        $toSignBuff= $toSignBuff.$contentType."\n";
    
        $contentMD5= base64_encode(MD5($content,true));
    
        if (isset($contentMD5)){
            $toSignBuff= $toSignBuff.$contentMD5."\n";
        }
        $toSignBuff.=$dateStr."\n";
        $toSignBuff.=$uri."\n";
        if(is_null($queryString))
        {
            $toSignBuff.='null'."\n";
        }else{
            $toSignBuff.=$queryString."\n";
        }
    
        $toSignBuff.=$nonce."\n";
        _p($toSignBuff);
        $hmac=	$this->calculateHMAC($macKey, $toSignBuff, $hmacAlgo);
    
        $hmacHeaders = Array(
            WALLET_HEADER_KEY_CONTENT_MD5 => $contentMD5,
            WALLET_HEADER_KEY_CONTENT_HMAC => $hmac,
            WALLET_HEADER_KEY_CONTENT_HMAC_ALGO => 'HmacSHA256',
            WALLET_HEADER_KEY_CONTENT_USER_KEY => $userKey,
            WALLET_HEADER_KEY_NONCE => $nonce,
            WALLET_HEADER_KEY_UNIX_DATE => $dateStr,
            WALLET_HEADER_KEY_CONTENT_TYPE => $contentType,
            WALLET_HEADER_KEY_CONTENT_LENGTH => strlen($content).""
        );
        return $hmacHeaders;
    }
    
    
    private function calculateHMAC($macKey,$macData,$macAlgo){
        $encodedMessage = null;
    
        if($macAlgo == null){
            $macAlgo = HMAC_SHA256_ALGORITHM;
        }
    
        $encodedMessage = hash_hmac ($macAlgo ,  $macData ,  $macKey);
        return $encodedMessage;
    }
    
    /**
    * Purpose : Method to prepare Paytm request data from given params
    * Params  :	1. $requestType - STRING/BOOL - if you want to use this api for verification purpose then pass VERIFY value into this, otherwise pass null 
    *           2. $merchantOrderId - STRING - Merchant txn reference number 
    *           3. $payeeEmailId - STRING - email id of payee
    *           4. $payeePhoneNumber - STRING - mobile number of the payee
    *           5. $appliedToNewUsers - If it is "Y" then wallet have to hold money for x amount of days if user not exist on our system. After that the amount is refunded to merchant sub wallet. If it is "N" then if user is not exist at wallet end we directly refund this amount to merchant sub wallet
    *           6. $amount - STRING - Amount to be transffered
    * Author  : Romil
    */
    private function _prepareRequest($requestType = "VERIFY", $merchantOrderId, $payeeEmailId, $payeePhoneNumber, $appliedToNewUsers = 'N'){
        $requestData = json_encode(array(
                                        "request" => array(
                                            "requestType"       => $requestType,
                                            "merchantGuid"      => PAYTM_MERCHANT_GUID,
                                            "merchantOrderId"   => $merchantOrderId,
                                            "salesWalletName"   => null,
                                            "salesWalletGuid"   => PAYTM_SALES_WALLET_GUID,
                                            "payeeEmailId"      => $payeeEmailId,
                                            "payeePhoneNumber"  => $payeePhoneNumber,
                                            "payeeSsoId"        => "",
                                            "appliedToNewUsers" => $appliedToNewUsers,
                                            "amount"            => PAYTM_BONUS_AMOUNT,
                                            "currencyCode"      => "INR"
                                        ),
                                        "metadata"      => "Testing Data",
                                        "ipAddress"     => "127.0.0.1",
                                        "platformName"  => "PayTM",
                                        "operationType" => "SALES_TO_USER_CREDIT")
                                );

        return $requestData;
    }
    
    /**
    * Purpose : Method to prepare Paytm Header Paramters using HMAC UTIL
    * Params  :	1. $requestData - array - paytm request data
    *           2. $uri - STRING - URI of the API
    *           3. $nonce - INT - nonce is unique number value it must be uniqueue for each request.
    *           4. $currentTime - INT - currentTime contain time in nanosec
    * Author  : Romil
    */
    private function _prepareHeaderParameters($requestData, $uri, $nonce, $currentTime){

        $hmacHeaders =$this->getHmacHeaderMap("POST", "application/json",$requestData , $currentTime, $uri,null, $nonce, PAYTM_SHIKSHA_USERKEY, PAYTM_SHIKSHA_MACKEY, HMAC_SHA256_ALGORITHM);
        
        $headerValue = array();
        foreach($hmacHeaders as $key=>$value)
        {
            $headerValue[]=$key.':'.$value;
        }
        
        return $headerValue;
    }
    
    /**
    * Purpose : Method to check whether a Paytm account with given Mobile number/ Phone Number exists or not
    * Params  :	1. $mobileNumber - user's mobile number
    *           2. $emailId - user's email id
    * Author  : Romil
    */
    public function verifyAccount($mobileNumber, $emailId){

        // get the current timestamp in nanoseconds
        $currentTime    = system('date +%s%N');

        // prepare paytm request
        $requestData = $this->_prepareRequest("VERIFY", $currentTime, $emailId, $mobileNumber, 'N');

        // prepare header params from the request
        $headerValue = $this->_prepareHeaderParameters($requestData, "/wallet-web/salesToUserCredit", $currentTime, $currentTime);

        // make request
        $ch = curl_init(PAYTM_DOMAIN_URI.'wallet-web/salesToUserCredit');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $requestData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headerValue);

        $result = curl_exec($ch);
        curl_close($ch);

        // decode the response
        $result = json_decode($result, true);

        if($result['statusCode'] == "SUCCESS")
            return true;
        else
            return false;
        
    }

    /**
    * Purpose : Method to transfer money from merchants paytm wallet to given user's wallet account
    * Params  :	1. $onlineFormId - online form application id
    *           2. $userId - user's email id
    *           3. $couponCode - Coupon code used
    *           4. $couponType - Type of the Coupon Code
    *           5. $mobileNumber - Mobile Number
    *           6. $emailId - Email-id
    * Author  : Romil
    */
    public function transferMoneyToUserWallet($onlineFormId, $userId, $couponCode, $couponType, $mobileNumber, $emailId){

        // get the current timestamp in nanoseconds
        $currentTime    = system('date +%s%N');

        $applied_to_new_users = "Y";

        // prepare paytm request
        $requestData = $this->_prepareRequest(null, $currentTime, $emailId, $mobileNumber, $applied_to_new_users);

        // LOG : make the log of this request
        $rowId = $this->_trackPaytmRequest($onlineFormId, $userId, $couponCode, $couponType, $requestData, $currentTime, $applied_to_new_users, $mobileNumber, $emailId);

        // prepare header params from the request
        $headerValue = $this->_prepareHeaderParameters($requestData, "/wallet-web/salesToUserCredit", $currentTime, $currentTime);

        // make request
        $ch = curl_init(PAYTM_DOMAIN_URI.'wallet-web/salesToUserCredit');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $requestData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headerValue);

        $result = curl_exec($ch);
        curl_close($ch);

        // LOG : update response data
        $this->_trackPaytmResponse($rowId, $result);
        
        $result = json_decode($result, true);
        
        return $result['statusCode'];
    }

    /**
    * Purpose : Method to track Paytm transaction request
    * Params  :	Tracking params
    * Author  : Romil
    */
    private function _trackPaytmRequest($onlineFormId, $userId, $couponCode, $couponType, $requestData, $merchantOrderId, $applied_to_new_users, $mobileNumber, $emailId){

        if(!$this->couponmodel)
            $this->couponmodel  = $this->CI->load->model("common/couponmodel");

        $data                           = array();
        $data['application_id']         = $onlineFormId;
        $data['user_id']                = $userId;
        $data['coupon_code']            = $couponCode;
        $data['coupon_type']            = $couponType;
        $data['merchant_order_id']      = $merchantOrderId;
        $data['applied_to_new_users']   = $applied_to_new_users;
        $data['amount']                 = PAYTM_BONUS_AMOUNT;
        $data['user_mobile']            = $mobileNumber;
        $data['user_email']             = $emailId;

        $rowId = $this->couponmodel->trackPaytmRequest($data);

        return $rowId;
    }
    
    /**
    * Purpose : Method to track Paytm transaction response
    * Params  :	1. $rowId - Unique row id
    *           2. Tracking params
    * Author  : Romil
    */
    private function _trackPaytmResponse($rowId, $responseData){

        if(!$this->couponmodel)
                $this->couponmodel  = $this->CI->load->model("common/couponmodel");

        $responseDataArr                        = json_decode($responseData, true);
        $data                                   = array();
        $data['is_existing_user']               = $responseDataArr['statusCode'] == "STUC_1001" ? "0" : "1";
        $data['rowId']                          = $rowId;
        $data['response_full_text']             = $responseData;
        $data['response_status_code']           = $responseDataArr['statusCode'];
        $data['response_status_message']        = $responseDataArr['statusMessage'];
        $data['wallet_transactionid']           = $responseDataArr['response']['walletSysTransactionId'];

        $this->couponmodel->trackPaytmResponse($data);
    }
}

?>
