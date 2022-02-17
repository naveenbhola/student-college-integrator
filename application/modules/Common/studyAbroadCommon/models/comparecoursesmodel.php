<?php

class comparecoursesmodel extends MY_Model{
    private $dbHandle = '';
    private $dbHandleMode = '';
    
    
    public function __construct() {
        parent::__construct('Listing');
    }
    
    // function to be called for getting dbHandle with read/write mode
    private function initiateModel($mode='read'){
        if($this->dbHandle && $this->dbHandleMode == 'write'){
            return ;
        }
        $this->dbHandleMode = $mode;
        if($mode == 'write'){
            $this->dbHandle = $this->getWriteHandle();
        }elseif ($mode == 'read') {
            $this->dbHandle = $this->getReadHandle();
        }
    }

	public function getUserComparedCourses($userId){
		$this->initiateModel('read');
		$this->dbHandle->select('courseId');
		$this->dbHandle->from('abroadUserComparedCourses');
		$this->dbHandle->where('status','live');
		$this->dbHandle->where('userId',$userId);
		return array_map(function($ele){return $ele['courseId'];},$this->dbHandle->get()->result_array());
	}
	
	public function trackCompareAdd($courseId,$userId,$source,$sessionId){
		$this->initiateModel('write');
		$dataArray = array(
			'courseId'	=> $courseId,
			'userId' 	=> $userId,
			'trackingId'=> $source,
			'sessionId' => $sessionId,
			'addedOn' 	=> date('Y-m-d H:i:s'),
			'removedOn' => '',
			'status' 	=> 'live'
		);
		$this->dbHandle->insert('abroadUserComparedCourses',$dataArray);
	}
	
	public function trackCompareRemove($courseId,$userId,$source,$sessionId){
		if(!is_array($courseId)){
			$courseId = array($courseId);
		}
		$this->initiateModel('write');
		$dataArray = array(
			'removedOn' => date('Y-m-d H:i:s'),
			'status'	=> 'deleted'
		);
		if($userId == -1){
			$this->dbHandle->where('sessionId',$sessionId);
		}
		$this->dbHandle->where('userId',$userId);
		$this->dbHandle->where_in('courseId',$courseId);
		$this->dbHandle->where('status','live');
		$this->dbHandle->update('abroadUserComparedCourses',$dataArray);
		
	}

	public function putAbroadComparedCoursesFromCookieToDB($sessionId,$userId,$courseIds){
		$this->initiateModel('write');
		//First invalidate any previous selections
		$dataArray = array('removedOn'=>date('Y-m-d H:i:s'),'status'=>'history');
		$this->dbHandle->where('userId',$userId);
		$this->dbHandle->where('status','live');
		$this->dbHandle->update('abroadUserComparedCourses',$dataArray);
		//Next, invalidate with userId the current session courses. No status check here because it is migration.
		$dataArray = array('userId'=>$userId);
		$this->dbHandle->where('sessionId',$sessionId);
		$this->dbHandle->where('userId',-1);
		$this->dbHandle->update('abroadUserComparedCourses',$dataArray);
		//Now, if affected rows is >0, then we've updated previous ones and no insertion is needed. Else, we need to insert.
		if($this->dbHandle->affected_rows()>0){
			return true;
		}
		foreach($courseIds as $courseId){
			$this->trackCompareAdd($courseId,$userId,0,$sessionId);
		}
	}
	
	public function markDeletedComparedCourses($missingCourses){
		$this->initiateModel('write');
		$dataArray = array("removedOn"=>date("Y-m-d H:i:s"),'status'=>'deleted');
		$this->dbHandle->where_in('courseId',$missingCourses);
		$this->dbHandle->where('status','live');
		$this->dbHandle->update('abroadUserComparedCourses',$dataArray);
		return true;
	}
	
	public function clearCompareSelection($courseIds,$userId,$sessionId){
		$this->initiateModel('write');
		if($userId == -1){
			$this->dbHandle->where("sessionId",$sessionId);
		}
		$this->dbHandle->where("userId",$userId);
		$this->dbHandle->where_in("courseId",$courseIds);
		$this->dbHandle->where("status","live");
		$this->dbHandle->update("abroadUserComparedCourses",array('status'=>'deleted','removedOn'=>date('Y-m-d H:i:s')));
	}

	public function trackCompareButtonClick($insertionData){
		$this->initiateModel('write');
		$this->dbHandle->insert_batch('userFinallyComparedCourses',$insertionData);
		return true;
	}

	public function migrateCompareButtonClickTracking($userId,$sessionId){
		$this->initiateModel('write');
		$this->dbHandle->where("userId","-1");
		$this->dbHandle->where("visitorSessionId",$sessionId);
		$this->dbHandle->update("userFinallyComparedCourses",array("userId"=>$userId));
	}
}
