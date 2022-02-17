<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class abroadresponsegenerationmodel extends MY_Model{
    private $dbHandle;
    
    private function _initiateModel($mode = "write"){
        if($mode == 'read') {
            $this->dbHandle = $this->getReadHandle();
        } else {
            $this->dbHandle = $this->getWriteHandle();
        }
    }
    public function checkIfResponseExistLastDay($data){
        if(empty($data)){
            return "error";
        }
        $this->_initiateModel('write');
        $this->dbHandle->select('id,action,submit_date');
        $this->dbHandle->from('tempLMSTable');
        $this->dbHandle->where('listing_subscription_type',$data['listing_subscription_type']);
        $this->dbHandle->where('userId',$data['userId']);
        $this->dbHandle->where('listing_type_id',$data['listing_type_id']);
        $this->dbHandle->where('listing_type',$data['listing_type']);
        $this->dbHandle->where('submit_date >',$data['prevDayDate']);
        $row = $this->dbHandle->get()->result_array();
        return reset($row);
    }
    public function increaseUserListingPerDayResponseCount($data){
        if(empty($data)){
            return "error";
        }
        $this->_initiateModel('write');
        $this->dbHandle->set('count','count+1', false);
        $this->dbHandle->set('submit_date', $data['submit_date']);
        $this->dbHandle->where('listing_subscription_type',$data['listing_subscription_type']);
        $this->dbHandle->where('userId',$data['userId']);
        $this->dbHandle->where('listing_type_id',$data['listing_type_id']);
        $this->dbHandle->where('listing_type',$data['listing_type']);
        $this->dbHandle->where('submit_date >',$data['prevDayDate']);
        $this->dbHandle->update('tempLmsRequest');
    }
    public function createResponse($data){
        if(empty($data)){
            return "error";
        }
        $this->_initiateModel('write');
        $tempLMSRequestInsertId = $this->_insertIntoTempLMSRequest($data);
        $tempLMSTableId         = $this->_insertIntoTempLMSTable($data);
        return array('tempLMSTableId'=>$tempLMSTableId,'tempLMSRequestInsertId'=>$tempLMSRequestInsertId);
    }
    
    private function _insertIntoTempLMSRequest($data){
        $temp_lms_request_data = array(
            'listing_type_id'   =>  $data['listing_type_id'],
            'listing_type'      =>  $data['listing_type'],
            'userId'            =>  $data['userId'],
            'displayName'       =>  $data['displayName'],
            'email'             =>  $data['contact_email'],
            'contact_cell'      =>  $data['contact_cell'],
            'CounsellorId'      =>  isset($data['CounsellorId']) ? $data['CounsellorId'] : '',
            'listing_subscription_type'=>$data['listing_subscription_type'],
            'submit_date'       =>  $data['submit_date'] 
        );
        $queryCmd = $this->dbHandle->insert_string('tempLmsRequest',$temp_lms_request_data);      
        $queryCmd .= " on duplicate key update count=count+1, submit_date=?";
        $this->dbHandle->query($queryCmd,array($data['submit_date'] ));
        
        $temp_request_id = $this->dbHandle->insert_id();

        $this->dbHandle->where('queue_id', $data['queue_id']);
        $this->dbHandle->where('response_from', 'abroad');
        $this->dbHandle->update('response_elastic_map', array('temp_lms_request_id' => $temp_request_id));

        return $temp_request_id;

    }
    private function _insertIntoTempLMSTable($data){
        // insert into tempLMSTable 
        $temp_lms_table_data = array(
                'listing_type_id'   =>  $data['listing_type_id'],
                'listing_type'      =>  $data['listing_type'],
                'userId'            =>  $data['userId'],
                'displayName'       =>  $data['displayName'],
                'message'           =>  $data['message'],
                'email'             =>  $data['contact_email'],
                'action'            =>  $data['action'],
                'contact_cell'      =>  $data['contact_cell'],
                'marketingFlagSent' =>  isset($data['marketingFlagSent']) ? $data['marketingFlagSent'] : '',
                'marketingUserKeyId'=>  isset($data['marketingUserKeyId']) ? $data['marketingUserKeyId'] : '',
                'listing_subscription_type'=>$data['listing_subscription_type'],
                'tracking_keyid'    =>$data['tracking_page_key'],
                'visitorsessionid'  =>$data['visitorSessionid'],
                'submit_date'       =>$data['submit_date'] 
        );
        $queryCmd = $this->dbHandle->insert_string('tempLMSTable',$temp_lms_table_data);      
        $this->dbHandle->query($queryCmd);

        $recent_id = $this->dbHandle->insert_id();

        $this->dbHandle->where('queue_id', $data['queue_id']);
        $this->dbHandle->where('response_from', 'abroad');
        $this->dbHandle->update('response_elastic_map', array('temp_lms_id' => $recent_id,'temp_lms_time'=> date("Y-m-d H:i:s"),'use_for_response'=>'y'));

        return $recent_id;
    }
    public function upgradeResponse($data,$responseId){
        $this->_initiateModel('write');
        $this->dbHandle->set('action',$data['action']);
        if(!empty($data['tracking_page_key'])){
            $this->dbHandle->set('tracking_keyid',$data['tracking_page_key']);
            $this->dbHandle->set('visitorsessionid',$data['visitorSessionid']);
        }
        $this->dbHandle->set('submit_date',$data['submit_date']);
        $this->dbHandle->where('id',$responseId);  
        $this->dbHandle->update('tempLMSTable');

        $this->dbHandle->where('queue_id', $data['queue_id']);
        $this->dbHandle->where('response_from', 'abroad');
        $this->dbHandle->update('response_elastic_map', array('temp_lms_id' => $responseId,'temp_lms_time'=> date("Y-m-d H:i:s")));

    }
    public function setIsLdbUser($userId,$isLDBUser){
        $this->_initiateModel('write');
        $this->dbHandle->set('isLDBUser',$isLDBUser);
        $this->dbHandle->where('userId',$userId);
        $this->dbHandle->update('tuserflag');
    }
    public function addResponseLocation($insertData){
        if(!empty($insertData)){
            $this->_initiateModel('write');
            $this->dbHandle->insert($insertData);
        }
    }
    public function getResponseCityId($instituteLocationId){
        if(!empty($instituteLocationId)){
            $this->_initiateModel('write');
            $this->dbHandle->select('city_id');
            $this->dbHandle->from('institute_location_table');
            $this->dbHandle->where('institute_location_id',$instituteLocationId);
            $this->dbHandle->where('status','live');
            $resultArray = $this->dbHandle->get()->result_array();
            return $resultArray[0]['city_id'];
        }
    }
    public function updateResponseLocationAffinity($userId,$responseCityId){
        if(!empty($userId) && !empty($responseCityId)){
            $sql = "INSERT INTO userResponseLocationAffinity (userId, cityId, affinity)
			VALUES (?, ?, 1)
			ON DUPLICATE KEY UPDATE affinity = affinity+1";
            $this->dbHandle->query($sql, array($userId, $responseCityId));
        }
    }
}

