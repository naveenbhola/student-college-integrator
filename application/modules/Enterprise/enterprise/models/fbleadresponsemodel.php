<?php

class fbleadresponsemodel extends MY_Model {

    /**
    * @var Object DB Handle
    */
    private $dbHandle;
    
	/**
	* Initiate the model
	*
	* @param string $operation
	*/
    private function initiateModel($operation = 'read'){
		if($operation=='read'){
		    $this->dbHandle = $this->getReadHandle();
		}else{
		    $this->dbHandle = $this->getWriteHandle();
		}
    }


    public function insert_fb_response_mapping($data){

    	$this->initiateModel('write');
        $this->dbHandle->insert('fb_lead_campaign_mapping',$data);
        $insertId = $this->dbHandle->insert_id();        
        return true;

    }

    public function getFbLeadMapping(){
    	$this->initiateModel('read');

        //$sql = "select id,institute_id,fb_form_id,fb_form_type from fb_lead_campaign_mapping order by case when fb_form_id = NULL THEN 1 ELSE 2 END, fb_form_id";
    	$sql = "select id,institute_id,fb_form_id,fb_form_type,description,created_on from fb_lead_campaign_mapping order by created_on desc";

    	$query = $this->dbHandle->query($sql);
		return $query->result_array();    	
    }

    public function updateFbFormId($fbFormId,$tableId,$description){
    	$this->initiateModel('write');
    	$this->dbHandle->trans_start();
    	$data = array('fb_form_id'=>$fbFormId,'description' => $description);
		$this->dbHandle->where('id',$tableId);
		$this->dbHandle->update('fb_lead_campaign_mapping',$data);
 		$this->dbHandle->trans_complete();
        if($this->dbHandle->trans_status() === false){
            return false;
        }else{
            return true;
        }
    }

    public function isFormIdExist($formId,$tableId){
    	$this->initiateModel('read');
		$this->dbHandle->select('id');
		$this->dbHandle->from('fb_lead_campaign_mapping');
        $this->dbHandle->where('id !=',$tableId);
		$this->dbHandle->where('fb_form_id',$formId);
		$result = $this->dbHandle->get()->result_array();

		if(count($result) > 0){
			return true;
		}else{
			return false;
		}
    }

    public function getFBMappingById($id){
    	$this->initiateModel('read');
		$this->dbHandle->select('*');
		$this->dbHandle->from('fb_lead_campaign_mapping');
		$this->dbHandle->where('id',$id);
		$result = $this->dbHandle->get()->row_array();

		return $result;
    }

    
    public function getFBLeadsCount(){
        $this->initiateModel('read');
        $this->dbHandle->select('count(*) as count, status');
        $this->dbHandle->from('fb_lead_data');
        $this->dbHandle->group_by('status');
        
        $result = $this->dbHandle->get()->result_array();
        return $result;
    } 

    public function getFBExceptionCityCount($type){
        $this->initiateModel('read');
        $this->dbHandle->select('count(*) as count');
        $this->dbHandle->from('fb_lead_exception');
        $this->dbHandle->where('exception_type',$type);
        $this->dbHandle->where('is_corrected','no');
        
        
        $result = $this->dbHandle->get()->result_array();
        return $result;
    }

    public function getExceptionsByType($type){
        $this->initiateModel('read');
        $this->dbHandle->select('id, old_value');
        $this->dbHandle->from('fb_field_mapping');
        $this->dbHandle->where('type',$type);
        $this->dbHandle->where('corrected_value','');
        
        $result = $this->dbHandle->get()->result_array();
        return $result;
    }

    public function updateCityExceptionMapping($data){
        $this->initiateModel('write');
        $result = $this->dbHandle->update_batch('fb_field_mapping', $data, 'id'); 

        return true;
    }
}

?>