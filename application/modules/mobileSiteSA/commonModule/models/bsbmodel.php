<?php 
class bsbmodel extends MY_Model{
	private $dbHandle;
	private $dbHandleMode;
	public function __construct(){
		parent::__construct('ShikshaApply');
	}

	private function initiateModel($mode = 'read'){
		if($this->dbHandle && $this->dbHandleMode == 'write'){
			return;
		}

		$this->dbHandleMode = $mode;
		$this->dbHandle = NULL;

		if($mode == 'read'){
			$this->dbHandle = $this->getReadHandle();
		}elseif($mode == 'write'){
			$this->dbHandle = $this->getWriteHandle();
		}
    }

    public function getUserBSBData($visitorId, $BSBActionFlag){
    	$this->initiateModel();
    	$this->dbHandle->select('addedAt, clickedAt, userId');
    	$this->dbHandle->from('saBSBTrackingTable');
    	$this->dbHandle->where('visitorId', $visitorId);
    	if($BSBActionFlag == 'shown'){
    		$this->dbHandle->where('BSBAction', 'shown');
    	}else if($BSBActionFlag == 'clicked'){
    		$this->dbHandle->where('BSBAction != "shown"', NULL, true);
    	}
    	$this->dbHandle->order_by('addedAt', 'desc');
    	$this->dbHandle->limit(1);
    	return $this->dbHandle->get()->result_array();
    }

    public function getUserBSBDataById($userId, $BSBActionFlag){
    	$this->initiateModel();
    	$this->dbHandle->select('addedAt, clickedAt, userId');
    	$this->dbHandle->from('saBSBTrackingTable');
    	$this->dbHandle->where('userId', $userId);
    	if($BSBActionFlag == 'shown'){
    		$this->dbHandle->where('BSBAction', 'shown');
    	}else if($BSBActionFlag == 'clicked'){
    		$this->dbHandle->where('BSBAction != "shown"', NULL, true);
    	}
    	$this->dbHandle->order_by('addedAt', 'desc');
    	$this->dbHandle->limit(1);
    	return $this->dbHandle->get()->result_array();
    }

    public function addBSBTrackingData($insertData){
    	$this->initiateModel('write');
    	$this->dbHandle->insert('saBSBTrackingTable', $insertData);
    	return $this->dbHandle->insert_id();
    }

    public function trackBSBAction($bsbTrackingId, $BSBAction){
    	if(empty($bsbTrackingId)){
    		return false;
    	}
    	$this->initiateModel('write');
    	$updateData = array();
    	$updateData['BSBAction'] = $BSBAction;
    	$updateData['clickedAt'] = date('Y-m-d H:i:s');
    	$this->dbHandle->where('id', $bsbTrackingId);
    	$this->dbHandle->update('saBSBTrackingTable', $updateData);
    	//echo $this->dbHandle->last_query();
    	return true;
    }
}