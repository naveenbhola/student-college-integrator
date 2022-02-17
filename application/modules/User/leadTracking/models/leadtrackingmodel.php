<?php

class LeadTrackingModel extends MY_Model {
	function __construct(){
		parent::__construct('LDB');
	}

	function getDbHandle($operation='read') {
		if($operation=='read'){
			return $this->getReadHandle();
		}
		else{
        	return $this->getWriteHandle();
		}
	}

	public function fetchLeadTrackingData(){
		$this->dbHandle = $this->getDbHandle();

        $delayedTime = date('Y-m-d H:i:s', strtotime('-15 min'));

		$sql = "Select id, userTrackingData from LeadDeliveryTracking where indexingStatus='queued' and   submitTime<? limit 1000";

		$result = $this->dbHandle->query($sql,array($delayedTime))->result_array();

		return $result;
	}

	public function logLeadTrackingData($user_id, $lead_racking_data){
    	$this->dbHandle = $this->getDbHandle('write');

    	$sql = "insert into LeadDeliveryTracking (userId, userTrackingData) VALUES (?, ?)";
    	$query = $this->dbHandle->query($sql, array($user_id, $lead_racking_data));
    }

    public function checkIsProcessedFlag($user_id){
		$this->dbHandle = $this->getDbHandle();

		$sql = "select is_processed from tUserPref  where userId=?";
		$query_result = $this->dbHandle->query($sql, array($user_id))->result_array();

		return $query_result[0]['is_processed'];
    }

    public function getUserFlag($user_id){
    	$this->dbHandle = $this->getDbHandle();

		$sql = "select exl.id as exclusion_id, is_processed, mobileverified, hardbounce, ownershipchallenged, isTestUser, TimeOfStart, abused, softbounce, extraflag, isLDBUser  from tUserPref pref join tuserflag flag on pref.userId = flag.userId  LEFT JOIN  LDBExclusionList exl on flag.userId =  exl.userId where flag.userId=?";

		$query_result = $this->dbHandle->query($sql, array($user_id))->result_array();

		return $query_result[0];
    }

    public function storeDeliveryMonitoringData($monitoring_data){
    	$this->dbHandle = $this->getDbHandle('write');

    	$sql = "INSERT INTO LeadDataSentTracking(slaId,mailQueueId, smsQueueId) 
				VALUES";
		
		foreach ($monitoring_data as $sla_id => $data) {
			 $sql .= "('".$sla_id."','".$data['email']."','".$data['sms']."'),";		
		}
		
		$sql = substr($sql, 0,-1);
		$this->dbHandle->query($sql);
    }

    public function getAllStreamsWithIds(){
    	$this->dbHandle = $this->getDbHandle();
    	$sql = "select stream_id, name from streams where status='live'";
    	$result = $this->dbHandle->query($sql)->result_array();

    	return $result;

    }

    public function getAllSubStreamsWithIds(){
    	$this->dbHandle = $this->getDbHandle();
    	$sql = "select substream_id, name from substreams where status='live'";
    	$result = $this->dbHandle->query($sql)->result_array();

    	return $result;

    }

    public function getAllSpecializationsWithIds(){
    	$this->dbHandle = $this->getDbHandle();
    	$sql = "select specialization_id, name from specializations where status='live'";
    	$result = $this->dbHandle->query($sql)->result_array();

    	return $result;

    }

    public function getAllBaseCoursesWithIds(){
    	$this->dbHandle = $this->getDbHandle();
    	$sql = "select base_course_id, name from base_courses where status='live'";
    	$result = $this->dbHandle->query($sql)->result_array();

    	return $result;

    }

    public function getAllModesWithIds(){
    	$this->dbHandle = $this->getDbHandle();
    	$sql = "select value_id, value_name from base_attribute_list where status='live' and value_id in (20,21,33,34,35,36,37,39)";
    	$result = $this->dbHandle->query($sql)->result_array();

    	return $result;

    }

    public function getAllCityWithIds(){
    	$this->dbHandle = $this->getDbHandle();
    	$sql = "select city_id,city_name,  state_id, tier from countryCityTable";
    	$result = $this->dbHandle->query($sql)->result_array();

    	return $result;

    }

    public function getAllStateWithIds(){
        $this->dbHandle = $this->getDbHandle();
        $sql = "select state_id, state_name from  stateTable";
        $result = $this->dbHandle->query($sql)->result_array();

        return $result;

    }

    public function getAllCountryWithIds(){
        $this->dbHandle = $this->getDbHandle();
        $sql = "select countryId as country_id, name as country_name from  countryTable";
        $result = $this->dbHandle->query($sql)->result_array();

        return $result;

    }

    public function getAllLocalityWithIds(){
    	$this->dbHandle = $this->getDbHandle();
    	$sql = "select localityId, localityName from localityCityMapping where status='live'";
    	$result = $this->dbHandle->query($sql)->result_array();

    	return $result;

    }

    public function getActualLeadDeliveryData($sla_ids){
    	$this->dbHandle = $this->getDbHandle();
    	$sql = 'select slaId, mQ.createdTime, mQ.sendTime as mailTime, sQ.createdDate, sQ.processTime as smsTime, sla.sms_sent, sla.email_sent, sla.auto_download from LeadDataSentTracking lds join tMailQueue mQ on lds.mailQueueId=mQ.id join smsQueue sQ on lds.smsQueueId=sQ.id join SALeadAllocation sla on sla.id=lds.slaId where slaId in (?) order by slaId';
    	$result = $this->dbHandle->query($sql,array($sla_ids))->result_array();

    	return $result;
    }

    public function getAllDesiredCourseWithIds(){
        $this->dbHandle = $this->getDbHandle();
        $sql = "select SpecializationId as desired_id,CourseName as desired_name from tCourseSpecializationMapping where SpecializationName='All'";
        $result = $this->dbHandle->query($sql)->result_array();

        return $result;
    }

    public function markDataIndexed($row_id){
        $this->dbHandle = $this->getDbHandle('write');
        $sql = "update LeadDeliveryTracking set indexingStatus='indexed' where id in (?)";
        $result = $this->dbHandle->query($sql,array($row_id));

    }

     public function getFinalAllocatedGenies($sla_ids){
        $this->dbHandle = $this->getDbHandle();
        $sql = 'select agentid from  SALeadAllocation where id in (?)';
        $result = $this->dbHandle->query($sql,array($sla_ids))->result_array();

        return $result;
    }

}


?>

