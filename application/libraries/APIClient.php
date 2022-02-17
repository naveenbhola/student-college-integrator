<?php
/**
 * APIClient Class.
 * Class for calling APIs to fetch data, process data, insert/update/delete data.
 * @date    2016-02-17
 * @author  Romil Goel
 * @todo    none
*/
class APIClient
{

    /**
     * Class data members
     */
    private $requestData;
    private $headers = array();
    private $requestType;
    private $errorFlag = 0;
    private $errorMsg = "";
    private $userId = 0;
    private $visitorId = "";
    private $cookie = "";

    /**
     * Constructor
     */
    function __construct() {

        global $isGoodBot;
        $this->headers[] = "SOURCE: AndroidShiksha";
        $this->headers[] = "SITEIDENTIFIER: WebAPICall";
        $this->headers[] = "ISGOODBOT: ".$isGoodBot;
        $this->CI        = &get_instance();

        // set visitor id cookie
        $this->setCookie("visitorId", getVisitorId());
    }

    /**
     * Method to set User-id used for setting authchecksum
     * @param  [int]     $userId 
     * @return none
     */
    function setUserId($userId){
        $this->userId = $userId;
    }
    
    /**
     * Method to set Visitor-id used for setting authchecksum
     * @param  [String]     $visitorId
     * @return none
     */
    function setVisitorId($visitorId){
    	$this->visitorId = $visitorId;
    }

    /**
     * Method to set request data of API to be called
     * @param  [array]     $requestData 
     * @return none
     */
    function setRequestData($requestData){
        $this->requestData = $requestData;
    }

    /**
     * Method to set type of request(POST/GET)
     * @param  [string]     $requestType 
     * @return none
     */
    function setRequestType($requestType){
        $this->requestType = $requestType;
    }

    function setCookie($name, $value){
        $this->cookie[] = $name."=".$value;
    }
    /**
     * Method to compute and set authchecksum header
     * @param  none
     * @return none
     */
    private function _setAuthCheckSumHeader(){

        $apiSecurityLib     = $this->CI->load->library("common_api/APISecurityLib");
        
        $authchecksumArr    = array( "userId"      => $this->userId,
                                     "email"       => "",
                                     "firstName"   => "",
                                     "lastName"    => "",
                                     "displayName" => "",
        							 "visitorId"   => $this->visitorId);

        if(MOBILE_APP_NEW){
            $this->authChecksum = $apiSecurityLib->encrypt(json_encode($authchecksumArr));
        }else{
            $this->authChecksum = $apiSecurityLib->encrypt(serialize($authchecksumArr));
        }
        $this->addHeader("authChecksum", $this->authChecksum);
    }

    /**
     * Method to add a custom header
     * @param  [string]     $headerName 
     * @param  [string]     $headerValue 
     * @return none
     */
    private function addHeader($headerName, $headerValue){
        $this->headers[] = $headerName.": ".$headerValue;
    }

    /**
     * Method to call API
     * @param  [string]     $apiPath (eg. AnA/getHomepageData) 
     * @return none
     */
    function getAPIData($apiPath){

        // start curl session
        $ch = curl_init();

        // compute authchecksum header value
        $this->_setAuthCheckSumHeader();

        // set common header value
        $this->_setCommonHeaders();

        // set Request Params in case of POST
        if(strtolower($this->requestType) == 'post' && !empty($this->requestData)){
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->requestData));
        }
        // if GET
        elseif(strtolower($this->requestType) == 'get' && !empty($this->requestData)){
            $apiPath = $apiPath."?".http_build_query($this->requestData);
        }

        curl_setopt($ch, CURLOPT_URL, SHIKSHA_HOME."/api/v/".$apiPath);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');

        if(ENVIRONMENT == 'production'){
            curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        }else{
            curl_setopt($ch, CURLOPT_TIMEOUT, 200);
        }

        // execute the curl request        
        $result = array();
        $result = curl_exec($ch);
        $culrRetcode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        
        // check if any error occured
        if($culrRetcode >= 400){
            $this->errorFlag = 1;
            $this->errorMsg = $result;
        }
        else{
            $result = json_decode($result, true);
        }
        curl_close($ch);

        return $result;
    }

    /**
     * Method to set common headers
     * @author Romil Goel <romil.goel@shiksha.com>
     * @date   2016-04-27
     */
    private function _setCommonHeaders(){

        // add cookies to header
        $this->addHeader('Cookie',implode($this->cookie, ";"));

        // set identifier for mobile site api call
        if($_COOKIE['ci_mobile_js_support'] == 'yes' || $GLOBALS['flag_mobile_js_support_user_agent'] == 'yes'){
            $this->addHeader('WEBAPISOURCE',"mobile");
        }
        else{
            $this->addHeader('WEBAPISOURCE',"desktop");
        }
    }
}
