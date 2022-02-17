<?php

class UserProfileModelDesktop extends MY_Model{
	
	private $dbHandle;

	function __construct()
    {
        parent::__construct('User');
    }

	private function initiateModel($operation = 'read'){

		if($operation=='read'){
			$this->dbHandle = $this->getReadHandle();
		}
		else{
        	$this->dbHandle = $this->getWriteHandle();
		} 
	}

	public function getUserMyProfile($userId, $groupId){
		$this->initiateModel();

		// $sql = "select fieldId from userProfileGroupFieldMapping where status='live'";

		// if($groupId != 'all'){
		// 	$sql .= " and groupId = '$groupId' ";
		// }

		// $result = $this->dbHandle->query($sql,array($userId))->result_array();

		$this->dbHandle->select('fieldId');
		if($groupId != 'all'){
			$this->dbHandle->where(array('status'=>'live','groupId'=>$groupId));
		} else {
			$this->dbHandle->where(array('status'=>'live'));
		}
		$query = $this->dbHandle->get('userProfileGroupFieldMapping');
		if($query->num_rows() > 0) {
			$result = $query->result_array();
		}

		return $result;
	}

	public function getUserPublicProfile($userId, $groupId,$masterPrivateFieldIds){
		$this->initiateModel();

		$sql = "select uMap.fieldId from userProfileGroupFieldMapping uMap 
				left join tUserDataPrivacySettings tPri on tPri.fieldId = uMap.fieldId and tPri.status='live' and tPri.userId=?
				where uMap.status='live'  and tPri.id is null";

		$inputData = array();
		$inputData[] = $userId;
		if(!empty($masterPrivateFieldIds)){
			$sql .= " and uMap.fieldId NOT IN (?)";
			$inputData[] = $masterPrivateFieldIds;
		}

		if($groupId != 'all'){
			$sql .= " and uMap.groupId = ? ";
			$inputData[] = $groupId;
		}

		$result = $this->dbHandle->query($sql,$inputData)->result_array();
		return $result;
	}

	public function getUserFlagInfo($userId){
		$this->initiateModel();

		$sql = "select b.unsubscribe from tuser a, tuserflag b where a.userid = b.userId AND a.userid = ? ";

		$result = $this->dbHandle->query($sql,array($userId))->result_array();
		return $result;
	}

	public function setUserFieldPrivate($userId,$fieldIds=array()){
		if(empty($fieldIds)){
			return;
		}

		$this->initiateModel('write');

		$inputData = array();
		$sql ="insert into tUserDataPrivacySettings (userId,fieldId,status) VALUES ";	
		foreach ($fieldIds as $fieldId) {
			$sql .="(?,?,'live'),";
			$inputData[] = $userId;
			$inputData[] = $fieldId;
		}

		$sql = substr($sql, 0,-1);

		return $this->dbHandle->query($sql,$inputData);
	}

	public function getUserPointsLevel($userId){
		$this->initiateModel('read');

		$sql = "select levelId from userpointsystembymodule where modulename = 'ANA' and userId = ? ";
		$result = $this->dbHandle->query($sql,array($userId))->result_array();

		return $result[0]['levelId'];
	}

	public function setUserFieldPublic($userId,$fieldIds=array()){
		
		if(empty($fieldIds)){
			return;
		}

		$this->initiateModel('write');
		$sql ="update tUserDataPrivacySettings set status='history' where userId  =?  and fieldId IN (?)";
		return $this->dbHandle->query($sql,array($userId,$fieldIds));
	}

	public function getUserCityLocaityData($locality){
		$this->initiateModel('read');

		$sql = "select lcm.localityName as locality, cct.city_name as city from localityCityMapping lcm left join countryCityTable cct on cct.city_id= lcm.cityId where lcm.localityId=  ? ";
		return $this->dbHandle->query($sql,array($locality))->result_array();
	}

	public function getUserCityData($city){
		$this->initiateModel('read');

		$sql = "select city_name as city from countryCityTable where city_id= ?";
		return $this->dbHandle->query($sql,array($city))->result_array();
	}

	public function getCountryName($countryId){
		$this->initiateModel('read');
		
		$sql = "select name as country from countryTable where countryId = ?";
		$result = $this->dbHandle->query($sql,array($countryId))->result_array();
		return $result[0]['country'];
	}

	public function getDesiredCourseDetailsByDesiredCourseId ($desiredCourseId = '', $prefId = '', $extraFlag = '') {	
		$this->initiateModel('read');
		$inputData = '';
	    if($extraFlag == 'testprep') {
			$queryCmdx = "select blogTable.blogid, acronym as CourseName from blogTable,tUserPref_testprep_mapping where tUserPref_testprep_mapping.blogid = blogTable.blogid AND blogTable.status = 'live' AND tUserPref_testprep_mapping.prefid=?";
			$inputData = $prefId;
	    } else {
			$queryCmdx = "select tCourseSpecializationMapping.CourseName, categoryBoardTable.boardId, categoryBoardTable.name as CategoryName from tCourseSpecializationMapping left join categoryBoardTable on boardId=CategoryId where SpecializationId=?";
			$inputData = $desiredCourseId;
	    }
		    
		$query = $this->dbHandle->query($queryCmdx,array($inputData));
		
		$row = $query->row_array();
		    
		$userData = array();
		$userData['courseName'] = $row['CourseName'];
		if($extraFlag != 'testprep') {
			$userData['categoryName'] = $row['CategoryName'];
			$userData['categoryId'] = $row['boardId'];
			$userData['desiredCourseId'] = $desiredCourseId;
		} else {
			$userData['desiredCourseId'] = $row['blogid'];
			$userData['categoryId'] = 14;
			$userData['categoryName'] = 'Test Preparation';

		}

		return $userData;
	}

	function getUserPrivateFields($userId){
		$this->initiateModel('write');

		$sql = "select fieldId from tUserDataPrivacySettings where userId =? and status ='live'";

		$result =  $this->dbHandle->query($sql,array($userId))->result_array();

		return $result;
	}

	function getMasterPrivacyFields(){
		$this->initiateModel('read');

		$sql = "select fieldId from userProfileGroupFieldMapping where  status ='live'";

		$result =  $this->dbHandle->query($sql)->result_array();

		return $result;
	}

	function checkIfPrivate($fieldId,$userid){
		$this->initiateModel('read');

		$sql = "select id from tUserDataPrivacySettings where  status ='live' and userId=? and fieldId=?";

		$result =  $this->dbHandle->query($sql,array($userid,$fieldId))->result_array();

		return $result;
	}

	public function markPreviousPrivacyHistory($userId){
		$this->initiateModel('write');

		$sql = "update tUserDataPrivacySettings set status = 'history' where userId = ? and
					 (fieldId LIKE '%EmployerworkExp%' OR fieldId LIKE '%DesignationworkExp%' OR fieldId LIKE '%DepartmentworkExp%'
					  OR fieldId LIKE '%CurrentJobworkExp%')";

		$this->dbHandle->query($sql,array($userId));

	}

	public function createPrivacyWorkEx($newPrivacy,$userId){
		$this->initiateModel('write');

		$sql = "INSERT IGNORE INTO tUserDataPrivacySettings(userId,fieldId,status) 
					VALUES";

		$workField = array('DepartmentworkExp','EmployerworkExp','DesignationworkExp','CurrentJobworkExp');
		$inputData = array();

		foreach ($newPrivacy as $key => $value) {
			$level = $key +1;

			foreach ($workField as $fieldId) {

				$sql .= "(?,?,'live'),";		
				$inputData[] = $userId;
				$inputData[] = $fieldId.$level;
			}	
		}	

		$sql = substr($sql, 0,-1);

		$this->dbHandle->query($sql,$inputData);		
	}

	public function getUserVistedProfilePage($from, $to){
		$this->initiateModel('read');

		$sql = 'SELECT DISTINCT userid FROM userActionTracking WHERE type = "userProfile" AND trackingTime > ? AND trackingTime < ? AND userid != 0';
		$result = $this->dbHandle->query($sql,array($from,$to))->result();
		
		$data = array();
		foreach($result as $key=>$value){
			$data[] = $value->userid;
		}
		return $data;
	}

	public function getuserPersonalInfo($userIds = array()){
		if(count($userIds) < 1){
			return array();
		}

		$this->initiateModel('read');

		$sql = "select t.userid as UserId, t.email as Email, t.isdCode as ISDCode, mobile as Mobile, 
				t.firstname, t.lastname, ct.name as Country, cct.city_name as City,lcm.localityName as Locality, 
				t.dateofbirth, tuai.aboutMe as AboutMe, tuai.bio as Bio, t.avtarimageurl as Photo
				from tuser t 
				inner join tuserflag tuf on t.userid = tuf.userid
				left join tUserAdditionalInfo tuai on t.userid = tuai.userid
				left join countryTable ct on t.country = ct.countryId 
                left join countryCityTable cct on t.city = cct.city_id
                left join localityCityMapping lcm on t.Locality = lcm.localityId
				where t.userid in (?) ";

		$result = $this->dbHandle->query($sql,array($userIds))->result_array();
		$data = array();
		foreach($result as $key=>$value){
			$data[$value['UserId']] = $value;
		}

		return $data;
	}

	public function getUserWorkExData($userIds = array()){
		if(empty($userIds)){
			return array();
		}

		$this->initiateModel('read');

		$sql = "SELECT t.userid as UserId, tw.employer, tw.designation as Designation, tw.department, tw.currentJob, t.experience as TotalWorkEx 
				FROM tuser t 
				left join tUserWorkExp tw on t.userid = tw.userId 
				WHERE tw.userid IN (?) group by t.userid";
		$result = $this->dbHandle->query($sql,array($userIds))->result_array();
		$data = array();
		foreach($result as $key=>$value){
			$data[$value['UserId']] = $value;
		}
		
		return $data;
	}

	public function getUserEducationData($userIds = array(), $fieldLevelMapping = array()){
		if(empty($userIds)){
			return array();
		}

		$this->initiateModel('read');

		$sql = 'SELECT UserId, name, Level, instituteName, Specialization, board, subjects, Marks , CourseCompletionDate
				FROM tUserEducation WHERE UserId IN (?) order by level';
		$result = $this->dbHandle->query($sql,array($userIds))->result_array();
		$data = array();
		foreach($result as $outerkey=>$outervalue){
			foreach ($outervalue as $key => $value) {
				$data[$outervalue['UserId']][$fieldLevelMapping[$outervalue['Level']][$key]] = $value;
			}
		}

		return $data;			
	}

	public function getTUserPrefExtraFlag($userIds = array()){
		if(empty($userIds)){
			return array();
		}

		$this->initiateModel('read');

		$sql = 'SELECT  userId, ExtraFlag from tUserPref WHERE userId IN (?)';
		$result = $this->dbHandle->query($sql,array($userIds))->result_array();
		
		$data = array();
		foreach($result as $key=>$value){
			$value['ExtraFlag'] = $value['ExtraFlag'] == '' ? 'national': $value['ExtraFlag'];
			$data[$value['ExtraFlag']][] =  $value['userId'];
		}
	
		return $data;
	}

	public function getUserExamsDetails($userIds = array()){
		if(empty($userIds)){
			return array();
		}

		$this->initiateModel('read');
		
		//$sql = "SELECT userId, Name, Marks as examMarks from tUserEducation where Level = 'Competitive exam' AND Name NOT IN ('TOEFL','IELTS','PTE','GRE','GMAT','SAT','CAEL','MELAB','CAE') AND userid in (".implode(', ', $userIds).")";
		$sql = "SELECT userId, Name, Marks as examMarks from tUserEducation where Level = 'Competitive exam' AND userid in (?)";
		$result = $this->dbHandle->query($sql,array($userIds))->result_array();
		foreach($result as $key=>$value){
			$data[$value['userId']]['exam'] = $value['Name'];
			if(isset($value['examMarks']) && $value['examMarks'] > 0){
				$data[$value['userId']]['examMarks'] = $value['examMarks'];
				$data[$value['userId']]['exam'] = $value['Name']; // If marks field exist
			}
		}
		
		return $data;
	}

	public function getUsersTestPrepInterest($userIds){
		if(count($userIds) < 1){
			return array();
		}

		$this->initiateModel('read');

		$sql = "select tUserPref.userId, acronym as DesiredCourse 
				from blogTable,tUserPref_testprep_mapping, tUserPref 
				where tUserPref_testprep_mapping.blogid = blogTable.blogid AND blogTable.status = 'live' 
				AND tUserPref_testprep_mapping.prefid=tUserPref.prefId AND tUserPref.userId IN (?)";
		$result = $this->dbHandle->query($sql,array($userIds))->result_array();
		$data = array();
		foreach ($result as $key => $value) {
			$data[$value['userId']] = $value;
			$data[$value['userId']]['Interest'] = 'Test Preparation';
		}

		return $data;

	}

	public function getUsersNationalCourseAndInterest($userIds){
		if(count($userIds) < 1){
			return array();
		}
		$this->initiateModel('read');

		$sql = "select  tup.userId, tcs.CourseName as DesiredCourse, cbt.name as Interest, tup.ExtraFlag
				from tUserPref tup
				left join tCourseSpecializationMapping tcs on tup.DesiredCourse = tcs.SpecializationId
				left join categoryBoardTable cbt on tcs.CategoryId = cbt.boardId
				where tup.userId in (?)";
		$result = $this->dbHandle->query($sql,array($userIds))->result_array();
		$data = array();
		foreach ($result as $key => $value) {
			$data[$value['userId']] = $value;
		}
		return $data;
	}

	public function getAbroadUserDetails($userIds){
		if(count($userIds) < 1){
			return array();
		}

		$this->initiateModel('read');

		$sql = "SELECT tup.userId, tup.UserFundsBank, tup.UserFundsBank, tup.UserFundsOwn, tup.UserFundsNone, program_budget as Budget, 
				tuai.extracurricular, tuai.specialConsiderations, tuai.preferences, tuai.studentEmail as studentEmail, timeofstart as planToGo
				FROM tUserPref tup 
				LEFT JOIN tUserAdditionalInfo tuai ON tup.userId = tuai.userId 
				WHERE tup.userId IN (?)";
		$result = $this->dbHandle->query($sql,array($userIds))->result_array();
		$data = array();
		foreach($result as $key=>$value){
			$data[$value['userId']] = $value;
			$data[$value['userId']]['abroadEducationalDetails'] = 'TRUE';
			if($value['UserFundsBank'] == 'yes'){
				$data[$value['userId']]['fundingSource'] = 'Bank';
			}else if($value['UserFundsNone'] == 'yes'){
				$data[$value['userId']]['fundingSource'] = 'Other';
			}else{
				$data[$value['userId']]['fundingSource'] = 'Self';
			}
		}
		return $data;
	}

	public function getUserDesiredCountries($userIds){
		if(count($userIds) < 1){
			return array();
		}

		$this->initiateModel('read');
		$sql = "SELECT tulf.UserId, GROUP_CONCAT(ct.name) AS DesiredCountries FROM tUserLocationPref tulf LEFT JOIN countryTable ct ON ct.countryId = tulf.CountryId
				WHERE tulf.UserId IN (?) GROUP BY tulf.UserId";
		$result = $this->dbHandle->query($sql,array($userIds))->result_array();
		$data = array();
		foreach($result as $key=>$value){
			$data[$value['UserId']]['DesiredCountries'] = $value['DesiredCountries'];
		}
		return $data;
	}

	public function fetchExamDetailsFromDB($userId) {
	    $this->initiateModel('read');
	    
	    if(empty($userId)) {
	    	return array();
	    }
	    $sql = "SELECT Name,Marks, MarksType from tUserEducation where userid = ? and Level = 'Competitive exam'";	    
	    $query = $this->dbHandle->query($sql, array($userId));	    
	    $userData = array();
		foreach($query->result_array() as $row) {
			$userData['Name'][] = $row['Name'];		    
			$userData['Marks'][] = $row['Marks'];		    
			$userData['MarksType'][] = $row['MarksType'];		    
	    }
	    
	    return $userData;
	}

	/**
	* Function to get user level data from userpointvaluebymodule table
	* @param : userId
	* @return : user level data (levelId, points and level name)
	*/
	public function getUserLevelData($userId){

		if(empty($userId)){
			return;
		}
		
		$this->initiateModel('read');

		$sql = "select userpointvaluebymodule as points, levelId as ".
				"userLevel, levelName from userpointsystembymodule where ".
				"modulename = 'ANA' and userId = ? ";
		$result = $this->dbHandle->query($sql,array($userId))->result_array();

		return $result[0];
	}

	public function getCountOfUsersIamFollowing($userId){
		if(empty($userId)){
			return;
		}
		
		$this->initiateModel('read');
		$sql = "select count(*) as count from tuserFollowTable where userId = ? and ". 
				"entityType = 'user' and status = 'follow'";

		$result = $this->dbHandle->query($sql,$userId)->result_array();
		return $result[0]['count'];
	}

	public function getFlagIfIAmFollowing($entityId, $loggedInUserId){
		if(empty($loggedInUserId) || empty($entityId)){
			return;
		}
		
		$this->initiateModel('read');
		$sql = "select status from tuserFollowTable where userId=? and entityId=? and entityType = 'user';";

		$result = $this->dbHandle->query($sql,array($loggedInUserId,$entityId))->result_array();
		return $result;
	}

	public function getUserUnsubscribeMapping($userId){
		if(empty($userId)){
			return;
		}
		$this->initiateModel('read');

		$this->dbHandle->select('userUnsubMap.unsubscribe_category');
    	$this->dbHandle->from('user_unsubscribe_mapping userUnsubMap');
    	$this->dbHandle->where('userUnsubMap.user_id',$userId);
    	$this->dbHandle->where('userUnsubMap.status','live');
    	$result = $this->dbHandle->get()->result_array();
    	return $result;

	}

	public function userUnsubscribeMapping($userId,$unsubscribeStatus,$unsubscribeCatId){
		if(empty($userId) || empty($unsubscribeCatId)){
			return;
		}
		$this->initiateModel('write');
		$updateSql ="update user_unsubscribe_mapping set status='history', modified_on = now() where user_id = ?  and unsubscribe_category = ? and status = ?";
		$this->dbHandle->query($updateSql,array($userId,$unsubscribeCatId,'live'));

		if($unsubscribeStatus == 'true'){
			$sql = "insert into user_unsubscribe_mapping (user_id,unsubscribe_category,status) values (?,?,?) ON DUPLICATE KEY UPDATE added_on = now()";
			$result = $this->dbHandle->query($sql,array($userId,$unsubscribeCatId,'live'));
		}

		return true;
	}

	public function saveUserExamProfile($userId, $userExamDetails){

		$this->initiateModel('write');
		$sql = "insert into tUserEducation (UserId, Name, Level, Marks, MarksType, examGroupId, submitDate) values ";

		foreach ($userExamDetails as $index => $examDetail) {
			$sql .= "(".$userId.",'".$examDetail['examName']."','Competitive exam','".$examDetail['examScore']."','".$examDetail['examScoreType']."',".$examDetail['examGroupId'].", now()),";
		}

		$sql = substr($sql, 0,-1);
		$this->dbHandle->query($sql);

		return $this->dbHandle->insert_id();
	}

	public function deleteUserExamProfile($userId, $examGroupId){
		$this->initiateModel('write');

		if($userId<0){
			return false;
		}

		$sql ="delete from tUserEducation where userId=? and examGroupId in (?) and Level ='Competitive exam'";
		$this->dbHandle->query($sql,array($userId, $examGroupId));
	}

	public function checkValidExamProfile($examGroupId, $userId){
		$this->initiateModel('write');

		$sql 	 = "select  examGroupId from tUserEducation where examGroupId in (?) and userId=? and Level ='Competitive exam' and marks>0";
		$result  = $this->dbHandle->query($sql,array($examGroupId, $userId))->result_array();
		
		return $result;
	}
}
 ?>
