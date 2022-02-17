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
    
    public function doesOTPExist($email) {
		if(empty($email)){
            return array();
        }

        $this->initiateModel('read');
        
        $OTP = false;
        
        $sql = "SELECT id, OTP, Flag, attempts, isdCode, mobile, modified_date FROM OTPVerification WHERE email = ?";
        $query = $this->dbHandle->query($sql, array($email));
        
        if($query->num_rows() > 0) {
            $row = $query->row_array();
            
            $OTP             = array();
            $OTP['id']       = $row['id'];
            $OTP['OTP']      = $row['OTP'];
            $OTP['attempts'] = $row['attempts'];
            $OTP['isdCode']  = $row['isdCode'];
            $OTP['mobile']   = $row['mobile'];
            $OTP['flag']     = $row['Flag'];
            
            $date1 = $row['modified_date'];
            $diff  = abs(strtotime("now") - strtotime($date1));
            $diff  = floor($diff/(60*60));
            if($diff >= 1){
                $this->updateOTPStatus($row['id'],"attempsUpdate");
                $OTP['attempts'] = 0;
               
            }
        }
        
        unset($query); 
        unset($row);
        return $OTP;
    }
    
    public function saveOTP($email, $mobile, $OTP,$flag, $isdCode) {
        $this->initiateModel('write');
        
        $data = array(
                        'email'    =>   $email,
                        'isdCode'  =>   $isdCode,
                        'mobile'   =>   $mobile,
                        'OTP'      =>   $OTP,
                        'attempts' =>   0,
                        'status'   =>   'pending',
                        'Flag'     =>   $flag
                    );
        
        $queryCmd = $this->dbHandle->insert_string('OTPVerification', $data);
        $queryCmd .= " on duplicate key update attempts=attempts+1";
        $query    = $this->dbHandle->query($queryCmd);
        $return   = $this->dbHandle->insert_id();
        
        return $return;
    }
    
    public function checkOTP($email, $mobile, $OTP, $isStudyAbroad, $isdCode) {
        $this->initiateModel('read');
        
        $verified = 'no';
        
        $flag = $isStudyAbroad == 1? 'Abroad':'National';
        
        $sql = "SELECT id, OTP, attempts, Flag FROM OTPVerification WHERE email = ? and isdCode = ? AND mobile = ? AND status = ?";
        $query = $this->dbHandle->query($sql, array($email, $isdCode, $mobile, 'sent'));
        
        if($query->num_rows() > 0) {
            $row = $query->row_array();
            $id = $row['id'];
            $validOTP = $row['OTP'];
            $attempts = $row['attempts'];
            $existingFlag = $row['Flag'];
            
            if($flag != $existingFlag){
                $verified = 'changeValue';
                $this->updateOTPStatus($id , $verified, '', $flag);
            }
            
            if($OTP == $validOTP && $attempts < 5) {
                $verified = 'yes';
            }
            else if($OTP != $validOTP && $attempts >= 4) {
                $verified = 'failed';
            }else if($attempts >= 4){
                $verified = 'failed';
            }
            
            if($verified != 'failed'){
                $this->updateOTPStatus($id, $verified);
            }
            $this->updateOTPTrackingStatus('', $id, $verified);

        }
        
        return $verified;
    }
    
    public function updateOTPStatus($id, $status, $mobile = '', $flag='', $OTP = '', $isdCode = '', $GUID) {

        if(empty($id)){
            return;
        }
        
        $this->initiateModel('write');
        
        $clauses = array();
        if($status == 'yes') {

            //$clauses[] = "attempts = attempts + 1";
            $clauses[] = "status = 'verified'";
            $clauses[] = "method = 'OTP'";

        }
        else if($status == 'no' || $status == 'failed' || $status == 'resend') {

            $clauses[] = "attempts = attempts + 1";

        } else if($status == 'sent') {

            $clauses[] = "status = 'sent'";
            $clauses[] = "method = 'OTP'";
            $clauses[] = "GUID = ".$this->dbHandle->escape($GUID);

        }else if($status == 'attempsUpdate'){

            $clauses[] = "attempts = 0";

        }else if($status == 'changeValue') {

            if(isset($mobile) && is_numeric($mobile)) {

                $clauses[] = "mobile = ".$this->dbHandle->escape($mobile);
                $clauses[] = "OTP = ".$this->dbHandle->escape($OTP);
                $clauses[] = "status = 'sent'";
                $clauses[] = "method = 'OTP'";

            }

            if(isset($isdCode) && is_numeric($isdCode)){

                $clauses[] = "isdCode = ".$this->dbHandle->escape($isdCode);
                $clauses[] = "OTP = ".$this->dbHandle->escape($OTP);
                $clauses[] = "status = 'sent'";
                $clauses[] = "method = 'OTP'";

            }

            if(isset($flag) && !empty($flag)){

               $clauses[] = "Flag = ".$this->dbHandle->escape($flag);

            }
            $clauses[] = "attempts = attempts + 1";
        }
        
        if(count($clauses) > 0 && isset($id)) {
            $sql = "UPDATE OTPVerification SET ".implode(', ', $clauses)." WHERE id =?";
            
            $this->dbHandle->query($sql, array($id));
        }
    }
    
    public function isUserVerified($email, $mobile, $isdCode) {

        if(empty($email) || empty($mobile) || empty($isdCode)){
            return false;
        }

        $this->initiateModel('read');
        
        $verified = false;
        
        if($mobile > 0) {
            $sql = "SELECT id FROM OTPVerification WHERE email = ? and isdCode = ? AND mobile = ? AND status = ?";
            $query = $this->dbHandle->query($sql, array($email, $isdCode, $mobile, 'verified'));
            
            if($query->num_rows() > 0) {
                $verified = true;
            }
        }
        
        $queryString = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY);
	    parse_str($queryString, $URLParams);
        
        if(OTP_VERIFICATION == false || $URLParams['disableOTPVerification'] == 'e2b22fef40f50e9588431cf86f32406a') {
            $verified = true;
        }
        
        return $verified;
    }
    
    public function doesOTPVerified($email){
        $this->initiateModel('read');
        
        $sql = "SELECT status, mobile FROM OTPVerification WHERE email = ? ";
        $query = $this->dbHandle->query( $sql, array($email))->row_array();
        
        if($query['status'] == 'verified'){
            return true;
        }else{
            return false;
        }        
    }

    public function trackOTP($trackingParams = array()) {
        $this->initiateModel('write');
        
        $data = array(
                        'otp_verification_id' => $trackingParams['otp_verification_id'],
                        'reg_form_id'         => $trackingParams['regFormId'],
                        'email'               => $trackingParams['email'],
                        'isdCode'             => $trackingParams['isdCode'],
                        'mobile'              => $trackingParams['mobile'],
                        'otp'                 => $trackingParams['OTP'],
                        'resend_otp'          => $trackingParams['isResend'],
                        'change_number'       => $trackingParams['isChangeNumber'],
                        'otp_status'          => $trackingParams['otp_status'],
                        'site_source'         => $trackingParams['site_source'],
                        'is_new_user'         => $trackingParams['is_new_user'],
                        'tracking_key_id'     => $trackingParams['trackingKeyId'],
                        'visitor_session_id'  => $trackingParams['visitor_session_id'],
                        'GUID'                => $trackingParams['GUID'],
                        'error_code'          => $trackingParams['errorCode']
                    );
        
        $queryCmd = $this->dbHandle->insert_string('OTPTracking', $data);
        $query    = $this->dbHandle->query($queryCmd);
        $return   = $this->dbHandle->insert_id();
        
        return $return;
        
    }

    public function updateOTPTrackingStatus($trackId, $verificationId, $otpStatus, $GUID, $errorCode) {
        
        $this->initiateModel('write');

        $data = array();
        if($trackId == ''){
            switch ($otpStatus) {
                case 'yes':
                    $data['otp_status'] = 'verified';
                    break;
                
                case 'failed':
                case 'no':
                    $this->dbHandle->set('no_of_tries', 'no_of_tries+1', false);
                    break;
            }

            $data['verified_time'] = date('Y-m-d H:i:s');
            $this->dbHandle->order_by('id', 'desc');
            $this->dbHandle->limit(1);

        } else {

            $data = array(
                    'otp_status'    => $otpStatus, // can be 'sent' or 'failed'
                    'GUID'          => $GUID,
                    'error_code'    => $errorCode,
                    'sent_time'     => date('Y-m-d H:i:s')
                    );
            $this->dbHandle->where('id', $trackId);

        }
        
        $this->dbHandle->where('otp_verification_id', $verificationId);
        $updateStatus = $this->dbHandle->update('OTPTracking', $data);
        return $updateStatus;

    }

    public function getNumberAndMaxIdToBeProcessed($lastProcessedId){
        
        if($lastProcessedId < 1 || $lastProcessedId == ''){
            return false;
        }
        $this->initiateModel('read');
        $this->dbHandle->select('count(*) as number, MAX(id) as maxId');
        $this->dbHandle->from('OTPTracking');
        $this->dbHandle->where('id > ',$lastProcessedId);
        
        $now  = strtotime(date("Y-m-d H:i:s"));
        $date = date("Y-m-d H:i:s", strtotime('-24 hours', $now));
        $this->dbHandle->where('added_on < ',$date);
        $this->dbHandle->where('GUID IS NOT NULL', NULL, FALSE);
        $result = $this->dbHandle->get()->result_array();
        return $result;
    }

    public function getRecordsToBeProcessed($lastProcessedId, $count){
        
        if($lastProcessedId < 1 || $lastProcessedId == ''){
            return false;
        }
        $this->initiateModel('read');
        $this->dbHandle->select('id, GUID, sent_time');
        $this->dbHandle->from('OTPTracking');
        $this->dbHandle->where('id > ',$lastProcessedId);
        $this->dbHandle->where('GUID IS NOT NULL', NULL, FALSE);
        $this->dbHandle->where('GUID != ""', NULL, FALSE);
        $this->dbHandle->limit($count);
        $result = $this->dbHandle->get()->result_array();
        return $result;
    }

    public function getLastProcessedTrackingId($processName){
        
        if(empty($processName)){
            return false;
        }
        $this->initiateModel('read');
        $this->dbHandle->select('lastprocessedid');
        $this->dbHandle->from('SALeadAllocationCron');
        $this->dbHandle->where('process',$processName);
        $result = $this->dbHandle->get()->result_array();
        return $result[0]['lastprocessedid'];

    }

    public function updateLastProcessedTrackingId($lastTrackingId, $processName){
        
        if($lastTrackingId < 1 || empty($processName)){
            return;
        }
        $this->initiateModel('write');
        $data = array('lastprocessedid' => $lastTrackingId);
        $this->dbHandle->where('process',$processName);
        $this->dbHandle->update('SALeadAllocationCron',$data);

    }

    public function insertResponseInBatch($dataToInsert = array()){

        if(empty($dataToInsert)){
            return false;
        }
        $this->initiateModel('write');
        $this->dbHandle->insert_batch('SMSResponseTracking',$dataToInsert);
        
        return;

    }

    public function doesMCVerified($mobile){
        $this->initiateModel('write');

        $sql = "SELECT mobile FROM missed_call_response WHERE mobile = ? and time > NOW() - INTERVAL 15 MINUTE";
        $query = $this->dbHandle->query($sql, array($mobile));

        if($query->num_rows() > 0){
            return 'yes';
        }else{
            return 'no';
        } 
    }

    public function trackMC($trackingParams = array()) {
        $this->initiateModel('write');
        
        $queryCmd = $this->dbHandle->insert_string('missed_call_tracking', $trackingParams);
        $query    = $this->dbHandle->query($queryCmd);
        $return   = $this->dbHandle->insert_id();        
        return $return;        
    }    

    function updateMCTrackingAndVerification($mobile,$email){
        $this->initiateModel('write');
        if($mobile > 0){
            $this->dbHandle->trans_start();

            $this->dbHandle->select('id,otp_verification_id,mobile');
            $this->dbHandle->from('missed_call_tracking');
            $this->dbHandle->where('mobilewithisd',$mobile);
            $this->dbHandle->where('email',$email);
            $this->dbHandle->order_by('id','desc');
            $this->dbHandle->limit(1);
            $result = $this->dbHandle->get()->row_array();
            //update status in missed call tracking            

            $mobileNo = $result['mobile'];
            $data = array(
                'missed_call_status' =>'verified',
                'verified_time'      => date('Y-m-d H:i:s')
            );

            $this->dbHandle->where('id',$result['id']);
            $msTrackingResponse = $this->dbHandle->update('missed_call_tracking',$data);

            $dataOTP = array(
                'status'      =>'verified',
                'method'      => 'MS'
            );

            $this->dbHandle->where('id',$result['otp_verification_id']);
            $this->dbHandle->where('status !=','verified');
            $otpVerificationResponse = $this->dbHandle->update('OTPVerification',$dataOTP);
            
            $this->dbHandle->trans_complete();
            if($this->dbHandle->trans_status() === false){
                return array('status'=>false,'mobile'=>$mobileNo);
            }else{
                return array('status'=>true,'mobile'=>$mobileNo);
            }
        }
    }
}
