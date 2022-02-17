<?php 
class LeadAllocationModel extends MY_Model {
	function __construct()
	{
		parent::__construct('SearchAgents');
	}

	function getDbHandle($operation = 'read')
	{
		if($operation=='read'){
			return $this->getReadHandle();
		}
		else{
        	return $this->getWriteHandle();
		}
	}

	function pickUnallocatedInterest(){
		$dbHandle = $this->getDbHandle();

		//put check of submit date 

		//$sql = "select userId,ExtraFlag from tUserPref where is_processed = 'no' and SubmitDate >'2016-08-08 00:00:00' and  SubmitDate <'2016-08-19 00:00:00' order by userid desc limit 5";

		$delayedTime = date('Y-m-d H:i:s', strtotime('-15 min'));
			
		$sql = "SELECT a.userid as userId,c.ExtraFlag, a.usercreationDate, a.lastlogintime, c.submitdate from tuser a LEFT JOIN SALeadAllocation sl ".
			         "ON a.userid = sl.userid LEFT JOIN LDBExclusionList exl on a.userid = exl.userid and exl.status='live', tuserflag b, tUserPref c ".
			         "WHERE a.userid = b.userId AND a.userid = c.userid ".
			         "AND a.usergroup not in ('sums', 'enterprise', 'cms', 'experts', 'lead_operator', 'saAdmin', 'saCMS', 'saContent', 'saSales') ".
			         "AND b.mobileverified='1' AND b.hardbounce!='1' ".
			         "AND b.ownershipchallenged!='1' AND b.isTestUser = 'NO' ".
			         "AND ((c.TimeOfStart <= now() + interval 1 year ".
			         "AND c.TimeOfStart != '0000-00-00 00:00:00') OR (c.TimeOfStart IS NULL)) ".
			         "AND b.abused!='1' AND b.softbounce!='1' AND c.is_processed = 'no' ".
			         "AND b.isLDBUser = 'YES' ".
			         "AND (((c.submitdate > now() - interval 1 day) AND (extraflag ='testprep' OR extraflag ='undecided' OR extraflag IS NULL)) ". 
			         "OR ((c.submitdate > now() - interval 1 day) AND (c.submitdate <?) AND (extraflag ='studyabroad'))) ".
			         "AND sl.userid is null ".
			         "AND exl.id is null group by a.userid order by c.submitdate";
		

		$result = $dbHandle->query($sql,array($delayedTime))->result_array();

		return $result;
	}

	public function pickUnallocatedOldInterest($exclude, $lastCronRunTime){
		$dbHandle = $this->getDbHandle('write');
		
		$delayedTime = date('Y-m-d H:i:s', strtotime('-15 min'));	

		$append_sql = '';
		if(is_array($exclude) && count($exclude)>0){
			$append_sql = "and a.userid not in (?) ";
		}

		$sql = "select a.userid as userId, c.ExtraFlag, a.usercreationDate, a.lastlogintime, c.submitdate
				from tuser a 
				inner join tuserflag b on a.userid = b.userId 
				inner join tUserPref c on a.userid = c.userid 
				LEFT JOIN LDBExclusionList exl on a.userid = exl.userid and exl.status='live'
				where  a.usergroup not in ('sums', 'enterprise', 'cms', 'experts', 'lead_operator', 'saAdmin', 'saCMS', 'saContent', 'saSales')  and b.mobileverified='1' and b.hardbounce!='1'and exl.id is null ".
				"and b.isLDBUser = 'YES' and b.ownershipchallenged!='1' and b.isTestUser = 'NO' ".
				"and ((c.TimeOfStart <= now() + interval 1 year and c.TimeOfStart != '0000-00-00 00:00:00') OR (c.TimeOfStart IS NULL)) ".
				"AND ((extraflag ='undecided' OR extraflag IS NULL) ". 
			    "OR ((c.submitdate < ?) AND (extraflag ='studyabroad'))) ".
				"and b.abused!='1' and b.softbounce!='1' and a.lastlogintime >= ? ".$append_sql;

	   	$query = $dbHandle->query($sql,array($delayedTime, $lastCronRunTime, $exclude));
	   	$results = $query->result_array();

	   	return $results;

	}


	public function insertInMatchingLog($final_data){

		if(!is_array($final_data) || count($final_data)<=0){
			return false;
		}
		
		$dbHandle = $this->getDbHandle('write');

		$dbHandle->insert_batch('SALeadMatchingLog',$final_data);

		$matcing_insert_id = $dbHandle->insert_id();
		return $matcing_insert_id;
	}

	public function getClientWithSuffienctCredits($clientIds,$credit){
		//$sumsDBHandle = $this->_loadDatabaseHandle('read','SUMS');

		$dbHandle = $this->getDbHandle();		//put SUMS db handle

        $sql = "SELECT S.ClientUserId
                FROM SUMS.Subscription_Product_Mapping SPM
                INNER JOIN SUMS.Subscription S ON S.SubscriptionId = SPM.SubscriptionID
                INNER JOIN SUMS.Base_Products B ON SPM.BaseProductId=B.BaseProductId
                WHERE S.ClientUserId IN (?)
                AND S.SubscrStatus='ACTIVE'
                AND SPM.BaseProdRemainingQuantity > ?
                AND DATE(SPM.SubscriptionEndDate) >= curdate()
                AND DATE(SPM.SubscriptionStartDate) <= curdate()
                AND SPM.Status='ACTIVE'
                AND B.BaseProdCategory = 'Lead-Search'";

        $query = $dbHandle->query($sql,array($clientIds,$credit));

        $results = $query->result_array();

        return  $results;
	}

	public function getAlloctedClients($clientIds,$userId,$streamId, $subStreamId){
		if(count($clientIds) <=0 || !isset($userId)){
			return false;
		}

		if(!isset($streamId) && !isset($subStreamId)){
			return false;
		}


		$dbHandle = $this->getDbHandle('write');

		$sql="select clientId from UserProfileMappingToClient where clientId IN (?) and streamId = ? and userId =?";

		if(isset($subStreamId) && $subStreamId!=''){
			$sql.= " and subStreamId =".$subStreamId;
		}

		$query = $dbHandle->query($sql,array($clientIds, $streamId,$userId));
        $results = $query->result_array();

        return  $results;
	}

	public function markUserProcessed($userId){
		if(!isset($userId) || $userId == '' || empty($userId)){
			return false;
		}

		$dbHandle = $this->getDbHandle('write');

		$sql="update `tUserPref` set is_processed = 'yes' where userid = ?";

		$dbHandle->query($sql,array($userId));
	}
	
	public function getClientsAllocatedToUser($userId){
		if(!isset($userId)){
			return false;
		}

		$dbHandle = $this->getDbHandle('write');

		$sql = "select clientId from LDBLeadContactedTracking where userId =?";
	
		$query = $dbHandle->query($sql,array($userId));
        $results = $query->result_array();

        return  $results;
	}

	public function getUserViewCount($userId){
		if(!isset($userId)){
			return false;
		}

		$dbHandle = $this->getDbHandle();

		$sql = "select viewCount,StreamId,substreamId from LDBLeadViewCount where userId =?";
	
		$query = $dbHandle->query($sql,array($userId));
        $results = $query->result_array();

        return  $results;
	}

	public function getAbroadUserViewLimit($desiredCourse){
		if(!isset($desiredCourse)){
			return false;
		}

		$dbHandle = $this->getDbHandle();

		$sql = "SELECT deductcredit as viewLimit
					 FROM  `tCourseGrouping` a, tGroupCreditDeductionPolicy b
					 WHERE a.groupid = b.groupid
					 AND a.status =  'live'
					 AND b.actionType =  'view_limit'
					 AND b.status =  'live' and courseId =?";
	
		$query = $dbHandle->query($sql,array($desiredCourse));
        $results = $query->result_array();

        return  $results[0]['viewLimit'];
	}

	public function getAbroadUserViewCount($userId,$desiredCourse){
		if(!isset($userId) || !isset($desiredCourse)){
			return false;
		}

		$dbHandle = $this->getDbHandle();

		$sql = "select viewCount from LDBLeadViewCount where userId =? and desiredCourse=?";
	
		$query = $dbHandle->query($sql,array($userId,$desiredCourse));
        $results = $query->result_array();

        return  $results[0]['viewCount'];
	}

	public function getOldLeadCronRunTime(){
		$dbHandle = $this->getDbHandle('write');

		$sql = "select modifiedtime from SALeadAllocationCron where process = 'OLD_LEAD_MATCHING' ";
	
		$query = $dbHandle->query($sql);
		
        $results = $query->result_array();

        return  $results[0]['modifiedtime'];
	}

	public function updateCronLastRunTime($cronStartTime){
		$dbHandle = $this->getDbHandle('write');

		$sql="update `SALeadAllocationCron` set modifiedtime = ? where process = 'OLD_LEAD_MATCHING'";

		$dbHandle->query($sql,array($cronStartTime));
	}
}
?>
