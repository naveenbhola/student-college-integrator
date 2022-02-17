<?php
class miscronsmodel extends MY_Model
{
	/**
	 * constructor method.
	 *
	 * @param array
	 * @return array
	 */
	function __construct()
	{ 
		parent::__construct('CategoryList');
	}
	
	private function initiateModel($operation='read'){
		if($operation=='read'){ 
			$this->dbHandle = $this->getReadHandle();
		}else{
		    $this->dbHandle = $this->getWriteHandle();
		}		
	}
    
    function getTrackingData($trackingIds, $site= 'all'){
        $this->initiateModel();
        $this->dbHandle->select('*');
        $this->dbHandle->FROM('tracking_pagekey');
        if($site == 'abroad'){
            $this->dbHandle->where('site','Study Abroad');
        }
        if(count($trackingIds)>0)
        {
            $this->dbHandle->where_in('id',$trackingIds);
        }
        $result = $this->dbHandle->get()->result_array();

        return $result;   
    }

    function getTrackingIds()
    {
        $this->initiateModel();
        
        $this->dbHandle->select('id, keyName, conversionType,site,siteSource');
        $this->dbHandle->FROM('tracking_pagekey');
        $result = $this->dbHandle->get()->result_array();
        return $result;
    }

    // un used
    function getResponses($date,$trackingIdsDataArray){
        $this->initiateModel();
        $this->dbHandle->select('tracking_keyid,listing_subscription_type,visitorsessionid, count(1) as count');
        $this->dbHandle->from('tempLMSTable');
        
        $this->dbHandle->where('submit_date >=',$date.' 00:00:00');
        $this->dbHandle->where('submit_date <=',$date.' 23:59:59');
        $this->dbHandle->where_in('tracking_keyid',$trackingIdsDataArray);
        $this->dbHandle->group_by('visitorsessionid');
        $this->dbHandle->group_by('listing_subscription_type');
        $this->dbHandle->group_by('tracking_keyid');
        //echo $this->dbHandle->_compile_select();die;
        $result = $this->dbHandle->get()->result_array();
        return $result;
    }

    function getResponseData($dateRange,$trackingKeyForAbroad){
        $this->initiateModel();
        $this->dbHandle->select('id,listing_type_id as courseId,userId,action,listing_subscription_type as responseType,tracking_keyid,visitorsessionid,submit_date as responseDate');
        $this->dbHandle->from('tempLMSTable');
        $this->dbHandle->where('submit_date >=',$dateRange['startDate']);
        $this->dbHandle->where('submit_date <=',$dateRange['endDate']);
        $this->dbHandle->where('listing_type','course');
        //$this->dbHandle->where('tracking_keyid > 0');
        $this->dbHandle->where_in('tracking_keyid',$trackingKeyForAbroad);
        $this->dbHandle->order_by('submit_date','asc');
        //echo $this->dbHandle->_compile_select();die;
        $result = $this->dbHandle->get()->result_array();
        return $result;
    }

    function getDataForIds($IdsToInsert,$trackingKeyForAbroad){
        $this->initiateModel();
        $this->dbHandle->select('id,listing_type_id as courseId,userId,action,listing_subscription_type as responseType,tracking_keyid,visitorsessionid,submit_date as responseDate');
        $this->dbHandle->from('tempLMSTable');
        $this->dbHandle->where_in('id',$IdsToInsert);
        $this->dbHandle->where_in('tracking_keyid',$trackingKeyForAbroad);
        $this->dbHandle->where('listing_type','course');
        //$this->dbHandle->where('tracking_keyid > 0');
        //echo $this->dbHandle->_compile_select();die;
        $result = $this->dbHandle->get()->result_array();
        return $result;
    }

    function getSessionInformation($uniqueSessionIds){
        $this->initiateModel();
        $this->dbHandle->select('distinct(sessionId) as sessionId,source,utm_source,utm_campaign,utm_medium');
        $this->dbHandle->from('session_tracking');
        $this->dbHandle->where_in('sessionId',$uniqueSessionIds);
        //echo $this->dbHandle->_compile_select();die;
        $result = $this->dbHandle->get()->result_array();
        //_p($result);die;
        return $result;
    }

    function getAbroadCourseInformation($courseIds,$desiredCourseIds){
        $this->initiateModel();
        $desiredCourseIds = implode(",", $desiredCourseIds);
        $courseIds = implode(",", $courseIds);
        $status = "'live','deleted'";
        
        $sql = "(select distinct(course_id),category_id,sub_category_id,ldb_course_id,course_level,university_id,country_id,city_id from abroadCategoryPageData where course_id in (".$courseIds.") and ldb_course_id  in (".$desiredCourseIds.") and status in (".$status.") ) union (select distinct(course_id),category_id,sub_category_id,ldb_course_id,course_level,university_id,country_id,city_id from abroadCategoryPageData where course_id in (".$courseIds.") and status in (".$status.") group by course_id having min(ldb_course_id) not in (".$desiredCourseIds.") )";
        $result = $this->dbHandle->query($sql)->result_array();
        //echo $this->dbHandle->last_query();die;
        //_p($result);die;
        return $result;
    }

    function getInstituteLocationIds($tempLMSIds){
        //echo '2';_p($tempLMSIds);
        $this->initiateModel();
        $this->dbHandle->select('responseId,instituteLocationId');
        $this->dbHandle->from('responseLocationTable');
        $this->dbHandle->where_in('responseId',$tempLMSIds);
        $result = $this->dbHandle->get()->result_array();
        //_p($result);die;
        //echo $this->dbHandle->last_query();die;
        return $result;
    }

    function getLocationIdForCourse($instituteLocationIds){
        //_p($instituteLocationIds);die;
        $this->initiateModel();
        $status = array('live','deleted');
        $this->dbHandle->select('distinct(institute_location_id),institute_id,city_id');
        $this->dbHandle->from('institute_location_table');
        $this->dbHandle->where_in('institute_location_id',$instituteLocationIds);
        $this->dbHandle->where_in('status',$status);
        $result = $this->dbHandle->get()->result_array();
        //_p($result);die;
        //echo $this->dbHandle->last_query();die;
        return $result;
    }

    function getCourseLevelForCourse($ldbCourseIds){
        $this->initiateModel();
        $this->dbHandle->select('SpecializationId,CourseName');
        $this->dbHandle->from('tCourseSpecializationMapping');
        $this->dbHandle->where_in('SpecializationId',$ldbCourseIds);
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result;
    }

    function getCategoryForCourse($subCategoryIds){
        $this->initiateModel();
        $this->dbHandle->select('boardId,parentId');
        $this->dbHandle->from('categoryBoardTable');
        $this->dbHandle->where_in('boardId',$subCategoryIds);
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result;
    }

    function getLDBCourseIdForCourse($subCategoryIds){
        $this->initiateModel();

        $subCategoryIds = implode(",", $subCategoryIds);
        $sql = 'select categoryID,ldbCourseID from LDBCoursesToSubcategoryMapping where categoryID in ('.$subCategoryIds.') and status = "live" group by categoryID having min(ldbCourseID)';
        $result = $this->dbHandle->query($sql)->result_array();
        //$result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result;
    }

    function getTestPrepCourse($courseIds){
        $this->initiateModel();
        $status = array("live","deleted");
        $this->dbHandle->select('distinct(cpd.course_id)');
        $this->dbHandle->from('categoryPageData cpd');
        $this->dbHandle->join('categoryBoardTable cbt','cpd.category_id = cbt.boardId and cbt.parentId = 14','inner');
        $this->dbHandle->where_in('cpd.status',$status);
        $this->dbHandle->where_in('cpd.course_id',$courseIds);
        return $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
    }

    function getBlogIdsForCourse($testPrepCourseIds){
        $this->initiateModel();
        /*$this->dbHandle->select('clientCourseID,blogId');
        $this->dbHandle->from('clientCourseToLDBCourseMapping cctlm');
        $this->dbHandle->join('testPrepCourseMapping tpcm','cctlm.LDBCourseID = tpcm.specializationId','inner');
        $this->dbHandle->where_in('clientCourseID',$testPrepCourseIds);
        $this->dbHandle->where_in('cctlm.status','live');
        $this->dbHandle->where_in('tpcm.status','live');*/

        $testPrepCourseIds = implode(",", $testPrepCourseIds);
        $sql = 'select cctlm.clientCourseID,tpcm.blogId from clientCourseToLDBCourseMapping cctlm inner join testPrepCourseMapping tpcm on cctlm.LDBCourseID = tpcm.specializationId where cctlm.status="live" and tpcm.status="live"  and cctlm.clientCourseID in ('.$testPrepCourseIds.') group by cctlm.clientCourseID having min(tpcm.blogId)';
        $result = $this->dbHandle->query($sql)->result_array();
        //echo $this->dbHandle->last_query();die;
        //_p($result);die;
        return $result;
    }

    function getCourseSubCatCourseLevelInfo($ldbCourseIdForFilter){
        $this->initiateModel();
        $this->dbHandle->select('blogId,boardId,blogTypeValues');
        $this->dbHandle->from('blogTable');
        $this->dbHandle->where_in('blogId',$ldbCourseIdForFilter);
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result;
    }

    function getTempLMSIds($date){
        //$date = date("Y-m-d");
        $this->initiateModel();
        $this->dbHandle->select('tempLMSId');
        $this->dbHandle->from('upgradedResponseData');
        $this->dbHandle->where('responseDate <=',$date);
        $this->dbHandle->where('isProcessed','no');
        //echo $this->dbHandle->_compile_select();die;
        $result = $this->dbHandle->get()->result_array();
        return $result;
    }

    function getResponseDataForUpgradation($tempLMSIdsArray, $trackingIdsDataArray){
        $this->initiateModel();
        $this->dbHandle->select('id,submit_date,tracking_keyid,visitorsessionid,listing_subscription_type as responseType');
        $this->dbHandle->from('tempLMSTable');
        $this->dbHandle->where_in('tracking_keyid',$trackingIdsDataArray);
        $this->dbHandle->where_in('id',$tempLMSIdsArray);
        $this->dbHandle->where('listing_type','course');
        $result = $this->dbHandle->get()->result_array();
        return $result;   
    }

    function updateStatusInUpgradedResponseTable($tempLMSIdsArray){
            
        $this->initiateModel("write");
        $tempLMSIdsArray = implode(",", $tempLMSIdsArray);
        $sql = "update upgradedResponseData set isProcessed = 'yes' where tempLMSId in (".$tempLMSIdsArray.")";
        $this->dbHandle->query($sql);
        //echo $this->dbHandle->last_query();
    }

    function getRegistrations($date,$trackingIdsDataArray){
        $this->initiateModel();
        $this->dbHandle->select('tracking_keyid,visitorsessionid,count(1) as count');
        $this->dbHandle->from('tusersourceInfo');
        $this->dbHandle->where('time >=',$date.' 00:00:00');
        $this->dbHandle->where('time <=',$date.' 23:59:59');
        $this->dbHandle->where_in('tracking_keyid',$trackingIdsDataArray);
        $this->dbHandle->group_by('visitorsessionid');
        $this->dbHandle->group_by('tracking_keyid');
        $result = $this->dbHandle->get()->result_array();
        return $result;
    }

    function getTrafficSource($uniqueSessions,$date){
        $this->initiateModel();
        $this->dbHandle->select(' distinct(sessionId),source');
        $this->dbHandle->from('session_tracking');
        $this->dbHandle->where_in('sessionId',$uniqueSessions);
        //echo $this->dbHandle->_compile_select();die;
        $result = $this->dbHandle->get()->result_array();
        return $result;
    }

    function insertMISData($data){
        $this->initiateModel('write');
        $this->dbHandle->insert_batch('MISOverviewData',$data);
    }

    function getRegistrationdataFromDB($dateRange){
        $this->initiateModel('read');
        $this->dbHandle->select('rt.id,rt.userId,rt.desiredCourse,rt.categoryId,rt.subCatId,rt.blogId,rt.city,rt.country,rt.prefCountry1,rt.prefCountry2,rt.prefCountry3,rt.source,rt.userType,rt.isNewReg,rt.submitTime,tu.usercreationDate,rt.trackingkeyId,rt.visitorSessionId');
        $this->dbHandle->from('registrationTracking rt');
        $this->dbHandle->join('tuser tu','tu.userId = rt.userId','inner');
        $this->dbHandle->where('rt.submitTime >= ',$dateRange['startDate']);
        $this->dbHandle->where('rt.submitTime <= ',$dateRange['endDate']);
        /*$this->dbHandle->where('submitTime >= ','2016-03-19 00:00:00');
        $this->dbHandle->where('submitTime <= ','2016-03-19 10:10:10');*/
        //$this->dbHandle->where('userType', 'abroad');

        //$this->dbHandle->where('trackingkeyId > 0');
        $this->dbHandle->where('rt.isNewReg =',"yes" );
        $this->dbHandle->order_by('rt.submitTime','asc');
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result;
    }

    function getBlogIdToCourseLevel($blogIds){
        $statusArray = array('live','deleted');
        $this->initiateModel('read');
        $this->dbHandle->select('blogId,blogTypeValues');
        $this->dbHandle->from('blogTable');
        $this->dbHandle->where('blogType','exam');
        $this->dbHandle->where_in('blogId',$blogIds);
        $this->dbHandle->where_in('status',$statusArray);
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result;
    }

    function getDesiredCourseToCourseLevel($desiredCourseIds,$userType){
        if($userType =='national'){
            $fieldName = 'CourseLevel1';
            $scope = 'india';
        }else if($userType =='abroad'){
            $fieldName = 'CourseName';
            $scope = 'abroad';
        }
    
        $this->initiateModel('read');
        $this->dbHandle->select('SpecializationId,'.$fieldName);
        $this->dbHandle->from('tCourseSpecializationMapping');
        $this->dbHandle->where_in('SpecializationId',$desiredCourseIds);
        $this->dbHandle->where('scope',$scope);
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();echo '<br>';
        return $result;
    }

    public function checkIfUserHasGivenExam($userIds,$abroadExams){
        if(!is_array($userIds) || (!count($userIds) >0)){
            return array();
        }

        $this->initiateModel('read');
        $this->dbHandle->select('distinct(userId) as userId');
        $this->dbHandle->from('tUserEducation');
        $this->dbHandle->where('Level','Competitive exam');
        $this->dbHandle->where('status','live');
        $this->dbHandle->where_in('name',$abroadExams);
        $this->dbHandle->where_in('userId',$userIds);
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result;
    }

    public function userHasBookedExam($userIds){
        if(!is_array($userIds) || (!count($userIds) >0)){
            return array();
        }

        $this->initiateModel('read');
        $this->dbHandle->select('userId');
        $this->dbHandle->from('tUserAdditionalInfo');
        $this->dbHandle->where('bookedExamDate','1');
        $this->dbHandle->where_in('userId',$userIds);
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result;   
    }

    public function getUserInterestData($userIds){
        if(!is_array($userIds) || (!count($userIds) >0)){
            return array();
        }
        $this->initiateModel('read');
        $this->dbHandle->select('interestId, streamId, subStreamId, time, userId');
        $this->dbHandle->from('tUserInterest');
        $this->dbHandle->where_in('userId',$userIds);
        $this->dbHandle->order_by('interestId','asc');
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result;   
    }

    public function getUserBaseCourse($interestIds){
        if(!is_array($interestIds) || (!count($interestIds) >0)){
            return array();
        }

        $this->initiateModel('read');
        $this->dbHandle->select('interestId, specializationId, baseCourseId');
        $this->dbHandle->from('tUserCourseSpecialization');
        $this->dbHandle->where_in('interestId',$interestIds);
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result;
    }

    public function getUserModeDetails($interestIds){
        if(!is_array($interestIds) || (!count($interestIds) >0)){
            return array();
        }

        $this->initiateModel('read');
        $this->dbHandle->select('interestId, attributeKey, attributeValue');
        $this->dbHandle->from('tUserAttributes');
        $this->dbHandle->where_in('interestId',$interestIds);
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result;
    }

    public function getUserDetails(){
        //select distinct userid from tuser where isdCode not in (91) and usercreationDate >= '2018-11-01';
        $this->initiateModel('read');
        $this->dbHandle->select('distinct( tu.userid) usrId, ts.visitorsessionid');
        $this->dbHandle->from('tuser tu');
        $this->dbHandle->join('tusersourceInfo ts','ts.userId = tu.userId','inner');
        $this->dbHandle->where('isdCode !=',91);
        $this->dbHandle->where("tu.usercreationDate >= ",'2018-11-01 00:00:00');
        $this->dbHandle->limit(100);
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result;
    }

    public function getNationalData($dateRange){
        $this->initiateModel('read');
        $this->dbHandle->select('rt.id,rt.userId,rt.desiredCourse,rt.categoryId,rt.subCatId,rt.blogId,rt.city,rt.country,rt.prefCountry1,rt.prefCountry2,rt.prefCountry3,rt.source,rt.userType,rt.isNewReg,rt.submitTime,tu.usercreationDate,rt.trackingkeyId,rt.visitorSessionId');
        $this->dbHandle->from('registrationTracking rt');
        $this->dbHandle->join('tuser tu','tu.userId = rt.userId','inner');
        $this->dbHandle->where('rt.submitTime >= ',$dateRange['startDate']);
        $this->dbHandle->where('rt.submitTime <= ',$dateRange['endDate']);
        $this->dbHandle->where('rt.userType !=', 'abroad');

        //$this->dbHandle->where('trackingkeyId > 0');
        $this->dbHandle->where('rt.isNewReg =',"yes" );
        $this->dbHandle->order_by('rt.submitTime','asc');
        $result = $this->dbHandle->get()->result_array();

        //echo $this->dbHandle->last_query();die;
        return $result;   
    }

    function getTrackingDetails()
    {
        $this->initiateModel();
        
        $this->dbHandle->select('*');
        $this->dbHandle->FROM('tracking_pagekey');
        $result = $this->dbHandle->get()->result_array();
        return $result;
    }

    function getDataFromTempLMSTable($dateRange){
        $this->initiateModel("read");
        $this->dbHandle->select("id, visitorsessionid, tracking_keyid");
        $this->dbHandle->from("tempLMSTable");
        $this->dbHandle->where("submit_date >= ", $dateRange['startDate']);
        $this->dbHandle->where("submit_date <= ", $dateRange['endDate']);
        $result = $this->dbHandle->get()->result_array();
        return $result;
        //echo $this->dbHandle->last_query();
    }

    function getTrackingIdsForPageGroup($pageGroup = "examPageMain"){
        $this->initiateModel("read");
        $this->dbHandle->select("*");
        $this->dbHandle->from("tracking_pagekey");
        $this->dbHandle->where("pageGroup ", $pageGroup);
        $this->dbHandle->where("site ", "Domestic");
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result;

    }
}