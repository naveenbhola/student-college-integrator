<?php
class portingLib{
	private $CI;
    
    public function __construct(){
        $this->CI = & get_instance();
        $this->portingmodel = $this->CI->load->model('lms/portingmodel');
    }

    public function getAllLivePortings($portingType = ""){
    	if(empty($portingType)){
    		return array();
    	}
    	$data = array();
    	
    	if($portingType == "examResponse"){
    		$data = $this->_getAllLiveExamPorting();
    	}
    	return $data;
    }

    private function _getAllLiveExamPorting(){
    	$portingIds = array();
    	$lastERAllocationId = $this->portingmodel->getLastProcessedId('EXAM_RESPONSE_PORTING');
    	$maxERAllocationId = $lastERAllocationId;

    	// get porting ids for new exam response
    	//fwrite($fp, "Get Live Exam Porting Start New porting response ==".time()."\n");
    	$result = $this->portingmodel->getPortingIdsForNewExamResponses($lastERAllocationId, $maxERAllocationId);
    	//fwrite($fp, "Get Live Exam Porting Start New porting response end ==".time()."\n");
    	$hasPortings = 0;
    	foreach ($result as $row) {
    		$hasPortings = 1;
    		$maxERAllocationId = $row['id'];
    		$portingIds[$row['portingId']] = 1;
    	}

    	// get new porting ids
    	//fwrite($fp, "Get Live Exam Porting Start backlog porting start ==".time()."\n");
    	$result = $this->portingmodel->getNewPortingIds('examResponse');
    	//fwrite($fp, "Get Live Exam Porting Start backlog porting end ==".time()."\n");
    	foreach ($result as $row) {
    		$hasPortings = 1;
    		$portingIds[$row['id']] = 1;
    	}

    	if($hasPortings == 1){
    		$portingIds = array_keys($portingIds);	
    	}
    	
    	$data = array(
					'portingIds'         => $portingIds,
					'lastERAllocationId' => $lastERAllocationId,
					'maxERAllocationId'  => $maxERAllocationId
    				);
    	return $data;
    }

    public function getPortingDetails($portingIds, $portingType){
    	if(empty($portingType) || !is_array($portingIds) || count($portingIds)<=0){
    		return array();
    	}

    	$response = $this->portingmodel->getAllLiveExamResponsePortings($portingIds);
    	$portingDataSet = array();
    	if(is_array($response) && count($response)>0){
    		$portingIds = array();
    		foreach ($response as $row) {
				$portingIds[] = $row['id'];
				$portingDataSet[$row['id']] = $row;
			}
			unset($response);

			$portingConditions = $this->portingmodel->getConditionsForMultiplePorting($portingIds);
			if(is_array($portingConditions) && count($portingConditions)>0){
				foreach ($portingConditions as $row) {
					if($row['key'] == 'examSubscription'){
						$portingDataSet[$row['porting_master_id']]['portingCriteria']['examSubscription'][] = $row['value'];
					}else if($row['key'] == 'responsetype'){
						$portingDataSet[$row['porting_master_id']]['portingCriteria']['responseTypes'][] = $row['value'];
					}
				}
				unset($portingConditions);
				$mappings = $this->portingmodel->getPortingFieldsMapping($portingIds);
				foreach($mappings as $row) {
					if($row['master_field_id'] >0){
						$portingDataSet[$row['porting_master_id']]['mappings'][$row['field_group']][$row['name']] = $row['client_field_name'];
					}else{
						$portingDataSet[$row['porting_master_id']]['mappings']['other'][$row['client_field_name']] = $row['other_value'];
					}
			    }
			}else{
				return array();
			}
		}
		return $portingDataSet;
    }
}
