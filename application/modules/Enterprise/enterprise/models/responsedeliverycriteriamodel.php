<?php 


class responsedeliverycriteriamodel extends MY_Model {

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

    public function getCourseFromUniversityInstitute($id,$type){
    	if (empty($id) || empty($type) || !is_numeric($id)){
			return 0;
		}	
		$this->initiateModel('read');
		$this->dbHandle->select('course_id');
		$this->dbHandle->from('shiksha_courses');
		$this->dbHandle->where('primary_id',$id);
		$this->dbHandle->where('status','live');
		$this->dbHandle->where('primary_type',$type);
		$result = $this->dbHandle->get()->result_array();	
		return $result;
    }

    public function getPaidCourses($course_id, $handle = 'read'){
    	if (empty($course_id)){
			return ;
		}
		$paid_constants = array(SILVER_LISTINGS_BASE_PRODUCT_ID,GOLD_SL_LISTINGS_BASE_PRODUCT_ID,GOLD_ML_LISTINGS_BASE_PRODUCT_ID);
		$this->initiateModel('read');
		$this->dbHandle->select('listing_title,listing_type_id,username');
		$this->dbHandle->from('listings_main');
		$this->dbHandle->where_in('listing_type_id',$course_id);
		$this->dbHandle->where('status','live');
		$this->dbHandle->where_in('pack_type',$paid_constants);
		$this->dbHandle->where('listing_type','course');
		$result = $this->dbHandle->get()->result_array();
		return $result;
    }

    function getResponseDeliveryCriteria($clientId){
    	if (empty($clientId) || !is_numeric($clientId)){
			return ;
		}

		$this->initiateModel('read');
		$this->dbHandle->select('*');
		$this->dbHandle->from('response_delivery_criteria');
		$this->dbHandle->where_in('client_id',$clientId);
		$this->dbHandle->where('status','live');
		return $this->dbHandle->get()->result_array();
    }

	public function getClientAllInstitutes($clientId){

    	if ($clientId <= 0) {
			return ;
		}

		$this->initiateModel('read');
        $sql = "select lm.listing_type_id, lm.listing_type, lm.listing_title FROM listings_main lm INNER JOIN shiksha_institutes si on lm.listing_type_id = si.listing_id INNER JOIN tuser t ON lm.username = t.userid WHERE lm.status = ? AND si.status = ? AND username = ? AND lm.listing_type IN ('institute','university_national')";

        return $this->dbHandle->query($sql,array('live', 'live', $clientId))->result_array();

    }

    function getInstituteUniversityName($id,$type){
    	if (empty($id) || empty($type)){
			return ;
		}
		$this->initiateModel('read');
		$this->dbHandle->select('listing_title');
		$this->dbHandle->from('listings_main');
		$this->dbHandle->where_in('listing_type_id',$id);
		$this->dbHandle->where('status','live');
		$this->dbHandle->where('listing_type',$type);
		$result = $this->dbHandle->get()->result_array();
		return $result;
    }

    public function saveRdcFormData($data)
    {
    	if(empty($data))
    	{
    		return;
    	}

    	$this->initiateModel('write');
    	$this->dbHandle->insert_batch('response_delivery_criteria',$data);
    }

    public function updateOldFormData($type,$entityIds,$clientId)
    {
    	if(empty($type) || empty($entityIds) || empty($clientId))
    	{
    		return;
    	}

		$this->initiateModel('write');
		$sql = "UPDATE response_delivery_criteria set status='history', update_time=now() where entity_id in (?) and entity_type=? and client_id=? and status='live'";

		$this->dbHandle->query($sql,array($entityIds,$type,$clientId));
    }


    public function updateOldCriteria($entityIds, $type)
    {
    	if(empty($type) || empty($entityIds))
    	{
    		return;
    	}

		$this->initiateModel('write');
		$sql = "UPDATE response_delivery_criteria set status=?, update_time=now() where entity_id in (?) and entity_type=? and status=?";
		$this->dbHandle->query($sql,array('history', $entityIds, $type, 'live'));
    }

}





?>
