<?php

class Ldbleadtrackingmodel extends MY_Model {
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

	function getLeadTrackingLog($leadId){
		if(empty($leadId)){
			return ;
		}

		$this->dbHandle = $this->getDbHandle();

		$sql = "Select trackingLog from LDBLeadTrackingLog where userid =? order by id desc";

		$result = $this->dbHandle->query($sql,array($leadId))->result_array();

		return $result[0]['trackingLog'];
	}

	function getUserDetails($userid){
		if(empty($userid)){
			return;
		}

		$this->dbHandle = $this->getDbHandle();

		$sql = "Select tu.firstname, tu.lastname, tu.email, tu.mobile,tu.usercreationDate, tu.lastModifiedOn, tu.lastlogintime,
					 tu.usergroup, tflag.hardbounce, tflag.mobileverified, tflag.isTestUser, tflag.isNDNC, tflag.isLDBUser, 
					 tpref.desiredcourse, tSpec.Coursename from tuser tu join tuserflag tflag on tu.userid =  tflag.userid 
					join tUserPref tpref on tu.userid = tpref.userid
					join tCourseSpecializationMapping tSpec on tSpec.specializationid = tpref.desiredcourse
					 where tu.userid =?";

		$result = $this->dbHandle->query($sql,array($userid))->result_array();

		return $result;
	}

	function getAllocatedSearchAgentIds($leadId){
		if(empty($leadId)){
			return;
		}

		$this->dbHandle = $this->getDbHandle();

		$sql = "Select agentid,allocationtime from SALeadAllocation where userid =?";

		$result = $this->dbHandle->query($sql,array($leadId))->result_array();
		return $result;

	}

	//not used anywhere till now
	function getDesiredCourseChange($leadId){
		if(empty($leadId)){
			return;
		}

		$this->dbHandle = $this->getDbHandle();

		$sql ='select DesirecCourse, UpdatedTime from LDBLeadViewCount where userid =?';

		$result = $this->dbHandle->query($sql,array($leadId))->result_array();

		return $result;
	}

	function getSearchAgentDetails($searchAgentId){
		if(empty($searchAgentId)){
			return;
		}

		$this->dbHandle = $this->getDbHandle();

		$sql ="select sa.searchAgentName, sa.clientid, sa.deliveryMethod, sa.flag_auto_download, sa.flag_auto_responder_sms,sa.flag_auto_responder_email, sa.created_on, sa.updated_on, sa.type from SASearchAgent sa where sa.is_active='live' and sa.searchAgentId =?";

		$result = $this->dbHandle->query($sql,array($searchAgentId))->result_array();

		return $result;
	}

	function getSearchAgentCriteria($searchAgentId){
		if(empty($searchAgentId)){
			return;
		}

		$this->dbHandle = $this->getDbHandle();

		$sql ="select keyname,value from SAMultiValuedSearchCriteria where searchAlertId = ? and is_active ='live'";

		$result = $this->dbHandle->query($sql,array($searchAgentId))->result_array();

		return $result;
	}

	function getAllocatedLeads($searchAgentId){
		if(empty($searchAgentId)){
			return;
		}

		$this->dbHandle = $this->getDbHandle();

		$sql ="select userid,allocationtime from SALeadAllocation where agentid = ? order by allocationtime desc limit 15";

		$result = $this->dbHandle->query($sql,array($searchAgentId))->result_array();

		return $result;
	}

}


?>

