<?php

class PaymentRequest
{
    private $ci;
    private $client;
    
    private $userId = 0;
    private $userStatus;
    private $onlineFormId = 0;
    private $userFormDetails = NULL;
    
    private $error_msg;
    private $error_format;
    
    private $validations = array();
    
    function __construct($userStatus,$onlineFormId=0)
    {
        $this->ci = & get_instance();
		$this->ci->load->library('Online_form_client');
        $this->client = new Online_form_client();
        
        $this->userStatus = $userStatus;
        $this->onlineFormId = $onlineFormId;
    }
    
    function isValid()
    {
        if(!$this->getUserId())
        {
            $this->setErrorMsg('User is not logged in');
            return false;
        }
            
        if(!$this->getOnlineFormId())
        {
            $this->setErrorMsg('Invalid Form');
            return false;
        }
        
        $userFormDetails = $this->getUserFormDetails();
        
        if(!is_array($userFormDetails) || $userFormDetails['status'] != 'completed')
        {
            $this->setErrorMsg('Invalid Form');
            return false;
        }
            
        return true;
    }
    
    function setErrorFormat($error_format)
    {
        $this->error_format = $error_format;
        return $this;
    }
    
    function setErrorMsg($error_msg)
    {
        $this->error_msg = $error_msg;
    }
    
    function getErrorMsg()
    {
        if($this->error_format == 'json')
        {
            return json_error($this->error_msg);
        }
        else
        {
            return $this->error_msg;
        }
    }
    
    function getUserFormDetails()
    {
        if(!$this->userFormDetails)
        {
            $userFormDetailsArray = $this->client->getFormListForUser($this->userId,$this->onlineFormId);
            
            if(is_array($userFormDetailsArray))
            {
                $this->userFormDetails = $userFormDetailsArray[0];
            }
        }
        return $this->userFormDetails;
    }
    
    function getUserId()
    {
        if(!$this->userId)
        {
            if(is_array($this->userStatus) && intval($this->userStatus[0]['userid']))
            {
                $this->userId = intval($this->userStatus[0]['userid']);
            }
        }
        
        return $this->userId;
    }
    
    function getOnlineFormId()
    {
        return $this->onlineFormId;
    }
    
    function getAmount()
    {
        $userFormDetails = $this->getUserFormDetails();
        return $userFormDetails['actualFees'];
    }
    
    function getInstituteId()
    {
        $userFormDetails = $this->getUserFormDetails();
        return $userFormDetails['instituteId'];
    }
}