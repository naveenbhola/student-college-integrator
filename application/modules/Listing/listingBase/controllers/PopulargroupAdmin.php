<?php

class PopulargroupAdmin extends MX_Controller {
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
		$this->populargroupRepo = $this->ListingBaseBuilder->getPopularGroupRepository();
		$this->load->library('Tagging/TaggingCMSLib');
	    $this->taggingCMSLib = new TaggingCMSLib();
	}

	public function index(){
		$this->load->view('listingBase/list',array());
	}

	public function getAllPopularGroups(){
		$this->init();
		$data = $this->populargroupRepo->getAllPopularGroups('object');
		$returnArray = array();

		foreach($data as $key=>$singleObject) {
			$arr = $singleObject->getObjectAsArray();
			$returnArray[$arr['popular_group_id']] = $arr;
		}
		$data  = array('data'=>$returnArray);
		echo json_encode($data);die;
	}

	public function submit(){
		$this->init();
		$request_body = file_get_contents('php://input');
		$data = json_decode($request_body,true);

		$data = $data['result'];
		$data['userId'] = $this->userId;
		$response = $this->populargroupRepo->save($data,$data['mode']);
		if($data['mode'] == "add"){
			$this->taggingCMSLib->addTagsPendingMappingAction("Popular-groups",$response['data']['popular_group_id'],'Add');
		} else{
			$this->taggingCMSLib->addTagsPendingMappingAction("Popular-groups",$response['data']['popular_group_id'],'Edit');
		}
		echo json_encode($response);
	}

	public function getPopularGroupById($id){
		$this->init();
		$data = $this->populargroupRepo->find($id);

		$data = $data->getObjectAsArray();
		$data['hierarchyArray'] = $this->populargroupRepo->getBaseEntitiesByPopularGroups($id);

		echo json_encode(array('data'=>$data));
	}
}

?>