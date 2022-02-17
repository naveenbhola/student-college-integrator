<?php

class FbExceptionModel extends MY_Model
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
    private function initiateModel($operation = 'read'){
		if($operation=='read'){
			$this->dbHandle = $this->getReadHandle();
		}
		else{
        	$this->dbHandle = $this->getWriteHandle();
		}
	}
    

    public function getResolvedException(){
        $update_time = date('Y-m-d H:i:s',strtotime("-1 days"));

        $this->initiateModel('write');
        $this->dbHandle->select('type, old_value, corrected_value');
        $this->dbHandle->from('fb_field_mapping');
        $this->dbHandle->where('update_time >',$update_time);
                
        $result = $this->dbHandle->get()->result_array();

        return $result;
    }

    public function getLeadIdsWithExceptionValue($key, $old_value){

        $this->initiateModel('write');
        $this->dbHandle->select('id, lead_id');
        $this->dbHandle->from('fb_lead_exception');
        $this->dbHandle->where('exception_type',$key);
        $this->dbHandle->where('old_value',$old_value);
        $this->dbHandle->where('is_corrected','no');

        //check for update flag as no
                
        $result = $this->dbHandle->get()->result_array();

        return $result;
    }

    public function updateCorrectLeadData($primary_ids, $data){
    
        $this->initiateModel('write');
        $this->dbHandle->where_in('id',$primary_ids);
        $this->dbHandle->update('fb_lead_exception', $data);
                
    }

    public function getLeadsWithException($lead_ids){
        $this->initiateModel('write');
        $this->dbHandle->select('distinct(lead_id)');
        $this->dbHandle->from('fb_lead_exception');
        $this->dbHandle->where_in('lead_id',$lead_ids);
        $this->dbHandle->where('is_corrected','no');
        $result = $this->dbHandle->get()->result_array();

        return $result;
    }

    public function updateLeadDataStatus($lead_ids, $data){
        $this->initiateModel('write');
        $this->dbHandle->where_in('lead_id',$lead_ids);
        $this->dbHandle->update('fb_lead_data', $data);
    }

    public function getLeadsToRegister(){
        $this->initiateModel('write');

        $this->dbHandle->select('lead_id');
        $this->dbHandle->from('fb_lead_data');
        $this->dbHandle->where('status','lead_data');
        $result = $this->dbHandle->get()->result_array();

        return $result;
    }
}
