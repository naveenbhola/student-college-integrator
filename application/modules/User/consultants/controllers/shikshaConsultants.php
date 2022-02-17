<?php 
   /*

   Copyright 2007 Info Edge India Ltd

   */
class shikshaConsultants extends MX_Controller {
        private $userStatus = 'false';
        function init(){
                $this->load->helper(array('url','form'));
                $this->load->library(array('common/ajax','alerts/alerts_client','consultants/consultant_client'));
                $this->userStatus = $this->checkUserValidation();
        }
	function cmsUserValidation()
    {
        $validity = $this->checkUserValidation();
        global $logged;
        global $userid;
        global $usergroup;
        $thisUrl = $_SERVER['REQUEST_URI'];
        if(($validity == "false" )||($validity == "")) {
            $logged = "No";
            header('location:/enterprise/Enterprise/loginEnterprise');
            exit();
        } else {
            $logged = "Yes";
            $userid = $validity[0]['userid'];
            $usergroup = $validity[0]['usergroup'];
            if ($usergroup=="user" || $usergroup == "requestinfouser" || $usergroup == "quicksignupuser" || $usergroup == "tempuser" || $usergroup == "fbuser") {
                header("location:/enterprise/Enterprise/migrateUser");
                exit;
            }
            if( !(($usergroup == "cms")) ){
                header("location:/enterprise/Enterprise/unauthorizedEnt");
                exit();
            }
        }
        $this->load->library('enterprise/enterprise_client');
        $entObj = new Enterprise_client();
        $headerTabs = $entObj->getHeaderTabs(1,$validity[0]['usergroup'],$validity[0]['userid']);
        $this->load->library('sums/sums_product_client');
        $objSumsProduct =  new Sums_Product_client();
        $myProductDetails = $objSumsProduct->getProductsForUser(1,array('userId'=>$userid));
        $returnArr['userid']=$userid;
        $returnArr['usergroup']=$usergroup;
        $returnArr['logged'] = $logged;
        $returnArr['thisUrl'] = $thisUrl;
        $returnArr['validity'] = $validity;
        $returnArr['headerTabs'] = $headerTabs;
        $returnArr['myProducts'] = $myProductDetails;
        return $returnArr;
    }

    function checkUniqueConsultant()
    {
    }

    function getConsultantsByKeyword($keyword='+',$start='0',$rows='7',$sortBy='')
    {
        $this->init();
	$userArray = $this->cmsUserValidation();
	$ConsultantClientObj = new consultant_client();
	$consultantArray = $ConsultantClientObj->getConsultantListByKeyword($keyword,$start,$rows,$sortBy);
	// error_log("Shirish".print_r($consultantArray,true));
        echo json_encode($consultantArray);
    }
    function getConsultantsByFilter($cityId='-1',$countryId='-1',$categoryId='-1',$start='0',$rows='7',$sortBy='')
    {
        $this->init();
	$userArray = $this->cmsUserValidation();
	$ConsultantClientObj = new consultant_client();
	$consultantArray = $ConsultantClientObj->getConsultantList($cityId,$countryId,$categoryId,$start,$rows,$sortBy);
	// error_log("Shirish".print_r($consultantArray,true));
        echo json_encode($consultantArray);
    }

        function addConsultant(){
        $this->init();
        $cmsUserInfo = $this->cmsUserValidation();

	$consultant_name = $this->input->post('consultant_name');
	$consultant_email = $this->input->post('consultant_email');
	$consultant_mobile = $this->input->post('consultant_mobile');
	$consultant_address = $this->input->post('consultant_address');
	$consultant_city = $this->input->post('consultant_city');
	$consultant_countries = $this->input->post('ctry');
	$consultant_categories = $this->input->post('consultant_category');
	$consultant_sourceOfFund = $this->input->post('sfund');
	$consultant_leadStartDate = $this->input->post('start_date');
	$consultant_leadEndDate = $this->input->post('end_date');

 	$consultant_id = "-1";
	$post_consultant_id = $this->input->post('consultant_id', true);
	if(isset($post_consultant_id))
	{
		$consultant_id = $post_consultant_id;
		error_log('Shirish  '.$consultant_id);
	}
	$checkNameStatus = $this->checkConsultantName($consultant_id,$consultant_name);
	if($checkNameStatus != "new")
	{
		die(json_encode(array('failure'=>'Name already exists')));
	}

        $appId = 1;
	error_log(print_r($_POST,true). " ..... SHIRISH");
        $validateuser = $this->userStatus;
        $ConsultantClientObj = new consultant_client();
	if($consultant_id != "-1")
	{
		$status = $ConsultantClientObj->editConsultant($consultant_name,$consultant_email,$consultant_mobile,$consultant_address,$consultant_city,$consultant_categories,$consultant_countries,$consultant_leadStartDate,$consultant_leadEndDate,$consultant_sourceOfFund,$validateuser[0]['userid'],$consultant_id);
	}
	else
	{
		$status = $ConsultantClientObj->addConsultant($consultant_name,$consultant_email,$consultant_mobile,$consultant_address,$consultant_city,$consultant_categories,$consultant_countries,$consultant_leadStartDate,$consultant_leadEndDate,$consultant_sourceOfFund,$validateuser[0]['userid']);
	}
	//header("Location:".SHIKSHA_HOME."/enterprise/Enterprise/index/29");
	die(json_encode(array('success'=>"/enterprise/Enterprise/index/29")));
    }

        function checkConsultantName($consId, $consultantName){
        $this->init();
        $cmsUserInfo = $this->cmsUserValidation();
        $appId = 1;
        $validateuser = $this->userStatus;
	$this->load->library('categoryList/category_list_client');
	$categoryClient = new Category_list_client();
        $ConsultantClientObj = new consultant_client();
	$status = $ConsultantClientObj->checkConsultantName($consId, $consultantName);
	return($status);
    }

	function deleteConsultant($whichTab,$consId){
        $this->init();
        $cmsUserInfo = $this->cmsUserValidation();
        $appId = 1;
        $validateuser = $this->userStatus;
        $ConsultantClientObj = new consultant_client();
	$status = $ConsultantClientObj->deleteConsultant($consId);
	header("Location:".SHIKSHA_HOME."/enterprise/Enterprise/index/29");
    }

        function showConsultantsForm($consultantId = '-1'){
        $this->init();
        $cmsUserInfo = $this->cmsUserValidation();
	$this->load->library('categoryList/category_list_client');
	$categoryClient = new Category_list_client();
        $ConsultantClientObj = new consultant_client();
	$cityListTier1 = $categoryClient->getCitiesInTier($appId,1,2);
	$cityListTier2 = $categoryClient->getCitiesInTier($appId,2,2);
	$cityListTier3 = $categoryClient->getCitiesInTier($appId,0,2);
	$displayData['cityTier1'] = $cityListTier1;
	$displayData['cityTier2'] = $cityListTier2;
	$displayData['cityTier3'] = $cityListTier3;
	$allCategoryList = $categoryClient->getCategoryTree(1,1); 
	$displayData['allCategories'] = $allCategoryList;
	if($consultantId != '-1')
	{
		$getConsultantData = $ConsultantClientObj->getConsultantData($consultantId);
		if(isset($getConsultantData[0]))
			$displayData['consultantData'] = $getConsultantData[0];
	}
        $this->load->view('consultants/studyAbroadCMS',$displayData);
	}
        function showConsultantsCRUDForm($consultantId = '-1'){
        $this->init();
        $cmsUserInfo = $this->cmsUserValidation();
        //$cmsUserInfo = $this->checkFlowCase($clientUserId,$cmsUserInfo);
        $clientId = $cmsUserInfo['clientId'];
        $cmsPageArr = array();
        $cmsPageArr = $cmsUserInfo;
	$displayData['headerTabs'] =  $cmsUserInfo['headerTabs'];
        /*$cmsPageArr['validateuser'] = $cmsUserInfo['validity'];
        $cmsPageArr['pageTitle'] = "Consultant Profile.";
        $cmsPageArr['viewType'] = 1;

        $cmsPageArr['country_list'] = $categoryClient->getCountries('1');

        $cmsPageArr['formPostUrl'] = '/enterprise/ShowForms/addInstitute';
        $cmsPageArr['prodId'] = '7';
        //echo "<pre>";print_r($cmsPageArr);echo "</pre>";
        //$this->load->view('listing_forms/new_homepage',$cmsPageArr);*/
        $this->load->view('consultants/studyAbroadCMSHome',$displayData);
        }
}
?>
