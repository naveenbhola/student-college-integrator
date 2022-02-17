<?php 

/**
 * Responsible for handling the CMS level operations for the Certification Provider
 */
class CertificationProviderAdmin extends MX_Controller {

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
		$this->cpRepo = $this->ListingBaseBuilder->getCertificateProviderRepository();	
		$this->load->library('Tagging/TaggingCMSLib');
	    $this->taggingCMSLib = new TaggingCMSLib();	
	}

	public function index(){
		$this->load->view('listingBase/list',array());
	}

	public function getAllCertificationProviders(){
		$this->init();
		$data = $this->cpRepo->findAll();
		$returnArray = array();

		foreach($data as $key=>$singleObject) {
			$arr = $singleObject->getObjectAsArray();
			$returnArray[$arr['certificate_provider_id']] = $arr;
		}
		$data  = array('data'=>$returnArray);
		echo json_encode($data);die;
	}

	public function getCertificationProvider($id){
		$this->init();
		$data = $this->cpRepo->find($id);
		$data = $data->getObjectAsArray();
		$courseMapping = $this->cpRepo->getBaseCourseIdsbyCertProviders($id);		
		$data['courseMapping'] = $courseMapping[$id];
		$data = array('data' =>$data);
		echo json_encode($data);die;
	}

	public function submit(){
		$this->init();
		$request_body = file_get_contents('php://input');
		$data = json_decode($request_body,true);

		$data = $data['result'];
		$data['userId'] = $this->userId;
		$response = $this->cpRepo->save($data,$data['mode']);
		if($data['mode'] == "add"){
			$this->taggingCMSLib->addTagsPendingMappingAction("Certificate-Provider",$response['data']['certificate_provider_id'],'Add');
		} else{
			$this->taggingCMSLib->addTagsPendingMappingAction("Certificate-Provider",$response['data']['certificate_provider_id'],'Edit');
		}
		echo json_encode($response);
	}
}