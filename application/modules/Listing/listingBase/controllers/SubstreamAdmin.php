<?php

class SubstreamAdmin extends MX_Controller {

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
		$this->substreamRepository = $this->ListingBaseBuilder->getSubstreamRepository();
		$this->streamRepository = $this->ListingBaseBuilder->getStreamRepository();
		$this->hierarchyRepo = $this->ListingBaseBuilder->getHierarchyRepository();

	    $this->load->library('Tagging/TaggingCMSLib');
	    $this->taggingCMSLib = new TaggingCMSLib();


	}

	public function index(){
		$this->load->view('listingBase/list',array());
	}

	public function getAllStreams(){
		$this->init();
		$data = $this->streamRepository->findAll();
		$returnArray = array();

		foreach($data as $key=>$singleObject) {
			$arr = $singleObject->getObjectAsArray();
			$returnArray[$arr['stream_id']] = $arr;
		}
		$data  = array('data'=>$returnArray);
		echo json_encode($data);die;
	}

	public function getAllSubstreams(){
		$this->init();
		$data = $this->substreamRepository->findAll();
		$returnArray = array();

		foreach($data as $key=>$singleObject) {
			$arr = $singleObject->getObjectAsArray();
			$returnArray[$arr['substream_id']] = $arr;
		}
		$data  = array('data'=>$returnArray);
		echo json_encode($data);die;
	}

	public function getSubstream($id){
		$this->init();
		$data = $this->substreamRepository->find($id);

		$data = array('data' => $data->getObjectAsArray());
		echo json_encode($data);die;
	}

	public function getSubstreamsByStream($streamIds){
		$this->init();
		$streamIds = explode(',',$streamIds);
		$data = $this->hierarchyRepo->getSubstreamSpecializationByStreamId($streamIds,1);
		echo json_encode(array('data'=>$data));
	}

	public function submit(){
		$this->init();
		$request_body = file_get_contents('php://input');
		$data = json_decode($request_body,true);

		$data = $data['result'];
		$data['userId'] = $this->userId;
		$response = $this->substreamRepository->save($data,$data['mode']);

		if($data['mode'] == 'add'){
            $response['hierarchyId'] = $this->hierarchyRepo->insertIntoHierarchyTable(array('streamId'=>$data['substreamPrimaryStream'],'substreamId' => $response['data']['substream_id'],'userId'=>$this->userId));
            $this->taggingCMSLib->addTagsPendingMappingAction("Substream",$response['data']['substream_id'],'Add');
        }else{
            $this->taggingCMSLib->addTagsPendingMappingAction("Substream",$response['data']['substream_id'],'Edit');
        }
		echo json_encode($response);
	}
}