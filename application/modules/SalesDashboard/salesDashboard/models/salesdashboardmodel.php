<?php

class salesdashboardmodel extends MY_Model
{

	private function initiateModel($operation='read'){
		if($operation=='read'){
			$this->dbHandle = $this->getReadHandle();
		}else{
		    $this->dbHandle = $this->getWriteHandle();
		}		
	}

	public function getClientDeliveryUniversities(){
		$this->initiateModel('read');
		$this->dbHandle->select("distinct(u.name)");
		$this->dbHandle->select("r.universityId");
		$this->dbHandle->from("rmcCounsellorUniversityMapping r");
		$this->dbHandle->join("university u","u.university_id = r.universityId","inner");
		$this->dbHandle->where("r.status","live");
		$this->dbHandle->where("u.status","live");
		$val = $this->dbHandle->get()->result_array();
		return $val;
	}

	public function getCoursesOfUniversities($univIds){
		if(empty($univIds)){
			return array();
		}
		$this->initiateModel('read');
		$this->dbHandle->select('distinct(course_id), university_id, country_id');
		$this->dbHandle->where('status','live');
		$this->dbHandle->where_in('university_id',$univIds);
		return $this->dbHandle->get('abroadCategoryPageData')->result_array();
	}

	public function getSubscriptionIdsForCourses($courseIds){
		if(empty($courseIds)){
			return array('ids'=>array(),'clients'=>array());
		}
		$this->initiateModel('read');
		$this->dbHandle->select("listing_type_id as courseId");
		$this->dbHandle->select("subscriptionId");
		$this->dbHandle->select("username as clientId");
		$this->dbHandle->from("listings_main");
		$this->dbHandle->where("listing_type","course");
		$this->dbHandle->where_in("listing_type_id",$courseIds);
		$this->dbHandle->where("status","live");
		$res = $this->dbHandle->get()->result_array();
		foreach($res as $row){
			$result[$row['courseId']] = $row['subscriptionId'];
			$client[$row['courseId']] = $row['clientId'];
		}
		return array('ids'=>$result,'clients'=>$client);
	}

	public function getCoursePaidResponses($courseIds){
		if(empty($courseIds)){
			return array();
		}
		$this->initiateModel('read');
		$this->dbHandle->select("count(1) as responseCount, listing_type_id as courseId");
		$this->dbHandle->from("tempLMSTable");
		$this->dbHandle->where("listing_subscription_type","paid");
		$this->dbHandle->where("listing_type","course");
		$this->dbHandle->where_in("listing_type_id",$courseIds);
		$this->dbHandle->group_by("listing_type_id");
		$result = $this->dbHandle->get()->result_array();
		return $result;
	}

	public function getRMCUsers($courseIds){
		if(empty($courseIds)){
			return array();
		}
		$this->initiateModel('read');
		$this->dbHandle->select("userId, listing_type_id as courseId");
		$this->dbHandle->from("tempLMSTable");
		$this->dbHandle->where("listing_type","course");
		$this->dbHandle->where_in("action",array("rate_my_chances","rate_my_chances_sa_mobile"));
		$this->dbHandle->where_in("listing_type_id",$courseIds);
		$res = $this->dbHandle->get()->result_array();
		return $res;
	}

	public function getExamsForUsers($userIds){
		if(empty($userIds)){
			return array();
		}
		$this->initiateModel('read');
		$this->dbHandle->select("UserId, Name");
		$this->dbHandle->from("tUserEducation");
		$this->dbHandle->where_in("UserId",$userIds);
		$this->dbHandle->where("status","live");
		$this->dbHandle->where("Level","Competitive exam");
		$res = $this->dbHandle->get()->result_array();
		$result = array();
		foreach($res as $row){
			$result[$row['UserId']][] = $row['Name'];
		}
		return $result;
	}

	public function getCoursesFinalizedForUsers(){
		$this->initiateModel('read');
		$this->dbHandle->select("userId, courseId, admissionOffered");
		$this->dbHandle->from("rmcCandidateFinalizedCourses");
		$this->dbHandle->where("status","live");
		return $this->dbHandle->get()->result_array();
	}

	public function getSOPFinalizedUsers($userIds){
		if(empty($userIds)){
			return array();
		}
		$this->initiateModel('read');
		$this->dbHandle->select("ru.userId");
		$this->dbHandle->from("rmcUserStage ru");
		$this->dbHandle->join("rmcMasterStages rs","ru.stageId = rs.stageId","inner");
		$this->dbHandle->where("ru.status","live");
		$this->dbHandle->where("rs.status","live");
		$this->dbHandle->where("rs.stageOrder >=","6");
		$this->dbHandle->where_in("ru.userId",$userIds);
		return $this->dbHandle->get()->result_array();
	}

	public function getNamesOfCountries($countryIds){
		if(empty($countryIds)){
			return array();
		}
		$this->initiateModel('read');
		$this->dbHandle->select("countryId, name");
		$this->dbHandle->from("countryTable");
		$this->dbHandle->where_in("countryId",$countryIds);
		$res = $this->dbHandle->get()->result_array();
		foreach($res as $row){
			$ret[$row['countryId']] = $row['name'];
		}
		return $ret;
	}

	public function getCounsellorsOfUniversities($univIds){
		$this->initiateModel("read");
		$this->dbHandle->select("c.counsellor_name, r.universityId");
		$this->dbHandle->from("rmcCounsellorUniversityMapping r");
		$this->dbHandle->join("RMS_counsellor c","c.counsellor_id = r.counsellorId","inner");
		$this->dbHandle->where("r.status","live");
		$this->dbHandle->where("c.status","live");
		$this->dbHandle->where_in("r.universityId",$univIds);
		$res = $this->dbHandle->get()->result_array();
		$ret = array();
		foreach($res as $row){
			$ret[$row['universityId']] = $row['counsellor_name'];
		}
		return $ret;
	}
}