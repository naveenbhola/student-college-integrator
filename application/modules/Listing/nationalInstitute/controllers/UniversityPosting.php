<?php

class UniversityPosting extends MX_Controller{

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
			// _p($this->userId); die;
		}
	}


	public function init(){
		$this->load->config('nationalInstitute/instituteStaticAttributeConfig');
		$this->institutePostingLib = $this->load->library('nationalInstitute/InstitutePostingLib');
		$this->institutepostingmodel = $this->load->model('nationalInstitute/institutepostingmodel');
	}

	public function viewList(){
		$this->load->view('enterprise/adminBase/adminLayout');

	}

	public function create(){
		$this->load->view('enterprise/adminBase/adminLayout');
	}
}


?>