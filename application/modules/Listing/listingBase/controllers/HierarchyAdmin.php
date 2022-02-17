<?php 

/**
 * Responsible for handling the CMS level operations for the Hierarchy
 */
class HierarchyAdmin extends MX_Controller {

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
		$this->hierarchyRepo = $this->ListingBaseBuilder->getHierarchyRepository();
	}

	public function index(){
		$this->load->view('listingBase/list',array());
	}

	public function getAllHierarchies($outputFormat = 'json'){
		$hierarchymodel = $this->load->model('listingBase/hierarchymodel');
		$data = $hierarchymodel->getCompleteHierarchyTableData();
		
		foreach ($data as $key => $value) {
			if($value['national'] && !$value['abroad']) {
				$data[$key]['scope'] = 'national';
			}
			elseif(!$value['national'] && $value['abroad']) {
				$data[$key]['scope'] = 'abroad';
			}
			elseif($value['national'] && $value['abroad']) {
				$data[$key]['scope'] = 'both';
			}

			if($value['academic'] && !$value['testprep']) {
				$data[$key]['course_type'] = 'academic';
			}
			elseif(!$value['academic'] && $value['testprep']) {
				$data[$key]['course_type'] = 'testprep';
			}
			elseif($value['academic'] && $value['testprep']) {
				$data[$key]['course_type'] = 'both';
			}
		}
		$returnArray = array('data' => $data);

		if($outputFormat == 'array') {
			return $returnArray;
		}
		echo json_encode($returnArray); die;
	}

	public function submit(){
		$this->init();
		$request_body = file_get_contents('php://input');
		$data = json_decode($request_body,true);
		$data = $data['result'];

		$arr['streamId'] = $data['stream'];
		$arr['substreamId'] = $data['substream'];
		$arr['specializationId'] = $data['specialization'];
		$arr['scope'] = $data['scope'];
		$arr['courseType'] = $data['courseType'];
		$arr['userId'] = $this->userId;

		$data = $this->hierarchyRepo->insertIntoHierarchyTable($arr);
		

		echo json_encode($data);die;
	}


	/**
	 * Get the hierarchy tree at any point in time
	 *
	 * @param int $getIdNames Flag indicating if the hierarchy should contain the <code>name</code> and <code>alias</code> information as well
	 *
	 * @see \HierarchyRepository::createHierarchyTree for the tree generation logic
	 */
	public function getHierarchyTree($getIdNames = 0,$outputFormat = 'json') {
		$this->init();
		$flatData = $this->getAllHierarchies('array');
		$data = $this->hierarchyRepo->createHierarchyTree($flatData['data'], $getIdNames);
		$returnArray = array('data' => $data);
		if($outputFormat === 'array'){
			return $returnArray;
		}
		echo json_encode($returnArray);
	}
} ?>