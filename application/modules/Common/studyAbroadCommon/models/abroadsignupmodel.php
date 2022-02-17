<?php
class abroadsignupmodel extends MY_Model{
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

    public function getMISTrackingDetails($trackingPageKeyId){
        if(empty($trackingPageKeyId) || $trackingPageKeyId <=0){
            return false;
        }
        $this->initiateModel();
        $this->dbHandle->select('*');
        $this->dbHandle->from('tracking_pagekey');
        $this->dbHandle->where('id',$trackingPageKeyId);
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result;
    }

    public function getCategoryIdForDesireCourse($preferredCourse){
    	if(empty($preferredCourse) || $preferredCourse <=0){
            return false;
        }
        $this->initiateModel();
        $this->dbHandle->select('CategoryId');
        $this->dbHandle->from('tCourseSpecializationMapping');
        $this->dbHandle->where('SpecializationId',$preferredCourse);
        $this->dbHandle->where('status','live');
        $this->dbHandle->where('scope','abroad');
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result[0]['CategoryId'];	

    }
}