<?php
/**
 * API Parent Class.
 * This is the parent class for all the APIs and it needs to be extended by all API controllers
 * @date    2015-07-10
 * @author  Romil Goel
 * @todo    none
*/

class APIParent extends MX_Controller {

	private $userDetails;

	function __construct(){

		// error_log("\n".date("d-m-y h:i:s")." IN  :: API : ".$_SERVER['SCRIPT_URL']." :DEVICE: ".$_SERVER['HTTP_AUTHKEY']." :AUTHCHECK: ".$_SERVER['HTTP_AUTHCHECKSUM'], 3, '/tmp/apitrack.log');

		// load files, initialize variables
		$this->_setDependencies();

		// check if site is under development or not
		$this->_verifyForDowntime();

		// verify if the device is registered or not
		$this->_verifyDevice();

		// parse auth-check sum for user validation
		$this->_parseAuthChecksum();

		// set notification count in response
		$this->_setNotificationCount();

		// common API Validations
		$this->_commonAPIValidations();
	}

	/**
	 * Method to load resources needed
	 * @author Romil Goel <romil.goel@shiksha.com>
	 * @date   2015-07-14
	 */
	private function _setDependencies(){

		$this->load->config("apiConfig");
		$this->load->library("common_api/Response");
		$this->load->library("common_api/ResponseFactory");
		$this->load->library("common_api/APICommonCacheLib");

		$this->response              = new Response();
		$this->apiCommonLib          = $this->load->library("common_api/APICommonLib");
		$this->deviceRegistrationLib = $this->load->library("common_api/DeviceRegistrationLib");
		$this->apiSecurityLib 		 = $this->load->library("common_api/APISecurityLib");
	}

	/**
	 * This will verify the device from which the API request is coming whether that device 
	 * is a known device or not based on the authKey it will be sending.
	 * 
	 * @author Romil Goel <romil.goel@shiksha.com>
	 * @date   2015-07-13
	 * @return [type]     [description]
	 */
	private function _verifyDevice(){

		global $isWebAPICall;
		if($isWebAPICall == 1){
			return false;
		}

		global $requestedAPI;
		
		$skipDeviceVerificationAPIs = $this->config->item("skipDeviceVerificationAPIs");
		$authKey                    = $_SERVER['HTTP_AUTHKEY'];
		$isAuthKeyValid             = false;

		// skip the device verfication checks if SKIP_DEVICE_VERIFICATION flag is ON or some initial APIs are called
		if(SKIP_DEVICE_VERIFICATION || in_array($requestedAPI, $skipDeviceVerificationAPIs) ){
			return true;
		}

		// check for AuthKey validity
		if($authKey)
			$isAuthKeyValid = $this->deviceRegistrationLib->checkAuthKey($authKey);

		// if AuthKey is not found valid, send error response and return back
		if(!$isAuthKeyValid){

			$response = ResponseFactory::createResponse(ResponseFactory::RESPONSE_UNKNOWN_DEVICE);
			$response->output();
			exit();
		}
	}

	/**
	 * Parse/Decrypt authchecksum value present in the header to get logged-in user's basic details
	 * @author Romil Goel <romil.goel@shiksha.com>
	 * @date   2015-07-20
	 */
	private function _parseAuthChecksum(){

		$authchecksum = $this->input->server("HTTP_AUTHCHECKSUM");
		$this->userDetails = $this->apiSecurityLib->decrypt($authchecksum);
		if(MOBILE_APP_NEW){
			$this->userDetails = json_decode($this->userDetails, true);
		}else{
			$this->userDetails = unserialize($this->userDetails);
		}
		
	}

	/**
	 * Getter for logged-in users basic details
	 */
	public function getUserDetails(){
		return $this->userDetails;
	}

	/**
	 * Track all the API calls
	 * @author Romil Goel <romil.goel@shiksha.com>
	 * @date   2015-09-17
	 * @return [type]     [description]
	 */
	public function _trackAPI($response, $trackType = "APITrack"){

		// do not track in case of mobile site
		global $isWebAPICall;
		if($isWebAPICall == 1){
			return false;
		}

		global $rtr_class; // controller
		global $rtr_method; // method
		global $rtr_module; // module

		$skipTrackingAPIList = $this->config->item("skipTrackingAPIList");
		
		// skip specified APIs
		if(in_array($rtr_method, $skipTrackingAPIList) ){
			return true;
		}
		
		// get user-id
		$userId = $this->userDetails ? $this->userDetails['userId'] : null; // userid 
		// get all the parameters passed to the method
		$methodParams = $this->router->uri->segment_array();
		$methodParams = $methodParams ? $methodParams : '';
		$response     = $response ? $response : '';
		
		$url        = $_SERVER['SCRIPT_URL'] ? $_SERVER['SCRIPT_URL'] : null;
		$sessionId  = $_SERVER['HTTP_SESSIONID'] ? $_SERVER['HTTP_SESSIONID'] : null;
		$className  = $rtr_class ? $rtr_class : null;
		$methodName = $rtr_method ? $rtr_method : "";
		$moduleName = $rtr_module ? $rtr_module : null;
		$apiV       = $_SERVER["HTTP_APPVERSIONCODE"] ? $_SERVER["HTTP_APPVERSIONCODE"] : null;
		$authKey    = $_SERVER['HTTP_AUTHKEY'] ? $_SERVER['HTTP_AUTHKEY'] : null;
		$userId     = $userId ? $userId : $userId;
		$requestParams = $_REQUEST;

		// remove tracking of critical data like passwords
		if($rtr_class == 'User' && (in_array($rtr_method, array('login', 'register', 'resetPassword')))){
			unset($requestParams['password']);
		}

		global $serverStartTime;
		$serverProcessingTime = (microtime(true)-$serverStartTime)*1000;

		// tracking list
		$trackingData = array(
							"url"           => $url,
							"sessionId"     => $sessionId,
							"controller"    => $className,
							"method"        => $methodName,
							"module"        => $moduleName,
							"appVersion"    => $apiV,
							"deviceId"      => $authKey,
							"userId"        => $userId,
							"requestParams" => json_encode($requestParams),
							"type"			=> $trackType,
							"response"		=> json_encode($response),
							"methodParams"  => json_encode($methodParams),
							"creationDate"     => date('Y-m-d H:i:s'),
							"serverProcessingTime" => $serverProcessingTime
							);

		

/*		$apiCommonCacheLib = new APICommonCacheLib();
$apiCommonCacheLib->insertAPILogDetails(array(json_encode($trackingData)));
 */
		// $this->apicommonmodel = $this->load->model("common_api/apicommonmodel");
		// $this->apicommonmodel->trackAPI($trackingData);

		// $this->config->load('amqp');
		// $this->load->library("common/jobserver/JobManagerFactory");
		// $jobManager = JobManagerFactory::getClientInstance();
		// $jobManager->addBackgroundJob("LogMobileApp", $trackingData);
	}

	public function getDeviceUID(){
		return $_SERVER['HTTP_AUTHKEY'];
	}

	private function _setNotificationCount(){
		$userDetails = $this->getUserDetails();

		if(!$userDetails || !$userDetails['userId']){
			return;
		}

        $apiCommonCacheLib = new APICommonCacheLib();
        $notificationCount = $apiCommonCacheLib->getUserNotificationCount($userDetails['userId']);
        
        $this->response->setNotificationCount($notificationCount);
	}

	private function _commonAPIValidations(){

		// do not check common API validations in case of mobile website
		global $isWebAPICall;
		if($isWebAPICall == 1){
			return false;
		}

		// check if user needs to be force logged-out or not
		$newSessionFlag = $this->input->server("HTTP_ISNEWSESSION");
		if(isset($newSessionFlag) && $newSessionFlag == 1 && $this->userDetails && $this->userDetails['userId']){
			$logOutFlag = $this->apiCommonLib->getUserLogOutFlagStatus($this->userDetails['userId']);;

			// if user's logout flag is set, then update it to 0 and force log-out the user
			if($logOutFlag){
				$this->apiCommonLib->resetUserLogOutFlag($this->userDetails['userId']);
				$this->response->setStatusCode(STATUS_CODE_FORCE_LOGOUT);
				$this->response->setResponseMsg(FORCE_LOGOUT_MESSAGE);
				$this->response->output();
				exit();
			}
		}

		// check if user is logged-in or not
		// 1. if user is not logged-in, then show the login page(except for those APIs mentioned in "nonLoggedInAPIsList" in apiConfig.php)
		// 2. if user is logged-in, then do nothing and let the flow continue
		$this->_verifyUserLoggedInState();
	}

	/**
	 * Check if user is logged-in or not
	 * 1. if user is not logged-in, then show the login page(except for those APIs mentioned in "nonLoggedInAPIsList" in apiConfig.php)
	 * 2. if user is logged-in, then do nothing and let the flow continue
	 * @author Romil Goel <romil.goel@shiksha.com>
	 * @date   2015-12-17
	 */
	function _verifyUserLoggedInState(){

		global $rtr_method;

		// get list of all those apis that need not to be tested for logged-in state
		$nonLoggedInAPIsList = $this->config->item("nonLoggedInAPIsList");

		// check if API request needs to be validated for logged-in state or not
		if(in_array($rtr_method, $nonLoggedInAPIsList) ){
			return true;
		}

		// check if user is logged-in or not
		if(empty($this->userDetails) || empty($this->userDetails['userId'])){
			$this->response->setStatusCode(STATUS_CODE_FORCE_LOGOUT);
			$this->response->setResponseMsg("Please login to continue");
			$this->response->output();
			exit();
		}

	}

	private function _verifyForDowntime(){

		// check if app is under-maintainance or not
		if(defined("APP_UNDER_MAINTAINANCE") && APP_UNDER_MAINTAINANCE){
			$this->response->setStatusCode(STATUS_CODE_SITE_UNDER_MAINTAINANCE);
			$this->response->setResponseMsg("Site under maintainance");
			$this->response->output();
			exit();
		}
	}

	function __destruct(){
		// log every API request for tracking
		$this->_trackAPI();
	}

}
?>

