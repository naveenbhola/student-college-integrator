<?php
class salesoperationmodel extends MY_Model
{
	/**
	 * constructor method.
	 *
	 * @param array
	 * @return array
	 */
	function __construct()
	{ 
		// parent::__construct('MISTracking');
		parent::__construct('Listing');
 	       $this->abroadCommonLib = $this->load->library('listingPosting/AbroadCommonLib');
	}
	
	private function initiateModel($operation='read'){
		if($operation=='read'){ 
			$this->dbHandle = $this->getReadHandle();
		}else{
		    $this->dbHandle = $this->getWriteHandle();
		}		
	}

	public function getGroupId($userId, $userActiveCheckReq = true){
		if($userId && $userId > 0){
			$this->initiateModel('read');
			$this->dbHandle->select('groupId');
			$this->dbHandle->from('spliceGroupMembers');
			$this->dbHandle->where('userId',$userId);
			if($userActiveCheckReq == true){
				$this->dbHandle->where('isActive','yes');
			}
			$result = $this->dbHandle->get()->result_array();
			//echo $this->dbHandle->last_query();die;
			return $result[0]['groupId'];
		}
	}

	public function getGroupUserDetails($groupIds){
		if(is_array($groupIds) && count($groupIds) >0){
			$this->initiateModel();
			$this->dbHandle->select('userId,addedOn,addedBy,lastLoginOn,isActive');
			$this->dbHandle->from('spliceGroupMembers');
			$this->dbHandle->where_in('groupId',$groupIds);
			$result = $this->dbHandle->get()->result_array();
			//echo $this->dbHandle->last_query();die;
			return $result;
		}else{
			return array();
		}
	}

	public function getSpliceUserDetails($userIds){
		if(is_array($userIds) && count($userIds) > 0){
			$this->initiateModel();
			//$this->dbHandle->select('SQL_CALC_FOUND_ROWS userId,addedOn,addedBy,groupId,lastLoginOn,isActive',false);
			$this->dbHandle->select('userId,addedOn,addedBy,groupId,lastLoginOn,isActive');
			$this->dbHandle->from('spliceGroupMembers');
			$this->dbHandle->where_in('userId',$userIds);
			$this->dbHandle->order_by('addedOn','desc');
			//$this->dbHandle->limit(400,0);
			$result = $this->dbHandle->get()->result_array();
			//echo $this->dbHandle->last_query();die;
			//$totalRows = $this->dbHandle->query('SELECT FOUND_ROWS() count;')->row()->count;
			/*return array('totalRows' => $totalRows,
					'result' => $result);*/
			return $result;
		}else{
			/*return array('totalRows' => 0,
					'result' => array());*/
			return array();
		}
		
	}

	public function getUserIdsForGivenGroups($groupIds){
		if(!(is_array($groupIds) && count($groupIds))){
			return array();
		}
		$this->initiateModel();

		$this->dbHandle->select('userId');
		$this->dbHandle->from('spliceGroupMembers');
		$this->dbHandle->where('isActive','yes');
		$this->dbHandle->where_in('groupId',$groupIds);
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		return $result;
	}

	public function getAddedUserIds($ids,$type,$activeUserCheckRequired = false){
		if($type == 'userId'){
			if($ids==''){
				return array();
			}
		}else if($type == 'groupId'){
			if(!(is_array($ids) && count($ids))){
				return array();
			}
		}else if($type == 'pseudoAdmin'){
			if(!(is_array($ids) && count($ids))){
				return array();
			}
		}
		$this->initiateModel();
		$this->dbHandle->select('userId,groupId');
		$this->dbHandle->from('spliceGroupMembers');
		if($type == 'userId'){
			$this->dbHandle->where('supervisorId',$ids);
		}else if($type == 'groupId'){
			$this->dbHandle->where_in('groupId',$ids);
		}else if($type == 'pseudoAdmin'){
			$this->dbHandle->where_in('groupId',$ids);
		}
		if($activeUserCheckRequired == true){
			$this->dbHandle->where('isActive','yes');
		}
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		return $result;
	}


	public function getUserGroupDetails($groupId){
		$this->initiateModel();
		$this->dbHandle->select('userId');
		$this->dbHandle->from('spliceGroupMembers');
		$this->dbHandle->where('groupId',$groupId);
		$this->dbHandle->where('isActive','yes');
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		return $result;
	}

	public function getLeadDetails($type,$typeValue,$leadId){
		$this->initiateModel();

		$this->dbHandle->select('userId');
		$this->dbHandle->from('spliceGroupMembers');
		if($type == 'userId'){
			$this->dbHandle->where('supervisorId',$typeValue);
			$this->dbHandle->where('groupId',$leadId);
		}else if($type == 'groupId'){			
			$this->dbHandle->where('groupId',$leadId);
		}
		$this->dbHandle->where('isActive','yes');
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		//error_log($this->dbHandle->last_query(), 3, "/var/www/html/my-errors.txt");
		return $result;
	}

	public function getSupervisorId($userId){
		$this->initiateModel();
		$this->dbHandle->select('supervisorId');
		$this->dbHandle->from('spliceGroupMembers');
		$this->dbHandle->where('userId',$userId);
		$this->dbHandle->where('isActive','yes');
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		return $result[0]['supervisorId'];
	}

	public function getGroupIdAndSupervisorId($userIds){
		if(!(is_array($userIds) && count($userIds))){
			return array();
		}
		$this->initiateModel();
		$this->dbHandle->select('userId,groupId,supervisorId');
		$this->dbHandle->from('spliceGroupMembers');
		$this->dbHandle->where_in('userId',$userIds);
		$this->dbHandle->where('isActive','yes');
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		return $result;
	}

	public function updateLastLogin($userId){
		if($userId){
			$this->initiateModel('write');
			$sql = "update spliceGroupMembers set lastLoginOn = now() where userId = ?";
			$this->dbHandle->query($sql,array($userId));
		}
	}

	public function updateLastLoginDate($userId){
		if($userId){
			$this->initiateModel('write');			
			$sql = "update spliceGroupMembers set lastLoginOn = now() where userId = ?";
			$this->dbHandle->query($sql,array($userId));
		}
	}

	public function addNewMemberToInterface($inputData){
		$data = array(
			'userId'	=> $inputData['userId'],
			'addedOn' 	=> date("Y-m-d H:i:s"),
			'addedBy'	=> $inputData['addedBy'],
			'groupId'	=> $inputData['groupId'],
			'branchId'	=> $inputData['branchId']?$inputData['branchId']:0,
			'updatedBy'	=> $inputData['addedBy'],
			'updatedOn'	=> date("Y-m-d H:i:s"),
			'supervisorId' => $inputData['supervisorId']
			);
		$this->initiateModel('write');
		$queryCmd = $this->dbHandle->insert_string('spliceGroupMembers',$data);
		$query = $this->dbHandle->query($queryCmd);
        $recentId = $this->dbHandle->insert_id();
        return $recentId;
	}

	public function getTeamName($groupId){
		$this->initiateModel('read');
		$this->dbHandle->select('team');
		$this->dbHandle->from('spliceGroups');
		$this->dbHandle->where('id',$groupId);
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		return $result[0]['team'];	
	}

	public function getGroupDetails($groupIds){
		if(!(is_array($groupIds) && count($groupIds))){
			return array();
		}
		$this->initiateModel();
		$this->dbHandle->select('id,groupName,team');
		$this->dbHandle->from('spliceGroups');
		$this->dbHandle->where_in('id',$groupIds);
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		return $result;
	}

	public function getTeamGroupIds($team){
		if(!(is_array($team) && count($team))){
			return array();
		}
		$this->initiateModel();
		$this->dbHandle->select('id,team');
		$this->dbHandle->from('spliceGroups');
		$this->dbHandle->where_in('team',$team);
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		return $result;
	}

	public function getUserName($userIds){
		if(!(is_array($userIds) && count($userIds))){
			return array();
		}
		$this->initiateModel();
		$this->dbHandle->select('userId,firstname,lastname');
		$this->dbHandle->from('tuser');
		$this->dbHandle->where_in('userId',$userIds);
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		return $result;
	}

	public function getUserDetails($inputData,$type ='email'){
		if($type == 'userId'){
			if(is_array($inputData)){
				if(!(count($inputData))){
					return array();
				}
			}
		}
		$this->initiateModel();
		$this->dbHandle->select('userId,email,firstname,lastname');
		$this->dbHandle->from('tuser');
		if($type == 'email'){
			$this->dbHandle->where('email',$inputData);
		}else if($type == 'userId'){
			if(is_array($inputData)){
				$this->dbHandle->where_in('userId',$inputData);				
			}else{
				$this->dbHandle->where('userId',$inputData);
			}
		}
		
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		return $result;
	}

	public function checkIfListingIsValid($site,$listingId){
		$resultArray = array();
		$this->initiateModel();
		if($site == 'studyAbroad'){
			$this->dbHandle->select('distinct(university_id) as listingId');
			$this->dbHandle->from('abroadCategoryPageData');
			$this->dbHandle->where('university_id',$listingId);
		}else if($site == 'domestic'){
			$this->dbHandle->select('listing_id as listingId, name, listing_type');
			$this->dbHandle->from('shiksha_institutes');
			$this->dbHandle->where('listing_id',$listingId);
		}
		$this->dbHandle->where('status','live');
		$result = $this->dbHandle->get()->result_array();
		if($result){
			if($site == 'studyAbroad'){
				$listingId = $result[0]['listingId'];
				$listingName = $this->getListingName($listingId,'university');
				$resultArray['listingName'] = $listingName[0]['listing_title'];
				$resultArray['listingType'] = 'university';
			}else{
				$resultArray['listingName'] = $result[0]['name'];
				$listingURLType = $result[0]['listing_type'];
				if($listingURLType == 'university'){
					$listingURLType = 'university_national';
				}
				$resultArray['listingType'] = $listingURLType;
			}
		}		
		return  $resultArray;		
	}

	public function getListingName($listingId,$listingType){
		$this->initiateModel();
		$this->dbHandle->select('listing_id,listing_title');
		$this->dbHandle->from('listings_main');
		$this->dbHandle->where('listing_type_id',$listingId);
		$this->dbHandle->where('listing_type',$listingType);
		$this->dbHandle->where('status','live');
		$result = $this->dbHandle->get()->result_array();
		return $result;
	}

	public function checkIfLandingPageURLIsValid($landingPageURL){
		$this->initiateModel();

		$this->dbHandle->select('listing_id');
		$this->dbHandle->from('listings_main');
		$this->dbHandle->where('listing_seo_url',$landingPageURL);
		$this->dbHandle->where('listing_type','course');
		$this->dbHandle->where('status','live');
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		return  $result;
	}

	public function addNewRequest($data,$table){
		//_p($table);_p($data);die;
		$this->initiateModel('write');		
		if(($table == 'spliceRequestTaskAttributes')){
			$this->dbHandle->insert_batch($table,$data);
		}else{
			$this->dbHandle->insert($table,$data);
		}
		//echo $this->dbHandle->last_query();die;
		return $this->dbHandle->insert_id();
	}

	public function getTaskDataForGivenUserIds($inputArray,$checkForTaskIds){
		if($checkForTaskIds == true){
			if(!(is_array($inputArray['requestIds']) && count($inputArray['requestIds']))){
				return '';
			}
		}
		//_p($inputArray);die;
		$this->initiateModel();

		$this->dbHandle->select('SQL_CALC_FOUND_ROWS distinct(srt.id) as id,srt.taskTitle,sr.requestedOn,sr.requestedBy,srt.TATDate,srt.currentStatus',false);
		$this->dbHandle->from('spliceRequestTasks srt');
		$this->dbHandle->join('spliceRequests sr','sr.id = srt.requestId','inner');

		if($checkForTaskIds == true){
			$this->dbHandle->where_in('srt.id',$inputArray['requestIds']);
		}

		$salesAndAdminGroupId = array_merge($inputArray['teamGroupIds']['admin'],$inputArray['teamGroupIds']['salesTeam']);
		if(!in_array($inputArray['groupId'],$salesAndAdminGroupId)){
			if(in_array($inputArray['groupId'],$inputArray['teamGroupIds']['design'])){
				$taskCategory = array('Banner','Shoshkele','Mailer');
				$this->dbHandle->where_in('srt.taskCategory',$taskCategory);
			}else if(in_array($inputArray['groupId'],$inputArray['teamGroupIds']['contentOps'])){
				$this->dbHandle->where('srt.taskCategory','Listing');
			}else if(in_array($inputArray['groupId'],$inputArray['teamGroupIds']['salesOps'])){
				$this->dbHandle->where('srt.taskCategory','Campaign Activation');
			}
		}

		if($inputArray['isAdvancedFilter']){
			if($inputArray['course']){
				if($inputArray['course'][0] == "all"){				
					$this->dbHandle->where('srt.taskCategory','Mailer');
				}else{
					$this->dbHandle->join('spliceRequestTaskAttributes srta','srta.requestTaskId = srt.id','inner');
					$this->dbHandle->where('srta.attributeName','course');
					$this->dbHandle->where_in('srta.attributeValue',$inputArray['course']);
				}
			}

			if($inputArray['selectStatus']){
				if($inputArray['selectStatus'][0] != "all"){
					$this->dbHandle->where_in('srt.currentStatus',$inputArray['selectStatus']);
				}
			}

			if($inputArray['branch'] && $inputArray['branch'][0] !="all"){
				$this->dbHandle->join('spliceGroupMembers sgm','sgm.userId = sr.requestedBy','inner');
				$this->dbHandle->where_in('sgm.branchId',$inputArray['branch']);
			}

			if($inputArray['requestId']){
				$this->dbHandle->where('sr.id',$inputArray['requestId']);
			}

			if($inputArray['taskId']){
				$this->dbHandle->where('srt.id',$inputArray['taskId']);
			}

			if($inputArray['salesOrderNo']){
				$this->dbHandle->where('sr.salesOrderNumber',$inputArray['salesOrderNo']);
			}

			if($inputArray['lastUpdatedOn'] && $inputArray['lastUpdatedOn'] != 0){
				$inputArray['lastUpdatedOn'] = date('Y-m-d',strtotime(date('Y-m-d') ."  -".intval($inputArray['lastUpdatedOn'])." days"));				
				$this->dbHandle->where('srt.lastUpdatedOn >=',$inputArray['lastUpdatedOn'].' 00:00:00');
			}

			if($inputArray['fromDate']){
				$this->dbHandle->where('sr.requestedOn >=',$inputArray['fromDate'].' 00:00:00');
				$this->dbHandle->where('sr.requestedOn <=',$inputArray['toDate'].' 23:59:59');
			}

			if($inputArray['taskCategory'] && $inputArray['taskCategory'][0] != "all"){			
				$this->dbHandle->where_in('srt.taskCategory',$inputArray['taskCategory']);
			}
		}
		$this->dbHandle->order_by('srt.lastUpdatedOn','desc');		
		$this->dbHandle->limit(400,0);
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		$totalRows = $this->dbHandle->query('SELECT FOUND_ROWS() count;')->row()->count;
		return array('totalRows' => $totalRows,
					'result' => $result);
		return $result;
	}

	public function getPendingCampaignRequest($requestIds,$checkForRequestIds,$requestType){
		if($checkForRequestIds == 'true'){
			if(!(is_array($requestIds) && count($requestIds))){
				return array();
			}
		}
		$this->initiateModel();
		$this->dbHandle->select('*');
		$this->dbHandle->from('splicePendingCampaignRequest');
		$this->dbHandle->where('isPendingForCampaignActivation','1');
		if($checkForRequestIds == 'true'){
			if($requestType == 'request'){
				$this->dbHandle->where_in('requestId',$requestIds);
			}else{
				$this->dbHandle->where_in('requestTaskId',$requestIds);
			}
		}
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		return $result;
	}

	public function getRequestDataForGivenUserIds($inputArray,$checkForRequestIds){
		//_p($inputArray);die;
		if($checkForRequestIds == true){
			if(count($inputArray['requestIds']) <= 0){
				return '';
			}
		}
		$this->initiateModel();
		$this->dbHandle->select('SQL_CALC_FOUND_ROWS distinct(sr.id) as id,sr.salesOrderNumber,sr.campaignDate,sr.requestedOn,sr.requestedBy,sr.status as status',false);

		$this->dbHandle->from('spliceRequests sr');
		if($checkForRequestIds == true){
			$requestIds = implode(',',$inputArray['requestIds']);
			$this->dbHandle->where('sr.id in ( '.$requestIds.' )');
		}

		if($inputArray['isAdvancedFilter']){
			$isSpliceRequestTasksAlreadyUsed = false;
			if($inputArray['course']){
				if($isSpliceRequestTasksAlreadyUsed == false){
					$this->dbHandle->join('spliceRequestTasks srt','sr.id = srt.requestId','inner');	
					$isSpliceRequestTasksAlreadyUsed = true;
				}
				$isSpliceRequestTasksRequired = true;
				if($inputArray['course'][0] == "all"){				
					$this->dbHandle->where('srt.taskCategory','Mailer');
				}else{
					$this->dbHandle->join('spliceRequestTaskAttributes srta','srta.requestTaskId = srt.id','inner');
					$this->dbHandle->where('srta.attributeName','course');
					$this->dbHandle->where_in('srta.attributeValue',$inputArray['course']);
				}
			}

			if($inputArray['selectStatus']){
				if($inputArray['selectStatus'][0] != "all"){
					$this->dbHandle->where_in('sr.status',$inputArray['selectStatus']);
				}
			}

			if($inputArray['taskCategory'] && $inputArray['taskCategory'][0] != "all"){
				if($isSpliceRequestTasksAlreadyUsed == false){
					$this->dbHandle->join('spliceRequestTasks srt','sr.id = srt.requestId','inner');	
					$isSpliceRequestTasksAlreadyUsed = true;
				}
				$this->dbHandle->where_in('srt.taskCategory',$inputArray['taskCategory']);
			}

			if($inputArray['branch'] && $inputArray['branch'][0] !="all"){
				$this->dbHandle->join('spliceGroupMembers sgm','sgm.userId = sr.requestedBy','inner');
				$this->dbHandle->where_in('sgm.branchId',$inputArray['branch']);
			}

			if($inputArray['requestId']){
				$this->dbHandle->where('sr.id',$inputArray['requestId']);
			}

			if($inputArray['salesOrderNo']){
				$this->dbHandle->where('sr.salesOrderNumber',$inputArray['salesOrderNo']);
			}

			if($inputArray['lastUpdatedOn'] && $inputArray['lastUpdatedOn'] != 0){				
				$inputArray['lastUpdatedOn'] = date('Y-m-d',strtotime(date('Y-m-d') ."  -".intval($inputArray['lastUpdatedOn'])." days"));				
				$this->dbHandle->where('date(sr.lastUpdatedOn) >=',$inputArray['lastUpdatedOn'].' 00:00:00');
			}

			if($inputArray['fromDate']){
				$this->dbHandle->where('sr.requestedOn >=',$inputArray['fromDate'].' 00:00:00');
				$this->dbHandle->where('sr.requestedOn <=',$inputArray['toDate'].' 23:59:59');
			}
		}

		$this->dbHandle->order_by('sr.lastUpdatedOn','desc');
		$this->dbHandle->limit(400,0);
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		$totalRows = $this->dbHandle->query('SELECT FOUND_ROWS() count;')->row()->count;
		//_p($result);_p($result1);die;
		
		return array('totalRows' => $totalRows,
					'result' => $result);
	}

	public function getTaskDetails($requestId){
		$this->initiateModel();

		$this->dbHandle->select('id,taskCategory,taskTitle,assignee,TATDate,currentStatus');
		$this->dbHandle->from('spliceRequestTasks');
		$this->dbHandle->where('requestId',$requestId);
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		return $result;
	}

	public function getTaskDetailsForPendingTask($userIds,$statusArray){
		if(!(is_array($userIds) && count($userIds))){
			return array();
		}
		$this->initiateModel();
		$this->dbHandle->select('id,taskCategory,currentStatus,TATDate,assignee');
		$this->dbHandle->from('spliceRequestTasks');
		$this->dbHandle->where_in('assignee',$userIds);
		$this->dbHandle->where_not_in('currentStatus',$statusArray);
		//$this->dbHandle->group_by('TATDate');
		//$this->dbHandle->order_by('id','desc');
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		return $result;
	}

	public function getPendingRequestTask($requestIds){
		$this->initiateModel();
		$this->dbHandle->select('id,taskCategory');
		$this->dbHandle->from('spliceRequestTasks');
		if(count($requestIds) > 0){
			$this->dbHandle->where_in('requestId',$requestIds);	
		}
		$statusArray = array('clientApprovedAndClosed','cancel');
		$this->dbHandle->where_not_in('currentStatus' ,$statusArray);
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		return $result;
	}

	public function getPendingRequestTaskForClientApproval($requestIds){
		$this->initiateModel();
		$this->dbHandle->select('id,taskCategory');
		$this->dbHandle->from('spliceRequestTasks');
		if(count($requestIds) > 0){
			$this->dbHandle->where_in('requestId',$requestIds);
		}
		$this->dbHandle->where('currentStatus' ,'doneAndReviewed');
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		return $result;
	}

	public function getTaskIdsForRequestIds($requestIds){
		if(!(is_array($requestIds) && count($requestIds))){
			return array();
		}
		$this->initiateModel();
		$this->dbHandle->select('id');
		$this->dbHandle->from('spliceRequestTasks');
		$this->dbHandle->where_in('requestId',$requestIds);
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		return $result;
	}

	public function getRequestStatusWiseDataForOther($userIds){
		$this->initiateModel();		
		$this->dbHandle->select('currentStatus,count(1) as count');
		$this->dbHandle->from('spliceRequestTasks');
		if(count($userIds) > 0){
			$this->dbHandle->where_in('assignee',$userIds);
		}
		$this->dbHandle->group_by('currentStatus');
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		
		return $result;
	}	

	public function getTATExpiredRequestCountForOther($userIds){
		if(!(is_array($userIds) && count($userIds))){
			return array();
		}
		$this->initiateModel();
		$this->dbHandle->select('TATDate,currentStatus,count(1) as count');
		$this->dbHandle->from('spliceRequestTasks');
		$this->dbHandle->where_in('assignee',$userIds);		
		$this->dbHandle->where('TATDate >= ',date('Y-m-d'));
		$this->dbHandle->where('TATDate <= ',date('Y-m-d',strtotime(date('y-m-d') . "+1 days")));
		$this->dbHandle->group_by('TATDate,currentStatus');
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		
		return $result;
	}

	public function getRequestByForCurrentTask($requestTaskId){
		$this->initiateModel();

		$this->dbHandle->select('sr.requestedBy as requestedBy,srt.assignee as assignee');	
		$this->dbHandle->from('spliceRequestTasks srt');
		$this->dbHandle->join('spliceRequests sr','srt.requestId = sr.id','inner');
		$this->dbHandle->where('srt.id',$requestTaskId);	
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		
		return $result;
	}

	public function getTaskCategoryCurrentAssignee($requestTaskId){
		$this->initiateModel();

		$this->dbHandle->select('requestId,taskCategory,assignee,currentStatus');
		$this->dbHandle->from('spliceRequestTasks');
		$this->dbHandle->where('id',$requestTaskId);
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		return $result;
	}

	public function getTaskData($requestTaskId){
		$this->initiateModel();
		$this->dbHandle->select('*');
		$this->dbHandle->from('spliceRequestTasks');
		$this->dbHandle->where('id',$requestTaskId);
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		return $result;
	}

	public function getAllTaskForGivenRequestId($requestId, $status =""){
		$this->initiateModel();
		$this->dbHandle->select('id,taskCategory,currentStatus');
		$this->dbHandle->from('spliceRequestTasks');
		$this->dbHandle->where('requestId',$requestId);
		if($status == "live"){
			$this->dbHandle->where_not_in('currentStatus',array("cancel","clientApprovedAndClosed"));
		}
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		
		return $result;
	}

	public function getLastUpdatedOnForTask($requestTaskId){
		if(!(is_array($requestTaskId) && count($requestTaskId))){
			return array();
		}
		$this->initiateModel();
		$this->dbHandle->select('lastUpdatedOn');
		$this->dbHandle->from('spliceRequestTasks');
		$this->dbHandle->where_in('id',$requestTaskId);
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		
		return $result[0]['lastUpdatedOn'];	
	}

	public function getCurrentStatusForTasks($requestTaskId){
		if(!(is_array($requestTaskId) && count($requestTaskId))){
			return array();
		}
		$this->initiateModel();
		$this->dbHandle->select('id,currentStatus,taskCategory,TATDate');
		$this->dbHandle->from('spliceRequestTasks');
		$this->dbHandle->where_in('id',$requestTaskId);
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		
		return $result;
	}

	public function getRequestIdForTaskIds($requestTaskIds){
		if(!(is_array($requestTaskIds) && count($requestTaskIds))){
			return array();
		}
		$this->initiateModel();
		//_p($requestTaskIds);die;
		$this->dbHandle->select('distinct(requestId)');
		$this->dbHandle->from('spliceRequestTasks');
		$this->dbHandle->where_in('id',$requestTaskIds);
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		
		return $result;	
	}

	public function getCurrentTATForTask($requestTaskId){
		$this->initiateModel();

		$this->dbHandle->select('TATDate');
		$this->dbHandle->from('spliceRequestTasks');
		$this->dbHandle->where('id',$requestTaskId);		
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		
		return $result;	
	}

	public function getTATExpiredYesterdayDetails($date,$status,$userIds){
		$this->initiateModel();

		$this->dbHandle->select('id,requestId,taskTitle,taskCategory,currentStatus,assignee,TATDate,taskCategory,site');
		$this->dbHandle->from('spliceRequestTasks');
		$this->dbHandle->where('date(TATDate)',$date);
		$this->dbHandle->where_not_in('currentStatus',$status);
		$this->dbHandle->where_not_in('assignee',$userIds);
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();echo '<br>';die;
		
		return $result;	
	}

	public function updateOrInsertRowInTaskActionTable($data,$requestTaskId){
		$this->initiateModel('write');
		$this->dbHandle->where('id',$requestTaskId);
		$this->dbHandle->update('spliceRequestTasks',$data);
		//$this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
	}

	public function updateRequestTaskTable($inputData,$id){
		$this->initiateModel('write');
		$this->dbHandle->where('id',$id);
		$this->dbHandle->update('spliceRequestTasks',$inputData);
	}

	public function getRequestDetails($requestId){
		//_p($requestId);die;
		$this->initiateModel();

		$this->dbHandle->select('*');
		$this->dbHandle->from('spliceRequests');
		$this->dbHandle->where_in('id',$requestId);		
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		
		return $result;	
	}

	public function getRequestStatusWiseDataForClient($userIds){
		$this->initiateModel();

		$this->dbHandle->select('status,count(1) as count');
		$this->dbHandle->from('spliceRequests');
		if(count($userIds) > 0){
			$this->dbHandle->where_in('requestedBy',$userIds);
		}
		$this->dbHandle->group_by('status');
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		
		return $result;
	}

	public function getRequestStatusWiseDataForRequestIds($requestIds){
		$this->initiateModel();

		$this->dbHandle->select('status,count(1) as count');
		$this->dbHandle->from('spliceRequests');
		if(count($requestIds) > 0){
			$this->dbHandle->where_in('id',$requestIds);
		}
		$this->dbHandle->group_by('status');
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		
		return $result;
	}

	public function getRequestCreatedByUsers($userIds){
		$this->initiateModel();

		$this->dbHandle->select('id,requestType');
		$this->dbHandle->from('spliceRequests');
		if($userIds && count($userIds) > 0){
			$this->dbHandle->where_in('requestedBy',$userIds);
		}		
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		
		return $result;
	}

	public function getTATExpiredRequestCount($userIds){
		if(!(is_array($userIds) && count($userIds))){
			return array();
		}
		$this->initiateModel();
		$this->dbHandle->select('count(1) as count');
		$this->dbHandle->from('spliceRequests');
		$this->dbHandle->where_in('requestedBy',$userIds);
		$this->dbHandle->where('status != ','Approved From Client');
		$this->dbHandle->where('TATDate < ',date('y-m-d'));
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		
		return $result[0]['count'];
	}

	public function updateSpliceRequestTable($inputData,$id){
		$this->initiateModel('write');
		$this->dbHandle->where('id',$id);
		$this->dbHandle->update('spliceRequests',$inputData);
	}

	public function getAllAssigneeForSalesTeamForRequest(){
		$this->initiateModel();
		$this->dbHandle->select('id,requestedBy,reassignedUserIds');
		$this->dbHandle->from('spliceRequests');
		$result = $this->dbHandle->get()->result_array();
		
		//echo $this->dbHandle->last_query();die;
		return $result;	
	}

	public function getLastUpdatedOnForRequest($requestId){
		if(!(is_array($requestId) && count($requestId))){
			return array();
		}
		$this->initiateModel();

		$this->dbHandle->select('lastUpdatedOn');
		$this->dbHandle->from('spliceRequests');
		$this->dbHandle->where_in('id',$requestId);
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		
		return $result[0]['lastUpdatedOn'];	
	}

	public function getReassignedUserIdsForTask($requestId){
		$this->initiateModel();

		$this->dbHandle->select('requestedBy,reassignedUserIds');
		$this->dbHandle->from('spliceRequests');
		$this->dbHandle->where('id',$requestId);		
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		
		return $result;	
	}

	public function getCurrentTATForRequest($requestId){
		$this->initiateModel();

		$this->dbHandle->select('TATDate');
		$this->dbHandle->from('spliceRequests');
		$this->dbHandle->where('id',$requestId);		
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		
		return $result;	
	}

	public function getTaskActionDetailCurrentLevelAndAssignedBy($taskId){
		$this->initiateModel();

		$this->dbHandle->select('id,level,assignee,status');
		$this->dbHandle->from('spliceTasksActionDetails');
		$this->dbHandle->where('requestTaskId',$taskId);
		$this->dbHandle->order_by('id','desc');
		$this->dbHandle->limit(1,0);
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		
		return $result;
	}

	public function getRequestTaskIdForTaskActionIds($taskActionId){
		if(!(is_array($taskActionId) && count($taskActionId))){
			return array();
		}
		$this->initiateModel();

		$this->dbHandle->select('distinct(requestTaskId) as requestTaskId');
		$this->dbHandle->from('spliceTasksActionDetails');
		$this->dbHandle->where_in('id',$taskActionId);
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		return $result;	
	}

	public function getTaskActionDetailCurrentLevel($taskId){
		$this->initiateModel();

		$this->dbHandle->select('level');
		$this->dbHandle->from('spliceTasksActionDetails');
		$this->dbHandle->where('requestTaskId',$taskId);
		$this->dbHandle->order_by('id','desc');
		$this->dbHandle->limit(1,0);
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		
		return $result[0]['level'];
	}

	public function insertNewRowToTaskUpdateDetailsTable($inputArray){
		$this->initiateModel('write');
		//_p($inputArray);die;
		$this->dbHandle->insert('spliceTasksActionDetails',$inputArray);
		//echo $this->dbHandle->last_query();die;
		
		return $this->dbHandle->insert_id();
	}

	public function getTaskDetailHistory($requestTaskId){
		$this->initiateModel();

		$this->dbHandle->select('*');
		$this->dbHandle->from('spliceTasksActionDetails');
		$this->dbHandle->where('requestTaskId',$requestTaskId);
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		
		return $result;
	}

	public function getPreviousOtherStatusForTask($requestTaskId){
		$this->initiateModel();

		$this->dbHandle->select('id,isOtherActionTaken');
		$this->dbHandle->from('spliceTasksActionDetails');
		$this->dbHandle->where('requestTaskId',$requestTaskId);
		$this->dbHandle->order_by('id','desc');
		$this->dbHandle->limit(1,0);
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		
		return $result;	
	}

	public function getSecondLastRowForRequestTaskId($requestTaskId){
		$this->initiateModel();

		$this->dbHandle->select('assignee,actionTakenBy,status,assignedBy');
		$this->dbHandle->from('spliceTasksActionDetails');
		$this->dbHandle->where('requestTaskId',$requestTaskId);
		$this->dbHandle->order_by('id','desc');
		$this->dbHandle->limit(1,1);		
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		
		return $result;
	}

	public function getLastRowForRequestTaskId($requestTaskId){
		$this->initiateModel();

		$this->dbHandle->select('id,level,assignee,actionTakenBy');
		$this->dbHandle->from('spliceTasksActionDetails');
		$this->dbHandle->where('requestTaskId',$requestTaskId);
		$this->dbHandle->order_by('id','desc');
		$this->dbHandle->limit(1,0);		
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		
		return $result;
	}

	public function getAssigneeGroupId($requestTaskId){
		$this->initiateModel();

		$this->dbHandle->select('id,level,assignee,actionTakenBy');
		$this->dbHandle->from('spliceTasksActionDetails');
		$this->dbHandle->where('requestTaskId',$requestTaskId);
		$this->dbHandle->order_by('id','desc');
		$this->dbHandle->limit(1,0);		
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		
		return $result;	
	}

	public function getUserIdForPushedBack($requestTaskId){
		$this->initiateModel();

		$this->dbHandle->select('assignee,assignedBy,actionTakenBy');
		$this->dbHandle->from('spliceTasksActionDetails');
		$this->dbHandle->where('requestTaskId',$requestTaskId);
		$this->dbHandle->where_in('status',array('partialDoneAndReviewed','doneAndReviewed'));
		$this->dbHandle->order_by('id','desc');
		$this->dbHandle->limit(1,1);		
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		
		return $result;	
	}

	public function checkIfTaskHaveClientApprovedAndCreateHTMLPrevious($requestTaskId){
		$this->initiateModel();

		$this->dbHandle->select('id');
		$this->dbHandle->from('spliceTasksActionDetails');
		$this->dbHandle->where('requestTaskId',$requestTaskId);
		$this->dbHandle->where('status','clientApprovedAndCreateHTML');		
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		
		return $result;	
	}
	
	public function getOtherTeamLastUserIdForTask($requestTaskId, $status){
		$this->initiateModel();

		$this->dbHandle->select('assignee,actionTakenBy');
		$this->dbHandle->from('spliceTasksActionDetails');
		$this->dbHandle->where('requestTaskId',$requestTaskId);
		$this->dbHandle->where('status',$status);
		$this->dbHandle->order_by('id','desc');
		$this->dbHandle->limit(1,1);
		$result = $this->dbHandle->get()->result_array();

		//echo $this->dbHandle->last_query();die;
		
		return $result;
	}

	public function getOtherTeamLastUserId($requestTaskId,$level){
		$this->initiateModel();

		$this->dbHandle->select('assignee,actionTakenBy');
		$this->dbHandle->from('spliceTasksActionDetails');
		$this->dbHandle->where('requestTaskId',$requestTaskId);
		$this->dbHandle->where('level !=',$level);
		$this->dbHandle->where('status','doneAndReviewed');
		$this->dbHandle->order_by('id','desc');
		$this->dbHandle->limit(1,0);
		$result = $this->dbHandle->get()->result_array();

		//echo $this->dbHandle->last_query();die;
		
		return $result;
	}

	public function getTaskAssignedToUsers($userIds){
		if(!(is_array($userIds) && count($userIds))){
			return array();
		}
		$this->initiateModel();
		$this->dbHandle->select('distinct(requestTaskId)as requestTaskId');
		$this->dbHandle->from('spliceTasksActionDetails');
		$this->dbHandle->where_in('assignee',$userIds);
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		return $result;
	}

	public function getTaskAssignedToUsersForPushBack($userIds){
		if(!(is_array($userIds) && count($userIds))){
			return array();
		}
		$this->initiateModel();
		$this->dbHandle->select('distinct(taskActionDetailId)as taskActionId');
		$this->dbHandle->from('spliceTasksPushedBackAndCommentsDetails');
		$this->dbHandle->where_in('assignee',$userIds);
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		return $result;
	}

	public function getTaskIdsForUsers($userIds,$taskActionIds = array()){
		if(!(is_array($userIds) && count($userIds))){
			return array();
		}

		$this->initiateModel();
		$this->dbHandle->select('distinct(requestTaskId)');
		$this->dbHandle->from('spliceTasksActionDetails');

		$this->dbHandle->where_in('assignee',$userIds);
		$this->dbHandle->or_where_in('assignedBy',$userIds);
		$this->dbHandle->or_where_in('actionTakenBy',$userIds);
		if(is_array($taskActionIds) && count($taskActionIds)){
			$this->dbHandle->or_where_in('id',$taskActionIds);
		}
		
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		
		return $result;
	}

	public function checkIfUserPerformMainActionOnTask($requestTaskId,$userIds){
		$this->initiateModel();
		$this->dbHandle->select('distinct(requestTaskId)');
		$this->dbHandle->from('spliceTasksActionDetails');
		$userIds = implode(',',$userIds);
		$this->dbHandle->where('requestTaskId',$requestTaskId);
		$this->dbHandle->where('(assignee in ('.$userIds.') or assignedBy in ('.$userIds.') or actionTakenBy in ('.$userIds.') )');
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		
		return $result;
	}

	public function getTaskIdsForInputStatus($statusArray,$userIds){
		if(!(is_array($userIds) && count($userIds))){
			return array();
		}
		$this->initiateModel();
		$this->dbHandle->select('');
		$this->dbHandle->from('spliceTasksActionDetails');
		$this->dbHandle->where_in('assignee',$userIds);
		//$this->dbHandle->where_in('status',$statusArray);
		$this->dbHandle->group_by('requestTaskId');
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		
		return $result;
	}

	public function getTaskActionIdsForTaskId($requestTaskId){
		$this->initiateModel();
		$this->dbHandle->select('id');
		$this->dbHandle->from('spliceTasksActionDetails');
		$this->dbHandle->where('requestTaskId',$requestTaskId);
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		
		return $result;
	}

	public function updateOtherActionTakenField($id,$data){
		$this->initiateModel('write');
		$this->dbHandle->where('id',$id);
		$this->dbHandle->update('spliceTasksActionDetails',$data);
	}

	public function updateLastRowOfTasksActionDetails($data,$level,$requestTaskId){
		//_p($data);_p($level);_p($requestTaskId);die;
		$this->initiateModel('write');
		$this->dbHandle->where('level',$level);
		$this->dbHandle->where('requestTaskId',$requestTaskId);
		$this->dbHandle->update('spliceTasksActionDetails',$data);
		//echo $this->dbHandle->last_query();die;
		
		return $level;
	}

	public function insertNewRowTocommentDetails($data){
		$this->initiateModel('write');
		$this->dbHandle->insert('spliceTasksPushedBackAndCommentsDetails',$data);
		
		return $this->dbHandle->insert_id();
	}

	public function getPushBackAndCommentDetails($taskActionDetailId){
		if(!(is_array($taskActionDetailId) && count($taskActionDetailId))){
			return array();
		}
		$this->initiateModel();

		$this->dbHandle->select('*');
		$this->dbHandle->from('spliceTasksPushedBackAndCommentsDetails');
		$this->dbHandle->where_in('taskActionDetailId',$taskActionDetailId);
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		
		return $result;
	}

	public function getLastAssignedById($taskActionDetailId){
		$this->initiateModel();

		$this->dbHandle->select('assignedBy,actionTakenBy');
		$this->dbHandle->from('spliceTasksPushedBackAndCommentsDetails');
		$this->dbHandle->where('taskActionDetailId',$taskActionDetailId);		
		$this->dbHandle->where('status','pushedBack');
		$this->dbHandle->order_by('id','desc');
		$this->dbHandle->limit(1,0);
		$result = $this->dbHandle->get()->result_array();
		
		//echo $this->dbHandle->last_query();die;
		return $result;
	}

	public function getLastActionTakenUserIdForForwardAction($taskActionDetailId){
		$this->initiateModel();

		$this->dbHandle->select('assignee,actionTakenBy,assignedBy');
		$this->dbHandle->from('spliceTasksPushedBackAndCommentsDetails');
		$this->dbHandle->where('taskActionDetailId',$taskActionDetailId);		
		$this->dbHandle->where('status','forwarded');
		$this->dbHandle->order_by('id','desc');
		$this->dbHandle->limit(1,0);
		$result = $this->dbHandle->get()->result_array();
		
		//echo $this->dbHandle->last_query();die;
		return $result;
	}

	public function getTaskActionIdsForUsers($userIds){
		if(!(is_array($userIds) && count($userIds))){
			return array();
		}
		$this->initiateModel();
		$this->dbHandle->select('distinct(taskActionDetailId) as taskActionDetailId');
		$this->dbHandle->from('spliceTasksPushedBackAndCommentsDetails');
		$this->dbHandle->where_in('assignee',$userIds);
		$this->dbHandle->or_where_in('assignedBy',$userIds);
		$this->dbHandle->or_where_in('actionTakenBy',$userIds);
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		
		return $result;
	}

	public function checkIfUserPerformOtherActionOnTask($taskActionIds,$userIds){
		if(!(is_array($taskActionIds) && count($taskActionIds))){
			return array();
		}

		if(!(is_array($userIds) && count($userIds))){
			return array();
		}

		$userIds = implode(',',$userIds);
		$this->initiateModel();
		$this->dbHandle->select('id');
		$this->dbHandle->from('spliceTasksPushedBackAndCommentsDetails');
		$this->dbHandle->where_in('taskActionDetailId',$taskActionIds);
		$this->dbHandle->where('(assignee in ('.$userIds.') or assignedBy in ('.$userIds.') or actionTakenBy in ('.$userIds.') )');
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		
		return $result;	
	}
	
	public function getTaskAttributes($requestTaskId){
		$this->initiateModel();

		$this->dbHandle->select('*');
		$this->dbHandle->from('spliceRequestTaskAttributes');
		$this->dbHandle->where('requestTaskId',$requestTaskId);
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		
		return $result;		
	}

	public function insertPushBackDetails($inputArray){
		$this->initiateModel('write');
		//_p($inputArray);die;
		$this->dbHandle->insert('spliceTasksPushedBackDetails',$inputArray);
		//echo $this->dbHandle->last_query();die;
		
		return $this->dbHandle->insert_id();	
	}

	public function checkIfUserIdForTaskIsAlreadyExist($inputArray){
		//_p($inputArray);die;
		$this->initiateModel();
		$this->dbHandle->select('id');
		$this->dbHandle->from('TATMatchingDetails');
		$this->dbHandle->where('requestTaskId',$inputArray['requestTaskId']);
		$this->dbHandle->where('assignedUserId',$inputArray['assignedUserId']);
		$result = $this->dbHandle->get()->result_array();
		//echo	$this->dbHandle->last_query();die;
		
		return $result;
	}

	public function insertNewRowForUserInTATMatchingDetails($inputArray){
		$this->initiateModel('write');
		$this->dbHandle->insert('TATMatchingDetails',$inputArray);
		
		return $this->dbHandle->insert_id();
	}

	public function updateRowForUserInTATMatchingDetails($id,$data){
		$this->initiateModel('write');
		$this->dbHandle->where('id',$id);		
		$this->dbHandle->update('TATMatchingDetails',$data);
		
		//echo $this->dbHandle->last_query();die;
	}

	public function getTATExpiredDetails($ids,$requestType='task'){		
		$this->initiateModel();
		$this->dbHandle->select('distinct(requestTaskId) as requestTaskId');
		if($requestType == 'task'){
			$this->dbHandle->where_in('assignedUserId',$ids);	
		}else{
			if(count($ids) > 0){
				$this->dbHandle->where_in('requestId',$ids);
			}
		}
		$this->dbHandle->from('TATMatchingDetails');
		
		$this->dbHandle->where('TATExpired','1');
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		
		return $result;
	}

	public function insertOrUpdateTATMatchingDetailsTable($insertArray){
		$this->initiateModel('write');
		$insertValue = "insert into TATMatchingDetails (requestId,requestTaskId,assignedUserId,TATExpired) values ";
		foreach ($insertArray as $key => $value) {
			$sql = $insertValue.'(?,?,?,"1" ) ON DUPLICATE KEY UPDATE TATExpired="1"';
			$this->dbHandle->query($sql,array($value[0],$value[1],$value[2]));
			$insertOrUpdateId = $this->dbHandle->insert_id();
			//echo $this->dbHandle->last_query();echo '--------:'.$insertOrUpdateId.'<br>';
		}
	}

	public function insertOrUpdatePendingCampaignRequest($inputData){
		$this->initiateModel('write');
		$sql = "insert into splicePendingCampaignRequest (requestId,requestTaskId,isPendingForCampaignActivation) values (?,?,'1' ) ON DUPLICATE KEY UPDATE isPendingForCampaignActivation = '1'";
		$this->dbHandle->query($sql,array($inputData['requestId'],$inputData['requestTaskId']));
	}

	public function getTaskIdsForCurrentAssignee($userIds){
		//_p($userIds);die;
		if(!(is_array($userIds) && count($userIds))){
			return '';
		}
		$status = array('clientApprovedAndClosed','cancel');
		$this->initiateModel();
		$this->dbHandle->select('id');
		$this->dbHandle->from('spliceRequestTasks');
		$this->dbHandle->where_in('assignee',$userIds);
		$this->dbHandle->where_not_in('currentStatus',$status);
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		return $result;
	}

	public function getTaskIdsForAssignedUserForInputFilter($taskIds,$filter){
		if(!(is_array($taskIds) && count($taskIds))){
			return '';
		}
		//_p($taskIds);_p($filter);die;
		$this->initiateModel();
		$this->dbHandle->select('srt.id as id');
		$this->dbHandle->from('spliceRequestTasks srt');
		$this->dbHandle->where_in('srt.id',$taskIds);

		if($filter){
			$statusForPendingAssignedTask = array('pushedBack','markedDone','inProgress','clientApprovedAndCreateHTML');
		}

		switch ($filter) {
			case 'pendingAssignedTask':
				$this->dbHandle->where_in('srt.currentStatus',$statusForPendingAssignedTask);
				break;

			case 'assignedTaskTATToday':
				$this->dbHandle->where_in('srt.currentStatus',$statusForPendingAssignedTask);
				$todayDate = date('Y-m-d');
				$this->dbHandle->where('srt.TATDate >=',$todayDate.' 00:00:00');
				$this->dbHandle->where('srt.TATDate <=',$todayDate.' 23:59:59');
				break;

			case 'assignedTaskTATTomorrow':
				$this->dbHandle->where_in('srt.currentStatus',$statusForPendingAssignedTask);
				$tomorrowDate = date('Y-m-d',strtotime(date('Y-m-d') . "+1 days"));
				$this->dbHandle->where('srt.TATDate >=',$tomorrowDate.' 00:00:00');
				$this->dbHandle->where('srt.TATDate <=',$tomorrowDate.' 23:59:59');
				break;

			default:
				# code...
				break;
		}
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		//_p($result[0]['count']);die;
		return $result;
	}

	public function checkIfTaskIdIsAlreadyExist($requestTaskId){
		$this->initiateModel();
		$this->dbHandle->select('*');
		$this->dbHandle->from('splicePendingCampaignRequest');
		$this->dbHandle->where('requestTaskId',$requestTaskId);
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query(); die;
		return $result;
	}

	public function updateIsPendingForCampaignActivation($requestTaskId){
		$this->initiateModel('write');
		$sql = "update splicePendingCampaignRequest set isPendingForCampaignActivation = '0' where requestTaskId = ?";
		$this->dbHandle->query($sql,array($requestTaskId));
	}

	public function filterCampaignTaskId($taskIds){
		if(!(is_array($taskIds) && count($taskIds))){
			return '';
		}
		$this->initiateModel();
		$this->dbHandle->select('id');
		$this->dbHandle->from('spliceRequestTasks');
		$this->dbHandle->where_in('id',$taskIds);
		$this->dbHandle->where('taskCategory','Campaign Activation');
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query(); die;
		
		return $result;
	}
}
