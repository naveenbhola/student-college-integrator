<?php

class apicommonmodel extends MY_Model
{
    function __construct()
    {
    	parent::__construct('User');
    }

    function insertDeviceInfo($uid){

        $dbHandle                    = $this->getWriteHandle();
        $dataArr                     = array();
        $dataArr['uid']              = $uid;
        $dataArr['status']           = 0;
        $dataArr['ip_address']       = getenv("HTTP_TRUE_CLIENT_IP")?getenv("HTTP_TRUE_CLIENT_IP"):(getenv("HTTP_X_FORWARDED_FOR")?getenv("HTTP_X_FORWARDED_FOR"):getenv("REMOTE_ADDR"));
        $dataArr['user_agent']       = $_SERVER["HTTP_SOURCE"];
        $dataArr['app_version']      = $_SERVER["HTTP_APPVERSIONCODE"];
        if(empty($dataArr['app_version'])){
            $dataArr['app_version'] = 0;
        }
        $dataArr['platform_version'] = $_SERVER["HTTP_PLATFORMVERSION"];
        $dataArr['platform_name']    = $_SERVER["HTTP_PLATFORMNAME"];

        $status = $dbHandle->insert("devicesAuthKey", $dataArr);

        return $status;
    }

    function enableUID($uid){

        $dbHandle       = $this->getWriteHandle();
        $data['status'] = 1;

        $dbHandle->where('uid',$uid);
        $status = $dbHandle->update('devicesAuthKey',$data);

        return $status;
    }

    function checkIfUIDExists($uid){

        $dbHandle = $this->getReadHandle();
        $sql      = "select count(*) as cnt from devicesAuthKey where uid = ? ";
        $rows     = $dbHandle->query($sql, array($uid))->row_array();

        if($rows['cnt'] == 0){
            return false;
        }
        else{
            return true;
        }  
    }

    function updateDeviceGCMDetails($uid, $userId, $gcmId){

        $dbHandle       = $this->getWriteHandle();
        $data['userId']  = $userId;
        $data['gcmId'] = $gcmId;

        $dbHandle->where('uid',$uid);
        $status = $dbHandle->update('devicesAuthKey',$data);

        return $status;
    }

    function updateDeviceFCMDetails($uid, $userId, $fcmId){

        $dbHandle       = $this->getWriteHandle();
        $data['userId']  = $userId;
        $data['fcmId'] = $fcmId;

        $dbHandle->where('uid',$uid);
        $status = $dbHandle->update('devicesAuthKey',$data);

        return $status;
    }

    function removeSameFCMId($userId, $fcmId){

        $dbHandle       = $this->getWriteHandle();
        $data['userId'] = null;
        $data['fcmId']  = null;

        $dbHandle->where('fcmId',$fcmId);
        $status = $dbHandle->update('devicesAuthKey',$data);

        return $status;
    }

    function removeSameGCMId($userId, $gcmId){

        $dbHandle       = $this->getWriteHandle();
        $data['userId'] = null;
        $data['gcmId']  = null;

        $dbHandle->where('gcmId',$gcmId);
        $status = $dbHandle->update('devicesAuthKey',$data);

        return $status;
    }

    function updateGCMResponseStatus($notificationId , $response, $responseCode, $deliveryStatus = 'sent'){

        $dbHandle       = $this->getWriteHandle();

        $data                  = array();
        $data['response']      = json_encode($response);
        $data['responseCode']  = $responseCode;
        $data['deliverStatus'] = $deliveryStatus;
        $data['sentTime']      = date("Y-m-d H:i:s");

        $dbHandle->where('id',$notificationId);
        $status = $dbHandle->update('notificationsGCMQueue',$data);

        return $status;
    }

    function trackGCMNotification($notificationId, $type){

        $dbHandle       = $this->getWriteHandle();

        $data                  = array();
        if($type == 'delivered'){
            $data['deliverTime']      = date("Y-m-d H:i:s");
            $data['deliverStatus']      = 'delivered';
        }
        else if($type == 'read'){
            $data['readTime']      = date("Y-m-d H:i:s");
            $data['readStatus']      = 'read';
        }
        else{
            return false;
        }

        $dbHandle->where('id',$notificationId);
        $status = $dbHandle->update('notificationsGCMQueue',$data);

        return $status;
    }

    function trackAPI($trackingData){

        if(empty($trackingData))
            return;

        $dbHandle       = $this->getWriteHandle();

        $status = $dbHandle->insert("api_tracking", $trackingData);
    }

    function updateUserLastActivityTime($userId, $lastActivityTime){

        if(empty($userId))
            return;

        if(empty($lastActivityTime))
            $lastActivityTime = date("Y-m-d H:i:s");

        $dbHandle       = $this->getWriteHandle();
        $sql = "INSERT INTO `appUserLastActivity` (userId, lastActivityTime) VALUES (?,?) ON DUPLICATE KEY UPDATE lastActivityTime = ?";

        $rows     = $dbHandle->query($sql, array($userId, $lastActivityTime, $lastActivityTime));
    }

    function getUserLogOutFlagStatus($userId){

        if(empty($userId))
            return false;

        $dbHandle = $this->getReadHandle();
        $sql      = "SELECT id FROM passwordChangedTracking WHERE userId=? AND isLogOutNeeded=1";
        $rows     = $dbHandle->query($sql, array($userId));

        if($rows->num_rows() > 0) {
            return true;
        }

        return false;
    }

    function resetUserLogOutFlag($userId){

        if(empty($userId))
            return false;

        $dbHandle = $this->getWriteHandle();
        
        $data                     = array();
        $data['isLogOutNeeded']   = 0;
        $data['modificationTime'] = date('Y-m-d H:i:s');
        
        $dbHandle->where('userId',$userId);
        $status = $dbHandle->update('passwordChangedTracking',$data);
    }

    /**
    * Function to check whether user is active on APP or NOT
    * @param userId Integer UserId for whom we need to check
    * @return true = if user is active , false = if user is NOT ACTIVE
    * ACTIVE MEANS LAST NO API HIT on last 14 DAYS
    */
    function checkIfUserActive($userId,$maxLastActivedate){
        $dbHandle = $this->getReadHandle();
        if($userId){
            $sql = "SELECT id FROM appUserLastActivity WHERE userId = ? AND lastActivityTime >= ? LIMIT 1";
            $query = $dbHandle->query($sql,array($userId,$maxLastActivedate));
            $row = $query->row_array();
            if(empty($row)){
                return false;
            }
            else {
                return true;
            }
        }else {
            return false;
        }
        
    }
	
}
