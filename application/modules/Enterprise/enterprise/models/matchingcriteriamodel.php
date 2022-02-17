<?php

class MatchingCriteriaModel extends MY_Model
{
	private $dbHandle = '';
	function getDbHandle($operation = 'read')
	{
		if($operation=='read'){
			return $this->getReadHandle();
		}
		else{
        	return $this->getWriteHandle();
		}
	}
    
    public function getClientAllCriteria($client_id)
    {
        $dbHandle = $this->getDBHandle("read");
		
        $sql =  "SELECT course_id, qualitypercentage from clientCourseMatchedResponseCriteria where client_id =? and status = ? order by updated_on desc";
        $query = $dbHandle->query($sql, array($client_id, 'live'));
        $results = $query->result_array();
		$clientallcriteria = array();
		foreach($results as $result) {
			$clientallcriteria[$result['course_id']]['qualitypercentage'] = $result['qualitypercentage'];
		}
        return $clientallcriteria;
    }
	
    public function saveMatchingCriteria($client_id, $course_id, $qualitypercentage) {
        $dbHandle = $this->getDBHandle('write'); 
        
        $data = array(
                        'client_id'    =>   $client_id,
                        'course_id'   =>   $course_id,
                        'qualitypercentage'   =>   $qualitypercentage,
						'status' => 'live'
                    );
        
        $queryCmd = $dbHandle->insert_string('clientCourseMatchedResponseCriteria', $data);
        $query    = $dbHandle->query($queryCmd);
        $last_id   = $dbHandle->insert_id();
        
        return $last_id;
    }
	
    public function updateMatchingCriteria($client_id, $course_id, $qualitypercentage) {
        $dbHandle = $this->getDBHandle('write');
		$updated_time = date("Y-m-d h:i:s");
        $sql = "UPDATE clientCourseMatchedResponseCriteria SET qualitypercentage = ?, updated_on = ? WHERE client_id = ? and course_id = ?";        
        $dbHandle->query($sql, array($qualitypercentage, $updated_time, $client_id, $course_id));
    }

    public function getCustomMultiplocationDetails(){
        $dbHandle = $this->getDBHandle('read');
        $dbHandle->select('email, courseId, city, isHeadOffice');
        $dbHandle->from("customMultilocationMail");
        $dbHandle->where('status','live');
        $result = $dbHandle->get()->result_array();
        return $result;
    }
    
}
?>