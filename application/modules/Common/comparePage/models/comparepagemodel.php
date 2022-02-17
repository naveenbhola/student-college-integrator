<?php
class comparepagemodel extends MY_Model
{
	function __construct()
    {
        parent::__construct();
    }

    private function initiateModel($operation='read'){
		if($operation=='read'){ 
			$this->dbHandle = $this->getReadHandle();
		}else{
		    $this->dbHandle = $this->getWriteHandle();
		}		
	}

    function comparisionOfPopularCourses($baseEntityArrayForCourses,$source='desktop'){
   		$this->initiateModel();
	 	if($source == 'mobile'){
	 		$this->dbHandle->where('course3_id',0);
	 		$this->dbHandle->where('course4_id',0);
	 	}
        
        $this->dbHandle->select('course1_id,course1_name,course1_location,course2_id,course2_name,course2_location,course3_id,course3_name,course3_location,course4_id,course4_name,course4_location');
        $this->dbHandle->from('Popular_Courses_Comparision');
        $this->dbHandle->where('status','live');
        $this->dbHandle->where('stream_id',$baseEntityArrayForCourses['stream_id']);
        $this->dbHandle->where('substream_id',$baseEntityArrayForCourses['substream_id']);
        $this->dbHandle->where('specialization_id',$baseEntityArrayForCourses['specialization_id']);
        $this->dbHandle->where('base_course_id',$baseEntityArrayForCourses['base_course_id']);
        $this->dbHandle->order_by('','RANDOM');
        $this->dbHandle->limit(5,0);
        $result = $this->dbHandle->get()->result_array();
        return $result;
   	}

   	function getStaticURLDetailsFromDB ($courseId1,$courseId2,$courseId3 = 0,$courseId4 = 0){
        $this->initiateModel();
      	$sql="SELECT course1_id,course1_name,course1_location,course2_id,course2_name,course2_location,course3_id,course3_name,course3_location,course4_id,course4_name,course4_location FROM Popular_Courses_Comparision WHERE course1_id = ? AND course2_id = ? AND course3_id = ? AND course4_id = ? and status = 'live'";
		$query = $this->dbHandle->query($sql,array($courseId1,$courseId2,$courseId3,$courseId4));
         return $query->result_array();      
   }

   // need to remove this function after migration script.  UGC-4403
   public function getSubcategoryIdsForStaticComparePageData(){
		$this->initiateModel();
		$this->dbHandle->select('distinct(subcategory_id) as subcategory_id');
		$this->dbHandle->from('Popular_Courses_Comparision');
		$this->dbHandle->where('status','live');
		$result = $this->dbHandle->get()->result_array();
		return $result;
	}

	// need to remove this function after migration script.  UGC-4403
	public function getNewMappingForSubcategoryId($subcategoryIds){
		$this->initiateModel();
		$this->dbHandle->select('oldsubcategory_id as subcategory_id, stream_id, substream_id, specialization_id, base_course_id, education_type, delivery_method, credential,level');
		$this->dbHandle->from('base_entity_mapping');
		$this->dbHandle->where_in('oldsubcategory_id',$subcategoryIds);
		$this->dbHandle->where('oldspecializationid',0);
		$result = $this->dbHandle->get()->result_array();
		return $result;	
	}

	// need to remove this function after migration script.  UGC-4403
	public function migrateSubcategoryToNewMapping($subCategoryToNewMappingData){
		$this->initiateModel('write');
		$this->dbHandle->update_batch('Popular_Courses_Comparision',$subCategoryToNewMappingData,'subcategory_id');
	}

	public function checkIfPopularCourseComparisionMappingExist($courseId, $dbHandle){
		if(empty($courseId) || $courseId <= 0){
			return false;
		}

		if(!empty($dbHandle)){
			$this->dbHandle = $dbHandle;
		}else{
			$this->initiateModel('read');	
		}
		$this->dbHandle->select('id');
		$this->dbHandle->from('Popular_Courses_Comparision');
		$this->dbHandle->where('status','live');
		$this->dbHandle->where('(course1_id='.$courseId,'',false);
        $this->dbHandle->or_where('course2_id='.$courseId,'',false);
        $this->dbHandle->or_where('course3_id='.$courseId,'',false);
        $this->dbHandle->or_where('course4_id='.$courseId,')',false);
        $result = $this->dbHandle->get()->result_array();
        if(count($result) > 0){
   			return true;
   		}else{
   			return false;
   		}
	}

	public function deletePopularCourseComparisionMappingForCourse($courseId, $dbHandle){
		if(empty($courseId) || $courseId <= 0){
			return false;
		}

		if(!empty($dbHandle)){
			$this->dbHandle = $dbHandle;
		}else{
			$this->initiateModel('write');	
		}

		$fieldsTobeUpdated = array('status' => 'deleted');

		$this->dbHandle->where('status','live');
		$this->dbHandle->where('(course1_id='.$courseId,'',false);
        $this->dbHandle->or_where('course2_id='.$courseId,'',false);
        $this->dbHandle->or_where('course3_id='.$courseId,'',false);
        $this->dbHandle->or_where('course4_id='.$courseId,')',false);
		$response = $this->dbHandle->update('Popular_Courses_Comparision',$fieldsTobeUpdated);
		return $response;
	}

	public function migratePopularCourseComparisionMappingForCourse($oldCourse,$newCourse,$newCourseDetails, $dbHandle){
		if(empty($oldCourse)|| $oldCourse <= 0){
			return false;
		}

		if(empty($newCourse) || $newCourse <= 0){
			return false;
		}

		if(empty($newCourseDetails)){
			return false;
		}

		if(!empty($dbHandle)){
			$dbHandle = $dbHandle;
		}else{
			$dbHandle = $this->getWriteHandle();
		}

		$response = true;
		//1. get all data for which course id = oldCourseId
		$dbHandle->select('*');
		$dbHandle->from('Popular_Courses_Comparision');
		$dbHandle->where('status','live');
		$dbHandle->where('(course1_id='.$oldCourse,'',false);
        $dbHandle->or_where('course2_id='.$oldCourse,'',false);
        $dbHandle->or_where('course3_id='.$oldCourse,'',false);
        $dbHandle->or_where('course4_id='.$oldCourse,')',false);
        $result = $dbHandle->get()->result_array();

        if($result){
        	// delete previous rows
        	$fieldsTobeUpdated = array('status' => 'deleted');

			$dbHandle->where('status','live');
			$dbHandle->where('(course1_id='.$oldCourse,'',false);
	        $dbHandle->or_where('course2_id='.$oldCourse,'',false);
	        $dbHandle->or_where('course3_id='.$oldCourse,'',false);
	        $dbHandle->or_where('course4_id='.$oldCourse,')',false);
			$response = $dbHandle->update('Popular_Courses_Comparision',$fieldsTobeUpdated);

			if($response){
				// insert new rows	
				foreach ($result as $key => $value) {
					if($value['course1_id'] == $oldCourse){
						$result[$key]['course1_id'] = $newCourse;
						$result[$key]['course1_name'] = $newCourseDetails['courseName'];
						$result[$key]['course1_location'] = $newCourseDetails['courseCityName'];
					}else if($value['course2_id'] == $oldCourse){
						$result[$key]['course2_id'] = $newCourse;
						$result[$key]['course2_name'] = $newCourseDetails['courseName'];
						$result[$key]['course2_location'] = $newCourseDetails['courseCityName'];
					}else if($value['course3_id'] == $oldCourse){
						$result[$key]['course3_id'] = $newCourse;
						$result[$key]['course3_name'] = $newCourseDetails['courseName'];
						$result[$key]['course3_location'] = $newCourseDetails['courseCityName'];
					}else if($value['course4_id'] == $oldCourse){
						$result[$key]['course4_id'] = $newCourse;
						$result[$key]['course4_name'] = $newCourseDetails['courseName'];
						$result[$key]['course4_location'] = $newCourseDetails['courseCityName'];
					}
					unset($result[$key]['id']);
				}
				$response = $dbHandle->insert_batch('Popular_Courses_Comparision', $result);
			}
        }
		return $response;
	}

  function trackComparePage($pageType,$source,$courseBucket,$userId){
	 $this->initiateModel('write');
	 $sessionId = sessionId();
	 $clientIP = getenv("HTTP_TRUE_CLIENT_IP")?getenv("HTTP_TRUE_CLIENT_IP"):(getenv("HTTP_X_FORWARDED_FOR")?getenv("HTTP_X_FORWARDED_FOR"):getenv("REMOTE_ADDR"));
 
	 $queryCmd = "SELECT * FROM user_session_info WHERE session_id = ?";
	 $query = $this->dbHandle->query($queryCmd,array($sessionId));
 
	 if($query->num_rows() == 0) {
	     $queryCmd = "INSERT INTO user_session_info SET session_id = ?,user_id = ?,user_ip = ?";
	     $query = $this->dbHandle->query($queryCmd,array($sessionId,$userId,$clientIP));
	 }
 
	 $queryCmd = "SELECT id FROM user_session_info WHERE session_id = ? ORDER BY time DESC LIMIT 1";
	 $results = $this->dbHandle->query($queryCmd,array($sessionId))->result_array();
	 $user_session_id = ($results[0]['id'] !='') ? $results[0]['id'] : '';
 	
	 $courseArray = array_keys($courseBucket);
	
	 $courseId_1 = (isset($courseArray[0]) && $courseArray[0]!='')?$courseArray[0]:0;
	 $courseId_2 = (isset($courseArray[1]) && $courseArray[1]!='')?$courseArray[1]:0;
	 $courseId_3 = (isset($courseArray[2]) && $courseArray[2]!='')?$courseArray[2]:0;
	 $courseId_4 = (isset($courseArray[3]) && $courseArray[3]!='')?$courseArray[3]:0;
     
     $key  = $courseBucket;

     $key1 = (isset($key[$courseId_1]) && $key[$courseId_1] !='') ? $key[$courseId_1] : Null;
     $key2 = (isset($key[$courseId_2]) && $key[$courseId_2] !='') ? $key[$courseId_2] : Null;
     $key3 = (isset($key[$courseId_3]) && $key[$courseId_3] !='') ? $key[$courseId_3] : Null;
     $key4 = (isset($key[$courseId_4]) && $key[$courseId_4] !='') ? $key[$courseId_4] : Null;

        $isResponseMade = '0';
	 if($userId>0)
	 {
	    $isResponseMade = '1';
	 }
	 $queryCmd = "INSERT INTO ComparePage_Tracking (userId,sessionId,courseId_1,courseId_2,courseId_3,courseId_4,pageKeyId_1,pageKeyId_2,pageKeyId_3,pageKeyId_4,pageType,source,isResponseMade) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";	 
	 $query = $this->dbHandle->query($queryCmd,array($userId,$user_session_id,$courseId_1,$courseId_2,$courseId_3,$courseId_4,$key1,$key2,$key3,$key4,$pageType,$source,$isResponseMade));
   }

   	//After user has loggedin,update userId for made response of compare
	function updateUserIdForMadeResponse($userId)
	{
	 	$this->initiateModel('write');
	    $sessionId = sessionId();
	 	$queryCmd = "SELECT id FROM user_session_info WHERE session_id = ? ORDER BY time DESC LIMIT 1";
	    $results = $this->dbHandle->query($queryCmd,array($sessionId))->result_array();
	    if($results[0]['id'] > 0) {
	       $user_session_id = $results[0]['id'];
	       $query = "UPDATE ComparePage_Tracking set userId = ?
	                where sessionId = '$user_session_id'
			and isResponseMade = '0'
			and userId = '0'";
	       $this->dbHandle->query($query,array($userId));
	       return $this->dbHandle->affected_rows();
	 	}   
	}

	function getUserComparedCourses()
   	{
	 	$this->initiateModel('read');
        $queryCmd = "SELECT id,userId,courseId_1,courseId_2,courseId_3,courseId_4,pageKeyId_1,pageKeyId_2,pageKeyId_3,pageKeyId_4,source FROM ComparePage_Tracking
	              WHERE userId != '0'
		      AND isResponseMade = '0'";
		      return $this->dbHandle->query($queryCmd)->result_array();
   	}

   	function updateIsResponseMade($compareTrackingIds)
   	{
		 $this->initiateModel('write');
		 $compareTrackingIdsString = "'".implode("','",$compareTrackingIds)."'";
		 $query = "UPDATE ComparePage_Tracking set isResponseMade = '1'
		          where id in ($compareTrackingIdsString)  and isResponseMade='0'";
		 $this->dbHandle->query($query);
		 return $this->dbHandle->affected_rows();
   	}

}