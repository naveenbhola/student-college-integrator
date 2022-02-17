<?php
class ratemychancemodel extends MY_Model {
    private $dbHandle = '';
    private $dbHandleMode = '';
    
    function __construct() {
		parent::__construct('Listing');
    }
    
    private function initiateModel($mode = "write"){
		if($this->dbHandle && $this->dbHandleMode == 'write')
		    return;

		$this->dbHandleMode = $mode;
		$this->dbHandle = NULL;
		if($mode == 'read') {
			$this->dbHandle = $this->getReadHandle();
		} else {
			$this->dbHandle = $this->getWriteHandle();
		}
    }
    
	/*
	 * get all course ids on which user has made rate my chance response
	 * Note: this also includes the shiksha apply courses on which a counselor has rated chances
	 * @params: $userid (of the user currently logged in)
	 * @return: array of course ids
	 */
	public function getRMCCoursesByUser($userId, $limitOffset = 0)
	{
		if(!($userId>0)) {
			return array();
		}
		$this->initiateModel("read");
		$this->dbHandle->select('SQL_CALC_FOUND_ROWS distinct lms.listing_type_id',false);
		$this->dbHandle->from('tempLMSTable lms');
		$this->dbHandle->join('course_details cd','lms.listing_type_id = cd.course_id','inner');
		$this->dbHandle->join('rmcUserCourseRating rucr','rucr.courseId = cd.course_id AND cd.status = rucr.status AND rucr.userId = lms.userId','left');
		$this->dbHandle->where('lms.userId',$userId);
		$this->dbHandle->where('lms.listing_type','course');
		$this->dbHandle->where('rucr.rating <>5','',false);
		$this->dbHandle->where('(lms.action in ("rate_my_chances","rate_my_chances_sa_mobile")','',false);
		$this->dbHandle->or_where('rucr.rating >0)','',false);
		$this->dbHandle->where( 'cd.status' , 'live');
		$this->dbHandle->limit(RMC_TAB_TUPLE_COUNT,$limitOffset); 
		$queryRes =$this->dbHandle->get()->result_array();
		
		//error_log($limitOffset."BOKUWA:".$this->dbHandle->last_query());
		// get total records as well
		$this->dbHandle->select('found_rows() as rows',FALSE);
		$data = $this->dbHandle->get()->result_array();
		
		$result = array(
						'courseIds'	=> array_map(function($a){return $a['listing_type_id'];},$queryRes),
						'totalCount'=> $data[0]['rows']
						);
		
		return $result;
	}

	public function getRMCCountByUser($userId){
        if(!($userId>0)) {
            return false;
        }
        $this->initiateModel("read");
        $this->dbHandle->select('count(*) as rmc_count',false);
        $this->dbHandle->from('rmcUserCourseRating rucr');
        $this->dbHandle->where('rucr.userId',$userId);
        $this->dbHandle->where('rucr.rating <>5','',false);
        $this->dbHandle->where( 'rucr.status' , 'live');
        $queryRes =$this->dbHandle->get()->result_array();
        return $queryRes[0]['rmc_count'];
    }

	public function recordResponse($data){
		$this->initiateModel('write');
		$this->dbHandle->insert('rmcResponses',$data);
	}
	
	public function checkAndAddUserStage($userId){
		$this->initiateModel('write');
		$this->dbHandle->select("*");
		$this->dbHandle->where("userId",$userId);
		$result = $this->dbHandle->get("rmcUserStage")->result_array();
		if(count($result) > 0){
			return false;
		}
		$values = array();
		$values['userId'] = $values['modifiedBy'] = $userId;
		$values['stageId'] = 1;
		$values['addedOn'] = $values['modifiedOn'] = date('Y-m-d H:i:s');
		$values['status'] = 'live';
		$this->dbHandle->insert('rmcUserStage',$values);
		return true;
	}
	
	public function addNewUserRating($userId,$courseId){
		$this->initiateModel('write');
		$this->dbHandle->select('id');
		$this->dbHandle->from('rmcUserCourseRating');
		$this->dbHandle->where('userId',$userId);
		$this->dbHandle->where('courseId',$courseId);
		$this->dbHandle->where('status','live');
		$res = reset(reset($this->dbHandle->get()->result_array()));
		if(!empty($res)){
			return false;
		}
		$values = array();
		$values['rmcUserCourseRatingId'] = Modules::run('common/IDGenerator/generateId','rmcUserCourseRating');
		$values['userId'] = $userId;
		$values['courseId'] = $courseId;
		$values['rating'] = 0;
		$values['addedOn'] = $values['modifiedOn'] = date('Y-m-d H:i:s');
		$values['addedBy'] = $values['modifiedBy'] = $userId;
		$values['status'] = 'live';
		$this->dbHandle->insert('rmcUserCourseRating',$values);
		return true;
	}
	//This function will return courses which have valid rating means other than "No rating Given"
	public function getUserRatings($userId){
		if(!is_int($userId) || $userId <= 0){
			return array();
		}
		$this->initiateModel('read');
		$this->dbHandle->select('courseId,rating');
		$this->dbHandle->from('rmcUserCourseRating');
		$this->dbHandle->where('rating <> 5','',false);
		$this->dbHandle->where('userId',$userId);
		$this->dbHandle->where('status','live');
		$res = $this->dbHandle->get()->result_array();
		$result = array();
		foreach($res as $row){
			$result[$row['courseId']] = $row['rating'];
		}
		return $result;
	}
	
	public function getUserRatingsWithNoRatingGivenAsWell($userId){
		if(!is_int($userId) || $userId <= 0){
			return array();
		}
		$this->initiateModel('write');
		$this->dbHandle->select('courseId,rating');
		$this->dbHandle->from('rmcUserCourseRating');
		//$this->dbHandle->where('rating <> 5','',false);
		$this->dbHandle->where('userId',$userId);
		$this->dbHandle->where('status','live');
		$res = $this->dbHandle->get()->result_array();
		$result = array();
		foreach($res as $row){
			$result[$row['courseId']] = $row['rating'];
		}
		return $result;
	}
		
	public function getUserStage($userId){
		if(!is_int($userId) || $userId <= 0){
			return array();
		}
		$this->initiateModel('read');
		$this->dbHandle->select('stageId');
		$this->dbHandle->from('rmcUserStage');
		$this->dbHandle->where('userId',$userId);
		$this->dbHandle->where('status','live');
		$res = $this->dbHandle->get()->result_array();
		$val = reset(reset($res));
		if(empty($val)){
			return 0;
		}
		return (integer)$val;
	}

	public function saveUserEnrolmentForCounseling($userId,$courseId){
		if($userId <= 0){
			return false;
		}
		if($courseId <= 0){
			return false;
		}
		$this->initiateModel('write');
		
		//fetch rmcUserCourseRatingId
		$this->dbHandle->select('rmcUserCourseRatingId');
		$this->dbHandle->from('rmcUserCourseRating');
		$this->dbHandle->where('userId',$userId);
		$this->dbHandle->where('courseId',$courseId);
		$this->dbHandle->where('status','live');
		$result = $this->dbHandle->get()->result_array();
		//_p($this->dbHandle->last_query());
		
		if(count($result)>0)
		{
			$rmcUserCourseRatingId = reset(reset($result));;
		}else{
			//Send a mail for error to satech group
			$this->CI = get_instance();
			$commonStudyAbroadLib   = $this->CI->load->library('common/studyAbroadCommonLib');
            $errorSubject = 'saveUserEnrolmentForCounseling failed :';
            $errorMsg .= 'UserId : '.$userId.'<br/>';
			$errorMsg .= 'courseId : '.$courseId.'<br/>';
			$errorMsg .= 'Referer : '.$_SERVER['HTTP_REFERER'].'<br/>';
			$errorMsg .= '<br/><br/>Regards,<br/>SA Team';
            $commonStudyAbroadLib->selfMailer($errorSubject,$errorMsg);
			return false;
		}
		if($rmcUserCourseRatingId!=''){
		//insert into rmcStudentEnrolmentForCounsellingSession
		$values = array();
		$values['rmcUserCourseRatingId'] = $rmcUserCourseRatingId;
		$values['addedOn'] = date('Y-m-d H:i:s');

		$this->dbHandle->insert('rmcStudentEnrolmentForCounsellingSession',$values);
		}
		return true;
	}

	public function checkUserIsCandidate($counselorId){
    	$this->initiateModel('read');
    	$this->dbHandle->select('counsellor_id');
    	$this->dbHandle->from('rmcUserStage rus');
    	$this->dbHandle->join('rmcCandidates rc', 'rus.userId = rc.userId');
    	$this->dbHandle->join('RMS_counsellor counsellor', 'counsellor.counsellor_id = rc.counsellorId');
    	$this->dbHandle->where('rus.userId', $counselorId);
    	$this->dbHandle->where('rus.status', 'live');
    	$this->dbHandle->where('rc.status', 'live');
    	$this->dbHandle->where('counsellor.status', 'live');
    	$this->dbHandle->where_not_in('stageId', array(0,1,2,8,9,10));
    	$result = $this->dbHandle->get()->row_array();
    	return count($result) > 0 ? true : false;
    }
}
