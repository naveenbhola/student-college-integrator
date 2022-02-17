<?php
class facebook_client{

	var $CI_Instance;
    function init()
    {
        $this->CI_Instance = & get_instance();
        $this->CI_Instance->load->library('xmlrpc');
        $this->CI_Instance->xmlrpc->set_debug(0);
        $this->CI_Instance->xmlrpc->server(FACEBOOK_SERVER_URL, FACEBOOK_SERVER_PORT);
        //$this->CI_Instance->xmlrpc->server('http://facebook/FacebookF/facebook_server', '80');
    }
    
    function getDataForFacebook($appId,$userId,$type,$parentId=0,$mainAnswerId=0,$instituteId =0,$courseId=0){
		$this->init();
		$this->CI_Instance->xmlrpc->method('getDataForFacebook');
		$request = array($appId,$userId,$type,$parentId,$mainAnswerId,$instituteId,$courseId);
		$this->CI_Instance->xmlrpc->request($request);
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
			return $this->CI_Instance->xmlrpc->display_error();
		}else{
			return $this->CI_Instance->xmlrpc->display_response();
		}
    }
        
    function editFbPostSettings($userId, $columnName, $attributeValue){
      $this->init();
      $this->CI_Instance->xmlrpc->method('editFbPostSettings');
        $request = array(array($userId,'int'),array($columnName,'string'),array($attributeValue,'int'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }
    
    function getFbPostSettings($userId, $columnName){
      $this->init();
      $this->CI_Instance->xmlrpc->method('getFbPostSettings');
        $request = array(array($userId,'int'),array($columnName,'string'));
        //print_r($request);
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }
    
    function saveAccessToken($userId,$access_token){
      $this->init();
      $this->CI_Instance->xmlrpc->method('saveAccessToken');
        $request = array(array($userId,'int'),array($access_token,'string'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }
    
    function getAccessToken($userId){
      $this->init();
      $this->CI_Instance->xmlrpc->method('getAccessToken');
        $request = array(array($userId,'int'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }
    
    function saveFacebookFollowInfo($userId,$actionId,$action){
      $this->init();
      error_log(print_r($action,true),3,'/home/aakash/Desktop/error.log');
      $this->CI_Instance->xmlrpc->method('saveFacebookFollowInfo');
        $request = array(array($userId,'int'),array($actionId,'int'),array($action,'string'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }
    
    function deleteAccessToken($userId){
      $this->init();
      $this->CI_Instance->xmlrpc->method('deleteAccessToken');
        $request = array($userId,'int');
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }
    
    function getUserDetailsForEventUpdates($appId){
      $this->init();
      $this->CI_Instance->xmlrpc->method('getUserDetailsForEventUpdates');
        $request = array($appId,'int');
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }
    
    function getUserDetailsForArticlesUpdates($appId){
      $this->init();
      $this->CI_Instance->xmlrpc->method('getUserDetailsForArticlesUpdates');
        $request = array($appId,'int');
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }
    function checkThresholdValue($userId){
      $this->init();
      $this->CI_Instance->xmlrpc->method('checkThresholdValue');
        $request = array($userId,'string');
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }
   
     function updateFacebookFlag($action,$actionId){
        $this->init();
        $this->CI_Instance->xmlrpc->method('updateFacebookFlag');
        $request = array(array($action,'string'),array($actionId,'int'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
	}
	
     function checkLinkArticle($userId,$categoryId){
        $this->init();
        $this->CI_Instance->xmlrpc->method('checkLinkArticle');
        $request = array(array($userId,'string'),array($categoryId,'int'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
      }	
    //pranjul starts
          
        
     function saveAccessToken_AnA($userId,$access_token,$email,$automaticFShare,$cookieAuto){
      $this->init();error_log('i m in saveAcessToken Client today'.print_r($automaticFShare,true));error_log('i m in saveAcessToken Client cookie today'.print_r($cookieAuto,true));
      $this->CI_Instance->xmlrpc->method('saveAccessToken_AnA');
        $request = array(array($userId,'int'),array($access_token,'string'),array($email,'string'),array($automaticFShare,'int'),array($cookieAuto,'string'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }

    function donShowAgain($cookieAuto,$userId){ 
        $this->init();
      $this->CI_Instance->xmlrpc->method('donShowAgain');
        $request = array(array($userId,'int'),array($cookieAuto,'string'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }

    function getAccountSetttingInfo($action,$userId){ error_log("i m inside getAccountSetttingInfo Client today");
      $this->init();
      $this->CI_Instance->xmlrpc->method('getAccountSetttingInfo');
        $request = array(array($action,'string'),array($userId,'int'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }
    
    function getAccessToken_AnA($userId){ error_log("i m inside getAccessToken Client today");
      $this->init();
      $this->CI_Instance->xmlrpc->method('getAccessToken_AnA');
        $request = array(array($userId,'int'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }
    //pranjul ends
    
}
?>
