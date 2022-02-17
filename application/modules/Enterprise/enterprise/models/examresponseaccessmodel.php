<?php

class examresponseaccessmodel extends MY_Model {

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

    function checkValidEnterpriseUser($userId){
    	if(!empty($userId) && $userId >0){
    		$this->initiateModel('read');
    		$this->dbHandle->select('userid');
    		$this->dbHandle->from('tuser');
    		$this->dbHandle->where('userid',$userId);
    		$this->dbHandle->where('usergroup','enterprise');
    		$result = $this->dbHandle->get()->result_array();
    		//error_log("Exam Response : valid client check : ".$this->dbHandle->last_query());
    		return $result;
	    }else{
	      return false;
	    }
    }

    function getAllExamDetails(){
    	$this->initiateModel('read');
    	$this->dbHandle->select('distinct(emain.id) id, emain.name name');
    	$this->dbHandle->from('exampage_main emain');
    	$this->dbHandle->join('exampage_master emaster','emaster.exam_id = emain.id','inner');
    	$this->dbHandle->where("emain.status","live");
    	$this->dbHandle->where("emaster.status","live");
    	$result = $this->dbHandle->get()->result_array();
    	//error_log("Exam Response : valid client check : ".$this->dbHandle->last_query());
    	return $result;
    }

    function saveSubscription($subScriptionData, $subScriptionEntitesData){
        if(empty($subScriptionData) || !is_array($subScriptionData) && count($subScriptionData) <=0){
            return false;
        }

        if(empty($subScriptionEntitesData) || !is_array($subScriptionEntitesData) && count($subScriptionEntitesData) <=0){
            return false;
        }
        $this->initiateModel('write');
        $this->dbHandle->trans_start();

        $this->dbHandle->insert('examResponseSubscription',$subScriptionData);
        $insertId = $this->dbHandle->insert_id();
        
        foreach ($subScriptionEntitesData as $key => $subScriptionEntites) {
            $subScriptionEntitesData[$key]['subscriptionId'] = $insertId;
        }

        $this->dbHandle->insert_batch('examResponseSubscriptionEnitity',$subScriptionEntitesData);
        $this->dbHandle->trans_complete();
        if($this->db->trans_status() === false){
            return false;
        }else{
            return true;
        }
    }

    function getSubscriptionData($type = '',$clientId = '', $subscriptionId = ''){
        if(!empty($type) && !in_array($type, array("active","expired","inactive","deleted","all"))){
          return false;
        }
        
        $this->initiateModel("read");
        $this->dbHandle->select('id,clientId, clientName, accountManagerName, examId, groupIds, userLocationIds, startDate, endDate, quantityExpected, quantityDelivered, status, email, mobile');
        $this->dbHandle->from('examResponseSubscription');
        if(!empty($clientId)){
            $this->dbHandle->where('clientId',$clientId);
        }
        if($type != 'all'){
            if($type == "active"){
                $this->dbHandle->where('status','active');
            }else if(in_array($type, array("expired","inactive","deleted"))){
                $this->dbHandle->where_in('status',array('inactive','deleted'));
            }            
        }

        if(!empty($subscriptionId)){
            $this->dbHandle->where('id',$subscriptionId);
        }
        
        $this->dbHandle->order_by('creationDate','desc');

        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result;
    }

    function getExamName($examIds){
        if(empty($examIds) || !is_array($examIds) || count($examIds) <=0){
            return false;
        }

        $this->initiateModel('read');
        $this->dbHandle->select('id, name');
        $this->dbHandle->from('exampage_main');
        $this->dbHandle->where_in('id',$examIds);
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result;
    }

    function getGroupName($groupIds){
        if(empty($groupIds) || !is_array($groupIds) || count($groupIds) <=0){
            return false;
        }

        $this->initiateModel('read');
        $this->dbHandle->select('groupId, groupName');
        $this->dbHandle->from('exampage_groups');
        $this->dbHandle->where_in('groupId',$groupIds);
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();
        return $result;
    }

    function updateSubscriptionStatus($subscriptionId, $status){
        if(empty($subscriptionId) || $subscriptionId <=0){
          return false;
        }

        if(empty($status)){
          return false;
        }

        $this->initiateModel('write');
        $this->dbHandle->trans_start();

        $data = array(
            'status'=>$status,
            'statusUpdatedAt' => date('Y-m-d H:i:s')
        );

        $this->dbHandle->where('id',$subscriptionId);
        $result = $this->dbHandle->update('examResponseSubscription',$data);

        unset($data['statusUpdatedAt']);
        $this->dbHandle->where('subscriptionId',$subscriptionId);
        $result = $this->dbHandle->update('examResponseSubscriptionEnitity',$data);

        $this->dbHandle->trans_complete();
        if($this->dbHandle->trans_status() === false){
            return false;
        }else{
            return true;
        }
    }


    function deleteSubscriptionFromPorting($subscriptionId){
        if(empty($subscriptionId) || $subscriptionId <=0){
          return false;
        }

        $this->initiateModel('write');

        $query = "update porting_conditions set status='deleted' where `key`='examsubscription' and value=?";
        $this->dbHandle->query($query,array($subscriptionId));
    }
}
