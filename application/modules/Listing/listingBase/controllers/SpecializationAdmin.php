<?php

class SpecializationAdmin extends MX_Controller {

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
		$this->specializationRepository = $this->ListingBaseBuilder->getSpecializationRepository();
		$this->hierarchyRepo = $this->ListingBaseBuilder->getHierarchyRepository();
		$this->load->library('Tagging/TaggingCMSLib');
	    $this->taggingCMSLib = new TaggingCMSLib();
	}

	public function index(){
		$this->load->view('listingBase/list',array());
	}

	public function getAllSpecializations(){
		$this->init();
		$data = $this->specializationRepository->findAll();
		$returnArray = array();

		foreach($data as $key=>$singleObject) {
			$arr = $singleObject->getObjectAsArray();
			$returnArray[$arr['specialization_id']] = $arr;
		}
		
		$data  = array('data'=>$returnArray);
		echo json_encode($data);die;
	}

	public function getSpecialization($id){
		$this->init();
		$data = $this->specializationRepository->find($id);

		$data = array('data' => $data->getObjectAsArray());
		echo json_encode($data);die;
	}

	public function getSpecializationsBasedOnStreamSubstream($streamIds,$substreamIds){
		$substreamIds = explode(',', $substreamIds);
		$this->init();
		// $this->hierarchyRepo->
	}

	public function submit(){
		$this->init();
		$request_body = file_get_contents('php://input');
		$data = json_decode($request_body,true);

		$data = $data['result'];
		$data['userId'] = $this->userId;
		$response = $this->specializationRepository->save($data,$data['mode']);

		if(empty($data['specializationPrimarySubstream'])){
			$data['specializationPrimarySubstream'] = 0;
		}

		if($data['mode'] == 'add'){
			$response['hierarchyId'] = $this->hierarchyRepo->insertIntoHierarchyTable(array('streamId' => $data['specializationPrimaryStream'],'substreamId' => $data['specializationPrimarySubstream'], 'specializationId' => $response['data']['specialization_id'],'userId'=>$this->userId));
			$this->taggingCMSLib->addTagsPendingMappingAction("Specialization",$response['data']['specialization_id'],'Add');
		}else{
			$this->taggingCMSLib->addTagsPendingMappingAction("Specialization",$response['data']['specialization_id'],'Edit');
		}

		echo json_encode($response);
	}
}