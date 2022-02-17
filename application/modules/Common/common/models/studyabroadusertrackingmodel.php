<?php

class studyAbroadUserTrackingModel extends MY_Model
{
    private function initiateModel($mode = "write", $module = ''){
        if($mode == 'read') {
            $this->dbHandle = empty($module) ? $this->getReadHandle() : $this->getReadHandleByModule($module);
        } else {
            $this->dbHandle = empty($module) ? $this->getWriteHandle() : $this->getWriteHandleByModule($module);
        }
    }
    
    public function trackUser($data){
        $this->initiateModel("write");
        $this->dbHandle->insert("studyAbroadUserMovementTracking",$data);
    }
    /*
     * function to get user/session wise movement data for a certain type of page
     */
    public function getUserMovementDataByPageForDuration($params)
    {
		$this->initiateModel("read");
        $this->dbHandle->select('sessionId,userId,url',FALSE);
        $this->dbHandle->from('studyAbroadUserMovementTracking');
        $this->dbHandle->where('timeStamp >=' , $params['dateCheck']);
		foreach($params['urlPattern'] as $key=>$urlPattern){
			if($key === 0){
				$this->dbHandle->where("(url like '%".$urlPattern."%' ".(array_key_exists($key+1,$params['urlPattern'])!== TRUE?")":""));
			}
			else{
				$this->dbHandle->or_where("url like '%".$urlPattern."%' ".(array_key_exists($key+1,$params['urlPattern'])!== TRUE?")":""));
			}
		}
		$this->dbHandle->limit($params['length'],$params['limitOffset']);
        $result = $this->dbHandle->get()->result_array();
        echo "QRY:<br>".$this->dbHandle->last_query();
        return $result;
    }
}

?>