<?php
class verificationmodel extends MY_Model {
    private $dbHandle = '';
    private $dbHandleMode = '';
    
    private function initiateModel($mode = "write"){
        if($this->dbHandle && $this->dbHandleMode == 'write')
            return;
        
        $this->dbHandleMode = $mode;
        $this->dbHandle = NULL;
        
        if($mode == 'read') {
            $this->dbHandle = $this->getReadHandle();
        } else {
            $this->dbHandle = $this->getWriteHandle();
        }
    }
    
    public function doesODBExist($email,$mobile) {
        $this->initiateModel('read');
        
        $ODB = false;
        
        $sql = "SELECT id,mobile FROM ODBVerification WHERE email = ?";
        $query = $this->dbHandle->query($sql, array($email));
        
        if($query->num_rows() > 0) {
            $row = $query->row_array();
            if($mobile !== $row['mobile'])
            {   
                $return = $this->updateExistMobileNumber($row['id'],$mobile);
                if($return)
                {
                    $ODB = array();
                    $ODB['id'] = $row['id'];
                    $ODB['mobile'] = $mobile;
                }
            }else{
                    $ODB = array();
                    $ODB['id'] = $row['id'];
                    $ODB['mobile'] = $row['mobile'];
            }
            
        }
        return $ODB;
    }
    
    function updateExistMobileNumber($id,$mobile)
    {
        $sql = "UPDATE ODBVerification SET mobile = ? WHERE id = ?";
        $this->dbHandle->query($sql, array($mobile,$id));
        return $this->dbHandle->affected_rows();
    }
    
    public function saveODB($email, $mobile) {
        $this->initiateModel('write');
        
        $data = array(
                        'email'    =>   $email,
                        'mobile'   =>   $mobile,
                        'status'   =>   'pending'
                    );
        
        $queryCmd = $this->dbHandle->insert_string('ODBVerification', $data);
        $query    = $this->dbHandle->query($queryCmd);
        $return   = $this->dbHandle->insert_id();
        return $return;
    }
        
    public function updatecallresponse($id, $call_id, $status_code) {
        $this->initiateModel('write');
        
        $status_text = array(200=>'Successful request',201=>'Number in NDNC',202=>'Unsuccessful request',203=>'Problem in queuing');
        
        $sql = "UPDATE ODBVerification SET call_id = ?, status_code = ?,status_text = ?, attempts = attempts+1, status = ? ,startTime = now() WHERE id =?";        
        $this->dbHandle->query($sql, array($call_id, $status_code, $status_text[$status_code], 'sent', $id));
    }
    
    public function checkUserResponse($email, $mobile) {
        $this->initiateModel('read');
        
        $sql = "SELECT status, response_code, response_text, response_captured FROM ODBVerification WHERE email = ? AND mobile = ?";
        $query = $this->dbHandle->query($sql, array($email, $mobile));
        $row = array();
        if($query->num_rows() > 0) {
            $row = $query->row_array();
        } 
        return $row;
    }
    
    public function updateODBResponse ($params) {
        $this->initiateModel('write');

        $response_text = array(
                               '0xxx'=>'Dropped',
                               '0x0' =>'No answer from user',
                               '0x16'=>'No answer from user',
                               '0x19'=>'No answer from user',
                               '0x01'=>'Invalid Number',
                               '0x22'=>'Invalid Number',
                               '0x28'=>'Invalid Number',
                               '0x17'=>'User busy',
                               '0x21'=>'User busy',
                               '0x41'=>'User busy',
                               '0x47'=>'User busy',
                               '0x20'=>'Switch Off',
                               '0x44'=>'Switch Off',
                               '0x58'=>'Switch Off',
                               '0x88'=>'Switch Off',
                               '0x10'=>'Call Successful',
                               '0x57'=>'Temporary Out of Service',
                               '0x63'=>'Out of Network',
                               '0x91'=>'Out of Network',
                               '0x34'=>'Network Congestion',
                               '0x42'=>'Network Congestion'
                               );
        
        $response_text = ($response_text[$params['response_code']] !='') ? $response_text[$params['response_code']] : 'Network Busy';
        
        $checkResponseTime = $this->getDataByCallId($params['call_id']);
        
        $currentTime = strtotime(date('Y-m-d H:i:s'));
        
        $createdTime = (strtotime($checkResponseTime) + 90);
        
        if($params['response_code'] == '0x10' && $params['response_captured'] == '1' && ($currentTime <= $createdTime)) {
            
            $queryCmd = "update ODBVerification set `status`=?, response_code = ?, response_text = ?, response_captured = ?, endTime = now() where call_id = ?";
            $this->dbHandle->query($queryCmd, array('registrationVerified', $params['response_code'], $response_text, $params['response_captured'], $params['call_id']));
        } else {
            $queryCmd = "update ODBVerification set status= ?, response_code = ?, response_text = ?, response_captured = ?, endTime = now() where call_id = ?";
            $this->dbHandle->query($queryCmd, array('callVerified', $params['response_code'], $response_text, $params['response_captured'], $params['call_id']));            
        }
    }
    
    function getAttempByUserEmail($email)
    {
        $this->initiateModel('read');
        
        $sql = "SELECT attempts FROM ODBVerification WHERE email = ?";
        $query = $this->dbHandle->query($sql, array($email));
        $row = array();
        if($query->num_rows() > 0) {
            $row = $query->row_array();
            $attemp = $row['attempts'];
        } 
        return $attemp;
    }
    
    function getDataByCallId($call_id)
    {
        $this->initiateModel('read');
        
        $sql = "SELECT startTime FROM ODBVerification WHERE call_id = ?";
        $query = $this->dbHandle->query($sql, array($call_id));
        $row = array();
        if($query->num_rows() > 0) {
            $row = $query->row_array();
            $ctime = $row['startTime'];
        } 
        return $ctime;
    }
    
}
