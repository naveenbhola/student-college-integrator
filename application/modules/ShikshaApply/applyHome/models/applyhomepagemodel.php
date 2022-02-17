<?php

class applyhomepagemodel extends MY_Model{
    private $dbHandle = '';
    private $dbHandleMode = '';
    
    
    public function __construct() {
        parent::__construct('ShikshaApply');
    }
    
    // function to be called for getting dbHandle with read/write mode
    private function initiateModel($mode='read'){
        if($this->dbHandle && $this->dbHandleMode == 'write'){
            return ;
        }
        $this->dbHandleMode = $mode;
        if($mode == 'write'){
            $this->dbHandle = $this->getWriteHandle();
        }elseif ($mode == 'read') {
            $this->dbHandle = $this->getReadHandle();
		}
	}

    public function getCounsellorDataByUrl($userEnteredUrl){
        if(empty($userEnteredUrl)){
            return;
        }
        $this->initiateModel('read');
        $this->dbHandle->select('counsellor_id, counsellor_name, counsellor_bio, counsellor_expertise, seoUrl');
        $this->dbHandle->from('RMS_counsellor');
        $this->dbHandle->where('seoUrl',$userEnteredUrl);
        $this->dbHandle->where('status','live');
        $result = $this->dbHandle->get()->result_array();
        return reset($result);
    }

    /*
	 * add counselor reviews
	 */
	public function insertCounselorReviews($batch = array())
	{
		if(count($batch)==0)
		{
			return array();
		}
		// insert the batch
		$this->initiateModel('write');
		$this->dbHandle->insert_batch('counselorReviewsSA',$batch);
	}
	public function getUserIdsByEmail($emails){
		if(empty($emails)){
			return array();
		}
		$this->initiateModel('read');
		$this->dbHandle->select('userid, email, firstname, lastname');
		$this->dbHandle->from('tuser');
		$this->dbHandle->where_in('email', $emails);
		$result = $this->dbHandle->get()->result_array();
		$finalRes = array();
		foreach ($result as $key => $value) {
			$finalRes[strtolower($value['email'])] = array('userId'=>$value['userid'],
											'fname'=>$value['firstname'],
											'lname'=>$value['lastname']
											);
		}
		return $finalRes;
	}

	/*
	 * function to get counselor by review count ordered by their overall rating
	 */
	public function getTopRatedCounselors($numOfCounselors = 3)
	{
		$this->initiateModel('read');
		$this->dbHandle->select("count(cr.id) as reviewCount,(avg(cr.overallRating)) as avgOverallRating, cr.counselorId, c.counsellor_name, c.counsellor_expertise, c.seoUrl, c.counsellor_image", false);
		$this->dbHandle->from("counselorReviewsSA cr");
		$this->dbHandle->join("RMS_counsellor c", 'c.counsellor_id = cr.counselorId', 'inner');
		$this->dbHandle->join("tuser t", 't.userid = c.counsellor_id', 'inner');
		$this->dbHandle->where("t.usergroup",'SAShikshaApply');
		$this->dbHandle->where("cr.status",'live');
		$this->dbHandle->where("c.status",'live');
		$this->dbHandle->group_by("cr.counselorId");
		$this->dbHandle->having("reviewCount >=7","",false);
		$this->dbHandle->order_by("avgOverallRating", "desc");
		$this->dbHandle->limit($numOfCounselors);
		$result = $this->dbHandle->get()->result_array();
		return $result;
	}
		/*
	 * function to get counselor by review count ordered by their overall rating
	 */
	public function getTopReviewsByCounselorIds($counselorIds = array())
	{
		if(count($counselorIds) == 0)
		{
			return array();
		}
		$this->initiateModel('read');
		$this->dbHandle->select("counselorId, userId, StudentName, anonymousFlag, reviewText, length(reviewText) as reviewSize", false);
		$this->dbHandle->from("counselorReviewsSA");
		$this->dbHandle->where_in("counselorId",$counselorIds);
		$this->dbHandle->where("status",'live');
		$this->dbHandle->where("length(reviewText)>20",'',false);
		$this->dbHandle->where("overallRating=10","",false);
		$this->dbHandle->order_by("counselorId", "asc");
		$this->dbHandle->order_by("reviewSize", "desc");
		$this->dbHandle->order_by("addedAt", "desc");
		$result = $this->dbHandle->get()->result_array();
		return $result;
	}

	public function getReviewsCountByCounselorIds($counselorIds = array()){
		if(count($counselorIds) == 0)
		{
			return array();
		}
		$this->initiateModel('read');
		$this->dbHandle->select("count(id) reviewCount, counselorId", false);
		$this->dbHandle->from("counselorReviewsSA");
		$this->dbHandle->where_in("counselorId",$counselorIds);
		$this->dbHandle->where("length(reviewText)>20",'',false);
		$this->dbHandle->where("status",'live');
		$this->dbHandle->group_by("counselorId");
		$result = $this->dbHandle->get()->result_array();
		$finalArr = array();
		foreach ($result as $value) {
			$finalArr[$value['counselorId']] = $value['reviewCount'];
		}
		return $finalArr;
	}

	public function saveExamScoreRecord($data)
	{
		if($data['userId'] == '' || $data['documentUrl'] =='')
		{
			return array();
		}
		$this->initiateModel('write');
		$this->dbHandle->insert('rmcStudentExamsDocuments',$data);
		return $this->dbHandle->insert_id();
	}

	public function getReviewByCounselorId($counselorId,$limit=20,$totalCountFlag=false,$firstReviewId=0){

		if($counselorId == '')
		{
			return array();
		}
		$result =array();
		$this->initiateModel('read');
		if($totalCountFlag){
			$this->dbHandle->select("SQL_CALC_FOUND_ROWS id,userId,anonymousFlag,counselorId,StudentName,overallRating as guidanceRating,knowledgeRating,responseRating,addedAt,reviewText",false);
		}
		else{	
			$this->dbHandle->select("id,userId,anonymousFlag,counselorId,StudentName,overallRating as guidanceRating,knowledgeRating,responseRating,addedAt,reviewText",false);
		}
		$this->dbHandle->from("counselorReviewsSA");
		$this->dbHandle->where("status",'live');
		$this->dbHandle->where("length(reviewText)>=20","",false); // to prevent reviews like "No, good, none, etc."
		$this->dbHandle->where("counselorId",$counselorId);
		if($firstReviewId >0){
			$this->dbHandle->where("id <",$firstReviewId);	
		}
		$this->dbHandle->order_by("addedAt","desc");
		$this->dbHandle->limit($limit);
        
		$result['result'] = $this->dbHandle->get()->result_array();
        foreach($result['result'] as &$row){
            $row['overallRating'] = $this->_getOverAllRating($row);
        }
		if($totalCountFlag){
			$this->dbHandle->select("found_rows() as totalReviewCount",false);
			$total = $this->dbHandle->get()->result_array();
			$result['totalReviewCount'] = $total[0]['totalReviewCount'];
		}
		return $result;
	}
    public function getReviewPostedBy($reviewId){
        if(empty($reviewId)){
            return;
        }
        $this->initiateModel('read');
        $this->dbHandle->select('userId');
        $this->dbHandle->from('counselorReviewsSA');
        $this->dbHandle->where("status",'live');
        $this->dbHandle->where('id',$reviewId);
        $result = $this->dbHandle->get()->result_array();
        return reset($result)['userId'];
    }
    public function deleteReview($reviewId,$userId){
        if(empty($reviewId) || empty($userId)){
            return false;
        }
        $this->initiateModel('write');
        $this->dbHandle->trans_start();
        $this->dbHandle->set('status','deleted');
        $this->dbHandle->where('id',$reviewId);
        $this->dbHandle->update('counselorReviewsSA');
        $this->_trackReviewDeletion($reviewId, $userId);
        $this->dbHandle->trans_complete();
        if ($this->dbHandle->trans_status() === FALSE){
            return false;
        }else{
            return true;
        }
        
    }
    
    private function _trackReviewDeletion($reviewId,$userId){
        $insertData['reviewId'] = $reviewId;
        $insertData['userId'] = $userId;
        $insertData['deletedAt'] = date('Y-m-d H:i:s',time());
        if(isMobileRequest()){
            $insertData['applicationSource'] = 'mobile';
        }else{
            $insertData['applicationSource'] = 'desktop';
        }
        $this->dbHandle->insert('studyAbroadCounsellorReviewDeletionTracking',$insertData);
    }

    public function getUserEnrolledCourses($userIds){
        if(is_array($userIds)&&count($userIds)>0){
            $this->initiateModel('read');
            $this->dbHandle->select('userId, courseId');
            $this->dbHandle->from('rmcCandidateEnrollmentCourse');
            $this->dbHandle->where('enrollmentStatus','yes');
            $this->dbHandle->where('status','live');
            $this->dbHandle->where_in('userId',$userIds);
            $result = $this->dbHandle->get()->result_array();
            $returnArray = array();
            foreach($result as &$row){
                $returnArray[$row['userId']] = $row['courseId'];
            }
            return $returnArray;
        }
    }

    public function getUserAdmittedCourses($userIds){
    	return $this->getUserEnrolledCourses($userIds);
        /*if(is_array($userIds)&&count($userIds)>0){
            $this->initiateModel('read');
            $this->dbHandle->select('userId, courseId');
            $this->dbHandle->from('rmcCandidateFinalizedCourses');
            $this->dbHandle->where('admissionOffered','accepted');
            $this->dbHandle->where('admissionTaken','yes');
            $this->dbHandle->where('status','live');
            $this->dbHandle->where_in('userId',$userIds);
//            $this->dbHandle->group_by('userId');
            
            $result = $this->dbHandle->get()->result_array();
            $returnArray = array();
            foreach($result as &$row){
                $returnArray[$row['userId']] = $row['courseId'];
            }
//            _p($this->dbHandle->last_query());
            return $returnArray;
        }*/
    }
    public function getRatingInfoByCounselorIds($counselorIds = array())
	{
		if(count($counselorIds) == 0)
		{
			return array();
		}
		$this->initiateModel('read');
		$this->dbHandle->select("count(*) as totalReviewCount,
								CAST(avg(overallRating) AS DECIMAL (4,1)) as guidanceRating, 
								CAST(avg(knowledgeRating) AS DECIMAL (4,1)) as knowledgeRating, 
								CAST(avg(responseRating) AS DECIMAL (4,1)) as responseRating, 
								counselorId", false);
		$this->dbHandle->from("counselorReviewsSA");
		$this->dbHandle->where("status",'live');
		$this->dbHandle->where("length(reviewText)>=20","",false);
		if(count($counselorIds)==1){
			$this->dbHandle->where("counselorId",$counselorIds[0]);
		}else{
			$this->dbHandle->where_in("counselorId",$counselorIds);
			$this->dbHandle->group_by("counselorId");
		}
		$result = $this->dbHandle->get()->result_array();
        foreach($result as &$row){
            $row['overAllRating'] = $this->_getOverAllRating($row);
        }
		return $result;
	}
    private function _getOverAllRating($row){
        $overAllRating = $row['guidanceRating'] + $row['knowledgeRating'] + $row['responseRating'];
        $c = 0;
        if(!empty($row['guidanceRating'])){
            $c++;
        }
        if(!empty($row['knowledgeRating'])){
            $c++;
        }
        if(!empty($row['responseRating'])){
            $c++;
        }
        $overAllRating = round($overAllRating/$c,1);
        return $overAllRating;
    }
	public function saveCounselorReview($data = array()){
		if(count($data)==0){
			return array();
		}
		$this->initiateModel('write');
		$this->dbHandle->insert('counselorReviewsSA', $data);
		return true;
	}

	public function getUserRelatedCounsellors($userId, $counselorId = NULL){
		if(empty($userId)){
			return;
		}
		$this->initiateModel('read');
		$result = array();
		$this->dbHandle->distinct();
		$this->dbHandle->select("cand.counsellorId");
		$this->dbHandle->from("rmcCandidates cand");
		$this->dbHandle->join("rmcUserStage rus","cand.userId = rus.userId");
		$this->dbHandle->join('RMS_counsellor rc','rc.counsellor_id=cand.counsellorId','inner');
		$this->dbHandle->where("cand.userId",$userId);
		if($counselorId > 0){
			$this->dbHandle->where("rc.counsellor_id",$counselorId);
		}
		$this->dbHandle->where("rc.status",'live');
		$this->dbHandle->where("rus.status",'live');
		$this->dbHandle->where("cand.status",'live');
		$this->dbHandle->where("stageId >=",3);
		$result = $this->dbHandle->get()->row_array();
		if(!empty($result)){
			return array($result['counsellorId']);
		}else{
			return array();
		}		
	}

	public function getCounselorDetails($counselorIds){
		
		if(is_numeric($counselorIds))
		{
			$counselorIds = array($counselorIds);
		}
		if(empty($counselorIds)){
			return;
		}
		$this->initiateModel('read');
		$counselorDetails = array();
		$userModel = $this->load->model('user/usermodel');
		$counselorBasicInfo = $userModel->getUsersBasicInfoById($counselorIds);
		foreach ($counselorBasicInfo as $key => $value) {
			$counselorBasicInfo[$key]['avtarimageurl'] = MEDIAHOSTURL.($value['avtarimageurl']==''?'/public/images/photoNotAvailable.gif':$value['avtarimageurl']);
		}
		
		$result = array();
		$this->dbHandle->select("counsellor_id,counsellor_name,counsellor_expertise,counsellor_mobile,seoUrl,counsellor_image");
		$this->dbHandle->from("RMS_counsellor");
		$this->dbHandle->where("status",'live');
		$this->dbHandle->where_in("counsellor_id",array_keys($counselorBasicInfo));
		$result = $this->dbHandle->get()->result_array();
		foreach ($result as $key => $value) {
				$value['counselorPageUrl'] = SHIKSHA_STUDYABROAD_HOME.$value['seoUrl'];
				$value['counselorImageUrl'] = ($value['counsellor_image']==''? IMGURL_SECURE.'/public/images/photoNotAvailable.gif':MEDIAHOSTURL.$value['counsellor_image']);

				unset($value['seoUrl']);
				$counselorDetails[$value['counsellor_id']] = $value;
				$counselorDetails[$value['counsellor_id']] = array_merge($value,$counselorBasicInfo[$value['counsellor_id']]);
		}
		
		return $counselorDetails;
	}

	public function userStageCheck($userId){
		if(empty($userId)){
			return false;
		}
		$result = array();
		$this->initiateModel('read');
		$this->dbHandle->select("stageId");
		$this->dbHandle->from("rmcUserStage");
		$this->dbHandle->where("userId",$userId);
		$this->dbHandle->where("stageId >=",3);
		$this->dbHandle->where("stageId !=",10);
		$this->dbHandle->where("status",'live');
		$result = $this->dbHandle->get()->result_array();
		if(count($result)>0){
			return $result[0]['stageId'];
		}
		return false;
	}

	public function getStageName($stageId){
   		if(empty($stageId)){
			return;
		}
		$result = array();
		$this->initiateModel('read');
		$this->dbHandle->select("name");
		$this->dbHandle->from("rmcMasterStages");
		$this->dbHandle->where("stageId",$stageId);
		$this->dbHandle->where("status",'live');
		$result = $this->dbHandle->get()->result_array();
		if($result){
			$result = $result[0]['name'];
		}
		return $result;
    }

    public function getUserOverallRating($row){
    	if(count($row)){
    		$overAllRating = $this->_getOverAllRating($row);
    		return $overAllRating;
    	}
    	return;
    }

    public function getAllSubmittedApplications(){
    	$this->initiateModel('read');
    	$this->dbHandle->select('courseId, modifiedOn', null, true);
    	$this->dbHandle->from('rmcCandidateFinalizedCourses');
    	$this->dbHandle->where_in('status', array('live','history'));
    	$this->dbHandle->where('admissionOffered', 'submitted');
    	$this->dbHandle->group_by('userId, courseId');
    	$this->dbHandle->order_by('modifiedOn', 'desc');
    	$result = $this->dbHandle->get()->result_array();
    	return $result;
    }

    public function getUnivAndCountryDataFromCourseId($courseIds){
    	if(empty($courseIds)){
    		return array();
    	}
    	$this->initiateModel('read');
    	$this->dbHandle->select('university_id, country_id', null, true);
    	$this->dbHandle->from('abroadCategoryPageData');
    	$this->dbHandle->where('status', 'live');
    	$this->dbHandle->where_in('course_id', $courseIds);
    	$this->dbHandle->group_by('course_id');
    	$result = $this->dbHandle->get()->result_array();
    	$finalArr = array();
    	foreach ($result as $key => $value) {
    		$finalArr['universityIds'][$value['university_id']] = $value['university_id'];
    		$finalArr['countryIds'][$value['country_id']] = $value['country_id'];
    	}
    	return $finalArr;
	}
	public function getCounsellingReviewsByIds($reviewIds = array())
	{
		if(empty($reviewIds))
		{
			return array();
		}
		$this->initiateModel('read');
		$this->dbHandle->select('id, userId, studentName, serviceReviewText');
		$this->dbHandle->from('counselorReviewsSA');
		$this->dbHandle->where('status','live');
		$this->dbHandle->where_in('id',$reviewIds);
		$result = $this->dbHandle->get()->result_array();
		return $result;
	}

	public function getStudyAbroadCounsellingRatingData(){
	    $this->initiateModel('read');
	    $this->dbHandle->select('count(id) as ratingCount, CAST(avg(studyAbroadExpRating)/2 AS DECIMAL (4,1)) as overallRating',false);
        $this->dbHandle->from("counselorReviewsSA");
        $this->dbHandle->where("status",'live');
        $this->dbHandle->where("studyAbroadExpRating is not null",null,false);
        $result = $this->dbHandle->get()->result_array();
        return $result[0];
    }

}
