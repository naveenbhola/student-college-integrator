<?php


class ResponseDeliveryCriteriaLib {

	private $CI;

	function __construct(){
		$this->CI = & get_instance();
		$this->model = $this->CI->load->model('enterprise/responsedeliverycriteriamodel');
	}

	function getCourseFromUniversityInstitute($id, $type){
		if (empty($id) || empty($type) || !is_numeric($id)){
			return 0;
		}
		$returnData = array();
		$title = $this->model->getInstituteUniversityName($id,$type);
		$title = $title[0]["listing_title"];
		if ($type == 'university_national'){
			$type = 'university';
		}
		$returnData[$type] = array('listing_type_id'=>$id,'listing_title'=>$title);
		$courseData = $this->model->getCourseFromUniversityInstitute($id,$type);
		$courseIds = array_column($courseData, 'course_id');
		$paidCourse = $this->model->getPaidCourses($courseIds);
		$returnData["courses"] = $paidCourse;
		return $returnData;
	}

	function getResponseDeliveryCriteria($clientId){
		if (empty($clientId) || !is_numeric($clientId)){
			return;
		}
		else{
			$retData = array();
			$data = $this->model->getResponseDeliveryCriteria($clientId);
			foreach ($data as $key => $value) {
				if ($value["action_value"] ==0){
					$value["action_value"] = "notshow";
				}
				else{
					$value["action_value"] = "show";
				}
				$pushData=array(	"id" =>$value["entity_id"],
									"actionType" => $value["action_type"],
									"actionValue" =>strval($value["action_value"]),
									"locationId" =>$value["location_ids"]
								);
				if ($value["entity_type"] == "course"){
					$retData["course"][($value["entity_id"]).($value["action_type"])] = $pushData;
				}
				else if ($value["entity_type"] == "institute"){
					$retData["institute"][$value["entity_id"].($value["action_type"])] = $pushData;
				}
				else{
					$retData["university"][$value["entity_id"].($value["action_type"])] = $pushData;
				}
			}
		}
		return $retData;
	}

	function getClientAllInstitutes($clientId){
		
		if (empty($clientId) || !is_numeric($clientId)){			
			return;
		}

		$courseData = array();
		$courseData = $this->model->getClientAllInstitutes($clientId);
		return $courseData;
	}

	public function saveRdcFormData($post)
	{
		$data = $post['data'];
		$instituteList = json_decode($post['allInstituteListJson']);
		$universityList = json_decode($post['allUniversityListJson']);
		$courseList = json_decode($post['allCourseListJson']);
		$client_id = $data['client_id'];

		if(empty($client_id) && !is_numeric($client_id))
		{
			return;
		}
		unset($data['client_id']);

		$finalData = array();
		$mapForTypeId = array();

		foreach ($courseList as $course)
		{
			$mapForTypeId['course'][] = $course;
			
			$this->makeFinalData("course",$course,"cvr",$data,$client_id,"CVR",$finalData);

			if(isset($data['course_nvr_'.$course]))
			{
				$finalData[] = array("client_id"=>$client_id,"entity_type"=>"course","entity_id"=>$course,"action_type"=>"NVR","action_value"=>"1","location_ids"=>$data['course_nvr_'.$course],"status"=>"live");
			}
		}

		foreach ($universityList as $university)
		{
			$mapForTypeId['university'][] = $university;
			$this->makeFinalData("university",$university,"ivr",$data,$client_id,"IVR",$finalData);
		}

		foreach ($instituteList as $institute)
		{
			$mapForTypeId['institute'][] = $institute;
			$this->makeFinalData("institute",$institute,"ivr",$data,$client_id,"IVR",$finalData);
		}

		foreach ($mapForTypeId as $key => $value) 
		{
			$this->model->updateOldFormData($key,$value,$client_id);
		}		
		
		$this->model->saveRdcFormData($finalData);	

	}

	public function makeFinalData($listing_type,$value,$type,$data,$client_id,$action_type,&$finalData)
	{
		if($data[$listing_type.'_clpresponse_'.$value]==1)
		{
			if(isset($data[$listing_type.'_'.$type.'_'.$value]))
			{
				$finalData[] = array("client_id"=>$client_id,"entity_type"=>$listing_type,"entity_id"=>$value,"action_type"=>$action_type,"action_value"=>"1","location_ids"=>$data[$listing_type.'_'.$type.'_'.$value],"status"=>"live");
			}
		}
		else
		{
			$finalData[] = array("client_id"=>$client_id,"entity_type"=>$listing_type,"entity_id"=>$value,"action_type"=>$action_type,"action_value"=>"0","location_ids"=>"","status"=>"live");
		}
	}

	public function getVirtualCityMappingForSearch() {
		$this->_ci = & get_instance();
		$this->_ci->load->builder('SearchBuilder', 'search');
		$this->_ci->load->builder("LocationBuilder", "location");
		$this->_ci->load->helper('search/SearchUtility');
		$this->_ci->load->model('search/SearchModel', '', true);
		$this->_ci->config->load('search_config');
		$this->_ci->load->library('listing/cache/ListingCache');
		$this->config = $this->_ci->config;
		$listingCache 			= new ListingCache();
		$virtualCityMappingData = $listingCache->getVirtualCityMappingForSearch();
		if(empty($virtualCityMappingData)) {
			$searchModel  		= new SearchModel();
			$virtualCityMappingData = $searchModel->getVirtualCityMappingForSearch();
			if(!empty($virtualCityMappingData)) {
				$listingCache->storeVirtualCityMappingForSearch($virtualCityMappingData);
			}
		}
		return $virtualCityMappingData;
	}

	function getClientAllDetails($clientId){
		
		if (empty($clientId) || !is_numeric($clientId)){			
			return;
		}

		$courseData = array();
		$courseData = $this->model->getClientAllInstitutes($clientId);

		$userModel = $this->CI->load->model('user/usermodel');
		$userDetails = $userModel->getNameByUserId($clientId);

		$data = array();
		$data['courseData'] = $courseData;
		$data['userDetails'] = $userDetails;

		return $data;
	}

	function disableResponseCriteria($nationalCourses = array(), $nationalInstitutes = array()) {

		if(empty($nationalCourses) && empty($nationalInstitutes)) {
			return;
		}

		if(!empty($nationalCourses)) {
			$this->model->updateOldCriteria($nationalCourses, 'course');
		}

		if(!empty($nationalInstitutes)) {
			$this->model->updateOldCriteria($nationalInstitutes, 'institute');
		}

	}

	function getPaidCourses($courseId, $handle) {
		if (empty($courseId)) {
			return;
		}
		$paidCourse = $this->model->getPaidCourses($courseId, $handle);
		return $paidCourse;
	}

}


?>
