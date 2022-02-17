<?php
class sasalesmodel extends MY_Model
{
	/**
	 * constructor method.
	 *
	 * @param array
	 * @return array
	 */
	function __construct()
	{ 
		parent::__construct('MISTracking');
	}
	
	private function initiateModel($operation='read'){
		if($operation=='read'){ 
			$this->dbHandle = $this->getReadHandle();
		}else{
		    $this->dbHandle = $this->getWriteHandle();
		}		
	}

	public function getUniversityDetails($univId){
		if($univId >0){
			$this->initiateModel('read');
			$this->dbHandle->select('*');
			$this->dbHandle->from('university');
			$this->dbHandle->where('university_id',$univId);
			$this->dbHandle->where('status','live');
			$result = $this->dbHandle->get()->result_array();
		}
        //echo $this->dbHandle->last_query();die;
        return $result;
	}

	public function getUniversitySubsciptionDetails($courseIds,$dateRange){

		if(!is_array($courseIds) || count($courseIds)==0){
			return array();
		}
		$this->initiateModel('read');
		$this->dbHandle->select('max(id) as id, courseId',false);
		$this->dbHandle->from('courseSubscriptionHistoricalDetails');
		$this->dbHandle->where_in('courseId',$courseIds);
		$this->dbHandle->where('source','abroad');
		$this->dbHandle->where('packType',GOLD_SL_LISTINGS_BASE_PRODUCT_ID);

		if($dateRange['start'] !='' && $dateRange['end']!=''){
		$this->dbHandle->where('((addedonDate between "'.$dateRange['start'].'" and "'.$dateRange['end'].'") OR (endedOnDate between "'.$dateRange['start'].'" and "'.$dateRange['end'].'") OR (addedOnDate < "'.$dateRange['start'].'" and (endedOnDate >"'.$dateRange['end'].'" OR endedOnDate  = "0000-00-00")))');
		}

		$this->dbHandle->group_by('courseId');
		$rowResult = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();
		$rowIds = $paidCourseIds = array();
		foreach ($rowResult as $key => $value) {
			$rowIds[] = $value['id'];
			$paidCourseIds[] = $value['courseId'];
		}

		if(count($rowIds)>0){
			$this->dbHandle->select('min(subscriptionStartDate) as startDate,max(subscriptionExpiryDate) as endDate,min(addedonDate) as activationStartDate,',false);
			$this->dbHandle->from('courseSubscriptionHistoricalDetails');
			$this->dbHandle->where_in('id',$rowIds);
			$this->dbHandle->where('source','abroad');
			$this->dbHandle->where('packType',GOLD_SL_LISTINGS_BASE_PRODUCT_ID);
			$result = $this->dbHandle->get()->result_array();
			//echo $this->dbHandle->last_query();
		}
		
		//_p($result);
		return array("mainResult" => $result,"paidCourseIds"=>$paidCourseIds);
	}
	
	public function getCitiesForUsersByDuration($userIds, $dateRange)
	{
		
		if(count($userIds)==0){
			return array();
		}
		$this->initiateModel("read");
		$this->dbHandle->select('DISTINCT city,userId,submitDate',false);
		$this->dbHandle->from('registrationTracking');
		$this->dbHandle->where_in('userId', $userIds);
		//$this->dbHandle->where('submitDate>=', $dateRange['start'],false); //  because user may not have changed details in that duration
		$this->dbHandle->where('submitDate<="'. $dateRange['end'].'"','',false); // but may have changed before the duration began.
		$this->dbHandle->where('city is not null','',false);
		$this->dbHandle->order_by('userId','asc');
		$this->dbHandle->order_by('submitTime','asc');
		
		$userCities = $this->dbHandle->get()->result_array();
		
		// rows need to be arranged as an array :-
		//array(
		//	  'userId'=> array(
		//			array('date'=>d1,'city'=>c1),
		//			array('date'=>d2,'city'=>c2),
		//			...
		//		)
		//	  );
		
		$resultArr = array();
		foreach($userCities as $userCity)
		{
			if(!array_key_exists($userCity['userId'],$resultArr))
			{
				$resultArr[$userCity['userId']] = array();
			}
			$resultArr[$userCity['userId']][] = array('city'=>$userCity['city'],'date'=>$userCity['submitDate']);
		}
		return $resultArr;
	}

	public function getUserCurrentCity($userIds)
	{
		if(count($userIds)==0){
			return array();
		}
		$this->initiateModel('read');
		$this->dbHandle->select('userid as userId, city',false);
		$this->dbHandle->from('tuser');
		$this->dbHandle->where('city is not null','', false);
		$this->dbHandle->where_in('userid',$userIds);
		$result = $this->dbHandle->get()->result_array();
		$returnData = array();
		foreach($result as $row)
		{
			if(is_numeric($row['city'])){
				$returnData[$row['userId']] = $row['city'];
			}
		}
		return $returnData;
	}

	public function getShortlistedStudent($courseIds,$dateRange)
	{

/*select distinct a.userId,a.*,b.* from rmcShortlistDataForCandidates a join rmcShortlistDataCourseMapping b
on a.id = b.rmcShortlistDataId and a.status=b.status 
where a.addedOn between "2015-11-10 18:02:49" and "2016-11-10 18:02:49" 
and b.courseId in (202434,198721,226813);*/  
		if(!is_array($courseIds) || count($courseIds)==0){
			return false;
		}
		$this->initiateModel('read');
		$this->dbHandle->select('distinct rsdc.userId',false);
		$this->dbHandle->from('rmcShortlistDataForCandidates rsdc');
		$this->dbHandle->join('rmcShortlistDataCourseMapping rsdcm','rsdc.id = rsdcm.rmcShortlistDataId and rsdc.status=rsdcm.status');
		
		if($dateRange['start'] !='' && $dateRange['end']!=''){
		$this->dbHandle->where('(rsdc.addedOn between "'.$dateRange['start'].' 00:00:00" and "'.$dateRange['end'].' 23:59:59")');
		}

		$this->dbHandle->where_in('rsdcm.courseId',$courseIds);
		$this->dbHandle->order_by('rsdc.addedOn','desc');
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();
		return $result;
	}

	public function getUniversityFinalizedStudent($courseIds,$dateRange,$onlySubmittedCourses=false)
	{
		if(!is_array($courseIds) || count($courseIds)==0){
			return false;
		}
		$this->initiateModel('read');
		$this->dbHandle->select('distinct rcfc.userId',false);
		$this->dbHandle->from('rmcCandidateFinalizedCourses rcfc');
		
		if($dateRange['start'] !='' && $dateRange['end']!=''){
		$this->dbHandle->where('(rcfc.addedOn between "'.$dateRange['start'].' 00:00:00" and "'.$dateRange['end'].' 23:59:59")');
		}

		if($onlySubmittedCourses ==true){
		$this->dbHandle->where('rcfc.admissionOffered','submitted');	
		}else{
		$this->dbHandle->where('rcfc.admissionOffered <>','"submitted"',false);	
		}

		$this->dbHandle->where_in('rcfc.courseId',$courseIds);
		$this->dbHandle->order_by('rcfc.modifiedOn','desc');
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();
		return $result;
	}
	
	/*
	 * function that gets notes posted against given users who are being followed up , have been followed up, need not be followed up based on given date range
	 */
	public function getRMCNoteStatusByUserId($userIds,$dateRange)
	{
		if(count($userIds)==0)
		{
			return array();
		}
		$this->config->load('shikshaApplyCRM/shikshaApplyConfig');
		$noteIds = $this->config->item("EXCLUDED_NOTE_IDS_CONVERSION");
		$this->initiateModel('read');
		$this->dbHandle->select('note.user_id, note.note_id, master.contacted_status');
		$this->dbHandle->from('sa_rmc_user_notes note');
		$this->dbHandle->join('sa_rmc_notes_master master', 'master.id = note.note_id AND master.status="live"');
		$this->dbHandle->where('added_on<="'.$dateRange['end'].' 23:59:59"','',false);
		$this->dbHandle->where_in('user_id',$userIds);
		$this->dbHandle->where_not_in('master.id',$noteIds);
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();
		//_p($result);die;
		return $result;
	}

	public function getShortlistLatestRowByUserId($studentIds){
		/*select * 
		from rmcShortlistDataForCandidates 
		where id in(
		select max(id) from rmcShortlistDataForCandidates where userId in(32844581,32844600,32844555) group by userId);*/

		if(count($studentIds)==0)
		{
			return array();
		}
		$this->initiateModel('read');

		$this->dbHandle->select('max(id) as mxid')->from('rmcShortlistDataForCandidates');
		$this->dbHandle->where_in('userId',$studentIds);
		$this->dbHandle->group_by('userId');
		$subQuery =  $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();
		$maxIds =  array();
		foreach ($subQuery as $key => $value) 
		{
			$maxIds[] = $value['mxid'];
		}

		if(count($maxIds)>0){
	 		$this->dbHandle->select('userId,termSeason,termYear,courseName,addedOn');
	        $this->dbHandle->from('rmcShortlistDataForCandidates');
	        $this->dbHandle->where_in("id", $maxIds);
	        $result = $this->dbHandle->get()->result_array();
	        //echo $this->dbHandle->last_query();
	        $final = array();
	        foreach ($result as $value) 
			{
				$final[$value['userId']] = $value;
			}
	        return $final;
		}
		return array();
	}
	/*
	 * function that gets user & user's finalized course for which they have applied, been accepted,been rejected
	 */
	public function getApplicationProcessDataFromFinalizedCourses($courseIds,$dateRange)
	{
		if(count($courseIds)==0)
		{
			return array();
		}
		$this->initiateModel('read');
		$this->dbHandle->select('userId,courseId,admissionOffered,scholarshipReceived,scholarshipDetails,modifiedOn,status');
		$this->dbHandle->from('rmcCandidateFinalizedCourses');
		$this->dbHandle->where_in('courseId',$courseIds);
		$this->dbHandle->where('modifiedOn>="'.$dateRange['start'].' 00:00:00"','',false);
		$this->dbHandle->where('modifiedOn<="'.$dateRange['end'].' 23:59:59"','',false);
		$this->dbHandle->where_in('status',array('live','history'));
		$this->dbHandle->order_by('id','desc');
		$result = $this->dbHandle->get()->result_array();
		//_p($result);
		return $result;
	}
	/*
	 * function that returns university ids of courses that were compared along with a given set of course ids
	 */
	public function getComparedUniversities($courseIds,$exclusionIds,$dateRange)
	{
		if(count($courseIds)==0)
		{
			return array();
		}
		$this->initiateModel("read");
		$this->dbHandle->select('acpd.university_id,count(distinct u2.id) as compCount', false);
		$this->dbHandle->from('userFinallyComparedCourses u1',false);
		$this->dbHandle->join('userFinallyComparedCourses u2','u1.visitorSessionId = u2.visitorSessionId and u1.addedOnDate = u2.addedOnDate and u1.addedOnTime = u2.addedOnTime',false);
		$this->dbHandle->join('abroadCategoryPageData acpd','u2.courseId=acpd.course_id and acpd.status in ("live","deleted")',false);
		$this->dbHandle->where_in('u1.courseId',$courseIds);
		$this->dbHandle->where_not_in('u2.courseId',$exclusionIds);
		$this->dbHandle->where('u1.addedOnDate between "'.$dateRange['start'].'" and "'.$dateRange['end'].'"','',false);
		$this->dbHandle->group_by('acpd.university_id');
		$this->dbHandle->order_by('compCount','desc');
		$this->dbHandle->limit(5);
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();
		//_p($result);die;
		return $result;
	}

	public function getAppliedStudent($status,$courseIds=array(),$dateRange=array(),$studentIds=array())
	{
		
		$this->initiateModel('read');
		$this->dbHandle->select('max(id) as mxid');
		$this->dbHandle->from('rmcCandidateEnrollmentCourse');

		if(count($studentIds)>0){
			$this->dbHandle->where_in('userId',$studentIds);
		}
		if(count($courseIds)>0){
			$this->dbHandle->where_in('courseId',$courseIds);
		}
		if(count($dateRange)>0 && $dateRange['start']!='' && $dateRange['end']!=''){
			$this->dbHandle->where('modifiedOn between "'.$dateRange['start'].' 00:00:00" and "'.$dateRange['end'].' 23:59:59"','',false);
		}

		if($status=='visa'){
			$this->dbHandle->where('enrollmentStatus is null','',false);
		}
		if($status=='admitted'){
			$this->dbHandle->where('enrollmentStatus','yes');
		}

		$this->dbHandle->where_in('status',array('live','history'));
		$this->dbHandle->group_by('userId');
		$this->dbHandle->order_by('modifiedOn','desc');
		$subQuery = $this->dbHandle->get()->result_array();
		
		//echo $this->dbHandle->last_query();
		//_p($result);die;
		$maxIds =  array();
		foreach ($subQuery as $key => $value) 
		{
			$maxIds[] = $value['mxid'];
		}

		if(count($maxIds)>0){
			if(count($courseIds)>0){
				$this->dbHandle->select('userId');
			}else{
				$this->dbHandle->select('userId,visaStatus,visaReason,enrollmentStatus,enrollmentReason');
			} 		
	        $this->dbHandle->from('rmcCandidateEnrollmentCourse');
	        $this->dbHandle->where_in("id", $maxIds);
	        $result = $this->dbHandle->get()->result_array();
	        //echo $this->dbHandle->last_query();
			//_p($result);die;
	        return $result;
		}

		return array();
	}
}

?>
