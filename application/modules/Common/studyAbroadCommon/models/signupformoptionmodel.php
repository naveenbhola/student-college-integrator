<?php
class signupformoptionmodel extends MY_Model{
    /**
	 * constructor method.
	 *
	 * @param array
	 * @return array
	*/
	function __construct()
	{ 
		parent::__construct('CategoryList');
	}
	
	private function initiateModel($operation='read'){
		if($operation=='read'){ 
			$this->dbHandle = $this->getReadHandle();
		}else{
		    $this->dbHandle = $this->getWriteHandle();
		}		
	}

    public function insertSignUpFormABTracking($data){
    	$this->initiateModel('write');
    	$this->dbHandle->insert('signUpFormABTracking',$data);
    }

    public function getTableRowIdToBeUpdated($MISTrackingId, $visitorSessionId, $pageReferer=''){
    	if(empty($MISTrackingId)){
    		$MISTrackingId = 0;
    	}
    	if(!empty($visitorSessionId)){
    		$this->initiateModel('read');
    		$this->dbHandle->select('max(id) as id');
    		$this->dbHandle->from('signUpFormABTracking');
    		$this->dbHandle->where('visitorSessionId', $visitorSessionId);
    		$this->dbHandle->where('MISTrackingId', $MISTrackingId);
            if(!empty($pageReferer)){
                $this->dbHandle->where('pageReferer', str_replace(SHIKSHA_STUDYABROAD_HOME,'',$pageReferer));
            }
    		$result = $this->dbHandle->get()->result_array();
    		//echo $this->dbHandle->last_query();die;
    		return $result;
    	}
    }

    public function updateSignUpFormABtrackingData($data,$tableRowId){
    	$this->initiateModel('write');
    	$this->dbHandle->where('id',$tableRowId);
    	$this->dbHandle->update('signUpFormABTracking',$data);
    }
}