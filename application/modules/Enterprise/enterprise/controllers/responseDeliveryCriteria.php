<?php

class responseDeliveryCriteria extends MX_Controller{
	private $userStatus = 'false';

	function __constuct(){
	}

	private function _init(){
		$this->userStatus = $this->checkUserValidation();
		$this->ResponseDeliveryCriteriaLib = $this->load->library('enterprise/ResponseDeliveryCriteriaLib');

		if(($this->userStatus == "false") || ($this->userStatus == "")){
			header('location:/enterprise/Enterprise/loginEnterprise');
            exit();
		}
 
 		$adminUsers = array('cms','sums','enterprise');
		if (!in_array($this->userStatus[0]['usergroup'],$adminUsers)) {
            header("location:/enterprise/Enterprise/unauthorizedEnt");
        }
	}

	private function getHeaderData(){
		$data['prodId'] = 1050;
		$data['validateuser'] = $this->userStatus;
		$this->load->library("enterprise_client");
		$entObj = new Enterprise_client();
        $data['headerTabs'] = $entObj->getHeaderTabs(1,$this->userStatus[0]['usergroup'], $this->userStatus[0]['userid']);
        return $data;
	}

	function manageRDC(){
		$data = array();
		
		$this->_init();
		$data = $this->getHeaderData();	
		$this->load->view('courseResponseAccess/manageManualAccess',$data);
	}

	function getCourseFromUniversityInstitute($id,$type,$clientId){
		ini_set("memory_limit",'512M');
		$this->_init();
		if (empty($id) || empty($type) || !is_numeric($id) || !is_numeric($clientId)){
			return 0;
		}
		$data["courseData"] = $this->ResponseDeliveryCriteriaLib->getCourseFromUniversityInstitute($id,$type);
		$data["cityState"] = $this->getCityData();
		$data["dbValues"] = $this->ResponseDeliveryCriteriaLib->getResponseDeliveryCriteria($clientId);
		return $this->load->view('courseResponseAccess/rdcCriteria',$data);
	}

	function getClientAllInstitutes() {

		$clientId = $this->input->post('client_id');
		$clientId = trim($clientId);
		if ($clientId <= 0) {			
			return;
		}

        $responseDeliveryCriteriaLib = $this->load->library('enterprise/ResponseDeliveryCriteriaLib');
        $getClientAllDetails = $responseDeliveryCriteriaLib->getClientAllDetails($clientId);

        $data = array();
		$data["institutes"] = $getClientAllDetails['courseData'];
		$data["userDetails"] = $getClientAllDetails['userDetails'][0];
		$data['clientId'] = $clientId;

		return $this->load->view('courseResponseAccess/getCourseFromInstituteUniversity',$data);
	}

	function saveRdcFormData()
	{
		if(empty($_POST))
		{
			return;
		}
		$responseDeliveryCriteriaLib = $this->load->library('enterprise/ResponseDeliveryCriteriaLib');
		$responseDeliveryCriteriaLib->saveRdcFormData($_POST);
		echo "success";

	}

	function getCityData(){

		$this->load->library(array('category_list_client', 'LDB_Client'));
		$virtualCity = $this->ResponseDeliveryCriteriaLib->getVirtualCityMappingForSearch();
        $cityState['virtualCityMapping'] = $virtualCity;
        $childParentArray = array();
        foreach ($virtualCity as $key => $value) {
        	$filledArray= array_fill_keys($value, $key);
        	foreach ($filledArray as $original => $virtual) {
        		$childParentArray[$original]=$virtual;
        	}
        }
		$cityState['childVirtualMapping'] = $childParentArray;


        $categoryClient = new Category_list_client();
        $ldbObj = new LDB_client();
        $cityListTier1 = $categoryClient->getCitiesInTier($appId, 1, 2);
        $cityListTier2 = $categoryClient->getCitiesInTier($appId, 2, 2);
        $cityState['cityList'] = array_merge($cityListTier1, $cityListTier2);
        $cityState['virtualCities'] = $cityListTier1;
        $cityState['cityList_tier2'] = $cityListTier2;
        $cityState['cityList_tier1'] = $cityListTier1;
        $cityState['country_state_city_list'] = json_decode($ldbObj->sgetCityStateList(12), true);
    	
    	return $cityState;
    }
}
