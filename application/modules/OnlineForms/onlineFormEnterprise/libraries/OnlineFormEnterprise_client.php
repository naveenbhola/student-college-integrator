<?php
class OnlineFormEnterprise_client{
	var $CI = '';

	function init()
	{ 
		$this->CI_Instance = & get_instance();
		$this->CI_Instance->load->library('xmlrpc');
		$this->CI_Instance->xmlrpc->set_debug(0);
		$this->CI_Instance->xmlrpc->server(OLFENTERPRISE_SERVER_URL, OLFENTERPRISE_SERVER_PORT);
	}

        function commonResponseFunction($request){
            $this->CI_Instance->xmlrpc->request($request);
            if ( ! $this->CI_Instance->xmlrpc->send_request()){ 
                    return $this->CI_Instance->xmlrpc->display_error();
            }else{
                    return $this->CI_Instance->xmlrpc->display_response();
            }
        }
        
        function getBreadCrumbForEnterpriseUser($userId){
            $this->init();
            $this->CI_Instance->xmlrpc->method('getBreadCrumbForEnterpriseUser');
            $request = array($userId);
            return $this->commonResponseFunction($request);
        }
        
        function checkOnlineFormEnterpriseTabStatus($userid, $instituteId){
		$this->init();
		$this->CI_Instance->xmlrpc->method('sCheckOnlineFormEnterpriseTabStatus');
		$request = array(array($userid,'int'), array($instituteId, 'int'));
		return $this->commonResponseFunction($request);
	}

        function sendAlertFromEnterpriseToUser($userAndFormIds=array(),$actionType,$instituteId,$calenderDate='',$typeaction,$instituteSpecId){
                $this->init();
                $this->CI_Instance->xmlrpc->method('sSendAlertFromEnterpriseToUser');
                $request = array(array($userAndFormIds,'string'),array($actionType,'string'),array($instituteId,'int'),array($calenderDate,'string'),array($typeaction,'string'),array($instituteSpecId,'string'));
                return $this->commonResponseFunction($request);
        }

        function getAllAlerts($onlineFormEnterpriseInfo=array(),$userType){
                //echo "<pre>"; print_r($onlineFormEnterpriseInfo); echo "</pre>";
                $this->init(); 
		$this->CI_Instance->xmlrpc->method('sgetAllAlerts');
		$request = array(json_encode($onlineFormEnterpriseInfo),array($userType,'string'));
		$data = $this->commonResponseFunction($request);error_log("getAllAlerts client".print_r($data,true));
                return $data;
        }
        function updateOnlineFormEnterpriseStatus($instituteid,$userId,$formId){
                $this->init();
                $this->CI_Instance->xmlrpc->method('updateOnlineFormEnterpriseStatus');
                $request = array(array($instituteid,'int'),array($userId,'int'),array($formId,'int'));
                $data = $this->commonResponseFunction($request);error_log("getAllAlerts client".print_r($data,true));
                return $data;
        }
	/***************************************************/
	//Code to Download Forms in CSV,XLS,XML Format Start
	/***************************************************/
	 function getOnlineFormLabelsAndValues($courseId,$instituteId){
                $this->init(); 
                $this->CI_Instance->xmlrpc->method('getOnlineFormLabelsAndValues');
                //$request = array(array($userId,'int'),array($formId,'int'));
                $request = array(array($courseId,'int'),array($instituteId,'int'));
                $data = $this->commonResponseFunction($request);
                $response = json_decode(gzuncompress(base64_decode($data)),true);
                return $response;
        }
	/***************************************************/
	//Code to Download Forms in CSV,XLS,XML Format End
	/***************************************************/
}
?>
