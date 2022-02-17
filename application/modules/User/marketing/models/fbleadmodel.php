<?php

class fbleadmodel extends MY_Model
{
    /**
     * @var Object DB Handle
     */ 
    private $dbHandle;
    
    /**
     * Constructor
     */ 
    function __construct()
    {
        parent::__construct('User');
    }
    
    /**
     * Initiate the model
     *
     * @param string $operation
     */ 
    private function initiateModel($operation = 'read')
    {
		if($operation=='read'){
			$this->dbHandle = $this->getReadHandle();
		}
		else{
        	$this->dbHandle = $this->getWriteHandle();
		}
	}

    public function checkCityInFBFieldMapping($cityName){
        //_p($cityName);die;
        if(empty($cityName)){
            return false;
        }
        $this->initiateModel('write');
        $this->dbHandle->select('corrected_value');
        $this->dbHandle->from('fb_field_mapping');
        $this->dbHandle->where('type','city');
        $this->dbHandle->where('old_value',$cityName);
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result;
    }

    public function getCampaignDetails($fbFormId){
        if(empty($fbFormId) || $fbFormId <=0){
            return false;
        }

        $this->initiateModel('read');
        $this->dbHandle->select('institute_id, fb_form_type, city_id, course_ids');
        $this->dbHandle->from('fb_lead_campaign_mapping');
        $this->dbHandle->where('fb_form_id',$fbFormId);
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result;
    }

    public function addToFBException($exceptionData, $fieldMappingData){
        //_p($exceptionData);_p($fieldMappingData);die;
        if(!is_array($exceptionData) || count($exceptionData) <=0){
            return false;
        }

        if(!is_array($fieldMappingData) || count($fieldMappingData) <=0){
            return false;
        }

        $this->initiateModel('write');
        $this->dbHandle->trans_start();

        $this->dbHandle->insert_batch('fb_lead_exception', $exceptionData);

        // insert into fb field mapping
        $sql = "INSERT IGNORE INTO fb_field_mapping(type, old_value) values";
        $sqlData = array();
        foreach ($fieldMappingData as $key => $row) {
            $sql.= "(?,?),";
            $sqlData[] = $row['type'];
            $sqlData[] = $row['old_value'];
        }
        $sql = substr($sql, 0,-1);
        $this->dbHandle->query($sql,$sqlData);

        $this->dbHandle->trans_complete();
        if($this->dbHandle->trans_status() === false){
            return false;
        }else{
            return true;
        }
    }

    public function insertFBLead($data){
        if(count($data)<=0){
            return;
        }
        
        $this->initiateModel('write');
        $queryCmd = $this->dbHandle->insert_string('fb_lead_data', $data);
        $queryCmd .= " on duplicate key update status=?";
        $sqlData = array();
        $sqlData[] = $data['status'];
        if($data['user_id'] >0){
            $queryCmd .= " , user_id =?";
            $sqlData[] = $data['user_id'];
        }
        $query    = $this->dbHandle->query($queryCmd, $sqlData);
    }

    public function updateFBLead($data ,$leadId){
        if(count($data)<=0){
            return;
        }

        if($leadId <=0){
            return;
        }

        $this->initiateModel('write');
        $this->dbHandle->where('lead_id',$leadId);
        $this->dbHandle->update('fb_lead_data', $data);
    }

    public function updateOTPVerificationTable($oldUserOTPData, $email){
        if(!is_array($oldUserOTPData) || count($oldUserOTPData) <=0){
            return false;
        }
        if(empty($email)){
            return false;
        }
        $this->initiateModel('write');
        $this->dbHandle->where('email',$email);
        $this->dbHandle->update('OTPVerification',$oldUserOTPData);
    }

	public function saveOTP($data) {
        if(!is_array($data) || count($data) <=0){
            return false;
        }
        $this->initiateModel('write');
        $queryCmd = $this->dbHandle->insert_string('OTPVerification', $data);
        $queryCmd .= " on duplicate key update status= 'verified', isdCode = ?, mobile =?, GUID = ?";
        $query    = $this->dbHandle->query($queryCmd, array($data['isdCode'],$data['mobile'],$data['GUID']));
    }

	public function getOTPDataForOldUser($email){
        if(empty($email)){
            return false;
        }

        $this->initiateModel('read');
        $this->dbHandle->select('*');
        $this->dbHandle->from('OTPVerification');
        $this->dbHandle->where('email',$email);
        $result = $this->dbHandle->get()->result_array();
        return $result;
    }

    public function getActiveFormIds($form_id){
        $this->initiateModel('read');
        $this->dbHandle->distinct();
        $this->dbHandle->select('fb_form_id');
        $this->dbHandle->where('fb_form_id', $form_id); 
        $result = $this->dbHandle->get('fb_lead_campaign_mapping')->result_array();
        // select distinct fb_form_id  from fb_lead_campaign_mapping where !isnull(fb_form_id)
        $data = array();
        foreach ($result as $key => $value) {
            $data[] = $value['fb_form_id'];
        }
        
        return $data;
    }
    
    public function getLeadDataForResolvedLeads($lead_ids){
        $this->initiateModel('read');
        $this->dbHandle->select('lead_data');
        $this->dbHandle->from('fb_lead_data');
        $this->dbHandle->where_in('lead_id',$lead_ids);
        $this->dbHandle->where('status','lead_data');
        $result = $this->dbHandle->get()->result_array();

        return $result;                
    }    

    public function insertFBLeadId($data){
        $data['created_on']  = date("Y-m-d H:i:s");
        $data['status']      = 'lead';
        $this->initiateModel('write');
        $sql = "INSERT IGNORE INTO `fb_lead_data` (`lead_id`, `fb_form_id`, `fb_lead_created_on`, `created_on`, `status`) VALUES (?,?,?,?,?)";
        $this->dbHandle->query($sql,array($data['lead_id'],$data['fb_form_id'],$data['fb_lead_created_on'],$data['created_on'],$data['status']));
        return $this->dbHandle->insert_id();
    }

    public function getPreviousDayActiveFormIds($noOfDays){
        $this->initiateModel('read');

        if($noOfDays == 0){
            //before one hour
            $sqlQuery  = "SELECT DISTINCT fb_form_id FROM  fb_lead_data WHERE date(created_on) = curdate()";
            //and DATE_SUB(NOW(), INTERVAL 1 HOUR) > created_on
            $result = $this->dbHandle->query($sqlQuery)->result_array();            
        }else{
            $sqlQuery  = "SELECT DISTINCT fb_form_id FROM  fb_lead_data WHERE created_on BETWEEN CURDATE() - INTERVAL ? DAY AND CURDATE()";
            $result = $this->dbHandle->query($sqlQuery,array((int)$noOfDays))->result_array();            
        }
        return $result;
    }

    public function getPreviousDayLeadIdsByFormId($formIds,$noOfDays){
        $this->initiateModel('read');
        if($noOfDays == 0){
            //before one hour
            $sqlQuery  = "SELECT lead_id,fb_form_id,lead_data, fb_lead_created_on  FROM  fb_lead_data WHERE fb_form_id in (?)"; 
            //AND date(created_on) = curdate() AND DATE_SUB(NOW(), INTERVAL 1 HOUR) > created_on            
            $result = $this->dbHandle->query($sqlQuery,array($formIds))->result_array();            
        }else{
            $sqlQuery  = "SELECT lead_id,fb_form_id,lead_data, fb_lead_created_on  FROM  fb_lead_data WHERE fb_form_id in (?) AND created_on BETWEEN CURDATE() - INTERVAL ? DAY AND CURDATE()";
            $result = $this->dbHandle->query($sqlQuery,array($formIds,(int)$noOfDays))->result_array();            
        }
        return $result;        
    }
}
