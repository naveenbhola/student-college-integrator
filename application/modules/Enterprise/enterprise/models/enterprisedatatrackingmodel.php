<?php

class enterprisedatatrackingmodel extends MY_Model {

    private $dbHandle;
  
    private function initiateModel($operation = 'read'){
		if($operation=='read'){
		    $this->dbHandle = $this->getReadHandle();
		}else{
		    $this->dbHandle = $this->getWriteHandle();
		}
    }

    public function saveEnterpriseTrackingData($data){
    	if(!is_array($data) || !isset($data['product'])){
    		return false;
    	}
    	$this->initiateModel('write');
    	$this->dbHandle->insert('enterprise_data_tracking',$data);
    }
}