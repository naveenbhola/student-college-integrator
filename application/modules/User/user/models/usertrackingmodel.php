<?php

class UserTrackingModel extends MY_Model{

	private function initiateModel($mode = 'read', $module = '')
	{
		if($mode == 'read') {
			$this->dbHandle = empty($module) ? $this->getReadHandle() : $this->getReadHandleByModule($module);
		} else {
			$this->dbHandle = empty($module) ? $this->getWriteHandle() : $this->getWriteHandleByModule($module);
		}
	}

	public function saveUserProfileTracking($trackingData = array()){
        if(count($trackingData) > 0){
            
            $this->initiateModel('write');

            $sql = $this->db->insert_string('userActionTracking',$trackingData);
            $query = $this->db->query($sql);
            
            return $this->db->insert_id();
        }

        return 0;
    }
}