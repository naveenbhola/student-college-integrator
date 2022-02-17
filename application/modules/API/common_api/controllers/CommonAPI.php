<?php
class CommonAPI extends APIParent {

	function __construct(){
		parent::__construct();
	}
	
	/**
	 * API for device registration and confirmation.
	 * 1. If sharedIdentifier is coming in POST params then a new authkey is generated in disabled state and returned back to the user.
	 * 2. If authKey  is coming in POST params then a it is validated and if found correct it is activated.
	 * @author Romil Goel <romil.goel@shiksha.com>
	 * @date   2015-07-13
	 * @return [type]     [description]
	 */
	function registerDevice(){

    	$this->deviceRegistrationLib =  $this->load->library("common_api/DeviceRegistrationLib");

		$sharedIdentifier = $this->input->post('sharedIdentifier');
		$authKey          = $this->input->post('authKey');

    	// skip the device verification checks if the flag is ON
		if(SKIP_DEVICE_VERIFICATION){
			if($sharedIdentifier)
				$this->response->setBody(array("authKey" => "9b305e33e983d66f616e718ba325b344"));
			else
				$this->response->setBody(array("authKey" => "55b1d534de3b7"));
			$this->response->output();
			return true;
		}

		if($sharedIdentifier){
			$res = $this->deviceRegistrationLib->generateAuthKey($sharedIdentifier);
		}
		else if($authKey){
			$res = $this->deviceRegistrationLib->enableDevice($authKey);
		}
		else{
			$response = ResponseFactory::createResponse(ResponseFactory::RESPONSE_SHARED_IDENTIFIER_NOT_FOUND);
			$response->output();
			exit();
		}

		$this->response->setBody($res);

		$this->response->output();
    }

    function updateGCMId(){

    	//step 1:Fetch the Input from GET/POST
		$Validate = $this->getUserDetails();
		$userId   = isset($Validate['userId'])?$Validate['userId']:'';
		$gcmId    = $this->input->post("gcmId");
		$deviceId = $this->getDeviceUID();
		$gcmId    = $gcmId ? $gcmId : null;

        if(empty($userId) || empty($Validate)){
        	$response = ResponseFactory::createResponse(ResponseFactory::RESPONSE_NOT_FOUND);
            $response->setResponseMsg("User-id Missing");
            $response->output();
            exit(0);
        }
        else if(empty($deviceId)){
        	$response = ResponseFactory::createResponse(ResponseFactory::RESPONSE_NOT_FOUND);
            $response->setResponseMsg("AuthKey Missing");
            $response->output();
            exit(0);	
        }

        $apicommonmodel = $this->load->model("apicommonmodel");

        // update userid and gcmid to NULL if same gcmid exists in the table
        $apicommonmodel->removeSameGCMId($userId, $gcmId);

        // update device's gcm and user-id
        $apicommonmodel->updateDeviceGCMDetails($deviceId, $userId, $gcmId);

        $this->response->output();
    }
    
    function updateFCMId(){

        //step 1:Fetch the Input from GET/POST
        $Validate = $this->getUserDetails();
        $userId   = isset($Validate['userId'])?$Validate['userId']:'';
        $fcmId    = $this->input->post("fcmId");
        $deviceId = $this->getDeviceUID();
        $userId    = $userId ? $userId : null;

        if(empty($deviceId)){
                $response = ResponseFactory::createResponse(ResponseFactory::RESPONSE_NOT_FOUND);
            $response->setResponseMsg("AuthKey Missing");
            $response->output();
            exit(0);
        }
        else if(empty($fcmId)){
                $response = ResponseFactory::createResponse(ResponseFactory::RESPONSE_NOT_FOUND);
            $response->setResponseMsg("FCM-id Missing");
            $response->output();
            exit(0);
        }

        $apicommonmodel = $this->load->model("apicommonmodel");

        // update userid and fcmid to NULL if same fcmid exists in the table
        $apicommonmodel->removeSameFCMId($userId, $fcmId);

        // update device's fcm and user-id
        $apicommonmodel->updateDeviceFCMDetails($deviceId, $userId, $fcmId);

        $this->response->output();
    }
    
    function trackGCMNotification($notificationId, $type){

    	//step 1:Fetch the Input from GET/POST
		$Validate = $this->getUserDetails();
		$userId   = isset($Validate['userId'])?$Validate['userId']:'';
		
        if(empty($userId) || empty($Validate)){
        	$response = ResponseFactory::createResponse(ResponseFactory::RESPONSE_NOT_FOUND);
            $response->setResponseMsg("User-id Missing");
            $response->output();
            exit(0);
        }

        $apicommonmodel = $this->load->model("apicommonmodel");

        // update userid and gcmid to NULL if same gcmid exists in the table
        $apicommonmodel->trackGCMNotification($notificationId, $type);

        $this->response->output();
    }
    
    public function trackThreadView(){
    	$validate = $this->getUserDetails();
    	if(isset($validate['userId']) && $validate['userId'] > 0){
            $threadViewData = $this->input->post('threadViewData');
            $pageSource     = $this->input->post('pageSource');
            $pageType       = $this->input->post('pageType');
            $pageSource     = empty($pageSource) ? 'mobileapp' : $pageSource;
            
            // parse threadViewData from API request
            $threadViewData = explode(",", $threadViewData);
            
    		if(!empty($threadViewData)){
    			$this->load->library('common_api/APICommonLib');
    			$result = $this->apicommonlib->trackThreadView($validate['userId'], '',$threadViewData, $pageType, $pageSource);
    			if($result){
    				$this->response->setStatusCode(STATUS_CODE_SUCCESS);
    			}else{
    				$this->response->setStatusCode(STATUS_CODE_FAILURE);
    				$this->response->setResponseMsg("Something went wrong");
    			}
    		}else{
    			$this->response->setStatusCode(STATUS_CODE_FAILURE);
    			$this->response->setResponseMsg("Thread View Data Submitted is not Valid");
    		}
    	}else{
    		$this->response->setStatusCode(STATUS_CODE_FAILURE);
    		$this->response->setResponseMsg("User is not Valid");
    	}
    	$this->response->output();
    }
}
?>

