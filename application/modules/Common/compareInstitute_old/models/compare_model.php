<?php
class compare_model extends MY_Model
{
    
   function __construct()
    {
        parent::__construct();
    }
	
    private function initiateModel($operation='read'){
        if($operation=='read') { 
            $this->dbHandle = $this->getReadHandle();
        }
        else {
            $this->dbHandle = $this->getWriteHandle();
        }		
    }
        
    function getNumberOfVotesByCourse($courseIds)
    {
        $numberOfVotes = array();
        
        if(count($courseIds)) {
            $this->initiateModel();
            $sql = "SELECT clicked_course_id , COUNT( * ) AS votes
                    FROM category_action_track
                    WHERE action = 'COMPARE_PAGE_VOTE'
                    AND clicked_course_id IN (".implode(',', $courseIds).")
                    GROUP BY clicked_course_id";
            $query = $this->dbHandle->query($sql);
            $results = $query->result_array();
            if(count($results)) {
                foreach($results as $row) {
                    $numberOfVotes[$row['clicked_course_id']] = $row['votes'];
                }
            }
        }
        
        return $numberOfVotes;
    }

   function comparisionOfPopularCourses($subcat,$source='desktop')
   {
	 if($source == 'mobile'){
		$whereClause = " AND course3_id = 0 AND course4_id = 0 ";
	 }
         $this->initiateModel();
      	 $sql="SELECT course1_id,course1_name,course1_location,course2_id,course2_name,course2_location,course3_id,course3_name,course3_location,course4_id,course4_name,course4_location FROM Popular_Courses_Comparision
              WHERE status='live' and subcategory_id= ? $whereClause ORDER BY RAND() LIMIT 5";
	 $query = $this->dbHandle->query($sql,array($subcat));
         return $query->result_array();
   }

   function getCoursesOfPopularComparisons(){
   		$this->initiateModel();
   		$sql = "SELECT course1_id,course2_id,course3_id,course4_id FROM Popular_Courses_Comparision WHERE status='live'";
   		return $this->dbHandle->query($sql)->result_array();
   }

   function removeInvalidPopularComparisons($courseIdString){
	   	if($courseIdString == ''){
	   		return;
	   	}
   		$this->initiateModel();
   		$sql = "update Popular_Courses_Comparision set status='deleted' where course1_id in ($courseIdString) or course2_id in ($courseIdString) or course3_id in ($courseIdString) or course4_id in ($courseIdString) and status='live'";
   		$this->dbHandle->query($sql);
   		return $this->dbHandle->affected_rows();
   }

   function getStaticURLDetailsFromDB ($courseId1,$courseId2,$courseId3 = 0,$courseId4 = 0){
         $this->initiateModel();
      	 $sql="SELECT course1_id,course1_name,course1_location,course2_id,course2_name,course2_location,course3_id,course3_name,course3_location,course4_id,course4_name,course4_location FROM Popular_Courses_Comparision WHERE course1_id = ? AND course2_id = ? AND course3_id = ? AND course4_id = ? and status = 'live'";
	 $query = $this->dbHandle->query($sql,array($courseId1,$courseId2,$courseId3,$courseId4));
         return $query->result_array();      
   }
   
   function trackComparePage($pageType,$source,$courseString,$userId,$trackeyStr,$defaultKey){
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
 
	 $courseArray = explode(',',$courseString);
	 $courseId_1 = (isset($courseArray[0]) && $courseArray[0]!='')?$courseArray[0]:0;
	 $courseId_2 = (isset($courseArray[1]) && $courseArray[1]!='')?$courseArray[1]:0;
	 $courseId_3 = (isset($courseArray[2]) && $courseArray[2]!='')?$courseArray[2]:0;
	 $courseId_4 = (isset($courseArray[3]) && $courseArray[3]!='')?$courseArray[3]:0;
     
     //added by akhter, added pageKey on course
	 $trackeyStrArr = explode('|||',$trackeyStr);
	 if(count($trackeyStrArr)>0){
	 	foreach ($trackeyStrArr as $value) {
	 		$v= explode('::',$value);
	 			$key[$v[1]] = $v[2];
	 	}
	 }

     $key1 = (isset($key[$courseId_1]) && $key[$courseId_1] !='') ? $key[$courseId_1] : (($courseId_1==0) ? Null : $defaultKey); 
     $key2 = (isset($key[$courseId_2]) && $key[$courseId_2] !='') ? $key[$courseId_2] : (($courseId_2==0) ? Null : $defaultKey); 
     $key3 = (isset($key[$courseId_3]) && $key[$courseId_3] !='') ? $key[$courseId_3] : (($courseId_3==0) ? Null : $defaultKey); 
     $key4 = (isset($key[$courseId_4]) && $key[$courseId_4] !='') ? $key[$courseId_4] : (($courseId_4==0) ? Null : $defaultKey); 

        $isResponseMade = '0';
	 if($userId>0)
	 {
	    $isResponseMade = '1';
	 }
	 $queryCmd = "INSERT INTO ComparePage_Tracking (userId,sessionId,courseId_1,courseId_2,courseId_3,courseId_4,pageKeyId_1,pageKeyId_2,pageKeyId_3,pageKeyId_4,pageType,source,isResponseMade) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";	 
	 $query = $this->dbHandle->query($queryCmd,array($userId,$user_session_id,$courseId_1,$courseId_2,$courseId_3,$courseId_4,$key1,$key2,$key3,$key4,$pageType,$source,$isResponseMade));
   }

   function popularComparisonsMIS($noOfDays = 30,$limitPerSubCat = 20){
	 $this->initiateModel();

	 $date = date("Y-m-d");
	 $date = strtotime("-$noOfDays days",strtotime($date));
	 $date = date ( 'Y-m-j' , $date );
	    
	 $queryCmd = "SELECT l.category_id as SubCategoryId, cb.name as SubCategoryName, count(*) comparisonCount,courseId_1,courseId_2,courseId_3,courseId_4, (Select category_id from listing_category_table where status='live' and listing_type='course' and listing_type_id = courseId_2 LIMIT 1) subCat2, (Select category_id from listing_category_table where status='live' and listing_type='course' and listing_type_id = courseId_3 LIMIT 1) subCat3, (Select category_id from listing_category_table where status='live' and listing_type='course' and listing_type_id = courseId_4 LIMIT 1 ) subCat4,(Select institute_id from course_details where status='live' and course_id = courseId_1 LIMIT 1) instituteId_1,(Select institute_id from course_details where status='live' and course_id = courseId_2 LIMIT 1) instituteId_2,(Select institute_id from course_details where status='live' and course_id = courseId_3 LIMIT 1) instituteId_3,(Select institute_id from course_details where status='live' and course_id = courseId_4 LIMIT 1) instituteId_4,(Select institute_name from institute i,course_details cd where cd.status='live' and i.status='live' and cd.course_id = courseId_1 and i.institute_id = cd.institute_id LIMIT 1) instituteName_1,(Select institute_name from institute i,course_details cd where cd.status='live' and i.status='live' and cd.course_id = courseId_2 and i.institute_id = cd.institute_id LIMIT 1) instituteName_2,(Select institute_name from institute i,course_details cd where cd.status='live' and i.status='live' and cd.course_id = courseId_3 and i.institute_id = cd.institute_id LIMIT 1) instituteName_3,(Select institute_name from institute i,course_details cd where cd.status='live' and i.status='live' and cd.course_id = courseId_4 and i.institute_id = cd.institute_id LIMIT 1) instituteName_4 FROM ComparePage_Tracking c, listing_category_table l, categoryBoardTable cb WHERE c.creationTime > ? AND c.courseId_1 > 0 AND c.courseId_2 > 0 AND l.listing_type = 'course' AND l.status = 'live' AND  l.listing_type_id = c.courseId_1 AND cb.boardId = l.category_id GROUP BY courseId_1,courseId_2,courseId_3,courseId_4 ORDER BY SubCategoryId ASC, comparisonCount desc";
	 $query = $this->dbHandle->query($queryCmd,array($date));

	 //Check if all the Courses are from Same Sub-Category. If yes, put them in Final array
	 $finalArray = array();
	 foreach($query->result_array() as $row){
	    $addInFinalArray = true;
            if($row['subCat2']>0){
	       if($row['subCat2']!=$row['SubCategoryId']){
		  $addInFinalArray = false;
	       }
            }
            if($row['subCat3']>0){
	       if($row['subCat3']!=$row['SubCategoryId']){
		  $addInFinalArray = false;
	       }
            }
            if($row['subCat4']>0){
	       if($row['subCat4']!=$row['SubCategoryId']){
		  $addInFinalArray = false;
	       }
            }
	    if($addInFinalArray){
	       $finalArray[] = $row;
	    }
	 }
	 
	 //Also, for each Sub-Cat, fetch only the rows as per Limit
	 $limitedArray = array();
	 $count = array();
	 foreach($finalArray as $row){
	    $currentSubCat = $row['SubCategoryId'];
	    if($count[$currentSubCat]<$limitPerSubCat){
	       $limitedArray[] = $row;
	    }
	    $count[$currentSubCat] = $count[$currentSubCat] + 1;
	 }
	 
	 //Now, create a CSV
	 $filename = 'comparisonData.csv';
	 $mime = 'text/x-csv';
	 $columnListArray = array();
	 $columnListArray[]='SubCategoryId';
	 $columnListArray[]='SubCategoryName';
	 $columnListArray[]='ComparisonCount';
	 $columnListArray[]='CourseId_1';
	 $columnListArray[]='InstituteId_1';
	 $columnListArray[]='InstituteName_1';
	 $columnListArray[]='CourseId_2';
	 $columnListArray[]='InstituteId_2';
	 $columnListArray[]='InstituteName_2';
	 $columnListArray[]='CourseId_3';
	 $columnListArray[]='InstituteId_3';
	 $columnListArray[]='InstituteName_3';
	 $columnListArray[]='CourseId_4';
	 $columnListArray[]='InstituteId_4';
	 $columnListArray[]='InstituteName_4';

	 $ColumnList = $columnListArray;
	 $csv = '';
	 foreach ($ColumnList as $ColumnName){
	     $csv .= '"'.$ColumnName.'",';
	 }
	 $csv .= "\n";

	 foreach ($limitedArray as $info){
	       $subCategoryName = $info['SubCategoryName'];
	       $instituteName_1 = $info['instituteName_1'];
	       $instituteName_2 = $info['instituteName_2'];
	       $instituteName_3 = $info['instituteName_3'];
	       $instituteName_4 = $info['instituteName_4'];
	       //$csv .= $info['SubCategoryId'].",".str_replace(",","",$subCategoryName).",".$info['comparisonCount'].",".$info['courseId_1'].",".$info['courseId_2'].",".$info['courseId_3'].",".$info['courseId_4'];
	       $csv .= $info['SubCategoryId'].",".str_replace(",","",$subCategoryName).",".$info['comparisonCount'].",".$info['courseId_1'].",".$info['instituteId_1'].",".str_replace(",","",$instituteName_1).",".$info['courseId_2'].",".$info['instituteId_2'].",".str_replace(",","",$instituteName_2).",".$info['courseId_3'].",".$info['instituteId_3'].",".str_replace(",","",$instituteName_3).",".$info['courseId_4'].",".$info['instituteId_4'].",".str_replace(",","",$instituteName_4);
	       $csv .= "\n";
	 }

	 $this->load->library('alerts_client');
	 $alertClientObj = new Alerts_client();
	 $type_id = time();
	 $subject='Popular Comparisons Report';
	 $content = "<p>Hi, </p><p>Please find the attached report for Popular Comparisons on Shiksha. </p><p>- Shiksha Tech.</p>";
	 $email   = array('ankur.gupta@shiksha.com','pranjul.raizada@shiksha.com','anil.narayanan@shiksha.com');
	 for($i=0;$i<count($email);$i++){
		 $attachmentResponse = $alertClientObj->createAttachment("12",$type_id,'COURSE','E-Brochure',$csv,$filename,'text');
		 $attachmentId = $attachmentResponse;
		 $attachmentArray=array();
		 array_push($attachmentArray,$attachmentId);
		 $response = $alertClientObj->externalQueueAdd("12","info@shiksha.com",$email[$i],$subject,$content,$contentType="html",'','y',$attachmentArray);
	 }
	 
   }
   
   //After user has loggedin,update userId for made response
   
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
		      AND isResponseMade = '0'
		      AND (UNIX_TIMESTAMP(now())- UNIX_TIMESTAMP(creationTime))<86400";
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

   function getPopularComparisonForHomepage($subcatId = 23, $noOfDays = 30, $start = 0, $count = 5){
	 $this->initiateModel();

	 $date = date("Y-m-d");
	 $date = strtotime("-$noOfDays days",strtotime($date));
	 $date = date ( 'Y-m-j' , $date );
	    
	 $queryCmd = "SELECT l.category_id as SubCategoryId, count(*) comparisonCount,courseId_1,courseId_2,(Select category_id from listing_category_table where status='live' and listing_type='course' and listing_type_id = courseId_2 LIMIT 1) subCat2, (Select institute_name from institute i,course_details cd where cd.status='live' and i.status='live' and cd.course_id = courseId_1 and i.institute_id = cd.institute_id LIMIT 1) instituteName_1,(Select institute_name from institute i,course_details cd where cd.status='live' and i.status='live' and cd.course_id = courseId_2 and i.institute_id = cd.institute_id LIMIT 1) instituteName_2 FROM ComparePage_Tracking c, listing_category_table l WHERE c.creationTime > ? AND c.courseId_1 > 0 AND c.courseId_2 > 0 AND l.listing_type = 'course' AND l.status = 'live' AND  l.listing_type_id = c.courseId_1 AND l.category_id =? GROUP BY courseId_1,courseId_2 HAVING subcat2=? ORDER BY comparisonCount desc LIMIT ?,?";
	 $query = $this->dbHandle->query( $queryCmd, array($date,$subcatId,$subcatId,$start,$count) );
	 return $query->result_array();      
   }
	
	public function getSubcategoryIdsForStaticComparePageData(){
		$this->initiateModel();
		$this->dbHandle->select('distinct(subcategory_id) as subcategory_id');
		$this->dbHandle->from('Popular_Courses_Comparision');
		$this->dbHandle->where('status','live');
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();
		//_p($result);die;
		return $result;
	}

	public function getNewMappingForSubcategoryId($subcategoryIds){
		$this->initiateModel();
		$this->dbHandle->select('oldsubcategory_id as subcategory_id, stream_id, substream_id, specialization_id, base_course_id, education_type, delivery_method, credential,level');
		$this->dbHandle->from('base_entity_mapping');
		$this->dbHandle->where_in('oldsubcategory_id',$subcategoryIds);
		$this->dbHandle->where('oldspecializationid',0);
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();
		//_p($result);die;
		return $result;	
	}

	public function migrateSubcategoryToNewMapping($subCategoryToNewMappingData){
		$this->initiateModel('write');
		$this->dbHandle->update_batch('Popular_Courses_Comparision',$subCategoryToNewMappingData,'subcategory_id');
		//echo $this->dbHandle->last_query();
	}
}
