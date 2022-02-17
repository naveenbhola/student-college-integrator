<?php
/**
 * User: abhinav
 * Date: 11/3/19
 * Time: 11:49 AM
 */

class APICallerLib {
    private $CI;
    private $config;

    function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->config('config/'.ENVIRONMENT.'/APICallerConfig');
        $this->config = $this->CI->config->item("APICallerConfigData");
    }

    function makeAPICall($serviceName, $endPoint, $method, $queryParams, $bodyData, $headers, $sendCookies=true, $contentTypeJson = true){
        if (empty($serviceName) || empty($endPoint) || empty($method) || !in_array($method, array("GET","POST")) || !key_exists($serviceName, $this->config['services']) || empty($this->config['services'][$serviceName])){
            return;
        }
        $host   = $this->config['services'][$serviceName];
        $url    = $host.$endPoint;
        $defaultHeaders = array("AUTHREQUEST"=>"INFOEDGE_SHIKSHA");
        if($contentTypeJson) {
            $defaultHeaders = array("AUTHREQUEST"=>"INFOEDGE_SHIKSHA","Content-Type" => "application/json");
        }
        $requestHeaders = array();
        if(is_array($headers) && !empty($headers)){
            foreach ($headers as $key => $value) {
                $defaultHeaders[$key] = $value;
            }
        }

        foreach ($defaultHeaders as $key=>$value){
                $requestHeaders[] = $key.": ".$value;
        }
        
        if (function_exists("getallheaders")) {
            $requestHeadersForThisRequest = getallheaders();
            if (key_exists("X-transaction-ID", $requestHeaders)){
                $requestHeaders[] = "x-transaction-ID: ".$requestHeadersForThisRequest['X-transaction-ID'];
            }
        }
        $queryString    = "";
        if (is_array($queryParams) && !empty($queryParams)){
            foreach ($queryParams AS $key=>$value){
                $queryString .= "&".$key."=".$value;
            }
        }
        if (!empty($queryString)){
            $queryString = substr($queryString, 1);
            $url .= "?".$queryString;
        }
        $cookieString = "";
        if ($sendCookies){
            foreach ($_COOKIE as $key=>$value){
                $cookieString .= ";".$key."=".$value;
            }
            if (!empty($cookieString)){
                $cookieString = substr($cookieString, 1);
            }
        }
        $curl = curl_init();
        switch ($method){
            case "GET"  :   curl_setopt($curl, CURLOPT_POST, 0);
                            curl_setopt($curl, CURLOPT_HTTPGET, 1);
                            break;
            case "POST" :   curl_setopt($curl, CURLOPT_POST, 1);
                            break;
            default     :   curl_close();
                            return;
                            break;
        }
        if (!empty($bodyData)){
            curl_setopt($curl, CURLOPT_POSTFIELDS, $bodyData);
        }
        curl_setopt($curl, CURLOPT_URL,$url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $requestHeaders);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        if ($sendCookies && !empty($cookieString)){
            curl_setopt( $curl, CURLOPT_COOKIE, $cookieString);
        }
        $response = array("output" => "", "error_no" => "", "error_message" => "");
        $response['output'] = curl_exec($curl);
        if(curl_errno($curl)){
            $response['error_no']       = curl_errno($curl);
            $response['error_message']  = curl_error($curl);
        }
        curl_close();
        return $response;
    }
}