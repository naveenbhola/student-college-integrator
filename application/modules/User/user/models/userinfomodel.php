<?php
/**
 * Model class file for User Info
 */

/**
 * Model class for User Info
 */
class UserInfoModel extends MY_Model
{
    /**
     * Database Handler
     *
     * @var object
     */
    private $dbHandle = null;
	
	/**
	 * Constructor Function
	 */
	function __construct()
	{
		parent::__construct('User');
	}
	
	/**
	 * Function to initiate the model in read/write mode
	 *
	 * @param string $mode
	 * @param striing $module
	 */
	private function initiateModel($mode = 'read', $module = '')
	{
		if($mode == 'read') {
			$this->dbHandle = empty($module) ? $this->getReadHandle() : $this->getReadHandleByModule($module);
		} else {
			$this->dbHandle = empty($module) ? $this->getWriteHandle() : $this->getWriteHandleByModule($module);
		}
	}
    
    /**
     * Function to get user's desired course along with sub-category and category from profile
     * Test-prep user's are excluded
     *
     * @param array $userIds userIds of users whose details need to be extracted
     * @return array $data desired course, sub-category and category of users
     */ 
    public function getDesiredCourseFromProfile($userIds = array())
    {
        Contract::mustBeNonEmptyArrayOfIntegerValues($userIds,'User IDs');
        
        $this->initiateModel();
        
        $data = array();
        
		$sql = "SELECT DISTINCT TUP.UserId as userId,TUP.DesiredCourse as ldbCourseId,LCSM.categoryID as subCategoryId,TCSM.categoryId
                FROM tUserPref TUP
                INNER JOIN tCourseSpecializationMapping TCSM ON TUP.DesiredCourse = TCSM.SpecializationId
                INNER JOIN LDBCoursesToSubcategoryMapping LCSM ON TUP.DesiredCourse = LCSM.ldbCourseID
                WHERE TUP.UserID in (?)
                AND TUP.Status = 'live'
                AND TCSM.Status = 'live'
                AND LCSM.status = 'live'";
		
		
		$query = $this->dbHandle->query($sql,array($userIds));
		foreach($query->result_array() as $row) {
			$data[$row['userId']] = array(
                                            'ldbCourseId' => $row['ldbCourseId'],
                                            'subCategoryId' => $row['subCategoryId'],
                                            'categoryId' => $row['categoryId']
                                        );
		}
		return $data;
    }
    
    /**
     * Get user's desired course along with sub-category and category from last response
     * Test-prep user's are excluded
     *
     * @param array $userIds userIds of users whose details need to be extracted
     * @return array $data desired course, sub-category and category of users
     */ 
    public function getDesiredCourseFromLastResponse($userIds = array())
    {
        Contract::mustBeNonEmptyArrayOfIntegerValues($userIds,'User IDs');
        
        $this->initiateModel();
	$data = array();
        
        $lastResponses = $this->getLastResponseMade($userIds);
	if(count($lastResponses) > 0) {
	    $LDBCourseData = $this->getLDBCourseFromClientCourse($lastResponses);
	    
	    $data = array();
	    foreach($lastResponses as $userId => $lastResponse) {
		if($LDBCourseData[$lastResponse]) {
		    $data[$userId] = $LDBCourseData[$lastResponse];
		}
	    }
	}
        
	return $data;
    }
    
    /**
     * Get last response made by each user in a set
     * Only responses made on courses are considered
     *
     * @param array $userIds userIds of last responses
     * @return array $data data of last responses made on courses
     */    
    public function getLastResponseMade($userIds = array())
    {
        Contract::mustBeNonEmptyArrayOfIntegerValues($userIds,'User IDs');
        $this->initiateModel();
	
    	global $highPriorityActions;
        $data = array();
            
    	$sql = 'SELECT userId, courseId
                    FROM latestUserResponseData
                    WHERE userId IN (?)
                    AND action IN (?)';
            
        $query = $this->dbHandle->query($sql,array($userIds,$highPriorityActions));        
    	foreach($query->result_array() as $row) {
    	    $data[$row['userId']] = $row['courseId'];
    	}
    	
    	$userIds = array_diff($userIds, array_keys($data));
    	
    	if(count($userIds)) {
    	    $sql = 'SELECT userId, courseId
    		    FROM latestUserResponseData
    		    WHERE userId IN (?)
    		    AND action NOT IN (?)';
    	    
    	    $query = $this->dbHandle->query($sql,array($userIds,$highPriorityActions));
    	    foreach($query->result_array() as $row) {
    		$data[$row['userId']] = $row['courseId'];
    	    }
    	}
    	
    	return $data;
    }
    
    /**
     * Get LDB course id for given client course id
     *
     * @param array $clientCourseIds list of client course ids
     * @return array $data mapping of client course id with LDB course id, category id and subcategory id
     */ 
    public function getLDBCourseFromClientCourse($clientCourseIds = array())
    {
        Contract::mustBeNonEmptyArrayOfIntegerValues($clientCourseIds,'Client Course IDs');
        
        $this->initiateModel();
        $data = array();
        $abroadCourses = array();
	$nationalCourses = array();
	
	$sql = "SELECT course_id, category_id, sub_category_id, ldb_course_id
		FROM abroadCategoryPageData
		WHERE course_id IN (?)
		AND status = 'live'";
	$query = $this->dbHandle->query($sql,array($clientCourseIds));
	if($query->num_rows() > 0) {
	    foreach($query->result_array() as $row) {
		$abroadCourses[] = $row['course_id'];
		
		if(!$data[$row['course_id']]) {
		    $data[$row['course_id']] = array(
                                                        'ldbCourseId' => $row['ldb_course_id'],
                                                        'subCategoryId' => $row['sub_category_id'],
                                                        'categoryId' => $row['category_id']
                                                    );
		}
	    }
	}
	
	$nationalCourses = array_diff($clientCourseIds, $abroadCourses);
	
	if(count($nationalCourses)) {
	    $sql = "SELECT CLM.clientCourseID,CLM.LDBCourseID,LSM.categoryID as subCategoryId,CBT.parentId as categoryId
		    FROM clientCourseToLDBCourseMapping CLM
		    INNER JOIN LDBCoursesToSubcategoryMapping LSM ON LSM.ldbCourseID = CLM.LDBCourseID
		    INNER JOIN categoryBoardTable CBT ON CBT.boardId = LSM.categoryID
		    WHERE CLM.status = 'live'
		    AND LSM.status = 'live'
		    AND CLM.clientCourseID IN (?)";
	    
	    $query = $this->dbHandle->query($sql,array($clientCourseIds));
	    foreach($query->result_array() as $row) {
		if(!$data[$row['clientCourseID']]) {
		    $data[$row['clientCourseID']] = array(
                                                        'ldbCourseId' => $row['LDBCourseID'],
                                                        'subCategoryId' => $row['subCategoryId'],
                                                        'categoryId' => $row['categoryId']
                                                    );
		}
	    }
	}
        
	return $data;
    }
    
    /**
     * Get category/subcategory from last question asked
     *
     * @param array $userIds to get category id of
     * @return array $data mapping of user id, category id and subcategory id
     */ 
    public function getCategoryFromLastQuestionAsked($userIds = array())
    {
        Contract::mustBeNonEmptyArrayOfIntegerValues($userIds,'User IDs');
        $this->initiateModel();
        $data = array();
        
        $sql = "SELECT M1.userId, M1.msgId, MCT.categoryId
                FROM messageTable M1
                LEFT JOIN messageTable M2 ON (M1.userId = M2.userId AND M1.msgId < M2.msgId) 
                LEFT JOIN messageCategoryTable MCT ON MCT.threadId = M1.msgId
                WHERE M2.msgId IS NULL 
                AND M1.parentId =0
                AND M1.fromOthers =  'user'
                AND M1.mainAnswerId = -1
                AND M1.status =  'live'
                AND MCT.categoryId !=1
                AND M1.userId IN (?)";

        $query = $this->dbHandle->query($sql,array($userIds));
		foreach($query->result_array() as $row) {
            if($data[$row['userId']]) {
                if($data[$row['userId']]['categoryId'] > $row['categoryId']) {
                    $data[$row['userId']]['subCategoryId'] = $data[$row['userId']]['categoryId'];
                    $data[$row['userId']]['categoryId'] = $row['categoryId'];
                }
                else {
                    $data[$row['userId']]['subCategoryId'] = $row['categoryId'];
                }
            }
            else {
                $data[$row['userId']] = array('categoryId' => $row['categoryId']);
            }
		}
		return $data;
    }
	
    /**
     * Get user's desired country from profile
     *
     * @param array $userIds userids to get desired countries for
     * @return array $data mapping of user id and country id
     */ 
    public function getDesiredCountriesFromProfile($userIds = array())
    {
        Contract::mustBeNonEmptyArrayOfIntegerValues($userIds,'User IDs');
        
        $this->initiateModel();
        
        $data = array();
        
        $sql = "SELECT DISTINCT UserId as userId, CountryId as countryId
                FROM tUserLocationPref
                WHERE status = 'live'
                AND UserId IN (?)";

        $query = $this->dbHandle->query($sql,array($userIds));
		foreach($query->result_array() as $row) {
                $data[$row['userId']][] = $row['countryId'];
		}
		return $data;
    }
	
    /**
     * Get user's desired country from last response made
     *
     * @param array $userIds userids to get desired countries for
     * @return array $data mapping of user id and country id
     */ 
    public function getDesiredCountriesFromLastResponse($userIds = array())
    {
        Contract::mustBeNonEmptyArrayOfIntegerValues($userIds,'User IDs');
        
        $this->initiateModel();
		$data = array();
        
        $lastResponses = $this->getLastResponseMade($userIds);
		if(count($lastResponses) > 0) {
			$countryData = $this->getCountryFromClientCourse($lastResponses);
			
			$data = array();
			foreach($lastResponses as $userId => $lastResponse) {
				if($countryData[$lastResponse]) {
					$data[$userId][] = $countryData[$lastResponse];
				}
			}
		}
        
		return $data;
    }
	
    /**
     * Get countries corresponding to courses
     *
     * @param array $clientCourseIds  course ids to get country id for
     * @return array $data mapping of course id and country id
     */ 
    public function getCountryFromClientCourse($clientCourseIds = array())
    {
        Contract::mustBeNonEmptyArrayOfIntegerValues($clientCourseIds,'Client Course IDs');
        
        $this->initiateModel();
        $data = array();
        
        $sql = "SELECT CD.course_id as courseId,ILT.country_id as countryId
                FROM course_details CD
                INNER JOIN institute_location_table ILT ON ILT.institute_id = CD.institute_id
                WHERE CD.status = 'live'
                AND ILT.status = 'live'
                AND CD.course_id IN (?)";
        
        $query = $this->dbHandle->query($sql,array($clientCourseIds));
		foreach($query->result_array() as $row) {
            if(!$data[$row['courseId']]) {
                $data[$row['courseId']] = $row['countryId'];
            }
		}
		return $data;
    }
	
    /**
     * Get countries corresponding to users
     *
     * @param array $userIds  user ids to get country id for
     * @return array $data mapping of user id and country id
     */
	public function getCountriesFromLastQuestionAsked($userIds = array())
    {
        Contract::mustBeNonEmptyArrayOfIntegerValues($userIds,'User IDs');
        $this->initiateModel();
        $data = array();
        
        $sql = "SELECT M1.userId, M1.msgId, MCT.countryId
                FROM messageTable M1
                LEFT JOIN messageTable M2 ON (M1.userId = M2.userId AND M1.msgId < M2.msgId) 
                LEFT JOIN messageCountryTable MCT ON MCT.threadId = M1.msgId
                WHERE M2.msgId IS NULL 
                AND M1.parentId =0
                AND M1.fromOthers =  'user'
                AND M1.mainAnswerId = -1
                AND M1.status =  'live'
                AND MCT.countryId != 1
                AND M1.userId IN (?)";

        $query = $this->dbHandle->query($sql,array($userIds));
		foreach($query->result_array() as $row) {
				$data[$row['userId']][] = $row['countryId'];
		}
		return $data;
    }

    /**
    * Extracts user data:- name, email, mobile, courses and institute id 
    * Used for offline response interface
    * @param : userId
    * @return array user data:- name, email, mobile, courses and institute id 
    */

    public function extractOfflineResponse($userInfo, $userField){
        mail('teamldb@shiksha.com', 'function -extractOfflineResponse called after Unsubscribe removed from tuserflag', print_r($_SERVER,true));
        return;
        $this->initiateModel();

        $sql = "SELECT a.email,  a.firstname, a.lastname, a.mobile, a.displayname, a.password, c.courseTitle, d.institute_name
                FROM tuser a
                LEFT JOIN tempLMSTable b ON (a.userid = b.userId AND b.listing_type =  'course')
                LEFT JOIN course_details c ON (b.listing_type_id = c.course_id AND c.status = 'live')
                LEFT JOIN institute d ON (c.institute_id = d.institute_id AND d.status = 'live')
                LEFT JOIN tUserPref tf ON a.userid = tf.UserId
                LEFT JOIN tuserflag tuf ON a.userid = tuf.userId
                WHERE a.$userField = ? AND tf.DesiredCourse = 2
                AND tuf.mobileverified ='1' AND tuf.isTestUser = 'NO' 
                AND tuf.hardbounce = '0' AND tuf.softbounce = '0' 
                AND tuf.ownershipchallenged = '0' AND tuf.abused = '0'
                AND tuf.unsubscribe = '0' AND a.usergroup = 'user' 
                GROUP BY b.listing_type_id";

         return $this->dbHandle->query($sql, $userInfo)->result_array();
    }

    /**
    * Extracts user competitive exam  
    * @param : userIds is an array containing userids
    * @return array user data:- name, email, mobile, courses and institute id 
    */

    public function getUserExamInfo($userIds = array()){
        if(empty($userIds)){
            return array();
        }

        $this->initiateModel();
        $sql = "SELECT UserId, Name, Marks, MarksType AS Type FROM tUserEducation WHERE Level = 'Competitive exam' AND UserId IN (?)";
        return $this->dbHandle->query($sql,array($userIds))->result_array();
    }

    public function getUserDetailsForCSV($userIdList){
                // code not in use

        if(empty($userIdList)){
            return array();
        }

        if(is_array($userIdList)){
            $userIdList = explode(',', $userIdList);
        }

        $this->initiateModel();
        $sql = "select tuser.userId, firstname,lastname, email, isdCode, mobile, country, locality, city, usercreationdate, isNDNC from tuser join tuserflag on tuser.userId = tuserflag.userId
                 where tuser.userId IN (".$userIdList.")";
        
        return $this->dbHandle->query($sql)->result_array();
    }

    function getUserExamName($userIdList){
        //code not in use
        if(empty($userIdList)){
            return array();
        }

        if(is_array($userIdList)){
            $userIdList = explode(',', $userIdList);
        }

        $this->initiateModel();
        $sql = "select name,userId from tUserEducation where level = 'Competitive exam' and userId IN (".$userIdList.")";
        
        return $this->dbHandle->query($sql)->result_array();
    }

}
