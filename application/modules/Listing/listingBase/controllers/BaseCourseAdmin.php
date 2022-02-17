<?php 

class BaseCourseAdmin extends MX_Controller {
	function __construct(){
		$validity = $this->checkUserValidation();
		if(($validity == "false" )||($validity == "")) {
		    header('location:/enterprise/Enterprise/loginEnterprise');
		    exit();
		}
		else{
			$usergroup = $validity[0]['usergroup'];
			if($usergroup !="cms" && $usergroup !="listingAdmin")
			{
			    header("location:/enterprise/Enterprise/unauthorizedEnt");
			    exit();
			}
			$this->userId = $validity[0]['userid'];
		}
	}

	function init(){
		$this->load->builder('ListingBaseBuilder', 'listingBase');
		$this->ListingBaseBuilder    = new ListingBaseBuilder();
		$this->basecourseRepo = $this->ListingBaseBuilder->getBaseCourseRepository();
		$this->load->library('Tagging/TaggingCMSLib');
	    $this->taggingCMSLib = new TaggingCMSLib();
	}

	public function index(){
		$this->load->view('listingBase/list',array());
	}

	public function getAllBasecourses($outputFormat = 'json'){
		$this->init();
		$data = $this->basecourseRepo->getAllBaseCourses('object');
		$returnArray = array();

		foreach($data as $key=>$singleObject) {
			$arr = $singleObject->getObjectAsArray();
			$returnArray[$arr['base_course_id']] = $arr;
		}
		$data  = array('data'=>$returnArray);
		if($outputFormat === 'array'){
			return $data;
		}
		echo json_encode($data);die;
	}

	public function getBaseCourse($baseCourseIds)
	{
		$this->init();
		$data = $this->basecourseRepo->find($baseCourseIds);

		$data = $data->getObjectAsArray();

		$data['hierarchyArray'] = $this->basecourseRepo->getBaseEntitiesByBaseCourseIds($baseCourseIds);

		echo json_encode(array('data' => $data));
	}

	public function saveBaseCourse($basecourseId=0){

		$request_body = file_get_contents('php://input');
		$data = json_decode($request_body,true);
		$data = $data['result'];
		$data['userId'] = $this->userId;

		$this->init();

		$response = $this->basecourseRepo->saveBaseCourse($data);
		if($data['mode'] == "add"){
			$this->taggingCMSLib->addTagsPendingMappingAction("Base-course",$response['data']['base_course_id'],'Add');
		} else{
			$this->taggingCMSLib->addTagsPendingMappingAction("Base-course",$response['data']['base_course_id'],'Edit');
		}
		echo json_encode($response);

	}

	/**
	 * Get attributes such as
	 * <ul><li>Course Credential</li><li>Course Level</li></ul>
	 * for the base courses available in the system.
	 *
	 * @see BaseAttributeLibrary::getValuesForAttributeByName
	 */
	public function getCourseAttributes($outputFormat = 'json'){
		$this->load->library('listingBase/BaseAttributeLibrary');
		$baseAttributelibrary = new BaseAttributeLibrary();

		$inputArr = array('Credential', 'Course level', 'Education Type', 'Medium/Delivery Method');

		$data = $baseAttributelibrary->getValuesForAttributeByName($inputArr);
		
		if($outputFormat === 'json'){
			echo json_encode(array('data' => $data));
		}
		return array('data'=>$data);
		
	}

	public function getPopularCourses($outputFormat = 'json'){
		$this->init();
		$data = $this->basecourseRepo->getAllPopularCourses('object');
		$returnArray = array();

		foreach($data as $key=>$singleObject) {
			$arr = $singleObject->getObjectAsArray();
			$returnArray[$arr['base_course_id']] = $arr;
		}
		$data  = array('data'=>$returnArray);
		if($outputFormat === 'array'){
			return $data;
		}
		echo json_encode($data);
	}

	public function getNonPopularCourses(){
		$this->init();
		$data = $this->basecourseRepo->getAllNonPopularCourses('object');
		$returnArray = array();

		foreach($data as $key=>$singleObject) {
			$arr = $singleObject->getObjectAsArray();
			$returnArray[$arr['base_course_id']] = $arr;
		}
		$data  = array('data'=>$returnArray);
		if($outputFormat === 'array'){
			return $data;
		}
		echo json_encode($data);
	}

	public function getBasecoursesByMultipleBaseEntities(){
		$this->init();
		$request_body = file_get_contents('php://input');
		$data = json_decode($request_body,true);
		$hierarchyArr = $data['hierarchyArr'];
		$data = $this->basecourseRepo->getBasecoursesByMultipleBaseEntities($hierarchyArr,1,'object');
		$returnArray = array();

		foreach($data as $key=>$singleObject) {
			$arr = $singleObject->getObjectAsArray();
			$returnArray[$arr['base_course_id']] = $arr;
		}
		$data  = array('data'=>$returnArray);
		if($outputFormat === 'array'){
			return $data;
		}
		echo json_encode($data);
	}
}