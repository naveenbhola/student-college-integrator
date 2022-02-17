<?php

class Support extends MX_Controller
{
    private $authorizedUsers = array(3876449, 8496209, 8495951, 8496041, 11544959);
	private $userGroups = array('user','cms','enterprise','experts','marketingPage','privileged','sums', 'saCMS', 'listingAdmin','saContent','saSales','saShikshaApply','shikshaTracking','saCMSLead','saCustomerDelivery','CRModerator','saAuditor');
    private $model;
    
    function init()
    {
        $this->dbLibObj = DbLibCommon::getInstance('User');
		$dbHandle = $this->_loadDatabaseHandle('write');
		
        $this->load->model('support/support_model');
        $this->model = new support_model($dbHandle);
        
        $this->load->library('support/Support_lib');
        $this->load->helper('support/support');
    }
    
    private function _validateUser()
    {
        $userInfo = $this->checkUserValidation();
        if($userInfo == 'false' || !in_array($userInfo[0]['userid'],$this->authorizedUsers)) {
            $this->load->view('support/login',$data);
            return false;
        }
        
        return $userInfo;
    }
    
    function Login()
    {
        $userId = (int) Modules::run('user/Login/submit');
        if($userId > 0) {
            if(in_array($userId,$this->authorizedUsers)) {
                echo $userId;
            }
            else {
                Modules::run('user/Login/signOut');
                echo '0';
            }
        }    
        else {
            echo '0';
        }
    }

    function user($sUserId)
    {
        if(!$loggedInUserInfo = $this->_validateUser()) {
            return false;
        }
        $this->init();   
        
        $userDetails = $this->support_lib->getUserDetails(intval($sUserId));
        
        $userId = $userDetails['userid'];
        if($userId) {
            $email = $userDetails['email'];
            $emailBlockedPrefix = 'blocked__'.$userId.'__';
            if(strpos($email,$emailBlockedPrefix) === 0) {
                $blocked = true;
            }
            if($userDetails['ownershipchallenged'] == '1') {
                $ownershipChallenged = true;
            }
        }
        
        if($sUserId && !$userId) {
            setSupportMessage('The user id (<span style="color:blue"><i>'.$sUserId.'</i></span>) does not exist. Please enter the correct user id.','error');
        }
        
        $data = array();
        $data['sUserId'] = $sUserId;
        $data['userId'] = $userDetails['userid'];
        $data['displayName'] = $userDetails['displayname'];
        $data['email'] = $userDetails['email'];
        //$data['password'] = $userDetails['textpassword'];
        $data['mobile'] = $userDetails['mobile'];
        $data['blocked'] = $blocked;
        $data['ownershipChallenged'] = $ownershipChallenged;
		$data['selectedUserGroup'] = $userDetails['usergroup'];
        $data['loggedInUserInfo'] = $loggedInUserInfo;
		$data['userGroups'] = $this->userGroups;
        $this->load->view('user',$data);
    }
    
    function findUserByEmail()
    {
        $userId = 0;
        
        $email = trim($this->input->post('semail'));
        if($email) {
            $this->init();   
            $userId = (int) $this->support_lib->getUserByEmail($email);
        }
        
        if($userId) {
            header("Location: /support/Support/user/".$userId);
            exit();
        }
        else {
            setSupportMessage('The email id (<span style="color:blue"><i>'.$email.'</i></span>) does not exist. Please enter the correct email id.','error');
            header("Location: /support/Support/user");
            exit();
        }
    }
    
    function blockUser()
    {
        if(!$loggedInUserInfo = $this->_validateUser()) {
            return false;
        }
        
        $this->init();
        $userId = $this->input->post('userId');
        $result = $this->support_lib->blockUser($userId,$loggedInUserInfo[0]['userid']);
        setSupportMessage('The user has been successfully '.$result[0].'.');
        header('Location: /support/Support/user/'.$userId);
    }
    
    function resolveOwnershipChallenged()
    {
        if(!$loggedInUserInfo = $this->_validateUser()) {
            return false;
        }
        
        $this->init();
        $userId = $this->input->post('userId');
        $this->model->resolveOwnershipChallenged($userId,$loggedInUserInfo[0]['userid']);
        setSupportMessage('The challenged ownership has been successfully resolved.');
        header('Location: /support/Support/user/'.$userId);
    }
    
    function editUser()
    {
        if(!$loggedInUserInfo = $this->_validateUser()) {
            return false;
        }
        
        $this->init();
        $userId = $this->input->post('userId');
        $displayName = trim($this->input->post('displayName'));
        $email = trim($this->input->post('email'));
        $mobile = trim($this->input->post('mobile'));
		$userGroup = trim($this->input->post('usergroup'));
        
        if($displayName || $email || $mobile || $userGroup) {
            $result = $this->support_lib->editUser($userId,$displayName,$email,$mobile,$userGroup,$loggedInUserInfo[0]['userid']);
            if($result['error']) {
                $errorMsg = '';
                switch($result['error']) {
                    case 'DISPLAYNAME_EXISTS':
                        $errorMsg = 'Display name provided by you (<span style="color:blue"><i>'.$displayName.'</i></span>) already exists. Please choose another display name.';
                        break;
                    case 'EMAIL_EXISTS':
                        $errorMsg = 'Email address provided by you (<span style="color:blue"><i>'.$email.'</i></span>) already exists. Please choose another email.';
                        break;
                }
                setSupportMessage($errorMsg,'error');
            }
            else {
                setSupportMessage('User info has been successfully updated');
            }
        }
        else {
            setSupportMessage('Please enter either display name or email or mobile to change info','error');
        }
        
        header('Location: /support/Support/user/'.$userId);
    }
}
