<?php

class followUpTableMigrationScript extends MX_Controller {
	
	function __construct(){
		//exit;
	}
	
	function initDB() {

		$this->load->library("common/DbLibCommon");
		$this->dbLibObj = DbLibCommon::getInstance('ShikshaApply');
		$this->dbHandle = $this->_loadDatabaseHandle("write");
	}
	
	function addRecordsToPreCandidateTable(){
		
		ini_set('max_execution_time', -1);
		ini_set('memory_limit', '-1');
		$seeker = 0;
		$this->initDB();
		
		$this->rmcPostingLib = $this->load->library('shikshaApplyCRM/rmcPostingLib');
		
		$this->load->model('shikshaApplyCRM/rmcpostingmodel');
        $this->rmcPostingModel = new rmcpostingmodel();
		
		$counsellorsList = $this->rmcPostingLib->getAllRMCCounsellor();
		foreach($counsellorsList as $key=>$value)
		{
			$universities = $this->rmcPostingLib->getAllShikshaApplyUniversities($value['counsellor_id']);
			if(count($universities)>0){
				$universityId = array_map(function($a){ return $a['universityId'];},$universities);
				if(count($universityId)>0)
				{
					$universityId 	= $universityId;
					//fetch all users with stage 2 and is not in rmcCandidates table
					$this->dbHandle->select('	SQL_CALC_FOUND_ROWS
												rmc.userId,
												rmc.courseId,
												rmc.rmcUserCourseRatingId,
												rus.modifiedOn,
												max(rem.sentOn) as sentOn,
												IF( (rus.modifiedOn >  max(rem.sentOn) OR sentOn IS NULL ),rus.modifiedOn,  max(rem.sentOn)) AS lastUpdatedDate,									
												rmc.rating,
												max(rem.reminderNumber) as reminderNumber,
												cd.courseTitle as courseName,
												rmc.rmcUserCourseRatingId,
												ium.university_id as universityId,
												tu.email,
												tu.mobile,
												tu.firstname,
												tu.lastname',false);
					
						$this->dbHandle->from('rmcUserCourseRating rmc');
						$this->dbHandle->join('rmcUserStage rus','rus.userId = rmc.userId','inner');
						$this->dbHandle->join('rmcCandidates rmcc','rus.userId = rmcc.userId','left');
						$this->dbHandle->join('rmcUserReminders rem','rem.userId = rmc.userId','left');
						$this->dbHandle->join('course_details cd', 'cd.course_id = rmc.courseId','inner');
						$this->dbHandle->join('institute_university_mapping ium', 'cd.institute_id = ium.institute_id and cd.status = ium.status','inner');
						$this->dbHandle->join('tuser tu', 'rmc.userId = tu.userid','inner');		    
						$this->dbHandle->where('rmc.rating>0','',false);
						$this->dbHandle->where('rus.stageId','2');
						$this->dbHandle->where('rmcc.userId is null','',false);
						$this->dbHandle->where('rus.status','live');
						$this->dbHandle->where('cd.status','live');
						$this->dbHandle->where('rmc.status','live');
						
						if(!empty($universityId))
						{		
							$this->dbHandle->where_in('ium.university_id',$universityId);
						}
						
						$this->dbHandle->group_by("rmc.userId, rmc.courseId");
						$this->dbHandle->order_by('lastUpdatedDate','ASC');
						$tableresult = $this->dbHandle->get()->result_array();
						
						//_p($tableresult);
				
						if(count($tableresult)>0)
						{
							foreach($tableresult as $key2=>$value2)
							{
								$this->dbHandle->select('count(id) as totalRow');
								$this->dbHandle->from('rmcPreCandidateCounsellorMapping r');		    
								$this->dbHandle->where('r.rmcUserCourseRatingId',$value2['rmcUserCourseRatingId']);
								$countResult = $this->dbHandle->get()->result_array();
								$countResult = reset($countResult);
								_p($countResult);
								if($countResult['totalRow']==0)
								{
									$preCandidateCounsellorData = array();
									$preCandidateCounsellorData['rmcUserCourseRatingId']= $value2['rmcUserCourseRatingId'];
									$preCandidateCounsellorData['userId'] 				= $value2['userId'];
									$preCandidateCounsellorData['counsellorId'] 		= $value['counsellor_id'];
									$preCandidateCounsellorData['addedAt'] 				= date('Y-m-d H:i:s');
									$preCandidateCounsellorData['addedBy'] 				= $value['counsellor_id'];
									$preCandidateCounsellorData['modifiedAt'] 			= date('Y-m-d H:i:s');
									$preCandidateCounsellorData['modifiedBy'] 			= $value['counsellor_id'];
									$preCandidateCounsellorData['followUpDate'] 		= date("Y-m-d", strtotime(date('Y-m-d'). " +3 days"));
									$preCandidateCounsellorData['status'] 				= 'followUp';
									// insert
									$result = $this->dbHandle->insert('rmcPreCandidateCounsellorMapping',$preCandidateCounsellorData);
								}
							}
						}
				}
			}
		}
	}

	function fillCandidatureTypeInPreCandidateTable(){
		$this->load->model('shikshaApplyCRM/rmcfollowupmodel');
        $this->rmcFollowUpModel = new rmcfollowupmodel();

        $rmcIdsChunks       = array();
        $nonRmcIdsChunks    = array();
        $leadIdsChunks      = array();
        $oldRmcIdsChunks    = array();
        $oldNonRmcIdsChunks = array();
        
        $this->rmcFollowUpModel->getRMCPreCandidateData($rmcIdsChunks); //Rating 1,2,3,4
        $this->rmcFollowUpModel->updatePreCandidateData($rmcIdsChunks, 'rmcResponse');

        $this->rmcFollowUpModel->getAllClientCourseFromAllocationTable($nonRmcIdsChunks); //New Non-RMC
        $this->rmcFollowUpModel->updatePreCandidateData($nonRmcIdsChunks, 'nonRmcResponse');

        $this->rmcFollowUpModel->getPreCandidateDataForLeads($leadIdsChunks); //Leads
        $this->rmcFollowUpModel->updatePreCandidateData($leadIdsChunks, 'lead');

        $this->rmcFollowUpModel->getOldRMCRecords($oldRmcIdsChunks); //Pure RMC - Rating 0 (history) and 5 (live)
        $this->rmcFollowUpModel->updatePreCandidateData($oldRmcIdsChunks, 'rmcResponse');

        $this->rmcFollowUpModel->getOldNonRMCRecords($oldNonRmcIdsChunks); //Remaining are old Non-RMC
        $this->rmcFollowUpModel->updatePreCandidateData($oldNonRmcIdsChunks, 'nonRmcResponse');

        echo 'Migration completed for candidatureType.';
		die;
	}

	/**
	* This script fills the values in newly created columns 'countryId', 'LDBCourseId', 'courseLevel'
	* in the rmcPreCandidateCounsellorMapping table.
	*/

	public function fillNewColumnsInPreCandidateTable(){
		$this->load->model('shikshaApplyCRM/rmcfollowupmodel');
        $this->rmcFollowUpModel = new rmcfollowupmodel();
        $preCandidateIds = array();
        $courseIdArr = array();
        $courseDetails = array();
        $preCandidateDataForLeads = array();
        $userIdArr = array();
    	$userDetails = array();
        $this->rmcFollowUpModel->getAllRecordsInPreCandidateTable($preCandidateIds, $courseIdArr, $courseDetails, $preCandidateDataForLeads, $userDetails, $userIdArr);
        foreach ($courseDetails as $courseData) {
        	$ldbCourseIds = explode(',', $courseData['ldb_course_ids']);
        	$desiredCourseIdToUpdate = 0;
        	if(in_array(1508, $ldbCourseIds)){
        		$desiredCourseIdToUpdate = 1508;
        	}
        	if(in_array(1509, $ldbCourseIds)){
        		$desiredCourseIdToUpdate = 1509;
        	}
        	if(in_array(1510, $ldbCourseIds)){
        		$desiredCourseIdToUpdate = 1510;
        	}

        	$updateQeuryData = array();
        	$updateQeuryData['countryId']   = $courseData['country_id'];
        	$updateQeuryData['LDBCourseId'] = $desiredCourseIdToUpdate;
        	$updateQeuryData['courseLevel'] = $courseData['course_level'];
        	
        	$this->rmcFollowUpModel->updateCourseDetailForSet($updateQeuryData, $preCandidateIds[$courseData['course_id']]);
        }

        foreach ($userDetails as $key => $value) {
        	$desiredCourseIdToUpdate = 0;
        	if(in_array($value['LDBCourseId'], array(1508,1509,1510))){
        		$desiredCourseIdToUpdate = $value['LDBCourseId'];
        	}
        	$updateQeuryData = array();
        	$updateQeuryData['countryId']   = $value['countryId'];
        	$updateQeuryData['LDBCourseId'] = $desiredCourseIdToUpdate;
        	$updateQeuryData['courseLevel'] = $value['courseLevel'];

        	$this->rmcFollowUpModel->updateCourseDetailForSet($updateQeuryData, $preCandidateDataForLeads[$value['userId']]);
        }
        echo 'Migration completed.';
	}
}
?>

