<?php
/**
 * Response Class.
 * Class for managing the response values of APIs
 * @date    2015-07-14
 * @author  Romil Goel
 * @todo    none
*/

class Response {

	/**
     * HTTP response code constant
     */
	const 	OK             		 = 200,
			CREATED              = 201,
			NOCONTENT            = 204,
			MOVEDPERMANENTLY     = 301,
			FOUND                = 302,
			SEEOTHER             = 303,
			NOTMODIFIED          = 304,
			TEMPORARYREDIRECT    = 307,
			BADREQUEST           = 400,
			UNAUTHORIZED         = 401,
			FORBIDDEN            = 403,
			NOTFOUND             = 404,
			METHODNOTALLOWED     = 405,
			NOTACCEPTABLE        = 406,
			GONE                 = 410,
			LENGTHREQUIRED       = 411,
			PRECONDITIONFAILED   = 412,
			UNSUPPORTEDMEDIATYPE = 415,
			INTERNALSERVERERROR  = 500;

	/**
	* HTTP response code of the request to be sent with the header
	* @var integer
	*/
    private $code = Response::OK;

    /**
     * Response body to be sent
     * @var array
     */
    private $body;

    /**
    * Status code of the request to be sent with the header
    * @var integer
    */
    private $statusCode = STATUS_CODE_SUCCESS;

    /**
    * Response message of the request to be sent with the header
    * @var string
    */
    private $responseMsg = "Success";

    /**
    * AuthChecksum containing user details in encrypted form
    * @var string
    */
    private $authChecksum = null;

    /**
    * to set field specific errors
    * @var array
    */
    private $error = null;

    /**
     * List of additional headers to be sent along with the response
     * @var array : Key will be the header name and the value will be the header value
     */
    private $headers = array();

    /**
    * notification count to be shown in the APP
    * @var integer
    */
    private $notificationCount = 0;

    /**
     * constructor
     */
    public function __construct(){

        if($_SERVER['HTTP_AUTHCHECKSUM'])
            $this->authChecksum = $_SERVER['HTTP_AUTHCHECKSUM'];

        $this->appVersion = $_SERVER["HTTP_APPVERSIONCODE"];
    }

    /**
     * Method to covert the given dataset to JSON format
     * @param  [list]     $dataSet 
     * @return [JSON list]     JSON format of $dataSet
     */
    public function __toJSON($dataSet) {
        return json_encode($dataSet);
    }

    /**
     * Setter method for additional header values
     * @param  [string]     $header [header name]
     * @param  [string]     $value  [header value]
     */
    function addHeader($header, $value) {
        $this->headers[$header] = $value;
    }

    /**
     * Method to add a value to the body of the response
     * @param  [type]     $paramName [parameter name/key name]
     * @param  [type]     $value     [Value of the response corresponding to $paramName]
     */
    public function addBodyParam($paramName, $value){

    	// if paramName is empty then do not set any value in the body
    	if(empty($paramName))
    		return;

    	$this->body[$paramName] = $value;
    }

    /**
     * Method to set all the common parameters to be sent along with the response
     * @author Romil Goel <romil.goel@shiksha.com>
     * @date   2015-07-14
     */
    function _setCommonParams(){

    	// determine force upgrade flag
        $ci                 = &get_instance();
        $forceUpgradeMapping = $ci->config->item("forceUpgradeMapping");

        unset($ci);
        if(array_key_exists($this->appVersion, $forceUpgradeMapping)){
            $this->addBodyParam("forceUpgrade"  , $forceUpgradeMapping[$this->appVersion]);
        }
        else
            $this->addBodyParam("forceUpgrade"  , null);

        $this->addBodyParam("responseCode"      , $this->statusCode);
        $this->addBodyParam("responseMessage"   , $this->responseMsg);
        $this->addBodyParam("authChecksum"      , $this->authChecksum);
        $this->addBodyParam("error"             , $this->error);
        $this->addBodyParam("notificationCount" , $this->notificationCount);
    }

    /**************** SETTER :START ***************
    */
    function setHttpCode($inputHTTPCode){
        $this->code = $inputHTTPCode;
    }

    function setBody($inputBody){
        $this->body = $inputBody;
    }

    function setStatusCode($inputStatusCode){
        $this->statusCode = $inputStatusCode;
    }

    function setResponseMsg($inputMsg){
        $this->responseMsg = $inputMsg;
    }

    function setAuthChecksum($userid, $email, $firstname, $lastname, $displayName){

        $ci             = &get_instance();
        $apiSecurityLib = $ci->load->library("common_api/APISecurityLib");
        $visitorId      = getVisitorId();
        
        $authchecksumArr    = array( "userId"      => $userid,
                                     "email"       => $email,
                                     "firstName"   => $firstname,
                                     "lastName"    => $lastname,
                                     "displayName" => $displayName,
                                     "visitorId"   => $visitorId);
        if(MOBILE_APP_NEW){
            $this->authChecksum = $apiSecurityLib->encrypt(json_encode($authchecksumArr));
        }else{
            $this->authChecksum = $apiSecurityLib->encrypt(serialize($authchecksumArr));
        }
    }

    function setFieldError($inputFieldInputArr){
        $this->error = $inputFieldInputArr;
    }

    function setNotificationCount($count){
        $this->notificationCount = $count;
    }
    /**************** SETTER :END ***************
    */
   
   /**************** GETTER :START ***************
    */
    function getHttpCode(){
        return $this->code;
    }

    function getBody(){
        return $this->body;
    }

    function getStatusCode(){
        return $this->statusCode;
    }

    function getResponseMsg(){
        return $this->responseMsg;
    }

    function getAuthChecksum(){
        return $this->authChecksum;
    }

    function getFieldError(){
        return $this->error;
    }

    function getNotificationCount(){
        return $this->notificationCount;
    }
    /**************** GETTER :END ***************
    */

    /**
     * Method to print the response
     * @author Romil Goel <romil.goel@shiksha.com>
     * @date   2015-07-14
     * @return response
     */
    public function output(){

    	// set common parameters needed to be sent with response
    	$this->_setCommonParams();

    	// send headers
    	header('HTTP/1.1 '.$this->code);
    	header('Content-Type: application/json');
        header('Country-Code: '.$_SERVER['GEOIP_COUNTRY_CODE']);

    	foreach ($this->headers as $header => $value) {
            header($header.': '.$value);
        }

        // track api
        // error_log("\n".date("d-m-y h:i:s")." OUT :: API : ".$_SERVER['SCRIPT_URL']." :DEVICE: ".$_SERVER['HTTP_AUTHKEY']." :AUTHCHECK: ".$_SERVER['HTTP_AUTHCHECKSUM'], 3, '/tmp/apitrack.log');

        // send body
    	echo $this->__toJSON($this->body);
    }

}
