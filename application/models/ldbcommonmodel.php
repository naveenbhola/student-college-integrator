<?php
class LDBCommonModel extends MY_Model
{
    /**
     * Variable for DB Handling
     * @var object
     */
    private $dbHandle = '';

    /**
     * Constructor Function
     */
    public function __construct()
    {
        parent::__construct('User');
    }

    /**
     * Function to initiate the Model
     *
     * @param string $operation
     *
     */
    private function initiateModel($operation = 'read')
    {
        if ($operation == 'read' && 0) {
            $this->dbHandle = $this->getReadHandle();
        } else {
            $this->dbHandle = $this->getWriteHandle();
        }
    }

	public function getUserIdOfNonTestUserByMobileNumber($mobiles = array()){
		if(empty($mobiles)){
			return array();
		}

		$this->initiateModel('read');
		$sql = 'Select t.userid from tuser t inner join tuserflag tuf on t.userid=tuf.userid where tuf.isTestUser="NO" and t.mobile in (?)';
		return $this->dbHandle->query($sql,array($mobiles))->result_array();
	}

    public function updateTestFlagByUserId($userIds){
        if(empty($userIds)){
            return array();
        }

        $this->initiateModel('write');
        $sql = 'Update tuserflag set isTestUser="YES" where userid in (?)';
        return $this->dbHandle->query($sql,array($userIds));
    }

    public function batchInsert($table, $data = array()){
        if(empty($table) || empty($data)){
            return array();
        }

        $this->initiateModel('write');
        $this->dbHandle->insert_batch($table, $data); 
    }

    public function getUsersByDesiredCourse($desiredCourse = array()){
        if(empty($desiredCourse)){
            return array();
        }

        $this->initiateModel('read');
        $sql = "SELECT DISTINCT UserId FROM tUserPref WHERE DesiredCourse IN (?) AND (ExtraFlag='undecided' OR ExtraFlag is NULL) and UserId > 0 order by UserId ";
        return $this->dbHandle->query($sql,array($desiredCourse))->result_array();
    }

    public function getDCFromMappingTable(){
        $this->initiateModel('read');
        $sql = 'SELECT DISTINCT oldspecializationid FROM base_entity_mapping WHERE (education_type!=0 OR delivery_method != 0) AND oldspecializationid !=0';
        return $this->dbHandle->query($sql)->result_array();
    }

    public function getInterestidByUserid($userid = array()){
        if(empty($userid)){
            return array();
        }

        $this->initiateModel('read');
        $sql = "SELECT interestId FROM tUserInterest WHERE userid IN (?) and status!='history'";
        return $this->dbHandle->query($sql,array($userid))->result_array();
    }

    public function changeUserInterestStatus($interestIds = array(), $status = "history"){
        if(empty($interestIds)){
            return false;
        }

        $sql = 'UPDATE tUserInterest SET status=? WHERE interestId IN (?)';
        $this->dbHandle->query($sql, array($status,$interestIds));

        $sql = 'UPDATE tUserCourseSpecialization SET status=? WHERE interestId IN (?)';
        $this->dbHandle->query($sql, array($status,$interestIds));

        $sql = 'UPDATE tUserAttributes SET status=? WHERE interestId IN (?)';
        $this->dbHandle->query($sql, array($status,$interestIds));

        return true;
    }

    public function checkuserInterestStatus($userid = array()){
        if(empty($userid)){
            return array();
        }

        $this->initiateModel('read');
        $sql = "SELECT count(interestId) FROM tUserInterest WHERE userid IN (".implode(", ", $userid).") and status!='history'";
        return $this->dbHandle->query($sql)->result_array();
    }

    public function getDuplipcateDisplayName(){
        $this->initiateModel('read');
        $sql = "select displayname, COUNT(*) as displayCount from tuser group by displayname  having displayCount > 1";
        return $this->dbHandle->query($sql)->result_array();
    }

    public function getUserIdsByDisplayName($displayname){
        $this->initiateModel('read');
        $sql = "select userId,firstname from tuser where displayname = ?";
        return $this->dbHandle->query($sql,array($displayname))->result_array();        
    }

    public function getUserIdsOfIncorrectDisplayName(){
        $this->initiateModel('read');
        $sql = "SELECT userId,displayname FROM tuser WHERE  displayname not REGEXP '^[A-Za-z0-9]+$' ";
        return $this->dbHandle->query($sql)->result_array();        
    }

    function updateDisplayName($displayNameArr){
        $this->initiateModel('write');
        $this->dbHandle->trans_start();
        $this->dbHandle->update_batch('tuser', $displayNameArr, 'userId');
        $this->dbHandle->trans_complete();
        return ($this->dbHandle->trans_status() === FALSE) ? false : true;
    }

    function insertDuplicateDisplayNameUserIds($userIds){
        $this->initiateModel('write');
        $this->dbHandle->insert_batch('sanitizeDisplayName',$userIds);
        return true;
    }

    function getOldDisplayName($displayname){
        $this->initiateModel('read');
        $sql = "SELECT userId FROM sanitizeDisplayName WHERE  oldDisplayName =  ? limit 1";
        return $this->dbHandle->query($sql,array($displayname))->row_array();        
    }

    function getDisplayNameByUserId($userId){
        $this->initiateModel('read');
        $sql = "SELECT displayname FROM tuser WHERE  userid =  ?";
        $result = $this->dbHandle->query($sql,array($userId))->row_array();
        return $result['displayname'];
    }

    function getUserIdsForDisplayName(){
        $this->initiateModel('write');
        $sql = "select userId from sanitizeDisplayName where is_processed='n' limit 1000";
        $result = $this->dbHandle->query($sql)->result_array();
        return $result;
    }

    function updateStatusForDisplayName($userIds){
        $this->initiateModel('write');
        $sql = "update sanitizeDisplayName set is_processed='y' where userId in (?)";
        $this->dbHandle->query($sql,array($userIds));
    }

    function saveMCResponse($mcResponseData){
        $this->initiateModel('write');
        $this->dbHandle->insert('missed_call_response',$mcResponseData);
        $returnId   = $this->dbHandle->insert_id();        
        return $returnId;       
    }    

    function getInvalidCityUsers(){
        $this->initiateModel('read');
        $sql = " SELECT userId, City FROM tuser WHERE city REGEXP '[a-zA-Z]' ";
        $result = $this->dbHandle->query($sql)->result_array();
        return $result; 
    }

    function getAllCityDetails(){
        $this->initiateModel('read');
        $sql = " SELECT city_id, city_name FROM countryCityTable";
        $result = $this->dbHandle->query($sql)->result_array();
        return $result; 
    }

    function updateUserCityId($city_id, $user_ids){
        $this->initiateModel('write');
        $sql = "update tuser set city=? where userId in (?)";
        $this->dbHandle->query($sql,array($city_id, $user_ids));
    }
}
