<?php

class support_model extends MY_Model
{
    private $dbHandle;
    
    function __construct($dbHandle)
    {
        $this->dbHandle = $dbHandle;
    }
    
    public function getUserDetails($userId)
    {
        $userId = (int) $userId;
        $query = $this->dbHandle->query("SELECT t.userid,t.displayname,t.email,t.mobile,tf.ownershipchallenged,t.usergroup ".
                                        "FROM tuser t,tuserflag tf ".
                                        "WHERE t.userid = tf.userid and t.userid = '".$userId."'");
        return $query->row_array();
    }
    
    public function getUserByEmail($email)
    {
        $query = $this->dbHandle->query("SELECT userid FROM tuser WHERE email = ".$this->dbHandle->escape($email)."");
        $result = $query->row_array();
        if($result['userid']) {
            return $result['userid'];
        }else{
            return 0; 
        }

        // else {
        //     $sql = "SELECT userid FROM tuser WHERE email LIKE '%\_\_".$this->dbHandle->escape_like_str($email)."'";
        //     $query = $this->dbHandle->query($sql,array($email));
        //     $result = $query->row_array();
        //     if($result['userid']) {
        //         return $result['userid'];
        //     }
        //     else {
        //         return 0;
        //     }
        // }
    }
    
    public function blockUser($userId,$loggedInUserId)
    {
        $userId = (int) $userId;
        $query = $this->dbHandle->query("SELECT email FROM tuser WHERE userid = '".$userId."'");
        $result = $query->row_array();
        $email = $result['email'];
        
        $action = '';
        
        $emailBlockedPrefix = 'blocked__'.$userId.'__';
        if(strpos($email,$emailBlockedPrefix) === 0) {
            $email = substr($email,strlen($emailBlockedPrefix));
            $action = 'unblocked';
        }
        else {
            $email = $emailBlockedPrefix.$email;
            $action = 'blocked';
        }
        
        $this->_log(array('loggedInUserId' => $loggedInUserId,'userId' => $userId,'action' => $action));
        
        $this->dbHandle->query("UPDATE tuser SET email = '$email' WHERE userid = '".$userId."'");
        return $action;
    }
    
    public function resolveOwnershipChallenged($userId,$loggedInUserId)
    {
        $userId = (int) $userId;
        $this->_log(array('loggedInUserId' => $loggedInUserId,'userId' => $userId,'action' => 'Resolved Challenged Ownership'));
        
        $this->dbHandle->query("UPDATE tuserflag SET ownershipchallenged = '0' WHERE userid = '".$userId."'");
    }
    
    public function editUser($userId,$displayName,$email,$mobile,$userGroup,$loggedInUserId)
    {
        $userId = (int) $userId;
        
        $logData = array('loggedInUserId' => $loggedInUserId,'userId' => $userId,'action' => 'changeInfo');
        
        $currentUserDetails = $this->getUserDetails($userId);
        $currentName = $currentUserDetails['displayname'];
        $currentEmail = $currentUserDetails['email'];
        $currentMobile = $currentUserDetails['mobile'];
        $currentUserGroup = $currentUserDetails['usergroup'];
        
        $updateFields = array();
        
        if($displayName) {
            $sql = "SELECT userid FROM tuser WHERE displayname = ?";
            $query = $this->dbHandle->query($sql,array($displayName));
            $result = $query->row_array();
            
            if($result['userid'] && $result['userid'] != $userId) {
                return array('error' => 'DISPLAYNAME_EXISTS');
            }
            else {
                $updateFields[] = "displayname = ".$this->dbHandle->escape($displayName)."";
                $logData['old_name'] = $currentName;
                $logData['new_name'] = $displayName;
            }
        }
        
        if($email) {
            $sql = "SELECT userid FROM tuser WHERE email = ?";
            $query = $this->dbHandle->query($sql,array($email));
            $result = $query->row_array();
            
            if($result['userid'] && $result['userid'] != $userId) {
                return array('error' => 'EMAIL_EXISTS');
            }
            else {
                $updateFields[] = "email = ".$this->dbHandle->escape($email)."";
                $logData['old_email'] = $currentEmail;
                $logData['new_email'] = $email;
            }
        }
        
        if($mobile) {
            $updateFields[] = "mobile = ".$this->dbHandle->escape($mobile)."";
            $logData['old_mobile'] = $currentMobile;
            $logData['new_mobile'] = $mobile;
        }
        
        if($userGroup) {
            $updateFields[] = "usergroup = ".$this->dbHandle->escape($userGroup)."";
            $logData['old_usergroup'] = $currentUserGroup;
            $logData['new_usergroup'] = $userGroup;
        }
        
        $sql = "UPDATE tuser SET ".implode(',',$updateFields)." WHERE userid = $userId";
        $this->dbHandle->query($sql);
        
        $this->_log($logData);
        
        return array('success' => 1);
    }
    
    private function _log($data)
    {
        $data['date'] = date('Y-m-d H:i:s');
        $this->dbHandle->insert('support_user_log',$data);
    }
}
