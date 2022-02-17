<?php

class registrationmodel extends MY_Model
{
    /**
     * To handle all db query
     * @var Object DB Handle
     */
    private $dbHandle;
    
    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct('User');
    }
    
    /**
     * Initiate the model
     *
     * @param string $operation
     */
    private function initiateModel($operation = 'read')
    {
		if($operation=='read'){
			$this->dbHandle = $this->getReadHandle();
		}
		else{
        	$this->dbHandle = $this->getWriteHandle();
		}
	}
    
    /**
    * Function to get the last communicated lead
    *
    * @param string $clientKey 
    */ 
    public function getLastCommunicatedLead($clientKey)
    {
        $this->initiateModel();
        $sql = "SELECT MAX(userid) as userid FROM customRegistrationCommunicationLog WHERE clientKey = ?";
		$query = $this->dbHandle->query($sql, array($clientKey));
		$result  = $query->row_array();
        return $result['userid'];
    }
	 /**
    * Function to get the leads for Custom Registration Communication by desired courses
    *
    * @param array $desiredCourses
    * @param integer $lastCommunicatedLead
    * @param string $startDate
    *
    * @return array
    */
	public function getLeadsForCustomRegistrationCommunicationByDesiredCourses($desiredCourses,$lastCommunicatedLead,$startDate)
	{
		$this->initiateModel();
		if(!empty($lastCommunicatedLead)) {
			$clause = "t.userid > ".$this->dbHandle->escape($lastCommunicatedLead);
		}
		else if(!empty($startDate)) {
			$clause = "t.usercreationDate >= ".$this->dbHandle->escape($startDate);
		}
		
		if(!empty($clause)) {
			$sql =  "SELECT t.userid,t.email,t.mobile ".
					"FROM tuser t INNER JOIN tUserPref tup ON tup.UserId = t.userid ".
					"WHERE ".$clause." AND tup.DesiredCourse IN (?)";
			
			
					
			$query = $this->dbHandle->query($sql, array($desiredCourses));
			return $query->result_array();
		}
		else {
			return array();
		}
	}
    /**
    * Function to get the leads for Custom Registration Communication by study abroad
    *
    * @param integer $lastCommunicatedLead
    * @param string $startDate
    *
    * @return array
    */	
	public function getLeadsForCustomRegistrationCommunicationByStudyAbroad($lastCommunicatedLead,$startDate)
	{
		$this->initiateModel();
		if(!empty($lastCommunicatedLead)) {
			$clause = "t.userid > ".$this->dbHandle->escape($lastCommunicatedLead);
		}
		else if(!empty($startDate)) {
			$clause = "t.usercreationDate >= ".$this->dbHandle->escape($startDate);
		}
		
		if(!empty($clause)) {
			$sql =  "SELECT t.userid,t.email,t.mobile ".
					"FROM tuser t INNER JOIN tUserPref tup ON tup.UserId = t.userid ".
					"WHERE ".$clause." AND tup.ExtraFlag = 'studyabroad'";
					
			$query = $this->dbHandle->query($sql);
			return $query->result_array();
		}
		else {
			return array();
		}
	}
	    /**
    * Function to insert the information of log communicated lead to DB
    *
    * @param string $clientKey
    * @param integer $leadId
    */	
	public function logCommunicatedLead($clientKey,$leadId)
	{
		$this->initiateModel('write');
		$data = array(
			'clientKey' => $clientKey,
			'userId' => $leadId,
			'date' => date('Y-m-d H:i:s')
		);
		$this->dbHandle->insert('customRegistrationCommunicationLog',$data);
	}
	  /**
    * Function to get the Courses information for Course Page based on sub category
    *
    * @param integer $subcategoryId
    * @return array 
    */	
	public function getCoursesInCoursePageSubcategory($subcategoryId)
	{
		$this->initiateModel();
        $sql = "SELECT SpecializationId,CourseName FROM course_pages_subcategory_desiredcourse_mapping a, tCourseSpecializationMapping b WHERE a.desiredCourseId = b.SpecializationId AND a.subCatId = ? AND a.status = 'live' AND b.status = 'live'";
		$query = $this->dbHandle->query($sql,array($subcategoryId));
		return $query->result_array();
	}
		  /**
    * Function to get the course page specific courses
    *
    * @return array 
    */	
	public function getCoursePageSpecificCourses()
	{
		$this->initiateModel();
        $sql = "SELECT DISTINCT desiredCourseId FROM course_pages_subcategory_desiredcourse_mapping WHERE status = 'live' AND allow_global_registration = 'no'";
		$query = $this->dbHandle->query($sql); 
		return $this->getColumnArray($query->result_array(),'desiredCourseId');
	}
	  /**
     * Function to get the course page details
     *
     * @param integer $courseId
     * @return array
     */
	public function getCoursePageCourseDetails($courseId)
	{
		$this->initiateModel();
        $sql = "SELECT * FROM course_pages_subcategory_desiredcourse_mapping WHERE status = 'live' AND desiredCourseId = ?";
		$query = $this->dbHandle->query($sql,array($courseId));
		return $query->row_array();
	}
	
     /**
     * Get Customized cities for special courses like SMU
     * @param integer $listingId
     *
     */
     
	public function checkCustomizedCity($listingId){
	    $this->initiateModel();
	    $sql = "SELECT * FROM `CustomLocationTable` WHERE `Status` = 'Live' AND `course_id` = ?";
	    $query = $this->dbHandle->query($sql,$listingId);
	  
	    if ($query->num_rows() > 0){
		return 1;
	    }else{
		return 0;
	    }
	    
	}
	
    /**
     * Get Customized localities for special courses like SMU
     *
     * @param integer $listingId
     */
	
	public function getCustomizedLocalitiesId($listingId){
	    $this->initiateModel();
	    $sql = "SELECT `City`, `Locality` FROM `CustomLocationTable` WHERE `Status`='Live' AND `course_id` = ?";
	    $query = $this->dbHandle->query($sql,$listingId);
    
	    return $query->result_array();
	}
	
	
	/**
	 * Function to get the list of all custom courses
	 *
	 * @return array
	 */
	public function getAllCustomCourse(){
	   
	    $this->initiateModel();
	    $sql = "SELECT DISTINCT(`course_id`) FROM `CustomLocationTable` WHERE `Status`='Live'";
	    $query = $this->dbHandle->query($sql);
	    return $query->result_array();
	}
	
	
	/**
	 * Function to check if OTP is verified for email
	 *
	 * @param string $email
	 * 
	 * @return boolean
	 */ 
	public function isOTPVerified($email)
	{
		$this->initiateModel();
        $sql = "SELECT status FROM OTPVerification WHERE email = ?";
		$query = $this->dbHandle->query($sql,array($email));
		$row = $query->row_array();
		return $row['status'] == 'verified' ? TRUE : FALSE;
	}

	/*
	 * Function to get the course Level from tCourseSpecialization from LDB courseId
	 * @params :  $ldbCourseId =>  Integer
	 * @returns :  'PG' or 'UG'
	 */
	public function getCourseLevelByLDBCourseId($ldbCourseId){

		$this->initiateModel();
		$sql = "select CourseLevel1 from tCourseSpecializationMapping where SpecializationId = ? ";
		$query = $this->dbHandle->query($sql, array($ldbCourseId));
		$row = $query->row_array();
		return ($row['CourseLevel1'] == 'PG' || $row['CourseLevel1'] == "PhD" ? 'PG' : 'UG');
	}


	public function getCurrentLevelByLDBCourseIdForSA($ldbCourseId){
		$this->initiateModel();
		$this->dbHandle->select('CourseName');
		$this->dbHandle->from('tCourseSpecializationMapping');
		$this->dbHandle->where('SpecializationId',$ldbCourseId);
		$this->dbHandle->where('scope','abroad');
		$this->dbHandle->where('isEnabled',1);
		$this->dbHandle->where('status','live');
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		$courseName = $result[0];
		return $courseName;
	}

	public function getUserDesiredCountries($userId){
		if(empty($userId) || !is_numeric($userId)){
			return false;
		}

		$this->initiateModel('write');
		$this->dbHandle->select('CountryId');
		$this->dbHandle->from('tUserLocationPref');
		$this->dbHandle->where('UserId',$userId);
		$this->dbHandle->where('CountryId >',2);
		$this->dbHandle->where('Status','live');
		$result = $this->dbHandle->get()->result_array();
		return $result;
	}

	public function getUserLDBCourseId($userId){
		if(empty($userId) || !is_numeric($userId)){
			return false;
		}

		$this->initiateModel('write');
		$this->dbHandle->select('DesiredCourse');
		$this->dbHandle->from('tUserPref');
		$this->dbHandle->where('UserId',$userId);
		$this->dbHandle->where('ExtraFlag','studyabroad');
		$this->dbHandle->where('Status','live');
		$result = $this->dbHandle->get()->result_array();
		return $result;
	}

	/*
	 * Function to get the course Level from tCourseSpecialization and clentCourseToLDBCourseMapping from client courseId
	 * @params :  $clientCourseId =>  Integer
	 * @returns :  'PG' or 'UG'
	 */
	public function getCourseLevelByClientCourseId($clientCourseId){

		$this->initiateModel();
		$sql = "SELECT tcs.CourseLevel1 FROM clientCourseToLDBCourseMapping map JOIN tCourseSpecializationMapping tcs ON map.`LDBCourseID` = tcs.SpecializationId WHERE map.`clientCourseID` = ? AND map.status =  'live' limit 1";
		$query = $this->dbHandle->query($sql, array($clientCourseId));
		$row = $query->row_array();
		return $row['CourseLevel1'] == 'PG' ? 'PG' : 'UG';
	}

	public function getRegistrationStreamFlow($streamId){

		if(empty($streamId)){
			return array();
		}

		$this->initiateModel();
		$sql = 'SELECT flow FROM registrationStreamFlow WHERE streamId = ? AND status="live"';
		$result = $this->dbHandle->query($sql, array($streamId))->result_array();
		return $result[0]['flow'];
    }

    public function getSubStreamSpecFieldsRule($streamId){
    	if(empty($streamId)){
			return array();
		}

		$this->initiateModel();
		$sql = 'SELECT isSpecializationMand FROM registrationStreamFlow WHERE streamId = ? AND status="live"';
		$result = $this->dbHandle->query($sql, array($streamId))->result_array();
		return $result[0]['isSpecializationMand'];	
    }

    public function getCompleteIsdCodeData(){
    	$this->initiateModel();
    	$sql = 'SELECT shiksha_countryId, shiksha_countryName, abbreviation, isdCode 
    			FROM isdCodeCountryMapping 
    			WHERE status="live" 
    			order by priority, shiksha_countryName asc';
    	return $this->dbHandle->query($sql, array($streamId))->result_array();
    }

    public function getClientCourseNameById($course_ids = array()){
    	if(empty($course_ids)){
    		return array();
    	}

    	$this->initiateModel();
    	$sql = 'SELECT course_id, name FROM shiksha_courses WHERE course_id IN (?) AND status="live"';
    	$data = $this->dbHandle->query($sql,array($course_ids))->result_array();

    	$returnData = array();
    	foreach($data as $key=>$value){
    		$returnData[$value['course_id']] = $value['name'];
    	}
    	return $returnData;
    }

    public function getInstituteNameById($inst_ids = array()){
    	if(empty($inst_ids)){
    		return array();
    	}

    	$this->initiateModel();
    	$sql = 'SELECT listing_id, name FROM shiksha_institutes WHERE listing_id IN (?) AND status="live"';
    	$data = $this->dbHandle->query($sql,array($inst_ids))->result_array();

    	$returnData = array();
    	foreach($data as $key=>$value){
    		$returnData[$value['listing_id']] = $value['name'];
    	}
    	return $returnData;
    }

    public function getUsersToSendReminder($frequency_date, $actionData){
    	if(empty($frequency_date) || !is_array($actionData) || count($actionData) <=0){
    		return array();
    	}

    	$startDate = $frequency_date.' 00:00:00';
    	$endDate = $frequency_date.' 23:59:59';

    	$this->initiateModel();
    	$sql = "select user_id, listing_id, listing_type, keyname  from  temp_response tmp_res  join tracking_pagekey tKey on tmp_res.tracking_key= tKey.id join tuserflag flag on flag.userid = tmp_res.user_id where keyname in (?) and tmp_res.submit_time >=? and tmp_res.submit_time<=? and mobileverified='0' order by tmp_res.submit_time desc";
    	$data = $this->dbHandle->query($sql,array($actionData,$startDate, $endDate))->result_array();
    	return $data;
    }

    public function getUserContactData($user_ids){
    	if(count($user_ids)<1){
    		return array();
    	}

    	$this->initiateModel();
    	$sql = "select userid, email,firstname, mobile, isdCode from tuser where userid in (?)";
    	$data = $this->dbHandle->query($sql,array($user_ids))->result_array();

    	return $data;		
    }

    public function saveInvalidFieldData($fieldData) {

    	if(!empty($fieldData) && is_array($fieldData)) {
    		
    		$this->initiateModel('write');
    		$data = array(
				'field_name'         => $fieldData['fieldName'],
				'field_value'        => $fieldData['fieldValue'],
				'reg_form_id'        => $fieldData['regFormId'],
				'visitor_session_id' => $fieldData['visitorSessionId'],
				'referer'            => $fieldData['referer']
			);
			$insertFlag = $this->dbHandle->insert('registration_field_tracking',$data);
			return $insertFlag;

		}

    }

    public function reportError($url,$error){
    	if(empty($url)){
    		return;
    	}

    	$this->initiateModel('write');
    	$data = array(
    		'error' => $error,
    		'url' => $url
    	);

    	$this->dbHandle->insert('registration_layer_tracking',$data);
    }

}
