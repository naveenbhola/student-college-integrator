<?php

class oafportingmodel extends MY_Model
{
	private $dbHandle = null;

	function __construct()
	{
		parent::__construct('User');
	}

	private function initiateModel($mode = "write", $module = '')
	{
		if($mode == 'read') {
			$this->dbHandle = empty($module) ? $this->getReadHandle() : $this->getReadHandleByModule($module);
		} else {
			$this->dbHandle = empty($module) ? $this->getWriteHandle() : $this->getWriteHandleByModule($module);
		}
	}

	public function getLastProcessedId($process){
		if(empty($process)){
			return false;
		}
		$this->initiateModel('read','User');
		$this->dbHandle->select('lastprocessedid');
		$this->dbHandle->from('SALeadAllocationCron');
		$this->dbHandle->where('process',$process);
		$result = $this->dbHandle->get()->result_array();

		return $result[0]['lastprocessedid'];       
    }

    public function updateLastProcessedId($lastprocessedId, $process){
    	if($lastprocessedId<1 || empty($process)){
    		return;
    	}

    	$data = array('lastprocessedid' => $lastprocessedId);
    	$this->initiateModel('write','User');
    	$this->dbHandle->where('process',$process);
    	$this->dbHandle->update('SALeadAllocationCron',$data);
    }

    public function updatePortingsProcessedId($lastprocessedId, $portingIds){
    	if(empty($portingIds)){
    		return;
    	}
    	$data = array('last_ported_id' => $lastprocessedId, 'isrun_firsttime' => 'yes');
    	$this->initiateModel('write','User');
    	$this->dbHandle->where('status','live')->where_in('id', $portingIds);
    	$this->dbHandle->update('porting_main', $data);
    }

    public function updatePortingsRunFirstTime($portingId, $isRunFirsttime){
    	$data = array('isrun_firsttime' => $isRunFirsttime);
    	$this->initiateModel('write','User');
    	$this->dbHandle->where('status','live')->where('id', $portingId);
    	$this->dbHandle->update('porting_main', $data);
    }

    public function getFormListByClientId($clientId)
    {
    	if(empty($clientId)) { 
            return array();
        }
        $this->initiateModel('read');
    	$this->dbHandle->select('formName,formId,listing_type_id');
    	$this->dbHandle->from('listings_main AS lm');
    	$this->dbHandle->join('OF_ListForms AS lf','listing_type_id = courseId');
    	$this->dbHandle->where('username',$clientId);
    	$this->dbHandle->where('listing_type','course');
    	// $this->dbHandle->where('lm.status','live');
    	// $this->dbHandle->where('lf.status','live');
    	$result = $this->dbHandle->get()->result_array();
    	return $result;

    }

    public function getEntityIds($formIds)
    {
    	if(empty($formIds))
    	{
    		return;
    	}
    	$this->initiateModel('read');
    	$this->dbHandle->select('pem.pageId,entitySetId,entitySetType');
    	$this->dbHandle->from('OF_PageMappingInForm AS pmf');
    	$this->dbHandle->join('OF_PageEntityMapping AS pem','pem.pageId=pmf.pageId');
    	$this->dbHandle->where_in('formId',$formIds);
    	$this->dbHandle->group_by('pem.pageId, entitySetId,entitySetType');
    	$result=$this->dbHandle->get()->result_array();
    	return $result;

    }
	
	public function getGroupEntityIds($groupIds)
	{
		$this->initiateModel('read');
    	$this->dbHandle->select('fieldId');
    	$this->dbHandle->from('OF_GroupList');
    	$this->dbHandle->where_in('groupId',$groupIds);
    	$result=$this->dbHandle->get()->result_array();
    	return $result;
	}	

	public function getMappingByFieldIds($fieldIds)
	{
		$this->initiateModel('read');
		$this->dbHandle->select('fieldId,name');
		$this->dbHandle->from('OF_FieldsList');
		$this->dbHandle->where_in('fieldId',$fieldIds);
		$result = $this->dbHandle->get()->result_array();
		return $result;
	}

	public function getAllPortings($portingType){
        $this->initiateModel('read','User');
        $sql = "SELECT pm.*, CONCAT(IFNULL( tu.firstname, '' ) , ' ', IFNULL( tu.lastname, '' ))  as displayname 
        		FROM `porting_main` pm, `tuser` tu
        		WHERE pm.`client_id` = tu.`userid`  and pm.`type` =? order by pm.client_id, pm.id desc";


        $query = $this->dbHandle->query($sql,array($portingType));
        $retArr = array();
        foreach($query->result() as $row) {
            $retArr[] = (array)$row;
        }
        return $retArr;
    }

    public function setCustomizedMappedFields($clientId,$customField,$data)
	{
		$this->initiateModel('write','User');
		$entity = $customField;

		$sql = "update porting_customized_fields  set status = 'history' where client_id = ? and entity_type = ? ";
		$this->dbHandle->query($sql, array($clientId, $entity));

        $this->dbHandle->insert_batch('porting_customized_fields',$data);
		
		return true;
	}

	public function getCoursesForForms($formIds)
    {
    	$this->initiateModel('read','User');
    	if(empty($formIds))
    	{
    		return;
    	}
    	$query = "select sc.course_id,sc.name from OF_ListForms as OAF inner join shiksha_courses as sc on (OAF.courseId=sc.course_id and OAF.formId in (?) and sc.status='live')";

    	$result = $this->dbHandle->query($query,array($formIds))->result_array();

    	return $result;
    }

    public function getMappedCourseName($client_id,$courseIds,$customField='oaf_course')
    {
    	$this->initiateModel('read','User');
    	if(empty($client_id) || empty($courseIds))
    	{
    		return;
    	}
    	$query = "select entity_id,entity_value from porting_customized_fields where client_id = ? and entity_id in (?) and entity_type = ? and status='live'";

    	$result = $this->dbHandle->query($query,array($client_id,$courseIds,$customField))->result_array();

    	return $result;

    }

    public function insertInPortingMain($data)
    {
    	$this->initiateModel('write','User');
    	$this->dbHandle->insert('porting_main',$data);
    	return  $this->dbHandle->insert_id();
    }

    public function insertInPortingConditions($portingConditions)
    {
    	$this->initiateModel('write','User');
    	$this->dbHandle->insert_batch('porting_conditions',$portingConditions);
    	return;
    }

    public function insertInPortingFieldMappings($portingFieldMappings)
    {
    	$this->initiateModel('write','User');
    	$this->dbHandle->insert_batch('porting_field_mappings',$portingFieldMappings);
    	return;
    }

	public function getOnlineFormResponses($lastprocessedId, $endDate){
		$this->initiateModel('read','OnlineForms');

		$sql = "SELECT id,userId,onlineFormId from OF_Response_queue where id > ? and is_processed = 'no' and `date` < ? order by id";
		$responses = $this->dbHandle->query($sql, array($lastprocessedId, $endDate))->result_array();
		return $responses;
	}

	public function getUserData($userIds){
		if(empty($userIds)){
			return array();
		}
		$sql = "SELECT userId,isTestUser from tuserflag where userId in (?)";
		$data = $this->dbHandle->query($sql, array($userIds))->result_array();

		$returnData = array();
		foreach ($data as $row) {
			$returnData[$row['userId']] = $row;
		}
		return $returnData;
	}

	public function getOnlineFormMetaData($onlineFormIds){
		if(empty($onlineFormIds)){
			return array();
		}
		$this->initiateModel('read','OnlineForms');
		$sql = "SELECT uf.onlineFormId,uf.courseId,lf.formId,lf.formName,lm.username from OF_UserForms uf join listings_main lm on lm.listing_type_id = uf.courseId and lm.listing_type = 'course' and lm.status='live' join OF_ListForms lf on lf.courseId = uf.courseId and lf.status='live'  where uf.type = ? and uf.onlineFormId in (?) and uf.formStatus = 'live'";

		$data = $this->dbHandle->query($sql, array('course', $onlineFormIds))->result_array();
		return $data;
	}

	public function getAllNewPortings(){
		$this->initiateModel('read');
		$sql = "SELECT pm.id from porting_main pm where pm.status = 'live' and pm.isrun_firsttime = 'no' and pm.type='oaf'";
		$data = $this->dbHandle->query($sql)->result_array();
		return $data;
	}

	public function getAllTestPortings(){
		$this->initiateModel('read');
		$sql = "SELECT pm.id from porting_main pm where pm.status = 'intest' and pm.type='oaf'";
		$data = $this->dbHandle->query($sql)->result_array();
		return $data;
	}

	public function getResponsesByFormIds($lastprocessedId, $lastPortedId, $formIds, $status){
		if(empty($formIds)){
			return array();
		}
		$sql = "SELECT distinct rq.id,rq.userId,rq.onlineFormId,uf.courseId as clientCourseId,lf.formId,lf.formName,lm.username as clientId
			from OF_Response_queue rq join OF_UserForms uf on uf.onlineFormId = rq.onlineFormId and rq.is_processed = 'no' and uf.formStatus = 'live'
			join listings_main lm on lm.listing_type_id = uf.courseId and lm.listing_type = 'course' and lm.status='live' 
			join OF_ListForms lf on lf.courseId = uf.courseId and lf.status='live'  and uf.type = 'course' 
			where lf.formId in (?) and rq.id > ? ";

		if($status != 'intest'){
			$sql .= ' and rq.id <= ? ';
		}
		$sql .= 'order by rq.id asc ';
		if($status == 'intest'){
			$sql .= ' limit 1 ';
		}

		$data = $this->dbHandle->query($sql, array($formIds, $lastPortedId, $lastprocessedId))->result_array();
		// _p($this->dbHandle->last_query());
		return $data;
	}

	/*public function getOnlineFormResponsesForNewPortings($lastprocessedId){
		$this->initiateModel('read','OnlineForms');
		$sql = "SELECT distinct rq.id,rq.userId,rq.onlineFormId,uf.courseId,lf.formId,lf.formName,lm.username 
			from OF_Response_queue rq join OF_UserForms uf on uf.onlineFormId = rq.onlineFormId and rq.is_processed = 'no' and uf.formStatus = 'live'
			join listings_main lm on lm.listing_type_id = uf.courseId and lm.listing_type = 'course' and lm.status='live' 
			join OF_ListForms lf on lf.courseId = uf.courseId and lf.status='live'  and uf.type = 'course' 
			join porting_conditions pc on pc.value = lf.formId and pc.status = 'live' and  pc.key = 'oafFormId'
			join porting_main pm  on pc.porting_master_id = pm.id and pm.status = 'live' and pm.isrun_firsttime = 'no' and pm.type='oaf' and rq.id <= ? order by rq.id asc";
		$data = $this->dbHandle->query($sql, array($lastprocessedId))->result_array();
		return $data;
	}*/

	public function getPortingsByFormIds($formIds){
		if(empty($formIds)){
			return array();
		}
		$this->initiateModel('read','User');

		$sql = "SELECT pc.porting_master_id,pc.value from porting_conditions pc join porting_main pm on pm.id = pc.porting_master_id and pc.key = 'oafFormId' and pc.value in (?) and pc.status='live' and pm.status = 'live' and pm.isrun_firsttime = 'yes' ";
		$data = $this->dbHandle->query($sql, array($formIds))->result_array();

		return $data;
	}

	public function getPortingMainData($portingIds, $portingMainStatus){
		if(empty($portingIds) || empty($portingMainStatus)){
			return;
		}
		$this->initiateModel('read','User');
		$sql = "SELECT id,client_id,name,type,request_type,api,data_format,data_key,xml_format,dataEncode,status,last_ported_id,isrun_firsttime,vendor_name from porting_main where type = ? and id in (?) and status in (?)";
		$data = $this->dbHandle->query($sql,array('oaf',$portingIds, $portingMainStatus))->result_array();
		return $data;
	}

	public function getPortingConditionsData($portingIds){
		if(empty($portingIds)){
			return;
		}
		$this->initiateModel('read','User');
		$sql = "SELECT `porting_master_id`,`key`, `value` FROM `porting_conditions` WHERE `porting_master_id` in (?) AND `status` = 'live'";
		$data = $this->dbHandle->query($sql, array($portingIds))->result_array();
		return $data;
	}

	public function getPortingFieldsMappingData($portingIds){
		if(empty($portingIds)){
			return;
		}
		$this->initiateModel('read','User');
		$sql = "SELECT porting_master_id,client_field_name,master_field_id,other_value from porting_field_mappings where status = 'live' and porting_master_id in (?)";
		$data = $this->dbHandle->query($sql, array($portingIds))->result_array();
		return $data;
	}

	public function getOnlineFormData($onlineFormIds){
		if(empty($onlineFormIds)){
			return;
		}
		$sql = "SELECT onlineFormId,userId,pageId,fieldId,value,isMultipleCase,fieldName from OF_FormUserData fud where onlineFormId in (?)";
		$data = $this->dbHandle->query($sql, array($onlineFormIds))->result_array();
		return $data;
	}

	public function updatePortingStatus($id, $portingMasterId, $response, $serializedData, $status){
	    $this->initiateModel('write','User');
	    $serializedData = base64_encode($serializedData);
	    $response = addslashes($response);

	    $sql = "INSERT INTO `oaf_porting_status` (`porting_master_id`,  `ported_item_id`, `response`, `sent_data`) VALUES (?, ?, ?,  ?)";
	    $this->dbHandle->query($sql, array($portingMasterId, $id, $response, $serializedData));
	}

	public function updateResponsesAsProcessed($responseIds){
		if(empty($responseIds)){
			return;
		}

		$this->initiateModel('write','User');
		$data = array('is_processed' => 'yes');
		$this->dbHandle->where_in('id', $responseIds);
		$this->dbHandle->update('OF_Response_queue',$data);
	}

	public function getCustomizedMappedField($entityId, $entityType, $clientId){
		$this->initiateModel('write','User');
		$this->dbHandle->where(array('entity_id' => $entityId, 'entity_type' => $entityType,"client_id" => $clientId, "status" => 'live'));
		$data = $this->dbHandle->get('porting_customized_fields')->row_array();
		return $data;
	}

    public function changePortingStatus($portingId,$status){
		$this->initiateModel('write','User');
		$data = array('status' => $status, 'status_modification_datetime' => date("Y-m-d H:i:s"));
		
		$this->dbHandle->where('id',$portingId);
		$this->dbHandle->update('porting_main',$data);
		return true;
	}
        public function getPortingMain($portingId)
    {
       $this->initiateModel('read','MISTracking');
        $sql = "SELECT * FROM `porting_main` WHERE `id` = ?";
        $query = $this->dbHandle->query($sql, array($portingId));
        foreach($query->result() as $row) {
            $retArr = (array)$row;
        }
        return $retArr;
    }

     public function getClientFields($portingId)
    {
        $this->initiateModel('read','MISTracking');
        $sql = "SELECT * FROM `porting_field_mappings` WHERE `status` = 'live' and `porting_master_id` = ?";
        $query = $this->dbHandle->query($sql, array($portingId));
        foreach($query->result() as $row) {
            $retArr[] = (array)$row;
        }
        return $retArr;
    }

    public function getPortingConditions($portingId)
    {
        $this->initiateModel('read','MISTracking');
        $retArr = array();
        $sql = "SELECT `key`, `value` FROM `porting_conditions` WHERE `status` = 'live' and `porting_master_id` = ?";
        $query = $this->dbHandle->query($sql, array($portingId));
        foreach($query->result() as $row) {
            $retArr[$row->key][$row->value] = true;
        }
        return $retArr;
    }
    
    public function updatePortingMain($data,$portingId)
    {
        $this->initiateModel('write','User');
        $this->dbHandle->where('id', $portingId);
        $this->dbHandle->update('porting_main',$data);
        return $portingId;
    }

    public function updatePortingConditions($data,$portingId)
    {
        $this->initiateModel('write','User');
        $this->dbHandle->where('porting_master_id', $portingId);
        $this->dbHandle->update('porting_conditions',$data);
        return $portingId;
    }


    public function updatePortingFieldMappings($data,$portingId)
    {
         $this->initiateModel('write','User');
        $this->dbHandle->where('porting_master_id', $portingId);
        $this->dbHandle->update('porting_field_mappings',$data);
        return $portingId;
    }

	public function getPortingsByClientId($clientId){
        if($clientId<=0){
        	return;
        }

        $this->initiateModel('read','User');
        $sql = "SELECT id,name FROM porting_main where type='oaf' and client_id=?";
        $result = $this->dbHandle->query($sql, array($clientId))->result_array();
        
        return $result;
    }

    public function getMisCountDataDaily($portingId, $dateFrom, $dateTo, $reportFormat){
        $this->initiateModel('read','User');
        $dateFrom = $dateFrom.' 00:00:00';
    	$dateTo = $dateTo.' 23:59:59';
        $sql = "select porting_master_id as portingID, DATE(request_time)  as portingDate, count(*) as number from  `oaf_porting_status` where porting_master_id = ".$this->dbHandle->escape($portingId)." and request_time >= ".$this->dbHandle->escape($dateFrom)." and request_time <= ".$this->dbHandle->escape($dateTo)."  group by DATE(request_time) DESC";

        $query = $this->dbHandle->query($sql);
        $retArr = array();
        foreach($query->result() as $row) {
            $retArr[] = (array)$row;
        }
        return $retArr;
    }

    public function getMisCountDataWeekly($portingId, $dateFrom, $dateTo, $reportFormat){
        $this->initiateModel('read','MISTracking');
        $dateFrom = $dateFrom.' 00:00:00';
    	$dateTo = $dateTo.' 23:59:59';
        $sql = "select porting_master_id as portingID, CONCAT( DATE_ADD( DATE(request_time),INTERVAL(1 - DAYOFWEEK(DATE(request_time))) DAY), ' TO ' , DATE_ADD(DATE(request_time), INTERVAL(7 - DAYOFWEEK(DATE(request_time))) DAY)) as portingWeek, count(*) as number  from  `oaf_porting_status` where porting_master_id = ".$this->dbHandle->escape($portingId)." and request_time >= ".$this->dbHandle->escape($dateFrom)." and request_time <= ".$this->dbHandle->escape($dateTo)."  group by WEEK(request_time),YEAR(request_time) DESC  order by YEAR(request_time), MONTH(request_time)";

        $query = $this->dbHandle->query($sql);
        $retArr = array();
        foreach($query->result() as $row) {
            $retArr[] = (array)$row;
        }
        return $retArr;
    }

    public function getMisCountDataMonthly($portingId, $dateFrom, $dateTo, $reportFormat){
        $this->initiateModel('read','MISTracking');
        $dateFrom = $dateFrom.' 00:00:00';
    	$dateTo = $dateTo.' 23:59:59';
        $sql = "select porting_master_id as portingID, MONTHNAME(request_time) as portingMonth, count(*) as number from  `oaf_porting_status` where porting_master_id = ".$this->dbHandle->escape($portingId)." and request_time >= ".$this->dbHandle->escape($dateFrom)." and  request_time <= ".$this->dbHandle->escape($dateTo)."  group by YEAR(request_time), MONTH(request_time) DESC order by YEAR(request_time), MONTH(request_time)";

        $query = $this->dbHandle->query($sql);
        $retArr = array();
        foreach($query->result() as $row) {
            $retArr[] = (array)$row;
        }
        return $retArr;
    }

    public function getMisData($portingId, $dateFrom, $dateTo){
    	$dateFrom = $dateFrom.' 00:00:00';
    	$dateTo = $dateTo.' 23:59:59';
        $this->initiateModel('read','User');
        $sql = "select porting_master_id, request_time, ported_item_id, response, sent_data from  `oaf_porting_status` where porting_master_id = ? and request_time >= ? and  request_time <= ? order by request_time desc";
        $query = $this->dbHandle->query($sql, array($portingId, $dateFrom, $dateTo));
        $retArr = array();
        foreach($query->result() as $row) {
            $retArr[] = (array)$row;
        }
        return $retArr;
    }

    public function getPaymentDetails($userId,$onlineFormId)
    {
    	if(empty($userId) || empty($onlineFormId))
    		return;

       	$this->initiateModel('read');
    	$this->dbHandle->select('op.mode,op.userId,op.onlineFormId,op.orderId,op.bankName,op.amount,op.date,opl.log');
    	$this->dbHandle->from('OF_Payments AS op');
    	$this->dbHandle->join('OF_PaymentLog AS opl','op.paymentId = opl.paymentId');
    	$this->dbHandle->where_in('op.onlineFormId',$onlineFormId);
    	$this->dbHandle->where_in('op.userId',$userId);
    	$this->dbHandle->where('op.status','success');
    	$this->dbHandle->where('opl.status','Success');
    	$result=$this->dbHandle->get()->result_array();
    	return $result;
    }

	public function getFieldPrefilledData($fieldId){

		if(empty($fieldId)) {
			return;
		}
		$this->initiateModel('read');
		$this->dbHandle->where('fieldId',$fieldId);
		$data = $this->dbHandle->get('OF_FieldPrefilledValues')->row_array();
		return $data;
	}

	public function getFileTypeFields()
	{
		$this->initiateModel('read');
		$this->dbHandle->select('fieldId');
		$this->dbHandle->where('type','file');
		$data = $this->dbHandle->get('OF_FieldsList')->result_array();
		$ret = array();
		foreach ($data as $key => $value) {
			$ret[] = $value['fieldId'];
		}
		return $ret;
	}

	public function getCountryMapping(){

		$this->initiateModel('read');
		$this->dbHandle->select('countryId,name');
		$data = $this->dbHandle->get('countryTable')->result_array();
		return $data;
	}


	public function getCityMapping(){

		$this->initiateModel('read');
		$this->dbHandle->select('city_name,city_id');
		$data = $this->dbHandle->get('countryCityTable')->result_array();
		return $data;
	}

	public function getVendorMapping($vendors){
		$this->initiateModel('read');

		$sql =   "SELECT city_id, city_name,vendor_entity,stateTable.state_name,vendor_name FROM shiksha_vendor_mapping LEFT JOIN countryCityTable ON ((city_id = shiksha_entity) AND entity_type = 'city') left Join stateTable  on  ((stateTable.state_id = shiksha_entity) and entity_type = 'state') where vendor_name in (?)";
		$data = $this->dbHandle->query($sql,array($vendors))->result_array();
		return $data;
	}

}	
