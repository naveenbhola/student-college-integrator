<?php

namespace onlineFormEnterprise\libraries;

class OnlineFormSecurity{
	private $user;
	private $dao;
	private $logFile = "/home/vikasa/logs_container/onlineSecurity.log";
	
	function __construct()
    {
        $this->CI = & get_instance();
		$validity =  $this->CI->checkUserValidation();
		if($validity == "false"){
			$this->user = null;
		}else{
			$this->user = $validity[0];
		}
		$this->CI->load->model('onlineFormEnterprise/onlineformsecuritymodel'); 
		$this->dao = new $this->CI->onlineformsecuritymodel();
    }
	
	public function checkCourse($courseId){
		if($this->user && $this->dao->checkCourse($courseId,$this->user['userid'])){
			return true;
		}else{
			error_log("\nCourse Security Breech Course ID: $courseId User IP: ".$this->CI->input->ip_address().
					  " URL: ".base_url().uri_string()
					  ,3,$this->logFile);
			return false;
		}
	}
	
	public function checkForm($formId){
		if($this->user && $this->dao->checkForm($formId,$this->user['userid'])){
			return true;
		}else{
			error_log("\nForm Security Breech Form ID: $formId User IP: ".$this->CI->input->ip_address().
					  " URL: ".base_url().uri_string()
					  ,3,$this->logFile);
			return false;
		}
	}
	
	public function getUser(){
		return $this->user;
	}

	public function checkValidCourse($courseId,$checkForExpire='false',$checkForInternal='false'){
                if($this->dao->checkValidCourse($courseId,$checkForExpire,$checkForInternal)){
                        return true;
                }else{
                        error_log("\nCourse NOT HAVING ONLINE FORM Security Breech Course ID: $courseId User IP: ".$this->CI->input->ip_address().
                                          " URL: ".base_url().uri_string()
                                          ,3,$this->logFile);
                        return false;
                }
	}
	
}
