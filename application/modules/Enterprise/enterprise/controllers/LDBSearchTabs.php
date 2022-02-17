<?php

/**
 *  Class LDB Search Access Tabs
 *
 * @author
 * @package 
 *
 */

class LDBSearchTabs extends MX_Controller
{
	var $tabId = 785;
    
	function init() {
		$this->userStatus = $this->checkUserValidation();
		$this->load->helper(array('form', 'url','date','image','shikshaUtility'));    
	}

	public function ldbSearchAccessInterface(){
		$validateuser = $this->checkUserValidation();
		$cmsUserInfo = $this->cmsUserValidation();
		 if($cmsUserInfo['usergroup']!='cms'){
		    header("location:/enterprise/Enterprise/disallowedAccess");
		    exit();
		}
		$headerComponents = array(
				'css' => array('headerCms','raised_all', 'footer','mainStyle','smart'),
				'js' => array('user','tooltip','newcommon','header','common'),
				'jsFooter' => array('scriptaculous','utils'),
				'title' => "",
				'product' => '',
				'displayname' => (isset($validateuser[0]['displayname']) ? $validateuser[0]['displayname'] : ""),
				);
		$data = array();
		$cmsUserInfo['prodId'] 	=   $this->tabId;
		$data["tab"] = $extraParam;

		echo $this->load->view('enterprise/headerCMS', $headerComponents,true);
		echo $this->load->view('enterprise/cmsTabs', $cmsUserInfo, true);
		echo $this->load->view('enterprise/searchLDBClientUser', true);
	}

	public function showClientDetails()
	{
		$this->init();
		$request['cmsUserInfo'] = $this->cmsUserValidation();
		if($request['cmsUserInfo']['usergroup']!='cms'){
			header("location:/enterprise/Enterprise/disallowedAccess");
			exit();
		}
		
		$request['clientId'] = $this->input->post('clientId');

		$this->load->library('sums_manage_client');
		$objSumsManage = new Sums_Manage_client();
		$response['users'] =  $objSumsManage->getUserForQuotation($this->appId,$request);		
		$response['cmsUserId'] = $request['cmsUserInfo']['userid'];
		$this->load->model('enterprise/ldbsearchtabsaccessrightsmodel');
		$ldbsearchtabsaccessrightsmodel = new ldbsearchtabsaccessrightsmodel();
		$ldbTabsAccessSet = $ldbsearchtabsaccessrightsmodel->getClientLDBSearchAccessTabs($request['clientId']);
		
		
		$data['ldbTabsAccessSet'] = $ldbTabsAccessSet;
		foreach($response['users'] as $key=>$value){
			$clientId = $key;
		}

		$this->load->library('sums_product_client');
		$objSumsProduct = new Sums_Product_client();

		/**
		* this logic checks if LDB Search Access Tabs to be made open for the client based on the DB Subscription 127 available for the client
		*  
		*/
	
			echo $this->load->view('enterprise/selectedUserDetails',$response,true);							
			require FCPATH.'globalconfig/LDBSearchTabsCoursesList.php';
			
			$data['LDBCourseList'] = $LDBCourseList;
			$data['clientId'] = $clientId;
			$data['other_array'] = $other_array;
			
			echo $this->load->view('enterprise/LdbSearchAccess',$data,true);
		
	}


	public function storeLDBCategories(){
	    
		$categoriesSelected =  $this->input->post('category',true);
		$clientId = $this->input->post('clientId',true);

		$this->load->model('enterprise/ldbsearchtabsaccessrightsmodel');
		$ldbsearchtabsaccessrightsmodel = new ldbsearchtabsaccessrightsmodel();
		
		$response = $ldbsearchtabsaccessrightsmodel->updateAlreadySetClientSpecificCategories($clientId);
		    		
		$response = $ldbsearchtabsaccessrightsmodel->setClientSpecificCategories($clientId,$categoriesSelected);
		echo "<span style=\"padding-left:2px; color:#000000;font-size:16px;\">
			Categories selected for the mentioned client have been submitted successfully.
			</span>";
		
	}


}
